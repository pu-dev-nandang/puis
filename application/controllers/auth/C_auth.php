
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Nandang
 * Date: 12/20/2017
 * Time: 1:41 PM
 * edited by adhi setelah implement abstract class, datetime : 12/04/2018
 */


class C_auth extends Globalclass { 

    public function __construct()
    {
        parent::__construct();
        $this->db_server = $this->load->database('server', TRUE);
        $this->db = $this->load->database('default', TRUE);
    }

    public function get_auth()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

//        $pas =md5($password);
//        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

        $pass = md5($password);

        $data = $this->db->query('SELECT * FROM siak4.user u JOIN siak4.karyawan k ON() WHERE ');

//        $array = array('Nama' => $username, 'Password' => $pass);
//        $this->db->where($array);
//        $query = $this->db->get('siak4.user');

        print_r($data->result_array());




    }

    public function db($table=''){
        $max_execution_time = 360;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes

//        $this->load->view('md5');

        if($table=='karyawan'){

            $data = $this->db_server->query('SELECT k.*, u.Password AS Password_Old, u.Lock FROM siak4.karyawan k 
                                              LEFT JOIN siak4.user u ON (u.Nama = k.NIP AND k.ID = u.EntityID)')->result_array();

            for($i=0;$i<count($data);$i++){
                switch ($data[$i]['NIP']){
                    case '2017090':
                        $PositionMain = '12.13';
                        $EmailPU = 'nandang.mulyadi@podomorouniversity.ac.id';
                        break;
                    case '2016065' :
                        $PositionMain = '12.13';
                        $EmailPU = 'novita.riani@podomorouniversity.ac.id';
                        break;
                    case '2018018' :
                        $PositionMain = '12.13';
                        $EmailPU = 'alhadi.rahman@podomorouniversity.ac.id';
                        break;
                    case '2018034' :
                        $PositionMain = '12.11';
                        $EmailPU = 'martin.hasen@podomorouniversity.ac.id';
                        break;

                    default :
                        $PositionMain = '';
                        $EmailPU = '';
                }

                $Status = ($data[$i]['Lock']==0) ? '-1' : '0';
                $NIP = trim($data[$i]['NIP']);

                $f = explode('.',$data[$i]['Foto']);
                $foto = (count($f)==2) ? $NIP.'.'.$f[1] : '' ;

                // Get Email Podomoro
                if($EmailPU==''){
                    $s = $data[$i]['Email'];
                    $ex = explode(',',$s);
                    for ($d=0;$d<count($ex);$d++){
                        $eeeem = explode('@podomorouniversity',$ex[$d]);
                        if(count($eeeem)>0){
                            $EmailPU = trim($ex[$d]);
                            break;
                        }
                    }
                }

                $arr = array(
                    "ReligionID" => $data[$i]['AgamaID'],
                    "PositionMain" => $PositionMain,
                    "CityID" => $data[$i]['KotaID'],
                    "ProvinceID" => $data[$i]['PropinsiID'],
                    "NIP" => $NIP,
                    "KTP" => $data[$i]['KTP'],
                    "Name" => $data[$i]['Nama'],
                    "TitleAhead" => $data[$i]['Title'],
                    "TitleBehind" => $data[$i]['Gelar'],
                    "Gender" => $data[$i]['Kelamin'],
                    "PlaceOfBirth" => $data[$i]['TempatLahir'],
                    "DateOfBirth" => $data[$i]['TanggalLahir'],
                    "Phone" => $data[$i]['Telepon'],
                    "HP" => $data[$i]['HP'],
                    "Email" => $data[$i]['Email'],
                    "EmailPU" => $EmailPU,
                    "Password" => $this->genratePassword($NIP,123456),
                    "Password_Old" => $data[$i]['Password_Old'],
                    "Address" => $data[$i]['Alamat'],
                    "Photo" => $foto,
                    "Status" => $Status
                );

                $this->db->insert('db_employees.employees',$arr);
            }
        }
        else if($table=='dosen'){
            $data = $this->db_server->query('SELECT k.*, u.Password AS Password_Old, u.Lock
                                                        FROM siak4.dosen k
                                                        LEFT JOIN siak4.user u
                                                        ON (k.NIP = u.Nama AND k.ID = u.EntityID) WHERE u.TabelUserID = 2')
                                    ->result_array();

            for($i=0;$i<count($data);$i++){
//                $data_cek = $this->db->query('SELECT * FROM db_employees.employees WHERE NIP = "'.$data[$i]['NIP'].'" ')->result_array();
                $data_cek = $this->db_server->query('SELECT * FROM siak4.karyawan WHERE NIP = "'.trim($data[$i]['NIP']).'" ')->result_array();
                if(count($data_cek)>0){

                }
                else {

                    $StatusEmployeeID = ($data[$i]['StatusPegawai']=='Tetap')? 3 : 4;

                    $ProdiID = $data[$i]['ProdiID'];

                    if($ProdiID=='3') {
                        $ProdiID = 1;
                    }
                    else if($ProdiID=='4'){
                        $ProdiID = 2;
                    }
                    else if($ProdiID=='6'){
                        $ProdiID = 3;
                    }
                    else if($ProdiID=='7'){
                        $ProdiID = 4;
                    }
                    else if($ProdiID=='13'){
                        $ProdiID = 5;
                    }
                    else if($ProdiID=='14'){
                        $ProdiID = 6;
                    }
                    else if($ProdiID=='15'){
                        $ProdiID = 7;
                    }
                    else if($ProdiID=='16'){
                        $ProdiID = 8;
                    }
                    else if($ProdiID=='17'){
                        $ProdiID = 9;
                    }
                    else if($ProdiID=='18'){
                        $ProdiID = 10;
                    }
                    else if($ProdiID=='19'){
                        $ProdiID = 11;
                    }


                    $NIP = trim($data[$i]['NIP']);

                    $Status = ($data[$i]['Lock']==0) ? '-1' : '0';

                    $f = explode('.',$data[$i]['Foto']);
                    $foto = (count($f)==2) ? $NIP.'.'.$f[1] : '' ;

                    $PositionMain = '14.7';
                    if($NIP=='2114002' || $NIP=='3114016'){
                        $PositionMain = '14.5';
                    } else if($NIP=='1116066' || $NIP=='1217099' || $NIP=='2218002' ||
                        $NIP=='2415078' || $NIP=='2516028' || $NIP=='2617100' || $NIP=='3017098' || $NIP=='3114014'){
                        $PositionMain = '14.6';
                    }

                    $arr = array(
                        "ReligionID" => $data[$i]['AgamaID'],

                        "PositionMain" => $PositionMain,
                        "ProdiID" => $ProdiID,
                        "StatusEmployeeID" => $StatusEmployeeID,
                        "CityID" => $data[$i]['KotaID'],
                        "ProvinceID" => $data[$i]['PropinsiID'],
                        "NIP" => $NIP,
                        "KTP" => $data[$i]['KTP'],
                        "Name" => $data[$i]['Nama'],
                        "TitleAhead" => $data[$i]['Title'],
                        "TitleBehind" => $data[$i]['Gelar'],
                        "Gender" => $data[$i]['Kelamin'],
                        "PlaceOfBirth" => $data[$i]['TempatLahir'],
                        "DateOfBirth" => $data[$i]['TanggalLahir'],
                        "Phone" => $data[$i]['Telepon'],
                        "HP" => $data[$i]['HP'],
                        "Email" => $data[$i]['Email'],
                        "Password" => $this->genratePassword($data[$i]['NIP'],123456),
                        "Password_Old" => $data[$i]['Password_Old'],
                        "Address" => $data[$i]['Alamat'],
                        "NIDN" => $data[$i]['NIDN'],
                        "Photo" => $foto,
                        "Status" => $Status

                    );

//                    $no += 1;

//                    print_r(array('NIP'=>$data[$i]['NIP']));

                    $this->db->insert('db_employees.employees',$arr);
                }
            }

        }

        else if($table=='dosen_baru'){
            $data = $this->db_server->query('SELECT k.*, u.Password AS Password_Old, u.Lock
                                                        FROM siak4.dosen k
                                                        LEFT JOIN siak4.user u
                                                        ON (k.NIP = u.Nama AND k.ID = u.EntityID) WHERE u.TabelUserID = 2')
                ->result_array();

            for($i=0;$i<count($data);$i++){
                // Cek apakah sudah ada di sistem baru atau belum
                $dataNew = $this->db->select('NIP')->get_where('db_employees.employees',array('NIP' => trim($data[$i]['NIP'])))->result_array();

                if(count($dataNew)<=0){
                    $StatusEmployeeID = ($data[$i]['StatusPegawai']=='Tetap')? 3 : 4;

                    $ProdiID = $data[$i]['ProdiID'];

                    if($ProdiID=='3') {
                        $ProdiID = 1;
                    }
                    else if($ProdiID=='4'){
                        $ProdiID = 2;
                    }
                    else if($ProdiID=='6'){
                        $ProdiID = 3;
                    }
                    else if($ProdiID=='7'){
                        $ProdiID = 4;
                    }
                    else if($ProdiID=='13'){
                        $ProdiID = 5;
                    }
                    else if($ProdiID=='14'){
                        $ProdiID = 6;
                    }
                    else if($ProdiID=='15'){
                        $ProdiID = 7;
                    }
                    else if($ProdiID=='16'){
                        $ProdiID = 8;
                    }
                    else if($ProdiID=='17'){
                        $ProdiID = 9;
                    }
                    else if($ProdiID=='18'){
                        $ProdiID = 10;
                    }
                    else if($ProdiID=='19'){
                        $ProdiID = 11;
                    }


                    $NIP = trim($data[$i]['NIP']);

                    $Status = ($data[$i]['Lock']==0) ? '-1' : '0';

                    $f = explode('.',$data[$i]['Foto']);
                    $foto = (count($f)==2) ? $NIP.'.'.$f[1] : '' ;

                    $PositionMain = '14.7';
                    if($NIP=='2114002' || $NIP=='3114016'){
                        $PositionMain = '14.5';
                    } else if($NIP=='1116066' || $NIP=='1217099' || $NIP=='2218002' ||
                        $NIP=='2415078' || $NIP=='2516028' || $NIP=='2617100' || $NIP=='3017098' || $NIP=='3114014'){
                        $PositionMain = '14.6';
                    }

                    $arr = array(
                        "ReligionID" => $data[$i]['AgamaID'],

                        "PositionMain" => $PositionMain,
                        "ProdiID" => $ProdiID,
                        "StatusEmployeeID" => $StatusEmployeeID,
                        "CityID" => $data[$i]['KotaID'],
                        "ProvinceID" => $data[$i]['PropinsiID'],
                        "NIP" => $NIP,
                        "KTP" => $data[$i]['KTP'],
                        "Name" => $data[$i]['Nama'],
                        "TitleAhead" => $data[$i]['Title'],
                        "TitleBehind" => $data[$i]['Gelar'],
                        "Gender" => $data[$i]['Kelamin'],
                        "PlaceOfBirth" => $data[$i]['TempatLahir'],
                        "DateOfBirth" => $data[$i]['TanggalLahir'],
                        "Phone" => $data[$i]['Telepon'],
                        "HP" => $data[$i]['HP'],
                        "Email" => $data[$i]['Email'],
                        "Password" => $this->genratePassword($data[$i]['NIP'],123456),
                        "Password_Old" => $data[$i]['Password_Old'],
                        "Address" => $data[$i]['Alamat'],
                        "NIDN" => $data[$i]['NIDN'],
                        "Photo" => $foto,
                        "Status" => $Status

                    );

//                    $no += 1;

//                    print_r(array('NIP'=>$data[$i]['NIP']));

                    $this->db->insert('db_employees.employees',$arr);
                }

            }
        }

        else if($table=='cekdosen'){
            $data = $this->db_server->query('SELECT k.*, u.Password AS Password_Old, u.Lock 
                                                        FROM siak4.dosen k 
                                                        RIGHT JOIN siak4.user u 
                                                        ON (k.NIP = u.Nama AND k.ID = u.EntityID)')
                ->result_array();
            $dataLec = [];
            for($i=0;$i<count($data);$i++){
                $data_cek = $this->db_server->query('SELECT * FROM siak4.karyawan WHERE NIP = "'.$data[$i]['NIP'].'" ')->result_array();
                if(count($data_cek)>0){
                    $arr = array(
                        'NIP' => $data_cek[0]['NIP'],
                        'Name' => $data_cek[0]['Nama']
                    );
                    array_push($dataLec,$arr);
//                    print_r($data_cek);
//                    $no_sama += 1;
                }

            }

            print_r($dataLec);
        }

        else if($table=='prodi'){
            $data = $this->db_server->query('SELECT * FROM siak4.programstudi')->result_array();
            for($i=0;$i<count($data);$i++){
                $EducationLevelID = $data[$i]['JenjangID'];
                if($EducationLevelID==8) { $EducationLevelID = 5;}
                else if($EducationLevelID==6) { $EducationLevelID = 4;}
                $arr = array(
                    'EducationLevelID' => $EducationLevelID,
                    'FacultyID' => $data[$i]['FakultasID'],
                    'KaprodiID' => $data[$i]['KaProdiID'],
                    'DiktiID' => $data[$i]['ProdiDiktiID'],
                    'Code' => $data[$i]['Kode'],
                    'Name' => $data[$i]['Nama'],
                    'NameEng' => $data[$i]['NamaInggris'],
                    'Akreditasi' => $data[$i]['Akreditasi'],
                    'AkreditasiDate' => $data[$i]['TglAK'],
                    'NoSK' => $data[$i]['NoSK'],
                    'SKDate' => $data[$i]['TglSK'],
                    'TotalSKS' => $data[$i]['JmlSKS'],
                    'Email' => $data[$i]['Email'],
                    'NoSKBANPT' => $data[$i]['NoSKBAN'],
                    'SKBANPTDate' => $data[$i]['TglSKBAN'],
                    'AkreditasiBANPTDate' => $data[$i]['TglABAN'],
                    'Visi' => $data[$i]['Visi'],
                    'Misi' => $data[$i]['Misi']
                );
                $this->db->insert('db_academic.program_study',$arr);
            }
        }
        else if($table=='mk'){
            $data = $this->db_server->query('SELECT * FROM siak4.matakuliah')->result_array();


            // Double MKCode
            //SELECT * FROM db_academic.mata_kuliah WHERE MKCode IN (SELECT MKCode FROM db_academic.mata_kuliah GROUP BY MKCode HAVING count(*) > 1);

            $this->db->truncate('db_academic.mata_kuliah');
            foreach($data as $item){
                $ProdiID = $item['BaseProdiID'];

                if($ProdiID=='3') {
                    $ProdiID = 1;
                }
                else if($ProdiID=='4'){
                    $ProdiID = 2;
                }
                else if($ProdiID=='6'){
                    $ProdiID = 3;
                }
                else if($ProdiID=='7'){
                    $ProdiID = 4;
                }
                else if($ProdiID=='13'){
                    $ProdiID = 5;
                }
                else if($ProdiID=='14'){
                    $ProdiID = 6;
                }
                else if($ProdiID=='15'){
                    $ProdiID = 7;
                }
                else if($ProdiID=='16'){
                    $ProdiID = 8;
                }
                else if($ProdiID=='17'){
                    $ProdiID = 9;
                }
                else if($ProdiID=='18'){
                    $ProdiID = 10;
                }
                else if($ProdiID=='19'){
                    $ProdiID = 11;
                }
                $arr = array(
                    'ID' => $item['ID'],
                    'MKCode' => $item['MKKode'],
                    'Name' => $item['NamaIndo'],
                    'NameEng' => $item['NamaInggris'],
                    'BaseProdiID' => $ProdiID,
                    'UpdateBy' => '2017090',
                    'UpdateAt' => '2017-01-09 10:10:10'
                );
                print_r($arr);

                $this->db->insert('db_academic.mata_kuliah',$arr);
            }


        }
        else if($table=='kurikulum'){

            $data = $this->db_server->query('SELECT dt.*,mk.nama,k.nama as K,mk.MKKode as MKCode , d.NIP FROM siak4.detailkurikulum dt 
                                                  JOIN siak4.matakuliah mk ON (dt.MKID=mk.ID)
                                                  JOIN siak4.kurikulum k ON (dt.KurikulumID = k.ID)
                                                  LEFT JOIN siak4.dosen d ON (dt.DosenPeng = d.ID)')->result_array();

//            print_r($data);
//exit;
            $this->db->truncate('db_academic.curriculum_details');
            foreach ($data as $item){
                $ProdiID = $item['ProdiID'];

                if($ProdiID=='3') {
                    $ProdiID = 1;
                }
                    else if($ProdiID=='4'){
                    $ProdiID = 2;
                }
                    else if($ProdiID=='6'){
                    $ProdiID = 3;
                }
                    else if($ProdiID=='7'){
                    $ProdiID = 4;
                }
                    else if($ProdiID=='13'){
                    $ProdiID = 5;
                }
                    else if($ProdiID=='14'){
                    $ProdiID = 6;
                }
                    else if($ProdiID=='15'){
                    $ProdiID = 7;
                }
                    else if($ProdiID=='16'){
                    $ProdiID = 8;
                }
                    else if($ProdiID=='17'){
                    $ProdiID = 9;
                }
                    else if($ProdiID=='18'){
                    $ProdiID = 10;
                }
                    else if($ProdiID=='19'){
                    $ProdiID = 11;
                }

//                $PreconditionMKID = ($item['MKIDpra']!=0 && $item['MKIDpra']!=null)?$item['MKIDpra']:'';
                $StatusPrecondition = '0';
                $PreconditionMKID = null;
                if($item['MKIDpra']!=null && $item['MKIDpra']!=0 && $item['MKIDpra']!='0' && $item['MKIDpra']!=''){
                    $StatusPrecondition = '1';
                    $PreconditionMKID_arr = explode(',',$item['MKIDpra']);
                    $PreconditionMKID = [];
                    for($p=0;$p<count($PreconditionMKID_arr);$p++){
                        if($PreconditionMKID_arr[$p]!='0')  {
                            $dataMKC = $this->db_server->get_where('siak4.matakuliah',array('ID'=>$PreconditionMKID_arr[$p]),1)->result_array();
                            $MKcKode = (count($dataMKC)>0) ? $dataMKC[0]['MKKode'] : '';
                            array_push($PreconditionMKID,$PreconditionMKID_arr[$p].'.'.$MKcKode);
                        }
                    }
                }
//                $PreconditionMKID = $item['MKIDpra'];
//                $StatusPrecondition = ($item['MKIDpra']!=null || $item['MKIDpra']!=0) ? '1' : '0';
                $arr = array(
                    'ID' => $item['ID'],
                    'CurriculumID' => $item['KurikulumID'],
                    'CurriculumTypeID' => $item['JenisKurikulumID'],
                    'MKID' => $item['MKID'],
//                    'MKCode' => $item['MKCode'],
//                    'ProdiIDBefore' => $item['ProdiID'],
                    'ProdiID' => $ProdiID,
//                    'EducationLevelID' => $EducationLevelID,
                    'LecturerNIP' => $item['NIP'],
                    'MKType' => $item['JenisMK'],
                    'Semester' => $item['Semester'],
                    'TotalSKS' => $item['TotalSKS'],
                    'SKSTeori' => $item['SKSTatapMuka'],
                    'SKSPraktikum' => $item['SKSPraktikum'],
                    'StatusPrecondition' => $StatusPrecondition,
//                    'PreconditionMKID' => $item['MKIDpra'],
                    'DataPrecondition' => json_encode($PreconditionMKID),
                    'SKSPraktikLapangan' => $item['SKSPraktekLap'],
                    'Syllabus' => $item['Silabus'],
                    'SAP' => $item['SAP'],
                    'StatusMK' => $item['StatusMK'],
                    'UpdateBy' => '2017090',
                    'UpdateAt' => '2018-01-08 10:10:10'
                );

//                print_r($arr);

                $this->db->insert('db_academic.curriculum_details',$arr);
            }

//            print_r(count($data));


        }
        else if($table=='krs_old'){
            $angkatan = 2017;

            $db_lokal = 'ta_'.$angkatan;
//            $data = $this->db_server->query('SELECT r.ID,j.TahunID AS SemesterID, th.TahunID AS YearCode,m.NPM,r.JadwalID AS ScheduleID,
//mk.MKKode AS MKCode,mk.Nama AS MKName, mk.NamaInggris AS MKNameEng,
//r.Evaluasi1,r.Evaluasi2,r.Evaluasi3,r.Evaluasi4,r.Evaluasi5,
//r.UTS,r.UAS,r.NilaiAkhir AS Score,r.NilaiHuruf AS Grade,r.approval AS Approval
//from siak4.rencanastudi r
//left JOIN siak4.mahasiswa m ON (r.MhswID=m.ID)
//left join siak4.jadwal j ON (r.JadwalID = j.ID)
//left join siak4.tahun th ON(j.TahunID=th.ID)
//left join siak4.matakuliah mk ON(j.MKID = mk.ID)
//WHERE r.JadwalID!=\'\' AND r.NilaiAkhir!=0.00 AND substring(m.NPM,3,2)='.$angkatan)->result_array();

            $dataMhs = $this->db_server->query('SELECT ID FROM siak4.mahasiswa m where m.TahunMasuk='.$angkatan)->result_array();
            $this->db->truncate($db_lokal.'.study_planning');
            for($m=0;$m<count($dataMhs);$m++){

                $data = $this->db_server->query('SELECT 
                                                r.ID,j.TahunID AS SemesterID, 
                                                r.JadwalID AS ScheduleID,
                                                r.Evaluasi1,r.Evaluasi2,r.Evaluasi3,r.Evaluasi4,r.Evaluasi5,
                                                r.UTS,r.UAS,r.NilaiAkhir AS Score,r.NilaiHuruf AS Grade,r.approval AS Approval,
                                                m.ID AS MKID, m.MKKode AS MKCode,m.NamaIndo AS MKName, m.NamaInggris AS MKNameEng,
                                                mhs.NPM,
                                                th.TahunID AS YearCode, dt.TotalSKS AS Credit,
                                                dt.ID AS CDID
                                                                                        
                                                 FROM siak4.rencanastudi r 
                                                JOIN siak4.jadwal j ON (r.JadwalID=j.ID)
                                                JOIN siak4.matakuliah m ON (m.ID=j.MKID)
                                                join siak4.mahasiswa mhs ON(r.MhswID = mhs.ID)
                                                join siak4.tahun th ON(j.TahunID=th.ID)
                                                left JOIN siak4.detailkurikulum dt ON (dt.MKID = j.MKID)
                                                where r.MhswID = '.$dataMhs[$m]['ID'].'
                                                GROUP BY m.ID')->result_array();

                for($i=0;$i<count($data);$i++){

                    $GradeValue = [4.00,3.70,3.30,3.00,2.70,2.30,2.00,1.00,0.00];
                    $Grade = ['A','A-','B+','B','B-','C+','C','D','E'];

                    $GV = $GradeValue[array_search($data[$i]['Grade'],$Grade)];

                    $data_insert = array(
                        'SemesterID' => $data[$i]['SemesterID'],
                        'YearCode' => $data[$i]['YearCode'],
                        'NPM' => $data[$i]['NPM'],
                        'ScheduleID' => $data[$i]['ScheduleID'],
                        'CDID' => $data[$i]['CDID'],
                        'MKID' => $data[$i]['MKID'],
                        'Credit' => $data[$i]['Credit'],
                        'MKName' => $data[$i]['MKName'],
                        'MKNameEng' => $data[$i]['MKNameEng'],
                        'Evaluasi1' => $data[$i]['Evaluasi1'],
                        'Evaluasi2' => $data[$i]['Evaluasi2'],
                        'Evaluasi3' => $data[$i]['Evaluasi3'],
                        'Evaluasi4' => $data[$i]['Evaluasi4'],
                        'Evaluasi5' => $data[$i]['Evaluasi5'],
                        'UTS' => $data[$i]['UTS'],
                        'UAS' => $data[$i]['UAS'],
                        'Score' => $data[$i]['Score'],
                        'Grade' => $data[$i]['Grade'],
                        'GradeValue' => $GV,
                        'Approval' => $data[$i]['Approval'],
                        'StatusSystem' => '0'
                    );
                    $this->db->insert($db_lokal.'.study_planning',$data_insert);
                }

            }



        }

        else if($table=='krs'){

            $max_execution_time = 360;
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes

            $db_ = 2014;
            $db_lokal = 'ta_'.$db_;
            $dataMhs = $this->db_server->query('SELECT m.ID, m.NPM, r.MKID FROM siak4.rencanastudi r 
                                                LEFT JOIN siak4.mahasiswa m ON (m.ID = r.MhswID) 
                                                WHERE (m.TahunMasuk = "'.$db_.'") AND (r.) GROUP BY m.NPM ORDER BY m.NPM, r.TahunID ASC')->result_array();

            $res=[];

            $this->db->truncate($db_lokal.'.study_planning');
            for($m=0;$m<count($dataMhs);$m++){

                $dataRencana = $this->db_server->query('SELECT r.MKID,r.NilaiAkhir,r.TahunID, 
                                                      r.JadwalID AS ScheduleID,
                                                      r.Evaluasi1,r.Evaluasi2,r.Evaluasi3,r.Evaluasi4,r.Evaluasi5,r.UTS,r.UAS,
                                                      r.NilaiHuruf AS Grade,r.approval AS Approval,r.glue AS Glue,
                                                      dt.TotalSKS AS Credit, dt.ID AS CDID
                                                      FROM siak4.rencanastudi r 
                                                      LEFT JOIN siak4.matakuliah m ON (m.ID=r.MKID)
                                                      LEFT JOIN siak4.jadwal j ON (r.JadwalID=j.ID)
                                                      LEFT JOIN siak4.detailkurikulum dt ON (dt.MKID = j.MKID)
                                                      WHERE r.MhswID= "'.$dataMhs[$m]['ID'].'" ORDER BY MKID ASC ')->result_array();
                $MKID= '';
                $NilaiAkhir = '';

                $next = 1;

                for($n=0;$n<count($dataRencana);$n++){


                    $next = $n+1;

//                    print_r($n.' - '.$next.' | ');
                    if($next==count($dataRencana)){
                        $MKID= $dataRencana[$n]['MKID'];
                        $NilaiAkhir = $dataRencana[$n]['NilaiAkhir'];

                        $g = $this->getBobot($dataRencana[$n]['TahunID'],$NilaiAkhir);

                        $arrp = array(

                            'SemesterID' => $dataRencana[$n]['TahunID'],
                            'MhswID' => $dataMhs[$m]['ID'],
                            'NPM' => $dataMhs[$m]['NPM'],
                            'ScheduleID' => $dataRencana[$n]['ScheduleID'],
                            'TypeSchedule' => 'Br',
                            'CDID' => $dataRencana[$n]['CDID'],
                            'MKID' => $MKID,
                            'Credit' => $dataRencana[$n]['Credit'],

                            'Evaluasi1' => $dataRencana[$n]['Evaluasi1'],
                            'Evaluasi2' => $dataRencana[$n]['Evaluasi2'],
                            'Evaluasi3' => $dataRencana[$n]['Evaluasi3'],
                            'Evaluasi4' => $dataRencana[$n]['Evaluasi4'],
                            'Evaluasi5' => $dataRencana[$n]['Evaluasi5'],
                            'UTS' => $dataRencana[$n]['UTS'],
                            'UAS' => $dataRencana[$n]['UAS'],
                            'Score' => $NilaiAkhir,
                            'Grade' => $g['Nilai'],
                            'GradeValue' => $g['Bobot'],
                            'Approval' => ''.$dataRencana[$n]['Approval'],
                            'StatusSystem' => '0',
                            'Glue' => $dataRencana[$n]['Glue'],
                            'Status' => '1'

                        );
                        array_push($res,$arrp);
                        $this->db->insert($db_lokal.'.study_planning',$arrp);
                    } else {
                        if($dataRencana[$n]['MKID']==$dataRencana[$n+1]['MKID']){
                            $Nilai = $dataRencana[$n]['NilaiAkhir'];
                            if($Nilai>$NilaiAkhir){
                                $NilaiAkhir = $Nilai;
                            }
                        }
                        else {
                            $MKID= $dataRencana[$n]['MKID'];
                            $NilaiAkhir = $dataRencana[$n]['NilaiAkhir'];

                            $g = $this->getBobot($dataRencana[$n]['TahunID'],$NilaiAkhir);

                            $arrp = array(

                                'SemesterID' => $dataRencana[$n]['TahunID'],
                                'MhswID' => $dataMhs[$m]['ID'],
                                'NPM' => $dataMhs[$m]['NPM'],
                                'ScheduleID' => $dataRencana[$n]['ScheduleID'],
                                'TypeSchedule' => 'Br',
                                'CDID' => $dataRencana[$n]['CDID'],
                                'MKID' => $MKID,
                                'Credit' => $dataRencana[$n]['Credit'],

                                'Evaluasi1' => $dataRencana[$n]['Evaluasi1'],
                                'Evaluasi2' => $dataRencana[$n]['Evaluasi2'],
                                'Evaluasi3' => $dataRencana[$n]['Evaluasi3'],
                                'Evaluasi4' => $dataRencana[$n]['Evaluasi4'],
                                'Evaluasi5' => $dataRencana[$n]['Evaluasi5'],
                                'UTS' => $dataRencana[$n]['UTS'],
                                'UAS' => $dataRencana[$n]['UAS'],
                                'Score' => $NilaiAkhir,
                                'Grade' => $g['Nilai'],
                                'GradeValue' => $g['Bobot'],
                                'Approval' => ''.$dataRencana[$n]['Approval'],
                                'StatusSystem' => '0',
                                'Glue' => $dataRencana[$n]['Glue'],
                                'Status' => '1'

                            );
                            array_push($res,$arrp);
                        $this->db->insert($db_lokal.'.study_planning',$arrp);

                        }
                    }





                }

//                print_r(count($res));
//                print_r($res);


            }

        }


        else if($table=='auth'){

            $data = $this->db_server->query('SELECT mhs.*, u.Password AS Password_Old FROM siak4.mahasiswa mhs 
                                                LEFT JOIN siak4.user u ON (u.Nama = mhs.NPM)
                                                WHERE mhs.StatusMhswID = 3 ORDER BY mhs.TahunMasuk ASC ')->result_array();
            $this->db->truncate('db_academic.auth_students');
            for($i=0;$i<count($data);$i++){
                $EmailPU = $data[$i]['Email'];

                $arrAuth = array(
                    'NPM' => $data[$i]['NPM'],
                    'Password' => $this->genratePassword($data[$i]['NPM'],'123456'),
                    'Password_Old' => $data[$i]['Password_Old'],
                    'Year' => $data[$i]['TahunMasuk'],
                    'EmailPU' => trim(strtolower($EmailPU)),
                    'StatusStudentID' => $data[$i]['StatusMhswID'],
                    'Status' => '-1'
                );

                $this->db->insert('db_academic.auth_students',$arrAuth);
            }

        }
        else if($table=='mhs'){
            $angkatan = 2014;

            $db_lokal = 'ta_'.$angkatan;
            $data = $this->db_server->query('SELECT d.NIP AS AcademicMentor,mhs.* FROM siak4.mahasiswa mhs 
                                                    LEFT JOIN siak4.setpembimbing s ON (mhs.ID=s.MhswID)
                                                    LEFT JOIN siak4.pembimbing p ON (s.PembimbingID = p.ID)
                                                    LEFT JOIN siak4.dosen d ON (d.ID = p.PembimbingID)
                                                WHERE mhs.TahunMasuk =  '.$angkatan)->result_array();


            $this->db->truncate($db_lokal.'.students');


            for($i=0;$i<count($data);$i++){

                $ProdiID = $data[$i]['ProdiID'];

                if($ProdiID=='3') {
                    $ProdiID = 1;
                }
                else if($ProdiID=='4'){
                    $ProdiID = 2;
                }
                else if($ProdiID=='6'){
                    $ProdiID = 3;
                }
                else if($ProdiID=='7'){
                    $ProdiID = 4;
                }
                else if($ProdiID=='13'){
                    $ProdiID = 5;
                }
                else if($ProdiID=='14'){
                    $ProdiID = 6;
                }
                else if($ProdiID=='15'){
                    $ProdiID = 7;
                }
                else if($ProdiID=='16'){
                    $ProdiID = 8;
                }
                else if($ProdiID=='17'){
                    $ProdiID = 9;
                }
                else if($ProdiID=='18'){
                    $ProdiID = 10;
                }
                else if($ProdiID=='19'){
                    $ProdiID = 11;
                }
                $arr = array(
                    'ProdiID' => $ProdiID,
                    'ID' => $data[$i]['ID'],
                    'ProgramID' => $data[$i]['ProgramID'],
                    'LevelStudyID' => $data[$i]['JenjangID'],
                    'ReligionID' => $data[$i]['AgamaID'],

                    'ProvinceID' => $data[$i]['PropinsiID'],
                    'CityID' => $data[$i]['KotaID'],
                    'HighSchool' => $data[$i]['NamaSekolah'],
                    'MajorsHighSchool' => $data[$i]['JurusanSekolah'],

                    'NPM' => $data[$i]['NPM'],
                    'Name' => $data[$i]['Nama'],
                    'Address' => $data[$i]['Alamat'],
                    'Photo' => $data[$i]['Foto'],
                    'Gender' => $data[$i]['Kelamin'],
                    'PlaceOfBirth' => $data[$i]['TempatLahir'],
                    'DateOfBirth' => $data[$i]['TanggalLahir'],
                    'Phone' => $data[$i]['Telepon'],
                    'HP' => $data[$i]['HP'],
//                    'Email' => '',
                    'ClassOf' => $data[$i]['TahunMasuk'],
//                    'EmailPU' => strtolower($EmailPU),
                    'Jacket' => $data[$i]['Jacket'],
//                    'AcademicMentor' => $data[$i]['AcademicMentor'],
                    'AnakKe' => $data[$i]['AnakKe'],
                    'JumlahSaudara' => $data[$i]['JumlahSaudara'],
                    'NationExamValue' => $data[$i]['Nilaiunas'],
                    'GraduationYear' => $data[$i]['TahunLulus'],
                    'IjazahNumber' => $data[$i]['NoIjazah'],

                    'Father' => $data[$i]['Ayah'],
                    'Mother' => $data[$i]['Ibu'],
                    'StatusFather' => $data[$i]['StatusAyah'],
                    'StatusMother' => $data[$i]['StatusIbu'],
                    'PhoneFather' => str_replace('/','',$data[$i]['PhoneAyah']),
                    'PhoneMother' => str_replace('/','',$data[$i]['PhoneIbu']),
                    'OccupationFather' => $data[$i]['PekerjaanAyah'],
                    'OccupationMother' => $data[$i]['PekerjaanIbu'],
                    'EducationFather' => $data[$i]['PDAyah'],
                    'EducationMother' => $data[$i]['PDIbu'],
                    'AddressFather' => $data[$i]['AlamatAyah'],
                    'AddressMother' => $data[$i]['AlamatIbu'],
                    'EmailFather' => $data[$i]['EmailAyah'],
                    'EmailMother' => $data[$i]['EmailIbu'],
                    'StatusStudentID' => $data[$i]['StatusMhswID']

                );

                $this->db->insert($db_lokal.'.students',$arr);

            }
        }
        else if($table=='mentor'){
            $data = $this->db_server->query('SELECT d.NIP AS AcademicMentor,mhs.* FROM siak4.mahasiswa mhs 
                                                    LEFT JOIN siak4.setpembimbing s ON (mhs.ID=s.MhswID)
                                                    LEFT JOIN siak4.pembimbing p ON (s.PembimbingID = p.ID)
                                                    LEFT JOIN siak4.dosen d ON (d.ID = p.PembimbingID) ORDER BY mhs.TahunMasuk ASC')->result_array();

            $this->db->truncate('db_academic.mentor_academic');

            for($i=0;$i<count($data);$i++){

                $ProdiID = $data[$i]['ProdiID'];

                if($ProdiID=='3') {
                    $ProdiID = 1;
                }
                else if($ProdiID=='4'){
                    $ProdiID = 2;
                }
                else if($ProdiID=='6'){
                    $ProdiID = 3;
                }
                else if($ProdiID=='7'){
                    $ProdiID = 4;
                }
                else if($ProdiID=='13'){
                    $ProdiID = 5;
                }
                else if($ProdiID=='14'){
                    $ProdiID = 6;
                }
                else if($ProdiID=='15'){
                    $ProdiID = 7;
                }
                else if($ProdiID=='16'){
                    $ProdiID = 8;
                }
                else if($ProdiID=='17'){
                    $ProdiID = 9;
                }
                else if($ProdiID=='18'){
                    $ProdiID = 10;
                }
                else if($ProdiID=='19'){
                    $ProdiID = 11;
                }

                $datains = array(
                    'ProdiID' => $ProdiID,
                    'Year' => trim($data[$i]['TahunMasuk']),
                    'NIP' => trim($data[$i]['AcademicMentor']),
                    'NPM' => trim($data[$i]['NPM']),
                    'Status' => '1',
                    'UpdateBy' => '2017090',
                    'UpdateAt' => '2018-02-02 09:00:00'
                );

                $this->db->insert('db_academic.mentor_academic',$datains);
            }

        }


        else if($table=='jadwal'){

            $sqlJadwal = 'SELECT j.ID,j.TahunID AS SemesterID, j.ProdiID, j.MKID, d.NIP, 
                                h.Nama AS Day, r.Nama AS Classroom, j.JamMulai AS Start ,j.JamSelesai AS End, j.glue AS Glue
                                FROM siak4.jadwal j 
                                LEFT JOIN siak4.hari h ON (h.ID=j.HariID)
                                LEFT JOIN siak4.ruang r ON (r.ID=j.RuangID)
                                LEFT JOIN siak4.dosen d ON (d.ID=j.DosenID)';
            $dataJadwal = $this->db_server->query($sqlJadwal)->result_array();


            $this->db->truncate('db_academic.z_schedule');
            $this->db->truncate('db_academic.z_team_teaching');

            for($i=0;$i<count($dataJadwal);$i++){
                $chek = $this->db_server->query('SELECT t.JadwalID AS ScheduleID, d.NIP , t.pengampu AS Pengampu , t.glue AS Glue
                                                  FROM siak4.teamteaching t
                                                  LEFT JOIN siak4.dosen d ON (d.ID = t.DosenID)
                                                  WHERE t.glue = "'.$dataJadwal[$i]['Glue'].'" ')->result_array();

//                $chek = $this->db->query('SELECT count(t.*) AS jml
//                                                  FROM siak4.teamteaching t
//                                                  WHERE t.glue = "'.$dataJadwal[$i]['Glue'].'" ')->result_array();
//                $dataJadwal[$i]['IsTeamTeaching'] = ($chek[0]['jml']>0) ? '1' : '0';
                $dataJadwal[$i]['IsTeamTeaching'] = (count($chek)>0) ? '1' : '0';

                $ProdiID = $dataJadwal[$i]['ProdiID'];

                if($ProdiID=='3') {
                    $ProdiID = 1;
                }
                else if($ProdiID=='4'){
                    $ProdiID = 2;
                }
                else if($ProdiID=='6'){
                    $ProdiID = 3;
                }
                else if($ProdiID=='7'){
                    $ProdiID = 4;
                }
                else if($ProdiID=='13'){
                    $ProdiID = 5;
                }
                else if($ProdiID=='14'){
                    $ProdiID = 6;
                }
                else if($ProdiID=='15'){
                    $ProdiID = 7;
                }
                else if($ProdiID=='16'){
                    $ProdiID = 8;
                }
                else if($ProdiID=='17'){
                    $ProdiID = 9;
                }
                else if($ProdiID=='18'){
                    $ProdiID = 10;
                }
                else if($ProdiID=='19'){
                    $ProdiID = 11;
                }

                $dataJadwal[$i]['ProdiID'] = $ProdiID;

                $this->db->insert('db_academic.z_schedule',$dataJadwal[$i]);
//                print_r($dataJadwal[$i]);
//
                if(count($chek)>0) {
                    for($t=0;$t<count($chek);$t++){
                        $this->db->insert('db_academic.z_team_teaching',$chek[$t]);
                    }

                }

            }


        }

        else if($table=='updatenameFoto'){
            $ta = 2016;
            $db_ = 'ta_'.$ta;

            $dataTa = $this->db->select('ID,NPM,Photo')->get($db_.'.students')->result_array();

            for($i=0;$i<count($dataTa);$i++){
                if($dataTa[$i]['Photo']!='' && $dataTa[$i]['Photo']!=null){

                    $ext = explode('.',$dataTa[$i]['Photo']);
                    $in= count($ext) - 1;
                    $exts = $ext[$in];

                    $this->db->set('Photo', $dataTa[$i]['NPM'].'.'.$exts );
                    $this->db->where('ID', $dataTa[$i]['ID']);
                    $this->db->update($db_.'.students');
                }

            }


        }

        else if($table=='updateAttd'){
            $dataSchedule = $this->db->query('SELECT ID,SemesterID,ScheduleID FROM db_academic.attendance')->result_array();
            $db_ = 'ta_2018';

            for($i=0;$i<count($dataSchedule);$i++){
                $ScheduleID = $dataSchedule[$i]['ScheduleID'];
                $SemesterID = $dataSchedule[$i]['SemesterID'];
                $data = $this->db->query('SELECT NPM FROM '.$db_.'.study_planning sp WHERE sp.ScheduleID = "'.$ScheduleID.'" AND sp.SemesterID = "'.$SemesterID.'" ')->result_array();

                // Insert ke attd std
                for($s=0;$s<count($data);$s++){
                    $dataIn = array(
                        'ID_Attd' => $dataSchedule[$i]['ID'],
                        'NPM' => $data[$s]['NPM']
                    );
                    $this->db->insert('db_academic.attendance_students',$dataIn);
                }


            }

        }

        else if($table=='updatePasswd2018'){

            $db_ = 'ta_2018';

            $dataStd = $this->db->select('*')->get($db_.'.students')->result_array();

            for($i=0;$i<count($dataStd);$i++){

                $Name = ucwords(strtolower($dataStd[$i]['Name']));
                $Address = ucwords(strtolower($dataStd[$i]['Address']));

                $up = array(
                    'Name' => $Name,
                    'HighSchool' => strtoupper($dataStd[$i]['HighSchool']),
                    'Address' => $Address,
                    'Email' => strtolower($dataStd[$i]['Email']),
                    'Father' => ucwords(strtolower($dataStd[$i]['Father'])),
                    'Mother' => ucwords(strtolower($dataStd[$i]['Mother'])),
                    'AddressFather' => ucwords(strtolower($dataStd[$i]['AddressFather'])),
                    'AddressMother' => ucwords(strtolower($dataStd[$i]['AddressMother']))
                );

                $this->db->set($up);
                $this->db->where('ID', $dataStd[$i]['ID']);
                $this->db->update($db_.'.students');

                $ex = explode('-',$dataStd[$i]['DateOfBirth']);

                $pass = trim($ex[2]).''.trim($ex[1]).''.trim(substr($ex[0],2,2));

                print_r($pass);

                $this->db->set('Password_Old',md5($pass));
                $this->db->where('NPM', $dataStd[$i]['NPM']);
                $this->db->update('db_academic.auth_students');


            }

//            $data = $this->db->query('SELECT * FROM db_academic.auth_students')->result_array();
        }

        else if($table=='rekap'){
            $db_ = 'ta_2014';
            $dataStd = $this->db->query('SELECT * FROM '.$db_.'.students ORDER BY NPM ASC')->result_array();

            $res = [];
            for($c=0;$c<count($dataStd);$c++){
                $NPM = $dataStd[$c]['NPM'];
                $dataS = $this->db->query('SELECT * FROM '.$db_.'.study_planning 
                                                            WHERE SemesterID = 13 AND NPM = "'.$NPM.'" ')
                    ->result_array();
                $Credit = 0;
                for($s=0;$s<count($dataS);$s++){
                    $Credit = $Credit + $dataS[$s]['Credit'];
                }

                $re = array(
                    'NPM' => $NPM,
                    'Name' => ucwords(strtolower($dataStd[$c]['Name'])),
                    'Credit' => $Credit
                );
                array_push($res,$re);
            }

            echo "<table><tr><th>NPM</th><th>Name</th><th>Credit</th></tr>";

            for($r=0;$r<count($res);$r++){
                echo "<tr><td>".$res[$r]['NPM']."</td><td>".$res[$r]['Name']."</td><td>".$res[$r]['Credit']."</td></tr>";
            }

            echo "</table>";

//            return print_r($res);
        }

        else if($table=='getAllStudent'){
            $db_ = 'ta_2018';
            $dataStd = $this->db->get($db_.'.students')->result_array();
            for($i=0;$i<count($dataStd);$i++){
                $d = $dataStd[$i];
                // Cek apakah sudah ada di AUTH
                $dataAuth = $this->db->get_where('db_academic.auth_students',array('NPM' => $d['NPM']),1)->result_array();
                if(count($dataAuth)>0){
                    // Update
                    $dataUpdate = array(
                        'Name' => ucwords(strtolower($d['Name'])),
                        'ProdiID' => $d['ProdiID']
                    );
                    $this->db->set($dataUpdate);
                    $this->db->where('ID', $dataAuth[0]['ID']);
                    $this->db->update('db_academic.auth_students');

                }
                else {
                    $dataInsert = array(
                        'NPM' => $d['NPM'],
                        'Name' => $d['Name'],
                        'ProdiID' => $d['ProdiID'],
                        'Password_Old' => md5('Podomoro2015'),
                        'Year' => $d['ClassOf'],
                        'StatusStudentID' => $d['StatusStudentID'],
                        'Status' => '-1',
                    );
                    $this->db->insert('db_academic.auth_students',$dataInsert);
                }
            }

        }

        else if($table=='updateStatus'){
            $y = 2016;
            $db_ = 'ta_'.$y;
            $dataStd = $this->db->select('NPM, StatusStudentID')->get($db_.'.students')->result_array();

            for($i=0;$i<count($dataStd);$i++){
                $this->db->set('StatusStudentID', $dataStd[$i]['StatusStudentID']);
                $this->db->where('NPM', $dataStd[$i]['NPM']);
                $this->db->update('db_academic.auth_students');
            }

        }
    }

    public function getClassOf(){
        $data = $this->db->query('SELECT ast.Year FROM db_academic.auth_students ast 
                                                  GROUP BY ast.Year');

        return $data->result_array();
    }

    private function genratePassword($NIP,$Password){

        $plan_password = $NIP.''.$Password;
        $pas = md5($plan_password);
        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

        return $pass;
    }

    public function foto2(){

        $data = $this->db_server->where('TahunMasuk',2016)->select('ID,NPM,Foto')->get('siak4.mahasiswa')->result_array();

//        print_r($data);
//
//        exit;
        $db_ = '';
        for($i=0;$i<count($data);$i++){

            if($data[$i]['Foto']!='' && $data[$i]['Foto']!=null){
                $old = './fotomhs/'.$data[$i]['Foto'];
                $ext = explode('.',$data[$i]['Foto']);
                $newName = $data[$i]['NPM'].'.'.$ext[1];
                $new = './'.$db_.'/'.$newName;

                if(file_exists($old)){
                    rename($old, $new);
                    $this->db->where('NPM',$data[$i]['NPM']);
                    $this->db->update($db_.'.students',array('Photo' => $newName));
                } else {
                    $this->db->where('NPM',$data[$i]['NPM']);
                    $this->db->update($db_.'.students',array('Photo' => null));
                }
            }

        }



    }

    public function foto(){
        $db_ = 'db_employees';
        $data = $this->db->query('SELECT ID,NIP,Photo FROM '.$db_.'.employees ')->result_array();

        for($i=0;$i<count($data);$i++){
            if($data[$i]['Photo']!='' && $data[$i]['Photo']!=null){

                $old = './fotomhs/'.$data[$i]['Photo'];
                $ext = explode('.',$data[$i]['Photo']);
                $newName = $data[$i]['NIP'].'.'.$ext[1];
                $new = './'.$db_.'/'.$newName;

                if(file_exists($old)){
                    rename($old, $new);
                    $this->db->where('ID',$data[$i]['ID']);
                    $this->db->update($db_.'.employees',array('Photo_new' => $newName));
                } else {
                    $this->db->where('ID',$data[$i]['ID']);
                    $this->db->update($db_.'.employees',array('Photo_new' => null));
                }

            }
        }
    }

    private function getBobot($semesterID,$final){

        $tb = ($semesterID<7) ? 'siak4.bobot' : 'siak4.bobot2';
        $data = $this->db->query('SELECT Nilai,Bobot FROM '.$tb.' WHERE MinNilai<= "'.$final.'" AND MaxNilai>= "'.$final.'" LIMIT 1 ');

        return $data->result_array()[0];

    }

    public function migrationStudent(){

        $max_execution_time = 630;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes

        $angkatan = 2014;
        $db_lokal = 'ta_'.$angkatan;

        // students
        $data = $this->db_server->query('SELECT d.NIP AS AcademicMentor,mhs.* FROM siak4.mahasiswa mhs 
                                                    LEFT JOIN siak4.setpembimbing s ON (mhs.ID=s.MhswID)
                                                    LEFT JOIN siak4.pembimbing p ON (s.PembimbingID = p.ID)
                                                    LEFT JOIN siak4.dosen d ON (d.ID = p.PembimbingID)
                                                WHERE mhs.TahunMasuk =  '.$angkatan)->result_array();


        $this->db->truncate($db_lokal.'.students');
        $this->db->where('Year', $angkatan);
        $this->db->delete(array('db_academic.auth_students','db_academic.mentor_academic'));

        for($i=0;$i<count($data);$i++){
            $EmailPU = $data[$i]['Email'];

            $ProdiID = $data[$i]['ProdiID'];

            if($ProdiID=='3') {
                $ProdiID = 1;
            }
            else if($ProdiID=='4'){
                $ProdiID = 2;
            }
            else if($ProdiID=='6'){
                $ProdiID = 3;
            }
            else if($ProdiID=='7'){
                $ProdiID = 4;
            }
            else if($ProdiID=='13'){
                $ProdiID = 5;
            }
            else if($ProdiID=='14'){
                $ProdiID = 6;
            }
            else if($ProdiID=='15'){
                $ProdiID = 7;
            }
            else if($ProdiID=='16'){
                $ProdiID = 8;
            }
            else if($ProdiID=='17'){
                $ProdiID = 9;
            }
            else if($ProdiID=='18'){
                $ProdiID = 10;
            }
            else if($ProdiID=='19'){
                $ProdiID = 11;
            }
            $arr = array(
                'ID' => $data[$i]['ID'],
                'ProdiID' => $ProdiID,
                'ProgramID' => $data[$i]['ProgramID'],
                'LevelStudyID' => $data[$i]['JenjangID'],
                'ReligionID' => $data[$i]['AgamaID'],

                'ProvinceID' => $data[$i]['PropinsiID'],
                'CityID' => $data[$i]['KotaID'],
                'HighSchool' => $data[$i]['NamaSekolah'],
                'MajorsHighSchool' => $data[$i]['JurusanSekolah'],

                'NPM' => $data[$i]['NPM'],
                'Name' => $data[$i]['Nama'],
                'Address' => $data[$i]['Alamat'],
                'Photo' => $data[$i]['Foto'],
                'Gender' => $data[$i]['Kelamin'],
                'PlaceOfBirth' => $data[$i]['TempatLahir'],
                'DateOfBirth' => $data[$i]['TanggalLahir'],
                'Phone' => $data[$i]['Telepon'],
                'HP' => $data[$i]['HP'],
//                    'Email' => '',
                'ClassOf' => $data[$i]['TahunMasuk'],
//                    'EmailPU' => strtolower($EmailPU),
                'Jacket' => $data[$i]['Jacket'],
//                    'AcademicMentor' => $data[$i]['AcademicMentor'],
                'AnakKe' => $data[$i]['AnakKe'],
                'JumlahSaudara' => $data[$i]['JumlahSaudara'],
                'NationExamValue' => $data[$i]['Nilaiunas'],
                'GraduationYear' => $data[$i]['TahunLulus'],
                'IjazahNumber' => $data[$i]['NoIjazah'],

                'Father' => $data[$i]['Ayah'],
                'Mother' => $data[$i]['Ibu'],
                'StatusFather' => $data[$i]['StatusAyah'],
                'StatusMother' => $data[$i]['StatusIbu'],
                'PhoneFather' => str_replace('/','',$data[$i]['PhoneAyah']),
                'PhoneMother' => str_replace('/','',$data[$i]['PhoneIbu']),
                'OccupationFather' => $data[$i]['PekerjaanAyah'],
                'OccupationMother' => $data[$i]['PekerjaanIbu'],
                'EducationFather' => $data[$i]['PDAyah'],
                'EducationMother' => $data[$i]['PDIbu'],
                'AddressFather' => $data[$i]['AlamatAyah'],
                'AddressMother' => $data[$i]['AlamatIbu'],
                'EmailFather' => $data[$i]['EmailAyah'],
                'EmailMother' => $data[$i]['EmailIbu'],
                'StatusStudentID' => $data[$i]['StatusMhswID']

            );

            $this->db->insert($db_lokal.'.students',$arr);

            if($data[$i]['StatusMhswID']=='3' || $data[$i]['StatusMhswID']==3){
                $arrAuth = array(
                    'NPM' => $data[$i]['NPM'],
                    'Password' => $this->genratePassword($data[$i]['NPM'],'123456'),
                    'Year' => $data[$i]['TahunMasuk'],
                    'EmailPU' => strtolower($EmailPU),
                    'StatusStudentID' => $data[$i]['StatusMhswID'],
                    'Status' => '0'
                );

                $this->db->insert('db_academic.auth_students',$arrAuth);
            }
        }


        // study_planning
        $dataMhs = $this->db->query('SELECT m.ID, m.NPM, r.MKID FROM rencanastudi r 
                                                LEFT JOIN mahasiswa m ON (m.ID = r.MhswID) 
                                                WHERE m.TahunMasuk = "'.$angkatan.'" GROUP BY m.NPM 
                                                ORDER BY m.NPM, r.TahunID ASC')->result_array();

        $this->db->truncate($db_lokal.'.study_planning');

        for($m=0;$m<count($dataMhs);$m++){

            $dataRencana = $this->db->query('SELECT r.MKID,r.NilaiAkhir,r.TahunID, 
                                                      r.JadwalID AS ScheduleID,
                                                      r.Evaluasi1,r.Evaluasi2,r.Evaluasi3,r.Evaluasi4,r.Evaluasi5,r.UTS,r.UAS,
                                                      r.NilaiHuruf AS Grade,r.approval AS Approval,
                                                      dt.TotalSKS AS Credit, dt.ID AS CDID
                                                      FROM rencanastudi r 
                                                      LEFT JOIN siak4.matakuliah m ON (m.ID=r.MKID)
                                                      LEFT JOIN siak4.jadwal j ON (r.JadwalID=j.ID)
                                                      LEFT JOIN siak4.detailkurikulum dt ON (dt.MKID = j.MKID)
                                                      WHERE r.MhswID= "'.$dataMhs[$m]['ID'].'" ORDER BY MKID ASC ')->result_array();


            $MKID= '';
            $NilaiAkhir = '';
            for($n=0;$n<count($dataRencana);$n++){
                if($MKID==''){
                    $MKID = $dataRencana[$n]['MKID'];
                    $NilaiAkhir = $dataRencana[$n]['NilaiAkhir'];
                }
                else if($MKID==$dataRencana[$n]['MKID']){
                    $Nilai = $dataRencana[$n]['NilaiAkhir'];
                    if($Nilai>$NilaiAkhir){
                        $NilaiAkhir = $Nilai;
                    }
                } else {
                    $MKID= $dataRencana[$n]['MKID'];
                    $NilaiAkhir = $dataRencana[$n]['NilaiAkhir'];

                    $g = $this->getBobot($dataRencana[$n]['TahunID'],$NilaiAkhir);

                    $arrp = array(

                        'SemesterID' => $dataRencana[$n]['TahunID'],
                        'MhswID' => $dataMhs[$m]['ID'],
                        'NPM' => $dataMhs[$m]['NPM'],
                        'ScheduleID' => $dataRencana[$n]['ScheduleID'],
                        'CDID' => $dataRencana[$n]['CDID'],
                        'MKID' => $MKID,
                        'Credit' => $dataRencana[$n]['Credit'],

                        'Evaluasi1' => $dataRencana[$n]['Evaluasi1'],
                        'Evaluasi2' => $dataRencana[$n]['Evaluasi2'],
                        'Evaluasi3' => $dataRencana[$n]['Evaluasi3'],
                        'Evaluasi4' => $dataRencana[$n]['Evaluasi4'],
                        'Evaluasi5' => $dataRencana[$n]['Evaluasi5'],
                        'UTS' => $dataRencana[$n]['UTS'],
                        'UAS' => $dataRencana[$n]['UAS'],
                        'Score' => $NilaiAkhir,
                        'Grade' => $g['Nilai'],
                        'GradeValue' => $g['Bobot'],
                        'Approval' => ''.$dataRencana[$n]['Approval'],
                        'StatusSystem' => '0',
                        'Status' => '1'

                    );
                    $this->db->insert($db_lokal.'.study_planning',$arrp);
                }
            }
        }


    }


}
