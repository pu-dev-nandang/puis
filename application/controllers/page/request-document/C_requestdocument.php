<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_requestdocument extends Globalclass {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('database/m_database');
        $this->load->library('JWT');
        $this->data['department'] = parent::__getDepartement(); 
    }


     public function temp($content)
    {
        parent::template($content);
    }

    public function menu_request($page){
        $data['page'] = $page;
        $content = $this->load->view('page/rektorat/menu_rektorat2',$data,true);
        $this->temp($content);
    }


    public function list_requestdocument()
    {
        $page = $this->load->view('page/'.$this->data['department'].'/list_requestdocument','',true);
        $this->menu_request($page);
    }

    public function list_requestsuratmengajar()
    {
        $page = $this->load->view('page/'.$this->data['department'].'/list_requestsuratmengajar','',true);
        $this->menu_request($page);
    }


    public function frm_requestdocument() {
        $page = $this->load->view('page/rektorat/form_request','',true);
        $this->menu_request($page);
    }

     public function suratKeluar(){

        //$token = '488a476ba583155fd274ffad3ae741408d357054';
        // get token
            $G_emp = $this->m_master->caribasedprimary('db_employees.employees','NIP',$this->session->userdata('NIP'));
            $token = $G_emp[0]['Password'];


        $dataEmployees = $this->db->select('Name,NIP,TitleAhead,TitleBehind')->get_where('db_employees.employees',array(
            'Password' => $token
        ))->result_array();


        // $data['include'] = $this->load->view('template/include','',true);
        $data['dataEmp'] = $dataEmployees;
        $content =$this->load->view('global/form/formTugasKeluar',$data,true);
        $this->temp($content);
    }

    



}
