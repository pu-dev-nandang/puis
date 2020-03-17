<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_onlineclass extends CI_Model {


    function __construct()
    {
        parent::__construct();
    }

    function getMonitoringAttd($data_arr){

        $ScheduleID = $data_arr['ScheduleID'];
        $Session = $data_arr['Session'];

        // Get Dosen



    }

}