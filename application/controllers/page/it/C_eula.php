<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_eula extends It_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement();
        $this->load->model('m_api');
        // $this->load->model('master/m_master');
    }


    private function menu_eula($page){
        $department = parent::__getDepartement();
        $data['NIP']= $this->session->userdata('NIP');
        $data['page']= $page;
        $content = $this->load->view('page/'.$department.'/eula/menu_eula',$data,true);
        $this->temp($content);
    }

    public function create_eula()
    {
        $department = parent::__getDepartement();
        $data['NIP']= $this->session->userdata('NIP');
        $page = $this->load->view('page/'.$department.'/eula/create_eula',$data,true);
        $this->menu_eula($page);
    }

    public function list_eula()
    {
        $department = parent::__getDepartement();
        $data['NIP']= $this->session->userdata('NIP');
        $page = $this->load->view('page/'.$department.'/eula/list_eula',$data,true);
        $this->menu_eula($page);
    }




}
