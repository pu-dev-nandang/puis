<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_api_menu extends CI_Controller {

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

    public function crudShareMenu(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='getShareMenu'){

            $data = $this->db->get('db_it.sm_menu')->result_array();

            if(count($data)>0){

                for($i=0;$i<count($data);$i++){
                    $data[$i]['Child'] = $this->db->get_where('db_it.sm_menu_details',
                        array('IDSM' => $data[$i]['ID']))->result_array();
                }

            }

            return print_r(json_encode($data));

        }

    }


    public function crudSurvey(){
        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='getSurvQuestionType'){
            $data = $this->db->get_where('db_it.surv_question_type',array('IsActive'=>'1'))->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='updateQuestionCategory'){
            $ID = $data_arr['ID'];
            $dataForm = array(
                'Description' => $data_arr['Description'],
                'DepartmentID' => $data_arr['DepartmentID']
            );

            if($ID!=''){
                // Update
                $dataForm['UpdatedBy'] = $data_arr['NIP'];
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID', $ID);
                $this->db->update('db_it.surv_question_category',$dataForm);
            } else {
                // Insert
                $dataForm['CreatedBy'] = $data_arr['NIP'];
                $this->db->insert('db_it.surv_question_category',$dataForm);
            }

            return print_r(1);

        }
        else if($data_arr['action']=='getQuestionCategory'){

            $data = $this->db->order_by('ID','DESC')->get_where('db_it.surv_question_category',array(
                'DepartmentID' => $data_arr['DepartmentID']
            ))->result_array();

            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    $data[$i]['Link'] = $this->db
                        ->query('SELECT COUNT(*) AS Total 
                                    FROM db_it.surv_question 
                                    WHERE QCID = "'.$data[$i]['ID'].'" ')
                        ->result_array()[0]['Total'];
                }
            }

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removeQuestionCategory'){

            $dataCk = $this->db
                ->query('SELECT COUNT(*) AS Total 
                                    FROM db_it.surv_question 
                                    WHERE QCID = "'.$data_arr['ID'].'" ')
                ->result_array()[0]['Total'];

            $result = array('Status' => 0);

            if($dataCk<=0){
                $this->db->where('ID', $data_arr['ID']);
                $this->db->delete('db_it.surv_question_category');
                $result = array('Status' => 1);
            }


            return print_r(json_encode($result));
        }
        else if($data_arr['action']=='readDataQuestion'){
            $ID = $data_arr['ID'];
            $data = $this->db->get_where('db_it.surv_question',
                array('ID' => $ID))->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='getBankQuestion'){

            $requestData = $_REQUEST;

            $Type = $data_arr['Type'];
            $QuestionCategory = $data_arr['QuestionCategory'];

            $dataWhere = '';
            if($Type!='' || $QuestionCategory!=''){
                $w_Type = ($Type!='')
                    ? 'AND sq.QTID = "'.$Type.'" ' : '';
                $w_QuestionCategory = ($QuestionCategory!='')
                    ? 'AND sq.QCID = "'.$QuestionCategory.'" ' : '';

                $w = $w_Type.$w_QuestionCategory;
                $dataWhere = ' WHERE '.substr($w,3);
            }

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataScr = 'sq.Question LIKE "%'.$search.'%"';
                $dataSearch = ($Type!='' || $QuestionCategory!='')
                    ? ' AND ('.$dataScr.')'
                    : ' WHERE '.$dataScr;
            }

            $queryDefault = 'SELECT sq.ID, sq.Question, sq.IsRequired, sq.AnswerType,  
                                             sqc.Description AS QuestionCategory, 
                                             sqt.Description AS QuestionType FROM db_it.surv_question sq
                                            LEFT JOIN db_it.surv_question_category sqc ON (sqc.ID = sq.QCID)
                                            LEFT JOIN db_it.surv_question_type sqt ON (sqt.ID = sq.QTID) 
                                            '. $dataWhere.$dataSearch;

            $queryDefaultTotal = 'SELECT COUNT(*) AS Total FROM ('.$queryDefault.') xx';

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefaultTotal)->result_array()[0]['Total'];

            $no = $requestData['start'] + 1;
            $data = array();

            for($i=0;$i<count($query);$i++) {

                $nestedData = array();
                $row = $query[$i];

                $tokenID = $this->jwt->encode(array('ID'=>$row['ID']),'UAP)(*');

                $btnAct = '<div class="btn-group">
                          <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-pencil"></i> <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#">Add to survey</a></li>
                            <li><a href="'.base_url('survey/create-question?tkn='.$tokenID).'" target="_blank">Edit</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Remove</a></li>
                          </ul>
                        </div>';

                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div style="text-align: left;">'.$row['Question'].'</div>';
                $nestedData[] = $btnAct;
                $nestedData[] = '<div>'.$row['QuestionCategory'].'<br/>
                                    <span class="label label-success">'.$row['QuestionType'].'</span></div>';

                $data[] = $nestedData;
                $no++;


            }

            $json_data = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval($queryDefaultRow),
                "recordsFiltered" => intval( $queryDefaultRow),
                "data"            => $data,
                "dataQuery"            => $query
            );
            echo json_encode($json_data);


        }
        else if($data_arr['action']=='updateDataQuestion'){

            $ID = $data_arr['ID'];
            $dataQuestion = (array) $data_arr['dataQuestion'];

            if($ID!=''){
                // Update
                $dataQuestion['UpdatedBy'] = $data_arr['NIP'];
                $dataQuestion['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID', $ID);
                $this->db->update('db_it.surv_question',$dataQuestion);
            } else {
                // Insert
                $dataQuestion['CreatedBy'] = $data_arr['NIP'];
                $this->db->insert('db_it.surv_question',$dataQuestion);
            }

            return print_r(1);
        }
    }



}
