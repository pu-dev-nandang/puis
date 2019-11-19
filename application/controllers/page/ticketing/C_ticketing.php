<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_ticketing extends Globalclass {

    function __construct()
    {
        parent::__construct();
        $this->load->model('ticketing/m_general');
    }

    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_ticket($page){
        $data['Authen'] = $this->m_master->showData_array('db_ticketing.rest_setting');
        $data['DepartmentID'] = $this->m_general->getDepartmentNow();
        $data['ArrSelectOptionDepartment'] = $this->m_general->getAuthDepartment();
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('dashboard/ticketing/menu_ticketing',$data,true);
        parent::template($content);
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


}
