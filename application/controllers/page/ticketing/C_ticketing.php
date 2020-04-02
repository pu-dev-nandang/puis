<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_ticketing extends Ticket_Controler {

    function __construct()
    {
        parent::__construct();
    }

    public function ticket()
    {
        $page = $this->load->view('dashboard/ticketing/ticket_today','',true);
        $this->menu_ticket($page);
    }

    public function ticket_list()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('dashboard/ticketing/ticket_list','',true);
        $this->menu_ticket($page);
    }

    public function setting()
    {
        $data['action'] = ($this->m_general->auth()) ? 'write' : '';
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('dashboard/ticketing/setting',$data,true);
        $this->menu_ticket($page);
    }

    public function student_report()
    {
//        $data['action'] = ($this->m_general->auth()) ? 'write' : '';
//        $data['department'] = parent::__getDepartement();
        $data='';
        $page = $this->load->view('dashboard/ticketing/student_report',$data,true);
        $this->menu_ticket($page);
    }

    public function ticket_dashboard(){
        $data=[];
        $page = $this->load->view('dashboard/ticketing/ticketing_dashboard',$data,true);
        $this->menu_ticket($page);
    }

}
