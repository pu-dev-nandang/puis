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
        $this->data['G_Approver'] = $this->m_pr_po->Get_m_Approver();
        $this->data['m_type_user'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');    
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
                                $this->insert_spb();
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
            );

            $this->db->insert('db_payment.spb',$dataSave2);

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
                $this->db->where('ID_payment',$ID_payment);
                $this->db->update('db_payment.spb',$dataSave2);

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
                        if ($G_data[0]['Status'] != 2) {
                            $this->edit_gr_po();
                            $rs['Status']= 1;
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
        $Code_po_create = $Input['Code_po_create'];    
        $ID_payment = $Input['ID_payment'];
        $G_good_receipt_spb = $this->m_master->caribasedprimary('db_purchasing.good_receipt_spb','ID_payment',$ID_payment);
        if (count($G_good_receipt_spb) > 0) {
           $ID_good_receipt_spb = $G_good_receipt_spb[0]['ID'];
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

}
