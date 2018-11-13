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
        case 'edit':
          $FormUpdate = (array) $input['FormUpdate'];
          for ($i=0; $i < count($FormUpdate); $i++) { 
            $dataSave = array();
            $ID = $FormUpdate[$i]->ID;
            foreach ($FormUpdate[$i] as $key => $value) {
              if ($key != 'ID') {
                $dataSave[$key] = $value;
              }
            }
            // print_r($dataSave);die();
            $this->db->where('ID',$ID);
            $this->db->update('db_employees.division', $dataSave);
          }
          break;
          case 'delete':
            $this->m_master->delete_id_table_all_db($input['CDID'],'db_employees.division');
          break;
        default:
          # code...
          break;
      } 
    }

    public function saveService()
    {
      $input = $this->getInputToken();
      switch ($input['Action']) {
         case 'add':
           $FormSave = (array) $input['SaveForm'];
           $this->db->insert('db_employees.service', $FormSave);
           break;
         case 'edit':
           $FormUpdate = (array) $input['FormUpdate'];
           for ($i=0; $i < count($FormUpdate); $i++) { 
             $dataSave = array();
             $ID = $FormUpdate[$i]->ID;
             foreach ($FormUpdate[$i] as $key => $value) {
               if ($key != 'ID') {
                 $dataSave[$key] = $value;
               }
             }
             // print_r($dataSave);die();
             $this->db->where('ID',$ID);
             $this->db->update('db_employees.service', $dataSave);
           }
           break;
           case 'delete':
             $this->m_master->delete_id_table_all_db($input['CDID'],'db_employees.service');
           break;
         default:
           # code...
           break;
       }
    }

    public function saveRuleService()
    {
      $input = $this->getInputToken();
      $msg = '';
      switch ($input['Action']) {
         case 'add':
           $FormSave = (array) $input['SaveForm'];
            // check data exist
           $chk = function($FormSave){
            $sql = 'select * from db_employees.rule_service where IDDivision = ? and IDService = ?';
            $query=$this->db->query($sql, array($FormSave['IDDivision'],$FormSave['IDService']))->result_array();
            if (count($query) == 0) {
              return true;
            }
            else
            {
              return false;
            }
           };

           $chk1 = $chk($FormSave);
           if ($chk1) {
             $this->db->insert('db_employees.rule_service', $FormSave);
           }
           else
           {
            $msg = 'Data Duplicate';
           }
           echo json_encode($msg);
           break;
         case 'edit':
           $FormUpdate = (array) $input['FormUpdate'];
           $chk = function($FormUpdate){
            $find = true;
            for ($i=0; $i < count($FormUpdate); $i++) {
              $find2 = true; 
              for ($j=$i+1; $j < count($FormUpdate); $j++) {
                $IDDivision1 = $FormUpdate[$i]->IDDivision;
                $IDService1 = $FormUpdate[$i]->IDService;
                $IDDivision2 = $FormUpdate[$j]->IDDivision;
                $IDService2 = $FormUpdate[$j]->IDService;
                if ($IDDivision1 == $IDDivision2 && $IDService1 == $IDService2) {
                  $find2 = false;
                  break; 
                }
              }
              if (!$find2) {
                $find = false;
                break;
              }
              else
              {
                $sql = 'select * from db_employees.rule_service where IDDivision = ? and IDService = ? and ID != ?';
                $query=$this->db->query($sql, array($FormUpdate[$i]->IDDivision,$FormUpdate[$i]->IDService,$FormUpdate[$i]->ID))->result_array();
                if (count($query) > 0) {
                  $find = false;
                  break;
                }
              }
            }
            return $find;
           };
           $chk1 = $chk($FormUpdate);
           if ($chk1) {
             for ($i=0; $i < count($FormUpdate); $i++) { 
               $dataSave = array();
               $ID = $FormUpdate[$i]->ID;
               foreach ($FormUpdate[$i] as $key => $value) {
                 if ($key != 'ID') {
                   $dataSave[$key] = $value;
                 }
               }
               // print_r($dataSave);die();
               $this->db->where('ID',$ID);
               $this->db->update('db_employees.rule_service', $dataSave);
             }
           }
           else
           {
            $msg = 'Data Duplicate';
           }
           echo json_encode($msg);
           break;
           case 'delete':
             $this->m_master->delete_id_table_all_db($input['CDID'],'db_employees.rule_service');
           break;
         default:
           # code...
           break;
       }
    }

    public function saveRuleUser()
    {
      $input = $this->getInputToken();
      $msg = '';
      switch ($input['Action']) {
         case 'add':
           $FormSave = (array) $input['SaveForm'];
            // check data exist
           $chk = function($FormSave){
            $sql = 'select * from db_employees.rule_users where IDDivision = ? and NIP = ?';
            $query=$this->db->query($sql, array($FormSave['IDDivision'],$FormSave['NIP']))->result_array();
            if (count($query) == 0) {
              return true;
            }
            else
            {
              return false;
            }
           };

           $chk1 = $chk($FormSave);
           if ($chk1) {
             $this->db->insert('db_employees.rule_users', $FormSave);
           }
           else
           {
            $msg = 'Data Duplicate';
           }
           echo json_encode($msg);
           break;
         case 'edit':
           $FormUpdate = (array) $input['FormUpdate'];
           $chk = function($FormUpdate){
            $find = true;
            for ($i=0; $i < count($FormUpdate); $i++) {
              $find2 = true; 
              for ($j=$i+1; $j < count($FormUpdate); $j++) {
                $IDDivision1 = $FormUpdate[$i]->IDDivision;
                $NIP1 = $FormUpdate[$i]->NIP;
                $IDDivision2 = $FormUpdate[$j]->IDDivision;
                $NIP2 = $FormUpdate[$j]->NIP;
                if ($IDDivision1 == $IDDivision2 && $NIP1 == $NIP2) {
                   // print_r($IDDivision1.'=='.$IDDivision2.' ; '.$NIP1.'=='.$NIP2.' ; '.$i.' -> '.$j);die();
                  $find2 = false;
                  break; 
                }
              }
              if (!$find2) {
                $find = false;
                break;
              }
              else
              {
                $sql = 'select * from db_employees.rule_users where IDDivision = ? and NIP = ? and ID != ?';
                $query=$this->db->query($sql, array($FormUpdate[$i]->IDDivision,$FormUpdate[$i]->NIP,$FormUpdate[$i]->ID))->result_array();
                if (count($query) > 0) {
                  $find = false;
                  break;
                }
              }
            }
            return $find;
           };
           $chk1 = $chk($FormUpdate);
           if ($chk1) {
             for ($i=0; $i < count($FormUpdate); $i++) { 
               $dataSave = array();
               $ID = $FormUpdate[$i]->ID;
               foreach ($FormUpdate[$i] as $key => $value) {
                 if ($key != 'ID') {
                   $dataSave[$key] = $value;
                 }
               }
               // print_r($dataSave);die();
               $this->db->where('ID',$ID);
               $this->db->update('db_employees.rule_users', $dataSave);
             }
           }
           else
           {
            $msg = 'Data Duplicate';
           }
           echo json_encode($msg);
           break;
           case 'delete':
             $this->m_master->delete_id_table_all_db($input['CDID'],'db_employees.rule_users');
           break;
         default:
           # code...
           break;
       }
    }

}
