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


    public function detailEmployeeOBJ(){
        $this->load->model(array('General_model','hr/m_hr'));
        $data = $this->input->post();
        $json = array();
        if($data){
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data['token'],$key);
            $isExist = $this->General_model->fetchData("db_employees.employees",array("NIP"=>$data_arr['NIP']))->row();
            if(!empty($isExist)){
                //$isExist->MyCareer = $this->General_model->fetchData("db_employees.employees_career",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyHistorical = $this->General_model->fetchData("db_employees.employees_joindate",array("NIP"=>$isExist->NIP),"ID","desc")->result();
                $isExist->MyCareer = $this->m_hr->getEmpCareer(array("a.NIP"=>$isExist->NIP,"isShowSTO"=>0))->result();
                $isExist->MyBank = $this->General_model->fetchData("db_employees.employees_bank_account",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducation = $this->General_model->fetchData("db_employees.employees_educations",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationNonFormal = $this->General_model->fetchData("db_employees.employees_educations_non_formal",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyEducationTraining = $this->General_model->fetchData("db_employees.employees_educations_training",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyFamily = $this->General_model->fetchData("db_employees.employees_family_member",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyExperience = $this->General_model->fetchData("db_employees.employees_experience",array("NIP"=>$isExist->NIP))->result();
                $isExist->MyBPJS = $this->General_model->fetchData("db_employees.employees_bpjs",array("NIP"=>$isExist->NIP),"Type","asc")->result();
                $isExist->MyAllowance = $this->General_model->fetchData("db_employees.employees_allowance",array("NIP"=>$isExist->NIP))->result();
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

    public function crudOnlineClass(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='getMonitoringAttd'){


            $ScheduleID = $data_arr['ScheduleID'];
            $Session = $data_arr['Session'];

            $dataLect = $this->m_rest->getAllLecturerByScheduleID($ScheduleID);

            // Get AttdID

            $dataArrAttdID = $this->db->query('SELECT attd.ID AS ID_Attd, d.NameEng, cl.Room, sd.StartSessions, sd.EndSessions 
                                                    FROM db_academic.attendance attd
                                                    LEFT JOIN db_academic.schedule_details sd ON (sd.ID = attd.SDID)
                                                    LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                                    WHERE attd.ScheduleID = "'.$ScheduleID.'" 
                                                    ORDER BY sd.DayID ASC')->result_array();

            if(count($dataLect)>0){

                for($i=0;$i<count($dataLect);$i++){

                    $d = $dataLect[$i];

                    // Cek Forum
                    $dataLect[$i]['Forum'] = $this->db->query('SELECT COUNT(*) AS Total FROM (SELECT ct.ID  
                                                            FROM db_academic.counseling_topic ct
                                                            WHERE ct.ScheduleID = "'.$ScheduleID.'" 
                                                            AND ct.Sessions = "'.$Session.'"
                                                             AND ct.CreateBy = "'.$d['NIP'].'" 
                                                             UNION ALL
                                                             SELECT ct.ID FROM db_academic.counseling_comment cc
                                                             LEFT JOIN db_academic.counseling_topic ct ON (ct.ID = cc.TopicID)
                                                            WHERE ct.ScheduleID = "'.$ScheduleID.'" 
                                                            AND ct.Sessions = "'.$Session.'"
                                                             AND cc.UserID = "'.$d['NIP'].'" ) xx  
                                                             ')->result_array()[0]['Total'];

                    // Cek Task
                    $dataLect[$i]['Task'] = $this->db->query('SELECT COUNT(*) AS Total FROM db_academic.schedule_task st
                                                                        WHERE st.ScheduleID = "'.$ScheduleID.'" 
                                                                        AND st.Session = "'.$Session.'"
                                                                        AND st.EntredBy = "'.$d['NIP'].'" ')
                                                                ->result_array()[0]['Total'];

                    // Cek Material
                    $dataLect[$i]['Material'] = $this->db->query('SELECT sm.File, em.NIP, em.Name FROM db_academic.schedule_material sm 
                                                                            LEFT JOIN db_employees.employees em ON (em.NIP = sm.UpdateBy)
                                                                            WHERE sm.ScheduleID = "'.$ScheduleID.'"
                                                                             AND sm.Session = "'.$Session.'" ')->result_array();

                    // Cek Attendance
                    if(count($dataArrAttdID)>0){
                        $whereAttd = '';
                        for ($r=0;$r<count($dataArrAttdID);$r++){
                            $or = ($r!=0) ? ' OR ' : '';
                            $whereAttd = $whereAttd.$or.' (al.ID_Attd = "'.$dataArrAttdID[$r]['ID_Attd'].'" AND Meet = "'.$Session.'") ';
                        }

                        $SessionAttend = $this->db->query('SELECT * 
                                                                    FROM db_academic.attendance_lecturers al 
                                                                    WHERE al.NIP = "'.$d['NIP'].'" 
                                                                    AND ( '.$whereAttd.') GROUP BY al.ID_Attd')->result_array();
                    }

                    $dataLect[$i]['SessionAttend'] = count($SessionAttend);
                    $dataLect[$i]['SessionAttendDetails'] = $SessionAttend;
                    $dataLect[$i]['SessionAttendSch'] = count($dataArrAttdID);
                }

            }

            $dataAttd = $this->db->query('SELECT attd.ID FROM db_academic.attendance attd WHERE 
                                                    attd.ScheduleID = "'.$ScheduleID.'" 
                                                    GROUP BY attd.ScheduleID')->result_array();
            $dataStd = [];
            if(count($dataAttd)>0){
                $dataStd = $this->db->query('SELECT auth.NPM, auth.Name FROM db_academic.attendance_students ats 
                                            LEFT JOIN db_academic.auth_students auth ON (auth.NPM = ats.NPM)
                                            WHERE ats.ID_Attd = "'.$dataAttd[0]['ID'].'" 
                                            GROUP BY ats.NPM ORDER BY auth.NPM ASC')->result_array();
            }


            if(count($dataStd)>0){
                for($i=0;$i<count($dataStd);$i++){
                    $d = $dataStd[$i];

                    // Comment
                    $dataStd[$i]['TotalComment'] = $this->db->query('SELECT COUNT(*) AS Total 
                                                    FROM db_academic.counseling_comment cc 
                                                    LEFT JOIN db_academic.counseling_topic ct ON (ct.ID = cc.TopicID)
                                                    WHERE ct.ScheduleID = "'.$ScheduleID.'"
                                                    AND ct.Sessions = "'.$Session.'" 
                                                    AND cc.UserID = "'.$d['NPM'].'" ')->result_array()[0]['Total'];

                    // Task
                    $dataStd[$i]['TotalTask'] = $this->db->query('SELECT std.ID FROM db_academic.schedule_task_student std
                                                                    LEFT JOIN db_academic.schedule_task st ON (st.ID = std.IDST)
                                                                    WHERE st.ScheduleID = "'.$ScheduleID.'"
                                                                     AND st.Session = "'.$Session.'" 
                                                                     AND std.NPM = "'.$d['NPM'].'"')->result_array();

                    $dataStd[$i]['TotalTaskRevisi'] = $this->db->query('SELECT stsr.EntredAt, em.Name 
                                                                                FROM db_academic.schedule_task_student_remove stsr 
                                                                                LEFT JOIN db_academic.schedule_task st 
                                                                                ON (st.ID = stsr.IDST)
                                                                                LEFT JOIN db_employees.employees em 
                                                                                ON (em.NIP = stsr.EntredBy) 
                                                                                WHERE st.ScheduleID = "'.$ScheduleID.'"
                                                                                AND st.Session = "'.$Session.'"
                                                                                AND stsr.NPM = "'.$d['NPM'].'"
                                                                                 ORDER BY stsr.ID ASC')
                                                            ->result_array();




                    // Attendance
                    $SessionAttend = [];
                    $SessionAttendTotal = 0;
                    if(count($dataArrAttdID)>0){
                        $whereAttd = '';
                        for ($r=0;$r<count($dataArrAttdID);$r++){
                            $or = ($r!=0) ? ' OR ' : '';
                            $whereAttd = $whereAttd.$or.' al.ID_Attd = "'.$dataArrAttdID[$r]['ID_Attd'].'" ';
                        }

                        $SessionAttend = $this->db->query('SELECT al.M'.$Session.' FROM db_academic.attendance_students al 
                                                                WHERE al.NPM = "'.$d['NPM'].'" AND ('.$whereAttd.')')->result_array();

                        if(count($SessionAttend)>0){
                            for ($s=0;$s<count($SessionAttend);$s++){
                                $ses = ($SessionAttend[$s]['M'.$Session]!=null && $SessionAttend[$s]['M'.$Session]!=''
                                    && $SessionAttend[$s]['M'.$Session]!=2 && $SessionAttend[$s]['M'.$Session]!='2') ? 1 : 0;
                                $SessionAttendTotal = $SessionAttendTotal + $ses;
                             }
                        }


                    }

                    $dataStd[$i]['SessionAttend'] = $SessionAttendTotal;
                    $dataStd[$i]['SessionAttendDetails'] = $SessionAttend;
                    $dataStd[$i]['SessionAttendSch'] = count($dataArrAttdID);


                }
            }


            $ScheduleTask = $this->db->get_where('db_academic.schedule_task',
                array(
                    'ScheduleID' => $ScheduleID,
                    'Session' => $Session
                ))->result_array();

            $result = array(
                'Schedule' => $dataArrAttdID,
                'Lecturer' => $dataLect,
                'Student' => $dataStd,
                'ScheduleTask' => $ScheduleTask
            );

            return print_r(json_encode($result));
        }
        else if($data_arr['action']=='removeTaskStudent'){

            $ID = $data_arr['ID'];

            $dataCk = $this->db->get_where('db_academic.schedule_task_student',
                array('ID' => $ID))->result_array();

            if(count($dataCk)>0){
                $d = $dataCk[0];

                // Insert ke table remove
                $data_arrIns = array(
                    'IDST' => $d['IDST'],
                    'NPM' => $d['NPM'],
                    'EntredBy' => $data_arr['NIP']
                );

                $this->db->insert('db_academic.schedule_task_student_remove',$data_arrIns);
                $this->db->reset_query();

                // Cek apakah file ada atau tidak
                if($d['File']!='' && $d['File']!=null){
                    $Path = './uploads/task/'.$d['File'];
                    if(file_exists($Path)){
                        unlink($Path);
                    }
                }

                $this->db->where('ID', $ID);
                $this->db->delete('db_academic.schedule_task_student');

            }

            return print_r(1);

        }
        else if($data_arr['action']=='getDataAttdOnlineStudent'){
            $ScheduleID = $data_arr['ScheduleID'];
            $NPM = $data_arr['NPM'];

            $result = [];

            for($i=1;$i<=14;$i++){

                // Task
                $dataTask = $this->db->query('SELECT COUNT(*) AS Total FROM db_academic.schedule_task_student sts 
                                                        LEFT JOIN  db_academic.schedule_task st
                                                        ON (sts.IDST = st.ID)
                                                        WHERE sts.NPM = "'.$NPM.'" AND 
                                                        st.ScheduleID = "'.$ScheduleID.'" AND
                                                        st.Session = "'.$i.'"
                                                           ')->result_array()[0]['Total'];


                // Forum
                $dataForum = $this->db->query('SELECT COUNT(*) AS Total FROM db_academic.counseling_comment cc
                                                        LEFT JOIN db_academic.counseling_topic ct 
                                                        ON (ct.ID = cc.TopicID)
                                                        WHERE cc.UserID = "'.$NPM.'" 
                                                        AND ct.ScheduleID = "'.$ScheduleID.'"
                                                         AND ct.Sessions = "'.$i.'" ')->result_array()[0]['Total'];

                // Attendance
                $dataAttd = $this->db->query('SELECT ID FROM db_academic.attendance 
                                                    WHERE ScheduleID = "'.$ScheduleID.'" ')->result_array();
                $dataPresent = 0;
                $dataAbsen = 0;
                if(count($dataAttd)>0){
                    for($a=0;$a<count($dataAttd);$a++){

                        $ID_Attd = $dataAttd[$a]['ID'];

                        $dt = $this->db->query('SELECT ast.M'.$i.' FROM db_academic.attendance_students ast 
                                                            WHERE 
                                                            ast.ID_Attd = "'.$ID_Attd.'" 
                                                            AND ast.NPM = "'.$NPM.'"
                                                             ')->result_array()[0]['M'.$i];

                        if($dt=='1'){
                            $dataPresent = $dataPresent + 1;
                        } else if($dt=='2') {
                            $dataAbsen = $dataAbsen + 1;
                        }
                    }
                }



                $result[$i-1] = array(
                    'Session' => $i,
                    'Task' => $dataTask,
                    'Forum' => $dataForum,
                    'AttdID' => count($dataAttd),
                    'Present' => $dataPresent,
                    'Present' => $dataPresent,
                    'Absen' => $dataAbsen
                );

            }

            return print_r(json_encode($result));

        }

    }


    public function getDataOnlineClass(){
        $requestData= $_REQUEST;
        $data_arr = $this->getInputToken2();

        $SemesterID = $data_arr['SemesterID'];
        $WhereProdi = ($data_arr['ProdiID']!='') ? ' AND sdc.ProdiID = "'.$data_arr['ProdiID'].'" ' : '';

        $dataSearch = '';
        if( !empty($requestData['search']['value']) ) {
            $search = $requestData['search']['value'];
            $dataSearch = ' AND (s.ClassGroup LIKE "%'.$search.'%" 
                                    OR mk.MKCode LIKE "%'.$search.'%" 
                                    OR mk.NameEng LIKE "%'.$search.'%"
                                    ) ';
        }

        $queryDefault = 'SELECT s.ID AS ScheduleID, s.ClassGroup, mk.NameEng AS CourseEng 
                                    FROM db_academic.schedule s
                                    LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                    WHERE s.SemesterID = "'.$SemesterID.'" AND s.OnlineLearning = "1" '.$WhereProdi.$dataSearch.'
                                    
                                    GROUP BY s.ID ';

        $queryDefaultTotal = 'SELECT COUNT(*) AS Total FROM (SELECT s.ID FROM db_academic.schedule s
                                    LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                    WHERE s.SemesterID = "'.$SemesterID.'" AND s.OnlineLearning = "1" '.$WhereProdi.$dataSearch.'
                                    GROUP BY s.ID ) xx';

        $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';
        $query = $this->db->query($sql)->result_array();
        $queryDefaultRow = $this->db->query($queryDefaultTotal)->result_array()[0]['Total'];

        $no = $requestData['start'] + 1;
        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];

            $dataLect = $this->m_rest->getAllLecturerByScheduleID($row['ScheduleID']);
            $viewLec = '';
            if(count($dataLect)>0){
                for ($t=0;$t<count($dataLect);$t++){
                    $co = ($t==0) ? ' (Co)' : '';
                    $viewLec = $viewLec.'<div>'.$dataLect[$t]['NIP'].' - '.$dataLect[$t]['Name'].$co.'</div>';
                }
            }

            $nestedData[] = '<div>'.$no.'<textarea class="hide" id="text_'.$row['ScheduleID'].'">'.json_encode($row).'</textarea></div>';
            $nestedData[] = '<div style="text-align: left;"><b>'.$row['CourseEng'].'</b>
                                    <div style="font-size: 12px;">Group : '.$row['ClassGroup'].'</div>
                                    <div>'.$viewLec.'</div>
                                    </div>';
//            $nestedData[] = '<div>'.$no.'</div>';


            for($s=1;$s<=14;$s++){
                // Get date
                $dataSes = $this->m_rest->getRangeDateLearningOnlinePerSession($row['ScheduleID'],$s);

                // Material
                $viewMaterial = (count($dataSes['dataMaterial']))
                    ? '<div><a href="'.url_sign_in_lecturers.'uploads/material/'.$dataSes['dataMaterial'][0]['File'].'" target="_blank">
                                <span class="label label-default"><b>Material</b></span></a></div>'
                    : '';

                $rangeSt = date('d/M/Y',strtotime($dataSes['RangeStart']));
                $rangeEn = date('d/M/Y',strtotime($dataSes['RangeEnd']));

                $bg = ($dataSes['Status']=='1' || $dataSes['Status']==1)
                    ? 'background: #ffeb3b42;border: 1px solid #9E9E9E;border-radius: 5px;' : '';

                // Cek Topik
                $viewCkTopik = ($dataSes['CheckTopik']>0)
                    ? '<a href="javascript:void(0);" data-schid="'.$row['ScheduleID'].'" data-session="'.$s.'" class="btnAdmShowForum">
                            <div><span class="label label-primary"><b>Forum '.$dataSes['TotalComment'].'</b></span></div></a>'
                    : '';

                // Cek Task
                $viewTask = ($dataSes['CheckTask']>0)
                    ? '<a href="javascript:void(0);" data-schid="'.$row['ScheduleID'].'" data-session="'.$s.'" class="btnAdmShowTask">
                            <div><span class="label label-success"><b>Task '.$dataSes['TotalTask'].'</b></span></div></a>'
                    : '';

                 $arr = '<div style="'.$bg.'padding-top: 5px;padding-bottom: 5px;">
                                    '.$viewCkTopik.$viewTask.$viewMaterial.'
                                    <a href="javascript:void(0);" data-active="'.$dataSes['Status'].'" data-schid="'.$row['ScheduleID'].'" 
                                    data-session="'.$s.'" data-start="'.$dataSes['RangeStart'].'" 
                                    data-end="'.$dataSes['RangeEnd'].'" class="btnAdmShowAttendance">
                                    <div style="font-size: 10px;color: #607d8b;margin-top: 5px;font-weight: bold;">
                                    '.$rangeSt.'<br/>'.$rangeEn.'</div>
                                    </div></a>';
                 array_push($nestedData,$arr);
            }

            $no++;
            $data[] = $nestedData;

        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($queryDefaultRow),
            "recordsFiltered" => intval( $queryDefaultRow) ,
            "data"            => $data
        );

        echo json_encode($json_data);

    }

    public function crudExamOnline(){

        $data_arr = $this->getInputToken();

        if($data_arr['action']=='insertDateTimeExamOnline'){

            $ExamID = $data_arr['ExamID'];
            $NPM = $data_arr['NPM'];

            $ck = $this->db->get_where('db_academic.exam_student_online',
                array(
                    'ExamID' => $ExamID,
                    'NPM' => $NPM
                ))->result_array();

            if(count($ck)<=0){


                $dataInsert = array(
                    'ExamID' => $ExamID,
                    'NPM' => $NPM
                );
                $this->db->insert('db_academic.exam_student_online',
                    $dataInsert);
                $this->db->reset_query();


                $this->db->set('Status', '1');
                $this->db->where($dataInsert);
                $this->db->update('db_academic.exam_details');
                $this->db->reset_query();

                $this->db->set('Status', '-1');
                $this->db->where(array(
                    'ExamID' => $ExamID,
                    'Status' => '0'
                ));
                $this->db->update('db_academic.exam_details');


            }

            return print_r(1);

        }
        else if($data_arr['action']=='loadChatExamOnline'){
            $ExamID = $data_arr['ExamID'];
            $data = $this->db->order_by('ID','DESC')->order_by('ID','ASC')
                ->get_where('db_academic.exam_task_chat',
                    array('ExamID' => $ExamID))
                ->result_array();

            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    $d = $data[$i];
                    if($d['TypeUser']=='std'){
                        $Name = $this->db->select('Name')->get_where('db_academic.auth_students',
                            array('NPM' => $d['UserID']))->result_array()[0]['Name'];
                    } else {
                        $Name = $this->db->select('Name')->get_where('db_employees.employees',
                            array('NIP' => $d['UserID']))->result_array()[0]['Name'];
                    }
                    $data[$i]['Name'] = $Name;
                }
            }

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='insertChatExamOnline'){
            $dataForm = (array) $data_arr['dataForm'];

            $this->db->insert('db_academic.exam_task_chat',$dataForm);
            return print_r(1);
        }
        else if($data_arr['action']=='insertInvigilator'){
            $dataForm = (array) $data_arr['dataForm'];

            $ck = $this->db->get_where('db_academic.exam_employees_online',
                $dataForm)->result_array();

            if(count($ck)<=0){
                $this->db->insert('db_academic.exam_employees_online',$dataForm);
            }
            return print_r(1);
        }
        else if($data_arr['action']=='loadDataExamOnline'){
            $ExamID = $data_arr['ExamID'];
            $data = $this->db->query('SELECT eso.*, ats.Name FROM 
                                                db_academic.exam_student_online eso 
                                                LEFT JOIN db_academic.auth_students ats ON (ats.NPM = eso.NPM)
                                                WHERE eso.ExamID = "'.$ExamID.'"
                                                 ORDER BY eso.StartWorking ASC ')->result_array();

            return print_r(json_encode($data));

        }

    }

    public function crudBlockStudent(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='EditingDateBlock'){

            $ID = $data_arr['ID'];

            $dataForm = (array) $data_arr['dataForm'];

            if($ID!=''){
                // Update
                $this->db->where('ID', $ID);
                $this->db->update('block',$dataForm);
                $this->db->reset_query();
                $insert_id = $ID;
            } else {
                $dataForm['CreatedBy'] = $this->session->userdata('NIP');
                $dataForm['CreatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->insert('block',$dataForm);
                $insert_id = $this->db->insert_id();
                $this->db->reset_query();
            }

            $this->db->where('IDBlock',$insert_id);
            $this->db->delete('db_academic.block_student');
            $this->db->reset_query();
            for($i=0;$i<count($data_arr['dataStudent']);$i++){
                $d = (array) $data_arr['dataStudent'][$i];
                $arrIns = array(
                    'IDBlock' => $insert_id,
                    'NPM' => $d['NPM']
                );
                $this->db->insert('db_academic.block_student',$arrIns);
            }

            return print_r(1);

        }

        else if($data_arr['action']=='getDataBlock'){
            $data = $this->db->query('SELECT b.*, em.Name AS CreatedByName, em2.Name AS UpdatedByName FROM db_academic.block b 
                                                LEFT JOIN db_employees.employees em ON (em.NIP = b.CreatedBy)
                                                LEFT JOIN db_employees.employees em2 ON (em2.NIP = b.UpdatedBy) ')->result_array();

            if(count($data)>0){

                for($i=0;$i<count($data);$i++){
                    $data[$i]['Students'] = $this->db->query('SELECT ats.NPM, ats.Name FROM db_academic.block_student bs 
                                                                    LEFT JOIN db_academic.auth_students ats ON (ats.NPM = bs.NPM)
                                                                    WHERE bs.IDBlock = "'.$data[$i]['ID'].'" ')->result_array();
                }

            }

            return print_r(json_encode($data));
        }

        else if($data_arr['action']=='removeDateBlock'){
            $ID = $data_arr['ID'];
            $this->db->where('ID', $ID);
            $this->db->delete('db_academic.block');
            $this->db->reset_query();

            $this->db->where('IDBlock', $ID);
            $this->db->delete('db_academic.block_student');
            $this->db->reset_query();

            return print_r(1);
        }

    }



}
