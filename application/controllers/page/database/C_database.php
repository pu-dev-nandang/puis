<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_database extends Globalclass {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model(array('m_sendemail','database/m_database','General_model','akademik/M_akademik'));
        $this->load->library('JWT');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_lecturer($page){
        $data['page'] = $page;
        $content = $this->load->view('page/database/menu_lecturer',$data,true);
        $this->temp($content);
    }



    public function lecturers()
    {
        $page = $this->load->view('page/database/lecturers','',true);
        $this->menu_lecturer($page);
    }

    public function mentor_academic(){

        $page = $this->load->view('page/database/mentor_academic','',true);
        $this->menu_lecturer($page);
    }


    public function lecturersDetails($NIP){
        $data['NIP']=$NIP;
        $content = $this->load->view('page/database/lecturer/lecturer_menu',$data,true);
        $this->temp($content);
    }

    public function loadpagelecturersDetails(){
        $data_arr = $this->getInputToken();
        //print_r($data_arr);
        $this->load->view('page/database/lecturer/'.$data_arr['page'],$data_arr);
    }

    /*ADDED BY FEBRI @ NOV 2019*/

    public function lecturerRequest(){
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
        $this->load->view('page/database/lecturer/requestMerging',$data);
    }
 

    public function lecturerRequestAppv(){
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
                if($data_arr['ACT'] == 2){ //for rejected
                    $dataPost = array("isApproved"=>2,"NoteApproved"=>(!empty($data_arr['NOTE']) ? $data_arr['NOTE'] : null));                    
                    $rejectData = $this->General_model->updateData("db_employees.employees",$dataPost,$conditions);
                    $message = ($rejectData ? "Successfully":"Failed")." saved.";
                }else if($data_arr['ACT'] == 0){ //for approved
                    $reqData = $isExist->Logs;
                }else{$message="Unknow request approved.";}
            }

            /*if(!empty($isExist)){
                if($data_arr['ACT'] == 1){
                    $getTempEmpyReq = $this->General_model->fetchData("db_employees.tmp_employees",$conditions)->row();
                    $dataAppv = array();
                    $reqpic = "";
                    if(!empty($getTempEmpyReq->Photo)){
                        $reqpic = $getTempEmpyReq->Photo;
                        $changeName = explode("-", $getTempEmpyReq->Photo);
                        rename("./uploads/employees/".$getTempEmpyReq->Photo, "./uploads/employees/".$changeName[0].".jpg");
                    }
                    unset($getTempEmpyReq->Photo);
                    unset($getTempEmpyReq->ID);
                    unset($getTempEmpyReq->NIP);
                    unset($getTempEmpyReq->isApproval);
                    unset($getTempEmpyReq->note);
                    unset($getTempEmpyReq->created);
                    unset($getTempEmpyReq->createdby);
                    unset($getTempEmpyReq->edited);
                    unset($getTempEmpyReq->editedby);
                    unset($getTempEmpyReq->pathPhoto);
                    $getTempEmpyReq->Name = ucwords($getTempEmpyReq->Name);
                    $updateTA = $this->General_model->updateData("db_employees.employees",$getTempEmpyReq,$conditions);
                    if($updateTA){
                        //check if has a different birthdate between old and new
                        if($isExist->DateOfBirth != $getTempEmpyReq->DateOfBirth){
                            //update birthdate-pass on auth student
                            $updateOldPass = $this->General_model->updateData("db_employees.employees",array("Password_old"=>date("dmy",strtotime($getTempEmpyReq->DateOfBirth))),$conditions);
                        }

                        $adMessage="";
                        //check if access card different
                        if($_SERVER['SERVER_NAME']=='pcam.podomorouniversity.ac.id'){
                            if($isExist->Access_Card_Number != $getTempEmpyReq->Access_Card_Number){
                                $urlAD = URLAD.'__api/Create';
                                $is_url_exist = $this->m_master->is_url_exist($urlAD);
                                if ($is_url_exist) {
                                    $explodeMailPU = explode("@", $isExist->EmailPU);
                                    $username = $explodeMailPU[0];
                                    //update to AD
                                    $data_arr1 = [
                                        'pager' => $getTempEmpyReq->Access_Card_Number ,
                                    ];                                    
                                    $dataAD = array(
                                        'auth' => 's3Cr3T-G4N',
                                        'Type' => 'Employee',
                                        'UserID' => $username,
                                        'data_arr' => $data_arr1,
                                    );

                                    $url = URLAD.'__api/Edit';
                                    $token = $this->jwt->encode($dataAD,"UAP)(*");
                                    $updateAD = $this->m_master->apiservertoserver_Response($url,$token,true);
                                    $adMessage = ($updateAD[0] != 1) ? "Failed update Access Card to Windows Active Directory.!":"";
                                }else{$adMessage="Windows active directory server not connected";}
                            }
                        }

                        //update status table temp_student
                        $dataAppv['isApproval'] = 2;
                        $dataAppv['note'] = (!empty($data_arr['NOTE']) ? $data_arr['NOTE'] : null);
                        $dataAppv['editedby'] = $myName;

                        $updateTempStd = $this->General_model->updateData("db_employees.tmp_employees",$dataAppv,$conditions);
                        $message = (($updateTempStd) ? "Successfully":"Failed")." saved.".(!empty($adMessage) ? "<b>".$adMessage."</b>":"");
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
            }else{$message="Student data is not founded.";}*/
            $json = array("message"=>$message,"finish"=>$isfinish);
        }

        echo json_encode($json);
    }

    /*END ADDED BY FEBRI @ NOV 2019*/



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
        $this->temp($content);
    }

    public function form_input_employees($page = '')
    {   
        $this->data['NIPedit'] = $page;
        if ($page == '') {
            $this->data['action'] = 'add';
        }
        else
        {
            $this->data['action'] = 'edit';
        }
        
        $content = $this->load->view('page/database/employees/form_input',$this->data,true);
        $this->temp($content);
    }

    public function form_input_submit_employees()
    {
        $data_arr = $this->getInputToken();
        $action = $this->input->post('Action');
        // print_r($action);
        if ($action == 'add') {
            $NIP = $data_arr['NIP'];
            $uploadFile = $this->uploadFoto($NIP);
            if (is_array($uploadFile)) {
                $ReligionID  = $data_arr['ReligionID'];
                $JobGradeID = null;
                $PositionMain = $data_arr['Division'].'.'.$data_arr['PositionMain'];
                $ProdiID = null;
                $CityID = null;
                $ProvinceID = null;
                $NIDN = $data_arr['NIDN'];
                $KTP = $data_arr['KTP'];
                $Name = $data_arr['Name'];
                $TitleAhead = $data_arr['TitleAhead'];
                $TitleBehind = $data_arr['TitleBehind'];
                $Gender = $data_arr['Gender'];
                $PlaceOfBirth = $data_arr['PlaceOfBirth'];
                $DateOfBirth = $data_arr['BirthYears'].'-'.$data_arr['BirthMonth'].'-'.$data_arr['BirthDate'];
                $Phone = $data_arr['Phone'];
                $HP = $data_arr['HP'];
                $Email = $data_arr['Email'];
                $EmailPU = $data_arr['EmailPU'];
                $Password = 123456;
                $Address = $data_arr['Address'];
                $Photo = $uploadFile['file_name'];
                $PositionOther1 = $data_arr['PositionOtherdivisi1'].'.'.$data_arr['PositionOtherMain1'];
                $PositionOther1 = ($PositionOther1 == '.')? '' : $PositionOther1;
                $PositionOther2 = $data_arr['PositionOtherdivisi2'].'.'.$data_arr['PositionOtherMain2'];
                $PositionOther2 = ($PositionOther2 == '.')? '' : $PositionOther2;
                $PositionOther3 = $data_arr['PositionOtherdivisi3'].'.'.$data_arr['PositionOtherMain3'];
                $PositionOther3 = ($PositionOther3 == '.')? '' : $PositionOther3;
                $StatusEmployeeID = $data_arr['StatusEmployeeID'];
                $Status = "1";
                $this->m_database->insert_data_employees($ReligionID,$JobGradeID,$PositionMain,$ProdiID,$CityID,$ProvinceID,$NIP,$NIDN,$KTP,$Name,$TitleAhead,$TitleBehind,$Gender,$PlaceOfBirth,$DateOfBirth,$Phone,$HP,$Email,$EmailPU,$Password,$Address,$Photo,$PositionOther1,$PositionOther2,$PositionOther3,$StatusEmployeeID,$Status);
                echo json_encode(array('msg' => 'The file has been successfully uploaded and data has been saved','status' => 1));
            }
            else
            {
                echo json_encode(array('msg' => 'The file did not upload successfully','status' => 0));
            }
        }
        elseif ($action == 'edit') {
            $a = $this->input->post('fileData'); // get File Upload Data
            $NIP = $data_arr['NIP'];
            $ReligionID  = $data_arr['ReligionID'];
            // $JobGradeID = null;
            $PositionMain = $data_arr['Division'].'.'.$data_arr['PositionMain'];
            $ProdiID = null;
            $CityID = null;
            $ProvinceID = null;
            $NIDN = $data_arr['NIDN'];
            $KTP = $data_arr['KTP'];
            $Name = $data_arr['Name'];
            $TitleAhead = $data_arr['TitleAhead'];
            $TitleBehind = $data_arr['TitleBehind'];
            $Gender = $data_arr['Gender'];
            $PlaceOfBirth = $data_arr['PlaceOfBirth'];
            $DateOfBirth = $data_arr['BirthYears'].'-'.$data_arr['BirthMonth'].'-'.$data_arr['BirthDate'];
            $Phone = $data_arr['Phone'];
            $HP = $data_arr['HP'];
            $Email = $data_arr['Email'];
            $EmailPU = $data_arr['EmailPU'];
            // $Password = 123456;
            $Address = $data_arr['Address'];
            // $Photo = $uploadFile['file_name'];
            $PositionOther1 = $data_arr['PositionOtherdivisi1'].'.'.$data_arr['PositionOtherMain1'];
            $PositionOther1 = ($PositionOther1 == '.')? '' : $PositionOther1;
            $PositionOther2 = $data_arr['PositionOtherdivisi2'].'.'.$data_arr['PositionOtherMain2'];
            $PositionOther2 = ($PositionOther2 == '.')? '' : $PositionOther2;
            $PositionOther3 = $data_arr['PositionOtherdivisi3'].'.'.$data_arr['PositionOtherMain3'];
            $PositionOther3 = ($PositionOther3 == '.')? '' : $PositionOther3;

            $StatusEmployeeID = $data_arr['StatusEmployeeID'];
            $Status = "1";
            $NIPedit =  $data_arr['NIPedit'];
            if ($a == 'undefined') {
               $this->m_database->update_data_employees1($ReligionID,$PositionMain,$NIP,$NIDN,$KTP,$Name,$TitleAhead,$TitleBehind,$Gender,$PlaceOfBirth,$DateOfBirth,$Phone,$HP,$Email,$EmailPU,$Address,$PositionOther1,$PositionOther2,$PositionOther3,$StatusEmployeeID,$NIPedit);
               echo json_encode(array('msg' => 'The data has been updated','status' => 1));
            }
            else
            {
                $uploadFile = $this->uploadFoto($NIP);
                $Photo = $uploadFile['file_name'];
                $this->m_database->update_data_employees1($ReligionID,$PositionMain,$NIP,$NIDN,$KTP,$Name,$TitleAhead,$TitleBehind,$Gender,$PlaceOfBirth,$DateOfBirth,$Phone,$HP,$Email,$EmailPU,$Address,$PositionOther1,$PositionOther2,$PositionOther3,$StatusEmployeeID,$NIPedit);
                 echo json_encode(array('msg' => 'The data has been updated','status' => 1));
            }
             // print_r($a);
        }
        else
        {
            exit('No direct script access allowed');
        }
        
    }

    public function uploadFoto($NIP)
    {
         // upload file
         $filename = $NIP;
         $config['upload_path']   = './uploads/employees/';
         $config['overwrite'] = TRUE; 
         $config['allowed_types'] = 'png|PNG|jpg|JPG|jpeg|jpeg'; 
         $config['file_name'] = $filename;
         //$config['max_size']      = 100; 
         //$config['max_width']     = 300; 
         //$config['max_height']    = 300;  
         $this->load->library('upload', $config);
            
         if ( ! $this->upload->do_upload('fileData')) {
            return $error = $this->upload->display_errors(); 
            //$this->load->view('upload_form', $error); 
         }
            
         else { 
           return $data =  $this->upload->data(); 
            //$this->load->view('upload_success', $data); 
         }
    }


    public function changestatus()
    {
        $data_arr = $this->getInputToken();
        $NIP = $data_arr['NIP'];
        $Active = $data_arr['Active'];
        $this->m_database->changestatus($NIP,$Active);
    }


    // Modal Show Detail Mahasiswa
    public function showStudent(){
        $data['token'] = $this->input->post('token');
        $this->load->view('page/database/modal/modal_detail_student',$data);
    }


    // === Students ===

    public function menu_student($page){

        $data['page'] = $page;
        $content = $this->load->view('page/database/students/menu_student',$data,true);
        $this->temp($content);

    }

    public function students()
    {
        /*UPDATED BY FEBRI @ JAN 2020*/
        $data['studyprogram'] = $this->General_model->fetchData("db_academic.program_study",array("Status"=>1))->result();
        $data['statusstd'] = $this->General_model->fetchData("db_academic.status_student",array())->result();
        $data['religion'] = $this->General_model->fetchData("db_admission.agama",array())->result();
        $data['yearIntake'] = $this->General_model->fetchData("db_academic.semester",array(),null,null,null,"Year")->result();

        // Updated by Adhi 19-02-2020
        $data['rest_setting_alumni'] = $this->m_master->showData_array('db_alumni.rest_setting');
        // END by Adhi 19-02-2020
        $page = $this->load->view('page/database/students/list_students',$data,true);
        /*END UPDATED BY FEBRI @ JAN 2020*/

        $this->menu_student($page);

    }

    public function block_students(){

        $data = '';
        $page = $this->load->view('page/database/students/block_students',$data,true);
        $this->menu_student($page);

    }

    public function students_group()
    {
        $content = $this->load->view('page/database/students/students_group','',true);
        $this->temp($content);
    }

    public function edit_students($DB_Student,$NPM,$Name){

        $data['DB_Student'] = $DB_Student;
        $data['NPM'] = $NPM;
        $data['Name'] = $Name;
        // load Nationality
        $data['Arr_nationality'] = json_encode($this->m_master->caribasedprimary('db_admission.country','ctr_active',1));
        $data['Religion'] = $this->General_model->fetchData("`db_admission`.`agama`",array())->result();
        $data['companyInsurance'] = $this->General_model->fetchData("db_employees.master_company",array("IndustryID"=>33,"IsActive"=>1),"Name","ASC")->result();
        $content = $this->load->view('page/database/students/editStudent',$data,true);
        $this->temp($content);
    }

    public function loadPageStudents(){
        $data['dataForm'] = $this->input->post('data');
        $this->load->view('page/database/students/students_details',$data);
    }

    public function students2()
    {
        $content = $this->load->view('page/database/students','',true);
        $this->temp($content);
    }

    public function data_mahasiswa()
    {
        $content = $this->load->view('page/database/admisi/students','',true);
        $this->temp($content);
    }

    public function loadPageStudents_admission()
    {
        $data['dataForm'] = $this->input->post('data');
        $this->load->view('page/database/admisi/students_details',$data);
    }


    /*ADDED BY FEBRI @ NOV 2019*/
    public function students_req_merge(){

        $data = $this->input->post();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $conditions = array("NPM"=>$data_arr['NPM']);
            $isExist = $this->General_model->fetchData("ta_".$data_arr['TA'].".students",$conditions)->row();
            if(!empty($isExist)){
                $isExist->Insurance = $this->M_akademik->getStdInsurance(array("a.NPM"=>$data_arr['NPM']))->row();
                $data['NPM'] = $data_arr['NPM'];
                $data['TA'] = $data_arr['TA'];
                if(!empty($isExist)){
                    if(!empty($isExist->InsuranceID)){
                        $getInsurance = $this->General_model->fetchData("db_academic.std_insurance", array("NPM"=>$isExist->NPM))->row();
                        if(!empty($getInsurance)){
                            $detailInsurance = $this->General_model->fetchData("db_employees.master_company",array("InsuranceID"=>$getInsurance->InsuranceID))->row();
                            $getInsurance->CompanyName = $detailInsurance->Name; 
                            $isExist->Insurance = $getInsurance; 
                        }                        
                    }
                    if(!empty($isExist->ReligionID)){
                        $religion = $this->General_model->fetchData("db_admission.agama",array("ID"=>$isExist->ReligionID))->row();
                        $isExist->Religion = (!empty($religion) ? $religion->Nama : '-');
                    }
                }
                $data['detail_ori'] = $isExist;
                $data['detail_auth_ori'] = $this->General_model->fetchData("db_academic.auth_students",array("NPM"=>$data_arr['NPM']))->row();
                $conditions['isApproval'] = 1;
                $data['detail_req'] = $this->General_model->fetchData("db_academic.tmp_students",$conditions)->row();
                if(!empty($data['detail_req'])){
                    if(!empty($data['detail_req']->InsuranceID)){
                        $data['detail_req']->Insurance = $this->General_model->fetchData("db_employees.master_company",array("ID"=>$data['detail_req']->InsuranceID))->row();
                    }
                    if(!empty($data['detail_req']->ReligionID)){
                        $religion = $this->General_model->fetchData("db_admission.agama",array("ID"=>$data['detail_req']->ReligionID))->row();
                        $data['detail_req']->Religion = (!empty($religion) ? $religion->Nama : '-');
                    }
                }
            }
        }
        $this->load->view('page/database/admisi/requestMerging',$data);
    }

    public function students_req_approval(){
        $data = $this->input->post();
        $myName = $this->session->userdata('Name');
        $myNIP = $this->session->userdata('NIP');
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $conditions = array("NPM"=>$data_arr['NPM']);
            $isExist = $this->General_model->fetchData("ta_".$data_arr['TA'].".students",$conditions)->row();
            $isExistAuth = $this->General_model->fetchData("db_academic.auth_students",$conditions)->row();
            $message = ""; $isfinish = false;
            if(!empty($isExist) && !empty($isExistAuth)){
                if($data_arr['ACT'] == 1){
                    $getTempStudentReq = $this->General_model->fetchData("db_academic.tmp_students",$conditions)->row();
                    $dataAppv = array();

                    if(!empty($getTempStudentReq->Photo)){
                        $reqpic = $getTempStudentReq->Photo;
                        $changeName = explode("-", $getTempStudentReq->Photo);
                        rename("./uploads/students/ta_".$data_arr['TA']."/".$getTempStudentReq->Photo, "./uploads/students/ta_".$data_arr['TA']."/".$changeName[0].".jpg");
                    }
                    
                    unset($getTempStudentReq->Photo);
                    unset($getTempStudentReq->isApproval);
                    unset($getTempStudentReq->note);
                    unset($getTempStudentReq->created);
                    unset($getTempStudentReq->createdby);
                    unset($getTempStudentReq->edited);
                    unset($getTempStudentReq->editedby);
                    unset($getTempStudentReq->ID);
                    unset($getTempStudentReq->Name);

                    $KTPNumber = $getTempStudentReq->KTPNumber;
                    $Access_Card_Number = $getTempStudentReq->Access_Card_Number;
                    unset($getTempStudentReq->KTPNumber);
                    unset($getTempStudentReq->Access_Card_Number);
                    unset($getTempStudentReq->approvedBy);

                    //HEALTY INSURANCE 
                    $dataInsurance = array();
                    $dataInsurance['InsuranceID'] = ($getTempStudentReq->InsuranceID != 'OTH') ? $getTempStudentReq->InsuranceID:null;
                    $dataInsurance['InsuranceOTH'] = $getTempStudentReq->InsuranceOTH;
                    $dataInsurance['InsurancePolicy'] = $getTempStudentReq->InsurancePolicy;
                    $dataInsurance['EffectiveStart'] = $getTempStudentReq->EffectiveStart;
                    $dataInsurance['EffectiveEnd'] = $getTempStudentReq->EffectiveEnd;
                    //clone card
                    if(!empty($getTempStudentReq->Card)){
                        $cardName = $getTempStudentReq->Card;
                        $clonepath = "./uploads/students/insurance_card/";
                        $cloneNewFile = str_replace("REQ", "APPV", $getTempStudentReq->Card);
                        $cloneCard = copy($clonepath.$cardName, $clonepath.$cloneNewFile);
                        unlink($clonepath.$cardName);
                        $dataInsurance['Card'] = $cloneNewFile;
                    }
                    //end clone
                    $dataInsurance['NPM'] = $getTempStudentReq->NPM;
                    unset($getTempStudentReq->InsuranceID);
                    unset($getTempStudentReq->InsuranceOTH);
                    unset($getTempStudentReq->InsurancePolicy);
                    unset($getTempStudentReq->EffectiveStart);
                    unset($getTempStudentReq->EffectiveEnd);
                    unset($getTempStudentReq->NPM);
                    unset($getTempStudentReq->Card);
                    //END HEALTY INSURANCE 

                    $updateTA = $this->General_model->updateData("ta_".$data_arr['TA'].".students",$getTempStudentReq,$conditions);
                    if($updateTA){
                        //check if has a different birthdate between old and new
                        if($isExist->DateOfBirth != $getTempStudentReq->DateOfBirth){
                            //update birthdate-pass on auth student
                            $updateOldPass = $this->General_model->updateData("db_academic.auth_students",array("Password_old"=>date("dmy",strtotime($getTempStudentReq->DateOfBirth))),$conditions);
                        }

                        //update status table temp_student
                        $dataAppv['isApproval'] = 2;
                        $dataAppv['note'] = (!empty($data_arr['NOTE']) ? $data_arr['NOTE'] : null);
                        $dataAppv['editedby'] = $myName;
                        $dataAppv['approvedBy'] = $myName;
                        
                        $updateTempStd = $this->General_model->updateData("db_academic.tmp_students",$dataAppv,$conditions);
                        //update to table auth student on dbaccademic
                        $updateAuthStd = $this->General_model->updateData("db_academic.auth_students",array("KTPNumber"=>$KTPNumber,"Access_Card_Number"=>$Access_Card_Number),$conditions);
                        //insert new company insurance
                        if(!empty($dataInsurance['InsuranceOTH']) && empty($dataInsurance['InsuranceID'])){
                            $isSameCompany = $this->General_model->fetchData("db_employees.master_company",array("Name"=>$dataInsurance['InsuranceOTH']))->row();
                            if(empty($isSameCompany)){
                                $insertInsurance = $this->General_model->insertData("db_employees.master_company",array("Name"=>$dataInsurance['InsuranceOTH'],"IsActive"=>1,"IndustryID"=>33,"createdby"=>$myNIP));
                                $dataInsurance['InsuranceID'] = $this->db->insert_id();
                            }
                        }
                        //update Insurance
                        $isExistInsurance = $this->General_model->fetchData("db_academic.std_insurance",$conditions)->row();
                        if(!empty($isExistInsurance)){
                            //if(!empty($getTempStudentReq->Card)){ unlink($clonepath.$cardName); }
                            $dataInsurance['editedby'] = $myName;
                            $saveInsurance = $this->General_model->updateData("db_academic.std_insurance",$dataInsurance,$conditions);
                        }else{
                            $dataInsurance['createdby'] = $myName;
                            $saveInsurance = $this->General_model->insertData("db_academic.std_insurance",$dataInsurance);
                        }

                        $adMessage = "";
                        if($updateTempStd && $updateAuthStd && $saveInsurance){
                            if(!empty($Access_Card_Number)){
                                if($Access_Card_Number != $isExistAuth->Access_Card_Number){ //check if different number of card
                                    if($_SERVER['SERVER_NAME']=='pcam.podomorouniversity.ac.id'){
                                        $urlAD = URLAD.'__api/Create';
                                        $is_url_exist = $this->m_master->is_url_exist($urlAD);
                                        if ($is_url_exist) {
                                            //update to AD
                                            $data_arr1 = [
                                                'pager' => $Access_Card_Number ,
                                            ];
                                            $dataAD = array(
                                                'auth' => 's3Cr3T-G4N',
                                                'Type' => 'Student',
                                                'UserID' => $data_arr['NPM'],
                                                'data_arr' => $data_arr1,
                                            );

                                            $url = URLAD.'__api/Edit';
                                            $token = $this->jwt->encode($dataAD,"UAP)(*");
                                            //$this->m_master->apiservertoserver_NotWaitResponse($url,$token);                                    
                                            $updateAD = $this->m_master->apiservertoserver_Response($url,$token,true);
                                            $adMessage = ($updateAD[0] != 1) ? "Failed update Access Card to Windows Active Directory.!":"";
                                        }else{$adMessage="Windows active directory server not connected";}
                                    }
                                }
                            }                            
                        }
                        $message = (($updateTempStd && $updateAuthStd && $saveInsurance) ? "Successfully":"Failed")." saved.".(!empty($adMessage) ? ' <b>'.$adMessage.'</b>':'');
                        $isfinish = $updateTempStd;
                    }else{
                        $message = "Failed saved data. Try again.";
                    }
                }else{
                    //update status table temp_student
                    $updateTempStd = $this->General_model->updateData("db_academic.tmp_students",array("isApproval"=>$data_arr['ACT'],"note"=>(!empty($data_arr['NOTE']) ? $data_arr['NOTE'] : null),"editedby"=>$myName),$conditions);
                    $message = (($updateTempStd) ? "Successfully":"Failed")." saved." ;
                    $isfinish = $updateTempStd;
                }
            }else{$message="Student data is not founded.";}

            $json = array("message"=>$message,"finish"=>$isfinish);
        }
        echo json_encode($json);
    }


    public function saveStudentHealtInfo(){
        $data = $this->input->post();
        $myName = $this->session->userdata('Name');
        $myNIP = $this->session->userdata('NIP');
        if($data){
            $conditions = array("NPM"=>$data['NPM']);
            $isExist = $this->General_model->fetchData("db_academic.auth_students",$conditions)->row();
            if(!empty($isExist)){
                $data['EffectiveStart'] = date("Y-m-d",strtotime($data['EffectiveStart']));
                $data['EffectiveEnd'] = date("Y-m-d",strtotime($data['EffectiveEnd']));
                
                $uri = "ta_".$isExist->Year."/".$isExist->NPM."/".preg_replace('/\s+/', '-', $isExist->Name)."#stdHI";
                $message = "";
                if(!empty($_FILES['insuranceCard']['name'])){
                    $ispic = false;
                    $file_name = $_FILES['insuranceCard']['name'];
                    $file_size =$_FILES['insuranceCard']['size'];
                    $file_tmp =$_FILES['insuranceCard']['tmp_name'];
                    $file_type=$_FILES['insuranceCard']['type'];
                    if($file_type == "image/jpeg" || $file_type == "image/png"){
                        $ispic = true;
                    }else {
                        $ispic = false;
                        $err_msg     .= "Extention image '".$file_name."' doesn't allowed.";
                    }
                    if($file_size > 2097152){ //2Mb
                        $err_msg     .= "Size of image '".$file_name."'s too large from 2Mb.";
                    }else { $ispic = true; }

                    $newFilename = 'insurance-card-'.$data['NPM']."-REQ-".date('Y-m-d').".jpg";

                    //create folder for an album 
                    if($_SERVER['SERVER_NAME']=='studentpu.podomorouniversity.ac.id'){
                        $pathInPieces = explode('/', $_SERVER['DOCUMENT_ROOT']);
                        $t = count($pathInPieces) - 1;
                        $newPath = "";
                        for ($i=0; $i < $t; $i++) { 
                            $newPath .= $pathInPieces[$i]."/";
                        }
                        $folderPCAM = $newPath.'pcam/';
                    }else{
                        $folderPCAM = $_SERVER['DOCUMENT_ROOT'].'/puis/';
                    }

                    //create folder for an album 
                    $pathFile = $folderPCAM.".//uploads//students//insurance_card";
                    //$folderName = APPPATH."../".$pathFile;
                    if(!file_exists($pathFile)){
                        mkdir($pathFile,0777);
                        $error403 = "<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
                        file_put_contents($pathFile."/index.html", $error403);
                    }
                    
                    $moveimage = move_uploaded_file($file_tmp,$pathFile."//".$newFilename);
                    if($moveimage) {$data['Card'] = $newFilename;}
                    $message .= ((!$moveimage) ? "<b>Failed upload image.</b> ":"");
                }

                //check insurance other
                if(!empty($data['InsuranceOTH'])){
                    $isSameCompany = $this->General_model->fetchData("db_employees.master_company",array("Name"=>$data['InsuranceOTH']))->row();
                    if(empty($isSameCompany)){
                        $insertInsurance = $this->General_model->insertData("db_employees.master_company",array("Name"=>$data['InsuranceOTH'],"IsActive"=>1,"IndustryID"=>33,"createdby"=>$myNIP));
                        $data['InsuranceID'] = $this->db->insert_id();
                    }
                }

                $isExistInsurance = $this->General_model->fetchData("db_academic.std_insurance",$conditions)->row();
                if(!empty($isExistInsurance)){
                    //if(!empty($_FILES['insuranceCard']['name'])){ unlink("./uploads/students/insurance_card/".$isExistInsurance->Card); }
                    $data['editedby'] = $myName;
                    $saveInsurance = $this->General_model->updateData("db_academic.std_insurance",$data,$conditions);
                }else{
                    $data['createdby'] = $myName;
                    $saveInsurance = $this->General_model->insertData("db_academic.std_insurance",$data);
                }
                $message = (($saveInsurance) ? "Successfully":"Failed")." saved.";
            }else{
                $message = "Student does not founded.";
                $uri = "404-Not-found";
            }
            $this->session->set_flashdata("message",$message);
            redirect(site_url('database/students/edit-students/'.$uri));
        }
    }

    /*END ADDED BY FEBRI @ NOV 2019*/

    private function pingAddress($url=null) {
        if(!empty($url)){
            $getProtocol = ((preg_match('/\bhttp\b/', $url)) ? "http://": ((strpos($url, 'https') !== false) ? "https://":"") );
            if(!empty($getProtocol)){
                $explode = explode($getProtocol, $url);
                $splitColon = explode(":", $explode[1]);
                $ipAddress = $splitColon[0];
                echo $ipAddress;
                $pingresult = exec("ping ".$ipAddress, $outcome, $status);
                var_dump($pingresult);
                if (0 == $status) {
                    $status = "alive";
                } else {
                    $status = "dead";
                }      
                return $status;
            }else return false;
        }else return false;
        /*
        return $status;*/
    }



    // Reset Pasword ====
    public function sendMailResetPassword(){

        $data_arr = $this->getInputToken();
        $token = $this->input->post('token');

        $to = $data_arr['Email'];
//        $to = 'nndg.ace3@gmail.com';

        $subject = 'Reset Your Password';
        $text = 'Dear <strong style="color: blue;">'.$data_arr['Name'].'</strong>,

                <p style="color: #673AB7;">To reset your password on <strong>Portal Podomoro University</strong>, please Click the link below, and your account will be activated instantly</p>

                <table width="178" cellspacing="0" cellpadding="12" border="0">
                    <tbody>
                    <tr>
                        <td bgcolor="#ff9000" align="center">
                            <a href="'.url_sign_out.'resetpassword/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color:#ff9000" target="_blank" >Reset Password</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br/>';

        $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text,null,'Reset Password');

        return print_r(1);
    }


}
