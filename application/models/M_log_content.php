<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_log_content extends CI_Model{

	public function fetchLogContent($count=false,$param='',$start='',$limit='',$order=''){
        $where=''; $tablename ='';
        if(!empty($param)){
            $where = 'WHERE '; $conditionDate = '';
            $counter = 0; $notExistquery = '';
            foreach ($param as $key => $value) {
            	if($value['field'] == 'a.TypeContent'){
            		$tablename = $value['data'];
            	}
                if($counter==0){
                    $where = $where.$value['field']." ".$value['data'];                        
                }else{ 
                    $where = $where.$value['filter']." ".$value['field']." ".$value['data'];
                }
                $counter++;
            }
        }

        $lims="";
        if($start!="" || $limit!=""){
            $lims = " LIMIT {$start},{$limit}"; 
        }

        $groupby = ''; $sorted = ''; $order_by = '';

        $tablename2=trim(preg_replace('/[^ \w-]/', '', $tablename));
        $leftJoinTable = ""; $selectC="";
        if($tablename2 == 'knowledge_base'){
            $selectC         = 'd.Type as TypeName, b.Desc as Description, abc.NameDepartment as DivisionName';
            $leftJoinTable  .= 'LEFT JOIN db_employees.knowledge_base b on a.ContentID = b.ID ';
            $leftJoinTable  .= 'LEFT JOIN db_employees.kb_type d on d.ID = b.IDType ';
            $leftJoinTable  .= 'left join (
                                        select * from (
                                        select CONCAT("AC.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND,Code as Abbr from db_academic.program_study
                                        UNION
                                        select CONCAT("NA.",ID) as ID, Division as NameDepartment,Description as NameDepartmentIND,Abbreviation as Abbr from db_employees.division  
                                        UNION
                                        select CONCAT("FT.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND,Abbr from db_academic.faculty 
                                ) abc) abc on d.IDDivision = abc.ID';
        }else if($tablename2 == 'user_qna'){
            $selectC         = 'b.Type as TypeName, b.Questions as Description, d.Division as DivisionName ';
            $leftJoinTable   = 'LEFT JOIN db_employees.user_qna b on a.ContentID = b.Id ';
            $leftJoinTable  .= 'LEFT JOIN db_employees.division d on d.ID = Division_ID ';
        }

        if($count){
            $select = "count(DISTINCT a.NIP) as Total";
        }else{
            $select = "c.NIP, c.`Name`, DATE_FORMAT(a.ViewedAt,'%d-%M-%Y %H:%i:%s') as ViewedAt, {$selectC}, 
                       ( select count(NIP) from db_employees.log_countable_content where NIP = a.NIP and TypeContent = '{$tablename2}' and ContentID = a.ContentID  ) as totalRead ";
            $groupby = "group by a.NIP";
        }

        
        
        $string = " select {$select}
					from db_employees.log_countable_content a
					{$leftJoinTable}
					LEFT JOIN db_employees.employees c on c.NIP = a.NIP
                   {$where} {$groupby} {$order_by} {$lims}";
        $value  = $this->db->query($string);
        //var_dump($this->db->last_query());
        return $value;
	}


	public function fetchLogByEmployee($count=false,$param='',$start='',$limit='',$groupby='',$order_by=''){
        $where=''; $tablename ='';
        if(!empty($param)){
            $where = 'WHERE '; $conditionDate = '';
            $counter = 0; $notExistquery = '';
            foreach ($param as $key => $value) {
            	if($value['field'] == 'a.TypeContent'){
            		$tablename = $value['data'];
            	}
                if($counter==0){
                    $where = $where.$value['field']." ".$value['data'];                        
                }else{ 
                    $where = $where.$value['filter']." ".$value['field']." ".$value['data'];
                }
                $counter++;
            }
        }

        $lims="";
        if($start!="" || $limit!=""){
            $lims = " LIMIT {$start},{$limit}"; 
        }


        $tablename2=trim(preg_replace('/[^ \w-]/', '', $tablename));
        
        $leftJoinTable = ""; $selectC="";
        if($tablename2 == 'knowledge_base'){
        	$selectC = 'd.Type as TypeName, b.Desc as Description';
            $leftJoinTable = 'LEFT JOIN db_employees.knowledge_base b on a.ContentID = b.ID ';
            $leftJoinTable .= 'LEFT JOIN db_employees.kb_type d on d.ID = b.IDType ';
        }else if($tablename2 == 'user_qna'){
        	$selectC = 'b.Type as TypeName, b.Questions as Description';
            $leftJoinTable = 'LEFT JOIN db_employees.user_qna b on a.ContentID = b.Id';
        }

        if($count){
            $select = "count(a.NIP) as Total";
        }else{
            $select = "c.NIP, c.`Name`, DATE_FORMAT(a.ViewedAt,'%d-%M-%Y %H:%i:%s') as ViewedAt, {$selectC} ";
        }
        
        $string = " select {$select}
					from db_employees.log_countable_content a
					{$leftJoinTable}
					LEFT JOIN db_employees.employees c on c.NIP = a.NIP
                   {$where} {$groupby} {$order_by} {$lims}";
        $value  = $this->db->query($string);
        //var_dump($this->db->last_query());
        return $value;
	}

}