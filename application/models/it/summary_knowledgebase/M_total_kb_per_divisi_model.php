<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_total_kb_per_divisi_model extends CI_Model {

    function __construct() {
       parent::__construct();
    	$this->sqlFrom = 'from (
							SELECT
								pu_department.ID,
								pu_department.NameDepartment,
								(select count(*) as total from  db_employees.knowledge_base as kb join db_employees.kb_type as kt on kb.IDType = kt.ID where  kt.IDDivision =  pu_department.ID ) AS Countable,
								type_division
							FROM
								(
									SELECT
										CONCAT("AC.", ID) AS ID,
										NameEng AS NameDepartment,
										NAME AS NameDepartmentIND,
										CODE AS Abbr,
										"AC" AS type_division
									FROM
										db_academic.program_study
									UNION
										SELECT
											CONCAT("NA.", ID) AS ID,
											Division AS NameDepartment,
											Description AS NameDepartmentIND,
											Abbreviation AS Abbr,
											"NA" AS type_division
										FROM
											db_employees.division
										UNION
											SELECT
												CONCAT("FT.", ID) AS ID,
												NameEng AS NameDepartment,
												NAME AS NameDepartmentIND,
												Abbr,
												"FT" AS type_division
											FROM
												db_academic.faculty
								) AS pu_department
								GROUP BY
								pu_department.ID
						) AS summary';
    }

    public function get_all($start = 0, $length, $search, $order=array()){
    	$sqlSelect  = 'select "auto" as No, NameDepartment,Countable';
    	$where = $this->filtered($search);

    	$sqlOrderby = 'order by '. $this->subdata['tbl_total_kb_per_divisi']['columns'][$order['column']]['name'].' '.$order['dir'];
    	$sqlLimit = 'Limit '.$start.' , '.$length.'';

    	$datas =  $this->db->query(
    	    $sqlSelect.' '.$this->sqlFrom.' '.$where.' '.$sqlOrderby.' '.$sqlLimit
    	);

    	return $datas;
    }

    private function filtered($search=''){
    	$where = '';
    	if( !empty($search) ) {
    	    $WhereOrAnd = (!empty($where)) ? ' And ' : ' Where ';
    	    $where .= $WhereOrAnd.' NameDepartment like  "%'.$search.'%"  ';
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

    public function chart(){
    	$AbbreviationNonProdi = [];
    	$AbbreviationProdi = [];
    	$TotalNonProdi = [];
    	$TotalProdi = [];
    	$AllDepartment =  $this->m_master->getAllDepartementPU();
    	$x = 10;
    	$y = 10;
    	$z = 10;
    	$TotalNonProdi2 = [];
    	$AbbreviationNonProdi2 = [];
    	for ($i=0; $i < count($AllDepartment); $i++) { 
    		$Abbr = $AllDepartment[$i]['Abbr'];
    		if (substr($AllDepartment[$i]['Code'], 0,2) == 'NA') {
    			if ($x >= 20) {
    				$dataCountable = $this->db->query(
    					'select Countable '.$this->sqlFrom.' where ID = "'.$AllDepartment[$i]['Code'].'" limit 1 '
    				)->row()->Countable;
    				$AbbreviationNonProdi2[] = $Abbr;
    				$TotalNonProdi2[] = [$z,$dataCountable];
    				$z++;
    			}
    			else
    			{
    				$dataCountable = $this->db->query(
    					'select Countable '.$this->sqlFrom.' where ID = "'.$AllDepartment[$i]['Code'].'" limit 1 '
    				)->row()->Countable;
    				$AbbreviationNonProdi[] = $Abbr;
    				$TotalNonProdi[] = [$x,$dataCountable];
    				$x++;
    			}
    			
    		}
    		else
    		{
    			$dataCountable = $this->db->query(
    				'select Countable '.$this->sqlFrom.' where ID = "'.$AllDepartment[$i]['Code'].'" limit 1 '
    			)->row()->Countable;
    			$AbbreviationProdi[] = $Abbr;
    			$TotalProdi[] = [$y,$dataCountable];
    			$y++;
    		}
    	}

    	$rs =[

    	    'NonProdi' => [
    	        'Total' => $TotalNonProdi,
    	        'Abbreviation' => $AbbreviationNonProdi,
    	    ],

    	    'Prodi' => [
    	        'Total' => $TotalProdi,
    	        'Abbreviation' => $AbbreviationProdi,
    	    ],

    	    'NonProdi2' => [
    	        'Total' => $TotalNonProdi2,
    	        'Abbreviation' => $AbbreviationNonProdi2,
    	    ],

    	    
    	];

    	return $rs;
    }



}