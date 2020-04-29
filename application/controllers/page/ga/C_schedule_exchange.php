<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_schedule_exchange extends Ga_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement();
        $this->load->model(array('vreservation/m_reservation','General_model','general-affair/m_general_affair'));        
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function index($page)
    {
        $department = parent::__getDepartement();
        $this->data['page'] = $page;
        $content = $this->load->view('page/'.$this->data['department'].'/schedule_exchange/page_index',$this->data,true);
        $this->temp($content);
    }

    public function schedule_exchange_action(){
        $department = parent::__getDepartement();
        $data[''] = '';
        $page = $this->load->view('page/'.$department.'/schedule_exchange/schedule_exchange_action',$data,true);
        $this->index($page);
    }

    public function submit_change_status()
    {
        $msg = '';
        $this->auth_ajax();
        $input = $this->getInputToken();
        $msg = '';
        $Status = $input['status'];
        $token = $input['token'];
        $key = "s3Cr3T-G4N";
        $data_arr_token = (array) $this->jwt->decode($token,$key);

        $emailrequest = $input['emailrequest'];
        $emailkaprodi = $input['emailkaprodi'];
        $scheduleexchangeid = $input['scheduleexchangeid'];

        if (array_key_exists('reason', $input)) {
            $reason = $input['reason'];
            $dataSave = array(
                  'Comment' => $reason,
                  'Status' =>  $Status,
                  'Updated2At' => date('Y-m-d H:i:s'),
                  'Updated2By' => $this->session->userdata('NIP'),
            );
            $this->db->where('ID',$scheduleexchangeid);
            $this->db->update('db_academic.schedule_exchange',$dataSave);
                if ($this->db->affected_rows() > 0 )
                 {
                   // send email
                    $text = '<div>
                        Dear <span style="color: #333;">Lecturer</span>,
                        <br/>
                        Perihal : <b>Permohonan Ruangan Untuk Kuliah Pengganti</b><br/><br/>
                       <div style="background: lightyellow;color: red;border: 1px solid red; text-align: center;padding: 7px;margin-bottom: 10px;">
                           <h2 style="margin-top: 7px;margin-bottom: 0px;">Permohonan Ditolak</h2>
                           <p style="color: blue;margin-top: 3px;">
                               '.$reason.' 
                           </p>
                       </div><br/>
                        <br/>
                        <div style="text-align: center;">
                            <p>--- Detail permohonan ---</p>
                        </div>
                        <div style="font-size: 14px;">
                            <table  width="100%" cellspacing="0" cellpadding="1" border="0">
                                <tbody>
                                <tr>
                                    <td style="width: 20%;">Dosen</td>
                                    <td style="width: 2%;">:</td>
                                    <td style="width: 40%;">'.$data_arr_token['Lecturer'].'</td>
                                </tr>
                                <tr>
                                    <td>Program Studi</td>
                                    <td>:</td>
                                    <td>'.$data_arr_token['Prodi'].'</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="color: #673AB7;">Mengajukan permohonan untuk kuliah pengganti</td>
                                </tr>
                                <tr>
                                    <td>Mata Kuliah</td>
                                    <td>:</td>
                                    <td>'.$data_arr_token['Course'].'</td>
                                </tr>
                                <tr>
                                    <td>Group Kelas</td>
                                    <td>:</td>
                                    <td>'.$data_arr_token['ClassGroup'].'</td>
                                </tr>
                                <tr>
                                    <td>Sesi (Pertemuan ke)</td>
                                    <td>:</td>
                                    <td>'.$data_arr_token['Meeting'].'</td>
                                </tr>
                                <tr>
                                    <td>Jadwal Semula</td>
                                    <td>:</td>
                                    <td>'.$data_arr_token['ScheduleExist'].'</td>
                                </tr>
                                <tr>
                                    <td>Diganti Pada</td>
                                    <td>:</td>
                                    <td style="color: green;font-weight: bolder;">'.$data_arr_token['ScheduleExchange'].'</td>
                                </tr>
                                <tr>
                                    <td>Alasan</td>
                                    <td>:</td>
                                    <td>'.$data_arr_token['Reason'].'</td>
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
                                        Reject By
                                        <br/>
                                        <h3 style="color: #009688;margin-top: 7px;">'.$this->session->userdata('Name').'
                                            <br/>
                                        <small>'.$this->session->userdata('NIP').'</small>
                                        </h3>
                                    </td>
    
                                </tr>
                            </table>
                        </div>
                    </div>';

                    $to = $emailrequest.','.$emailkaprodi;
//                    if ($_SERVER['SERVER_NAME'] == 'localhost') {
//                        $to = $emailrequest.','.$emailkaprodi.','.'nandang.mulyadi@podomorouniversity.ac.id,novita.riani@podomorouniversity.ac.id';
//                    }
                    $subject = "GA : Permohonan Ruangan Untuk Kuliah Pengganti";
                    $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                }
                 
        }

        if (array_key_exists('Room', $input)) {
            // alert check bentrok
            $G_data = $this->m_master->caribasedprimary('db_academic.schedule_exchange','ID',$scheduleexchangeid);
            $Start = date("Y-m-d H:i:s", strtotime($G_data[0]['Date'].$G_data[0]['StartSessions']));
            $End = date("Y-m-d H:i:s", strtotime($G_data[0]['Date'].$G_data[0]['EndSessions']));
            $Room = $input['Room'];
            $roomname = $input['roomname'];
            if (array_key_exists('confirm', $input)) {
                if ($input['confirm'] == 1) {
                    $chk['bool'] = true;
                }
                else
                {
                    $chk = $this->m_reservation->checkBentrok2($Start,$End,'',$roomname);
                }
            }
            else
            {
                $chk = $this->m_reservation->checkBentrok2($Start,$End,'',$roomname);
            }

            $bool = $chk['bool'];
            
            if ($bool) {
                $dataSave = array(
                      'ClassroomID' => $Room,
                      'Status' =>  $Status,
                      'Updated2At' => date('Y-m-d H:i:s'),
                      'Updated2By' => $this->session->userdata('NIP'),
                );
                $this->db->where('ID',$scheduleexchangeid);
                $this->db->update('db_academic.schedule_exchange',$dataSave);

                if ($this->db->affected_rows() > 0 )
                {
                  // send email
                     $text = '<div>
                         Dear <span style="color: #333;">Lecturer</span>,
                         <br/>
                         Perihal : <b>Permohonan Ruangan Untuk Kuliah Pengganti</b><br/><br/>
                         <div style="background: lightyellow;border: 1px solid green;color: green;text-align: center;padding: 7px;margin-bottom: 10px;">
                               <h2 style="margin-top: 7px;margin-bottom: 10px;">Permohonan diterima</h2>
                           </div>
                         <br/>
                         <br/>
                         <div style="font-size: 14px;">
                             <table  width="100%" cellspacing="0" cellpadding="1" border="0">
                                 <tbody>
                                 <tr>
                                     <td style="width: 20%;">Dosen</td>
                                     <td style="width: 2%;">:</td>
                                     <td style="width: 40%;">'.$data_arr_token['Lecturer'].'</td>
                                 </tr>
                                 <tr>
                                     <td>Program Studi</td>
                                     <td>:</td>
                                     <td>'.$data_arr_token['Prodi'].'</td>
                                 </tr>
                                 <tr>
                                     <td colspan="3" style="color: #673AB7;">Mengajukan permohonan untuk kuliah pengganti</td>
                                 </tr>
                                 <tr>
                                     <td>Mata Kuliah</td>
                                     <td>:</td>
                                     <td>'.$data_arr_token['Course'].'</td>
                                 </tr>
                                 <tr>
                                     <td>Group Kelas</td>
                                     <td>:</td>
                                     <td>'.$data_arr_token['ClassGroup'].'</td>
                                 </tr>
                                 <tr>
                                     <td>Sesi (Pertemuan ke)</td>
                                     <td>:</td>
                                     <td>'.$data_arr_token['Meeting'].'</td>
                                 </tr>
                                 <tr>
                                     <td>Jadwal Semula</td>
                                     <td>:</td>
                                     <td>'.$data_arr_token['ScheduleExist'].'</td>
                                 </tr>
                                 <tr>
                                     <td>Diganti Pada</td>
                                     <td>:</td>
                                     <td style="color: green;font-weight: bolder;">'.$data_arr_token['ScheduleExchange'].' | '.$roomname.'</td>
                                 </tr>
                                 <tr>
                                     <td>Alasan</td>
                                     <td>:</td>
                                     <td>'.$data_arr_token['Reason'].'</td>
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
                                         Approve By
                                         <br/>
                                         <h3 style="color: #009688;margin-top: 7px;">'.$this->session->userdata('Name').'
                                             <br/>
                                         <small>'.$this->session->userdata('NIP').'</small>
                                         </h3>
                                     </td>
                    
                                 </tr>
                             </table>
                         </div>
                     </div>';

                     $to = $emailrequest.','.$emailkaprodi;
                     $subject = "GA : Permohonan Ruangan Untuk Kuliah Pengganti";
                     $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                }
            }
            else
            {
                $msg = $chk;

            }
            
        }

        echo json_encode($msg);
    }

    

}
