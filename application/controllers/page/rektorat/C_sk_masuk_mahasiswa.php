<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_sk_masuk_mahasiswa extends Globalclass {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('database/m_database');
        $this->load->library('JWT');
        $this->data['department'] = parent::__getDepartement(); 
    }


     public function temp($content)
    {
        parent::template($content);
    }

    public function menu_request($page){
        $data['page'] = $page;
        $content = $this->load->view('page/rektorat/menu_rektorat',$data,true);
        $this->temp($content);
    }


    public function index()
    {
        $page = $this->load->view('page/'.$this->data['department'].'/master_data/sk_masuk_mahasiswa','',true);
        $this->menu_request($page);
    }

    public function crud_sk_mhs()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $Input = $this->getInputToken();
        $action = $Input['action'];
        if ($action == 'add') {
            $dataSave = [];
            if (array_key_exists('FileUpload', $_FILES)) {
                // do upload file
                $FileUpload = $this->m_master->uploadDokumenMultiple(uniqid(),'FileUpload',$path = './uploads/rektorat');
                $FileUpload = json_encode($FileUpload); 
                $dataSave['FileUpload'] = $FileUpload; 
            }

            $data = $Input['data'];
            $data = json_decode(json_encode($data),true);
            $dataSave = $dataSave + $data;
            $dataSave['Update_at'] = date('Y-m-d H:i:s');
            $dataSave['Update_by'] = $this->session->userdata('NIP');
            $this->db->insert('db_rektorat.sk_tgl_msk',$dataSave);
            echo json_encode(1);

        }
        elseif ($action == 'delete') {
            $ID = $Input['ID'];
            $G_data_ = $this->m_master->caribasedprimary('db_rektorat.sk_tgl_msk','ID',$ID);
            if ($G_data_[0]['FileUpload'] != '' && $G_data_[0]['FileUpload'] != null) {
                $arr_file = (array) json_decode($G_data_[0]['FileUpload'],true);
                $filePath = 'rektorat\\'.$arr_file[0]; // pasti ada file karena required
                $path = FCPATH.'uploads\\'.$filePath;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $this->db->where('ID',$ID);
            $this->db->delete('db_rektorat.sk_tgl_msk');
            echo json_encode(1);


        }
        elseif ($action == 'edit') {
            $dataSave = [];
            $ID = $Input['ID'];
            $data = $Input['data'];
            $data = json_decode(json_encode($data),true);
            $G_data_ = $this->m_master->caribasedprimary('db_rektorat.sk_tgl_msk','ID',$ID);
            if (array_key_exists('FileUpload', $_FILES)) {
                if ($G_data_[0]['FileUpload'] != '' && $G_data_[0]['FileUpload'] != null) {
                    $arr_file = (array) json_decode($G_data_[0]['FileUpload'],true);
                    $filePath = 'rektorat\\'.$arr_file[0]; // pasti ada file karena required
                    $path = FCPATH.'uploads\\'.$filePath;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                // do upload file
                $FileUpload = $this->m_master->uploadDokumenMultiple(uniqid(),'FileUpload',$path = './uploads/rektorat');
                $FileUpload = json_encode($FileUpload); 
                $dataSave['FileUpload'] = $FileUpload; 
            }

            $dataSave = $dataSave + $data;
            $dataSave['Update_at'] = date('Y-m-d H:i:s');
            $dataSave['Update_by'] = $this->session->userdata('NIP');
            $this->db->where('ID',$ID);
            $this->db->update('db_rektorat.sk_tgl_msk',$dataSave);
           echo json_encode(1);
        }
        elseif ($action == 'read') {
            $sql = 'select a.*,b.Name as NameEmp from db_rektorat.sk_tgl_msk as a
                    join db_employees.employees as b on a.Update_by = b.NIP
                    ';
            $query = $this->db->query($sql)->result_array();
            $data = array();
            for ($i=0; $i <count($query) ; $i++) { 
                $nestedData = array();
                $row = $query[$i];
                $nestedData[] = $i+1;
                $nestedData[] = $row['TA'];
                $nestedData[] = $this->m_master->getDateIndonesian($row['Tgl_msk']);
                $nestedData[] = $row['NoSK'];
                $nestedData[] = $row['FileUpload'];
                $nestedData[] = $this->m_master->getDateIndonesian($row['Update_at']);
                $nestedData[] = $row['NameEmp'];
                $nestedData[] = $row['Update_by'];
                $nestedData[] = $row['ID'];
                $token = $this->jwt->encode($row,"UAP)(*");
                $nestedData[] = $token;
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
    }


}
