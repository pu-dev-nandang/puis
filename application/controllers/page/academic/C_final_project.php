<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_final_project extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->load->model('akademik/m_tahun_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_transcript($page){
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$data['department'].'/finalproject/menu_finalproject',$data,true);
        parent::template($content);
    }

    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/finalproject_list_student',$data,true);
        $this->menu_transcript($page);
    }

    public function list_student(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/list_student',$data,true);
        $this->menu_transcript($page);
    }

    public function seminar_schedule(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/seminar_schedule',$data,true);
        $this->menu_transcript($page);
    }

    public function monitoring_yudisium(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/monitoring_yudisium',$data,true);
        $this->menu_transcript($page);
    }

    public function setting_transcript(){
        $data['Transcript'] = $this->db->get('db_academic.setting_transcript')->result_array()[0];
        $data['Graduation'] = $this->db->get('db_academic.graduation')->result_array();
        $data['Education'] = $this->db->get('db_academic.education_level')->result_array();
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/transcript/setting_transcript',$data,true);
        $this->menu_transcript($page);
    }

    public function uploadIjazahStudent(){

        $fileName = $this->input->get('fileName');
        $old = $this->input->get('old');
        $id = $this->input->get('id');

        $config['upload_path']          = './uploads/ijazah_student/';
        $config['allowed_types']        = '*';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;

        if($old!='' && is_file('./uploads/ijazah_student/'.$old)){
            unlink('./uploads/ijazah_student/'.$old);
        }


        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('userfile')){
            // Error
            $error = array('error' => $this->upload->display_errors());
            return print_r(json_encode($error));
        }
        else {
            // Sukses
            $this->db->set('IjazahSMA', $fileName);
            $this->db->where('ID', $id);
            $this->db->update('db_academic.auth_students');
            return print_r(1);
        }
    }

    public function scheduling_final_project(){

        $ID = $this->input->get('id');

        $data['department'] = parent::__getDepartement();
        $data['ID'] = ($ID!='') ? $ID : '';

        $DataEdit = [];
        if($ID!=''){
            $DataEdit = $this->db->query('SELECT fpc.*, cl.Room, cl.Seat, cl.SeatForExam FROM db_academic.final_project_schedule fpc 
                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID = fpc.ClassroomID)
                                                    WHERE fpc.ID = "'.$ID.'" ')->result_array();

            if(count($DataEdit)>0){
                for($i=0;$i<count($DataEdit);$i++){
                    // Get Std
                    $DataEdit[$i]['Student'] = $this->db->query('SELECT sp.*, fp.Status, ats.Name, em1.Name AS  MentorFP1_Name,  
                                                         em2.Name AS  MentorFP2_Name FROM db_academic.final_project_schedule_student sp
                                                        LEFT JOIN db_academic.final_project fp ON (fp.NPM = sp.NPM)
                                                        LEFT JOIN db_academic.auth_students ats ON (ats.NPM = sp.NPM)
                                                        LEFT JOIN db_employees.employees em1 ON (em1.NIP = ats.MentorFP1)
                                                        LEFT JOIN db_employees.employees em2 ON (em2.NIP = ats.MentorFP2)
                                                        WHERE sp.FPSID = "'.$DataEdit[$i]['ID'].'" ')->result_array();

                    $DataEdit[$i]['Examiner'] = $this->db->query('SELECT sp.*, em.Name  FROM db_academic.final_project_schedule_lecturer sp 
                                                        LEFT JOIN db_employees.employees em ON (em.NIP = sp.NIP)
                                                        WHERE sp.FPSID = "'.$DataEdit[$i]['ID'].'" ')->result_array();
                }
            }


        }

        $data['DataEdit'] = json_encode($DataEdit);
        $data['ID'] = (count($DataEdit)>0) ? $ID : '';

        $page = $this->load->view('page/'.$data['department'].'/finalproject/scheduling_final_project',$data,true);
        $this->menu_transcript($page);
    }

}
