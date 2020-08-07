<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_studentlife extends Student_Life {

    private $varClass = [];

    function __construct()
    {
        parent::__construct();
        $this->load->model('student-life/m_studentlife','stdlife');
        $this->load->model('student-life/m_alumni');
    }

    private function __setting_rest_alumni(){
        $G_setting = $this->m_master->showData_array('db_alumni.rest_setting');
        $this->varClass = $G_setting[0];
        $this->varClass['customPost'] = [
            'get' => '?apikey='.$this->varClass['Apikey'],
            'header' => [
                'Hjwtkey' => $this->varClass['Hjwtkey'],
            ],
            
        ];
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

    public function forum_alumni(){
        $this->__setting_rest_alumni();
        $this->varClass['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$this->varClass['department'].'/tracer-alumni/forum_alumni',$this->varClass,true);
        $this->menu_stracert_alumni($page);
    }

    public function forum_alumni_detail($token){
        $this->__setting_rest_alumni();
        $this->varClass['ForumID'] = $this->decodeToken($token);
        $this->varClass['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$this->varClass['department'].'/tracer-alumni/forum_alumni_detail',$this->varClass,true);
        $this->menu_stracert_alumni($page);
    }

    public function testimony(){
        $this->__setting_rest_alumni();
        $this->varClass['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$this->varClass['department'].'/tracer-alumni/testimony',$this->varClass,true);
        $this->menu_stracert_alumni($page);
    }


    // ------------Menu Alumni Live---------//
    // Yamin
    // 03/08/2020

    public function menu_content(){
        // $data['page'] = $page;
        $data['department'] = parent::__getDepartement();
        // $data['category'] = $this->m_alumni->get_category();
        $content = $this->load->view('page/'.$data['department'].'/portal-alumni/menu_content',$data,true);
        $this->temp($content);
    }

    ## table load /list

    public function ajax_list()
    {
        $type=$this->uri->segment(2);
        $list = $this->m_alumni->get_datatables($type);
        $data = array();        
        $no = $_POST['start'];
       
        foreach ($list as $m) {
            $no++;
            $row = array();
            $row[] = $m->TitleContent;
            
            $row[] = $m->Description;
            $row[] = $m->Status;
            $row[] = $m->UpdatedAt; 
            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_alumni('."'".$m->ID_Content."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_alumni('."'".$m->ID_Content."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
 
            $data[] = $row;
        }
       
        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->m_alumni->count_all(),
                "recordsFiltered" => $this->m_alumni->count_filtered(),
                "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->m_alumni->get_by_id($id);
        // print_r($data);die();
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $this->_validate();
        $data = array(
                // 'IDType' => $this->input->post('type'),
                'TitleContent' => $this->input->post('title'),
                'IDSubCat' => $this->input->post('idsubcategory'),
                'Description' => $this->input->post('description'),
                'Meta_description' => $this->input->post('meta_des'),
                'Meta_keywords' => $this->input->post('meta_key'),
                'Lang' => $this->input->post('lang'),
                'AddDate' => $this->input->post('date'),
                'Status' => $this->input->post('status'),
                'CreateAt' => date('Y-m-d H:i:s'),
                'CreateBy' => $this->session->userdata('NIP'),
            );

        if(!empty($_FILES['photo']['name']))
        {
            $upload = $this->_do_upload();
            $data['File'] = $upload;
        }
        $type = $this->input->post('type');
        $insert = $this->m_alumni->save($data,$type);
        echo json_encode(array("status" => TRUE));
    }

    // Content 
    public function ajax_update()
    {
        $this->_validate();
        $data = array(
                'TitleContent' => $this->input->post('title'),
                'IDSubCat' => $this->input->post('category'),
                'Description' => $this->input->post('description'),
                'Meta_description' => $this->input->post('meta_des'),
                'Meta_keywords' => $this->input->post('meta_key'),
                // 'Lang' => $this->input->post('lang'),
                'AddDate' => $this->input->post('date'),
                'Status' => $this->input->post('status'),
                'UpdatedAt' => date('Y-m-d H:i:s'),
                'UpdatedBy' => $this->session->userdata('NIP'),
            );

        if(!empty($_FILES['photo']['name']))
        {
            $upload = $this->_do_upload();
             
            //delete file
            $alumni = $this->m_alumni->get_by_id($this->input->post('id'));
            if(file_exists('./uploads/alumni/'.$alumni->File) && $alumni->File)
                unlink('./uploads/alumni/'.$alumni->File);
 
            $data['File'] = $upload;
        }

        $this->m_alumni->update(array('ID_Content' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_delete($id)
    {
        //delete file
        $alumni = $this->m_alumni->get_by_id($id);
        if(file_exists('./uploads/alumni/'.$alumni->File) && $alumni->File)
            unlink('./uploads/alumni/'.$alumni->File);

        $this->m_alumni->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _do_upload()
    {
        $config['upload_path']          = './uploads/alumni';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|pdf';
        $config['max_size']             = 2048000; //set max size allowed in Kilobyte 2mb
        $config['max_width']            = 1600; // set max width image allowed
        $config['max_height']           = 600; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name
 
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
            $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        return $this->upload->data('file_name');
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('title') == '')
        {
            $data['inputerror'][] = 'title';
            $data['error_string'][] = 'Title is required';
            $data['status'] = FALSE;
        }
 
        // if($this->input->post('description') == '')
        // {
        //     $data['inputerror'][] = 'description';
        //     $data['error_string'][] = 'Description is required';
        //     $data['status'] = FALSE;
        // }
 
        if($this->input->post('status') == '')
        {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Status is required';
            $data['status'] = FALSE;
        }
 
        // if($this->input->post('lang') == '')
        // {
        //     $data['inputerror'][] = 'lang';
        //     $data['error_string'][] = 'Please select language';
        //     $data['status'] = FALSE;
        // }
        
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }



}
