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

    public function timetable(){

        $department = parent::__getDepartement();
        $data = '';
        $page = $this->load->view('page/'.$department.'/academic/timetable',$data,true);
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

    public function menu_user_activity($page){
        $department = parent::__getDepartement();
        $data['NIP']= $this->session->userdata('NIP');
        $data['page']= $page;
        $content = $this->load->view('page/'.$department.'/user-activity/menu_user_activity',$data,true);
        $this->temp($content);
    }

    public function user_activity(){
        $department = parent::__getDepartement();
        $data['NIP']= $this->session->userdata('NIP');
        $page = $this->load->view('page/'.$department.'/user-activity/user_activity',$data,true);
        $this->menu_user_activity($page);
    }

    public function user_activity_student(){
        $department = parent::__getDepartement();
        $data['NIP']= $this->session->userdata('NIP');
        $page = $this->load->view('page/'.$department.'/user-activity/user_activity_student',$data,true);
        $this->menu_user_activity($page);
    }

    public function user_activity_lecturer(){
        $department = parent::__getDepartement();
        $data['NIP']= $this->session->userdata('NIP');
        $page = $this->load->view('page/'.$department.'/user-activity/user_activity_lecturer',$data,true);
        $this->menu_user_activity($page);
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

    public function menu_developer($page){
        $data['page'] = $page;
        $content = $this->load->view('page/it/console-developer/menu_developer',$data,true);
        $this->temp($content);
    }

    public function console_developer()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/console-developer/console_developer',$this->data,true);
      $this->menu_developer($content);
    }

    public function routes()
    {
      $this->data['input_routes'] = $this->load->view('page/'.$this->data['department'].'/console-developer/input_routes',$this->data,true);
      $this->data['table_routes_local'] = $this->load->view('page/'.$this->data['department'].'/console-developer/table_routes_local',$this->data,true);
      $this->data['table_routes_live'] = $this->load->view('page/'.$this->data['department'].'/console-developer/table_routes_live',$this->data,true);
      $content = $this->load->view('page/'.$this->data['department'].'/console-developer/routes',$this->data,true);
      $this->menu_developer($content);
    }

    public function submit_routes()
    {
      header('Access-Control-Allow-Origin: *');
      header('Content-Type: application/json');
      error_reporting(0);
      $Input = $this->getInputToken();
      $action = $Input['action'];
      if ($action == 'add') {
        /*
          hanya untuk local
        */
          try {
            $dataSave = $Input['data'];
            $dataSave = json_decode(json_encode($dataSave),true);
            $dataSave['Status'] = $Input['server'];
            $dataSave['Updated_at'] = date('Y-m-d H:i:s');
            $dataSave['Updated_by'] = $this->session->userdata('NIP');

            $this->db->db_debug = FALSE;
            if (!$this->db->insert('db_it.routes',$dataSave)) {
              echo json_encode('Duplicate URL');
            }
            else
            {
              echo json_encode(1);
            }
            
          } catch (Exception $e) {
            echo json_encode('something went wrong, caught yah! n');
          }
          
      }
      elseif ($action == 'edit') {
        /*
            local dan live
        */
        try {
          $ID = $Input['ID'];
          // deklarasi database
              $dbselected = ($Input['server'] == 'live') ? $this->load->database('server_live', TRUE) : $this->db;

          $dataSave = $Input['data'];
          $dataSave = json_decode(json_encode($dataSave),true);
          $dataSave['Status'] = $Input['server'];
          $dataSave['Updated_at'] = date('Y-m-d H:i:s');
          $dataSave['Updated_by'] = $this->session->userdata('NIP');

          $dbselected->db_debug = FALSE;

          $dbselected->where('ID',$ID);
          if (!$dbselected->update('db_it.routes',$dataSave)) {
           echo json_encode('Duplicate URL');
          }
          else
          {
            echo json_encode(1);
          }
        } catch (Exception $e) {
          echo json_encode('something went wrong, caught yah! n');
        }
          
      }
      elseif ($action =='delete') {
         /*
             local dan live
         */
         $ID = $Input['ID'];
         // deklarasi database
             $dbselected = ($Input['server'] == 'live') ? $this->load->database('server_live', TRUE) : $this->db;
         $dbselected->where('ID',$ID);
         $dbselected->delete('db_it.routes');
         echo json_encode(1);  
      }
      elseif ($action =='read') {
         /*
             local dan live
         */
        $dbselected = ($Input['server'] == 'live') ? $this->load->database('server_live', TRUE) : $this->db;
        $sql = 'select a.*,b.Name as NameEmp,dp.Name2 as NameDepartment from db_it.routes as a
                join db_employees.employees as b on a.Updated_by = b.NIP
                join (
                    select CONCAT("AC.",ID) as ID, CONCAT("Prodi ",Name) as Name1, CONCAT("Study ",NameEng)  as Name2 from db_academic.program_study where Status = 1
                    UNION
                    select CONCAT("NA.",ID) as ID, Description as Name1, Division as Name2 from db_employees.division
                    UNION
                    select CONCAT("FT.",ID) as ID, CONCAT("Facultas ",Name) as Name1, CONCAT("Faculty ",NameEng) as Name2 from db_academic.faculty where StBudgeting = 1
  
                ) as dp on a.Department = dp.ID
                where a.Status = "'.$Input['server'].'"
                ';
        $query = $dbselected->query($sql)->result_array();
        $data = array();
        for ($i=0; $i <count($query) ; $i++) { 
            $nestedData = array();
            $row = $query[$i];
            $nestedData[] = $i+1;
            $nestedData[] = $row['Slug'];
            $nestedData[] = $row['Controller'];
            $nestedData[] = $row['Type'];
            $nestedData[] = $row['NameDepartment'];
            $nestedData[] = $row['NameEmp'];
            $nestedData[] = $row['Updated_at'];
            $nestedData[] = $row['Updated_by'];
            $nestedData[] = $row['Status'];
            $nestedData[] = $row['ID'];
            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data
        );
        echo json_encode($json_data);
      }
      elseif ($action == 'Migrate') {
         $data = $Input['data'];
         $data = json_decode(json_encode($data),true);
         $dbselected =  $this->load->database('server_live', TRUE);
         $dbselected->db_debug = FALSE;
         $msg = 1;
         try {
           for ($i=0; $i <count($data) ; $i++) { 
             // get data
            $ID = $data[$i];
            $G_dt = $this->m_master->caribasedprimary('db_it.routes','ID',$ID);
            if (count($G_dt) > 0) {
              $dataSave = [];
              $r = $G_dt[0];
              foreach ($r as $key => $value) {
                if ($key != 'ID') {
                  if ($key == 'Status') {
                    $dataSave[$key] = 'live';
                  }
                  else
                  {
                    $dataSave[$key] = $value;
                  }
                  
                }
              }
              if (!$dbselected->insert('db_it.routes',$dataSave)) {
               $msg = 'Duplicate URL with '.$dataSave['Slug'];
               break;
              }
              else
              {
                // update
                $this->db->where('ID',$ID);
                $this->db->update('db_it.routes',$dataSave);
              }

            }
           }
         } catch (Exception $e) {
           $msg = 'Something wrong';
         }
         
         echo json_encode($msg); 
      }
      elseif ($action == 'MigrateLive') {

        if ($_SERVER['SERVER_NAME'] != 'pcam.podomorouniversity.ac.id') {
          $data = $Input['data'];
          $data = json_decode(json_encode($data),true);
          $dbselected =  $this->load->database('server_live', TRUE);

          // Truncate data local
          $sql = 'TRUNCATE TABLE db_it.routes';
          $this->db->query($sql,array());

          for ($i=0; $i <count($data) ; $i++) { 
            // get data
           $ID = $data[$i];
           $G_dt = $dbselected->query('select * from db_it.routes where ID = '.$ID.' ')->result_array();
           if (count($G_dt) > 0) {
             $dataSave = [];
             $r = $G_dt[0];
             foreach ($r as $key => $value) {
               if ($key != 'ID') {
                 if ($key == 'Status') {
                   $dataSave[$key] = 'live';
                 }
                 else
                 {
                   $dataSave[$key] = $value;
                 }
                 
               }
             }
             $this->db->insert('db_it.routes',$dataSave);
           }
          }
        }

         echo json_encode(1); 
      } 
    }

}
