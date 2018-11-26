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

    public function getInputToken()
    {
        $token = $this->input->post('token');
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
        // cek apakah ikut ujian atau tidak
        $get = $this->m_master->caribasedprimary('db_admission.register_butuh_ujian','ID_register_formulir',$ID_register_formulir);
        if (count($get) == 0) {
            $get2 = $this->m_master->caribasedprimary('db_admission.register_nilai','ID_register_formulir',$ID_register_formulir);
            for ($i=0; $i < count($get2); $i++) { 
                $NamaUjian = $this->m_master->caribasedprimary('db_admission.ujian_perprody_m','ID',$get2[$i]['ID_ujian_perprody']);
                $get2[$i] = $get2[$i] + array('NamaUjian' => $NamaUjian[0]['NamaUjian'],'Bobot' => $NamaUjian[0]['Bobot']);
            }
            $query = $get2;
        }
        else
        {
            $this->load->model('admission/m_admission');
            $get2 = $this->m_admission->getHasilUjian($ID_register_formulir);
            $query = $get2;
        }
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
        $sql = 'select NIP from db_employees.employees WHERE Status > 0 ';
        $query=$this->db->query($sql, array())->result_array();
        // 3 administrative
        for ($i=0; $i < count($query); $i++) { 
            $NIP = $query[$i]['NIP'];
            // check NIP existing
            $get = $this->m_master->caribasedprimary('db_reservation.previleges_guser','NIP',$NIP);
            if (count($get) == 0) {
                $dataSave = array(
                    'NIP' => $NIP,
                    'G_user' => 4,
                );
                $this->db->insert('db_reservation.previleges_guser', $dataSave);
            }

        }

    }

    public function testInject2()
    {
        $get = $this->m_master->showData_array('db_admission.sale_formulir_offline');
        for ($i=0; $i < count($get); $i++) { 
            $ID = $get[$i]['ID'];
            $FullName = strtolower($get[$i]['FullName']);
            $FullName = ucwords($FullName);
            $dataSave = array(
                    'FullName' => ucwords($FullName),
                    'Email' => strtolower($get[$i]['Email'])
                            );
            $this->db->where('ID',$ID);
            $this->db->update('db_admission.sale_formulir_offline', $dataSave);
        }


    }

    public function testInject3()
    {
        $get = $this->m_master->showData_array('db_admission.register');
        for ($i=0; $i < count($get); $i++) { 
            $ID = $get[$i]['ID'];
            $FullName = strtolower($get[$i]['Name']);
            $FullName = ucwords($FullName);
            $dataSave = array(
                    'Name' => ucwords($FullName),
                    'Email' => strtolower($get[$i]['Email'])
                            );
            $this->db->where('ID',$ID);
            $this->db->update('db_admission.register', $dataSave);
        }


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

        $sql = 'select ID from db_admission.register_formulir where ID not in (select ID_register_formulir from db_admission.register_document)';
        $query=$this->db->query($sql, array())->result_array();
        for ($i=0; $i < count($query); $i++) {
            $ID_register_formulir = $query[$i]['ID']; 
            $arrID_reg_doc_checklist = $this->m_master->caribasedprimary('db_admission.reg_doc_checklist','Active',1);
            for ($xy=0; $xy < count($arrID_reg_doc_checklist); $xy++) { 
                $dataSave = array(
                        'ID_register_formulir' => $ID_register_formulir,
                        'ID_reg_doc_checklist' => $arrID_reg_doc_checklist[$xy]['ID'],
                                );

                $this->db->insert('db_admission.register_document', $dataSave);
            }
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

    public function approve_venue_markom($token)
    {
       error_reporting(0);
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
                
               // Markom Status harus sama dengan 1
               if ($t_booking[0]['MarcommStatus'] == 0) { 
                  echo '{"status":"999","message":"Data doesn\'t exist "}';
                  die();
               } 
               elseif ($t_booking[0]['MarcommStatus'] == 2) {
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
                            $sql = 'select a.* from db_reservation.cfg_policy as a join db_reservation.cfg_group_user as b on a.ID_group_user = b.ID join db_reservation.previleges_guser as c 
                                    on b.ID = c.G_user where c.NIP = ? limit 1';
                            $query=$this->db->query($sql, array($CreatedBy))->result_array();
                            
                        $ID_group_user = $query[0]['ID_group_user'];
                        $dataApprover = array();
                        for ($l=0; $l < count($Approver1); $l++) {
                            // find by ID_group_user
                                if ($ID_group_user == $Approver1[$l]->UserType) {
                                    // get TypeApprover
                                    $TypeApprover = $Approver1[$l]->TypeApprover;
                                    switch ($TypeApprover) {
                                        case 'Position':
                                            // get Division to access position approval
                                                $PositionMain = $getCreatedBy[0]['PositionMain'];
                                                $PositionMain = explode('.', $PositionMain);
                                                $IDDivision = $PositionMain[0];
                                                $IDPositionApprover = $Approver1[$l]->Approver; 
                                                if ($IDDivision == 15) { // if prodi
                                                    // find prodi
                                                    $sqlgg = 'select * from db_academic.program_study where AdminID = ? or KaprodiID = ?';
                                                    $gg=$this->db->query($sql, array($CreatedBy,$CreatedBy))->result_array();
                                                    if (count($gg) > 0) {
                                                        for ($k=0; $k < count($gg); $k++) { 
                                                            $Kaprodi = $gg[$k]['KaprodiID'];
                                                            $getApprover1 = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Kaprodi);
                                                            for ($m=0; $m < count($getApprover1); $m++) { 
                                                                if ($getApprover1[$k]['StatusEmployeeID'] > 0) {
                                                                     $dataApprover[] = array('Email' => $getApprover1[$k]['EmailPU'],'Name' => $getApprover1[$k]['Name'],'Code' => $Kaprodi,'TypeApprover' => $TypeApprover);
                                                                }
                                                            }
                                                        }
                                                        
                                                    }
                                                }
                                                else
                                                {
                                                    // find by division and position
                                                    $getApprover1 = $this->m_master->caribasedprimary('db_employees.employees','PositionMain',$IDDivision.'.'.$IDPositionApprover);
                                                    for ($k=0; $k < count($getApprover1); $k++) {
                                                        if ($getApprover1[$k]['StatusEmployeeID'] > 0) {
                                                             $dataApprover[] = array('Email' => $getApprover1[$k]['EmailPU'],'Name' => $getApprover1[$k]['Name'],'Code' => $getApprover1[$k]['NIP'],'TypeApprover' => $TypeApprover);
                                                        } 
                                                       
                                                    }
                                                }
                                            break;
                                        
                                        case 'Division':
                                            $getApprover1 = $this->m_master->caribasedprimary('db_employees.division','ID',$Approver1[$l]->Approver);
                                            for ($k=0; $k < count($getApprover1); $k++) { 
                                               $dataApprover[] = array('Email' => $getApprover1[$k]['Email'],'Name' => $getApprover1[$k]['Division'],'Code' => $getApprover1[$k]['ID'],'TypeApprover' => $TypeApprover);
                                            }
                                            break;

                                        case 'Employees':
                                            $getApprover1 = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver1[$l]->Approver);
                                            for ($k=0; $k < count($getApprover1); $k++) { 
                                               $dataApprover[] = array('Email' => $getApprover1[$k]['EmailPU'],'Name' => $getApprover1[$k]['Name'],'Code' => $getApprover1[$k]['NIP'],'TypeApprover' => $TypeApprover);
                                            }
                                            break;    
                                    }
                                }
                        } // end loop for
                
                        if($_SERVER['SERVER_NAME']!='localhost') {
                            // send email
                            $EmailPU = '';
                            $NameEmail = '';
                            $Code = '';
                            $TypeApproverE = '';
                            if (count($dataApprover) > 0) {
                                $temp = array();
                                $temp2 = array();
                                $temp3 = array();
                                for ($k=0; $k < count($dataApprover); $k++) { 
                                    $EM = $dataApprover[$k]['Email'];
                                    $NM = $dataApprover[$k]['Name'];
                                    $Code = $dataApprover[$k]['Code'];
                                    $temp[] = $EM;
                                    $temp2[] = $NM;
                                    $temp3[] = $Code;
                                }
                                 
                                $EmailPU = implode(",", $temp);
                                $NameEmail = implode(" / ", $temp2);
                                $Code = implode(";", $temp3);
                            }
                            
                            if ($EmailPU != '' || $EmailPU != null) {
                                $token = array(
                                    'EmailPU' => $EmailPU,
                                    'Code' => $Code,
                                    'ID_t_booking' => $ID_t_booking,
                                    'approvalNo' => 1,
                                );
                                $token = $this->jwt->encode($token,'UAP)(*');
                                $Email = $EmailPU;
                                $text = 'Dear Mr/Mrs '.$NameEmail.',<br><br>
                                            Please help to approve Venue Reservation requested by '.$getCreatedBy[0]['Name'].',<br><br>
                                            Details Schedule : <br><ul>
                                            <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                            <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                            <li>Room  : '.$t_booking[0]['Room'].'</li>
                                            <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                            '.$MarkomEmail.'
                                            </ul>
                                            '.$EmailKetAdditional.' </br>
                                            <table width="100" cellspacing="0" cellpadding="12" border="0">
                                                <tbody>
                                                <tr>
                                                    <td bgcolor="#51a351" align="center">
                                                        <a href="'.url_pas.'approve_venue/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #51a351;" target="_blank" >Approve</a>
                                                    </td>
                                                    <td align="center">
                                                       -
                                                    </td>
                                                    <td bgcolor="#red" align="center">
                                                        <a href="'.url_pas.'cancel_venue/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #red;" target="_blank" >Reject</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        ';        
                                $to = $Email;
                                $subject = "Podomoro University Venue Reservation Approval 1";
                                $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

                            }
                        }
                        else
                        {
                            // send email
                            $EmailPU = '';
                            $NameEmail = '';
                            $Code = '';
                            if (count($dataApprover) > 0) {
                                $temp = array();
                                $temp2 = array();
                                $temp3 = array();
                                for ($k=0; $k < count($dataApprover); $k++) { 
                                    $EM = $dataApprover[$k]['Email'];
                                    $NM = $dataApprover[$k]['Name'];
                                    $Code = $dataApprover[$k]['Code'];
                                    $temp[] = $EM;
                                    $temp2[] = $NM;
                                    $temp3[] = $Code;
                                }
                                 
                                $EmailPU = implode(",", $temp);
                                $NameEmail = implode(" / ", $temp2);
                                $Code = implode(";", $temp3);
                            }
                            
                            if ($EmailPU != '' || $EmailPU != null) {
                                $token = array(
                                    'EmailPU' => $EmailPU,
                                    'Code' => $Code,
                                    'ID_t_booking' => $ID_t_booking,
                                    'approvalNo' => 1,
                                );
                                $token = $this->jwt->encode($token,'UAP)(*');
                                $Email = 'alhadi.rahman@podomorouniversity.ac.id';
                                $text = 'Dear Mr/Mrs '.$NameEmail.',<br><br>
                                            Please help to approve Venue Reservation requested by '.$getCreatedBy[0]['Name'].',<br><br>
                                            Details Schedule : <br><ul>
                                            <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                            <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                            <li>Room  : '.$t_booking[0]['Room'].'</li>
                                            <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                            '.$MarkomEmail.'
                                            </ul>
                                            '.$EmailKetAdditional.' </br>
                                            <table width="200" cellspacing="0" cellpadding="12" border="0">
                                                <tbody>
                                                <tr>
                                                    <td bgcolor="#51a351" align="center">
                                                        <a href="'.url_pas.'approve_venue/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #51a351;" target="_blank" >Approve</a>
                                                    </td>
                                                    <td align="center">
                                                      -
                                                    </td>
                                                    <td bgcolor="#e98180" align="center">
                                                        <a href="'.url_pas.'cancel_venue/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #e98180;" target="_blank" >Reject</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        ';        
                                $to = $Email;
                                $subject = "Podomoro University Venue Reservation Approval 1";
                                $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

                            }
                        }

                        $FieldTbl = (array('MarcommStatus' => 2));
                        $this->db->where('ID',$data_arr['ID_t_booking']);
                        $this->db->update('db_reservation.t_booking', $FieldTbl);
                        if ($this->db->affected_rows() > 0 )
                         {
                            // user
                                $getUser = $this->m_master->caribasedprimary('db_employees.employees','NIP',$t_booking[0]['CreatedBy']);
                                $Email = $getUser[0]['EmailPU'];

                                $text = 'Dear Mr/Mrs '.$getUser[0]['Name'].',<br><br>
                                            Your Venue Reservation approved by '.'Markom Division'.',<br><br>
                                            Details Schedule : <br><ul>
                                            <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                            <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                            <li>Room  : '.$t_booking[0]['Room'].'</li>
                                            <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                            </ul>
                                            '.$EmailKetAdditional.'
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
           else{
               // handling orang iseng
               echo '{"status":"999","message":"Data doesn\'t exist "}';
           }
       }
       //catch exception
       catch(Exception $e) {
         // handling orang iseng
         echo '{"status":"999","message":"jangan iseng :D"}';
       } 
    }

    public function approve_venue($token)
    {
        error_reporting(0);
        try 
        {
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($token,$key);
            // cek status
            $t_booking = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$data_arr['ID_t_booking']);
            
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
                                        $token = array(
                                            'EmailPU' => 'ga@podomorouniversity.ac.id',
                                            'Code' => 8,
                                            'ID_t_booking' => $data_arr['ID_t_booking'],
                                            'approvalNo' => 2,
                                        );
                                        $token = $this->jwt->encode($token,'UAP)(*');
                                        if($_SERVER['SERVER_NAME']!='localhost') {
                                            // email to ga
                                            $Email = 'ga@podomorouniversity.ac.id';
                                            $text = 'Dear GA Team,<br><br>
                                                        Please help to approve Venue Reservation,<br><br>
                                                        Details Schedule : <br><ul>
                                                        <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                                        <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                                        <li>Room  : '.$t_booking[0]['Room'].'</li>
                                                        <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                                        </ul>
                                                        '.$EmailKetAdditional.' </br>
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
                                            $Email = 'alhadi.rahman@podomorouniversity.ac.id';
                                            $text = 'Dear GA Team,<br><br>
                                                        Please help to approve Venue Reservation,<br><br>
                                                        Details Schedule : <br><ul>
                                                        <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                                        <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                                        <li>Room  : '.$t_booking[0]['Room'].'</li>
                                                        <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                                        </ul>
                                                        '.$EmailKetAdditional.' </br>
                                                        <table width="50" cellspacing="0" cellpadding="12" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td bgcolor="#51a351" align="center">
                                                                    <a href="'.url_pas.'approve_venue/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #51a351;" target="_blank" >Approve</a>
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
                                                    </ul>
                                                    '.$EmailKetAdditional.'
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
                            $text = 'Dear Mr/Mrs '.$getUser[0]['Name'].',<br><br>
                                        Your Venue Reservation approved by Approver 2,<br><br>
                                        Details Schedule : <br><ul>
                                        <li>Start  : '.$StartNameDay.', '.$t_booking[0]['Start'].'</li>
                                        <li>End  : '.$EndNameDay.', '.$t_booking[0]['End'].'</li>
                                        <li>Agenda  : '.$t_booking[0]['Agenda'].'</li>
                                        <li>Room  : '.$t_booking[0]['Room'].'</li>
                                        </ul>
                                        '.$EmailKetAdditional.'
                                    ';        
                            $to = $Email;
                            $subject = "Podomoro University Venue Reservation Approved";
                            $place  = $t_booking[0]['Room'];
                            $StartIcal = date("Ymd", strtotime($t_booking[0]['Start']));
                            $StartTimeIcal = date("His", strtotime($t_booking[0]['Start']));
                            $EndIcal = date("Ymd", strtotime($t_booking[0]['End']));
                            $EndTimeIcal = date("His", strtotime($t_booking[0]['End']));
                            // print_r($EndTimeIcal);die();
                            $sendEmail = $this->m_sendemail->sendEmailIcal($to,$subject,$text, $place,$StartIcal,$StartTimeIcal,$EndIcal,$EndTimeIcal);
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
          echo '{"status":"999","message":"jangan iseng :D"}';
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
                // Markom Status harus sama dengan 1
                if ($t_booking[0]['MarcommStatus'] == 0) { 
                   echo '{"status":"999","message":"Data doesn\'t exist "}';
                   die();
                } 
                elseif ($t_booking[0]['MarcommStatus'] == 2) {
                    show_404($log_error = TRUE);
                    die();
                }
                // end MarcommStatus
                $data['include'] = $this->load->view('template/include','',true);
                $data['t_booking'] = $t_booking;
                $data['Code'] = $data_arr['Code'];
                $data['Approver'] = 'Markom Division';
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
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function cancel_venue($token)
    {
        error_reporting(0);
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
          echo '{"status":"999","message":"jangan iseng :D"}';
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
                if(array_key_exists("Reason",$input))
                {
                    $Reason = $input['Reason'];
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
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

}
