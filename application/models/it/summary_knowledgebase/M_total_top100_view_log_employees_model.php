<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_total_top100_view_log_employees_model extends CI_Model {

    function __construct() {
       parent::__construct();
    	$this->sqlFrom = ' from (select * from (
                                    select lcc.NIP, 
                                    emp.Name,
                                    count(lcc.NIP) as Countable from db_employees.log_countable_content as lcc
                                    join db_employees.employees as emp on emp.NIP = lcc.NIP
                                    group by lcc.NIP

                                    ) as summary
                    order by Countable desc limit 100 ) summary

                    ';
    }

    public function get_all($start = 0, $length, $search, $order=array()){
    	$sqlSelect  = 'select "auto" as No, NIP,Name,Countable';
    	$where = $this->filtered($search);

    	$sqlOrderby = 'order by '. $this->subdata['total_top100_view_log_employees']['columns'][$order['column']]['name'].' '.$order['dir'];
    	$sqlLimit = 'Limit '.$start.' , '.$length.'';

    	$datas =  $this->db->query(
    	    $sqlSelect.' '.$this->sqlFrom.' '.$where.' '.$sqlOrderby.' '.$sqlLimit
    	);

    	return $datas;
    }

    private function filtered($search=''){
    	$where = '';
    	if( !empty($search) ) {
    	    $where .= 'where ( NIP like  "%'.$search.'%" or Name like  "%'.$search.'%")  ';
    	}

    	return $where;
    }

    public function get_total($search=''){
    	$where = $this->filtered($search);
    	$sql = 'select count(*) as total '.' '.$this->sqlFrom.' '.$where;

    	$data_total = $this->db->query(
    	    'select count(*) as total '.' '.$this->sqlFrom.' '.$where
    	)->row()->total;

    	return $data_total;
    }

}