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
    }

    public function dashboard()
    {
      $data['department'] = parent::__getDepartement();
      $content = $this->load->view('dashboard/dashboard',$data,true);
      $this->temp($content);
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
        $tahun = $input['selectTahun'];
        $nama = $input['NamaCandidate'];
        $status = $input['selectStatus'];

        $this->load->library('pagination');
        $config = $this->config_pagination_default_ajax($this->m_admission->count_calon_mahasiswa(),2,6);
  
        $this->pagination->initialize($config);
        $page = $this->uri->segment(6);
        $start = ($page - 1) * $config["per_page"];
        $this->data['datadb'] = $this->m_admission->selectDataCalonMahasiswa($config["per_page"], $start,$tahun,$nama,$status);
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
      else
      {
        $Status = "Reject";
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
          $text = 'Dear Candidate,<br><br>
                      You have document not approved yet, Please send your valid document.<br>
                      '.url_registration."formulir-registration/".$keyURL['url'].'
                  ';
          $to = $keyURL['email'];
          $subject = "Podomoro University Document Upload";
          $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);        
      }
      else
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
            $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);   
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
      $content = $this->load->view('page/'.$this->data['department'].'/distribusi_formulir/tabel_formulir_online',$this->data,true);

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
       $config = $this->config_pagination_default_ajax($this->m_admission->totalDataFormulir_offline(),15,5);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(5);
       $start = ($page - 1) * $config["per_page"];
       $this->data['datadb'] = $this->m_admission->selectDataDitribusiFormulirOffline($config["per_page"], $start,$tahun,$NomorFormulir,$NamaStaffAdmisi,$status,$statusJual);
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
       $config = $this->config_pagination_default_ajax(1000,5,6);
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
      $input = $this->getInputToken();
      switch ($input['Action']) {
          case 'add':
              $this->m_admission->inserData_formulir_offline_sale_save($input);
              break;
          case 'edit':
              $this->m_admission->editData_formulir_offline_sale_save($input);
              break;
          case 'delete':
              $query = $this->m_master->caribasedprimary('db_admission.sale_formulir_offline','ID',$input['CDID']);
              $FormulirCode = $query[0]['FormulirCodeOffline'];
              $this->m_master->delete_id_table($input['CDID'],'sale_formulir_offline');
              // print_r($FormulirCode);
              $this->m_master->updateStatusJual($FormulirCode);
              break;        
      }
    }

    public function formulir_offline_salect_PIC()
    {
      header('Access-Control-Allow-Origin: *');
      header('Content-Type: application/json');
      $input = $this->getInputToken();
      $generate = $this->m_admission->formulir_offline_salect_PIC($input);
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
       $this->load->library('pagination');
       $config = $this->config_pagination_default_ajax(1000,25,4);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(4);
       $start = ($page - 1) * $config["per_page"];
       $this->data['datadb'] = $this->m_admission->loadData_calon_mahasiswa($config["per_page"], $start,$Nama,$selectProgramStudy,$Sekolah);
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

       $this->load->library('pagination');
       $config = $this->config_pagination_default_ajax(1000,10,5);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(5);
       $start = ($page - 1) * $config["per_page"];
       $this->data['datadb'] = $this->m_admission->daftar_set_nilai_rapor_load_data_paging($config["per_page"], $start,$selectPrody);
       $this->data['mataujian'] = $this->m_admission->select_mataUjian($selectPrody);
       $this->data['grade'] = json_encode($this->m_admission->showData('db_academic.grade'));
      $this->data['no'] = $start + 1;
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
       $selectProgramStudy = $input['selectProgramStudy'];
       $Sekolah = $input['Sekolah'];
       $this->load->library('pagination');
       $config = $this->config_pagination_default_ajax(1000,25,4);
       $this->pagination->initialize($config);
       $page = $this->uri->segment(4);
       $start = ($page - 1) * $config["per_page"];
       $this->data['url_registration'] = url_registration;
       $this->data['datadb'] = $this->m_admission->loadData_calon_mahasiswa_created($config["per_page"], $start,$Nama,$selectProgramStudy,$Sekolah);
       $this->data['mataujian'] = $this->m_admission->select_mataUjian($selectProgramStudy);
       $this->data['grade'] = $this->m_admission->showData('db_academic.grade');
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
      echo json_encode( array('msg' => 'Data berhasil disimpan') );
    }

    public function set_tuition_fee()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/set_tuition_fee',$this->data,true);
      $this->temp($content);
    }

    /*public function set_tuition_fee_input($page = null)
    {
      $this->load->library('pagination');
      $config = $this->config_pagination_default_ajax(1000,5,5);
      $this->pagination->initialize($config);
      $page = $this->uri->segment(5);
      $start = ($page - 1) * $config["per_page"];

      $this->data['payment_type'] = json_encode($this->m_master->showData_array('db_finance.payment_type'));
      $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsTuitionFee($config["per_page"], $start));
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/page_tuition_fee_input',$this->data,true);

      $pagination = '';
      if (count($this->m_admission->getDataCalonMhsTuitionFee($config["per_page"], $start)) > 0) {
        $pagination = $this->pagination->create_links();
      }

      $output = array(
      'pagination_link'  => $pagination,
      'loadtable'   => $content,
      );
      echo json_encode($output);

    }*/

    public function set_tuition_fee_input($page = null)
    {
      $this->load->library('pagination');
      $page_Count = 5;
      $countData = $this->m_admission->count_getDataCalonMhsTuitionFee();
      $config = $this->config_pagination_default_ajax($countData,$page_Count,5);
      $this->pagination->initialize($config);
      $page = $this->uri->segment(5);
      $start = ($page - 1) * $config["per_page"];

      $this->data['payment_type'] = json_encode($this->m_master->showData_array('db_finance.payment_type'));
      $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsTuitionFee($config["per_page"], $start));
      // $this->m_admission->getDataCalonMhsAll($config["per_page"], $start,$input);
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
      $this->load->library('pagination');
      $page_Count = 5;
      $countData = $this->m_admission->count_getDataCalonMhsTuitionFee_delete();
      $config = $this->config_pagination_default_ajax($countData,$page_Count,5);
      $this->pagination->initialize($config);
      $page = $this->uri->segment(5);
      $start = ($page - 1) * $config["per_page"];

      $this->data['payment_type'] = json_encode($this->m_master->showData_array('db_finance.payment_type'));
      $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsTuitionFee_delete($config["per_page"], $start));
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
      $this->m_admission->set_tuition_fee_delete_data($input);
    }

    public function set_tuition_fee_approved($page = null)
    {
      $this->load->library('pagination');
      $config = $this->config_pagination_default_ajax(1000,15,5);
      $this->pagination->initialize($config);
      $page = $this->uri->segment(5);
      $start = ($page - 1) * $config["per_page"];

      $this->data['payment_type'] = json_encode($this->m_master->showData_array('db_finance.payment_type'));
      $this->data['getDataCalonMhs'] = json_encode($this->m_admission->getDataCalonMhsTuitionFee_approved($config["per_page"], $start));
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
      $content = $this->load->view('page/'.$this->data['department'].'/proses_calon_mahasiswa/page_data_calon_mahasiswa',$this->data,true);
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
          $Address = $objWorksheet->getCellByColumnAndRow(12, $i)->getCalculatedValue();
          $Photo = "";
          $Gender = $objWorksheet->getCellByColumnAndRow(14, $i)->getCalculatedValue();
          $PlaceOfBirth = $objWorksheet->getCellByColumnAndRow(15, $i)->getCalculatedValue();
          $DateOfBirth = $objWorksheet->getCellByColumnAndRow(16, $i)->getCalculatedValue();
          $Phone = $objWorksheet->getCellByColumnAndRow(17, $i)->getCalculatedValue();
          $HP = $objWorksheet->getCellByColumnAndRow(18, $i)->getCalculatedValue();
          $ClassOf = $objWorksheet->getCellByColumnAndRow(19, $i)->getCalculatedValue();
          $Email = $objWorksheet->getCellByColumnAndRow(20, $i)->getCalculatedValue();
          $Jacket = $objWorksheet->getCellByColumnAndRow(21, $i)->getCalculatedValue();
          $AnakKe = $objWorksheet->getCellByColumnAndRow(22, $i)->getCalculatedValue();
          $JumlahSaudara = $objWorksheet->getCellByColumnAndRow(23, $i)->getCalculatedValue();
          $NationExamValue = $objWorksheet->getCellByColumnAndRow(24, $i)->getCalculatedValue();
          $GraduationYear = $objWorksheet->getCellByColumnAndRow(25, $i)->getCalculatedValue();
          $IjazahNumber = $objWorksheet->getCellByColumnAndRow(26, $i)->getCalculatedValue();
          $Father = $objWorksheet->getCellByColumnAndRow(27, $i)->getCalculatedValue();
          $Mother = $objWorksheet->getCellByColumnAndRow(28, $i)->getCalculatedValue();
          $StatusFather = $objWorksheet->getCellByColumnAndRow(29, $i)->getCalculatedValue();
          $StatusMother = $objWorksheet->getCellByColumnAndRow(30, $i)->getCalculatedValue();
          $PhoneFather = $objWorksheet->getCellByColumnAndRow(31, $i)->getCalculatedValue();
          $PhoneMother = $objWorksheet->getCellByColumnAndRow(32, $i)->getCalculatedValue();
          $OccupationFather = $objWorksheet->getCellByColumnAndRow(33, $i)->getCalculatedValue();
          $OccupationMother = $objWorksheet->getCellByColumnAndRow(34, $i)->getCalculatedValue();
          $EducationFather = $objWorksheet->getCellByColumnAndRow(35, $i)->getCalculatedValue();
          $EducationMother = $objWorksheet->getCellByColumnAndRow(36, $i)->getCalculatedValue();
          $AddressFather = $objWorksheet->getCellByColumnAndRow(37, $i)->getCalculatedValue();
          $AddressMother = $objWorksheet->getCellByColumnAndRow(38, $i)->getCalculatedValue();
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
            'Address' => $Address,
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

          $temp2 = array(
              'NPM' => $NPM,
              'Password' => $pass,
              'Year' => date('Y'),
              'EmailPU' => $NPM.'@podomorouniversity.ac.id',
              'StatusStudentID' => 3,
              'Status' => '1',
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

}
