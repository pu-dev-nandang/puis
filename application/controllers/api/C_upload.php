<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_upload extends CI_Controller {

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


    // Upload File Skripsi
    function upload_skripsi(){

        $fileName = $this->input->get('fileName');
        $NPM = $this->input->get('n');
        $column = $this->input->get('c');

        $path = './uploads/document/'.$NPM;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }



        $config['upload_path']          = $path;
        $config['allowed_types']        = '*';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;

//        if($old!='' && is_file('./uploads/agregator/'.$old)){
//            unlink('./uploads/agregator/'.$old);
//        }


        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());
//            return print_r(json_encode($error));
            return print_r(0);
        }
        else {

            // Cek mhs
            $getStd = $this->db->get_where('db_academic.final_project_files',array(
                'NPM' => $NPM
            ))->result_array();

            if(count($getStd)>0){
                $this->db->where('NPM', $NPM);
                $this->db->update('db_academic.final_project_files',array(
                    $column => $fileName
                ));
            } else {
                $arr = array(
                    'NPM' => $NPM,
                    $column => $fileName
                );
                $this->db->insert('db_academic.final_project_files',$arr);
            }

            return print_r(1);


        }

    }

    function remove_skripsi(){
        $fileName = $this->input->get('fileName');
        $NPM = $this->input->get('n');
        $column = $this->input->get('c');
        $result = 0;

        $path = './uploads/document/'.$NPM.'/'.$fileName;
        if (file_exists($path)) {

            unlink($path);

            // Update DB


        }

        $this->db->where('NPM', $NPM);
        $this->db->update('db_academic.final_project_files',array(
            $column => ''
        ));

        $result = 1;

        return print_r($result);


    }



}
