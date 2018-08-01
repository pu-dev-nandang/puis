<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_global extends Vreservation_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
        $this->load->library('JWT');
        $this->load->model('master/m_master');
    }


    public function index()
    {
        $content = $this->load->view($this->pathView.'dashboard/dashboard','',true);
        $this->temp($content);
    }

    public function getroom()
    {
        $arr = array();
        $getData = $this->m_master->showData_array('db_academic.classroom');
        $a = 0;
        for ($i=0; $i < count($getData); $i++) { 
            $room = $getData[$i]['Room'];
            $status = '<span class="label label-success">Available</span>';
            $BookedBy = '-';
            $Ends = '-';
            if ($i == $a) {
                $status = '<span class="label label-danger">Booked</span>';
                $BookedBy = 'Alhadi Rahman';
                $Ends = date('Y-m-d H:i:s', time() + 2 * 3600);
                $a = $a + 2;
            }    
            $arr[] = array(
                'room' => $room,
                'status' => $status,
                'BookedBy' => $BookedBy,
                'Ends' => $Ends
            );
        }
        echo json_encode($arr);

    }

}
