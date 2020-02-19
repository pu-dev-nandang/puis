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
        $data['division'] = $this->General_model->fetchData("db_employees.division",array("StatusDiv"=>1))->result();
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



    /*ADDED BY FEBRI @ NOV 2019*/
    public function empRequest(){
        $data = $this->input->post();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $conditions = array("NIP"=>$data_arr['NIP']);
            $isExist = $this->General_model->fetchData("db_employees.employees",$conditions)->row();
            if(!empty($isExist)){
                $data['NIP'] = $data_arr['NIP'];
                $data['detail_ori'] = $isExist;
                $data['religion_ori'] = $this->General_model->fetchData("db_employees.religion",array("IDReligion"=>$isExist->ReligionID))->row();
                $data['province_ori'] = $this->General_model->fetchData("db_employees.data_province",array("IDProvince"=>$isExist->ProvinceID))->row();
                $data['city_ori'] = $this->General_model->fetchData("db_employees.data_city",array("IDCity"=>$isExist->CityID))->row();
                $conditions['isApproval'] = 1;
                $data['detail_req'] = $this->General_model->fetchData("db_employees.tmp_employees",$conditions)->row();
                $data['religion_req'] = $this->General_model->fetchData("db_employees.religion",array("IDReligion"=>$data['detail_req']->ReligionID))->row();
                $data['province_req'] = $this->General_model->fetchData("db_employees.data_province",array("IDProvince"=>$data['detail_req']->ProvinceID))->row();
                $data['city_req'] = $this->General_model->fetchData("db_employees.data_city",array("IDCity"=>$data['detail_req']->CityID))->row();
            }
        }
        $this->load->view('page/human-resources/employees/requestMerging',$data);
    }

    public function empRequestAppv(){
        $data = $this->input->post();
        $myName = $this->session->userdata('Name');
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $conditions = array("NIP"=>$data_arr['NIP']);
            $message = ""; $isfinish = false;
            $isExist = $this->General_model->fetchData("db_employees.employees",$conditions)->row();
            if(!empty($isExist)){
                if($data_arr['ACT'] == 1){
                    $getTempEmpyReq = $this->General_model->fetchData("db_employees.tmp_employees",$conditions)->row();
                    $dataAppv = array();
                    if(empty($getTempEmpyReq->Photo)){
                        unset($getTempEmpyReq->Photo);
                    }else{
                        $imgReq = $getTempEmpyReq->pathPhoto."uploads/profile/".$getTempEmpyReq->Photo;
                        $ch = curl_init($imgReq);
                        $fp = fopen('./uploads/employees/'.$getTempEmpyReq->Photo, 'wb');
                        curl_setopt($ch, CURLOPT_FILE, $fp);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_exec($ch);
                        curl_close($ch);
                        fclose($fp);

                        //remove picture
                        $tmp_pic = $_SERVER['DOCUMENT_ROOT'].'/lecturer/uploads/profile/'.$getTempEmpyReq->Photo;
                        unlink($tmp_pic);

                        $dataAppv["Photo"] = null;
                    }
                    unset($getTempEmpyReq->ID);
                    unset($getTempEmpyReq->NIP);
                    unset($getTempEmpyReq->isApproval);
                    unset($getTempEmpyReq->note);
                    unset($getTempEmpyReq->created);
                    unset($getTempEmpyReq->createdby);
                    unset($getTempEmpyReq->edited);
                    unset($getTempEmpyReq->editedby);
                    unset($getTempEmpyReq->pathPhoto);

                    $updateTA = $this->General_model->updateData("db_employees.employees",$getTempEmpyReq,$conditions);
                    if($updateTA){
                        //check if has a different birthdate between old and new
                        if($isExist->DateOfBirth != $getTempEmpyReq->DateOfBirth){
                            //update birthdate-pass on auth student
                            $updateOldPass = $this->General_model->updateData("db_employees.employees",array("Password_old"=>date("dmy",strtotime($getTempEmpyReq->DateOfBirth))),$conditions);
                        }

                        //update status table temp_student
                        $dataAppv['isApproval'] = 2;
                        $dataAppv['note'] = (!empty($data_arr['NOTE']) ? $data_arr['NOTE'] : null);
                        $dataAppv['editedby'] = $myName;
                        $updateTempStd = $this->General_model->updateData("db_employees.tmp_employees",$dataAppv,$conditions);
                        $message = (($updateTempStd) ? "Successfully":"Failed")." saved.";
                        $isfinish = $updateTempStd;
                    }else{
                        $message = "Failed saved data. Try again.";
                    }

                }else{
                    //update status Rejected table temp_student
                    $updateTempStd = $this->General_model->updateData("db_employees.tmp_employees",array("isApproval"=>$data_arr['ACT'],"note"=>(!empty($data_arr['NOTE']) ? $data_arr['NOTE'] : null),"editedby"=>$myName),$conditions);
                    $message = (($updateTempStd) ? "Successfully":"Failed")." saved." ;
                    $isfinish = $updateTempStd;
                }
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
            $bankID = $data['bankID'];
            $bankName = $data['bankName'];
            $bankAccName = $data['bankAccName'];
            $bankAccNum = $data['bankAccNum'];
            unset($data['bankID']);
            unset($data['bankName']);
            unset($data['bankAccName']);
            unset($data['bankAccNum']);

            $conditions = array("NIP"=>$data['NIP']);
            $update = $this->General_model->updateData("db_employees.employees",$data,$conditions);
            if($update){
                if(!empty($bankName)){
                    for ($i=0; $i < count($bankName); $i++) { 
                        if(!empty($bankID[$i])){
                            $updateBank = $this->General_model->updateData("db_employees.employees_bank_account",array("NIP"=>$data['NIP'],"bank"=>$bankName[$i],"accountName"=>$bankAccName[$i],"accountNumber"=>$bankAccNum[$i]), array("ID"=>$bankID[$i]));
                        }else{
                            $inserBank = $this->General_model->insertData("db_employees.employees_bank_account",array("NIP"=>$data['NIP'],"bank"=>$bankName[$i],"accountName"=>$bankAccName[$i],"accountNumber"=>$bankAccNum[$i]));                        
                        }
                    }
                }

                $message = "Successfully saved.";
            }else{$message = "Failed saved.";}

            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/additional-info/'.$data['NIP']));

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
                    $dataPost = array("NIP"=>$data['NIP'],"name"=>$data['name'][$i],"relationID"=>$data['relation'][$i], "gender"=>$data['gender'][$i], "placeBirth"=>$data["placeBirth"][$i], "birthdate"=>$data["birthdate"][$i], "lastEduID"=>$data['lastEdu'][$i] );
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
            $data['division'] = $this->General_model->fetchData("db_employees.sto_temp",array( "typeNode"=>1,"isActive"=>1))->result();
            $data['employees_status'] = $this->General_model->fetchData("db_employees.employees_status","Type != 'lec'")->result();
            $data['detail'] = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$NIP))->row();
            $data['currComp'] = $this->General_model->fetchData("db_employees.master_company",array("ID"=>1))->row();
            $page = $this->load->view('page/'.$department.'/employees/career-level',$data,true);
            $this->tab_menu_new_emp($page,$NIP);
        }else{show_404();}
    }


    public function careerSave(){
        $data = $this->input->post();
        if($data){
            $message = "";
            if(!empty($data['JoinDate']) && !empty($data['StatusEmployeeID'])){
                $updateDetail = $this->General_model->updateData("db_employees.employees",array("JoinDate"=>$data['JoinDate'],"ResignDate"=>(!empty($data['ResignDate']) ? $data['ResignDate'] : null),"StatusEmployeeID"=>$data['StatusEmployeeID']),array("NIP"=>$data['NIP']));
            }

            if(!empty($data['startJoin'])){
                for ($i=0; $i < count($data['startJoin']) ; $i++) {
                    $dataPost = array("NIP"=>$data['NIP'],"StartJoin"=>$data['startJoin'][$i], "EndJoin"=>$data['endJoin'][$i], "LevelID"=>$data['statusLevelID'][$i],"DepartmentID"=>$data['division'][$i],"PositionID"=>$data['position'][$i],"JobTitle"=>$data['jobTitle'][$i],"Superior"=>$data['superior'][$i],"StatusID"=>$data['statusID'][$i],"Remarks"=>$data['remarks'][$i]);
                    $dataSTOUser = array("NIP"=>$data['NIP'],"STOID"=>$data['position'][$i],"IsActive"=>1,"StatusID"=>$data['statusID'][$i],"JobTitle"=>$data['jobTitle'][$i]);
                    if(!empty($data['careerID'][$i])){
                        $isExist = $this->General_model->fetchData("db_employees.employees_career",array("ID"=>$data['careerID'][$i]))->row();
                        if(!empty($isExist)){
                            $update = $this->General_model->updateData("db_employees.employees_career",$dataPost,array("ID"=>$data['careerID'][$i]));
                            if($update){
                                $isExistSTO = $this->General_model->fetchData("db_employees.sto_rel_user",array("NIP"=>$data['NIP'],"STOID"=>$data['position'][$i]))->row();
                                if(!empty($isExistSTO)){
                                    $updateSTO = $this->General_model->updateData("db_employees.sto_rel_user",$dataSTOUser,array("NIP"=>$data['NIP']));                                    
                                }else{
                                    $insertSTO = $this->General_model->insertData("db_employees.sto_rel_user",$dataSTOUser);
                                }
                            }
                            $message = (($update) ? "Successfully":"Failed")." updated.";
                        }else{$message="Data not founded.";}
                    }else{
                        $insert = $this->General_model->insertData("db_employees.employees_career",$dataPost);
                        if($insert){
                            //insert into STO
                            $insertSTO = $this->General_model->insertData("db_employees.sto_rel_user",$dataSTOUser);
                        }
                        $message = (($insert) ? "Successfully":"Failed")." saved.";
                    }
                }
            }else{$message = "Cannot saved. Empty dataPost";}

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
                        $dataPostEdu = array("NIP"=>$data['NIP'],"levelEduID"=>$data['eduLevel'][$i], "instituteName"=>$data['eduInstitute'][$i], "location"=>$data['eduCC'][$i], "major"=>$data['eduMajor'][$i], "graduation"=>$data['eduGraduation'][$i], "gpa"=>$data['eduGPA'][$i] );
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

            $this->session->set_flashdata("message",$message);
            redirect(site_url('human-resources/employees/educations/'.$data['NIP']));

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
                    $dataPost = array("NIP"=>$data['NIP'],"company"=>$data['comName'][$i],"industryID"=>$data['comIndustry'][$i], "start_join"=>$data['comStartJoin'][$i], "end_join"=>$data['comEndJoin'][$i], "jobTitle"=>$data['comJobTitle'][$i], "reason"=>$data['comReason'][$i] );
                    if(!empty($data['comID'][$i])){
                        $update = $this->General_model->updateData("db_employees.employees_experience",$dataPost,array("ID"=>$data['comID'][$i]));
                        $message = (($update) ? "Successfully":"Failed")." updated.";
                    }else{
                        $insert = $this->General_model->insertData("db_employees.employees_experience",$dataPost);
                        $message = (($insert) ? "Successfully":"Failed")." saved.";
                    }
                }
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


    public function detailEmployeeOBJ(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$data_arr['NIP']))->row();
            if(!empty($isExist)){
                //$isExist->MyCareer = $this->General_model->fetchData("db_employees.employees_career",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyCareer = $this->m_hr->getEmpCareer(array("a.NIP"=>$isExist->NIP))->result();
                $isExist->MyBank = $this->General_model->fetchData("db_employees.employees_bank_account",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducation = $this->General_model->fetchData("db_employees.employees_educations",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationNonFormal = $this->General_model->fetchData("db_employees.employees_educations_non_formal",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationTraining = $this->General_model->fetchData("db_employees.employees_educations_training",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyFamily = $this->General_model->fetchData("db_employees.employees_family_member",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyExperience = $this->General_model->fetchData("db_employees.employees_experience",array("NIP"=>$isExist->NIP))->result();
                $json = $isExist;
            }
        }
        echo json_encode($json);
    }


    public function removeAdditonal(){
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

    /*END ADDED BY FEBI @ FEB 2020*/


}
