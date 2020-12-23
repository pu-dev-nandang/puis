<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_setting extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('JWT');
    }

    public function Group_LoadData(){
        $hasil = $this->db->query('select * from db_blogs.set_group where Active = 1 ')->result_array();
        return $hasil;
    }

    public function member_LoadData(){
        $hasil = $this->db->query('select * from db_blogs.set_member where Active = 1 order by LevelSequence desc ')->result_array();
        return $hasil;
    }

    public function listmember_datatables($dataToken){
        $this->load->model('m_article');
        $Addwhere = '';
        if (array_key_exists('param', $dataToken)) {
            $param = $dataToken['param'];
            if (array_key_exists('ChooseGroup', $param)) {
               $WhereOrAnd = ($Addwhere == '') ? ' Where ' : ' And ';
               $Addwhere .= ($param['ChooseGroup'] != '%') ? $WhereOrAnd.' a.ID_set_group = '.$param['ChooseGroup'] : '';
            }

            if (array_key_exists('ChooseMember', $param)) {
               $WhereOrAnd = ($Addwhere == '') ? ' Where ' : ' And ';
               $Addwhere .= ($param['ChooseMember'] != '%') ? $WhereOrAnd.' a.ID_set_member = '.$param['ChooseMember'] : '';
            }
        }

        $sql = 'select a.ID_set_group,b.GroupName,a.ID_set_member,c.MemberName,a.NIPNPM, '
                .$this->m_article->getNameUpdateBY('a.NIPNPM','MemberListName').',
                '.$this->m_article->getEMPorMHS('a.NIPNPM','TypeUser').',
                a.UpdateBY,d.Name as NameUpdateBY,a.UpdateAT,a.ID_set_list_member   
                from db_blogs.set_list_member as a 
                join db_blogs.set_group as b on a.ID_set_group = b.ID_set_group
                join db_blogs.set_member as c on a.ID_set_member = c.ID_set_member
                join db_employees.employees as d on a.UpdateBY = d.NIP
                '.$Addwhere.'
            ';
        $query = $this->db->query($sql,array())->result_array();
        $data = array();
        for ($i=0; $i < count($query); $i++) { 
            $nestedData = array();
            $query[$i]['UpdateAT'] = date('d M Y H:i:s', strtotime($query[$i]['UpdateAT']));
            $row = $query[$i]; 
            $nestedData[] = ($i+1);
            $nestedData[] = $row['NIPNPM'];
            $nestedData[] = $row['MemberListName'];
            $nestedData[] = $row['MemberName'];
            $nestedData[] = $row['GroupName'];
            $nestedData[] = $row['NameUpdateBY'];
            $nestedData[] = $row['UpdateAT'];

            $nestedData[] = $row['ID_set_list_member'];
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

    public function Group_LoadData_datatables(){
        $sql = 'select * from db_blogs.set_group where Active = 1
            ';
        $query = $this->db->query($sql,array())->result_array();
        $data = array();
        for ($i=0; $i < count($query); $i++) { 
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = ($i+1);
            $nestedData[] = $row['GroupName'];
            $nestedData[] = $row['ID_set_group'];
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

    public function member_LoadData_datatables()
    {
        $sql = 'select * from db_blogs.set_member where Active = 1
            ';
        $query = $this->db->query($sql,array())->result_array();
        $data = array();
        for ($i=0; $i < count($query); $i++) { 
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = ($i+1);
            $nestedData[] = $row['MemberName'];
            $nestedData[] = $row['LevelSequence'];
            $nestedData[] = $row['ID_set_member'];
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

    public function checkTransactionData($table,$field,$valueField){
        $hasil = $this->db->query('select count(*) as total from 
                                    (
                                        select 1 from  '.$table.' where '.$field.' = "'.$valueField.'"
                                    ) cc    
                                    ')->result_array();
        if ($hasil[0]['total'] == 0) {
            return true;
        }
        else
        {
            return false;
        }
    }

}