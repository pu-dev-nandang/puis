<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_employees extends HR_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('akademik/m_akademik');
        $this->load->model('hr/m_hr');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function tab_menu($page)
    {
        $department = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$department.'/employees/tab_employees',$data,true);
        $this->temp($content);
    }


    public function employees()
    {
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/employees/employees','',true);
        $this->tab_menu($page);
    }

    public function input_employees(){
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/employees/inputEmployees','',true);
        $this->tab_menu($page);
    }

    public function edit_employees($NIP){
        $department = parent::__getDepartement();
        $arrEmp = $this->db->get_where('db_employees.employees',array('NIP'=>$NIP),1)->result_array();
        $data['arrEmp'] = (count($arrEmp)>0) ? $arrEmp[0] : [];

        // Cek apakah NIP dapat di hapus secara permanen atau tidak
        $data['btnDelPermanent'] = $this->m_hr->checkPermanentDelete($NIP);

        $page = $this->load->view('page/'.$department.'/employees/editEmployees',$data,true);
        $this->tab_menu($page);
    }

    function upload_photo(){

        $fileName = $this->input->get('fileName');

        $config['upload_path']          = './uploads/employees/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;


            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('userfile')){
                $error = array('error' => $this->upload->display_errors());
                return print_r(json_encode($error));
            }
            else {

                $success = array('success' => $this->upload->data());
                $success['success']['formGrade'] = 0;

                return print_r(json_encode($success));
            }



    }


}
