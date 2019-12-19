<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_set extends CI_Model {

	function __construct()
	{
	    parent::__construct();
	}

	public function __generate($Props)
	{
		$rs = [];
		foreach ($Props as $key => $value) {
			$ex = explode('.', $value);
			$func = trim($ex[0]);
			if (!method_exists($this,$ex[0])) {
				echo json_encode('Method not exist '.$ex[0]);
				die();
			}

			$rs[][$ex[0]] = $this->$ex[0]($value); // call function
			
		}

		return $rs;
	}

	public function PolaNoSurat($params){
		// example :  041/UAP/R/SKU/X/2019
		$ex = explode('.', $params);
		if (count($ex) > 0 && count($ex) == 1 ) {
			return array(
				'value' => 'method1',
				'setting' => array(
					'prefix' => '',
				),
			);
		}

	}

	public function Signature($params){
		$rs = [];
		$ex = explode('.', $params);
		$entity = $ex[1];
		switch ($entity) {
			case 'NIP':
				
				break;
			case 'Position':
				$sql = 'select * from db_employees.position where ID in(1,2,3,4,5,6,10,11,12)';
				$query = $this->db->query($sql,array())->result_array();
				$rs['select'] = $query;
				$rs['user'] = '';
				break;
			default:
				# code...
				break;
		}

		return $rs;
		
	}
}