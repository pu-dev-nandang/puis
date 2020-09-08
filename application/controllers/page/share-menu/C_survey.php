<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class C_survey extends Globalclass {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('master/m_master','General_model',
            'General_model','global-informations/Globalinformation_model',
            'hr/m_hr','m_log_content'));
        $this->load->helper("General_helper");
        $this->load->library('JWT');
    }
    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_survey($page){
        $data['page'] = $page;
        $content = $this->load->view('page/share-menu/survey/menu_survey',$data,true);
        $this->temp($content);
    }

    public function list_survey(){
        $page = $this->load->view('page/share-menu/survey/list_survey','',true);
        $this->menu_survey($page);
    }

    public function manage_question($token){
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);

        $dataSurvey = $this->db->get_where('db_it.surv_survey',array('ID' => $data_arr['ID']))->result_array();

        if(count($dataSurvey)>0){


            $dataSurvey[0]['StatusLabel'] = '<span class="label label-warning">Unpublish</span>';
            if($dataSurvey[0]['Status']=='1'){
                $dataSurvey[0]['StatusLabel'] = '<span class="label label-success">Publish</span>';
            } else if ($dataSurvey[0]['Status']=='2'){
                $dataSurvey[0]['StatusLabel'] = '<span class="label label-danger">Close</span>';
            }

            $data['dataSurvey'] = $dataSurvey[0];

            $page = $this->load->view('page/share-menu/survey/manage_question',$data,true);
            $this->menu_survey($page);
        } else {
            echo "Not allow access";
        }


    }

    public function create_survey(){
        $page = $this->load->view('page/share-menu/survey/create_survey','',true);
        $this->menu_survey($page);
    }

    public function create_question(){
        $page = $this->load->view('page/share-menu/survey/create_question','',true);
        $this->menu_survey($page);
    }

    public function bank_question(){
        $page = $this->load->view('page/share-menu/survey/bank_question','',true);
        $this->menu_survey($page);
    }

}
