<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_reservation extends CI_Model {


    function __construct()
    {
        parent::__construct();
    }
    

    public function get_m_equipment_additional($available = '> 0')
    {
        $sql = 'select a.ID as ID_add,a.*,b.* from db_reservation.m_equipment_additional as a join db_reservation.m_equipment as b
        on a.ID_m_equipment = b.ID where a.Qty '.$available;
        $query=$this->db->query($sql, array());
        return $query->result_array();
    }

    public function get_m_additional_personel()
    {
        $sql = 'select a.*,b.* from db_reservation.m_additional_personel as a join db_employees.division as b
        on a.ID_division = b.ID';
        $query=$this->db->query($sql, array());
        return $query->result_array();
    }

    public function getDataClassroomAcademic($NameDay,$date)
    {
        $arr_result = array();
        $date2 = $date;
        $date2 = ($date2 == null) ? date('Y-m-d') : $date2;
        // $sql = "select a.Room,b.NameEng,c.StartSessions,c.EndSessions,TIMEDIFF(CONCAT(curdate(),' ',EndSessions), CONCAT(curdate(),' ',StartSessions)) as time
        //         from db_academic.classroom as a join db_academic.schedule_details as c
        //         on a.ID = c.ClassroomID
        //         join db_academic.days as b
        //         on c.DayID = b.ID
        //         where CURDATE() >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
        //         and CURDATE() <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
        //         and b.NameEng = ?
        //         order by a.Room";
        $sql = "select a.Room,b.NameEng,c.StartSessions,c.EndSessions,TIMEDIFF(CONCAT(curdate(),' ',EndSessions), CONCAT(curdate(),' ',StartSessions)) as time
                from db_academic.classroom as a join db_academic.schedule_details as c
                on a.ID = c.ClassroomID
                join db_academic.days as b
                on c.DayID = b.ID
                where CURDATE() >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                and CURDATE() <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
                and b.NameEng = ?
                and c.ID not in (select a.ScheduleID from db_academic.attendance as a join db_academic.schedule_exchange as b
                on a.ID = b.ID_Attd where b.Status = '1' and b.DateOriginal = '".$date2."')
                order by a.Room";        
        $query=$this->db->query($sql, array($NameDay))->result_array();

        $date = ($date == null) ? '' : '  and DATE_FORMAT(a.`Start`,"%Y-%m-%d") = "'.$date.'"';
        $sql2 = 'select a.*,b.Name from db_reservation.t_booking as a
         join db_employees.employees as b on a.CreatedBy = b.NIP
         where a.Status in(0,1)  '.$date;
        $query2=$this->db->query($sql2, array())->result_array();

        $sql3 = 'select a.Room,b.NameEng,c.StartSessions,c.EndSessions,TIMEDIFF(CONCAT(curdate()," ",EndSessions), CONCAT(curdate()," ",StartSessions)) as time 
                from db_academic.classroom as a join db_academic.schedule_exchange as c
                on a.ID = c.ClassroomID
                join db_academic.days as b
                on c.DayID = b.ID where c.Status = "1" and c.Date ="'.$date2.'"';
        $query3=$this->db->query($sql3, array())->result_array();

        for ($i=0; $i < count($query); $i++) { 
            $time = $query[$i]['time'];
            $time = explode(':', $time);
            $time = ($time[0] * 60) + $time[1];
            $colspan = $time / 30;
            $colspan = (int)$colspan;
            $a = $time % 30;
            if ($a > 0) {
                $colspan++;
            }

            $start = $query[$i]['StartSessions'];
            $start = explode(':', $start);
            $start = $start[0].':'.$start[1];
            $end = $query[$i]['EndSessions'];
            $end = explode(':', $end);
            $end = $end[0].':'.$end[1];

            $dt = array(
                'user'  => 'Academic TimeTables',
                'start' => $start,
                'end'   => $end,
                'time'  => $time,
                'colspan' => $colspan,
                'agenda' => 'Study',
                'room' => $query[$i]['Room'],
                'approved' => 1,
                'NIP' => '0',
                'ID' => '0',
                //'NameEng' => $query[$i]['NameEng'],
            );
            $arr_result[] = $dt;
        }

        for ($i=0; $i < count($query2); $i++) { 
            $dt = array(
                'user'  => $query2[$i]['Name'],
                'start' => $query2[$i]['Start'],
                'end'   => $query2[$i]['End'],
                'time'  => $query2[$i]['Time'],
                'colspan' => $query2[$i]['Colspan'],
                'agenda' => $query2[$i]['Agenda'],
                'room' => $query2[$i]['Room'],
                'approved' => $query2[$i]['Status'],
                'NIP' => $query2[$i]['CreatedBy'],
                'ID' => $query2[$i]['ID'],
                //'NameEng' => $query[$i]['NameEng'],
            );
            $arr_result[] = $dt;
        }

        for ($i=0; $i < count($query3); $i++) { 
            $time = $query3[$i]['time'];
            $time = explode(':', $time);
            $time = ($time[0] * 60) + $time[1];
            $colspan = $time / 30;
            $colspan = (int)$colspan;
            $a = $time % 30;
            if ($a > 0) {
                $colspan++;
            }

            $start = $query3[$i]['StartSessions'];
            $start = explode(':', $start);
            $start = $start[0].':'.$start[1];
            $end = $query3[$i]['EndSessions'];
            $end = explode(':', $end);
            $end = $end[0].':'.$end[1];

            $dt = array(
                'user'  => 'Academic TimeTables EX',
                'start' => $start,
                'end'   => $end,
                'time'  => $time,
                'colspan' => $colspan,
                'agenda' => 'Study',
                'room' => $query3[$i]['Room'],
                'approved' => 1,
                'NIP' => '0',
                'ID' => '0',
                //'NameEng' => $query[$i]['NameEng'],
            );
            $arr_result[] = $dt;
        }

        return $arr_result;
    }

    public function checkBentrok($Start,$End,$chk_e_multiple,$Room,$NotIDMyself = '')
    {
        $bool = true;
        $NotIDMyself = ($NotIDMyself == '') ? '' : ' and a.ID != '.$NotIDMyself;
        for ($xx=0; $xx < 3; $xx++) {  // check twice
            // get 
            $TimeStart = date("H:i:s", strtotime($Start));
            $TimeEnd = date("H:i:s", strtotime($End));

            if ($chk_e_multiple == '') {
                // check academic timeline
                $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $Start);
                $NameDay = $datetime->format('l');
                $date2 = date("Y-m-d", strtotime($Start));

                $sql = 'select count(*) as total from db_academic.classroom as a join db_academic.schedule_details as c
                        on a.ID = c.ClassroomID
                        join db_academic.days as b
                        on c.DayID = b.ID
                        where CURDATE() >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                        and CURDATE() <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
                        and b.NameEng = "'.$NameDay.'" and ((c.StartSessions >= "'.$TimeStart.'" and c.StartSessions < "'.$TimeEnd.'" ) or (c.EndSessions > "'.$TimeStart.'" and c.EndSessions <= "'.$TimeEnd.'" )) and a.Room = "'.$Room.'" and c.ID not in (select a.ScheduleID from db_academic.attendance as a join db_academic.schedule_exchange as b
                on a.ID = b.ID_Attd where b.Status = "1" and b.DateOriginal = "'.$date2.'")';
                $query=$this->db->query($sql, array())->result_array();

                if ($query[0]['total'] > 0) {
                    $bool = false;

                }
                else
                {
                    $sql3 = 'select count(*) as total
                            from db_academic.classroom as a join db_academic.schedule_exchange as c
                            on a.ID = c.ClassroomID
                            join db_academic.days as b
                            on c.DayID = b.ID where c.Status = "1" and c.Date ="'.$date2.'" and ((c.StartSessions >= "'.$TimeStart.'" and c.StartSessions < "'.$TimeEnd.'" ) or (c.EndSessions > "'.$TimeStart.'" and c.EndSessions <= "'.$TimeEnd.'" )) and a.Room = "'.$Room.'"';
                    //print_r($sql3);die();
                    $query3=$this->db->query($sql3, array())->result_array();
                    if ($query3[0]['total'] > 0) {
                        $bool = false;
                    }
                    else
                    {
                        $sql2 = 'select count(*) as total from db_reservation.t_booking as a
                                 join db_employees.employees as b on a.CreatedBy = b.NIP
                                 where a.Status in(0,1) and ((a.`Start` >= "'.$Start.'" and a.`Start` < "'.$End.'" ) or (a.`End` > "'.$Start.'" and a.`End` <= "'.$End.'" )) and a.Room = "'.$Room.'"'.' '.$NotIDMyself;
                        $query2=$this->db->query($sql2, array())->result_array();
                         if ($query2[0]['total'] > 0) {
                            $bool = false;
                         } 
                    }
                          
                }
            }
            else
            {

                // check academic timeline untuk data satu
                $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $Start);
                $NameDay = $datetime->format('l');
                $date2 = date("Y-m-d", strtotime($Start));

                $sql = 'select count(*) as total from db_academic.classroom as a join db_academic.schedule_details as c
                        on a.ID = c.ClassroomID
                        join db_academic.days as b
                        on c.DayID = b.ID
                        where CURDATE() >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                        and CURDATE() <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
                        and b.NameEng = "'.$NameDay.'" and ((c.StartSessions >= "'.$TimeStart.'" and c.StartSessions < "'.$TimeEnd.'" ) or (c.EndSessions > "'.$TimeStart.'" and c.EndSessions <= "'.$TimeEnd.'" )) and a.Room = "'.$Room.'" and c.ID not in (select a.ScheduleID from db_academic.attendance as a join db_academic.schedule_exchange as b
                on a.ID = b.ID_Attd where b.Status = "1" and b.DateOriginal = "'.$date2.'")';
                $query=$this->db->query($sql, array())->result_array();

                if ($query[0]['total'] > 0) {
                    $bool = false;

                }
                else
                {
                    // $sql3 = 'select count(*) as total
                    //         from db_academic.classroom as a join db_academic.schedule_exchange as c
                    //         on a.ID = c.ClassroomID
                    //         join db_academic.days as b
                    //         on c.DayID = b.ID where c.Status = "1" and c.Date ="'.$date2.'"';
                    // $query3=$this->db->query($sql3, array())->result_array();
                    $sql3 = 'select count(*) as total
                            from db_academic.classroom as a join db_academic.schedule_exchange as c
                            on a.ID = c.ClassroomID
                            join db_academic.days as b
                            on c.DayID = b.ID where c.Status = "1" and c.Date ="'.$date2.'" and ((c.StartSessions >= "'.$TimeStart.'" and c.StartSessions < "'.$TimeEnd.'" ) or (c.EndSessions > "'.$TimeStart.'" and c.EndSessions <= "'.$TimeEnd.'" )) and a.Room = "'.$Room.'"';
                    //print_r($sql3);die();
                    $query3=$this->db->query($sql3, array())->result_array();
                    if ($query3[0]['total'] > 0) {
                        $bool = false;
                    }
                    else
                    {
                        $sql2 = 'select count(*) as total from db_reservation.t_booking as a
                                 join db_employees.employees as b on a.CreatedBy = b.NIP
                                 where a.Status = 1 and ((a.`Start` >= "'.$Start.'" and a.`Start` < "'.$End.'" ) or (a.`End` > "'.$Start.'" and a.`End` <= "'.$End.'" )) and a.Room = "'.$Room.'"'.' '.$NotIDMyself;
                        $query2=$this->db->query($sql2, array())->result_array();
                         if ($query2[0]['total'] > 0) {
                            $bool = false;
                         } 
                    }
                          
                }

                for ($i=0; $i < count($chk_e_multiple) ; $i++) { 
                    $getDate = $chk_e_multiple[$i];
                    $Start = $chk_e_multiple[$i].' '.$TimeStart;
                    $End = $chk_e_multiple[$i].' '.$TimeEnd;
                    // check academic timeline
                    $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $Start);
                    $NameDay = $datetime->format('l');

                    $sql = 'select count(*) as total from db_academic.classroom as a join db_academic.schedule_details as c
                            on a.ID = c.ClassroomID
                            join db_academic.days as b
                            on c.DayID = b.ID
                            where CURDATE() >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                            and CURDATE() <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
                            and b.NameEng = "'.$NameDay.'" and ((c.StartSessions >= "'.$TimeStart.'" and c.StartSessions < "'.$TimeEnd.'" ) or (c.EndSessions > "'.$TimeStart.'" and c.EndSessions <= "'.$TimeEnd.'" )) and a.Room = "'.$Room.'" and c.ID not in (select a.ScheduleID from db_academic.attendance as a join db_academic.schedule_exchange as b
                             on a.ID = b.ID_Attd where b.Status = "1" and b.DateOriginal = "'.$getDate.'")';
                    $query=$this->db->query($sql, array())->result_array();
                    if ($query[0]['total'] > 0) {
                        $bool = false;
                        break;

                    }
                    else
                    {
                        // $sql3 = 'select count(*) as total
                        //         from db_academic.classroom as a join db_academic.schedule_exchange as c
                        //         on a.ID = c.ClassroomID
                        //         join db_academic.days as b
                        //         on c.DayID = b.ID where c.Status = "1" and c.Date ="'.$getDate.'"';
                        // $query3=$this->db->query($sql3, array())->result_array();
                        $sql3 = 'select count(*) as total
                                from db_academic.classroom as a join db_academic.schedule_exchange as c
                                on a.ID = c.ClassroomID
                                join db_academic.days as b
                                on c.DayID = b.ID where c.Status = "1" and c.Date ="'.$getDate.'" and ((c.StartSessions >= "'.$TimeStart.'" and c.StartSessions < "'.$TimeEnd.'" ) or (c.EndSessions > "'.$TimeStart.'" and c.EndSessions <= "'.$TimeEnd.'" )) and a.Room = "'.$Room.'"';
                        //print_r($sql3);die();
                        $query3=$this->db->query($sql3, array())->result_array();
                        if ($query3[0]['total'] > 0) {
                            $bool = false;
                        }
                        else
                        {
                            $sql2 = 'select count(*) as total from db_reservation.t_booking as a
                                     join db_employees.employees as b on a.CreatedBy = b.NIP
                                     where a.Status in(0,1) and ((a.`Start` >= "'.$Start.'" and a.`Start` < "'.$End.'" ) or (a.`End` > "'.$Start.'" and a.`End` <= "'.$End.'" )) and a.Room = "'.$Room.'" '.$NotIDMyself;
                            $query2=$this->db->query($sql2, array())->result_array();
                             if ($query2[0]['total'] > 0) {
                                $bool = false;
                                break;
                             } 
                        }
                              
                    }
                }
            }
            usleep( 1000 );
        }

        return $bool;
    }

    public function getCountApprove()
    {
        $sql = 'select count(*) as total from db_reservation.t_booking where Status = 0 and Start >= timestamp(DATE_SUB(NOW(), INTERVAL 30 MINUTE))';
        $query=$this->db->query($sql, array())->result_array();
        return $query[0]['total'];

    }

    public function getDataT_booking($Start = null,$Status = 0)
    {
        $arr_result = array();
        $this->load->model('master/m_master');
        $Start = ($Start == null) ? ' and Start >= timestamp(DATE_SUB(NOW(), INTERVAL 30 MINUTE))' : ' and Start like "%'.$Start.'%"';
        $sql = 'select a.*,b.Name from db_reservation.t_booking as a join db_employees.employees as b on a.CreatedBy = b.NIP where a.Status = ?
                 '.$Start;
        $query=$this->db->query($sql, array($Status))->result_array();
        // print_r($query);die();
        for ($i=0; $i < count($query); $i++) { 
            $Startdatetime = DateTime::createFromFormat('Y-m-d H:i:s', $query[$i]['Start']);
            $Enddatetime = DateTime::createFromFormat('Y-m-d H:i:s', $query[$i]['End']);
            $StartNameDay = $Startdatetime->format('l');
            $EndNameDay = $Enddatetime->format('l');
            $Time = $query[$i]['Time'].' Minutes';
            $ID_equipment_add = '-';
            $Name_equipment_add = '-';
            if ($query[$i]['ID_equipment_add'] != '' || $query[$i]['ID_equipment_add'] != null) {
                $ID_equipment_add = explode(',', $query[$i]['ID_equipment_add']);
                $Name_equipment_add = '<ul>';
                for ($j=0; $j < count($ID_equipment_add); $j++) { 
                    $get = $this->m_master->caribasedprimary('db_reservation.m_equipment_additional','ID',$ID_equipment_add[$j]);
                    // print_r($ID_equipment_add);die();
                    $ID_m_equipment = $get[0]['ID_m_equipment'];
                    $get = $this->m_master->caribasedprimary('db_reservation.m_equipment','ID',$ID_m_equipment);
                    $Name_equipment_add .= '<li>'.$get[0]['Equipment'].'</li>';
                }
                $Name_equipment_add .= '</ul>';
            }

            $ID_add_personel = '-';
            $Name_add_personel = '-';
            if ($query[$i]['ID_add_personel'] != '' || $query[$i]['ID_add_personel'] != null) {
                $ID_add_personel = explode(',', $query[$i]['ID_add_personel']);
                $Name_add_personel = '<ul>';
                for ($j=0; $j < count($ID_add_personel); $j++) { 
                    $get = $this->m_master->caribasedprimary('db_employees.division','ID',$ID_add_personel[$j]);
                    $Name_add_personel .= '<li>'.$get[0]['Division'].'</li>';
                }

                $Name_add_personel .= '</ul>';
            }

            $Reqdatetime = DateTime::createFromFormat('Y-m-d', $query[$i]['Req_date']);
            $ReqdateNameDay = $Reqdatetime->format('l');
            $arr_result[] = array(
                    'Start' => $StartNameDay.', '.$query[$i]['Start'],
                    'End' => $EndNameDay.', '.$query[$i]['End'],
                    'Time' => $Time,
                    'Agenda' => $query[$i]['Agenda'],
                    'Room' => $query[$i]['Room'],
                    'Equipment_add' => $Name_equipment_add,
                    'Persone_add' => $Name_add_personel,
                    'Req_date' => $ReqdateNameDay.', '.$query[$i]['Req_date'],
                    'Req_layout' => $query[$i]['Req_layout'],
                    'ID' => $query[$i]['ID'],
            );
        }

        return $arr_result;         
    }

    public function get_m_room_equipment($room)
    {
        $sql = 'select a.Room,a.qty,b.Equipment from db_reservation.m_room_equipment as a join db_reservation.m_equipment as b on a.ID_m_equipment = b.ID and a.Room = ?';
        $query=$this->db->query($sql, array($room))->result_array();
        return $query;
    }

    public function checkBentrokScheduleAPI()
    {
        $bool = true;
        $arr_result = array('ID' => '','bool' => $bool);
        // get data schedule details and compare with t_booking table
        $sql = 'select * from db_reservation.t_booking where Start >= timestamp(DATE_SUB(NOW(), INTERVAL 30 MINUTE))';
        $query=$this->db->query($sql, array())->result_array();
        for ($i=0; $i < count($query); $i++) {
             $Start = $query[$i]['Start'];
             $End = $query[$i]['End'];
             $Room = $query[$i]['Room'];
             // get 
             $TimeStart = date("H:i:s", strtotime($Start));
             $TimeEnd = date("H:i:s", strtotime($End)); 
             // check academic timeline
                $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $Start);
                $NameDay = $datetime->format('l');
                $date2 = date("Y-m-d", strtotime($Start));

                $sql2 = 'select count(*) as total from db_academic.classroom as a join db_academic.schedule_details as c
                        on a.ID = c.ClassroomID
                        join db_academic.days as b
                        on c.DayID = b.ID
                        where CURDATE() >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                        and CURDATE() <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
                        and b.NameEng = "'.$NameDay.'" and ((c.StartSessions >= "'.$TimeStart.'" and c.StartSessions < "'.$TimeEnd.'" ) or (c.EndSessions > "'.$TimeStart.'" and c.EndSessions <= "'.$TimeEnd.'" )) and a.Room = "'.$Room.'" and c.ID not in (select a.ScheduleID from db_academic.attendance as a join db_academic.schedule_exchange as b
                on a.ID = b.ID_Attd where b.Status = "1" and b.DateOriginal = "'.$date2.'")';
                $query2=$this->db->query($sql2, array())->result_array();
                if ($query2[0]['total'] > 0) {
                    // print_r('bentrok :'.$NameDay.', '.$TimeStart.'-'.$TimeEnd.' : '.$date2).'<br>';
                    $bool = false;
                    // break;
                    $arr_result = array('ID' => $query[$i]['ID'],'bool' => $bool);
                    break;
                }
                else
                {
                    $sql3 = 'select count(*) as total
                            from db_academic.classroom as a join db_academic.schedule_exchange as c
                            on a.ID = c.ClassroomID
                            join db_academic.days as b
                            on c.DayID = b.ID where c.Status = "1" and c.Date ="'.$date2.'" and ((c.StartSessions >= "'.$TimeStart.'" and c.StartSessions < "'.$TimeEnd.'" ) or (c.EndSessions > "'.$TimeStart.'" and c.EndSessions <= "'.$TimeEnd.'" )) and a.Room = "'.$Room.'"';
                    //print_r($sql3);die();
                    $query3=$this->db->query($sql3, array())->result_array();
                    if ($query3[0]['total'] > 0) {
                        // print_r('bentrok :'.$NameDay.', '.$TimeStart.'-'.$TimeEnd.' : '.$date2);
                        $bool = false;
                        // break;
                        $arr_result = array('ID' => $query[$i]['ID'],'bool' => $bool);
                        break;
                    }
                    else
                    {
                        // print_r('Ok<br>');
                    }
                }
        }

        // die();
        return $arr_result;

    }
}