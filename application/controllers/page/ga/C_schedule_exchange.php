<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_schedule_exchange extends Ga_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement();
        
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function index($page)
    {
        $department = parent::__getDepartement();
        $this->data['page'] = $page;
        $content = $this->load->view('page/'.$this->data['department'].'/schedule_exchange/page_index',$this->data,true);
        $this->temp($content);
    }

    public function schedule_exchange_action(){
        $department = parent::__getDepartement();
        $data[''] = '';
        $page = $this->load->view('page/'.$department.'/schedule_exchange/schedule_exchange_action',$data,true);
        $this->index($page);
    }

}
