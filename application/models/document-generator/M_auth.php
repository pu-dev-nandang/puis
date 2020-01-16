<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->library('JWT');
    }

    public function __authDepartment(){
        $sql = 'select qdj.*,a.Level from  db_generatordoc.user_access_department as a 
                left join  (
                    select * from (
                    select CONCAT("AC.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND,Code as Abbr from db_academic.program_study
                    UNION
                    select CONCAT("NA.",ID) as ID, Division as NameDepartment,Description as NameDepartmentIND,Abbreviation as Abbr from db_employees.division  
                    UNION
                    select CONCAT("FT.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND,Abbr from db_academic.faculty 
                    ) qdj
                ) qdj on qdj.ID = a.Department
                where a.NIP = "'.$this->session->userdata('NIP').'"   

        ';
        $rs = $this->db->query($sql,array())->result_array();
        return $rs;
    }

    public function __cekAuth(){
        $DepartmentID = $this->session->userdata('DepartmentIDDocument');
        $NIP = $this->session->userdata('NIP');
        $sql = 'select * from db_generatordoc.user_access_department where NIP = "'.$NIP.'" and Department = "'.$DepartmentID.'" ';
        $query = $this->db->query($sql,array())->result_array();
        if (count($query) == 0) {
            show_404($log_error = TRUE); 
        }
        elseif (count($query) > 0 && $query[0]['Level'] != 'Admin') {
            show_404($log_error = TRUE); 
        }
        
    }
  
}
