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
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/page_set_tagihan_mhs',$this->data,true);
        $this->temp($content);
    }

    public function get_tagihan_mhs()
    {
        $input = $this->getInputToken();

        $this->load->library('pagination');
        // count all
        $count = $this->m_finance->count_get_tagihan_mhs($input['ta'],$input['prodi'],$input['PTID'],$input['NPM']);

        $config = $this->config_pagination_default_ajax($count,20,3);
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
                $fieldEND = '';
                $desc = '';
                switch ($Input[$i]->PTID) {
                    case '1':
                        $fieldEND = '';
                        $desc = '';
                        break;
                    case '2':
                        $fieldEND = 'bayarBPPEnd';
                        $desc = 'Pembayaran BPP';
                        break;
                    case '3':
                        $fieldEND = 'bayarEnd';
                        $desc = 'Pembayaran SKS';
                        break;    
                    default:
                        $fieldEND = '';
                        $desc = '';
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
                            $create_va = $this->m_finance->create_va_Payment($payment,$DeadLinePayment, $Name, $Email,$VA_number,$description = $desc,$tableRoutes = 'db_finance.payment_students');
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
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/page_cek_tagihan_mhs',$this->data,true);
        $this->temp($content);
    }

    public function get_created_tagihan_mhs($page = null)
    {
        $input = $this->getInputToken();
        $this->load->library('pagination');
        // count
        $count = $this->m_finance->count_get_created_tagihan_mhs($input['ta'],$input['prodi'],$input['PTID'],$input['NIM']);
        $config = $this->config_pagination_default_ajax($count,50,3);
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

    public function get_created_tagihan_mhs_not_approved($page = null)
    {
        $input = $this->getInputToken();
        $this->load->library('pagination');
        // count
        $count = $this->m_finance->count_get_created_tagihan_mhs_not_approved($input['ta'],$input['prodi'],$input['PTID'],$input['NIM']);
        $config = $this->config_pagination_default_ajax($count,50,3);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(3);
        $start = ($page - 1) * $config["per_page"];
        $data = $this->m_finance->get_created_tagihan_mhs_not_approved($input['ta'],$input['prodi'],$input['PTID'],$input['NIM'],$config["per_page"], $start);
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
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/page_cancel_tagihan_mhs',$this->data,true);
        $this->temp($content);
    }

    public function cancel_created_tagihan_mhs()
    {
        $Input = $this->getInputToken();
        $Input = $Input['arrValueCHK'];
        $proses = $this->m_finance->cancel_created_tagihan_mhs($Input);
        echo json_encode($proses);
    }

    public function set_cicilan_tagihan_mhs()
    {
        $max_cicilan= $this->m_master->showData_array('db_admission.cfg_cicilan');
        $this->data['max_cicilan'] = $max_cicilan[0]['max_cicilan'];
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/page_set_cicilan_tagihan_mhs',$this->data,true);
        $this->temp($content);
    }

    public function set_cicilan_tagihan_mhs_submit()
    {
        $Input = $this->getInputToken();
        $fieldEND = '';
        $bool = true;
        $DeadLinePayment = '';
        $ID = $Input[0]->ID;
        for ($i=0; $i < count($Input); $i++) { 
                        // get Deadline
                        $fieldEND = '';
                        $desc = '';
                        switch ($Input[$i]->PTID) {
                            case '1':
                                $fieldEND = '';
                                $desc = '';
                                break;
                            case '2':
                                $fieldEND = 'bayarBPPEnd';
                                $desc = 'Pembayaran BPP';
                                break;
                            case '3':
                                $fieldEND = 'bayarEnd';
                                $desc = 'Pembayaran SKS';
                                break;    
                            default:
                                $fieldEND = '';
                                $desc = '';
                                break;
                        }
            if ($DeadLinePayment == '') {
                $getDeadlineTagihanDB = $this->m_finance->getDeadlineTagihanDB($fieldEND,$Input[$i]->SemesterID);
                $DeadLinePayment = $getDeadlineTagihanDB.' 23:59:00';  
            }            
               
            // check Deadline Input telah melewati tanggal Deadline
            $aaa = $this->m_master->chkTgl($Input[$i]->Deadline,$DeadLinePayment);
            if (!$aaa) {
                $bool = false;
                break;
            }
        }

        if ($bool) {
               // cari Biling ID status = 0
               $a = $this->m_finance->findDatapayment_studentsBaseID_payment($ID);
               if(count($a) != 1)
               {
                echo json_encode('Data ini telah diset cicilan, sehingga proses dihentikan.');
                break;
               }
               else
               {
                    $now = date('Y-m-d H:i:s');
                    for ($i=0; $i < count($Input); $i++) { 
                      // update cicilan untuk array 0 atau array pertama
                      if ($i == 0) {
                        // update va existing
                        $BilingID = $a[0]['BilingID'];
                        $checkVa = $this->m_finance->checkBiling($BilingID);
                        if ($checkVa['msg']['va_status'] != 2) {
                            $getData= $this->m_master->caribasedprimary('db_va.va_log','trx_id',$BilingID);
                            $trx_amount = $Input[$i]->Payment;
                            $datetime_expired = $Input[$i]->Deadline;
                            $customer_name = $getData[0]['customer_name'];
                            $customer_email = $getData[0]['customer_email'];
                            $update = $this->m_finance->update_va_Payment($trx_amount,$datetime_expired, $customer_name, $customer_email,$BilingID,'db_finance.payment_students',$desc);
                            if ($update['status'] == 1) {
                              // update data pada table db_finance.payment_students
                                $this->m_finance->updateCicilanMHS($BilingID,$trx_amount,$datetime_expired);
                            }
                            else
                            {
                              $arr['msg'] .= 'Va tidak bisa di update, error koneksi ke BNI <br>';
                            }
                        }
                      }
                      else
                      {
                        $this->m_finance->insertaDataPaymentStudents($ID,$Input[$i]->Payment,"0",$Input[$i]->Deadline);
                      } 

                    }
                    echo json_encode(''); 
               }
        }
        else
        {
            echo json_encode('Tanggal yang anda input melewati tanggal akademik : '.$DeadLinePayment);
        }  

    }

    public function edit_cicilan_tagihan_mhs()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/page_edit_cicilan_tagihan_mhs',$this->data,true);
        $this->temp($content);
    }

    public function edit_cicilan_tagihan_mhs_submit()
    {
        $Input = $this->getInputToken();
        $msg = '';
        $proses = $this->m_finance->edit_cicilan_tagihan_mhs_submit($Input);
        $msg = $proses['msg'];
        echo json_encode($msg);

    }

    public function delete_cicilan_tagihan_mhs_submit()
    {
        $Input = $this->getInputToken();
        $msg = '';
        $proses = $this->m_finance->delete_cicilan_tagihan_mhs_submit($Input);
        $msg = $proses['msg'];
        echo json_encode($msg);
    }

    public function penerimaan_tagihan_mhs()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/penerimaan_pembayaran/penerimaan_pembayaran_mhs',$this->data,true);
        $this->temp($content);
    }

    public function get_pembayaran_mhs($page = null)
    {
        $input = $this->getInputToken();
        $this->load->library('pagination');
        $config = $this->config_pagination_default_ajax(1000,100,3);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(3);
        $start = ($page - 1) * $config["per_page"];
        $data = $this->m_finance->get_pembayaran_mhs($input['ta'],$input['prodi'],$input['PTID'],$input['NIM'],$input['Semester'],$config["per_page"], $start);
        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $data,
        );
        echo json_encode($output);
    }

    public function check_va()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/checkva/page_check_va',$this->data,true);
        $this->temp($content);
    }

    public function check_va_cari()
    {   
        $arr = array('msg' => '' , 'rs' => '');
        $Input = $this->getInputToken();
        $VA = $Input['VA'];
        // cari va desc dengan status = 0 pada table va_log
            $va_log = $this->m_finance->cari_va($VA);
            if ($va_log['msg'] == '') {
                $data = $va_log['data'];
                $arr['rs'] = $data;
            }
            else
            {
                $arr['msg'] = $va_log['msg'];
            }

        echo json_encode($arr);    
    }

    public function export_excel($token)
    {
        $key = "UAP)(*";
        $input = (array) $this->jwt->decode($token,$key);
        // print_r($input);
        $Semester = $input['Semester'];
        $Semester = explode('.', $Semester);
        $Semester = $Semester[1];
        $data = $input['Data'];
        $dataGenerate = $this->GroupingNPM($data);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/finance/TemplatePembayaran.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();
        $excel3->setCellValue('A2', 'Rekap Penerimaan & AGING '.$Semester);

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        // start dari A7
        $a = 8;
        for ($i=0; $i < count($dataGenerate); $i++) {
           $no = $i + 1;  
           $excel3->setCellValue('A'.$a, $no); 
           $excel3->setCellValue('B'.$a, $dataGenerate[$i]['Nama']);
           $excel3->setCellValue('C'.$a, $dataGenerate[$i]['NPM']);
           $excel3->setCellValue('D'.$a, $dataGenerate[$i]['ProdiEng']);
           $excel3->setCellValue('E'.$a, $dataGenerate[$i]['SPP']);
           $excel3->setCellValue('F'.$a, $dataGenerate[$i]['Another']);
           $excel3->setCellValue('G'.$a, $dataGenerate[$i]['BPP']);
           $excel3->setCellValue('H'.$a, $dataGenerate[$i]['BPPKet']);
           $excel3->setCellValue('I'.$a, $dataGenerate[$i]['Credit']);
           $excel3->setCellValue('J'.$a, $dataGenerate[$i]['CreditKet']);

           // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
           $excel3->getStyle('A'.$a)->applyFromArray($style_row);
           $excel3->getStyle('B'.$a)->applyFromArray($style_row);
           $excel3->getStyle('C'.$a)->applyFromArray($style_row);
           $excel3->getStyle('D'.$a)->applyFromArray($style_row);
           $excel3->getStyle('E'.$a)->applyFromArray($style_row);
           $excel3->getStyle('F'.$a)->applyFromArray($style_row);
           $excel3->getStyle('G'.$a)->applyFromArray($style_row);
           $excel3->getStyle('H'.$a)->applyFromArray($style_row);
           $excel3->getStyle('I'.$a)->applyFromArray($style_row);
           $excel3->getStyle('J'.$a)->applyFromArray($style_row);
           $excel3->getStyle('K'.$a)->applyFromArray($style_row);

           $a = $a + 1; 
        }

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        // We'll be outputting an excel file  
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        // It will be called file.xlss
        header('Content-Disposition: attachment; filename="file.xlsx"'); // jalan ketika tidak menggunakan ajax
        //$filename = 'PenerimaanPembayaran.xlsx';
        //$objWriter->save('./document/'.$filename);
        $objWriter->save('php://output'); // jalan ketika tidak menggunakan ajax

    }

    /*public function export_excel()
    {
        $input = $this->getInputToken();
        // print_r($input);
        $Semester = $input['Semester'];
        $Semester = explode('.', $Semester);
        $Semester = $Semester[1];
        $data = $input['Data'];
        $dataGenerate = $this->GroupingNPM($data);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/finance/TemplatePembayaran.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();
        $excel3->setCellValue('A2', 'Rekap Penerimaan & AGING '.$Semester);

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        // start dari A7
        $a = 8;
        for ($i=0; $i < count($dataGenerate); $i++) {
           $no = $i + 1;  
           $excel3->setCellValue('A'.$a, $no); 
           $excel3->setCellValue('B'.$a, $dataGenerate[$i]['Nama']);
           $excel3->setCellValue('C'.$a, $dataGenerate[$i]['NPM']);
           $excel3->setCellValue('D'.$a, $dataGenerate[$i]['ProdiEng']);
           $excel3->setCellValue('E'.$a, $dataGenerate[$i]['SPP']);
           $excel3->setCellValue('F'.$a, $dataGenerate[$i]['Another']);
           $excel3->setCellValue('G'.$a, $dataGenerate[$i]['BPP']);
           $excel3->setCellValue('H'.$a, $dataGenerate[$i]['BPPKet']);
           $excel3->setCellValue('I'.$a, $dataGenerate[$i]['Credit']);
           $excel3->setCellValue('J'.$a, $dataGenerate[$i]['CreditKet']);

           // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
           $excel3->getStyle('A'.$a)->applyFromArray($style_row);
           $excel3->getStyle('B'.$a)->applyFromArray($style_row);
           $excel3->getStyle('C'.$a)->applyFromArray($style_row);
           $excel3->getStyle('D'.$a)->applyFromArray($style_row);
           $excel3->getStyle('E'.$a)->applyFromArray($style_row);
           $excel3->getStyle('F'.$a)->applyFromArray($style_row);
           $excel3->getStyle('G'.$a)->applyFromArray($style_row);
           $excel3->getStyle('H'.$a)->applyFromArray($style_row);
           $excel3->getStyle('I'.$a)->applyFromArray($style_row);
           $excel3->getStyle('J'.$a)->applyFromArray($style_row);
           $excel3->getStyle('K'.$a)->applyFromArray($style_row);

           $a = $a + 1; 
        }

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        // We'll be outputting an excel file  
        // header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        // It will be called file.xlss
        // header('Content-Disposition: attachment; filename="file.xls"'); // jalan ketika tidak menggunakan ajax
        $filename = 'PenerimaanPembayaran.xlsx';
        $objWriter->save('./document/'.$filename);
        // $objWriter->save('php://output'); // jalan ketika tidak menggunakan ajax
        echo json_encode($filename);
    }*/

    public function import_price_list_mhs()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/page_import_price_list_mhs',$this->data,true);
        $this->temp($content);
    }

    public function submit_import_price_list_mhs()
    {
        // print_r($_FILES);
        if(isset($_FILES["fileData"]["name"]))
        {
          $path = $_FILES["fileData"]["tmp_name"];
          $arr_insert = array();
          include APPPATH.'third_party/PHPExcel/PHPExcel.php';
          $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
          $excel2 = $excel2->load($path); // Empty Sheet
          $objWorksheet = $excel2->setActiveSheetIndex(0);
          $CountRow = $objWorksheet->getHighestRow();
          $CountRow = $CountRow + 1;
          $Pay_Cond = $this->input->post('Pay_Cond');
         
          for ($i=2; $i < $CountRow; $i++) {
            $temp = array();
            $NPM = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
            $temp = array(
              'NPM' => $NPM,
              'Pay_Cond' => $Pay_Cond,
            );
            $arr_insert[] = $temp;
          }
          $this->db->update_batch('db_academic.auth_students', $arr_insert, 'NPM');
          echo json_encode(array('status'=> 1,'msg' => ''));
        }
        else
        {
          exit('No direct script access allowed');
        }
    }

    public function list_telat_bayar()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/page_list_telat_bayar',$this->data,true);
        $this->temp($content);
    }

    public function get_list_telat_bayar($page = null)
    {
        $input = $this->getInputToken();
        $this->load->library('pagination');
        // count
        $count = $this->m_finance->count_get_list_telat_bayar_mhs($input['ta'],$input['prodi'],$input['PTID'],$input['NIM']);
        $config = $this->config_pagination_default_ajax($count,100,3);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(3);
        $start = ($page - 1) * $config["per_page"];
        $data = $this->m_finance->get_list_telat_bayar_mhs($input['ta'],$input['prodi'],$input['PTID'],$input['NIM'],$config["per_page"], $start);
        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $data,
        );
        echo json_encode($output);
    }

    public function edit_telat_bayar($token)
    {
        $key = "UAP)(*";
        $input = (array) $this->jwt->decode($token,$key);
        $this->data['NPM'] = $input['NPM'];
        $this->data['PaymentID'] = $input['PaymentID'];
        $this->data['semester'] = $input['semester'];
        $this->data['PTID'] = $input['PTID'];
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/page_edit_telat_bayar',$this->data,true);
        $this->temp($content);

    }

    public function import_pembayaran_manual()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/page_import_pembayaran_manual',$this->data,true);
        $this->temp($content);
    }

    public function submit_import_pembayaran_manual()
    {
        // print_r($_FILES);
        if(isset($_FILES["fileData"]["name"]))
        {
          $path = $_FILES["fileData"]["tmp_name"];
          include APPPATH.'third_party/PHPExcel/PHPExcel.php';
          $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
          $excel2 = $excel2->load($path); // Empty Sheet
          $objWorksheet = $excel2->setActiveSheetIndex(0);
          $CountRow = $objWorksheet->getHighestRow();
          $CountRow = $CountRow + 1;
          $selectPTID = $this->input->post('selectPTID');
          $selectSemester = $this->input->post('selectSemester');
         
          for ($i=2; $i < $CountRow; $i++) {
            $temp = array();
            $NPM = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
            // get payment
               $auth = $this->m_master->caribasedprimary('db_academic.auth_students','NPM',$NPM);
               $Pay_Cond = $auth[0]['Pay_Cond'];
               $Year = $auth[0]['Year'];
               $db = 'ta_'.$Year.'.students';
               $ta = $this->m_master->caribasedprimary($db,'NPM',$NPM);
               $ProdiID = $ta[0]['ProdiID'];
               $payment = $this->m_finance->getPriceBaseBintang($selectPTID,$ProdiID,$Year,$Pay_Cond);

            $aa = $this->m_finance->insertaDataPayment($selectPTID,$selectSemester,$NPM,$payment,0,"1",$this->session->userdata('NIP'));
            if ($aa != 0) {
                $bb = $this->m_finance->insertaDataPaymentStudents($aa,$payment,0,'0000-00-00 00:00:00',1);
            }
            
          }
          
          echo json_encode(array('status'=> 1,'msg' => ''));
        }
        else
        {
          exit('No direct script access allowed');
        }
    }

    public function import_beasiswa_mahasiswa()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/page_import_beasiswa_mahasiswa',$this->data,true);
        $this->temp($content);
    }

    public function  submit_import_beasiswa_mahasiswa()
    {
        // print_r($_FILES);
        if(isset($_FILES["fileData"]["name"]))
        {
          $path = $_FILES["fileData"]["tmp_name"];
          $arr_insert = array();
          include APPPATH.'third_party/PHPExcel/PHPExcel.php';
          $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
          $excel2 = $excel2->load($path); // Empty Sheet
          $objWorksheet = $excel2->setActiveSheetIndex(0);
          $CountRow = $objWorksheet->getHighestRow();
          $CountRow = $CountRow + 1;
          $selectPTID = $this->input->post('selectPTID');
          
          for ($i=2; $i < $CountRow; $i++) {
            $temp = array();
            $NPM = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
            $Discount = $objWorksheet->getCellByColumnAndRow(3, $i)->getCalculatedValue();

            // replace
            $Discount = $this->m_master->replaceKomaToTitik($Discount);
            if ($selectPTID == 2) {
               $temp = array(
                             'NPM' => $NPM,
                             'Bea_BPP' => $Discount,
                           );
            }
            elseif ($selectPTID == 3) {
                $temp = array(
                              'NPM' => $NPM,
                              'Bea_Credit' => $Discount,
                            );
            }
            $arr_insert[] = $temp;
          }

          $this->db->update_batch('db_academic.auth_students', $arr_insert, 'NPM');
          echo json_encode(array('status'=> 1,'msg' => ''));
        }
        else
        {
          exit('No direct script access allowed');
        }
    }

    public function mahasiswa()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/page_master_mahasiswa',$this->data,true);
        $this->temp($content);
    }

    public function mahasiswa_list($page = null)
    {
        $input = $this->getInputToken();

        $this->load->library('pagination');
        // cari count
        $count = $this->m_finance->count_mahasiswa_list($input['ta'],$input['prodi'],$input['NPM']);
        $config = $this->config_pagination_default_ajax($count,20,4);   
        $this->pagination->initialize($config);
        $page = $this->uri->segment(4);
        $start = ($page - 1) * $config["per_page"];

        $data = $this->m_finance->mahasiswa_list($input['ta'],$input['prodi'],$input['NPM'],$config["per_page"], $start);
        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $data,
        );
        echo json_encode($output);
    }

    public function edited_bea_bpp()
    {
        $input = $this->getInputToken();
        $dataSave = array(
                'Bea_BPP' => $input['Discount'],
                        );
        $this->db->where('NPM',$input['Npm']);
        $this->db->update('db_academic.auth_students', $dataSave);
    }

    public function edited_bea_credit()
    {
        $input = $this->getInputToken();
        $dataSave = array(
                'Bea_Credit' => $input['Discount'],
                        );
        $this->db->where('NPM',$input['Npm']);
        $this->db->update('db_academic.auth_students', $dataSave);
    }

    public function edited_pay_cond()
    {
        $input = $this->getInputToken();
        $dataSave = array(
                'Pay_Cond' => $input['bintang'],
                        );
        $this->db->where('NPM',$input['Npm']);
        $this->db->update('db_academic.auth_students', $dataSave);
    }
    

}
