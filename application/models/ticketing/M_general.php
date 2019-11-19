<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_general extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
    }

    public function getDepartmentNow(){
        $str = '';
        $Department= 'NA.'.$this->session->userdata('IDdepartementNavigation');
        if ($this->session->userdata('IDdepartementNavigation') == 15 || $this->session->userdata('IDdepartementNavigation') == 14) {
            $Department= 'AC.'.$this->session->userdata('prodi_active_id');
        }

        if ($this->session->userdata('IDdepartementNavigation') == 34) {
            $Department = 'FT.'.$this->session->userdata('faculty_active_id');
        }
        $str = $Department; 
        return $str;
    }

    public function auth() // kabag dan IT Administrator
    {
        $Bool = false;
        $DepartmentID = $this->getDepartmentNow();
        $Explode = explode('.', $DepartmentID);
        $NIP = $this->session->userdata('NIP');
        switch ($Explode[0]) {
            case 'NA':
                // get kabag
                $Division = $Explode[1];
                $sql = 'SELECT a.NIP,a.Name,SPLIT_STR(a.PositionMain, ".", 1) as Division,
                   SPLIT_STR(a.PositionMain, ".", 2) as Position,
                         a.StatusEmployeeID
                        FROM   db_employees.employees as a
                        where SPLIT_STR(a.PositionMain, ".", 1) = '.$Division.' and a.StatusEmployeeID != -1 and SPLIT_STR(a.PositionMain, ".", 2) in(11,12)'; // kabag dan kasubag
                $query = $this->db->query($sql,array())->result_array();
                for ($i=0; $i < count($query); $i++) { 
                   if ($query[$i]['NIP'] == $NIP) {
                     $Bool = true;
                     break;
                   }
                }

                if (!$Bool) {
                    $sql = 'select * from db_ticketing.admin_register where NIP = "'.$NIP.'" and DepartmentID = "'.$DepartmentID.'" ';
                    $query = $this->db->query($sql,array())->result_array();
                    if (count($query) > 0) {
                        $Bool = true;
                    }
                }        
            break;
            
            default:
                # code...
                break;
        }

        $PositionMain = $this->session->userdata('PositionMain');
        if (!$Bool && $PositionMain['IDDivision'] == 12) { // IT all akses
            $Bool = true;
        }

        return $Bool;
    }

    public function getAllUserAutoComplete($dataToken)
    {
        if (array_key_exists('search', $dataToken) && array_key_exists('DepartmentID', $dataToken)) {
            $search = $dataToken['search'];
            $DepartmentID = $dataToken['DepartmentID'];
            $Explode = explode('.', $DepartmentID);
            $AddWhere = '';
            switch ($Explode[0]) {
                case 'NA':
                    $Division = $Explode[1];
                    $AddWhere .= ' and SPLIT_STR(a.PositionMain, ".", 1) = '.$Division;
                    break;
                case 'AC':
                    $ProdiID = $Explode[1];
                    $AddWhere .= ' and a.ProdiID = '.$ProdiID;
                    break;
                case 'FT':
                    $FacultyID = $Explode[1];
                    $StrWhere = '';
                    $G_prodi = $this->m_master->caribasedprimary('db_academic.program_study','FacultyID',$FacultyID);
                    if (count($G_prodi) > 0 ) {
                        $StrWhere = 'and a.ProdiID IN (';
                        $StrWhere .= $G_prodi[0]['ID'];
                        for ($i=1; $i < count($G_prodi); $i++) { 
                            $StrWhere .= ','.$G_prodi[$i]['ID'];
                        }

                        $StrWhere .= ')';
                        $AddWhere .= $StrWhere;
                    }
                    
                    break;
                default:
                    # code...
                    break;
            }


            $sql = 'select CONCAT(a.Name," | ",a.NIP) as Name, a.NIP from db_employees.employees as a
              where (a.Name like "%'.$search.'%" or a.NIP like "%'.$search.'%" ) and a.StatusEmployeeID != -1
              '.$AddWhere.'
              ';
            $query=$this->db->query($sql, array())->result_array();
            return $query;
        }
        else
        {
            return array();
        }
        
    }
  
}
