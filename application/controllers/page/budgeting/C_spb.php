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
        $this->load->model('budgeting/m_pr_po');
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

    public function submitgrpo()
    {
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
        // insert to pr_circulation_sheet
            $this->m_spb->spb_grpo_circulation_sheet(null,'Good Receipt<br>{'.$Desc.'}');
        // insert to po_circulation_sheet
            $this->m_pr_po->po_circulation_sheet($Code_po_create,'Good Receipt<br>{'.$Desc.'}');   

    }

}
