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

    // public function share_public(){
    //     $rs = ['status' => 0,'msg' => '','callback' => [] ]; 
    //     $datatoken =  $this->getInputToken();
    //     $formData = json_decode(json_encode($datatoken),true);
      
    //   $id = $formData['ID'];
    //   $share = $formData['shareAtPublic'];
  
    //   $updates = array(
    //     'isPublicSurvey' => (string)$share
        
    //   );
    
    //  $this->db->where('ID', $id);
    //   $this->db->update('db_it.surv_survey', $updates);
        
    //   $rs['status'] = 1;  
    //   echo json_encode($rs);
    // }

    public function share_public(){
        $reqdata = $this->input->post();
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($reqdata['token'],$key);


                if($data_arr['action']=='sharetoPublic'){
                    
                            $id = $data_arr['ID'];  
                            $share = $data_arr['shareAtPublic']; 
                            $updates = array(
                                'isPublicSurvey' => (string)$share
                            );
    
                            $this->db->where('ID', $id);
                            $this->db->update('db_it.surv_survey', $updates);  

                            $updates = array(
                                'SharePublicStat' => '0'
                            );
    
                            $this->db->where('SurveyID', $id);
                            $this->db->update('db_it.surv_survey_detail', $updates);                                
                            return print_r(json_encode(1));  
                }
                
                else if($data_arr['action']=='selectQuestionSurvey'){
                            $QuestionID = $data_arr['QuestionID'];
                            $ID = explode(",", $QuestionID);
                            $SurveyID = $data_arr['ID'];
                     

                                $updates = array(
                                    'SharePublicStat' => '1'
                                );
    
                            $this->db->where_in('QuestionID', $ID);
                            $this->db->where('SurveyID', $SurveyID);
                            $this->db->update('db_it.surv_survey_detail', $updates);  
                             
                             $updates = array(
                                    'SharePublicStat' => '0'
                                );
    
                            $this->db->where_not_in('QuestionID', $ID);
                            $this->db->where('SurveyID', $SurveyID);
                            $this->db->update('db_it.surv_survey_detail', $updates);  

                    
                            $updates = array(
                                'isPublicSurvey' => '1'
                            );
    
                            $this->db->where('ID', $SurveyID);
                            $this->db->update('db_it.surv_survey', $updates);  
                            return print_r(json_encode(1));  
                }

                else if($data_arr['action']=='showQuestioninSurveyShare'){

                    $SurveyID = $data_arr['SurveyID'];

                    $data = $this->db->query('SELECT ssd.QuestionID, ssd.SharePublicStat AS stat, sq.Question, sq.QTID, sqc.Description AS Category, 
                                                 sqt.Description AS Type
                                                FROM db_it.surv_survey_detail ssd
                                                LEFT JOIN db_it.surv_question sq ON (sq.ID = ssd.QuestionID)
                                                LEFT JOIN db_it.surv_question_category sqc ON (sqc.ID = sq.QCID)
                                                LEFT JOIN db_it.surv_question_type sqt ON (sqt.ID = sq.QTID)
                                                WHERE ssd.SurveyId = "'.$SurveyID.'" ORDER BY ssd.Queue ASC ')
                                    ->result_array();


                    if(count($data)>0){
                        for($i=0;$i<count($data);$i++){
                            if ($data[$i]['stat']==0||$data[$i]['stat']==null) {
                                $AverageRate = '<input type="checkbox" class="selectQuestion" name="selectQuestion[]" value="'.$data[$i]['QuestionID'].'"></input>';
                            }else{
                                $AverageRate = '<input type="checkbox" class="selectQuestion" name="selectQuestion[]" value="'.$data[$i]['QuestionID'].'" checked="checked"></input>';
                            }

                            $data[$i]['AverageRate'] = '<div style="text-align: center;">'.$AverageRate.'</div>';
                        }
                    }
                    return print_r(json_encode($data));
                }

    }

}
