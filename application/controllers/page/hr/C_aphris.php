<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_aphris extends HR_Controler {

    function __construct(){
        parent::__construct();
        $this->load->model(array('hr/m_hr','General_model'));
    }


    public function temp($content){
        parent::template($content);
    }


    public function masterData(){
    	$data= array();
    	$dbName = $this->uri->segment(3);
    	$data['title'] = $dbName;
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/aphris/index',$data,true);
        $this->temp($page);
    }


    public function masterFetch(){
    	$reqdata = $this->input->post();
    	$json_data = array();
        if($reqdata){
        	$key = "UAP)(*";
        	$data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
        	if(!empty($data_arr['DBNAME'])){
	        	$param = '';
	        	if(!empty($reqdata['search']['value']) ) {
	                $search = $reqdata['search']['value'];
	                $param = "name like '%".$search."%'";
	            }
	            $no = $reqdata['start'] + 1;
	            $getTotal = $this->General_model->countData("db_employees.".$data_arr['DBNAME'],(!empty($param) ? $param : array()))->row();
	            $total = (!empty($getTotal) ? $getTotal->Total : 0);
	            $results = $this->General_model->fetchData("db_employees.".$data_arr['DBNAME'],(!empty($param) ? $param : array()),null,null,$reqdata['start']."#".$reqdata['length'])->result();
		    	$json_data = array(
		            "draw"            => intval( $reqdata['draw'] ),
		            "recordsTotal"    => intval($total),
		            "recordsFiltered" => intval($total),
		            "data"            => (!empty($results) ? $results : 0)
		        );
		    }
        }

        $response = $json_data;
        echo json_encode($response);
    }


    public function masterDetail(){
    	$data = $this->input->post();
    	$json = array();
    	if($data){
    		$key = "UAP)(*";
        	$data_arr = (array) $this->jwt->decode($data['token'],$key);
        	if(!empty($data_arr['ID'])){
        		$isExist = $this->General_model->fetchData("db_employees.master_status",array("ID"=>$data_arr['ID']))->row();
        		if(!empty($isExist)){
        			$json = $isExist;
        		}
        	}
    	}
    	echo json_encode($json);
    }


    public function masterSave(){
    	$data = $this->input->post();
    	if($data){
    		$conditions = array("ID"=>$data['ID']);
    		$DBNAME = (!empty($data['DBNAME']) ? $data['DBNAME'] : null);
    		$message = "";
    		if(!empty($DBNAME)){
    			unset($data['DBNAME']);
	    		if(!empty($data['ID'])){
	    			$isExist = $this->General_model->fetchData("db_employees.".$DBNAME,$conditions)->row();
	    			if(!empty($isExist)){
	    				$data['editedby'] = $this->session->userdata('NIP');
	    				$update = $this->General_model->updateData("db_employees.".$DBNAME,$data,$conditions);
	    				$message = (($update) ? "Successfully":"Failed")." updated.";
	    			}else{
	    				$message = "Data not founded. Failed saved.";
	    			}
	    		}else{
	    			$data['createdby'] = $this->session->userdata('NIP');
	    			$insert = $this->General_model->insertData("db_employees.".$DBNAME,$data);
	    			$message = (($insert) ? "Successfully":"Failed")." saved.";
	    		}
    		}else{$message="Data master not founded.";}
    		$this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/master-aphris/'.$DBNAME));
    	}else{show_404();}
		
    }

}