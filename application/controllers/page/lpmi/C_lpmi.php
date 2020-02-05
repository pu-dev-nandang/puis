<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_lpmi extends Lpmi {

    function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_edom($page){
        $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/edom/menu_edom',$data,true);
        $this->temp($content);
    }

    public function edom_list_lecturer()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/edom/list_lecturer',$data,true);
        $this->menu_edom($page);
    }

    public function edom_list_question()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/edom/question',$data,true);
        $this->menu_edom($page);
    }

    public function crudQuestion($action,$ID)
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/edom/crudQuestion',$data,true);
        $this->menu_edom($page);
    }


    public function edom_list_result(){
        $data['department'] = parent::__getDepartement();
        $data['semester'] = $this->General_model->fetchData("db_academic.semester",array(),"ID","ASC")->result();
        $page = $this->load->view('page/'.$data['department'].'/edom/list_result',$data,true);
        $this->menu_edom($page);
    }


    public function request_edom(){
        $data = $this->input->post();
        if($data){
            $explodeSemester = explode(".", $data['semester']);
            $semeseterID = (!empty($explodeSemester[0]) ? $explodeSemester[0] : 0);
            $semeseterYear = (!empty($explodeSemester[1]) ? $explodeSemester[1] : 0);
            $semeseterOddEvent = (!empty($explodeSemester[2]) ? $explodeSemester[2] : 0);
            $semesterType = ($semeseterOddEvent==2)? "genap":"ganjil";
            
            $explodeProdi = explode(".", $data['prodi']);
            $prodiID = (!empty($explodeProdi[0]) ? $explodeProdi[0] : 0);
            $prodiCode = (!empty($explodeProdi[1]) ? strtolower($explodeProdi[1]) : null);

            $message = "";
            if((!empty($semeseterID) && !empty($data['intake'])) && (!empty($prodiID)) ){
                $tablename = "edomRecap_".$prodiCode."_".$semeseterYear."_".$semesterType."_".$data['intake'];
                //chek existing table
                $isExistTable = $this->General_model->callStoredProcedure("select * from information_schema.`TABLES` where table_schema = 'db_statistik' and table_name = '".$tablename."'")->row();
                if(!empty($isExistTable)){
                    $conditions = array("Prodi_id"=>$prodiID,"Semester_id"=>$semeseterID);
                    $results = $this->General_model->fetchData("db_statistik.".$tablename,$conditions)->result();
                    
                    if(!empty($results)){
                        header("Content-type: application/vnd-ms-excel");
                        header("Content-Disposition: attachment; filename=edom-recap-".$prodiCode."-".$semeseterYear."-".$semesterType.".xls");
                        echo '<table border="1"><thead><tr><th>No</th><th>Intake</th><th>Code</th><th>Course</th><th>Group</th><th>Program Study</th><th>Lecturer</th><th>NIP</th><th>Question</th><th>Total Student</th><th>Rate</th></tr></thead><tbody>';
                        $no = 1;
                        foreach ($results as $v) {
                            echo '<tr height="50px"><td>'.$no.'</td>'.
                                 '<td>'.$v->Intake.'</td>'.
                                 '<td>'.$v->CourseCode.'</td>'.
                                 '<td>'.$v->CourseNameEng.'</td>'.
                                 '<td>'.$v->ClassGroup.'</td>'.
                                 '<td>'.$v->ProdiNameEng.'</td>'.
                                 '<td>'.$v->Lecturer.'</td>'.
                                 '<td>'.$v->LecturerNIP.'</td>'.
                                 '<td width="60%">'.$v->Question.'</td>'.
                                 '<td>'.$v->TotalStudent.'</td>'.
                                 '<td>'.round($v->Rate,2).'</td></tr>';
                            $no++;
                        }
                        echo '</tbody></table>';
                        // /$message = "Your request data has been completed.";
                    }else{
                        $message = "Your request data is unavailable.";
                        $this->session->set_flashdata("message",$message);
                        redirect(site_url('lpmi/lecturer-evaluation/download-result'));
                    }
                }else{
                    $message = "Your request data is unavailable. Database '".$tablename."' is unavailable.";
                    $this->session->set_flashdata("message",$message);
                    redirect(site_url('lpmi/lecturer-evaluation/download-result'));
                }
            }
            
        }
    }


}
