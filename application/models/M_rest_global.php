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


}
