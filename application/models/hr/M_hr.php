<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hr extends CI_Model {


    public function checkPermanentDelete($NIP){

        $res = 0;

        // Cek apakah berelasi dengan jadwal
        $dataJ = $this->db->query('SELECT s.ID FROM db_academic.schedule s 
                                               LEFT JOIN db_academic.schedule_team_teaching stt 
                                               ON (stt.ScheduleID = s.ID)
                                               WHERE s.Coordinator = "'.$NIP.'" 
                                               OR stt.NIP = "'.$NIP.'" ')->result_array();

        $dataJlama = $this->db->query('SELECT s.ID FROM db_academic.z_schedule s
                                            LEFT JOIN db_academic.z_team_teaching ztt 
                                            ON (ztt.ScheduleID = s.ID)
                                            WHERE s.NIP = "'.$NIP.'" 
                                            OR ztt.NIP = "'.$NIP.'" ')->result_array();



        // Cek apakah dia mempunyai jabatan
        $dataKa = $this->db->query('SELECT ID FROM db_academic.program_study 
                                      WHERE KaprodiID = "'.$NIP.'" ')->result_array();

        // Cek apakah dia mempunyai mahasiswa bimbingan
        $dataMentor = $this->db->query('SELECT ID FROM db_academic.mentor_academic 
                                          WHERE NIP = "'.$NIP.'"')->result_array();
        if(count($dataJ)<=0 && count($dataJlama)<=0 && count($dataKa)<=0 && count($dataMentor)<=0){
            $res = array(
                'Status' => '0',
                'Msg' => 'Abel to delete permanent'
            );
        } else {
            if(count($dataJ)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist on schedule'
                );
            }
            else if(count($dataJlama)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist on schedule in Old System (SIAK LAMA)'
                );
            }
            else if(count($dataKa)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist AS -Kaprodi- '
                );
            }
            else if(count($dataMentor)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist as mentor (Dosen PA)'
                );
            } else {
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee EMPTY'
                );
            }
        }

        return $res;

    }

    public function getLecPartime(){
        $data = $this->db->query('SELECT * FROM db_employees.employees em
                                          WHERE em.StatusEmployeeID != -2 AND em.StatusEmployeeID = 4 ')->result_array();

        return $data;
    }

    /*ADDED BY FEBRI @ FEB 2020*/
    public function getMemberSTO($data){
        $this->db->select("a.ID as CareerID,a.StartJoin,a.EndJoin,a.LevelID,a.DepartmentID,a.PositionID,a.JobTitle,a.Superior,a.StatusID,a.Remarks, e.*");
        $this->db->from("db_employees.employees_career a");
        $this->db->join("db_employees.employees_career b","b.NIP = a.NIP and b.PositionID = a.PositionID and a.StartJoin > b.StartJoin","left");
        $this->db->join("db_employees.employees_career c","a.NIP = c.NIP and a.PositionID = c.PositionID and a.StartJoin > c.StartJoin and b.StartJoin > c.StartJoin","LEFT OUTER");
        $this->db->join("db_employees.sto_temp d","a.PositionID = d.ID","left");
        $this->db->join("db_employees.employees e","e.NIP = a.NIP","left");
        $this->db->where($data);
        $this->db->group_by("a.NIP");
        $query = $this->db->get();

        return $query;
    }

    public function getTitleSTO($keyword=null){
        $query = "select a.Position as Name, a.Description from db_employees.position a where a.Description like '%".$keyword."%' or a.Position like '%".$keyword."%'
                  union 
                  select b.Division as Name, b.Description from db_employees.division b where b.Description like '%".$keyword."%' or b.Division like '%".$keyword."%' ";
        $result = $this->db->query($query);
        return $result;
    }

    public function getEmpCareer($data){
        $this->db->select("a.*, b.name as LevelName, c.title as DepartmentName, d.title as PositionName");
        $this->db->from("db_employees.employees_career a");
        $this->db->join("db_employees.master_level b","b.ID=a.LevelID","left");
        $this->db->join("db_employees.sto_temp c","c.ID=a.DepartmentID","left");
        $this->db->join("db_employees.sto_temp d","d.ID=a.PositionID","left");
        $this->db->where($data);
        $this->db->order_by("a.StartJoin, a.EndJoin","desc");
        $query = $this->db->get();
        return $query;
    }



    public function fetchEmployee($count=false,$param='',$start='',$limit='',$order=''){
        $where='';$where2='';$startDate = date("Y-m-d");
        if(!empty($param)){
            $where = 'WHERE '; $conditionDate = '';
            $counter = 0; $notExistquery = '';
            foreach ($param as $key => $value) {
                if($value['field'] == "lem.AccessedOn"){
                    //$startDate = preg_replace("/'/", '', $value['data']);
                    $value['field'] = "DATE(".$value['field'].")";
                    $value['data'] = $value['data'];
                    //$conditionDate = " and "."DATE(a.AccessedOn)" ." ".$value['data'];
                }
                if(!empty($value['subquery'])){
                    $notExistquery = $value['subquery'];
                }

                if($counter==0){
                    if(strpos($value['field'], 'lem.AccessedOn') !== false){
                        $where2 = $where.$value['field']." ".$value['data'];
                    }else{
                        $where = $where.$value['field']." ".$value['data'];                        
                    }
                }else{ 
                    if(strpos($value['field'], 'lem.AccessedOn') !== false){
                        $where2 = $where.$value['filter']." ".$value['field']." ".$value['data'];
                    }else{
                        $where = $where.$value['filter']." ".$value['field']." ".$value['data'];
                    }
                }

                $counter++;
            }
        }
        //echo $where2;die();
        if($where == 'WHERE '){
            $where = '';
        }
        $lims="";
        if($start!="" || $limit!=""){
            $lims = " LIMIT {$start},{$limit}"; 
        }

        $query1 = "SELECT em.*
                    ,d.Division as DivisionMain_, p.Position as PositionMain_
                    ,concat(d1.Division,'-',p1.Position ) as PositionOther1Name
                    ,concat(d2.Division,'-',p2.Position ) as PositionOther2Name
                    ,concat(d3.Division,'-',p3.Position ) as PositionOther3Name
                    ,DATE_FORMAT(lem.AccessedOn, '%d-%M-%Y %H:%i:%s') as FirstLoginPortal 
                    ,DAYNAME(lem.AccessedOn) as FirstLoginPortalDay 
                    ,WEEKDAY(lem.AccessedOn) as FirstLoginPortalDayNum 

                    FROM db_employees.employees em 
                    left JOIN db_employees.log_employees lem on (em.NIP = lem.NIP) 

                    LEFT JOIN db_employees.division d on (d.ID = SUBSTRING_INDEX(em.PositionMain,'.',1) ) 
                    LEFT JOIN db_employees.position p on (p.ID = SUBSTRING_INDEX(em.PositionMain,'.',-1) ) 

                    LEFT JOIN db_employees.division d1 on (d1.ID = SUBSTRING_INDEX(em.PositionOther1,'.',1) ) 
                    LEFT JOIN db_employees.position p1 on (p1.ID = SUBSTRING_INDEX(em.PositionOther1,'.',-1) ) 

                    LEFT JOIN db_employees.division d2 on (d2.ID = SUBSTRING_INDEX(em.PositionOther2,'.',1) ) 
                    LEFT JOIN db_employees.position p2 on (p2.ID = SUBSTRING_INDEX(em.PositionOther2,'.',-1) ) 

                    LEFT JOIN db_employees.division d3 on (d3.ID = SUBSTRING_INDEX(em.PositionOther3,'.',1) ) 
                    LEFT JOIN db_employees.position p3 on (p3.ID = SUBSTRING_INDEX(em.PositionOther3,'.',-1) ) 

                    {$where2} 
                    GROUP BY em.NIP, DATE(lem.AccessedOn) order by FirstLoginPortal asc ";
        $query2 = "select em.*
                    ,d.Division as DivisionMain_, p.Position as PositionMain_
                    ,concat(d1.Division,'-',p1.Position ) as PositionOther1Name
                    ,concat(d2.Division,'-',p2.Position ) as PositionOther2Name
                    ,concat(d3.Division,'-',p3.Position ) as PositionOther3Name
                    ,null as FirstLoginPortal
                    ,null as  FirstLoginPortalDay
                    ,null as FirstLoginPortalDayNum 

                    from db_employees.employees em

                    LEFT JOIN db_employees.division d on (d.ID = SUBSTRING_INDEX(em.PositionMain,'.',1) ) 
                    LEFT JOIN db_employees.position p on (p.ID = SUBSTRING_INDEX(em.PositionMain,'.',-1) ) 

                    LEFT JOIN db_employees.division d1 on (d1.ID = SUBSTRING_INDEX(em.PositionOther1,'.',1) ) 
                    LEFT JOIN db_employees.position p1 on (p1.ID = SUBSTRING_INDEX(em.PositionOther1,'.',-1) ) 

                    LEFT JOIN db_employees.division d2 on (d2.ID = SUBSTRING_INDEX(em.PositionOther2,'.',1) ) 
                    LEFT JOIN db_employees.position p2 on (p2.ID = SUBSTRING_INDEX(em.PositionOther2,'.',-1) ) 

                    LEFT JOIN db_employees.division d3 on (d3.ID = SUBSTRING_INDEX(em.PositionOther3,'.',1) ) 
                    LEFT JOIN db_employees.position p3 on (p3.ID = SUBSTRING_INDEX(em.PositionOther3,'.',-1) ) 
                    {$where} AND {$notExistquery} 

                    ";

        $groupby = '';
        $sorted = '';
        $order_by = '';
        if($count){
            //$select = "count(DISTINCT em.NIP, DATE(lem.AccessedOn) ) as Total";
            $select = "COUNT(*) as Total";
            /*$query1 = "SELECT em.*
                        FROM db_employees.employees em 
                        left JOIN db_employees.log_employees lem on (em.NIP = lem.NIP) 
                        {$where2}
                        GROUP BY em.NIP, DATE(lem.AccessedOn)";
            $query2 = "select em.*
                        from db_employees.employees em
                        {$where}";*/
        }else{
            $select = "*";
            
            $groupby = "GROUP BY NIP,FirstLoginPortal";
            $order_by = 'order by FirstLoginPortal desc';
            /*
            $select = "em.*
                        ,d.Division as DivisionMain_, p.Position as PositionMain_
                        ,concat(d1.Division,'-',p1.Position ) as PositionOther1
                        ,concat(d2.Division,'-',p2.Position ) as PositionOther2
                        ,concat(d3.Division,'-',p3.Position ) as PositionOther3
                        , DATE_FORMAT(lem.AccessedOn, '%d-%M-%Y %H:%i:%s') as FirstLoginPortal
                        ,DAYNAME(lem.AccessedOn) as FirstLoginPortalDay
                        ,WEEKDAY(lem.AccessedOn) as FirstLoginPortalDayNum ";
            $groupby = 'GROUP BY em.NIP, DATE(lem.AccessedOn)';
            $sorted = " order by ".(!empty($order) ? $order : 'FirstLoginPortal asc');
            */
        }
        
        
        /*
        $string = "SELECT {$select}
                   FROM db_employees.log_employees lem
                   LEFT JOIN db_employees.employees em on (em.NIP = lem.NIP)
                   
                    LEFT JOIN db_employees.division d on (d.ID = SUBSTRING_INDEX(em.PositionMain,'.',1) ) 
                    LEFT JOIN db_employees.position p on (p.ID = SUBSTRING_INDEX(em.PositionMain,'.',-1) ) 

                    LEFT JOIN db_employees.division d1 on (d1.ID = SUBSTRING_INDEX(em.PositionOther1,'.',1) ) 
                    LEFT JOIN db_employees.position p1 on (p1.ID = SUBSTRING_INDEX(em.PositionOther1,'.',-1) ) 

                    LEFT JOIN db_employees.division d2 on (d2.ID = SUBSTRING_INDEX(em.PositionOther2,'.',1) ) 
                    LEFT JOIN db_employees.position p2 on (p2.ID = SUBSTRING_INDEX(em.PositionOther2,'.',-1) ) 

                    LEFT JOIN db_employees.division d3 on (d3.ID = SUBSTRING_INDEX(em.PositionOther3,'.',1) ) 
                    LEFT JOIN db_employees.position p3 on (p3.ID = SUBSTRING_INDEX(em.PositionOther3,'.',-1) ) 
                    
                    {$where} {$groupby} {$sorted} {$lims} ";
        */


        $string = "select {$select} 
                   from (
                    ( {$query1} )

                    union all

                    ( {$query2} )

                    ) as Attendance
                    {$groupby} {$order_by} {$lims}";
        $value  = $this->db->query($string);
        //var_dump($this->db->last_query());
        return $value;
    }


    public function fetchLecturer($count=false,$param='',$start='',$limit='',$order=''){
        $where='';$where2='';$startDate = date("Y-m-d");
        if(!empty($param)){
            $where = 'WHERE '; $conditionDate = '';
            $counter = 0; $notExistquery = '';
            foreach ($param as $key => $value) {
                if($value['field'] == "lem.AccessedOn"){
                    //$startDate = preg_replace("/'/", '', $value['data']);
                    $value['field'] = "DATE(".$value['field'].")";
                    $value['data'] = $value['data'];
                    //$conditionDate = " and "."DATE(a.AccessedOn)" ." ".$value['data'];
                }
                if(!empty($value['subquery'])){
                    $notExistquery = $value['subquery'];
                }

                if($counter==0){
                    if(strpos($value['field'], 'lem.AccessedOn') !== false){
                        $where2 = $where.$value['field']." ".$value['data'];
                    }else{
                        $where = $where.$value['field']." ".$value['data'];                        
                    }
                }else{ 
                    if(strpos($value['field'], 'lem.AccessedOn') !== false){
                        $where2 = $where.$value['filter']." ".$value['field']." ".$value['data'];
                    }else{
                        $where = $where.$value['filter']." ".$value['field']." ".$value['data'];
                    }
                }

                $counter++;
            }
        }
        //echo $where2;die();
        if($where == 'WHERE '){
            $where = '';
        }
        $lims="";
        if($start!="" || $limit!=""){
            $lims = " LIMIT {$start},{$limit}"; 
        }

        $query1 = "SELECT em.*
                    , ps.ID as ProdiID_, ps.CodeID as ProdiCodeID, ps.NameEng as ProdiName, ps.`Code` as ProdiCode 
                    ,DATE_FORMAT(lem.AccessedOn,'%d-%M-%Y %H:%i:%s') as FirstLoginPortal 
                    ,DAYNAME(lem.AccessedOn) as FirstLoginPortalDay 
                    ,WEEKDAY(lem.AccessedOn) as FirstLoginPortalDayNum 
                    FROM db_employees.employees em 
                    left JOIN db_employees.log_lecturers lem on (em.NIP = lem.NIP) 
                    LEFT JOIN db_academic.program_study ps ON (ps.ID = em.ProdiID) 
                    {$where2} 
                    GROUP BY em.NIP, DATE(lem.AccessedOn) order by FirstLoginPortal asc ";
        $query2 = "select em.*
                    ,ps.ID as ProdiID_, ps.CodeID as ProdiCodeID, ps.NameEng as ProdiName, ps.`Code` as ProdiCode 
                    ,null as FirstLoginPortal
                    ,null as FirstLoginPortalDay 
                    ,null as FirstLoginPortalDayNum 
                    from db_employees.employees em
                    LEFT JOIN db_academic.program_study ps ON (ps.ID = em.ProdiID)
                    {$where} AND {$notExistquery} 
                    ";

        $groupby = '';
        $sorted = '';
        $order_by = '';
        if($count){
            $select = "COUNT(*) as Total";
        }else{
            $select = "*";
            $groupby = "GROUP BY NIP,FirstLoginPortal";
            $order_by = 'order by NIP,FirstLoginPortal desc';
        }
        
        $string = "select {$select} 
                   from (
                    ( {$query1} )
                    union all
                    ( {$query2} )
                    ) as Attendance
                    {$groupby} {$order_by} {$lims}";
        $value  = $this->db->query($string);
        //var_dump($this->db->last_query());
        return $value;
    }


    public function fetchMemberOFDepartpent($data){
        $this->db->select('em.*,d.Division as DivisionMainName, p.Position as PositionMainName
                            ,( case when (em.PositionOther1 is null or em.PositionOther1 = "") then "" else ( concat(d1.Division,"-",p1.Position ) ) end ) as PositionOtherName1
                            ,( case when (em.PositionOther2 is null or em.PositionOther2 = "") then "" else ( concat(d2.Division,"-",p2.Position ) ) end ) as PositionOtherName2
                            ,( case when (em.PositionOther3 is null or em.PositionOther3 = "") then "" else ( concat(d3.Division,"-",p3.Position ) ) end ) as PositionOtherName3 ');
        $this->db->from('db_employees.employees em');
        $this->db->join('db_employees.division d','(d.ID = SUBSTRING_INDEX(em.PositionMain,".",1) )','left');
        $this->db->join('db_employees.position p','(p.ID = SUBSTRING_INDEX(em.PositionMain,".",-1) )','left');
        
        $this->db->join('db_employees.division d1','(d1.ID = SUBSTRING_INDEX(em.PositionOther1,".",1) )','left');
        $this->db->join('db_employees.position p1','(p1.ID = SUBSTRING_INDEX(em.PositionOther1,".",-1) )','left');
        
        $this->db->join('db_employees.division d2','(d2.ID = SUBSTRING_INDEX(em.PositionOther2,".",1) )','left');
        $this->db->join('db_employees.position p2','(p2.ID = SUBSTRING_INDEX(em.PositionOther2,".",-1) )','left');
        
        $this->db->join('db_employees.division d3','(d3.ID = SUBSTRING_INDEX(em.PositionOther3,".",1) )','left');
        $this->db->join('db_employees.position p3','(p3.ID = SUBSTRING_INDEX(em.PositionOther3,".",-1) )','left');
        $this->db->where($data);
        $this->db->order_by('em.PositionMain','asc');
        $query = $this->db->get();
        return $query;
    }
    /*END ADDED BY FEBRI @ FEB 2020*/

    public function getDataRecapitulation($data_arr){

        $SemesterID = $data_arr['SemesterID'];
        $ProdiID = ($data_arr['ProdiID']!='' && $data_arr['ProdiID']!=null && isset($data_arr['ProdiID']))
            ? ' AND em.ProdiID = "'.$data_arr['ProdiID'].'" ' : '';
        $StatusLecturerID = $data_arr['StatusLecturerID'];

        $dateStart = $data_arr['RangeStart'];
        $dateEnd = $data_arr['RangeEnd'];



        $data = $this->db->query('SELECT em.NIP, em.Name, ps.Code AS ProdiCode FROM db_employees.employees em 
                                           LEFT JOIN db_academic.program_study ps ON (ps.ID = em.ProdiID)
                                           WHERE em.StatusLecturerID = "'.$StatusLecturerID.'" 
                                             '.$ProdiID.' ORDER BY em.ProdiID, em.NIP ASC ' )->result_array();

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $NIP = $data[$i]['NIP'];
                $arrID_Attd = [];

                $dataSchedule = $this->db->query('SELECT s.ID AS ScheduleID, s.Coordinator AS NIP, mk.NameEng AS Course, cd.TotalSKS AS Credit, csl.Money AS Fee_SKS,   
                                                    csl.Allowance AS Fee_Tunjangan, csl.Allowance_NIDN AS Fee_NIDN
                                                    FROM db_academic.schedule s
                                                    LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                                    LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                    LEFT JOIN db_employees.credit_salary_lecturer csl ON (csl.NIP = s.Coordinator AND csl.SemesterID = s.SemesterID)
                                                    WHERE s.SemesterID = "'.$SemesterID.'" AND s.Coordinator = "'.$NIP.'" GROUP BY s.ID
                                                    UNION ALL 
                                                    SELECT s.ID AS ScheduleID, stt.NIP, mk.NameEng AS Course, cd.TotalSKS AS Credit, csl.Money AS Fee_SKS, 
                                                    csl.Allowance AS Fee_Tunjangan, csl.Allowance_NIDN AS Fee_NIDN 
                                                    FROM db_academic.schedule_team_teaching stt 
                                                    LEFT JOIN db_academic.schedule s ON (s.ID = stt.ScheduleID) 
                                                    LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                                    LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                    LEFT JOIN db_employees.credit_salary_lecturer csl ON (csl.NIP = stt.NIP AND csl.SemesterID = s.SemesterID)
                                                    WHERE s.SemesterID = "'.$SemesterID.'" AND stt.NIP = "'.$NIP.'"  GROUP BY s.ID')->result_array();

                // Get Schedule Details dan ID Attd
                if(count($dataSchedule)>0){
                    for($s=0;$s<count($dataSchedule);$s++){

                        // Get total dosen
                        $dataTotalDosen = $this->db->query('SELECT s.Coordinator AS NIP, em.Name FROM db_academic.schedule s 
                                                                    LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                                    WHERE s.ID = "'.$dataSchedule[$s]['ScheduleID'].'"
                                                                     UNION ALL 
                                                                     SELECT stt.NIP, em.Name 
                                                                     FROM db_academic.schedule_team_teaching stt 
                                                                      LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                                      WHERE stt.ScheduleID = "'.$dataSchedule[$s]['ScheduleID'].'"')->result_array();
                        $dataSchedule[$s]['TotalLecturer'] = count($dataTotalDosen);
                        $dataSchedule[$s]['TotalLecturer_Details'] = $dataTotalDosen;


                        $dataAttdID = $this->db->query('SELECT sd.ScheduleID, sd.ID AS SDID, attd.ID AS ID_Attd FROM db_academic.schedule_details sd 
                                                                        LEFT JOIN db_academic.attendance attd ON (attd.ScheduleID = sd.ScheduleID AND attd.SDID = sd.ID)
                                                                        WHERE sd.ScheduleID = "'.$dataSchedule[$s]['ScheduleID'].'" ')->result_array();


                        $Attending = 0;

                        if(count($dataAttdID)>0){

                            $dataArrDetail = [];

                            for($a=0;$a<count($dataAttdID);$a++){

                                $dataDetail = $this->db->query('SELECT attdl.* FROM db_academic.attendance_lecturers attdl 
                                                                            WHERE attdl.ID_Attd = "'.$dataAttdID[$a]['ID_Attd'].'"
                                                                             AND attdl.Date >= "'.$dateStart.'"
                                                                              AND attdl.Date <= "'.$dateEnd.'"
                                                                              AND attdl.NIP = "'.$NIP.'" 
                                                                              ORDER BY attdl.Date')->result_array();

                                // Mendapatkan attending dengan hari yang sama

                                array_push($arrID_Attd,$dataAttdID[$a]['ID_Attd']);

                                if(count($dataDetail)>0){
                                    for($ab=0;$ab<count($dataDetail);$ab++){
                                        array_push($dataArrDetail,$dataDetail[$ab]);
                                    }
                                }


                            }

                            usort($dataArrDetail, function($a, $b) {
                                return strtotime($a['Date']) - strtotime($b['Date']);
                            });

                            $Attending = count($dataArrDetail);
                            $dataSchedule[$s]['Attending_Details'] = $dataArrDetail;


                        }



                        $dataSchedule[$s]['Attending'] = $Attending;
                    }

                    $querySameDate = '';
                    if(count($arrID_Attd)>0){
                        for($att=0;$att<count($arrID_Attd);$att++){
                            $ckOR = ($att!=0) ? ' OR ' : '';
                            $querySameDate = $querySameDate.$ckOR.'attdl.ID_Attd = "'.$arrID_Attd[$att].'" ';
                        }
                    }

                    $dataDetail_sameDate = $this->db->query('SELECT attdl.Date FROM db_academic.attendance_lecturers attdl 
                                                                            WHERE  attdl.Date >= "'.$dateStart.'"
                                                                              AND attdl.Date <= "'.$dateEnd.'"
                                                                              AND attdl.NIP = "'.$NIP.'" 
                                                                              AND ('.$querySameDate.') GROUP BY attdl.Date ORDER BY attdl.Date')->result_array();

                    $AttendingSameDate = count($dataDetail_sameDate);
                    $data[$i]['AttendingSameDate_Details'] = $dataDetail_sameDate;
                    $data[$i]['AttendingSameDate'] = $AttendingSameDate;

                }


                $data[$i]['Schedule'] = $dataSchedule;

            }
        }


        return $data;

    }

}
