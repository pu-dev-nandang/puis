<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_presensi extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->load->model('akademik/m_tahun_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_presensi($page){

        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$data['department'].'/presensi/menu_presensi',$data,true);
        $this->temp($content);

    }

    //==== Page =====
    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/presensi/attendance',$data,true);
        $this->menu_presensi($content);
    }

    public function monitoring_lecturer(){
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/presensi/monitoring_lecturer',$data,true);
        $this->menu_presensi($content);
    }

    public function monitoring_student(){
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/presensi/monitoring_student',$data,true);
        $this->menu_presensi($content);
    }

    public function monitoring_allstudent(){
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/presensi/monitoring_allstudent',$data,true);
        $this->menu_presensi($content);
    }

    public function monitoring_exchange(){
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/presensi/monitoring_schedule_exchange',$data,true);
        $this->menu_presensi($content);
    }


    //========================


    public function loadPagePresensi(){
        $data_arr = $this->getInputToken();

        $this->load->view('page/academic/presensi/'.$data_arr['page'],$data_arr);
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
