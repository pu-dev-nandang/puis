<?php 
class M_home extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        // Load database prodi
        $this->load->library('JWT');
        
    }

    function getTableProdi(){// db_academic

    	$prodi_active_id = $this->session->userdata('prodi_active_id');

        $hasil=$this->db->query("SELECT * FROM db_academic.program_study_detail where ProdiID = '".$prodi_active_id."' ")->result_array();
        return $hasil;
    }

    function updateTableProdi($data_arr){
        $prodi_active_id = $this->session->userdata('prodi_active_id');

        $dataForm = (array) $data_arr['dataForm'];

        // Cek
        $dataCk = $this->db->get_where('db_academic.program_study_detail'
            ,array('ProdiID'=>$prodi_active_id))->result_array();

        if(count($dataCk)>0){
        // print_r($dataForm);die();
       // print_r($dataForm);die();
            $this->db->where('ProdiID', $prodi_active_id);
            $this->db->update('db_academic.program_study_detail',$dataForm);
        } else {
            $dataForm['ProdiID'] = $prodi_active_id;
            $this->db->insert('db_academic.program_study_detail',$dataForm);
        }

    }

    function getDataTestimoni(){ // db_prodi
        $prodi_active_id = $this->session->userdata('prodi_active_id');

        $hasil=$this->db->query("SELECT * FROM db_prodi.testimoni where ProdiID = '".$prodi_active_id."' ")->result_array();
        return $hasil;
    }

    function getDataSlider(){ // db_prodi
        $prodi_active_id = $this->session->userdata('prodi_active_id');

        $hasil=$this->db->query("SELECT * FROM db_prodi.slider where ProdiID = '".$prodi_active_id."' order by Sorting asc ")->result_array();
        for ($i=0; $i < count($hasil); $i++) { 
            $data = $hasil[$i];
            $token = $this->jwt->encode($data,"UAP)(*");
            $hasil[$i]['token'] = $token;
        }
        // print_r($hasil);die();
        return $hasil;
    }
    function updateDataProdi($data_arr){
        $prodi_active_id = $this->session->userdata('prodi_active_id');

        $dataForm = (array) $data_arr['dataForm'];

        // Cek
        $dataCk = $this->db->get_where('db_prodi.slider'
            ,array('ProdiID'=>$prodi_active_id))->result_array();

        if(count($dataCk)>0){
        // print_r($dataForm);die();
       // print_r($dataForm);die();
            $this->db->where('ProdiID', $prodi_active_id);
            $this->db->update('db_prodi.slider',$dataForm);
        } else {
            $dataForm['ProdiID'] = $prodi_active_id;
            $this->db->insert('db_prodi.slider',$dataForm);
        }

        
    }


}
?>