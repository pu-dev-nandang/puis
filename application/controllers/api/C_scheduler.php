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
          // $this->__s1();
          break;
        case 's2a':
          // $this->__s2a();
          break;
        case 's2b':
          // $this->__s2b();
          break;
        case 's3a1':
          // $this->__s3a1();
          break;
        case 's3b1':
          // $this->__s3b1();
          break;
        case 's3b2':
          // $this->__s3b2();
          break;
        case 's3b4':
          // $this->__s3b4();
          break;
        case 's3b5':
          // $this->__s3b5();
          break;
        case 's3b7':
          //$this->__s3b7();
          break;
        case 's4':
          //$this->__s4();
          break;
        case 's5a':
          $this->__s5a();
          break;
        case 's5b':
          $this->__s5b();
          break;
        case 's5c':
          $this->__s5c();
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
       'TableName' => 's3a1'
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


     $tableFill = 'aps_apt_rekap.s3a1';
     for ($i=0; $i < count($dataProdi); $i++) { 
        $ProdiID = $dataProdi[$i]['ID'];
        // remove old data first by years and month
        $this->db->query(
          'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
        );

        for ($j=0; $j < count($arr_StatusForlap); $j++) { 
          $StatusForlap = $arr_StatusForlap[$j];

          for ($k=0; $k < count($getSemester); $k++) { 
            $SemesterID = $getSemester[$k]['ID'];
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
              $arrGetYear = [];
              $JMP = [];
              for ($z=0; $z < count($result); $z++) { 
                // get field Year
                  $JMLDibimbingBy_LainPS = $result[$z]['JMLDibimbingBy_LainPS'];
                  if ($z == 0 ) {
                      for ($zz=0; $zz < count($JMLDibimbingBy_LainPS); $zz++) { 
                        $arrGetYear[] = $JMLDibimbingBy_LainPS[$zz]['Year'];
                      }

                      // alter cololumn if need it
                      for ($zz=0; $zz < count($arrGetYear) ; $zz++) { 

                        $getField =  'JMPSAk_'.$arrGetYear[$zz].'_Value';
                        $chk = $this->m_master->checkColoumnExist($tableFill,$getField);
                        if (!$chk) { // not exist
                          $this->db->query(
                            'ALTER TABLE '.$tableFill.' ADD '.$getField.' int NOT NULL DEFAULT 0 '
                          );
                        }
                        $JMP[]=$getField;

                        $getField =  'JMPSAk_'.$arrGetYear[$zz].'_Token';
                        $chk = $this->m_master->checkColoumnExist($tableFill,$getField);
                        if (!$chk) { // not exist
                          $this->db->query(
                            'ALTER TABLE '.$tableFill.' ADD '.$getField.' text '
                          );
                        }
                        $JMP[]=$getField;

                        $getField =  'JMPSLain_'.$arrGetYear[$zz].'_Value';
                        $chk = $this->m_master->checkColoumnExist($tableFill,$getField);
                        if (!$chk) { // not exist
                          $this->db->query(
                            'ALTER TABLE '.$tableFill.' ADD '.$getField.' int NOT NULL DEFAULT 0 '
                          );
                        }
                        $JMP[]=$getField;

                        $getField =  'JMPSLain_'.$arrGetYear[$zz].'_Token';
                        $chk = $this->m_master->checkColoumnExist($tableFill,$getField);
                        if (!$chk) { // not exist
                          $this->db->query(
                            'ALTER TABLE '.$tableFill.' ADD '.$getField.' text '
                          );
                        }
                        $JMP[]=$getField;

                      }

                  }

                  $dataSave = [];
                  $dataSave = $dataSave + [
                    'ProdiID' => $ProdiID,
                    'ForlapStatus' => $StatusForlap,
                    'SemesterID' => $SemesterID,
                    'NamaDosen' => $result[$z]['NameDosen'],
                    'NIDN' => $result[$z]['NIDN'],
                    'NIDK' => $result[$z]['NIDK'],
                    'PPS1' => $result[$z]['PendidikanPascaSarjana'],
                    'PPPS2' => $result[$z]['PendidikanPascaSarjana2'],
                    'Perusahaan' => $result[$z]['PerusahaanIndustri'],
                    'Pendidikan_Tertinggi' => $result[$z]['PendidikanTertinggi'],
                    'Bidang_Keahlian' => $result[$z]['BidKeahlian'],
                    'Kesesuaian_Komp' => $result[$z]['KesesuaianKompetensiIntiPS'],
                    'Jabatan_Akademik' => $result[$z]['JabatanAkademik'],
                    'Sertifikat1' => $result[$z]['SertifikatPendidikProfesional'],
                    'Sertifikat2' => $result[$z]['SertifikatPendidikProfesi'],
                    'MTPS_Akreditasi' => $result[$z]['MKPSAkreditasi'],
                    'Kesesuaian' => $result[$z]['KesesuaianBidKeahlian'],
                    'MTPS2' => $result[$z]['MKPS_lain'],

                  ];

                  $BobotKredit_lain =  $result[$z]['BobotKredit_lain'];
                
                  $dataSave = $dataSave + [
                    'BobotKreditValue' => $BobotKredit_lain['value'],
                    'BobotKreditToken' => $BobotKredit_lain['data'],
                  ];

                  $JMLDibimbingBy_PS =  $result[$z]['JMLDibimbingBy_PS'];
                  $JMLDibimbingBy_LainPS =  $result[$z]['JMLDibimbingBy_LainPS'];
                  for ($aa=0; $aa < count($JMP) ; $aa++) { 
                    $fieldJMP = $JMP[$aa];
                    $fieldx =  explode('_', $fieldJMP) ;
                    $fieldxFirst = $fieldx[0];
                    $fieldxYear = $fieldx[1];
                    $fieldxLast = $fieldx[2];
                    if ($fieldxFirst == 'JMPSAk') {
                       $valGet  = ($fieldxLast == 'Value') ? 0 : '';
                       $tokGet  = '';
                       for ($ab=0; $ab < count($JMLDibimbingBy_PS); $ab++) { 
                         if ($fieldxYear == $JMLDibimbingBy_PS[$ab]['Year']) {
                           $valGet  = ($fieldxLast == 'Value') ? $JMLDibimbingBy_PS[$ab]['tot'] : $JMLDibimbingBy_PS[$ab]['data'];
                           break;
                         }
                       }
                       $dataSave[$fieldJMP] = $valGet;
                    }
                    else
                    {
                      $valGet  = ($fieldxLast == 'Value') ? 0 : '';
                      $tokGet  = '';
                      for ($ab=0; $ab < count($JMLDibimbingBy_LainPS); $ab++) { 
                        if ($fieldxYear == $JMLDibimbingBy_LainPS[$ab]['Year']) {
                          $valGet  = ($fieldxLast == 'Value') ? $JMLDibimbingBy_LainPS[$ab]['tot'] : $JMLDibimbingBy_LainPS[$ab]['data'];
                          break;
                        }
                      }
                      $dataSave[$fieldJMP] = $valGet;
                    }
                  }

                  $dataSave = $dataSave + [
                    'Rata_rata_bimbingan' => $result[$z]['rata2BimBingan'],
                    'Rata_rata_bimbingan_all' => $result[$z]['rata2BimBinganAll'],
                    'DateCreated' => $DateCreated,
                  ];

                  // print_r($dataSave);

                  $this->db->insert($tableFill,$dataSave);
              }

            } catch (Exception $e) {
               print_r($e);
            }


          }

        }

     }


     $this->data['status'] = 1;
     if ($this->data['status'] == 1) {
       $this->db->where('ID',$ID);
       $this->db->update('aps_apt_rekap.log',['Status' => 1]);
     }


    }

    private function arrYearPUStart(){
      $arr = [];
      $Start = 2014;
      $EndYear = date('Y');

      for ($i=$Start; $i <=$EndYear ; $i++) { 
        $arr[] = $i;
      }

      return $arr;


    }

    private function __s3b1(){

      $this->db->insert('aps_apt_rekap.log',[
        'RunTime' => date('Y-m-d H:i:s'),
        'TableName' => 's3b1'
      ]);

      $ID = $this->db->insert_id();
      $dataProdi = $this->getProdi();
      // $dataProdi = array(['ID' => 1]);
      $param = [
       'auth' => 's3Cr3T-G4N',
       'mode' => 'RekognisiDosenKaryaIlmiah'
      ];

      $YearList = $this->arrYearPUStart();
      // $YearList = array(2018);
      $urlPost = base_url().'rest3/__get_APS_CrudAgregatorTB3';
      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d');
      $tableFill = 'aps_apt_rekap.s3b1';

      for ($i=0; $i < count($dataProdi); $i++) { 
          $ProdiID = $dataProdi[$i]['ID'];
          // remove old data first by years and month
          $this->db->query(
            'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
          );

          for ($j=0; $j < count($YearList); $j++) { 
            $filterTahun = $YearList[$j];
            $param['filterTahun']  = $filterTahun;
            $param['ProdiID']  = $ProdiID;
            $token = $this->jwt->encode($param,"UAP)(*");
            $data_post = [
              'token' => $token,
            ];
            try {
              $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
              $result = (array) json_decode($postTicket,true);
              if (array_key_exists('body', $result)) {
                $body = $result['body'];
                for ($y=0; $y < count($body) ; $y++) { 
                  $rowPerson =  $body[$y];
                  if (count($rowPerson) > 0 ) {
                    // print_r('y == '.$y.'; Year == '.$filterTahun.'; ProdiID == '.$ProdiID);
                    // get rowspan untuk insert multiple data
                    $rowspan =  $rowPerson[0]['rowspan'];
                    // insert person first
                    $NamaDosen = $rowPerson[1]['value'];
                    for ($x=0; $x < $rowspan; $x++) {
                      $Rekognisi_Bukti_Pendukung = "";
                      if (array_key_exists($x, $rowPerson[2])) {
                          $Rekognisi_Bukti_Pendukung = $rowPerson[2][$x];
                      }

                      $T_Wilayah ="";
                      if (array_key_exists($x, $rowPerson[3])) {
                          $T_Wilayah = $rowPerson[3][$x];
                      } 

                      $T_Nasional = "";
                      if (array_key_exists($x, $rowPerson[4])) {
                         $T_Nasional = $rowPerson[4][$x];
                      }

                      $T_Internasional = "";
                      if (array_key_exists($x, $rowPerson[5])) {
                           $T_Internasional = $rowPerson[5][$x];
                      } 

                      $TahunRekognisi = $rowPerson[6]['value'];
                      $JudulArtikeSitasi = $rowPerson[7]['value'];
                      $JumlahSitasi = $rowPerson[8]['value'];
                      $dataSave = [
                        'ProdiID' => $ProdiID,
                        'Year' => $filterTahun,
                        'NamaDosen' => $NamaDosen,
                        'Rekognisi_Bukti_Pendukung' => $Rekognisi_Bukti_Pendukung,
                        'T_Wilayah' => (empty($T_Wilayah) || $T_Wilayah == NULL) ? '' : $T_Wilayah,
                        'T_Nasional' => (empty($T_Nasional) || $T_Nasional == NULL) ? '' : $T_Nasional,
                        'T_Internasional' => (empty($T_Internasional) || $T_Internasional == NULL) ? '' : $T_Internasional,
                        'TahunRekognisi' => $TahunRekognisi,
                        'JudulArtikeSitasi' => $JudulArtikeSitasi,
                        'JumlahSitasi' => $JumlahSitasi,
                        'DateCreated' => $DateCreated,
                      ];
                      $this->db->insert($tableFill,$dataSave);
                    }
                  }
                }
              }
              else
              {
                $this->data['status'] = 0;
                return 0;
              }
            } catch (Exception $e) {
               print_r($e);
            }
          }
      }

      $this->data['status'] = 1;
      if ($this->data['status'] == 1) {
        $this->db->where('ID',$ID);
        $this->db->update('aps_apt_rekap.log',['Status' => 1]);
      }


    }

    private function __s3b2(){
      $this->db->insert('aps_apt_rekap.log',[
        'RunTime' => date('Y-m-d H:i:s'),
        'TableName' => 's3b2'
      ]);

      $ID = $this->db->insert_id();
      $dataProdi = $this->getProdi();
      // $dataProdi = array(['ID' => 4]);
      $param = [
       'auth' => 's3Cr3T-G4N',
       'mode' => 'JudulPenelitian&JudulPKM'
      ];

      $urlPost = base_url().'rest3/__get_APS_CrudAgregatorTB3';
      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d');
      $tableFill = 'aps_apt_rekap.s3b2';

      for ($i=0; $i < count($dataProdi); $i++) { 
        $ProdiID = $dataProdi[$i]['ID'];
        $this->db->query(
          'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
        );

        $param['ProdiID']  = $ProdiID;
        $token = $this->jwt->encode($param,"UAP)(*");
        $data_post = [
          'token' => $token,
        ];

        try {
          $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
          $result = (array) json_decode($postTicket,true);

          $arrYearHeader = [];
          if (array_key_exists('header', $result)) {
            // get year firt
            /*
              two type year
                1. JJP => Jumlah Judul Penelitian
                2. JJPKM => Jumlah Judul PkM
  
            */

            $header = $result['header'];
            // get year
            $arrYearHeader = $header[2]['Sub'];

            $Jyear = [];
            // create field
            for ($x=0; $x < count($arrYearHeader); $x++) { 
              $getField =  'JJP_'.$arrYearHeader[$x];
              $chk = $this->m_master->checkColoumnExist($tableFill,$getField);
              if (!$chk) { // not exist
                $this->db->query(
                  'ALTER TABLE '.$tableFill.' ADD '.$getField.' int NOT NULL DEFAULT 0 '
                );
              }
              $Jyear[] = $getField;
            }

            for ($x=0; $x < count($arrYearHeader); $x++) { 
              $getField =  'JJPKM_'.$arrYearHeader[$x];
              $chk = $this->m_master->checkColoumnExist($tableFill,$getField);
              if (!$chk) { // not exist
                $this->db->query(
                  'ALTER TABLE '.$tableFill.' ADD '.$getField.' int NOT NULL DEFAULT 0 '
                );
              }
              $Jyear[] = $getField;
            }

            $body  = $result['body'];
            for ($z=0; $z < count($body); $z++) { 
              $rowData = $body[$z];
              $dataSave = [
                'ProdiID' => $ProdiID,
                'SumberPembiayaan' => $rowData[1]['show']
              ];

              $keyJyear = 0;
              for ($y=2; $y <=4 ; $y++) { 
                $dataSave[$Jyear[$keyJyear]] = $rowData[$y]['show'];

                $keyJyear++;
              }

              for ($y=6; $y <=8 ; $y++) { 
                $dataSave[$Jyear[$keyJyear]] = $rowData[$y]['show'];

                $keyJyear++;
              }

              $dataSave['DateCreated'] = $DateCreated;
              $this->db->insert($tableFill,$dataSave);
            }

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

    private function __s3b4(){
      $this->db->insert('aps_apt_rekap.log',[
        'RunTime' => date('Y-m-d H:i:s'),
        'TableName' => 's3b4'
      ]);

     $ID = $this->db->insert_id();
     $dataProdi = $this->getProdi();
     // $dataProdi = array(['ID' => 4]);
     $param = [
      'auth' => 's3Cr3T-G4N',
      'mode' => 'Publikasi_ilmiah_dtps'
     ];
     
     $arr_ts = [];
     $Y_ts = date('Y');
     $Yback_ts = $Y_ts - 2;
     for ($i=$Yback_ts; $i <= $Y_ts; $i++) { 
       $arr_ts[] = $i;
     }

     $urlPost = base_url().'rest3/__get_APS_CrudAgregatorTB3';
     $Year = date('Y');
     $Month = date('m');
     $DateCreated = $Year.'-'.$Month.'-'.date('d');
     $tableFill = 'aps_apt_rekap.s3b4';

     $param['arr_ts'] = $arr_ts;
     for ($i=0; $i < count($dataProdi); $i++) { 
       $ProdiID = $dataProdi[$i]['ID'];
       $param['ProdiID'] = $ProdiID.'.'.$dataProdi[$i]['Code'];
       // remove old data first by years and month
       $this->db->query(
         'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
       );
       $token = $this->jwt->encode($param,"UAP)(*");
       $data_post = [
         'token' => $token,
       ];

       try {
        $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
        $result = (array) json_decode($postTicket,true);
        // print_r($result);
        if (count($result) > 0) {
          // create fiels if need it
          $Jyear = [];
          for ($z=0; $z < count($arr_ts); $z++) { 
            $getField =  'JJ_'.$arr_ts[$z];
            $chk = $this->m_master->checkColoumnExist($tableFill,$getField);
            if (!$chk) { // not exist
              $this->db->query(
                'ALTER TABLE '.$tableFill.' ADD '.$getField.' int NOT NULL DEFAULT 0 '
              );
            }
            $Jyear[] = $getField;
          }

          for ($z=0; $z < count($result); $z++) { 
            $rowData = $result[$z];
            $dataSave = [
              'ProdiID' => $ProdiID,
              'JenisPublikasi' => $rowData[1]
            ];

            $keyJyear = 0;
            for ($x=2; $x <=4 ; $x++) { 
              $dataSave[$Jyear[$keyJyear]] = ($rowData[$x]['total'] == NULL || empty($rowData[$x]['total'])) ? 0 : $rowData[$x]['total'];

              $keyJyear++;
            }


            $dataSave['DateCreated'] = $DateCreated;
            $this->db->insert($tableFill,$dataSave);

          }

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

    public function __s3b5()
    {

      $this->db->insert('aps_apt_rekap.log',[
         'RunTime' => date('Y-m-d H:i:s'),
         'TableName' => 's3b5'
      ]);

      $ID = $this->db->insert_id();
      $dataProdi = $this->getProdi();
      // $dataProdi = array(['ID' => 4]);
      $param = [
       'auth' => 's3Cr3T-G4N',
       'mode' => 'luaran_penelitan_dtps'
      ];

      $urlPost = base_url().'rest3/__get_APS_CrudAgregatorTB3';
      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d');
      $tableFill = 'aps_apt_rekap.s3b5';

      for ($i=0; $i < count($dataProdi); $i++) { 
        $ProdiID = $dataProdi[$i]['ID'];
        $param['ProdiID'] = $ProdiID.'.'.$dataProdi[$i]['Code'];

        // remove old data first by years and month
        $this->db->query(
          'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
        );

        $token = $this->jwt->encode($param,"UAP)(*");
        $data_post = [
          'token' => $token,
        ];

        try {
          $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
          $result = (array) json_decode($postTicket,true);
          $dataSave = [
            'ProdiID' => $ProdiID,
            'JsonData' => json_encode($result),
            'DateCreated' => $DateCreated
          ];

          $this->db->insert($tableFill,$dataSave);


        }catch (Exception $e) {
            print_r($e);
        }

      }

      $this->data['status'] = 1;
      if ($this->data['status'] == 1) {
        $this->db->where('ID',$ID);
        $this->db->update('aps_apt_rekap.log',['Status' => 1]);
      }

    }

    public function __s3b7()
    {
      $this->db->insert('aps_apt_rekap.log',[
         'RunTime' => date('Y-m-d H:i:s'),
         'TableName' => 's3b7'
      ]);

      $ID = $this->db->insert_id();
      $dataProdi = $this->getProdi();
      // $dataProdi = array(['ID' => 4]);
      $param = [
       'auth' => 's3Cr3T-G4N',
       'mode' => 'produk_jasa_dtps'
      ];

      $urlPost = base_url().'rest3/__get_APS_CrudAgregatorTB3';
      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d');
      $tableFill = 'aps_apt_rekap.s3b7';

      $YearList = $this->arrYearPUStart();

      for ($i=0; $i < count($dataProdi); $i++) { 
        $ProdiID = $dataProdi[$i]['ID'];
        // remove old data first by years and month
        $this->db->query(
          'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
        );

        for ($j=0; $j < count($YearList); $j++) { 
          $param['Year']  = $YearList[$j];
          $param['ProdiID'] = $ProdiID.'.'.$dataProdi[$i]['Code'];
          $token = $this->jwt->encode($param,"UAP)(*");
          $data_post = [
            'token' => $token,
          ];

          try {
            $postTicket = $this->m_master->postApiPHP($urlPost,$data_post);
            $result = (array) json_decode($postTicket,true);
            $dataGet = $result['data'];

            for ($z=0; $z < count($dataGet); $z++) { 
              $dataSave = [
                'ProdiID' => $ProdiID,
                'Year' => $YearList[$j],
                'Nama' => $dataGet[$z][1],
                'ProdukJasa' => $dataGet[$z][2],
                'DeskripsiProdukJasa' => $dataGet[$z][3],
                'Bukti' => $dataGet[$z][4],
                'DateCreated' => $DateCreated,

              ];

              $this->db->insert($tableFill,$dataSave);
            }

          }catch (Exception $e) {
              print_r($e);
          }

        }

      }

      $this->data['status'] = 1;
      if ($this->data['status'] == 1) {
        $this->db->where('ID',$ID);
        $this->db->update('aps_apt_rekap.log',['Status' => 1]);
      }


    }


    public function __s4()
    {
      $this->db->insert('aps_apt_rekap.log',[
         'RunTime' => date('Y-m-d H:i:s'),
         'TableName' => 's4'
      ]);

      $ID = $this->db->insert_id();
      $dataProdi = $this->getProdi();
      // $dataProdi = array(['ID' => 4]);
      $tableFill = 'aps_apt_rekap.s4';

      // get year first by API
        $urlPost1 = base_url().'api3/__crudAgregatorTB4';
        $param1 = [
          'action' => 'viewPenggunaanDanaYear_aps'
        ];
        $token = $this->jwt->encode($param1,"UAP)(*");
        $data_post = [
          'token' => $token,
        ];

        $postCurl = $this->m_master->postApiPHP($urlPost1,$data_post);
        $arrApiYear = (array) json_decode($postCurl,true);
        $arr_Year = [];
        for ($i=0; $i < count($arrApiYear); $i=$i+3) { 
          $t =  $arrApiYear[$i];
          foreach ($t as $key => $value) {
            $arr_Year[] = $value;
          }
        }

        $Year = date('Y');
        $Month = date('m');
        $DateCreated = $Year.'-'.$Month.'-'.date('d');
       
        
        for ($i=0; $i < count($dataProdi); $i++) { 
          $ProdiID = $dataProdi[$i]['ID'];

          // remove old data first by years and month
          $this->db->query(
            'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
          );

          for ($z=0; $z < count($arr_Year); $z++) {
            $yearParam = $arr_Year[$z];
            $yearParam1 = $arr_Year[$z] - 1;
            $yearParam2 = $arr_Year[$z] - 2;
            $yearParam3 = $arr_Year[$z];
            $yearParam4 = $yearParam1;
            $yearParam5 = $yearParam2;

            $param = [
              'Year' => $yearParam,
              'ProdiID' => $ProdiID,
              'Year1' => $yearParam1,
              'Year2' => $yearParam2,
              'Year3' => $yearParam3,
              'Year4' => $yearParam4,
              'Year5' => $yearParam5,
              'action' => "viewPenggunaanDana_aps"
            ];

            $token = $this->jwt->encode($param,"UAP)(*");
            $data_post = [
              'token' => $token,
            ];

            try {
              $postTicket = $this->m_master->postApiPHP($urlPost1,$data_post);
              $result = (array) json_decode($postTicket,true);
              for ($x=0; $x < count($result); $x++) { 
                $dataSave =[
                  'ProdiID' => $ProdiID,
                  'JenisPenggunaan' => $result[$x]['Jenis'],
                  'DateCreated' => $DateCreated,
                ];

                $fieldLoop =  'th';
                $arr_field_get = [
                  'UPPS_'.$yearParam,
                  'UPPS_'.$yearParam1,
                  'UPPS_'.$yearParam2,
                  'PS_'.$yearParam3,
                  'PS_'.$yearParam4,
                  'PS_'.$yearParam5,
                ];


                for ($y=0; $y < count($arr_field_get); $y++) { 

                  // create field if not exist
                  $getField =  $arr_field_get[$y];
                  $chk = $this->m_master->checkColoumnExist($tableFill,$getField);
                  if (!$chk) { // not exist
                    $this->db->query(
                      'ALTER TABLE '.$tableFill.' ADD '.$getField.' int(15) NOT NULL DEFAULT 0 '
                    );
                  }

                  $fi = $y + 1;
                  $getFieldLoop = $fieldLoop.$fi;
                  $dataSave[$getField] = $result[$x][$getFieldLoop];
                }

                $chk = $this->db->query(
                  'select count(*) as total from 
                   (
                     select 1 from '.$tableFill.' where ProdiID = '.$ProdiID.' and JenisPenggunaan = "'.$dataSave['JenisPenggunaan'].'"
                   )xxx
                  '
                )->result_array()[0]['total'];

                if ($chk == 0) {
                  $this->db->insert($tableFill,$dataSave);
                }
                else
                {
                  $this->db->where('ProdiID',$ProdiID);
                  $this->db->where('JenisPenggunaan',$dataSave['JenisPenggunaan']);
                  $this->db->update($tableFill,$dataSave);
                }

              }
            }catch (Exception $e) {
                print_r($e);
            }

          }

        }

        $this->data['status'] = 1;
        if ($this->data['status'] == 1) {
          $this->db->where('ID',$ID);
          $this->db->update('aps_apt_rekap.log',['Status' => 1]);
        }

    }

    public function __s5a(){
      $this->db->insert('aps_apt_rekap.log',[
         'RunTime' => date('Y-m-d H:i:s'),
         'TableName' => 's5a'
      ]);

      $ID = $this->db->insert_id();
      $dataProdi = $this->getProdi();
      // $dataProdi = array(['ID' => 4]);
      $tableFill = 'aps_apt_rekap.s5a';

      // get Curiculum First
      $urlCuriculum = base_url().'api/__getKurikulumSelectOption';
      $apiCuriculum = json_decode($this->m_master->postApiPHP($urlCuriculum,['null' => null]),true) ;

      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d');

      for ($i=0; $i < count($dataProdi); $i++) { 
        $ProdiID = $dataProdi[$i]['ID'];
        $ProdiName = $dataProdi[$i]['Name'];
        // remove old data first by years and month
        $this->db->query(
         'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
        );

        // loop curiculum
        for ($j=0; $j < count($apiCuriculum) ; $j++) { 
          $CurriculumID = $apiCuriculum[$j]['ID'];
          $CurriculumIDParameter = $apiCuriculum[$j]['ID'].'.'.$apiCuriculum[$j]['Year'];
          $param =  [
            'ProdiID' => $ProdiID,
            'ProdiName' =>$ProdiName ,
            'Kurikulum' => $CurriculumIDParameter,
            'auth' => 's3Cr3T-G4N',
            'mode' => 'KurikulumCapaianRencana'
          ];

          $token = $this->jwt->encode($param,"UAP)(*");
          $data_post = [
            'token' => $token,
            'start' => 0,
            'length' => 10000,
            'search[value]' => '',
            'search[regex]' => false,
            'draw' => 1,
          ];

          $urlPostCurl =  base_url().'rest3/__get_APS_CrudAgregatorTB7';
          $postCurl = $this->m_master->postApiPHP($urlPostCurl,$data_post);
          $result = (array) json_decode($postCurl,true);
          $dataResult  = $result['data'];
          for ($z=0; $z < count($dataResult); $z++) { 
            $dataSave = [
              'ProdiID' => $ProdiID,
              'CurriculumID' => $CurriculumID,
              'SemesterTo' => $dataResult[$z][1] ,
              'KodeMataKuliah' => $dataResult[$z][2],
              'NamaMataKuliah' => $dataResult[$z][3],
              'MataKuliahKompetensi' => $dataResult[$z][4],
              'Kuliah_responsi_tutorial' => $dataResult[$z][5],
              'Seminar' => $dataResult[$z][6],
              'Pratikum' => $dataResult[$z][7],
              'KonversiKreditKeJam' => $dataResult[$z][8],
              'Sikap' => $dataResult[$z][9],
              'Pengetahuan' => $dataResult[$z][10],
              'KeterampilanUmum' => $dataResult[$z][11],
              'KeterampilanKhusus' => $dataResult[$z][12],
              'DokumenRencanaPembelajaran' => $dataResult[$z][13],
              'UnitPenyelenggara' => $dataResult[$z][14],
              'DateCreated' => $DateCreated,
            ];

             $this->db->insert($tableFill,$dataSave);
          }
        }


      }

      $this->data['status'] = 1;
      if ($this->data['status'] == 1) {
        $this->db->where('ID',$ID);
        $this->db->update('aps_apt_rekap.log',['Status' => 1]);
      } 


    }

    private function __s5b(){
      $this->db->insert('aps_apt_rekap.log',[
         'RunTime' => date('Y-m-d H:i:s'),
         'TableName' => 's5b'
      ]);

      $ID = $this->db->insert_id();
      $dataProdi = $this->getProdi();
      // $dataProdi = array(['ID' => 4]);
      $tableFill = 'aps_apt_rekap.s5b';

      // get Semester First
      $urlSemester =  base_url().'api/__crudSemester';
      $dataParamSemester = [
        'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY3Rpb24iOiJyZWFkIiwib3JkZXIiOiJERVNDIn0.CO53uPRekFICOPQSjaWhF2R-eYtplw2IHlUePrmsCBw'
      ];

      $apiSemester = json_decode($this->m_master->postApiPHP($urlSemester,$dataParamSemester),true);

      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d');

      for ($i=0; $i < count($dataProdi); $i++) { 
         $ProdiID = $dataProdi[$i]['ID'];
         // remove old data first by years and month
         $this->db->query(
          'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
         );

         for ($j=0; $j < count($apiSemester); $j++) { 
           $SemesterID = $apiSemester[$j]['ID'];
           $param = [
              'mode' => 'Integrasi_penelitian_dkm',
              'auth' => 's3Cr3T-G4N',
              'ProdiID' => $ProdiID.'.'.$dataProdi[$i]['Code'],
              'FilterSemester' => $SemesterID.'.'.$apiSemester[$j]['Year'].'.'.$apiSemester[$j]['Code']  ,
           ];
           $token = $this->jwt->encode($param,"UAP)(*");
           $data_post = [
             'token' => $token,
           ];

           $urlPostCurl =  base_url().'rest3/__get_APS_CrudAgregatorTB5';
           $postCurl = $this->m_master->postApiPHP($urlPostCurl,$data_post);
           $result = (array) json_decode($postCurl,true);
           for ($z=0; $z < count($result) ; $z++) { 
             $dataSave = [
                'ProdiID' => $ProdiID,
                'SemesterID' => $SemesterID,
                'JudulPenelitian' => $result[$z][1]  ,
                'NamaDosen' => $result[$z][2] ,
                'MataKuliah' => $result[$z][3] ,
                'BentukIntegrasi' => $result[$z][4] ,
                'Tahun' => $result[$z][5] ,
                'DateCreated' => $DateCreated,
             ];

             $this->db->insert($tableFill,$dataSave);

           }

         }

      }

      $this->data['status'] = 1;
      if ($this->data['status'] == 1) {
        $this->db->where('ID',$ID);
        $this->db->update('aps_apt_rekap.log',['Status' => 1]);
      } 

    }

    private function __s5c(){
      $this->db->insert('aps_apt_rekap.log',[
         'RunTime' => date('Y-m-d H:i:s'),
         'TableName' => 's5c'
      ]);

      $ID = $this->db->insert_id();
      $dataProdi = $this->getProdi();
      // $dataProdi = array(['ID' => 4]);
      $tableFill = 'aps_apt_rekap.s5c';

      // get Semester First
      $urlSemester =  base_url().'api/__crudSemester';
      $dataParamSemester = [
        'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY3Rpb24iOiJyZWFkIiwib3JkZXIiOiJERVNDIn0.CO53uPRekFICOPQSjaWhF2R-eYtplw2IHlUePrmsCBw'
      ];
      
      $apiSemester = json_decode($this->m_master->postApiPHP($urlSemester,$dataParamSemester),true);

      $Year = date('Y');
      $Month = date('m');
      $DateCreated = $Year.'-'.$Month.'-'.date('d'); 

      for ($i=0; $i < count($dataProdi); $i++) { 
         $ProdiID = $dataProdi[$i]['ID'];
         // remove old data first by years and month
         $this->db->query(
          'delete from '.$tableFill.' where Year(DateCreated) = "'.$Year.'" and Month(DateCreated) = "'.$Month.'" and ProdiID = '.$ProdiID
         );

         for ($j=0; $j < count($apiSemester); $j++) { 
           $SemesterID = $apiSemester[$j]['ID'];
           $param = [
              'mode' => 'kepuasan_mhs',
              'auth' => 's3Cr3T-G4N',
              'ProdiID' => $ProdiID.'.'.$dataProdi[$i]['Code'],
              'FilterSemester' => $SemesterID.'.'.$apiSemester[$j]['Year'].'.'.$apiSemester[$j]['Code']  ,
           ];
           $token = $this->jwt->encode($param,"UAP)(*");
           $data_post = [
             'token' => $token,
           ];

           $urlPostCurl =  base_url().'rest3/__get_APS_CrudAgregatorTB5';
           $postCurl = $this->m_master->postApiPHP($urlPostCurl,$data_post);
           $result = (array) json_decode($postCurl,true);
           $dataResult =  $result['data'];

           for ($z=0; $z < count($dataResult); $z++) { 
              $dataSave = [
                'ProdiID' => $ProdiID,
                'SemesterID' => $SemesterID,
                'AspekRatio' => $dataResult[$z][1] ,
                'TingkatKepuasanSangatBaik' => $dataResult[$z][2] ,
                'TingkatKepuasanBaik' => $dataResult[$z][3] ,
                'TingkatKepuasanCukup' => $dataResult[$z][4] ,
                'TingkatKepuasanKurang' => $dataResult[$z][5] ,
                'RencanaTindakLanjutUPPSorPS' => $dataResult[$z][6] ,
                'DateCreated' => $DateCreated ,
              ];

              $this->db->insert($tableFill,$dataSave);
           }

         }



      }

      $this->data['status'] = 1;
      if ($this->data['status'] == 1) {
        $this->db->where('ID',$ID);
        $this->db->update('aps_apt_rekap.log',['Status' => 1]);
      } 

    }

}