<?php
/*CREATED FILE  BY FEBRI @ DEC 2019*/
defined('BASEPATH') OR exit('No direct script access allowed');

class C_global_informations extends Globalclass {

    function __construct(){
        parent::__construct();
        $this->load->model(array("General_model","global-informations/Globalinformation_model","M_sendemail"));
    }


    public function index(){
    	$content = $this->load->view('dashboard/global-informations/index','',true);
        $this->template($content);
    }
    
    public function user_tabs_global_informations($page){
        $data['page'] = $page;
        $content = $this->load->view('dashboard/global-informations/user_tabs_global_informations',$data,true);
        $this->template($content);
    }
    
    public function blast_global_informations($page){
        $data['page'] = $page;
        $content = $this->load->view('dashboard/global-informations/blast_global_informations',$data,true);
        $this->template($content);
    }



/*STUDENTS*/

    public function students(){
    	$data['studyprogram'] = $this->General_model->fetchData("db_academic.program_study",array("Status"=>1))->result();
    	$data['statusstd'] = $this->General_model->fetchData("db_academic.status_student",array())->result();
    	$data['religion'] = $this->General_model->fetchData("db_admission.agama",array())->result();
    	$data['yearIntake'] = $this->General_model->fetchData("db_academic.semester",array(),null,null,null,"Year")->result();
        $page = $this->load->view('dashboard/global-informations/students/index',$data,true);
        $this->user_tabs_global_informations($page);
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
        }/*else{
        	$getCurrentSemes = $this->General_model->fetchData("db_academic.semester",array("status"=>1))->row();
        	$param[] = array("field"=>"ta.`ClassOf`","data"=>" =".$getCurrentSemes->Year." ","filter"=>"AND",);    
        }*/
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

        if(!empty($data_arr['sorted'])){
            $orderBy = $data_arr['sorted'];
        }else{
            $orderBy = " NPM DESC";
        }

