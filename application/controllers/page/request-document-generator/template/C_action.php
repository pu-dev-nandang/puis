<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_action extends ServiceDocumentGenerator_Controler {

    function __construct()
    {
        header('Content-Type: application/json');
        parent::__construct();
    }

    public function upload_template(){
        if (array_key_exists('PathTemplate', $_FILES)) {
            $rs = ['status' => 1,'msg' => '','callback' => []];
            $read = $this->m_doc->readTemplate();
            $rs['callback'] = $read;
            echo json_encode($rs);
        }
    }

    public function preview_template(){
        if (array_key_exists('PathTemplate', $_FILES)) {
             $Input = json_decode(json_encode($this->getInputToken()),true);
             $rs = $this->m_doc->preview_template($Input);
             echo json_encode($rs);
        }
    }

    public function save_template(){
        if (array_key_exists('PathTemplate', $_FILES)) {
             $Input = json_decode(json_encode($this->getInputToken()),true);
             $rs = $this->m_doc->save_template($Input);
             echo json_encode($rs);
        }
    }

    public function save_edit_department_access(){
        $Input = json_decode(json_encode($this->getInputToken()),true);
        $rs = $this->m_doc->save_edit_department_access($Input);
        echo json_encode($rs);
    }

    public function loadtableMaster(){
        $dataToken = $this->getInputToken();
        $rs = $this->m_doc->loadtableMaster($dataToken);
        echo json_encode($rs);
    }

    public function LoadMasterSuratAccess(){
        $dataToken = $this->getInputToken();
        $rs = $this->m_doc->LoadMasterSuratAccess($dataToken);
        echo json_encode($rs);
    }

    public function preview_template_table(){
        $dataToken = $this->getInputToken();
        $dataToken = json_decode(json_encode($dataToken),true);
        $rs = $this->m_doc->preview_template_table($dataToken);
        echo json_encode($rs);
    }

    public function RemoveDocumentMaster(){
        $dataToken = $this->getInputToken();
        $ID = $dataToken['ID'];
        $rs = $this->m_doc->RemoveDocumentMaster($ID);
        echo json_encode($rs);
    }

    public function run_set_table(){
        $dataToken = $this->getInputToken();
        $dataToken = json_decode(json_encode($dataToken),true);
        $rs = $this->m_doc->run_set_table($dataToken);
        echo json_encode($rs);
    }

    public function LoadTableCategorySrt(){
        $dataToken = $this->getInputToken();
        $dataToken = json_decode(json_encode($dataToken),true);
        $rs = $this->m_doc->LoadTableCategorySrt($dataToken);
        echo json_encode($rs);
    }

    public function submit_CategorySrt(){
        $rs = ['status' => 0,'msg' => ''];
        $dataToken = $this->getInputToken();
        $dataToken = json_decode(json_encode($dataToken),true);
        $action = $dataToken['action'];
        switch ($action) {
            case 'add':
                $data = $dataToken['data'];
                $data['Config'] = json_encode($data['Config']);
                $data['Department'] = $this->session->userdata('DepartmentIDDocument');
                $data['UpdatedBy'] = $this->session->userdata('NIP');
                $data['UpdatedAt'] = date('Y-m-d H:i:s');
                $this->db->insert('db_generatordoc.category_document',$data);
                $rs['status'] = 1;
                break;
            case 'delete':
                $ID = $dataToken['ID'];
                $data['Active'] = 0;
                $data['UpdatedBy'] = $this->session->userdata('NIP');
                $data['UpdatedAt'] = date('Y-m-d H:i:s');
                $this->db->where('ID',$ID);
                $this->db->update('db_generatordoc.category_document',$data);
                $rs['status'] = 1;
                break;
            case 'edit':
                $ID = $dataToken['ID'];
                $data = $dataToken['data'];
                $data['Config'] = json_encode($data['Config']);
                $data['Department'] = $this->session->userdata('DepartmentIDDocument');
                $data['UpdatedBy'] = $this->session->userdata('NIP');
                $data['UpdatedAt'] = date('Y-m-d H:i:s');
                $this->db->where('ID',$ID);
                $this->db->update('db_generatordoc.category_document',$data);
                $rs['status'] = 1;
                break;
            default:
                # code...
                break;
        }

        echo json_encode($rs);
    }
    
}