<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_rest_global extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model("global-informations/Globalinformation_model");
        $this->load->library('JWT');
    }

    public function api_Biodata_MHS($NPM){
        $param = [];
        $param[] = array("field"=>"ta.`NPM`","data"=>" = '".$NPM."' ","filter"=>"AND",); 
        $result = $this->Globalinformation_model->fetchStudentsPS(false,false,$param);
        // get IPK
        $url = base_url().'rest/__getTranscript'; 
        $dataPost = [
            'auth' => [
                'user'=> 'students',
                'token' => $result[0]->Password,
            ],

            'NPM' => $NPM,
            'ClassOf' => $result[0]->ClassOf,
            'date' => date('Y-m-d'),
            'Source' => '',
        ];
        $token= $this->jwt->encode($dataPost,"s3Cr3T-G4N");
        $dataIPK = $this->m_master->apiservertoserver($url,$token);
        $result[0]->IPK_data = $dataIPK;
        return $result;
    }

    public function api_university_or_instansi($dataToken){
        $where='';
        if (array_key_exists('param', $dataToken)) {
           $where = 'WHERE ';
           $param = $dataToken['param'];
           # Param example
           // $param = [];
           // $param[] = array("field"=>"ta.`NPM`","data"=>" = '".$NPM."' ","filter"=>"AND",);
           $counter = 0;
           foreach ($param as $key => $value) {
               if($counter==0){
                   $where = $where.$value['field']." ".$value['data'];
               }
               else{
                   $where = $where.$value['filter']." ".$value['field']." ".$value['data'];
               }
               $counter++;
           } 
        }

        return $rs = $this->db->query(
            'select * from db_research.university '.$where
        )->result_array();
    }


}
