<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_menu extends Budgeting_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function menu()
    {
    	// pass all department api/__getAllDepartementPU
    	$this->data['Arr_Department'] = $this->m_master->apiservertoserver(base_url().'api/__getAllDepartementPU',$token = '');
    	$sql = 'select a.*,d.NameDepartement from db_budgeting.cfg_menu as a 
    				 left join (
                select * from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, NameEng as NameDepartement from db_academic.faculty where StBudgeting = 1
                ) aa
                ) as d on a.IDDepartement = d.ID
    			order by a.Sort asc';
    	$query=$this->db->query($sql, array())->result_array();
    	$this->data['Arr_Menu'] =$query;		
    	$content = $this->load->view('page/budgeting/menu/menu',$this->data,true);
    	$this->temp($content);
    }

}
