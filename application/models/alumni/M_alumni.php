<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_alumni extends CI_Model {
    private $callback = ['status' => 0,'msg' => '','callback' => array() ];
    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
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

    public function about_me($dataToken){
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
  
}
