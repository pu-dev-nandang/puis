<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_transaksi extends Vreservation_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
        $this->load->library('JWT');
        $this->load->model('master/m_master');
        $this->load->model('vreservation/m_reservation');
    }


    public function booking_create()
    {
        $content = $this->load->view($this->pathView.'transaksi/booking','',true);
        $this->temp($content);
    }

    public function add_save_transaksi()
    {
        $input = $this->getInputToken();
        $uploadFile = $this->uploadFfile(mt_rand());
        $filename = '';
        if (is_array($uploadFile)) {
            $filename = $uploadFile['file_name'];
        }

        $Start = date("Y-m-d H:i:s", strtotime($input['date'].$input['Start']));
        $End = date("Y-m-d H:i:s", strtotime($input['date'].$input['End']));

        $time = $this->m_master->countTimeQuery($End, $Start);
        $time = $time[0]['time'];
        $time = explode(':', $time);
        $time = ($time[0] * 60) + $time[1];
        $Colspan = $time / 30;
        $Colspan = (int)$Colspan;
        $a = $time % 30;
        if ($a > 0) {
            $Colspan++;
        }

        $ID_equipment_add = '';
        if (is_array($input['chk_e_additional'])) {
            $ID_equipment_add = implode(',', $input['chk_e_additional']);
        }
        
        $ID_add_personel = '';
        if (is_array($input['chk_person_support'])) {
            $ID_add_personel = implode(',', $input['chk_person_support']);
        }

        // check data bentrok dengan jam lain
        $chk = $this->m_reservation->checkBentrok($Start,$End,$input['chk_e_multiple'],$input['Room']);
        if ($chk) {
            $Multiple = '';
            if (is_array($input['chk_e_multiple'])) {
                for ($i=0; $i < count($input['chk_e_multiple']); $i++) { 
                   if ($i == 0) {
                        $dataSave = array(
                            'Start' => $Start,
                            'End' => $End,
                            'Time' => $time,
                            'Colspan' => $Colspan,
                            'Agenda' => $input['Agenda'],
                            'Room' => $input['Room'],
                            'ID_equipment_add' => $ID_equipment_add,
                            'ID_add_personel' => $ID_add_personel,
                            'Req_date' => date('Y-m-d'),
                            'CreatedBy' => $this->session->userdata('NIP'),
                        );
                        $this->db->insert('db_reservation.t_booking', $dataSave);
                        $insert_id = $this->db->insert_id();
                        $Multiple = $insert_id;

                        $dataSave = array(
                            'Multiple' => $insert_id,
                        );
                        $this->db->where('ID', $insert_id);
                        $this->db->update('db_reservation.t_booking', $dataSave);

                       $get = $input['chk_e_multiple'][$i];
                       $Start = date("Y-m-d H:i:s", strtotime($get.$input['Start']));
                       $End = date("Y-m-d H:i:s", strtotime($get.$input['End']));
                        
                       $dataSave = array(
                           'Start' => $Start,
                           'End' => $End,
                           'Time' => $time,
                           'Colspan' => $Colspan,
                           'Agenda' => $input['Agenda'],
                           'Room' => $input['Room'],
                           'ID_equipment_add' => $ID_equipment_add,
                           'ID_add_personel' => $ID_add_personel,
                           'Req_date' => date('Y-m-d'),
                           'CreatedBy' => $this->session->userdata('NIP'),
                           'Multiple' => $Multiple
                       );
                       $this->db->insert('db_reservation.t_booking', $dataSave);

                    }
                    else
                    {
                        $get = $input['chk_e_multiple'][$i];
                        $Start = date("Y-m-d H:i:s", strtotime($get.$input['Start']));
                        $End = date("Y-m-d H:i:s", strtotime($get.$input['End']));
                        $dataSave = array(
                            'Start' => $Start,
                            'End' => $End,
                            'Time' => $time,
                            'Colspan' => $Colspan,
                            'Agenda' => $input['Agenda'],
                            'Room' => $input['Room'],
                            'ID_equipment_add' => $ID_equipment_add,
                            'ID_add_personel' => $ID_add_personel,
                            'Req_date' => date('Y-m-d'),
                            'CreatedBy' => $this->session->userdata('NIP'),
                            'Multiple' => $Multiple,
                        );
                        $this->db->insert('db_reservation.t_booking', $dataSave);
                    }
                }

            }
            else
            {
                $dataSave = array(
                    'Start' => $Start,
                    'End' => $End,
                    'Time' => $time,
                    'Colspan' => $Colspan,
                    'Agenda' => $input['Agenda'],
                    'Room' => $input['Room'],
                    'ID_equipment_add' => $ID_equipment_add,
                    'ID_add_personel' => $ID_add_personel,
                    'Req_date' => date('Y-m-d'),
                    'CreatedBy' => $this->session->userdata('NIP'),
                );
                $this->db->insert('db_reservation.t_booking', $dataSave);
            }
            echo json_encode(array('msg' => 'The Proses Finish','status' => 1));
        }
        else
        {
            echo json_encode(array('msg' => 'Your schedule is Conflict Please check.','status' => 0));
        }

        
    }

    // mt_rand()

    public function uploadFfile($name)
    {
         // upload file
         $filename = md5($name);
         $config['upload_path']   = './uploads/vreservation/';
         $config['overwrite'] = TRUE; 
         $config['allowed_types'] = '*'; 
         $config['file_name'] = $filename;
         //$config['max_size']      = 100; 
         //$config['max_width']     = 300; 
         //$config['max_height']    = 300;  
         $this->load->library('upload', $config);
            
         if ( ! $this->upload->do_upload('fileData')) {
            return $error = $this->upload->display_errors(); 
            //$this->load->view('upload_form', $error); 
         }
            
         else { 
           return $data =  $this->upload->data(); 
            //$this->load->view('upload_success', $data); 
         }
    }



}
