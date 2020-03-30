<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');

class C_rest_global extends CI_Controller {
    public $data = [];
    private $keyToken = 's3Cr3T-G4N';
    private $callback = ['status' => 0,'msg' => '','callback' => array() ];
    function __construct(){
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('m_rest_global');
        $this->load->library('JWT');
        try {
          $G_setting = $this->m_master->showData_array('db_it.rest_setting');
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

    private function getInputToken()
    {
      $token = $this->input->post('token');
      $key = "UAP)(*";
      $data_arr = (array) $this->jwt->decode($token,$key);
      $data_arr = json_decode(json_encode($data_arr),true);
      return $data_arr;
    }

    public function api_Biodata_MHS(){
      $input = $this->getInputToken();
      $data = $input['data'];
      $NPM = $data['NPM'];
      $result = $this->m_rest_global->api_Biodata_MHS($NPM);
      echo json_encode($result);
    }

    public function load_university_or_instansi(){
      $input = $this->getInputToken();
      $result = $this->m_rest_global->api_university_or_instansi($input);
      echo json_encode($result);
    }
}