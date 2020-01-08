<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_action extends ServiceDocumentGenerator_Controler {
	public $GLobalVariable = array();

	function __construct()
	{
		header('Content-Type: application/json');
	    parent::__construct();
	}

	public function LoadMasterSurat(){
		$dataToken = $this->getInputToken();
		$rs = $this->m_doc->loadtableMaster($dataToken);
		echo json_encode($rs);
	}

	public function previewbyUserRequest(){
		$dataToken = $this->getInputToken();
		$dataToken = json_decode(json_encode($dataToken),true);
		$rs = $this->m_doc->previewbyUserRequest($dataToken);
		echo json_encode($rs);
	}

	public function savebyUserRequest(){
		$dataToken = $this->getInputToken();
		$dataToken = json_decode(json_encode($dataToken),true);
		$rs = $this->m_doc->savebyUserRequest($dataToken);
		echo json_encode($rs);
	}
}