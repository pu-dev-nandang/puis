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

        	$param[] = array("field"=>"(aut_s.Name","data"=>" like '%".$search."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"aut_s.NPM","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"ps.Name","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"ps.NameEng","data"=>" like '%".$search."%' )","filter"=>"OR",);    
        }

        if(!empty($data_arr['student'])){
        	$param[] = array("field"=>"(aut_s.Name","data"=>" like '%".$data_arr['student']."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"aut_s.NPM","data"=>" like '%".$data_arr['student']."%' ","filter"=>"OR",);
        	$param[] = array("field"=>"aut_s.EmailPU","data"=>" like '%".$data_arr['student']."%') ","filter"=>"OR",);
        }        
        if(!empty($data_arr['class_of'])){
        	$param[] = array("field"=>"aut_s.Year","data"=>" =".$data_arr['class_of']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['study_program'])){
        	$param[] = array("field"=>"ps.ID","data"=>" =".$data_arr['study_program']." ","filter"=>"AND",);    
        }
        if(!empty($data_arr['status'])){
        	$param[] = array("field"=>"ss.CodeStatus","data"=>" =".$data_arr['status']." ","filter"=>"AND",);    
        }


    	$data = array();
    	$totalData = $this->Globalinformation_model->fetchStudent($param)->result();
    	$result = $this->Globalinformation_model->fetchStudent($param,$reqdata['start'],$reqdata['length'])->result();
    	$no = $reqdata['start'] + 1;
    	foreach ($result as $v) {
    		$detailStudent = $this->General_model->fetchData("ta_".$v->Year.".students",array("NPM"=>$v->NPM))->row();
    		
    		$url_image = 'uploads/students/ta_'.$v->Year.'/'.$detailStudent->Photo;
    		$srcImg =  base_url('images/icon/userfalse.png');
            if($detailStudent->Photo != '' && $detailStudent->Photo != null){
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
    		$nestedData[] = "<center>".$v->Year."</center>";
    		$nestedData[] = $v->ProdiNameEng;
    		$nestedData[] = $v->StatusStudent;
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
        	$param[] = array("field"=>"aut_s.NPM","data"=>" =".$data_arr['NPM']." ","filter"=>"AND");    
        	$isExist = $this->Globalinformation_model->fetchStudent($param)->row();
        	if(!empty($isExist)){
        		$isExist->detailTA = $this->Globalinformation_model->detailStudent("ta_".$isExist->Year.".students",array("a.NPM"=>$data_arr['NPM']))->row();
        		$data['detail'] = $isExist;
        		$data['profilepic'] = null;
        		if(!empty($isExist->detailTA)){
	        		$url_image = 'uploads/students/ta_'.$isExist->Year.'/'.$isExist->detailTA->Photo;
		    		$srcImg =  base_url('images/icon/userfalse.png');
		            if($isExist->detailTA->Photo != '' && $isExist->detailTA->Photo != null){
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
        	$param[] = array("field"=>"(em.NIP","data"=>" like '%".$search."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"em.NIDN","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"ps.NameEng","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"em.Name","data"=>" like '%".$search."%' )","filter"=>"OR",);    
        }        
        if(!empty($data_arr['position'])){
        	$param[] = array("field"=>"em.PositionMain","data"=>" like '14%".$data_arr['position']."' ","filter"=>"AND",);    
        }/*else{
        	$param[] = array("field"=>"(em.PositionMain","data"=>" ='14.5' ","filter"=>"AND",);
	    	$param[] = array("field"=>"em.PositionMain","data"=>" ='14.6' ","filter"=>"OR",);
	    	$param[] = array("field"=>"em.PositionMain","data"=>" ='14.7' ","filter"=>"OR",);
	    	$param[] = array("field"=>"em.PositionOther1","data"=>" ='14.5' ","filter"=>"OR",);
	    	$param[] = array("field"=>"em.PositionOther2","data"=>" ='14.6' ","filter"=>"OR",);
	    	$param[] = array("field"=>"em.PositionOther3","data"=>" ='14.7') ","filter"=>"OR",);
        }*/
        if(!empty($data_arr['study_program'])){
        	$param[] = array("field"=>"em.ProdiID","data"=>" =".$data_arr['study_program']." ","filter"=>"AND",);    
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


    public function lecturersDetail(){
    	$data = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($data['token'],$key);
        if($data){
        	$param[] = array("field"=>"em.ID","data"=>" =".$data_arr['ID']." ","filter"=>"AND");    
        	$isExist = $this->Globalinformation_model->fetchLecturer($param)->row();
        	if(!empty($isExist)){
        		$url_image = './uploads/employees/'.$isExist->Photo;
	    		$srcImg =  base_url('images/icon/userfalse.png');
	            if($isExist->Photo != '' && $isExist->Photo != null){
	                $srcImg = (file_exists($url_image)) ? base_url('uploads/employees/'.$isExist->Photo) : base_url('images/icon/userfalse.png') ;
	            }
	            $data['profilePIC'] = $srcImg;

        		$data['detail'] = $isExist;
        		$splitMPosition = explode(".", $isExist->PositionMain);
    			$data['positionMain'] = $this->General_model->fetchData("db_employees.position",array("ID"=>$splitMPosition[1]))->row();
    			if(!empty($isExist->PositionOther1)){
	    			$splitOTHPosition1 = explode(".", $isExist->PositionOther1);
	    			$data['othPositionDiv1'] = $this->General_model->fetchData("db_employees.division",array("ID"=>$splitOTHPosition1[0]))->row();
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
        $page = $this->load->view('dashboard/global-informations/employees/index',$data,true);
        $this->menu_global_informations($page);
    }


    public function employeesFetch(){
    	$reqdata = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
    	$param = array();

    	$param[] = array("field"=>"em.StatusEmployeeID","data"=>" = '-1' ","filter"=>"AND",);    
    	$param[] = array("field"=>"em.StatusEmployeeID","data"=>" = '1' ","filter"=>"OR",);    
    	$param[] = array("field"=>"em.StatusEmployeeID","data"=>" = '2' ","filter"=>"OR",);    
    	
    	
    	if(!empty($reqdata['search']['value']) ) {
            $search = $reqdata['search']['value'];

        	$param[] = array("field"=>"(em.NIP","data"=>" like '%".$search."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"em.NIDN","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"ps.NameEng","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"em.Name","data"=>" like '%".$search."%' )","filter"=>"OR",);    
        }

        if(!empty($data_arr['lecturer'])){
        	$param[] = array("field"=>"(em.NIP","data"=>" like '%".$search."%' ","filter"=>"AND",);    
        	$param[] = array("field"=>"em.NIDN","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"ps.NameEng","data"=>" like '%".$search."%' ","filter"=>"OR",);    
        	$param[] = array("field"=>"em.Name","data"=>" like '%".$search."%' )","filter"=>"OR",);    
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
    		$nestedData[] = $division->Division."<br>".$position->Description;
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
