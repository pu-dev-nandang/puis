<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_master extends Vreservation_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
        $this->load->library('JWT');
        // $this->load->model('master/m_master');
        // $this->load->model('vreservation/m_reservation');
    }

    public function equipment_master()
    {
        $content = $this->load->view($this->pathView.'master/equipment_master','',true);
        $this->temp($content);
    }

    public function modalform($table)
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_reservation.'.$table);
        $this->data['getData'] = null;
        if ($this->data['id'] != '') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_reservation.'.$table,'ID',$this->data['id']);
        }
        echo $this->load->view($this->pathView.'master/modalform',$this->data,true);
    }

    public function load_table_master($table)
    {
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_reservation.'.$table);
        $this->data['getData'] = $this->m_master->showData('db_reservation.'.$table);
        echo $this->load->view($this->pathView.'master/table_master_global',$this->data,true);
    }


    public function submit_m_equipment()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                //$this->m_master->inserData_jenis_tempat_tinggal($input['Equipment']);
            $dataSave = array(
                'Equipment' => ucwords($input['Equipment']),
                'CreateAT' => date('Y-m-d'),
            );
            $this->db->insert('db_reservation.m_equipment', $dataSave);
                break;
            case 'edit':
                //$this->m_master->editData_jenis_tempat_tinggal($input['Equipment'],$input['CDID']);
                $dataSave = array(
                    'Equipment' => ucwords($input['Equipment']),
                );
                $this->db->where('ID', $input['CDID']);
                $this->db->update('db_reservation.m_equipment', $dataSave);
                break;
            case 'delete':
                $this->m_master->delete_id_table_all_db($input['CDID'],'db_reservation.m_equipment');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table_allDB($input['CDID'],$input['Active'],'db_reservation.m_equipment');
                break;
            default:
                # code...
                break;
        }
    }

    public function ruangan()
    {
        $content = $this->load->view($this->pathView.'master/ruangan','',true);
        $this->temp($content);
    }

}
