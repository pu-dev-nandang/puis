<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_rest extends CI_Model {

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
    }


    public function getDateTimeNow(){
        $date = date('Y-m-d H:i:s');
        return $date;
    }

    public function getDateNow(){
        $date = date('Y-m-d');
        return $date;
    }

    public function getTimeNow(){
        $date = date('H:i:s');
        return $date;
    }


    private function _getSemesterActive(){
        $data = $this->db->query('SELECT ay.*
                                            FROM db_academic.semester s
                                            LEFT JOIN db_academic.academic_years ay
                                            ON (s.ID = ay.SemesterID)
                                            WHERE s.Status = "1" LIMIT 1 ');
//        $data = $this->db->get_where('db_academic.semester', array('Status'=>'1'),1);
        return $data->result_array()[0];
    }

    public function __getKSM($db,$ProdiID,$NPM,$ClassOf){
        $dataSemester = $this->db->query('SELECT s.* FROM db_academic.semester s WHERE s.Year >= '.$ClassOf.' ORDER BY s.ID ASC')->result_array();

//        print_r($dataSemester);

        $result = [];
        $smt = 1;
        for($i=0;$i<count($dataSemester);$i++){

            if($dataSemester[$i]['ID']<13){
                $dataSchedule = $this->db->query('SELECT zc.*,sp.TypeSchedule, mk.MKCode, mk.Name AS MKName, mk.NameEng AS MKNameEng, 
                                                            cd.TotalSKS AS Credit, em.Name AS Lecturer, sp.TransferCourse
                                                            FROM '.$db.'.study_planning sp 
                                                            LEFT JOIN db_academic.z_schedule zc ON (zc.Glue = sp.Glue) 
                                                            LEFT JOIN db_employees.employees em ON (em.NIP = zc.NIP)
                                                            LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sp.CDID)
                                                            LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sp.MKID)
                                                            WHERE sp.NPM = "'.$NPM.'" 
                                                            AND sp.SemesterID = "'.$dataSemester[$i]['ID'].'"                                                             
                                                            GROUP BY mk.MKCode
                                                            ORDER BY mk.MKCode ASC
                                                            ')->result_array();

                if(count($dataSchedule)>0){

                    for($s=0;$s<count($dataSchedule);$s++){
                        $dataDateTime = [];
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

                        }
                        $dataSchedule[$s]['DetailDateSchedule'] = $dataDateTime;
                    }

                }
                else {
                    $dataSchedule = [];
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
            else {

                $data = $this->db->query('SELECT sp.ScheduleID,sp.TypeSchedule,mk.MKCode,mk.Name AS MKName,mk.nameEng AS MKNameEng,cd.TotalSKS AS Credit,
                                                sp.StatusSystem,sc.ClassGroup, sc.TeamTeaching,
                                                em.NIP,em.Name,em.TitleAhead, em.TitleBehind, em.EmailPU, sp.TransferCourse
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

                        $dataSchedule = $this->db->query('SELECT sd.ID AS SDID, sd.StartSessions,sd.EndSessions,cl.Room,d.Name AS Day, d.NameEng AS DayEng 
                                                                      FROM db_academic.schedule_details sd
                                                                      LEFT JOIN db_academic.classroom cl ON (cl.ID=sd.ClassroomID)
                                                                      LEFT JOIN db_academic.days d ON (d.ID=sd.DayID)
                                                                      WHERE sd.ScheduleID = "'.$data[$sc]['ScheduleID'].'"
                                                                       ORDER BY d.ID ASC')->result_array();

                        $dataGrade = $this->db->query('SELECT * FROM db_academic.grade_course gc 
                                                            WHERE gc.ScheduleID = "'.$data[$sc]['ScheduleID'].'"
                                                             ')->result_array();

                        $meeting = 0;
                        $Totalpresen = 0;
                        // Get Attendance
                        if(count($dataSchedule)>0){

                            for($sds=0;$sds<count($dataSchedule);$sds++){
                                $dataAttd = $this->db->query('SELECT attd_s.* FROM db_academic.attendance_students attd_s 
                                                          LEFT JOIN db_academic.attendance attd ON (attd.ID = attd_s.ID_Attd)
                                                          WHERE attd.SemesterID = "'.$dataSemester[$i]['ID'].'" 
                                                          AND attd.ScheduleID = "'.$data[$sc]['ScheduleID'].'"
                                                          AND attd.ScheduleID = "'.$data[$sc]['ScheduleID'].'"
                                                          AND attd.SDID = "'.$dataSchedule[$sds]['SDID'].'"
                                                           AND attd_s.NPM = "'.$NPM.'" ')->result_array();

                                            if(count($dataAttd)>0){
                                                $presen = 0;
                                                $ArrPresensi = [];
                                                for($m=1;$m<=14;$m++){
                                                    $meeting += 1;
                                                    if($dataAttd[0]['M'.$m]=='1'){
                                                        $presen += 1;
                                                        $Totalpresen += 1;
                                                    }
                                                    array_push($ArrPresensi,$dataAttd[0]['M'.$m]);
                                                }


                                    $dataSchedule[$sds]['Presensi'] = $presen;
                                    $dataSchedule[$sds]['AttendanceStudentDetails'] = $ArrPresensi;
                                }
                            }

                        }

                        $data[$sc]['GradeCourse'] = $dataGrade;
                        $data[$sc]['Schedule'] = $dataSchedule;

                        // Menghitung preseni
                        $PresensiArg = ($Totalpresen==0) ? 0 : ($Totalpresen/$meeting) * 100;
                        $data[$sc]['AttendanceStudent'] = $PresensiArg;

                        $data[$sc]['TeamTeachingDetails'] = [];


                        if($data[$sc]['TeamTeaching']==1){
                            $dataTT = $this->db->query('SELECT e.NIP,e.Name,e.TitleAhead, e.TitleBehind FROM db_academic.schedule_team_teaching stt 
                                                          LEFT JOIN db_employees.employees e ON (e.NIP = stt.NIP) WHERE stt.ScheduleID = "'.$data[$sc]['ScheduleID'].'" ')->result_array();
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

    public function __getExamScheduleForStudent($db,$SemesterID,$NPM,$ClassOf,$ExamType){
        $dataSemester = $this->db->query('SELECT s.*, ay.utsStart, ay.utsEnd, ay.uasStart, ay.uasEnd FROM db_academic.semester s 
                                                        LEFT JOIN db_academic.academic_years ay ON (ay.SemesterID = s.ID)
                                                        WHERE s.ID = '.$SemesterID.' 
                                                        ORDER BY s.ID ASC')->result_array();

        // Get setting exam
        $dataExamSetting = $this->db->get('db_academic.exam_setting')->result_array();

        $result = [];
        for($i=0;$i<count($dataSemester);$i++){

            $Semester = $this->checkSemesterByClassOf($ClassOf,$SemesterID);

            if($dataSemester[$i]['ID']>=13) {

                $checkBPP = true;
                $checkCredit = true;

                // Cek attendance
                $checkAttendance = false;
                $checkAttendanceValue = 0;

                // Cek apakah sudah masuk pada periode ujian atau belum (UTS / UAS)
                $checkDateExam = true;
                $dateExamStart = ($ExamType=='uts' || $ExamType=='UTS')
                    ? $dataSemester[$i]['utsStart']
                    : $dataSemester[$i]['uasStart'];


                if($ExamType=='uts' || $ExamType=='UTS'){
                    // Cek Setting
                    if(count($dataExamSetting)>0){
                        $ExamSetting = $dataExamSetting[0];

                        if($Semester>1|| $Semester>'1'){
                            // Apakah ada setting pengecekan pembayaran BPP
                            if($ExamSetting['UTSPaymentBPP']=='1' || $ExamSetting['UTSPaymentBPP']==1){
                                $datacheckBPP = $this->checkBPPPayment($NPM,$dataSemester[$i]['ID']);
                                if($datacheckBPP['Status']==1){
                                    $checkBPP = true;
                                } else {
                                    $checkBPP = false;
                                }
                            }

                            // Apakah ada setting pengecekan pembayaran credit
                            if($ExamSetting['UTSPaymentCredit']=='1' || $ExamSetting['UTSPaymentCredit']==1){
                                $datacheckCredit = $this->checkCreditPayment($NPM,$dataSemester[$i]['ID']);
                                if($datacheckCredit['Status']==1){
                                    $checkCredit = true;
                                } else {
                                    $checkCredit = false;
                                }
                            }
                        }


                        if($ExamSetting['UTSAttd']==1 || $ExamSetting['UTSAttd']=='1'){
                            $checkAttendance = true;
                            $checkAttendanceValue = $ExamSetting['UTSAttdValue'];
                        }

                    }
                }

                else if($ExamType=='uas' || $ExamType=='UAS'){
                    if(count($dataExamSetting)>0){
                        $ExamSetting = $dataExamSetting[0];

                        if($Semester>1|| $Semester>'1'){
                            // Apakah ada setting pengecekan pembayaran BPP
                            if($ExamSetting['UASPaymentBPP']=='1' || $ExamSetting['UASPaymentBPP']==1){
                                $datacheckBPP = $this->checkBPPPayment($NPM,$dataSemester[$i]['ID']);
                                if($datacheckBPP['Status']==1){
                                    $checkBPP = true;
                                } else {
                                    $checkBPP = false;
                                }
                            }

                            // Apakah ada setting pengecekan pembayaran credit
                            if($ExamSetting['UASPaymentCredit']=='1' || $ExamSetting['UASPaymentCredit']==1){
                                $datacheckCredit = $this->checkCreditPayment($NPM,$dataSemester[$i]['ID']);
                                if($datacheckCredit['Status']==1){
                                    $checkCredit = true;
                                } else {
                                    $checkCredit = false;
                                }
                            }
                        }

                        if($ExamSetting['UASAttd']==1 || $ExamSetting['UASAttd']=='1'){
                            $checkAttendance = true;
                            $checkAttendanceValue = $ExamSetting['UTSAttdValue'];
                        }


                    }
                }

                if($ExamType=='uts' || $ExamType=='UTS' || $ExamType=='uas' || $ExamType=='UAS')
                {

                    $day = 7;
                    if(count($dataExamSetting)>0) {
                        $ExamSetting = $dataExamSetting[0];
                        $day = ($ExamType=='uts' || $ExamType=='UTS')
                            ? $ExamSetting['UTSShown']
                            : $ExamSetting['UASShown'];
                    }

                    $dateShow = date('Y-m-d',strtotime($this->getDateNow().'+ '.$day.' days'));
                    $AvailableDate = date('Y-m-d',strtotime($dateExamStart.'- '.$day.' days'));

                    if($dateExamStart > $dateShow){
                        $checkDateExam = false;
                    }

                }



                if($checkBPP && $checkCredit && $checkDateExam){
                    $ExamSchedule = $this->getDetailsScheduleExam($db,$NPM,$dataSemester[$i]['ID'],$ExamType);

                    // Status (StatusExam)
                    // 1 = tidak ada masalah
                    // -1 = attendance tidak memenuhi

                    $detailExam = [];

                    if(count($ExamSchedule)>0){
                        for ($i=0;$i<count($ExamSchedule);$i++){
                            $ExamSchedule[$i]['StatusExam'] = 1;
                            $dc = $ExamSchedule[$i];

                            if($checkAttendance && ($dc['Attendance']==1 || $dc['Attendance']=='1')){
                                if($dc['AttendancePercentage']<$checkAttendanceValue){
                                    $ExamSchedule[$i]['ExamDate'] = '';
                                    $ExamSchedule[$i]['ExamStart'] = '';
                                    $ExamSchedule[$i]['ExamEnd'] = '';
                                    $ExamSchedule[$i]['Room'] = '';
                                    $ExamSchedule[$i]['StatusExam'] = -1;
                                }
                            }

                            array_push($detailExam,$ExamSchedule[$i]);

                        }
                    }


                    $result = array(
                        'Status' => 1,
                        'ExamSchedule' => $detailExam
                    );

                }
                else if($checkBPP==false){
                    $result = array(
                        'Status' => -1,
                        'Message' => 'BPP Payment Unpaid'
                    );
                }
                else if($checkCredit==false){
                    $result = array(
                        'Status' => -2,
                        'Message' => 'Credit Payment Unpaid'
                    );
                }
                else if($checkDateExam==false){
                    $result = array(
                        'Status' => -3,
                        'Message' => 'The exam is out of date',
                        'AvailableDate' => $AvailableDate
                    );
                }

            }
            else {
                $result = array(
                    'Status' => -4,
                    'Message' => 'Schedule Exam Not Available'
                );
            }

        }

        return $result;

    }

    public function getListStudentExam($ExamID,$order=''){

        $orderby = 'ORDER BY exd.NPM ASC';

        if($order!=''){
            $orderby = 'ORDER BY '.$order;
        }

        $dataStudents = $this->db->query('SELECT exd.ID AS EXDID, exd.ExamID, exd.ScheduleID, exd.DB_Students, exd.Status, exd.NPM, auts.Name, 
                                                    ex.Type AS ExamType, ex.SemesterID, auts.Year, s.Attendance FROM db_academic.exam_details exd
                                                    LEFT JOIN db_academic.exam ex ON (ex.ID = exd.ExamID)
                                                    LEFT JOIN db_academic.auth_students auts ON (exd.NPM = auts.NPM)
                                                    LEFT JOIN db_academic.schedule s ON (s.ID = exd.ScheduleID) 
                                                    WHERE exd.ExamID = "'.$ExamID.'" '.$orderby)->result_array();

        // Get setting exam
        $dataExamSetting = $this->db->get('db_academic.exam_setting')->result_array();


        if(count($dataStudents)>0){

            $students = [];

            for ($i=0;$i<count($dataStudents) ;$i++){

                $item = $dataStudents[$i];

                // Get Semester Student


                $checkBPP = true;
                $checkCredit = true;

                // Cek attendance
                $checkAttendance = false;
                $checkAttendanceValue = 0;

                $ExamType = $item['ExamType'];
                $NPM = $item['NPM'];
                $SemesterID = $item['SemesterID'];

                $Semester = $this->checkSemesterStudent($item['Year']);

                if($ExamType=='uts' || $ExamType=='UTS'){
                    // Cek Setting
                    if(count($dataExamSetting)>0){
                        $ExamSetting = $dataExamSetting[0];

                        if($Semester>1|| $Semester>'1'){
                            // Apakah ada setting pengecekan pembayaran BPP
                            if($ExamSetting['UTSPaymentBPP']=='1' || $ExamSetting['UTSPaymentBPP']==1){
                                $datacheckBPP = $this->checkBPPPayment($NPM,$SemesterID);
                                if($datacheckBPP['Status']==1){
                                    $checkBPP = true;
                                } else {
                                    $checkBPP = false;
                                }
                            }

                            // Apakah ada setting pengecekan pembayaran credit
                            if($ExamSetting['UTSPaymentCredit']=='1' || $ExamSetting['UTSPaymentCredit']==1){
                                $datacheckCredit = $this->checkCreditPayment($NPM,$SemesterID);
                                if($datacheckCredit['Status']==1){
                                    $checkCredit = true;
                                } else {
                                    $checkCredit = false;
                                }
                            }
                        }


                        if($ExamSetting['UTSAttd']==1 || $ExamSetting['UTSAttd']=='1'){
                            $checkAttendance = true;
                            $checkAttendanceValue = $ExamSetting['UTSAttdValue'];
                        }

                    }
                }
                else if($ExamType=='uas' || $ExamType=='UAS'){
                    if(count($dataExamSetting)>0){
                        $ExamSetting = $dataExamSetting[0];

                        if($Semester>1|| $Semester>'1'){
                            // Apakah ada setting pengecekan pembayaran BPP
                            if($ExamSetting['UASPaymentBPP']=='1' || $ExamSetting['UASPaymentBPP']==1){
                                $datacheckBPP = $this->checkBPPPayment($NPM,$SemesterID);
                                if($datacheckBPP['Status']==1){
                                    $checkBPP = true;
                                } else {
                                    $checkBPP = false;
                                }
                            }

                            // Apakah ada setting pengecekan pembayaran credit
                            if($ExamSetting['UASPaymentCredit']=='1' || $ExamSetting['UASPaymentCredit']==1){
                                $datacheckCredit = $this->checkCreditPayment($NPM,$SemesterID);
                                if($datacheckCredit['Status']==1){
                                    $checkCredit = true;
                                } else {
                                    $checkCredit = false;
                                }
                            }
                        }

                        if($ExamSetting['UASAttd']==1 || $ExamSetting['UASAttd']=='1'){
                            $checkAttendance = true;
                            $checkAttendanceValue = $ExamSetting['UTSAttdValue'];
                        }


                    }
                }

                if($checkBPP && $checkCredit){

                    if($checkAttendance && ($item['Attendance']==1 || $item['Attendance']=='1')){
                        // Cek Attendance
                        $attd = $this->checkPercentageAttendance($NPM,$item['ScheduleID']);
                        if($attd>=$checkAttendanceValue){
                            array_push($students,$item);
                        }

                    } else {
                        array_push($students,$item);
                    }


                }

            }

            $result = array(
                'Status' => 1,
                'Message' => 'Student not yet',
                'DetailStudents' => $students
            );
        } else {
            $result = array(
                'Status' => -1,
                'Message' => 'Student not yet'
            );
        }

        return $result;

    }

    public function checkSemesterStudent($Year){

        $data = $this->db->query('SELECT Status FROM db_academic.semester WHERE Year >= "'.$Year.'" ')->result_array();

        $Semester = 0;
        if(count($data)>0){
            foreach ($data AS $item){
                if($item['Status']==0 || $item['Status']=='0'){
                    $Semester = $Semester + 1;
                } else {
                    $Semester = $Semester + 1;
                    break;
                }
            }
        }

        return $Semester;

    }

    public function checkSemesterByClassOf($ClassOf,$SemesterID){
        $dataSemester = $this->db->query('SELECT s.* FROM db_academic.semester s
                                                        WHERE s.Year >= "'.$ClassOf.'" 
                                                        AND s.id <= "'.$SemesterID.'"
                                                        ORDER BY s.ID ASC')->result_array();


        $smt_now = count($dataSemester);

        return $smt_now;
    }

    public function checkPayment($NPM,$SemesterID){
        // BPP
        $dataBpp = $this->db->select('Status')->get_where('db_finance.payment',
            array('NPM'=>$NPM,'PTID' => 2, 'SemesterID' => $SemesterID),1)->result_array();

        if(count($dataBpp)>0){
            if($dataBpp[0]['Status']=='1' || $dataBpp[0]['Status']==1){
                $StatusBPP = 1;
                $MessageBPP = 'BPP payment Paid';
            } else {
                $StatusBPP = 0;
                $MessageBPP = 'BPP payment Unpaid';
            }
        } else {
            $StatusBPP = -1;
            $MessageBPP = 'BPP payment unset, please contact academic service';
        }


        // Credit
        $dataSKS = $this->db->select('Status')->get_where('db_finance.payment',
            array('NPM'=>$NPM,'PTID' => 3, 'SemesterID' => $SemesterID),1)->result_array();
        if(count($dataSKS)>0){
            if($dataSKS[0]['Status']=='1' || $dataSKS[0]['Status']==1){
                $StatusCredit = 1;
                $MessageCredit = 'Credit payment Paid';
            }
            else {
                $StatusCredit = 0;
                $MessageCredit = 'Credit payment Unpaid';
            }
        }
        else {
            $StatusCredit = -1;
            $MessageCredit = 'Credit payment unset, please contact academic service';
        }

        $result = array(
            'BPP' => array('Status'=>$StatusBPP, 'Message' => $MessageBPP),
            'Credit' => array('Status' => $StatusCredit, 'Message' => $MessageCredit)
        );

        return $result;
    }

    public function checkBPPPayment($NPM,$SemesterID){
        // BPP
        $dataBpp = $this->db->select('Status')->get_where('db_finance.payment',
            array('NPM'=>$NPM,'PTID' => 2, 'SemesterID' => $SemesterID),1)->result_array();

        if(count($dataBpp)>0){
            if($dataBpp[0]['Status']=='1' || $dataBpp[0]['Status']==1){
                $StatusBPP = 1;
                $MessageBPP = 'BPP payment Paid';
            } else {
                $StatusBPP = 0;
                $MessageBPP = 'BPP payment Unpaid';
            }
        } else {
            $StatusBPP = -1;
            $MessageBPP = 'BPP payment unset, please contact academic service';
        }

        return array('Status'=>$StatusBPP, 'Message' => $MessageBPP);
    }

    public function checkCreditPayment($NPM,$SemesterID){
        // Credit
        $dataSKS = $this->db->select('Status')->get_where('db_finance.payment',
            array('NPM'=>$NPM,'PTID' => 3, 'SemesterID' => $SemesterID),1)->result_array();
        if(count($dataSKS)>0){
            if($dataSKS[0]['Status']=='1' || $dataSKS[0]['Status']==1){
                $StatusCredit = 1;
                $MessageCredit = 'Credit payment Paid';
            }
            else {
                $StatusCredit = 0;
                $MessageCredit = 'Credit payment Unpaid';
            }
        }
        else {
            $StatusCredit = -1;
            $MessageCredit = 'Credit payment unset, please contact academic service';
        }

        return array('Status' => $StatusCredit, 'Message' => $MessageCredit);
    }

    public function checkPercentageAttendance($NPM,$ScheduleID){

        $dataSD = $this->db->select('ID')
            ->get_where('db_academic.schedule_details'
            ,array('ScheduleID' => $ScheduleID))->result_array();

        $arrDataAttd = [];
        for($t=0;$t<count($dataSD);$t++){
            // Get Attendance
            $dataAttd = $this->db->query('SELECT attd_s.* FROM db_academic.attendance_students attd_s 
                                                          LEFT JOIN db_academic.attendance attd ON (attd.ID = attd_s.ID_Attd)
                                                          WHERE attd.ScheduleID = "'.$ScheduleID.'"
                                                          AND attd.SDID = "'.$dataSD[$t]['ID'].'"
                                                           AND attd_s.NPM = "'.$NPM.'" ')->result_array();
            array_push($arrDataAttd,$dataAttd);
        }

        $result = 0;

        // Count Attendance
        if(count($arrDataAttd)>0){
            $meeting = 0;
            $Totalpresen = 0;
            for($a=0;$a<count($arrDataAttd);$a++){
                $dataAttd = $arrDataAttd[$a];
                for($m=1;$m<=14;$m++){
                    $meeting += 1;
                    if($dataAttd[0]['M'.$m]=='1'){
                        $Totalpresen += 1;
                    }
                }

            }

            $PresensiArg = ($Totalpresen==0) ? 0 : ($Totalpresen/$meeting) * 100;
            $result = round($PresensiArg);
        }

        return $result;


    }

    public function getDetailsScheduleExam($db,$NPM,$SemesterID,$ExamType){
        // Get data jadwal

        $q = 'SELECT sc.ID AS ScheduleID, mk.MKCode, mk.Name AS Course, mk.NameEng AS CourseEng, ex.ExamDate, ex.ExamStart, ex.ExamEnd, cl.Room,  
                                                                    sc.ClassGroup, sc.Attendance
                                                                    FROM '.$db.'.study_planning sp
                                                                    LEFT JOIN db_academic.exam_details exd ON (exd.ScheduleID = sp.ScheduleID AND exd.NPM = sp.NPM)
                                                                    LEFT JOIN db_academic.exam ex ON (ex.ID = exd.ExamID)
                                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID = ex.ExamClassroomID)
                                                                    LEFT JOIN db_academic.schedule sc ON (sc.ID = sp.ScheduleID)
                                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sp.MKID)
                                                                    WHERE sp.SemesterID = "'.$SemesterID.'" 
                                                                    AND ex.Type LIKE "'.$ExamType.'"
                                                                    AND sp.NPM = "'.$NPM.'"
                                                                    GROUP BY ex.ID
                                                                    ORDER BY mk.MKCode ASC';

        $ExamSchedule = $this->db->query($q)->result_array();

        if(count($ExamSchedule)>0){
            for($g=0;$g<count($ExamSchedule);$g++){

                $examD = $ExamSchedule[$g];

                // Get Schedule Detail
                $dataSD = $this->db->select('ID')->get_where('db_academic.schedule_details',array('ScheduleID' => $examD['ScheduleID']))->result_array();

                $arrDataAttd = [];
                for($t=0;$t<count($dataSD);$t++){
                    // Get Attendance
                    $dataAttd = $this->db->query('SELECT attd_s.* FROM db_academic.attendance_students attd_s 
                                                          LEFT JOIN db_academic.attendance attd ON (attd.ID = attd_s.ID_Attd)
                                                          WHERE attd.SemesterID = "'.$SemesterID.'" 
                                                          AND attd.ScheduleID = "'.$examD['ScheduleID'].'"
                                                          AND attd.SDID = "'.$dataSD[$t]['ID'].'"
                                                           AND attd_s.NPM = "'.$NPM.'" ')->result_array();
                    array_push($arrDataAttd,$dataAttd);
                }


                if(count($arrDataAttd)>0){
                    $meeting = 0;
                    $Totalpresen = 0;
                    for($a=0;$a<count($arrDataAttd);$a++){
                        $dataAttd = $arrDataAttd[$a];
                        for($m=1;$m<=14;$m++){
                            $meeting += 1;
                            if($dataAttd[0]['M'.$m]=='1'){
                                $Totalpresen += 1;
                            }
                        }

                    }

                    $PresensiArg = ($Totalpresen==0) ? 0 : ($Totalpresen/$meeting) * 100;
                    $ExamSchedule[$g]['AttendancePercentage'] = round($PresensiArg);

                    // UAS
//                    if($ExamType=='uas' || $ExamType=='UAS'){
//                        if($PresensiArg<75 && $ExamSchedule[$g]['Attendance']=='1'){
//                            $ExamSchedule[$g]['ExamDate'] = null;
//                            $ExamSchedule[$g]['ExamEnd'] = null;
//                            $ExamSchedule[$g]['ExamStart'] = null;
//                            $ExamSchedule[$g]['Room'] = null;
//                        }
//                    }
                }
            }
        }

        return $ExamSchedule;
    }

    public function getAttendanceStudent($NPM,$ScheduleID){
        $dataSD = $this->db->query('SELECT sd.ID AS SDID, s.SemesterID FROM db_academic.schedule_details sd 
                                              LEFT JOIN db_academic.schedule s ON (s.ID = sd.ScheduleID)
                                              WHERE s.ID = "'.$ScheduleID.'" ')->result_array();


        $arrDataAttd = [];
        if(count($dataSD)>0){
            for($s=0;$s<count($dataSD);$s++){

                // Get Attendance
                $dataAttd = $this->db->query('SELECT attd_s.* FROM db_academic.attendance_students attd_s 
                                                          LEFT JOIN db_academic.attendance attd ON (attd.ID = attd_s.ID_Attd)
                                                          WHERE attd.SemesterID = "'.$dataSD[$s]['SemesterID'].'" 
                                                          AND attd.ScheduleID = "'.$ScheduleID.'"
                                                          AND attd.SDID = "'.$dataSD[$s]['SDID'].'"
                                                           AND attd_s.NPM = "'.$NPM.'" ')->result_array();

                array_push($arrDataAttd,$dataAttd);
            }
        }

        $meeting = 0;
        $Totalpresen = 0;
        $Percentage = 0;

        if(count($arrDataAttd)>0){

            for($a=0;$a<count($arrDataAttd);$a++){
                $dataAttd = $arrDataAttd[$a];
                for($m=1;$m<=14;$m++){
                    $meeting += 1;
                    if($dataAttd[0]['M'.$m]=='1'){
                        $Totalpresen += 1;
                    }
                }

            }

            $Percentage = ($Totalpresen==0) ? 0 : ($Totalpresen/$meeting) * 100;
        }

        $result = array(
            'Session' => $meeting,
            'TotalPresent' => $Totalpresen,
            'Percentage' => $Percentage
        );

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
        $SemesterID = $SemesterActive['SemesterID'];

        $dataSemester = $this->db->query('SELECT s.* FROM db_academic.semester s ORDER BY s.ID ASC')->result_array();

//        print_r($dataSemester);
//        exit;

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

                $TeamTheaching = $this->db->query('SELECT s.*,em.Name AS CoordinatorName, stt.Status AS StatusTeamTeaching 
                                                        FROM db_academic.schedule_team_teaching stt 
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


            if($dataSemester[$i]['Status']==1 || $dataSemester[$i]['Status']=='1'){
                break;
            }
        }

        return $result;

    }

    private function getDetailTimeTable($dataSch,$param){

        if(count($dataSch)){
            $this->load->model('m_api');
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
//                $dataSch[$s]['Students'] = $this->m_api->getStudentByScheduleID($dataSch[$s]['SemesterID'],$dataSch[$s]['ID'],'');
                $dataSch[$s]['TotalStudents'] = $this->m_api->getTotalStdPerDay($dataSch[$s]['SemesterID'],$dataSch[$s]['ID'],'');

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

    public function __getStudentsDetails($SemesterID,$ScheduleID){

        $this->load->model('m_api');
        $dataCl = $this->m_api->getClassOf();

        $arrDataStd = [];
        if(count($dataCl)>0){
            for($i=0;$i<count($dataCl);$i++){
                $db_ = 'ta_'.$dataCl[$i]['Year'];

                $data = $this->db->query('SELECT s.NPM, s.Name, sp.Evaluasi1, sp.Evaluasi2, 
                                                    sp.Evaluasi3, sp.Evaluasi4, sp.Evaluasi5, sp.UTS, sp.UAS,
                                                    sp.Score, sp.Grade, sp.Approval 
                                                    FROM '.$db_.'.study_planning sp 
                                                    LEFT JOIN '.$db_.'.students s ON (s.NPM = sp.NPM)
                                                    WHERE sp.SemesterID ="'.$SemesterID.'" 
                                                    AND sp.ScheduleID = "'.$ScheduleID.'"
                                                    ORDER BY s.NPM ASC
                                                     ')->result_array();

                if(count($data)>0){
                    for($s=0;$s<count($data);$s++){
                        array_push($arrDataStd,$data[$s]);
                    }
                }

            }
        }


        // Get Total Assigment Aktif
        $dataAssg = $this->db->select('ID AS ScheduleID, ClassGroup, TotalAssigment')->get_where('db_academic.schedule',array(
            'SemesterID' => $SemesterID,
            'ID' => $ScheduleID
        ),1)->result_array();

        $dataGrade = $this->db->get_where('db_academic.grade_course',array(
            'SemesterID' => $SemesterID,
            'ScheduleID' => $ScheduleID
        ),1)->result_array();;


        if(count($dataAssg)>0){
            $dataAssg[0]['DetailStudent'] = $arrDataStd;
            $dataAssg[0]['Weightages'] = $dataGrade;
        }



        return $dataAssg;
    }

    public function __getStudentByScheduleID($SemesterID,$ScheduleID){
        $this->load->model('m_api');
        $dataCl = $this->m_api->getClassOf();

        $arrDataStd = [];
        if(count($dataCl)>0){
            for($i=0;$i<count($dataCl);$i++){
                $db_ = 'ta_'.$dataCl[$i]['Year'];

                $data = $this->db->query('SELECT s.NPM, s.Name, sp.Evaluasi1, sp.Evaluasi2, 
                                                    sp.Evaluasi3, sp.Evaluasi4, sp.Evaluasi5, sp.UTS, sp.UAS,
                                                    sp.Score, sp.Grade, sp.Approval 
                                                    FROM '.$db_.'.study_planning sp 
                                                    LEFT JOIN '.$db_.'.students s ON (s.NPM = sp.NPM)
                                                    WHERE sp.SemesterID ="'.$SemesterID.'" 
                                                    AND sp.ScheduleID = "'.$ScheduleID.'"
                                                    ORDER BY s.NPM ASC
                                                     ')->result_array();

                if(count($data)>0){
                    for($s=0;$s<count($data);$s++){
                        array_push($arrDataStd,$data[$s]);
                    }
                }

            }
        }

        return $arrDataStd;
    }


    public function __getExamSchedule($NIP,$Type)
    {

        $SemesterActive = $this->_getSemesterActive();
        $SemesterID = $SemesterActive['SemesterID'];

        $dataSemester = $this->db->query('SELECT s.ID, s.ProgramCampusID, s.Name, s.Status, ay.utsStart, ay.utsEnd, ay.uasStart, ay.uasEnd FROM db_academic.semester s 
                                                      LEFT JOIN db_academic.academic_years ay ON (ay.SemesterID = s.ID)
                                                      ORDER BY s.ID ASC')->result_array();

        $result = [];
        for($i=0;$i<count($dataSemester);$i++){
            if($dataSemester[$i]['ID']<13){

                $arr_p = array(
                    'DataSemester' => $dataSemester[$i],
                    'SemesterID' => $dataSemester[$i]['ID'],
                    'Semester' => $dataSemester[$i]['Name'],
                    'Status' => $dataSemester[$i]['Status'],
                    'DataExamSchedule' => []
                );

                array_push($result,$arr_p);

            }
            // Sistem Baru
            else {

                $Coordinator = $this->db->query('SELECT s.ID,s.ClassGroup, mk.NameEng AS CourseEng, mk.MKCode
                                              FROM db_academic.schedule s
                                              LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                              LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                              LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                              WHERE s.SemesterID = "'.$dataSemester[$i]['ID'].'" 
                                              AND s.Coordinator = "'.$NIP.'" AND s.IsSemesterAntara = "0"
                                               GROUP BY s.ID')->result_array();

                $TeamTheaching = $this->db->query('SELECT s.ID,s.ClassGroup, mk.NameEng AS CourseEng, mk.MKCode 
                                                        FROM db_academic.schedule_team_teaching stt 
                                                        LEFT JOIN db_academic.schedule s ON (s.ID=stt.ScheduleID)
                                                        LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                      LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                                      LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                        WHERE s.SemesterID ="'.$SemesterID.'" 
                                                        AND stt.NIP = "'.$NIP.'"
                                                        AND s.IsSemesterAntara = "0" ')->result_array();

                $dataCourse = $Coordinator;
                if(count($TeamTheaching)>0){
                    for($t=0;$t<count($TeamTheaching);$t++){
                        array_push($dataCourse,$TeamTheaching[$t]);
                    }
                }

                // Load Exam
                if(count($dataCourse)>0){
                    for($r=0;$r<count($dataCourse);$r++){
                        $d = $dataCourse[$r];
                        $dataExam = $this->db->query('SELECT ex.Type, ex.ExamDate, ex.ExamStart, ex.ExamEnd, cl.Room
                                                                , em1.Name AS P_Name1, em2.Name AS P_Name2
                                                                FROM db_academic.exam_group exg
                                                                LEFT JOIN db_academic.exam ex ON (ex.ID = exg.ExamID)
                                                                LEFT JOIN db_academic.classroom cl ON (cl.ID = ex.ExamClassroomID)
                                                                LEFT JOIN db_employees.employees em1 ON (em1.NIP = ex.Pengawas1)
                                                                LEFT JOIN db_employees.employees em2 ON (em2.NIP = ex.Pengawas2)
                                                                WHERE exg.ScheduleID = "'.$d['ID'].'" AND ex.Type = "'.$Type.'"
                                                                 ORDER BY ExamDate, ExamStart, ExamEnd ASC')->result_array();
                        $dataCourse[$r]['ExamSchedule'] = $dataExam;
                    }
                }


                $arr_p = array(
                    'DataSemester' => $dataSemester[$i],
                    'SemesterID' => $dataSemester[$i]['ID'],
                    'Semester' => $dataSemester[$i]['Name'],
                    'Status' => $dataSemester[$i]['Status'],
                    'DataExamSchedule' => $dataCourse
                );

                array_push($result,$arr_p);

            }
        }

        return $result;

    }

    public function __getExamSchedule4Lecturer($SemesterID,$NIP,$ExamType){

        $dataSemester = $this->db->query('SELECT s.*, ay.utsStart, ay.utsEnd, ay.uasStart, ay.uasEnd FROM db_academic.semester s 
                                                        LEFT JOIN db_academic.academic_years ay ON (ay.SemesterID = s.ID)
                                                        WHERE s.ID = '.$SemesterID.' 
                                                        ORDER BY s.ID ASC')->result_array();

        // Get setting exam
        $dataExamSetting = $this->db->get('db_academic.exam_setting')->result_array();

        if($SemesterID>=13){

            $i = 0;

            // Cek apakah sudah masuk pada periode ujian atau belum (UTS / UAS)
            $checkDateExam = true;
            $dateExamStart = ($ExamType=='uts' || $ExamType=='UTS')
                ? $dataSemester[$i]['utsStart']
                : $dataSemester[$i]['uasStart'];

            if($ExamType=='uts' || $ExamType=='UTS' || $ExamType=='uas' || $ExamType=='UAS')
            {

                $day = 7;
                if(count($dataExamSetting)>0) {
                    $ExamSetting = $dataExamSetting[0];
                    $day = ($ExamType=='uts' || $ExamType=='UTS')
                        ? $ExamSetting['UTSShown']
                        : $ExamSetting['UASShown'];
                }

                $dateShow = date('Y-m-d',strtotime($this->getDateNow().'+ '.$day.' days'));
                $AvailableDate = date('Y-m-d',strtotime($dateExamStart.'- '.$day.' days'));

                if($dateExamStart > $dateShow){
                    $checkDateExam = false;
                }

            }

            if($checkDateExam){

                $Coordinator = $this->db->query('SELECT s.ID AS ScheduleID, s.ClassGroup, mk.NameEng AS CourseEng, mk.MKCode
                                              FROM db_academic.schedule s
                                              LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                              LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                              LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                              WHERE s.SemesterID = "'.$SemesterID.'" 
                                              AND s.Coordinator = "'.$NIP.'" AND s.IsSemesterAntara = "0"
                                               GROUP BY s.ID')->result_array();

                $TeamTheaching = $this->db->query('SELECT s.ID AS ScheduleID, s.ClassGroup, mk.NameEng AS CourseEng, mk.MKCode 
                                                        FROM db_academic.schedule_team_teaching stt 
                                                        LEFT JOIN db_academic.schedule s ON (s.ID=stt.ScheduleID)
                                                        LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                      LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                                      LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                        WHERE s.SemesterID ="'.$SemesterID.'" 
                                                        AND stt.NIP = "'.$NIP.'"
                                                        AND s.IsSemesterAntara = "0" ')->result_array();

                $dataCourse = $Coordinator;
                if(count($TeamTheaching)>0){
                    for($t=0;$t<count($TeamTheaching);$t++){
                        array_push($dataCourse,$TeamTheaching[$t]);
                    }
                }

                if(count($dataCourse)>0){
                    $i=0;
                    foreach ($dataCourse as $item){
                        $dataExam = $this->db->query('SELECT ex.*, cl.Room, em1.Name AS Inv1, em2.Name AS Inv2  
                                                                    FROM db_academic.exam ex
                                                                    LEFT JOIN db_academic.exam_group eg ON (eg.ExamID = ex.ID)
                                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID = ex.ExamClassroomID)
                                                                    LEFT JOIN db_employees.employees em1 ON (em1.NIP = ex.Pengawas1)
                                                                    LEFT JOIN db_employees.employees em2 ON (em2.NIP = ex.Pengawas2)
                                                                    WHERE ex.SemesterID = "'.$SemesterID.'" 
                                                                    AND ex.Type = "'.strtolower($ExamType).'" 
                                                                    AND eg.ScheduleID = "'.$item['ScheduleID'].'"
                                                                    GROUP BY ex.ID ')->result_array();

                        if(count($dataExam)>0){
                            for($s=0;$s<count($dataExam);$s++){
                                $dataStd = $this->db->get_where('db_academic.exam_details'
                                    ,array('ExamID' => $dataExam[$s]['ID']))->num_rows();

                                $dataExam[$s]['TotalStudent'] = $dataStd;
                            }
                        }


                        $dataCourse[$i]['ExamSchedule'] = $dataExam;
                        $i+=1;
                    }

                    $result = array(
                        'Status' => 1,
                        'DetailExam' => $dataCourse
                    );

                } else {
                    $result = array(
                        'Status' => -5,
                        'Message' => 'You don\'t have a teaching schedule'
                    );
                }



            } else {
                $result = array(
                    'Status' => -3,
                    'Message' => 'The exam is out of date',
                    'AvailableDate' => $AvailableDate
                );
            }

        } else {
            $result = array(
                'Status' => -4,
                'Message' => 'Schedule Exam Not Available'
            );
        }




        return $result;

    }

    public function getDetailStudyResultByNPM($ClassOf,$NPM){
        $order = 'ASC';

        $data = $this->db->query('SELECT s.*, ay.showNilai_H, ay.showNilai_T FROM db_academic.semester s 
                                            LEFT JOIN db_academic.academic_years ay ON (s.ID = ay.SemesterID)
                                            WHERE s.ID >= (SELECT s2.ID FROM db_academic.semester s2 
                                                                  WHERE s2.Year="'.$ClassOf.'" LIMIT 1) 
                                            ORDER BY s.ID '.$order)->result_array();

        $db = 'ta_'.$ClassOf;
        $smt = 1;
        $res = [];
        for($i=0;$i<count($data);$i++){

            $System = ($data[$i]['ID']>=13) ? 1 : 0;
            $khs = $this->getDataKHS($db,$NPM,$data[$i]['ID'],$data[$i]['Status'],$System);

//            if(count($khs)>0){
            $result[$i]['semester'] = $smt;
            $result[$i]['SemesterID'] = $data[$i]['ID'];
            $result[$i]['SemesterName'] = $data[$i]['Name'];
            $result[$i]['Show_H'] = $data[$i]['showNilai_H'];
            $result[$i]['Show_T'] = $data[$i]['showNilai_T'];
            $result[$i]['semesterDetail'] = $khs;

            array_push($res,$result[$i]);
//            }
            $smt += 1;
            if($data[$i]['Status']=='1' || $data[$i]['Status']==1){
                break;
            }

        }

        return $res;
    }

    public function getTranscript($ClassOf,$NPM,$order){


        $data = $this->db->query('SELECT s.* FROM db_academic.semester s WHERE s.ID >= (SELECT s2.ID FROM db_academic.semester s2 
                                        WHERE s2.Year="'.$ClassOf.'" LIMIT 1) ORDER BY s.ID '.$order)->result_array();

        $db = 'ta_'.$ClassOf;
        $dataSmtActive = $this->_getSemesterActive();

        $transcript = [];
        $arrTranscriptID = [];
        $dateNow = date("Y-m-d");
        $showNilaiSemesterActive = ($dataSmtActive['updateTranscript'] <= $dateNow) ? 1 : 0;

        $smt = ($order=='ASC') ? 0 : count($data) + 1;
        for($i=0;$i<count($data);$i++){

            $System = ($data[$i]['ID']>=13) ? 1 : 0;
            $khs = $this->getDataKHS($db,$NPM,$data[$i]['ID'],'',$System);

            if(count($khs)>0){
                $smt = ($order=='ASC') ? $smt + 1 : $smt - 1;

                // Cek apakah ada mata kuliha ngulang apa engga
                for($k=0;$k<count($khs);$k++){
                    $d = $khs[$k];
                    if($d['ShowTranscript']==1 && $d['ShowTranscript']=='1'){
                        // cek akaha sudah ada di list transcript atau belum, jika belim lanjutkan
                        if(in_array($d['MKID'],$arrTranscriptID)!=-1){

                            // cek apakah MKID punya lebih dari 1 jika maka ambil nilai tertingginya
                            if($showNilaiSemesterActive==1 || $showNilaiSemesterActive=='1'){
                                $dataScore = $this->db->order_by('Score', 'DESC')
                                    ->get_where($db.'.study_planning',array('NPM' => $NPM,'MKID'=>$d['MKID']))->result_array();
                            } else {
                                $dataScore = $this->db->order_by('Score', 'DESC')
                                    ->get_where($db.'.study_planning',array('NPM' => $NPM,'MKID'=>$d['MKID']
                                    ,'SemesterID !='=>$dataSmtActive['SemesterID']))->result_array();
                            }

                            if(count($dataScore)>0){
                                $Score = (isset($dataScore[0]['Score']) && $dataScore[0]['Score']!='' && $dataScore[0]['Score']!=null && $dataScore[0]['Score']!='-') ? $dataScore[0]['Score'] : 0;
                                $Grade = (isset($dataScore[0]['Grade']) && $dataScore[0]['Grade']!='' && $dataScore[0]['Grade']!=null && $dataScore[0]['Grade']!='-') ? $dataScore[0]['Grade'] : 'E';
                                $GradeValue = (isset($dataScore[0]['GradeValue']) && $dataScore[0]['GradeValue']!='' && $dataScore[0]['GradeValue']!=null && $dataScore[0]['GradeValue']!='-') ? $dataScore[0]['GradeValue'] : 0;

                                $arrTr = array(
                                    'MKID' => $d['MKID'],
                                    'MKCode' => $d['MKCode'],
                                    'Course' => $d['Name'],
                                    'CourseEng' => $d['NameEng'],
                                    'Credit' => $d['Credit'],
                                    'Score' => $Score,
                                    'Grade' => $Grade,
                                    'GradeValue' => $GradeValue,
                                    'Point' => ($d['Credit']* $GradeValue)
                                );
                                array_push($arrTranscriptID,$d['MKID']);
                                array_push($transcript,$arrTr);
                            }


                        }
                    }

                }


            }


        }


        return $transcript;
    }


    public function getDataKHS($db,$NPM,$SemesterID,$Status,$System){

        $data = $this->db->query('SELECT sp.*,mk.MKCode, mk.Name, mk.NameEng, s.TotalAssigment 
                                        FROM '.$db.'.study_planning sp 
                                        LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sp.CDID)
                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID) 
                                        LEFT JOIN db_academic.schedule s ON (s.ID = sp.ScheduleID)
                                        WHERE sp.NPM = "'.$NPM.'" 
                                        AND sp.SemesterID="'.$SemesterID.'" 
                                        AND sp.CDID IS NOT NULL 
                                        ORDER BY mk.MKCode ASC ')->result_array();

        $dateNow = date("Y-m-d");
        $showUTS = false;
        $showUAS = false;
        if($System=='1'){
            // cek tanggal show nilai UTS & UAS
            $dataAY_UTS = $this->db->query('SELECT * FROM db_academic.academic_years 
                            WHERE SemesterID = "'.$SemesterID.'" AND showNilaiUts <= "'.$dateNow.'" ')->result_array();
            if(count($dataAY_UTS)>0){
                $showUTS = true;
            }

            $dataAY_UAS = $this->db->query('SELECT * FROM db_academic.academic_years 
                            WHERE SemesterID = "'.$SemesterID.'" AND showNilaiUas <= "'.$dateNow.'" ')->result_array();

            if(count($dataAY_UAS)>0){
                $showUAS = true;
            }

        }



        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $dt = $data[$i];

                if($System==1){
                    if(($showUTS && $dt['Approval']=='1') ||($showUTS && $dt['Approval']=='2') || ($showUAS && $dt['Approval']=='2')){

                        for($d=1;$d<=5;$d++){
                            $n = '-';
                            if($d<=$dt['TotalAssigment'] && $dt['Evaluasi'.$d]!=null && $dt['Evaluasi'.$d]!=''){
                                $n = $data[$i]['Evaluasi'.$d];
                            }
                            $data[$i]['Evaluasi'.$d] = $n;
                        }

                        if($dt['UTS']==null || $dt['UTS']==''){
                            $data[$i]['UTS'] = 0;
                        }

                        // ======== UAS =========
                        if($showUAS && $dt['Approval']=='2'){
                            if($dt['UAS']==null || $dt['UAS']==''){
                                $data[$i]['UAS'] = 0;
                            }

                            if($dt['Score']==null || $dt['Score']==''){
                                $data[$i]['Score'] = 0;
                            }
                            if($dt['Grade']==null || $dt['Grade']==''){
                                $data[$i]['Grade'] = 'E';
                            }
                            if($dt['GradeValue']==null || $dt['GradeValue']==''){
                                $data[$i]['GradeValue'] = 0;
                            }

                        }
                        else {
                            $data[$i]['UAS'] = '-';
                            $data[$i]['Score'] = '-';
                            $data[$i]['Grade'] = '-';
                            $data[$i]['GradeValue'] = 0;
                        }
                        // ======== UAS =========

                    }

                    else {

                        $data[$i]['Evaluasi1'] = '-';
                        $data[$i]['Evaluasi2'] = '-';
                        $data[$i]['Evaluasi3'] = '-';
                        $data[$i]['Evaluasi4'] = '-';
                        $data[$i]['Evaluasi5'] = '-';
                        $data[$i]['UTS'] = '-';
                        $data[$i]['UAS'] = '-';
                        $data[$i]['Score'] = '-';
                        $data[$i]['Grade'] = '-';
                        $data[$i]['GradeValue'] = 0;

                    }
                }
                else {
                    if($dt['Evaluasi1']==null || $dt['Evaluasi1']==''){
                        $data[$i]['Evaluasi1'] = '-';
                    }
                    if($dt['Evaluasi2']==null || $dt['Evaluasi2']==''){
                        $data[$i]['Evaluasi2'] = '-';
                    }
                    if($dt['Evaluasi3']==null || $dt['Evaluasi3']==''){
                        $data[$i]['Evaluasi3'] = '-';
                    }
                    if($dt['Evaluasi4']==null || $dt['Evaluasi4']==''){
                        $data[$i]['Evaluasi4'] = '-';
                    }
                    if($dt['Evaluasi5']==null || $dt['Evaluasi5']==''){
                        $data[$i]['Evaluasi5'] = '-';
                    }

                    if($dt['UTS']==null || $dt['UTS']==''){
                        $data[$i]['UTS'] = '-';
                    }


                    if($dt['UAS']==null || $dt['UAS']==''){
                        $data[$i]['UAS'] = 0;
                    }
                    if($dt['Score']==null || $dt['Score']==''){
                        $data[$i]['Score'] = 0;
                    }
                    if($dt['Grade']==null || $dt['Grade']==''){
                        $data[$i]['Grade'] = 'E';
                    }
                    if($dt['GradeValue']==null || $dt['GradeValue']==''){
                        $data[$i]['GradeValue'] = 0;
                    }
                }

            }
        }

        return $data;
    }



    public function getTopicByUserID($UserID){

        $data = $this->db->query('SELECT cu.ReadComment, ct.* FROM db_academic.counseling_user cu
                                              LEFT JOIN db_academic.counseling_topic ct 
                                              ON (ct.ID = cu.TopicID)
                                              WHERE cu.UserID = "'.$UserID.'"
                                               ORDER BY cu.TopicID DESC')->result_array();

        return $data;

    }

    public function uploadDokumenMultiple($filename,$ggFiles = 'fileData',$path = './uploads/document' )
    {
        $output = array();
        // Count total files
        if (count($_FILES) > 0) {
            $countfiles = count($_FILES[$ggFiles ]['name']);
            // Looping all files
            for($i=0;$i<$countfiles;$i++){
                  $config = array();
                  if(!empty($_FILES[$ggFiles ]['name'][$i])){
            
                    // Define new $_FILES array - $_FILES['file']
                    $_FILES['file']['name'] = $_FILES[$ggFiles]['name'][$i];
                    $_FILES['file']['type'] = $_FILES[$ggFiles]['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES[$ggFiles]['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES[$ggFiles]['error'][$i];
                    $_FILES['file']['size'] = $_FILES[$ggFiles]['size'][$i];

                    // Set preference
                    $config['upload_path'] = $path.'/';
                    $config['allowed_types'] = '*';
                    $config['overwrite'] = TRUE; 
                    $no = $i + 1;
                    $config['file_name'] = $filename.'_'.$no;

                    $filenameUpload = $_FILES['file']['name'];
                    $ext = pathinfo($filenameUpload, PATHINFO_EXTENSION);

                    // $filenameNew = $filename.'_'.$no.'.pdf';
                    $filenameNew = $filename.'_'.$no.'_'.mt_rand().'.'.$ext;
                    // print_r($_FILES['file']['type']);

            
                    //Load upload library
                    $this->load->library('upload',$config); 
                    $this->upload->initialize($config);
            
                    // File upload
                    if($this->upload->do_upload('file')){
                      // Get data about the file
                      $uploadData = $this->upload->data();
                      $filePath = $uploadData['file_path'];
                      $filename_uploaded = $uploadData['file_name'];
                      // rename file
                      $old = $filePath.'/'.$filename_uploaded;
                      $new = $filePath.'/'.$filenameNew;

                      rename($old, $new);

                      $output[] = $filenameNew;
                    }
                  }
              }
        }
      
        return $output;
    }

    public function count_get_schedule_exchange_by_status($Status,$Semester)
    {
        $Status = ($Status =='') ? '' : ' where a.Status = "'.$Status.'"';
        $Semester = ($Semester =='') ? '' : ($Status =='') ? ' where b.SemesterID = "'.$Semester.'"' : ' and b.SemesterID = "'.$Semester.'"';
        $sql = 'select count(*) as total from db_academic.schedule_exchange as a left join db_academic.attendance as b 
                on a.ID_Attd = b.ID
                 '.$Status.$Semester;
        $query = $this->db->query($sql)->result_array();
        return $query[0]['total'];
    }

}
