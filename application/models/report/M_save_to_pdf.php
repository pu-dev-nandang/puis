<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_save_to_pdf extends CI_Model {


    public function getScheduleByDay($SemesterID,$DayID,$dateNow){

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



        // ====== Get Exchange =======
        $dayofweek = date('w', strtotime($dateNow));
        $dateSearch    = date('Y-m-d', strtotime(($DayID - $dayofweek).' day', strtotime($dateNow)));



        $dataEx = $this->db->query('SELECT s.ID, s.TeamTeaching, s.ClassGroup, ex.StartSessions, ex.EndSessions, em.Name AS Coordinator,
                                            cl.Room AS ClassRoom  FROM db_academic.schedule_exchange ex 
                                            LEFT JOIN db_academic.attendance attd ON (attd.ID = ex.ID_Attd)
                                            LEFT JOIN db_academic.schedule s ON (attd.ScheduleID = s.ID)
                                            LEFT JOIN db_academic.schedule_details sd ON (sd.ScheduleID = s.ID)
                                            LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                            LEFT JOIN db_academic.classroom cl ON (cl.ID = ex.ClassroomID)
                                              WHERE ex.Date = "'.$dateSearch.'" AND ex.Status = "1" ')
            ->result_array();

        if(count($dataEx)>0){
            for($e=0;$e<count($dataEx);$e++){
                $dataEx[$e]['Label'] = 'Ex';
                array_push($dataSc,$dataEx[$e]);
            }
        }







        if(count($dataSc)>0){
            for($i=0;$i<count($dataSc);$i++){
                $d = $dataSc[$i];

                $d['Label'] = (isset($d['Label']) && $d['Label']=='Ex') ? 'Ex' : 'Pr';

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

    public function getExamSchedule($SemesterID,$Type,$ExamDate){

        $data = $this->db->query('SELECT ex.*,cl.Room, em1.Name AS Name_P1, em2.Name AS Name_P2 FROM db_academic.exam ex 
                                          LEFT JOIN db_academic.classroom cl ON (cl.ID = ex.ExamClassroomID)
                                          LEFT JOIN db_employees.employees em1 ON (em1.NIP = ex.Pengawas1)
                                          LEFT JOIN db_employees.employees em2 ON (em2.NIP = ex.Pengawas2)
                                          WHERE ex.SemesterID = "'.$SemesterID.'"
                                           AND ex.Type = "'.$Type.'"
                                            AND ex.ExamDate = "'.$ExamDate.'"
                                             ORDER BY ex.ExamDate, ex.ExamStart, ex.ExamEnd ')->result_array();

        if(count($data)>0){
            for($c=0;$c<count($data);$c++){
                $dataC = $this->db->query('SELECT exg.*, s.ClassGroup, mk.NameEng AS Course, mk.MKCode, 
                                                    em.Name AS Lecturere
                                                    FROM db_academic.exam_group exg
                                                    LEFT JOIN db_academic.schedule s ON (s.ID = exg.ScheduleID)
                                                    LEFT JOIN db_academic.schedule_details_course sdc ON (s.ID = sdc.ScheduleID)
                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                    LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                    WHERE exg.ExamID = "'.$data[$c]['ID'].'"
                                                     GROUP BY exg.ScheduleID ORDER BY s.ClassGroup ASC ')->result_array();

                $data[$c]['Course'] = $dataC;
            }
        }

        return $data;

    }

    public function getExamScheduleWithStudent($SemesterID,$Type,$ExamDate){

        $data = $this->db->query('SELECT ex.*,cl.Room FROM db_academic.exam ex 
                                          LEFT JOIN db_academic.classroom cl ON (cl.ID = ex.ExamClassroomID)
                                          WHERE ex.SemesterID = "'.$SemesterID.'"
                                           AND ex.Type = "'.$Type.'"
                                            AND ex.ExamDate = "'.$ExamDate.'"
                                             ORDER BY ex.ExamDate, ex.ExamStart, ex.ExamEnd ')->result_array();

        if(count($data)>0){
            for($c=0;$c<count($data);$c++){
                $dataC = $this->db->query('SELECT exg.*, s.ClassGroup, mk.NameEng AS Course, mk.MKCode, 
                                                    em.Name AS Lecturere
                                                    FROM db_academic.exam_group exg
                                                    LEFT JOIN db_academic.schedule s ON (s.ID = exg.ScheduleID)
                                                    LEFT JOIN db_academic.schedule_details_course sdc ON (s.ID = sdc.ScheduleID)
                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                    LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                    WHERE exg.ExamID = "'.$data[$c]['ID'].'"
                                                     GROUP BY exg.ScheduleID ORDER BY s.ClassGroup ASC ')->result_array();

                if(count($dataC)>0){
                    for($r=0;$r<count($dataC);$r++){
                        $dataStd = $this->db->query('SELECT NPM,DB_Students FROM db_academic.exam_details exd 
                                                        WHERE exd.ExamID = "'.$data[$c]['ID'].'" 
                                                        AND exd.ScheduleID = "'.$dataC[$r]['ScheduleID'].'"
                                                         ORDER BY exd.NPM ASC ')->result_array();

                        if(count($dataStd)>0){
                            for($st=0;$st<count($dataStd);$st++){
                                $dataStdName = $this->db->select('Name')->get_where($dataStd[$st]['DB_Students'].'.students',
                                    array('NPM' => $dataStd[$st]['NPM']),1)->result_array();
                                $dataStd[$st]['Name'] = $dataStdName[0]['Name'];
                            }
                        }

                        $dataC[$r]['DetailStudents'] = $dataStd;

                    }
                }



                $data[$c]['Course'] = $dataC;
            }
        }

        return $data;

    }

    public function getExamByID($ExamID){

        $data = $this->db->query('SELECT ex.*,cl.Room, cl.DeretForExam,cl.LectureDesk,  
                                             s.Name AS Semester,
                                             em1.Name AS Name_P1, em2.Name AS Name_P2
                                            FROM db_academic.exam ex
                                            LEFT JOIN db_academic.classroom cl ON (cl.ID=ex.ExamClassroomID)
                                            LEFT JOIN db_employees.employees em1 ON (em1.NIP = ex.Pengawas1)
                                            LEFT JOIN db_employees.employees em2 ON (em2.NIP = ex.Pengawas2)
                                            LEFT JOIN db_academic.semester s ON (s.ID = ex.SemesterID)
                                            WHERE ex.ID = "'.$ExamID.'" ')->result_array();


        if(count($data)>0){

            for($i=0;$i<count($data);$i++){

                // Data Course
                $dataC = $this->db->query('SELECT exg.*,s.ClassGroup, mk.NameEng AS CourseEng, mk.MKCode FROM db_academic.exam_group exg 
                                                      LEFT JOIN db_academic.schedule s ON (s.ID = exg.ScheduleID)
                                                      LEFT JOIN db_academic.schedule_details_course sdc ON (s.ID = sdc.ScheduleID)
                                                      LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                      WHERE exg.ExamID = "'.$data[$i]['ID'].'" GROUP BY s.ID')->result_array();

                if(count($dataC)>0){

                    // Cek Status Random atau tidak
                    $dataRand = $this->db->get_where('db_academic.config',array('ConfigID' => 1),1)->result_array();
                    $rand = ($dataRand[0]['Status']=='1' || $dataRand[0]['Status']==1) ? 'RAND()' : 'exd.NPM ASC' ;

                    for($c=0;$c<count($dataC);$c++){

                        // Get Students
                        $dataStd = $this->db->query('SELECT exd.NPM,exd.Name,exd.ScheduleID 
                                                                  FROM db_academic.exam_details exd 
                                                                  WHERE exd.ExamID = "'.$data[$i]['ID'].'"
                                                                   AND exd.ScheduleID = "'.$dataC[$c]['ScheduleID'].'"
                                                                    ORDER BY '.$rand.' ')
                                                                    ->result_array();

                        $dataC[$c]['DetailStudent'] = $dataStd;
                    }
                }

                $data[$i]['Course'] = $dataC;

            }
        }

        return $data;

    }

}
