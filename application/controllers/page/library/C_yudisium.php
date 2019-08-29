<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_yudisium extends Library {

    function __construct()
    {
        parent::__construct();
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_yudisium($page){
        $data['department'] = parent::__getDepartement();
        $data['page']=$page;
        $content = $this->load->view('page/'.$data['department'].'/yudisium/menu_yudisium',$data,true);
        $this->temp($content);
    }

    public function monitoring_yudisium()
    {
        $data['departement'] = $this->__getDepartement();
        $page = $this->load->view('page/'.$data['departement'].'/yudisium/monitoring_yudisium','',true);
        $this->menu_yudisium($page);
    }

    public function final_project()
    {
        $data['departement'] = $this->__getDepartement();
        $page = $this->load->view('page/'.$data['departement'].'/yudisium/final_project','',true);
        $this->menu_yudisium($page);
    }


}
