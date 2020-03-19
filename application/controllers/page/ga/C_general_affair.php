<?php
/*ADDED BY FEBRI @ MARCH 2020*/
defined('BASEPATH') OR exit('No direct script access allowed');

class C_general_affair extends Globalclass {

    function __construct(){
        parent::__construct();
        $this->load->model(array("General_model","global-informations/Globalinformation_model",'general-affair/m_general_affair'));
    }


    private function temp($content){
        parent::template($content);
    }

    
    public function packageOrder(){
        $myNIP = $this->session->userdata('NIP');
        $myName = $this->session->userdata('Name');
        $data['employee'] = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$myNIP))->row();
        $content = $this->load->view('page/general-affair/package-order/index',$data,true);
        $this->temp($content);
    }

    public function fetchPackageOrder(){
        $reqdata = $this->input->post();
        if($reqdata){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
            $param = array();$orderBy=" em.ID DESC ";

            if(!empty($reqdata['search']['value']) ) {
                $search = $reqdata['search']['value'];

                $param[] = array("field"=>"(CourierExpedition","data"=>" like '%".$search."%' ","filter"=>"AND",);
                $param[] = array("field"=>"Shipper","data"=>" like '%".$search."%' ","filter"=>"OR",);
                $param[] = array("field"=>"BelongsTo","data"=>" like '%".$search."%' ","filter"=>"OR",);
                $param[] = array("field"=>"Receiver","data"=>" like '%".$search."%' )","filter"=>"OR",);
            }
            $totalData = $this->m_general_affair->fetchPackage(true,$param)->row();
            $TotalData = (!empty($totalData) ? $totalData->Total : 0);
            if(!empty($reqdata['start']) && !empty($reqdata['length'])){
                $result = $this->m_general_affair->fetchPackage(false,$param,$reqdata['start'],$reqdata['length'],$orderBy)->result();
            }else{
                $result = $this->m_general_affair->fetchPackage(false,$param)->result();
            }

            $json_data = array(
                "draw"            => intval( (!empty($reqdata['draw']) ? $reqdata['draw'] : null) ),
                "recordsTotal"    => intval($TotalData),
                "recordsFiltered" => intval($TotalData),
                "data"            => (!empty($result) ? $result : 0)
            );

        }else{$json_data=null;}
        $response = $json_data;
        echo json_encode($response);
    }


    public function packageSaveChanges(){
        $data=$this->input->post();
        $myNIP = $this->session->userdata('NIP');
        $myName = $this->session->userdata('Name');
        if($data){
            if(!empty($data['ID'])){
                $conditions = array("ID"=>$data['ID']);
                $isExist = $this->General_model->fetchData("db_general_affair.package_order",$conditions)->row();
                if(!empty($isExist)){
                    $data['UpdatedBy'] = $myNIP."/".$myName;
                    $update = $this->General_model->updateData("db_general_affair.package_order",$data,$conditions);
                    $message = (($update) ? "Successfully":"Failed")." saved.";
                }else{$message = "Data not founded.";}
            }else{
                $data['CreatedBy'] = $myNIP."/".$myName;
                $insert = $this->General_model->insertData("db_general_affair.package_order",$data);
                $message = (($insert) ? "Successfully":"Failed")." saved.";
            }
            $this->session->set_flashdata("message",$message);
            redirect(site_url('general-affair/package-order'));
        }else{show_404();}
    }


    public function packageDetail(){
        $data=$this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_general_affair.package_order",array("ID"=>$data_arr['ID']))->row();
            $json = $isExist;
        }
        echo json_encode($json);
    }




    public function lostAndFound(){
        $myNIP = $this->session->userdata('NIP');
        $myName = $this->session->userdata('Name');
        $data['employee'] = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$myNIP))->row();
        $content = $this->load->view('page/general-affair/lost-and-found/index',$data,true);
        $this->temp($content);
    }

    public function fetchLostAndFound(){
        $reqdata = $this->input->post();
        if($reqdata){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
            $param = array();$orderBy=" em.ID DESC ";

            if(!empty($reqdata['search']['value']) ) {
                $search = $reqdata['search']['value'];

                $param[] = array("field"=>"(CourierExpedition","data"=>" like '%".$search."%' ","filter"=>"AND",);
                $param[] = array("field"=>"Shipper","data"=>" like '%".$search."%' ","filter"=>"OR",);
                $param[] = array("field"=>"BelongsTo","data"=>" like '%".$search."%' ","filter"=>"OR",);
                $param[] = array("field"=>"Receiver","data"=>" like '%".$search."%' )","filter"=>"OR",);
            }
            $totalData = $this->m_general_affair->fetchPackage(true,$param)->row();
            $TotalData = (!empty($totalData) ? $totalData->Total : 0);
            if(!empty($reqdata['start']) && !empty($reqdata['length'])){
                $result = $this->m_general_affair->fetchPackage(false,$param,$reqdata['start'],$reqdata['length'],$orderBy)->result();
            }else{
                $result = $this->m_general_affair->fetchPackage(false,$param)->result();
            }

            $json_data = array(
                "draw"            => intval( (!empty($reqdata['draw']) ? $reqdata['draw'] : null) ),
                "recordsTotal"    => intval($TotalData),
                "recordsFiltered" => intval($TotalData),
                "data"            => (!empty($result) ? $result : 0)
            );

        }else{$json_data=null;}
        $response = $json_data;
        echo json_encode($response);
    }


    public function lostAndFoundSaveChanges(){
        $data=$this->input->post();
        $myNIP = $this->session->userdata('NIP');
        $myName = $this->session->userdata('Name');
        if($data){
            if(!empty($data['ID'])){
                $conditions = array("ID"=>$data['ID']);
                $isExist = $this->General_model->fetchData("db_general_affair.package_order",$conditions)->row();
                if(!empty($isExist)){
                    $data['UpdatedBy'] = $myNIP."/".$myName;
                    $update = $this->General_model->updateData("db_general_affair.package_order",$data,$conditions);
                    $message = (($update) ? "Successfully":"Failed")." saved.";
                }else{$message = "Data not founded.";}
            }else{
                $data['CreatedBy'] = $myNIP."/".$myName;
                $insert = $this->General_model->insertData("db_general_affair.package_order",$data);
                $message = (($insert) ? "Successfully":"Failed")." saved.";
            }
            $this->session->set_flashdata("message",$message);
            redirect(site_url('general-affair/package-order'));
        }else{show_404();}
    }


    public function lostAndFoundDetail(){
        $data=$this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_general_affair.package_order",array("ID"=>$data_arr['ID']))->row();
            $json = $isExist;
        }
        echo json_encode($json);
    }

}

