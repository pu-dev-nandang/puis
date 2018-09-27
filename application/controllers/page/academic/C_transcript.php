<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_transcript extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->load->model('akademik/m_tahun_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_transcript($page){
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$data['department'].'/transcript/menu_transcript',$data,true);
        parent::template($content);
    }

    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/transcript/transcript_list_student',$data,true);
        $this->menu_transcript($page);
    }

    public function inputScore(){
        $data_arr = $this->getInputToken();
        $this->load->view('page/academic/score/inputScore',$data_arr);
    }

}
