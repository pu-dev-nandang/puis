<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bkd extends Globalclass {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('m_sendemail');
        $this->load->model('database/m_database');
        $this->load->library('JWT');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_bkd($page){
        $data['page'] = $page;
        $content = $this->load->view('page/bkd/menu_bkd',$data,true);
        $this->temp($content);
    }

    public function lecturer_list()
    {
        $page = $this->load->view('page/bkd/lecturer_list','',true);
        $this->menu_bkd($page);
    }



}
