<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');

class C_api_doc extends CI_Controller {
    public $data = [];
    private $keyToken = 's3Cr3T-G4N';
    function __construct(){
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('m_api_doc');
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

    public function Docx_permohonanBiayaKuliah(){
      $FileTemplate = './uploads/template/Template_surat_permohonan_cicilan.docx';
      $dataToken = $this->getInputToken();
      $excute = $this->m_api_doc->exeDocx_permohonanBiayaKuliah($dataToken,$FileTemplate);
      echo json_encode($excute);
    }

}