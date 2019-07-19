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

    private function agregatorPrevilege($viewPage){

        $data = $this->db->get_where('db_agregator.agregator_menu',array(
            'View' => $viewPage
        ))->result_array();


        $result = '0';

        if(count($data)>0){

            $checkMenu = $this->db->query('SELECT au.* FROM db_agregator.agregator_user_member aum 
                                                LEFT JOIN db_agregator.agregator_user au ON (aum.AUPID = au.ID)
                                                WHERE aum.NIP = "'.$this->session->userdata('NIP').'" 
                                                LIMIT 1')->result_array();


            $MyMenu = (count($checkMenu)>0) ? $checkMenu[0]['Menu'] : "[]" ;

            $MyMenu = json_decode($MyMenu);

//            print_r($MyMenu);

            if(count($MyMenu)>0){

                // Cek apakah ada
                if(in_array($data[0]['ID'],$MyMenu)){
                    $result = '1';
                }

            }


        }

        return $result;

    }

    public function menu_agregator($page){

        $dataMenu = $this->db->order_by('ID','ASC')->get('db_agregator.agregator_menu_header')->result_array();
        if(count($dataMenu)>0){
            $i = 0;
            foreach ($dataMenu AS $itm){
                $dataMenu[$i]['Menu'] = $this->db->get_where('db_agregator.agregator_menu',array('MHID' => $itm['ID']))->result_array();
                $i++;
            }
        }

        $data['page'] = $page;
        $data['listMenu'] = $dataMenu;


        $content = $this->load->view('page/agregator/menu_agregator',$data,true);
        $this->temp($content);
    }

    public function setting(){

        $dataSetting = $this->db->get_where('db_agregator.agregator_admin',array(
            'NIP' => $this->session->userdata('NIP')
        ))->result_array();

        $data['access'] = (count($dataSetting)>0) ? '1' : '0';
        $page = $this->load->view('page/agregator/setting',$data,true);
        $this->menu_agregator($page);


    }

    public function akreditasi_eksternal()
    {
        $viewPage = 'akreditasi_eksternal';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }

    public function akreditasi_internasional()
    {
        $viewPage = 'akreditasi_internasional';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }

    public function audit_keuangan_eksternal()
    {
        $viewPage = 'audit_keuangan_eksternal';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }

    public function akreditasi_program_studi()
    {
        $viewPage = 'akreditasi_program_studi';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }

    public function kerjasama_perguruan_tinggi()
    {
        $viewPage = 'kerjasama_perguruan_tinggi';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }

    // ========

    public function seleksi_mahasiswa_baru()
    {
        $viewPage = 'seleksi_mahasiswa_baru';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }
    public function mahasiswa_asing()
    {
        $viewPage = 'mahasiswa_asing';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }

    public function kecukupan_dosen(){

        $viewPage = 'kecukupan_dosen';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }


    public function uploadFile(){

        $fileName = $this->input->get('fileName');
        $old = $this->input->get('old');
        $id = $this->input->get('id');

        $config['upload_path']          = './uploads/agregator/';
        $config['allowed_types']        = '*';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;

        if($old!='' && is_file('./uploads/agregator/'.$old)){
            unlink('./uploads/agregator/'.$old);
        }


        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());
            return print_r(json_encode($error));
        }
        else {
            $this->db->where('ID', $id);
            $this->db->update('db_agregator.university_collaboration',array(
                'File' => $fileName
            ));
        }
    }




}
