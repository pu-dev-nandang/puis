<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        if($this->session->userdata('loggedIn')){
            $departement = $this->__getDepartement();
            $this->load->model('master/m_master');
            $this->load->model('m_menu');
            $this->load->model('m_menu2');
            // define config Virtual Account
            if (!defined('VA_client_id')) {
                $getCFGVA = $this->m_master->showData_array('db_va.cfg_bank');
                define('VA_client_id',$getCFGVA[0]['client_id'] ,true);
                define('VA_secret_key',$getCFGVA[0]['secret_key'] ,true);
                define('VA_url',$getCFGVA[0]['url'] ,true);
            }

        } else {
            redirect(base_url());
        }

        $this->load->library('JWT');
        $this->load->library('google');
    }

    public function checkMaintenanceMode(){


        // Cek mode
        $dataMode = $this->db->get_where('db_it.m_config',array(
            'ID' => 3
        ))->result_array();

        if($dataMode[0]['MaintenanceMode']=='1'){
            $data['include'] = $this->load->view('template/include','',true);
            $this->load->view('template/maintenance',$data);
        }


    }


}

// deklarasi nama function yang akan diimplementasikan ke sub class dibawah nya
abstract class MyAbstract  extends MY_Controller{
   abstract protected function template($content);
   abstract protected function blank_temp($content);
   abstract protected function menu_header();
   abstract protected function menu_navigation();
   abstract protected function crumbs();
   abstract protected function __getDepartement();
   abstract protected function __setDepartement($dpt);
}

abstract class Globalclass extends MyAbstract{

    public function __construct()
    {
        parent::__construct();

//        $this->checkMaintenanceMode();


    }

    public function template($content)
    {

        $data['include'] = $this->load->view('template/include','',true);

        $depertment = $this->__getDepartement();
        if($depertment!=null && $depertment!=''){
            $data['header'] = $this->menu_header();
            $data['navigation'] = $this->menu_navigation();
            $data['crumbs'] = $this->crumbs();

            $data['content'] = $content;
            $this->load->view('template/template',$data);
        } else {
            $this->load->view('template/userfalse',$data);
        }


    }

    public function page404(){
        $data['include'] = $this->load->view('template/include','',true);
        $this->load->view('template/404page',$data);
    }

    public function blank_temp($content){
        $data['include'] = $this->load->view('template/include','',true);
        $data['content'] = $content;


        $this->load->view('template/blank',$data);
    }


    protected function menu_header(){

        $nav_departement['departement'] = $this->__getDepartement();
        $data['page_departement'] = $this->load->view('template/navigation_departement',$nav_departement,true);

        $exp_name = explode(" ",$this->session->userdata('Name'));
        $data['name']= (count($exp_name)>0) ? $exp_name[0] : $this->session->userdata('Name');

        $MainPosition = $this->session->userdata('PositionMain');
        $data['rule_service'] = $this->m_master->__getService($MainPosition['IDDivision']);

        $page = $this->load->view('template/header',$data,true);
        return $page;
    }

    protected function menu_navigation(){
        $nav = $this->__getDepartement();
        $data['departement'] = $nav;
        $page = $this->load->view('page/'.$data['departement'].'/menu_navigation','',true);
        return $page;
    }

    protected function crumbs(){
        $data['crumbs_departement'] = $this->session->userdata('departementNavigation');
        $data['segment'] = $this->uri->segment_array();
        $page = $this->load->view('template/crumbs',$data,true);
        return $page;
    }


    //==== Get Set ===
    public function __getDepartement(){
        $nav = $this->session->userdata('departementNavigation');
        return $nav;
    }

    public function __setDepartement($dpt)
    {
        $this->session->set_userdata('departementNavigation', ''.$dpt);
    }

    public function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function config_pagination_default_ajax($total_rows = 999,$per_page = 10,$uri_segment = 6)
    {
        $config = array();
        $config["base_url"] = "#";
        $config["total_rows"] =  $total_rows;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = $uri_segment;
        $config["use_page_numbers"] = TRUE;
        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';
        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';
        $config['next_link'] = '&gt;';
        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_link"] = "&lt;";
        $config["prev_tag_open"] = "<li>";
        $config["prev_tag_close"] = "</li>";
        $config["cur_tag_open"] = "<li class='active'><a href='#'>";
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] = "<li>";
        $config["num_tag_close"] = "</li>";
        $config["num_links"] = 4;

