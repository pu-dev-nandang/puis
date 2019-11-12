<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class General_model extends CI_Model{

	public function fetchData($tablename,$data,$idsort="", $sort="", $limit=null) {
		$this->db->from($tablename);
		$this->db->where($data);
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

}