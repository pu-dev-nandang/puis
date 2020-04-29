<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_research extends CI_Model {
    public $data = [];

    function __construct()
    {
        parent::__construct();
    }

    public function datatable_LoadListUserEskternal($dataToken){
    	$requestData = $_REQUEST;
    	$AddwherePost = [];
    	$where='';
    	if(!empty($requestData['search']['value']) ) {
    	    $search = $requestData['search']['value'];
    	    $AddwherePost[] = array('field'=>'(a.`'.'Nama'.'`','data'=>' like "%'.$search.'%"' ,'filter' =>' AND ');     
    	    $AddwherePost[] = array('field'=>'a.`'.'NIP'.'`','data'=>' like "'.$search.'%" ' ,'filter' =>' OR ');     
    	    $AddwherePost[] = array('field'=>'a.`'.'NIDN'.'`','data'=>' like "'.$search.'%" ' ,'filter' =>' OR ');     
    	    $AddwherePost[] = array('field'=>'a.`'.'Email'.'`','data'=>' like "'.$search.'%" )' ,'filter' =>' OR ');     
    	}

    	if (array_key_exists('TypeUser', $dataToken) && $dataToken['TypeUser']!= '%') {
    		$AddwherePost[] = array('field'=>'a.`'.'TypeUser'.'`','data'=>' = "'.$dataToken['TypeUser'].'"' ,'filter' =>' And '); 
    	}

    	if(!empty($AddwherePost)){
    	  $where = ' WHERE ';
    	  $counter = 0;
    	  foreach ($AddwherePost as $key => $value) {
    	      if($counter==0){
    	          $where = $where.$value['field']." ".$value['data'];
    	      }
    	      else{
    	          $where = $where.$value['filter']." ".$value['field']." ".$value['data'];
    	      }
    	      $counter++;
    	  }
    	}


    	$totalData = $this->db->query(
    		'select count(*) as total from db_research.master_user_research as a 
    		'.$where.'
    		'
    	)->row()->total;

    	$queryData = $this->db->query(
    		'select a.*,b.Name_University from db_research.master_user_research as a
    		left join db_research.university as b on a.ID_University = b.ID 
    		'.$where.' LIMIT
    		'.$requestData['start'].' , '.$requestData['length']
    	)->result_array();

    	$No = (int)$requestData['start'] + 1;
    	$data = array();
    	for ($i=0; $i < count($queryData); $i++) { 
    	  $row = $queryData[$i];
    	  $nestedData = array();
    	  $nestedData[] = $No;
    	  foreach ($row as $key => $value) {
    	  	$nestedData[] = $value;
    	  }
    	  $tokenRow = $this->jwt->encode($row,"UAP)(*");
    	  $nestedData['data'] = $tokenRow;
    	  $data[] = $nestedData;
    	  $No++;
    	}

    	$json_data = array(
    	    "draw"            => intval( $requestData['draw'] ),
    	    "recordsTotal"    => intval($totalData ),
    	    "recordsFiltered" => intval( $totalData ),
    	    "data"            => $data,
    	);

    	return $json_data;

    }

    private function checkDataUserEksternalDuplicate($arr,$ID=''){
    	$bool = true;
    	$WhereID = ($ID!='') ? 'And ID != '.$ID : '';
    	foreach ($arr as $key => $value) {
    		if ($key = 'TypeUser' && $value == 'Dosen') {
    			$chkQuery = $this->db->query('
    					select count(*) as total from (
    						select 1 from db_research.master_user_research
    						where NIP = "'.$arr['NIP'].'" and NIDN = "'.$arr['NIDN'].'"
    						and ID_University = "'.$arr['ID_University'].'" '.$WhereID.'
    					)xx
    				')->row()->total;
    			if ($chkQuery > 0) {
    				$bool = false;
    				break;
    			}
    		}
    		elseif ($key = 'TypeUser' && $value == 'Mahasiswa') {
    			$chkQuery = $this->db->query('
    					select count(*) as total from (
    						select 1 from db_research.master_user_research
    						where NIM = "'.$arr['NIM'].'"
    						and ID_University = "'.$arr['ID_University'].'" '.$WhereID.'
    					)xx
    				')->row()->total;
    			if ($chkQuery > 0) {
    				$bool = false;
    				break;
    			}
    		}
    		else
    		{
    			$chkQuery = $this->db->query('
    					select count(*) as total from (
    						select 1 from db_research.master_user_research
    						where Email = "'.$arr['Email'].'" '.$WhereID.'
    					)xx
    				')->row()->total;
    			if ($chkQuery > 0) {
    				$bool = false;
    				break;
    			}
    		}
    	}

    	return $bool;
    }

    private function checkDataUserEksternalhasBeenUsing($ID){
    	$chkQuery = $this->db->query(
    		'select count(*) as total from 
    		(
    			select 1 from db_research.master_anggota_penelitian
    			where ID_user = "ekd.'.$ID.'"
    			UNION ALL
    			select 1 from db_research.master_anggota_pkm
    			where ID_user = "ekd.'.$ID.'"
    			UNION ALL
    			select 1 from db_research.master_anggota_publikasi
    			where ID_user = "ekd.'.$ID.'"
    		)xx

    		'
    	)->row()->total;

    	if ($chkQuery > 0 ) {
    		return false;
    	}
    	else
    	{
    		return true;
    	}
    }

    public function edit_user_eksternal($dataToken){
    	$dataSave = $dataToken['data'];
    	$ID = $dataToken['ID'];
    	$chk = $this->checkDataUserEksternalDuplicate($dataSave,$ID);
    	if ($chk) {
    		$this->db->where('ID',$ID);
    		$this->db->update('db_research.master_user_research',$dataSave);
    		return ['status' => 1,'msg' => ''];
    	}
    	else
    	{
    		return ['status' => 0,'msg' => 'Data duplicate, please check'];
    	}
    }

    public function insert_user_eksternal($dataToken){
    	$dataSave = $dataToken['data'];
    	$chk = $this->checkDataUserEksternalDuplicate($dataSave);
    	if ($chk) {
    		$this->db->insert('db_research.master_user_research',$dataSave);
    		return ['status' => 1,'msg' => ''];
    	}
    	else
    	{
    		return ['status' => 0,'msg' => 'Data duplicate, please check'];
    	}
    }

    public function delete_user_eksternal($dataToken){
    	$ID = $dataToken['ID'];
    	$chk = $this->checkDataUserEksternalhasBeenUsing($ID);
    	if ($chk) {
    		$this->db->where('ID',$ID);
    		$this->db->delete('db_research.master_user_research');
    		return ['status' => 1,'msg' => ''];
    	}
    	else
    	{
    		return ['status' => 0,'msg' => 'Data has been using for transaction'];
    	}
    }

}    