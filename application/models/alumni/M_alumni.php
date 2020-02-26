<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_alumni extends CI_Model {
    private $callback = ['status' => 0,'msg' => '','callback' => array() ];
    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('m_rest_global');
        $this->load->library('JWT');
    }

    public function registration($action,$data){
        $tbl = 'db_alumni.registration';
        switch ($action) {
            case 'delete':
                $this->db->db_debug=false;
                $this->db->where('NPM',$data['NPM']);
                $query = $this->db->delete($tbl);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                break;
            case 'create':
                $this->db->db_debug=false;
                $dataSave = [
                    'NPM' => $data['NPM'],
                    'UpdateAt' => date('Y-m-d H:i:s'),
                    'UpdateBy' => $data['UpdateBy'],
                ];
                $query = $this->db->insert($tbl,$dataSave);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                break;
            default:
                # code...
                break;
        }

        $this->db->db_debug=true;
        return $this->callback;
    }

    public function authLogin($data){
        $NPM = $data['NPM'];
        $Password = $data['Password'];
        $dataMHS = $this->db->query('select ID,NPM,Year from db_academic.auth_students where NPM = "'.$NPM.'" and Password = "'.$Password.'" ')->result_array();
        $dataAlumni = $this->db->query('select count(*) as total from db_alumni.registration where NPM = "'.$NPM.'"  ')->result_array();
        if (count($dataMHS) > 0 && $dataAlumni[0]['total'] > 0 ) {
             $this->callback = $this->setUserSession($dataMHS[0]);
        }

        return $this->callback;
    }

    private function setUserSession($dataAuth){

        $table = 'ta_'.$dataAuth['Year'].'.students';
        $dataUser = $this->getDataUser($dataAuth['Year'],$table,$dataAuth['NPM']);

        $newdata = array(
            'alumni_NPM'  => $dataUser[0]['NPM'],
            'alumni_Name'  => $dataUser[0]['Name'],
            'alumni_Gender'  => $dataUser[0]['Gender'],
            'alumni_HP' => $dataUser[0]['HP'],
            'alumni_Email' => $dataUser[0]['Email'],
            'alumni_DateOfBirth' => $dataUser[0]['DateOfBirth'],
            'alumni_PlaceOfBirth' => $dataUser[0]['PlaceOfBirth'],
            'alumni_EmailPU'  => $dataUser[0]['EmailPU'],
            'alumni_Faculty'  => $dataUser[0]['Faculty'],
            'alumni_ProdiID'  => $dataUser[0]['ProdiID'],
            'alumni_ProdiGroupID'  => $dataUser[0]['ProdiGroupID'],
            'alumni_ProdiGroup'  => $dataUser[0]['ProdiGroup'],
            'alumni_ProdiCode'  => $dataUser[0]['ProdiCode'],
            'alumni_CurriculumID'  => $dataUser[0]['CurriculumID'],
            'alumni_ProgramCampusID'  => $dataUser[0]['ProgramID'],
            'alumni_SemesterID'  => $dataUser[0]['SemesterID'],
            'alumni_Semester'  => $dataUser[0]['SemesterNow'],
            'alumni_Year'  => $dataUser[0]['Year'],
            'alumni_SemesterCode'  => $dataUser[0]['SemesterCode'],
            'alumni_Photo'  => $dataUser[0]['Photo'],
            'alumni_StatusStudentID'  => $dataUser[0]['StatusStudentID'],
            'alumni_ClassOf'  => $dataAuth['Year'],
            'alumni_AcademicMentor'  => $dataUser[0]['AcademicMentor'],
            'alumni_MentorName'  => $dataUser[0]['MentorName'],
            'alumni_MentorEmailPU'  => $dataUser[0]['MentorEmailPU'],
            'alumni_Token'  => $dataUser[0]['Token'],
            'alumni_DB'  => 'ta_'.$dataAuth['Year'],
            'alumni_loggedIn' => TRUE
        );

        return $newdata;
    }

    private function getDataUser($year,$table,$NPM) {
        $data = $this->db->query('SELECT s.*, ast.Password AS Token, ast.EmailPU, ast.ProdiGroupID, pg.Code AS ProdiGroup, ma.NIP AS AcademicMentor, 
                                    e.Name AS MentorName, e.EmailPU AS MentorEmailPU, 
                                    ps.Code AS ProdiCode, fc.Name AS Faculty FROM 
                                    '.$table.' s
                                    LEFT JOIN db_academic.auth_students ast ON (s.NPM = ast.NPM)
                                    LEFT JOIN db_academic.prodi_group pg ON (pg.ID = ast.ProdiGroupID)
                                    LEFT JOIN db_academic.mentor_academic ma ON (ma.NPM = s.NPM AND ma.Status="1")
                                    LEFT JOIN db_employees.employees e ON (e.NIP = ma.NIP)
                                    LEFT JOIN db_academic.program_study ps ON (ps.ID = s.ProdiID)
                                    LEFT JOIN db_academic.faculty fc ON (fc.ID = ps.FacultyID)
                                    WHERE s.NPM = "'.$NPM.'"')->result_array();

        $dataSemester = $this->db->query('SELECT ID FROM db_academic.curriculum 
                                              WHERE Year = "'.$year.'" LIMIT 1')->result_array()[0];

        $dataTotalSmt = $this->db->query('SELECT s.* FROM db_academic.semester s 
                                                    WHERE s.ID >= (SELECT ID FROM db_academic.semester s2 
                                                    WHERE s2.Year="'.$year.'" 
                                                    LIMIT 1)')->result_array();

        $smt = 0;
        $Year='';
        $SemesterCode='';
        $SemesterID = 0;
        $bool = true;
        for($s=0;$s<count($dataTotalSmt);$s++){
            
                $smt += 1;
                $Year = $dataTotalSmt[$s]['Name'];
                $SemesterCode=$dataTotalSmt[$s]['Code'];
                $SemesterID = $dataTotalSmt[$s]['ID'];

            if($dataTotalSmt[$s]['Status']=='1'){
                $bool = false;
                
               
                break;
            } 
        }

        if ($bool) {
            $s = 0;
            $smt = 1;
            $Year = $dataTotalSmt[$s]['Name'];
            $SemesterCode=$dataTotalSmt[$s]['Code'];
            $SemesterID = $dataTotalSmt[$s]['ID'];
        }

        $data[0]['CurriculumID'] = $dataSemester['ID'];
        $data[0]['SemesterNow'] = $smt;
        $data[0]['SemesterID'] = $SemesterID;
        $data[0]['Year'] = $Year;
        $data[0]['SemesterCode'] = $SemesterCode;

        return $data;
    }

    public function change_photo($dataToken){
        if (array_key_exists('Photo', $_FILES)) {
            $data = $dataToken['data'];
            $NPM = $data['NPM'];
            $filename = $_FILES['Photo']['name'][0];
            $varFiles = 'Photo';
            $path = './uploads/document/'.$NPM;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                copy("./uploads/index.html",$path.'/index.html');
                copy("./uploads/index.php",$path.'/index.php');
            }

            if (file_exists($path.'/'.$filename)) {
                unlink($path.'/'.$filename);
            }
            $proc = $this->m_master->uploadDokumenSetFileName($filename,$varFiles,$path);
            if (!empty($proc)) {

                $filenameDB = $proc[0];
                $G_dt = $this->m_master->caribasedprimary('db_alumni.biodata','NPM',$NPM);
                if (count($G_dt) > 0) {
                    $this->db->db_debug=false;
                    $this->db->where('NPM',$NPM);
                    $query = $this->db->update('db_alumni.biodata',[
                        'Photo' => $filenameDB,
                        ]+$data
                    );

                    if( !$query )
                    {
                       $this->callback['msg'] = json_encode($this->db->error());
                    }
                    else
                    {
                     $this->callback['status'] = 1; 
                    }
                }
                else
                {
                    $query = $this->db->insert('db_alumni.biodata',[
                        'Photo' => $filenameDB,
                        ]+$data
                    );

                    if( !$query )
                    {
                       $this->callback['msg'] = json_encode($this->db->error());
                    }
                    else
                    {
                     $this->callback['status'] = 1; 
                    }

                }
            }
            
        }
        $this->db->db_debug=true;
        return $this->callback;
    }

    public function save_biodata($dataToken){
        $tbl = 'db_alumni.biodata';
        $action = $dataToken['action'];
        switch ($action) {
            case 'add':
                $data = $dataToken['data'];
                $this->db->db_debug=false;
                $query = $this->db->insert($tbl,$data);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                break;
            case 'edit':
                $data = $dataToken['data'];
                $NPM = $dataToken['NPM'];
                $this->db->db_debug=false;
                $this->db->where('NPM',$NPM);
                $query = $this->db->update($tbl,$data);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                break;
            default:
                # code...
                break;
        }

        $this->db->db_debug=true;
        return $this->callback;
    }

    public function upd_tbl_ta($dataToken){
        $tbl = $dataToken['tbl'];
        $data = $dataToken['data'];
        $NPM = $dataToken['NPM'];
        $this->db->db_debug=false;
        $this->db->where('NPM',$NPM);
        $query = $this->db->update($tbl,$data);
        if( !$query )
        {
           $this->callback['msg'] = json_encode($this->db->error());
        }
        else
        {
         $this->callback['status'] = 1; 
        }

        $this->db->db_debug=true;
        return $this->callback;
        
    }

    public function load_data_education($dataToken){
        $data = $dataToken['data'];
        $NPM = $data['NPM'];
        $sql = 'select a.ID,a.NPM,a.ID_university,un.Name_University,jud.GraduationDate as Jud_GraduationDate,a.edu_code,ed.edu_name,a.ctr_code,ctr.ctr_name,
            a.ID_major_programstudy_employees,mjr.Name_MajorProgramstudy, a.IPK, a.Description,auts.GraduationDate as GraduationDate
            from db_alumni.education as a
            join db_research.university as un on a.ID_university = un.ID
            join db_academic.judiciums_list as judlst on judlst.NPM = a.NPM
            join db_academic.judiciums as jud on jud.ID = judlst.JID
            join db_admission.education as ed on ed.edu_code = a.edu_code
            join db_admission.country as ctr on ctr.ctr_code = a.ctr_code
            join db_employees.major_programstudy_employees as mjr on mjr.ID = a.ID_major_programstudy_employees
            join db_academic.auth_students as auts on auts.NPM = a.NPM
            where a.NPM = "'.$NPM.'"
            group by a.ID
            order by ed.edu_sort asc
            ';
        $query = $this->db->query(
            $sql
        )->result_array();

        if (count($query) == 0) {
            // insert lulusan PU
                // get IPK
                $dataMHS = $this->m_rest_global->api_Biodata_MHS($NPM);


            $dataSave = [
                'NPM' => $NPM,
                'ID_university' => 52, // podomoro unversity
                'Date_graduation' => $this->__Date_graduation($NPM),
                'edu_code' =>  $this->__education_lulusan($NPM),
                'ctr_code' => '001', // indonesia
                'ID_major_programstudy_employees' => $this->__major_programstudy_employees($NPM),
                'IPK' => number_format($dataMHS[0]->IPK_data['dataIPK']['IPK'], 2),
            ];

            // print_r($dataSave);die();

            $this->db->insert('db_alumni.education',$dataSave);

            $query = $this->db->query(
                $sql
            )->result_array();
        }

        $this->callback['status'] = 1; 
        $this->callback['callback'] = $query;
        return  $this->callback;
    }

    private function __major_programstudy_employees($NPM){
        $query = $this->db->query(
            'select mps.ID from db_academic.auth_students as auts
             join db_academic.program_study as pst on auts.ProdiID = pst.ID
             join db_employees.major_programstudy_employees as mps on mps.LinkIDProgramStudy = pst.ID
             where auts.NPM = "'.$NPM.'"
             '
        )->result_array();

        return $query[0]['ID'];
    }

    private function __education_lulusan($NPM){
        $query = $this->db->query(
            'select ed.edu_code from db_academic.auth_students as auts
             join db_academic.program_study as pst on auts.ProdiID = pst.ID
             join db_academic.education_level as edlev on edlev.ID = pst.EducationLevelID
             join db_admission.education as ed on ed.edu_name = edlev.Name
             where auts.NPM = "'.$NPM.'"
            '
        )->result_array();

        return $query[0]['edu_code'];
    }

    private function __Date_graduation($NPM){
        $query = $this->db->query(
            'select b.GraduationDate from db_academic.judiciums_list as a 
             join db_academic.judiciums as b on a.JID = b.ID
             where a.NPM = "'.$NPM.'"   
             '
        )->result_array();

        return $query[0]['GraduationDate'];
    }

    public function submit_education($dataToken){
        $tbl = 'db_alumni.education';
        $action = $dataToken['action'];
        switch ($action) {
            case 'add':
                $data = $dataToken['data'];
                $this->db->db_debug=false;
                $query = $this->db->insert($tbl,$data);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                $this->db->db_debug=true;
                break;
            case 'edit':
                $data = $dataToken['data'];
                $ID = $dataToken['ID'];
                $this->db->db_debug=false;
                $this->db->where('ID',$ID);
                $query = $this->db->update($tbl,$data);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                $this->db->db_debug=true;
                break;
            case 'delete':
                $ID = $dataToken['ID'];
                $this->db->db_debug=false;
                $this->db->where('ID',$ID);
                $query = $this->db->delete($tbl);
                if( !$query )
                {
                   $this->callback['msg'] = json_encode($this->db->error());
                }
                else
                {
                 $this->callback['status'] = 1; 
                }
                $this->db->db_debug=true;
                break;
            default:
                # code...
                break;
        }

        return $this->callback;
    }

    public function load_data_skills($dataToken){
        $data = $dataToken['data'];
        $NPM = $data['NPM'];
        $rs = [];
        $Level = ['Tingkat Lanjut','Menengah','Pemula'];
        for ($i=0; $i < count($Level); $i++) { 
            $sql = 'select * from db_alumni.skill where NPM = "'.$NPM.'" and Level = "'.$Level[$i].'"
                        ';
                    $query = $this->db->query(
                        $sql
                    )->result_array();
            $rs[] = [
                'level' => $Level[$i],
                'data' => $query,
            ];
        }

        $this->callback['status'] = 1; 
        $this->callback['callback'] = $rs;
        return  $this->callback;
    }

    public function submit_skills($dataToken){
        $data = $dataToken['data'];
        $tbl = 'db_alumni.skill';
        if (count($data) > 0) {
            // delete first
            $NPM = $data[0]['NPM'];
            $this->db->where('NPM',$NPM);
            $this->db->delete($tbl);

            // insert batch
            $this->db->insert_batch($tbl, $data);

            $this->callback['status'] = 1; 
        }

        return $this->callback;
    }
  
}
