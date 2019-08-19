<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pettycash extends Budgeting_Controler {
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
    	$content = $this->load->view('global/budgeting/pettycash/menu_horizontal',$data,true);
    	$this->temp($content);
    }

    public function index()
    {
        $this->data['G_Approver'] = $this->m_pr_po->Get_m_Approver();
        $this->data['m_type_user'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');         
		$page = $this->load->view('global/budgeting/pettycash/list',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function create_pettycash()
    {
        $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
        $Year = $get[0]['Year'];
        $this->data['Year'] = $Year;
        $IDDepartementPUBudget = $this->session->userdata('IDDepartementPUBudget');
        // get budgeting/detail_budgeting_remaining
            $getData = $this->m_budgeting->get_budget_remaining($Year,$IDDepartementPUBudget);
            $arr_result = array('data' =>$getData);
        $this->data['detail_budgeting_remaining'] = json_encode($arr_result['data']);
        $G_depart = $this->m_budgeting->SearchDepartementBudgeting($IDDepartementPUBudget);
        $this->data['NameDepartement'] = $G_depart[0]['NameDepartement'];          
		$page = $this->load->view('global/budgeting/pettycash/create_pettycash',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function view_petty_cash_user($token)
    {
        $key = "UAP)(*";
        $ID_payment = $this->jwt->decode($token,$key);
        $this->data['ID_payment'] = $ID_payment;
        // get year and Department existing by budget left
            $__get_budget_left = function($ID_payment)
            {
                $Year = date('Y');
                $Departement = $this->session->userdata('IDDepartementPUBudget');
                $arr = array(
                    'Year' => $Year,
                    'Departement' => $Departement,
                );

                $G_ = $this->m_master->caribasedprimary('db_payment.petty_cash','ID_payment',$ID_payment);
                $G__detail = $this->m_master->caribasedprimary('db_payment.petty_cash_detail','ID_petty_cash',$G_[0]['ID']);
                $ID_budget_left = $G__detail[0]['ID_budget_left'];
                $q = $this->m_global->get_year_department_by_budget_left($ID_budget_left);
                $arr['Year'] = $q[0]['Year'];
                $arr['Departement'] = $q[0]['Departement'];
                return $arr;
            };
            $t = $__get_budget_left($ID_payment);
            $Year = $t['Year'];
            $IDDepartementPUBudget = $t['Departement'];
        $this->data['Year'] = $Year;  

        // get budgeting/detail_budgeting_remaining
            $getData = $this->m_budgeting->get_budget_remaining($Year,$IDDepartementPUBudget);
            $arr_result = array('data' =>$getData);
        $this->data['detail_budgeting_remaining'] = json_encode($arr_result['data']);
        $G_depart = $this->m_budgeting->SearchDepartementBudgeting($IDDepartementPUBudget);
        $this->data['NameDepartement'] = $G_depart[0]['NameDepartement'];          
        $page = $this->load->view('global/budgeting/pettycash/create_pettycash',$this->data,true);
        $this->menu_horizontal($page);
    }

    public function configuration()
    {
    	/*
			1.Only auth finance
    	*/
    	if ($this->session->userdata('IDDepartementPUBudget') == 'NA.9') {
    		$page = $this->load->view('global/budgeting/pettycash/configuration',$this->data,true);
    		$this->menu_horizontal($page);
    	}
    	else
    	{
    		show_404($log_error = TRUE);
    	}
    	
    }

    public function submit_pettycash_user()
    {
        $action = $this->input->post('Action');
        switch ($action) {
            case 1:
                $ID_payment = $this->input->post('ID_payment');
                $key = "UAP)(*";
                $ID_payment = $this->jwt->decode($ID_payment,$key);
                if ($ID_payment == '') {
                    $this->PaymentToIssued();
                }
                else
                {
                    $this->PaymentToIssued_edit();
                }
                
                break;
            default:
                # code...
                break;
        }
    }

    private function PaymentToIssued()
    {
        $msg = '';
        $St_error = 1;
        $BudgetChange = 0;
        $input = $this->getInputToken();
        $Year = $this->input->post('Year');
        $key = "UAP)(*";
        $Year = $this->jwt->decode($Year,$key);

        $Departement = $this->input->post('Departement');
        $key = "UAP)(*";
        $Departement = $this->jwt->decode($Departement,$key);

        $ID_payment = $this->input->post('ID_payment');
        $key = "UAP)(*";
        $ID_payment = $this->jwt->decode($ID_payment,$key);

        $Perihal = $this->input->post('Perihal');
        $key = "UAP)(*";
        $Perihal = $this->jwt->decode($Perihal,$key);

        $NoIOM = $this->input->post('NoIOM');
        $key = "UAP)(*";
        $NoIOM = $this->jwt->decode($NoIOM,$key);

        $BudgetRemaining = $this->input->post('BudgetRemaining');
        $key = "UAP)(*";
        $BudgetRemaining = $this->jwt->decode($BudgetRemaining,$key);
        $BudgetRemaining =  (array)  json_decode(json_encode($BudgetRemaining),true);

        $BudgetLeft_awal = $this->input->post('BudgetLeft_awal');
        $key = "UAP)(*";
        $BudgetLeft_awal = $this->jwt->decode($BudgetLeft_awal,$key);
        $BudgetLeft_awal = (array)  json_decode(json_encode($BudgetLeft_awal),true);

        // get template
        $ID_template = $this->input->post('ID_template');
        $key = "UAP)(*";
        $ID_template = $this->jwt->decode($ID_template,$key);

        $StatusPayment = '';

        // adding Supporting_documents
            $Supporting_documents = array();
            $Supporting_documents = json_encode($Supporting_documents); 
            if (array_key_exists('Supporting_documents', $_FILES)) {
                // do upload file
                $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'Supporting_documents',$path = './uploads/budgeting/pettycash');
                $Supporting_documents = json_encode($uploadFile); 
            }

        // RuleApproval
            // check Subtotal
                $Amount = 0;
                for ($i=0; $i < count($input); $i++) {
                    $data = $input[$i]; 
                    $key = "UAP)(*";
                    $data_arr = (array) $this->jwt->decode($data,$key);
                    // print_r($data_arr);
                    $SubTotal = $data_arr['SubTotal'];
                    $Amount = $Amount + $SubTotal;
                }
            // get approval template
            if ($ID_template != 0 ) {
               $JsonStatus = $this->m_pr_po->GetRuleApproval_Template($ID_template,$Departement); 
            }
            else
            {
                $JsonStatus = $this->m_pr_po->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$input);
            }
        
            if (count($JsonStatus) > 1) {
                $BoolBudget = $this->m_pr_po->checkBudgetClientToServer_edit($BudgetLeft_awal,$BudgetRemaining);
                if ($BoolBudget) { // jika Budget yang digunakan belum ada perubahan yang berarti cocok antara client dengan server
                    $dataSave = array(
                        'Type' => 'Petty Cash',
                        'CreatedAt' => date('Y-m-d H:i:s'),
                        'CreatedBy' => $this->session->userdata('NIP'),
                        'Status' => 1,
                        'JsonStatus' => json_encode($JsonStatus),
                        'NoIOM' => $NoIOM,
                        'UploadIOM' => $Supporting_documents,
                        'Departement' => $Departement,
                        'ID_template' => $ID_template,
                    );

                    $this->db->insert('db_payment.payment',$dataSave);
                    $ID_payment =$this->db->insert_id();
                    $StatusPayment = 1;
                    // passing show name JsonStatus
                    for ($i=0; $i < count($JsonStatus); $i++) { 
                        $Name = $this->m_master->SearchNameNIP_Employees_PU_Holding($JsonStatus[$i]['NIP']);
                        $Name = $Name[0]['Name'];
                        $JsonStatus[$i]['Name'] = $Name;
                    }

                    // insert ke table pettycash
                    $dataSave = array(
                        'ID_payment' => $ID_payment,
                        'Date_Needed' => date('Y-m-d'),
                        'Perihal' => $Perihal,
                        'TypePay' => 'Cash',
                        'ID_bank' => 0,
                        'Invoice' => $Amount,
                    );
                    $this->db->insert('db_payment.petty_cash',$dataSave);
                    $ID_petty_cash = $this->db->insert_id();

                    if ($this->db->affected_rows() > 0 )
                    {
                        // remove PRCode in pr_detail
                            // $this->db->where(array('PRCode' => $PRCode));
                            // $this->db->delete('db_budgeting.pr_detail');
                        for ($i=0; $i < count($input); $i++) {
                            $data = $input[$i]; 
                            $key = "UAP)(*";
                            $data_arr = (array) $this->jwt->decode($data,$key);
                            $PassNumber = $data_arr['PassNumber'];
                                $dataSave = array(
                                    'ID_petty_cash' =>$ID_petty_cash,
                                    'ID_budget_left' => $data_arr['ID_budget_left'],
                                    'NamaBiaya' => $data_arr['NamaBiaya'],
                                    'NomorAcc' => $data_arr['NomorAcc'],
                                    'Invoice' => $data_arr['SubTotal'],
                                ); 
                                $this->db->insert('db_payment.petty_cash_detail',$dataSave);
                                
                        }

                        // Update to budget_left
                            $this->m_pr_po->Update_budget_left_pr($BudgetLeft_awal,$BudgetRemaining,$input);

                        // insert to spb_circulation_sheet
                            $this->m_spb->payment_circulation_sheet($ID_payment,'Input Petty Cash');

                        // get CodeURL
                        $key = "UAP)(*";
                        $token = $this->jwt->encode($ID_payment,$key);
                        $CodeUrl = $token;    

                        // send notifikasi
                            $IDdiv = $Departement;
                            $G_div = $this->m_budgeting->SearchDepartementBudgeting($IDdiv);
                            // $NameDepartement = $G_div[0]['NameDepartement'];
                            $Code = $G_div[0]['Code'];
                            $data = array(
                                'auth' => 's3Cr3T-G4N',
                                'Logging' => array(
                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> Petty Cash has been Created by '.$Code,
                                                'Description' => 'Petty Cash has been Created by '.$Code.'('.$this->session->userdata('Name').')',
                                                'URLDirect' => 'budgeting_menu/pembayaran/pettycash/'.$CodeUrl,
                                                'CreatedBy' => $this->session->userdata('NIP'),
                                              ),
                                'To' => array(
                                          'NIP' => array($JsonStatus[1]['NIP']),
                                        ),
                                'Email' => 'No', 
                            );
                            $url = url_pas.'rest2/__send_notif_browser';
                            $token = $this->jwt->encode($data,"UAP)(*");
                            $this->m_master->apiservertoserver($url,$token);

                            // send email is holding or warek keatas
                                 $this->m_master->send_email_budgeting_holding($JsonStatus[1]['NIP'],$IDdiv,$data['Logging']['URLDirect'],$data['Logging']['Description']);      
                    }
                    else
                    {
                        //return FALSE;
                        $ID_payment = '';
                    }
                }
                else
                {
                    $BudgetChange = 1;
                }
                
            }
            else
            {
                $St_error = 0;
                $msg = 'Limit : '.$Amount.' not set in RAD';
            }
        
        echo json_encode(array('ID_payment' => $ID_payment,'JsonStatus' => json_encode($JsonStatus),'St_error' => $St_error,'msg'=>$msg,'StatusPayment' => $StatusPayment,'BudgetChange' => $BudgetChange));    
    }

    public function PaymentToIssued_edit()
    {
        $msg = '';
        $St_error = 1;
        $BudgetChange = 0;
        $input = $this->getInputToken();
        $Year = $this->input->post('Year');
        $key = "UAP)(*";
        $Year = $this->jwt->decode($Year,$key);

        $Departement = $this->input->post('Departement');
        $key = "UAP)(*";
        $Departement = $this->jwt->decode($Departement,$key);

        $ID_payment = $this->input->post('ID_payment');
        $key = "UAP)(*";
        $ID_payment = $this->jwt->decode($ID_payment,$key);

        $Perihal = $this->input->post('Perihal');
        $key = "UAP)(*";
        $Perihal = $this->jwt->decode($Perihal,$key);

        $NoIOM = $this->input->post('NoIOM');
        $key = "UAP)(*";
        $NoIOM = $this->jwt->decode($NoIOM,$key);

        $BudgetRemaining = $this->input->post('BudgetRemaining');
        $key = "UAP)(*";
        $BudgetRemaining = $this->jwt->decode($BudgetRemaining,$key);
        $BudgetRemaining =  (array)  json_decode(json_encode($BudgetRemaining),true);

        // get template
        $ID_template = $this->input->post('ID_template');
        $key = "UAP)(*";
        $ID_template = $this->jwt->decode($ID_template,$key);

        $BudgetLeft_awal = $this->input->post('BudgetLeft_awal');
        $key = "UAP)(*";
        $BudgetLeft_awal = $this->jwt->decode($BudgetLeft_awal,$key);
        $BudgetLeft_awal = (array)  json_decode(json_encode($BudgetLeft_awal),true);
        $StatusPayment = '';

        $dataSave = array(
            'Status' => 1,
        );
        $G_data = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
        // adding Supporting_documents
            $Supporting_documents = array();
            $Supporting_documents = json_encode($Supporting_documents); 
            if (array_key_exists('Supporting_documents', $_FILES)) {
                // do upload file
                $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'Supporting_documents',$path = './uploads/budgeting/pettycash');
                $F_Supporting_documents = $G_data[0]['UploadIOM'];
                $F_Supporting_documents = (array) json_decode($F_Supporting_documents,true);
                // for ($i=0; $i < count($F_Supporting_documents); $i++) { 
                //     $uploadFile[] = $F_Supporting_documents[$i];
                // }
                //print_r($uploadFile);
                $uploadFile = array_merge($uploadFile,$F_Supporting_documents);
                $Supporting_documents = json_encode($uploadFile);
                $dataSave['UploadIOM'] = $Supporting_documents; 
            }
        $JsonStatus = $G_data[0]['JsonStatus'];
        $JsonStatus = (array) json_decode($JsonStatus,true);

        // jika Status sama dengan 1 #  untuk ketika submit masih bisa edit sebelum approval
        $b = true;
        if ($G_data[0]['Status'] == 1) {
            for ($i=1; $i < count($JsonStatus); $i++) { // skip index 0 karena admin
                if ($JsonStatus[$i]['Status'] == 1) {
                    $b = false;
                    break;
                }
            }

            if (!$b) {
                $St_error = 0;
                $BudgetChange = 1;
                $msg = 'The data has been approve and will do to reload';
            }
        }

        if ($G_data[0]['Status'] == -1 || $b) {
            // update JsonStatus menjadi 0
            for ($i=1; $i < count($JsonStatus); $i++) { // skip index 0 karena admin
                $JsonStatus[$i]['Status'] = 0;
            }
            $dataSave['JsonStatus'] = json_encode($JsonStatus);
                // RuleApproval
                    // check Subtotal
                        $Amount = 0;
                        for ($i=0; $i < count($input); $i++) {
                            $data = $input[$i]; 
                            $key = "UAP)(*";
                            $data_arr = (array) $this->jwt->decode($data,$key);
                            // print_r($data_arr);
                            $SubTotal = $data_arr['SubTotal'];
                            $Amount = $Amount + $SubTotal;
                        }
                        
            // get approval template
            if ($ID_template != 0 ) {
               $JsonStatus2 = $this->m_pr_po->GetRuleApproval_Template($ID_template,$Departement); 
            }
            else
            {
                $JsonStatus2 = $this->m_pr_po->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$input);
            }   
            // new approval
            $dataSave['JsonStatus'] = json_encode($JsonStatus2);
                if (count($JsonStatus2) > 1) {
                    $BoolBudget = $this->m_pr_po->checkBudgetClientToServer_edit($BudgetLeft_awal,$BudgetRemaining);
                    if ($BoolBudget) { // jika Budget yang digunakan belum ada perubahan yang berarti cocok antara client dengan server
                        $dataSave['ID_template'] = $ID_template;
                        $dataSave['LastUpdatedBy'] = $this->session->userdata('NIP');
                        $dataSave['LastUpdatedAt'] = date('Y-m-d H:i:s');
                        $this->db->where('ID',$ID_payment);
                        $this->db->update('db_payment.payment',$dataSave);
                        $StatusPayment = 1;
                        // passing show name JsonStatus
                        for ($i=0; $i < count($JsonStatus); $i++) { 
                            $Name = $this->m_master->SearchNameNIP_Employees_PU_Holding($JsonStatus[$i]['NIP']);
                            $Name = $Name[0]['Name'];
                            $JsonStatus[$i]['Name'] = $Name;
                        } 

                        $dataSave = array(
                            'ID_payment' => $ID_payment,
                            'Date_Needed' => date('Y-m-d'),
                            'Perihal' => $Perihal,
                            'TypePay' => 'Cash',
                            'ID_bank' => 0,
                            'Invoice' => $Amount,
                        );
                        $this->db->where('ID_payment',$ID_payment);
                        $this->db->update('db_payment.petty_cash',$dataSave);
                        $G_dt_petty_cash = $this->m_master->caribasedprimary('db_payment.petty_cash','ID_payment',$ID_payment);
                        $ID_petty_cash = $G_dt_petty_cash[0]['ID'];

                        if ($this->db->affected_rows() > 0 )
                        {
                            /*
                                Note : 
                                Pengembalian Post Budget using ke awal sebelum pr tercreate
                            */
                             $BackBudgetToBeforeCreate = $this->m_global->BackBudgetToBeforeCreate($ID_payment,$Year,$Departement);  

                             // Simpan File pr_detail
                                $G_detail = $this->m_master->caribasedprimary('db_payment.petty_cash_detail','ID_petty_cash',$ID_petty_cash);


                            // remove PRCode in pr_detail
                                $this->db->where(array('ID_petty_cash' => $ID_petty_cash));
                                $this->db->delete('db_payment.petty_cash_detail');
                            for ($i=0; $i < count($input); $i++) {
                                $data = $input[$i]; 
                                $key = "UAP)(*";
                                $data_arr = (array) $this->jwt->decode($data,$key);
                                $PassNumber = $data_arr['PassNumber'];
                                $dataSave = array(
                                    'ID_petty_cash' =>$ID_petty_cash,
                                    'ID_budget_left' => $data_arr['ID_budget_left'],
                                    'NamaBiaya' => $data_arr['NamaBiaya'],
                                    'NomorAcc' => $data_arr['NomorAcc'],
                                    'Invoice' => $data_arr['SubTotal'],
                                ); 
                                $this->db->insert('db_payment.petty_cash_detail',$dataSave);
                            }

                            // Update to budget_left
                                $this->m_pr_po->Update_budget_left_pr($BudgetLeft_awal,$BudgetRemaining,$input);

                            // insert to spb_circulation_sheet
                                $this->m_spb->payment_circulation_sheet($ID_payment,'Edit Petty Cash');

                                // get CodeURL
                                $key = "UAP)(*";
                                $token = $this->jwt->encode($ID_payment,$key);
                                $CodeUrl = $token;

                                // send notifikasi
                                    $IDdiv = $Departement;
                                    $G_div = $this->m_budgeting->SearchDepartementBudgeting($IDdiv);
                                    // $NameDepartement = $G_div[0]['NameDepartement'];
                                    $Code = $G_div[0]['Code'];
                                    $data = array(
                                        'auth' => 's3Cr3T-G4N',
                                        'Logging' => array(
                                                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> Petty Cash has been Revised by '.$Code,
                                                        'Description' => 'Petty Cash has been Revised by '.$Code.'('.$this->session->userdata('Name').')',
                                                        'URLDirect' => 'budgeting_menu/pembayaran/pettycash/'.$CodeUrl,
                                                        'CreatedBy' => $this->session->userdata('NIP'),
                                                      ),
                                        'To' => array(
                                                  'NIP' => array($JsonStatus[1]['NIP']),
                                                ),
                                        'Email' => 'No', 
                                    );
                                    $url = url_pas.'rest2/__send_notif_browser';
                                    $token = $this->jwt->encode($data,"UAP)(*");
                                    $this->m_master->apiservertoserver($url,$token);

                                    // send email is holding or warek keatas
                                         $this->m_master->send_email_budgeting_holding($JsonStatus[1]['NIP'],$IDdiv,$data['Logging']['URLDirect'],$data['Logging']['Description']);
                        }
                        else
                        {
                            //return FALSE;
                             $ID_payment = '';
                        }
                    }
                    else
                    {
                        $BudgetChange = 1;
                    }
                    
                }
                else
                {
                    $St_error = 0;
                    $msg = 'Limit : '.$Amount.' not set in RAD';
                }
        }
        
        
        echo json_encode(array('ID_payment' => $ID_payment,'JsonStatus' => json_encode($JsonStatus),'St_error' => $St_error,'msg'=>$msg,'StatusPayment' => $StatusPayment,'BudgetChange' => $BudgetChange));
    }

}
