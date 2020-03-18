<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_credit_type_courses extends Globalclass {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->library('JWT');
        $this->data['department'] = parent::__getDepartement(); 
    }

    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_request($page){
        $data['page'] = $page;
        $content = $this->load->view('page/secretariat-rectorate/menu_sec-rektorat',$data,true);
        $this->temp($content);
    }


    public function index()
    {
        $data['InputForm'] = $this->load->view('page/'.$this->data['department'].'/master_data/credit_type_courses/InputForm','',true);
        $data2['action'] = 'write';
        $data['ViewTable'] = $this->load->view('page/'.$this->data['department'].'/master_data/credit_type_courses/ViewTable',$data2,true);
        $page = $this->load->view('page/'.$this->data['department'].'/master_data/credit_type_courses',$data,true);
        $this->menu_request($page);
    }

    public function crud_credit_type_courses()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $Input = $this->getInputToken();
        $action = $Input['action'];
        if ($action == 'read') {
            $sql = 'select a.*,b.Name from db_rektorat.credit_type_courses as a 
                    join db_employees.employees as b on a.Updated_by = b.NIP
                    ';
            $query = $this->db->query($sql,array())->result_array();
            $data = array();
            for ($i=0; $i < count($query); $i++) {
                $nestedData = array();
                $row = $query[$i]; 
                $nestedData[] = $i+1;
                $nestedData[] = $row['NamaType'];
                $nestedData[] = $row['SKSPerMinutes'];
                $nestedData[] = $row['Updated_at'];
                $nestedData[] = $row['Name'];
                $token = $this->jwt->encode($row,"UAP)(*");
                $nestedData[] = $token;
                $nestedData[] = $row['ID'];
                $data[] = $nestedData;
            }

            $json_data = array(
                "draw"            => intval( 0 ),
                "recordsTotal"    => intval(count($query)),
                "recordsFiltered" => intval( count($query) ),
                "data"            => $data
            );
            echo json_encode($json_data);   
        }
        elseif ($action =='add') {
            $dataSave = json_decode(json_encode($Input['data']),true);
            $arr_add = [
                'Updated_at' => date('Y-m-d H:i:s'),
                'Updated_by' => $this->session->userdata('NIP'),
            ];
            $dataSave = $dataSave  + $arr_add;
            $this->db->insert('db_rektorat.credit_type_courses',$dataSave);
            echo json_encode(1);
        }
        elseif ($action =='delete') {
            $ID = $Input['ID'];
            $this->db->where('ID',$ID);
            $this->db->delete('db_rektorat.credit_type_courses');
            echo json_encode(1);
        }
        elseif ($action = 'edit') {
            $ID = $Input['ID'];
            $dataSave = json_decode(json_encode($Input['data']),true);
            $arr_add = [
                'Updated_at' => date('Y-m-d H:i:s'),
                'Updated_by' => $this->session->userdata('NIP'),
            ];
            $dataSave = $dataSave  + $arr_add;
            $this->db->where('ID',$ID);
            $this->db->update('db_rektorat.credit_type_courses',$dataSave);
            echo json_encode(1);
        }
        else
        {
            echo '{"status":"999","message":"Not Authorize"}'; 
        }
    }

}
