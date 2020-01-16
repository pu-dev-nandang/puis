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

    public function loadtableMaster(){
        $dataToken = $this->getInputToken();
        $rs = $this->m_doc->loadtableMaster($dataToken);
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
    
}