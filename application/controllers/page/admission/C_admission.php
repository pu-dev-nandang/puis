<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_admission extends Admission_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('admission/m_admission');
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement();
        $this->load->model('m_api');
        $this->data['NameMenu'] = $this->GlobalData['NameMenu'];
        // get academic year admission
            $t = $this->m_master->showData_array('db_admission.set_ta');
            $this->data['academic_year_admission'] = $t[0]['Ta'];
    }

    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $content = "test";
        $this->temp($content);

    }

    public function verifikasi_dokumen_calon_mahasiswa()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/verifikasi_dokumen_calon_mahasiswa',$this->data,true);
        $this->temp($content);
    }

    public function pagination_calon_mahasiswa($page= null)
    {
        $input =  $this->getInputToken();
        //$tahun = $input['selectTahun'];
        $nama = $input['NamaCandidate'];
        $status = $input['selectStatus'];
        $FormulirCode = $input['FormulirCode'];

        $this->load->library('pagination');
        // $config = $this->config_pagination_default_ajax($this->m_admission->CountSelectDataCalonMahasiswa($tahun,$nama,$status,$FormulirCode),2,6);
        $config = $this->config_pagination_default_ajax($this->m_admission->CountSelectDataCalonMahasiswa($nama,$status,$FormulirCode),2,6);

        $this->pagination->initialize($config);
        $page = $this->uri->segment(6);
        $start = ($page - 1) * $config["per_page"];
        // $this->data['datadb'] = $this->m_admission->selectDataCalonMahasiswa($config["per_page"], $start,$tahun,$nama,$status,$FormulirCode);
        $this->data['datadb'] = $this->m_admission->selectDataCalonMahasiswa($config["per_page"], $start,$nama,$status,$FormulirCode);
       $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/page_verifikasi_dokumen',$this->data,true);

        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'register_document_table'   => $content,
        );
        echo json_encode($output);

    }

    public function proses_document()
    {
    $max_execution_time = 1000;
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', $max_execution_time); //60 seconds = 1 minutes
      $input = $this->getInputToken();
      $action = $input['action'];
      $data = $input['data_passing'];
      $data_arr = explode(",", $data);
      $Status = "Reject";
      if ($action ==  'approve') {
        $Status = "Done";
      }
      else if($action ==  'reject')
      {
        $Status = "Reject";
      }
      else
      {
        $Status = "Progress Checking";
      }

      $this->m_admission->updateStatusVeriDokumen($data_arr,$Status);
      $temp = explode(";", $data_arr[0]);
      $ID_register_document = $temp[0];
      if ($ID_register_document == 'nothing') {
          $temp = explode(";", $data_arr[1]);
          $ID_register_document = $temp[0];
      }
      $this->m_admission->data['ID_register_document'] = $ID_register_document;
      $keyURL = $this->m_admission->getKeylinkURLFormulirRegistration();
      $keyURL = $this->m_admission->data['callback'];

      // send email
      if ($Status == "Reject") {
          if($_SERVER['SERVER_NAME']!='localhost') {
            $text = 'Dear Candidate,<br><br>
                        You have document not approved yet, Please send your valid document.<br>
                        '.url_registration."formulir-registration/".$keyURL['url'].'
                    ';
            $to = $keyURL['email'];
            $subject = "Podomoro University Document Upload";
            // $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
         }
      }
      else if($Status == 'Done')
      {
        // check status if all done
        $check = $this->m_admission->checkAllstatusDoneVeriDoc($ID_register_document);
        if ($check) {
            $text = 'Dear Candidate,<br><br>
                        You have finished your all required document.<br>
                        '.url_registration."formulir-registration/".$keyURL['url'].'
                    ';
            $to = $keyURL['email'];
            $subject = "Podomoro University Document Upload";
            // $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
        }

      }

    }

    public function distribusi_formulir_offline()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/distribusi_formulir/formulir_offline',$this->data,true);
      $this->temp($content);
    }

    public function distribusi_formulir_online()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/distribusi_formulir/formulir_online',$this->data,true);
      $this->temp($content);
    }

    public function pagination_formulir_online($page= null)
    {
       $input =  $this->getInputToken();
       // print_r($input);
       $tahun = $input['selectTahun'];
       $NomorFormulir = $input['NomorFormulir'];
       $status = $input['selectStatus'];

       $this->load->library('pagination');
       $config = $this->config_pagination_default_ajax($this->m_admission->totalDataFormulir_online(),15,5);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(5);
       $start = ($page - 1) * $config["per_page"];
       $this->data['datadb'] = $this->m_admission->selectDataDitribusiFormulirOnline($config["per_page"], $start,$tahun,$NomorFormulir,$status);
      // $content = $this->load->view('page/'.$this->data['department'].'/distribusi_formulir/tabel_formulir_online',$this->data,true);
      $content = $this->load->view('page/'.'admission'.'/distribusi_formulir/tabel_formulir_online',$this->data,true);

       $output = array(
       'pagination_link'  => $this->pagination->create_links(),
       'tabel_formulir_online'   => $content,
       );
       echo json_encode($output);
    }

    public function pagination_formulir_offline($page= null)
    {
       $input =  $this->getInputToken();
       // print_r($input);
       $tahun = $input['selectTahun'];
       $NomorFormulir = $input['NomorFormulir'];
       $NomorFormulirRef = $input['NomorFormulirRef'];
       $NamaStaffAdmisi = $input['NamaStaffAdmisi'];
       $status = $input['selectStatus'];
       $statusJual = $input['selectStatusJual'];
       if (isset($input['action'])) {
         $this->data['actiontbl'] = $input['action'];
       }
       else
       {
        $this->data['actiontbl'] = 1;
       }

       $this->load->library('pagination');
       $config = $this->config_pagination_default_ajax($this->m_admission->totalDataFormulir_offline3($tahun,$NomorFormulir,$NamaStaffAdmisi,$status,$statusJual,$NomorFormulirRef),10,5);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(5);
       $start = ($page - 1) * $config["per_page"];
       $this->data['No'] = $start + 1;
       $this->data['datadb'] = $this->m_admission->selectDataDitribusiFormulirOffline($config["per_page"], $start,$tahun,$NomorFormulir,$NamaStaffAdmisi,$status,$statusJual,$NomorFormulirRef);
      $content = $this->load->view('page/'.'admission'.'/distribusi_formulir/tabel_formulir_offline',$this->data,true);

       $output = array(
       'pagination_link'  => $this->pagination->create_links(),
       'tabel_formulir_offline'   => $content,
       );
       echo json_encode($output);
    }

    public function submit_sellout_formulir_offline()
    {
      $input = $this->getInputToken();
      $action = $input['action'];
      $data = $input['data_passing'];
      $data_arr = explode(",", $data);
      $this->m_admission->updateSelloutFormulir($data_arr);
    }

    public function set_jadwal_ujian()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/set_jadwal_ujian',$this->data,true);
      $this->temp($content);
    }

    public function set_jadwal_ujian_load_table()
    {
       $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/set_jadwal_ujian_load_table',$this->data,true);
       echo json_encode($content);
    }

    public function set_jadwal_ujian_load_table_getJsonApi()
    {
      $generate = $this->m_admission->getJadwalUjian();
      return print_r(json_encode($generate));
    }

    public function set_jadwal_ujian_save()
    {
      $max_execution_time = 1000;
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', $max_execution_time); //60
      $result = array('msg' => '');
      $input = $this->getInputToken();
      $ID_ProgramStudy = $input['program_study'];
      $DateTimeTest = $input['datetime_ujian'];
      $Lokasi = $input['Lokasi'];
      // $check = $this->m_admission->checKjadwalMasihActive($ID_ProgramStudy,$DateTimeTest);

      // save data di register_jadwal_ujian dan return array ID nya
      // get Data ID ujian_perprody
      $arr_ID_ujian_per_prody = $this->m_admission->get_arr_ID_ujian_per_prody($ID_ProgramStudy);
      $result['msg'] = $arr_ID_ujian_per_prody['result'];
      if ($result['msg'] == '') {
        $proses = $this->m_admission->saveDataJadwalUjian_returnArr($arr_ID_ujian_per_prody,$DateTimeTest,$Lokasi);

        // get ID formulir berdasarkan ID_ProgramStudy
        $arr_ID_register_formulir = $this->m_admission->getID_register_formulir_programStudy_arr($proses);
        if (count($arr_ID_register_formulir) > 0) {
          // insert data di register_formulir_jadwal_ujian
          // gunakan try catch untuk continue data karena unique
          $this->m_admission->saveDataregister_formulir_jadwal_ujian($arr_ID_register_formulir);

        }
      }

      return print_r(json_encode($result));

    }

    public function daftar_jadwal_ujian()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/daftar_jadwal_ujian',$this->data,true);
      $this->temp($content);
    }

    public function daftar_jadwal_ujian_load_data_now()
    {
      $generate = $this->m_admission->daftar_jadwal_ujian_load_data_now();
      return print_r(json_encode($generate));
    }

    public function daftar_jadwal_ujian_load_data_paging($page= null)
    {
        $input =  $this->getInputToken();
        $Nama = $input['Nama'];
        $FormulirCode = $input['FormulirCode'];
        $this->load->library('pagination');
        $config = $this->config_pagination_default_ajax(1000,5,6);
        $this->pagination->initialize($config);
        $page = $this->uri->segment(6);
        $start = ($page - 1) * $config["per_page"];
        $this->data['datadb'] = $this->m_admission->daftar_jadwal_ujian_load_data_paging($config["per_page"], $start,$Nama,$FormulirCode);
       $this->data['no'] = $start + 1;
       $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/daftar_jadwal_ujian_load_data_paging',$this->data,true);

        $output = array(
        'pagination_link'  => $this->pagination->create_links(),
        'loadtable'   => $content,
        );
        echo json_encode($output);

    }

    public function set_nilai_ujian()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/set_nilai_ujian',$this->data,true);
      $this->temp($content);
    }

    public function set_nilai_ujian_load_data_paging($page = null)
    {
       $input =  $this->getInputToken();
       $Nama = $input['selectPrody'];
       $selectPrody = $input['selectPrody'];

       $this->load->library('pagination');
       $config = $this->config_pagination_default_ajax($this->m_admission->count_daftar_set_nilai_ujian_load_data_paging($selectPrody),5,6);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(6);
       $start = ($page - 1) * $config["per_page"];
       $this->data['datadb'] = $this->m_admission->daftar_set_nilai_ujian_load_data_paging($config["per_page"], $start,$selectPrody);
       $this->data['mataujian'] = $this->m_admission->select_mataUjian($selectPrody);
       $this->data['grade'] = json_encode($this->m_admission->showData('db_academic.grade'));
      $this->data['no'] = $start + 1;
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/daftar_nilai_ujian_load_data_paging',$this->data,true);

       $output = array(
       'pagination_link'  => $this->pagination->create_links(),
       'loadtable'   => $content,
       );
       echo json_encode($output);
    }

    public function set_nilai_ujian_save()
    {
      $input = $this->getInputToken();
      $this->m_admission->saveDataNilaiUjian($input);
      echo json_encode( array('msg' => 'Data berhasil disimpan') );
    }

    public function formulir_offline_sale_save()
    {
      $rs = array('msg'=> '','Status'=> 1);
      $input = $this->getInputToken();
      switch ($input['Action']) {
          case 'add':
              // check email already exist or not
              $B_email = $this->m_admission->alreadyExistingEmail($input['email']);
              // check No_Ref is available
              $G_formulirGlobal = $this->m_master->caribasedprimary('db_admission.formulir_number_global','FormulirCodeGlobal',$input['No_Ref']);
              if ($G_formulirGlobal[0]['Status'] == 0) {
                if ($B_email) {
                  $this->m_admission->inserData_formulir_offline_sale_save($input);
                }
                else
                {
                  $rs['Status'] = 0;
                  $rs['msg'] = 'Email already exist';
                }
              }
              else
              {
                $rs['Status'] = 0;
                $rs['msg'] = 'No_Ref is used, Please reload your browser';
              }

              break;
          case 'edit':
            /*
              Note :
              Tidak boleh ganti No_Ref
            */
              // get old data first
              $G_dt = $this->m_master->caribasedprimary('db_admission.sale_formulir_offline','ID',$input['CDID']);
              // get Email
              $Email_ =  $G_dt[0]['Email'];

              $B_email = $this->m_admission->alreadyExistingEmail($input['email']);
              // $G_formulirGlobal = $this->m_master->caribasedprimary('db_admission.formulir_number_global','FormulirCodeGlobal',$input['No_Ref']);
              // if ($G_formulirGlobal[0]['Status'] == 0) {
              //   if ($B_email) {
              //     $this->m_admission->editData_formulir_offline_sale_save($input);
              //   }
              //   else
              //   {
              //     $rs['Status'] = 0;
              //     $rs['msg'] = 'Email already exist';
              //   }
              // }
              // else
              // {
              //   $rs['Status'] = 0;
              //   $rs['msg'] = 'No_Ref is used, Please reload your browser';
              // }
              if ($B_email || $Email_ == $input['email']) {
                $this->m_admission->editData_formulir_offline_sale_save($input);
              }
              else
              {
                $rs['Status'] = 0;
                $rs['msg'] = 'Email already exist';
              }
              break;
          case 'delete':
              $query = $this->m_master->caribasedprimary('db_admission.sale_formulir_offline','ID',$input['CDID']);
              $FormulirCode = $query[0]['FormulirCodeOffline'];
              $this->m_master->updateStatusJual2($FormulirCode);
              $this->m_master->delete_id_table($input['CDID'],'sale_formulir_offline');
              // print_r($FormulirCode);
              break;
      }

      echo json_encode($rs);
    }

    public function formulir_offline_salect_PIC()
    {
      header('Access-Control-Allow-Origin: *');
      header('Content-Type: application/json');
      $input = $this->getInputToken();
      $generate = $this->m_admission->formulir_offline_salect_PIC_renew($input);
      return print_r(json_encode($generate));
    }

    public function set_ujian()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/set_ujian',$this->data,true);
      $this->temp($content);
    }

    public function loadData_calon_mahasiswa()
    {
       $input =  $this->getInputToken();
       $Nama = $input['Nama'];
       $selectProgramStudy = $input['selectProgramStudy'];
       $Sekolah = $input['Sekolah'];
       $No_Formulir = $input['No_Formulir'];
       $this->load->library('pagination');
       $config = $this->config_pagination_default_ajax($this->m_admission->count_loadData_calon_mahasiswa($Nama,$selectProgramStudy,$Sekolah,$No_Formulir),25,4);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(4);
       $start = ($page - 1) * $config["per_page"];
       $this->data['datadb'] = $this->m_admission->loadData_calon_mahasiswa($config["per_page"], $start,$Nama,$selectProgramStudy,$Sekolah,$No_Formulir);
      $this->data['no'] = $start + 1;
      $this->data['chkActive'] = 1;
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/table_calon_mahasiswa',$this->data,true);

       $output = array(
       'pagination_link'  => $this->pagination->create_links(),
       'loadtable'   => $content,
       );
       echo json_encode($output);
    }

    public function submit_ikut_ujian()
    {
      $input = $this->getInputToken();
      $this->m_admission->submit_ikut_ujian($input['chkValue']);
    }

    public function input_nilai_rapor()
    {
      $this->data['url_registration'] = url_registration;
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/set_nilai_rapor',$this->data,true);
      $this->temp($content);
    }

    public function set_nilai_rapor_load_data_paging($page = null)
    {
       $input =  $this->getInputToken();
       $Nama = $input['selectPrody'];
       $selectPrody = $input['selectPrody'];
       $FormulirCode = $input['FormulirCode'];

       $this->load->library('pagination');
       $config = $this->config_pagination_default_ajax($this->m_admission->count_daftar_set_nilai_rapor_load_data_paging($selectPrody,$FormulirCode),3,5);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(5);
       $start = ($page - 1) * $config["per_page"];
       $datadb = $this->m_admission->daftar_set_nilai_rapor_load_data_paging($config["per_page"], $start,$selectPrody,$FormulirCode);
       $this->data['datadb'] = $datadb['query'];
       $this->data['mataujian'] = $this->m_admission->select_mataUjian($datadb['Prodi']);
       $this->data['grade'] = json_encode($this->m_admission->showData('db_admission.grade'));
      $this->data['no'] = $start + 1;

      // get data nilai to finance
      $this->data['G_Jurusan'] = $this->m_master->showData_array('db_admission.m_criteria_rapor_fin');
      $this->data['G_Jurusan_sub'] = $this->m_master->showData_array('db_admission.m_sub_criteria_rfin');

      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/daftar_nilai_rapor_load_data_paging',$this->data,true);

       $output = array(
       'pagination_link'  => $this->pagination->create_links(),
       'loadtable'   => $content,
       );
       echo json_encode($output);
    }

    public function set_nilai_rapor_save()
    {
      $input = $this->getInputToken();
      $this->m_admission->saveDataNilaRapor($input);
      $this->m_admission->saveDataRangkingRapor($input);
      $this->m_admission->saveDataRaporToFin($input['arr_fin']);
      echo json_encode( array('msg' => 'Data berhasil disimpan') );
    }

    public function cancel_nilai_lapor()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/cancel_nilai_rapor',$this->data,true);
      $this->temp($content);
    }

    public function loaddata_nilai_calon_mahasiswa()
    {
       $input =  $this->getInputToken();
       $Nama = $input['Nama'];
       $FormulirCode = $input['FormulirCode'];
       $selectProgramStudy = $input['selectProgramStudy'];
       $Sekolah = $input['Sekolah'];
       $this->load->library('pagination');
       $config = $this->config_pagination_default_ajax($this->m_admission->count_loadData_calon_mahasiswa_created($Nama,$selectProgramStudy,$Sekolah,$FormulirCode),3,4);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(4);
       $start = ($page - 1) * $config["per_page"];
       $this->data['url_registration'] = url_registration;
       $this->data['datadb'] = $this->m_admission->loadData_calon_mahasiswa_created($config["per_page"], $start,$Nama,$selectProgramStudy,$Sekolah,$FormulirCode);
       $this->data['mataujian'] = $this->m_admission->select_mataUjian($selectProgramStudy);
       $this->data['grade'] = $this->m_admission->showData('db_admission.grade');
      $this->data['no'] = $start + 1;
      $this->data['chkActive'] = 1;
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/table_nilai_calon_mahasiswa',$this->data,true);

       $output = array(
       'pagination_link'  => $this->pagination->create_links(),
       'loadtable'   => $content,
       );
       echo json_encode($output);
    }

    public function submit_cancel_nilai_rapor()
    {
      $input = $this->getInputToken();
      $this->m_admission->submit_cancel_nilai_rapor($input['chkValue']);
      $this->m_admission->submit_cancel_nilai_rapor_rangking($input['chkValue']);
      $this->m_admission->submit_cancel_nilai_rapor_finance($input['chkValue']);
      echo json_encode( array('msg' => 'Data berhasil disimpan') );
    }

    public function set_tuition_fee()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/set_tuition_fee',$this->data,true);
      $this->temp($content);
    }

    public function set_tuition_fee_input($page = null)
    {
      $input = $this->getInputToken();
      $FormulirCode = $input['FormulirCode'];
      $this->load->library('pagination');
      $page_Count = 5;
      $countData = $this->m_admission->count_getDataCalonMhsTuitionFee($FormulirCode);
      $config = $this->config_pagination_default_ajax($countData,$page_Count,5);
      $this->pagination->initialize($config);
      $page = $this->uri->segment(5);
      $start = ($page - 1) * $config["per_page"];

      $this->data['payment_type'] = json_encode($this->m_master->caribasedprimary('db_finance.payment_type','Type','0'));
      $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsTuitionFee($config["per_page"], $start,$FormulirCode));
      $this->data['no'] = $start + 1;
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/page_tuition_fee_input',$this->data,true);

      $output = array(
      'pagination_link'  => $this->pagination->create_links(),
      'loadtable'   => $content,
      );
      echo json_encode($output);

    }

    public function set_tuition_fee_save()
    {
      $input = $this->getInputToken();
      $this->m_admission->set_tuition_fee_save($input);

      $text = 'Dear Team,<br><br>
                  Tuition Fee calon mahasiswa telah diset oleh pihak admisi.<br>
                  Silahkan cek pada portal Anda.
              ';
      $query = $this->m_master->caribasedprimary('db_admission.email_to','Function','Finance');
      $to = $query[0]['EmailTo'];
      $subject = "Podomoro University Notify";
      $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

    }

    public function set_tuition_fee_delete($page = null)
    {
      $input = $this->getInputToken();
      $FormulirCode = $input['FormulirCode'];

      $this->load->library('pagination');
      $page_Count = 5;
      $countData = $this->m_admission->count_getDataCalonMhsTuitionFee_delete($FormulirCode);
      $config = $this->config_pagination_default_ajax($countData,$page_Count,5);
      $this->pagination->initialize($config);
      $page = $this->uri->segment(5);
      $start = ($page - 1) * $config["per_page"];

      // $this->data['payment_type'] = json_encode($this->m_master->showData_array('db_finance.payment_type'));
      $this->data['payment_type'] = json_encode($this->m_master->caribasedprimary('db_finance.payment_type','Type','0'));
      $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsTuitionFee_delete($config["per_page"], $start,$FormulirCode));
      $this->data['no'] = $start + 1;
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/page_tuition_fee_delete',$this->data,true);

      $output = array(
      'pagination_link'  => $this->pagination->create_links(),
      'loadtable'   => $content,
      );
      echo json_encode($output);
    }

    public function set_tuition_fee_delete_data()
    {
      $input = $this->getInputToken();
      $this->m_admission->set_tuition_fee_delete_data($input,'Created');
    }

    public function set_tuition_fee_approved($page = null)
    {
      $input = $this->getInputToken();
      $FormulirCode = $input['FormulirCode'];

      $this->load->library('pagination');
      $config = $this->config_pagination_default_ajax($this->m_admission->count_getDataCalonMhsTuitionFee_delete($FormulirCode,'p.Status = "Approved"'),15,5);
      $this->pagination->initialize($config);
      $page = $this->uri->segment(5);
      $start = ($page - 1) * $config["per_page"];

      // $this->data['payment_type'] = json_encode($this->m_master->showData_array('db_finance.payment_type'));
      $this->data['payment_type'] = json_encode($this->m_master->caribasedprimary('db_finance.payment_type','Type','0'));
      $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsTuitionFee_approved($config["per_page"], $start,$FormulirCode,'p.Status = "Approved"'));
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/page_tuition_fee_approved',$this->data,true);
      $output = array(
      'pagination_link'  => $this->pagination->create_links(),
      'loadtable'   => $content,
      );
      echo json_encode($output);
    }

    public function cicilan()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/cicilan',$this->data,true);
      $this->temp($content);
    }

    public function cicilan_data($page = null)
    {
      $this->load->library('pagination');
      $config = $this->config_pagination_default_ajax(1000,25,4);
      $this->pagination->initialize($config);
      $page = $this->uri->segment(4);
      $start = ($page - 1) * $config["per_page"];

      $this->data['max_cicilan'] = json_encode($this->m_master->showData_array('db_admission.cfg_cicilan'));
      $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsCicilan($config["per_page"], $start));
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/page_cicilan_list',$this->data,true);
      $output = array(
      'pagination_link'  => $this->pagination->create_links(),
      'loadtable'   => $content,
      );
      echo json_encode($output);
    }

    public function submit_edit_deadline_cicilan()
    {
      $input = $this->getInputToken();
      $arr = array('msg' => '','status'=>0);
      $arr2 = array();
      for ($i=0; $i < count($input); $i++) {
        foreach ($input[$i] as $key => $value) {
          $arr2[] = array($key => $value);
        }
      }

      print_r($arr2);
      /*$dataSave = array(
              'ID_ujian_perprody' => $ID_ujian_perprody,
              'DateTimeTest' => $DateTimeTest,
              'Lokasi' => $Lokasi,
      );
      $this->db->insert('db_admission.register_jadwal_ujian', $dataSave);*/
      /*switch ($input['Action']) {
          case 'create_va':
              // get VA Number
                $getDataPersonal = $this->m_admission->getDataPersonal($input['ID_register_formulir']);
                $VA = $getDataPersonal[0]['VA_number'];
              // cek cicilan selanjutnya untuk dibayar, return invoice dan deadline, jika deadline telah melewati tgl sekarang maka deadline ambil dari deadline_payment
                $this->load->model('finance/m_finance');
                $checkCicilan = $this->m_finance->checkCicilan($input['ID_register_formulir'],0);
                if (count($checkCicilan) > 0) {
                  $payment = $checkCicilan[0]['Invoice'];
                  $DeadLinePayment = $checkCicilan[0]['Deadline'];
                  // cek tanggal telah berlalu atau belum
                    $t = $this->m_admission->cekTanggalTime($DeadLinePayment);
                    if ($t) {
                      $this->load->model('master/m_master');
                      $deadline = $this->m_master->DeadLinePayment();
                      $DeadLinePayment = date('c', time() + (($deadline) * 24 * 3600));
                      $p = $this->m_finance->create_va_Payment($payment,$DeadLinePayment, $getDataPersonal[0]['Name'], $getDataPersonal[0]['Email'],$VA);

                        // send email tentang update ini
                          $text = 'Dear '.$getDataPersonal[0]['Name'].',<br><br>
                                      You have changes to your payment deadline.<br>
                                      For detail please check on page Tuition Fee in '.$this->GlobalVariableAdi['url_registration'];
                          $to = $getDataPersonal[0]['Email'];
                          $subject = "Podomoro University Change Payment Deadline";
                          $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

                      if ($p['status']) {
                        // update db tentang deadline dan biling
                        $this->m_finance->updateCicilanDeadline($p['msg'],$checkCicilan[0]['ID']);
                        $arr['msg'] = 'Data berhasil diupdate';
                        $arr['status'] = 1;
                      }
                    }
                    else
                    {
                      $p = $this->m_finance->create_va_Payment($payment,$DeadLinePayment, $getDataPersonal[0]['Name'], $getDataPersonal[0]['Email'],$VA);
                        // send email tentang update ini
                            $text = 'Dear '.$getDataPersonal[0]['Name'].',<br><br>
                                        You have changes to your payment deadline.<br>
                                        For detail please check on page Tuition Fee in '.$this->GlobalVariableAdi['url_registration'];
                            $to = $getDataPersonal[0]['Email'];
                            $subject = "Podomoro University Change Payment Deadline";
                            $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

                      if ($p['status']) {
                        // update db tentang deadline dan biling
                        $this->m_finance->updateCicilanDeadline($p['msg'],$checkCicilan[0]['ID']);
                        $arr['msg'] = 'Data berhasil diupdate';
                        $arr['status'] = 1;
                      }
                    }
                }
              // create va
              break;
          case 'update_va':
                // get VA Number
                  $getDataPersonal = $this->m_admission->getDataPersonal($input['ID_register_formulir']);
                  $VA = $getDataPersonal[0]['VA_number'];
                  // $DeadLinePayment = $input['deadline_payment'];
                  $DeadLinePayment = $input['deadline_payment'];
                  // cek cicilan selanjutnya untuk dibayar, return invoice dan deadline, jika deadline telah melewati tgl sekarang maka deadline ambil dari deadline_payment
                    $this->load->model('finance/m_finance');
                    $checkCicilan = $this->m_finance->checkCicilan($input['ID_register_formulir'],0);
                    if (count($checkCicilan) > 0) {
                      $payment = $checkCicilan[0]['Invoice'];
                      $BilingID = $checkCicilan[0]['BilingID'];
                      $DeadLinePaymentOld = $checkCicilan[0]['Deadline'];
                      if (strlen($DeadLinePayment) != 19) {
                        $DeadLinePayment = $DeadLinePayment.':00';
                      }
                      $t = $this->m_admission->cekTanggalTime($DeadLinePaymentOld);
                      if ($t) {

                        // $this->load->model('master/m_master');
                        // $deadline = $this->m_master->DeadLinePayment();
                        // $DeadLinePayment = date('c', time() + (($deadline) * 24 * 3600));
                        $p = $this->m_finance->create_va_Payment($payment,$DeadLinePayment, $getDataPersonal[0]['Name'], $getDataPersonal[0]['Email'],$VA);
                      }
                      else
                      {
                        $p = $this->m_finance->update_va_Payment($payment,$DeadLinePayment, $getDataPersonal[0]['Name'], $getDataPersonal[0]['Email'],$BilingID);
                      }

                        // send email tentang update ini
                            $text = 'Dear '.$getDataPersonal[0]['Name'].',<br><br>
                                        You have changes to your payment deadline.<br>
                                        For detail please check on page Tuition Fee in '.$this->GlobalVariableAdi['url_registration'];
                            $to = $getDataPersonal[0]['Email'];
                            $subject = "Podomoro University Change Payment Deadline";
                            $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

                      if ($p['status']) {
                        // update db tentang deadline dan biling
                        $this->m_finance->updateCicilanDeadline($p['msg'],$checkCicilan[0]['ID']);
                        $arr['msg'] = 'Data berhasil diupdate';
                        $arr['status'] = 1;
                      }

                    }
              break;
      }*/

      echo json_encode($arr);

    }

    public function page_data_calon_mahasiswa()
    {
      // $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/page_data_calon_mahasiswa',$this->data,true);
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/page_data_calon_mahasiswa_server_side',$this->data,true);
      $this->temp($content);
    }

    public function data_calon_mahasiswa($page = null)
    {
      $input = $this->getInputToken();
      $this->load->library('pagination');
      $config = $this->config_pagination_default_ajax(1000,25,4);
      $this->pagination->initialize($config);
      $page = $this->uri->segment(4);
      $start = ($page - 1) * $config["per_page"];
      $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsAll($config["per_page"], $start,$input));
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/data_calon_mahasiswa',$this->data,true);
      $output = array(
      'pagination_link'  => $this->pagination->create_links(),
      'loadtable'   => $content,
      );
      echo json_encode($output);
    }

    public function detailPayment()
    {
      $input = $this->getInputToken();
      $payment_register = $this->m_admission->getPaymentType_Cost_created($input['ID_register_formulir']);
      $payment_pre = $this->m_master->caribasedprimary('db_finance.payment_pre','ID_register_formulir',$input['ID_register_formulir']);
      $arr = array();
      for ($i=0; $i < count($payment_pre); $i++) {
        $arr[$i] = array(
            'Invoice' => 'Rp '.number_format($payment_pre[$i]['Invoice'],2,',','.'),
            'BilingID' => $payment_pre[$i]['BilingID'],
            'Status' => ($payment_pre[$i]['Status'] == 0 ) ? 'Belum Bayar' : 'Sudah Bayar',
            'Deadline' => $payment_pre[$i]['Deadline'],
        );
      }
      $arr2 = array();
      for ($i=0; $i < count($payment_register); $i++) {
        $arr2[$i] = array(
            'Description' => $payment_register[$i]['Description'],
            'Discount' => $payment_register[$i]['Discount'].'%',
            'Pay_tuition_fee' => 'Rp '.number_format($payment_register[$i]['Pay_tuition_fee'],2,',','.'),
            'Status' => $payment_register[$i]['Status'],
        );
      }
      $output = array(
      'payment_register'  => $arr2,
      'payment_pre'   => $arr,
      );
      echo json_encode($output);

    }

    public function set_input_tuition_fee_submit()
    {
      $input = $this->getInputToken();
      // save data to payment_register and payment_pre
      //print_r($input);
      $msg = '';
      try {
        $output =$this->m_admission->set_input_tuition_fee_submit($input);
        $msg = '';
      }
      catch(Exception $e) {
        $msg = $e->getMessage();
      }
      echo json_encode($msg);
    }

    public function generatenim()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/master_calon_mahasiswa/generatenim',$this->data,true);
      $this->temp($content);
    }

    public function submit_import_excel_File_generate_nim()
    {
      die();
      // print_r($_FILES);
      if(isset($_FILES["fileData"]["name"]))
      {
        $path = $_FILES["fileData"]["tmp_name"];
        $arr_insert = array();
        $arr_insert_auth = array();
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load($path); // Empty Sheet
        $objWorksheet = $excel2->setActiveSheetIndex(0);
        $CountRow = $objWorksheet->getHighestRow();
        $ProdiID = $this->input->post('Prodi');
        $ta = $this->input->post('ta');
        $ta = 'ta_'.$ta;
        //echo $objWorksheet->getCellByColumnAndRow(1, 8)->getCalculatedValue();
        // start by coloumn 1
        //check existing db
        $checkDB = $this->m_master->checkDB($ta);
        if ($checkDB) {
          // create db
          $this->m_api->createDBYearAcademicNew($ta);

        }

        $aa = 1;
        $bb = 1;
        $Q_getLastNPM = $this->m_master->getLastNPM($ta,$ProdiID);
        // print_r($Q_getLastNPM);
        if (count($Q_getLastNPM)== 1) {
          $bb = $Q_getLastNPM[0]['NPM'];
        }
        for ($i=2; $i < ($CountRow + 1); $i++) {
          $temp = array();
          $temp2 = array();
          $ProgramID = $objWorksheet->getCellByColumnAndRow(1, $i)->getCalculatedValue();
          $LevelStudyID = $objWorksheet->getCellByColumnAndRow(2, $i)->getCalculatedValue();
          $ReligionID = $objWorksheet->getCellByColumnAndRow(3, $i)->getCalculatedValue();
          $NationalityID = $objWorksheet->getCellByColumnAndRow(4, $i)->getCalculatedValue();
          $ProvinceID = $objWorksheet->getCellByColumnAndRow(5, $i)->getCalculatedValue();
          $CityID = $objWorksheet->getCellByColumnAndRow(6, $i)->getCalculatedValue();
          $HighSchoolID = $objWorksheet->getCellByColumnAndRow(7, $i)->getCalculatedValue();
          $HighSchool = $objWorksheet->getCellByColumnAndRow(8, $i)->getCalculatedValue();
          $HighSchool = strtoupper($HighSchool);

          $MajorsHighSchool = $objWorksheet->getCellByColumnAndRow(9, $i)->getCalculatedValue();
          if (count($Q_getLastNPM) == 0) {
            // search NPM dengan 2 Pertama kode Prodi CodeID
            // 2 kedua tahun angkatan ambil 2 digit terakhir
            $Q_Prodi = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ProdiID);
            $CodeID = $Q_Prodi[0]['CodeID'];
            $strLenTA = strlen($ta) - 2; // last 2 digit
            $P_ang = substr($ta, $strLenTA,2); // last 2 digit
            $MaxInc = 4;
            $strlen_aa = strlen($aa);
            $V_aa = $aa;
            for ($j=0; $j < ($MaxInc-$strlen_aa); $j++) {
              $V_aa = '0'.$V_aa;
            }
            $inc = $CodeID.$P_ang.$V_aa;
          }
          else
          {
            // $bb =(int)$bb
            $bb = $bb + 1;
            $inc = $bb;
          }

          $NPM = $inc;
          $Name = $objWorksheet->getCellByColumnAndRow(11, $i)->getCalculatedValue();
          $Name = strtolower($Name);
          $Name = ucwords($Name);

          $Address = $objWorksheet->getCellByColumnAndRow(12, $i)->getCalculatedValue();
          $Address = strtolower($Address);
          $Address = ucwords($Address);

          $Photo = "";
          $Gender = $objWorksheet->getCellByColumnAndRow(14, $i)->getCalculatedValue();
          $PlaceOfBirth = $objWorksheet->getCellByColumnAndRow(15, $i)->getCalculatedValue();
          $DateOfBirth = $objWorksheet->getCellByColumnAndRow(16, $i)->getCalculatedValue();
          $DateOfBirth = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($DateOfBirth));
          $Phone = $objWorksheet->getCellByColumnAndRow(17, $i)->getCalculatedValue();
          $HP = $objWorksheet->getCellByColumnAndRow(18, $i)->getCalculatedValue();
          $ClassOf = $objWorksheet->getCellByColumnAndRow(19, $i)->getCalculatedValue();
          $Email = $objWorksheet->getCellByColumnAndRow(20, $i)->getCalculatedValue();
          $Email = strtolower($Email);

          $Jacket = $objWorksheet->getCellByColumnAndRow(21, $i)->getCalculatedValue();
          $AnakKe = $objWorksheet->getCellByColumnAndRow(22, $i)->getCalculatedValue();
          $JumlahSaudara = $objWorksheet->getCellByColumnAndRow(23, $i)->getCalculatedValue();
          $NationExamValue = $objWorksheet->getCellByColumnAndRow(24, $i)->getCalculatedValue();
          $GraduationYear = $objWorksheet->getCellByColumnAndRow(25, $i)->getCalculatedValue();
          $IjazahNumber = $objWorksheet->getCellByColumnAndRow(26, $i)->getCalculatedValue();

          $Father = $objWorksheet->getCellByColumnAndRow(27, $i)->getCalculatedValue();
          $Father = strtolower($Father);
          $Father = ucwords($Father);

          $Mother = $objWorksheet->getCellByColumnAndRow(28, $i)->getCalculatedValue();
          $Mother = strtolower($Mother);
          $Mother = ucwords($Mother);


          $StatusFather = $objWorksheet->getCellByColumnAndRow(29, $i)->getCalculatedValue();
          $StatusMother = $objWorksheet->getCellByColumnAndRow(30, $i)->getCalculatedValue();
          $PhoneFather = $objWorksheet->getCellByColumnAndRow(31, $i)->getCalculatedValue();
          $PhoneMother = $objWorksheet->getCellByColumnAndRow(32, $i)->getCalculatedValue();
          $OccupationFather = $objWorksheet->getCellByColumnAndRow(33, $i)->getCalculatedValue();
          $OccupationMother = $objWorksheet->getCellByColumnAndRow(34, $i)->getCalculatedValue();
          $EducationFather = $objWorksheet->getCellByColumnAndRow(35, $i)->getCalculatedValue();
          $EducationMother = $objWorksheet->getCellByColumnAndRow(36, $i)->getCalculatedValue();

          $AddressFather = $objWorksheet->getCellByColumnAndRow(37, $i)->getCalculatedValue();
          $AddressFather = strtolower($AddressFather);
          $AddressFather = ucwords($AddressFather);

          $AddressMother = $objWorksheet->getCellByColumnAndRow(38, $i)->getCalculatedValue();
          $AddressMother = strtolower($AddressMother);
          $AddressMother = ucwords($AddressMother);

          $EmailFather = $objWorksheet->getCellByColumnAndRow(39, $i)->getCalculatedValue();
          $EmailMother = $objWorksheet->getCellByColumnAndRow(40, $i)->getCalculatedValue();
          $StatusStudentID = 3;
          $temp = array(
            'ProdiID' => $ProdiID,
            'ProgramID' => $ProgramID,
            'LevelStudyID' => $LevelStudyID,
            'ReligionID' => $ReligionID,
            'NationalityID' => $NationalityID,
            'ProvinceID' => $ProvinceID,
            'CityID' => $CityID,
            'HighSchoolID' => $HighSchoolID,
            'HighSchool' => $HighSchool,
            'MajorsHighSchool' => $MajorsHighSchool,
            'NPM' => $NPM,
            'Name' => $Name,
            // 'Address' => $Address,
            'Address' => $Address,
            'Gender' => $Gender,
            'PlaceOfBirth' => $PlaceOfBirth,
            'DateOfBirth' => $DateOfBirth,
            'Phone' => $Phone,
            'HP' => $HP,
            'ClassOf' => $ClassOf,
            'Email' => $Email,
            'Jacket' => $Jacket,
            'AnakKe' => $AnakKe,
            'JumlahSaudara' => $JumlahSaudara,
            'NationExamValue' => $NationExamValue,
            'GraduationYear' => $GraduationYear,
            'IjazahNumber' => $IjazahNumber,
            'Father' => $Father,
            'Mother' => $Mother,
            'StatusFather' => $StatusFather,
            'StatusMother' => $StatusMother,
            'PhoneFather' => $PhoneFather,
            'PhoneMother' => $PhoneMother,
            'OccupationFather' => $OccupationFather,
            'OccupationMother' => $OccupationMother,
            'EducationFather' => $EducationFather,
            'EducationMother' => $EducationMother,
            'AddressFather' => $AddressFather,
            'AddressMother' => $AddressMother,
            'EmailFather' => $EmailFather,
            'EmailMother' => $EmailMother,
            'StatusStudentID' => $StatusStudentID,
          );
          $arr_insert[] = $temp;

          $plan_password = $NPM.''.'123456';
          $pas = md5($plan_password);
          $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

          $pasword_old = $DateOfBirth;
          $d = explode('-', $pasword_old);
          $pasword_old = $d[2].$d[1].substr($d[0], 2,2);

          $temp2 = array(
              'NPM' => $NPM,
              'Password' => $pass,
              'Password_Old' => md5($pasword_old),
              'Year' => date('Y'),
              'EmailPU' => $NPM.'@podomorouniversity.ac.id',
              'StatusStudentID' => 3,
              'Status' => '-1',
          );

          $arr_insert_auth[] = $temp2;

          $aa++;
        }

        $this->db->insert_batch($ta.'.students', $arr_insert);
        $this->db->insert_batch('db_academic.auth_students', $arr_insert_auth);
        echo json_encode(array('status'=> 1,'msg' => ''));
      }
      else
      {
        exit('No direct script access allowed');
      }
    }


    public function getDataPersonal_Candidate()
    {
        $requestData= $_REQUEST;
        $reqTahun = $this->input->post('tahun');
        $FormulirType = $this->input->post('FormulirType');
        $StatusPayment = $this->input->post('StatusPayment');
        // print_r($requestData);
        // die();
        $No = $requestData['start'] + 1;
        $totalData = $this->m_admission->getCountAllDataPersonal_Candidate($requestData,$reqTahun,$FormulirType,$StatusPayment);
        $AddWhere = '';
        $AddWhere2 = '';
        if ($FormulirType != '%') {
           $AddWhere .= ' and a.StatusReg = '.$FormulirType.' ';
        }
        $sql = 'select ccc.* from (
                select a.ID as RegisterID,a.Name,a.SchoolID,a.Phone,b.SchoolName,a.Email,a.RegisterAT,a.VA_number,c.FormulirCode,e.ID_program_study,d.NameEng,d.Name as NamePrody, e.ID as ID_register_formulir,e.UploadFoto,
                xq.DiscountType,
                if(f.Rangking > 0 ,f.Rangking,"-") as Rangking,
                if(
                    (select count(*) as total from db_finance.payment_pre where `Status` = 0 and ID_register_formulir = e.ID limit 1) = 0 ,
                        if((select count(*) as total from db_finance.payment_pre as aaa where aaa.ID_register_formulir =  e.ID limit 1)
                             > 0 ,"Lunas","-"
                          )
                        ,
                        "Belum Lunas"
                  ) as chklunas,
                (select count(*) as total from db_finance.payment_pre as aaa where aaa.ID_register_formulir =  e.ID ) as Cicilan
                ,xx.Name as NameSales,
                if(a.StatusReg = 1, (select No_Ref from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,(select No_Ref from db_admission.formulir_number_online_m where FormulirCode = c.FormulirCode limit 1)  ) as No_Ref
                from db_admission.register as a
                LEFT join db_admission.school as b
                on a.SchoolID = b.ID
                LEFT JOIN db_admission.register_verification as z
                on a.ID = z.RegisterID
                LEFT JOIN db_admission.register_verified as c
                on z.ID = c.RegVerificationID
                LEFT JOIN db_admission.register_formulir as e
                on c.ID = e.ID_register_verified
                LEFT join db_academic.program_study as d
                on e.ID_program_study = d.ID
                LEFT join db_admission.register_rangking as f
                on e.ID = f.ID_register_formulir
                left join db_admission.sale_formulir_offline as xz
                  on c.FormulirCode = xz.FormulirCodeOffline
                LEFT JOIN db_employees.employees as xx
                on xz.PIC = xx.NIP
                LEFT JOIN db_finance.register_admisi as xy
                on e.ID = xy.ID_register_formulir
                LEFT JOIN db_admission.register_dsn_type_m as xq
                on xq.ID = xy.TypeBeasiswa
                where a.SetTa = "'.$reqTahun.'" '.$AddWhere.'
              ) ccc
            ';
        if ($StatusPayment != '%') {
           $AddWhere2 .= ' and chklunas = "'.$StatusPayment.'" ';
        }    
        $sql.= ' where ( Name LIKE "'.$requestData['search']['value'].'%" or NamePrody LIKE "%'.$requestData['search']['value'].'%"
                or FormulirCode LIKE "'.$requestData['search']['value'].'%" or SchoolName LIKE "%'.$requestData['search']['value'].'%"
                #or chklunas LIKE "'.$requestData['search']['value'].'%" 
                or DiscountType LIKE "'.$requestData['search']['value'].'%"
                or NameSales LIKE "'.$requestData['search']['value'].'%"
                or No_Ref LIKE "'.$requestData['search']['value'].'%" )
                '.$AddWhere2.'
                ';
        $sql.= ' ORDER BY chklunas ASC, RegisterID DESC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

        $query = $this->db->query($sql)->result_array();

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];

            // $nestedData[] = '<input type="checkbox" name="id[]" value="'.$row['ID_register_formulir'].'">';
            // $nestedData[] = $row['NamePrody'];
            $Code = ($row['No_Ref'] != "") ? $row['FormulirCode'].' / '.$row['No_Ref'] : $row['FormulirCode'];
            $nestedData[] = $No;
            $nestedData[] = $row['Name'].'<br>'.$row['Email'].'<br>'.$row['Phone'].'<br>'.$row['SchoolName'];
            $nestedData[] = $row['NamePrody'].'<br>'.$Code.'<br>'.$row['VA_number'];
            $nestedData[] = $row['NameSales'];
            $nestedData[] = $row['Rangking'];
            $nestedData[] = $row['DiscountType'];
            $nestedData[] = '<button class="btn btn-inverse btn-notification btn-show" id-register-formulir = "'.$row['ID_register_formulir'].'" email = "'.$row['Email'].'" Nama = "'.$row['Name'].'">Show</button>';
            // get tagihan
            $getTagihan = $this->m_admission->getPaymentType_Cost_created($row['ID_register_formulir']);
            $tagihan = '';
            for ($j=0; $j < count($getTagihan); $j++) {
                $tagihan .= $getTagihan[$j]['Abbreviation'].' : '.'Rp '.number_format($getTagihan[$j]['Pay_tuition_fee'],2,',','.').'<br>';
            }

            $nestedData[] = $tagihan;
            $cicilan = '';
            if ($row['Cicilan'] == 0) {
              $cicilan = '-';
            }
            elseif ($row['Cicilan'] == 1) {
               $cicilan = '1x Pembayaran'.'<br><button class = "btn btn-primary btn-payment" id-register-formulir = "'.$row['ID_register_formulir'].'" Nama = "'.$row['Name'].'">Detail</button>';
             }
             elseif ($row['Cicilan'] > 1) {
               $cicilan = $row['Cicilan'].'x Pembayaran'.'<br><button class = "btn btn-primary btn-payment" id-register-formulir = "'.$row['ID_register_formulir'].'" Nama = "'.$row['Name'].'">Detail</button>';
             }
            $nestedData[] = $cicilan;
            $nestedData[] = $row['chklunas'];
            $nestedData[] = $row['RegisterAT'];
            $nestedData[] = '<div style="text-align: center;"><button class="btn btn-sm btn-primary btnLoginPortalRegister " data-xx="'.$row['Email'].'" data-xx2="'.$row['FormulirCode'].'"  ><i class="fa fa-sign-in right-margin"></i> Login Portal</button></div>';
            $data[] = $nestedData;
            $No++;
        }

        // print_r($data);

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function getDataPersonal_Candidate_to_be_mhs()
    {
      $requestData= $_REQUEST;
      $reqTahun = $this->input->post('tahun');
      $FormulirType = $this->input->post('FormulirType');
      $StatusPayment = $this->input->post('StatusPayment');
      // print_r($requestData);
      // die();
      $No = $requestData['start'] + 1;
      $totalData = $this->m_admission->getCountDataPersonal_Candidate_to_be_mhs($requestData,$reqTahun,$FormulirType,$StatusPayment);
      $AddWhere = '';
      $AddWhere2 = ' chklunas in ("Lunas","Belum Lunas") ';
      if ($FormulirType != '%') {
         $AddWhere .= ' and a.StatusReg = '.$FormulirType.' ';
      }

      $sql = 'select ccc.* from (
              select a.ID as RegisterID,a.Name,a.SchoolID,b.SchoolName,a.Email,a.VA_number,c.FormulirCode,e.ID_program_study,d.NameEng,d.Name as NamePrody, e.ID as ID_register_formulir,e.UploadFoto,
              xq.DiscountType,
              if(f.Rangking > 0 ,f.Rangking,"-") as Rangking,
              if(
                  (select count(*) as total from db_finance.payment_pre where `Status` = 0 and ID_register_formulir = e.ID limit 1) = 0 ,
                      if((select count(*) as total from db_finance.payment_pre as aaa where aaa.ID_register_formulir =  e.ID limit 1)
                           > 0 ,"Lunas","-"
                        )
                      ,
                      "Belum Lunas"
                ) as chklunas,
              (select count(*) as total from db_finance.payment_pre as aaa where aaa.ID_register_formulir =  e.ID ) as Cicilan
              ,xx.Name as NameSales,
              if(a.StatusReg = 1, (select No_Ref from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,(select No_Ref from db_admission.formulir_number_online_m where FormulirCode = c.FormulirCode limit 1)  ) as No_Ref
              from db_admission.register as a
              join db_admission.school as b
              on a.SchoolID = b.ID
              LEFT JOIN db_admission.register_verification as z
              on a.ID = z.RegisterID
              LEFT JOIN db_admission.register_verified as c
              on z.ID = c.RegVerificationID
              LEFT JOIN db_admission.register_formulir as e
              on c.ID = e.ID_register_verified
              LEFT join db_academic.program_study as d
              on e.ID_program_study = d.ID
              LEFT join db_admission.register_rangking as f
              on e.ID = f.ID_register_formulir
              left join db_admission.sale_formulir_offline as xz
                on c.FormulirCode = xz.FormulirCodeOffline
              LEFT JOIN db_employees.employees as xx
              on xz.PIC = xx.NIP
              LEFT JOIN db_finance.register_admisi as xy
              on e.ID = xy.ID_register_formulir
              LEFT JOIN db_admission.register_dsn_type_m as xq
              on xq.ID = xy.TypeBeasiswa
              left join db_admission.formulir_number_offline_m as px
              on px.FormulirCode = c.FormulirCode
              where a.SetTa = "'.$reqTahun.'" '.$AddWhere.'
            ) ccc
          ';
      if ($StatusPayment != '%') {
         $AddWhere2 .= ' and chklunas = "'.$StatusPayment.'" ';
      }    
      $sql.= ' where (Name LIKE "'.$requestData['search']['value'].'%" or NamePrody LIKE "%'.$requestData['search']['value'].'%"
              or FormulirCode LIKE "'.$requestData['search']['value'].'%" or SchoolName LIKE "%'.$requestData['search']['value'].'%"
              #or chklunas LIKE "'.$requestData['search']['value'].'%" 
              or DiscountType LIKE "'.$requestData['search']['value'].'%"
              or NameSales LIKE "'.$requestData['search']['value'].'%"
              or No_Ref LIKE "'.$requestData['search']['value'].'%"
                )
             and '.$AddWhere2.' and FormulirCode not in (select FormulirCode from db_admission.to_be_mhs)';
      $sql.= ' ORDER BY chklunas Desc,RegisterID DESC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

      $query = $this->db->query($sql)->result_array();

      $data = array();
      for($i=0;$i<count($query);$i++){
          $nestedData=array();
          $row = $query[$i];

          // $nestedData[] = '<input type="checkbox" name="id[]" value="'.$row['ID_register_formulir'].'">';
          // $nestedData[] = $row['NamePrody'];
          $nestedData[] = $No.' &nbsp <input type="checkbox" name="id[]" value="'.$row['ID_register_formulir'].'">';
          $nestedData[] = $row['Name'].'<br>'.$row['Email'].'<br>'.$row['SchoolName'];
          $FormulirCode = ($row['No_Ref'] != "" || $row['No_Ref'] != null ) ? $row['FormulirCode'].' / '.$row['No_Ref'] : $row['FormulirCode'];
          $nestedData[] = $row['NamePrody'].'<br>'.$FormulirCode.'<br>'.$row['VA_number'];
          $nestedData[] = $row['NameSales'];
          $nestedData[] = $row['Rangking'];
          $nestedData[] = $row['DiscountType'];
          $nestedData[] = '<button class="btn btn-inverse btn-notification btn-show" id-register-formulir = "'.$row['ID_register_formulir'].'" email = "'.$row['Email'].'" Nama = "'.$row['Name'].'">Show</button>';
          // get tagihan
          $getTagihan = $this->m_admission->getPaymentType_Cost_created($row['ID_register_formulir']);
          $tagihan = '';
          for ($j=0; $j < count($getTagihan); $j++) {
              $tagihan .= $getTagihan[$j]['Abbreviation'].' : '.'Rp '.number_format($getTagihan[$j]['Pay_tuition_fee'],2,',','.').'<br>';
          }

          $nestedData[] = $tagihan;
          $cicilan = '';
          if ($row['Cicilan'] == 0) {
            $cicilan = '-';
          }
          elseif ($row['Cicilan'] == 1) {
            $cicilan = '1x Pembayaran'.'<br><button class = "btn btn-primary btn-payment" id-register-formulir = "'.$row['ID_register_formulir'].'" Nama = "'.$row['Name'].'">Detail</button>';
          }
          elseif ($row['Cicilan'] > 1) {
            $cicilan = $row['Cicilan'].'x Pembayaran'.'<br><button class = "btn btn-primary btn-payment" id-register-formulir = "'.$row['ID_register_formulir'].'" Nama = "'.$row['Name'].'">Detail</button>';
          }
          $nestedData[] = $cicilan;
          $nestedData[] = $row['chklunas'];
          $data[] = $nestedData;
          $No++;
      }

      // print_r($data);

      $json_data = array(
          "draw"            => intval( $requestData['draw'] ),
          "recordsTotal"    => intval($totalData),
          "recordsFiltered" => intval($totalData ),
          "data"            => $data
      );
      echo json_encode($json_data);
    }


    public function generate_to_be_mhs()
    {
      // die();

      // check koneksi AD
      if($_SERVER['SERVER_NAME']=='pcam.podomorouniversity.ac.id') {
        $urlAD = URLAD.'__api/Create';
        $is_url_exist = $this->m_master->is_url_exist($urlAD);
        if (!$is_url_exist) {
          $msg = 'Windows active directory server not connected';
          echo json_encode($msg);
          die(); // stop script
        }
      }

      $input = $this->getInputToken();
      $msg = '';
      //check existing db
          // get setting ta
          $taDB = $this->m_master->showData_array('db_admission.set_ta');
          $YearAuth = $taDB[0]['Ta'];
          $ta = $taDB[0]['Ta'];
          $ta = 'ta_'.$ta;

           $checkDB = $this->m_master->checkDB($ta);
          if ($checkDB) {
            // create db
            $this->m_api->createDBYearAcademicNew($ta);
          }

          // search data berdasarkan ID_register_formulir
          $arrInputID = $input['checkboxArr'];
          $arr = array();
          $arr_insert_auth = array();
          $arr_insert3 = array(); // for auth_parents
          $arr_insert4 = array();
          $arr_insert_library = array();
          $data_arr = array();
          for ($i=0; $i < count($arrInputID); $i++) {
            $data = $this->m_master->caribasedprimary('db_admission.register_formulir','ID',$arrInputID[$i]);


            $data2 = $this->m_admission->getDataPersonal($arrInputID[$i]);

            // Update Intake CRM
             if (count($data2) > 0) {
              $t_r = $data2[0];
              if (array_key_exists('ID_Crm', $t_r)) {
                $ID_Crm = $data2[0]['ID_Crm'];
                if($ID_Crm!=null && $ID_Crm!=0 && $ID_Crm!='' && $ID_Crm!='0') {
                    $this->db->set('Status', 8);
                    $this->db->where('ID', $ID_Crm);
                    $this->db->update('db_admission.crm');
                    $this->db->reset_query();
                }
              }
             }

            $ProdiID = $data[0]['ID_program_study'];
            $aa = 1;
            $bb = 1;
                $Q_getLastNPM = $this->m_master->getLastNPM($ta,$ProdiID);
                // print_r($Q_getLastNPM);
                if (count($Q_getLastNPM)== 1) {
                  $bb = $Q_getLastNPM[0]['NPM'];
                }

                $Q_Prodi = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ProdiID);
                if (count($Q_getLastNPM) == 0) {
                    // search NPM dengan 2 Pertama kode Prodi CodeID
                    // 2 kedua tahun angkatan ambil 2 digit terakhir
                    if (count($Q_Prodi) == 0) {
                      $msg = 'Error';
                      break;
                    }
                    $CodeID = $Q_Prodi[0]['CodeID'];
                    $strLenTA = strlen($ta) - 2; // last 2 digit
                    $P_ang = substr($ta, $strLenTA,2); // last 2 digit
                    $MaxInc = 4;
                    $strlen_aa = strlen($aa);
                    $V_aa = $aa;
                    for ($j=0; $j < ($MaxInc-$strlen_aa); $j++) {
                      $V_aa = '0'.$V_aa;
                    }
                    $inc = $CodeID.$P_ang.$V_aa;
                }
                else
                {
                  // $bb =(int)$bb
                  $bb = $bb + 1;
                  $inc = $bb;
                }

            $NPM = $inc;
            $ProgramID = 1; // program id pada db siak lama diset 1 adalah program kuliah reguler
            $LevelStudyID = $Q_Prodi[0]['EducationLevelID'];
            $ReligionID = $data[0]['ReligionID'];
            $NationalityID = $data[0]['NationalityID'];
            $ProvinceID = $data[0]['ID_province'];
            $CityID = $data[0]['ID_region'];
            $HighSchoolID = $data2[0]['SchoolID'];
            $SchoolName = $this->m_master->caribasedprimary('db_admission.school','ID',$HighSchoolID);
            if (count($SchoolName) == 0) {
               $msg = 'Error';
              break;
            }

            $HighSchool = $SchoolName[0]['SchoolName'];
            $MajorsHighSchool = $this->m_master->caribasedprimary('db_admission.register_major_school','ID',$data[0]['ID_register_major_school']);
            if (count($MajorsHighSchool) == 0) {
               $msg = 'Error';
              break;
            }
            $MajorsHighSchool = $MajorsHighSchool[0]['SchoolMajor'];
            $Name = $data2[0]['Name'];

            $Kelurahan = ' Kelurahan : '.$data[0]['District'];
            $DistrictID = $data[0]['ID_districts'];
            $DistrictID = $this->m_master->caribasedprimary('db_admission.district','DistrictID',$DistrictID);
            if (count($DistrictID) == 0) {
               $msg = 'Error';
              break;
            }
            $DistrictID = ' Kecamatan : '.$DistrictID[0]['DistrictName'];
            $RegionID = $this->m_master->caribasedprimary('db_admission.region','RegionID',$data[0]['ID_region']);
            if (count($RegionID) == 0) {
               $msg = 'Error';
              break;
            }
            $RegionID = $RegionID[0]['RegionName'];
            $ID_province = $this->m_master->caribasedprimary('db_admission.province','ProvinceID',$data[0]['ID_province']);
            if (count($ID_province) == 0) {
               $msg = 'Error';
              break;
            }
            $ID_province = $ID_province[0]['ProvinceName'];

            $Address = $data[0]['Address'].$Kelurahan.$DistrictID.' '.$RegionID.' '.$ID_province;

            $Gender = $data[0]['Gender'];
            $PlaceOfBirth = $data[0]['PlaceBirth'];
            $DateOfBirth = $data[0]['DateBirth'];
            $Phone = $data[0]['PhoneNumber'];
            $HP = $data[0]['PhoneNumber'];
            $ClassOf  = $taDB[0]['Ta'];
            $Email = $data2[0]['Email'];
            $Jacket = $this->m_master->caribasedprimary('db_admission.register_jacket_size_m','ID',$data[0]['ID_register_jacket_size_m']);
            if (count($Jacket) == 0) {
              $Jacket = '';
            }
            else
            {
              $Jacket = $Jacket[0]['JacketSize'];
            }

            $AnakKe = 0;
            $JumlahSaudara = 0;
            $NationExamValue = 0;
            $GraduationYear = $data[0]['YearGraduate'];
            $IjazahNumber = '';
            $Father = $data[0]['FatherName'];
            $Mother = $data[0]['MotherName'];
            $StatusFather = substr($data[0]['FatherStatus'], 0,1);
            $StatusMother = substr($data[0]['MotherStatus'], 0,1);
            $PhoneFather = $data[0]['FatherPhoneNumber'];
            $PhoneMother = $data[0]['MotherPhoneNumber'];
            $OccupationFather  = $this->m_master->caribasedprimary('db_admission.occupation','ocu_code',$data[0]['Father_ID_occupation']);
            if (count($OccupationFather) == 0) {
              $OccupationFather  = '';
            }
            else
            {
              $OccupationFather  = $OccupationFather[0]['ocu_name'];
            }

            $OccupationMother = $this->m_master->caribasedprimary('db_admission.occupation','ocu_code',$data[0]['Mother_ID_occupation']);
            if (count($OccupationMother) == 0) {
              $OccupationMother  = '';
            }
            else
            {
              $OccupationMother = $OccupationMother[0]['ocu_name'];
            }

            $EducationFather = '';
            $EducationMother = '';
            $AddressFather = $data[0]['FatherAddress'];
            $AddressMother = $data[0]['MotherAddress'];
            $EmailFather = '';
            $EmailMother = '';
            $StatusStudentID = 3;

             $KTPNumber = $data[0]['IdentityCard'];

            // copy document
            $Photo = ''; // id foto 5
            if (!file_exists('./uploads/document/'.$NPM)) {
                mkdir('./uploads/document/'.$NPM, 0777, true);
                // copy("./document/index.html",'./document/'.$namaFolder.'/index.html');
                // copy("./document/index.php",'./document/'.$namaFolder.'/index.php');
            }

            if (!file_exists('./uploads/students/'.$ta)) {
                mkdir('./uploads/students/'.$ta, 0777, true);
                // copy("./document/index.html",'./document/'.$namaFolder.'/index.html');
                // copy("./document/index.php",'./document/'.$namaFolder.'/index.php');
            }

            $getDoc = $this->m_master->caribasedprimary('db_admission.register_document','ID_register_formulir',$arrInputID[$i]);
            for ($z=0; $z < count($getDoc); $z++) {
              if ($getDoc[$z]['Attachment'] != '' || !empty($getDoc[$z]['Attachment'])) {
                $explode = explode(',', $getDoc[$z]['Attachment']);
                // asign variable foto
                  if ($getDoc[$z]['ID_reg_doc_checklist'] == 5 && $getDoc[$z]['Status']== 'Done') {
                    if (count($explode) > 0) {
                      $G_FileName = $explode[0];
                      $ff = explode('.', $G_FileName);
                      $Photo = $NPM.'.'.$ff[1];
                      if (file_exists($this->path_upload_regOnline.$Email.'/'.$explode[0])) {
                        copy($this->path_upload_regOnline.$Email.'/'.$explode[0], './uploads/students/'.$ta.'/'.$Photo);
                      }

                    }
                  }

                // if ($getDoc[$z]['Status'] == 'Done') {
                  if (count($explode) > 0) {
                    for ($ee=0; $ee < count($explode); $ee++) {
                     if (file_exists($this->path_upload_regOnline.$Email.'/'.$explode[$ee])) {
                      copy($this->path_upload_regOnline.$Email.'/'.$explode[$ee], './uploads/document/'.$NPM.'/'.$explode[$ee]);
                      // unlink($this->path_upload_regOnline.$Email.'/'.$explode[$ee]);
                     }

                    }
                  }
                  else
                  {
                    if (file_exists($this->path_upload_regOnline.$Email.'/'.$getDoc[$z]['Attachment'])) {
                      copy($this->path_upload_regOnline.$Email.'/'.$getDoc[$z]['Attachment'], './uploads/document/'.$NPM.'/'.$getDoc[$z]['Attachment']);
                      // unlink($this->path_upload_regOnline.$Email.'/'.$getDoc[$z]['Attachment']);
                    }

                  }

                  // if (file_exists($this->path_upload_regOnline.$Email.'/'.$getDoc[$z]['Attachment'])) {
                  //     unlink($this->path_upload_regOnline.$Email.'/'.$getDoc[$z]['Attachment']);
                  // }
                // }
              }

              $dataSave = array(
                  'NPM' => $NPM,
                  'ID_reg_doc_checklist' => $getDoc[$z]['ID_reg_doc_checklist'],
                  'Status' => $getDoc[$z]['Status'],
                  'Attachment' => $getDoc[$z]['Attachment'],
                  'Description' => $getDoc[$z]['Description'],
                  'VerificationBY' => $getDoc[$z]['VerificationBY'],
                  'VerificationAT' => $getDoc[$z]['VerificationAT'],
              );
              $this->db->insert('db_admission.doc_mhs', $dataSave);
            }

            $temp = array(
                        'ProdiID' => $ProdiID,
                        'ProgramID' => $ProgramID,
                        'LevelStudyID' => $LevelStudyID,
                        'ReligionID' => $ReligionID,
                        'NationalityID' => $NationalityID,
                        'ProvinceID' => $ProvinceID,
                        'CityID' => $CityID,
                        'HighSchoolID' => $HighSchoolID,
                        'HighSchool' => $HighSchool,
                        'MajorsHighSchool' => $MajorsHighSchool,
                        'NPM' => $NPM,
                        'Name' => $Name,
                        'Address' => $Address,
                        'Gender' => $Gender,
                        'PlaceOfBirth' => $PlaceOfBirth,
                        'DateOfBirth' => $DateOfBirth,
                        'Phone' => $Phone,
                        'HP' => $HP,
                        'ClassOf' => $ClassOf,
                        'Email' => $Email,
                        'Jacket' => $Jacket,
                        'AnakKe' => $AnakKe,
                        'JumlahSaudara' => $JumlahSaudara,
                        'NationExamValue' => $NationExamValue,
                        'GraduationYear' => $GraduationYear,
                        'IjazahNumber' => $IjazahNumber,
                        'Father' => $Father,
                        'Mother' => $Mother,
                        'StatusFather' => $StatusFather,
                        'StatusMother' => $StatusMother,
                        'PhoneFather' => $PhoneFather,
                        'PhoneMother' => $PhoneMother,
                        'OccupationFather' => $OccupationFather,
                        'OccupationMother' => $OccupationMother,
                        'EducationFather' => $EducationFather,
                        'EducationMother' => $EducationMother,
                        'AddressFather' => $AddressFather,
                        'AddressMother' => $AddressMother,
                        'EmailFather' => $EmailFather,
                        'EmailMother' => $EmailMother,
                        'StatusStudentID' => $StatusStudentID,
                        'Photo' => $Photo
                      );

            $this->db->insert($ta.'.students', $temp);

            // $arr[] = $temp;

            $plan_password = $NPM.''.'123456';
            $pas = md5($plan_password);
            $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

            $pasword_old = $DateOfBirth;
            $d = explode('-', $pasword_old);
            $pasword_old = $d[2].$d[1].substr($d[0], 2,2);

            $temp2 = array(
                'NPM' => $NPM,
                'Password' => $pass,
                'Password_Old' => md5($pasword_old),
                'Year' => $YearAuth,
                'EmailPU' => $NPM.'@podomorouniversity.ac.id',
                'StatusStudentID' => 3,
                'Status' => '-1',
                'Name' => $Name,
                'ProdiID' => $ProdiID,
                'ProgramID' => 1,
                'KTPNumber' => $KTPNumber,
            );

            $arr_insert_auth[] = $temp2;


                      $temp2['DateOfBirth'] = $temp['DateOfBirth'];
                      $temp2['Address'] = $temp['Address'];
                      $temp2['Email'] = $temp['Email'];

                      $arr_insert_library[] = $temp2;
            $temp3 = array(
                'NPM' => $NPM,
                'ProgramID' => 1,
                'ProdiID' => $ProdiID,
                'Year' => $YearAuth,
                'Password' => $pass,
                'Password_Old' => md5($pasword_old),
                'FatherName' => $Father,
                'MotherName' => $Mother,
                'StatusStudentID' => 3,
                'Status' => '-1',
            );

            $arr_insert3[] = $temp3;

            $dataSave = array(
                'NPM' => $NPM,
                'FormulirCode' => $data2[0]['FormulirCode'],
                'DateTime' => date('Y-m-d H:i:s'),
                'GeneratedBy' => $this->session->userdata('NIP'),
            );
            $this->db->insert('db_admission.to_be_mhs', $dataSave);

            // store arr AD
            $data_arr[] = array(
              'Name' => $Name,
              'NIM' => $NPM,
              'Password' => $pasword_old,
              'description' => $Q_Prodi[0]['Name'] ,
            );

            //move payment
              $Semester = $input['Semester'];
              $Semester = explode('.', $Semester);
              $SemesterID = $Semester[0];

              // get payment
                 $getPaymentAdmisi = $this->m_master->caribasedprimary('db_finance.payment_admisi','ID_register_formulir',$arrInputID[$i]);
                 $PayFee = $this->m_master->caribasedprimary('db_finance.payment_pre','ID_register_formulir',$arrInputID[$i]);
                 $hitung = 0;
                 for ($x=0; $x < count($PayFee); $x++) {
                   $InvoiceP = $PayFee[$x]['Invoice'];
                   if ($PayFee[$x]['Status'] == 1) {
                     $hitung = $hitung + $InvoiceP;
                   }

                 }
                 for ($z=0; $z < count($getPaymentAdmisi); $z++) {
                     $Invoice = $getPaymentAdmisi[$z]['Pay_tuition_fee'];

                     $dataSave = array(
                         'NPM' => $NPM,
                         'PTID' => $getPaymentAdmisi[$z]['PTID'],
                         'SemesterID' => $SemesterID,
                         'Invoice' => $Invoice,
                         'Discount' => $getPaymentAdmisi[$z]['Discount'],
                         'Status' => '1',
                     );
                     $this->db->insert('db_finance.payment', $dataSave);
                     $insert_id = $this->db->insert_id();

                     // insert to m_tuition_fee
                     $this->m_master->insert_m_tuition_fee($NPM,$getPaymentAdmisi[$z]['PTID'],$ProdiID,$YearAuth,$Invoice,$getPaymentAdmisi[$z]['Discount']);


                     // cek lunas atau tidak
                     if ($hitung >= $Invoice) {
                       $dataSave = array(
                           'ID_payment' => $insert_id,
                           'Invoice' => $Invoice,
                           'BilingID' => 0,
                           'Status' => 1,
                       );
                       $this->db->insert('db_finance.payment_students', $dataSave);

                       $hitung = $hitung - $Invoice;
                     }
                     else
                     {
                        if ($hitung > 0) {
                         $dataSave = array(
                           'ID_payment' => $insert_id,
                           'Invoice' => $hitung,
                           'BilingID' => 0,
                           'Status' => 1,
                         );
                         $this->db->insert('db_finance.payment_students', $dataSave);

                         $Sisa =  $Invoice - $hitung;
                         $dataSave = array(
                           'ID_payment' => $insert_id,
                           'Invoice' => $Sisa,
                           'BilingID' => 0,
                           'Status' => 0,
                         );
                         $this->db->insert('db_finance.payment_students', $dataSave);
                         $hitung = 0;
                        }
                        else
                        {
                          $dataSave = array(
                            'ID_payment' => $insert_id,
                            'Invoice' => $Invoice,
                            'BilingID' => 0,
                            'Status' => 0,
                          );
                          $this->db->insert('db_finance.payment_students', $dataSave);
                        }
                     }

                  }

                  $text = 'Dear '.$Name.',<br><br>
                              Congarulations, You were admitted to Podomoro University,<br>
                              Your Nim is '.$NPM.'.<br><br>
                              For Details, Please open your portal '.url_sign_out.' and Portal Library '.url_library.' with :<br>
                              Username : '.$NPM.'<br>
                              Password : '.$pasword_old.'<br><br>
                          ';
                  $to = $Email;
                  $subject = "Podomoro University Registration";
                  $ServerName = $_SERVER['SERVER_NAME'];
                  if ($ServerName == 'pcam.podomorouniversity.ac.id') {
                    $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                  }

            $aa++;
          }
          // print_r($arr);
          // die();

          // $this->db->insert_batch($ta.'.students', $arr);
          $this->db->insert_batch('db_academic.auth_students', $arr_insert_auth);
          $this->db->insert_batch('db_academic.auth_parents', $arr_insert3);
          if($_SERVER['SERVER_NAME']=='pcam.podomorouniversity.ac.id') {
          // if(true) {
            $this->m_admission->insert_to_Library($arr_insert_library);

            // insert to AD
            $data = array(
                'auth' => 's3Cr3T-G4N',
                'Type' => 'Student',
                'data_arr' => $data_arr,
            );

            $url = URLAD.'__api/Create';
            $token = $this->jwt->encode($data,"UAP)(*");
            $this->m_master->apiservertoserver_NotWaitResponse($url,$token);

          }

          // send notif
            $data = array(
                'auth' => 's3Cr3T-G4N',
                'Logging' => array(
                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Generate Student',
                                'Description' => 'Admission has been Generate Student',
                                'URLDirect' => 'database/students/'.$YearAuth,
                                'CreatedBy' => $this->session->userdata('NIP'),
                              ),
                'To' => array(
                          'Div' => array(6,12),
                        ),
                'Email' => 'No',
            );

            $url = url_pas.'rest2/__send_notif_browser';
            $token = $this->jwt->encode($data,"UAP)(*");
            $this->m_master->apiservertoserver($url,$token);

            // send to admission dengan url yang berbeda
              $data['Logging']['URLDirect'] = 'admission/master-calon-mahasiswa/data-mahasiswa';
              $data['To']['Div'] = array(10);
              $token = $this->jwt->encode($data,"UAP)(*");
              $this->m_master->apiservertoserver($url,$token);

          echo json_encode($msg);
    }

    public function importFormulirManual()
    {
      include APPPATH.'third_party/PHPExcel/PHPExcel.php';
      $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
      $excel2 = $excel2->load('report_penjualan_data_18-10-09.xlsx'); // Empty Sheet
      $objWorksheet = $excel2->setActiveSheetIndex(0);
      $CountRow = $objWorksheet->getHighestRow();

       $arr_bulan = array(
           'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Des'
       );

       $arr_temp = array();
      for ($i=5; $i < ($CountRow + 1); $i++) {
        $FormulirCode = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
        // print_r($FormulirCode.'<br>');
        $Tanggal = $objWorksheet->getCellByColumnAndRow(1, $i)->getCalculatedValue();
        $Tanggal = explode(" ", $Tanggal);
        for ($k=0; $k < count($arr_bulan); $k++) {
          $month = $Tanggal[1];
          if ($arr_bulan[$k] == $month) {
            $k++;
            break;
          }
        }

        if (strlen($k) == 1) {
          $k = '0'.$k;
        }

        $date = $Tanggal[2].'-'.$k.'-'.$Tanggal[0];
        $getNIP = $this->m_master->getAllUserAutoComplete($objWorksheet->getCellByColumnAndRow(2, $i)->getCalculatedValue());

        try {
          if (count($getNIP) > 0) {
            $NIP = $getNIP[0]['NIP'];
          }
          else
          {
            $NIP = $objWorksheet->getCellByColumnAndRow(2, $i)->getCalculatedValue();
          }

        }
        //catch exception
        catch(Exception $e) {
          $NIP = $objWorksheet->getCellByColumnAndRow(2, $i)->getCalculatedValue();
        }

        $program_study = $this->m_master->caribasedprimary('db_academic.program_study','Name',$objWorksheet->getCellByColumnAndRow(5, $i)->getCalculatedValue());
        $program_study2 = $this->m_master->caribasedprimary('db_academic.program_study','Name',$objWorksheet->getCellByColumnAndRow(6, $i)->getCalculatedValue());


        $skool1 = $objWorksheet->getCellByColumnAndRow(10, $i)->getCalculatedValue();
        $skool = trim(str_replace("SMA","", $skool1));
        $skool = trim(str_replace("SMK","", $skool));
        $sql = 'select * from db_admission.school where SchoolName like "%'.$skool.'%"';
        $query=$this->db->query($sql, array())->result_array();

        $aa = $skool1;
        if (count($query) > 0) {
          $SchoolID = $query[0]['ID'];
        }
        else
        {
          $city = $objWorksheet->getCellByColumnAndRow(11, $i)->getCalculatedValue();
          $CityName = $objWorksheet->getCellByColumnAndRow(11, $i)->getCalculatedValue();
          $CityID ='';

          $sql2 = 'select * from db_admission.region where RegionName like "%'.$city.'%"';
          $query2=$this->db->query($sql2, array())->result_array();
          if (count($query2) > 0) {
            $CityID =$query2[0]['RegionID'];
          }
          else
          {
            $city2 = str_replace('Kabupaten', 'Kab.', $city);
            $sql2 = 'select * from db_admission.region where RegionName like "%'.$city2.'%"';
            $query2=$this->db->query($sql2, array())->result_array();
            if (count($query2) > 0) {
              $CityID =$query2[0]['RegionID'];
            }
            else
            {

            }

          }

          $ProvinceID = $this->m_master->caribasedprimary('db_admission.province_region','RegionID',$CityID);
          $ProvinceID = $ProvinceID[0]['ProvinceID'];
          $ProvinceName = $this->m_master->caribasedprimary('db_admission.province','ProvinceID',$ProvinceID);
          $ProvinceName = $ProvinceName[0]['ProvinceName'];

          $dataSave = array(
              'ProvinceID' => $ProvinceID,
              'ProvinceName' => $ProvinceName,
              'CityID' => $CityID,
              'CityName' => $CityName,
              'DistrictID' => '',
              'DistrictName' => '',
              'SchoolType' => '',
              'SchoolName' => $skool1,
              'SchoolAddress' => '',
              'Created' => 0,
              'Approved' => 1,
              'Approver' => 0,
          );
          // print_r($dataSave);
          // print_r('<br>');
          $this->db->insert('db_admission.school', $dataSave);
          $SchoolID = $this->db->insert_id();
        }

        $Channel = 'Event';
        $Iklan = trim($objWorksheet->getCellByColumnAndRow(12, $i)->getCalculatedValue());
        $aa = $this->m_master->caribasedprimary('db_admission.source_from_event','src_name',$Iklan);
        $sqlaa = 'select * from db_admission.source_from_event where src_name like "'.$Iklan.'%" ';
        $queryaa=$this->db->query($sqlaa, array())->result_array();

        $SchoolIDChanel = '';
        if ($Iklan == 'SEKOLAH') {
          $Channel = 'School';
          $SchoolIDChanel = $SchoolID;
        }
        // // print_r($sqlaa.'<br>');
        // if ($i == 10) {
        //   die();
        // }
        if(count($queryaa) > 0)
        {
          $source_from_event_ID = $queryaa[0]['ID'];
        }
        else
        {
          $source_from_event_ID = 0;
        }

        $sql3 = 'select * from db_admission.formulir_number_offline_m where StatusJual = 0 order by ID asc';
        $query3=$this->db->query($sql3, array())->result_array();
        $No_Ref = $FormulirCode;
        $FormulirCode = $query3[0]['FormulirCode'];

        // check $No_Ref Unique
        $sql23 = 'select * from db_admission.formulir_number_offline_m where No_Ref = ?';
        $query23=$this->db->query($sql23, array($No_Ref))->result_array();

        if(count($query23) > 0)
        {
          continue;
        }

        $temp = array(
            'FormulirCodeOffline' => $FormulirCode,
            'DateSale' => $date,
            'PIC' => $NIP,
            'FullName' => $objWorksheet->getCellByColumnAndRow(3, $i)->getCalculatedValue(),
            'Gender' => ($objWorksheet->getCellByColumnAndRow(4, $i)->getCalculatedValue() == 'Female') ? 'P' : 'L',
            'ID_ProgramStudy' => $program_study[0]['ID'] ,
            'ID_ProgramStudy2' => (count($program_study2) > 0) ? $program_study2[0]['ID'] : 0,
            'HomeNumber' => $objWorksheet->getCellByColumnAndRow(7, $i)->getCalculatedValue(),
            'PhoneNumber' => $objWorksheet->getCellByColumnAndRow(8, $i)->getCalculatedValue(),
            'Email' => $objWorksheet->getCellByColumnAndRow(9, $i)->getCalculatedValue(),
            'SchoolID' => $SchoolID,
            'Channel' => $Channel,
            'price_event_ID' => '',
            'source_from_event_ID' => $source_from_event_ID,
            'SchoolIDChanel' => $SchoolID,
            'Price_Form' => 150000,
        );
        $this->db->insert('db_admission.sale_formulir_offline', $temp);
        $dataSave = array(
                'No_Ref' => $No_Ref,
                'StatusJual' => 1,
                'Print' => 1
                        );
        $this->db->where('FormulirCode',$FormulirCode);
        $this->db->update('db_admission.formulir_number_offline_m', $dataSave);

      }
      // $this->db->insert_batch('db_admission.sale_formulir_offline', $arr_temp);
      // print_r($arr_temp);

    }

    public function ImportupdateNoKwitansi()
    {
      include APPPATH.'third_party/PHPExcel/PHPExcel.php';
            $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
            $excel2 = $excel2->load('report_penjualan_data_18-10-09_KWITANSI.xlsx'); // Empty Sheet
            $objWorksheet = $excel2->setActiveSheetIndex(0);
            $CountRow = $objWorksheet->getHighestRow();
      for ($i=2; $i < ($CountRow + 1); $i++) {
        $No_Ref = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
        $NoKwitansi = $objWorksheet->getCellByColumnAndRow(13, $i)->getCalculatedValue();
        $CreatedBY = $objWorksheet->getCellByColumnAndRow(14, $i)->getCalculatedValue();
        // print_r($CreatedBY);die();
        $get = $this->m_master->caribasedprimary('db_admission.formulir_number_offline_m','No_Ref',$No_Ref);
        if (count($get) > 0) {
          $FormulirCodeOffline = $get[0]['FormulirCode'];
          $dataSave = array(
              'NoKwitansi' => $NoKwitansi,
              'CreatedBY' => $CreatedBY,
          );
          $this->db->where('FormulirCodeOffline', $FormulirCodeOffline);
          $this->db->update('db_admission.sale_formulir_offline', $dataSave);
        }
      }
    }

    public function LoadListPenjualanoffline()
    {
      $this->auth_ajax();
      $arr_result = array('html' => '','jsonPass' => '');
      $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/distribusi_formulir/LoadListPenjualanoffline',$this->data,true);
      echo json_encode($arr_result);
    }

    public function LoadListPenjualanoffline_serverSide()
    {
      $requestData= $_REQUEST;
      $reqTahun = $this->input->post('tahun');
      $StatusJual = $this->input->post('StatusJual');
      $No = $requestData['start'] + 1;
      $totalData = $this->m_admission->totalDataFormulir_offline4($reqTahun,$requestData,$StatusJual);

      if($StatusJual != '%') {
        $StatusJual = ' and b.StatusJual = '.$StatusJual;
      }
      else
      {
        $StatusJual = '';
      }

      // check session division
      $PositionMain = $this->session->userdata('PositionMain');
      $division = $PositionMain['IDDivision'];
        $queryDiv = "";
        switch ($division) {
          case 10:
            // $queryDiv = ' where LEFT(c.PositionMain ,INSTR(c.PositionMain ,".")-1) = "'.$division.'"';
            $queryDiv = '';
            break;
          case 18:
            // $queryDiv = ' where LEFT(c.PositionMain ,INSTR(c.PositionMain ,".")-1) = "'.$division.'"';
            $queryDiv = '';
            break;
          default:
            $queryDiv = "";
            break;
        }

      $sql = 'select a.NameCandidate,a.Email,a.SchoolName,b.FormulirCode,b.No_Ref,a.StatusReg,b.Years,b.Status as StatusUsed, b.StatusJual,
                b.FullName as NamaPembeli,b.PhoneNumber as PhoneNumberPembeli,b.HomeNumber as HomeNumberPembeli,b.Email as EmailPembeli,b.Sales,b.PIC as SalesNIP,b.SchoolNameFormulir,b.CityNameFormulir,b.DistrictNameFormulir,b.TypePay,
                b.ID as ID_sale_formulir_offline,b.Price_Form,b.DateSale,b.src_name,b.NameProdi,b.NoKwitansi,b.Link,b.DateFin
                from (
                select a.Name as NameCandidate,a.Email,z.SchoolName,c.FormulirCode,a.StatusReg
                from db_admission.register as a
                join db_admission.register_verification as b
                on a.ID = b.RegisterID
                join db_admission.register_verified as c
                on c.RegVerificationID = b.ID
                join db_admission.school as z
                on z.ID = a.SchoolID
                where a.StatusReg = 1
                ) as a right JOIN
                (
                select a.FormulirCode,a.No_Ref,a.Years,a.Status,a.StatusJual,b.FullName,b.HomeNumber,b.PhoneNumber,b.DateSale,b.NoKwitansi,b.DateFin,
                b.Email,c.Name as Sales,b.PIC,b.ID,b.Price_Form,z.SchoolName as SchoolNameFormulir,z.CityName as  CityNameFormulir,z.DistrictName as DistrictNameFormulir,b.TypePay,a.Link,
                if(b.source_from_event_ID = 0,"", (select src_name from db_admission.source_from_event where ID = b.source_from_event_ID and Active = 1 limit 1) ) as src_name,b.ID_ProgramStudy,y.Name as NameProdi
                from db_admission.formulir_number_offline_m as a
                left join db_admission.sale_formulir_offline as b
                on a.FormulirCode = b.FormulirCodeOffline
                left join db_employees.employees as c
                on c.NIP = b.PIC
                left join db_admission.school as z
                on z.ID = b.SchoolID
                left join db_academic.program_study as y
                on b.ID_ProgramStudy = y.ID
                '.$queryDiv.'
                )
                as b
                on a.FormulirCode = b.FormulirCode
          ';

      $sql.= 'where Years = "'.$reqTahun.'" and
                      (
                        b.FormulirCode like "'.$requestData['search']['value'].'%" or
                        b.No_Ref like "'.$requestData['search']['value'].'%" or
                        b.Sales like "'.$requestData['search']['value'].'%" or
                        a.NameCandidate like "'.$requestData['search']['value'].'%" or
                        b.SchoolNameFormulir like "%'.$requestData['search']['value'].'%" or
                        b.NameProdi like "'.$requestData['search']['value'].'%" or
                        b.src_name like "'.$requestData['search']['value'].'%" or
                        b.FullName like "'.$requestData['search']['value'].'%" or
                        b.DateSale like "'.$requestData['search']['value'].'%" or
                        b.NoKwitansi like "'.$requestData['search']['value'].'%"
                      ) '.$StatusJual.'';
      $sql.= ' order by b.NoKwitansi desc LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

      $query = $this->db->query($sql)->result_array();

      // cek semester year tahun 2019 aktif untuk hide action
         $semester = $this->m_master->caribasedprimary('db_academic.semester','Status',1);
         $semesterYear = $semester[0]['Year'];

      $data = array();
      for($i=0;$i<count($query);$i++){
          $nestedData=array();
          $row = $query[$i];
          $nestedData[] = $No;
          $nestedData[] = $row['FormulirCode'];
          $nestedData[] = $row['No_Ref'];
          $nestedData[] = $row['NoKwitansi'];
          $nestedData[] = $row['NameProdi'];
          $aa = ($row['StatusUsed'] == 0) ? '<div style="color:  green;">No</div>' : '<div style="color:  red;">Yes</div>';
          $nestedData[] = $aa;
          $aa = ($row['StatusJual'] == 0) ? '<div style="color:  green;">IN</div>' : '<div style="color:  red;">Sold Out</div>';
          $nestedData[] = $aa;
          $nestedData[] = $row['Sales'];
          $nestedData[] = number_format($row['Price_Form'],0,',','.');
          $nestedData[] = $row['DateSale'];
          $nestedData[] = $row['NamaPembeli'].'<br>'.$row['PhoneNumberPembeli'].'<br>'.$row['EmailPembeli'].'<br>'.$row['SchoolNameFormulir'].'<br>'.$row['DistrictNameFormulir'].' '.$row['CityNameFormulir'];
          $nestedData[] = $row['src_name'];
          $action = '';
          $hide = '';
          $cek = $this->m_master->caribasedprimary('db_admission.register_verified','FormulirCode',$row['FormulirCode']);
            if (count($cek) > 0) {
              $hide = 'hide';
            }
            if ($semesterYear >= $reqTahun) {
              $hide = 'hide';
            }

            // auth by finance
              $hide2 = '';
              $DateFin = $row['DateFin'];
              if ($DateFin != '' && $DateFin != null && $DateFin != '0000-00-00') {
                $hide2 = 'hide';
              }

          if ($row['ID_sale_formulir_offline'] != null || $row['ID_sale_formulir_offline'] != '')
          {
            $action = '<div class="row '.$hide.'">
                        <div class="col-md-12">
                          <span data-smt="'.$row['Link'].'" class="btn btn-xs btn-delete inputFormulir">
                            <i class="fa fa-sign-in right-margin"></i> Input Formulir
                          </span>
                        </div>
                      </div>
                      <div class="row '.$hide.' '.$hide2.'" style="margin-top: 10px">
                        <div class="col-md-12">
                          <span data-smt="'.$row['ID_sale_formulir_offline'].'" class="btn btn-xs btn-delete deletepenjualan">
                            <i class="fa fa-trash"></i> Delete Penjualan
                          </span>
                        </div>
                      </div>
                      <div class="row" style="margin-top: 10px">
                        <div class="col-md-12">
                          <span ref = "'.$row['No_Ref'].'" NamaLengkap = "'.$row['NamaPembeli'].'" class="btn btn-xs btn-print" phonehome = "'.$row['HomeNumberPembeli'].'" hp = "'.$row['PhoneNumberPembeli'].'" jurusan = "'.$row['NameProdi'].'" pembayaran ="Pembelian Formulir Pendaftaran('.$row['NameProdi'].')" jenis= "'.$row['TypePay'].'" jumlah = "'.$row['Price_Form'].'" date = "'.$row['DateSale'].'" formulir = "'.$row['FormulirCode'].'" NoKwitansi = "'.$row['NoKwitansi'].'">
                           <i class="fa fa-print"></i> Kwitansi
                         </span>
                        </div>
                      </div>
                      <div class="row '.$hide.' '.$hide2.'" style="margin-top: 10px">
                        <div class="col-md-12">
                          <span data-smt="'.$row['ID_sale_formulir_offline'].'" class="btn btn-xs btn-edit">
                            <i class="fa fa-edit"></i> Edit Penjualan
                          </span>
                        </div>
                      </div>
                      ';
          }

          $nestedData[] = $action;
          $nestedData[] = $row['Link'];
          $data[] = $nestedData;
          $No++;
      }

      $json_data = array(
          "draw"            => intval( $requestData['draw'] ),
          "recordsTotal"    => intval($totalData),
          "recordsFiltered" => intval($totalData ),
          "data"            => $data
      );
      echo json_encode($json_data);
    }

    public function LoadInputPenjualanoffline()
    {
      $this->auth_ajax();
      $arr_result = array('html' => '','jsonPass' => '');
      $input = $this->getInputToken();
      $this->data['action'] = $input['action'];
      $this->data['CDID'] = $input['ID'];
      $Ta = $this->m_master->showData_array('db_admission.set_ta');
      $this->data['Ta'] = $Ta[0]['Ta'];
      switch ($input['action']) {
        case 'add':
          # code...
          break;
        case 'edit':
          $get1 = $this->m_master->caribasedprimary('db_admission.sale_formulir_offline','ID',$input['ID']);
          $get2 = $this->m_master->caribasedprimary('db_admission.formulir_number_offline_m','FormulirCode',$get1[0]['FormulirCodeOffline']);
          $this->data['get1'] = $get1;
          $this->data['get2'] = $get2;
          $get3 = $this->m_master->caribasedprimary('db_admission.school','ID',$get1[0]['SchoolID']);
          $get4 = $this->m_master->caribasedprimary('db_admission.school','ID',$get1[0]['SchoolIDChanel']);

          $getPICName = $this->m_master->caribasedprimary('db_employees.employees','NIP',$get1[0]['PIC']);

          $this->data['get3'] = (count($get3) > 0 ) ? $get3 : $get3 = array(array('SchoolName' => ''));
          $this->data['get4'] = (count($get4) > 0 ) ? $get4 : $get4 = array(array('SchoolName' => ''));
          $this->data['PICName'] = (count($getPICName) > 0 ) ? $getPICName[0] : $getPICName = array('Name' => '');
          break;
        default:
          # code...
          break;
      }
//      $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/distribusi_formulir/LoadInputPenjualanoffline',$this->data,true);
      $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/distribusi_formulir/LoadInputPenjualanoffline2',$this->data,true);
      echo json_encode($arr_result);
    }

    public function LoadImportInputPenjualan()
    {
      $this->auth_ajax();
      $arr_result = array('html' => '','jsonPass' => '');
      $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/distribusi_formulir/LoadImportInputPenjualan',$this->data,true);
      echo json_encode($arr_result);
    }

    public function submit_import_excel_penjualan_formulir_offline()
    {
      // print_r($_FILES);
      if(isset($_FILES["fileData"]["name"]))
      {
        $path = $_FILES["fileData"]["tmp_name"];
        $arr_insert = array();
        $arr_insert_auth = array();
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load($path); // Empty Sheet
        $objWorksheet = $excel2->setActiveSheetIndex(0);
        $CountRow = $objWorksheet->getHighestRow();

         $arr_bulan = array(
             'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Des'
         );

         $arr_temp = array();
         $No_Ref = '';
        for ($i=5; $i < ($CountRow + 1); $i++) {
          $FormulirCode = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
          if ($FormulirCode == "" || $FormulirCode == null) {
            break;
          }

          $No_Ref = $FormulirCode;
          // check $No_Ref Unique
          $sql23 = 'select * from db_admission.formulir_number_offline_m where No_Ref = ?';
          $query23=$this->db->query($sql23, array($No_Ref))->result_array();

          if(count($query23) > 0)
          {
            continue;
          }

          // print_r($FormulirCode.'<br>');
          $Tanggal = $objWorksheet->getCellByColumnAndRow(1, $i)->getCalculatedValue();
          $date =  date('Y-m-d', strtotime($Tanggal));
          // $Tanggal = explode(" ", $Tanggal);
          // for ($k=0; $k < count($arr_bulan); $k++) {
          //   $month = $Tanggal[1];
          //   if ($arr_bulan[$k] == $month) {
          //     $k++;
          //     break;
          //   }
          // }

          // if (strlen($k) == 1) {
          //   $k = '0'.$k;
          // }

          // $date = $Tanggal[2].'-'.$k.'-'.$Tanggal[0];
          $getNIP = $this->m_master->getAllUserAutoComplete($objWorksheet->getCellByColumnAndRow(2, $i)->getCalculatedValue());

          try {
            if (count($getNIP) > 0) {
              $NIP = $getNIP[0]['NIP'];
            }
            else
            {
              $NIP = $objWorksheet->getCellByColumnAndRow(2, $i)->getCalculatedValue();
            }

          }
          //catch exception
          catch(Exception $e) {
            $NIP = $objWorksheet->getCellByColumnAndRow(2, $i)->getCalculatedValue();
          }

          $Jurusan1 = $objWorksheet->getCellByColumnAndRow(5, $i)->getCalculatedValue();
          if (strpos($Jurusan1, 'Managemen dan Rekayasa') !== false) {
              $Jurusan1 = 'Manajemen Rekayasa dan Konstruksi';
          }

          $Jurusan2 = $objWorksheet->getCellByColumnAndRow(6, $i)->getCalculatedValue();
          if (strpos($Jurusan2, 'Managemen dan Rekayasa') !== false) {
              $Jurusan2 = 'Manajemen Rekayasa dan Konstruksi';
          }

          $program_study = $this->m_master->caribasedprimary('db_academic.program_study','Name',$Jurusan1);
          $program_study2 = $this->m_master->caribasedprimary('db_academic.program_study','Name',$Jurusan2);


          $skool1 = $objWorksheet->getCellByColumnAndRow(10, $i)->getCalculatedValue();
          $skool = trim(str_replace("SMA","", $skool1));
          $skool = trim(str_replace("SMK","", $skool));
          $sql = 'select * from db_admission.school where SchoolName like "%'.$skool.'%"';
          $query=$this->db->query($sql, array())->result_array();

          $aa = $skool1;
          if (count($query) > 0) {
            $SchoolID = $query[0]['ID'];
          }
          else
          {
            $city = $objWorksheet->getCellByColumnAndRow(11, $i)->getCalculatedValue();
            $CityName = $objWorksheet->getCellByColumnAndRow(11, $i)->getCalculatedValue();
            $CityID ='';

            $sql2 = 'select * from db_admission.region where RegionName like "%'.$city.'%"';
            $query2=$this->db->query($sql2, array())->result_array();
            if (count($query2) > 0) {
              $CityID =$query2[0]['RegionID'];
            }
            else
            {
              $city2 = str_replace('Kabupaten', 'Kab.', $city);
              $sql2 = 'select * from db_admission.region where RegionName like "%'.$city2.'%"';
              $query2=$this->db->query($sql2, array())->result_array();
              if (count($query2) > 0) {
                $CityID =$query2[0]['RegionID'];
              }
              else
              {

              }

            }

            $ProvinceID = $this->m_master->caribasedprimary('db_admission.province_region','RegionID',$CityID);
            $ProvinceID = $ProvinceID[0]['ProvinceID'];
            $ProvinceName = $this->m_master->caribasedprimary('db_admission.province','ProvinceID',$ProvinceID);
            $ProvinceName = $ProvinceName[0]['ProvinceName'];

            $dataSave = array(
                'ProvinceID' => $ProvinceID,
                'ProvinceName' => $ProvinceName,
                'CityID' => $CityID,
                'CityName' => $CityName,
                'DistrictID' => '',
                'DistrictName' => '',
                'SchoolType' => '',
                'SchoolName' => $skool1,
                'SchoolAddress' => '',
                'Created' => 0,
                'Approved' => 1,
                'Approver' => 0,
            );
            // print_r($dataSave);
            // print_r('<br>');
            $this->db->insert('db_admission.school', $dataSave);
            $SchoolID = $this->db->insert_id();
          }

          $Channel = 'Event';
          $Iklan = trim($objWorksheet->getCellByColumnAndRow(12, $i)->getCalculatedValue());
          $aa = $this->m_master->caribasedprimary('db_admission.source_from_event','src_name',$Iklan);
          $sqlaa = 'select * from db_admission.source_from_event where src_name like "'.$Iklan.'%" ';
          $queryaa=$this->db->query($sqlaa, array())->result_array();

          $SchoolIDChanel = '';
          if ($Iklan == 'SEKOLAH') {
            $Channel = 'School';
            $SchoolIDChanel = $SchoolID;
          }
          // // print_r($sqlaa.'<br>');
          // if ($i == 10) {
          //   die();
          // }
          if(count($queryaa) > 0)
          {
            $source_from_event_ID = $queryaa[0]['ID'];
          }
          else
          {
            $source_from_event_ID = 0;
          }

          $sql3 = 'select * from db_admission.formulir_number_offline_m where StatusJual = 0 order by ID asc';
          $query3=$this->db->query($sql3, array())->result_array();
          $FormulirCode = $query3[0]['FormulirCode'];
          $FullName = strtolower($objWorksheet->getCellByColumnAndRow(3, $i)->getCalculatedValue()) ;
          $temp = array(
              'FormulirCodeOffline' => $FormulirCode,
              'DateSale' => $date,
              'PIC' => $NIP,
              'FullName' => ucwords( $FullName ) ,
              'Gender' => ($objWorksheet->getCellByColumnAndRow(4, $i)->getCalculatedValue() == 'Female') ? 'P' : 'L',
              'ID_ProgramStudy' => $program_study[0]['ID'] ,
              'ID_ProgramStudy2' => (count($program_study2) > 0) ? $program_study2[0]['ID'] : 0,
              'HomeNumber' => $objWorksheet->getCellByColumnAndRow(7, $i)->getCalculatedValue(),
              'PhoneNumber' => $objWorksheet->getCellByColumnAndRow(8, $i)->getCalculatedValue(),
              'Email' => strtolower($objWorksheet->getCellByColumnAndRow(9, $i)->getCalculatedValue() ),
              'SchoolID' => $SchoolID,
              'Channel' => $Channel,
              'price_event_ID' => '',
              'source_from_event_ID' => $source_from_event_ID,
              'SchoolIDChanel' => $SchoolID,
              'Price_Form' => 150000,
          );
          $this->db->insert('db_admission.sale_formulir_offline', $temp);
          $dataSave = array(
                  'No_Ref' => $No_Ref,
                  'StatusJual' => 1,
                  'Print' => 1
                          );
          $this->db->where('FormulirCode',$FormulirCode);
          $this->db->update('db_admission.formulir_number_offline_m', $dataSave);

        }

        echo json_encode(array('status'=> 1,'msg' => '','No_Ref' => $No_Ref));
      }
      else
      {
        exit('No direct script access allowed');
      }
    }

    public function submit_import_excel_kwitansi_penjualan_formulir_offline()
    {
      if(isset($_FILES["fileData"]["name"]))
      {
        $path = $_FILES["fileData"]["tmp_name"];
        $arr_insert = array();
        $arr_insert_auth = array();
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load($path); // Empty Sheet
        $objWorksheet = $excel2->setActiveSheetIndex(0);
        $CountRow = $objWorksheet->getHighestRow();
        $No_Ref = '';
      for ($i=5; $i < ($CountRow + 1); $i++) {
        $No_Ref = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
        if ($No_Ref == "" || $No_Ref == null) {
          break;
        }
        $NoKwitansi = $objWorksheet->getCellByColumnAndRow(13, $i)->getCalculatedValue();
        $CreatedBY = '0';
        // print_r($CreatedBY);die();
        $get = $this->m_master->caribasedprimary('db_admission.formulir_number_offline_m','No_Ref',$No_Ref);
        if (count($get) > 0) {
          $FormulirCodeOffline = $get[0]['FormulirCode'];
          $dataSave = array(
              'NoKwitansi' => $NoKwitansi,
              'CreatedBY' => $CreatedBY,
          );
          $this->db->where('FormulirCodeOffline', $FormulirCodeOffline);
          $this->db->update('db_admission.sale_formulir_offline', $dataSave);
        }
      }

        echo json_encode(array('status'=> 1,'msg' => '','No_Ref' => $No_Ref));
      }
      else
      {
        exit('No direct script access allowed');
      }
    }

    public function tutorial()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/tutorial/pagetutorial',$this->data,true);
      $this->temp($content);
    }

    public function submit_import_excel_pengembalian_formulir_offline()
    {
      if(isset($_FILES["fileData"]["name"]))
      {
        $path = $_FILES["fileData"]["tmp_name"];
        $arr_insert = array();
        $arr_insert_auth = array();
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load($path); // Empty Sheet
        $objWorksheet = $excel2->setActiveSheetIndex(0);
        $CountRow = $objWorksheet->getHighestRow();
        $No_RefWr = '';
        $arr_key_list_err = array();
      for ($i=6; $i < ($CountRow + 1); $i++) {
        $No_Ref = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
        if ($No_Ref == "" || $No_Ref == null) {
          break;
        }
        $No_RefWr = $No_Ref;
        // cari No Formulir
        $get = $this->m_master->caribasedprimary('db_admission.formulir_number_offline_m','No_Ref',$No_Ref);
        if(count($get) == 0){$arr_key_list_err[] = array('err' => 'Nomor Formulir tidak ditemukan','No_Ref' => $No_Ref);continue;}
        $FormulirCode = $get[0]['FormulirCode'];

        // find FormulirCode pada register_verified
        $chkFormulir = $this->m_master->caribasedprimary('db_admission.register_verified','FormulirCode',$FormulirCode);
        if (count($chkFormulir) > 0) {
          continue;
        }

        // NIM masih null
        $Tanggal = $objWorksheet->getCellByColumnAndRow(2, $i)->getCalculatedValue();
        $Tanggal = date('Y-m-d', strtotime($Tanggal));
        $Nama = $objWorksheet->getCellByColumnAndRow(3, $i)->getCalculatedValue();
        $Nama = strtolower($Nama);
        $Nama = ucwords($Nama);
        $Gender = $objWorksheet->getCellByColumnAndRow(4, $i)->getCalculatedValue();
        $Gender = (substr($Gender, 0,1) == 'M') ? 'L' : 'P';
        $TTLahir = $objWorksheet->getCellByColumnAndRow(5, $i)->getCalculatedValue();
        $TTLahir = explode(',', $TTLahir);
        $PlaceBirth = $TTLahir[0];
        $DateBirth = date('Y-m-d', strtotime($TTLahir[1]));
        $Religion = $objWorksheet->getCellByColumnAndRow(6, $i)->getCalculatedValue();
        $get = $this->m_master->caribasedprimary('db_admission.agama','Nama',$Religion);
        if(count($get) == 0){$arr_key_list_err[] = array('err' => 'Agama tidak ditemukan','No_Ref' => $No_Ref);continue;}
        $ReligionID = $get[0]['ID'];
        $Nationality = $objWorksheet->getCellByColumnAndRow(7, $i)->getCalculatedValue();
        $get = $this->m_master->caribasedprimary('db_admission.country','ctr_name',$Nationality);
        if(count($get) == 0){$arr_key_list_err[] = array('err' => 'Negara tidak ditemukan','No_Ref' => $No_Ref);continue;}
        $NationalityID = $get[0]['ctr_code'];
        $Jurusan1 = $objWorksheet->getCellByColumnAndRow(8, $i)->getCalculatedValue();
        if (strpos($Jurusan1, 'Managemen dan Rekayasa') !== false) {
            $Jurusan1 = 'Manajemen Rekayasa dan Konstruksi';
        }
        $get = $this->m_master->caribasedprimary('db_academic.program_study','Name',$Jurusan1);
        if(count($get) == 0){$arr_key_list_err[] = array('err' => 'Prodi tidak ditemukan','No_Ref' => $No_Ref);continue;}
        $ID_program_study = $get[0]['ID'];
        $Jurusan2 = '';
        $PhoneNumber = $objWorksheet->getCellByColumnAndRow(10, $i)->getCalculatedValue();
        $PhoneNumber2 = $objWorksheet->getCellByColumnAndRow(11, $i)->getCalculatedValue();
        $PhoneNumber = $PhoneNumber.' / '.$PhoneNumber2;
        $Email = $objWorksheet->getCellByColumnAndRow(12, $i)->getCalculatedValue();
        // if($Email == '' || $Email == null){$arr_key_list_err[] = array('err' => 'Email kosong','No_Ref' => $No_Ref);continue;}
        if($Email == '' || $Email == null){$Email = 'admission@podomorouniversity.ac.id';}
        $jacket_size = $objWorksheet->getCellByColumnAndRow(14, $i)->getCalculatedValue();
        $get = $this->m_master->caribasedprimary('db_admission.register_jacket_size_m','JacketSize',$jacket_size);
        // if(count($get) == 0){$arr_key_list_err[] = array('err' => 'Jacket Size tidak ditemukan','No_Ref' => $No_Ref);continue;}
        if(count($get) == 0)
        {
          $ID_register_jacket_size_m = 0;
        }
        else
        {
          $ID_register_jacket_size_m = $get[0]['ID'];
        }

        $Address = $objWorksheet->getCellByColumnAndRow(16, $i)->getCalculatedValue();
        $RTRW = $objWorksheet->getCellByColumnAndRow(17, $i)->getCalculatedValue();
        $Address = 'RT/RW '.$RTRW.', '.$Address;
        $region_Prov = $objWorksheet->getCellByColumnAndRow(18, $i)->getCalculatedValue();
        $region_Prov = explode('-', $region_Prov);;
        $region = $region_Prov[0];
        $region = str_replace('Kabupaten', 'Kab.', $region);
        $get = $this->m_master->caribasedprimary('db_admission.region','RegionName',$region);
        // if(count($get) == 0){$arr_key_list_err[] = array('err' => 'Region tidak ditemukan','No_Ref' => $No_Ref);continue;}
        if (count($get) > 0) {
          $ID_region = $get[0]['ID'];
        }
        else
        {
          $ID_region = 0;
        }

        if(array_key_exists(1,$region_Prov))
        {
          $Prov = $region_Prov[1];
        }
        else
        {
          $Prov = "";
        }

        $sql = 'select * from db_admission.province where ProvinceName like "%'.$Prov.'%" ';
        $get = $this->db->query($sql, array())->result_array();
        // if(count($get) == 0){$arr_key_list_err[] = array('err' => 'Provinsi tidak ditemukan','No_Ref' => $No_Ref);continue;}
        if(count($get) > 0)
        {
          $ID_province = $get[0]['ProvinceID'];
        }
        else
        {
          $ID_province = 0;
        }


        $skool1 = $objWorksheet->getCellByColumnAndRow(19, $i)->getCalculatedValue();
        $skool = trim(str_replace("SMA","", $skool1));
        $skool = trim(str_replace("SMAS","", $skool1));
        $skool = trim(str_replace("SMAN","", $skool1));
        $skool = trim(str_replace("SMK","", $skool));
        $skool = trim(str_replace("SMKN","", $skool));
        $skool = trim(str_replace("SMKS","", $skool));
        $sql = 'select * from db_admission.school where SchoolName like "%'.$skool.'%"';
        $query=$this->db->query($sql, array())->result_array();

        if (count($query) > 0) {
          $SchoolID = $query[0]['ID'];
        }
        else
        {
          $city = $objWorksheet->getCellByColumnAndRow(20, $i)->getCalculatedValue();
          $CityName = $objWorksheet->getCellByColumnAndRow(20, $i)->getCalculatedValue();
          $CityID ='';

          $sql2 = 'select * from db_admission.region where RegionName like "%'.$city.'%"';
          $query2=$this->db->query($sql2, array())->result_array();
          if (count($query2) > 0) {
            $CityID =$query2[0]['RegionID'];
          }
          else
          {
            $city2 = str_replace('Kabupaten', 'Kab.', $city);
            $sql2 = 'select * from db_admission.region where RegionName like "%'.$city2.'%"';
            $query2=$this->db->query($sql2, array())->result_array();
            if (count($query2) > 0) {
              $CityID =$query2[0]['RegionID'];
            }
            else
            {

            }

          }

          $ProvinceID = $this->m_master->caribasedprimary('db_admission.province_region','RegionID',$CityID);
          if (count($ProvinceID) > 0) {
            $ProvinceID = $ProvinceID[0]['ProvinceID'];
            $ProvinceName = $this->m_master->caribasedprimary('db_admission.province','ProvinceID',$ProvinceID);
            $ProvinceName = $ProvinceName[0]['ProvinceName'];
          }
          else
          {
            $ProvinceID = 0;
            $ProvinceName = '';
          }



          $dataSave = array(
              'ProvinceID' => $ProvinceID,
              'ProvinceName' => $ProvinceName,
              'CityID' => $CityID,
              'CityName' => $CityName,
              'DistrictID' => '',
              'DistrictName' => '',
              'SchoolType' => '',
              'SchoolName' => $skool1,
              'SchoolAddress' => '',
              'Created' => 0,
              'Approved' => 1,
              'Approver' => 0,
          );
          // print_r($dataSave);
          // print_r('<br>');
          $this->db->insert('db_admission.school', $dataSave);
          $SchoolID = $this->db->insert_id();
        }
        $ID_school_type = 1;
        $FatherName = strtolower($objWorksheet->getCellByColumnAndRow(29, $i)->getCalculatedValue() );
        $FatherStatus = ( $objWorksheet->getCellByColumnAndRow(30, $i)->getCalculatedValue() == 'Alive' ) ? 'Alive' : 'Died';
        $FatherPhoneNumber = $objWorksheet->getCellByColumnAndRow(31, $i)->getCalculatedValue();
        $Father_occupation = $objWorksheet->getCellByColumnAndRow(33, $i)->getCalculatedValue();
        $Father_ID_occupation = 0;
        if ($Father_occupation != '' || $Father_occupation != null) {
          $get = $this->m_master->caribasedprimary('db_admission.occupation','ocu_name',$Father_occupation);
          // if(count($get) == 0){$arr_key_list_err[] = array('err' => 'Jenis Pekerjaan Ayah tidak ditemukan','No_Ref' => $No_Ref);continue;}
          if(count($get) > 0)
          {
            $Father_ID_occupation = $get[0]['ocu_code'];
          }
          else
          {
            $Father_ID_occupation = 0;
          }

        }

        $FatherAddress = $objWorksheet->getCellByColumnAndRow(34, $i)->getCalculatedValue();


        $MotherName = strtolower($objWorksheet->getCellByColumnAndRow(35, $i)->getCalculatedValue());
        $MotherStatus = ( $objWorksheet->getCellByColumnAndRow(36, $i)->getCalculatedValue() == 'Alive' ) ? 'Alive' : 'Died';
        $MotherPhoneNumber = $objWorksheet->getCellByColumnAndRow(37, $i)->getCalculatedValue();
        $Mother_occupation = $objWorksheet->getCellByColumnAndRow(39, $i)->getCalculatedValue();
        $Mother_ID_occupation = 0;
        if ($Mother_occupation != '' || $Mother_occupation != null) {
          $get = $this->m_master->caribasedprimary('db_admission.occupation','ocu_name',$Mother_occupation);
          if(count($get) == 0){$arr_key_list_err[] = array('err' => 'Jenis Pekerjaan Ibu tidak ditemukan','No_Ref' => $No_Ref);continue;}

          $Mother_ID_occupation = $get[0]['ocu_code'];
        }
        $MotherAddress = $objWorksheet->getCellByColumnAndRow(40, $i)->getCalculatedValue();

        // save to db register
            // check priceForm dari sale_formulir_offline
            $get1234 = $this->m_master->caribasedprimary('db_admission.sale_formulir_offline','FormulirCodeOffline',$FormulirCode);
            $query23 = array();
            if (count($get1234) == 0) {
              $sql23 = "select PriceFormulir from db_admission.price_formulir_offline as a where a.active = 1 order by a.CreateAT desc limit 1";
              $query23=$this->db->query($sql23, array())->result_array();
            }
            else
            {
              $query23[0]['PriceFormulir'] = $get1234[0]['Price_Form'];
            }


            $sql33 = 'select * from db_admission.va_generate where VA_Status = 1 order by ID asc limit 1';
            $query33=$this->db->query($sql33, array())->result_array();
            if (count($query33) == 0) {
                $sql33 = 'select * from db_admission.va_generate where VA_Status = 3 order by ID asc limit 1';
                $query33=$this->db->query($sql33, array())->result_array();
            }

            $SetTa= $this->m_master->showData_array('db_admission.set_ta');
            $dataSave = array(
                    'Name' => $Nama,
                    'Email' => strtolower($Email),
                    'MomenUnix' => '',
                    'SchoolID' => $SchoolID,
                    'PriceFormulir' => $query23[0]['PriceFormulir'],
                    'VA_number' => $query33[0]['VA'],
                    'RegisterAT' => $Tanggal,
                    'StatusReg' => 1,
                    'SetTa' => $SetTa[0]['Ta']
                            );

            $this->db->insert('db_admission.register', $dataSave);
            $RegisterID = $this->db->insert_id();

            $sqlxx = "update db_admission.va_generate set VA_Status = 2 where VA = ? ";
            $queryxx=$this->db->query($sqlxx, array($query33[0]['VA']));

            $dataSave = array(
                    'RegisterID' => $RegisterID,
                    'FileUpload' => '',
                    'CreateAT' => date("Y-m-d"),
                            );

            $this->db->insert('db_admission.register_verification', $dataSave);
            $RegVerificationID = $this->db->insert_id();

            $dataSave = array(
                         'RegVerificationID' => $RegVerificationID,
                         'FormulirCode' => $FormulirCode,
                         // 'VerificationBY' => $this->session->userdata('NIP'),
                         // 'VerificationAT' => date('Y-m-d H:i:s'),
                                 );
            $this->db->insert('db_admission.register_verified', $dataSave);
            $ID_register_verified = $this->db->insert_id();

            $dataSave = array(
                    'Status' => 1,
                            );
            $this->db->where('FormulirCode',$FormulirCode);
            $this->db->update('db_admission.formulir_number_offline_m', $dataSave);

            $dataSave = array(
                         'ID_register_verified' => $ID_register_verified,
                         'ID_program_study' => $ID_program_study,
                         'Gender' => $Gender,
                         'IdentityCard' => '',
                         'NationalityID' => $NationalityID,
                         'ReligionID' => $ReligionID,
                         'PlaceBirth' => $PlaceBirth,
                         'DateBirth' => $DateBirth,
                         'ID_register_jtinggal_m' => 1,
                         'ID_country_address' => $NationalityID,
                         'ID_province' => $ID_province,
                         'ID_region' => $ID_region,
                         'ID_districts' => 0,
                         'District' => '',
                         'Address' => $Address,
                         'ZipCode' => 0,
                         'PhoneNumber' => $PhoneNumber,
                         'ID_school_type' => $ID_school_type,
                         'ID_register_major_school' => 0,
                         'YearGraduate' => 0,
                         'KPSReceiverStatus' => 'Tidak',
                         'ID_register_jacket_size_m' => $ID_register_jacket_size_m,
                         'FatherName' => ucwords($FatherName),
                         'FatherNIK' => '',
                         'FatherPlaceBirth' => '',
                         'FatherDateBirth' => '1945-08-17',
                         'FatherStatus' => $FatherStatus,
                         'FatherPhoneNumber' => $FatherPhoneNumber,
                         'Father_ID_occupation' => $Father_ID_occupation,
                         'Father_ID_register_income_m' => 0,
                         'FatherAddress' => $FatherAddress,
                         'MotherName' => ucwords($MotherName),
                         'MotherNik' => '',
                         'MotherPlaceBirth' => '',
                         'MotherDateBirth' => '1945-08-17',
                         'MotherStatus' => $MotherStatus,
                         'MotherPhoneNumber' => $MotherPhoneNumber,
                         'Mother_ID_occupation' => $Mother_ID_occupation,
                         'Mother_ID_register_income_m' => 0,
                         'MotherAddress' => $MotherAddress,
                                 );
            $this->db->insert('db_admission.register_formulir', $dataSave);
            $ID_register_formulir = $this->db->insert_id();

            $arrID_reg_doc_checklist = $this->m_master->caribasedprimary('db_admission.reg_doc_checklist','Active',1);
            for ($xy=0; $xy < count($arrID_reg_doc_checklist); $xy++) {
                $dataSave = array(
                        'ID_register_formulir' => $ID_register_formulir,
                        'ID_reg_doc_checklist' => $arrID_reg_doc_checklist[$xy]['ID'],
                                );

                $this->db->insert('db_admission.register_document', $dataSave);
            }

      }

        echo json_encode(array('status'=> 1,'msg' => '','No_Ref' => $No_RefWr,'arr_key_list_err' => $arr_key_list_err));
      }
      else
      {
        exit('No direct script access allowed');
      }
    }

}
