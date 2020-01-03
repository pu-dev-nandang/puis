<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_page extends ServiceDocumentGenerator_Controler {
	public $GLobalVariable = array();

	function __construct()
	{
	    parent::__construct();
	}

	public function index(){
		$this->GLobalVariable['table'] = $this->load->view('global/request-document-generator/document/table','',true);
		$this->GLobalVariable['form_input'] = $this->load->view('global/request-document-generator/document/form_input','',true);
		$page = $this->load->view('global/request-document-generator/index',$this->GLobalVariable,true);
	    $this->menu_document($page);
	}
}