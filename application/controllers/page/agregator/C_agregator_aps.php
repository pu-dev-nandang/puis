<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_agregator_aps extends Globalclass {

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

        // $data = $this->db->get_where('db_agregator.agregator_menu',array(
        //     'View' => $viewPage,
        // ))->result_array();
        $sql = 'select a.* from db_agregator.agregator_menu as a 
                join db_agregator.agregator_menu_header as b on a.MHID = b.ID
                where b.Type = "APS" and a.View = "'.$viewPage.'"
        ';
        $data = $this->db->query($sql,array())->result_array();

        $result = '0';

        if(count($data)>0){

            $checkMenu = $this->db->query('SELECT au.* FROM db_agregator.agregator_user_member_aps aum 
                                                LEFT JOIN db_agregator.agregator_user_aps au ON (aum.AUPID = au.ID)
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

        $dataMenu = $this->db->order_by('ID','ASC')->get_where('db_agregator.agregator_menu_header',array(
            'Type' => 'APS'
        ))->result_array();
        if(count($dataMenu)>0){
            $i = 0;
            foreach ($dataMenu AS $itm){
                $dataMenu[$i]['Menu'] = $this->db->get_where('db_agregator.agregator_menu',
                    array(
                        'MHID' => $itm['ID'],
                        'HideMenu' =>'0'
                    ))->result_array();
                $i++;
            }
        }

        $data['page'] = $page;
        $data['listMenu'] = $dataMenu;
        $URL = $this->uri->segment(1).'/'.$this->uri->segment(2);
        $data['Description'] = $this->db->get_where('db_agregator.agregator_menu',array(
            'URL' => $URL
        ))->result_array();
        // print_r($data);die();
        $content = $this->load->view('page/agregator_aps/menu_agregator_aps',$data,true);
        $this->temp($content);
    }

    public function loadpage_aps($viewPage){
//        $viewPage = 'kerjasama_tridharma';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator_aps/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }

    public function programme_study(){
        $viewPage = 'programme_study';
        $accessUser = $this->agregatorPrevilege($viewPage);
        $data['accessUser'] = $accessUser;
        $page = $this->load->view('page/agregator_aps/'.$viewPage,$data,true);
        $this->menu_agregator($page);
    }

    public function setting()
    {
        $dataSetting = $this->db->get_where('db_agregator.agregator_admin',array(
            'NIP' => $this->session->userdata('NIP')
        ))->result_array();

        $data['access'] = (count($dataSetting)>0) ? '1' : '0';
        $page = $this->load->view('page/agregator_aps/setting',$data,true);
        $this->menu_agregator($page);
    }

    public function authenticate_aps()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $rs = [];
        $Input = $this->getInputToken();
        $NIP = $Input['NIP'];
        $URL = $Input['getCurrentURL'];
        $Type = $Input['Type'];
        $URI = str_replace(url_pas, '', $URL);
        $Rule = $this->m_master->__Previleges_aps_apt_user($NIP,$URI,$Type);
        echo json_encode($Rule);

    }

}
