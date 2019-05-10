<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_save_to_pdf extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_rest');
    }


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
                                              WHERE ex.Date = "'.$dateSearch.'" AND (ex.Status = "2" OR ex.Status = "1") 
                                              GROUP BY ex.ID')
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



                for($r=0;$r<count($dataC);$r++){
                    // Detail Prodi
                    $dataProdi = $this->db->query('SELECT ps.Code FROM db_academic.schedule_details_course sdc 
                                                            LEFT JOIN db_academic.program_study ps ON (ps.ID = sdc.ProdiID)
                                                            WHERE sdc.ScheduleID = "'.$dataC[$r]['ScheduleID'].'"
                                                            GROUP BY ps.ID ORDER BY ps.ID')->result_array();

                    $prodi = '';
                    for($p=0;$p<count($dataProdi);$p++){
                        $del = ($p==0) ? '' : ', ';
                        $prodi = $prodi.''.$del.''.''.$dataProdi[$p]['Code'];
                    }
                    $dataC[$r]['Prodi'] = $prodi;
                }


                $data[$c]['Course'] = $dataC;
            }
        }

        return $data;

    }

    public function getExamScheduleWithStudent($SemesterID,$Type,$ExamDate){

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


                if(count($dataC)>0){

                    // Get Prodi
                    for($r=0;$r<count($dataC);$r++){
                        // Detail Prodi
                        $dataProdi = $this->db->query('SELECT ps.Code FROM db_academic.schedule_details_course sdc 
                                                            LEFT JOIN db_academic.program_study ps ON (ps.ID = sdc.ProdiID)
                                                            WHERE sdc.ScheduleID = "'.$dataC[$r]['ScheduleID'].'"
                                                            GROUP BY ps.ID ORDER BY ps.ID')->result_array();

                        $prodi = '';
                        for($p=0;$p<count($dataProdi);$p++){
                            $del = ($p==0) ? '' : ', ';
                            $prodi = $prodi.''.$del.''.''.$dataProdi[$p]['Code'];
                        }
                        $dataC[$r]['Prodi'] = $prodi;
                    }



                    // Get Students
                    for($r=0;$r<count($dataC);$r++){
//                        $dataStd = $this->db->query('SELECT NPM,DB_Students FROM db_academic.exam_details exd
//                                                        WHERE exd.ExamID = "'.$dataC[$r]['ExamID'].'"
//                                                        AND exd.ScheduleID = "'.$dataC[$r]['ScheduleID'].'"
//                                                         ORDER BY exd.NPM ASC ')->result_array();
//
//                        $arr_student = [];
//                        if(count($dataStd)>0){
//                            for($st=0;$st<count($dataStd);$st++){
//                                $arr = [];
//                                $dataStdName = $this->db->select('Name,ClassOf')->get_where($dataStd[$st]['DB_Students'].'.students',
//                                    array('NPM' => $dataStd[$st]['NPM']),1)->result_array();
//
//                                // Cek Semester
//                                $dataSemester = $this->m_rest->checkSemesterByClassOf($dataStdName[0]['ClassOf'],$SemesterID);
//
//                                // Cek Attendace
//                                $dataAttendace = $this->m_rest->getAttendanceStudent($dataStd[$st]['NPM'],$dataC[$r]['ScheduleID']);
//
//                                if($dataSemester==1 || $dataSemester=='1'){
//                                    if($Type=='uts' || $Type=='UTS'){
//                                        $arr = array(
//                                            'NPM' => $dataStd[$st]['NPM'],
//                                            'Name' => $dataStdName[0]['Name'],
//                                            'DB_Students' => $dataStd[$st]['DB_Students'],
//                                        );
//                                    } else {
//                                        if(isset($dataAttendace['Percentage'])
//                                            && $dataAttendace['Percentage']!=null
//                                            && $dataAttendace['Percentage']!='' && round($dataAttendace['Percentage'])>=75){
//                                            $arr = array(
//                                                'NPM' => $dataStd[$st]['NPM'],
//                                                'Name' => $dataStdName[0]['Name'],
//                                                'DB_Students' => $dataStd[$st]['DB_Students'],
//                                            );
//                                        }
//                                    }
//
//                                } else{
//
//                                  // Cek Pembayaran
//                                    $dataPayment = $this->m_rest->checkPayment($dataStd[$st]['NPM'],$SemesterID);
//                                    if($dataPayment['BPP']['Status']==1 && $dataPayment['Credit']['Status']==1){
//                                        if($Type=='uts' || $Type=='UTS'){
//                                            $arr = array(
//                                                'NPM' => $dataStd[$st]['NPM'],
//                                                'Name' => $dataStdName[0]['Name'],
//                                                'DB_Students' => $dataStd[$st]['DB_Students'],
//                                            );
//                                        } else if ($Type=='uas' || $Type=='UAS') {
//                                            if(isset($dataAttendace['Percentage'])
//                                                && $dataAttendace['Percentage']!=null
//                                                && $dataAttendace['Percentage']!='' && round($dataAttendace['Percentage'])>=75){
//                                                $arr = array(
//                                                    'NPM' => $dataStd[$st]['NPM'],
//                                                    'Name' => $dataStdName[0]['Name'],
//                                                    'DB_Students' => $dataStd[$st]['DB_Students'],
//                                                );
//                                            }
//                                        }
//
//                                    } else if ($Type=='re_uts') {
//                                        $arr = array(
//                                            'NPM' => $dataStd[$st]['NPM'],
//                                            'Name' => $dataStdName[0]['Name'],
//                                            'DB_Students' => $dataStd[$st]['DB_Students'],
//                                        );
//                                    }
//                                }
//
//                                if(isset($arr['NPM'])){
//                                    array_push($arr_student,$arr);
//                                }
//
//                            }
//                        }


                        $arr_student = [];
                         $dataStdInExcam = $this->m_rest->getListStudentExam($dataC[$r]['ExamID']);
                        if($dataStdInExcam['Status']=='1' || $dataStdInExcam['Status']==1){
                            $arr_student = $dataStdInExcam['DetailStudents'];
                        }

                        $dataC[$r]['DetailStudents'] = $arr_student;

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
                    $rand = ($dataRand[0]['Status']=='1' || $dataRand[0]['Status']==1) ? 'RAND()' : '' ;

                    for($c=0;$c<count($dataC);$c++){

                        // Get Students
//                        $dataStd = $this->db->query('SELECT exd.NPM,exd.Name,exd.ScheduleID
//                                                                  FROM db_academic.exam_details exd
//                                                                  WHERE exd.ExamID = "'.$data[$i]['ID'].'"
//                                                                   AND exd.ScheduleID = "'.$dataC[$c]['ScheduleID'].'"
//                                                                    ORDER BY '.$rand.' ')
//                                                                    ->result_array();
//
//                        $dataC[$c]['DetailStudent'] = $dataStd;

                        $dataStd =  $this->m_rest->getListStudentExam($data[$i]['ID'],$rand);
                        $resStd = ($dataStd['Status']==1 || $dataStd['Status']=='1')
                            ? $dataStd['DetailStudents'] : [];

                        $dataC[$c]['DetailStudent'] = $resStd;
                    }
                }

                $data[$i]['Course'] = $dataC;

            }
        }

        return $data;

    }

    public function getEmployeesByPositionMain($PositionMain){
        $data = $this->db->query('SELECT NIP,Name FROM db_employees.employees 
                                            WHERE PositionMain = "'.$PositionMain.'" 
                                            AND (StatusEmployeeID = 2 OR StatusEmployeeID = 1) LIMIT 1')
                                        ->result_array();
        return $data;
    }

    public function getTranscript($DBStudent,$NPM){

        $dataStd = $this->db->query('SELECT s.Name, s.NPM, s.PlaceOfBirth, s.DateOfBirth, aus.CertificateSerialNumber AS CSN, aus.CertificateNationalNumber AS CNN, 
                                            ps.Name AS Prodi, ps.NameEng AS ProdiEng, edl.Description AS GradeDesc, 
                                            edl.DescriptionEng AS GradeDescEng, em.NIP, em.Name AS Dekan, em.TitleAhead, em.TitleBehind, 
                                            fp.TitleInd, fp.TitleEng, f.Name AS FacultyName
                                            FROM '.$DBStudent.'.students s
                                            LEFT JOIN db_academic.auth_students aus ON (s.NPM = aus.NPM) 
                                            LEFT JOIN db_academic.program_study ps ON (s.ProdiID = ps.ID) 
                                            LEFT JOIN db_academic.education_level edl ON (edl.ID = ps.EducationLevelID)
                                            LEFT JOIN db_academic.faculty f ON (f.ID = ps.FacultyID)
                                            LEFT JOIN db_employees.employees em ON (em.NIP = f.NIP)
                                            LEFT JOIN db_academic.final_project fp ON (fp.NPM = s.NPM)
                                            WHERE s.NPM = "'.$NPM.'" ')->result_array();


        $data = $this->db->query('SELECT sp.Credit, sp.Grade, sp.GradeValue, mk.Name AS MKName, mk.NameEng AS MKNameEng, 
                                          sp.MKID, mk.MKCode 
                                          FROM '.$DBStudent.'.study_planning sp 
                                          LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sp.CDID)
                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                          LEFT JOIN db_academic.semester s ON (s.ID = sp.SemesterID)
                                          WHERE sp.NPM = "'.$NPM.'" AND s.Status != 1 AND sp.ShowTranscript = "1" ')->result_array();

        $totalSKS = 0;
        $totalGradeValue = 0;

        $arrDetailCourseID = [];
        $DetailCourse = [];

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $d = $data[$i];

                if(in_array($d['MKID'],$arrDetailCourseID)!=-1){
                    $dataScore = $dataScore = $this->db->order_by('Score', 'DESC')
                        ->get_where($DBStudent.'.study_planning',array('NPM' => $NPM,'MKID'=>$d['MKID']))->result_array();

                    $Grade = ($dataScore[0]['Grade']!='' && $dataScore[0]['Grade']!=null) ? $dataScore[0]['Grade'] : 'E';
                    $GradeValue = ($dataScore[0]['GradeValue']!='' && $dataScore[0]['GradeValue']!=null) ? $dataScore[0]['GradeValue'] : 0;
                    $Point = $d['Credit'] * $GradeValue;

                    $data[$i]['Grade'] = $Grade;
                    $data[$i]['GradeValue'] = $GradeValue;
                    $data[$i]['Point'] = $Point;

                    $totalSKS = $totalSKS + $d['Credit'];
                    $totalGradeValue = $totalGradeValue + $Point;

                    array_push($arrDetailCourseID,$d['MKID']);
                    array_push($DetailCourse,$data[$i]);
                }


            }
        }

        $IPK_Ori = (count($data)>0) ? $totalGradeValue/$totalSKS : 0 ;
        $ipk = round($IPK_Ori,2);

        $grade = $this->getGraduation(number_format($ipk,2,'.',''));

        // Get Rektor
        $dataRektor = $this->db->query('SELECT em.NIP, em.Name, em.TitleAhead, em.TitleBehind FROM db_employees.employees em
                                                    LEFT JOIN db_employees.employees_status ems ON (ems.ID = em.StatusEmployeeID)
                                                    WHERE em.PositionMain = "2.1" AND ems.IDStatus != -1 AND ems.IDStatus != -2 ')->result_array();

        // Wakil rektor akademik / Warek I
        $dataWaRek1 = $this->db->query('SELECT em.NIP, em.Name, em.TitleAhead, em.TitleBehind FROM db_employees.employees em
                                                    LEFT JOIN db_employees.employees_status ems ON (ems.ID = em.StatusEmployeeID)
                                                    WHERE em.PositionMain = "2.2" AND ems.IDStatus != -1 AND ems.IDStatus != -2 ')->result_array();

        $dataTranscript = $this->db->limit(1)->get('db_academic.setting_transcript')->result_array();
        $dataTempTranscript = $this->db->limit(1)->get('db_academic.setting_temp_transcript')->result_array();

        $result = array(
            'Student' => $dataStd,
            'Result' => array(
                'TotalSKS' => $totalSKS,
                'TotalGradeValue' => $totalGradeValue,
                'IPK_Ori' => $IPK_Ori,
                'IPK' => $ipk,
                'Grading' => $grade
            ),
            'Transcript' => $dataTranscript,
            'TempTranscript' => $dataTempTranscript,
            'Rektorat' => $dataRektor,
            'WaRek1' => $dataWaRek1,
            'DetailCourse' => $DetailCourse
//            'DetailCourse' => $data
        );

        return $result;
    }

    public function getGraduation($IPK){

        $dataGrade = $this->db->query('SELECT * FROM db_academic.graduation g 
                                                  WHERE g.IPKStart <= "'.$IPK.'" AND g.IPKEnd >= "'.$IPK.'"
                                                   LIMIT 1')->result_array();

        return $dataGrade;
    }

    public function getIjazah($DBStudent,$NPM){
        $dataStd = $this->db->query('SELECT s.Name, s.NPM, s.PlaceOfBirth, s.DateOfBirth, aus.CertificateSerialNumber AS CSN, 
                                            ps.Name AS Prodi, ps.NameEng AS ProdiEng, aus.KTPNumber,
                                            ps.Degree, ps.TitleDegree, ps.DegreeEng, ps.TitleDegreeEng, 
                                            edl.Description AS GradeDesc, edl.DescriptionEng AS GradeDescEng, 
                                            em.NIP, em.Name AS Dekan, em.TitleAhead, em.TitleBehind 
                                            FROM '.$DBStudent.'.students s
                                            LEFT JOIN db_academic.auth_students aus ON (s.NPM = aus.NPM) 
                                            LEFT JOIN db_academic.program_study ps ON (s.ProdiID = ps.ID) 
                                            LEFT JOIN db_academic.education_level edl ON (edl.ID = ps.EducationLevelID)
                                            LEFT JOIN db_academic.faculty f ON (f.ID = ps.FacultyID)
                                            LEFT JOIN db_employees.employees em ON (em.NIP = f.NIP)
                                            WHERE s.NPM = "'.$NPM.'" ')->result_array();

        $dataTranscript = $this->db->get('db_academic.setting_transcript')->result_array();

        // Get Rektor
        $dataRektor = $this->db->select('NIP, Name, TitleAhead, TitleBehind')->get_where('db_employees.employees',array('PositionMain' => '2.1'),1)->result_array();

        $result = array(
            'Student' => $dataStd,
            'Rektorat' => $dataRektor,
            'Ijazah' => $dataTranscript
        );

        return $result;
    }


//======================================= tambahan SKL TGL 18-01-2019 =========================================================
//==============================================================================================================================
    public function getSkls($DBStudent,$NPM){
        $dataStd = $this->db->query('SELECT s.Name, s.NPM, s.PlaceOfBirth, s.DateOfBirth, aus.CertificateSerialNumber AS CSN, 
                                            ps.Name AS Prodi, ps.NameEng AS ProdiEng, 
                                            ps.Degree, ps.TitleDegree, ps.DegreeEng, ps.TitleDegreeEng, 
                                            edl.Description AS GradeDesc, edl.DescriptionEng AS GradeDescEng, 
                                            em.NIP, em.Name AS Dekan, em.TitleAhead, em.TitleBehind,f.Name AS Faculty,f.NameEng AS FacultyEng , 
                                            s.Gender, aus.SklNumber AS SKLN
                                            FROM '.$DBStudent.'.students s
                                            LEFT JOIN db_academic.auth_students aus ON (s.NPM = aus.NPM) 
                                            LEFT JOIN db_academic.program_study ps ON (s.ProdiID = ps.ID) 
                                            LEFT JOIN db_academic.education_level edl ON (edl.ID = ps.EducationLevelID)
                                            LEFT JOIN db_academic.faculty f ON (f.ID = ps.FacultyID)
                                            LEFT JOIN db_employees.employees em ON (em.NIP = f.NIP)
                                            WHERE s.NPM = "'.$NPM.'" ')->result_array();

        $dataTranscript = $this->db->get('db_academic.setting_transcript')->result_array();

          $result = array(
            'Student' => $dataStd,
            'Skls' => $dataTranscript
        );

        return $result;

    }
//==============================================================================================================================
//==============================================================================================================================

}
