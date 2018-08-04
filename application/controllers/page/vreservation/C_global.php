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

    public function getschedule()
    {
        // get room
        $getRoom = $this->m_master->showData_array('db_academic.classroom');
        $endTime = '18';
        $getHoursNow = date('H');
        $getHoursNow = (int)$getHoursNow;
        $data['getRoom'] = $getRoom;
        $arrHours = array();

        // array list booked and requested
        $data_pass = array(
            array(
                'user'  => 'User 1',
                'start' => '10.30',
                'end'   => '12.00',
                'time'  => 90,
                'colspan' => 3,
                'agenda' => 'meeting',
                'room' => 503,
                'approved' => 1
            ),
            array(
                'user'  => 'User 1',
                'start' => '08.00',
                'end'   => '10.00',
                'time'  => 120,
                'colspan' => 4,
                'agenda' => 'meeting',
                'room' => 504,
                'approved' => 1
            ),

            array(
                'user'  => 'User 1',
                'start' => '13.00',
                'end'   => '15.30',
                'time'  => 150,
                'colspan' => 5,
                'agenda' => 'meeting',
                'room' => 503,
                'approved' => 1
            ),
            
            array(
                'user'  => 'User 1',
                'start' => '13.00',
                'end'   => '15.00',
                'time'  => 120,
                'colspan' => 4,
                'agenda' => 'meeting',
                'room' => 506,
                'approved' => 1
            ),

            array(
                'user'  => 'User 1',
                'start' => '10.30',
                'end'   => '12.00',
                'time'  => 90,
                'colspan' => 3,
                'agenda' => 'meeting',
                'room' => 505,
                'approved' => 0
            ),
            array(
                'user'  => 'User 1',
                'start' => '16.30',
                'end'   => '17.00',
                'time'  => 120,
                'colspan' => 1,
                'agenda' => 'requested',
                'room' => 503,
                'approved' => 0
            ),

            array(
                'user'  => 'User 1',
                'start' => '13.00',
                'end'   => '15.00',
                'time'  => 120,
                'colspan' => 4,
                'agenda' => 'requested',
                'room' => 507,
                'approved' => 0
            ),
            
            array(
                'user'  => 'User 1',
                'start' => '13.00',
                'end'   => '14.30',
                'time'  => 90,
                'colspan' => 3,
                'agenda' => 'requested',
                'room' => 508,
                'approved' => 0
            ),
        );

        // SORTING ASC
        usort($data_pass, function($a, $b) {
            return $a['room'] - $b['room'];
        });

        for ($i=7; $i <= $endTime; $i++) { 
            // check len
            $a = $i;
            for ($j=0; $j < 2 - strlen($i); $j++) { 
                $a = '0'.$a;
            }
            $d = $a.':30';
            $a = $a.':00';
            $arrHours[] = date("h:i a", strtotime($a));
            //$arrHours[] = date("h:i a", strtotime($d));
            if ($i != $endTime) {
                $arrHours[] = date("h:i a", strtotime($d));
            }
        }
        $data['arrHours'] = $arrHours;
        $data['data_pass'] = $data_pass;
        $content = $this->load->view($this->pathView.'schedule',$data,true);
        echo json_encode($content);
    }

}
