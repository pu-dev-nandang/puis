<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_spb extends CI_Model {


    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('budgeting/m_budgeting');
        $this->load->model('budgeting/m_pr_po');
    }

    public function checkdt_spb_before_submit($data_verify)
    {
        $bool = true;
        $Code_po_create = $data_verify['Code_po_create'];
        $InvoicePO = $data_verify['InvoicePO'];
        $InvoiceLeftPO = $data_verify['InvoiceLeftPO'];

        $G_dt = $this->m_master->caribasedprimary('db_purchasing.po_invoice_status','Code_po_create',$Code_po_create);
        if (count($G_dt) == 0) {
            $bool = false;
        }
        else
        {
            if ($InvoicePO != $G_dt[0]['InvoicePO'] || $InvoiceLeftPO != $G_dt[0]['InvoiceLeftPO']) {
                $bool = false;
            }
        }

        if ($G_dt[0]['Status'] == 1) {
            $bool = false;
        }

        return $bool;
    }

    public function get_spb_gr_by_po($Code_po_create)
    {
        $arr = array(
            'dtspb'=> array(),
        );

        $sql = 'select a.*,c.Name as NameCreatedBy,
                poi.InvoicePO,poi.InvoicePayPO,InvoiceLeftPO,poi.Status as StatusPOI,poi.ID as ID_poi
                from db_payment.payment as a
                left join db_employees.employees as c on a.CreatedBy = c.NIP
                left join db_purchasing.po_invoice_status as poi on a.Code_po_create = poi.Code_po_create
                where a.Code_po_create = ?
                ';
        $query=$this->db->query($sql, array($Code_po_create))->result_array();
        if (count($query) > 0) {
            for ($i=0; $i < count($query); $i++) { 
                $JsonStatus = $query[$i]['JsonStatus'];
                // Get ID
                $ID = $query[$i]['ID'];
                if ($JsonStatus != '' && $JsonStatus != null) {
                    // decode first
                    $JsonStatus = json_decode($JsonStatus,true);
                    for ($j=0; $j < count($JsonStatus); $j++) { 
                        $NIP = $JsonStatus[$j]['NIP'];
                        $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
                        $JsonStatus[$j]['Name'] = $G_emp[0]['Name'];
                    }

                    $JsonStatus = json_encode($JsonStatus);
                    // update JsonStatus for show
                    $query[$i]['JsonStatus'] = $JsonStatus;
                    $Type = $query[$i]['Type'];
                    $Detail = array();
                    switch ($Type) {
                        case 'Spb':
                            $G_dt = $this->m_master->caribasedprimary('db_payment.spb','ID_payment',$ID);
                            $Detail = $G_dt;
                            break;
                        case 'Bank Advance':
                            $G_dt = $this->m_master->caribasedprimary('db_payment.bank_advance','ID_payment',$ID);
                            $tot = count($G_dt);
                            for ($j=0; $j < $tot; $j++) { 
                                // get bank_advance_detail
                                $ID_bank_advance = $G_dt[$j]['ID'];
                                $__Detail = $this->m_master->caribasedprimary('db_payment.bank_advance_detail','ID_bank_advance',$ID_bank_advance);
                                for ($k=0; $k < count($__Detail); $k++) { 
                                    $__Detail_Realisasi = $this->m_master->caribasedprimary('db_payment.bank_advance_realisasi_detail','ID_bank_advance_detail',$__Detail[$k]['ID']);
                                   $__Detail[$k]['Realisasi'] =  $__Detail_Realisasi;
                                }

                                $__Realisasi = $this->m_master->caribasedprimary('db_payment.bank_advance_realisasi','ID_bank_advance',$ID_bank_advance);
                                for ($l=0; $l < count($__Realisasi); $l++) { 
                                   $__JsonStatus = $__Realisasi[$l]['JsonStatus'];
                                   $__JsonStatus_de = json_decode($__JsonStatus,true);
                                   for ($m=0; $m < count($__JsonStatus_de); $m++) { 
                                       $NIP_Re = $__JsonStatus_de[$j]['NIP'];
                                       $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP_Re);
                                       $__JsonStatus_de[$j]['Name'] = $G_emp[0]['Name'];
                                   }

                                    $__JsonStatus = json_encode($__JsonStatus_de);
                                    $__Realisasi[$l]['JsonStatus'] = $__JsonStatus;
                                }

                                $G_dt[$j]['Detail'] = $__Detail;
                                $G_dt[$j]['Realisasi'] = $__Realisasi;
                            }
                            $Detail = $G_dt;
                            break;
                        case 'Cash Advance':
                            $G_dt = $this->m_master->caribasedprimary('db_payment.cash_advance','ID_payment',$ID);
                            $tot2 = count($G_dt);
                            for ($j=0; $j < $tot2; $j++) { 
                                // get bank_advance_detail
                                $ID_cash_advance = $G_dt[$j]['ID'];
                                $__Detail = $this->m_master->caribasedprimary('db_payment.cash_advance_detail','ID_cash_advance',$ID_cash_advance);
                                for ($k=0; $k < count($__Detail); $k++) { 
                                    $__Detail_Realisasi = $this->m_master->caribasedprimary('db_payment.cash_advance_realisasi_detail','ID_cash_advance_detail',$__Detail[$k]['ID']);
                                   $__Detail[$k]['Realisasi'] =  $__Detail_Realisasi;
                                }
                                $__Realisasi = $this->m_master->caribasedprimary('db_payment.cash_advance_realisasi','ID_cash_advance',$ID_cash_advance);

                                for ($l=0; $l < count($__Realisasi); $l++) { 
                                   $__JsonStatus = $__Realisasi[$l]['JsonStatus'];
                                   $__JsonStatus_de = json_decode($__JsonStatus,true);
                                   for ($m=0; $m < count($__JsonStatus_de); $m++) { 
                                       $NIP_Re = $__JsonStatus_de[$j]['NIP'];
                                       $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP_Re);
                                       $__JsonStatus_de[$j]['Name'] = $G_emp[0]['Name'];
                                   }

                                    $__JsonStatus = json_encode($__JsonStatus_de);
                                    $__Realisasi[$l]['JsonStatus'] = $__JsonStatus;
                                }
                                
                                $G_dt[$j]['Detail'] = $__Detail;
                                $G_dt[$j]['Realisasi'] = $__Realisasi;
                            }
                            $Detail = $G_dt;
                            break;    
                        default:
                            # code...
                            break;
                    }

                    $query[$i]['Detail'] = $Detail;
                }

                // add good_receipt_spb & good_receipt_detail
                $Good_Receipt = array();
                $G_good_receipt_spb = $this->m_master->caribasedprimary('db_purchasing.good_receipt_spb','ID_payment',$ID); // spb , cash advance, bank advance
                for ($j=0; $j < count($G_good_receipt_spb); $j++) { 
                    $ID_good_receipt_spb = $G_good_receipt_spb[$j]['ID'];
                    $G_good_receipt_detail = $this->m_master->caribasedprimary('db_purchasing.good_receipt_detail','ID_good_receipt_spb',$ID_good_receipt_spb);
                    $G_good_receipt_spb[$j]['Detail'] = $G_good_receipt_detail;
                }

                $Good_Receipt = $G_good_receipt_spb;
                $query[$i]['Good_Receipt'] = $Good_Receipt;

                // check ap udah bayar atau belum
                $FinanceAP = array();
                $FinanceAP = $this->m_master->caribasedprimary('db_budgeting.ap','ID_payment',$ID);

                $query[$i]['FinanceAP'] = $FinanceAP;
            }
            $arr['dtspb']=$query;
        }
        
        return $arr;
    }

    public function payment_circulation_sheet($ID_payment,$Desc,$By = '')
    {
        if ($By ==  '') {
            $By = $this->session->userdata('NIP');
        }
        $dataSave = array(
            'ID_payment' => $ID_payment,
            'Desc' => $Desc,
            'Date' => date('Y-m-d'),
            'By' => $By,
        );

        $this->db->insert('db_payment.payment_circulation_sheet',$dataSave);
    }

    public function Get_SPBCode($Departement)
    {
        /* method PR
           Code : 05/UAP-IT/SPB/IX/2018
           05 : Increment (Max length = 2)
           UAP- : Fix
           IT : Division Abbreviation
           SPB : Fix
           IX : Bulan dalam romawi
           2018 : Get Years Now
        */
        $Code = '';   
        $Year = date('Y');
        $Month = date('m');
        $Month = $this->m_master->romawiNumber($Month);
        $MaxLengthINC = 2;
        
        $sql = 'select * from db_payment.payment 
                where Departement = ? and SPLIT_STR(Code, "/", 5) = ?
                and SPLIT_STR(Code, "/", 4) = ?
                and Type = "Spb"
                order by SPLIT_STR(Code, "/", 1) desc
                limit 1';
        $query=$this->db->query($sql, array($Departement,$Year,$Month))->result_array();
        if (count($query) == 1) {
            // Inc last code
            $Code = $query[0]['Code'];
            $explode = explode('/', $Code);
            $C = $explode[0];
            $C = (int) $C;
            $C = $C + 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }

            $explode[0] = $strINC;
            $Code = implode('/', $explode);
        }
        else
        {
            $C = 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }

            // get abbreviation department
                $ExpDepart = explode('.', $Departement);
                $abbreviation_Div = '';
                if ($ExpDepart[0] == 'NA') {
                    $G_Div = $this->m_master->caribasedprimary('db_employees.division','ID',$ExpDepart[1]);
                    $abbreviation_Div = $G_Div[0]['Abbreviation'];
                }
                elseif ($ExpDepart[0] == 'AC') {
                    $G_Div = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ExpDepart[1]);
                    $abbreviation_Div = $G_Div[0]['Code'];
                }
                else
                {
                    $G_Div = $this->m_master->caribasedprimary('db_academic.faculty','ID',$ExpDepart[1]);
                    $abbreviation_Div = $G_Div[0]['Abbr'];
                }

            $Code = $strINC.'/'.'UAP-'.$abbreviation_Div.'/'.'SPB'.'/'.$Month.'/'.$Year;
        }    
        return $Code;        

    }

}