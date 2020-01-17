<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_action extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->library('JWT');
    }

    public function LoadTablePrivileges($dataToken){
        $addWhere = '';
        if (array_key_exists('DepartmentIDChoose', $dataToken)) {
            $WhereOrAnd = ($addWhere == '') ? ' Where ' : ' And ';
            $addWhere .= $WhereOrAnd.' a.Department = "'.$dataToken['DepartmentIDChoose'].'" ';
        }
        $sql = 'select a.NIP,b.Name,qdj.NameDepartment,a.Level,a.Department,a.ID
                from db_generatordoc.user_access_department as a join db_employees.employees as b on a.NIP = b.NIP
                '.$this->m_master->QueryDepartmentJoin('a.Department').'
                '.$addWhere.'
                ';

        $query = $this->db->query($sql,array())->result_array();
        $data = array();

        for ($i=0; $i < count($query); $i++) {
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = $row['NIP'].' - '.$row['Name'];
            // $nestedData[] = $row['NameDepartment'];
            $nestedData[] = $row['Level'];
            $nestedData[] = $row['ID'];
            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }
        $rs = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval($i),
            "recordsFiltered" => intval( $i ),
            "data"            => $data
        );
        return $rs;

    }

    public function InsertTablePrivileges($dataToken){
        $this->db->insert('db_generatordoc.user_access_department',$dataToken['data']);
        return [
            'status' => 1,
            'msg' => '',
        ];
    }

    public function DeleteTablePrivileges($dataToken){
        $this->db->where('ID',$dataToken['ID']);
        $this->db->delete('db_generatordoc.user_access_department');
        return [
            'status' => 1,
            'msg' => '',
        ];
    }

    public function EditTablePrivileges($dataToken){
        $this->db->where('ID',$dataToken['ID']);
        $this->db->update('db_generatordoc.user_access_department',$dataToken['data']);
        return [
            'status' => 1,
            'msg' => '',
        ];
    }

    public function checkValidationQuery($dataToken){
        /*
            Status
            0 :false
            1 : true
            2 : callback data    
        
        */
        $rs = ['status' => 0,'callback' => [],'data' => [] ];
        $data = $dataToken['data'];
        $querySql = $data['Query'];
        $Params = (array)json_decode($data['Params'],true);
        if ($data['Params'] != '' && empty($Params)  ) {
           $rs['callback'] = [
            'code' => 0,
            'message' => 'Parameter is not support',
           ];
        }
        else
        {
            if ($data['Params'] == '') {
               $this->db->db_debug=false;
               $query = $this->db->query($querySql,$Params);
               if( !$query )
               {
                  $rs['callback'] = $this->db->error();
               }
               else
               {
                $rs['data']['query'] = $query;
                $rs['status'] = 1; 
               }
            }
            else
            {
                $VarPassing = [];
                if (!array_key_exists('user', $dataToken)) {
                    $this->load->model('document-generator/m_table');
                    for ($i=0; $i < count($Params); $i++) { 
                        if (substr($Params[$i], 0,1) == '#') {
                            // get data by passing
                            $str = str_replace('#', '__', $Params[$i]);
                            $Obj = $this->m_table;
                            if (!method_exists($Obj,$str)) {
                                $rs['callback'] = [
                                 'code' => 0,
                                 'message' => 'Method not exist : '.$str,
                                ];
                                break;
                            }
                            else
                            {
                                $rs['data'][$str] = $Obj->$str();
                                $rs['status'] =2;
                            }

                        }
                        else if (substr($Params[$i], 0,1) == '$') {
                            $str = str_replace('$', '', $Params[$i]);
                            if (!$this->session->userdata($str)) {
                               $rs['callback'] = [
                                'code' => 0,
                                'message' => 'Session not exist : '.$str,
                               ];
                               break; 
                            }
                        }
                        else
                        {
                            $rs['callback'] = [
                             'code' => 0,
                             'message' => 'Parameter not exist : '.$Params[$i],
                            ];
                            break; 
                        }
                    }

                }
                else
                {
                    // excecute sql
                    
                }
                
                
            }
            
        }

        return $rs;

    }

}
