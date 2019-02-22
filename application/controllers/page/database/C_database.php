<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_database extends Globalclass {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('m_sendemail');
        $this->load->model('database/m_database');
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

    public function edit_students($DB_Student,$NPM,$Name){

        $data['DB_Student'] = $DB_Student;
        $data['NPM'] = $NPM;
        $data['Name'] = $Name;

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
