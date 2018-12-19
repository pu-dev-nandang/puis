<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_rest extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('master/m_master');
        $this->load->library('JWT');
        $this->load->library('google');
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

    private function cekAuthAPI($dataAuth){

        $auth = (array) $dataAuth;

        if($auth['user']=='students'){
            $db = 'db_academic.auth_students';
        } else if($auth['user']=='lecturer') {
            $db = 'db_employees.employees';
        } else if($auth['user']=='siak') {
            return true;
        }

        $data = $this->db->get_where($db, array('Password' => $auth['token']))->result_array();

        if(count($data)>0){
            return true;
        } else {
            return false;
        }

    }

    function checkDateKRS(){
        $dataToken = $this->getInputToken();
        $cekUser = $this->cekAuthAPI($dataToken['auth']);

        if($cekUser){
            $user = (array) $dataToken['auth'];
            if($user['user']=='lecturer'){
                $data = $this->m_api->__checkDateKRSLecturer($dataToken['date']);
            } else {
                $data = $this->m_api->__checkDateKRS($dataToken['SemesterIDActive'],$dataToken['date'],
                    $dataToken['ProdiID'],$dataToken['GroupProdiID'],$dataToken['ClassOf'],
                    $dataToken['NPM'],$dataToken['DB_std']);
            }

            return print_r(json_encode($data));
        } else {
            $msg = array(
                'msg' => 'Error'
            );
            return print_r(json_encode($msg));
        }
    }

    function getDetailKRS(){
        $token = $this->input->post('token');
        $key = "s3Cr3T-G4N";
        $dataToken = (array) $this->jwt->decode($token,$key);

        $cekUser = $this->cekAuthAPI($dataToken['auth']);
        if($cekUser){
            $data = $this->m_api->getDetailStudyPlanning($dataToken['NPM'],$dataToken['ta']);
            return print_r(json_encode($data));
        } else {
            $msg = array(
                'msg' => 'Error'
            );
            return print_r(json_encode($msg));
        }

    }

    function getKSM(){
        $dataToken = $this->getInputToken();
        $cekUser = $this->cekAuthAPI($dataToken['auth']);

        if($cekUser){

            $data = $this->m_rest->__getKSM($dataToken['DB_'],$dataToken['ProdiID'],$dataToken['NPM'],$dataToken['ClassOf']);
            return print_r(json_encode($data));

        } else {
            $msg = array(
                'msg' => 'Error'
            );
            return print_r(json_encode($msg));
        }

    }

    public function getExamScheduleForStudent(){
        $dataToken = $this->getInputToken();
        $cekUser = $this->cekAuthAPI($dataToken['auth']);
//
//        print_r($dataToken);
//        exit;

        if($cekUser){
            $data = $this->m_rest->__getExamScheduleForStudent($dataToken['DB_'],
                $dataToken['ProdiID'],$dataToken['SemesterID'],$dataToken['NPM'],
                $dataToken['SemeaterYear'],$dataToken['ClassOf'],
                $dataToken['ExamType'],$dataToken['Date']);
            return print_r(json_encode($data));
        } else {
            $msg = array(
                'msg' => 'Error'
            );
            return print_r(json_encode($msg));
        }
    }

    public function geTimetable(){

        $dataToken = $this->getInputToken();
        $cekUser = $this->cekAuthAPI($dataToken['auth']);

        if($cekUser){

            if($dataToken['action']=='getTimeTable'){
                $NIP = $dataToken['NIP'];
//                $SemesterID = $dataToken['SemesterID'];
                $schedule = $this->m_rest->__geTimetable($NIP);

                return print_r(json_encode($schedule));
            }
            else if($dataToken['action']=='chekTeamTheaching'){

                $data = $this->db
                            ->get_where('db_academic.schedule_team_teaching',
                                array(
                                    'ScheduleID'=>$dataToken['ScheduleID'],
                                    'NIP'=>$dataToken['NIP']
                                ),1)
                            ->result_array();

                return print_r(json_encode($data));
            }
            else if($dataToken['action']=='updateStatusTeamTeaching'){
                $this->db->set('Status', $dataToken['Status']);
                $this->db->where('ID', $dataToken['ID']);
                $this->db->update('db_academic.schedule_team_teaching');
                return print_r(1);
            }

            else if($dataToken['action']=='getDetailsStudents'){
                $SemesterID = $dataToken['SemesterID'];
                $ScheduleID = $dataToken['ScheduleID'];

                $dataStd = $this->m_rest->__getStudentsDetails($SemesterID,$ScheduleID);

                return print_r(json_encode($dataStd));
            }

        }
        else {
            $msg = array(
                'msg' => 'Error'
            );
            return print_r(json_encode($msg));
        }


    }

    public function getExamSchedule(){

        $dataToken = $this->getInputToken();
        $cekUser = $this->cekAuthAPI($dataToken['auth']);

        if($cekUser){

            if($dataToken['action']=='readExamSchedule'){
                $NIP = $dataToken['NIP'];
//                $SemesterID = $dataToken['SemesterID'];
                $schedule = $this->m_rest->__getExamSchedule($NIP,strtolower($dataToken['Type']));

                return print_r(json_encode($schedule));
            }


        }
        else {
            $msg = array(
                'msg' => 'Error'
            );
            return print_r(json_encode($msg));
        }


    }

    public function cek_deadline_paymentNPM()
    {
        $dataToken = $this->getInputToken();
        $cekUser = $this->cekAuthAPI($dataToken['auth']);
        if($cekUser) {
            $NPM = $dataToken['NPM'];
            $arr = $this->m_api->cek_deadline_paymentNPM($NPM);
            return print_r(json_encode($arr));
        }
        else {
            $msg = array(
                'msg' => 'Error'
            );
            return print_r(json_encode($msg));
        }
    }

    public function getTableData($db = null,$table = null)
    {
        error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $json = array();
                if ( ($db != null || $db != '') && ($table != null || $table != '')  ) {
                    $json = $this->m_master->showData_array($db.'.'.$table);
                }
                echo json_encode($json);
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function rule_service()
    {
        error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $json = $this->m_master->getData_rule_service();
                echo json_encode($json);
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function rule_users()
    {
        error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $json = $this->datatableSSRuleUser();
                echo json_encode($json);
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    private function datatableSSRuleUser()
    {
        $requestData= $_REQUEST;
        $gettotalData = function($requestData){
                $sql = 'select count(*) as total from (
                        select a.ID,a.NIP,a.IDDivision,a.privilege,b.Name,c.Division from
                        db_employees.rule_users as a left join 
                        db_employees.employees as b on 
                        a.NIP = b.NIP 
                        left join db_employees.division as c 
                        on a.IDDivision = c.ID
                        where 
                        b.Name like "%'.$requestData['search']['value'].'%" or a.NIP like "%'.$requestData['search']['value'].'%" or c.Division like "%'.$requestData['search']['value'].'%"
                )aa';
                $query=$this->db->query($sql, array())->result_array();
                return $query[0]['total'];
        };
        $totalData = $gettotalData($requestData);
        $sql = 'select a.ID,a.NIP,a.IDDivision,a.privilege,b.Name,c.Division from
                db_employees.rule_users as a left join 
                db_employees.employees as b on 
                a.NIP = b.NIP 
                left join db_employees.division as c 
                on a.IDDivision = c.ID
                where 
                b.Name like "%'.$requestData['search']['value'].'%" or a.NIP like "%'.$requestData['search']['value'].'%" or c.Division like "%'.$requestData['search']['value'].'%"';
        $sql.= ' order by a.NIP desc LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';
        $query = $this->db->query($sql)->result_array();

        $data = array();
        $No = $requestData['start'] + 1;
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];
            $nestedData[] = $No;
            $nestedData[] = $row['NIP'].' || '.$row['Name'];
            $nestedData[] = $row['Division'];
            $action = '<button type="button" class="btn btn-danger btn-delete" data-sbmt="'.$row['ID'].'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';

            $nestedData[] = $action;
            $nestedData[] = $row['NIP'];
            $nestedData[] = $row['IDDivision'];
            $nestedData[] = $row['ID'];
            $data[] = $nestedData;
            $No++;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        return $json_data;
        // echo json_encode($json_data);
    }

    public function getEmployees($Status = 'aktif')
    {
        error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                if ($Status == 'aktif') {
                    $AddSql = ' where StatusEmployeeID not in (-1,-2,4,6)';
                }
                else
                {
                    $AddSql = ' where StatusEmployeeID = "'.$Status.'"';
                }

                $sql = 'SELECT em.NIP, em.NIDN, em.Photo, em.Name, em.Gender, em.PositionMain, em.ProdiID,
                            ps.NameEng AS ProdiNameEng,em.EmailPU,em.Status, em.Address, ems.Description, em.StatusEmployeeID
                            FROM db_employees.employees em 
                            LEFT JOIN db_academic.program_study ps ON (ps.ID = em.ProdiID)
                            LEFT JOIN db_employees.employees_status ems ON (ems.IDStatus = em.StatusEmployeeID) 
                            ';

                $sql.= $AddSql;
                $query=$this->db->query($sql, array())->result_array();

                echo json_encode($query);
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
        
    }

    public function loadDataFormulirGlobal()
    {
        // error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $where = (!array_key_exists("division",$dataToken)) ? ' where a.Years = "'.$dataToken['selectTahun'].'"' : ' where a.Division ="'.$dataToken['division'].'" and a.Years = "'.$dataToken['selectTahun'].'" ';
                $sql = 'SELECT a.*,b.FormulirCode from db_admission.formulir_number_global as a left join db_admission.formulir_number_offline_m as b on a.FormulirCodeGlobal = b.No_Ref'.$where.' group by a.FormulirCodeGlobal';
                $query=$this->db->query($sql, array())->result_array();
                echo json_encode($query);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function loadDataFormulirGlobal_available()
    {
        // error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $Ta = $this->m_master->showData_array('db_admission.set_ta');
                $Ta = $Ta[0]['Ta'];
                $where = (!array_key_exists("division",$dataToken)) ? ' where a.Status = 0 and a.Years = "'.$Ta.'"' : ' where a.Division ="'.$dataToken['division'].'" and a.Status = 0 and a.Years = "'.$Ta.'"';
                $sql = 'SELECT a.*,b.FormulirCode from db_admission.formulir_number_global as a left join db_admission.formulir_number_offline_m as b on a.FormulirCodeGlobal = b.No_Ref'.$where.' group by a.FormulirCodeGlobal';
                $query=$this->db->query($sql, array())->result_array();
                echo json_encode($query);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function rekapintake()
    {
        // error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $this->load->model('statistik/m_statistik');
                $Year = $dataToken['Year'];
                $result = $this->m_statistik->ShowRekapIntake($Year);
                echo json_encode($result);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function rekapintake_reset()
    {
        $data = file_get_contents('php://input');
        
        $data_json = json_decode($data,true);

        if (!$data_json) {
            // handling orang iseng
            echo '{"status":"999","message":"jangan iseng :D"}';
        }
        else {
            try {
                $getData = $data_json['data'];
                $token = $getData;
                $key = "UAP)(*";
                $dataToken = (array) $this->jwt->decode($token,$key);
                $auth = $this->m_master->AuthAPI($dataToken);
                if ($auth) {
                    $this->load->model('statistik/m_statistik');
                    $Year = $dataToken['Year'];
                    if ($dataToken['action'] == 'reset') {
                       // drop table
                        $this->m_statistik->droptablerekapintake($Year);
                    }
                     //$this->m_statistik->droptablerekapintake($Year);
                     $result = $this->m_statistik->ShowRekapIntake($Year);

                    echo '{"status":"000"}';
                }
                else
                {
                    // handling orang iseng
                    echo '{"status":"999","message":"Not Authorize"}';
                }

            }
            catch(Exception $e) {
              // handling orang iseng
              echo '{"status":"999","message":"jangan iseng :D"}';
            }
        }
    }

    public function rekapintake_reset_client()
    {
        // error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $this->load->model('statistik/m_statistik');
                $Year = $dataToken['Year'];
                if ($dataToken['action'] == 'reset') {
                   // drop table
                    $this->m_statistik->droptablerekapintake($Year);
                }
                $result = $this->m_statistik->ShowRekapIntake($Year);
                echo json_encode($result);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function trigger_formulir()
    {
        $data = file_get_contents('php://input');
        
        $data_json = json_decode($data,true);

        if (!$data_json) {
            // handling orang iseng
            echo '{"status":"999","message":"jangan iseng :D"}';
        }
        else {
            try {
                $getData = $data_json['data'];
                $token = $getData;
                $key = "UAP)(*";
                $dataToken = (array) $this->jwt->decode($token,$key);
                $auth = $this->m_master->AuthAPI($dataToken);
                if ($auth) {
                    $this->load->model('statistik/m_statistik');
                    $ta = $dataToken['ta'];
                    // month & year
                    $month = $dataToken['month'];
                    $year = $dataToken['year'];
                    $ProdiID = $dataToken['ProdiID'];
                    $action = $dataToken['action'];
                    $this->m_statistik->trigger_formulir($ta,$month,$year,$ProdiID,$action);
                    echo '{"status":"000"}';
                }
                else
                {
                    // handling orang iseng
                    echo '{"status":"999","message":"Not Authorize"}';
                }

            }
            catch(Exception $e) {
              // handling orang iseng
              echo '{"status":"999","message":"jangan iseng :D"}';
            }
        }
    }

    public function rekapintake_beasiswa()
    {
        // error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $this->load->model('statistik/m_statistik');
                $Year = $dataToken['Year'];
                if(array_key_exists("action",$dataToken))
                {
                    if ($dataToken['action'] == 'reset') {
                        $tblname = 'rekapintake_bea_'.$Year;
                       // drop table
                        $this->m_statistik->droptable($tblname);
                    }
                }
                $result = $this->m_statistik->ShowRekapIntake_Beasiswa($Year);
                echo json_encode($result);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function rekapintake_perschool()
    {
        // error_reporting(0);
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $this->load->model('statistik/m_statistik');
                $Year = $dataToken['Year'];
                if(array_key_exists("action",$dataToken))
                {
                    if ($dataToken['action'] == 'reset') {
                        $tblname = 'rekapintake_sch_'.$Year;
                       // drop table
                        $this->m_statistik->droptable($tblname);
                    }
                }
                $result = $this->m_statistik->ShowRekapIntake_School($Year);
                echo json_encode($result);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function rekapmhspayment()
    {
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $this->load->model('statistik/m_statistik');
                if(array_key_exists("action",$dataToken))
                {
                    if ($dataToken['action'] == 'reset') {
                        $tblname = 'summary_payment_mhs';
                       // drop table
                        $this->m_statistik->droptable($tblname);
                    }
                }
                $result = $this->m_statistik->ShowRekap_summary_payment_mhs();
                echo json_encode($result);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function sendEmail()
    {
        $msg = '';
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $this->load->model('m_sendemail');
                $arr = array('to','subject','text');
                $bool = true;
                foreach ($dataToken as $key => $value) {
                    if ($key != 'auth' && $key != 'attach') {
                        if(!in_array($key,$arr))
                        {
                            $bool = false;
                            $msg ='Field is not match, the field is : '.$key;
                            break;
                        }
                    }
                }

                if ($bool) {
                    $to = $dataToken['to'];
                    $subject = $dataToken['to'];
                    $text = $dataToken['text'];
                    if (array_key_exists('attach',$dataToken)) {
                        $path = $dataToken['attach'];
                        $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text,$path);
                    }
                    else
                    {
                        $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                    }

                   $msg =  $sendEmail['msg'];
                    
                }

                echo json_encode($msg);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function v_reservation_json_list_booking()
    {
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $this->load->model('vreservation/m_reservation');
                if(array_key_exists("DivisionID",$dataToken))
                {
                    $getData = $this->m_reservation->getDataT_booking_Api($dataToken['ID_t_booking'],$dataToken['DivisionID']);
                }
                else
                {
                    $getData = $this->m_reservation->getDataT_booking_Api($dataToken['ID_t_booking'],17);
                }
                
                echo json_encode($getData);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function remind_vreservation()
    {
        $msg = '';
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                // action 
                    $this->load->model('vreservation/m_reservation');
                    $Q_remind_vreservation = $this->m_reservation->remind_vreservation();
                echo json_encode($msg);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    public function venue__fill_feedback()
    {
        $msg = '';
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $this->load->model('vreservation/m_reservation');
                $this->load->model('m_sendemail');
                // get data done besar sama dengan hari ini dan kecil sama dengan dua hari lagi
                $gg = $this->m_reservation->venue__fill_feedback();
                // ambil email requester / created by untuk email.
                // body email berisi token dengan auth via get dan passing ID
                if (count($gg) > 0) {
                    for ($i=0; $i < count($gg); $i++) { // and grouping by Created by
                        $data = array();
                        $data[] = $gg[$i];
                        $Email = $gg[$i]['EmailPU'];
                        $CreatedBy = $gg[$i]['CreatedBy'];
                        $Name = $gg[$i]['Name'];
                        $DateLimit = $gg[$i]['Datelimit'].' 23:59:59';
                        $DateLimitCreated = DateTime::createFromFormat('Y-m-d', $gg[$i]['Datelimit']);
                        // print_r($DateLimit);die();
                        $NameDayDateLimit =$DateLimitCreated->format('l');
                        for ($j=$i+1; $j < count($gg); $j++) { 
                            if ($CreatedBy == $gg[$j]['CreatedBy']) {
                                $data[] = $gg[$j];
                            }
                            else
                            {
                                break;
                            }
                            $i = $j;
                        }

                        $token = array(
                            'CreatedBy' => $CreatedBy,
                            'data' => $data,
                            'auth' => 's3Cr3T-G4N',
                            'Datelimit' => $gg[$i]['Datelimit'],
                        );
                        $token = $this->jwt->encode($token,'UAP)(*');
                        $text = 'Dear Mr/Mrs '.$Name.',<br><br>
                                    Thanks for using Venue Reservation Apps.<br><br>
                                    Please give me feedback about Room which you are  using with click View Button below.<br>
                                    <table width="200" cellspacing="0" cellpadding="12" border="0">
                                         <tbody>
                                         <tr>
                                             <td bgcolor="#51a351" align="center">
                                                 <a href="'.url_pas.'vreservation/feedback/'.$token.'" style="font:bold 16px/1 Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;background-color: #51a351;" target="_blank" >View</a>
                                             </td>
                                         </tr>
                                         </tbody>
                                     </table><br><br>
                                     <strong> Link will be deactive on '.$NameDayDateLimit.','.$DateLimit.'
                                ';        
                        if($_SERVER['SERVER_NAME']!='localhost') {
                            $to = 'alhadi.rahman@podomorouniversity.ac.id';
                            $subject = "Podomoro University Venue Reservation Feedback";
                            $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                            
                        }
                        else
                        {
                            $to = $Email;
                            $subject = "Podomoro University Venue Reservation Feedback";
                            $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text);
                        }
                        //print_r($data);
                    }
                }
                
                echo json_encode($gg);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"jangan iseng :D"}';
        }
    }

    private function get_content($url, $post = '') {
        $usecookie = __DIR__ . "/cookie.txt";
        $header[] = 'Content-Type: application/json';
        $header[] = "Accept-Encoding: gzip, deflate";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        // curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");

        if ($post)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $rs = curl_exec($ch);

        if(empty($rs)){
            var_dump($rs, curl_error($ch));
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $rs;
    }


}
