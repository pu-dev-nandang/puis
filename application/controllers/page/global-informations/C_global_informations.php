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
    	$totalData = $this->Globalinformation_model->fetchStudent($param);
    	$result = $this->Globalinformation_model->fetchStudent($param,$reqdata['start'],$reqdata['length'])->result();
    	$no = $reqdata['start'] + 1;
    	foreach ($result as $v) {
    		$detailStudent = $this->General_model->fetchData("ta_".$v->Year.".students",array("NPM"=>$v->NPM))->row();
    		$studentBox = '<div class="detail-user" data-user="'.$v->NPM.'"> 
    						<img class="std-img img-rounded" src="'.base_url().(!empty($detailStudent->Photo) ? 'uploads/students/ta_'.$v->Year.'/'.$detailStudent->Photo:"images/icon/userfalse.png").'"> 
    						<p class="npm">'.$v->NPM.'</p>
    						<p class="name">'.$v->Name.'</p>
    						<p class="email"><i class="fa fa-envelope-o"></i> '.(!empty($v->EmailPU) ? $v->EmailPU : "-").'</p>
    					   </div>';
    		$nestedData = array();    		
    		$nestedData[] = ($no++);
    		$nestedData[] = $studentBox;
    		$nestedData[] = "<center>".$v->Year."</center>";
    		$nestedData[] = $v->ProdiNameEng.$data_arr['class_of'];
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
        	}
        	$this->load->view('dashboard/global-informations/students/detail',$data);
        }else{show_404();}
    }

    /*END STUDENTS*/


    /*LECTURER*/
    public function lecturers(){
    	$data['studyprogram'] = $this->General_model->fetchData("db_academic.program_study",array("Status"=>1))->result();
    	$data['statusstd'] = $this->General_model->fetchData("db_academic.status_student",array())->result();
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
    	$totalData = $this->Globalinformation_model->fetchStudent($param);
    	$result = $this->Globalinformation_model->fetchStudent($param,$reqdata['start'],$reqdata['length'])->result();
    	$no = $reqdata['start'] + 1;
    	foreach ($result as $v) {
    		$detailStudent = $this->General_model->fetchData("ta_".$v->Year.".students",array("NPM"=>$v->NPM))->row();
    		$studentBox = '<div class="detail-user" data-user="'.$v->NPM.'"> 
    						<img class="std-img img-rounded" src="'.base_url().(!empty($detailStudent->Photo) ? 'uploads/students/ta_'.$v->Year.'/'.$detailStudent->Photo:"images/icon/userfalse.png").'"> 
    						<p class="npm">'.$v->NPM.'</p>
    						<p class="name">'.$v->Name.'</p>
    						<p class="email"><i class="fa fa-envelope-o"></i> '.(!empty($v->EmailPU) ? $v->EmailPU : "-").'</p>
    					   </div>';
    		$nestedData = array();    		
    		$nestedData[] = ($no++);
    		$nestedData[] = $studentBox;
    		$nestedData[] = "<center>".$v->Year."</center>";
    		$nestedData[] = $v->ProdiNameEng.$data_arr['class_of'];
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


    public function lecturersDetail(){
    	$data = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($data['token'],$key);
        if($data){
        	$param[] = array("field"=>"aut_s.NPM","data"=>" =".$data_arr['NPM']." ","filter"=>"AND");    
        	$isExist = $this->Globalinformation_model->fetchStudent($param)->row();
        	if(!empty($isExist)){
        		$isExist->detailTA = $this->Globalinformation_model->detailStudent("ta_".$isExist->Year.".students",array("a.NPM"=>$data_arr['NPM']))->row();
        		$data['detail'] = $isExist;
        	}
        	$this->load->view('dashboard/global-informations/lecturers/detail',$data);
        }else{show_404();}
    }
    /*END LECTURER*/


}
