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

    public function SwitchBintang($tokenURL){
        if ($this->input->is_ajax_request()) {
            $dataToken = $this->getInputToken();
            $action = $dataToken['action'];
            switch ($action) {
                case 'LoadBintangOption':
                    $rs = [];
                    $dataParam = $dataToken['data'];
                    $NPM = $dataParam->NPM;
                    $ProdiID = $dataParam->ProdiID;
                    $Classof = $dataParam->Classof;
                    $rs['auth_std'] = $this->m_master->caribasedprimary('db_academic.auth_students','NPM',$NPM)[0];
                    $rs['OptionBintang'] = $this->db->query(
                        'select distinct(Pay_Cond) from db_finance.tuition_fee 
                         where ProdiID = '.$ProdiID.' and Classof = '.$Classof.' 
                        '
                    )->result_array();
                    echo json_encode($rs);
                    break;
                case 'LoadloG' :
                    $dataParam = $dataToken['data'];
                    $NPM = $dataParam->NPM;
                    $query = $this->m_master->caribasedprimary('db_finance.m_tuition_fee_updated','NPM',$NPM);
                    $rs = [];
                    for ($i=0; $i < count($query); $i++) { 
                        $nestedData=array();
                        $row = $query[$i];
                        foreach ($row as $key => $value) {
                            $nestedData[] = $value;
                        }
                        $nestedData['token'] = $this->jwt->encode($row,'UAP)(*');
                        $rs[] = $nestedData;
                    }

                    $json_data = array(
                        "draw"            => intval(count($query)),
                        "recordsTotal"    => intval(count($query)),
                        "recordsFiltered" => intval(count($query)),
                        "data"            => $rs,
                    );
                    echo json_encode($json_data);
                    break;
                default:
                    # code...
                    break;
            }
        }
        else
        {
            try {
                $this->data['tokenURL'] = $tokenURL;
                $dataDecodeToken = $this->jwt->decode($tokenURL,'UAP)(*');
                $this->data['NPM'] = $dataDecodeToken->NPM;
                $this->data['Name'] = $dataDecodeToken->Namemhs;
                $this->data['Prodiname'] = $dataDecodeToken->Prodiname;
                $content = $this->load->view('page/'.$this->data['department'].'/master/SwitchBintang',$this->data,true);
                $this->temp($content);
            } catch (Exception $e) {
                
            }
            

        }
       
    }

}
