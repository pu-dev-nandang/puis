<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_api extends CI_Model {

    public function getClassOf(){
        $data = $this->db->query('SELECT ast.Year FROM db_academic.auth_students ast 
                                                  GROUP BY ast.Year');

        return $data->result_array();
    }


    public function __getGradeByIDKurikulum($CurriculumID){
        $data = $this->db->query('SELECT * FROM db_academic.grade WHERE CurriculumID = "'.$CurriculumID.'" ');
        return $data->result_array();
    }

    public function __getMataKuliahByIDKurikulum($CurriculumID){
        $data = $this->db->query('SELECT ps.Name AS ProdiName, ps.NameEng AS ProdiNameEng, 
                                          mk.MKCode, mk.Name AS NameMK, mk.NameEng AS NameMKEng, 
                                          cd.Semester , cd.TotalSKS,
                                          em.Name AS NameLecturer
                                    FROM db_academic.mata_kuliah mk 
                                    JOIN db_academic.curriculum_details cd ON (mk.ID = cd.MKID)
                                    JOIN db_academic.program_study ps ON (cd.ProdiID = ps.ID)
                                    JOIN db_employees.employees em ON (cd.LecturerNIP = em.NIP)
                                    WHERE cd.CurriculumID="'.$CurriculumID.'" ORDER BY ProdiName');
        return $data->result_array();
    }

    public function __getBaseProdi()
    {
        $data = $this->db->query('SELECT * FROM db_academic.program_study');
        return $data->result_array();
    }

    public function __getBaseProdiSelectOption()
    {
//        $data = $this->db->query('SELECT ID,Code,Name,NameEng FROM db_academic.program_study');
        $data = $this->db->query('SELECT ps.ID,ps.Code,ps.Name,ps.NameEng, el.Name as Level
                                                      FROM db_academic.program_study ps 
                                                      LEFT JOIN db_academic.education_level el ON (el.ID = ps.EducationLevelID)
                                                      WHERE ps.Status=1
                                                      ORDER BY el.EducationLevelID, ps.Name ASC');
        return $data->result_array();
    }

    public function __getBaseProdiSelectOptionAll()
    {
        $data = $this->db->query('SELECT ps.ID,ps.Code,ps.Name,ps.NameEng, el.Name as Level
                                                      FROM db_academic.program_study ps 
                                                      LEFT JOIN db_academic.education_level el ON (el.ID = ps.EducationLevelID)
                                                      ORDER BY el.EducationLevelID, ps.Name ASC');
//        $data = $this->db->query('SELECT ID,Code,Name,NameEng FROM db_academic.program_study WHERE Status=1');
        return $data->result_array();
    }

    public function __getMKByID($ID){
        $data = $this->db->query('SELECT mk.*, ps.Code AS ProdiCode FROM db_academic.mata_kuliah mk
                                    LEFT JOIN db_academic.program_study ps ON (mk.BaseProdiID = ps.ID)
                                    WHERE mk.ID = "'.$ID.'" LIMIT 1');
        return $data->result_array();
    }

    public function __getLecturer(){
        $data = $this->db->query('SELECT * FROM db_employees.employees WHERE PositionMain = "14.7"');
        return $data->result_array();
    }

    public function __getAllMK(){
        $data = $this->db->query('SELECT mk.*,pg.Code, pg.Name AS NameProdi 
                                    FROM db_academic.mata_kuliah mk 
                                    JOIN db_academic.program_study pg 
                                    ON (mk.BaseProdiID = pg.ID)');
        return $data->result_array();
    }


    // ==== KURIKULUM ====
    public function __getKurikulumByYear($SemesterSearch,$year,$ProdiID){

        // Mendapatkan Kurikulum
        $detail_kurikulum = $this->Kurikulum($year);

        if($detail_kurikulum!=''){

            // Mendapatkan Total Semester Yang ada dalam kurikulum ini
            $semester = $this->Semester($detail_kurikulum['ID']);

            for($i=0;$i<count($semester);$i++){
                $semester[$i]['DetailSemester'] = $this->DetailMK($SemesterSearch,$detail_kurikulum['ID'],$ProdiID,$semester[$i]['Semester']);
            }

            $result = array(
                'DetailKurikulum' => $detail_kurikulum,
                'MataKuliah' => $semester
            );
        } else {
            $result = false;
        }

        return $result;
    }

    private function Kurikulum($year){
        $data = $this->db->query('SELECT c.*,e.Name AS CreateByName, e2.Name AS UpdateByName FROM db_academic.curriculum c
                                              JOIN db_employees.employees e ON (c.CreateBy = e.NIP) 
                                              JOIN db_employees.employees e2 ON (c.UpdateBy = e2.NIP) 
                                              WHERE c.Year ="'.$year.'" LIMIT 1');

        if(count($data->result_array())>0){
            return $data->result_array()[0];
        } else {
            return false;
        }

    }

    private function Semester($CurriculumID){
        $data = $this->db->query('SELECT cd.Semester 
                                      FROM db_academic.curriculum_details cd 
                                      WHERE cd.CurriculumID="'.$CurriculumID.'" GROUP BY cd.Semester;');

        return $data->result_array();
    }


    private function DetailMK($SemesterSearch,$CurriculumID,$ProdiID,$Semester){
        $select = 'SELECT 
                    ps.Name AS ProdiName, ps.NameEng AS ProdiNameEng, 
                    mk.MKCode, mk.Name AS NameMK, mk.NameEng AS NameMKEng, 
                    cd.ID AS CDID, cd.CurriculumID, cd.Semester , cd.TotalSKS, cd.SKSTeori, 
                    cd.SKSPraktikum, cd.SKSPraktikLapangan, cd.MKType, cd.DataPrecondition,
                    cd.Syllabus, cd.SAP, cd.StatusMK, cd.StatusPrecondition,
                    em.Name AS NameLecturer,edu.Name AS EducationLevel';

        if($ProdiID!=''){
            $data = $this->db->query($select.' FROM db_academic.curriculum_details cd 
                                                LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID)
                                                LEFT JOIN db_academic.program_study ps ON (cd.ProdiID = ps.ID)
                                                LEFT JOIN db_employees.employees em ON (cd.LecturerNIP = em.NIP)
                                                LEFT JOIN db_academic.education_level edu ON (edu.ID = cd.EducationLevelID)
                                                WHERE cd.CurriculumID="'.$CurriculumID.'" 
                                                AND cd.Semester="'.$Semester.'"
                                                AND cd.ProdiID="'.$ProdiID.'"
                                                ORDER BY mk.MKCode ASC')->result_array();
        } else {
            $data = $this->db->query($select.' FROM db_academic.curriculum_details cd 
                                                LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID)
                                                LEFT JOIN db_academic.program_study ps ON (cd.ProdiID = ps.ID)
                                                LEFT JOIN db_employees.employees em ON (cd.LecturerNIP = em.NIP)
                                                LEFT JOIN db_academic.education_level edu ON (edu.ID = cd.EducationLevelID)
                                                WHERE cd.CurriculumID="'.$CurriculumID.'" 
                                                AND cd.Semester="'.$Semester.'"
                                                ORDER BY mk.MKCode ASC')->result_array();
        }

        if(count($data)>0 && $SemesterSearch!=''){
            $dataSMT = $this->db->query('SELECT * FROM db_academic.semester WHERE Status = 1 LIMIT 1')->result_array();
            for($i=0;$i<count($data);$i++){
                $data[$i]['Offering'] = false;
                $dataOffering = $this->db->query('SELECT co.Arr_CDID FROM db_academic.course_offerings co
                                    WHERE
                                    co.SemesterID = "'.$dataSMT[0]['ID'].'"
                                    AND co.CurriculumID = "'.$CurriculumID.'"
                                    AND co.ProdiID = "'.$ProdiID.'"
                                    AND co.Semester = "'.$SemesterSearch.'" LIMIT 1 ')->result_array();


                if(count($dataOffering)){
                    $dataCourse = json_decode($dataOffering[0]['Arr_CDID']);

                    if(in_array($data[$i]['CDID'],$dataCourse)){
                        $data[$i]['Offering'] = true;
                    }

                }

            }
        }


        return $data;
    }


    public function cekTahunKurikulum($year){
        $data = $this->db->query('SELECT * FROM db_academic.curriculum WHERE Year = "'.$year.'"');

        return $data->result_array();
    }

    public function __getKurikulumSelectOption(){
        $data = $this->db->query('SELECT * FROM db_academic.curriculum ORDER BY Year DESC');

        return $data->result_array();
    }

    public function __geteducationLevel(){
        $data = $this->db->query('SELECT * FROM db_academic.education_level ORDER BY EducationLevelID DESC');

        return $data->result_array();
    }

    public function __getDosenSelectOption(){
        $data = $this->db->query('SELECT ID,NIP,NIDN,Name FROM db_employees.employees WHERE 
                                                                    PositionMain = "14.5" 
                                                                    OR PositionMain = "14.6" 
                                                                    OR PositionMain = "14.7"
                                                                    
                                                                    OR PositionOther1 = "14.5"
                                                                    OR PositionOther1 = "14.6"
                                                                    OR PositionOther1 = "14.7"
                                                                    
                                                                    OR PositionOther2 = "14.5"
                                                                    OR PositionOther2 = "14.6"
                                                                    OR PositionOther2 = "14.7"
                                                                    
                                                                    OR PositionOther3 = "14.5"
                                                                    OR PositionOther3 = "14.6"
                                                                    OR PositionOther3 = "14.7"
                                                                    ');
        return $data->result_array();
    }

    public function __getItemKuriklum($table){

        $data = $this->db->query('SELECT * FROM db_academic.'.$table);
        return $data->result_array();
    }

    public function __getdetailKurikulum($CDID){

        $data = $this->db->query('SELECT cd.*,
                                    ct.Name AS NameCurriculumType,
                                    ps.Name AS NameProdi,
                                    el.Name AS NameEducationLevel,
                                    cg.Name AS NameCoursesGroups,
                                    em.Name AS NameLecturer,
                                    mk.Name AS NameMK,
                                    mk.NameEng AS NameMKEng
                                    FROM db_academic.curriculum_details cd
                                    LEFT JOIN db_academic.curriculum_types ct ON (ct.ID = cd.CurriculumTypeID)
                                    LEFT JOIN db_academic.program_study ps ON (ps.ID = cd.ProdiID)
                                    LEFT JOIN db_academic.education_level el ON (el.ID = cd.EducationLevelID)
                                    LEFT JOIN db_academic.courses_groups cg ON (cg.ID = cd.CoursesGroupsID)
                                    LEFT JOIN db_employees.employees em ON (cd.LecturerNIP = em.NIP)
                                    LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID)
                                    WHERE cd.ID = "'.$CDID.'" ')->result_array();


        if($data[0]['StatusPrecondition']==1){
            $dataPre = json_decode($data[0]['DataPrecondition']);

            $pre_arr = [];
            for($i=0;$i<count($dataPre);$i++){
                $exp = explode('.',$dataPre[$i]);
                $pre = $this->db->query('SELECT ID,MKcode,Name,NameEng FROM db_academic.mata_kuliah 
                                            WHERE ID="'.$exp[0].'"')->result_array();

                array_push($pre_arr,$pre[0]);
            }

            $data[0]['DetailPrecondition'] = $pre_arr;

        }

//        print_r($data);
//        exit;

        return $data;
    }


    public function __genrateMKCode($ID){
        $data = $this->db->query('SELECT count(*) AS TotalMK FROM db_academic.mata_kuliah WHERE BaseProdiID="'.$ID.'" ');
        return $data->result_array();
    }

    public function __cekMKCode($MKCode){
        $data = $this->db->query('SELECT MKCode FROM db_academic.mata_kuliah WHERE MKCode LIKE "'.$MKCode.'" ');
        return $data->result_array();
    }

    public function __cekTotalLAD($ladID){
        $data = $this->db->query('SELECT * FROM db_academic.lecturers_availability_detail 
                                        WHERE LecturersAvailabilityID="'.$ladID.'" ');

        return $data->result_array();
    }

    public function __crudDataDetailTahunAkademik($id){

        $data = $this->db->query('SELECT * FROM db_academic.semester 
                                    WHERE ID = "'.$id.'"')->result_array();

        if(count($data)>0){
            $dataDetail = $this->db->query('SELECT * FROM db_academic.academic_years 
                                              WHERE SemesterID = "'.$id.'"')->result_array();

//            $dt = (count($dataDetail)>0) ? $dataDetail[0] : '';

            if(count($dataDetail)>0){
                $result['DetailTA'] = $dataDetail[0];
            }

            $result['TahunAkademik'] = $data[0];
        } else {
            $result = false;
        }
        return $result;

    }

    public function __getAcademicYearOnPublish(){
        $data = $this->db->query('SELECT * FROM db_academic.semester s WHERE s.Status=1');

        return $data->result_array();
    }

    public function getMataKuliahSingle($ID,$MKCode){
        $data = $this->db->query('SELECT mk.*,cd.Semester,cd.TotalSKS FROM db_academic.mata_kuliah mk
                                      LEFT JOIN db_academic.curriculum_details cd ON (mk.ID = cd.MKID AND mk.MKCode=cd.MKCode)
                                      WHERE mk.ID="'.$ID.'" AND mk.MKCode = "'.$MKCode.'" ');
        return $data->result_array();
    }

    public function getMatakuliahOfferings($SemesterID,$MKID,$MKCode){

        $data = $this->db->query('SELECT cd.Semester, cd.TotalSKS FROM db_academic.course_offerings co 
                                           LEFT JOIN db_academic.curriculum_details cd ON (co.CurriculumDetailID = cd.ID)
                                           WHERE co.SemesterID = "'.$SemesterID.'" AND cd.MKID = "'.$MKID.'" AND cd.MKCode = "'.$MKCode.'" 
                                           ');

        return $data->result_array();
    }

    public function getProgramCampus(){
        $data = $this->db->query('SELECT * FROM db_academic.programs_campus ORDER BY ID ASC');

        return $data->result_array();
    }

    public function getSemester($order){
        $data = $this->db->query('SELECT * FROM db_academic.semester ORDER BY ID '.$order);

        return $data->result_array();
    }

    public function getSemesterActive($CurriculumID,$ProdiID,$Semester,$IsSemesterAntara){
        $data = $this->db->query('SELECT * FROM db_academic.semester WHERE Status = 1 LIMIT 1')->result_array();

        $result = array(
            'SemesterActive' => $data[0],
            'DetailCourses' => $this->getDetailCourses($data[0]['ID'],$CurriculumID,$ProdiID,$Semester,$IsSemesterAntara)
        );

        return $result;
    }

    private function getDetailCourses($SemesterID,$CurriculumID,$ProdiID,$Semester,$IsSemesterAntara){

        $data = $this->db->query('SELECT cd.ID AS CurriculumDetailID,cd.Semester, cd.MKType, cd.MKID, mk.MKCode, cd.TotalSKS, cd.StatusMK, 
                                    mk.Name AS MKName, mk.NameEng AS MKNameEng,
                                    ps.Code AS ProdiCode, ps.Name AS ProdiName, ps.NameEng AS ProdiNameEng
                                    FROM db_academic.curriculum_details cd
                                    LEFT JOIN db_academic.program_study ps ON (cd.ProdiID = ps.ID)
                                    LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID)
                                    
                                    WHERE cd.CurriculumID = "'.$CurriculumID.'" 
                                    AND cd.ProdiID = "'.$ProdiID.'" 
                                    AND cd.Semester = "'.$Semester.'" 
                                     
                                    ORDER BY cd.Semester , ps.Code ASC')->result_array();



        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $data[$i]['Offering'] = false;
                $dataOffering = $this->db->query('SELECT co.Arr_CDID FROM db_academic.course_offerings co
                                    WHERE 
                                    co.SemesterID = "'.$SemesterID.'" 
                                    AND co.CurriculumID = "'.$CurriculumID.'" 
                                    AND co.ProdiID = "'.$ProdiID.'" 
                                    AND co.Semester = "'.$Semester.'"
                                    AND co.IsSemesterAntara = "'.$IsSemesterAntara.'" LIMIT 1 ')->result_array();

                if(count($dataOffering)){
                    $dataCourse = json_decode($dataOffering[0]['Arr_CDID']);

                    if(in_array($data[$i]['CurriculumDetailID'],$dataCourse)){
                        $data[$i]['Offering'] = true;
                    }

                }


            }
        }

//        print_r($data);





        return $data;
    }

    public function getAllCourseOfferings($SemesterID,$CurriculumID,$ProdiID,$Semester,$IsSemesterAntara){

        $dataProdi = $this->db->query('SELECT * FROM db_academic.program_study WHERE Status = 1 AND ID = "'.$ProdiID.'" ORDER BY ID ASC ')->result_array();

        $result = [];
        for($i=0;$i<count($dataProdi);$i++){
            $dataOfferings = $this->getDetailAllOfferings($SemesterID,$CurriculumID,$ProdiID,$Semester,$IsSemesterAntara);
            $data = array(
                'Prodi' => array(
                    'ID' => $dataProdi[$i]['ID'],
                    'Code' => $dataProdi[$i]['Code'],
                    'Name' => $dataProdi[$i]['Name'],
                    'NameEng' => $dataProdi[$i]['NameEng'],
                ),
                'Offerings' => $dataOfferings
            );

            array_push($result,$data);
        }

        return $result;
    }

    private function getDetailAllOfferings($SemesterID,$CurriculumID,$ProdiID,$Semester,$IsSemesterAntara){

        $data = $this->db->query('SELECT * FROM db_academic.course_offerings co 
                                        WHERE co.SemesterID = "'.$SemesterID.'" 
                                        AND co.CurriculumID = "'.$CurriculumID.'" 
                                        AND co.ProdiID = "'.$ProdiID.'" 
                                        AND co.Semester = "'.$Semester.'"
                                        AND co.IsSemesterAntara = "'.$IsSemesterAntara.'"
                                         LIMIT 1 ')->result_array();

        $result = [];
        if(count($data)>0){
            $Course = json_decode($data[0]['Arr_CDID']);

            $CourseArr = [];

            for($i=0;$i<count($Course);$i++){
                $dataCourse = $this->db->query('SELECT cd.ID AS CDID, cd.ProdiID, cd.Semester, cd.MKType, cd.TotalSKS, cd.StatusMK, cd.MKID,
						                              mk.NameEng AS MKNameEng,
                                                      mk.Name AS MKName, mk.MKCode
                                                      FROM db_academic.curriculum_details cd
                                                      LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID)
                                                      WHERE cd.ID = "'.$Course[$i].'" 
                                                      LIMIT 1')->result_array();

                $dataCekInSchedule = $this->db->query('SELECT s1.ID AS ScheduleID
                                                      FROM db_academic.curriculum_details cd
                                                      LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID)
                                                      LEFT JOIN db_academic.schedule_details_course sdc1 ON (sdc1.MKID = mk.ID)
                                                      LEFT JOIN db_academic.schedule s1 ON (sdc1.ScheduleID = s1.ID)
                                                      WHERE cd.ID = "'.$Course[$i].'" 
                                                      AND cd.ID IN (
                                                            SELECT sdc.CDID FROM db_academic.schedule_details_course sdc 
                                                            LEFT JOIN db_academic.schedule s ON (sdc.ScheduleID = s.ID) 
                                                            ) 
                                                      LIMIT 1')->result_array();

                if(count($dataCourse)>0){
                    $dataCourse[0]['ScheduleID'] = (count($dataCekInSchedule)>0) ? $dataCekInSchedule[0]['ScheduleID'] : null;
                }

                $dataPush = (count($dataCourse)>0) ? $dataCourse[0] : $dataCourse;
                array_push($CourseArr,$dataPush);

            }

            $result = $data;

            $result[0]['Details'] = $CourseArr;
        }

        return $result;

    }

    private function getDetailOfferings($SemesterID,$ProdiID){

//        $data = $this->db->query('SELECT co.ID, cd.Semester, cd.MKType, cd.MKID, cd.MKCode, cd.TotalSKS, cd.StatusMK,
//                                          mk.Name AS MKName, mk.NameEng AS MKNameEng, s.ID AS ScheduleID
//                                            FROM db_academic.course_offerings co
//                                            LEFT JOIN db_academic.curriculum_details cd ON (co.CurriculumDetailID = cd.ID)
//                                            LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID AND cd.MKCode = mk.MKCode)
//                                            LEFT JOIN db_academic.schedule s ON (s.SemesterID = co.SemesterID AND cd.MKID = s.MKID AND cd.MKCode = s.MKCode)
//                                            WHERE  co.SemesterID = "'.$SemesterID.'" AND co.ProdiID = "'.$ProdiID.'"
//                                   ');

        // Load Mata Kuliah Saat Input Jadwal Tanpa Mata Kuliah Umum
        $data = $this->db->query('SELECT co.ID, co.ToSemester, cd.ProdiID, cd.Semester, cd.MKType, cd.MKID, mk.MKCode, cd.TotalSKS, cd.StatusMK, 
                                          mk.Name AS MKName, mk.NameEng AS MKNameEng, s.ID AS ScheduleID
                                            FROM db_academic.course_offerings co
                                            LEFT JOIN db_academic.curriculum_details cd ON (co.CurriculumDetailID = cd.ID)
                                            LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID)
                                            LEFT JOIN db_academic.schedule s ON (s.SemesterID = co.SemesterID AND cd.MKID = s.MKID)
                                            WHERE  co.SemesterID = "'.$SemesterID.'" AND co.ProdiID = "'.$ProdiID.'" AND mk.BaseProdiID != 7
                                   ');
        return $data->result_array();
    }

    public function getAllCourseOfferingsMKU($SemesterID){

        $data = $this->db->query('SELECT co.ID, cd.Semester, cd.MKType, cd.MKID, mk.MKCode, cd.TotalSKS, cd.StatusMK, 
                                          mk.Name AS MKName, mk.NameEng AS MKNameEng , s.ID AS ScheduleID
                                        FROM db_academic.course_offerings co
                                        LEFT JOIN db_academic.curriculum_details cd ON (co.CurriculumDetailID = cd.ID)
                                        LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID)
                                        LEFT JOIN db_academic.schedule s ON (s.SemesterID = co.SemesterID AND cd.MKID = s.MKID AND cd.MKCode = s.MKCode)
                                        WHERE co.SemesterID = "'.$SemesterID.'" AND mk.BaseProdiID = 7 GROUP BY cd.MKCode
                                        ');

        return $data->result_array();

    }


    public function getOfferingsToSetSchedule($dataForm){

//        print_r($dataForm);

        $query = $this->db
            ->order_by('Semester', 'ASC')
            ->get_where('db_academic.course_offerings', $dataForm)->result_array();

        $result = [];
        if(count($query)>0){
            for($i=0;$i<count($query);$i++){
                $dt = $this->getOfferingsToSetScheduleDetails($query[$i],$query[$i]['Arr_CDID']);
                $dataRes = array(
                    'Offerings' => $query[$i],
                    'Details' => $dt
                );

                array_push($result,$dataRes);
            }
        }

        return $result;
    }

    private function getOfferingsToSetScheduleDetails($query,$Arr_CDID){
        $data_CDID = json_decode($Arr_CDID);
        $result = [];
        for($i=0;$i<count($data_CDID);$i++){
            $data = $this->db->query('SELECT cd.ID AS CDID, cd.TotalSKS, mk.ID,mk.MKCode,mk.Name AS MKName, 
                                                mk.NameEng AS MKNameEng, cd.Semester 
                                                FROM db_academic.curriculum_details cd 
                                                LEFT JOIN db_academic.mata_kuliah mk ON (cd.MKID = mk.ID)
                                                WHERE cd.ID = "'.$data_CDID[$i].'" LIMIT 1')->result_array();



            if(count($data)>0){
                $sc = $this->db->query('SELECT s.ID AS ScheduleID FROM db_academic.schedule_details_course sdc
                                                  JOIN db_academic.schedule s ON (s.ID=sdc.ScheduleID)
                                                  WHERE s.SemesterID = "'.$query['SemesterID'].'" 
                                                  AND s.ProgramsCampusID = "'.$query['ProgramsCampusID'].'"
                                                  AND sdc.ProdiID = "'.$query['ProdiID'].'"
                                                  AND sdc.CDID = "'.$data_CDID[$i].'"
                                                  AND sdc.MKID = "'.$data[0]['ID'].'"
                                                  LIMIT 1
                                                   ')->result_array();
                $data[0]['ScheduleID'] = '';
                if(count($sc)>0){
                    $data[0]['ScheduleID'] = $sc[0]['ScheduleID'];
                }
                array_push($result,$data[0]);
            }


        }

        return $result;
    }

    public function getSemesterCurriculum($SemesterID,$IsSemesterAntara){

        $where = ($SemesterID!='' && $SemesterID!=0) ? 's.ID = '.$SemesterID : 's.Status = 1';

        $dataCurriculum = $this->db->query('SELECT * FROM db_academic.curriculum c 
                                                    WHERE c.Year <= (
                                                      SELECT Year FROM db_academic.semester s WHERE '.$where.' LIMIT 1) 
                                                      ORDER BY c.Year DESC ')
                                ->result_array();

        $result=[];

        for($s=0;$s<count($dataCurriculum);$s++){
            $data = $this->db->query('SELECT s.* FROM db_academic.semester s 
                                                    WHERE s.Year>="'.$dataCurriculum[$s]['Year'].'" ')
                                ->result_array();

            $smt=1;


            for($i=0;$i<count($data);$i++){

                if($SemesterID!='' && $SemesterID!=0){
                    if($data[$i]['ID']!=$SemesterID){
                        $smt = $smt + 1;
                    } else {
                        break;
                    }
                } else {
                    if($data[$i]['Status']==0){
                        $smt = $smt + 1;
                    } else {
                        break;
                    }
                }




            }

            $d = array(
                'Curriculum' => $dataCurriculum[$s],
                'Semester' => $smt
            );


            array_push($result,$d);

        }





        return $result;
    }


    public function getSchedule($DayID,$dataWhere){


        $ProgramsCampusID = ($dataWhere['ProgramsCampusID']!='') ? ' AND s.ProgramsCampusID = "'.$dataWhere['ProgramsCampusID'].'" ' : '';
        $SemesterID = ($dataWhere['SemesterID']!='') ? ' AND s.SemesterID = "'.$dataWhere['SemesterID'].'" ' : '';
        $CombinedClasses = ($dataWhere['CombinedClasses']!='') ? ' AND s.CombinedClasses = "'.$dataWhere['CombinedClasses'].'" ' : '';
        $IsSemesterAntara = ($dataWhere['IsSemesterAntara']!='') ? ' AND s.IsSemesterAntara = "'.$dataWhere['IsSemesterAntara'].'" ' : '';

        $data = $this->db->query('SELECT s.*,
                                          sd.ClassroomID,sd.Credit,sd.DayID,sd.TimePerCredit,sd.StartSessions,sd.EndSessions,
                                          em.Name AS Lecturer,
                                          cl.Room 
                                          FROM db_academic.schedule_details sd
                                          LEFT JOIN db_academic.schedule s ON (s.ID=sd.ScheduleID)
                                          LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                          LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)                                   
                                          WHERE sd.DayID = "'.$DayID.'" '.$ProgramsCampusID.' '.$SemesterID.' '.$CombinedClasses.' '.$IsSemesterAntara.' 
                                          ORDER BY sd.StartSessions, sd.EndSessions ASC');

        $result = $data->result_array();


        if(count($result)>0){

            $CO_SemesterID = ($dataWhere['SemesterID']!='') ? ' AND co.SemesterID = "'.$dataWhere['SemesterID'].'" ' : '';
            $CO_ProdiID = ($dataWhere['ProdiID']!='') ? ' AND co.ProdiID = "'.$dataWhere['ProdiID'].'" ' : '';
            $CO_IsSemesterAntara = ($dataWhere['IsSemesterAntara']!='') ? ' AND co.IsSemesterAntara = "'.$dataWhere['IsSemesterAntara'].'" ' : '';
            $CO_Semester = ($dataWhere['Semester']!='') ? ' AND co.Semester = "'.$dataWhere['Semester'].'" ' : '';

            $ClassOf = $this->getClassOf();

            // Get Course
            for($c=0;$c<count($result);$c++){
//                $ProdiIDsdc = ($dataWhere['ProdiID']!='') ? ' AND sdc.ProdiID = "'.$dataWhere['ProdiID'].'" ' : '';


                $dataOffering = $this->db->query('SELECT * FROM db_academic.course_offerings co 
                                                          WHERE co.ProgramsCampusID = "'.$dataWhere['ProgramsCampusID'].'" '.$CO_Semester.' 
                                                           '.$CO_SemesterID.' '.$CO_ProdiID.' '.$CO_IsSemesterAntara.' ')->result_array();


                $dataCourse = [];

                if(count($dataOffering)>0){
                    for($f=0;$f<count($dataOffering);$f++){
                        $Arr_CDID = json_decode($dataOffering[$f]['Arr_CDID']);

                        for($s=0;$s<count($Arr_CDID);$s++){

                            $__course = $this->db->query('SELECT sdc.CDID, mk.ID, mk.MKCode, mk.NameEng AS MKNameEng, mk.Name AS MKName,
                                                          ps.NameEng AS ProdiEng, ps.name AS Prodi, ps.Code AS ProdiCode, cd.Semester AS BaseSemester
                                                          FROM db_academic.schedule_details_course sdc
                                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                          LEFT JOIN db_academic.program_study ps ON (ps.ID = sdc.ProdiID)
                                                          LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                          WHERE sdc.ScheduleID="'.$result[$c]['ID'].'" AND sdc.CDID = "'.$Arr_CDID[$s].'" LIMIT 1')->result_array();

                            if(count($__course)>0){
//                                $__course[0]['Semester'] = $dataWhere['Semester'];
                                $__course[0]['Semester'] = $dataOffering[$f]['Semester'];
                                array_push($dataCourse,$__course[0]);
                            }
                        }

                    }
                }

                $result[$c]['SemesterDetails'] = $dataOffering;
                $result[$c]['DetailCourse'] = $dataCourse;

                $Students = [];
                for($st=0;$st<count($ClassOf);$st++){
                    $db_ = 'ta_'.$ClassOf[$st]['Year'];

                    $dataStdCourse = $this->db->query('SELECT s.Name, s.NPM FROM '.$db_.'.study_planning sp 
                                                            LEFT JOIN '.$db_.'.students s ON (s.NPM = sp.NPM)
                                                            WHERE sp.SemesterID = "'.$dataWhere['SemesterID'].'" 
                                                            AND sp.ScheduleID = "'.$result[$c]['ID'].'" ')->result_array();

                    if(count($dataStdCourse)>0){
                        array_push($Students,$dataStdCourse);
                    }
                }

                $result[$c]['StudentsDetails'] = (count($Students)>0) ? $Students[0] : $Students ;

            }

            for($i=0;$i<count($result);$i++){
                if($result[$i]['TeamTeaching']==1){
                    $result[$i]['DetailTeamTeaching'] = $this->getTeamTeaching($result[$i]['ID']);
                }

            }

            // Daftar Jadwal

        }

        return $result;

    }


    // ====== Get Jadwal Per Day =======

    public function getTotalPerDay($DayID,$dataWhere){

        $ProgramsCampusID = ($dataWhere['ProgramsCampusID']!='') ? ' AND s.ProgramsCampusID = "'.$dataWhere['ProgramsCampusID'].'" ' : '';
        $SemesterID = ($dataWhere['SemesterID']!='') ? ' AND s.SemesterID = "'.$dataWhere['SemesterID'].'" ' : '';
        $CombinedClasses = ($dataWhere['CombinedClasses']!='') ? ' AND s.CombinedClasses = "'.$dataWhere['CombinedClasses'].'" ' : '';
        $IsSemesterAntara = ($dataWhere['IsSemesterAntara']!='') ? ' AND s.IsSemesterAntara = "'.$dataWhere['IsSemesterAntara'].'" ' : '';

        $ProdiID = ($dataWhere['ProdiID']!='') ? ' AND sdc.ProdiID = "'.$dataWhere['ProdiID'].'" ' : '';

        $dataDay = $this->db->query('SELECT sdc.CDID FROM db_academic.schedule_details sd
                                          LEFT JOIN db_academic.schedule s ON (s.ID = sd.ScheduleID)
                                          LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = sd.ScheduleID)
                                          WHERE sd.DayID = '.$DayID.'  '.$ProgramsCampusID.'
                                           '.$SemesterID.' '.$CombinedClasses.' 
                                           '.$IsSemesterAntara.' 
                                           '.$ProdiID.' 
                                           GROUP BY s.ID ')->result_array();

        $res = $dataDay;

        return $res;

    }

    public function getSchedulePerDayLimit($DayID,$dataWhere,$start,$length){


        $ProgramsCampusID = ($dataWhere['ProgramsCampusID']!='') ? ' AND s.ProgramsCampusID = "'.$dataWhere['ProgramsCampusID'].'" ' : '';
        $SemesterID = ($dataWhere['SemesterID']!='') ? ' AND s.SemesterID = "'.$dataWhere['SemesterID'].'" ' : '';
        $CombinedClasses = ($dataWhere['CombinedClasses']!='') ? ' AND s.CombinedClasses = "'.$dataWhere['CombinedClasses'].'" ' : '';
        $IsSemesterAntara = ($dataWhere['IsSemesterAntara']!='') ? ' AND s.IsSemesterAntara = "'.$dataWhere['IsSemesterAntara'].'" ' : '';

        $ProdiID = ($dataWhere['ProdiID']!='') ? ' AND sdc.ProdiID = "'.$dataWhere['ProdiID'].'" ' : '';

        $q ='SELECT s.*, sd.ClassroomID,sd.Credit,sd.DayID,sd.TimePerCredit,sd.StartSessions,sd.EndSessions,
                                          em.Name AS Lecturer,
                                          cl.Room 
                                          FROM db_academic.schedule_details sd
                                          LEFT JOIN db_academic.schedule s ON (s.ID = sd.ScheduleID)
                                          LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = sd.ScheduleID)
                                          LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                          LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                          WHERE sd.DayID = '.$DayID.'  '.$ProgramsCampusID.'
                                           '.$SemesterID.' '.$CombinedClasses.' 
                                           '.$IsSemesterAntara.' 
                                           '.$ProdiID.' 
                                           GROUP BY s.ID 
                                           ORDER BY sd.StartSessions, sd.EndSessions ASC
                                           LIMIT '.$start.' , '.$length.'
                                           ';



        return $q;
    }

    public function getSchedulePerDaySearch($DayID,$dataWhere,$search){
        $ProgramsCampusID = ($dataWhere['ProgramsCampusID']!='') ? ' AND s.ProgramsCampusID = "'.$dataWhere['ProgramsCampusID'].'" ' : '';
        $SemesterID = ($dataWhere['SemesterID']!='') ? ' AND s.SemesterID = "'.$dataWhere['SemesterID'].'" ' : '';
        $CombinedClasses = ($dataWhere['CombinedClasses']!='') ? ' AND s.CombinedClasses = "'.$dataWhere['CombinedClasses'].'" ' : '';
        $IsSemesterAntara = ($dataWhere['IsSemesterAntara']!='') ? ' AND s.IsSemesterAntara = "'.$dataWhere['IsSemesterAntara'].'" ' : '';

        $ProdiID = ($dataWhere['ProdiID']!='') ? ' AND sdc.ProdiID = "'.$dataWhere['ProdiID'].'" ' : '';

        $q ='SELECT s.*, sd.ClassroomID,sd.Credit,sd.DayID,sd.TimePerCredit,sd.StartSessions,sd.EndSessions,
                                          em.Name AS Lecturer,
                                          cl.Room 
                                          FROM db_academic.schedule_details sd
                                          LEFT JOIN db_academic.schedule s ON (s.ID = sd.ScheduleID)
                                          LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = sd.ScheduleID)
                                          LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                          LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                          WHERE ( sd.DayID = '.$DayID.'  '.$ProgramsCampusID.'
                                           '.$SemesterID.' '.$CombinedClasses.' 
                                           '.$IsSemesterAntara.' 
                                           '.$ProdiID.' ) AND (
                                           s.ClassGroup LIKE "%'.$search.'%" OR
                                           em.Name LIKE "%'.$search.'%" OR
                                           cl.Room LIKE "%'.$search.'%"
                                            ) 
                                           GROUP BY s.ID 
                                           ORDER BY sd.StartSessions, sd.EndSessions ASC                                           
                                           ';



        return $q;
    }

    public function getTeamTeachingPerDay($ScheduleID){
        $data = $this->db->query('SELECT em.Name AS Lecturer FROM db_academic.schedule_team_teaching stt
                                            LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                            WHERE stt.ScheduleID = "'.$ScheduleID.'" ')->result_array();

        $res = '';
        if(count($data)>0){

            for($i=0;$i<count($data);$i++){

                $lec = '<div style="margin-bottom: 7px;"><span class="label label-info-inline"><b>'.$data[$i]['Lecturer'].'</b></span></div>';

                $res = $res.''.$lec;
            }
        }

        return $res;
    }

    public function getCoursesPerDay($ScheduleID){
        $dataCourse = $this->db->query('SELECT mk.MKCode, mk.Name AS MKName, mk.NameEng AS MKNameEng ,
                                            ps.Code AS CodeProdi, ps.NameEng AS ProdiEng 
                                            FROM db_academic.schedule_details_course sdc 
                                            LEFT JOIN db_academic.program_study ps ON (ps.ID = sdc.ProdiID)
                                            LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                            WHERE sdc.ScheduleID = "'.$ScheduleID.'" ')->result_array();


        $resCourse = '';
        if(count($dataCourse)>0){
            for($r=0;$r<count($dataCourse);$r++){
                $d = $dataCourse[$r];

                $cc = '<div style="margin-bottom: 10px;"><b>'.$d['MKNameEng'].'</b><br/><i>'.$d['MKName'].'</i><br/>
                            <span class="label label-default">'.$d['MKCode'].'</span> | 
                            <span class="label label-danger-inline"><b>'.$d['ProdiEng'].'</b></span></div>';
                $resCourse = $resCourse.''.$cc;
            }
        }

        return $resCourse;
    }

    public function getTotalStdPerDay($SemesterID,$ScheduleID){
        $ClassOf = $this->getClassOf();

        $totalStd = 0;
        for($st=0;$st<count($ClassOf);$st++){
            $db_ = 'ta_'.$ClassOf[$st]['Year'];

            $dataStdCourse = $this->db->query('SELECT s.NPM FROM '.$db_.'.study_planning sp 
                                                            LEFT JOIN '.$db_.'.students s ON (s.NPM = sp.NPM)
                                                            WHERE sp.SemesterID = "'.$SemesterID.'" 
                                                            AND sp.ScheduleID = "'.$ScheduleID.'" ')->result_array();
            $totalStd = $totalStd + count($dataStdCourse);
        }

        return $totalStd;
    }

    // =======================================

    // ====== Get Jadwal Per Semester =======
    public function getTotalPerSemester($dataWhere){

        $CO_SemesterID = ($dataWhere['SemesterID']!='') ? ' AND co.SemesterID = "'.$dataWhere['SemesterID'].'" ' : '';
        $CO_ProdiID = ($dataWhere['ProdiID']!='') ? ' AND co.ProdiID = "'.$dataWhere['ProdiID'].'" ' : '';
        $CO_IsSemesterAntara = ($dataWhere['IsSemesterAntara']!='') ? ' AND co.IsSemesterAntara = "'.$dataWhere['IsSemesterAntara'].'" ' : '';
        $CO_Semester = ($dataWhere['Semester']!='') ? ' AND co.Semester = "'.$dataWhere['Semester'].'" ' : '';


        $dataOffering = $this->db->query('SELECT * FROM db_academic.course_offerings co 
                                                          WHERE co.ProgramsCampusID = "'.$dataWhere['ProgramsCampusID'].'"
                                                           '.$CO_SemesterID.'
                                                           '.$CO_ProdiID.' 
                                                           '.$CO_Semester.'                                                             
                                                           '.$CO_IsSemesterAntara.' ')->result_array();

        $res = [];
        if(count($dataOffering)>0){
            for($r=0;$r<count($dataOffering);$r++){
                $Arr_CDID = json_decode($dataOffering[$r]['Arr_CDID']);
                for($s=0;$s<count($Arr_CDID);$s++){
                    array_push($res,$Arr_CDID[$s]);
                }
            }
        }

        return $res;

    }

    public function getTotalPerSemesterLimit($dataWhere,$start,$length){

        $dataCDID = $this->getTotalPerSemester($dataWhere);

        $arrLim = array_slice($dataCDID, $start, $length);

        $res =[];
        if(count($arrLim)>0){
            for($c=0;$c<count($arrLim);$c++){
                $dataC = $this->db->query('SELECT s.*, sd.ClassroomID,sd.Credit,sd.DayID,sd.TimePerCredit,sd.StartSessions,sd.EndSessions,
                                                  em.Name AS Lecturer,
                                                  cl.Room 
                                                FROM db_academic.schedule_details_course sdc
                                                LEFT JOIN db_academic.schedule s ON (s.ID = sdc.ScheduleID)
                                                LEFT JOIN db_academic.schedule_details sd ON (s.ID = sd.ScheduleID)
                                                LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                                WHERE s.SemesterID = "'.$dataWhere['SemesterID'].'" 
                                                AND sdc.CDID = "'.$arrLim[$c].'"
                                                ')->result_array();

                if(count($dataC)>0){
                    for($dc=0;$dc<count($dataC);$dc++){
                        array_push($res,$dataC[$dc]);
                    }
                }
            }
        }

        return $res;
    }

    public function getTotalPerSemesterSearch($dataWhere,$search){
        $arrLim = $this->getTotalPerSemester($dataWhere);

        $res =[];
        if(count($arrLim)>0){
            for($c=0;$c<count($arrLim);$c++){
                $dataC = $this->db->query('SELECT s.*, sd.ClassroomID,sd.Credit,sd.DayID,sd.TimePerCredit,sd.StartSessions,sd.EndSessions,
                                                  em.Name AS Lecturer,
                                                  cl.Room 
                                                FROM db_academic.schedule_details_course sdc
                                                LEFT JOIN db_academic.schedule s ON (s.ID = sdc.ScheduleID)
                                                LEFT JOIN db_academic.schedule_details sd ON (s.ID = sd.ScheduleID)
                                                LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                                WHERE ( s.SemesterID = "'.$dataWhere['SemesterID'].'" 
                                                AND sdc.CDID = "'.$arrLim[$c].'" ) AND (
                                                    s.ClassGroup LIKE "%'.$search.'%" OR
                                                   em.Name LIKE "%'.$search.'%" OR
                                                   cl.Room LIKE "%'.$search.'%"
                                                )
                                                ')->result_array();

                if(count($dataC)>0){
                    for($dc=0;$dc<count($dataC);$dc++){
                        array_push($res,$dataC[$dc]);
                    }
                }
            }
        }

        return $res;

    }


    // =======================================
    public function getDetailSc($dataWhere,$result){
        if(count($result)>0){
            $CO_SemesterID = ($dataWhere['SemesterID']!='') ? ' AND co.SemesterID = "'.$dataWhere['SemesterID'].'" ' : '';
            $CO_ProdiID = ($dataWhere['ProdiID']!='') ? ' AND co.ProdiID = "'.$dataWhere['ProdiID'].'" ' : '';
            $CO_IsSemesterAntara = ($dataWhere['IsSemesterAntara']!='') ? ' AND co.IsSemesterAntara = "'.$dataWhere['IsSemesterAntara'].'" ' : '';
            $CO_Semester = ($dataWhere['Semester']!='') ? ' AND co.Semester = "'.$dataWhere['Semester'].'" ' : '';

//            $ClassOf = $this->getClassOf();
            for($c=0;$c<count($result);$c++){
                $dataOffering = $this->db->query('SELECT * FROM db_academic.course_offerings co 
                                                          WHERE co.ProgramsCampusID = "'.$dataWhere['ProgramsCampusID'].'" '.$CO_Semester.' 
                                                           '.$CO_SemesterID.' '.$CO_ProdiID.' '.$CO_IsSemesterAntara.' ')->result_array();

                $dataCourse = [];

                if(count($dataOffering)>0){
                    for($f=0;$f<count($dataOffering);$f++){
                        $Arr_CDID = json_decode($dataOffering[$f]['Arr_CDID']);

                        for($s=0;$s<count($Arr_CDID);$s++){

                            $__course = $this->db->query('SELECT sdc.CDID, mk.ID, mk.MKCode, mk.NameEng AS MKNameEng, mk.Name AS MKName,
                                                          ps.NameEng AS ProdiEng, ps.name AS Prodi, ps.Code AS ProdiCode, cd.Semester AS BaseSemester
                                                          FROM db_academic.schedule_details_course sdc
                                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                          LEFT JOIN db_academic.program_study ps ON (ps.ID = sdc.ProdiID)
                                                          LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                          WHERE sdc.ScheduleID="'.$result[$c]['ID'].'" AND sdc.CDID = "'.$Arr_CDID[$s].'" LIMIT 1')->result_array();

                            if(count($__course)>0){
//                                $__course[0]['Semester'] = $dataWhere['Semester'];
                                $__course[0]['Semester'] = $dataOffering[$f]['Semester'];
                                array_push($dataCourse,$__course[0]);
                            }
                        }

                    }
                }

                $result[$c]['SemesterDetails'] = $dataOffering;
                $result[$c]['DetailCourse'] = $dataCourse;

            }
        }

        return $result;
    }


    public function getOneSchedule($ScheduleID){
//        $data = $this->db->query('SELECT s.ID,sm.Name AS semesterName,sm.ID AS SemesterID, pc.Name AS viewProgramsCampus,
//                                          s.CombinedClasses,
//                                          ps.NameEng AS ProgramStudy,
//                                          s.ClassGroup AS viewClassGroup,
//                                          mk.ID AS MKID, mk.MKCode, mk.Name AS viewMataKuliah, mk.NameEng AS viewMataKuliahEng,
//                                          cd.Semester, cd.TotalSKS,
//                                          em.Name AS Coordinator,
//                                          em.NIP,
//                                          s.TeamTeaching,
//                                          s.SubSesi
//                                          FROM  db_academic.schedule s
//                                          LEFT JOIN db_academic.semester sm ON (s.SemesterID = sm.ID)
//                                          LEFT JOIN db_academic.programs_campus pc ON (s.ProgramsCampusID = pc.ID)
//                                          LEFT JOIN db_academic.program_study ps ON (s.ProdiID = ps.ID)
//                                          LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = s.MKID)
//                                          LEFT JOIN db_academic.curriculum_details cd ON (sm.CurriculumID = cd.CurriculumID AND cd.MKID = s.MKID)
//                                          LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
//                                          WHERE s.ID = "'.$ScheduleID.'" LIMIT 1');
        $data = $this->db->query('SELECT s.ID,sm.Name AS semesterName,
                                          sm.ID AS SemesterID, pc.Name AS viewProgramsCampus,
                                          s.CombinedClasses,
                                          s.ClassGroup AS viewClassGroup,
                                          
                                          em.Name AS Coordinator,
                                          em.NIP,
                                          s.TeamTeaching,
                                          s.SubSesi                                          
                                          FROM  db_academic.schedule s 
                                          LEFT JOIN db_academic.semester sm ON (s.SemesterID = sm.ID)
                                          LEFT JOIN db_academic.programs_campus pc ON (s.ProgramsCampusID = pc.ID)
                                          
                                          LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                          WHERE s.ID = "'.$ScheduleID.'" LIMIT 1');

        $result = $data->result_array();

        if(count($result)>0){
            if($result[0]['TeamTeaching']==1){
                $dataTeam = $this->getTeamTeaching($result[0]['ID']);
                for($i2=0;$i2<count($dataTeam);$i2++){
                    $result[0]['DetailTeamTeaching'][$i2] = $dataTeam[$i2]['NIP'];
                }
            }

            // Get Sesi
            $dataSesi = $this->db->query('SELECT sd.ID AS sdID ,sd.ClassroomID,sd.Credit,sd.DayID,sd.TimePerCredit,sd.StartSessions,sd.EndSessions,
                                          cl.Room  FROM db_academic.schedule_details sd LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                          WHERE sd.ScheduleID = "'.$ScheduleID.'" ');
            $result[0]['SubSesiDetails'] = $dataSesi->result_array();

            $dataCourse = $this->db->query('SELECT sdc.CDID, mk.ID AS MKID, mk.MKCode, mk.Name, mk.NameEng, 
                                                      ps.ID AS ProdiID, ps.Code, ps.Name AS Prodi, ps.NameEng AS ProdiEng
                                                      FROM db_academic.schedule_details_course sdc 
                                                      LEFT JOIN db_academic.program_study ps ON (ps.ID = sdc.ProdiID)
                                                      LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                      WHERE sdc.ScheduleID = "'.$ScheduleID.'" ');
            $result[0]['Courses'] = $dataCourse->result_array();
        }

        return $result[0];
    }

    private function getTeamTeaching($ScheduleID){
        $data = $this->db->query('SELECT stt.ID,stt.NIP,stt.Status,em.Name AS Lecturer FROM db_academic.schedule_team_teaching stt
                                            LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                            WHERE stt.ScheduleID = "'.$ScheduleID.'" ');

        return $data->result_array();
    }


    public function getSchedule2($DayID,$dataWhere){

        if(count($DayID)>0){

        } else {
            for($i=0;$i<count();$i++){

            }

        }




        if(count($dataWhere)>0){
            $where = '';
            for($i=0;$i<count($dataWhere);$i++){
                if($dataWhere['ProgramCampusID']!=''){
                    $where = $where.' AND ProgramCampusID='.$dataWhere['ProgramCampusID'];
                }

                if($dataWhere['SemesterID']!=''){
                    $where = $where.' AND SemesterID='.$dataWhere['SemesterID'];
                }
            }
        }

    }


    // Database Mahasiswa
    public function __getTahunAngkatan(){
        $data = $this->db->query('SELECT Year FROM db_academic.auth_students 
                                                GROUP BY Year ORDER BY Year ASC')->result_array();

        $result=[];
        for($i=0;$i<count($data);$i++){
            $DataStudents = $this->__getStudents($data[$i]['Year']);
            $result[$i]['Angkatan'] = $data[$i]['Year'];
            $result[$i]['DataStudents'] = $DataStudents;
        }
        return $result;
    }

    private function __getStudents($ta){
        $db = 'ta_'.$ta;
        $data = $this->db->query('SELECT s.*, au.EmailPU, p.Name AS ProdiName, p.NameEng AS ProdiNameEng,
                                      ss.Description AS StatusStudentDesc
                                      FROM '.$db.'.students s
                                      JOIN db_academic.program_study p ON (s.ProdiID = p.ID)
                                      JOIN db_academic.status_student ss ON (s.StatusStudentID = ss.ID)
                                      JOIN db_academic.auth_students au ON (s.NPM = au.NPM) 
                                      ORDER BY s.NPM ASC ');

        return $data->result_array();
    }

    public function __getStudentByNPM($ta,$NPM){

        $db = 'ta_'.$ta;
        $data = $this->db->query('SELECT s.*, au.EmailPU, p.Name AS ProdiName, p.NameEng AS ProdiNameEng,
                                      ss.Description AS StatusStudentDesc
                                      FROM '.$db.'.students s
                                      JOIN db_academic.program_study p ON (s.ProdiID = p.ID)
                                      JOIN db_academic.status_student ss ON (s.StatusStudentID = ss.ID)
                                      JOIN db_academic.auth_students au ON (s.NPM = au.NPM)
                                      WHERE s.NPM = "'.$NPM.'" LIMIT 1');

        return $data->result_array();
    }

    public function __checkClassGroup($ProgramsCampusID,$SemesterID,$ProdiCode,$IsSemesterAntara){


        $data = $this->db->query('SELECT scg.* FROM db_academic.schedule s 
                                                LEFT JOIN db_academic.schedule_class_group scg ON (s.ID=scg.ScheduleID) 
                                                WHERE s.ProgramsCampusID = "'.$ProgramsCampusID.'" AND
                                                    s.SemesterID = "'.$SemesterID.'" AND
                                                    s.IsSemesterAntara = "'.$IsSemesterAntara.'" AND
                                                    scg.ProdiCode = "'.$ProdiCode.'"  ');

        return $data->result_array();
    }

    public function __checkClassGroupParalel($ProgramsCampusID,$SemesterID,$ProdiCode,$IsSemesterAntara){

        $data = $this->db->query('SELECT * FROM db_academic.schedule_class_group scg 
                                            LEFT JOIN db_academic.schedule s ON (s.ID = scg.ScheduleID)
                                            WHERE scg.ProdiCode LIKE "'.$ProdiCode.'" 
                                            AND s.SemesterID = "'.$SemesterID.'"
                                            AND s.ProgramsCampusID = "'.$ProgramsCampusID.'"
                                            AND s.IsSemesterAntara = "'.$IsSemesterAntara.'"
                                            AND scg.Type = "1"
                                            AND scg.Alphabet = 0
                                             ')->result_array();

        if(count($data)>0){
            for($s=0;$s<count($data);$s++){
                $data_s = $this->db->query('SELECT COUNT(*) AS alp FROM db_academic.schedule_class_group scg 
                                            LEFT JOIN db_academic.schedule s ON (s.ID = scg.ScheduleID)
                                            WHERE scg.ProdiCode LIKE "'.$ProdiCode.'" 
                                            AND s.SemesterID = "'.$SemesterID.'"
                                            AND s.ProgramsCampusID = "'.$ProgramsCampusID.'"
                                            AND s.IsSemesterAntara = "'.$IsSemesterAntara.'"
                                            AND scg.Type = "1"
                                            AND scg.Numeric = "'.$data[$s]['Numeric'].'"
                                             ')->result_array();
                $data[$s]['alp'] = $data_s[0]['alp'];
            }
        }

        return $data;
    }

    public function __getAllClassRoom(){
        $data = $this->db->query('SELECT * FROM db_academic.classroom');
        return $data->result_array();
    }

    public function __getAllGrade(){
        $data = $this->db->query('SELECT * FROM db_academic.grade ORDER BY EndRange DESC');
        return $data->result_array();
    }

    public function __getRangeCredits(){
        $data = $this->db->query('SELECT * FROM db_academic.range_credits ORDER BY Credit DESC');
        return $data->result_array();
    }

    public function __getAllTimePerCredit(){
        $data = $this->db->query('SELECT * FROM db_academic.time_per_credits ORDER BY Time DESC');
        return $data->result_array();
    }

    public function __getLecturerDetail($NIP){

//        $data = $this->db->query('SELECT e.* FROM db_employees.employees e WHERE e.NIP="'.$NIP.'" AND e.PositionMain = "14.7"');
        $data = $this->db->query('SELECT e.* FROM db_employees.employees e 
                  WHERE e.NIP="'.$NIP.'" LIMIT 1 ')->result_array();

        if(count($data)>0){
            return $data[0];
        } else {
            return $data;
        }


    }

    public function __checkSchedule($dataFilter){

//        echo 'SELECT s.ID AS ScheduleID,s.ClassGroup , sd.ID AS sdID, sd.DayID,sd.StartSessions, sd.EndSessions, cl.Room
//                                              FROM db_academic.schedule s
//                                              RIGHT JOIN db_academic.schedule_details sd ON (s.ID=sd.ScheduleID)
//                                              LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
//                                              WHERE s.SemesterID="'.$dataFilter['SemesterID'].'"
//                                              AND s.IsSemesterAntara="'.$dataFilter['IsSemesterAntara'].'"
//                                              AND sd.ClassroomID="'.$dataFilter['ClassroomID'].'"
//                                              AND sd.DayID="'.$dataFilter['DayID'].'"
//                                              AND (("'.$dataFilter['StartSessions'].'" >= sd.StartSessions  AND "'.$dataFilter['StartSessions'].'" <= sd.EndSessions) OR
//                                              ("'.$dataFilter['EndSessions'].'" >= sd.StartSessions AND "'.$dataFilter['EndSessions'].'" <= sd.EndSessions) OR
//                                              ("'.$dataFilter['StartSessions'].'" <= sd.StartSessions AND "'.$dataFilter['EndSessions'].'" >= sd.EndSessions)
//                                              ) ORDER BY sd.StartSessions ASC ';

        $jadwal = $this->db->query('SELECT s.ID AS ScheduleID,s.ClassGroup , sd.ID AS sdID, sd.DayID,sd.StartSessions, sd.EndSessions, cl.Room                                               
                                              FROM db_academic.schedule s
                                              RIGHT JOIN db_academic.schedule_details sd ON (s.ID=sd.ScheduleID)   
                                              LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                              WHERE s.SemesterID="'.$dataFilter['SemesterID'].'"
                                              AND s.IsSemesterAntara="'.$dataFilter['IsSemesterAntara'].'" 
                                              AND sd.ClassroomID="'.$dataFilter['ClassroomID'].'" 
                                              AND sd.DayID="'.$dataFilter['DayID'].'" 
                                              AND (("'.$dataFilter['StartSessions'].'" >= sd.StartSessions  AND "'.$dataFilter['StartSessions'].'" <= sd.EndSessions) OR
                                              ("'.$dataFilter['EndSessions'].'" >= sd.StartSessions AND "'.$dataFilter['EndSessions'].'" <= sd.EndSessions) OR
                                              ("'.$dataFilter['StartSessions'].'" <= sd.StartSessions AND "'.$dataFilter['EndSessions'].'" >= sd.EndSessions)
                                              ) ORDER BY sd.StartSessions ASC 
                                              ')->result_array();

        if(count($jadwal)>0){
            for($i=0;$i<count($jadwal);$i++){
                $dataCourse = $this->db->query('SELECT sdc.*,mk.NameEng, mk.ID AS MKID, mk.MKCode FROM db_academic.schedule_details_course sdc
                                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                        WHERE sdc.ScheduleID = "'.$jadwal[$i]['ScheduleID'].'" GROUP BY sdc.ScheduleID ')->result_array();

                $jadwal[$i]['DetailsCourse'] = $dataCourse;
            }
        }

        return $jadwal;

    }

    public function saveDataWilayah($arr)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        $data = $arr['data'];
        $arr_temp = array();
        $sql ="select RegionID from db_admission.region";
        $query=$this->db->query($sql, array())->result();
        foreach ($query as $key) {
            $arr_temp[] =  $key->RegionID;
        }

        $kode_wilayah_arr = array();
        for ($i=0; $i < count($data); $i++) {
            // find data in array
            $kode_wilayah = $data[$i]['kode_wilayah'];
            $kode_wilayah_arr[] = $kode_wilayah;
            if (!in_array($kode_wilayah, $arr_temp)) {
                $dataSave = array(
                        'RegionID' => $data[$i]['kode_wilayah'],
                        'RegionName' => $data[$i]['nama'],
                        'RegionCodeMst' => $data[$i]['mst_kode_wilayah']
                );

                $this->db->insert('db_admission.region', $dataSave);
            }
            
        }

        return $kode_wilayah_arr;
    }

    public function saveDataSchool($arr)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        $data = $arr['data'];
        $arr_temp = array();
        $sql ="select SchoolID from db_admission.school";
        $query=$this->db->query($sql, array())->result();
        foreach ($query as $key) {
            $arr_temp[] =  $key->SchoolID;
        }

        $kode_school_arr = array();
        for ($i=0; $i < count($data); $i++) {
            // find data in array
            $kode_school = $data[$i]['id'];
            $kode_school_arr[] = $kode_school;
            if (!in_array($kode_school, $arr_temp)) {
                $dataSave = array(
                        'ProvinceID' => $data[$i]['kode_prop'],
                        'ProvinceName' => $data[$i]['propinsi'],
                        'CityID' => $data[$i]['kode_kab_kota'],
                        'CityName' => $data[$i]['kabupaten_kota'],
                        'DistrictID' => $data[$i]['kode_kec'],
                        'DistrictName' => $data[$i]['kecamatan'],
                        'SchoolID' => $data[$i]['id'],
                        'npsn' => $data[$i]['npsn'],
                        'SchoolName' => $data[$i]['sekolah'],
                        'SchoolType' => $data[$i]['bentuk'],
                        'Status' => $data[$i]['status'],
                        'SchoolAddress' => $data[$i]['alamat_jalan'],
                        'Latitude' => $data[$i]['lintang'],
                        'Longitude' => $data[$i]['bujur'],
                );

                $this->db->insert('db_admission.school', $dataSave);
            }
            
        }

        //return $kode_school_arr;
        return "Done";
    }

    public function getdataWilayah()
    {
        $sql = "select * from db_admission.region";
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function __getSMAWilayah($kode_wilayah)
    {
        $sql = "select * from db_admission.school as a where a.CityID = ? and Approved = 1";
        $query=$this->db->query($sql, array($kode_wilayah))->result_array();
        return $query;
    }

    public function getDataRegisterBelumBayar()
    {
        $sql = "select a.* from (
                select a.ID,a.Name,a.Email,b.SchoolName,a.PriceFormulir,a.VA_number,a.BilingID,a.Datetime_expired,a.RegisterAT,c.FileUpload,c.CreateAT as uploadAT,c.ID as ver_id
                        from db_admission.register as a LEFT JOIN db_admission.school as b
                        on a.SchoolID = b.ID
                        LEFT JOIN db_admission.register_verification as c
                        on a.ID = c.RegisterID
                ) as a
                where a.ver_id not in (select RegVerificationID from db_admission.register_verified)
                UNION
                select a.ID,a.Name,a.Email,b.SchoolName,a.PriceFormulir,a.VA_number,a.BilingID,a.Datetime_expired,a.RegisterAT,null,null,null
                from db_admission.register as a LEFT JOIN db_admission.school as b
                on a.SchoolID = b.ID
                where a.ID not in(select RegisterID from db_admission.register_verification)
                ORDER BY uploadAT desc";        
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function getDataRegisterTelahBayar()
    {
        $sql = "select a.ID,a.Name,a.Email,b.SchoolName,a.PriceFormulir,a.VA_number,a.BilingID,a.Datetime_expired,a.RegisterAT,c.FileUpload,c.CreateAT as uploadAT,c.ID as ver_id,d.FormulirCode,d.VerificationAT,e.name as VerificationBY,
                d.ID as verified_id
                from db_admission.register as a LEFT JOIN db_admission.school as b
                on a.SchoolID = b.ID
                JOIN db_admission.register_verification as c
                on a.ID = c.RegisterID
                join db_admission.register_verified as d
                on c.ID = d.RegVerificationID
                LEFT JOIN db_employees.employees as e
                on e.NIP = d.VerificationBY where a.StatusReg = 0";        
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function __checkDateKRS($SemesterIDActive,$date,$ProdiID,$NPM,$DB_std){

        // Cek apakah ada special casenya
        $dataSC = $this->db->query('SELECT * FROM db_academic.academic_years_special_case WHERE SemesterID = "'.$SemesterIDActive.'"
                                              AND AcademicDescID = 1 AND UserID = "'.$ProdiID.'" AND Status = "2"
                                               AND Start <= "'.$date.'" AND End >= "'.$date.'" ')->result_array();

        if(count($dataSC)>0){
            $data[0] = array(
                'SemesterID' => $SemesterIDActive,
                'krsStart' => $dataSC[0]['Start'],
                'krsEnd' => $dataSC[0]['End'],
            );
        } else {
            $data = $this->db->query('SELECT ay.krsStart,ay.krsEnd,ay.SemesterID FROM  db_academic.academic_years ay
                                            WHERE ay.krsStart <= "'.$date.'" 
                                            AND ay.krsEnd >= "'.$date.'" 
                                            AND ay.SemesterID = "'.$SemesterIDActive.'" ')->result_array();
        }

        $dataCekbayarBPP = $this->db->query('SELECT p.* FROM db_academic.semester s
                                                    LEFT JOIN db_finance.payment p ON (s.ID = p.SemesterID) 
                                                    WHERE p.PTID = "2" 
                                                    AND p.NPM = "'.$NPM.'" 
                                                    AND s.Status = 1 ')->result_array();

        if(count($data)>0){
            $data[0]['PaymentDetails'] = $dataCekbayarBPP;
        }
        return $data;
    }

    public function __checkDateKRSLecturer($date){
        $data = $this->db->query('SELECT ay.krsStart,ay.krsEnd,ay.SemesterID FROM db_academic.semester s 
                                            JOIN db_academic.academic_years ay ON (ay.SemesterID = s.ID)
                                            WHERE ay.krsStart <= "'.$date.'" 
                                            AND ay.krsEnd >= "'.$date.'" 
                                            AND s.Status = 1 ')->result_array();

//        $dataCekbayarBPP = $this->db->query('SELECT p.* FROM db_academic.semester s
//                                                    LEFT JOIN db_finance.payment p ON (s.ID = p.SemesterID)
//                                                    WHERE p.PTID = "2"
//                                                    AND p.NPM = "'.$NPM.'"
//                                                    AND s.Status = 1 ')->result_array();
//
//        if(count($data)>0){
//            $data[0]['PaymentDetails'] = $dataCekbayarBPP;
//        }
        return $data;
    }

    public function getScheduleDetails($ScheduleID){
        $dataSchedule = $this->db
                            ->select('db_academic.schedule.*, db_academic.programs_campus.Name AS ProgramCampus, db_employees.employees.Name AS CoordinatorName')
                            ->join('db_academic.programs_campus','db_academic.programs_campus.ID = db_academic.schedule.ProgramsCampusID')
                            ->join('db_employees.employees','db_employees.employees.NIP = db_academic.schedule.Coordinator')
                            ->get_where('db_academic.schedule',array('db_academic.schedule.ID'=>$ScheduleID),1)->result_array()[0];

        $result = $dataSchedule;

        // Detail Course
        $dataCourse = $this->db->query('SELECT sdc.*, mk.Name AS MKName, mk.NameEng AS MKNameEng, mk.MKCode,
                                                  ps.Name AS Prodi, ps.NameEng AS ProdiEng, ps.Code AS ProdiCode
                                                  FROM db_academic.schedule_details_course sdc
                                                  LEFT JOIN db_academic.program_study ps ON (sdc.ProdiID = ps.ID)
                                                  LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                  LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                  WHERE sdc.ScheduleID = "'.$ScheduleID.'"
                                                   ')->result_array();
        $result['DetailCourse'] = $dataCourse;

        // Daata Sesi
        $dataSubSesi = $this->db->query('SELECT sd.*, cl.Room, cl.Seat, cl.SeatForExam FROM db_academic.schedule_details sd
                                                  LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                                  LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                                  WHERE sd.ScheduleID = "'.$ScheduleID.'"
                                                  ')->result_array();

        $result['DetailSubSesi'] = $dataSubSesi;

        $dataTeamTeaching = $this->db->query('SELECT stt.*,e.Name AS Lecturer FROM db_academic.schedule_team_teaching stt
                                                        LEFT JOIN db_employees.employees e ON (stt.NIP = e.NIP)
                                                        WHERE stt.ScheduleID = "'.$ScheduleID.'"
                                                        ')->result_array();

        $result['DetailTeamTeaching'] = $dataTeamTeaching;

        if($result['IsSemesterAntara']=='0'){
            $dataCurriculum = $this->db->select('Name')->get_where('db_academic.semester',array('ID'=>$result['SemesterID']),1)->result_array();
        } else {
            $dataCurriculum = $this->db->select('Name')->get_where('db_academic.semester_antara',array('SemesterID'=>$result['SemesterID']),1)->result_array();
        }

        $result['DataCurriculum'] = $dataCurriculum[0];

        return $result;

    }

    public function __checkCourse($SemesterID,$MKID){
        $data = $this->db->query('SELECT * FROM db_academic.schedule s 
                                  JOIN db_academic.schedule_details_course sdc 
                                  ON (s.ID = sdc.ScheduleID)
                                  WHERE s.SemesterID = "'.$SemesterID.'" 
                                  AND sdc.MKID = "'.$MKID.'" ');
        return $data->result_array();
    }

    private function _getSemesterActive(){
        $data = $this->db->get_where('db_academic.semester', array('Status'=>'1'),1);

        return $data->result_array()[0];
    }

    private function _getClassStd(){
        $data = $this->db->query('SELECT ast.Year FROM db_academic.auth_students ast GROUP BY ast.Year');
        return $data->result_array();
    }

    public function __getStudyPlanning($dataWhere){

        $db_ta = 'ta_'.$dataWhere['ClassOf'];

        $dataSemester = $this->_getSemesterActive();

        $data = $this->db->query('SELECT s.Name,s.NPM,s.ClassOf,ast.EmailPU FROM '.$db_ta.'.students s 
                                                    LEFT JOIN db_academic.mentor_academic ma ON (ma.NPM = s.NPM)
                                                    LEFT JOIN db_academic.auth_students ast ON (ast.NPM = s.NPM)
                                                    WHERE s.ProdiID = "'.$dataWhere['ProdiID'].'" 
                                                    AND s.ProgramID = "'.$dataWhere['ProgramID'].'" 
                                                    ORDER BY s.NPM ASC')
                        ->result_array();

        $result = [];
        if(count($data)>0){
            $smtActID = $dataSemester['ID'];
            for($i=0;$i<count($data);$i++){
                $data_stdCourse = $this->db->query('SELECT cd.Semester,cd.TotalSKS FROM db_academic.std_krs sk 
                                                      LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sk.CDID)
                                                      WHERE sk.NPM = "'.$data[$i]['NPM'].'" ')
                                        ->result_array();

                $data_mentor = $this->db->query('SELECT ma.NIP,em.Name AS Mentor FROM db_academic.mentor_academic ma
                                                    LEFT JOIN db_employees.employees em ON (em.NIP = ma.NIP)
                                                    WHERE ma.NPM = "'.$data[$i]['NPM'].'" ')->result_array();

                $data[$i]['DetailSemester'] = $this->getMaxCredit($db_ta,$data[$i]['NPM'],$dataWhere['ClassOf'],$smtActID,$dataWhere['ProdiID']);
                $data[$i]['DetailPayment'] = $this->getPayment($dataSemester['ID'],$data[$i]['NPM']);
                $data[$i]['DetailMentor'] = $data_mentor;

                $dataRes = array(
                    'Student' => $data[$i],
                    'StudyPlanning' => $data_stdCourse
                );

                array_push($result,$dataRes);
            }
        }

//        print_r($result);
//
//        exit;

        return $result;

    }

    private function getPayment($SemesterID,$NPM){
        $data = $this->db->query('SELECT p.PTID,p.Status FROM db_finance.payment p WHERE p.NPM = "'.$NPM.'" 
                                            AND p.SemesterID = "'.$SemesterID.'"');
        return $data->result_array();
    }

    private function getMaxCredit($db_ta,$NPM,$ClassOf,$smtActID,$ProdiID){


        $dataIDLast = $this->db->query('SELECT * FROM db_academic.semester s 
                                        WHERE s.ID < "'.$smtActID.'" ORDER BY ID DESC LIMIT 1')
                                    ->result_array();


        if(count($dataIDLast)>0){
            $dataResult = $this->db->query('SELECT s.GradeValue,s.SemesterID,cd.TotalSKS AS Credit FROM '.$db_ta.'.study_planning s
                                                LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = s.CDID) 
                                                WHERE s.NPM = "'.$NPM.'" ORDER BY s.SemesterID ASC ')->result_array();

//            print_r($dataResult);
            $TotalSKS=0;
            $totalGradeValue=0;

            $TotalSKSSemester=0;
            $totalGradeValueSemester=0;

            for ($s=0;$s<count($dataResult);$s++){

                // Menghitung IPK
                $TotalSKS = $TotalSKS + (int) $dataResult[$s]['Credit'];
                $gradeV = (int) $dataResult[$s]['Credit'] * (float) $dataResult[$s]['GradeValue'];

                $totalGradeValue =$totalGradeValue + $gradeV;

                if($dataResult[$s]['SemesterID']==$dataIDLast[0]['ID']){
                    $TotalSKSSemester = $TotalSKSSemester + (int) $dataResult[$s]['Credit'];
                    $gradeVSemester = (int) $dataResult[$s]['Credit'] * (float) $dataResult[$s]['GradeValue'];
                    $totalGradeValueSemester = $totalGradeValueSemester + $gradeVSemester;
                }

            }



            $IPK = ($totalGradeValue>0) ? $totalGradeValue/$TotalSKS : 0;

            $LastIPS = ($totalGradeValueSemester==0 || $TotalSKSSemester==0) ? 0 : $totalGradeValueSemester/$TotalSKSSemester;


            // Semester Saat Ini
            $dataTotalSmt = $this->db->query('SELECT s.Status FROM db_academic.semester s 
                                                    WHERE s.ID >= (SELECT ID FROM db_academic.semester s2 
                                                    WHERE s2.Year="'.$ClassOf.'" 
                                                    LIMIT 1)')->result_array();

            $smt = 0;
            for($s=0;$s<count($dataTotalSmt);$s++){
                if($dataTotalSmt[$s]['Status']=='1'){
                    $smt += 1;
                    break;
                } else {
                    $smt += 1;
                }
            }

            // Cek semester
            if($smt==1 || $smt=='1'){
                $dataCreditDef = $this->db->select('DefaultCredit')->get_where('db_academic.program_study',
                    array('ID' => $ProdiID),1)->result_array();

                $IPK = 0;
                $LastIPS = 0;
                $MaxCredit = array('Credit'=>$dataCreditDef[0]['DefaultCredit'],'Flag'=>'0');
                $smt = 1;
            } else {
                //Cek Maksimal Credit apakah di custom atau tidak
                $dataCustomCredit = $this->db->select('Credit')->get_where('db_academic.limit_credit',
                    array('SemesterID'=>$smtActID,'NPM'=>$NPM),1)->result_array();

                if(count($dataCustomCredit)>0){
                    $MaxCredit = array('Credit'=>$dataCustomCredit[0]['Credit'],'Flag'=>'2');
                } else {

                    $pembulatanIPS = round($LastIPS,2);

                    $dataMakCredit = $this->db->query('SELECT Credit FROM db_academic.range_credits WHERE 
                                                      IPSStart <= '.$pembulatanIPS.' 
                                                      AND IPSEnd >= '.$pembulatanIPS.' LIMIT 1')->result_array();
                    $MaxCredit = array('Credit' => $dataMakCredit[0]['Credit'],'Flag'=>'1');
                }
            }

        }
        else {
            $IPK = 0;
            $LastIPS = 0;
            $MaxCredit = array('Credit'=>0,'Flag'=>'-1');
            $smt = 1;
        }



        $result = array(
//            'LastIPS' => $dataResult[0],
            'IPK' => $IPK,
            'LastIPS' => $LastIPS,
            'MaxCredit' => $MaxCredit,
            'Semester' => $smt
        );


        return $result;
    }

    private function getScheduleByCDID($student_DB,$NPM,$CDID,$smtActID){

        $data = $this->db->query('SELECT s.*, 
                                            cd.ID AS CDID, cd.MKType, cd.Semester ,cd.TotalSKS AS Credit, cd.StatusPrecondition, cd.DataPrecondition, 
                                            mk.ID AS MKID, mk.MKCode, mk.Name AS MKName, mk.NameEng AS MKNameEng
                                            FROM db_academic.schedule_details_course sdc
                                            LEFT JOIN db_academic.schedule s ON (s.ID = sdc.ScheduleID)
                                            LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                            LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                            WHERE sdc.CDID = "'.$CDID.'" AND s.SemesterID = "'.$smtActID.'" ')->result_array();

        $result = [];
        if(count($data)>0){

            for($s=0;$s<count($data);$s++){
                $d = $data[$s];
                if($d['StatusPrecondition']==1){
                    $dataPre = json_decode($d['DataPrecondition']);

                    $pre_arr = [];
                    $cek_arr = [];
                    for($i=0;$i<count($dataPre);$i++){
                        $exp = explode('.',$dataPre[$i]);
                        $pre = $this->db->query('SELECT ID,MKcode,Name,NameEng FROM db_academic.mata_kuliah 
                                            WHERE ID="'.$exp[0].'"')->result_array();

                        // Cek apakah prasyarat sudah diambil atau belum
                        $cekPre = $this->db
                            ->get_where($student_DB.'.study_planning', array('NPM'=>$NPM,'MKID'=>$exp[0]),1)
                            ->result_array();

                        if(count($cekPre)>0){
                            array_push($cek_arr,1);
                        } else {
                            array_push($cek_arr,0);
                        }

                        array_push($pre_arr,$pre[0]);
                    }

                    $d['DetailPrecondition'] = $pre_arr;
                    if(in_array(0, $cek_arr)){
                        $d['AllowPrecondition'] = 0;
                    } else {
                        $d['AllowPrecondition'] = 1;
                    }

                }

                $dataMK = $this->db
                    ->get_where($student_DB.'.study_planning', array('NPM'=>$NPM,'MKID'=>$d['MKID']),1)
                    ->result_array();

                $d['SPID'] = (count($dataMK)>0)? $dataMK[0]['ID'] : null ;

                //Status KRS
                $dataStatus = $this->db->get_where('db_academic.std_krs',array('NPM'=>$NPM,'CDID'=>$CDID),1)->result_array();
                $d['DetailKRS'] = (count($dataStatus)>0)? $dataStatus[0] : $dataStatus;
                // Get Sesi
                $dataSesi = $this->db->query('SELECT sd.ID, d.Name AS DayName,d.NameEng AS DayNameEng, cl.Room, cl.Seat, 
                                                  sd.StartSessions, sd.EndSessions, sd.ClassroomID, sd.DayID, sd.ScheduleID  
                                                    FROM db_academic.schedule_details sd 
                                                    LEFT JOIN db_academic.days d ON (d.ID=sd.DayID)
                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID=sd.ClassroomID)
                                                    WHERE sd.ScheduleID = "'.$d['ID'].'" ')->result_array();

                for($s2=0;$s2<count($dataSesi);$s2++){
                    $whereCheck = array(
                        'SemesterID' => $d['SemesterID'],
                        'ScheduleID' => $d['ID'],
                        'CDID' => $CDID
                    );
                    $querySeat = $this->db->get_where('db_academic.std_krs', $whereCheck)->result_array();
                    $dataSesi[$s2]['CountSeat'] = count($querySeat);
                }

                $d['ScheduleDetails'] = $dataSesi;

                if($d['TeamTeaching']=='1'){
                    $dataTT = $this->db->query('SELECT * FROM db_academic.schedule_team_teaching stt WHERE stt.ScheduleID = "'.$d['ID'].'" ')->result_array();
                    $d['TeamTeachingDetails'] = $dataTT;
                }

                array_push($result,$d);
            }

        }

        return $result;

    }

    public function getDetailStudyPlanning($NPM,$ta){
        $db_ta = 'ta_'.$ta;
        $data = $this->db->query('SELECT s.ProdiID,s.ProgramID,s.NPM,s.Name, s.Photo, ma.NIP AS AcademicMentor,s.Gender,
                                    ast.EmailPU ,
                                    em.Name AS Mentor, em.EmailPU AS MentorEmailPU
                                    FROM '.$db_ta.'.students s 
                                    LEFT JOIN db_academic.auth_students ast ON (s.NPM = ast.NPM)
                                    LEFT JOIN db_academic.mentor_academic ma ON (ma.NPM = s.NPM AND ma.Status = "1")
                                    LEFT JOIN db_employees.employees em ON (ma.NIP = em.NIP)
                                    WHERE s.NPM = "'.$NPM.'" AND s.StatusStudentID = "3" ')
                            ->result_array();

        $dataSemester = $this->_getSemesterActive();
        $smtActID = $dataSemester['ID'];
        $data[0]['DetailSemester'] = $this->getMaxCredit($db_ta,$NPM,$ta,$smtActID,$data[0]['ProdiID']);

        // Get Mata Kuliah yang ditawarkan
        $dataOfferings = $this->db->query('SELECT co.* FROM db_academic.course_offerings co 
                                            LEFT JOIN db_academic.curriculum c ON (c.ID = co.CurriculumID)
                                            WHERE co.SemesterID = "'.$smtActID.'" 
                                            AND co.ProgramsCampusID = "'.$data[0]['ProgramID'].'"
                                             AND co.ProdiID = "'.$data[0]['ProdiID'].'"
                                              AND co.Semester = "'.$data[0]['DetailSemester']['Semester'].'"
                                               AND co.IsSemesterAntara = "0"
                                                AND c.Year = "'.$ta.'" LIMIT 1 ')->result_array();

        $result = [];
        if(count($dataOfferings)>0){
            $dataCDID = json_decode($dataOfferings[0]['Arr_CDID']);
            $result = $dataOfferings[0];
            $result['Schedule'] = [];
            for($i=0;$i<count($dataCDID);$i++){
                $dataSch = $this->getScheduleByCDID($db_ta,$data[0]['NPM'],$dataCDID[$i],$smtActID);
                if(count($dataSch)>0){
                    for($s=0;$s<count($dataSch);$s++){
                        array_push($result['Schedule'],$dataSch[$s]);
                    }
//                    array_push($result['Schedule'],$dataSch);
                }

            }


            // DrafKRS
            $DetailDrafKRS = $this->db->query('SELECT sk.*,cd.TotalSKS AS Credit, 
                                                          skc.ID AS ReasonID, skc.Reason
                                                          FROM db_academic.std_krs sk 
                                                          LEFT JOIN db_academic.curriculum_details cd 
                                                          ON (cd.ID = sk.CDID)
                                                          LEFT JOIN db_academic.std_krs_comment skc ON (skc.KRSID = sk.ID)
                                                          WHERE sk.SemesterID = "'.$result['SemesterID'].'" 
                                                          AND sk.NPM = "'.$this->session->userdata('student_NPM').'" ');
            $result['ScheduleDraf'] = $DetailDrafKRS->result_array();

        }

        $data[0]['DetailOfferings'] = $result;

        $dataPlanning = $this->db->query('SELECT cd.ID AS CDID, s.ID AS ScheduleID, mk.ID AS MKID, 
                                                    mk.Name, mk.NameEng, mk.MKCode, 
                                                    cd.Semester, cd.TotalSKS AS Credit, 
                                                    s.ClassGroup,
                                                    sk.ID AS KRSID, sk.Status AS KRSStatus,
                                                    skc.ID AS ReasonID, skc.Reason, sk.TypeSP
                                                    FROM db_academic.std_krs sk 
                                                    LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sk.CDID)
                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                                    LEFT JOIN db_academic.schedule s ON (s.ID = sk.ScheduleID)
                                                    LEFT JOIN db_academic.std_krs_comment skc ON (skc.KRSID = sk.ID)
                                                    WHERE sk.SemesterID = "'.$smtActID.'" AND sk.NPM = "'.$NPM.'" ')->result_array();

        if(count($dataPlanning)>0){
            for($i=0;$i<count($dataPlanning);$i++){

                $dataPlanning[$i]['DetailSchedule'] = $this->db->query('SELECT sd.*,cl.Room,d.NameEng AS DayNameEng FROM db_academic.schedule_details sd
                                                                              LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                                                              LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                                                              WHERE sd.ScheduleID = "'.$dataPlanning[$i]['ScheduleID'].'" ')
                    ->result_array();

            }
        }

        $data[0]['Student_DB'] = $db_ta;
        $data[0]['Schedule'] = $dataPlanning;

        return $data[0];
    }

    public function createDBYearAcademicNew($db_new){
        $this->load->dbforge();

        if ($this->dbforge->create_database($db_new)) {

            // Student
            $this->db->query('CREATE TABLE '.$db_new.'.students (
                              `ID` int(11) NOT NULL AUTO_INCREMENT,
                              `ProdiID` int(11) DEFAULT NULL,
                              `ProgramID` int(11) DEFAULT NULL,
                              `LevelStudyID` int(11) DEFAULT NULL,
                              `ReligionID` int(11) DEFAULT NULL,
                              `NationalityID` int(11) DEFAULT NULL,
                              `ProvinceID` int(11) DEFAULT NULL,
                              `CityID` int(11) DEFAULT NULL,
                              `HighSchoolID` int(11) DEFAULT NULL,
                              `HighSchool` text,
                              `MajorsHighSchool` varchar(45) DEFAULT NULL,
                              `NPM` varchar(20) DEFAULT NULL,
                              `Name` varchar(200) DEFAULT NULL,
                              `Address` text,
                              `Photo` text,
                              `Gender` varchar(2) DEFAULT NULL,
                              `PlaceOfBirth` varchar(100) DEFAULT NULL,
                              `DateOfBirth` date DEFAULT NULL,
                              `Phone` varchar(10) DEFAULT NULL,
                              `HP` varchar(15) DEFAULT NULL,
                              `ClassOf` varchar(30) DEFAULT NULL,
                              `Email` varchar(100) DEFAULT NULL,
                              `Jacket` varchar(2) DEFAULT NULL,
                              `AnakKe` int(11) DEFAULT NULL,
                              `JumlahSaudara` int(11) DEFAULT NULL,
                              `NationExamValue` decimal(10,0) DEFAULT NULL,
                              `GraduationYear` int(11) DEFAULT NULL,
                              `IjazahNumber` varchar(50) DEFAULT NULL,
                              `Father` varchar(45) DEFAULT NULL,
                              `Mother` varchar(45) DEFAULT NULL,
                              `StatusFather` varchar(2) DEFAULT NULL,
                              `StatusMother` varchar(2) DEFAULT NULL,
                              `PhoneFather` varchar(15) DEFAULT NULL,
                              `PhoneMother` varchar(15) DEFAULT NULL,
                              `OccupationFather` text,
                              `OccupationMother` text,
                              `EducationFather` varchar(45) DEFAULT NULL,
                              `EducationMother` varchar(45) DEFAULT NULL,
                              `AddressFather` text,
                              `AddressMother` text,
                              `EmailFather` varchar(100) DEFAULT NULL,
                              `EmailMother` varchar(100) DEFAULT NULL,
                              `StatusStudentID` int(11) DEFAULT NULL,
                              PRIMARY KEY (`ID`),
                              UNIQUE KEY `NPM` (`NPM`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1');

            // study_planning
            $this->db->query('CREATE TABLE '.$db_new.'.study_planning (
                              `ID` int(11) NOT NULL AUTO_INCREMENT,
                              `SemesterID` int(11) DEFAULT NULL,
                              `MhswID` int(11) DEFAULT NULL,
                              `NPM` varchar(30) NOT NULL,
                              `ScheduleID` int(11) NOT NULL,
                              `TypeSchedule` enum("Br","Ul") DEFAULT NULL,
                              `CDID` int(11) DEFAULT NULL,
                              `MKID` int(11) DEFAULT NULL,
                              `Credit` int(11) DEFAULT NULL,
                              `Evaluasi1` float DEFAULT NULL,
                              `Evaluasi2` float DEFAULT NULL,
                              `Evaluasi3` float DEFAULT NULL,
                              `Evaluasi4` float DEFAULT NULL,
                              `Evaluasi5` float DEFAULT NULL,
                              `UTS` float DEFAULT NULL,
                              `UAS` float DEFAULT NULL,
                              `Score` float DEFAULT NULL,
                              `Grade` varchar(3) DEFAULT NULL,
                              `GradeValue` float DEFAULT NULL,
                              `Approval` enum("0","1") DEFAULT NULL,
                              `StatusSystem` enum("1","0") DEFAULT NULL COMMENT "0 = Siak Lama , 1 = Baru",
                              `Glue` varchar(45) DEFAULT NULL,
                              `Status` enum("0","1") DEFAULT NULL,
                              PRIMARY KEY (`ID`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1');

        }
    }


    public function __filterStudents($filter){

        $db_s = 'ta_'.$filter['Year'];

        $Year = ($filter['Year']!='')? ' ma.Year = "'.$filter['Year'].'" ' : '';
        $ProdiID = ($filter['ProdiID']!='')? ' AND ma.ProdiID = "'.$filter['ProdiID'].'" ' : '';
        $NIP = ($filter['NIP']!='') ? 'AND ma.NIP = "'.$filter['NIP'].'"' : '';

//        if($filter['NIP']!=''){
//            $NIP = 'AND ma.NIP = "'.$filter['NIP'].'"';
//        }


        $StudentsAlredyMentor = $this->db->query('SELECT ma.*,em.Name AS Lecturer FROM db_academic.mentor_academic ma
                                                      LEFT JOIN db_employees.employees em ON (em.NIP = ma.NIP)
                                                      WHERE '.$Year.' '.$ProdiID)->result_array();


        $StudentsGuidance = $this->db->query('SELECT ma.*,s.Name  FROM db_academic.mentor_academic ma
                                          LEFT JOIN '.$db_s.'.students s ON (s.NPM = ma.NPM)
                                        WHERE '.$Year.' '.$ProdiID.' '.$NIP.' ')->result_array();


        $AllStudents = $this->db->query('SELECT s.NPM, s.Name, em.Name AS Lecturer, ma.NIP, ma.ID AS IDMA FROM '.$db_s.'.students s
                                                        LEFT JOIN db_academic.mentor_academic ma ON (ma.NPM=s.NPM)
                                                        LEFT JOIN db_employees.employees em ON (em.NIP = ma.NIP)
                                                        WHERE s.ProdiID = "'.$filter['ProdiID'].'"  ')->result_array();

        $result = array(
//            'StudentsAlredyMentor' => $StudentsAlredyMentor,
//            'StudentsGuidance' => $StudentsGuidance,
            'AllStudents' => $AllStudents
        );

//        print_r('SELECT ma.* FROM db_academic.mentor_academic ma WHERE '.$Year.' '.$ProdiID.' '.$NIP);

        return $result;
    }

    public function getEmployeesBy($division = null,$position = null)
    {
        if ($division == null) {
            $division = '';
        }
        else
        {   
            if ($position != null) {
                $position = '.'.$position;
            }
            else
            {   
                $position = '';
            }
            $division = ' where a.PositionMain like "%'.$division.$position.'%"';
        }
        $sql = "select a.* from db_employees.employees as a ".$division; 
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function getFormulirOfflineAvailable()
    {
        $sql = "select FormulirCode from db_admission.formulir_number_offline_m where StatusJual = 0 and Print = 1";        
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function getSchoolbyNameAC($school)
    {
      $sql = 'select ID, SchoolName from db_admission.school where SchoolName like "%'.$school.'%"';
      $query=$this->db->query($sql, array())->result_array();
      return $query;
    }

    public function __getScheduleTeacher($SemesterID,$NIP){
        $dataCoor = $this->db->query('SELECT s.ClassGroup,sdc.ScheduleID, mk.ID AS MKID, mk.NameEng FROM db_academic.schedule s 
                                                RIGHT JOIN db_academic.schedule_details_course sdc ON (s.ID = sdc.ScheduleID)
                                                LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                WHERE s.SemesterID = "'.$SemesterID.'" 
                                                AND s.Coordinator = "'.$NIP.'"
                                                GROUP BY sdc.ScheduleID');
        return $dataCoor->result_array();
    }

    public function __getSpecialCase($SemesterID,$AcademicDescID){
        $data = $this->db->query('SELECT s.ClassGroup,aysc.*,mk.NameEng, em.Name AS Lecturers FROM db_academic.academic_years_special_case aysc 
                                            LEFT JOIN db_academic.schedule s ON (s.ID=aysc.DataID)
                                            RIGHT JOIN db_academic.schedule_details_course sdc ON (s.ID = sdc.ScheduleID)
                                            LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                            LEFT JOIN  db_employees.employees em ON (em.NIP=aysc.UserID)
                                            WHERE aysc.SemesterID = "'.$SemesterID.'" 
                                            AND aysc.AcademicDescID="'.$AcademicDescID.'"
                                            GROUP BY sdc.ScheduleID');

        return $data->result_array();
    }

    public function getJadwalUjian(){
        $SemesterActive = $this->_getSemesterActive();
        $data = $this->db->query('SELECT s.*,em.Name AS CoordinatorName FROM db_academic.schedule s 
                                          LEFT JOIN db_employees.employees em ON (s.Coordinator = em.NIP)
                                          WHERE s.SemesterID = "'.$SemesterActive['ID'].'" ');
        return $data->result_array();
    }

    public function getDateExam(){

        $SemesterActive = $this->_getSemesterActive();

        $data = $this->db->query('SELECT * FROM db_academic.academic_years 
                                          WHERE SemesterID = "'.$SemesterActive['ID'].'" 
                                          LIMIT 1');

        return $data->result_array()[0];

    }

    public function __checkDataCourseForExam($ScheduleID,$Type){

        $SemesterActive = $this->_getSemesterActive();

        // Cek Schedule Exam
        $dataExam = $this->db->query('SELECT * FROM db_academic.exam ex 
                                              WHERE ex.SemesterID = "'.$SemesterActive['ID'].'" 
                                              AND ex.ScheduleID = "'.$ScheduleID.'" 
                                              AND ex.Type = "'.$Type.'" ')->result_array();

        $dataSch = $this->db->query('SELECT em.NIP, em.Name FROM db_academic.schedule s 
                                              LEFT JOIN db_employees.employees em 
                                              ON (em.NIP = s.Coordinator)
                                              WHERE s.ID = "'.$ScheduleID.'" ');

        $dataCourse = $this->db->query('SELECT sdc.*, mk.MKCode, mk.NameEng AS Course, 
                                                ps.NameEng AS ProdiEng, cd.TotalSKS AS Credit
                                                FROM  db_academic.schedule_details_course sdc
                                                LEFT JOIN db_academic.program_study ps ON (ps.ID = sdc.ProdiID)
                                                LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                WHERE sdc.ScheduleID = "'.$ScheduleID.'"');

        $dataTeamTeaching = $this->db->query('SELECT em.NIP,em.Name AS Lecturer 
                                                  FROM db_academic.schedule_team_teaching stt 
                                                  LEFT JOIN db_employees.employees em ON (stt.NIP = em.NIP) 
                                                  WHERE stt.ScheduleID = "'.$ScheduleID.'"');

        $year = $this->_getClassStd();
        $dataStudents = [];
        $dataStudentsDetails = [];
        $TotalStudents = 0;
        for($y=0;$y<count($year);$y++){
            $db_ = 'ta_'.$year[$y]['Year'];

            $dataSt = $this->db->query('SELECT sp.MhswID, s.NPM, s.Name FROM '.$db_.'.study_planning sp 
                                                            LEFT JOIN '.$db_.'.students s ON (s.ID = sp.MhswID)
                                                            WHERE sp.ScheduleID = "'.$ScheduleID.'" 
                                                            AND sp.StatusSystem = "1" ')->result_array();

            if(count($dataSt)>=0){
                $arr = array(
                    'DB_Students' => $db_,
                    'Details' => $dataSt
                );

                $TotalStudents = $TotalStudents + count($dataSt);

                for($s=0;$s<count($dataSt);$s++){

                    // Cek Apakah ada Di Exam
                    $dataStdExamCheck = $this->db->query('SELECT exd.* FROM db_academic.exam ex
                                                                    LEFT JOIN db_academic.exam_details exd 
                                                                    ON (ex.ID = exd.ExamID)
                                                                    WHERE ex.ScheduleID = "'.$ScheduleID.'"
                                                                    AND exd.MhswID = "'.$dataSt[$s]['MhswID'].'" 
                                                                    LIMIT 1')
                                            ->result_array();

                    $dataSt[$s]['IDEd'] = (count($dataStdExamCheck)>0) ? $dataStdExamCheck[0]['ID'] : '' ;
                    $dataSt[$s]['DB_Students'] = $db_;
                    array_push($dataStudentsDetails,$dataSt[$s]);
                }

                array_push($dataStudents,$arr);
            }

        }

        $res = array(
            'Exam' => $dataExam,
            'Coordinator' => $dataSch->result_array(),
            'Course' => $dataCourse->result_array(),
            'TeamTeaching' => $dataTeamTeaching->result_array(),
            'Students' => $dataStudents,
            'TotalStudents' => $TotalStudents,
            'StudentsDetails' => $dataStudentsDetails
        );

        return $res;
    }

    public function getScheduleExam($SemesterID,$Type,$ProdiID){
        $data = $this->db->query('SELECT e.*,s.ClassGroup,d.NameEng AS DayEng, cl.Room,
                                      em1.Name AS Pengawas1Name, em2.Name AS Pengawas2Name 
                                      FROM db_academic.exam e 
                                      LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = e.ScheduleID)
                                      LEFT JOIN db_academic.schedule s ON (s.ID = e.ScheduleID)
                                      LEFT JOIN db_academic.classroom cl ON (cl.ID=e.ExamClassroomID)
                                      LEFT JOIN db_academic.days d ON (d.ID=e.DayID)
                                      LEFT JOIN db_employees.employees em1 ON (em1.NIP = e.Pengawas1)
                                      LEFT JOIN db_employees.employees em2 ON (em2.NIP = e.Pengawas2)
                                      WHERE e.SemesterID = "'.$SemesterID.'" 
                                      AND e.Type = "'.$Type.'" 
                                      AND sdc.ProdiID = "'.$ProdiID.'" ')->result_array();

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $dataCourse = $this->db->query('SELECT sdc.CDID,mk.MKCode, mk.NameEng AS MKNameEng,
                                                    em.Name AS Coordinator 
                                                    FROM db_academic.schedule_details_course sdc 
                                                    LEFT JOIN db_academic.schedule s ON (s.ID = sdc.ScheduleID)
                                                    LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                                    LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                                    WHERE sdc.ScheduleID = "'.$data[$i]['ScheduleID'].'" GROUP BY sdc.ScheduleID LIMIT 1')->result_array();
                $data[$i]['CourseDetails'] = $dataCourse[0];

                // Get Students
//                $dataStd = $this->db->query('SELECT * FROM db_academic.exam_details ed
//                                                      LEFT JOIN db ');
            }


        }

        return $data;

    }

    public function __getScore($ScheduleID) {

        $dataClassOf = $this->getClassOf();

        $dataCourse = $this->db->query('SELECT sdc.*,mk.NameEng AS MKNameEng, mk.MKCode, s.TotalAssigment FROM db_academic.schedule_details_course sdc 
                                                  LEFT JOIN db_academic.curriculum_details cd ON (sdc.CDID = cd.ID)
                                                  LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                                  LEFT JOIN db_academic.schedule s ON (s.ID = sdc.ScheduleID)
                                                  WHERE sdc.ScheduleID = "'.$ScheduleID.'" ')->result_array();

        $res = [];
        for($c=0;$c<count($dataClassOf);$c++){
            $db_ = 'ta_'.$dataClassOf[$c]['Year'];
            $dataSc = $this->db->query('SELECT sp.*,s.Name FROM '.$db_.'.study_planning sp 
                                                LEFT JOIN '.$db_.'.students s ON (s.NPM = sp.NPM)
                                                WHERE sp.ScheduleID = "'.$ScheduleID.'" AND sp.StatusSystem = "1" ')
                ->result_array();

            if(count($dataSc)>0){
                for($d=0;$d<count($dataSc);$d++){
                    $dataSc[$d]['DB_Student'] = $db_;
                }
                array_push($res,$dataSc);
            }

        }

        $result = array(
            'Course' => $dataCourse,
            'Students' => $res[0]
        );

        return $result;

    }

    public function __getGrade($Score){
        $data = $this->db->query('SELECT * FROM db_academic.grade g 
                                            WHERE g.StartRange<= "'.$Score.'" 
                                            AND g.EndRange >= "'.$Score.'" LIMIT 1');
        return $data->result_array()[0];
    }

    public function __getGradeSchedule($ScheduleID){
        $SemesterActive = $this->_getSemesterActive();
        $SemesterID = $SemesterActive['ID'];

        $data = $this->db->get_where('db_academic.grade_course',array(
            'SemesterID' => $SemesterID,
            'ScheduleID' => $ScheduleID
        ),1)->result_array();

        return $data;

    }

    public function __getDataAttendance($ScheduleID){
        $SemesterActive = $this->_getSemesterActive();
        $SemesterID = $SemesterActive['ID'];

        $data = $this->db->query('SELECT attd.ID AS AttendanceID, sd.*, cl.Room, d.NameEng AS DayNameEng 
                                            FROM db_academic.attendance attd 
                                            LEFT JOIN db_academic.schedule_details sd ON (sd.ID = attd.SDID)
                                            LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                            LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                            WHERE attd.SemesterID = "'.$SemesterID.'" AND attd.ScheduleID = "'.$ScheduleID.'"
                                            AND attd.ScheduleID ORDER BY sd.DayID, sd.StartSessions ASC')->result_array();

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $data[$i]['DetailStudents'] = $this->db->query('SELECT * FROM db_academic.attendance_students astd 
                                                                  WHERE astd.ID = "'.$data[$i]['AttendanceID'].'" ')
                                                                    ->result_array();
            }
        }

        return $data;
    }

    public function __getStudensAttd($SemesterID,$ScheduleID,$SDID,$Meeting){


        $dataAttd = $this->db->query('SELECT attd_s.*,ast.Year AS ta FROM db_academic.attendance_students attd_s
                                          LEFT JOIN db_academic.attendance attd ON (attd.ID = attd_s.ID_Attd)
                                          LEFT JOIN db_academic.auth_students ast ON (ast.NPM = attd_s.NPM)
                                          WHERE attd.SemesterID = "'.$SemesterID.'"
                                          AND attd.ScheduleID = "'.$ScheduleID.'"
                                          AND attd.SDID = "'.$SDID.'" ')->result_array();

        $result = [];
        if(count($dataAttd)>0){
            for($s=0;$s<count($dataAttd);$s++){
                $db_ = 'ta_'.$dataAttd[$s]['ta'];
                $dataStd = $this->db->select('ID,Name,NPM,ClassOf')
                        ->get_where($db_.'.students',array('NPM' => $dataAttd[$s]['NPM']))
                        ->result_array();

                $attdStd = ($dataAttd[$s]['M'.$Meeting]!='' && $dataAttd[$s]['M'.$Meeting]!=null) ? $dataAttd[$s]['M'.$Meeting] : '0';
                $arr = array(
                    'DetailStudent' => $dataStd[0],
                    'ID_Attd_S' => $dataAttd[$s]['ID'],
                    'DBStudent' => $db_,
                    'Status' => $attdStd,
                    'Description' => $dataAttd[$s]['D'.$Meeting]
                );

                array_push($result,$arr);

            }

        }

        return $result;

    }

    public function __getAttendanceSchedule($AttendanceID){
        $data = $this->db->get_where('db_academic.attendance',
            array('ID'=>$AttendanceID),1)
            ->result_array();

        // Mendapatkan Presensi Students
        $dataStd = $this->db->get_where('db_academic.attendance_students',
            array('ID_Attd' => $AttendanceID))->result_array();

        if(count($data)>0){
            for($s=1;$s<=14;$s++){

                $Lecturers = $this->db->query('SELECT al.*, em.Name FROM db_academic.attendance_lecturers al 
                                                         LEFT JOIN db_employees.employees em ON (al.NIP = em.NIP)
                                                         WHERE al.ID_Attd = "'.$data[0]['ID'].'" 
                                                         AND al.Meet = "'.$s.'" 
                                                         ORDER BY al.Date, al.In, al.Out ASC ')->result_array();

                $data[0]['AttdLecturers'.$s] = $Lecturers;

                $p = '-';
                $a = '-';

                if($data[0]['Meet'.$s] == 1 && $data[0]['Meet'.$s] == '1'){

                    $p = 0;
                    $a = 0;
                    for($st=0;$st<count($dataStd);$st++){
                        if($dataStd[$st]['M'.$s]=='2' || $dataStd[$st]['M'.$s]==2){
                            $a += 1;
                        } else if ($dataStd[$st]['M'.$s]=='1' || $dataStd[$st]['M'.$s]==1) {
                            $p += 1;
                        }
                    }
                }

                $dataWhereS_Ex = $this->db->get_where('db_academic.schedule_exchange',
                    array(
                        'ID_Attd' => $AttendanceID,
                        'Meeting'=>$s),1)->result_array();
                $ScheduleEx_Status = (count($dataWhereS_Ex)>0) ? $dataWhereS_Ex[0]['Status'] : '-';

                $data[0]['ScheduleExchange_Status'.$s] = $ScheduleEx_Status;
                $data[0]['S_P'.$s] = $p;
                $data[0]['S_A'.$s] = $a;

            }
        }

        return $data;

    }

    public function __getdataExchange($ID_Attd,$ScheduleID,$SDID,$Meeting){

        $dataSD = $this->db->query('SELECT * FROM db_academic.schedule_details sd 
                                        WHERE sd.ID = "'.$SDID.'" LIMIT 1 ')->result_array();

        $dataSEx = $this->db->query('SELECT * FROM db_academic.schedule_exchange se 
                                              WHERE se.ID_Attd = "'.$ID_Attd.'" 
                                              AND se.Meeting = "'.$Meeting.'" LIMIT 1')->result_array();

        $coor = $this->db->query('SELECT em.NIP,em.Name FROM db_academic.schedule s 
                                            LEFT JOIN db_employees.employees em ON (em.NIP = s.Coordinator)
                                            WHERE s.ID = "'.$ScheduleID.'"
                                              ')->result_array();

        $teamt = $this->db->query('SELECT em.NIP,em.Name FROM db_academic.schedule_team_teaching stt 
                                                    LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                    WHERE stt.ScheduleID = "'.$ScheduleID.'" 
                                                    ')->result_array();

        if(count($teamt)>0){
            for($t=0;$t<count($teamt);$t++){
                array_push($coor,$teamt[$t]);
            }
        }

        $res = array(
            'Lecturer' => $coor,
            'S_Details' => $dataSD[0],
            'S_Exchange' => $dataSEx
        );

        return $res;

    }

    public function getDataCourse2Score($SemesterID,$ProdiID,$CombinedClasses,$IsSemesterAntara){

        $dataSch = $this->db->query('SELECT sc.Classgroup,sc.TotalAssigment, sc.TeamTeaching,
                                        sdc.*,cd.TotalSKS AS Credit, mk.MKCode, mk.Name AS MKName,
                                        mk.NameEng AS MKNameEng, sc.Coordinator, em.Name AS CoordinatorName 
                                        FROM db_academic.schedule_details_course sdc 
                                        LEFT JOIN db_academic.schedule sc ON (sc.ID = sdc.ScheduleID)
                                        LEFT JOIN db_employees.employees em ON (em.NIP = sc.Coordinator)
                                        LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID) 
                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
                                        WHERE sc.SemesterID = "'.$SemesterID.'" 
                                        AND sdc.ProdiID = "'.$ProdiID.'"
                                        AND sc.CombinedClasses = "'.$CombinedClasses.'"
                                        AND sc.IsSemesterAntara = "'.$IsSemesterAntara.'" 
                                        ORDER BY sdc.ID ASC')->result_array();

        if(count($dataSch)>0){
            for($i=0;$i<count($dataSch);$i++){
                $detailSch = $this->db->query('SELECT cl.Room, d.NameEng AS DayEng, sd.StartSessions, sd.EndSessions FROM db_academic.schedule_details sd 
                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID = sd.ClassroomID)
                                                    LEFT JOIN db_academic.days d ON (d.ID = sd.DayID)
                                                    WHERE sd.ScheduleID = "'.$dataSch[$i]['ScheduleID'].'"
                                                     ORDER BY sd.DayID, sd.StartSessions ASC')->result_array();

                $team = $this->db->query('SELECT em.NIP, em.Name FROM db_academic.schedule_team_teaching stt 
                                                    LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                    WHERE stt.ScheduleID = "'.$dataSch[$i]['ScheduleID'].'" ')
                                            ->result_array();

                $dataSch[$i]['DetailTeamTeaching'] = $team;
                $dataSch[$i]['DetailSchedule'] = $detailSch;

                // Silabus SAP
                $dataSilabusSAP = $this->db->get_where('db_academic.grade_course',
                            array('SemesterID' => $SemesterID,
                                'ScheduleID' => $dataSch[$i]['ScheduleID']),1)->result_array();

                $dataSch[$i]['dataSilabusSAP'] = $dataSilabusSAP;
            }
        }


        return $dataSch;

    }

    public function getDataStudents_Schedule($SemesterID,$ScheduleID){

        $dataClassOf = $this->getClassOf();

        $res = [];
        for($c=0;$c<count($dataClassOf);$c++){
            $db_ = 'ta_'.$dataClassOf[$c]['Year'];
            $dataSc = $this->db->query('SELECT sp.*,s.Name FROM '.$db_.'.study_planning sp 
                                                LEFT JOIN '.$db_.'.students s ON (s.NPM = sp.NPM)
                                                WHERE sp.SemesterID = "'.$SemesterID.'" 
                                                AND sp.ScheduleID = "'.$ScheduleID.'" 
                                                AND sp.StatusSystem = "1" ')->result_array();

            if(count($dataSc)>0){
                for($d=0;$d<count($dataSc);$d++){
                    $dataSc[$d]['DB_Student'] = $db_;
                    array_push($res,$dataSc[$d]);
                }

            }

        }

        return $res;

    }

    public function getAllStudents(){
        $dataClassOf = $this->getClassOf();
        $res = [];
        for($c=0;$c<count($dataClassOf);$c++){
            $db_ = 'ta_'.$dataClassOf[$c]['Year'];
            $dataSc = $this->db->query('SELECT * FROM '.$db_.'.students ORDER BY ProdiID, NPM ASC ')->result_array();

            array_push($res,$dataSc);
        }

        print_r($res);

        return $res;
    }

    public function getAgama()
    {
        $sql = "select * from db_employees.religion";
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function insertToMainKRS($KRSID,$TypeSP,$db_ta){
        $dataCourse = $this->db->query('SELECT sk.*, s.ID AS MhswID, cd.MKID, cd.TotalSKS AS Credit 
                                                FROM db_academic.std_krs sk 
                                                LEFT JOIN '.$db_ta.'.students s ON (s.NPM = sk.NPM)
                                                LEFT JOIN db_academic.curriculum_details cd ON (sk.CDID = cd.ID)
                                                LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                                WHERE sk.ID = "'.$KRSID.'" ')
            ->result_array()[0];
        $dataInsert = array(
            'SemesterID' => $dataCourse['SemesterID'],
            'MhswID' => $dataCourse['MhswID'],
            'NPM' => $dataCourse['NPM'],
            'ScheduleID' => $dataCourse['ScheduleID'],
            'TypeSchedule' => $TypeSP,
            'CDID' => $dataCourse['CDID'],
            'MKID' => $dataCourse['MKID'],
            'Credit' => $dataCourse['Credit'],
            'StatusSystem' => '1',
            'Status' => '0'
        );

        $this->db->insert($db_ta.'.study_planning',$dataInsert);

        // Cek Attd dosen
        $dataAttd = $this->db->get_where('db_academic.attendance',
            array('SemesterID' => $dataCourse['SemesterID'],
                'ScheduleID'=> $dataCourse['ScheduleID']))->result_array();
        if(count($dataAttd)>0){
            for($i=0;$i<count($dataAttd);$i++){
                $dataAttdMhs = array(
                    'ID_Attd' => $dataAttd[$i]['ID'],
                    'NPM' => $dataCourse['NPM']
                );
                // Cek apakah ada datanya atau blm
                $dataChek = $this->db->get_where('db_academic.attendance_students',$dataAttdMhs,1)->result_array();
                if(count($dataChek)<=0){
                    $this->db->insert('db_academic.attendance_students',$dataAttdMhs);
                }

            }
        }
    }

    public function cek_deadline_paymentNPM($NPM)
    {
        error_reporting(0);
        $arr = array();
        $SemesterID = $this->m_master->caribasedprimary('db_academic.semester','Status',1);
        // cari data payment yang status = 0 desc limit 1
        $sql = 'select * from db_finance.payment where Status = "0" and NPM = ? and SemesterID = ? order by ID desc limit 1';
        $query=$this->db->query($sql, array($NPM,$SemesterID[0]['ID']))->result_array();
        if (count($query) == 1 ) {
            $ID_payment = $query[0]['ID'];
            $this->load->model('master/m_master');
            $get = $this->m_master->caribasedprimary('db_finance.payment_students','ID_payment',$ID_payment);
            for ($i=0; $i < count($get); $i++) { 
                $arr[] = array('Deadline' => $get[$i]['Deadline'],'Price' => $get[$i]['Invoice'],'Status' => $get[$i]['Status']);
            }
        }
        return $arr;

    }

    public function __getCC_GroupCalss($ProdiID){
        $dataSmtAct = $this->_getSemesterActive();

        $data = $this->db->query('SELECT s.ID, s.ClassGroup FROM db_academic.schedule_details_course sdc 
                                                LEFT JOIN db_academic.schedule s ON (s.ID = sdc.ScheduleID)
                                                WHERE s.SemesterID = "'.$dataSmtAct['ID'].'" 
                                                AND sdc.ProdiID = "'.$ProdiID.'"
                                                GROUP BY s.ID ')->result_array();


        return $data;
    }


}
