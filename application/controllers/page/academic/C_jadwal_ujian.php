<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_jadwal_ujian extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
        $this->load->model('akademik/m_jadwal_ujian');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_jadwalUjian($page){
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$data['department'].'/jadwalujian/menu_jadwal_ujian',$data,true);
        $this->temp($content);
    }


    public function list_exam(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/jadwalujian/list_exam',$data,true);
        $this->menu_jadwalUjian($page);
    }

    public function set_exam_schedule(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/jadwalujian/set_exam_schedule',$data,true);
        $this->menu_jadwalUjian($page);
    }

    public function exam_setting(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/jadwalujian/exam_setting',$data,true);
        $this->menu_jadwalUjian($page);
    }

    public function list_waiting_approve(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/jadwalujian/list_waiting_approve',$data,true);
        $this->menu_jadwalUjian($page);
    }

    public function edit_exam_schedule($ExamID){
        $data['department'] = parent::__getDepartement();
        $data['arrExam'] = $this->m_jadwal_ujian->__getExam($ExamID);
        $page = $this->load->view('page/'.$data['department'].'/jadwalujian/edit_exam_schedule',$data,true);
        $this->menu_jadwalUjian($page);
    }



    // Jadwal Ujian Lama
    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/jadwalujian/tab_jadwalujian',$data,true);
        $this->temp($content);
    }

    public function setPageJadwal()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);

        $page = $data_arr['page'];
        $ScheduleID = $data_arr['ScheduleID'];

        $department = parent::__getDepartement();
        $path = 'page/'.$department.'/jadwalujian';

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
