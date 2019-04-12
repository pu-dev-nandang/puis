<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_it extends It_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement(); 
        $this->load->model('m_api');
        $this->load->model('master/m_master');
    }

    
    public function dashboard()
    {
      $data['department'] = parent::__getDepartement();
      $content = $this->load->view('dashboard/dashboard',$data,true);
      $this->temp($content);
    }

    public function rule_service_user()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/ruleservice/page',$this->data,true);
      $this->temp($content);
    }

    public function version_data(){
      
      $department = parent::__getDepartement();

      $data['ProdiArr'] = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
      $page = $this->load->view('page/'.$department.'/version/menu_version',$data,true);
      $this->temp($page);

      // ----old code ----
      //$department = parent::__getDepartement();
      //print_r($department);
      //$data['ProdiArr'] = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
      //$page = $this->load->view('page/'.$department.'/version/version_data',$data,true);
      //$this->temp($page);
     
    }

    public function version_menu(){
      $department = parent::__getDepartement();

      $data['NIP']=$NIP;
      $content = $this->load->view('page/'.$department.'/academic/academic_menu',$data,true);
      $this->temp($content);

    }


    public function loadpageversiondetail(){
        $department = parent::__getDepartement();
        $data_arr = $this->getInputToken();
        $G_TypeFiles = $this->m_master->showData_array('db_employees.master_files');
        $data_arr['G_TypeFiles'] =  $G_TypeFiles;
        $this->load->view('page/'.$department.'/version/'.$data_arr['page'], $data_arr);
    }






    

}
