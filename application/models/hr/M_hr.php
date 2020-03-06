<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hr extends CI_Model {


    public function checkPermanentDelete($NIP){

        $res = 0;

        // Cek apakah berelasi dengan jadwal
        $dataJ = $this->db->query('SELECT s.ID FROM db_academic.schedule s 
                                               LEFT JOIN db_academic.schedule_team_teaching stt 
                                               ON (stt.ScheduleID = s.ID)
                                               WHERE s.Coordinator = "'.$NIP.'" 
                                               OR stt.NIP = "'.$NIP.'" ')->result_array();

        $dataJlama = $this->db->query('SELECT s.ID FROM db_academic.z_schedule s
                                            LEFT JOIN db_academic.z_team_teaching ztt 
                                            ON (ztt.ScheduleID = s.ID)
                                            WHERE s.NIP = "'.$NIP.'" 
                                            OR ztt.NIP = "'.$NIP.'" ')->result_array();



        // Cek apakah dia mempunyai jabatan
        $dataKa = $this->db->query('SELECT ID FROM db_academic.program_study 
                                      WHERE KaprodiID = "'.$NIP.'" ')->result_array();

        // Cek apakah dia mempunyai mahasiswa bimbingan
        $dataMentor = $this->db->query('SELECT ID FROM db_academic.mentor_academic 
                                          WHERE NIP = "'.$NIP.'"')->result_array();
        if(count($dataJ)<=0 && count($dataJlama)<=0 && count($dataKa)<=0 && count($dataMentor)<=0){
            $res = array(
                'Status' => '0',
                'Msg' => 'Abel to delete permanent'
            );
        } else {
            if(count($dataJ)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist on schedule'
                );
            }
            else if(count($dataJlama)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist on schedule in Old System (SIAK LAMA)'
                );
            }
            else if(count($dataKa)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist AS -Kaprodi- '
                );
            }
            else if(count($dataMentor)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist as mentor (Dosen PA)'
                );
            } else {
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee EMPTY'
                );
            }
        }

        return $res;

    }

    public function getLecPartime(){
        $data = $this->db->query('SELECT * FROM db_employees.employees em
                                          WHERE em.StatusEmployeeID != -2 AND em.StatusEmployeeID = 4 ')->result_array();

        return $data;
    }

    public function getDataRecapitulation($data_arr){

        $SemesterID = $data_arr['SemesterID'];
        $ProdiID = $data_arr['ProdiID'];
        $StatusLecturerID = $data_arr['StatusLecturerID'];

        $data = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.employees em 
                                           WHERE em.ProdiID = "'.$ProdiID.'"
                                             AND em.StatusLecturerID = "'.$StatusLecturerID.'" ' )->result_array();

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $NIP = $data[$i]['NIP'];
                $dataSchedule = $this->db->query('SELECT s.ID AS ScheduleID, s.Coordinator AS NIP FROM db_academic.schedule s 
                                                    WHERE s.SemesterID = 16 AND s.Coordinator = "'.$NIP.'"
                                                    UNION ALL SELECT s.ID AS ScheduleID, s.Coordinator AS NIP 
                                                    FROM db_academic.schedule_team_teaching stt LEFT JOIN db_academic.schedule s ON (s.ID = stt.ScheduleID) 
                                                    WHERE s.SemesterID = 16 AND stt.NIP = "'.$NIP.'"')->rsult_array();

                // Get Schedule Details dan ID Attd
                if(count($dataSchedule)>0){
                    for($s=0;$s<count($dataSchedule);$s++){
                        $dataAttdID = $this->db->query('SELECT ScheduleID FORM db_academic.schedule_details 
                                                    WHERE ScheduleID = "'.$dataSchedule[$s][''].'" ')->result_array();
                    }
                }

            }
        }

    }

}
