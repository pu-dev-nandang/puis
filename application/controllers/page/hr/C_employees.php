<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_employees extends HR_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('akademik/m_akademik');
        $this->load->model('hr/m_hr');
        $this->load->model('master/m_master');
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

    public function tab_menuacademic($page)
    {
        $department = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$department.'/academic/tab_academic',$data,true);
        $this->temp($content);
    }


    public function employees()
    {
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/employees/employees','',true);
        $this->tab_menu($page);
    }

    //add bismar
    public function employees_files(){
        $department = parent::__getDepartement();

        $data['NIP'] = '';
        $page = $this->load->view('page/'.$department.'/employees/employees_files',$data,true);
        $this->tab_menu($page);
    }

    public function academicDetails($NIP){
        $department = parent::__getDepartement();

        $data['NIP']=$NIP;
        $content = $this->load->view('page/'.$department.'/academic/academic_menu',$data,true);
        $this->temp($content);
    }

    public function loadpageacademicDetails(){
        $department = parent::__getDepartement();
        $data_arr = $this->getInputToken();
        $G_TypeFiles = $this->m_master->showData_array('db_employees.master_files');
        $data_arr['G_TypeFiles'] =  $G_TypeFiles;
        $this->load->view('page/'.$department.'/academic/'.$data_arr['page'], $data_arr);
    }


    public function upload_files(){
        $fileName = $this->input->get('fileName');
        $Colom = $this->input->get('c');
        $User = $this->input->get('u');

        $config['upload_path']          = './uploads/files/';
        $config['allowed_types']        = '*';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;

        if(is_file('./uploads/files/'.$fileName)){
            unlink('./uploads/files/'.$fileName);
        }
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());
            return print_r(json_encode($error));
        }
        else {
            
            //$dataUpdate = array(
            //    'type_file' => $Colom,
            //    'NIP' => $User,
             //   'name_file' => $fileName,
             //   'user_create' =>$this->session->userdata('NIP') 
            //);
            //$this->db->insert('db_employees.temp_files',$dataUpdate);
            // $this->m_master->save_images($fileName, $User);
                            
            $success = array('success' => $this->upload->data());
            $success['success']['formGrade'] = 0;
            // Cek apakah di db sudah ada
            $dataNIP = $this->db->get_where('db_employees.files',array('NIP'=>$User))->result_array();
            $dataUpdate = array(
                $Colom => $fileName
            );
            if(count($dataNIP)>0){
                $this->db->where('NIP', $User);
                $this->db->update('db_employees.files',$dataUpdate);
            } else {
                $dataUpdate['NIP'] = $User;
                $this->db->insert('db_employees.files',$dataUpdate);
            }
            return print_r(json_encode($success));

        }

    }

    public function remove_files(){
        $fileName = $this->input->get('fileName');
        $result = 0;
        if(is_file('./uploads/files/'.$fileName)){
            unlink('./uploads/files/'.$fileName);
            $result = 1;
        }

        $user = $this->input->get('user');
        $colom = $this->input->get('colom');

        $this->db->set($colom, '');
        $this->db->where('NIP', $user);
        $this->db->update('db_employees.files');

        return print_r($result);
    }

    public function input_employees(){
        $department = parent::__getDepartement();
        // get Prodi
        $data['ProdiArr'] = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
        $page = $this->load->view('page/'.$department.'/employees/inputEmployees',$data,true);
        $this->tab_menu($page);
    }

    public function edit_employees($NIP){
        $department = parent::__getDepartement();
        // get Prodi
        $data['ProdiArr'] = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
        $arrEmp = $this->db->get_where('db_employees.employees',array('NIP'=>$NIP),1)->result_array();
        $data['arrEmp'] = (count($arrEmp)>0) ? $arrEmp[0] : [];

        // Cek apakah NIP dapat di hapus secara permanen atau tidak
        $data['btnDelPermanent'] = $this->m_hr->checkPermanentDelete($NIP);

//        print_r($arrEmp);
//        exit;

        $page = $this->load->view('page/'.$department.'/employees/editEmployees',$data,true);
        $this->tab_menu($page);
    }

    public function upload_photo(){

        $fileName = $this->input->get('fileName');
        print_r(fileName);

        $config['upload_path']          = './uploads/employees/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;

        if(is_file('./uploads/employees/'.$fileName)){
            unlink('./uploads/employees/'.$fileName);
        }

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


    public function upload_fileAcademic(){

        $action = $this->input->get('action');
        $Colom = $this->input->get('c');
        $IDuser = $this->session->userdata('NIP');
        //print_r($action);

        if ($Colom == 'IjazahS1') {
                $fileName = $this->input->get('fileName');
                //$Colom = $this->input->get('c');
                $NIP = $this->input->get('u');

                $config['upload_path']          = './uploads/files/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 8000; // 8 mb
                $config['file_name']            = $fileName;

                if(is_file('./uploads/files/'.$fileName)){
                    unlink('./uploads/files/'.$fileName);
                }
                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('userfile')){
                    $error = array('error' => $this->upload->display_errors());
                    return print_r(json_encode($error));
                }
                else {
                    
                    $success = array('success' => $this->upload->data());
                    $success['success']['formGrade'] = 0;
                    
                    //$Get_MasterFiles = $this->m_master->MasterfileStatus($Colom);
                    //$dataSave = array(
                    //        'NIP' => $NIP,
                    //        'TypeFiles' => $Get_MasterFiles[0]['ID'],
                    //        'LinkFiles' => $fileName,
                    //        'UserCreate' => $IDuser
                    //);
                    //$this->db->insert('db_employees.files',$dataSave);
                    return print_r(json_encode($success));

                }
        }        

        elseif ($Colom == 'TranscriptS1') {

                $fileName = $this->input->get('fileName');
                //$Colom = $this->input->get('c');
                $NIP = $this->input->get('u');
                
                $config['upload_path']          = './uploads/files/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 8000; // 8 mb
                $config['file_name']            = $fileName;

                if(is_file('./uploads/files/'.$fileName)){
                    unlink('./uploads/files/'.$fileName);
                }
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('userfile')){
                    $error = array('error' => $this->upload->display_errors());
                    return print_r(json_encode($error));
                }
                else {
                    
                    $success = array('success' => $this->upload->data());
                    $success['success']['formGrade'] = 0;
                    //$Get_MasterFiles = $this->m_master->MasterfileStatus($Colom);
                    //$dataSave = array(
                    ///        'NIP' => $NIP,
                    //        'TypeFiles' => $Get_MasterFiles[0]['ID'],
                    //        'LinkFiles' => $fileName,
                    //        'UserCreate' => $IDuser
                    //);

                    //$this->db->insert('db_employees.files',$dataSave);
                    return print_r(json_encode($success));
                }

            } 
            elseif ($Colom == 'IjazahS2') {

                $fileName = $this->input->get('fileName');
                $Colom = $this->input->get('c');
                $User = $this->input->get('u');
                //print_r($fileName);

                $config['upload_path']          = './uploads/files/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 8000; // 8 mb
                $config['file_name']            = $fileName;

                if(is_file('./uploads/files/'.$fileName)){
                    unlink('./uploads/files/'.$fileName);
                }
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('userfile')){
                    $error = array('error' => $this->upload->display_errors());
                    return print_r(json_encode($error));
                }
                else {
                    
                    $success = array('success' => $this->upload->data());
                    $success['success']['formGrade'] = 0;
                    //$Get_MasterFiles = $this->m_master->MasterfileStatus($Colom);
                    //$dataSave = array(
                    //        'NIP' => $NIP,
                    //        'TypeFiles' => $Get_MasterFiles[0]['ID'],
                    //        'LinkFiles' => $fileName,
                    //        'UserCreate' => $IDuser
                    //);

                    //$this->db->insert('db_employees.files',$dataSave);
                    return print_r(json_encode($success));
                }

            }
             elseif ($Colom == 'TranscriptS2') {

                $fileName = $this->input->get('fileName');
                $Colom = $this->input->get('c');
                $User = $this->input->get('u');
                //print_r($fileName);

                $config['upload_path']          = './uploads/files/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 8000; // 8 mb
                $config['file_name']            = $fileName;

                if(is_file('./uploads/files/'.$fileName)){
                    unlink('./uploads/files/'.$fileName);
                }
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('userfile')){
                    $error = array('error' => $this->upload->display_errors());
                    return print_r(json_encode($error));
                }
                else {
                    
                    $success = array('success' => $this->upload->data());
                    $success['success']['formGrade'] = 0;
                    //$Get_MasterFiles = $this->m_master->MasterfileStatus($Colom);
                    //
                    //$dataSave = array(
                    //        'NIP' => $NIP,
                    //        'TypeFiles' => $Get_MasterFiles[0]['ID'],
                    //        'LinkFiles' => $fileName,
                    //        'UserCreate' => $IDuser
                    //);
                    //$this->db->insert('db_employees.files',$dataSave);
                    return print_r(json_encode($success));
                }
            }
            elseif ($Colom == 'IjazahS3') {

                $fileName = $this->input->get('fileName');
                $Colom = $this->input->get('c');
                $User = $this->input->get('u');
                
                $config['upload_path']          = './uploads/files/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 8000; // 8 mb
                $config['file_name']            = $fileName;

                if(is_file('./uploads/files/'.$fileName)){
                    unlink('./uploads/files/'.$fileName);
                }
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('userfile')){
                    $error = array('error' => $this->upload->display_errors());
                    return print_r(json_encode($error));
                }
                else {
                    
                    $success = array('success' => $this->upload->data());
                    $success['success']['formGrade'] = 0;
                    //$Get_MasterFiles = $this->m_master->MasterfileStatus($Colom);
                    //$dataSave = array(
                    //        'NIP' => $NIP,
                    //        'TypeFiles' => $Get_MasterFiles[0]['ID'],
                     //       'LinkFiles' => $fileName,
                     //       'UserCreate' => $IDuser
                    //);

                    //$this->db->insert('db_employees.files',$dataSave);
                    return print_r(json_encode($success));
                }
            }
            elseif ($Colom == 'TranscriptS3') {

                $fileName = $this->input->get('fileName');
                $Colom = $this->input->get('c');
                $User = $this->input->get('u');

                $config['upload_path']          = './uploads/files/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 8000; // 8 mb
                $config['file_name']            = $fileName;

                if(is_file('./uploads/files/'.$fileName)){
                    unlink('./uploads/files/'.$fileName);
                }
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('userfile')){
                    $error = array('error' => $this->upload->display_errors());
                    return print_r(json_encode($error));
                }
                else {
                    
                    $success = array('success' => $this->upload->data());
                    $success['success']['formGrade'] = 0;
                    //$Get_MasterFiles = $this->m_master->MasterfileStatus($Colom);
                    //$dataSave = array(
                    //        'NIP' => $NIP,
                    //        'TypeFiles' => $Get_MasterFiles[0]['ID'],
                    //        'LinkFiles' => $fileName,
                    //        'UserCreate' => $IDuser
                    //);

                    //$this->db->insert('db_employees.files',$dataSave);
                    return print_r(json_encode($success));
                }
            }
            elseif ($action == 'OtherFiles') {

                $fileName = $this->input->get('fileName');
                $Colom = $this->input->get('c');
                $User = $this->input->get('u');

                $config['upload_path']          = './uploads/files/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 8000; // 8 mb
                $config['file_name']            = $fileName;

                if(is_file('./uploads/files/'.$fileName)){
                    unlink('./uploads/files/'.$fileName);
                }
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('userfile')){
                    $error = array('error' => $this->upload->display_errors());
                    return print_r(json_encode($error));
                }
                else {
                    
                    $success = array('success' => $this->upload->data());
                    $success['success']['formGrade'] = 0;

                    //$Get_MasterFiles = $this->m_master->MasterfileStatus($Colom);
                   
                    return print_r(json_encode($success));
                }


            }
    }


    public function upload_ijazah(){

        $fileName = $this->input->get('fileName');

        $config['upload_path']          = './uploads/ijazah/';
        $config['allowed_types']        = 'pdf';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;

//        $pathUn = realpath(APPPATH);
        if(is_file('./uploads/ijazah/'.$fileName)){
            unlink('./uploads/ijazah/'.$fileName);
        }


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

    public function upload_certificate(){

        $fileName = $this->input->get('fileName');
        $old = $this->input->get('old');
        $ID = $this->input->get('id');

        $config['upload_path']          = './uploads/certificate/';
        $config['allowed_types']        = 'pdf';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;

        if($old!=''  && is_file('./uploads/certificate/'.$old)){
            unlink('./uploads/certificate/'.$old);
        }

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());
            return print_r(json_encode($error));
        }
        else {

            // Update DB
            $this->db->where('ID', $ID);
            $this->db->update('db_employees.employees_certificate',array(
                'File' => $fileName
            ));

            $success = array('success' => $this->upload->data());
            $success['success']['formGrade'] = 0;

            return print_r(json_encode($success));
        }

    }




    // ==================Academic Employess Data===========================
    
     public function academic_employees(){
        $department = parent::__getDepartement();
        $data['ProdiArr'] = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
        $page = $this->load->view('page/'.$department.'/academic/academic_employees',$data,true);
        $this->tab_menuacademic($page);
    }


     public function files_employees(){
      $department = parent::__getDepartement();

        $logged_in = $this->session->userdata('NIP');

        $data['filesview'] = $this->m_master->carifilestemp();
        $page = $this->load->view('page/'.$department.'/employees/files_review',$data,true);

        //$sender= $this->ms->getSenderMenu($kode);
        //$this->load->view('confirm/form_acc_gm', $datas);

     }


    // =============================================

    public function tab_menu_report($page)
    {
        $department = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$department.'/monitoring/tab_monitoring',$data,true);
        $this->temp($content);
    }

    public function with_range_date(){

        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/monitoring/with_range_date','',true);
        $this->tab_menu_report($page);

    }


}
