<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_studentlife extends CI_Model {


    function getDetailCompanyByID($ID){

        $data = $this->db->query('SELECT * FROM db_studentlife.master_company mc WHERE mc.ID = "'.$ID.'" ')->result_array();

        return $data;

    }

}
