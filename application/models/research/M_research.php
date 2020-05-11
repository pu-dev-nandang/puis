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

    public function QueryUserReserachJoin($IDJoin,$aliasTable = 'usrr',$join='left join'){
      $sql = ' '.$join.' (
            select * from (
            select CONCAT("ekm.",ID) as ID_user,Nama,NIDN,NIP as NIPorNPM,Email from db_research.master_user_research
            where TypeUser = "Mahasiswa"
            UNION ALL
            select CONCAT("ekd.",ID) as ID_user,Nama,NIDN,NIP as NIPorNPM,Email from db_research.master_user_research
            where TypeUser != "Mahasiswa"
            UNION ALL
            select CONCAT("mhs.",NPM) as ID_user,Name as Nama,"",NPM as NIPorNPM,EmailPU from
            db_academic.auth_students
            UNION ALL
            select CONCAT("dsn.",NIP) as ID_user,Name as Nama,NIDN,NIP as NIPorNPM,EmailPU from db_employees.employees
        )'.$aliasTable.')'.$aliasTable.' on '.$IDJoin.'='.$aliasTable.'.ID_user';
      return $sql;
    }

    public function dtSrvSide_research_eksternal($parameter){
        $requestData = $parameter['request'];
        $ID_user = $parameter['ID_user'];
        $AddwherePost = [];
        $where='';
        if(!empty($requestData['search']['value']) ) {
            $search = $requestData['search']['value'];
            $AddwherePost[] = array('field'=>'(a.`'.'Judul_litabmas'.'`','data'=>' like "%'.$search.'%"' ,'filter' =>' AND ');     
            $AddwherePost[] = array('field'=>'c.`'.'Nama'.'`','data'=>' like "'.$search.'%" )' ,'filter' =>' OR ');     
        }

        if (array_key_exists('filter', $parameter) ) {
            $filter  = $parameter['filter'];
            foreach ($filter as $row => $v) {
                if ($row == 'status' && $v != 'all') {
                    $AddwherePost[] = array('field'=>'c.`'.'Reviewer_confirm'.'`','data'=>' = "'.$v.'"' ,'filter' =>' And '); 
                }
            }
            
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

        $select1 = 'select 1';
        $selectdata = 'SELECT a.ID_litabmas, a.Judul_litabmas, a.ID_thn_usulan, a.ID_thn_laks,  a.ID_skim, b.Nm_skim, a.Lama_kegiatan, a.Last_update, a.Status_data, xx.Name, a.Jenis_usulan,c.Reviewer_confirm,c.Nama as NamaReviewer,c.ID_list_anggota';

        $fromData = ' FROM db_research.litabmas AS a 
                    left JOIN db_research.skim_kegiatan AS b ON (b.Kd_skim = a.ID_skim)
                    JOIN db_employees.employees AS xx ON (xx.NIP = a.NIP)
                    JOIN (
                            select lap.ID_litabmas,lap.ID_anggota,lap.Reviewer_confirm,map.ID_user,map.Type_anggota,map.Luar_internal,usr.Nama,lap.ID as ID_list_anggota
                            from db_research.list_anggota_penelitian as lap,
                            db_research.master_anggota_penelitian as map
                            '.$this->QueryUserReserachJoin('map.ID_user','usr').' 
                            where
                            lap.ID_anggota = map.ID
                            AND map.ID_user = "'.$ID_user.'" 
                            AND map.Type_anggota = "REV" and Luar_internal = "0"
                        ) as c on a.ID_litabmas = c.ID_litabmas 
                    left join (
                        select * from db_research.appr_reviewer_penelitian
                        group by ID_list_anggota_penelitian order by ID desc
                    ) as log on log.ID_list_anggota_penelitian =  c.ID_list_anggota   
                        ';

        $addSql = ' order by log.ID desc LIMIT
            '.$requestData['start'].' , '.$requestData['length'];


        $totalData = $this->db->query(
            'select count(*) as total from 
            (
               '.$select1.' '.$fromData.' '.$where.' 
            )xx

            '
        )->row()->total;

        $queryData = $this->db->query(
            $selectdata.' '.$fromData.' '.$where.' '.$addSql
        )->result_array();

        // print_r($this->db->last_query());die();

        $No = (int)$requestData['start'] + 1;
        $data = array();
        for ($i=0; $i < count($queryData); $i++) { 
          $row = $queryData[$i];
          $nestedData = array();
          $nestedData[] = $No;
          foreach ($row as $key => $value) {
            $nestedData[] = $value;
          }
          $row['DataDetail'] = $this->detail_litabmas($row['ID_list_anggota']);
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

    public function detail_litabmas($ID_list_anggota){
        $rs = [];
        $query = 'SELECT a.ID_Litabmas, a.User_create
                  FROM db_research.list_anggota_penelitian AS a
                  LEFT JOIN db_research.master_anggota_penelitian AS b ON (a.ID_anggota = b.ID)
                  WHERE a.ID = "'.$ID_list_anggota.'" LIMIT 1 ';
        $datas=$this->db->query($query, array())->result_array(); 
        // print_r($query); exit();
        
        if(count($datas)>0) {

            $sql = 'SELECT a.*,  b.Nm_skim, c.Nm_kel_bidang, d.Name_University, a.Judul_litabmas, e.Judul_litabmas AS judul_lanjutan, f.SumberDana, g.NameEng, h.Name AS NamaSemester, j.Name AS NmThn_akademik
            FROM db_research.litabmas AS a 
            LEFT JOIN db_research.skim_kegiatan AS b ON (b.Kd_skim = a.ID_skim)
            LEFT JOIN db_research.kelompok_bidang AS c ON (c.Kode_kel_bidang = a.ID_kel_bidang)
            LEFT JOIN db_research.university AS d ON (d.ID = a.ID_lemb_iptek)
            LEFT JOIN db_research.litabmas e ON (e.ID_litabmas = a.ID_lanjutan_litabmas)
            LEFT JOIN db_agregator.sumber_dana f ON (f.ID = a.ID_sumberdana)
            LEFT JOIN db_academic.mata_kuliah g ON (g.MKCode = a.MKCode)
            LEFT JOIN db_academic.semester h ON (h.ID = a.SemesterID)
            LEFT JOIN db_academic.semester j ON (j.ID = a.Tahun_akademik)
            WHERE a.ID_litabmas = "'.$datas[0]['ID_Litabmas'].'" AND a.NIP = "'.$datas[0]['User_create'].'" AND a.Stat_aktif= 1 ';
            $rs = $this->db->query($sql, array())->result_array();   
        }

        return $rs; 
    }

    public function getProposal_research($parameter){
        // print_r($parameter);die();
        $ID_Litabmas = $parameter['ID_Litabmas'];
        $NIP = $parameter['NIP'];
        $G_format_laporan = $this->m_master->showData_array('db_research.master_format_laporan');
        $rs = [];
        for ($i=0; $i < count($G_format_laporan); $i++) { 
            $ID = $G_format_laporan[$i]['ID'];
            $sbj = $G_format_laporan[$i]['Nama_format'];
            $rs[$i]=[
                'subject' => $sbj,
            ];

            $query = $this->db->query(
                'select * from db_research.litabmas_isi_laporan
                where   ID_litabmas = '.$ID_Litabmas.'
                and User_create = "'.$NIP.'"
                and Jenis_format = '.$ID.'
                '
            )->result_array();

            $rs[$i]['content'] = (count($query)>0) ? $query[0]['Isi_laporan'] : '';
        }

        return $rs;

    }

}    