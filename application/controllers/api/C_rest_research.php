<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');

class C_rest_research extends CI_Controller {
    public $data = [];
    private $keyToken = 's3Cr3T-G4N';
    function __construct(){
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('research/m_research');
        $this->load->library('JWT');
        try {
          $G_setting = $this->m_master->showData_array('db_ticketing.rest_setting');
          if (!$this->auth($G_setting)) {
            echo '{"status":"999","message":"Not Authenfication"}'; 
            die();
          }
          else
          {
            header('Access-Control-Allow-Origin: *');
          }
        } catch (Exception $e) {
          echo json_encode($e);
          die();
        }
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        $data_arr = json_decode(json_encode($data_arr),true);
        return $data_arr;
    }

    private function auth($G_setting){
      $Bool = false;
      try {
        $dataToken = $this->getInputToken();
        if(array_key_exists("auth",$dataToken) &&  $dataToken['auth'] == $this->keyToken ) {
            $Bool = true;
        } 

        return $Bool;
      } catch (Exception $e) {
         echo json_encode($e);
         die();
      }

      return false;
    }


    public function load_box(){
      $dataToken = $this->getInputToken();
      $action = $dataToken['action'];
      $rs = ['status' => 0,'callback' => [],'result' => 0];
      switch ($action) {
        case 'total_user':
          $query = $this->db->query('select count(*) as total from db_research.master_user_research')->row()->total;
          $rs['status'] = 1;
          $rs['callback'] = 'Please in the list for detail';
          $rs['result'] = $query;
          break;
        case 'total_login_today':
          $query = $this->db->query('select count(*) as total from (
              select 1 from db_research.eksternal_login
              where DATE_FORMAT(TimeLogin,"%Y-%m-%d") = CURDATE()
          )xx ')->row()->total;
          $data = $this->db->query('select a.*,b.Nama,b.Email,b.F_kolaborasi,b.F_dosen,b.F_mhs,b.F_reviewer from db_research.eksternal_login as a
            join db_research.master_user_research as b on a.ID_master_user_research = b.ID
              where DATE_FORMAT(a.TimeLogin,"%Y-%m-%d") = CURDATE() ')->result_array();
          $rs['status'] = 1;
          $rs['callback'] = $data;
          $rs['result'] = $query;
          break;
        case 'total_approval':
          $rs['status'] = 1;
          $rs['callback'] = '';
          $rs['result'] = 0;
          break;
      }

      echo json_encode($rs);
    }

    public function datatable_LoadListUserEskternal(){
      $dataToken = $this->getInputToken();
      $rs = $this->m_research->datatable_LoadListUserEskternal($dataToken);
      echo json_encode($rs);
    }

    public function CRUDUserEksternal(){
      $dataToken = $this->getInputToken();
      $action = $dataToken['action'];
      $rs = [];
      switch ($action) {
        case 'add':
          $rs = $this->m_research->insert_user_eksternal($dataToken);
          break;
        case 'delete':
          $rs = $this->m_research->delete_user_eksternal($dataToken);
          break;
        case 'edit':
          $rs = $this->m_research->edit_user_eksternal($dataToken);
          break;
        default:
          # code...
          break;
      }
      echo json_encode($rs);
    }

    public function research_eksternal(){
      $dataToken = $this->getInputToken();
      $action = $dataToken['action'];
      $rs = [];
      switch ($action) {
        case 'datatable_server_side':
          $data = $dataToken['data'];
          $rs =  $this->m_research->dtSrvSide_research_eksternal($data);
          break;
        
        default:
          # code...
          break;
      }

      echo json_encode($rs);
    }

}