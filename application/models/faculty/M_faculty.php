<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_faculty extends CI_Model {


    function __construct()
    {
        parent::__construct();
    }

    public function auth()
    {
        $PositionMain = $this->session->userdata('PositionMain');
        $DivisionID = $PositionMain['IDDivision'];
        $IDPosition = $PositionMain['IDPosition'];
        // get Faculty
        $GetFaculty = ($DivisionID == 12 || $IDPosition <= 5) ?  $this->m_master->showData_array('db_academic.faculty'):array();

        // check Faculty
        $NIP = $this->session->userdata('NIP');
        $a_ID = $this->m_master->caribasedprimary('db_academic.faculty','AdminID',$NIP);
        $k_ID = $this->m_master->caribasedprimary('db_academic.faculty','NIP',$NIP);

        if (count($a_ID) > 0) {
            $GetFaculty = $a_ID;
        }
        elseif (count($k_ID) > 0) {
            $GetFaculty = $k_ID;
        }
        else
        {
            if ($DivisionID != 12 && $IDPosition >= 6) {
               redirect(base_url().'page404');die();
            }
        }

        if (count($GetFaculty) > 0) {
            $this->session->set_userdata('faculty_get',$GetFaculty);
            // if (count($GetFaculty) == 1) {
            //     $a = $this->session->userdata('faculty_get');
            //     $faculty_active = $a[0]['Name'];
            //     $faculty_active = strtolower($faculty_active);
            //     $faculty_active = str_replace(" ", "-", $faculty_active);
            //     $this->session->set_userdata('faculty_active',$faculty_active);
            //     $this->session->set_userdata('faculty_active_id',$a[0]['ID']);
            // }
            $a = $this->session->userdata('faculty_get');
            $faculty_active = $a[0]['Name'];
            $faculty_active = strtolower($faculty_active);
            $faculty_active = str_replace(" ", "-", $faculty_active);
            $this->session->set_userdata('faculty_active',$faculty_active);
            $this->session->set_userdata('faculty_active_id',$a[0]['ID']);
        }
        else
        {
            redirect(base_url().'page404');die();
        }
    }
}