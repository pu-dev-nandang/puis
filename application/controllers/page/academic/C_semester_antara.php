<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_semester_antara extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('akademik/m_matakuliah');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function index()
    {
        $department = parent::__getDepartement();
//        $data['data_mk'] = $this->m_matakuliah->__getAllMK();
        $content = $this->load->view('page/'.$department.'/semesterantara/semester_antara','',true);
        $this->temp($content);
    }

    private function menu_semester_antara($page,$SASemesterID){
        $data['department'] = parent::__getDepartement();
        $data['page']=$page;

        $dataSemesterAntara = $this->db->get_where('db_academic.semester_antara',
            array(
                'ID' => $SASemesterID
            ))->result_array();

        $data['DataSemesterAntara'] = json_encode($dataSemesterAntara);

        $content = $this->load->view('page/'.$data['department'].'/semesterantara/menu_semester_antara',$data,true);
        $this->temp($content);
    }

    public function timetable($SASemesterID){
        $data['SASemesterID'] = $SASemesterID;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_timetable',$data,true);
        $this->menu_semester_antara($page,$SASemesterID);
    }

    public function students($SASemesterID){
        $data['SASemesterID'] = $SASemesterID;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_students',$data,true);
        $this->menu_semester_antara($page,$SASemesterID);
    }

    public function score($SASemesterID){
        $data['SASemesterID'] = $SASemesterID;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_score',$data,true);
        $this->menu_semester_antara($page,$SASemesterID);
    }


    public function setting_timetable($SASemesterID){

        $ScheduleIDSA = $this->input->get('edit');

        $data['ScheduleIDSA'] = $ScheduleIDSA;
        $dataScheduleSA = [];
        if(isset($ScheduleIDSA) && $ScheduleIDSA!='' && $ScheduleIDSA!=null){
            $dataScheduleSA = $this->db->query('SELECT ss.*, cl.Seat, cl.SeatForExam FROM db_academic.sa_schedule ss 
                                                          LEFT JOIN db_academic.classroom cl ON (cl.ID = ss.ClassroomID) 
                                                          WHERE ss.ID = "'.$ScheduleIDSA.'" ')->result_array();

            if(count($dataScheduleSA)>0){
                $ScheduleIDSA = $dataScheduleSA[0]['ID'];
                $dataTeam = $this->db->select('NIP')->get_where('db_academic.sa_schedule_team_teaching',array(
                    'ScheduleIDSA' => $ScheduleIDSA
                ))->result_array();
                $team = [];
                if(count($dataTeam)>0){
                    foreach ($dataTeam AS $itm) {
                        array_push($team,$itm['NIP']);
                    }
                }

                $dataScheduleSA[0]['TeamTeaching'] = $team;

                // Course
                $dataCourse = $this->db->select('IDSSD')->get_where('db_academic.sa_schedule_course',array(
                    'ScheduleIDSA' => $ScheduleIDSA
                ))->result_array();
                $course = [];
                if(count($dataCourse)>0){
                    foreach ($dataCourse AS $item){
                        array_push($course,$item['IDSSD']);
                    }
                }
                $dataScheduleSA[0]['DataCourse'] = $course;
            }
        }

        $data['dataScheduleSA'] = json_encode($dataScheduleSA);
        $data['SASemesterID'] = $SASemesterID;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_setting_timetable',$data,true);
        $this->menu_semester_antara($page,$SASemesterID);
    }

    public function setting_exam($SASemesterID){

        $ExamIDSA = $this->input->get('edit');

        $data['SASemesterID'] = $SASemesterID;
        $dataExamSA = [];
        if(isset($ExamIDSA) && $ExamIDSA!='' && $ExamIDSA!=null){

            $dataExamSA = $this->db->query('SELECT se.*, cl.Seat, cl.SeatForExam FROM db_academic.sa_exam se 
                                              LEFT JOIN db_academic.classroom cl ON (cl.ID = se.ClassroomID) 
                                              WHERE se.ID = "'.$ExamIDSA.'" LIMIT 1')->result_array();


            if(count($dataExamSA)>0){
                $dataExamSA[0]['Course'] = $this->db->select('ScheduleIDSA')->get_where('db_academic.sa_exam_course',
                    array('ExamIDSA' => $ExamIDSA))->result_array();
            }

        }



        $data['ExamIDSA'] = $ExamIDSA;
        $data['dataExamSA'] = json_encode($dataExamSA);
        $data['department'] = parent::__getDepartement();


        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_setting_exam',$data,true);
        $this->menu_semester_antara($page,$SASemesterID);
    }

    public function setting($SASemesterID){
        $data['SASemesterID'] = $SASemesterID;
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/semesterantara/sa_setting',$data,true);
        $this->menu_semester_antara($page,$SASemesterID);
    }



    // ============================================

    public function loadDetails($SA_ID){
        $department = parent::__getDepartement();
        $data['SA_ID'] =$SA_ID;
        $this->load->view('page/'.$department.'/semesterantara/details_sa',$data);

    }


}
