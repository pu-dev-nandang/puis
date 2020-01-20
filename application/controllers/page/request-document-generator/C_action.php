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
		// print_r($dataToken);die();
		$rs = $this->m_doc->previewbyUserRequest($dataToken);
		echo json_encode($rs);
	}

	public function savebyUserRequest(){
		$dataToken = $this->getInputToken();
		$dataToken = json_decode(json_encode($dataToken),true);
		$action = $dataToken['action'];
		switch ($action) {
			case 'add':
				$rs = $this->m_doc->savebyUserRequest($dataToken);
				break;
			case 'edit':
				$rs = $this->m_doc->editbyUserRequest($dataToken);
				break;
			default:
				# code...
				break;
		}
		
		echo json_encode($rs);
	}

	public function LoadTablebyUserRequest(){
	    $dataToken = $this->getInputToken();
	    $rs = $this->m_doc->LoadTablebyUserRequest($dataToken);
	    echo json_encode($rs);
	}

	public function ApproveOrReject(){
		$dataToken = $this->getInputToken();
		$dataToken = json_decode(json_encode($dataToken),true);
		$decision = $dataToken['decision'];
		switch ($decision) {
			case 'Approve':
				$rs = $this->m_doc->ApproveDocument($dataToken);
				break;
			case 'Reject':
				$approval_number = $dataToken['approval_number'];
				$dataID = $dataToken['dataID'];
				$Note = $dataToken['Note'];
				$dataSave = [
					'Approve'.$approval_number.'Status' => -1,
					'Approve'.$approval_number.'At' => date('Y-m-d H:i:s'),
					'Status' => 'Reject',
					'Note' => $Note,
				];
				$this->db->where('ID',$dataID);
				$this->db->update('db_generatordoc.document_data',$dataSave);
				$rs = 1;
				break;
			default:
				# code...
				break;
		}

		echo json_encode($rs);
	}

	public function logData(){
		$dataToken = $this->getInputToken();
		$dataToken = json_decode(json_encode($dataToken),true);
		$ID = $dataToken['ID'];
		$sql = 'select a.DocumentName,b.Log,c.Name as NameBy,b.CreatedAt
				from db_generatordoc.document as a 
				join db_generatordoc.document_data as d on d.ID_document = a.ID
				join db_generatordoc.log_data as b on d.ID = b.ID_document_data
				join db_employees.employees as c on b.CreatedBy = c.NIP
				where d.ID = '.$ID.'
				';
		$query = $this->db->query($sql,array())->result_array();
		echo json_encode($query);
	}

	public function LoadSessionDepartment(){
		$dataToken = $this->getInputToken();
		$this->__setDepartmentSession($dataToken['DepartmentIDChoose']);
		echo json_encode(1);
	}
}