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

    private function genratePassword($Username,$Password){

        $plan_password = $Username.''.$Password;
        $pas = md5($plan_password);
        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

        return $pass;
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
            ->get_where('db_it.m_config',array('GlobalPassword' => $data_arr['Password']))
            ->result_array();

        if(count($itSetting)>0){
            $dIT = $itSetting[0];
            if($dIT['DevelopMode']==1 || $dIT['DevelopMode']=='1'){

                // Get data student
                $dataStd = $this->db->get_where('db_academic.auth_students',
                    array('NPM' => $data_arr['NPM']))->result_array();

                if(count($dataStd)>0){
                    $photo = $this->getPhoto($dataStd[0]['Year'],$data_arr['NPM']);
                    $dataStd[0]['Photo'] = $photo;
                    $result = array(
                        'Status' => 1,
                        'User' => $dataStd[0]
                    );
                }
                else {
                    $result = array(
                        'Status' => 0
                    );
                }


            } else {
                $dataStd = $this->checkUser($data_arr['NPM'],$data_arr['Password']);
                if(count($dataStd)>0){
                    $result = array(
                        'Status' => 1,
                        'User' => $dataStd[0]
                    );
                }
                else {
                    $result = array(
                        'Status' => 0
                    );
                }
            }
        } else {
            // Pengecekan manual

            $dataStd = $this->checkUser($data_arr['NPM'],$data_arr['Password']);
            if(count($dataStd)>0){
                $photo = $this->getPhoto($dataStd[0]['Year'],$data_arr['NPM']);
                $dataStd[0]['Photo'] = $photo;
                $result = array(
                    'Status' => 1,
                    'User' => $dataStd[0]
                );
            } else {
                $result = array(
                    'Status' => 0
                );
            }

        }

        return print_r(json_encode($result));

    }

    private function checkUser($NPM,$Password){
        $pass = $this->genratePassword($NPM,$Password);

        $dataStd = $this->db->limit(1)->get_where('db_academic.auth_students'
            ,array(
                'NPM' => $NPM,
                'Password' => $pass
            ))->result_array();

        return $dataStd;
    }

    private function getPhoto($Year,$NPM){

        $db_ = 'ta_'.$Year;
        $dataDetailStd = $this->db->select('Photo,Gender')->get_where($db_.'.students',array('NPM' => $NPM),1)->result_array();

        $srcImage = base_url('images/icon/userfalse.png');
        if($dataDetailStd[0]["Photo"]!='' && $dataDetailStd[0]["Photo"]!=null){
            $urlImg = './uploads/students/'.$db_.'/'.$dataDetailStd[0]["Photo"];
            $srcImage = (file_exists($urlImg)) ? base_url('uploads/students/'.$db_.'/'.$dataDetailStd[0]["Photo"]) : base_url('images/icon/userfalse.png') ;
        }

        return $srcImage;
    }



}