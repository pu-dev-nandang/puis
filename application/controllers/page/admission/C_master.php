<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_master extends Admission_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('master/m_master');
        $this->data['department'] = parent::__getDepartement();
        $this->data['NameMenu'] = $this->GlobalData['NameMenu'];
        // get academic year admission
            $t = $this->m_master->showData_array('db_admission.set_ta');
            $this->data['academic_year_admission'] = $t[0]['Ta'];
        //$this->checkAuth_user();
    }

    public function page_set_tgl_register()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/page_set_tgl_register',$this->data,true);
        $this->temp($content);
    }

    public function data_cfg_deadline()
    {
        $generate = $this->m_master->showData_array('db_admission.cfg_deadline');
        echo json_encode($generate);
    }

    public function modalform_set_tgl_register()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_admission.cfg_deadline','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_set_tgl_register',$this->data,true);
    }

    public function submit_cfg_deadline()
    {
        $input = $this->getInputToken();
        if (strlen($DeadLinePayment) != 19) {
            $DeadLinePayment = $DeadLinePayment.':00';
        }
        $startDate = (strlen($input['startDate']) != 19) ? $input['startDate'].':00' : $input['startDate'];
        $endDate = (strlen($input['endDate']) != 19) ? $input['endDate'].':00' : $input['endDate'];
        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_cfg_deadline($startDate,$endDate);
                break;
            case 'edit':
                $this->m_master->editData_cfg_deadline($startDate,$endDate,$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'cfg_deadline');
                break;
            case 'getactive':
                $this->m_master->getActive_id_active_table($input['CDID'],$input['Active'],'cfg_deadline');
                break;
            default:
                # code...
                break;
        }
    }

    public function page_set_max_cicilan()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/page_set_max_cicilan',$this->data,true);
        $this->temp($content);
    }

    public function modalform_set_max_cicilan()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_admission.cfg_cicilan','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_max_cicilan',$this->data,true);
    }

    public function submit_cfg_cicilan()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_cfg_cicilan($input['max_cicilan']);
                break;
            case 'edit':
                $this->m_master->editData_cfg_cicilan($input['max_cicilan'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'cfg_cicilan');
                break;
            case 'getactive':
                $this->m_master->getActive_id_active_table($input['CDID'],$input['Active'],'cfg_cicilan');
                break;
            default:
                # code...
                break;
        }
    }

    public function data_cfg_cicilan()
    {
        $generate = $this->m_master->showData_array('db_admission.cfg_cicilan');
        echo json_encode($generate);
    }

    public function sma($approval = 0)
    {
        $this->data['approval'] = $approval;
        $content = $this->load->view('page/'.$this->data['department'].'/master/sma',$this->data,true);
        $this->temp($content);
    }

    public function sma_integration()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/sma_integration',$this->data,true);
        $this->temp($content);

    }

    public function sma_table()
    {
        $token = $this->input->post('token');
        $this->data['token'] = $token;
        $this->load->view('page/'.$this->data['department'].'/master/sma_table',$this->data);
    }

    public function modalform_sekolah()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_admission.school','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_sekolah',$this->data,true);
    }

    public function config_set_email()
    {
        $getEmailConfig = $this->m_sendemail->loadEmailConfig();
        $this->data['email'] = $getEmailConfig;
        $content = $this->load->view('page/'.$this->data['department'].'/master/set_email',$this->data,true);
        $this->temp($content);
    }

    public function testing_email()
    {
        $input = $this->getInputToken();
        $email = $input['email'];
        $pwd = $input['pwd'];
        $smtp_port = $input['smtp_port'];
        $smtp_host = $input['smtp_host'];
        $to = $this->m_sendemail->getToEmail('Testing').','.'alhadi.rahman@podomorouniversity.ac.id';
        //$to = "alhadi.rahman@podomorouniversity.ac.id";
        $subject = "Testemail";
        $sendEmail = $this->m_sendemail->sendEmail($to,$subject,$smtp_host,$smtp_port,$email,$pwd);
        return print_r(json_encode($sendEmail));
    }

    public function save_email()
    {
        $input = $this->getInputToken();
        $email = $input['email'];
        $pwd = $input['pwd'];
        $smtp_port = $input['smtp_port'];
        $smtp_host = $input['smtp_host'];
        $text = $input['text'];
        $save_email = $this->m_sendemail->save_email($smtp_host,$smtp_port,$email,$pwd,$text);
        return print_r(json_encode($save_email));
    }

    public function total_account()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/total_account',$this->data,true);
        $this->temp($content);
    }

    public function load_table_total_account()
    {
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_admission.count_account');
        $this->data['getData'] = $this->m_master->showData('db_admission.count_account');
        echo $this->load->view('page/'.$this->data['department'].'/master/table_master_global',$this->data,true);

    }

    public function modalform($table)
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_admission.'.$table);
        $this->data['getData'] = null;
        if ($this->data['id'] != '') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_admission.'.$table,'ID',$this->data['id']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform',$this->data,true);
    }

    public function submit_count_account()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_count_account($input['CountAccount']);
                break;
            case 'edit':
                $this->m_master->editData_count_account($input['CountAccount'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_count_account($input['CDID']);
                break;
            case 'getactive':
                $this->m_master->getActive_count_account($input['CDID'],$input['Active']);
                break;
            default:
                # code...
                break;
        }
    }

    public function email_to()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/email_to',$this->data,true);
        $this->temp($content);
    }

    public function load_table_email_to()
    {
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_admission.email_to');
        $this->data['getData'] = $this->m_master->showData('db_admission.email_to');
        echo $this->load->view('page/'.$this->data['department'].'/master/table_master_global',$this->data,true);
    }

    public function submit_email_to()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_email_to($input['EmailTo'],$input['fungsi']);
                break;
            case 'edit':
                $this->m_master->editData_email_to($input['EmailTo'],$input['fungsi'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_email_to($input['CDID']);
                break;
            case 'getactive':
                $this->m_master->getActive_email_to($input['CDID'],$input['Active']);
                break;
            default:
                # code...
                break;
        }
    }

    public function lama_pembayaran()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/lama_pembayaran',$this->data,true);
        $this->temp($content);
    }

    public function load_table_master($table)
    {
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_admission.'.$table);
        $this->data['getData'] = $this->m_master->showData('db_admission.'.$table);
        if ($table == 'price_formulir' || $table == 'price_formulir_offline') {
            $getData = $this->m_master->showData('db_admission.'.$table);
            $getData[0]->PriceFormulir = 'Rp '.number_format($getData[0]->PriceFormulir,2,',','.');
            $this->data['getData'] = $getData;
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/table_master_global',$this->data,true);
    }

    public function submit_lama_pembayaran()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_lama_pembayaran($input['Longtime']);
                break;
            case 'edit':
                $this->m_master->editData_lama_pembayaran($input['Longtime'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'deadline_register');
                break;
            case 'getactive':
                $this->m_master->getActive_id_active_table($input['CDID'],$input['Active'],'deadline_register');
                break;
            default:
                # code...
                break;
        }
    }

    public function harga_formulir_online()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/harga_formulir',$this->data,true);
        $this->temp($content);
    }

    public function submit_harga_formulir_online()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_harga_formulir($input['PriceFormulir']);
                break;
            case 'edit':
                $this->m_master->editData_harga_formulir($input['PriceFormulir'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'price_formulir');
                break;
            case 'getactive':
                $this->m_master->getActive_id_active_table($input['CDID'],$input['Active'],'price_formulir');
                break;
            default:
                # code...
                break;
        }
    }

    public function harga_formulir_offline()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/harga_formulir_offline',$this->data,true);
        $this->temp($content);
    }

    public function submit_harga_formulir_offline()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_harga_formulir_offline($input['PriceFormulir']);
                break;
            case 'edit':
                $this->m_master->editData_harga_formulir_offline($input['PriceFormulir'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'price_formulir_offline');
                break;
            case 'getactive':
                $this->m_master->getActive_id_active_table($input['CDID'],$input['Active'],'price_formulir_offline');
                break;
            default:
                # code...
                break;
        }
    }

    public function global_wilayah()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/global_wilayah',$this->data,true);
        $this->temp($content);
    }

    public function loadTableMasterNoAction($table)
    {
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_admission.'.$table);
        $this->data['getData'] = $this->m_master->showData('db_admission.'.$table);
        echo $this->load->view('page/'.$this->data['department'].'/master/table_master_global_no_action',$this->data,true);
    }

    public function jenis_tempat_tinggal()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/jenis_tempat_tinggal',$this->data,true);
        $this->temp($content);
    }

    public function submit_jenis_tempat_tinggal()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_jenis_tempat_tinggal($input['JenisTempatTinggal']);
                break;
            case 'edit':
                $this->m_master->editData_jenis_tempat_tinggal($input['JenisTempatTinggal'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'register_jtinggal_m');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'register_jtinggal_m');
                break;
            default:
                # code...
                break;
        }
    }

    public function pendapatan()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/pendapatan',$this->data,true);
        $this->temp($content);
    }

    public function submit_pendapatan()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_pendapatan($input['Income']);
                break;
            case 'edit':
                $this->m_master->editData_pendapatan($input['Income'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'register_income_m');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'register_income_m');
                break;
            default:
                # code...
                break;
        }
    }

    public function agama()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/agama',$this->data,true);
        $this->temp($content);
    }

    public function load_table_master_agama()
    {
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_admission.agama');
        $this->data['getData'] = $this->m_master->showData('db_admission.agama');
        echo $this->load->view('page/'.$this->data['department'].'/master/table_master_global_no_action',$this->data,true);
    }

    public function tipe_sekolah()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/tipe_sekolah',$this->data,true);
        $this->temp($content);
    }

    public function load_table_tipe_sekolah()
    {
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_admission.school_type');
        $this->data['getData'] = $this->m_master->showData('db_admission.school_type');
        echo $this->load->view('page/'.$this->data['department'].'/master/table_master_tipe_sekolah',$this->data,true);
    }

    public function  document_checklist()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/document_checklist',$this->data,true);
        $this->temp($content);
    }

    public function submit_document_checklist()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_document_checklist($input['DocumentChecklist']);
                break;
            case 'edit':
                $this->m_master->editData_document_checklist($input['DocumentChecklist'],$input['CDID'],$input['Required']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'reg_doc_checklist');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'reg_doc_checklist');
                break;
            default:
                # code...
                break;
        }
    }

    public function formulir_online()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/formulir_online',$this->data,true);
        $this->temp($content);
    }

    public function loadDataFormulirOnline()
    {
        $input = $this->getInputToken();
        $this->data['passSelectTahun'] = $input['selectTahun'];
        $content = $this->load->view('page/'.$this->data['department'].'/master/load_formulir_online',$this->data,true);
        echo $content;
    }

    public function get_json_formulir_online()
    {
        $input = $this->getInputToken();
        $data = $this->m_master->getDataFormulirOnline($input['selectTahun']);
        return print_r(json_encode($data));
    }

    public function generate_formulir_online()
    {
        $input = $this->getInputToken();
        $this->m_master->generate_formulir_online($input['selectTahun']);
    }

    public function formulir_offline()
    {
        $totalData = $this->m_master->caribasedprimary('db_admission.count_account','Active',1);
        $this->data['totalData'] = (int) $totalData[0]['CountAccount'];
        $content = $this->load->view('page/'.$this->data['department'].'/master/formulir_offline',$this->data,true);
        $this->temp($content);
    }

    public function loadDataFormulirOffline()
    {
        $input = $this->getInputToken();
        $this->data['passSelectTahun'] = $input['selectTahun'];
        $content = $this->load->view('page/'.$this->data['department'].'/master/load_formulir_offline',$this->data,true);
        echo $content;
    }

    public function get_json_formulir_offline()
    {
        $input = $this->getInputToken();
        $data = $this->m_master->getDataFormulirOffline($input['selectTahun']);
        return print_r(json_encode($data));
    }

    public function generate_formulir_offline()
    {
        $input = $this->getInputToken();
        $this->m_master->generate_formulir_offline($input['selectTahun'],$input['qty']);
    }

    public function jacket_size()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/jacket_size',$this->data,true);
        $this->temp($content);
    }

    public function submit_jacket_size()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_Jacket_Size($input['JacketSize']);
                break;
            case 'edit':
                $this->m_master->editData_Jacket_Size($input['JacketSize'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'register_jacket_size_m');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'register_jacket_size_m');
                break;
            default:
                # code...
                break;
        }
    }

    public function jurusan_sekolah()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/jurusan_sekolah',$this->data,true);
        $this->temp($content);
    }

    public function submit_jurusan_sekolah()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_jurusan_sekolah($input['SchoolMajor']);
                break;
            case 'edit':
                $this->m_master->editData_jurusan_sekolah($input['SchoolMajor'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'register_major_school');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'register_major_school');
                break;
            default:
                # code...
                break;
        }
    }

    public function ujian_masuk_per_prody()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/ujian_masuk_per_prody',$this->data,true);
        $this->temp($content);
    }

    public function modalform_ujian_masuk_per_prody()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getDataEdit'] =  $this->m_master->caribasedprimary('db_admission.ujian_perprody_m','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_ujian_masuk_per_prody',$this->data,true);
    }

    public function table_ujian_masuk_per_prody()
    {
        $this->data['getColoumn'] = array('query' => array('ID','Program Study','NamaUjian','Bobot','Active','CreateAT') );
        $this->data['getData'] = $this->m_master->showDataUjianMasukPerPrody();
        echo $this->load->view('page/'.$this->data['department'].'/master/table_ujian_masuk_per_prody',$this->data,true);
    }

    public function submit_ujian_masuk_per_prody()
    {
        $input = $this->getInputToken();

        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_ujian_masuk($input['nm_ujian'],$input['selectBobot'],$input['selectPrody']);
                break;
            case 'edit':
                $this->m_master->editData_ujian_masuk($input['nm_ujian'],$input['selectBobot'],$input['selectPrody'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'ujian_perprody_m');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'ujian_perprody_m');
                break;
            default:
                # code...
                break;
        }
    }

    public function menu_previleges()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/menu_previleges',$this->data,true);
        $this->temp($content);
    }

    public function modal_form_previleges()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        echo $this->load->view('page/'.$this->data['department'].'/master/modal_menu_previleges',$this->data,true);
    }

    public function get_menu()
    {
        $generate = $this->m_master->getdataMenu();
        echo json_encode($generate);
    }

    public function get_menu_save_menu()
    {
        $input = $this->getInputToken();
        $menu = $input['InputJenisMenu'];
        $this->m_master->saveMenu($menu);
    }

    public function get_submenu_save_menu()
    {
        $input = $this->getInputToken();
        $menu = $input['selectMenu'];
        $sub_menu1 = $input['sub_menu1'];
        $sub_menu2 = $input['sub_menu2'];
        $Slug = $input['Slug'];
        $Controller = $input['Controller'];
        $chkPrevileges = $input['chkPrevileges'];
        $this->m_master->saveSubMenu($menu,$sub_menu1,$sub_menu2,$chkPrevileges,$Slug,$Controller);
    }

    public function get_submenu_show()
    {
        $generate = $this->m_master->showSubmenu();
        echo json_encode($generate);
    }

    public function get_submenu_update()
    {
        $input = $this->getInputToken();
        $this->m_master->updateSubMenu($input);

    }

    public function get_submenu_delete()
    {
        $input = $this->getInputToken();
        $this->m_master->deleteSubMenu($input);
    }

    public function get_submenu_by_menu()
    {
        $input = $this->getInputToken();
        $generate = $this->m_master->get_submenu_by_menu($input);
        echo json_encode($generate);
    }

    public function autocompleteuser()
    {
        $input = $this->getInputToken();
        $data['response'] = 'true'; //mengatur response
        $data['message'] = array(); //membuat array
        $getData = $this->m_master->getUserAdmission($input['Nama']);
        for ($i=0; $i < count($getData); $i++) {
            $data['message'][] = array(
                'label' => $getData[$i]['Name'],
                'value' => $getData[$i]['NIP']
            );
        }
        echo json_encode($data);
    }

    public function save_user_previleges()
    {
        $input = $this->getInputToken();
        $this->m_master->save_user_previleges($input);
    }

    public function get_previleges_user_show()
    {
        $input = $this->getInputToken();
        $NIP = $input['Nama_search'];
        $generate = $this->m_master->get_previleges_user_show($NIP);
        echo json_encode($generate);
    }

    public function previleges_user_update()
    {
        $input = $this->getInputToken();
        $this->m_master->previleges_groupuser_update($input);
    }

    public function previleges_user_delete()
    {
        $input = $this->getInputToken();
        $this->m_master->previleges_group_user_delete($input);
    }

    public function page_create_va()
    {
        $t = $this->m_master->caribasedprimary('db_admission.count_account','Active',1);
        $this->data['TotalAcccount'] = 1000;
        if (count($t) > 0) {
             $this->data['TotalAcccount'] = $t[0]['CountAccount'];
        }
        $content = $this->load->view('page/'.$this->data['department'].'/master/page_create_va',$this->data,true);
        $this->temp($content);
    }

    public function generate_va()
    {
        $input = $this->getInputToken();
        $selectJMLVA = $input['selectJMLVA'];
        $startVA = 1; // increment 8 digit
        $const_VA = '98800202';
        $inc_VA = '';
        $max_va_adm = 9999999;
        if ($selectJMLVA < $max_va_adm) {
            for ($i=0; $i < $selectJMLVA; $i++) {
                // sleep(0.5);
                $inc_VA = $startVA;
                for ($j=0; $j < (8 - strlen($startVA)); $j++) {
                    $inc_VA = '0'.$inc_VA;
                }
                $inc_VA = $const_VA.$inc_VA;
                $this->m_master->saveGenerateVA($inc_VA);
                $startVA++;
            }
        }

    }

    public function loadDataVA_available()
    {
        $generate = $this->m_master->loadDataVA_available();
        echo json_encode($generate);
    }

    public function event()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/event',$this->data,true);
        $this->temp($content);
    }

    public function modalform_event()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_admission.price_event','ID',$input['CDID']);
        }
        $this->data['harga_formulir_offline'] = $this->m_master->price_formulir_offline();
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_event',$this->data,true);
    }

    public function table_event()
    {
        $generate = $this->m_master->load_data_event();
        echo json_encode($generate);
    }

    public function modalform_event_save()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_price_event($input['evn_price'],$input['evn_name']);
                break;
            case 'edit':
                $this->m_master->editData_price_event($input['evn_price'],$input['evn_name'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'price_event');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'price_event');
                break;
            default:
                # code...
                break;
        }

    }

    public function sumber_iklan()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/sumber_iklan',$this->data,true);
        $this->temp($content);
    }

    public function submit_source_from_event()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_source_from_event($input['src_name']);
                break;
            case 'edit':
                $this->m_master->editData_source_from_event($input['src_name'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'source_from_event');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'source_from_event');
                break;
            default:
                # code...
                break;
        }
    }

    public function sales_koordinator_wilayah_page()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/sales_koordinator',$this->data,true);
        $this->temp($content);
    }

    public function sales_koordinator_wilayah_modal_form()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_admission.sales_school_m','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_sales_koordinator',$this->data,true);
    }

    public function modalform_sales_koordinator_save()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_sales_school_m($input['selectSekolah'],$input['selectSales'],$input['selectWilayah']);
                break;
            case 'edit':
                $this->m_master->editData_sales_school_m($input['selectSekolah'],$input['selectSales'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'sales_school_m');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'sales_school_m');
                break;
            default:
                # code...
                break;
        }
    }

    public function sales_koordinator_pagination($page = null)
    {
        $input =  $this->getInputToken();
        $selectWilayah = $input['selectWilayah'];
        $selectSchool = $input['selectSchool'];
        $selectSales = $input['selectSales'];
        $selectStatus = $input['selectStatus'];

        $this->load->library('pagination');
        $config = $this->config_pagination_default_ajax($this->m_master->count_sales_koordinator(),10,5);

        $this->pagination->initialize($config);
        $page = $this->uri->segment(5);
        $start = ($page - 1) * $config["per_page"];
        $this->data['no'] = $start;
        $this->data['datadb'] = $this->m_master->selectDataSalesKoordinator($config["per_page"], $start,$selectWilayah,$selectSchool,$selectSales,$selectStatus);
        $content = $this->load->view('page/'.$this->data['department'].'/master/table_sales_koordinator_pagination',$this->data,true);

        $output = array(
            'pagination_link'  => $this->pagination->create_links(),
            'sales_koordinator_pagination'   => $content,
        );
        echo json_encode($output);
    }

    public function fileGet($file)
    {
        //check session ID_register_formulir ada atau tidak
        // check session token untuk download

        // Check File exist atau tidak
        if (file_exists('./document/'.$file)) {
            // $this->load->helper('download');
            // $data   = file_get_contents('./document/'.$namaFolder.'/'.$file);
            // $name   = $file;
            // force_download($name, $data); // script download file
            $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    public function download($file)
    {
        if (file_exists('./document/'.$file)) {
             $this->load->helper('download');
             $data   = file_get_contents('./document/'.$file);
             $name   = $file;
             force_download($name, $data); // script download file
            // $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    public function download_template($file)
    {
        $file = str_replace('-', '/', $file);
        if (file_exists('./uploads/'.$file)) {
             $this->load->helper('download');
             $data   = file_get_contents('./uploads/'.$file);
             $name   = $file;
             force_download($name, $data); // script download file
            // $this->showFile($file);
        }
        else
        {
            show_404($log_error = TRUE);
        }
    }

    private function showFile($file)
    {
        header("Content-type: application/pdf");
        header("Content-disposition: inline;
        filename=".basename('document/'.$file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        $filePath = readfile('document/'.$file);
    }

    public function downloadPDFToken()
    {
        //error_reporting(0);
        $input = $this->getInputToken();
        $arr_temp = array('filename' => '');
        $filename = "token_".$input['formulir_code'].'.pdf';
        $getData = $this->m_master->showData_array('db_admission.set_label_token_off');
        $setXAwal = 10;
        $setYAwal = 18;
        $setJarakY = 5;
        $setFontIsian = 12;

        try{
            $config=array('orientation'=>'P','size'=>'A4');
            $this->load->library('MyPDF',$config);
            $this->mypdf->SetMargins(10,10,10,10);
            $this->mypdf->SetAutoPageBreak(true, 0);
            $this->mypdf->AddPage();
            // Logo
            $this->mypdf->Image('./images/logo_tr2.png',10,10,50);
            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Text(150, 15, 'Formulir Number : '.$input['formulir_code']);
            // Line break
            $this->mypdf->Ln(20);

            $this->mypdf->SetFont('Arial','B',$getData[0]['setFontHeader']);
            $this->mypdf->Cell(190, 10, $getData[0]['Header'], 0, 1, 'C', 0);

            $contain1 = $getData[0]['Contain1'];
            $proses = $this->m_master->proses_link($contain1,$input['url_token']);

            // isian
            $setY = $setYAwal + 15;
            $setX = $setXAwal;

            $setY = $setY + ($setJarakY * 3);
            $setXisian = 10;
            // $this->mypdf->SetXY($setXisian,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
            $isian = $proses['getFirst'];
            $SetYMultiCell = $setY - 2;
            $this->mypdf->SetXY($setXisian,$SetYMultiCell);
            $this->mypdf->MultiCell( 190, 6,$isian, 0,'L');

            $setY = $setY + ($setJarakY * 2);
            $setXisian = 10;
            // $this->mypdf->SetXY($setXisian,$setY);
            $this->mypdf->SetTextColor(0,0,255);
            $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
            $isian = $proses['link'];
            $SetYMultiCell = $setY - 2;
            $this->mypdf->SetXY($setXisian,$SetYMultiCell);
            $this->mypdf->MultiCell( 190, 6,$isian, 0,'L');

            $setY = $setY + ($setJarakY * 2);
            $setXisian = 10;
            // $this->mypdf->SetXY($setXisian,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
            $isian = $proses['getLast'];
            $SetYMultiCell = $setY - 2;
            $this->mypdf->SetXY($setXisian,$SetYMultiCell);
            $this->mypdf->MultiCell( 190, 6,$isian, 0,'L');


            // $setY = $setY + ($setJarakY * 15);
            $setY = $this->mypdf->GetY();
            $setY = $setY + 15;
            $setXisian = 10;
            // $this->mypdf->SetXY($setXisian,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$getData[0]['setFont2']);
            $isian = $getData[0]['Contain2'];
            $SetYMultiCell = $setY - 2;
            $this->mypdf->SetXY($setXisian,$SetYMultiCell);
            $this->mypdf->MultiCell( 190, 6,$isian, 0,'L');

            $this->mypdf->Line(20, 280, 190, 280);
            $setY = 282;
            $this->mypdf->SetFont('Arial','',6);
            $this->mypdf->SetXY(40,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            // $this->mypdf->SetFillColor(0,0,0);
            $this->mypdf->Cell(190, 5, 'Admission Office :  Central Park Mall, Lantai 3, Unit 112, Podomoro City, JL Letjen S. Parman Kav.28, Jakarta Barat 11470', 0, 1, 'L', 0);
            $setY = 284;
            $this->mypdf->SetFont('Arial','',6);
            $this->mypdf->SetXY(43,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            // $this->mypdf->SetFillColor(0,0,0);
            $this->mypdf->Cell(190, 5, 'Telp : (021) 292 00 456    Email : admission@podomorouniversity.ac.id   Website : www.podomorouniversity.ac.id', 0, 1, 'L', 0);

            $path = './document';
            $path = $path.'/'.$filename;
            // $this->mypdf->Output($path,'F');
            $this->m_master->updateStatusPrint($input['formulir_code']);
            $this->mypdf->Output($filename.'.pdf','I');
            // echo json_encode($filename);

        }
        catch (Exception $e){
            echo json_encode($filename);
            // return $arr_temp['filename'] = $filename;
        }
        // return $arr_temp['filename'] = $filename;
    }

    public function set_print_label()
    {
        $this->data['getData'] = $this->m_master->showData_array('db_admission.set_label_token_off');
        $content = $this->load->view('page/'.$this->data['department'].'/master/set_print_label',$this->data,true);
        $this->temp($content);
    }

    public function testing_print_label_token()
    {
        $input = $this->getInputToken();
        $arr_temp = array('filename' => '');
        $filename = "test.pdf";
        $setXAwal = 10;
        $setYAwal = 18;
        $setJarakY = 5;
        $setFontIsian = 12;

        try{
            $config=array('orientation'=>'P','size'=>'A4');
            $this->load->library('MyPDF',$config);
            $this->mypdf->SetMargins(10,10,10,10);
            $this->mypdf->SetAutoPageBreak(true, 0);
            $this->mypdf->AddPage();
            // Logo
            $this->mypdf->Image('./images/logo_tr.png',10,10,50);
            // Line break
            $this->mypdf->Ln(18);

            $this->mypdf->SetFont('Arial','B',$input['selectFontHeader']);
            $this->mypdf->Cell(190, 10, $input['header'], 0, 1, 'C', 0);

            $contain1 = $input['contain1'];
            $proses = $this->m_master->proses_link_test($contain1);

            // isian
            $setY = $setYAwal + 15;
            $setX = $setXAwal;

            $setY = $setY + ($setJarakY * 2);
            $setXisian = 10;
            // $this->mypdf->SetXY($setXisian,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$input['selectFontContain1']);
            $isian = $proses['getFirst'];
            $SetYMultiCell = $setY - 2;
            $this->mypdf->SetXY($setXisian,$SetYMultiCell);
            $this->mypdf->MultiCell( 190, 6,$isian, 0,'L');

            $setY = $setY + ($setJarakY * 2);
            $setXisian = 10;
            // $this->mypdf->SetXY($setXisian,$setY);
            $this->mypdf->SetTextColor(0,0,255);
            $this->mypdf->SetFont('Arial','',$input['selectFontContain1']);
            $isian = $proses['link'];
            $SetYMultiCell = $setY - 2;
            $this->mypdf->SetXY($setXisian,$SetYMultiCell);
            $this->mypdf->MultiCell( 190, 6,$isian, 0,'L');

            $setY = $setY + ($setJarakY * 2);
            $setXisian = 10;
            // $this->mypdf->SetXY($setXisian,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$input['selectFontContain1']);
            $isian = $proses['getLast'];
            $SetYMultiCell = $setY - 2;
            $this->mypdf->SetXY($setXisian,$SetYMultiCell);
            $this->mypdf->MultiCell( 190, 6,$isian, 0,'L');


            // $setY = $setY + ($setJarakY * 15);
            $setY = $this->mypdf->GetY();
            $setY = $setY + 15;
            $setXisian = 10;
            // $this->mypdf->SetXY($setXisian,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            $this->mypdf->SetFont('Arial','',$input['selectFontContain2']);
            $isian = $input['contain2'];
            $SetYMultiCell = $setY - 2;
            $this->mypdf->SetXY($setXisian,$SetYMultiCell);
            $this->mypdf->MultiCell( 190, 6,$isian, 0,'L');


            $this->mypdf->Line(20, 280, 190, 280);
            $setY = 282;
            $this->mypdf->SetFont('Arial','',6);
            $this->mypdf->SetXY(40,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            // $this->mypdf->SetFillColor(0,0,0);
            $this->mypdf->Cell(190, 5, 'Admission Office :  Central Park Mall, Lantai 3, Unit 112, Podomoro City, JL Letjen S. Parman Kav.28, Jakarta Barat 11470', 0, 1, 'L', 0);
            $setY = 284;
            $this->mypdf->SetFont('Arial','',6);
            $this->mypdf->SetXY(43,$setY);
            $this->mypdf->SetTextColor(0,0,0);
            // $this->mypdf->SetFillColor(0,0,0);
            $this->mypdf->Cell(190, 5, 'Telp : (021) 292 00 456    Email : admission@podomorouniversity.ac.id   Website : www.podomorouniversity.ac.id', 0, 1, 'L', 0);

            $path = './document';
            $path = $path.'/'.$filename;
            $this->mypdf->Output($path,'F');
            echo json_encode($filename);

        }
        catch (Exception $e){
            echo json_encode($filename);
            // return $arr_temp['filename'] = $filename;
        }
    }

    public function save_set_print_label()
    {
        $input = $this->getInputToken();
        $this->m_master->save_set_print_label($input);
        echo json_encode('success');
    }

    public function page_recycle_va()
    {
        $this->data['datadb'] = $this->m_master->recycleDataVa(1, 0);
        $content = $this->load->view('page/'.$this->data['department'].'/master/page_recycle_va',$this->data,true);
        $this->temp($content);
    }

    public function loadDataVA_deleted($page)
    {
        $this->load->library('pagination');
        $sql = 'select count(*) as total from db_admission.register_deleted';
        $query=$this->db->query($sql, array())->result_array();
        $config = $this->config_pagination_default_ajax($query[0]['total'],18,4);

        $this->pagination->initialize($config);
        $page = $this->uri->segment(4);
        $start = ($page - 1) * $config["per_page"];
        $this->data['start'] =  $start;
        $this->data['datadb'] = $this->m_master->recycleDataVa($config["per_page"], $start);
        $content = $this->load->view('page/'.$this->data['department'].'/master/loadDataVa_pagination',$this->data,true);

        $output = array(
            'pagination_link'  => $this->pagination->create_links(),
            'register_deleted'   => $content,
        );
        echo json_encode($output);
    }

    public function submit_recycle_va()
    {
        $input = $this->getInputToken();
        // methode update
        $updateBNI = $this->m_master->updateBiling($input);
        for ($i=0; $i < count($updateBNI) ; $i++) {
            if ($updateBNI[$i]['msg'] != '000') {
                // echo json_encode('Update Failed, Koneksi ke Server BNI Terputus');
                echo json_encode($updateBNI[$i]['msg']);
                return;
            }
        }

        $updateDB = $this->m_master->updateDB_registerDeleted($updateBNI);
        echo json_encode('Success');
        // $updateBNI2 = $this->m_master->updateBiling2($input);
        // print_r($updateBNI2);

    }

    public function load_data_autocomplete_calon_mahasiswa()
    {
        $input = $this->getInputToken();
        $data['response'] = 'true'; //mengatur response
        $data['message'] = array(); //membuat array
        $getData = $this->m_master->getCalon_mahasiswa($input['Nama']);
        for ($i=0; $i < count($getData); $i++) {
            $data['message'][] = array(
                'label' => $getData[$i]['Name'],
                'value' => $getData[$i]['Name']
            );
        }
        echo json_encode($data);
    }

    public function upload_pengumuman()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/upload_pengumuman',$this->data,true);
        $this->temp($content);
    }

    public function jalur_prestasi_akademik()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/jalur_prestasi_akademik',$this->data,true);
        $this->temp($content);
    }

    public function table_jpa()
    {
        $generate = $this->m_master->showData_array('db_admission.register_dsn_jpa');
        echo json_encode($generate);
    }

    public function modalform_jpa()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_admission.register_dsn_jpa','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_jpa',$this->data,true);
    }

    public function submit_jpa()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_jpa($input['selectRangking1'],$input['selectRangking2'],$input['selectPotongan']);
                break;
            case 'edit':
                $this->m_master->editData_jpa($input['selectRangking1'],$input['selectRangking2'],$input['selectPotongan'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'register_dsn_jpa');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'register_dsn_jpa');
                break;
            default:
                # code...
                break;
        }
    }

    public function jalur_prestasi_akademik_umum()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/jalur_prestasi_akademik_umum',$this->data,true);
        $this->temp($content);
    }

    public function table_jpau()
    {
        $generate = $this->m_master->showData_array('db_admission.register_dsn_jpau');
        echo json_encode($generate);
    }

    public function modalform_jpau()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_admission.register_dsn_jpau','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_jpau',$this->data,true);
    }

    public function submit_jpau()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_jpau($input['Tingkat'],$input['selectPotongan']);
                break;
            case 'edit':
                $this->m_master->editData_jpau($input['Tingkat'],$input['selectPotongan'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'register_dsn_jpau');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'register_dsn_jpau');
                break;
            default:
                # code...
                break;
        }
    }

    public function jalur_prestasi_bidang_or_seni()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/jalur_prestasi_bidang_or_seni',$this->data,true);
        $this->temp($content);
    }

    public function table_jpok()
    {
        $generate = $this->m_master->showData_array('db_admission.register_dsn_jpok');
        echo json_encode($generate);
    }

    public function modalform_jpok()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_admission.register_dsn_jpok','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_jpok',$this->data,true);
    }

    public function submit_jpok()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_jpok($input['Tingkat'],$input['selectPotonganSPP'],$input['selectPotonganSKS']);
                break;
            case 'edit':
                $this->m_master->editData_jpok($input['Tingkat'],$input['selectPotonganSPP'],$input['selectPotonganSKS'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'register_dsn_jpok');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'register_dsn_jpok');
                break;
            default:
                # code...
                break;
        }
    }

    public function submit_sekolah()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                $this->m_master->inserData_Sekolah($input['selectProvinsi'],$input['selectRegion'],$input['selectDistrict'],$input['selectTypeSekolah'],$input['nm_sekolah'],$input['alamat']);
                break;
            case 'edit':
                $this->m_master->editData_Sekolah($input['selectProvinsi'],$input['selectRegion'],$input['selectDistrict'],$input['selectTypeSekolah'],$input['nm_sekolah'],$input['alamat'],$input['CDID']);
                break;
            case 'delete':
                $this->m_master->delete_id_table($input['CDID'],'school');
                // count
                $a = $this->m_master->caribasedprimary('db_admission.register','SchoolID',$input['CDID']);
                if (count($a) > 0) {
                    $text = 'Dear Team,<br><br>
                                    Mohon koreksi data calon mahasiswa karena data master sekolah telah di hapus oleh, <br> Nama :
                                    '. $this->session->userdata('Name').
                        '<br>Divisi : '.$this->session->userdata('PositionMain')['Division'];
                    $to = $this->m_sendemail->getToEmail('Admisi');
                    $subject = "Koreksi Data Calon Mahasiswa";
                    $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                }
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table($input['CDID'],$input['Active'],'school');
                break;
            default:
                # code...
                break;
        }
    }

    // modification menu
    public function getGroupPrevileges()
    {
        // get NIP
        $NIP = $this->session->userdata('NIP');
        $get = $this->m_master->caribasedprimary('db_admission.previleges_guser','NIP',$NIP);
        if ($get[0]['G_user'] == 1) {
            $generate = $this->m_master->showData_array('db_admission.cfg_group_user');
        }
        else
        {
            $generate = $this->m_master->getDataWithoutSuperAdmin();
        }

        echo json_encode($generate);
    }

    public function groupuser_save()
    {
        $input = $this->getInputToken();
        $this->m_master->groupuser_save($input);
    }

    public function get_previleges_group_show()
    {
        $input = $this->getInputToken();
        $GroupID = $input['Nama_search'];
        $generate = $this->m_master->get_previleges_group_show($GroupID);
        echo json_encode($generate);
    }

    public function modalform_group_user()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        echo $this->load->view('page/'.$this->data['department'].'/master/modal_group_user',$this->data,true);
    }

    public function save_group_user()
    {
        $input = $this->getInputToken();
        $dataSave = array(
            'GroupAuth' => $input['groupName'],
        );
        $this->db->insert('db_admission.cfg_group_user', $dataSave);
    }

    public function update_group_user()
    {
        $input = $this->getInputToken();
        $ID = $input['ID'];
        $GroupAuth = $input['GroupAuth'];
        $sql = "update db_admission.cfg_group_user set GroupAuth = ? where ID = ? ";
        $query=$this->db->query($sql, array($GroupAuth,$ID));
    }

    public function delete_group_user()
    {
        $input = $this->getInputToken();
        $sql = "delete from db_admission.cfg_group_user where ID = ".$input['ID'];
        $query=$this->db->query($sql, array());
    }

    public function menu_group()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/previleges',$this->data,true);
        $this->temp($content);
    }

    public function edit_auth_user()
    {
        $input = $this->getInputToken();
        $dataSave = array(
            'G_user' => $input['valuee'],
        );
        $this->db->where('NIP', $input['NIP']);
        $this->db->update('db_admission.previleges_guser', $dataSave);
    }

    public function add_auth_user()
    {
        error_reporting(0);
        $input = $this->getInputToken();
        $dataSave = array(
            'NIP' => $input['NIP'],
            'G_user' => $input['GroupUser'],
        );
        $this->db->insert('db_admission.previleges_guser', $dataSave);

    }

    public function delete_authUser()
    {
        $input = $this->getInputToken();
        $sql = "delete from db_admission.previleges_guser where NIP = '".$input['NIP']."'";
        $query=$this->db->query($sql, array());
    }

    public function getAuthDataTables()
    {
        $requestData= $_REQUEST;
        // print_r($requestData);
        $totalData = $this->m_master->getCountAllDataAuth('db_admission.previleges_guser');

        // get NIP
        $NIP = $this->session->userdata('NIP');
        $get = $this->m_master->caribasedprimary('db_admission.previleges_guser','NIP',$NIP);
        if ($get[0]['G_user'] == 1) {
            if( !empty($requestData['search']['value']) ) {
                $sql = 'SELECT a.NIP,b.Name,a.G_user FROM db_admission.previleges_guser as a join db_employees.employees as b
                        on a.NIP = b.NIP  left join db_admission.cfg_group_user as cgu on a.G_user = cgu.ID';

                $sql.= ' where a.NIP LIKE "'.$requestData['search']['value'].'%" or b.Name LIKE "%'.$requestData['search']['value'].'%" or cgu.GroupAuth LIKE "%'.$requestData['search']['value'].'%"';
                $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

            }
            else {
                 $sql = 'SELECT a.NIP,b.Name,a.G_user FROM db_admission.previleges_guser as a join db_employees.employees as b
                         on a.NIP = b.NIP  left join db_admission.cfg_group_user as cgu on a.G_user = cgu.ID';
                 $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

            }
        }
        else
        {
            if( !empty($requestData['search']['value']) ) {
                $sql = 'SELECT a.NIP,b.Name,a.G_user FROM db_admission.previleges_guser as a join db_employees.employees as b
                        on a.NIP = b.NIP  left join db_admission.cfg_group_user as cgu on a.G_user = cgu.ID';

                $sql.= ' where a.NIP LIKE "'.$requestData['search']['value'].'%" or b.Name LIKE "%'.$requestData['search']['value'].'%" and a.G_user != 1 or cgu.GroupAuth LIKE "%'.$requestData['search']['value'].'%"';
                $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

            }
            else {
                 $sql = 'SELECT a.NIP,b.Name,a.G_user FROM db_admission.previleges_guser as a join db_employees.employees as b
                         on a.NIP = b.NIP and a.G_user != 1 left join db_admission.cfg_group_user as cgu on a.G_user = cgu.ID';
                 $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

            }
        }

        // if( !empty($requestData['search']['value']) ) {
        //     $sql = 'SELECT a.NIP,b.Name,a.G_user FROM db_reservation.previleges_guser as a join db_employees.employees as b
        //             on a.NIP = b.NIP ';

        //     $sql.= ' where a.NIP LIKE "'.$requestData['search']['value'].'%" or b.Name LIKE "%'.$requestData['search']['value'].'%"';
        //     $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

        // }
        // else {
        //      $sql = 'SELECT a.NIP,b.Name,a.G_user FROM db_reservation.previleges_guser as a join db_employees.employees as b
        //              on a.NIP = b.NIP ';
        //      $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

        // }

        $query = $this->db->query($sql)->result_array();

        if ($get[0]['G_user'] == 1) {
            $getGroupUser = $this->m_master->showData_array('db_admission.cfg_group_user');
        }
        else
        {
            $getGroupUser = $this->m_master->getDataWithoutSuperAdminGlobal('db_admission.cfg_group_user');
        }

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];

            $nestedData[] = $row['NIP'];
            $nestedData[] = $row['Name'];

            $combo = '<select class="full-width-fix select grouPAuth btn-edit" NIP = "'.$row['NIP'].'">';
            for ($j=0; $j < count($getGroupUser); $j++) {
                if ($getGroupUser[$j]['ID'] == $row['G_user']) {
                     $combo .= '<option value = "'.$getGroupUser[$j]['ID'].'" selected>'.$getGroupUser[$j]['GroupAuth'].'</option>';
                }
                else
                {
                    $combo .= '<option value = "'.$getGroupUser[$j]['ID'].'">'.$getGroupUser[$j]['GroupAuth'].'</option>';
                }
            }

            $combo .= '</select>';

            $nestedData[] = $combo;

            $btn = '<button class="btn btn-danger btn-sm btn-delete btn-delete-group" NIP = "'.$row['NIP'].'"><i class="fa fa-trash" aria-hidden="true"></i></button>';

            $nestedData[] = $btn;
            $data[] = $nestedData;
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

    public function submit_upload_announcement()
    {
        // upload file
        $filename = 'Announcenment.pdf';
        $config['upload_path']   = path_register_online.'/upload/';
        $config['overwrite'] = TRUE;
        $config['allowed_types'] = '*';
        $config['file_name'] = $filename;
        //$config['max_size']      = 100;
        //$config['max_width']     = 300;
        //$config['max_height']    = 300;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('fileData')) {
           // return $error = $this->upload->display_errors();
           echo json_encode(array('msg' => 'The file did not upload successfully','status' => 0));
           //$this->load->view('upload_form', $error);
        }

        else {
          // return $data =  $this->upload->data();
          echo json_encode(array('msg' => 'The file has been successfully uploaded','status' => 1));
           //$this->load->view('upload_success', $data);
        }
    }

    public function set_tahun_ajaran()
    {
        // get tahun ajaran
        $t = $this->m_master->showData_array('db_admission.set_ta');
        $this->data['tahun'] = date('Y');
        if (count($t) > 0) {
           $this->data['tahun'] = $t[0]['Ta'];
        }

        $content = $this->load->view('page/'.$this->data['department'].'/master/set_tahun_ajaran2',$this->data,true);
        $this->temp($content);
    }

    public function submit_set_tahun_ajaran()
    {
        $input = $this->getInputToken();
        $Ta = $input['Ta'];
        $sql = "update db_admission.set_ta set Ta = '".$Ta."'";
        $query=$this->db->query($sql, array());
    }

    public function reset_va()
    {
        $this->auth_ajax();
        $this->load->model('finance/m_finance');
        $sql = "select VA from db_admission.va_generate where VA_Status != 1";
        $query=$this->db->query($sql, array())->result_array();
        if (count($query) > 0) {
            for ($i=0; $i < count($query); $i++) {
                $VA = $query[$i]['VA'];
                try{
                    $va_log = $this->m_finance->cari_va($VA);
                    if ($va_log['msg'] == '') { // VA aktif
                        $data = $va_log['data'];
                        // print_r($data);die();
                        $DeadLinePayment = date('Y-m-d H:i:s');
                       $update = $this->m_finance->update_va_Payment($data['Invoice'],$DeadLinePayment, $data['Nama'], $data['EmailPU'],$data['BilingID'],$routes_table = '',$desc = 'Close by Reset VA');
                       // if ($update['status'] == 1) {
                       //   $dataSave = array(
                       //           'VA_Status' => 1,
                       //                   );
                       //   $this->db->where('VA',$VA);
                       //   $this->db->update('db_admission.va_generate', $dataSave);
                       // }
                       // else
                       // {
                       //   echo 'Error';
                       // }
                    }
                    else
                    {
                        // VA tidak aktif
                    }
                    $dataSave = array(
                            'VA_Status' => 1,
                                    );
                    $this->db->where('VA',$VA);
                    $this->db->update('db_admission.va_generate', $dataSave);
                }
                catch(Exception $e)
                {
                    continue;
                }
            }


        }
    }

    public function globalformulir()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/globalformulir',$this->data,true);
        $this->temp($content);
    }

    public function generate_formulir_global()
    {
        $input = $this->getInputToken();
        $prefix = substr($input['Angkatan'], 2,4);
        for ($i=$input['Start']; $i <= $input['End']; $i++) {
           // check length max 4
            $code = $i;
            $c = strlen($i);
            for ($j=0; $j < 4-$c; $j++) {
                $code = '0'.$code;
            }
            $code = $prefix.$code;

            // check data already exist in table formulir_number_global
            $chk = $this->m_master->caribasedprimary('db_admission.formulir_number_global','FormulirCodeGlobal',$code);
            // if (count($chk) == 1) {
            if (count($chk) > 0 ) {
                // check data telah used atau unsued untuk update TypeFormulir
                $Status = $chk[0]['Status'];
                if ($Status == 0) {
                    // update TypeFormulir
                    $arr_field['TypeFormulir'] = $input['TypeFormulir'];
                    $this->db->where('FormulirCodeGlobal',$code);
                    $this->db->update('db_admission.formulir_number_global',$arr_field);
                }
                continue;
            }
            else
            {
                $arr_field = array(
                    'FormulirCodeGlobal' => $code,
                    'Years' => $input['Angkatan'],
                    'Status' => 0,
                    'Division' => $input['division'],
                    'TypeFormulir' => $input['TypeFormulir'],
                );
                // check data already using in formulir_number_offline_m
                  $chk2 = $this->m_master->caribasedprimary('db_admission.formulir_number_offline_m','No_Ref',$code);
                  if (count($chk2) == 1) {
                      $arr_field['Status'] = 1;
                  }
                  $this->db->insert('db_admission.formulir_number_global', $arr_field);
            }

        }

    }

    public function import_sales_regional()
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

           $arr_bulan = array(
               'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Des'
           );

           $arr_temp = array();
           $No_Ref = '';
          for ($i=2; $i < ($CountRow + 1); $i++) {
            $SalesNIP = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
            $SchoolID = $objWorksheet->getCellByColumnAndRow(1, $i)->getCalculatedValue();
            $dataSave = array(
                    'SalesNIP' => $SalesNIP,
                    'SchoolID' => $SchoolID,
                    'CreateAT' => date('Y-m-d'),
                            );
            $this->db->insert('db_admission.sales_school_m', $dataSave);

          }

          echo json_encode(array('status'=> 1,'msg' => '','No_Ref' => $No_Ref));
        }
        else
        {
          exit('No direct script access allowed');
        }
    }

    public function capacity_tahun_ajaran($tokenID_crm_period)
    {
        $key = "UAP)(*";
        $ID_crm_period =$this->jwt->decode($tokenID_crm_period,$key);
        $this->m_master->__fillTA_Capacity($ID_crm_period);
        // get Year
        $G_dt = $this->m_master->caribasedprimary('db_admission.crm_period','ID',$ID_crm_period);
        $this->data['NamePeriod'] = $G_dt[0]['Name'];
        $this->data['ID_crm_period'] = $ID_crm_period;
        // get data
        $this->data['G_data'] = $this->m_master->__data_capacity(array('ID_crm_period' => $ID_crm_period));
        $content = $this->load->view('page/'.$this->data['department'].'/master/tahun_ajaran_capacity',$this->data,true);
        $this->temp($content);
    }

    public function save_capacity_tahun_ajaran()
    {
        $Input = $this->getInputToken();
        $dt = json_decode(json_encode($Input),true);
        for ($i=0; $i < count($dt); $i++) {
            $Capacity = $dt[$i]['Capacity'];
            $ID = $dt[$i]['ID'];
            $this->db->where('ID',$ID);
            $this->db->update('db_admission.ta_setting',array('Capacity' => $Capacity));
        }
        echo json_encode(1);
    }

}
