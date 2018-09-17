<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_save_to_pdf extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('JWT');
        $this->load->library('pdf');

        $this->load->model('report/m_save_to_pdf');

        date_default_timezone_set("Asia/Jakarta");
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

        $this->headerDefault($pdf);

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

        $this->headerDefault($pdf);

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
        $this->headerDefault($pdf);


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
        $this->headerDefault($pdf);

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

    private function headerDefault($pdf){
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

                            $this->exam_attendance_students($pdf,$data_arr,$dataDetailExam,$dataCourse);

                            $pdf->SetFont('Times','',7);
                            $pdf->Ln(9);
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

            $pdf = new FPDF('L','mm','A4');
            if(count($dataExam)>0){
                $pdf->SetMargins(10,3,10);
                $pdf->AddPage();

                $this->header_attendance_pengawas($pdf,$data_arr);

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


                for($ex=0;$ex<count($dataExam);$ex++){

                    $dataDetailExam = $dataExam[$ex];

                    $this->attendance_pengawas($pdf,$data_arr,$dataDetailExam);
                }
            }

            $pdf->Output('document_pengawas.pdf','I');

        }
        else if($data_arr['DocumentType']==5){

            // Get data exam
            $dataExam = $this->m_save_to_pdf->getExamScheduleWithStudent($data_arr['SemesterID'],$data_arr['Type'],$data_arr['ExamDate']);

//            print_r($dataExam);
//            exit;

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

        $pdf->Cell(135,$h,'EXAM PAPER HANDOVER',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'EXAM PAPER HANDOVER',0,1,'C');

        $pdf->Cell(135,$h,'ACADEMIC YEAR '.strtoupper($data_arr['Semester']),0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'ACADEMIC YEAR '.strtoupper($data_arr['Semester']),0,1,'C');

        $pdf->SetFont('Times','',10);

        $pdf->Ln(4);
        $examType = ($data_arr['Type']=='UTS' || $data_arr['Type']=='uts')
            ? 'Mid Exam' : 'Fina Exam';

        $pdf->Cell(135,$h,'It has handover exam paper of '.$examType,0,0,'L');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'It has handover exam paper of '.$examType,0,1,'L');

        $pdf->Ln(4);

        $w_t = 30;
        $w_pars = 5;
        $w_fill = 100;
        $w_space = 17;

        $pdf->Cell($w_t,$h,'Class Group',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['ClassGroup'],0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Class Group',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['ClassGroup'],0,1,'L');


        $pdf->Cell($w_t,$h,'Code',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['MKCode'],0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Code',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['MKCode'],0,1,'L');


        $pdf->Cell($w_t,$h,'Course',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['Course'],0,0,'L');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell($w_t,$h,'Course',0,0,'L');
        $pdf->Cell($w_pars,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,$dataCourse['Course'],0,1,'L');


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
        $pdf->Cell(67.5,$h,'Submitted by',0,0,'C');
        $pdf->Cell(67.5,$h,'Received by',0,0,'C');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell(67.5,$h,'Submitted by',0,0,'C');
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
        $pdf->Cell(67.5,$h,'Submitted by',0,0,'C');
        $pdf->Cell(67.5,$h,'Received by',0,0,'C');
        $pdf->Cell($w_space,$h,'',0,0);
        $pdf->Cell(67.5,$h,'Submitted by',0,0,'C');
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
            ? 'Ujian Tengah Semester GASAL' : 'Ujian Akhir Semester GENAP';

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
        $pdf->Cell($w_label,$h,'Hari, Tanggal',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,date("l, d F Y", strtotime($dataDetailExam['ExamDate'])),0,1,'L');

        $pdf->Cell($w_sp1,$h,'',0,0,'L');
        $pdf->Cell($w_label,$h,'Waktu',0,0,'L');
        $pdf->Cell($w_sp2,$h,':',0,0,'C');
        $pdf->Cell($w_fill,$h,substr($dataDetailExam['ExamStart'],0,5).' - '.substr($dataDetailExam['ExamEnd'],0,5),0,1,'L');

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
        for($s=1;$s<=5;$s++){
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

    private function exam_attendance_students($pdf,$data_arr,$dataDetailExam,$dataCourse){
        $pdf->Image(base_url('images/icon/favicon.png'),15,13,15);

        $pdf->SetFont('Times','I',7);
        $pdf->Cell(195,1,'FM-UAP/AKD-13-03A',0,1,'R');

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

        $pdf->SetFont('Times','B',10);
        $pdf->Cell(195,$h,'ATTENDANCE FINAL EXAM',0,1,'C');
        $pdf->Cell(195,$h,'EVEN SEMESTER ACADEMIC YEAR '.strtoupper($data_arr['Semester']),0,1,'C');

        $pdf->Ln(5);
        $pdf->SetFont('Times','',10);


        $w_label_r = 22;
        $w_sp_r = 3;
        $w_fill_r = 105;

        $w_label_l = 18;
        $w_sp_l = 3;
        $w_fill_l = 45;

        $pdf->Cell($w_label_r,$h,'Class Group',0,0,'L');
        $pdf->Cell($w_sp_r,$h,':',0,0,'C');
        $pdf->Cell($w_fill_r,$h,$dataCourse['ClassGroup'],0,0,'L');

        $pdf->Cell($w_label_l,$h,'Day, Date',0,0,'L');
        $pdf->Cell($w_sp_l,$h,':',0,0,'C');
        $pdf->Cell($w_fill_l,$h,date("l, d M Y", strtotime($dataDetailExam['ExamDate'])),0,1,'L');

        $dCourse = (strlen($dataCourse['Course'])>=50) ? substr($dataCourse['Course'],0,50).'_' : $dataCourse['Course'];

        $pdf->Cell($w_label_r,$h,'Course',0,0,'L');
        $pdf->Cell($w_sp_r,$h,':',0,0,'C');
        $pdf->Cell($w_fill_r,$h,$dataCourse['MKCode'].' - '.$dCourse,0,0,'L');

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

                $no++;
            }
        }

        // Kosong
        for($k=1;$k<=2;$k++){
            $pdf->Cell(10,$h,'',1,0,'C');
            $pdf->Cell(25,$h,'',1,0,'C');
            $pdf->Cell(110,$h,'',1,0,'L');
            $pdf->Cell(30,$h,'',1,0,'C');
            $pdf->Cell(20,$h,'',1,1,'C');
        }

        $pdf->Ln(5);
        $pdf->Cell(150,$h,'',0,0,'C');
        $pdf->Cell(45,$h,'Sign by Lecturer',0,1,'C');

        $pdf->Ln(8);

        $pdf->Cell(150,$h,'',0,0,'C');
        $pdf->Cell(45,$h,'( _ _ _ _ _ _ _ _ _ )',0,1,'C');


    }

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

    }

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

        $pdf->Cell(135,$h,$xam_t,0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,$xam_t,0,1,'C');

        $pdf->Cell(135,$h,'EVEN SEMESTER',0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'EVEN SEMESTER',0,1,'C');

        $pdf->Cell(135,$h,'ACADEMIC YEAR '.strtoupper($data_arr['Semester']),0,0,'C');
        $pdf->Cell(17,$h,'',0,0);
        $pdf->Cell(135,$h,'ACADEMIC YEAR '.strtoupper($data_arr['Semester']),0,1,'C');

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

        //======================================

        $pdf->Ln(7);

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
}
