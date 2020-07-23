<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_setting extends Globalclass {
public $data = array();
    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_request($page){
        $data['page'] = $page;

        $content = $this->load->view('page/rektorat/Setting',$data,true);
        $this->temp($content);
    }


    public function index()
    {
        $data1['G_division'] = $this->m_master->apiservertoserver(base_url().'api/__getAllDepartementPU');
        $data['InputSetting'] = $this->load->view('page/rektorat/monthly_report/InputSetting',$data1,true);
        $data2['action'] = 'write';
        $data['ViewSetting'] = $this->load->view('page/rektorat/monthly_report/ViewSetting',$data2,true);
        $page = $this->load->view('page/rektorat/Setting',$data,true);
        $this->menu_request($page);
    }

     public function crud_setting_monthly_report()
        {
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');
            $Input = $this->getInputToken();
            $action = $Input['action'];
            if ($action == 'read') {
                $sql = 'select a.*,b.Name, c.NameDepartment, d.Name as NamePrevileges from db_rektorat.privileges_monthly_report as a 
                        join db_employees.employees as b on a.UpdatedBy = b.NIP
                        join db_employees.employees as d on a.NIP =  d.NIP
                        '.$this->m_master->QueryDepartmentJoin('a.DivisionID','c').'
                        ';
                $query = $this->db->query($sql,array())->result_array();
                $data = array();
                for ($i=0; $i < count($query); $i++) {
                    $nestedData = array();
                    $row = $query[$i]; 
                    $nestedData[] = $i+1;
                    $nestedData[] = $row['NIP'].' - '.$row['NamePrevileges'];
                    $nestedData[] = $row['NameDepartment'];
                    $nestedData[] = $row['Access'];
                    $nestedData[] = $this->m_master->getDateIndonesian($row['UpdatedAt']);
                    // $nestedData[] = $row['UpdatedBy'];
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
                // print_r($Input);die();
                $dataSave = json_decode(json_encode($Input['data']),true);
                $arr_add = [
                    'UpdatedAt' => date('Y-m-d H:i:s'),
                    'UpdatedBy' => $this->session->userdata('NIP'),
                ];
                $dataSave = $dataSave  + $arr_add;
                $this->db->insert('db_rektorat.privileges_monthly_report',$dataSave);
                echo json_encode(1);
            }
            elseif ($action =='delete') {
                $ID = $Input['ID'];
                $G_data_ = $this->m_master->caribasedprimary('db_rektorat.monthly_report','ID',$ID);
                $this->db->where('ID',$ID);
                $this->db->delete('db_rektorat.privileges_monthly_report');
                echo json_encode(1);
            }
            elseif ($action = 'edit') {
                $ID = $Input['ID'];
                $dataSave = json_decode(json_encode($Input['data']),true);
                $G_data_ = $this->m_master->caribasedprimary('db_rektorat.privileges_monthly_report','ID',$ID);
                $arr_add = [
                    'UpdatedAt' => date('Y-m-d H:i:s'),
                    'UpdatedBy' => $this->session->userdata('NIP'),
                ];
                $dataSave = $dataSave  + $arr_add;
                $this->db->where('ID',$ID);
                $this->db->update('db_rektorat.privileges_monthly_report',$dataSave);
                echo json_encode(1);
            }
            else
            {
                echo '{"status":"999","message":"Not Authorize"}'; 
            }
        }
    

}
