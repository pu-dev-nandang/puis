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

    

}
