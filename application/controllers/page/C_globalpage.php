<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

// class C_globalpage extends Globalclass {
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

    public function create_spb_by_po($POCode)
    {
        /*
            Syarat halaman bisa di buka
            1.Code PO ada pada database
            2.POCode dengan status all approve
            3.Cek User memiliki hubungan dengan Code PO tersebut,kecuali Finance & Purchasing

            Note : untuk PO yang sudah dibuat spbnya diperbolehkan untuk create spb dengan function ini dan auto TypeInvoice
        */
          $POCode = str_replace('-','/', $POCode);  
          $G_data = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$POCode);
          // status all aprove dengan value 2
          $bool = true;
          if ($this->session->userdata('IDdepartementNavigation') == 4 || $this->session->userdata('IDdepartementNavigation') == 9) {
                 $bool = true;
          }
          else{
             $bool = false;
          }

          if (count($G_data) > 0  && $bool  ) {
              if ($G_data[0]['Status'] == 2) {
                  $sql = 'select * from db_purchasing.spb_created where Code_po_create = ? order by ID desc limit 1';
                  $query=$this->db->query($sql, array($POCode))->result_array();
                  $data['DT_SPB_Exist'] = $query;
                  $data['POCode'] = $POCode;
                  $content = $this->load->view('global/budgeting/spb/create_new_spb',$data,true);
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

}