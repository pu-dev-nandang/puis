<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_onlineclass extends CI_Model {


    function __construct()
    {
        parent::__construct();
    }

    function getMonitoringAttd($data_arr){

        $ScheduleID = $data_arr['ScheduleID'];
        $Session = $data_arr['Session'];

        // Get Dosen
    }

    function setAttendanceStudent($data_arr){
        $ArrIDAttd = $data_arr['ArrIDAttd'];

        $Meet = $data_arr['Meet'];
        $dataUpdate = array(
            'M'.$Meet => $data_arr['Attendance'],
            'IsOnline'.$Meet => 1
        );

        if(count($ArrIDAttd)>0){
            for($i=0;$i<count($ArrIDAttd);$i++){


                // Update Attendace
                $ck = $this->db->query('SELECT Meet'.$Meet.' AS StatusMeet FROM db_academic.attendance WHERE ID = "'.$ArrIDAttd[$i].'" ')->result_array();
                if($ck[0]['StatusMeet']!='1'){
                    $this->db->where('ID',$ArrIDAttd[$i]);
                    $this->db->update('db_academic.attendance',
                        array(
                            'Meet'.$Meet => '1',
                            'Date'.$Meet => $this->m_rest->getDateNow()
                        ));
                }



                // Update Attendance Absent Bagi yang Meetnya null
                $this->db->query('UPDATE db_academic.attendance_students SET  M'.$Meet.' = "2", IsOnline'.$Meet.' = "1"
                                           WHERE ID_Attd = "'.$ArrIDAttd[$i].'" AND M'.$Meet.' IS NULL');
                $this->db->reset_query();

                // Update Attendance Student
                $this->db->where(array(
                    'ID_Attd' => $ArrIDAttd[$i],
                    'NPM' => $data_arr['NPM']
                ));
                $this->db->update('db_academic.attendance_students',$dataUpdate);
                $this->db->reset_query();
            }
        }

        return 1;
    }

    function getArrIDAttd($ScheudleID){

        $data = $this->db->get_where('db_academic.attendance',
            array('ScheduleID' => $ScheudleID))->result_array();

        $result = [];
        if(count($data)>0){
            foreach ($data AS $itm){
                array_push($result,$itm['ID']);
            }
        }

        return $result;
    }

    public function checkOnlineAttendance($NPM,$ScheduleID,$Session){

        // Get SemesterID
        $dataSetting = $this->db->query('SELECT soc.*, s.OnlineLearning FROM db_academic.setting_online_class soc 
                                    LEFT JOIN db_academic.schedule s ON (s.SemesterID = soc.SemesterID)
                                    WHERE s.ID = "'.$ScheduleID.'" ')->result_array();

        $result = 0;
        if(count($dataSetting)>0){

            if($dataSetting[0]['OnlineLearning']=='1'){
                // Task
                $dataTask = $this->db->query('SELECT COUNT(*) AS Total FROM db_academic.schedule_task_student sts 
                                                        LEFT JOIN  db_academic.schedule_task st
                                                        ON (sts.IDST = st.ID)
                                                        WHERE sts.NPM = "'.$NPM.'" AND 
                                                        st.ScheduleID = "'.$ScheduleID.'" AND
                                                        st.Session = "'.$Session.'"
                                                           ')->result_array()[0]['Total'];


                // Forum
                $dataForum = $this->db->query('SELECT COUNT(*) AS Total FROM db_academic.counseling_comment cc
                                                        LEFT JOIN db_academic.counseling_topic ct 
                                                        ON (ct.ID = cc.TopicID)
                                                        WHERE cc.UserID = "'.$NPM.'" 
                                                        AND ct.ScheduleID = "'.$ScheduleID.'"
                                                         AND ct.Sessions = "'.$Session.'" ')->result_array()[0]['Total'];


                // Quiz
                $dataQuiz = $this->db->query('SELECT COUNT(*) AS Total FROM db_academic.q_quiz_students qqs
                                                        LEFT JOIN db_academic.q_quiz qq 
                                                        ON (qq.ID = qqs.QuizID)
                                                        WHERE qqs.NPM = "'.$NPM.'"
                                                        AND qqs.WorkDuration IS NOT NULL
                                                        AND qqs.WorkDuration > 0
                                                        AND qq.ScheduleID = "'.$ScheduleID.'"
                                                         AND qq.Session = "'.$Session.'" ')->result_array()[0]['Total'];


                $Task = ($dataSetting[0]['Task']=='1')
                    ? ($dataTask>0) ? true : false
                    : true;

                $Forum = ($dataSetting[0]['Forum']=='1')
                    ? ($dataForum>0) ? true : false
                    : true;

                $Quiz = ($dataSetting[0]['Quiz']=='1')
                    ? ($dataQuiz>0) ? true : false
                    : true;

                if($Task && $Forum && $Quiz){

                    $dataArrAttd = $this->getArrIDAttd($ScheduleID);

                    $data_arr_attd = array(
                        'ArrIDAttd' => $dataArrAttd,
                        'Meet' => $Session,
                        'Attendance' => '1',
                        'NPM' => $NPM
                    );

                    $this->setAttendanceStudent($data_arr_attd);


                    $result = 1;
                }
            }


        }

        return $result;

    }

}