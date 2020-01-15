<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_table extends CI_Model {

	/*
		NOTE :
			# => selected,
			$ => by Session

	*/

	function __construct()
	{
	    parent::__construct();
	    // $this->load->model('master/m_master');
	}

	public function __generate($Props){
		$DepartmentID = $this->session->userdata('DepartmentIDDocument');
		$sql = 'select a.* from db_generatordoc.api_doc  as a
				join db_generatordoc.api_doc_department as b on b.ID_api_doc = a.ID
				where a.Active = 1 
				and b.Department = "'.$DepartmentID.'"
				group by a.ID
				order by ID';
		$query = $this->db->query($sql,array())->result_array();

		$sqlSMT = 'select ID,Name as Value,Status as Selected from db_academic.semester order by ID DESC';
		$querySQLSMT = $this->db->query($sqlSMT,array())->result_array();
		$sqlEmployeesSample = 'SELECT em.NIP, em.Name
		                            FROM db_employees.employees em 
		                            LEFT JOIN db_academic.program_study ps ON (ps.ID = em.ProdiID)
		                            LEFT JOIN db_employees.employees_status ems ON (ems.IDStatus = em.StatusEmployeeID) 
		                            where StatusEmployeeID not in (-1,-2,4,6)
		                            ';
		$querySQLEmployees = $this->db->query($sqlEmployeesSample,array())->result_array();


		$Props['API'] = [
			'select' => $query,
			'Choose' => '',
			'paramsChoose' => [
				'#SemesterID' => $querySQLSMT,
			],
			'selectEmployees' => $querySQLEmployees,
			// 'MapTable' => [] => by JS
		];
		return $Props;
	}

	public function writeDocument($TemplateProcessor,$dataParams,$RSQuery){
		// print_r($dataParams);die();
		$arr_value = [];
		// fill header
		$HeaderMap = $dataParams['MapTable']['Header'];
		$temp = [];
		foreach ($HeaderMap as $key => $value) {
			$temp['TBL.'.$key] = $value;
			
		}
		$arr_value[] = $temp;
		$RSQuery = $RSQuery['callback'];
		// fill value
		for ($i=0; $i < count($RSQuery); $i++) { 
			$temp = [];
			$ValueMap = $dataParams['MapTable']['Value'];
			foreach ($ValueMap as $key => $value) {
				if ($value == 'Increment') {
					$no =  $i+1;
					$temp['TBL.'.$key] = $no;
				}
				else
				{
					$temp['TBL.'.$key] = $RSQuery[$i][$value];
				}
			}

			$arr_value[] = $temp;
		}

		$TemplateProcessor->cloneRowAndSetValues('TBL.'.$dataParams['KEY'][0],$arr_value );
		
	}

}