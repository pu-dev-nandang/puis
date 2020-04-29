<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_log extends CI_Model {


    function __construct()
    {
        parent::__construct();
    }

    public function readDataLog($UserID,$status=null){

//        $IDDivision = $this->session->userdata('IDdepartementNavigation');

        $this->db->where('UserID', $UserID);
        
        // $this->db->update('db_notifikasi.logging_user',array('StatusRead' => '1', 'ShowNotif' => '1'));
        $this->db->update('db_notifikasi.logging_user',array('ShowNotif' => '1'));

        $basicQuery = 'SELECT l.*,lu.StatusRead,lu.ShowNotif,lu.ID as ID_logging_user FROM db_notifikasi.logging_user lu 
                                      LEFT JOIN db_notifikasi.logging l ON (l.ID = lu.IDLogging)
                                      WHERE lu.UserID = "'.$UserID.'" '.(!empty($status) ? " and lu.StatusRead = '0'":"").' ';
        $limit = 20;
        $data = $this->db->query($basicQuery.' ORDER BY l.ID DESC LIMIT '.$limit)->result_array();
        $dataCount = $this->db->query($basicQuery.' AND lu.StatusRead = "0" LIMIT '.$limit)->result_array();

        $result = array(
            "Unread" => count($dataCount),
            "Details" => $data
        );

        return $result;

    }

}