        return $config;
    }

}

class Academic_Controler extends Globalclass{

    public function __construct()
    {
        parent::__construct();
//        print_r('ok');exit;
        $this->m_menu2->set_model('academic_sess','auth_academic_sess','menu_academic_sess','menu_academic_grouping','db_academic');
    }
}

class HR_Controler extends Globalclass{

    public function __construct()
    {
        parent::__construct();
    }
}

abstract class Student_Life extends Globalclass{
    public function __construct()
    {
        parent::__construct();
    }
}

abstract class Lpmi extends Globalclass{
    public function __construct()
    {
        parent::__construct();
    }
}

abstract class Library extends Globalclass{
    public function __construct()
    {
        parent::__construct();
    }
}

abstract class Admission_Controler extends Globalclass{
    public $GlobalVariableAdi = array('url_registration' => 'http://demo.web.podomorouniversity.ac.id/registeronline/');
    public $GlobalData = array('NameMenu' => '');

    public function __construct()
    {
        parent::__construct();
        $this->m_menu->set_model('admission_sess','auth_admission_sess','menu_admission_sess','menu_admission_grouping','db_admission');

        $this->GetNameMenu();
    }

    public $path_upload_regOnline = path_register_online.'document/';

    private function GetNameMenu()
    {
        $currentURL = current_url();
        $Slug = str_replace(serverRoot.'/', '', $currentURL);
        $get = $this->m_master->caribasedprimary('db_admission.cfg_sub_menu','Slug',$Slug);
        if (count($get) > 0) {
            if ($get[0]['SubMenu2'] == 'Empty') {
                $this->GlobalData['NameMenu'] = $get[0]['SubMenu1'];
            }
            else
            {
                $this->GlobalData['NameMenu'] = $get[0]['SubMenu1'].'-'.$get[0]['SubMenu2'];
            }
            
        }
    }

    public function temp($content)
    {
        $this->template($content);
    }


    // overide function
    // public function template($content)
    // {

    //     $data['include'] = $this->load->view('template/include','',true);

    //     $data['header'] = $this->menu_header();
    //     $data['navigation'] = $this->menu_navigation();
    //     $data['crumbs'] = $this->crumbs();

    //     $data['content'] = $content;
    //     $this->load->view('template/template',$data);

    // }

    // // overide function
    // public function  menu_navigation(){
    //     $data['departement'] = $this->__getDepartement();
    //     $page = $this->load->view('page/'.$data['departement'].'/menu_navigation','',true);
    //     return $page;
    // }

    public function auth_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
    }

}


abstract class Finnance_Controler extends Globalclass{
    public $GlobalVariableAdi = array('url_registration' => 'http://demo.web.podomorouniversity.ac.id/registeronline/');

    public function __construct()
    {
        parent::__construct();
        // save session using VA or NOT
        $this->get_PolicySYS();
    }

    public function get_PolicySYS()
    {
        if (!$this->session->userdata('finance_auth_Policy_SYS')) {
            $get = $this->m_master->showData_array('db_finance.cfg_policy_sys');
            $this->session->set_userdata('finance_auth_Policy_SYS',$get[0]['VA_active']);
        }
    }


}


abstract class Vreservation_Controler extends Globalclass{

