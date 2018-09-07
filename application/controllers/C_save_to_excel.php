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
            // Settingan awal fil excel
            $excel->getProperties()->setCreator('Alhadi Rahman')
                ->setLastModifiedBy('Alhadi Rahman')
                ->setTitle("Data Karyawan Produksi")
                ->setSubject("Data Karyawan Produksi")
                ->setDescription("Rekap Data Karyawan Produksi")
                ->setKeywords("Data Karyawan Produksi");

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

            $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA KARYAWAN PRODUKSI"); // Set kolom A1 dengan tulisan "DATA KARYAWAN"
            $excel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
            $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

            // Buat header tabel nya pada baris ke 3
            $excel->setActiveSheetIndex(0)->setCellValue('A3', "NIK"); // Set kolom A3 dengan tulisan "NIK"
            $excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA");
            $excel->setActiveSheetIndex(0)->setCellValue('C3', "DIVISI");
            $excel->setActiveSheetIndex(0)->setCellValue('D3', "STATUS");
            $excel->setActiveSheetIndex(0)->setCellValue('E3', "JABATAN");
            $excel->setActiveSheetIndex(0)->setCellValue('F3', "Tgl Masuk");
            $excel->setActiveSheetIndex(0)->setCellValue('G3', "Tgl Lahir");
            $excel->setActiveSheetIndex(0)->setCellValue('H3', "Skill");

            // Apply style header yang telah kita buat tadi ke masing-masing kolom header
            $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);

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
        print_r($input);
    }

    public function export_excel_payment_received()
    {
        $token = $this->input->post('token');
        //print_r($token);
        //die();
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



}