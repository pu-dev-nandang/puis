<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_kurikulum extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('akademik/m_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function kurikulum()
    {
        $data['department'] = parent::__getDepartement();

//        $content = $this->load->view('page/'.$data['department'].'/kurikulum',$data,true);
        $content = $this->load->view('page/'.$data['department'].'/kurikulum/kurikulum',$data,true);
        $this->temp($content);
    }

    public function kurikulum_detail(){

        $token = $this->input->post('token');
        $data['department'] = parent::__getDepartement();
        $data['token'] = $token;
        $this->load->view('page/'.$data['department'].'/kurikulum/kurikulum_detail',$data);
    }


    //==== Modal Kurikulum =====
    public function add_kurikulum(){
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);

        if(count($data_arr)>0){
            $data['department'] = parent::__getDepartement();
            $data['token'] = $token;
            $data['kurikulum'] = $data_arr;
            $this->load->view('page/'.$data['department'].'/kurikulum/modal_add_kurikulum',$data);
        } else {
            echo '<h3>Data Is Empty!</h3>';
        }


    }


    public function loadPageDetailMataKuliah(){
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);

        if(count($data_arr)>0){
            $data['department'] = parent::__getDepartement();
            $data['CDID'] = $data_arr['CDID'];
            $data['action'] = $data_arr['Action'];
            $data['semester'] = $data_arr['Semester'];
            $this->load->view('page/'.$data['department'].'/kurikulum/modal_add_semester',$data);
        } else {
            echo '<h3>Data Is Empty!</h3>';
        }

    }

    public function getDataConf(){
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);

        $table='';
        if($data_arr['action']=='ConfJenisKurikulum') {
            $table = 'curriculum_types';
        } else if($data_arr['action']=='ConfJenisKelompok'){
            $table = 'courses_groups';
        } else if($data_arr['action']=='ConfProgram'){
            $table = 'programs_campus';
        }
        $data['conf'] = $this->m_akademik->__getDataConf($table);

        $data['department'] = parent::__getDepartement();
        $data['table'] = $table;

        $this->load->view('page/'.$data['department'].'/kurikulum/kurikulum_conf',$data);

    }

//    public function getClassGroup(){
//
//        $token = $this->input->post('token');
//        $key = "UAP)(*";
//        $data_arr = (array) $this->jwt->decode($token,$key);
//
//        $data['department'] = parent::__getDepartement();
//
//        if($data_arr['action']=='read'){
//            $data ['btnAction'] = ($data_arr['options']=='disabledBtnAction') ? 'disabled' : '';
//            $data['dataClassGroup'] = $this->m_akademik->getdataClassGroup();
//            $this->load->view('page/'.$data['department'].'/kurikulum/modal_class_group',$data);
//        } else if($data_arr['action']=='add'){
//            $dataForm = (array) $data_arr['dataForm'];
//            $this->db->insert('db_academic.class_group',$dataForm);
//            $insert_id = $this->db->insert_id();
//
//            return print_r($insert_id);
//        } else if($data_arr['action']=='delete') {
//            $this->db->where('ID', $data_arr['ID']);
//            $this->db->delete('db_academic.class_group');
//            return print_r(1);
//        } else if($data_arr['action']=='edit'){
//            $dataForm = (array) $data_arr['dataForm'];
//            $this->db->where('ID', $data_arr['ID']);
//            $this->db->update('db_academic.class_group',$dataForm);
//            return print_r(1);
//        } else if($data_arr['action']=='read_json'){
//            header('Content-Type: application/json');
//            $data['dataClassGroup'] = $this->m_akademik->getSelectOptionClassGroup();
//            return print_r(json_encode($data['dataClassGroup']));
//        }
//
//
//    }


    public function kurikulum_detail2(){
        $data_json = $this->input->post('data_json');
        $data['department'] = parent::__getDepartement();

        $data['data_json'] = $data_json;

        $this->load->view('page/'.$data['department'].'/kurikulum_detail',$data);
    }

    public function kurikulum_detail_mk(){
        $data_json = $this->input->post('data_json');
        $data['department'] = parent::__getDepartement();

        $data['data_json'] = $data_json;

        $this->load->view('page/'.$data['department'].'/kurikulum_detail_mk',$data);
    }

    public function curriculum_cross($ta){

        $q = 'SELECT sp.ID AS SPID, sp.SemesterID, sp.NPM, ats.Name, sp.CDID AS CDID_1, sp.MKID AS MKID_1, cd.Semester, cd.ProdiID,
                                                cd.ID AS CDID_2, cd.MKID AS MKID_2, c.Year, mk.MKCode, mk.NameEng, ps.Name AS ProdiName , ats.ProdiID AS ProdiAsal
                                                FROM ta_'.$ta.'.study_planning sp 
                                                LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sp.CDID)
                                                LEFT JOIN db_academic.curriculum c ON (cd.CurriculumID = c.ID) 
                                                LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                                LEFT JOIN db_academic.program_study ps ON (ps.ID = cd.ProdiID)
                                                LEFT JOIN ta_'.$ta.'.students ats ON (ats.NPM = sp.NPM)
                                                ORDER BY sp.NPM, cd.Semester, cd.ProdiID ASC ';


        $data = $this->db->query($q.' LIMIT 300')->result_array();

//        print_r($data);exit;


        $resOk = [];
        if(count($data)>0){

            for ($i=0;$i<count($data);$i++){
                $d = $data[$i];

                $dataBener = $this->db->query('SELECT cd.ID AS CDID, cd.MKID, cd.ProdiID, c.Year, mk.MKCode, mk.NameEng, ps.Name AS ProdiName, cd.Semester   
                                                          FROM db_academic.curriculum_details cd
                                                          LEFT JOIN db_academic.curriculum c ON (c.ID = cd.CurriculumID)
                                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                                          LEFT JOIN db_academic.program_study ps ON (ps.ID = cd.ProdiID)
                                                          WHERE  
                                                          cd.ProdiID = "'.$d['ProdiAsal'].'" AND cd.MKID = "'.$d['MKID_1'].'" AND c.Year = "'.$ta.'" ')->result_array();


                if($ta!=$d['Year'] || $d['ProdiAsal']!=$d['ProdiID']){
                    $data[$i]['Bener'] = $dataBener;
                    array_push($resOk,$data[$i]);
                }


            }

        }


        $department = parent::__getDepartement();

        $result['DataOk'] = array(
            'Student' => $resOk,
            'Kesalahan' => count($resOk),
            'TA' => $ta
        );

//        print_r($result);exit;
        $this->load->view('page/'.$department.'/kurikulum/kurikulum_detail_cross',$result,false);



    }

}
