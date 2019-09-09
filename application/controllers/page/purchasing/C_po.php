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
        if (empty($_GET)) {
           $this->data['action_mode'] = 'add';
           $this->data['POCode'] = '';
        }
        else{
            try {
                $token = $_GET['POCode'];
                $key = "UAP)(*";
                $POCode =$this->jwt->decode($token,$key);
                $Code = str_replace('-','/', $POCode);
                $G_data = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$Code);
                if (count($G_data) > 0) {
                    // cek status dari PO
                        /*
                            allow status array(0,1,-1,4)
                        */
                        if (in_array($G_data[0]['Status'], array(0,1,-1,4)) ) {
                              $bool = true;
                             // special untuk status = 1, trigger belum di lakukan proses approval oleh approver
                             if ($G_data[0]['Status'] == 1) {
                                  $JsonStatus = json_decode($G_data[0]['JsonStatus'],true) ;
                                  for ($i=1; $i < count($JsonStatus) ; $i++) { 
                                     if ($JsonStatus[$i]['Status'] == 1) {
                                         $bool = false;
                                         break;
                                     }
                                  }  
                              } 
                              
                              if ($bool) {
                                  $this->data['action_mode'] = 'edit';
                                  $this->data['POCode'] = $Code;
                              }
                              else
                              {
                                show_404($log_error = TRUE);
                              }
                             
                        }
                        else{
                            show_404($log_error = TRUE); 
                        }    
                    
                }
                else
                {
                    show_404($log_error = TRUE); 
                }
            }
            //catch exception
            catch(Exception $e) {
                 show_404($log_error = TRUE); 
            }
            
        } 
       
       $page['content'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/open',$this->data,true);
       $this->page_po($page); 
    }


    public function cancel_reject_pr()
    {
       $page['content'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/cancel_reject_pr',$this->data,true);
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

    public function Set_Approval_SPK()
    {
        $this->auth_ajax();
       $arr_result = array('html' => '','jsonPass' => '');
       $this->data['cfg_m_type_approval'] = $this->m_master->showData_array('db_purchasing.cfg_m_type_approval');
       $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/config/Set_Approval_SPK',$this->data,true);
       echo json_encode($arr_result);
    }

    public function get_cfg_set_roleuser_po($Departement)
    {
        $this->auth_ajax();
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser,c.Visible,c.TypeDesc
                from db_purchasing.cfg_m_userrole as a left join (select * from db_purchasing.cfg_approval where Departement = ? ) as c
                on a.ID = c.ID_m_userrole
                left join 
                (select NIP,Name from db_employees.employees
                 UNION 
                 select NIK as NIP,Name from db_employees.holding
                ) as b on b.NIP = c.NIP 
                order by a.ID asc
                ';
        $query=$this->db->query($sql, array($Departement))->result_array();
        echo json_encode($query);
    }

    public function get_cfg_set_roleuser_spk($Departement)
    {
        $this->auth_ajax();
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser,c.Visible,c.TypeDesc
                from db_purchasing.cfg_m_userrole as a left join (select * from db_purchasing.cfg_approval_spk where Departement = ? ) as c
                on a.ID = c.ID_m_userrole
                left join 
                (select NIP,Name from db_employees.employees
                 UNION 
                 select NIK as NIP,Name from db_employees.holding
                )
                as b on b.NIP = c.NIP 
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
                    $G = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
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

    public function save_cfg_set_roleuser_spk()
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
                    $G = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
                    if (count($G) == 0) {
                        $msg['msg'] = 'NIP : '.$NIP.' is not already exist';   
                        break;
                    }
                    $Method = $dt[$i]['Method'];
                    $subAction = $Method['Action'];
                    if ($subAction == 'add') {
                        $this->db->insert('db_purchasing.cfg_approval_spk',$FormInsert);
                    }
                    else
                    {
                        $ID = $Method['ID'];
                        $this->db->where('ID', $ID);
                        $this->db->update('db_purchasing.cfg_approval_spk', $FormInsert);
                    }
                }
                if ($msg['msg'] == '') {
                   $msg['status'] = 1;
                }
                break;
            case "delete":
                $ID = $Input['ID_set_roleuser'];
                $this->m_master->delete_id_table_all_db($ID,'db_purchasing.cfg_approval_spk');
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
            case 'modifycreated':
                $this->modifycreated_submit_po();
                break;
            case 'cancel':
                $this->cancel_created_submit_po();
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

            // -- check amount untuk check total approval
            $Amount = 0;
                for ($i=0; $i < count($arr_pr_detail); $i++) { 
                    $G = $this->m_master->caribasedprimary('db_budgeting.pr_detail','ID',$arr_pr_detail[$i]);
                    $Amount = $Amount +$G[0]['SubTotal'];
                }

            $JsonStatus = $this->m_pr_po->GetRuleApproval_PO_JsonStatus($Amount);
            if (count($JsonStatus) > 0) {
                // 1
                   $dataSave = array(
                       'CreatedBy' => $this->session->userdata('NIP'),
                       'CreatedAt' => date('Y-m-d H:i:s'),
                   );

                   $this->db->insert('db_purchasing.pre_po',$dataSave);
                   $ID_pre_po = $this->db->insert_id();

                // 3
                   
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

                       $temp = array(
                           'Code' => $Code,
                           'ID_pre_po_detail' => $ID_pre_po_detail,
                           'UnitCost' => $G[0]['UnitCost'],
                           'Discount' => 0,
                           'PPN' => $G[0]['PPH'],
                           'AnotherCost' => 0.0,
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
                           for ($j=0; $j < count($arr_pr_code); $j++) { 
                               if ($arr_pr_code[$j]['PRCode'] == $G[0]['PRCode']) {
                                   $arr_pr_code[$j]['Count'] = $arr_pr_code[$j]['Count'] + 1;
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

                   // get pay_type
                   $G_pay_type = $this->m_master->showData_array('db_purchasing.pay_type');
                   $ID_pay_type = $G_pay_type[0]['ID'];

                 // 5 PO & 6
                   $dataSave = array(
                       'ID_pre_po' => $ID_pre_po,
                       'TypeCreate' => 1,
                       'Code' => $Code,
                       'ID_pre_po_supplier' => $ID_pre_po_supplier,
                       'JsonStatus' => json_encode($JsonStatus),
                       'Status' => 0,
                       // 'Notes' => 'Syarat Pembayaran : 2 minggu setelah barang & INV diterima',
                       'Notes' => $G_pay_type[0]['Name'],
                       'ID_pay_type' => $ID_pay_type,
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
                           'Status' => 1,
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
                   $arr_rs['url'] = 'global/purchasing/transaction/po/list/'.$urlCode;
                   $arr_rs['Code'] = $Code;

                   // insert to pr_circulation_sheet
                       $this->m_pr_po->po_circulation_sheet($Code,'PO Created');
            }
            else
            {
                $arr_rs = array('status' => '0','message' => 'Nominal '.$Amount.' tidak di set di RAD','url'=> '','Code');
            }

        echo json_encode($arr_rs);    
    }

    public function edit_submit_po()
    {
        $arr_rs = array('status' => '1','message' => '','url'=> '','Code');
        $arr_pr_detail = $this->input->post('arr_pr_detail');
        $key = "UAP)(*";
        $arr_pr_detail = $this->jwt->decode($arr_pr_detail,$key);
        $arr_supplier = $this->input->post('arr_supplier');
        $key = "UAP)(*";
        $arr_supplier = $this->jwt->decode($arr_supplier,$key);
        $arr_supplier = (array) json_decode(json_encode($arr_supplier),true);
        $Code = $this->input->post('Code');
        $Code = $this->jwt->decode($Code,$key);

        // print_r($arr_supplier);die();

        /*
            1.Cari Data Code dengan POCode pada po_create
            2.Dapatkan ID_pre_po
            3.Dapatkan pre_po_supplier dengan ID_pre_po
              bandingkan dengan arr_supplier, @jika ada check file upload ada atau tidak, jika tidak ada upload file gunakan file lama jika CodeSupplier Sama,
              jika CodeSupplier tidak sama maka required fileupload
              jika ada upload file hapus file lama dan upload file baru lalu update data untuk simpan filenamenya
              @jika tidak ada maka buat file upload menjadi required
            4.Cari data dengan ID_pre_po pada table pre_po_detail
            5.Dapatkan Nomor PRnya dengan cari ke pr_detail dengan ID_pr_detail
              Setiap satu row / per looping Item_proc -1 dan Item_pending +1 pada table pr_status
              Setiap satu row / per looping ID_pr_detail diupdate status menjadi 0 pada table pr_status_detail
            6.Cari Data Code dengan POCode pada po_detail
              dapatkan ID_pre_po_detail
              hapus pre_po_detail by ID dengan ID_pre_po_detail
            7.Hapus po_detail by Code
            8 dan seterusnya sama dengan metode insert
            

        */

            // -- check amount untuk check total approval
            $Amount = 0;
                for ($i=0; $i < count($arr_pr_detail); $i++) { 
                    $G = $this->m_master->caribasedprimary('db_budgeting.pr_detail','ID',$arr_pr_detail[$i]);
                    $Amount = $Amount +$G[0]['SubTotal'];
                }

            $JsonStatus = $this->m_pr_po->GetRuleApproval_PO_JsonStatus($Amount);
            if (count($JsonStatus) > 0) {
                // 1
                  $G_data = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$Code);
                // 2  
                  $ID_pre_po = $G_data[0]['ID_pre_po'];
                // 3  
                  $G_pre_po_supplier = $this->m_master->caribasedprimary('db_purchasing.pre_po_supplier','ID_pre_po',$ID_pre_po);
                  $ID_pre_po_supplier = ''; // for approve

                    // validation file required
                    $CheckUpload  = true;
                    for ($i=0; $i < count($G_pre_po_supplier); $i++) { 
                        $ID = $G_pre_po_supplier[$i]['ID'];
                        for ($j=0; $j < count($arr_supplier); $j++) { 
                            if ($G_pre_po_supplier[$i]['CodeSupplier'] == $arr_supplier[$j]['CodeSupplier']) {
                                if (!array_key_exists('UploadFile'.$j, $_FILES)) {
                                    $CheckUpload = false;
                                    $arr_rs['status'] = 0;
                                    $arr_rs['message'] = 'File is required';
                                    break;
                                }
                            }
                        }
                    }

                    if ($CheckUpload) {
                        $ID_pre_po_supplier = '';
                        for ($i=0; $i < count($G_pre_po_supplier); $i++) { 
                          $bool_s = true;
                          $ID = $G_pre_po_supplier[$i]['ID'];
                          $FileOffer = $G_pre_po_supplier[$i]['FileOffer'];
                          $FileOffer = json_decode($FileOffer,true); // only one file
                          $FileOffer = $FileOffer[0];
                          for ($j=0; $j < count($arr_supplier); $j++) { 
                              if ($G_pre_po_supplier[$i]['CodeSupplier'] == $arr_supplier[$j]['CodeSupplier']) {
                                  $bool_s = false;
                                  // jika ada check file upload ada atau tidak, jika tidak gunakan file lama
                                  // jika ada hapus file lama dan upload file baru lalu update data untuk simpan filenamenya
                                      if (array_key_exists('UploadFile'.$j, $_FILES)) {
                                          // do upload file
                                          $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadFile'.$j,$path = './uploads/budgeting/po');
                                          $dataSave = $arr_supplier[$j];
                                          $dataSave['FileOffer'] = json_encode($uploadFile); 
                                          // delete file lama
                                          $path = FCPATH.'uploads\\budgeting\\po\\'.$FileOffer;
                                          if (file_exists($path)) {
                                            unlink($path);
                                          }
                                          
                                          // update di database
                                          $this->db->where('ID',$ID);
                                          $this->db->update('db_purchasing.pre_po_supplier',$dataSave);

                                          if ($dataSave['Approve'] == 1) {
                                              $ID_pre_po_supplier = $ID;
                                          }
                                      }
                                      
                                  break;
                               }
                          }
                           
                           if ($bool_s) {
                               // delete db and delete file
                                $path = FCPATH.'uploads\\budgeting\\po\\'.$FileOffer;
                                if (file_exists($path)) {
                                  unlink($path);
                                }
                               $this->db->where('ID',$ID);
                               $this->db->delete('db_purchasing.pre_po_supplier');
                           }
                        }

                        // file arr_supplier tambahan jika ada
                        $G_pre_po_supplier = $this->m_master->caribasedprimary('db_purchasing.pre_po_supplier','ID_pre_po',$ID_pre_po);
                        for ($i=0; $i < count($arr_supplier); $i++) {
                            $bool = true;
                            for ($j=0; $j < count($G_pre_po_supplier); $j++) {
                               if ($G_pre_po_supplier[$j]['CodeSupplier'] == $arr_supplier[$i]['CodeSupplier']) {
                                 $bool = false;
                                 break;
                               }
                            }

                            if ($bool) {
                                if (array_key_exists('UploadFile'.$i, $_FILES)) {
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
                            }

                        }

                        // 4 & // 5 
                            $sql = 'select a.ID as pre_po_detail, a.ID_pre_po,a.ID_pr_detail,b.*
                                    from db_purchasing.pre_po_detail as a 
                                    join db_budgeting.pr_detail as b on a.ID_pr_detail = b.ID
                                    where a.ID_pre_po = ?
                                    ';
                            $query=$this->db->query($sql, array($ID_pre_po))->result_array();
                            for ($i=0; $i < count($query); $i++) { 
                                $PRCode = $query[$i]['PRCode'];
                                $ID_pr_detail = $query[$i]['ID_pr_detail'];
                                $G_data_pr_status = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
                                if ($query[$i]['Status'] != -1) {
                                  $Item_proc= $G_data_pr_status[0]['Item_proc'] - 1;    
                                  $Item_pending= $G_data_pr_status[0]['Item_pending'] + 1;
                                }
                                else
                                {
                                  $Item_proc= $G_data_pr_status[0]['Item_proc'];
                                  $Item_pending= $G_data_pr_status[0]['Item_pending'];
                                }
                                
                                $Status = ($Item_proc == 0 && $G_data_pr_status[0]['Item_done'] == 0) ? 0 : 1;
                                $dataSave = array(
                                   'Item_proc' => $Item_proc,
                                    'Item_pending' => $Item_pending,
                                    'Status' => $Status,   
                                );

                                $this->db->where('PRCode',$PRCode);
                                $this->db->update('db_purchasing.pr_status',$dataSave);

                                $G_data_pr_status_detail = $this->m_master->caribasedprimary('db_purchasing.pr_status_detail','ID_pr_status',$G_data_pr_status[0]['ID']);
                                for ($j=0; $j < count($G_data_pr_status_detail); $j++) { 
                                    $ID_pr_detail_ = $G_data_pr_status_detail[$j]['ID_pr_detail'];
                                    if ($ID_pr_detail == $ID_pr_detail_) {
                                        $dataSave = array(
                                            'Status' => 0,
                                        );
                                        $this->db->where('ID',$G_data_pr_status_detail[$j]['ID']);
                                        $this->db->update('db_purchasing.pr_status_detail',$dataSave);
                                    }
                                }
                            }

                        // 6  
                            $G_po_detail = $this->m_master->caribasedprimary('db_purchasing.po_detail','Code',$Code);
                            for ($i=0; $i < count($G_po_detail); $i++) { 
                                $ID_pre_po_detail = $G_po_detail[$i]['ID_pre_po_detail'];
                                $this->db->where('ID',$ID_pre_po_detail);
                                $this->db->delete('db_purchasing.pre_po_detail');
                            }
                        // 7    
                            $this->db->where('Code',$Code);
                            $this->db->delete('db_purchasing.po_detail'); 


                        // 8    
                            // $Amount = 0;
                            $dt_po_detail = array();
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
                                // $Amount = $Amount +$G[0]['SubTotal'];

                                $temp = array(
                                    'Code' => $Code,
                                    'ID_pre_po_detail' => $ID_pre_po_detail,
                                    'UnitCost' => $G[0]['UnitCost'],
                                    'Discount' => 0,
                                    'PPN' => $G[0]['PPH'],
                                    'AnotherCost' => 0.0,
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
                                    for ($j=0; $j < count($arr_pr_code); $j++) { 
                                        if ($arr_pr_code[$j]['PRCode'] == $G[0]['PRCode']) {
                                            $arr_pr_code[$j]['Count'] = $arr_pr_code[$j]['Count'] + 1;
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

                        // get pay_type
                        $G_pay_type = $this->m_master->showData_array('db_purchasing.pay_type');
                        $ID_pay_type = $G_pay_type[0]['ID'];    

                        // 9
                           $dataSave = array(
                               'TypeCreate' => 1,
                               'ID_pre_po_supplier' => $ID_pre_po_supplier,
                               'JsonStatus' => json_encode($JsonStatus),
                               'Status' => 0,
                               // 'Notes' => 'Syarat Pembayaran : 2 minggu setelah barang & INV diterima',
                               'Notes' => $G_pay_type[0]['Name'],
                               'ID_pay_type' => $ID_pay_type,
                               'Supporting_documents' => json_encode($arr = array()),
                           );

                           $this->db->where('Code',$Code);
                           $this->db->update('db_purchasing.po_create',$dataSave);

                           for ($i=0; $i < count($dt_po_detail); $i++) { 
                               $dataSave = $dt_po_detail[$i];
                               $this->db->insert('db_purchasing.po_detail',$dataSave);
                           }

                           for ($i=0; $i < count($arr_pr_code); $i++) {
                                $PRCode = $arr_pr_code[$i]['PRCode'];
                                $G_data = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
                                $Item_pending = $G_data[0]['Item_pending'];
                                $Item_pending = $Item_pending - $arr_pr_code[$i]['Count'];
                                $Item_proc = $G_data[0]['Item_proc'];
                                $Item_proc = $Item_proc  + $arr_pr_code[$i]['Count'];
                                $Status = ($Item_proc == 0 && $G_data[0]['Item_done'] == 0) ? 0 : 1;
                                $dataSave = array(
                                 'Item_pending' => $Item_pending,
                                 'Item_proc' => $Item_proc,
                                 'Status' => $Status,
                                );

                                $this->db->where('ID',$G_data[0]['ID']);
                                $this->db->update('db_purchasing.pr_status',$dataSave);
                            }

                            for ($i=0; $i < count($arr_pr_detail); $i++) { 
                                $ID_pr_detail = $arr_pr_detail[$i];
                                $dataSave = array(
                                 'Status' => 1,
                                );
                                $this->db->where('ID_pr_detail',$ID_pr_detail);
                                $this->db->update('db_purchasing.pr_status_detail',$dataSave); 
                            }

                            $urlCode = str_replace('/', '-', $Code);
                            $arr_rs['url'] = 'global/purchasing/transaction/po/list/'.$urlCode;
                            $arr_rs['Code'] = $Code;

                            // insert to pr_circulation_sheet
                                $this->m_pr_po->po_circulation_sheet($Code,'Re-Open-PO'); 
                    }
            }
            else
            {
                $arr_rs = array('status' => '0','message' => 'Nominal '.$Amount.' tidak di set di RAD','url'=> '','Code');
            }

        echo json_encode($arr_rs);
    }

    public function modifycreated_submit_po()
    {
        $rs = array('Status' => 1,'Change' => 0,'msg' => '');
        $Input = $this->getInputToken();
        $po_data = $Input['po_data']; // data yang di passing awal dari server ke client
        $arr_post_data_detail =$Input['arr_post_data_detail'];
        // $AnotherCost = $Input['AnotherCost'];
        $Notes = $Input['Notes'];
        $ID_pay_type = $Input['ID_pay_type'];
        $NotesAnotherCost = $Input['NotesAnotherCost'];

        $CheckPerubahanData = $this->m_pr_po->CheckPerubahanData_PO_Created($po_data);
        if ($CheckPerubahanData) {
            $arr_post_data_detail = json_decode(json_encode($arr_post_data_detail),true);
            $Amount = 0;
            for ($i=0; $i < count($arr_post_data_detail); $i++) { 
                $dataSave = $arr_post_data_detail[$i];
                unset($dataSave['ID_po_detail']);
                $ID = $arr_post_data_detail[$i]['ID_po_detail'];
                $this->db->where('ID',$ID);
                $this->db->update('db_purchasing.po_detail',$dataSave);
                $Amount = $Amount +$dataSave['Subtotal'];
            }

            $po_data = json_decode(json_encode($po_data),true);
            $po_create = $po_data['po_create'];
            $Code = $po_create[0]['Code'];

            // cek circulation sheet
            if ($po_create[0]['Code'] != 0 || $po_create[0]['Code'] != '') {
                // insert to pr_circulation_sheet
                    $this->m_pr_po->po_circulation_sheet($Code,'PO Edited');
            }

            $JsonStatus = $this->m_pr_po->GetRuleApproval_PO_JsonStatus($Amount);

            $dataSave = array(
                // 'AnotherCost' => $AnotherCost,
                'Notes' => $Notes,
                'ID_pay_type' => $ID_pay_type,
                'Status' => 1,
                'JsonStatus' => json_encode($JsonStatus),
                'Notes2' => $NotesAnotherCost,
            );
            $this->db->where('Code',$Code);
            $this->db->update('db_purchasing.po_create',$dataSave);

            // notification
              $CodeUrl = str_replace('/', '-', $Code);
              // $JsonStatus = json_decode($po_create[0]['JsonStatus'],true);
              $NIPApprovalNext = $JsonStatus[1]['NIP'];
              $NIP = $this->session->userdata('NIP');
              // Send Notif for next approval
                  $data = array(
                      'auth' => 's3Cr3T-G4N',
                      'Logging' => array(
                                      'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Created PO/SPK : '.$Code,
                                      'Description' => 'Please approve PO/SPK '.$Code,
                                      'URLDirect' => 'global/purchasing/transaction/po/list/'.$CodeUrl,
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

                  // send email is holding or warek keatas
                       $this->m_master->send_email_budgeting_holding($NIPApprovalNext,'NA.4',$data['Logging']['URLDirect'],$data['Logging']['Description']);
        }
        else
        {
            $rs['Change'] = 1;
        }

        echo json_encode($rs);
    }

    public function cancel_created_submit_po()
    {
        $rs = array('Status' => 1,'Change' => 0,'msg' => '');
        $Input = $this->getInputToken();
        $po_data = $Input['po_data']; // data yang di passing awal dari server ke client
        $arr_post_data_ID_po_detail =$Input['arr_post_data_ID_po_detail'];
        $PRRejectItem = $Input['PRRejectItem']; // data yang di passing awal dari server ke client
        $NoteDel = $Input['NoteDel'];

        $po_data = json_decode(json_encode($po_data),true);
        $po_create = $po_data['po_create'];
        $Code = $po_create[0]['Code'];
        $PRCode = $Input['PRCode'];

        $CheckPerubahanData = $this->m_pr_po->CheckPerubahanData_PO_Created($po_data);
        if ($CheckPerubahanData) {
            // check apakah data telah dibuat payment atau belum
                $G= $this->m_master->caribasedprimary('db_payment.payment','Code_po_create',$Code);
                if (count($G) == 0) {
                    // do change status pr item & kembalikan
                        $arr_post_data_ID_po_detail = json_decode(json_encode($arr_post_data_ID_po_detail),true);

                    // cek circulation sheet
                    if ($po_create[0]['Code'] != 0 || $po_create[0]['Code'] != '') {
                        $Desc = 'PO Cancel<br>{'.$NoteDel.'}';
                        $Desc2 = 'PO '.$Code.' Cancel<br>{'.$NoteDel.'}';
                        if ($PRRejectItem) {
                            $this->m_pr_po->ReturnAllBudgetFromPO($arr_post_data_ID_po_detail);
                            // cek for some item or all item
                                $cek_item = $this->m_pr_po->CekPRTo_Item_IN_PO($arr_post_data_ID_po_detail,$PRCode);
                                $__r = '';
                                if ($cek_item) {
                                   $__r = 'All';
                                }
                                else
                                {
                                    $__r = 'Some'; 
                                }

                            $Desc = 'PO '.$Code.' & '.$__r.' ITEM PR have been Cancel<br>{'.$NoteDel.'}';
                            $Desc2 = 'PO '.$Code.' & '.$__r.' ITEM PR have been Cancel<br>{'.$NoteDel.'}';

                            // update status pr jika all item cancel menjadi cancel
                                // $G_item_pr = $this->m_master->caribasedprimary('db_budgeting.pr_detail','PRCode',$PRCode);
                                // $bool = true;
                                // for ($i=0; $i < count($G_item_pr); $i++) { 
                                //      if ($G_item_pr[$i]['Status'] == 1) {
                                //          break;
                                //          $bool = false;
                                //      }
                                // }

                                // if ($bool) {
                                if ($cek_item) { // jika all item
                                    $arr_save = array(
                                        'Status' => 4,
                                    );

                                    $this->db->where('PRCode',$PRCode);
                                    $this->db->update('db_budgeting.pr_create',$arr_save);
                                }

                            // update status pr_status dan pr_status_detail
                                $this->m_pr_po->__Cancel_update_pr_status_pr_status_detail($PRCode,$arr_post_data_ID_po_detail);    
                        }
                        // insert to pr_circulation_sheet
                            $this->m_pr_po->po_circulation_sheet($Code,$Desc);
                            $this->m_pr_po->pr_circulation_sheet($PRCode,$Desc2);
                    }

                    $dataSave = array(
                        'Status' => 4,
                    );
                    
                    $this->db->where('Code',$Code);
                    $this->db->update('db_purchasing.po_create',$dataSave);
                }
                else
                {
                    $rs['Status'] = 0;
                    $rs['msg'] = 'Payment for The PO : '.$Code.' has created, cant action!!!';
                }
        }
        else
        {
            $rs['Change'] = 1;
        }

        echo json_encode($rs);
    }

    public function submit_spk()
    {
        $action_mode = $this->input->post('action_mode');
        $key = "UAP)(*";
        $action_mode = $this->jwt->decode($action_mode,$key);

        switch ($action_mode) {
            case 'add':
                $this->insert_submit_spk();
                break;
            case 'edit':
                $this->edit_submit_spk();
                break;
            case 'modifycreated':
                $this->modifycreated_submit_spk();
                break;
            case 'cancel':
                $this->cancel_created_submit_spk();
                break;          
            default:
                echo '{"status":"999","message":"Not Authorize"}'; 
                break;
        }
    }

    public function insert_submit_spk()
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

            // -- check amount untuk check total approval
            $Amount = 0;
                for ($i=0; $i < count($arr_pr_detail); $i++) { 
                    $G = $this->m_master->caribasedprimary('db_budgeting.pr_detail','ID',$arr_pr_detail[$i]);
                    $Amount = $Amount +$G[0]['SubTotal'];
                }

            $JsonStatus = $this->m_pr_po->GetRuleApproval_SPK_JsonStatus();
            if (count($JsonStatus) > 0) {
                // 1
                   $dataSave = array(
                       'CreatedBy' => $this->session->userdata('NIP'),
                       'CreatedAt' => date('Y-m-d H:i:s'),
                   );

                   $this->db->insert('db_purchasing.pre_po',$dataSave);
                   $ID_pre_po = $this->db->insert_id();

                // 3
                   $dt_po_detail = array();
                   $Code = $this->m_pr_po->Get_SPKCode(); 
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
                       // $Amount = $Amount +$G[0]['SubTotal'];

                       $temp = array(
                           'Code' => $Code,
                           'ID_pre_po_detail' => $ID_pre_po_detail,
                           'UnitCost' => $G[0]['UnitCost'],
                           'Discount' => 0,
                           'PPN' => $G[0]['PPH'],
                           'AnotherCost' => 0.0,
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
                           for ($j=0; $j < count($arr_pr_code); $j++) { 
                               if ($arr_pr_code[$j]['PRCode'] == $G[0]['PRCode']) {
                                   $arr_pr_code[$j]['Count'] = $arr_pr_code[$j]['Count'] + 1;
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

                   // get pay_type
                   $G_pay_type = $this->m_master->showData_array('db_purchasing.pay_type');
                   $ID_pay_type = $G_pay_type[0]['ID'];    

                 // 5 PO & 6
                   $dataSave = array(
                       'ID_pre_po' => $ID_pre_po,
                       'TypeCreate' => 2,
                       'Code' => $Code,
                       'ID_pre_po_supplier' => $ID_pre_po_supplier,
                       'JsonStatus' => json_encode($JsonStatus),
                       'Status' => 0,
                       // 'Notes' => '2 minggu setelah barang & INV diterima',
                       'Notes' => $G_pay_type[0]['Name'],
                       'ID_pay_type' => $ID_pay_type,
                       'Notes2' => '-',
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
                           'Status' => 1,
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
                   $arr_rs['url'] = 'global/purchasing/transaction/spk/list/'.$urlCode;
                   $arr_rs['Code'] = $Code;

                   // insert to pr_circulation_sheet
                       $this->m_pr_po->po_circulation_sheet($Code,'SPK Created');
            }
            else
            {
                $arr_rs = array('status' => '0','message' => 'Rule Approval SPK tidak ada','url'=> '','Code');
            }

         

        echo json_encode($arr_rs);    
    }

    public function edit_submit_spk()
    {
        $arr_rs = array('status' => '1','message' => '','url'=> '','Code');
        $arr_pr_detail = $this->input->post('arr_pr_detail');
        $key = "UAP)(*";
        $arr_pr_detail = $this->jwt->decode($arr_pr_detail,$key);
        $arr_supplier = $this->input->post('arr_supplier');
        $key = "UAP)(*";
        $arr_supplier = $this->jwt->decode($arr_supplier,$key);
        $arr_supplier = (array) json_decode(json_encode($arr_supplier),true);
        $Code = $this->input->post('Code');
        $Code = $this->jwt->decode($Code,$key);

        // print_r($arr_supplier);die();

        /*
            1.Cari Data Code dengan POCode pada po_create
            2.Dapatkan ID_pre_po
            3.Dapatkan pre_po_supplier dengan ID_pre_po
              bandingkan dengan arr_supplier, @jika ada check file upload ada atau tidak, jika tidak ada upload file gunakan file lama jika CodeSupplier Sama,
              jika CodeSupplier tidak sama maka required fileupload
              jika ada upload file hapus file lama dan upload file baru lalu update data untuk simpan filenamenya
              @jika tidak ada maka buat file upload menjadi required
            4.Cari data dengan ID_pre_po pada table pre_po_detail
            5.Dapatkan Nomor PRnya dengan cari ke pr_detail dengan ID_pr_detail
              Setiap satu row / per looping Item_proc -1 dan Item_pending +1 pada table pr_status
              Setiap satu row / per looping ID_pr_detail diupdate status menjadi 0 pada table pr_status_detail
            6.Cari Data Code dengan POCode pada po_detail
              dapatkan ID_pre_po_detail
              hapus pre_po_detail by ID dengan ID_pre_po_detail
            7.Hapus po_detail by Code
            8 dan seterusnya sama dengan metode insert
            

        */
            // 1
              $G_data = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$Code);
            // 2  
              $ID_pre_po = $G_data[0]['ID_pre_po'];
            // 3  
              $G_pre_po_supplier = $this->m_master->caribasedprimary('db_purchasing.pre_po_supplier','ID_pre_po',$ID_pre_po);
              $ID_pre_po_supplier = ''; // for approve

                // validation file required
                $CheckUpload  = true;
                for ($i=0; $i < count($G_pre_po_supplier); $i++) { 
                    $ID = $G_pre_po_supplier[$i]['ID'];
                    for ($j=0; $j < count($arr_supplier); $j++) { 
                        if ($G_pre_po_supplier[$i]['CodeSupplier'] == $arr_supplier[$j]['CodeSupplier']) {
                            if (!array_key_exists('UploadFile'.$j, $_FILES)) {
                                $CheckUpload = false;
                                $arr_rs['status'] = 0;
                                $arr_rs['message'] = 'File is required';
                                break;
                            }
                        }
                    }
                }

                if ($CheckUpload) {
                    $ID_pre_po_supplier = '';
                    for ($i=0; $i < count($G_pre_po_supplier); $i++) { 
                      $bool_s = true;
                      $ID = $G_pre_po_supplier[$i]['ID'];
                      $FileOffer = $G_pre_po_supplier[$i]['FileOffer'];
                      $FileOffer = json_decode($FileOffer,true); // only one file
                      $FileOffer = $FileOffer[0];
                      for ($j=0; $j < count($arr_supplier); $j++) { 
                          if ($G_pre_po_supplier[$i]['CodeSupplier'] == $arr_supplier[$j]['CodeSupplier']) {
                              $bool_s = false;
                              // jika ada check file upload ada atau tidak, jika tidak gunakan file lama
                              // jika ada hapus file lama dan upload file baru lalu update data untuk simpan filenamenya
                                  if (array_key_exists('UploadFile'.$j, $_FILES)) {
                                      // do upload file
                                      $uploadFile = $this->m_master->uploadDokumenMultiple(uniqid(),'UploadFile'.$j,$path = './uploads/budgeting/po');
                                      $dataSave = $arr_supplier[$j];
                                      $dataSave['FileOffer'] = json_encode($uploadFile); 
                                      // delete file lama
                                      $path = FCPATH.'uploads\\budgeting\\po\\'.$FileOffer;
                                      if (file_exists($path)) {
                                        unlink($path);
                                      }
                                      
                                      // update di database
                                      $this->db->where('ID',$ID);
                                      $this->db->update('db_purchasing.pre_po_supplier',$dataSave);

                                      if ($dataSave['Approve'] == 1) {
                                          $ID_pre_po_supplier = $ID;
                                      }
                                  }
                                  
                              break;
                           }
                      }
                       
                       if ($bool_s) {
                           // delete db and delete file
                            $path = FCPATH.'uploads\\budgeting\\po\\'.$FileOffer;
                            if (file_exists($path)) {
                              unlink($path);
                            }
                           $this->db->where('ID',$ID);
                           $this->db->delete('db_purchasing.pre_po_supplier');
                       }
                    }

                    // file arr_supplier tambahan jika ada
                    $G_pre_po_supplier = $this->m_master->caribasedprimary('db_purchasing.pre_po_supplier','ID_pre_po',$ID_pre_po);
                    for ($i=0; $i < count($arr_supplier); $i++) {
                        $bool = true;
                        for ($j=0; $j < count($G_pre_po_supplier); $j++) {
                           if ($G_pre_po_supplier[$j]['CodeSupplier'] == $arr_supplier[$i]['CodeSupplier']) {
                             $bool = false;
                             break;
                           }
                        }

                        if ($bool) {
                            if (array_key_exists('UploadFile'.$i, $_FILES)) {
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
                        }

                    }

                    // 4 & // 5 
                        $sql = 'select a.ID as pre_po_detail, a.ID_pre_po,a.ID_pr_detail,b.*
                                from db_purchasing.pre_po_detail as a 
                                join db_budgeting.pr_detail as b on a.ID_pr_detail = b.ID
                                where a.ID_pre_po = ?
                                ';
                        $query=$this->db->query($sql, array($ID_pre_po))->result_array();
                        for ($i=0; $i < count($query); $i++) { 
                            $PRCode = $query[$i]['PRCode'];
                            $ID_pr_detail = $query[$i]['ID_pr_detail'];
                            $G_data_pr_status = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
                            if ($query[$i]['Status'] != -1) {
                              $Item_proc= $G_data_pr_status[0]['Item_proc'] - 1;    
                              $Item_pending= $G_data_pr_status[0]['Item_pending'] + 1;
                            }
                            else
                            {
                              $Item_proc= $G_data_pr_status[0]['Item_proc'];
                              $Item_pending= $G_data_pr_status[0]['Item_pending'];
                            }
                            $Status = ($G_data_pr_status[0]['Item_done'] == 0 && $Item_proc == 0) ? 0 : 1;
                            $dataSave = array(
                               'Item_proc' => $Item_proc,
                                'Item_pending' => $Item_pending,
                                'Status' => $Status   
                            );

                            $this->db->where('PRCode',$PRCode);
                            $this->db->update('db_purchasing.pr_status',$dataSave);

                            $G_data_pr_status_detail = $this->m_master->caribasedprimary('db_purchasing.pr_status_detail','ID_pr_status',$G_data_pr_status[0]['ID']);
                            for ($j=0; $j < count($G_data_pr_status_detail); $j++) { 
                                $ID_pr_detail_ = $G_data_pr_status_detail[$j]['ID_pr_detail'];
                                if ($ID_pr_detail == $ID_pr_detail_) {
                                    $dataSave = array(
                                        'Status' => 0,
                                    );
                                    $this->db->where('ID',$G_data_pr_status_detail[$j]['ID']);
                                    $this->db->update('db_purchasing.pr_status_detail',$dataSave);
                                }
                            }
                        }

                    // 6  
                        $G_po_detail = $this->m_master->caribasedprimary('db_purchasing.po_detail','Code',$Code);
                        for ($i=0; $i < count($G_po_detail); $i++) { 
                            $ID_pre_po_detail = $G_po_detail[$i]['ID_pre_po_detail'];
                            $this->db->where('ID',$ID_pre_po_detail);
                            $this->db->delete('db_purchasing.pre_po_detail');
                        }
                    // 7    
                        $this->db->where('Code',$Code);
                        $this->db->delete('db_purchasing.po_detail'); 


                    // 8    
                        $Amount = 0;
                        $dt_po_detail = array();
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
                                'AnotherCost' => 0.0,
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
                                for ($j=0; $j < count($arr_pr_code); $j++) { 
                                    if ($arr_pr_code[$j]['PRCode'] == $G[0]['PRCode']) {
                                        $arr_pr_code[$j]['Count'] = $arr_pr_code[$j]['Count'] + 1;
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

                        // get pay_type
                        $G_pay_type = $this->m_master->showData_array('db_purchasing.pay_type');
                        $ID_pay_type = $G_pay_type[0]['ID'];   

                    // 9
                       $JsonStatus = $this->m_pr_po->GetRuleApproval_SPK_JsonStatus();
                       $dataSave = array(
                           'TypeCreate' => 2,
                           'ID_pre_po_supplier' => $ID_pre_po_supplier,
                           'JsonStatus' => json_encode($JsonStatus),
                           'Status' => 0,
                           // 'Notes' => 'Syarat Pembayaran : 2 minggu setelah barang & INV diterima',
                           'Notes' => $G_pay_type[0]['Name'],
                           'ID_pay_type' => $ID_pay_type,
                           'Supporting_documents' => json_encode($arr = array()),
                       );

                       $this->db->where('Code',$Code);
                       $this->db->update('db_purchasing.po_create',$dataSave);

                       for ($i=0; $i < count($dt_po_detail); $i++) { 
                           $dataSave = $dt_po_detail[$i];
                           $this->db->insert('db_purchasing.po_detail',$dataSave);
                       }

                       for ($i=0; $i < count($arr_pr_code); $i++) {
                            $PRCode = $arr_pr_code[$i]['PRCode'];
                            $G_data = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
                            $Item_pending = $G_data[0]['Item_pending'];
                            $Item_pending = $Item_pending - $arr_pr_code[$i]['Count'];
                            $Item_proc = $G_data[0]['Item_proc'];
                            $Item_proc = $Item_proc  + $arr_pr_code[$i]['Count'];
                            $Status = ($G_data[0]['Item_done'] == 0 && $Item_proc == 0) ? 0 : 1;

                            $dataSave = array(
                             'Item_pending' => $Item_pending,
                             'Item_proc' => $Item_proc,
                             'Status' => $Status,
                            );

                            $this->db->where('ID',$G_data[0]['ID']);
                            $this->db->update('db_purchasing.pr_status',$dataSave);
                        }

                        for ($i=0; $i < count($arr_pr_detail); $i++) { 
                            $ID_pr_detail = $arr_pr_detail[$i];
                            $dataSave = array(
                             'Status' => 1,
                            );
                            $this->db->where('ID_pr_detail',$ID_pr_detail);
                            $this->db->update('db_purchasing.pr_status_detail',$dataSave); 
                        }

                        $urlCode = str_replace('/', '-', $Code);
                        $arr_rs['url'] = 'global/purchasing/transaction/spk/list/'.$urlCode;
                        $arr_rs['Code'] = $Code;

                        // insert to pr_circulation_sheet
                            $this->m_pr_po->po_circulation_sheet($Code,'Re-Open-spk'); 
                }

        echo json_encode($arr_rs);
    }

    public function modifycreated_submit_spk()
    {
        $rs = array('Status' => 1,'Change' => 0,'msg' => '');
        $Input = $this->getInputToken();
        $po_data = $Input['po_data']; // data yang di passing awal dari server ke client
        $arr_post_data_detail =$Input['arr_post_data_detail'];
        $Notes = $Input['Notes'];
        $Notes2 = $Input['Notes2'];
        $JobSpk = $Input['JobSpk'];
        $ID_pay_type = $Input['ID_pay_type'];

        $CheckPerubahanData = $this->m_pr_po->CheckPerubahanData_PO_Created($po_data);
        if ($CheckPerubahanData) {
            $arr_post_data_detail = json_decode(json_encode($arr_post_data_detail),true);
            // print_r($arr_post_data_detail);die();
            // $Amount = 0;
            for ($i=0; $i < count($arr_post_data_detail); $i++) { 
                $dataSave = $arr_post_data_detail[$i];
                unset($dataSave['ID_po_detail']);
                $ID = $arr_post_data_detail[$i]['ID_po_detail'];
                $this->db->where('ID',$ID);
                $this->db->update('db_purchasing.po_detail',$dataSave);
                // $Amount = $Amount +$dataSave['Subtotal'];
            }

            $po_data = json_decode(json_encode($po_data),true);
            $po_create = $po_data['po_create'];
            $Code = $po_create[0]['Code'];

            // cek circulation sheet

            if ($po_create[0]['Code'] != 0 || $po_create[0]['Code'] != '') {
                // insert to pr_circulation_sheet
                    $this->m_pr_po->po_circulation_sheet($Code,'SPK Edited');
            }

            $JsonStatus = $this->m_pr_po->GetRuleApproval_SPK_JsonStatus();
            $dataSave = array(
                'JobSpk' => $JobSpk,
                'Notes' => $Notes,
                'ID_pay_type' => $ID_pay_type,
                'Notes2' => $Notes2,
                'Status' => 1,
                'JsonStatus' => json_encode($JsonStatus),
            );
            $this->db->where('Code',$Code);
            $this->db->update('db_purchasing.po_create',$dataSave);

            // notification
              $CodeUrl = str_replace('/', '-', $Code);
              // $JsonStatus = json_decode($po_create[0]['JsonStatus'],true);
              $NIPApprovalNext = $JsonStatus[1]['NIP'];
              $NIP = $this->session->userdata('NIP');
              // Send Notif for next approval
                  $data = array(
                      'auth' => 's3Cr3T-G4N',
                      'Logging' => array(
                                      'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Created PO/SPK : '.$Code,
                                      'Description' => 'Please approve PO/SPK '.$Code,
                                      'URLDirect' => 'global/purchasing/transaction/spk/list/'.$CodeUrl,
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

                  // send email is holding or warek keatas
                       $this->m_master->send_email_budgeting_holding($NIPApprovalNext,'NA.4',$data['Logging']['URLDirect'],$data['Logging']['Description']);
        }
        else
        {
            $rs['Change'] = 1;
        }
        echo json_encode($rs);
    }

    public function cancel_created_submit_spk()
    {
        $rs = array('Status' => 1,'Change' => 0,'msg' => '');
        $Input = $this->getInputToken();
        $po_data = $Input['po_data']; // data yang di passing awal dari server ke client
        $arr_post_data_ID_po_detail =$Input['arr_post_data_ID_po_detail'];
        $PRRejectItem = $Input['PRRejectItem']; // data yang di passing awal dari server ke client
        $NoteDel = $Input['NoteDel'];

        $po_data = json_decode(json_encode($po_data),true);
        $po_create = $po_data['po_create'];
        $Code = $po_create[0]['Code'];
        $PRCode = $Input['PRCode'];

        $CheckPerubahanData = $this->m_pr_po->CheckPerubahanData_PO_Created($po_data);
        if ($CheckPerubahanData) {
            // check apakah data telah dibuat payment atau belum
                $G= $this->m_master->caribasedprimary('db_payment.payment','Code_po_create',$Code);
                if (count($G) == 0) {
                    // do change status pr item & kembalikan
                        $arr_post_data_ID_po_detail = json_decode(json_encode($arr_post_data_ID_po_detail),true);

                    // cek circulation sheet
                    if ($po_create[0]['Code'] != 0 || $po_create[0]['Code'] != '') {
                        $Desc = 'SPK Cancel<br>{'.$NoteDel.'}';
                        $Desc2 = 'SPK '.$Code.' Cancel<br>{'.$NoteDel.'}';
                        if ($PRRejectItem) {
                            $this->m_pr_po->ReturnAllBudgetFromPO($arr_post_data_ID_po_detail);
                            // cek for some item or all item
                                $cek_item = $this->m_pr_po->CekPRTo_Item_IN_PO($arr_post_data_ID_po_detail,$PRCode);
                                $__r = '';
                                if ($cek_item) {
                                   $__r = 'All';
                                }
                                else
                                {
                                    $__r = 'Some'; 
                                }

                            $Desc = 'SPK '.$Code.' & '.$__r.' ITEM PR have been Cancel<br>{'.$NoteDel.'}';
                            $Desc2 = 'SPK '.$Code.' & '.$__r.' ITEM PR have been Cancel<br>{'.$NoteDel.'}';

                            // update status pr jika all item cancel menjadi cancel
                                $G_item_pr = $this->m_master->caribasedprimary('db_budgeting.pr_detail','PRCode',$PRCode);
                                $bool = true;
                                for ($i=0; $i < count($G_item_pr); $i++) { 
                                     if ($G_item_pr[$i]['Status'] == 1) {
                                         break;
                                         $bool = false;
                                     }
                                }

                                if ($bool) {
                                    $arr_save = array(
                                        'Status' => 4,
                                    );

                                    $this->db->where('PRCode',$PRCode);
                                    $this->db->update('db_budgeting.pr_create',$arr_save);
                                }

                            // update status pr_status dan pr_status_detail
                                $this->m_pr_po->__Cancel_update_pr_status_pr_status_detail($PRCode,$arr_post_data_ID_po_detail);    
                        }
                        // insert to pr_circulation_sheet
                            $this->m_pr_po->po_circulation_sheet($Code,$Desc);
                            $this->m_pr_po->pr_circulation_sheet($PRCode,$Desc2);
                    }

                    $dataSave = array(
                        'Status' => 4,
                    );
                    
                    $this->db->where('Code',$Code);
                    $this->db->update('db_purchasing.po_create',$dataSave);
                }
                else
                {
                    $rs['Status'] = 0;
                    $rs['msg'] = 'Payment for The SPK : '.$Code.' has created, cant action!!!';
                }
        }
        else
        {
            $rs['Change'] = 1;
        }

        echo json_encode($rs);
    }

    public function pembayaran()
    {
      // get data bank rest/__Databank
          $data = array(
              'auth' => 's3Cr3T-G4N', 
          );
          $key = "UAP)(*";
          $token = $this->jwt->encode($data,$key);
          $G_data_bank = $this->m_master->apiservertoserver(base_url().'rest/__Databank',$token);
          $this->data['G_data_bank'] = $G_data_bank;
       $page['content'] = $this->load->view('page/'.$this->data['department'].'/transaksi/po/pembayaran',$this->data,true);
       $this->page_po($page); 
    }

    public function upload_file_Approve()
    {
       $rs = array('status' => 0,'msg' => '');
       $Input = $this->getInputToken();
       $Code = $Input['Code'];
       // check file sebelumnya, jika ada maka hapus
       $G_data = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$Code);
       $F_POPrint_Approve = $G_data[0]['POPrint_Approve'];
       if ($F_POPrint_Approve != '' && $F_POPrint_Approve != null) {
           $arr_file = (array) json_decode($F_POPrint_Approve,true);
           $filePath = 'budgeting\\po\\'.$arr_file[0]; // pasti ada file karena required
           $path = FCPATH.'uploads\\'.$filePath;
           unlink($path);
       }


       $POPrint_Approve = $this->m_master->uploadDokumenMultiple(uniqid(),'fileData',$path = './uploads/budgeting/po');
       $POPrint_Approve = json_encode($POPrint_Approve);
       $dataSave['POPrint_Approve']  = $POPrint_Approve;
       $this->db->where('Code',$Code);
       $this->db->update('db_purchasing.po_create',$dataSave);
       $rs['status'] = 1;
       echo json_encode($rs);
    }

}
