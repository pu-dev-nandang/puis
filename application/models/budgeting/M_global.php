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

}