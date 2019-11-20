<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');

class C_rest_ticketing extends CI_Controller {
    public $data = [];
    private $keyToken = 's3Cr3T-G4N';
    function __construct(){
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('ticketing/m_general');
        $this->load->model('ticketing/m_setting');
        $this->load->model('ticketing/m_ticketing');
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

    private function auth($G_setting){
      $Bool = false;
      try {
        $dataToken = $this->getInputToken();
        $getallheaders = getallheaders();
        foreach ($getallheaders as $name  => $value) {
          if ($name == 'Hjwtkey' && $value == $G_setting[0]['Hjwtkey']) {
            // cek api get
            if(isset($_GET['apikey']) && $_GET['apikey'] == $G_setting[0]['Apikey'] && array_key_exists("auth",$dataToken) &&  $dataToken['auth'] == $this->keyToken ) {
                $Bool = true;
                break;
            } 
          }
        }

        return $Bool;
      } catch (Exception $e) {
         echo json_encode($e);
         die();
      }

      return false;
    }

    private function __addHttpOrhttps($arr){
      $rs = [];
      for ($i=0; $i < count($arr); $i++) { 
        $rs[] = 'http://'.$arr[$i];
        $rs[] = 'https://'.$arr[$i];
      }
      return $rs;
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function CRUDCategory(){
      try {
        $dataToken = $this->getInputToken();
        $action = $dataToken['action'];
        switch ($action) {
          case 'read':
            $rs = $this->m_setting->LoadDataCategory($dataToken);
            echo json_encode($rs);
            break;
          case 'add':
            $rs = $this->m_setting->ActionTable('add',$dataToken,'db_ticketing.category');
            echo json_encode($rs);
            break;
          case 'delete' : 
             $rs = $this->m_setting->ActionTable('delete',$dataToken,'db_ticketing.category');
             echo json_encode($rs);
            break;
          case 'edit' : 
             $rs = $this->m_setting->ActionTable('edit',$dataToken,'db_ticketing.category');
             echo json_encode($rs);
            break;
        }
        // end switch

      } catch (Exception $e) {
        echo json_encode($e);
      }
    }

    public function AutocompleteEmployees()
    {
      try {
         $dataToken = $this->getInputToken();
         $data['response'] = 'true'; //mengatur response
         $data['message'] = array(); //membuat array 
         $getData = $this->m_general->getAllUserAutoComplete($dataToken);
         for ($i=0; $i < count($getData); $i++) {
             $data['message'][] = array(
                 'label' => $getData[$i]['Name'],
                 'value' => $getData[$i]['NIP']
             );
         }
         echo json_encode($data);
      } catch (Exception $e) {
        echo json_encode($e);
      }
    }

    public function CRUDAdmin(){
      try {
        $dataToken = $this->getInputToken();
        $action = $dataToken['action'];
        switch ($action) {
          case 'read':
            $rs = $this->m_setting->LoadDataAdmin($dataToken);
            echo json_encode($rs);
            break;
          case 'add':
            $rs = $this->m_setting->ActionTable('add',$dataToken,'db_ticketing.admin_register');
            echo json_encode($rs);
            break;
          case 'delete' : 
             $rs = $this->m_setting->ActionTable('remove',$dataToken,'db_ticketing.admin_register');
             echo json_encode($rs);
            break;
          case 'edit' : 
             $rs = $this->m_setting->ActionTable('edit',$dataToken,'db_ticketing.admin_register');
             echo json_encode($rs);
            break;
        }
        // end switch

      } catch (Exception $e) {
        echo json_encode($e);
      }
    }

    public function event_ticketing(){
      try {
        $dataToken = $this->getInputToken();
        $action = $dataToken['action'];
        switch ($action) {
          case 'create':
            $rs = [];
            $rs = $this->m_ticketing->create_ticketing($dataToken);
            $CategoryID = $rs['callback']['CategoryID'];
            $getAdminTicketing = $this->m_ticketing->getAdminTicketing($CategoryID);
            if (count($getAdminTicketing)>0) {
              // send notification
              $array_send_notification=[
                'NameRequested' => $rs['callback']['NameRequested'],
                'Description' => 'Number Ticket '.$rs['callback']['NoTicket'],
                'URLDirect' => 'ticket/ticket-get/'.$rs['callback']['NoTicket'],
                'CreatedBy' => $rs['callback']['RequestedBy'],
                'To' => $getAdminTicketing,
                'NeedEmail' => 'No',
              ];

              $this->m_ticketing->send_notification_ticketing($array_send_notification);

            }

            echo json_encode($rs);
            break;
          
          default:
            # code...
            break;
        }
      } catch (Exception $e) {
          echo json_encode($e);
      }
    }

    public function load_ticketing_dashboard()
    {
      try {
        $rs = [];
        $dataToken = $this->getInputToken();
        $action = $dataToken['action'];
        switch ($action) {
          case 'open_ticket':
            $rs = $this->m_ticketing->rest_open_ticket();
            echo json_encode($rs);
            break;
          
          default:
            # code...
            break;
        }
      } catch (Exception $e) {
        echo json_encode($e);
      }
    }



}