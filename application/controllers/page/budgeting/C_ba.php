<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_ba extends Budgeting_Controler { // SPB / Bank Advance 
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function menu_horizontal($page)
    {
    	$data['content'] = $page;
    	$content = $this->load->view('global/budgeting/ba/menu_horizontal',$data,true);
    	$this->temp($content);
    }

    public function index()
    {
    	/*
			1.filtering by pr
    	*/
        $this->data['G_Approver'] = $this->m_pr_po->Get_m_Approver();
        $this->data['m_type_user'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');    
		$page = $this->load->view('global/budgeting/ba/list',$this->data,true);
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
           $this->data['BACode'] = '';
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
            $page = $this->load->view('global/budgeting/ba/create_spb',$this->data,true);
        }
        else
        {
            $page = $this->load->view('global/budgeting/ba/create_spb_user',$this->data,true);
        }

        $this->menu_horizontal($page);  

		
    }

    public function configuration()
    {
    	/*
			1.Only auth finance
    	*/
    	if ($this->session->userdata('IDDepartementPUBudget') == 'NA.9') {
    		$page = $this->load->view('global/budgeting/ba/configuration',$this->data,true);
    		$this->menu_horizontal($page);
    	}
    	else
    	{
    		show_404($log_error = TRUE);
    	}
    	
    }

    public function submitba()
    {
        /* Tidak Boleh cancel */
        $rs = array('Status' => 0,'Change' => 0);
        try{
            $Input = $this->getInputToken();
            // verify data spb
            $token2 = $this->input->post('token2');
            $key = "UAP)(*";
            $data_verify = (array) $this->jwt->decode($token2,$key);
            $__checkdt = $this->m_spb->checkdt_spb_before_submit($data_verify);
            if ($__checkdt) {
                $action = $Input['action'];
                switch ($action) {
                    case 'add':
                        $this->insert_ba();
                        $rs['Status']= 1;
                        break;
                    case 'edit':
                        // check status tidak sama dengan 2
                        $ID_payment = $Input['ID_payment'];
                        $G_spb_created = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
                        // find data spb
                        $G_data = $this->m_master->caribasedprimary('db_payment.bank_advance','ID_payment',$ID_payment);
                        if ($G_spb_created[0]['Status'] != 2) {
                            if (count($G_data) > 0) { // spb exist
                               $this->edit_ba();
                               $rs['Status']= 1;
                            }
                            else
                            {
                                // remove another payment
                                $this->m_global->__change_payment_type($ID_payment);
                                // insert db spb
                                $dts = array(
                                        'ID_payment' => $ID_payment
                                    );
                                $this->db->insert('db_payment.bank_advance',$dts);
                                $this->edit_ba();
                                $rs['Status']= 1;       
                            }
                        }
                        else
                        {
                            $rs['Change']= 1;
                        }
                        break;
                    default:
                        # code...
                        break;
                }
            }
            else
            {
                $rs['Change']= 1;
            }

            echo json_encode($rs);
            
        }
        catch(Exception $e) {
          echo json_encode($rs);
        }
    }

    public function insert_ba()
    {
        $Input = $this->getInputToken();
        $Desc_circulationSheet = '';
        unset($Input['action']);
        $Code_po_create = $Input['Code_po_create'];
        $Departement = $Input['Departement'];
        // get Approval
        $token3 = $this->input->post('token3');
        $Amount = $Input['Biaya'];

        // for approval
        $token4 = $this->input->post('token4');
        $key = "UAP)(*";
        $token4 = (array) $this->jwt->decode($token4,$key);
        $JsonStatus =  $this->m_pr_po->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$token4);
        if (count($JsonStatus) > 1) {
            $dataSave = $Input;
            $Desc_circulationSheet = 'Bank Advance Created';
            $dataSave1 = array(
                'Type' => 'Bank Advance',
                'Code' => '',
                'Code_po_create' => $Input['Code_po_create'],
                'Departement' => $Input['Departement'],
                'CreatedBy' => $this->session->userdata('NIP'),
                'CreatedAt' => date('Y-m-d H:i:s'),
                'JsonStatus' => json_encode($JsonStatus),
                'Status' => 1,
            );
            $this->db->insert('db_payment.payment',$dataSave1);
            $ID_payment = $this->db->insert_id();

            $dataSave2 = array(
                'ID_payment' => $ID_payment,
                'Biaya' =>  $Input['Biaya'],
                'TypePay' =>  $Input['TypePay'],
                'Perihal' =>  $Input['Perihal'],
                'No_Rekening' =>  $Input['No_Rekening'],
                'ID_bank' =>  $Input['ID_bank'],
                'Nama_Penerima' =>  $Input['Nama_Penerima'],
                'Date_Needed' =>  $Input['Date_Needed'],
            );

            $this->db->insert('db_payment.bank_advance',$dataSave2);
            $ID_bank_advance = $this->db->insert_id();
            // insert bank_advance_detail
            $dataSave = array(
                    'ID_bank_advance' => $ID_bank_advance,
                    'ID_budget_left' => $Input['ID_budget_left'],
                    'NamaBiaya' => $Input['Perihal'],
                    'Invoice' => $Input['Biaya'],
                );

             $this->db->insert('db_payment.bank_advance_detail',$dataSave);
            // insert to spb_circulation_sheet
                $this->m_spb->payment_circulation_sheet($ID_payment,$Desc_circulationSheet);
            // insert to po_circulation_sheet
                $this->m_pr_po->po_circulation_sheet($Code_po_create,$Desc_circulationSheet);  
        }
        else
        {
            // echo json_encode('Mohon Cek RAD');
            die();
        }
    }

    public function edit_ba()
    {
        $Input = $this->getInputToken();
        $ID_payment = $Input['ID_payment'];
        /*
            jika approval satu telah approve maka tidak boleh melakukan edit lagi
        */
        $G_data = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
        $JsonStatus_existing = $G_data[0]['JsonStatus'];
        $JsonStatus_existing = json_decode($JsonStatus_existing,true);
        $bool = true;
        $Code_SPB = '';
        $Desc_circulationSheet = '';
        // get data spb
            $G_data_ = $this->m_master->caribasedprimary('db_payment.bank_advance','ID_payment',$ID_payment);
        if (count($JsonStatus_existing) > 0) {
            for ($i=1; $i < count($JsonStatus_existing); $i++) { 
                if ($JsonStatus_existing[$i]['Status'] == 1) {
                    $bool = false;
                    break;
                }
            }
        }

        if ($bool) {
            unset($Input['ID_payment']);
            unset($Input['action']);
            $Code_po_create = $Input['Code_po_create'];
            $Departement = $Input['Departement'];
            // for approval
            $token4 = $this->input->post('token4');
            $key = "UAP)(*";
            $token4 = (array) $this->jwt->decode($token4,$key);
            // for approval
            $Amount = $Input['Biaya'];
            $JsonStatus =  $this->m_pr_po->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$token4);
            if (count($JsonStatus) > 1) {
                $dataSave = array(
                    'Departement' => $Input['Departement'],
                    'Code_po_create' => $Code_po_create,
                );
                $dataSave2 = array();
                if (count($G_data_) == 0) {
                   $Desc_circulationSheet = 'Bank Advance Created';
                   $dataSave['Code'] = null;
                   $dataSave['CreatedBy'] = $this->session->userdata('NIP');
                   $dataSave['CreatedAt'] = date('Y-m-d H:i:s');
                   $dataSave['LastUpdatedBy'] = null;
                   $dataSave['LastUpdatedAt'] = null;
                }
                else
                {
                    $dataSave['Code'] = null;
                    
                    if ($G_data_[0]['Perihal'] != ''&& $G_data_[0]['Perihal'] != null) {
                        $Desc_circulationSheet = 'Bank Advance Edited';
                        $dataSave['LastUpdatedBy'] = $this->session->userdata('NIP');
                        $dataSave['LastUpdatedAt'] = date('Y-m-d H:i:s');
                    }
                    else
                    {
                        $Desc_circulationSheet = 'Bank Advance Created';
                        $dataSave['CreatedBy'] = $this->session->userdata('NIP');
                        $dataSave['CreatedAt'] = date('Y-m-d H:i:s');
                        $dataSave['LastUpdatedBy'] = null;
                        $dataSave['LastUpdatedAt'] = null;
                    }
                    
                }

                $dataSave['JsonStatus'] = json_encode($JsonStatus);
                $dataSave['Status'] = 1;
                $dataSave['Type'] = 'Bank Advance';
                $this->db->where('ID',$ID_payment);
                $this->db->update('db_payment.payment',$dataSave);

               $dataSave2 = array(
                   'Biaya' =>  $Input['Biaya'],
                   'TypePay' =>  $Input['TypePay'],
                   'Perihal' =>  $Input['Perihal'],
                   'No_Rekening' =>  $Input['No_Rekening'],
                   'ID_bank' =>  $Input['ID_bank'],
                   'Nama_Penerima' =>  $Input['Nama_Penerima'],
                   'Date_Needed' =>  $Input['Date_Needed'],
               );
                $this->db->where('ID_payment',$ID_payment);
                $this->db->update('db_payment.bank_advance',$dataSave2);
                // remove bank_advance_detail
                $ID_bank_advance = $G_data_[0]['ID'];
                $this->db->where('ID_bank_advance',$ID_bank_advance);
                $this->db->delete('db_payment.bank_advance_detail');
                // insert bank_advance_detail
                $dataSave = array(
                        'ID_bank_advance' => $ID_bank_advance,
                        'ID_budget_left' => $Input['ID_budget_left'],
                        'NamaBiaya' => $Input['Perihal'],
                        'Invoice' => $Input['Biaya'],
                    );
                $this->db->insert('db_payment.bank_advance_detail',$dataSave);
                // insert to spb_circulation_sheet
                    $this->m_spb->payment_circulation_sheet($ID_payment,$Desc_circulationSheet);
                // insert to po_circulation_sheet
                    $this->m_pr_po->po_circulation_sheet($Code_po_create,$Desc_circulationSheet);  
            }
            else
            {
                // echo json_encode('Mohon Cek RAD');
                die();
            }

        }
        else
        {
            // $rs = array('Status' => 0,'Change' => 0);
            // echo json_encode($rs);
            die();
        }
    }

}
