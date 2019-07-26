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
        $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
        $Year = $get[0]['Year'];
        $this->data['Year'] = $Year;
        $IDDepartementPUBudget = $this->session->userdata('IDDepartementPUBudget');
        // get budgeting/detail_budgeting_remaining
            $getData = $this->m_budgeting->get_budget_remaining($Year,$IDDepartementPUBudget);
            $arr_result = array('data' =>$getData);
        $this->data['detail_budgeting_remaining'] = json_encode($arr_result['data']);          
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
