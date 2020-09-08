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


                // Total link
                $dataTotalLink = $this->db->query('SELECT COUNT(*) AS Total 
                                            FROM db_it.surv_survey_detail 
                                            WHERE QuestionID = "'.$row['ID'].'" ')
                    ->result_array()[0]['Total'];

                $tokenID = $this->jwt->encode(array('ID'=>$row['ID']),'UAP)(*');

                $btnAct = '<div class="btn-group">
                          <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-pencil"></i> <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="'.base_url('survey/create-question?tkn='.$tokenID).'" target="_blank">Edit</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="javascript:void(0)" data-id="'.$row['ID'].'" class="removeThisQuestion">Remove</a></li>
                          </ul>
                        </div>';

                $showBtnAct = ($dataTotalLink<=0)
                    ? $btnAct : '-';


                $isRequied = ($row['IsRequired']=='1')
                    ? '<span class="label label-danger">Required</span>'
                    : '<span class="label label-warning">Optional</span>';

                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div style="text-align: left;">'.$row['Question'].'</div>';
                $nestedData[] = '<a href="javascript:void(0);" 
                                    class="showLinkSurvey" data-id="'.$row['ID'].'">'.$dataTotalLink.'</a>';
                $nestedData[] = $showBtnAct;
                $nestedData[] = '<div>'.$row['QuestionCategory'].'<br/>
                                    <span class="label label-success">'.$row['QuestionType'].'</span>
                                    '.$isRequied.'</div>';

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

        else if($data_arr['action']=='removeQuestion'){

            $QuestionID = $data_arr['ID'];

            // Cek apakah pertanyaan di gunakan dalam survey ?
            $dataCk = $this->db->query('SELECT COUNT(*) AS Total 
                                            FROM db_it.surv_survey_detail 
                                            WHERE QuestionID = "'.$QuestionID.'" ')
                            ->result_array()[0]['Total'];

            $Status = 0;

            if($dataCk<=0){

                $dataQuestion = $this->db->query('SELECT sq.SummernoteID FROM db_it.surv_question sq 
                                        WHERE sq.ID = "'.$QuestionID.'" ')
                    ->result_array();

                if(count($dataQuestion)>0){
                    for($i=0;$i<count($dataQuestion);$i++){

                        $SummernoteID = $dataQuestion[$i]['SummernoteID'];

                        $this->m_rest
                            ->checkImageSummernote('delete',$SummernoteID,
                                'db_it.surv_question','Question');
                    }
                }

                $this->db->where('ID', $QuestionID);
                $this->db->delete('db_it.surv_question');
                $Status = 1;
            }

            return print_r(json_encode(array('Status' => $Status)));

        }

        else if($data_arr['action']=='showLinkQuestion'){

            $QuestionID = $data_arr['ID'];

            // Cek apakah pertanyaan di gunakan dalam survey ?
            $data = $this->db->query('SELECT ss.Title, ss.StartDate, ss.EndDate, ss.Status  
                                            FROM db_it.surv_survey_detail ssd
                                            LEFT JOIN db_it.surv_survey ss ON (ss.ID = ssd.SurveyID) 
                                            WHERE ssd.QuestionID = "'.$QuestionID.'" ')
                ->result_array();

            return print_r(json_encode($data));

        }

        else if($data_arr['action']=='getMasterQuestion'){

            $requestData = $_REQUEST;

            $Type = $data_arr['Type'];
            $QuestionCategory = $data_arr['QuestionCategory'];

            $dataWhere = '';
            if($Type!='' || $QuestionCategory!=''){
                $w_Type = ($Type!='')
                    ? 'AND sq.QTID = "'.$Type.'" ' : '';
                $w_QuestionCategory = ($QuestionCategory!='')
                    ? 'AND sq.QCID = "'.$QuestionCategory.'" ' : '';

                $dataWhere = $w_Type.$w_QuestionCategory;
            }

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataScr = 'sq.Question LIKE "%'.$search.'%"';

                $dataSearch = ' AND ('.$dataScr.')';
            }

            $queryDefault = 'SELECT sq.ID, sq.Question, sq.IsRequired, sq.AnswerType,  
                                             sqc.Description AS QuestionCategory, 
                                             sqt.Description AS QuestionType FROM db_it.surv_question sq
                                            LEFT JOIN db_it.surv_question_category sqc ON (sqc.ID = sq.QCID)
                                            LEFT JOIN db_it.surv_question_type sqt ON (sqt.ID = sq.QTID)
                                            WHERE sq.DepartmentID = "'.$data_arr['DepartmentID'].'" 
                                            '.$dataWhere.$dataSearch;

            $queryDefaultTotal = 'SELECT COUNT(*) AS Total FROM ('.$queryDefault.') xx';

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefaultTotal)->result_array()[0]['Total'];

            $no = $requestData['start'] + 1;
            $data = array();

            for($i=0;$i<count($query);$i++) {

                $nestedData = array();
                $row = $query[$i];

                $btnAct = '<div style="margin-bottom: 10px;">
                                <button class="btn btn-info btn-sm btnAddToSurvey" data-id="'.$row['ID'].'"><i class="fa fa-arrow-left margin-right"></i> Add to survey</button></div>';

                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div style="text-align: left;">'.$btnAct.$row['Question'].'</div>';
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

        else if($data_arr['action']=='addQuestionToSurvey'){

            $dataInsrt = array(
                'SurveyID' => $data_arr['SurveyID'],
                'QuestionID' => $data_arr['QuestionID']
            );

            // Cek apakah sudah ada atau blm
            $dataCk = $this->db->get_where('db_it.surv_survey_detail',$dataInsrt)->result_array();

            $result = array('Status'=>0);

            if(count($dataCk)<=0){
                // Dapetin urutan
                $TotalQuestion = $this->db->query('SELECT COUNT(*) AS Total 
                                        FROM db_it.surv_survey_detail 
                                        WHERE SurveyID = "'.$data_arr['SurveyID'].'" ')
                    ->result_array()[0]['Total'];

                $dataInsrt['Queue'] = $TotalQuestion + 1;
                $this->db->insert('db_it.surv_survey_detail',$dataInsrt);
                $result = array('Status'=>1);
            }

            return print_r(json_encode($result));


        }

        else if($data_arr['action']=='QuestionInMySurvey'){

            $SurveyID = $data_arr['SurveyID'];

            $data = $this->db->query('SELECT ssd.ID, sq.ID AS QuestionID , sq.Question,   
                                            sqc.Description AS Category, ssd.Queue
                                            FROM db_it.surv_survey_detail ssd
                                            LEFT JOIN db_it.surv_question sq ON (sq.ID = ssd.QuestionID)
                                            LEFT JOIN db_it.surv_question_category sqc ON (sqc.ID = sq.QCID)
                                            WHERE ssd.SurveyID = "'.$SurveyID.'" ORDER BY ssd.Queue ASC')->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='removeQUestionFromSurvey'){

            $this->db->where('ID',$data_arr['ID']);
            $this->db->delete('db_it.surv_survey_detail');

            return print_r(1);

        }

        else if($data_arr['action']=='updateQueueQuestion'){
            $this->db->where('ID', $data_arr['ID']);
            $this->db->update('db_it.surv_survey_detail'
                ,array('Queue' => $data_arr['Queue']));
            return print_r(1);
        }

        else if($data_arr['action']=='updateDataQuestion'){

            $ID = $data_arr['ID'];
            $dataQuestion = (array) $data_arr['dataQuestion'];

            if($ID!=''){
                // Update
                // Cek apakah pertanyaan sudah di assign ke dalam survey atau blm
                $dataCk = $this->db->query('SELECT COUNT(*) AS Total 
                                            FROM db_it.surv_survey_detail 
                                            WHERE QuestionID = "'.$ID.'" ')
                    ->result_array()[0]['Total'];

                if($dataCk<=0) {
                    $dataQuestion['UpdatedBy'] = $data_arr['NIP'];
                    $dataQuestion['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                    $this->db->where('ID', $ID);
                    $this->db->update('db_it.surv_question',$dataQuestion);
                    $Status = 1;
                } else {
                    $Status = 0;
                }

            }
            else {
                // Insert
                $dataQuestion['CreatedBy'] = $data_arr['NIP'];
                $this->db->insert('db_it.surv_question',$dataQuestion);

                $Status = 1;
            }

            if($Status==1 || $Status=='1'){

                $SummernoteID = $dataQuestion['SummernoteID'];
                // Cek image in summernote
                $this->m_rest
                    ->checkImageSummernote('insert',$SummernoteID,'db_it.surv_question','Question');

            }

            return print_r(json_encode(array('Status'=> $Status)));
        }
        else if($data_arr['action']=='updateSurvey'){

            $ID = $data_arr['ID'];
            $dataSurvey = (array) $data_arr['dataSurvey'];

            if($ID!=''){
                // Update
                $dataSurvey['UpdatedBy'] = $data_arr['NIP'];
                $dataSurvey['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID', $ID);
                $this->db->update('db_it.surv_survey',$dataSurvey);
            } else {
                // Insert
                $dataSurvey['CreatedBy'] = $data_arr['NIP'];
                $this->db->insert('db_it.surv_survey',$dataSurvey);
            }

            return print_r(1);

        }
        else if($data_arr['action']=='getListSurvey'){

            $requestData = $_REQUEST;

            $dataWhere = '';

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataScr = 'sq.Question LIKE "%'.$search.'%"';
                $dataSearch = '';
            }

            $queryDefault = 'SELECT ss.* FROM db_it.surv_survey ss WHERE ss.DepartmentID = "'.$data_arr['DepartmentID'].'"  '. $dataWhere.$dataSearch;

            $queryDefaultTotal = 'SELECT COUNT(*) AS Total FROM ('.$queryDefault.') xx';

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefaultTotal)->result_array()[0]['Total'];

            $no = $requestData['start'] + 1;
            $data = array();

            for($i=0;$i<count($query);$i++) {

                $nestedData = array();
                $row = $query[$i];

                $Range = date('d M Y',strtotime($row['StartDate'])).' - '.
                    date('d M Y',strtotime($row['EndDate']));


                $Status = '<span class="label label-warning">Unpublish</span>';
                $btnClose = 'hide';
                $btnPublish = '';
                $btnRemove = '';
                if($row['Status']=='1'){
                    $Status = '<span class="label label-success">Publish</span>';
                    $btnClose = '';
                    $btnPublish = 'hide';
                    $btnRemove = 'hide';
                } else if ($row['Status']=='2'){
                    $Status = '<span class="label label-danger">Close</span>';
                    $btnClose = 'hide';
                    $btnPublish = 'hide';
                    $btnRemove = 'hide';
                }

                $tokenBtn = $this->jwt->encode(array('ID' => $row['ID']),"UAP)(*");

                $btnAct = '<div class="btn-group">
                              <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-pencil"></i> <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu">
                                <li class="'.$btnPublish.'" id="li_btn_Publish_'.$row['ID'].'">
                                        <a href="javascript:void(0);" class="btnPublishSurvey" data-id="'.$row['ID'].'">Publish</a>
                                        </li>
                                <li class="'.$btnClose.'" id="li_btn_Close_'.$row['ID'].'">
                                        <a href="javascript:void(0);" class="btnCloseSurvey" data-id="'.$row['ID'].'" style="color: red;">Close</a>
                                 </li>
                                <li role="separator" class="divider"></li>
                                <li><a href="javascript:void(0);" class="btnEditSurvey" data-id="'.$row['ID'].'">View Survey</a></li>
                                <li><a href="javascript:void(0);" class="btnManageTarget" data-id="'.$row['ID'].'">Manage Targets</a></li>
                                <li><a href="'.base_url('survey/manage-question/'.$tokenBtn).'" target="_blank">Manage Question</a></li>
                                <li role="separator" class="divider"></li>
                                <li class="'.$btnRemove.'"><a href="#">Remove</a></li>
                              </ul>
                            </div>';

                // Cek jumlah yang sudah mengisi survey
                $TotalYgUdahIsiSurvey = $this->db->from('db_it.surv_answer')->where('SurveyID',$row['ID'])->count_all_results();
                $btnTotalAlreadyFillOut = ($TotalYgUdahIsiSurvey>0)
                    ? '<a href="javascript:void(0)" class="showAlreadyFillOut" data-id="'.$row['ID'].'">'.$TotalYgUdahIsiSurvey.'</a>'
                    : '0';

                $TotalQuestion = $this->db->from('db_it.surv_survey_detail')->where('SurveyID',$row['ID'])->count_all_results();
                $btnShowTotalQuestion = ($TotalQuestion>0) ? '<a href="javascript:void(0)" class="showQuestionList" data-id="'.$row['ID'].'">'.$TotalQuestion.'</a>' : '0';


                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div style="text-align: left;">'.$row['Title'].'</div>';
                $nestedData[] = $btnShowTotalQuestion;
                $nestedData[] = $btnTotalAlreadyFillOut;
                $nestedData[] = '<div>'.$btnAct.'</div>';
                $nestedData[] = '<div>'.$Range.'</div>';
                $nestedData[] = '<div id="viewStatusSurvey_'.$row['ID'].'">'.$Status.'</div>';

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

        else if($data_arr['action']=='showQuestionInSurvey'){

            $SurveyID = $data_arr['SurveyID'];

            $data = $this->db->query('SELECT ssd.QuestionID, sq.Question, sq.QTID, sqc.Description AS Category, 
                                                 sqt.Description AS Type
                                                FROM db_it.surv_survey_detail ssd
                                                LEFT JOIN db_it.surv_question sq ON (sq.ID = ssd.QuestionID)
                                                LEFT JOIN db_it.surv_question_category sqc ON (sqc.ID = sq.QCID)
                                                LEFT JOIN db_it.surv_question_type sqt ON (sqt.ID = sq.QTID)
                                                WHERE ssd.SurveyId = "'.$SurveyID.'" ORDER BY ssd.Queue ASC ')
                                    ->result_array();

            if(count($data)>0){
                for($i=0;$i<count($data);$i++){

                    $AverageRate = '';

                    if($data[$i]['QTID']=='4' || $data[$i]['QTID']==4){
                        // Cek berapa yang udah jawab
                        $dataTotalJawaban = $this->db->get_where('db_it.surv_answer_detail',
                            array(
                                'SurveyID' => $SurveyID,
                                'QuestionID' => $data[$i]['QuestionID'],
                                'QTID' => $data[$i]['QTID']
                            ))->result_array();

                        if(count($dataTotalJawaban)>0){
                            $TotalRate = 0;
                            for($r=0;$r<count($dataTotalJawaban);$r++){
                                $TotalRate = $TotalRate+$dataTotalJawaban[$r]['Rate'];
                            }
                            $AverageRate = number_format(round($TotalRate / count($dataTotalJawaban)),2);
                        }



                    }

                    $data[$i]['AverageRate'] = $AverageRate;
                }
            }


            return print_r(json_encode($data));


        }

        else if($data_arr['action']=='showUserAlreadyFill'){

            $requestData = $_REQUEST;
            $dataWhere = '';
            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataScr = ' AND (sa.Username LIKE "%'.$search.'%" OR 
                                em.Name LIKE "%'.$search.'%"  OR 
                                ats.Name LIKE "%'.$search.'%")';
                $dataSearch = $dataScr;
            }

            $queryDefault = 'SELECT sa.*,  CASE WHEN  em.Name IS NOT NULL THEN em.Name
                                                ELSE ats.Name END AS "Name" FROM db_it.surv_answer sa 
                                                LEFT JOIN db_employees.employees em ON (em.NIP = sa.Username)
                                                LEFT JOIN db_academic.auth_students ats ON (ats.NPM = sa.Username)
                                        WHERE sa.SurveyID = "'.$data_arr['SurveyID'].'"  '.
                                        $dataWhere.$dataSearch;

            $queryDefaultTotal = 'SELECT COUNT(*) AS Total FROM ('.$queryDefault.') xx';

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefaultTotal)->result_array()[0]['Total'];

            $no = $requestData['start'] + 1;
            $data = array();

            for($i=0;$i<count($query);$i++) {

                $nestedData = array();
                $row = $query[$i];

                $Type = ($row['Type']=='std')
                    ? '<span class="label label-primary">Student</span>'
                    : '<span class="label label-success">Emp / Lec</span>';

                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div style="text-align: left;"><b>'.$row['Name'].'</b><br/>'.$row['Username'].'</div>';
                $nestedData[] = '<div>'.$Type.'</div>';
                $nestedData[] = '<div>'.date('d M Y H:i',strtotime($row['EntredAt'])).'</div>';

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

        else if($data_arr['action']=='getOneDataSurvey'){
            $ID = $data_arr['ID'];
            $data = $this->db->get_where('db_it.surv_survey',array('ID' => $ID))->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='setStatusSurvey'){

            $dataFmQuiz['Status'] = $data_arr['Status'];
            $dataFmQuiz['UpdatedBy'] = $data_arr['NIP'];
            $dataFmQuiz['UpdatedAt'] = $this->m_rest->getDateTimeNow();
            $this->db->where('ID', $data_arr['ID']);
            $this->db->update('db_it.surv_survey',$dataFmQuiz);

            $row_Status = $data_arr['Status'];
            $Status = '<span class="label label-warning">Unpublish</span>';
            if($row_Status=='1'){
                $Status = '<span class="label label-success">Publish</span>';
            } else if ($row_Status=='2'){
                $Status = '<span class="label label-danger">Close</span>';
            }

            return print_r(json_encode(array('Status'=>1,'Label' => $Status)));

        }

        else if($data_arr['action']=='updateTargetSurvey'){

            $ID = $data_arr['ID'];

            // ======= Employees ========

            $surv_survey_usr_emp = $data_arr['surv_survey_usr_emp'];
            // Remove data sebelumnya
            $this->db->where('SurveyID',$ID);
            $this->db->delete('db_it.surv_survey_usr_emp');
            $this->db->reset_query();

            if($surv_survey_usr_emp!='-1' && $surv_survey_usr_emp!=-1){
                $this->db->insert('db_it.surv_survey_usr_emp',
                    array('SurveyID' => $ID,'TypeUser' => $surv_survey_usr_emp));
                $this->db->reset_query();
            }

            // ====== Student ========
            $surv_survey_usr_std = $data_arr['surv_survey_usr_std'];

            if($surv_survey_usr_std!=0 && $surv_survey_usr_std!='0'){
                // Remove data sebelumnya
                $dataCk = $this->db->select('ID')->get_where('db_it.surv_survey_usr_std',
                    array('SurveyID' => $ID))->result_array();

                if(count($dataCk)>0){
                    $SUSID = $dataCk[0]['ID'];
                    $this->db->where('SUSID',$SUSID);
                    $this->db->delete('db_it.surv_survey_usr_std_details');
                    $this->db->reset_query();

                    $this->db->where('ID',$SUSID);
                    $this->db->delete('db_it.surv_survey_usr_std');
                    $this->db->reset_query();
                }

                if($surv_survey_usr_std!='-1' && $surv_survey_usr_std!=-1){
                    $this->db->insert('db_it.surv_survey_usr_std',
                        array('SurveyID' => $ID,'TypeUser' => $surv_survey_usr_std));
                    $this->db->reset_query();
                }
            }



            return print_r(1);


        }

        else if($data_arr['action']=='setDataTargetUsrtStdDetail'){

            // cek apakah ID sudah ada di table surv_survey_usr_std
            $dataCk = $this->db->select('ID')->get_where('db_it.surv_survey_usr_std',
                array('SurveyID' => $data_arr['ID']))
                ->result_array();



            if(count($dataCk)>0){
                $SUSID = $dataCk[0]['ID'];
                $this->db->where('ID', $SUSID);
                $this->db->update('db_it.surv_survey_usr_std'
                    ,array('TypeUser' => '0'));
            } else {
                $dataIns = array(
                    'SurveyID' => $data_arr['ID'],
                    'TypeUser' => '0'
                );
                $this->db->insert('db_it.surv_survey_usr_std',$dataIns);
                $SUSID = $this->db->insert_id();
            }

            $dataForm = (array) $data_arr['dataForm'];
            $dataForm['SUSID'] = $SUSID;

            $dataCk2 = $this->db->select('ID')
                ->get_where('db_it.surv_survey_usr_std_details',$dataForm)
                ->result_array();

            $result = array('Status' => 0);

            if(count($dataCk2)<=0) {
                $result = array('Status' => 1);
                $this->db->insert('db_it.surv_survey_usr_std_details', $dataForm);
            }


            return print_r(json_encode($result));

        }

        else if($data_arr['action']=='removeDataFromTargetUsrtStdDetail'){

            $this->db->where('ID', $data_arr['ID']);
            $this->db->delete('db_it.surv_survey_usr_std_details');

            return print_r(1);

        }

        else if($data_arr['action']=='getDataTargetSurvey'){
            $ID = $data_arr['ID'];

            // Data survey
            $dataSurv = $this->db->get_where('db_it.surv_survey',
                array('ID' => $ID))->result_array();

            $dataEmp = $this->db->get_where('db_it.surv_survey_usr_emp',
                            array('SurveyID' => $ID))->result_array();

            $dataStd = $this->db->get_where('db_it.surv_survey_usr_std',
                            array('SurveyID' => $ID))->result_array();

            $dataSurv[0]['Employee'] = $dataEmp;
            $dataSurv[0]['Student'] = $dataStd;

            return print_r(json_encode($dataSurv[0]));

        }
        else if($data_arr['action']=='getDataTargetCustomStudent'){
            $ID = $data_arr['ID'];
            $dataStd = $this->db->get_where('db_it.surv_survey_usr_std',
                array('SurveyID' => $ID))->result_array();

            $result =[];

            if(count($dataStd)>0){
                $d = $dataStd[0];
                $result = $this->db
                    ->query('SELECT ssusd.ID AS TargetUserID, ssusd.ClassOf, ps.NameEng AS Prodi, ss.Description 
                                    FROM db_it.surv_survey_usr_std_details ssusd
                                    LEFT JOIN db_academic.program_study ps 
                                    ON (ps.ID = ssusd.ProdiID)
                                    LEFT JOIN db_academic.status_student ss 
                                    ON (ss.ID = ssusd.StatusStudentID)
                                    WHERE ssusd.SUSID = "'.$d['ID'].'" 
                                    ORDER BY ps.NameEng ASC, ssusd.ClassOf DESC, ss.ID ASC ')
                    ->result_array();
            }

            return print_r(json_encode($result));

        }

    }



}
