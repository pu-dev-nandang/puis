<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_reservation extends CI_Model {


    function __construct()
    {
        parent::__construct();
    }

    public function getdataMenu()
    {
        $sql = "select * from db_reservation.cfg_menu";
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function saveMenu($menu)
    {
        $dataSave = array(
            'Menu' => ucwords($menu),
        );
        $this->db->insert('db_reservation.cfg_menu', $dataSave);
    }

    public function saveSubMenu($menu,$sub_menu1,$sub_menu2,$chkPrevileges,$Slug,$Controller)
    {
        $sub_menu2 = ($sub_menu2 == '') ? 'empty' : $sub_menu2;
        // print_r($chkPrevileges);
        $dataSave = array();
        $dataSave['ID_Menu'] = $menu;
        $dataSave['SubMenu1'] = ucwords($sub_menu1);
        $dataSave['SubMenu2'] = ucwords($sub_menu2);
        $dataSave['Slug'] = $Slug;
        $dataSave['Controller'] = $Controller;

        for ($i=0; $i < count($chkPrevileges) ; $i++) {
            switch ($chkPrevileges[$i]) {
                case 'Read':
                    $dataSave['read'] = 1;
                    break;
                case 'Write':
                    $dataSave['write'] = 1;
                    break;
                case 'Update':
                    $dataSave['update'] = 1;
                    break;
                case 'Delete':
                    $dataSave['delete'] = 1;
                    break;
                default:
                    $dataSave['read'] = 0;
                    $dataSave['write'] = 0;
                    $dataSave['update'] = 0;
                    $dataSave['delete'] = 0;
                    break;
            }
        }
        // print_r($dataSave);
        $this->db->insert('db_reservation.cfg_sub_menu', $dataSave);
    }

    public function showSubmenu()
    {
        $sql = "select a.Menu,b.* from db_reservation.cfg_menu as a
          join db_reservation.cfg_sub_menu as b
          on a.ID = b.ID_Menu";
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function updateSubMenu($input)
    {
        // $dataArr = array();
        $ID_Menu = '';
        $Menu = '';
        $SubMenu1 = '';
        $SubMenu2 = '';
        $read = '';
        $write = '';
        $update = '';
        $delete = '';
        $ID = '';
        $query = '';
        $Slug = '';
        $Controller = '';

        if(array_key_exists("Menu",$input))
        {
            $ID_Menu = $input['ID_Menu'];
            $Menu = $input['Menu'];
            $sql = "update db_reservation.cfg_menu set Menu = ? where ID = ? ";
            $query=$this->db->query($sql, array($Menu,$ID_Menu));
        }

        if(array_key_exists("Slug",$input))
        {
            $ID_Menu = $input['ID_Menu'];
            $Slug = $input['Slug'];
            $sql = "update db_reservation.cfg_sub_menu set Slug = ? where ID = ? ";
            $query=$this->db->query($sql, array($Slug,$ID_Menu));
        }

        if(array_key_exists("Controller",$input))
        {
            $ID_Menu = $input['ID_Menu'];
            $Controller = $input['Controller'];
            $sql = "update db_reservation.cfg_sub_menu set Controller = ? where ID = ? ";
            $query=$this->db->query($sql, array($Controller,$ID_Menu));
        }

        if(array_key_exists("SubMenu1",$input))
        {
            $ID = $input['ID'];
            $SubMenu1 = $input['SubMenu1'];
            $sql = "update db_reservation.cfg_sub_menu set SubMenu1 = ? where ID = ? ";
            $query=$this->db->query($sql, array($SubMenu1,$ID));
        }

        if(array_key_exists("SubMenu2",$input))
        {
            $ID = $input['ID'];
            $SubMenu2 = $input['SubMenu2'];
            $sql = "update db_reservation.cfg_sub_menu set SubMenu2 = ? where ID = ? ";
            $query=$this->db->query($sql, array($SubMenu2,$ID));
        }

        if(array_key_exists("read",$input))
        {
            $ID = $input['ID'];
            $read = $input['read'];
            $sql = "update db_reservation.cfg_sub_menu set `read` = ? where ID = ? ";
            $query=$this->db->query($sql, array($read,$ID));
        }

        if(array_key_exists("write",$input))
        {
            $ID = $input['ID'];
            $write = $input['write'];
            $sql = "update db_reservation.cfg_sub_menu set `write` = ? where ID = ? ";
            $query=$this->db->query($sql, array($write,$ID));
        }

        if(array_key_exists("update",$input))
        {
            $ID = $input['ID'];
            $update = $input['update'];
            $sql = "update db_reservation.cfg_sub_menu set `update` = ? where ID = ? ";
            $query=$this->db->query($sql, array($update,$ID));
        }

        if(array_key_exists("delete",$input))
        {
            $ID = $input['ID'];
            $delete = $input['delete'];
            $sql = "update db_reservation.cfg_sub_menu set `delete` = ? where ID = ? ";
            $query=$this->db->query($sql, array($delete,$ID));
        }

    }

    public function deleteSubMenu($input)
    {
        $sql = "delete from db_reservation.cfg_sub_menu where ID = ".$input['ID'];
        $query=$this->db->query($sql, array());
    }

    public function get_submenu_by_menu($input)
    {
        $ID_Menu = $input['Menu'];
        $GroupUser = $input['GroupUser'];
        $sql = "select a.Menu,b.* from db_reservation.cfg_menu as a
      join db_reservation.cfg_sub_menu as b
      on a.ID = b.ID_Menu where b.ID_Menu = ?
      and b.ID not in (select ID_cfg_sub_menu from db_reservation.cfg_rule_g_user where cfg_group_user = ?)";
        $query=$this->db->query($sql, array($ID_Menu,$GroupUser))->result_array();
        return $query;
    }

    public function get_previleges_group_show($GroupID)
    {
        $sql = 'SELECT d.GroupAuth, b.Menu,c.SubMenu1,c.SubMenu2,c.ID_Menu,a.ID_cfg_sub_menu,a.ID as ID_previleges,a.`read`,a.`write`,a.`update`,
a.`delete`,c.`read` as readMenu,c.`update` as updateMenu,c.`write` as writeMenu,c.`delete` as deleteMenu from db_reservation.cfg_rule_g_user as a
            join db_reservation.cfg_group_user as d
            on a.cfg_group_user = d.ID
            join db_reservation.cfg_sub_menu as c
            on a.ID_cfg_sub_menu = c.ID
            join db_reservation.cfg_menu as b
            on b.ID = c.ID_Menu where d.ID = ? ';
        $query=$this->db->query($sql, array($GroupID))->result_array();
        return $query;
    }

    public function save_groupuser_previleges($input)
    {
        $ID_GroupUSer = $input['ID_GroupUSer'];
        $checkbox = $input['checkbox'];
        $data = array();
        $increment = 0;
        for ($i=0; $i < count($checkbox); $i++) {
            $value = strtolower($checkbox[$i]->value);
            $ID_cfg_sub_menu = $checkbox[$i]->ID;

            // check data pertama
            if (count($data) == 0) {
                $data[$increment] = array(
                    'cfg_group_user' => $ID_GroupUSer,
                    'ID_cfg_sub_menu' => $ID_cfg_sub_menu,
                    $value => 1,
                );
                continue;
            }

            if (count($data) > 0) {
                // check data ada pada array
                $check = false;
                for ($j=0; $j < count($data); $j++) {
                    if ($data[$j]['ID_cfg_sub_menu'] == $ID_cfg_sub_menu) {
                        $data[$j][$value] = 1;
                        $check = true;
                        break;
                    }
                }

                if ($check) {
                    continue;
                }

                // check data tidak ada pada array
                for ($j=0; $j < count($data); $j++) {
                    if ($data[$j]['ID_cfg_sub_menu'] != $ID_cfg_sub_menu) {
                        $check = true;
                        break;
                    }
                }

                if ($check) {
                    $increment++;
                    $data[$increment] = array(
                        'cfg_group_user' => $ID_GroupUSer,
                        'ID_cfg_sub_menu' => $ID_cfg_sub_menu,
                        $value => 1,
                    );
                    continue;
                }

            }

        }

        // print_r($data);
        // save data
        for ($i=0; $i < count($data); $i++) {
            $dataSave = array();
            foreach ($data[$i] as $key => $value) {
                $dataSave[$key] = $value;
                // $dataSave = array($key=>$value);
            }
            $this->db->insert('db_reservation.cfg_rule_g_user', $dataSave);
        }
    }

    public function previleges_groupuser_update($input)
    {
        // $dataArr = array();
        $read = '';
        $write = '';
        $update = '';
        $delete = '';
        $ID = '';
        $query = '';

        if(array_key_exists("read",$input))
        {
            $ID = $input['ID'];
            $read = $input['read'];
            $sql = "update db_reservation.cfg_rule_g_user set `read` = ? where ID = ? ";
            $query=$this->db->query($sql, array($read,$ID));
        }

        if(array_key_exists("write",$input))
        {
            $ID = $input['ID'];
            $write = $input['write'];
            $sql = "update db_reservation.cfg_rule_g_user set `write` = ? where ID = ? ";
            $query=$this->db->query($sql, array($write,$ID));
        }

        if(array_key_exists("update",$input))
        {
            $ID = $input['ID'];
            $update = $input['update'];
            $sql = "update db_reservation.cfg_rule_g_user set `update` = ? where ID = ? ";
            $query=$this->db->query($sql, array($update,$ID));
        }

        if(array_key_exists("delete",$input))
        {
            $ID = $input['ID'];
            $delete = $input['delete'];
            $sql = "update db_reservation.cfg_rule_g_user set `delete` = ? where ID = ? ";
            $query=$this->db->query($sql, array($delete,$ID));
        }
    }

    public function previleges_groupuser_delete($input)
    {
        $sql = "delete from db_reservation.cfg_rule_g_user where ID = ".$input['ID'];
        $query=$this->db->query($sql, array());
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

    public function getDataT_bookingByUser($Start = null,$Status = 0,$both = '')
    {
        $arr_result = array();
        $this->load->model('master/m_master');
        $Start = ($Start == null) ? ' and Start >= timestamp(DATE_SUB(NOW(), INTERVAL 30 MINUTE))' : ' and Start like "%'.$Start.'%"';
        if ($both == '') {
            $sql = 'select a.*,b.Name from db_reservation.t_booking as a join db_employees.employees as b on a.CreatedBy = b.NIP where a.Status = ?
                     '.$Start.' and a.CreatedBy = ? ';
            $query=$this->db->query($sql, array($Status,$this->session->userdata('NIP')))->result_array();         
        }
        else
        {
            $sql = 'select a.*,b.Name from db_reservation.t_booking as a join db_employees.employees as b on a.CreatedBy = b.NIP where a.Status like "%"
                     '.$Start.' and a.CreatedBy = ? ';
            $query=$this->db->query($sql, array($this->session->userdata('NIP')))->result_array();           
        }
        
        
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
                    'Status' => $query[$i]['Status'],
            );
        }

        return $arr_result;         
    }

    public function getDataT_booking($Start = null,$Status = 0,$both = '')
    {
        $arr_result = array();
        $this->load->model('master/m_master');
        $Start = ($Start == null) ? ' and Start >= timestamp(DATE_SUB(NOW(), INTERVAL 30 MINUTE))' : ' and Start like "%'.$Start.'%"';
        if ($both == '') {
            $sql = 'select a.*,b.Name from db_reservation.t_booking as a join db_employees.employees as b on a.CreatedBy = b.NIP where a.Status = ?
                     '.$Start;
            $query=$this->db->query($sql, array($Status))->result_array();         
        }
        else
        {
            $sql = 'select a.*,b.Name from db_reservation.t_booking as a join db_employees.employees as b on a.CreatedBy = b.NIP where a.Status like "%"
                     '.$Start;
            $query=$this->db->query($sql, array())->result_array();           
        }
        
        
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
                    'Status' => $query[$i]['Status'],
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