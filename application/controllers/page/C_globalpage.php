<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_globalpage extends Budgeting_Controler {

    public function temp($content)
    {
        parent::template($content);
    }

    public function testadi()
    {
        /*for ($i=0; $i <= 100 ; $i= $i + 5) {
            $dataSave = array(
                'discount' => $i,
            );
            $this->db->insert('db_finance.discount', $dataSave);
        }

        echo 'test';*/
        

        $client = new Client(new Version1X('//localhost:3000'));

        $client->initialize();
        // send message to connected clients
        $client->emit('update_notifikasi', ['update_notifikasi' => '1']);
        $client->close();
    }

    public function InfoPO($CodePO)
    {
        $Code = str_replace('-','/', $CodePO);
        /*
            1.Cek Code PO exist or not
            2.Cek User memiliki hubungan dengan Code PO tersebut,kecuali Finance & Purchasing
        */

         $G_data = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$Code);
         if (count($G_data) > 0 && $G_data[0]['TypeCreate'] == 1) {
             $bool = true;
             if ($this->session->userdata('IDdepartementNavigation') == 4 || $this->session->userdata('IDdepartementNavigation') == 9) {
                    $bool = true;
             }
             else{
                $bool = false;
             }

             if (!$bool) { // for user
                $JsonStatus = $G_data[0]['JsonStatus'];
                $arr = (array) json_decode($JsonStatus,true);
                $NIP = $this->session->userdata('NIP');
                for ($i=0; $i < count($arr); $i++) { 
                    $NIP_ = $arr[$i]['NIP'];
                    if ($NIP == $NIP_) {
                        $bool = true;
                        break;
                    }
                }
             }
             $G_pay_type = $this->m_master->showData_array('db_purchasing.pay_type');
             $data['G_pay_type'] = $G_pay_type;
             $data['bool'] = $bool;
             $data['Code'] = $Code;
             $data['G_data'] = $G_data;
             if ($G_data[0]['TypeCreate'] == 1) { // PO
                $content = $this->load->view('global/budgeting/po/InfoPO',$data,true);
                $this->temp($content);
             }
             else
             {
                show_404($log_error = TRUE); 
             }
            
         }
         else
         {
            show_404($log_error = TRUE); 
         }
            
    }

    public function InfoSPK($CodePO)
    {
        $Code = str_replace('-','/', $CodePO);
        /*
            1.Cek Code PO exist or not
            2.Cek User memiliki hubungan dengan Code PO tersebut,kecuali Finance & Purchasing
        */

         $G_data = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$Code);
         if (count($G_data) > 0 && $G_data[0]['TypeCreate'] == 2) {
             $bool = true;
             if ($this->session->userdata('IDdepartementNavigation') == 4 || $this->session->userdata('IDdepartementNavigation') == 9) {
                    $bool = true;
             }
             else{
                $bool = false;
             }

             if (!$bool) { // for user
                $JsonStatus = $G_data[0]['JsonStatus'];
                $arr = (array) json_decode($JsonStatus,true);
                $NIP = $this->session->userdata('NIP');
                for ($i=0; $i < count($arr); $i++) { 
                    $NIP_ = $arr[$i]['NIP'];
                    if ($NIP == $NIP_) {
                        $bool = true;
                        break;
                    }
                }
             }

             $G_pay_type = $this->m_master->showData_array('db_purchasing.pay_type');
             $data['G_pay_type'] = $G_pay_type;
             $data['bool'] = $bool;
             $data['Code'] = $Code;
             $data['G_data'] = $G_data;
             if ($G_data[0]['TypeCreate'] == 2) { // PO
                $content = $this->load->view('global/budgeting/po/InfoSPK',$data,true);
                $this->temp($content);
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

    public function InfoSPB($CodeSPB)
    {
        $Code = str_replace('-','/', $CodeSPB);
        $ex = explode('/', $Code);
        $rsCode = '';
        for ($i=0; $i < count($ex); $i++) { 
            $deli = ($i == 1) ? '-' : '/';
            if ($i == count($ex) - 1) {
                $rsCode .= $ex[$i];
            }
            else
            {
                $rsCode .= $ex[$i].$deli;
            }
             
        }

        /*
            01-UAP-PURCHASING-SPB-VII-2019
            jadikan ke 01/UAP-PURCHASING/SPB/VII/2019
            1.Cek Code SPB exist or not
            2.Cek User memiliki hubungan dengan Code SPB tersebut,kecuali Finance
            3.Cek Code SPB dari PO/SPK atau tidak
                jika dari PO maka SPB PO dan jika tidak maka dari user
        */
         $sql = 'select a.ID as ID_payment_,a.Type,a.Code,a.Code_po_create,a.Departement,a.UploadIOM,a.NoIOM,a.JsonStatus,a.Notes,a.Status,a.Print_Approve,a.CreatedBy,a.CreatedAt,a.LastUpdatedBy,a.LastUpdatedAt,b.* from db_payment.payment as a join db_payment.spb as b on a.ID = b.ID_payment where a.Type = "Spb" and a.Code = ?';
         $query=$this->db->query($sql, array($rsCode))->result_array();
         $G_data = $query;
         if (count($G_data) > 0) {
             $bool = true;
             if ($this->session->userdata('IDdepartementNavigation') == 9) {
                    $bool = true;
             }
             else{
                $bool = false;
             }

             if (!$bool) { // for user
                $JsonStatus = $G_data[0]['JsonStatus'];
                $arr = (array) json_decode($JsonStatus,true);
                $NIP = $this->session->userdata('NIP');
                for ($i=0; $i < count($arr); $i++) { 
                    $NIP_ = $arr[$i]['NIP'];
                    if ($NIP == $NIP_) {
                        $bool = true;
                        break;
                    }
                }
             }

             $data = array(
                 'auth' => 's3Cr3T-G4N', 
             );
             $key = "UAP)(*";
             $token = $this->jwt->encode($data,$key);
             $G_data_bank = $this->m_master->apiservertoserver(base_url().'rest/__Databank',$token);
             $data['G_data_bank'] = $G_data_bank;
             $data['bool'] = $bool;
             $data['Code'] = $rsCode;
             $data['Code_po_create'] = $G_data[0]['Code_po_create'];
             $data['G_data'] = $G_data;
             if ($G_data[0]['Code_po_create'] != '' && $G_data[0]['Code_po_create'] != null) {
                 $content = $this->load->view('global/budgeting/spb/InfoSPB_PO',$data,true);
                 $this->temp($content);
             }
             else
             {
                $content = $this->load->view('global/budgeting/spb/InfoSPB_User',$data,true);
                $this->temp($content);
             }
             
             
         }
         else
         {
            show_404($log_error = TRUE); 
         }
    }

    public function InfoBA($TokenPayment)
    {
        try {
            $key = "UAP)(*";
            $token = $this->jwt->decode($TokenPayment,$key);
            $ID_payment = $token;
            $sql = 'select a.ID as ID_payment_,a.Type,a.Code,a.Code_po_create,a.Departement,a.UploadIOM,a.NoIOM,a.JsonStatus,a.Notes,a.Status,a.Print_Approve,a.CreatedBy,a.CreatedAt,a.LastUpdatedBy,a.LastUpdatedAt,b.* from db_payment.payment as a join db_payment.bank_advance as b on a.ID = b.ID_payment where a.Type = "Bank Advance" and a.ID = ?';
            $query=$this->db->query($sql, array($ID_payment))->result_array();
            $G_data = $query;
            if (count($G_data) > 0) {
                $bool = true;
                if ($this->session->userdata('IDdepartementNavigation') == 9) {
                       $bool = true;
                }
                else{
                   $bool = false;
                }

                if (!$bool) { // for user
                   $JsonStatus = $G_data[0]['JsonStatus'];
                   $arr = (array) json_decode($JsonStatus,true);
                   $NIP = $this->session->userdata('NIP');
                   for ($i=0; $i < count($arr); $i++) { 
                       $NIP_ = $arr[$i]['NIP'];
                       if ($NIP == $NIP_) {
                           $bool = true;
                           break;
                       }
                   }
                }

                $data = array(
                    'auth' => 's3Cr3T-G4N', 
                );
                $key = "UAP)(*";
                $token = $this->jwt->encode($data,$key);
                $G_data_bank = $this->m_master->apiservertoserver(base_url().'rest/__Databank',$token);
                $data['G_data_bank'] = $G_data_bank;
                $data['bool'] = $bool;
                $data['ID_payment'] = $ID_payment;
                $data['Code_po_create'] = $G_data[0]['Code_po_create'];
                $data['G_data'] = $G_data;
                if ($G_data[0]['Code_po_create'] != '' && $G_data[0]['Code_po_create'] != null) {
                    $content = $this->load->view('global/budgeting/ba/Infoba_PO',$data,true);
                    $this->temp($content);
                }
                else
                {
                   $content = $this->load->view('global/budgeting/ba/Infoba_User',$data,true);
                   $this->temp($content);
                }
                
                
            }
            else
            {
               show_404($log_error = TRUE); 
            }

        } catch (Exception $e) {
            show_404($log_error = TRUE); 
        }

    }

    public function InfoCA($TokenPayment)
    {
        try {
            $key = "UAP)(*";
            $token = $this->jwt->decode($TokenPayment,$key);
            $ID_payment = $token;
            $sql = 'select a.ID as ID_payment_,a.Type,a.Code,a.Code_po_create,a.Departement,a.UploadIOM,a.NoIOM,a.JsonStatus,a.Notes,a.Status,a.Print_Approve,a.CreatedBy,a.CreatedAt,a.LastUpdatedBy,a.LastUpdatedAt,b.* from db_payment.payment as a join db_payment.cash_advance as b on a.ID = b.ID_payment where a.Type = "Cash Advance" and a.ID = ?';
            $query=$this->db->query($sql, array($ID_payment))->result_array();
            $G_data = $query;
            if (count($G_data) > 0) {
                $bool = true;
                if ($this->session->userdata('IDdepartementNavigation') == 9) {
                       $bool = true;
                }
                else{
                   $bool = false;
                }

                if (!$bool) { // for user
                   $JsonStatus = $G_data[0]['JsonStatus'];
                   $arr = (array) json_decode($JsonStatus,true);
                   $NIP = $this->session->userdata('NIP');
                   for ($i=0; $i < count($arr); $i++) { 
                       $NIP_ = $arr[$i]['NIP'];
                       if ($NIP == $NIP_) {
                           $bool = true;
                           break;
                       }
                   }
                }

                $data = array(
                    'auth' => 's3Cr3T-G4N', 
                );
                $key = "UAP)(*";
                $token = $this->jwt->encode($data,$key);
                $G_data_bank = $this->m_master->apiservertoserver(base_url().'rest/__Databank',$token);
                $data['G_data_bank'] = $G_data_bank;
                $data['bool'] = $bool;
                $data['ID_payment'] = $ID_payment;
                $data['Code_po_create'] = $G_data[0]['Code_po_create'];
                $data['G_data'] = $G_data;
                if ($G_data[0]['Code_po_create'] != '' && $G_data[0]['Code_po_create'] != null) {
                    $content = $this->load->view('global/budgeting/cashadvance/Infoca_PO',$data,true);
                    $this->temp($content);
                }
                else
                {
                   $content = $this->load->view('global/budgeting/cashadvance/Infoca_User',$data,true);
                   $this->temp($content);
                }
                
                
            }
            else
            {
               show_404($log_error = TRUE); 
            }

        } catch (Exception $e) {
            show_404($log_error = TRUE); 
        }

    }

}