<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_employees extends HR_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model(array('akademik/m_akademik','hr/m_hr','master/m_master','General_model'));
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
        /*ADDED BY FEBRI @ JAN 2020*/
        $data['statusstd'] = $this->General_model->fetchData("db_employees.employees_status","IDStatus != '-2'","IDStatus","asc")->result();
        $data['division'] = $this->General_model->fetchData("db_employees.division",array())->result();
        $data['position'] = $this->General_model->fetchData("db_employees.position",array())->result();
        $data['religion'] = $this->General_model->fetchData("db_employees.religion",array())->result();
        $data['level_education'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
        $content = $this->load->view('page/database/employees',$data,true);
        /*END ADDED BY FEBRI @ JAN 2020*/
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/employees/employees',$data,true);
        $this->tab_menu($page);
    }


    public function preferences()
    {
        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/employees/preferences','',true);
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
        //$G_TypeFiles = $this->m_master->showData_array('db_employees.master_files'); 
        $sql = 'SELECT * FROM db_employees.master_files WHERE Type = 1 AND ID NOT IN ("13")';
        $G_TypeFiles =$this->db->query($sql, array())->result_array();
    
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
        $data['NIP'] = $NIP;

        // Cek apakah NIP dapat di hapus secara permanen atau tidak
        $data['btnDelPermanent'] = $this->m_hr->checkPermanentDelete($NIP);

//        print_r($arrEmp);
//        exit;

        $page = $this->load->view('page/'.$department.'/employees/editEmployees',$data,true);
        $this->tab_menu_new_emp($page,$NIP);
    }

    public function upload_photo(){

        $fileName = $this->input->get('fileName');
        //print_r(fileName);

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


    public function upload_edit_fileAcademic(){
        
        $action = $this->input->get('action');
        $Colom = $this->input->get('c');
        $IDuser = $this->session->userdata('NIP');

        if ($Colom == 'Ijazah') {
                $fileName = $this->input->get('fileName');
                //$Colom = $this->input->get('c');
                $NIP = $this->input->get('u');

                // Delete files academic
                $sql = 'SELECT LinkFiles FROM db_employees.files WHERE LinkFiles = "'.$fileName.'" ';
                $qufiles =$this->db->query($sql, array())->result_array();

                if(count($qufiles)>0) {
                    $NameFIles = $qufiles[0]['LinkFiles'];

                    $pathPhoto = './uploads/files/'.$NameFIles;
                    if(file_exists($pathPhoto)){
                            unlink($pathPhoto);
                    }
                }
                // Delete files academic

                //------------setting upload files
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
                    return print_r(json_encode($success));
                }
                 //------------setting upload files
        }        

        elseif ($Colom == 'Transcript') {

                $fileName = $this->input->get('fileName');
                //$Colom = $this->input->get('c');
                $NIP = $this->input->get('u');

                // Delete files academic
                $sql = 'SELECT LinkFiles FROM db_employees.files WHERE LinkFiles = "'.$fileName.'" ';
                $qufiles =$this->db->query($sql, array())->result_array();   

                if(count($qufiles)>0) {
                    $NameFIles = $qufiles[0]['LinkFiles'];

                    $pathPhoto = './uploads/files/'.$NameFIles;
                    if(file_exists($pathPhoto)){
                            unlink($pathPhoto);
                    }
                }
                // Delete files academic
                
                //------------setting upload files
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
                    return print_r(json_encode($success));
                }
                //------------setting upload files

            } 
        elseif ($Colom == 'OtherFiles') {
            $fileName = $this->input->get('fileName');
            $NIP = $this->input->get('u');

            // Delete files academic
            $sql = 'SELECT LinkFiles FROM db_employees.files WHERE LinkFiles = "'.$fileName.'" ';
            $qufiles =$this->db->query($sql, array())->result_array();   

            if(count($qufiles)>0) {
                $NameFIles = $qufiles[0]['LinkFiles'];

                $pathPhoto = './uploads/files/'.$NameFIles;
                if(file_exists($pathPhoto)){
                            unlink($pathPhoto);
                }
            }
            // Delete files academic

            //------------setting upload files
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
                    return print_r(json_encode($success));
                }
                //------------setting upload files
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
                    return json_encode($error);
                    //return print_r(json_encode($error));
                }
                else {
                    $success = array('success' => $this->upload->data());
                    $success['success']['formGrade'] = 0;

                    //$Get_MasterFiles = $this->m_master->MasterfileStatus($Colom);
                   
                    return json_encode($success);
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
        $type = $this->input->get('type');

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

            if($type=='stdacv'){

                $this->db->where('ID', $ID);
                $this->db->update('db_studentlife.student_achievement',array(
                    'Certificate' => $fileName
                ));
            } else {

                // Update DB
                $this->db->where('ID', $ID);
                $this->db->update('db_employees.employees_certificate',array(
                    'File' => $fileName
                ));

            }



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

    public function hrd_academic_setting(){
        $department = parent::__getDepartement();
        $logged_in = $this->session->userdata('NIP');
        $data['ProdiArr'] = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
        $page = $this->load->view('page/'.$department.'/academic/setting_academic',$data,true);
        $this->tab_menuacademic($page);

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

    public function lecturer_fees(){

        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/monitoring/lecturer_fees','',true);
        $this->tab_menu_report($page);

    }

    public function recapitulation_salaries (){

        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/monitoring/recapitulation_salaries','',true);
        $this->tab_menu_report($page);

    }



    /*ADDED BY FEBRI @ NOV 2019*/
    public function empRequest(){
        $this->load->helper("General_helper");
        $data = $this->input->post();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $conditions = array("NIP"=>$data_arr['NIP']);
            $isExist = $this->General_model->fetchData("db_employees.employees",$conditions)->row();
            if(!empty($isExist)){
                $isExist->MyHistorical = $this->General_model->fetchData("db_employees.employees_joindate",array("NIP"=>$isExist->NIP),"ID","desc")->result();
                $isExist->MyCareer = $this->m_hr->getEmpCareer(array("a.NIP"=>$isExist->NIP,"isShowSTO"=>0))->result();
                $isExist->MyBank = $this->General_model->fetchData("db_employees.employees_bank_account",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducation = $this->General_model->fetchData("db_employees.employees_educations",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationNonFormal = $this->General_model->fetchData("db_employees.employees_educations_non_formal",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationTraining = $this->General_model->fetchData("db_employees.employees_educations_training",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyFamily = $this->General_model->fetchData("db_employees.employees_family_member",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyExperience = $this->General_model->fetchData("db_employees.employees_experience",array("NIP"=>$isExist->NIP))->result();
                
                $data['NIP'] = $data_arr['NIP'];
                $data['origin'] = $isExist;
                
                $Logs = json_decode($isExist->Logs);
                $data['request'] = $Logs;             
            }
        }
        $this->load->view('page/human-resources/employees/requestMerging',$data);
    }

    public function empRequestAppv(){
        $data = $this->input->post();
        $myNIP = $this->session->userdata('NIP');
        $myName = $this->session->userdata('Name');
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $conditions = array("NIP"=>$data_arr['NIP']);
            $message = ""; $isfinish = false;
            $isExist = $this->General_model->fetchData("db_employees.employees",$conditions)->row();
            if(!empty($isExist)){
                if($data_arr['ACT'] == 2){ //for rejected
                    $dataPost = array("isApproved"=>2,"NoteApproved"=>(!empty($data_arr['NOTE']) ? $data_arr['NOTE'] : null));                    
                    $dataPost['UpdatedBy'] = $myNIP."/".$myName;
                    $rejectData = $this->General_model->updateData("db_employees.employees",$dataPost,$conditions);
                    $message = ($rejectData ? "Successfully":"Failed")." saved.";
                }else if($data_arr['ACT'] == 0){ //for approved
                    if(!empty($isExist->Logs)){
                        $Logs = json_decode($isExist->Logs);
                        
                        if(!empty($Logs->Photo)){
                            $reqPhoto = $Logs->Photo;
                            $currPhoto = $isExist->Photo;
                            $folderPCAM = 'uploads/employees/';
                            if(file_exists($folderPCAM.$currPhoto)){
                                if(rename($folderPCAM.$reqPhoto, $folderPCAM.$currPhoto)){
                                    $Logs->Photo = $currPhoto;
                                }
                            }                            
                        }
                        if(!empty($Logs->MyBank)){
                            $MyBank = $Logs->MyBank; unset($Logs->MyBank);
                            foreach ($MyBank as $b) {
                                if(!empty($b->ID)){
                                    $conditions['ID'] = $b->ID;
                                    $updateChild = $this->General_model->updateData("db_employees.employees_bank_account",$b,$conditions);
                                    unset($conditions['ID']);
                                }else{
                                    unset($b->ID);
                                    $insertChild = $this->General_model->insertData("db_employees.employees_bank_account",$b);
                                }
                            }
                        }
                        if(!empty($Logs->MyFamily)){
                            $MyFamily = $Logs->MyFamily;unset($Logs->MyFamily);
                            foreach ($MyFamily as $b) {
                                if(!empty($b->ID)){
                                    $conditions['ID'] = $b->ID;
                                    $updateChild = $this->General_model->updateData("db_employees.employees_family_member",$b,$conditions);
                                    unset($conditions['ID']);
                                }else{
                                    unset($b->ID);
                                    $insertChild = $this->General_model->insertData("db_employees.employees_family_member",$b);
                                }
                            }
                        }
                        if(!empty($Logs->MyEducation)){
                            $MyEducation = $Logs->MyEducation;unset($Logs->MyEducation);
                            foreach ($MyEducation as $b) {
                                if(!empty($b->ID)){
                                    $conditions['ID'] = $b->ID;
                                    $updateChild = $this->General_model->updateData("db_employees.employees_educations",$b,$conditions);
                                    unset($conditions['ID']);
                                }else{
                                    unset($b->ID);
                                    $insertChild = $this->General_model->insertData("db_employees.employees_educations",$b);
                                }
                            }
                        }
                        if(!empty($Logs->MyEducationNonFormal)){
                            $MyEducationNonFormal = $Logs->MyEducationNonFormal;unset($Logs->MyEducationNonFormal);
                            foreach ($MyEducationNonFormal as $b) {
                                if(!empty($b->ID)){
                                    $conditions['ID'] = $b->ID;
                                    $updateChild = $this->General_model->updateData("db_employees.employees_educations_non_formal",$b,$conditions);
                                    unset($conditions['ID']);
                                }else{
                                    unset($b->ID);
                                    if($b->instituteName != ''){
                                        $insertChild = $this->General_model->insertData("db_employees.employees_educations_non_formal",$b);
                                    }
                                }
                            }
                        }
                        if(!empty($Logs->MyEducationTraining)){
                            $MyEducationTraining = $Logs->MyEducationTraining;unset($Logs->MyEducationTraining);
                            foreach ($MyEducationTraining as $b) {
                                if(!empty($b->ID)){
                                    $conditions['ID'] = $b->ID;
                                    $updateChild = $this->General_model->updateData("db_employees.employees_educations_training",$b,$conditions);
                                    unset($conditions['ID']);
                                }else{
                                    unset($b->ID);
                                    $insertChild = $this->General_model->insertData("db_employees.employees_educations_training",$b);
                                }
                            }
                        }
                        if(!empty($Logs->MyExperience)){
                            $MyExperience = $Logs->MyExperience;unset($Logs->MyExperience);
                            foreach ($MyExperience as $b) {
                                if(!empty($b->ID)){
                                    $conditions['ID'] = $b->ID;
                                    $updateChild = $this->General_model->updateData("db_employees.employees_experience",$b,$conditions);
                                    unset($conditions['ID']);
                                }else{
                                    unset($b->ID);
                                    $insertChild = $this->General_model->insertData("db_employees.employees_experience",$b);
                                }
                            }
                        }

                        if(!empty($Logs->Signature)){
                            $data = $Logs->Signature;

                            list($type, $data) = explode(';', $data);
                            list(, $data)      = explode(',', $data);
                            $data = base64_decode($data);
                            $filenameSignature = $data_arr['NIP'].'.png';
                            file_put_contents('./uploads/signature/'.$filenameSignature, $data);
                            $Logs->Signature= $filenameSignature;
                        }

                        $Logs->Logs = null;
                        $Logs->isApproved = null;
                        $Logs->NoteApproved = null;
                        $Logs->UpdatedBy = $myNIP."/".$myName;
                        
                        if(!empty($conditions['ID'])){unset($conditions['ID']);}
                        $updatedPersonalData = $this->General_model->updateData("db_employees.employees",$Logs,$conditions);
                        $message = (($updatedPersonalData) ? "Successfully":"Failed")." saved.";
                        $isfinish = $updatedPersonalData;
                    }else{$message = "No data requested.";}
                }else{$message="Unknow request approved.";}
            }else{$message="Student data is not founded.";}
            $json = array("message"=>$message,"finish"=>$isfinish);
        }

        echo json_encode($json);
    }
    /*END ADDED BY FEBRI @ NOV 2019*/


    /*ADDED BY FEBI @ FEB 2020*/
    public function tab_menu_new_emp($page,$NIP){
        $department = parent::__getDepartement();
        $data['page'] = $page;
        //$data['employee'] = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
        $this->load->model('global-informations/Globalinformation_model');
        $param[] = array("field"=>"em.NIP","data"=>" = ".$NIP." ","filter"=>"AND",);    
        $data['employee'] = $this->Globalinformation_model->fetchEmployee(false,$param)->row();
        $getHistoricalJoin = $this->General_model->fetchData("db_employees.employees_joindate",array("NIP"=>$NIP),"ID","ASC")->row();
        $data['employee']->HistoricalJoin = (!empty($getHistoricalJoin) ? $getHistoricalJoin : null);
        $content = $this->load->view('page/'.$department.'/employees/tab_menu_new_emp',$data,true);
        $this->temp($content);
    }


    public function additionalInformation($NIP){
        $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
        if(!empty($isExist)){
            $department = parent::__getDepartement();
            $data['NIP'] = $NIP;
            $data['detail'] = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
            
            $page = $this->load->view('page/'.$department.'/employees/additional-information',$data,true);
            $this->tab_menu_new_emp($page,$NIP);
        }else{show_404();}
    }


    public function additionalInformationSave(){
        $data = $this->input->post();
        if($data){
            $action = "additional-info";
            if(!empty($data['action'])){
                $action = $data['action'];unset($data['action']);
            }
            $bankID = $data['bankID'];
            $bankName = $data['bankName'];
            $bankAccName = $data['bankAccName'];
            $bankAccNum = $data['bankAccNum'];
            unset($data['bankID']);
            unset($data['bankName']);
            unset($data['bankAccName']);
            unset($data['bankAccNum']);

            $conditions = array("NIP"=>$data['NIP']);
            $myNIP = $this->session->userdata('NIP');
            $myName = $this->session->userdata('Name');
            $data['UpdatedBy'] = $myNIP.'/'.$myName;
            $update = $this->General_model->updateData("db_employees.employees",$data,$conditions);
            if($update){
                if(!empty($bankName)){
                    for ($i=0; $i < count($bankName); $i++) { 
                        if(!empty($bankID[$i])){
                            $updateBank = $this->General_model->updateData("db_employees.employees_bank_account",array("NIP"=>$data['NIP'],"bank"=>$bankName[$i],"accountName"=>$bankAccName[$i],"accountNumber"=>$bankAccNum[$i]), array("ID"=>$bankID[$i]));
                        }else{
                            $insertBank = $this->General_model->insertData("db_employees.employees_bank_account",array("NIP"=>$data['NIP'],"bank"=>$bankName[$i],"accountName"=>$bankAccName[$i],"accountNumber"=>$bankAccNum[$i]));                        
                        }
                    }
                }

                $message = "Successfully saved.";
            }else{$message = "Failed saved.";}

            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/'.$action.'/'.$data['NIP']));

        }else{show_404();}
    }


    public function family($NIP){
        $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
        if(!empty($isExist)){
            $department = parent::__getDepartement();
            $data['NIP'] = $NIP;
            $data['familytree'] = $this->General_model->fetchData("db_employees.master_family_relations",array("IsActive"=>1))->result();
            $data['educationLevel'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
            $data['myfamily'] = $this->General_model->fetchData("db_employees.employees_family_member",array("NIP"=>$NIP))->result();
            $page = $this->load->view('page/'.$department.'/employees/family',$data,true);
            $this->tab_menu_new_emp($page,$NIP);
        }else{show_404();}
    }


    public function familySave(){
        $data = $this->input->post();
        if($data){
            if(!empty($data['relation'])){
                $dataPost = array();
                for ($i=0; $i < count($data['relation']); $i++) { 
                    $dataPost = array("NIP"=>$data['NIP'],"name"=>$data['name'][$i],"relationID"=>$data['relation'][$i], "gender"=>$data['gender'][$i], "placeBirth"=>$data["placeBirth"][$i], "birthdate"=>$data["birthdate"][$i], "lastEduID"=>$data['lastEdu'][$i], "isCoverInsurance"=>$data['isCoverInsurance'][$i] );
                    if(!empty($data['familyID'][$i])){
                        $isExist = $this->General_model->fetchData("db_employees.employees_family_member",array("ID"=>$data['familyID'][$i]))->row();
                        if(!empty($isExist)){
                            $update = $this->General_model->updateData("db_employees.employees_family_member",$dataPost,array("ID"=>$data['familyID'][$i]));
                            $message = (($update) ? "Successfully":"Failed")." updated.";
                        }else{$message = "Data not founded.";}
                    }else{
                        $insert = $this->General_model->insertData("db_employees.employees_family_member",$dataPost);
                        $message = (($insert) ? "Successfully":"Failed")." saved.";
                    }
                }

                $conditions = array("NIP"=>$data['NIP']);
                $myNIP = $this->session->userdata('NIP');
                $myName = $this->session->userdata('Name');
                $update = $this->General_model->updateData("db_employees.employees",array("UpdatedBy"=>$myNIP.'/'.$myName),$conditions);


                //var_dump($dataPost);die();
            }else{$message="Cannot saved. Empty data post.";}

            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/family/'.$data['NIP']));

        }else{show_404();}
    }
    

    public function careerLevel($NIP){
        $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
        if(!empty($isExist)){
            $department = parent::__getDepartement();
            $data['NIP'] = $NIP;
            $data['status'] = $this->General_model->fetchData("db_employees.master_status",array("IsActive"=>1))->result();
            $data['level'] = $this->General_model->fetchData("db_employees.master_level",array("IsActive"=>1))->result();
            //$data['division'] = $this->General_model->fetchData("db_employees.sto_temp",array("isMainSTO"=>1, "typeNode"=>1,"isActive"=>1))->result();
            $data['division'] = $this->General_model->fetchData("db_employees.division",array())->result();
            $data['position'] = $this->General_model->fetchData("db_employees.position",array())->result();
            $data['employees_status'] = $this->General_model->fetchData("db_employees.employees_status","Type != 'lec' and IDStatus != '-2'")->result();
            $data['detail'] = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
            //$data['currComp'] = $this->General_model->fetchData("db_employees.master_company",array("ID"=>1))->row();
            $page = $this->load->view('page/'.$department.'/employees/career-level',$data,true);
            $this->tab_menu_new_emp($page,$NIP);
        }else{show_404();}
    }


    /* ## CAREER SAVING BY STO ##
    public function careerSave(){
        $data = $this->input->post();
        if($data){
            $message = "";
            if(!empty($data['JoinDate'])){
                for ($h=0; $h < count($data['JoinDate']); $h++) { 
                    $dataPostJoin = array("NIP"=>$data['NIP'],"JoinDate"=>$data['JoinDate'][$h],"ResignDate"=>(!empty($data['ResignDate'][$h]) ? $data['ResignDate'][$h] : null ), "StatusEmployeeID"=>$data['StatusEmployeeID'][$h]);
                    if(!empty($data['joinID'][$h])){
                        $isExist = $this->General_model->fetchData("db_employees.employees_joindate",array("ID"=>$data['joinID'][$h]))->row();
                        if(!empty($isExist)){
                            $updateJoin = $this->General_model->updateData("db_employees.employees_joindate",$dataPostJoin,array("ID"=>$data['joinID'][$h]));
                            $message = (($updateJoin) ? "Successfully":"Failed")." updated.";                            
                        }else{$message = "Historical join not founded.";}
                    }else{

                        $insertJoin = $this->General_model->insertData("db_employees.employees_joindate",$dataPostJoin);
                        $message = (($insertJoin) ? "Successfully":"Failed")." saved.";
                    }
                }

            }else{$message .= "Cannot saved. Empty dataPost";}
            

            if(!empty($data['startJoin'])){
                for ($i=0; $i < count($data['startJoin']) ; $i++) {
                    $dataPost = array("NIP"=>$data['NIP'],"StartJoin"=>$data['startJoin'][$i], "EndJoin"=>$data['endJoin'][$i], "LevelID"=>$data['statusLevelID'][$i],"DepartmentID"=>$data['division'][$i],"PositionID"=>$data['position'][$i],"JobTitle"=>$data['jobTitle'][$i],"Superior"=>$data['superior'][$i],"StatusID"=>$data['statusID'][$i],"Remarks"=>$data['remarks'][$i],"isShowSTO"=>0);
                    $dataSTOUser = array("NIP"=>$data['NIP'],"STOID"=>$data['position'][$i],"IsActive"=>1,"StatusID"=>$data['statusID'][$i],"JobTitle"=>$data['jobTitle'][$i]);
                    if(!empty($data['careerID'][$i])){
                        $isExist = $this->General_model->fetchData("db_employees.employees_career",array("ID"=>$data['careerID'][$i]))->row();
                        if(!empty($isExist)){
                            $update = $this->General_model->updateData("db_employees.employees_career",$dataPost,array("ID"=>$data['careerID'][$i]));
                            $message = (($update) ? "Successfully":"Failed")." updated.";
                        }else{$message ="Data not founded.";}
                    }else{
                        $insert = $this->General_model->insertData("db_employees.employees_career",$dataPost);
                        $message .= (($insert) ? "Successfully":"Failed")." saved.";
                    }
                }
            }else{$message .= "Cannot saved. Empty dataPost";}

            $conditions = array("NIP"=>$data['NIP']);
            $myNIP = $this->session->userdata('NIP');
            $myName = $this->session->userdata('Name');
            $update = $this->General_model->updateData("db_employees.employees",array("UpdatedBy"=>$myNIP.'/'.$myName),$conditions);

            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/career-level/'.$data['NIP']));

        }else{show_404();}
    }
    */

    ## CAREER SAVING BY EXISTING POSITION MAIN ##
    public function careerSave(){
        $data = $this->input->post();
        if($data){
            $message = "";
            if(!empty($data['JoinDate'])){
                for ($h=0; $h < count($data['JoinDate']); $h++) { 
                    $dataPostJoin = array("NIP"=>$data['NIP'],"JoinDate"=>$data['JoinDate'][$h],"ResignDate"=>(!empty($data['ResignDate'][$h]) ? $data['ResignDate'][$h] : null ), "StatusEmployeeID"=>$data['StatusEmployeeID'][$h]);
                    if(!empty($data['joinID'][$h])){
                        $isExist = $this->General_model->fetchData("db_employees.employees_joindate",array("ID"=>$data['joinID'][$h]))->row();
                        if(!empty($isExist)){
                            $updateJoin = $this->General_model->updateData("db_employees.employees_joindate",$dataPostJoin,array("ID"=>$data['joinID'][$h]));
                            $message = (($updateJoin) ? "Successfully":"Failed")." updated.";                            
                        }else{$message = "Historical join not founded.";}
                    }else{

                        $insertJoin = $this->General_model->insertData("db_employees.employees_joindate",$dataPostJoin);
                        $message = (($insertJoin) ? "Successfully":"Failed")." saved.";
                    }
                }

            }else{$message .= "Cannot saved. Empty dataPost";}
            

            if(!empty($data['startJoin'])){
                for ($i=0; $i < count($data['startJoin']) ; $i++) {
                    $dataPost = array("NIP"=>$data['NIP'],"StartJoin"=>$data['startJoin'][$i], "EndJoin"=>$data['endJoin'][$i], "LevelID"=>$data['statusLevelID'][$i],"DepartmentID"=>$data['division'][$i],"PositionID"=>$data['position'][$i],"JobTitle"=>$data['jobTitle'][$i],"Superior"=>$data['superior'][$i],"StatusID"=>$data['statusID'][$i],"Remarks"=>$data['remarks'][$i],"isShowSTO"=>0);
                    $dataSTOUser = array("NIP"=>$data['NIP'],"STOID"=>$data['position'][$i],"IsActive"=>1,"StatusID"=>$data['statusID'][$i],"JobTitle"=>$data['jobTitle'][$i]);
                    if(!empty($data['careerID'][$i])){
                        $isExist = $this->General_model->fetchData("db_employees.employees_career",array("ID"=>$data['careerID'][$i]))->row();
                        if(!empty($isExist)){
                            $update = $this->General_model->updateData("db_employees.employees_career",$dataPost,array("ID"=>$data['careerID'][$i]));
                            $message = (($update) ? "Successfully":"Failed")." updated.";
                        }else{$message ="Data not founded.";}
                    }else{
                        $insert = $this->General_model->insertData("db_employees.employees_career",$dataPost);
                        $message .= (($insert) ? "Successfully":"Failed")." saved.";
                    }
                }
            }else{$message .= "Cannot saved. Empty dataPost";}

            $conditions = array("NIP"=>$data['NIP']);
            $myNIP = $this->session->userdata('NIP');
            $myName = $this->session->userdata('Name');
            $update = $this->General_model->updateData("db_employees.employees",array("UpdatedBy"=>$myNIP.'/'.$myName),$conditions);

            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/career-level/'.$data['NIP']));

        }else{show_404();}
    }

    public function educations($NIP){
        $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
        if(!empty($isExist)){
            $department = parent::__getDepartement();
            $data['NIP'] = $NIP;
            $data['educationLevel'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
            $data['industry'] = $this->General_model->fetchData("db_employees.master_industry_type",array("IsActive"=>1))->result();
            $page = $this->load->view('page/'.$department.'/employees/education',$data,true);
            $this->tab_menu_new_emp($page,$NIP);
        }else{show_404();}
    }


    public function educationSave(){
        $data = $this->input->post();
        if($data){
            $message ="";
            if(!empty($data['eduLevel'])){
                for ($i=0; $i < count($data['eduLevel']); $i++) { 
                    if(!empty($data['eduLevel'][$i])){
                        $dataPostEdu = array("NIP"=>$data['NIP'],"levelEduID"=>$data['eduLevel'][$i], "instituteName"=>$data['eduInstitute'][$i], "location"=>$data['eduCC'][$i], "major"=>$data['eduMajor'][$i], "graduation"=>$data['eduGraduation'][$i], "gpa"=>$data['eduGPA'][$i], "Note"=>$data['Note'][$i] );
                        if(!empty($data['eduID'][$i])){
                            //update
                            $update = $this->General_model->updateData("db_employees.employees_educations",$dataPostEdu,array("ID"=>$data['eduID'][$i]));
                            $message = (($update) ? "Successfully":"Failed")." updated.";
                        }else{
                            //insert
                            $insert = $this->General_model->insertData("db_employees.employees_educations",$dataPostEdu);
                            $message = (($insert) ? "Successfully":"Failed")." saved.";
                        }
                    }
                }
            }else{$message = "Cannot saved. Empty data post.";}

            if(!empty($data['nonEduInstitute'])){
                for ($j=0; $j < count($data['nonEduInstitute']); $j++) { 
                    if(!empty($data['nonEduInstitute'][$j])){
                        $dataPostNonEdu = array("NIP"=>$data['NIP'],"subject"=>$data['nonEduSubject'][$j],"instituteName"=>$data['nonEduInstitute'][$j], "start_event"=>$data['nonEduStart'][$j], "end_event"=>$data['nonEduEnd'][$j], "location"=>$data['nonEduCC'][$j] );
                        if(!empty($data['nonEduID'][$j])){
                            $update = $this->General_model->updateData("db_employees.employees_educations_non_formal",$dataPostNonEdu,array("ID"=>$data['nonEduID'][$j]));
                            $message = (($update) ? "Successfully":"Failed")." updated.";
                        }else{
                            //insert
                            $insert = $this->General_model->insertData("db_employees.employees_educations_non_formal",$dataPostNonEdu);
                            $message = (($insert) ? "Successfully":"Failed")." saved.";
                        }
                    }
                }
            }
            
            if(!empty($data['trainingTitle'])){
                for ($k=0; $k < count($data['trainingTitle']); $k++) { 
                    if(!empty($data['trainingTitle'][$k])){
                        $dataPostTraining = array("NIP"=>$data['NIP'],"name"=>$data['trainingTitle'][$k],"trainer"=>$data['trainingTrainer'][$k], "start_event"=>$data['trainingStart'][$k], "end_event"=>$data['trainingEnd'][$k], "location"=>$data['trainingLocation'][$k], "feedback"=>$data['trainingFeedback'][$k] );
                        if(!empty($data['trainingID'][$k])){
                            $update = $this->General_model->updateData("db_employees.employees_educations_training",$dataPostTraining,array("ID"=>$data['trainingID'][$k]));
                            $message = (($update) ? "Successfully":"Failed")." updated.";
                        }else{
                            //insert
                            $insert = $this->General_model->insertData("db_employees.employees_educations_training",$dataPostTraining);
                            $message = (($insert) ? "Successfully":"Failed")." saved.";
                        }
                    }
                }
            }

            $conditions = array("NIP"=>$data['NIP']);
            $myNIP = $this->session->userdata('NIP');
            $myName = $this->session->userdata('Name');
            $update = $this->General_model->updateData("db_employees.employees",array("UpdatedBy"=>$myNIP.'/'.$myName),$conditions);

            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/educations/'.$data['NIP']));

        }else{show_404();}
    }


    public function training($NIP){
        $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
        if(!empty($isExist)){
            $department = parent::__getDepartement();
            $data['NIP'] = $NIP;
            $page = $this->load->view('page/'.$department.'/employees/training',$data,true);
            $this->tab_menu_new_emp($page,$NIP);
        }else{show_404();}
    }


    public function trainingSave(){
        $data = $this->input->post();
        if($data){
            $message ="";
            if(!empty($data['trainingTitle'])){
                for ($k=0; $k < count($data['trainingTitle']); $k++) { 
                    if(!empty($data['trainingTitle'][$k])){
                        $err_msg = "";
                        if(!empty($_FILES['certificate']['name'][$k])){
                            
                            $ispic = false;
                            $file_name = $_FILES['certificate']['name'][$k];
                            $file_size =$_FILES['certificate']['size'][$k];
                            $file_tmp =$_FILES['certificate']['tmp_name'][$k];
                            $file_type=$_FILES['certificate']['type'][$k];
                            if($file_type == "image/jpeg" || $file_type == "image/png"){
                                $ispic = true;
                            }else {
                                $ispic = false;
                                $err_msg     .= "Extention image '".$file_name."' doesn't allowed.";
                            }
                            if($file_size > 2000000){ //2Mb
                                $ispic = false;
                                $err_msg     .= "Size of image '".$file_name."'s too large from 2Mb.";
                            }else { $ispic = true; }

                            $trainingTitleFilename = preg_replace('/\s+/', "_", $data['trainingTitle'][$k]);
                            $newFilename = $data['NIP']."-TRAINING-".$trainingTitleFilename."-".date('ymd').".jpg";

                            $folderPCAM = 'uploads/profile/training';
                            if(!file_exists($folderPCAM)){
                                mkdir($folderPCAM,0777);
                                $error403 = "<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
                                file_put_contents($folderPCAM."/index.html", $error403);
                            }
                            if($ispic){
                                $moveimage = move_uploaded_file($file_tmp,$folderPCAM."//".$newFilename);
                                if(!$moveimage){
                                    $err_msg .= "<b>Failed insert file.</b><br>";
                                }else{
                                    $data['certificate'][$k] = $newFilename;
                                }
                            }
                        }

                        $dataPostTraining = array("NIP"=>$data['NIP'],
                                                  "name"=>$data['trainingTitle'][$k],
                                                  "organizer"=>$data['organizer'][$k], 
                                                  "start_event"=>$data['trainingStart'][$k]." ".(!empty($data['trainingStartTime'][$k]) ? $data['trainingStartTime'][$k].":00" : "00:00:00"), 
                                                  "end_event"=>$data['trainingEnd'][$k]." ".(!empty($data['trainingEndTime'][$k]) ? $data['trainingEndTime'][$k].":00" : "00:00:00"), 
                                                  "location"=>$data['trainingLocation'][$k], 
                                                  "category"=>$data['trainingCategory'][$k],
                                                  "costCompany"=>$data['trainingCostCompany'][$k],
                                                  "costEmployee"=>$data['trainingCostEmployee'][$k],
                                                  "certificate"=>(!empty($data['certificate'][$k]) ? $data['certificate'][$k] : null),
                                                   );
                        if(!empty($data['trainingID'][$k])){
                            $update = $this->General_model->updateData("db_employees.employees_educations_training",$dataPostTraining,array("ID"=>$data['trainingID'][$k]));
                            $message = (($update) ? "Successfully":"Failed")." updated.".(!empty($err_msg) ? "<br>".$err_msg:"");
                        }else{
                            //insert
                            $insert = $this->General_model->insertData("db_employees.employees_educations_training",$dataPostTraining);
                            $message = (($insert) ? "Successfully":"Failed")." saved.".(!empty($err_msg) ? "<br>".$err_msg:"");
                        } 

                    }
                }

                $conditions = array("NIP"=>$data['NIP']);
                $myNIP = $this->session->userdata('NIP');
                $myName = $this->session->userdata('Name');
                $update = $this->General_model->updateData("db_employees.employees",array("UpdatedBy"=>$myNIP.'/'.$myName),$conditions);
            }           

            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/training/'.$data['NIP']));

        }else{show_404();}
    }
    

    public function workExperience($NIP){
        $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
        if(!empty($isExist)){
            $department = parent::__getDepartement();
            $data['NIP'] = $NIP;
            $data['educationLevel'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
            $data['industry'] = $this->General_model->fetchData("db_employees.master_industry_type",array("IsActive"=>1))->result();
            $page = $this->load->view('page/'.$department.'/employees/experience',$data,true);
            $this->tab_menu_new_emp($page,$NIP);
        }else{show_404();}
    }


    public function workExperienceSave(){
        $data = $this->input->post();
        if($data){
            $message = "";
            if(!empty($data['comName'])){
                for ($i=0; $i < count($data['comName']); $i++) { 
                    //check company name
                    /*$isMasterCompany = $this->General_model->fetchData("db_employees.master_company","Name like '%".$data['comName'][$i]."%'")->row();
                    if(empty($isMasterCompany)){
                        $insertCompany = $this->General_model->insertData("db_employees.master_company",array("Name"=>$data['comName'][$i],"IsActive"=>1,"IndustryID"=>$data['comIndustry'][$i]));
                    }*/
                    $dataPost = array("NIP"=>$data['NIP'],"company"=>$data['comName'][$i],"industryID"=>$data['comIndustry'][$i], "start_join"=>$data['comStartJoin'][$i], "end_join"=>$data['comEndJoin'][$i], "jobTitle"=>$data['comJobTitle'][$i], "reason"=>$data['comReason'][$i], "Note"=>$data['Note'][$i] );
                    if(!empty($data['comID'][$i])){
                        $update = $this->General_model->updateData("db_employees.employees_experience",$dataPost,array("ID"=>$data['comID'][$i]));
                        $message = (($update) ? "Successfully":"Failed")." updated.";
                    }else{
                        $insert = $this->General_model->insertData("db_employees.employees_experience",$dataPost);
                        $message = (($insert) ? "Successfully":"Failed")." saved.";
                    }
                }


            $conditions = array("NIP"=>$data['NIP']);
            $myNIP = $this->session->userdata('NIP');
            $myName = $this->session->userdata('Name');
            $update = $this->General_model->updateData("db_employees.employees",array("UpdatedBy"=>$myNIP.'/'.$myName),$conditions);


            }else{$message = "Cannot saved. Empty data post.";}
            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/work-experience/'.$data['NIP']));
        }else{show_404();}
    }


    public function attendance($NIP){
        $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
        if(!empty($isExist)){
            $department = parent::__getDepartement();
            $data['NIP'] = $NIP;
            $data['educationLevel'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
            $data['industry'] = $this->General_model->fetchData("db_employees.master_industry_type",array("IsActive"=>1))->result();
            $page = $this->load->view('page/'.$department.'/employees/attendance',$data,true);
            $this->tab_menu_new_emp($page,$NIP);
        }else{show_404();}
    }


    public function credentialBenefit($NIP){
        $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
        if(!empty($isExist)){
            $department = parent::__getDepartement();
            $data['NIP'] = $NIP;
            $data['educationLevel'] = $this->General_model->fetchData("db_employees.level_education",array())->result();
            $data['industry'] = $this->General_model->fetchData("db_employees.master_industry_type",array("IsActive"=>1))->result();
            $page = $this->load->view('page/'.$department.'/employees/credential-benefit',$data,true);
            $this->tab_menu_new_emp($page,$NIP);
        }else{show_404();}
    }


    public function detailEmployeeOBJ(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$data_arr['NIP']))->row();
            if(!empty($isExist)){
                //$isExist->MyCareer = $this->General_model->fetchData("db_employees.employees_career",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyHistorical = $this->General_model->fetchData("db_employees.employees_joindate",array("NIP"=>$isExist->NIP),"ID","desc")->result();
                $isExist->MyCareer = $this->m_hr->getEmpCareer(array("a.NIP"=>$isExist->NIP,"isShowSTO"=>0))->result();
                $isExist->MyBank = $this->General_model->fetchData("db_employees.employees_bank_account",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducation = $this->General_model->fetchData("db_employees.employees_educations",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationNonFormal = $this->General_model->fetchData("db_employees.employees_educations_non_formal",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationTraining = $this->General_model->fetchData("db_employees.employees_educations_training",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyFamily = $this->General_model->fetchData("db_employees.employees_family_member",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyExperience = $this->General_model->fetchData("db_employees.employees_experience",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyBPJS = $this->General_model->fetchData("db_employees.employees_bpjs",array("NIP"=>$isExist->NIP),"Type","asc")->result();
                $isExist->MyAllowance = $this->General_model->fetchData("db_employees.employees_allowance",array("NIP"=>$isExist->NIP))->result();
                $json = $isExist;
            }
        }
        echo json_encode($json);
    }


    public function removeAdditonal(){
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_employees.".$data_arr['TABLE'],array("ID"=>$data_arr['ID']))->row();
            if(!empty($isExist)){
                $remove = $this->General_model->deleteData("db_employees.".$data_arr['TABLE'],array("ID"=>$data_arr['ID']));
                $message = (($remove) ? "Successfully":"Failed")." removed.";
            }else{$message = "Data not founded.";}
            $json = array("message"=>$message);
        }
        echo json_encode($json);
    }


    public function credentialBenefitSave(){
        $data = $this->input->post();
        if($data){
            $action = $data['action'];unset($data['action']);
            $message = "";
            $conditions = array("NIP"=>$data['NIP']);
            $isExist = $this->General_model->fetchData("db_employees.employees",$conditions)->row();
            if(empty($isExist)){show_404();}
            
            if($action == "BPJS"){
                if(!empty($data['CardNumber'])){
                    $isvalid = false;
                    for ($i=0; $i < count($data['CardNumber']) ; $i++) { 
                        if(!empty($data['CardNumber'][$i]) && !empty($data['NIP'])){
                            $dataPost = array("NIP"=>$data['NIP'],"CardNumber"=>$data['CardNumber'][$i],"Type"=>$data['Type'][$i],"CutsEmployer"=>$data['CutsEmployer'][$i],"CutsEmployee"=>$data['CutsEmployee'][$i]);
                            
                            //check card number on employee
                            if(!empty($data['Type'][$i])){
                                $bpjsType = "";
                                switch ($data['Type'][$i]) {
                                case 1:
                                    $bpjsType = "IDBpjstk";
                                    break;
                                case 2:
                                    $bpjsType = "IDBpjspensiun";
                                    break;
                                case 3:
                                    $bpjsType = "IDBpjskesehatan";
                                    break;                                
                                default:
                                    $bpjsType = "";
                                    break;
                                }
                                if(!empty($bpjsType)){
                                    $conditions[$bpjsType] = $data['CardNumber'][$i];
                                    $isExistCard = $this->General_model->fetchData("db_employees.employees",$conditions)->row();
                                    if(!empty($isExistCard)){
                                        $isvalid = true;
                                        unset($conditions[$bpjsType]);
                                    }
                                }
                            }

                            if($isvalid){
                                $conditions['CardNumber'] = $data['CardNumber'][$i];
                                $isExistBPJS = $this->General_model->fetchData("db_employees.employees_bpjs",$conditions)->row();
                                if(!empty($isExistBPJS)){
                                    //update
                                    $update = $this->General_model->updateData("db_employees.employees_bpjs",$dataPost,$conditions);
                                    $message = (($update) ? "Successfully":"Failed")." updated.";
                                }else{
                                    //insert
                                    $insert = $this->General_model->insertData("db_employees.employees_bpjs",$dataPost);
                                    $message = (($insert) ? "Successfully":"Failed")." saved.";
                                }
                                unset($conditions['CardNumber']);
                            }else{$message="Card Number [".$data['CardNumber'][$i]."] is not exist.";}
                        }else{$message = "No post data.";}
                    }
                }else{$message = "There's no post data on BPJS.";}
            }else if($action = "ALLOWANCE"){
                if(!empty($data['Allowance'])){
                    for ($a=0; $a < count($data['Allowance']); $a++) { 
                        $dataPost = array("NIP"=>$data['NIP'],"Allowance"=>$data['Allowance'][$a],"Price"=>$data['Price'][$a],"Note"=>$data['Note'][$a]);
                        if(!empty($data['ID'][$a])){
                            $execute = $this->General_model->updateData("db_employees.employees_allowance",$dataPost,array("ID"=>$data['ID']));
                        }else{
                            $execute = $this->General_model->insertData("db_employees.employees_allowance",$dataPost);
                        }
                        $message = (($execute) ? "Successfully":"Failed")." saved.";
                    }
                }else{$message = "There's no post data on Allowance.";}
            }

            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/credential-benefit/'.$data['NIP']));
        }else{show_404();}
    }


    /*ATTENDANCE TEMPORARY
    ATTENDANCE EMPLOYEE*/
    public function attendanceTemp(){
        $data['statusstd'] = $this->General_model->fetchData("db_employees.employees_status","IDStatus != '-2'","IDStatus","asc")->result();
        $data['division'] = $this->General_model->fetchData("db_employees.division",array())->result();
        $data['position'] = $this->General_model->fetchData("db_employees.position",array())->result();
        $data['religion'] = $this->General_model->fetchData("db_employees.religion",array())->result();
        $data['level_education'] = $this->General_model->fetchData("db_employees.level_education",array())->result();

        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/attendance-temp/index',$data,true);
        $this->temp($page);
    }


    public function fetchAttdTempEmp(){
        set_time_limit(0);
        $this->load->helper("General_helper");
        $reqdata = $this->input->post(); 
        $myDivisionID = $this->session->userdata('PositionMain')['IDDivision'];

        if($reqdata){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
            $param = array();$orderBy=" lem.ID DESC ";

            if(!empty($reqdata['search']['value']) ) {
                $search = $reqdata['search']['value'];

                $param[] = array("field"=>"(em.NIP","data"=>" like '%".$search."%' ","filter"=>"AND",);
                $param[] = array("field"=>"em.Name","data"=>" like '%".$search."%' )","filter"=>"OR",);
            }
            if(!empty($data_arr['Filter'])){
                $parse = parse_str($data_arr['Filter'],$output);

                //check data emp if lecturers
                if(!empty($output['isLecturer'])){
                    $divLect = '14';
                    $param[] = array("field"=>"(em.PositionMain","data"=>" like'".$divLect.".%' ","filter"=>"AND",);
                    $param[] = array("field"=>"em.PositionOther1","data"=>" like'".$divLect.".%' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther2","data"=>" like'".$divLect.".%' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther3","data"=>" like'".$divLect.".%' )","filter"=>"OR",);
                    if( !empty($output['position'])){
                        $param[] = array("field"=>"em.PositionMain","data"=>" = '".$divLect.".".$output['position']."' ","filter"=>"AND",);
                    }
                    if(!empty($output['status'])){
                        $sn = 1;
                        $dataArrStatus = array();
                        $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                        if(count($output['status']) == 1){
                            $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" ='".$output['status'][0]."' ","filter"=> "" );
                        }else{
                            foreach ($output['status'] as $s) {
                                $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" ='".$s."' ".((($sn < count($output['status'])) ? ' OR ':'')) ,"filter"=> null );
                                $sn++;
                            }
                        }
                        $param[] = array("field"=>")","data"=>null,"filter"=>null);
                    }
                    if(!empty($output['study_program'])){
                        $sn = 1;
                        $dataArrStatus = array();
                        $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                        if(count($output['study_program']) == 1){
                            $param[] = array("field"=>"em.ProdiID","data"=>" ='".$output['study_program'][0]."' ","filter"=> "" );
                        }else{
                            foreach ($output['study_program'] as $s) {
                                $param[] = array("field"=>"em.ProdiID","data"=>" ='".$s."' ".((($sn < count($output['study_program'])) ? ' OR ':'')) ,"filter"=> null );
                                $sn++;
                            }
                        }
                        $param[] = array("field"=>")","data"=>null,"filter"=>null);
                    }
                }
                //check data for employee
                else{
                    if(!empty($output['statusstd'])){
                        $sn = 1;
                        $dataArrStatus = array();
                        $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                        if(count($output['statusstd']) == 1){
                            $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" ='".$output['statusstd'][0]."' ","filter"=> "" );
                        }else{
                            foreach ($output['statusstd'] as $s) {
                                $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" ='".$s."' ".((($sn < count($output['statusstd'])) ? ' OR ':'')) ,"filter"=> null );
                                $sn++;
                            }
                        }
                        $param[] = array("field"=>")","data"=>null,"filter"=>null);
                    }else{
                        $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                        $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" = 1 or " ,"filter"=> null );    
                        $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" = 2 or " ,"filter"=> null );    
                        $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" = 7 " ,"filter"=> null );    
                        $param[] = array("field"=>")","data"=>null,"filter"=>null);    
                    }
                }

                if(!empty($output['division'])){
                    if(!empty($output['position'])){
                        $param[] = array("field"=>"(em.PositionMain","data"=>" = '".$output['division'].".".$output['position']."' ","filter"=>"AND",);
                        $param[] = array("field"=>"em.PositionOther1","data"=>" = '".$output['division'].".".$output['position']."' ","filter"=>"OR",);
                        $param[] = array("field"=>"em.PositionOther2","data"=>" = '".$output['division'].".".$output['position']."' ","filter"=>"OR",);
                        $param[] = array("field"=>"em.PositionOther3","data"=>" = '".$output['division'].".".$output['position']."' ) ","filter"=>"OR",);
                    }else{
                        $param[] = array("field"=>"(em.PositionMain","data"=>" like '".$output['division'].".%' ","filter"=>"AND",);
                        $param[] = array("field"=>"em.PositionOther1","data"=>" like '".$output['division'].".%' ","filter"=>"OR",);
                        $param[] = array("field"=>"em.PositionOther2","data"=>" like '".$output['division'].".%' ","filter"=>"OR",);
                        $param[] = array("field"=>"em.PositionOther3","data"=>" like '".$output['division'].".%' ) ","filter"=>"OR",);
                    }
                }else{
                    $param[] = array("field"=>"em.PositionMain","data"=>" like '".$myDivisionID.".%' ","filter"=>"AND",);
                }
                if(!empty($output['staff'])){
                    $param[] = array("field"=>"(em.NIP","data"=>" like '%".$output['staff']."%' ","filter"=>"AND",);
                    $param[] = array("field"=>"em.Name","data"=>" like '%".$output['staff']."%' )","filter"=>"OR",);
                }
                if(!empty($output['religion'])){
                    $sn = 1;
                    $dataArrStatus = array();
                    $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                    if(count($output['religion']) == 1){
                        $param[] = array("field"=>"em.ReligionID","data"=>" ='".$output['religion'][0]."' ","filter"=> "" );
                    }else{
                        foreach ($output['religion'] as $s) {
                            $param[] = array("field"=>"em.ReligionID","data"=>" ='".$s."' ".((($sn < count($output['religion'])) ? ' OR ':'')) ,"filter"=> null );
                            $sn++;
                        }
                    }
                    $param[] = array("field"=>")","data"=>null,"filter"=>null);
                }
                if(!empty($output['gender'])){
                    $sn = 1;
                    $dataArrStatus = array();
                    $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                    if(count($output['gender']) == 1){
                        $param[] = array("field"=>"em.Gender","data"=>" ='".$output['gender'][0]."' ","filter"=> "" );
                    }else{
                        foreach ($output['gender'] as $s) {
                            $param[] = array("field"=>"em.Gender","data"=>" ='".$s."' ".((($sn < count($output['gender'])) ? ' OR ':'')) ,"filter"=> null );
                            $sn++;
                        }
                    }
                    $param[] = array("field"=>")","data"=>null,"filter"=>null);
                }
                if(!empty($output['level_education'])){
                    $sn = 1;
                    $dataArrStatus = array();
                    $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                    if(count($output['level_education']) == 1){
                        $param[] = array("field"=>"em.LevelEducationID","data"=>" ='".$output['level_education'][0]."' ","filter"=> "" );
                    }else{
                        foreach ($output['level_education'] as $s) {
                            $param[] = array("field"=>"em.LevelEducationID","data"=>" ='".$s."' ".((($sn < count($output['level_education'])) ? ' OR ':'')) ,"filter"=> null );
                            $sn++;
                        }
                    }
                    $param[] = array("field"=>")","data"=>null,"filter"=>null);
                }
                $dateX='';
                if(!empty($output['attendance_start'])){
                    if(!empty($output['attendance_end'])){
                        $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>" between '".date("Y-m-d",strtotime($output['attendance_start']))."' and '".date("Y-m-d",strtotime($output['attendance_end']))."' ","filter"=>"AND",);
                        $dateX= " between '".date("Y-m-d",strtotime($output['attendance_start']))."' and '".date("Y-m-d",strtotime($output['attendance_end']))."'";
                    }else{
                        $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>"='".date("Y-m-d",strtotime($output['attendance_start']))."' ","filter"=>"AND",);
                        $dateX= "='".date("Y-m-d",strtotime($output['attendance_start']))."' ";
                    }
                }else{
                    $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>"='".date("Y-m-d")."' ","filter"=>"AND",);
                    $dateX= " = '".date("Y-m-d")."' ";
                }
                if(!empty($output['sorted'])){
                    $orderBy = $output['sorted'];
                }
            }

            $param[] = array("subquery"=>" NOT EXISTS( select NIP from db_employees.log_employees a where a.NIP = em.NIP and (DATE(a.AccessedOn) ".$dateX." )  limit 1 ) ","field"=>null,"data"=>null,"filter"=>null,);

            $totalData = $this->m_hr->fetchEmployee(true,$param)->row();
            $TotalData = (!empty($totalData) ? $totalData->Total : 0);
            if(!empty($reqdata['length'])){
                $result = $this->m_hr->fetchEmployee(false,$param,$reqdata['start'],$reqdata['length'])->result();
            }else{
                $result = $this->m_hr->fetchEmployee(false,$param)->result();
            }
            //var_dump($this->db->last_query());die();

            if(!empty($result)){
                $rs = array();
                $sort = array();
                $index=0;
                $maxTime = minMaxCalculate()['maxTime'];
                $maxCalTime = minMaxCalculate()['max'];
                $minCalTime = minMaxCalculate()['min'];

                foreach ($result as $r) {
                    $isLateCome = false; $isLateOut = false;
                    if(!empty($r->FirstLoginPortal)){
                        
                        $conditions = array("NIP"=>$r->NIP,"DATE(a.AccessedOn)"=>date("Y-m-d",strtotime($r->FirstLoginPortal)));
                        $r->LastLoginPortal = date("d-M-Y H:i:s",strtotime( lastLogin($conditions)->AccessedOn ));
                        /*$r->CalculateAttendanceTime = calculateAttendanceTime($r->FirstLoginPortal,$r->LastLoginPortal);
                        if($r->CalculateAttendanceTime < $minCalTime){
                            $isLate = true;
                        }else if($r->CalculateAttendanceTime > $maxCalTime){
                            $isLate = true;
                        }
                        */
                        //cek is late
                        //$plusMaxTime = strtotime(date('H:i',strtotime($r->FirstLoginPortal))) + 60*60*3; // time + 3 jam
                        $fTime = date('H:i',strtotime($r->FirstLoginPortal));
                        $plusTime = date('H:i', strtotime ("+3 hour", strtotime($r->FirstLoginPortal)));
                        if (date('H:i',strtotime($maxTime)) < $fTime) {
                            $isLateCome = true;
                        }
                        if (date('H:i', strtotime($r->LastLoginPortal)) < $plusTime )  {
                            $isLateOut = true;
                        }
                        if($r->FirstLoginPortalDayNum > 4){
                            $isLateCome = true;
                            $isLateOut = true;
                        }
                        
                    }else{$isLateCome = true;$isLateOut = true;}
                    $r->IsLateCome = $isLateCome;
                    $r->IsLateOut = $isLateOut;
                    $rs[] = $r;
                    $index++;
                }
                $result = $rs;
            }

            
            $json_data = array(
                "draw"            => intval( (!empty($reqdata['draw']) ? $reqdata['draw'] : null) ),
                "recordsTotal"    => intval($TotalData),
                "recordsFiltered" => intval($TotalData),
                "data"            => (!empty($result) ? $result : 0)
            );

        }else{$json_data=null;}
        $response = $json_data;
        echo json_encode($response);
    }


    /*public function detailAttdTempEmp(){
        $data = $this->input->post();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $param[] = array("field"=>"em.NIP","data"=>" = ".$data_arr['NIP'],"filter"=>"AND",);
            $isExist = $this->m_hr->fetchEmployee(false,$param)->row();
            if(!empty($isExist)){
                $data['attendance'] = $this->General_model->fetchData("db_employees.log_employees","NIP = ".$data_arr['NIP']." and DATE(AccessedOn) between DATE('".$data_arr['DATE']."') and DATE('".$data_arr['DATEEND']."')","AccessedOn","asc")->result();
                $data['employee'] = $isExist;
                $data['TotalActivity'] = $this->General_model->fetchData("db_employees.log_employees","NIP = ".$data_arr['NIP']." and DATE(AccessedOn) between DATE('".$data_arr['DATE']."') and DATE('".$data_arr['DATEEND']."')","AccessedOn","asc",null,"AccessedOn")->result();
                $department = parent::__getDepartement();
                $this->load->view('page/'.$department.'/attendance-temp/detail',$data);                
            }else{echo "<h1>Employee not founded</h1>";}
        }else{show_404();}
    }*/


    public function fetchMemberDepartment(){
        $json = array();       
        $data = $this->input->post();
        if($data){ 
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$data_arr['NIP']))->row();
            if(!empty($isExist)){
                $explodeMain = explode(".", $isExist->PositionMain);
                $explodeOth1 = explode(".", $isExist->PositionOther1);
                $explodeOth2 = explode(".", $isExist->PositionOther2);
                $explodeOth3 = explode(".", $isExist->PositionOther3);
                $param = "(em.StatusEmployeeID = 1 or em.StatusEmployeeID = 2) and ";
                $param .= "(em.PositionMain like '".$explodeMain[0].".%' or ";
                $param .= "em.PositionOther1 like '".$explodeOth1[0].".%' or ";
                $param .= "em.PositionOther2 like '".$explodeOth2[0].".%' or ";
                $param .= "em.PositionOther3 like '".$explodeOth3[0].".%' ) ";
                $json = $this->m_hr->fetchMemberOFDepartpent($param)->result(); 
                //var_dump($this->db->last_query());               
            }
        }
        echo json_encode($json);
    }


    public function fetcthUser(){
        header('Access-Control-Allow-Origin: *');
        $json = array();
        $sql = "select NIP as ID,Name as Name, 'Employee' as Status from db_employees.employees
                union 
                select NPM as ID,Name as Name, 'Student' as Status from db_academic.auth_students";
        $json = $this->General_model->callStoredProcedure($sql)->result();
        echo json_encode($json);
    }


    public function downloadAttendanceTemp(){
        set_time_limit(0);
        $reqdata = $this->input->post();
        if($reqdata){
            $this->load->helper("General_helper");
            $param = array();$orderBy=" em.ID DESC ";

            if(!empty($reqdata['division'])){
                if(!empty($reqdata['position'])){
                    $param[] = array("field"=>"(em.PositionMain","data"=>" = '".$reqdata['division'].".".$reqdata['position']."' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther1","data"=>" = '".$reqdata['division'].".".$reqdata['position']."' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther2","data"=>" = '".$reqdata['division'].".".$reqdata['position']."' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther3","data"=>" = '".$reqdata['division'].".".$reqdata['position']."' ) ","filter"=>"AND",);
                }else{
                    $param[] = array("field"=>"(em.PositionMain","data"=>" like '".$reqdata['division'].".%' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther1","data"=>" like '".$reqdata['division'].".%' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther2","data"=>" like '".$reqdata['division'].".%' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther3","data"=>" like '".$reqdata['division'].".%' ) ","filter"=>"AND",);
                }
            }else{
                $param[] = array("field"=>"em.PositionMain","data"=>" like '".$myDivisionID.".%' ","filter"=>"AND",);
            }

            if(!empty($reqdata['statusstd'])){
                $sn = 1;
                $dataArrStatus = array();
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                if(count($reqdata['statusstd']) == 1){
                    $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" ='".$reqdata['statusstd'][0]."' ","filter"=> "" );
                }else{
                    foreach ($reqdata['statusstd'] as $s) {
                        $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" ='".$s."' ".((($sn < count($reqdata['statusstd'])) ? ' OR ':'')) ,"filter"=> null );
                        $sn++;
                    }
                }
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }else{
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" = 1 or " ,"filter"=> null );    
                $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" = 2 or " ,"filter"=> null );    
                $param[] = array("field"=>"em.`StatusEmployeeID`","data"=>" = 7 " ,"filter"=> null );    
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }

            if(!empty($reqdata['staff'])){
                $param[] = array("field"=>"(em.NIP","data"=>" like '%".$reqdata['staff']."%' ","filter"=>"AND",);
                $param[] = array("field"=>"em.Name","data"=>" like '%".$reqdata['staff']."%' )","filter"=>"OR",);
            }
            if(!empty($reqdata['religion'])){
                $sn = 1;
                $dataArrStatus = array();
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                if(count($reqdata['religion']) == 1){
                    $param[] = array("field"=>"em.ReligionID","data"=>" ='".$reqdata['religion'][0]."' ","filter"=> "" );
                }else{
                    foreach ($reqdata['religion'] as $s) {
                        $param[] = array("field"=>"em.ReligionID","data"=>" ='".$s."' ".((($sn < count($reqdata['religion'])) ? ' OR ':'')) ,"filter"=> null );
                        $sn++;
                    }
                }
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }
            if(!empty($reqdata['gender'])){
                $sn = 1;
                $dataArrStatus = array();
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                if(count($reqdata['gender']) == 1){
                    $param[] = array("field"=>"em.Gender","data"=>" ='".$reqdata['gender'][0]."' ","filter"=> "" );
                }else{
                    foreach ($reqdata['gender'] as $s) {
                        $param[] = array("field"=>"em.Gender","data"=>" ='".$s."' ".((($sn < count($reqdata['gender'])) ? ' OR ':'')) ,"filter"=> null );
                        $sn++;
                    }
                }
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }
            if(!empty($reqdata['level_education'])){
                $sn = 1;
                $dataArrStatus = array();
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                if(count($reqdata['level_education']) == 1){
                    $param[] = array("field"=>"em.LevelEducationID","data"=>" ='".$reqdata['level_education'][0]."' ","filter"=> "" );
                }else{
                    foreach ($reqdata['level_education'] as $s) {
                        $param[] = array("field"=>"em.LevelEducationID","data"=>" ='".$s."' ".((($sn < count($reqdata['level_education'])) ? ' OR ':'')) ,"filter"=> null );
                        $sn++;
                    }
                }
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }

            $dateX='';
            if(!empty($reqdata['attendance_start'])){
                if(!empty($reqdata['attendance_end'])){
                    $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>" between '".date("Y-m-d",strtotime($reqdata['attendance_start']))."' and '".date("Y-m-d",strtotime($reqdata['attendance_end']))."' ","filter"=>"AND",);
                    $dateX= " between '".date("Y-m-d",strtotime($reqdata['attendance_start']))."' and '".date("Y-m-d",strtotime($reqdata['attendance_end']))."'";
                }else{
                    $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>"='".date("Y-m-d",strtotime($reqdata['attendance_start']))."' ","filter"=>"AND",);
                    $dateX= "='".date("Y-m-d",strtotime($reqdata['attendance_start']))."' ";
                }
            }else{
                $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>"='".date("Y-m-d")."' ","filter"=>"AND",);
                $dateX= " = '".date("Y-m-d")."' ";
            }

            $param[] = array("subquery"=>" NOT EXISTS( select NIP from db_employees.log_employees a where a.NIP = em.NIP and (DATE(a.AccessedOn) ".$dateX." )  limit 1 ) ","field"=>null,"data"=>null,"filter"=>null,);


            $totalData = $this->m_hr->fetchEmployee(true,$param)->row();
            $TotalData = (!empty($totalData) ? $totalData->Total : 0);
            $result = $this->m_hr->fetchEmployee(false,$param)->result();

            if(!empty($result)){
                $rs = array();
                $maxTime = minMaxCalculate()['maxTime'];
                $maxCalTime = minMaxCalculate()['max'];
                $minCalTime = minMaxCalculate()['min'];
                foreach ($result as $r) {
                    $isLateCome = false; $isLateOut = false;
                    if(!empty($r->FirstLoginPortal)){
                        $conditions = array("NIP"=>$r->NIP,"DATE(a.AccessedOn)"=>date("Y-m-d",strtotime($r->FirstLoginPortal)));
                        $r->LastLoginPortal = date("d-M-Y H:i:s",strtotime( lastLogin($conditions)->AccessedOn ));
                        $r->CalculateAttendanceTime = calculateAttendanceTime($r->FirstLoginPortal,$r->LastLoginPortal);
    
                        //cek is late
                        $fTime = date('H:i',strtotime($r->FirstLoginPortal));
                        $plusTime = date('H:i', strtotime ("+3 hour", strtotime($r->FirstLoginPortal)));
                        if (date('H:i',strtotime($maxTime)) < $fTime) {
                            $isLateCome = true;
                        }
                        if (date('H:i', strtotime($r->LastLoginPortal)) < $plusTime )  {
                            $isLateOut = true;
                        }
                        if($r->FirstLoginPortalDayNum > 4){
                            $isLateCome = true;
                            $isLateOut = true;
                        }
                        
                    }else{$isLateCome = true;$isLateOut = true;}

                    $r->IsLateCome = $isLateCome;
                    $r->IsLateOut = $isLateOut;
                    $rs[] = $r;
                }
                $result = $rs;
            }
            /*
            echo "<pre>";
            var_dump($result);die();
            */
            include APPPATH.'third_party/PHPExcel/PHPExcel.php';
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 600); //600 seconds = 10 minutes


            // Panggil class PHPExcel nya
            $excel = new PHPExcel();

            $pr = strtoupper('Attendance Temporary - By Portal');

            // Settingan awal fil excel
            $excel->getProperties()->setCreator('IT PU')
                ->setLastModifiedBy('IT PU')
                ->setTitle($pr)
                ->setSubject($pr)
                ->setDescription($pr)
                ->setKeywords($pr);

            $excel->setActiveSheetIndex(0)->setCellValue('A1', $pr);
            $excel->getActiveSheet()->mergeCells('A1:F1');
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
            $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
            
            $header= [array("cell"=>"A","val"=>"No"),
                    array("cell"=>"B","val"=>"NIP"),
                    array("cell"=>"C","val"=>"Employee"),
                    array("cell"=>"D","val"=>"Division/Position"),
                    array("cell"=>"E","val"=>"Day"),
                    array("cell"=>"F","val"=>"First Login"),
                    array("cell"=>"G","val"=>"Last Login")
            ];

            $styleColHeader = array(
                'font' => array('bold' => true), // Set font nya jadi bold
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                ),
                'borders' => array(
                    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                )
            );
            $numRow = 3;
            foreach ($header as $k=>$v) {
                $excel->setActiveSheetIndex(0)->setCellValue($v['cell'].$numRow, $v['val']);
                $excel->getActiveSheet()->getStyle($v['cell'].$numRow)->applyFromArray($styleColHeader);
            }

            $numRow = $numRow+1;
            
            if(!empty($result)){
                $styleCell = array(
                    'borders' => array(
                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                    )
                );
                $no=1;
                foreach ($result as $v) {
                    $excel->setActiveSheetIndex(0)->setCellValue('A'.$numRow, $no);
                    $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('A'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('B'.$numRow, $v->NIP);
                    $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('B'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('C'.$numRow, $v->Name);
                    $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('C'.$numRow)->applyFromArray($styleCell);
                    
                    $otherPosition = '';
                    if(!empty($v->PositionOther1Name)){
                        $otherPosition .= '<br>'.$v->PositionOther1Name;
                    }else if(!empty($v->PositionOther2Name)){
                        $otherPosition .= '<br>'.$v->PositionOther2Name;
                    }else if(!empty($v->PositionOther3Name)){
                        $otherPosition .= '<br>'.$v->PositionOther3Name;
                    }

                    $excel->setActiveSheetIndex(0)->setCellValue('D'.$numRow, $v->DivisionMain_.'/'.$v->PositionMain_.(!empty($otherPosition) ? $otherPosition : ''));
                    $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('D'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('E'.$numRow, ( !empty($v->FirstLoginPortalDay) ? $v->FirstLoginPortalDay : ''));
                    $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('E'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('F'.$numRow, ( !empty($v->FirstLoginPortal) ? (date('d-F-Y H:i:s',strtotime($v->FirstLoginPortal))) : ''));
                    $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('F'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('G'.$numRow, (!empty($v->LastLoginPortal) ? (date('d-F-Y H:i:s',strtotime($v->LastLoginPortal))) : ''));
                    $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('G'.$numRow)->applyFromArray($styleCell);
                    
                    if($v->IsLateCome || $v->IsLateOut){
                        $excel->getActiveSheet()
                        ->getStyle('A'.$numRow.':G'.$numRow)
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF0000');
                    }
                    

                    $no++;
                    $numRow++;
                }
            }

            // Proses file excel
            $filename = "Attendance-Temp-Download.xlsx";
            //$FILEpath = "./dokument/".$filename;
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$filename); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $write->save('php://output');
    
        }else{
            show_404();
        }
    
    }

    /*END ATTENDANCE EMPLOYEE*/


    /*ATTENDANCE LECTURER*/
    public function attendanceTempLect(){
        $data['statusstd'] = $this->General_model->fetchData("db_employees.employees_status","Type = 'lec'","IDStatus","asc")->result();
        $data['prody'] = $this->General_model->fetchData("db_academic.program_study",array(),'NameEng','ASC')->result();
        $data['religion'] = $this->General_model->fetchData("db_employees.religion",array())->result();
        $data['level_education'] = $this->General_model->fetchData("db_employees.level_education",array())->result();

        $department = parent::__getDepartement();
        $page = $this->load->view('page/'.$department.'/attendance-temp/lecturer',$data,true);
        $this->temp($page);
    }


    public function fetchAttdTempLect(){
        set_time_limit(0);
        $this->load->helper("General_helper");
        $reqdata = $this->input->post(); 
        $myDivisionID = $this->session->userdata('PositionMain')['IDDivision'];

        if($reqdata){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);
            $param = array();$orderBy=" lem.ID DESC ";

            if(!empty($reqdata['search']['value']) ) {
                $search = $reqdata['search']['value'];

                $param[] = array("field"=>"(em.NIP","data"=>" like '%".$search."%' ","filter"=>"AND",);
                $param[] = array("field"=>"em.Name","data"=>" like '%".$search."%' )","filter"=>"OR",);
            }
            if(!empty($data_arr['Filter'])){
                $parse = parse_str($data_arr['Filter'],$output);

                //check data emp if lecturers
                if(!empty($output['isLecturer'])){
                    $divLect = '14';
                    $param[] = array("field"=>"(em.PositionMain","data"=>" like'".$divLect.".%' ","filter"=>"AND",);
                    $param[] = array("field"=>"em.PositionOther1","data"=>" like'".$divLect.".%' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther2","data"=>" like'".$divLect.".%' ","filter"=>"OR",);
                    $param[] = array("field"=>"em.PositionOther3","data"=>" like'".$divLect.".%' )","filter"=>"OR",);
                    if( !empty($output['position'])){
                        $param[] = array("field"=>"em.PositionMain","data"=>" = '".$divLect.".".$output['position']."' ","filter"=>"AND",);
                    }
                    if(!empty($output['status'])){
                        $sn = 1;
                        $dataArrStatus = array();
                        $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                        if(count($output['status']) == 1){
                            $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" ='".$output['status'][0]."' ","filter"=> "" );
                        }else{
                            foreach ($output['status'] as $s) {
                                $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" ='".$s."' ".((($sn < count($output['status'])) ? ' OR ':'')) ,"filter"=> null );
                                $sn++;
                            }
                        }
                        $param[] = array("field"=>")","data"=>null,"filter"=>null);
                    }
                    if(!empty($output['study_program'])){
                        $sn = 1;
                        $dataArrStatus = array();
                        $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                        if(count($output['study_program']) == 1){
                            $param[] = array("field"=>"em.ProdiID","data"=>" ='".$output['study_program'][0]."' ","filter"=> "" );
                        }else{
                            foreach ($output['study_program'] as $s) {
                                $param[] = array("field"=>"em.ProdiID","data"=>" ='".$s."' ".((($sn < count($output['study_program'])) ? ' OR ':'')) ,"filter"=> null );
                                $sn++;
                            }
                        }
                        $param[] = array("field"=>")","data"=>null,"filter"=>null);
                    }
                }
                //check data for employee
                else{
                    if(!empty($output['statusstd'])){
                        $sn = 1;
                        $dataArrStatus = array();
                        $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                        if(count($output['statusstd']) == 1){
                            $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" ='".$output['statusstd'][0]."' ","filter"=> "" );
                        }else{
                            foreach ($output['statusstd'] as $s) {
                                $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" ='".$s."' ".((($sn < count($output['statusstd'])) ? ' OR ':'')) ,"filter"=> null );
                                $sn++;
                            }
                        }
                        $param[] = array("field"=>")","data"=>null,"filter"=>null);
                    }else{
                        $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                        $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" = 3 or " ,"filter"=> null );    
                        $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" = 4 or " ,"filter"=> null );    
                        $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" = 5 or " ,"filter"=> null );    
                        $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" = 6 " ,"filter"=> null );    
                        $param[] = array("field"=>")","data"=>null,"filter"=>null);
                    }
                }

                if(!empty($output['ProdiID'])){
                    if($output['ProdiID'] == 7){$output['ProdiID'] = 0;}
                    $param[] = array("field"=>"em.ProdiID","data"=>" = ".$output['ProdiID'].' ',"filter"=>"AND",);
                }else{
                    $param[] = array("field"=>"em.ProdiID","data"=>" = 4 ","filter"=>"AND",);
                }
                if(!empty($output['staff'])){
                    $param[] = array("field"=>"(em.NIP","data"=>" like '%".$output['staff']."%' ","filter"=>"AND",);
                    $param[] = array("field"=>"em.Name","data"=>" like '%".$output['staff']."%' )","filter"=>"OR",);
                }
                if(!empty($output['religion'])){
                    $sn = 1;
                    $dataArrStatus = array();
                    $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                    if(count($output['religion']) == 1){
                        $param[] = array("field"=>"em.ReligionID","data"=>" ='".$output['religion'][0]."' ","filter"=> "" );
                    }else{
                        foreach ($output['religion'] as $s) {
                            $param[] = array("field"=>"em.ReligionID","data"=>" ='".$s."' ".((($sn < count($output['religion'])) ? ' OR ':'')) ,"filter"=> null );
                            $sn++;
                        }
                    }
                    $param[] = array("field"=>")","data"=>null,"filter"=>null);
                }
                if(!empty($output['gender'])){
                    $sn = 1;
                    $dataArrStatus = array();
                    $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                    if(count($output['gender']) == 1){
                        $param[] = array("field"=>"em.Gender","data"=>" ='".$output['gender'][0]."' ","filter"=> "" );
                    }else{
                        foreach ($output['gender'] as $s) {
                            $param[] = array("field"=>"em.Gender","data"=>" ='".$s."' ".((($sn < count($output['gender'])) ? ' OR ':'')) ,"filter"=> null );
                            $sn++;
                        }
                    }
                    $param[] = array("field"=>")","data"=>null,"filter"=>null);
                }
                if(!empty($output['level_education'])){
                    $sn = 1;
                    $dataArrStatus = array();
                    $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                    if(count($output['level_education']) == 1){
                        $param[] = array("field"=>"em.LevelEducationID","data"=>" ='".$output['level_education'][0]."' ","filter"=> "" );
                    }else{
                        foreach ($output['level_education'] as $s) {
                            $param[] = array("field"=>"em.LevelEducationID","data"=>" ='".$s."' ".((($sn < count($output['level_education'])) ? ' OR ':'')) ,"filter"=> null );
                            $sn++;
                        }
                    }
                    $param[] = array("field"=>")","data"=>null,"filter"=>null);
                }
                $dateX='';
                if(!empty($output['attendance_start'])){
                    if(!empty($output['attendance_end'])){
                        $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>" between '".date("Y-m-d",strtotime($output['attendance_start']))."' and '".date("Y-m-d",strtotime($output['attendance_end']))."' ","filter"=>"AND",);
                        $dateX= " between '".date("Y-m-d",strtotime($output['attendance_start']))."' and '".date("Y-m-d",strtotime($output['attendance_end']))."'";
                    }else{
                        $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>"='".date("Y-m-d",strtotime($output['attendance_start']))."' ","filter"=>"AND",);
                        $dateX= "='".date("Y-m-d",strtotime($output['attendance_start']))."' ";
                    }
                }else{
                    $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>"='".date("Y-m-d")."' ","filter"=>"AND",);
                    $dateX= " = '".date("Y-m-d")."' ";
                }
                if(!empty($output['sorted'])){
                    $orderBy = $output['sorted'];
                }
            }

            $param[] = array("subquery"=>" NOT EXISTS( select NIP from db_employees.log_lecturers a where a.NIP = em.NIP and (DATE(a.AccessedOn) ".$dateX." )  limit 1 ) ","field"=>null,"data"=>null,"filter"=>null,);

            $totalData = $this->m_hr->fetchLecturer(true,$param)->row();
            $TotalData = (!empty($totalData) ? $totalData->Total : 0);
            if(!empty($reqdata['length'])){
                $result = $this->m_hr->fetchLecturer(false,$param,$reqdata['start'],$reqdata['length'])->result();
            }else{
                $result = $this->m_hr->fetchLecturer(false,$param)->result();
            }

            if(!empty($result)){
                $rs = array();
                $sort = array();
                $index=0;
                $maxTime = minMaxCalculate()['maxTime'];
                $maxCalTime = minMaxCalculate()['max'];
                $minCalTime = minMaxCalculate()['min'];

                foreach ($result as $r) {
                    $isLateCome = false; $isLateOut = false;
                    if(!empty($r->FirstLoginPortal)){
                        
                        $conditions = array("NIP"=>$r->NIP,"DATE(a.AccessedOn)"=>date("Y-m-d",strtotime($r->FirstLoginPortal)));
                        $r->LastLoginPortal = date("d-M-Y H:i:s",strtotime( lastLoginLect($conditions)->AccessedOn ));
                        $fTime = date('H:i',strtotime($r->FirstLoginPortal));
                        $plusTime = date('H:i', strtotime ("+3 hour", strtotime($r->FirstLoginPortal)));
                        if (date('H:i',strtotime($maxTime)) < $fTime) {
                            $isLateCome = true;
                        }
                        if (date('H:i', strtotime($r->LastLoginPortal)) < $plusTime )  {
                            $isLateOut = true;
                        }

                        if($r->FirstLoginPortalDayNum > 4){
                            $isLateCome = true;
                            $isLateOut = true;
                        }
                        
                    }else{$isLateCome = true;$isLateOut = true;}
                    $r->IsLateCome = $isLateCome;
                    $r->IsLateOut = $isLateOut;
                    $rs[] = $r;
                    $index++;
                }
                $result = $rs;
            }

            
            $json_data = array(
                "draw"            => intval( (!empty($reqdata['draw']) ? $reqdata['draw'] : null) ),
                "recordsTotal"    => intval($TotalData),
                "recordsFiltered" => intval($TotalData),
                "data"            => (!empty($result) ? $result : 0)
            );

        }else{$json_data=null;}
        $response = $json_data;
        echo json_encode($response);
    }


    public function downloadAttendanceTempLect(){
        set_time_limit(0);
        $reqdata = $this->input->post();
        if($reqdata){
            $this->load->helper("General_helper");
            $param = array();$orderBy=" em.ID DESC ";

            if(!empty($reqdata['ProdiID'])){
                $param[] = array("field"=>"em.ProdiID","data"=>" = ".$reqdata['ProdiID'].' ',"filter"=>"AND",);
            }else{
                $param[] = array("field"=>"em.ProdiID","data"=>" = 4 ","filter"=>"AND",);
            }

            if(!empty($reqdata['statusstd'])){
                $sn = 1;
                $dataArrStatus = array();
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                if(count($reqdata['statusstd']) == 1){
                    $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" ='".$reqdata['statusstd'][0]."' ","filter"=> "" );
                }else{
                    foreach ($reqdata['statusstd'] as $s) {
                        $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" ='".$s."' ".((($sn < count($reqdata['statusstd'])) ? ' OR ':'')) ,"filter"=> null );
                        $sn++;
                    }
                }
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }else{
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" = 3 or " ,"filter"=> null );    
                $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" = 4 or " ,"filter"=> null );    
                $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" = 5 or " ,"filter"=> null );    
                $param[] = array("field"=>"em.`StatusLecturerID`","data"=>" = 6 " ,"filter"=> null );    
                $param[] = array("field"=>")","data"=>null,"filter"=>null);    
            }

            if(!empty($reqdata['staff'])){
                $param[] = array("field"=>"(em.NIP","data"=>" like '%".$reqdata['staff']."%' ","filter"=>"AND",);
                $param[] = array("field"=>"em.Name","data"=>" like '%".$reqdata['staff']."%' )","filter"=>"OR",);
            }
            if(!empty($reqdata['religion'])){
                $sn = 1;
                $dataArrStatus = array();
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                if(count($reqdata['religion']) == 1){
                    $param[] = array("field"=>"em.ReligionID","data"=>" ='".$reqdata['religion'][0]."' ","filter"=> "" );
                }else{
                    foreach ($reqdata['religion'] as $s) {
                        $param[] = array("field"=>"em.ReligionID","data"=>" ='".$s."' ".((($sn < count($reqdata['religion'])) ? ' OR ':'')) ,"filter"=> null );
                        $sn++;
                    }
                }
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }
            if(!empty($reqdata['gender'])){
                $sn = 1;
                $dataArrStatus = array();
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                if(count($reqdata['gender']) == 1){
                    $param[] = array("field"=>"em.Gender","data"=>" ='".$reqdata['gender'][0]."' ","filter"=> "" );
                }else{
                    foreach ($reqdata['gender'] as $s) {
                        $param[] = array("field"=>"em.Gender","data"=>" ='".$s."' ".((($sn < count($reqdata['gender'])) ? ' OR ':'')) ,"filter"=> null );
                        $sn++;
                    }
                }
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }
            if(!empty($reqdata['level_education'])){
                $sn = 1;
                $dataArrStatus = array();
                $param[] = array("field"=>"(","data"=>null,"filter"=>"AND");
                if(count($reqdata['level_education']) == 1){
                    $param[] = array("field"=>"em.LevelEducationID","data"=>" ='".$reqdata['level_education'][0]."' ","filter"=> "" );
                }else{
                    foreach ($reqdata['level_education'] as $s) {
                        $param[] = array("field"=>"em.LevelEducationID","data"=>" ='".$s."' ".((($sn < count($reqdata['level_education'])) ? ' OR ':'')) ,"filter"=> null );
                        $sn++;
                    }
                }
                $param[] = array("field"=>")","data"=>null,"filter"=>null);
            }

            $dateX='';
            if(!empty($reqdata['attendance_start'])){
                if(!empty($reqdata['attendance_end'])){
                    $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>" between '".date("Y-m-d",strtotime($reqdata['attendance_start']))."' and '".date("Y-m-d",strtotime($reqdata['attendance_end']))."' ","filter"=>"AND",);
                    $dateX= " between '".date("Y-m-d",strtotime($reqdata['attendance_start']))."' and '".date("Y-m-d",strtotime($reqdata['attendance_end']))."'";
                }else{
                    $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>"='".date("Y-m-d",strtotime($reqdata['attendance_start']))."' ","filter"=>"AND",);
                    $dateX= "='".date("Y-m-d",strtotime($reqdata['attendance_start']))."' ";
                }
            }else{
                $param[] = array("multiple"=>"date","field"=>"lem.AccessedOn","data"=>"='".date("Y-m-d")."' ","filter"=>"AND",);
                $dateX= " = '".date("Y-m-d")."' ";
            }

            $param[] = array("subquery"=>" NOT EXISTS( select NIP from db_employees.log_lecturers a where a.NIP = em.NIP and (DATE(a.AccessedOn) ".$dateX." )  limit 1 ) ","field"=>null,"data"=>null,"filter"=>null,);


            $totalData = $this->m_hr->fetchLecturer(true,$param)->row();
            $TotalData = (!empty($totalData) ? $totalData->Total : 0);
            $result = $this->m_hr->fetchLecturer(false,$param)->result();

            if(!empty($result)){
                $rs = array();
                $maxTime = minMaxCalculate()['maxTime'];
                $maxCalTime = minMaxCalculate()['max'];
                $minCalTime = minMaxCalculate()['min'];
                foreach ($result as $r) {
                    $isLateCome = false; $isLateOut = false;
                    if(!empty($r->FirstLoginPortal)){
                        $conditions = array("NIP"=>$r->NIP,"DATE(a.AccessedOn)"=>date("Y-m-d",strtotime($r->FirstLoginPortal)));
                        $r->LastLoginPortal = date("d-M-Y H:i:s",strtotime( lastLoginLect($conditions)->AccessedOn ));
                        $r->CalculateAttendanceTime = calculateAttendanceTime($r->FirstLoginPortal,$r->LastLoginPortal);
    
                        //cek is late
                        $fTime = date('H:i',strtotime($r->FirstLoginPortal));
                        $plusTime = date('H:i', strtotime ("+3 hour", strtotime($r->FirstLoginPortal)));
                        if (date('H:i',strtotime($maxTime)) < $fTime) {
                            $isLateCome = true;
                        }
                        if (date('H:i', strtotime($r->LastLoginPortal)) < $plusTime )  {
                            $isLateOut = true;
                        }

                        if($r->FirstLoginPortalDayNum > 4){
                            $isLateCome = true;
                            $isLateOut = true;
                        }
                        
                    }else{$isLateCome = true;$isLateOut = true;}

                    $r->IsLateCome = $isLateCome;
                    $r->IsLateOut = $isLateOut;
                    $rs[] = $r;
                }
                $result = $rs;
            }

            include APPPATH.'third_party/PHPExcel/PHPExcel.php';
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 600); //600 seconds = 10 minutes


            // Panggil class PHPExcel nya
            $excel = new PHPExcel();

            $pr = strtoupper('Attendance Lecturer Temporary - By Portal');

            // Settingan awal fil excel
            $excel->getProperties()->setCreator('IT PU')
                ->setLastModifiedBy('IT PU')
                ->setTitle($pr)
                ->setSubject($pr)
                ->setDescription($pr)
                ->setKeywords($pr);

            $excel->setActiveSheetIndex(0)->setCellValue('A1', $pr);
            $excel->getActiveSheet()->mergeCells('A1:F1');
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
            $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
            
            $header= [array("cell"=>"A","val"=>"No"),
                    array("cell"=>"B","val"=>"NIP"),
                    array("cell"=>"C","val"=>"Lecturer"),
                    array("cell"=>"D","val"=>"Program Study"),
                    array("cell"=>"E","val"=>"Day"),
                    array("cell"=>"F","val"=>"First Login"),
                    array("cell"=>"G","val"=>"Last Login")
            ];

            $styleColHeader = array(
                'font' => array('bold' => true), // Set font nya jadi bold
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                ),
                'borders' => array(
                    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                )
            );
            $numRow = 3;
            foreach ($header as $k=>$v) {
                $excel->setActiveSheetIndex(0)->setCellValue($v['cell'].$numRow, $v['val']);
                $excel->getActiveSheet()->getStyle($v['cell'].$numRow)->applyFromArray($styleColHeader);
            }

            $numRow = $numRow+1;
            
            if(!empty($result)){
                $styleCell = array(
                    'borders' => array(
                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                    )
                );
                $no=1;
                foreach ($result as $v) {
                    $excel->setActiveSheetIndex(0)->setCellValue('A'.$numRow, $no);
                    $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('A'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('B'.$numRow, $v->NIP);
                    $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('B'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('C'.$numRow, $v->Name);
                    $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('C'.$numRow)->applyFromArray($styleCell);
                   
                    $excel->setActiveSheetIndex(0)->setCellValue('D'.$numRow, $v->ProdiName);
                    $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('D'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('E'.$numRow, ( !empty($v->FirstLoginPortal) ? $v->FirstLoginPortalDay : ''));
                    $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('E'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('F'.$numRow, ( !empty($v->FirstLoginPortal) ? (date('d-F-Y H:i:s',strtotime($v->FirstLoginPortal))) : ''));
                    $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('F'.$numRow)->applyFromArray($styleCell);
                    
                    $excel->setActiveSheetIndex(0)->setCellValue('G'.$numRow, (!empty($v->LastLoginPortal) ? (date('d-F-Y H:i:s',strtotime($v->LastLoginPortal))) : ''));
                    $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                    $excel->getActiveSheet()->getStyle('G'.$numRow)->applyFromArray($styleCell);
                    
                    if($v->IsLateCome || $v->IsLateOut){
                        $excel->getActiveSheet()
                        ->getStyle('A'.$numRow.':G'.$numRow)
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF0000');
                    }
                    

                    $no++;
                    $numRow++;
                }
            }

            // Proses file excel
            $filename = "Attendance-Lecturer-Temp-Download.xlsx";
            //$FILEpath = "./dokument/".$filename;
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$filename); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $write->save('php://output');
    
        }else{
            show_404();
        }
    }
    /*END ATTENDANCE LECTURER*/
    
    /*END ADDED BY FEBI @ FEB 2020*/

}
