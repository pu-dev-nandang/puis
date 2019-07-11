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
            'dtgood_receipt_spb'=>array(),
            'dtgood_receipt_detail'=> array(),
        );

        $sql = 'select a.*,b.Name as NameBank,c.Name as NameCreatedBy,
                poi.InvoicePO,poi.InvoicePayPO,InvoiceLeftPO,poi.Status as StatusPOI,poi.ID as ID_poi
                from db_payment.payment as a
                left join db_payment.spb as spb on a.ID = spb.ID_payment
                left join db_finance.bank as b on spb.ID_bank = b.ID
                left join db_employees.employees as c on a.CreatedBy = c.NIP
                left join db_purchasing.po_invoice_status as poi on a.Code_po_create = poi.Code_po_create
                where a.Code_po_create = ? limit 1
                ';
        $query=$this->db->query($sql, array($Code_po_create))->result_array();
        if (count($query) > 0) {
            $JsonStatus = $query[0]['JsonStatus'];
            if ($JsonStatus != '' && $JsonStatus != null) {
                // decode first
                $JsonStatus = json_decode($JsonStatus,true);
                for ($j=0; $j < count($JsonStatus); $j++) { 
                    $NIP = $JsonStatus[$j]['NIP'];
                    $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
                    $JsonStatus[$j]['Name'] = $G_emp[0]['Name'];
                }

                $JsonStatus = json_encode($JsonStatus);
                $query[0]['JsonStatus'] = $JsonStatus;
            }
            
            $arr['dtspb']=$query;

            // Get ID untu GRPO
            $ID = $query[0]['ID'];
            $arr['dtgood_receipt_spb']=$this->m_master->caribasedprimary('db_purchasing.good_receipt_spb','ID_payment',$ID);
            $ID_good_receipt_spb = $arr['dtgood_receipt_spb'][0]['ID'];
            $sql = 'select ';
            $tt=$this->m_master->caribasedprimary('db_purchasing.good_receipt_detail','ID_good_receipt_spb',$ID_good_receipt_spb);
            for ($i=0; $i < count($tt); $i++) { 
               $ID_po_detail =$tt[$i]['ID_po_detail'];
               $G_dt = $this->m_pr_po->Get_data_po_by_ID_po_detail($ID_po_detail);
               $tt[$i]['Detail'] = $G_dt;
            }
            $arr['dtgood_receipt_detail'] = $tt;
        }
        
        return $arr;
    }

    public function spb_grpo_circulation_sheet($Code,$Desc,$By = '')
    {
        if ($By ==  '') {
            $By = $this->session->userdata('NIP');
        }
        $dataSave = array(
            'Code' => $Code,
            'Desc' => $Desc,
            'Date' => date('Y-m-d'),
            'By' => $By,
        );

        $this->db->insert('db_purchasing.spb_circulation_sheet',$dataSave);
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
        
        $sql = 'select * from db_purchasing.spb_created 
                where Departement = ? and SPLIT_STR(Code, "/", 5) = ?
                and SPLIT_STR(Code, "/", 4) = ?
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