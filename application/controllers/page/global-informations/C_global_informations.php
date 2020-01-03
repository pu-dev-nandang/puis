<?php
/*CREATED FILE  BY FEBRI @ DEC 2019*/
defined('BASEPATH') OR exit('No direct script access allowed');

class C_global_informations extends Globalclass {

    function __construct(){
        parent::__construct();
        $this->load->model(array("General_model","global-informations/Globalinformation_model"));
    }


    public function menu_global_informations($page){
        $data['page'] = $page;
        $content = $this->load->view('dashboard/global-informations/menu_global_informations',$data,true);
        $this->template($content);
    }

    /*STUDENTS*/

    public function students(){
    	$data['studyprogram'] = $this->General_model->fetchData("db_academic.program_study",array("Status"=>1))->result();
    	$data['statusstd'] = $this->General_model->fetchData("db_academic.status_student",array())->result();
    	$data['religion'] = $this->General_model->fetchData("db_admission.agama",array())->result();
    	$data['yearIntake'] = $this->General_model->fetchData("db_academic.semester",array(),null,null,null,"Year")->result();
        $page = $this->load->view('dashboard/global-informations/students/index',$data,true);
        $this->menu_global_informations($page);
    }


    public function studentsFetch(){
    	$reqdata = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
    	$param = array();
    	
    	if(!empty($reqdata['search']['value']) ) {
            $search = $reqdata['search']['value'];

        	$param[] = array("field"=>"(ta.`Name`","data"=>" like '%".$search."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"ta.`NPM`","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"ps.`NameEng`","data"=>" like '%".$search."%' )","filter"=>"OR",);    
        }

        if(!empty($data_arr['student'])){
        	$param[] = array("field"=>"(ta.`Name`","data"=>" like '%".$data_arr['student']."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"ta.`NPM`","data"=>" like '%".$data_arr['student']."%' ","filter"=>"OR",);
        	$param[] = array("field"=>"ath.`EmailPU`","data"=>" like '%".$data_arr['student']."%') ","filter"=>"OR",);
        }        
        if(!empty($data_arr['class_of'])){
        	$param[] = array("field"=>"ta.`ClassOf`","data"=>" =".$data_arr['class_of']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['study_program'])){
        	$param[] = array("field"=>"ta.`ProdiID`","data"=>" =".$data_arr['study_program']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['status'])){
        	$param[] = array("field"=>"ss.`CodeStatus`","data"=>" =".$data_arr['status']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['gender'])){
        	$param[] = array("field"=>"ta.`Gender`","data"=>" ='".$data_arr['gender']."' ","filter"=>"AND",);    
        }
        if(!empty($data_arr['religion'])){
        	$param[] = array("field"=>"ag.`ID`","data"=>" =".$data_arr['religion']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['graduation_year'])){
        	$param[] = array("field"=>"ath.`GraduationYear`","data"=>" =".$data_arr['graduation_year']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['graduation_start'])){
        	if(!empty($data_arr['graduation_end'])){
        		$param[] = array("field"=>"(ath.`GraduationDate`","data"=>" >= '".date("Y-m-d",strtotime($data_arr['graduation_start']))."' ","filter"=>"AND",);    
        		$param[] = array("field"=>"ath.`GraduationDate`","data"=>" <= '".date("Y-m-d",strtotime($data_arr['graduation_end']))."' )","filter"=>"AND",);    
        	}else{
        		$param[] = array("field"=>"ath.`GraduationDate`","data"=>" >= '".date("Y-m-d",strtotime($data_arr['graduation_start']))."' ","filter"=>"AND",);
        	}
        }        
        if(!empty($data_arr['birthdate_start'])){
        	if(!empty($data_arr['birthdate_end'])){
        		$param[] = array("field"=>"(ta.DateOfBirth","data"=>" >= '".date("Y-m-d",strtotime($data_arr['birthdate_start']))."' ","filter"=>"AND",);    
        		$param[] = array("field"=>"ta.DateOfBirth","data"=>" <= '".date("Y-m-d",strtotime($data_arr['birthdate_end']))."' )","filter"=>"AND",);    
        	}else{
        		$param[] = array("field"=>"ta.DateOfBirth","data"=>" >= '".date("Y-m-d",strtotime($data_arr['birthdate_start']))."' ","filter"=>"AND",);
        	}
        }

    	$data = array();
    	$totalData = $this->Globalinformation_model->fetchStudentsPS(false,$param);
    	$result = $this->Globalinformation_model->fetchStudentsPS(false,$param,$reqdata['start'],$reqdata['length']);
    	$no = $reqdata['start'] + 1;
    	foreach ($result as $v) {
    		//$detailStudent = $this->General_model->fetchData("ta_".$v->ClassOf.".students",array("NPM"=>$v->NPM))->row();
    		
    		$url_image = 'uploads/students/ta_'.$v->ClassOf.'/'.$v->Photo;
    		$srcImg =  base_url('images/icon/userfalse.png');
            if($v->Photo != '' && $v->Photo != null){
                $srcImg = (file_exists($url_image)) ? base_url($url_image) : base_url('images/icon/userfalse.png') ;
            }
    		$studentBox = '<div class="detail-user" data-user="'.$v->NPM.'"> 
    						<img class="std-img img-rounded" src="'.$srcImg.'"> 
    						<p class="npm">'.$v->NPM.'</p>
    						<p class="name">'.$v->Name.'</p>
    						<p class="email"><i class="fa fa-envelope-o"></i> '.(!empty($v->EmailPU) ? $v->EmailPU : "-").'</p>
    					   </div>';
    		$nestedData = array();
    		$nestedData[] = ($no++);
    		$nestedData[] = $studentBox;
    		$nestedData[] = "<center>".$v->ClassOf."</center>";
    		$nestedData[] = $v->ProdiNameEng;
    		$nestedData[] = $v->StatusStudent;
    		$nestedData[] = $v->NPM;
    		$data[] = $nestedData;
    	}


    	$json_data = array(
            "draw"            => intval( $reqdata['draw'] ),
            "recordsTotal"    => intval(count($totalData)),
            "recordsFiltered" => intval( count($totalData) ),
            "data"            => $data
        );

        echo json_encode($json_data);
    }


    public function studentsDetail(){
    	$data = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($data['token'],$key);
        if($data){
        	$param[] = array("field"=>"ath.NPM","data"=>" =".$data_arr['NPM']." ","filter"=>"AND");    
        	$isExist = $this->Globalinformation_model->fetchStudentsPS(true,$param);
        	if(!empty($isExist)){
        		//$isExist->detailTA = $this->Globalinformation_model->detailStudent("ta_".$isExist->Year.".students",array("a.NPM"=>$data_arr['NPM']))->row();
        		$data['detail'] = $isExist;
        		$data['profilepic'] = null;
        		if(!empty($isExist->Photo)){
	        		$url_image = 'uploads/students/ta_'.$isExist->ClassOf.'/'.$isExist->Photo;
		    		$srcImg =  base_url('images/icon/userfalse.png');
		            if($isExist->Photo != '' && $isExist->Photo != null){
		                $srcImg = (file_exists($url_image)) ? base_url($url_image) : base_url('images/icon/userfalse.png') ;
		            }
		            $data['profilepic'] = $srcImg;
	            }

        	}
        	$this->load->view('dashboard/global-informations/students/detail',$data);
        }else{show_404();}
    }

    /*END STUDENTS*/


    /*LECTURER*/
    public function lecturers(){
    	$data['studyprogram'] = $this->General_model->fetchData("db_academic.program_study",array("Status"=>1))->result();
    	$data['statusstd'] = $this->General_model->fetchData("db_employees.employees_status","Type = 'lec' or IDStatus = '-1'")->result();
    	$data['position'] = $this->General_model->fetchData("db_employees.position","ID=5 or ID=6 or ID=7")->result();
    	$data['religion'] = $this->General_model->fetchData("db_employees.religion",array())->result();
    	$data['level_education'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
        $page = $this->load->view('dashboard/global-informations/lecturers/index',$data,true);
        $this->menu_global_informations($page);
    }


    public function lecturersFetch(){
    	$reqdata = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
    	$param = array();
   	  	

    	if(!empty($reqdata['search']['value']) ) {
            $search = $reqdata['search']['value'];

        	$param[] = array("field"=>"(em.NIP","data"=>" like '%".$search."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"em.NIDN","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"ps.NameEng","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"em.Name","data"=>" like '%".$search."%' )","filter"=>"OR",);    
        }

        if(!empty($data_arr['lecturer'])){
        	$param[] = array("field"=>"(em.NIP","data"=>" like '%".$data_arr['lecturer']."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"em.NIDN","data"=>" like '%".$data_arr['lecturer']."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"ps.NameEng","data"=>" like '%".$data_arr['lecturer']."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"em.Name","data"=>" like '%".$data_arr['lecturer']."%' )","filter"=>"OR",);    
        }
        if(!empty($data_arr['position'])){
        	$param[] = array("field"=>"em.PositionMain","data"=>" like '14%".$data_arr['position']."' ","filter"=>"AND",);    
        }
        if(!empty($data_arr['study_program'])){
        	$param[] = array("field"=>"em.ProdiID","data"=>" =".$data_arr['study_program']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['religion'])){
        	$param[] = array("field"=>"em.ReligionID","data"=>" =".$data_arr['religion']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['gender'])){
        	$param[] = array("field"=>"em.Gender","data"=>" ='".$data_arr['gender']."' ","filter"=>"AND",);    
        }
        if(!empty($data_arr['level_education'])){
        	$param[] = array("field"=>"em.LevelEducationID","data"=>" =".$data_arr['level_education']." ","filter"=>"AND",);    
        }

        if(!empty($data_arr['birthdate_start'])){
        	if(!empty($data_arr['birthdate_end'])){
        		$param[] = array("field"=>"(em.DateOfBirth","data"=>" >= '".date("Y-m-d",strtotime($data_arr['birthdate_start']))."' ","filter"=>"AND",);    
        		$param[] = array("field"=>"em.DateOfBirth","data"=>" <= '".date("Y-m-d",strtotime($data_arr['birthdate_end']))."' )","filter"=>"AND",);    
        	}else{
        		$param[] = array("field"=>"em.DateOfBirth","data"=>" >= '".date("Y-m-d",strtotime($data_arr['birthdate_start']))."' ","filter"=>"AND",);
        	}
        }

        if(!empty($data_arr['status'])){
    		$param[] = array("field"=>"em.StatusLecturerID","data"=>" =".$data_arr['status']." ","filter"=>"AND",);            		
        }else{
        	$param[] = array("field"=>"(em.StatusLecturerID","data"=>" =3 ","filter"=>"AND",);
	    	$param[] = array("field"=>"em.StatusLecturerID","data"=>" =4 ","filter"=>"OR",);
	    	$param[] = array("field"=>"em.StatusLecturerID","data"=>" =5 ","filter"=>"OR",);
	    	$param[] = array("field"=>"em.StatusLecturerID","data"=>" =6 ","filter"=>"OR",);
	    	$param[] = array("field"=>"em.StatusLecturerID","data"=>" ='-1') ","filter"=>"OR",);
        }

        $param[] = array("field"=>"em.PositionMain","data"=>" like'14.%' ","filter"=>"AND",);

    	$data = array();
    	$totalData = $this->Globalinformation_model->fetchLecturer($param)->result();
    	$result = $this->Globalinformation_model->fetchLecturer($param,$reqdata['start'],$reqdata['length'])->result();
    	$no = $reqdata['start'] + 1;
    	foreach ($result as $v) {
    		if(!empty($v->PositionMain)){
    			$splitPosition = explode(".", $v->PositionMain);
    			$division = $this->General_model->fetchData("db_employees.division",array("ID"=>$splitPosition[0]))->row();
    			$position = $this->General_model->fetchData("db_employees.position",array("ID"=>$splitPosition[1]))->row();
    		}
    		$url_image = './uploads/employees/'.$v->Photo;
    		$srcImg =  base_url('images/icon/userfalse.png');
            if($v->Photo != '' && $v->Photo != null){
                $srcImg = (file_exists($url_image)) ? base_url('uploads/employees/'.$v->Photo) : base_url('images/icon/userfalse.png') ;
            }
    		$lecturerBox = '<div class="detail-user" data-user="'.$v->ID.'"> 
    						<img class="std-img img-rounded" src="'.$srcImg.'"> 
    						<p class="nip">'.$v->NIP.'</p>
    						<p class="name">'.$v->Name.'</p>
    						<p class="email"><i class="fa fa-envelope-o"></i> '.(!empty($v->EmailPU) ? $v->EmailPU : "-").'</p>
    					   </div>';
    		$nestedData = array();    		
    		$nestedData[] = ($no++);
    		$nestedData[] = $lecturerBox;
    		$nestedData[] = $position->Description;
    		$nestedData[] = $v->ProdiNameEng;
    		$nestedData[] = (!empty($v->StatusLecturerID) ? $v->EmpStatus : "Non Active");
    		$data[] = $nestedData;
    	}


    	$json_data = array(
            "draw"            => intval( $reqdata['draw'] ),
            "recordsTotal"    => intval(count($totalData)),
            "recordsFiltered" => intval( count($totalData) ),
            "data"            => $data
        );

        echo json_encode($json_data);
    }


    /*END LECTURER*/


    /*EMPLOYEE*/
    public function employees(){
    	$data['statusstd'] = $this->General_model->fetchData("db_employees.employees_status","Type = 'emp' or IDStatus = '-1' ")->result();
    	$data['division'] = $this->General_model->fetchData("db_employees.division",array("StatusDiv"=>1))->result();
    	$data['position'] = $this->General_model->fetchData("db_employees.position",array())->result();
        $data['religion'] = $this->General_model->fetchData("db_employees.religion",array())->result();
    	$data['level_education'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
        $page = $this->load->view('dashboard/global-informations/employees/index',$data,true);
        $this->menu_global_informations($page);
    }


    public function employeesFetch(){
    	$reqdata = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
    	$param = array();
    	
    	if(!empty($reqdata['search']['value']) ) {
            $search = $reqdata['search']['value'];

        	$param[] = array("field"=>"(em.NIP","data"=>" like '%".$search."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"em.NIDN","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"ps.NameEng","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"em.Name","data"=>" like '%".$search."%' )","filter"=>"OR",);    
        }

        if(!empty($data_arr['staff'])){
        	$param[] = array("field"=>"(em.NIP","data"=>" like '%".$data_arr['staff']."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"ps.NameEng","data"=>" like '%".$data_arr['staff']."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"em.Name","data"=>" like '%".$data_arr['staff']."%' )","filter"=>"OR",);    
        }
        if(!empty($data_arr['division'])){
        	$param[] = array("field"=>"em.PositionMain","data"=>" like '".$data_arr['division'].".%' ","filter"=>"AND",);    
        }
        if( !empty($data_arr['division']) && !empty($data_arr['position'])){
        	$param[] = array("field"=>"em.PositionMain","data"=>" = '".$data_arr['division'].".".$data_arr['position']."' ","filter"=>"AND",);    
        }
        if(!empty($data_arr['study_program'])){
        	$param[] = array("field"=>"em.ProdiID","data"=>" =".$data_arr['study_program']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['status'])){
    		$param[] = array("field"=>"em.StatusEmployeeID","data"=>" =".$data_arr['status']." ","filter"=>"AND",);            		
        }else{
        	$param[] = array("field"=>"(em.StatusEmployeeID","data"=>" != '-2') ","filter"=>"AND",);    
        	/*$param[] = array("field"=>"(em.StatusEmployeeID","data"=>" = '-1' ","filter"=>"AND",);    
	    	$param[] = array("field"=>"em.StatusEmployeeID","data"=>" = '1' ","filter"=>"OR",);    
	    	$param[] = array("field"=>"em.StatusEmployeeID","data"=>" = '2') ","filter"=>"OR",);*/
        }

        if(!empty($data_arr['religion'])){
        	$param[] = array("field"=>"em.ReligionID","data"=>" =".$data_arr['religion']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['gender'])){
        	$param[] = array("field"=>"em.Gender","data"=>" ='".$data_arr['gender']."' ","filter"=>"AND",);    
        }
        if(!empty($data_arr['level_education'])){
        	$param[] = array("field"=>"em.LevelEducationID","data"=>" =".$data_arr['level_education']." ","filter"=>"AND",);    
        }

        if(!empty($data_arr['birthdate_start'])){
        	if(!empty($data_arr['birthdate_end'])){
        		$param[] = array("field"=>"(em.DateOfBirth","data"=>" >= '".date("Y-m-d",strtotime($data_arr['birthdate_start']))."' ","filter"=>"AND",);    
        		$param[] = array("field"=>"em.DateOfBirth","data"=>" <= '".date("Y-m-d",strtotime($data_arr['birthdate_end']))."' )","filter"=>"AND",);    
        	}else{
        		$param[] = array("field"=>"em.DateOfBirth","data"=>" >= '".date("Y-m-d",strtotime($data_arr['birthdate_start']))."' ","filter"=>"AND",);
        	}
        }


    	$data = array();
    	$totalData = $this->Globalinformation_model->fetchEmployee($param)->result();
    	$result = $this->Globalinformation_model->fetchEmployee($param,$reqdata['start'],$reqdata['length'])->result();
    	
    	$no = $reqdata['start'] + 1;
    	foreach ($result as $v) {
    		if(!empty($v->PositionMain)){
    			$splitPosition = explode(".", $v->PositionMain);
    			$division = $this->General_model->fetchData("db_employees.division",array("ID"=>$splitPosition[0]))->row();
    			$position = $this->General_model->fetchData("db_employees.position",array("ID"=>$splitPosition[1]))->row();
    		}
    		$url_image = './uploads/employees/'.$v->Photo;
    		$srcImg =  base_url('images/icon/userfalse.png');
            if($v->Photo != '' && $v->Photo != null){
                $srcImg = (file_exists($url_image)) ? base_url('uploads/employees/'.$v->Photo) : base_url('images/icon/userfalse.png') ;
            }

    		$lecturerBox = '<div class="detail-user" data-user="'.$v->ID.'"> 
    						<img class="std-img img-rounded" src="'.$srcImg.'">
    						<p class="nip">'.$v->NIP.'</p>
    						<p class="name">'.$v->Name.'</p>
    						<p class="email"><i class="fa fa-envelope-o"></i> '.(!empty($v->EmailPU) ? $v->EmailPU : "-").'</p>
    					   </div>';
    		$nestedData = array();    		
    		$nestedData[] = ($no++);
    		$nestedData[] = $lecturerBox;
    		$nestedData[] = "<b>".$division->Division."</b><br>".$position->Description;
    		$nestedData[] = (!empty($v->StatusEmployeeID) ? $v->EmpStatus : "Non Active");
    		$data[] = $nestedData;
    	}


    	$json_data = array(
            "draw"            => intval( $reqdata['draw'] ),
            "recordsTotal"    => intval(count($totalData)),
            "recordsFiltered" => intval( count($totalData) ),
            "data"            => $data
        );

        echo json_encode($json_data);
    }


    public function employeesDetail(){
    	$data = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($data['token'],$key);
        if($data){
        	$param[] = array("field"=>"em.ID","data"=>" =".$data_arr['ID']." ","filter"=>"AND");    
        	$isExist = $this->Globalinformation_model->fetchEmployee($param)->row();
        	if(!empty($isExist)){
        		//$isExist->detailTA = $this->Globalinformation_model->detailStudent("ta_".$isExist->Year.".students",array("a.NPM"=>$data_arr['NPM']))->row();
        		$url_image = './uploads/employees/'.$isExist->Photo;
	    		$srcImg =  base_url('images/icon/userfalse.png');
	            if($isExist->Photo != '' && $isExist->Photo != null){
	                $srcImg = (file_exists($url_image)) ? base_url('uploads/employees/'.$isExist->Photo) : base_url('images/icon/userfalse.png') ;
	            }
	            $data['profilePIC'] = $srcImg;
        		$data['detail'] = $isExist;
        		$splitMPosition = explode(".", $isExist->PositionMain);
    			$data['divisionMain'] = $this->General_model->fetchData("db_employees.division",array("ID"=>$splitMPosition[0]))->row();
    			$data['positionMain'] = $this->General_model->fetchData("db_employees.position",array("ID"=>$splitMPosition[1]))->row();
    			if(!empty($isExist->PositionOther1)){
	    			$splitOTHPosition1 = explode(".", $isExist->PositionOther1);
	    			$data['othPositionDiv1'] = $this->General_model->fetchData("db_employees.division",array("ID"=>(!empty($splitOTHPosition1[0]) ? $splitOTHPosition1[0] : null)))->row();
	    			$data['othPosition1'] = $this->General_model->fetchData("db_employees.position",array("ID"=>$splitOTHPosition1[1]))->row();
    			}
    			if(!empty($isExist->PositionOther2)){
	    			$splitOTHPosition2 = explode(".", $isExist->PositionOther2);
	    			$data['othPositionDiv2'] = $this->General_model->fetchData("db_employees.division",array("ID"=>$splitOTHPosition2[0]))->row();
	    			$data['othPosition2'] = $this->General_model->fetchData("db_employees.position",array("ID"=>$splitOTHPosition2[1]))->row();
    			}
    			if(!empty($isExist->PositionOther3)){
	    			$splitOTHPosition3 = explode(".", $isExist->PositionOther3);
	    			$data['othPositionDiv3'] = $this->General_model->fetchData("db_employees.division",array("ID"=>$splitOTHPosition3[0]))->row();
	    			$data['othPosition3'] = $this->General_model->fetchData("db_employees.position",array("ID"=>$splitOTHPosition3[1]))->row();
    			}
        	}
        	$this->load->view('dashboard/global-informations/employees/detail',$data);
        }else{show_404();}
    }
    
    /*END EMPLOYEE*/


}
