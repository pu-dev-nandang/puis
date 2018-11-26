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

    public function getCountAllDataAuth()
    {
        $sql = 'select count(*) as total from db_reservation.previleges_guser';
        $query=$this->db->query($sql, array())->result_array();
        return $query[0]['total'];
    }

    public function get_m_equipment_additional($available = '> 0')
    {
        $sql = 'select a.ID as ID_add,a.*,b.*,c.Division from db_reservation.m_equipment_additional as a join db_reservation.m_equipment as b
        on a.ID_m_equipment = b.ID join db_employees.division as c on a.Owner = c.ID where a.Qty '.$available;
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
        $this->load->model('m_api');
        $date2 = $date;
        $date2 = ($date2 == null) ? date('Y-m-d') : $date2;
        
        // cek academic years apakah periode ujian atau tidak
            $SemesterID = $this->m_master->caribasedprimary('db_academic.semester','Status',1);
            $SemesterID = $SemesterID[0]['ID'];

            $sqlWaktu = 'select * from db_academic.academic_years where SemesterID = ? and (utsStart <="'.$date2.'" and utsEnd >= "'.$date2.'")';
            $queryWaktu=$this->db->query($sqlWaktu, array($SemesterID))->result_array();

            $sqlWaktu2 = 'select * from db_academic.academic_years where SemesterID = ? and (uasStart <="'.$date2.'" and uasEnd >= "'.$date2.'")';
            $queryWaktu2=$this->db->query($sqlWaktu2, array($SemesterID))->result_array();
            if (count($queryWaktu) == 0) {
                if (count($queryWaktu2) > 0) {
                    $sql = "select a.ExamClassroomID,a.ID as ID_exam,a.ExamDate,a.ExamStart as StartSessions,a.ExamEnd as EndSessions,TIMEDIFF(CONCAT(curdate(),' ',a.ExamEnd), CONCAT(curdate(),' ',a.ExamStart)) as time,
                            b.Name as NamaDosen,c.ScheduleID,d.NameEng as NamaHari,e.Room,f.MKID,g.NameEng as NamaMataKuliah
                            from db_academic.exam as a
                            join db_employees.employees as b
                            on a.Pengawas1 = b.NIP
                            join db_academic.exam_details as c
                            on a.ID = c.ExamID
                            join db_academic.days as d
                            on d.ID = a.DayID
                            join db_academic.classroom as e
                            on e.ID = a.ExamClassroomID
                            join db_academic.schedule_details_course as f
                            on f.ScheduleID = c.ScheduleID
                            join db_academic.mata_kuliah as g
                            on g.ID = f.MKID
                            where d.NameEng = ?
                            and a.ExamDate = '".$date2."'
                            and a.`Status` = '1'
                            and a.SemesterID = '".$SemesterID."'
                            group by c.ScheduleID
                    ";
                }
                else
                {
                    $sql = "select a.Room,b.NameEng,c.StartSessions,c.EndSessions,TIMEDIFF(CONCAT(curdate(),' ',EndSessions), CONCAT(curdate(),' ',StartSessions)) as time,
                            e.Name as NamaDosen,g.NameEng as NamaMataKuliah,d.ID as ScheduleID
                            from db_academic.classroom as a join db_academic.schedule_details as c
                            on a.ID = c.ClassroomID
                            join db_academic.days as b
                            on c.DayID = b.ID
                            left join db_academic.schedule as d on d.ID = c.ScheduleID
                            left join db_employees.employees as e on d.Coordinator = e.NIP
                            left join (select * from db_academic.schedule_details_course group by ScheduleID) as f on f.ScheduleID = d.ID
                            left join db_academic.mata_kuliah as g on g.ID = f.MKID
                            where '".$date2."' >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                            and '".$date2."' <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
                            and b.NameEng = ?
                            and c.ID not in (select a.ScheduleID from db_academic.attendance as a join db_academic.schedule_exchange as b
                            on a.ID = b.ID_Attd where b.Status = '1' and b.DateOriginal = '".$date2."')
                            order by a.Room";  
                }

                
            }
            else
            {
                $sql = "select a.ExamClassroomID,a.ID as ID_exam,a.ExamDate,a.ExamStart as StartSessions,a.ExamEnd as EndSessions,TIMEDIFF(CONCAT(curdate(),' ',a.ExamEnd), CONCAT(curdate(),' ',a.ExamStart)) as time,
                        b.Name as NamaDosen,c.ScheduleID,d.NameEng as NamaHari,e.Room,f.MKID,g.NameEng as NamaMataKuliah
                        from db_academic.exam as a
                        join db_employees.employees as b
                        on a.Pengawas1 = b.NIP
                        join db_academic.exam_details as c
                        on a.ID = c.ExamID
                        join db_academic.days as d
                        on d.ID = a.DayID
                        join db_academic.classroom as e
                        on e.ID = a.ExamClassroomID
                        join db_academic.schedule_details_course as f
                        on f.ScheduleID = c.ScheduleID
                        join db_academic.mata_kuliah as g
                        on g.ID = f.MKID
                        where d.NameEng = ?
                        and a.ExamDate = '".$date2."'
                        and a.`Status` = '1'
                        and a.SemesterID = '".$SemesterID."'
                        group by c.ScheduleID
                ";
            }
   
        $query=$this->db->query($sql, array($NameDay))->result_array();
        // print_r($query);die(); 

        $date = ($date == null) ? '' : '  and DATE_FORMAT(a.`Start`,"%Y-%m-%d") = "'.$date.'"';
        $sql2 = 'select a.*,b.Name from db_reservation.t_booking as a
         join db_employees.employees as b on a.CreatedBy = b.NIP
         where a.Status in(0,1)  '.$date;
        $query2=$this->db->query($sql2, array())->result_array();

        if (count($queryWaktu) == 0 && count($queryWaktu2) == 0 ) {
           $sql3 = 'select a.Room,b.NameEng,c.StartSessions,c.EndSessions,TIMEDIFF(CONCAT(curdate()," ",EndSessions), CONCAT(curdate()," ",StartSessions)) as time,
                   f.Name as NamaDosen,h.NameEng as NamaMataKuliah,e.ID as ScheduleID 
                   from db_academic.classroom as a join db_academic.schedule_exchange as c
                   on a.ID = c.ClassroomID
                   join db_academic.days as b
                   on c.DayID = b.ID 
                   left join db_academic.attendance as d on d.ID = c.ID_Attd
                   left join db_academic.schedule as e on e.ID = d.ScheduleID
                   left join db_employees.employees as f on e.Coordinator = f.NIP
                   left join (select * from db_academic.schedule_details_course group by ScheduleID) as g on g.ScheduleID = e.ID
                   left join db_academic.mata_kuliah as h on h.ID = g.MKID
                   where c.Status = "1" and c.Date ="'.$date2.'"';
            $query3=$this->db->query($sql3, array())->result_array();       
        }
        else
        {
            $query3 = array();
        }


        

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

            // get jumlah Mahasiswa
            $arrMhs = $this->m_api->__getStudentByScheduleID($query[$i]['ScheduleID']);
            $jumlahMHS = count($arrMhs);

            $agenda = 'Study';
            if (count($queryWaktu) > 0 || count($queryWaktu2) > 0) {
                $agenda = 'Exam';
            }

            $dt = array(
                'user'  => 'Academic TimeTables',
                'start' => $start,
                'end'   => $end,
                'time'  => $time,
                'colspan' => $colspan,
                'agenda' => $agenda,
                'room' => $query[$i]['Room'],
                'approved' => 1,
                'NIP' => '0',
                'ID' => '0',
                'NamaMataKuliah' => $query[$i]['NamaMataKuliah'],
                'NamaDosen' => $query[$i]['NamaDosen'],
                'jumlahMHS' => $jumlahMHS
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

            // get jumlah Mahasiswa
            // $this->load->model('m_api');
            $arrMhs = $this->m_api->__getStudentByScheduleID($query3[$i]['ScheduleID']);
            $jumlahMHS = count($arrMhs);

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
                'NamaMataKuliah' => $query3[$i]['NamaMataKuliah'],
                'NamaDosen' => $query3[$i]['NamaDosen'],
                'jumlahMHS' => $jumlahMHS
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
        for ($xx=0; $xx < 1; $xx++) {  // check twice

            $TimeStart = date("H:i:s", strtotime($Start));
            $TimeEnd = date("H:i:s", strtotime($End));

            if ($chk_e_multiple == '') {
                // check academic timeline
                $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $Start);
                $NameDay = $datetime->format('l');
                $date2 = date("Y-m-d", strtotime($Start));

                // cek academic years apakah periode ujian atau tidak
                    $SemesterID = $this->m_master->caribasedprimary('db_academic.semester','Status',1);
                    $SemesterID = $SemesterID[0]['ID'];

                    $sqlWaktu = 'select * from db_academic.academic_years where SemesterID = ? and (utsStart <="'.$date2.'" and utsEnd >= "'.$date2.'")';
                    $queryWaktu=$this->db->query($sqlWaktu, array($SemesterID))->result_array();

                    $sqlWaktu2 = 'select * from db_academic.academic_years where SemesterID = ? and (uasStart <="'.$date2.'" and uasEnd >= "'.$date2.'")';
                    $queryWaktu2=$this->db->query($sqlWaktu2, array($SemesterID))->result_array();

                if (count($sqlWaktu) == 0) {
                    if (count($sqlWaktu2) > 0) {
                        $sql = "select count(*) as total from
                               (select a.ExamClassroomID,a.ID as ID_exam
                                from db_academic.exam as a
                                join db_employees.employees as b
                                on a.Pengawas1 = b.NIP
                                join db_academic.exam_details as c
                                on a.ID = c.ExamID
                                join db_academic.days as d
                                on d.ID = a.DayID
                                join db_academic.classroom as e
                                on e.ID = a.ExamClassroomID
                                join db_academic.schedule_details_course as f
                                on f.ScheduleID = c.ScheduleID
                                join db_academic.mata_kuliah as g
                                on g.ID = f.MKID
                                where d.NameEng = '".$NameDay."'
                                and a.ExamDate = '".$date2."'
                                and a.`Status` = '1'
                                and a.SemesterID = '".$SemesterID."'
                                and ((a.ExamStart >= '".$TimeStart."'  and a.ExamStart < '".$TimeEnd."' ) 
                                   or  (a.ExamEnd > '".$TimeStart."'  and a.ExamEnd <= '".$TimeEnd."' )
                                ) and e.Room  = '".$Room."' 
                                group by c.ScheduleID
                               )
                                aa
                        ";
                    }
                    else
                    {
                        $sql = 'select count(*) as total from db_academic.classroom as a join db_academic.schedule_details as c
                                on a.ID = c.ClassroomID
                                join db_academic.days as b
                                on c.DayID = b.ID
                                where "'.$date2.'" >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                                and "'.$date2.'" <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
                                and b.NameEng = "'.$NameDay.'" and ((c.StartSessions >= "'.$TimeStart.'" and c.StartSessions < "'.$TimeEnd.'" ) or (c.EndSessions > "'.$TimeStart.'" and c.EndSessions <= "'.$TimeEnd.'" )) and a.Room = "'.$Room.'" and c.ID not in (select a.ScheduleID from db_academic.attendance as a join db_academic.schedule_exchange as b
                        on a.ID = b.ID_Attd where b.Status = "1" and b.DateOriginal = "'.$date2.'")';
                    }
                }
                else
                {
                    $sql = "select count(*) as total from
                               (select a.ExamClassroomID,a.ID as ID_exam
                                from db_academic.exam as a
                                join db_employees.employees as b
                                on a.Pengawas1 = b.NIP
                                join db_academic.exam_details as c
                                on a.ID = c.ExamID
                                join db_academic.days as d
                                on d.ID = a.DayID
                                join db_academic.classroom as e
                                on e.ID = a.ExamClassroomID
                                join db_academic.schedule_details_course as f
                                on f.ScheduleID = c.ScheduleID
                                join db_academic.mata_kuliah as g
                                on g.ID = f.MKID
                                where d.NameEng = '".$NameDay."'
                                and a.ExamDate = '".$date2."'
                                and a.`Status` = '1'
                                and a.SemesterID = '".$SemesterID."'
                                and ((a.ExamStart >= '".$TimeStart."'  and a.ExamStart < '".$TimeEnd."' ) 
                                   or  (a.ExamEnd > '".$TimeStart."'  and a.ExamEnd <= '".$TimeEnd."' )
                                ) and e.Room  = '".$Room."' 
                                group by c.ScheduleID
                               )
                                aa
                        ";
                } // exit cek date academic
                // print_r($sql);die();

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
                // looping
                // check academic timeline untuk data satu
                $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $Start);
                $NameDay = $datetime->format('l');
                $date2 = date("Y-m-d", strtotime($Start));

                $sql = 'select count(*) as total from db_academic.classroom as a join db_academic.schedule_details as c
                        on a.ID = c.ClassroomID
                        join db_academic.days as b
                        on c.DayID = b.ID
                        where "'.$date2.'" >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                        and "'.$date2.'" <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
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
                            where "'.$getDate.'" >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                            and "'.$getDate.'" <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
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
            usleep( 500 );
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
                    $Owner = $get[0]['Owner'];
                    $getX = $this->m_master->caribasedprimary('db_employees.division','ID',$Owner);
                    $Owner = $getX[0]['Division'];

                    $Qty = $get[0]['Qty'];
                    $get = $this->m_master->caribasedprimary('db_reservation.m_equipment','ID',$ID_m_equipment);
                    $Name_equipment_add .= '<li>'.$get[0]['Equipment'].' by '.$Owner.'['.$Qty.']</li>';
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

            $MarkomSupport = '<label>No</Label>';
            if ($query[$i]['MarcommSupport'] != '') {
                $MarkomSupport = '<ul>';
                $dd = explode(',', $query[$i]['MarcommSupport']);
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

            $arr_result[] = array(
                    'Start' => $StartNameDay.', '.$query[$i]['Start'],
                    'End' => $EndNameDay.', '.$query[$i]['End'],
                    'Time' => $Time,
                    'Agenda' => $query[$i]['Agenda'],
                    'Room' => $query[$i]['Room'],
                    'Equipment_add' => $Name_equipment_add,
                    'Persone_add' => $Name_add_personel,
                    'Req_date' => $query[$i]['Name'].'<br>'.$ReqdateNameDay.', '.$query[$i]['Req_date'],
                    'Req_layout' => $query[$i]['Req_layout'],
                    'ID' => $query[$i]['ID'],
                    'Status' => $query[$i]['Status'],
                    'MarkomSupport' => $MarkomSupport
            );
        }

        return $arr_result;         
    }

    public function getDataT_booking($Start = null,$Status = 0,$both = '')
    {
        $arr_result = array();
        $this->load->model('master/m_master');
        $NIP = $this->session->userdata('NIP');
        // check Auth berdasarkan grouping user
            $add_where = '';
            // if ( !in_array($cd_akses, array(1,2,3,4,10)) ) {
            //      show_404($log_error = TRUE);
            // }

            // if ($this->session->userdata('ID_group_user') == 4) {
            //     $add_where = ' and a.CreatedBy = "'.$NIP.'"';
            // }

        $Start = ($Start == null) ? ' and Start >= timestamp(DATE_SUB(NOW(), INTERVAL 30 MINUTE))' : ' and Start like "%'.$Start.'%"';
        if ($both == '') {
            $sql = 'select a.*,b.Name from db_reservation.t_booking as a join db_employees.employees as b on a.CreatedBy = b.NIP where a.Status = ? '.$add_where.'
                     '.$Start.' order by a.Status asc,a.Start asc';
            $query=$this->db->query($sql, array($Status))->result_array();         
        }
        else
        {
            $sql = 'select a.*,b.Name from db_reservation.t_booking as a join db_employees.employees as b on a.CreatedBy = b.NIP where a.Status like "%" '.$add_where.'
                     '.$Start.' order by a.Status asc,a.Start asc';
            // print_r($sql);die();         
            $query=$this->db->query($sql, array())->result_array();           
        }
       
        for ($i=0; $i < count($query); $i++) { 
            $Startdatetime = DateTime::createFromFormat('Y-m-d H:i:s', $query[$i]['Start']);
            $Enddatetime = DateTime::createFromFormat('Y-m-d H:i:s', $query[$i]['End']);
            $StartNameDay = $Startdatetime->format('l');
            $EndNameDay = $Enddatetime->format('l');
            $Time = $query[$i]['Time'].' Minutes';

            $getRoom = $this->m_master->caribasedprimary('db_academic.classroom','Room',$query[$i]['Room']);
            // cek ApproveAccess
            $Status1 = $query[$i]['Status1'];
            $Status = $query[$i]['Status'];
            $MarcommStatus = $query[$i]['MarcommStatus'];
                $ApproveAccess = function($getRoom,$Status1,$Status,$CreatedBy,$MarcommStatus){
                    if ($MarcommStatus == 1) {
                        return $find = 0;
                    }

                    $PositionMain = $this->session->userdata('PositionMain');
                    $IDDivision = $PositionMain['IDDivision'];
                    $Position = $PositionMain['IDPosition'];
                    $NIP = $this->session->userdata('NIP');
                    // get Category Room to approver
                        $ApproveAccess = 0;
                        $ID_group_user = $this->session->userdata('ID_group_user');
                        $getPolicy = $this->m_master->caribasedprimary('db_reservation.cfg_policy','ID_group_user',$ID_group_user);
                        $CategoryRoom = $getPolicy[0]['CategoryRoom'];
                        $CategoryRoom = json_decode($CategoryRoom);
                        $CategoryRoomByRoom = $getRoom[0]['ID_CategoryRoom'];
                        $getDataCategoryRoom = $this->m_master->caribasedprimary('db_reservation.category_room','ID',$CategoryRoomByRoom);
                        // find access
                            $find = 1;
                                // for ($l=0; $l < count($CategoryRoom); $l++) { 
                                //     if ($CategoryRoomByRoom == $CategoryRoom[$l]) {
                                //         $find++;    
                                //         break;
                                //     }
                                // }

                                if ($find == 1) {
                                    // get status 
                                    if ($Status1 == 0) {
                                       // find approver1
                                           $Approver1 = $getDataCategoryRoom[0]['Approver1'];
                                           $Approver1 = json_decode($Approver1);

                                           // $NIP = $this->session->userdata('NIP');
                                           // for ($l=0; $l < count($Approver1); $l++) { 
                                           //     if ($NIP == $Approver1[$l]) {
                                           //         $find++;    
                                           //         break;
                                           //     }
                                           // } // old

                                           $dataApprover = array();
                                           // find by ID_group_user
                                                // $CreatedBy = $query[$i]['CreatedBy'];
                                                $cc = $this->m_master->caribasedprimary('db_reservation.previleges_guser','NIP',$CreatedBy);
                                                $dd = $this->m_master->caribasedprimary('db_employees.employees','NIP',$CreatedBy);
                                                $ID_group_user = (count($cc) > 0) ? $cc[0]['G_user'] : '';
                                            // stop loop
                                            $getLoop = true;    
                                           for ($l=0; $l < count($Approver1); $l++) {
                                                   if ($ID_group_user == $Approver1[$l]->UserType) {
                                                       // get TypeApprover
                                                       $TypeApprover = $Approver1[$l]->TypeApprover;
                                                       switch ($TypeApprover) {
                                                           case 'Position':
                                                               // get Division to access position approval
                                                                   $DivisionCreated = $dd[0]['PositionMain'];
                                                                   $DivisionCreated = explode(".", $DivisionCreated);

                                                                   $IDPositionApprover = $Approver1[$l]->Approver;

                                                                   if ($DivisionCreated[0] == 15) { // if prodi
                                                                       // find prodi
                                                                       $gg = $this->m_master->caribasedprimary('db_academic.program_study','AdminID',$CreatedBy);
                                                                       if (count($gg) > 0) {
                                                                           for ($k=0; $k < count($gg); $k++) { 
                                                                               $Kaprodi = $gg[$k]['KaprodiID'];
                                                                               if ($Kaprodi == $NIP) {
                                                                                   $find++;
                                                                                   $getLoop = false;     
                                                                                   break;
                                                                               }
                                                                           }
                                                                           
                                                                       }
                                                                    }
                                                                    else
                                                                    {
                                                                        // find by division and position
                                                                        if ($DivisionCreated[0] == $IDDivision) {
                                                                           // compare Position
                                                                           if ($IDPositionApprover == $Position) {
                                                                               $find++;
                                                                               $getLoop = false;    
                                                                               break;
                                                                           }
                                                                        }
                                                                    }
                                                               break;
                                                           
                                                           case 'Division':
                                                               if ($Approver1[$l]->Approver == $IDDivision) {
                                                                   $find++;
                                                                   $getLoop = false;    
                                                                   break;
                                                               }
                                                               break;

                                                           case 'Employees':
                                                               if ($NIP == $Approver1[$l]->Approver) {
                                                                   $find++;
                                                                   $getLoop = false;    
                                                                   break;
                                                               }
                                                               break;    
                                                       }
                                                   }

                                                   if (!$getLoop) { // stop loop
                                                       break;
                                                   }
                                           } // end loop for

                                    }
                                    else
                                    {
                                        $find = $find + 2;  
                                    }
                                }

                                if ($find == 3) {
                                   // find approver2
                                       $Approver2 = $getDataCategoryRoom[0]['Approver2'];
                                       $Approver2 = json_decode($Approver2);
                                       $DivisionID = $this->session->userdata('PositionMain');
                                       $DivisionID = $DivisionID['IDDivision'];
                                       if ($Status == 0) {
                                            for ($l=0; $l < count($Approver2); $l++) { 
                                                if ($DivisionID == $Approver2[$l]) {
                                                    $find++;    
                                                    break;
                                                }
                                            }
                                       }
                                       else
                                       {
                                        $find = $find + 2;
                                       }
                                       
                                }
                    $ApproveAccess = $find;
                    return $ApproveAccess;            
                };

                $StatusBooking = '';
                $CaseApproveAccess = $ApproveAccess($getRoom,$Status1,$Status,$query[$i]['CreatedBy'],$MarcommStatus);
                switch ($CaseApproveAccess) {
                    case 0:
                         $StatusBooking = 'Awaiting approval Marcomm Division';
                         break;
                    case 1:
                    case 2:
                        $StatusBooking = 'Awaiting approval 1';
                        break;
                    case 3:
                    case 4:
                        $StatusBooking = 'Awaiting approval 2';
                        break;
                    case 5:
                        $StatusBooking = 'Approved';
                        break;    
                    default:
                        # code...
                        break;
                }

            $ID_equipment_add = '-';
            $Name_equipment_add = '-';
            if ($query[$i]['ID_equipment_add'] != '' || $query[$i]['ID_equipment_add'] != null) {
                $ID_equipment_add = explode(',', $query[$i]['ID_equipment_add']);
                $Name_equipment_add = '<ul style = "margin-left : -28px">';
                $btnEquipment = '';
                for ($j=0; $j < count($ID_equipment_add); $j++) {
                    if ($this->session->userdata('ID_group_user') <= 3) {
                        if ($this->session->userdata('ID_group_user') != 3) {
                            $btnEquipment = '<button class = "btn btn-danger btnEquipment btn-xs" ID_equipment_add = "'.$ID_equipment_add[$j].'" ><i class="fa fa-times"></i> </button>';
                        }
                        else
                        {
                            if ($CaseApproveAccess == 2 || $CaseApproveAccess == 4) {
                                $btnEquipment = '<button class = "btn btn-danger btnEquipment btn-xs" ID_equipment_add = "'.$ID_equipment_add[$j].'" ><i class="fa fa-times"></i> </button>';
                            }
                        }
                        
                    }
                    else{
                        if ($CaseApproveAccess == 2 || $CaseApproveAccess == 4) {
                            $btnEquipment = '<button class = "btn btn-danger btnEquipment btn-xs" ID_equipment_add = "'.$ID_equipment_add[$j].'" ><i class="fa fa-times"></i> </button>';
                        }
                    }  
                    $get = $this->m_master->caribasedprimary('db_reservation.m_equipment_additional','ID',$ID_equipment_add[$j]);
                    $ID_m_equipment = $get[0]['ID_m_equipment'];
                    $Owner = $get[0]['Owner'];
                    $getX = $this->m_master->caribasedprimary('db_employees.division','ID',$Owner);
                    $Owner = $getX[0]['Division'];

                    $Qty = $get[0]['Qty'];
                    $get = $this->m_master->caribasedprimary('db_reservation.m_equipment','ID',$ID_m_equipment);
                    $Name_equipment_add .= '<li>'.$get[0]['Equipment'].' by '.$Owner.'['.$Qty.'] &nbsp'.$btnEquipment.'</li>';
                }
                $Name_equipment_add .= '</ul>';
            }

            $ID_add_personel = '-';
            $Name_add_personel = '-';

            if ($query[$i]['ID_add_personel'] != '' || $query[$i]['ID_add_personel'] != null) {
                $Name_add_personel = $query[$i]['ID_add_personel'];
            }

            $Reqdatetime = DateTime::createFromFormat('Y-m-d', $query[$i]['Req_date']);
            $ReqdateNameDay = $Reqdatetime->format('l');

            $MarkomSupport = '<label>No</Label>';
            if ($query[$i]['MarcommSupport'] != '') {
                $MarkomSupport = '<ul style = "margin-left : -28px">';
                $dd = explode(',', $query[$i]['MarcommSupport']);
                $btnMarkomSupport = '';
                for ($zx=0; $zx < count($dd); $zx++) {
                    $a = 'How are you?';
                    if ($this->session->userdata('ID_group_user') <= 3) {
                        if ($this->session->userdata('ID_group_user') != 3) {
                            $btnMarkomSupport = '<button class = "btn btn-danger btnMarkomSupport btn-xs" MarcommSupport = "'.$dd[$zx].'" ><i class="fa fa-times"></i> </button>';
                        }
                        else
                        {
                            if ($CaseApproveAccess == 2 || $CaseApproveAccess == 4) {
                                $btnMarkomSupport = '<button class = "btn btn-danger btnMarkomSupport btn-xs" MarcommSupport = "'.$dd[$zx].'" ><i class="fa fa-times"></i> </button>';
                            }
                        }
                        
                    }
                    else
                    {
                        // if ($CaseApproveAccess == 2 || $CaseApproveAccess == 4) {
                        //     $btnMarkomSupport = '<button class = "btn btn-danger btnMarkomSupport btn-xs" MarcommSupport = "'.$dd[$zx].'" ><i class="fa fa-times"></i> </button>';
                        // }

                        if ($CaseApproveAccess == 0) {
                            $PositionMain = $this->session->userdata('PositionMain');
                            $IDDivision = $PositionMain['IDDivision'];
                            if ($IDDivision == 17) {
                                 $btnMarkomSupport = '<button class = "btn btn-danger btnMarkomSupport btn-xs" MarcommSupport = "'.$dd[$zx].'" ><i class="fa fa-times"></i> </button>';
                            } 
                            
                        }
                    }

                    if (strpos($dd[$zx], 'Graphic Design') !== false) {
                         $pos = strpos($dd[$zx],'[');
                         $li = substr($dd[$zx], 0,$pos);
                         $posE = strpos($dd[$zx],']');
                         $ISIe = substr($dd[$zx], ($pos+1), $posE);
                         $length = strlen($ISIe);
                         $ISIe = substr($ISIe, 0, ($length - 1));
                         // print_r($ISIe);die();
                         $MarkomSupport .= '<li>'.$li.'&nbsp'.$btnMarkomSupport;
                         $FileMarkom = explode(';', $ISIe);
                         $MarkomSupport .= '<ul style = "margin-left : -28px">';
                         for ($vc=0; $vc < count($FileMarkom); $vc++) { 
                            $MarkomSupport .= '<li>'.'<a href="'.base_url("fileGetAny/vreservation-".$FileMarkom[$vc]).'" target="_blank"></i>'.$FileMarkom[$vc].'</a>';
                         }
                         $MarkomSupport .= '</ul></li>';
                    } 
                    else{
                        if (strpos($dd[$zx], 'Note') !== false) {
                            $pos = strpos($dd[$zx],':');
                            $dd[$zx] = substr($dd[$zx], 0,$pos+1).'<br>'.substr($dd[$zx], $pos+1,strlen($dd[$zx]));
                        }
                      $MarkomSupport .= '<li>'.$dd[$zx].'&nbsp'.$btnMarkomSupport.'</li>';  
                    }
                    
                }
                $MarkomSupport .= '</ul>';
            }

            $KetAdditional = $query[$i]['KetAdditional'];
            $KetAdditional = json_decode($KetAdditional);
            $Participant = '<ul><li>Participant Qty : '.$query[$i]['ParticipantQty'].'</li>';
            if (count($KetAdditional) > 0) {
                foreach ($KetAdditional as $key => $value) {
                    if ($value != "" || $value != null) {
                        $Participant .= '<li>'.str_replace("_", " ", $key).' : '.$value.'</li>';
                    }
                }
            }
            $Participant .= '</ul>';
                
            $arr_result[] = array(
                    'Start' => $StartNameDay.', '.$query[$i]['Start'],
                    'End' => $EndNameDay.', '.$query[$i]['End'],
                    'Time' => $Time,
                    'Agenda' => $query[$i]['Agenda'],
                    'Room' => $query[$i]['Room'],
                    'Equipment_add' => $Name_equipment_add,
                    'Persone_add' => $Name_add_personel,
                    'Req_date' => $query[$i]['Name'].'<br>'.$ReqdateNameDay.', '.$query[$i]['Req_date'],
                    'Req_layout' => $query[$i]['Req_layout'],
                    'ID' => $query[$i]['ID'],
                    'Status' => $query[$i]['Status'],
                    'MarkomSupport' => $MarkomSupport,
                    'Participant' => $Participant,
                    'ApproveAccess' => $CaseApproveAccess,
                    'StatusBooking' => $StatusBooking,
            );
        }

        return $arr_result;         
    }

    public function get_m_room_equipment($room)
    {
        $sql = 'select a.Room,a.qty,b.Equipment,a.Note from db_reservation.m_room_equipment as a join db_reservation.m_equipment as b on a.ID_m_equipment = b.ID and a.Room = ?';
        $query=$this->db->query($sql, array($room))->result_array();
        return $query;
    }

    public function get_m_room_equipment_all()
    {
        $sql = 'select a.ID,a.Room,a.qty,b.Equipment,c.Name as NameCreated,a.CreatedAt, d.Name as NameUpdated,a.UpdatedAt,a.Note from db_reservation.m_room_equipment as a join db_reservation.m_equipment as b on a.ID_m_equipment = b.ID join db_employees.employees as c on a.CreatedBy = c.NIP left join db_employees.employees as d on a.UpdatedBy = d.NIP';
        $query=$this->db->query($sql, array())->result_array();
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
                        where "'.$date2.'" >= (select z.kuliahStart from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1) 
                        and "'.$date2.'" <= (select z.kuliahEnd from db_academic.academic_years as z,db_academic.semester as x where z.SemesterID = x.ID and x.Status = 1 LIMIT 1)
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

    public function getDataWithoutSuperAdmin()
    {
        $sql = 'select * from db_reservation.cfg_group_user where ID != 1';
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function getDataWithoutSuperAdmin2($ID)
    {
        $sql = 'select * from db_reservation.cfg_group_user where ID >= "'.$ID.'"';
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function chkAuthDB_Base_URL_vreservation($URL)
    {
        $a = explode('/', $URL);
        $b = count($a) - 1;
        $URISlug = 'and a.Slug = "'.$URL.'"';
        if ($a[$b] == 1) {
            $URISlug = '';
            for ($i=0; $i < count($b); $i++) { 
                $URISlug .= $a[$i].'/';
            }
            $URISlug = 'and a.Slug like "%'.$URISlug.'%"';
        }
        $sql = "select b.read,b.write,b.update,b.delete from db_reservation.cfg_sub_menu as a join db_reservation.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
        join db_reservation.previleges_guser as c on c.G_user = b.cfg_group_user
        where c.NIP = ? ".$URISlug;
        $query=$this->db->query($sql, array($this->session->userdata('NIP')))->result_array();
        // print_r($query);die();
        return $query;
    }

    public function checkAuth_user_vreservation()
    {
        $base_url = base_url();
        $currentURL = current_url();
        $URL = str_replace($base_url,"",$currentURL);
        
        // get Access URL
        $getDataSess  = $this->session->userdata('menu_vreservation_grouping');
        $access = array(
            'read' => 0,
            'write' => 0,
            'update' => 0,
            'delete' => 0,
        );

        $p = $this->chkAuthDB_Base_URL_vreservation($URL);
        if (count($p) > 0 ) {
            $access = array(
                'read' => $p[0]['read'],
                'write' => $p[0]['write'],
                'update' => $p[0]['update'],
                'delete' => $p[0]['delete'],
            );
        }

        // print_r($access);die();

        $html = '';
        if ($access['read'] == 0) {
            $html .= '<script type="text/javascript">
                 var waitForEl = function(selector, callback) {
                   if (jQuery(selector).length) {
                     callback();
                   } else {
                     setTimeout(function() {
                       waitForEl(selector, callback);
                     }, 100);
                   }
                 };

                 waitForEl(".btn-read", function() {
                   $(".btn-read").remove();
                 });

                 $(document).ready(function () {
                     $(".btn-read").remove();
                     //window.location.href = base_url_js+"vreservation/dashboard/view";
                     $(document).ajaxComplete(function () {
                         $(".btn-read").remove();
                     });
                 });
                 </script>
            ';
            echo $html;
        }

        if ($access['write'] == 0) {
            $html .= '<script type="text/javascript">
                 var waitForEl = function(selector, callback) {
                   if (jQuery(selector).length) {
                     callback();
                   } else {
                     setTimeout(function() {
                       waitForEl(selector, callback);
                     }, 100);
                   }
                 };

                 waitForEl(".btn-add", function() {
                   $(".btn-add").remove();
                 });

                 $(document).ready(function () {
                     $(".btn-add").remove();
                     $(document).ajaxComplete(function () {
                        $(".btn-add").remove();
                     });
                 });
                 </script>
            ';
            echo $html;
        }
        if ($access['update'] == 0) {
            $html .= '<script type="text/javascript">
                 var waitForEl = function(selector, callback) {
                   if (jQuery(selector).length) {
                     callback();
                   } else {
                     setTimeout(function() {
                       waitForEl(selector, callback);
                     }, 100);
                   }
                 };

                 waitForEl(".btn-edit", function() {
                   $(".btn-edit").remove();
                 });

                 $(document).ready(function () {
                     $(".btn-edit").remove();
                     $(document).ajaxComplete(function () {
                              $(".btn-edit").remove();
                     });
                 });
                 </script>
            ';
            echo $html;
        }
        if ($access['delete'] == 0) {
            $html .= '<script type="text/javascript">
                 var waitForEl = function(selector, callback) {
                   if (jQuery(selector).length) {
                     callback();
                   } else {
                     setTimeout(function() {
                       waitForEl(selector, callback);
                     }, 100);
                   }
                 };

                 waitForEl(".btn-delete", function() {
                   $(".btn-delete").remove();
                 });

                 waitForEl(".btn-Active", function() {
                   $(".btn-Active").remove();
                 });

                 $(document).ready(function () {
                    $(".btn-delete").remove();
                    $(".btn-Active").remove();
                    $(document).ajaxComplete(function () {
                        $(".btn-delete").remove();
                        $(".btn-Active").remove();
                    });
                     
                 });
                 
                 </script>
            ';
            echo $html;
        }


        // special menu & group
        $bool = true;
        foreach ($access as $key => $value) {
            if ($value == 0) {
                $bool = false;
                break;
            }
        }

        if (!$bool) {
            $html .= '<script type="text/javascript">
                 var waitForEl = function(selector, callback) {
                   if (jQuery(selector).length) {
                     callback();
                   } else {
                     setTimeout(function() {
                       waitForEl(selector, callback);
                     }, 100);
                   }
                 };

                 waitForEl(".btn-delete-menu-auth", function() {
                    $(".btn-delete-menu-auth").remove();
                 });

                 waitForEl(".btn-edit-menu-auth", function() {
                   $(".btn-edit-menu-auth").remove();
                 });

                 waitForEl(".btn-edit-menu-auth", function() {
                   $(".btn-edit-menu-auth").remove();
                 })

                 waitForEl(".btn-add-menu-auth", function() {
                   $(".btn-add-menu-auth").remove();
                 });

                 waitForEl(".btn-delete-menu-auth", function() {
                   $(".btn-delete-menu-auth").remove();
                 });
                 
                 </script>
            ';
            echo $html;
        }

        return $html;
    }

    public function get__m_additional_personel()
    {
        $sql = 'select b.Division,a.ID as ID_m_additional_personel, b.ID as ID_division from db_reservation.m_additional_personel as a
        join db_employees.division as b on a.ID_division = b.ID
        ';
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function get_JSonEquipment_additional()
    {
        $sql = 'select c.ID,a.Equipment,b.ID as ID_division,b.Division,c.Qty,c.ID_m_equipment,c.CreatedBy,c.CreatedAt,c.UpdatedBy,c.UpdatedAt,d.Name as NameCreated,
                e.Name as NameUpdated
                from db_reservation.m_equipment as a join db_reservation.m_equipment_additional as c on a.ID = c.ID_m_equipment
                join db_employees.division as b on c.`Owner` = b.ID join db_employees.employees as d on d.NIP = c.CreatedBy left join db_employees.employees as e 
                on e.NIP = c.UpdatedBy
        ';
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function save_sess_policy_grouping()
    {
        $NIP = $this->session->userdata('NIP');
        $sql = 'select a.* from db_reservation.cfg_policy as a join db_reservation.cfg_group_user as b on a.ID_group_user = b.ID join db_reservation.previleges_guser as c 
                on b.ID = c.G_user where c.NIP = ? limit 1';
        $query=$this->db->query($sql, array($NIP))->result_array();
        $arr = array(
            'V_BookingDay' => 1,
            'ID_group_user' => 4,
        );

        if (count($query) > 0) {
            $arr = array(
                'V_BookingDay' => $query[0]['BookingDay'],
                'ID_group_user' => $query[0]['ID_group_user'],
            );
        }

        $this->session->set_userdata($arr);
    }

    public function OpCategorybyIN($CategoryRoom)
    {
        $sql = 'select * from db_reservation.category_room where ID in ('.$CategoryRoom.')';
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }
}