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

        $prodi_active_id = $this->session->userdata('prodi_active_id');

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
        else if($data_arr['action']=='updateDataslider')
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
                $sql = $this->db->get_where('db_prodi.slider',array(
                'ID' => $dataSave2['ID']))->result_array();

                $arr_file =  $sql[0]['Images'];
                $path = './images/Slider/'. $arr_file;

                if(is_file($path)){
                    $this->db->where('ID',$dataSave2['ID']);
                    $this->db->update('db_prodi.slider',$dataSave2);
                    unlink($path);
                }
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
        else if($data_arr['action']=='readLanguageProdi'){

            $data = $this->db->get('db_prodi.language')->result_array();

            return print_r(json_encode($data));
        }

        // Add by Nandang =====
        else if($data_arr['action']=='updateProdiTexting'){

            $dataForm = (array) $data_arr['dataForm'];

            $dataForm['ProdiID'] = $prodi_active_id;
            $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();

            // Cek apakah udah di input atau blm
            $dataCk = $this->db->get_where('db_prodi.prodi_texting',array(
                'ProdiID' => $prodi_active_id,
                'Type' => $dataForm['Type'],
                'LangID' => $dataForm['LangID'],
            ))->result_array();

            if(count($dataCk)>0){
                $this->db->where('ID', $dataCk[0]['ID']);
                $this->db->update('db_prodi.prodi_texting',$dataForm);
            } else {
                $this->db->insert('db_prodi.prodi_texting',$dataForm);
            }

            return print_r(1);

        }
        else if($data_arr['action']=='readProdiTexting'){

            $Type = $data_arr['Type'];

            $data = $this->db->query('SELECT pt.*, l.Language ,st.Photo,ast.Name,ast.NPM 
                                                FROM db_prodi.prodi_texting pt 
                                                LEFT JOIN db_prodi.language l ON (pt.LangID = l.ID)
                                                INNER JOIN db_prodi.student_testimonials_details std ON (std.IDProdiTexting = pt.ID)
                                                INNER JOIN db_prodi.student_testimonials st ON (st.ID = std.IDStudentTexting)
                                                INNER JOIN db_academic.auth_students ast ON (ast.NPM = st.NPM)
                                                WHERE pt.ProdiID = "'.$prodi_active_id.'" AND pt.Type="'.$Type.'" ')->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='readDataProdiTexting'){
            $Type = $data_arr['Type'];
            $LangID = $data_arr['LangID'];

            $data = $this->db->get_where('db_prodi.prodi_texting',array(
                'ProdiID' => $prodi_active_id,
                'Type' => $Type,
                'LangID' => $LangID
            ))->result_array();

            return print_r(json_encode($data));

        }
        // Add by yamin =====
        else if($data_arr['action']=='saveDataTestimonials'){

            if (array_key_exists('uploadFile', $_FILES)) { // jika file di upload
                $upload = $this->m_master->uploadDokumenMultiple(uniqid(),'uploadFile',$path = './images/Testimonials');
                $upload = json_encode($upload); 
                // convert file
                $upload = json_decode($upload,true);
                $upload = $upload[0];

                $dataForm = $data_arr; 

                $student_testimonials = $dataForm['student_testimonials'];
                $student_testimonials = json_decode( json_encode($student_testimonials),true);

                $prodi_texting = $dataForm['prodi_texting'];
                $prodi_texting = json_decode( json_encode($prodi_texting),true);

                // check action insert or update
                $sql = 'select st.NPM,st.Photo,std.IDStudentTexting,std.IDProdiTexting from db_prodi.student_testimonials as st join db_prodi.student_testimonials_details as std on st.ID = std.IDStudentTexting
                    join db_prodi.prodi_texting as pt on pt.ID = std.IDProdiTexting
                    where st.NPM = ? and pt.LangID = ? and pt.Type = ?
                ';

                $NPM = $student_testimonials['NPM'];
                $LangID = $prodi_texting['LangID'];
                $Type = $prodi_texting['Type'];
                $query=$this->db->query($sql, array($NPM,$LangID,$Type))->result_array();
                if (count($query) == 0) { // insert

                    // insert prodi_texting
                    $prodi_texting['ProdiID'] = $prodi_active_id;
                    $prodi_texting['UpdatedAt'] = $this->m_rest->getDateTimeNow();

                    $this->db->insert('db_prodi.prodi_texting',$prodi_texting);
                    $IDProdiTexting = $this->db->insert_id();

                    // insert student_testimonials
                    $student_testimonials['Photo'] = $upload;
                    $student_testimonials['ProdiID'] = $prodi_active_id;

                    $this->db->insert('db_prodi.student_testimonials',$student_testimonials);
                    $IDStudentTexting = $this->db->insert_id();

                    // insert student_testimonials_details
                    $student_testimonials_details = [
                        'IDStudentTexting' => $IDStudentTexting,
                        'IDProdiTexting' => $IDProdiTexting,
                    ];
                    $this->db->insert('db_prodi.student_testimonials_details',$student_testimonials_details);
                }
                else
                {
                    // update student_testimonials
                        // action photo delete dulu file fotonya kalau dia upload foto, baru insert

                    $arr_file =  $query[0]['Photo'];
                    $path = './images/Testimonials/'. $arr_file;

                      if(is_file($path)){

                        $IDStudentTexting = $query[0]['IDStudentTexting'];
                        $student_testimonials['Photo'] = $upload;

                        $this->db->where('ID',$IDStudentTexting);
                        $this->db->update('db_prodi.student_testimonials',$student_testimonials);
                        unlink($path);
                      }

                    
                    // update prodi_textting
                    $IDProdiTexting = $query[0]['IDProdiTexting'];
                    $prodi_texting['ProdiID'] = $prodi_active_id;
                    $prodi_texting['UpdatedAt'] = $this->m_rest->getDateTimeNow();

                    $this->db->where('ID',$IDProdiTexting);
                    $this->db->update('db_prodi.prodi_texting',$prodi_texting);

                }
            }

            return print_r(1);
        }


    }


    function getContentProdi(){
        $id = $this->input->get('id');
        $lang = $this->input->get('lang');
        $content = $this->input->get('content');
    }




}
