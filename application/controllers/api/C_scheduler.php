<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');

class C_scheduler extends CI_Controller {
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

    public function APS(){
      $this->data=['status' => 0,'msg' => '','callback' => []];
      $dataToken = $this->getInputToken();
      $table = $dataToken['table'];
      switch ($table) {
        case 's1':
          $this->__s1();
          break;
        
        default:
          # code...
          break;
      }
    }


    private function getProdi(){
      return $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
    }

    private function __s1(){
      // save to log
      // $this->db->insert('aps_apt_rekap',[
      //   'RunTime' => date('Y-m-d H:i:s'),
      //   'TableName' => 's1'
      // ]);

      // $ID = $this->db->insert_id();

      // get Prodi first
      $dataProdi = $this->getProdi();
      $urlPost = base_url().'rest2/__get_data_kerja_sama_perguruan_tinggi';
      $data_post = [
        'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdXRoIjoiczNDcjNULUc0TiIsIm1vZGUiOiJEYXRhS2VyamFTYW1hQWdncmVnYXRvciIsIlByb2RpSUQiOiI0In0.91PdePvZuzA4NcnbbGubucQB-OVOyrnMEhfb-vNXWOQ',
        'start' => 0,
        'length' => 10000,
        'search[value]' => '',
        'search[regex]' => false,
        'draw' => 1,
      ];
      $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
      print_r($postTicket);


    }

}