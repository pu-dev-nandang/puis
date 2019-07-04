<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_spb extends Budgeting_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('budgeting/m_spb');
    }

    public function menu_horizontal($page)
    {
    	$data['content'] = $page;
    	$content = $this->load->view('global/budgeting/spb/menu_horizontal',$data,true);
    	$this->temp($content);
    }

    public function index()
    {
    	/*
			1.filtering by pr
    	*/
		$page = $this->load->view('global/budgeting/spb/list',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function create_spb()
    {
    	/*
			1.SPB bisa dicreate dari user manapun dengan trigerr PO / SPK done
			2.Show PO dengan status Done.
			3.filtering by pr
    	*/

            // get data bank rest/__Databank
                $data = array(
                    'auth' => 's3Cr3T-G4N', 
                );
                $key = "UAP)(*";
                $token = $this->jwt->encode($data,$key);
                $G_data_bank = $this->m_master->apiservertoserver(base_url().'rest/__Databank',$token);
                $this->data['G_data_bank'] = $G_data_bank;

        if (empty($_GET)) {
           $this->data['action_mode'] = 'add';
           $this->data['SPBCode'] = '';
        }
        else{
            try {
                // read token
            }
            //catch exception
            catch(Exception $e) {
                 show_404($log_error = TRUE); 
            }
            
        }   

        // check purchasing & non purchasing
        if ($this->session->userdata('IDDepartementPUBudget') == 'NA.4') { // purchasing
            $page = $this->load->view('global/budgeting/spb/create_spb',$this->data,true);
            
        }
        else
        {
            $page = $this->load->view('global/budgeting/spb/create_spb_user',$this->data,true);
        }

        $this->menu_horizontal($page);  

		
    }

    public function configuration()
    {
    	/*
			1.Only auth finance
    	*/
    	if ($this->session->userdata('IDDepartementPUBudget') == 'NA.9') {
    		$page = $this->load->view('global/budgeting/spb/configuration',$this->data,true);
    		$this->menu_horizontal($page);
    	}
    	else
    	{
    		show_404($log_error = TRUE);
    	}
    	
    }

    public function submitspb()
    {
        $rs = array('Status' => 0,'Change' => 0);
        $Input = $this->getInputToken();
        // verify data spb
        $token2 = $this->input->post('token2');
        $key = "UAP)(*";
        $data_verify = (array) $this->jwt->decode($token2,$key);
        $__checkdt = $this->m_spb->checkdt_spb_before_submit($data_verify);

    }

}
