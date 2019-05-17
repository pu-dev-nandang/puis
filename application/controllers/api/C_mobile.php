<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_mobile extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('master/m_master');
        $this->load->model('hr/m_hr');
        $this->load->model('vreservation/m_reservation');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('notification/m_log');
        $this->load->library('JWT');
        $this->load->library('google');

    }

    private function genratePassword($Username,$Password){

        $plan_password = $Username.''.$Password;
        $pas = md5($plan_password);
        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');

        return $pass;
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function getInputToken2($token)
    {
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function login(){

        $data_arr = $this->getInputToken();

        // Cek setting
        $itSetting = $this->db
            ->get_where('db_it.m_config',array('GlobalPassword' => $data_arr['Password']))
            ->result_array();

        if(count($itSetting)>0){
            $dIT = $itSetting[0];
            if($dIT['DevelopMode']==1 || $dIT['DevelopMode']=='1'){

                // Get data student
                $dataStd = $this->getdataStudent($data_arr['NPM']);

                if(count($dataStd)>0){
                    $photo = $this->getPhoto($dataStd[0]['Year'],$data_arr['NPM']);
                    $dataStd[0]['Photo'] = $photo;
                    $result = array(
                        'Status' => 1,
                        'User' => $dataStd[0]
                    );
                }
                else {
                    $result = array(
                        'Status' => 0
                    );
                }


            } else {
                $dataStd = $this->checkUser($data_arr['NPM'],$data_arr['Password']);
                if(count($dataStd)>0){
                    $result = array(
                        'Status' => 1,
                        'User' => $dataStd[0]
                    );
                }
                else {
                    $result = array(
                        'Status' => 0
                    );
                }
            }
        } else {
            // Pengecekan manual

            $dataStd = $this->checkUser($data_arr['NPM'],$data_arr['Password']);
            if(count($dataStd)>0){
                $photo = $this->getPhoto($dataStd[0]['Year'],$data_arr['NPM']);
                $dataStd[0]['Photo'] = $photo;
                $result = array(
                    'Status' => 1,
                    'User' => $dataStd[0]
                );
            } else {
                $result = array(
                    'Status' => 0
                );
            }

        }

        return print_r(json_encode($result));

    }

    private function checkUser($NPM,$Password){
        $pass = $this->genratePassword($NPM,$Password);

        $dataStd = $this->db->limit(1)->get_where('db_academic.auth_students'
            ,array(
                'NPM' => $NPM,
                'Password' => $pass
            ))->result_array();

        $rest = [];
        if(count($dataStd)>0){
            $rest = $this->getdataStudent($NPM);
        }



        return $rest;
    }

    public function test_mobile(){
        $data = $this->getdataStudent('21150001');
        print_r($data);
    }

    private function getdataStudent($NPM){

        $dataStd = $this->db->query('SELECT ats.*, ps.Name AS Prodi , ps.NameEng AS ProdiEng, c.ID AS CurriculumID
                                        FROM db_academic.auth_students ats
                                        LEFT JOIN db_academic.program_study ps ON (ps.ID = ats.ProdiID)
                                        LEFT JOIN db_academic.curriculum c ON (c.Year = ats.Year)
                                        WHERE ats.NPM = "'.$NPM.'" LIMIT 1')
            ->result_array();

        if(count($dataStd)>0){
            $d = $dataStd[0];
            $dataTotalSmt = $this->db->query('SELECT s.* FROM db_academic.semester s 
                                                    WHERE s.ID >= (SELECT ID FROM db_academic.semester s2 
                                                    WHERE s2.Year="'.$d['Year'].'" 
                                                    LIMIT 1)')->result_array();


            $smt = 0;
            $SemesterName ='';
            $SemesterCode='';
            $SemesterID = 0;
            $SemesterID_Last = 0;
            if(count($dataTotalSmt)>0){
                for($s=0;$s<count($dataTotalSmt);$s++){

                    if($dataTotalSmt[$s]['Status']=='1'){
                        $smt += 1;
                        $SemesterName = $dataTotalSmt[$s]['Name'];
                        $SemesterCode=$dataTotalSmt[$s]['Code'];
                        $SemesterID = $dataTotalSmt[$s]['ID'];
                        break;
                    } else {
                        $smt += 1;
                        $SemesterID_Last = $dataTotalSmt[$s]['ID'];
                    }
                }
            }


            $dataStd[0]['SemesterNow'] = $smt;
            $dataStd[0]['SemesterID'] = $SemesterID;
            $dataStd[0]['SemesterName'] = $SemesterName;
            $dataStd[0]['SemesterCode'] = $SemesterCode;

            // Get Total Credit


            $dataSp = $this->m_rest->getTranscript($d['Year'],$NPM,'ASC');
            $TotalCredit = 0;
            $TotalPoint = 0;
            if(count($dataSp)>0){
                foreach ($dataSp AS $itemSP){

                    $TotalCredit = $TotalCredit + $itemSP['Credit'];
                    $TotalPoint = $TotalPoint + $itemSP['Point'];


                }
            }

            $IPK = ($TotalCredit>0) ? $TotalPoint / $TotalCredit : 0;
            $dataStd[0]['TotalCredit'] = $TotalCredit;
            $dataStd[0]['IPK'] = round($IPK,2);


            // LAST IPS
            $SemesterID_Last;

            $dataIPS = $this->db->select('Credit, GradeValue')->get_where('ta_'.$d['Year'].'.study_planning', array(
                'NPM' => $NPM,
                'SemesterID' => $SemesterID_Last
            ))->result_array();

            $TotalCredit_IPS = 0;
            $TotalGradeValue_IPS = 0;
            if(count($dataIPS)>0){
                foreach ($dataIPS AS $itemIPS){
                    $TotalCredit_IPS = $TotalCredit_IPS + $itemIPS['Credit'];
                    $TotalGradeValue_IPS = $TotalGradeValue_IPS + ($itemIPS['Credit'] * $itemIPS['GradeValue']);
                }
            }


            $LastIPS = ($TotalCredit_IPS>0) ? $TotalGradeValue_IPS / $TotalCredit_IPS : 0;

            $dataStd[0]['LastIPS'] = round($LastIPS,2);


        }

        return $dataStd;
    }

    private function getPhoto($Year,$NPM){

        $db_ = 'ta_'.$Year;
        $dataDetailStd = $this->db->select('Photo,Gender')->get_where($db_.'.students',array('NPM' => $NPM),1)->result_array();

        $srcImage = base_url('images/icon/userfalse.png');
        if($dataDetailStd[0]["Photo"]!='' && $dataDetailStd[0]["Photo"]!=null){
            $urlImg = './uploads/students/'.$db_.'/'.$dataDetailStd[0]["Photo"];
            $srcImage = (file_exists($urlImg)) ? base_url('uploads/students/'.$db_.'/'.$dataDetailStd[0]["Photo"]) : base_url('images/icon/userfalse.png') ;
        }

        return $srcImage;
    }

    public function readGlobalInfo(){

        $data_arr = $this->getInputToken();
        $dateNow = $this->m_rest->getDateNow();

        if($data_arr['action']=='announcement'){

            $db = 'db_notifikasi.announcement_employees';
            $urr = 'ann.NIP = "'.$data_arr['UserID'].'" ';

            if($data_arr['User']=='Std') {
                $db = 'db_notifikasi.announcement_student';
                $urr = 'ann.NPM = "'.$data_arr['UserID'].'" ';
            }

            $dataAnnc = $this->db->query('SELECT ann.ID AS IDAnncStudent ,ann.Read, annc.* FROM '.$db.' ann 
                                                LEFT JOIN db_notifikasi.announcement annc ON (annc.ID = ann.IDAnnc)
                                                WHERE '.$urr.' AND annc.Start <= "'.$dateNow.'" 
                                                AND annc.End >= "'.$dateNow.'" ORDER BY annc.ID DESC LIMIT '.$data_arr['Limit'])->result_array();


            $dataSaved = $this->db->query('SELECT COUNT(*) AS TotalSaved FROM '.$db.' ann WHERE ann.Read = "2" AND '.$urr)->result_array();

            $result = array(
                'Announcement' => $dataAnnc,
                'Saved' => $dataSaved[0]['TotalSaved']
            );

            return print_r(json_encode($result));
        }

        else if($data_arr['action']=='shceuleNow'){
            $NPM = $data_arr['UserID'];
            $db = 'ta_'.$data_arr['ClassOf'];

            $day = $this->m_rest->getCustomeDateTimeNow('N');

            $dateNow = $this->m_rest->getDateNow();

            $dataSmtActive = $this->m_rest->_getSemesterActive();

            // Get setting
            $dataAcademicyear = $this->db->get_where('db_academic.academic_years',array(
                'SemesterID' => $dataSmtActive['SemesterID']
            ))->result_array();

            // Cek apakah sedang UTS atau UAS
            $uts = ($dateNow>=$dataAcademicyear[0]['utsStart'] && $dateNow<=$dataAcademicyear[0]['utsEnd']) ? 0 : 1;
            $uas = ($dateNow>=$dataAcademicyear[0]['uasStart'] && $dateNow<=$dataAcademicyear[0]['uasEnd']) ? 0 : 1;

            $data = [];
            if($dateNow>=$dataAcademicyear[0]['kuliahStart'] && $dateNow<=$dataAcademicyear[0]['kuliahEnd'] && $uts==1 && $uas==1){

                $data = $this->db->query('SELECT sd.ID AS SDID, sd.ScheduleID, attd.ID AS ID_Attd, sd.StartSessions,sd.EndSessions, cl.Room, mk.NameEng AS CourseEng
                                                 FROM '.$db.'.study_planning sp
                                                LEFT JOIN db_academic.schedule_details sd ON (sd.ScheduleID = sp.ScheduleID)
                                                LEFT JOIN db_academic.classroom cl ON (sd.ClassroomID = cl.ID)
                                                LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = sp.ScheduleID)
                                                LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                LEFT JOIN db_academic.attendance attd ON (attd.ScheduleID = sd.ScheduleID AND attd.SDID = sd.ID)
                                                WHERE sp.NPM = "'.$NPM.'" 
                                                AND sp.SemesterID = "'.$dataSmtActive['SemesterID'].'"
                                                 AND sd.DayID = "'.$day.'" 
                                                 GROUP BY sp.ScheduleID
                                                 ORDER BY sd.StartSessions ')
                    ->result_array();

                // Get Attendance
                if(count($data)>0){
                    for($i=0;$i<count($data);$i++){
                        $d = $data[$i];
                        // Get Meet
                        $Meet = $this->m_rest->getSessionByID_Attd($d['ID_Attd']);
                        $data[$i]['Meet'] = $Meet;

                        // Get Status Attendance

                        $sc = 'attds.M'.$Meet.' AS Status';

                        $dataStatus = $this->db->query('SELECT '.$sc.' FROM db_academic.attendance_students attds 
                                                            WHERE attds.ID_Attd = "'.$d['ID_Attd'].'" 
                                                            AND attds.NPM = "'.$NPM.'"
                                                            LIMIT 1')->result_array();
                        $data[$i]['StatusAttd'] = $dataStatus[0]['Status'];
                    }
                }

            }

            return print_r(json_encode($data));


        }




    }



}