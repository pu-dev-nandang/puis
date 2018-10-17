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
        //$this->m_finance->set_tuition_fee_approve($input);
       
        $getData = $this->m_finance->tuition_fee_calon_mhs_by_ID($input[0]);

        $cicilan = $this->m_master->caribasedprimary('db_finance.payment_pre','ID_register_formulir',$input[0]);
        $proses = $this->m_finance->process_tuition_fee_calon_mhs($getData,$cicilan);
        if ($proses == '') {
            // create pdf & send email
            $this->Tuition_PDF_SendEmail($getData,$cicilan);
        }
    }

    public function Tuition_PDF_SendEmail($Personal,$arr_cicilan)
    {
        $Sekolah = $Personal[0]['SchoolName'];
        $TuitionFee = $this->m_finance->getTuitionFee_calon_mhs($Personal[0]['ID_register_formulir']);
        $arr_temp = array('filename' => '');
        $filename = 'Tuition_fee_'.$Personal[0]['FormulirCode'].'.pdf';
        $getData = $this->m_master->showData_array('db_admission.set_label_token_off');

        $setXAwal = 10;
        $setYAwal = 18;
        $setJarakY = 5;
        $setJarakX = 40;
        $setFontIsian = 12;

        $config=array('orientation'=>'P','size'=>'A4');
        $this->load->library('mypdf',$config);
        $this->mypdf->SetMargins(10,10,10,10);
        $this->mypdf->SetAutoPageBreak(true, 0);
        $this->mypdf->AddPage();
        // Logo
        $this->mypdf->Image('./images/logo_tr.png',10,10,50);
        $this->mypdf->SetFont('Arial','B',10);
        $this->mypdf->Text(150, 17, 'Formulir Number : '.$Personal[0]['FormulirCode']);

        // Line break
        $this->mypdf->Ln(20);

        // isian
        $setY = $setYAwal + 20;
        $setX = $setXAwal;

        // label
        $this->mypdf->SetXY($setX,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
        $this->mypdf->Cell(0, 0, 'Nama', 0, 1, 'L', 0);

        // titik dua
        $setXtitik2 = $setX+$setJarakX;
        $this->mypdf->SetXY($setXtitik2,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$setFontIsian);
        $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

        // value
        $setXvalue = $setXtitik2 + 2;
        $this->mypdf->SetXY($setXvalue,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
        $this->mypdf->Cell(0, 0, $Personal[0]['Name'], 0, 1, 'L', 0); 

        $setY = $setY + 8;

        // label
        $this->mypdf->SetXY($setX,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
        $this->mypdf->Cell(0, 0, 'Sekolah', 0, 1, 'L', 0);

        // titik dua
        $setXtitik2 = $setX+$setJarakX;
        $this->mypdf->SetXY($setXtitik2,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$setFontIsian);
        $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

        // value
        $setXvalue = $setXtitik2 + 2;
        $this->mypdf->SetXY($setXvalue,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
        $this->mypdf->Cell(0, 0, $Personal[0]['SchoolName'], 0, 1, 'L', 0);

        $setY = $setY + 8;
        // label
        $this->mypdf->SetXY($setX,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
        $this->mypdf->Cell(0, 0, 'Program Studi', 0, 1, 'L', 0);

        // titik dua
        $setXtitik2 = $setX+$setJarakX;
        $this->mypdf->SetXY($setXtitik2,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$setFontIsian);
        $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

        // value
        $setXvalue = $setXtitik2 + 2;
        $this->mypdf->SetXY($setXvalue,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
        $this->mypdf->Cell(0, 0, $Personal[0]['NamePrody'], 0, 1, 'L', 0);

        $setY = $setY + 8;
        // check VA active atau tidak
        if ($this->session->userdata('finance_auth_Policy_SYS') == 1) {
            // label
            $this->mypdf->SetXY($setX,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
            $this->mypdf->Cell(0, 0, 'Virtual Account', 0, 1, 'L', 0);

            // titik dua
            $setXtitik2 = $setX+$setJarakX;
            $this->mypdf->SetXY($setXtitik2,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$setFontIsian);
            $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

            // value
            $setXvalue = $setXtitik2 + 2;
            $this->mypdf->SetXY($setXvalue,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
            $this->mypdf->Cell(0, 0, $Personal[0]['VA_number'], 0, 1, 'L', 0);
        }
        else
        {
            // label
            $this->mypdf->SetXY($setX,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
            $this->mypdf->Cell(0, 0, 'Rekening', 0, 1, 'L', 0);

            // titik dua
            $setXtitik2 = $setX+$setJarakX;
            $this->mypdf->SetXY($setXtitik2,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$setFontIsian);
            $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

            // value
            $setXvalue = $setXtitik2 + 2;
            $this->mypdf->SetXY($setXvalue,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
            $this->mypdf->Cell(0, 0, '161.3888.555 (BCA Yayasan Pendidikan Agung Podomoro)', 0, 1, 'L', 0);
        }
        

        $t = 0;
        for ($i=0; $i < count($TuitionFee); $i++) { 
            $t = $t + $TuitionFee[$i]['Pay_tuition_fee'];
        }

        $setY = $setY + 8;
        // label
        $this->mypdf->SetXY($setX,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
        $this->mypdf->Cell(0, 0, 'Total Tagihan', 0, 1, 'L', 0);

        // titik dua
        $setXtitik2 = $setX+$setJarakX;
        $this->mypdf->SetXY($setXtitik2,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$setFontIsian);
        $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

        // value
        $setXvalue = $setXtitik2 + 2;
        $this->mypdf->SetXY($setXvalue,$setY);
        $this->mypdf->SetTextColor(0,0,0);
        $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
        $this->mypdf->Cell(0, 0, 'Rp '.number_format($t,2,',','.'), 0, 1, 'L', 0);

         $setY = $setY + 10;

        $this->mypdf->SetXY($setX,$setY); 
        $this->mypdf->SetFillColor(226, 226, 226);
        $this->mypdf->Cell(8,9,'No',1,0,'C',true);
        $this->mypdf->Cell(80,9,'Invoice',1,0,'C',true);
        $this->mypdf->Cell(60,9,'Deadline',1,1,'C',true);

        for ($i=0; $i < count($arr_cicilan); $i++) { 
            $no = $i + 1;
            $this->mypdf->SetFillColor(255, 255, 255);
            $this->mypdf->Cell(8,9,$no,1,0,'C',true);
            $this->mypdf->Cell(80,9,'Rp '.number_format($arr_cicilan[$i]['Invoice'],2,',','.'),1,0,'L',true);
            $this->mypdf->Cell(60,9,$arr_cicilan[$i]['Deadline'],1,1,'L',true);

        }

        $this->mypdf->Cell(60,9,'',0,1,'L',true); // enter
        $this->mypdf->Cell(60,9,'',0,1,'L',true); // enter

        $this->mypdf->Cell(25,5,'Note : ',0,1,'L',true);
        $this->mypdf->SetFont('Arial','',9);
        $this->mypdf->Cell(100,5,'* Biaya kuliah per semester : Biaya BPP + (Biaya per SKS (Credit) * Jumlah SKS) +  Biaya lain-lain persemester,',0,1,'L',true);
        $this->mypdf->Cell(100,5,'* Jika calon mahasiswa tidak lulus Ujian Nasional (UN) maka biaya yang telah dibayarkan akan dikembalikan dan ',0,1,'L',true);
        $this->mypdf->Cell(100,5,'  dipotong biaya administrasi sebesar Rp 500.000,00 setelah menunjukan surat keterangan dari sekolah, ',0,1,'L',true);
        $this->mypdf->Cell(100,5,'* Apabila diterima di Perguruan Tinggi Negri (PTN) yaitu UI,ITB,UNPAD,UNDIP,IPB,UGM,UNAIR,ITS melalui ',0,1,'L',true);
        $this->mypdf->Cell(100,5,'  jalur SNMPTN & SBMPTN (tidak termasuk jalur Ujian Mandiri, program diploma & politeknik negri) maka biaya yang telah',0,1,'L',true);
        $this->mypdf->Cell(100,5,'  dibayarkan akan dikembalikan & dipotong biaya administrasi Rp 1.500.000,00 ',0,1,'L',true);
        $this->mypdf->Cell(100,5,'  (dengan menunjukan surat penerimaan dari universitas terkait) ',0,1,'L',true);
        
         
         $this->mypdf->Line(20, 280, 190, 280);
         $setY = 282;
         $this->mypdf->SetFont('Arial','',6);
         $this->mypdf->SetXY(40,$setY);
         $this->mypdf->SetTextColor(0,0,0);
         // $this->mypdf->SetFillColor(0,0,0);
         $this->mypdf->Cell(190, 5, 'Admission Office :  Central Park Mall, Lantai 3, Unit 112, Podomoro City, JL Letjen S. Parman Kav.28, Jakarta Barat 11470', 0, 1, 'L', 0);
         $setY = 285;
         $this->mypdf->SetFont('Arial','',6);
         $this->mypdf->SetXY(43,$setY);
         $this->mypdf->SetTextColor(0,0,0);
         // $this->mypdf->SetFillColor(0,0,0);
         $this->mypdf->Cell(190, 5, 'Telp : (021) 292 00 456    Email : admission@podomorouniversity.ac.id   Website : www.podomorouniversity.ac.id', 0, 1, 'L', 0);

         $path = './document';
         $path = $path.'/'.$filename;
         $this->mypdf->Output($path,'F');

         $text = 'Dear '.$Personal[0]['Name'].',<br><br>
                     Plase find attached your Tuition Fee.<br>
                     For Detail your payment, please see in '.url_registration."login/";
         $to = $Personal[0]['Email'];
         $subject = "Podomoro University Tuition Fee";
         $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text,$path);

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
                    if ($this->session->userdata('finance_auth_Policy_SYS') == 1) {
                        $chkTgl = $this->m_master->checkTglNow($getDeadlineTagihanDB);
                    }
                    else
                    {
                        $chkTgl = true;
                    }
                    
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

                            // cek VA policy
                            if ($this->session->userdata('finance_auth_Policy_SYS') == 1) {
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
                            else
                            {
                                $countSuccessVA++;
                                $aa = $this->m_finance->insertaDataPayment($Input[$i]->PTID,$Input[$i]->semester,$Input[$i]->NPM,$payment,$Input[$i]->Discount);
                                $ab = $this->m_finance->insertaDataPaymentStudents($aa,$payment,0,$DeadLinePayment);
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
        $config = $this->config_pagination_default_ajax($count,20,3);
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
        // print_r($Input);die();
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
            if ($this->session->userdata('finance_auth_Policy_SYS') == 1) {
                $aaa = $this->m_master->chkTgl($Input[$i]->Deadline,$DeadLinePayment);
            }
            else
            {
                $aaa = true;
            }
            
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
                        if ($this->session->userdata('finance_auth_Policy_SYS') == 1) {
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
                            else
                            {
                                $sqlDelete = 'delete from db_finance.payment_students where BilingID = "'.$BilingID.'"';
                                $queryDelete=$this->db->query($sqlDelete, array());

                                $getData= $this->m_master->caribasedprimary('db_va.va_log','trx_id',$BilingID);
                                $VA_number = $getData[0]['virtual_account'];
                                $trx_amount = $Input[$i]->Payment;
                                $datetime_expired = $Input[$i]->Deadline;
                                $customer_name = $getData[0]['customer_name'];
                                $customer_email = $getData[0]['customer_email'];
                                // $update = $this->m_finance->update_va_Payment($trx_amount,$datetime_expired, $customer_name, $customer_email,$BilingID,'db_finance.payment_students',$desc);
                                $update = $this->m_finance->create_va_Payment($trx_amount,$datetime_expired, $customer_name, $customer_email,$VA_number,'Re-'.$desc,'db_finance.payment_students');
                                if ($update['status']) {
                                  // update data pada table db_finance.payment_students
                                    // $this->m_finance->updateCicilanMHS($BilingID,$trx_amount,$datetime_expired);
                                    $this->m_finance->insertaDataPaymentStudents($ID,$Input[$i]->Payment,$update['msg']['trx_id'],$Input[$i]->Deadline);
                                }
                                else
                                {
                                  $arr['msg'] .= 'Va tidak bisa di update, error koneksi ke BNI <br>';
                                }
                            }
                        } // exit if va status
                        else
                        {
                            // update data pada table db_finance.payment_students
                              $trx_amount = $Input[$i]->Payment;
                              $datetime_expired = $Input[$i]->Deadline;
                              $ID_psStudent = $a[0]['ID'];
                              $this->m_finance->UpdateCicilanbyID($ID_psStudent,0,$trx_amount,$datetime_expired);
                              //$this->m_finance->updateCicilanMHS(0,$trx_amount,$datetime_expired);

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

    public function import_pembayaran_lain()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/page_import_pembayaran_lain',$this->data,true);
        $this->temp($content);
    }

    public function submit_import_pembayaran_lain()
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
          $selectSemester = explode('.', $selectSemester);
          $maba = $this->input->post('maba');
         
          for ($i=2; $i < $CountRow; $i++) {
            $temp = array();
            $NPM = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
            $PTID = $selectPTID;
            $SemesterID = $selectSemester[0];
            $sql = 'select ID from db_finance.payment where PTID = "'.$PTID.'"  and SemesterID = "'.$SemesterID.'" and NPM = "'.$NPM.'" and Status = "1" ';
            $query=$this->db->query($sql, array())->result_array();

            $ID_payment = $query[0]['ID'];
            $dataSave = array(
                    'Status' => 1,
                    'BilingID' => 0,
                            );
            $this->db->where('ID_payment',$ID_payment);
            $this->db->where('Status',0);
            $this->db->update('db_finance.payment_students', $dataSave);
            
          }
          
          echo json_encode(array('status'=> 1,'msg' => ''));
        }
        else
        {
          exit('No direct script access allowed');
        }
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
          $selectSemester = explode('.', $selectSemester);
          $selectSemester = $selectSemester[0];
          $maba = $this->input->post('maba');
         
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
               // check PTID, jika SKS / Credit dikali per sks yang diambil // 3 credit
                  // check checklist mahasiswa baru atau tidak
                if ($selectPTID == 3) {
                    if ($maba == 1) {
                        $ProStuDefaultCredit = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ProdiID);
                        $DefaultCredit = $ProStuDefaultCredit[0]['DefaultCredit'];
                        $payment = (int)$payment * (int)$DefaultCredit;
                    }
                    else
                    {
                        // '.$db.'.study_planning
                        $Credit = $this->m_finance->getSKSMahasiswa('ta_'.$Year,$NPM);
                        $payment = (int)$payment * (int)$Credit;
                    }
                }

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

    public function download_log_va()
    {
       $content = $this->load->view('page/'.$this->data['department'].'/download_log_va/page_download_log_va',$this->data,true);
       $this->temp($content);
    }

    public function listfile_va()
    {
        $generate = $this->m_master->loadData_limit500('db_va.va_log_sftp','ID','desc');
        echo json_encode($generate);
    }

    public function page_master_discount()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/page_master_discount',$this->data,true);
        $this->temp($content);
    }

    public function load_discount()
    {
        $generate = $this->m_master->showData_array('db_finance.discount');
        echo json_encode($generate);
    }

    public function modalform_discount()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_finance.discount','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_discount',$this->data,true);
    }

    public function sbmt_discount()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                $this->m_finance->inserData_discount($input['Discount']);
                break;
            case 'edit':
                $this->m_finance->editData_discount($input['Discount'],$input['CDID']);
                break;
            case 'delete':
                $this->m_finance->delete_id_table($input['CDID'],'discount');
                break;
            default:
                # code...
                break;
        }
    }

    public function testApprove()
    {
        $sql = 'select * from db_finance.payment where Status = "1" and DATE_FORMAT(UpdateAt,"%Y%m%d") = 20180808';
        $query=$this->db->query($sql, array())->result_array();
        for ($i=0; $i < count($query); $i++) { 
            $ID_payment = $query[$i]['ID'];
            $dataSave = array(
                    'Status' => 1,
                    'BilingID' => 0,
                            );
            $this->db->where('ID_payment',$ID_payment);
            $this->db->where('Status',0);
            $this->db->update('db_finance.payment_students', $dataSave);
        }
    }

    public function bayar_manual_mahasiswa()
    {
        $input = $this->getInputToken();
        $IDStudent = $input['IDStudent'];
        $bayar = $input['bayar'];
        if ($bayar == 1) {
            $dataSave = array(
                    'Status' => 1,
                    'BilingID' => 0,
                    'UpdateAt' => date('Y-m-d H:i:s'),
                            );
            $this->db->where('ID',$IDStudent);
            $this->db->where('Status',0);
            $this->db->update('db_finance.payment_students', $dataSave);
        }
        else
        {
            $dataSave = array(
                    'Status' => 0,
                    'BilingID' => 0,
                    'UpdateAt' => date('Y-m-d H:i:s'),
                            );
            $this->db->where('ID',$IDStudent);
            $this->db->where('Status',1);
            $this->db->update('db_finance.payment_students', $dataSave);
        }
        
    }

    public function set_tuition_fee_delete_data()
    {
      $input = $this->getInputToken();
      $this->m_admission->set_tuition_fee_delete_data($input);

      // send email to admission
      $getEmailDB = $this->m_master->caribasedprimary('db_admission.email_to','Function','Admisi');
      $text = "Dear Team,<br><br>
                    Please following below list of prospective student bills rejected by Finance, <br><br>
                    <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 style='border-collapse:collapse;border:none'><tr><td width=35 valign=top style='width:26.6pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>NO<o:p></o:p></p></td><td width=270 valign=top style='width:202.5pt;border:solid windowtext 1.0pt;border-left:none;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>Nama<o:p></o:p></p></td><td width=162 valign=top style='width:121.5pt;border:solid windowtext 1.0pt;border-left:none;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>Prody<o:p></o:p></p></td><td width=156 valign=top style='width:116.9pt;border:solid windowtext 1.0pt;border-left:none;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>Formulir Code<o:p></o:p></p></td></tr>
                ";    
      $arr_list = array();
      for ($i=0; $i < count($input); $i++) { 
          $GetDataMHS = $this->m_admission->getDataPersonal($input[$i]);
          $No = $i + 1;
          $Name = $GetDataMHS[0]['Name'];
          $Prody = $GetDataMHS[0]['NameProdyIND'];
          $FormulirCode = $GetDataMHS[0]['FormulirCode'];
          $text .= "<tr><td width=35 valign=top style='width:26.6pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>".$No."<o:p></o:p></p></td><td width=270 valign=top style='width:202.5pt;border:solid windowtext 1.0pt;border-left:none;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>".$Name."<o:p></o:p></p></td><td width=162 valign=top style='width:121.5pt;border:solid windowtext 1.0pt;border-left:none;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>".$Prody."<o:p></o:p></p></td><td width=156 valign=top style='width:116.9pt;border:solid windowtext 1.0pt;border-left:none;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>".$FormulirCode."<o:p></o:p></p></td></tr>";
      }
      $text .= '</table>';
      

      // $table = "<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 style='border-collapse:collapse;border:none'><tr><td width=35 valign=top style='width:26.6pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>NO<o:p></o:p></p></td><td width=270 valign=top style='width:202.5pt;border:solid windowtext 1.0pt;border-left:none;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>Nama<o:p></o:p></p></td><td width=162 valign=top style='width:121.5pt;border:solid windowtext 1.0pt;border-left:none;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>Prody<o:p></o:p></p></td><td width=156 valign=top style='width:116.9pt;border:solid windowtext 1.0pt;border-left:none;padding:0in 5.4pt 0in 5.4pt'><p class=MsoNormal>Formulir Code<o:p></o:p></p></td></tr></table>";

      $Email = $getEmailDB[0]['EmailTo'];
      $to = $Email;
      $subject = "Podomoro University Notification Bills";
      $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
    }

    public function bayar_manual_mahasiswa_formulironline()
    {
        $input = $this->getInputToken();
        $RegID = $input['RegID'];
        $dataSave = array(
              'BilingID' => 0,
                      );
      $this->db->where('ID',$RegID);
      $this->db->update('db_admission.register', $dataSave);

      // insert to another table
      $getData = $this->m_master->caribasedprimary('db_admission.register','ID',$RegID);
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

    }

    public function penerimaan_pembayaran_biaya()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/penerimaan_pembayaran/penerimaan_pembayaran_admission',$this->data,true);
        $this->temp($content);
    }

    public function getPayment_admission()
    {
        $requestData= $_REQUEST;
        // print_r($requestData);
        $reqTahun = $this->input->post('tahun');
        $totalData = $this->m_finance->getCountAllPayment_admission();

        $sql = 'select * from (
                select a.ID as ID_register_formulir,a.ID_program_study,o.Name as NamePrody,d.Name,a.Gender,a.IdentityCard,e.ctr_name as Nationality,
                f.Religion,concat(a.PlaceBirth,",",a.DateBirth) as PlaceDateBirth,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,e.ctr_name as SchoolCountry,
                n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,a.UploadFoto,
                if((select count(*) as total from db_admission.register_nilai where Status = "Approved" and ID_register_formulir = a.ID limit 1) > 0,"Rapor","Ujian")
                as status1,p.CreateAT,p.CreateBY,b.FormulirCode,p.TypeBeasiswa,p.FileBeasiswa,
                if( (select count(*) as total from db_finance.payment_pre where ID_register_formulir = a.ID limit 1) > 1,"Cicilan","Tidak Cicilan") as cicilan,
                if((select count(*) as total from db_finance.payment_pre where `Status` = 0 and ID_register_formulir = a.ID limit 1) = 0 ,"Lunas","Belum Lunas") as StatusPayment
                from db_admission.register_formulir as a
                JOIN db_admission.register_verified as b 
                ON a.ID_register_verified = b.ID
                JOIN db_admission.register_verification as c
                ON b.RegVerificationID = c.ID
                JOIN db_admission.register as d
                ON c.RegisterID = d.ID
                JOIN db_admission.country as e
                ON a.NationalityID = e.ctr_code
                JOIN db_employees.religion as f
                ON a.ReligionID = f.IDReligion
                JOIN db_admission.school_type as l
                ON l.sct_code = a.ID_school_type
                JOIN db_admission.register_major_school as m
                ON m.ID = a.ID_register_major_school
                JOIN db_admission.school as n
                ON n.ID = d.SchoolID
                join db_academic.program_study as o
                on o.ID = a.ID_program_study
                join db_finance.register_admisi as p
                on a.ID = p.ID_register_formulir
                where p.Status = "Approved"  and d.SetTa = "'.$reqTahun.'" group by a.ID

                ) SubQuery
            ';

        $sql.= ' where (Name LIKE "'.$requestData['search']['value'].'%" or NamePrody LIKE "%'.$requestData['search']['value'].'%"
                or FormulirCode LIKE "'.$requestData['search']['value'].'%" or SchoolName LIKE "%'.$requestData['search']['value'].'%"
                or StatusPayment LIKE "'.$requestData['search']['value'].'%" or cicilan LIKE "'.$requestData['search']['value'].'%")
                ';
        $sql.= ' ORDER BY StatusPayment ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

        $query = $this->db->query($sql)->result_array();

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];

            // $nestedData[] = '<input type="checkbox" name="id[]" value="'.$row['ID_register_formulir'].'">';
            $nestedData[] = $row['NamePrody'];
            $nestedData[] = $row['Name'].'<br>'.$row['Email'];
            $nestedData[] = $row['FormulirCode'];

            // get tagihan
            $getTagihan = $this->m_admission->getPaymentType_Cost_created($row['ID_register_formulir']);
            $tagihan = '';
            for ($j=0; $j < count($getTagihan); $j++) { 
                $tagihan .= $getTagihan[$j]['Abbreviation'].' : '.'Rp '.number_format($getTagihan[$j]['Pay_tuition_fee'],2,',','.').'<br>';
            }

            $nestedData[] = $tagihan;
            $nestedData[] = $row['cicilan'];
            $nestedData[] = '<button class="btn btn-inverse btn-notification btn-show" id-register-formulir = "'.$row['ID_register_formulir'].'" email = "'.$row['Email'].'" Nama = "'.$row['Name'].'">Show</button>';
            $nestedData[] = $row['StatusPayment'];
            $nestedData[] = '<button class = "btn btn-primary btn-payment" id-register-formulir = "'.$row['ID_register_formulir'].'" Nama = "'.$row['Name'].'">Detail</button>';


            // $combo = '<select class="full-width-fix select grouPAuth btn-edit" NIP = "'.$row['NIP'].'">';
            // for ($j=0; $j < count($getGroupUser); $j++) { 
            //     if ($getGroupUser[$j]['ID'] == $row['G_user']) {
            //          $combo .= '<option value = "'.$getGroupUser[$j]['ID'].'" selected>'.$getGroupUser[$j]['GroupAuth'].'</option>';
            //     }
            //     else
            //     {
            //         $combo .= '<option value = "'.$getGroupUser[$j]['ID'].'">'.$getGroupUser[$j]['GroupAuth'].'</option>';
            //     }
            // }

            // $combo .= '</select>';

            // $nestedData[] = $combo;

            // $btn = '<button class="btn btn-danger btn-sm btn-delete btn-delete-group" NIP = "'.$row['NIP'].'"><i class="fa fa-trash" aria-hidden="true"></i></button>';  

            // $nestedData[] = $btn;
            // $data[] = $nestedData;
            $data[] = $nestedData;
        }

        // print_r($data);

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        echo json_encode($json_data);
    }


    public function getPayment_admission_edit_cicilan()
    {
        $requestData= $_REQUEST;
        // print_r($requestData);
        $totalData = $this->m_finance->getCountAllPayment_admission();

        $sql = 'select * from (
                select a.ID as ID_register_formulir,a.ID_program_study,o.Name as NamePrody,d.Name,a.Gender,a.IdentityCard,e.ctr_name as Nationality,
                f.Religion,concat(a.PlaceBirth,",",a.DateBirth) as PlaceDateBirth,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,e.ctr_name as SchoolCountry,
                n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,a.UploadFoto,
                if((select count(*) as total from db_admission.register_nilai where Status = "Approved" and ID_register_formulir = a.ID limit 1) > 0,"Rapor","Ujian")
                as status1,p.CreateAT,p.CreateBY,b.FormulirCode,p.TypeBeasiswa,p.FileBeasiswa,
                if( (select count(*) as total from db_finance.payment_pre where ID_register_formulir = a.ID limit 1) > 1,"Cicilan","Tidak Cicilan") as cicilan,
                if((select count(*) as total from db_finance.payment_pre where `Status` = 0 and ID_register_formulir = a.ID limit 1) = 0 ,"Lunas","Belum Lunas") as StatusPayment
                from db_admission.register_formulir as a
                JOIN db_admission.register_verified as b 
                ON a.ID_register_verified = b.ID
                JOIN db_admission.register_verification as c
                ON b.RegVerificationID = c.ID
                JOIN db_admission.register as d
                ON c.RegisterID = d.ID
                JOIN db_admission.country as e
                ON a.NationalityID = e.ctr_code
                JOIN db_employees.religion as f
                ON a.ReligionID = f.IDReligion
                JOIN db_admission.school_type as l
                ON l.sct_code = a.ID_school_type
                JOIN db_admission.register_major_school as m
                ON m.ID = a.ID_register_major_school
                JOIN db_admission.school as n
                ON n.ID = d.SchoolID
                join db_academic.program_study as o
                on o.ID = a.ID_program_study
                join db_finance.register_admisi as p
                on a.ID = p.ID_register_formulir
                where p.Status = "Approved" group by a.ID

                ) SubQuery
            ';

        $sql.= ' where (Name LIKE "'.$requestData['search']['value'].'%" or NamePrody LIKE "%'.$requestData['search']['value'].'%"
                or FormulirCode LIKE "'.$requestData['search']['value'].'%" or SchoolName LIKE "%'.$requestData['search']['value'].'%"
                or StatusPayment LIKE "'.$requestData['search']['value'].'%" or cicilan LIKE "'.$requestData['search']['value'].'%")
                and FormulirCode not in (select FormulirCode from db_admission.to_be_mhs)
                ';
        $sql.= ' ORDER BY StatusPayment ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

        $query = $this->db->query($sql)->result_array();

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];

            $nestedData[] = '<input type="checkbox" name="id[]" value="'.$row['ID_register_formulir'].'" Nama = "'.$row['Name'].'">';
            $nestedData[] = $row['NamePrody'];
            $nestedData[] = $row['Name'].'<br>'.$row['Email'];
            $nestedData[] = $row['FormulirCode'];
            // get tagihan
            $getTagihan = $this->m_admission->getPaymentType_Cost_created($row['ID_register_formulir']);
            $tagihan = '';
            for ($j=0; $j < count($getTagihan); $j++) { 
                $tagihan .= $getTagihan[$j]['Abbreviation'].' : '.'Rp '.number_format($getTagihan[$j]['Pay_tuition_fee'],2,',','.').'<br>';
            }

            $nestedData[] = $tagihan;
            $nestedData[] = $row['cicilan'];
            $nestedData[] = '<button class="btn btn-inverse btn-notification btn-show" id-register-formulir = "'.$row['ID_register_formulir'].'" email = "'.$row['Email'].'" Nama = "'.$row['Name'].'">Show</button>';
            $nestedData[] = $row['StatusPayment'];
            // $nestedData[] = '<button class = "btn btn-primary btn-payment" id-register-formulir = "'.$row['ID_register_formulir'].'" Nama = "'.$row['Name'].'">Detail</button>';


            // $combo = '<select class="full-width-fix select grouPAuth btn-edit" NIP = "'.$row['NIP'].'">';
            // for ($j=0; $j < count($getGroupUser); $j++) { 
            //     if ($getGroupUser[$j]['ID'] == $row['G_user']) {
            //          $combo .= '<option value = "'.$getGroupUser[$j]['ID'].'" selected>'.$getGroupUser[$j]['GroupAuth'].'</option>';
            //     }
            //     else
            //     {
            //         $combo .= '<option value = "'.$getGroupUser[$j]['ID'].'">'.$getGroupUser[$j]['GroupAuth'].'</option>';
            //     }
            // }

            // $combo .= '</select>';

            // $nestedData[] = $combo;

            // $btn = '<button class="btn btn-danger btn-sm btn-delete btn-delete-group" NIP = "'.$row['NIP'].'"><i class="fa fa-trash" aria-hidden="true"></i></button>';  

            // $nestedData[] = $btn;
            // $data[] = $nestedData;
            $data[] = $nestedData;
        }

        // print_r($data);

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function getPayment_detail_admission()
    {
        $input = $this->getInputToken();
        $data = $this->m_finance->checkPayment_admisi($input['ID_register_formulir']);
        echo json_encode($data);
    }

    public function getPayment_detail_admission2()
    {
        $input = $this->getInputToken();
        $data = $this->m_finance->checkPayment_admisi2($input['ID_register_formulir']);
        echo json_encode($data);
    }

    public function bayar_manual_mahasiswa_admission()
    {
        $input = $this->getInputToken();
        $IDStudent = $input['IDStudent'];
        $bayar = $input['bayar'];
        if ($bayar == 1) {
            $dataSave = array(
                    'Status' => 1,
                    'BilingID' => 0,
                    'UpdateAt' => date('Y-m-d H:i:s'),
                            );
            $this->db->where('ID',$IDStudent);
            $this->db->where('Status',0);
            $this->db->update('db_finance.payment_pre', $dataSave);
        }
        else
        {
            $dataSave = array(
                    'Status' => 0,
                    'BilingID' => 0,
                    'UpdateAt' => date('Y-m-d H:i:s'),
                            );
            $this->db->where('ID',$IDStudent);
            $this->db->where('Status',1);
            $this->db->update('db_finance.payment_pre', $dataSave);
        }
        
    }

    public function approved_edit()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/approved/edit_tagihan_cicilan',$this->data,true);
        $this->temp($content);
    }

    public function approved_edit_submit()
    {
        $Input = $this->getInputToken();
        $msg = '';
        $proses = $this->m_finance->edit_cicilan_tagihan_admission_submit($Input);
        $msg = $proses['msg'];
        echo json_encode($msg);
    }

    public function report()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/report',$this->data,true);
        $this->temp($content);
    }

    public function get_reporting($page = null)
    {
        $input = $this->getInputToken();
        $this->load->library('pagination');
        // per page 2 database
        $sqlCount = 'show databases like "%ta_2%"';
        $queryCount=$this->db->query($sqlCount, array())->result_array();

        $config = $this->config_pagination_default_ajax(count($queryCount),1,3);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(3);
        $start = ($page - 1) * $config["per_page"];
        $data = $this->m_finance->get_report_pembayaran_mhs($input['ta'],$input['prodi'],$input['NIM'],$input['Semester'],$input['Status'],$config["per_page"], $start);
        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $data,
        );
        echo json_encode($output);
    }

    public function formulir_registration_offline_serverSide()
    {
        $requestData= $_REQUEST;
        $reqTahun = $this->input->post('tahun');
        $StatusJual = $this->input->post('StatusJual');
        $No = $requestData['start'] + 1;
        $totalData = $this->m_admission->totalDataFormulir_offline4($reqTahun,$requestData,$StatusJual);

        if($StatusJual != '%') {
          $StatusJual = ' and b.StatusJual = '.$StatusJual;
        }
        else
        {
          $StatusJual = ''; 
        }

        $sql = 'select a.NameCandidate,a.Email,a.SchoolName,b.FormulirCode,b.No_Ref,a.StatusReg,b.Years,b.Status as StatusUsed, b.StatusJual,
                  b.FullName as NamaPembeli,b.PhoneNumber as PhoneNumberPembeli,b.HomeNumber as HomeNumberPembeli,b.Email as EmailPembeli,b.Sales,b.PIC as SalesNIP,b.SchoolNameFormulir,b.CityNameFormulir,b.DistrictNameFormulir,
                  b.ID as ID_sale_formulir_offline,b.Price_Form,b.DateSale,b.src_name,b.NameProdi,b.NoKwitansi
                  from (
                  select a.Name as NameCandidate,a.Email,z.SchoolName,c.FormulirCode,a.StatusReg
                  from db_admission.register as a 
                  join db_admission.register_verification as b
                  on a.ID = b.RegisterID
                  join db_admission.register_verified as c
                  on c.RegVerificationID = b.ID
                  join db_admission.school as z
                  on z.ID = a.SchoolID
                  where a.StatusReg = 1
                  ) as a right JOIN
                  (
                  select a.FormulirCode,a.No_Ref,a.Years,a.Status,a.StatusJual,b.FullName,b.HomeNumber,b.PhoneNumber,b.DateSale,b.NoKwitansi,
                  b.Email,c.Name as Sales,b.PIC,b.ID,b.Price_Form,z.SchoolName as SchoolNameFormulir,z.CityName as  CityNameFormulir,z.DistrictName as DistrictNameFormulir,
                  if(b.source_from_event_ID = 0,"", (select src_name from db_admission.source_from_event where ID = b.source_from_event_ID and Active = 1 limit 1) ) as src_name,b.ID_ProgramStudy,y.Name as NameProdi
                  from db_admission.formulir_number_offline_m as a
                  left join db_admission.sale_formulir_offline as b
                  on a.FormulirCode = b.FormulirCodeOffline
                  left join db_employees.employees as c
                  on c.NIP = b.PIC
                  left join db_admission.school as z
                  on z.ID = b.SchoolID
                  left join db_academic.program_study as y
                  on b.ID_ProgramStudy = y.ID
                  )
                  as b
                  on a.FormulirCode = b.FormulirCode
            ';

        $sql.= 'where Years = "'.$reqTahun.'" and 
                        (
                          b.FormulirCode like "'.$requestData['search']['value'].'%" or
                          b.No_Ref like "'.$requestData['search']['value'].'%" or
                          b.Sales like "'.$requestData['search']['value'].'%" or
                          a.NameCandidate like "'.$requestData['search']['value'].'%" or
                          b.SchoolNameFormulir like "%'.$requestData['search']['value'].'%" or
                          b.NameProdi like "'.$requestData['search']['value'].'%" or
                          b.src_name like "'.$requestData['search']['value'].'%" or
                          b.FullName like "'.$requestData['search']['value'].'%" or
                          b.DateSale like "'.$requestData['search']['value'].'%"
                        ) '.$StatusJual.'';
        $sql.= ' order by b.No_Ref asc LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

        $query = $this->db->query($sql)->result_array();

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];
            $nestedData[] = $No;
            $nestedData[] = $row['FormulirCode'];
            $nestedData[] = $row['No_Ref'];
            $nestedData[] = $row['NameProdi'];
            $aa = ($row['StatusUsed'] == 0) ? '<div style="color:  green;">No</div>' : '<div style="color:  red;">Yes</div>';
            $nestedData[] = $aa;
            $aa = ($row['StatusJual'] == 0) ? '<div style="color:  green;">IN</div>' : '<div style="color:  red;">Sold Out</div>';
            $nestedData[] = $aa;
            $nestedData[] = $row['Sales'];
            $nestedData[] = number_format($row['Price_Form'],0,',','.');
            $nestedData[] = $row['DateSale'];
            $nestedData[] = $row['NamaPembeli'].'<br>'.$row['PhoneNumberPembeli'].'<br>'.$row['EmailPembeli'].'<br>'.$row['SchoolNameFormulir'].'<br>'.$row['DistrictNameFormulir'].' '.$row['CityNameFormulir'];
            $nestedData[] = $row['src_name'];
            $action = '';
            if ($row['ID_sale_formulir_offline'] != null || $row['ID_sale_formulir_offline'] != '')
            {
              $action = '<div class="row" style="margin-top: 10px">
                          <div class="col-md-12">
                            <span ref = "'.$row['No_Ref'].'" NamaLengkap = "'.$row['NamaPembeli'].'" class="btn btn-xs btn-print" phonehome = "'.$row['HomeNumberPembeli'].'" hp = "'.$row['PhoneNumberPembeli'].'" jurusan = "'.$row['NameProdi'].'" pembayaran ="Pembelian Form('.$row['No_Ref'].')" jenis= "Cash" jumlah = "'.$row['Price_Form'].'" date = "'.$row['DateSale'].'" formulir = "'.$row['FormulirCode'].'" NoKwitansi = "'.$row['NoKwitansi'].'">
                             <i class="fa fa-print"></i> Kwitansi
                           </span>
                          </div>
                        </div>
                        ';
            }

            $nestedData[] = $action;
            $data[] = $nestedData;
            $No++;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function copy_last_tuition_fee()
    {
        $input = $this->getInputToken();
        if ($input['verify'] == 'CreateTuitionFee') {
            $year = date('Y');
            $YearNext = (int) $year + 1;
            $msg = '';
            $get = $this->m_master->caribasedprimary('db_finance.tuition_fee','ClassOf',$YearNext);
            if (count($get) == 0) {
                $sql = "INSERT INTO db_finance.tuition_fee (ID, PTID, ProdiID, ClassOf,Cost,Pay_Cond)
                        SELECT null, PTID, ProdiID, ?,Cost,Pay_Cond
                        FROM db_finance.tuition_fee 
                        WHERE ClassOf = ?";
                $query=$this->db->query($sql, array($YearNext,$year));   
            }
            else
             {
                $msg = 'The data is already exist';
             }
             echo json_encode($msg);
        }
        else
        {
            exit('No direct script access allowed');
        }
    }

}
