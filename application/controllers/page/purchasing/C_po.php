<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_po extends Transaksi_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement(); 
        $this->load->model('m_api');
        $this->load->model('budgeting/m_budgeting');
        $this->load->model('budgeting/m_pr_po');
        $this->load->model('master/m_master');
    }

    public function index()
    {
        $page['content'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/list',$this->data,true);
        $this->page_po($page);
    }

    public function find_vendor()
    {
       $page['content'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/find_vendor',$this->data,true);
       $this->page_po($page); 
    }

}
