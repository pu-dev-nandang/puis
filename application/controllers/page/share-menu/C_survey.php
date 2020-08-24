<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class C_survey extends Globalclass {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('master/m_master','General_model','General_model','global-informations/Globalinformation_model','hr/m_hr','m_log_content'));
        $this->load->helper("General_helper");
    }
    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_survey($page){
        $data['page'] = $page;
        $content = $this->load->view('page/share-menu/survey/menu_survey',$data,true);
        $this->temp($content);
    }

    public function list_survey(){
        $page = $this->load->view('page/share-menu/survey/list_survey','',true);
        $this->menu_survey($page);
    }

    public function create_survey(){
        $page = $this->load->view('page/share-menu/survey/create_survey','',true);
        $this->menu_survey($page);
    }

    public function create_question(){
        $page = $this->load->view('page/share-menu/survey/create_question','',true);
        $this->menu_survey($page);
    }

}
