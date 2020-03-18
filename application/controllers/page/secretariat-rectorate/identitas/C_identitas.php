<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_identitas extends Academic_Controler {

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

    public function list_identitas(){
        // $data['TempTranscript'] = $this->db->get('db_academic.setting_temp_transcript')->result_array()[0];
        // $data['Transcript'] = $this->db->get('db_academic.setting_transcript')->result_array()[0];
        // $data['Graduation'] = $this->db->get('db_academic.graduation')->result_array();
        // $data['Education'] = $this->db->get('db_academic.education_level')->result_array();

        $data['identitas'] = $this->db->query('SELECT * FROM db_academic.identitas')->result_array();

        //$data['ProgramStudy'] = $this->db->get('db_academic.program_study')->result_array();
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/identitas/list_identitas',$data,true);
        $this->menu_transcript($page);
    }

}
