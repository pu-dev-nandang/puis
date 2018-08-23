<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_config extends Finnance_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement();
        $this->load->model('finance/m_finance');
        $this->load->model('m_sendemail');
        $this->load->model('master/m_master');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function policysys()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/config/policysys',$this->data,true);
        $this->temp($content);
    }

    public function policy_sys_json_data()
    {
        $get = $this->m_master->showData_array('db_finance.cfg_policy_sys');
        echo json_encode($get);
    }

    public function policy_sys_modalform()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getDataEdit'] =  $this->m_master->caribasedprimary('db_finance.cfg_policy_sys','ID',$input['CDID']);
        }
        echo $this->load->view('page/'.$this->data['department'].'/config/modalform_policy',$this->data,true);
    }

    public function policy_sys_submit()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                //$this->m_master->inserData_jenis_tempat_tinggal($input['Equipment']);
            $dataSave = array(
                'VA_active' => $input['VA_status'],
            );
            $this->db->insert('db_finance.cfg_policy_sys', $dataSave);
                break;
            case 'edit':
                //$this->m_master->editData_jenis_tempat_tinggal($input['Equipment'],$input['CDID']);
                $dataSave = array(
                    'VA_active' => $input['VA_status'],
                );
                $this->db->where('ID', $input['CDID']);
                $this->db->update('db_finance.cfg_policy_sys', $dataSave);
                break;
            case 'delete':
                // $this->m_master->delete_id_table_all_db($input['CDID'],'db_finance.cfg_policy_sys');
                break;
            case 'getactive':
                // $this->m_master->getActive_id_activeAll_table_allDB($input['CDID'],$input['Active'],'db_reservation.m_equipment');
                break;
            default:
                # code...
                break;
        }
    }

}
