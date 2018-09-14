<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_budgeting extends Budgeting_Controler {

    public function __construct()
    {
        parent::__construct();
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
        $departementName = $this->__getDepartement();
        // $content = '<pre>'.print_r($this->session->userdata('menu_budgeting_grouping')).'</pre>';
        $content = $this->load->view('page/'.$departementName.'/budgeting/dashboard',$this->data,true);
        $this->temp($content);
    }

    public function configfinance()
    {
        $content = $this->load->view('page/'.'finance'.'/budgeting/configfinance',$this->data,true);
        $this->temp($content);
    }


}
