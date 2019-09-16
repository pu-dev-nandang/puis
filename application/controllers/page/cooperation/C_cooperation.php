<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_cooperation extends Cooperation_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->data['department'] = parent::__getDepartement();
    }

    public function kerja_sama_perguruan_tinggi()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/kerjasama-perguruan-tinggi/index',$this->data,true);
      $this->temp($content);
      
    }

}
