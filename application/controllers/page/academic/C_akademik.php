<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_akademik extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('akademik/m_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }


    public function ketersediaan_dosen()
    {
        $department = parent::__getDepartement();
        $content = $this->load->view('page/'.$department.'/ketersediaandosen/ketersediaan_dosen','',true);
        $this->temp($content);
    }




    // ===== MODAL ======

    public function modal_tahun_akademik_detail_prodi(){
        $data['department'] = parent::__getDepartement();
        $this->load->view('page/'.$data['department'].'/modal/modal_tahun_akademik_detail_prodi',$data);
    }

    public function modal_tahun_akademik_detail_lecturer(){
        $data['department'] = parent::__getDepartement();
        $this->load->view('page/'.$data['department'].'/modal/modal_tahun_akademik_detail_lecturer',$data);
    }

    public function Modal_KetersediaanDosen(){

        $ID = $this->input->post('ID');
        $data['department'] = parent::__getDepartement();
        $data['dataDosen'] = $this->m_akademik->__getKetersediaanDosen($ID);
        $this->load->view('page/'.$data['department'].'/ketersediaandosen/modal_ketersediaan_dosen',$data);

    }
    // ===== /MODAL =====

    public function upload_photo_student(){

        $folder = $this->input->get('f');
        $fileName = $this->input->get('fileName');
        $config['overwrite'] = TRUE;
        $config['upload_path']          = './uploads/students/'.$folder.'/';
        // $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['allowed_types']        = '*';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;


        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());
            return print_r(json_encode($error));
        }
        else {

            // Update Db
            $dataNPM = trim(explode('.',$fileName)[0]);

            $this->db->set('Photo', $fileName);
            $this->db->where('NPM', $dataNPM);
            $this->db->update($folder.'.students');

            $success = array('success' => $this->upload->data());
            $success['success']['formGrade'] = 0;

            return print_r(json_encode($success));
        }
    }

}
