<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_score extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->load->model('akademik/m_tahun_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }



    public function menu_score($page){
        $data['department'] = parent::__getDepartement();
        $data['page']=$page;
        $content = $this->load->view('page/'.$data['department'].'/score/menu_score',$data,true);
        $this->temp($content);
    }

    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/score/score',$data,true);
        $this->menu_score($page);
    }

    public function inputScore(){
        $data_arr = $this->getInputToken();
        $this->load->view('page/academic/score/inputScore',$data_arr);
    }

    public function monitoring_score(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/score/monitoring_score',$data,true);
        $this->menu_score($page);
    }

}
