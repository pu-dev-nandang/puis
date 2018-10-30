<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_global extends Vreservation_Controler {

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
        $this->load->library('JWT');
    }


    public function index()
    {
        $content = $this->load->view($this->pathView.'dashboard/dashboard','',true);
        $this->temp($content);
    }

    public function getschedule($date = null)
    {
        // get room
        // $getRoom = $this->m_master->showData_array('db_academic.classroom');
        $getRoom = $this->m_master->caribasedprimary('db_academic.classroom','L_Venue',1);
        // get data classroom
        $NextDate = '';
        $PreviousDate = '';
        if ($date== null) {
            $date = date('Y-m-d');
        }
            $datetime = DateTime::createFromFormat('Y-m-d', $date);
            $NameDay = $datetime->format('l');
            $data1 = $this->m_reservation->getDataClassroomAcademic($NameDay,$date);



        $endTime = '20';
        $getHoursNow = date('H');
        $getHoursNow = ($date == date('Y-m-d')) ? (int)$getHoursNow : 7;
        $data['getRoom'] = $getRoom;
        $arrHours = array();

        $data_pass = $data1;
        // SORTING ASC
            usort($data_pass, function($a, $b) {
                return $a['room'] - $b['room'];
            });
        // print_r($data_pass);
        // die();

        for ($i=7; $i < $endTime; $i++) { 
            // check len
            $a = $i;
            for ($j=0; $j < 2 - strlen($i); $j++) { 
                $a = '0'.$a;
            }
            $d = $a.':30';
            $a = $a.':00';
            $arrHours[] = date("h:i a", strtotime($a));
            //$arrHours[] = date("h:i a", strtotime($d));
            if ($i != $endTime) {
                $arrHours[] = date("h:i a", strtotime($d));
            }
        }
        $data['arrHours'] = $arrHours;
        $data['data_pass'] = $data_pass;
        $data['date'] = $date;
        // previous & next
        $NextDate = date('Y-m-d', strtotime($date . ' +1 day'));
        $PreviousDate = date('Y-m-d', strtotime($date . ' -1 day'));
        $data['NextDate'] = $NextDate;
        $data['PreviousDate'] = $PreviousDate;
        $chkDate = 0;
        if(strtotime($date) >= strtotime(date('Y-m-d')) )
        {
            $chkDate =  1;
        }
        $data['chkDate'] = $chkDate;
        $content = $this->load->view($this->pathView.'schedule',$data,true);
        echo json_encode($content);
    }

    public function modal_form()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['room'] = $input['room'];
        $this->data['time'] = $input['time'];
        $this->data['user'] = $input['user'];

        // print_r(strlen($input['time']));die();

        if (strlen($input['time']) == 8) {
            $End = date("Y-m-d H:i:s", strtotime($input['tgl'].$input['time']));
            // $End = date("Y-m-d H:i:s", strtotime($input['time']));\
        }
        else
        {
            $input['tgl'] = date('Y-m-d');
            $End = date("Y-m-d H:i:s", strtotime($input['time']));
        }
        $this->data['tgl'] = $input['tgl'];
        // print_r($End);
        // print_r($input['time']);
        // cek time telah melewati waktu sekarang
        $Start = date("Y-m-d H:i:s");
        $time = $this->m_master->countTimeQuery($End, $Start);
        $time = $time[0]['time'];
        $time = explode(':', $time);
        
        if (strpos($time[0], '-00') !== false) {
            $time = ($time[0] * 60) + $time[1];
            $time = '-'.$time;
            $time = (int)$time;
        }
        else
        {
            $time0 = (int)$time[0];
            $time1 = ($time0 * 60);
            if (strpos($time1, '-') !== false) {
                $time = $time1 - $time[1];
            }
            else
            {
                $time = $time1 + $time[1];
            }
            $time = (int)$time;
        }

        $getRoom = $this->m_master->caribasedprimary('db_academic.classroom','Room',$input['room']);
        $this->data['RoomDB'] = $getRoom;
        $file = $getRoom[0]['Layout'];
        $this->data['Layout'] = $file;
        if ($time > -30) {
            switch ($input['Action']) {
                case 'add':
                    echo $this->load->view($this->pathView.'modal_form',$this->data,true);
                    break;
                case 'view':
                    $data = $input['dt'];
                    $Start = $data[1];
                    $End = $data[2];
                    $this->data['End'] = date("h:i a", strtotime($End));
                    $this->data['Start'] = date("h:i a", strtotime($Start));
                    $this->data['Agenda'] = $data[5];
                    $this->data['User'] = $data[0];
                    $ID = $data[9];
                    $get = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$ID);
                    $this->data['ParticipantQty'] = $get[0]['ParticipantQty'];
                    // get data Equipment Additional
                    $Name_equipment_add = '-';
                    if ($get[0]['ID_equipment_add'] != '' || $get[0]['ID_equipment_add'] != null) {
                        $ID_equipment_add = explode(',', $get[0]['ID_equipment_add']);
                        $Name_equipment_add = '';
                        for ($j=0; $j < count($ID_equipment_add); $j++) { 
                            $get2 = $this->m_master->caribasedprimary('db_reservation.m_equipment_additional','ID',$ID_equipment_add[$j]);
                            // print_r($ID_equipment_add);die();
                            $ID_m_equipment = $get2[0]['ID_m_equipment'];
                            $get3 = $this->m_master->caribasedprimary('db_reservation.m_equipment','ID',$ID_m_equipment);
                            if ($j != count($ID_equipment_add) - 1) {
                                $Owner = $get2[0]['Owner'];
                                $getX = $this->m_master->caribasedprimary('db_employees.division','ID',$Owner);
                                $Owner = $getX[0]['Division'];
                                $Name_equipment_add .= $get3[0]['Equipment'].' by '.$Owner.'['.$get2[0]['Qty'].'] , ';
                            }
                            else
                            {
                                $Owner = $get2[0]['Owner'];
                                $getX = $this->m_master->caribasedprimary('db_employees.division','ID',$Owner);
                                $Owner = $getX[0]['Division'];
                                $Name_equipment_add .= $get3[0]['Equipment'].' by '.$Owner.'['.$get2[0]['Qty'].']';
                            }
                            
                        }
                    }
                    $this->data['Name_equipment_add'] = $Name_equipment_add;

                    // get personel
                    $ID_add_personel = '-';
                    $Name_add_personel = '-';
                    if ($get[0]['ID_add_personel'] != '' || $get[0]['ID_add_personel'] != null) {
                        $ID_add_personel = explode(',', $get[0]['ID_add_personel']);
                        $Name_add_personel = '';
                        for ($j=0; $j < count($ID_add_personel); $j++) { 
                            $get2 = $this->m_master->caribasedprimary('db_employees.division','ID',$ID_add_personel[$j]);
                            if ($j != count($ID_add_personel) - 1) {
                                $Name_add_personel .= $get2[0]['Division'].',';
                            }
                            else
                            {
                                $Name_add_personel .= $get2[0]['Division'];
                            }
                        }

                    }
                    $this->data['Name_add_personel'] = $Name_add_personel;

                    
                    if ($get[0]['Req_layout'] != '' || $get[0]['Req_layout'] != null) {
                         $Req_layout = '<a href="'.base_url("fileGetAny/vreservation-".$get[0]['Req_layout']."").'" target="_blank"></i>Layout</a>';
                    }
                    else
                    {
                        $Req_layout = '<a href="'.base_url("fileGetAny/vreservation-".$file).'" target="_blank"></i>Default Layout</a>';
                    }
                    
                    $MarkomSupport = '<label>No</Label>';
                    if ($get[0]['MarcommSupport'] != '') {
                        $MarkomSupport = '<ul>';
                        $dd = explode(',', $get[0]['MarcommSupport']);
                        for ($zx=0; $zx < count($dd); $zx++) {
                            $a = 'How are you?';

                            if (strpos($dd[$zx], 'Graphic Design') !== false) {
                                 $pos = strpos($dd[$zx],'[');
                                 $li = substr($dd[$zx], 0,$pos);
                                 $posE = strpos($dd[$zx],']');
                                 $ISIe = substr($dd[$zx], ($pos+1), $posE);
                                 $length = strlen($ISIe);
                                 $ISIe = substr($ISIe, 0, ($length - 1));
                                 // print_r($ISIe);die();
                                 $MarkomSupport .= '<li>'.$li;
                                 $FileMarkom = explode(';', $ISIe);
                                 $MarkomSupport .= '<ul>';
                                 for ($vc=0; $vc < count($FileMarkom); $vc++) { 
                                    $MarkomSupport .= '<li>'.'<a href="'.base_url("fileGetAny/vreservation-".$FileMarkom[$vc]).'" target="_blank"></i>'.$FileMarkom[$vc].'</a>';
                                 }
                                 $MarkomSupport .= '</ul></li>';
                            } 
                            else{
                              $MarkomSupport .= '<li>'.$dd[$zx].'</li>';  
                            }
                            
                        }
                        $MarkomSupport .= '</ul>';

                    }
                    $this->data['MarkomSupport'] = $MarkomSupport;
                    $this->data['Req_layout'] = $Req_layout;
                    $this->data['ID'] = $get[0]['ID'];
                    echo $this->load->view($this->pathView.'modal_form_view',$this->data,true);
                    break;
                default:
                    # code...
                    break;
            }
        }
        else
        {
            $html = '<div>Time date selected is less than present time</div><br><div style="text-align: center;">       
            <div class="col-sm-12" id="BtnFooter">
                <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>
            </div>
        </div>';
        echo $html;
        }
        
    }

    public function getCountApprove()
    {
        $getData= $this->m_reservation->getCountApprove();
        echo json_encode($getData);
    }

}
