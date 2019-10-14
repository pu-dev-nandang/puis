<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_credit_type_courses extends Globalclass {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->library('JWT');
        $this->data['department'] = parent::__getDepartement(); 
    }


     public function temp($content)
    {
        parent::template($content);
    }

    public function menu_request($page){
        $data['page'] = $page;
        $content = $this->load->view('page/rektorat/menu_rektorat',$data,true);
        $this->temp($content);
    }


    public function index()
    {
        $data['InputForm'] = $this->load->view('page/'.$this->data['department'].'/master_data/credit_type_courses/InputForm','',true);
        $data2['action'] = 'write';
        $data['ViewTable'] = $this->load->view('page/'.$this->data['department'].'/master_data/credit_type_courses/ViewTable',$data2,true);
        $page = $this->load->view('page/'.$this->data['department'].'/master_data/credit_type_courses',$data,true);
        $this->menu_request($page);
    }

}
