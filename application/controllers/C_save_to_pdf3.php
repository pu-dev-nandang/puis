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
        $fpdf->Cell(0,0, 'Purchase Order', 0, 1, 'C', 0);
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
        $w_no = 8;
        $w_desc = 20;
        $w_spec = 20;
        $w_date_needed = 20;
        $w_qty = 8;
        $w_pricest = 25;
        $w_pph = 15;
        $w_disc = 15;
        $w_anotcost = 25;
        $w_totalammount = 35;
        $h=4.4;
        $y = $fpdf->GetY();
        $fpdf->SetXY($x,$y);
        $fpdf->SetFillColor(255, 255, 255);
         $fpdf->Cell($w_no,$h,'No.',$border,0,'C',true);
         $fpdf->Cell($w_desc,$h,'Nama Barang',$border,0,'C',true);
         $fpdf->Cell($w_spec,$h,'Specification',$border,0,'C',true);
         $fpdf->Cell($w_date_needed,$h,'Date Needed',$border,0,'C',true);
         $fpdf->Cell($w_qty,$h,'Qty',$border,0,'C',true);
         $fpdf->Cell($w_pricest,$h,'Harga',$border,0,'C',true);
         $fpdf->Cell($w_pph,$h,'PPN',$border,0,'C',true);
         $fpdf->Cell($w_disc,$h,'Discount',$border,0,'C',true);
         $fpdf->Cell($w_anotcost,$h,'Another Cost',$border,0,'C',true);
         $fpdf->Cell($w_totalammount,$h,'Total Amount',$border,1,'C',true);

        // isi table
         $no = 1;
         $fpdf->SetFont('Arial','',$FontIsian);
         $total = 0;
         $fpdf->SetWidths(array($w_no,$w_desc,$w_spec,$w_date_needed,$w_qty,$w_pricest,$w_pph,$w_disc,$w_anotcost,$w_totalammount));
         $fpdf->SetLineHeight(5);
         $fpdf->SetAligns(array('C','L','L','C','L','C','C','C','C','C'));
         $total = 0;
         for ($i=0; $i < count($po_detail); $i++) { 
             $Spesification = '';
             $DetailCatalog = json_decode($po_detail[$i]['DetailCatalog'],true);
             foreach ($DetailCatalog as $key => $value) {
                $Spesification .= $key.' : '.$value."\n";
             }

             if ($po_detail[$i]['Spec_add'] != '' && $po_detail[$i]['Spec_add'] != null) {
                 $Spesification .= "\n";
                 $Spesification .= $po_detail[$i]['Spec_add'];
             }

             $fpdf->Row(array(
                $no,
                $po_detail[$i]['Item']."\n".$po_detail[$i]['Desc'],
                $Spesification,
                $po_detail[$i]['DateNeeded'],
                $po_detail[$i]['QtyPR'],
                'Rp '.number_format($po_detail[$i]['UnitCost_PO'],2,',','.'),
                (int)$po_detail[$i]['PPN_PO'].'%',
                (int)$po_detail[$i]['Discount_PO'].'%',
                'Rp '.number_format($po_detail[$i]['AnotherCost'],2,',','.'),
                'Rp '.number_format($po_detail[$i]['Subtotal'],2,',','.'),

             ));
             $total += $po_detail[$i]['Subtotal'];
             $no++;
         }


         // footer table
         $y = $fpdf->GetY();
         $x__ = $w_no+$w_desc+$w_spec+$w_date_needed+$w_qty+$w_pricest+$w_pph;
         $total2= 'Rp '.number_format($total,2,',','.');
         $fpdf->SetXY($x,$y);
         $fpdf->Cell($x__,$h,'Total',$border,0,'L',true);
         $fpdf->Cell($w_disc+$w_anotcost+$w_totalammount,$h,$total2,$border,1,'C',true);
         $fpdf->SetFont('Arial','B',$FontIsian);
         $fpdf->Cell($x__+$w_disc+$w_anotcost+$w_totalammount,$h,$po_create[0]['Notes'],$border,1,'L',true);

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
            $fpdf->Cell($w_col_value,$h,'LILY B. PUTRI & WIBOWO NGASERIN',$border,1,'L',true);

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
                $fpdf->Cell($w3,$h,'UnitCost',1,0,'L',true);
                $fpdf->Cell($w4,$h,'PPN(%)',1,0,'L',true);
                $fpdf->Cell($w5,$h,'Total',1,1,'L',true);

                $total = 0;
                for ($i=0; $i < count($po_detail); $i++) {
                    $fpdf->SetX($x); 
                    $fpdf->Cell($w_no,$h,'',$border,0,'L',true);
                    $fpdf->Cell($w1,$h,$po_detail[$i]['Item'],1,0,'L',true);
                    $fpdf->Cell($w2,$h,$po_detail[$i]['QtyPR'],1,0,'L',true);
                    $fpdf->Cell($w3,$h,'Rp '.number_format($po_detail[$i]['UnitCost_PO'],2,',','.'),1,0,'L',true);
                    $fpdf->Cell($w4,$h,(int)$po_detail[$i]['PPN_PO'],1,0,'L',true);
                    $fpdf->Cell($w5,$h,'Rp '.number_format($po_detail[$i]['Subtotal'],2,',','.'),1,1,'L',true);
                    $total += $po_detail[$i]['Subtotal'];
                }

            // $x -=5;
            $fpdf->SetX($x); 
            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell($w_no,10,'',$border,0,'L',true);
            $fpdf->Cell($w_col_tugas, 10, 'HARGA TOTAL', 0, 0, 'L', 0);
            $fpdf->Cell($w_col_titik2,10,':',$border,0,'C',true);
            $fpdf->Cell($w_col_value,10,'Rp '.number_format($total,2,',','.'),$border,1,'L',true);

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
            $fpdf->SetFont('Arial','',$FontIsian);
            $fpdf->Cell(37.5, 20, 'No.PR        :        '.$po_detail[0]['PRCode'], 0, 1, 'L', 0);

            $fpdf->SetFont('Arial','B',$FontIsian);
            $fpdf->Cell(135, 5, 'PIHAK I', 0, 0, 'L', 0);
            $fpdf->Cell(37.5, 5, 'PIHAK II', 0, 1, 'L', 0);
            $fpdf->Cell(135, 5, 'YAY PENDIDIKAN AGUNG PODOMORO', 0, 0, 'L', 0);
            $fpdf->Cell(37.5, 5, strtoupper($po_create[0]['NamaSupplier']), 0, 1, 'L', 0);
            $fpdf->SetFont('Arial','BU',$FontIsian);
            $fpdf->ln(20);
            $fpdf->Cell(135, 0, 'LILY B. PUTRI & WIBOWO NGASERIN', 0, 0, 'L', 0);
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
        $fpdf->Cell(75, $h, 'Mohon dibayarkan / ditransfer kepada', 0, 0, 'L', 0);
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
        $fpdf->Cell(75, $h, 'Mohon dibayarkan / ditransfer kepada', 0, 0, 'L', 0);
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
         $fpdf->Cell(50,$h, 'Uang yang diberikan melalui : (pilih salah satu)', 0, 1, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Cash') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Tunai', 0, 1, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Transfer') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Transfer', 0, 1, 'L', 0);
         $No_Rekening = '...............................................................................';
         $Name_Penerima = '...............................................................................';
         $Nama_Bank = '...............................................................................';
         if ($dtspb[0]['Detail'][0]['No_Rekening'] != '' && $dtspb[0]['Detail'][0]['No_Rekening'] != null) {
             $No_Rekening = $dtspb[0]['Detail'][0]['No_Rekening'];
             $Name_Penerima = $dtspb[0]['Detail'][0]['Nama_Penerima'];
             $ID_bank = $dtspb[0]['Detail'][0]['ID_bank'];
             $G_bank = $this->m_master->caribasedprimary('db_finance.bank','ID',$ID_bank);
             $Nama_Bank = $G_bank[0]['Name'];
         }
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Ke rekening : ', 0, 1, 'L', 0);
         $h = 5;
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Nama penerima : ', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h,$Name_Penerima, 0, 1, 'L', 0);

         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Bank : ', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h,$Nama_Bank, 0, 1, 'L', 0);

         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'No rekening: : ', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h,$No_Rekening, 0, 1, 'L', 0);
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
         $fpdf->Cell(50,$h, 'Uang yang diberikan melalui : (pilih salah satu)', 0, 1, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Cash') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Tunai', 0, 1, 'L', 0);
         $fpdf->SetX(15);
         $fpdf->Cell(50,$h, ($dtspb[0]['Detail'][0]['TypePay'] == 'Transfer') ? '(..V..)' : '(....)', 0, 0, 'L', 0);
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Transfer', 0, 1, 'L', 0);
         $No_Rekening = '...............................................................................';
         $Name_Penerima = '...............................................................................';
         $Nama_Bank = '...............................................................................';
         if ($dtspb[0]['Detail'][0]['No_Rekening'] != '' && $dtspb[0]['Detail'][0]['No_Rekening'] != null) {
             $No_Rekening = $dtspb[0]['Detail'][0]['No_Rekening'];
             $Name_Penerima = $dtspb[0]['Detail'][0]['Nama_Penerima'];
             $ID_bank = $dtspb[0]['Detail'][0]['ID_bank'];
             $G_bank = $this->m_master->caribasedprimary('db_finance.bank','ID',$ID_bank);
             $Nama_Bank = $G_bank[0]['Name'];
         }
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Ke rekening : ', 0, 1, 'L', 0);
         $h = 5;
         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Nama penerima : ', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h,$Name_Penerima, 0, 1, 'L', 0);

         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'Bank : ', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h,$Nama_Bank, 0, 1, 'L', 0);

         $fpdf->SetX(25);
         $fpdf->Cell(50,$h,'No rekening: : ', 0, 0, 'L', 0);
         $fpdf->Cell(50,$h,$No_Rekening, 0, 1, 'L', 0);
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

}
