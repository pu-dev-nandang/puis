<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_tuition_fee extends Finnance_Controler {

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


    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $content = "test";
        $this->temp($content);
    }

    public function tuition_fee(){
        $content = $this->load->view('page/'.$this->data['department'].'/master/tuition_fee',$this->data,true);
        $this->temp($content);
    }

    public function modal_tagihan_mhs()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        $this->data['selectCurriculum'] = $input['selectCurriculum'];
        $this->data['selectBintang'] = $this->m_master->showData_array('db_finance.tuition_fee_schema');
        echo $this->load->view('page/'.$this->data['department'].'/master/modalform_tuition_fee',$this->data,true);
    }

    public function modal_tagihan_mhs_submit()
    {
        $input = $this->getInputToken();
        // check data master tagihan exist
        if ($this->m_finance->checkMasterTagihanExisting($input['TypePembayaran'],$input['Prodi'],$input['ClassOf'],$input['Pay_Cond'])) {
             $this->m_finance->inserData_master_tagihan_mhs($input['TypePembayaran'],$input['Prodi'],$input['Cost'],$input['ClassOf'],$input['Pay_Cond']);
             echo json_encode('');
        }
        else
        {
            echo json_encode('Data telah ada pada database');
        }
       
    }

    public function edited_tagihan_mhs_submit()
    {
        $input = $this->getInputToken();
        $this->m_finance->updateTagihanMhsList($input);
    }

    public function deleted_tagihan_mhs_submit()
    {
        $input = $this->getInputToken();
        $this->m_finance->deleteTagihanMHSByProdiYear($input);
    }

}
