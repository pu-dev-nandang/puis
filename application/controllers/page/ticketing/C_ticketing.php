<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_ticketing extends Globalclass {

    public function temp($content)
    {
        parent::template($content);
        $this->load->model('master/m_master');
    }

    public function menu_ticket($page){
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('dashboard/ticketing/menu_ticketing',$data,true);
        parent::template($content);
    }

    public function ticket()
    {
        $data['department'] = parent::__getDepartement();
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
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('dashboard/ticketing/setting','',true);
        $this->menu_ticket($page);
    }


}
