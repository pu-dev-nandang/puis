<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_save_to_pdf3 extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('JWT');
        $this->load->library('pdf');
        $this->load->library('pdf_mc_table');

        $this->load->model('m_rest');
        $this->load->model('master/m_master');
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

    public function getDateIndonesian($date){
        $e = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? '%#d' : '%e';
        $data = strftime($e." %B %Y",strtotime($date));

        $result = str_replace('Pebruari', 'Februari', $data);

        return $result;

    }

    public function spk_or_po()
    {
        try {
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
            switch ($input['type']) {
                case 'spk':
                   $this->GeneratePDFSPK($input);
                    break;
                case 'po':
                    $this->GeneratePDFPO($input);
                    break;
                default:
                    # code...
                    break;
            }        
        } catch (Exception $e) {
            // handling orang iseng
            echo $e;
            // echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    private function GeneratePDFPO($input)
    {
        $this->load->model('master/m_master');
        $this->load->model('budgeting/m_global');
        $this->load->model('budgeting/m_pr_po');
        $Code = $input['Code'];
        $CodeReplace = str_replace('/', '-', $Code);
        $filename = '__'.$CodeReplace.'.pdf';  
        $data = array(
            'Code' => $Code,
            'auth' => 's3Cr3T-G4N', 
        );
        $key = "UAP)(*";
        $token = $this->jwt->encode($data,$key);
        $G_data_po = $this->m_master->apiservertoserver(base_url().'rest2/__Get_data_po_by_Code',$token);
        $po_create = $G_data_po['po_create'];
        $po_detail = $G_data_po['po_detail'];
        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        // $fpdf->AliasNbPages();
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        $x = 10;
        $y = 20;
        $FontIsianHeader = 8;
        $FontIsian = 7;

        // Logo
        // $fpdf->Image('./images/YPAP_logo_L.png',10,5,160);
        // Header
        $fpdf->SetXY($x,$y);
        $fpdf->SetFont('Arial','BU',12);
        $fpdf->SetX(90);
        $fpdf->Cell(70,0, 'Purchase Order', 0, 0, 'L', 0);
        $fpdf->SetFont('Arial','BI',6);
        $fpdf->Cell(0,0, 'FM-UAP/PUR/02.03 Rev.1', 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','B',$FontIsianHeader);
        $fpdf->Cell(0, 10, $Code, 0, 1, 'C', 0);

        // isi
        $fpdf->Cell(50, 5, 'YAY Pendidikan Agung Podomoro', 0, 0, 'L', 0);
        $x1 = 160;
        $fpdf->SetX($x1);
        $fpdf->SetFont('Arial','U',$FontIsianHeader);
        $fpdf->Cell(50, 5, 'Jakarta,'.$po_create[0]['CreatedAt_Indo'], 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','',$FontIsianHeader);
        $fpdf->Cell(50, 5, 'Podomoro City APL Tower, Lantai 5', 0, 1, 'L', 0);
        $fpdf->Cell(70, 5, 'Jl. Let Jend. S. Parman Kav 28, Jakarta 11470', 0, 0, 'L', 0);
        $fpdf->SetX($x1);
        $fpdf->Cell(70, 5, 'Kepada Yth :', 0, 1, 'L', 0);
        $fpdf->Cell(50, 5, 'Telp 021 29200456', 0, 0, 'L', 0);
        $fpdf->SetX($x1);
        $fpdf->SetFont('Arial','B',$FontIsianHeader);
        $fpdf->Cell(50, 5, $po_create[0]['NamaSupplier'], 0, 1, 'L', 0);
        $fpdf->SetX($x1);
        $fpdf->SetFont('Arial','',$FontIsianHeader);
        $fpdf->Cell(50, 5, $po_create[0]['PICName'].' ('.$po_create[0]['NoTelp'].')', 0, 1, 'L', 0);

        $JsonStatus = $po_create[0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
        $fpdf->Cell(50, 5,'PIC : '.$JsonStatus[0]['Name'], 0, 1, 'L', 0);
        $fpdf->Cell(50, 5,'Bersama ini kami meminta untuk dikirim barang-barang sebagai berikut :', 0, 1, 'L', 0);

        // table
        // header
        $border = 1;
        $w_no = 7;
        $w_desc = 35;
        $w_spec = 40;
        $w_date_needed = 15;
        $w_qty = 7;
        $w_pricest = 22;
        $w_disc = 13;
        $w_pph = 8;
        $w_anotcost = 18;
        $w_totalammount = 26;
        $h=8;
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array($w_no,$w_desc,$w_spec,$w_date_needed,$w_qty,$w_pricest,$w_disc,$w_pph,$w_anotcost,$w_totalammount));
        $fpdf->SetLineHeight(5);
        $fpdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C'));

        $fpdf->Row(array(
           'No',
           'Nama Barang',
           'Specification',
           'Date Needed',
           'Qty',
           'Harga',
           'Discount',
           'PPN',
           'Another Cost',
           'Total Amount',

        ));


         $h=4.4;
        // isi table
         $no = 1;
         $fpdf->SetFont('Arial','',$FontIsian);
         $total = 0;
         $fpdf->SetWidths(array($w_no,$w_desc,$w_spec,$w_date_needed,$w_qty,$w_pricest,$w_disc,$w_pph,$w_anotcost,$w_totalammount));
         $fpdf->SetLineHeight(5);
         $fpdf->SetAligns(array('C','L','L','C','C','C','C','C','C','C'));
         $total = 0;
         // $fpdf->SetFont('Arial','U',7);
         for ($i=0; $i < count($po_detail); $i++) { 
             $Spesification = '';
             $DetailCatalog = json_decode($po_detail[$i]['DetailCatalog'],true);
             foreach ($DetailCatalog as $key => $value) {
                $Spesification .= $key.' : '.$value.", ";
             }

             // if ($po_detail[$i]['Spec_add'] != '' && $po_detail[$i]['Spec_add'] != null) {
             //     $Spesification .= ",";
             //     $Spesification .= $po_detail[$i]['Spec_add'];
             // }

             $fpdf->Row(array(
                $no,
                $po_detail[$i]['Item'],
                $Spesification,
                $po_detail[$i]['DateNeeded'],
                $po_detail[$i]['QtyPR'],

                'Rp '.number_format($po_detail[$i]['UnitCost_PO'],0,',','.'),
                (int)$po_detail[$i]['Discount_PO'].'%',
                (int)$po_detail[$i]['PPN_PO'].'%',
                'Rp '.number_format($po_detail[$i]['AnotherCost'],0,',','.'),
                'Rp '.number_format($po_detail[$i]['Subtotal'],0,',','.'),

             ));
             $total += $po_detail[$i]['Subtotal'];
             $no++;
         }

         // footer table
         $y = $fpdf->GetY();
         $x__ = $w_no+$w_desc+$w_spec+$w_date_needed+$w_qty+$w_pricest+$w_pph;
         $total2= 'Rp '.number_format($total,0,',','.');
         $x = 10;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell($x__,$h,'Total',$border,0,'L',true);
         $fpdf->Cell($w_disc+$w_anotcost+$w_totalammount,$h,$total2,$border,1,'C',true);
         $fpdf->SetFont('Arial','B',$FontIsian);
         $fpdf->Cell($x__+$w_disc+$w_anotcost+$w_totalammount,$h,$po_create[0]['Notes'],$border,1,'L',true);

         if ($po_create[0]['Notes2'] != '' && $po_create[0]['Notes2'] != null) {
             // Note Another Cost
             $y = $fpdf->GetY()+3;
             $fpdf->SetXY($x,$y);
             $fpdf->SetFont('Arial','',$FontIsian);
             $fpdf->Cell(25, 5, 'Note Another Cost : ', 0, 0, 'L', 0);
             $fpdf->MultiCell(80, 5, $po_create[0]['Notes2'], 0, 1, 'L', 0);
         }

         // show terbilang
         $y = $fpdf->GetY()+5;
         $data = array(
             'bilangan' => $total,
             'auth' => 's3Cr3T-G4N', 
         );
         $key = "UAP)(*";
         $token = $this->jwt->encode($data,$key);
         $_ajax_terbilang = $this->m_master->apiservertoserver(base_url().'rest2/__ajax_terbilang',$token);
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(0,$h,'Terbilang (Rupiah) : '.$_ajax_terbilang[0].' Rupiah',0,1,'L',true);

         // approval
         $y = $fpdf->GetY()+10;
         $fpdf->SetXY($x,$y);
         $w__ = 210 / count($JsonStatus);
         $w__ = (int)$w__;
         $c__ = 0;

         // update jsonstatus 1 purchasing
         $G = $this->m_pr_po->get_approval_po('NA.4');
         $arr_g =  array(
                        'NIP' => $G[0]['NIP'],
                        'Name' => $G[0]['NamaUser'],
                        'Status' => 1,
                        'ApproveAt' => '',
                        'Representedby' => '',
                        'Visible' => $G[0]['Visible'],
                        'NameTypeDesc' => $G[0]['NameTypeDesc'],
                    );
         $JsonStatus[0] = $arr_g;
         // print_r($JsonStatus);die();
         for ($i=0; $i < count($JsonStatus); $i++) {
            if ($JsonStatus[$i]['Visible'] == 'Yes') {
                // Name
                $a_ = $c__;
                
                if ( ($a_ + $w__)<= 210) {
                    $w = $w__;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',true);
                    $c__ += $w__;
                }
                else
                {
                    // sisa
                    $w = 210 - $a_;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',true);
                }

            } 
             
         }


         $y = $fpdf->GetY()+15;
         $fpdf->SetXY($x,$y);
         $w__ = 210 / count($JsonStatus);
         $w__ = (int)$w__;
         $c__ = 0;
         $Sx = $x;
         for ($i=0; $i < count($JsonStatus); $i++) {
            if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
                
                // Name
                $a_ = $c__;
                
                if ( ($a_ + $w__)<= 210) {
                    $w = $w__;
                    if (file_exists('./uploads/signature/'.$Signatures)) {
                        // print_r('== '.$Signatures.'<br>');
                       $fpdf->Cell($w__,5,'',0,0,'L',true);
                       $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-10,15,10);
                    }
                    else
                    {
                       $fpdf->Cell($w__,5,'',0,0,'L',true);
                    }
                    // $fpdf->Cell($w__,5,'',0,0,'L',true);
                    $c__ += $w__;
                }
                else
                {
                    // sisa
                    $w = 210 - $a_;
                    // $fpdf->Cell($w__,5,'',0,0,'L',true);
                    if (file_exists('./uploads/signature/'.$Signatures)) {
                       $fpdf->Cell($w__,5,'',0,0,'L',true);
                       $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY(),$w__,5);
                    }
                    else
                    {
                       $fpdf->Cell($w__,5,'',0,0,'L',true);
                    }
                }

                $Sx += $w__;

            } 
             
         }

         // die();

         $y = $fpdf->GetY();
         $fpdf->SetXY($x,$y);
         $w__ = 210 / count($JsonStatus);
         $w__ = (int)$w__;
         $c__ = 0;
         for ($i=0; $i < count($JsonStatus); $i++) {
            if ($JsonStatus[$i]['Visible'] == 'Yes') {
                // Name
                $a_ = $c__;
                
                if ( ($a_ + $w__)<= 210) {
                    $w = $w__;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',true);
                    $c__ += $w__;
                }
                else
                {
                    // sisa
                    $w = 210 - $a_;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',true);
                }

            } 
             
         }

         $fpdf->SetFont('Arial','',$FontIsian);
         $y = $fpdf->GetY()+10;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(50,5,'No. PR : '.$po_detail[0]['PRCode'],0,0,'L',true);

         // diterima oleh vendor
         $y = $fpdf->GetY()+10;
         $fpdf->SetXY($x,$y);
         $fpdf->SetFont('Arial','B',$FontIsian);
         $fpdf->Cell(50,5,'Diterima oleh Vendor,',0,1,'L',true);

         $y = $fpdf->GetY()+20;
         $fpdf->SetXY($x,$y);
         $fpdf->SetFont('Arial','I',$FontIsian);
         $fpdf->Cell(50,3,'(Tandatangan,Nama,Stampel)',0,1,'L',true);
         $fpdf->Cell(50,3,'Note : Copi PO mohon dapat dilampirkan pada kami bersama invoice',0,1,'L',true);

        // footer
        // $fpdf->SetFont('Arial','',$FontIsian);
        // $fpdf->text(50,285,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28. Jakarta Barat 11470, Indonesia');
        // $fpdf->text(80,290,'Tlp: 021 292 00456 Fax: 021 292 00455');

        // $fpdf->SetFillColor(20,56,127);
        // $fpdf->Rect(0,294,210,3,'F');

        $fpdf->Output($filename,'I');
    }

    private function GeneratePDFSPK($input)
    {
        $this->load->model('master/m_master');
        $Code = $input['Code'];
        $CodeReplace = str_replace('/', '-', $Code);
        $filename = '__'.$CodeReplace.'.pdf';  
        $data = array(
            'Code' => $Code,
            'auth' => 's3Cr3T-G4N', 
        );
        $key = "UAP)(*";
        $token = $this->jwt->encode($data,$key);
        $G_data_po = $this->m_master->apiservertoserver(base_url().'rest2/__Get_data_po_by_Code',$token);
        $po_create = $G_data_po['po_create'];
        $po_detail = $G_data_po['po_detail'];
        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        // $fpdf->AliasNbPages();
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        $x = 10;
        $y = 30;
        $FontIsianHeader = 8;
        $FontIsian = 7;

        // Logo
        $fpdf->Image('./images/YPAP_logo_L.png',10,5,160);
        // Header
        $fpdf->SetXY($x,$y);
        $fpdf->SetFont('Arial','BU',12);
        $fpdf->Cell(0,0, 'Surat Perintah Kerja', 0, 1, 'C', 0);
        $fpdf->SetFont('Arial','B',$FontIsianHeader);
        $fpdf->Cell(0, 10, $Code, 0, 1, 'C', 0);

        // isi
            // Create Terbilang
            $data = array(
                'Date' => $po_create[0]['CreatedAt'],
                'auth' => 's3Cr3T-G4N', 
            );
            $key = "UAP)(*";
            $token = $this->jwt->encode($data,$key);
            $CreatedAt_terbilang  =$this->m_master->apiservertoserver(base_url().'rest2/__Get_spk_pembukaan',$token);
            $fpdf->SetFont('Arial','',$FontIsian);
            $fpdf->Cell(0, 10, $CreatedAt_terbilang[0], 0, 1, 'L', 0);

            // Pemberi Tugas
            $w_no = 15;
            $border = 0; 
            $w_col_tugas = 45;  
            $w_col_titik2 = 5;  
            $w_col_value = 75;
            $h=6;
            $y = 55;
            $x +=5;
            $fpdf->SetXY($x,$y); 
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell($w_no,$h,'I',$border,0,'L',true);
            $fpdf->Cell($w_col_tugas,$h,'PEMBERI TUGAS',$border,1,'L',true);

            $fpdf->SetX($x); 
            $fpdf->Cell($w_no,$h,'',$border,0,'C',true);
            $fpdf->Cell($w_col_tugas,$h,'NAMA PERUSAHAAN',$border,0,'L',true);
            $fpdf->Cell($w_col_titik2,$h,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,$h,'YAY PENDIDIKAN AGUNG PODOMORO',$border,1,'L',true);

            $fpdf->SetX($x); 
            $fpdf->Cell($w_no,$h,'',$border,0,'C',true);
            $fpdf->Cell($w_col_tugas,$h,'PENANGGUNG JAWAB',$border,0,'L',true);
            $fpdf->Cell($w_col_titik2,$h,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,$h,'SIA LILY BRAMAPUTRI & WIBOWO NGASERIN',$border,1,'L',true);

            $fpdf->SetX($x); 
            $fpdf->Cell($w_no,$h,'',$border,0,'C',true);
            $fpdf->Cell($w_col_tugas,$h,'JABATAN',$border,0,'L',true);
            $fpdf->Cell($w_col_titik2,$h,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,$h,'WAKIL BENDAHARA & KETUA YAYASAN',$border,1,'L',true);

            $fpdf->SetX($x); 
            $fpdf->Cell($w_no,$h,'',$border,0,'C',true);
            $fpdf->Cell($w_col_tugas,$h,'ALAMAT',$border,0,'L',true);
            $fpdf->Cell($w_col_titik2,$h,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,$h,'Jl.S. Parman Kav 28, Tanjung Duren Selatan, Grogol Petamburan,Jakarta Barat',$border,1,'L',true);


            // PIHAK PERTAMA
            $x -=5;
            $fpdf->SetFont('Arial','',$FontIsian);
            $fpdf->Cell(37.5, 10, 'Yang selanjutnya disebut sebagai ', 0, 0, 'L', 0);
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell(0, 10, 'PIHAK PERTAMA', 0, 1, 'L', 0);

            // PENERIMA TUGAS
            $x +=5;
            $fpdf->SetX($x); 
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell($w_no,$h,'II',$border,0,'L',true);
            $fpdf->Cell($w_col_tugas,$h,'PENERIMA TUGAS',$border,1,'L',true);

            $fpdf->SetX($x); 
            $fpdf->Cell($w_no,$h,'',$border,0,'C',true);
            $fpdf->Cell($w_col_tugas,$h,'NAMA PERUSAHAAN',$border,0,'L',true);
            $fpdf->Cell($w_col_titik2,$h,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,$h,strtoupper($po_create[0]['NamaSupplier']),$border,1,'L',true);

            $fpdf->SetX($x); 
            $fpdf->Cell($w_no,$h,'',$border,0,'C',true);
            $fpdf->Cell($w_col_tugas,$h,'PENANGGUNG JAWAB',$border,0,'L',true);
            $fpdf->Cell($w_col_titik2,$h,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,$h,strtoupper($po_create[0]['PICName']),$border,1,'L',true);

            $fpdf->SetX($x); 
            $fpdf->Cell($w_no,$h,'',$border,0,'C',true);
            $fpdf->Cell($w_col_tugas,$h,'JABATAN',$border,0,'L',true);
            $fpdf->Cell($w_col_titik2,$h,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,$h,strtoupper($po_create[0]['JabatanPIC']),$border,1,'L',true);

            $fpdf->SetX($x); 
            $fpdf->Cell($w_no,$h,'',$border,0,'C',true);
            $fpdf->Cell($w_col_tugas,$h,'ALAMAT',$border,0,'L',true);
            $fpdf->Cell($w_col_titik2,$h,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,$h,strtoupper($po_create[0]['Alamat']),$border,1,'L',true);

            // PIHAK KEDUA
            $x -=5;
            $fpdf->SetFont('Arial','',$FontIsian);
            $fpdf->Cell(37.5, 10, 'Yang selanjutnya disebut sebagai ', 0, 0, 'L', 0);
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell(0, 10, 'PIHAK KEDUA', 0, 1, 'L', 0);

            // UNTUK MENGERJAKAN
            $U_mengerjakan = $po_create[0]['JobSpk'];
                if ($U_mengerjakan == '') {
                    for ($i = 0; $i < count($po_detail); $i++) {
                        if ( $i == count($po_detail) - 1) { // jika loop data terakhir
                            if (count($po_detail) != 1) {
                                $U_mengerjakan += ' dan '+$po_detail[$i]['Item'];
                            }
                            else
                            {
                                $U_mengerjakan += $po_detail[$i]['Item'];
                            }
                            
                        }
                        else if (i==0) { // data awal
                            $U_mengerjakan += $po_detail[$i]['Item'];
                        }
                        else
                        {
                            $U_mengerjakan += ' , '+$po_detail[$i]['Item'];
                        }

                    }
                }

            $x +=5;
            $fpdf->SetX($x); 
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell($w_no,$h,'',$border,0,'L',true);
            $fpdf->Cell($w_col_tugas,$h,'UNTUK MENGERJAKAN',$border,0,'L',true);
            $fpdf->Cell($w_col_titik2,$h,':',$border,0,'C',true);
            // split untuk br
            $__w = $w_no+$w_col_tugas+$w_col_titik2+$x;
            $U_mengerjakan = nl2br($U_mengerjakan);
            $__mengerjakan = function($U_mengerjakan,$__w,$w_col_value,$border,$fpdf,$FontIsian,$h)
            {
                $arr = explode('<br />', $U_mengerjakan);
                for ($i=0; $i < count($arr); $i++) { 
                   $fpdf->SetX($__w);
                   $fpdf->SetFont('Arial','B',$FontIsian);
                   $fpdf->Cell($w_col_value,$h,trim($arr[$i]),$border,1 ,'L',true);
                }
            };

            $__mengerjakan($U_mengerjakan,$__w,$w_col_value,$border,$fpdf,$FontIsian,$h);
            // end split untuk br

            $fpdf->SetX($x);
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell($w_no,$h,'',$border,0,'L',true);
            $fpdf->Cell($w_col_tugas,$h,'Deskripsi',$border,1,'L',true);

            // table deskripsi
              /*
                first top border
                last bottom border
                else
                LR
              */
                $w1 = 40;
                $w2 = 15;
                $w3 = 35;
                $w4 = 15;
                $w5 = 35;

                $fpdf->SetX($x);
                $fpdf->Cell($w_no,$h,'',$border,0,'L',true);
                $fpdf->SetFont('Arial','',$FontIsian);
                $fpdf->Cell($w1,$h,'Item',1,0,'L',true);
                $fpdf->Cell($w2,$h,'Qty',1,0,'L',true);
                $fpdf->Cell($w3,$h,'Unit Cost',1,0,'L',true);
                $fpdf->Cell($w4,$h,'PPN(%)',1,0,'L',true);
                $fpdf->Cell($w5,$h,'Total',1,1,'L',true);

                $total = 0;
                for ($i=0; $i < count($po_detail); $i++) {
                    $fpdf->SetX($x); 
                    $fpdf->Cell($w_no,$h,'',$border,0,'L',true);
                    $fpdf->Cell($w1,$h,$po_detail[$i]['Item'],1,0,'L',true);
                    $fpdf->Cell($w2,$h,$po_detail[$i]['QtyPR'],1,0,'L',true);
                    $fpdf->Cell($w3,$h,'Rp '.number_format($po_detail[$i]['UnitCost_PO'],0,',','.'),1,0,'L',true);
                    $fpdf->Cell($w4,$h,(int)$po_detail[$i]['PPN_PO'],1,0,'L',true);
                    $fpdf->Cell($w5,$h,'Rp '.number_format($po_detail[$i]['Subtotal'],0,',','.'),1,1,'L',true);
                    $total += $po_detail[$i]['Subtotal'];
                }

            // $x -=5;
            $fpdf->SetX($x); 
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell($w_no,10,'',$border,0,'L',true);
            $fpdf->Cell($w_col_tugas, 10, 'HARGA TOTAL', 0, 0, 'L', 0);
            $fpdf->Cell($w_col_titik2,10,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,10,'Rp '.number_format($total,0 ,',','.'),$border,1,'L',true);

            $fpdf->SetX($x); 
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell($w_no,10,'',$border,0,'L',true);
            $fpdf->Cell($w_col_tugas, 5, 'CARA PEMBAYARAN', 0, 0, 'L', 0);
            $fpdf->Cell($w_col_titik2,5,':',$border,0,'C',true);
            $U_mengerjakan = nl2br($po_create[0]['Notes']);
            $__mengerjakan($U_mengerjakan,$__w,$w_col_value,$border,$fpdf,$FontIsian,5);

            $fpdf->SetX($x); 
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell($w_no,10,'',$border,0,'L',true);
            $fpdf->Cell($w_col_tugas, 5, 'SYARAT - SYARAT', 0, 0, 'L', 0);
            $fpdf->Cell($w_col_titik2,5,':',$border,0,'C',true);
            $U_mengerjakan = nl2br($po_create[0]['Notes2']);
            $__mengerjakan($U_mengerjakan,$__w,$w_col_value,$border,$fpdf,$FontIsian,5);
            
            // NO PR
            //$fpdf->SetFont('Arial','',$FontIsian);
            //$fpdf->Cell(37.5, 20, 'No.PR        :        '.$po_detail[0]['PRCode'], 0, 1, 'L', 0);
            $fpdf->SetY($fpdf->GetY() + 20 );
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell(135, 5, 'PIHAK I', 0, 0, 'L', 0);
            $fpdf->Cell(37.5, 5, 'PIHAK II', 0, 1, 'L', 0);
            $fpdf->Cell(135, 5, 'YAY PENDIDIKAN AGUNG PODOMORO', 0, 0, 'L', 0);
            $fpdf->Cell(37.5, 5, strtoupper($po_create[0]['NamaSupplier']), 0, 1, 'L', 0);
            $fpdf->SetFont('Arial','BU',$FontIsian);
            $fpdf->ln(20);
            $fpdf->Cell(135, 0, 'SIA LILY BRAMAPUTRI & WIBOWO NGASERIN', 0, 0, 'L', 0);
            $fpdf->Cell(37.5, 0, strtoupper($po_create[0]['PICName']), 0, 1, 'L', 0);
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell(135, 5, 'WAKIL BENDAHARA & KETUA YAYASAN', 0, 0, 'L', 0);
            $fpdf->Cell(37.5, 5, strtoupper($po_create[0]['JabatanPIC']), 0, 1, 'L', 0);

            // footer
            $fpdf->SetFont('Arial','',$FontIsian);
            $fpdf->text(50,285,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28. Jakarta Barat 11470, Indonesia');
            $fpdf->text(80,290,'Tlp: 021 292 00456 Fax: 021 292 00455');

            $fpdf->SetFillColor(20,56,127);
            $fpdf->Rect(0,294,210,3,'F');

        $fpdf->Output($filename,'I');
        // print_r($G_data_po);die();
    }

    public function pre_pembayaran()
    {
        $this->load->model('master/m_master');
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['dt_arr'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        if (array_key_exists('dtspb', $dt_arr)) {
            $dtspb = $dt_arr['dtspb'];
        }
        else
        {
            $dtspb = $dt_arr['payment'];
        }
        
        $Type = $dtspb[0]['Type'];
        switch ($Type) {
            case 'Spb':
               $this->GeneratePDFSpb();
                break;
            case 'Bank Advance':
                $this->GeneratePDFBankAdvance();
                break;    
            case 'Cash Advance':
                $this->GeneratePDFCashAdvance();
                break; 
            default:
                # code...
                break;
        }
        
    }

    private function GeneratePDFSpb()
    {
        $this->load->model('budgeting/m_global');
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['dt_arr'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['dtspb'];
        // print_r($dtspb);die();

        $Dataselected = $input['Dataselected'];
        $Dataselected = json_decode(json_encode($Dataselected),true);
        
        $po_data = $input['po_data']; 
        $po_data = json_decode(json_encode($po_data),true);
        $po_create = $po_data['po_create'];

        $filename = '__'.$ID_payment.'.pdf'; 

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        // $fpdf->AliasNbPages();
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;

        // Header
        $yline = $y + 3;
        $fpdf->SetXY($x,$y);
        $fpdf->SetFont('Arial','B',12);
        $fpdf->Cell(0,0, 'SURAT PERMOHONAN PEMBAYARAN', 0, 1, 'C', 0);
        $fpdf->Line(10,$yline,200,$yline);

        // isi
        $fpdf->SetFont('Arial','B',$FontIsian);
        $y += 5;
        $fpdf->SetY($y);
        $fpdf->Cell(50, $h, 'NOMOR', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$dtspb[0]['Code'] , 0, 1, 'L', 0);

        $fpdf->Cell(50, $h, 'VENDOR/SUPPLIER', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$po_create[0]['NamaSupplier'] , 0, 1, 'L', 0);

        $fpdf->Cell(50, $h, 'NO KWT/INV', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$dtspb[0]['Detail'][0]['NoInvoice'] , 0, 1, 'L', 0);

        $fpdf->Cell(50, $h, 'TANGGAL', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$this->getDateIndonesian($dtspb[0]['Detail'][0]['Datee']) , 0, 1, 'L', 0);

        $fpdf->Cell(50, $h, 'PERIHAL', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$dtspb[0]['Detail'][0]['Perihal'] , 0, 1, 'L', 0);

        $y = $fpdf->GetY()+2;
        $fpdf->Line(10,$y,200,$y);

        $fpdf->SetFont('Arial','',$FontIsian);
        $y += 5;
        $fpdf->SetY($y);
        // $fpdf->Cell(75, $h, 'Mohon dibayarkan / ditransfer kepada', 0, 0, 'L', 0);
        $fpdf->Cell(75, $h, 'Mohon dibayarkan dengan '.$dtspb[0]['Detail'][0]['TypeBayar'], 0, 0, 'L', 0);
        $fpdf->SetFont('Arial','B',$FontIsian);
        $fpdf->Cell(80, $h,$po_create[0]['NamaSupplier'] , 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(75, $h, 'No Rekening', 0, 0, 'L', 0);
        $ID_bank = $dtspb[0]['Detail'][0]['ID_bank'];
        $G_bank = $this->m_master->caribasedprimary('db_finance.bank','ID',$ID_bank);
        $fpdf->Cell(80, $h,$G_bank[0]['Name'].' No : '.$dtspb[0]['Detail'][0]['No_Rekening'] , 0, 1, 'L', 0);

        $y = $fpdf->GetY()+10;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','B',$FontIsian);
        $fpdf->Cell(50, $h, 'PEMBAYARAN', 0, 1, 'L', 0);
        $fpdf->Cell(5, $h, '-', 0, 0, 'L', 0);
        $fpdf->Cell(45, $h, 'Harga', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, '=', 0, 0, 'L', 0);
        // count left po
        $InvoiceleftPO = $dtspb[0]['InvoicePO'];
        $datadtsb = $Dataselected['dtspb'];
        for ($i=0; $i < count($datadtsb); $i++) { 
            if ($ID_payment == $datadtsb[$i]['ID'] && $i > 0) {
                if ($datadtsb[$i]['Detail'][0]['Invoice'] != null && $datadtsb[$i]['Detail'][0]['Invoice'] != 'null') {
                    for ($j=0; $j < $i ; $j++) { 
                        $InvoiceleftPO -= $datadtsb[$j]['Detail'][0]['Invoice'];
                    }
                   
                    // print_r($InvoiceleftPO.'  ====<br>');
                }
                else
                {
                    $InvoiceleftPO  -= 0;
                }
                break;
            }
            
        }
       
        $fpdf->Cell(50, $h, 'Rp '.number_format($InvoiceleftPO,2,',','.'), 0, 0, 'L', 0);
        $fpdf->Cell(30, $h, '(include PPN)', 0, 1, 'L', 0);
        $fpdf->Cell(5, $h, '-', 0, 0, 'L', 0);
        $fpdf->Cell(45, $h, $dtspb[0]['Detail'][0]['TypeInvoice'], 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, '=', 0, 0, 'L', 0);
        $fpdf->Cell(50, $h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 0, 0, 'L', 0);
        $fpdf->Cell(30, $h, '(include PPN)', 0, 1, 'L', 0);

        $xcustom = 50;
        $y = $fpdf->GetY();
        $fpdf->Line(55,$y,100,$y);
        $fpdf->Cell(5, $h, '-', 0, 0, 'L', 0);
        $fpdf->Cell(45, $h,'Sisa Pembayaran', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, '=', 0, 0, 'L', 0);
        $Sisa = $InvoiceleftPO - $dtspb[0]['Detail'][0]['Invoice'];
        $fpdf->Cell(50, $h, 'Rp '.number_format($Sisa,2,',','.'), 0, 1, 'L', 0);

        $y = $fpdf->GetY();
        $y += 5;
        $data = array(
            'bilangan' => (int)$dtspb[0]['Detail'][0]['Invoice'],
            'auth' => 's3Cr3T-G4N', 
        );
        $key = "UAP)(*";
        $token = $this->jwt->encode($data,$key);
        $_ajax_terbilang = $this->m_master->apiservertoserver(base_url().'rest2/__ajax_terbilang',$token);
        $fpdf->SetY($y);
        $fpdf->Cell(50,5, 'Terbilang (Rupiah) : '.$_ajax_terbilang[0].' Rupiah', 0, 1, 'L', 0);

        
        $JsonStatus = $dtspb[0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);

        $y = $fpdf->GetY()+20;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $Sx = $x;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,20,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,20,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

               $Sx += $w__;

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function GeneratePDFBankAdvance()
    {
        $this->load->model('budgeting/m_global');
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['dt_arr'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['dtspb'];

        $Dataselected = $input['Dataselected'];
        $Dataselected = json_decode(json_encode($Dataselected),true);
        
        $po_data = $input['po_data']; 
        $po_data = json_decode(json_encode($po_data),true);
        $po_create = $po_data['po_create'];

        $filename = '__'.$ID_payment.'.pdf'; 

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        // Logo
        $fpdf->Image('./images/logo_tr.png',10,10,50);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;
        $fpdf->SetFont('Arial','B',7);
        $fpdf->Text(150, 15, 'FM-UAP/KEU-01.  06');
        $y += 15;
        $fpdf->SetFont('Arial','B',12);
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,0, 'YAYASAN PENDIDIKAN AGUNG PODOMORO / YPAP', 0, 1, 'L', 0);
        $y += 15;
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,0, 'BANK ADVANCE FORM', 0, 1, 'C', 0);
        $y += 5;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(0,$h, 'Mohon dapat diberikan bank advance dengan perincian sebagai berikut:', 0, 1, 'L', 0);
        $fpdf->Cell(0,$h, '1.', 0, 0, 'L', 0);
        $fpdf->SetX(15);
        $fpdf->Cell(0,$h, ''.$dtspb[0]['Detail'][0]['Perihal'], 0, 1, 'L', 0);
        $fpdf->SetX($x);
        $fpdf->Cell(0,$h, '2.', 0, 0, 'L', 0);
        $fpdf->SetX(15);
        $fpdf->Cell(0,$h, 'Perincian biaya : ', 0, 0, 'L', 0);
        $fpdf->SetX(60);
        $fpdf->Cell(0,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 0, 1, 'L', 0);
        $y += 35;
        $fpdf->SetXY(15,$y);
        $fpdf->Cell(0,$h, 'Jumlah', 0, 0, 'L', 0);
         $fpdf->SetX(60);
         $fpdf->Cell(50,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 'TB', 1, 'L', 0);
         $y += 15;
         $fpdf->SetXY($x,$y);
         $h = 5;
         $fpdf->Cell(0,$h, '3.', 0, 0, 'L', 0);
         $fpdf->SetX(15);
         // $fpdf->Cell(50,$h, 'Uang yang diberikan melalui : (pilih salah satu)', 0, 1, 'L', 0);
         $fpdf->Cell(50,$h, 'Uang yang diberikan melalui : ', 0, 1, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Cash') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Tunai', 0, 1, 'L', 0);
         // $fpdf->SetX(15);
         // $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Transfer') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Transfer', 0, 1, 'L', 0);
         // $No_Rekening = '...............................................................................';
         // $Name_Penerima = '...............................................................................';
         // $Nama_Bank = '...............................................................................';
         // if ($dtspb[0]['Detail'][0]['ID_bank'] != 0) {
         //     $No_Rekening = $dtspb[0]['Detail'][0]['No_Rekening'];
         //     $Name_Penerima = $dtspb[0]['Detail'][0]['Nama_Penerima'];
         //     $ID_bank = $dtspb[0]['Detail'][0]['ID_bank'];
         //     $G_bank = $this->m_master->caribasedprimary('db_finance.bank','ID',$ID_bank);
         //     $Nama_Bank = $G_bank[0]['Name'];
         // }
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Ke rekening : ', 0, 1, 'L', 0);
         // $h = 5;
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Nama penerima : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$Name_Penerima, 0, 1, 'L', 0);

         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Bank : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$Nama_Bank, 0, 1, 'L', 0);

         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'No rekening: : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$No_Rekening, 0, 1, 'L', 0);
         $h = 10;
         $y = $fpdf->GetY()+ 5;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(50,$h,'4.', 0, 0, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(60,$h, 'Dibutuhkan pada tanggal:', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h, $this->getDateIndonesian($dtspb[0]['Detail'][0]['Date_Needed']), 0, 0, 'L', 0);
        

        $JsonStatus = $dtspb[0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);

        $y = $fpdf->GetY()+20;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $Sx = $x;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

               $Sx += $w__;

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function GeneratePDFCashAdvance()
    {
        $this->load->model('budgeting/m_global');
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['dt_arr'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['dtspb'];

        $Dataselected = $input['Dataselected'];
        $Dataselected = json_decode(json_encode($Dataselected),true);
        
        $po_data = $input['po_data']; 
        $po_data = json_decode(json_encode($po_data),true);
        $po_create = $po_data['po_create'];

        $filename = '__'.$ID_payment.'.pdf'; 

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        // Logo
        $fpdf->Image('./images/logo_tr.png',10,10,50);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;
        $fpdf->SetFont('Arial','B',12);
        $fpdf->Text(150, 15, 'FM-UAP/KEU-01.  06');
        $y += 15;
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,0, 'YAYASAN PENDIDIKAN AGUNG PODOMORO / YPAP', 0, 1, 'L', 0);
        $y += 15;
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,0, 'CASH ADVANCE FORM', 0, 1, 'C', 0);
        $y += 5;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(0,$h, 'Mohon dapat diberikan bank advance dengan perincian sebagai berikut:', 0, 1, 'L', 0);
        $fpdf->Cell(0,$h, '1.', 0, 0, 'L', 0);
        $fpdf->SetX(15);
        $fpdf->Cell(0,$h, ''.$dtspb[0]['Detail'][0]['Perihal'], 0, 1, 'L', 0);
        $fpdf->SetX($x);
        $fpdf->Cell(0,$h, '2.', 0, 0, 'L', 0);
        $fpdf->SetX(15);
        $fpdf->Cell(0,$h, 'Perincian biaya : ', 0, 0, 'L', 0);
        $fpdf->SetX(60);
        $fpdf->Cell(0,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 0, 1, 'L', 0);
        $y += 35;
        $fpdf->SetXY(15,$y);
        $fpdf->Cell(0,$h, 'Jumlah', 0, 0, 'L', 0);
         $fpdf->SetX(60);
         $fpdf->Cell(50,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 'TB', 1, 'L', 0);
         $y += 15;
         $fpdf->SetXY($x,$y);
         $h = 5;
         $fpdf->Cell(0,$h, '3.', 0, 0, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, 'Uang yang diberikan melalui : ', 0, 1, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Cash') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Tunai', 0, 1, 'L', 0);
         // $fpdf->SetX(15);
         // $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Transfer') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Transfer', 0, 1, 'L', 0);
         // $No_Rekening = '...............................................................................';
         // $Name_Penerima = '...............................................................................';
         // $Nama_Bank = '...............................................................................';
         // if ($dtspb[0]['Detail'][0]['No_Rekening'] != '' && $dtspb[0]['Detail'][0]['No_Rekening'] != null) {
         //     $No_Rekening = $dtspb[0]['Detail'][0]['No_Rekening'];
         //     $Name_Penerima = $dtspb[0]['Detail'][0]['Nama_Penerima'];
         //     $ID_bank = $dtspb[0]['Detail'][0]['ID_bank'];
         //     $G_bank = $this->m_master->caribasedprimary('db_finance.bank','ID',$ID_bank);
         //     $Nama_Bank = $G_bank[0]['Name'];
         // }
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Ke rekening : ', 0, 1, 'L', 0);
         // $h = 5;
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Nama penerima : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$Name_Penerima, 0, 1, 'L', 0);

         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Bank : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$Nama_Bank, 0, 1, 'L', 0);

         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'No rekening: : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$No_Rekening, 0, 1, 'L', 0);
         $h = 10;
         $y = $fpdf->GetY()+ 5;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(50,$h,'4.', 0, 0, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(60,$h, 'Dibutuhkan pada tanggal:', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h, $this->getDateIndonesian($dtspb[0]['Detail'][0]['Date_Needed']), 0, 0, 'L', 0);
        

        $JsonStatus = $dtspb[0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);

        $y = $fpdf->GetY()+20;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $Sx = $x;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

               $Sx += $w__;

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function GeneratePDFCashAdvance_old()
    {
        $this->load->model('budgeting/m_global');
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['dt_arr'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['dtspb'];

        $Dataselected = $input['Dataselected'];
        $Dataselected = json_decode(json_encode($Dataselected),true);
        
        $po_data = $input['po_data']; 
        $po_data = json_decode(json_encode($po_data),true);
        $po_create = $po_data['po_create'];

        $filename = '__'.$ID_payment.'.pdf'; 

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        // Logo
        $fpdf->Image('./images/logo_tr.png',10,10,50);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;

        $y += 15;
        $fpdf->SetFont('Arial','B',10);
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,$h, 'PENYELESAIAN UANG MUKA', 0, 1, 'C', 0);

        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(0,$h, 'Penyelesaian Uang Muka atas biaya  : ', 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','B',10);
        $fpdf->Cell(0,$h, 'Cash Advance Pembelian ', 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','',10);
        $fpdf->Cell(50,$h, 'Tanggal diterima uang muka', 0, 0, 'L', 0);
        $fpdf->Cell(100,$h, ':', 0, 0, 'L', 0);
        $fpdf->SetTextColor(255,0,0);
        $fpdf->Cell(40,$h, $this->getDateIndonesian($dtspb[0]['Detail'][0]['Date_Needed']), 0, 1, 'L', 0);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(50,$h, 'Uang Muka yang diterima ', 0, 0, 'L', 0);
        $fpdf->Cell(100,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);
        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->Cell(50,$h, 'Biaya ', 0, 0, 'L', 0);
        $fpdf->Cell(10,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(90,$h, 'Kegiatan : '.$dtspb[0]['Detail'][0]['Perihal'], 1, 0, 'L', 0);
        $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);
        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->Cell(150,$h, 'Total biaya ', 0, 0, 'L', 0);
        $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);

        $y = $fpdf->getY()+10;
        $fpdf->SetY($y);
        $fpdf->Cell(150,5, 'Jakarta, '.$this->getDateIndonesian($dtspb[0]['CreatedAt']), 0, 1, 'L', 0);

        $JsonStatus = $dtspb[0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function payment_user()
    {
        $token = $this->input->post('token');
        $Input = $this->getInputToken($token);
        $TypePay = $Input['TypePay'];
        switch ($TypePay) {
            case 'Petty Cash':
                $this->PdfPettyCash_User($Input);
                break;
            case 'Spb':
                $this->PdfSpb_User($Input);
                break;
            default:
            case 'Bank Advance':
                $this->PdfBA_User($Input);
                break;
            case 'Cash Advance':
                $this->PdfCA_User($Input);
                break;
            default:
                # code...
                break;
        }
    }

    public function PdfPettyCash_User($Input)
    {
        $this->load->model('budgeting/m_global');
        $ID_payment = $Input['ID_payment'];
        $DataPayment = $Input['DataPayment'];
        $DataPayment = json_decode(json_encode($DataPayment),true);
        $dt_arr = $DataPayment['payment'];
        // print_r($DataPayment);die();
         $fpdf = new Pdf_mc_table('l','mm','A5');
         $fpdf->AddPage();
         $fpdf->SetMargins(10,0,10,0);
         $x = 10;
         $y = 10;
         $FontIsianHeader = 8;
         $FontIsian = 7;
         // Logo
         $fpdf->Image('./images/YPAP_logo_petty_cash.png',7,5,50);
         // Header
         $fpdf->SetXY($x,$y);
         $fpdf->SetFont('Arial','B',12);
         $fpdf->Cell(0, 10, 'PETTY CASH VOUCHER', 0, 1, 'C', 0);
         $fpdf->Image('./images/logo_tr.png',150,5,50);

         $fpdf->SetFont('Arial','',$FontIsian);
         $Date = date('Y-m-d',strtotime($dt_arr[0]['CreatedAt']));
         $DateWr = $this->getDateIndonesian($Date);
         $fpdf->Cell(0, 10, 'Tanggal : '.$DateWr, 0, 1, 'L', 0);

         // buat table petty cash
         $w_dibayar = 80;
         $w_NomorAcc = 55;
         $w_JumlahRupiah = 55;
         $border = 1;
         $h=4.4;
         $y = $fpdf->GetY();
         $fpdf->SetXY($x,$y);
         $fpdf->SetFillColor(255, 255, 255);
         $fpdf->SetFont('Arial','B',$FontIsianHeader);
         $fpdf->Cell($w_dibayar,$h,'DIBAYAR UNTUK',$border,0,'C',true);
         $fpdf->Cell($w_NomorAcc,$h,'NOMOR ACC',$border,0,'C',true);
         $fpdf->Cell($w_JumlahRupiah,$h,'JUMLAH RUPIAH',$border,1,'C',true);

         $fpdf->SetFont('Arial','',$FontIsian);
         $fpdf->SetWidths(array($w_dibayar,$w_NomorAcc,$w_JumlahRupiah));
         $fpdf->SetLineHeight(5);
         $fpdf->SetAligns(array('L','L','C'));

         $MaxItem = 10;
         $total = $dt_arr[0]['Detail'][0]['Invoice'];
         $arr_DetailItem = $dt_arr[0]['Detail'][0]['Detail'];
         for ($i=0; $i < count($arr_DetailItem); $i++) { 
            $dibayar = $arr_DetailItem[$i]['NamaBiaya'];
            $NomorAcc = $arr_DetailItem[$i]['NomorAcc'];
            $JumlahRupiah = 'Rp '.number_format($arr_DetailItem[$i]['Invoice'],2,',','.');
            $fpdf->Row(array(
               $dibayar,
               $NomorAcc,
               $JumlahRupiah,
            ));
         }

         for ($i=0; $i < $MaxItem - count($arr_DetailItem); $i++) { 
            $fpdf->Row(array(
               '',
               '',
               '',
            ));
         }
         $y = $fpdf->GetY();
         $x__ = $w_dibayar+$w_NomorAcc;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell( ($x__-10) ,$h,'',0,0,'L',true);
         $fpdf->SetFont('Arial','B',$FontIsianHeader);
         $fpdf->Cell(10,$h,'Total',0,0,'L',true);
         $fpdf->Cell($w_JumlahRupiah,$h,'Rp '.number_format($total,2,',','.'),$border,1,'C',true);

         // show terbilang
         $y = $fpdf->GetY()+5;
         $data = array(
             'bilangan' => $total,
             'auth' => 's3Cr3T-G4N', 
         );
         $key = "UAP)(*";
         $token = $this->jwt->encode($data,$key);
         $_ajax_terbilang = $this->m_master->apiservertoserver(base_url().'rest2/__ajax_terbilang',$token);
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(0,$h,'Terbilang (Rupiah) : '.$_ajax_terbilang[0].' Rupiah',0,1,'L',true);

         $fpdf->SetFont('Arial','',$FontIsian);
         $y = $fpdf->GetY()+10;
         $fpdf->SetXY($x,$y);
         $JsonStatus = $dt_arr[0]['JsonStatus'];
         $JsonStatus = json_decode($JsonStatus,true);
         $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
         $w__ = 190 / count($JsonStatus);
         $w__ = (int)$w__;
         $c__ = 0;

         for ($i=0; $i < count($JsonStatus); $i++) {
            if ($JsonStatus[$i]['Visible'] == 'Yes') {
                // Name
                $a_ = $c__;
                
                if ( ($a_ + $w__)<= 190) {
                    $w = $w__;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',true);
                    $c__ += $w__;
                }
                else
                {
                    // sisa
                    $w = 190 - $a_;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',true);
                }

            } 
             
         }


         $y = $fpdf->GetY()+15;
         $fpdf->SetXY($x,$y);
         $w__ = 190 / count($JsonStatus);
         $w__ = (int)$w__;
         $c__ = 0;
         for ($i=0; $i < count($JsonStatus); $i++) {
            if ($JsonStatus[$i]['Visible'] == 'Yes') {
                // Name
                $a_ = $c__;
                
                if ( ($a_ + $w__)<= 190) {
                    $w = $w__;
                    $fpdf->Cell($w__,5,'',0,0,'L',true);
                    $c__ += $w__;
                }
                else
                {
                    // sisa
                    $w = 190 - $a_;
                    $fpdf->Cell($w__,5,'',0,0,'L',true);
                }

            } 
             
         }

         $y = $fpdf->GetY();
         $fpdf->SetXY($x,$y);
         $w__ = 190 / count($JsonStatus);
         $w__ = (int)$w__;
         $c__ = 0;
         for ($i=0; $i < count($JsonStatus); $i++) {
            if ($JsonStatus[$i]['Visible'] == 'Yes') {
                // Name
                $a_ = $c__;
                
                if ( ($a_ + $w__)<= 190) {
                    $w = $w__;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',true);
                    $c__ += $w__;
                }
                else
                {
                    // sisa
                    $w = 190 - $a_;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',true);
                }

            } 
             
         }

         // footer
         $fpdf->SetFillColor(20,56,127);
         $fpdf->Rect(0,145.5,210,3,'F');
         $fpdf->SetTextColor(255,255,255);
         $fpdf->SetFont('Arial','',$FontIsian);
         $fpdf->text(50,148,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28. Jakarta Barat 11470 T. 021 292 00456 F. 021 292 00455');

         $filename = '__'.'PettyCash_'.$ID_payment.'.pdf';  
         $fpdf->Output($filename,'I');
    }

    public function PdfSpb_User($Input){
        $this->load->model('budgeting/m_global');
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['DataPayment'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['payment'];

        $filename = '__'.$ID_payment.'.pdf'; 
        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        // $fpdf->AliasNbPages();
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;

        // Header
        $yline = $y + 3;
        $fpdf->SetXY($x,$y);
        $fpdf->SetFont('Arial','B',12);
        $fpdf->Cell(0,0, 'SURAT PERMOHONAN PEMBAYARAN', 0, 1, 'C', 0);
        $fpdf->Line(10,$yline,200,$yline);

        // isi
        $fpdf->SetFont('Arial','B',$FontIsian);
        $y += 5;
        $fpdf->SetY($y);
        $fpdf->Cell(50, $h, 'NOMOR', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$dtspb[0]['Code'] , 0, 1, 'L', 0);
        
        $fpdf->Cell(50, $h, 'VENDOR/SUPPLIER', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$dtspb[0]['Detail'][0]['NamaSupplier'] , 0, 1, 'L', 0);

        $fpdf->Cell(50, $h, 'NO KWT/INV', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$dtspb[0]['Detail'][0]['NoInvoice'] , 0, 1, 'L', 0);

        $fpdf->Cell(50, $h, 'TANGGAL', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$this->getDateIndonesian($dtspb[0]['Detail'][0]['Datee']) , 0, 1, 'L', 0);

        $fpdf->Cell(50, $h, 'PERIHAL', 0, 0, 'L', 0);
        $fpdf->Cell(5, $h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(80, $h,$dtspb[0]['Detail'][0]['Perihal'] , 0, 1, 'L', 0);

        $y = $fpdf->GetY()+2;
        $fpdf->Line(10,$y,200,$y);

        $fpdf->SetFont('Arial','',$FontIsian);
        $y += 5;
        $fpdf->SetY($y);
        // $fpdf->Cell(75, $h, 'Mohon dibayarkan / ditransfer kepada', 0, 0, 'L', 0);
        $fpdf->Cell(75, $h, 'Mohon dibayarkan dengan '.$dtspb[0]['Detail'][0]['TypeBayar'], 0, 0, 'L', 0);
        $fpdf->SetFont('Arial','B',$FontIsian);
        $fpdf->Cell(80, $h,$dtspb[0]['Detail'][0]['NamaSupplier'] , 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(75, $h, 'No Rekening', 0, 0, 'L', 0);
        $ID_bank = $dtspb[0]['Detail'][0]['ID_bank'];
        $G_bank = $this->m_master->caribasedprimary('db_finance.bank','ID',$ID_bank);
        $fpdf->Cell(80, $h,$G_bank[0]['Name'].' No : '.$dtspb[0]['Detail'][0]['No_Rekening'] , 0, 1, 'L', 0);
        $y = $fpdf->GetY()+5;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','B',$FontIsian);
        $fpdf->Cell(0, $h, 'PEMBAYARAN', 0, 1, 'C', 0);

        // buat table
        $w_no = 10;
        $w_dibayar = 100;
        $w_JumlahRupiah = 75;
        $border = 1;
        $h=7;
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('Arial','B',$FontIsianHeader);
        $fpdf->Cell($w_no,$h,'No',$border,0,'C',true);
        $fpdf->Cell($w_dibayar,$h,'DIBAYAR UNTUK',$border,0,'C',true);
        $fpdf->Cell($w_JumlahRupiah,$h,'JUMLAH RUPIAH',$border,1,'C',true);

        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->SetWidths(array($w_no,$w_dibayar,$w_JumlahRupiah));
        $fpdf->SetLineHeight(7);
        $fpdf->SetAligns(array('C','L','C'));

        $MaxItem = 10;
        $total = $dtspb[0]['Detail'][0]['Invoice'];
        $arr_DetailItem = $dtspb[0]['Detail'][0]['Detail'];
        $no = 1;
        for ($i=0; $i < count($arr_DetailItem); $i++) {
           $dibayar = $arr_DetailItem[$i]['NamaBiaya'];
           $JumlahRupiah = 'Rp '.number_format($arr_DetailItem[$i]['Invoice'],2,',','.');
           $fpdf->Row(array(
              $no,
              $dibayar,
              $JumlahRupiah,
           ));

           $no++;
        }
        // print_r($arr_DetailItem);die();
        $y = $fpdf->GetY();
        $x__ = $w_dibayar+$no;
        $fpdf->SetXY($x+1,$y);
        $fpdf->Cell( ($x__-4) ,$h,'',0,0,'L',true);
        $fpdf->SetFont('Arial','B',$FontIsianHeader);
        $fpdf->Cell(10,$h,'Total',0,0,'L',true);
        $fpdf->Cell($w_JumlahRupiah,$h,'Rp '.number_format($total,2,',','.'),0,1,'C',true);

        $y = $fpdf->GetY();
        $y += 5;
        $data = array(
            'bilangan' => (int)$total,
            'auth' => 's3Cr3T-G4N', 
        );
        $key = "UAP)(*";
        $token = $this->jwt->encode($data,$key);
        $_ajax_terbilang = $this->m_master->apiservertoserver(base_url().'rest2/__ajax_terbilang',$token);
        $fpdf->SetY($y);
        $fpdf->Cell(50,5, 'Terbilang (Rupiah) : '.$_ajax_terbilang[0].' Rupiah', 0, 1, 'L', 0);

        $JsonStatus = $dtspb[0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
        $y = $fpdf->GetY()+20;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $Sx = $x;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,20,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,20,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

               $Sx += $w__;

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function PdfBA_User($input)
    {
        $this->load->model('budgeting/m_global');
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['DataPayment'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['payment'];
        $filename = '__'.$ID_payment.'.pdf'; 

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        // Logo
        $fpdf->Image('./images/logo_tr.png',10,10,50);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;
        $fpdf->SetFont('Arial','B',12);
        $fpdf->Text(150, 15, 'FM-UAP/KEU-01.  06');
        $y += 15;
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,0, 'YAYASAN PENDIDIKAN AGUNG PODOMORO / YPAP', 0, 1, 'L', 0);
        $y += 15;
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,0, 'BANK ADVANCE FORM', 0, 1, 'C', 0);
        $y += 5;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(0,$h, 'Mohon dapat diberikan bank advance dengan perincian sebagai berikut:', 0, 1, 'L', 0);
        $fpdf->Cell(0,$h, '1.', 0, 0, 'L', 0);
        $fpdf->SetX(15);
        $fpdf->Cell(0,$h, 'Kegiatan : '.$dtspb[0]['Detail'][0]['Perihal'], 0, 1, 'L', 0);
        $fpdf->SetX($x);
        $fpdf->Cell(0,$h, '2.', 0, 0, 'L', 0);
        $fpdf->SetX(15);
        $fpdf->Cell(0,$h, 'Perincian biaya : ', 0, 0, 'L', 0);
        $fpdf->SetX(60);
        // buat table
        $w_dibayar = 60;
        $w_JumlahRupiah = 50;
        $border = 1;
        $h=6;
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->SetWidths(array($w_dibayar,$w_JumlahRupiah));
        $fpdf->SetLineHeight(6);
        $fpdf->SetAligns(array('C','L','C'));
        $total = $dtspb[0]['Detail'][0]['Invoice'];
        $arr_DetailItem = $dtspb[0]['Detail'][0]['Detail'];
        $no = 1;
        for ($i=0; $i < count($arr_DetailItem); $i++) {
           $dibayar = $arr_DetailItem[$i]['NamaBiaya'];
           $JumlahRupiah = 'Rp '.number_format($arr_DetailItem[$i]['Invoice'],2,',','.');
           $fpdf->Row(array(
              $dibayar,
              $JumlahRupiah,
           ));
           $fpdf->SetX(60);
           $no++;
        }

        $y = $fpdf->GetY()+5;
        $fpdf->SetXY(15,$y);
        $fpdf->Cell(0,$h, 'Jumlah', 0, 0, 'L', 0);
         $fpdf->SetX($w_dibayar+$w_JumlahRupiah+10);
         $fpdf->Cell(50,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 'TB', 1, 'L', 0);
         $h = 5;
         $y = $fpdf->GetY()+5;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(0,$h, '3.', 0, 0, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, 'Uang yang diberikan melalui : ', 0, 1, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Cash') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Tunai', 0, 1, 'L', 0);
         // $fpdf->SetX(15);
         // $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Transfer') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Transfer', 0, 1, 'L', 0);
         // $No_Rekening = '...............................................................................';
         // $Name_Penerima = '...............................................................................';
         // $Nama_Bank = '...............................................................................';
         // if ($dtspb[0]['Detail'][0]['No_Rekening'] != '' && $dtspb[0]['Detail'][0]['No_Rekening'] != null) {
         //     $No_Rekening = $dtspb[0]['Detail'][0]['No_Rekening'];
         //     $Name_Penerima = $dtspb[0]['Detail'][0]['Nama_Penerima'];
         //     $ID_bank = $dtspb[0]['Detail'][0]['ID_bank'];
         //     $G_bank = $this->m_master->caribasedprimary('db_finance.bank','ID',$ID_bank);
         //     $Nama_Bank = $G_bank[0]['Name'];
         // }
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Ke rekening : ', 0, 1, 'L', 0);
         // $h = 5;
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Nama penerima : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$Name_Penerima, 0, 1, 'L', 0);

         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Bank : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$Nama_Bank, 0, 1, 'L', 0);

         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'No rekening: : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$No_Rekening, 0, 1, 'L', 0);
         $h = 10;
         $y = $fpdf->GetY()+ 5;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(50,$h,'4.', 0, 0, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(60,$h, 'Dibutuhkan pada tanggal:', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h, $this->getDateIndonesian($dtspb[0]['Detail'][0]['Date_Needed']), 0, 0, 'L', 0);
        

        $JsonStatus = $dtspb[0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
        $y = $fpdf->GetY()+20;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $Sx = $x;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

               $Sx += $w__;

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function PdfCA_User($input)
    {
        $this->load->model('budgeting/m_global');
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['DataPayment'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['payment'];
        $filename = '__'.$ID_payment.'.pdf'; 

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        // Logo
        $fpdf->Image('./images/logo_tr.png',10,10,50);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;
        $fpdf->SetFont('Arial','B',12);
        $fpdf->Text(150, 15, 'FM-UAP/KEU-01.  06');
        $y += 15;
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,0, 'YAYASAN PENDIDIKAN AGUNG PODOMORO / YPAP', 0, 1, 'L', 0);
        $y += 15;
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,0, 'CASH ADVANCE FORM', 0, 1, 'C', 0);
        $y += 5;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(0,$h, 'Mohon dapat diberikan bank advance dengan perincian sebagai berikut:', 0, 1, 'L', 0);
        $fpdf->Cell(0,$h, '1.', 0, 0, 'L', 0);
        $fpdf->SetX(15);
        $fpdf->Cell(0,$h, 'Kegiatan : '.$dtspb[0]['Detail'][0]['Perihal'], 0, 1, 'L', 0);
        $fpdf->SetX($x);
        $fpdf->Cell(0,$h, '2.', 0, 0, 'L', 0);
        $fpdf->SetX(15);
        $fpdf->Cell(0,$h, 'Perincian biaya : ', 0, 0, 'L', 0);
        $fpdf->SetX(60);
        // buat table
        $w_dibayar = 60;
        $w_JumlahRupiah = 50;
        $border = 1;
        $h=6;
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->SetWidths(array($w_dibayar,$w_JumlahRupiah));
        $fpdf->SetLineHeight(6);
        $fpdf->SetAligns(array('C','L','C'));
        $total = $dtspb[0]['Detail'][0]['Invoice'];
        $arr_DetailItem = $dtspb[0]['Detail'][0]['Detail'];
        $no = 1;
        for ($i=0; $i < count($arr_DetailItem); $i++) {
           $dibayar = $arr_DetailItem[$i]['NamaBiaya'];
           $JumlahRupiah = 'Rp '.number_format($arr_DetailItem[$i]['Invoice'],2,',','.');
           $fpdf->Row(array(
              $dibayar,
              $JumlahRupiah,
           ));
           $fpdf->SetX(60);
           $no++;
        }

        $y = $fpdf->GetY()+5;
        $fpdf->SetXY(15,$y);
        $fpdf->Cell(0,$h, 'Jumlah', 0, 0, 'L', 0);
         $fpdf->SetX($w_dibayar+$w_JumlahRupiah+10);
         $fpdf->Cell(50,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 'TB', 1, 'L', 0);
         $h = 5;
         $y = $fpdf->GetY()+5;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(0,$h, '3.', 0, 0, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, 'Uang yang diberikan melalui : ', 0, 1, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Cash') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Tunai', 0, 1, 'L', 0);
         // $fpdf->SetX(15);
         // $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Transfer') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Transfer', 0, 1, 'L', 0);
         // $No_Rekening = '...............................................................................';
         // $Name_Penerima = '...............................................................................';
         // $Nama_Bank = '...............................................................................';
         // if ($dtspb[0]['Detail'][0]['No_Rekening'] != '' && $dtspb[0]['Detail'][0]['No_Rekening'] != null) {
         //     $No_Rekening = $dtspb[0]['Detail'][0]['No_Rekening'];
         //     $Name_Penerima = $dtspb[0]['Detail'][0]['Nama_Penerima'];
         //     $ID_bank = $dtspb[0]['Detail'][0]['ID_bank'];
         //     $G_bank = $this->m_master->caribasedprimary('db_finance.bank','ID',$ID_bank);
         //     $Nama_Bank = $G_bank[0]['Name'];
         // }
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Ke rekening : ', 0, 1, 'L', 0);
         // $h = 5;
         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Nama penerima : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$Name_Penerima, 0, 1, 'L', 0);

         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'Bank : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$Nama_Bank, 0, 1, 'L', 0);

         // $fpdf->SetX(25);
         // $fpdf->Cell(50,$h,'No rekening: : ', 0, 0, 'L', 0);
         // $fpdf->Cell(50,$h,$No_Rekening, 0, 1, 'L', 0);
         $h = 10;
         $y = $fpdf->GetY()+ 5;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(50,$h,'4.', 0, 0, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(60,$h, 'Dibutuhkan pada tanggal:', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h, $this->getDateIndonesian($dtspb[0]['Detail'][0]['Date_Needed']), 0, 0, 'L', 0);
        

        $JsonStatus = $dtspb[0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
        $y = $fpdf->GetY()+20;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $Sx = $x;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

               $Sx += $w__;

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function PdfCA_User_old($input)
    {
        $this->load->model('budgeting/m_global');
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['DataPayment'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['payment'];
        $filename = '__'.$ID_payment.'.pdf'; 

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        // Logo
        $fpdf->Image('./images/logo_tr.png',10,10,50);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;

        $y += 15;
        $fpdf->SetFont('Arial','B',10);
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,$h, 'PENYELESAIAN UANG MUKA', 0, 1, 'C', 0);

        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(0,$h, 'Penyelesaian Uang Muka atas biaya  : ', 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','B',10);
        $fpdf->Cell(0,$h, 'Cash Advance Pembelian ', 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','',10);
        $fpdf->Cell(50,$h, 'Tanggal diterima uang muka', 0, 0, 'L', 0);
        $fpdf->Cell(100,$h, ':', 0, 0, 'L', 0);
        $fpdf->SetTextColor(255,0,0);
        $fpdf->Cell(40,$h, $this->getDateIndonesian($dtspb[0]['Detail'][0]['Date_Needed']), 0, 1, 'L', 0);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(50,$h, 'Uang Muka yang diterima ', 0, 0, 'L', 0);
        $fpdf->Cell(100,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);
        $y = $fpdf->getY()+5;

        $fpdf->SetY($y);
        $fpdf->Cell(50,$h, 'Biaya ', 0, 0, 'L', 0);
        $fpdf->Cell(10,$h, ':', 0, 0, 'L', 0);

        // $fpdf->Cell(90,$h, 'Kegiatan : '.$dtspb[0]['Detail'][0]['Perihal'], 1, 0, 'L', 0);
        // $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);
        $w_dibayar = 60;
        $w_JumlahRupiah = 50;
        $border = 1;
        $h=6;
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->SetWidths(array($w_dibayar,$w_JumlahRupiah));
        $fpdf->SetLineHeight(6);
        $fpdf->SetAligns(array('C','L','C'));
        $total = $dtspb[0]['Detail'][0]['Invoice'];
        $arr_DetailItem = $dtspb[0]['Detail'][0]['Detail'];
        $no = 1;
        $fpdf->SetX(90);
        for ($i=0; $i < count($arr_DetailItem); $i++) {
           $dibayar = $arr_DetailItem[$i]['NamaBiaya'];
           $JumlahRupiah = 'Rp '.number_format($arr_DetailItem[$i]['Invoice'],2,',','.');
           $fpdf->Row(array(
              $dibayar,
              $JumlahRupiah,
           ));
           $fpdf->SetX(90);
           $no++;
        }
        
        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->Cell(150,$h, 'Total biaya ', 0, 0, 'L', 0);
        $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);

        $y = $fpdf->getY()+10;
        $fpdf->SetY($y);
        $fpdf->Cell(150,5, 'Jakarta, '.$this->getDateIndonesian($dtspb[0]['CreatedAt']), 0, 1, 'L', 0);

        $JsonStatus = $dtspb[0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function pre_pembayaran_realisasi_po()
    {
        $this->load->model('budgeting/m_global');
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['dt_arr'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['dtspb'];
        // print_r($dtspb);die();

        $Dataselected = $input['Dataselected'];
        $Dataselected = json_decode(json_encode($Dataselected),true);
        
        $po_data = $input['po_data']; 
        $po_data = json_decode(json_encode($po_data),true);
        $po_create = $po_data['po_create'];

        $filename = '__Realisasi_'.$ID_payment.'.pdf'; 

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        // Logo
        $fpdf->Image('./images/logo_tr.png',10,10,50);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;

        $y += 15;
        $fpdf->SetFont('Arial','B',10);
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,$h, 'PENYELESAIAN UANG MUKA', 0, 1, 'C', 0);

        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(0,$h, 'Penyelesaian Uang Muka atas biaya  : ', 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','B',10);
        $fpdf->Cell(0,$h, $dtspb[0]['Type'].' Pembelian ', 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','',10);
        $fpdf->Cell(50,$h, 'Tanggal diterima uang muka', 0, 0, 'L', 0);
        $fpdf->Cell(100,$h, ':', 0, 0, 'L', 0);
        $fpdf->SetTextColor(255,0,0);
        $fpdf->Cell(40,$h, $this->getDateIndonesian($dtspb[0]['FinanceAP'][0]['CreatedAt']), 0, 1, 'L', 0);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(50,$h, 'Uang Muka yang diterima ', 0, 0, 'L', 0);
        $fpdf->Cell(100,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);
        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->Cell(50,$h, 'Biaya ', 0, 0, 'L', 0);
        $fpdf->Cell(10,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(90,$h, ''.$dtspb[0]['Detail'][0]['Perihal'], 1, 0, 'L', 0);
        $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);
        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->Cell(150,$h, 'Total biaya ', 0, 0, 'L', 0);
        $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);

        $y = $fpdf->getY()+10;
        $fpdf->SetY($y);
        $fpdf->Cell(150,5, 'Jakarta, '.$this->getDateIndonesian($dtspb[0]['Detail'][0]['Realisasi'][0]['Date_Realisasi']), 0, 1, 'L', 0);

        $JsonStatus = $dtspb[0]['Detail'][0]['Realisasi'][0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $Sx = $x;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

               $Sx += $w__;

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function payment_user_realisasi()
    {
        $this->load->model('budgeting/m_global');
        $token = $this->input->post('token');
        $input = $this->getInputToken($token);
        $ID_payment = $input['ID_payment'];
        $dt_arr = $input['DataPayment'];
        $dt_arr = json_decode(json_encode($dt_arr),true);
        $dtspb = $dt_arr['payment'];
        $filename = '__Realisasi_'.$ID_payment.'.pdf'; 
        
        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        // Logo
        $fpdf->Image('./images/logo_tr.png',10,10,50);
        $x = 10;
        $y = 15;
        $FontIsianHeader = 10;
        $FontIsian = 10;
        $h = 10;

        $y += 15;
        $fpdf->SetFont('Arial','B',10);
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(0,$h, 'PENYELESAIAN UANG MUKA', 0, 1, 'C', 0);

        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->Cell(0,$h, 'Penyelesaian Uang Muka atas biaya  : ', 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','B',10);
        $fpdf->Cell(0,$h, $dtspb[0]['Type'].' Pembelian ', 0, 1, 'L', 0);
        $fpdf->SetFont('Arial','',10);
        $fpdf->Cell(50,$h, 'Tanggal diterima uang muka', 0, 0, 'L', 0);
        $fpdf->Cell(100,$h, ':', 0, 0, 'L', 0);
        $fpdf->SetTextColor(255,0,0);
        $fpdf->Cell(40,$h, $this->getDateIndonesian($dtspb[0]['FinanceAP'][0]['CreatedAt']), 0, 1, 'L', 0);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(50,$h, 'Uang Muka yang diterima ', 0, 0, 'L', 0);
        $fpdf->Cell(100,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);
        $y = $fpdf->getY()+5;
        $fpdf->SetY($y);
        $fpdf->Cell(50,$h, 'Biaya ', 0, 0, 'L', 0);
        $fpdf->Cell(10,$h, ':', 0, 0, 'L', 0);


        // $fpdf->Cell(90,$h, 'Kegiatan : '.$dtspb[0]['Detail'][0]['Perihal'], 1, 0, 'L', 0);
        // $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);
        // $y = $fpdf->getY()+5;
        // $fpdf->SetY($y);
        // $fpdf->Cell(150,$h, 'Total biaya ', 0, 0, 'L', 0);
        // $fpdf->Cell(40,$h, 'Rp '.number_format($dtspb[0]['Detail'][0]['Invoice'],2,',','.'), 1, 1, 'L', 0);
        // buat table
        $w_dibayar = 60;
        $w_JumlahRupiah = 50;
        $border = 1;
        $h=6;
        $fpdf->SetFont('Arial','',$FontIsian);
        $fpdf->SetWidths(array($w_dibayar,$w_JumlahRupiah));
        $fpdf->SetLineHeight(6);
        $fpdf->SetAligns(array('C','L','C'));
        $total = $dtspb[0]['Detail'][0]['Invoice'];
        $arr_DetailItem = $dtspb[0]['Detail'][0]['Detail'];
        $no = 1;
        $totRealisasi = 0;
        for ($i=0; $i < count($arr_DetailItem); $i++) {
           $dibayar = $arr_DetailItem[$i]['NamaBiaya'];
           $totRealisasi += $arr_DetailItem[$i]['Realisasi'][0]['InvoiceRealisasi'];
           $JumlahRupiah = 'Rp '.number_format($arr_DetailItem[$i]['Realisasi'][0]['InvoiceRealisasi'],2,',','.');
           $fpdf->Row(array(
              $dibayar,
              $JumlahRupiah,
           ));
           $fpdf->SetX(70);
           $no++;
        }

        $y = $fpdf->GetY()+5;
        $fpdf->SetXY(10,$y);
        $fpdf->Cell(0,$h, 'Jumlah', 0, 0, 'L', 0);
        $fpdf->SetX($w_dibayar+$w_JumlahRupiah+20);
        $fpdf->Cell(50,$h, 'Rp '.number_format($totRealisasi,2,',','.'), 'TB', 1, 'L', 0);

        // sisa
        $y = $fpdf->GetY()+5;
        $fpdf->SetXY(10,$y);
        $TotalInvoice = $dtspb[0]['Detail'][0]['Invoice'];
        $absInvoice = abs($TotalInvoice - $totRealisasi);
        $fpdf->Cell(0,$h, 'Lebih / Kurang', 0, 0, 'L', 0);
        $fpdf->SetX($w_dibayar+$w_JumlahRupiah+20);
        $fpdf->Cell(50,$h, 'Rp '.number_format($absInvoice,2,',','.'), 'TB', 1, 'L', 0);

        $y = $fpdf->getY()+10;
        $fpdf->SetY($y);
        $fpdf->Cell(150,5, 'Jakarta, '.$this->getDateIndonesian($dtspb[0]['Detail'][0]['Realisasi'][0]['Date_Realisasi']), 0, 1, 'L', 0);

        $JsonStatus = $dtspb[0]['Detail'][0]['Realisasi'][0]['JsonStatus'];
        $JsonStatus = json_decode($JsonStatus,true);
        $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $fpdf->SetFont('Arial','',$FontIsian);
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',0);
               }

           } 
            
        }

        $y = $fpdf->GetY()+25;
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        $Sx = $x;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   if (file_exists('./uploads/signature/'.$Signatures)) {
                       // print_r('== '.$Signatures.'<br>');
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                      $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-15,15,10);
                   }
                   else
                   {
                      $fpdf->Cell($w__,5,'',0,0,'L',0);
                   }
                   // $fpdf->Cell($w__,5,'',0,0,'L',0);
               }

               $Sx += $w__;

           } 
            
        }

        $fpdf->SetFont('Arial','B',$FontIsian);
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $w__ = 210 / count($JsonStatus);
        $w__ = (int)$w__;
        $c__ = 0;
        for ($i=0; $i < count($JsonStatus); $i++) {
           if ($JsonStatus[$i]['Visible'] == 'Yes') {
               // Name
               $a_ = $c__;
               
               if ( ($a_ + $w__)<= 210) {
                   $w = $w__;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
                   $c__ += $w__;
               }
               else
               {
                   // sisa
                   $w = 210 - $a_;
                   $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',0);
               }

           } 
            
        }

        $fpdf->Output($filename,'I');
    }

    public function realisasi_petty_cash()
    {
        $this->load->model('budgeting/m_global');
        $token = $this->input->post('token');
        $Input = $this->getInputToken($token);
        $ID_payment = $Input['ID_payment'];
        $DataPayment = $Input['dt_arr'];
        $DataPayment = json_decode(json_encode($DataPayment),true);
        if (array_key_exists('payment', $DataPayment)) {
            $dt_arr = $DataPayment['payment'];
        }
        else
        {
            $dt_arr = $DataPayment['dtspb'];
        }
        $CodePettyCash = $Input['CodePettyCash'];
        
        // print_r($DataPayment);die();
         $fpdf = new Pdf_mc_table('l','mm','A5');
         $fpdf->AddPage();
         $fpdf->SetMargins(10,0,10,0);
         $x = 10;
         $y = 10;
         $FontIsianHeader = 8;
         $FontIsian = 7;
         // Logo
         $fpdf->Image('./images/YPAP_logo_petty_cash.png',7,5,50);
         // Header
         $fpdf->SetXY($x,$y);
         $fpdf->SetFont('Arial','B',12);
         $fpdf->Cell(0, 10, 'PETTY CASH VOUCHER', 0, 1, 'C', 0);

         $fpdf->SetXY(135,0);
         $fpdf->Cell(0, 10, 'No : '.$CodePettyCash, 0, 1, 'C', 0);
         $fpdf->Image('./images/logo_tr.png',150,8,40);

         $y = $fpdf->getY()+10;
         $fpdf->SetXY($x,$y);   
         $fpdf->SetFont('Arial','',$FontIsian);
         $Date = date('Y-m-d',strtotime($dt_arr[0]['Detail'][0]['Realisasi'][0]['Date_Realisasi']));
         $DateWr = $this->getDateIndonesian($Date);
         $fpdf->Cell(140, 10, 'Tanggal : '.$DateWr, 0, 0, 'L', 0);
         $fpdf->SetFont('Arial','B',10);
         $fpdf->Cell(0, 10, 'FM-UAP/KEU.02 03', 0, 1, 'L', 0);

         // buat table petty cash
         $w_dibayar = 80;
         $w_NomorAcc = 55;
         $w_JumlahRupiah = 55;
         $border = 1;
         $h=4.4;
         $y = $fpdf->GetY();
         $fpdf->SetXY($x,$y);
         $fpdf->SetFillColor(255, 255, 255);
         $fpdf->SetFont('Arial','B',$FontIsianHeader);
         $fpdf->Cell($w_dibayar,$h,'DIBAYAR UNTUK',$border,0,'C',true);
         $fpdf->Cell($w_NomorAcc,$h,'NOMOR ACC',$border,0,'C',true);
         $fpdf->Cell($w_JumlahRupiah,$h,'JUMLAH RUPIAH',$border,1,'C',true);

         $fpdf->SetFont('Arial','',$FontIsian);
         $fpdf->SetWidths(array($w_dibayar,$w_NomorAcc,$w_JumlahRupiah));
         $fpdf->SetLineHeight(5);
         $fpdf->SetAligns(array('L','L','C'));

         $MaxItem = 10;
         $total = 0;
         $arr_DetailItem = $dt_arr[0]['Detail'][0]['Detail'];
         for ($i=0; $i < count($arr_DetailItem); $i++) { 
            $dibayar = $arr_DetailItem[$i]['NamaBiaya'];
            $NomorAcc = $arr_DetailItem[$i]['Realisasi'][0]['NomorAcc'];
            $JumlahRupiah = 'Rp '.number_format($arr_DetailItem[$i]['Realisasi'][0]['InvoiceRealisasi'],2,',','.');
            $fpdf->Row(array(
               $dibayar,
               $NomorAcc,
               $JumlahRupiah,
            ));

            $total += $arr_DetailItem[$i]['Realisasi'][0]['InvoiceRealisasi'];
         }

         for ($i=0; $i < $MaxItem - count($arr_DetailItem); $i++) { 
            $fpdf->Row(array(
               '',
               '',
               '',
            ));
         }
         $y = $fpdf->GetY();
         $x__ = $w_dibayar+$w_NomorAcc;
         $fpdf->SetXY($x,$y);
         $fpdf->Cell( ($x__-10) ,$h,'',0,0,'L',true);
         $fpdf->SetFont('Arial','B',$FontIsianHeader);
         $fpdf->Cell(10,$h,'Total',0,0,'L',true);
         $fpdf->Cell($w_JumlahRupiah,$h,'Rp '.number_format($total,2,',','.'),$border,1,'C',true);

         // show terbilang
         $y = $fpdf->GetY()+5;
         $data = array(
             'bilangan' => $total,
             'auth' => 's3Cr3T-G4N', 
         );
         $key = "UAP)(*";
         $token = $this->jwt->encode($data,$key);
         $_ajax_terbilang = $this->m_master->apiservertoserver(base_url().'rest2/__ajax_terbilang',$token);
         $fpdf->SetXY($x,$y);
         $fpdf->Cell(0,$h,'Terbilang (Rupiah) : '.$_ajax_terbilang[0].' Rupiah',0,1,'L',true);

         $fpdf->SetFont('Arial','',$FontIsian);
         $y = $fpdf->GetY()+10;
         $fpdf->SetXY($x,$y);
         $JsonStatus = $dt_arr[0]['Detail'][0]['Realisasi'][0]['JsonStatus'];
         $JsonStatus = json_decode($JsonStatus,true);
         // $JsonStatus = $this->m_global->FilteringDoubleApproval($JsonStatus);
         $w__ = 190 / count($JsonStatus);
         $w__ = (int)$w__;
         $c__ = 0;

         // json_modify
             $arr_temp = array();
             for ($i=1; $i < count($JsonStatus); $i++) {
                 if ($i == count($JsonStatus) - 1 ) {
                     $JsonStatus[$i]['NameTypeDesc'] = 'DIPERIKSA OLEH';
                     $JsonStatus[$i]['Visible'] = 'Yes';
                 }
                 else{
                    $JsonStatus[$i]['NameTypeDesc'] = 'DISETUJUI OLEH';
                 }
                 
                 $arr_temp[] = $JsonStatus[$i];
             }
             $JsonStatus[0]['NameTypeDesc'] = 'DITERIMA OLEH';
             $arr_temp[] = $JsonStatus[0]; 

         $JsonStatus = $arr_temp;
         // print_r($JsonStatus);die(); 
         for ($i=0; $i < count($JsonStatus); $i++) {
            if ($JsonStatus[$i]['Visible'] == 'Yes') {
                // Name
                $a_ = $c__;
                
                if ( ($a_ + $w__)<= 190) {
                    $w = $w__;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',true);
                    $c__ += $w__;
                }
                else
                {
                    // sisa
                    $w = 190 - $a_;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['NameTypeDesc'],0,0,'L',true);
                }

            } 
             
         }


         $y = $fpdf->GetY()+15;
         $fpdf->SetXY($x,$y);
         $w__ = 190 / count($JsonStatus);
         $w__ = (int)$w__;
         $c__ = 0;
         $Sx = $x;
         for ($i=0; $i < count($JsonStatus); $i++) {
            if ($JsonStatus[$i]['Visible'] == 'Yes') {
                $Approver = $JsonStatus[$i]['NIP'];
                $G_CreatedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$Approver);
                $Signatures = $G_CreatedBy[0]['Signatures'];
                // Name
                $a_ = $c__;
                
                if ( ($a_ + $w__)<= 190) {
                    $w = $w__;
                    if (file_exists('./uploads/signature/'.$Signatures) && $JsonStatus[$i]['Status'] == 1 ) {
                        // print_r('== '.$Signatures.'<br>');
                       $fpdf->Cell($w__,5,'',0,0,'L',true);
                       $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-10,15,10);
                    }
                    else
                    {
                       $fpdf->Cell($w__,5,'',0,0,'L',true);
                    }
                    // $fpdf->Cell($w__,5,'',0,0,'L',true);
                    $c__ += $w__;
                }
                else
                {
                    // sisa
                    $w = 190 - $a_;
                    if (file_exists('./uploads/signature/'.$Signatures) && $JsonStatus[$i]['Status'] == 1 ) {
                        // print_r('== '.$Signatures.'<br>');
                       $fpdf->Cell($w__,5,'',0,0,'L',true);
                       $fpdf->Image('./uploads/signature/'.$Signatures,$Sx,$fpdf->GetY()-10,15,10);
                    }
                    else
                    {
                       $fpdf->Cell($w__,5,'',0,0,'L',true);
                    }
                    // $fpdf->Cell($w__,5,'',0,0,'L',true);
                }
                $Sx += $w__;

            } 
             
         }

         $y = $fpdf->GetY();
         $fpdf->SetXY($x,$y);
         $w__ = 190 / count($JsonStatus);
         $w__ = (int)$w__;
         $c__ = 0;
         for ($i=0; $i < count($JsonStatus); $i++) {
            if ($JsonStatus[$i]['Visible'] == 'Yes') {
                // Name
                $a_ = $c__;
                
                if ( ($a_ + $w__)<= 190) {
                    $w = $w__;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',true);
                    $c__ += $w__;
                }
                else
                {
                    // sisa
                    $w = 190 - $a_;
                    $fpdf->Cell($w__,5,$JsonStatus[$i]['Name'],0,0,'L',true);
                }

            } 
             
         }

         // footer
         $fpdf->SetFillColor(20,56,127);
         $fpdf->Rect(0,145.5,210,3,'F');
         $fpdf->SetTextColor(255,255,255);
         $fpdf->SetFont('Arial','',$FontIsian);
         $fpdf->text(50,148,'APL Tower Lt. 5, Podomoro City Jln. LetJend. S. Parman Kav. 28. Jakarta Barat 11470 T. 021 292 00456 F. 021 292 00455');

         $filename = $CodePettyCash.'__Realisasi_'.'PettyCash_'.$ID_payment.'.pdf';  
         $fpdf->Output($filename,'I');
    }

    public function print_akses_karyawan()
    {
        $token = $this->input->post('token');
        $Input = $this->getInputToken($token);
        $UsernamePC = $Input['UsernamePC'];
        $UsernamePCam = $Input['UsernamePCam'];
        $PasswordFill = $Input['PasswordFill'];
        $EmailPUFill = $Input['EmailPUFill'];
        $filename = 'Data_'.$UsernamePCam.'.pdf';

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(10,0,10,0);
        // Logo
        $fpdf->Image('./images/logo_tr.png',10,10,50);
        $x = 10;
        $y = 30;
        $FontIsian = 10;
        $fpdf->SetFont('Arial','B',$FontIsian);
        $h = 10;
        $fpdf->SetXY($x,$y);
        $fpdf->Cell(50,$h, 'Username PC ', 0, 0, 'L', 0);
        $fpdf->Cell(10,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(50,$h, $UsernamePC, 0, 1, 'L', 0);

        $fpdf->Cell(50,$h, 'Username PCam ', 0, 0, 'L', 0);
        $fpdf->Cell(10,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(50,$h, $UsernamePCam, 0, 1, 'L', 0);

        $fpdf->Cell(50,$h, 'Password ', 0, 0, 'L', 0);
        $fpdf->Cell(10,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(50,$h, $PasswordFill, 0, 1, 'L', 0);

        $fpdf->Cell(50,$h, 'Email PU ', 0, 0, 'L', 0);
        $fpdf->Cell(10,$h, ':', 0, 0, 'L', 0);
        $fpdf->Cell(50,$h, $EmailPUFill, 0, 1, 'L', 0);

        $fpdf->Output($filename,'I');
    }


    public function bap_online(){

        $data_arr = $this->getInputToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhQ291cnNlIjp7Ik5JUCI6IjMxMTUwMDYiLCJOYW1lIjoiVmluY2VudCBTeWx2ZXN0ZXIgTGVld2VsbHluIiwiUHJvZGlJRCI6IjUiLCJQcm9kaU5hbWUiOiJCaXNuaXMgUGVyaG90ZWxhbiIsIkNvdXJzZSI6IkRpdmlzaSBLYW1hciAtIFRhdGEgR3JhaGEgVGVvcmkiLCJDcmVkaXQiOiIyIiwiQ2xhc3NHcm91cCI6IjA1MUQiLCJTZW1lc3Rlck5hbWUiOiIyMDE5LzIwMjAgR2FuamlsIiwiVG90YWxTdHVkZW50IjoiMjkiLCJJRF9BdHRkIjoiOTE0In0sImRldGFpbHNCQVAiOlt7IlByZXNlbnQiOjI5LCJBYnNlbnQiOjAsIkxlY3R1cmVyIjpbeyJJRCI6Ijk2ODciLCJJRF9BdHRkIjoiOTE0IiwiTWVldCI6IjEiLCJOSVAiOiIzMTE1MDA2IiwiRGF0ZSI6IjIwMTktMDgtMjMiLCJJbiI6IjExOjI3OjExIiwiT3V0IjoiMTE6NDA6MDAiLCJNb2RpZnlCeSI6bnVsbCwiTW9kaWZ5QXQiOm51bGwsIklQX1B1YmxpYyI6bnVsbCwiSVBfUHJpdmF0ZSI6bnVsbCwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIkJBUCI6W3siSUQiOiIyNDI3IiwiSURfQXR0ZCI6IjkxNCIsIk5JUCI6IjMxMTUwMDYiLCJTZXNpIjoiMSIsIkRhdGUiOm51bGwsIlN1YmplY3QiOiJNZW1haGFtaSBkYW4gbWVueWVwYWthdGkgUlBTIHNlcnRhIEtvbnRyYWsgUGVya3VsaWFoYW4uXG5cbk1hbXB1IG1lbmplbGFza2FuIHBlcmFuIERlcGFydGVtZW4gVGF0YSBHcmFoYSBkYWxhbSBzZWJ1YWggaG90ZWwiLCJNYXRlcmlhbCI6ImEuXHRSUFMgJiBLb250cmFrIFBlcmt1bGlhaGFuXG5iLlx0TWFydGluLCBSb2JlcnQsIFByb2Zlc3Npb25hbCBNYW5hZ2VtZW50IG9mIEhvdXNla2VlcGluZyBPcGVyYXRpb25zLiBJU0JOOiAwNDcxMTk4NjI1XG5jLlx0SG91c2VrZWVwaW5nIE1hbmFnZW1lbnQuIElTQk46IDA0NzEyNTE4OTUiLCJEZXNjcmlwdGlvbiI6ImEuXHRNZW1haGFtaSB0dWp1YW4sIG1hdGVyaSwgcHJvc2VzLCB0dWdhcywgc3VtYmVyLCBwZW5pbGFpYW4gZGFuIGhhbC1oYWwgbGFpbiBkYWxhbSBwZXJrdWxpYWhhblxuYi5cdERhcGF0IG1lbmplbGFza2FuIHBlcmFuIERlcGFydGVtZW4gVGF0YSBHcmFoYSBkYWxhbSBzZWJ1YWggSG90ZWxcbmMuXHREYXBhdCBtZW5qZWxhc2thbiBwZXJhbiBkYW4gZnVnbnNpIEV4ZWN1dGl2ZSBIb3VzZWtlZXBlciBkYWxhbSBzZWJ1YWggaG90ZWwiLCJQcmVzZW50IjpudWxsLCJBYnNlbnQiOm51bGwsIlN0YXJ0IjpudWxsLCJFbmQiOm51bGwsIlN0dWRlbnRTaWduQnkiOiIzMTE5MDAyOCIsIlN0dWRlbnRTaWduQXQiOiIyMDE5LTEyLTA2IDEwOjA4OjQ2IiwiUmV2aWV3IjoiU2VjYXJhIGtlc2VsdXJ1aGFuIFdlZWsgMSAtIDcgc3VkYWggc2VzdWFpIGRlbmdhbiBCQVAuIERpcGVydGFoYW5rYW4geWEuLi4hIiwiUmV2aWV3ZWRCeSI6IjMxMTUwMDYiLCJSZXZpZXdlZEF0IjoiMjAxOS0xMi0xNSAxNzowMDoyOCIsIkluc2VydEJ5IjoiMzExNTAwNiIsIkluc2VydEF0IjoiMjAxOS0wOS0yNiAwNjowODo0NiIsIlVwZGF0ZUJ5IjpudWxsLCJVcGRhdGVBdCI6bnVsbCwiU3R1ZGVudCI6IkNhdGhlcmluZSBWYWxlbmNpYSBDaHJpc3RpYW5pIiwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIlN0YXR1c0VkaXQiOiIwIn0seyJQcmVzZW50IjoyOSwiQWJzZW50IjowLCJMZWN0dXJlciI6W3siSUQiOiIxMDAxMiIsIklEX0F0dGQiOiI5MTQiLCJNZWV0IjoiMiIsIk5JUCI6IjMxMTUwMDYiLCJEYXRlIjoiMjAxOS0wOC0zMCIsIkluIjoiMTA6MDU6NDIiLCJPdXQiOiIxMTo0MDowMCIsIk1vZGlmeUJ5IjpudWxsLCJNb2RpZnlBdCI6bnVsbCwiSVBfUHVibGljIjpudWxsLCJJUF9Qcml2YXRlIjpudWxsLCJMZWN0dXJlciI6IlZpbmNlbnQgU3lsdmVzdGVyIExlZXdlbGx5biJ9XSwiQkFQIjpbeyJJRCI6IjQzMzMiLCJJRF9BdHRkIjoiOTE0IiwiTklQIjoiMzExNTAwNiIsIlNlc2kiOiIyIiwiRGF0ZSI6bnVsbCwiU3ViamVjdCI6Ik1hbXB1IG1lbmplbGFza2FuIGRhbiBtZW55dXN1biByZW5jYW5hIHVudHVrIG1lbWJ1a2EgaG90ZWwgYmFydSwgbWVueXVzdW4gc3RydWt0dXIgb3JnYW5pc2FzaSwgdXJhaWFuIHR1Z2FzIHNlcnRhIHJlbmNhbmEgcmVrcnV0bWVuIHN0YWYgZGVwYXJ0ZW1lbiBUYXRhIEdyYWhhIiwiTWF0ZXJpYWwiOiJhLlx0TWFydGluLCBSb2JlcnQsIFByb2Zlc3Npb25hbCBNYW5hZ2VtZW50IG9mIEhvdXNla2VlcGluZyBPcGVyYXRpb25zLiBJU0JOOiAwNDcxMTk4NjI1XG5iLlx0SG91c2VrZWVwaW5nIE1hbmFnZW1lbnQuIiwiRGVzY3JpcHRpb24iOiJhLiBEYXBhdCBtZW5qZWxhc2thbiBwZXJhbiBFeGVjdXRpdmUgSG91c2VrZWVwZXIgZGFsYW0gcGVtYnVrYWFuIGhvdGVsIGJhcnUsXG5cbmIuIERhcGF0IG1lbWJ1YXQgSG91c2UgQnJlYWtvdXQgUGxhbixcblxuYy5EYXBhdCBtZW55dXN1biBzdHJ1a3R1ciBPcmdhbmlzYXNpIHNlcnRhIHVyYWlhbiB0dWdhcyxcblxuZC5EYXBhdCBtZW55dXN1biByZW5jYW5hIHJla3J1dG1lbiBEZXBhcnRlbWVuIFRhdGEgR3JhaGEiLCJQcmVzZW50IjpudWxsLCJBYnNlbnQiOm51bGwsIlN0YXJ0IjpudWxsLCJFbmQiOm51bGwsIlN0dWRlbnRTaWduQnkiOiIzMTE5MDAyOCIsIlN0dWRlbnRTaWduQXQiOiIyMDE5LTEyLTA2IDEwOjA5OjAxIiwiUmV2aWV3IjoiU2VjYXJhIGtlc2VsdXJ1aGFuIFdlZWsgMSAtIDcgc3VkYWggc2VzdWFpIGRlbmdhbiBCQVAuIERpcGVydGFoYW5rYW4geWEuLi4hIiwiUmV2aWV3ZWRCeSI6IjMxMTUwMDYiLCJSZXZpZXdlZEF0IjoiMjAxOS0xMi0xNSAxNzowMDoyOCIsIkluc2VydEJ5IjoiMzExNTAwNiIsIkluc2VydEF0IjoiMjAxOS0xMi0wMyAwODoxNzo1MyIsIlVwZGF0ZUJ5IjoiMzExNTAwNiIsIlVwZGF0ZUF0IjoiMjAxOS0xMi0wMyAwODoxODozMyIsIlN0dWRlbnQiOiJDYXRoZXJpbmUgVmFsZW5jaWEgQ2hyaXN0aWFuaSIsIkxlY3R1cmVyIjoiVmluY2VudCBTeWx2ZXN0ZXIgTGVld2VsbHluIn1dLCJTdGF0dXNFZGl0IjoiMCJ9LHsiUHJlc2VudCI6MjksIkFic2VudCI6MCwiTGVjdHVyZXIiOlt7IklEIjoiMTA3MDAiLCJJRF9BdHRkIjoiOTE0IiwiTWVldCI6IjMiLCJOSVAiOiIzMTE1MDA2IiwiRGF0ZSI6IjIwMTktMDktMDYiLCJJbiI6IjEwOjAwOjAwIiwiT3V0IjoiMTE6MjA6MDAiLCJNb2RpZnlCeSI6bnVsbCwiTW9kaWZ5QXQiOm51bGwsIklQX1B1YmxpYyI6bnVsbCwiSVBfUHJpdmF0ZSI6bnVsbCwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIkJBUCI6W3siSUQiOiI0MzM1IiwiSURfQXR0ZCI6IjkxNCIsIk5JUCI6IjMxMTUwMDYiLCJTZXNpIjoiMyIsIkRhdGUiOm51bGwsIlN1YmplY3QiOiJNYW1wdSBtZW5lbnR1a2FuIGp1bWxhaCBzdGFmIERlcGFydGVtZW4gVGF0YSBHcmFoYSB5YW5nIGFrYW4gZGlyZWtydXQgZGFuIGRhcGF0IG1lbmdhdHVyIGphZHdhbCBrZXJqYSBzdGFmZiB5YW5nIGFkYSIsIk1hdGVyaWFsIjoiYS5cdE1hcnRpbiwgUm9iZXJ0LCBQcm9mZXNzaW9uYWwgTWFuYWdlbWVudCBvZiBIb3VzZWtlZXBpbmcgT3BlcmF0aW9ucy4gSVNCTjogMDQ3MTE5ODYyNVxuYi5cdEhvdXNla2VlcGluZyBNYW5hZ2VtZW50LiBJU0JOOiAwNDcxMjUxODk1IiwiRGVzY3JpcHRpb24iOiJhLlx0RGFwYXQgbWVuZ2hpdHVuZyBqdW1sYWgga2VidXR1aGFuIGthcnlhd2FuXG5iLlx0RGFwYXQgbWVueXVzdW4gamFkd2FsIGtlcmphIHN0YWYiLCJQcmVzZW50IjpudWxsLCJBYnNlbnQiOm51bGwsIlN0YXJ0IjpudWxsLCJFbmQiOm51bGwsIlN0dWRlbnRTaWduQnkiOiIzMTE5MDAyOCIsIlN0dWRlbnRTaWduQXQiOiIyMDE5LTEyLTA2IDEwOjA5OjE4IiwiUmV2aWV3IjoiU2VjYXJhIGtlc2VsdXJ1aGFuIFdlZWsgMSAtIDcgc3VkYWggc2VzdWFpIGRlbmdhbiBCQVAuIERpcGVydGFoYW5rYW4geWEuLi4hIiwiUmV2aWV3ZWRCeSI6IjMxMTUwMDYiLCJSZXZpZXdlZEF0IjoiMjAxOS0xMi0xNSAxNzowMDoyOCIsIkluc2VydEJ5IjoiMzExNTAwNiIsIkluc2VydEF0IjoiMjAxOS0xMi0wMyAwODoxOToxMCIsIlVwZGF0ZUJ5IjpudWxsLCJVcGRhdGVBdCI6bnVsbCwiU3R1ZGVudCI6IkNhdGhlcmluZSBWYWxlbmNpYSBDaHJpc3RpYW5pIiwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIlN0YXR1c0VkaXQiOiIwIn0seyJQcmVzZW50IjoyOSwiQWJzZW50IjowLCJMZWN0dXJlciI6W3siSUQiOiIxMTA0MyIsIklEX0F0dGQiOiI5MTQiLCJNZWV0IjoiNCIsIk5JUCI6IjMxMTUwMDYiLCJEYXRlIjoiMjAxOS0wOS0xMyIsIkluIjoiMTA6MDA6MDAiLCJPdXQiOiIxMToyMDowMCIsIk1vZGlmeUJ5IjpudWxsLCJNb2RpZnlBdCI6bnVsbCwiSVBfUHVibGljIjpudWxsLCJJUF9Qcml2YXRlIjpudWxsLCJMZWN0dXJlciI6IlZpbmNlbnQgU3lsdmVzdGVyIExlZXdlbGx5biJ9XSwiQkFQIjpbeyJJRCI6IjQzMzYiLCJJRF9BdHRkIjoiOTE0IiwiTklQIjoiMzExNTAwNiIsIlNlc2kiOiI0IiwiRGF0ZSI6bnVsbCwiU3ViamVjdCI6Ik1hbXB1IG1lbnl1c3VuIHBlcmVuY2FuYWFuIEFuZ2dhcmFuIHByZS1vcGVuaW5nLCB5YW5nIGJlcmh1YnVuZ2FuIGRlbmdhbiBwZXJsZW5na2FwYW4gZGFuIHBlcmFsYXRhbiBrYW1hciIsIk1hdGVyaWFsIjoiYS5cdE1hcnRpbiwgUm9iZXJ0LCBQcm9mZXNzaW9uYWwgTWFuYWdlbWVudCBvZiBIb3VzZWtlZXBpbmcgT3BlcmF0aW9ucy4gSVNCTjogMDQ3MTE5ODYyNVxuYi5cdEhvdXNla2VlcGluZyBNYW5hZ2VtZW50LiBJU0JOOiAwNDcxMjUxODk1IiwiRGVzY3JpcHRpb24iOiJhLlx0RGFwYXQgbWVueXVzdW4gYW5nZ2FyYW4gcHJlLW9wZW5pbmdcbmIuXHREYXBhdCBtZW55dXN1biByZW5jYW5hIHBlbWlsaWhhbiBkYW4gcGVtYmVsaWFuIHBlcmFsYXRhbiBkYW4gcGVybGVuZ2thcGFuIGthbWFyIHRhbXUiLCJQcmVzZW50IjpudWxsLCJBYnNlbnQiOm51bGwsIlN0YXJ0IjpudWxsLCJFbmQiOm51bGwsIlN0dWRlbnRTaWduQnkiOiIzMTE5MDAyOCIsIlN0dWRlbnRTaWduQXQiOiIyMDE5LTEyLTA2IDEwOjA5OjMyIiwiUmV2aWV3IjoiU2VjYXJhIGtlc2VsdXJ1aGFuIFdlZWsgMSAtIDcgc3VkYWggc2VzdWFpIGRlbmdhbiBCQVAuIERpcGVydGFoYW5rYW4geWEuLi4hIiwiUmV2aWV3ZWRCeSI6IjMxMTUwMDYiLCJSZXZpZXdlZEF0IjoiMjAxOS0xMi0xNSAxNzowMDoyOCIsIkluc2VydEJ5IjoiMzExNTAwNiIsIkluc2VydEF0IjoiMjAxOS0xMi0wMyAwODoxOTo0MSIsIlVwZGF0ZUJ5IjpudWxsLCJVcGRhdGVBdCI6bnVsbCwiU3R1ZGVudCI6IkNhdGhlcmluZSBWYWxlbmNpYSBDaHJpc3RpYW5pIiwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIlN0YXR1c0VkaXQiOiIwIn0seyJQcmVzZW50IjoyNywiQWJzZW50IjoyLCJMZWN0dXJlciI6W3siSUQiOiIxMTI0MiIsIklEX0F0dGQiOiI5MTQiLCJNZWV0IjoiNSIsIk5JUCI6IjMxMTUwMDYiLCJEYXRlIjoiMjAxOS0wOS0yNSIsIkluIjoiMTA6MTY6NTAiLCJPdXQiOiIxMTo0MDowMCIsIk1vZGlmeUJ5IjpudWxsLCJNb2RpZnlBdCI6bnVsbCwiSVBfUHVibGljIjpudWxsLCJJUF9Qcml2YXRlIjpudWxsLCJMZWN0dXJlciI6IlZpbmNlbnQgU3lsdmVzdGVyIExlZXdlbGx5biJ9XSwiQkFQIjpbeyJJRCI6IjQzMzciLCJJRF9BdHRkIjoiOTE0IiwiTklQIjoiMzExNTAwNiIsIlNlc2kiOiI1IiwiRGF0ZSI6bnVsbCwiU3ViamVjdCI6Ik1hbXB1IG1lbnl1c3VuIHBlcmVuY2FuYWFuIHVudHVrIHBlbWJlbGlhbiBkYW4gcGVyYXdhdGFuIGxhbnRhaSwgZGluZGluZyBkYW4gamVuZGVsYSIsIk1hdGVyaWFsIjoiYS5cdE1hcnRpbiwgUm9iZXJ0LCBQcm9mZXNzaW9uYWwgTWFuYWdlbWVudCBvZiBIb3VzZWtlZXBpbmcgT3BlcmF0aW9ucy4gSVNCTjogMDQ3MTE5ODYyNVxuYi5cdEhvdXNla2VlcGluZyBNYW5hZ2VtZW50LiBJU0JOOiAwNDcxMjUxODk1IiwiRGVzY3JpcHRpb24iOiJhLlx0RGFwYXQgbWVuamVsYXNrYW4gamVuaXMgYmFoYW4gbGFudGFpLCBkaW5kaW5nIGRhbiBqZW5kZWxhLlxuYi5cdERhcGF0IG1lbmplbGFza2FuIGNhcmEgcGVyYXdhdGFuIGxhbnRhaSwgZGluZGluZyBkYW4gamVuZGVsYS5cbmMuXHREYXBhdCAgbWVueXVzdW4gcmVuY2FuYSBwZW1iZWxpYW4gZGFuIHBlcmF3YXRhbiBiYWhhbiBsYW50YWksIGRpbmRpbmcgZGFuIGplbmRlbGEuIiwiUHJlc2VudCI6bnVsbCwiQWJzZW50IjpudWxsLCJTdGFydCI6bnVsbCwiRW5kIjpudWxsLCJTdHVkZW50U2lnbkJ5IjoiMzExOTAwMjgiLCJTdHVkZW50U2lnbkF0IjoiMjAxOS0xMi0wNiAxMDowOTo0NCIsIlJldmlldyI6IlNlY2FyYSBrZXNlbHVydWhhbiBXZWVrIDEgLSA3IHN1ZGFoIHNlc3VhaSBkZW5nYW4gQkFQLiBEaXBlcnRhaGFua2FuIHlhLi4uISIsIlJldmlld2VkQnkiOiIzMTE1MDA2IiwiUmV2aWV3ZWRBdCI6IjIwMTktMTItMTUgMTc6MDA6MjgiLCJJbnNlcnRCeSI6IjMxMTUwMDYiLCJJbnNlcnRBdCI6IjIwMTktMTItMDMgMDg6MjA6MTAiLCJVcGRhdGVCeSI6IjMxMTUwMDYiLCJVcGRhdGVBdCI6IjIwMTktMTItMDMgMDg6MjA6MjEiLCJTdHVkZW50IjoiQ2F0aGVyaW5lIFZhbGVuY2lhIENocmlzdGlhbmkiLCJMZWN0dXJlciI6IlZpbmNlbnQgU3lsdmVzdGVyIExlZXdlbGx5biJ9XSwiU3RhdHVzRWRpdCI6IjAifSx7IlByZXNlbnQiOjI5LCJBYnNlbnQiOjAsIkxlY3R1cmVyIjpbeyJJRCI6IjExMjA4IiwiSURfQXR0ZCI6IjkxNCIsIk1lZXQiOiI2IiwiTklQIjoiMzExNTAwNiIsIkRhdGUiOiIyMDE5LTA5LTIwIiwiSW4iOiIxMDowMDowMCIsIk91dCI6IjExOjQwOjAwIiwiTW9kaWZ5QnkiOm51bGwsIk1vZGlmeUF0IjpudWxsLCJJUF9QdWJsaWMiOm51bGwsIklQX1ByaXZhdGUiOm51bGwsIkxlY3R1cmVyIjoiVmluY2VudCBTeWx2ZXN0ZXIgTGVld2VsbHluIn1dLCJCQVAiOlt7IklEIjoiNDMzOSIsIklEX0F0dGQiOiI5MTQiLCJOSVAiOiIzMTE1MDA2IiwiU2VzaSI6IjYiLCJEYXRlIjpudWxsLCJTdWJqZWN0IjoiTWFtcHUgbWVueXVzdW4gcGVyZW5jYW5hYW4gcGVyYXdhdGFuIHRlbXBhdCB0aWR1ciBkYW4gbGluZW4iLCJNYXRlcmlhbCI6ImEuXHRNYXJ0aW4sIFJvYmVydCwgUHJvZmVzc2lvbmFsIE1hbmFnZW1lbnQgb2YgSG91c2VrZWVwaW5nIE9wZXJhdGlvbnMuIElTQk46IDA0NzExOTg2MjVcbmIuXHRIb3VzZWtlZXBpbmcgTWFuYWdlbWVudC4gSVNCTjogMDQ3MTI1MTg5NSIsIkRlc2NyaXB0aW9uIjoiYS5cdERhcGF0IG1lbmplbGFza2FuIHRpcGUgZGFuIGplbmlzIHRlbXBhdCB0aWR1ciBkYW4gbWF0cmFzcy5cbmIuXHREYXBhdCBtZW5qZWxhc2thbiBtYWNhbS1tYWNhbS9qZW5pcyBsaW5lbi4iLCJQcmVzZW50IjpudWxsLCJBYnNlbnQiOm51bGwsIlN0YXJ0IjpudWxsLCJFbmQiOm51bGwsIlN0dWRlbnRTaWduQnkiOiIzMTE5MDAyOCIsIlN0dWRlbnRTaWduQXQiOiIyMDE5LTEyLTA2IDEwOjA5OjU5IiwiUmV2aWV3IjoiU2VjYXJhIGtlc2VsdXJ1aGFuIFdlZWsgMSAtIDcgc3VkYWggc2VzdWFpIGRlbmdhbiBCQVAuIERpcGVydGFoYW5rYW4geWEuLi4hIiwiUmV2aWV3ZWRCeSI6IjMxMTUwMDYiLCJSZXZpZXdlZEF0IjoiMjAxOS0xMi0xNSAxNzowMDoyOCIsIkluc2VydEJ5IjoiMzExNTAwNiIsIkluc2VydEF0IjoiMjAxOS0xMi0wMyAwODoyMDozMyIsIlVwZGF0ZUJ5IjoiMzExNTAwNiIsIlVwZGF0ZUF0IjoiMjAxOS0xMi0wMyAwODoyMDo0NSIsIlN0dWRlbnQiOiJDYXRoZXJpbmUgVmFsZW5jaWEgQ2hyaXN0aWFuaSIsIkxlY3R1cmVyIjoiVmluY2VudCBTeWx2ZXN0ZXIgTGVld2VsbHluIn1dLCJTdGF0dXNFZGl0IjoiMCJ9LHsiUHJlc2VudCI6MjgsIkFic2VudCI6MSwiTGVjdHVyZXIiOlt7IklEIjoiMTIzODQiLCJJRF9BdHRkIjoiOTE0IiwiTWVldCI6IjciLCJOSVAiOiIzMTE1MDA2IiwiRGF0ZSI6IjIwMTktMTEtMDEiLCJJbiI6IjEwOjIxOjU2IiwiT3V0IjoiMTE6NDA6MDAiLCJNb2RpZnlCeSI6bnVsbCwiTW9kaWZ5QXQiOm51bGwsIklQX1B1YmxpYyI6bnVsbCwiSVBfUHJpdmF0ZSI6bnVsbCwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIkJBUCI6W3siSUQiOiI0MzQwIiwiSURfQXR0ZCI6IjkxNCIsIk5JUCI6IjMxMTUwMDYiLCJTZXNpIjoiNyIsIkRhdGUiOm51bGwsIlN1YmplY3QiOiJNYW1wdSBtZW55dXN1biBwZXJlbmNhbmFhbiBvcGVyYXNpb25hbCBEZXBhcnRlbWVuIFRhdGEgR3JhaGEiLCJNYXRlcmlhbCI6ImEuXHRNYXJ0aW4sIFJvYmVydCwgUHJvZmVzc2lvbmFsIE1hbmFnZW1lbnQgb2YgSG91c2VrZWVwaW5nIE9wZXJhdGlvbnMuIElTQk46IDA0NzExOTg2MjVcbmIuXHRIb3VzZWtlZXBpbmcgTWFuYWdlbWVudC4gSVNCTjogMDQ3MTI1MTg5NSIsIkRlc2NyaXB0aW9uIjoiYS5cdERhcGF0IG1lbmplbGFza2FuIHByb3NlcyDigJxvcGVuaW5nIHRoZSBob3VzZeKAnS5cbmIuXHRNZW1haGFtaSBwZW50aW5nbnlhIFN0YW5kYXJkIE9wZXJhdGluZyBQcm9jZWR1cmUuIiwiUHJlc2VudCI6bnVsbCwiQWJzZW50IjpudWxsLCJTdGFydCI6bnVsbCwiRW5kIjpudWxsLCJTdHVkZW50U2lnbkJ5IjoiMzExOTAwMjgiLCJTdHVkZW50U2lnbkF0IjoiMjAxOS0xMi0wNiAxMDoxMDoyMCIsIlJldmlldyI6IlNlY2FyYSBrZXNlbHVydWhhbiBXZWVrIDEgLSA3IHN1ZGFoIHNlc3VhaSBkZW5nYW4gQkFQLiBEaXBlcnRhaGFua2FuIHlhLi4uISIsIlJldmlld2VkQnkiOiIzMTE1MDA2IiwiUmV2aWV3ZWRBdCI6IjIwMTktMTItMTUgMTc6MDA6MjgiLCJJbnNlcnRCeSI6IjMxMTUwMDYiLCJJbnNlcnRBdCI6IjIwMTktMTItMDMgMDg6MjE6MDciLCJVcGRhdGVCeSI6bnVsbCwiVXBkYXRlQXQiOm51bGwsIlN0dWRlbnQiOiJDYXRoZXJpbmUgVmFsZW5jaWEgQ2hyaXN0aWFuaSIsIkxlY3R1cmVyIjoiVmluY2VudCBTeWx2ZXN0ZXIgTGVld2VsbHluIn1dLCJTdGF0dXNFZGl0IjoiMCJ9LHsiUHJlc2VudCI6MjksIkFic2VudCI6MCwiTGVjdHVyZXIiOlt7IklEIjoiMTI3MTQiLCJJRF9BdHRkIjoiOTE0IiwiTWVldCI6IjgiLCJOSVAiOiIzMTE1MDA2IiwiRGF0ZSI6IjIwMTktMTEtMDgiLCJJbiI6IjA5OjU5OjAyIiwiT3V0IjoiMTE6NDA6MDAiLCJNb2RpZnlCeSI6bnVsbCwiTW9kaWZ5QXQiOm51bGwsIklQX1B1YmxpYyI6bnVsbCwiSVBfUHJpdmF0ZSI6bnVsbCwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIkJBUCI6W3siSUQiOiI0MzQyIiwiSURfQXR0ZCI6IjkxNCIsIk5JUCI6IjMxMTUwMDYiLCJTZXNpIjoiOCIsIkRhdGUiOm51bGwsIlN1YmplY3QiOiJEYXBhdCBtZW5qZWxhc2thbiBkYW4gbWVuZ2FwbGlrYXNpa2FuIGxhbmdrYWgg4oCTIGxhbmdrYWggeWFuZyBkaWxha3VrYW4gZGFsYW0gcHJvc2VzIHBlbWJlcnNpaGFuIGthbWFyIHRhbXUiLCJNYXRlcmlhbCI6ImEuXHRNYXJ0aW4sIFJvYmVydCwgUHJvZmVzc2lvbmFsIE1hbmFnZW1lbnQgb2YgSG91c2VrZWVwaW5nIE9wZXJhdGlvbnMuIElTQk46IDA0NzExOTg2MjVcbmIuXHRIb3VzZWtlZXBpbmcgTWFuYWdlbWVudC4gSVNCTjogMDQ3MTI1MTg5NSIsIkRlc2NyaXB0aW9uIjoiYS5cdERhcGF0IG1lbmplbGFza2FuIGRhbiBtZW5nYXBsaWthc2lrYW4gcHJvc2VzIHBlbWJlcnNpaGFuIGthbWFyLlxuYi5cdE1lbmplbGFza2FuIHNlY2FyYSBrcm9ub2xvZ2lrYWwgcHJvc2VzIHBlbWJlcnNpaGFuIGthbWFyLiIsIlByZXNlbnQiOm51bGwsIkFic2VudCI6bnVsbCwiU3RhcnQiOm51bGwsIkVuZCI6bnVsbCwiU3R1ZGVudFNpZ25CeSI6IjMxMTkwMDI4IiwiU3R1ZGVudFNpZ25BdCI6IjIwMTktMTItMDYgMTA6MTA6MzYiLCJSZXZpZXciOiJTZWNhcmEga2VzZWx1cnVoYW4gV2VlayA4IC0gMTQgc3VkYWggc2VzdWFpIGRlbmdhbiBCQVAuIERpcGVydGFoYW5rYW4geWEuLi4hIiwiUmV2aWV3ZWRCeSI6IjMxMTUwMDYiLCJSZXZpZXdlZEF0IjoiMjAxOS0xMi0xNSAxNzowMToyMSIsIkluc2VydEJ5IjoiMzExNTAwNiIsIkluc2VydEF0IjoiMjAxOS0xMi0wMyAwODoyMTozNiIsIlVwZGF0ZUJ5IjpudWxsLCJVcGRhdGVBdCI6bnVsbCwiU3R1ZGVudCI6IkNhdGhlcmluZSBWYWxlbmNpYSBDaHJpc3RpYW5pIiwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIlN0YXR1c0VkaXQiOiIwIn0seyJQcmVzZW50IjoyOSwiQWJzZW50IjowLCJMZWN0dXJlciI6W3siSUQiOiIxMzA5MyIsIklEX0F0dGQiOiI5MTQiLCJNZWV0IjoiOSIsIk5JUCI6IjMxMTUwMDYiLCJEYXRlIjoiMjAxOS0xMS0xNiIsIkluIjoiMTA6MDA6MDAiLCJPdXQiOiIxMTo0MDowMCIsIk1vZGlmeUJ5IjoiMTAxNjA2MCIsIk1vZGlmeUF0IjoiMjAxOS0xMS0xNSAxNjo0Mzo1MyIsIklQX1B1YmxpYyI6bnVsbCwiSVBfUHJpdmF0ZSI6bnVsbCwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIkJBUCI6W3siSUQiOiI0MzQzIiwiSURfQXR0ZCI6IjkxNCIsIk5JUCI6IjMxMTUwMDYiLCJTZXNpIjoiOSIsIkRhdGUiOm51bGwsIlN1YmplY3QiOiJEYXBhdCBtZW5qZWxhc2thbiBmdW5nc2kg4oCTIGZ1bmdzaSB2aXRhbCB5YW5nIGhhcnVzIGRpcGFzdGlrYW4gYmVyamFsYW4vYmVyb3BlcmFzaSBkZW5nYW4gYmFpayBkYWxhbSBEZXBhcnRlbWVuIFRhdGEgR3JhaGEiLCJNYXRlcmlhbCI6ImEuXHRNYXJ0aW4sIFJvYmVydCwgUHJvZmVzc2lvbmFsIE1hbmFnZW1lbnQgb2YgSG91c2VrZWVwaW5nIE9wZXJhdGlvbnMuIElTQk46IDA0NzExOTg2MjVcbmIuXHRIb3VzZWtlZXBpbmcgTWFuYWdlbWVudC4gSVNCTjogMDQ3MTI1MTg5NSIsIkRlc2NyaXB0aW9uIjoiYS5cdERhcGF0IG1lbmplbGFza2FuIGZ1bmdzaS1mdW5nc2ktZnVuZ3NpIHZpdGFsIGRhbGFtIG1hbmFqZW1lbiB0YXRhIGdyYWhhLlxuYi5cdERhcGF0IG1lbWFoYW1pIGh1YnVuZ2FuLWh1YnVuZ2FuIGRlbmdhbiBmdW5nc2kgbGFpbiBkYWxhbSBob3RlbCB5YW5nIGJlcmh1YnVuZ2FuIGRlbmdhbiBtYW5hamVtZW4gdGF0YSBncmFoYS4iLCJQcmVzZW50IjpudWxsLCJBYnNlbnQiOm51bGwsIlN0YXJ0IjpudWxsLCJFbmQiOm51bGwsIlN0dWRlbnRTaWduQnkiOiIzMTE5MDAyOCIsIlN0dWRlbnRTaWduQXQiOiIyMDE5LTEyLTA2IDEwOjEwOjUxIiwiUmV2aWV3IjoiU2VjYXJhIGtlc2VsdXJ1aGFuIFdlZWsgOCAtIDE0IHN1ZGFoIHNlc3VhaSBkZW5nYW4gQkFQLiBEaXBlcnRhaGFua2FuIHlhLi4uISIsIlJldmlld2VkQnkiOiIzMTE1MDA2IiwiUmV2aWV3ZWRBdCI6IjIwMTktMTItMTUgMTc6MDE6MjEiLCJJbnNlcnRCeSI6IjMxMTUwMDYiLCJJbnNlcnRBdCI6IjIwMTktMTItMDMgMDg6MjE6NTMiLCJVcGRhdGVCeSI6bnVsbCwiVXBkYXRlQXQiOm51bGwsIlN0dWRlbnQiOiJDYXRoZXJpbmUgVmFsZW5jaWEgQ2hyaXN0aWFuaSIsIkxlY3R1cmVyIjoiVmluY2VudCBTeWx2ZXN0ZXIgTGVld2VsbHluIn1dLCJTdGF0dXNFZGl0IjoiMCJ9LHsiUHJlc2VudCI6MjksIkFic2VudCI6MCwiTGVjdHVyZXIiOlt7IklEIjoiMTMyOTIiLCJJRF9BdHRkIjoiOTE0IiwiTWVldCI6IjEwIiwiTklQIjoiMzExNTAwNiIsIkRhdGUiOiIyMDE5LTExLTIwIiwiSW4iOiIwOTo1ODo1MCIsIk91dCI6IjExOjQwOjAwIiwiTW9kaWZ5QnkiOiIzMTE1MDA2IiwiTW9kaWZ5QXQiOiIyMDE5LTExLTIwIDA5OjU4OjQzIiwiSVBfUHVibGljIjpudWxsLCJJUF9Qcml2YXRlIjpudWxsLCJMZWN0dXJlciI6IlZpbmNlbnQgU3lsdmVzdGVyIExlZXdlbGx5biJ9XSwiQkFQIjpbeyJJRCI6IjQzNDQiLCJJRF9BdHRkIjoiOTE0IiwiTklQIjoiMzExNTAwNiIsIlNlc2kiOiIxMCIsIkRhdGUiOm51bGwsIlN1YmplY3QiOiJEYXBhdCBtZW5qZWxhc2thbiBzZXJ0YSBtZW5nYXBsaWthc2lrYW4gbWFuYWplbWVuIGtvbGFtIHJlbmFuZyB5YW5nIGFtYW4gZGFuIHNlaGF0IiwiTWF0ZXJpYWwiOiJhLlx0TWFydGluLCBSb2JlcnQsIFByb2Zlc3Npb25hbCBNYW5hZ2VtZW50IG9mIEhvdXNla2VlcGluZyBPcGVyYXRpb25zLiBJU0JOOiAwNDcxMTk4NjI1XG5iLlx0SG91c2VrZWVwaW5nIE1hbmFnZW1lbnQuIElTQk46IDA0NzEyNTE4OTUiLCJEZXNjcmlwdGlvbiI6ImEuXHREYXBhdCBtZW5qZWxhc2thbiBtYW5hamVtZW4ga29sYW0gcmVuYW5nIHlhbmcgYW1hbiBkYW4gc2VoYXQuXG5iLlx0RGFwYXQgbWVuamVsYXNrYW4gcHJvc2VzIHBlcmF3YXRhbiBrb2xhbSByZW5hbmcgYWdhciBhbWFuIGRhbiBzZWhhdC4iLCJQcmVzZW50IjpudWxsLCJBYnNlbnQiOm51bGwsIlN0YXJ0IjpudWxsLCJFbmQiOm51bGwsIlN0dWRlbnRTaWduQnkiOiIzMTE5MDAyOCIsIlN0dWRlbnRTaWduQXQiOiIyMDE5LTEyLTA2IDEwOjExOjA4IiwiUmV2aWV3IjoiU2VjYXJhIGtlc2VsdXJ1aGFuIFdlZWsgOCAtIDE0IHN1ZGFoIHNlc3VhaSBkZW5nYW4gQkFQLiBEaXBlcnRhaGFua2FuIHlhLi4uISIsIlJldmlld2VkQnkiOiIzMTE1MDA2IiwiUmV2aWV3ZWRBdCI6IjIwMTktMTItMTUgMTc6MDE6MjEiLCJJbnNlcnRCeSI6IjMxMTUwMDYiLCJJbnNlcnRBdCI6IjIwMTktMTItMDMgMDg6MjI6MDMiLCJVcGRhdGVCeSI6IjMxMTUwMDYiLCJVcGRhdGVBdCI6IjIwMTktMTItMDMgMDg6MjI6MTYiLCJTdHVkZW50IjoiQ2F0aGVyaW5lIFZhbGVuY2lhIENocmlzdGlhbmkiLCJMZWN0dXJlciI6IlZpbmNlbnQgU3lsdmVzdGVyIExlZXdlbGx5biJ9XSwiU3RhdHVzRWRpdCI6IjAifSx7IlByZXNlbnQiOjI5LCJBYnNlbnQiOjAsIkxlY3R1cmVyIjpbeyJJRCI6IjEzMDk0IiwiSURfQXR0ZCI6IjkxNCIsIk1lZXQiOiIxMSIsIk5JUCI6IjMxMTUwMDYiLCJEYXRlIjoiMjAxOS0xMS0xNSIsIkluIjoiMTA6MDA6MDAiLCJPdXQiOiIxMToyMDowMCIsIk1vZGlmeUJ5IjoiMTAxNjA2MCIsIk1vZGlmeUF0IjoiMjAxOS0xMS0xNSAxNjo0NDozMyIsIklQX1B1YmxpYyI6bnVsbCwiSVBfUHJpdmF0ZSI6bnVsbCwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIkJBUCI6W3siSUQiOiI0MzQ2IiwiSURfQXR0ZCI6IjkxNCIsIk5JUCI6IjMxMTUwMDYiLCJTZXNpIjoiMTEiLCJEYXRlIjpudWxsLCJTdWJqZWN0IjoiRGFwYXQgbWVuamVsYXNrYW4gbWFuYWplbWVuIGxpbmdrdW5nYW4gaG90ZWwgeWFuZyBhbWFuIGRhbiBzZWhhdCIsIk1hdGVyaWFsIjoiYS5cdE1hcnRpbiwgUm9iZXJ0LCBQcm9mZXNzaW9uYWwgTWFuYWdlbWVudCBvZiBIb3VzZWtlZXBpbmcgT3BlcmF0aW9ucy4gSVNCTjogMDQ3MTE5ODYyNVxuYi5cdEhvdXNla2VlcGluZyBNYW5hZ2VtZW50LiBJU0JOOiAwNDcxMjUxODk1IiwiRGVzY3JpcHRpb24iOiJhLlx0RGFwYXQgbWVuamVsYXNrYW4gbWFuYWplbWVuIGxpbmdrdW5nYW4gaG90ZWwgeWFuZyBhbWFuIGRhbiBzZWhhdC5cbmIuXHREYXBhdCBtZW5qZWxhc2thbiBoYWwtaGFsIHlhbmcgcGVudGluZyBkaXBlcmhhdGlrYW4gZGFsYW0gbWFuYWplbWVuIGxpbmdrdW5nYW4uIiwiUHJlc2VudCI6bnVsbCwiQWJzZW50IjpudWxsLCJTdGFydCI6bnVsbCwiRW5kIjpudWxsLCJTdHVkZW50U2lnbkJ5IjoiMzExOTAwMjgiLCJTdHVkZW50U2lnbkF0IjoiMjAxOS0xMi0wNiAxMDoxMToyMSIsIlJldmlldyI6IlNlY2FyYSBrZXNlbHVydWhhbiBXZWVrIDggLSAxNCBzdWRhaCBzZXN1YWkgZGVuZ2FuIEJBUC4gRGlwZXJ0YWhhbmthbiB5YS4uLiEiLCJSZXZpZXdlZEJ5IjoiMzExNTAwNiIsIlJldmlld2VkQXQiOiIyMDE5LTEyLTE1IDE3OjAxOjIxIiwiSW5zZXJ0QnkiOiIzMTE1MDA2IiwiSW5zZXJ0QXQiOiIyMDE5LTEyLTAzIDA4OjIzOjAxIiwiVXBkYXRlQnkiOm51bGwsIlVwZGF0ZUF0IjpudWxsLCJTdHVkZW50IjoiQ2F0aGVyaW5lIFZhbGVuY2lhIENocmlzdGlhbmkiLCJMZWN0dXJlciI6IlZpbmNlbnQgU3lsdmVzdGVyIExlZXdlbGx5biJ9XSwiU3RhdHVzRWRpdCI6IjAifSx7IlByZXNlbnQiOjI5LCJBYnNlbnQiOjAsIkxlY3R1cmVyIjpbeyJJRCI6IjEzNDE1IiwiSURfQXR0ZCI6IjkxNCIsIk1lZXQiOiIxMiIsIk5JUCI6IjMxMTUwMDYiLCJEYXRlIjoiMjAxOS0xMS0yMiIsIkluIjoiMTA6MDA6NDUiLCJPdXQiOiIxMTo0MDowMCIsIk1vZGlmeUJ5IjoiMzExNTAwNiIsIk1vZGlmeUF0IjoiMjAxOS0xMS0yMiAxMDowMDozNSIsIklQX1B1YmxpYyI6bnVsbCwiSVBfUHJpdmF0ZSI6bnVsbCwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIkJBUCI6W3siSUQiOiI0MzQ3IiwiSURfQXR0ZCI6IjkxNCIsIk5JUCI6IjMxMTUwMDYiLCJTZXNpIjoiMTIiLCJEYXRlIjpudWxsLCJTdWJqZWN0IjoiRGFwYXQgbWVuamVsYXNrYW4gcHJvc2VzIG1hbmFqZW1lbiBrZWFtYW5hbiBkYW4ga2VzZWxhbWF0YW4gaG90ZWwiLCJNYXRlcmlhbCI6ImEuXHRNYXJ0aW4sIFJvYmVydCwgUHJvZmVzc2lvbmFsIE1hbmFnZW1lbnQgb2YgSG91c2VrZWVwaW5nIE9wZXJhdGlvbnMuIElTQk46IDA0NzExOTg2MjVcbmIuXHRIb3VzZWtlZXBpbmcgTWFuYWdlbWVudC4gSVNCTjogMDQ3MTI1MTg5NSIsIkRlc2NyaXB0aW9uIjoiYS5cdERhcGF0IG1lbmplbGFza2FuIHByb3NlcyBtYW5hamVtZW4ga2VhbWFuYW4gaG90ZWwgZGFuIHBlbmdhbWFuYW4gYXNldCB0YW11LlxuYi5cdERhcGF0IG1lbmplbGFza2FuIGRhbiBtZW5nYXBsaWthc2lrYW4gYXNwZWsga2VzZWxhbWF0YW4ga2VyamEgaG90ZWwuIiwiUHJlc2VudCI6bnVsbCwiQWJzZW50IjpudWxsLCJTdGFydCI6bnVsbCwiRW5kIjpudWxsLCJTdHVkZW50U2lnbkJ5IjoiMzExOTAwMjgiLCJTdHVkZW50U2lnbkF0IjoiMjAxOS0xMi0wNiAxMDoxMTozOSIsIlJldmlldyI6IlNlY2FyYSBrZXNlbHVydWhhbiBXZWVrIDggLSAxNCBzdWRhaCBzZXN1YWkgZGVuZ2FuIEJBUC4gRGlwZXJ0YWhhbmthbiB5YS4uLiEiLCJSZXZpZXdlZEJ5IjoiMzExNTAwNiIsIlJldmlld2VkQXQiOiIyMDE5LTEyLTE1IDE3OjAxOjIxIiwiSW5zZXJ0QnkiOiIzMTE1MDA2IiwiSW5zZXJ0QXQiOiIyMDE5LTEyLTAzIDA4OjIzOjIxIiwiVXBkYXRlQnkiOm51bGwsIlVwZGF0ZUF0IjpudWxsLCJTdHVkZW50IjoiQ2F0aGVyaW5lIFZhbGVuY2lhIENocmlzdGlhbmkiLCJMZWN0dXJlciI6IlZpbmNlbnQgU3lsdmVzdGVyIExlZXdlbGx5biJ9XSwiU3RhdHVzRWRpdCI6IjAifSx7IlByZXNlbnQiOjI5LCJBYnNlbnQiOjAsIkxlY3R1cmVyIjpbeyJJRCI6IjEzNzc4IiwiSURfQXR0ZCI6IjkxNCIsIk1lZXQiOiIxMyIsIk5JUCI6IjMxMTUwMDYiLCJEYXRlIjoiMjAxOS0xMS0yOSIsIkluIjoiMTA6MDA6MzUiLCJPdXQiOiIxMTo0MDowMCIsIk1vZGlmeUJ5IjoiMzExNTAwNiIsIk1vZGlmeUF0IjoiMjAxOS0xMS0yOSAxMDowMDozNyIsIklQX1B1YmxpYyI6IjE0MC4yMTMuMTI4LjE2MCIsIklQX1ByaXZhdGUiOiIxMTIuMjE1LjIzNi41MCIsIkxlY3R1cmVyIjoiVmluY2VudCBTeWx2ZXN0ZXIgTGVld2VsbHluIn1dLCJCQVAiOlt7IklEIjoiNDM0OCIsIklEX0F0dGQiOiI5MTQiLCJOSVAiOiIzMTE1MDA2IiwiU2VzaSI6IjEzIiwiRGF0ZSI6bnVsbCwiU3ViamVjdCI6IkRhcGF0IG1lbmplbGFza2FuIG1hbmFqZW1lbiBiaW5hdHUiLCJNYXRlcmlhbCI6ImEuXHRNYXJ0aW4sIFJvYmVydCwgUHJvZmVzc2lvbmFsIE1hbmFnZW1lbnQgb2YgSG91c2VrZWVwaW5nIE9wZXJhdGlvbnMuIElTQk46IDA0NzExOTg2MjVcbmIuXHRIb3VzZWtlZXBpbmcgTWFuYWdlbWVudC4gSVNCTjogMDQ3MTI1MTg5NSIsIkRlc2NyaXB0aW9uIjoiYS5cdERhcGF0IG1lbmplbGFza2FuIG1hbmFqZW1lbiBiaW5hdHUuXG5iLlx0RGFwYXQgbWVuamVsYXNrYW4gcHJvc2VzIGtlcmphIGRpIGJpbmF0dS4iLCJQcmVzZW50IjpudWxsLCJBYnNlbnQiOm51bGwsIlN0YXJ0IjpudWxsLCJFbmQiOm51bGwsIlN0dWRlbnRTaWduQnkiOiIzMTE5MDAyOCIsIlN0dWRlbnRTaWduQXQiOiIyMDE5LTEyLTA2IDEwOjExOjUyIiwiUmV2aWV3IjoiU2VjYXJhIGtlc2VsdXJ1aGFuIFdlZWsgOCAtIDE0IHN1ZGFoIHNlc3VhaSBkZW5nYW4gQkFQLiBEaXBlcnRhaGFua2FuIHlhLi4uISIsIlJldmlld2VkQnkiOiIzMTE1MDA2IiwiUmV2aWV3ZWRBdCI6IjIwMTktMTItMTUgMTc6MDE6MjEiLCJJbnNlcnRCeSI6IjMxMTUwMDYiLCJJbnNlcnRBdCI6IjIwMTktMTItMDMgMDg6MjM6MzkiLCJVcGRhdGVCeSI6bnVsbCwiVXBkYXRlQXQiOm51bGwsIlN0dWRlbnQiOiJDYXRoZXJpbmUgVmFsZW5jaWEgQ2hyaXN0aWFuaSIsIkxlY3R1cmVyIjoiVmluY2VudCBTeWx2ZXN0ZXIgTGVld2VsbHluIn1dLCJTdGF0dXNFZGl0IjoiMCJ9LHsiUHJlc2VudCI6MjksIkFic2VudCI6MCwiTGVjdHVyZXIiOlt7IklEIjoiMTQxODQiLCJJRF9BdHRkIjoiOTE0IiwiTWVldCI6IjE0IiwiTklQIjoiMzExNTAwNiIsIkRhdGUiOiIyMDE5LTEyLTA2IiwiSW4iOiIxMDowMDo1OSIsIk91dCI6IjExOjQwOjAwIiwiTW9kaWZ5QnkiOiIzMTE1MDA2IiwiTW9kaWZ5QXQiOiIyMDE5LTEyLTA2IDEwOjAwOjU5IiwiSVBfUHVibGljIjoiMjEwLjIxMC4xNTguMTIxIiwiSVBfUHJpdmF0ZSI6IjEwLjEuNjAuMTU5IiwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIkJBUCI6W3siSUQiOiI0MzQ5IiwiSURfQXR0ZCI6IjkxNCIsIk5JUCI6IjMxMTUwMDYiLCJTZXNpIjoiMTQiLCJEYXRlIjpudWxsLCJTdWJqZWN0IjoiRGFwYXQgbWVuamVsYXNrYW4gZGFuIG1lbnl1c3VuIHNlcnRhIG1lbXByZXNlbnRhc2lrYW4gUmVuY2FuYSBEZXBhcnRlbWVuIFRhdGEgR3JhaGEiLCJNYXRlcmlhbCI6ImEuXHRNYXJ0aW4sIFJvYmVydCwgUHJvZmVzc2lvbmFsIE1hbmFnZW1lbnQgb2YgSG91c2VrZWVwaW5nIE9wZXJhdGlvbnMuIElTQk46IDA0NzExOTg2MjVcbmIuXHRIb3VzZWtlZXBpbmcgTWFuYWdlbWVudC4gSVNCTjogMDQ3MTI1MTg5NSIsIkRlc2NyaXB0aW9uIjoiYS5cdERhcGF0IG1lbnl1c3VuIGRhbiBtZW1wcmVzZW50YXNpa2FuIFJlbmNhbmEgRGVwYXJ0ZW1lbiBUYXRhIEdyYWhhLiIsIlByZXNlbnQiOm51bGwsIkFic2VudCI6bnVsbCwiU3RhcnQiOm51bGwsIkVuZCI6bnVsbCwiU3R1ZGVudFNpZ25CeSI6IjMxMTkwMDI4IiwiU3R1ZGVudFNpZ25BdCI6IjIwMTktMTItMDYgMTA6MTI6MDciLCJSZXZpZXciOiJTZWNhcmEga2VzZWx1cnVoYW4gV2VlayA4IC0gMTQgc3VkYWggc2VzdWFpIGRlbmdhbiBCQVAuIERpcGVydGFoYW5rYW4geWEuLi4hIiwiUmV2aWV3ZWRCeSI6IjMxMTUwMDYiLCJSZXZpZXdlZEF0IjoiMjAxOS0xMi0xNSAxNzowMToyMSIsIkluc2VydEJ5IjoiMzExNTAwNiIsIkluc2VydEF0IjoiMjAxOS0xMi0wMyAwODoyMzo1NyIsIlVwZGF0ZUJ5IjpudWxsLCJVcGRhdGVBdCI6bnVsbCwiU3R1ZGVudCI6IkNhdGhlcmluZSBWYWxlbmNpYSBDaHJpc3RpYW5pIiwiTGVjdHVyZXIiOiJWaW5jZW50IFN5bHZlc3RlciBMZWV3ZWxseW4ifV0sIlN0YXR1c0VkaXQiOiIwIn1dfQ.Ip6GJ95ke0RlHCQEo5qBAnaQbzJVFsCUetWhMjyt87Y');
//        print_r($data_arr);
//        exit;

        $fpdf = new Pdf_mc_table('L', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetMargins(5,5,5);

        $fpdf->SetAutoPageBreak(true, 5);

        $fpdf->Image('./images/logo_tr.png',5,5,41);
        $b = 0;
        $h_3 = 3;
        $h_5 = 5;

        $Course = (array) $data_arr['dataCourse'];

        $fpdf->SetFont('Arial','',7);
        $fpdf->Cell(0,$h_3,'FM-UAP/AKD-08-02-REV.01',$b,1,'R');

        $fpdf->SetFont('Arial','B',13);
        $fpdf->Cell(0,$h_5,'Berita Acara Perkuliahan',$b,1,'C');

        $fpdf->SetFont('Arial','',10);
        $fpdf->Cell(0,$h_5,'Semester : '.$Course['SemesterName'],$b,1,'C');
        $fpdf->Ln(3);

        $fpdf->SetFont('Arial','',9);
        $b=0;
        $fpdf->setFillColor(242, 242, 242);

        // 287


        $fpdf->Cell(25,$h_5,'Program Studi',$b,0,'L');
        $fpdf->Cell(5,$h_5,':',$b,0,'C');
        $fpdf->Cell(72,$h_5,$Course['ProdiName'],$b,0,'L',true);

        $fpdf->Cell(25,$h_5,'Mata Kuliah',$b,0,'L');
        $fpdf->Cell(5,$h_5,':',$b,0,'C');
        $fpdf->Cell(110,$h_5,$Course['Course'],$b,0,'L',true);

        $fpdf->Cell(25,$h_5,'SKS',$b,0,'L');
        $fpdf->Cell(5,$h_5,':',$b,0,'C');
        $fpdf->Cell(15,$h_5,$Course['Credit'],$b,1,'L',true);

        $fpdf->Ln(0.5);
        $fpdf->Cell(25,$h_5,'Dosen',$b,0,'L');
        $fpdf->Cell(5,$h_5,':',$b,0,'C');
        $fpdf->Cell(72,$h_5,$Course['Name'],$b,0,'L',true);

        $fpdf->Cell(25,$h_5,'Kelas / Grup',$b,0,'L');
        $fpdf->Cell(5,$h_5,':',$b,0,'C');
        $fpdf->Cell(55,$h_5,$Course['ClassGroup'],$b,0,'L',true);

        $fpdf->Cell(35,$h_5,'Mahasiswa Terdaftar',$b,0,'L');
        $fpdf->Cell(5,$h_5,':',$b,0,'C');
        $fpdf->Cell(15,$h_5,$Course['TotalStudent'],$b,0,'L',true);


        $fpdf->Cell(25,$h_5,'Semester',$b,0,'L');
        $fpdf->Cell(5,$h_5,':',$b,0,'C');
        $fpdf->Cell(15,$h_5,'',$b,1,'L',true);

        $fpdf->setFillColor(130, 200, 222 );
        $h_10 = 10;
        $b = 1;
        $fpdf->Ln(5);
        $fpdf->SetFont('Arial','B',8);
        $fpdf->Cell(9,$h_10,'Sesi',$b,0,'C',true);
        $fpdf->Cell(25,$h_10,'Tanggal',$b,0,'C',true);
        $fpdf->Cell(55,$h_10,'Pokok Bahasan',$b,0,'C',true);
        $fpdf->Cell(55,$h_10,'Materi',$b,0,'C',true);
        $fpdf->Cell(55,$h_10,'Keterangan',$b,0,'C',true);
        $fpdf->Cell(15,$h_10,"Mhs Hdr",$b,0,'C',true);
        $fpdf->Cell(29,$h_5,'Waktu',$b,0,'C',true);
        $fpdf->Cell(44,$h_5,'Paraf & Nama',$b,1,'C',true);


        $fpdf->Cell(214,0,'',0,0,'C');
        $fpdf->Cell(14.5,$h_5,'Mulai',$b,0,'C',true);
        $fpdf->Cell(14.5,$h_5,'Selesai',$b,0,'C',true);
        $fpdf->Cell(19,$h_5,'Dosen',$b,0,'C',true);
        $fpdf->Cell(25,$h_5,'Mahasiswa',$b,1,'C',true);

        $fpdf->SetFont('Arial','',8);

        $lineHight = 4;

        $fpdf->SetWidths(array(9,25,55,55,55,15,14.5,14.5,19,25));
//        $fpdf->SetLineHeight(6.2);
        $fpdf->SetAligns(array('C','C','L','L','L','C','C','C','C','L'));

        $dataBAP = $data_arr['detailsBAP'];
        $NIPLecturer = $Course['NIP'];
        $ReviewAkhir = '';

        for($i=0;$i<14;$i++){

            $d = (array) $dataBAP[$i];
            $bap = (count($d['BAP'])>0) ? $d['BAP'][0] : [];

            $Tanggal = (count($d['BAP'])>0 && $bap->InsertAt!='' && $bap->InsertAt!=null) ? date('d M Y',strtotime($bap->InsertAt)) : '';
            $Subject = (count($d['BAP'])>0 && $bap->Subject!='' && $bap->Subject!=null) ? $bap->Subject : '';
            $Material = (count($d['BAP'])>0 && $bap->Material!='' && $bap->Material!=null) ? $bap->Material : '';
            $Description = (count($d['BAP'])>0 && $bap->Description!='' && $bap->Description!=null) ? $bap->Description : '';
            $Present = (count($d['BAP'])>0 && $d['Present']!='' && $d['Present']!=null) ? $d['Present'] : '';

            $dataLec = $d['Lecturer'];
            $In = ''; $Out = '';
            if(count($dataLec)>0){
                for($l=0;$l<count($dataLec);$l++){
                    if($NIPLecturer==$dataLec[$l]->NIP){
                        $In = ($dataLec[$l]->In!='' && $dataLec[$l]->In!=null) ? substr($dataLec[$l]->In,0,5) : '';
                        $Out = ($dataLec[$l]->Out!='' && $dataLec[$l]->Out!=null) ? substr($dataLec[$l]->Out,0,5) : '';
                        break;
                    }
                }
            }

            $StdNPM = (count($d['BAP'])>0 && $bap->StudentSignBy!='' && $bap->StudentSignBy!=null) ? '('.$bap->StudentSignBy.') ' : '';
            $StdName = (count($d['BAP'])>0 && $bap->Student!='' && $bap->Student!=null) ? $bap->Student : '';
            $StdAt = (count($d['BAP'])>0 && $bap->StudentSignAt!='' && $bap->StudentSignAt!=null) ? date("d M Y H:s",strtotime($bap->StudentSignAt)) : '';

            $viewStdName = (strlen($StdName)>35) ? substr($StdName,0,35).'__' : $StdName;



            $fpdf->Row_bapOnline($lineHight,array(
                ($i+1),$Tanggal,
                $Subject,$Material,$Description,
                $Present, $In, $Out,
                "",
                $viewStdName."\n".$StdNPM."\n".$StdAt,
            ));

            if($i==6){
                $Review = (count($d['BAP'])>0 && $bap->Review!='' && $bap->Review!=null) ? $bap->Review : '';
                $fpdf->SetFont('Arial','B',8);
                $fpdf->Cell(287-44,$h_5,'Review Tengah Semester :','LRT',0,'L');
                $fpdf->Cell(44,$h_5,'Koordinator/Kaprodi','LRT',1,'C');
                $fpdf->SetFont('Arial','',8);
                $fpdf->Cell(287-44,$h_10,$Review,'LRB',0,'L');
                $fpdf->Cell(44,$h_10,'','LRB',1,'C');


                $fpdf->AddPage();
                $fpdf->SetMargins(5,5,5);

                $fpdf->Image('./images/logo_tr.png',5,5,41);

                $b = 0;
                $fpdf->SetFont('Arial','',7);
                $fpdf->Cell(0,$h_3,'FM-UAP/AKD-08-02-REV.01',$b,1,'R');

                $fpdf->SetFont('Arial','B',13);
                $fpdf->Cell(0,$h_5,'Berita Acara Perkuliahan',$b,1,'C');

                $fpdf->SetFont('Arial','',10);
                $fpdf->Cell(0,$h_5,'Semester : 2019/2020 Ganjil',$b,1,'C');
                $fpdf->Ln(7);

                $b = 1;

                $fpdf->SetFont('Arial','B',8);
                $fpdf->Cell(9,$h_10,'Sesi',$b,0,'C',true);
                $fpdf->Cell(25,$h_10,'Tanggal',$b,0,'C',true);
                $fpdf->Cell(55,$h_10,'Pokok Bahasan',$b,0,'C',true);
                $fpdf->Cell(55,$h_10,'Materi',$b,0,'C',true);
                $fpdf->Cell(55,$h_10,'Keterangan',$b,0,'C',true);
                $fpdf->Cell(15,$h_10,"Mhs Hdr",$b,0,'C',true);
                $fpdf->Cell(29,$h_5,'Waktu',$b,0,'C',true);
                $fpdf->Cell(44,$h_5,'Paraf & Nama',$b,1,'C',true);


                $fpdf->Cell(214,0,'',0,0,'C');
                $fpdf->Cell(14.5,$h_5,'Mulai',$b,0,'C',true);
                $fpdf->Cell(14.5,$h_5,'Selesai',$b,0,'C',true);
                $fpdf->Cell(19,$h_5,'Dosen',$b,0,'C',true);
                $fpdf->Cell(25,$h_5,'Mahasiswa',$b,1,'C',true);

                $fpdf->SetFont('Arial','',8);
            }

            if($i==13){
                $ReviewAkhir = (count($d['BAP'])>0 && $bap->Review!='' && $bap->Review!=null) ? $bap->Review : '';
            }

        }

        $fpdf->SetFont('Arial','B',8);
        $fpdf->Cell(287-44,$h_5,'Review Akhir Semester :','LRT',0,'L');
        $fpdf->Cell(44,$h_5,'Koordinator/Kaprodi','LRT',1,'C');
        $fpdf->SetFont('Arial','',8);
        $fpdf->Cell(287-44,$h_10,$ReviewAkhir,'LRB',0,'L');
        $fpdf->Cell(44,$h_10,'','LRB',1,'C');


        $fpdf->Output('n.pdf','I');

    }

}
