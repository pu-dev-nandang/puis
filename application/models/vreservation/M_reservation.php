<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_reservation extends CI_Model {


    function __construct()
    {
        parent::__construct();
    }
    

    public function get_m_equipment_additional($available = '> 0')
    {
        $sql = 'select a.*,b.* from db_reservation.m_equipment_additional as a join db_reservation.m_equipment as b
        on a.ID_m_equipment = b.ID where a.Qty '.$available;
        $query=$this->db->query($sql, array());
        return $query->result_array();
    }

    public function get_m_additional_personel()
    {
        $sql = 'select a.*,b.* from db_reservation.m_additional_personel as a join db_employees.division as b
        on a.ID_division = b.ID';
        $query=$this->db->query($sql, array());
        return $query->result_array();
    }
}