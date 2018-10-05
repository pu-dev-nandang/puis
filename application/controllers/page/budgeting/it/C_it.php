<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_it extends Budgeting_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement();
        if ($this->data['department'] != 12) {
          exit('No direct script access allowed');
        }
        $this->load->model('m_api');
    }

}
