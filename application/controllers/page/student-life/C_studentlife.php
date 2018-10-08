<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_studentlife extends Student_Life {

    function __construct()
    {
        parent::__construct();
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_diploma_supplement($page){
        $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/diploma-supplement/menu_diploma_supplement',$data,true);
        $this->temp($content);
    }

    public function diploma_supplement()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/diploma-supplement/list_student',$data,true);
        $this->menu_diploma_supplement($page);
    }


}
