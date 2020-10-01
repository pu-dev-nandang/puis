<?php
class M_sm_menu extends CI_Model{ 
	public function getAllSm_menubyid($id){
    	$query=$this->db->query("SELECT sm.*, sc.Name AS namechild, sc.Route AS routechild FROM db_it.sm_menu sm LEFT JOIN db_it.sm_menu_details sc ON sm.ID=sc.IDSM WHERE sm.ID = '$id'");
    	return $query;
    }

    public function getAllSm_menu(){
    	$query=$this->db->query("SELECT sm.*, sc.Name AS namechild, sc.Route AS routechild FROM db_it.sm_menu sm LEFT JOIN db_it.sm_menu_details sc ON sm.ID=sc.IDSM GROUP BY sm.ID  ORDER BY sm.ID");
    	return $query;
    }

	public function insertSm_menu($data){
    	if( array_key_exists('ID', $data) && $data['ID'] == -1){
			return -1;
		}else{
        	$this->db->insert('db_it.sm_menu', $data);
			return $this->db->insert_id();
		}
    }

	public function getSm_menu($data = array('ID' => -1))
	{
		if($data['ID'] == -1){
			return null;
		}else{
			$query = $this->db->get_where('db_it.sm_menu', $data);
			return $query->row_array();
		}
	}

	public function updateSm_menu($ID = -1, $data = array('Name' => ""))
	{
		if($ID == -1){
			return false;
		}else{
			$this->db->where('ID', $ID);
			$this->db->update('db_it.sm_menu', $data);
			return true;
		}
	}

    public function deleteSm_menu($id)
	{
		$data = array('ID' => $id);
		if($data['ID'] == -1){
			return false;
		}else{
			$this->db->delete('db_it.sm_menu', $data);
			return true;
		}
	}

	public function getTotalSm_child($id){
    	$query=$this->db->query("SELECT IFNULL(COUNT(sc.ID),0) AS total FROM db_it.sm_menu sm LEFT JOIN db_it.sm_menu_details sc ON sm.ID=sc.IDSM WHERE IDSM ='$id'");
    	return $query;
    }

	public function insertSm_child($data){
    	if(array_key_exists('ID', $data) && $data['ID'] == -1){
			return -1;
		}else{
        	$this->db->insert('db_it.sm_menu_details', $data);
			return $this->db->insert_id();
		}
    }

    public function getIDSM_child($id)
	{
		$query=$this->db->query("SELECT * FROM db_it.sm_menu_details WHERE IDSM ='$id'");
    	return $query;
		
	}

	public function getIDSM_child1($data = array('IDSM' => -1))
	{
		if($data['IDSM'] == -1){
			return null;
		}else{
			$query = $this->db->get_where('db_it.sm_menu_details', $data);
			return $query->row_array();
		}
	}

    public function deleteSm_child($id)
	{
		$data = array('ID' => $id);
		if($data['ID'] == -1){
			return false;
		}else{
			$this->db->delete('db_it.sm_menu_details', $data);
			return true;
		}
	}

	public function deleteChild_byIDSM($id)
	{
		$data = array('IDSM' => $id);
		if($data['IDSM'] == -1){
			return false;
		}else{
			$this->db->delete('db_it.sm_menu_details', $data);
			return true;
		}
	}

	public function getDivisionIDSM($iddiv, $idsm){
    	$query=$this->db->query("SELECT su.ID, su.IDSM AS idsm, su.IDDivision, d.Division AS Division FROM db_it.sm_user su RIGHT JOIN db_employees.division d ON su.IDDivision = d.ID WHERE d.ID = $iddiv AND idsm = $idsm GROUP BY d.ID");
    	return $query->row_array();
    }
    

    public function getAllDivision()
    {
    	$query=$this->db->query("SELECT * FROM db_employees.division");
    	return $query;
    }

    public function getsm_user($idsm)
    {
    	$query=$this->db->query("SELECT su.ID, su.IDSM AS idsm, su.IDDivision, d.Division AS Division FROM db_it.sm_user su LEFT JOIN db_employees.division d ON su.IDDivision = d.ID WHERE idsm= $idsm");
    	return $query->row_array();
    }

    public function getallsm_user($id)
    {
    	$query=$this->db->query("SELECT * FROM db_it.sm_user WHERE IDSM = $id ");
    	return $query->row_array();
    }

    public function insertSm_user($data){
    	if( array_key_exists('ID', $data) && $data['ID'] == -1){
			return -1;
		}else{
        	$this->db->insert('db_it.sm_user', $data);
			return $this->db->insert_id();
		}
    }

    public function deletesm_user($iddiv, $idsm)
    {
    	$this->db->where('IDSM', $idsm);
    	$this->db->where('IDDivision', $iddiv);
		$this->db->delete('db_it.sm_user');
		return true;
    }

}