<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class C_global extends CI_Controller {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
        $this->load->library('JWT');
        $this->load->model('master/m_master');
    }

    private function template_blank($content){

        $data['content'] = $content;
        $this->load->view('template/template_blank',$data);
    }

    public function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function getInputTokenGet($token)
    {
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function loadDataRegistrationBelumBayar()
    {
        $Tahun = $this->input->post('tahun');
        // print_r('test =--'.$Tahun);die();
        // $Tahun = $Tahun['tahun'];
        $this->data['tahun']= $Tahun;
        $content = $this->load->view('page/load_data_registration_belum_bayar',$this->data,true);
        echo $content;
    }

    public function load_data_registration_telah_bayar()
    {
        $Tahun = $this->input->post('tahun');
        // $Tahun = $Tahun['tahun'];
        $this->data['tahun']= $Tahun;
        $content = $this->load->view('page/load_data_registration_telah_bayar',$this->data,true);
        echo $content;
    }

    public function load_data_registration_formulir_offline()
    {
        $content = $this->load->view('page/load_data_registration_formulir_offline',$this->data,true);
        echo $content;
    }

    public function download($file)
    {
        if (file_exists('./document/'.$file)) {
             $this->load->helper('download');
             $data   = file_get_contents('./document/'.$file);
             $name   = $file;
             force_download($name, $data); // script download file
            // $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    public function fileGet($file)
    {
        //check session ID_register_formulir ada atau tidak
        // check session token untuk download

        // Check File exist atau tidak
        if (file_exists('./document/'.$file)) {
            // $this->load->helper('download');
            // $data   = file_get_contents('./document/'.$namaFolder.'/'.$file);
            // $name   = $file;
            // force_download($name, $data); // script download file
            $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    public function fileGetAny($file)
    {
        error_reporting(0);
        //check session ID_register_formulir ada atau tidak
        // check session token untuk download
        $file = str_replace('-', '/', $file);
        // print_r($file);die();
        $path = $file;
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($ext == 'pdf') {
            if (file_exists('./uploads/'.$file)) {
                    // // $file = "path_to_file";
                    // $fp = fopen($path, "r") ;
                    // header("Cache-Control: maxage=1");
                    // header("Pragma: public");
                    // header("Content-type: application/pdf");
                    // header("Content-Disposition: inline; filename=".$filename."");
                    // header("Content-Description: PHP Generated Data");
                    // header("Content-Transfer-Encoding: binary");
                    // header('Content-Length:' . filesize($path));
                    // ob_clean();
                    // flush();
                    // while (!feof($fp)) {
                    //    $buff = fread($fp, 1024);
                    //    print $buff;
                    // }
                    // exit;
                $this->showFile2($file);
            }
            else
            {
                show_404($log_error = TRUE);
            }
        }
        else
        {
            if (file_exists('./uploads/'.$file)) {
                $imageData = base64_encode(file_get_contents(FCPATH.'uploads/'.$path));
                echo '<img src="data:image/jpeg;base64,'.$imageData.'">';
                
            }
            else
            {
                show_404($log_error = TRUE);
            }
            
        }
    }

    private function showFile2($file)
    {
        header("Content-type: application/pdf");
        header("Content-disposition: inline;     
        filename=".basename('uploads/'.$file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        $filePath = readfile('uploads/'.$file);
    }

    public function download_template($file)
    {
        $file = str_replace('-', '/', $file);
        if (file_exists('./uploads/'.$file)) {
             $this->load->helper('download');
             $data   = file_get_contents('./uploads/'.$file);
             $name   = $file;
             force_download($name, $data); // script download file
            // $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    public function download_anypath()
    {
        $input = $this->getInputToken();
        $path = $input['path'];
        $filename = $input['Filename'];
        if (file_exists($path)) {
             $this->load->helper('download');
             $data   = file_get_contents($path);
             $name   = $filename;
             force_download($name, $data); // script download file
            // $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    private function showFile($file)
    {
        header("Content-type: application/pdf");
        header("Content-disposition: inline;     
        filename=".basename('document/'.$file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        $filePath = readfile('document/'.$file);
    }

    public function get_detail_cicilan_fee_admisi()
    {
        $input = $this->getInputToken();
        $ID_register_formulir = $input['ID_register_formulir'];
        $output = $this->m_master->caribasedprimary('db_finance.payment_pre','ID_register_formulir',$ID_register_formulir);
        echo json_encode($output);

    }

    public function get_nilai_from_admission()
    {
        $input = $this->getInputToken();
        $ID_register_formulir = $input['ID_register_formulir'];
        $query = array();
        $query = $this->m_master->caribasedprimary('db_admission.register_nilai_fin','ID_register_formulir',$ID_register_formulir);
        // if (count($get) == 0) {
        //     $get2 = $this->m_master->caribasedprimary('db_admission.register_nilai','ID_register_formulir',$ID_register_formulir);
        //     for ($i=0; $i < count($get2); $i++) { 
        //         $NamaUjian = $this->m_master->caribasedprimary('db_admission.ujian_perprody_m','ID',$get2[$i]['ID_ujian_perprody']);
        //         $get2[$i] = $get2[$i] + array('NamaUjian' => $NamaUjian[0]['NamaUjian'],'Bobot' => $NamaUjian[0]['Bobot']);
        //     }
        //     $query = $get2;
        // }
        // else
        // {
        //     $this->load->model('admission/m_admission');
        //     $get2 = $this->m_admission->getHasilUjian($ID_register_formulir);
        //     $query = $get2;
        // }
        echo json_encode($query);
    }

    public function autocompleteAllUser()
    {
        $input = $this->getInputToken();
        $data['response'] = 'true'; //mengatur response
        $data['message'] = array(); //membuat array
        $getData = $this->m_master->getAllUserAutoComplete($input['Nama']);
        for ($i=0; $i < count($getData); $i++) {
            $data['message'][] = array(
                'label' => $getData[$i]['Name'],
                'value' => $getData[$i]['NIP']
            );
        }
        echo json_encode($data);
    }

    public function testInject()
    {
        ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
        ini_set('max_execution_time', 0); // for infinite time of execution

        // $sql = 'select * from db_employees.division';
        // $query=$this->db->query($sql, array())->result_array();
        // for ($i=0; $i < count($query); $i++) {
        //     $sql1 = 'select * from db_employees.rule_service where IDService = 5 and IDDivision = '.$query[$i]['ID'];
        //     $query1=$this->db->query($sql1, array())->result_array();
        //     if (count($query1) == 0) {
        //         $datasave = array(
        //             'IDDivision' => $query[$i]['ID'],
        //             'IDService' => 5,
        //             'Status' => '1',
        //         );
        //         $this->db->insert('db_employees.rule_service',$datasave);
        //     }
           
        // }

        // $sql = 'SELECT a.NIP,a.Name,SPLIT_STR(a.PositionMain, ".", 1) as PositionMain1,
        //        SPLIT_STR(a.PositionMain, ".", 2) as PositionMain2,
        //              a.StatusEmployeeID
        // FROM   db_employees.employees as a
        // where SPLIT_STR(a.PositionMain, ".", 1) = 12 and a.StatusEmployeeID != -1';
        // $query=$this->db->query($sql, array())->result_array();
        // for ($i=0; $i < count($query); $i++) { 
        //     $NIP = $query[$i]['NIP'];
        //     $IDDivision = 34;
        //     $sql1 = 'select count(*) as total from db_employees.rule_users where NIP = ? and IDDivision = ?';
        //     $query1=$this->db->query($sql1, array($NIP,$IDDivision))->result_array();
        //     $total = $query1[0]['total'];
        //     if ($total == 0) {
        //        $dataSave = array(
        //             'NIP' => $NIP,
        //             'IDDivision' => $IDDivision,
        //             'privilege' => 1,
        //        );

        //        $this->db->insert('db_employees.rule_users',$dataSave);
        //     }
        // }



        // $datasave = array(
        //     'Approver2' => '[{"TypeApprover":"Division","Approver":"8"}]',
        // );
        // $this->db->where('Approver2','["8"]');
        // $this->db->update('db_reservation.category_room',$datasave);

        // insert auth budgeting
        // $sql = 'select NIP,PositionMain from db_employees.employees where StatusEmployeeID = 1';
        // $query=$this->db->query($sql, array())->result_array();
        // for ($i=0; $i < count($query); $i++) { 
        //     $NIP = $query[$i]['NIP'];
        //     $PositionMain = $query[$i]['PositionMain'];
        //     $P = explode('.', $PositionMain);
        //     if (count($P) > 1) {
        //         $P = $P[1];
        //         $array_admin2 = array(13,14,8,21,7);
        //             $G_user = 3;
        //         if (in_array($P, $array_admin2) ) {
        //              $G_user = 4;
        //         }

        //         // find data di previleges_guser db_budgeting
        //         $G__ = $this->m_master->caribasedprimary('db_budgeting.previleges_guser','NIP',$NIP);
        //         if (count($G__) == 0) {
        //             $datasave = array(
        //                 'NIP' => $NIP,
        //                 'G_user' => $G_user,    
        //             );

        //             $this->db->insert('db_budgeting.previleges_guser',$datasave);
        //         }
        //     }
            
        // }

        // insert auth purchasing
        // $sql = 'SELECT a.NIP,a.Name,SPLIT_STR(a.PositionMain, ".", 1) as PositionMain1,
        //        SPLIT_STR(a.PositionMain, ".", 2) as PositionMain2,
        //              a.StatusEmployeeID
        // FROM   db_employees.employees as a
        // where (SPLIT_STR(a.PositionMain, ".", 1) = 4 or  SPLIT_STR(a.PositionMain, ".", 1) = 12 ) and a.StatusEmployeeID != -1';
        // $query=$this->db->query($sql, array())->result_array();
        // for ($i=0; $i < count($query); $i++) { 
        //    if ($query[$i]['PositionMain1'] == 4) {
        //       $array_admin2 = array(13,14,8,21,7);
        //        if (in_array($query[$i]['PositionMain2'], $array_admin2) ) {
        //             $G_user = 4;
        //         }
        //         else
        //         {
        //             $G_user = 3;
        //         }

        //    }
        //    else
        //    {
        //     $G_user = 1;
        //    }
        //    $NIP = $query[$i]['NIP'];
        //   $G__ = $this->m_master->caribasedprimary('db_purchasing.previleges_guser','NIP',$NIP);
        //   if (count($G__) == 0) {
        //       $datasave = array(
        //           'NIP' => $NIP,
        //           'G_user' => $G_user,    
        //       );

        //       $this->db->insert('db_purchasing.previleges_guser',$datasave);
        //   }
        // }

        // inject cfg_rule_g_user purchasing 
        // $sql = 'select * from db_purchasing.cfg_rule_g_user';
        // $query=$this->db->query($sql, array())->result_array();
        // for ($i=0; $i < count($query); $i++) { 
        //     $r = $query[$i];
        //     unset($r['ID']);
        //     $r['cfg_group_user'] = 3;
        //     $this->db->insert('db_purchasing.cfg_rule_g_user',$r);  
        //     $r['cfg_group_user'] = 4;
        //     $this->db->insert('db_purchasing.cfg_rule_g_user',$r);  

        // }

        // $this->db_server22 = $this->load->database('server22', TRUE);
        // $sql = 'select *,SUBSTRING(member_id,3,2) as angkatan from library.member where Year(register_date) = 2019 and member_type_id = 2 and SUBSTRING(member_id,3,2) = 19';
        // $query=$this->db_server22->query($sql, array())->result_array();
        // for ($i=0; $i < count($query); $i++) { 
        //     $G_dt = $this->m_master->caribasedprimary('ta_2019.students','NPM',$query[$i]['member_id']);
        //     $birth_date = $G_dt[0]['DateOfBirth'];
        //     $member_address = $G_dt[0]['Address'];
        //     $member_email = $G_dt[0]['Email'];

        //     $datasave = array(
        //         'birth_date' => $birth_date,
        //         'member_address' => $member_address,
        //         'member_email' => $member_email,
        //     );
        //     $this->db_server22->where('member_id',$query[$i]['member_id']);
        //     $this->db_server22->update('library.member',$datasave);
        // }

    }

    public function testInject2()
    {
        // $get = $this->m_master->showData_array('db_admission.sale_formulir_offline');
        // for ($i=0; $i < count($get); $i++) { 
        //     $ID = $get[$i]['ID'];
        //     $FullName = strtolower($get[$i]['FullName']);
        //     $FullName = ucwords($FullName);
        //     $dataSave = array(
        //             'FullName' => ucwords($FullName),
        //             'Email' => strtolower($get[$i]['Email'])
        //                     );
        //     $this->db->where('ID',$ID);
        //     $this->db->update('db_admission.sale_formulir_offline', $dataSave);
        // }


    }

    public function testInject3()
    {
        // $get = $this->m_master->showData_array('db_admission.register');
        // for ($i=0; $i < count($get); $i++) { 
        //     $ID = $get[$i]['ID'];
        //     $FullName = strtolower($get[$i]['Name']);
        //     $FullName = ucwords($FullName);
        //     $dataSave = array(
        //             'Name' => ucwords($FullName),
        //             'Email' => strtolower($get[$i]['Email'])
        //                     );
        //     $this->db->where('ID',$ID);
        //     $this->db->update('db_admission.register', $dataSave);
        // }

        //get all payment dengan PTID 5 & 6
        $arr = array();
        $sql = 'select * from db_finance.payment where PTID in (5,6) limit 100';
        $query=$this->db->query($sql, array())->result_array();
        for ($i=0; $i < count($query); $i++) {
            $dt = array(); 
            $ID_payment = $query[$i]['ID'];
            $NPM = $query[$i]['NPM'];
            $Invoice = $query[$i]['Invoice'];
            $Status = $query[$i]['Status'];
            $PTID = $query[$i]['PTID'];

            // update Invoice = 0.0 Status = 1 based ID = ID_payment
            // $arr_upd = array(
            //     'Invoice' => 0.0,
            //     'Status' => '1',
            // );
            // $this->db->where('ID',$ID_payment);
            // $this->db->update('db_finance.payment',$arr_upd);

            // cek ke payment_student
            $sql2 = 'select * from db_finance.payment_students where ID_payment = ?';
            $query2=$this->db->query($sql2, array($ID_payment))->result_array();
            for ($k=0; $k < count($query2); $k++) { 
                $ID_payment_std = $query2[$k]['ID'];
                $InvoiceBill = $query2[$k]['Invoice'];
                $StatusBill = $query2[$k]['Status'];
                $dt[] = array(
                    'ID_payment_std' => $ID_payment_std,
                    'InvoiceBill' => $InvoiceBill,
                    'StatusBill' => $StatusBill
                );

                // update Status = '1' based ID = ID_payment_std
                // $arr_upd2 = array(
                //     'Invoice' => 0.0,
                //     'Status' => 1,
                // );

                // $this->db->where('ID',$ID_payment_std);
                // $this->db->update('db_finance.payment_students',$arr_upd2);
            }

            $arr[] = array(
                'ID_payment' => $ID_payment,
                'NPM' => $NPM,
                'Invoice' => $Invoice,
                'Status' => $Status,
                'PTID' => $PTID,
                'dt' =>$dt
            );
        }

        print_r($arr);

    }

    public function testInject4()
    {
        // $get = $this->m_master->showData_array('db_admission.formulir_number_offline_m');
        // for ($i=0; $i < count($get); $i++) { 
        //     $Link = $get[$i]['Link'];
        //     $Link = str_replace('http://admission.podomorouniversity.ac.id/', 'http://localhost/registeronline/', $Link);
        //     $dataSave = array(
        //             'Link' => $Link,
        //                     );
        //     $this->db->where('ID',$get[$i]['ID']);
        //     $this->db->update('db_admission.formulir_number_offline_m', $dataSave);
        // }

        // $sql = 'select ID from db_admission.register_formulir where ID not in (select ID_register_formulir from db_admission.register_document)';
        // $query=$this->db->query($sql, array())->result_array();
        // for ($i=0; $i < count($query); $i++) {
        //     $ID_register_formulir = $query[$i]['ID']; 
        //     $arrID_reg_doc_checklist = $this->m_master->caribasedprimary('db_admission.reg_doc_checklist','Active',1);
        //     for ($xy=0; $xy < count($arrID_reg_doc_checklist); $xy++) { 
        //         $dataSave = array(
        //                 'ID_register_formulir' => $ID_register_formulir,
        //                 'ID_reg_doc_checklist' => $arrID_reg_doc_checklist[$xy]['ID'],
        //                         );

        //         $this->db->insert('db_admission.register_document', $dataSave);
        //     }
        // }

        // untuk data yang tidak sesuai dengan beasiswanya dengan nilai value nya pada table payment_admisi compare dengan tuition_fee
        // $this->load->model('admission/m_admission');
        // $this->load->model('finance/m_finance');
        // $arr = array();
        // // $get = $this->m_master->showData_array('db_finance.payment_admisi');
        // // $sql = 'select * from db_finance.payment_admisi where ID >= 800 and ID_register_formulir != 302';
        // // $sql = 'select * from db_finance.payment_admisi where ID_register_formulir != 302 order by ID asc limit 200,700';
        // $sql = 'select * from db_finance.payment_admisi where ID_register_formulir != 302';
        // $get=$this->db->query($sql, array())->result_array();
        // $Filter_ = array();
        // for ($i=0; $i < count($get); $i++) {
        //     $ID =  $get[$i]['ID'];
        //     $ID_register_formulir = $get[$i]['ID_register_formulir'];
        //     $dt = $this->m_admission->getDataPersonal($ID_register_formulir);
        //     // get Years and ProdiID
        //     $Years = $dt[0]['SetTa'];
        //     $ProdiID = $dt[0]['ID_program_study'];
        //     // get PTID
        //     $PTID = $get[$i]['PTID'];
        //     // get tuition fee
        //         $G_tuition_fee = $this->m_finance->getPriceBaseBintang($PTID,$ProdiID,$Years,1);
        //         $Cost = $G_tuition_fee;
        //         $Pay_tuition_fee = $get[$i]['Pay_tuition_fee'];
        //         // persen
        //         if ($PTID == 3) {
        //             $ccc = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ProdiID);
        //             $Credit = $ccc[0]['DefaultCredit'];
        //             $Cost = $Cost * $Credit;
        //             $persen = (($Pay_tuition_fee-$Cost) / $Cost) * 100;
        //             $persen = abs($persen);
        //         }
        //         else
        //         {
        //             $persen = (($Pay_tuition_fee-$Cost) / $Cost) * 100;
        //             $persen = abs($persen);
        //         }
                
        //         if ($persen != $get[$i]['Discount']) {
        //             // get date created and created by
        //             $G_register_admisi = $this->m_master->caribasedprimary('db_finance.register_admisi','ID_register_formulir',$ID_register_formulir);
        //             // hitung berapa orang
        //                 $b = true;
        //                 for ($j=0; $j < count($Filter_); $j++) { 
        //                     if ($ID_register_formulir == $Filter_[$j]['ID_register_formulir']) {
        //                        $b = false;
        //                     }
        //                 }

        //                 if ($b) {
        //                     $Filter_[]= array(
        //                         'ID_register_formulir' =>$ID_register_formulir,
        //                         'FormulirCode' => $dt[0]['FormulirCode'],
        //                         'Name' => $dt[0]['Name'],
        //                         'CreateAT' => $G_register_admisi[0]['CreateAT'],
        //                         'CreateBY' => $G_register_admisi[0]['CreateBY'],
        //                     );
        //                 }

        //             $temp = array(
        //                 'ID' => $ID,
        //                 'ID_register_formulir' => $ID_register_formulir,
        //                 'FormulirCode' => $dt[0]['FormulirCode'],
        //                 'Name' => $dt[0]['Name'],
        //                 'Discount_seharusnya' => $persen,
        //                 'Discount_db' => $get[$i]['Discount'],
        //                 'Pay_tuition_fee' => $Pay_tuition_fee,
        //                 'CostDefault' => $Cost,
        //                 'CreateAT' => $G_register_admisi[0]['CreateAT'],
        //                 'CreateBY' => $G_register_admisi[0]['CreateBY'],
        //             );
        //             $arr['dt'][] = $temp;

        //             // update data
        //             // $arr_save_db = array(
        //             //     'Discount' => $persen,
        //             // );
        //             // $this->db->where('ID',$ID);
        //             // $this->db->update('db_finance.payment_admisi',$arr_save_db);
        //         }


        // }
        // $arr['Count_person'] = count($Filter_);
        // $arr['Person'] = $Filter_;
        // print_r($arr);

        $G_dt = $this->m_master->showData_array('db_cooperation.kegiatan');
        for ($i=0; $i < count($G_dt); $i++) { 
            $KerjasamaID = $G_dt[$i]['KerjasamaID'];
            $ID = $G_dt[$i]['ID'];
            $G = $this->m_master->caribasedprimary('db_cooperation.kerjasama','ID',$KerjasamaID);
            $Kategori_kegiatan = $G[0]['Kategori'];
            $this->db->where('ID',$ID);
            $this->db->update('db_cooperation.kegiatan',array('Kategori_kegiatan' => $Kategori_kegiatan));
        }
    }



    public function testInject5()
    {
        //Load Composer's autoloader
        include_once APPPATH.'vendor/autoload.php';

        $mail = new PHPMailer(true);                
        $event_id = 1234;
        $sequence = 0;
        $status = 'TENTATIVE';
        // event params
        $summary = 'Summary of the 161330';
        $venue = 'Jakarta';
        $start = '20181105';
        $start_time = '161330';
        $end = '20181105';
        $end_time = '170630';

        //PHPMailer
       //Server settings
           $mail->SMTPDebug = 0;                                 // Enable verbose debug output
           $mail->isSMTP();                                      // Set mailer to use SMTP
           $mail->Host = 'ssl://smtp.gmail.com';  // Specify main and backup SMTP servers
           $mail->SMTPAuth = true;                               // Enable SMTP authentication
           $mail->Username = 'ithelpdesk.notif@podomorouniversity.ac.id';                 // SMTP username
           $mail->Password = '4dm1n5!S';                           // SMTP password
           $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
           $mail->Port = 465;    
           $mail->IsHTML(false);                                // TCP port to connect to
        $mail->setFrom('ithelpdesk.notif@podomorouniversity.ac.id', 'IT');
        $mail->addReplyTo('alhadi.rahman@podomorouniversity.ac.id', 'IT');
        $mail->addAddress('alhadi.rahman@podomorouniversity.ac.id','IT');
        $mail->addAddress('alhadirahman22@gmail.com','adi');
        $mail->addAddress('rqul22@gmail.com','RF');
        $mail->ContentType = 'text/calendar';

        $mail->Subject = "Outlooked Event";
        $mail->addCustomHeader('MIME-version',"1.0");
        $mail->addCustomHeader('Content-type',"text/calendar; method=REQUEST; charset=UTF-8");
        $mail->addCustomHeader('Content-Transfer-Encoding',"7bit");
        $mail->addCustomHeader('X-Mailer',"Microsoft Office Outlook 12.0");
        $mail->addCustomHeader("Content-class: urn:content-classes:calendarmessage");

        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//YourCassavaLtd//EateriesDept//EN\r\n";
        $ical .= "METHOD:REQUEST\r\n";
        $ical .= "BEGIN:VEVENT\r\n";
        $ical .= "ORGANIZER;SENT-BY=\"MAILTO:it@podomorouniversity.ac.id\":MAILTO:it@podomorouniversity.ac.id\r\n";
        $ical .= "ATTENDEE;CN=it@podomorouniversity.ac.id.com;ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED;RSVP=TRUE:mailto:it@podomorouniversity.ac.id\r\n";
        $ical .= "UID:".strtoupper(md5($event_id))."-podomorouniversity.ac.id\r\n";
        $ical .= "SEQUENCE:".$sequence."\r\n";
        $ical .= "STATUS:".$status."\r\n";
        $ical .= "DTSTAMPTZID=Africa/Nairobi:".date('Ymd').'T'.date('His')."\r\n";
        $ical .= "DTSTART:".$start."T".$start_time."\r\n";
        $ical .= "DTEND:".$end."T".$end_time."\r\n";
        $ical .= "LOCATION:".$venue."\r\n";
        $ical .= "SUMMARY:".$summary."\r\n";
        $ical .= "DESCRIPTION:".'Test Description'."\r\n"; 
        $ical .= "BEGIN:VALARM\r\n";
        $ical .= "TRIGGER:-PT15M\r\n";
        $ical .= "ACTION:DISPLAY\r\n";
        $ical .= "DESCRIPTION:Reminder\r\n";
        $ical .= "END:VALARM\r\n";
        $ical .= "END:VEVENT\r\n";
        $ical .= "END:VCALENDAR\r\n";

        $mail->Body = $ical;

        //send the message, check for errors
        if(!$mail->send()) {
        $this->error = "Mailer Error: " . $mail->ErrorInfo;
        return false;
        } else {
        $this->error = "Message sent!";
        return true;
        }
    }

    // public function page_mahasiswa()
    // {
    //     $content = $this->load->view('page/academic'.'/master/students/students','',true);
    //     $this->temp($content);
    // }

    // public function page_dok_admisi_mahasiswa()
    // {

    // }

    public function getRevision_detail_admission()
    {
        $input = $this->getInputToken();
        $ID_register_formulir = $input['ID_register_formulir'];
        
        $sql = 'select a.*,b.Name from db_finance.register_admisi_rev as a
                left join db_employees.employees as b
                on a.RevBy = b.NIP
                where a.ID_register_formulir = ? order by a.RevNo asc';
        $query=$this->db->query($sql, array($ID_register_formulir))->result_array();
        echo json_encode($query);
    }

    public function view_venue_markom($token)
    {
       // error_reporting(0);
       try 
       {
           $key = "UAP)(*";
           $data_arr = (array) $this->jwt->decode($token,$key);
           // cek status
           $t_booking = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$data_arr['ID_t_booking']);
           if (count($t_booking ) > 0) {
               if ($t_booking[0]['Status'] == 0) {
                   $data['include'] = $this->load->view('template/include','',true);
                   $data['ID_t_booking'] = $data_arr['ID_t_booking'];
                   $this->load->view('page/vreservation/t_view_markom_support',$data);
               }
           }
           else{
               // handling orang iseng
               echo '{"status":"999","message":"Data doesn\'t exist "}';
           }
       }
       //catch exception
       catch(Exception $e) {
         // handling orang iseng
         echo '{"status":"999","message":"Not Authorize"}';
       }
    }

    public function approve_venue($token)
    {
        //error_reporting(0);
        try 
        {
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($token,$key);
            // cek status
            $t_booking = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$data_arr['ID_t_booking']);
            $this->load->model('vreservation/m_reservation');
            if (count($t_booking ) > 0) {
                $Startdatetime = DateTime::createFromFormat('Y-m-d H:i:s', $t_booking[0]['Start']);
                $Enddatetime = DateTime::createFromFormat('Y-m-d H:i:s', $t_booking[0]['End']);
                $StartNameDay = $Startdatetime->format('l');
                $EndNameDay = $Enddatetime->format('l');
                $KetAdditional = json_decode($t_booking[0]['KetAdditional']);    
                $EmailKetAdditional = '<br>';
                if ($KetAdditional != '') {
                    if (count($KetAdditional) > 0) {
                        foreach ($KetAdditional as $key => $value) {
                            if ($value != "" || $value != null) {
                                // $EmailKetAdditional .= '<br>*   '.$key.' : '.$value;
                                $EmailKetAdditional .= '<br>*   '.str_replace("_", " ", $key).' : '.$value;    
                            }  
                        }
                    }
                    
                }

                $Email_add_person = $data_arr['Email_add_person'];
                //$MarkomEmail = $data_arr['MarkomEmail']; // get status below
                $EmailKetAdditional = $data_arr['EmailKetAdditional'];
                //$KetAdditional_eq = $data_arr['KetAdditional_eq'];  // get status below

                //
                $mks = $t_booking[0]['MarcommSupport'];
                if ($mks != '' && $mks != NULL ) {
                    $mks = explode(",", $mks); 
                }
                $MarkomEmail ='';
                if (is_array($mks)) {
                    $xx = $mks;
                    $dzx = array();
                    for ($xz=0; $xz < count($xx); $xz++) { 
                        $pos1 = stripos($xx[$xz], 'Note');
                        $exitLoop = false;
                        if ($pos1 !== false) {
                            $temp = array();
                            for ($ixx = $xz; $ixx < count($xx); $ixx++) { 
                                $temp[] = $xx[$ixx];
                            }
                            $dzx[] = implode(',', $temp);
                            $exitLoop = true;
                        }
                        else
                        {
                            $dzx[] = $xx[$xz];
                        }

                        if ($exitLoop) {
                            break;
                        }
                    }

                    $xx = $dzx;

                    
                    $MarkomEmail ='<li>Documentation<ul>';
                    for ($i=0; $i < count($xx); $i++) { 
                        if(strpos($xx[$i], 'Note') === false) {
                            $g_markom = $this->m_reservation->g_markom($xx[$i],$t_booking[0]['ID']);
                            if ($g_markom[0]['StatusMarkom'] == 0) {
                                $Status_markom = '{Not Confirm}';
                            }
                            elseif ($g_markom[0]['StatusMarkom'] == 1) {
                                $Status_markom = '{Confirm}';
                            }
                            else
                            {
                                $Status_markom = '{Reject}';
                            }
                            $MarkomEmail .='<li>'.$g_markom[0]['Name'].$Status_markom.'</li>';
                        }
                        else
                        {
                            $MarkomEmail .='<li>'.nl2br($xx[$i]).'</li>';
                        }
                    }
                    $MarkomEmail .= '</ul></li>';
                }

                $KetAdditional_eq = '';
                $keq_add = $t_booking[0]['ID_equipment_add'];
                if ($keq_add != '' && $keq_add != NULL) {
                    $keq_add = explode(",",$keq_add);
                }
                $e_div = array();
                if (is_array($keq_add)) {
                    // save data t_booking_eq_additional
                    $KetAdditional_eq = '<br><br>*  Equipment Additional<ul>';
                    $xx = $keq_add;
                    $ID_t_booking = $t_booking[0]['ID'];
                    for ($i=0; $i < count($xx); $i++) { 
                        $gett_booking_eq_additional = $this->m_reservation->gett_booking_eq_additional($xx[$i],$ID_t_booking);
                        if ($gett_booking_eq_additional[0]['Status'] == 0) {
                            $Status_eq_additional = '{Not Confirm}';
                        }
                        elseif ($gett_booking_eq_additional[0]['Status'] == 1) {
                            $Status_eq_additional = '{Confirm}';
                        }
                        else
                        {
                            $Status_eq_additional = '{Reject}';
                        }
                        $Qty = $gett_booking_eq_additional[0]['Qty'];
                        $ID_equipment_additional = $gett_booking_eq_additional[0]['ID_equipment_additional'];
                        $get = $this->m_master->caribasedprimary('db_reservation.m_equipment_additional','ID',$ID_equipment_additional);
                        $OwnerID = $get[0]['Owner'];
                        $getX = $this->m_master->caribasedprimary('db_employees.division','ID',$OwnerID);
                        $e_div[] = $getX[0]['Email'];
                        $Owner = $getX[0]['Division'];
                        $ID_m_equipment = $get[0]['ID_m_equipment'];
                        $get = $this->m_master->caribasedprimary('db_reservation.m_equipment','ID',$ID_m_equipment);
                        $KetAdditional_eq .= '<li>'.$get[0]['Equipment'].' by '.$Owner.'['.$Qty.']'.$Status_eq_additional.'</li>';

                    }
                    $KetAdditional_eq .= '</ul>';
                }

                $files_invitation = $t_booking[0]['Invitation'];
                $Email_invitation = $this->m_reservation->Email_invitation($files_invitation);
                // cek Approval
                $FieldTbl = array();
                $FieldTbl = ($data_arr['approvalNo'] == 1) ? array('Status1' => 1,'ApprovedAt1' => date('Y-m-d H:i:s'),'ApprovedBy1' => $data_arr['Code']) : array('Status' => 1,'ApprovedAt' => date('Y-m-d H:i:s'),'ApprovedBy' => $data_arr['Code']);
                if ($data_arr['approvalNo'] == 1) {
                    if ($t_booking[0]['Status1'] == 1) {
                        show_404($log_error = TRUE);
                    }
                    else
                    {
                        $this->db->where('ID',$data_arr['ID_t_booking']);
                        $this->db->update('db_reservation.t_booking', $FieldTbl);
                        if ($this->db->affected_rows() > 0 )
                         {
                            // find approval 1 sama dengan approval 2
                                $Code = explode(";", $data_arr['Code']);
                                
                                // send email to approval 2 and user
                                    // send email to approval 2

                                        $getRoom = $this->m_master->caribasedprimary('db_academic.classroom','Room',$t_booking[0]['Room']);
                                        $CategoryRoomByRoom = $getRoom[0]['ID_CategoryRoom'];
                                        $getDataCategoryRoom = $this->m_master->caribasedprimary('db_reservation.category_room','ID',$CategoryRoomByRoom);
                                        $Approver2 = $getDataCategoryRoom[0]['Approver2'];
                                        $Approver2 = json_decode($Approver2);

                                        $EmailPUAPP2 = '';
                                        $CodeAPP2 = '';
                                        $NameWR = '';
                                        for ($zz=0; $zz < count($Approver2); $zz++) { 
                                            $rdata = $Approver2[$zz];
                                            $TypeApprover = $rdata->TypeApprover;
                                            $bool = false;
                                            switch ($TypeApprover) {
                                                case 'Division':
                                                    $DivisionApprove = $this->m_master->caribasedprimary('db_employees.division','ID',$rdata->Approver);
                                                    $EmailPUAPP2 = $DivisionApprove[0]['Email'];
                                                    $CodeAPP2 = $DivisionApprove[0]['ID'];
                                                    $NameWR = $DivisionApprove[0]['Division'].' Team';
                                                    $bool = true;
                                                    break;
                                                case 'Employees':
                                                    $NIPAPP2 = $rdata->Approver;
                                                    $G_emp = $this->m_master->caribasedprimary('db_employees.employees','NIP',$NIPAPP2);
                                                    $EmailPUAPP2 = $G_emp[0]['EmailPU'];
                                                    $CodeAPP2 = $NIPAPP2;
                                                    $NameWR = 'Mr/Mrs '.$G_emp[0]['Name'];
                                                    $bool = true;
                                                    break;

                                            }

                                            if ($bool) {
                                                break;
                                            }
                                        }

                                        $token = array(
                                            'EmailPU' => $EmailPUAPP2,
                                            'Code' => $CodeAPP2,
                                            'ID_t_booking' => $data_arr['ID_t_booking'],
                                            'approvalNo' => 2,
                                            'Email_add_person' => $Email_add_person,
                                            'MarkomEmail' => $MarkomEmail,
                                            'EmailKetAdditional' => $EmailKetAdditional,
                                            'KetAdditional_eq' => $KetAdditional_eq,
                                        );
                                        $token = $this->jwt->encode($token,'UAP)(*');

                                        $approver1 = $data_arr['Code'];
                                        $nmapprover1 = '';
                                        $strlenapprover1 = strlen($approver1);
                                        if ($strlenapprover1 > 3) {
                                            $G_app1 = $this->m_master->caribasedprimary('db_employees.employees','NIP',$approver1);
                                            $nmapprover1 = $G_app1[0]['Name'];
                                        }
                                        else
                                        {
                                            $G_app1 = $this->m_master->caribasedprimary('db_employees.division','ID',$approver1);
                                            $nmapprover1 = $G_app1[0]['Division'];
                                        }

                                        if($_SERVER['SERVER_NAME']!='localhost') {
                                            // email to ga
                                            $Email = $EmailPUAPP2;
                                            $text = 'Dear '.$NameWR.',<br><br>
                                                        Venue Reservation has been approved by '.$nmapprover1.' as Approver 1,<br><br>
                                                        Please help to approve Venue Reservation,<br><br>
                                                        Details Schedule : <br><ul>
                                                        <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                                        <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                                        <li>Room  : '.$t_booking[0]['Room'].'</li>
                                                        <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                                        '.$Email_add_person.'
                                                        '.$MarkomEmail.'
                                                        </ul>
                                                        '.$EmailKetAdditional.'
                                                        '.$KetAdditional_eq.
                                                        $Email_invitation.'</br>
                                                       <table width="200" cellspacing="0" cellpadding="12" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td bgcolor="#51a351" align="center">
                                                                    <a href="'.url_pas.'approve_venue/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #51a351;" target="_blank" >Approve</a>
                                                                </td>
                                                                <td>
                                                                   -
                                                                </td>
                                                                <td bgcolor="#de4341" align="center">
                                                                    <a href="'.url_pas.'cancel_venue/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #de4341;" target="_blank" >Reject</a>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    ';        
                                            $to = $Email;
                                            $subject = "Podomoro University Venue Reservation Approval 2";
                                            $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

                                        }
                                        else
                                        {
                                            $Email = $EmailPUAPP2;
                                            $text = 'Dear '.$NameWR.',<br><br>
                                                        Venue Reservation has been approved by '.$nmapprover1.' as Approver 1,<br><br>
                                                        Please help to approve Venue Reservation,<br><br>
                                                        Details Schedule : <br><ul>
                                                        <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                                        <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                                        <li>Room  : '.$t_booking[0]['Room'].'</li>
                                                        <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                                        '.$Email_add_person.'
                                                        '.$MarkomEmail.'
                                                        </ul>
                                                        '.$EmailKetAdditional.'
                                                        '.$KetAdditional_eq.
                                                        $Email_invitation.'</br>
                                                        <table width="50" cellspacing="0" cellpadding="12" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td bgcolor="#51a351" align="center">
                                                                    <a href="'.url_pas.'approve_venue/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #51a351;" target="_blank" >Approve</a>
                                                                </td>
                                                                <td>
                                                                   -
                                                                </td>
                                                                <td bgcolor="#de4341" align="center">
                                                                    <a href="'.url_pas.'cancel_venue/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #de4341;" target="_blank" >Reject</a>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    ';        
                                            $to = $Email;
                                            $subject = "Podomoro University Venue Reservation Approval 2";
                                            $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                                        }

                                    // user
                                        $getUser = $this->m_master->caribasedprimary('db_employees.employees','NIP',$t_booking[0]['CreatedBy']);
                                        $Email = $getUser[0]['EmailPU'];

                                        $text = 'Dear Mr/Mrs '.$getUser[0]['Name'].',<br><br>
                                                    Your Venue Reservation approved by '.'Approver 1'.',<br><br>
                                                    Details Schedule : <br><ul>
                                                    <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                                    <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                                    <li>Room  : '.$t_booking[0]['Room'].'</li>
                                                    <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                                    '.$Email_add_person.'
                                                    '.$MarkomEmail.'
                                                    </ul>
                                                    '.$EmailKetAdditional.'
                                                    '.$KetAdditional_eq.
                                                    $Email_invitation.'</br>
                                                ';        
                                        $to = $Email;
                                        $subject = "Podomoro University Venue Reservation Approved";
                                        $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

                            $data['include'] = $this->load->view('template/include','',true);
                            $this->load->view('template/venue_approve_page',$data);
                         }
                         else
                         {
                            print_r('<h2><b>Please Try Again !!!</b2></h2>');
                         }
                    }
                }
                elseif ($data_arr['approvalNo'] == 2) {
                    if ($t_booking[0]['Status'] == 1) {
                        show_404($log_error = TRUE);
                    }
                    else
                    {
                        $this->db->where('ID',$data_arr['ID_t_booking']);
                        $this->db->update('db_reservation.t_booking', $FieldTbl);
                        if ($this->db->affected_rows() > 0 )
                         {
                            // send by Ical
                            $getUser = $this->m_master->caribasedprimary('db_employees.employees','NIP',$t_booking[0]['CreatedBy']);
                            $Email = $getUser[0]['EmailPU'];
                            $StartIcal = date("Ymd", strtotime($t_booking[0]['Start']));
                            $EndIcal = date("Ymd", strtotime($t_booking[0]['End']));
                            $place  = $t_booking[0]['Room'];
                            //sent for reminder
                               $to = $Email;
                               $StartTimeIcal = '073000';
                               $EndTimeIcal = '083000';
                               $subject = "Reminder Venue Reservation";
                               $text = 'Dear Mr/Mrs '.$getUser[0]['Name'].',<br><br>
                                           Reminder Venue Reservation,<br><br>
                                           Details Schedule : <br><ul>
                                           <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                           <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                           <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                           <li>Room  : '.$t_booking[0]['Room'].'</li>
                                           '.$Email_add_person.'
                                           '.$MarkomEmail.'
                                           </ul>
                                           '.$EmailKetAdditional.'
                                           '.$KetAdditional_eq.
                                           $Email_invitation.'</br>
                                       ';
                               $sendEmail = $this->m_sendemail->sendEmailIcal($to,$subject,$text, $place,$StartIcal,$StartTimeIcal,$EndIcal,$EndTimeIcal);


                            $text = 'Dear Mr/Mrs '.$getUser[0]['Name'].',<br><br>
                                        Your Venue Reservation approved by Approver 2,<br><br>
                                        Details Schedule : <br><ul>
                                        <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                        <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                        <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                        <li>Room  : '.$t_booking[0]['Room'].'</li>
                                        '.$Email_add_person.'
                                        '.$MarkomEmail.'
                                        </ul>
                                        '.$EmailKetAdditional.'
                                        '.$KetAdditional_eq.
                                        $Email_invitation.'</br>
                                    ';        
                            //$to = $Email;
                            $subject = "Podomoro University Venue Reservation Approved";
                            // $StartIcal = date("Ymd", strtotime($t_booking[0]['Start']));
                            $StartTimeIcal = date("His", strtotime($t_booking[0]['Start']));
                            //print_r($StartTimeIcal);die();
                            // $EndIcal = date("Ymd", strtotime($t_booking[0]['End']));
                            $EndTimeIcal = date("His", strtotime($t_booking[0]['End']));
                            // print_r($EndTimeIcal);die();
                            $sendEmail = $this->m_sendemail->sendEmailIcal($to,$subject,$text, $place,$StartIcal,$StartTimeIcal,$EndIcal,$EndTimeIcal);

                            // // markom & equipment
                                if ($MarkomEmail != '') {
                                    $e_markom = $this->m_master->caribasedprimary('db_employees.division','ID',17);
                                    $e_markom = $e_markom[0]['Email'];
                                    $Email = $e_markom;
                                    $to = $Email;

                                    // reminder
                                    $StartTimeIcal = '073000';
                                    $EndTimeIcal = '083000';
                                    $subject = "Reminder Venue Reservation";
                                    $text = 'Dear Team,<br><br>
                                                Reminder Venue Reservation,<br><br>
                                                Details Schedule : <br><ul>
                                                <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                                <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                                <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                                <li>Room  : '.$t_booking[0]['Room'].'</li>
                                                '.$Email_add_person.'
                                                '.$MarkomEmail.'
                                                </ul>
                                                '.$EmailKetAdditional.'
                                                '.$KetAdditional_eq.
                                                $Email_invitation.'</br>
                                            ';
                                    $sendEmail = $this->m_sendemail->sendEmailIcal($to,$subject,$text, $place,$StartIcal,$StartTimeIcal,$EndIcal,$EndTimeIcal);


                                    $text = 'Dear Team,<br><br>
                                                Venue Reservation schedule,<br><br>
                                                Details Schedule : <br><ul>
                                                <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                                <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                                <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                                <li>Room  : '.$t_booking[0]['Room'].'</li>
                                                '.$Email_add_person.'
                                                '.$MarkomEmail.'
                                                </ul>
                                                '.$EmailKetAdditional.'
                                                '.$KetAdditional_eq.
                                                $Email_invitation.'</br>
                                            '; 
                                    $subject = "Podomoro University Venue Reservation Approved";
                                    $StartTimeIcal = date("His", strtotime($t_booking[0]['Start']));
                                    $EndTimeIcal = date("His", strtotime($t_booking[0]['End']));               
                                    $sendEmail = $this->m_sendemail->sendEmailIcal($to,$subject,$text, $place,$StartIcal,$StartTimeIcal,$EndIcal,$EndTimeIcal);

                                }

                                if ($KetAdditional_eq != '') {
                                   for ($n=0; $n < count($e_div); $n++) { 
                                       $Email = $e_div[$n];
                                       $to = $Email;

                                       // reminder
                                       $subject = "Reminder Venue Reservation";
                                       $text = 'Dear Team,<br><br>
                                                   Reminder Venue Reservation,<br><br>
                                                   Details Schedule : <br><ul>
                                                   <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                                   <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                                   <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                                   <li>Room  : '.$t_booking[0]['Room'].'</li>
                                                   '.$Email_add_person.'
                                                   '.$MarkomEmail.'
                                                   </ul>
                                                   '.$EmailKetAdditional.'
                                                   '.$KetAdditional_eq.
                                                   $Email_invitation.'</br>
                                               ';
                                       $StartTimeIcal = '073000';
                                       $EndTimeIcal = '083000';
                                       $subject = "Reminder Venue Reservation";
                                       $sendEmail = $this->m_sendemail->sendEmailIcal($to,$subject,$text, $place,$StartIcal,$StartTimeIcal,$EndIcal,$EndTimeIcal);


                                       $text = 'Dear Team,<br><br>
                                                   Venue Reservation schedule by '.$getUser[0]['Name'].' as Requester,<br><br>
                                                   Details Schedule : <br><ul>
                                                   <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                                   <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                                   <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                                   <li>Room  : '.$t_booking[0]['Room'].'</li>
                                                   '.$Email_add_person.'
                                                   '.$MarkomEmail.'
                                                   </ul>
                                                   '.$EmailKetAdditional.'
                                                   '.$KetAdditional_eq.
                                                   $Email_invitation.'</br>
                                               ';
                                        $StartTimeIcal = date("His", strtotime($t_booking[0]['Start']));
                                        $EndTimeIcal = date("His", strtotime($t_booking[0]['End']));   
                                        $subject = "Podomoro University Venue Reservation Approved";     
                                       $sendEmail = $this->m_sendemail->sendEmailIcal($to,$subject,$text, $place,$StartIcal,$StartTimeIcal,$EndIcal,$EndTimeIcal);
                                    }
                                }
                            $data['include'] = $this->load->view('template/include','',true);
                            $this->load->view('template/venue_approve_page',$data);
                         }
                         else
                         {
                            print_r('<h2><b>Please Try Again !!!</b2></h2>');
                         }
                    }
                }
                else
                {
                    show_404($log_error = TRUE);
                }
            }
            else{
                // handling orang iseng
                echo '{"status":"999","message":"Data doesn\'t exist "}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function cancel_venue_markom($token)
    {
        // error_reporting(0);
        try 
        {
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($token,$key);

            // cek status
            $t_booking = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$data_arr['ID_t_booking']);
            
            if (count($t_booking ) > 0) {
             $ID_t_booking = $data_arr['ID_t_booking'];
                $Startdatetime = DateTime::createFromFormat('Y-m-d H:i:s', $t_booking[0]['Start']);
                $Enddatetime = DateTime::createFromFormat('Y-m-d H:i:s', $t_booking[0]['End']);
                $StartNameDay = $Startdatetime->format('l');
                $EndNameDay = $Enddatetime->format('l');
                $MarkomEmail = $data_arr['MarkomEmail'];
                $EmailKetAdditional = $data_arr['EmailKetAdditional'];
                $Email_add_person = $data_arr['Email_add_person'];
                $KetAdditional_eq = $data_arr['KetAdditional_eq'];
                // Markom Status harus sama dengan 1
                if ($t_booking[0]['MarcommStatus'] == 0) { 
                   echo '{"status":"999","message":"Data doesn\'t exist "}';
                   die();
                } 
                elseif ($t_booking[0]['MarcommStatus'] == 2) {
                    show_404($log_error = TRUE);
                    die();
                }
                elseif ($t_booking[0]['MarcommStatus'] == 3) {
                    show_404($log_error = TRUE);
                    die();
                }
                // end MarcommStatus

                // send email to approval 1
                   // email to approval 1
                         $getRoom = $this->m_master->caribasedprimary('db_academic.classroom','Room',$t_booking[0]['Room']);
                         $CategoryRoomByRoom = $getRoom[0]['ID_CategoryRoom'];
                         $getDataCategoryRoom = $this->m_master->caribasedprimary('db_reservation.category_room','ID',$CategoryRoomByRoom);
                         $Approver1 = $getDataCategoryRoom[0]['Approver1'];
                         $Approver1 = json_decode($Approver1);
                         // get user type
                             $CreatedBy = $t_booking[0]['CreatedBy'];
                             $getCreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$CreatedBy);

                         $FieldTbl = (array('MarcommStatus' => 3));
                         $this->db->where('ID',$data_arr['ID_t_booking']);
                         $this->db->update('db_reservation.t_booking', $FieldTbl);
                         if ($this->db->affected_rows() > 0 )
                          {
                             // user
                                 $getUser = $this->m_master->caribasedprimary('db_employees.employees','NIP',$t_booking[0]['CreatedBy']);
                                 $Email = $getUser[0]['EmailPU'];

                                 $text = 'Dear Mr/Mrs '.$getUser[0]['Name'].',<br><br>
                                             Your Venue Reservation was cancel by '.'Markom Division'.',<br><br>
                                             Details Schedule : <br><ul>
                                             <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                             <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                             <li>Room  : '.$t_booking[0]['Room'].'</li>
                                             <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                             </ul>
                                             '.$MarkomEmail.'
                                             '.$Email_add_person.'
                                             </ul>
                                             '.$EmailKetAdditional.'
                                               '.$KetAdditional_eq.'</br>
                                         ';        
                                 $to = $Email;
                                 $subject = "Podomoro University Venue Reservation Approved";
                                 $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                                 $data['include'] = $this->load->view('template/include','',true);
                             $this->load->view('template/response_cancel_page',$data);
                          }
                          else
                          {
                             print_r('<h2><b>Please Try Again !!!</b2></h2>');
                          }
            }
            else{
                // handling orang iseng
                echo '{"status":"999","message":"Data doesn\'t exist "}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        } 
    }

    public function cancel_venue($token)
    {
        //error_reporting(0);
        try 
        {
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($token,$key);
            // cek status
            $t_booking = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$data_arr['ID_t_booking']);
            
            if (count($t_booking ) > 0) {
                $data['include'] = $this->load->view('template/include','',true);
                $data['t_booking'] = $t_booking;
                $data['Code'] = $data_arr['Code'];
                $data['Approver'] = ($data_arr['approvalNo'] == 2) ? 'Approver 2' : 'Approver 1';

                // $data['Email_add_person'] = $data_arr['Email_add_person'];
                // $data['MarkomEmail'] = $data_arr['MarkomEmail']; // get status below
                // $data['EmailKetAdditional'] = $data_arr['EmailKetAdditional'];
                // $data['KetAdditional_eq'] = $data_arr['KetAdditional_eq'];  // get status below
                $this->load->view('template/cancel_approve_page',$data);
            }
            else{
                // handling orang iseng
                echo '{"status":"999","message":"Data doesn\'t exist "}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function submitcancelvenue()
    {
        // error_reporting(0);
        try {
            $dataToken = $this->getInputToken();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $get = $dataToken['t_booking'];
                $getUser = $this->m_master->caribasedprimary('db_employees.employees','NIP',$get[0]->CreatedBy);
                $getE_additional = $this->m_master->caribasedprimary('db_reservation.t_booking_eq_additional','ID_t_booking',$get[0]->ID);
                $KetAdditional = json_decode($get[0]->KetAdditional);    
                $EmailKetAdditional = '';
                if ($KetAdditional != '') {
                    if (count($KetAdditional) > 0) {
                        foreach ($KetAdditional as $key => $value) {
                            if ($value != "" || $value != null) {
                                // $EmailKetAdditional .= '<br>*   '.$key.'('.$value.')';
                                $EmailKetAdditional .= '<br>*   '.str_replace("_", " ", $key).' : '.$value;    
                            }  
                        }
                    }
                    
                }

                $Reason = 'Cancel by yourself';
                if(array_key_exists("Reason",$dataToken))
                {
                    $Reason = $dataToken['Reason'];
                }

                for ($i=0; $i < count($getE_additional); $i++) { 
                    $dataSave = array(
                        'ID_t_booking_eq_add' => $getE_additional[$i]['ID'],
                        'ID_t_booking' => $get[0]->ID,
                        'ID_equipment_additional' => $getE_additional[$i]['ID_equipment_additional'],
                        'Qty' => $getE_additional[$i]['Qty'],
                    );
                    $this->db->insert('db_reservation.t_booking_eq_additional_delete', $dataSave); 
                }
                
                $sql = "delete from db_reservation.t_booking_eq_additional where ID_t_booking = ".$get[0]->ID;
                $query=$this->db->query($sql, array());

                $dataSave = array(
                    'Start' => $get[0]->Start,
                    'End' => $get[0]->End,
                    'Time' => $get[0]->Time,
                    'Colspan' => $get[0]->Colspan,
                    'Agenda' => $get[0]->Agenda,
                    'Room' => $get[0]->Room,
                    'ID_equipment_add' => $get[0]->ID_equipment_add,
                    'ID_add_personel' => $get[0]->ID_add_personel,
                    'Req_date' => $get[0]->Req_date,
                    'CreatedBy' => $get[0]->CreatedBy,
                    'ID_t_booking' => $get[0]->ID,
                    'Note_deleted' => 'Cancel By User##'.$Reason,
                    'DeletedBy' => $dataToken['Code'],
                    'Req_layout' => $get[0]->Req_layout,
                    'Status' => $get[0]->Status,
                    'MarcommSupport' => $get[0]->MarcommSupport,
                    'KetAdditional' => $get[0]->KetAdditional,
                );
                $this->db->insert('db_reservation.t_booking_delete', $dataSave); 

                $this->m_master->delete_id_table_all_db($get[0]->ID,'db_reservation.t_booking');
            // send email
                
                $Startdatetime = DateTime::createFromFormat('Y-m-d H:i:s', $get[0]->Start);
                $Enddatetime = DateTime::createFromFormat('Y-m-d H:i:s', $get[0]->End);
                $StartNameDay = $Startdatetime->format('l');
                $EndNameDay = $Enddatetime->format('l');

                $Email = 'alhadi.rahman@podomorouniversity.ac.id';
                if($_SERVER['SERVER_NAME']!='localhost') {
                    $Email = $getUser[0]['EmailPU'];
                }
                
                $text = 'Dear Mr/Mrs '.$getUser[0]['Name'].',<br><br>
                            Your Venue Reservation was Cancel by '.$dataToken['Approver'].',<br><br>
                            Details Schedule : <br><ul>
                            <li>Start  : '.$StartNameDay.', '.$get[0]->Start.'</li>
                            <li>End  : '.$EndNameDay.', '.$get[0]->End.'</li>
                            <li>Room  : '.$get[0]->Room.'</li>
                            <li>Agenda  : '.$get[0]->Agenda.'</li>
                            <li>Reason : '.$Reason.'</li>
                            </ul>

                            '.$EmailKetAdditional.'
                        ';        
                $to = $Email;
                $subject = "Podomoro University Venue Reservation Cancel Reservation";
                $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);    

                // send email to administratif
                $getEmail = $this->m_master->showData_array('db_reservation.email_to');
                if($_SERVER['SERVER_NAME']!='localhost') {
                    for ($i=0; $i < count($getEmail); $i++) {
                        if ($i != 0) {
                             $Email = $getEmail[$i]['Email'];
                             $text = 'Dear Mr/Mrs '.$getEmail[$i]['Ownership'].',<br><br>
                                         Venue Reservation was Cancel by '.$dataToken['Approver'].',<br><br>
                                         Details Schedule : <br><ul>
                                         <li>Start  : '.$StartNameDay.', '.$get[0]->Start.'</li>
                                         <li>End  : '.$EndNameDay.', '.$get[0]->End.'</li>
                                         <li>Room  : '.$get[0]->Room.'</li>
                                         <li>Agenda  : '.$get[0]->Agenda.'</li>
                                         <li>Reason : '.$Reason.'</li>
                                         </ul>

                                         '.$EmailKetAdditional.'
                                     ';        
                             $to = $Email;
                             $subject = "Podomoro University Venue Reservation Cancel Reservation";
                             $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                        } 
                    }

                }
                else
                {
                    $Email = $getEmail[0]['Email'];
                    $text = 'Dear Mr/Mrs '.$getEmail[0]['Ownership'].',<br><br>
                                Venue Reservation was Cancel by '.$dataToken['Approver'].',<br><br>
                                Details Schedule : <br><ul>
                                <li>Start  : '.$StartNameDay.', '.$get[0]->Start.'</li>
                                <li>End  : '.$EndNameDay.', '.$get[0]->End.'</li>
                                <li>Room  : '.$get[0]->Room.'</li>
                                <li>Agenda  : '.$get[0]->Agenda.'</li>
                                <li>Reason : '.$Reason.'</li>
                                </ul>

                                '.$EmailKetAdditional.'
                            ';        
                    $to = $Email;
                    $subject = "Podomoro University Venue Reservation Cancel Reservation";
                    $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                }
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function vreservation_confirm_eq_additional()
    {
        try {
            $dataToken = $this->getInputToken();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $msg = '';
                $idtbooking = $dataToken['idtbooking'];
                $action = $dataToken['action'];
                $arr_eq = (array)$dataToken['arr_eq'];
                $this->load->model('vreservation/m_reservation');
                for ($i=0; $i < count($arr_eq); $i++) {
                    $chkQty = ($action == 'Reject') ? true : $this->m_reservation->chkQty_eq_additional2($idtbooking,$arr_eq[$i]);
                    // $chkQty = $this->m_reservation->chkQty_eq_additional2($idtbooking,$arr_eq[$i]);
                    if ($chkQty) {
                        $datasave = array(
                            'ApproveBy' => $this->session->userdata('NIP'),
                            'ApproveAt' => date('Y-m-d H:i:s'),
                            'Status' => ($action == 'Confirm') ? 1 : 2,
                        );

                        $this->db->where('ID_t_booking',$idtbooking);
                        $this->db->where('ID_equipment_additional',$arr_eq[$i]);
                        $this->db->update('db_reservation.t_booking_eq_additional', $datasave);
                    }
                    else
                    {
                        $msg = 'Qty is not enough';
                        break;
                    }
                    

                }
                echo json_encode($msg);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function vreservation_confirm_markom_support()
    {
        try {
            $dataToken = $this->getInputToken();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $msg = '';
                $idtbooking = $dataToken['idtbooking'];
                $action = $dataToken['action'];
                $arr_eq = (array)$dataToken['arr_eq'];
                $this->load->model('vreservation/m_reservation');
                for ($i=0; $i < count($arr_eq); $i++) {
                    $datasave = array(
                            'ApproveBy' => $this->session->userdata('NIP'),
                            'ApproveAt' => date('Y-m-d H:i:s'),
                            'Status' => ($action == 'Confirm') ? 1 : 2,
                        );

                    $this->db->where('ID_t_booking',$idtbooking);
                    $this->db->where('ID_m_markom_support',$arr_eq[$i]);
                    $this->db->update('db_reservation.t_markom_support', $datasave);

                }
                echo json_encode($msg);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function view_eq_additional($token)
    {
        // error_reporting(0);
        try 
        {
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($token,$key);
            // cek status
            $t_booking = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$data_arr['ID_t_booking']);
            if (count($t_booking ) > 0) {
                if ($t_booking[0]['Status'] == 0) {
                    $data['include'] = $this->load->view('template/include','',true);
                    $data['ID_t_booking'] = $data_arr['ID_t_booking'];
                    $data['EmailPU'] = $data_arr['EmailPU'];
                    $data['DivisionID'] = $data_arr['DivisionID'];
                    $this->load->view('page/vreservation/t_view_eq_additional',$data);
                }
            }
            else{
                // handling orang iseng
                echo '{"status":"999","message":"Data doesn\'t exist "}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function vreservation_page_feedback($token)
    {
        try {
            //print_r($token);die();
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($token,$key);
            $auth = $this->m_master->AuthAPI($data_arr);
            if ($auth) {
                $data = $data_arr['data'];
                $DateLimit = $data_arr['Datelimit'];
                $chk = $this->m_master->chkTgl(date('Y-m-d'),$DateLimit);
                // updated data if exist
                if ($chk) {
                    for ($i=0; $i < count($data); $i++) { 
                        $c = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$data[$i]->ID);
                        $fbck =  $c[0]['Feedback'];
                        $data[$i]->Feedback = $fbck;
                    }
                    $this->data['data'] = $data;
                    $this->data['include'] = $this->load->view('template/include','',true);
                    $this->load->view('page/vreservation/t_view_feedback',$this->data);
                }
                else
                {
                    echo '{"status":"404","message":"Link expired"}';
                }
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
        

    }

    public function vreservation_api_feedback()
    {
        try {
            $key = "UAP)(*";
            $data_arr = $this->getInputToken();
            $auth = $this->m_master->AuthAPI($data_arr);
            if ($auth) {
                $datasave = array(
                    'Feedback' => $data_arr['Feedback'],
                    'FeedbackAt' => date('Y-m-d H:i:s')
                );
                $this->db->where('ID',$data_arr['id_key']);
                $this->db->update('db_reservation.t_booking', $datasave);
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function summary_use_room()
    {
        try {
            $key = "UAP)(*";
            $data_arr = $this->getInputToken();
            $auth = $this->m_master->AuthAPI($data_arr);
            if ($auth) {
                $rs = array();
                $this->load->model('vreservation/m_reservation');

                $url = url_pas.'api/__crudClassroomVreservation';
                $data = array(
                        'action' => 'read',
                    );
                $JWT = new JWT();
                $Input = $JWT->encode($data,"UAP)(*");
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,
                            "token=".$Input);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $r = curl_exec($ch);
                $r = (array)json_decode($r,true);
                //print_r($pr);
                curl_close ($ch);


                $condition = array(
                    'date1' => $data_arr['date1'],
                    'date2' => $data_arr['date2'],
                );
                for ($i=0; $i < count($r); $i++) { 
                   $Room = $r[$i]['Room'];
                   $Usage = $this->m_reservation->getUsagePerRoom($Room,$condition);
                   $r[$i]['Usage'] = $Usage;
                   $rs[] = $r[$i];
                }

                echo json_encode($rs);
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function detailroom()
    {
        try {
            $key = "UAP)(*";
            $data_arr = $this->getInputToken();
            $auth = $this->m_master->AuthAPI($data_arr);
            if ($auth) {
                $rs = array();
                $this->load->model('vreservation/m_reservation');
                $con1 = '';
                if (array_key_exists('date1', $data_arr) && array_key_exists('date2', $data_arr)) {
                    if ($data_arr['date1'] != '' && $data_arr['date2'] != '') {
                        $con1 = 'DATE_FORMAT(a.Start,"%Y-%m-%d") >= "'.$data_arr['date1'].'" and DATE_FORMAT(a.Start,"%Y-%m-%d") <= "'.$data_arr['date2'].'" and ';
                    }
                }
                $condition = $con1.' a.Status = 1 and a.Room = "'.$data_arr['room'].'"';
                $g = $this->m_reservation->getDataT_info('','',$condition);
                echo json_encode($g);
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }


    // ==== Exchange Schedule =====

    public function exchange_approved($token){

        $data = $this->getInputTokenGet($token);
        $content = $this->load->view('global/academic/attendance/schedule_exchange_approve',$data,true);
        $this->template_blank($content);
    }

    public function exchange_rejected($token){

        $data = $this->getInputTokenGet($token);
        $content = $this->load->view('global/academic/attendance/schedule_exchange_rejecte',$data,true);
        $this->template_blank($content);
    }

    public function modify_attendance($token,$action){

        $data = $this->getInputTokenGet($token);
        $data['token'] = $token;
        $data['action'] = strtolower($action);

        $content = $this->load->view('global/academic/attendance/modify_attendance',$data,true);
        $this->template_blank($content);
    }

    public function genrateBarcode($code)
    {
        //load library
        $this->load->library('zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');
        //generate barcode
        Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
    }

    public function getBarcodeExam(){

//        $data = $this->db->query('SELECT ex.*,em.Name AS Peng1, em2.Name AS Peng2 FROM db_academic.exam ex
//                                            LEFT JOIN db_academic.exam_group exg ON (exg.ExamID = ex.ID)
//                                            LEFT JOIN db_employees.employees em ON (em.NIP = ex.Pengawas1)
//                                            LEFT JOIN db_employees.employees em2 ON (em2.NIP = ex.Pengawas2)
//                                            GROUP BY ex.ID
//                                            WHERE ex.SemesterID = 14 AND ex.UTS = "uts" ')->result_array();
//
//        print_r($data);
//        exit;
        $this->load->view('global/academic/exam');
    }


    public function upload_files2(){

        $fileName = $this->input->get('name');
        $id = $this->input->get('id');

        $config['upload_path']          = './uploads/files/';
        $config['allowed_types']        = 'pdf';
        $config['max_size']             = 5000; // 5 mb
        $config['file_name']            = $fileName;


        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());

            // Get file lama
            $dataC = $this->db->limit(1)->get_where('db_employees.files',array(
                'ID' => $id
            ))->result_array();

            if(count($dataC)>0){
                if($dataC[0]['LinkFiles']==null || $dataC[0]['LinkFiles']==''){
                    $this->db->where('ID', $id);
                    $this->db->delete('db_employees.files');
                }
            }



            return print_r(json_encode($error));
        }
        else {


            // Get file lama
            $dataC = $this->db->limit(1)->get_where('db_employees.files',array(
                'ID' => $id
            ))->result_array();

            if(count($dataC)>0){
                $d = $dataC[0];
                $fileLama = $d['LinkFiles'];
                if(is_file('./uploads/files/'.$fileLama)){
                    unlink('./uploads/files/'.$fileLama);
                }
            }

            $success = array('success' => $this->upload->data());
            $success['success']['formGrade'] = 0;

            $this->db->set('LinkFiles', $fileName);
            $this->db->where('ID', $id);
            $this->db->update('db_employees.files');

//            return print_r(json_encode($success));
            return print_r(json_encode(array(
                'Upload' => 'Success'
            )));
        }



    }

    public function menu_request($page){
        $data['page'] = $page;
        $content = $this->load->view('page/rektorat/menu_rektorat',$data,true);
        //$this->temp($content);
    }


    public function getlistrequestdoc(){

        $page = $this->load->view('page/rektorat/listrek_requestdoc','',true);
        $this->menu_request($page);

    }

    public function error_page($url)
    {
        $data['include'] = $this->load->view('template/include','',true);
        $this->load->view('template/404page',$data);
    }


}
