<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_search extends CI_Model {


    public function __construct()
    {
        parent::__construct();
    }

    public function searchEmployees($key,$limit){

        $data = $this->db->query('SELECT em.NIP, em.Name, es.Description AS StatusEmployees, es2.Description AS StatusLecturer   
                                                FROM db_employees.employees em
                                                LEFT JOIN db_employees.employees_status es ON (es.IDStatus = em.StatusEmployeeID)
                                                LEFT JOIN db_employees.employees_status es2 ON (es2.IDStatus = em.StatusLecturerID)
                                                WHERE em.StatusEmployeeID != "-2" AND 
                                                ( em.Name LIKE "%'.$key.'%" OR em.NIP LIKE "%'.$key.'%")
                                                 ORDER BY em.NIP ASC LIMIT '.$limit.'
                                                ')->result_array();

        return $data;

    }

    public function getDataRelatedNIP($NIP){


        $dataEmp = $this->db->query('SELECT em.NIP, em.Name, es.Description AS StatusEmployees, es2.Description AS StatusLecturer, em.StatusEmployeeID, em.StatusLecturerID   
                                                FROM db_employees.employees em
                                                LEFT JOIN db_employees.employees_status es ON (es.IDStatus = em.StatusEmployeeID)
                                                LEFT JOIN db_employees.employees_status es2 ON (es2.IDStatus = em.StatusLecturerID)
                                                WHERE em.NIP = "'.$NIP.'" ')->result_array();

        $dataRelated = $this->db->query('SELECT rn.*, em.Name 
                                                     FROM db_employees.related_nip rn
                                                    LEFT JOIN db_employees.employees em ON (em.NIP = rn.RelatedNIP) 
                                                    WHERE rn.NIP = "'.$NIP.'" ')->result_array();

        if(count($dataRelated)>0){
            for($i=0;$i<count($dataRelated);$i++){
                // Cek apakah punya relasi lain
                $NIP_2 = $dataRelated[$i]['RelatedNIP'];
                $dataRelated_2 = $this->db->query('SELECT rn.*, em.Name FROM db_employees.related_nip rn 
                                                    LEFT JOIN db_employees.employees em ON (em.NIP = rn.RelatedNIP) 
                                                    WHERE rn.NIP = "'.$NIP_2.'" ')->result_array();

                $dataRelated[$i]['RelatedDetail'] = $dataRelated_2;

            }
        }

        $result = array(
            'DataEmp' => $dataEmp[0],
            'Related' => $dataRelated
        );

        return $result;

    }

    public function setToDataRelatedNIP($NIPInduk,$NIP){

        $dataCk_1 = $this->db->get_where('db_employees.related_nip',array('RelatedNIP' => $NIP))->result_array();

        if(count($dataCk_1)<=0){

            $this->db->insert('db_employees.related_nip',array(
                'NIP' => $NIPInduk,
                'RelatedNIP' => $NIP,
                'EntredBy' => $this->session->userdata('NIP')
            ));

            $result = array(
                'Status' => 1,
                'Msg' => 'NIP was successfully linked'
            );

        } else {
            $result = array(
                'Status' => -1,
                'Msg' => 'This NIP has been linked'
            );
        }

        return $result;

    }


}