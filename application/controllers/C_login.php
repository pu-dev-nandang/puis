
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Nandang
 * Date: 12/20/2017
 * Time: 1:41 PM
 */
include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_login extends CI_Controller {
    public $GlobalVariableAdi = array('url_registration' => 'http://10.1.10.230/registeronline/');
    function __construct()
    {
        parent::__construct();
        $this->load->library('JWT');
        $this->load->library('google');
        $this->load->model('m_auth');


    }

    public function temp($content){

        $data['include'] = $this->load->view('template/include','',true);
        $data['content'] = $content;


        $this->load->view('template/blank',$data);
    }

    public function index()
    {
        $data['loginURL'] = $this->google->loginURL();
        $content = $this->load->view('auth/login',$data,true);
        $this->temp($content);
    }

    public function authGoogle(){
        if(isset($_GET['code'])){

            try{
                //authenticate user
                $this->google->getAuthenticate();

                //get user info from google
                $gpInfo = $this->google->getUserInfo();

                //preparing data for database insertion
                $userData['oauth_provider'] = 'google';
                $userData['oauth_uid'] 		= $gpInfo['id'];
                $userData['first_name'] 	= $gpInfo['given_name'];
                $userData['last_name'] 		= $gpInfo['family_name'];
                $userData['email'] 			= $gpInfo['email'];
                $userData['gender'] 		= !empty($gpInfo['gender'])?$gpInfo['gender']:'';
                $userData['locale'] 		= !empty($gpInfo['locale'])?$gpInfo['locale']:'';
                $userData['profile_url'] 	= !empty($gpInfo['link'])?$gpInfo['link']:'';
                $userData['picture_url'] 	= !empty($gpInfo['picture'])?$gpInfo['picture']:'';


                // Cek Userdata
                $dataUser = $this->m_auth->__getUserByEmailPU($userData['email'] );

                if(count($dataUser)>0) {
                    $this->setSession($dataUser[0]['ID'],$dataUser[0]['NIP']);
                    redirect(base_url('dashboard'));
                } else {
                    redirect(base_url());
                }

            } catch (Exception $err){
                redirect(base_url());
            }


        }
    }

    public function authUserPassword(){
        $token = $this->input->post('token');
        $key = "L06M31N";
        $data_arr = (array) $this->jwt->decode($token,$key);

        if(count($data_arr)>0){

            $NIP = $data_arr['nip'];
            $Password = $this->genratePassword($NIP,$data_arr['password']);

            $dataUser = $this->m_auth->__getauthUserPassword($NIP,$Password);

            if(count($dataUser)>0){
                $this->setSession($dataUser[0]['ID'],$dataUser[0]['NIP']);
                return print_r(1);
            } else {
                return print_r(0);
            }
        } else {
            return print_r(0);
        }
    }

    public function callback()
    {
        include_once APPPATH.'third_party/bni/BniEnc.php';
        // FROM BNI
        $client_id = '00202';
        $secret_key = '8ef738df0433c674e6663f3f7f5e6b68';

        // URL utk simulasi pembayaran: http://dev.bni-ecollection.com/


        $data = file_get_contents('php://input');

        $data_json = json_decode($data, true);

        if (!$data_json) {
            // handling orang iseng
            echo '{"status":"999","message":"jangan iseng :D"}';
        }
        else {
            if ($data_json['client_id'] === $client_id) {
                $data_asli = BniEnc::decrypt(
                    $data_json['data'],
                    $client_id,
                    $secret_key
                );

                if (!$data_asli) {
                    // handling jika waktu server salah/tdk sesuai atau secret key salah
                    echo '{"status":"999","message":"waktu server tidak sesuai NTP atau secret key salah."}';
                }
                else {
                  // content berhasil
                  $this->load->model('master/m_master');
                  $this->load->model('finance/m_finance');
                  $this->load->model('m_sendemail');
                  $BNIdbLog = $this->m_master->caribasedprimary('db_va.va_log','trx_id',$data_asli['trx_id']);
                  $routes_table =$BNIdbLog[0]['routes_table'];
                  switch ($routes_table) {
                      case 'db_admission.register':
                             if ($BNIdbLog[0]['Status'] == 0) {
                                  $getData = $this->m_master->caribasedprimary('db_admission.register','BilingID',$data_asli['trx_id']);
                                  $Email = $getData[0]['Email'];
                                  $RegisterID = $getData[0]['ID'];
                                  $this->m_master->saveDataToVerification_offline($RegisterID);
                                  $getData = $this->m_master->caribasedprimary('db_admission.register_verification','RegisterID',$RegisterID);
                                  $RegVerificationID = $getData[0]['ID'];
                                  $FormulirCode = $this->m_finance->getFormulirCode('online');
                                  // save data to register_verified
                                  $this->m_master->saveDataRegisterVerified($RegVerificationID,$FormulirCode);

                                  $text = 'Dear Candidate,<br><br>
                                              Your payment has been received,<br>
                                              Please click link below to login your portal <br>
                                              '.$this->GlobalVariableAdi['url_registration']."login/".'
                                          ';        
                                  $to = $Email;
                                  $subject = "Podomoro University Link Formulir Registration";
                                  $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                                  $this->m_master->update_va_log($data_asli);
                              }
                              echo '{"status":"000"}';
                              exit;
                          break;
                          case 'db_finance.payment_pre':
                                  $getData = $this->m_master->caribasedprimary('db_admission.register','VA_number',$BNIdbLog[0]['virtual_account']);
                                  $Email = $getData[0]['Email'];
                                  $RegisterID = $getData[0]['ID'];
                                  $Name = $getData[0]['Name'];

                                  $getData2 = $this->m_master->caribasedprimary('db_finance.payment_pre','BilingID',$data_asli['trx_id']);
                                  $ID_register_formulir = $getData2[0]['ID_register_formulir'];

                                  if ($getData2[0]['Status'] == 0) {
                                       //update data telah dibayar
                                       $this->m_finance->update_payment_pre($data_asli['trx_id']);

                                       // proses cicilan atau generate va kembali
                                       $p = $this->m_finance->proses_cicilan($ID_register_formulir,$getData);

                                       $text = 'Dear '.$Name.',<br><br>
                                                   Your payment has been received,<br>
                                                   as much as Rp '.number_format($getData2[0]['Invoice'],2,',','.').'
                                                   <br>'.$p.'
                                               ';        
                                       $to = $Email;
                                       $subject = "Podomoro University Payment thank you";
                                       $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                                       $this->m_master->update_va_log($data_asli);
                                   } 
                                  echo '{"status":"000"}';
                                  exit;
                              break;
                              case 'db_finance.payment_students':

                                  // Get Status sudah bayar atau belum karena BNi hit lebih dari satu kali
                                  $getData = $this->m_master->caribasedprimary('db_finance.payment_students','BilingID',$data_asli['trx_id']);
                                  if ($getData[0]['Status'] == 0) {
                                    // get Informasi Mahasiswa
                                       $GetPayment = $this->m_master->caribasedprimary('db_finance.payment','ID',$getData[0]['ID_payment']);
                                       $NPM = $GetPayment[0]['NPM'];
                                       $data = $this->m_master->PaymentgetMahasiswaByNPM($NPM);
                                       $PTIDDesc = $data['PTIDDesc'];
                                       $SemesterName = $data['SemesterName'];
                                       $Nama = $data['Nama'];
                                       $EmailPU = $data['EmailPU'];
                                       $ProdiEng = $data['ProdiEng'];

                                    // Buat Update dan View Node JS untuk notifikasi
                                    $this->m_finance->update_payment_MHS($data_asli['trx_id'],$getData[0]['ID_payment']); 

                                    // Send Email
                                    $text = 'Dear '.$Nama.',<br><br>
                                                Your payment has been received,<br><br>
                                                Payment Type : '.$PTIDDesc.'<br>
                                                Prodi : '.$ProdiEng.'<br>
                                                SemesterName : '.$SemesterName.'<br><br>
                                                as much as Rp '.number_format($getData[0]['Invoice'],2,',','.').'
                                                <br>
                                            ';        
                                    $to = $EmailPU;
                                    $subject = "Podomoro University Payment thank you";
                                    $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                                    $this->m_master->update_va_log($data_asli);
                                    $this->m_master->saveNotification($data);

                                    // send notifikasi
                                    $client = new Client(new Version1X('//10.1.10.230:3000'));

                                    $client->initialize();
                                    // send message to connected clients
                                    $client->emit('update_notifikasi', ['update_notifikasi' => '1']);
                                    $client->close();
                                    
                                  }
                                  echo '{"status":"000"}';
                                  exit;
                              break;
                      default:
                          # code...
                          break;
                  }
                  
                }
            }
        }
    }

    public function testadi2()
    {
        $content = $this->load->view('page/finance/script','',true);
        echo $content;
    }

    private function setSession($ID,$NIP){

        $dataSession = $this->m_auth->__getUserAuth($ID,$NIP);
        $timePerCredits = $this->m_auth->__getTimePerCredits();

        $ruleUser = $this->m_auth->__getRuleUser($NIP);

        // Super Divisi -- Lihat ID Di table db_employees.division
        // 1 (Yayasan), 2 (Rectore) , 12 (IT)
        $superDivision = [1,2,12];

        $setSession = array(
            'ID'  => $dataSession[0]['ID'],
            'NIP'  => $dataSession[0]['NIP'],
            'Name'  => $dataSession[0]['Name'],
            'FullNameTitle'  => $dataSession[0]['TitleAhead'].' '.$dataSession[0]['Name'].' '.$dataSession[0]['TitleBehind'],
            'Email'  => $dataSession[0]['Email'],
            'EmailPU'  => $dataSession[0]['EmailPU'],
            'Address'  => $dataSession[0]['Address'],
            'Photo'  => $dataSession[0]['Photo'],
            'PositionMain'  => array(
                'IDDivision' => $dataSession[0]['IDDivision'],
                'Division' => $dataSession[0]['Division'],
                'IDPosition' => $dataSession[0]['IDPosition'],
                'Position' => $dataSession[0]['Position']
            ),
            'PositionOther1' => array(
                'IDDivisionOther1' => $dataSession[0]['IDDivisionOther1'],
                'DivisionOther1' => $dataSession[0]['DivisionOther1'],
                'IDPositionOther1' => $dataSession[0]['IDPositionOther1'],
                'PositionOther1' => $dataSession[0]['PositionOther1']
            ),
            'PositionOther2' => array(
                'IDDivisionOther2' => $dataSession[0]['IDDivisionOther2'],
                'DivisionOther2' => $dataSession[0]['DivisionOther2'],
                'IDPositionOther2' => $dataSession[0]['IDPositionOther2'],
                'PositionOther2' => $dataSession[0]['PositionOther2']
            ),
            'PositionOther3' => array(
                'IDDivisionOther3' => $dataSession[0]['IDDivisionOther3'],
                'DivisionOther3' => $dataSession[0]['DivisionOther3'],
                'IDPositionOther3' => $dataSession[0]['IDPositionOther3'],
                'PositionOther3' => $dataSession[0]['PositionOther3']
            ),
            'timePerCredits' => $timePerCredits['time'],
            'ruleUser' => $ruleUser,
            'menuDepartement' => (count($ruleUser)>1) ? false : true ,
            'departementNavigation' => $dataSession[0]['MenuNavigation'],
            'IDdepartementNavigation' => $dataSession[0]['IDDivision'],
            'loggedIn' => true
        );

        $this->session->set_userdata($setSession);
    }

    private function genratePassword($NIP,$Password){

        $plan_password = $NIP.''.$Password;
        $pas = md5($plan_password);
        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

        return $pass;
    }



    public function logMeOut(){
        $this->session->sess_destroy();
        return 1;
    }

    public function gen($NIP,$Password){
        $plan_password = $NIP.''.$Password;
        $pas = md5($plan_password);
        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

        print_r($pass);
    }


    public function genratePassword2($NIP,$Password){

        $plan_password = $NIP.''.$Password;
        $pas = md5($plan_password);
        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

        print_r($pass);
    }

    public function sendmail($to='it@podomorouniversity.ac.id'){
        $ci = get_instance();

        $config['protocol'] = "smtp";
        $config['smtp_host'] = "mail.smtp2go.com";
        $config['smtp_port'] = "2525";
        $config['smtp_user'] = "email.insw@gmail.com";
        $config['smtp_pass'] = "WySUCXipf0Pw";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $ci->load->library('email',$config);
//        $ci->email->initialize($config);
        $mesg = '
        <div style="margin:0;padding:10px 0;background-color:#ebebeb;font-size:14px;line-height:20px;font-family:Helvetica,sans-serif;width:100%;text-align:center">
            <div class="adM">
                <br>
            </div>
            <table style="width:600px;margin:0 auto;background-color:#ebebeb" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td></td>
                        <td style="background-color:#fff;padding:0 30px;color:#333;vertical-align:top">
                            <br>
                            <div style="font-family:Proxima Nova Semi-bold,Helvetica,sans-serif;font-weight:bold;font-size:24px;line-height:24px;color:#2196f3">
                                HORE BERHASIL KIRIM EMAIL HTML
                            </div>
                            <div style="font-family:Proxima Nova Reg,Helvetica,sans-serif">
                                <div style="max-width:600px;margin:30px 0;display:block;font-size:14px;text-align:left!important">
                                    Hai Nandang Mulyadi,<br><br>

                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                    <br/>
                                    <a href="https://gist.github.com/ndang17/a787a7d4ef571753b04e551af95c4903" style="text-decoration:none;color:#fff;background-color:#337ab7;border:0;line-height:2;font-weight:bold;margin-right:10px;text-align:center;display:inline-block;border-radius:3px;padding:6px 12px;font-size:14px" target="_blank">Log Me In</a>
                                    <br><br>
                                    Atau klik link di bawah ini :
                                    <br>
                                    <a href="https://gist.github.com/ndang17/a787a7d4ef571753b04e551af95c4903" target="_blank">https://gist.github.com/ndang17/a787a7d4ef571753b04e551af95c4903</a>
                                    <br><br><br>
                                    <p style="color:#EB6936;"><i>*) Jangan dibalas, e-mail ini dikirim secara otomatis</i> </p>

                                </div>

                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div style="background-color:#fff;border-top:1px solid #ddd; ">
                                    <p style="color:#b7b0b0;font-size:0.9em;padding-bottom:10px;">NANDANG MULYADI
                                    </p>

                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
        ';


        $ci->email->from('dari@gmail.com', 'Dari');
        //------------ Kirim ke beberapa email -------
//        $list = array('mail_1@gmail.com','mail_2@yahoo.com','mail_3@email_apa_aja.com');
//        $ci->email->to($list);
        //--------------------------------------------
        $ci->email->to($to);

        $ci->email->subject('TES EMAIL CUY');
        $ci->email->message(''.$mesg);
        $ci->email->send();
    }

}
