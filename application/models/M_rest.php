<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_rest extends CI_Model {

    private function _getSemesterActive(){
        $data = $this->db->get_where('db_academic.semester', array('Status'=>'1'),1);

        return $data->result_array()[0];
    }

    public function __getKSM2($db,$ProdiID,$NPM){

        $data = $this->db->query('SELECT sp.ScheduleID,sc.ClassGroup, sc.TeamTeaching,em.NIP,em.Name, em.EmailPU FROM '.$db.'.study_planning sp
                                                LEFT JOIN db_academic.semester s ON (s.ID = sp.SemesterID)
                                                LEFT JOIN db_academic.schedule sc ON (sc.ID = sp.ScheduleID)
                                                LEFT JOIN db_employees.employees em ON (em.NIP = sc.Coordinator)
                                                WHERE sp.NPM = "'.$NPM.'" AND s.Status = 1 ')->result_array();


        // Sch detail
        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $scDetail = $this->db->query('SELECT sd.ClassroomID, sd.DayID, sd.StartSessions, sd.EndSessions, cl.Room, d.NameEng
                                                    FROM db_academic.schedule_details sd 
                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID=sd.ClassRoomID)
                                                    LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                                    WHERE sd.ScheduleID = "'.$data[$i]['ScheduleID'].'" ORDER BY sd.DayID ASC ')->result_array();

                $data[$i]['DetailSchedule'] = $scDetail;

                $scCourse = $this->db->query('SELECT sdc.ProdiID,sdc.CDID, sdc.MKID,mk.MKCode, mk.NameEng, cd.TotalSKS AS Credit FROM db_academic.schedule_details_course sdc 
                                                        LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                                        WHERE sdc.ScheduleID = "'.$data[$i]['ScheduleID'].'" AND sdc.ProdiID = "'.$ProdiID.'" ')->result_array();

                $data[$i]['DetailCourse'] = $scCourse[0];

                if($data[$i]['TeamTeaching']=='1'){
                    $scTeam = $this->db->query('SELECT tc.NIP,em.Name,tc.Status FROM db_academic.schedule_team_teaching tc 
                                                          LEFT JOIN db_employees.employees em 
                                                          ON (em.NIP = tc.NIP)
                                                          WHERE tc.ScheduleID = "'.$data[$i]['ScheduleID'].'" ')->result_array();

                    $data[$i]['DetailTeamTeaching'] = $scTeam;
                }

            }

        }

        return $data;

    }

    public function __getKSM($db,$ProdiID,$NPM){
        $dataSemester = $this->db->query('SELECT s.* FROM db_academic.semester s ORDER BY s.ID ASC')->result_array();

//        print_r($dataSemester);

        $result = [];
        $smt = 1;
        for($i=0;$i<count($dataSemester);$i++){

            if($dataSemester[$i]['ID']<13){
                $dataSchedule = $this->db->query('SELECT zc.*,sp.TypeSchedule, mk.MKCode, mk.Name AS MKName, mk.NameEng AS MKNameEng, cd.TotalSKS AS Credit, em.Name AS Lecturer
                                                            FROM '.$db.'.study_planning sp 
                                                            LEFT JOIN db_academic.z_schedule zc ON (zc.Glue = sp.Glue) 
                                                            LEFT JOIN db_employees.employees em ON (em.NIP = zc.NIP)
                                                            LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = zc.MKID)
                                                            LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sp.CDID)
                                                            WHERE sp.NPM = "'.$NPM.'" 
                                                            AND zc.SemesterID = "'.$dataSemester[$i]['ID'].'" 
                                                            AND zc.ProdiID = "'.$ProdiID.'"
                                                            GROUP BY mk.MKCode
                                                            ORDER BY mk.MKCode ASC
                                                            ')->result_array();

                if(count($dataSchedule)>0){

                    for($s=0;$s<count($dataSchedule);$s++){
                        if($dataSchedule[$s]['IsTeamTeaching']=='1'){
                            $dataTc = $this->db->query('SELECT ztt.*,em.Name AS Lecturer, em.TitleAhead, em.TitleBehind FROM db_academic.z_team_teaching ztt
                                                            LEFT JOIN db_employees.employees em ON (ztt.NIP = em.NIP)
                                                            WHERE ztt.Glue = "'.$dataSchedule[$s]['Glue'].'" ')->result_array();


                            $dataSchedule[$s]['TeamTeaching'] = $dataTc;

                            $dataDateTime = $this->db->query('SELECT zc.Day, zc.Start, zc.End, zc.Classroom FROM db_academic.z_schedule zc 
                                                                  WHERE
                                                                  zc.SemesterID = "'.$dataSemester[$i]['ID'].'"
                                                                  AND zc.ProdiID = "'.$ProdiID.'" 
                                                                  AND zc.Glue = "'.$dataSchedule[$s]['Glue'].'" ')->result_array();
                            $dataSchedule[$s]['DetailDateSchedule'] = $dataDateTime;
                        }
                    }

                    $dataArr = array(
                        'SemesterID' => $dataSemester[$i]['ID'],
                        'Semester' => $smt,
                        'SemesterName' => $dataSemester[$i]['Name'],
                        'StatusSystem' => '0',
                        'Schedule' => $dataSchedule
                    );
                    array_push($result,$dataArr);
                    $smt += 1;
                }
            }
            else {

                $data = $this->db->query('SELECT sp.ScheduleID,sp.TypeSchedule,mk.MKCode,mk.Name AS MKName,mk.nameEng AS MKNameEng,cd.TotalSKS AS Credit,
                                                sp.StatusSystem,sc.ClassGroup, sc.TeamTeaching,
                                                em.NIP,em.Name,em.TitleAhead, em.TitleBehind, em.EmailPU
                                                FROM '.$db.'.study_planning sp
                                                LEFT JOIN db_academic.semester s ON (s.ID = sp.SemesterID)
                                                LEFT JOIN db_academic.schedule sc ON (sc.ID = sp.ScheduleID)
                                                LEFT JOIN db_employees.employees em ON (em.NIP = sc.Coordinator)
                                                LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sp.MKID)
                                                LEFT JOIN db_academic.curriculum_details cd ON (sp.CDID=cd.ID)
                                                WHERE sp.NPM = "'.$NPM.'" AND s.ID = "'.$dataSemester[$i]['ID'].'"
                                                ORDER BY mk.MKCode ASC ')->result_array();

                if(count($data)>0){
                    for($sc=0;$sc<count($data);$sc++){
                        $LecturerCoor = $data[$sc]['TitleAhead'].' '.$data[$sc]['Name'].' '.$data[$sc]['TitleBehind'];
                        $data[$sc]['Lecturer'] = trim($LecturerCoor);

                        $dataSchedule = $this->db->query('SELECT sd.StartSessions,sd.EndSessions,cl.Room,d.Name AS Day, d.NameEng AS DayEng FROM db_academic.schedule_details sd
                                                                      LEFT JOIN db_academic.classroom cl ON (cl.ID=sd.ClassroomID)
                                                                      LEFT JOIN db_academic.days d ON (d.ID=sd.DayID)
                                                                      WHERE sd.ScheduleID = "'.$data[$sc]['ScheduleID'].'"
                                                                       ORDER BY d.ID ASC')->result_array();
                        $data[$sc]['Schedule'] = $dataSchedule;
                        $data[$sc]['TeamTeachingDetails'] = [];

                        if($data[$sc]['TeamTeaching']==1){
                            $dataTT = $this->db->query('SELECT e.NIP,e.Name,e.TitleAhead, e.TitleBehind FROM db_academic.schedule_team_teaching stt LEFT JOIN db_employees.employees e ON (e.NIP = stt.NIP)')->result_array();
                            for($t=0;$t<count($dataTT);$t++){
                                $Lecturer = $dataTT[$t]['TitleAhead'].' '.$dataTT[$t]['Name'].' '.$dataTT[$t]['TitleBehind'];
                                $dataTT[$t]['Lecturer'] = trim($Lecturer);
                            }
                            $data[$sc]['TeamTeachingDetails'] = $dataTT ;
                        }

                    }

                }

                $dataArr = array(
                    'SemesterID' => $dataSemester[$i]['ID'],
                    'Semester' => $smt,
                    'SemesterName' => $dataSemester[$i]['Name'],
                    'StatusSystem' => '1',
                    'Schedule' => $data
                );
                array_push($result,$dataArr);
                $smt += 1;

            }

        }

        return $result;
    }

    public function newSystem($data,$ProdiID){


        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $scDetail = $this->db->query('SELECT sd.ClassroomID, sd.DayID, sd.StartSessions, sd.EndSessions, cl.Room, d.NameEng
                                                    FROM db_academic.schedule_details sd 
                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID=sd.ClassRoomID)
                                                    LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                                    WHERE sd.ScheduleID = "'.$data[$i]['ScheduleID'].'" ORDER BY sd.DayID ASC ')->result_array();

                $data[$i]['DetailSchedule'] = $scDetail;

                $scCourse = $this->db->query('SELECT sdc.ProdiID,sdc.CDID, sdc.MKID,mk.MKCode, mk.NameEng, cd.TotalSKS AS Credit FROM db_academic.schedule_details_course sdc 
                                                        LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                                        WHERE sdc.ScheduleID = "'.$data[$i]['ScheduleID'].'" AND sdc.ProdiID = "'.$ProdiID.'" ')->result_array();

                $data[$i]['DetailCourse'] = $scCourse[0];

                if($data[$i]['TeamTeaching']=='1'){
                    $scTeam = $this->db->query('SELECT tc.NIP,em.Name,tc.Status FROM db_academic.schedule_team_teaching tc 
                                                          LEFT JOIN db_employees.employees em 
                                                          ON (em.NIP = tc.NIP)
                                                          WHERE tc.ScheduleID = "'.$data[$i]['ScheduleID'].'" ')->result_array();

                    $data[$i]['DetailTeamTeaching'] = $scTeam;
                }

            }

        }

        return $data;
    }


    public function __geTimetable($NIP)
    {

        $SemesterActive = $this->_getSemesterActive();
        $SemesterID = $SemesterActive['ID'];

        $dataSemester = $this->db->query('SELECT s.* FROM db_academic.semester s ORDER BY s.ID ASC')->result_array();

        $result = [];
        for($i=0;$i<count($dataSemester);$i++){
            if($dataSemester[$i]['ID']<13){
                // Koordinator
                $Coordinator = $this->db->query('SELECT s.*, mk.MKCode, mk.Name AS MKName, mk.NameEng AS MKNameEng, ps.NameEng AS ProdiEng, ps.Code AS ProdiCode 
                                                          FROM db_academic.z_schedule s 
                                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID=s.MKID)
                                                          LEFT JOIN db_academic.program_study ps ON (ps.ID=s.ProdiID)
                                                          WHERE s.SemesterID="'.$dataSemester[$i]['ID'].'" 
                                                          AND s.NIP = "'.$NIP.'" GROUP BY mk.MKCode ')->result_array();

                if(count($Coordinator)>0){
                    for($t=0;$t<count($Coordinator);$t++){
                        if($Coordinator[$t]['IsTeamTeaching']=='1'){

                            $ttc = $this->db->query('SELECT ttc.*,em.Name,em.TitleAhead,em.TitleBehind FROM db_academic.z_team_teaching ttc 
                                                          LEFT JOIN db_employees.employees em ON (em.NIP=ttc.NIP)
                                                          WHERE ttc.Glue = "'.$Coordinator[$t]['Glue'].'" AND ttc.Pengampu = "TIDAK" ')
                                ->result_array();
                            if(count($ttc)){
                                for($l=0;$l<count($ttc);$l++){
                                    $Lecturer = $ttc[$l]['TitleAhead'].' '.$ttc[$l]['Name'].' '.$ttc[$l]['TitleBehind'];
                                    $ttc[$l]['Lecturer'] = trim($Lecturer);
                                }
                                $Coordinator[$t]['DetailTeamTeaching'] = $ttc;
                            }

                        }
                    }

                }

                $TeamTeaching = $this->db->query('SELECT s.*,em.Name,em.TitleAhead,em.TitleBehind, mk.MKCode, mk.Name AS MKName,
                                                          mk.NameEng AS MKNameEng, ps.NameEng AS ProdiEng, ps.Code AS ProdiCode 
                                                          FROM db_academic.z_team_teaching ttc
                                                          LEFT JOIN db_academic.z_schedule s ON (s.Glue = ttc.Glue)
                                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID=s.MKID)
                                                          LEFT JOIN db_academic.program_study ps ON (ps.ID=s.ProdiID)
                                                          LEFT JOIN db_employees.employees em ON (em.NIP=s.NIP)
                                                          WHERE s.SemesterID="'.$dataSemester[$i]['ID'].'" 
                                                          AND ttc.NIP = "'.$NIP.'" AND ttc.Pengampu = "TIDAK" GROUP BY mk.MKCode')->result_array();

                if(count($TeamTeaching)>0){
                    for($ttc=0;$ttc<count($TeamTeaching);$ttc++){
                        $CoordinatorTcc = $TeamTeaching[$ttc]['TitleAhead'].' '.$TeamTeaching[$ttc]['Name'].' '.$TeamTeaching[$ttc]['TitleBehind'];
                        $TeamTeaching[$ttc]['Coordinator'] = trim($CoordinatorTcc);
                    }
                }

                $arr_p = array(
                    'SemesterID' => $dataSemester[$i]['ID'],
                    'Semester' => $dataSemester[$i]['Name'],
                    'Status' => $dataSemester[$i]['Status'],
                    'DetailsCoordinator' => $Coordinator,
                    'DetailsTeamTeaching' => $TeamTeaching
                );

                array_push($result,$arr_p);

            }
            // Sistem Baru
            else {

                $Coordinator = $this->db->query('SELECT s.*,em.Name AS CoordinatorName
                                              FROM db_academic.schedule s
                                              LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                              WHERE s.SemesterID = "'.$dataSemester[$i]['ID'].'" 
                                              AND s.Coordinator = "'.$NIP.'" AND s.IsSemesterAntara = "0" ')->result_array();

                $TeamTheaching = $this->db->query('SELECT s.*,em.Name AS CoordinatorName, stt.Status AS StatusTeamTeaching FROM db_academic.schedule_team_teaching stt 
                                                        LEFT JOIN db_academic.schedule s ON (s.ID=stt.ScheduleID)
                                                        LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                        WHERE s.SemesterID ="'.$SemesterID.'" 
                                                        AND stt.NIP = "'.$NIP.'"
                                                        AND s.IsSemesterAntara = "0" ')->result_array();

                $arr_p = array(
                    'SemesterID' => $dataSemester[$i]['ID'],
                    'Semester' => $dataSemester[$i]['Name'],
                    'Status' => $dataSemester[$i]['Status'],
                    'DetailsCoordinator' => $this->getDetailTimeTable($Coordinator,'Coordinator'),
                    'DetailsTeamTeaching' => $this->getDetailTimeTable($TeamTheaching,'')
                );

                array_push($result,$arr_p);

            }
        }

        return $result;

    }

    private function getDetailTimeTable($dataSch,$param){

        if(count($dataSch)){
            for ($s = 0; $s < count($dataSch); $s++) {
                $sesi = $this->db->query('SELECT sd.ScheduleID, cl.Room, d.NameEng, sd.StartSessions, sd.EndSessions
                                                   FROM db_academic.schedule_details sd
                                                   LEFT JOIN db_academic.classroom cl ON (cl.ID=sd.ClassroomID)
                                                   LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                                    WHERE sd.ScheduleID = "' . $dataSch[$s]['ID'] . '" ')->result_array();
                $dataSch[$s]['detailSesi'] = $sesi;

                $course = $this->db->query('SELECT sdc.ScheduleID, mk.ID AS MKID, cd.TotalSKS AS Credit, mk.MKCode, mk.Name, mk.NameEng  
                                                   FROM db_academic.schedule_details_course sdc
                                                   LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID) 
                                                   LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                    WHERE sdc.ScheduleID = "' . $dataSch[$s]['ID'] . '" GROUP BY  sdc.ScheduleID ')->result_array();
//                                                    WHERE sdc.ScheduleID = "' . $dataSch[$s]['ID'] . '" AND  sdc.ProdiID = "'.$ProdiID.'"  ')->result_array();
                $dataSch[$s]['detailCourse'] = $course;

//                if($param=='Coordinator'){
                    $team = $this->db->query('SELECT em.NIP,em.Name FROM db_academic.schedule_team_teaching stt 
                                                       LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                       WHERE stt.ScheduleID="' . $dataSch[$s]['ID'] . '"
                                                       ')->result_array();
                    $dataSch[$s]['detailTeamTeaching'] = $team;

                    $silabus = $this->db->query('SELECT gc.* 
                                                            FROM  db_academic.grade_course gc 
                                                            WHERE gc.ScheduleID = "'.$dataSch[$s]['ID'].'" ')->result_array();

                    $dataSch[$s]['detailSilabusSAP'] = $silabus;
//                }

            }
        }

        return $dataSch;
    }


}
