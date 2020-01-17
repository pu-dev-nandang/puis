<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_action extends It_Controler {
	public $GLobalVariable = array();

	function __construct()
	{
		header('Content-Type: application/json');
	    parent::__construct();
	    $this->load->model('it/document-generator/m_action');
	}

	public function CRUDPrivileges()
	{
		$dataToken = $this->getInputToken();
		$dataToken = json_decode(json_encode($dataToken),true);
		$action = $dataToken['action'];
		switch ($action) {
			case 'read':
				$rs = $this->m_action->LoadTablePrivileges($dataToken);
				break;
			case 'add':
				$rs = $this->m_action->InsertTablePrivileges($dataToken);
				break;
			case 'delete':
				$rs = $this->m_action->DeleteTablePrivileges($dataToken);
				break;
			case 'edit':
				$rs = $this->m_action->EditTablePrivileges($dataToken);
				break;	
			default:
				# code...
				break;
		}
		
		echo json_encode($rs);
	}

	public function CRUDsqlQueryLanguange(){
		$dataToken = $this->getInputToken();
		$dataToken = json_decode(json_encode($dataToken),true);
		$action = $dataToken['action'];
		$rs = [];
		$checkValidation = $this->m_action->checkValidationQuery($dataToken);
		if ($checkValidation['status'] == 1) {
			switch ($action) {
				case 'add':
					
					break;
				
				default:
					# code...
					break;
			}
		}
		else
		{
			$rs = $checkValidation;
		}

		echo json_encode($rs);
		
	}
}