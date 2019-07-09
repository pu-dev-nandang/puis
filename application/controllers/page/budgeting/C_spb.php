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
                        $ID_spb_created = $Input['ID_spb_created'];
                        $G_spb_created = $this->m_master->caribasedprimary('db_purchasing.spb_created','ID',$ID_spb_created);
                        if ($G_spb_created[0]['Status'] != 2) {
                            $this->edit_spb();
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

    public function insert_spb()
    {
        $Input = $this->getInputToken();
        unset($Input['action']);
        $Code_po_create = $Input['Code_po_create'];
        $Departement = $Input['Departement'];
        // get Approval
        $token3 = $this->input->post('token3');
        $Amount = $Input['Invoice'];
        $JsonStatus =  $this->m_pr_po->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$token3);
        if (count($JsonStatus) > 1) {
            $dataSave = $Input;
            $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/po');
            $UploadInvoice = json_encode($UploadInvoice); 

            $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/po');
            $UploadTandaTerima = json_encode($UploadTandaTerima);   

            $dataSave['Code'] = $this->m_spb->Get_SPBCode($Departement);
            $dataSave['Status'] = 1;
            $dataSave['UploadInvoice'] = $UploadInvoice;
            $dataSave['UploadTandaTerima'] = $UploadTandaTerima;
            $dataSave['JsonStatus'] = json_encode($JsonStatus);
            $dataSave['CreatedBy'] = $this->session->userdata('NIP');
            $dataSave['CreatedAt'] = date('Y-m-d H:i:s');
            $this->db->insert('db_purchasing.spb_created',$dataSave);
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
        $ID_spb_created = $Input['ID_spb_created'];
        /*
            jika approval satu telah approve maka tidak boleh melakukan edit lagi
        */
        $G_data = $this->m_master->caribasedprimary('db_purchasing.spb_created','ID',$ID_spb_created);
        $JsonStatus_existing = $G_data[0]['JsonStatus'];
        $JsonStatus_existing = json_decode($JsonStatus_existing,true);
        $bool = true;

        if (count($JsonStatus_existing) > 0) {
            for ($i=1; $i < count($JsonStatus_existing); $i++) { 
                if ($JsonStatus_existing[$i]['Status'] == 1) {
                    $bool = false;
                    break;
                }
            }
        }

        if ($bool) {
            unset($Input['ID_spb_created']);
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
                $dataSave = $Input;
                // get code if null || empty
                // $dataSave['Code'] = $this->m_spb->Get_SPBCode($Departement);
                if ($G_data[0]['Code'] =='' || $G_data[0]['Code'] == null) {
                    $dataSave['Code'] = $this->m_spb->Get_SPBCode($Departement);
                    $dataSave['CreatedBy'] = $this->session->userdata('NIP');
                    $dataSave['CreatedAt'] = date('Y-m-d H:i:s');
                }else
                {
                    $dataSave['LastUpdatedBy'] = $this->session->userdata('NIP');
                    $dataSave['LastUpdatedAt'] = date('Y-m-d H:i:s');
                }

                // delete old file and upload new file if user do upload
                if (array_key_exists('UploadInvoice', $_FILES)) {
                    // remove old file
                    if ($G_data[0]['UploadInvoice'] != '' && $G_data[0]['UploadInvoice'] != null) {
                        $arr_file = (array) json_decode($G_data[0]['UploadInvoice'],true);
                        $filePath = 'budgeting\\po\\'.$arr_file[0]; // pasti ada file karena required
                        $path = FCPATH.'uploads\\'.$filePath;
                        unlink($path);
                    }

                    // do upload file
                    $UploadInvoice = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadInvoice',$path = './uploads/budgeting/po');
                    $UploadInvoice = json_encode($UploadInvoice); 
                    $dataSave['UploadInvoice'] = $UploadInvoice; 
                }

                if (array_key_exists('UploadTandaTerima', $_FILES)) {
                    // remove old file
                        if ($G_data[0]['UploadTandaTerima'] != '' && $G_data[0]['UploadTandaTerima'] != null) {
                            $arr_file = (array) json_decode($G_data[0]['UploadTandaTerima'],true);
                            $filePath = 'budgeting\\po\\'.$arr_file[0]; // pasti ada file karena required
                            $path = FCPATH.'uploads\\'.$filePath;
                            unlink($path);
                        }

                    // do upload file
                    $UploadTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadTandaTerima',$path = './uploads/budgeting/po');
                    $UploadTandaTerima = json_encode($UploadTandaTerima); 
                    $dataSave['UploadTandaTerima'] = $UploadTandaTerima; 
                }

                $dataSave['JsonStatus'] = json_encode($JsonStatus);
                $dataSave['Status'] = 1;
                $this->db->where('ID',$ID_spb_created);
                $this->db->update('db_purchasing.spb_created',$dataSave);
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
                        $ID_spb_created = $Input['ID_spb_created'];
                        $G_spb_created = $this->m_master->caribasedprimary('db_purchasing.spb_created','ID',$ID_spb_created);
                        if ($G_spb_created[0]['Status'] != 2) {
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
            1.Insert spb_created dengan field Code_po_create,Departement
            2.get  $insert_id = $this->db->insert_id();
            3.Insert good_receipt_spb 
            4.get  $insert_id = $this->db->insert_id();
            5.Insert good_receipt_detail 
            6.Insert spb_circulation_sheet dengan keteranga terima barang 
            7.Update PR Status & PR Status Detail
        */

        $Code_po_create = $Input['Code_po_create'];
        $Departement = $Input['Departement'];
        $dataSave = array(
            'Code_po_create' => $Code_po_create,
            'Departement'=> $Departement,
        ); 

        $this->db->insert('db_purchasing.spb_created',$dataSave);
        $ID_spb_created = $this->db->insert_id();

        $FileDocument = $this->m_master->uploadDokumenMultiple(uniqid(),'FileDocument',$path = './uploads/budgeting/po');
        $FileDocument = json_encode($FileDocument); 

        $FileTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'FileTandaTerima',$path = './uploads/budgeting/po');
        $FileTandaTerima = json_encode($FileTandaTerima); 


        $dataSave = array(
            'ID_spb_created' => $ID_spb_created,
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
            $this->m_spb->spb_grpo_circulation_sheet(null,'Good Receipt<br>{'.$Desc.'}');
        // insert to po_circulation_sheet
            $this->m_pr_po->po_circulation_sheet($Code_po_create,'Good Receipt<br>{'.$Desc.'}');   

    }

    public function edit_gr_po()
    {
        $Input = $this->getInputToken();
        /*
            1.Dapatkan ID_spb_created dan ID_good_receipt_spb
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
            6.insert spb_grpo_circulation_sheet dengan tambahan kata edited  
            7.insert po_circulation_sheet dengan tambahan kata edited  
        */
        $Code_po_create = $Input['Code_po_create'];    
        $ID_spb_created = $Input['ID_spb_created'];
        $G_good_receipt_spb = $this->m_master->caribasedprimary('db_purchasing.good_receipt_spb','ID_spb_created',$ID_spb_created);
        $ID_good_receipt_spb = $G_good_receipt_spb[0]['ID'];

        $dataSave = array(
            'ID_spb_created' => $ID_spb_created,
            'Date'=> $Input['TglGRPO'],
            'NoDocument'=> $Input['NoDocument'],
            'NoTandaTerima'=> $Input['NoTandaTerima'],
            'LastUpdatedBy'=> $this->session->userdata('NIP'),
            'LastUpdatedAt'=> date('Y-m-d H:i:s'),
        ); 
        if (array_key_exists('FileDocument', $_FILES)) {
            // remove old file
                $arr_file = (array) json_decode($G_good_receipt_spb[0]['FileDocument'],true);
                $filePath = 'budgeting\\po\\'.$arr_file[0]; // pasti ada file karena required
                $path = FCPATH.'uploads\\'.$filePath;
                unlink($path);

            // do upload file
            $FileDocument = $this->m_master->uploadDokumenMultiple(uniqid(),'FileDocument',$path = './uploads/budgeting/po');
            $dataSave['FileDocument'] = json_encode($FileDocument); 
        }

        if (array_key_exists('FileTandaTerima', $_FILES)) {
            // remove old file
                $arr_file = (array) json_decode($G_good_receipt_spb[0]['FileTandaTerima'],true);
                $filePath = 'budgeting\\po\\'.$arr_file[0]; // pasti ada file karena required
                $path = FCPATH.'uploads\\'.$filePath;
                unlink($path);

            // do upload file
            $FileTandaTerima = $this->m_master->uploadDokumenMultiple(uniqid(),'FileTandaTerima',$path = './uploads/budgeting/po');
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
            $this->m_spb->spb_grpo_circulation_sheet(null,'Good Receipt Edited<br>{'.$Desc.'}');
        // insert to po_circulation_sheet
            $this->m_pr_po->po_circulation_sheet($Code_po_create,'Good Receipt Edited<br>{'.$Desc.'}');
    }

}
