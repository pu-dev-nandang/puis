<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_master extends Vreservation_Controler {

    // private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
    }

    public function equipment_master()
    {
        $content = $this->load->view($this->pathView.'master/equipment_master','',true);
        $this->temp($content);
    }

    public function modalform($table)
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_reservation.'.$table);
        $this->data['getData'] = null;
        if ($this->data['id'] != '') {
            $this->data['getData'] = $this->m_master->caribasedprimary('db_reservation.'.$table,'ID',$this->data['id']);
        }
        echo $this->load->view($this->pathView.'master/modalform',$this->data,true);
    }

    public function load_table_master($table)
    {
        $this->data['getColoumn'] = $this->m_master->getColumnTable('db_reservation.'.$table);
        $this->data['getData'] = $this->m_master->showData('db_reservation.'.$table);
        echo $this->load->view($this->pathView.'master/table_master_global',$this->data,true);
    }


    public function submit_m_equipment()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                //$this->m_master->inserData_jenis_tempat_tinggal($input['Equipment']);
            $dataSave = array(
                'Equipment' => ucwords($input['Equipment']),
                'CreateAT' => date('Y-m-d'),
            );
            $this->db->insert('db_reservation.m_equipment', $dataSave);
                break;
            case 'edit':
                //$this->m_master->editData_jenis_tempat_tinggal($input['Equipment'],$input['CDID']);
                $dataSave = array(
                    'Equipment' => ucwords($input['Equipment']),
                );
                $this->db->where('ID', $input['CDID']);
                $this->db->update('db_reservation.m_equipment', $dataSave);
                break;
            case 'delete':
                $this->m_master->delete_id_table_all_db($input['CDID'],'db_reservation.m_equipment');
                break;
            case 'getactive':
                $this->m_master->getActive_id_activeAll_table_allDB($input['CDID'],$input['Active'],'db_reservation.m_equipment');
                break;
            default:
                # code...
                break;
        }
    }

    public function ruangan()
    {
        $data['CategoryRoom'] = $this->m_master->showData_array('db_reservation.category_room');
        $content = $this->load->view($this->pathView.'master/ruangan',$data,true);
        $this->temp($content);
    }

    public function additional_personel()
    {
        $content = $this->load->view($this->pathView.'master/additional_personel','',true);
        $this->temp($content);
    }

    public function additional_personel_json_data()
    {
        $get = $this->m_reservation->get__m_additional_personel();
        echo json_encode($get);
    }

    public function additional_personel_modal_form()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getDataEdit'] =  $this->m_master->caribasedprimary('db_reservation.m_additional_personel','ID',$input['CDID']);
        }
        echo $this->load->view($this->pathView.'master/modalform_additional_personel',$this->data,true);
    }

    public function additional_personel_submit()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                //$this->m_master->inserData_jenis_tempat_tinggal($input['Equipment']);
            $dataSave = array(
                'ID_division' =>$input['selectDivision'],
            );
            $this->db->insert('db_reservation.m_additional_personel', $dataSave);
                break;
            case 'edit':
                //$this->m_master->editData_jenis_tempat_tinggal($input['Equipment'],$input['CDID']);
                $dataSave = array(
                    'ID_division' => $input['selectDivision'],
                );
                $this->db->where('ID', $input['CDID']);
                $this->db->update('db_reservation.m_additional_personel', $dataSave);
                break;
            case 'delete':
                //$this->m_master->delete_id_table_all_db($input['CDID'],'db_reservation.m_equipment');
                break;
            case 'getactive':
                //$this->m_master->getActive_id_activeAll_table_allDB($input['CDID'],$input['Active'],'db_reservation.m_equipment');
                break;
            default:
                # code...
                break;
        }
    }

    public function equipment_room()
    {
        $content = $this->load->view($this->pathView.'master/equipment_room','',true);
        $this->temp($content);
    }

    public function loaddataJSonEquipmentRoom()
    {
        $get = $this->m_reservation->get_m_room_equipment_all();
        echo json_encode($get);
    }

    public function modal_form_equipmentroom()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $this->data['getDataEdit'] =  $this->m_master->caribasedprimary('db_reservation.m_room_equipment','ID',$input['CDID']);
        }
        echo $this->load->view($this->pathView.'master/modal_form_equipmentroom',$this->data,true);
    }

    public function getDataEquipmentMaster()
    {
        $get = $this->m_master->showData_array('db_reservation.m_equipment'); 
        echo json_encode($get);  
    }

    public function EquipmentRoom_submit()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                //$this->m_master->inserData_jenis_tempat_tinggal($input['Equipment']);
            $dataSave = array(
                'Room' =>$input['selectRoom'],
                'ID_m_equipment' =>$input['selectEquipmentItem'],
                'Qty' =>$input['Qty'],
                'Note' =>$input['Note'],
                'CreatedBy' =>$this->session->userdata('NIP'),
                'CreatedAt' =>date('Y-m-d H:i:s'),
            );
            $this->db->insert('db_reservation.m_room_equipment', $dataSave);
                break;
            case 'edit':
                //$this->m_master->editData_jenis_tempat_tinggal($input['Equipment'],$input['CDID']);
                $dataSave = array(
                    'Room' =>$input['selectRoom'],
                    'ID_m_equipment' =>$input['selectEquipmentItem'],
                    'Qty' =>$input['Qty'],
                    'Note' =>$input['Note'],
                    'UpdatedBy' =>$this->session->userdata('NIP'),
                    'UpdatedAt' =>date('Y-m-d H:i:s'),
                );
                $this->db->where('ID', $input['CDID']);
                $this->db->update('db_reservation.m_room_equipment', $dataSave);
                break;
            case 'delete':
                $this->m_master->delete_id_table_all_db($input['CDID'],'db_reservation.m_room_equipment');
                break;
            case 'getactive':
                //$this->m_master->getActive_id_activeAll_table_allDB($input['CDID'],$input['Active'],'db_reservation.m_equipment');
                break;
            default:
                # code...
                break;
        }
    }

    public function equipment_additional()
    {
        $ID_division = $this->session->userdata('PositionMain');
        $ID_division = $ID_division['IDDivision'];
        $this->data['ID_division'] = $ID_division;
        $content = $this->load->view($this->pathView.'master/equipment_additional',$this->data,true);
        $this->temp($content);
    }

    public function loaddataJSonEquipment_additional()
    {
        $get = $this->m_reservation->get_JSonEquipment_additional();
        echo json_encode($get);
    }

    public function modal_form_equipmentadditional()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        $ID_division = $this->session->userdata('PositionMain');
        $ID_division = $ID_division['IDDivision'];
        $this->data['ID_division'] = $ID_division;
        $html = $this->load->view($this->pathView.'master/modal_form_equipmentadditional',$this->data,true);
        if ($input['Action'] == 'edit') {
            $this->data['getDataEdit'] =  $this->m_master->caribasedprimary('db_reservation.m_equipment_additional','ID',$input['CDID']);
            $dd = $this->data['getDataEdit'];
            $ID_division_own =  $dd[0]['Owner'];
            if ($ID_division == $ID_division_own) {
                  $html = $this->load->view($this->pathView.'master/modal_form_equipmentadditional',$this->data,true);
            }
            else
            {
                $html = "You are not authorize to set this data";
                $html .= '<div style="text-align: center;">       
                            <div class="col-sm-12" id="BtnFooter">
                                <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>
                            </div>
                        </div>';
            }   

        }
        echo $html;
    }

    public function EquipmentAdditional_submit()
    {
        $input = $this->getInputToken();
        switch ($input['Action']) {
            case 'add':
                //$this->m_master->inserData_jenis_tempat_tinggal($input['Equipment']);
            $dataSave = array(
                'Owner' =>$input['selectDivision'],
                'ID_m_equipment' =>$input['selectEquipmentItem'],
                'Qty' =>$input['Qty'],
                'CreatedBy' =>$this->session->userdata('NIP'),
                'CreatedAt' =>date('Y-m-d H:i:s'),
            );
            $this->db->insert('db_reservation.m_equipment_additional', $dataSave);
                break;
            case 'edit':
                //$this->m_master->editData_jenis_tempat_tinggal($input['Equipment'],$input['CDID']);
                $dataSave = array(
                    'Owner' =>$input['selectDivision'],
                    'ID_m_equipment' =>$input['selectEquipmentItem'],
                    'Qty' =>$input['Qty'],
                    'UpdatedBy' =>$this->session->userdata('NIP'),
                    'UpdatedAt' =>date('Y-m-d H:i:s'),
                );
                $this->db->where('ID', $input['CDID']);
                $this->db->update('db_reservation.m_equipment_additional', $dataSave);
                break;
            case 'delete':
                $this->m_master->delete_id_table_all_db($input['CDID'],'db_reservation.m_equipment_additional');
                break;
            case 'getactive':
                //$this->m_master->getActive_id_activeAll_table_allDB($input['CDID'],$input['Active'],'db_reservation.m_equipment');
                break;
            default:
                # code...
                break;
        }
    }

    public function select_venue_room()
    {
        $content = $this->load->view($this->pathView.'master/select_venue_room',$this->data,true);
        $this->temp($content);
    }

    public function getRoomItem()
    {
        $data = array();
        $get = $this->m_master->showData_array('db_academic.classroom');
        for ($i=0; $i < count($get); $i++) { 
            $nestedData=array();
            $row = $get[$i];
             $nestedData[] = $row['ID'];
             $nestedData[] = $row['Room'];
             $nestedData[] = $row['L_Venue'];
             $data[] = $nestedData;
        }
        $json_data = array(
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function submit_select_venue_room()
    {
        $input = $this->getInputToken();
        $arr_insert = [];
        for ($i=0; $i < count($input['checkboxArr']); $i++) {
          $temp = array();
          $ID = $input['checkboxArr'][$i];
          $temp = array(
            'ID' => $ID,
            'L_Venue' => 1
          );
          $arr_insert[] = $temp;
        }
        $this->db->update_batch('db_academic.classroom', $arr_insert, 'ID');

        $getID = implode(',',$input['checkboxArr'] );
        $sql = 'update db_academic.classroom set L_Venue = 0 where ID not in ('.$getID.')';
        $query2=$this->db->query($sql, array());


    }

}
