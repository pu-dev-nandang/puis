<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pettycash extends Budgeting_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function menu_horizontal($page)
    {
    	$data['content'] = $page;
    	$content = $this->load->view('global/budgeting/pettycash/menu_horizontal',$data,true);
    	$this->temp($content);
    }

    public function index()
    {       
		$page = $this->load->view('global/budgeting/pettycash/list',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function create_pettycash()
    {
		$page = $this->load->view('global/budgeting/pettycash/create_pettycash',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function configuration()
    {
    	/*
			1.Only auth finance
    	*/
    	if ($this->session->userdata('IDDepartementPUBudget') == 'NA.9') {
    		$page = $this->load->view('global/budgeting/pettycash/configuration',$this->data,true);
    		$this->menu_horizontal($page);
    	}
    	else
    	{
    		show_404($log_error = TRUE);
    	}
    	
    }

}
