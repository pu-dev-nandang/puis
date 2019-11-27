<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_general extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->library('JWT');
    }

    public function jwt_decode_department($EncodeDepartment){
        try {
            $key = "UAP)(*";
            $DepartmentID =  (string) $this->jwt->decode($EncodeDepartment,$key);
            return $DepartmentID;
        } catch (Exception $e) {
            echo json_encode($e);
            die();
        }
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

    public function DepartmentAbbr($DepartmentID)
    {
        $sql = 'select * from (
            select CONCAT("AC.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND,Code as Abbr from db_academic.program_study
            UNION
            select CONCAT("NA.",ID) as ID, Division as NameDepartment,Description as NameDepartmentIND,Abbreviation as Abbr from db_employees.division  
            UNION
            select CONCAT("FT.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND,Abbr from db_academic.faculty 
        )qdj
        where ID = "'.$DepartmentID.'"
        ';
        $query = $this->db->query($sql,array())->result_array();
        return $query[0]['Abbr'];
    }

    public function auth($DepartmentID = null,$NIP = null) // kabag dan IT serta Admin
    {
        $Bool = false;
        $DepartmentID = ($DepartmentID != null && $DepartmentID != '') ? $DepartmentID : $this->getDepartmentNow();
        $Explode = explode('.', $DepartmentID);
        $NIP = ($NIP != null && $NIP != '') ? $NIP : $this->session->userdata('NIP');
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
                        
            break;
            case 'AC':
                $ProdiID = $Explode[1];
                $sql = 'select * from db_academic.program_study where ID = '.$ProdiID.'';
                $query = $this->db->query($sql,array())->result_array();
                if (count($query) >0 && $query[0]['KaprodiID'] == $NIP) {
                    $Bool = true;
                }
                
            break;
            case 'FT':
                $IDFaculty = $Explode[1];
                $sql = 'select * from db_academic.faculty where ID = '.$IDFaculty.'';
                $query = $this->db->query($sql,array())->result_array();
                if (count($query) >0 && $query[0]['NIP'] == $NIP) {
                    $Bool = true;
                }
                
            break;
            default:
                # code...
                break;
        }

        if (!$Bool) {
            $sql = 'select * from db_ticketing.admin_register where NIP = "'.$NIP.'" and DepartmentID = "'.$DepartmentID.'" ';
            $query = $this->db->query($sql,array())->result_array();
            if (count($query) > 0) {
                $Bool = true;
            }
        }

        /* For IT */
        $PositionMain = $this->session->userdata('PositionMain');
        if (!$Bool && ($PositionMain['IDDivision'] == 12 || $DepartmentID == 'NA.12' ) ) { // IT all akses
            $Division = $Explode[1];
            $sql = 'SELECT a.NIP,a.Name,SPLIT_STR(a.PositionMain, ".", 1) as Division,
               SPLIT_STR(a.PositionMain, ".", 2) as Position,
                     a.StatusEmployeeID
                    FROM   db_employees.employees as a
                    where SPLIT_STR(a.PositionMain, ".", 1) = '.$Division.' and a.StatusEmployeeID != -1 and a.NIP = "'.$NIP.'" '; // kabag dan kasubag
            $query = $this->db->query($sql,array())->result_array();
            if (count($query)>0) {
                 $Bool = true;
            }
           
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

    public function getAllUserByDepartment($dataToken)
    {
        if (array_key_exists('DepartmentID', $dataToken)) {
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
              where a.StatusEmployeeID != -1
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

    public function getAuthDepartment(){
        $rs = [];
        $DepartmentID = $this->getDepartmentNow();
        // ALL Department
        $url = url_pas.'api/__getAllDepartementPU';
        $GetDepartment = $this->m_master->apiservertoserver($url);
        if ($DepartmentID == 'NA.12') {
            $rs = $GetDepartment;
        }
        else
        {
            for ($i=0; $i < count($GetDepartment); $i++) { 
                if ($GetDepartment[$i]['Code'] == $DepartmentID) {
                    $rs[] = $GetDepartment[$i];
                    break;
                }
            }
        }

        return $rs;
    }

    public function QueryDepartmentJoin($IDJoin,$aliasTable = 'qdj'){
      $sql = ' left join (
            select * from (
            select CONCAT("AC.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND,Code as Abbr from db_academic.program_study
            UNION
            select CONCAT("NA.",ID) as ID, Division as NameDepartment,Description as NameDepartmentIND,Abbreviation as Abbr from db_employees.division  
            UNION
            select CONCAT("FT.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND,Abbr from db_academic.faculty 
        )'.$aliasTable.')'.$aliasTable.' on '.$IDJoin.'='.$aliasTable.'.ID';
      return $sql;
    }

    public function ApiTicketing($url,$dataPass){
        $Authen = $this->m_master->showData_array('db_ticketing.rest_setting');
        $Apikey = $Authen[0]['Apikey'];
        $Hjwtkey = $Authen[0]['Hjwtkey'];
        $dataPass['auth'] = 's3Cr3T-G4N';
        $header[] = "Hjwtkey: ".$Hjwtkey."";
        $url = $url.'?apikey='.$Apikey;
        $token = $this->jwt->encode($dataPass,"UAP)(*");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "token=".$token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $pr = curl_exec($ch);
        $rs = (array) json_decode($pr,true);
        curl_close ($ch);
        return $rs;
    }
  
}
