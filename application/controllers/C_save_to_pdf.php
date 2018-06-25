<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_save_to_pdf extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('JWT');
        $this->load->library('pdf');
        date_default_timezone_set("Asia/Jakarta");
    }

    private function header_exam_layout($pdf){

        $pdf->Image(base_url('images/icon/logo-l.png'),10,10,30);

        $pdf->SetFont('Arial','B',10);

        $pdf->Cell(45,11,'',0,0,'C');
        $pdf->Cell(230,11,'SEATING MAP FINAL EXAM (UAS) - 2017/2018 GANJIL',1,1,'C');

        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(0,7,'Page : '.$pdf->PageNo().' of {nb}',0,0,'R');

        $pdf->SetFont('Arial','',10);

        $pdf->Cell(190,3,'',0,1);

        $space_header = 5;
        $pdf->Cell(45,$space_header,'',0,0);
        $pdf->Cell(20,$space_header,'Course',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(92,$space_header,'Studio 4',0,0);
        $pdf->Cell(20,$space_header,'Date',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(92,$space_header,'Tuesday, 8 Jun 2018',0,1);

        $pdf->Cell(45,$space_header,'',0,0);
        $pdf->Cell(20,$space_header,'Pengawas 1',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(92,$space_header,'Nandang M',0,0);
        $pdf->Cell(20,$space_header,'Time',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(92,$space_header,'08:00 - 09:00',0,1);

        $pdf->Cell(45,$space_header,'',0,0);
        $pdf->Cell(20,$space_header,'Pengawas 2',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(92,$space_header,'-',0,0);
        $pdf->Cell(20,$space_header,'Room',0,0);
        $pdf->Cell(3,$space_header,':',0,0,'C');
        $pdf->Cell(92,$space_header,'503',0,1);

        $pdf->Cell(275,7,'',0,1);
        $pdf->Cell(275,0.3,'',1,1);

        // Lecturer
        $mejaGuru = 'kiri2';
        if($mejaGuru=='kiri'){
            $pdf->Image(base_url('images/icon/lecturer.png'),25,50,15);
            $pdf->Image(base_url('images/icon/door.png'),250,50,15);
        } else {
            $pdf->Image(base_url('images/icon/door.png'),25,50,15);
            $pdf->Image(base_url('images/icon/lecturer.png'),250,50,15);
        }


        $pdf->SetFillColor(226, 226, 226);
        $pdf->Cell(275,7,'',0,1);
        $pdf->Cell(45,5,'',0,0);
        $pdf->Cell(180,7,'Board',1,1,'C',true);

        $pdf->Cell(275,15,'',0,1);

        $pdf->AliasNbPages();

    }

    public function exam_layout(){
        $pdf = new FPDF('l','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();

        $this->header_exam_layout($pdf);

        $base_x = 10;
        $base_y = 75;
        $koor_x = $base_x;
        $koor_y = $base_y;
        $no=1;

        $jml_deret = 7; // Dinamis
        $total_w = 275;
        $space = 2;
        $width = ($total_w - (($jml_deret - 1) * $space)) / $jml_deret;

        $height_panel = 7;
        $pdf->SetFont('Arial','',10);
        $pdf->setFillColor(255,255,102);

        for($m=1;$m<=170;$m++){

            // NIM
            $pdf->SetXY($koor_x,$koor_y);
            $pdf->Cell($width,$height_panel,'NPM '.$m,1,0,'C',true);

            // Name
            $pdf->SetXY($koor_x,($koor_y+7));
            $nm = $m."_Nandang M";
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
                    $pdf->AddPage();
                    $this->header_exam_layout($pdf);
                    $pdf->SetFont('Arial','',10);
                    $pdf->setFillColor(255,255,102);
                    $koor_x = $base_x;
                    $koor_y = $base_y;
                }
            } else {
                $koor_x = $koor_x+$width;
            }

            $no++;
        }

//        $pdf->Output('Study_Card.pdf','D');
        $pdf->Output();
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

    // Naskah Soal
    public function draft_questions(){

        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();

        $this->header_exam($pdf);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(185,7,'',0,1);
        $pdf->Cell(185,5,'DRAFT QUESTIONS',0,1,'C');
        $pdf->Cell(185,5,'ATTENDANCE FINAL EXAMINATION',0,1,'C');
        $pdf->Cell(185,5,'EVEN SEMESTER',0,1,'C');
        $pdf->Cell(185,5,'ACADEMIC YEAR 2017/2018',0,1,'C');

        $pdf->Cell(185,7,'',0,1);

        $pdf->Rect(10, 76, 185, 93);
        $pdf->Rect(10, 184, 185, 23);

        $pdf->SetFont('Arial','I',8);
        $pdf->Cell(185,5,'Exam Scheduled / Supplementary *)',0,1,'R');

        $height_fill = 10;
        $pdf->Cell(185,5,'',0,1);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Information Exam',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'UAS',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Study Program',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'HBP',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Code | Course',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'HOT0014 | Food and Beverage - Service Theory',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Class Group',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'A',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Lecturer/s',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'MICHAEL YADISAPUTRA, MICHAEL YADISAPUTRA',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Day, Date',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'Monday, 25 June 2018',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Time, Room',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'08:00 - 09:00, 503',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Total Script',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'______________________',0,1);

        $pdf->Cell(185,7,'',0,1);
        $pdf->SetFont('Arial','I',8);
        $pdf->Cell(185,7,'Note : Cross out unnecessary *)',0,1,'L');


        $pdf->Cell(185,10,'',0,1);
        $pdf->SetFont('Arial','',10);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Pengawas 1',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'Nandang Mulyadi',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(40,$height_fill,'Pengawas 2',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(135,$height_fill,'-',0,1);

        $pdf->Cell(185,10,'',0,1);
        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(185,5,'Download On : '.date("d M Y H:i:s").' | '.chr(169).' IT Podomoro University',0,1,'R');

//        $pdf->Output('Study_Card.pdf','D');
        $pdf->Output();

    }

    // Lembar Jawaban
    public function answer_sheet(){

        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();

        $this->header_exam($pdf);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(185,7,'',0,1);
        $pdf->Cell(185,5,'ANSWER SHEET',0,1,'C');
        $pdf->Cell(185,5,'ATTENDANCE FINAL EXAMINATION',0,1,'C');
        $pdf->Cell(185,5,'EVEN SEMESTER',0,1,'C');
        $pdf->Cell(185,5,'ACADEMIC YEAR 2017/2018',0,1,'C');

        $pdf->Cell(185,7,'',0,1);

        $pdf->Rect(10, 76, 185, 93);
        $pdf->Rect(10, 184, 185, 23);

        $pdf->SetFont('Arial','I',8);
        $pdf->Cell(185,5,'Exam Scheduled / Supplementary *)',0,1,'R');

        $height_fill = 10;
        $pdf->Cell(185,5,'',0,1);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Information Exam',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'UAS',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Study Program',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'HBP',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Code | Course',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'HOT0014 | Food and Beverage - Service Theory',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Class Group',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'A',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Lecturer/s',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'MICHAEL YADISAPUTRA, MICHAEL YADISAPUTRA',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Day, Date',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'Monday, 25 June 2018',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Time, Room',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'08:00 - 09:00, 503',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Number of Answer Sheet',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'______________________',0,1);

        $pdf->Cell(185,7,'',0,1);
        $pdf->SetFont('Arial','I',8);
        $pdf->Cell(185,7,'Note : Cross out unnecessary *)',0,1,'L');


        $pdf->Cell(185,10,'',0,1);
        $pdf->SetFont('Arial','',10);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Pengawas 1',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'Nandang Mulyadi',0,1);

        $pdf->Cell(5,$height_fill,'',0,0);
        $pdf->Cell(45,$height_fill,'Pengawas 2',0,0,'L');
        $pdf->Cell(5,$height_fill,':',0,0);
        $pdf->Cell(130,$height_fill,'-',0,1);

        $pdf->Cell(185,10,'',0,1);
        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(185,5,'Download On : '.date("d M Y H:i:s").' | '.chr(169).' IT Podomoro University',0,1,'R');

//        $pdf->Output('Study_Card.pdf','D');
        $pdf->Output();

    }

    // Berita Acara
    public function news_event(){

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

    private function header_attendance($pdf){
        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(0,7,'Page : '.$pdf->PageNo().' of {nb}',0,0,'R');
        $pdf->AliasNbPages();

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(185,7,'',0,1);
        $pdf->Cell(185,5,'ATTENDANCE FINAL EXAMINATION',0,1,'C');
        $pdf->Cell(185,5,'EVEN SEMESTER ACADEMIC YEAR 2017/2018',0,1,'C');

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



    private function header_attd_table($pdf){
        $pdf->Cell(185,7,'',0,1,'');
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

    // Daftar Hadir
    public function attendance_list(){

        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        $this->header_exam($pdf);

        $this->header_attendance($pdf);

        $this->header_attd_table($pdf);


        $width_attd = 9;

        $no = 1;
        for($s=0;$s<26;$s++){
            $pdf->Cell(10,$width_attd,$no++,1,0,'C');
            $pdf->Cell(25,$width_attd,'21140008',1,0,'C');
            $pdf->Cell(95,$width_attd,'Name',1,0,'L');
            $pdf->Cell(35,$width_attd,'Sign',1,0,'C');
            $pdf->Cell(20,$width_attd,''.$pdf->GetY(),1,1,'C');

            if($pdf->GetY()>=265){
                $pdf->AddPage();
                $this->header_exam($pdf);
                $this->header_attendance($pdf);
                $this->header_attd_table($pdf);
            }

        }



        $pdf->Cell(185,7,'',0,1,'');
        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(185,3,'Download On : '.date("d M Y H:i:s").' | '.chr(169).' IT Podomoro University',0,1,'R');



        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(185,17,'',0,1,'');
        $pdf->Cell(130,3,'',0,0,'R');
        $pdf->Cell(55,3,'Sign by Lecturer : ',0,1,'C');

        $pdf->Cell(185,17,'',0,1,'');

        $pdf->Cell(130,3,'',0,0,'R');
        $pdf->Cell(55,3,'( ...................... )',0,1,'C');




//        $pdf->Output('Study_Card.pdf','D');
        $pdf->Output();

    }


}
