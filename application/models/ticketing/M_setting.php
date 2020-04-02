<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_setting extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('ticketing/m_general');
        $this->load->library('JWT');
    }

    public function LoadDataCategory($dataToken)
    {
        $rs = [];
        $AddWhere = '';
        if (array_key_exists('DepartmentID', $dataToken)) {
            $DepartmentID = $dataToken['DepartmentID'];
            $AddWhere = ' and a.DepartmentID = "'.$DepartmentID.'"';
        }

        $sql = 'select a.Descriptions,c.Name as NameEmployees,a.ID,a.DepartmentID,a.UpdatedBy,a.UpdatedAt,qdj.NameDepartment,qdj.NameDepartmentIND,
                a.TemplateMessage
                from db_ticketing.category as a '.$this->m_general->QueryDepartmentJoin('a.DepartmentID').'
                left join db_employees.employees as c on a.UpdatedBy = c.NIP
                where  a.Active = 1  '.$AddWhere.'
                order by a.DepartmentID desc,a.ID desc
        ';
        $query = $this->db->query($sql,array())->result_array();

        $data = array();
        for ($i=0; $i < count($query); $i++) {
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = $i+1;
            foreach ($row as $key => $value) {
              $nestedData[] = $value;
            }
            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }

        $rs = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data
        );

        return $rs;
    }

    public function LoadDataAdmin($dataToken)
    {
        $rs = [];
        $AddWhere = '';
        if (array_key_exists('DepartmentID', $dataToken)) {
            $DepartmentID = $dataToken['DepartmentID'];
            $WhereORAnd = ($AddWhere == '') ? ' Where ' : ' And';
            $AddWhere = $WhereORAnd.'  a.DepartmentID = "'.$DepartmentID.'"';
        }

        $sql = 'select CONCAT(cc.Name," | ",a.NIP) as NameAdmin,a.ID,a.NIP,a.DepartmentID,c.Name as NameUpdatedBy,a.UpdatedBy,a.UpdatedAt,qdj.NameDepartment,qdj.NameDepartmentIND
                from db_ticketing.admin_register as a '.$this->m_general->QueryDepartmentJoin('a.DepartmentID').'
                left join db_employees.employees as c on a.UpdatedBy = c.NIP
                left join db_employees.employees as cc on a.NIP = cc.NIP
                '.$AddWhere.'
                order by a.ID desc
        ';
        $query = $this->db->query($sql,array())->result_array();
        $data = array();
        for ($i=0; $i < count($query); $i++) {
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = $i+1;
            foreach ($row as $key => $value) {
              $nestedData[] = $value;
            }
            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }

        $rs = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data
        );

        return $rs;
    }

    public function ActionTable($action,$dataToken,$TableName){
        $rs = ['status' => 0,'msg' => ''];
        switch ($action) {
            case 'add':
                try {
                    $dataSave = json_decode(json_encode($dataToken['data']),true);
                    $dataSave['UpdatedAt'] = date('Y-m-d H:i:s');
                    $this->db->insert($TableName,$dataSave);
                    $rs['status'] = 1;
                } catch (Exception $e) {
                  $rs['msg'] = $e;
                }
                break;
            case 'delete': // change active not active
                try {
                    $ID = $dataToken['ID'];
                    $dataSave = ['Active' => 0,
                                 'UpdatedAt' => date('Y-m-d H:i:s'),
                                ];
                    $this->db->where('ID',$ID);
                    $this->db->update($TableName,$dataSave);
                    $rs['status'] = 1;
                } catch (Exception $e) {
                  $rs['msg'] = $e;
                }
                break;
            case 'remove': // delete data
                try {
                    $ID = $dataToken['ID'];
                    $this->db->where('ID',$ID);
                    $this->db->delete($TableName);
                    $rs['status'] = 1;
                } catch (Exception $e) {
                  $rs['msg'] = $e;
                }
                break;
            case 'edit' : 
               $rs = ['status' => 0,'msg' => ''];
               try {
                   $ID = $dataToken['ID'];
                   $dataSave = json_decode(json_encode($dataToken['data']),true);
                   $dataSave['UpdatedAt'] = date('Y-m-d H:i:s');
                   $this->db->where('ID',$ID);
                   $this->db->update($TableName,$dataSave);
                   $rs['status'] = 1;
               } catch (Exception $e) {
                 $rs['msg'] = $e;
               }
              break;
            default:
                # code...
                break;
        }
        return $rs;
    }

    public function LoadDataAuthDashboard($dataToken){
        $rs = [];
        $AddWhere = '';

        $sql = 'select CONCAT(cc.Name," | ",a.NIP) as NameAdmin,a.ID,a.NIP,c.Name as NameUpdatedBy,a.UpdatedBy,a.UpdatedAt
                from db_ticketing.auth_dashboard as a
                left join db_employees.employees as c on a.UpdatedBy = c.NIP
                left join db_employees.employees as cc on a.NIP = cc.NIP
                order by a.ID desc
        ';
        $query = $this->db->query($sql,array())->result_array();
        $data = array();
        for ($i=0; $i < count($query); $i++) {
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = $i+1;
            foreach ($row as $key => $value) {
              $nestedData[] = $value;
            }
            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }

        $rs = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data
        );

        return $rs;
    }
  
}
