<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_save_to_excel extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('JWT');
//        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('report/m_save_to_excel');

    }

    private function getInputToken($token)
    {
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function test2()
    {

//        echo 'ok';
//        exit;

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes

        // Panggil class PHPExcel nya
        $excel = new PHPExcel();

        $pr = 'REKAP NILAI';

        // Settingan awal fil excel
        $excel->getProperties()->setCreator('IT PU')
            ->setLastModifiedBy('IT PU')
            ->setTitle($pr)
            ->setSubject($pr)
            ->setDescription($pr)
            ->setKeywords($pr);

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', $pr); // Set kolom A1 dengan tulisan "DATA KARYAWAN"
        $excel->getActiveSheet()->mergeCells('A1:O1'); // Set Merge Cell pada kolom A1 sampai O1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NIM"); // Set kolom A3 dengan tulisan "NIK"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Nama");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Prodi");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Code");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Course");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Group");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Coordinator");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Assignment 1");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Assignment 2");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Assignment 3");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Assignment 4");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "Assignment 5");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "UTS");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "UAS");
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Score");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "Grade");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O3')->applyFromArray($style_col);

        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4


        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Rekap Data Karyawan");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        $filename = "Rekap_Data_Karyawan.xlsx";
        //$FILEpath = "./dokument/".$filename;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=test.xlsx'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
//            $write->save($FILEpath);

        //echo json_encode(array('file' => $filename));

        // exit else ajax
    }


    function test(){

//        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
//        $excel2 = $excel2->load('./uploads/finance/TemplatePembayaran.xlsx'); // Empty Sheet
//
//        $excel = new PHPExcel();
//
//        $excel->getProperties()->setCreator('Alhadi Rahman')
//            ->setLastModifiedBy('Alhadi Rahman')
//            ->setTitle("Data Karyawan Produksi")
//            ->setSubject("Data Karyawan Produksi")
//            ->setDescription("Rekap Data Karyawan Produksi")
//            ->setKeywords("Data Karyawan Produksi");
//
//        $excel2->setActiveSheetIndex(0);
//
//        $excel3 = $excel2->getActiveSheet();
        $excel3 =  new PHPExcel();;
        $excel3->setCellValue('A2', 'Rekap Penerimaan & AGING ');

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // start dari A7
        $d = ' Nandang';
        $a = 8;
        for ($i=0; $i < 5; $i++) {
            $no = $i + 1;
            $excel3->setCellValue('A'.$a, $no);
            $excel3->setCellValue('B'.$a, $d);
            $excel3->setCellValue('C'.$a, $d);
            $excel3->setCellValue('D'.$a, $d);
            $excel3->setCellValue('E'.$a, $d);
            $excel3->setCellValue('F'.$a, $d);
            $excel3->setCellValue('G'.$a, $d);
            $excel3->setCellValue('H'.$a, $d);
            $excel3->setCellValue('I'.$a, $d);
            $excel3->setCellValue('J'.$a, $d);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel3->getStyle('A'.$a)->applyFromArray($style_row);
            $excel3->getStyle('B'.$a)->applyFromArray($style_row);
            $excel3->getStyle('C'.$a)->applyFromArray($style_row);
            $excel3->getStyle('D'.$a)->applyFromArray($style_row);
            $excel3->getStyle('E'.$a)->applyFromArray($style_row);
            $excel3->getStyle('F'.$a)->applyFromArray($style_row);
            $excel3->getStyle('G'.$a)->applyFromArray($style_row);
            $excel3->getStyle('H'.$a)->applyFromArray($style_row);
            $excel3->getStyle('I'.$a)->applyFromArray($style_row);
            $excel3->getStyle('J'.$a)->applyFromArray($style_row);
            $excel3->getStyle('K'.$a)->applyFromArray($style_row);

            $a = $a + 1;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');

        $filename = 'PenerimaanPembayaran.xlsx';
//        $objWriter->save('./document/'.$filename);
        $objWriter->save('php://output'); // jalan ketika tidak menggunakan ajax
    }

    public function export_excel_report_finance()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $input = (array) $this->jwt->decode($token,$key);
        $GetDateNow = date('Y-m-d');
        $this->load->model('master/m_master');
        $this->load->model('finance/m_finance');
        $GetDateNow = $this->m_master->getIndoBulan($GetDateNow);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/finance/Template_report_rev1.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();
        $excel3->setCellValue('A3', $GetDateNow.' Jam '.date('H:i'));

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // start dari A7
        $dataGenerate = $input['Data'];
        $summary = $input['summary'];
        $PostPassing = $input['PostPassing'];
        $a = 7;
        $sumTagihanAll = 0;
        $sumPembayaranAll = 0;
        $sumPiutangAll = 0;

        for ($i=0; $i < count($dataGenerate); $i++) {
            $no = $i + 1;
            $excel3->setCellValue('A'.$a, $dataGenerate[$i][0]);
            $excel3->setCellValue('B'.$a, $dataGenerate[$i][1]);
            $excel3->setCellValue('C'.$a, $dataGenerate[$i][2]);
            $excel3->setCellValue('D'.$a, $dataGenerate[$i][3]);
            $excel3->setCellValue('E'.$a, $dataGenerate[$i][4]);
            $excel3->setCellValue('F'.$a, $dataGenerate[$i][11]);
            $excel3->setCellValue('G'.$a, $dataGenerate[$i][5]);
            $excel3->setCellValue('H'.$a, $dataGenerate[$i][12]);
            $excel3->setCellValue('I'.$a, $dataGenerate[$i][13]);
            $excel3->setCellValue('J'.$a, $dataGenerate[$i][14]);
            $excel3->setCellValue('K'.$a, $dataGenerate[$i][15]);
            $excel3->setCellValue('L'.$a, $dataGenerate[$i][16]);
            $excel3->setCellValue('M'.$a, $dataGenerate[$i][6]);
            $excel3->setCellValue('N'.$a, $dataGenerate[$i][7]);
            $excel3->setCellValue('O'.$a, $dataGenerate[$i][8]);
            $excel3->setCellValue('P'.$a, $dataGenerate[$i][17]);


            // $ket = "adi\nresa";

            $excel3->setCellValue('Q'.$a, $dataGenerate[$i][9]);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel3->getStyle('A'.$a)->applyFromArray($style_row);
            $excel3->getStyle('B'.$a)->applyFromArray($style_row);
            $excel3->getStyle('C'.$a)->applyFromArray($style_row);
            $excel3->getStyle('D'.$a)->applyFromArray($style_row);
            $excel3->getStyle('E'.$a)->applyFromArray($style_row);
            $excel3->getStyle('F'.$a)->applyFromArray($style_row);
            $excel3->getStyle('G'.$a)->applyFromArray($style_row);
            $excel3->getStyle('H'.$a)->applyFromArray($style_row);
            $excel3->getStyle('I'.$a)->applyFromArray($style_row);
            $excel3->getStyle('J'.$a)->applyFromArray($style_row);
            $excel3->getStyle('K'.$a)->applyFromArray($style_row);
            $excel3->getStyle('L'.$a)->applyFromArray($style_row);
            $excel3->getStyle('M'.$a)->applyFromArray($style_row);
            $excel3->getStyle('N'.$a)->applyFromArray($style_row);
            $excel3->getStyle('O'.$a)->applyFromArray($style_row);
            $excel3->getStyle('P'.$a)->applyFromArray($style_row);
            $excel3->getStyle('P'.$a)->getAlignment()->setWrapText(true);
            $excel3->getStyle('Q'.$a)->applyFromArray($style_row);
            $excel3->getStyle('Q'.$a)->getAlignment()->setWrapText(true);

            // $excel3->getStyle('K'.$a)->applyFromArray($style_row);

            $a = $a + 1;
        }

        $excel3->mergeCells('A'.$a.':L'.$a); // Set Merge Cell pada kolom A1 sampai E1
        $setTA = $summary->taShow;
        $excel3->setCellValue('A'.$a, $setTA);
        $excel3->getStyle('A'.$a)->applyFromArray($style_row);
        $excel3->getStyle('B'.$a)->applyFromArray($style_row);
        $excel3->getStyle('C'.$a)->applyFromArray($style_row);
        $excel3->getStyle('D'.$a)->applyFromArray($style_row);
        $excel3->getStyle('E'.$a)->applyFromArray($style_row);
        $excel3->getStyle('F'.$a)->applyFromArray($style_row);
        $excel3->getStyle('G'.$a)->applyFromArray($style_row);
        $excel3->getStyle('H'.$a)->applyFromArray($style_row);
        $excel3->getStyle('I'.$a)->applyFromArray($style_row);
        $excel3->getStyle('J'.$a)->applyFromArray($style_row);
        $excel3->getStyle('K'.$a)->applyFromArray($style_row);
        $excel3->getStyle('L'.$a)->applyFromArray($style_row);
        // $excel3->getStyle('A'.$a)->applyFromArray($style_row);
        $excel3->setCellValue('M'.$a, $summary->sumTagihan);
        $excel3->setCellValue('N'.$a, $summary->sumPembayaran);
        $excel3->setCellValue('O'.$a, $summary->sumPiutang);
        $excel3->setCellValue('P'.$a, '');
        $excel3->setCellValue('Q'.$a, '');

        $excel3->getStyle('M'.$a)->applyFromArray($style_row);
        $excel3->getStyle('N'.$a)->applyFromArray($style_row);
        $excel3->getStyle('O'.$a)->applyFromArray($style_row);
        $excel3->getStyle('P'.$a)->applyFromArray($style_row);
        $excel3->getStyle('Q'.$a)->applyFromArray($style_row);

        $sumTagihanAll = $sumTagihanAll + $summary->sumTagihan;
        $sumPembayaranAll = $sumPembayaranAll +$summary->sumPembayaran;
        $sumPiutangAll  = $sumPiutangAll +$summary->sumPiutang;

        $a = $a + 1;
        // get all mahasiswa
        // per page 2 database
        if ($PostPassing->ta == '' && $PostPassing->NIM == '') {
            $sqlCount = 'show databases like "%ta_2%"';
            $queryCount=$this->db->query($sqlCount, array())->result_array();
            $bigData = array();
            foreach ($queryCount as $key) {
                foreach ($key as $keyB ) {
                    $bigData[] = $keyB;
                }

            }

            rsort($bigData);

            for ($zz=0; $zz < count($bigData); $zz++) {
                # code...

                $dbTA = explode('_', $bigData[$zz]);
                $dbTA = $dbTA[1];

                if($dbTA != $dataGenerate[0][10])
                {

                    $a = $a + 2;
                    $aa = $a + 1;
                    $dbPass = '0.'.$dbTA;
                    $data = $this->m_finance->get_report_pembayaran_mhs($dbPass,$PostPassing->prodi,$PostPassing->NIM,$PostPassing->Semester,$PostPassing->Status,1, 0);
                    if (count($data) > 0) {
                        // make header table
                        $excel3->mergeCells('A'.$a.':A'.$aa); // Set Merge Cell pada kolom A1 sampai E1
                        $excel3->mergeCells('B'.$a.':B'.$aa); // Set Merge Cell pada kolom A1 sampai E1
                        $excel3->mergeCells('C'.$a.':C'.$aa); // Set Merge Cell pada kolom A1 sampai E1
                        $excel3->mergeCells('E'.$a.':L'.$a); // Set Merge Cell pada kolom A1 sampai E1

                        $excel3->mergeCells('M'.$a.':M'.$aa); // Set Merge Cell pada kolom A1 sampai E1
                        $excel3->mergeCells('N'.$a.':N'.$aa); // Set Merge Cell pada kolom A1 sampai E1
                        $excel3->mergeCells('O'.$a.':O'.$aa); // Set Merge Cell pada kolom A1 sampai E1
                        $excel3->mergeCells('P'.$a.':P'.$aa); // Set Merge Cell pada kolom A1 sampai E1
                        $excel3->mergeCells('Q'.$a.':Q'.$aa); // Set Merge Cell pada kolom A1 sampai E1

                        $excel3->setCellValue('A'.$a, 'No');
                        $excel3->setCellValue('B'.$a, 'NAMA');
                        $excel3->setCellValue('C'.$a, 'NPM');
                        $excel3->setCellValue('D'.$a, 'JURUSAN');
                        $excel3->setCellValue('E'.$a, 'TAGIHAN');
                        $excel3->setCellValue('E'.$aa, 'BPP');
                        $excel3->setCellValue('F'.$aa, 'DueDate');
                        $excel3->setCellValue('G'.$aa, 'SKS');
                        $excel3->setCellValue('H'.$aa, 'DueDate');
                        $excel3->setCellValue('I'.$aa, 'SPP');
                        $excel3->setCellValue('J'.$aa, 'DueDate');
                        $excel3->setCellValue('K'.$aa, 'Lain-Lain');
                        $excel3->setCellValue('L'.$aa, 'DueDate');
                        $excel3->setCellValue('M'.$a, 'TOTAL TAGIHAN');
                        $excel3->setCellValue('N'.$a, 'TOTAL PEMBAYARAN');
                        $excel3->setCellValue('O'.$a, 'PIUTANG');
                        $excel3->setCellValue('P'.$a, 'Aging');
                        $excel3->setCellValue('Q'.$a, 'KETERANGAN');

                        $excel3->getStyle('A'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('A'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('B'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('B'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('C'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('C'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('D'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('D'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('E'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('E'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('F'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('F'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('G'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('G'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('H'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('H'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('I'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('I'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('J'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('J'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('K'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('K'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('L'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('L'.$a)->applyFromArray($style_row);    

                        $excel3->getStyle('M'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('M'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('N'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('N'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('O'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('O'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('P'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('P'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('P'.$a)->getAlignment()->setWrapText(true);
                        $excel3->getStyle('Q'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('Q'.$aa)->applyFromArray($style_row);
                        $excel3->getStyle('Q'.$a)->getAlignment()->setWrapText(true);

                        $a = $aa + 1;

                        // $bigData[] = $data;
                        $sumTagihan = 0;
                        $sumPembayaran = 0;
                        $sumPiutang = 0;
                        for ($z=0; $z < count($data); $z++) {
                            $no = $z+1;
                            $Total_tagihan = $data[$z]['BPP']  + $data[$z]['Cr'] +$data[$z]['SPP'] + $data[$z]['An'] ;
                            $sumTagihan = $sumTagihan + $Total_tagihan;
                            $Total_pembayaran = $data[$z]['PayBPP']  + $data[$z]['PayCr'] + $data[$z]['PayAn'] + $data[$z]['PaySPP'];
                            $sumPembayaran = $sumPembayaran + $Total_pembayaran;
                            $Piutang = $data[$z]['SisaCr']  + $data[$z]['SisaBPP'] + $data[$z]['SisaAn'] + $data[$z]['SisaSPP'];
                            $sumPiutang = $sumPiutang + $Piutang;
                            $ketEXcel = "";

                            if ($Piutang > 0) {
                                if($data[$z]['DetailPaymentBPP'] != '')
                                {
                                    $DetailPaymentBPP = $data[$z]['DetailPaymentBPP'];
                                    $keteranganBPPEX = "BPP\n";
                                    for ($l = 0; $l < count($DetailPaymentBPP); $l++) {
                                        $lno = $l + 1;
                                        $StatusPay = ($DetailPaymentBPP[$l]['Status'] == 1) ? 'Sudah Bayar' : 'Belum Bayar';
                                        if ($DetailPaymentBPP[$l]['Status'] == 0) {
                                            $keteranganBPPEX .= "Pembayaran : ".$lno." \n";
                                            $keteranganBPPEX .= "Deadline : ".$DetailPaymentBPP[$l]['Deadline']."\n";
                                            $keteranganBPPEX .= "Status : ".$StatusPay."\n";
                                        }

                                    }
                                    $keteranganBPPEX .= "\n";
                                }
                                else{
                                    $keteranganBPPEX = "Tagihan BPP belum diset\n";
                                }

                                if($data[$z]['DetailPaymentCr'] != '')
                                {
                                    $DetailPaymentCr = $data[$z]['DetailPaymentCr'];
                                    $keteranganCrEX = "Credit\n";
                                    for ($l = 0; $l < count($DetailPaymentCr); $l++) {
                                        $lno = $l + 1;
                                        $StatusPay = ($DetailPaymentCr[$l]['Status'] == 1)? 'Sudah Bayar' : 'Belum Bayar';
                                        if($DetailPaymentCr[$l]['Status'] == 0)
                                        {
                                            $keteranganCrEX .= "Pembayaran : ".$lno."\n";
                                            $keteranganCrEX .= "Deadline : ".$DetailPaymentCr[$l]['Deadline']."\n";
                                            $keteranganCrEX .= "Status : ".$StatusPay."\n";
                                        }

                                    }
                                    $keteranganCrEX .= "\n";

                                }
                                else
                                {
                                    $keteranganCrEX .= "Tagihan Credit belum diset\n";
                                }
                            }
                            else if($Piutang == 0 && ($data[$z]['DetailPaymentCr'] == '' || $data[$z]['DetailPaymentBPP'] == '') ) // belum diset
                            {
                                if ($data[$z]['DetailPaymentBPP'] == '') {
                                    $keteranganBPPEX = "Tagihan BPP belum diset\n";
                                }

                                if ($data[$z]['DetailPaymentCr'] == '') {
                                    $keteranganCrEX = "Tagihan Credit belum diset\n";
                                }

                            }
                            $ketEXcel = $keteranganBPPEX.$keteranganCrEX;
                            $Aging = $data[$z]['AgingBPP']."\n".$data[$z]['AgingCr']."\n".$data[$z]['AgingSPP']."\n".$data[$z]['AgingAn'];

                            $excel3->setCellValue('A'.$a, $no);
                            $excel3->setCellValue('B'.$a, $data[$z]['Name']);
                            $excel3->setCellValue('C'.$a, $data[$z]['NPM']);
                            $excel3->setCellValue('D'.$a, $data[$z]['ProdiENG']);
                            $excel3->setCellValue('E'.$a, $data[$z]['BPP']);
                            $excel3->setCellValue('F'.$a, $data[$z]['DueDateBPP']);
                            $excel3->setCellValue('G'.$a, $data[$z]['Cr']);
                            $excel3->setCellValue('H'.$a, $data[$z]['DueDateCR']);
                            $excel3->setCellValue('I'.$a, $data[$z]['SPP']);
                            $excel3->setCellValue('J'.$a, $data[$z]['DueDateSPP']);
                            $excel3->setCellValue('K'.$a, $data[$z]['An']);
                            $excel3->setCellValue('L'.$a, $data[$z]['DueDateAn']);

                            $excel3->setCellValue('M'.$a, $Total_tagihan);
                            $excel3->setCellValue('N'.$a, $Total_pembayaran);
                            $excel3->setCellValue('O'.$a, $Piutang);
                            $excel3->setCellValue('P'.$a, $Aging);

                            // $ket = "adi\nresa";

                            $excel3->setCellValue('Q'.$a, $ketEXcel);

                            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
                            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
                            $excel3->getStyle('A'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('B'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('C'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('D'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('E'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('F'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('G'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('H'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('I'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('J'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('K'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('L'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('M'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('N'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('O'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('P'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('P'.$a)->getAlignment()->setWrapText(true);
                            $excel3->getStyle('Q'.$a)->applyFromArray($style_row);
                            $excel3->getStyle('Q'.$a)->getAlignment()->setWrapText(true);
                            $a = $a + 1;

                        }

                        $taShow = "Total Tagihan Mahasiswa TA ".$data[0]['Year'];

                        $excel3->mergeCells('A'.$a.':L'.$a); // Set Merge Cell pada kolom A1 sampai E1
                        $setTA = $summary->taShow;
                        $excel3->setCellValue('A'.$a, $setTA);
                        $excel3->getStyle('A'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('B'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('C'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('D'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('E'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('F'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('G'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('H'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('I'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('J'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('K'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('L'.$a)->applyFromArray($style_row);
                        // $excel3->getStyle('A'.$a)->applyFromArray($style_row);
                        $excel3->setCellValue('M'.$a, $sumTagihan);
                        $excel3->setCellValue('N'.$a, $sumPembayaran);
                        $excel3->setCellValue('O'.$a, $sumPiutang);
                        $excel3->setCellValue('P'.$a, '');
                        $excel3->setCellValue('Q'.$a, '');

                        $excel3->getStyle('M'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('N'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('O'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('P'.$a)->applyFromArray($style_row);
                        $excel3->getStyle('Q'.$a)->applyFromArray($style_row);

                        $sumTagihanAll = $sumTagihanAll + $sumTagihan;
                        $sumPembayaranAll = $sumPembayaranAll +$sumPembayaran;
                        $sumPiutangAll  = $sumPiutangAll +$sumPiutang;

                        $a = $a + 1;
                    } // exit if data


                }


            }
        } // exit if not search

        // summary All
        $a = $a + 1;
        $taShow = "Summary All";

        $excel3->mergeCells('A'.$a.':L'.$a); // Set Merge Cell pada kolom A1 sampai E1
        $setTA = $taShow;
        $excel3->setCellValue('A'.$a, $setTA);
        $excel3->getStyle('A'.$a)->applyFromArray($style_row);
        $excel3->getStyle('B'.$a)->applyFromArray($style_row);
        $excel3->getStyle('C'.$a)->applyFromArray($style_row);
        $excel3->getStyle('D'.$a)->applyFromArray($style_row);
        $excel3->getStyle('E'.$a)->applyFromArray($style_row);
        $excel3->getStyle('F'.$a)->applyFromArray($style_row);
        $excel3->getStyle('G'.$a)->applyFromArray($style_row);
        $excel3->getStyle('H'.$a)->applyFromArray($style_row);
        $excel3->getStyle('I'.$a)->applyFromArray($style_row);
        $excel3->getStyle('J'.$a)->applyFromArray($style_row);
        $excel3->getStyle('K'.$a)->applyFromArray($style_row);
        $excel3->getStyle('L'.$a)->applyFromArray($style_row);
        // $excel3->getStyle('A'.$a)->applyFromArray($style_row);
        $excel3->setCellValue('M'.$a, $sumTagihanAll);
        $excel3->setCellValue('N'.$a, $sumPembayaranAll);
        $excel3->setCellValue('O'.$a, $sumPiutangAll);
        $excel3->setCellValue('P'.$a, '');
        $excel3->setCellValue('Q'.$a, '');

        $excel3->getStyle('M'.$a)->applyFromArray($style_row);
        $excel3->getStyle('N'.$a)->applyFromArray($style_row);
        $excel3->getStyle('O'.$a)->applyFromArray($style_row);
        $excel3->getStyle('P'.$a)->applyFromArray($style_row);
        $excel3->getStyle('Q'.$a)->applyFromArray($style_row);

        // print_r($bigData);
        // die();


        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        $aa = date('YmdHis');
        if ($PostPassing->ta == '' && $PostPassing->NIM == '') {
            $aa = 'All_'.$aa; 
        }

        if ($PostPassing->NIM != '') {
            $aa = 'NPM_'.$PostPassing->NIM.'_'.$aa; 
        }

        if ($PostPassing->ta  != '') {
            $aa = 'ta_'.$PostPassing->ta.'_'.$aa; 
        }

        $Filename = 'Report_Tagihan_Mhs_'.$aa.'.xlsx';
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        // It will be called file.xlss
        header('Content-Disposition: attachment; filename="'.$Filename.'"'); // jalan ketika tidak menggunakan ajax
        //$filename = 'PenerimaanPembayaran.xlsx';
        //$objWriter->save('./document/'.$filename);
        $objWriter->save('php://output'); // jalan ketika tidak menggunakan ajax

        // print_r($input['summary']);
    }

    public function export_excel_payment_received()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $input = (array) $this->jwt->decode($token,$key);
        // print_r($input);
        $Semester = $input['Semester'];
        $Semester = explode('.', $Semester);
        $Semester = $Semester[1];
        $data = $input['Data'];
        $this->load->model('finance/m_finance');
        $dataGenerate = $this->m_finance->GroupingNPM($data);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/finance/TemplatePembayaran.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();
        $excel3->setCellValue('A2', 'Rekap Penerimaan & AGING '.$Semester);

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // start dari A7
        $a = 8;
        for ($i=0; $i < count($dataGenerate); $i++) {
            $no = $i + 1;
            $excel3->setCellValue('A'.$a, $no);
            $excel3->setCellValue('B'.$a, $dataGenerate[$i]['Nama']);
            $excel3->setCellValue('C'.$a, $dataGenerate[$i]['NPM']);
            $excel3->setCellValue('D'.$a, $dataGenerate[$i]['ProdiEng']);
            $excel3->setCellValue('E'.$a, $dataGenerate[$i]['SPP']);
            $excel3->setCellValue('F'.$a, $dataGenerate[$i]['Another']);
            $excel3->setCellValue('G'.$a, $dataGenerate[$i]['BPP']);
            $excel3->setCellValue('H'.$a, $dataGenerate[$i]['BPPKet']);
            $excel3->setCellValue('I'.$a, $dataGenerate[$i]['Credit']);
            $excel3->setCellValue('J'.$a, $dataGenerate[$i]['CreditKet']);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel3->getStyle('A'.$a)->applyFromArray($style_row);
            $excel3->getStyle('B'.$a)->applyFromArray($style_row);
            $excel3->getStyle('C'.$a)->applyFromArray($style_row);
            $excel3->getStyle('D'.$a)->applyFromArray($style_row);
            $excel3->getStyle('E'.$a)->applyFromArray($style_row);
            $excel3->getStyle('F'.$a)->applyFromArray($style_row);
            $excel3->getStyle('G'.$a)->applyFromArray($style_row);
            $excel3->getStyle('H'.$a)->applyFromArray($style_row);
            $excel3->getStyle('I'.$a)->applyFromArray($style_row);
            $excel3->getStyle('J'.$a)->applyFromArray($style_row);
            $excel3->getStyle('K'.$a)->applyFromArray($style_row);

            $a = $a + 1;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        // It will be called file.xlss
        header('Content-Disposition: attachment; filename="file.xlsx"'); // jalan ketika tidak menggunakan ajax
        //$filename = 'PenerimaanPembayaran.xlsx';
        //$objWriter->save('./document/'.$filename);
        $objWriter->save('php://output'); // jalan ketika tidak menggunakan ajax

    }

    public function export_excel_budget_creator()
    {
        $this->load->model('master/m_master');
        $this->load->model('budgeting/m_budgeting');
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $Input = (array) $this->jwt->decode($token,$key);
        $getData = $this->m_budgeting->get_creator_budget($Input['id_creator_budget_approval']);
        $dt = $this->m_master->caribasedprimary('db_budgeting.creator_budget_approval','ID',$Input['id_creator_budget_approval']);
        $NameDepartement = $this->m_budgeting->SearchDepartementBudgeting($dt[0]['Departement']);
        $NameDepartement = $NameDepartement[0]['NameDepartement'];
        $Year = $dt[0]['Year'];
        $YearWr = $dt[0]['Year'].'/'.($dt[0]['Year'] + 1);
        $YearWr2 = ($dt[0]['Year'] - 1).'/'.$dt[0]['Year'];
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes

        // Panggil class PHPExcel nya
        $excel = new PHPExcel();
        // Settingan awal fil excel
        $excel->getProperties()->setCreator('Alhadi Rahman')
            ->setLastModifiedBy('Alhadi Rahman')
            ->setTitle("Podomoro University Budgeting")
            ->setSubject("Budgeting ".$NameDepartement)
            ->setDescription("Budgeting ".$NameDepartement)
            ->setKeywords("Budgeting ".$NameDepartement);

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('D1', "Anggaran Program Studi Tahun Akademik ".$YearWr.' Universitas Agung Podomoro');
        $excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('D1')->getFont()->setSize(15);
        // $excel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        // $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        $excel->setActiveSheetIndex(0)->setCellValue('B3', 'Nama Bagian');
        $excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(15);

        $excel->setActiveSheetIndex(0)->setCellValue('G3', ':');
        $excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('G3')->getFont()->setSize(15);

        $excel->setActiveSheetIndex(0)->setCellValue('H3', $NameDepartement);
        $excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('H3')->getFont()->setSize(15);

        // get Month
        $month = $getData[0]['DetailMonth'];
        $month = json_decode($month);

        // make header
            $excel->setActiveSheetIndex(0)->setCellValue('B6', "No");
            $excel->getActiveSheet()->mergeCells('B6:C7');
            $excel->getActiveSheet()->getStyle('B6:C7')->applyFromArray($style_col);
            
            $excel->setActiveSheetIndex(0)->setCellValue('D6', "POS ANGGARAN");
            $excel->getActiveSheet()->mergeCells('D6:D7');
            $excel->getActiveSheet()->getStyle('D6:D7')->applyFromArray($style_col);

            $excel->setActiveSheetIndex(0)->setCellValue('E6', "HARGA");
            $excel->getActiveSheet()->getStyle('E6')->applyFromArray($style_col);
            $excel->setActiveSheetIndex(0)->setCellValue('E7', "(,000)");
            $excel->getActiveSheet()->getStyle('E7')->applyFromArray($style_col);

            $excel->setActiveSheetIndex(0)->setCellValue('F6', "QTY");
            $excel->getActiveSheet()->mergeCells('F6:F7');
            $excel->getActiveSheet()->getStyle('F6:F7')->applyFromArray($style_col);

            $excel->setActiveSheetIndex(0)->setCellValue('G6', "UNIT");
            $excel->getActiveSheet()->mergeCells('G6:G7');
            $excel->getActiveSheet()->getStyle('G6:G7')->applyFromArray($style_col);

            $excel->setActiveSheetIndex(0)->setCellValue('H6', "TOTAL");
            $excel->getActiveSheet()->mergeCells('H6:H7');
            $excel->getActiveSheet()->getStyle('H6:H7')->applyFromArray($style_col);

            // Month
                $keyM = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                $St = 6;
                $excel->setActiveSheetIndex(0)->setCellValue('J'.$St, "ESTIMASI  PER-BULAN");
                $StH = 9 + (count($month) - 1) ; // dimulai dari I

                $excel->getActiveSheet()->mergeCells('J'.$St.':'.$keyM[$StH].$St);
                $excel->getActiveSheet()->getStyle('J'.$St.':'.$keyM[$StH].$St)->applyFromArray($style_col);
                $St++;
                $StH = 9;
                for ($i=0; $i < count($month); $i++) {
                    $a = $month[$i]->month;
                    $a = explode('-', $a);
                    $NameBulan = $this->m_master->BulanInggris($a[1]);
                    $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $NameBulan);
                    $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_col);
                    $StH = $StH + 1;
                }


        // make isian
           $St++;
           $No = 0;
           // Grouping per by Head Account
            $total = 0;
                for ($i=0; $i < count($getData); $i++) {
                    // No
                    $No++;
                    $NoSub = 0;
                    $StH = 1;
                    $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, ($No) );
                    $excel->getActiveSheet()->mergeCells($keyM[$StH].$St.':'.$keyM[($StH + 1)].$St);
                    $excel->getActiveSheet()->getStyle($keyM[$StH].$St.':'.$keyM[($StH + 1)].$St)->applyFromArray($style_col);

                    // Name Head Account
                    $StH = $StH + 2;
                    $CodeHeadAccount1 = $getData[$i]['CodeHeadAccount'];
                    $NameHeadAccount = $getData[$i]['NameHeadAccount'];
                    $excel->setActiveSheetIndex(0)->setCellValue($keyM[($StH)].$St, $NameHeadAccount );
                    $excel->getActiveSheet()->getStyle($keyM[($StH)].$St)->applyFromArray($style_col);
                    // buat border sampai total
                    for ($j=0; $j < 4; $j++) { 
                       $StH++;
                       $excel->getActiveSheet()->getStyle($keyM[($StH)].$St)->applyFromArray($style_col);
                    } 

                    // month 
                    $month = $getData[$i]['DetailMonth'];
                    $month = json_decode($month);
                        $StH = $StH + 2;
                        for ($z=0; $z < count($month); $z++) {
                            $a = '-';
                            $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $a);
                            $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);
                            $StH = $StH + 1;
                        }



                    // set coloumn
                        $st_arr1 = array(
                            'borders' => array(
                                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                            )
                        );

                        $st_arr2 = array(
                            'borders' => array(
                                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                                // 'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                            )
                        );

                    // tulis data pertama
                       $StH = 2;
                       $St++;
                       $No2 = strtolower($keyM[$NoSub]);
                       $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, ($No2) );
                       $excel->getActiveSheet()->getStyle($keyM[($StH -1) ].$St)->applyFromArray($st_arr1);
                       $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($st_arr2);

                       $StH++;
                       $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $getData[$i]['RealisasiPostName']);
                       $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                       $StH++;
                       $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $getData[$i]['UnitCost'] / 1000);
                       $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                       $StH++;
                       $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $getData[$i]['Freq']);
                       $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row); 

                       $StH++;
                       $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                       $StH++;
                       $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $getData[$i]['SubTotal'] / 1000);
                       $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                       $total = $total + ($getData[$i]['SubTotal'] / 1000);

                    // month   
                       $StH = $StH + 2;
                       for ($z=0; $z < count($month); $z++) {
                           $a = $month[$z]->value * ($getData[$i]['UnitCost'] / 1000);
                           $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $a);
                           $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);
                           $StH = $StH + 1;
                       }

                    // Grpuping
                    for ($j=$i+1; $j < count($getData); $j++) { 
                       $CodeHeadAccount2 = $getData[$j]['CodeHeadAccount'];
                        if ($CodeHeadAccount1 == $CodeHeadAccount2) {
                            $NoSub++;
                            $StH = 2;
                            $St++;
                            $No2 = strtolower($keyM[$NoSub]);
                            // month 
                            $month = $getData[$j]['DetailMonth'];
                            $month = json_decode($month);

                               $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, ($No2) );
                               $excel->getActiveSheet()->getStyle($keyM[($StH -1) ].$St)->applyFromArray($st_arr1);
                               $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($st_arr2);

                               $StH++;
                               $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $getData[$j]['RealisasiPostName']);
                               $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                               $StH++;
                               $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $getData[$j]['UnitCost'] / 1000);
                               $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                               $StH++;
                               $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $getData[$j]['Freq']);
                               $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row); 

                               $StH++;
                               $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                               $StH++;
                               $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $getData[$j]['SubTotal'] / 1000);
                               $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                                $total = $total + ($getData[$j]['SubTotal'] / 1000);

                            // month   
                               $StH = $StH + 2;
                               for ($z=0; $z < count($month); $z++) {
                                   $a = $month[$z]->value * ($getData[$j]['UnitCost'] / 1000);
                                   $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $a);
                                   $excel->getActiveSheet()->getStyle($keyM[$StH].$St)->applyFromArray($style_row);
                                   $StH = $StH + 1;
                               }
                        }
                        else
                        {
                            break;
                        }
                        $i = $j; 
                    }

                    $St++;
                }

            //make total
                $StH = 2;
                $excel->setActiveSheetIndex(0)->setCellValue('B'.$St, "TOTAL");
                $excel->getActiveSheet()->mergeCells('B'.$St.':'.'G'.$St);
                $excel->getActiveSheet()->getStyle('B'.$St.':'.'G'.$St)->applyFromArray($style_col);

                $excel->setActiveSheetIndex(0)->setCellValue('H'.$St, $total); 
                $excel->getActiveSheet()->getStyle('H'.$St)->applyFromArray($style_col);

            // Make Footer
                $St = $St + 2;
                $StTot = $St + 2;
                $excel->setActiveSheetIndex(0)->setCellValue('D'.$St, "Anggaran TA ".$YearWr2);
                $excel->setActiveSheetIndex(0)->setCellValue('D'.($St+1), "Anggaran TA ".$YearWr);
                $excel->setActiveSheetIndex(0)->setCellValue('D'.$StTot, "Presentasi Deviasi");
                $excel->getActiveSheet()->getStyle('D'.$St.':'.'H'.$StTot)->applyFromArray($style_row);

            // Write Rule Approval
               $St = $StTot + 2;     
               $excel->setActiveSheetIndex(0)->setCellValue('D'.$St, "Jakarta,");

               $St = $St + 2;
               $StTot = $St + 6;
               $StH = 1;
               $StH2 = 3;

               $JsonStatus = (array) json_decode($dt[0]['JsonStatus'],true);
               for ($i=0; $i < count($JsonStatus); $i++) { 
                   $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$St, $JsonStatus[$i]['NameTypeDesc']);
                   $N = $this->m_master->caribasedprimary('db_employees.employees','NIP',$JsonStatus[$i]['NIP']);
                   $excel->setActiveSheetIndex(0)->setCellValue($keyM[$StH].$StTot, $N[0]['Name']);
                   $excel->getActiveSheet()->getStyle($keyM[$StH].$St.':'.$keyM[$StH2].$StTot)->applyFromArray($style_row);
                   $StH = $StH2 + 2; 
                   $StH2 = $StH + 2; 
               }

         $excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);       
         $excel->getActiveSheet()->getColumnDimension('C')->setWidth(3);       
         $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);       
         $excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);       
         $excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);       

        // Set judul file excel nya
        $excel->getActiveSheet()->setTitle($NameDepartement);
        $excel->setActiveSheetIndex(0);


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=PodomoroUniversityBudgeting.xlsx'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    public function export_excel_budget_creator_all()
    {
        $this->load->model('master/m_master');
        $this->load->model('budgeting/m_budgeting');
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $Input = (array) $this->jwt->decode($token,$key);
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes

        // Panggil class PHPExcel nya
        $excel2 = new PHPExcel();
        // Settingan awal fil excel
        $excel2->getProperties()->setCreator('Alhadi Rahman')
            ->setLastModifiedBy('Alhadi Rahman')
            ->setTitle("Podomoro University Budgeting")
            ->setSubject("Budgeting ".'All')
            ->setDescription("Budgeting ".'All')
            ->setKeywords("Budgeting ".'All');

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $dt = $this->m_budgeting->get_data_ListBudgetingDepartement($Input['Year']);
        $sql = '';
        for ($m=0; $m < count($dt); $m++) { 
            $incsheet = $m;
            $excel = $excel2->createSheet($incsheet); 
            $NameDepartement = substr($dt[$m]['NameDepartement'], 0,20) ;
            $excel->setTitle($NameDepartement);
            $getData = $this->m_budgeting->get_creator_budget($dt[$m]['ID_creator_budget']);

            $Year = $Input['Year'];
            $YearWr = $Input['Year'].'/'.($Input['Year'] + 1);
            $YearWr2 = ($Input['Year'] - 1).'/'.$Input['Year'];

            $excel->setCellValue('D1', "Anggaran Program Studi Tahun Akademik ".$YearWr.' Universitas Agung Podomoro');
            $excel->getStyle('D1')->getFont()->setBold(TRUE);
            $excel->getStyle('D1')->getFont()->setSize(15);
            // $excel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
            $excel->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
            // $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

            $excel->setCellValue('B3', 'Nama Bagian');
            $excel->getStyle('B3')->getFont()->setBold(TRUE);
            $excel->getStyle('B3')->getFont()->setSize(15);

            $excel->setCellValue('G3', ':');
            $excel->getStyle('G3')->getFont()->setBold(TRUE);
            $excel->getStyle('G3')->getFont()->setSize(15);

            $excel->setCellValue('H3', $NameDepartement);
            $excel->getStyle('H3')->getFont()->setBold(TRUE);
            $excel->getStyle('H3')->getFont()->setSize(15);

            // get Month
            $month = array();
            if (count($getData) > 0) {
                $month = $getData[0]['DetailMonth'];
                $month = json_decode($month);

                // make header
                    $excel->setCellValue('B6', "No");
                    $excel->mergeCells('B6:C7');
                    $excel->getStyle('B6:C7')->applyFromArray($style_col);
                    
                    $excel->setCellValue('D6', "POS ANGGARAN");
                    $excel->mergeCells('D6:D7');
                    $excel->getStyle('D6:D7')->applyFromArray($style_col);

                    $excel->setCellValue('E6', "HARGA");
                    $excel->getStyle('E6')->applyFromArray($style_col);
                    $excel->setCellValue('E7', "(,000)");
                    $excel->getStyle('E7')->applyFromArray($style_col);

                    $excel->setCellValue('F6', "QTY");
                    $excel->mergeCells('F6:F7');
                    $excel->getStyle('F6:F7')->applyFromArray($style_col);

                    $excel->setCellValue('G6', "UNIT");
                    $excel->mergeCells('G6:G7');
                    $excel->getStyle('G6:G7')->applyFromArray($style_col);

                    $excel->setCellValue('H6', "TOTAL");
                    $excel->mergeCells('H6:H7');
                    $excel->getStyle('H6:H7')->applyFromArray($style_col);

                    // Month
                        $keyM = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                        $St = 6;
                        $excel->setCellValue('J'.$St, "ESTIMASI  PER-BULAN");
                        $StH = 9 + (count($month) - 1) ; // dimulai dari I

                        $excel->mergeCells('J'.$St.':'.$keyM[$StH].$St);
                        $excel->getStyle('J'.$St.':'.$keyM[$StH].$St)->applyFromArray($style_col);
                        $St++;
                        $StH = 9;
                        for ($i=0; $i < count($month); $i++) {
                            $a = $month[$i]->month;
                            $a = explode('-', $a);
                            $NameBulan = $this->m_master->BulanInggris($a[1]);
                            $excel->setCellValue($keyM[$StH].$St, $NameBulan);
                            $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_col);
                            $StH = $StH + 1;
                        }


                // make isian
                   $St++;
                   $No = 0;
                   // Grouping per by Head Account
                    $total = 0;
                        for ($i=0; $i < count($getData); $i++) {
                            // No
                            $No++;
                            $NoSub = 0;
                            $StH = 1;
                            $excel->setCellValue($keyM[$StH].$St, ($No) );
                            $excel->mergeCells($keyM[$StH].$St.':'.$keyM[($StH + 1)].$St);
                            $excel->getStyle($keyM[$StH].$St.':'.$keyM[($StH + 1)].$St)->applyFromArray($style_col);

                            // Name Head Account
                            $StH = $StH + 2;
                            $CodeHeadAccount1 = $getData[$i]['CodeHeadAccount'];
                            $NameHeadAccount = $getData[$i]['NameHeadAccount'];
                            $excel->setCellValue($keyM[($StH)].$St, $NameHeadAccount );
                            $excel->getStyle($keyM[($StH)].$St)->applyFromArray($style_col);
                            // buat border sampai total
                            for ($j=0; $j < 4; $j++) { 
                               $StH++;
                               $excel->getStyle($keyM[($StH)].$St)->applyFromArray($style_col);
                            } 

                            // month 
                            $month = $getData[$i]['DetailMonth'];
                            $month = json_decode($month);
                                $StH = $StH + 2;
                                for ($z=0; $z < count($month); $z++) {
                                    $a = '-';
                                    $excel->setCellValue($keyM[$StH].$St, $a);
                                    $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);
                                    $StH = $StH + 1;
                                }



                            // set coloumn
                                $st_arr1 = array(
                                    'borders' => array(
                                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                                        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                                        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                                    )
                                );

                                $st_arr2 = array(
                                    'borders' => array(
                                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                                        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                                        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                                        // 'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                                    )
                                );

                            // tulis data pertama
                               $StH = 2;
                               $St++;
                               $No2 = strtolower($keyM[$NoSub]);
                               $excel->setCellValue($keyM[$StH].$St, ($No2) );
                               $excel->getStyle($keyM[($StH -1) ].$St)->applyFromArray($st_arr1);
                               $excel->getStyle($keyM[$StH].$St)->applyFromArray($st_arr2);

                               $StH++;
                               $excel->setCellValue($keyM[$StH].$St, $getData[$i]['RealisasiPostName']);
                               $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                               $StH++;
                               $excel->setCellValue($keyM[$StH].$St, $getData[$i]['UnitCost'] / 1000);
                               $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                               $StH++;
                               $excel->setCellValue($keyM[$StH].$St, $getData[$i]['Freq']);
                               $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row); 

                               $StH++;
                               $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                               $StH++;
                               $excel->setCellValue($keyM[$StH].$St, $getData[$i]['SubTotal'] / 1000);
                               $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                               $total = $total + ($getData[$i]['SubTotal'] / 1000);

                            // month   
                               $StH = $StH + 2;
                               for ($z=0; $z < count($month); $z++) {
                                   $a = $month[$z]->value * ($getData[$i]['UnitCost'] / 1000);
                                   $excel->setCellValue($keyM[$StH].$St, $a);
                                   $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);
                                   $StH = $StH + 1;
                               }

                            // Grpuping
                            for ($j=$i+1; $j < count($getData); $j++) { 
                               $CodeHeadAccount2 = $getData[$j]['CodeHeadAccount'];
                                if ($CodeHeadAccount1 == $CodeHeadAccount2) {
                                    $NoSub++;
                                    $StH = 2;
                                    $St++;
                                    $No2 = strtolower($keyM[$NoSub]);
                                    // month 
                                    $month = $getData[$j]['DetailMonth'];
                                    $month = json_decode($month);

                                       $excel->setCellValue($keyM[$StH].$St, ($No2) );
                                       $excel->getStyle($keyM[($StH -1) ].$St)->applyFromArray($st_arr1);
                                       $excel->getStyle($keyM[$StH].$St)->applyFromArray($st_arr2);

                                       $StH++;
                                       $excel->setCellValue($keyM[$StH].$St, $getData[$j]['RealisasiPostName']);
                                       $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                                       $StH++;
                                       $excel->setCellValue($keyM[$StH].$St, $getData[$j]['UnitCost'] / 1000);
                                       $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                                       $StH++;
                                       $excel->setCellValue($keyM[$StH].$St, $getData[$j]['Freq']);
                                       $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row); 

                                       $StH++;
                                       $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                                       $StH++;
                                       $excel->setCellValue($keyM[$StH].$St, $getData[$j]['SubTotal'] / 1000);
                                       $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);

                                        $total = $total + ($getData[$j]['SubTotal'] / 1000);

                                    // month   
                                       $StH = $StH + 2;
                                       for ($z=0; $z < count($month); $z++) {
                                           $a = $month[$z]->value * ($getData[$j]['UnitCost'] / 1000);
                                           $excel->setCellValue($keyM[$StH].$St, $a);
                                           $excel->getStyle($keyM[$StH].$St)->applyFromArray($style_row);
                                           $StH = $StH + 1;
                                       }
                                }
                                else
                                {
                                    break;
                                }
                                $i = $j; 
                            }

                            $St++;
                        }

                    //make total
                        $StH = 2;
                        $excel->setCellValue('B'.$St, "TOTAL");
                        $excel->mergeCells('B'.$St.':'.'G'.$St);
                        $excel->getStyle('B'.$St.':'.'G'.$St)->applyFromArray($style_col);

                        $excel->setCellValue('H'.$St, $total); 
                        $excel->getStyle('H'.$St)->applyFromArray($style_col);

                    // Make Footer
                        $St = $St + 2;
                        $StTot = $St + 2;
                        $excel->setCellValue('D'.$St, "Anggaran TA ".$YearWr2);
                        $excel->setCellValue('D'.($St+1), "Anggaran TA ".$YearWr);
                        $excel->setCellValue('D'.$StTot, "Presentasi Deviasi");
                        $excel->getStyle('D'.$St.':'.'H'.$StTot)->applyFromArray($style_row);

                    // Write Rule Approval
                       $St = $StTot + 2;     
                       $excel->setCellValue('D'.$St, "Jakarta,");

                       $St = $St + 2;
                       $StTot = $St + 6;
                       $StH = 1;
                       $StH2 = 3;

                       $JsonStatus = (array) json_decode($dt[0]['JsonStatus'],true);
                       for ($i=0; $i < count($JsonStatus); $i++) { 
                           $excel->setCellValue($keyM[$StH].$St, $JsonStatus[$i]['NameTypeDesc']);
                           $N = $this->m_master->caribasedprimary('db_employees.employees','NIP',$JsonStatus[$i]['NIP']);
                           $excel->setCellValue($keyM[$StH].$StTot, $N[0]['Name']);
                           $excel->getStyle($keyM[$StH].$St.':'.$keyM[$StH2].$StTot)->applyFromArray($style_row);
                           $StH = $StH2 + 2; 
                           $StH2 = $StH + 2; 
                       }

                 $excel->getColumnDimension('B')->setWidth(3);       
                 $excel->getColumnDimension('C')->setWidth(3);       
                 $excel->getColumnDimension('D')->setWidth(20);       
                 $excel->getColumnDimension('E')->setWidth(25);       
                 $excel->getColumnDimension('H')->setWidth(25);    
            }

        } // end loop per sheet

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=PodomoroUniversityBudgeting.xlsx'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        $write->save('php://output');
        
    }

    public function monitoring_score()
    {

        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        $dataM = $this->m_save_to_excel->getMonitoringScore($data_arr);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes

        // Panggil class PHPExcel nya
        $excel = new PHPExcel();

        $pr = 'SCORE RECAP ACADEMIC YEAR '.$data_arr['Year'];

        // Settingan awal fil excel
        $excel->getProperties()->setCreator('IT PU')
            ->setLastModifiedBy('IT PU')
            ->setTitle($pr)
            ->setSubject($pr)
            ->setDescription($pr)
            ->setKeywords($pr);

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );


        $style_col_fill = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', $pr); // Set kolom A1 dengan tulisan "DATA KARYAWAN"
        $excel->getActiveSheet()->mergeCells('A1:O1'); // Set Merge Cell pada kolom A1 sampai O1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NIM"); // Set kolom A3 dengan tulisan "NIK"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Nama");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Prodi");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Code");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Course");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Group");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Coordinator");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Assignment 1");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Assignment 2");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Assignment 3");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Assignment 4");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "Assignment 5");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "UTS");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "UAS");
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Score");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "Grade");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O3')->applyFromArray($style_col);

        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

        if(count($dataM)>0){
            for($i=0;$i<count($dataM);$i++){
                $d = $dataM[$i];
                $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $d['NPM']);
                $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $d['Name']);
                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $d['ProdiName']);
                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $d['MKCode']);
                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $d['MKNameEng']);
                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $d['ClassGroup']);
                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $d['CoordinatorName']);
                $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $d['Evaluasi1']);
                $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $d['Evaluasi2']);
                $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $d['Evaluasi3']);
                $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $d['Evaluasi4']);
                $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $d['Evaluasi5']);
                $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $d['UTS']);
                $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $d['UAS']);
                $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $d['Score']);
                $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $d['Grade']);

                // Apply style header yang telah kita buat tadi ke masing-masing kolom header
                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_col_fill);

                $numrow += 1;
            }
        }





        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Rekap Data Karyawan");
        $excel->setActiveSheetIndex(0);

        foreach(range('A','Z') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        // Proses file excel
        $filename = str_replace(' ','_',$pr).".xlsx";
        //$FILEpath = "./dokument/".$filename;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.$filename); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
//            $write->save($FILEpath);

        //echo json_encode(array('file' => $filename));

        // exit else ajax
    }

    public function export_PenjualanFormulirData()
    {
        $this->load->model('admission/m_admission');
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $Input = (array) $this->jwt->decode($token,$key);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        switch ($Input['cf']) {
            case 0: // date range
                $dateRange1 = $Input['dateRange1'];
                $dateRange2 = $Input['dateRange2'];
                $SelectSetTa = $Input['SelectSetTa'];
                $SelectSortBy = $Input['SelectSortBy'];
                $get = $this->m_admission->getSaleFormulirOfflineBetwwen($dateRange1,$dateRange2,$SelectSetTa,$SelectSortBy);
                $title = 'Tanggal '.date('d M Y', strtotime($dateRange1)).' - '.date('d M Y', strtotime($dateRange2));
                $this->exCel_PenjualanFormulirData($title,$get);
                break;
            case 1: // by Month
               $SelectMonth = $Input['SelectMonth'];
               $SelectYear = $Input['SelectYear'];
               $SelectSetTa = $Input['SelectSetTa'];
               $SelectSortBy = $Input['SelectSortBy'];
               $get = $this->m_admission->getSaleFormulirOfflinePerMonth($SelectMonth,$SelectYear,$SelectSetTa,$SelectSortBy);
               $title = 'Bulan '.date('F Y', strtotime($SelectYear.'-'.$SelectMonth.'-01'));
               $this->exCel_PenjualanFormulirData($title,$get); 
                break;
            default:
                # code...
                break;
        }
    }

    private function exCel_PenjualanFormulirData($title,$data)
    {
        // print_r($data);die();
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel;
        $sheet = $objPHPExcel->getActiveSheet();
        $count = 5;
        $phone = PHPExcel_Cell_DataType::TYPE_STRING;
        
        $sheet->setCellValue('A1', 'Laporan Penjualan Formulir');
        $sheet->setCellValue('A2', $title);
        
        $sheet->setCellValue('A4', 'Form');
        $sheet->setCellValue('B4', 'Tanggal');
        $sheet->setCellValue('C4', 'PIC');
        $sheet->setCellValue('D4', 'Nama');
        $sheet->setCellValue('E4', 'Gender');
        $sheet->setCellValue('F4', 'Jurusan 1');
        $sheet->setCellValue('G4', 'Jurusan 2');
        $sheet->setCellValue('H4', 'Phone Home');
        $sheet->setCellValue('I4', 'Phone Mobile');
        $sheet->setCellValue('J4', 'Email');
        $sheet->setCellValue('K4', 'Sekolah');
        $sheet->setCellValue('L4', 'Kota Sekolah');
        $sheet->setCellValue('M4', 'Sumber Iklan');

        for ($i=0; $i < count($data); $i++) { 
            $sheet->setCellValue('A'.$count, ($data[$i]['No_Ref'] == "" || $data[$i]['No_Ref'] == null ) ? $data[$i]['FormulirCode'] : $data[$i]['No_Ref'] );
            $sheet->setCellValue('B'.$count, date('d M Y', strtotime( $data[$i]['DateSale'] ) ) );
            $sheet->setCellValue('C'.$count, $data[$i]['Sales']);
            $sheet->setCellValue('D'.$count, $data[$i]['FullName']);
            $sheet->setCellValue('E'.$count, ($data[$i]['Gender'] == "P") ? 'Perempuan' : 'Laki-Laki'  );
            $sheet->setCellValue('F'.$count, $data[$i]['NameProdi1']);
            $sheet->setCellValue('G'.$count, $data[$i]['NameProdi2']);
            $sheet->setCellValueExplicit('H'.$count, $data[$i]['HomeNumber'], $phone);
            $sheet->setCellValueExplicit('I'.$count, $data[$i]['PhoneNumber'], $phone);
            $sheet->setCellValue('J'.$count, $data[$i]['Email']);
            $sheet->setCellValue('K'.$count, $data[$i]['SchoolNameFormulir'].' '.$data[$i]['DistrictNameFormulir']);
            $sheet->setCellValue('L'.$count, $data[$i]['CityNameFormulir']);
            $sheet->setCellValue('M'.$count, $data[$i]['src_name']);
            $count++;
        }
        
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:M1');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:M2');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle('A2')->getFont()->setSize(12);
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A4:M4')->getFont()->setBold(true);
        
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:M4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:M'.$count)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A4:M4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ABCAFF');
        
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);

        $sheet->setTitle('Penjualan Form');
        $objPHPExcel->setActiveSheetIndex(0);
        $filename = 'report_penjualan_data_'.date('y-m-d').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $write->save('php://output');
    }

    public function export_PenjualanFormulirFinance()
    {
        $this->load->model('admission/m_admission');
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $Input = (array) $this->jwt->decode($token,$key);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        switch ($Input['cf']) {
            case 0: // date range
                $dateRange1 = $Input['dateRange1'];
                $dateRange2 = $Input['dateRange2'];
                $SelectSetTa = $Input['SelectSetTa'];
                $SelectSortBy = $Input['SelectSortBy'];
                $get = $this->m_admission->getSaleFormulirOfflineBetwwen($dateRange1,$dateRange2,$SelectSetTa,$SelectSortBy);
                $title = 'Tanggal '.date('d M Y', strtotime($dateRange1)).' - '.date('d M Y', strtotime($dateRange2));
                $this->exCel_PenjualanFormulirFinance($title,$get);
                break;
            case 1: // by Month
               $SelectMonth = $Input['SelectMonth'];
               $SelectYear = $Input['SelectYear'];
               $SelectSetTa = $Input['SelectSetTa'];
               $SelectSortBy = $Input['SelectSortBy'];
               $get = $this->m_admission->getSaleFormulirOfflinePerMonth($SelectMonth,$SelectYear,$SelectSetTa,$SelectSortBy);
               $title = 'Bulan '.date('F Y', strtotime($SelectYear.'-'.$SelectMonth.'-01'));
               $this->exCel_PenjualanFormulirFinance($title,$get); 
                break;
            case 3: // dashboard / per ta
               $SelectSetTa = $Input['SelectSetTa'];
               $get = $this->m_admission->getSaleFormulirOfflinePerTA($SelectSetTa);
               $title = 'Angkatan : '.$SelectSetTa;
               $this->exCel_PenjualanFormulirFinance($title,$get); 
                break;    
            default:
                # code...
                break;
        }
    }

    public function v_Finance_export_PenjualanFormulir()
    {
        $this->load->model('finance/m_finance');
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $Input = (array) $this->jwt->decode($token,$key);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        $title1 = 'Form Pendaftaran TA '.$Input['SelectSetTa'].'/'.($Input['SelectSetTa']+1);
        $title2 = 'Per Tanggal '.date('d M Y', strtotime(date('Y-m-d')));
        $SelectSetTa = $Input['SelectSetTa'];
        $SelectSortBy = $Input['SelectSortBy'];
        switch ($Input['cf']) {
            case 0: // date range
                $dateRange1 = $Input['dateRange1'];
                $dateRange2 = $Input['dateRange2'];
                $SelectSetTa = $Input['SelectSetTa'];
                $SelectSortBy = $Input['SelectSortBy'];
                // $get = $this->m_admission->getSaleFormulirOfflineBetwwen($dateRange1,$dateRange2,$SelectSetTa,$SelectSortBy);
                $title = 'Search by : Tanggal '.date('d M Y', strtotime($dateRange1)).' - '.date('d M Y', strtotime($dateRange2));
                // $this->exCel_PenjualanFormulirData($title,$get);
                // $get = $this->m_finance->getSaleFormulirOffline($SelectSetTa,$SelectSortBy);
                $get = $this->m_finance->getSaleFormulirOfflineBetwwen_fin($SelectSetTa,$SelectSortBy,$dateRange1,$dateRange2);
                $this->exCel_v_Finance_export_PenjualanFormulirFinance($title1,$title2,$get,$title);
                break;
            case 1: // by Month
               $SelectMonth = $Input['SelectMonth'];
               $SelectYear = $Input['SelectYear'];
               $SelectSetTa = $Input['SelectSetTa'];
               $SelectSortBy = $Input['SelectSortBy'];
               // $get = $this->m_admission->getSaleFormulirOfflinePerMonth($SelectMonth,$SelectYear,$SelectSetTa,$SelectSortBy);
               $title = 'Search by : Bulan '.date('F Y', strtotime($SelectYear.'-'.$SelectMonth.'-01'));
               // $this->exCel_PenjualanFormulirData($title,$get); 
               $get = $this->m_finance->getSaleFormulirOfflineMonth_fin($SelectSetTa,$SelectSortBy,$SelectMonth,$SelectYear);
               $this->exCel_v_Finance_export_PenjualanFormulirFinance($title1,$title2,$get,$title);
                break;
            case 2: // All
               $SelectSetTa = $Input['SelectSetTa'];
               $SelectSortBy = $Input['SelectSortBy'];
               // $get = $this->m_admission->getSaleFormulirOfflinePerMonth($SelectMonth,$SelectYear,$SelectSetTa,$SelectSortBy);
               $title = 'Search by : All ';
               // $this->exCel_PenjualanFormulirData($title,$get); 
               $get = $this->m_finance->getSaleFormulirOfflineAll_fin($SelectSetTa,$SelectSortBy);
               $this->exCel_v_Finance_export_PenjualanFormulirFinance($title1,$title2,$get,$title);
                break;    
            default:
                # code...
                break;
        }
    }

    private function exCel_v_Finance_export_PenjualanFormulirFinance($title1 = '',$title2 = '',$get= array(),$title)
    {
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel;
        $sheet = $objPHPExcel->getActiveSheet();
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/finance/TemplateRekapFormulir.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        $excel3->setCellValue('B1', $title1);
        $excel3->setCellValue('B2', $title2);
        $excel3->setCellValue('B3', $title);

        // start dari B6
        $a = 6;
        $data = $get;
        $fill =  array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFFF00')
                )
            );
        $countNotSell = 0;
        $countFormulir = 0;
        $countSell = 0;
        for ($i=0; $i < count($data); $i++) {
            // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
            $style_row2 = array(
              'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              ),
              'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
              )
            );
           $no = $i + 1;  
           $excel3->setCellValue('B'.$a, $no);
           $excel3->setCellValue('C'.$a, $data[$i]['FormulirCodeGlobal']);
           $excel3->setCellValue('D'.$a, $data[$i]['FullName']);
           $dateFin = ($data[$i]['DateFin'] != '' || $data[$i]['DateFin'] != null) ? date('d M Y', strtotime($data[$i]['DateFin'])) : '';
           $excel3->setCellValue('E'.$a, $dateFin);
           $excel3->setCellValue('F'.$a, $data[$i]['Sales']);

           // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
           if ($data[$i]['StatusGlobalFormulir'] == 0) {
               $countNotSell++;
               $style_row2 = $style_row2 + $fill;
           }
           else
           {
            $countSell++;
           }
           $excel3->getStyle('B'.$a)->applyFromArray($style_row2);
           $excel3->getStyle('C'.$a)->applyFromArray($style_row2);
           $excel3->getStyle('D'.$a)->applyFromArray($style_row2);
           $excel3->getStyle('E'.$a)->applyFromArray($style_row2);
           $excel3->getStyle('F'.$a)->applyFromArray($style_row2);
           $a = $a + 1;
           $countFormulir++; 
        }

        $excel3->setCellValue('H7', 'Terjual :'.$countSell);
        $excel3->setCellValue('H8', 'Tidak Terjual :'.$countNotSell);
        $excel3->setCellValue('H9', 'Total :'.$countFormulir);
        $filename = 'Rekap_Formulir_'.date('y-m-d').'.xlsx';

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        // We'll be outputting an excel file  
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        // It will be called file.xlss
        header('Content-Disposition: attachment; filename="'.$filename.'"'); // jalan ketika tidak menggunakan ajax
        $objWriter->save('php://output'); // jalan ketika tidak menggunakan ajax
    }

    private function exCel_PenjualanFormulirFinance($title,$data)
    {
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel;
        $sheet = $objPHPExcel->getActiveSheet();
        $count = 5;
        $buy = $free = 0;
        $total = 0;
        
        //---------------------- table data ----------------------
        $sheet->setCellValue('A1', 'Laporan Penjualan Formulir');
        $sheet->setCellValue('A2', $title);
        
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Tanggal');
        $sheet->setCellValue('C4', 'Form');
        $sheet->setCellValue('D4', 'Nama');
        $sheet->setCellValue('E4', 'Channel');
        $sheet->setCellValue('F4', 'Keterangan');
        $sheet->setCellValue('G4', 'Jumlah');
        
        for ($i=0; $i < count($data); $i++) { 
            $sheet->setCellValue('A'.$count, ($i + 1));
            $sheet->setCellValue('B'.$count, date('d M Y', strtotime($data[$i]['DateSale'] )));
            $sheet->setCellValue('C'.$count, ($data[$i]['No_Ref'] == "" || $data[$i]['No_Ref'] == null ) ? $data[$i]['FormulirCode'] : $data[$i]['No_Ref']);
            $sheet->setCellValue('D'.$count, $data[$i]['FullName']);
            $sheet->setCellValue('E'.$count, $data[$i]['Channel']);
            $sheet->setCellValue('F'.$count, '');
            $sheet->setCellValue('G'.$count, number_format($data[$i]['Price_Form']));
            $count++;
            $total += $data[$i]['Price_Form'];
            if ($data[$i]['Price_Form'] > 0) {
                $buy++;
            }
            else
            {
                $free++;
            }
        }

        $sheet->setCellValue('A'.$count, 'Total');
        $sheet->setCellValue('G'.$count, number_format($total));
        
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$count.':F'.$count);
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle('A2')->getFont()->setSize(12);
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A4:G4')->getFont()->setBold(true);
        $sheet->getStyle('A'.$count)->getFont()->setBold(true);
        
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G5:G'.$count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G'.$count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A4:G'.$count)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A4:G4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ABCAFF');
        
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        
        //---------------------- summary ----------------------
        $sheet->setCellValue('I4', 'Summary');
        $sheet->setCellValue('I5', 'Bayar');
        $sheet->setCellValue('I6', 'Free');
        $sheet->setCellValue('I7', 'Total form');
        
        $sheet->setCellValue('J4', ':');
        $sheet->setCellValue('J5', ':');
        $sheet->setCellValue('J6', ':');
        $sheet->setCellValue('J7', ':');
        
        $sheet->setCellValue('K4', '-');
        $sheet->setCellValue('K5', $buy);
        $sheet->setCellValue('K6', $free);
        $sheet->setCellValue('K7', count($data));
        
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I4:L4');

        $sheet->setTitle('Penjualan Form');
        $objPHPExcel->setActiveSheetIndex(0);
        $filename = 'report_penjualan_finance_'.date('y-m-d').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $write->save('php://output');
    }

    public function export_PengembalianFormulirData()
    {
        $this->load->model('admission/m_admission');
        $this->load->model('master/m_master');
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $Input = (array) $this->jwt->decode($token,$key);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        switch ($Input['cf']) {
            case 0: // date range
                $dateRange1 = $Input['dateRange1'];
                $dateRange2 = $Input['dateRange2'];
                $SelectSetTa = $Input['SelectSetTa'];
                $SelectSortBy = $Input['SelectSortBy'];
                $get = $this->m_admission->getRegisterData($dateRange1,$dateRange2,$SelectSetTa,$SelectSortBy);
                $title = 'Tanggal '.date('d M Y', strtotime($dateRange1)).' - '.date('d M Y', strtotime($dateRange2));
                $this->exCel_PengembalianFormulirData($title,$get);
                break;
            case 1: // by Month
               $SelectMonth = $Input['SelectMonth'];
               $SelectYear = $Input['SelectYear'];
               $SelectSetTa = $Input['SelectSetTa'];
               $SelectSortBy = $Input['SelectSortBy'];
               $get = $this->m_admission->getRegisterDataPermonth($SelectMonth,$SelectYear,$SelectSetTa,$SelectSortBy);
               $title = 'Bulan '.date('F Y', strtotime($SelectYear.'-'.$SelectMonth.'-01'));
               $this->exCel_PengembalianFormulirData($title,$get); 
                break;
            default:
                # code...
                break;
        }
    }

    private function exCel_PengembalianFormulirData($title,$data)
    {
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel;
        $sheet = $objPHPExcel->getActiveSheet();
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/admisi/report_pengembalian_data.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        $excel3->setCellValue('A2', $title);

        // start dari A7
        $a = 6;
        for ($i=0; $i < count($data); $i++) {
           $no = $i + 1;  
           $excel3->setCellValue('A'.$a, $data[$i]['FormulirWrite']); 
           $excel3->setCellValue('B'.$a, '');
           $excel3->setCellValue('C'.$a, date('d M Y', strtotime($data[$i]['RegisterAT'])));
           $excel3->setCellValue('D'.$a, $data[$i]['Name']);
           $excel3->setCellValue('E'.$a, $data[$i]['Gender']);
           $excel3->setCellValue('F'.$a, $data[$i]['PlaceBirth'].', '.date('d M Y', strtotime($data[$i]['DateBirth'])) );
           $excel3->setCellValue('G'.$a, $data[$i]['Agama']);
           $excel3->setCellValue('H'.$a, $data[$i]['ctr_name']);
           $excel3->setCellValue('I'.$a, $data[$i]['NamaProdi']);
           $excel3->setCellValue('J'.$a, '');
           $excel3->setCellValue('K'.$a, $data[$i]['PhoneNumber']);
           $excel3->setCellValue('L'.$a, $data[$i]['HomeNumber']);
           $excel3->setCellValue('M'.$a, $data[$i]['Email']);
           $excel3->setCellValue('N'.$a, '');
           $excel3->setCellValue('O'.$a, $data[$i]['JacketSize']);
           $excel3->setCellValue('P'.$a, $data[$i]['src_name']);
           $excel3->setCellValue('Q'.$a, $data[$i]['alamat']);
           $excel3->setCellValue('R'.$a, '');
           $excel3->setCellValue('S'.$a, $data[$i]['ProvinceName']);
           $excel3->setCellValue('T'.$a, $data[$i]['SchoolName']);
           $excel3->setCellValue('U'.$a, $data[$i]['CitySchool']);
           $excel3->setCellValue('V'.$a, $data[$i]['ads_sta']);
           $excel3->setCellValue('W'.$a, $data[$i]['raport']);
           $excel3->setCellValue('X'.$a, $data[$i]['Ijazah']);
           $excel3->setCellValue('Y'.$a, '');
           $excel3->setCellValue('Z'.$a, $data[$i]['Foto']);
           $excel3->setCellValue('AA'.$a, $data[$i]['Refletter']);
           $excel3->setCellValue('AB'.$a, $data[$i]['SuratNarkoba']);
           $excel3->setCellValue('AC'.$a, $data[$i]['Essay']);
           $excel3->setCellValue('AD'.$a, $data[$i]['FatherName']);
           $excel3->setCellValue('AE'.$a, $data[$i]['FatherStatus']);
           $excel3->setCellValue('AF'.$a, $data[$i]['FatherPhoneNumber']);
           $excel3->setCellValue('AG'.$a, '');
           $excel3->setCellValue('AH'.$a, $data[$i]['FatherJob']);
           $excel3->setCellValue('AI'.$a, $data[$i]['FatherAddress']);
           $excel3->setCellValue('AJ'.$a, $data[$i]['MotherName']);
           $excel3->setCellValue('AK'.$a, $data[$i]['MotherStatus']);
           $excel3->setCellValue('AL'.$a, $data[$i]['MotherPhoneNumber']);
           $excel3->setCellValue('AM'.$a, '');
           $excel3->setCellValue('AN'.$a, $data[$i]['MotherJob']);
           $excel3->setCellValue('AO'.$a, $data[$i]['MotherAddress']);

           // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
           $excel3->getStyle('A'.$a)->applyFromArray($style_row);
           $excel3->getStyle('B'.$a)->applyFromArray($style_row);
           $excel3->getStyle('C'.$a)->applyFromArray($style_row);
           $excel3->getStyle('D'.$a)->applyFromArray($style_row);
           $excel3->getStyle('E'.$a)->applyFromArray($style_row);
           $excel3->getStyle('F'.$a)->applyFromArray($style_row);
           $excel3->getStyle('G'.$a)->applyFromArray($style_row);
           $excel3->getStyle('H'.$a)->applyFromArray($style_row);
           $excel3->getStyle('I'.$a)->applyFromArray($style_row);
           $excel3->getStyle('J'.$a)->applyFromArray($style_row);
           $excel3->getStyle('K'.$a)->applyFromArray($style_row);
           $excel3->getStyle('L'.$a)->applyFromArray($style_row);
           $excel3->getStyle('M'.$a)->applyFromArray($style_row);
           $excel3->getStyle('N'.$a)->applyFromArray($style_row);
           $excel3->getStyle('O'.$a)->applyFromArray($style_row);
           $excel3->getStyle('P'.$a)->applyFromArray($style_row);
           $excel3->getStyle('Q'.$a)->applyFromArray($style_row);
           $excel3->getStyle('R'.$a)->applyFromArray($style_row);
           $excel3->getStyle('S'.$a)->applyFromArray($style_row);
           $excel3->getStyle('T'.$a)->applyFromArray($style_row);
           $excel3->getStyle('U'.$a)->applyFromArray($style_row);
           $excel3->getStyle('V'.$a)->applyFromArray($style_row);
           $excel3->getStyle('W'.$a)->applyFromArray($style_row);
           $excel3->getStyle('X'.$a)->applyFromArray($style_row);
           $excel3->getStyle('Y'.$a)->applyFromArray($style_row);
           $excel3->getStyle('Z'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AA'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AB'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AC'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AD'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AE'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AF'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AG'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AH'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AI'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AJ'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AK'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AL'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AM'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AN'.$a)->applyFromArray($style_row);
           $excel3->getStyle('AO'.$a)->applyFromArray($style_row);

           $a = $a + 1; 
        }

        $filename = 'report_pengembalian_formulir_'.date('y-m-d').'.xlsx';

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        // We'll be outputting an excel file  
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        // It will be called file.xlss
        header('Content-Disposition: attachment; filename="'.$filename.'"'); // jalan ketika tidak menggunakan ajax
        $objWriter->save('php://output'); // jalan ketika tidak menggunakan ajax
    }



    // ===== REKAP IPS IPK ======

    public function cumulative_recap(){
        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        $data = $this->m_save_to_excel->_getCumulativeRecap($data_arr);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes


        // Panggil class PHPExcel nya
        $excel = new PHPExcel();

        $pr = 'REKAP IPS IPK '.$data_arr['Year'];

        // Settingan awal fil excel
        $excel->getProperties()->setCreator('IT PU')
            ->setLastModifiedBy('IT PU')
            ->setTitle($pr)
            ->setSubject($pr)
            ->setDescription($pr)
            ->setKeywords($pr);

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '33cccc')
            )
        );


        $style_col_fill = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', $pr); // Set kolom A1 dengan tulisan "DATA KARYAWAN"
        $excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai O1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1


        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NIM"); // Set kolom A3 dengan tulisan "NIK"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Nama");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Program Studi");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "SKS IPS");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "IPS");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Total SKS");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "IPK");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);

        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

        if(count($data)>0){
            foreach ($data AS $item){
                // Buat header tabel nya pada baris ke 3
                $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $item['NPM']);
                $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ucwords(strtolower($item['Name'])));
                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, ucwords(strtolower($item['ProdiName'])));
                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $item['IPS_TotalCredit']);
                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $item['IPS']);
                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $item['IPK_TotalCredit']);
                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $item['IPK']);

                // Apply style header yang telah kita buat tadi ke masing-masing kolom header
                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_col_fill);
                $numrow += 1;
            };
        }

        foreach(range('A','Z') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        // Proses file excel
        $filename = str_replace(' ','_',$pr).".xlsx";
        //$FILEpath = "./dokument/".$filename;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.$filename); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');

    }

    // ===== PENUTUP REKAP IPS IPK ======

    // ===== DATA STUDENT ======

    public function student_recap(){

        $token = $this->input->post('token');
        $data_arr = $this->getInputToken($token);

        $data = $this->m_save_to_excel->_getStudentRecap($data_arr);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes


        // Panggil class PHPExcel nya
        $excel = new PHPExcel();

//        $pr = 'REKAP IPS IPK '.$data_arr['Year'];
        $pr = 'REKAP IPS IPK ';

        // Settingan awal fil excel
        $excel->getProperties()->setCreator('IT PU')
            ->setLastModifiedBy('IT PU')
            ->setTitle($pr)
            ->setSubject($pr)
            ->setDescription($pr)
            ->setKeywords($pr);

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '00ffcc')
            )
        );

        $style_col_ayah = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'd6d6c2')
            )
        );

        $style_col_ibu = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffebcc')
            )
        );


        $style_col_fill = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', $pr); // Set kolom A1 dengan tulisan "DATA KARYAWAN"
        $excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai O1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1


        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "No.");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NIM");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Name");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Prodi");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Program");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Jenjang");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "JK");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Agama");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Tempat Lahir");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Tanggal Lahir");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "Alamat");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "Telepon");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "HP");
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Email");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "EmailPU");
        $excel->setActiveSheetIndex(0)->setCellValue('P3', "Status");
        $excel->setActiveSheetIndex(0)->setCellValue('Q3', "Anak Ke");
        $excel->setActiveSheetIndex(0)->setCellValue('R3', "Jumlah Saudara");
        $excel->setActiveSheetIndex(0)->setCellValue('S3', "Nomor Ijazah");

        $excel->setActiveSheetIndex(0)->setCellValue('T3', "Ayah");
        $excel->setActiveSheetIndex(0)->setCellValue('U3', "Telepon Ayah");
        $excel->setActiveSheetIndex(0)->setCellValue('V3', "Pekerjaan Ayah");
        $excel->setActiveSheetIndex(0)->setCellValue('W3', "Pendidikan Ayah");
        $excel->setActiveSheetIndex(0)->setCellValue('X3', "Alamat Ayah");

        $excel->setActiveSheetIndex(0)->setCellValue('Y3', "Ibu");
        $excel->setActiveSheetIndex(0)->setCellValue('Z3', "Telepon Ibu");
        $excel->setActiveSheetIndex(0)->setCellValue('AA3', "Pekerjaan Ibu");
        $excel->setActiveSheetIndex(0)->setCellValue('AB3', "Pendidikan Ibu");
        $excel->setActiveSheetIndex(0)->setCellValue('AC3', "Alamat Ibu");

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('P3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('Q3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('R3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('S3')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('T3')->applyFromArray($style_col_ayah);
        $excel->getActiveSheet()->getStyle('U3')->applyFromArray($style_col_ayah);
        $excel->getActiveSheet()->getStyle('V3')->applyFromArray($style_col_ayah);
        $excel->getActiveSheet()->getStyle('W3')->applyFromArray($style_col_ayah);
        $excel->getActiveSheet()->getStyle('X3')->applyFromArray($style_col_ayah);

        $excel->getActiveSheet()->getStyle('Y3')->applyFromArray($style_col_ibu);
        $excel->getActiveSheet()->getStyle('Z3')->applyFromArray($style_col_ibu);
        $excel->getActiveSheet()->getStyle('AA3')->applyFromArray($style_col_ibu);
        $excel->getActiveSheet()->getStyle('AB3')->applyFromArray($style_col_ibu);
        $excel->getActiveSheet()->getStyle('AC3')->applyFromArray($style_col_ibu);

        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

        if(count($data)>0){
            $no = 1;
            foreach ($data AS $item){
                // Buat header tabel nya pada baris ke 3
                $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
                $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow,$item['NPM']);
                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow,$item['Name']);
                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow,$item['ProdiName']);
                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow,$item['Program']);
                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow,$item['Level']);
                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow,$item['Gender']);
                $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow,$item['Religion']);
                $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow,$item['PlaceOfBirth']);
                $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow,$item['DateOfBirth']);
                $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow,$item['Address']);
                $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow,$item['Phone']);
                $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow,$item['HP']);
                $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow,$item['Email']);
                $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow,$item['EmailPU']);
                $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow,$item['StatusDesc']);
                $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow,$item['AnakKe']);
                $excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow,$item['JumlahSaudara']);
                $excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow,$item['IjazahNumber']);

                $excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow,$item['Father']);
                $excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow,$item['PhoneFather']);
                $excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow,$item['OccupationFather']);
                $excel->setActiveSheetIndex(0)->setCellValue('W'.$numrow,$item['EducationFather']);
                $excel->setActiveSheetIndex(0)->setCellValue('X'.$numrow,$item['AddressFather']);

                $excel->setActiveSheetIndex(0)->setCellValue('Y'.$numrow,$item['Mother']);
                $excel->setActiveSheetIndex(0)->setCellValue('Z'.$numrow,$item['PhoneMother']);
                $excel->setActiveSheetIndex(0)->setCellValue('AA'.$numrow,$item['OccupationMother']);
                $excel->setActiveSheetIndex(0)->setCellValue('AB'.$numrow,$item['EducationMother']);
                $excel->setActiveSheetIndex(0)->setCellValue('AC'.$numrow,$item['AddressMother']);

                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('R'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('S'.$numrow)->applyFromArray($style_col_fill);

                $excel->getActiveSheet()->getStyle('T'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('U'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('V'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('W'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('X'.$numrow)->applyFromArray($style_col_fill);

                $excel->getActiveSheet()->getStyle('Y'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('Z'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('AA'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('AB'.$numrow)->applyFromArray($style_col_fill);
                $excel->getActiveSheet()->getStyle('AC'.$numrow)->applyFromArray($style_col_fill);

                $no += 1;
                $numrow += 1;
            }
        }

        $rangeS = range('A','Z');
        $rangeS[26] = 'AA';
        $rangeS[27] = 'AB';
        $rangeS[28] = 'AC';
        foreach( $rangeS as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        // Proses file excel
        $filename = str_replace(' ','_',$pr).".xlsx";
        //$FILEpath = "./dokument/".$filename;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.$filename); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');


    }

    // ===== PENUTUP DATA STUDENT ======

    public function export_TuitionFee_Excel()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $input = (array) $this->jwt->decode($token,$key);
        $this->load->model('admission/m_admission');
        // start dari A4
        $Year = $input['Year'];
        $Prodi = $input['Prodi'];
        $getData = $this->m_admission->getDataCalonMhsTuitionFee_approved_ALL($Year,$Prodi);
        $this->getExcelTuition_fee_admission($getData,$Year,$Prodi);
    }

    public function getExcelTuition_fee_admission($getData,$Year,$Prodi)
    {
        $GetDateNow = date('Y-m-d');
        $this->load->model('master/m_master');
        $GetDateNow = $this->m_master->getIndoBulan($GetDateNow);
        // print_r($input['Data']);die();

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/admisi/rekap_tuition_fee.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();
        // $excel3->setCellValue('A3', $GetDateNow.' Jam '.date('H:i'));

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // start dari A4
        $a = 4;
        $Filaname = 'Intake_'.$Year.'.xlsx';
        $getData = $this->m_admission->getDataCalonMhsTuitionFee_approved_ALL($Year,$Prodi);
        $keyM = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        // print_r($getData);die();
        $SumTotalTagihan = 0;
        $SumTotalPembayaran = 0;
        $SumTotalSisaTagihan = 0;
        for ($i=0; $i < count($getData); $i++) { 
            $ID_register_formulir = $getData[$i]['ID_register_formulir'];
            $output = $this->m_master->caribasedprimary('db_finance.payment_pre','ID_register_formulir',$ID_register_formulir);
            $no = $i + 1;
            $FormulirCode = ($getData[$i]['No_Ref'] == "" || $getData[$i]['No_Ref'] == null) ? $getData[$i]['FormulirCode'] : $getData[$i]['No_Ref'];
            $GetPayment = "SPP : Rp ".number_format($getData[$i]['SPP'],2,',','.')."\n"."BPP : Rp ".number_format($getData[$i]['BPP'],2,',','.')."\n"."SKS : Rp ".number_format($getData[$i]['Credit'],2,',','.')."\n"."Lain-Lain : Rp ".number_format($getData[$i]['Another'],2,',','.')."\n" ;
            $Beasiswa = ($getData[$i]['getBeasiswa'] == "-") ? "" : "Beasiswa ".$getData[$i]['getBeasiswa']."\n";
            $Discount = "";
            if ($getData[$i]['Discount-SPP'] > 0) {
                $Discount .= 'SPP : '.$getData[$i]['Discount-SPP'].'%';
            }

            if ($getData[$i]['Discount-BPP'] > 0) {
                $aa = "";
                if ($Discount != "") {
                    $aa = "\n";
                }
                $Discount .= $aa.'BPP : '.$getData[$i]['Discount-BPP'].'%';
            }

            if ($getData[$i]['Discount-Credit'] > 0) {
                $aa = "";
                if ($Discount != "") {
                    $aa = "\n";
                }
                $Discount .= $aa.'SKS : '.$getData[$i]['Discount-Credit'].'%';
            }

            if ($getData[$i]['Discount-Another'] > 0) {
                $aa = "";
                if ($Discount != "") {
                    $aa = "\n";
                }
                $Discount .= $aa.'Lain-lain : '.$getData[$i]['Discount-Another'].'%';
            }
            
            $excel3->setCellValue('A'.$a, $no); 
            $excel3->setCellValue('B'.$a, $FormulirCode);
            $excel3->setCellValue('C'.$a, $getData[$i]['Name']);
            $excel3->setCellValue('D'.$a, $getData[$i]['NamePrody']);
            $excel3->setCellValue('E'.$a, $getData[$i]['SchoolName']);
            $excel3->setCellValue('F'.$a, $getData[$i]['CitySchool']);
            $excel3->setCellValue('G'.$a, $GetPayment);
            $excel3->setCellValue('H'.$a, $Beasiswa.$Discount);

            // cicilan start array key 8 yaitu I
            $keyI = 8;
            $TotalBayar = 0;
            $TotalAll = 0;
            for ($j=0; $j < count($output); $j++) { 
               $tt = '';
               $byr = '';
               $as = $keyI + 1;
               if ($output[$j]['Status'] == 1) {
                   $tt = date('d M Y', strtotime($output[$j]['DatePayment']));
                   $byr = "Rp. ".number_format($output[$j]['Invoice'],2,',','.');
                   $TotalBayar = $TotalBayar + $output[$j]['Invoice'];
               }
               $TotalAll = $TotalAll + $output[$j]['Invoice'];

               $excel3->setCellValue($keyM[$keyI].$a, $tt);
               $excel3->setCellValue($keyM[$as].$a, $byr);
               $keyI = $keyI + 2;
            }

            $ss = 7 - count($output);
            for ($j=0; $j < $ss; $j++) {
               $as = $keyI + 1; 
               $excel3->setCellValue($keyM[$keyI].$a, "");
               $excel3->setCellValue($keyM[$as].$a, "");
               $keyI = $keyI + 2;
            }
            $SisaTagihan = $TotalAll - $TotalBayar;
            $SumTotalTagihan = $SumTotalTagihan + $TotalAll;
            $SumTotalPembayaran = $SumTotalPembayaran + $TotalBayar;
            $SumTotalSisaTagihan = $SumTotalSisaTagihan + $SisaTagihan;
            $excel3->setCellValue('W'.$a, "Rp. ".number_format($TotalAll,2,',','.'));
            $excel3->setCellValue('X'.$a, "Rp. ".number_format($TotalBayar,2,',','.'));
            $excel3->setCellValue('Y'.$a, "Rp. ".number_format($SisaTagihan,2,',','.'));
            $excel3->setCellValue('Z'.$a, ($SisaTagihan > 0) ? "Belum lunas" : "Lunas" );
            $excel3->setCellValue('AA'.$a, $getData[$i]['Event']);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel3->getStyle('A'.$a)->applyFromArray($style_row);
            $excel3->getStyle('B'.$a)->applyFromArray($style_row);
            $excel3->getStyle('C'.$a)->applyFromArray($style_row);
            $excel3->getStyle('D'.$a)->applyFromArray($style_row);
            $excel3->getStyle('E'.$a)->applyFromArray($style_row);
            $excel3->getStyle('F'.$a)->applyFromArray($style_row);
            $excel3->getStyle('G'.$a)->applyFromArray($style_row);
             $excel3->getStyle('G'.$a)->getAlignment()->setWrapText(true);
            $excel3->getStyle('H'.$a)->applyFromArray($style_row);
            $excel3->getStyle('H'.$a)->getAlignment()->setWrapText(true);
            $excel3->getStyle('I'.$a)->applyFromArray($style_row);
            $excel3->getStyle('J'.$a)->applyFromArray($style_row);
            $excel3->getStyle('K'.$a)->applyFromArray($style_row);
            $excel3->getStyle('L'.$a)->applyFromArray($style_row);
            $excel3->getStyle('M'.$a)->applyFromArray($style_row);
            $excel3->getStyle('N'.$a)->applyFromArray($style_row);
            $excel3->getStyle('O'.$a)->applyFromArray($style_row);
            $excel3->getStyle('P'.$a)->applyFromArray($style_row);
            $excel3->getStyle('Q'.$a)->applyFromArray($style_row);
            $excel3->getStyle('R'.$a)->applyFromArray($style_row);
            $excel3->getStyle('S'.$a)->applyFromArray($style_row);
            $excel3->getStyle('T'.$a)->applyFromArray($style_row);
            $excel3->getStyle('U'.$a)->applyFromArray($style_row);
            $excel3->getStyle('V'.$a)->applyFromArray($style_row);
            $excel3->getStyle('W'.$a)->applyFromArray($style_row);
            $excel3->getStyle('X'.$a)->applyFromArray($style_row);
            $excel3->getStyle('Y'.$a)->applyFromArray($style_row);
            $excel3->getStyle('Z'.$a)->applyFromArray($style_row);
            $excel3->getStyle('AA'.$a)->applyFromArray($style_row);
            $a = $a + 1; 
        }

        $excel3->setCellValue('W'.$a, "Rp. ".number_format($SumTotalTagihan,2,',','.'));
        $excel3->setCellValue('X'.$a, "Rp. ".number_format($SumTotalPembayaran,2,',','.'));
        $excel3->setCellValue('Y'.$a, "Rp. ".number_format($SumTotalSisaTagihan,2,',','.'));

        foreach(range('A','Z') as $columnID) {
            $excel2->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        // It will be called file.xlss
        header('Content-Disposition: attachment; filename="'.$Filaname.'"'); // jalan ketika tidak menggunakan ajax
        //$filename = 'PenerimaanPembayaran.xlsx';
        //$objWriter->save('./document/'.$filename);
        $objWriter->save('php://output'); // jalan ketika tidak menggunakan ajax

        // print_r($input['summary']);
    }

    public function excel_data_mahasiswa_fin()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $input = (array) $this->jwt->decode($token,$key);
        $GetDateNow = date('Y-m-d');
        $this->load->model('master/m_master');
        $this->load->model('finance/m_finance');
        $this->load->model('admission/m_admission');
        //$GetDateNow = $this->m_master->getIndoBulan($GetDateNow);
        // print_r($input['Data']);die();

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/finance/TemplateDataMahasiswa.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();
        // write date export 
        $DatePrint = date('d M Y', strtotime($GetDateNow));
        $excel3->setCellValue('A2', 'Tanggal : '.$DatePrint);
        $excel3->setCellValue('A3', 'Data Mahasiswa Angkatan '.$input['Year']);

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // start dari A4
        $Year = $input['Year'];
        $Prodi = $input['Prodi'];
        $NPM = $input['NPM'];
        $a = 5;
        $Filaname = 'Data_MHS_'.$Year.'.xlsx';
        $getData = $this->m_finance->mahasiswa_list_all($Year,$Prodi,$NPM);
        $keyM = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $getStatus = $this->m_master->showData_array('db_academic.status_student');
        $Bin1 = 0;
        $Bin2 = 0;
        $arrxx = array();
        for ($i=0; $i < count($getData); $i++) { 
            $no = $i + 1;
            // number_format($getData[$i]['SPP'],2,',','.')
            
            $excel3->setCellValue('A'.$a, $no); 
            $excel3->setCellValue('B'.$a, $getData[$i]['NPM']);
            $excel3->setCellValue('C'.$a, $getData[$i]['Name']);
            $excel3->setCellValue('D'.$a, (string)$getData[$i]['VA']);
            $excel3->setCellValue('E'.$a, $getData[$i]['ProdiEng']);
            $excel3->setCellValue('F'.$a, number_format($getData[$i]['IPS'],2,',','.'));
            $excel3->setCellValue('G'.$a, number_format($getData[$i]['IPK'],2,',','.'));
            $excel3->setCellValue('H'.$a, $getData[$i]['Credit']);
            $excel3->setCellValue('I'.$a, $getData[$i]['StatusStudentName']);
            $PriceList = ($getData[$i]['Pay_Cond'] == 1) ? "*" : "**";
            if ($getData[$i]['Pay_Cond'] == 1) {
                $Bin1++;
            }
            if ($getData[$i]['Pay_Cond'] == 2) {
                $Bin2++;
            }

            for ($l=0; $l < count($getStatus); $l++) {
                // find StudentID
                if ($getStatus[$l]['ID'] == $getData[$i]['StatusStudentID']) {
                    $Name =  $getStatus[$l]['Description'];
                    $Name =  str_replace(" ", "", $Name);
                    if(array_key_exists($Name,$arrxx))
                    {
                        $arrxx[$Name] = $arrxx[$Name] + 1 ;
                    }
                    else
                    {
                       $arrxx[$Name] = 1;
                    }
                }
                
            }

            $excel3->setCellValue('J'.$a, $PriceList);
            $excel3->setCellValue('K'.$a, number_format($getData[$i]['Bea_BPP'],2,',','.'));
            $excel3->setCellValue('L'.$a, number_format($getData[$i]['Bea_Credit']));

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel3->getStyle('A'.$a)->applyFromArray($style_row);
            $excel3->getStyle('B'.$a)->applyFromArray($style_row);
            $excel3->getStyle('C'.$a)->applyFromArray($style_row);
            $excel3->getStyle('D'.$a)->applyFromArray($style_row);
            $excel3->getStyle('E'.$a)->applyFromArray($style_row);
            $excel3->getStyle('F'.$a)->applyFromArray($style_row);
            $excel3->getStyle('G'.$a)->applyFromArray($style_row);
            $excel3->getStyle('H'.$a)->applyFromArray($style_row);
            $excel3->getStyle('I'.$a)->applyFromArray($style_row);
            $excel3->getStyle('J'.$a)->applyFromArray($style_row);
            $excel3->getStyle('K'.$a)->applyFromArray($style_row);
            $excel3->getStyle('L'.$a)->applyFromArray($style_row);
            $a = $a + 1; 
        }
            
        $excel3->setCellValue('O'.'5', 'Bintang 1 : '.$Bin1);   
        $excel3->setCellValue('O'.'6', 'Bintang 2 : '.$Bin2);
        $aaaa = 7;
        foreach ($arrxx as $key => $value) {
            $excel3->setCellValue('O'.$aaaa, $key.' : '.$value);     
            $aaaa++;
        }  
        // foreach(range('A','Z') as $columnID) {
        //     $excel2->getActiveSheet()->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        // It will be called file.xlss
        header('Content-Disposition: attachment; filename="'.$Filaname.'"'); // jalan ketika tidak menggunakan ajax
        //$filename = 'PenerimaanPembayaran.xlsx';
        //$objWriter->save('./document/'.$filename);
        $objWriter->save('php://output'); // jalan ketika tidak menggunakan ajax
    }

    public function dailypenerimaanBank_admission()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $input = (array) $this->jwt->decode($token,$key);
        $GetDateNow = date('Y-m-d');
        $this->load->model('master/m_master');
        $this->load->model('finance/m_finance');
        $this->load->model('admission/m_admission');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/finance/TemplateDailyPenerimaanBank.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();
        // write date export 
        $PerTgl = 'Per tgl '.date('d M Y', strtotime($input['DailyTgl']));
        $DatePrint = date('d M Y', strtotime($GetDateNow));
        $excel3->setCellValue('A4', $PerTgl);

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // start dari A8
        $Year = $input['Year'];
        $DailyTgl = $input['DailyTgl'];
        $excel3->setCellValue('A6', 'Intake: '.$Year.'/'.($Year+1));
        $a = 7;
        $Filaname = 'DailyPenerimaanBank_Intake_'.$Year.'_'.$DailyTgl.'.xlsx';
        $getData = $this->m_finance->getPayment_Daily_admission($Year,$DailyTgl);
        $keyM = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $arrxx = array();
        $Total = 0;
        for ($i=0; $i < count($getData); $i++) { 
            $data =$getData[$i]['data'];
            $excel3->setCellValue('A'.$a, 'No'); 
            $excel3->setCellValue('B'.$a, 'Tgl');
            $excel3->setCellValue('C'.$a, 'Nama');
            $excel3->setCellValue('D'.$a, 'Semester');
            $excel3->setCellValue('E'.$a, 'Jurusan');
            $excel3->setCellValue('F'.$a, 'Keterangan');
            $excel3->setCellValue('G'.$a, 'Jumlah');
            $excel3->getStyle('A'.$a)->applyFromArray($style_col);
            $excel3->getStyle('B'.$a)->applyFromArray($style_col);
            $excel3->getStyle('C'.$a)->applyFromArray($style_col);
            $excel3->getStyle('D'.$a)->applyFromArray($style_col);
            $excel3->getStyle('E'.$a)->applyFromArray($style_col);
            $excel3->getStyle('F'.$a)->applyFromArray($style_col);
            $excel3->getStyle('G'.$a)->applyFromArray($style_col);
            $a = $a + 1; // untuk isi
            for ($j=0; $j < count($data); $j++) {
                $no = $j + 1;
                $excel3->setCellValue('A'.$a, $no); 
                $excel3->setCellValue('B'.$a, date('d M Y', strtotime($data[$j]['DatePayment'])));
                $excel3->setCellValue('C'.$a, $data[$j]['Name']);
                $excel3->setCellValue('D'.$a, (string)1);
                $excel3->setCellValue('E'.$a, $data[$j]['NamePrody']);
                $Pembayaranke = 'ke'.$this->m_master->moneySay($data[$j]['Pembayaranke']);
                $ket = ($data[$j]['StatusTbl'] == 1) ? 'Cicilan '.$Pembayaranke : 'Pembayaran Formulir';
                $excel3->setCellValue('F'.$a, $ket);
                $excel3->setCellValue('G'.$a, $data[$j]['Invoice']);

                $excel3->getStyle('A'.$a)->applyFromArray($style_row);
                $excel3->getStyle('B'.$a)->applyFromArray($style_row);
                $excel3->getStyle('C'.$a)->applyFromArray($style_row);
                $excel3->getStyle('D'.$a)->applyFromArray($style_row);
                $excel3->getStyle('E'.$a)->applyFromArray($style_row);
                $excel3->getStyle('F'.$a)->applyFromArray($style_row);
                $excel3->getStyle('G'.$a)->applyFromArray($style_row);
                $a = $a + 1; 
            }

            $excel3->setCellValue('A'.$a, 'SUBTOTAL '.$data[0]['NamePrody']); 
            $excel3->setCellValue('G'.$a, $getData[$i]['subtotal']); 
            $excel3->mergeCells('A'.$a.':F'.$a);
            $excel3->getStyle('A'.$a)->applyFromArray($style_col);
            $excel3->getStyle('B'.$a)->applyFromArray($style_col);
            $excel3->getStyle('C'.$a)->applyFromArray($style_col);
            $excel3->getStyle('D'.$a)->applyFromArray($style_col);
            $excel3->getStyle('E'.$a)->applyFromArray($style_col);
            $excel3->getStyle('F'.$a)->applyFromArray($style_col);
            $excel3->getStyle('G'.$a)->applyFromArray($style_col);
            $Total = $Total + $getData[$i]['subtotal'];
            $a = $a + 1; 
            
        }
        
        $excel3->setCellValue('A'.$a, 'Total '); 
        $excel3->setCellValue('G'.$a, $Total); 
        $excel3->mergeCells('A'.$a.':F'.$a);
        $excel3->getStyle('A'.$a)->applyFromArray($style_col);
        $excel3->getStyle('B'.$a)->applyFromArray($style_col);
        $excel3->getStyle('C'.$a)->applyFromArray($style_col);
        $excel3->getStyle('D'.$a)->applyFromArray($style_col);
        $excel3->getStyle('E'.$a)->applyFromArray($style_col);
        $excel3->getStyle('F'.$a)->applyFromArray($style_col);
        $excel3->getStyle('G'.$a)->applyFromArray($style_col);       
        $excel3->setCellValue('F'.($a+3), 'Print Date,'.$DatePrint);
        // foreach(range('A','Z') as $columnID) {
        //     $excel2->getActiveSheet()->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        header('Content-Disposition: attachment; filename="'.$Filaname.'"'); // jalan ketika tidak menggunakan ajax
        $objWriter->save('php://output');
    }

    public function export_excel_report_daily()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $input = (array) $this->jwt->decode($token,$key);
        $GetDateNow = date('Y-m-d');
        $this->load->model('master/m_master');
        $this->load->model('finance/m_finance');
        $this->load->model('admission/m_admission');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/finance/TemplateDailyPenerimaanBank.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();
        // write date export 
        $PerTgl = 'Per tgl '.date('d M Y', strtotime($input['DailyTgl']));
        $DatePrint = date('d M Y', strtotime($GetDateNow));
        $excel3->setCellValue('A4', $PerTgl);

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // start dari A8
        $Semester = $input['selectSemester'];
        $SemesterName = explode(".", $Semester);
        $SemesterName = $SemesterName[1];

        $DailyTgl = $input['DailyTgl'];
        $excel3->setCellValue('A6', 'Semester: '.$SemesterName);
        $a = 7;
        $Filaname = 'DailyPenerimaanBank_Mhs_'.$DailyTgl.'.xlsx';
        $getData = $this->m_finance->getPayment_Daily_mhs($Semester,$DailyTgl);
        $keyM = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $arrxx = array();
        $Total = 0;
        for ($i=0; $i < count($getData); $i++) { 
            $data =$getData[$i]['data'];
            $excel3->setCellValue('A'.$a, 'No'); 
            $excel3->setCellValue('B'.$a, 'Tgl');
            $excel3->setCellValue('C'.$a, 'Nama');
            $excel3->setCellValue('D'.$a, 'Semester');
            $excel3->setCellValue('E'.$a, 'Jurusan');
            $excel3->setCellValue('F'.$a, 'Keterangan');
            $excel3->setCellValue('G'.$a, 'Jumlah');
            $excel3->getStyle('A'.$a)->applyFromArray($style_col);
            $excel3->getStyle('B'.$a)->applyFromArray($style_col);
            $excel3->getStyle('C'.$a)->applyFromArray($style_col);
            $excel3->getStyle('D'.$a)->applyFromArray($style_col);
            $excel3->getStyle('E'.$a)->applyFromArray($style_col);
            $excel3->getStyle('F'.$a)->applyFromArray($style_col);
            $excel3->getStyle('G'.$a)->applyFromArray($style_col);
            $a = $a + 1; // untuk isi
            for ($j=0; $j < count($data); $j++) {
                $no = $j + 1;
                $excel3->setCellValue('A'.$a, $no); 
                $excel3->setCellValue('B'.$a, date('d M Y', strtotime($data[$j]['DatePayment'])));
                $excel3->setCellValue('C'.$a, $data[$j]['NPM'].' | '.$data[$j]['NamaMHS']);
                $excel3->setCellValue('D'.$a, $data[$j]['semesterCount']);
                $excel3->setCellValue('E'.$a, $data[$j]['NamePrody']);
                $Pembayaranke = 'ke'.$this->m_master->moneySay($data[$j]['Pembayaranke']);
                $ket = 'Cicilan '.$Pembayaranke.' '.$data[$j]['Description'];
                $excel3->setCellValue('F'.$a, $ket);
                $excel3->setCellValue('G'.$a, $data[$j]['PaymentMhs']);

                $excel3->getStyle('A'.$a)->applyFromArray($style_row);
                $excel3->getStyle('B'.$a)->applyFromArray($style_row);
                $excel3->getStyle('C'.$a)->applyFromArray($style_row);
                $excel3->getStyle('D'.$a)->applyFromArray($style_row);
                $excel3->getStyle('E'.$a)->applyFromArray($style_row);
                $excel3->getStyle('F'.$a)->applyFromArray($style_row);
                $excel3->getStyle('G'.$a)->applyFromArray($style_row);
                $a = $a + 1; 
            }

            $excel3->setCellValue('A'.$a, 'SUBTOTAL '.$data[0]['NamePrody']); 
            $excel3->setCellValue('G'.$a, $getData[$i]['subtotal']); 
            $excel3->mergeCells('A'.$a.':F'.$a);
            $excel3->getStyle('A'.$a)->applyFromArray($style_col);
            $excel3->getStyle('B'.$a)->applyFromArray($style_col);
            $excel3->getStyle('C'.$a)->applyFromArray($style_col);
            $excel3->getStyle('D'.$a)->applyFromArray($style_col);
            $excel3->getStyle('E'.$a)->applyFromArray($style_col);
            $excel3->getStyle('F'.$a)->applyFromArray($style_col);
            $excel3->getStyle('G'.$a)->applyFromArray($style_col);
            $Total = $Total + $getData[$i]['subtotal'];
            $a = $a + 1; 
            
        }
         
        $excel3->setCellValue('A'.$a, 'Total '); 
        $excel3->setCellValue('G'.$a, $Total); 
        $excel3->mergeCells('A'.$a.':F'.$a);
        $excel3->getStyle('A'.$a)->applyFromArray($style_col);
        $excel3->getStyle('B'.$a)->applyFromArray($style_col);
        $excel3->getStyle('C'.$a)->applyFromArray($style_col);
        $excel3->getStyle('D'.$a)->applyFromArray($style_col);
        $excel3->getStyle('E'.$a)->applyFromArray($style_col);
        $excel3->getStyle('F'.$a)->applyFromArray($style_col);
        $excel3->getStyle('G'.$a)->applyFromArray($style_col);   
        $excel3->setCellValue('F'.($a+3), 'Print Date,'.$DatePrint);
        // foreach(range('A','Z') as $columnID) {
        //     $excel2->getActiveSheet()->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        header('Content-Disposition: attachment; filename="'.$Filaname.'"'); // jalan ketika tidak menggunakan ajax
        $objWriter->save('php://output');
    }

    public function RekapIntake()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $input = (array) $this->jwt->decode($token,$key);
        $GetDateNow = date('Y-m-d');
        $this->load->model('master/m_master');
        $this->load->model('finance/m_finance');
        $this->load->model('admission/m_admission');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('./uploads/finance/TemplateRekapIntake.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);

        $excel3 = $excel2->getActiveSheet();
        // write date export 
        $Year = $input['Year'];
        $excel3->setCellValue('A3', 'Intake: '.$Year.'/'.($Year+1));
        $PerTgl = 'Per tgl '.date('d M Y', strtotime($GetDateNow));
        $DatePrint = date('d M Y', strtotime($GetDateNow));
        $excel3->setCellValue('A4', $PerTgl);

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // start dari A5
        $a = 5;
        $Filaname = 'Rekap_Intake_'.$Year.'_'.$GetDateNow.'.xlsx';
        $keyM = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $rekapintake_ = $this->m_master->showData_array('db_statistik.rekapintake_'.$Year);    
        $rekapintake_bea_ = $this->m_master->showData_array('db_statistik.rekapintake_bea_'.$Year);    
        $rekapintake_sch_ = $this->m_master->showData_array('db_statistik.rekapintake_sch_'.$Year);
        $arrxx = array();

        // get last update
        $lastupdate = $this->m_master->showData_array('db_statistik.lastupdated');
        $rekapintake_ls = '';
        $rekapintake_bea_ls = '';
        $rekapintake_sch_ls = '';
        for ($i=0; $i < count($lastupdate); $i++) { 
            if ($lastupdate[$i]['TableName'] == 'rekapintake_'.$Year) {
                $rekapintake_ls = $lastupdate[$i]['LastUpdated'];
            }
            elseif ($lastupdate[$i]['TableName'] == 'rekapintake_bea_'.$Year) {
                $rekapintake_bea_ls = $lastupdate[$i]['LastUpdated'];
            }
            elseif ($lastupdate[$i]['TableName'] == 'rekapintake_sch_'.$Year) {
                $rekapintake_sch_ls = $lastupdate[$i]['LastUpdated'];
            }
        }

        // rekapintake_
        $getColoumn = $this->m_master->getColumnTable('db_statistik.rekapintake_'.$Year);
        $field = $getColoumn['field'];
        $getProdi = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
        $excel3->setCellValue('A'.$a, 'Last Updated : '.$rekapintake_ls);
        $a = $a + 1;
        $excel3->setCellValue('A'.$a, '');
        $excel3->getStyle('A'.$a)->applyFromArray($style_col);
        for ($j=0; $j < count($getProdi); $j++) { 
             $z = $j + 1;
             $excel3->setCellValue($keyM[$z].$a, $getProdi[$j]['Name']);
             $excel3->getStyle($keyM[$z].$a)->applyFromArray($style_col);
         }

        $a = $a + 1;
        $arr_total = array();
        for ($i=0; $i < count($field); $i++) { 
            if ($field[$i] != 'ID' && $field[$i] != 'ProdiID') {
                $aa =  $field[$i];
                $z = 0;
                $MonthName = $aa.' '.$Year;
                 if (strpos($aa, '_') !== false ) {
                     $monthQ = substr($aa, 0,strpos($aa, '_'));
                     $YearQ = $Year - 1;
                     $MonthName = $monthQ.' '.$YearQ;
                 }
                 // else
                 // {
                 //    $MonthName = $aa.' '.$Year;
                 // }
                  $excel3->setCellValue($keyM[$z].$a, $MonthName);
                  $excel3->getStyle($keyM[$z].$a)->applyFromArray($style_row);
                  $f = true;
                  for ($j=0; $j < count($getProdi); $j++) {
                    if ($getProdi[$j]['ID'] == $rekapintake_[$j]['ProdiID']) {
                         $z++;
                         $value = ($rekapintake_[$j][$aa] == 0) ? '-' : $rekapintake_[$j][$aa];
                         $excel3->setCellValue($keyM[$z].$a, $value);
                         $excel3->getStyle($keyM[$z].$a)->applyFromArray($style_row);
                         if(array_key_exists($rekapintake_[$j]['ProdiID'],$arr_total)){
                            $arr_total[$rekapintake_[$j]['ProdiID']] = $arr_total[$rekapintake_[$j]['ProdiID']] + $value;
                         }
                         else
                         {
                            $arr_total[$rekapintake_[$j]['ProdiID']] = $value;
                         }
                     } 
                      
                  }
               $a = $a + 1;   
            }   
        }

        $excel3->setCellValue('A'.$a, 'Total');
        $excel3->getStyle('A'.$a)->applyFromArray($style_col);
        for ($j=0; $j < count($getProdi); $j++) {
             $z = $j + 1;
             $excel3->setCellValue($keyM[$z].$a, $arr_total[$getProdi[$j]['ID']]);
             $excel3->getStyle($keyM[$z].$a)->applyFromArray($style_col);
        }

        // rekapintake_bea
        $arr_total = array();
        $a = $a + 2;
        $excel3->setCellValue('A'.$a, 'Last Updated : '.$rekapintake_bea_ls);
        $a = $a + 1;  
        $excel3->setCellValue('A'.$a, '');
        $excel3->getStyle('A'.$a)->applyFromArray($style_col);
        for ($j=0; $j < count($getProdi); $j++) { 
             $z = $j + 1;
             $excel3->setCellValue($keyM[$z].$a, $getProdi[$j]['Name']);
             $excel3->getStyle($keyM[$z].$a)->applyFromArray($style_col);
        }
        $a = $a + 1; 
        // get type beasiswa
            $dtFirst = json_decode($rekapintake_bea_[0]['Detail'],true);
            $arr_y = array();
            foreach ($dtFirst as $key => $value) {
                $arr_y[] =  $key;
            }

            for ($i=0; $i < count($arr_y); $i++) { 
                $typebea = $arr_y[$i];
                $z = 0;
                $namebea = ($typebea == 'Beasiswa_0%') ? 'Reguler' : str_replace('_', ' ', $typebea);
                $excel3->setCellValue($keyM[$z].$a, $namebea);
                $excel3->getStyle($keyM[$z].$a)->applyFromArray($style_row);
                for ($j=0; $j < count($getProdi); $j++) { 
                    $ProdiID = $getProdi[$j]['ID'];
                    for ($l=0; $l < count($rekapintake_bea_); $l++) {
                        $f1 = true; 
                        $ProdiIDBea = $rekapintake_bea_[$l]['ProdiID'];
                        $Detail = json_decode($rekapintake_bea_[$l]['Detail'],true);
                        foreach ($Detail as $key => $value) {
                            if ($ProdiIDBea == $ProdiID && $key == $typebea) {
                                $z++;
                               $value = ($value == 0) ? '-' : $value;
                               $excel3->setCellValue($keyM[$z].$a, $value);
                               $excel3->getStyle($keyM[$z].$a)->applyFromArray($style_row);
                               $f1 = false;
                               if(array_key_exists($ProdiIDBea,$arr_total)){
                                  $arr_total[$ProdiIDBea] = $arr_total[$ProdiIDBea] + $value;
                               }
                               else
                               {
                                  $arr_total[$ProdiIDBea] = $value;
                               }
                               break;
                            }
                        }
                        
                        if  (!$f1) {
                            break;
                        }    

                    }
                }

                $a = $a + 1; 
            }

            $excel3->setCellValue('A'.$a, 'Total');
            $excel3->getStyle('A'.$a)->applyFromArray($style_col);
            for ($j=0; $j < count($getProdi); $j++) {
                 $z = $j + 1;
                 if(array_key_exists($getProdi[$j]['ID'],$arr_total)){
                    $excel3->setCellValue($keyM[$z].$a, $arr_total[$getProdi[$j]['ID']]);
                    $excel3->getStyle($keyM[$z].$a)->applyFromArray($style_col);
                 }
            }
            $a = $a + 2;

        //rekapintake_sch_
        $excel3->setCellValue('A'.$a, 'Last Updated : '.$rekapintake_sch_ls);
        $a = $a + 1;  
        for ($i=0; $i < count($rekapintake_sch_); $i++) {
            $Qty = $rekapintake_sch_[$i]['Qty']; 
            if ($Qty > 0) {
                $ProvinceID = $rekapintake_sch_[$i]['ProvinceID']; 
                $Detail = json_decode($rekapintake_sch_[$i]['Detail']);
                $g = $this->m_master->caribasedprimary('db_admission.province','ProvinceID',$ProvinceID);
                $ProvinceName = $g[0]['ProvinceName'];
                $xTotal = $a;
                $excel3->setCellValue('A'.$xTotal, $ProvinceName);
                $excel3->getStyle('A'.$xTotal)->applyFromArray($style_col);
                $excel3->setCellValue('B'.$xTotal, '');
                $excel3->getStyle('B'.$xTotal)->applyFromArray($style_col);
                $excel3->setCellValue('C'.$xTotal, $Qty);
                $excel3->getStyle('C'.$xTotal)->applyFromArray($style_col);
                $a = $a + 1;
                foreach ($Detail as $key => $value) {
                    if ($value > 0) {
                        $RegionID = $key;
                        $gg = $this->m_master->caribasedprimary('db_admission.region','RegionID',$RegionID);
                        $RegionName = $gg[0]['RegionName'];
                        $value = ($value == 0) ? '-' : $value;
                        $excel3->setCellValue('A'.$a, $RegionName);
                        $excel3->getStyle('A'.$a)->applyFromArray($style_row);
                        $excel3->setCellValue('B'.$a, $value);
                        $excel3->getStyle('B'.$a)->applyFromArray($style_row);
                        $excel3->setCellValue('C'.$a, '');
                        $excel3->getStyle('C'.$a)->applyFromArray($style_row);
                        $a = $a + 1;
                    }
                    
                }
                $a = $a + 1;
            }
        }

        $excel3->setCellValue('F'.($a+3), 'Print Date,'.$DatePrint);
        // foreach(range('B','Z') as $columnID) {
        //     $excel2->getActiveSheet()->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }

        $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
        header('Content-type: application/vnd.ms-excel'); // jalan ketika tidak menggunakan ajax
        header('Content-Disposition: attachment; filename="'.$Filaname.'"'); // jalan ketika tidak menggunakan ajax
        $objWriter->save('php://output');
    }

    public function export_excel_post_department()
    {
        $this->load->model('master/m_master');
        $this->load->model('budgeting/m_budgeting');
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $Input = (array) $this->jwt->decode($token,$key);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes

        // Panggil class PHPExcel nya
        $excel = new PHPExcel();
        // Settingan awal fil excel
        $excel->getProperties()->setCreator('Alhadi Rahman')
            ->setLastModifiedBy('Alhadi Rahman')
            ->setTitle("Podomoro University Budgeting")
            ->setSubject('Post Budgeting')
            ->setDescription('Post Budgeting')
            ->setKeywords('Post Budgeting');

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $DepartementPost = $Input['DepartementPost'];
        $DepartementPostName = substr($Input['DepartementPostName'], 0,20);
        $DepartementPost = ($DepartementPost != 'all') ? array(
                                                                array('Code' => $DepartementPost,'Name2' => $DepartementPostName,
                                                                ),
                                                              ) : $this->m_master->apiservertoserver(serverRoot.'/api/__getAllDepartementPU');
        $YearPostDepartement = $Input['YearPostDepartement'];
        $YearPostDepartementText = $Input['YearPostDepartementText'];
        $YearPostDepartementText = str_replace(' ', '', $YearPostDepartementText);
        $incsheet = 0;
        // $excel->getActiveSheet();
        for ($i=0; $i < count($DepartementPost); $i++) {
            $exc = $excel->createSheet($i);
            $exc->setTitle(substr($DepartementPost[$i]['Name2'],0,20) );
            $getDataForDom = $this->m_budgeting->getPostDepartementEx($YearPostDepartement,$DepartementPost[$i]['Code']);
            $exc->setCellValue('A1', $DepartementPost[$i]['Name2']);

            $data = $getDataForDom;
            if (count($data) > 0) {
                // group by codepost
                $st = 3;
                $strow = 4;
                $arr_wr = array();
                for ($j=0; $j < count($data); $j++) { 
                    $CodePost1 = $data[$j]['CodePost'];
                    $temp = array();
                    $temp['Post'] = $data[$j]['PostName'];
                    $temp2 = array();
                    $temp2[] = array('NameHeadAccount' => $data[$j]['NameHeadAccount'],'Cost' => 'Rp. '.number_format($data[$j]['Budget'],2,',','.'),'j' => $j,'k' => 0);
                    for ($k=$j+1; $k < count($data); $k++) { 
                       $CodePost2 = $data[$k]['CodePost'];
                       if ($CodePost1 == $CodePost2  ) {
                           $temp2[] = array('NameHeadAccount' => $data[$k]['NameHeadAccount'],'Cost' => 'Rp. '.number_format($data[$k]['Budget'],2,',','.'),'j' => $j,'k' => $k);
                       }
                       else
                       {
                        $j = $k -1;
                        break;
                       }
                       $j = $k;
                    }

                    $temp['data'] = $temp2;
                    $arr_wr[] = $temp;
                }

                // print_r($arr_wr);
                for ($k=0; $k < count($arr_wr); $k++) { 
                  $Post = $arr_wr[$k]['Post'];
                  $exc->setCellValue('A'.$st, $Post);
                  $dt = $arr_wr[$k]['data'];
                  $no = 1;
                  for ($l=0; $l < count($dt); $l++) { 
                      // add isi data
                      if ($l == 0) {
                          $exc->setCellValue('A'.$strow, 'No'); 
                          $exc->setCellValue('B'.$strow, 'Head Account'); 
                          $exc->setCellValue('C'.$strow, 'Cost'); 

                          $exc->getStyle('A'.$strow)->applyFromArray($style_col);
                          $exc->getStyle('B'.$strow)->applyFromArray($style_col);
                          $exc->getStyle('C'.$strow)->applyFromArray($style_col);
                          $strow++;
                      }
                      $exc->setCellValue('A'.$strow, $no); 
                      $exc->setCellValue('B'.$strow, $dt[$l]['NameHeadAccount'] ); 
                      $exc->setCellValue('C'.$strow, $dt[$l]['Cost']); 

                      $exc->getStyle('A'.$strow)->applyFromArray($style_row);
                      $exc->getStyle('B'.$strow)->applyFromArray($style_row);
                      $exc->getStyle('C'.$strow)->applyFromArray($style_row);
                      $strow++;
                      $no++;
                  }
                  
                  $st= $strow + 1;
                  $strow = $st +1;
                }
            }
        }
        // die();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=Alocation_head_account'.$YearPostDepartementText.'.xlsx'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}