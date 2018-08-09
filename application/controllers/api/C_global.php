<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_global extends CI_Controller {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
        $this->load->library('JWT');
        $this->load->model('master/m_master');
    }

    public function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function loadDataRegistrationBelumBayar()
    {
        $content = $this->load->view('page/load_data_registration_belum_bayar',$this->data,true);
        echo $content;
    }

    public function load_data_registration_telah_bayar()
    {
        $content = $this->load->view('page/load_data_registration_telah_bayar',$this->data,true);
        echo $content;
    }

    public function load_data_registration_formulir_offline()
    {
        $content = $this->load->view('page/load_data_registration_formulir_offline',$this->data,true);
        echo $content;
    }

    public function download($file)
    {
        if (file_exists('./document/'.$file)) {
             $this->load->helper('download');
             $data   = file_get_contents('./document/'.$file);
             $name   = $file;
             force_download($name, $data); // script download file
            // $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    public function fileGet($file)
    {
        //check session ID_register_formulir ada atau tidak
        // check session token untuk download

        // Check File exist atau tidak
        if (file_exists('./document/'.$file)) {
            // $this->load->helper('download');
            // $data   = file_get_contents('./document/'.$namaFolder.'/'.$file);
            // $name   = $file;
            // force_download($name, $data); // script download file
            $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    public function fileGetAny($file)
    {
        //check session ID_register_formulir ada atau tidak
        // check session token untuk download
        $file = str_replace('-', '/', $file);

        // Check File exist atau tidak
        if (file_exists('./uploads/'.$file)) {
            // $this->load->helper('download');
            // $data   = file_get_contents('./document/'.$namaFolder.'/'.$file);
            // $name   = $file;
            // force_download($name, $data); // script download file
            $this->showFile2($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    private function showFile2($file)
    {
        header("Content-type: application/pdf");
        header("Content-disposition: inline;     
        filename=".basename('uploads/'.$file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        $filePath = readfile('uploads/'.$file);
    }

    public function download_template($file)
    {
        $file = str_replace('-', '/', $file);
        if (file_exists('./uploads/'.$file)) {
             $this->load->helper('download');
             $data   = file_get_contents('./uploads/'.$file);
             $name   = $file;
             force_download($name, $data); // script download file
            // $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    public function download_anypath()
    {
        $input = $this->getInputToken();
        $path = $input['path'];
        $filename = $input['Filename'];
        if (file_exists($path)) {
             $this->load->helper('download');
             $data   = file_get_contents($path);
             $name   = $filename;
             force_download($name, $data); // script download file
            // $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    private function showFile($file)
    {
        header("Content-type: application/pdf");
        header("Content-disposition: inline;     
        filename=".basename('document/'.$file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        $filePath = readfile('document/'.$file);
    }

    public function get_detail_cicilan_fee_admisi()
    {
        $input = $this->getInputToken();
        $ID_register_formulir = $input['ID_register_formulir'];
        $output = $this->m_master->caribasedprimary('db_finance.payment_pre','ID_register_formulir',$ID_register_formulir);
        echo json_encode($output);

    }

}
