<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_cashadvance extends Budgeting_Controler {
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
    	$content = $this->load->view('global/budgeting/cashadvance/menu_horizontal',$data,true);
    	$this->temp($content);
    }

    public function index()
    {
        /*
            1.filtering by pr
        */
        $this->data['G_Approver'] = $this->m_pr_po->Get_m_Approver();
        $this->data['m_type_user'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');          
		$page = $this->load->view('global/budgeting/cashadvance/list',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function create_cashadvance()
    {
		$page = $this->load->view('global/budgeting/cashadvance/create_cashadvance',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function configuration()
    {
    	/*
			1.Only auth finance
    	*/
    	if ($this->session->userdata('IDDepartementPUBudget') == 'NA.9') {
    		$page = $this->load->view('global/budgeting/cashadvance/configuration',$this->data,true);
    		$this->menu_horizontal($page);
    	}
    	else
    	{
    		show_404($log_error = TRUE);
    	}
    	
    }

    public function submitca()
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
                        $this->insert_ca();
                        $rs['Status']= 1;
                        break;
                    case 'edit':
                        // check status tidak sama dengan 2
                        $ID_payment = $Input['ID_payment'];
                        $G_spb_created = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
                        // find data spb
                        $G_data = $this->m_master->caribasedprimary('db_payment.cash_advance','ID_payment',$ID_payment);
                        if ($G_spb_created[0]['Status'] != 2) {
                            if (count($G_data) > 0) { // spb exist
                               $this->edit_ca();
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
                                $this->db->insert('db_payment.cash_advance',$dts);
                                $this->edit_ca();
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

    public function insert_ca()
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
            $Desc_circulationSheet = 'Cash Advance Created';
            $dataSave1 = array(
                'Type' => 'Cash Advance',
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

            $this->db->insert('db_payment.cash_advance',$dataSave2);
            $ID_cash_advance = $this->db->insert_id();
            // insert bank_advance_detail
            $dataSave = array(
                    'ID_cash_advance' => $ID_cash_advance,
                    'ID_budget_left' => $Input['ID_budget_left'],
                    'NamaBiaya' => $Input['Perihal'],
                    'Invoice' => $Input['Biaya'],
                );

             $this->db->insert('db_payment.cash_advance_detail',$dataSave);

             // Notif to next step approval
                 $NIPApprovalNext = $JsonStatus[1]['NIP'];
                 $Type = 'Cash Advance';
                 $urlType = 'ca';
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

    public function edit_ca()
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
            $G_data_ = $this->m_master->caribasedprimary('db_payment.cash_advance','ID_payment',$ID_payment);
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
                   $Desc_circulationSheet = 'Cash Advance Created';
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
                        $Desc_circulationSheet = 'Cash Advance Edited';
                        $dataSave['LastUpdatedBy'] = $this->session->userdata('NIP');
                        $dataSave['LastUpdatedAt'] = date('Y-m-d H:i:s');
                    }
                    else
                    {
                        $Desc_circulationSheet = 'Cash Advance Created';
                        $dataSave['CreatedBy'] = $this->session->userdata('NIP');
                        $dataSave['CreatedAt'] = date('Y-m-d H:i:s');
                        $dataSave['LastUpdatedBy'] = null;
                        $dataSave['LastUpdatedAt'] = null;
                    }
                    
                }

                $dataSave['JsonStatus'] = json_encode($JsonStatus);
                $dataSave['Status'] = 1;
                $dataSave['Type'] = 'Cash Advance';
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
                $this->db->update('db_payment.cash_advance',$dataSave2);
                // remove bank_advance_detail
                $ID_cash_advance = $G_data_[0]['ID'];
                $this->db->where('ID_cash_advance',$ID_cash_advance);
                $this->db->delete('db_payment.cash_advance_detail');
                // insert bank_advance_detail
                $dataSave = array(
                        'ID_cash_advance' => $ID_cash_advance,
                        'ID_budget_left' => $Input['ID_budget_left'],
                        'NamaBiaya' => $Input['Perihal'],
                        'Invoice' => $Input['Biaya'],
                    );
                $this->db->insert('db_payment.cash_advance_detail',$dataSave);

                // Notif to next step approval
                    $NIPApprovalNext = $JsonStatus[1]['NIP'];
                    $Type = 'Cash Advance';
                    $urlType = 'ca';
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

    public function submitca_realisasi_by_po()
    {
        $rs = array('Status' => 0,'Change' => 0);
        $Input = $this->getInputToken();
        $action = $Input['action'];
        $ID_payment = $Input['ID_payment'];
        switch ($action) {
            case 'add':
                $ID_cash_advance = $Input['ID_payment_type'];
                $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/cashadvance');
                $UploadInvoice = json_encode($UploadInvoice); 

                $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/cashadvance');
                $UploadTandaTerima = json_encode($UploadTandaTerima);
                $JsonStatus = json_encode($this->m_global->JsonStatusRealisasi());
                $Status = 1;
                $dataSave = array(
                    'ID_cash_advance' => $ID_cash_advance,
                    'UploadInvoice' => $UploadInvoice,
                    'NoInvoice' => $Input['NoInvoice'],
                    'UploadTandaTerima' => $UploadTandaTerima,
                    'NoTandaTerima' => $Input['NoTandaTerima'],
                    'Date_Realisasi' =>  $Input['Date_Realisasi'],
                    'Status' => $Status,
                    'JsonStatus' => $JsonStatus,
                );

                $this->db->insert('db_payment.cash_advance_realisasi',$dataSave);
                
                $G = $this->m_master->caribasedprimary('db_payment.cash_advance_detail','ID_cash_advance',$ID_cash_advance);
                for ($i=0; $i < count($G); $i++) { 
                    $dataSave = array(
                        'ID_cash_advance_detail' => $G[$i]['ID'],
                        'InvoiceRealisasi' => $G[$i]['Invoice'],
                        'Status' => 1,
                    );
                    $this->db->insert('db_payment.cash_advance_realisasi_detail',$dataSave);
                }

                 $this->m_spb->payment_circulation_sheet($ID_payment,'Input Realisasi');
                $rs['Status']= 1;
                break;
            case 'edit':
                $ID_Realisasi = $Input['ID_Realisasi'];
                $ID_cash_advance = $Input['ID_payment_type'];
                $JsonStatus = json_encode($this->m_global->JsonStatusRealisasi());
                $Status = 1;
                $dataSave = array(
                    'ID_cash_advance' => $ID_cash_advance,
                    'NoInvoice' => $Input['NoInvoice'],
                    'NoTandaTerima' => $Input['NoTandaTerima'],
                    'Date_Realisasi' =>  $Input['Date_Realisasi'],
                    'Status' => $Status,
                    'JsonStatus' => $JsonStatus,
                );
                $G_data_ = $this->m_master->caribasedprimary('db_payment.cash_advance_realisasi','ID',$ID_Realisasi);
                // delete old file and upload new file if user do upload
                if (array_key_exists('UploadInvoice', $_FILES)) {
                    // remove old file
                    if ($G_data_[0]['UploadInvoice'] != '' && $G_data_[0]['UploadInvoice'] != null) {
                        $arr_file = (array) json_decode($G_data_[0]['UploadInvoice'],true);
                        $filePath = 'budgeting\\cashadvance\\'.$arr_file[0]; // pasti ada file karena required
                        $path = FCPATH.'uploads\\'.$filePath;
                        unlink($path);
                    }

                    // do upload file
                    $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/cashadvance');
                    $UploadInvoice = json_encode($UploadInvoice); 
                    $dataSave['UploadInvoice'] = $UploadInvoice; 
                }

                if (array_key_exists('UploadTandaTerima', $_FILES)) {
                    // remove old file
                        if ($G_data_[0]['UploadTandaTerima'] != '' && $G_data_[0]['UploadTandaTerima'] != null) {
                            $arr_file = (array) json_decode($G_data_[0]['UploadTandaTerima'],true);
                            $filePath = 'budgeting\\cashadvance\\'.$arr_file[0]; // pasti ada file karena required
                            $path = FCPATH.'uploads\\'.$filePath;
                            unlink($path);
                        }

                    // do upload file
                    $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/cashadvance');
                    $UploadTandaTerima = json_encode($UploadTandaTerima); 
                    $dataSave['UploadTandaTerima'] = $UploadTandaTerima; 
                }

                $this->db->where('ID',$ID_Realisasi);
                $this->db->update('db_payment.cash_advance_realisasi',$dataSave);
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