    public $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('vreservation/m_reservation');
        if (!$this->session->userdata('auth_vreservation_sess')) {
            $this->getAuthVreservation();
        }
        // read policy
        $dayPolicy = $this->session->userdata('V_BookingDay');
        $dateDay = date('Y-m-d');
        $dateDay = date('Y-m-d', strtotime($dateDay . ' +'.$dayPolicy.' day'));
        $this->data['dateDay'] = $dateDay;
        $this->data['dayPolicy'] = $dayPolicy;
    }

    public $pathView = 'page/vreservation/';

    public function temp($content)
    {
        $this->template($content);
    }


    // overide function
    public function template($content)
    {

        $data['include'] = $this->load->view('template/include','',true);

        $data['header'] = $this->menu_header();
        $data['navigation'] = $this->menu_navigation();
        $data['crumbs'] = $this->crumbs();

        $data['content'] = $content;
        $this->load->view('template/template',$data);

    }

    // overide function
    public function  menu_navigation(){
        $data['departement'] = $this->__getDepartement();
        $page = $this->load->view('page/vreservation/menu_navigation','',true);
        return $page;
    }

    private function getAuthVreservation()
    {
        $data = array();
        $getDataMenu = $this->m_master->getMenuGroupUser($this->session->userdata('NIP'),'db_reservation');
        $data_sess = array();
        if (count($getDataMenu) > 0) {
            $this->session->set_userdata('auth_vreservation_sess',1);
            $this->session->set_userdata('menu_vreservation_sess',$getDataMenu);
            $this->session->set_userdata('menu_vreservation_grouping',$this->groupBYMenu_sess());
            // save session group user
            $this->m_reservation->save_sess_policy_grouping();
        }
    }

    public function groupBYMenu_sess()
    {
        $DataDB = $this->session->userdata('menu_vreservation_sess');
        $arr = array();
        for ($i=0; $i < count($DataDB); $i++) {
            $submenu1 = $this->m_menu->getSubmenu1BaseMenu_grouping($DataDB[$i]['ID_menu'],'db_reservation');
            $arr2 = array();
            for ($k=0; $k < count($submenu1); $k++) { 
                $submenu2 = $this->m_menu->getSubmenu2BaseSubmenu1_grouping($submenu1[$k]['SubMenu1'],'db_reservation',$DataDB[$i]['ID_menu']);
                $arr2[] = array(
                    'SubMenu1' => $submenu1[$k]['SubMenu1'],
                    'Submenu' => $submenu2,
                );
            }

            $arr[] =array(
                'Menu' => $DataDB[$i]['Menu'],
                'Icon' => $DataDB[$i]['Icon'],
                'Submenu' => $arr2

            );
            
        }
        return $arr;
    }

    public function auth_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
    }

    public function checkAuth_user()
    {
        $base_url = base_url();
        $currentURL = current_url();
        $getURL = str_replace($base_url,"",$currentURL);
        $chk = $this->m_reservation->chkAuthDB_Base_URL_vreservation($getURL);

        if (!$this->input->is_ajax_request()) {
            if (count($chk) == 0) {
                show_404($log_error = TRUE); 
            }
            else
            {
                if ($chk[0]['read'] == 0) {
                   show_404($log_error = TRUE); 
                }
            }
            
        }
        
    }

}


abstract class Budgeting_Controler extends Globalclass{

    public $data = array();


    public function __construct()
    {
        parent::__construct();
        $this->load->model('budgeting/m_budgeting');
        $this->load->model('budgeting/m_global');
        $this->load->model('budgeting/m_pr_po');
        $this->load->model('budgeting/m_spb');

        $this->session->unset_userdata('auth_budgeting_sess');
        $this->session->unset_userdata('menu_budgeting_sess');
        $this->session->unset_userdata('menu_budgeting_grouping');
        
        $PositionMain = $this->session->userdata('PositionMain');
        $DivisionPage = $PositionMain['Division'];
        $IDPosition = $PositionMain['IDPosition'];
        $G_departementNavigation = $this->m_master->caribasedprimary('db_employees.division','ID',$this->session->userdata('IDdepartementNavigation'));
        //print_r($this->session->userdata('IDdepartementNavigation'));die();
        $departementNavigation = $G_departementNavigation[0]['Division'];
        $this->data['department'] = ($PositionMain['IDDivision'] == 12 || $IDPosition <= 6)? $departementNavigation : $DivisionPage;
        $this->data['department'] = strtolower($this->data['department']);
        $this->data['department'] = str_replace(" ", "-", $this->data['department']);
        $this->data['IDdepartment'] = $PositionMain['IDDivision'];
        // set session division 
        $this->session->set_userdata('IDDepartement',$PositionMain['IDDivision']);

        // adding menu department
        // $MenuDepartement= ($this->data['IDdepartment'] == 12) ? 'NA.'.$this->session->userdata('IDdepartementNavigation'):'NA.'.$this->data['IDdepartment']; 
        $MenuDepartement= 'NA.'.$this->session->userdata('IDdepartementNavigation'); 

        if ($this->data['IDdepartment'] == 15 || $this->data['IDdepartment'] == 14) {
            $MenuDepartement= 'AC.'.$this->session->userdata('prodi_active_id');
        }

        if ($MenuDepartement == 'NA.34') {
            $MenuDepartement = 'FT.'.$this->session->userdata('faculty_active_id');
        }

        $this->getAuthSession($MenuDepartement);
    }

