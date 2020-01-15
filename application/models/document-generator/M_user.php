<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_user extends CI_Model {

	function __construct()
	{
	    parent::__construct();
	}

	public function preview_template($getObjInput){
		$rs = [];
		foreach ($getObjInput as $key => $value) {
			$generate = $this->__generate($value);
			$rs[] = [
				'field' => $value,
				'value' => $generate,
			];
		}

		return $rs;
	}

	private function __generate($variable){
		$ex = explode('.', $variable);
		/* array index 0 base session
		   JUST NIP untuk sementara
		*/
		switch ($ex[0]) {
			case 'NIP':
				$NIP = $this->session->userdata('NIP');
				$sql = 'select a.*,b.Name as ProdiName,b.Name as NameProdiEng,
						SPLIT_STR(a.PositionMain, ".", 1) as DivisionID,c.Division as DepartmentName,
						SPLIT_STR(a.PositionMain, ".", 2) as PosistionID,d.Position as PositionName
						from db_employees.employees as a
						left join db_academic.program_study as b on a.ProdiID = b.ID
						left join db_employees.division as c on SPLIT_STR(a.PositionMain, ".", 1) = c.ID
						left join db_employees.position as d on SPLIT_STR(a.PositionMain, ".", 2) = d.ID
						where a.NIP = "'.$NIP.'"
				 ';

				$query = $this->db->query($sql,array())->result_array();
				return $query[0][$ex[1]];
				break;
			
			default:
				# code...
				break;
		}
		
		
	}

}