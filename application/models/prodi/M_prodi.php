<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_prodi extends CI_Model {


    function __construct()
    {
        parent::__construct();
    }

    public function auth()
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

        if (count($GetProdi) > 0) {
            $this->session->set_userdata('prodi_get',$GetProdi);
            if (count($GetProdi) == 1) {
                $a = $this->session->userdata('prodi_get');
                $prodi_active = $a[0]['Name'];
                $prodi_active = strtolower($prodi_active);
                $prodi_active = str_replace(" ", "-", $prodi_active);
                $this->session->set_userdata('prodi_active',$prodi_active);
                $this->session->set_userdata('prodi_active_id',$a[0]['ID']);
            }
        }
        else
        {
            redirect(base_url().'page404');die();
        }
    }
}