    public function temp($content)
    {
        $this->template($content);
    }


    // overide function
    public function template($content)
    {

        $data['include'] = $this->load->view('template/include','',true);

        $data['header'] = $this->menu_header();
        $data['navigation'] = $this->menu_navigation();
        $data['crumbs'] = $this->crumbs();

        $data['content'] = $content;
        $this->load->view('template/template',$data);

    }

    // overide function
    public function  menu_navigation(){
        $data['departement'] = $this->__getDepartement();
        $page = $this->load->view('global/budgeting/menu_navigation','',true);
        return $page;
    }

    public function auth_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
    }

    public function getAuthSession($MenuDepartement)
    {
        $data = array();
        if ($MenuDepartement == 'NA.15' || $MenuDepartement == 'NA.14') {
            $MenuDepartement = 'AC.'.$this->session->userdata('prodi_active_id');
        }

        if ($MenuDepartement == 'NA.34') {
            $MenuDepartement = 'FT.'.$this->session->userdata('faculty_active_id');
        }

        if ($MenuDepartement == 'NA.36') { // other division
            $PositionMain = $this->session->userdata('PositionMain');
            $MenuDepartement = 'NA.'.$PositionMain['IDDivision'];
        }

        $getDataMenu = $this->m_budgeting->getMenuGroupUser($this->session->userdata('NIP'),$MenuDepartement);
        $this->session->set_userdata('IDDepartementPUBudget',$MenuDepartement);
        // print_r($MenuDepartement);die();
        $data_sess = array();
        if (count($getDataMenu) > 0) {
            $this->session->set_userdata('auth_budgeting_sess',1);
            $this->session->set_userdata('menu_budgeting_sess',$getDataMenu);
            $this->session->set_userdata('menu_budgeting_grouping',$this->groupBYMenu_sess());
            // $this->session->set_userdata('role_user_budgeting',$this->m_budgeting->role_user_budgeting());
        }
    }

    public function groupBYMenu_sess()
    {
        $DataDB = $this->session->userdata('menu_budgeting_sess');
        $arr = array();
        for ($i=0; $i < count($DataDB); $i++) {
            $submenu1 = $this->m_budgeting->getSubmenu1BaseMenu_grouping($DataDB[$i]['ID_menu'],'db_budgeting');
            // print_r($submenu1);
            $arr2 = array();
            for ($k=0; $k < count($submenu1); $k++) { 
                $submenu2 = $this->m_budgeting->getSubmenu2BaseSubmenu1_grouping($submenu1[$k]['SubMenu1'],'db_budgeting',$DataDB[$i]['ID_menu']);
                $arr2[] = array(
                    'SubMenu1' => $submenu1[$k]['SubMenu1'],
                    'Submenu' => $submenu2,
                );
            }

            $arr[] =array(
                'Menu' => $DataDB[$i]['Menu'],
                'Icon' => $DataDB[$i]['Icon'],
                'Submenu' => $arr2

            );
            
        }
        // print_r($arr);die();
        return $arr;
    }

}

abstract class Purchasing_Controler extends Globalclass{

    public $data = array();


