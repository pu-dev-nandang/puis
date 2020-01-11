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
		$sql = 'select * from db_generatordoc.api_doc where Active = 1 order by ID';
		$query = $this->db->query($sql,array())->result_array();

		$sqlSMT = 'select ID,Name as Value,Status as Selected from db_academic.semester order by ID DESC';
		$querySQLSMT = $this->db->query($sqlSMT,array())->result_array();

		$Props['API'] = [
			'select' => $query,
			'Choose' => '',
			'paramsChoose' => [
				'#SemesterID' => $querySQLSMT,
			],
		];
		return $Props;
	}

}