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
            
            default:
                # code...
                break;
        }
    }

}