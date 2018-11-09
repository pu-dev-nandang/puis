<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_rule_service extends It_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement(); 
        $this->load->model('m_api');
        $this->load->model('master/m_master');
    }

    public function Page()
    {
      $arr_result = array('html' => '','jsonPass' => '');
      $uri = $this->uri->segment(3);
      $content = $this->load->view('page/'.$this->data['department'].'/ruleservice/'.$uri,$this->data,true);
      $arr_result['html'] = $content;
      echo json_encode($arr_result);
    }

    public function saveDivision()
    {
     $input = $this->getInputToken();
     switch ($input['Action']) {
        case 'add':
          $FormSave = (array) $input['SaveForm'];
          $this->db->insert('db_employees.division', $FormSave);
          break;
        
        default:
          # code...
          break;
      } 
    }

}
