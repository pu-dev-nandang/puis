<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_global extends CI_Model {


    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('budgeting/m_budgeting');
        $this->load->model('budgeting/m_pr_po');
        $this->load->model('budgeting/m_spb');
    }

    public function __change_payment_type($ID_payment)
    {
        $G_data = $this->m_master->caribasedprimary('db_payment.payment','ID',$ID_payment);
        if (count($G_data) > 0 ) {
            // check type 
            $Type = $G_data[0]['Type'];
            switch ($Type) {
                 case 'Spb':
                     // remove data & file spb
                     $G_data_ = $this->m_master->caribasedprimary('db_payment.spb','ID_payment',$ID_payment);
                     // get file to remove
                     if ($G_data_[0]['UploadInvoice'] != '' && $G_data_[0]['UploadInvoice'] != null) {
                        $arr_file = (array) json_decode($G_data_[0]['UploadInvoice'],true);
                        $filePath = 'budgeting\\spb\\'.$arr_file[0]; // pasti ada file karena required
                        $path = FCPATH.'uploads\\'.$filePath;
                        unlink($path);
                     }

                     if ($G_data_[0]['UploadTandaTerima'] != '' && $G_data_[0]['UploadTandaTerima'] != null) {
                         $arr_file = (array) json_decode($G_data_[0]['UploadTandaTerima'],true);
                         $filePath = 'budgeting\\spb\\'.$arr_file[0]; // pasti ada file karena required
                         $path = FCPATH.'uploads\\'.$filePath;
                         unlink($path);
                     }

                     $this->db->where('ID_payment',$ID_payment);
                     $this->db->delete('db_payment.spb');
                     break;
                 case 'Bank Advance':
                     // remove data & file spb
                     $G_data_ = $this->m_master->caribasedprimary('db_payment.bank_advance','ID_payment',$ID_payment);
                     $ID_bank_advance = $G_data_[0]['ID'];
                     $G_data_realisasi = $this->m_master->caribasedprimary('db_payment.bank_advance_realisasi','ID_bank_advance',$ID_bank_advance);
                     for ($i=0; $i < count($G_data_realisasi); $i++) { 
                         if ($G_data_realisasi[$i]['UploadInvoice'] != '' && $G_data_realisasi[$i]['UploadInvoice'] != null) {
                             $arr_file = (array) json_decode($G_data_realisasi[0]['UploadInvoice'],true);
                             $filePath = 'budgeting\\bankadvance\\'.$arr_file[0]; // pasti ada file karena required
                             $path = FCPATH.'uploads\\'.$filePath;
                             unlink($path);
                         }

                         if ($G_data_realisasi[$i]['UploadTandaTerima'] != '' && $G_data_realisasi[$i]['UploadTandaTerima'] != null) {
                             $arr_file = (array) json_decode($G_data_realisasi[0]['UploadTandaTerima'],true);
                             $filePath = 'budgeting\\bankadvance\\'.$arr_file[0]; // pasti ada file karena required
                             $path = FCPATH.'uploads\\'.$filePath;
                             unlink($path);
                         }
                     }

                     $G_data_detail = $this->m_master->caribasedprimary('db_payment.bank_advance_detail','ID_bank_advance',$ID_bank_advance);
                     for ($i=0; $i < count($G_data_detail); $i++) { 
                        $ID_bank_advance_detail = $G_data_detail[$i]['ID'];
                        $this->db->where('ID_bank_advance_detail',$ID_bank_advance_detail);
                        $this->db->delete('db_payment.bank_advance_realisasi_detail');
                     }

                     $this->db->where('ID_bank_advance',$ID_bank_advance);
                     $this->db->delete('db_payment.bank_advance_realisasi');

                     $this->db->where('ID_bank_advance',$ID_bank_advance);
                     $this->db->delete('db_payment.bank_advance_detail');

                     $this->db->where('ID',$ID_bank_advance);
                     $this->db->delete('db_payment.bank_advance');
                     break;
                 case 'Cash Advance':
                     // remove data & file spb
                     $G_data_ = $this->m_master->caribasedprimary('db_payment.cash_advance','ID_payment',$ID_payment);
                     $ID_cash_advance = $G_data_[0]['ID'];
                     $G_data_realisasi = $this->m_master->caribasedprimary('db_payment.cash_advance_realisasi','ID_cash_advance',$ID_cash_advance);
                     for ($i=0; $i < count($G_data_realisasi); $i++) { 
                         if ($G_data_realisasi[$i]['UploadInvoice'] != '' && $G_data_realisasi[$i]['UploadInvoice'] != null) {
                             $arr_file = (array) json_decode($G_data_realisasi[0]['UploadInvoice'],true);
                             $filePath = 'budgeting\\cashadvance\\'.$arr_file[0]; // pasti ada file karena required
                             $path = FCPATH.'uploads\\'.$filePath;
                             unlink($path);
                         }

                         if ($G_data_realisasi[$i]['UploadTandaTerima'] != '' && $G_data_realisasi[$i]['UploadTandaTerima'] != null) {
                             $arr_file = (array) json_decode($G_data_realisasi[0]['UploadTandaTerima'],true);
                             $filePath = 'budgeting\\cashadvance\\'.$arr_file[0]; // pasti ada file karena required
                             $path = FCPATH.'uploads\\'.$filePath;
                             unlink($path);
                         }
                     }

                     $G_data_detail = $this->m_master->caribasedprimary('db_payment.cash_advance_detail','ID_cash_advance',$ID_cash_advance);
                     for ($i=0; $i < count($G_data_detail); $i++) { 
                        $ID_cash_advance_detail = $G_data_detail[$i]['ID'];
                        $this->db->where('ID_cash_advance_detail',$ID_cash_advance_detail);
                        $this->db->delete('db_payment.cash_advance_realisasi_detail');
                     }

                     $this->db->where('ID_cash_advance',$ID_cash_advance);
                     $this->db->delete('db_payment.cash_advance_realisasi');

                     $this->db->where('ID_cash_advance',$ID_cash_advance);
                     $this->db->delete('db_payment.cash_advance_detail');

                     $this->db->where('ID',$ID_bank_advance);
                     $this->db->delete('db_payment.cash_advance');
                     break;    
                 default:
                     # code...
                     break;
             } 
        }
    }

    public function JsonStatusRealisasi()
    {
        // approval oleh kasubag finance
        $arr = array();
        // insert created by
        $arr[] = array(
          'NIP' => $this->session->userdata('NIP'),
          'Status' => 1,
          'ApproveAt' => date('Y-m-d H:i:s'),
          'Representedby' => '',
          'Visible' => 'Yes',
          'NameTypeDesc' => 'Requested by',
        );

        $sql = "SELECT a.NIP,a.Name,SPLIT_STR(a.PositionMain, '.', 1) as PositionMain1,
                       SPLIT_STR(a.PositionMain, '.', 2) as PositionMain2,
                             a.StatusEmployeeID
                FROM   db_employees.employees as a
                where SPLIT_STR(a.PositionMain, '.', 1) = 9 and SPLIT_STR(a.PositionMain, '.', 2) = 12";
                                                        // Finance                                  // kasubag
        $query=$this->db->query($sql, array())->result_array();        

        if (count($query) > 0) {
            $arr[] = array(
              'NIP' => $query[0]['NIP'] ,
              'Status' => 0,
              'ApproveAt' => '',
              'Representedby' => '',
              'Visible' => 'Yes',
              'NameTypeDesc' => 'Approval by',
            );
        }
        else
        {
            die();
        }
        return $arr;
    }

    public function Get_data_payment_user($ID)
    {
        $arr = array(
            'payment'=> array(),
        );
        $sql = 'select a.*,b.NameDepartement from db_payment.payment as a 
                join
                (
                select * from (
                                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                                UNION
                                select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                                UNION
                                select CONCAT("FT.",ID) as ID, NameEng as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                                ) aa
                ) as b on a.Departement = b.ID 
        where a.ID = ?';
        $query = $this->db->query($sql, array($ID))->result_array();
        if (count($query) > 0) {
            for ($i=0; $i < count($query); $i++) { 
                $JsonStatus = $query[$i]['JsonStatus'];
                // Get ID
                $ID = $query[$i]['ID'];
                if ($JsonStatus != '' && $JsonStatus != null) {
                    // decode first
                    $JsonStatus = json_decode($JsonStatus,true);
                    for ($j=0; $j < count($JsonStatus); $j++) { 
                        $NIP = $JsonStatus[$j]['NIP'];
                        $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
                        $JsonStatus[$j]['Name'] = $G_emp[0]['Name'];
                    }

                    $JsonStatus = json_encode($JsonStatus);
                    // update JsonStatus for show
                    $query[$i]['JsonStatus'] = $JsonStatus;
                    $Type = $query[$i]['Type'];
                    $Detail = array();
                    switch ($Type) {
                        case 'Spb':
                            // $G_dt = $this->m_master->caribasedprimary('db_payment.spb','ID_payment',$ID);
                            $sql__ = 'select a.*,b.NamaSupplier,b.Website,b.PICName,b.Alamat
                                      from db_payment.spb as a left join db_purchasing.m_supplier as b on a.CodeSupplier = b.CodeSupplier
                                      where a.ID_payment = ?
                                    ';
                            $G_dt = $this->db->query($sql__, array($ID))->result_array();
                            for ($j=0; $j < count($G_dt); $j++) {
                               $ID_budget_left =  $G_dt[$j]['ID_budget_left'];
                               $ID_spb = $G_dt[$j]['ID'];
                               $__Detail = $this->m_master->caribasedprimary('db_payment.spb_detail','ID_spb',$ID_spb);
                               for ($k=0; $k < count($__Detail); $k++) {
                                   $ID_budget_left =  $__Detail[$k]['ID_budget_left'];
                                   $__Detail[$k]['DataPostBudget'] = $this->m_pr_po->Get_DataBudgeting_by_ID_budget_left($ID_budget_left);
                               }

                               $G_dt[$j]['Detail'] = $__Detail;
                            }
                            $Detail = $G_dt;
                            break;
                        case 'Bank Advance':
                            $G_dt = $this->m_master->caribasedprimary('db_payment.bank_advance','ID_payment',$ID);
                            $tot = count($G_dt);
                            for ($j=0; $j < $tot; $j++) { 
                                // get bank_advance_detail
                                $ID_bank_advance = $G_dt[$j]['ID'];
                                $__Detail = $this->m_master->caribasedprimary('db_payment.bank_advance_detail','ID_bank_advance',$ID_bank_advance);
                                for ($k=0; $k < count($__Detail); $k++) {
                                    $ID_budget_left =  $__Detail[$k]['ID_budget_left'];
                                    $__Detail[$k]['DataPostBudget'] = $this->m_pr_po->Get_DataBudgeting_by_ID_budget_left($ID_budget_left);

                                    $__Detail_Realisasi = $this->m_master->caribasedprimary('db_payment.bank_advance_realisasi_detail','ID_bank_advance_detail',$__Detail[$k]['ID']);
                                   $__Detail[$k]['Realisasi'] =  $__Detail_Realisasi;
                                }

                                $__Realisasi = $this->m_master->caribasedprimary('db_payment.bank_advance_realisasi','ID_bank_advance',$ID_bank_advance);
                                for ($l=0; $l < count($__Realisasi); $l++) { 
                                   $__JsonStatus = $__Realisasi[$l]['JsonStatus'];
                                   $__JsonStatus_de = json_decode($__JsonStatus,true);
                                   for ($m=0; $m < count($__JsonStatus_de); $m++) { 
                                       $NIP_Re = $__JsonStatus_de[$m]['NIP'];
                                       $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP_Re);
                                       $__JsonStatus_de[$m]['Name'] = $G_emp[0]['Name'];
                                   }

                                    $__JsonStatus = json_encode($__JsonStatus_de);
                                    $__Realisasi[$l]['JsonStatus'] = $__JsonStatus;
                                }

                                $G_dt[$j]['Detail'] = $__Detail;
                                $G_dt[$j]['Realisasi'] = $__Realisasi;
                            }
                            $Detail = $G_dt;
                            break;
                        case 'Cash Advance':
                            $G_dt = $this->m_master->caribasedprimary('db_payment.cash_advance','ID_payment',$ID);
                            $tot2 = count($G_dt);
                            for ($j=0; $j < $tot2; $j++) { 
                                // get bank_advance_detail
                                $ID_cash_advance = $G_dt[$j]['ID'];
                                $__Detail = $this->m_master->caribasedprimary('db_payment.cash_advance_detail','ID_cash_advance',$ID_cash_advance);
                                for ($k=0; $k < count($__Detail); $k++) {
                                    $ID_budget_left =  $__Detail[$k]['ID_budget_left'];
                                    $__Detail[$k]['DataPostBudget'] = $this->m_pr_po->Get_DataBudgeting_by_ID_budget_left($ID_budget_left);

                                    $__Detail_Realisasi = $this->m_master->caribasedprimary('db_payment.cash_advance_realisasi_detail','ID_cash_advance_detail',$__Detail[$k]['ID']);
                                   $__Detail[$k]['Realisasi'] =  $__Detail_Realisasi;
                                }
                                $__Realisasi = $this->m_master->caribasedprimary('db_payment.cash_advance_realisasi','ID_cash_advance',$ID_cash_advance);

                                for ($l=0; $l < count($__Realisasi); $l++) { 
                                   $__JsonStatus = $__Realisasi[$l]['JsonStatus'];
                                   $__JsonStatus_de = json_decode($__JsonStatus,true);
                                   for ($m=0; $m < count($__JsonStatus_de); $m++) { 
                                       $NIP_Re = $__JsonStatus_de[$m]['NIP'];
                                       $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP_Re);
                                       $__JsonStatus_de[$m]['Name'] = $G_emp[0]['Name'];
                                   }

                                    $__JsonStatus = json_encode($__JsonStatus_de);
                                    $__Realisasi[$l]['JsonStatus'] = $__JsonStatus;
                                }
                                
                                $G_dt[$j]['Detail'] = $__Detail;
                                $G_dt[$j]['Realisasi'] = $__Realisasi;
                            }
                            $Detail = $G_dt;
                            break;
                        case 'Petty Cash':
                            $G_dt = $this->m_master->caribasedprimary('db_payment.petty_cash','ID_payment',$ID);
                            $tot2 = count($G_dt);
                            for ($j=0; $j < $tot2; $j++) { 
                                // get bank_advance_detail
                                $ID_petty_cash = $G_dt[$j]['ID'];
                                $__Detail = $this->m_master->caribasedprimary('db_payment.petty_cash_detail','ID_petty_cash',$ID_petty_cash);
                                for ($k=0; $k < count($__Detail); $k++) {
                                    $ID_budget_left =  $__Detail[$k]['ID_budget_left'];
                                    $__Detail[$k]['DataPostBudget'] = $this->m_pr_po->Get_DataBudgeting_by_ID_budget_left($ID_budget_left);

                                    $__Detail_Realisasi = $this->m_master->caribasedprimary('db_payment.petty_cash_realisasi_detail','ID_petty_cash_detail',$__Detail[$k]['ID']);
                                   $__Detail[$k]['Realisasi'] =  $__Detail_Realisasi;
                                }
                                $__Realisasi = $this->m_master->caribasedprimary('db_payment.petty_cash_realisasi','ID_petty_cash',$ID_petty_cash);

                                for ($l=0; $l < count($__Realisasi); $l++) { 
                                   $__JsonStatus = $__Realisasi[$l]['JsonStatus'];
                                   $__JsonStatus_de = json_decode($__JsonStatus,true);
                                   for ($m=0; $m < count($__JsonStatus_de); $m++) { 
                                       $NIP_Re = $__JsonStatus_de[$m]['NIP'];
                                       $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP_Re);
                                       $__JsonStatus_de[$m]['Name'] = $G_emp[0]['Name'];
                                   }

                                    $__JsonStatus = json_encode($__JsonStatus_de);
                                    $__Realisasi[$l]['JsonStatus'] = $__JsonStatus;
                                }
                                
                                $G_dt[$j]['Detail'] = $__Detail;
                                $G_dt[$j]['Realisasi'] = $__Realisasi;
                            }
                            $Detail = $G_dt;
                            break;    
                        default:
                            # code...
                            break;
                    }

                    $query[$i]['Detail'] = $Detail;
                }

                // add good_receipt_spb & good_receipt_detail
                $Good_Receipt = array();
                $G_good_receipt_spb = $this->m_master->caribasedprimary('db_purchasing.good_receipt_spb','ID_payment',$ID); // spb , cash advance, bank advance
                for ($j=0; $j < count($G_good_receipt_spb); $j++) { 
                    $ID_good_receipt_spb = $G_good_receipt_spb[$j]['ID'];
                    $G_good_receipt_detail = $this->m_master->caribasedprimary('db_purchasing.good_receipt_detail','ID_good_receipt_spb',$ID_good_receipt_spb);
                    $G_good_receipt_spb[$j]['Detail'] = $G_good_receipt_detail;
                }

                $Good_Receipt = $G_good_receipt_spb;
                $query[$i]['Good_Receipt'] = $Good_Receipt;

                // check ap udah bayar atau belum
                $FinanceAP = array();
                $FinanceAP = $this->m_master->caribasedprimary('db_budgeting.ap','ID_payment',$ID);

                $query[$i]['FinanceAP'] = $FinanceAP;
            }
            $arr['payment']=$query;
        }
        
        return $arr;
    }

    public function BackBudgetToBeforeCreate($ID_payment,$Year,$Departement)
    {
        $DtExisting = $this->Get_data_payment_user($ID_payment);
        $DtExisting = $DtExisting['payment'];
        $DetailPayment = $DtExisting[0]['Detail'];
        $DetailTypePayment = $DetailPayment[0]['Detail'];
        $G_data = $DetailTypePayment;
        $getData = $this->m_budgeting->get_budget_remaining($Year,$Departement);
        $temp = array();
        for ($i=0; $i < count($getData); $i++) { 
            $CodePostRealisasi = $getData[$i]['CodePostRealisasi'];
            $Using = $getData[$i]['Using'];
            $Value = $getData[$i]['Value'];
            for ($j=0; $j < count($G_data); $j++) { 
               $CodePostRealisasi_ = $G_data[$j]['DataPostBudget'][0]['CodePostRealisasi'];
               if ($CodePostRealisasi == $CodePostRealisasi_) {
                   $SubTotal = $G_data[$j]['Invoice'];
                   $Cost1 = $SubTotal;

                   $Using = $Using - $Cost1;
                   $getData[$i]['Using'] = $Using;
                   $bool2 = false;
                   for ($m=0; $m < count($temp); $m++) { 
                       if ($temp[$m] == $getData[$i]['ID']) {
                           $bool2 = true;
                           break;
                       }
                   }

                   if (!$bool2) {
                       $temp[] = $getData[$i]['ID'];
                   }

                   break;
               }
            }
        }

        // update to database
            for ($i=0; $i < count($temp); $i++) { 
                $ID = $temp[$i];    
                for ($j=0; $j <count($getData); $j++) { 
                    $ID_ = $getData[$j]['ID'];
                    if ($ID == $ID_) {
                       $dataSave = array(
                        'Using' => $getData[$j]['Using'],
                       );
                       $this->db->where('ID',$ID);
                       $this->db->update('db_budgeting.budget_left', $dataSave);
                       break;
                    }
                }
            }
    }

}