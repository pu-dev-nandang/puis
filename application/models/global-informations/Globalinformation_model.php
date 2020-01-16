<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Globalinformation_model extends CI_Model{

	/*public function fetchAllStudent($param='',$start='',$limit=''){
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

    	$queryYearIntake ="select Year from db_academic.semester group by Year order by Year asc";
    	$resultYearIntake  = $this->db->query($queryYearIntake)->result();
    	
    	$combineSql = "";
    	if(!empty($resultYearIntake)){
    		$combineSql = "select * from (";
    		$isunion = "";
    		$t = 1;
    		foreach ($resultYearIntake as $y) {
    			$combineSql .= "(select ta.*, ath.Password , ath.Password_Old, ath.EmailPU, ath.KTPNumber, ps.Name AS ProdiName, ps.NameEng AS ProdiNameEng, ps.DegreeEng as ProdiDegree, el.Name as ProdiEdu, ss.CodeStatus as CodeStatusStudent, ss.Description AS StatusStudent, 
								pg.Code AS ProdiGroup,fma.FormulirCode,fma.No_Ref,em.Name AS Mentor, em.NIP as MentorNIP, em.EmailPU AS MentorEmailPU, ps.TitleDegreeEng as ProdiTitle,
								ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(ta.DateOfBirth, '%Y-%m-%d'))/365) as Age
			                    from ta_".$y->Year.".students ta 
			                    left join db_academic.auth_students ath on (ath.NPM = ta.NPM)
								LEFT JOIN db_academic.program_study ps ON (ps.ID = ta.ProdiID)
								LEFT JOIN db_academic.prodi_group pg ON (pg.ID = ath.ProdiGroupID)
								LEFT JOIN db_academic.status_student ss ON (ss.ID = ta.StatusStudentID)
								LEFT JOIN db_academic.education_level el ON (el.ID = ps.EducationLevelID)
								LEFT JOIN db_academic.mentor_academic ma ON (ma.NPM=ta.NPM)
								LEFT JOIN db_employees.employees em ON (em.NIP=ma.NIP)
								LEFT JOIN (
								 select a.NPM,a.FormulirCode,a.GeneratedBy,a.DateTime as DateTimeGeneratedBy,dd.No_Ref
								 from db_admission.to_be_mhs as a
								 left join (
										 select FormulirCode,No_Ref from db_admission.formulir_number_offline_m
										 UNION
										 select FormulirCode,No_Ref from db_admission.formulir_number_online_m
								 ) dd on a.FormulirCode = dd.FormulirCode
								) as fma on fma.NPM = ta.NPM
			                    ) ".((count($resultYearIntake) > $t ) ? 'union':'')." ";
    			$t++;
    		}
    		$combineSql .= ") a {$where} order by NPM {$lims}";
    	}

    	$queryStudents = $combineSql;
    	$result = $this->db->query($queryStudents);
    	return $result;
    }*/



    public function fetchStudentsPS($single=false,$param='',$start='',$limit='',$order=''){
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

        $sorted = " order by ".(!empty($order) ? $order : " NPM desc");

        $psquery = 'call db_academic.fetchStudents("'.$where.'" , "'.$lims.'","'.$sorted.'")';
    	$query = $this->db->query($psquery);
        //var_dump($this->db->last_query());
    	if($single){
    		$value = $query->row();
    	}else{
    		$value = $query->result();
    	}
    	
        //limit execute time
        mysqli_next_result( $this->db->conn_id );
        $query->free_result(); 
        //end limit execute time
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


    public function fetchLecturer($param='',$start='',$limit='',$order=''){
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

        $sorted = " order by ".(!empty($order) ? $order : 'em.ID DESC');
        $string = "SELECT em.*, ps.NameEng AS ProdiNameEng, ps.DegreeEng as ProdiDegree, es.Description as EmpStatus, r.Religion as EmpReligion, le.Level as EmpLevelEduName, le.Description as EmpLevelDesc, lap.Position as EmpAcaName
				   FROM db_employees.employees em
				   LEFT JOIN db_academic.program_study ps ON (ps.ID = em.ProdiID)
				   LEFT JOIN db_employees.employees_status es ON (es.IDStatus = em.StatusLecturerID)
				   LEFT JOIN db_employees.religion r ON (r.IDReligion = em.ReligionID)
				   LEFT JOIN db_employees.level_education le ON (le.ID = em.LevelEducationID)
				   LEFT JOIN db_employees.lecturer_academic_position lap ON (lap.ID = em.LecturerAcademicPositionID)
                   {$where} {$sorted} {$lims} ";
        
        $value  = $this->db->query($string);
     	//var_dump($this->db->last_query());
     	return $value;
    }


    public function fetchEmployee($param='',$start='',$limit='',$order=''){
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

        $sorted = " order by ".(!empty($order) ? $order : 'em.ID DESC');
        
        $string = "SELECT em.*, ps.NameEng AS ProdiNameEng, ps.DegreeEng as ProdiDegree, es.Description as EmpStatus, r.Religion as EmpReligion, le.Level as EmpLevelEduName, le.Description as EmpLevelDesc, lap.Position as EmpAcaName
				   FROM db_employees.employees em
				   LEFT JOIN db_academic.program_study ps ON (ps.ID = em.ProdiID)
				   LEFT JOIN db_employees.employees_status es ON (es.IDStatus = em.StatusEmployeeID)
				   LEFT JOIN db_employees.religion r ON (r.IDReligion = em.ReligionID)
				   LEFT JOIN db_employees.level_education le ON (le.ID = em.LevelEducationID)
				   LEFT JOIN db_employees.lecturer_academic_position lap ON (lap.ID = em.LecturerAcademicPositionID)
                   {$where} {$sorted} {$lims} ";
        
        $value  = $this->db->query($string);
     	//var_dump($this->db->last_query());
     	return $value;
    }

    /* Added by Adhi 2020-01-16 */
    public function fetchTotalDataStudent($param){
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

          $psquery = 'call db_academic.fetchStudentsTotal("'.$where.'" )';
          $query = $this->db->query($psquery);

          $value = $query->row();

          mysqli_next_result( $this->db->conn_id );
          $query->free_result(); 

          return $value;
    }


    /* end Added by Adhi */

}