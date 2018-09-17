<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal_ujian extends CI_Model {

    public function __getExam($ExamID){

        $data = $this->db->query('SELECT ex.*, cl.Room, cl.Seat, cl.SeatForExam FROM db_academic.exam ex 
                                            LEFT JOIN db_academic.classroom cl ON (cl.ID = ex.ExamClassroomID)
                                            WHERE ex.ID = "'.$ExamID.'" ')->result_array();

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $dataC = $this->db->query('SELECT exg.ID, exg.ScheduleID, s.ClassGroup, mk.NameEng AS CourseEng FROM db_academic.exam_group exg 
                                                    LEFT JOIN db_academic.schedule s ON (s.ID = exg.ScheduleID)
                                                    LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                    WHERE exg.ExamID = "'.$data[$i]['ID'].'" GROUP BY exg.ScheduleID ORDER BY s.ClassGroup ASC')->result_array();

                if(count($dataC)>0){
                    for($f=0;$f<count($dataC);$f++){
                        $dataF = $this->db->select('ID,NPM,Name,DB_Students')->get_where('db_academic.exam_details',
                            array('ExamID' => $data[$i]['ID'],'ExamGroupID' => $dataC[$f]['ID'] ,
                            'ScheduleID' => $dataC[$f]['ScheduleID']))->result_array();

//                        if(count($dataF)>0){
//                            for($r=0;$r<count($dataF);$r++){
//                                $dn = $this->db->select('Name')->get_where($dataF[$r]['DB_Students'].'.students',array('NPM'=>$dataF[$r]['NPM']),1)->result_array();
//                                $dataF[$r]['Name'] = $dn[0]['Name'];
//                            }
//                        }

                        $dataC[$f]['DetailStudent'] = $dataF;
                    }
                }
                $data[$i]['Course'] = $dataC;
            }
        }

        return $data;

    }

}
