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
        		$isExist = $this->General_model->fetchData("db_employees.".$data_arr['DBNAME'],array("ID"=>$data_arr['ID']))->row();
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
            $member = $this->m_hr->getMemberSTO(array("a.STOID"=>$c->ID))->result();
            $listMember = "";
            if(!empty($member)){
                foreach ($member as $m) {
                    $listMember .= "<p><u>".(!empty($m->TitleAhead) ? $m->TitleAhead : '').$m->Name.(!empty($m->TitleBehind) ? ' '.$m->TitleBehind : '')."</u>".(!empty($m->JobTitle) ? '<br>'.$m->JobTitle : '')."</p>";
                }
            }
            $mychild = $this->getChild($c->ID);
            $child[] = array("name"=>$c->title,
                             "title"=>$listMember, 
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
            $data['status'] = $this->General_model->fetchData("db_employees.master_status",array("IsActive"=>1))->result();
            $getMember = $this->m_hr->getMemberSTO(array("a.STOID"=>$data_arr['ID']))->result();
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
                $param[] = array("field"=>"(em.PositionMain","data"=>" like '".$data_arr['division'].".".(!empty($data_arr['position']) ? $data_arr['position'] : '%')."' ","filter"=>"AND",);
                $param[] = array("field"=>"em.PositionOther1","data"=>" like '".$data_arr['division'].".".(!empty($data_arr['position']) ? $data_arr['position'] : '%')."' ","filter"=>"OR",);
                $param[] = array("field"=>"em.PositionOther2","data"=>" like '".$data_arr['division'].".".(!empty($data_arr['position']) ? $data_arr['position'] : '%')."' ","filter"=>"OR",);
                $param[] = array("field"=>"em.PositionOther3","data"=>" like '".$data_arr['division'].".".(!empty($data_arr['position']) ? $data_arr['position'] : '%')."' )","filter"=>"OR",);
            }
            if(!empty($data_arr['position'])){
                $param[] = array("field"=>"(em.PositionMain","data"=>" like '".(!empty($data_arr['division']) ? $data_arr['division'] : '%').".".$data_arr['position']."' ","filter"=>"AND",);
                $param[] = array("field"=>"em.PositionOther1","data"=>" like '".(!empty($data_arr['division']) ? $data_arr['division'] : '%').".".$data_arr['position']."' ","filter"=>"OR",);
                $param[] = array("field"=>"em.PositionOther2","data"=>" like '".(!empty($data_arr['division']) ? $data_arr['division'] : '%').".".$data_arr['position']."' ","filter"=>"OR",);
                $param[] = array("field"=>"em.PositionOther3","data"=>" like '".(!empty($data_arr['division']) ? $data_arr['division'] : '%').".".$data_arr['position']."' )","filter"=>"OR",);
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
                $StatusID = $data['StatusID']; unset($data['StatusID']);
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
                if(!empty($NIP)){
                    //get parent node 
                    $ParentNode = $this->General_model->fetchData("db_employees.sto_temp",array("ID"=>$nodeID))->row();
                    $ParentID = (!empty($ParentNode) ? $ParentNode->parentID : 0);
                    $SuperiorParent = $this->m_hr->getMemberSTO(array("STOID"=>$ParentID))->row();
                    $superiorNIP = (!empty($SuperiorParent) ? $SuperiorParent->NIP : '');
                    $superiorName = (!empty($SuperiorParent) ? $superiorNIP."/".$SuperiorParent->Name : '');
                    //end get parent node
                    
                    //check if division
                    if(!empty($data['typeNode'])){
                        if($data['typeNode'] == 1 ){
                            //remove member old division             
                            $deleteMember = $this->General_model->deleteData("db_employees.sto_rel_user",array("STOID"=>$nodeID));
                        }
                    }
                    //end of division

                    for ($i=0; $i < count($NIP) ; $i++) { 
                        $checkExisMember = $this->General_model->fetchData("db_employees.sto_rel_user",array("STOID"=>$nodeID,"NIP"=>$NIP[$i]))->row();
                        
                        if(!empty($checkExisMember)){
                            $excuteMember = $this->General_model->updateData("db_employees.sto_rel_user",array("JobTitle"=>$jobTitle[$i],"NIP"=>$NIP[$i],"StatusID"=>$StatusID[$i],"IsActive"=>$data['isActive']),array("NIP"=>$NIP[$i]));
                        }else{
                            $excuteMember = $this->General_model->insertData("db_employees.sto_rel_user",array("JobTitle"=>$jobTitle[$i],"NIP"=>$NIP[$i],"IsActive"=>$data['isActive'],"STOID"=>$nodeID,"StatusID"=>$StatusID[$i]));
                        }   

                        //check on emp career
                        if($excuteMember){
                            $conditionCareer = array("NIP"=>$NIP[$i],"PositionID"=>$nodeID);
                            $dataPostCareer = array("NIP"=>$NIP[$i],"DepartmentID"=>$ParentID,"PositionID"=>$nodeID,"JobTitle"=>$jobTitle[$i],"Superior"=>$superiorName,"StatusID"=>$StatusID[$i]);

                            $isCareer = $this->General_model->fetchData("db_employees.employees_career",$conditionCareer)->row();
                            if(!empty($isCareer)){
                                $updateCareer = $this->General_model->updateData("db_employees.employees_career",$dataPostCareer,$conditionCareer);
                            }else{
                                $insertCareer = $this->General_model->insertData("db_employees.employees_career",$dataPostCareer);
                            }
                        }
                    }
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
    

    public function deleteSTOUser(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            if(!empty($data_arr['NIP'])){
                $conditions = array("NIP"=>$data_arr['NIP']);
                $isExist = $this->General_model->fetchData("db_employees.sto_rel_user",$conditions)->row();
                if(!empty($isExist)){
                    $delete = $this->General_model->deleteData("db_employees.sto_rel_user",array("NIP"=>$data_arr['NIP'],"STOID"=>$data_arr['STOID']));
                    
                    $message = (($delete) ? "Successfully":"Failed")." removed.";
                }else{$message = "Node is not found.";}

                $json = array("message"=>$message);
            }
        }

        echo json_encode($json);
    }


    public function changeNodeSTO(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            if(!empty($data_arr['NODE']) && !empty($data_arr['PARENT'])){
                $isExist = $this->General_model->fetchData("db_employees.sto_temp","ID = ".$data_arr['NODE']." or ID = ".$data_arr['PARENT'])->result();                
                if(!empty($isExist)){
                    $update = $this->General_model->updateData("db_employees.sto_temp",array("parentID"=>$data_arr['PARENT']),array("ID"=>$data_arr['NODE']));
                    $message = (($update) ? "Successfully":"Failed")." saved.";
                }else{$message="Node not founded.";}

            }else{ $message = "There's no Node selected."; }
            $json = array("message"=>$message);
        }

        echo json_encode($json);
    }



    public function fetchPositionSTO(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            if(!empty($data_arr['KEYWORD'])){
                $results = $this->m_hr->getTitleSTO($data_arr['KEYWORD'])->result();
                if(!empty($results)){
                    $json = $results;
                }
            }
        }

        echo json_encode($json);
    }


    public function fetchDivision(){
        $data = $this->input->post();
        if($data){
            $response = $this->General_model->fetchData("db_employees.sto_temp","title like '%".$data['term']."%' and typeNode = 1  and isActive=1")->result();
            echo json_encode($response);
        }
    }
    

    public function fetchPosition(){
        $data = $this->input->post();
        if($data){
            $response = $this->General_model->fetchData("db_employees.sto_temp","title like '%".$data['term']."%' and typeNode = 2 and isActive=1 and parentID = ".$data['id'])->result();
            echo json_encode($response);
        }
    }

    public function fetcthSuperior(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $member = $this->m_hr->getMemberSTO(array("a.STOID"=>$data_arr['ID']))->row();
            if(!empty($member)){$json = $member;}
        }
        echo json_encode($json);
    }


}