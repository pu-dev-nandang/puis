<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_finap extends Budgeting_Controler {
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
    	$content = $this->load->view('global/budgeting/finap/menu_horizontal',$data,true);
    	$this->temp($content);
    }

    public function index()
    {
		$page = $this->load->view('global/budgeting/finap/list',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function create_ap()
    {
        $page = $this->load->view('global/budgeting/finap/create_ap',$this->data,true);
        $this->menu_horizontal($page);
    }

}
