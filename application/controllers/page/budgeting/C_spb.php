<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_spb extends Budgeting_Controler { // SPB / Bank Advance 
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
    	$content = $this->load->view('global/budgeting/spb/menu_horizontal',$data,true);
    	$this->temp($content);
    }

    public function index()
    {
    	/*
			1.filtering by pr
    	*/
        $this->data['G_Approver'] = $this->m_pr_po->Get_m_Approver();
        $this->data['m_type_user'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');    
		$page = $this->load->view('global/budgeting/spb/list',$this->data,true);
		$this->menu_horizontal($page);
    }

    public function create_spb($tokenSPB = null)
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
        if ($tokenSPB == null) {
           $this->data['SPBCode'] = '';
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
                $ID_payment = $this->jwt->decode($tokenSPB,$key);
                $this->data['ID_payment'] = $ID_payment;
                // print_r($ID_payment);die();
                $G_dt_payment = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
                $this->data['SPBCode'] = $G_dt_payment[0]['Code'];
                // get year and Department existing by budget left
                    $__get_budget_left = function($ID_payment)
                    {
                        $Year = date('Y');
                        $Departement = $this->session->userdata('IDDepartementPUBudget');
                        $arr = array(
                            'Year' => $Year,
                            'Departement' => $Departement,
                        );

                        $G_spb = $this->m_master->caribasedprimary('db_payment.spb','ID_payment',$ID_payment);
                        $G_spb_detail = $this->m_master->caribasedprimary('db_payment.spb_detail','ID_spb',$G_spb[0]['ID']);
                        $ID_budget_left = $G_spb_detail[0]['ID_budget_left'];
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
        $page = $this->load->view('global/budgeting/spb/InfoSPB_User',$this->data,true);
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
                        $this->insert_spb();
                        $rs['Status']= 1;
                        break;
                    case 'edit':
                        // check status tidak sama dengan 2
                        $ID_payment = $Input['ID_payment'];
                        $G_spb_created = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
                        // find data spb
                        $G_data = $this->m_master->caribasedprimary('db_payment.spb','ID_payment',$ID_payment);
                        if ($G_spb_created[0]['Status'] != 2) {
                            if (count($G_data) > 0) { // spb exist
                               $this->edit_spb();
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
                                $this->db->insert('db_payment.spb',$dts);
                                $this->edit_spb();
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

    public function insert_spb()
    {
        $Input = $this->getInputToken();
        $Code_SPB = '';
        $Desc_circulationSheet = '';
        unset($Input['action']);
        $Code_po_create = $Input['Code_po_create'];
        $Departement = $Input['Departement'];
        // get Approval
        $token3 = $this->input->post('token3');
        $Amount = $Input['Invoice'];

        // for approval
        $token4 = $this->input->post('token4');
        $key = "UAP)(*";
        $token4 = (array) $this->jwt->decode($token4,$key);
        $JsonStatus =  $this->m_pr_po->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$token4);
        if (count($JsonStatus) > 1) {
            $dataSave = $Input;
            $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/spb');
            $UploadInvoice = json_encode($UploadInvoice); 

            $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/spb');
            $UploadTandaTerima = json_encode($UploadTandaTerima);   

            $Code = $this->m_spb->Get_SPBCode($Departement);
            $Desc_circulationSheet = 'SPB created{'.$Code.'}';
            $dataSave1 = array(
                'Type' => 'Spb',
                'Code' => $Code,
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
                'ID_budget_left' => $Input['ID_budget_left'],
                'ID_payment' => $ID_payment,
                'UploadInvoice' => $UploadInvoice,
                'NoInvoice' => $Input['NoInvoice'],
                'UploadTandaTerima' => $UploadTandaTerima,
                'NoTandaTerima' => $Input['NoTandaTerima'],
                'Datee' => $Input['Datee'],
                'Perihal' => $Input['Perihal'],
                'No_Rekening' => $Input['No_Rekening'],
                'ID_bank' => $Input['ID_bank'],
                'Invoice' => $Input['Invoice'],
                'TypeInvoice' => $Input['TypeInvoice'],
                'TypeBayar' => $Input['TypeBayar'],
            );

            $this->db->insert('db_payment.spb',$dataSave2);

            // send to notifikasi ke approval 1
            // Notif to next step approval & User
                $NIPApprovalNext = $JsonStatus[1]['NIP'];
                $CodeUrl = str_replace('/', '-', $Code);
                // Send Notif for next approval
                    $data = array(
                        'auth' => 's3Cr3T-G4N',
                        'Logging' => array(
                                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Created SPB : '.$Code,
                                        'Description' => 'Please approve SPB '.$Code,
                                        'URLDirect' => 'global/purchasing/transaction/spb/list/'.$CodeUrl,
                                        'CreatedBy' => $this->session->userdata('NIP'),
                                      ),
                        'To' => array(
                                  'NIP' => array($NIPApprovalNext),
                                ),
                        'Email' => 'No', 
                    );

                    $url = url_pas.'rest2/__send_notif_browser';
                    $token = $this->jwt->encode($data,"UAP)(*");
                    $this->m_master->apiservertoserver($url,$token);

                    // send email is holding or warek keatas
                         $this->m_master->send_email_budgeting_holding($NIPApprovalNext,'NA.4',$data['Logging']['URLDirect'],$data['Logging']['Description']);

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

    public function edit_spb()
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
            $G_data_ = $this->m_master->caribasedprimary('db_payment.spb','ID_payment',$ID_payment);
        if ($G_data[0]['Status'] != -1) {
             if (count($JsonStatus_existing) > 0) {
                 for ($i=1; $i < count($JsonStatus_existing); $i++) { 
                     if ($JsonStatus_existing[$i]['Status'] == 1) {
                         $bool = false;
                         break;
                     }
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
            $Amount = $Input['Invoice'];
            $JsonStatus =  $this->m_pr_po->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$token4);
            if (count($JsonStatus) > 1) {
                $dataSave = array(
                    'Departement' => $Input['Departement'],
                    'Code_po_create' => $Code_po_create,
                );
                $dataSave2 = array();
                if ($G_data[0]['Code'] =='' || $G_data[0]['Code'] == null) {
                    $dataSave['Code'] = $this->m_spb->Get_SPBCode($Departement);
                    $Code_SPB = $dataSave['Code'];
                    $Desc_circulationSheet = 'SPB created{'.$Code_SPB.'}';
                    $dataSave['CreatedBy'] = $this->session->userdata('NIP');
                    $dataSave['CreatedAt'] = date('Y-m-d H:i:s');
                }
                else
                {
                    $dataSave['LastUpdatedBy'] = $this->session->userdata('NIP');
                    $dataSave['LastUpdatedAt'] = date('Y-m-d H:i:s');
                    $Code_SPB = $G_data[0]['Code'];
                    $Desc_circulationSheet = 'SPB edited{'.$Code_SPB.'}';
                }

                // delete old file and upload new file if user do upload
                if (array_key_exists('UploadInvoice', $_FILES)) {
                    // remove old file
                    if ($G_data_[0]['UploadInvoice'] != '' && $G_data_[0]['UploadInvoice'] != null) {
                        $arr_file = (array) json_decode($G_data_[0]['UploadInvoice'],true);
                        $filePath = 'budgeting\\spb\\'.$arr_file[0]; // pasti ada file karena required
                        $path = FCPATH.'uploads\\'.$filePath;
                        unlink($path);
                    }

                    // do upload file
                    $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/spb');
                    $UploadInvoice = json_encode($UploadInvoice); 
                    $dataSave2['UploadInvoice'] = $UploadInvoice; 
                }

                if (array_key_exists('UploadTandaTerima', $_FILES)) {
                    // remove old file
                        if ($G_data_[0]['UploadTandaTerima'] != '' && $G_data_[0]['UploadTandaTerima'] != null) {
                            $arr_file = (array) json_decode($G_data_[0]['UploadTandaTerima'],true);
                            $filePath = 'budgeting\\spb\\'.$arr_file[0]; // pasti ada file karena required
                            $path = FCPATH.'uploads\\'.$filePath;
                            unlink($path);
                        }

                    // do upload file
                    $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/spb');
                    $UploadTandaTerima = json_encode($UploadTandaTerima); 
                    $dataSave2['UploadTandaTerima'] = $UploadTandaTerima; 
                }

                $dataSave['JsonStatus'] = json_encode($JsonStatus);
                $dataSave['Status'] = 1;
                $dataSave['Type'] = 'Spb';
                $this->db->where('ID',$ID_payment);
                $this->db->update('db_payment.payment',$dataSave);

                $dataSave2['NoInvoice'] = $Input['NoInvoice'];
                $dataSave2['NoTandaTerima'] = $Input['NoTandaTerima'];
                $dataSave2['Datee'] = $Input['Datee'];
                $dataSave2['Perihal'] = $Input['Perihal'];
                $dataSave2['No_Rekening'] = $Input['No_Rekening'];
                $dataSave2['ID_bank'] = $Input['ID_bank'];
                $dataSave2['Invoice'] = $Input['Invoice'];
                $dataSave2['TypeInvoice'] = $Input['TypeInvoice'];
                $dataSave2['TypeBayar'] = $Input['TypeBayar'];
                $this->db->where('ID_payment',$ID_payment);
                $this->db->update('db_payment.spb',$dataSave2);

                // send to notifikasi ke approval 1
                // Notif to next step approval & User
                    $Code = $G_data[0]['Code'];
                    $NIPApprovalNext = $JsonStatus[1]['NIP'];
                    $CodeUrl = str_replace('/', '-', $Code);
                    // Send Notif for next approval
                        $data = array(
                            'auth' => 's3Cr3T-G4N',
                            'Logging' => array(
                                            'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Created SPB : '.$Code.' after edited',
                                            'Description' => 'Please approve SPB '.$Code.' after edited',
                                            'URLDirect' => 'global/purchasing/transaction/spb/list/'.$CodeUrl,
                                            'CreatedBy' => $this->session->userdata('NIP'),
                                          ),
                            'To' => array(
                                      'NIP' => array($NIPApprovalNext),
                                    ),
                            'Email' => 'No', 
                        );

                        $url = url_pas.'rest2/__send_notif_browser';
                        $token = $this->jwt->encode($data,"UAP)(*");
                        $this->m_master->apiservertoserver($url,$token);

                        // send email is holding or warek keatas
                             $this->m_master->send_email_budgeting_holding($NIPApprovalNext,'NA.4',$data['Logging']['URLDirect'],$data['Logging']['Description']);

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

    public function submitgrpo()
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
                        $this->insert_gr_po();
                        $rs['Status']= 1;
                        break;
                    case 'edit':
                        // check status tidak sama dengan 2
                        $ID_payment = $Input['ID_payment'];
                        $G_data = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
                        // if ($G_data[0]['Status'] != 2) {
                        //     $this->edit_gr_po();
                        //     $rs['Status']= 1;
                        // }
                        // else
                        // {
                        //     $rs['Change']= 1;
                        // }
                         $this->edit_gr_po();
                         $rs['Status']= 1;
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

    public function insert_gr_po()
    {
        $Input = $this->getInputToken();
        /*
            1.Insert payment dengan field Code_po_create,Departement
            2.get  $insert_id = $this->db->insert_id();
            3.Insert good_receipt_spb 
            4.get  $insert_id = $this->db->insert_id();
            5.Insert good_receipt_detail 
            6.Insert payment_circulation_sheet dengan keteranga terima barang 
            7.Update PR Status & PR Status Detail
        */

        $Code_po_create = $Input['Code_po_create'];
        $Departement = $Input['Departement'];
        $dataSave = array(
            'Code_po_create' => $Code_po_create,
            'Departement'=> $Departement,
            'Type'=> 'Spb',
        ); 

        $this->db->insert('db_payment.payment',$dataSave);
        $ID_payment = $this->db->insert_id();

        $FileDocument = $this->m_master->uploadDokumenMultiple(uniqid(),'FileDocument',$path = './uploads/budgeting/grpo');
        $FileDocument = json_encode($FileDocument); 

        $FileTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'FileTandaTerima',$path = './uploads/budgeting/grpo');
        $FileTandaTerima = json_encode($FileTandaTerima); 


        $dataSave = array(
            'ID_payment' => $ID_payment,
            'Date'=> $Input['TglGRPO'],
            'NoDocument'=> $Input['NoDocument'],
            'FileDocument'=> $FileDocument,
            'NoTandaTerima'=> $Input['NoTandaTerima'],
            'FileTandaTerima'=> $FileTandaTerima,
            'CreatedBy'=> $this->session->userdata('NIP'),
            'CreatedAt'=> date('Y-m-d H:i:s'),
        ); 

        $this->db->insert('db_purchasing.good_receipt_spb',$dataSave);
        $ID_good_receipt_spb = $this->db->insert_id();

        $arr_item = $Input['arr_item'];
        $arr_item = json_decode(json_encode($arr_item),true);
        $po_data = $Input['po_data'];
        $po_data = json_decode(json_encode($po_data),true);
        $po_detail = $po_data['po_detail'];

        $PRCode =$po_detail[0]['PRCode'];
        // find ID_pr_status
        $G_data = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
        $ID_pr_status = $G_data[0]['ID'];


        // Desc Item for circulation sheet
        $__arr_item = array();
        for ($i=0; $i < count($arr_item); $i++) { 
            $arr_item[$i]['ID_good_receipt_spb'] = $ID_good_receipt_spb;
            $dataSave = $arr_item[$i];
            $this->db->insert('db_purchasing.good_receipt_detail',$dataSave);
            // update pr_status_detail
            $QtyDiterima = $arr_item[$i]['QtyDiterima'];
            $ID_po_detail = $arr_item[$i]['ID_po_detail'];

            
            // find ID_po_detail untuk QtyPR
            for ($j=0; $j < count($po_detail); $j++) { 
                if ($ID_po_detail == $po_detail[$j]['ID_po_detail']) {
                    $QtyPR = $po_detail[$j]['QtyPR'];
                    $__arr_item[] = $po_detail[$j]['Item'].'('.$QtyDiterima.')';
                    $ID_pr_detail = $po_detail[$j]['ID_pr_detail'];
                    if ($QtyPR == $QtyDiterima) {
                        // check pr_status untuk update
                        $Item_proc = $G_data[0]['Item_proc'];
                        $Item_done = $G_data[0]['Item_done'];
                        $Item_proc = $Item_proc - 1;
                        $Item_done = $Item_done + 1;
                        $Item_pending = $G_data[0]['Item_pending'];
                        $dataSave = array(
                            'Item_proc' => $Item_proc,
                            'Item_done' => $Item_done,
                        );
                        if ($Item_pending == 0 && $Item_proc == 0) {
                            $Status = 2;
                            $dataSave['Status'] = $Status;
                        }

                        $this->db->where('ID',$ID_pr_status);
                        $this->db->update('db_purchasing.pr_status',$dataSave);

                        // update pr_status_detail
                        $dataSave = array(
                            'Status' => 2,
                        );
                        $this->db->where('ID_pr_status',$ID_pr_status);
                        $this->db->where('ID_pr_detail',$ID_pr_detail);
                        $this->db->update('db_purchasing.pr_status_detail',$dataSave);
                    }
                    break;
                }
            }

        }

        $Desc = implode(',', $__arr_item);
        // insert to spb_circulation_sheet
            $this->m_spb->payment_circulation_sheet($ID_payment,'Good Receipt<br>{'.$Desc.'}');
        // insert to po_circulation_sheet
            $this->m_pr_po->po_circulation_sheet($Code_po_create,'Good Receipt<br>{'.$Desc.'}');   
    }

    public function edit_gr_po()
    {
        $Input = $this->getInputToken();
        /*
            1.Dapatkan ID_payment dan ID_good_receipt_spb
            2.Check file upload FileDocument & FileTandaTerima
                jika ada maka hapus file lama
            3.Get data good_receipt_detail berdasarkan ID_good_receipt_spb
            4.Loop good_receipt_detail
                jika ID_po_detail pada good_receipt_detail == ID_po_detail pada po_detail
                dan jika QtyPR pada po_detail == QtyDiterima pada good_receipt_detail maka
                    Kurangi 1 Item Done pada pr_status dan tambah 1 untuk Item_proc
                    check status pr_status dengan  Item_pending dan  Item_proc
                diluar jika QtyPR pada po_detail == QtyDiterima pada good_receipt_detail
                jadikan Status = 1 untuk  pr_status_detail berdasarkan ID_pr_detail dan ID_pr_status 
            5.hapus data good_receipt_detail  berdasarkan ID_good_receipt_spb   
            6.insert payment_circulation_sheet dengan tambahan kata edited  
            7.insert po_circulation_sheet dengan tambahan kata edited  
        */
        // check action2 untuk add or edit    
        if ($Input['action2'] == 'add') {
          $this->addGRPO();
          $rs = array('Status' => 0,'Change' => 0);
          $rs['Status']= 1;
          echo json_encode($rs);
          die();  
        }    

        $Code_po_create = $Input['Code_po_create'];    
        $ID_payment = $Input['ID_payment'];
        $G_good_receipt_spb = $this->m_master->caribasedprimary('db_purchasing.good_receipt_spb','ID_payment',$ID_payment);
        if (count($G_good_receipt_spb) > 0) {
           // $ID_good_receipt_spb = $G_good_receipt_spb[0]['ID'];
           $ID_good_receipt_spb = $Input['ID_good_receipt_spb'];
        }
        else
        {
            $dts = array(
                'ID_payment' => $ID_payment,
                'CreatedBy'=> $this->session->userdata('NIP'),
                'CreatedAt'=> date('Y-m-d H:i:s'),
                );
            $this->db->insert('db_purchasing.good_receipt_spb',$dts);
            $ID_good_receipt_spb = $this->db->insert_id();
        }
        $G_good_receipt_spb = $this->m_master->caribasedprimary('db_purchasing.good_receipt_spb','ID_payment',$ID_payment);

        $dataSave = array(
            'ID_payment' => $ID_payment,
            'Date'=> $Input['TglGRPO'],
            'NoDocument'=> $Input['NoDocument'],
            'NoTandaTerima'=> $Input['NoTandaTerima'],
            'LastUpdatedBy'=> $this->session->userdata('NIP'),
            'LastUpdatedAt'=> date('Y-m-d H:i:s'),
        );

        if (array_key_exists('FileDocument', $_FILES)) {
            // remove old file
                if ($G_good_receipt_spb[0]['FileDocument'] != '' && $G_good_receipt_spb[0]['FileDocument'] != null && empty($G_good_receipt_spb[0]['FileDocument'])) {
                    $arr_file = (array) json_decode($G_good_receipt_spb[0]['FileDocument'],true);
                    $filePath = 'budgeting\\grpo\\'.$arr_file[0]; // pasti ada file karena required
                    $path = FCPATH.'uploads\\'.$filePath;
                    unlink($path);
                }
                

            // do upload file
            $FileDocument = $this->m_master->uploadDokumenMultiple(uniqid(),'FileDocument',$path = './uploads/budgeting/grpo');
            $dataSave['FileDocument'] = json_encode($FileDocument); 
        }

        if (array_key_exists('FileTandaTerima', $_FILES)) {
            // remove old file
                if ($G_good_receipt_spb[0]['FileTandaTerima'] != '' && $G_good_receipt_spb[0]['FileTandaTerima'] != null && empty($G_good_receipt_spb[0]['FileTandaTerima'])) 
                {
                    $arr_file = (array) json_decode($G_good_receipt_spb[0]['FileTandaTerima'],true);
                    $filePath = 'budgeting\\grpo\\'.$arr_file[0]; // pasti ada file karena required
                    $path = FCPATH.'uploads\\'.$filePath;
                    unlink($path);
                }
                
            // do upload file
            $FileTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'FileTandaTerima',$path = './uploads/budgeting/grpo');
            $dataSave['FileTandaTerima'] = json_encode($FileTandaTerima); 
        }

        $this->db->where('ID',$ID_good_receipt_spb);
        $this->db->update('db_purchasing.good_receipt_spb',$dataSave);

        $G_good_receipt_detail = $this->m_master->caribasedprimary('db_purchasing.good_receipt_detail','ID_good_receipt_spb',$ID_good_receipt_spb);
        $po_data = $Input['po_data'];
        $po_data = json_decode(json_encode($po_data),true);
        $po_detail = $po_data['po_detail'];

        $PRCode =$po_detail[0]['PRCode'];
        // find ID_pr_status
        $G_data = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
        $ID_pr_status = $G_data[0]['ID'];

        for ($i=0; $i < count($G_good_receipt_detail); $i++) { 
            $ID_po_detail = $G_good_receipt_detail[$i]['ID_po_detail'];
            $QtyDiterima = $G_good_receipt_detail[$i]['QtyDiterima'];
            for ($j=0; $j < count($po_detail); $j++) { 
                if ($ID_po_detail == $po_detail[$j]['ID_po_detail']) {
                    $QtyPR = $po_detail[$j]['QtyPR'];
                    $ID_pr_detail = $po_detail[$j]['ID_pr_detail'];
                    if ($QtyPR == $QtyDiterima) {
                        $Item_proc = $G_data[0]['Item_proc'];
                        $Item_done = $G_data[0]['Item_done'];
                        $Item_proc = $Item_proc + 1;
                        $Item_done = $Item_done - 1;
                        $Item_pending = $G_data[0]['Item_pending'];

                        $dataSave = array(
                            'Item_proc' => $Item_proc,
                            'Item_done' => $Item_done,
                        );
                        if ($Item_pending == 0 && $Item_proc == 0) {
                            $Status = 2;
                            $dataSave['Status'] = $Status;
                        }
                        else
                        {
                            $Status = 1;
                            $dataSave['Status'] = $Status;
                        }


                        $this->db->where('ID',$ID_pr_status);
                        $this->db->update('db_purchasing.pr_status',$dataSave);


                        // update pr_status_detail
                        $dataSave = array(
                            'Status' => 1,
                        );
                        $this->db->where('ID_pr_status',$ID_pr_status);
                        $this->db->where('ID_pr_detail',$ID_pr_detail);
                        $this->db->update('db_purchasing.pr_status_detail',$dataSave);
                    }
                    break;
                }
            }
        }

        $this->db->where('ID_good_receipt_spb',$ID_good_receipt_spb);
        $this->db->delete('db_purchasing.good_receipt_detail');

        $arr_item = $Input['arr_item'];
        $arr_item = json_decode(json_encode($arr_item),true);

        // find ID_pr_status
        $G_data = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
        $ID_pr_status = $G_data[0]['ID'];

        $__arr_item = array();
        for ($i=0; $i < count($arr_item); $i++) { 
            $arr_item[$i]['ID_good_receipt_spb'] = $ID_good_receipt_spb;
            $dataSave = $arr_item[$i];
            $this->db->insert('db_purchasing.good_receipt_detail',$dataSave);
            // update pr_status_detail
            $QtyDiterima = $arr_item[$i]['QtyDiterima'];
            $ID_po_detail = $arr_item[$i]['ID_po_detail'];

            
            // find ID_po_detail untuk QtyPR
            for ($j=0; $j < count($po_detail); $j++) { 
                if ($ID_po_detail == $po_detail[$j]['ID_po_detail']) {
                    $QtyPR = $po_detail[$j]['QtyPR'];
                    $__arr_item[] = $po_detail[$j]['Item'].'('.$QtyDiterima.')';
                    $ID_pr_detail = $po_detail[$j]['ID_pr_detail'];
                    if ($QtyPR == $QtyDiterima) {
                        // check pr_status untuk update
                        $Item_proc = $G_data[0]['Item_proc'];
                        $Item_done = $G_data[0]['Item_done'];
                        $Item_proc = $Item_proc - 1;
                        $Item_done = $Item_done + 1;
                        $Item_pending = $G_data[0]['Item_pending'];
                        $dataSave = array(
                            'Item_proc' => $Item_proc,
                            'Item_done' => $Item_done,
                        );
                        if ($Item_pending == 0 && $Item_proc == 0) {
                            $Status = 2;
                            $dataSave['Status'] = $Status;
                        }

                        $this->db->where('ID',$ID_pr_status);
                        $this->db->update('db_purchasing.pr_status',$dataSave);

                        // update pr_status_detail
                        $dataSave = array(
                            'Status' => 2,
                        );
                        $this->db->where('ID_pr_status',$ID_pr_status);
                        $this->db->where('ID_pr_detail',$ID_pr_detail);
                        $this->db->update('db_purchasing.pr_status_detail',$dataSave);
                    }
                    break;
                }
            }

        }

        $Desc = implode(',', $__arr_item);

        // insert to spb_circulation_sheet
            $this->m_spb->payment_circulation_sheet($ID_payment,'Good Receipt Edited<br>{'.$Desc.'}');
        // insert to po_circulation_sheet
            $this->m_pr_po->po_circulation_sheet($Code_po_create,'Good Receipt Edited<br>{'.$Desc.'}');
    }

    public function addGRPO()
    {
        $Input = $this->getInputToken();
        $Code_po_create = $Input['Code_po_create'];    
        $ID_payment = $Input['ID_payment'];

        $FileDocument = $this->m_master->uploadDokumenMultiple(uniqid(),'FileDocument',$path = './uploads/budgeting/grpo');
        $FileDocument = json_encode($FileDocument); 

        $FileTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'FileTandaTerima',$path = './uploads/budgeting/grpo');
        $FileTandaTerima = json_encode($FileTandaTerima); 

        $dataSave = array(
            'ID_payment' => $ID_payment,
            'Date'=> $Input['TglGRPO'],
            'NoDocument'=> $Input['NoDocument'],
            'FileDocument'=> $FileDocument,
            'NoTandaTerima'=> $Input['NoTandaTerima'],
            'FileTandaTerima'=> $FileTandaTerima,
            'CreatedBy'=> $this->session->userdata('NIP'),
            'CreatedAt'=> date('Y-m-d H:i:s'),
        ); 

        $this->db->insert('db_purchasing.good_receipt_spb',$dataSave);
        $ID_good_receipt_spb = $this->db->insert_id();

        $arr_item = $Input['arr_item'];
        $arr_item = json_decode(json_encode($arr_item),true);
        $po_data = $Input['po_data'];
        $po_data = json_decode(json_encode($po_data),true);
        $po_detail = $po_data['po_detail'];

        $PRCode =$po_detail[0]['PRCode'];
        // find ID_pr_status
        $G_data = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
        $ID_pr_status = $G_data[0]['ID'];


        // Desc Item for circulation sheet
        $__arr_item = array();
        for ($i=0; $i < count($arr_item); $i++) { 
            $arr_item[$i]['ID_good_receipt_spb'] = $ID_good_receipt_spb;
            $dataSave = $arr_item[$i];
            $this->db->insert('db_purchasing.good_receipt_detail',$dataSave);
            // update pr_status_detail
            $QtyDiterima = $arr_item[$i]['QtyDiterima'];
            $ID_po_detail = $arr_item[$i]['ID_po_detail'];

            
            // find ID_po_detail untuk QtyPR
            for ($j=0; $j < count($po_detail); $j++) { 
                if ($ID_po_detail == $po_detail[$j]['ID_po_detail']) {
                    $QtyPR = $po_detail[$j]['QtyPR'];
                    $__arr_item[] = $po_detail[$j]['Item'].'('.$QtyDiterima.')';
                    $ID_pr_detail = $po_detail[$j]['ID_pr_detail'];
                    if ($QtyPR == $QtyDiterima) {
                        // check pr_status untuk update
                        $Item_proc = $G_data[0]['Item_proc'];
                        $Item_done = $G_data[0]['Item_done'];
                        $Item_proc = $Item_proc - 1;
                        $Item_done = $Item_done + 1;
                        $Item_pending = $G_data[0]['Item_pending'];
                        $dataSave = array(
                            'Item_proc' => $Item_proc,
                            'Item_done' => $Item_done,
                        );
                        if ($Item_pending == 0 && $Item_proc == 0) {
                            $Status = 2;
                            $dataSave['Status'] = $Status;
                        }

                        $this->db->where('ID',$ID_pr_status);
                        $this->db->update('db_purchasing.pr_status',$dataSave);

                        // update pr_status_detail
                        $dataSave = array(
                            'Status' => 2,
                        );
                        $this->db->where('ID_pr_status',$ID_pr_status);
                        $this->db->where('ID_pr_detail',$ID_pr_detail);
                        $this->db->update('db_purchasing.pr_status_detail',$dataSave);
                    }
                    break;
                }
            }

        }

        $Desc = implode(',', $__arr_item);
        // insert to spb_circulation_sheet
            $this->m_spb->payment_circulation_sheet($ID_payment,'Good Receipt added<br>{'.$Desc.'}');
        // insert to po_circulation_sheet
            $this->m_pr_po->po_circulation_sheet($Code_po_create,'Good Receipt added<br>{'.$Desc.'}');
    }

    public function submit_spb_user()
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

        // get template
        $ID_template = $this->input->post('ID_template');
        $key = "UAP)(*";
        $ID_template = $this->jwt->decode($ID_template,$key);

        $dataInput = $this->input->post('dataInput');
        $key = "UAP)(*";
        $dataInput = $this->jwt->decode($dataInput,$key);
        $dataInput = (array)  json_decode(json_encode($dataInput),true);

        $StatusPayment = '';

        // adding Supporting_documents
            $Supporting_documents = array();
            $Supporting_documents = json_encode($Supporting_documents); 
            $UploadInvoice = array();
            $UploadInvoice = json_encode($UploadInvoice); 
            $UploadTandaTerima = array();
            $UploadTandaTerima = json_encode($UploadTandaTerima); 
           
            if (array_key_exists('Supporting_documents', $_FILES)) {
                // do upload file
                $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'Supporting_documents',$path = './uploads/budgeting/spb');
                $Supporting_documents = json_encode($uploadFile); 
            }

            if (array_key_exists('UploadInvoice', $_FILES)) {
                // do upload file
                $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/spb');
                $UploadInvoice = json_encode($uploadFile); 
            }

            if (array_key_exists('UploadTandaTerima', $_FILES)) {
                // do upload file
                $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/spb');
                $UploadTandaTerima = json_encode($uploadFile); 
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
                if ($BoolBudget) {
                    $Code = $this->m_spb->Get_SPBCode($Departement);
                    $dataSave = array(
                        'Code' => $Code,
                        'Type' => 'Spb',
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

                     // insert ke table spb
                     $dataSave = array(
                         'ID_payment' => $ID_payment,
                         'CodeSupplier' => $dataInput['CodeSupplier'],
                         'UploadInvoice' => $UploadInvoice ,
                         'NoInvoice' => $dataInput['NoInvoice'] ,
                         'UploadTandaTerima' => $UploadTandaTerima,
                         'NoTandaTerima' => $dataInput['NoTandaTerima'] ,
                         'Datee' => $dataInput['Datee'],
                         'Perihal' => $dataInput['Perihal'],
                         'No_Rekening' => $dataInput['No_Rekening'],
                         'ID_bank' => $dataInput['ID_bank'],
                         'Invoice' => $dataInput['Invoice'],
                         'TypeInvoice' => $dataInput['TypeInvoice'],
                         'TypeBayar' => $dataInput['TypeBayar'],
                     );
                     $this->db->insert('db_payment.spb',$dataSave);
                     $ID_spb = $this->db->insert_id();

                     for ($i=0; $i < count($input); $i++) {
                         $data = $input[$i]; 
                         $key = "UAP)(*";
                         $data_arr = (array) $this->jwt->decode($data,$key);
                         $PassNumber = $data_arr['PassNumber'];
                             $dataSave = array(
                                 'ID_spb' =>$ID_spb,
                                 'ID_budget_left' => $data_arr['ID_budget_left'],
                                 'NamaBiaya' => $data_arr['NamaBiaya'],
                                 'Invoice' => $data_arr['SubTotal'],
                             ); 
                             $this->db->insert('db_payment.spb_detail',$dataSave);
                     }

                     // Update to budget_left
                         $this->m_pr_po->Update_budget_left_pr($BudgetLeft_awal,$BudgetRemaining,$input);

                     // insert to spb_circulation_sheet
                         $this->m_spb->payment_circulation_sheet($ID_payment,'Input SPB');

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
                                             'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> SPB has been Created by '.$Code,
                                             'Description' => 'SPB has been Created by '.$Code.'('.$this->session->userdata('Name').')',
                                             'URLDirect' => 'budgeting_menu/pembayaran/spb/'.$CodeUrl,
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

        // get template
        $ID_template = $this->input->post('ID_template');
        $key = "UAP)(*";
        $ID_template = $this->jwt->decode($ID_template,$key);

        $dataInput = $this->input->post('dataInput');
        $key = "UAP)(*";
        $dataInput = $this->jwt->decode($dataInput,$key);
        $dataInput = (array)  json_decode(json_encode($dataInput),true);

        $StatusPayment = '';

        $dataSave = array(
            'Status' => 1,
        );
        $G_data = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);

        if (array_key_exists('Supporting_documents', $_FILES)) {
            // do upload file
            $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'Supporting_documents',$path = './uploads/budgeting/spb');
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
        }

        if (count($JsonStatus2) > 1) {
            $BoolBudget = $this->m_pr_po->checkBudgetClientToServer_edit($BudgetLeft_awal,$BudgetRemaining);
            if ($BoolBudget) {
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

                 // update ke table spb
                // get data spb
                    $G_data_ = $this->m_master->caribasedprimary('db_payment.spb','ID_payment',$ID_payment);
                 $dataSave = array(
                     'ID_payment' => $ID_payment,
                     'CodeSupplier' => $dataInput['CodeSupplier'],
                     'NoInvoice' => $dataInput['NoInvoice'] ,
                     'NoTandaTerima' => $dataInput['NoTandaTerima'] ,
                     'Datee' => $dataInput['Datee'],
                     'Perihal' => $dataInput['Perihal'],
                     'No_Rekening' => $dataInput['No_Rekening'],
                     'ID_bank' => $dataInput['ID_bank'],
                     'Invoice' => $dataInput['Invoice'],
                     'TypeInvoice' => $dataInput['TypeInvoice'],
                     'TypeBayar' => $dataInput['TypeBayar'],
                 );

                 // delete old file and upload new file if user do upload
                 if (array_key_exists('UploadInvoice', $_FILES)) {
                     // remove old file
                     if ($G_data_[0]['UploadInvoice'] != '' && $G_data_[0]['UploadInvoice'] != null) {
                         $arr_file = (array) json_decode($G_data_[0]['UploadInvoice'],true);
                         $filePath = 'budgeting\\spb\\'.$arr_file[0]; // pasti ada file karena required
                         $path = FCPATH.'uploads\\'.$filePath;
                         unlink($path);
                     }

                     // do upload file
                     $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/spb');
                     $UploadInvoice = json_encode($UploadInvoice); 
                     $dataSave['UploadInvoice'] = $UploadInvoice; 
                 }

                 if (array_key_exists('UploadTandaTerima', $_FILES)) {
                     // remove old file
                         if ($G_data_[0]['UploadTandaTerima'] != '' && $G_data_[0]['UploadTandaTerima'] != null) {
                             $arr_file = (array) json_decode($G_data_[0]['UploadTandaTerima'],true);
                             $filePath = 'budgeting\\spb\\'.$arr_file[0]; // pasti ada file karena required
                             $path = FCPATH.'uploads\\'.$filePath;
                             unlink($path);
                         }

                     // do upload file
                     $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/spb');
                     $UploadTandaTerima = json_encode($UploadTandaTerima); 
                     $dataSave['UploadTandaTerima'] = $UploadTandaTerima; 
                 }

                 $this->db->where('ID_payment',$ID_payment);
                 $this->db->update('db_payment.spb',$dataSave);
                 $G_dt_spb = $this->m_master->caribasedprimary('db_payment.spb','ID_payment',$ID_payment);
                 $ID_spb = $G_dt_spb[0]['ID'];

                 if ($this->db->affected_rows() > 0 )
                 {
                    /*
                        Note : 
                        Pengembalian Post Budget using ke awal sebelum pr tercreate
                    */
                     $BackBudgetToBeforeCreate = $this->m_global->BackBudgetToBeforeCreate($ID_payment,$Year,$Departement);

                     // $G_detail = $this->m_master->caribasedprimary('db_payment.spb_detail','ID_spb',$ID_spb);
                     $this->db->where(array('ID_spb' => $ID_spb));
                     $this->db->delete('db_payment.spb_detail');

                     for ($i=0; $i < count($input); $i++) {
                         $data = $input[$i]; 
                         $key = "UAP)(*";
                         $data_arr = (array) $this->jwt->decode($data,$key);
                         $PassNumber = $data_arr['PassNumber'];
                             $dataSave = array(
                                 'ID_spb' =>$ID_spb,
                                 'ID_budget_left' => $data_arr['ID_budget_left'],
                                 'NamaBiaya' => $data_arr['NamaBiaya'],
                                 'Invoice' => $data_arr['SubTotal'],
                             ); 
                             $this->db->insert('db_payment.spb_detail',$dataSave);
                     }

                     // Update to budget_left
                         $this->m_pr_po->Update_budget_left_pr($BudgetLeft_awal,$BudgetRemaining,$input);

                     // insert to spb_circulation_sheet
                         $this->m_spb->payment_circulation_sheet($ID_payment,'Edit SPB');

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
                                                 'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> SPB has been Revised by '.$Code,
                                                 'Description' => 'SPB has been Revised by '.$Code.'('.$this->session->userdata('Name').')',
                                                 'URLDirect' => 'budgeting_menu/pembayaran/spb/'.$CodeUrl,
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

}
