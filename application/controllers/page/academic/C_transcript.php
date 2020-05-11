<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_transcript extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->load->model('akademik/m_tahun_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_transcript($page){
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$data['department'].'/transcript/menu_transcript',$data,true);
        parent::template($content);
    }

    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/transcript/transcript_list_student',$data,true);
        $this->menu_transcript($page);
    }

    public function ijazah(){

//        $this->load->library('encrypt');
//
//        $msg = 'Ini Pesan rahasia lho! Jangan bilang siapa-siapa ya!'; //Plain text
//        $key = 'recodeku.blospot.com123456789123'; //Key 32 character
//        //default menggunakan MCRYPT_RIJNDAEL_256
//        $hasil_enkripsi = $this->encrypt->encode($msg, $key);
//        $hasil_dekripsi = $this->encrypt->decode($hasil_enkripsi, $key);
//        echo "Pesan yang mau dienkripsi: ".$msg."<br/><br/>";
//        echo "Hasil enkripsi: ".$hasil_enkripsi."<br/><br/>";
//        echo "Hasil dekripsi: ".$hasil_dekripsi."<br/><br/>";
//
//        exit();

        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/transcript/ijazah',$data,true);
        $this->menu_transcript($page);
    }

    public function setting_transcript(){
        $data['TempTranscript'] = $this->db->get('db_academic.setting_temp_transcript')->result_array()[0];
        $data['Transcript'] = $this->db->get('db_academic.setting_transcript')->result_array()[0];
        $data['Graduation'] = $this->db->get('db_academic.graduation')->result_array();
        $data['Education'] = $this->db->get('db_academic.education_level')->result_array();

        $data['ProgramStudy'] = $this->db->query('SELECT a.ID, a.Name, a.NameEng, a.NoSKBANPT, a.SKBANPTDate, 
                                                                                    b.Name AS NameLevel, c.Name AS NameFak, 
                                                                                    d.Label as accreditation,
                                                                                    d.ID AS accreditationID
                                                                                    FROM db_academic.program_study AS a
                                                                                    LEFT JOIN db_academic.education_level AS b ON (a.EducationLevelID = b.ID)
                                                                                    LEFT JOIN db_academic.accreditation as d ON (a.accreditationID =  d.ID)
                                                                                    LEFT JOIN db_academic.faculty AS c ON (a.FacultyID = c.FacultyID)
                                                                                    where a.status =1
                                                                                    ORDER BY a.ID ASC ')->result_array();

        //$data['ProgramStudy'] = $this->db->get('db_academic.program_study')->result_array();
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/transcript/setting_transcript',$data,true);
        $this->menu_transcript($page);
    }

}
