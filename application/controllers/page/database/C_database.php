<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_database extends Globalclass {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model(array('m_sendemail','database/m_database','General_model'));
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
            }else{$message="Student data is not founded.";}
            $json = array("message"=>$message,"finish"=>$isfinish);
        }

        echo json_encode($json);
    }

    /*END ADDED BY FEBRI @ NOV 2019*/



    public function employees()
    {

        $content = $this->load->view('page/database/employees','',true);
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
    public function students()
    {
        $content = $this->load->view('page/database/students','',true);
        $this->temp($content);
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
                $data['NPM'] = $data_arr['NPM'];
                $data['TA'] = $data_arr['TA'];
                $data['detail_ori'] = $isExist;
                $data['detail_auth_ori'] = $this->General_model->fetchData("db_academic.auth_students",array("NPM"=>$data_arr['NPM']))->row();
                $conditions['isApproval'] = 1;
                $data['detail_req'] = $this->General_model->fetchData("db_academic.tmp_students",$conditions)->row();
            }
        }
        $this->load->view('page/database/admisi/requestMerging',$data);
    }

    public function students_req_approval(){
        $data = $this->input->post();
        $myName = $this->session->userdata('Name');
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
                    if(empty($getTempStudentReq->Photo)){
                        unset($getTempStudentReq->Photo);
                    }else{
                        $imgReq = $getTempStudentReq->pathPhoto."uploads/ta_".$data_arr['TA']."/".$getTempStudentReq->Photo;
                        $ch = curl_init($imgReq);
                        $fp = fopen('./uploads/students/ta_'.$data_arr['TA'].'/'.$getTempStudentReq->Photo, 'wb');
                        curl_setopt($ch, CURLOPT_FILE, $fp);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_exec($ch);
                        curl_close($ch);
                        fclose($fp);

                        //remove picture
                        $tmp_pic = $_SERVER['DOCUMENT_ROOT'].'/students/uploads/ta_'.$data_arr['TA'].'/'.$getTempStudentReq->Photo;
                        unlink($tmp_pic);

                        $dataAppv["Photo"] = null;
                    }
                    unset($getTempStudentReq->isApproval);
                    unset($getTempStudentReq->note);
                    unset($getTempStudentReq->created);
                    unset($getTempStudentReq->createdby);
                    unset($getTempStudentReq->edited);
                    unset($getTempStudentReq->editedby);
                    unset($getTempStudentReq->pathPhoto);
                    unset($getTempStudentReq->ID);
                    unset($getTempStudentReq->NPM);
                    $KTPNumber = $getTempStudentReq->KTPNumber;
                    $Access_Card_Number = $getTempStudentReq->Access_Card_Number;
                    unset($getTempStudentReq->KTPNumber);
                    unset($getTempStudentReq->Access_Card_Number);
                    unset($getTempStudentReq->approvedBy);

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
                        $adMessage = "";
                        if($updateTempStd && $updateAuthStd){
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
                        $message = (($updateTempStd && $updateAuthStd) ? "Successfully":"Failed")." saved.".(!empty($adMessage) ? ' <b>'.$adMessage.'</b>':'');
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


    public function testAD(){
        $urlAD = URLAD.'__api/Create';
        echo "URL:".$urlAD;
        $adMessage = "";
        $data_arr['NPM'] = 11140005;
        $Access_Card_Number = 111111;
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
            $update = $this->m_master->apiservertoserver_Response($url,$token,true);
            $adMessage = $update; 
        }else{$adMessage="Windows active directory server not connected";}
        
    }

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

    /*END ADDED BY FEBRI @ NOV 2019*/


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
