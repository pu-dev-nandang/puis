<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Globalinformation_model extends CI_Model{

	public function fetchStudent($param='',$start='',$limit=''){
        $where='';
        if(!empty($param)){
            $where = 'WHERE ';
            $counter = 0;
            foreach ($param as $key => $value) {
                if($counter==0){
                    $where = $where.$value['field']." ".$value['data'];
                }
                else{
                    $where = $where.$value['filter']." ".$value['field']." ".$value['data'];
                }
                $counter++;
            }
        }

        $lims="";
        if($start!="" || $limit!=""){
            $lims = " LIMIT {$start},{$limit}";	
        }

        $string = "SELECT aut_s.*, ps.Name AS ProdiName, ps.NameEng AS ProdiNameEng, ps.DegreeEng as ProdiDegree, el.Name as ProdiEdu, ss.Description AS StatusStudent, ss.ID AS StatusStudentID, 
                   pg.Code AS ProdiGroup,fma.FormulirCode,fma.No_Ref,em.Name AS Mentor, em.NIP as MentorNIP, em.EmailPU AS MentorEmailPU, ps.TitleDegreeEng as ProdiTitle
                   FROM db_academic.auth_students aut_s
                   LEFT JOIN db_academic.program_study ps ON (ps.ID = aut_s.ProdiID)
                   LEFT JOIN db_academic.prodi_group pg ON (pg.ID = aut_s.ProdiGroupID)
                   LEFT JOIN db_academic.status_student ss ON (ss.ID = aut_s.StatusStudentID)
                   LEFT JOIN db_academic.education_level el ON (el.ID = ps.EducationLevelID)
                   LEFT JOIN db_academic.mentor_academic ma ON (ma.NPM=aut_s.NPM)
                   LEFT JOIN db_employees.employees em ON (em.NIP=ma.NIP)
                   LEFT JOIN (
                     select a.NPM,a.FormulirCode,a.GeneratedBy,a.DateTime as DateTimeGeneratedBy,dd.No_Ref
                     from db_admission.to_be_mhs as a
                     left join (
                         select FormulirCode,No_Ref from db_admission.formulir_number_offline_m
                         UNION
                         select FormulirCode,No_Ref from db_admission.formulir_number_online_m
                     ) dd on a.FormulirCode = dd.FormulirCode
                   ) as fma on fma.NPM = aut_s.NPM
                   {$where} ORDER BY aut_s.NPM ASC {$lims} ";
        
        $value  = $this->db->query($string);
     	
     	return $value;
    }



    public function detailStudent($tablename,$data){
    	$this->db->select("a.*,b.Nama as religionName, c.ctr_name as nationalityName, e.ProvinceName");
    	$this->db->from($tablename." a");
    	$this->db->join("db_admission.agama b","b.ID=a.ReligionID","left");
    	$this->db->join("db_admission.country c","c.ctr_code=a.NationalityID","left");
    	$this->db->join("db_admission.province_region d","d.ID=a.ProvinceID","left");
    	$this->db->join("db_admission.province e","d.ProvinceID=e.ProvinceID","left");
    	$this->db->where($data);
    	$query = $this->db->get();
    	return $query;
    }

}