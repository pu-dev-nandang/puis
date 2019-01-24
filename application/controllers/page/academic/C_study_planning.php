<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_study_planning extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
//        $this->load->model('m_kurikulum');
    }

    public function temp($content)
    {
        parent::template($content);
    }


    public function menu_studyplan($page){
        $department = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$department.'/studyplanning/menu_studyplan',$data,true);
        $this->temp($content);
    }

    public function liststudent(){
        $department = parent::__getDepartement();
        $data[''] = '';
        $page = $this->load->view('page/'.$department.'/studyplanning/liststudent',$data,true);
        $this->menu_studyplan($page);
    }

    public function course_offer($SemesterID,$ProdiGroupID,$NPM){
        $department = parent::__getDepartement();
        $data['NPM'] = $NPM;
        $data['SemesterID'] = $SemesterID;
        $data['ProdiGroupID'] = $ProdiGroupID;
        $page = $this->load->view('page/'.$department.'/studyplanning/course_offer',$data,true);
        $this->menu_studyplan($page);

    }

    public function index()
    {
        $department = parent::__getDepartement();
        $data = '';
        $content = $this->load->view('page/'.$department.'/studyplanning/tab_menu',$data,true);
        $this->temp($content);
    }

    public function setPageJadwal(){
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);


        $page = $data_arr['page'];
        $ScheduleID = $data_arr['ScheduleID'];

        $department = parent::__getDepartement();
        $path = 'page/'.$department.'/jadwal';

        $this->cekFileView($path,$page,$ScheduleID);

    }

    private function cekFileView($path,$file,$ScheduleID)
    {

        $data = false;
        if (file_exists(APPPATH."views/".$path."/{$file}.php"))
        {
            $dataView['ScheduleID'] = $ScheduleID;
            $data = $this->load->view($path.'/'.$file,$dataView);

        }

        return $data;
    }

}
