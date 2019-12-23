
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
//        $this->db_server = $this->load->database('server', TRUE);
        $this->db = $this->load->database('default', TRUE);
    }

    public function checkSKS($SemesterID,$StatusEmployeeID,$StatusLecturerID){

        $dataLecturer = $this->db->query('SELECT NIP, Name FROM db_employees.employees em
                                                  WHERE em.StatusEmployeeID = "'.$StatusEmployeeID.'" AND em.StatusLecturerID = "'.$StatusLecturerID.'"
                                                  ORDER BY em.NIP ASC ')->result_array();


        if(count($dataLecturer)>0){
            for($i=0;$i<count($dataLecturer);$i++){
                $d = $dataLecturer[$i];

                $dataAllCourse = [];

                $dataSch = $this->db->query('SELECT s.ID AS ScheduleID, s.ClassGroup, mk.MKCode, mk.Name, mk.NameEng, cd.TotalSKS AS CreditMK FROM db_academic.schedule s 
                                                        LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                                        LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                        WHERE s.SemesterID = "'.$SemesterID.'" 
                                                        AND s.Coordinator = "'.$d['NIP'].'" 
                                                        GROUP BY s.ID')->result_array();

                if(count($dataSch)>0){
                    for($a=0;$a<count($dataSch);$a++){
                        $dataTeam = $this->db->query('SELECT em.NIP, em.Name, "0" AS IsCoordinator FROM db_academic.schedule_team_teaching stt 
                                                                LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                                WHERE stt.ScheduleID = "'.$dataSch[$a]['ScheduleID'].'" ')->result_array();

                        $Single = 1;
                        if(count($dataTeam)>0){
                            $Single = 0;
                        }
                        $dataSch[$a]['Single'] = $Single;
                        $dataSch[$a]['DetailTeam'] = $dataTeam;

                        array_push($dataAllCourse, $dataSch[$a]);
                    }
                }

                $dataSchTeam = $this->db->query('SELECT s.ID AS ScheduleID, s.ClassGroup, mk.MKCode, mk.Name, mk.NameEng, cd.TotalSKS AS CreditMK,  
                                                        em.NIP AS CoordinatorNIP, em.Name AS CoordinatorName, 0 AS Single
                                                        FROM db_academic.schedule s
                                                        LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                                        LEFT JOIN db_academic.schedule_team_teaching stt ON (stt.ScheduleID = s.ID)
                                                        LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                        LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                        WHERE s.SemesterID = "'.$SemesterID.'" 
                                                        AND stt.NIP = "'.$d['NIP'].'" 
                                                        GROUP BY s.ID')->result_array();


                if(count($dataSchTeam)>0){
                    for($a=0;$a<count($dataSchTeam);$a++){
                        $dataTeamTeaching = $this->db->query('SELECT stt.NIP, em.Name, "0" AS IsCoordinator FROM db_academic.schedule_team_teaching stt 
                                                                        LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                                        WHERE stt.ScheduleID = "'.$dataSchTeam[$a]['ScheduleID'].'"
                                                                        AND stt.NIP != "'.$d['NIP'].'" ' )->result_array();

                        array_push($dataTeamTeaching,array(
                            'NIP' => $dataSchTeam[$a]['CoordinatorNIP'],
                            'Name' => $dataSchTeam[$a]['CoordinatorName'],
                            'IsCoordinator' => '1',
                        ));


                        $arrPush = array(
                            'ScheduleID' => $dataSchTeam[$a]['ScheduleID'],
                            'ClassGroup' => $dataSchTeam[$a]['ClassGroup'],
                            'MKCode' => $dataSchTeam[$a]['MKCode'],
                            'Name' => $dataSchTeam[$a]['Name'],
                            'NameEng' => $dataSchTeam[$a]['NameEng'],
                            'CreditMK' => $dataSchTeam[$a]['CreditMK'],
                            'Single' => '0',
                            'DetailTeam' => $dataTeamTeaching
                        );
                        array_push($dataAllCourse, $arrPush);
                    }
                }




                // ==============================
                // Mengambil data jadwal

                if(count($dataAllCourse)>0){
                    for($a=0;$a<count($dataAllCourse);$a++){

                        $TotalTeam = count($dataAllCourse[$a]['DetailTeam']) + 1;
                        $CreditBKD = ($dataAllCourse[$a]['Single']=='0') ? (integer) $dataAllCourse[$a]['CreditMK'] / $TotalTeam : $dataAllCourse[$a]['CreditMK'];


                        $dataAllCourse[$a]['CreditBKD'] = (is_int($CreditBKD)) ? $CreditBKD : round($CreditBKD,2);

                        $dataAllCourse[$a]['Schedule'] = $this->db->query('SELECT sd.Credit, sd.DayID, cl.Room, sd.StartSessions, sd.EndSessions, d.NameEng AS DayNameEng  
                                                                                    FROM db_academic.schedule_details sd
                                                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                                                                    LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                                                                    WHERE sd.ScheduleID = "'.$dataAllCourse[$a]['ScheduleID'].'" ')->result_array();



                    }
                }


                // ==============================

                $dataLecturer[$i]['Course'] = $dataAllCourse;
            }

        }

//        print_r($dataLecturer);
//
//        exit;
        $data['dataLecturer'] = $dataLecturer;

        $data['SemesterID'] = $SemesterID;
        $data['StatusEmployeeID'] = $StatusEmployeeID;
        $data['StatusLecturerID'] = $StatusLecturerID;

        $this->load->view('dashboard/check_sks',$data,false);



    }

    public function dataSinta(){

        $api_key = 'a39e735fd5049ba1f7ff0b4e05c9f207';
        $NIDN = '0025106201';
        $limit = '&offset=0&limit=10';

        $str = 'GET : http://sinta2.ristekdikti.go.id/api/author?api_key='.$api_key.'&nidn='.$NIDN.'
GET : http://sinta2.ristekdikti.go.id/api/gsdocs?api_key='.$api_key.'&nidn='.$NIDN.''.$limit.'
GET : http://sinta2.ristekdikti.go.id/api/scopusdocs?api_key='.$api_key.'&nidn='.$NIDN.''.$limit.'
GET : http://sinta2.ristekdikti.go.id/api/authors?api_key='.$api_key.'&afiliasi_id=384'.$limit.'
GET : http://sinta2.ristekdikti.go.id/api/authorbooks?api_key='.$api_key.'&id='.$NIDN.''.$limit.'
GET : http://sinta2.ristekdikti.go.id/api/authoriprs?api_key='.$api_key.'&id='.$NIDN.''.$limit.'
GET : http://sinta2.ristekdikti.go.id/api/affiliation?api_key='.$api_key.'&kode=002001
GET : http://sinta2.ristekdikti.go.id/api/countauthors?api_key='.$api_key.'&kode_pt=001002&verified=1
GET : http://sinta2.ristekdikti.go.id/api/countcitations?api_key='.$api_key.'&kode_pt=001002';

        $str_arr = explode('GET : ',$str);
        $listApi = [];
        if(count($str_arr)>0){
            for($i=0;$i<count($str_arr);$i++){
                if($str_arr[$i]!=''){
                    array_push($listApi,$str_arr[$i]);
                }
            }
        }

        $data['listApi'] = $listApi;
        $this->load->view('template/sementara',$data);

//        print_r($listApi);

    }

    public function cekFile()
    {
        $data = $this->db->query('SELECT f.NIP, em.Name, f.ID AS FID ,f.NameUniversity, u.ID FROM db_employees.files f 
                                            LEFT JOIN db_research.university u ON (f.NameUniversity = u.Code_University)
                                            LEFT JOIN db_employees.employees em ON (em.NIP = f.NIP)
                                            WHERE f.NameUniversity IS NOT NULL
                                            ORDER BY f.NameUniversity')->result_array();

        // print_r($data);
        // exit;

        $result = [];
        for($i=0;$i<count($data);$i++){
            $d = $data[$i];

            if(!is_numeric($d['NameUniversity'])){


                array_push($result, $data[$i]);
            }
        }
        print_r($result);
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

        if($table=='parent'){
            $ta = 2014;
            $data = $this->db->query('SELECT * FROM '.$ta.'.students')->result_array();

            foreach ($data AS $item){

                if($item['DateOfBirth']!=null && $item['DateOfBirth']!=''){

                    $dataInsert = array(
                        'NPM' => $item['NPM'],
                        'ProgramID' => $item['ProgramID'],
                        'ProdiID' => $item['ProdiID'],
                        'ProdiGroupID' => '',
                        'Year' => $item['ClassOf'],
                        'Password_Old' => md5(date('dmy',strtotime($item['DateOfBirth']))),
                        'FatherName' => ucwords($item['Father']),
                        'MotherName' => ucwords($item['Mother']),
                        'Status' => '-1',
                    );

                    $this->db->insert('db_academic.auth_parents', $dataInsert);

                }
            }

        }
        else if($table=='test'){
            echo date('dmy',strtotime('2001-09-30'));
        }


    }

    public function parent($ta){
        $max_execution_time = 360;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes

        $data = $this->db->query('SELECT std.*, ats.ProdiGroupID FROM ta_'.$ta.'.students std LEFT JOIN db_academic.auth_students ats ON (ats.NPM = std.NPM)')->result_array();

        foreach ($data AS $item){

            if($item['DateOfBirth']!=null && $item['DateOfBirth']!=''){

                $dataInsert = array(
                    'NPM' => $item['NPM'],
                    'ProgramID' => $item['ProgramID'],
                    'ProdiID' => $item['ProdiID'],
                    'ProdiGroupID' => $item['ProdiGroupID'],
                    'Year' => $item['ClassOf'],
                    'Password_Old' => md5(date('dmy',strtotime($item['DateOfBirth']))),
                    'FatherName' => ucwords(strtolower($item['Father'])),
                    'MotherName' => ucwords(strtolower($item['Mother'])),
                    'Status' => '-1',
                );

                $this->db->insert('db_academic.auth_parents', $dataInsert);

            }
        }

    }

    public function getReportEdom($SemesterID,$ClassOf,$ProdiID){

        $max_execution_time = 3600;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes

//        $SemesterID = 14;
//        $ClassOf = 2014;

        // AS COORDINATOR
        // Dapeting jadwalnya dulu
        $data = $this->db->query('SELECT s.ID, s.ClassGroup, em.NIP, em.Name AS Lecturer, mk.Name AS Course, mk.NameEng AS CourseEng, 
                                            ps.Name AS ProdiName, mk.MKCode
                                            FROM db_academic.schedule s
                                            LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                            LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                            LEFT JOIN db_academic.program_study ps ON (ps.ID = sdc.ProdiID)
                                            LEFT JOIN db_employees.employees em ON (s.Coordinator = em.NIP)
                                            WHERE s.SemesterID = "'.$SemesterID.'" AND sdc.ProdiID = "'.$ProdiID.'"
                                            GROUP BY s.ID 
                                            UNION
                                            SELECT s1.ID, s1.ClassGroup, em1.NIP, em1.Name AS Lecturer, mk1.Name AS Course, mk1.NameEng AS CourseEng,
                                            ps1.Name AS ProdiName, mk1.MKCode
                                            FROM db_academic.schedule_team_teaching stt1
                                            LEFT JOIN db_academic.schedule s1 ON (s1.ID = stt1.ScheduleID)
                                            LEFT JOIN db_academic.schedule_details_course sdc1 ON (sdc1.ScheduleID = s1.ID)
                                            LEFT JOIN db_academic.mata_kuliah mk1 ON (mk1.ID = sdc1.MKID)
                                            LEFT JOIN db_academic.program_study ps1 ON (ps1.ID = sdc1.ProdiID)
                                            LEFT JOIN db_employees.employees em1 ON (stt1.NIP = em1.NIP)
                                            WHERE s1.SemesterID = "'.$SemesterID.'" AND sdc1.ProdiID = "'.$ProdiID.'"
                                            GROUP BY s1.ID
                                            ')->result_array();

//         AS TEAMTEACHING

//        $dataTeam = $this->db->query('SELECT s.ID, s.ClassGroup, s.SemesterID, em.NIP, em.Name AS Lecturer, mk.Name AS Course, mk.NameEng AS CourseEng,
//                                            ps.Name AS ProdiName, mk.MKCode
//                                            FROM db_academic.schedule_team_teaching stt
//                                            LEFT JOIN db_academic.schedule s ON (s.ID = stt.ScheduleID)
//                                            LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
//                                            LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
//                                            LEFT JOIN db_academic.program_study ps ON (ps.ID = sdc.ProdiID)
//                                            LEFT JOIN db_employees.employees em ON (stt.NIP = em.NIP)
//                                            WHERE s.SemesterID = "'.$SemesterID.'"
//                                            GROUP BY s.ID ORDER BY s.ID ASC')->result_array();
//
//        for($i=0;$i<count($dataTeam);$i++){
//            array_push($data,$dataTeam[$i]);
//        }

//        print_r($data);
//        exit;



        $result = [];
        for ($e=0;$e<count($data);$e++){
            $d = $data[$e];

            // Multiple
            $dataQuestion = $this->db->query('SELECT * FROM db_academic.edom_question WHERE ID < 12 ')->result_array();

//            print_r($dataQuestion);

            for($q=0;$q<count($dataQuestion);$q++){
                $dataEdom = $this->db->query('SELECT ea.NPM, ead.Rate, ead.Essay FROM db_academic.edom_answer ea
                                                    LEFT JOIN db_academic.edom_answer_details ead ON (ead.EAID = ea.ID)
                                                    LEFT JOIN db_academic.auth_students auts ON (auts.NPM = ea.NPM)
                                                    WHERE ea.SemesterID = "'.$SemesterID.'"
                                                    AND ea.ScheduleID = "'.$d['ID'].'"
                                                    AND ea.NIP = "'.$d['NIP'].'"
                                                    AND ea.Type = "1"
                                                    AND ead.QuestionID = "'.$dataQuestion[$q]['ID'].'"
                                                    AND auts.Year = "'.$ClassOf.'"
                                                    ORDER BY ea.NPM')->result_array();


                if(count($dataEdom)>0){
                    $totalRate = 0;
                    foreach ($dataEdom as $itemEd){
                        $totalRate = $totalRate + $itemEd['Rate'];
                    }

                    $Rate = $totalRate / count($dataEdom);

                    $data[$e]['Question'] = $dataQuestion[$q]['Question'];
                    $data[$e]['TotalStudent'] = count($dataEdom);
                    $data[$e]['Rate'] = round($Rate,2);
                    array_push($result,$data[$e]);
                }



            }


        }


        $no = 1;
        echo "<table border='0.5'><thead>
                        <tr>
                            <th>No</th>
                            <th>Code</th>
                            <th>Course</th>
                            <th>Group</th>
                            <th>Programme Study</th>
                            <th>Lecturer</th>
                            <th>NIP</th>
                            <th>Question</th>
                            <th>TotalStudent</th>
                            <th>Rate</th>
                        </tr>
                        </thead>
                        <tbody>";

        foreach ($result AS $item){
            echo '<tr>
                    <td>'.($no++).'</td>
                    <td>'.$item['MKCode'].'</td>
                    <td>'.$item['Course'].'</td>
                    <td>'.$item['ClassGroup'].'</td>
                    <td>'.$item['ProdiName'].'</td>
                    <td>'.$item['Lecturer'].'</td>
                    <td>'.$item['NIP'].'</td>
                    <td>'.$item['Question'].'</td>
                    <td>'.$item['TotalStudent'].'</td>
                    <td>'.$item['Rate'].'</td>
                 </tr>';
        }

        echo "</tbody>
                        </table>";



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


    public function toStdPlanning($ta,$NIP){

        print_r('Exited');
        exit;

        $max_execution_time = 360;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes

        $db = 'ta_'.$ta;

        $dataSP = $this->db->order_by('SemesterID ASC, NPM ASC')->get_where($db.'.study_planning',array('SemesterID' => 15))->result_array();

        if(count($dataSP)>0){
            $this->db->where(array(
                'ClassOf' => $ta,
                'SemesterID' => 15

            ));
            $this->db->delete('db_academic.std_study_planning');
            $this->db->reset_query();

            foreach ($dataSP AS $item){
                $arr = array(
                    'SPID' => $item['ID'],
                    'ClassOf' => $ta,
                    'SemesterID' => $item['SemesterID'],
                    'NPM' => $item['NPM'],
                    'ScheduleID' => $item['ScheduleID'],
                    'TypeSchedule' => $item['TypeSchedule'],
                    'CDID' => $item['CDID'],
                    'MKID' => $item['MKID'],
                    'Credit' => $item['Credit'],
                    'EntredBy' => $NIP
                );

                $this->db->insert('db_academic.std_study_planning',$arr);
            }
        }


    }


}
