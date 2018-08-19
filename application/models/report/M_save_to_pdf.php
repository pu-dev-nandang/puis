<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_save_to_pdf extends CI_Model {


    public function getScheduleByDay($SemesterID,$DayID){

        // Get Name Semester ID
        $dataSm = $this->db->select('Name AS SemesterName')->get_where('db_academic.semester',array('ID'=>$SemesterID),1)->result_array();
        $dataDay = $this->db->select('NameEng AS DayNameEng')->get_where('db_academic.days',array('ID'=>$DayID),1)->result_array();



        $dataSc = $this->db->query('SELECT s.ID, s.TeamTeaching, s.ClassGroup, sd.StartSessions, sd.EndSessions, em.Name AS Coordinator,
                                            cl.Room AS ClassRoom 
                                            FROM db_academic.schedule s 
                                            LEFT JOIN db_academic.schedule_details sd ON (sd.ScheduleID = s.ID)
                                            LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                            LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                            WHERE s.SemesterID = "'.$SemesterID.'" AND sd.DayID = "'.$DayID.'"
                                            ORDER BY sd.StartSessions, sd.EndSessions, s.ClassGroup ASC ')->result_array();

        if(count($dataSc)>0){
            for($i=0;$i<count($dataSc);$i++){
                $d = $dataSc[$i];
                $detailTeamTeaching = [];
                if($d['TeamTeaching']=='1' || $d['TeamTeaching']==1){
                    $dataEm = $this->db->query('SELECT em.Name FROM db_academic.schedule_team_teaching stt 
                                                        LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                        WHERE stt.ScheduleID = "'.$d['ID'].'"')->result_array();
                    if(count($dataEm)>0){
                        for($t=0;$t<count($dataEm);$t++){
                            array_push($detailTeamTeaching,$dataEm[$t]['Name']);
                        }
                    }
                }

                $d['detailTeamTeaching'] = $detailTeamTeaching;

                // Mendapatkan Matakuliah
                $dataC = $this->db->query('SELECT mk.NameEng FROM db_academic.schedule_details_course sdc 
                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                    WHERE sdc.ScheduleID = "'.$d['ID'].'" LIMIT 1')->result_array();

                if(count($dataC)>0){
                    $d['Course'] = $dataC[0]['NameEng'];
                }

                $dataSc[$i] = $d;

            }
        }

        $arrResult = array(
            'DetailsSemester' => array(
                'SemesterName' => $dataSm[0]['SemesterName'],
                'DayNameEng' => $dataDay[0]['DayNameEng']
            ),
            'DetailsCourse' => $dataSc
        );

        return $arrResult;

    }


}
