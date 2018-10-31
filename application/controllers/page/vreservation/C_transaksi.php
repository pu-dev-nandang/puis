<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_transaksi extends Vreservation_Controler {

    // private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
        $this->load->library('JWT');
        // $this->load->model('master/m_master');
        // $this->load->model('vreservation/m_reservation');
    }


    public function booking_create()
    {
        $content = $this->load->view($this->pathView.'transaksi/booking',$this->data,true);
        $this->temp($content);
    }

    public function uploadDokumenMultiple($filename)
    {
        $path = './uploads/vreservation';
        // Count total files
        $countfiles = count($_FILES['fileDataMarkomm']['name']);
      
      $output = array();
      // Looping all files
      for($i=0;$i<$countfiles;$i++){
            $config = array();
            if(!empty($_FILES['fileDataMarkomm']['name'][$i])){
     
              // Define new $_FILES array - $_FILES['file']
              $_FILES['file']['name'] = $_FILES['fileDataMarkomm']['name'][$i];
              $_FILES['file']['type'] = $_FILES['fileDataMarkomm']['type'][$i];
              $_FILES['file']['tmp_name'] = $_FILES['fileDataMarkomm']['tmp_name'][$i];
              $_FILES['file']['error'] = $_FILES['fileDataMarkomm']['error'][$i];
              $_FILES['file']['size'] = $_FILES['fileDataMarkomm']['size'][$i];

              // Set preference
              $config['upload_path'] = $path.'/';
              $config['allowed_types'] = '*';
              $config['overwrite'] = TRUE; 
              $no = $i + 1;
              $config['file_name'] = $filename.'_'.$no;

              $filenameUpload = $_FILES['file']['name'];
              $ext = pathinfo($filenameUpload, PATHINFO_EXTENSION);

              // $filenameNew = $filename.'_'.$no.'.pdf';
              $filenameNew = $filename.'_'.$no.'_'.mt_rand().'.'.$ext;
              // print_r($_FILES['file']['type']);

     
              //Load upload library
              $this->load->library('upload',$config); 
              $this->upload->initialize($config);
     
              // File upload
              if($this->upload->do_upload('file')){
                // Get data about the file
                $uploadData = $this->upload->data();
                $filePath = $uploadData['file_path'];
                $filename_uploaded = $uploadData['file_name'];
                // rename file
                $old = $filePath.'/'.$filename_uploaded;
                $new = $filePath.'/'.$filenameNew;

                rename($old, $new);

                $output[] = $filenameNew;
              }
            }
        }
        return $output;
    }

    public function add_save_transaksi()
    {
        $input = $this->getInputToken();
        $uploadFile = $this->uploadFfile(mt_rand());
        $filename = '';
        if (is_array($uploadFile)) {
            $filename = $uploadFile['file_name'];
        }

        if (array_key_exists('fileDataMarkomm',$_FILES)) {
            // upload file markomm
            $uploadFile2 = $this->uploadDokumenMultiple('GraphicDesign');
            $filenamemarkomm = ''; 
            if (is_array($uploadFile2)) {
                $filenamemarkomm = implode(';', $uploadFile2);
            }
        }

        // print_r($filenamemarkomm);die();

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

        // $ID_equipment_add = '';
        // if (is_array($input['chk_e_additional'])) {
        //     $ID_equipment_add = implode(',', $input['chk_e_additional']);
        // }

        $ID_equipment_add = '';
        if (is_array($input['chk_e_additional'])) {
            $xx = $input['chk_e_additional'];
            $yy = array();
            for ($i=0; $i < count($xx); $i++) { 
                $yy[] = $xx[$i]->ID_equipment_add;
            }
            $ID_equipment_add = implode(',', $yy);
        }
        
        $ID_add_personel = '';
        if (is_array($input['chk_person_support'])) {
            $ID_add_personel = implode(',', $input['chk_person_support']);
        }

        $chk_markom_support = '';
        if (is_array($input['chk_markom_support'])) {
            if ($filenamemarkomm == '') {
                $chk_markom_support = implode(',', $input['chk_markom_support']);
            }
            else
            {
                $s = $input['chk_markom_support'];
                for ($x=0; $x < count($s); $x++) { 
                    if ($x == 0 ) {
                        if ($s[$x] == 'Graphic Design') {
                            $chk_markom_support = $s[$x].'['.$filenamemarkomm.']'; 
                        }
                        else
                        {
                            $chk_markom_support = $s[$x]; 
                        }
                        
                    }
                    elseif($x == (count($s) - 1))
                    {
                        if ($s[$x] == 'Graphic Design') {
                            $chk_markom_support = $chk_markom_support.','.$s[$x].'['.$filenamemarkomm.']'; 
                        }
                        else
                        {
                            $chk_markom_support = $chk_markom_support.','.$s[$x]; 
                        }
                    }
                    else
                    {
                        if ($s[$x] == 'Graphic Design') {
                            $chk_markom_support = $chk_markom_support.','.$s[$x].'['.$filenamemarkomm.']'; 
                        }
                        else
                        {
                            $chk_markom_support = $chk_markom_support.','.$s[$x].''; 
                        }
                    }
                }
            }
        }

        // check data bentrok dengan jam lain
        // $chk = $this->m_reservation->checkBentrok($Start,$End,$input['chk_e_multiple'],$input['Room']);
        $chk = $this->m_reservation->checkBentrok($Start,$End,'',$input['Room']);
        if ($chk) {
            $Multiple = '';
            // if (is_array($input['chk_e_multiple'])) {
            $boolArray = false;
            if ($boolArray) {
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
                            'Req_layout' => $filename,
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
                           'Multiple' => $Multiple,
                           'Req_layout' => $filename,
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
                            'Req_layout' => $filename,
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
                    'Req_layout' => $filename,
                    'ParticipantQty' => $input['Participant'],
                    'MarcommSupport' => $chk_markom_support,
                );
                $this->db->insert('db_reservation.t_booking', $dataSave);
                $ID_t_booking = $this->db->insert_id();

                if (is_array($input['chk_e_additional'])) {
                    // save data t_booking_eq_additional
                    $xx = $input['chk_e_additional'];
                    $yy = array();
                    for ($i=0; $i < count($xx); $i++) { 
                        $yy[] = array('ID_t_booking' =>$ID_t_booking,'ID_equipment_additional' => $xx[$i]->ID_equipment_add,'Qty' =>  $xx[$i]->Qty);
                    }
                    // $this->db->insert('db_reservation.t_booking_eq_additional', $yy);
                    $this->db->insert_batch('db_reservation.t_booking_eq_additional', $yy);
                }


                // define for email
                $Startdatetime = DateTime::createFromFormat('Y-m-d H:i:s', $Start);
                $Enddatetime = DateTime::createFromFormat('Y-m-d H:i:s', $End);
                $StartNameDay = $Startdatetime->format('l');
                $EndNameDay = $Enddatetime->format('l');

                $MarkomEmail ='';
                if (is_array($input['chk_markom_support'])) {
                    $ss = implode(',', $input['chk_markom_support']);
                    $MarkomEmail = '<li>Documentation  : '.$ss.'</li>';
                }
                if($_SERVER['SERVER_NAME']!='localhost') {
                    // email to ga
                    $Email = 'ga@podomorouniversity.ac.id';
                    $text = 'Dear GA Team,<br><br>
                                Please Approve Venue Reservation request by '.$this->session->userdata('Name').',<br><br>
                                Details Schedule : <br><ul>
                                <li>Start  : '.$StartNameDay.', '.$Start.'</li>
                                <li>End  : '.$EndNameDay.', '.$End.'</li>
                                <li>Room  : '.$input['Room'].'</li>
                                <li>Agenda  : '.$input['Agenda'].'</li>
                                '.$MarkomEmail.'
                                </ul>
                            ';        
                    $to = $Email;
                    $subject = "Podomoro University Venue Reservation Notification";
                    $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

                }
                else
                {
                    $Email = 'alhadi.rahman@podomorouniversity.ac.id';
                    $text = 'Dear GA Team,<br><br>
                                Please Approve Venue Reservation request by '.$this->session->userdata('Name').',<br><br>
                                Details Schedule : <br><ul>
                                <li>Start  : '.$StartNameDay.', '.$Start.'</li>
                                <li>End  : '.$EndNameDay.', '.$End.'</li>
                                <li>Room  : '.$input['Room'].'</li>
                                <li>Agenda  : '.$input['Agenda'].'</li>
                                '.$MarkomEmail.'
                                </ul>
                            ';        
                    $to = $Email;
                    $subject = "Podomoro University Venue Reservation Notification";
                    $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                }

                if (is_array($input['chk_person_support'])) {
                    if($_SERVER['SERVER_NAME']!='localhost') {
                        for ($i=0; $i < count($input['chk_person_support']); $i++) { 
                           $nested = $input['chk_person_support'];
                           $getDataDB = $this->m_master->caribasedprimary('db_employees.division','ID',$nested[$i]);
                           $Email = $getDataDB[0]['Email'];
                           $text = 'Dear '.$getDataDB[0]['Division'].',<br><br>
                                       Venue Reservation request by '.$this->session->userdata('Name').',<br><br>
                                       Details Schedule : <br><ul>
                                       <li>Start  : '.$StartNameDay.', '.$Start.'</li>
                                       <li>End  : '.$EndNameDay.', '.$End.'</li>
                                       <li>Room  : '.$input['Room'].'</li>
                                       <li>Agenda  : '.$input['Agenda'].'</li>
                                       <li>Note : <strong>Need Person Support</strong></li>
                                       </ul>
                                   ';        
                           $to = $Email;
                           $subject = "Podomoro University Venue Reservation Person Support";
                           $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

                        }
                    }
                    else{
                        $Email = 'alhadi.rahman@podomorouniversity.ac.id';
                        $text = 'Dear IT,<br><br>
                                    Venue Reservation request by '.$this->session->userdata('Name').',<br><br>
                                    Details Schedule : <br><ul>
                                    <li>Start  : '.$StartNameDay.', '.$Start.'</li>
                                    <li>End  : '.$EndNameDay.', '.$End.'</li>
                                    <li>Room  : '.$input['Room'].'</li>
                                    <li>Agenda  : '.$input['Agenda'].'</li>
                                    <li>Note : <strong>Need Person Support</strong></li>
                                    </ul>
                                ';        
                        $to = $Email;
                        $subject = "Podomoro University Venue Reservation Person Support";
                        $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                    }    
                    
                }

                if (array_key_exists('fileDataMarkomm',$_FILES)) {
                    // email notification to markom
                    $exFile =explode(';', $filenamemarkomm);
                    $MarkomSupport = '<ul>';
                    for ($m=0; $m < count($exFile); $m++) { 
                        $MarkomSupport .= '<li>'.'<a href="'.base_url("fileGetAny/vreservation-".$exFile[$m]).'" target="_blank"></i>'.$exFile[$m].'</a></li>';
                    }
                    $MarkomSupport .= '</ul></li>';

                    if($_SERVER['SERVER_NAME']!='localhost') {
                          $getDataDB = $this->m_master->caribasedprimary('db_reservation.email_to','Ownership','Markom');
                          $Email = $getDataDB[0]['Email'];
                          $text = 'Dear Team,<br><br>
                                      Venue Reservation request by '.$this->session->userdata('Name').',<br><br>
                                      Details Schedule : <br><ul>
                                      <li>Start                       : '.$StartNameDay.', '.$Start.'</li>
                                      <li>End                         : '.$EndNameDay.', '.$End.'</li>
                                      <li>Room                        : '.$input['Room'].'</li>
                                      <li>Agenda                      : '.$input['Agenda'].'</li>
                                      <li>Documentation               : <strong>Please Click link below to download these files</strong>
                                      '.$MarkomSupport.'
                                      </ul>

                                  ';        
                          $to = $Email;
                          $subject = "Podomoro University Venue Reservation Marcomm Support";
                          $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                    }
                    else{
                        $Email = 'alhadi.rahman@podomorouniversity.ac.id';
                        $text = 'Dear Team,<br><br>
                                    Venue Reservation request by '.$this->session->userdata('Name').',<br><br>
                                    Details Schedule : <br><ul>
                                    <li>Start                       : '.$StartNameDay.', '.$Start.'</li>
                                    <li>End                         : '.$EndNameDay.', '.$End.'</li>
                                    <li>Room                        : '.$input['Room'].'</li>
                                    <li>Agenda                      : '.$input['Agenda'].'</li>
                                    <li>Documentation      : <strong>Please Click link below to download these files</strong>
                                    '.$MarkomSupport.'
                                    </ul>

                                ';        
                        $to = $Email;
                        $subject = "Podomoro University Venue Reservation Marcomm Support";
                        $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                    } 
                }
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

    public function vr_request()
    {
        $content = $this->load->view($this->pathView.'transaksi/page_approve','',true);
        $this->temp($content);
    }

    public function json_list_approve()
    {
        $getData = $this->m_reservation->getDataT_booking();
        echo json_encode($getData);
    }

    public function json_list_booking_by_user()
    {
        $getData = $this->m_reservation->getDataT_bookingByUser(null,'',2);
        echo json_encode($getData);
    }

    public function json_list_booking()
    {
        $getData = $this->m_reservation->getDataT_booking(null,'',2);
        echo json_encode($getData);
    }

    public function approve_submit()
    {
        $msg = '';
        $input = $this->getInputToken();
        $ID = $input['ID_tbl'];
        // check approve bentrok
        $get = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$ID);
        $Start = $get[0]['Start'];$End = $get[0]['End'];$chk_e_multiple = '';$Room = $get[0]['Room'];
        $chk = $this->m_reservation->checkBentrok($Start,$End,$chk_e_multiple,$Room,$ID);
        if ($chk) {
            $dataSave = array(
                    'Status' => 1,
                    'ApprovedBy' => $this->session->userdata('NIP'),
                    'ApprovedAt' => date('Y-m-d H:i:s'),
                            );
            $this->db->where('ID',$ID);
            $this->db->update('db_reservation.t_booking', $dataSave);

            // // add Qty
            // $getE_additional = $this->m_master->caribasedprimary('db_reservation.t_booking_eq_additional','ID_t_booking',$ID);
            // if (count($getE_additional) > 0) {
            //     $bool = true; // check qty ready
            //     for ($i=0; $i < count($getE_additional); $i++) { 
            //         // add Qty
            //         $ID_equipment_additional = $getE_additional[$i]['ID_equipment_additional'];
            //         $Qty_T = $getE_additional[$i]['Qty'];
            //         $getM_equip_add = $this->m_master->caribasedprimary('db_reservation.m_equipment_additional','ID',$ID_equipment_additional);
            //         if ($getM_equip_add < $Qty_T || $getM_equip_add ==  0) {
            //             $bool = false;
            //             break;
            //         }
            //     }

            //     if ($bool) {
            //         for ($i=0; $i < count($getE_additional); $i++) { 
            //             // add Qty
            //             $ID_equipment_additional = $getE_additional[$i]['ID_equipment_additional'];
            //             $Qty_T = $getE_additional[$i]['Qty'];
            //             $getM_equip_add = $this->m_master->caribasedprimary('db_reservation.m_equipment_additional','ID',$ID_equipment_additional);
            //             $QTY_Upd = $getM_equip_add[0]['Qty'] - $Qty_T;
            //             $dataSave = array(
            //                 'Qty' => $QTY_Upd,
            //             );
            //             $this->db->where('ID', $ID_equipment_additional);
            //             $this->db->update('db_reservation.m_equipment_additional', $dataSave);
            //         }
            //     }
            //     else
            //     {
            //         $msg = 'This Equipment Additional isnot enough to quantity, Please check';
            //     }
                

            // }

            // send email approve to user
            $getUser = $this->m_master->caribasedprimary('db_employees.employees','NIP',$get[0]['CreatedBy']);
            $Startdatetime = DateTime::createFromFormat('Y-m-d H:i:s', $get[0]['Start']);
            $Enddatetime = DateTime::createFromFormat('Y-m-d H:i:s', $get[0]['End']);
            $StartNameDay = $Startdatetime->format('l');
            $EndNameDay = $Enddatetime->format('l');

            $Email = $getUser[0]['EmailPU'];
            $text = 'Dear '.$getUser[0]['Name'].',<br><br>
                        Your Venue Reservation approved by '.$this->session->userdata('Name').',<br><br>
                        Details Schedule : <br><ul>
                        <li>Start  : '.$StartNameDay.', '.$get[0]['Start'].'</li>
                        <li>End  : '.$EndNameDay.', '.$get[0]['End'].'</li>
                        <li>Room  : '.$get[0]['Room'].'</li>
                        </ul>
                    ';        
            $to = $Email;
            $subject = "Podomoro University Venue Reservation Approved";
            $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

        }
        else
        {
            $msg = 'This schedule conflict, Please check';
        }

        echo json_encode($msg);
        
    }

    public function cancel_submit()
    {
        $msg = '';
            $input = $this->getInputToken();

            $get = $this->m_master->caribasedprimary('db_reservation.t_booking','ID',$input['ID_tbl']);
            $getUser = $this->m_master->caribasedprimary('db_employees.employees','NIP',$get[0]['CreatedBy']);
            $getE_additional = $this->m_master->caribasedprimary('db_reservation.t_booking_eq_additional','ID_t_booking',$get[0]['ID']);

            $Reason = 'Cancel by yourself';
            if(array_key_exists("Reason",$input))
            {
                $Reason = $input['Reason'];
            }

            for ($i=0; $i < count($getE_additional); $i++) { 
                $dataSave = array(
                    'ID_t_booking_eq_add' => $getE_additional[$i]['ID'],
                    'ID_t_booking' => $get[0]['ID'],
                    'ID_equipment_additional' => $getE_additional[$i]['ID_equipment_additional'],
                    'Qty' => $getE_additional[$i]['Qty'],
                );
                $this->db->insert('db_reservation.t_booking_eq_additional_delete', $dataSave); 
            }
            
            $sql = "delete from db_reservation.t_booking_eq_additional where ID_t_booking = ".$get[0]['ID'];
            $query=$this->db->query($sql, array());

            // if (count($getE_additional) > 0) {
            //     // cek status approve atau tidak
            //     if ($get[0]['Status'] == 1) {
            //         for ($i=0; $i < count($getE_additional); $i++) { 
            //             // add Qty
            //             $ID_equipment_additional = $getE_additional[$i]['ID_equipment_additional'];
            //             $Qty_T = $getE_additional[$i]['Qty'];
            //             $getM_equip_add = $this->m_master->caribasedprimary('db_reservation.m_equipment_additional','ID',$ID_equipment_additional);
            //             $QTY_Upd = $Qty_T + $getM_equip_add[0]['Qty'];
            //             $dataSave = array(
            //                 'Qty' => $QTY_Upd,
            //             );
            //             $this->db->where('ID', $ID_equipment_additional);
            //             $this->db->update('db_reservation.m_equipment_additional', $dataSave);

            //             $dataSave = array(
            //                 'ID_t_booking_eq_add' => $getE_additional[$i]['ID'],
            //                 'ID_t_booking' => $get[0]['ID'],
            //                 'ID_equipment_additional' => $getE_additional[$i]['ID_equipment_additional'],
            //                 'Qty' => $getE_additional[$i]['Qty'],
            //             );
            //             $this->db->insert('db_reservation.t_booking_eq_additional_delete', $dataSave); 
            //         }
            //     }
            //     else
            //     {
            //         for ($i=0; $i < count($getE_additional); $i++) { 
            //             $dataSave = array(
            //                 'ID_t_booking_eq_add' => $getE_additional[$i]['ID'],
            //                 'ID_t_booking' => $get[0]['ID'],
            //                 'ID_equipment_additional' => $getE_additional[$i]['ID_equipment_additional'],
            //                 'Qty' => $getE_additional[$i]['Qty'],
            //             );
            //             $this->db->insert('db_reservation.t_booking_eq_additional_delete', $dataSave); 
            //         }
            //     }

            //     $sql = "delete from db_reservation.t_booking_eq_additional where ID_t_booking = ".$get[0]['ID'];
            //     $query=$this->db->query($sql, array());

            // }

            $dataSave = array(
                'Start' => $get[0]['Start'],
                'End' => $get[0]['End'],
                'Time' => $get[0]['Time'],
                'Colspan' => $get[0]['Colspan'],
                'Agenda' => $get[0]['Agenda'],
                'Room' => $get[0]['Room'],
                'ID_equipment_add' => $get[0]['ID_equipment_add'],
                'ID_add_personel' => $get[0]['ID_add_personel'],
                'Req_date' => $get[0]['Req_date'],
                'CreatedBy' => $get[0]['CreatedBy'],
                'ID_t_booking' => $get[0]['ID'],
                'Note_deleted' => 'Cancel By User##'.$Reason,
                'DeletedBy' => $this->session->userdata('NIP'),
                'Req_layout' => $get[0]['Req_layout'],
                'Status' => $get[0]['Status'],
                'MarcommSupport' => $get[0]['MarcommSupport'],
            );
            $this->db->insert('db_reservation.t_booking_delete', $dataSave); 

            $this->m_master->delete_id_table_all_db($get[0]['ID'],'db_reservation.t_booking');
// send email
            
            $Startdatetime = DateTime::createFromFormat('Y-m-d H:i:s', $get[0]['Start']);
            $Enddatetime = DateTime::createFromFormat('Y-m-d H:i:s', $get[0]['End']);
            $StartNameDay = $Startdatetime->format('l');
            $EndNameDay = $Enddatetime->format('l');

            $Email = $getUser[0]['EmailPU'];
            $text = 'Dear '.$getUser[0]['Name'].',<br><br>
                        Your Venue Reservation was Cancel by '.$this->session->userdata('Name').',<br><br>
                        Details Schedule : <br><ul>
                        <li>Start  : '.$StartNameDay.', '.$get[0]['Start'].'</li>
                        <li>End  : '.$EndNameDay.', '.$get[0]['End'].'</li>
                        <li>Room  : '.$get[0]['Room'].'</li>
                        <li>Reason : '.$Reason.'</li>
                        </ul>
                    ';        
            $to = $Email;
            $subject = "Podomoro University Venue Reservation Cancel Reservation";
            $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);

            // send email to administratif
            $getEmail = $this->m_master->showData_array('db_reservation.email_to');
            if($_SERVER['SERVER_NAME']!='localhost') {
                for ($i=0; $i < count($getEmail); $i++) {
                    if ($i != 0) {
                         $Email = $getEmail[$i]['Email'];
                         $text = 'Dear '.$getEmail[$i]['Ownership'].',<br><br>
                                     Venue Reservation was Cancel by '.$this->session->userdata('Name').',<br><br>
                                     Details Schedule : <br><ul>
                                     <li>Start  : '.$StartNameDay.', '.$get[0]['Start'].'</li>
                                     <li>End  : '.$EndNameDay.', '.$get[0]['End'].'</li>
                                     <li>Room  : '.$get[0]['Room'].'</li>
                                     <li>Reason : '.$Reason.'</li>
                                     </ul>
                                 ';        
                         $to = $Email;
                         $subject = "Podomoro University Venue Reservation Cancel Reservation";
                         $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                    } 
                }

            }
            else
            {
                $Email = $getEmail[0]['Email'];
                $text = 'Dear '.$getEmail[0]['Ownership'].',<br><br>
                            Venue Reservation was Cancel by '.$this->session->userdata('Name').',<br><br>
                            Details Schedule : <br><ul>
                            <li>Start  : '.$StartNameDay.', '.$get[0]['Start'].'</li>
                            <li>End  : '.$EndNameDay.', '.$get[0]['End'].'</li>
                            <li>Room  : '.$get[0]['Room'].'</li>
                            <li>Reason : '.$Reason.'</li>
                            </ul>
                        ';        
                $to = $Email;
                $subject = "Podomoro University Venue Reservation Cancel Reservation";
                $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
            }

        echo json_encode($msg);    
    }

    public function booking_cancel()
    {
        $content = $this->load->view($this->pathView.'transaksi/page_booking_cancel','',true);
        $this->temp($content);
    }
    public function data_reservation()
    {
        $content = $this->load->view($this->pathView.'transaksi/page_data_reservation','',true);
        $this->temp($content);
    }


}
