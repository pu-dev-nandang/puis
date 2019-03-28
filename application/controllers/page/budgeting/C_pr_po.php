<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pr_po extends Budgeting_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function authFin()
    {
        $DepartementSess = $this->session->userdata('IDDepartementPUBudget');
        if ($DepartementSess != 'NA.9') {
            exit('No direct script access allowed');
        }
        
    }

    public function configfinance_pr($Request = null)
    {
        $this->authFin();
        $arr_menuConfig = array('Set_Rad',
                                'Set_Approval',
                                null
                            );
        if (in_array($Request, $arr_menuConfig))
          {
            $this->data['request'] = $Request;
            $content = $this->load->view('page/budgeting/'.$this->data['department'].'/configfinance_pr',$this->data,true);
            $this->temp($content);
          }
        else
          {
            show_404($log_error = TRUE);
          }
    }

}
