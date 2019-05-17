<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_globalpage extends Globalclass {

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
         if (count($G_data) > 0) {
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

             $data['bool'] = $bool;
             $data['Code'] = $Code;
             $data['G_data'] = $G_data;
             if ($G_data[0]['TypeCreate'] == 1) { // PO
                $content = $this->load->view('global/budgeting/po/InfoPO',$data,true);
             }
             $this->temp($content);
         }
         else
         {
            show_404($log_error = TRUE); 
         }
            
    }

}