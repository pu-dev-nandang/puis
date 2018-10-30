<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_report extends Finnance_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement();
        $this->load->model('finance/m_finance');
        $this->load->model('m_sendemail');
        $this->load->model('admission/m_admission');
        $this->load->model('master/m_master');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function reportTagihanMHS()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/tagihan_mahasiswa/report',$this->data,true);
        $this->temp($content);
    }

    public function get_reportingTagihanMHS($page = null)
    {
        $input = $this->getInputToken();
        $this->load->library('pagination');
        // per page 2 database
        $sqlCount = 'show databases like "%ta_2%"';
        $queryCount=$this->db->query($sqlCount, array())->result_array();

        $config = $this->config_pagination_default_ajax(count($queryCount),1,3);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(3);
        $start = ($page - 1) * $config["per_page"];
        $data = $this->m_finance->get_report_pembayaran_mhs($input['ta'],$input['prodi'],$input['NIM'],$input['Semester'],$input['Status'],$config["per_page"], $start);
        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $data,
        );
        echo json_encode($output);
    }

    public function report_admission()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/report_admission/report_admission',$this->data,true);
        $this->temp($content);
    }

}
