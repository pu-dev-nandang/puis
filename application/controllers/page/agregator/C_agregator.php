<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_agregator extends Globalclass {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('m_sendemail');
        $this->load->model('database/m_database');
        $this->load->library('JWT');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_agregator($page){

        $dataMenu = $this->db->order_by('ID','ASC')->get('db_it.agregator_menu_header')->result_array();
        if(count($dataMenu)>0){
            $i = 0;
            foreach ($dataMenu AS $itm){
                $dataMenu[$i]['Menu'] = $this->db->get_where('db_it.agregator_menu',array('MHID' => $itm['ID']))->result_array();
                $i++;
            }
        }

        $data['page'] = $page;
        $data['listMenu'] = $dataMenu;
        $content = $this->load->view('page/agregator/menu_agregator',$data,true);
        $this->temp($content);
    }

    public function setting(){
        $page = $this->load->view('page/agregator/setting','',true);
        $this->menu_agregator($page);
    }

    public function akreditasi_eksternal()
    {
        $page = $this->load->view('page/agregator/akreditasi_eksternal','',true);
        $this->menu_agregator($page);
    }

    public function akreditasi_internasional()
    {
        $page = $this->load->view('page/agregator/akreditasi_internasional','',true);
        $this->menu_agregator($page);
    }

    public function audit_keuangan_eksternal()
    {
        $page = $this->load->view('page/agregator/audit_keuangan_eksternal','',true);
        $this->menu_agregator($page);
    }

    public function akreditasi_program_studi()
    {
        $page = $this->load->view('page/agregator/akreditasi_program_studi','',true);
        $this->menu_agregator($page);
    }

    public function kerjasama_perguruan_tinggi()
    {
        $page = $this->load->view('page/agregator/kerjasama_perguruan_tinggi','',true);
        $this->menu_agregator($page);
    }

    // ========

    public function seleksi_mahasiswa_baru()
    {
        $page = $this->load->view('page/agregator/seleksi_mahasiswa_baru','',true);
        $this->menu_agregator($page);
    }
    public function mahasiswa_asing()
    {
        $page = $this->load->view('page/agregator/mahasiswa_asing','',true);
        $this->menu_agregator($page);
    }




}
