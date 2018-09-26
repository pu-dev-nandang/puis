<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_master extends Budgeting_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->data['department'] = parent::__getDepartement(); 
    }

    public function catalog()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/budgeting/master/catalog',$this->data,true);
        $this->temp($content);
    }

    public function supplier()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/budgeting/master/supplier',$this->data,true);
        $this->temp($content);
    }

}
