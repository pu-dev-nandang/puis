<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_purchasing extends Purchasing_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement(); 
        $this->load->model('m_api');
    }

    public function dashboard()
    {
      $data['department'] = parent::__getDepartement();
      $content = $this->load->view('dashboard/dashboard',$data,true);
      $this->temp($content);
    }

    public function LoadTable_db_purchasing_all($table,$Active = null)
    {
        $this->auth_ajax();
        $query = array();
        if ($Active == null) {
            $sql = 'select * from db_purchasing.'.$table;
            $query=$this->db->query($sql, array())->result_array();
        }
        else
        {
            $sql = 'select * from db_purchasing.'.$table.' where Active = ?';
            $query=$this->db->query($sql, array($Active))->result_array();
        }

        echo json_encode($query);
    }

    

}
