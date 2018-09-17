<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_budgeting extends Budgeting_Controler {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->data['department'] = parent::__getDepartement(); 
    }

    public function index()
    {
        $this->session->unset_userdata('auth_budgeting_sess');
        $this->session->unset_userdata('menu_budgeting_sess');
        $this->session->unset_userdata('menu_budgeting_grouping');
        $IDdepartementNavigation = $this->session->userdata('IDdepartementNavigation');
        switch ($IDdepartementNavigation) {
            case 12: // IT
                // print_r($IDdepartementNavigation);
                $this->BudgetingIT();
                break;
            case 9: // IT
                // print_r($IDdepartementNavigation);
                $this->BudgetingFinance();
                break;    
            default:
                # code...
                break;
        }
        
    }

    public function BudgetingIT()
    {
         echo __FUNCTION__;
    }

    public function BudgetingFinance()
    {
        // get previleges for menu and content
        $MenuDepartement= 'NA.'.$this->session->userdata('IDdepartementNavigation');
        $this->getAuthSession($MenuDepartement);
        // $content = '<pre>'.print_r($this->session->userdata('menu_budgeting_grouping')).'</pre>';
        $content = $this->load->view('page/'.$this->data['department'].'/budgeting/dashboard',$this->data,true);
        $this->temp($content);
    }

    public function configfinance($Request = null)
    {
        if ($Request == null) {
            $content = $this->load->view('page/'.$this->data['department'].'/budgeting/configfinance',$this->data,true);
            $this->temp($content);
        }
        
    }

    public function pageLoadTimePeriod()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $this->data['loadData'] = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Active',1);
        $this->data['loadData'] = json_encode($this->data['loadData']);
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/pageLoadTimePeriod',$this->data,true);
        echo json_encode($arr_result);
    }


}
