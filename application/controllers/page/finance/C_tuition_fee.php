<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_tuition_fee extends Finnance_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement();
        $this->load->model('finance/m_finance');
        $this->load->model('m_sendemail');
    }


    public function temp($content)
    {
        parent::template($content);
    }


    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $content = "test";
        $this->temp($content);
    }

    public function tuition_fee(){
        $content = $this->load->view('page/'.$this->data['department'].'/master/tuition_fee',$this->data,true);
        $this->temp($content);
    }

}
