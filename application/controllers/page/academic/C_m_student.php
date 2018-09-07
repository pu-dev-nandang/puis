<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_m_student extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('akademik/m_akademik');
        $this->load->model('master/m_master');
        $this->load->model('akademik/m_mstudents');
        $this->data['department'] = parent::__getDepartement(); 
    }

    public function temp($content)
    {
        parent::template($content);
    }

    public function index()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/students/students','',true);
        $this->temp($content);
    }

    // Modal Show Detail Mahasiswa
    public function showStudent(){
        $data['token'] = $this->input->post('token');
        $this->load->view('page/'.$this->data['department'].'/master/students/modal_detail_student',$data);
        // $this->load->view('page/database/modal/modal_detail_student',$data);
    }

    public function loadPageStudents(){
        $data['dataForm'] = $this->input->post('data');
        $this->load->view('page/'.$this->data['department'].'/master/students/students_details',$data);
    }

    public function form_input_student($page = '')
    {   
        $this->data['NPM'] = $page;
        if ($page == '') {
            $this->data['action'] = 'add';
        }
        else
        {
            $this->data['action'] = 'edit';
        }
        
        $content = $this->load->view('page/database/employees/form_input',$this->data,true);
        $this->temp($content);
    }

    public function edit_student()
    {
        $input = $this->getInputToken();
        $arr = $this->m_master->caribasedprimary($input['Ta'].'.students','NPM',$input['NPM']);

        print_r($arr);
    }


}
