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

      echo json_encode($this->data);
    }


    private function getProdi(){
      return $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
    }

    private function __s1(){
      // save to log
      $this->db->insert('aps_apt_rekap.log',[
        'RunTime' => date('Y-m-d H:i:s'),
        'TableName' => 's1'
      ]);

      $ID = $this->db->insert_id();

      // get Prodi first
      $dataProdi = $this->getProdi();
      $param = [
        'auth' => 's3Cr3T-G4N',
        'mode' => 'DataKerjaSamaAggregator',
      ];
      $urlPost = base_url().'rest2/__get_data_kerja_sama_perguruan_tinggi';
      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d');
      for ($i=0; $i < count($dataProdi); $i++) { 
        $ProdiID = $dataProdi[$i]['ID'];
        $param['ProdiID'] = $ProdiID;
        $token = $this->jwt->encode($param,"UAP)(*");
        $data_post = [
          'token' => $token,
          'start' => 0,
          'length' => 10000,
          'search[value]' => '',
          'search[regex]' => false,
          'draw' => 1,
        ];
        try {
          // remove old data first by years and month
          $this->db->query(
            'delete from aps_apt_rekap.s1 where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
          );

          $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
          $result = (array) json_decode($postTicket,true);
          $getData =  $result['data'];
          for ($j=0; $j < count($getData); $j++) { 
            $row = $getData[$j];
            $Tingkat = 'Internasional';
            if ($row[3] == 1) {
              $Tingkat = 'Nasional';
            }

            if ($row[4] == 1) {
              $Tingkat = 'Lokal';
            }

            $WaktuDurasi = 'Start : '.$row[12]."\n".'End : '.$row[8]."\n".$row[16].' days';
            $file =  (array) json_decode($row[7],true);
            $BuktiKerjasama =base_url().'fileGetAny/cooperation-'.$file[0];
            $dataSave = [
              'ProdiID' => $ProdiID,
              'Lembaga' => $row[1],
              'Kategory' =>   $row[13],
              'Tingkat' =>   $Tingkat,
              'JudulKegiatan' =>   $row[10],
              'Manfaat' =>   $row[11],
              'WaktuDurasi' =>   $WaktuDurasi ,
              'BuktiKerjasama' =>   $BuktiKerjasama,
              'MasaBerlaku' =>   $row[8],
              'Semester' =>   $row[9],
              'DateCreated' =>   $DateCreated,
            ];

            $this->db->insert('aps_apt_rekap.s1',$dataSave);

          }
          
        } catch (Exception $e) {
           print_r($e);
        }

      }

      $this->data['status'] = 1;
      if ($this->data['status'] == 1) {
        $this->db->where('ID',$ID);
        $this->db->update('aps_apt_rekap.log',['Status' => 1]);
      }
    }

}