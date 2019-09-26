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
            case 'DataDosen':
                $rs = [];
                $ProdiID = $dataToken['ProdiID'];
                $F_SemesterID = function($dataToken)
                {
                    if (array_key_exists('SemesterID', $dataToken)) {
                        $SemesterID = $dataToken['SemesterID'];
                    }
                    else
                    {
                        $G_smt = $this->m_master->caribasedprimary('db_academic.semester','Status',1);
                        $SemesterID = $G_smt[0]['ID'];
                    }

                    return $SemesterID;
                };

                $SemesterID = $F_SemesterID($dataToken);
                // ts berdasarkan semester
                $G_smt = $this->m_master->caribasedprimary('db_academic.semester','ID',$SemesterID);
                $Year = $G_smt[0]['Year'];
                $Year3 = $Year - 2;
                $arr_year = [];
                for ($i=$Year3; $i <= $Year; $i++) { 
                   $arr_year[] = $i;
                }

                $sql = 'select a.*,b.Position as JabatanAkademik from db_employees.employees as a
                    left join db_employees.lecturer_academic_position as b on a.LecturerAcademicPositionID = b.ID  
                     where ( 
                                SPLIT_STR(a.PositionMain, ".", 2) = 7 or 
                                SPLIT_STR(a.PositionOther1, ".", 2) = 7 or
                                SPLIT_STR(a.PositionOther2, ".", 2) = 7 or
                                SPLIT_STR(a.PositionOther3, ".", 2) = 7
                            ) and StatusForlap != "0" and ProdiID = ?';
                $query=$this->db->query($sql, array($ProdiID))->result_array();
                for ($i=0; $i < count($query); $i++) { 
                    $NIP = $query[$i]['NIP'];
                    $LevelEducationName = function($ID){
                        $rs = '';
                        $G= $this->m_master->caribasedprimary('db_employees.level_education','ID',$ID);
                        if (count($G) > 0) {
                            $rs = $G[0]['Level'];
                        }
                        return $rs;
                    };

                    $LevelEdu = $LevelEducationName($query[$i]['LevelEducationID']);

                    $PendidikanPascaSarjana = function($NIP,$LevelEdu)
                    {
                        $rs = array('PendidikanPascaSarjana' => '' ,'BidKeahlian' => '');
                        $M_q = '(3,4)';
                        if ($LevelEdu == 'S3') {
                           $M_q = '(5,6)';
                        }
                        elseif ($LevelEdu == 'S1') {
                           $M_q = '(1,2)';
                        }
                        $sql = 'select NameUniversity,Major from db_employees.files  where TypeFiles in'.$M_q.' and NIP = "'.$NIP.'"  order by ID desc limit 1';
                        // print_r($sql);
                        $query=$this->db->query($sql, array())->result_array();
                        if (count($query) > 0) {
                            $rs['PendidikanPascaSarjana'] = $query[0]['NameUniversity'];
                            $rs['BidKeahlian'] = $query[0]['Major'];
                        }
                        return $rs;
                    };
                    
                    $SertifikatPendidikProfesional = function($NIP){
                        return '';
                    };

                    $MKPSAkreditasi = function($NIP,$ProdiID,$SemesterID){
                        $rs = '';
                        $sql = 'select a.ID as SdcID,a.ScheduleID,a.MKID,b.ClassGroup,c.NameEng
                                from db_academic.schedule_details_course as a 
                                join db_academic.schedule as b on a.ScheduleID = b.ID
                                join db_academic.mata_kuliah as c on a.MKID = c.ID
                                where a.ProdiID = ? and b.Coordinator = ? and b.SemesterID = ?
                                group by b.ID,a.MKID    
                                 ';
                        $query=$this->db->query($sql, array($ProdiID,$NIP,$SemesterID))->result_array();
                        if (count($query) > 0) {
                            $rs .= $query[0]['ClassGroup'].' - '.$query[0]['NameEng'];
                            for ($i=1; $i < count($query); $i++) { 
                                $rs .= ', '.$query[$i]['ClassGroup'].' - '.$query[$i]['NameEng'];
                            }
                        }
                        return $rs;
                    };

                    // get dt academic except prodi
                    $dtAcademic = function($NIP,$ProdiID,$SemesterID){
                        $rs = array('MK' => '','SKS' => '');
                        $sql = 'select a.ID as SdcID,a.ScheduleID,a.MKID,b.ClassGroup,c.NameEng,d.TotalSKS
                                from db_academic.schedule_details_course as a 
                                join db_academic.schedule as b on a.ScheduleID = b.ID
                                join db_academic.mata_kuliah as c on a.MKID = c.ID
                                join db_academic.curriculum_details as d on a.CDID = d.ID
                                where a.ProdiID != ? and b.Coordinator = ? and b.SemesterID = ?
                                group by b.ID,a.MKID    
                                 ';
                        $query=$this->db->query($sql, array($ProdiID,$NIP,$SemesterID))->result_array();
                        $MK = '';
                        $SKS = '';
                        if (count($query) > 0) {
                            $MK .= $query[0]['ClassGroup'].' - '.$query[0]['NameEng'];
                            $SKS .= $query[0]['TotalSKS'];
                            for ($i=1; $i < count($query); $i++) { 
                                $MK .= ', '.$query[$i]['ClassGroup'].' - '.$query[$i]['NameEng'];
                                $SKS .= ', '.$query[$i]['TotalSKS'];
                            }

                            $rs['MK'] = $MK;
                            $rs['SKS'] = $SKS;
                        }
                        return $rs;
                    };

                    $dtAcademic_get = $dtAcademic($NIP,$ProdiID,$SemesterID);

                    // get jumlah mahasiswa dengan tiga ts terakhir
                    $JMLDibimbingBy_PS = array();
                    $JMLDibimbingBy_LainPS = array();
                    $SumBimbingan_tahun = 0;
                    $SumProgram_tahun = 0;
                    for ($k=0; $k < count($arr_year); $k++) { 
                        $Y = $arr_year[$k];
                        $sql_ = 'select a.NPM,b.Name from db_academic.mentor_academic as a
                                join db_academic.auth_students as b on a.NPM = b.NPM
                                where a.Status = ? and a.ProdiID = ? and a.Year = ? and a.NIP = ?
                                    ';
                        $query_=$this->db->query($sql_, array("1",$ProdiID,$Y,$NIP))->result_array();
                        $tot = count($query_);
                        $SumBimbingan_tahun += $tot;
                        $temp = [
                            'tot' => $tot,
                            'Year' => $Y,
                            'data' =>  $this->jwt->encode($query_,"UAP)(*"),
                        ];
                        $JMLDibimbingBy_PS[] = $temp;

                        $sql_lain = 'select a.NPM,b.Name from db_academic.mentor_academic as a
                                join db_academic.auth_students as b on a.NPM = b.NPM
                                where a.Status = ? and a.ProdiID != ? and a.Year = ? and a.NIP = ?
                                    ';
                        $query_lain=$this->db->query($sql_lain, array("1",$ProdiID,$Y,$NIP))->result_array();

                        $tot1 = count($query_lain);
                        $c_tot_all = $tot + $tot1;
                        $SumProgram_tahun += $c_tot_all;
                        $temp = [
                            'tot' => $tot1,
                            'Year' => $Y,
                            'data' =>  $this->jwt->encode($query_lain,"UAP)(*"),
                        ];

                        $JMLDibimbingBy_LainPS[] = $temp;
                    }
                    $C_year = count($arr_year);
                    $C_year2 = $C_year * 2;
                    $rata2BimBingan = $SumBimbingan_tahun / $C_year;
                    $rata2BimBinganAll = $SumProgram_tahun / $C_year2;

                    $G_master_academic = $PendidikanPascaSarjana($NIP,$LevelEdu);
                   
                    $temp = ['No'  =>  ($i+1),
                             'NameDosen' => $query[$i]['Name'],
                             'NIDN' => $query[$i]['NIDN'],
                             'NIDK' => $query[$i]['NIDK'],
                             'PendidikanPascaSarjana' => $G_master_academic['PendidikanPascaSarjana'],
                             'PerusahaanIndustri' => '',
                             // 'PendidikanTertinggi' => $LevelEducationName($query[$i]['LevelEducationID']),
                             'PendidikanTertinggi' => $LevelEdu,
                             'BidKeahlian' => $G_master_academic['BidKeahlian'],
                             'KesesuaianKompetensiIntiPS' => '',
                             'JabatanAkademik' => $query[$i]['JabatanAkademik'],
                             'SertifikatPendidikProfesional' => $SertifikatPendidikProfesional($NIP),
                             'SertifikatPendidikProfesi' => '',
                             'MKPSAkreditasi' => $MKPSAkreditasi($NIP,$ProdiID,$SemesterID),
                             'KesesuaianBidKeahlian' => '',
                             'MKPS_lain' => $dtAcademic_get['MK'],
                             'BobotKredit_lain' => $dtAcademic_get['SKS'],
                             'JMLDibimbingBy_PS' => $JMLDibimbingBy_PS,
                             'JMLDibimbingBy_LainPS' => $JMLDibimbingBy_LainPS,
                             'rata2BimBingan' => $rata2BimBingan,
                             'rata2BimBinganAll' => $rata2BimBinganAll,
                             // 'SumBimbingan_tahun' => $SumBimbingan_tahun,
                             // 'SumProgram_tahun' => $SumProgram_tahun,
                          ];
                    $rs[] = $temp;      
                }
                echo json_encode($rs);    
                break;
            default:
                # code...
                break;
        }
    }



}