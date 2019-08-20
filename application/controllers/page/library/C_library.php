<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_library extends Library {

    function __construct()
    {
        parent::__construct();
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function monitoring_yudisium()
    {
        $data['departement'] = $this->__getDepartement();
        $content = $this->load->view('page/'.$data['departement'].'/yudisium/monitoring_yudisium','',true);
        $this->temp($content);
    }


}
