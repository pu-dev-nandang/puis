<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class c_setpodivers extends Student_Life {
    
    function __construct()
    {        
        parent::__construct();
        // header('Access-Control-Allow-Origin: *');
        $this->load->model('m_setting');
    }

    public function dateTimeNow(){
        date_default_timezone_set('Asia/Jakarta');
        $dataTime = date('Y-m-d H:i:s') ;
        return $dataTime;
    }

    public function temp($content)
    {
        parent::template($content);
    }

    public function index()
    {
        $data["pageTitle"] = "Dashboard";
        $content = $this->load->view('template/V_content',$data,true);
        parent::template($content);
    }
    
    function data_setting_master_group(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $Input = $this->getInputToken();
        $action = $Input['action'];
        $rs = [];
        switch ($action) {
            case 'LoadData':
                $rs = $this->m_setting->MasterGroup_LoadData();
                break;
            case 'datatables':
                $rs = $this->m_setting->MasterGroup_LoadData_datatables();
                break;
            case 'add' : 
                $data = $Input['data'];
                $this->db->insert('db_blogs.set_master_group',$data);
                $rs = ['status' => 1,'msg' => ''];
            case 'edit' :
                $ID = $Input['ID']; 
                $data = $Input['data'];
                $this->db->where('ID_master_group',$ID);
                $this->db->update('db_blogs.set_master_group',$data);
                $rs = ['status' => 1,'msg' => ''];
                break;
            case 'delete' :
                $ID = $Input['ID'];
                // check data telah di gunakan atau belum
                // $chk =  $this->m_setting->checkTransactionData('db_blogs.set_list_member','ID_set_group',$ID);
                // if ($chk) {
                //     $this->db->where('ID_set_group',$ID);
                //     $this->db->delete('db_blogs.set_group');
                //     $rs = ['status' => 1,'msg' => ''];
                // }
                // else
                // {
                //     $rs = ['status' => 0,'msg' => 'The data has been using for transaction'];
                // }
                $data = $Input['data'];
                $this->db->where('ID_master_group',$ID);
                $this->db->update('db_blogs.set_master_group',['Active' => 0 ] );
                $rs = ['status' => 1,'msg' => ''];
                break;
            default:
                # code...
                break;
        }

        echo json_encode($rs);
    }

    function data_setting_group(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $Input = $this->getInputToken();
        $action = $Input['action'];
        $rs = [];
        switch ($action) {
            case 'LoadData':
                $rs = $this->m_setting->Group_LoadData();
                break;
            case 'datatables':
                $rs = $this->m_setting->Group_LoadData_datatables();
                break;
            case 'add' : 
                $data = $Input['data'];
                // print_r($data);die();
                $ck=$this->db->insert('db_blogs.set_group',$data);
                $insert_id = $this->db->insert_id();
                $rs = ['status' => 1,'msg' => ''];
                // if($insert_id){
                //     $hasil = $this->db->query('select * from db_blogs.set_group where Active = 1 and ID_set_group='.$insert_id.' ')->result_array();
                //     $data = array();
                //     for ($i=0; $i < count($hasil); $i++) { 
                //         $nestedData = array();
                //         $row = $hasil[$i]; 
                //         $nestedData[] = ($i+1);
                //         $nestedData[] = $row['ID_master_group']; 
                //         $nestedData[] = $row['ID_set_group'];     
                //         $data[] = $nestedData;
                //     }
                //     $this->db->insert('db_blogs.set_master_join',$data);
                // }
                    
                
            case 'edit' :
                $ID = $Input['ID']; 
                $data = $Input['data'];
                $this->db->where('ID_set_group',$ID);
                $this->db->update('db_blogs.set_group',$data);
                $rs = ['status' => 1,'msg' => ''];
                break;
            case 'delete' :
                $ID = $Input['ID'];
                // check data telah di gunakan atau belum
                // $chk =  $this->m_setting->checkTransactionData('db_blogs.set_list_member','ID_set_group',$ID);
                // if ($chk) {
                //     $this->db->where('ID_set_group',$ID);
                //     $this->db->delete('db_blogs.set_group');
                //     $rs = ['status' => 1,'msg' => ''];
                // }
                // else
                // {
                //     $rs = ['status' => 0,'msg' => 'The data has been using for transaction'];
                // }
                $data = $Input['data'];
                $this->db->where('ID_set_group',$ID);
                $this->db->update('db_blogs.set_group',['Active' => 0 ] );
                $rs = ['status' => 1,'msg' => ''];
                break;
            default:
                # code...
                break;
        }

        echo json_encode($rs);
    }

    function data_setting_member(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $Input = $this->getInputToken();
        $action = $Input['action'];
        $rs = [];
        switch ($action) {
            case 'LoadData':
                $rs = $this->m_setting->member_LoadData();
                break;
            case 'datatables' :
                $rs = $this->m_setting->member_LoadData_datatables();
                break;
            case 'add' : 
                $data = $Input['data'];
                $this->db->insert('db_blogs.set_member',$data);
                $rs = ['status' => 1,'msg' => ''];
            case 'edit' :
                $ID = $Input['ID']; 
                $data = $Input['data'];
                $this->db->where('ID_set_member',$ID);
                $this->db->update('db_blogs.set_member',$data);
                $rs = ['status' => 1,'msg' => ''];
                break;
            case 'delete' :
                $ID = $Input['ID'];
                $data = $Input['data'];
                $this->db->where('ID_set_member',$ID);
                $this->db->update('db_blogs.set_member',['Active' => 0 ] );
                $rs = ['status' => 1,'msg' => ''];
                break;
            default:
                # code...
                break;
        }

        echo json_encode($rs);
    }

    function data_setting_listmember(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $Input = $this->getInputToken();
        $Input = json_decode(json_encode($Input),true);
        $action = $Input['action'];
        $rs = [];
        switch ($action) {
            case 'datatables':
                $rs = $this->m_setting->listmember_datatables($Input);
                break;
            case 'add' : 
                $data = $Input['data'];
                $data['UpdateBY'] = $this->session->userdata('Username');
                $data['UpdateAT'] = date('Y-m-d H:i:s');

                $this->db->insert('db_blogs.set_list_member',$data);
                $rs = ['status' => 1,'msg' => ''];
            case 'edit' :
                $ID = $Input['ID']; 
                $data = $Input['data'];
                $data['UpdateBY'] = $this->session->userdata('Username');
                $data['UpdateAT'] = date('Y-m-d H:i:s');
                $this->db->where('ID_set_list_member',$ID);
                $this->db->update('db_blogs.set_list_member',$data);
                $rs = ['status' => 1,'msg' => ''];
                break;
            case 'delete' :
                $ID = $Input['ID']; 
                $this->db->where('ID_set_list_member',$ID);
                $this->db->delete('db_blogs.set_list_member');
                $rs = ['status' => 1,'msg' => ''];
                break;
            default:
                # code...
                break;
        }

        echo json_encode($rs);
    }

    /// ============= ==============  ///
 
}
