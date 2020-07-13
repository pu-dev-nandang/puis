<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_setting extends Globalclass {
public $data = array();
    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function index ()
    {   
        $data['G_division'] = $this->m_master->apiservertoserver(base_url().'api/__getAllDepartementPU');
        $content = $this->load->view('page/rektorat/monthly_report/Setting',$data,true);
        $this->temp($content);
    }

     
    

}
