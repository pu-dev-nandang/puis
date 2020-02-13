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


    public function company(){
    	$data= array();
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/aphris/company/index',$data,true);
        $this->temp($page);
    }


    public function companyFetch(){
    	$reqdata = $this->input->post();
    	$key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
    	$param = "";
    	
    	if(!empty($reqdata['search']['value']) ) {
            $search = $reqdata['search']['value'];

        	$param .= "Name like '%".$search."%' AND ";
        }

        $result = $this->General_model->fetchData("db_employees.master_company",(!empty($param) ? $param : array()))->result();
        //var_dump($this->db->last_query());
        $TotalData = (!empty($result)) ? count($result) : 0;
        $no = $reqdata['start'] + 1;
        $dataSub = array();
        if(!empty($result)){
        	foreach ($result as $v) {
        		$v->no = $no++;
        		$v->Industry = $this->General_model->fetchData("db_employees.master_industry_type",array("ID"=>$v->IndustryID))->row();
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


    public function companyForm(){
        $data= $this->input->post();
        $department = parent::__getDepartement();

        if($data){
        	$key = "UAP)(*";
	        $data_arr = (array) $this->jwt->decode($data['token'],$key);
        	$data['detail'] = $this->General_model->fetchData("db_employees.master_company",array("ID"=>$data_arr['ID']))->row();
        	$data['detail']->company  = $this->General_model->fetchData("db_employees.master_industry_type",array("ID"=>$data['detail']->IndustryID))->row();
        }
        $data['type'] = $this->General_model->fetchData("db_employees.master_industry_type",array("IsActive"=>1))->result();
        $this->load->view('page/'.$department.'/aphris/company/form',$data,false);
        
    }


    public function companySave(){
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
            redirect(site_url('human-resources/master-aphris/master_company'));	
    	}else{show_404();}
    }


    public function structureOrganization(){
        $data= array();
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/aphris/structure-organization/index',$data,true);
        $this->temp($page);
    }


    public function fetchStructurOrganization(){
        $data = $this->input->post();
        $json = array();
        //get parent sto
        $parent = $this->General_model->fetchData("db_employees.sto_temp",array("isActive"=>1,"parentID"=>0),"ID","desc")->row();
        if(!empty($parent)){
            $member = $this->m_hr->getMemberSTO(array("a.STOID"=>$parent->ID))->row();
            $name = (!empty($member) ? (!empty($member->TitleAhead) ? $member->TitleAhead.' ' : '').$member->Name.(!empty($member->TitleBehind) ? ' '.$member->TitleBehind : ''):'-');
            //get child
            $children = $this->getChild($parent->ID);
            $STO = array("name"=>$parent->title,
                         "title"=>$name,
                         "id"=>$parent->ID,
                         "children"=>(!empty($children) ? $children : null),
                         "className"=>"middle-level");
            
            $json = $STO;
        }
        echo json_encode($json);
    }

    private function getChild($id){
        $child = array();
        $getChild = $this->General_model->fetchData("db_employees.sto_temp",array("isActive"=>1,"parentID"=>$id))->result();
        foreach ($getChild as $c) {
            $member = $this->m_hr->getMemberSTO(array("a.STOID"=>$c->ID))->row();
            $name = (!empty($member) ? (!empty($member->TitleAhead) ? $member->TitleAhead : '').$member->Name.(!empty($member->TitleBehind) ? ' '.$member->TitleBehind : ''):'-');
            $mychild = $this->getChild($c->ID);
            $child[] = array("name"=>$c->title,
                             "title"=>$name, 
                             "id"=>$c->ID,
                             "children"=>(!empty($mychild) ? $mychild : null),
                             "className"=>"middle-level");
        }
        return $child;
    }

    public function detailSTO(){
        $data = $this->input->post();
        if($data){
            $department = parent::__getDepartement();
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $data['statusstd'] = $this->General_model->fetchData("db_employees.employees_status","Type = 'emp' or IDStatus = '-1' ")->result();
            $data['division'] = $this->General_model->fetchData("db_employees.division",array())->result();
            $data['position'] = $this->General_model->fetchData("db_employees.position",array())->result();
            $data['detail'] = $this->General_model->fetchData("db_employees.sto_temp",array("ID"=>$data_arr['ID']))->row();
            $data['level'] = $this->General_model->fetchData("db_employees.master_level",array("IsActive"=>1))->result();
            $getMember = $this->m_hr->getMemberSTO(array("a.STOID"=>$data_arr['ID']))->row();
            if(!empty($getMember)){
                $data['detail']->member = $getMember;
            }
            $this->load->view('page/'.$department.'/aphris/structure-organization/detail',$data);
        }else{show_404();}
    }


    public function filterUserSTO(){
        $this->load->model("global-informations/Globalinformation_model");
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $param = array();
            if(!empty($data_arr['employee'])){
                $param[] = array("field"=>"(em.NIP","data"=>" like '%".$data_arr['employee']."%' ","filter"=>"AND",);
                $param[] = array("field"=>"em.Name","data"=>" like '%".$data_arr['employee']."%' )","filter"=>"OR",);
            }
            if(!empty($data_arr['division'])){
                $param[] = array("field"=>"em.PositionMain","data"=>" like '".$data_arr['division'].".".(!empty($data_arr['position']) ? $data_arr['position'] : '%')."' ","filter"=>"AND",);
            }
            if(!empty($data_arr['position'])){
                $param[] = array("field"=>"em.PositionMain","data"=>" like '".(!empty($data_arr['division']) ? $data_arr['division'] : '%').".".$data_arr['position']."' ","filter"=>"AND",);
            }

            if(!empty($data_arr['status'])){
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                $sn = 1;
                foreach ($data_arr['status'] as $s) {
                    $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" ='".$s."'".((($sn < count($data_arr['status'])) ? ' OR ':'')) ,"filter"=> null );
                    $sn++;
                }
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }

            $result = $this->Globalinformation_model->fetchEmployee(false,$param)->result();
            $json = $result;
        }
        echo json_encode($json);
    }


    public function saveSTO(){
        $data = $this->input->post();
        if($data){
            $conditions = array("ID"=>$data['ID']);
            $isExist = $this->General_model->fetchData("db_employees.sto_temp",$conditions)->row();
            if(!empty($isExist)){
                $NIP = $data['NIP']; unset($data['NIP']);
                $LevelID = $data['LevelID']; unset($data['LevelID']);
                $jobTitle = $data['jobtitle']; unset($data['jobtitle']);
                $nodeID = 0;
                if(!empty($data['parentID'])){
                    unset($data['ID']);
                    $execute = $this->General_model->insertData("db_employees.sto_temp",$data);
                    $nodeID = $this->db->insert_id();
                }else{
                    $nodeID = $data['ID'];
                    $data['editedby'] = $this->session->userdata('NIP')."/".$this->session->userdata('Name');
                    $execute = $this->General_model->updateData("db_employees.sto_temp",$data,$conditions);
                }
                //insert member 
                $checkExisMember = $this->General_model->fetchData("db_employees.sto_rel_user",array("STOID"=>$nodeID,"IsActive"=>1))->row();
                if(!empty($checkExisMember)){
                    $updateMember = $this->General_model->updateData("db_employees.sto_rel_user",array("JobTitle"=>$jobTitle,"NIP"=>$NIP,"IsActive"=>$data['isActive']),$conditions);
                }else{
                    $insertMember = $this->General_model->insertData("db_employees.sto_rel_user",array("JobTitle"=>$jobTitle,"NIP"=>$NIP,"IsActive"=>$data['isActive'],"STOID"=>$nodeID,"LevelID"=>$LevelID));
                }
                $message = (($execute) ? "Successfully":"Failed")." saved.";
            }else{$message = "Node undefind. Try again";}
            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/master-aphris/structure-organization')); 
        }else{show_404();}
    }


    public function deleteNodeSTO(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $conditions = array("ID"=>$data_arr['ID']);
            $isExist = $this->General_model->fetchData("db_employees.sto_temp",$conditions)->row();
            if(!empty($isExist)){
                //check if this node has a children
                //$child = $this->getChild($isExist->ID);

                //change to be non active
                $delete = $this->General_model->updateData("db_employees.sto_temp",array("isActive"=>0,"editedby"=>$this->session->userdata('NIP')."/".$this->session->userdata('Name')),$conditions);
                $message = (($delete) ? "Successfully":"Failed")." removed.";
            }else{$message = "Node is not found.";}

            $json = array("message"=>$message);
        }

        echo json_encode($json);
    }

}