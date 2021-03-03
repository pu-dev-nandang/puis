<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_log_content_model extends CI_Model {
	function __construct() {
	   parent::__construct();
	   $this->columns = isset($this->subdata['tbl_kb_log_content']) ? $this->subdata['tbl_kb_log_content']['columns'] : [];
	   $this->sqlFrom = '
	                    select lcc.NIP as NIP_readBy,emp_lcc.Name as Name_readBy,lcc.ViewedAt,kt.Type,kt.id as kb_type_id,qdj.Abbr as DepartmentCode,qdj.ID as IDDepartment,kb.Desc as KB_desc,kb.file as KB_file,kb.EntredBy as Entred_NIP,emp_kb.Name as Entred_Name
	                    	from db_employees.log_countable_content as lcc
	                    	join db_employees.knowledge_base as kb on kb.ID = lcc.ContentID
	                    	join db_employees.kb_type as kt on kt.ID = kb.IDType
	                    	'.$this->m_master->QueryDepartmentJoin('kt.IDDivision').'
	                    	join db_employees.employees as emp_lcc on emp_lcc.NIP = lcc.NIP
	                    	join db_employees.employees as emp_kb on emp_kb.NIP = kb.EntredBy
	                    	where lcc.TypeContent = "knowledge_base"

	                    ';
	}

	function get_all($start = 0, $length, $filter = array(), $order = array()) {
	    $this->filtered($filter);
	    if ($order) {
	        $order['column'] = $this->columns[$order['column']]['name'];
	        $this->db->order_by($order['column'], $order['dir']);
	    }
	    $data = $this->db->select('NIP_readBy,Name_readBy, ViewedAt,Type,kb_type_id,DepartmentCode,KB_desc,KB_file,Entred_NIP,Entred_Name')
	            ->limit($length, $start);

	    return $this->db->get('( '.$this->sqlFrom .' ) as summary');
	}

	private function filtered($filter = array()){
	    if (!empty($filter['start_date'])  &&  !empty($filter['end_date'] ) ) {
	    	$this->db->where(' ViewedAt >= "'.$filter['start_date'].'" and  ViewedAt <= "'.$filter['end_date'].' 23:59:59" ');
	    }
	    if ($filter) {
	        $this->db->group_start();
	        foreach ($filter as $column => $value) {

	        	if (is_int($column)) {
	        		if ($value  == '%') {
	        			$value = '';
	        		}
	        		$this->db->like('IFNULL(' . $this->columns[$column]['name'] . ',"")', $value);
	        	}
	            
	        }
	        $this->db->group_end();
	    }
	}

	public function get_total($filter = array()){
	    $this->filtered($filter);
	    $data = $this->db->count_all_results('( '.$this->sqlFrom .' ) as summary');
	    return $data;
	}


}