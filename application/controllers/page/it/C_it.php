<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_it extends It_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement(); 
        $this->load->model('m_api');
        $this->load->model('master/m_master');
    }

    
    public function dashboard()
    {
      $data['department'] = parent::__getDepartement();
      $content = $this->load->view('page/'.$data['department'].'/dashboard',$data,true);
      $this->temp($content);
    }

    public function rule_service_user()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/ruleservice/page',$this->data,true);
      $this->temp($content);
    }

    public function version_data(){
      
      $department = parent::__getDepartement();

      $data['ProdiArr'] = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
      $page = $this->load->view('page/'.$department.'/version/menu_version',$data,true);
      $this->temp($page);

      // ----old code ----
      //$department = parent::__getDepartement();
      //print_r($department);
      //$data['ProdiArr'] = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
      //$page = $this->load->view('page/'.$department.'/version/version_data',$data,true);
      //$this->temp($page);
     
    }

    public function redundancy_krs_online(){

        $department = parent::__getDepartement();
        $data = '';
        $page = $this->load->view('page/'.$department.'/academic/redundancy_krs_online',$data,true);
        $this->temp($page);

    }

    public function overwrite_course(){

        $department = parent::__getDepartement();
        $data = '';
        $page = $this->load->view('page/'.$department.'/academic/overwrite_course',$data,true);
        $this->temp($page);

    }

    public function agregator_menu(){

        $department = parent::__getDepartement();
        $data = '';
        $page = $this->load->view('page/'.$department.'/agregator/agregator_menu',$data,true);
        $this->temp($page);

    }

    public function version_menu(){
      $department = parent::__getDepartement();

//      $data['NIP']=$NIP;
      $data['NIP']= $this->session->userdata('NIP');
      $content = $this->load->view('page/'.$department.'/academic/academic_menu',$data,true);
      $this->temp($content);

    }

    public function loadupdategroup(){
      $dataNIP = $this->db->get_where('db_employees.files',array('NIP'=>$User))->result_array();
      
    }



    public function loadpageversiondetail(){
        $department = parent::__getDepartement();
        $data_arr = $this->getInputToken();
        $G_TypeFiles = $this->m_master->showData_array('db_employees.master_files');
        $data_arr['G_TypeFiles'] =  $G_TypeFiles;
        $this->load->view('page/'.$department.'/version/'.$data_arr['page'], $data_arr);
    }

    public function user_activity(){
        $department = parent::__getDepartement();
        $data['NIP']= $this->session->userdata('NIP');
        $content = $this->load->view('page/'.$department.'/user-activity/user_activity',$data,true);
        $this->temp($content);
    }

    public function seleksi_mahasiswa_asing(){

    }

    public function change_kode_formulir_online()
    {
      $this->data['NameMenu'] = 'Form Online';
      $t = $this->m_master->showData_array('db_admission.set_ta');
      $this->data['academic_year_admission'] = $t[0]['Ta'];
      $content = $this->load->view('page/'.$this->data['department'].'/admission/change_kode_formulir_online',$this->data,true);
      $this->temp($content);
    }

    public function submit_change_kode_formulir_online()
    {
      $Input = $this->getInputToken();
      $action = $Input['action'];
      $rs = ['msg' => '','Status' => 0];
      switch ($action) {
        case 'EditNumberFormulir':
        $FormulirCodeOnline = $Input['FormulirCodeOnline'];
          // set number formulir selected global dengan status = 0;
              $No_Ref_Selected = $Input['No_Ref_Selected'];
              $this->db->where('FormulirCodeGlobal',$No_Ref_Selected);
              $this->db->update('db_admission.formulir_number_global',array('Status' => 0));
          // set number formulir replacement global dengan status = 1
              $No_Ref_Replacement = $Input['No_Ref_Replacement'];
              $this->db->where('FormulirCodeGlobal',$No_Ref_Replacement);
              $this->db->update('db_admission.formulir_number_global',array('Status' => 1));

          // check change_set_ta 
              $change_set_ta = $Input['change_set_ta'];   
              if ($change_set_ta == 1) {
                $RegisterID = $Input['RegisterID'];
                $Year_Replacement = $Input['Year_Replacement'];
                $this->db->where('ID',$RegisterID);
                $this->db->update('db_admission.register',array('SetTa' => $Year_Replacement)); 

                // set Formulir Code Online dengan No_ref empty dan Status = 0
                $this->db->where('FormulirCode',$FormulirCodeOnline);
                $this->db->update('db_admission.formulir_number_online_m',array('No_Ref' => '','Status' => 0));

                // get number formulir online available
                $sql = 'select * from db_admission.formulir_number_online_m where Years = ? and Status = 0 limit 1';
                $query=$this->db->query($sql, array($Year_Replacement))->result_array();
                 $FormulirCodeOnline_new =  $query[0]['FormulirCode'];
                 $this->db->where('FormulirCode',$FormulirCodeOnline_new);
                 $this->db->update('db_admission.formulir_number_online_m',array('No_Ref' => $No_Ref_Replacement,'Status' => 1));

                // update di register verified
                 $this->db->where('FormulirCode',$FormulirCodeOnline);
                 $this->db->update('db_admission.register_verified',array('FormulirCode' => $FormulirCodeOnline_new));
              }
              else

              {
                // replace no_ref pada formulir code online dengan where FormulirCodeOnline dan set number formulir replacement
                    $FormulirCodeOnline = $Input['FormulirCodeOnline'];
                    $this->db->where('FormulirCode',$FormulirCodeOnline);
                    $this->db->update('db_admission.formulir_number_online_m',array('No_Ref' => $No_Ref_Replacement));
              }

              $rs['Status'] = 1;

              echo json_encode($rs);
          break;
        case 'exchangeNumberFormulir':
          $FormulirCodeOnline = $Input['FormulirCodeOnline'];
          $No_Ref_Selected = $Input['No_Ref_Selected'];
          $No_Ref_Replacement = $Input['No_Ref_Replacement'];
          $G_dt = $this->m_master->caribasedprimary('db_admission.formulir_number_online_m','No_ref',$No_Ref_Replacement);
          $FormulirCodeOnlineReplacement = $G_dt[0]['FormulirCode'];

          $this->db->where('FormulirCode',$FormulirCodeOnline);
          $this->db->update('db_admission.formulir_number_online_m',array('No_Ref' => $No_Ref_Replacement));

          $this->db->where('FormulirCode',$FormulirCodeOnlineReplacement);
          $this->db->update('db_admission.formulir_number_online_m',array('No_Ref' => $No_Ref_Selected));

           $rs['Status'] = 1;

          echo json_encode($rs);
          break;
        default:
          # code...
          break;
      }
    }

    public function roolback_to_be_mhs()
    {
      $this->data['DBTA'] = $this->m_master->ShowDBLikes();
      $content = $this->load->view('page/'.$this->data['department'].'/admission/roolback_to_be_mhs',$this->data,true);
      $this->temp($content);
    }

    public function console_developer()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/console-developer/console_developer',$this->data,true);
      $this->temp($content);
    }

}
