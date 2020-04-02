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

}