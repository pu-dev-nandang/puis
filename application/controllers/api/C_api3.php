<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_api3 extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
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

    public function is_url_exist($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code == 200){
            $status = true;
        }else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    public function getListMenuAgregator(){

        $data = $this->db->order_by('ID','ASC')->get('db_it.agregator_menu')->result_array();

        return print_r(json_encode($data));

    }

    public function crudTeamAgregagor(){

        $data_arr = $this->getInputToken();

        if($data_arr['action']=='insertTeamAggr'){

            $dataForm = (array) $data_arr['dataForm'];
            $this->db->insert('db_it.agregator_user',$dataForm);
            $insert_id = $this->db->insert_id();

            $Member = (array) $data_arr['Member'];
//            if(count())


        }
        else if($data_arr['action']=='updateTeamAggr'){

        }

    }



}
