<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();



        if($this->session->userdata('loggedIn')){
            $departement = $this->__getDepartement();
            if($departement==''){
//                $this->session->set_userdata('departementNavigation', 'academic');
            }

            // define config Virtual Account
            if (!defined('VA_client_id')) {
                $this->load->model('master/m_master');
                $getCFGVA = $this->m_master->showData_array('db_va.cfg_bank');
                define('VA_client_id',$getCFGVA[0]['client_id'] ,true);
                define('VA_secret_key',$getCFGVA[0]['secret_key'] ,true);
                define('VA_url',$getCFGVA[0]['url'] ,true);
            }

        } else {
            redirect(base_url());
        }

//        $this->load->model('master/m_master');
        $this->load->library('JWT');
        $this->load->library('google');
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
    }

    public function template($content)
    {
        $depertment = $this->__getDepartement();
        $data['include'] = $this->load->view('template/include','',true);
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
//        $data_departement ['departement'] = $this->m_master->get_departement();
//        $data_nav['departement'] = $this->load->view('template/menu/departement',$data_departement,true);

        $nav_departement['departement'] = $this->__getDepartement();
        $data['page_departement'] = $this->load->view('template/navigation_departement',$nav_departement,true);

        $exp_name = explode(" ",$this->session->userdata('Name'));
        $data['name']= (count($exp_name)>0) ? $exp_name[0] : $this->session->userdata('Name');

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
        $config["num_links"] = 1;

        return $config;
    }

}

class Academic_Controler extends Globalclass{

    public function __construct()
    {
        parent::__construct();
    }
}

class HR_Controler extends Globalclass{

    public function __construct()
    {
        parent::__construct();
    }
}

abstract class Admission_Controler extends Globalclass{
    // public $GlobalVariableAdi = array('url_registration' => 'http://10.1.10.230/register/');
    public $GlobalVariableAdi = array('url_registration' => 'http://demo.web.podomorouniversity.ac.id/registeronline/');

    public function __construct()
    {
        parent::__construct();
        // check user auth
        if (!$this->session->userdata('admission_sess')) {
            $check = $this->authAdmission();
            if (!$check) {
                // not authorize
                redirect(base_url().'dashboard');
            }
            else
            {
                if (!$this->session->userdata('auth_admission_sess')) {
                    $this->getAuthAdmission();
                }
            }
        }
    }

    private function authAdmission()
    {
        $NIP = $this->session->userdata('NIP');
        $this->load->model('master/m_master');
        $getData = $this->m_master->getUserAdmissionAuth($NIP);
        if (count($getData) > 0) {
            $this->session->set_userdata('admission_sess',1);
            return true;
        }

        return false;
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
        $page = $this->load->view('page/'.$data['departement'].'/menu_navigation','',true);
        return $page;
    }

    private function getAuthAdmission()
    {
        $data = array();
        $this->load->model('master/m_master');
        $getDataMenu = $this->m_master->getMenuUser($this->session->userdata('NIP'));
        $data_sess = array();
        if (count($getDataMenu) > 0) {
            $this->session->set_userdata('auth_admission_sess',1);
            $this->session->set_userdata('menu_admission_sess',$getDataMenu);
            $this->session->set_userdata('menu_admission_grouping',$this->groupBYMenu_sess());
        }
    }

    public function groupBYMenu_sess()
    {
        $DataDB = $this->session->userdata('menu_admission_sess');
        $this->load->model('master/m_master');
        $arr = array();
        for ($i=0; $i < count($DataDB); $i++) {
            $submenu1 = $this->m_master->getSubmenu1BaseMenu($DataDB[$i]['ID_menu']);
            $arr2 = array();
            for ($k=0; $k < count($submenu1); $k++) { 
                $submenu2 = $this->m_master->getSubmenu2BaseSubmenu1($submenu1[$k]['SubMenu1']);
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
        $this->load->model('master/m_master');
        $chk = $this->m_master->chkAuthDB_Base_URL($getURL);

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


abstract class Finnance_Controler extends Globalclass{
    // public $GlobalVariableAdi = array('url_registration' => 'http://10.1.10.230/register/');
    public $GlobalVariableAdi = array('url_registration' => 'http://demo.web.podomorouniversity.ac.id/registeronline/');

    public function __construct()
    {
        parent::__construct();
    }
}


abstract class Vreservation_Controler extends Globalclass{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('auth_vreservation_sess')) {
            $this->getAuthVreservation();
        }
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
        $this->load->model('master/m_master');
        $getDataMenu = $this->m_master->getMenuUser($this->session->userdata('NIP'),'db_reservation');
        $data_sess = array();
        if (count($getDataMenu) > 0) {
            $this->session->set_userdata('auth_vreservation_sess',1);
            $this->session->set_userdata('menu_vreservation_sess',$getDataMenu);
            $this->session->set_userdata('menu_vreservation_grouping',$this->groupBYMenu_sess());
        }
    }

    public function groupBYMenu_sess()
    {
        $DataDB = $this->session->userdata('menu_vreservation_sess');
        $this->load->model('master/m_master');
        $arr = array();
        for ($i=0; $i < count($DataDB); $i++) {
            $submenu1 = $this->m_master->getSubmenu1BaseMenu($DataDB[$i]['ID_menu'],'db_reservation');
            $arr2 = array();
            for ($k=0; $k < count($submenu1); $k++) { 
                $submenu2 = $this->m_master->getSubmenu2BaseSubmenu1($submenu1[$k]['SubMenu1'],'db_reservation');
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
        $this->load->model('master/m_master');
        $chk = $this->m_master->chkAuthDB_Base_URL($getURL);

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