<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_api2 extends CI_Controller {

    var $DummyEmail = 'nandang.mulyadi@podomorouniversity.ac.id';

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('master/m_master');
        $this->load->model('hr/m_hr');
        $this->load->model('vreservation/m_reservation');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('notification/m_log');
        $this->load->library('JWT');
        $this->load->library('google');

    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function getInputToken2($token)
    {
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function is_url_exist($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code == 200){
            $status = true;
        }else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    private function getInputTokenGet($token)
    {
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function sendMailRest($data){
        $url = ($_SERVER['SERVER_NAME']=='localhost')
            ? 'http://pcam.podomorouniversity.ac.id/rest/__sendEmail'
            : base_url('rest/__sendEmail');
        $Input = $this->jwt->encode($data,"UAP)(*");

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "token=".$Input);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $pr = curl_exec($ch);
        curl_close ($ch);
    }


    public function crudScheduleExchage(){
        $data_arr = $this->getInputToken();

        if (count($data_arr) > 0) {

            if($data_arr['action']=='approveExchange'){

                $dataSceduleExchange = $this->db->query('SELECT exc.*,em.Name AS Kaprodi, em2.Name AS Adum FROM db_academic.schedule_exchange exc
                                                                    LEFT JOIN db_employees.employees em ON (em.NIP = exc.Updated1By)
                                                                    LEFT JOIN db_employees.employees em2 ON (em2.NIP = exc.Updated2By)
                                                                    WHERE exc.ID = "'.$data_arr['EXID'].'" LIMIT 1 ')
                                        ->result_array();

                // Cek apakah sudah update atau belum jika sudah maka
                // 1. tidak perlu update lagi
                // 2. tidak perlu kirim email ke adum

                if(count($dataSceduleExchange)>0){

                    $d = $dataSceduleExchange[0];
                    if($d['Status']=='0' || $d['Status']==0){

                        $key = 's3Cr3T-G4N';
                        $token = $dataSceduleExchange[0]['Token'];
                        $dataToken = (array) $this->jwt->decode($token,$key);

                        // Get Name Kaprodi
                        $dataKaprodi = $this->db->select('Name,Photo')->get_where('db_employees.employees',
                            array('NIP' => $data_arr['ApprovedBy']))->result_array();

                        $bodyEmail = '<div>
                                            Dear <span style="color: #333;">General Affair</span>,
                                            <br/>
                                            Perihal : <b>Permohonan Ruangan Untuk Kuliah Pengganti</b>
                        
                                            <br/>
                                            <br/>
                                            <div style="font-size: 14px;">
                                                <table  width="100%" cellspacing="0" cellpadding="1" border="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width: 20%;">Dosen</td>
                                                        <td style="width: 2%;">:</td>
                                                        <td style="width: 40%;">'.$dataToken['Lecturer'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Program Studi</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['Prodi'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="color: #673AB7;">Mengajukan permohonan untuk kuliah pengganti</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mata Kuliah</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['Course'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Group Kelas</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['ClassGroup'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sesi (Pertemuan ke)</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['Meeting'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jadwal Semula</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['ScheduleExist'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Diganti Pada</td>
                                                        <td>:</td>
                                                        <td style="color: green;font-weight: bolder;">'.$dataToken['ScheduleExchange'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alasan</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['Reason'].'</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <br/>
                                                <p>
                                                    Demikian permohonan ini kami ajukan, mohon dapat diproses sesuai dengan ketentuan yang berlaku. Terima kasih
                                                </p>
                                                <br/>
                                                <br/>
                                                <table  width="100%" cellspacing="5" cellpadding="1" border="0">
                                                    <tr>
                                                        <td style="width: 100%;" align="center">
                                                            Approved By
                                                            <br/>
                                                            <h3 style="color: #009688;margin-top: 7px;">'.$dataKaprodi[0]['Name'].'
                                                                <br/>
                                                            <small>'.$data_arr['ApprovedBy'].'</small>
                                                            </h3>
                                                        </td>
                        
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>';

                        $emailGa = $this->db->select('Email')->get_where('db_employees.division',array('ID' => 8))->result_array();

                        $mailTo = (count($emailGa)>0)
                            ? $emailGa[0]['Email']
                            : $this->DummyEmail;


                        $data = array(
                            'to' => $mailTo,
                            'subject' => 'Kaprodi : Permohonan Ruangan Kelas Pengganti',
                            'text' => $bodyEmail,
                            'auth' => 's3Cr3T-G4N'
                        );

                        $this->sendMailRest($data);

                        // Update Status
                        $dataUpdate = array(
                            'Updated1At' => $data_arr['ApprovedAt'],
                            'Updated1By' => $data_arr['ApprovedBy'],
                            'Status' => '1',
                        );
                        $this->db->where('ID', $data_arr['EXID']);
                        $this->db->update('db_academic.schedule_exchange',$dataUpdate);


                        //============= Logging ==========
                        // Insert Logging
                        $url = base_url('uploads/employees/'.$dataKaprodi[0]['Photo']);
                        $img_profile = ($this->is_url_exist($url) && $dataKaprodi[0]['Photo']!='')
                            ? $url
                            : url_server_ws.'/images/icon/lecturer.png';

                        $Log_dataInsert = array(
                            'Icon' => $img_profile,
                            'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Schedule Exchange Approved',
                            'Description' => 'Kaprodi : Schedule Exchange Approved',
                            'URLDirect' => 'ga_schedule_exchange',
                            'URLDirectLecturer' => 'attendance/schedule-exchange',
                            'CreatedBy' => $data_arr['ApprovedBy'],
                            'CreatedName' => $dataKaprodi[0]['Name'],
                            'CreatedAt' => $data_arr['ApprovedAt'],
                        );

                        $this->db->insert('db_notifikasi.logging',$Log_dataInsert);
                        $insert_id_logging = $this->db->insert_id();

                        // insert ke user
                        $Log_arr_ins = array(
                            'IDLogging' => $insert_id_logging,
                            'UserID' => $d['NIP']
                        );
                        $this->db->insert('db_notifikasi.logging_user',$Log_arr_ins);

                        // Get Member Adum
                        $dataUserAdum = $this->db->select('NIP')->get_where('db_employees.rule_users',
                            array('IDDivision' => 8))->result_array();

                        if(count($dataUserAdum)>0){
                            foreach ($dataUserAdum as $item){
                                $Log_arr_ins = array(
                                    'IDLogging' => $insert_id_logging,
                                    'UserID' => $item['NIP']
                                );
                                $this->db->insert('db_notifikasi.logging_user',$Log_arr_ins);
                            }
                        }



                        $result = array(
                            'Status' => '1',
                            'Message' => 'Approved',
                            'By' => $dataKaprodi[0]['Name']
                        );

                    }
                    else if($d['Status']=='1' || $d['Status']==1 || $d['Status']=='2' || $d['Status']==2) {
                        $result = array(
                            'Status' => $d['Status'],
                            'Message' => 'Approved',
                            'By' => $d['Kaprodi']
                        );
                    }
                    else if($d['Status']=='-1' || $d['Status']==-1){
                        $result = array(
                            'Status' => $d['Status'],
                            'Message' => 'Rejected',
                            'Comment' => $d['Comment'],
                            'By' => $d['Kaprodi']
                        );
                    }
                    else if($d['Status']=='-2' || $d['Status']==-2){
                        $result = array(
                            'Status' => $d['Status'],
                            'Message' => 'Rejected',
                            'Comment' => $d['Comment'],
                            'By' => $d['Adum']
                        );
                    }



                } else {
                    $result = array(
                      'Message' => 'Data Not Yet'
                    );
                }

                return print_r(json_encode($result));


            }
            else if($data_arr['action']=='rejectedExchange'){

                $dataSceduleExchange = $this->db->query('SELECT exc.*,em.Name AS Lecturer, em.EmailPU FROM db_academic.schedule_exchange exc
                                                                    LEFT JOIN db_employees.employees em ON (em.NIP = exc.NIP)
                                                                    WHERE exc.ID = "'.$data_arr['EXID'].'" LIMIT 1 ')
                    ->result_array();

                if(count($dataSceduleExchange)>0){

                    $key = 's3Cr3T-G4N';
                    $token = $dataSceduleExchange[0]['Token'];
                    $dataToken = (array) $this->jwt->decode($token,$key);

                    // Get Name Kaprodi
                    $dataKaprodi = $this->db->select('Name,Photo')->get_where('db_employees.employees',
                        array('NIP' => $data_arr['ApprovedBy']))->result_array();

                    $bodyEmail = '<div>
                                            Dear <span style="color: #333;">'.$dataSceduleExchange[0]['Lecturer'].'</span>,
                                            <br/>
                                            Perihal : <b>Permohonan Ruangan Untuk Kuliah Pengganti</b>
                        
                                            <br/>
                                            <br/>
                                            
                                            <div style="background: lightyellow;color: red;border: 1px solid red; text-align: center;padding: 7px;margin-bottom: 10px;">
                                                <h2 style="margin-top: 7px;margin-bottom: 0px;">Permohonan Ditolak</h2>
                                                <p style="color: blue;margin-top: 3px;">'.$data_arr['Comment'].'</p>
                                            </div>
                                            
                                            <div style="text-align: center;">
                                                <p>--- Detail permohonan ---</p>
                                            </div>
                                            
                                            <div style="font-size: 14px;">
                                                <table  width="100%" cellspacing="0" cellpadding="1" border="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width: 20%;">Dosen</td>
                                                        <td style="width: 2%;">:</td>
                                                        <td style="width: 40%;">'.$dataToken['Lecturer'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Program Studi</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['Prodi'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="color: #673AB7;">Mengajukan permohonan untuk kuliah pengganti</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mata Kuliah</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['Course'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Group Kelas</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['ClassGroup'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sesi (Pertemuan ke)</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['Meeting'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jadwal Semula</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['ScheduleExist'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Diganti Pada</td>
                                                        <td>:</td>
                                                        <td style="color: green;font-weight: bolder;">'.$dataToken['ScheduleExchange'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alasan</td>
                                                        <td>:</td>
                                                        <td>'.$dataToken['Reason'].'</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <br/>
                                                <p>
                                                    Demikian permohonan ini kami ajukan, mohon dapat diproses sesuai dengan ketentuan yang berlaku. Terima kasih
                                                </p>
                                                <br/>
                                                <br/>
                                                <table  width="100%" cellspacing="5" cellpadding="1" border="0">
                                                    <tr>
                                                        <td style="width: 100%;" align="center">
                                                            Rejected By
                                                            <br/>
                                                            <h3 style="color: #009688;margin-top: 7px;">'.$dataKaprodi[0]['Name'].'
                                                                <br/>
                                                            <small>'.$data_arr['ApprovedBy'].'</small>
                                                            </h3>
                                                        </td>
                        
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>';

                    $mailTo = (count($dataSceduleExchange)>0)
                        ? $dataSceduleExchange[0]['EmailPU']
                        : $this->DummyEmail;

                    $data = array(
                        'to' => $mailTo,
                        'subject' => 'Kaprodi : Permohonan Ruangan Kelas Pengganti',
                        'text' => $bodyEmail,
                        'auth' => 's3Cr3T-G4N'
                    );

                    $this->sendMailRest($data);


                    $dataUpdate =  array(
                        'Updated1At' => $data_arr['ApprovedAt'],
                        'Updated1By' => $data_arr['ApprovedBy'],
                        'Comment' => $data_arr['Comment'],
                        'Status' => '-1',
                    );
                    $this->db->where('ID', $data_arr['EXID']);
                    $this->db->update('db_academic.schedule_exchange',$dataUpdate);


                    //============= Logging ==========
                    // Insert Logging

                    $url = base_url('uploads/employees/'.$dataKaprodi[0]['Photo']);
                    $img_profile = ($this->is_url_exist($url) && $dataKaprodi[0]['Photo']!='')
                        ? $url
                        : url_server_ws.'/images/icon/lecturer.png';

                    $Log_dataInsert = array(
                        'Icon' => $img_profile,
                        'Title' => '<i class="fa fa-times-circle margin-right" style="color:darkred;"></i> Schedule Exchange Rejected',
                        'Description' => 'Kaprodi : Schedule Exchange Rejected',
                        'URLDirect' => 'ga_schedule_exchange',
                        'URLDirectLecturer' => 'attendance/schedule-exchange',
                        'CreatedBy' => $data_arr['ApprovedBy'],
                        'CreatedName' => $dataKaprodi[0]['Name'],
                        'CreatedAt' => $data_arr['ApprovedAt'],
                    );


                    $this->db->insert('db_notifikasi.logging',$Log_dataInsert);
                    $insert_id_logging = $this->db->insert_id();

                    // insert ke user
                    $Log_arr_ins = array(
                        'IDLogging' => $insert_id_logging,
                        'UserID' => $dataSceduleExchange[0]['NIP']
                    );
                    $this->db->insert('db_notifikasi.logging_user',$Log_arr_ins);

                }



                return print_r(1);

            }
            else if($data_arr['action']=='readStatusExchange'){
                // Cek apakah sudah di approve atau belum
                $dataSceduleExchange = $this->db->query('SELECT exc.*,em.Name AS Kaprodi, em2.Name AS Adum FROM db_academic.schedule_exchange exc
                                                                    LEFT JOIN db_employees.employees em ON (em.NIP = exc.Updated1By)
                                                                    LEFT JOIN db_employees.employees em2 ON (em2.NIP = exc.Updated2By)
                                                                    WHERE exc.ID = "'.$data_arr['EXID'].'" LIMIT 1 ')
                    ->result_array();

                if(count($dataSceduleExchange)>0){
                    $d = $dataSceduleExchange[0];
                    if($d['Status']=='0' || $d['Status']==0){

                        $result = array(
                            'Status' => $d['Status']
                        );

                    }
                    else if($d['Status']=='1' || $d['Status']==1 || $d['Status']=='2' || $d['Status']==2){
                        $result = array(
                            'Status' => $d['Status'],
                            'Message' => 'Approved',
                            'By' => $d['Kaprodi']
                        );
                    }
                    else if($d['Status']=='-1' || $d['Status']==-1){
                        $result = array(
                            'Status' => $d['Status'],
                            'Message' => 'Rejected',
                            'Comment' => $d['Comment'],
                            'By' => $d['Kaprodi']
                        );
                    }
                    else if($d['Status']=='-2' || $d['Status']==-2){
                        $result = array(
                            'Status' => $d['Status'],
                            'Message' => 'Rejected',
                            'Comment' => $d['Comment'],
                            'By' => $d['Adum']
                        );
                    }
                }
                else {
                    $result = array(
                        'Message' => 'Data Not Yet'
                    );
                }

                return print_r(json_encode($result));
            }


        }

    }

    public function crudModifyAttendance(){
        $data_arr = $this->getInputToken();
        if (count($data_arr) > 0) {

            if ($data_arr['action'] == 'checkStatusModifyAttd') {

                $IDAM = $data_arr['IDAM'];

                $data = $this->db->query('SELECT am.Status, em.Name AS Lecturer, am.Reason_Reject AS Reason FROM db_academic.attendance_modify am 
                                                    LEFT JOIN db_employees.employees em ON (em.NIP = am.Updated1By)
                                                    WHERE am.ID = "'.$IDAM.'" ')->result_array();


                return print_r(json_encode($data));

            }
            else if($data_arr['action']=='rejectedModifyAttd'){

                $dataRequsted = $this->db->query('SELECT am.RequestBy, em.EmailPU, em.Name AS Lecturer, em.Gender, am.DataEmail, am.TokenURL FROM db_academic.attendance_modify am 
                                                            LEFT JOIN db_employees.employees em ON (em.NIP = am.RequestBy)
                                                            WHERE am.ID = "'.$data_arr['IDAM'].'" ')->result_array();


                $dataUpdate = array(
                    'Reason_Reject' => $data_arr['Reason'],
                    'Updated1By' => $data_arr['Updated1By'],
                    'Updated1At' => $data_arr['Updated1At'],
                    'Status' => '-1'
                );
                $this->db->where('ID', $data_arr['IDAM']);
                $this->db->update('db_academic.attendance_modify',$dataUpdate);


                $dataKaprodi = $this->db->select('Name,Photo')->get_where('db_employees.employees',
                    array('NIP' => $data_arr['Updated1By']))->result_array();

                if(count($dataKaprodi)>0){

                    $DataEmail = $this->getInputToken2($dataRequsted[0]['DataEmail']);

                    //============= Logging ==========
                    // Insert Logging
                    $url = base_url('uploads/employees/'.$dataKaprodi[0]['Photo']);
                    $img_profile = ($this->is_url_exist($url) && $dataKaprodi[0]['Photo']!='')
                        ? $url
                        : url_server_ws.'/images/icon/lecturer.png';

                    $Log_dataInsert = array(
                        'Icon' => $img_profile,
                        'Title' => '<i class="fa fa-times-circle margin-right" style="color:darkred;"></i> Modify Attendance Rejected',
                        'Description' => $DataEmail['Code'].' - '.$DataEmail['CourseEng'].' | Group : '.$DataEmail['Group'].' | Session : '.$DataEmail['Session'],
                        'URLDirectLecturer' => 'attendance/modify-attendance/'.$dataRequsted[0]['TokenURL'],
                        'CreatedBy' => $data_arr['Updated1By'],
                        'CreatedName' => $dataKaprodi[0]['Name'],
                        'CreatedAt' => $data_arr['Updated1At'],
                    );

                    $this->db->insert('db_notifikasi.logging',$Log_dataInsert);
                    $insert_id_logging = $this->db->insert_id();

                    // insert ke user
                    $Log_arr_ins = array(
                        'IDLogging' => $insert_id_logging,
                        'UserID' => $dataRequsted[0]['RequestBy']
                    );
                    $this->db->insert('db_notifikasi.logging_user',$Log_arr_ins);

                    // Send Email
                    $mailTo = (count($dataRequsted)>0)
                        ? $dataRequsted[0]['EmailPU']
                        : $this->DummyEmail;




                    $greating = ($dataRequsted[0]['Gender']=='L')  ? 'Bapak' : 'Ibu';
                    $bodyEmail = '<div>
                    Dear <span style="color: #333;">'.$greating.' '.ucwords($dataRequsted[0]['Lecturer']).'</span>,
                    <br/>
                    Perihal : <b>Perubahan Data Absensi Mahasiswa</b>

                    <br/>
                    <br/>

                    <div style="background: lightyellow;color: red;border: 1px solid red; text-align: center;padding: 7px;margin-bottom: 10px;">
                        <h2 style="margin-top: 7px;margin-bottom: 0px;">Permohonan Ditolak</h2>
                        <p style="color: blue;margin-top: 3px;">
                            '.$data_arr['Reason'].'
                        </p>
                    </div>

                    <div style="text-align: center;">
                        <p>--- Detail permohonan ---</p>
                    </div>

                    <div style="font-size: 14px;">
                        <table  width="100%" cellspacing="0" cellpadding="1" border="0">
                            <tbody>
                            <tr>
                                <td style="width: 20%;">Dosen</td>
                                <td style="width: 2%;">:</td>
                                <td style="width: 40%;">'.ucwords($dataRequsted[0]['Lecturer']).'</td>
                            </tr>
                            <tr>
                                <td>Kode</td>
                                <td>:</td>
                                <td>'.$DataEmail['Code'].'</td>
                            </tr>
                            <tr>
                                <td>Mata Kuliah</td>
                                <td>:</td>
                                <td>'.$DataEmail['CourseEng'].'</td>
                            </tr>
                            <tr>
                                <td>Group Kelas</td>
                                <td>:</td>
                                <td>'.$DataEmail['Group'].'</td>
                            </tr>
                            <tr>
                                <td>Sesi (Pertemuan ke)</td>
                                <td>:</td>
                                <td>'.$DataEmail['Session'].'</td>
                            </tr>
                            <tr>
                                <td>Alasan</td>
                                <td>:</td>
                                <td>'.$DataEmail['Reason'].'</td>
                            </tr>


                            <tr>
                                <td colspan="3" style="color: #673AB7;">Mengajukan permohonan untuk perubahan daftar hadir mahasiswa</td>
                            </tr>

                            <tr>
                                <td>Sebelumnya</td>
                                <td>:</td>
                                <td>'.$DataEmail['Before'].'</td>
                            </tr>
                            <tr>
                                <td>Menjadi</td>
                                <td>:</td>
                                <td>'.$DataEmail['After'].'</td>
                            </tr>

                            </tbody>
                        </table>
                        <br/>
                        <p>
                            Demikian permohonan ini saya ajukan, mohon dapat diproses sesuai dengan ketentuan yang berlaku. Terima kasih
                        </p>
                        <br/>
                        <br/>

                        <table  width="100%" cellspacing="5" cellpadding="1" border="0">
                            <tr>
                                <td style="width: 100%;" align="center">
                                    Rejected By
                                    <br/>
                                    <h3 style="color: #009688;margin-top: 7px;">'.$dataKaprodi[0]['Name'].'
                                        <br/>
                                        <small>'.$data_arr['Updated1By'].'</small>
                                    </h3>
                                </td>

                            </tr>
                        </table>

                    </div>
                </div>';
                    $data = array(
                        'to' => $mailTo,
                        'subject' => 'Kaprodi : Modify Attendance Rejected',
                        'text' => $bodyEmail,
                        'auth' => 's3Cr3T-G4N'
                    );

                    $this->sendMailRest($data);
                }


                return print_r(1);
            }
            else if($data_arr['action']=='approvedModifyAttd'){
                $dataRequsted = $this->db->query('SELECT am.RequestBy, em.EmailPU, em.Name AS Lecturer, em.Gender, am.DataEmail, am.TokenURL FROM db_academic.attendance_modify am 
                                                            LEFT JOIN db_employees.employees em ON (em.NIP = am.RequestBy)
                                                            WHERE am.ID = "'.$data_arr['IDAM'].'" ')->result_array();


                $dataUpdate = array(
                    'Updated1By' => $data_arr['Updated1By'],
                    'Updated1At' => $data_arr['Updated1At'],
                    'Status' => '1'
                );

                $this->db->where('ID', $data_arr['IDAM']);
                $this->db->update('db_academic.attendance_modify',$dataUpdate);
                $this->db->reset_query();

                // Get Detail Modify Atttendance
                $dataStd = $this->db->get_where('db_academic.attendance_modify_details'
                    ,array('IDAM' => $data_arr['IDAM']))->result_array();

                if(count($dataStd)>0){
                    foreach ($dataStd AS $item){

                        $dataUpdate = array(
                            'M'.$item['Sesi'] => ''.$item['Meet'],
                            'D'.$item['Sesi'] => $item['Reason']
                        );
                        $this->db->where('ID', $item['IDAS']);
                        $this->db->update('db_academic.attendance_students',$dataUpdate);
                    }
                }


                $dataKaprodi = $this->db->select('Name,Photo')->get_where('db_employees.employees',
                    array('NIP' => $data_arr['Updated1By']))->result_array();

                if(count($dataKaprodi)>0){

                    $DataEmail = $this->getInputToken2($dataRequsted[0]['DataEmail']);

                    //============= Logging ==========
                    // Insert Logging
                    $url = base_url('uploads/employees/'.$dataKaprodi[0]['Photo']);
                    $img_profile = ($this->is_url_exist($url) && $dataKaprodi[0]['Photo']!='')
                        ? $url
                        : url_server_ws.'/images/icon/lecturer.png';

                    $Log_dataInsert = array(
                        'Icon' => $img_profile,
                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> Modify Attendance Approved',
                        'Description' => $DataEmail['Code'].' - '.$DataEmail['CourseEng'].' | Group : '.$DataEmail['Group'].' | Session : '.$DataEmail['Session'],
                        'URLDirectLecturer' => 'attendance/modify-attendance/'.$dataRequsted[0]['TokenURL'],
                        'CreatedBy' => $data_arr['Updated1By'],
                        'CreatedName' => $dataKaprodi[0]['Name'],
                        'CreatedAt' => $data_arr['Updated1At'],
                    );

                    $this->db->insert('db_notifikasi.logging',$Log_dataInsert);
                    $insert_id_logging = $this->db->insert_id();

                    // insert ke user
                    $Log_arr_ins = array(
                        'IDLogging' => $insert_id_logging,
                        'UserID' => $dataRequsted[0]['RequestBy']
                    );
                    $this->db->insert('db_notifikasi.logging_user',$Log_arr_ins);

                    // Send Email
                    $mailTo = (count($dataRequsted)>0)
                        ? $dataRequsted[0]['EmailPU']
                        : $this->DummyEmail;


                    $greating = ($dataRequsted[0]['Gender']=='L')  ? 'Bapak' : 'Ibu';
                    $bodyEmail = '<div>
                    Dear <span style="color: #333;">'.$greating.' '.ucwords($dataRequsted[0]['Lecturer']).'</span>,
                    <br/>
                    Perihal : <b>Perubahan Data Absensi Mahasiswa</b>

                    <br/>
                    <br/>

                    <div style="background: lightyellow;border: 1px solid green;color: green;text-align: center;padding: 7px;margin-bottom: 10px;">
                        <h2 style="margin-top: 7px;margin-bottom: 10px;">Permohonan diterima</h2>
                    </div>

                    <div style="text-align: center;">
                        <p>--- Detail permohonan ---</p>
                    </div>

                    <div style="font-size: 14px;">
                        <table  width="100%" cellspacing="0" cellpadding="1" border="0">
                            <tbody>
                            <tr>
                                <td style="width: 20%;">Dosen</td>
                                <td style="width: 2%;">:</td>
                                <td style="width: 40%;">'.ucwords($dataRequsted[0]['Lecturer']).'</td>
                            </tr>
                            <tr>
                                <td>Kode</td>
                                <td>:</td>
                                <td>'.$DataEmail['Code'].'</td>
                            </tr>
                            <tr>
                                <td>Mata Kuliah</td>
                                <td>:</td>
                                <td>'.$DataEmail['CourseEng'].'</td>
                            </tr>
                            <tr>
                                <td>Group Kelas</td>
                                <td>:</td>
                                <td>'.$DataEmail['Group'].'</td>
                            </tr>
                            <tr>
                                <td>Sesi (Pertemuan ke)</td>
                                <td>:</td>
                                <td>'.$DataEmail['Session'].'</td>
                            </tr>
                            <tr>
                                <td>Alasan</td>
                                <td>:</td>
                                <td>'.$DataEmail['Reason'].'</td>
                            </tr>


                            <tr>
                                <td colspan="3" style="color: #673AB7;">Mengajukan permohonan untuk perubahan daftar hadir mahasiswa</td>
                            </tr>

                            <tr>
                                <td>Sebelumnya</td>
                                <td>:</td>
                                <td>'.$DataEmail['Before'].'</td>
                            </tr>
                            <tr>
                                <td>Menjadi</td>
                                <td>:</td>
                                <td>'.$DataEmail['After'].'</td>
                            </tr>

                            </tbody>
                        </table>
                        <br/>
                        <p>
                            Demikian permohonan ini saya ajukan, mohon dapat diproses sesuai dengan ketentuan yang berlaku. Terima kasih
                        </p>
                        <br/>
                        <br/>

                        <table  width="100%" cellspacing="5" cellpadding="1" border="0">
                            <tr>
                                <td style="width: 100%;" align="center">
                                    Approved By
                                    <br/>
                                    <h3 style="color: #009688;margin-top: 7px;">'.$dataKaprodi[0]['Name'].'
                                        <br/>
                                        <small>'.$data_arr['Updated1By'].'</small>
                                    </h3>
                                </td>

                            </tr>
                        </table>

                    </div>
                </div>';
                    $data = array(
                        'to' => $mailTo,
                        'subject' => 'Kaprodi : Modify Attendance Approved',
                        'text' => $bodyEmail,
                        'auth' => 's3Cr3T-G4N'
                    );

                    $this->sendMailRest($data);
                }


                return print_r(1);
            }
            else if($data_arr['action']=='approveAllModifyAttd'){
                $ProdiID = $data_arr['ProdiID'];
                $SemesterID = $data_arr['SemesterID'];

                $dataAttd = $this->db->query('SELECT am.* 
                                                            FROM db_academic.attendance_modify_prodi amp 
                                                            LEFT JOIN db_academic.attendance_modify am ON (am.ID = amp.IDAM)
                                                            LEFT JOIN db_academic.attendance attd ON (attd.ID = am.ID_Attd)
                                                            WHERE amp.ProdiID = "'.$ProdiID.'" AND attd.SemesterID = "'.$SemesterID.'"
                                                            AND am.Status = "0" ')->result_array();

                if(count($dataAttd)>0){
                    foreach ($dataAttd AS $item){

                        // Update Attendance Student
                        $dataStd = $this->db->get_where('db_academic.attendance_modify_details',array('IDAM' => $item['ID']))->result_array();
                        if(count($dataStd)>0){
                            foreach ($dataStd AS $itemStd){
                                $dataUpdate = array(
                                    'M'.$itemStd['Sesi'] => ''.$itemStd['Meet'],
                                    'D'.$itemStd['Sesi'] => $itemStd['Reason']
                                );
                                $this->db->where('ID', $itemStd['IDAS']);
                                $this->db->update('db_academic.attendance_students',$dataUpdate);
                                $this->db->reset_query();
                            }
                        }

                        // Update Status
                        $this->db->where('ID', $item['ID']);
                        $this->db->update('db_academic.attendance_modify',array(
                            'Status' => '1',
                            'Updated1By' => $data_arr['Updated1By'],
                            'Updated1At' => $data_arr['Updated1At']
                        ));
                        $this->db->reset_query();

                        $dataKaprodi = $this->db->select('Name,Photo')->get_where('db_employees.employees',
                            array('NIP' => $data_arr['Updated1By']))->result_array();

                        if(count($dataKaprodi)>0){
                            $DataEmail = $this->getInputToken2($item['DataEmail']);

                            //============= Logging ==========
                            // Insert Logging
                            $url = base_url('uploads/employees/'.$dataKaprodi[0]['Photo']);
                            $img_profile = ($this->is_url_exist($url) && $dataKaprodi[0]['Photo']!='')
                                ? $url
                                : url_server_ws.'/images/icon/lecturer.png';

                            $Log_dataInsert = array(
                                'Icon' => $img_profile,
                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> Modify Attendance Approved',
                                'Description' => $DataEmail['Code'].' - '.$DataEmail['CourseEng'].' | Group : '.$DataEmail['Group'].' | Session : '.$DataEmail['Session'],
                                'URLDirectLecturer' => 'attendance/modify-attendance/'.$item['TokenURL'],
                                'CreatedBy' => $data_arr['Updated1By'],
                                'CreatedName' => $dataKaprodi[0]['Name'],
                                'CreatedAt' => $data_arr['Updated1At']
                            );
                            $this->db->insert('db_notifikasi.logging',$Log_dataInsert);
                            $insert_id_logging = $this->db->insert_id();

                            // insert ke user
                            $Log_arr_ins = array(
                                'IDLogging' => $insert_id_logging,
                                'UserID' => $item['RequestBy']
                            );
                            $this->db->insert('db_notifikasi.logging_user',$Log_arr_ins);

                        }

                    }
                }

                return print_r(1);

            }

        }
    }


    public function getMonitoringAttendance(){

        $requestData= $_REQUEST;

        $data_arr = $this->getInputToken();

        $ProgramsCampusID = $data_arr['ProgramsCampusID'];
        $SemesterID = $data_arr['SemesterID'];
        $ProdiID = $data_arr['ProdiID'];
        $DayID = $data_arr['DayID'];

        $dataSearch = '';
        if( !empty($requestData['search']['value']) ) {
            $search = $requestData['search']['value'];
            $dataSearch = 'AND ( s.ClassGroup LIKE "%'.$search.'%" OR mk.MKCode LIKE "%'.$search.'%"
                           OR mk.NameEng LIKE "%'.$search.'%" OR d.NameEng LIKE "%'.$search.'%"
                           )';
        }

        $w_Prodi = ($ProdiID!='' && $ProdiID!=null) ? ' AND sdc.ProdiID = "'.$ProdiID.'" ' : '';
        $w_Day = ($DayID!='' && $DayID!=null) ? ' AND sd.DayID = "'.$DayID.'" ' : '';



        $queryDefault = 'SELECT s.ID AS ScheduleID, attd.ID AS ID_Attd,sd.ID AS SDID,s.Coordinator , s.ClassGroup, s.TeamTeaching,   
                                        mk.MKCode, mk.NameEng AS CourseEng,
                                        mk.Name AS Course, d.NameEng AS DayEng, cl.Room,
                                        cd.TotalSKS AS Credit, sd.StartSessions, sd.EndSessions, em.Name AS Lecturer
                                        FROM db_academic.schedule_details sd 
                                        LEFT JOIN db_academic.schedule s ON (sd.ScheduleID = s.ID)  
                                        LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)            
                                        LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)                   
                                        LEFT JOIN db_academic.schedule_details_course sdc ON (s.ID = sdc.ScheduleID)
                                        LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                        LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                        LEFT JOIN db_academic.attendance attd ON (attd.SemesterID = s.SemesterID AND attd.SDID = sd.ID)
                                        WHERE ( s.ProgramsCampusID = "'.$ProgramsCampusID.'" AND 
                                        s.SemesterID = "'.$SemesterID.'" '.$w_Prodi.' '.$w_Day.' ) '.$dataSearch.'
                                        GROUP BY sd.ID
                                        ORDER BY d.ID, sd.StartSessions, s.ID ASC ';


        $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';
        $query = $this->db->query($sql)->result_array();
        $queryDefaultRow = $this->db->query($queryDefault)->result_array();

        $no = $requestData['start'];
        $data = array();

        $tempG = '';
        for($i=0;$i<count($query);$i++) {
            $nestedData = array();
            $row = $query[$i];


            if($row['ClassGroup']!=$tempG){
                $no++;
            }
            $tempG = $row['ClassGroup'];


            // ==== Attendance Lecturer ====

            $arrLec = [$row['Coordinator']];
            $arrLecName = [$row['Lecturer']];
            if($row['TeamTeaching']==1 || $row['TeamTeaching']=='1'){
                $dataLec = $this->db->query('SELECT em.NIP,em.Name FROM db_academic.schedule_team_teaching stt 
                                                        LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                        WHERE stt.ScheduleID = "'.$row['ScheduleID'].'"')->result_array();
                if(count($dataLec)>0){
                    foreach ($dataLec AS $item){
                        array_push($arrLec,$item['NIP']);
                        array_push($arrLecName,$item['Name']);
                    }
                }
            }

            $showLec = '';
            $dataLec = [];
            // Load Data Teacher
            if(count($arrLec)>0){
                for ($t=0;$t<count($arrLec);$t++){
                    // Get Attendance

                    $dataAttd = $this->db->query('SELECT COUNT(*) AS P FROM db_academic.attendance_lecturers attd_l
                                                              LEFT JOIN db_academic.attendance attd ON (attd.ID = attd_l.ID_Attd)
                                                              WHERE attd.ID = "'.$row['ID_Attd'].'"
                                                              AND attd_l.NIP = "'.$arrLec[$t].'" ')->result_array();

                    $br = ($t!=0) ? '<br/>' : '';
                    $showLec = $showLec.''.$br.''.$arrLecName[$t].' - <span style="color: orangered;">'.$dataAttd[0]['P'].'</span>';

                    $arr = array(
                        'NIP' => $arrLec[$t],
                        'Name' => $arrLecName[$t]
                    );
                    array_push($dataLec,$arr);
                }
            }

            // ==== Attendance Student ====
            $dataStd = $this->db->query('SELECT * FROM attendance_students attd_s 
                                                    LEFT JOIN db_academic.attendance attd ON (attd.ID = attd_s.ID_Attd)
                                                    WHERE 
                                                    attd.ID = "'.$row['ID_Attd'].'"
                                                    ')->result_array();

            $arrP = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
            $arrA = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

            if(count($dataStd)>0){
                foreach ($dataStd as $items){
                    $noArr = 0;
                    for($l=1;$l<=14;$l++){
                        if($items['M'.$l]==1 || $items['M'.$l]=='1'){
                            $arrP[$noArr] = $arrP[$noArr]+1;
                        }
                        else if($items['M'.$l]==2 || $items['M'.$l]=='2'){
                            $arrA[$noArr] = $arrA[$noArr]+1;
                        }

                        $noArr ++;
                    }

                    // UTS
                    if($items['UTS']==1 || $items['UTS']=='1'){
                        $arrP[14] = $arrP[14] + 1;
                    } else if($items['UTS']==1 || $items['UTS']=='1'){
                        $arrA[14] = $arrA[14] + 1;
                    }

                    // UAS
                    if($items['UAS']==1 || $items['UAS']=='1'){
                        $arrP[15] = $arrP[15] + 1;
                    } else if($items['UAS']==1 || $items['UAS']=='1'){
                        $arrA[15] = $arrA[15] + 1;
                    }

                }
            }

            $nestedData[] = '<div  style="text-align:center;">'.$no.'</div>';
            $nestedData[] = '<div  style="text-align:left;"><a href="'.base_url('academic/attendance/details-attendace/'.$row['ScheduleID']).'"><b>'.$row['MKCode'].' - '.$row['CourseEng'].'</b></a><br/>'.$row['Course'].'</div>';
            $nestedData[] = '<div  style="text-align:center;">'.$row['ClassGroup'].'</div>';
            $nestedData[] = '<div  style="text-align:center;">'.$row['Credit'].'</div>';
            $nestedData[] = '<div  style="text-align:center;">'.count($dataStd).'</div>';
            $nestedData[] = '<div  style="text-align:left;">'.$showLec.'<textarea class="hide" id="dateLec'.$row['ID_Attd'].'">'.json_encode($dataLec).'</textarea></div>';
            $nestedData[] = '<div  style="text-align:right;"><b>'.$row['DayEng'].'</b><br/>'.
                substr($row['StartSessions'],0,5).' - '.substr($row['EndSessions'],0,5).' | '.$row['Room'].'</div>';

            $meetTemp = 1;
            for($l=0;$l<count($arrP);$l++){
                $totalStd = $arrP[$l]+$arrA[$l];
                if($totalStd==0){



                    $sw = '<div  style="text-align:center;">-</div>';

                    if($meetTemp<=14){
                        // Cek attd lecturer
                        $dataMeetLec = $this->db->query('SELECT * FROM db_academic.attendance_lecturers 
                                                                WHERE ID_Attd = "'.$row['ID_Attd'].'" 
                                                                AND Meet = "'.$meetTemp.'" ')->result_array();

                        if(count($dataMeetLec)<=0){
                            // Cek apakah sesi sudah 0 / null
                            $dataDSes = $this->db->select('Meet'.$meetTemp)
                                ->get_where('db_academic.attendance',array('ID' => $row['ID_Attd']))->result_array();
                            if($dataDSes[0]['Meet'.$meetTemp]=='1' || $dataDSes[0]['Meet'.$meetTemp]==1){
                                $sw = '<div  style="text-align:center;"><i class="fa fa-exclamation-circle fa-2x" style="color: #ff9800;"></i></div>';
                            }
                        }

                    }



                    $nestedData[] = $sw;

                } else {
                    $nestedData[] = '<div  style="text-align:center;"><span class="label label-success labelAttd">'.$arrP[$l].'</span><br/><span class="label label-danger labelAttd">'.$arrA[$l].'</span></div>';
                }
                $meetTemp+=1;
            }


            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval(count($queryDefaultRow)),
            "recordsFiltered" => intval( count($queryDefaultRow) ),
            "data"            => $data
        );

        echo json_encode($json_data);


    }

    public function crudAttendance2(){
        $data_arr = $this->getInputToken();
        if (count($data_arr) > 0) {
            if($data_arr['action']=='loadScheduleDetails'){
                $ScheduleID = $data_arr['ScheduleID'];

                // Get Course
                $dataCourse = $this->db->query('SELECT mk.NameEng AS CourseEng, mk.MKCode, s.ClassGroup, em.NIP, em.Name, s.TeamTeaching FROM db_academic.schedule s 
                                                          LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                          LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                          WHERE s.ID = "'.$ScheduleID.'" GROUP BY s.ID LIMIT 1')->result_array();

                // Get Lecturer
                $dataLecturer = [array(
                    'NIP' => $dataCourse[0]['NIP'],
                    'Name' => $dataCourse[0]['Name']
                )];

                if($dataCourse[0]['TeamTeaching']==1 || $dataCourse[0]['TeamTeaching']=='1'){
                    $dataT = $this->db->query('SELECT em.NIP,em.Name FROM db_academic.schedule_team_teaching stt 
                                                              LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                              WHERE stt.ScheduleID = "'.$ScheduleID.'" ORDER BY em.NIP ')->result_array();

                    if(count($dataT)>0){
                        foreach ($dataT as $item){
                            $ar = array(
                                'NIP' => $item['NIP'],
                                'Name' => $item['Name']
                            );
                            array_push($dataLecturer,$ar);
                        }
                    }
                }


                $dataSchedule = $this->db->query('SELECT attd.ID AS ID_Attd ,sd.ID AS SDID, sd.StartSessions, sd.EndSessions, cl.Room, d.NameEng AS DayEng FROM db_academic.schedule_details sd 
                                                    LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                                    LEFT JOIN db_academic.attendance attd ON (attd.ScheduleID = sd.ScheduleID AND attd.SDID = sd.ID)
                                                    WHERE sd.ScheduleID = "'.$ScheduleID.'" 
                                                    ORDER BY sd.DayID, sd.StartSessions ASC ')->result_array();


                $result = array(
                    'Course' => $dataCourse,
                    'Lecturer' => $dataLecturer,
                    'Schedule' => $dataSchedule
                );

                return print_r(json_encode($result));
            }
            else if($data_arr['action']=='readDetailAttendance'){
                $ScheduleID = $data_arr['ScheduleID'];
                $SDID = $data_arr['SDID'];

                $dataAttd = $this->db->query('SELECT * FROM db_academic.attendance attd 
                                        WHERE attd.ScheduleID = "'.$ScheduleID.'" AND attd.SDID = "'.$SDID.'" LIMIT 1')->result_array();

                $result = [];
                if(count($dataAttd)>0){

                    $ID_Attd = $dataAttd[0]['ID'];

                    for ($i=1;$i<=14;$i++){
                        $bap = $this->db->query('SELECT b.*, auts.Name AS Student, em.Name AS Lecturer
                                                  FROM db_academic.attendance_bap b
                                                  LEFT JOIN db_academic.auth_students auts ON (auts.NPM = b.StudentSignBy)
                                                  LEFT JOIN db_employees.employees em ON (em.NIP = b.NIP)
                                                  WHERE b.ID_Attd = "'.$ID_Attd.'"
                                                   AND b.Sesi = "'.$i.'" ')->result_array();

                        // Get Present
                        $p = ' AND atts.M'.$i.' = "1" ';
                        $present = $this->db->query('SELECT * FROM db_academic.attendance_students atts 
                                                      WHERE atts.ID_Attd = "'.$ID_Attd.'" '.$p)->result_array();

                        // Get Absent
                        $a = ' AND atts.M'.$i.' = "2" ';
                        $absent = $this->db->query('SELECT * FROM db_academic.attendance_students atts 
                                                      WHERE atts.ID_Attd = "'.$ID_Attd.'" '.$a)->result_array();

                        // Get Time Attendance
                        $dataLect = $this->db->query('SELECT al.*, em.Name AS Lecturer FROM db_academic.attendance_lecturers al
                                                      LEFT JOIN db_employees.employees em ON (em.NIP = al.NIP)
                                                      WHERE al.ID_Attd = "'.$ID_Attd.'" AND al.Meet = "'.$i.'" ')->result_array();

                        $countPresent = (count($present)==0 && count($absent)==0) ? '-' : count($present);
                        $countAbsent = (count($present)==0 && count($absent)==0) ? '-' : count($absent);


                        // Cek apakah ada kelas pengganti
                        $dataExc = $this->db->query('SELECT exc.*,cl.Room, cl.Seat, cl.SeatForExam FROM db_academic.schedule_exchange exc 
                                                                LEFT JOIN db_academic.classroom cl ON (cl.ID = exc.ClassroomID)
                                                                WHERE exc.ID_Attd = "'.$ID_Attd.'" AND Meeting = "'.$i.'" LIMIT 1')->result_array();


                        $arrRes = array(
                            'StatusSesi' => $dataAttd[0]['Meet'.$i],
                            'Present' => $countPresent,
                            'Absent' => $countAbsent,
                            'Lecturer' => $dataLect,
                            'BAP' => $bap,
                            'Exchange' => $dataExc
                        );
                        array_push($result,$arrRes);
                    }

                }

                return print_r(json_encode($result));

            }
            else if($data_arr['action']=='updateAttendanceLecturer'){

                $ID_Attd = $data_arr['ID_Attd'];
                $Meet = $data_arr['Meet'];
                $NIP = $data_arr['NIP'];

                // Cek apakah data sudah ada
                // 1. Jika ada maka data akan terupdate
                // 2. Jika tidak ada maka insert

                $dataAttd = $this->db->select('ID')->limit(1)->get_where('db_academic.attendance_lecturers',array(
                    'ID_Attd' => $ID_Attd,
                    'Meet' => $Meet,
                    'NIP' => $NIP
                ))->result_array();

                $dataUpdate = array(
                    'Meet' => $Meet,
                    'Date' => $data_arr['Date'],
                    'In' => $data_arr['In'],
                    'Out' => $data_arr['Out']
                );

                if(count($dataAttd)>0){

                    // Update
                    $this->db->where('ID', $dataAttd[0]['ID']);
                    $this->db->update('db_academic.attendance_lecturers',$dataUpdate);


                } else {
                    $dataUpdate['ID_Attd'] = $ID_Attd;
                    $dataUpdate['NIP'] = $NIP;
                    // Insert
                    $this->db->insert('db_academic.attendance_lecturers', $dataUpdate);
                }


                // Update Attendance
                $dataUpAt = array(
                    'Meet'.$Meet => '1',
                    'Date'.$Meet => $data_arr['Date']
                );
                $this->db->where('ID', $ID_Attd);
                $this->db->update('db_academic.attendance',$dataUpAt);

                return print_r(1);

            }
            else if($data_arr['action']=='deleteAttendanceLecturer'){
                $ID = $data_arr['ID'];
                $this->db->where('ID', $ID);
                $this->db->delete('db_academic.attendance_lecturers');
                return print_r(1);
            }
            else if($data_arr['action']=='readAttdStudent'){

                $ID_Attd = $data_arr['ID_Attd'];
                $Meet = $data_arr['Meet'];

                //cek apakah status aatd sudah 1 atau blm
                $dataAttd = $this->db->query('SELECT Meet'.$Meet.' AS StatusMeet FROM db_academic.attendance attd 
                                                                    WHERE attd.ID = "'.$ID_Attd.'" LIMIT 1')->result_array();

                // Get Attendance Setting
                $dataSeting = $this->db->query('SELECT attdSet.* FROM db_academic.attendance_setting attdSet 
                                                            LEFT JOIN db_academic.semester s ON (s.ID = attdSet.SemesterID)
                                                            WHERE s.Status = "1" ')->result_array();

                $dataStd = $this->db->query('SELECT attds.ID, ats.Name, ats.NPM, attds.D'.$Meet.' AS D, attds.M'.$Meet.' AS M FROM db_academic.attendance_students attds 
                                                        LEFT JOIN db_academic.auth_students ats ON (ats.NPM = attds.NPM)
                                                        WHERE attds.ID_Attd = "'.$ID_Attd.'" ORDER BY ats.NPM ASC')->result_array();

                $result = array(
                    'Attendance' => $dataAttd,
                    'Setting' => $dataSeting,
                    'Students' => $dataStd
                );

                return print_r(json_encode($result));

            }
            else if($data_arr['action']=='UpdateStudentAttd'){
                $Meet = $data_arr['Meet'];

                $attdStudent = (array) $data_arr['attdStudent'];

                if(count($attdStudent)>0){
                    foreach ($attdStudent AS $item){
                        $Update = array(
                            'M'.$Meet => $item->M,
                            'D'.$Meet => $item->D
                        );
                        $this->db->where('ID', $item->ID);
                        $this->db->update('db_academic.attendance_students',$Update);
                    }
                }

                return print_r(1);


            }
            else if($data_arr['action']=='loadBAP'){

                $Sesi = $data_arr['Sesi'];
                $ID_Attd = $data_arr['ID_Attd'];

                // Cek apakah ada BAP
                $dataBAP = $this->db->limit(1)->get_where('db_academic.attendance_bap'
                    ,array(
                        'ID_Attd' => $ID_Attd,
                        'Sesi' => $Sesi
                    ))->result_array();

                return print_r(json_encode($dataBAP));
            }
            else if($data_arr['action']=='updateExhange'){

                $EXID = $data_arr['EXID'];
                $dataUpdate = (array) $data_arr['dataUpdate'];


                // Get Semester Active
                $SemesterActive = $this->m_api->_getSemesterActive();

                // Cek bentrok
                $dataFilter  = array(
                    'SemesterID' => $SemesterActive['ID'],
                    'IsSemesterAntara' => '0',
                    'ClassroomID' =>  $dataUpdate['ClassroomID'],
                    'DayID' =>  $dataUpdate['DayID'],
                    'StartSessions' =>  $dataUpdate['StartSessions'],
                    'EndSessions' => $dataUpdate['EndSessions']
                );
                $dataConflict1 = $this->m_api->__checkSchedule($dataFilter);

                $dataGetRoom = $this->db->select('Room')->get_where('db_academic.classroom',array('ID' => $dataUpdate['ClassroomID']))->result_array();

                // Cek Bentrok Sesama Exchange
                $dataConflict2 = $this->db->query('SELECT exch.ID, exch.Date, exch.StartSessions, exch.EndSessions ,mk.NameEng AS CourseEng, mk.MKCode, s.ClassGroup, cl.Room  
                                                              FROM db_academic.schedule_exchange exch
                                                              LEFT JOIN db_academic.attendance attd ON (attd.ID = exch.ID_Attd)
                                                              LEFT JOIN db_academic.schedule s ON (s.ID = attd.ScheduleID)
                                                              LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = attd.ScheduleID)
                                                              LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                              LEFT JOIN db_academic.classroom cl ON (cl.ID = exch.ClassroomID)
                                                              WHERE exch.ID != "'.$EXID.'" AND exch.Date = "'.$dataUpdate['Date'].'"
                                                              AND exch.ClassroomID = "'.$dataUpdate['ClassroomID'].'" AND exch.Status = "2"
                                                              AND (("'.$dataUpdate['StartSessions'].'" >= exch.StartSessions  AND "'.$dataUpdate['StartSessions'].'" <= exch.EndSessions) OR
                                                                      ("'.$dataUpdate['EndSessions'].'" >= exch.StartSessions AND "'.$dataUpdate['EndSessions'].'" <= exch.EndSessions) OR
                                                                      ("'.$dataUpdate['StartSessions'].'" <= exch.StartSessions AND "'.$dataUpdate['EndSessions'].'" >= exch.EndSessions)
                                                                      ) ')->result_array();


                $DateStart = date("Y-m-d H:i:s", strtotime($dataUpdate['Date'].$dataUpdate['StartSessions']));
                $DateEnd = date("Y-m-d H:i:s", strtotime($dataUpdate['Date'].$dataUpdate['EndSessions']));
                $Room = $dataGetRoom[0]['Room'];


                $sql2 = 'select count(*) as Total from db_reservation.t_booking as a
                                 join db_employees.employees as b on a.CreatedBy = b.NIP
                                 where a.Status in(0,1) and (
                                    (a.`Start` >= "'.$DateStart.'" and a.`Start` < "'.$DateEnd.'" ) 
                                    or (a.`End` > "'.$DateStart.'" and a.`End` <= "'.$DateEnd.'" )
                                    or (
                                            a.`Start` <= "'.$DateStart.'" and a.`End` >= "'.$DateEnd.'"
                                        )
                                ) and a.Room = "'.$Room.'"';


                $query3=$this->db->query($sql2)->result_array();

                if(count($dataConflict1)>0 ||
                    count($dataConflict2)>0){
                    $result = array(
                        'Status' => 0,
                        'Schedule' => $dataConflict1,
                        'Exchange' => $dataConflict2,
                        'Vanue' => $query3[0]['Total']
                    );
                } else {
                    $this->db->where('ID', $EXID);
                    $this->db->update('db_academic.schedule_exchange',$dataUpdate);
                    $result = array(
                        'Status' => 1,
                        'Vanue' => $query3[0]['Total']
                    );
                }

                return print_r(json_encode($result));


            }
            else if($data_arr['action']=='delteExhange'){

                $Log_dataInsert = $data_arr['Logging'];
                $this->db->insert('db_notifikasi.logging',$Log_dataInsert);
                $insert_id_logging = $this->db->insert_id();

                // Insert logging
                $Log_arr_ins = array(
                    'IDLogging' => $insert_id_logging,
                    'UserID' => $data_arr['UserID']
                );
                // Send Notif To Kaprodi
                $this->db->insert('db_notifikasi.logging_user',$Log_arr_ins);

                // Cek apakah kaprodi sudah Approve atau belum
                if($data_arr['Updated1By']!='' && $data_arr['Updated1By']!=null){
                    // Insert logging
                    $Log_arr_ins = array(
                        'IDLogging' => $insert_id_logging,
                        'UserID' => $data_arr['Updated1By']
                    );
                    // Send Notif To Kaprodi
                    $this->db->insert('db_notifikasi.logging_user',$Log_arr_ins);
                }


                // ==== Action remove =====


                $EXID = $data_arr['EXID'];

                $this->db->where('ID', $EXID);
                $this->db->delete('db_academic.schedule_exchange');
                $this->db->reset_query();

                $this->db->where('EXID', $EXID);
                $this->db->delete('db_academic.schedule_exchange_prodi');

                return print_r(1);


            }
            else if($data_arr['action']=='cleanAttendance'){
                $Sesi = $data_arr['Sesi'];
                $ID_Attd = $data_arr['ID_Attd'];
                $User = $data_arr['User'];

                // Cek user yg dihapus


                if($User==0 || $User=='0'){

                    // Nullkan Attd Student
                    $this->db->where('ID_Attd',$ID_Attd);
                    $this->db->update('db_academic.attendance_students', array(
                        'M'.$Sesi => null,
                        'D'.$Sesi => ''
                    ));
                    $this->db->reset_query();

                    // Hapus Attd Lecturer
                    $this->db->where(array(
                        'ID_Attd' => $ID_Attd,
                        'Meet' => $Sesi
                    ));
                    $this->db->delete('db_academic.attendance_lecturers');
                    $this->db->reset_query();

                    // Null kan data Attendance
                    $this->db->where('ID',$ID_Attd);
                    $this->db->update('db_academic.attendance', array(
                        'Meet'.$Sesi => null,
                        'BAP'.$Sesi => null,
                        'Venue'.$Sesi => null,
                        'Equipments'.$Sesi => null
                    ));

                }
                else if($User==1 || $User=='1'){
                    // Nullkan Attd Student
                    $this->db->where('ID_Attd',$ID_Attd);
                    $this->db->update('db_academic.attendance_students', array(
                        'M'.$Sesi => null,
                        'D'.$Sesi => null
                    ));
                    $this->db->reset_query();
                }
                else if($User==2 || $User=='2'){
                    // Hapus Attd Lecturer
                    $this->db->where(array(
                        'ID_Attd' => $ID_Attd,
                        'Meet' => $Sesi
                    ));
                    $this->db->delete('db_academic.attendance_lecturers');
                    $this->db->reset_query();
                }


                return print_r(1);

            }
            else if($data_arr['action']=='readAttdSetting'){
                $SemesterID = $data_arr['SemesterID'];
                $data = $this->db
                    ->get_where('db_academic.attendance_setting'
                        ,array('SemesterID' => $SemesterID))->result_array();
                return print_r(json_encode($data));
            }

            // === Exam ===
            else if($data_arr['action']=='readAttendanceExam'){
                $ExamID = $data_arr['ID'];

//                $data = $this->db->query('SELECT ex.*, ats.Name FROM db_academic.exam_details ex
//                                                    LEFT JOIN db_academic.auth_students ats
//                                                    ON (ats.NPM = ex.NPM)
//                                                    WHERE ex.ExamID = "'.$ExamID.'"
//                                                    ORDER BY ex.NPM ASC')->result_array();

                $result = [];
                $data = $this->m_rest->getListStudentExam($ExamID);

                if($data['Status']==1 || $data['Status']=='1'){
                    $result = $data['DetailStudents'];
                }

                return print_r(json_encode($result));

            }

            else if($data_arr['action']=='insertAttdExam'){

                $arrAttd = $data_arr['arrAttd'];

                if(count($arrAttd)>0){
                    for($i=0;$i<count($arrAttd);$i++){
                        $d = (array) $arrAttd[$i];
                        $this->db->set('Status', $d['Status']);
                        $this->db->where('ID', $d['ExamDetailsID']);
                        $this->db->update('db_academic.exam_details');
                    }
                }



                return print_r(1);
            }

            // === Penutup Exam ===

        }
    }

    public function crudAnnouncement(){
        $data_arr = $this->getInputToken();
        if (count($data_arr) > 0) {

            if($data_arr['action']=='readStudent2Annc'){

                $selct = 'NPM,Name';

                if($data_arr['Year']!=0 && $data_arr['ProdiID']!=0){
                    $q = 'SELECT '.$selct.' FROM db_academic.auth_students WHERE Year = "'.$data_arr['Year'].'" 
                    AND ProdiID = "'.$data_arr['ProdiID'].'"  ORDER BY NPM ASC';
                }
                else if($data_arr['Year']==0 && $data_arr['ProdiID']!=0){
                    $q = 'SELECT '.$selct.' FROM db_academic.auth_students WHERE ProdiID = "'.$data_arr['ProdiID'].'"  ORDER BY NPM ASC';
                }
                else if($data_arr['Year']!=0 && $data_arr['ProdiID']==0){
                    $q = 'SELECT '.$selct.' FROM db_academic.auth_students WHERE Year = "'.$data_arr['Year'].'" ORDER BY NPM ASC';
                }
                else {
                    $q = 'SELECT '.$selct.' FROM db_academic.auth_students ORDER BY NPM ASC';
                }

                $dataStudent = $this->db->query($q)->result_array();

                return print_r(json_encode($dataStudent));

            }
            else if($data_arr['action']=='getStudentServerSide'){
                $Key = $data_arr['Key'];

                $data = $this->db->query('SELECT NPM, Name FROM db_academic.auth_students 
                                                            WHERE NPM LIKE "%'.$Key.'%" OR 
                                                            Name LIKE "%'.$Key.'%" ORDER BY NPM ASC LIMIT 10 ')->result_array();

                return print_r(json_encode($data));

            }
            else if($data_arr['action']=='getLecturerServerSide'){
                $Key = $data_arr['Key'];
                $data = $this->db->query('SELECT em.NIP,em.Name FROM db_employees.employees em 
                                                    WHERE em.NIP LIKE "%'.$Key.'%" OR em.Name LIKE "%'.$Key.'%"
                                                     ORDER BY em.NIP ASC LIMIT 10 ')->result_array();
                return print_r(json_encode($data));
            }
            else if($data_arr['action']=='createAnnouncement'){

                $max_execution_time = 360*5;
                ini_set('memory_limit', '-1');
                ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes

                $dataAnnc = (array) $data_arr['dataAnnc'];

                $this->db->insert('db_notifikasi.announcement',$dataAnnc);
                $insert_id = $this->db->insert_id();

                // Student
                $anncStd = (array) $data_arr['anncStd'];
                if(count($anncStd)>0){
                    for($i=0;$i<count($anncStd);$i++){
                        $d = (array) $anncStd[$i];
                        $insr = array(
                            'IDAnnc' => $insert_id,
                            'NPM' => $d['NPM']
                        );
                        $this->db->insert('db_notifikasi.announcement_student',$insr);
                    }
                }

                // Employees
                $anncLec = (array) $data_arr['anncLec'];
                if(count($anncLec)>0){
                    for($i=0;$i<count($anncLec);$i++){
                        $d = (array) $anncLec[$i];
                        $insr = array(
                            'IDAnnc' => $insert_id,
                            'NIP' => $d['NIP']
                        );
                        $this->db->insert('db_notifikasi.announcement_employees',$insr);
                    }
                }

                return print_r($insert_id);


            }
            else if($data_arr['action']=='showAnnc_UnreadOnly'){
                $User = $data_arr['User'];
                $UserID = $data_arr['UserID'];

                $db = ($User=='Std') ? 'announcement_student' : 'announcement_employees';
                $w = ($User=='Std') ? 'NPM' : 'NIP';

                $data = $this->db->query('SELECT * FROM db_notifikasi.'.$db.' u 
                                                   LEFT JOIN db_notifikasi.announcement anc ON (anc.ID = u.IDAnnc) 
                                                   WHERE u.Read = "0" AND u.'.$w.' = "'.$UserID.'" ORDER BY anc.ID DESC ')->result_array();

                return print_r(json_encode($data));

            }
            else if($data_arr['action']=='showAnnouncementActive'){
                $dateNow = $this->m_rest->getDateNow();

                $User = $data_arr['User'];
                $UserID = $data_arr['UserID'];

                $db = ($User=='Std') ? 'announcement_student' : 'announcement_employees';
                $w = ($User=='Std') ? 'NPM' : 'NIP';

                $data = $this->db->query('SELECT * FROM db_notifikasi.'.$db.' annu 
                                                  LEFT JOIN db_notifikasi.announcement ann 
                                                  ON (annu.IDAnnc = ann.ID)
                                                  WHERE annu.'.$w.' = "'.$UserID.'" AND 
                                                  ann.Start <= "'.$dateNow.'" AND 
                                                  ann.End >= "'.$dateNow.'" ORDER BY ann.ID DESC ')->result_array();

                return print_r(json_encode($data));

            }
            else if($data_arr['action']=='showAnnouncementSaved'){

                $User = $data_arr['User'];
                $UserID = $data_arr['UserID'];

                $db = ($User=='Std') ? 'db_notifikasi.announcement_student' : 'db_notifikasi.announcement_employees';
                $w = ($User=='Std') ? 'NPM' : 'NIP';

                $data = $this->db->query('SELECT * FROM '.$db.' annu 
                                                  LEFT JOIN db_notifikasi.announcement ann 
                                                  ON (annu.IDAnnc = ann.ID)
                                                  WHERE annu.'.$w.' = "'.$UserID.'" AND 
                                                  annu.Read = "2"  ORDER BY ann.ID DESC
                                                  ')->result_array();

                return print_r(json_encode($data));

            }
            else if($data_arr['action']=='showCountAnnouncementSaved'){

                $User = $data_arr['User'];
                $UserID = $data_arr['UserID'];

                $db = ($User=='Std') ? 'db_notifikasi.announcement_student' : 'db_notifikasi.announcement_employees';
                $w = ($User=='Std') ? 'NPM' : 'NIP';

                $data = $this->db->query('SELECT COUNT(*) AS Total FROM '.$db.' annu 
                                                  LEFT JOIN db_notifikasi.announcement ann 
                                                  ON (annu.IDAnnc = ann.ID)
                                                  WHERE annu.'.$w.' = "'.$UserID.'" AND 
                                                  annu.Read = "2"
                                                  ')->result_array();

                return print_r(json_encode($data));

            }
            else if($data_arr['action']=='readDetailAnnc'){

                $IDAnnc = $data_arr['IDAnnc'];
                $User = $data_arr['User'];
                $UserID = $data_arr['UserID'];

                $db = ($User=='Std') ? 'db_notifikasi.announcement_student' : 'db_notifikasi.announcement_employees';
                $w = ($User=='Std') ? 'NPM' : 'NIP';

                $dataUsr = $this->db->get_where($db,array('IDAnnc' => $IDAnnc, $w => $UserID))->result_array();

                if($dataUsr[0]['Read']==0 || $dataUsr[0]['Read']=='0'){
                    // Update jadi read
                    $this->db->set('Read', '1');
                    $this->db->where(array('IDAnnc' => $IDAnnc, $w => $UserID));
                    $this->db->update($db);
                }



                $data = $this->db->get_where('db_notifikasi.announcement',array(
                    'ID' => $IDAnnc
                ))->result_array();

                $data[0]['FileURL'] = ($data[0]['File']!='' && $data[0]['File']!=null) ? base_url('uploads/announcement/'.$data[0]['File']) : '';


                $result = array(
                    'Annc' => $data,
                    'Status' =>$dataUsr
                );

                return print_r(json_encode($result));

            }
            else if($data_arr['action']=='saveDetailAnnc'){
                $IDAnnc = $data_arr['IDAnnc'];
                $User = $data_arr['User'];
                $UserID = $data_arr['UserID'];
                $Status = $data_arr['Status'];

                $db = ($User=='Std') ? 'db_notifikasi.announcement_student' : 'db_notifikasi.announcement_employees';
                $w = ($User=='Std') ? 'NPM' : 'NIP';

                // Update jadi read
                $this->db->set('Read', $Status);
                $this->db->where(array('IDAnnc' => $IDAnnc, $w => $UserID));
                $this->db->update($db);

                return print_r(1);

            }
            else if($data_arr['action']=='loadAnnouncement'){

                $data = $this->db->get_where('db_notifikasi.announcement',array(
                    'ID' => $data_arr['ID']
                ))->result_array();

                return print_r(json_encode($data));
            }
            else if($data_arr['action']=='updateAnnouncement'){
                $ID = $data_arr['ID'];
                $dataUpdate = (array) $data_arr['dataUpdate'];

                $this->db->where('ID', $ID);
                $this->db->update('db_notifikasi.announcement',$dataUpdate);

                return print_r($ID);

            }
            else if($data_arr['action']=='deleteAnnouncement'){
                $ID = $data_arr['ID'];

                $lf = $data_arr['LastFile'];

                // Cek apakah ada file atau tidak
                if(isset($lf) && $lf!='' && $lf!=null) {
                    // Delete last data dulu
                    if(is_file('./uploads/announcement/'.$lf)){
                        unlink('./uploads/announcement/'.$lf);
                    }
                }

                $tables = array('db_notifikasi.announcement_employees', 'db_notifikasi.announcement_student');
                $this->db->where('IDAnnc', $ID);
                $this->db->delete($tables);

                $this->db->reset_query();
                $this->db->where('ID', $ID);
                $this->db->delete('db_notifikasi.announcement');

                return print_r(1);
            }

        }
    }

    public function getAnnouncement(){
        $requestData= $_REQUEST;

        $dataSearch = '';
        if( !empty($requestData['search']['value']) ) {

            $search = $requestData['search']['value'];

            $dataSearch = 'WHERE annc.Title LIKE "%'.$search.'%" OR annc.Message LIKE "%'.$search.'%" ';

        }

        $queryDefault = 'SELECT annc.*, em.Name FROM db_notifikasi.announcement annc
                                        LEFT JOIN db_employees.employees em ON (em.NIP = annc.CreatedBy)
                                        '.$dataSearch.'
                                        ORDER BY annc.ID DESC ';

        $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';



        $query = $this->db->query($sql)->result_array();
        $queryDefaultRow = $this->db->query($queryDefault)->result_array();

        $no = $requestData['start'] + 1;
        $data = array();

        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];

            $file = ($row['File']!=null && $row['File']!='')
                ? '<a class="btn btn-sm btn-default" target="_blank" href="'.base_url('uploads/announcement/'.$row['File']).'"><i class="fa fa-download"></i></a>' : '';

            $dataStd = $this->db->query('SELECT ann.*, auts.Name FROM db_notifikasi.announcement_student ann 
                                                  LEFT JOIN db_academic.auth_students auts ON (auts.NPM = ann.NPM)
                                                  WHERE IDAnnc = "'.$row['ID'].'" ')->result_array();

            $tkn_std = $this->jwt->encode($dataStd,'UAP)(*');
            $swStd = (count($dataStd)>0)
                ? '<a href="javascript:void(0);" class="showUser" data-token="'.$tkn_std.'" data-user="std">'.count($dataStd).' Students</a><br/>' : '';

            $dataEmp = $this->db->query('SELECT ann.*, em.Name FROM db_notifikasi.announcement_employees ann 
                                                  LEFT JOIN db_employees.employees em ON (em.NIP = ann.NIP)
                                                  WHERE IDAnnc = "'.$row['ID'].'" ')->result_array();

            $tkn_em = $this->jwt->encode($dataEmp,'UAP)(*');
            $swEmp = (count($dataEmp)>0)
                ? '<a href="javascript:void(0);" class="showUser" data-token="'.$tkn_em.'" data-user="emp">'.count($dataEmp).' Employees</a>' : '';


            $nestedData[] = '<div style="text-align:center;">'.$no.'</div>';
            $nestedData[] = '<div style="font-weight: bold;">'.$row['Title'].'</div>';
            $nestedData[] = '<div class="detail-message">'.$row['Message'].'</div>';
            $nestedData[] = '<div style="text-align:right;">'.$swStd.''.$swEmp.'</div>';
            $nestedData[] = '<div style="text-align:center;">'.$file.'</div>';
            $nestedData[] = '<div style="text-align:center;">'.date('d M Y',strtotime($row['Start'])).' - '.date('d M Y',strtotime($row['End'])).'</div>';
            $nestedData[] = '<div style="text-align:center;"><a href="'.base_url('announcement/edit-announcement/'.$row['ID']).'" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a></div>';
            $nestedData[] = '<div style="text-align:right;"><b>'.$row['Name'].'</b><br/>'.
                date('d M Y H:i',strtotime($row['CreatedAt'])).'</div>';

            $no++;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval(count($queryDefaultRow)),
            "recordsFiltered" => intval( count($queryDefaultRow) ),
            "data"            => $data
        );
        echo json_encode($json_data);


    }

    public function getSemesterOptionStudent($ClassOf){

        $dataSemester = $this->db->query('SELECT * FROM db_academic.semester s 
                                                    WHERE s.Year >= "'.$ClassOf.'" ORDER BY  s.ID ASC ')->result_array();

        return print_r(json_encode($dataSemester));

    }

    public function changePasswordStudent(){
        $data_arr = $this->getInputToken();

        $NPM = $data_arr['NPM'];

        $Old = $this->genratePassword($NPM,$data_arr['Old']);

        $check = $this->db->select('ID')->get_where('db_academic.auth_students',array(
            'NPM' => $NPM,
            'Password' => $Old
        ))->result_array();

        $result = 0;
        if(count($check)>0){
            $New = $this->genratePassword($NPM,$data_arr['New']);
            $this->db->set('Password', $New);
            $this->db->where('NPM', $NPM);
            $this->db->update('db_academic.auth_students');
            $result = 1;
        }

        return print_r(json_encode($result));

    }

    private function genratePassword($Username,$Password){

        $plan_password = $Username.''.$Password;
        $pas = md5($plan_password);
        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

        return $pass;
    }

    public function pass(){

    }

}