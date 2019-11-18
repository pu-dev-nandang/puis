<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_rest_ticketing extends CI_Controller {
    public $data = [];
    private $keyToken = 's3Cr3T-G4N';
    function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json');
        $this->load->model('master/m_master');
        $this->load->model('ticketing/m_general');
        $this->load->library('JWT');
        try {
          $G_setting = $this->m_master->showData_array('db_ticketing.rest_setting');
          if (!$this->auth($G_setting)) {
            echo '{"status":"999","message":"Not Authenfication"}'; 
            die();
          }
          else
          {
            header('Access-Control-Allow-Origin: *');
          }
        } catch (Exception $e) {
          echo json_encode($e);
          die();
        }
                
    }

    private function auth($G_setting){
      $Bool = false;
      try {
        $dataToken = $this->getInputToken();
        $getallheaders = getallheaders();
        foreach ($getallheaders as $name  => $value) {
          if ($name == 'Hjwtkey' && $value == $G_setting[0]['Hjwtkey']) {
            // cek api get
            if(isset($_GET['apikey']) && $_GET['apikey'] == $G_setting[0]['Apikey'] && array_key_exists("auth",$dataToken) &&  $dataToken['auth'] == $this->keyToken ) {
                $Bool = true;
                break;
            } 
          }
        }

        return $Bool;
      } catch (Exception $e) {
         echo json_encode($e);
         die();
      }

      return false;
    }

    private function __addHttpOrhttps($arr){
      $rs = [];
      for ($i=0; $i < count($arr); $i++) { 
        $rs[] = 'http://'.$arr[$i];
        $rs[] = 'https://'.$arr[$i];
      }
      return $rs;
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function QueryDepartmentJoin($IDJoin){
      $sql = ' left join (
            select * from (
            select CONCAT("AC.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND from db_academic.program_study
            UNION
            select CONCAT("NA.",ID) as ID, Division as NameDepartment,Description as NameDepartmentIND from db_employees.division  
            UNION
            select CONCAT("FT.",ID) as ID, NameEng as NameDepartment,Name as NameDepartmentIND from db_academic.faculty 
        )qdj)qdj on '.$IDJoin.'=qdj.ID';
      return $sql;
    }

    public function CRUDCategory(){
      try {
        $dataToken = $this->getInputToken();
        $action = $dataToken['action'];
        switch ($action) {
          case 'read':
          $rs = [];
            $DepartmentID = $dataToken['DepartmentID'];
            $sql = 'select a.Descriptions,c.Name as NameEmployees,a.ID,a.DepartmentID,a.UpdatedBy,a.UpdatedAt,qdj.NameDepartment,qdj.NameDepartmentIND
                    from db_ticketing.category as a '.$this->QueryDepartmentJoin('a.DepartmentID').'
                    left join db_employees.employees as c on a.UpdatedBy = c.NIP
                    where a.DepartmentID = "'.$DepartmentID.'"
                    order by a.ID desc
            ';
            $query = $this->db->query($sql,array())->result_array();
            $data = array();
            for ($i=0; $i < count($query); $i++) {
                $nestedData = array();
                $row = $query[$i]; 
                $nestedData[] = $i+1;
                foreach ($row as $key => $value) {
                  $nestedData[] = $value;
                }
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
            echo json_encode($rs);
            break;
          case 'add':
            $rs = ['status' => 0,'msg' => ''];
            try {
                $dataSave = json_decode(json_encode($dataToken['data']),true);
                $this->db->insert('db_ticketing.category',$dataSave);
                $rs['status'] = 1;
                echo json_encode($rs);
            } catch (Exception $e) {
              $rs['msg'] = $e;
              echo json_encode($rs);
            }
            break;
        }
      } catch (Exception $e) {
        echo json_encode($e);
      }
    }

}