
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
                                              '.url_registration."login/".'
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

                                  // find data apakah memiliki beberapa cicilan
                                  $ID_payment = $getData[0]['ID_payment'];
                                  $getData3 = $this->m_finance->findDatapayment_studentsBaseID_payment($ID_payment);
                                  if (count($getData3) > 0) {
                                    // create va selanjutnya
                                    $GetPayment = $this->m_master->caribasedprimary('db_finance.payment','ID',$getData[0]['ID_payment']);
                                    $NPM = $GetPayment[0]['NPM'];
                                    $data = $this->m_master->PaymentgetMahasiswaByNPM($NPM);
                                    $Nama = $data['Nama'];
                                    $EmailPU = $data['EmailPU'];
                                    $payment = $getData3[0]['Invoice'];

                                    $deli = strpos($payment, '.');
                                    $payment = substr($payment, 0,$deli);

                                    $DeadLinePayment = $getData3[0]['Deadline'];
                                    $VA_number = $this->m_finance->getVANumberMHS($NPM);
                                    $create_va = $this->m_finance->create_va_Payment($payment,$DeadLinePayment, $Nama, $EmailPU,$VA_number,'Cicilan',$tableRoutes = 'db_finance.payment_students');

                                    if ($create_va['status']) {
                                        // After create va update payment students
                                        $ab = $this->m_finance->updatePaymentStudentsFromCicilan($create_va['msg']['trx_id'],$getData3[0]['ID']);
                                        // Send Email
                                         $msg = 'Please continue to pay the next installment with VA Number : '.$VA_number. ' <br> as much as Rp '.number_format($getData3[0]['Invoice'],2,',','.');
                                        $text = 'Dear '.$Nama.',<br><br>
                                                '.$msg.'    
                                                ';        
                                        $to = $EmailPU;
                                        $subject = "Podomoro University Notification";
                                        $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                                    }
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



    private function genratePassword($Username,$Password){

        $plan_password = $Username.''.$Password;
        $pas = md5($plan_password);
        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

        return $pass;
    }



    public function logMeOut(){

        $this->session->sess_destroy();
        return 1;
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


    // ========= LOGIN SSO =========

    public function checkUsername(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        $token = $this->input->post('token');
        $key = "L0G1N-S50-3R0";
        $data_arr = (array) $this->jwt->decode($token,$key);

        $dataStudents = $this->db->query('SELECT * FROM db_academic.auth_students
                                                  WHERE NPM = "'.$data_arr['Username'].'" LIMIT 1')->result_array();

        if(count($dataStudents)>0){

            $dataMhs = $this->get_dataStd($dataStudents[0]['Year'],$dataStudents[0]['NPM']);

            $DataUser = array(
                'Name' => $dataMhs['Name'],
                'Username' => $dataMhs['NPM'],
                'User' => 'Students',
                'Year' => $dataStudents[0]['Year'],
                'Status' => $dataStudents[0]['Status'],
                'PathPhoto' => base_url().'uploads/students/ta_'.$dataStudents[0]['Year'].'/'.$dataMhs['Photo']
            );
            $result = array(
                'DataUser' => $DataUser,
                'Status' => '1',
                'Message' => 'User Exist'
            );

        }
        else {

            // Cek Apakah karyawan atau bukan
            $dataEmploy = $this->db->get_where('db_employees.employees',
                    array('NIP' => $data_arr['Username']),1)->result_array();

            if(count($dataEmploy)>0){
                $DataUser = array(
                    'Name' => $dataEmploy[0]['TitleAhead'].' '.$dataEmploy[0]['Name'].' '.$dataEmploy[0]['TitleBehind'],
                    'Username' => $dataEmploy[0]['NIP'],
                    'User' => 'Employees',
                    'Year' => 0,
                    'Status' => $dataEmploy[0]['Status'],
                    'PathPhoto' => base_url().'uploads/employees/'.$dataEmploy[0]['Photo']
                );
                $result = array(
                    'DataUser' => $DataUser,
                    'Status' => '1',
                    'Message' => 'User Exist'
                );
            } else {
                $result = array(
                    'Status' => '0',
                    'Message' => 'User Not Exist'
                );
            }


        }

        return print_r(json_encode($result));
    }


    public function checkPassword(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        $token = $this->input->post('token');
        $key = "L0G1N-S50-3R0";
        $data_arr = (array) $this->jwt->decode($token,$key);

        if($data_arr['User']=='Students'){
//            $db_ = 'ta_'.$data_arr['Year'];
            if($data_arr['Status']=='-1'){
                $dataMhs = $this->db->get_where('db_academic.auth_students',
                        array('NPM' => $data_arr['Username'],'Password_Old' => md5($data_arr['Password'])),1)
                    ->result_array();

                if(count($dataMhs)>0){
                    $dataMhsDetail = $this->get_dataStd($dataMhs[0]['Year'],$dataMhs[0]['NPM']);

                    $DataUser = array(
                        'Name' => $dataMhsDetail['Name'],
                        'Username' => $dataMhsDetail['NPM'],
                        'User' => 'Students',
                        'Year' => $dataMhs[0]['Year'],
                        'Status' => $dataMhs[0]['Status'],
                        'LastPassword' => $dataMhs[0]['Password_Old'],
                        'PathPhoto' => base_url().'uploads/students/ta_'.$dataMhs[0]['Year'].'/'.$dataMhsDetail['Photo']
                    );

                    $result = array(
                        'DataUser' => $DataUser,
                        'Status' => -1,
                        'Message' => 'Pleace, change your password'
                    );
                } else {
                    $result = array(
                        'Status' => 0,
                        'Message' => 'Password is wrong'
                    );
                }

            }
            else if($data_arr['Status']=='1'){
                $pass = $this->genratePassword($data_arr['Username'],$data_arr['Password']);
                $dataMhs = $this->db->get_where('db_academic.auth_students',
                    array('NPM' => $data_arr['Username'],'Password' => $pass),1)
                    ->result_array();

                if(count($dataMhs)>0){

                    $logon = $this->loadData_UserLogin('Students',$dataMhs[0]['Year'],$data_arr['Username']);
                    $result = array(
                        'Status' => 1,
                        'Message' => 'Login success',
                        'url_direct' => $logon['url_direct']
                    );
//                    $result = array(
//                        'Status' => 1,
//                        'Message' => 'Login Success',
//                        'url_direct' => url_students
//                    );
                } else {
                    $result = array(
                        'Status' => 0,
                        'Message' => 'Password is wrong'
                    );
                }
            }
        }

        else if($data_arr['User']=='Employees') {
            if($data_arr['Status']=='-1'){
                $dataEm = $this->db->get_where('db_employees.employees',
                    array(
                        'NIP' => $data_arr['Username'],
                        'Password_Old' => md5($data_arr['Password'])))->result_array();

                if(count($dataEm)>0){
                    $DataUser = array(
                        'Name' => $dataEm[0]['TitleAhead'].' '.$dataEm[0]['Name'].' '.$dataEm[0]['TitleBehind'],
                        'Username' => $dataEm[0]['NIP'],
                        'User' => 'Employees',
                        'Year' => 0,
                        'Status' => $dataEm[0]['Status'],
                        'PathPhoto' => base_url().'uploads/employees/'.$dataEm[0]['Photo']
                    );

                    $result = array(
                        'DataUser' => $DataUser,
                        'Status' => -1,
                        'Message' => 'Pleace, change your password'
                    );
                } else {
                    $result = array(
                        'Status' => 0,
                        'Message' => 'Password is wrong'
                    );
                }
            }
            else if($data_arr['Status']=='1'){
                $pass = $this->genratePassword($data_arr['Username'],$data_arr['Password']);
                $dataEm = $this->db->get_where('db_employees.employees',
                    array(
                        'NIP' => $data_arr['Username'],
                        'Password' => $pass))->result_array();
                if(count($dataEm)>0){
                    $logon = $this->loadData_UserLogin('Employees',0,$data_arr['Username']);
                    $result = array(
                        'Status' => 1,
                        'Message' => 'Login success',
                        'url_direct' => $logon['url_direct']
                    );
                } else {
                    $result = array(
                        'Status' => 0,
                        'Message' => 'Password is wrong'
                    );
                }
            }
        }


        return print_r(json_encode($result));

    }


    public function getAuthSSOLogin___delete(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $token = $this->input->post('token');
        $key = "L0G1N-S50-3R0";
        $data_arr = (array) $this->jwt->decode($token,$key);

        // Cek Apakah students atau bukan
        $pass = $this->genratePassword($data_arr['Username'],$data_arr['Password']);

        $dataStudents = $this->db->query('SELECT * FROM db_academic.auth_students
                                                  WHERE NPM = "'.$data_arr['Username'].'" LIMIT 1')->result_array();

        $result = [];
        if(count($dataStudents)>0){
            if($dataStudents[0]['Status']=='-1'){

                // Cek apakah password lama sama
                if(md5($data_arr['Password'])==$dataStudents[0]['Password_Old']){
                    $dataMhs = $this->get_dataStd($dataStudents[0]['Year'],$dataStudents[0]['NPM']);
                    $std = array(
                        'Name' => $dataMhs['Name'],
                        'Username' => $dataMhs['NPM'],
                        'User' => 'Students',
                        'LastPassword' => md5($data_arr['Password']),
                        'Path_Photo' => base_url().'uploads/students/ta_'.$dataStudents[0]['Year'].'/'.$dataMhs['Photo']
                    );
                    $result = array(
                        'Students' => $std,
                        'Status' => '-1',
                        'Login' => false,
                        'Message' => 'Please Change Your Password'
                    );
                } else {
                    $result = array(
                        'Status' => '-5',
                        'Login' => false,
                        'Message' => 'Old Password Not Match'
                    );
                }


            } else if($dataStudents[0]['Status']=='1'){

                // Cek Apakah Password Sama Dengan Inputan
                if($pass==$dataStudents[0]['Password']){
                    $result = array(
                        'Status' => '1',
                        'Login' => true,
                        'Message' => 'Login Success'
                    );
                } else {
                    $result = array(
                        'Status' => '-5',
                        'Login' => false,
                        'Message' => 'Password Is Wrong'
                    );
                }

            } else{
                $result = array(
                    'Status' => '0',
                    'Login' => false,
                    'Message' => 'Your Login Is Blocked'
                );
            }
        }
        else {
            $result = array(
                'Status' => '0',
                'Login' => false,
                'Message' => 'Your Login Is Blocked'
            );
        }


        return print_r(json_encode($result));

    }

    private function get_dataStd($Year,$NPM){
        $db_ = 'ta_'.$Year;
        $data = $this->db->get_where($db_.'.students', array('NPM'=>$NPM),1);

        return $data->result_array()[0];
    }


    public function updatePassword(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        
        $token = $this->input->post('token');
        $key = "L0G1N-S50-3R0";
        $data_arr = (array) $this->jwt->decode($token,$key);


        $pass = $this->genratePassword($data_arr['Username'],$data_arr['NewPassword']);
        $data = array(
            'Password' => $pass,
            'Status' => '1'
        );

        if($data_arr['User']=='Students'){
            $this->db->where('NPM', $data_arr['Username']);
            $this->db->update('db_academic.auth_students', $data);
        }
        else if($data_arr['User']=='Employees'){
            $this->db->where('NIP', $data_arr['Username']);
            $this->db->update('db_employees.employees', $data);
        }

        $result = $this->loadData_UserLogin($data_arr['User'],$data_arr['Year'],$data_arr['Username']);
        return print_r(json_encode($result));

    }


    private function loadData_UserLogin($User,$Year,$Username){
        $url_direct = [];
        if($User=='Students'){
            $this->setUserSession_Students($Year,$Username);

            $arp = array(
                'url' => url_students,
                'flag' => 'std'
            );
            array_push($url_direct,$arp);

        } else if ($User=='Employees') {
            $dataEmp = $this->db->get_where('db_employees.employees',
                array('NIP' => $Username),1)->result_array();



            $Main = explode('.',$dataEmp[0]['PositionMain']);
            if($dataEmp[0]['PositionMain']!=null && $dataEmp[0]['PositionMain']!='' && count($Main)>0){
                $urlp = ($Main[0]==14) ? url_lecturers : url_pcam ;
                $arp = array(
                    'url' => $urlp,
                    'flag' => ($Main[0]==14) ? 'lec' : 'emp'
                );
                array_push($url_direct,$arp);
            }

            $Ot1 = explode('.',$dataEmp[0]['PositionOther1']);
            if($dataEmp[0]['PositionOther1']!=null && $dataEmp[0]['PositionOther1']!='' && count($Ot1)>0){
                $urlp = ($Ot1[0]==14) ? url_lecturers : url_pcam ;
                $arp = array(
                    'url' => $urlp,
                    'flag' => ($Ot1[0]==14) ? 'lec' : 'emp'
                );
                array_push($url_direct,$arp);
            }

            $Ot2 = explode('.',$dataEmp[0]['PositionOther2']);
            if($dataEmp[0]['PositionOther2']!=null && $dataEmp[0]['PositionOther2']!='' && count($Ot2)>0){
                $urlp = ($Ot2[0]==14) ? url_lecturers : url_pcam ;
                $arp = array(
                    'url' => $urlp,
                    'flag' => ($Ot2[0]==14) ? 'lec' : 'emp'
                );
                array_push($url_direct,$arp);
            }

            $Ot3 = explode('.',$dataEmp[0]['PositionOther3']);
            if($dataEmp[0]['PositionOther3']!=null && $dataEmp[0]['PositionOther3']!='' && count($Ot3)>0){
                $urlp = ($Ot3[0]==14) ? url_lecturers : url_pcam ;
                $arp = array(
                    'url' => $urlp,
                    'flag' => ($Ot3[0]==14) ? 'lec' : 'emp'
                );
                array_push($url_direct,$arp);
            }


            if(count($url_direct)>0){
                for($s=0;$s<count($url_direct);$s++){
                    if($url_direct[$s]['flag']=='emp'){
                        $this->setSession($dataEmp[0]['ID'],$dataEmp[0]['NIP']);
                    }
                    else if($url_direct[$s]['flag']=='lec'){
                        $this->setUserSession_Lecturer($dataEmp[0]['NIP']);
                    }
                }
            }


        }

        $result = array(
            'url_direct' => $url_direct
        );



        return $result;
    }


    // ========= Session Students =========
    private function setUserSession_Students($Year,$NPM){

        $table = 'ta_'.$Year.'.students';
        $dataUser = $this->m_auth->getDataUser2LoginStudent($Year,$table,$NPM);

        $newdata = array(
            'student_NPM'  => $dataUser[0]['NPM'],
            'student_Name'  => $dataUser[0]['Name'],
            'student_Gender'  => $dataUser[0]['Gender'],
            'student_EmailPU'  => $dataUser[0]['EmailPU'],
            'student_Faculty'  => $dataUser[0]['Faculty'],
            'student_ProdiID'  => $dataUser[0]['ProdiID'],
            'student_ProdiCode'  => $dataUser[0]['ProdiCode'],
            'student_CurriculumID'  => $dataUser[0]['CurriculumID'],
            'student_ProgramCampusID'  => $dataUser[0]['ProgramID'],
            'student_SemesterID'  => $dataUser[0]['SemesterID'],
            'student_Semester'  => $dataUser[0]['SemesterNow'],
            'student_Year'  => $dataUser[0]['Year'],
            'student_SemesterCode'  => $dataUser[0]['SemesterCode'],
            'student_Photo'  => $dataUser[0]['Photo'],
            'student_StatusStudentID'  => $dataUser[0]['StatusStudentID'],
            'student_ClassOf'  => $Year,
            'student_AcademicMentor'  => $dataUser[0]['AcademicMentor'],
            'student_MentorName'  => $dataUser[0]['MentorName'],
            'student_MentorEmailPU'  => $dataUser[0]['MentorEmailPU'],
            'student_Token'  => $dataUser[0]['Token'],
            'student_DB'  => 'ta_'.$Year,
            'student_loggedIn' => TRUE
        );

        $this->session->set_userdata($newdata);
    }

    // ========= Session Lecturer =========
    private function setUserSession_Lecturer($NIP){

        $dataUser = $this->m_auth->getDataUser2LoginLecturer($NIP);

        $newdata = array(
            'lecturer_ID'  => $dataUser[0]['ID'],
            'lecturer_NIP'  => $dataUser[0]['NIP'],
            'lecturer_NIDN'  => $dataUser[0]['NIDN'],
            'lecturer_Name'  => $dataUser[0]['Name'],
            'lecturer_TitleAhead'  => $dataUser[0]['TitleAhead'],
            'lecturer_TitleBehind'  => $dataUser[0]['TitleBehind'],
            'lecturer_FacultyID'  => $dataUser[0]['FacultyID'],
            'lecturer_ProdiID'  => $dataUser[0]['ProdiID'],

            'lecturer_MainPositionID'  => $dataUser[0]['MainPositionID'],
            'lecturer_MainPosition'  => $dataUser[0]['MainPosition'],

            'lecturer_PositionOther1ID'  => $dataUser[0]['PositionOther1ID'],
            'lecturer_PositionOther1'  => $dataUser[0]['PositionOther1'],

            'lecturer_PositionOther2ID'  => $dataUser[0]['PositionOther2ID'],
            'lecturer_PositionOther2'  => $dataUser[0]['PositionOther2'],

            'lecturer_PositionOther3ID'  => $dataUser[0]['PositionOther3ID'],
            'lecturer_PositionOther3'  => $dataUser[0]['PositionOther3'],

            'lecturer_Email'  => $dataUser[0]['Email'],
            'lecturer_EmailPU'  => $dataUser[0]['EmailPU'],
            'lecturer_Token'  => $dataUser[0]['Password'],
            'lecturer_Photo'  => $dataUser[0]['Photo'],
            'lecturer_StatusEmployeeID'  => $dataUser[0]['StatusEmployeeID'],
            'lecturer_SemesterID'  => $dataUser[0]['SemesterID'],
//            'lecturer_DB'  => 'ta_'.$dataAuth['Year'],
            'lecturer_loggedIn' => TRUE
        );

        $this->session->set_userdata($newdata);
    }

    // ========= Session Employees ========
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

}
