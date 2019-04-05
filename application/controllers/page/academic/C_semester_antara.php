<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_semester_antara extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('akademik/m_matakuliah');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function index()
    {
        $department = parent::__getDepartement();
//        $data['data_mk'] = $this->m_matakuliah->__getAllMK();
        $content = $this->load->view('page/'.$department.'/semesterantara/semester_antara','',true);
        $this->temp($content);
    }

    private function menu_semester_antara($page,$IDSASemester){
        $data['department'] = parent::__getDepartement();
        $data['page']=$page;

        $dataSemesterAntara = $this->db->get_where('db_academic.semester_antara',
            array(
                'ID' => $IDSASemester
            ))->result_array();

        $data['DataSemesterAntara'] = json_encode($dataSemesterAntara);

        $content = $this->load->view('page/'.$data['department'].'/semesterantara/menu_semester_antara',$data,true);
        $this->temp($content);
    }

    public function timetable($IDSASemester){
        $data['IDSASemester'] = $IDSASemester;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_timetable',$data,true);
        $this->menu_semester_antara($page,$IDSASemester);
    }

    public function exam($IDSASemester){
        $data['IDSASemester'] = $IDSASemester;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_exam',$data,true);
        $this->menu_semester_antara($page,$IDSASemester);
    }

    public function score($IDSASemester){
        $data['IDSASemester'] = $IDSASemester;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_score',$data,true);
        $this->menu_semester_antara($page,$IDSASemester);
    }


    public function setting_timetable($IDSASemester){
        $data['IDSASemester'] = $IDSASemester;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_setting_timetable',$data,true);
        $this->menu_semester_antara($page,$IDSASemester);
    }

    public function setting_exam($IDSASemester){
        $data['IDSASemester'] = $IDSASemester;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_setting_exam',$data,true);
        $this->menu_semester_antara($page,$IDSASemester);
    }

    public function setting($IDSASemester){
        $data['IDSASemester'] = $IDSASemester;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_setting',$data,true);
        $this->menu_semester_antara($page,$IDSASemester);
    }



    // ============================================

    public function loadDetails($SA_ID){
        $department = parent::__getDepartement();
        $data['SA_ID'] =$SA_ID;
        $this->load->view('page/'.$department.'/semesterantara/details_sa',$data);

    }


}
