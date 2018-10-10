<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_report extends Admission_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('admission/m_admission');
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement(); 
        $this->load->model('m_api');
    }

    public function pageViewReport1()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/report/pageViewReport1',$this->data,true);
      $this->temp($content);
    }
}
