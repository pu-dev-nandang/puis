<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_requestdocument extends Globalclass {

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('database/m_database');
        $this->load->library('JWT');
    }


     public function temp($content)
    {
        parent::template($content);
    }

    public function menu_request($page){
        $data['page'] = $page;
        $content = $this->load->view('page/rektorat/menu_rektorat',$data,true);
        $this->temp($content);
    }


    public function list_requestdocument()
    {
        $page = $this->load->view('page/rektorat/list_requestdocument','',true);
        $this->menu_request($page);
    }

    public function frm_requestdocument() {
        $page = $this->load->view('page/rektorat/form_request','',true);
        $this->menu_request($page);
    }

    



}
