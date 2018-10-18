<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_save_to_excel extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('report/m_save_to_pdf');
    }

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


    public function _getCumulativeRecap($data_arr){

        $DB_ = 'ta_'.$data_arr['Year'];

        $dataWhere = '';
        if($data_arr['ProdiID']!='' && $data_arr['ProdiID']!=null &&
            $data_arr['StatusStudentID']!='' && $data_arr['StatusStudentID']!=null){
            $dataWhere = 'WHERE ProdiID = "'.$data_arr['ProdiID'].'" 
            AND StatusStudentID = "'.$data_arr['StatusStudentID'].'" ';
        } else if($data_arr['ProdiID']!='' && $data_arr['ProdiID']!=null){
            $dataWhere = 'WHERE ProdiID = "'.$data_arr['ProdiID'].'" ';
        } else if($data_arr['StatusStudentID']!='' && $data_arr['StatusStudentID']!=null){
            $dataWhere = 'WHERE StatusStudentID = "'.$data_arr['StatusStudentID'].'" ';
        }

        $dataStd = $this->db->query('SELECT NPM,Name FROM '.$DB_.'.students '.$dataWhere)->result_array();

        if(count($dataStd)>0){
            for($i=0;$i<count($dataStd);$i++){
                $dataTR = $this->getTranscript($DB_,$dataStd[$i]['NPM']);
                $dataStd[$i]['IPK_TotalCredit'] = $dataTR['Result']['TotalSKS'];
                $dataStd[$i]['IPK'] = $dataTR['Result']['IPK'];

                $dataIPS = $this->getLastSemester($DB_,$dataStd[$i]['NPM']);
                $dataStd[$i]['IPS_TotalCredit'] = $dataIPS['TotalCredit'];
                $dataStd[$i]['IPS'] = $dataIPS['IPS'];
            }
        }

        return $dataStd;

    }

    public function getTranscript($DBStudent,$NPM){
        $data = $this->db->query('SELECT sp.Credit, sp.Grade, sp.GradeValue, mk.Name AS MKName, mk.NameEng AS MKNameEng, 
                                          sp.MKID, mk.MKCode 
                                          FROM '.$DBStudent.'.study_planning sp 
                                          LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sp.CDID)
                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                          LEFT JOIN db_academic.semester s ON (s.ID = sp.SemesterID)
                                          WHERE sp.NPM = "'.$NPM.'" AND s.Status != 1 ')->result_array();

        $totalSKS = 0;
        $totalGradeValue = 0;

        $arrDetailCourseID = [];
        $DetailCourse = [];

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $d = $data[$i];

                if(in_array($d['MKID'],$arrDetailCourseID)!=-1){
                    $dataScore = $dataScore = $this->db->order_by('Score', 'DESC')
                        ->get_where($DBStudent.'.study_planning',array('NPM' => $NPM,'MKID'=>$d['MKID']))->result_array();

                    $Grade = ($dataScore[0]['Grade']!='' && $dataScore[0]['Grade']!=null) ? $dataScore[0]['Grade'] : 'E';
                    $GradeValue = ($dataScore[0]['GradeValue']!='' && $dataScore[0]['GradeValue']!=null) ? $dataScore[0]['GradeValue'] : 0;
                    $Point = $d['Credit'] * $GradeValue;

                    $data[$i]['Grade'] = $Grade;
                    $data[$i]['GradeValue'] = $GradeValue;
                    $data[$i]['Point'] = $Point;

                    $totalSKS = $totalSKS + $d['Credit'];
                    $totalGradeValue = $totalGradeValue + $Point;

                    array_push($arrDetailCourseID,$d['MKID']);
                    array_push($DetailCourse,$data[$i]);
                }


            }
        }

        $IPK_Ori = (count($data)>0) ? $totalGradeValue/$totalSKS : 0 ;
        $ipk = number_format(round($IPK_Ori,2),2);

        $grade = $this->m_save_to_pdf->getGraduation($ipk);

        $result = array(
            'DetailCourse' => $DetailCourse,
            'Result' => array(
                'TotalSKS' => $totalSKS,
                'TotalGradeValue' => $totalGradeValue,
                'IPK_Ori' => $IPK_Ori,
                'IPK' => $ipk,
                'Grading' => $grade
            )
        );

        return $result;
    }

    public function getLastSemester($DBStudent,$NPM){
        $data = $this->db->select('ID,Status')->order_by('ID','ASC')
                ->get('db_academic.semester')->result_array();

        $ID = 0;
        foreach ($data AS $item){


            if($item['Status']==1 || $item['Status']=='1'){
                break;
            }
            $ID = $item['ID'];
        }

        $data = $this->db->query('SELECT sp.Credit, sp.Grade, sp.GradeValue, mk.Name AS MKName, mk.NameEng AS MKNameEng, 
                                          sp.MKID, mk.MKCode 
                                          FROM '.$DBStudent.'.study_planning sp 
                                          LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sp.CDID)
                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                          LEFT JOIN db_academic.semester s ON (s.ID = sp.SemesterID)
                                          WHERE sp.NPM = "'.$NPM.'" AND s.ID = "'.$ID.'" ')->result_array();

        $totalSKS = 0;
        $totalGradeValue = 0;

        if(count($data)>0){
            foreach($data AS $item){
                $totalSKS = $totalSKS + $item['Credit'];
                $totalGradeValue = $totalGradeValue + ($item['GradeValue'] * $item['Credit']);
            }
        }

        $ips = (count($data)>0) ? $totalGradeValue/$totalSKS : 0;

        $result = array(
            'ID' => $ID,
            'DetailCourse' => $data,
            'TotalCredit' => $totalSKS,
            'totalGradeValue' => $totalGradeValue,
            'IPS' => number_format(round($ips,2), 2)
        );


        return $result;
    }
}