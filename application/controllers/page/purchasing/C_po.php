<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_po extends Transaksi_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement(); 
        $this->load->model('m_api');
        $this->load->model('budgeting/m_budgeting');
        $this->load->model('budgeting/m_pr_po');
        $this->load->model('master/m_master');
    }

    public function index()
    {
        $this->data['G_Approver'] = $this->m_pr_po->Get_m_Approver_po();
        $this->data['m_type_user'] = $this->m_master->showData_array('db_purchasing.cfg_m_type_approval');
        $page['content'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/list',$this->data,true);
        $this->page_po($page);
    }

    public function open()
    {
       $this->data['action_mode'] = 'add';
       $this->data['POCode'] = '';
       $page['content'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/open',$this->data,true);
       $this->page_po($page); 
    }

    public function configuration()
    {
       $page['content'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/configuration',$this->data,true);
       $this->page_po($page); 
    }

    public function set_rad()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        // pass check data existing
        $this->data['dt'] = $this->m_master->showData_array('db_purchasing.cfg_set_userrole');
        $this->data['cfg_m_userrole'] = $this->m_master->showData_array('db_purchasing.cfg_m_userrole');
        $content = $this->load->view('page/'.$this->data['department'].'/transaksi/po/config/set_rad',$this->data,true);
        $arr_result['html'] = $content;
        echo json_encode($arr_result);
    }

    public function userroledepart_submit()
    {
       $this->auth_ajax();
       $Msg = '';
       try {
        $Input = $this->getInputToken();
        $dataSave = array();
        if (count($Input) > 0) {
            $table = 'db_purchasing.cfg_set_userrole';
            $sql = "TRUNCATE TABLE ".$table;
            $query=$this->db->query($sql, array());
            foreach ($Input as $key) {
                $temp = array();
                foreach ($key as $keya => $value) {
                   $temp[$keya] = $value; 
                }
                $dataSave[] = $temp;
            }
            $this->db->insert_batch('db_purchasing.cfg_set_userrole', $dataSave);  
        }
        else
        {
            $Msg = 'No data action';
        }

       } catch (Exception $e) {
            $Msg = $this->Msg['Error'];
       }

       echo json_encode($Msg);
       
    }

    public function Set_Approval()
    {
        $this->auth_ajax();
       $arr_result = array('html' => '','jsonPass' => '');
       $this->data['cfg_m_type_approval'] = $this->m_master->showData_array('db_purchasing.cfg_m_type_approval');
       $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/config/Set_Approval',$this->data,true);
       echo json_encode($arr_result);
    }

    public function get_cfg_set_roleuser_po($Departement)
    {
        $this->auth_ajax();
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser,c.Visible,c.TypeDesc
                from db_purchasing.cfg_m_userrole as a left join (select * from db_purchasing.cfg_approval where Departement = ? ) as c
                on a.ID = c.ID_m_userrole
                left join db_employees.employees as b on b.NIP = c.NIP 
                order by a.ID asc
                ';
        $query=$this->db->query($sql, array($Departement))->result_array();
        echo json_encode($query);
    }

    public function save_cfg_set_roleuser_po()
    {
        $this->auth_ajax();
        $msg = array('status' => 0,'msg' => '');
        $Input = $this->getInputToken();
        $Action = $Input['Action'];
        switch ($Action) {
            case "":
                $dt = $Input['dt'];
                $dt = (array) json_decode(json_encode($dt),true);
                for ($i=0; $i < count($dt); $i++) { 
                    $FormInsert = $dt[$i]['FormInsert'];
                    // check NIM already exist in employees
                    $NIP = $FormInsert['NIP'];
                    $G = $this->m_master->caribasedprimary('db_employees.employees','NIP',$NIP);
                    if (count($G) == 0) {
                        $msg['msg'] = 'NIP : '.$NIP.' is not already exist';   
                        break;
                    }
                    $Method = $dt[$i]['Method'];
                    $subAction = $Method['Action'];
                    if ($subAction == 'add') {
                        $this->db->insert('db_purchasing.cfg_approval',$FormInsert);
                    }
                    else
                    {
                        $ID = $Method['ID'];
                        $this->db->where('ID', $ID);
                        $this->db->update('db_purchasing.cfg_approval', $FormInsert);
                    }
                }
                if ($msg['msg'] == '') {
                   $msg['status'] = 1;
                }
                break;
            case "delete":
                $ID = $Input['ID_set_roleuser'];
                $this->m_master->delete_id_table_all_db($ID,'db_purchasing.cfg_approval');
                $msg['status'] = 1;
                break;
            default:
                # code...
                break;
        }
        

        echo json_encode($msg);
    }

    public function submit_create_po_spk()
    {
        $action_submit = $this->input->post('action_submit');
        $key = "UAP)(*";
        $action_submit = $this->jwt->decode($action_submit,$key);
        switch ($action_submit) {
            case 'SPK':
                $this->submit_spk();
                break;
            case 'PO':
                $this->submit_po();
                break;    
            
            default:
                echo '{"status":"999","message":"Not Authorize"}'; 
                break;
        }
        /*arr_pr_detail
        arr_supplier
        action_mode
        action_submit
        */
    }

    public function submit_po()
    {
        $action_mode = $this->input->post('action_mode');
        $key = "UAP)(*";
        $action_mode = $this->jwt->decode($action_mode,$key);

        switch ($action_mode) {
            case 'add':
                $this->insert_submit_po();
                break;
            case 'edit':
                $this->edit_submit_po();
                break;
            default:
                echo '{"status":"999","message":"Not Authorize"}'; 
                break;
        }
    }

    public function insert_submit_po()
    {
        $arr_rs = array('status' => '1','message' => '','url'=> '','Code');
        $arr_pr_detail = $this->input->post('arr_pr_detail');
        $key = "UAP)(*";
        $arr_pr_detail = $this->jwt->decode($arr_pr_detail,$key);
        $arr_supplier = $this->input->post('arr_supplier');
        $key = "UAP)(*";
        $arr_supplier = $this->jwt->decode($arr_supplier,$key);
        $arr_supplier = (array) json_decode(json_encode($arr_supplier),true);

        /*
            1.insert to db_purchasing.pre_po
            2.get last insert id
            3.insert to db_purchasing.pre_po_detail
            4.insert to db_purchasing_pre_po_supplier
            5.insert to db_purchasing.po_create && get code
            6.insert to db_purchasing.po_detail
            7.insert to db_purchasing.pr_status
            8.insert to db_purchasing.pr_status_detail

        */

         // 1
            $dataSave = array(
                'CreatedBy' => $this->session->userdata('NIP'),
                'CreatedAt' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('db_purchasing.pre_po',$dataSave);
            $ID_pre_po = $this->db->insert_id();

         // 3
            $Amount = 0;
            $dt_po_detail = array();
            $Code = $this->m_pr_po->Get_POCode(); 
            $arr_pr_code = array();
            for ($i=0; $i < count($arr_pr_detail); $i++) { 
                $dataSave = array(
                    'ID_pre_po' => $ID_pre_po,
                    'ID_pr_detail' => $arr_pr_detail[$i],
                );

                $this->db->insert('db_purchasing.pre_po_detail',$dataSave);
                $ID_pre_po_detail = $this->db->insert_id();
                // get amount dari ID_pr_detail
                $G = $this->m_master->caribasedprimary('db_budgeting.pr_detail','ID',$arr_pr_detail[$i]);
                $Amount = $Amount +$G[0]['SubTotal'];

                $temp = array(
                    'Code' => $Code,
                    'ID_pre_po_detail' => $ID_pre_po_detail,
                    'UnitCost' => $G[0]['UnitCost'],
                    'Discount' => 0,
                    'PPN' => $G[0]['PPH'],
                    'SubTotal' => $G[0]['SubTotal'],
                );
                $dt_po_detail[] = $temp;

                // adding PR Code
                if (count($arr_pr_code) == 0) {
                    $temp = array(
                        'PRCode' => $G[0]['PRCode'],
                        'Count' => 1,
                    );

                    $arr_pr_code[] = $temp;
                }
                else
                {
                    $bool= true;
                    for ($i=0; $i < count($arr_pr_code); $i++) { 
                        if ($arr_pr_code[$i]['PRCode'] == $G[0]['PRCode']) {
                            $arr_pr_code[$i]['Count'] = $arr_pr_code[$i]['Count'] + 1;
                            $bool = false;
                            break;
                        }
                    }

                    if ($bool) {
                       $temp = array(
                           'PRCode' => $G[0]['PRCode'],
                           'Count' => 1,
                       );

                       $arr_pr_code[] = $temp;
                    }
                }
            }

          // 4
            $ID_pre_po_supplier = '';
            for ($i=0; $i < count($arr_supplier); $i++) { 
                $dataSave = $arr_supplier[$i];
                // do upload file
                $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadFile'.$i,$path = './uploads/budgeting/po');
                $dataSave['FileOffer'] = json_encode($uploadFile); 
                $dataSave['ID_pre_po'] = $ID_pre_po;
                $this->db->insert('db_purchasing.pre_po_supplier',$dataSave);
                $LastInserID = $this->db->insert_id();
                if ($dataSave['Approve'] == 1) {
                    $ID_pre_po_supplier = $LastInserID;
                }
            }

          // 5 PO & 6
            $JsonStatus = $this->m_pr_po->GetRuleApproval_PO_JsonStatus($Amount);
            $dataSave = array(
                'ID_pre_po' => $ID_pre_po,
                'TypeCreate' => 1,
                'Code' => $Code,
                'ID_pre_po_supplier' => $ID_pre_po_supplier,
                'AnotherCost' => 0,
                'JsonStatus' => json_encode($JsonStatus),
                'Status' => 0,
                'Notes' => '',
                'Supporting_documents' => json_encode($arr = array()),
                'CreatedBy' => $this->session->userdata('NIP'),
                'CreatedAt' => date('Y-m-d H:i:s'), 
            );

            $this->db->insert('db_purchasing.po_create',$dataSave);

            for ($i=0; $i < count($dt_po_detail); $i++) { 
                $dataSave = $dt_po_detail[$i];
                $this->db->insert('db_purchasing.po_detail',$dataSave);
            }

         // 7 PO & 8
            /* pr_status => Pengurangan dari Item_pending ke Item_proc($arr_pr_code)  & Status = 1
               pr_status_detail => $arr_pr_detail get ID_pr_detail untuk perubahan status dari 0 => 1 
            */  
              // 7 
              for ($i=0; $i < count($arr_pr_code); $i++) {
                   $PRCode = $arr_pr_code[$i]['PRCode'];
                   $G_data = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
                   $Item_pending = $G_data[0]['Item_pending'];
                   $Item_pending = $Item_pending - $arr_pr_code[$i]['Count'];
                   $Item_proc = $G_data[0]['Item_proc'];
                   $Item_proc = $Item_proc  + $arr_pr_code[$i]['Count'];

                   $dataSave = array(
                    'Item_pending' => $Item_pending,
                    'Item_proc' => $Item_proc,
                   );

                   $this->db->where('ID',$G_data[0]['ID']);
                   $this->db->update('db_purchasing.pr_status',$dataSave);
               }

               // 8
               for ($i=0; $i < count($arr_pr_detail); $i++) { 
                   $ID_pr_detail = $arr_pr_detail[$i];
                   $dataSave = array(
                    'Status' => 1,
                   );
                   $this->db->where('ID_pr_detail',$ID_pr_detail);
                   $this->db->update('db_purchasing.pr_status_detail',$dataSave); 
               } 
              

            $urlCode = str_replace('/', '-', $Code);
            $arr_rs['url'] = 'purchasing/transaction/po/list/'.$urlCode;
            $arr_rs['Code'] = $Code;

            // insert to pr_circulation_sheet
                $this->m_pr_po->po_circulation_sheet($Code,'PO Created');

        echo json_encode($arr_rs);    
    }

    public function submit_spk()
    {

    }

}
