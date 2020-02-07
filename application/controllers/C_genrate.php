<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_genrate extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        header('Access-Control-Allow-Origin: *');

        $this->load->library('JWT');
        $this->load->library('pdf');
        $this->load->library('pdf_mc_table');
        $this->load->library('Qrcode/qrlib');

        $this->load->model('m_rest');
        $this->load->model('master/m_master');
        $this->load->model('report/m_save_to_pdf');


        date_default_timezone_set("Asia/Jakarta");
        setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
    }

    private function getInputToken($token)
    {
        $key = "UAP)(*";
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


    public function setSKPIQRCode(){

        $data_arr = $this->getInputToken2();
        // QRcode::png($data_arr['data'],'./images/SKPI/SKPI-QRCode.png','L', 10, 4);
        $t = QRcode::png($data_arr['data'],false,'L', 10, 4);
        $enc = base64_encode($t);
        print_r($enc);die();
        return print_r(1);

    }




}
