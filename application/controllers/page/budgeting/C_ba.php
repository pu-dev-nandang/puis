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

    public function create_ba_user($tokenPayment = null)
    {
    	   // get data bank rest/__Databank
                $data = array(
                    'auth' => 's3Cr3T-G4N', 
                );
                $key = "UAP)(*";
                $token = $this->jwt->encode($data,$key);
                $G_data_bank = $this->m_master->apiservertoserver(base_url().'rest/__Databank',$token);
                $this->data['G_data_bank'] = $G_data_bank;
                $IDDepartementPUBudget = $this->session->userdata('IDDepartementPUBudget');
        if ($tokenPayment == null) {
           $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
           $Year = $get[0]['Year'];
           $this->data['Year'] = $Year;

           $getData = $this->m_budgeting->get_budget_remaining($Year,$IDDepartementPUBudget);
           $arr_result = array('data' =>$getData);
           $this->data['detail_budgeting_remaining'] = json_encode($arr_result['data']);    
        }
        else{
            try {
                // read token
                $key = "UAP)(*";
                $ID_payment = $this->jwt->decode($tokenPayment,$key);
                $this->data['ID_payment'] = $ID_payment;
                // print_r($ID_payment);die();
                $G_dt_payment = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
                // get year and Department existing by budget left
                    $__get_budget_left = function($ID_payment)
                    {
                        $Year = date('Y');
                        $Departement = $this->session->userdata('IDDepartementPUBudget');
                        $arr = array(
                            'Year' => $Year,
                            'Departement' => $Departement,
                        );

                        $G_payType = $this->m_master->caribasedprimary('db_payment.bank_advance','ID_payment',$ID_payment);
                        $G_pay_detail = $this->m_master->caribasedprimary('db_payment.bank_advance_detail','ID_bank_advance',$G_payType[0]['ID']);
                        $ID_budget_left = $G_pay_detail[0]['ID_budget_left'];
                        $q = $this->m_global->get_year_department_by_budget_left($ID_budget_left);
                        $arr['Year'] = $q[0]['Year'];
                        $arr['Departement'] = $q[0]['Departement'];
                        return $arr;
                    };

                    $t = $__get_budget_left($ID_payment);
                    $Year = $t['Year'];
                    $IDDepartementPUBudget = $t['Departement'];
                $this->data['Year'] = $Year;    
                $getData = $this->m_budgeting->get_budget_remaining($Year,$IDDepartementPUBudget);
                $arr_result = array('data' =>$getData);
                $this->data['detail_budgeting_remaining'] = json_encode($arr_result['data']);   
            }
            //catch exception
            catch(Exception $e) {
                 show_404($log_error = TRUE); 
            }
            
        }   
        $G_depart = $this->m_budgeting->SearchDepartementBudgeting($IDDepartementPUBudget);
        $this->data['NameDepartement'] = $G_depart[0]['NameDepartement'];
        $page = $this->load->view('global/budgeting/ba/Infoba_User',$this->data,true);
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
                'Invoice' =>  $Input['Biaya'],
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

             // Notif to next step approval
                 $NIPApprovalNext = $JsonStatus[1]['NIP'];
                 $Type = 'Bank Advance';
                 $urlType = 'ba';
                 $NIP = $this->session->userdata('NIP');
                 $key = "UAP)(*";
                 $token = $this->jwt->encode($ID_payment,$key);
                 $CodeUrl = $token;
                 // Send Notif for next approval
                     $data = array(
                         'auth' => 's3Cr3T-G4N',
                         'Logging' => array(
                                         'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Approval '.$Type,
                                         'Description' => 'Please approve '.$Type,
                                         'URLDirect' => 'global/purchasing/transaction/'.$urlType.'/list/'.$CodeUrl,
                                         'CreatedBy' => $NIP,
                                       ),
                         'To' => array(
                                   'NIP' => array($NIPApprovalNext),
                                 ),
                         'Email' => 'No', 
                     );

                     $url = url_pas.'rest2/__send_notif_browser';
                     $token = $this->jwt->encode($data,"UAP)(*");
                     $this->m_master->apiservertoserver($url,$token);

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
                   'Invoice' =>  $Input['Biaya'],
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

                // Notif to next step approval
                    $NIPApprovalNext = $JsonStatus[1]['NIP'];
                    $Type = 'Bank Advance';
                    $urlType = 'ba';
                    $NIP = $this->session->userdata('NIP');
                    $key = "UAP)(*";
                    $token = $this->jwt->encode($ID_payment,$key);
                    $CodeUrl = $token;
                    // Send Notif for next approval
                        $data = array(
                            'auth' => 's3Cr3T-G4N',
                            'Logging' => array(
                                            'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Approval '.$Type.' after edited',
                                            'Description' => 'Please approve '.$Type.' after edited',
                                            'URLDirect' => 'global/purchasing/transaction/'.$urlType.'/list/'.$CodeUrl,
                                            'CreatedBy' => $NIP,
                                          ),
                            'To' => array(
                                      'NIP' => array($NIPApprovalNext),
                                    ),
                            'Email' => 'No', 
                        );

                        $url = url_pas.'rest2/__send_notif_browser';
                        $token = $this->jwt->encode($data,"UAP)(*");
                        $this->m_master->apiservertoserver($url,$token);

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

    public function submitba_realisasi_by_po()
    {
        $rs = array('Status' => 0,'Change' => 0);
        $Input = $this->getInputToken();
        $action = $Input['action'];
        $ID_payment = $Input['ID_payment'];
        switch ($action) {
            case 'add':
                $ID_bank_advance = $Input['ID_payment_type'];
                $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/bankadvance');
                $UploadInvoice = json_encode($UploadInvoice); 

                $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/bankadvance');
                $UploadTandaTerima = json_encode($UploadTandaTerima);
                $JsonStatus = json_encode($this->m_global->JsonStatusRealisasi());
                $Status = 1;
                $dataSave = array(
                    'ID_bank_advance' => $ID_bank_advance,
                    'UploadInvoice' => $UploadInvoice,
                    'NoInvoice' => $Input['NoInvoice'],
                    'UploadTandaTerima' => $UploadTandaTerima,
                    'NoTandaTerima' => $Input['NoTandaTerima'],
                    'Date_Realisasi' =>  $Input['Date_Realisasi'],
                    'Status' => $Status,
                    'JsonStatus' => $JsonStatus,
                );

                $this->db->insert('db_payment.bank_advance_realisasi',$dataSave);
                
                $G = $this->m_master->caribasedprimary('db_payment.bank_advance_detail','ID_bank_advance',$ID_bank_advance);
                for ($i=0; $i < count($G); $i++) { 
                    $dataSave = array(
                        'ID_bank_advance_detail' => $G[$i]['ID'],
                        'InvoiceRealisasi' => $G[$i]['Invoice'],
                        'Status' => 1,
                    );
                    $this->db->insert('db_payment.bank_advance_realisasi_detail',$dataSave);
                }

                 $this->m_spb->payment_circulation_sheet($ID_payment,'Input Realisasi');
                $rs['Status']= 1;
                break;
            case 'edit':
                $ID_Realisasi = $Input['ID_Realisasi'];
                $ID_bank_advance = $Input['ID_payment_type'];
                $JsonStatus = json_encode($this->m_global->JsonStatusRealisasi());
                $Status = 1;
                $dataSave = array(
                    'ID_bank_advance' => $ID_bank_advance,
                    'NoInvoice' => $Input['NoInvoice'],
                    'NoTandaTerima' => $Input['NoTandaTerima'],
                    'Date_Realisasi' =>  $Input['Date_Realisasi'],
                    'Status' => $Status,
                    'JsonStatus' => $JsonStatus,
                );
                $G_data_ = $this->m_master->caribasedprimary('db_payment.bank_advance_realisasi','ID',$ID_Realisasi);
                // delete old file and upload new file if user do upload
                if (array_key_exists('UploadInvoice', $_FILES)) {
                    // remove old file
                    if ($G_data_[0]['UploadInvoice'] != '' && $G_data_[0]['UploadInvoice'] != null) {
                        $arr_file = (array) json_decode($G_data_[0]['UploadInvoice'],true);
                        $filePath = 'budgeting\\bankadvance\\'.$arr_file[0]; // pasti ada file karena required
                        $path = FCPATH.'uploads\\'.$filePath;
                        unlink($path);
                    }

                    // do upload file
                    $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/bankadvance');
                    $UploadInvoice = json_encode($UploadInvoice); 
                    $dataSave['UploadInvoice'] = $UploadInvoice; 
                }

                if (array_key_exists('UploadTandaTerima', $_FILES)) {
                    // remove old file
                        if ($G_data_[0]['UploadTandaTerima'] != '' && $G_data_[0]['UploadTandaTerima'] != null) {
                            $arr_file = (array) json_decode($G_data_[0]['UploadTandaTerima'],true);
                            $filePath = 'budgeting\\bankadvance\\'.$arr_file[0]; // pasti ada file karena required
                            $path = FCPATH.'uploads\\'.$filePath;
                            unlink($path);
                        }

                    // do upload file
                    $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/bankadvance');
                    $UploadTandaTerima = json_encode($UploadTandaTerima); 
                    $dataSave['UploadTandaTerima'] = $UploadTandaTerima; 
                }

                $this->db->where('ID',$ID_Realisasi);
                $this->db->update('db_payment.bank_advance_realisasi',$dataSave);
                $this->m_spb->payment_circulation_sheet($ID_payment,'Edit Realisasi');
                $rs['Status']= 1;
                break;
            default:
                # code...
                break;
        }
        echo json_encode($rs);
    }

    public function submit_bank_advance_user()
    {
        $action = $this->input->post('Action');
        switch ($action) {
            case 1:
                $ID_payment = $this->input->post('ID_payment');
                $key = "UAP)(*";
                $ID_payment = $this->jwt->decode($ID_payment,$key);
                if ($ID_payment == '') {
                    $this->PaymentToIssued_user();
                }
                else
                {
                    $this->PaymentToIssued_edit_user();
                }
                
                break;
            default:
                # code...
                break;
        }
    }

    public function PaymentToIssued_user()
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

        $FormInsert = $this->input->post('FormInsert');
        $key = "UAP)(*";
        $FormInsert = $this->jwt->decode($FormInsert,$key);
        $FormInsert = (array)  json_decode(json_encode($FormInsert),true);

        $StatusPayment = '';

        // adding Supporting_documents
            $Supporting_documents = array();
            $Supporting_documents = json_encode($Supporting_documents); 
           
            if (array_key_exists('Supporting_documents', $_FILES)) {
                // do upload file
                $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'Supporting_documents',$path = './uploads/budgeting/bankadvance');
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
            $JsonStatus = $this->m_pr_po->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$input);
            if (count($JsonStatus) > 1) {
                $BoolBudget = $this->m_pr_po->checkBudgetClientToServer_edit($BudgetLeft_awal,$BudgetRemaining);
                if ($BoolBudget) {
                    $dataSave = array(
                        'Type' => 'Bank Advance',
                        'CreatedAt' => date('Y-m-d H:i:s'),
                        'CreatedBy' => $this->session->userdata('NIP'),
                        'Status' => 1,
                        'JsonStatus' => json_encode($JsonStatus),
                        'NoIOM' => $NoIOM,
                        'UploadIOM' => $Supporting_documents,
                        'Departement' => $Departement,
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

                     // insert ke table spb
                     $FormInsert['ID_payment'] = $ID_payment;
                     $dataSave = $FormInsert;
                     $this->db->insert('db_payment.bank_advance',$dataSave);
                     $ID_bank_advance = $this->db->insert_id();

                     for ($i=0; $i < count($input); $i++) {
                         $data = $input[$i]; 
                         $key = "UAP)(*";
                         $data_arr = (array) $this->jwt->decode($data,$key);
                         $PassNumber = $data_arr['PassNumber'];
                             $dataSave = array(
                                 'ID_bank_advance' =>$ID_bank_advance,
                                 'ID_budget_left' => $data_arr['ID_budget_left'],
                                 'NamaBiaya' => $data_arr['NamaBiaya'],
                                 'Invoice' => $data_arr['SubTotal'],
                             ); 
                             $this->db->insert('db_payment.bank_advance_detail',$dataSave);
                     }

                     // Update to budget_left
                         $this->m_pr_po->Update_budget_left_pr($BudgetLeft_awal,$BudgetRemaining,$input);

                     // insert to spb_circulation_sheet
                         $this->m_spb->payment_circulation_sheet($ID_payment,'Input Bank Advance');

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
                                             'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> Bank Advance has been Created by '.$Code,
                                             'Description' => 'Bank Advance has been Created by '.$Code.'('.$this->session->userdata('Name').')',
                                             'URLDirect' => 'budgeting_menu/pembayaran/bank_advance/'.$CodeUrl,
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

    public function PaymentToIssued_edit_user()
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

        $FormInsert = $this->input->post('FormInsert');
        $key = "UAP)(*";
        $FormInsert = $this->jwt->decode($FormInsert,$key);
        $FormInsert = (array)  json_decode(json_encode($FormInsert),true);

        $StatusPayment = '';

        $dataSave = array(
            'Status' => 1,
        );
        $G_data = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);

        if (array_key_exists('Supporting_documents', $_FILES)) {
            // do upload file
            $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'Supporting_documents',$path = './uploads/budgeting/bankadvance');
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
            $JsonStatus2 = $this->m_pr_po->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$input);
            // new approval
            $dataSave['JsonStatus'] = json_encode($JsonStatus2);

        }

        if (count($JsonStatus2) > 1) {
            $BoolBudget = $this->m_pr_po->checkBudgetClientToServer_edit($BudgetLeft_awal,$BudgetRemaining);
            if ($BoolBudget) {
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

                 // update ke table spb
                // get data PaymentType
                    $G_data_ = $this->m_master->caribasedprimary('db_payment.bank_advance','ID_payment',$ID_payment);
                 $dataSave = $FormInsert;

                 $this->db->where('ID_payment',$ID_payment);
                 $this->db->update('db_payment.bank_advance',$dataSave);
                 $G_dt_spb = $this->m_master->caribasedprimary('db_payment.bank_advance','ID_payment',$ID_payment);
                 $ID_bank_advance = $G_dt_spb[0]['ID'];

                 if ($this->db->affected_rows() > 0 )
                 {
                    /*
                        Note : 
                        Pengembalian Post Budget using ke awal sebelum pr tercreate
                    */
                     $BackBudgetToBeforeCreate = $this->m_global->BackBudgetToBeforeCreate($ID_payment,$Year,$Departement);

                     // $G_detail = $this->m_master->caribasedprimary('db_payment.bank_advance_detail','ID_bank_advance',$ID_bank_advance);
                     $this->db->where(array('ID_bank_advance' => $ID_bank_advance));
                     $this->db->delete('db_payment.bank_advance_detail');

                     for ($i=0; $i < count($input); $i++) {
                         $data = $input[$i]; 
                         $key = "UAP)(*";
                         $data_arr = (array) $this->jwt->decode($data,$key);
                         $PassNumber = $data_arr['PassNumber'];
                             $dataSave = array(
                                 'ID_bank_advance' =>$ID_bank_advance,
                                 'ID_budget_left' => $data_arr['ID_budget_left'],
                                 'NamaBiaya' => $data_arr['NamaBiaya'],
                                 'Invoice' => $data_arr['SubTotal'],
                             ); 
                             $this->db->insert('db_payment.bank_advance_detail',$dataSave);
                     }

                     // Update to budget_left
                         $this->m_pr_po->Update_budget_left_pr($BudgetLeft_awal,$BudgetRemaining,$input);

                     // insert to spb_circulation_sheet
                         $this->m_spb->payment_circulation_sheet($ID_payment,'Edit Bank Advance');

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
                                                 'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> Bank Advance has been Revised by '.$Code,
                                                 'Description' => 'Bank Advance has been Revised by '.$Code.'('.$this->session->userdata('Name').')',
                                                 'URLDirect' => 'budgeting_menu/pembayaran/bank_advance/'.$CodeUrl,
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

    public function submitba_realisasi_by_user()
    {
        $rs = array('Status' => 0,'Change' => 0);
        $Input = $this->getInputToken();
        $action = $Input['action'];
        $ID_payment = $Input['ID_payment'];
        switch ($action) {
            case 'add':
                $ID_bank_advance = $Input['ID_payment_type'];
                $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/bankadvance');
                $UploadInvoice = json_encode($UploadInvoice); 

                $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/bankadvance');
                $UploadTandaTerima = json_encode($UploadTandaTerima);
                $JsonStatus = json_encode($this->m_global->JsonStatusRealisasi());
                $Status = 1;
                $dataSave = array(
                    'ID_bank_advance' => $ID_bank_advance,
                    'UploadInvoice' => $UploadInvoice,
                    'NoInvoice' => $Input['NoInvoice'],
                    'UploadTandaTerima' => $UploadTandaTerima,
                    'NoTandaTerima' => $Input['NoTandaTerima'],
                    'Date_Realisasi' =>  $Input['Date_Realisasi'],
                    'Status' => $Status,
                    'JsonStatus' => $JsonStatus,
                );

                $this->db->insert('db_payment.bank_advance_realisasi',$dataSave);
                
                // $G = $this->m_master->caribasedprimary('db_payment.bank_advance_detail','ID_bank_advance',$ID_bank_advance);
                $FormInsertDetail = $this->input->post('FormInsertDetail');
                $key = "UAP)(*";
                $FormInsertDetail = $this->jwt->decode($FormInsertDetail,$key);
                $FormInsertDetail =  (array)  json_decode(json_encode($FormInsertDetail),true);
                for ($i=0; $i < count($FormInsertDetail); $i++) { 
                    $dataSave = array(
                        'ID_bank_advance_detail' => $FormInsertDetail[$i]['ID_payment_detail'],
                        'InvoiceRealisasi' => $FormInsertDetail[$i]['InvoiceRealisasi'],
                        'Status' => 1,
                    );
                    $this->db->insert('db_payment.bank_advance_realisasi_detail',$dataSave);
                }

                 $this->m_spb->payment_circulation_sheet($ID_payment,'Input Realisasi');
                $rs['Status']= 1;
                break;
            case 'edit':
                $ID_Realisasi = $Input['ID_Realisasi'];
                $ID_bank_advance = $Input['ID_payment_type'];
                $JsonStatus = json_encode($this->m_global->JsonStatusRealisasi());
                $Status = 1;
                $dataSave = array(
                    'ID_bank_advance' => $ID_bank_advance,
                    'NoInvoice' => $Input['NoInvoice'],
                    'NoTandaTerima' => $Input['NoTandaTerima'],
                    'Date_Realisasi' =>  $Input['Date_Realisasi'],
                    'Status' => $Status,
                    'JsonStatus' => $JsonStatus,
                );
                $G_data_ = $this->m_master->caribasedprimary('db_payment.bank_advance_realisasi','ID',$ID_Realisasi);
                // delete old file and upload new file if user do upload
                if (array_key_exists('UploadInvoice', $_FILES)) {
                    // remove old file
                    if ($G_data_[0]['UploadInvoice'] != '' && $G_data_[0]['UploadInvoice'] != null) {
                        $arr_file = (array) json_decode($G_data_[0]['UploadInvoice'],true);
                        $filePath = 'budgeting\\bankadvance\\'.$arr_file[0]; // pasti ada file karena required
                        $path = FCPATH.'uploads\\'.$filePath;
                        unlink($path);
                    }

                    // do upload file
                    $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/bankadvance');
                    $UploadInvoice = json_encode($UploadInvoice); 
                    $dataSave['UploadInvoice'] = $UploadInvoice; 
                }

                if (array_key_exists('UploadTandaTerima', $_FILES)) {
                    // remove old file
                        if ($G_data_[0]['UploadTandaTerima'] != '' && $G_data_[0]['UploadTandaTerima'] != null) {
                            $arr_file = (array) json_decode($G_data_[0]['UploadTandaTerima'],true);
                            $filePath = 'budgeting\\bankadvance\\'.$arr_file[0]; // pasti ada file karena required
                            $path = FCPATH.'uploads\\'.$filePath;
                            unlink($path);
                        }

                    // do upload file
                    $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/bankadvance');
                    $UploadTandaTerima = json_encode($UploadTandaTerima); 
                    $dataSave['UploadTandaTerima'] = $UploadTandaTerima; 
                }

                $this->db->where('ID',$ID_Realisasi);
                $this->db->update('db_payment.bank_advance_realisasi',$dataSave);

                // bank_advance_detail delete first and insert again
                    $FormInsertDetail = $this->input->post('FormInsertDetail');
                    $key = "UAP)(*";
                    $FormInsertDetail = $this->jwt->decode($FormInsertDetail,$key);
                    $FormInsertDetail =  (array)  json_decode(json_encode($FormInsertDetail),true);

                    for ($i=0; $i < count($FormInsertDetail); $i++) { 
                        $this->db->where('ID_bank_advance_detail',$FormInsertDetail[$i]['ID_payment_detail']);
                        $this->db->delete('db_payment.bank_advance_realisasi_detail');
                    }

                    for ($i=0; $i < count($FormInsertDetail); $i++) { 
                        $dataSave = array(
                            'ID_bank_advance_detail' => $FormInsertDetail[$i]['ID_payment_detail'],
                            'InvoiceRealisasi' => $FormInsertDetail[$i]['InvoiceRealisasi'],
                            'Status' => 1,
                        );
                        $this->db->insert('db_payment.bank_advance_realisasi_detail',$dataSave);
                    }

                $this->m_spb->payment_circulation_sheet($ID_payment,'Edit Realisasi');
                $rs['Status']= 1;
                break;
            default:
                # code...
                break;
        }
        echo json_encode($rs);
    }

}
