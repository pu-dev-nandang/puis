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

    public function status(){
        $data= array();
        $data['title'] = "Master Status";
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/aphris/status',$data,true);
        $this->temp($page);
    }


    public function statusFetch(){
    	$reqdata = $this->input->post();
    	$json_data = array();
        if($reqdata){
        	$param = '';
        	if(!empty($reqdata['search']['value']) ) {
                $search = $reqdata['search']['value'];
                $param = "name like '%".$search."%'";
            }
            $no = $reqdata['start'] + 1;
            $results = $this->General_model->fetchData("db_employees.master_status",(!empty($param) ? $param : array()))->result();
            $total = (!empty($results) ? count($results) : 0);
	    	$json_data = array(
	            "draw"            => intval( $reqdata['draw'] ),
	            "recordsTotal"    => intval($total),
	            "recordsFiltered" => intval($total),
	            "data"            => (!empty($results) ? $results : 0)
	        );
        }

        $response = $json_data;
        echo json_encode($response);
    }


    public function statusDetail(){
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


    public function statusSave(){
    	$data = $this->input->post();
    	if($data){
    		$conditions = array("ID"=>$data['ID']);
    		$message = "";
    		if(!empty($data['ID'])){
    			$isExist = $this->General_model->fetchData("db_employees.master_status",$conditions)->row();
    			if(!empty($isExist)){
    				$update = $this->General_model->updateData("db_employees.master_status",$data,$conditions);    				
    				$message = (($update) ? "Successfully":"Failed")." updated.";
    			}else{
    				$message = "Data not founded. Failed saved.";
    			}
    		}else{
    			$insert = $this->General_model->insertData("db_employees.master_status",$data);
    			$message = (($insert) ? "Successfully":"Failed")." saved.";
    		}
    		$this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/master-aphris/master_status'));
    	}else{show_404();}
		
    }

}