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
         $this->load->model(array('m_api'));
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
        case 's2a':
          $this->__s2a();
          break;
        case 's2b':
          $this->__s2b();
          break;
        case 's3a1':
          $this->__s3a1();
          break;
        default:
          # code...
          break;
      }

      echo json_encode($this->data);
    }


    private function getProdi(){
      return $this->m_api->__getBaseProdiSelectOption();
    }

    private function __s2a(){
      // save to log
      $this->db->insert('aps_apt_rekap.log',[
        'RunTime' => date('Y-m-d H:i:s'),
        'TableName' => 's2a'
      ]);

      $ID = $this->db->insert_id();
      // get Prodi first
      $dataProdi = $this->getProdi();
      $param = [
        'action' => 'readDataMHSBaruByProdi',
      ];
      $urlPost = base_url().'api3/__crudAgregatorTB2';
      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d');
      for ($i=0; $i < count($dataProdi); $i++) { 
        $param['filterProdi'] = $dataProdi[$i]['ID'].'.'.$dataProdi[$i]['Code'];
        $param['filterProdiName'] = $dataProdi[$i]['Level'].' - '.$dataProdi[$i]['NameEng'];
        $ProdiID = $dataProdi[$i]['ID'];
        $token = $this->jwt->encode($param,"UAP)(*");
        $data_post = [
          'token' => $token,
        ];
        
        try {
          // remove old data first by years and month
          $this->db->query(
            'delete from aps_apt_rekap.s2a where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
          );
          $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
          $result = (array) json_decode($postTicket,true);
          for ($j=0; $j < count($result); $j++) { 
            $row = $result[$j];
            $dataSave = [
              'ProdiID' => $ProdiID,
              'TahunAkademik' => $row['Year'],
              'Capacity' => $row['Capacity'],
              'JCM_Pendaftar' =>   $row['Registrant'],
              'JCM_LulusSeleksi' =>   $row['PassSelection'],
              'JMB_Reguler' =>   $row['Regular'],
              'JMB_Transfer' =>   $row['Transfer'],
              'JM_Reguler' =>   $row['Regular2'],
              'JM_Transfer' =>   $row['Transfer2'],
              'DateCreated' =>   $DateCreated,
            ];

            $this->db->insert('aps_apt_rekap.s2a',$dataSave);
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

    private function __s2b(){
      // save to log
      $this->db->insert('aps_apt_rekap.log',[
        'RunTime' => date('Y-m-d H:i:s'),
        'TableName' => 's2b'
      ]);

      $ID = $this->db->insert_id();
      // get Prodi first
      $dataProdi = $this->getProdi();
      $param = [
        'action' => 'readDataMHSBaruAsingByProdi',
      ];
      $urlPost = base_url().'api3/__crudAgregatorTB2';
      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d');

      // checkCloumn By Ta
      $getTa = $this->m_master->ShowDBLikes();
      $getColoumn = $this->m_master->getColumnTable('aps_apt_rekap.s2b');
      $def_col_alter = [
                        'JMA',
                        'JMAPeW',
                        'JMAPaW'
                       ];

      $setColoumn = function($arr_ta,$getColoumn,$def_col_alter) {
        for ($i=0; $i < count($arr_ta); $i++) { 
          $ta = explode('_', $arr_ta[$i]);
          $ta = $ta[1];
          
          $field = $getColoumn['field'];

          $find = false;
          for ($j=0; $j < count($field); $j++) { 
            $col = $field[$j];
            $split = explode('_', $col);
            if (  count($split) > 1 && in_array($split[0], $def_col_alter) && $split[1] == $ta ) {
                $find = true;
                break;
            }
          }

          if (!$find) {
            for ($j=0; $j < count($def_col_alter); $j++) { 
              // adding coloumn
              $NameField = $def_col_alter[$j].'_'.$ta;
              $this->db->query(
                'ALTER TABLE aps_apt_rekap.s2b ADD '.$NameField.' int NOT NULL DEFAULT 0 '
              );
            }
          }
        }
      };

      $setColoumn($getTa,$getColoumn,$def_col_alter);
      for ($i=0; $i < count($dataProdi); $i++) { 
        $param['ProdiID'] = $dataProdi[$i]['ID'].'.'.$dataProdi[$i]['Code'];
        $param['ProdiName'] = $dataProdi[$i]['Level'].' - '.$dataProdi[$i]['NameEng'];
        $ProdiID = $dataProdi[$i]['ID'];
        $token = $this->jwt->encode($param,"UAP)(*");
        $data_post = [
          'token' => $token,
        ];
        
        try {
          // remove old data first by years and month
          $this->db->query(
            'delete from aps_apt_rekap.s2b where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
          );
          $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
          $result = (array) json_decode($postTicket,true);
          $bodyResult = $result['body'];
          $dataSave = [
            'ProdiID' => $ProdiID,
            'DateCreated' => $DateCreated,
          ];

          for ($z=2; $z < count($bodyResult); $z++) { // 0 is ID, 1 Name Prodi
              for ($k=0; $k < count($def_col_alter); $k++) { 
                $dbTA =  $getTa;
                for ($l=0; $l < count($dbTA); $l++) { 
                  $value = $bodyResult[$z];
                  $ta = explode('_', $dbTA[$l]);
                  $ta = $ta[1];
                  $key = $def_col_alter[$k].'_'.$ta;
                  $dataSave[$key] =$value;
                  $z++;
                }

              }
          }

           $this->db->insert('aps_apt_rekap.s2b',$dataSave);

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

    private function __s3a1(){
     // save to log
     $this->db->insert('aps_apt_rekap.log',[
       'RunTime' => date('Y-m-d H:i:s'),
       'TableName' => 's2b'
     ]);

     $ID = $this->db->insert_id();
     // get Prodi first
     $dataProdi = $this->getProdi();
     $param = [
      'auth' => 's3Cr3T-G4N',
      'mode' => 'DataDosen'
     ];

     $arr_StatusForlap = [
        '0', // NUP
        '1', // NIDN
        '2' // NIDK
     ];

     $getSemester = $this->m_master->showData_array('db_academic.semester');

     $urlPost = base_url().'rest3/__get_APS_CrudAgregatorTB3';
     $Year = date('Y');
     $Month = date('m');
     $DateCreated = $Year.'-'.$Month.'-'.date('d');

     for ($i=0; $i < count($dataProdi); $i++) { 
        // remove old data first by years and month
        $this->db->query(
          'delete from aps_apt_rekap.s3a1 where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
        );
        $ProdiID = $dataProdi[$i]['ID'];

        for ($j=0; $j < count($arr_StatusForlap); $j++) { 
          $StatusForlap = $arr_StatusForlap[$i];

          for ($k=0; $k < count($getSemester); $k++) { 
            $SemesterID = $getSemester[$j]['ID'];
            $param['StatusForlap']  = $StatusForlap;
            $param['SemesterID']  = $SemesterID;
            $param['ProdiID']  = $ProdiID;
            $token = $this->jwt->encode($param,"UAP)(*");
            $data_post = [
              'token' => $token,
            ];

            try {
              $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
              $result = (array) json_decode($postTicket,true);
            } catch (Exception $e) {
              
            }


          }

        }

     }
    }

}