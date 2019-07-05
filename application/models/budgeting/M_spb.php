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
        $Code_po_create = $data_verify['Code_po_create'];
        $InvoicePO = $data_verify['InvoicePO'];
        $InvoiceLeftPO = $data_verify['InvoiceLeftPO'];

        $G_dt = $this->m_master->caribasedprimary('db_purchasing.');
    }

    public function get_spb_gr_by_po($Code_po_create)
    {
        $arr = array(
            'dtspb'=> array(),
            'dtgood_receipt_spb'=>array(),
            'dtgood_receipt_detail'=> array(),
        );

        $sql = 'select a.*,b.Name as NameBank,c.Name as NameCreatedBy from db_purchasing.spb_created as a
                join db_finance.bank as b on a.ID_bank = b.ID
                join db_employees.employees as c on a.CreatedBy = c.NIP
                where a.Code_po_create = ? limit 1
                ';
        $query=$this->db->query($sql, array($Code_po_create))->result_array();
        if (count($query) > 0) {
            $JsonStatus = $query[0]['JsonStatus'];
            // decode first
            $JsonStatus = json_decode($JsonStatus,true);
            for ($j=0; $j < count($JsonStatus); $j++) { 
                $NIP = $JsonStatus[$j]['NIP'];
                $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
                $JsonStatus[$j]['Name'] = $G_emp[0]['Name'];
            }

            $JsonStatus = json_encode($JsonStatus);
            $query[0]['JsonStatus'] = $JsonStatus;
            $arr['dtspb']=$query;

            // Get ID untu GRPO
            $ID = $query[0]['ID'];
            $arr['dtgood_receipt_spb']=$this->m_master->caribasedprimary('db_purchasing.good_receipt_spb','ID_spb_created',$ID);
            $ID_good_receipt_spb = $arr['dtgood_receipt_spb'][0]['ID'];
            $sql = 'select ';
            $tt=$this->m_master->caribasedprimary('db_purchasing.good_receipt_detail','ID_good_receipt_spb',$ID_good_receipt_spb);
            for ($i=0; $i < count($t); $i++) { 
               $ID_po_detail =$t[$i]['ID_po_detail'];
               $G_dt = $this->m_pr_po->Get_data_po_by_ID_po_detail($ID_po_detail);
               $tt[$i]['Detail'] = $G_dt;
            }
            $arr['dtgood_receipt_detail'] = $tt;
        }
        
        return $arr;
    }

}