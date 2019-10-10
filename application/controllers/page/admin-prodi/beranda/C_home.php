<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_home extends Prodi_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        // load sambutan_model
        $this->load->model('admin-prodi/beranda/m_home');
        $this->load->helper(array('form', 'url'));
    }


    function temp($content)
    {
        parent::template($content);
    }

// ===== Slide ======
    function slide()
    {
        // Database
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/beranda/v_slider',$data,true);
        parent::template($content);
    }
    

// ===== why ======

    private function menu_whyus($page){
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$data['department'].'/beranda/whyus/menu_whyus',$data,true);
        parent::template($content);
    }
    public function whychoose(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/beranda/whyus/whyus',$data,true);
        $this->menu_whyus($page);
    }
    public function about(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/beranda/whyus/about',$data,true);
        $this->menu_whyus($page);
    }
    public function excellence(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/beranda/whyus/excellence',$data,true);
        $this->menu_whyus($page);
    }
    public function graduate_profile(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/beranda/whyus/graduate_profile',$data,true);
        $this->menu_whyus($page);
    }
    public function career_opportunities(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/beranda/whyus/career_opportunities',$data,true);
        $this->menu_whyus($page);
    }

    private function menu_visimisi($pagevisimisi){
        $data['department'] = parent::__getDepartement();
        $data['pagevisimisi'] = $pagevisimisi;
        $content = $this->load->view('page/'.$data['department'].'/about/menu_visimisi',$data,true);
        parent::template($content);
    }
// ===== overview ======
    function overview()
    {
        // Database
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/about/v_sambutan',$data,true);
        parent::template($content);
    }
    public function vision(){
        $data['department'] = parent::__getDepartement();
        $pagevisimisi = $this->load->view('page/'.$data['department'].'/about/v_vision',$data,true);
        $this->menu_visimisi($pagevisimisi);
    }
    public function mission(){
        $data['department'] = parent::__getDepartement();
        $pagevisimisi = $this->load->view('page/'.$data['department'].'/about/v_mission',$data,true);
        $this->menu_visimisi($pagevisimisi);
    }
//    function whychoose()
//    {
//        // Database
//        $data['department'] = parent::__getDepartement();
//        $content = $this->load->view('page/'.$data['department'].'/beranda/v_why',$data,true);
//        parent::template($content);
//    }
// ===== Call to Action ======
    function calltoaction()
    {
        // Database
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/beranda/v_call',$data,true);
        parent::template($content);
    }
// ===== Call to Action ======
    function testimoni()
    {
        // Database
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/beranda/v_testimoni',$data,true);
        parent::template($content);
    }
    // ===== Cliens ======
    function partner()
    {
        // Database
        $data['department'] = parent::__getDepartement();
        $content = $this->load->view('page/'.$data['department'].'/beranda/v_partner',$data,true);
        parent::template($content);
    }

        
}

