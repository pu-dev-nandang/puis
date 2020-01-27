<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_company extends HR_Controler {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('hr/m_hr','General_model'));
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function index(){
        $data= array();
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/company/index',$data,true);
        $this->temp($page);
    }
    
    public function fetch(){
    	$reqdata = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
    	$param = "";
    	
    	if(!empty($reqdata['search']['value']) ) {
            $search = $reqdata['search']['value'];

        	$param .= "Name like '%".$search."%' AND ";
        }

        if(!empty($data_arr['Category'])){
        	$param .= " Category = '".$data_arr['Category']."'";
        }
        $result = $this->General_model->fetchData("db_employees.master_company",$param)->result();
        //var_dump($this->db->last_query());
        $TotalData = (!empty($result)) ? count($result) : 0;
        $no = $reqdata['start'] + 1;
        $dataSub = array();
        if(!empty($result)){
        	foreach ($result as $v) {
        		$v->no = $no++;
        		$dataSub[] = $v;        		
        	}
        	$json_data = array(
	            "draw"            => intval( $reqdata['draw'] ),
	            "recordsTotal"    => intval($TotalData),
	            "recordsFiltered" => intval($TotalData),
	            "data"            => (!empty($dataSub) ? $dataSub : null)
	        );
        }else{$json_data=array();}

    	
        $response = $json_data;
        echo json_encode($response);
    }

    public function form(){
        $data= $this->input->post();
        $department = parent::__getDepartement();

        if($data){
        	$key = "UAP)(*";
	        $data_arr = (array) $this->jwt->decode($data['token'],$key);
        	$data['detail'] = $this->General_model->fetchData("db_employees.master_company",array("ID"=>$data_arr['ID']))->row();
        }
        $this->load->view('page/'.$department.'/company/form',$data,false);
        
    }


    public function save(){
    	$data = $this->input->post();
    	if($data){
    		if(!empty($data['ID'])){
    			$data['editedby'] = $this->session->userdata('NIP');
    			$update = $this->General_model->updateData("db_employees.master_company",$data,array("ID"=>$data['ID']));
    			$message = ($update) ? "Successfully updated.":"Failed update.".$update;
    		}else{
    			$data['createdby'] = $this->session->userdata('NIP');
    			$insert = $this->General_model->insertData("db_employees.master_company",$data);
    			$message = ($insert) ? "Successfully saved.":"Failed saved.".$insert;
    		}
    		$this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/master_insurance_company'));	
    	}else{show_404();}
    }


}