<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_action extends DocumentGenerator_Controler {

    function __construct()
    {
        header('Content-Type: application/json');
        parent::__construct();
    }

    public function upload_template(){
        if (array_key_exists('PathTemplate', $_FILES)) {
            $read = $this->m_doc->readTemplate();
            echo json_encode($read);
        }
    }
}