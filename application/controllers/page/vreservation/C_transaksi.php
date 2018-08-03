<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_transaksi extends Vreservation_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
        $this->load->library('JWT');
        $this->load->model('master/m_master');
    }


    public function booking()
    {
        $content = $this->load->view($this->pathView.'transaksi/booking','',true);
        $this->temp($content);
    }

}
