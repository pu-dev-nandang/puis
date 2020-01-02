<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_medical_record extends Student_Life {

    function __construct()
    {
        parent::__construct();
    }


    public function temp($content)
    {
        parent::template($content);
    }

    private function menu_student_medical_record($page){
        $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/medical-record/menu_student_medical_record',$data,true);
        $this->temp($content);
    }


    public function student_medical_record(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/medical-record/student_medical_record',$data,true);
        $this->menu_student_medical_record($page);
    }


    public function medical_history(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/medical-record/medical_history',$data,true);
        $this->menu_student_medical_record($page);
    }

}
