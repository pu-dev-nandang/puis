<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_home extends Prodi_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        // load sambutan_model
        $this->load->model('admin-prodi/beranda/m_home');
        $this->load->helper(array('form', 'url'));
    }


    function temp($content)
    {
        parent::template($content);
    }

// ===== Slide ======
    function slide()
    {
        // Database
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/beranda/v_slider',$data,true);
        parent::template($content);
    }
    
// ===== overview ======
    function overview()
    {
        // Database
    	$data['department'] = parent::__getDepartement();
    	$content = $this->load->view('page/'.$data['department'].'/beranda/v_sambutan',$data,true);
    	parent::template($content);
    }
// ===== why ======
    function whychoose()
    {
        // Database
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/beranda/v_why',$data,true);
        parent::template($content);
    }
// ===== Call to Action ======
    function calltoaction()
    {
        // Database
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/beranda/v_call',$data,true);
        parent::template($content);
    }
// ===== Call to Action ======
    function testimoni()
    {
        // Database
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/beranda/v_testimoni',$data,true);
        parent::template($content);
    }

        
}

