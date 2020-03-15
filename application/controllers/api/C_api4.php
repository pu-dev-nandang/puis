<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_api4 extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('m_search');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('master/m_master');
        $this->load->library('JWT');
        $this->load->library('google');

        date_default_timezone_set("Asia/Jakarta");
        setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
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
        $data_arr = json_decode(json_encode($data_arr),true);
        return $data_arr;
    }

    public function crudAgregatorTB3()
    {
        $Input = $this->getInputToken();
        $action = $Input['action'];
        switch ($action) {
            case 'readDataDosenTidakTetap':
                $sql = 'select a.';    

                break;
            
            default:
                # code...
                break;
        }
    }

    // Search Employees (termasuk dosen di dalamnya)
    public function searchEmployees(){

        $key = $this->input->get('key');
        $limit = $this->input->get('limit');

        $data = $this->m_search->searchEmployees($key,$limit);

        return print_r(json_encode($data));

    }

    public function crudPrefrencesEmployees(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='getDataRelatedNIP'){
            $NIP = $data_arr['NIP'];
            $data = $this->m_search->getDataRelatedNIP($NIP);

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='setToDataRelatedNIP'){
            $NIP = $data_arr['NIP'];
            $NIPInduk = $data_arr['NIPInduk'];
            $data = $this->m_search->setToDataRelatedNIP($NIPInduk,$NIP);

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removeDataRelatedNIP'){
            $ID = $data_arr['ID'];
            $this->db->where('ID', $ID);
            $this->db->delete('db_employees.related_nip');

            return print_r(1);
        }
        else if($data_arr['action']=='readAllDataRelatedNIP'){

            $data = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.related_nip rn 
                                                LEFT JOIN db_employees.employees em ON (em.NIP = rn.NIP)
                                                GROUP BY rn.NIP')->result_array();

            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    $data[$i]['Details'] = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.related_nip rn 
                                                LEFT JOIN db_employees.employees em ON (em.NIP = rn.RelatedNIP)
                                                WHERE rn.NIP = "'.$data[$i]['NIP'].'" ')->result_array();
                }
            }

            return print_r(json_encode($data));

        }


    }

  /*ADDED BY FEBRI @ JAN 2020*/
    public function getStdInsurance(){
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->m_api->getStdInsurance(array("a.NPM"=>$data_arr['NPM']))->row();
            if(!empty($isExist)){
                $json = $isExist;
            }
        }

        echo json_encode($json);
    }
    /*END ADDED BY FEBRI @ JAN 2020*/

    public function getTableMaster(){
        $input = $this->getInputToken2();
        if (array_key_exists('table', $input)) {
            $data = $this->m_master->showData_array($input['table']);
            echo json_encode($data);
        }
        else
        {
            echo '{"status":"999","message":"Not Authenfication"}'; 
        }
    }

}
