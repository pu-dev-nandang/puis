<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_rest3 extends CI_Controller {
    public $data = [];

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('master/m_master');
        $this->load->library('JWT');
        $this->load->library('google');

        // auth
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if (!$auth) {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
                die();
            }
            $this->data['dataToken'] = $dataToken;
        } catch (Exception $e) {
           // handling orang iseng
           echo '{"status":"999","message":"Not Authorize"}'; 
           die();
        }
        
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "s3Cr3T-G4N";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function getInputToken2()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function is_url_exist($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code == 200){
            $status = true;
        }else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    public function APS_CrudAgregatorTB3()
    {
        $dataToken = $this->data['dataToken'];
        $mode = $dataToken['mode'];
        switch ($mode) {
            case 'JudulPenelitian&JudulPKM':
                //get header and body
                $rs = ['header' => [],'body' => [] ];
                $header = [];
                $header[] = ['Name' => 'No','rowspan' => 2,'Sub' => [],'colspan' => 1 ];
                $header[] = ['Name' => 'Sumber Pembiayaan','rowspan' => 2,'Sub' => [],'colspan' => 1 ];
                $Year = Date('Y');
                $Year3 = $Year - 2;
                $arr_year = [];
                for ($i=$Year3; $i<= $Year; $i++) { 
                   $arr_year[] = $i;
                }
                $header[] = ['Name' => 'Jumlah Judul Penelitian','rowspan' => 1,'Sub' => $arr_year,'colspan' => count($arr_year) ];
                $header[] = ['Name' => 'Jumlah Penelitian','rowspan' => 2,'Sub' => [],'colspan' => 1 ];
                $header[] = ['Name' => 'Jumlah Judul PkM','rowspan' => 1,'Sub' => $arr_year,'colspan' => count($arr_year) ];
                $header[] = ['Name' => 'Jumlah PkM','rowspan' => 2,'Sub' => [],'colspan' => 1 ];

                $G_SumberDana = $this->m_master->caribasedprimary('db_agregator.sumber_dana','Status','1');
                $body = [];
                $ProdiID = $dataToken['ProdiID'];
                for ($i=0; $i < count($G_SumberDana); $i++) { 
                    $No = $i+1;
                    $ID_sumberdana = $G_SumberDana[$i]['ID'];
                    $Name = $G_SumberDana[$i]['SumberDana'];
                    $temp = [];
                    $temp[] = array('show' => $No ,'data' => []); // $j = 0 adalah No 
                    $temp[] = array('show' => $Name ,'data' => []); // $j = 1 adalah Name Sumber Dana 
                    $JmlPenelitian = 0;
                    $JmlPKM = 0;
                    for ($j=2; $j < count($header); $j++) {
                        switch ($j) {
                             case 2: // ambil dari table lintabmas
                                 // cek sub
                                 $sub = $header[$j]['Sub'];
                                 if (count($sub) > 0 ) {
                                    for ($k=0; $k < count($sub); $k++) { 
                                        $Y = $sub[$k];
                                        $sql = 'select a.*,b.Name from db_research.litabmas as a
                                               join db_employees.employees as b on a.NIP = b.NIP
                                               where a.ID_sumberdana = ? and a.ID_thn_laks = ?
                                               and b.ProdiID = ?     
                                                ';
                                        $query=$this->db->query($sql, array($ID_sumberdana,$Y,$ProdiID))->result_array();
                                        $tot = count($query);
                                        // encode token
                                        $token = $this->jwt->encode($query,"UAP)(*");
                                        $temp[] = array('show' => $tot ,'data' => $token);
                                        $JmlPenelitian += count($query);
                                    }     
                                 }
                                 else
                                 {
                                    $temp[] = array('show' => 0 ,'data' => []);
                                 }
                                 break;
                             case 3: // Jumlah Penelitian
                                $temp[] = array('show' => $JmlPenelitian ,'data' => []);
                                break;
                             case 4: // Judul PKM
                                // cek sub
                                $sub = $header[$j]['Sub'];
                                if (count($sub) > 0 ) {
                                   for ($k=0; $k < count($sub); $k++) { 
                                       $Y = $sub[$k];
                                       $sql = 'select a.*,b.Name from db_research.pengabdian_masyarakat as a
                                               join db_employees.employees as b on a.NIP = b.NIP
                                              where a.ID_sumberdana = ? and a.ID_thn_laks = ?
                                              and b.ProdiID = ?     
                                               ';
                                       $query=$this->db->query($sql, array($ID_sumberdana,$Y,$ProdiID))->result_array();
                                       $tot = count($query);
                                       // encode token
                                       $token = $this->jwt->encode($query,"UAP)(*");
                                       $temp[] = array('show' => $tot ,'data' => $token);
                                       $JmlPKM += count($query);
                                   }     
                                }
                                else
                                {
                                   $temp[] = array('show' => 0 ,'data' => []);
                                }
                                break;
                            case 5: // Jumlah Judul PKM
                               $temp[] = array('show' => $JmlPKM ,'data' => []);
                               break;
                             default:
                                 # code...
                                 break;
                         } 
                    }
                    $body[] = $temp;

                }
                $rs['header'] = $header;
                $rs['body'] = $body;
                echo json_encode($rs);
                break;
            case 'RekognisiDosenKaryaIlmiah':
                $rs = ['header' => [],'body' => [] ];
                $header = [];
                $header[] = ['Name' => 'No','rowspan' => 2,'Sub' => [],'colspan' => 1 ];
                $header[] = ['Name' => 'Nama Dosen','rowspan' => 2,'Sub' => [],'colspan' => 1 ];
                $header[] = ['Name' => 'Rekognisi dan Bukti Pendukung','rowspan' => 2,'Sub' => [],'colspan' => 1 ];
                $header[] = ['Name' => 'Tingkat','rowspan' => 1,'Sub' => ['Wilayah','Nasional','Internasional'],'colspan' => 3 ];
                $header[] = ['Name' => 'Tahun Rekognisi (YYYY)','rowspan' => 2,'Sub' => [],'colspan' => 1 ];
                $header[] = ['Name' => 'Judul Artikel yang Disitasi (Jurnal, Volume, Tahun, Nomor, Halaman)','rowspan' => 2,'Sub' => [],'colspan' => 1 ];
                $header[] = ['Name' => 'Jumlah Sitasi','rowspan' => 2,'Sub' => [],'colspan' => 1 ];

                $body = [];
                $ProdiID = $dataToken['ProdiID'];
                $sql = 'select a.*,b.Name from db_agregator.rekognisi_dosen as a 
                        join db_employees.employees as b on a.NIP = b.NIP
                        where b.ProdiID = ?
                        order by a.ID desc limit 1000
                        ';
                $query=$this->db->query($sql, array($ProdiID))->result_array();
                for ($i=0; $i < count($query); $i++) { 
                    $No = $i + 1;
                    $NIP = $query[$i]['NIP'];
                    $NameDosen = $query[$i]['Name'];
                    $G_dt_artikel = $this->m_master->caribasedprimary('db_agregator.sitasi_karya','NIP_penulis',$NIP);
                    $temp = [];
                    for ($k=0; $k < count($header); $k++) { 
                        switch ($k) {
                            case 0: // No
                               $temp[] = $No;
                                break;
                            case 1: // Name
                               $temp[] = $NameDosen;
                                break;
                            case 2: // Rekognisi dan Bukti Pendukung
                               $Rekognisi = $query[$i]['Rekognisi'];
                               $BuktiName = $query[$i]['BuktiPendukungName'];
                               $BuktiPendukungUpload = $query[$i]['BuktiPendukungUpload'];
                               $arr_file = (array) json_decode($BuktiPendukungUpload,true);
                               $wr = $Rekognisi;
                               if ($BuktiName != '' && count($arr_file) > 0 ) {
                                   $wr .= '<br/>'.$BuktiName.'<br/>'.'<a href="'.base_url().'fileGetAny/Agregator-Aps-'.$arr_file[0].'" target="_blank" class="Fileexist">Attachment</a>';
                               }
                               $temp[] = $wr;
                                break;
                            case 3: // Tingkat
                                $Sub = ['Wilayah','Nasional','Internasional'];
                                for ($l=0; $l < count($Sub); $l++) {
                                    $wr = '';
                                   if ($Sub[$l] == $query[$i]['Tingkat']) {
                                       $wr = 'V'; 
                                   }
                                   $temp[] = $wr;
                                }
                               break;
                            case 4: // Tahun
                               $wr = $query[$i]['Tahun'];
                               $temp[] = $wr;
                               break;
                            case 5: // Judul Artikel
                               $wr = '';
                               if (count($G_dt_artikel) > 0) {
                                   $wr .= $G_dt_artikel[0]['Judul_artikel'];
                                   for ($l=1; $l < count($G_dt_artikel); $l++) { 
                                      $wr .= '<br/>'.$G_dt_artikel[$l]['Judul_artikel'];
                                   }
                               }
                               $temp[] = $wr;
                               break;
                            case 6: // Jumlah sitasi
                               $wr = '';
                               if (count($G_dt_artikel) > 0) {
                                   $wr .= $G_dt_artikel[0]['Banyak_artikel'];
                                   for ($l=1; $l < count($G_dt_artikel); $l++) { 
                                      $wr .= '<br/>'.$G_dt_artikel[$l]['Banyak_artikel'];
                                   }
                               }
                               $temp[] = $wr;
                               break;
                            default:
                                # code...
                                break;
                        }
                    }

                    $body[] = $temp;
                }

                $rs['header'] = $header;
                $rs['body'] = $body;
                echo json_encode($rs);    
                break;
            default:
                # code...
                break;
        }
    }

}