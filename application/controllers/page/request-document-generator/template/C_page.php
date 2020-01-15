<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_page extends ServiceDocumentGenerator_Controler {
	public $GLobalVariable = array();

	function __construct()
	{
	    parent::__construct();
	    $this->load->model('document-generator/m_auth');
	    $this->m_auth->__cekAuth();
	    
	}

	public function page_template(){
		$this->GLobalVariable['form_input'] = $this->load->view('global/request-document-generator/document/template/form-input','',true);
		$this->GLobalVariable['table'] = $this->load->view('global/request-document-generator/document/template/table','',true);
		$page = $this->load->view('global/request-document-generator/index',$this->GLobalVariable,true);
	    $this->menu_document($page);
	}
}