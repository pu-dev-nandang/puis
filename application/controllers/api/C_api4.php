<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_api4 extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('m_search');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('master/m_master');
        $this->load->library('JWT');
        $this->load->library('google');
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "s3Cr3T-G4N";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function getInputToken2()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function crudAgregatorTB3()
    {
        $Input = $this->getInputToken();
        $action = $Input['action'];
        switch ($action) {
            case 'readDataDosenTidakTetap':
                $sql = 'select a.';    

                break;
            
            default:
                # code...
                break;
        }
    }

    // Search Employees (termasuk dosen di dalamnya)
    public function searchEmployees(){

        $key = $this->input->get('key');
        $limit = $this->input->get('limit');

        $data = $this->m_search->searchEmployees($key,$limit);

        return print_r(json_encode($data));

    }

    public function crudPrefrencesEmployees(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='getDataRelatedNIP'){
            $NIP = $data_arr['NIP'];
            $data = $this->m_search->getDataRelatedNIP($NIP);

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='setToDataRelatedNIP'){
            $NIP = $data_arr['NIP'];
            $NIPInduk = $data_arr['NIPInduk'];
            $data = $this->m_search->setToDataRelatedNIP($NIPInduk,$NIP);

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removeDataRelatedNIP'){
            $ID = $data_arr['ID'];
            $this->db->where('ID', $ID);
            $this->db->delete('db_employees.related_nip');

            return print_r(1);
        }
        else if($data_arr['action']=='readAllDataRelatedNIP'){

            $data = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.related_nip rn 
                                                LEFT JOIN db_employees.employees em ON (em.NIP = rn.NIP)
                                                GROUP BY rn.NIP')->result_array();

            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    $data[$i]['Details'] = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.related_nip rn 
                                                LEFT JOIN db_employees.employees em ON (em.NIP = rn.RelatedNIP)
                                                WHERE rn.NIP = "'.$data[$i]['NIP'].'" ')->result_array();
                }
            }

            return print_r(json_encode($data));

        }


    }

  /*ADDED BY FEBRI @ JAN 2020*/
    public function getStdInsurance(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->m_api->getStdInsurance(array("a.NPM"=>$data_arr['NPM']))->row();
            if(!empty($isExist)){
                $json = $isExist;
            }
        }

        echo json_encode($json);
    }


    public function detailEmployeeOBJ(){
        $this->load->model(array('General_model','hr/m_hr'));
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$data_arr['NIP']))->row();
            if(!empty($isExist)){
                //$isExist->MyCareer = $this->General_model->fetchData("db_employees.employees_career",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyHistorical = $this->General_model->fetchData("db_employees.employees_joindate",array("NIP"=>$isExist->NIP),"ID","desc")->result();
                $isExist->MyCareer = $this->m_hr->getEmpCareer(array("a.NIP"=>$isExist->NIP,"isShowSTO"=>0))->result();
                $isExist->MyBank = $this->General_model->fetchData("db_employees.employees_bank_account",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducation = $this->General_model->fetchData("db_employees.employees_educations",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationNonFormal = $this->General_model->fetchData("db_employees.employees_educations_non_formal",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationTraining = $this->General_model->fetchData("db_employees.employees_educations_training",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyFamily = $this->General_model->fetchData("db_employees.employees_family_member",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyExperience = $this->General_model->fetchData("db_employees.employees_experience",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyBPJS = $this->General_model->fetchData("db_employees.employees_bpjs",array("NIP"=>$isExist->NIP),"Type","asc")->result();
                $isExist->MyAllowance = $this->General_model->fetchData("db_employees.employees_allowance",array("NIP"=>$isExist->NIP))->result();
                $json = $isExist;
            }
        }
        echo json_encode($json);
    }
    /*END ADDED BY FEBRI @ JAN 2020*/

}
