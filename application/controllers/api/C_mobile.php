<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_mobile extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('master/m_master');
        $this->load->model('hr/m_hr');
        $this->load->model('vreservation/m_reservation');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('notification/m_log');
        $this->load->library('JWT');
        $this->load->library('google');

    }

    private function dateTimeNow(){
        $dataTime = date('Y-m-d H:i:s');
        return $dataTime;
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function getInputToken2($token)
    {
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function login(){

        $data_arr = $this->getInputToken();
        
        // Cek setting
        $itSetting = $this->db
            ->get_where('dbit.m_config',array('GlobalPassword' => $data_arr['Password']))
            ->result_array();
        if(count($itSetting)>0){
            $dIT = $itSetting[0];
            if($dIT['DevelopMode']==1 || $dIT['DevelopMode']=='1'){

                // Get data student
                $dataStd = $this->db->get_where('db_academic.auth_students',
                    array('NPM' => $data_arr['NPM']))->result_array();

                return print_r(json_encode($dataStd));

            }
        }


    }



}