<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_company extends HR_Controler {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('hr/m_hr','General_model'));
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function company($page)
    {
        $department = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$department.'/company/index',$data,true);
        $this->temp($content);
    }

    public function index(){
        $data= array();
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/company/fetch',$data,true);
        $this->company($page);
    }
    
    public function fetch(){
        $department = parent::__getDepartement();
        $data= $this->input->post();
        if($data){
        	$this->load->view('page/'.$department.'/company/fetch',$data,false);
        }else{show_404();}
        
    }

    public function form(){
        $data= array();
        $department = parent::__getDepartement();
        $this->load->view('page/'.$department.'/company/form',$data,false);
        
    }


}