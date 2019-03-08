<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_announcement extends Globalclass {

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

    public function menu_announcement($page){
        $data['page'] = $page;
        $content = $this->load->view('page/announcement/menu_announcement',$data,true);
        $this->temp($content);
    }

    public function list_announcement()
    {
        $page = $this->load->view('page/announcement/list_announcement','',true);
        $this->menu_announcement($page);
    }



}
