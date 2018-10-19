<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_save_to_pdf extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('JWT');
        $this->load->library('pdf');
        $this->load->library('pdf_mc_table');

        $this->load->model('report/m_save_to_pdf');

        date_default_timezone_set("Asia/Jakarta");
        setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
    }

    private function getInputToken($token)
    {
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    // ==== PDF Schedule ====
    public function schedulePDF(){

        $token = $this->input->get('token');
        $data_arr = $this->getInputToken($token);




        $pdf = new FPDF('l','mm','A4');

        $data_sch = $this->m_save_to_pdf->getScheduleByDay($data_arr['SemesterID'],$data_arr['DayID'],date("Y-m-d"));

        if(count($data_sch)>0){

            $DayNameEng = $data_sch['DetailsSemester']['DayNameEng'];
            $SemesterDetails = $data_sch['DetailsSemester']['SemesterName'];

            $pdf->AddPage();
            $this->header_schedule($pdf,$SemesterDetails,$DayNameEng);

            $dataCourse = $data_sch['DetailsCourse'];


            $deft_w = 10;
            if(count($dataCourse)>0){

                $no = 1;
                for($m=0;$m<count($dataCourse);$m++){

                    $d_course = $dataCourse[$m];


                    $w = (count($d_course['detailTeamTeaching'])>0) ? (count($d_course['detailTeamTeaching'])+1) * $deft_w : $deft_w;

                    $pdf->SetFillColor(255, 206, 206);
                    $lb = ($d_course['Label']=='Ex') ? true : false;

                    $pdf->Cell(10,$w,$no,1,0,'C',$lb);
                    $pdf->Cell(20,$w,$d_course['ClassGroup'],1,0,'C');

                    $course = (strlen(trim($d_course['Course']))>=55) ? substr(trim($d_course['Course']),0,55).'_' : trim($d_course['Course']);
                    $pdf->Cell(90,$w,' '.$course,1,0,'L');

                    $pdf->Cell(20,$w,substr($d_course['StartSessions'],0,5).' - '.substr($d_course['EndSessions'],0,5),1,0,'C');
                    $pdf->Cell(25,$w,$d_course['ClassRoom'],1,0,'C');
                    $pdf->SetFont('Arial','B',8);
                    $coor = (strlen(trim($d_course['Coordinator']))>20) ? substr(trim($d_course['Coordinator']),0,19).'_' : trim($d_course['Coordinator']) ;
                    $pdf->Cell(42,$deft_w,' (Co) '.$coor,1,0,'L');

                    $pdf->Cell(20,$deft_w,'',1,0,'L');
                    $pdf->Cell(15,$deft_w,'',1,0,'L');
                    $pdf->Cell(20,$deft_w,'',1,0,'L');
                    $pdf->Cell(15,$deft_w,'',1,1,'L');

                    $pdf->SetFont('Arial','',8);
                    if(count($d_course['detailTeamTeaching'])>0){
                        for($t=0;$t<count($d_course['detailTeamTeaching']);$t++){
                            $teamT = $d_course['detailTeamTeaching'][$t];
                            $teamT2 = (strlen(trim($teamT))>26) ? substr(trim($teamT),0,25).'_' : trim($teamT) ;
                            $pdf->Cell(165,$deft_w,'',0,0,'L');
                            $pdf->Cell(42,$deft_w,' '.$teamT2,1,0,'L');
                            $pdf->Cell(20,$deft_w,'',1,0,'L');
                            $pdf->Cell(15,$deft_w,'',1,0,'L');
                            $pdf->Cell(20,$deft_w,'',1,0,'L');
                            $pdf->Cell(15,$deft_w,'',1,1,'L');
                        }
                    }

                    if($pdf->GetY()>173){
                        $pdf->AddPage();
                        $this->header_schedule($pdf,$SemesterDetails,$DayNameEng);
                    }

                    $no += 1;
                }
            }
            else {
                $pdf->Cell(275,7,'Schedule Not yet',1,1,'C');
            }

        }

        $pdf->Output('I',$DayNameEng.'_schedule.pdf');
    }

    private function header_schedule($pdf,$SemesterDetails,$DayNameEng){
        $pdf->Image(base_url('images/icon/logo-hr.png'),10,10,50);

        $pdf->SetFont('Arial','B',10);

        $pdf->Cell(60,9,'',0,0,'C');
        $pdf->Cell(0,9,'Semester - '.$SemesterDetails,1,1,'C');


        $pdf->Ln(5);
        $pdf->Cell(0,5,''.$DayNameEng,0,1,'C');

        $pdf->SetFont('Arial','i',7);
        $pdf->Cell(275,5,'FM-UAP/AKD-08-05',0,1,'R');
//        $pdf->Cell(275,5,''.$DayNameEng,0,1,'R');
//        $pdf->Ln(5);

        $pdf->SetFillColor(226, 226, 226);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(10,7,'No',1,0,'C',true);
        $pdf->Cell(20,7,'Group',1,0,'C',true);
        $pdf->Cell(90,7,'Course',1,0,'C',true);
        $pdf->Cell(20,7,'Time',1,0,'C',true);
        $pdf->Cell(25,7,'Room',1,0,'C',true);
        $pdf->Cell(42,7,'Lecturers',1,0,'C',true);
        $pdf->Cell(20,7,'Time',1,0,'C',true);
        $pdf->Cell(15,7,'Taken',1,0,'C',true);
        $pdf->Cell(20,7,'Time',1,0,'C',true);
        $pdf->Cell(15,7,'Returned',1,1,'C',true);

        $pdf->SetFont('Arial','',8);
    }

    // +++++++++++++++++++++++++++++++++++++++++++++

    // ==== PDF Monitoring Attendance Lecturer ====

    public function monitoringAttdLecturer(){
        $token = $this->input->post('token');

        $data_arr = $this->getInputToken($token);

        $pdf = new FPDF('l','mm','A4');

        $pdf->AddPage();

        $this->headerDefaultLanscape($pdf);

        $h = 5;

        $pdf->SetFont('Times','B',8);
        $pdf->Cell(296,$h,'ATTENDANCE REPORT SEMESTER '.strtoupper($data_arr['Semester']),0,1,'C');
        $pdf->Cell(296,$h,'PROGRAMME STUDY : '.strtoupper($data_arr['Prodi']),0,1,'C');

        $pdf->Ln(5);

        $h = 5.5;

        $pdf->SetFillColor(226, 226, 226);
        $pdf->SetFont('Times','B',8);
        $pdf->Cell(8,$h + $h,'No',1,0,'C',true);
        $pdf->Cell(22,$h + $h,'Code',1,0,'C',true);
        $pdf->Cell(85,$h + $h,'Course',1,0,'C',true);
        $pdf->Cell(15,$h + $h,'Group',1,0,'C',true);
        $pdf->Cell(50,$h + $h,'Lecturer',1,0,'C',true);

        $pdf->Cell(20,$h + $h,'Day',1,0,'C',true);
        $pdf->Cell(20,$h + $h,'Time',1,0,'C',true);
        $pdf->Cell(24,$h + $h,'Room',1,0,'C',true);
        $pdf->Cell(43,$h,'Session',1,1,'C',true);

        $pdf->Cell(244,$h,'',0,0,'C');
        $pdf->Cell(14,$h,'Target',1,0,'C',true);
        $pdf->Cell(14,$h,'Real',1,0,'C',true);
        $pdf->Cell(15,$h,'%',1,1,'C',true);

        $pdf->SetFont('Times','',8);
        $save2PDF = (array) $data_arr['save2PDF'];
        if(count($save2PDF)>0){
            $no = 1;
            for($s=0;$s<count($save2PDF);$s++){
                $d = (array) $save2PDF[$s];

                $newH = $h * count($d['Schedule']);

                $pdf->Cell(8,$newH,($no++),1,0,'C');
                $pdf->Cell(22,$newH,$d['MKCode'],1,0,'C');
                $pdf->Cell(85,$newH,' '.$d['MKNameEng'],1,0);
                $pdf->Cell(15,$newH,$d['ClassGroup'],1,0,'C');
                $pdf->Cell(50,$newH,' '.$d['Lecturer'],1,0);

                for($c=0;$c<count($d['Schedule']);$c++){
                    $dt = (array) $d['Schedule'][$c];

                    if($c!=0){
                        $pdf->Cell(180,$h,'',0,0,'C');
                    }

                    $pdf->Cell(20,$h,$dt['Day'],1,0,'C');
                    $pdf->Cell(20,$h,$dt['Time'],1,0,'C');
                    $pdf->Cell(24,$h,$dt['Room'],1,0,'C');

                    $pdf->Cell(14,$h,$dt['Target'],1,0,'C');
                    $pdf->Cell(14,$h,$dt['Real'],1,0,'C');
                    $pdf->Cell(15,$h,$dt['Percent'],1,1,'C');

                }



            }
        }



        $pdf->Output('I','Monitoring_Attendance_Lecturer.pdf');


    }

    // ++++++++++++++++++++++++++++++++++++++

    // ==== PDF Schedule Exchange =====

    public function scheduleExchange(){
        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        $pdf = new FPDF('l','mm','A4');

        $pdf->AddPage();

        $this->headerDefaultLanscape($pdf);

        $h = 5;

        $pdf->SetFont('Times','B',8);
        $pdf->Cell(296,$h,'SCHEDULE EXCHANGE',0,1,'C');

        $pdf->Ln(5);

        $pdf->SetFillColor(226, 226, 226);
        $pdf->SetFont('Times','B',8);
        $pdf->Cell(8,$h + $h,'No',1,0,'C',true);
//        $pdf->Cell(22,$h + $h,'Code',1,0,'C',true);
        $pdf->Cell(86,$h + $h,'Course',1,0,'C',true);
        $pdf->Cell(15,$h + $h,'Group',1,0,'C',true);
        $pdf->Cell(10,$h + $h,'Sesi',1,0,'C',true);

        $pdf->Cell(38,$h + $h,'Reason',1,0,'C',true);
        $pdf->Cell(10,$h + $h,'Status',1,0,'C',true);


        $pdf->SetFillColor(202, 215, 255);
        $pdf->Cell(60,$h,'Schedule Exist',1,0,'C',true);

        $pdf->SetFillColor(255, 226, 226);
        $pdf->Cell(60,$h,'Exchange to',1,1,'C',true);

        $pdf->SetFillColor(202, 215, 255);
        $pdf->Cell(167,$h,'',0,0,'C');
        $pdf->Cell(34,$h,'Day, Time',1,0,'C',true);
        $pdf->Cell(26,$h,'Room',1,0,'C',true);

        $pdf->SetFillColor(255, 226, 226);
        $pdf->Cell(34,$h,'Day, Time',1,0,'C',true);
        $pdf->Cell(26,$h,'Room',1,1,'C',true);



        $pdf->SetFont('Times','',8);

        $ch_atas = 4;
        $ch = 4;

        $no = 1;
        for($i=0;$i<count($data_arr);$i++){

            $d = (array) $data_arr[$i];

            //27
            if(strlen($d['Reason'])>27){
                $r1 = substr($d['Reason'],0,26).'-';
                $r_2 = substr($d['Reason'],26,26);

                $r2 = (strlen($r_2)>27) ? $r_2.'_' : $r_2;


            } else {
                $r1 = $d['Reason'];
                $r2 = '';
            }

            $pdf->Cell(8,$ch_atas,($no++),'LRT',0,'C');
            $pdf->SetFont('Times','B',8);
            $pdf->Cell(86,$ch_atas,$d['Course'],'LRT',0,'L');
            $pdf->SetFont('Times','',8);
            $pdf->Cell(15,$ch_atas,$d['ClassGroup'],'LRT',0,'C');
            $pdf->Cell(10,$ch_atas,$d['A_Sesi'],'LRT',0,'C');
            $pdf->Cell(38,$ch_atas,$r1,'LRT',0,'L');

            if($d['Status']=='1'){
                $pdf->SetFont('ZapfDingbats');
                $pdf->Cell(10,$ch_atas,chr(52),'LRT',0,'C');
            } else {
                $pdf->Cell(10,$ch_atas,'-','LRT',0,'C');
            }



            $pdf->SetFont('Times','',8);
            $pdf->Cell(34,$ch_atas,$d['A_Date'],'LRT',0,'C');
            $pdf->Cell(26,$ch_atas,$d['A_Room'],'LRT',0,'C');
            $pdf->Cell(34,$ch_atas,$d['T_Date'],'LRT',0,'C');
            $pdf->Cell(26,$ch_atas,$d['T_Room'],'LRT',1,'C');


            $pdf->Cell(8,$ch,'','LRB',0,'C');
            $pdf->Cell(86,$ch,$d['Lecturer'],'LRB',0,'L');
            $pdf->Cell(15,$ch,'','LRB',0,'C');
            $pdf->Cell(10,$ch,'','LRB',0,'C');
            $pdf->Cell(38,$ch,$r2,'LRB',0,'L');
            $pdf->Cell(10,$ch,'','LRB',0,'C');
            $pdf->Cell(34,$ch,$d['A_Time'],'LRB',0,'C');
            $pdf->Cell(26,$ch,'','LRB',0,'C');
            $pdf->Cell(34,$ch,$d['T_Time'],'LRB',0,'C');
            $pdf->Cell(26,$ch,'','LRB',1,'C');
        }




        $pdf->Output('I','Schedule_Exchange.pdf');
    }

    // ++++++++++++++++++++++++++++++++++

    // ==== PDF Monitoring Students ===

    public function monitoringStudent(){

//        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJDb3Vyc2UiOlt7Ik5hbWVFbmciOiJBcmNoaXRlY3R1cmUgRGVzaWduIFN0dWRpbyA1IiwiTUtDb2RlIjoiQVJDMzExNSIsIk1LSUQiOiIxOTEiLCJDbGFzc0dyb3VwIjoiQVJDMTAiLCJTZW1lc3RlciI6IjIwMTgvMjAxOSBHYW5qaWwiLCJMZWN0dXJlciI6Illhc2VyaSBEYWhsaWEgQXByaXRhc2FyaSJ9XSwiU3R1ZGVudCI6W3siTlBNIjoiMjExNDAwMTAiLCJOYW1lIjoiS0VMVklOIFBBVUxVUyIsIkF0dGVuZGFuY2UiOlt7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6Ik1vbmRheSJ9LHsiTTEiOm51bGwsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IldlZG5lc2RheSJ9LHsiTTEiOiIyIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiRnJpZGF5In1dLCJUYXJnZXQiOjQyLCJUb3RhbF9BdHRkIjoxLCJQZXJjZW50IjoiMiAlIn0seyJOUE0iOiIyMTE1MDAxNiIsIk5hbWUiOiJBTEVTU0FORFJPIiwiQXR0ZW5kYW5jZSI6W3siTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiTW9uZGF5In0seyJNMSI6bnVsbCwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiV2VkbmVzZGF5In0seyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJGcmlkYXkifV0sIlRhcmdldCI6NDIsIlRvdGFsX0F0dGQiOjIsIlBlcmNlbnQiOiI1ICUifSx7Ik5QTSI6IjIxMTUwMDMwIiwiTmFtZSI6IkpVU1RZTkUiLCJBdHRlbmRhbmNlIjpbeyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJNb25kYXkifSx7Ik0xIjpudWxsLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJXZWRuZXNkYXkifSx7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IkZyaWRheSJ9XSwiVGFyZ2V0Ijo0MiwiVG90YWxfQXR0ZCI6MiwiUGVyY2VudCI6IjUgJSJ9LHsiTlBNIjoiMjExNTAwMzIiLCJOYW1lIjoiQ0FUSExFRU4gQ0hBUklUWSBDSEFORFJBIiwiQXR0ZW5kYW5jZSI6W3siTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiTW9uZGF5In0seyJNMSI6bnVsbCwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiV2VkbmVzZGF5In0seyJNMSI6IjIiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJGcmlkYXkifV0sIlRhcmdldCI6NDIsIlRvdGFsX0F0dGQiOjEsIlBlcmNlbnQiOiIyICUifSx7Ik5QTSI6IjIxMTUwMDQyIiwiTmFtZSI6IlNPUEhJQSBTQU1CQVJBIiwiQXR0ZW5kYW5jZSI6W3siTTEiOiIyIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiTW9uZGF5In0seyJNMSI6bnVsbCwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiV2VkbmVzZGF5In0seyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJGcmlkYXkifV0sIlRhcmdldCI6NDIsIlRvdGFsX0F0dGQiOjEsIlBlcmNlbnQiOiIyICUifSx7Ik5QTSI6IjIxMTYwMDAxIiwiTmFtZSI6Ik9MSVZFUiBLRU5OWSIsIkF0dGVuZGFuY2UiOlt7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6Ik1vbmRheSJ9LHsiTTEiOm51bGwsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IldlZG5lc2RheSJ9LHsiTTEiOiIyIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiRnJpZGF5In1dLCJUYXJnZXQiOjQyLCJUb3RhbF9BdHRkIjoxLCJQZXJjZW50IjoiMiAlIn0seyJOUE0iOiIyMTE2MDAwMiIsIk5hbWUiOiJWQVJSRU4gQU5BU1RBU0lBIiwiQXR0ZW5kYW5jZSI6W3siTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiTW9uZGF5In0seyJNMSI6bnVsbCwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiV2VkbmVzZGF5In0seyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJGcmlkYXkifV0sIlRhcmdldCI6NDIsIlRvdGFsX0F0dGQiOjIsIlBlcmNlbnQiOiI1ICUifSx7Ik5QTSI6IjIxMTYwMDAzIiwiTmFtZSI6Ik1FTElTQSIsIkF0dGVuZGFuY2UiOlt7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6Ik1vbmRheSJ9LHsiTTEiOm51bGwsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IldlZG5lc2RheSJ9LHsiTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiRnJpZGF5In1dLCJUYXJnZXQiOjQyLCJUb3RhbF9BdHRkIjoyLCJQZXJjZW50IjoiNSAlIn0seyJOUE0iOiIyMTE2MDAwNiIsIk5hbWUiOiJBTEVYQU5ERVIgQk9ORyIsIkF0dGVuZGFuY2UiOlt7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6Ik1vbmRheSJ9LHsiTTEiOm51bGwsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IldlZG5lc2RheSJ9LHsiTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiRnJpZGF5In1dLCJUYXJnZXQiOjQyLCJUb3RhbF9BdHRkIjoyLCJQZXJjZW50IjoiNSAlIn0seyJOUE0iOiIyMTE2MDAwNyIsIk5hbWUiOiJTVEVWRU4gVkVSRElBTlRBIiwiQXR0ZW5kYW5jZSI6W3siTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiTW9uZGF5In0seyJNMSI6bnVsbCwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiV2VkbmVzZGF5In0seyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJGcmlkYXkifV0sIlRhcmdldCI6NDIsIlRvdGFsX0F0dGQiOjIsIlBlcmNlbnQiOiI1ICUifSx7Ik5QTSI6IjIxMTYwMDA4IiwiTmFtZSI6IkRFTk5JUyBPV0VOIiwiQXR0ZW5kYW5jZSI6W3siTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiTW9uZGF5In0seyJNMSI6bnVsbCwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiV2VkbmVzZGF5In0seyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJGcmlkYXkifV0sIlRhcmdldCI6NDIsIlRvdGFsX0F0dGQiOjIsIlBlcmNlbnQiOiI1ICUifSx7Ik5QTSI6IjIxMTYwMDA5IiwiTmFtZSI6IllFTlNFTiBGRUJSSUFOIiwiQXR0ZW5kYW5jZSI6W3siTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiTW9uZGF5In0seyJNMSI6bnVsbCwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiV2VkbmVzZGF5In0seyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJGcmlkYXkifV0sIlRhcmdldCI6NDIsIlRvdGFsX0F0dGQiOjIsIlBlcmNlbnQiOiI1ICUifSx7Ik5QTSI6IjIxMTYwMDEwIiwiTmFtZSI6IkNJTkRZIE1BUkdBUkVUSEEiLCJBdHRlbmRhbmNlIjpbeyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJNb25kYXkifSx7Ik0xIjpudWxsLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJXZWRuZXNkYXkifSx7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IkZyaWRheSJ9XSwiVGFyZ2V0Ijo0MiwiVG90YWxfQXR0ZCI6MiwiUGVyY2VudCI6IjUgJSJ9LHsiTlBNIjoiMjExNjAwMTMiLCJOYW1lIjoiQklMTEkgS1VSTklBV0FOIiwiQXR0ZW5kYW5jZSI6W3siTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiTW9uZGF5In0seyJNMSI6bnVsbCwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiV2VkbmVzZGF5In0seyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJGcmlkYXkifV0sIlRhcmdldCI6NDIsIlRvdGFsX0F0dGQiOjIsIlBlcmNlbnQiOiI1ICUifSx7Ik5QTSI6IjIxMTYwMDE2IiwiTmFtZSI6IlBBUkFNSVRBIFNIRVJFTlRZQSIsIkF0dGVuZGFuY2UiOlt7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6Ik1vbmRheSJ9LHsiTTEiOm51bGwsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IldlZG5lc2RheSJ9LHsiTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiRnJpZGF5In1dLCJUYXJnZXQiOjQyLCJUb3RhbF9BdHRkIjoyLCJQZXJjZW50IjoiNSAlIn0seyJOUE0iOiIyMTE2MDAxNyIsIk5hbWUiOiJLRVpJQSBGRUJSSUFOWSIsIkF0dGVuZGFuY2UiOlt7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6Ik1vbmRheSJ9LHsiTTEiOm51bGwsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IldlZG5lc2RheSJ9LHsiTTEiOiIyIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiRnJpZGF5In1dLCJUYXJnZXQiOjQyLCJUb3RhbF9BdHRkIjoxLCJQZXJjZW50IjoiMiAlIn0seyJOUE0iOiIyMTE2MDAxOSIsIk5hbWUiOiJLRUxWSU4iLCJBdHRlbmRhbmNlIjpbeyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJNb25kYXkifSx7Ik0xIjpudWxsLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJXZWRuZXNkYXkifSx7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IkZyaWRheSJ9XSwiVGFyZ2V0Ijo0MiwiVG90YWxfQXR0ZCI6MiwiUGVyY2VudCI6IjUgJSJ9LHsiTlBNIjoiMjExNjAwMjAiLCJOYW1lIjoiUklPIENIUklTVElBTiBSQUVNQSIsIkF0dGVuZGFuY2UiOlt7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6Ik1vbmRheSJ9LHsiTTEiOm51bGwsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IldlZG5lc2RheSJ9LHsiTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiRnJpZGF5In1dLCJUYXJnZXQiOjQyLCJUb3RhbF9BdHRkIjoyLCJQZXJjZW50IjoiNSAlIn0seyJOUE0iOiIyMTE2MDAyMSIsIk5hbWUiOiJEQVZJRCBOSUNPREVNVVMiLCJBdHRlbmRhbmNlIjpbeyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJNb25kYXkifSx7Ik0xIjpudWxsLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJXZWRuZXNkYXkifSx7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IkZyaWRheSJ9XSwiVGFyZ2V0Ijo0MiwiVG90YWxfQXR0ZCI6MiwiUGVyY2VudCI6IjUgJSJ9LHsiTlBNIjoiMjExNjAwMjUiLCJOYW1lIjoiTUlDSEFFTCBBRFJJQU4gSEVSWUFOVE8iLCJBdHRlbmRhbmNlIjpbeyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJNb25kYXkifSx7Ik0xIjpudWxsLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJXZWRuZXNkYXkifSx7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IkZyaWRheSJ9XSwiVGFyZ2V0Ijo0MiwiVG90YWxfQXR0ZCI6MiwiUGVyY2VudCI6IjUgJSJ9LHsiTlBNIjoiMjExNjAwMjYiLCJOYW1lIjoiQVJOT1RUIEZFUkVMUyIsIkF0dGVuZGFuY2UiOlt7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6Ik1vbmRheSJ9LHsiTTEiOm51bGwsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IldlZG5lc2RheSJ9LHsiTTEiOiIxIiwiTTIiOm51bGwsIk0zIjpudWxsLCJNNCI6bnVsbCwiTTUiOm51bGwsIk02IjpudWxsLCJNNyI6bnVsbCwiTTgiOm51bGwsIk05IjpudWxsLCJNMTAiOm51bGwsIk0xMSI6bnVsbCwiTTEyIjpudWxsLCJNMTMiOm51bGwsIk0xNCI6bnVsbCwiRGF5RW5nIjoiRnJpZGF5In1dLCJUYXJnZXQiOjQyLCJUb3RhbF9BdHRkIjoyLCJQZXJjZW50IjoiNSAlIn0seyJOUE0iOiIyMTE2MDAyNyIsIk5hbWUiOiJWRVJOQU5ETyBBTlRPTkkiLCJBdHRlbmRhbmNlIjpbeyJNMSI6IjEiLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJNb25kYXkifSx7Ik0xIjpudWxsLCJNMiI6bnVsbCwiTTMiOm51bGwsIk00IjpudWxsLCJNNSI6bnVsbCwiTTYiOm51bGwsIk03IjpudWxsLCJNOCI6bnVsbCwiTTkiOm51bGwsIk0xMCI6bnVsbCwiTTExIjpudWxsLCJNMTIiOm51bGwsIk0xMyI6bnVsbCwiTTE0IjpudWxsLCJEYXlFbmciOiJXZWRuZXNkYXkifSx7Ik0xIjoiMSIsIk0yIjpudWxsLCJNMyI6bnVsbCwiTTQiOm51bGwsIk01IjpudWxsLCJNNiI6bnVsbCwiTTciOm51bGwsIk04IjpudWxsLCJNOSI6bnVsbCwiTTEwIjpudWxsLCJNMTEiOm51bGwsIk0xMiI6bnVsbCwiTTEzIjpudWxsLCJNMTQiOm51bGwsIkRheUVuZyI6IkZyaWRheSJ9XSwiVGFyZ2V0Ijo0MiwiVG90YWxfQXR0ZCI6MiwiUGVyY2VudCI6IjUgJSJ9XX0.bCvFK08D3Tk02y4ZaH-USvmPx7kjUxIpUbQ_KDQz3kg';
        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        $course = (array) $data_arr['Course'][0];
        $student = (array) $data_arr['Student'];

//        print_r($data_arr);
//        exit;

        $pdf = new FPDF('l','mm','A4');

        $pdf->AddPage();
        $this->headerDefaultLanscape($pdf);


        $h = 4;

        $pdf->SetFont('Times','B',8);
//        $pdf->Cell(296,$h,'SCHEDULE EXCHANGE',0,1,'C');

        $pdf->Cell(25,$h,'Semester',0,0,'L');
        $pdf->Cell(5,$h,':',0,0,'C');
        $pdf->Cell(123,$h,$course['Semester'],0,0,'L');
        $pdf->Cell(25,$h,'Code',0,0,'L');
        $pdf->Cell(5,$h,':',0,0,'C');
        $pdf->Cell(123,$h,$course['MKCode'],0,1,'L');

        $pdf->Cell(25,$h,'Course',0,0,'L');
        $pdf->Cell(5,$h,':',0,0,'C');
        $pdf->Cell(123,$h,$course['NameEng'],0,0,'L');
        $pdf->Cell(25,$h,'Class Group',0,0,'L');
        $pdf->Cell(5,$h,':',0,0,'C');
        $pdf->Cell(123,$h,$course['ClassGroup'],0,1,'L');

        $pdf->Cell(25,$h,'Lecturer',0,0,'L');
        $pdf->Cell(5,$h,':',0,0,'C');
        $pdf->Cell(123,$h,$course['Lecturer'],0,0,'L');
        $pdf->Cell(25,$h,'',0,0,'L');
        $pdf->Cell(5,$h,'',0,0,'C');
        $pdf->Cell(123,$h,'',0,1,'L');

        $pdf->Ln(5);

        $pdf->SetFillColor(226, 226, 226);
        $pdf->SetFont('Times','B',8);
        $pdf->Cell(8,$h + $h,'No',1,0,'C',true);
        $pdf->Cell(18,$h + $h,'NIP',1,0,'C',true);
        $pdf->Cell(60,$h + $h,'Name',1,0,'C',true);

        $pdf->Cell(13,$h + $h,'Target',1,0,'C',true);
        $pdf->Cell(13,$h + $h,'Real',1,0,'C',true);
        $pdf->Cell(18.5,$h + $h,'%',1,0,'C',true);
        $pdf->Cell(30,$h+$h,'Day',1,0,'C',true);

        $pdf->Cell(126,$h,'Session',1,1,'C',true);

        $pdf->Cell(160.5,$h,'',0,0,'C');

        $pdf->Cell(9,$h,'1',1,0,'C',true);
        $pdf->Cell(9,$h,'2',1,0,'C',true);
        $pdf->Cell(9,$h,'3',1,0,'C',true);
        $pdf->Cell(9,$h,'4',1,0,'C',true);
        $pdf->Cell(9,$h,'5',1,0,'C',true);
        $pdf->Cell(9,$h,'6',1,0,'C',true);
        $pdf->Cell(9,$h,'7',1,0,'C',true);
        $pdf->Cell(9,$h,'8',1,0,'C',true);
        $pdf->Cell(9,$h,'9',1,0,'C',true);
        $pdf->Cell(9,$h,'10',1,0,'C',true);
        $pdf->Cell(9,$h,'11',1,0,'C',true);
        $pdf->Cell(9,$h,'12',1,0,'C',true);
        $pdf->Cell(9,$h,'13',1,0,'C',true);
        $pdf->Cell(9,$h,'14',1,1,'C',true);




        $pdf->SetFont('Times','',8);
        // Load Data
        $no =1;
        $hrow = 5;
        for($i=0;$i<count($student);$i++){

            $d = (array) $student[$i];

            $attd = $d['Attendance'];
            $hrowD = $hrow * count($attd);

            $pdf->Cell(8,$hrowD,($no++),1,0,'C');
            $pdf->Cell(18,$hrowD,$d['NPM'],1,0,'C');
            $pdf->Cell(60,$hrowD,' '.$d['Name'],1,0,'L');

            $pdf->Cell(13,$hrowD,$d['Target'],1,0,'C');
            $pdf->SetFont('Times','B',8);
            $pdf->Cell(13,$hrowD,$d['Total_Attd'],1,0,'C');
            $pdf->Cell(18.5,$hrowD,$d['Percent'],1,0,'C');
            $pdf->SetFont('Times','',8);

//            $pdf->SetTextColor(0,255,0);

            for($t=0;$t<count($attd);$t++){

                if($t!=0){
                    $pdf->Cell(130.5,$hrow,'',0,0,'C');
                }

                $da = (array) $attd[$t];

                $pdf->Cell(30,$hrow,' '.$da['DayEng'],1,0,'L');

                if($da['M1']==1 || $da['M1']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M1']==2 || $da['M1']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M2']==1 || $da['M2']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M2']==2 || $da['M2']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M3']==1 || $da['M3']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M3']==2 || $da['M3']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M4']==1 || $da['M4']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M4']==2 || $da['M4']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M5']==1 || $da['M5']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M5']==2 || $da['M5']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M6']==1 || $da['M6']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M6']==2 || $da['M6']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M7']==1 || $da['M7']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M7']==2 || $da['M7']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M8']==1 || $da['M8']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M8']==2 || $da['M8']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M9']==1 || $da['M9']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M9']==2 || $da['M9']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M10']==1 || $da['M10']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M10']==2 || $da['M10']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M11']==1 || $da['M11']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M11']==2 || $da['M11']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M12']==1 || $da['M12']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M12']==2 || $da['M12']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M13']==1 || $da['M13']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M13']==2 || $da['M13']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,0,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,0,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                if($da['M14']==1 || $da['M14']=='1'){
                    $pdf->SetTextColor(0, 147, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(52),1,1,'C');
                    $pdf->SetFont('Times','',8);
                } else if($da['M14']==2 || $da['M14']=='2'){
                    $pdf->SetTextColor(147, 0, 0);
                    $pdf->SetFont('ZapfDingbats');
                    $pdf->Cell(9,$hrow,chr(54),1,1,'C');
                    $pdf->SetFont('Times','',8);
                } else {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(9,$hrow,'-',1,1,'C');
                }
                $pdf->SetTextColor(0, 0, 0);

            }



        }



        $pdf->Output('I','Monitoring_Attendance_Students.pdf');
    }

    // ++++++++++++++++++++++++++++++++


    // ==== Monitoring Attendance By Range Date

    public function monitoringAttendanceByRangeDate(){


        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

//        print_r($data_arr);
//        exit;

        $pdf = new FPDF('l','mm','A4');

        $pdf->AddPage();
        $this->headerDefaultLanscape($pdf);

        $totalTgl = count($data_arr['PDFarrDate']);
        $dateHeaderObj = (array) $data_arr['PDFarrDate'];
        $wTgl = 4;

        // 287
        $h = 3;

        $pdf->SetFont('Times','B',8);
        $pdf->Cell(287,$h,'Semester '.$data_arr['Semester'],0,1,'C');
        $pdf->Cell(287,$h,$data_arr['Employees'],0,1,'C');
        $this->header_monitoringAttendanceByRangeDate($pdf,$data_arr);




        $h_body = 5;
        if(count($data_arr['Details'])>0){
            $no = 1;
            for($t=0;$t<count($data_arr['Details']);$t++){
                $DetailsObj = (array) $data_arr['Details'];
                $d = (array) $DetailsObj[$t];

                $pdf->Cell(7,$h_body * count($d['Course']),$no,1,0,'C');
                $pdf->Cell(15,$h_body * count($d['Course']),$d['NIP'],1,0,'C');

                $LecName = (strlen($d['Name'])>34) ? substr($d['Name'],0,34).'_' : $d['Name'];
                $pdf->Cell(41,$h_body * count($d['Course']),$LecName,1,0,'L');

                // Cek Group
                for($r=0;$r<count($d['Course']);$r++){

                    if($r!=0){
                        $pdf->Cell(63,$h_body,'',0,0,'C');
                    }

                    $cObj = (array) $d['Course'];
                    $c = (array) $cObj[$r];

                    $CNameCourse = (strlen($c['NameEng'])>46) ? substr($c['NameEng'],0,46).'_' : $c['NameEng'];
                    $pdf->Cell(15,$h_body,$c['ClassGroup'],1,0,'C');
                    $pdf->Cell(53,$h_body,$CNameCourse,1,0,'L');
                    $pdf->Cell(8,$h_body,$c['Credit'],1,0,'C');

                    $AttdObj = (array) $c['Attendance'];

                    $totalSesi = 0;
                    for($i=0;$i<$totalTgl;$i++){
                        $sts = 0;
                        if(count($AttdObj)>0){

                            for($a=0;$a<count($AttdObj);$a++){
                                $d_Attd = $AttdObj[$a];
                                if($dateHeaderObj[$i]==$d_Attd){
                                    $sts = $sts + 1;
                                }
                            }
                        }
                        $totalSesi = $totalSesi + $sts;
                        $ssSts = ($sts!=0) ? $sts : '';
                        if($sts!=0){
                            $pdf->SetFillColor(153, 255, 153);
                        } else if(date('N', strtotime($dateHeaderObj[$i]))==6 || date('N', strtotime($dateHeaderObj[$i]))==7){
                            $pdf->SetFillColor(224, 224, 224);
                        } else {
                            $pdf->SetFillColor(255, 255, 255);
                        }
//                        $cl = ($sts!=0) ? true : false;
//                        $pdf->Cell($wTgl,$h_body,$ssSts,1,0,'C',true);
                        $pdf->Cell($wTgl,$h_body,$ssSts,1,0,'C',true);


                    }

                    $pdf->SetFont('Times','B',7);
                    $pdf->SetFillColor(255, 255, 204);
                    $pdf->Cell(10,$h_body,$totalSesi,1,0,'C',true);
                    $totalCredit = ($totalSesi!=0) ? $totalSesi * (int) $c['Credit'] : 0;
                    $pdf->Cell(10,$h_body,$totalCredit,1,1,'C',true);
                    $pdf->SetFont('Times','',7);



                }

                if($pdf->GetY()>173){
                    $pdf->AddPage();
                    $this->header_monitoringAttendanceByRangeDate($pdf,$data_arr);
                }

                $no++;

            }
        }

        $pdf->Ln(9);
        $h = 4.5;
        $w_ttd = 50;
        $pdf->SetFillColor(224, 224, 224);
        $pdf->SetFont('Times','',7);
//        $pdf->Cell(287,$h,'Semester '.$data_arr['Semester'],1,1,'C');
        $pdf->Cell($w_ttd,$h,'Reported by',1,0,'C',true);
        $pdf->Cell($w_ttd,$h,'Acknowledge by',1,0,'C',true);
        $pdf->Cell($w_ttd,$h,'Acknowledge by',1,1,'C',true);

        $pdf->Cell($w_ttd,$h,'Date : ',1,0,'L');
        $pdf->Cell($w_ttd,$h,'Date : ',1,0,'L');
        $pdf->Cell($w_ttd,$h,'Date : ',1,1,'L');
        $h = 19;
        $pdf->Cell($w_ttd,$h,'',1,0,'L');
        $pdf->Cell($w_ttd,$h,'',1,0,'L');
        $pdf->Cell($w_ttd,$h,'',1,1,'L');

        $h = 4.5;
        $pdf->SetFont('Times','B',7);
        $pdf->Cell($w_ttd,$h,'',1,0,'C');
        // Get Kabag Akademik id 6.11
        $dataKabagAkademik = $this->m_save_to_pdf->getEmployeesByPositionMain('6.11');
        $kabag = (count($dataKabagAkademik)>0) ? $dataKabagAkademik[0]['Name'] : '' ;
        $pdf->Cell($w_ttd,$h,$kabag,1,0,'C');

        $dataRektorat1 = $this->m_save_to_pdf->getEmployeesByPositionMain('2.2');
        $Rektorat1 = (count($dataRektorat1)>0) ? $dataRektorat1[0]['Name'] : '' ;
        $pdf->Cell($w_ttd,$h,$Rektorat1,1,1,'C');

        $pdf->SetFont('Times','',7);
        $pdf->Cell($w_ttd,$h,'Staff SAS',1,0,'C');
        $pdf->Cell($w_ttd,$h,'Kabag. Administrasi Perkuliahan',1,0,'C');
        $pdf->Cell($w_ttd,$h,'Wakil Rektor Bidang Akademik',1,1,'C');



        $pdf->Ln(5);
        $pdf->SetFont('Times','I',7);
        $pdf->Cell(287,$h,'Download On : '.date("l, d F Y H:i:s").' | '.chr(169).' Podomoro University',0,0,'C');

        $pdf->Output('I','Monitoring_Attendance_Range_Date.pdf');
    }

    private function header_monitoringAttendanceByRangeDate($pdf,$data_arr){
        $totalTgl = count($data_arr['PDFarrDate']);
        $dateHeaderObj = (array) $data_arr['PDFarrDate'];
        $wTgl = 4;

        $pdf->Ln(5);

        $pdf->SetFont('Times','B',7);
        $pdf->SetFillColor(153, 204, 255);
        $h_header = 4;
        // 287
        $pdf->Cell(7,$h_header,'No','TRL',0,'C',true);
        $pdf->Cell(15,$h_header,'NIP','TRL',0,'C',true);
        $pdf->Cell(41,$h_header,'Name','TRL',0,'C',true);
        $pdf->Cell(15,$h_header,'Group','TRL',0,'C',true);
        $pdf->Cell(53,$h_header,'Course','TRL',0,'C',true);
        $pdf->Cell(8,$h_header,'Credit','TRL',0,'C',true);


        $pdf->Cell(($wTgl * $totalTgl),$h_header,$data_arr['RangeDate'],1,0,'C',true);
        $pdf->Cell(10,$h_header,'Total','TRL',0,'C',true);
        $pdf->Cell(10,$h_header,'Total','TRL',1,'C',true);


        $pdf->Cell(7,$h_header,'','BRL',0,'C',true);
        $pdf->Cell(15,$h_header,'','BRL',0,'C',true);
        $pdf->Cell(41,$h_header,'','BRL',0,'C',true);
        $pdf->Cell(15,$h_header,'','BRL',0,'C',true);
        $pdf->Cell(53,$h_header,'','BRL',0,'C',true);
        $pdf->Cell(8,$h_header,'','BRL',0,'C',true);


        for($i=0;$i<$totalTgl;$i++){
            $dateHeader = $dateHeaderObj[$i];
            if(date('N', strtotime($dateHeader))==6 || date('N', strtotime($dateHeader))==7){
                $pdf->SetFillColor(255, 153, 153);
            }
            $pdf->Cell($wTgl,$h_header,''.substr($dateHeader,8,2),1,0,'C',true);
            $pdf->SetFillColor(153, 204, 255);

        }

        $pdf->Cell(10,$h_header,'Sesi','BRL',0,'C',true);
        $pdf->Cell(10,$h_header,'Credit','BRL',1,'C',true);

        $pdf->SetFont('Times','',7);
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    private function headerDefaultLanscape($pdf){
        $pdf->Image(base_url('images/icon/logo-hr.png'),10,10,50);

        $pdf->SetFont('Times','B',11);

        $pdf->Ln(1);



        $pdf->Cell(296,5,'Universitas Agung Podomoro',0,1,'C');

        $h = 4;
        $pdf->SetFont('Times','',8);
        $pdf->Cell(296,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28 Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');
        $pdf->Cell(296,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Line(10,27,296,27);

        $pdf->Ln(7);

    }

    private function headerDefaultPotret($pdf,$NoForm){
        $pdf->Image(base_url('images/icon/favicon.png'),15,13,15);

        $pdf->SetFont('Times','I',7);
        $pdf->Cell(195,1,$NoForm,0,1,'R');

        $h = 5;

        $pdf->SetFont('Times','B',14);
        $pdf->Cell(195,$h,'Universitas Agung Podomoro',0,1,'C');

        $pdf->Ln(1);

        $pdf->SetFont('Times','',10);
        $pdf->Cell(195,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,1,'C');
        $pdf->Cell(195,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');
        $pdf->Cell(195,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Line(10,35,205,35);

        $pdf->Ln(7);
    }

    // ========== Exam PDF =========

    public function filterDocument(){
        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        if($data_arr['DocumentType']==1){

            // Get data exam
            $dataExam = $this->m_save_to_pdf->getExamSchedule($data_arr['SemesterID'],$data_arr['Type'],$data_arr['ExamDate']);


            $pdf = new FPDF('l','mm','A4');

            if(count($dataExam)>0){
                for($ex=0;$ex<count($dataExam);$ex++){
                    if(count($dataExam[$ex]['Course'])>0){
                        for($i=0;$i<count($dataExam[$ex]['Course']);$i++){
                            // membuat halaman baru
                            $pdf->AddPage();
                            $dataDetailExam = $dataExam[$ex];
                            $dataCourse = $dataExam[$ex]['Course'][$i];

                            $this->news_event($pdf,$data_arr,$dataDetailExam,$dataCourse);

                            $pdf->SetFont('Times','',7);
                            $pdf->Ln(9);
                            $pdf->Cell(135,1,'Page : '.$pdf->PageNo().' of {nb}',0,0,'R');
                            $pdf->Cell(17,1,'',0,0);
                            $pdf->Cell(135,1,'Page : '.$pdf->PageNo().' of {nb}',0,1,'R');
                            $pdf->AliasNbPages();

                        }
                    }
                }
            }

            $pdf->Output('document_penyerahan_berkas.pdf','I');
        }
        else if($data_arr['DocumentType']==2){

            // Get data exam
            $dataExam = $this->m_save_to_pdf->getExamSchedule($data_arr['SemesterID'],$data_arr['Type'],$data_arr['ExamDate']);


            $pdf = new FPDF('P','mm','A4');

            if(count($dataExam)>0){
                for($ex=0;$ex<count($dataExam);$ex++){
                    if(count($dataExam[$ex]['Course'])>0){
                        for($i=0;$i<count($dataExam[$ex]['Course']);$i++){
                            // membuat halaman baru
                            $pdf->AddPage();
                            $dataDetailExam = $dataExam[$ex];
                            $dataCourse = $dataExam[$ex]['Course'][$i];

                            $this->news_implementation($pdf,$data_arr,$dataDetailExam,$dataCourse);

                            $pdf->SetFont('Times','',7);
                            $pdf->Ln(9);
                            $pdf->Cell(195,1,'Page : '.$pdf->PageNo().' of {nb}',0,1,'R');
                            $pdf->AliasNbPages();

                        }
                    }
                }
            }

            $pdf->Output('document_pelaksaan_ujian.pdf','I');
        }
        else if($data_arr['DocumentType']==3){

            // Get data exam
            $dataExam = $this->m_save_to_pdf->getExamScheduleWithStudent($data_arr['SemesterID'],$data_arr['Type'],$data_arr['ExamDate']);

            $pdf = new FPDF('P','mm','A4');

            if(count($dataExam)>0){

                for($ex=0;$ex<count($dataExam);$ex++){
                    if(count($dataExam[$ex]['Course'])>0){
                        for($i=0;$i<count($dataExam[$ex]['Course']);$i++){
                            // membuat halaman baru
                            $pdf->AddPage();
                            $dataDetailExam = $dataExam[$ex];
                            $dataCourse = $dataExam[$ex]['Course'][$i];

                            $this->header_exam_attendance_students($pdf,$data_arr,$dataDetailExam,$dataCourse);
                            $this->exam_attendance_students($pdf,$data_arr,$dataDetailExam,$dataCourse);

                            $pdf->SetFont('Times','',7);
                            $pdf->Ln(5);
                            $pdf->Cell(195,1,'Page : '.$pdf->PageNo().' of {nb}',0,1,'R');
                            $pdf->AliasNbPages();

                        }
                    }
                }

            }

            $pdf->Output('document_attendance_exam_student.pdf','I');
        }
        else if($data_arr['DocumentType']==4){

            // Get data exam
            $dataExam = $this->m_save_to_pdf->getExamSchedule($data_arr['SemesterID'],$data_arr['Type'],$data_arr['ExamDate']);

            $pdf = new FPDF('P','mm','A4');
            if(count($dataExam)>0){
                $pdf->SetMargins(3,3,10);
                $pdf->AddPage();

//                $this->header_attendance_pengawas($pdf,$data_arr);
                $this->header_attendance_pengawas_p($pdf,$data_arr);


                for($ex=0;$ex<count($dataExam);$ex++){

                    $dataDetailExam = $dataExam[$ex];

//                    $this->attendance_pengawas($pdf,$data_arr,$dataDetailExam);
                    $this->attendance_pengawas_p($pdf,$data_arr,$dataDetailExam);
                }
            }

            $pdf->Output('document_pengawas.pdf','I');

        }
        else if($data_arr['DocumentType']==5){

            // Get data exam
            $dataExam = $this->m_save_to_pdf->getExamScheduleWithStudent($data_arr['SemesterID'],$data_arr['Type'],$data_arr['ExamDate']);

            if(count($dataExam)>0){

                $pdf = new FPDF('L','mm','A4');

                if(count($dataExam)==1){
                    $this->exam_template_map($pdf,$data_arr,$dataExam[0],'');
                } else {
                    for($i=0;$i<count($dataExam);$i++){

                        $data2 = (isset($dataExam[$i + 1])) ? $dataExam[$i + 1] : '';

                        $this->exam_template_map($pdf,$data_arr,$dataExam[$i],$data2);

                        $i += 1;

                    }
                }

                $pdf->Output('document_map.pdf','I');

            } else {
                echo "Attribut / Data is empty";
            }

        }


    }

    private function news_event($pdf,$data_arr,$dataDetailExam,$dataCourse){

//        $pdf->SetMargins(5,5,0);

        $pdf->Rect(3, 7, 140, 185);
        $pdf->Rect(155, 7, 140, 185);

        $pdf->Image(base_url('images/icon/favicon.png'),5,10,13);
        $pdf->Image(base_url('images/icon/favicon.png'),158,10,13);

        $h=7;

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(133,$h,'Universitas Agung Podomoro',0,0,'C');
        $pdf->Cell(20,$h,'',0,0);
        $pdf->Cell(132,$h,'Universitas Agung Podomoro',0,1,'C');

        $h=5;

        $pdf->SetFont('Times','',10);
        $pdf->Cell(135,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,1,'C');

        $pdf->Cell(135,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');

        $pdf->Cell(135,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Line(3,33,143,33);
        $pdf->Line(155,33,295,33);

        $pdf->Ln(4);

        $pdf->SetFont('Times','B',10);

        $xam_t = ($data_arr['Type']=='uts' || $data_arr['Type']=='UTS') ? 'MID EXAM' : 'FINAL EXAM';
        $xam_h = ($data_arr['Type']=='uts' || $data_arr['Type']=='UTS') ? 'ODD' : 'EVEN';

        $pdf->Cell(135,$h,$xam_t.' PAPER HANDOVER',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,$xam_t.' PAPER HANDOVER',0,1,'C');

        $Semester = explode(' ',$data_arr['Semester']);
        $pdf->Cell(135,$h,$xam_h.' ACADEMIC YEAR '.strtoupper(trim($Semester[0])),0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,$xam_h.' ACADEMIC YEAR '.strtoupper(trim($Semester[0])),0,1,'C');

        $pdf->SetFont('Times','',10);

        $pdf->Ln(4);

        $pdf->Cell(135,$h,'It has been handover the '.strtolower($xam_t).' paper',0,0,'L');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'It has been handover the '.strtolower($xam_t).' paper',0,1,'L');

        $pdf->Ln(4);

        $w_t = 30;
        $w_pars = 5;
        $w_fill = 100;
        $w_space = 17;


        $course = (strlen($dataCourse['Course'])>=43) ? substr($dataCourse['Course'],0,43).'_': $dataCourse['Course'] ;

        $pdf->Cell($w_t,$h,'Course',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['MKCode'].' - '.$course,0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Course',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['MKCode'].' - '.$course,0,1,'L');

        $pdf->Cell($w_t,$h,'Class Group',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['ClassGroup'],0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Class Group',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['ClassGroup'],0,1,'L');


        $pdf->Cell($w_t,$h,'Study Program',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['Prodi'],0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Study Program',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['Prodi'],0,1,'L');



        $pdf->Cell($w_t,$h,'Lecturer',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['Lecturere'],0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Lecturer',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['Lecturere'],0,1,'L');


        $pdf->Cell($w_t,$h,'Day, Date',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,date("l, d F Y", strtotime($dataDetailExam['ExamDate'])),0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Day, Date',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,date("l, d F Y", strtotime($dataDetailExam['ExamDate'])),0,1,'L');


        $pdf->Cell($w_t,$h,'Time',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,substr($dataDetailExam['ExamStart'],0,5).' - '.substr($dataDetailExam['ExamEnd'],0,5),0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Time',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,substr($dataDetailExam['ExamStart'],0,5).' - '.substr($dataDetailExam['ExamEnd'],0,5),0,1,'L');

        $pdf->Cell($w_t,$h,'Room',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataDetailExam['Room'],0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Room',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataDetailExam['Room'],0,1,'L');

        $pdf->Cell($w_t,$h,'Total Script',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,'_ _ _ _ _ _ _ _ _ _ _ _ _ _',0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Total Script',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,'_ _ _ _ _ _ _ _ _ _ _ _ _ _',0,1,'L');


        $pdf->Ln(10);

        $pdf->Line(25,107,130,107);
        $pdf->Line(180,107,280,107);

        $pdf->SetFont('Times','B',11);
        $pdf->Cell(135,$h,'Taking',0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell(135,$h,'Taking',0,1,'L');

        $pdf->SetFont('Times','B',10);
        $pdf->Cell(67.5,$h,'Delivered by',0,0,'C');
        $pdf->Cell(67.5,$h,'Received by',0,0,'C');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell(67.5,$h,'Delivered by',0,0,'C');
        $pdf->Cell(67.5,$h,'Received by',0,1,'C');

        $pdf->Ln(15);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(67.5,$h,'( _ _ _ _ _ _ _ _ _ _ )',0,0,'C');
        $pdf->Cell(67.5,$h,'( _ _ _ _ _ _ _ _ _ _ )',0,0,'C');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell(67.5,$h,'( _ _ _ _ _ _ _ _ _ _ )',0,0,'C');
        $pdf->Cell(67.5,$h,'( _ _ _ _ _ _ _ _ _ _ )',0,1,'C');

        $pdf->Ln(10);

        $pdf->Line(25,147,130,147);
        $pdf->Line(180,147,280,147);

        $pdf->SetFont('Times','B',11);
        $pdf->Cell(135,$h,'Return',0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell(135,$h,'Return',0,1,'L');

        $pdf->SetFont('Times','B',10);
        $pdf->Cell(67.5,$h,'Delivered by',0,0,'C');
        $pdf->Cell(67.5,$h,'Received by',0,0,'C');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell(67.5,$h,'Delivered by',0,0,'C');
        $pdf->Cell(67.5,$h,'Received by',0,1,'C');

        $pdf->Ln(15);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(67.5,$h,'( _ _ _ _ _ _ _ _ _ _ )',0,0,'C');
        $pdf->Cell(67.5,$h,'( _ _ _ _ _ _ _ _ _ _ )',0,0,'C');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell(67.5,$h,'( _ _ _ _ _ _ _ _ _ _ )',0,0,'C');
        $pdf->Cell(67.5,$h,'( _ _ _ _ _ _ _ _ _ _ )',0,1,'C');

    }

    private function news_implementation($pdf,$data_arr,$dataDetailExam,$dataCourse){

//        $pdf->Rect(10, 7, 195, 280);

        $pdf->Image(base_url('images/icon/favicon.png'),15,13,15);

        $pdf->SetFont('Times','I',7);
        $pdf->Cell(195,1,'FM-UAP/AKD-13-05',0,1,'R');

        $h = 5;

        $pdf->SetFont('Times','B',14);
        $pdf->Cell(195,$h,'Universitas Agung Podomoro',0,1,'C');

        $pdf->Ln(1);

        $pdf->SetFont('Times','',10);
        $pdf->Cell(195,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,1,'C');
        $pdf->Cell(195,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');
        $pdf->Cell(195,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Line(10,35,205,35);

        $pdf->Ln(10);

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(195,$h,'BERITA ACARA PELAKSANAAN UJIAN',0,1,'C');
//        $pdf->Cell(195,$h,'ACADEMIC YEAR '.strtoupper($data_arr['Semester']),0,1,'C');

        $examType = ($data_arr['Type']=='UTS' || $data_arr['Type']=='uts')
            ? 'Ujian Tengah Semester GANJIL' : 'Ujian Akhir Semester GENAP';

        $pdf->Ln(5);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(195,$h,'Pada hari ini telah dilaksanakan  '.$examType.', Tahun Akademik '.$data_arr['Semester'],0,1,'L');

        $w_sp1 = 10;
        $w_label = 40;
        $w_sp2 = 5;
        $w_fill = 140;

        $pdf->Ln(5);

        $h = 7;

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Hari / Tanggal',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,date("l / d F Y", strtotime($dataDetailExam['ExamDate'])),0,1,'L');

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Waktu Ujian',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,substr($dataDetailExam['ExamStart'],0,5).' - '.substr($dataDetailExam['ExamEnd'],0,5),0,1,'L');

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Program Studi',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['Prodi'],0,1,'L');

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Kelas (Group)',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['ClassGroup'],0,1,'L');

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Kode Mata Kuliah',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['MKCode'],0,1,'L');

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Mata Kuliah',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['Course'],0,1,'L');

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Dosen',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['Lecturere'],0,1,'L');

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Ruangan',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataDetailExam['Room'],0,1,'L');


        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Jumlah Peserta hadir',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,'_ _ _ _ _ _ orang',0,1,'L');

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Jumlah Peserta tidak hadir',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,'_ _ _ _ _ _ orang',0,1,'L');

        $pdf->Ln(5);
        $pdf->SetFont('Times','B',10);
        $pdf->SetFillColor(226, 226, 226);
        $pdf->Cell($w_sp1,$h,'',0,0,'C');
        $pdf->Cell(10,$h,'No',1,0,'C',true);
        $pdf->Cell(30,$h,'NIM',1,0,'C',true);
        $pdf->Cell(50,$h,'Nama',1,0,'C',true);
        $pdf->Cell(95,$h,'Keterangan',1,1,'C',true);

//        $pdf->SetFont('Times','',10);
        for($s=1;$s<=5;$s++){
            $pdf->Cell($w_sp1,$h,'',0,0,'C');
            $pdf->Cell(10,$h,$s,1,0,'C');
            $pdf->Cell(30,$h,'',1,0,'C');
            $pdf->Cell(50,$h,'',1,0,'C');
            $pdf->Cell(95,$h,'',1,1,'C');
        }

        $pdf->Ln(5);

        $pdf->Cell(195,$h,'Catatan khusus selama ujian berlangsung :',0,1,'L');
        for($s=1;$s<=4;$s++){
            $pdf->Cell(195,$h,'','B',1,'L');
        }

        $pdf->Ln(5);
        $pdf->SetFont('Times','',10);

        $pdf->Cell(195,$h,'Jakarta, '.date("d F Y", strtotime($dataDetailExam['ExamDate'])),0,1,'R');

        $pdf->Cell(97.5,$h,'Pengawas Ujian 1',0,0,'C');
        $pdf->Cell(97.5,$h,'Pengawas Ujian 2',0,1,'C');

        $pdf->Ln(15);

        $pdf->Cell(97.5,$h,'(_ _ _ _ _ _ _ _ _ _ _ _ _ _)',0,0,'C');
        $pdf->Cell(97.5,$h,'(_ _ _ _ _ _ _ _ _ _ _ _ _ _)',0,1,'C');

    }

    private function header_exam_attendance_students($pdf,$data_arr,$dataDetailExam,$dataCourse){

        $this->headerDefaultPotret($pdf,'FM-UAP/AKD-13-03A');

        $xam_t = ($data_arr['Type']=='uts' || $data_arr['Type']=='UTS') ? 'MID EXAM' : 'FINAL EXAM';
        $xam_h = ($data_arr['Type']=='uts' || $data_arr['Type']=='UTS') ? 'ODD' : 'EVEN';
        $Semester = explode(' ',$data_arr['Semester']);

        $h = 5;
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(195,$h,'ATTENDANCE '.$xam_t,0,1,'C');
        $pdf->Cell(195,$h,$xam_h.' SEMESTER ACADEMIC YEAR '.strtoupper(trim($Semester[0])),0,1,'C');

        $pdf->Ln(5);
        $pdf->SetFont('Times','',10);
    }

    private function exam_attendance_students($pdf,$data_arr,$dataDetailExam,$dataCourse){

        $h = 5;
        $w_label_r = 22;
        $w_sp_r = 3;
        $w_fill_r = 95;

        $w_label_l = 23;
        $w_sp_l = 3;
        $w_fill_l = 50;


        $pdf->Cell($w_label_r,$h,'Code',0,0,'L');
        $pdf->Cell($w_sp_r,$h,':',0,0,'C');
        $pdf->Cell($w_fill_r,$h,$dataCourse['MKCode'],0,0,'L');

        $pdf->Cell($w_label_l,$h,'Study Program',0,0,'L');
        $pdf->Cell($w_sp_l,$h,':',0,0,'C');
        $pdf->Cell($w_fill_l,$h,$dataCourse['Prodi'],0,1,'L');

        $dCourse = (strlen($dataCourse['Course'])>=50) ? substr($dataCourse['Course'],0,50).'_' : $dataCourse['Course'];
        $pdf->Cell($w_label_r,$h,'Course',0,0,'L');
        $pdf->Cell($w_sp_r,$h,':',0,0,'C');
        $pdf->Cell($w_fill_r,$h,$dCourse,0,0,'L');

        $pdf->Cell($w_label_l,$h,'Day, Date',0,0,'L');
        $pdf->Cell($w_sp_l,$h,':',0,0,'C');
        $pdf->Cell($w_fill_l,$h,date("l, d M Y", strtotime($dataDetailExam['ExamDate'])),0,1,'L');




        $pdf->Cell($w_label_r,$h,'Class Group',0,0,'L');
        $pdf->Cell($w_sp_r,$h,':',0,0,'C');
        $pdf->Cell($w_fill_r,$h,$dataCourse['ClassGroup'],0,0,'L');

        $pdf->Cell($w_label_l,$h,'Time',0,0,'L');
        $pdf->Cell($w_sp_l,$h,':',0,0,'C');
        $pdf->Cell($w_fill_l,$h,substr($dataDetailExam['ExamStart'],0,5).' - '.substr($dataDetailExam['ExamEnd'],0,5),0,1,'L');

        $pdf->Cell($w_label_r,$h,'Lecturer',0,0,'L');
        $pdf->Cell($w_sp_r,$h,':',0,0,'C');
        $pdf->Cell($w_fill_r,$h,$dataCourse['Lecturere'],0,0,'L');

        $pdf->Cell($w_label_l,$h,'Room',0,0,'L');
        $pdf->Cell($w_sp_l,$h,':',0,0,'C');
        $pdf->Cell($w_fill_l,$h,$dataDetailExam['Room'],0,1,'L');

        $pdf->Ln(5);
        $pdf->SetFillColor(226, 226, 226);
        $pdf->SetFont('Times','B',10);
        $h = 7;
        $pdf->Cell(10,$h,'No',1,0,'C',true);
        $pdf->Cell(25,$h,'NIM',1,0,'C',true);
        $pdf->Cell(110,$h,'Name',1,0,'C',true);
        $pdf->Cell(30,$h,'Sign',1,0,'C',true);
        $pdf->Cell(20,$h,'Score',1,1,'C',true);

        $pdf->SetFont('Times','',10);
//        $h = 5;

        if(count($dataCourse['DetailStudents'])){
            $no =1;
            for($t=0;$t<count($dataCourse['DetailStudents']);$t++){
                $d = $dataCourse['DetailStudents'][$t];
                $pdf->Cell(10,$h,$no,1,0,'C');
                $pdf->Cell(25,$h,$d['NPM'],1,0,'C');
                $pdf->Cell(110,$h,ucwords(strtolower($d['Name'])),1,0,'L');
                $pdf->Cell(30,$h,'',1,0,'C');
                $pdf->Cell(20,$h,'',1,1,'C');

                if($pdf->GetY()>=242){
                    $pdf->AddPage();
                    $this->header_exam_attendance_students($pdf,$data_arr,$dataDetailExam,$dataCourse);
                }

                $no++;
            }
        }

        // Kosong
//        for($k=1;$k<=2;$k++){
//            $pdf->Cell(10,$h,'',1,0,'C');
//            $pdf->Cell(25,$h,'',1,0,'C');
//            $pdf->Cell(110,$h,'',1,0,'L');
//            $pdf->Cell(30,$h,'',1,0,'C');
//            $pdf->Cell(20,$h,'',1,1,'C');
//        }

        $pdf->Ln(5);
        $pdf->Cell(150,$h,'',0,0,'C');
        $pdf->Cell(45,$h,'Sign by Lecturer',0,1,'C');

        $pdf->Ln(15);

        $pdf->Cell(150,$h,'',0,0,'C');
        $pdf->Cell(45,$h,'( _ _ _ _ _ _ _ _ _ )',0,1,'C');


    }


    // =============== PENGAWAS

    private function header_attendance_pengawas($pdf,$data_arr){
        $pdf->Image(base_url('images/icon/favicon.png'),15,5,15);

        $h = 5;

        $pdf->SetFont('Times','B',14);
        $pdf->Cell(282,$h,'Universitas Agung Podomoro',0,1,'C');

        $pdf->Ln(1);

        $pdf->SetFont('Times','',10);
        $pdf->Cell(282,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,1,'C');
        $pdf->Cell(282,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');
        $pdf->Cell(282,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Line(10,25,292,25);

        $pdf->Ln(5);

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(282,$h,date('l, d F Y',strtotime($data_arr['ExamDate'])),0,1,'C');
        $pdf->SetFont('Times','',10);

        $pdf->Ln(4);

        $h = 7;
        $pdf->SetFillColor(226, 226, 226);
        $pdf->Cell(25,$h,'Time','TLR',0,'C',true);
        $pdf->Cell(20,$h,'Room','TLR',0,'C',true);

        $pdf->Cell(70,$h,'Invigilator 1',1,0,'C',true);
        $pdf->Cell(70,$h,'Invigilator 2',1,0,'C',true);

        $pdf->Cell(22,$h,'Group','TLR',0,'C',true);
        $pdf->Cell(75,$h,'Course','TLR',1,'C',true);



        $pdf->Cell(25,$h,'','BLR',0,'C',true);
        $pdf->Cell(20,$h,'','BLR',0,'C',true);

        $pdf->Cell(40,$h,'Name',1,0,'C',true);
        $pdf->Cell(15,$h,'Taken',1,0,'C',true);
        $pdf->Cell(15,$h,'Returned',1,0,'C',true);
        $pdf->Cell(40,$h,'Name',1,0,'C',true);
        $pdf->Cell(15,$h,'Taken',1,0,'C',true);
        $pdf->Cell(15,$h,'Returned',1,0,'C',true);

        $pdf->Cell(22,$h,'','BLR',0,'C',true);
        $pdf->Cell(75,$h,'','BLR',1,'C',true);

    }

    private function header_attendance_pengawas_p($pdf,$data_arr){
        $pdf->Image(base_url('images/icon/favicon.png'),15,5,15);

        $h = 5;

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(205,$h,'Universitas Agung Podomoro',0,1,'C');

        $pdf->Ln(1);

        $pdf->SetFont('Times','',10);
        $pdf->Cell(205,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,1,'C');
        $pdf->Cell(205,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');
        $pdf->Cell(205,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Line(3,25,207,25);

        $pdf->Ln(5);

        $pdf->SetFont('Times','B',10);
        $pdf->Cell(198,$h,date('l, d F Y',strtotime($data_arr['ExamDate'])),0,1,'C');
        $pdf->SetFont('Times','B',7);

        $pdf->Ln(4);

        $h = 7;
        $pdf->SetFillColor(226, 226, 226);
        $pdf->Cell(15,$h,'Time','TLR',0,'C',true);
        $pdf->Cell(15,$h,'Room','TLR',0,'C',true);

        $pdf->Cell(49,$h,'Invigilator 1',1,0,'C',true);
        $pdf->Cell(49,$h,'Invigilator 2',1,0,'C',true);

        $pdf->Cell(15,$h,'Group','TLR',0,'C',true);
        $pdf->Cell(62,$h,'Course','TLR',1,'C',true);



        $pdf->Cell(15,$h,'','BLR',0,'C',true);
        $pdf->Cell(15,$h,'','BLR',0,'C',true);

        $pdf->Cell(25,$h,'Name',1,0,'C',true);
        $pdf->Cell(12,$h,'Taken',1,0,'C',true);
        $pdf->Cell(12,$h,'Returned',1,0,'C',true);

        $pdf->Cell(25,$h,'Name',1,0,'C',true);
        $pdf->Cell(12,$h,'Taken',1,0,'C',true);
        $pdf->Cell(12,$h,'Returned',1,0,'C',true);

        $pdf->Cell(15,$h,'','BLR',0,'C',true);
        $pdf->Cell(62,$h,'','BLR',1,'C',true);

    }

    // ==============

    private function attendance_pengawas($pdf,$data_arr,$dataDetailExam){

        $totalCourse = count($dataDetailExam['Course']);
        $h = 7;
        $h2 = $h * $totalCourse;
        for($i=0;$i<$totalCourse;$i++){


            $time = substr($dataDetailExam['ExamStart'],0,5).' - '.substr($dataDetailExam['ExamEnd'],0,5);

            $group = $dataDetailExam['Course'][$i]['ClassGroup'];
            $datacourse = $dataDetailExam['Course'][$i]['Course'];
            $course = (strlen($datacourse)>=45) ? substr($datacourse,0,45).'_' : $datacourse;

            $data_p1 = $dataDetailExam['Name_P1'];
            $data_p2 = $dataDetailExam['Name_P2'];

            $Name_P1 = (strlen($data_p1)>=20) ? substr($data_p1,0,20).'_' : $data_p1;
            $Name_P2 = (strlen($data_p2)>=20) ? substr($data_p2,0,20).'_' : $data_p2;


            if($i==0){


                $pdf->Cell(25,$h2,$time,1,0,'C');
                $pdf->Cell(20,$h2,$dataDetailExam['Room'],1,0,'C');
                $pdf->Cell(40,$h2,$Name_P1,1,0,'L');
                $pdf->Cell(15,$h2,'',1,0,'C');
                $pdf->Cell(15,$h2,'',1,0,'C');
                $pdf->Cell(40,$h2,$Name_P2,1,0,'L');
                $pdf->Cell(15,$h2,'',1,0,'C');
                $pdf->Cell(15,$h2,'',1,0,'C');

            } else {
                $pdf->Cell(185,$h2,'',0,0,'C');
            }


            $pdf->Cell(22,$h,$group,1,0,'C');
            $pdf->Cell(75,$h,$course,1,1,'L');

        }

    }
    private function attendance_pengawas_p($pdf,$data_arr,$dataDetailExam){

        $pdf->SetFont('Times','',7);

        $totalCourse = count($dataDetailExam['Course']);
        $h = 10;
        $h2 = $h * $totalCourse;
        for($i=0;$i<$totalCourse;$i++){


            $time = substr($dataDetailExam['ExamStart'],0,5).' - '.substr($dataDetailExam['ExamEnd'],0,5);

            $group = $dataDetailExam['Course'][$i]['ClassGroup'];
            $datacourse = $dataDetailExam['Course'][$i]['Course'];
            $course = (strlen($datacourse)>=45) ? substr($datacourse,0,45).'_' : $datacourse;

            $data_p1 = $dataDetailExam['Name_P1'];
            $data_p2 = $dataDetailExam['Name_P2'];

            $Name_P1 = (strlen($data_p1)>=20) ? substr($data_p1,0,20).'_' : $data_p1;
            $Name_P2 = (strlen($data_p2)>=20) ? substr($data_p2,0,20).'_' : $data_p2;


            if($i==0){

                $ex_time = explode(' ',$time);
                $pdf->Cell(15,$h2,$time,1,0,'C');
                $pdf->Cell(15,$h2,$dataDetailExam['Room'],1,0,'C');
                $pdf->Cell(25,$h2,$Name_P1,1,0,'L');
                $pdf->Cell(12,$h2,'',1,0,'C');
                $pdf->Cell(12,$h2,'',1,0,'C');
                $pdf->Cell(25,$h2,$Name_P2,1,0,'L');
                $pdf->Cell(12,$h2,'',1,0,'C');
                $pdf->Cell(12,$h2,'',1,0,'C');

            } else {
                $pdf->Cell(128,$h2,'',0,0,'C');
            }


            $pdf->Cell(15,$h,$group,1,0,'C');
            $pdf->Cell(62,$h,$course,1,1,'L');

        }

    }

    private function exam_template_map($pdf,$data_arr,$dataDetailExam,$dataDetailExam_2){

        $pdf->SetMargins(5,5,0);
        $pdf->AddPage();

        $totalGroup = count($dataDetailExam['Course']);
        $totalGroup_2 = ($dataDetailExam_2!='')? count($dataDetailExam_2['Course']) : 1 ;
        $h_fx = ($totalGroup>=$totalGroup_2) ? $totalGroup : $totalGroup_2;
        $h_border = ($h_fx * 8) + 100;
        $pdf->Rect(3, 3, 140, $h_border);
        $pdf->Rect(155, 3, 140, $h_border);

        $pdf->Image(base_url('images/icon/favicon.png'),5,6,12);
        $pdf->Image(base_url('images/icon/favicon.png'),158,6,12);

        $h=6;

        $pdf->SetFont('Times','B',13);
        $pdf->Cell(133,$h,'Universitas Agung Podomoro',0,0,'C');
        $pdf->Cell(20,$h,'',0,0);
        $pdf->Cell(132,$h,'Universitas Agung Podomoro',0,1,'C');

        $h=3.9;

        $pdf->SetFont('Times','',9);
        $pdf->Cell(135,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,1,'C');

        $pdf->Cell(135,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');

        $pdf->Cell(135,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Line(3,25,143,25);
        $pdf->Line(155,25,295,25);

        $h=3.9;

        $pdf->Ln(5);
        $pdf->SetFont('Times','B',10);

        $xam_t = ($data_arr['Type']=='uts' || $data_arr['Type']=='UTS') ? 'MID EXAM' : 'FINAL EXAM';
        $xam_h = ($data_arr['Type']=='uts' || $data_arr['Type']=='UTS') ? 'ODD' : 'EVEN';

        $pdf->Cell(135,$h,$xam_t,0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,$xam_t,0,1,'C');

        $pdf->Cell(135,$h,'EVEN SEMESTER',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'EVEN SEMESTER',0,1,'C');

        $Semester = explode(' ',$data_arr['Semester']);
        $pdf->Cell(135,$h,$xam_h.' ACADEMIC YEAR '.strtoupper(trim($Semester[0])),0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,$xam_h.' ACADEMIC YEAR '.strtoupper(trim($Semester[0])),0,1,'C');

        //++++++++++++++++++++++++++++++++++++++++++++
        $pdf->Ln(5);
        $pdf->SetFont('Times','',10);
        $h=5;

        $w_label_left_1 = 25;
        $w_sp_left_1 = 7;
        $w_fill_left_1 = 45;

        $w_label_left_2 = 25;
        $w_sp_left_2 = 7;
        $w_fill_left_2 = 35;

        $w_space = 8;

        $w_label_right_1 = 25;
        $w_sp_right_1 = 7;
        $w_fill_right_1 = 45;

        $w_label_right_2 = 25;
        $w_sp_right_2 = 7;
        $w_fill_right_2 = 35;

        //======================================================

        $pdf->Cell($w_label_left_1,$h,'Exam',0,0,'L');
        $pdf->Cell($w_sp_left_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_1,$h,$xam_t." (".strtoupper($data_arr['Type']).")",0,0,'L');

        $pdf->Cell($w_label_left_2,$h,'',0,0,'L');
        $pdf->Cell($w_sp_left_2,$h,'',0,0,'C');
        $pdf->Cell($w_fill_left_2,$h,'',0,0,'L');

        $pdf->Cell($w_space,$h,'',0,0);


        $pdf->Cell($w_label_right_1,$h,'Exam',0,0,'L');
        $pdf->Cell($w_sp_right_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_1,$h,$xam_t." (".strtoupper($data_arr['Type']).")",0,0,'L');

        $pdf->Cell($w_label_right_2,$h,'',0,0,'L');
        $pdf->Cell($w_sp_right_2,$h,'',0,0,'C');
        $pdf->Cell($w_fill_right_2,$h,'',0,1,'L');

        //======================================================

        $pdf->Cell($w_label_left_1,$h,'Day, Date',0,0,'L');
        $pdf->Cell($w_sp_left_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_1,$h,date('l, d M Y',strtotime($data_arr['ExamDate'])),0,0,'L');

        $pdf->Cell($w_label_left_2,$h,'Exam Type',0,0,'L');
        $pdf->Cell($w_sp_left_2,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_2,$h,'Open / Close',0,0,'L');

        $pdf->Cell($w_space,$h,'',0,0);


        $pdf->Cell($w_label_right_1,$h,'Day, Date',0,0,'L');
        $pdf->Cell($w_sp_right_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_1,$h,date('l, d M Y',strtotime($data_arr['ExamDate'])),0,0,'L');

        $pdf->Cell($w_label_right_2,$h,'Exam Type',0,0,'L');
        $pdf->Cell($w_sp_right_2,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_2,$h,'Open / Close',0,1,'L');

        //======================================================

        $time_1 = substr($dataDetailExam['ExamStart'],0,5).' - '.substr($dataDetailExam['ExamEnd'],0,5);
        $time_2 = ($dataDetailExam_2!='') ? substr($dataDetailExam_2['ExamStart'],0,5).' - '.substr($dataDetailExam_2['ExamEnd'],0,5) : '-';

        $pdf->Cell($w_label_left_1,$h,'Time',0,0,'L');
        $pdf->Cell($w_sp_left_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_1,$h,$time_1,0,0,'L');

        $pdf->Cell($w_label_left_2,$h,'Calculator',0,0,'L');
        $pdf->Cell($w_sp_left_2,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_2,$h,'Yes / No',0,0,'L');

        $pdf->Cell($w_space,$h,'',0,0);


        $pdf->Cell($w_label_right_1,$h,'Time',0,0,'L');
        $pdf->Cell($w_sp_right_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_1,$h,$time_2,0,0,'L');

        $pdf->Cell($w_label_right_2,$h,'Calculator',0,0,'L');
        $pdf->Cell($w_sp_right_2,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_2,$h,'Yes / No',0,1,'L');

        //======================================================

        $room_1 = $dataDetailExam['Room'];
        $room_2 = ($dataDetailExam_2!='') ? $dataDetailExam_2['Room'] : '-';

        $pdf->Cell($w_label_left_1,$h,'Room',0,0,'L');
        $pdf->Cell($w_sp_left_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_1,$h,$room_1,0,0,'L');

        $pdf->Cell($w_label_left_2,$h,'Dictionary',0,0,'L');
        $pdf->Cell($w_sp_left_2,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_2,$h,'Yes / No',0,0,'L');

        $pdf->Cell($w_space,$h,'',0,0);


        $pdf->Cell($w_label_right_1,$h,'Room',0,0,'L');
        $pdf->Cell($w_sp_right_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_1,$h,$room_2,0,0,'L');

        $pdf->Cell($w_label_right_2,$h,'Dictionary',0,0,'L');
        $pdf->Cell($w_sp_right_2,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_2,$h,'Yes / No',0,1,'L');

        //======================================================
        $pdf->Ln(5);

        $invigilator_1_1 = $dataDetailExam['Name_P1'];
        $invigilator_1_2 = ($dataDetailExam_2!='') ? $dataDetailExam_2['Name_P1'] : '-' ;

        $pdf->Cell($w_label_left_1,$h,'Invigilator 1',0,0,'L');
        $pdf->Cell($w_sp_left_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_1+$w_label_left_2+$w_sp_left_2+$w_fill_left_2,$h,$invigilator_1_1,0,0,'L');

        $pdf->Cell($w_space,$h,'',0,0);

        $pdf->Cell($w_label_right_1,$h,'Invigilator 1',0,0,'L');
        $pdf->Cell($w_sp_right_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_1+$w_label_right_2+$w_sp_right_2+$w_fill_right_2,$h,$invigilator_1_2,0,1,'L');

        //======================================================

        $invigilator_2_1 = $dataDetailExam['Name_P2'];
        $invigilator_2_2 = ($dataDetailExam_2!='') ? $dataDetailExam_2['Name_P2'] : '-' ;

        $pdf->Cell($w_label_left_1,$h,'Invigilator 2',0,0,'L');
        $pdf->Cell($w_sp_left_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_1+$w_label_left_2+$w_sp_left_2+$w_fill_left_2,$h,$invigilator_2_1,0,0,'L');

        $pdf->Cell($w_space,$h,'',0,0);

        $pdf->Cell($w_label_right_1,$h,'Invigilator 2',0,0,'L');
        $pdf->Cell($w_sp_right_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_1+$w_label_right_2+$w_sp_right_2+$w_fill_right_2,$h,$invigilator_2_2,0,1,'L');

        //======================================================

        $pdf->Ln(3);
        $pdf->Cell($w_label_left_1,$h,'Total Exam',0,0,'L');
        $pdf->Cell($w_sp_left_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_left_1+$w_label_left_2+$w_sp_left_2+$w_fill_left_2,$h,' _ _ _ _ _ _ ',0,0,'L');

        $pdf->Cell($w_space,$h,'',0,0);

        $pdf->Cell($w_label_right_1,$h,'Total Exam',0,0,'L');
        $pdf->Cell($w_sp_right_1,$h,':',0,0,'C');
        $pdf->Cell($w_fill_right_1+$w_label_right_2+$w_sp_right_2+$w_fill_right_2,$h,' _ _ _ _ _ _ ',0,1,'L');

        //======================================

        $pdf->Ln(4);

        $pdf->SetFont('Times','B',9);
        $h = 6;
        $pdf->setFillColor(255,255,102);

        $pdf->Cell(20,$h,'Group',1,0,'C',true);
        $pdf->Cell(20,$h,'Code',1,0,'C',true);
        $pdf->Cell(88,$h,'Course',1,0,'C',true);
        $pdf->Cell(7,$h,'Std',1,0,'C',true);


        $pdf->Cell(17,$h,'',0,0);

        $pdf->Cell(20,$h,'Group',1,0,'C',true);
        $pdf->Cell(20,$h,'Code',1,0,'C',true);
        $pdf->Cell(88,$h,'Course',1,0,'C',true);
        $pdf->Cell(7,$h,'Std',1,1,'C',true);


        // --------------

        $pdf->SetFont('Times','',8);
        $h = 4;
        for($c=0;$c<$h_fx;$c++){
            $course1 = (isset($dataDetailExam['Course'][$c])) ? $dataDetailExam['Course'][$c] : '' ;
            $course2 = (isset($dataDetailExam_2['Course'][$c])) ? $dataDetailExam_2['Course'][$c] : '' ;

            $border_1 = ($course1!='') ? : 0;
            $border_2 = ($course2!='') ? : 0;

            $group_1 = ($course1!='') ? $course1['ClassGroup'] :'';
            $group_2 = ($course2!='') ? $course2['ClassGroup'] :'';

            $code_1 = ($course1!='') ? $course1['MKCode'] :'';
            $code_2 = ($course2!='') ? $course2['MKCode'] :'';

            $c_name_1 = ($course1!='') ? $course1['Course'] :'';
            $c_name_2 = ($course2!='') ? $course2['Course'] :'';

            $std_1 = ($course1!='') ? count($course1['DetailStudents']) :'';
            $std_2 = ($course2!='') ? count($course2['DetailStudents']) :'';

            $pdf->Cell(20,$h,$group_1,$border_1,0,'C');
            $pdf->Cell(20,$h,$code_1,$border_1,0,'C');
            $pdf->Cell(88,$h,$c_name_1,$border_1,0,'L');
            $pdf->Cell(7,$h,$std_1,$border_1,0,'C');


            $pdf->Cell(17,$h,'',0,0);

            $pdf->Cell(20,$h,$group_2,$border_2,0,'C');
            $pdf->Cell(20,$h,$code_2,$border_2,0,'C');
            $pdf->Cell(88,$h,$c_name_2,$border_2,0,'L');
            $pdf->Cell(7,$h,$std_2,$border_2,1,'C');

        }


        $pdf->Ln(5);

        $pdf->SetFont('Times','I',7);
        $pdf->Cell(135,$h,'Download On : '.date("d M Y H:i:s").' | '.chr(169).' Podomoro University',0,0,'R');

        $pdf->Cell(17,$h,'',0,0);

        $pdf->Cell(135,$h,'Download On : '.date("d M Y H:i:s").' | '.chr(169).' Podomoro University',0,1,'R');


    }



    //===========================

    private function header_exam_layout($pdf,$dataExam,$dataCourse){


        $pdf->Image(base_url('images/icon/logo-l.png'),10,10,30);

        $pdf->SetFont('Arial','B',10);

        $exam = ($dataExam['Type']=='uts' || $dataExam['Type']=='UTS') ? 'MID EXAM '.strtoupper($dataExam['Semester'])
            : 'FINAL EXAM '.strtoupper($dataExam['Semester']) ;

        $pdf->Cell(45,9,'',0,0,'C');
        $pdf->Cell(230,9,'SEATING MAP '.$exam,1,1,'C');

        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(0,5,'Page : '.$pdf->PageNo().' of {nb}',0,1,'R');

        $pdf->SetFont('Arial','',10);

        $dataCourseName = $dataCourse['CourseEng'];
        $course = (strlen($dataCourseName)>=55) ? substr($dataCourseName,0,55).'_' : $dataCourseName;

        $space_header = 5;
        $pdf->Cell(45,$space_header,'',0,0);
        $pdf->Cell(20,$space_header,'Course',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(122,$space_header, $dataCourse['MKCode'].' - '.$course,0,0);
        $pdf->Cell(20,$space_header,'Date',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(62,$space_header,date('l, d F, Y',strtotime($dataExam['ExamDate'])),0,1);

        $pdf->Cell(45,$space_header,'',0,0);
        $pdf->Cell(20,$space_header,'Pengawas 1',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(122,$space_header,$dataExam['Name_P1'],0,0);
        $pdf->Cell(20,$space_header,'Time',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(62,$space_header,substr($dataExam['ExamStart'],0,5).' - '.substr($dataExam['ExamEnd'],0,5),0,1);

        $pdf->Cell(45,$space_header,'',0,0);
        $pdf->Cell(20,$space_header,'Pengawas 2',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(122,$space_header,$dataExam['Name_P2'],0,0);
        $pdf->Cell(20,$space_header,'Room',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(62,$space_header,$dataExam['Room'],0,1);

        $pdf->Cell(275,7,'',0,1);
        $pdf->Cell(275,0.3,'',1,1);

        // Lecturer
        if($dataExam['LectureDesk']=='left'){
            $pdf->Image(base_url('images/icon/lecturerdesk.png'),25,50,30);
        } else {
            $pdf->Image(base_url('images/icon/lecturerdesk.png'),250,50,30);
        }


        $pdf->SetFillColor(226, 226, 226);
        $pdf->Cell(275,7,'',0,1);
        $pdf->Cell(55,5,'',0,0);
        $pdf->Cell(170,7,'Board',1,1,'C',true);

        $pdf->Cell(275,15,'',0,1);

        $pdf->AliasNbPages();



        $base_x = 10;
        $base_y = 75;
        $koor_x = $base_x;
        $koor_y = $base_y;
        $no=1;

        $jml_deret = $dataExam['DeretForExam']; // Dinamis
        $total_w = 275;
        $space = 2;
        $width = ($total_w - (($jml_deret - 1) * $space)) / $jml_deret;

        $height_panel = 7;
        $pdf->SetFont('Arial','',10);
        $pdf->setFillColor(255,255,102);

        // Total Students
        $array_stf = $dataCourse['DetailStudent'];
        $totalStudent = count($array_stf);

        for($m=0;$m<$totalStudent;$m++){
            $d_Std = $array_stf[$m];

            // NIM
            $pdf->SetXY($koor_x,$koor_y);
            $pdf->Cell($width,$height_panel,$d_Std['NPM'],1,0,'C',true);

            // Name
            $pdf->SetXY($koor_x,($koor_y+7));
            $exName = explode(' ',$d_Std['Name']);
            $nm = (count($exName)>=3) ? trim($exName[0]).' '.trim($exName[1]) : $d_Std['Name'];
            $exp_name = explode(" ",$nm);
            $name_fil = (count($exp_name)>3) ? $exp_name[0].' '.$exp_name[1] : $nm;
            $name = (strlen($name_fil)<=15) ? $name_fil : $exp_name[0];
            $pdf->Cell($width,7,$name,1,0,'C');

            $koor_x = $koor_x + $space;
            if($no%$jml_deret==0){
                $koor_x = $base_x;
                $koor_y = $koor_y + (($height_panel * 2) + 5);

                if($pdf->GetY()>170){
                    // membuat halaman baru
//                    $this->header_exam_layout($pdf,$dataExam,$dataCourse);
                    $pdf->SetFont('Arial','',10);
                    $pdf->setFillColor(255,255,102);
                    $koor_x = 10;
                    $koor_y = 10;
                    $pdf->AddPage();

                }
            } else {
                $koor_x = $koor_x+$width;
            }

            $no++;
        }

    }

    public function exam_layout($ExamID){

        $data = $this->m_save_to_pdf->getExamByID($ExamID);

        if(count($data)>0){

            $pdf = new FPDF('l','mm','A4');


            $dataExam = $data[0];

            for($i=0;$i<count($dataExam['Course']);$i++){
                $dataCourse = $dataExam['Course'][$i];


                // membuat halaman baru
                $pdf->AddPage();
                $this->header_exam_layout($pdf,$dataExam,$dataCourse);
            }

            //        $pdf->Output('Study_Card.pdf','D');
            $pdf->Output('layout.pdf','I');

        } else {

        }


    }

    private function header_exam($pdf){

        $pdf->Image(base_url('images/icon/logo-l.png'),10,10,25);

        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(35,11,'',0,0,'C');
        $pdf->Cell(150,5,'UNIVERSITAS AGUNG PODOMORO',0,1,'C');

        $pdf->SetFont('Arial','',10);
        $pdf->Cell(35,5,'',0,0,'C');
        $pdf->Cell(150,5,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,1,'C');
        $pdf->Cell(35,5,'',0,0,'C');
        $pdf->Cell(150,5,'Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');
        $pdf->Cell(35,5,'',0,0,'C');
        $pdf->Cell(150,5,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Cell(185,7,'',0,1);
        $pdf->Cell(185,0.3,'',1,1);

    }

    // Naskah Soal dan Lembar Jawaban
    public function draft_questions_answer_sheet(){
        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        $pdf = new FPDF('l','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();

        $pdf->SetMargins(5,5,0);

        $totalGroup = count($data_arr['Course']);
        $h_border = ($totalGroup * 8) + 100;
        $pdf->Rect(3, 7, 140, $h_border);
        $pdf->Rect(155, 7, 140, $h_border);

        $pdf->Image(base_url('images/icon/favicon.png'),5,10,13);
        $pdf->Image(base_url('images/icon/favicon.png'),158,10,13);

        $h=5;

        $pdf->SetFont('Times','B',11);
        $pdf->Cell(133,$h,'Universitas Agung Podomoro',0,0,'C');
        $pdf->Cell(20,$h,'',0,0);
        $pdf->Cell(132,$h,'Universitas Agung Podomoro',0,1,'C');

        $h=3.5;

        $pdf->SetFont('Times','',8);
        $pdf->Cell(135,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,1,'C');

        $pdf->Cell(135,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');

        $pdf->Cell(135,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Line(3,27,143,27);
        $pdf->Line(155,27,295,27);

        $pdf->Ln(4);

        $pdf->SetFont('Times','B',8);

        $pdf->Cell(135,$h,'DRAFT QUESTIONS',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'ANSWER SHEET',0,1,'C');

        $xam_t = ($data_arr['Exam']=='uts' || $data_arr['Exam']=='UTS') ? 'MID EXAM' : 'FINAL EXAM';

        $pdf->Cell(135,$h,'ATTENDANCE '.$xam_t,0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'ATTENDANCE '.$xam_t,0,1,'C');

        $pdf->Cell(135,$h,'EVEN SEMESTER',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'EVEN SEMESTER',0,1,'C');

        $pdf->Cell(135,$h,'ACADEMIC YEAR '.$data_arr['Semester'],0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'ACADEMIC YEAR '.$data_arr['Semester'],0,1,'C');

        $pdf->Ln(5);

        //++++++++++++++++++++++++++++++++++++++++++++

        $pdf->SetFont('Times','',9);
        $h=5;

        $pdf->Cell(25,$h,'Exam',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(105,$h,$data_arr['Exam'],0,0,'L');

        $pdf->Cell(20,$h,'',0,0);


        $pdf->Cell(40,$h,'Exam',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(90,$h,$data_arr['Exam'],0,1,'L');

        //======================================


        $pdf->Cell(25,$h,'Day, Date ',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(105,$h,$data_arr['Date'],0,0,'L');

        $pdf->Cell(20,$h,'',0,0);

        $pdf->Cell(40,$h,'Day, Date ',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(90,$h,$data_arr['Date'],0,1,'L');

        //======================================

        $pdf->Cell(25,$h,'Time, Room',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(105,$h,$data_arr['Time'].', '.$data_arr['Room'],0,0,'L');

        $pdf->Cell(20,$h,'',0,0);

        $pdf->Cell(40,$h,'Time, Room',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(90,$h,$data_arr['Time'].', '.$data_arr['Room'],0,1,'L');


        //======================================

        $pdf->Cell(25,$h,'Total Script',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(105,$h,'_ _ _ _ _ _ _ _ _ _ _ _',0,0,'L');

        $pdf->Cell(20,$h,'',0,0);

        $pdf->Cell(40,$h,'Number of Answer Sheet',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(90,$h,'_ _ _ _ _ _ _ _ _ _ _ _',0,1,'L');


        //=============================================

        $pdf->Ln(6);
//        $pdf->Rect(10, 75, 135, 23);
//        $pdf->Rect(156, 75, 135, 23);

        //======================================

        $pdf->Cell(25,$h,'Pengawas 1',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(105,$h,$data_arr['Pengawas_1'],0,0,'L');

        $pdf->Cell(20,$h,'',0,0);

        $pdf->Cell(40,$h,'Pengawas 1',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(90,$h,$data_arr['Pengawas_1'],0,1,'L');

        //======================================

        $pdf->Cell(25,$h,'Pengawas 2',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(105,$h,$data_arr['Pengawas_2'],0,0,'L');

        $pdf->Cell(20,$h,'',0,0);

        $pdf->Cell(40,$h,'Pengawas 2',0,0,'L');
        $pdf->Cell(2,$h,':',0,0,'C');
        $pdf->Cell(90,$h,$data_arr['Pengawas_2'],0,1,'L');



        //======================================

        $pdf->Ln(7);

        $pdf->SetFont('Times','B',9);
        $h = 6;
        $pdf->setFillColor(255,255,102);

        $pdf->Cell(20,$h,'Group',1,0,'C',true);
        $pdf->Cell(108,$h,'Course',1,0,'C',true);
        $pdf->Cell(7,$h,'Std',1,0,'C',true);


        $pdf->Cell(17,$h,'',0,0);

        $pdf->Cell(20,$h,'Group',1,0,'C',true);
        $pdf->Cell(108,$h,'Course',1,0,'C',true);
        $pdf->Cell(7,$h,'Std',1,1,'C',true);


        // --------------

        $pdf->SetFont('Times','',8);
        $h = 4;

        for($i=0;$i<$totalGroup;$i++){

            $c = (array) $data_arr['Course'][$i];

            $courseName = (strlen($c['CourseEng'])>=65) ? substr($c['CourseEng'],0,65).'_' : $c['CourseEng'];

            $pdf->Cell(20,$h,$c['ClassGroup'],'LRT',0,'C');
            $pdf->SetFont('Times','B',8);
//            $pdf->Cell(108,$h,$c['MKCode'].' - ','LRT',0,'L');
            $pdf->Cell(108,$h,$c['MKCode'].' - '.$courseName,'LRT',0,'L');
            $pdf->SetFont('Times','',8);
            $pdf->Cell(7,$h,count($c['DetailsStudent']),'LRT',0,'C');


            $pdf->Cell(17,$h,'',0,0);

            $pdf->Cell(20,$h,$c['ClassGroup'],'LRT',0,'C');
            $pdf->SetFont('Times','B',8);
            $pdf->Cell(108,$h,$c['MKCode'].' - '.$courseName,'LRT',0,'L');
            $pdf->SetFont('Times','',8);
            $pdf->Cell(7,$h,count($c['DetailsStudent']),'LRT',1,'C');


            // *******


            $pdf->Cell(20,$h,'','LRB',0,'C');
            $pdf->Cell(108,$h,'(Co) '.$c['Coordinator'],'LRB',0,'L');
            $pdf->Cell(7,$h,'','LRB',0,'C');

            $pdf->Cell(17,$h,'',0,0);

            $pdf->Cell(20,$h,'','LRB',0,'C');
            $pdf->Cell(108,$h,'(Co) '.$c['Coordinator'],'LRB',0,'L');
            $pdf->Cell(7,$h,'','LRB',1,'C');
        }



        $pdf->Ln(5);

        $pdf->SetFont('Times','I',7);
        $pdf->Cell(135,$h,'Download On : '.date("d M Y H:i:s").' | '.chr(169).' Podomoro University',0,0,'R');

        $pdf->Cell(17,$h,'',0,0);

        $pdf->Cell(135,$h,'Download On : '.date("d M Y H:i:s").' | '.chr(169).' Podomoro University',0,1,'R');


        $pdf->Output('draft_questions_answer_sheet.pdf','I');
    }

    // Daftar Hadir
    public function attendance_list(){

        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

//        print_r($data_arr);
//        exit;



        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru


        $dataExam = (array) $data_arr['Exam'];
        $totalCourse = count($data_arr['Course']);

        $width_attd = 9;

        for($c=0;$c<$totalCourse;$c++){
            $pdf->AddPage();
            $this->header_exam($pdf);
            $dataCourse = (array) $data_arr['Course'][$c];
            $this->header_attendance($pdf,$dataExam,$dataCourse);

            $this->header_attd_table($pdf);
            // Data Student
            $totalStd = count($dataCourse['DetailsStudent']);
            $no = 1;
            for($t=0;$t<$totalStd;$t++){
                $st_detail = (array) $dataCourse['DetailsStudent'][$t];

                $pdf->Cell(10,$width_attd,$no++,1,0,'C');
                $pdf->Cell(25,$width_attd,$st_detail['NPM'],1,0,'C');
                $pdf->Cell(95,$width_attd,' '.ucwords(strtolower($st_detail['Name'])),1,0,'L');
                $pdf->Cell(35,$width_attd,'',1,0,'C');
                $pdf->Cell(20,$width_attd,'',1,1,'C');

                if($pdf->GetY()>=265){
                    $pdf->AddPage();
                    $this->header_attd_table($pdf);
                }
            }


            $pdf->SetFont('Arial','I',9);
            $pdf->Cell(185,5,'',0,1,'');
            $pdf->Cell(130,3,'',0,0,'R');
            $pdf->Cell(55,3,'Sign by Lecturer : ',0,1,'C');

            $pdf->Cell(185,17,'',0,1,'');

            $pdf->Cell(130,3,'',0,0,'R');
            $pdf->Cell(55,3,'( ...................... )',0,1,'C');


            $pdf->Cell(185,7,'',0,1,'');
            $pdf->SetFont('Times','I',7);
            $pdf->Cell(185,3,'Download On : '.date("d M Y H:i:s").' | '.chr(169).' Podomoro University',0,1,'R');


        }

        $pdf->Output('attendance_exam_list.pdf','I');

    }

    // Berita Acara dan Bukti Serah Terima berkas
    public function news_event2(){
        $pdf = new FPDF('l','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();

        $pdf->SetMargins(5,5,0);

        $totalGroup = 3;
        $h_border = ($totalGroup * 8) + 100;
        $pdf->Rect(3, 7, 140, $h_border);
        $pdf->Rect(155, 7, 140, $h_border);

        $pdf->Image(base_url('images/icon/favicon.png'),5,10,13);
        $pdf->Image(base_url('images/icon/favicon.png'),158,10,13);

        $h=5;

        $pdf->SetFont('Times','B',11);
        $pdf->Cell(133,$h,'Universitas Agung Podomoro',0,0,'C');
        $pdf->Cell(20,$h,'',0,0);
        $pdf->Cell(132,$h,'Universitas Agung Podomoro',0,1,'C');

        $h=3.5;

        $pdf->SetFont('Times','',8);
        $pdf->Cell(135,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28',0,1,'C');

        $pdf->Cell(135,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'Tel: 021 292 00456 Fax: 021 292 00455',0,1,'C');

        $pdf->Cell(135,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'website : www.podomorouniversity.ac.id email : admissions@podomorouniversity.ac.id',0,1,'C');

        $pdf->Line(3,27,143,27);
        $pdf->Line(155,27,295,27);

        $pdf->Ln(4);

        $pdf->SetFont('Times','B',8);

        $pdf->Cell(135,$h,'DRAFT QUESTIONS',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'BERITA ACARA PENYERAHAN BERKAS UJIAN',0,1,'C');

        $pdf->Output('news_event.pdf','I');
    }


    // Berita Acara
    public function news_event1(){

        $pdf = new FPDF('l','mm','A5');
        // membuat halaman baru
        $pdf->AddPage();

        $pdf->Rect(10, 7, 35, 25);
        $pdf->Rect(45, 7, 115, 25);
        $pdf->Rect(160, 7, 35, 25);

        $pdf->Image(base_url('images/icon/logo-l.png'),15,8,25);

        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(35,11,'',0,0,'C');
        $pdf->Cell(115,6,'FINAL EXAMINATION',0,0,'C');
        $pdf->Cell(35,6,'',0,1,'C');

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(35,11,'',0,0,'C');
        $pdf->Cell(115,6,'EVEN SEMESTER',0,0,'C');
        $pdf->Cell(35,6,'',0,1,'C');

        $pdf->Cell(35,11,'',0,0,'C');
        $pdf->Cell(115,6,'ACADEMIC YEAR 2017/2018',0,0,'C');
        $pdf->Cell(35,6,'',0,1,'C');

        $pdf->Cell(185,7,'',0,1);
//        $pdf->Cell(185,0.3,'',1,1);

        $pdf->SetFont('Arial','',10);
        $pdf->Cell(185,7,'',0,1);

        $pdf->Cell(20,7,'Code',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(90,7,'HOT0014',0,0);

        $pdf->Cell(25,7,'Study Program',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(40,7,'HBP',0,1);

        // ---

        $pdf->Cell(20,7,'Subject',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(90,7,'Food and Beverage - Service Theory',0,0);

        $pdf->Cell(25,7,'Group',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(40,7,'HBP8A',0,1);

        // ---

        $pdf->Cell(20,7,'Day, date',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(90,7,'Monday, 25 June 2018',0,0);

        $pdf->Cell(25,7,'Exam Type',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(40,7,'Open / Close',0,1);

        // ---

        $pdf->Cell(20,7,'Time',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(90,7,'08:00 - 09:40',0,0);

        $pdf->Cell(25,7,'Calculator',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(40,7,'Yes / No',0,1);

        // ---

        $pdf->Cell(20,7,'Room',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(90,7,'504',0,0);

        $pdf->Cell(25,7,'Dictionary',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(40,7,'Yes / No',0,1);


        // ------------
        $pdf->Rect(10, 85, 185, 30);
        $pdf->Cell(185,10,'',0,1);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(185,10,'Note test execution',0,1,'C');

        $pdf->SetFont('Arial','',10);
        $pdf->Cell(5,7,'',0,0);
        $pdf->Cell(45,7,'Number of Participants',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(130,7,'_____________',0,1);

        $pdf->Cell(5,7,'',0,0);
        $pdf->Cell(45,7,'The number in attendance',0,0);
        $pdf->Cell(5,7,':',0,0,'C');
        $pdf->Cell(130,7,'_____________',0,1);

        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(185,13,'Note : *) Strikethrough the unnecessary items',0,1,'L');


        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(185,3,'Download On : '.date("d M Y H:i:s").' | '.chr(169).' IT Podomoro University',0,1,'R');

//        $pdf->Output('Study_Card.pdf','D');
        $pdf->Output();

    }

    private function header_attendance($pdf,$dataExam,$dataCourse){

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(185,7,'',0,1);

        $xam_t = ($dataExam['Exam']=='uts' || $dataExam['Exam']=='UTS') ? 'MID EXAM' : 'FINAL EXAM';

        $pdf->Cell(185,5,'ATTENDANCE '.$xam_t,0,1,'C');
        $pdf->Cell(185,5,'EVEN SEMESTER ACADEMIC YEAR '.$dataExam['Semester'],0,1,'C');

        $pdf->Cell(185,7,'',0,1,'');
        $pdf->SetFont('Arial','',9);
//        $pdf->Cell(25,5,'Code',0,0);
//        $pdf->Cell(3,5,':',0,0,'C');
//        $pdf->Cell(81,5,$dataCourse['MKCode'],0,0);
//
//        $pdf->Cell(25,5,'Study Program',0,0);
//        $pdf->Cell(3,5,':',0,0,'C');
//        $pdf->Cell(50,5,'HBP',0,1);

        // ---

        $cName = (strlen($dataCourse['CourseEng'])>=40)
            ? substr($dataCourse['CourseEng'],0,40).'_'
            : $dataCourse['CourseEng'];

        $pdf->Cell(25,5,'Code | Course',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(81,5,$dataCourse['MKCode'].' - '.$cName,0,0);

        $pdf->Cell(25,5,'Day, date',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(50,5,$dataExam['Date'],10,1);

        // ---

        $pdf->Cell(25,5,'Group',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(81,5,'HBP8A',0,0);

        $pdf->Cell(25,5,'Time',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(50,5,$dataExam['Time'],0,1);

        // ---

        $pdf->Cell(25,5,'Lecturer/s',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(81,5,$dataCourse['Coordinator'],0,0);

        $pdf->Cell(25,5,'Room',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(50,5,$dataExam['Room'],0,1);
    }

    private function header_attd_table($pdf){
        $pdf->Cell(185,3,'',0,1,'');

        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(185,7,'Page : '.$pdf->PageNo().' of {nb}',0,1,'R');
        $pdf->AliasNbPages();

        $width_attd = 9;
        $pdf->SetFillColor(226, 226, 226);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,$width_attd,'No',1,0,'C',true);
        $pdf->Cell(25,$width_attd,'NPM',1,0,'C',true);
        $pdf->Cell(95,$width_attd,'Name',1,0,'C',true);
        $pdf->Cell(35,$width_attd,'Sign',1,0,'C',true);
        $pdf->Cell(20,$width_attd,'Score',1,1,'C',true);
        $pdf->SetFont('Arial','',10);
    }




    // ========= Score ==========

    private function header_report_exam($pdf,$exam){
        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(0,7,'Page : '.$pdf->PageNo().' of {nb}',0,0,'R');
        $pdf->AliasNbPages();

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(185,7,'',0,1);
        $pdf->Cell(185,5,'Food and Beverage - Service Theory',0,1,'C');
        $pdf->Cell(185,5,'HOT0014',0,1,'C');
//        $pdf->Cell(185,5,'EVEN SEMESTER ACADEMIC YEAR 2017/2018',0,1,'C');

        $pdf->Cell(185,7,'',0,1,'');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(25,5,'Code',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(81,5,'HOT0014',0,0);

        $pdf->Cell(25,5,'Study Program',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(50,5,'HBP',0,1);

        // ---

        $pdf->Cell(25,5,'Course',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(81,5,'Food and Beverage - Service Theory',0,0);

        $pdf->Cell(25,5,'Day, date',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(50,5,'Monday, 25 Desember 2018',10,1);

        // ---

        $pdf->Cell(25,5,'Code',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(81,5,'HOT0014',0,0);

        $pdf->Cell(25,5,'Study Program',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(50,5,'HBP',0,1);

        // ---

        $pdf->Cell(25,5,'Group',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(81,5,'HBP8A',0,0);

        $pdf->Cell(25,5,'Time',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(50,5,'08:00 - 09:40',0,1);

        // ---

        $pdf->Cell(25,5,'Lecturer/s',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(81,5,'Michael Yadisaputra',0,0);

        $pdf->Cell(25,5,'Room',0,0);
        $pdf->Cell(3,5,':',0,0,'C');
        $pdf->Cell(50,5,'504',0,1);
    }

    // UTS
    public function report_uts(){

        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        $this->header_exam($pdf);

        $this->header_report_exam($pdf,'UTS');


//        $pdf->Output('Study_Card.pdf','D');
        $pdf->Output();

    }

    public function getpdfkwitansi($token)
    {
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        // print_r($data_arr);

        $pdf = new FPDF('l','mm','A5');
        // membuat halaman baru
        $pdf->AddPage();

        $pdf->SetMargins(10,10,10,10);
        $pdf->SetAutoPageBreak(true, 0);

        // Logo
        $pdf->Image('./images/logo_tr.png',5,5,50);

        $pdf->SetFont('Arial','',17);
        $pdf->Cell(200, 0, 'Tanda Terima Pembayaran Mahasiswa', 0, 1, 'C', 0);

        $pdf->SetFont('Arial','',15);
        $pdf->Cell(200, 15, 'Tahun Akademik', 0, 1, 'C', 0);

        $pdf->SetFont('Arial','',15);
        $pdf->Cell(200, 0, $data_arr['semester'], 0, 1, 'C', 0);

        $pdf->Line(10, 30, 200, 30);

        $pdf->Cell(200, 7, '', 0, 1, 'C', 0);

        $pdf->SetFont('Arial','',10);
        $pdf->Cell(100, 5, '', 0, 0, 'C');
        $this->load->model('master/m_master');
        $date = date('Y-m-d');
        $date = $this->m_master->getIndoBulan($date);
        $pdf->Cell(90, 5, 'Tanggal Cetak : '.$date, 0, 1, 'R');

        $pdf->Cell(35, 7, 'Nama', 0, 0, 'L');
        $pdf->Cell(5, 7, ':', 0, 0, 'L');
        $pdf->Cell(100, 7, $data_arr['nama'], 0, 1, 'L');

        $pdf->Cell(35, 7, 'NPM', 0, 0, 'L');
        $pdf->Cell(5, 7, ':', 0, 0, 'L');
        $pdf->Cell(100, 7, $data_arr['npm'], 0, 1, 'L');

        $pdf->Cell(35, 7, 'Tipe Pembayaran', 0, 0, 'L');
        $pdf->Cell(5, 7, ':', 0, 0, 'L');
        $pdf->Cell(100, 7, $data_arr['ptid'], 0, 1, 'L');

        $pdf->Cell(35, 7, 'Program Studi', 0, 0, 'L');
        $pdf->Cell(5, 7, ':', 0, 0, 'L');
        $pdf->Cell(100, 7, $data_arr['prodi'], 0, 1, 'L');

        $pdf->Cell(35, 7, 'Virtual Account', 0, 0, 'L');
        $pdf->Cell(5, 7, ':', 0, 0, 'L');
        $pdf->Cell(100, 7, $data_arr['va'], 0, 1, 'L');

        $pdf->Cell(35, 7, 'BilingID', 0, 0, 'L');
        $pdf->Cell(5, 7, ':', 0, 0, 'L');
        $pdf->Cell(100, 7, $data_arr['bilingid'], 0, 1, 'L');

        $pdf->Cell(35, 7, 'Invoice', 0, 0, 'L');
        $pdf->Cell(5, 7, ':', 0, 0, 'L');
        $pdf->Cell(100, 7, $data_arr['invoice'], 0, 1, 'L');

        $pdf->Cell(35, 7, 'Waktu Pembayaran', 0, 0, 'L');
        $pdf->Cell(5, 7, ':', 0, 0, 'L');
        $pdf->Cell(100, 7, $data_arr['Time'], 0, 1, 'L');

        $pdf->Cell(200, 7, '', 0, 1, 'C', 0);
        $pdf->Cell(190, 7, 'Note : ', 0, 1, 'L');
        $pdf->Cell(190, 7, 'Tanda terima ini merupakan bukti pembayaran yang diterbitkan oleh Podomoro University.', 0, 1, 'L');
        $pdf->Cell(190, 7, 'Jika terdapat kekeliruan harap hubungi bagian Admisi dibawah ini.', 0, 1, 'L');

        $pdf->Line(10, 135, 200, 135);
        $setY = 135;
        $pdf->SetFont('Arial','',6);
        $pdf->SetXY(40,$setY);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(190, 5, 'Admission Office :  Central Park Mall, Lantai 3, Unit 112, Podomoro City, JL Letjen S. Parman Kav.28, Jakarta Barat 11470', 0, 1, 'L', 0);

        $setY = 138;
        $pdf->SetFont('Arial','',6);
        $pdf->SetXY(43,$setY);
        $pdf->SetTextColor(0,0,0);
        // $this->mypdf->SetFillColor(0,0,0);
        $pdf->Cell(190, 5, 'Telp : (021) 292 00 456    Email : admission@podomorouniversity.ac.id   Website : www.podomorouniversity.ac.id', 0, 1, 'L', 0);

        $pdf->AliasNbPages();

        $pdf->Output();
    }


    // ===== Rekap Exam Schedule =====
    public function recapExamSchedule(){

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        $this->headerDefaultPotret($pdf,'FM-UAP/AKD-13-02');

        $h = 6;
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(195,$h,'Schedule Mid Exam Odd Semester Academic Year 2018/2019',0,1,'C');

        $pdf->Ln(5);
        // 195
        $pdf->SetFillColor(226, 226, 226);
        $pdf->SetFont('Times','B',8);
        $pdf->Cell(7,$h,'No',1,0,'C',true);
        $pdf->Cell(30,$h,'Date, Time',1,0,'C',true);
        $pdf->Cell(50,$h,'Course',1,0,'C',true);
        $pdf->Cell(30,$h,'Lecturer',1,0,'C',true);
        $pdf->Cell(15,$h,'Group',1,0,'C',true);
        $pdf->Cell(10,$h,'Std',1,0,'C',true);
        $pdf->Cell(18,$h,'Room',1,0,'C',true);
        $pdf->Cell(35,$h,'Invigilator',1,1,'C',true);


        $pdf->SetFont('Times','',8);
        $h = 4;

        $pdf->Cell(7,$h,'1000','LRT',0,'C');
        $pdf->Cell(30,$h,'Monday, 08 Oct 2018','LRT',0,'C');
        $pdf->Cell(50,$h,'Course','LRT',0,'L');
        $pdf->Cell(30,$h,'Lecturer','LRT',0,'L');
        $pdf->Cell(15,$h,'Group','LRT',0,'C');
        $pdf->Cell(10,$h,'Std','LRT',0,'C');
        $pdf->Cell(18,$h,'Room','LRT',0,'C');
        $pdf->Cell(35,$h,'Invigilator','LRT',1,'L');

        $pdf->Cell(7,$h,'','LRB',0,'C');
        $pdf->Cell(30,$h,'08:00 - 09:10 | 100 mnt','LRB',0,'C');
        $pdf->Cell(50,$h,'Course','LRB',0,'L');
        $pdf->Cell(30,$h,'Lecturer','LRB',0,'L');
        $pdf->Cell(15,$h,'Group','LRB',0,'C');
        $pdf->Cell(10,$h,'Std','LRB',0,'C');
        $pdf->Cell(18,$h,'Room','LRB',0,'C');
        $pdf->Cell(35,$h,'Invigilator','LRB',1,'L');


        $pdf->Output('document_recap_exam_schedule.pdf','I');
    }

    // ==== Penutup Rekap Exam Schedule =====


    // ====== Transcript Semestera =======

    public function temp_transcript(){

        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        $dataStudent = $this->m_save_to_pdf->getTranscript($data_arr['DBStudent'],$data_arr['NPM']);
        $Student = $dataStudent['Student'][0];
        $dataTempTr = $dataStudent['TempTranscript'][0];

        $pdf = new FPDF('P','mm','A4');

        // membuat halaman baru
        $pdf->SetMargins(10,5,10);
        $pdf->AddPage();

        $this->header_temp_transcript($pdf,$dataTempTr);

        $h=3.5;

        $tr = 'TRANSKRIP SEMENTARA';

        $pdf->Cell(190,$h,$tr,0,1,'C');

        $pdf->SetFont('dinprolight','',7);
        $pdf->Cell(190,$h,'No. : '.$dataTempTr['No'],0,1,'C');

        $border = 0;

        $l_left = 38;
        $sp_left = 1;
        $fill_left = 75;

        $l_right = 22;
        $sp_right = 1;
        $fill_right = 53;

        $h=3.3;
        $pdf->SetFont('dinpromedium','',7);
        $pdf->Ln(5);

        $pdf->Cell($l_left,$h,'Nama',$border,0,'L');
        $pdf->Cell($sp_left,$h,':',$border,0,'C');
        $pdf->Cell($fill_left,$h,ucwords(strtolower($Student['Name'])),$border,0,'L');
        $pdf->Cell($l_right,$h,'Fakultas',$border,0,'L');
        $pdf->Cell($sp_right,$h,':',$border,0,'C');
        $pdf->Cell($fill_right,$h,$Student['FacultyName'],$border,1,'L');

        $pdf->Cell($l_left,$h,'NIM',$border,0,'L');
        $pdf->Cell($sp_left,$h,':',$border,0,'C');
        $pdf->Cell($fill_left,$h,$Student['NPM'],$border,0,'L');
        $pdf->Cell($l_right,$h,'Program Studi',$border,0,'L');
        $pdf->Cell($sp_right,$h,':',$border,0,'C');
        $pdf->Cell($fill_right,$h,ucwords(strtolower($Student['Prodi'])),$border,1,'L');

        $e = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? '%#d' : '%e';
        $pdf->Cell($l_left,$h,'Tempat dan Tanggal Lahir',$border,0,'L');
        $pdf->Cell($sp_left,$h,':',$border,0,'C');
        $pdf->Cell($fill_left,$h,ucwords(strtolower($Student['PlaceOfBirth'])).', '.strftime($e." %B %Y",strtotime($Student['DateOfBirth'])),$border,1,'L');

        $pdf->Ln(2);

        $this->headerTable('ind',$pdf);
        $this->body_temp_transcript('ind',$pdf,$dataStudent,$dataTempTr);


        // +++++++ ENGLISH ++++++++

        // membuat halaman baru
        $pdf->SetMargins(10,5,10);
        $pdf->AddPage();

        $this->header_temp_transcript($pdf,$dataTempTr);

        $h=3.5;

        $tr = 'RECORD OF ACADEMIC ACHIEVEMENT';

        $pdf->Cell(190,$h,$tr,0,1,'C');

        $pdf->SetFont('dinprolight','',7);
        $pdf->Cell(190,$h,'No. : '.$dataTempTr['No'],0,1,'C');

        $border = 0;


        $h=3.3;
        $pdf->SetFont('dinpromedium','',7);
        $pdf->Ln(5);

        $pdf->Cell($l_left,$h,'Name',$border,0,'L');
        $pdf->Cell($sp_left,$h,':',$border,0,'C');
        $pdf->Cell($fill_left,$h,ucwords(strtolower($Student['Name'])),$border,0,'L');
        $pdf->Cell($l_right,$h,'Faculty',$border,0,'L');
        $pdf->Cell($sp_right,$h,':',$border,0,'C');
        $pdf->Cell($fill_right,$h,$Student['FacultyName'],$border,1,'L');


        $pdf->Cell($l_left,$h,'Student ID',$border,0,'L');
        $pdf->Cell($sp_left,$h,':',$border,0,'C');
        $pdf->Cell($fill_left,$h,$Student['NPM'],$border,0,'L');
        $pdf->Cell($l_right,$h,'Department',$border,0,'L');
        $pdf->Cell($sp_right,$h,':',$border,0,'C');
        $pdf->Cell($fill_right,$h,ucwords(strtolower($Student['ProdiEng'])),$border,1,'L');

        $pdf->Cell($l_left,$h,'Place, Date of Birth',$border,0,'L');
        $pdf->Cell($sp_left,$h,':',$border,0,'C');
        $pdf->Cell($fill_left,$h,ucwords(strtolower($Student['PlaceOfBirth'])).', '.date('F j, Y',strtotime($Student['DateOfBirth'])),$border,1,'L');

        $pdf->Ln(2);

        $this->headerTable('eng',$pdf);
        $this->body_temp_transcript('eng',$pdf,$dataStudent,$dataTempTr);




        $nameF = str_replace(' ','_',strtoupper($Student['Name']));
        $pdf->Output('TEMP_TRNSCPT_'.$Student['NPM'].'_'.$nameF.'.pdf','I');
    }

    private function header_temp_transcript2($lang,$pdf,$Student,$dataTempTr){
        $pdf->Image(base_url('images/logo.png'),10,5,40);

        $h=3.5;
        $pdf->Ln(5);
        $pdf->SetFont('dinpromedium','',9);

        $tr = ($lang=='ind') ? 'TRANSKRIP SEMENTARA' : 'RECORD OF ACADEMIC ACHIEVEMENT';

        $pdf->Cell(190,$h,$tr,0,1,'C');

        $pdf->SetFont('dinprolight','',7);
        $pdf->Cell(190,$h,'No. : '.$dataTempTr['No'],0,1,'C');


        $border = 0;

        $l_left = 38;
        $sp_left = 1;
        $fill_left = 93;

        $l_right = 22;
        $sp_right = 1;
        $fill_right = 35;

        $h=3.3;
        $pdf->SetFont('dinpromedium','',7);
        $pdf->Ln(1.5);

        if($lang=='ind'){

            $pdf->Cell(190,$h,'Fakultas : '.$Student['FacultyName'],0,1,'C');
            $pdf->Cell(190,$h,'Program Studi : '.ucwords(strtolower($Student['Prodi'])),0,1,'C');
            $pdf->Ln(1.5);

            $pdf->Cell($l_left,$h,'Nama',$border,0,'L');
            $pdf->Cell($sp_left,$h,':',$border,0,'C');
            $pdf->Cell($fill_left,$h,ucwords(strtolower($Student['Name'])),$border,0,'L');
            $pdf->Cell($l_right,$h,'NIM',$border,0,'L');
            $pdf->Cell($sp_right,$h,':',$border,0,'C');
            $pdf->Cell($fill_right,$h,$Student['NPM'],$border,1,'L');

            $e = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? '%#d' : '%e';
            $pdf->Cell($l_left,$h,'Tempat dan Tanggal Lahir',$border,0,'L');
            $pdf->Cell($sp_left,$h,':',$border,0,'C');
            $pdf->Cell($fill_left,$h,ucwords(strtolower($Student['PlaceOfBirth'])).', '.strftime($e." %B %Y",strtotime($Student['DateOfBirth'])),$border,1,'L');



        }
        else if($lang=='eng'){
            $pdf->Cell(190,$h,'Faculty : '.$Student['FacultyName'],0,1,'C');
            $pdf->Cell(190,$h,'Department : '.ucwords(strtolower($Student['ProdiEng'])),0,1,'C');
            $pdf->Ln(1.5);

            $pdf->Cell($l_left,$h,'Name',$border,0,'L');
            $pdf->Cell($sp_left,$h,':',$border,0,'C');
            $pdf->Cell($fill_left,$h,ucwords(strtolower($Student['Name'])),$border,0,'L');
            $pdf->Cell($l_right,$h,'Student ID',$border,0,'L');
            $pdf->Cell($sp_right,$h,':',$border,0,'C');
            $pdf->Cell($fill_right,$h,$Student['NPM'],$border,1,'L');

            $pdf->Cell($l_left,$h,'Place, Date of Birth',$border,0,'L');
            $pdf->Cell($sp_left,$h,':',$border,0,'C');
            $pdf->Cell($fill_left,$h,ucwords(strtolower($Student['PlaceOfBirth'])).', '.date('F j, Y',strtotime($Student['DateOfBirth'])),$border,1,'L');
        }



        $pdf->Ln(2.5);

        $border = 1;

        $w_no = 8;
        $w_smt = 8;
        $w_kode = 20;
        $w_mk = 103;
        $w_f = 11;
        $w_fv = 18;
        $h=4.3;
        $pdf->SetFillColor(226, 226, 226);
        $pdf->Cell($w_no,$h,'No.',$border,0,'C',true);
//        $pdf->Cell($w_smt,$h,'Smt',$border,0,'C',true);
        $pdf->Cell($w_kode,$h,'Kode',$border,0,'C',true);
        $pdf->Cell($w_mk+$w_smt,$h,'Mata Kuliah',$border,0,'C',true);
        $pdf->Cell($w_f,$h,'SKS',$border,0,'C',true);
        $pdf->Cell($w_f,$h,'Nilai',$border,0,'C',true);
        $pdf->Cell($w_f,$h,'Bobot',$border,0,'C',true);
        $pdf->Cell($w_fv,$h,'SKS X Bobot',$border,1,'C',true);

        $pdf->SetFont('dinprolight','',7);
    }

    private function headerTable($lang,$pdf){
        $border = 1;

        $w_no = 8;
        $w_smt = 8;
        $w_kode = 20;
        $w_mk = 103;
        $w_f = 11;
        $w_fv = 18;
        $h=4.3;
        $pdf->SetFillColor(226, 226, 226);

        if($lang=='ind') {
            $pdf->Cell($w_no,$h,'No.',$border,0,'C',true);
            $pdf->Cell($w_kode,$h,'Kode',$border,0,'C',true);
            $pdf->Cell($w_mk+$w_smt,$h,'Mata Kuliah',$border,0,'C',true);
            $pdf->Cell($w_f,$h,'SKS',$border,0,'C',true);
            $pdf->Cell($w_f,$h,'Nilai',$border,0,'C',true);
            $pdf->Cell($w_f,$h,'Bobot',$border,0,'C',true);
            $pdf->Cell($w_fv,$h,'SKS X Bobot',$border,1,'C',true);
        } else {
            $pdf->Cell($w_no,$h,'No.',$border,0,'C',true);
            $pdf->Cell($w_kode,$h,'Code',$border,0,'C',true);
            $pdf->Cell($w_mk+$w_smt,$h,'Course',$border,0,'C',true);
            $pdf->Cell($w_f,$h,'Credit',$border,0,'C',true);
            $pdf->Cell($w_f,$h,'Grade',$border,0,'C',true);
            $pdf->Cell($w_f,$h,'Score',$border,0,'C',true);
            $pdf->Cell($w_fv,$h,'Point',$border,1,'C',true);
        }



        $pdf->SetFont('dinprolight','',7);
    }

    private function header_temp_transcript($pdf,$dataTempTr){
        $pdf->SetFont('dinpromedium','',6);

        $pdf->Image(base_url('images/logo.png'),10,5,40);

        $pdf->SetXY(100,3);
        $pdf->Cell(100,5,$dataTempTr['NoForm'],0,1,'R');

    }

    private function body_temp_transcript($lang,$pdf,$dataStudent,$dataTempTr){

        $mk = ($lang=='ind')? '' : 'Eng';

        $Student = $dataStudent['Student'][0];
        $Transcript = $dataStudent['Transcript'][0];
        $DetailCourse = $dataStudent['DetailCourse'];
        $Result = $dataStudent['Result'];
        $Warek1 = $dataStudent['WaRek1'][0];

        $border = 1;
        $w_no = 8;
        $w_smt = 8;
        $w_kode = 20;
        $w_mk = 103;
        $w_f = 11;
        $w_fv = 18;
        $h=5;

        $no = 1;
        for($i=0;$i<count($DetailCourse);$i++){
            $d = $DetailCourse[$i];

            $y = $pdf->GetY();
            $pdf->Cell($w_no,$h,($no++),$border,0,'C');
//            $pdf->Cell($w_smt,$h,'1',$border,0,'C');
            $pdf->Cell($w_kode,$h,$d['MKCode'],$border,0,'C');
            $pdf->Cell($w_mk+$w_smt,$h,$d['MKName'.$mk],$border,0,'L');
            $pdf->Cell($w_f,$h,$d['Credit'],$border,0,'C');
            $pdf->Cell($w_f,$h,$d['Grade'],$border,0,'C');
//            $pdf->Cell($w_f,$h, (is_int($d['GradeValue'])) ? $d['GradeValue'] : number_format($d['GradeValue'],2),$border,0,'C');
//            $pdf->Cell($w_fv,$h, (is_int($d['Point'])) ? $d['Point'] : number_format($d['Point'],2),$border,1,'C');
            $pdf->Cell($w_f,$h, number_format($d['GradeValue'],2),$border,0,'C');
            $pdf->Cell($w_fv,$h, number_format($d['Point'],2),$border,1,'C');
//            $y = $pdf->GetY();

//            if($pdf->GetY()>287){
            if($y>231.7){
                // membuat halaman baru
                $pdf->SetMargins(10,5,10);
                $pdf->AddPage();
                $this->header_temp_transcript($pdf,$dataTempTr);
                $pdf->Ln(15);
                $this->headerTable($lang,$pdf);
            }

        }

        $pdf->SetFont('dinpromedium','',8);
        $pdf->Cell($w_smt+$w_no+$w_kode+$w_mk,$h,'Total',$border,0,'R',true);
        $pdf->Cell($w_f,$h,$Result['TotalSKS'],$border,0,'C',true);
        $pdf->Cell($w_f,$h,'-',$border,0,'C',true);
        $pdf->Cell($w_f,$h,'-',$border,0,'C',true);
        $pdf->Cell($w_fv,$h,number_format($Result['TotalGradeValue'],2),$border,1,'C',true);

        $ipkLabel = ($lang=='ind')? 'Indeks Prestasi Kumulatif' : 'Cummulative Grade Point Average';
        $h = 6;

        $pdf->Cell($w_smt+$w_no+$w_kode+$w_mk+(3*$w_f)+$w_fv,$h,$ipkLabel.' : '.number_format($Result['IPK'],2),$border,1,'C',true);


        $h = 3.5;
        $pdf->Ln(3.5);
        $border = 0;
        $pdf->SetFont('dinprolight','',8);
        $pdf->Cell($w_smt+$w_no+$w_kode+$w_mk,$h,'',$border,0,'R');
        $e = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? '%#d' : '%e';
        $dateT = ($lang=='ind') ? strftime($e." %B %Y",strtotime($dataTempTr['Date'])) : date('F j, Y',strtotime($dataTempTr['Date']));
        $pdf->Cell((3*$w_f)+$w_fv,$h,ucwords(strtolower($dataTempTr['Place'])).', '.$dateT,$border,1,'L');


        $ttdb = ($lang=='ind')? 'Wakil Rektor Bidang Akademik' : 'Vice Rector of Academic Affairs';
        $pdf->Cell($w_smt+$w_no+$w_kode+$w_mk,$h,'',$border,0,'R');
        $pdf->Cell((3*$w_f)+$w_fv,$h,$ttdb,$border,1,'L');

        $pdf->Ln(15);

        $nm = $Warek1['TitleAhead'].' '.ucwords(strtolower($Warek1['Name'])).', '.$Warek1['TitleBehind'];
        $pdf->Cell($w_smt+$w_no+$w_kode+$w_mk,$h,'',$border,0,'R');
        $pdf->Cell((3*$w_f)+$w_fv,$h,trim($nm),$border,1,'L');
    }

    // ====== Transcript =======

    public function transcript(){

        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        $dataStudent = $this->m_save_to_pdf->getTranscript($data_arr['DBStudent'],$data_arr['NPM']);

//        print_r($dataStudent);
//        exit;

        $Student = $dataStudent['Student'][0];
        $Transcript = $dataStudent['Transcript'][0];

        $pdf = new FPDF('P','mm','legal');

        $pdf->AddFont('dinproExpBold','','dinproExpBold.php');

        // membuat halaman baru
        $pdf->SetMargins(10,42.5,10);
        $pdf->AddPage();

        $pdf->SetFont('dinpromedium','',7);
        $pdf->SetXY(10,10);
        $pdf->Cell(123,7,'',0,0,'L');
        $pdf->Cell(21,7,'Nomor Transkrip / ',0,0,'L');
        $pdf->SetFont('dinlightitalic','',7);
        $pdf->Cell(21,7,'Transcript Number',0,0,'L');
        $pdf->SetFont('dinpromedium','',7);
        $pdf->Cell(2,7,' : ',0,0,'C');
        $pdf->Cell(25,7,$Student['CSN'],0,1,'R');

        $pdf->SetXY(10,43.5);

        $label_l = 35;
        $sparator_l = 1;
        $fill_l = 61;

        $label_r = 38;
        $sparator_r = 1;
        $fill_r = 55;
        $h=3.3;
        $ln = 1;
        $border = 0;

        $e = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? '%#d' : '%e';


        $f_title = 8;
        $f_title_i = 7;
        $pdf->SetFont('dinpromedium','',$f_title);
        $pdf->Cell($label_l,$h,'Nama',$border,0,'L');
        $pdf->Cell($sparator_l,$h,':',$border,0,'C');
        $pdf->Cell($fill_l,$h,ucwords(strtolower($Student['Name'])),$border,0,'L');
        $pdf->Cell($label_r,$h,'Program Pendidikan',$border,0,'L');
        $pdf->Cell($sparator_r,$h,':',$border,0,'C');
        $pdf->Cell($fill_r,$h,$Student['GradeDesc'],$border,1,'L');

        $pdf->SetFont('dinlightitalic','',$f_title_i);
        $pdf->Cell($label_l,$h,'Name',$border,0,'L');
        $pdf->Cell($sparator_l,$h,'',$border,0,'C');
        $pdf->Cell($fill_l,$h,'',$border,0,'L');
        $pdf->Cell($label_r,$h,'Educational Program',$border,0,'L');
        $pdf->Cell($sparator_r,$h,':',$border,0,'C');
        $pdf->Cell($fill_r,$h,$Student['GradeDescEng'],$border,1,'L');

        $pdf->Ln($ln);

        $pdf->SetFont('dinpromedium','',$f_title);
        $pdf->Cell($label_l,$h,'Tempat dan Tanggal Lahir',$border,0,'L');
        $pdf->Cell($sparator_l,$h,':',$border,0,'C');
        $pdf->Cell($fill_l,$h,ucwords(strtolower($Student['PlaceOfBirth'])).', '.strftime($e." %B %Y",strtotime($Student['DateOfBirth'])),$border,0,'L');
        $pdf->Cell($label_r,$h,'Program Studi',$border,0,'L');
        $pdf->Cell($sparator_r,$h,':',$border,0,'C');
        $pdf->Cell($fill_r,$h,$Student['Prodi'],$border,1,'L');

        $pdf->SetFont('dinlightitalic','',$f_title_i);
        $pdf->Cell($label_l,$h,'Place and Date of Birth',$border,0,'L');
        $pdf->Cell($sparator_l,$h,':',$border,0,'C');
        $pdf->Cell($fill_l,$h,ucwords(strtolower($Student['PlaceOfBirth'])).', '.date('F j, Y',strtotime($Student['DateOfBirth'])),$border,0,'L');
        $pdf->Cell($label_r,$h,'Study Program',$border,0,'L');
        $pdf->Cell($sparator_r,$h,':',$border,0,'C');
        $pdf->Cell($fill_r,$h,$Student['ProdiEng'],$border,1,'L');

        $pdf->Ln($ln);

        $pdf->SetFont('dinpromedium','',$f_title);
        $pdf->Cell($label_l,$h,'Nomor Induk Mahasiswa',$border,0,'L');
        $pdf->Cell($sparator_l,$h,':',$border,0,'C');
        $pdf->Cell($fill_l,$h,$Student['NPM'],$border,0,'L');
        $pdf->Cell($label_r,$h,'Nomor Keputusan Pendirian',$border,0,'L');
        $pdf->Cell($sparator_r,$h,':',$border,0,'C');
        $pdf->Cell($fill_r,$h,$Transcript['NumberUniv'],$border,1,'L');

        $pdf->SetFont('dinlightitalic','',$f_title_i);
        $pdf->Cell($label_l,$h,'Student Identification Number',$border,0,'L');
        $pdf->Cell($sparator_l,$h,'',$border,0,'C');
        $pdf->Cell($fill_l,$h,'',$border,0,'L');
        $pdf->SetFont('dinpromedium','',$f_title);
        $pdf->Cell($label_r,$h,'Perguruan Tinggi',$border,0,'L');
        $pdf->Cell($sparator_r,$h,'',$border,0,'C');
        $pdf->Cell($fill_r,$h,'',$border,1,'L');


        $pdf->Cell($label_l+$sparator_l+$fill_l,$h,'',0,0,'L');
        $pdf->SetFont('dinlightitalic','',$f_title_i);
        $pdf->Cell($label_r,$h,'University Establishment Permit Number',$border,0,'L');
        $pdf->Cell($sparator_r,$h,'',$border,0,'C');
        $pdf->Cell($fill_r,$h,'',$border,1,'L');


        // Table
        $pdf->Ln(3);

        $w_no = 13;
        $w_course = 110;
        $w_credit = 15;
        $w_grade = 15;
        $w_score = 15;
        $w_point = 23;

        $font_medium = 8;
        $font_medium_i = 7;


        $border_fill = 'LR';

        $this->header_transcript_table($pdf);

        $DetailStudent = $dataStudent['DetailCourse'];
        $no=1;
        for($i=0;$i<count($DetailStudent);$i++){

            $ds = $DetailStudent[$i];

            $this->spasi_transcript_table($pdf,'T');

            $h = 3;
            $pdf->SetFont('dinproExpBold','',$font_medium);
            $ytext = $pdf->GetY()+3.5;
            $x_ = ($i<9) ? 16 : 15;
            $pdf->Text($x_,$ytext,($no++));
            $pdf->Cell($w_no,$h,'',$border_fill,0,'C');
            $pdf->Cell($w_course,$h,$ds['MKName'],$border_fill,0,'L');

            $ytext = $pdf->GetY()+3.5;
            $xtext = $pdf->GetX()+7;
            $pdf->Text($xtext,$ytext,$ds['Credit']);
            $pdf->Cell($w_credit,$h,'',$border_fill,0,'C');

            $ytext = $pdf->GetY()+3.5;
            $xtext = $pdf->GetX()+6.5;
            $pdf->Text($xtext,$ytext,$ds['Grade']);
            $pdf->Cell($w_grade,$h,'',$border_fill,0,'C');

            $ytext = $pdf->GetY()+3.5;
            $xtext = $pdf->GetX()+6.3;
            $pdf->Text($xtext,$ytext,$ds['GradeValue']);
            $pdf->Cell($w_score,$h,'',$border_fill,0,'C');

            $ytext = $pdf->GetY()+3.5;
            $xtext = $pdf->GetX()+9.5;
            $pdf->Text($xtext,$ytext,$ds['Point']);
            $pdf->Cell($w_point,$h,'',$border_fill,1,'C');

            $pdf->SetFont('dinlightitalic','',$font_medium_i);
            $pdf->Cell($w_no,$h,'',$border_fill,0,'C');
            $pdf->Cell($w_course,$h,$ds['MKNameEng'],$border_fill,0,'L');
            $pdf->Cell($w_credit,$h,'',$border_fill,0,'C');
            $pdf->Cell($w_grade,$h,'',$border_fill,0,'C');
            $pdf->Cell($w_score,$h,'',$border_fill,0,'C');
            $pdf->Cell($w_point,$h,'',$border_fill,1,'C');

            $this->spasi_transcript_table($pdf,'B');

            if($pdf->GetY()>=324){
                $pdf->SetMargins(10,20,10);
                $pdf->AddPage();
//                $pdf->SetXY(10,43.5);
                $this->header_transcript_table($pdf);
            }
        }

        $Result = $dataStudent['Result'];

        $this->spasi_transcript_table($pdf,'TR');
        $pdf->SetFont('dinproExpBold','',$font_medium);
        $pdf->Cell($w_course+$w_no,$h,'Jumlah',$border_fill,0,'R');
        $ytext = $pdf->GetY()+3.5;
        $xtext = $pdf->GetX()+5.5;
        $pdf->Text($xtext,$ytext,$Result['TotalSKS']);
        $pdf->Cell($w_credit,$h,'',$border_fill,0,'C');

        $ytext = $pdf->GetY()+3.5;
        $xtext = $pdf->GetX()+7;
        $pdf->Text($xtext,$ytext,'-');
        $pdf->Cell($w_grade,$h,'',$border_fill,0,'C');
        $ytext = $pdf->GetY()+3.5;
        $xtext = $pdf->GetX()+7;
        $pdf->Text($xtext,$ytext,'-');
        $pdf->Cell($w_score,$h,'',$border_fill,0,'C');

        $ytext = $pdf->GetY()+3.5;
        $xtext = $pdf->GetX()+7.5;
        $pdf->Text($xtext,$ytext,$Result['TotalGradeValue']);
        $pdf->Cell($w_point,$h,'',$border_fill,1,'C');

        $pdf->SetFont('dinlightitalic','',$font_medium_i);
        $pdf->Cell($w_course+$w_no,$h,'Total',$border_fill,0,'R');
        $pdf->Cell($w_credit,$h,'',$border_fill,0,'C');
        $pdf->Cell($w_grade,$h,'',$border_fill,0,'C');
        $pdf->Cell($w_score,$h,'',$border_fill,0,'C');
        $pdf->Cell($w_point,$h,'',$border_fill,1,'C');
        $this->spasi_transcript_table($pdf,'BR');

        $pdf->Ln(3);
        $totalW = $w_course+$w_no+$w_credit+$w_grade+$w_score+$w_point;
        $w_Div = $totalW/2;
        $w_R_label = 40;
        $w_R_sparator = 3;
        $w_R_fill = 52.5;

        $h = 1.5;
        $pdf->Cell($w_Div,$h,'','LRT',0,'L');
        $pdf->Cell($w_Div,$h,'','LRT',1,'L');

        $h=3;
        $pdf->SetFont('dinpromedium','',$font_medium);
        $pdf->Cell($w_R_label,$h,' Indeks Prestasi Kumulatif','L',0,'L');
        $pdf->Cell($w_R_sparator,$h,':',0,0,'L');
        $pdf->Cell($w_R_fill,$h,$Result['IPK'],'R',0,'L');
        $pdf->Cell($w_R_label,$h,' Predikat Kelulusan','L',0,'L');
        $pdf->Cell($w_R_sparator,$h,':',0,0,'C');
        $pdf->Cell($w_R_fill,$h,$Result['Grading'][0]['Description'],'R',1,'L');

        $pdf->SetFont('dinlightitalic','',$font_medium_i);
        $pdf->Cell($w_Div,$h,' Grade Point Average','L',0,'L');
        $pdf->Cell($w_R_label,$h,' Graduation Honor','L',0,'L');
        $pdf->Cell($w_R_sparator,$h,':',0,0,'C');
        $pdf->Cell($w_R_fill,$h,$Result['Grading'][0]['DescriptionEng'],'R',1,'L');

        $h = 1.5;
        $pdf->Cell($w_Div,$h,'','LRB',0,'L');
        $pdf->Cell($w_Div,$h,'','LRB',1,'L');
        $h=3;

        $pdf->SetFont('dinpromedium','',$font_medium);
        $h = 1.5;
        $pdf->Cell($totalW,$h,'','LRT',1,'L');
        $h=3;
        $SkripsiInd = ($Student['TitleInd']!='' && $Student['TitleInd']!=null) ? $Student['TitleInd'] : '-';
        $SkripsiEng = ($Student['TitleEng']!='' && $Student['TitleEng']!=null) ? $Student['TitleEng'] : '-';

        $yA = $pdf->GetY();



        $pdf->Cell($w_R_label,$h,'Judul Skripsi',0,0,'L');
        $pdf->Cell($w_R_sparator,$h,':',0,0,'C');
        $pdf->MultiCell($w_R_fill+$w_Div-2,$h,$SkripsiInd,0);

        $pdf->SetFont('dinlightitalic','',$font_medium_i);
//        $pdf->SetFont('dinprolight','',$font_medium_i);
        $pdf->Cell($w_R_label,$h,'Thesis Title',0,0,'L');
        $pdf->Cell($w_R_sparator,$h,':',0,0,'C');
        $pdf->MultiCell($w_R_fill+$w_Div-2,$h,$SkripsiEng,0);

        $yA2 = $pdf->GetY();
//        $pdf->SetLineWidth(0.2);
        $pdf->Line(10, $yA, 10, $yA2);
        $pdf->Line(10+$w_R_label+$w_R_sparator+$w_R_fill+$w_Div, $yA, 10+$w_R_label+$w_R_sparator+$w_R_fill+$w_Div, $yA2);
        $h = 1.5;
        $pdf->Cell($totalW,$h,'','LRB',1,'L');
        $h=3;

        $pdf->Ln(7);

        +$min = 25;
        $borderttd = 0;
        $y = $pdf->GetY();
        $pdf->SetFont('dinpromedium','',$font_medium);
        $pdf->Cell($w_Div+$min,$h,'',$borderttd,0,'L');
        $pdf->Cell($w_Div-$min,$h,'Tempat dan Tanggal Diterbitkan',$borderttd,1,'L');


        $pdf->SetFont('dinlightitalic','',$font_medium_i);
        $pdf->Cell($w_Div+$min,$h,'',$borderttd,0,'L');
        $pdf->Cell($w_Div-$min,$h,'Place Date Issued',$borderttd,1,'L');

        $pdf->SetFont('dinpromedium','',$font_medium);
        $pdf->Cell($w_Div+$min,$h,'',$borderttd,0,'L');
        $pdf->Cell($w_Div-$min,$h,ucwords(strtolower($Transcript['PlaceIssued'])).', '.strftime($e." %B %Y",strtotime($Transcript['DateIssued'])),$borderttd,1,'L');

        $pdf->SetFont('dinlightitalic','',$font_medium_i);
        $pdf->Cell($w_Div+$min,$h,'',$borderttd,0,'L');
        $pdf->Cell($w_Div-$min,$h,ucwords(strtolower($Transcript['PlaceIssued'])).',  '.date('F j, Y',strtotime($Transcript['DateIssued'])),$borderttd,1,'L');

        $pdf->Ln(5);


        $pdf->SetFont('dinpromedium','',$font_medium);
        $pdf->Cell($w_Div+$min,$h,'Rektor',$borderttd,0,'L');
        $pdf->Cell($w_Div-$min,$h,'Dekan',$borderttd,1,'L');

        $pdf->SetFont('dinlightitalic','',$font_medium_i);
        $pdf->Cell($w_Div+$min,$h,'Rector',$borderttd,0,'L');
        $pdf->Cell($w_Div-$min,$h,'Dean',$borderttd,1,'L');

        $pdf->Ln(14);

        $titleA = ($Student['TitleAhead']!='') ? $Student['TitleAhead'].' ' : '';
        $titleB = ($Student['TitleBehind']!='') ? ' '.$Student['TitleBehind'] : '' ;

        $Dekan = $titleA.''.$Student['Dekan'].' '.$titleB;

        $Rektorat = $dataStudent['Rektorat'][0];
        $titleARektor = ($Rektorat['TitleAhead']!='')? $Rektorat['TitleAhead'].' ' : '';
        $titleBRektor = ($Rektorat['TitleBehind']!='')? ' '.$Rektorat['TitleBehind'] : '';
        $Rektor = $titleARektor.''.$Rektorat['Name'].''.$titleBRektor;

        // Foto


        $pdf->SetFont('dinpromedium','',$font_medium);
        $pdf->Cell($w_Div+$min,$h,$Rektor,$borderttd,0,'L');
        $pdf->Cell($w_Div-$min,$h,$Dekan,$borderttd,1,'L');

        $pdf->SetFont('dinpromedium','',$font_medium_i);
        $pdf->Cell($w_Div+$min,$h,'NIK : '.$Rektorat['NIP'],$borderttd,0,'L');
        $pdf->Cell($w_Div-$min,$h,'NIK : '.$Student['NIP'],$borderttd,1,'L');

        $pdf->Rect(77, $y, 33, 45);


        $nameF = str_replace(' ','_',strtoupper($Student['Name']));
        $pdf->Output('TRNSCPT_'.$Student['NPM'].'_'.$nameF.'.pdf','I');
    }

    private function header_transcript_table($pdf){

        $w_no = 13;
        $w_course = 110;
        $w_credit = 15;
        $w_grade = 15;
        $w_score = 15;
        $w_point = 23;


        $border_fill = 'LR';

        $this->spasi_transcript_table($pdf,'T');


        $h = 3;
        $pdf->SetFont('dinpromedium','',9);


        $pdf->Cell($w_no,$h,'',$border_fill,0,'C');
        $pdf->Cell($w_course,$h,'Mata Kuliah',$border_fill,0,'C');
        $pdf->Cell($w_credit,$h,'SKS',$border_fill,0,'C');
        $pdf->Cell($w_grade,$h,'Nilai',$border_fill,0,'C');
        $pdf->Cell($w_score,$h,'Angka',$border_fill,0,'C');
        $pdf->Cell($w_point,$h,'SKS x Angka',$border_fill,1,'C');
        $ytext = $pdf->GetY()+0.5;
        $pdf->Text(14,$ytext,'No.');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell($w_no,$h,'',$border_fill,0,'C');
        $pdf->Cell($w_course,$h,'Course',$border_fill,0,'C');
        $pdf->Cell($w_credit,$h,'Credit',$border_fill,0,'C');
        $pdf->Cell($w_grade,$h,'Grade',$border_fill,0,'C');
        $pdf->Cell($w_score,$h,'Score',$border_fill,0,'C');
        $pdf->Cell($w_point,$h,'Point',$border_fill,1,'C');

        $this->spasi_transcript_table($pdf,'B');
    }

    private function spasi_transcript_table($pdf,$desc){

        $w_no = 13;
        $w_course = 110;
        $w_credit = 15;
        $w_grade = 15;
        $w_score = 15;
        $w_point = 23;

        $border_fill_t = 'LRT';
        $border_fill_b = 'LRB';

        if(strtolower($desc)=='t'){
            $h = 0.6;

            $pdf->Cell($w_no,$h,'',$border_fill_t,0,'C');
            $pdf->Cell($w_course,$h,'',$border_fill_t,0,'C');
            $pdf->Cell($w_credit,$h,'',$border_fill_t,0,'C');
            $pdf->Cell($w_grade,$h,'',$border_fill_t,0,'C');
            $pdf->Cell($w_score,$h,'',$border_fill_t,0,'C');
            $pdf->Cell($w_point,$h,'',$border_fill_t,1,'C');
        } else if(strtolower($desc)=='b') {
            $h = 0.6;

            $pdf->Cell($w_no,$h,'',$border_fill_b,0,'C');
            $pdf->Cell($w_course,$h,'',$border_fill_b,0,'C');
            $pdf->Cell($w_credit,$h,'',$border_fill_b,0,'C');
            $pdf->Cell($w_grade,$h,'',$border_fill_b,0,'C');
            $pdf->Cell($w_score,$h,'',$border_fill_b,0,'C');
            $pdf->Cell($w_point,$h,'',$border_fill_b,1,'C');
        } else if(strtolower($desc)=='tr'){
            $h = 1.3;

            $pdf->Cell($w_course+$w_no,$h,'',$border_fill_t,0,'C');
            $pdf->Cell($w_credit,$h,'',$border_fill_t,0,'C');
            $pdf->Cell($w_grade,$h,'',$border_fill_t,0,'C');
            $pdf->Cell($w_score,$h,'',$border_fill_t,0,'C');
            $pdf->Cell($w_point,$h,'',$border_fill_t,1,'C');
        } else if(strtolower($desc)=='br') {
            $h = 1.3;

            $pdf->Cell($w_course+$w_no,$h,'',$border_fill_b,0,'C');
            $pdf->Cell($w_credit,$h,'',$border_fill_b,0,'C');
            $pdf->Cell($w_grade,$h,'',$border_fill_b,0,'C');
            $pdf->Cell($w_score,$h,'',$border_fill_b,0,'C');
            $pdf->Cell($w_point,$h,'',$border_fill_b,1,'C');
        }
    }

    // ====== Penutup Transcript =======


    // ====== Ijazah =======
    public function ijazah(){


        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        $dataIjazah = $this->m_save_to_pdf->getIjazah($data_arr['DBStudent'],$data_arr['NPM']);

        $pdf = new FPDF('L','mm','A4');

        $pdf->AddFont('dinproExpBold','','dinproExpBold.php');

        // membuat halaman baru
        $pdf->SetMargins(20.5,10.5,10);
        $pdf->AddPage();

        $h = 3;

        $Ijazah = $dataIjazah['Ijazah'][0];
        $Student = $dataIjazah['Student'][0];

        $border = 0;

        $full_width = 266.5;
        $w_left = 205;
        $w_right = $full_width - $w_left;

        $pdf->SetY(7);
        $pdf->SetFont('dinpromedium','',8);
        $pdf->Cell($w_left,$h,'Nomor Keputusan Pendirian Perguruan Tinggi : '.$Ijazah['NumberUniv'],$border,0,'L');
        $pdf->Cell($w_right,$h,'Nomor Seri Ijazah : '.$Student['CSN'],$border,1,'L');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell($w_left,$h,'University Estabilshment Permit Number',$border,0,'L');
        $pdf->Cell($w_right,$h,'Cerficate Serial Number',$border,1,'L');

        $pdf->Ln(29);

        $fn_b = 11.5;
        $fn_i = 10;

        $x = 60;
        $h = 4;
        $full_width = 212;
        $pdf->SetXY($x,45.5);
        $pdf->SetFont('dinpromedium','',$fn_b);

        $pdf->Cell($full_width,$h,'Memberikan Ijazah Kepada',$border,1,'C');

        $pdf->SetX($x);
        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->Cell($full_width,$h,'This certificate is awarded to',$border,1,'C');

        $pdf->SetX($x);
//        $pdf->SetFont('dinpromedium','',20);
        $pdf->SetFont('dinproExpBold','',20);
        $pdf->Cell($full_width,13,ucwords(strtolower($Student['Name'])),$border,1,'C');

        $pdf->Ln(2);

        $x = 67;
        $ln = 3;
        $label = 70;
        $sp = 1.5;
        $fill = 100;

        $fillFull = $label + $sp + $fill;



        $border = 0;

        $e = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? '%#d' : '%e';

        // ===== TTL =====
        $pdf->SetX($x);
        $pdf->SetFont('dinpromedium','',$fn_b);
        $pdf->Cell($label,$h,'Tempat dan Tanggal Lahir',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
        $pdf->Cell($fill,$h,$Student['PlaceOfBirth'].', '.strftime($e." %B %Y",strtotime($Student['DateOfBirth'])),$border,1,'L');

        $pdf->SetX($x);
        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->Cell($label,$h,'Place and Date of Birth',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
        $pdf->Cell($fill,$h,$Student['PlaceOfBirth'].', '.date('F j, Y',strtotime($Student['DateOfBirth'])),$border,1,'L');
        $pdf->Ln($ln);

        // ===== NIM =====
        $pdf->SetX($x);
        $pdf->SetFont('dinpromedium','',$fn_b);
        $pdf->Cell($label,$h,'Nomor Induk Mahasiswa',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
        $pdf->Cell($fill,$h,$Student['NPM'],$border,1,'L');

        $pdf->SetX($x);
        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->Cell($label,$h,'Student Identification Number',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
        $pdf->Cell($fill,$h,$Student['NPM'],$border,1,'L');
        $pdf->Ln($ln);

        // ===== Prodi =====
        $pdf->SetX($x);
        $pdf->SetFont('dinpromedium','',$fn_b);
        $pdf->Cell($label,$h,'Program Studi',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
        $pdf->Cell($fill,$h,$Student['Prodi'],$border,1,'L');

        $pdf->SetX($x);
        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->Cell($label,$h,'Study Program',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
        $pdf->Cell($fill,$h,$Student['ProdiEng'],$border,1,'L');
        $pdf->Ln($ln);

        // ===== Program Pendidikan =====
        $pdf->SetX($x);
        $pdf->SetFont('dinpromedium','',$fn_b);
        $pdf->Cell($label,$h,'Program Pendidikan',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
        $pdf->Cell($fill,$h,$Student['GradeDesc'],$border,1,'L');

        $pdf->SetX($x);
        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->Cell($label,$h,'Education Program',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
        $pdf->Cell($fill,$h,$Student['GradeDescEng'],$border,1,'L');
        $pdf->Ln($ln);

        // ===== Tanggal Yudisium =====
        $pdf->SetX($x);
        $pdf->SetFont('dinpromedium','',$fn_b);
        $pdf->Cell($label,$h,'Tanggal Yudisium',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
//        $pdf->Cell($fill,$h,date('d/m/Y',strtotime($Ijazah['DateOfYudisium'])),$border,1,'L');
        $pdf->Cell($fill,$h,strftime("%d %B %Y",strtotime($Ijazah['DateOfYudisium'])),$border,1,'L');

        $pdf->SetX($x);
        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->Cell($label,$h,'Date of Yudisiom',$border,0,'L');
        $pdf->Cell($sp,$h,':',$border,0,'C');
        $pdf->Cell($fill,$h,date('F j, Y',strtotime($Ijazah['DateOfYudisium'])),$border,1,'L');
//        $pdf->Cell($fill,$h,date('d/m/Y',strtotime($Ijazah['DateOfYudisium'])),$border,1,'L');
        $pdf->Ln($ln);

        // ===== Ket 1 =====
        // 171.5
        $pdf->SetX($x);
        $pdf->SetFont('dinpromedium','',$fn_b);
        $pdf->Cell(135,$h,'Dengan demikian yang bersangkutan berhak memakai gelar Akademik : ',$border,0,'L');
        $pdf->Cell(15,$h,'',$border,0,'L');
        $pdf->Cell(50,$h,$Student['Degree'].' ('.$Student['TitleDegree'].')',$border,1,'L');

        $pdf->SetX($x);
        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->Cell(135,$h,'Henceforth the conferee is entitled to use the academic degree of',$border,0,'L');
        $pdf->Cell(15,$h,'',$border,0,'L');
        $pdf->Cell(50,$h,$Student['DegreeEng'].' ('.$Student['TitleDegreeEng'].')',$border,1,'L');
        $pdf->Ln($ln);

        // ===== Ket 2 =====
        $pdf->SetX($x);
        $pdf->SetFont('dinpromedium','',$fn_b);
        $pdf->Cell($fillFull,$h,'dan diberikan hak serta wewenang yang berhubungan dengan gelar yang dimilikinya.',$border,1,'L');

        $pdf->SetX($x);
        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->Cell($fillFull,$h,'and conferred the right and privileges pertaining to this degree.',$border,1,'L');

        $y = $pdf->GetY()+7;
        $pdf->Ln(7);

        // 171.5
        // Tanda tangan

        $pdf->SetX($x+10);
        $pdf->SetFont('dinpromedium','',$fn_b);
        $pdf->Cell(171.5,$h,$Ijazah['PlaceIssued'].', '.strftime($e." %B %Y",strtotime($Ijazah['DateIssued'])),$border,1,'L');
        $pdf->SetX($x+10);
        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->Cell(171.5,$h,$Ijazah['PlaceIssued'].', '.date('F j, Y',strtotime($Ijazah['DateIssued'])),$border,1,'L');

        $pdf->Ln(2);
        $pdf->SetX($x+10);
        $pdf->SetFont('dinpromedium','',$fn_b);
        $pdf->Cell(138.5,$h,'Rektor',$border,0,'L');
        $pdf->Cell(41,$h,'Dekan',$border,1,'L');

        $pdf->SetFont('dinlightitalic','',$fn_i);
        $pdf->SetX($x+10);
        $pdf->Cell(138.5,$h,'Rector',$border,0,'L');
        $pdf->Cell(41,$h,'Dean',$border,1,'L');

        $pdf->Ln(13);

        // Dekan --
        $titleA = ($Student['TitleAhead']!='') ? $Student['TitleAhead'].' ' : '';
        $titleB = ($Student['TitleBehind']!='') ? $Student['TitleBehind'] : '' ;

        $Dekan = $titleA.''.$Student['Dekan'].''.$titleB;

        // Rektor
        $Rektorat = $dataIjazah['Rektorat'][0];
        $titleARektor = ($Rektorat['TitleAhead']!='')? $Rektorat['TitleAhead'].' ' : '';
        $titleBRektor = ($Rektorat['TitleBehind']!='')? $Rektorat['TitleBehind'] : '';
        $komaRektor = ($titleBRektor!='') ? ',' : '';
        $Rektor = $titleARektor.''.$Rektorat['Name'].''.$titleBRektor;
        // ----

        $pdf->SetFont('dinpromedium','',$fn_b);

        $yy = 13;
        $xx = 57;

        $ytext = $pdf->GetY()+$yy;
        $xtext = $pdf->GetX()+$xx;
        $pdf->Text($xtext,$ytext,$Rektor);

        $ytext = $pdf->GetY()+$yy+4;
        $xtext = $pdf->GetX()+$xx;
        $pdf->SetFont('dinpromedium','',$fn_b-2);
        $pdf->Text($xtext,$ytext,'NIK : '.$Rektorat['NIP']);


        $pdf->SetFont('dinpromedium','',$fn_b);
        $ytext = $pdf->GetY()+$yy;
        $xtext = $pdf->GetX()+$xx+138;
        $pdf->Text($xtext,$ytext,$Dekan);

        $ytext = $pdf->GetY()+$yy+4;
        $xtext = $pdf->GetX()+$xx+138;
        $pdf->SetFont('dinpromedium','',$fn_b-2);
        $pdf->Text($xtext,$ytext,'NIK : '.$Student['NIP']);




//        $pdf->SetX($x+10);
//        $pdf->Cell(138.5,$h,$Rektor,$border,0,'L');
//        $pdf->Cell(41,$h,$Dekan,$border,1,'L');

//        $pdf->SetFont('dinpromedium','',$fn_b-2);
//        $pdf->SetX($x+10);
//        $pdf->Cell(138.5,$h,'NIK : '.$Rektorat['NIP'],$border,0,'L');
//        $pdf->Cell(41,$h,'NIK : '.$Student['NIP'],$border,1,'L');

        $pdf->Rect($x+95, $y, 33, 45);


        $nameF = str_replace(' ','_',strtoupper($Student['Name']));
        $pdf->Output('IJAZAH_'.$Student['NPM'].'_'.$nameF.'.pdf','I');
    }


    function GenerateWord()
    {
        //Get a random word
        $nb=rand(3,10);
        $w='';
        for($i=1;$i<=$nb;$i++)
            $w.=chr(rand(ord('a'),ord('z')));
        return $w;
    }

    function GenerateSentence()
    {
        //Get a random sentence
        $nb=rand(1,10);
        $s='';
        for($i=1;$i<=$nb;$i++)
            $s.=$this->GenerateWord().' ';
        return substr($s,0,-1);
    }

    // ======= SKPI =======
    public function diploma_supplement(){

        $pdf = new Pdf_mc_table('P','mm','A4');

        $this->SKPI_page1($pdf);
        $this->SKPI_page2($pdf);
        $this->SKPI_page3($pdf);
        $this->SKPI_page4($pdf);
        $this->SKPI_page5($pdf);
        $this->SKPI_page6($pdf);


        $pdf->Output('skpi.pdf','I');
    }


    private function SKPI_page1($pdf){
        // membuat halaman baru
        $pdf->SetMargins(10,3,10);

        $pdf->AddPage();
        $pdf->Ln(30);

        $space_bt = 0.7;
        $R = 226;
        $G = 226;
        $B = 226;

        $pdf->SetFillColor($R, $G, $B);
        $pdf->SetFont('dinpromedium','',17);

        $pdf->Cell(190,3.5,'','T',1,'L');

        $h = 5.5;

        $pdf->Cell(120,$h,'SURAT KETERANGAN',0,0,'L');
        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(70,$h,'NOMOR : SKPI/MNS1/2013/BNN00324',0,1,'C',TRUE);

        $pdf->SetFont('dinpromedium','',17);
        $pdf->Cell(0,$h,'PENDAMPING IJAZAH',0,1,'L');

        $pdf->SetFont('dinlightitalic','',12);
        $pdf->Cell(190,$h,'Diploma Supplement',0,1,'L');

        $pdf->Cell(190,3,'','B',1,'L');

        $tx = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s
                standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a
                type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining
                essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum';
        $pdf->Ln(1.5);
        $pdf->SetFont('dinprolight','',9);
        $pdf->SetWidths(array(190));
        $pdf->Row(array($tx),0,4);

        $pdf->Ln(2);
        $pdf->Row(array(strlen($tx).' - '.$tx),0,4);

        $pdf->Cell(190,2,'','B',1,'L');
        $pdf->Ln(1.5);
        $h = 4.5;
        $pdf->SetFont('dinpromedium','',11);
        $pdf->Cell(190,$h,'01. INFORMASI TENTANG IDENTITAS DIRI PEMEGANG SKPI',0,1,'L');
        $pdf->SetFont('dinlightitalic','',10);
        $pdf->Cell(190,$h,'01. Information Identifying the Holder of Diploma Suppliyer',0,1,'L');


        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'NAMA LENGKAP',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'TAHUN LULUS',0,1,'L');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,'Full Name',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'Year of Completion',0,1,'L');

        $h = 6;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' Nandang Mulyadi',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' 2016',0,1,'L',true);


        $pdf->Ln(3);

        $h = 3.5;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'TEMPAT DAN TANGGAL LAHIR',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'GELAR',0,1,'L');
        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,'Date and Place of Birth',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'Name of Qualification',0,1,'L');

        $h = 3.5;
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' Jakarta, 29 Januari 1994',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Sarjana Ekonomi (SE)',0,1,'L',true);

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,' Jakarta, January 29, 1994',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Bachelor In Management',0,1,'L',true);
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);



        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'NOMOR INDUK MAHASISWA',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'NOMOR IJAZAH',0,1,'L');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,'Student Identification Number',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'Diploma Number',0,1,'L');

        $h = 6;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' 31140005',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' MNS/2013/31140005',0,1,'L',true);


        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        $pdf->Cell(190,2,'','B',1,'L');
        $pdf->Ln(2);
        $h = 4.5;
        $pdf->SetFont('dinpromedium','',11);
        $pdf->Cell(190,$h,'02. INFORMASI TENTANG IDENTITAS PENYELENGGARA PROGRAM',0,1,'L');
        $pdf->SetFont('dinlightitalic','',10);
        $pdf->Cell(190,$h,'02. Information Identifying the Awarding Institution',0,1,'L');


        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'SK PENDIRIAN PERGURUAN TINGGI',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'PERSYARATAN PENERIMAAN',0,1,'L');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,'Awarding Institution\'s License',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'Entry Requirements',0,1,'L');

        $h = 3.5;
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' No : 55/D/O/1996, Tanggal 8 Agustus 1996',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Lulusan pendidikan menengah atas / sederajat',0,1,'L',true);

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,' No : 55/D/O/1996, Date August 8, 1996',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Graduate from heigh school or similar level of education',0,1,'L',true);
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);


        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'NAMA PERGURUAN TINGGI',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'BAHASA PENGANTAR KULIAH',0,1,'L');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,'Awarding Institution',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'Language of Intrusion',0,1,'L');

        $h = 3.5;
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' Universitas Agung Podomoro',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Indonesia',0,1,'L',true);

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,' Agung Podomoro University',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Indonesian',0,1,'L',true);
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);


        // ++++++++++++++++++++++
        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'PROGRAM STUDI',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'SISTEM PENILAIAN',0,1,'L');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,'Major',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'Grading System',0,1,'L');

        $h = 3.5;
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' Manajemen',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Skala 1-4; A=4, B=3, C=2, D=1',0,1,'L',true);

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,' Management',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Scale 1-4; A=4, B=3, C=2, D=1',0,1,'L',true);
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);

        // +++++++++++++++++++++++++++

        $pdf->Ln(1);
        $h = 3.5;
        $this->minSpaceSKPICustom($pdf,$space_bt,true,$R, $G, $B);
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' Kelas : Reguler',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' LAMA STUDI REGULER',0,1,'L');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,' Class : Reguler',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Reguler Length of Study',0,1,'L');
        $this->minSpaceSKPICustom($pdf,$space_bt,true,$R, $G, $B);

        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        $pdf->Ln(1);
        $h = 3.5;
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' Program Manajemen',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' 8 Semester',0,1,'L',true);

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,' Program Management',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' 8 Semesters',0,1,'L',true);
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);

        // ++++++++++++++++++++++++++++++++++++++++++++++

        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'JENIS & JENJANG PENDIDIKAN',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'JENIS & JENJANG PENDIDIKAN LANJUTAN',0,1,'L');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,'Type & Level of Education',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'Access to Further Study',0,1,'L');

        $h = 3.5;
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' Akademik & Sarjana (Strata 1)',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Program Magister & Doktoral',0,1,'L',true);

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,' Academic & Bachelor Degree',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' master & Doctoral Program',0,1,'L',true);
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);

        // ++++++++++++++++++++++
        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'JENJANG KUALIFIKASI SESUAI KKNI',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'STATUS PROFESI (BILA ADA)',0,1,'L');

        $pdf->SetFont('dinlightitalic','',8);
        $pdf->Cell(90,$h,'Lefel of Qualification the National Qualification Framework',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'Profesional Status (If Applicable)',0,1,'L');

        $h = 6;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,' Level 6',0,0,'L',true);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' -'.$pdf->GetY(),0,1,'L',true);
    }

    private function SKPI_page2($pdf){

        // membuat halaman baru
        $this->newPageSKPI($pdf);

        $R = 226;
        $G = 226;
        $B = 226;
        $h_max = 270;

        $pdf->SetFillColor($R, $G, $B);

        $pdf->Cell(190,2,'','B',1,'L');
        $pdf->Ln(3);
        $h = 4.5;
        $pdf->SetFont('dinpromedium','',11);
        $pdf->Cell(190,$h,'03. INFORMASI TENTANG KULAIFIKASI DAN HASIL YANG DICAPAI',0,1,'L');
        $pdf->SetFont('dinlightitalic','',10);
        $pdf->Cell(190,$h,'03. Information Identifying the Qualification and Outcomes Obtained',0,1,'L');

        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(90,$h,'A. CAPAIAN PEMBELAJARAN',0,0,'L');
        $pdf->SetFont('dinpromediumitalic','',9);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'A. Learning Outcomes',0,1,'L');

        // +++++++++++
        $pdf->Ln(3);
        $space_bt = 1.5;
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);
        $h = 3.5;
        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(90,$h,' SARJANA EKONOMI : MANAJEMEN',0,0,'L',true);
        $pdf->SetFont('dinpromediumitalic','',9);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Management Bachelor Level',0,1,'L',true);


        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(90,$h,' (KKNI Level 6)',0,0,'L',true);
        $pdf->SetFont('dinpromediumitalic','',9);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' (KKNI Level 6)',0,1,'L',true);
        $this->minSpaceSKPI($pdf,$space_bt,true,$R, $G, $B);

        // ==========KEMAMPUAN KERJA============
        $pdf->Ln(3);
        $h = 5.5;
        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(90,$h,' KEMAMPUAN KERJA',0,0,'L',true);
        $pdf->SetFont('dinpromediumitalic','',9);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,' Working Capability',0,1,'L',true);

        $pdf->SetFont('dinprolight','',9);


        $this->loadMultyCellWithNumber($pdf,8);

        //===========================================

        // ==========PENGUASAAN PENGETAHUAN============

        // Cek apakah membuat halaman baru atau tidak
        $newH = $pdf->GetY()+15;
        if($newH>$h_max){
            $this->newPageSKPI($pdf);
            $newH = $pdf->GetY()+5;
        }

        $pdf->SetXY(10,$newH);

        $h = 5.5;
        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(90,$h,' PENGUASAAN PENGETAHUAN',0,0,'L',true);

        $pdf->SetX(110);
        $pdf->SetFont('dinpromediumitalic','',9);
        $pdf->Cell(90,$h,' Knowledge Competencies',0,1,'L',true);

        $this->loadMultyCellWithNumber($pdf,8);

        //===========================================


        // ==========SIKAP KHUSUS============
        $y = $pdf->GetY()+15;
        if($y>$h_max){
            $this->newPageSKPI($pdf);
            $y = $pdf->GetY()+5;
        }

        $pdf->SetXY(10,$y);

        $h = 5.5;
        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(90,$h,' SIKAP KHUSUS',0,0,'L',true);

        $pdf->SetX(110);
        $pdf->SetFont('dinpromediumitalic','',9);
        $pdf->Cell(90,$h,' Special Attitude',0,1,'L',true);

        $this->loadMultyCellWithNumber($pdf,4);

    }

    private function SKPI_page3($pdf){
        // membuat halaman baru
        $pdf->SetMargins(10,3,10);

        $pdf->AddPage();
        $pdf->Ln(30);

        $R = 226;
        $G = 226;
        $B = 226;

        $h_fill = 3.5;
        $h_max = 242;

        $pdf->SetFillColor($R, $G, $B);

        $pdf->Cell(190,2,'','B',1,'L');
        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(90,$h,'B. AKTIVITAS PRESTASI DAN PENGHARGAAN',0,0,'L');
        $pdf->SetFont('dinpromediumitalic','',9);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'B. Activities, Achievements and Awards',0,1,'L');

        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'Pemegang Surat Keterangan Pendamping Ijazah ini memiliki ',0,0,'L');
        $pdf->SetFont('dinlightitalic','',9);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'The holder of this supplement has the following professional',0,1,'L');

        $pdf->SetFont('dinprolight','',9);
        $pdf->Cell(90,$h,'sertifikat professional : ',0,0,'L');
        $pdf->SetFont('dinlightitalic','',9);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'certifications : ',0,1,'L');


        // +++++++++++++++++++++++++++++++++++
        $tx = 'typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ev';
        $tx2 = 'Lorem Ipsum is simply dummy text of the printing and ';

        $y = $pdf->GetY()+3;
        $y2 = $pdf->GetY()+3;

        $nb=0;
        $nb2=0;

        for($i=1;$i<=3;$i++){

            $t1 = ($i%2==0) ? $tx2 : $tx ;
            $t2 = ($i%2==0) ? $tx : $tx2 ;

            //Calculate the height of the row

            $nb =max($nb,$pdf->NbLines(83,$t1));
            $nb2 =max($nb2,$pdf->NbLines(83,$t2));

            $h=$h_fill*$nb;
            $h2=$h_fill*$nb2;

            $hC = ($h > $h2) ? $h : $h2;

            //Issue a page break first if needed
            $pdf->CheckPageBreak($hC);


            $pdf->SetFont('dinprolight','',9);
            // Indo
            $pdf->SetXY(10,$y);
            $pdf->MultiCell(7, $h_fill, $i, 0, 'C',false);
            $pdf->SetXY(17,$y);
            $pdf->MultiCell(83, $h_fill, $y.' - '.$t1, 0, 'L',false);
            $y = $pdf->GetY()+1;

            // Eng
            $pdf->SetFont('dinlightitalic','',9);
            $pdf->SetXY(110,$y2);
            $pdf->MultiCell(7, $h_fill, $i, 0, 'C',false);
            $pdf->SetXY(117,$y2);
            $pdf->MultiCell(83, $h_fill, $y2.' -'.$t2, 0, 'L',false);
            $y2 = $pdf->GetY()+1;

            if($y2>$h_max){
                $pdf->SetMargins(10,3,10);
                $pdf->AddPage();
                $pdf->Ln(30);

                $y = $pdf->GetY()+5;
                $y2 = $pdf->GetY()+5;
                $pdf->Cell(190,2,'','B',1,'L');
            }
        }

        // +++++++++++++++++++++++++++++++++++
        $y = $pdf->GetY()+10;
        $y2 = $pdf->GetY()+10;
        // Indo
        $pdf->SetFont('dinprolight','',9);
        $pdf->SetXY(10,$y);
        $pdf->MultiCell(90, $h_fill,'Mahasiswa Universitas Agung Podomoro telah mengikuti program atau telah memenuhi tanggung jawab sebagai berikut ini :', 0, 'L',false);

        // Eng
        $pdf->SetFont('dinlightitalic','',9);
        $pdf->SetXY(110,$y2);
        $pdf->MultiCell(90, $h_fill,'The students of Agung Podomoro University were involved in the following programs / fulfilled the folowing responsibilities :', 0, 'L',false);


        // +++++++++++++++++++++++++++++++++++
        $y = $pdf->GetY()+5;
        $y2 = $pdf->GetY()+5;

        $nb=0;
        $nb2=0;

        for($i=1;$i<=4;$i++){

            $t1 = ($i%2==0) ? $tx2 : $tx ;
            $t2 = ($i%2==0) ? $tx : $tx2 ;

            //Calculate the height of the row

            $nb =max($nb,$pdf->NbLines(83,$t1));
            $nb2 =max($nb2,$pdf->NbLines(83,$t2));

            $h=$h_fill*$nb;
            $h2=$h_fill*$nb2;

            $hC = ($h > $h2) ? $h : $h2;

            //Issue a page break first if needed
            $pdf->CheckPageBreak($hC);


            $pdf->SetFont('dinprolight','',9);
            // Indo
            $pdf->SetXY(10,$y);
            $pdf->MultiCell(7, $h_fill, $i, 0, 'C',false);
            $pdf->SetXY(17,$y);
            $pdf->MultiCell(83, $h_fill, $y.' - '.$t1, 0, 'L',false);
            $y = $pdf->GetY()+1;

            // Eng
            $pdf->SetFont('dinlightitalic','',9);
            $pdf->SetXY(110,$y2);
            $pdf->MultiCell(7, $h_fill, $i, 0, 'C',false);
            $pdf->SetXY(117,$y2);
            $pdf->MultiCell(83, $h_fill, $y2.' -'.$t2, 0, 'L',false);
            $y2 = $pdf->GetY()+1;

            if($y2>$h_max){
                $pdf->SetMargins(10,3,10);
                $pdf->AddPage();
                $pdf->Ln(30);

                $y = $pdf->GetY()+5;
                $y2 = $pdf->GetY()+5;
                $pdf->Cell(190,2,'','B',1,'L');
            }
        }

        // ++++++++++++++++++
        $y = $pdf->GetY()+10;
        $y2 = $pdf->GetY()+10;
        // Indo
        $pdf->SetFont('dinprolight','',9);
        $pdf->SetXY(10,$y);
        $pdf->MultiCell(90, $h_fill,'Catatan : Program-program tersebut di atas terdiri atas kegiatan untuk mrngrmbangkan soft skills mahasiswa. Daftar kegiatan ko-kurikuler dan ekstra-kulikuler yang diikuti oleh pemegang SKPI ini terlampir.', 0, 'L',false);

        // Eng
        $pdf->SetFont('dinlightitalic','',9);
        $pdf->SetXY(110,$y2);
        $pdf->MultiCell(90, $h_fill,'Note : The above-mentioned programs comprise of activities that develop student\'s soft skill. A list of co-curricular and extra curricular activities taken by the holder of this supplement is attached.', 0, 'L',false);



    }

    private function SKPI_page4($pdf){
        // membuat halaman baru
        $pdf->SetMargins(10,3,10);

        $pdf->AddPage();
        $pdf->Ln(30);

        $R = 226;
        $G = 226;
        $B = 226;

        $h_fill= 3.5;
        $h_max = 242;
        $pdf->SetFillColor($R, $G, $B);

        $pdf->Cell(190,2,'','B',1,'L');
        $pdf->Ln(3);

        $h = 4.5;
        $pdf->SetFont('dinpromedium','',11);
        $pdf->Cell(190,$h,'04. INFORMASI TENTANG PENDIDIKAN TINGGI DI INDONESIA',0,1,'L');
        $pdf->SetFont('dinlightitalic','',10);
        $pdf->Cell(190,$h,'04. Information on the Indonesia Higher Education System and the Indonesian National Qualification Framework',0,1,'L');

        $pdf->Ln(3);
        $h = 3.5;
        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(90,$h,'SISTEM PENDIDIKAN TINGGI DI INDONESIA',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->SetFont('dinpromediumitalic','',9);
        $pdf->Cell(90,$h,'Higher Edocation System In Indonesia',0,1,'L');

        $t = '
                Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.

                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                
                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                ';

        $y2 = $pdf->GetY()+4;
        $pdf->SetFont('dinprolight','',9);
        $pdf->SetXY(10,$y2);
        $pdf->MultiCell(90, $h_fill, $t, 0, 'L',false);

        $pdf->SetFont('dinlightitalic','',9);
        $pdf->SetXY(110,$y2);
        $pdf->MultiCell(90, $h_fill, $t, 0, 'L',false);



    }

    private function SKPI_page5($pdf){
        // membuat halaman baru
        $pdf->SetMargins(10,3,10);

        $pdf->AddPage();
        $pdf->Ln(30);

        $R = 226;
        $G = 226;
        $B = 226;

        $h_fill= 3.5;
        $h_max = 242;
        $pdf->SetFillColor($R, $G, $B);

        $pdf->Cell(190,2,'','B',1,'L');
        $pdf->Ln(3);

        $h = 4.5;
        $pdf->SetFont('dinpromedium','',11);
        $pdf->Cell(190,$h,'05. KERANGKA KUALIFIKASI NASIONAL INDONESIA (KKNI)',0,1,'L');
        $pdf->SetFont('dinlightitalic','',10);
        $pdf->Cell(190,$h,'05. Indonesian National Qualification Framework',0,1,'L');

    }

    private function SKPI_page6($pdf){
        // membuat halaman baru
        $this->newPageSKPI($pdf);

        $R = 226;
        $G = 226;
        $B = 226;

        $h_fill= 3.5;
        $h_max = 242;
        $pdf->SetFillColor($R, $G, $B);

        $pdf->Cell(190,2,'','B',1,'L');
        $pdf->Ln(3);

        $h = 4.5;
        $pdf->SetFont('dinpromedium','',11);
        $pdf->Cell(190,$h,'06. PENGESAHAN SKPI',0,1,'L');
        $pdf->SetFont('dinlightitalic','',10);
        $pdf->Cell(190,$h,'06. SKPI Legalization',0,1,'L');


        $h = 4;
        $pdf->Ln(15);
        $pdf->SetFont('dinprolight','',12);
        $pdf->Cell(190,$h,'JAKARTA, 20 DESEMBER 2019',0,1,'L');
        $pdf->SetFont('dinlightitalic','',10);
        $pdf->Cell(190,$h,'Jakarta, December 20, 2019',0,1,'L');

        $pdf->Ln(20);
        $pdf->SetFont('dinpromedium','',12);
        $pdf->Cell(190,$h,'NANDANG MULYADI SO, SE, S.KOM, MM, MBA, PH.D',0,1,'L');
        $pdf->Ln(2);
        $pdf->SetFont('dinprolight','',10);
        $pdf->Cell(190,$h,'DEKAN SEKOLAH MANAJEMENT PODOMORO UNIVERSITY',0,1,'L');
        $pdf->SetFont('dinlightitalic','',9);
        $pdf->Cell(190,$h,'Dean School of Business Management',0,1,'L');

        $pdf->Ln(4);
        $pdf->SetFont('dinprolight','',10);
        $pdf->Cell(190,$h,'NOMOR INDUK PEGAWAI : 2017090',0,1,'L');
        $pdf->SetFont('dinlightitalic','',9);
        $pdf->Cell(190,$h,'Employee ID Number',0,1,'L');

        $pdf->Ln(7);
        $pdf->Cell(190,2,'','B',1,'L');
        $pdf->Ln(7);


        $h = 3.5;
        $pdf->SetFont('dinpromedium','',9);
        $pdf->Cell(90,$h,'CATATAN RESMI',0,0,'L');
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->SetFont('dinpromediumitalic','',9);
        $pdf->Cell(90,$h,'Official Notes',0,1,'L');

        $this->loadMultyCellWithNumber($pdf,4);

        $pdf->Ln(15);
        $pdf->SetFont('dinpromedium','',8);
        $pdf->Cell(130,$h,'',0,0,'L');
        $pdf->Cell(60,$h,'ALAMAT',0,1,'L');
        $pdf->SetFont('dinpromediumitalic','',7);
        $pdf->Cell(130,$h,'',0,0,'L');
        $pdf->Cell(60,$h,'Contact Details',0,1,'L');


        $pdf->Ln(3);
        $pdf->SetFont('dinprolight','',8);
        $pdf->Cell(130,$h,'',0,0,'L');
        $pdf->Cell(60,$h,'UNIVERSITAS AGUNG PODOMORO',0,1,'L');
        $pdf->SetFont('dinlightitalic','',7);
        $pdf->Cell(130,$h,'',0,0,'L');
        $pdf->Cell(60,$h,'Agung Podomoro University',0,1,'L');

        $alamat = 'Located in: Central Park
Address: Jl. Letjen S. Parman No.28, RT.12/RW.6, Tj. Duren Sel., Grogol petamburan, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11470
Province: Jakarta
Founded: 2014
Phone: (021) 29200456';

        $y = $pdf->GetY()+2;
        $pdf->SetFont('dinprolight','',9);
        $pdf->SetXY(140,$y);
        $pdf->MultiCell(60, $h_fill, $alamat, 0, 'L',false);
//        $h = 3.5;
//        $pdf->SetFont('dinpromedium','',9);
//        $pdf->Cell(120,$h,'CATATAN RESMI',1,0,'L');
//        $pdf->Cell(10,$h,'',1,0,'L');
//        $pdf->SetFont('dinpromediumitalic','',9);
//
//        $pdf->SetFont('dinpromedium','',9);
//        $pdf->Cell(60,$h,'UNIVERSITAS AGUNG PODOMORO',1,1,'L');

    }

    private function minSpaceSKPI($pdf,$h,$color,$R,$G,$B){

        $pdf->SetFillColor($R,$G,$B);
        $pdf->Cell(90,$h,'',0,0,'L',$color);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'',0,1,'L',$color);
    }

    private function minSpaceSKPICustom($pdf,$h,$color,$R,$G,$B){

        $pdf->SetFillColor($R,$G,$B);
        $pdf->Cell(90,$h,'',0,0,'L',$color);
        $pdf->Cell(10,$h,'',0,0,'L');
        $pdf->Cell(90,$h,'',0,1,'L');
    }

    private function loadMultyCellWithNumber($pdf,$lengthData){

        $h_fill= 3.5;
        $h_max = 270;

        $tx = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ev';

        $tx2 = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting';

        $tx3 = 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.';

//        $lengthData = 7;

        $lineBreakLeft = 0;
        $lineBreakRight = 0;

        $lineBreakLeft2 = 0;
        $lineBreakRight2 = 0;

        $no_left = 1;
        // Bagian Kiri
        $y_left = $pdf->GetY()+5;
        $y_right = $pdf->GetY()+5;
        for($i=0;$i<$lengthData;$i++){
            $nb=0;
            $t = ($i%2==0) ? $tx : $tx3 ;
            $t1 = substr($t,0,280);

            $nb =max($nb,$pdf->NbLines(83,$t1));
            $h=$h_fill*$nb;

            if($y_left+$h > $h_max){
                $lineBreakLeft = $i;
                break;
            }

            // Indo
            $pdf->SetFont('dinprolight','',9);
            $pdf->SetXY(10,$y_left);
            $pdf->MultiCell(7, $h_fill, ($no_left++), 0, 'C',false);
            $pdf->SetXY(17,$y_left);
            $pdf->MultiCell(83, $h_fill, $t1, 0, 'L',false);
            $y_left = $y_left+$h+1;

        }

        $no_right = 1;
        for($i=0;$i<$lengthData;$i++){
            $nb=0;
            $t = ($i%2==0) ? $tx : $tx2 ;
            $t1 = substr($t,0,280);

            $nb =max($nb,$pdf->NbLines(83,$t1));
            $h=$h_fill*$nb;

            if($y_right+$h > $h_max){
                $lineBreakRight = $i;
                break;
            }

            // Eng
            $pdf->SetFont('dinlightitalic','',9);
            $pdf->SetXY(110,$y_right);
            $pdf->MultiCell(7, $h_fill, ($no_right++), 0, 'C',false);
            $pdf->SetXY(117,$y_right);
            $pdf->MultiCell(83, $h_fill, $nb.' - '.$t1, 0, 'L',false);
            $y_right = $y_right+$h+1;
        }


        // Cek apakah masih ada kelanjutannya atau tidak untuk page 2
        if(($lineBreakLeft !=0 || $lineBreakRight!=0) && ($lineBreakLeft < $lengthData || $lineBreakRight < $lengthData)){
            $this->newPageSKPI($pdf);

            $y_left = $pdf->GetY()+5;
            $y_right = $pdf->GetY()+5;
            $pdf->Cell(190,2,'','B',1,'L');

            if($lineBreakLeft !=0 && $lineBreakLeft < $lengthData){
                // Looping sekali lagi
                // Bagian Kiri
                for($i2=$lineBreakLeft;$i2<$lengthData;$i2++){
                    $nb=0;
                    $t = ($i2%2==0) ? $tx : $tx3 ;
                    $t1 = substr($t,0,280);

                    $nb =max($nb,$pdf->NbLines(83,$t1));
                    $h=$h_fill*$nb;

                    if($y_left+$h > $h_max){
                        $lineBreakLeft2 = $i2;
                        break;
                    }

                    // Indo
                    $pdf->SetFont('dinprolight','',9);
                    $pdf->SetXY(10,$y_left);
                    $pdf->MultiCell(7, $h_fill, ($no_left++), 0, 'C',false);
                    $pdf->SetXY(17,$y_left);
                    $pdf->MultiCell(83, $h_fill, $t1, 0, 'L',false);
                    $sp = ($i2==$lineBreakLeft) ? 4 : 1;
                    $y_left = $y_left+$h+$sp;
                }
            }

            if($lineBreakRight!=0 && $lineBreakRight < $lengthData){
                // Bagian Kanan
                for($i2=$lineBreakRight;$i2<$lengthData;$i2++){
                    $nb=0;
                    $t = ($i2%2==0) ? $tx : $tx3 ;
                    $t1 = substr($t,0,280);

                    $nb =max($nb,$pdf->NbLines(83,$t1));
                    $h=$h_fill*$nb;

                    if($y_right+$h > $h_max){
                        $lineBreakRight2 = $i2;
                        break;
                    }
                    // Eng
                    $pdf->SetFont('dinlightitalic','',9);
                    $pdf->SetXY(110,$y_right);
                    $pdf->MultiCell(7, $h_fill, ($no_right++), 0, 'C',false);
                    $pdf->SetXY(117,$y_right);
                    $pdf->MultiCell(83, $h_fill, $t1, 0, 'L',false);
                    $sp = ($i2==$lineBreakRight) ? 4 : 1;
                    $y_right = $y_right+$h+$sp;
                }
            }


        }


        // Cek apakah masih ada kelanjutannya atau tidak untuk page 3
        if(($lineBreakLeft2 !=0 || $lineBreakRight2!=0) && ($lineBreakLeft2 < $lengthData || $lineBreakRight2 < $lengthData)){
            $this->newPageSKPI($pdf);

            $y_left = $pdf->GetY()+5;
            $y_right = $pdf->GetY()+5;
            $pdf->Cell(190,2,'','B',1,'L');

            if($lineBreakLeft2 !=0 && $lineBreakLeft2 < $lengthData){
                // Looping sekali lagi
                // Bagian Kiri
                for($i3=$lineBreakLeft2;$i3<$lengthData;$i3++){

                    $t = ($i3%2==0) ? $tx : $tx3 ;
                    $t1 = substr($t,0,280);

                    // Indo
                    $pdf->SetFont('dinprolight','',9);
                    $pdf->SetXY(10,$y_left);
                    $pdf->MultiCell(7, $h_fill, ($no_left++), 0, 'C',false);
                    $pdf->SetXY(17,$y_left);
                    $pdf->MultiCell(83, $h_fill, $t1, 0, 'L',false);
                    $y_left = $y_left+1;
                }
            }

            if($lineBreakRight2!=0 && $lineBreakRight2 < $lengthData){
                // Bagian Kanan
                for($i3=$lineBreakRight2;$i3<$lengthData;$i3++){

                    $t = ($i3%2==0) ? $tx2 : $tx3 ;
                    $t1 = substr($t,0,280);
                    // Eng
                    $pdf->SetFont('dinlightitalic','',9);
                    $pdf->SetXY(110,$y_right);
                    $pdf->MultiCell(7, $h_fill, ($no_right++), 0, 'C',false);
                    $pdf->SetXY(117,$y_right);
                    $pdf->MultiCell(83, $h_fill, $t1, 0, 'L',false);
                    $y_right = $y_right+1;
                }
            }

        }

    }

    private function newPageSKPI($pdf){
        $pdf->SetMargins(10,3,10);
        $pdf->AddPage();
        $pdf->Ln(30);
    }

    public function export_kwitansi_formuliroffline()
    {
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
        $this->load->model('master/m_master');
        $taGet = $this->m_master->showData_array('db_admission.set_ta');
        $ta = substr($taGet[0]['Ta'], 2,2);
        $InputDate = $input['date'];
        $InputDate = explode('-', $InputDate);
        $bulanRomawi = $this->m_master->romawiNumber($InputDate[1]);

        $maxLen = 4;
        $NoKwitansi = $input['NumForm'];
        $aa = strlen($NoKwitansi);
        for ($i=0; $i < ( $maxLen - $aa ); $i++) {
            $NoKwitansi = '0'.$NoKwitansi;
        }
        $nomorWr = $InputDate[0].' / '.$bulanRomawi.' / FRM'.' / '.'MKT-PU-'.$ta.' / '.$NoKwitansi;


        $fpdf = new Fpdf('L', 'mm', array(216, 140));
        //$fpdf = new Fpdf('P', 'mm', 'A4');
        //$fpdf = new Fpdf('P', 'mm', array(215,140));

        //$fpdf->SetMargins(0, 0);
        //$fpdf->SetDisplayMode('real');
        $fpdf->SetAutoPageBreak(true, 0);
        $fpdf->AddPage();

        //====================== WATERMARK ======================
        // if ($data['print'] > 2) {
        //     $fpdf->SetTextColor(209, 209, 209);
        //     $fpdf->SetFont('Arial', '', 100);
        //     $fpdf->Text(42, 70, 'C O P Y');
        // }
        // rahmat 23 Februari 2016
        //====================== Nomor Form ======================
        $fpdf->SetFont('Arial', 'B', 8);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetXY(1, 5);
        $fpdf->Cell(206, 7, 'FM-UAP/KEU-06-03', 0, 0, 'R');

        // End rahmat

        //====================== HEADER ======================
        $fpdf->SetFont('Arial', 'B', 18);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetXY(5, 5);
        $fpdf->ln(1);
        $fpdf->Cell(206, 7, 'TANDA TERIMA PEMBAYARAN', 0, 0, 'C');
        $fpdf->ln(7);
        $fpdf->Line(65, 12.5, 163, 12.5);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->Cell(206, 5, 'Nomor: '.$nomorWr, 0, 0, 'C');

        // //====================== CONTENT ======================
        // if ($data['form'] == NULL) {
        //     $no_lbl = 'NIM';
        //     $no_txt = $data['nim'];
        // }
        // else {
        //     $no_lbl = 'No Form';
        //     $no_txt = $data['form'];
        // }

        $fpdf->SetFont('Arial', '', 14);
        $fpdf->Text(23, 28, 'Telah terima dari,');
        $no_lbl = 'No Form';

        $fpdf->SetFont('Arial', '', 14);
        $fpdf->Text(23, 36, $no_lbl);
        $fpdf->Text(23, 43, 'Nama lengkap');
        $fpdf->Text(23, 50, 'Tlp / HP');
        $fpdf->Text(23, 57, 'Jurusan');
        $fpdf->Text(23, 64, 'Pembayaran');
        $fpdf->Text(23, 71, 'Jenis');
        $fpdf->Text(23, 78, 'Jumlah');
        $fpdf->Text(23, 85, 'Terbilang');



        $fpdf->Text(59, 36, ':');
        $fpdf->Text(59, 43, ':');
        $fpdf->Text(59, 50, ':');
        $fpdf->Text(59, 57, ':');
        $fpdf->Text(59, 64, ':');
        $fpdf->Text(59, 71, ':');
        $fpdf->Text(59, 78, ':');
        $fpdf->Text(59, 85, ':');

        $terbilang = $this->m_master->moneySay($input['jumlah']);
        $fpdf->Text(64, 36, $input['NoFormRef'] );
        $fpdf->Text(64, 43, $input['namalengkap']);
        $fpdf->Text(64, 50, $input['hp']);
        $fpdf->Text(64, 57, $input['jurusan']);
        $fpdf->Text(64, 64, $input['pembayaran']);
        $fpdf->Text(64, 71, $input['jenis']);
        $fpdf->Text(64, 78, 'Rp '.number_format($input['jumlah'],2,',','.').',-');
        $fpdf->Text(64, 85, $terbilang);




        $fpdf->Line(63, 37, 195, 37);
        $fpdf->Line(63, 44, 195, 44);
        $fpdf->Line(63, 51, 195, 51);
        $fpdf->Line(63, 58, 195, 58);
        $fpdf->Line(63, 65, 195, 65);
        $fpdf->Line(63, 72, 195, 72);
        $fpdf->Line(63, 79, 195, 79);
        $fpdf->Line(63, 86, 195, 86);

        //====================== FOOTER / SIGN ======================
        $printDate = $this->m_master->getIndoBulan(date('Y-m-d'));
        $fpdf->SetFont('Arial', '', 14);
        $fpdf->SetXY(140, 92);
        $fpdf->Cell(60, 5, 'Jakarta, '.$printDate, 0, 0, 'C');
        $fpdf->SetXY(140, 116);
        $fpdf->SetFont('Arial', 'U', 14);
        $fpdf->Cell(60, 5, '( '.$this->session->userdata('Name').' )', 0, 0, 'C');

        //====================== FINISH ======================

        $fpdf->Output('receipt.pdf','I');
    }



}
