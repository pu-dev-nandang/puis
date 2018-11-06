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

}
