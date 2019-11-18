<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_general extends CI_Model {

    function __construct()
    {
        parent::__construct();
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
  
}
