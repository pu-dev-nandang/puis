<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_apilpmi extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        // $this->load->model('m_api');
        // $this->load->model('m_rest');
        // $this->load->model('akademik/m_tahun_akademik');
        // $this->load->model('master/m_master');
        // $this->load->model('admin-prodi/beranda/m_home');

        $this->load->library('JWT');
        $this->load->library('google');
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "s3Cr3T-G4N";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function getInputToken2()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function is_url_exist($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code == 200){
            $status = true;
        }else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    public function GetDataContent(){
        $data_arr = $this->getInputToken2();
        $IDType = $data_arr['IDType'];
        $lang = $data_arr['LangCode'];

        $sql = 'select ci.* from db_lpmi.content_index AS ci
                WHERE ci.SegmentMenu="'.$IDType.'" ';
        $query=$this->db->query($sql, array())->result_array();
        $idindex = $query[0]['ID'];
        
        // print_r($IDType).die();
        $data = $this->db->query('SELECT c.*, l.language, ct.Label , ci.SegmentMenu 
            FROM db_lpmi.content c 
            LEFT JOIN db_lpmi.content_type ct ON (ct.ID = c.IDindex)
            LEFT JOIN db_lpmi.content_index ci ON (ci.ID = c.IDindex)
            LEFT JOIN db_lpmi.language l ON (l.ID = ct.LangID)
            WHERE c.IDindex ='.$idindex.' and c.Lang="'.$lang.'" and c.Status="Yes" Limit 6')->result();

        for ($i=0; $i < count($data); $i++) {
            $string = $data[$i]->Title;
            $replace = '-';         
            $string = strtolower($string);     
            //replace / and . with white space     
            $string = preg_replace("/[\/\.]/", " ", $string);     
            $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);     
            //remove multiple dashes or whitespaces     
            $string = preg_replace("/[\s-]+/", " ", $string);     
            //convert whitespaces and underscore to $replace     
            $string = preg_replace("/[\s_]/", $replace, $string);

            $slug=$string;
            $data[$i]->SEO_title=$slug;
            // print_r($data);die();
            // $url=url.'upload/lpmi'.$hasil[0]['File'];
            // $cek=$this->is_url_exist($url);
            //     if(!$cek){
            //         $hasil[0]['File']='http://via.placeholder.com/660x260';
            //     }
        }
        // print_r($data);die();
        return print_r(json_encode($data));
        // return $data;
    }

    public function GetDataContentAll(){
        $data_arr = $this->getInputToken2();
        $IDType = $data_arr['IDType'];
        $lang = $data_arr['LangCode'];

        $sql = 'select ci.* from db_lpmi.content_index AS ci
                WHERE ci.SegmentMenu="'.$IDType.'" ';
        $query=$this->db->query($sql, array())->result_array();
        $idindex = $query[0]['ID'];
        
        // print_r($IDType).die();
        $data = $this->db->query('SELECT c.*, l.language, ct.Label , ci.SegmentMenu , e.Name
            FROM db_lpmi.content c 
            LEFT JOIN db_lpmi.content_type ct ON (ct.ID = c.IDindex)
            LEFT JOIN db_lpmi.content_index ci ON (ci.ID = c.IDindex)
            LEFT JOIN db_lpmi.language l ON (l.ID = ct.LangID)
            LEFT JOIN db_employees.employees e ON (e.NIP=c.CreateBy)
            WHERE c.IDindex ='.$idindex.' and c.Lang="'.$lang.'" and c.Status="Yes" ')->result_array();

        for ($i=0; $i < count($data); $i++) {
            $string=$data[$i]['Title'];
            $replace = '-';         
            $string = strtolower($string);     
            //replace / and . with white space     
            $string = preg_replace("/[\/\.]/", " ", $string);     
            $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);     
            //remove multiple dashes or whitespaces     
            $string = preg_replace("/[\s-]+/", " ", $string);     
            //convert whitespaces and underscore to $replace     
            $string = preg_replace("/[\s_]/", $replace, $string);

            $slug=$string;
            $data[$i]['SEO_title']=$slug;
            // print_r($data);die();
            // $url=window.location.href.'upload/lpmi'.$hasil[0]['File'];
            // $cek=$this->is_url_exist($url);
            //     if(!$cek){
            //         $hasil[0]['Images']='default.png';
            //     }
        }
        // print_r($data);die();
        return print_r(json_encode($data));
    }

    public function GetDataContentDetails(){
        $data_arr = $this->getInputToken2();
        $IDType = $data_arr['IDType'];
        $lang = $data_arr['LangCode'];
        $ID = $data_arr['ID'];

        $sql = 'select ci.* from db_lpmi.content_index AS ci
                WHERE ci.SegmentMenu="'.$IDType.'" ';
        $query=$this->db->query($sql, array())->result_array();
        $idindex = $query[0]['ID'];
        
        // print_r($IDType).die();
        $data = $this->db->query('SELECT c.*, l.language, ct.Label , ci.SegmentMenu , e.Name
            FROM db_lpmi.content c 
            LEFT JOIN db_lpmi.content_type ct ON (ct.ID = c.IDindex)
            LEFT JOIN db_lpmi.content_index ci ON (ci.ID = c.IDindex)
            LEFT JOIN db_lpmi.language l ON (l.ID = ct.LangID)
            LEFT JOIN db_employees.employees e ON (e.NIP=c.CreateBy)
            WHERE c.ID="'.$ID.'" and c.IDindex ='.$idindex.' and c.Lang="'.$lang.'" and c.Status="Yes" ')->result_array();
        return print_r(json_encode($data));
    }

    public function GetDataCommittee(){
        $data_arr = $this->getInputToken2(); 

        $sql = 'select * from db_employees.position ';
        $query=$this->db->query($sql, array())->result_array();
        $idindex = $query[0]['ID'];
        // print_r($IDType).die();
        $data = $this->db->query('SELECT * FROM  db_employees.employees AS e
                LEFT JOIN db_employees.position p on SUBSTRING_INDEX(SUBSTRING_INDEX(e.PositionMain, ".", 2), ".", -1) = p.ID
                WHERE e.PositionMain LIKE "%3.%" AND e.PositionMain NOT LIKE "%13.%" AND e.PositionMain NOT LIKE "%33.%" OR e.PositionOther1 like "%3.%" AND e.PositionOther1 NOT LIKE "%13.%" AND e.PositionOther1 NOT LIKE "%33.%" OR e.PositionOther2 like "%3.%" AND e.PositionOther2 NOT LIKE "%13.%" AND e.PositionOther2 NOT LIKE "%33.%" OR e.PositionOther3 like "%3.%" AND e.PositionOther3 NOT LIKE "%13.%" AND e.PositionOther3 NOT LIKE "%33.%"
                ORDER BY p.ID ASC')->result_array();
        return print_r(json_encode($data));
    }


    public function GetDataContentKnowledge(){
        $data_arr = $this->getInputToken2();
        $IDType = $data_arr['IDType'];
        $lang = $data_arr['LangCode'];
        $IDCat = $data_arr['IDCat'];
        // print_r($IDCat);
        $sql = 'select ci.* from db_lpmi.content_index AS ci
                WHERE ci.SegmentMenu="'.$IDType.'" ';
        $query=$this->db->query($sql, array())->result_array();
        $idindex = $query[0]['ID'];
        
        // print_r($IDType).die();
        if($IDCat==''){
            // print_r('kos');
            $data = $this->db->query('SELECT c.*, l.language, ct.Label , ci.SegmentMenu
            FROM db_lpmi.content c 
            LEFT JOIN db_lpmi.content_type ct ON (ct.ID = c.IDindex)
            LEFT JOIN db_lpmi.content_index ci ON (ci.ID = c.IDindex)
            LEFT JOIN db_lpmi.language l ON (l.ID = ct.LangID)
            LEFT JOIN db_lpmi.category cat ON (c.IDCat = cat.ID)
            WHERE c.IDindex ='.$idindex.' and c.Lang="'.$lang.'" and c.Status="Yes" ')->result();
        }else{
            // print_r('da');
            $data = $this->db->query('SELECT c.*, l.language, ct.Label , ci.SegmentMenu
            FROM db_lpmi.content c 
            LEFT JOIN db_lpmi.content_type ct ON (ct.ID = c.IDindex)
            LEFT JOIN db_lpmi.content_index ci ON (ci.ID = c.IDindex)
            LEFT JOIN db_lpmi.language l ON (l.ID = ct.LangID)
            LEFT JOIN db_lpmi.category cat ON (c.IDCat = cat.ID)
            WHERE c.IDindex ='.$idindex.' and c.Lang="'.$lang.'" and c.Status="Yes" and c.IDCat='.$IDCat.'')->result();
        }
        // print_r($data);die();
        

        for ($i=0; $i < count($data); $i++) {
            $string = $data[$i]->Title;
            $replace = '-';         
            $string = strtolower($string);     
            //replace / and . with white space     
            $string = preg_replace("/[\/\.]/", " ", $string);     
            $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);     
            //remove multiple dashes or whitespaces     
            $string = preg_replace("/[\s-]+/", " ", $string);     
            //convert whitespaces and underscore to $replace     
            $string = preg_replace("/[\s_]/", $replace, $string);

            $slug=$string;
            $data[$i]->SEO_title=$slug;
            // print_r($data);die();
            // $url=url.'upload/lpmi'.$hasil[0]['File'];
            // $cek=$this->is_url_exist($url);
            //     if(!$cek){
            //         $hasil[0]['File']='http://via.placeholder.com/660x260';
            //     }
        }
        // print_r($data);die();
        return print_r(json_encode($data));
        // return $data;
    }


}