    	$data = array();
    	$totalData = $this->Globalinformation_model->fetchStudentsPS(true,false,$param);
    	$TotalDataPS = (!empty($totalData) ? $totalData->Total : 0);
    	$result = $this->Globalinformation_model->fetchStudentsPS(false,false,$param,$reqdata['start'],$reqdata['length'],$orderBy);
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
    		$nestedData[] = "<p class='text-left'>".(!empty($v->PlaceOfBirth) ? $v->PlaceOfBirth.", ":"").date("d F Y", strtotime($v->DateOfBirth))."</p>";
    		$nestedData[] = "<p class='text-center'>".$v->religionName."</p>";
			$nestedData[] = "<p class='text-center'>".(($v->Gender == "L") ? 'Male':'Female')."</p>";
    		$nestedData[] = "<center>".$v->ClassOf."</center>";
    		$nestedData[] = $v->ProdiNameEng;
    		$nestedData[] = (($v->StatusStudentID == 1) ? $v->StatusStudent."<p>Graduated in ".(!empty($v->GraduationYear) ? $v->GraduationYear : date('Y',strtotime($v->GraduationDate))).", <br><small><i class='fa fa-graduation-cap'></i> ".date('D,d F Y',strtotime($v->GraduationDate))."</small></p>" : $v->StatusStudent);
    		$nestedData[] = $v->NPM;
    		$data[] = $nestedData;
    	}


    	$json_data = array(
            "draw"            => intval( $reqdata['draw'] ),
            "recordsTotal"    => intval($TotalDataPS),
            "recordsFiltered" => intval($TotalDataPS),
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
        	$isExist = $this->Globalinformation_model->fetchStudentsPS(false,true,$param);
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
	            }else{$data['profilepic'] = base_url('images/icon/userfalse.png');}
        	}
        	$this->load->view('dashboard/global-informations/students/detail',$data);
        }else{show_404();}
    }


    public function fetchStudentScore(){
    	$data = $this->input->post();
    	$json = array();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($data['token'],$key);
        if($data){
        	
            $transcript = $this->Globalinformation_model->fetchStudentTranscript($data_arr['NPM']);
            //$transcript = $this->Globalinformation_model->fetchStudentTranscript("21140014");
            
            $json = (!empty($transcript) ? $transcript : null);
            /*echo "<pre>";
            var_dump($json);die();*/

    	}

    	$response = $json;
    	echo json_encode($response);

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
        $this->user_tabs_global_informations($page);
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

        if(!empty($data_arr['sorted'])){
            $orderBy = $data_arr['sorted'];
        }else{
            $orderBy = " em.ID DESC";
        }

        $param[] = array("field"=>"em.PositionMain","data"=>" like'14.%' ","filter"=>"AND",);

    	$data = array();
    	$totalData = $this->Globalinformation_model->fetchLecturer(true,$param)->row();
    	$TotalData = (!empty($totalData) ? $totalData->Total : 0);
    	$result = $this->Globalinformation_model->fetchLecturer(false,$param,$reqdata['start'],$reqdata['length'],$orderBy)->result();
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
    		$nestedData[] = (!empty($v->PlaceOfBirth) ? $v->PlaceOfBirth.', ' : '').date("d F Y",strtotime($v->DateOfBirth));
    		$nestedData[] = $v->EmpReligion;
    		$nestedData[] = (!empty($v->Gender) ? (($v->Gender == "L") ? "Male":"Female") : "");
    		$nestedData[] = $v->EmpLevelDesc;
    		$nestedData[] = $position->Description;
    		$nestedData[] = $v->ProdiNameEng;
    		$nestedData[] = (!empty($v->StatusLecturerID) ? $v->EmpStatus : "Non Active");
    		$data[] = $nestedData;
    	}


    	$json_data = array(
            "draw"            => intval( $reqdata['draw'] ),
            "recordsTotal"    => intval($TotalData),
            "recordsFiltered" => intval($TotalData),
            "data"            => $data
        );

        echo json_encode($json_data);
    }


    public function lecturersDetail(){
    	$data = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($data['token'],$key);
        if($data){
        	$param[] = array("field"=>"em.ID","data"=>" =".$data_arr['ID']." ","filter"=>"AND");    
        	$isExist = $this->Globalinformation_model->fetchLecturer(false,$param)->row();
        	if(!empty($isExist)){
        		//$isExist->detailTA = $this->Globalinformation_model->detailStudent("ta_".$isExist->Year.".students",array("a.NPM"=>$data_arr['NPM']))->row();
        		$url_image = './uploads/employees/'.$isExist->Photo;
	    		$srcImg =  base_url('images/icon/userfalse.png');
	            if($isExist->Photo != '' || $isExist->Photo != null || !empty($isExist->Photo)){
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
        	$this->load->view('dashboard/global-informations/lecturers/detail',$data);
        }else{show_404();}
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
        $this->user_tabs_global_informations($page);
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

        if(!empty($data_arr['sorted'])){
            $orderBy = $data_arr['sorted'];
        }else{
            $orderBy = " em.ID DESC";
        }


    	$data = array();
    	$totalData = $this->Globalinformation_model->fetchEmployee(true,$param)->row();
    	$TotalData = (!empty($totalData) ? $totalData->Total : 0);
    	$result = $this->Globalinformation_model->fetchEmployee(false,$param,$reqdata['start'],$reqdata['length'],$orderBy)->result();
    	
    	$no = $reqdata['start'] + 1;
    	foreach ($result as $v) {
    		$url_image = './uploads/employees/'.$v->Photo;
    		$srcImg =  base_url('images/icon/userfalse.png');
            if($v->Photo != '' && $v->Photo != null){
                $srcImg = (file_exists($url_image)) ? base_url('uploads/employees/'.$v->Photo) : base_url('images/icon/userfalse.png') ;
            }

    		$empBox = '<div class="detail-user" data-user="'.$v->ID.'"> 
    						<img class="std-img img-rounded" src="'.$srcImg.'">
    						<p class="nip">'.$v->NIP.'</p>
    						<p class="name">'.$v->Name.'</p>
    						<p class="email"><i class="fa fa-envelope-o"></i> '.(!empty($v->EmailPU) ? $v->EmailPU : "-").'</p>
    					   </div>';
    		$nestedData = array();    		
    		$nestedData[] = ($no++);
    		$nestedData[] = $empBox;
    		$nestedData[] = (!empty($v->PlaceOfBirth) ?$v->PlaceOfBirth.', ':'' ).date("d F Y",strtotime($v->DateOfBirth)).(!empty($v->isMyBirthday) ? "<p class='bday'>It's My Birthday</p>" : "");
    		$nestedData[] = "<center>".$v->EmpReligion.'</center>';
    		$nestedData[] = "<center>".(!empty($v->Gender) ? (($v->Gender == "L") ? "Male":"Female") : "").'</center>';
    		$nestedData[] = $v->EmpLevelEduName;
    		$nestedData[] = "<b>".$v->DivisionMain."</b><br>".$v->PositionMain;
    		$nestedData[] = (!empty($v->StatusEmployeeID) ? $v->EmpStatus : "Non Active");
    		$data[] = $nestedData;
    	}


    	$json_data = array(
            "draw"            => intval( $reqdata['draw'] ),
            "recordsTotal"    => intval($TotalData),
            "recordsFiltered" => intval($TotalData),
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
        	$isExist = $this->Globalinformation_model->fetchEmployee(false,$param)->row();
        	if(!empty($isExist)){
        		//$isExist->detailTA = $this->Globalinformation_model->detailStudent("ta_".$isExist->Year.".students",array("a.NPM"=>$data_arr['NPM']))->row();
        		$url_image = './uploads/employees/'.$isExist->Photo;
	    		$srcImg =  base_url('images/icon/userfalse.png');
	            if($isExist->Photo != '' && $isExist->Photo != null || !empty($isExist->Photo)){
	                $srcImg = (file_exists($url_image)) ? base_url('uploads/employees/'.$isExist->Photo) : base_url('images/icon/userfalse.png') ;
	            }
	            $data['profilePIC'] = $srcImg;
        		$data['detail'] = $isExist;
        		/*$splitMPosition = explode(".", $isExist->PositionMain);
    			$data['divisionMain'] = $this->General_model->fetchData("db_employees.division",array("ID"=>$splitMPosition[0]))->row();
    			$data['positionMain'] = $this->General_model->fetchData("db_employees.position",array("ID"=>$splitMPosition[1]))->row();*/
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


    public function getEmployeesBDay(){
        $json = array();
        $param[] = array("field"=>'DATE_FORMAT(em.DateOfBirth,"%m-%d") = DATE_FORMAT(now(),"%m-%d")',"data"=>" ","filter"=>"AND",);    
        $param[] = array("field"=>"es.Type","data"=>" = 'emp' ","filter"=>"AND",);    
        $result = $this->Globalinformation_model->fetchEmployee(false,$param)->result();
        $json = (!empty($result) ? $result : 0);
        echo json_encode($json);
    }
    
/*END EMPLOYEE*/


/*MESSAGE BLAST*/
    public function messageBlast(){
    	$data['title'] = "Message Blast";
    	$page = $this->load->view('dashboard/global-informations/message-blast/index',$data,true);
        $this->blast_global_informations($page);    	
    }
    

    public function messageBlastForm(){
    	$data['title'] = "Create New Message Blast";
        $data['subject'] = $this->General_model->fetchData("db_mail_blast.subject_type",array("IsActive"=>1))->result();
    	$page = $this->load->view('dashboard/global-informations/message-blast/form',$data,true);
        $this->blast_global_informations($page);    	
    }


    public function messageBlastFormParticipants(){
		$data['t'] = "dsd";
		echo $this->load->view('dashboard/global-informations/message-blast/formParticipants',$data,true);
	}


	public function messageBlastFilterForm(){
		$data = $this->input->post();
		if($data){
			$key = "UAP)(*";
        	$data_arr = (array) $this->jwt->decode($data['token'],$key);
        	if(!empty($data_arr['TYPE'])){
        		switch ($data_arr['TYPE']) {
        			case 'Student':
        				$this->filterFormStudents();
        				break;
    				case 'Lecturer':
        				$this->filterFormLecturer();
        				break;
    				case 'Employee':
        				$this->filterFormEmployee();
        				break;        			
        			default:
        				show_404();
        				break;
        		}
        	}else{show_404();}
        		
		}else{show_404();}
	}


	private function filterFormStudents(){
		$data['studyprogram'] = $this->General_model->fetchData("db_academic.program_study",array("Status"=>1))->result();
    	$data['statusstd'] = $this->General_model->fetchData("db_academic.status_student",array())->result();
    	$data['religion'] = $this->General_model->fetchData("db_admission.agama",array())->result();
    	$data['yearIntake'] = $this->General_model->fetchData("db_academic.semester",array(),null,null,null,"Year")->result();
		echo $this->load->view("dashboard/global-informations/message-blast/filter/Student",$data,true);
	}


	private function filterFormLecturer(){
		$data['studyprogram'] = $this->General_model->fetchData("db_academic.program_study",array("Status"=>1))->result();
    	$data['statusstd'] = $this->General_model->fetchData("db_employees.employees_status","Type = 'lec' or IDStatus = '-1'")->result();
    	$data['position'] = $this->General_model->fetchData("db_employees.position","ID=5 or ID=6 or ID=7")->result();
    	$data['religion'] = $this->General_model->fetchData("db_employees.religion",array())->result();
    	$data['level_education'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
		echo $this->load->view("dashboard/global-informations/message-blast/filter/Lecturer",$data,true);
	}


	private function filterFormEmployee(){
		$data['statusstd'] = $this->General_model->fetchData("db_employees.employees_status","Type = 'emp' or IDStatus = '-1' ")->result();
    	$data['division'] = $this->General_model->fetchData("db_employees.division",array("StatusDiv"=>1))->result();
    	$data['position'] = $this->General_model->fetchData("db_employees.position",array())->result();
        $data['religion'] = $this->General_model->fetchData("db_employees.religion",array())->result();
    	$data['level_education'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
		echo $this->load->view("dashboard/global-informations/message-blast/filter/Employee",$data,true);
	}


    public function messageBlastSend(){
        $data = $this->input->post();
        $json = array();
        $myname = $this->session->userdata('Name');
        $mynip = $this->session->userdata('NIP');
        if($data){
            $getCogMail = $this->General_model->fetchData("db_mail_blast.cog_limit",array())->row();
            $limit = (!empty($getCogMail) ? $getCogMail->limit : 0);
            $mail_bcc = (!empty($getCogMail) ? $getCogMail->mail_bcc : 'it@podomorouniversity.ac.id');
            $message = "";
            $receiver = $data["mail_receiver_to"];
            $receiver_cc = $data["mail_receiver-cc_to"];
            $mail_subject = $data["subject"];
            $mail_message = $data["message"];
            $totalMail = count($receiver) + count($receiver_cc) + 1;
            
            $status = false; $dataInsert = array();
            if(count($receiver) > $limit ){
                $finish = false;
                $storedMail = array_chunk($receiver, $limit);
                foreach ($storedMail as $s) {
                    $sendEmail = $this->M_sendemail->sendEmail($s,$mail_subject,null,null,null,null,$mail_message,null,null,$receiver_cc,$mail_bcc);
                    try {
                        $finish = ($sendEmail['status'] == 1) ? true:false;
                    } catch (Exception $e) {
                        $finish = false;
                    }                    
                }
                $message = "Mail sent ".(($finish) ? "successfully.":"failed.");
                $status = $finish;
                $dataInsert['isSend'] = $finish;
            }else{
                $sendEmail = $this->M_sendemail->sendEmail($receiver,$mail_subject,null,null,null,null,$mail_message,null,null,$receiver_cc,$mail_bcc);
                try {
                    $message = "Mail sent ".(($sendEmail['status'] == 1) ? "successfully.":"failed.");
                    $status = (($sendEmail['status'] == 1) ? true : false);
                    $dataInsert['isSend'] = $status;
                } catch (Exception $e) {
                    $message = "Mail sent failed. Internal server error.";
                    $status = false;
                    $dataInsert['isSend'] = $status;
                }
            }

            //insert to db
            if($status){                
                $dataInsert['SubjectTypeID'] = $data['typeSubject'];
                $dataInsert['mail_from'] = $data['mail_from'];
                $dataInsert['mail_to'] = json_encode($data['mail_receiver_to']);
                $dataInsert['mail_cc'] = json_encode($data['mail_receiver-cc_to']);
                $dataInsert['mail_bcc'] = $mail_bcc;
                $dataInsert['SubjectOth'] = $data['subject'];
                $dataInsert['MessageOth'] = $data['message'];
                $dataInsert['isFlag'] = 0;
                $dataInsert['isShow'] = 1;
                $dataInsert['createdby'] = $mynip."/".$myname;
                $insertMailBlast = $this->General_model->insertData("db_mail_blast.mail_blast",$dataInsert);
            }

            $this->session->set_flashdata("message",$message);
            redirect(site_url('global-informations/message-blast')); 
        }
    }


    public function fetchMyMail(){
        $data = $this->input->post();
        $mynip = $this->session->userdata('NIP');
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $conditions = "createdby like '".$mynip."%' and isShow = 1";
            $sortby = "created";
            if(!empty($data_arr['Filter'])){            
                $parse = parse_str($data_arr['Filter'],$output);
                if(!empty($output['keywords'])){
                    $conditions .= " and (mail_to like '%".$output['keywords']."%' or SubjectOth like '%".$output['keywords']."%' or MessageOth like '%".$output['keywords']."%' ) ";
                }
                if(!empty($output['sort_label'])){
                    $sortby = $output['sort_label'];
                }
            }
            $data['results'] = $this->General_model->fetchData("db_mail_blast.mail_blast",$conditions,$sortby,"desc")->result();
            $this->load->view("dashboard/global-informations/message-blast/list",$data);
        }
    }


    public function detailMail(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_mail_blast.mail_blast",array("ID"=>$data_arr['MAILID']))->row();
            if(!empty($isExist)){
                $isExist->mail_to = json_decode($isExist->mail_to,true);
                $isExist->mail_cc = json_decode($isExist->mail_cc,true);
                $isExist->created = date("d F Y, H:i:s",strtotime($isExist->created));                

                $json = $isExist;
            }
        }
        echo json_encode($json);
    }


    public function removeMail(){
        $data = $this->input->post();
        $myname = $this->session->userdata('Name');
        $mynip = $this->session->userdata('NIP');
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_mail_blast.mail_blast",array("ID"=>$data_arr['MAILID']))->row();
            if(!empty($isExist)){
                $update = $this->General_model->updateData("db_mail_blast.mail_blast",array("isShow"=>0,"editedby"=>$mynip."/".$myname),array("ID"=>$data_arr['MAILID']));
                $message = ($update) ? "Successfully updated":"Failed updated. Error ".$update;
            }else{
                $message = "Data is not founded. Try again.";
            }
            $json = array("message"=>$message);
        }
        echo json_encode($json);
    }

/*END MESSAGE BLAST*/


/*SUBJECT TYPE*/
    public function subjectType(){
        $data['title'] = "Subject Type";
        $page = $this->load->view('dashboard/global-informations/message-blast/subject-type/index',$data,true);
        $this->blast_global_informations($page);
    }


    public function subjectTypeFetch(){
        $reqdata = $this->input->post();
        if($reqdata){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
            $param = array();
            if(!empty($data_arr['Filter'])){            
                $parse = parse_str($data_arr['Filter'],$output);
                if(!empty($output['subject'])){
                    $param[] = array("field"=>"subject","data"=>" like '%".$output['subject']."%' ","filter"=>"AND",);
                }    
                if(!empty($output['status'])){
                    $param[] = array("field"=>"IsActive","data"=>" =".$output['status']." ","filter"=>"AND",);
                }
            }

            if(!empty($reqdata['search']['value']) ) {
                $search = $reqdata['search']['value'];
                $param[] = array("field"=>"subject","data"=>" like '%".$search."%' ","filter"=>"AND");
            }            
            
            $data = array();
            $totalData = $this->Globalinformation_model->fetchSubjectType(true,$param)->row();
            $TotalDataRS = (!empty($totalData) ? $totalData->Total : 0);
            $result = $this->Globalinformation_model->fetchSubjectType(false,$param,(!empty($reqdata['start']) ? $reqdata['start']:0),(!empty($reqdata['length']) ? $reqdata['length'] : 0))->result();
            $json_data = array(
                "draw"            => intval( $reqdata['draw'] ),
                "recordsTotal"    => intval($TotalDataRS),
                "recordsFiltered" => intval($TotalDataRS),
                "data"            => (!empty($result) ? $result : 0)
            );

        }else{$json_data=null;}

        $response = $json_data;
        echo json_encode($response);
    }


    public function subjectTypeForm(){
        $data = $this->input->post();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            if(!empty($data_arr['ID'])){
                $data['detail'] = $this->General_model->fetchData("db_mail_blast.subject_type",array("ID"=>$data_arr['ID']))->row();                
            }
        }
        $this->load->view('dashboard/global-informations/message-blast/subject-type/form',$data,false);
    }


    public function subjectTypeSave(){
        $data = $this->input->post();
        $mynip = $this->session->userdata('NIP');
        if($data){
            $message = "";
            unset($data['files']);
            if(!empty($data['ID'])){
                $conditions = array("ID"=>$data['ID']);
                $isExist = $this->General_model->fetchData("db_mail_blast.subject_type",$conditions)->row();
                if(!empty($isExist)){
                    $data['editedby'] = $mynip;
                    $update = $this->General_model->updateData("db_mail_blast.subject_type",$data,$conditions);
                    $message = (($update) ? "Successfully":"Failed")." updated.";
                }else{
                    $message = "Data is not founded. Try again";
                }

            }else{
                $data['createdby'] = $mynip;
                $insert = $this->General_model->insertData("db_mail_blast.subject_type",$data);
                $message = (($insert) ? "Successfully":"Failed")." saved.";
            }

            $this->session->set_flashdata("message",$message);
            redirect(site_url('global-informations/subject-type')); 

        }else{show_404();}
    }


    public function getSubjectTypeID(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            
            $isExist = $this->General_model->fetchData("db_mail_blast.subject_type",array("ID"=>$data_arr['ID']))->row();
            if(!empty($isExist)){
                $json = $isExist;
            }
        }

        echo json_encode($json);
    }
/*END SUBJECT TYPE*/

}
