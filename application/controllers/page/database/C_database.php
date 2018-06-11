<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_database extends Globalclass {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('database/m_database');
        $this->load->library('JWT');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_lecturer($page){
        $data['page'] = $page;
        $content = $this->load->view('page/database/menu_lecturer',$data,true);
        $this->temp($content);
    }


    public function lecturers()
    {
        $page = $this->load->view('page/database/lecturers','',true);
        $this->menu_lecturer($page);
    }

    public function mentor_academic(){

        $page = $this->load->view('page/database/mentor_academic','',true);
        $this->menu_lecturer($page);
    }


    public function lecturersDetails($NIP){
        $data['NIP']=$NIP;
        $content = $this->load->view('page/database/lecturer/lecturer_menu',$data,true);
        $this->temp($content);
    }

    public function loadpagelecturersDetails(){
//        $page = $this->input->post('page');
//        $data['NIP'] = $this->input->post('NIP');

        $data_arr = $this->getInputToken();

//        print_r($data_arr);

        $this->load->view('page/database/lecturer/'.$data_arr['page'],$data_arr);
    }



    public function employees()
    {

        $content = $this->load->view('page/database/employees','',true);
        $this->temp($content);
    }


    // Modal Show Detail Mahasiswa
    public function showStudent(){
        $data['token'] = $this->input->post('token');
        $this->load->view('page/database/modal/modal_detail_student',$data);
    }


    // === Students ===
    public function students()
    {
        $content = $this->load->view('page/database/students','',true);
        $this->temp($content);
    }

    public function students2()
    {
        $content = $this->load->view('page/database/students','',true);
        $this->temp($content);
    }


}
