<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_warehouse extends Globalclass {

    function __construct(){
        parent::__construct();
        $this->load->model(array("General_model","global-informations/Globalinformation_model",'general-affair/m_general_affair'));
    }


    private function temp($content){
        parent::template($content);
    }


    public function masterData(){
    	$myNIP = $this->session->userdata('NIP');
        $myName = $this->session->userdata('Name');
        $data['title'] = '';
        $content = $this->load->view('page/warehouse/master-data/index',$data,true);
        $this->temp($content);
    }


    public function fetchMaster(){
    	$reqdata = $this->input->post();
    	$json_data = array();
        if($reqdata){
        	$key = "UAP)(*";
        	$data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
        	if(!empty($data_arr['tablename'])){
	        	$param = '';
	        	if(!empty($reqdata['search']['value']) ) {
	                $search = $reqdata['search']['value'];
	                $param = "Name like '%".$search."%'";
	            }
	            $no = $reqdata['start'] + 1;
	            $getTotal = $this->General_model->countData("db_warehouse.".$data_arr['tablename'],(!empty($param) ? $param : array()))->row();
	            $total = (!empty($getTotal) ? $getTotal->Total : 0);
	            $results = $this->General_model->fetchData("db_warehouse.".$data_arr['tablename'],(!empty($param) ? $param : array()),null,null,(!empty($reqdata['length']) ? ($reqdata['start']."#".$reqdata['length']) : null) )->result();
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


    public function masterSave(){
    	$reqdata = $this->input->post();
    	$json = array();
    	if($reqdata){
    		$key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
            if(!empty($data_arr['POST'])){
                $parse = parse_str($data_arr['POST'],$output);
            	$tablename = $output['tablename'];
            	$message = "";
              	if(!empty($output['Name'])){
              		for ($i=0; $i < count($output['Name']); $i++) { 
              			$dataPost = array("Name"=>$output['Name'][$i], "Description"=>$output['Description'][$i]);
              			if(!empty($output['IsActive'][$i])){ $dataPost['IsActive']=$output['IsActive'][$i]; }
              			if(!empty($output['Code'][$i])){ $dataPost['Code']=$output['Code'][$i]; }

              			if(!empty($output['ID'][$i])){
              				$condition = array('ID'=>$output['ID'][$i]);
              				$isExist = $this->General_model->fetchData("db_warehouse.".$tablename,$condition)->row();
              				if(!empty($isExist)){
              					$execute = $this->General_model->updateData("db_warehouse.".$tablename,$dataPost,$condition);
              					$message = (($execute) ? "Successfully":"Failed")." updated.";
              				}
              			}else{
              				$execute = $this->General_model->insertData("db_warehouse.".$tablename,$dataPost);
              				$message = (($execute) ? "Successfully":"Failed")." saved.";
              			}
              		}
              	}else{$message="There is no datapost.";}

              	$json = array("message"=>$message,"tablename"=>$tablename);
          	}
      	}
      	echo json_encode($json);
    }
    
    public function goodReceive(){
    	$myNIP = $this->session->userdata('NIP');
        $myName = $this->session->userdata('Name');
        $data['title'] = '';
        $content = $this->load->view('page/warehouse/good-receive/index',$data,true);
        $this->temp($content);
    }

}