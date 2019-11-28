<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_action extends Ticket_Controler {
    function __construct()
    {
        parent::__construct();
        $this->load->model('ticketing/m_ticketing');
    }

    private function auth($NoTicket,$DepartmentID,$first='no'){
        $NIP = $this->session->userdata('NIP');
        $auth = $this->m_ticketing->auth_action_tickets($NoTicket,$NIP,$DepartmentID,$first);
        if (!$auth['bool']) {
            show_404($log_error = TRUE); 
            die();
        }
        else{
          return $auth;
        }
    }

    private function getCategory(){
        $url =base_url()."rest_ticketing/__CRUDCategory";
        $dataPass = ['action' => 'read'];
        $rs = $this->m_general->ApiTicketing($url,$dataPass);
        return $rs['data'];
    }


    public function set_action_first($NoTicket,$EncodeDepartment){
       $DepartmentID = $this->m_general->jwt_decode_department($EncodeDepartment);
       $data['Authent'] = $this->auth($NoTicket,$DepartmentID,'yes');
       $data['DataTicket'] = $this->m_ticketing->getDataTicketBy(['NoTicket' => $NoTicket]);
       $data['DataCategory'] = $this->getCategory();
       $data['DataEmployees'] = $this->m_general->getAllUserByDepartment(['DepartmentID' => $DepartmentID ]);
       $data['DataReceived'] = $this->m_ticketing->getDataReceived(['ID' => $data['DataTicket'][0]['ID'] ]);
       $page = $this->load->view('dashboard/ticketing/set_action_first',$data,true);
       $this->menu_ticket($page);
    }

    public function set_action_progress($NoTicket,$EncodeDepartment){
      $DepartmentID = $this->m_general->jwt_decode_department($EncodeDepartment);
      $data['Authent'] = $this->auth($NoTicket,$DepartmentID);
      $data['DataTicket'] = $this->m_ticketing->getDataTicketBy(['NoTicket' => $NoTicket]);
      $dataToken = [
        'NIP' => $this->session->userdata('NIP'),
        'DepartmentID' => $DepartmentID,
      ];
      $data['DataAll'] = $this->m_ticketing->rest_progress_ticket($dataToken,' and a.NoTicket = "'.$NoTicket.'" ')['data'];
      $data['DataReceivedSelected'] = $this->m_ticketing->DataReceivedSelected($data['DataTicket'][0]['ID'],$DepartmentID);
      $data['DataCategory'] = $this->getCategory();
      $data['DataEmployees'] = $this->m_general->getAllUserByDepartment(['DepartmentID' => $DepartmentID ]);
      $page = $this->load->view('dashboard/ticketing/set_action_progress',$data,true);
      $this->menu_ticket($page);
    }

}
