<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_reservation extends CI_Model {


    function __construct()
    {
        parent::__construct();
    }
    

    public function get_m_equipment_additional($available = '> 0')
    {
        $sql = 'select a.*,b.* from db_reservation.m_equipment_additional as a join db_reservation.m_equipment as b
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
        $sql = "select a.Room,b.NameEng,c.StartSessions,c.EndSessions,TIMEDIFF(CONCAT(curdate(),' ',EndSessions), CONCAT(curdate(),' ',StartSessions)) as time
                from db_academic.classroom as a join db_academic.schedule_details as c
                on a.ID = c.ClassroomID
                join db_academic.days as b
                on c.DayID = b.ID
                where CURDATE() >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                and CURDATE() <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
                and b.NameEng = ?
                order by a.Room";
        $query=$this->db->query($sql, array($NameDay))->result_array();

        $date = ($date == null) ? '' : '  and DATE_FORMAT(a.`Start`,"%Y-%m-%d") = "'.$date.'"';
        $sql2 = 'select a.*,b.Name from db_reservation.t_booking as a
         join db_employees.employees as b on a.CreatedBy = b.NIP
         where a.Status = 1 '.$date;
        $query2=$this->db->query($sql2, array())->result_array();

        for ($i=0; $i < count($query); $i++) { 
            $time = $query[$i]['time'];
            $time = explode(':', $time);
            $time = ($time[0] * 60) + $time[1];
            $colspan = $time / 30;
            $colspan = (int)$colspan;
            $a = $colspan % 30;
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
                'approved' => 1,
                //'NameEng' => $query[$i]['NameEng'],
            );
            $arr_result[] = $dt;
        }

        return $arr_result;
    }

    public function checkBentrok($Start,$End,$chk_e_multiple)
    {
        // check academic timeline
        $sql = 'select count(*) as total from ';
        return false;
    }
}