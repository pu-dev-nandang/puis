<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_podivers extends CI_Model {
    private $callback = ['status' => 0,'msg' => '','callback' => array() ];
    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('m_rest_global');
        $this->load->library('JWT');
    }

    public function registration($action,$data){
        $tbl = 'db_podivers.set_list_member';
        switch ($action) {
            case 'delete':
                $this->db->db_debug=false;
                $this->db->where('NIPNPM',$data['NIPNPM']);
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
                    'NIPNPM' => $data['NIPNPM'],
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
        $dataPodivers = $this->db->query('select count(*) as total from db_podivers.set_list_member where NIPNPM = "'.$NPM.'"  ')->result_array();
        if (count($dataMHS) > 0 && $dataPodivers[0]['total'] > 0 ) {
             $this->callback = $this->setUserSession($dataMHS[0]);
        }
        return $this->callback;
    }

    private function setUserSession($dataAuth){

        $table = 'ta_'.$dataAuth['Year'].'.students';
        $dataUser = $this->getDataUser($dataAuth['Year'],$table,$dataAuth['NPM']);

        $newdata = array(
            'podivers_NPM'  => $dataUser[0]['NPM'],
            'podivers_Name'  => $dataUser[0]['Name'],
            'podivers_Gender'  => $dataUser[0]['Gender'],
            'podivers_HP' => $dataUser[0]['HP'],
            'podivers_Email' => $dataUser[0]['Email'],
            'podivers_DateOfBirth' => $dataUser[0]['DateOfBirth'],
            'podivers_PlaceOfBirth' => $dataUser[0]['PlaceOfBirth'],
            'podivers_EmailPU'  => $dataUser[0]['EmailPU'],
            'podivers_Faculty'  => $dataUser[0]['Faculty'],
            'podivers_ProdiID'  => $dataUser[0]['ProdiID'],
            'podivers_ProdiGroupID'  => $dataUser[0]['ProdiGroupID'],
            'podivers_ProdiGroup'  => $dataUser[0]['ProdiGroup'],
            'podivers_ProdiCode'  => $dataUser[0]['ProdiCode'],
            'podivers_CurriculumID'  => $dataUser[0]['CurriculumID'],
            'podivers_ProgramCampusID'  => $dataUser[0]['ProgramID'],
            'podivers_SemesterID'  => $dataUser[0]['SemesterID'],
            'podivers_Semester'  => $dataUser[0]['SemesterNow'],
            'podivers_Year'  => $dataUser[0]['Year'],
            'podivers_SemesterCode'  => $dataUser[0]['SemesterCode'],
            'podivers_Photo'  => 'https://pcam.podomorouniversity.ac.id/'.'uploads/students/'.$dataUser[0]['Photo'],
            'podivers_StatusStudentID'  => $dataUser[0]['StatusStudentID'],
            'podivers_ClassOf'  => $dataAuth['Year'],
            'podivers_AcademicMentor'  => $dataUser[0]['AcademicMentor'],
            'podivers_MentorName'  => $dataUser[0]['MentorName'],
            'podivers_MentorEmailPU'  => $dataUser[0]['MentorEmailPU'],
            'podivers_Token'  => $dataUser[0]['Token'],
            'podivers_DB'  => 'ta_'.$dataAuth['Year'],
            'podivers_loggedIn' => TRUE
        );
        // print_r($newdata);die();
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

}