    public function __construct()
    {
        parent::__construct();

        // add session department budgeting
        $PositionMain = $this->session->userdata('PositionMain');
        $this->session->set_userdata('IDDepartement',$PositionMain['IDDivision']);
        $this->data['IDdepartment'] = $PositionMain['IDDivision'];
        // adding menu department
        $IDDepartementPUBudget= ($PositionMain['IDDivision']== 12) ? 'NA.'.$this->session->userdata('IDdepartementNavigation'):'NA.'.$PositionMain['IDDivision']; 
        $this->session->set_userdata('IDDepartementPUBudget',$IDDepartementPUBudget);
        $this->m_menu2->set_model('purchasing_sess','auth_purchasing_sess','menu_purchasing_sess','menu_purchasing_grouping','db_purchasing');
    }

    public function temp($content)
    {
        $this->template($content);
    }


    // overide function
    // public function template($content)
    // {

    //     $data['include'] = $this->load->view('template/include','',true);

    //     $data['header'] = $this->menu_header();
    //     $data['navigation'] = $this->menu_navigation();
    //     $data['crumbs'] = $this->crumbs();

    //     $data['content'] = $content;
    //     $this->load->view('template/template',$data);

    // }

    // // overide function
    // public function  menu_navigation(){
    //     $data['departement'] = $this->__getDepartement();
    //     $page = $this->load->view('page/'.$data['departement'].'/menu_navigation','',true);
    //     return $page;
    // }

    public function auth_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
    }

}

class Transaksi_Controler extends Purchasing_Controler{
    public function __construct()
    {
        parent::__construct();
    }

    public function page_po($page)
    {
      $page['department'] = parent::__getDepartement();
      $content = $this->load->view('page/'.$page['department'].'/transaksi/po/page',$page,true);
      $this->temp($content);
    }
}


abstract class It_Controler extends Globalclass{
    public $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->m_menu2->set_model('it_sess','auth_it_sess','menu_it_sess','menu_it_grouping','db_it');
    }

    public function temp($content)
    {
        $this->template($content);
    }

    public function auth_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
    }


    // overide function
    // public function template($content)
    // {

    //     $data['include'] = $this->load->view('template/include','',true);

    //     $data['header'] = $this->menu_header();
    //     $data['navigation'] = $this->menu_navigation();
    //     $data['crumbs'] = $this->crumbs();

    //     $data['content'] = $content;
    //     $this->load->view('template/template',$data);

    // }

    // // overide function
    // public function  menu_navigation(){
    //     $data['departement'] = $this->__getDepartement();
    //     $page = $this->load->view('page/'.$data['departement'].'/menu_navigation','',true);
    //     return $page;
    // }

}


abstract class Prodi_Controler extends Globalclass{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('prodi/m_prodi');
        if (!$this->session->userdata('prodi_get')) {
          $this->m_prodi->auth();  
        }
        $this->m_menu2->set_model('prodi_sess','auth_prodi_sess','menu_prodi_sess','menu_prodi_grouping','db_prodi');
    }

}

abstract class Ga_Controler extends Globalclass{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('vreservation/m_reservation');
    }

    public function auth_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
    }

}

class Cooperation_Controler extends Globalclass{

    public function __construct()
    {
        parent::__construct();
    }

    public function temp($content)
    {
        parent::template($content);
    }
}

abstract class Ticket_Controler extends Globalclass{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ticketing/m_general');
    }

    public function temp($content)
    {
        $this->template($content);
    }

    public function menu_ticket($page){
        $data['Authen'] = $this->m_master->showData_array('db_ticketing.rest_setting');
        $data['DepartmentID'] = $this->m_general->getDepartmentNow();
        $data['DepartmentAbbr'] = $this->m_general->DepartmentAbbr($data['DepartmentID']);
        $data['ArrSelectOptionDepartment'] = $this->m_general->getAuthDepartment();
        $data['page'] = $page;
        $content = $this->load->view('dashboard/ticketing/menu_ticketing',$data,true);
        $this->template($content);
    }

    // overide function
    public function template($content)
    {

        $data['include'] = $this->load->view('template/include','',true);
        $data['header'] = $this->menu_header();
        $data['navigation'] = $this->menu_navigation();
        $data['crumbs'] = $this->crumbs();
        $data['ClassContainer'] = 'sidebar-closed';
        $data['content'] = $content;
        $this->load->view('template/template',$data);

    }


}