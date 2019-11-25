<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_action extends Ticket_Controler {
    function __construct()
    {
        parent::__construct();
        $this->load->model('ticketing/m_ticketing');
    }

    private function auth($NoTicket){
        $NIP = $this->session->userdata('NIP');
        $auth = $this->m_ticketing->auth_action_tickets($NoTicket,$NIP);
        if (!$auth) {
            show_404($log_error = TRUE); 
            die();
        }
    }

    private function getCategory(){
        $url =base_url()."rest_ticketing/__CRUDCategory";
        $dataPass = ['action' => 'read'];
        $rs = $this->m_general->ApiTicketing($url,$dataPass);
        return $rs['data'];
    }

    public function set_ticket($NoTicket){
       $this->auth($NoTicket);
       $data['DataTicket'] = $this->m_ticketing->getDataTicketBy(['NoTicket' => $NoTicket]);
       $data['DataCategory'] = $this->getCategory();
       $page = $this->load->view('dashboard/ticketing/set_ticket',$data,true);
       $this->menu_ticket($page);
    }

}
