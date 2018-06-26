<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_finance extends Finnance_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement();
        $this->load->model('finance/m_finance');
        $this->load->model('m_sendemail');
        $this->load->model('admission/m_admission');
        $this->load->model('master/m_master');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $content = "test";
        $this->temp($content);
    }

    public function formulir_registration_online_page()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/penerimaan_pembayaran/formulir_registration_online_page',$this->data,true);
        $this->temp($content);
    }

    public function formulir_registration_offline_page()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/penerimaan_pembayaran/formulir_registration_offline_page',$this->data,true);
        $this->temp($content);
    }

    public function confirmed_verfikasi_pembayaran_registration_online()
    {
        $input = $this->getInputToken();
        $arrdata = $input['arrdata'];
        $getEmailnURL = $this->getEmailnURL($arrdata);
        $SendEmail = $this->SendEmailToCandidate($getEmailnURL);
        $saveData = $this->SaveDataVerification($arrdata);
        //return print_r(json_encode($this->data));
    }

    public function getEmailnURL($arrdata)
    {
        $arr = explode(",", $arrdata);
        return $getEmailnURL = $this->m_finance->getEmailnURLCheckbox($arr,";");
        
    }

    public function SendEmailToCandidate($arr_email_url)
    {
        for ($i=0; $i < count($arr_email_url); $i++) {
            if ($arr_email_url[$i]['email'] != "nothing") {
                /*$text = 'Dear Candidate,<br><br>
                            Please click link below to get <strong>Formulir Registration</strong> :<br>
                            '.$this->GlobalVariableAdi['url_registration']."formulir-registration/".$arr_email_url[$i]['url'].'
                        ';*/
                $text = 'Dear Candidate,<br><br>
                            Please click link below to login your portal <br>
                            '.$this->GlobalVariableAdi['url_registration']."login/".'
                        ';        
                $to = $arr_email_url[$i]['email'];
                $subject = "Link Formulir Registration Podomoro University";
                $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                /*if ($sendEmail['status'] == 0) {
                    var_dump($sendEmail['msg']);
                }*/
            }
        }
    }

    public function SaveDataVerification($arrdata)
    {
        $this->m_finance->ProcessSaveDataVerification($arrdata);
    }

    public function nilai_rapor_page()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/approved/nilai_rapor_page',$this->data,true);
        $this->temp($content);
    }

    public function loaddata_nilai_calon_mahasiswa_verified($page = null)
    {
         $input =  $this->getInputToken();
         $Nama = $input['Nama'];
         $selectProgramStudy = $input['selectProgramStudy'];
         $Sekolah = $input['Sekolah'];
         $this->load->library('pagination');
         $config = $this->config_pagination_default_ajax(1000,25,4);
         $this->pagination->initialize($config);
         $page = $this->uri->segment(4);
         $start = ($page - 1) * $config["per_page"];
         $this->data['url_registration'] = $this->GlobalVariableAdi['url_registration'];
         $this->data['datadb'] = $this->m_finance->loadData_calon_mahasiswa_created($config["per_page"], $start,$Nama,$selectProgramStudy,$Sekolah);
         $this->data['mataujian'] = $this->m_admission->select_mataUjian($selectProgramStudy);
         $this->data['grade'] = $this->m_admission->showData('db_academic.grade');
        $this->data['no'] = $start + 1;
        $this->data['chkActive'] = 1;
        $content = $this->load->view('page/'.$this->data['department'].'/approved/table_nilai_calon_mahasiswa',$this->data,true);

         $output = array(
         'pagination_link'  => $this->pagination->create_links(),
         'loadtable'   => $content,
         );
         echo json_encode($output);
    }

    public function submit_approved_nilai_rapor()
    {
        $input = $this->getInputToken();
        $this->m_finance->submit_approved_nilai_rapor($input['chkValue']);

        $text = 'Dear Team,<br><br>
                    Finance Team telah approve nilai rapor kandidat mahasiswa<br>
                    Silahkan cek pada portal Anda.
                ';
        $query = $this->m_master->caribasedprimary('db_admission.email_to','Function','Admisi');             
        $to = $query[0]['EmailTo'];
        $subject = "Podomoro University Notify";
        $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

        echo json_encode( array('msg' => 'Data berhasil disimpan') );
    }

    public function tuition_fee()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/approved/tuition_fee',$this->data,true);
        $this->temp($content);
    }

    public function tuition_fee_approve($page = null)
    {
        $this->load->library('pagination');
        $config = $this->config_pagination_default_ajax(1000,5,5);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(5);
        $start = ($page - 1) * $config["per_page"];

        $this->data['payment_type'] = json_encode($this->m_master->showData_array('db_finance.payment_type'));
        $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsTuitionFee_delete($config["per_page"], $start));
        $content = $this->load->view('page/'.$this->data['department'].'/approved/page_tuition_fee_approve',$this->data,true);
        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $content,
        );
        echo json_encode($output);
    }

    public function approve_save()
    {
        $input = $this->getInputToken();
        $this->m_finance->set_tuition_fee_approve($input);
    }
    public function tuition_fee_approved()
    {
        $this->load->library('pagination');
        $config = $this->config_pagination_default_ajax(1000,15,5);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(5);
        $start = ($page - 1) * $config["per_page"];

        $this->data['payment_type'] = json_encode($this->m_master->showData_array('db_finance.payment_type'));
        $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsTuitionFee_approved($config["per_page"], $start));
        $content = $this->load->view('page/'.$this->data['department'].'/approved/page_tuition_fee_approved',$this->data,true);
        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $content,
        );
        echo json_encode($output);
    }

    public function page_set_tagihan_mhs()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/set_tagihan/page_set_tagihan_mhs',$this->data,true);
        $this->temp($content);
    }

    public function get_tagihan_mhs()
    {
        $input = $this->getInputToken();

        $this->load->library('pagination');
        $config = $this->config_pagination_default_ajax(1000,20,3);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(3);
        $start = ($page - 1) * $config["per_page"];

        $data = $this->m_finance->get_tagihan_mhs($input['ta'],$input['prodi'],$input['PTID'],$input['NPM'],$config["per_page"], $start);
        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $data,
        );
        echo json_encode($output);
    }

    public function submit_tagihan_mhs()
    {
        $msg = '';
        $Input = $this->getInputToken();
        $Input = $Input['arrValueCHK'];
        /*print_r($Input[0]->PTID);
        die();*/
        $countSuccess = 0;
        $countSuccessVA = 1;
        // create va
            $SemesterID = $this->m_master->caribasedprimary('db_academic.semester','Status',1);
            for ($i=0; $i < count($Input); $i++) { 
                // get Deadline
                $fieldEND = 'bayarBPPEnd';
                switch ($Input[$i]->PTID) {
                    case '1':
                        $fieldEND = '';
                        break;
                    case '2':
                        $fieldEND = 'bayarBPPEnd';
                        break;
                    case '3':
                        $fieldEND = 'bayarEnd';
                        break;    
                    default:
                        $fieldEND = '';
                        break;
                }

                if ($fieldEND != '')
                {
                    $getDeadlineTagihanDB = $this->m_finance->getDeadlineTagihanDB($fieldEND,$SemesterID[0]['ID']);
                    // check Deadline Tagihan telah melewati tanggal sekarang atau belum
                    $chkTgl = $this->m_master->checkTglNow($getDeadlineTagihanDB);
                    if ($chkTgl) {
                        $getDataMhsBYNPM = $this->m_master->getDataMhsBYNPM($Input[$i]->NPM,$Input[$i]->ta);
                        $payment = str_replace("Rp.","", $Input[$i]->Invoice);
                        $payment = trim(str_replace(",-","", $payment));
                        $payment = trim(str_replace(".","", $payment));
                        $DeadLinePayment = $getDeadlineTagihanDB.' 23:59:00';

                        // proses langsung pembayaran gratis atau = 0
                        if ($payment == 0) {
                            $aa = $this->m_finance->insertaDataPayment($Input[$i]->PTID,$Input[$i]->semester,$Input[$i]->NPM,$payment,$Input[$i]->Discount,"1",0);
                            $ab = $this->m_finance->insertaDataPaymentStudents($aa,$payment,0,$DeadLinePayment,1);
                        }
                        else
                        {
                            $Name = $getDataMhsBYNPM[0]['Name'];
                            $Email = $getDataMhsBYNPM[0]['EmailPU'];
                            $VA_number = $this->m_finance->getVANumberMHS($Input[$i]->NPM);
                            $create_va = $this->m_finance->create_va_Payment($payment,$DeadLinePayment, $Name, $Email,$VA_number,$description = $fieldEND,$tableRoutes = 'db_finance.payment_students');
                            if ($create_va['status']) {
                                // After create va insert data to db_finance.payment  and db_finance.payment_students
                                $countSuccessVA++;
                                $aa = $this->m_finance->insertaDataPayment($Input[$i]->PTID,$Input[$i]->semester,$Input[$i]->NPM,$payment,$Input[$i]->Discount);
                                $ab = $this->m_finance->insertaDataPaymentStudents($aa,$payment,$create_va['msg']['trx_id'],$create_va['msg']['datetime_expired']);
                            }
                            else
                            {
                                $msg .= 'Tidak bisa Create VA dengan Nama : '.$Name.' dan NPM : '.$Input[$i]->NPM.'<br>';
                            }
                        }
                    }
                    else
                    {
                        $msg = 'Tanggal Deadline telah melewati tanggal sekarang. <br> Data tidak dapat di Proses';
                    }

                    
                }
                
            }
            echo json_encode($msg);

    }

    public function page_cek_tagihan_mhs($page = '')
    {
        $this->data['NPM'] = $page;
        $content = $this->load->view('page/'.$this->data['department'].'/set_tagihan/page_cek_tagihan_mhs',$this->data,true);
        $this->temp($content);
    }

    public function get_created_tagihan_mhs($page = null)
    {
        $input = $this->getInputToken();
        $this->load->library('pagination');
        $config = $this->config_pagination_default_ajax(1000,10,3);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(3);
        $start = ($page - 1) * $config["per_page"];
        $data = $this->m_finance->get_created_tagihan_mhs($input['ta'],$input['prodi'],$input['PTID'],$input['NIM'],$config["per_page"], $start);
        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $data,
        );
        echo json_encode($output);
    }

    public function approved_created_tagihan_mhs()
    {
        $Input = $this->getInputToken();
        $Input = $Input['arrValueCHK'];
        $this->m_finance->updatePaymentApprove($Input);
        
    }

    public function unapproved_created_tagihan_mhs()
    {
        $Input = $this->getInputToken();
        $Input = $Input['arrValueCHK'];
        $proses = $this->m_finance->updatePaymentunApprove($Input);
        echo json_encode($proses);
    }

    public function cancel_tagihan_mhs()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/set_tagihan/page_cancel_tagihan_mhs',$this->data,true);
        $this->temp($content);
    }

    public function cancel_created_tagihan_mhs()
    {
        $Input = $this->getInputToken();
        $Input = $Input['arrValueCHK'];
        $proses = $this->m_finance->cancel_created_tagihan_mhs($Input);
        echo json_encode($proses);
    }

}
