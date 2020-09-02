<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_api_survey extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('m_search');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('akademik/m_onlineclass','m_oc');
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
        return $data_arr;
    }

    public function crudSurvey(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='getDataSurvey'){
            $SurveyID = $data_arr['SurveyID'];
            $data = $this->db->get_where('db_it.surv_survey',array('ID'=>$SurveyID))->result_array();

            $result = array('Status' => 0);
            if(count($data)>0){

                $data[0]['Quesions'] = $this->db->query('SELECT sq.Question, sq.IsRequired, sq.AnswerType, 
                                                        sq.QTID
                                                        FROM db_it.surv_survey_detail ssd 
                                                        LEFT JOIN db_it.surv_question sq ON (sq.ID = ssd.QuestionID)
                                                        WHERE ssd.SurveyID = "'.$SurveyID.'" 
                                                        ORDER BY ssd.Queue ASC ')->result_array();

                $result = array('Status' => 1, 'Data' => $data[0]);
            }

            return print_r(json_encode($result));
        }


    }




}
