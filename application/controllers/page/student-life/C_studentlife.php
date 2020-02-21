<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_studentlife extends Student_Life {

    function __construct()
    {
        parent::__construct();
        $this->load->model('student-life/m_studentlife','stdlife');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_diploma_supplement($page){
        $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/diploma-supplement/menu_diploma_supplement',$data,true);
        $this->temp($content);
    }

    public function diploma_supplement()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/diploma-supplement/list_student',$data,true);
        $this->menu_diploma_supplement($page);
    }

    // ===== MASTER ====

    // ++ Master Company ++
    public function menu_master_company($page){
        $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/master/company/menu_master_company',$data,true);
        $this->temp($content);
    }

    public function master_company(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/master/company/master_company',$data,true);
        $this->menu_master_company($page);
    }

    public function add_master_company(){

        $ID = $this->input->get('id');

        $detailCompany = [];
        if($ID!='' && $ID!=null){
            $detailCompany = $this->stdlife->getDetailCompanyByID($ID);
        }


        $data['detailCompany'] = $detailCompany;

        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/master/company/add_master_company',$data,true);
        $this->menu_master_company($page);
    }





    // ===== SKPI ====
    private function menu_skpi($page){
        $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/skpi/menu_skpi',$data,true);
        $this->temp($content);
    }

    public function skpi(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/skpi/list_student',$data,true);
        $this->menu_skpi($page);
    }

    public function judiciums_monitoring(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/skpi/monitoring_yudisium',$data,true);
        $this->menu_skpi($page);
    }


    // ===== student_achievement ====


    private function menu_student_achievementc($page){
        $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/student-achievement/menu_student_achievement',$data,true);
        $this->temp($content);
    }

    public function student_achievement()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/student-achievement/student_achievement',$data,true);
        $this->menu_student_achievementc($page);
    }

    public function update_data_achievement()
    {

        $ID = $this->input->get('id');
        /*ADDED BY FEBRI @ NOV 2019 */
        $this->load->model("General_model");
        $data['categories'] = $this->General_model->fetchData("db_studentlife.categories_achievement",array("isActive"=>1))->result();
        /*END ADDED BY FEBRI @ NOV 2019 */
        $data['department'] = parent::__getDepartement();
        $data['ID'] = ($ID!='' && $ID!=null && isset($ID)) ? $ID : '';
        $page = $this->load->view('page/'.$data['department'].'/student-achievement/update_data_achievement',$data,true);
        $this->menu_student_achievementc($page);
    }

    // ===== tracert_alumni ====

    private function menu_stracert_alumni($page){
        $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/tracer-alumni/menu_stracer_alumni',$data,true);
        $this->temp($content);
    }

    public function list_alumni(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/tracer-alumni/list_alumni',$data,true);
        $this->menu_stracert_alumni($page);
    }

    public function form_accreditation(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/tracer-alumni/form_accreditation',$data,true);
        $this->menu_stracert_alumni($page);
    }

}
