<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_document extends DocumentGenerator_Controler {

    function __construct()
    {
        parent::__construct();
    }

    public function document(){
    	$this->data['form_input'] = $this->load->view('page/rektorat/document-generator/document/form-input','',true);
    	$this->data['table'] = $this->load->view('page/rektorat/document-generator/document/table','',true);
    	$page = $this->load->view('page/rektorat/document-generator/document',$this->data,true);
        $this->menu_document($page);
    }

    public function setting(){
        $this->data['form_input'] = $this->load->view('page/rektorat/document-generator/setting/form-input','',true);
        $this->data['table'] = $this->load->view('page/rektorat/document-generator/setting/table','',true);
        $page = $this->load->view('page/rektorat/document-generator/setting',$this->data,true);
        $this->menu_document($page);
    }
}