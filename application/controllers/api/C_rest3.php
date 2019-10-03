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

                $AndWhere = '';
                if (array_key_exists('StatusForlap',$dataToken) ) {
                    if ($dataToken['StatusForlap'] == '%') {
                        $AndWhere = '';
                    }
                    else {
                        $AndWhere = ' AND StatusForlap = "'.$dataToken['StatusForlap'].'" ';
                    }
                }

                $sql = 'select a.*,b.Position as JabatanAkademik from db_employees.employees as a
                    left join db_employees.lecturer_academic_position as b on a.LecturerAcademicPositionID = b.ID  
                     where ( 
                                SPLIT_STR(a.PositionMain, ".", 2) = 7 or 
                                SPLIT_STR(a.PositionOther1, ".", 2) = 7 or
                                SPLIT_STR(a.PositionOther2, ".", 2) = 7 or
                                SPLIT_STR(a.PositionOther3, ".", 2) = 7
                            ) and StatusForlap  is not null and ProdiID = ? and StatusForlap != "" '.$AndWhere;
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

    public function get_roolback_door_to_be_mhs_admission()
    {
        $dataToken = $this->data['dataToken'];
        $action = $dataToken['action'];
        if ($action == 'read') {
            $TA = $dataToken['ta'];
            $DBTA = 'ta_'.$TA;
            $sql = 'select a.NPM,a.Name,a.ProdiID,b.Name as NameProdi,c.FormulirCode,if(y.StatusReg = 1, (select No_Ref from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,(select No_Ref from db_admission.formulir_number_online_m where FormulirCode = c.FormulirCode limit 1)  ) as No_Ref
            from '.$DBTA.'.students as a 
            join db_academic.program_study as b on a.ProdiID = b.ID
            join db_admission.to_be_mhs as c on a.NPM = c.NPM
            join db_admission.register_verified as z on c.FormulirCode = z.FormulirCode
            join db_admission.register_verification as x on z.RegVerificationID = x.ID
            join db_admission.register as y on x.RegisterID = y.ID
            where a.NPM NOT IN (
                select NPM from '.$DBTA.'.study_planning
                group by NPM
                order by ID desc
            )
            ';
            $query=$this->db->query($sql, array())->result_array();

            echo json_encode($query);
        }
        elseif ($action == 'roolback') {
            $this->load->model('admission/m_admission');
            $rs = ['Status' => 1,'Dt' => [] ];
            // $path = FCPATH.'upload\\document\\'.'11190042';
            
            // if (!file_exists('./uploads/document/'.'111900424')) {
            //     print_r('Not Directory');
            // }
            // else {
            //     print_r('Directory');
            // }

            // die();

            /*
                1.Cek Koneksi DB Library
                2.Cek Data telah diinput pada library atau belum
                3.Cek Koneksi AD
                4.Delete user AD first
                5.Remove file di folder upload/document/{NPM}
                6.Remove file photo di folder upload/students/{ta}/{NPM}{ekstension}
                7.Remove data di db_finance.payment_students by ID payment
                8.Remove data di db_finance.payment
                9.Remove data di db_finance.m_tuition_fee
                10.Remove data di db_academic.auth_students
                11.Remove data di db_academic.auth_parents
                12.Remove data di db_admission.doc_mhs
                13.Remove data di db_admission.to_be_mhs
                14.Remove data di {ta}.students by NPM
            */

            $DataSelected = $dataToken['DataSelected'];
            $ta =  $dataToken['ta'];
            $taDB =  'ta_'.$ta;
            $DataSelected = json_decode(json_encode($DataSelected),true);
            for ($i=0; $i < count($DataSelected); $i++) {
                $NPM = $DataSelected[$i]['NPM']; 
                $Name = $DataSelected[$i]['Name'];
                $MSG = ''; 
                // library conn
                $cekLib = $this->m_master->cekConectDb();
                if (!$cekLib) {
                    $MSG .= 'Library Koneksi : Koneksi Library bermasalah';
                    $DataSelected[$i]['Status'] = $MSG;
                    $rs['Status'] = 0;
                    $rs['Dt'] = $DataSelected;
                    break;
                }
                else
                {
                    $MSG .= 'Library Koneksi : Koneksi Library Success';
                    $DataSelected[$i]['Status'] = $MSG;
                    // Cek data telah diinput pada library
                    $CekLibStd = $this->m_admission->cekDBLibraryExistSTD($NPM);
                    if ($CekLibStd) {
                        $MSG .= '<br/>Library Data : Ada';
                        // delete data  pada library jika live
                        $DelDtLib = $this->m_admission->DelDBLibraryExistSTD($NPM);
                        if ($DelDtLib) {
                            $MSG .= '<br/>Library Data : Data berhasil dihapus';
                        }
                        else {
                            $MSG .= '<br/>Library Data : Data tidak dihapus';
                        }

                    }
                    else{
                        $MSG .= '<br/>Library Data : Tidak Ada';
                    }

                    // cek koneksi AD
                    $urlAD = URLAD.'__api/Create';
                    $is_url_exist = $this->m_master->is_url_exist($urlAD);
                    if (!$is_url_exist) {
                        $MSG .= '<br/>AD Koneksi : Windows active directory server not connected';
                        $DataSelected[$i]['Status'] = $MSG;
                        $rs['Status'] = 0;
                        $rs['Dt'] = $DataSelected;
                        break;
                    }
                    else {
                        $server = "ldap://10.1.30.2";
                        $ds=ldap_connect($server);
                        $dn = 'OU=Ldap,DC=pu,DC=local';
                        $userBind = 'alhadi.rahman'.'@pu.local';
                        $filter="(|(sAMAccountName=$NPM))";
                        $pwdBind = 'IT@podomoro6737ht';
                         if ($bind = ldap_bind($ds, $userBind , $pwdBind)) {
                             $sr = ldap_search($ds, $dn, $filter);
                             $ent= ldap_get_entries($ds,$sr);
                            //  $cn = $ent[0]['cn'][0];
                             $cn = 'test ad1';
                             if ($ent["count"] == 1) {
                               // api delete
                               // dsrm -noprompt CN="test ad",OU=Ldap,DC=pu,DC=local
                                $script = 'dsrm -noprompt CN="'.$cn.'",OU=Ldap,DC=pu,DC=local';
                                $data = array(
                                    'auth' => 's3Cr3T-G4N',
                                    'Type' => 'Student',
                                    'script' => $script,
                                );
                                
                                if($_SERVER['SERVER_NAME']=='pcam.podomorouniversity.ac.id') { // AD jalannya hanya di live
                                    $url = URLAD.'__api/Delete';
                                    $token = $this->jwt->encode($data,"UAP)(*");
                                    $this->m_master->apiservertoserver_NotWaitResponse($url,$token);
                                    $MSG .= '<br/>AD Proses Delete : Finish';
                                }

                             }
                             else {
                                $MSG .= '<br/>AD Data : Tidak Ada';
                             }
                         }

                         // Remove file di folder upload/document/{NPM}
                         $path = FCPATH.'uploads\\document\\'.$NPM;
                         if (file_exists('./uploads/document/'.$NPM)) {
                            $this->m_master->deleteDir($path);
                            $MSG .= '<br/>Remove Folder upload/document/'.$NPM.' : Selesai';
                          } else {
                            $MSG .= '<br/>Remove Folder upload/document/'.$NPM.' : Directory tidak ada';
                          }

                          // Remove file photo di folder upload/students/{ta}/{NIP}{ekstension}
                          $G_dtMHS = $this->m_master->caribasedprimary($taDB.'.students','NPM',$NPM);
                          if (count($G_dtMHS) > 0) {
                            $Photo = $G_dtMHS[0]['Photo'];
                            if (file_exists('./upload/students/'.$ta.'/'.$Photo)) {
                                $path = FCPATH.'uploads\\students\\'.$ta.'\\'.$Photo;
                                unlink($path);
                                $MSG .= '<br/>File Photo  : Berhasil Dihapus';
                            }
                            else {
                                $MSG .= '<br/>File Photo  : tidak ada';
                            }
                          }
                          else {
                            $MSG .= '<br/>Data Student  : tidak ada';
                          }

                          // remove di db_finance
                          $G_dtFin = $this->m_master->caribasedprimary('db_finance.payment','NPM',$NPM);
                          $countCek = 0;
                          for ($z=0; $z < count($G_dtFin); $z++) { 
                              // delete di payment student first
                              $this->db->where('ID_payment',$G_dtFin[$z]['ID']);
                              $this->db->delete('db_finance.payment_students');

                              // delete di payment
                              $this->db->where('ID',$G_dtFin[$z]['ID']);
                              $this->db->delete('db_finance.payment');
                              if ($this->db->affected_rows() > 0 )
                              {
                                $countCek++;
                              }
                          }
                          
                          if ($countCek == count($G_dtFin)) {
                            $MSG .= '<br/>Data Student -- Finance  : Berhasil dihapus';
                          }
                          else {
                            $MSG .= '<br/>Data Student -- Finance  : Gagal dihapus';
                          }

                          // delete di m_tuition fee
                          $this->db->where('NPM',$NPM);
                          $this->db->delete('db_finance.m_tuition_fee');
                          if ($this->db->affected_rows() > 0 )
                          {
                            $MSG .= '<br/>Data Student -- m_tuition_fee  : Berhasil dihapus';
                          }
                          else {
                            $MSG .= '<br/>Data Student -- m_tuition_fee  : Gagal dihapus';
                          }

                          // Remove data di db_academic.auth_students
                          $this->db->where('NPM',$NPM);
                          $this->db->delete('db_academic.auth_students');
                          if ($this->db->affected_rows() > 0 )
                          {
                            $MSG .= '<br/>Data Student -- auth_students  : Berhasil dihapus';
                          }
                          else {
                            $MSG .= '<br/>Data Student -- auth_students  : Gagal dihapus';
                          }

                          // Remove data di db_academic.auth_students
                          $this->db->where('NPM',$NPM);
                          $this->db->delete('db_academic.auth_parents');
                          if ($this->db->affected_rows() > 0 )
                          {
                            $MSG .= '<br/>Data Student -- auth_parents  : Berhasil dihapus';
                          }
                          else {
                            $MSG .= '<br/>Data Student -- auth_parents  : Gagal dihapus';
                          }

                          // Remove data di db_admission.doc_mhs
                          $this->db->where('NPM',$NPM);
                          $this->db->delete('db_admission.doc_mhs');
                          if ($this->db->affected_rows() > 0 )
                          {
                            $MSG .= '<br/>Data Student -- doc_mhs  : Berhasil dihapus';
                          }
                          else {
                            $MSG .= '<br/>Data Student -- doc_mhs  : Gagal dihapus';
                          }

                          // Remove data di db_admission.to_be_mhs
                          $this->db->where('NPM',$NPM);
                          $this->db->delete('db_admission.to_be_mhs');
                          if ($this->db->affected_rows() > 0 )
                          {
                            $MSG .= '<br/>Data Student -- to_be_mhs  : Berhasil dihapus';
                          }
                          else {
                            $MSG .= '<br/>Data Student -- to_be_mhs  : Gagal dihapus';
                          }

                          // Remove data di {ta}.students by NPM
                          $this->db->where('NPM',$NPM);
                          $this->db->delete($taDB.'.students');
                          if ($this->db->affected_rows() > 0 )
                          {
                            $MSG .= '<br/>Data Student -- {ta}.students  : Berhasil dihapus';
                          }
                          else {
                            $MSG .= '<br/>Data Student -- {ta}.students  : Gagal dihapus';
                          }
                    }
                }

                $DataSelected[$i]['Status'] = $MSG;
            }

            $rs['Dt'] = $DataSelected;
            echo json_encode($rs);
            
        }     
        else {
            
        }
        
    }

}