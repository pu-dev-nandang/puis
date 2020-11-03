<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_monthly_report extends Globalclass {
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
        // $data['G_division'] = $this->m_master->apiservertoserver(base_url().'api/__getAllDepartementPU');
        $content = $this->load->view('page/rektorat/monthly_report',$data,true);
        $this->temp($content);
    }

    private function authAction(){
        $NIP = $this->session->userdata('NIP');

        // read nav
        $DivisionID = $this->m_master->getSessionDepartmentPU();

        $G_data = $this->db->query(
            'select * from db_rektorat.privileges_monthly_report where
               NIP = "'.$NIP.'" and DivisionID = "'.$DivisionID.'" 
              '
        )->result_array();
        return $G_data;
    }


    public function index()
    {
        $data2['DivisionID']= $this->m_master->getSessionDepartmentPU();
        $data2['auth'] = $this->authAction();
        $data['InputForm'] = $this->load->view('page/rektorat/monthly_report/InputForm',$data2,true);
        $data2['action'] = 'write';
        $data2['G_division'] = $this->m_master->apiservertoserver(base_url().'api/__getAllDepartementPU');
        $data['ViewTable'] = $this->load->view('page/rektorat/monthly_report/ViewTable',$data2,true);
        $page = $this->load->view('page/rektorat/monthly_report',$data,true);
        $this->menu_request($page);
    }

    public function crud_monthly_report()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $Input = $this->getInputToken();
        $action = $Input['action'];
        if ($action == 'read') {
            $DivisionID = $Input['DivisionID'];
            $Where = '';
            // get Nav Dept
            $Nav = $this->m_master->getSessionDepartmentPU();
            if ($DivisionID == '%') {
                if ($Nav == 'NA.12' || $Nav == 'NA.2' || $Nav == 'NA.41') {
                    $Where = '';
                }
                else
                {
                    $Where = ' Where a.DivisionID = "'.$DivisionID.'" ';
                }
            }
            else
            {
                $Where = ' Where a.DivisionID = "'.$DivisionID.'" ';
            }

            $sql = 'select a.*,b.Name, c.NameDepartment from db_rektorat.monthly_report as a 
                    join db_employees.employees as b on a.UpdatedBy = b.NIP
                    '.$this->m_master->QueryDepartmentJoin('a.DivisionID','c').'
                    '.$Where;
            // print_r($Where);die();
            $query = $this->db->query($sql,array())->result_array();
            $data = array();
            for ($i=0; $i < count($query); $i++) {
                $nestedData = array();
                $row = $query[$i]; 
                $nestedData[] = $i+1;
                $nestedData[] = $row['Title'];
                $nestedData[] = $row['NameDepartment'];
                $nestedData[] = $row['Desc'];
                $nestedData[] = date('M Y', strtotime($row['DateReport']) );
                $nestedData[] = $row['File'];
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
            $dataSave = json_decode(json_encode($Input['data']),true);
            if (array_key_exists('File', $_FILES)) {
                // do upload file
                if ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') {
                    $headerOrigin = ($_SERVER['SERVER_NAME'] == 'localhost') ? "http://localhost" : serverRoot;
                    $path = 'rektorat/monthlyreport';
                    $FileName = uniqid();
                    $TheFile = 'File';
                    $uploadNas = $this->m_master->UploadManyFilesToNas($headerOrigin,$FileName,$TheFile,$path,'array');
                    $FileUpload = json_encode($uploadNas); 
                    $dataSave['File'] = $FileUpload;
                }
                else
                {
                    $FileUpload = $this->m_master->uploadDokumenMultiple(uniqid(),'File',$path = './uploads/rektorat/monthlyreport');
                    $FileUpload = json_encode($FileUpload); 
                    $dataSave['File'] = $FileUpload; 
                }
                
            }
            $arr_add = [
                'UpdatedAt' => date('Y-m-d H:i:s'),
                'UpdatedBy' => $this->session->userdata('NIP'),
                'DivisionID' => $this->m_master->getSessionDepartmentPU(),
            ];
            $dataSave = $dataSave  + $arr_add;
            $this->db->insert('db_rektorat.monthly_report',$dataSave);
            echo json_encode(1);
        }
        elseif ($action =='delete') {
            $ID = $Input['ID'];
            $G_data_ = $this->m_master->caribasedprimary('db_rektorat.monthly_report','ID',$ID);
            if (count($G_data_)>0) {
                if ($G_data_[0]['File'] != '' && $G_data_[0]['File'] != null) {
                    $arr_file = (array) json_decode($G_data_[0]['File'],true);
                    $filePath = 'rektorat\\monthlyreport\\'.$arr_file[0]; // pasti ada file karena required
                    $path = FCPATH.'uploads\\'.$filePath;
                    if ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') {
                        $headerOrigin = ($_SERVER['SERVER_NAME'] == 'localhost') ? "http://localhost" : serverRoot;
                        $path_delete = ($_SERVER['SERVER_NAME'] == 'localhost') ? "localhost/rektorat/monthlyreport/".$arr_file[0] : "pcam/rektorat/monthlyreport/".$arr_file[0];
                        $this->m_master->DeleteFileToNas($headerOrigin,$path_delete);
                    }
                    else
                    {
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                    
                }
            }
            
            $this->db->where('ID',$ID);
            $this->db->delete('db_rektorat.monthly_report');
            echo json_encode(1);
        }
        elseif ($action = 'edit') {
            $ID = $Input['ID'];
            $dataSave = json_decode(json_encode($Input['data']),true);
            $G_data_ = $this->m_master->caribasedprimary('db_rektorat.monthly_report','ID',$ID);
            if (array_key_exists('File', $_FILES)) {
                if ($G_data_[0]['File'] != '' && $G_data_[0]['File'] != null) {
                    $arr_file = (array) json_decode($G_data_[0]['File'],true);
                    $filePath = 'rektorat\\monthlyreport\\'.$arr_file[0]; // pasti ada file karena required
                    $path = FCPATH.'uploads\\'.$filePath;
                    if ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') {
                        $headerOrigin = ($_SERVER['SERVER_NAME'] == 'localhost') ? "http://localhost" : serverRoot;
                        $path_delete = ($_SERVER['SERVER_NAME'] == 'localhost') ? "localhost/rektorat/monthlyreport/".$arr_file[0] : "pcam/rektorat/monthlyreport/".$arr_file[0];
                        $this->m_master->DeleteFileToNas($headerOrigin,$path_delete);
                    }
                    else
                    {
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                   
                }

                // do upload file
                if ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') {
                    $headerOrigin = ($_SERVER['SERVER_NAME'] == 'localhost') ? "http://localhost" : serverRoot;
                    $path = 'rektorat/monthlyreport';
                    $FileName = uniqid();
                    $TheFile = 'File';
                    $uploadNas = $this->m_master->UploadManyFilesToNas($headerOrigin,$FileName,$TheFile,$path,'array');
                    $FileUpload = json_encode($uploadNas); 
                    $dataSave['File'] = $FileUpload;
                }
                else
                {
                    $FileUpload = $this->m_master->uploadDokumenMultiple(uniqid(),'File',$path = './uploads/rektorat/monthlyreport');
                    $FileUpload = json_encode($FileUpload); 
                    $dataSave['File'] = $FileUpload; 
                }
                
            }
            $arr_add = [
                'UpdatedAt' => date('Y-m-d H:i:s'),
                'UpdatedBy' => $this->session->userdata('NIP'),
            ];
            $dataSave = $dataSave  + $arr_add;
            $this->db->where('ID',$ID);
            $this->db->update('db_rektorat.monthly_report',$dataSave);
            echo json_encode(1);
        }
        else
        {
            echo '{"status":"999","message":"Not Authorize"}'; 
        }
    }

}
