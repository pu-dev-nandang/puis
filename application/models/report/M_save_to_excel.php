<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_save_to_excel extends CI_Model {

    public function getMonitoringScore($data_arr){

        $w_prodi = ($data_arr['ProdiID']!='') ? ' AND auts.ProdiID = "'.$data_arr['ProdiID'].'"' : '';

        $whereType = '';
        if($data_arr['Type']==10){
            $whereType = ' AND (sp.UTS IS NULL OR sp.UTS=0 OR sp.UTS="")';
        } else if($data_arr['Type']==11){
            $whereType = ' AND (sp.UTS IS NOT NULL AND sp.UTS!=0 AND sp.UTS != "")';
        }

        else if($data_arr['Type']==20){
            $whereType = ' AND (sp.UAS IS NULL OR sp.UAS=0 OR sp.UAS="")';
        } else if($data_arr['Type']==21){
            $whereType = ' AND (sp.UAS IS NOT NULL AND sp.UAS!=0 AND sp.UAS != "")';
        }


        $DB_ = 'ta_'.$data_arr['Year'];
        $queryDefault = 'SELECT s.ID,auts.NPM,auts.Name, s.ClassGroup, em.Name AS CoordinatorName, ps.Name AS ProdiName,
                                     sp.Evaluasi1, sp.Evaluasi2, sp.Evaluasi3, sp.Evaluasi4, sp.Evaluasi5, sp.UTS, sp.UAS, sp.Score,sp.Grade
                                    FROM '.$DB_.'.study_planning sp
                                    LEFT JOIN db_academic.auth_students auts ON (auts.NPM = sp.NPM)
                                    LEFT JOIN db_academic.schedule s ON (s.ID = sp.ScheduleID)
                                    LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                    LEFT JOIN db_academic.program_study ps ON (ps.ID = auts.ProdiID)
                                    WHERE ( sp.SemesterID = "'.$data_arr['SemesterID'].'" '.$w_prodi.' ) '.$whereType.' ORDER BY sp.NPM ASC
                                    ';

        $queryDefaultRow = $this->db->query($queryDefault)->result_array();

        if(count($queryDefaultRow)>0){
            for($i=0;$i<count($queryDefaultRow);$i++){
                $row = $queryDefaultRow[$i];
                $rowMK = $this->db->query('SELECT  mk.Name AS MKName, mk.NameEng AS MKNameEng, mk.MKCode 
                                                              FROM db_academic.schedule_details_course sdc
                                                              LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                              WHERE sdc.ScheduleID = "'.$row['ID'].'"  GROUP BY sdc.ScheduleID LIMIT 1')->result_array()[0];

                $queryDefaultRow[$i]['MKCode'] = $rowMK['MKCode'];
                $queryDefaultRow[$i]['MKNameEng'] = $rowMK['MKNameEng'];
                $queryDefaultRow[$i]['MKName'] = $rowMK['MKName'];
            }
        }

        return $queryDefaultRow;


    }

}