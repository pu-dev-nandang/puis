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

}