<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_lpmi extends Lpmi {

    function __construct()
    {
        parent::__construct();
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_edom($page){
        $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/edom/menu_edom',$data,true);
        $this->temp($content);
    }

    public function edom_list_lecturer()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/edom/list_lecturer',$data,true);
        $this->menu_edom($page);
    }


}
