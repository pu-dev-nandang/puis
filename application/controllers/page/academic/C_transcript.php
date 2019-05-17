<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_transcript extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->load->model('akademik/m_tahun_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_transcript($page){
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$data['department'].'/transcript/menu_transcript',$data,true);
        parent::template($content);
    }

    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/transcript/transcript_list_student',$data,true);
        $this->menu_transcript($page);
    }

    public function setting_transcript(){
        $data['TempTranscript'] = $this->db->get('db_academic.setting_temp_transcript')->result_array()[0];
        $data['Transcript'] = $this->db->get('db_academic.setting_transcript')->result_array()[0];
        $data['Graduation'] = $this->db->get('db_academic.graduation')->result_array();
        $data['Education'] = $this->db->get('db_academic.education_level')->result_array();

        $data['ProgramStudy'] = $this->db->query('SELECT a.ID, a.Name, a.NameEng, a.NoSKBANPT, a.SKBANPTDate, b.Name AS NameLevel, c.Name AS NameFak
            FROM db_academic.program_study AS a
            LEFT JOIN db_academic.education_level AS b ON (a.EducationLevelID = b.ID)
            LEFT JOIN db_academic.faculty AS c ON (a.FacultyID = c.FacultyID)
            WHERE c.ID NOT IN (4)')->result_array();

        //$data['ProgramStudy'] = $this->db->get('db_academic.program_study')->result_array();
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/transcript/setting_transcript',$data,true);
        $this->menu_transcript($page);
    }

}
