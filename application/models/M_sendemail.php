<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

    public function textEmail($text = null)
    {
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
                    <img src="http://siak.podomorouniversity.ac.id/logo_tr.png" style="max-width: 250px;">
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
                                <img src="http://siak.podomorouniversity.ac.id/logo_tr.png" style="max-width: 250px;">
                                <hr style="border-top: 1px solid #cccccc;"/>
                                <div style="font-family:Proxima Nova Semi-bold,Helvetica,sans-serif;font-weight:bold;font-size:24px;line-height:24px;color:#607D8B">Alert</div>
                            </div>
                            <br/>
                            <div style="font-family:Proxima Nova Reg,Helvetica,sans-serif">
                            <div style="max-width:600px;margin:30px 0;display:block;font-size:14px;text-align:left!important">
                            '.$text.'
                            <br><br>Best Regard, <br> IT Podomoro University (it@podomorouniversity.ac.id)
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

    public function sendEmail($to = null,$subject = null,$smtp_host = null,$smtp_port = null,$smtp_user = null,$smtp_pass = null,$text = null, $attach = null)
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
        $textEmail = $this->textEmail($this->VariableClass['text']);
        $max_execution_time = 630;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes

        $this->load->library('email', $config_email['setting']);
        $this->email->set_newline("\r\n");
        $this->email->from('it@podomorouniversity.ac.id','IT Podomoro University');
        $this->email->to($to);
        $this->email->subject($subject);
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

}