<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class General_model extends CI_Model{

	public function fetchData($tablename,$data,$idsort="", $sort="", $limit=null,$groupBy=null) {
		$this->db->from($tablename);
		$this->db->where($data);
		if(!empty($groupBy)){
			 $this->db->group_by($groupBy); 
		}
		$this->db->order_by($idsort,$sort);
		$this->db->limit($limit);
		$query = $this->db->get();
		return $query;
	}


	public function insertData($tablename,$data){
		try {
			$this->db->insert($tablename,$data);
			return true;
		} catch (Exception $e) {
			return $e."\n".$this->db->_error_message();;
		}
	}

	public function updateData($tablename,$data,$where){
		try {
			$this->db->where($where);
			$this->db->update($tablename, $data);
			return true;
		} catch (Exception $e) {
			return $e."\n".$this->db->_error_message();
		}
	}

	public function deleteData($tablename,$where){
        try {
            $this->db->where($where);
            $this->db->delete($tablename);
            return true;
        } catch (Exception $e) {
            return $e."\n".$this->db->_error_message();;
        }
    }


    public function callStoredProcedure($psquery){
		$query = $this->db->query($psquery);
		return $query;
	}
	
	public function callStoredProcedureWNoLimit($psquery){
		ini_set('max_execution_time', '0');
		$query = $this->db->query($psquery);

		$value = $query->row();

		//limit execute time
        /*mysqli_next_result( $this->db->conn_id );
        $query->free_result();*/ 
        //end limit execute time
		return $value;
	}


}