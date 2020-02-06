<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class M_sendemail extends CI_Model {

    private $VariableClass= array('text' => null,
        'smtp_host' => null,
        'smtp_port' => null,
        'smtp_user' => null,
        'smtp_pass' => null
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function loadEmailConfig()
    {
        $config_email_db = $this->config_email_db();
        return $config_email_db;
    }

    public function config_email_db()
    {

        if ($this->VariableClass['smtp_host'] != null) {
            $config = array('setting' => array(
                'protocol' => 'smtp',
                'smtp_host' => $this->VariableClass['smtp_host'],
                'smtp_port' => $this->VariableClass['smtp_port'],
                'smtp_user' => $this->VariableClass['smtp_user'],
                'smtp_pass' => $this->VariableClass['smtp_pass'],
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            ),
                'text' => $this->VariableClass['text'],

            );
        }
        else
        {
            $sql = "select * from db_admission.email_set as a limit 1";
            $query=$this->db->query($sql, array())->result_array();
            $config = array('setting' => array(
                'protocol' => 'smtp',
                'smtp_host' => $query[0]['smtp_host'],
                'smtp_port' => $query[0]['smtp_port'],
                'smtp_user' => $query[0]['email'],
                'smtp_pass' => $query[0]['pass'],
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            ),
                'text' => $query[0]['text'],

            );
        }

        return $config;
    }

    public function textEmail($text = null, $title = null)
    {

        $titleHead = ($title!=null) ? $title : '';

        if ($text == null) {
            $text1 = '<div style="margin:0;padding:10px ;background-color:#ebebeb;font-size:14px;line-height:20px;font-family:Helvetica,sans-serif;width:100%;">
    <div class="adM">
        <br>
    </div>
    <table style="width:600px;margin:0 auto;background-color:#ebebeb" border="0" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <td></td>
            <td style="background-color:#fff;padding:0 30px;color:#333;vertical-align:top;border:1px solid #cccccc;">
                <br>
                <div style="text-align: center;">
                    <img src="https://lh3.googleusercontent.com/mkqZdtpCm7IfWWrPdfxJBETqOTiEU09s3cr4tzfFwAGRl3WqH_pyo3yDGPKmpSHfMw1mSFU0JTRk-3yX9M7xAG5KiVHzuMS1DPHzFg=w500-h144-rw-no" style="max-width: 250px;">
                    <hr style="border-top: 1px solid #cccccc;"/>
                    <div style="font-family:Proxima Nova Semi-bold,Helvetica,sans-serif;font-weight:bold;font-size:24px;line-height:24px;color:#607D8B">Registration</div>
                </div>
                <br/>
                Dear <strong>Tono</strong>,
                <br/>
                <br/>
                To get a full formulir registration.
                <br/>
                Please transfer to :
                <br/>
                <br/>
                <div style="background: #00bcd414;border: 1px solid #2196f36e;min-height: 50px;width: 270px;margin: 0 auto;text-align: center;padding: 10px;">
                    <b style="color: blue;">BNI Virtual Account</b>
                    <br/>
                    <div style="color: red;"><b>9880020200000006</b></div>
                    <br/>
                    <span style="color: #827f7f;">Due date : 9 January 2019</span>
                </div>

                <div style="background: #ffeb3b47;border: 1px solid #2196f36e;min-height: 10px;width: 270px;margin: 0 auto;text-align: center;padding: 10px;margin-top: 10px;">
                    <b style="color: #333;font-size: 16px;">Rp. 200.000, -</b>
                </div>
                <br/>

                <b>Please wait next step after your payment.</b>
                <br/>
                <br/>

                <div style="background: #efefef; padding: 10px;border: 1px solid #cccccc;">
                    <strong>Note :</strong>
                    If we do not receive your payment until the time limit specified then Your Account will be suspended
                </div>
                <br><br>
                <p style="color:#EB6936;"><i>*) Do not reply, this email is sent automatically</i> </p>

            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
                <div style="background-color:#fff;border-top:1px solid #ddd; ">
                </div>
            </td>
        </tbody>
    </table>
</div>';
        }
        else
        {
            $text1 = '<div style="margin:0;padding:10px 0;background-color:#ebebeb;font-size:14px;line-height:20px;font-family:Helvetica,sans-serif;width:100%;text-align:center">
                    <div class="adM">
                    <br>
                    </div>
                    <table style="width:600px;margin:0 auto;background-color:#ebebeb" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td></td>
                            <td style="background-color:#fff;padding:0 30px;color:#333;vertical-align:top;border:1px solid #cccccc;">
                            <br>
                            <div style="text-align: center;">
                                <img src="https://lh3.googleusercontent.com/mkqZdtpCm7IfWWrPdfxJBETqOTiEU09s3cr4tzfFwAGRl3WqH_pyo3yDGPKmpSHfMw1mSFU0JTRk-3yX9M7xAG5KiVHzuMS1DPHzFg=w500-h144-rw-no" style="max-width: 250px;">
                                <hr style="border-top: 1px solid #cccccc;"/>
                                <div style="font-family:Proxima Nova Semi-bold,Helvetica,sans-serif;font-weight:bold;font-size:24px;line-height:24px;color:#607D8B">Notification '.$titleHead.'</div>
                            </div>
                            <br/>
                            <div style="font-family:Proxima Nova Reg,Helvetica,sans-serif">
                            <div style="max-width:600px;margin:30px 0;display:block;font-size:14px;text-align:left!important">
                            '.$text.'
                            <br><br>Best Regard, <br> IT Podomoro University
                            <br><br><br>
                            <p style="color:#EB6936;"><i>*) Do not reply, this email is sent automatically</i> </p>
                            </div>
                            </td>
                            <td></td>
                            </tr>
                            <tr>
                            <td colspan="3">
                            <div style="background-color:#fff;border-top:1px solid #ddd; ">';
        }

        return $this->VariableClass['text'] = $text1;

    }

    public function sendEmail($to = null,$subject = null,$smtp_host = null,$smtp_port = null,$smtp_user = null,$smtp_pass = null,$text = null, $attach = null, $title = null,$cc=null,$bcc=null)
    {
        $arr = array(
            'status' => 1,
            'msg'=>''
        );
        $this->VariableClass['smtp_host'] = $smtp_host;
        $this->VariableClass['smtp_port'] = $smtp_port;
        $this->VariableClass['smtp_user'] = $smtp_user;
        $this->VariableClass['smtp_pass'] = $smtp_pass;
        $this->VariableClass['text'] = $text;

        $config_email = $this->loadEmailConfig();
        $textEmail = $this->textEmail($this->VariableClass['text'],$title);
        $max_execution_time = 630;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes
        $BilingID = mt_rand();// unique number
        $this->load->library('email', $config_email['setting']);
        $this->email->set_newline("\r\n");
        $this->email->from('pu@podomorouniversity.ac.id','PU Notifications');
        $this->email->to($to);
        /*ADDED BY FEBRI @ JAN 2020*/
        if(!empty($cc)){
            $this->email->cc($cc);
        }
        if(!empty($bcc)){
            $this->email->bcc($bcc);
        }
        /*ADDED BY FEBRI @ JAN 2020*/
        $this->email->subject($subject.' - '.$BilingID);
        $this->email->message($this->VariableClass['text']);
        if ($attach != null) {
            $this->email->attach($attach);
        }
        if($this->email->send())
        {
            $arr['status'] = 1;
            $arr['msg'] = "Email Send";
        }
        else
        {
            $arr['status'] = 0;
            $arr['msg'] = $this->email->print_debugger();
        }
        return $arr;
    }

    public function save_email($smtp_host,$smtp_port,$email,$pwd,$text)
    {
        // check existing email ada atau tidak
        $sql = "select * from db_admission.email_set limit 1";
        $data = array();
        $query=$this->db->query($sql, array())->result();

        if ($smtp_host != "" || $smtp_port != "" || $email != "" || $pwd != "") {
            if (count($query) > 0 ) {
                # update
                foreach ($query as $key) {
                    $data = array(
                        'smtp_host' => $smtp_host,
                        'smtp_port' => $smtp_port,
                        'email' => $email,
                        'pass' => $pwd,
                        'text' => $text
                    );
                }

                $this->db->set($data)
                    ->update('db_admission.email_set');
            }
            else
            {
                # insert
                $dataSave = array(
                    'smtp_host' => $smtp_host,
                    'smtp_port' => $smtp_port,
                    'email' => $email,
                    'pass' => $pwd,
                    'text' => $text
                );

                $this->db->insert('db_admission.email_set', $dataSave);

            }
        }
        return "Done";
    }

    public function getToEmail($function = null)
    {
        $email_to = "it@podomorouniversity.ac.id";
        if ($function != null) {
            $arr_temp = array();
            $sql = "select EmailTo from db_admission.email_to as a where Active = 1 and Function = ?";
            $query=$this->db->query($sql, array($function))->result();
            foreach ($query as $key) {
                $arr_temp[] = $key->EmailTo;
            }
            $email_to = implode($arr_temp, ",");
        }
        return $email_to;
    }

    public function getUserToResetPassword($email,$username){

        $dataMhs = $this->db->query('SELECT ast.Name FROM db_academic.auth_students ast 
                                            WHERE ast.NPM = "'.$username.'" AND ast.EmailPU LIKE "'.$email.'" ')->result_array();

        $result = $dataMhs;
        if(count($dataMhs)<=0){
            $dataLecturer = $this->db->query('SELECT em.Name FROM db_employees.employees em 
                                                      WHERE em.NIP = "'.$username.'" 
                                                          AND em.EmailPU LIKE "'.$email.'" ')->result_array();
            $result = $dataLecturer;
        }

        return $result;

    }

    public function sendEmailIcal($to = null,$subject = null,$text = null, $place,$Start,$StartTime,$End,$EndTime,$attach = null,$title = null,$smtp_host = null,$smtp_port = null,$smtp_user = null,$smtp_pass = null)
    {

        // $Start = date("Y-m-d H:i:s", strtotime($input['date'].$input['Start']));

        $arr = array(
            'status' => 1,
            'msg'=>''
        );
        // $this->VariableClass['smtp_host'] = $smtp_host;
        // $this->VariableClass['smtp_port'] = $smtp_port;
        // $this->VariableClass['smtp_user'] = $smtp_user;
        // $this->VariableClass['smtp_pass'] = $smtp_pass;
        $this->VariableClass['text'] = $text;

        $config_email = $this->loadEmailConfig();
        $textEmail = $this->textEmail($this->VariableClass['text'],$title);
        
        //Load Composer's autoloader
        include_once APPPATH.'vendor/autoload.php';

        $mail = new PHPMailer(true);     
        $event_id = 1234;
        $sequence = 0;
        $status = 'TENTATIVE';
        // event params
        $BilingID = mt_rand();// unique number
        $subject = $subject.' '.$BilingID;
        $summary = $subject;
        $venue = $place;
        $start = $Start; // 20181105
        $start_time = $StartTime; // 143530
        $end = $End; // 20181105
        $end_time = $EndTime; // 150630

        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'ssl://smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'ithelpdesk.notif@podomorouniversity.ac.id';                 // SMTP username
        $mail->Password = 'Podomoro2018';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;
        $mail->IsHTML(true);
        $mail->msgHTML($textEmail);    

        // $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        // $mail->isSMTP();                                      // Set mailer to use SMTP
        // $mail->Host = $this->VariableClass['smtp_host'];  // Specify main and backup SMTP servers
        // $mail->SMTPAuth = true;                               // Enable SMTP authentication
        // $mail->Username = $this->VariableClass['smtp_user'];                 // SMTP username
        // $mail->Password = $this->VariableClass['smtp_pass'];                           // SMTP password
        // $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        // $mail->Port = $this->VariableClass['smtp_port'];    
        // $mail->IsHTML(false);

        $mail->setFrom('ithelpdesk.notif@podomorouniversity.ac.id', 'PU Notifications');
        // $mail->addReplyTo('alhadi.rahman@podomorouniversity.ac.id', 'IT');
        $to = explode(',', $to);
        // print_r($to);die(); 
        $EmailToArr = array();
        for ($i=0; $i < count($to); $i++) {
            if ($i == 0) {
                 $mail->addAddress($to[$i],'User '.$i);
            }
            else{
                $mail->AddCC($to[$i]);
            } 
            $EmailToArr[] = $to[$i];
        }
       
        $EmailTo = implode(',', $EmailToArr);
        $mail->ContentType = 'text/calendar';

        $mail->Subject = $subject ;
        // $mail->addCustomHeader('MIME-version',"1.0");
        // $mail->addCustomHeader('Content-type',"text/calendar; method=REQUEST; charset=UTF-8");
        // $mail->addCustomHeader('Content-Transfer-Encoding',"7bit");
        // $mail->addCustomHeader('X-Mailer',"Microsoft Office Outlook 12.0");
        // $mail->addCustomHeader("Content-class: urn:content-classes:calendarmessage");

        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//YourCassavaLtd//EateriesDept//EN\r\n";
        $ical .= "METHOD:REQUEST\r\n";
        $ical .= "BEGIN:VEVENT\r\n";
        $ical .= "ORGANIZER;SENT-BY=\"MAILTO:it@podomorouniversity.ac.id\":MAILTO:it@podomorouniversity.ac.id\r\n";
        $ical .= "ATTENDEE;CN=".$EmailTo.";ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED;RSVP=TRUE:mailto:".$EmailTo."\r\n";
        $ical .= "UID:".strtoupper(md5($event_id))."-podomorouniversity.ac.id\r\n";
        $ical .= "SEQUENCE:".$sequence."\r\n";
        $ical .= "STATUS:".$status."\r\n";
        $ical .= "DTSTAMPTZID=Asia/Jakarta:".date('Y-m-d').'T'.date('H:i:s')."\r\n";
        $ical .= "DTSTART:".$start."T".$start_time."\r\n";
        $ical .= "DTEND:".$end."T".$end_time."\r\n";
        $ical .= "LOCATION:".$venue."\r\n";
        $ical .= "SUMMARY:".$summary."\r\n";
        // $ical .= "DESCRIPTION:".$textEmail."\r\n"; 
        $ical .= "X-ALT-DESC;FMTTYPE=text/html:".$textEmail."\r\n"; 
        $ical .= "BEGIN:VALARM\r\n";
        // $ical .= "TRIGGER:-PT1440M\r\n";
        $ical .= "TRIGGER:-PT15M\r\n";
        $ical .= "ACTION:DISPLAY\r\n";
        $ical .= "DESCRIPTION:Reminder\r\n";
        $ical .= "END:VALARM\r\n";
        $ical .= "END:VEVENT\r\n";
        $ical .= "END:VCALENDAR\r\n";
        // print_r($ical);die();
        // $mail->Body = $ical;
        $mail->addStringAttachment($ical,'ical.ics','base64','text/calendar');
        //send the message, check for errors
        if(!$mail->send()) {
            $arr['status'] = 0;
            $arr['msg'] = "Email error : ".$mail->ErrorInfo;
            // $this->error = "Mailer Error: " . $mail->ErrorInfo;
            // return false;
        } else {
            $arr['status'] = 1;
            $arr['msg'] = "Email Send";
            // $this->error = "Message sent!";
            // return true;
        }

    }

    public function abc($htmlMsg)
    {
        $temp = str_replace(array("\r\n"),"\n",$htmlMsg);
        $lines = explode("\n",$temp);
        $new_lines =array();
        foreach($lines as $i => $line)
        {
            if(!empty($line))
            $new_lines[]=trim($line);
        }
        $desc = implode("\r\n ",$new_lines);
        return $desc;
    }

    public function resend_email_admission_no_record($to,$subject,$text = array(null,null,null,null,null)){
        $this->load->model('master/m_master');
        $arr = array(
            'status' => 1,
            'msg'=>'',
            'email' => [],
            );
        $config_email = $this->loadEmailConfig();
        // $getDeadline = $this->getDeadline();
        $getDeadline = $text[2];
        $max_execution_time = 630;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes

        // $url_upload_bayar = base_url().'register/formupload/'.$text[0];
        $this->load->library('email', $config_email['setting']);
        $msg = '<div style="margin:0;padding:10px 0;background-color:#ebebeb;font-size:14px;line-height:20px;font-family:Helvetica,sans-serif;width:100%">
                    <div class="adM">
                    <br>
                    </div>
                    <table style="width:600px;margin:0 auto;background-color:#ebebeb" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td></td>
                            <td style="background-color:#fff;padding:0 30px;color:#333;vertical-align:top;border:1px solid #cccccc;">
                            <br>
                            <div style="text-align: center;">
                                <img src="https://lh3.googleusercontent.com/mkqZdtpCm7IfWWrPdfxJBETqOTiEU09s3cr4tzfFwAGRl3WqH_pyo3yDGPKmpSHfMw1mSFU0JTRk-3yX9M7xAG5KiVHzuMS1DPHzFg=w500-h144-rw-no" style="max-width: 250px;">
                                <hr style="border-top: 1px solid #cccccc;"/>
                                <div style="font-family:Proxima Nova Semi-bold,Helvetica,sans-serif;font-weight:bold;font-size:24px;line-height:24px;color:#607D8B">Registration</div>
                            </div>
                            <br/>';
        // $SHeader = '<div style="font-family:Proxima Nova Semi-bold,Helvetica,sans-serif;font-weight:bold;font-size:24px;line-height:24px;color:#2196f3">';
        // $EHeader = '</div>';
        $msg .= $config_email['text'];
        
        $style1 = '<div style="background: #00bcd414;border: 1px solid #2196f36e;min-height: 50px;width: 400px;margin: 0 auto;text-align: center;padding: 10px;">
                    <b style="color: blue;">Rek BCA : 161.3888.555</b>
                    <div style="color: red;"><b>';
        $style2 = '</b></div>
                    <span style="color: #827f7f;">';
        $style3 = '</span>
                </div>';
        $style4 = '<div style="background: #ffeb3b47;border: 1px solid #2196f36e;min-height: 10px;width: 270px;margin: 0 auto;text-align: center;padding: 10px;margin-top: 10px;">
                    <b style="color: #333;font-size: 16px;">';
        $style5   = '</b>
                </div>';                                                 
        $payment = "Rp. ".number_format($text[1],2,",",".");
        $msg = str_replace('[#Candidate]', $text[3], $msg);
        $msg = str_replace('[#payment]', $payment, $msg);
        $deadline = $getDeadline;
        $deadline = explode(' ', $deadline);
        $deadline0 = $this->m_master->getIndoBulan($deadline[0]);
        $deadline1 = $deadline[1];
        $deadline = $deadline0.' Time : '.$deadline1;

        $NoRek = "Atas Nama : Yayasan Pendidikan Agung Podomoro";

        // $msg = str_replace('[#deadline]', $deadline, $msg);

        $msg = str_replace('[#VA]', $NoRek, $msg);
        $msg = str_replace("[#styleheader1]", $style1, $msg);
        $msg = str_replace("[#styleheader2]", $style2, $msg);
        // $msg = str_replace("[#styleheader3]", $style3, $msg);
        $msg = str_replace("[#styleheader4]", $style4, $msg);
        $msg = str_replace("[#styleheader5]", $style5, $msg);

        $msg .= '<div style="background: #efefef; padding: 10px;border: 1px solid #cccccc;">
                    <strong>Note :</strong>
                    If we do not receive your payment until the time limit specified then Your Account will be suspended
                </div>
                <br><br>
                <p style="color:#EB6936;"><i>*) Do not reply, this email is sent automatically</i> </p>';

        $this->email->set_newline("\r\n");
        // $this->email->from('it@podomorouniversity.ac.id','IT Podomoro University');
        $this->email->from('pu@podomorouniversity.ac.id','PU Notifications');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($msg);
        //var_dump($this->email->send());
        if($this->email->send())
        {
          $arr['status'] = 1;
          $arr['msg'] = "Email Send";
          $arr['email'] = [
            'to' => $to,
            'subject' => $subject,
            'msg' => $msg,
          ];
        }
        else
        {
            $arr['status'] = 0;
            $arr['msg'] = $this->email->print_debugger();
        }
        return $arr;
    }


    public function ResendEmail($to = null,$subject = null,$smtp_host = null,$smtp_port = null,$smtp_user = null,$smtp_pass = null,$text = null, $attach = null, $title = null,$cc=null,$bcc=null)
    {
        $arr = array(
            'status' => 1,
            'msg'=>''
        );
        $this->VariableClass['smtp_host'] = $smtp_host;
        $this->VariableClass['smtp_port'] = $smtp_port;
        $this->VariableClass['smtp_user'] = $smtp_user;
        $this->VariableClass['smtp_pass'] = $smtp_pass;

        $config_email = $this->loadEmailConfig();
        $max_execution_time = 630;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes
        $BilingID = mt_rand();// unique number
        $this->load->library('email', $config_email['setting']);
        $this->email->set_newline("\r\n");
        $this->email->from('pu@podomorouniversity.ac.id','PU Notifications');
        $this->email->to($to);
        /*ADDED BY FEBRI @ JAN 2020*/
        if(!empty($cc)){
            $this->email->cc($cc);
        }
        if(!empty($bcc)){
            $this->email->bcc($bcc);
        }
    
        $this->email->subject($subject.' - '.$BilingID);
        $this->email->message($text);
        if ($attach != null) {
            $this->email->attach($attach);
        }
        if($this->email->send())
        {
            $arr['status'] = 1;
            $arr['msg'] = "Email Send";
        }
        else
        {
            $arr['status'] = 0;
            $arr['msg'] = $this->email->print_debugger();
        }
        return $arr;
    }


}