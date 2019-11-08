<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_prodi extends CI_Model {
    public $data = array();

    function __construct()
    {
        parent::__construct();
    }

    private function __join_prodi_auth($NIP,$GetProdi = []){
        $G_data = $this->m_master->caribasedprimary('db_prodi.auth_prodi','NIP',$NIP);
        if (count($G_data) > 0) {
            $ProdiAuth = $G_data[0]['ProdiAuth']; // NIP is UNIQUE
            $arr_ProdiAuth = json_decode(json_encode($ProdiAuth),true);
            for ($i=0; $i < count($arr_ProdiAuth); $i++) {
                $ProdiID =  $arr_ProdiAuth[$i];
                // cek ProdiID exist
                $Bool = true;
                for ($j=0; $j <count($GetProdi) ; $j++) { 
                    if ($GetProdi[$j]['ID'] == $ProdiID) {
                        $Bool = false;
                        break;
                    }
                }
                if ($Bool) {
                    $d = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ProdiID);
                    $GetProdi[] = $d[0];
                }
            }
        }
        return $GetProdi;
    }

    public function auth($ProdiID = NULL)
    {
        $PositionMain = $this->session->userdata('PositionMain');
        $DivisionID = $PositionMain['IDDivision'];
        $IDPosition = $PositionMain['IDPosition'];
        // get prodi
        $GetProdi = ($DivisionID == 12 || $IDPosition < 5) ?  $this->m_master->caribasedprimary('db_academic.program_study','Status',1):array();

        // check Prodi
        $NIP = $this->session->userdata('NIP');
        $a_ID = $this->m_master->caribasedprimary('db_academic.program_study','AdminID',$NIP);
        $k_ID = $this->m_master->caribasedprimary('db_academic.program_study','KaprodiID',$NIP);

        if (count($a_ID) > 0) {
            $GetProdi = $a_ID;
        }
        elseif ($IDPosition == 5) { // for dekan
            $Fculty = $this->m_master->caribasedprimary('db_academic.faculty','NIP',$this->session->userdata('NIP') );
            if (count($Fculty ) > 0) {
                // cek prody faculty
                $ID = $Fculty[0]['ID'];
                $GetProdi = $this->m_master->caribasedprimary('db_academic.program_study','FacultyID',$ID);
            }
            else
            {
                redirect(base_url().'page404');die();
            }
        }
        elseif (count($k_ID) > 0) {
            $GetProdi = $k_ID;
        }
        else
        {
            if ($DivisionID != 12 && $IDPosition > 6) {
               redirect(base_url().'page404');die();
            }
        }

        $GetProdi = $this->__join_prodi_auth($NIP,$GetProdi);

        if (count($GetProdi) > 0) {
            $this->session->set_userdata('prodi_get',$GetProdi);
            if ($ProdiID == NULL || $ProdiID == '') {
                // if (count($GetProdi) == 1) {
                //     $a = $this->session->userdata('prodi_get');
                //     $prodi_active = $a[0]['Name'];
                //     $prodi_active = strtolower($prodi_active);
                //     $prodi_active = str_replace(" ", "-", $prodi_active);
                //     $this->session->set_userdata('prodi_active',$prodi_active);
                //     $this->session->set_userdata('prodi_active_id',$a[0]['ID']);
                // }
                $a = $this->session->userdata('prodi_get');
                $prodi_active = $a[0]['Name'];
                $prodi_active = strtolower($prodi_active);
                $prodi_active = str_replace(" ", "-", $prodi_active);
                $this->session->set_userdata('prodi_active',$prodi_active);
                $this->session->set_userdata('prodi_active_id',$a[0]['ID']);
            }
            else
            {
                $GetProdi_selected = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ProdiID);
                $a = $this->session->userdata('prodi_get');
                $prodi_active = $GetProdi_selected[0]['Name'];
                $prodi_active = strtolower($prodi_active);
                $prodi_active = str_replace(" ", "-", $prodi_active);
                $this->session->set_userdata('prodi_active',$prodi_active);
                $this->session->set_userdata('prodi_active_id',$GetProdi_selected[0]['ID']);

            }
            
        }
        else
        {
            redirect(base_url().'page404');die();
        }
    }
}