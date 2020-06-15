<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');

class C_react_mobile extends CI_Controller {
    public $data = [];
    private $keyToken = 's3Cr3T-G4N';
    function __construct(){
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->library('JWT');
        try {
          $G_setting = $this->m_master->showData_array('db_ticketing.rest_setting');
          if (!$this->auth($G_setting)) {
            echo '{"status":"999","message":"Not Authenfication"}'; 
            die();
          }
          else
          {
            header('Access-Control-Allow-Origin: *');
          }
        } catch (Exception $e) {
          echo json_encode($e);
          die();
        }
    }

    private function auth($G_setting){
      $Bool = false;
      try {
        $dataToken = $this->getInputToken();
        $getallheaders = getallheaders();
        foreach ($getallheaders as $name  => $value) {
          if ($name == 'Hjwtkey' && $value == $G_setting[0]['Hjwtkey']) {
            // cek api get
            if(isset($_GET['apikey']) && $_GET['apikey'] == $G_setting[0]['Apikey'] && array_key_exists("auth",$dataToken) &&  $dataToken['auth'] == $this->keyToken ) {
                $Bool = true;
                break;
            } 
          }
        }

        return $Bool;
      } catch (Exception $e) {
         echo json_encode($e);
         die();
      }

      return false;
    }

    private function __addHttpOrhttps($arr){
      $rs = [];
      for ($i=0; $i < count($arr); $i++) { 
        $rs[] = 'http://'.$arr[$i];
        $rs[] = 'https://'.$arr[$i];
      }
      return $rs;
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        $data_arr = json_decode(json_encode($data_arr),true);
        return $data_arr;
    }

    public function LoginMobile(){
      $rs = ['status' => -1,'msg' => '','callback' => []];
      $dataToken = $this->getInputToken();
      $data = $dataToken['data'];
      $NIP = $data['NIP'];
      $Password = $this->m_master->genratePassword($NIP, $data['Password']);
      $dataEMP = $this->db->query(
        'select * from db_employees.employees where NIP = "'.$NIP.'" and Password = "'.$Password.'" 
        and Status = "1"
         '
      )->result_array();
      $RuleUser = $this->db->query('SELECT * FROM db_employees.rule_users WHERE NIP LIKE "'.$NIP.'"')->result_array();
      if (count($dataEMP) > 0 && count($RuleUser) > 0 ) {
        // read rule user
         $DeptList = [];

         for ($i=0; $i <count($RuleUser) ; $i++) { 
           switch ($RuleUser[$i]['IDDivision']) {
             case '15': // Prodi
             case 15:
               $Auth_prodi = $this->m_master->caribasedprimary('db_prodi.auth_prodi','NIP',$NIP);
               if (count($Auth_prodi) > 0) {
                 $Auth_prodi =   $ProdiAuth[0]['ProdiAuth'];
                 $Auth_prodi =   json_decode($Auth_prodi,true);
                 for ($j=0; $j < count($Auth_prodi); $j++) { 
                   $ProdiID =  $Auth_prodi[$i];
                   $d = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ProdiID);
                   $DeptList[] = [
                    'DepartmenID' => 'AC.'.$ProdiID,
                    'DepartmentName' => 'Prodi '.$d[0]['Name'],
                   ];
                 }
               }
               
               break;
             
             case '34':
             case 34:
              $a_ID = $this->m_master->caribasedprimary('db_academic.faculty','AdminID',$NIP);
              $k_ID = $this->m_master->caribasedprimary('db_academic.faculty','NIP',$NIP);
              if (count($a_ID) > 0) {
                  $DeptList[] = [
                   'DepartmenID' => 'FT.'.$a_ID[0]['ID'],
                   'DepartmentName' => 'Fakultas '.$a_ID[0]['Name'],
                  ];
              }
              elseif (count($k_ID) > 0) {
                  $DeptList[] = [
                   'DepartmenID' => 'FT.'.$k_ID[0]['ID'],
                   'DepartmentName' => 'Fakultas '.$k_ID[0]['Name'],
                  ];
              }
              
              break;  

             default:
               $DeptDiv = $this->db->query(
                  'select * from db_employees.division where ID = '.$RuleUser[$i]['IDDivision'].'  '
               )->result_array();
               $DeptList[] = [
                'DepartmenID' => 'NA.'.$DeptDiv[0]['ID'] ,
                'DepartmentName' => $DeptDiv[0]['Division'],
               ];
               break;
           }
         }

         $rs['status'] = 1;
         $rs['callback'] = [
          'data' => $dataEMP,
          'DeptList' => $DeptList
         ];
         // set expired for auto login
         $timestamp = date('Y-m-d H:i:s');
         $start_date = date($timestamp);
         $expires = strtotime('+30 days', strtotime($timestamp));
         $rs['callback']['expiresIn'] = $expires;
      }

      echo json_encode($rs);


    }

}