<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_requestdocument extends Globalclass {

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('database/m_database');
        $this->load->library('JWT');
    }


     public function temp($content)
    {
        parent::template($content);
    }

    public function menu_request($page){
        $data['page'] = $page;
        $content = $this->load->view('page/requestdocumet/menu_requestdocument',$data,true);
        $this->temp($content);
    }


    public function list_requestdocument()
    {
        $page = $this->load->view('page/requestdocumet/list_requestdocument','',true);
        $this->menu_request($page);
    }


    public function list_requestdocumentxxx()
    {
        $content = $this->load->view('page/requestdocumet/list_requestdocument','',true);
        $this->temp($content);
        //$this->menu_announcement($page);
    }

    public function create_announcement()
    {
        $page = $this->load->view('page/announcement/create_announcement','',true);
        $this->menu_announcement($page);
    }

    public function edit_announcement($ID){

        $data['ID'] = $ID;

        $page = $this->load->view('page/announcement/edit_announcement',$data,true);
        $this->menu_announcement($page);
    }


    public function upload_files(){
        $IDAnnc = $this->input->get('IDAnnc');
        $fileName = $this->input->get('f');

        $lf = $this->input->get('lf');

        if(isset($lf) && $lf!='') {
            // Delete last data dulu
            if(is_file('./uploads/announcement/'.$lf)){
                unlink('./uploads/announcement/'.$lf);
            }
        }


        $config['upload_path']          = './uploads/announcement/';
        $config['allowed_types']        = 'pdf';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());
            return print_r(json_encode($error));
        }
        else {

            // Update Database
            $this->db->set('File', $fileName);
            $this->db->where('ID', $IDAnnc);
            $this->db->update('db_notifikasi.announcement');

            $success = array('success' => $this->upload->data());
            $success['success']['formGrade'] = 0;

            return print_r(json_encode($success));
        }

    }




}
