
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Nandang
 * Date: 12/20/2017
 * Time: 1:41 PM
 */
include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_pengawas_ujian extends CI_Controller {

    function __construct()
    {
        parent::__construct();


        // define config Virtual Account
        if (!$this->session->userdata('loggedIn')) {
            redirect(base_url());
        }

        $this->load->library('JWT');
        $this->load->library('google');
        $this->load->model('m_auth');
        $this->load->model('m_rest');
        date_default_timezone_set("Asia/Jakarta");

    }

    private function getInputToken($token)
    {
        $key = "s3Cr3T-G4N";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function temp($content){
        $data['include'] = $this->load->view('template/include','',true);
        $data['content'] = $content;
        $this->load->view('template/pengawas_ujian',$data);
    }

    public function invigilator(){
        $content = $this->load->view('page/invigilator/invigilator','',true);
        $this->temp($content);
    }

    public function detail_exam($token){

        $dataToken = $this->getInputToken($token);

        $dataEx = $this->db->get_where('db_academic.exam',
            array('ID' => $dataToken['ExamID']))->result_array();

        if(count($dataEx)>0){
            $ExamOnline = $dataEx[0];
            $timeStart = strtotime($ExamOnline['ExamStart']);
            $timeEnd = strtotime($ExamOnline['ExamEnd']);
            $time1 = strtotime($this->m_rest->getTimeNow());

            $data['dataToken'] = $dataToken;
            $data['ExamOnline'] = $ExamOnline;
            $data['viewPageExam'] = ($timeStart<=$time1 && $time1<=$timeEnd) ? 1 : 0;

            $content = $this->load->view('page/invigilator/detail_exam',$data,true);
            $this->temp($content);
        } else {
            echo "Not authorized";
        }


    }




}
