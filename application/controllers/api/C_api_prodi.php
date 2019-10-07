<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_api_prodi extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('master/m_master');
        $this->load->model('admin-prodi/beranda/m_home');

        $this->load->library('JWT');
        $this->load->library('google');
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "s3Cr3T-G4N";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function getInputToken2()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function is_url_exist($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code == 200){
            $status = true;
        }else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

// ==== CRUD DATA PRODI ====== // 
    function crudDataProdi(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='viewDataProdi')
        {
            $data=$this->m_home->getTableProdi();
            echo json_encode($data);
        } else if($data_arr['action']=='updateDataProdi')
        {
            $data=$this->m_home->updateTableProdi($data_arr);
            return print_r(1);
        }
        else if ($data_arr['action']=='viewDataTestimoni') 
        {
            $data=$this->m_home->getDataTestimoni();
            echo json_encode($data);
        }
        else if ($data_arr['action']=='viewDataSlider') 
        {
            $data=$this->m_home->getDataSlider();
            echo json_encode($data);
        }
        else if($data_arr['action']=='insertDataslider')
        {
           $dataSave2 =[];
           if (array_key_exists('uploadFile1', $_FILES)) { // jika file di upload
            $upload = $this->m_master->uploadDokumenMultiple(uniqid(),'uploadFile1',$path = './images/Slider');
            $upload = json_encode($upload); 
            // convert file
            $upload = json_decode($upload,true);
            $upload = $upload[0];
            // $dataSave2['Images'] = $upload; 
            // get posted data
            $dataform = $data_arr['dataform']; // data di jadikan array
            $dataform = json_decode(json_encode($dataform),true); // convert to array
            $dataform['Images'] = $upload;
            $dataform['ProdiID'] = $this->session->userdata('prodi_active_id');
            $dataform['UploadBy'] = $this->session->userdata('NIP');
            $dataform['UploadAt'] = date('Y-m-d');
            
            $dataSave2 = $dataform;
            // echo print_r($dataSave2);

            // Search Sorting
            $Sorting = 1;
            $ProdiID = $this->session->userdata('prodi_active_id');
            $sql = 'select * from db_prodi.slider where ProdiID = ? order by Sorting desc limit 1';
            $G_sorting = $this->db->query($sql, array($ProdiID))->result_array();
            if (count($G_sorting) > 0) { // jika data ada
                $Sorting = $G_sorting[0]['Sorting'] + 1;
            }
            $dataSave2['Sorting'] = $Sorting;
            $this->db->insert('db_prodi.slider',$dataSave2);
           }

            return print_r(1);

        }
        elseif ($data_arr['action'] == 'change_sorting') {
            $ID = $data_arr['ID'];
            $Sorting = $data_arr['Sorting'];
            $sortex = $data_arr['sortex'];

            $ProdiID = $this->session->userdata('prodi_active_id');
            $sql = 'select * from db_prodi.slider where ProdiID = ? and Sorting = ? ';
            $G_sorting = $this->db->query($sql, array($ProdiID,$Sorting))->result_array();

            // $G_sorting = $this->m_master->caribasedprimary('db_prodi.slider','Sorting',$Sorting);

            $this->db->where('ID',$ID);
            $this->db->update('db_prodi.slider',array('Sorting' => $Sorting ));

            $ID_change = $G_sorting[0]['ID'];

            $this->db->where('ID',$ID_change);
            $this->db->update('db_prodi.slider',array('Sorting' => $sortex ));

            return print_r(1);

        }


    }




}
