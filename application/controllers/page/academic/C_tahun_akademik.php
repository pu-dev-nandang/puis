<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_tahun_akademik extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->session->set_userdata('departement_nav', 'academic');
        $this->load->model('akademik/m_tahun_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function tesdb(){

        $this->load->dbforge();
        $db_new = 'ta_2018';

        if ($this->dbforge->create_database($db_new)) {

            // Student
            $this->db->query('CREATE TABLE `students` (
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
                              PRIMARY KEY (`ID`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=latin1;');

            // study_planning
            $this->db->query('CREATE TABLE `study_planning` (
                                  `ID` int(11) NOT NULL AUTO_INCREMENT,
                                  `SemesterID` int(11) DEFAULT NULL,
                                  `MhswID` int(11) DEFAULT NULL,
                                  `NPM` varchar(30) NOT NULL,
                                  `ScheduleID` int(11) NOT NULL,
                                  `TypeSchedule` enum(\'Br\',\'Ul\') DEFAULT NULL,
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
                                  `Approval` enum(\'0\',\'1\') DEFAULT NULL,
                                  `StatusSystem` enum(\'1\',\'0\') DEFAULT NULL COMMENT \'0 = Siak Lama , 1 = Baru\',
                                  `Glue` varchar(45) DEFAULT NULL,
                                  `Status` enum(\'0\',\'1\') DEFAULT NULL,
                                  PRIMARY KEY (`ID`)
                                ) ENGINE=InnoDB AUTO_INCREMENT=3703 DEFAULT CHARSET=latin1');


        }

    }

    public function tahun_akademik()
    {
        $data['department'] = parent::__getDepartement();

//        print_r($data['kurikulum']);
        $content = $this->load->view('page/'.$data['department'].'/tahunakademik/tahun_akademik',$data,true);
        $this->temp($content);
    }

    public function tahun_akademik_table(){
        $data['department'] = parent::__getDepartement();
        $data['semester'] = $this->m_tahun_akademik->__getSemester();
        $this->load->view('page/'.$data['department'].'/tahunakademik/tahun_akademik_table',$data);
    }

    public function page_detail_tahun_akademik(){
        $data['department'] = parent::__getDepartement();
        $data['ID'] = $this->input->post('ID');
        $this->load->view('page/'.$data['department'].'/tahunakademik/detail_tahun_akademik',$data);
    }

    public function tahun_akademik_detail($detail)
    {
        $data['department'] = parent::__getDepartement();
        $data['semester'] = $detail;
//        print_r($data['kurikulum']);
        $content = $this->load->view('page/'.$data['department'].'/tahun_akademik_detail',$data,true);
        $this->temp($content);
    }

    public function tahun_akademik_detail2(){
        $data_json = $this->input->post('data_json');
        $data['department'] = parent::__getDepartement();

        $data['data_json'] = $data_json;

        $this->load->view('page/'.$data['department'].'/tahun_akademik_detail',$data);
    }

    public function tahun_akademik_detail_date(){
        $data_json = $this->input->post('data_json');
        $data['department'] = parent::__getDepartement();

        $data['data_json'] = $data_json;

        $this->load->view('page/'.$data['department'].'/tahun_akademik_detail_date',$data);
    }


    // ==== Modal ====
    public function modal_tahun_akademik(){

        $data['action'] = $this->input->post('action');
        $data['id'] = $this->input->post('id');
        $data['department'] = parent::__getDepartement();
        $data['tahun'] = '';
        $data['itemTahunAkademik'] = [];

        $data['ProgramCampusID'] = '';
        $data['Year'] = '';
        $data['Code'] = '';
        if($data['action']!='add'){
            $data['itemTahunAkademik'] = $this->m_tahun_akademik->__getDataTahunAkademik($data['id']);
            if(count($data['itemTahunAkademik'])>0){
                $exp = explode(' ',$data['itemTahunAkademik'][0]['Name']);
                $data['tahun'] = trim($exp[0]);
                $data['ProgramCampusID'] = $data['itemTahunAkademik'][0]['ProgramCampusID'];
                $data['Year'] = substr($data['itemTahunAkademik'][0]['Year'],-1);
                $data['Code'] = substr($data['itemTahunAkademik'][0]['Code'],-1);
//                $data['semester'] = substr($data['itemTahunAkademik'][0]['YearCode'],-1);
            }
        }

//        print_r($data['itemTahunAkademik']);

        $this->load->view('page/'.$data['department'].'/tahunakademik/modal_tahun_akademik',$data);

    }

}
