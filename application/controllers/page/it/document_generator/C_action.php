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
		$checkValidation['status'] = 1;
		if ($action != 'read' && $action != 'delete') {
			$checkValidation = $this->m_action->checkValidationQuery($dataToken);
		}
		
		if ($checkValidation['status'] == 1) {
			switch ($action) {
				case 'run':
					$rs = $checkValidation;
					break;
				case 'add':
					$dataSave = $dataToken['data'];
					unset($dataSave['user']);
					$dataSave['UpdatedBy'] = $this->session->userdata('NIP');
					$dataSave['UpdatedAt'] = date('Y-m-d H:i:s');
					$this->db->insert('db_generatordoc.api_doc',$dataSave);
					$insert_id = $this->db->insert_id();
					$DepartmentArr = $dataToken['DepartmentArr'];
					for ($i=0; $i < count($DepartmentArr); $i++) { 
						$Department = $DepartmentArr[$i]['Code'];
						$dataSave = [
							'ID_api_doc' => $insert_id,
							'Department' => $Department,
						];

						$this->db->insert('db_generatordoc.api_doc_department',$dataSave);
					}
					// die();
					$rs = $checkValidation;
					$rs['status'] = 1;
					break;
				case 'edit':
					$ID = $dataToken['ID'];
					$dataSave = $dataToken['data'];
					unset($dataSave['user']);
					$dataSave['UpdatedBy'] = $this->session->userdata('NIP');
					$dataSave['UpdatedAt'] = date('Y-m-d H:i:s');
					// print_r($dataSave);die();
					$this->db->where('ID',$ID);
					$this->db->update('db_generatordoc.api_doc',$dataSave);
					$insert_id = $ID;
					$DepartmentArr = $dataToken['DepartmentArr'];
					// delete first
					$this->db->where('ID_api_doc',$insert_id);
					$this->db->delete('db_generatordoc.api_doc_department');

					for ($i=0; $i < count($DepartmentArr); $i++) { 
						$Department = $DepartmentArr[$i]['Code'];
						$dataSave = [
							'ID_api_doc' => $insert_id,
							'Department' => $Department,
						];
						$this->db->insert('db_generatordoc.api_doc_department',$dataSave);
					}
					$rs = $checkValidation;
					$rs['status'] = 1;
					break;
				case 'delete':
					$ID = $dataToken['ID'];
					$this->db->where('ID',$ID);
					$this->db->delete('db_generatordoc.api_doc');

					$insert_id = $ID;
					$this->db->where('ID_api_doc',$insert_id);
					$this->db->delete('db_generatordoc.api_doc_department');
					$rs['status'] = 1;
					break;
				case 'read':
					$rs = [];
					$sql = 'select * from db_generatordoc.api_doc where Active = 1
					';
					$query = $this->db->query($sql,array())->result_array();
					$data = array();
					for ($i=0; $i < count($query); $i++) {
					    $nestedData = array();
					    $row = $query[$i]; 
					    $nestedData[] = $row['ApiNameTable'];
					    $nestedData[] = $row['ID'];
					    $row['document_access_department'] = $this->m_master->caribasedprimary('db_generatordoc.api_doc_department','ID_api_doc',$row['ID']);
					    // print_r($row);
					    $token = $this->jwt->encode($row,"UAP)(*");
					    $nestedData[] = $token;
					    $data[] = $nestedData;
					}
					$rs = array(
					    "draw"            => intval( 0 ),
					    "recordsTotal"    => intval(count($query)),
					    "recordsFiltered" => intval( count($query) ),
					    "data"            => $data
					);
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