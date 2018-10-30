<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_timetables extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->load->model('akademik/m_tahun_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }



    public function menu_timetables($page){
        $data['department'] = parent::__getDepartement();
        $data['page']=$page;
        $content = $this->load->view('page/'.$data['department'].'/timetables/menu_timetables',$data,true);
        $this->temp($content);
    }

    public function list_timetables()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/timetables/list_timetables',$data,true);
        $this->menu_timetables($page);
    }

    public function edit_course($SemesterID,$ScheduleID,$Course){

        $data['department'] = parent::__getDepartement();
        $data['SemesterID'] = $SemesterID;
        $data['ScheduleID'] = $ScheduleID;
        $page = $this->load->view('page/'.$data['department'].'/timetables/edit_course',$data,true);
        $this->menu_timetables($page);
    }

    public function edit_schedule($SemesterID,$ScheduleID,$Course){
        $data['department'] = parent::__getDepartement();
        $data['SemesterID'] = $SemesterID;
        $data['ScheduleID'] = $ScheduleID;
        $page = $this->load->view('page/'.$data['department'].'/timetables/edit_schedule',$data,true);
        $this->menu_timetables($page);
    }

    public function course_offer()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/timetables/course_offer',$data,true);
        $this->menu_timetables($page);
    }

    public function setting_timetable()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/timetables/setting_timetable',$data,true);
        $this->menu_timetables($page);
    }





}
