<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_ticketing extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('ticketing/m_general');
        $this->load->library('JWT');
    }

    public function create_ticketing($dataToken)
    {
        $rs = ['status' => 0,'msg' => '','callback'=>[]];
        $dataSave = json_decode(json_encode($dataToken['data']),true);
        // Get Number Ticket
        $DepartmentAbbr = $dataToken['DepartmentAbbr'];
        $NoTicket = $this->generate_code_ticketing($dataSave['RequestedBy'],$DepartmentAbbr);
        $dataSave['NoTicket'] = $NoTicket;
        $dataSave['RequestedAt'] = date('Y-m-d H:i:s');
        // file upload to nas
        if (array_key_exists('Files', $_FILES)) {
            $uploadNas = $this->Ticketing_uploadNas($NoTicket);
            $dataSave['Files'] = $uploadNas;
        }

        try {
            $dataSave['UpdatedBy'] = $dataSave['RequestedBy'];
            $dataSave['UpdatedAt'] = date('Y-m-d H:i:s');
            $this->db->insert('db_ticketing.ticket',$dataSave);
            $insert_id = $this->db->insert_id();
            $this->__after_create_ticketing($insert_id,$dataSave);
            // get name employees
            $G_emp = $this->m_master->caribasedprimary('db_employees.employees','NIP',$dataSave['RequestedBy']);
            $dataSave['NameRequested'] = $G_emp[0]['Name'];
            $rs['status'] = 1;
            $rs['callback'] = $dataSave;
        } catch (Exception $e) {
            $rs['msg'] = $e;
        }
        return $rs;
    }

    private function __after_create_ticketing($TicketID,$dataSaveTicket){
        $G_category = $this->m_master->caribasedprimary('db_ticketing.category','ID',$dataSaveTicket['CategoryID']);
        $DepartmentReceivedID = $G_category[0]['DepartmentID'];
        $MessageReceived = $dataSaveTicket['Message'];
        $Flag = '0';
        $ReceivedStatus = '0';
        $dataSave = [
           'DepartmentReceivedID' => $G_category[0]['DepartmentID'],
           'MessageReceived' => $dataSaveTicket['Message'],
           'CategoryReceivedID' => $dataSaveTicket['CategoryID'],
           'Flag' => '0',
           'ReceivedStatus' => '0',
           'TicketID' => $TicketID,
           'CreatedBy' => $dataSaveTicket['RequestedBy'],
           'CreatedAt' => date('Y-m-d H:i:s'),
        ];

        $this->db->insert('db_ticketing.received',$dataSave);
    }

    public function Ticketing_uploadNas($filename)
    {
        $headerOrigin = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? serverRoot : "http://localhost";
        $path = 'ticketing';
        $uploadNas = $this->m_master->UploadManyFilesToNas($headerOrigin,$filename,'Files',$path,'array');
        return $uploadNas[0];
    }

    public function send_notification_ticketing($array)
    {
        $data = array(
            'auth' => 's3Cr3T-G4N',
            'Logging' => array(
                            'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>E-ticketing by '.$array['NameRequested'],
                            'Description' => $array['Description'],
                            'URLDirect' => $array['URLDirect'],
                            'CreatedBy' => $array['CreatedBy'],
                          ),
            'To' => array(
                      'NIP' => $array['To'],
                    ),
            'Email' => $array['NeedEmail'],
        );

        $url = url_pas.'rest2/__send_notif_browser';
        $token = $this->jwt->encode($data,"UAP)(*");
        $this->m_master->apiservertoserver($url,$token);
    }

    private function generate_code_ticketing($Request,$Abbr=null)
    {
        /* method
           Code : IT-19000001
           IT : Abbreviation
           19 : Year
           0000001 : Increment
        */
        $NIP = $Request;
        $GetAbbrEmployees = $this->m_master->SearchEmployeesByNIP($NIP);
        if ($Abbr == null) {
            $Abbr = $GetAbbrEmployees[0]['DivAbbr'];
            $DivisionID = $GetAbbrEmployees[0]['DivisionID'];
            if ($DivisionID == 15 || $DivisionID == 14 ) {
                $ProdiID = $GetAbbrEmployees[0]['ProdiID'];
                $G_prodi = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ProdiID);
                $Abbr = $G_prodi[0]['Code'];
            }
            elseif ($DivisionID == 34) {
                $sql_get = 'select * from db_academic.faculty
                            where AdminID = "'.$NIP.'" or NIP = "'.$NIP.'" or Laboran = "'.$NIP.'"
                            ';
                $querysql_get = $this->db->query($sql_get,array())->result_array();
                if (count($querysql_get)>0) {
                   $Abbr = $querysql_get[0]['Abbr'];
                }
            }
        }


        $Code = '';
        $Year = date('Y');
        $YearSubStr = substr($Year, 2,2);
        $MaxLengthINC = 5;

        $sql = 'select * from db_ticketing.ticket
                where Year(RequestedAt) = '.$Year.'
                order by ID desc
                limit 1';
        $query = $this->db->query($sql,array())->result_array();
        if (count($query) == 1) {
           $LastCode = $query[0]['NoTicket'];
           $explode = explode('-', $LastCode);
           $CodeNumber = (int) substr($explode[1], 2,(strlen($explode[1])-2) );
           $CodeNumber = $CodeNumber + 1;
           $strlen = strlen($CodeNumber);
           $strINC = $CodeNumber;
           for ($i=0; $i < $MaxLengthINC - $strlen; $i++) {
               $strINC = '0'.$strINC;
           }
           $strINC = $YearSubStr.$strINC;
           $Code = $Abbr.'-'.$strINC;
        }
        else
        {
            $CodeNumber = 1;
            $strlen = strlen($CodeNumber);
            $strINC = $CodeNumber;
            for ($i=0; $i < $MaxLengthINC - $strlen; $i++) {
                $strINC = '0'.$strINC;
            }
            $strINC = $YearSubStr.$strINC;

            $Code = $Abbr.'-'.$strINC;
        }

        return $Code;

    }

    public function getAdminTicketing($CategoryID)
    {
        $rs = [];
        $sql =  'select a.DepartmentID,b.NIP from db_ticketing.category as a
                 join db_ticketing.admin_register as b on a.DepartmentID = b.DepartmentID
                 where a.ID = '.$CategoryID.'
                 ';
        $query = $this->db->query($sql,array())->result_array();
        for ($i=0; $i < count($query); $i++) {
            $rs[] = $query[$i]['NIP'];
        }

        return $rs;
    }

    public function rest_open_ticket($dataToken)
    {
        $rs = [];
        $Addwhere = '';
        if (array_key_exists('DepartmentID', $dataToken)) {
           $Addwhere .= ' and (ca.DepartmentID = "'.$dataToken['DepartmentID'].'" or a.DepartmentTicketID = "'.$dataToken['DepartmentID'].'" )';
        }

        $NIP = $dataToken['NIP'];
        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";
        $sql = 'select a.DepartmentTicketID,a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo,qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.ID,ca.Descriptions as CategoryDescriptions,a.TicketStatus,
                a.RequestedBy
                from db_ticketing.ticket as a
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                join db_employees.employees as b on a.RequestedBy = b.NIP
                where a.TicketStatus = 1 and DATE_FORMAT(a.RequestedAt,"%Y-%m-%d") = CURDATE()
                '.$Addwhere.'
                and (select count(*) as total from db_ticketing.received where TicketID = a.ID) = 1
                order by a.ID asc
                ';
        $query = $this->db->query($sql,array())->result_array();
        $rs['count'] = count($query);
        for ($i=0; $i < $rs['count']; $i++) {

            $query[$i]['data_received'] = [];

            if ($query[$i]['Files'] != '' && $query[$i]['Files'] != null) {
                $token = $this->jwt->encode($query[$i]['Files'],"UAP)(*");
                $url = url_files."fileGetAnyToken/".$token;
                $query[$i]['Files'] = $url;
            }

            // add foto
            if ($query[$i]['Photo'] != '' && $query[$i]['Photo'] != null) {
                $url = url_pas."uploads/employees/".$query[$i]['Photo'];
                $query[$i]['Photo'] = $url;
            }

            $query[$i]['RequestedAt'] = $this->__set_tgl_ticket($query[$i]['RequestedAt']);
            $data_received = $this->getDataReceived_worker([ 'TicketID' => $query[$i]['ID'],'SetAction' => 1 ]);
            $query[$i]['setTicket'] = $this->__setTicket_action_progress($query[$i]['NoTicket'],$NIP,$dataToken['DepartmentID'],$data_received,$query[$i]['TicketStatus']);
            // $query[$i]['setTicket'] = ($this->m_general->auth($query[$i]['DepartmentIDDestination'],$NIP)) ? 'write' : '';
            $token = $this->jwt->encode($query[$i],"UAP)(*");
            $query[$i]['token'] =  $token;
        }
        $rs['data'] = $query;
        return $rs;
    }

    public function rest_pending_ticket($dataToken)
    {
        $rs = [];
        $Addwhere = '';
        if (array_key_exists('DepartmentID', $dataToken)) {
           $Addwhere .= ' and (ca.DepartmentID = "'.$dataToken['DepartmentID'].'" or a.DepartmentTicketID = "'.$dataToken['DepartmentID'].'" )';
        }
        $NIP = $dataToken['NIP'];
        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";
        $sql = 'select a.DepartmentTicketID,a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo,qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.ID,ca.Descriptions as CategoryDescriptions,a.TicketStatus,
                a.RequestedBy
                from db_ticketing.ticket as a
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                join db_employees.employees as b on a.RequestedBy = b.NIP
                where a.TicketStatus = 1 and DATE_FORMAT(a.RequestedAt,"%Y-%m-%d") < CURDATE()
                '.$Addwhere.'
                and (select count(*) as total from db_ticketing.received where TicketID = a.ID) = 1
                order by a.ID asc
                ';
        $query = $this->db->query($sql,array())->result_array();
        $rs['count'] = count($query);
        for ($i=0; $i < $rs['count']; $i++) {

            $query[$i]['data_received'] = [];

            if ($query[$i]['Files'] != '' && $query[$i]['Files'] != null) {
                $token = $this->jwt->encode($query[$i]['Files'],"UAP)(*");
                $url = url_files."fileGetAnyToken/".$token;
                $query[$i]['Files'] = $url;
            }

            // add foto
            if ($query[$i]['Photo'] != '' && $query[$i]['Photo'] != null) {
                $url = url_pas."uploads/employees/".$query[$i]['Photo'];
                $query[$i]['Photo'] = $url;
            }

            $query[$i]['RequestedAt'] = $this->__set_tgl_ticket($query[$i]['RequestedAt']);
            $data_received = $this->getDataReceived_worker([ 'TicketID' => $query[$i]['ID'],'SetAction' => 1 ]);
            $query[$i]['setTicket'] = $this->__setTicket_action_progress($query[$i]['NoTicket'],$NIP,$dataToken['DepartmentID'],$data_received,$query[$i]['TicketStatus']);
            // $query[$i]['setTicket'] = ($this->m_general->auth($query[$i]['DepartmentIDDestination'],$NIP)) ? 'write' : '';
            $token = $this->jwt->encode($query[$i],"UAP)(*");
            $query[$i]['token'] =  $token;
        }
        $rs['data'] = $query;
        return $rs;
    }

    public function rest_progress_ticket($dataToken,$customwhere = '')
    {
        $rs = [];
        $Addwhere = '';
        if (array_key_exists('DepartmentID', $dataToken)) {
           $Addwhere .= ' and ( a.DepartmentTicketID = "'.$dataToken['DepartmentID'].'"
                                or a.ID in (
                                    select a.TicketID from db_ticketing.received as a
                                    join db_ticketing.category as b on a.CategoryReceivedID = b.ID
                                    '.$this->m_general->QueryDepartmentJoin('b.DepartmentID','qdp').'
                                    where a.SetAction = "1" and qdp.ID = "'.$dataToken['DepartmentID'].'"
                                    and a.ReceivedStatus = "0"
                                )
            )';
        }
        $NIP = $dataToken['NIP'];
        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";
        $sql = 'select a.DepartmentTicketID,a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo,a.ID,ca.Descriptions as CategoryDescriptions,a.DepartmentTicketID,qdx.NameDepartment as NameDepartmentTicket,
                qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.TicketStatus,a.RequestedBy
                from db_ticketing.ticket as a
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                join db_employees.employees as b on a.RequestedBy = b.NIP
                '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID','qdj').'
                where a.TicketStatus = 2
                '.$Addwhere.$customwhere.'
                order by a.ID asc
                ';

        $query = $this->db->query($sql,array())->result_array();
        $rs['count'] = count($query);
        for ($i=0; $i < $rs['count']; $i++) {
            $data_received = $this->getDataReceived_worker([ 'TicketID' => $query[$i]['ID'] ]);

            $query[$i]['data_received'] = $data_received;

            if ($query[$i]['Files'] != '' && $query[$i]['Files'] != null) {
                $token = $this->jwt->encode($query[$i]['Files'],"UAP)(*");
                $url = url_files."fileGetAnyToken/".$token;
                $query[$i]['Files'] = $url;
            }

            // add foto
            if ($query[$i]['Photo'] != '' && $query[$i]['Photo'] != null) {
                $url = url_pas."uploads/employees/".$query[$i]['Photo'];
                $query[$i]['Photo'] = $url;
            }

            $query[$i]['RequestedAt'] = $this->__set_tgl_ticket($query[$i]['RequestedAt']);
            $query[$i]['setTicket'] = $this->__setTicket_action_progress($query[$i]['NoTicket'],$NIP,$dataToken['DepartmentID'],$data_received,$query[$i]['TicketStatus']);
            $token = $this->jwt->encode($query[$i],"UAP)(*");
            $query[$i]['token'] =  $token;
        }
        $rs['data'] = $query;
        return $rs;
    }

    public function rest_close_ticket($dataToken,$customwhere = ''){
        $rs = [];
        $Addwhere = '';
        if (array_key_exists('DepartmentID', $dataToken)) {
           $Addwhere .= ' and ( a.DepartmentTicketID = "'.$dataToken['DepartmentID'].'"
                                or a.ID in (
                                    select a.TicketID from db_ticketing.received as a
                                    join db_ticketing.category as b on a.CategoryReceivedID = b.ID
                                    '.$this->m_general->QueryDepartmentJoin('b.DepartmentID','qdp').'
                                    where qdp.ID = "'.$dataToken['DepartmentID'].'"
                                )
            )';
        }
        $NIP = $dataToken['NIP'];
        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";
        $sql = 'select a.DepartmentTicketID,a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo,a.ID,ca.Descriptions as CategoryDescriptions,a.DepartmentTicketID,qdx.NameDepartment as NameDepartmentTicket,
                qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.TicketStatus,a.RequestedBy
                from db_ticketing.ticket as a
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                join db_employees.employees as b on a.RequestedBy = b.NIP
                '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID','qdj').'
                where a.TicketStatus IN(3,4) and DATE_FORMAT(a.TicketClosedAt,"%Y-%m-%d") = CURDATE()
                '.$Addwhere.$customwhere.'
                order by a.ID asc
                ';

        $query = $this->db->query($sql,array())->result_array();
        $rs['count'] = count($query);
        for ($i=0; $i < $rs['count']; $i++) {
            $data_received = $this->getDataReceived_worker([ 'TicketID' => $query[$i]['ID'] ]);

            $query[$i]['data_received'] = $data_received;

            if ($query[$i]['Files'] != '' && $query[$i]['Files'] != null) {
                $token = $this->jwt->encode($query[$i]['Files'],"UAP)(*");
                $url = url_files."fileGetAnyToken/".$token;
                $query[$i]['Files'] = $url;
            }

            // add foto
            if ($query[$i]['Photo'] != '' && $query[$i]['Photo'] != null) {
                $url = url_pas."uploads/employees/".$query[$i]['Photo'];
                $query[$i]['Photo'] = $url;
            }

            $query[$i]['RequestedAt'] = $this->__set_tgl_ticket($query[$i]['RequestedAt']);
            $query[$i]['setTicket'] = $this->__setTicket_action_progress($query[$i]['NoTicket'],$NIP,$dataToken['DepartmentID'],$data_received,$query[$i]['TicketStatus']);
            $token = $this->jwt->encode($query[$i],"UAP)(*");
            $query[$i]['token'] =  $token;
        }
        $rs['data'] = $query;
        return $rs;
    }

    private function __set_tgl_ticket($date_field){
        $DateRequest = date('d M Y', strtotime($date_field));
        $TimeRequest = date('H:i', strtotime($date_field));
        $date_field = $DateRequest.' '.$TimeRequest;
        return $date_field;
    }

    private function __set_datetime_modal_tracking($date_field){
         $DateRequest = date('M d, Y', strtotime($date_field));
         $TimeRequest = '<span>'.date('H:i', strtotime($date_field)).'</span>';
         $date_field = $DateRequest.$TimeRequest;
         return $date_field;
    }

    private function __setTicket_action_progress($NoTicket,$NIP,$DepartmentID,$data_received,$TicketStatus){
        $rs = '';
        $first = (count($data_received) == 1 && $TicketStatus == 1) ? 'yes' : 'no';
        $auth_action_tickets = $this->auth_action_tickets($NoTicket,$NIP,$DepartmentID,$first);
        if ($auth_action_tickets['bool']) {
            $rs = 'write';
        }

        return $rs;
    }

    public function getDataTicketBy($arr,$customwhere=''){
        // array by ID or NoTicket

        $strWhere = '';
        foreach ($arr as $key => $value) {
            $AndOrWhere = ($strWhere == '') ? 'where ' : ' and ';
            $strWhere .= $AndOrWhere.'a.'.$key.' = "'.$value.'"';
        }

        if ($customwhere != '') {
           $AndOrWhere = ($strWhere == '') ? 'where ' : ' and ';
           $customwhere = $AndOrWhere.' '.$customwhere;
       }

        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";
        $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo as PhotoRequested,qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.CategoryID,ca.Descriptions as CategoryDescriptions,a.ID
                from db_ticketing.ticket as a
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                join db_employees.employees as b on a.RequestedBy = b.NIP
                '.$strWhere.$customwhere.'
                order by a.ID desc
                ';
        $query = $this->db->query($sql,array())->result_array();
        for ($i=0; $i < count($query); $i++) {
           $DateRequest = date('d M Y', strtotime($query[$i]['RequestedAt']));
           $TimeRequest = date('H:i', strtotime($query[$i]['RequestedAt']));
           $query[$i]['RequestedAt'] = $DateRequest.' '.$TimeRequest;
           if ($query[$i]['Files'] != '' && $query[$i]['Files'] != null) {
               $token = $this->jwt->encode($query[$i]['Files'],"UAP)(*");
               $url = url_files."fileGetAnyToken/".$token;
               $query[$i]['Files'] = $url;
           }
        }
        return $query;
    }

    public function auth_action_tickets($NoTicket,$NIP,$DepartmentID,$first){
        $rs = ['bool'=>false,'callback' => [] ];
        $arr_where = [
            'NoTicket' => $NoTicket,
            'TicketStatus' => ($first == 'yes') ? 1 : 2,
        ];
        $G_dt = $this->getDataTicketBy($arr_where);
        if (count($G_dt) > 0) {
            $DepartmentIDDestination = $this->__get_department_auth_action($G_dt[0]['ID'],$DepartmentID,$first);
            if (array_key_exists('Status', $DepartmentIDDestination)) {
                if ($first =='yes') {
                    if ($this->m_general->auth($DepartmentID,$NIP)) {
                        $rs['bool'] = true;
                        $rs['callback'] = $DepartmentIDDestination;
                    }
                }
                else
                {
                    $auth_action_progress = $this->auth_action_progress($DepartmentIDDestination,$NIP);
                    $DepartmentIDDestination['Detail'] = $auth_action_progress;
                    $rs['bool'] = true;
                    $rs['callback'] = $DepartmentIDDestination;
                }

            }

        }
        return $rs;
    }

    private function auth_action_progress($DataReceived_auth_action,$NIP)
    {
        $rs = [
            'Admin' => false,
            'Worker' => false,
        ];

        $DepartmentID = $DataReceived_auth_action['DepartmentID'];
        $rs['Admin'] = $this->m_general->auth($DepartmentID,$NIP);
        $DataReceived_Details = $DataReceived_auth_action['Detail']['DataReceived_Details'];
        $bool = false;
        for ($i=0; $i < count($DataReceived_Details); $i++) {
            if ($NIP == $DataReceived_Details[$i]['NIP']) {
              $bool = true;
              break;
            }
        }
        $rs['Worker'] = $bool;
        return $rs;
    }

    private function __get_department_auth_action($TicketID,$DepartmentID,$first){
        $rs = [];
        $arr_where = [
            'TicketID' => $TicketID,
        ];
        $query = $this->getDataReceived_worker($arr_where);
        if ($first =='yes') {
           if (count($query) == 1) {
              for ($i=0; $i < count($query); $i++) {
                  if ($DepartmentID == $query[$i]['DepartmentIDDestination']) {
                      $temp = [
                          'Status' => ($query[$i]['ReceivedStatus'] == 1) ? 'closed' : 'open',
                          'DepartmentID' => $query[$i]['DepartmentIDDestination'],
                          'SetAction' => ($query[$i]['SetAction'] == 0) ? 'View' : 'Action',
                      ];

                      $rs = $temp;
                      break;
                  }
              }
           }
        }
        else
        {
            for ($i=0; $i < count($query); $i++) {
                if ($DepartmentID == $query[$i]['DepartmentIDDestination']) {
                    $temp = [
                        'Status' => ($query[$i]['ReceivedStatus'] == 1) ? 'closed' : 'open',
                        'DepartmentID' => $query[$i]['DepartmentIDDestination'],
                        'SetAction' => ($query[$i]['SetAction'] == 0) ? 'View' : 'Action',
                        'Detail' => $query[$i],
                    ];

                    $rs = $temp;
                    break;
                }
            }
        }

        return $rs;
    }

    public function getDataReceived($arr)
    {
        $strWhere = '';
        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";
        foreach ($arr as $key => $value) {
            $AndOrWhere = ($strWhere == '') ? 'where ' : ' and ';
            $strWhere .= $AndOrWhere.'a.'.$key.' = "'.$value.'"';
        }

        $sql = 'select a.*,b.Descriptions as CategoryDescriptions,b.DepartmentID as DepartmentIDDestination,qdj.NameDepartment as NameDepartmentDestination,
                emp.Name as NameReceivedBy
                from db_ticketing.received as a left join db_ticketing.category as b on a.CategoryReceivedID = b.ID
                left join db_employees.employees as emp on a.ReceivedBy = emp.NIP
                '.$this->m_general->QueryDepartmentJoin('b.DepartmentID').'
                '.$strWhere.'
                order by a.ID asc
        ';
        $query = $this->db->query($sql,array())->result_array();
        for ($i=0; $i < count($query); $i++) { 
            if ($query[$i]['FileUpload'] != null  && $query[$i]['FileUpload'] != '') {
                $temp = $pathfolder.$query[$i]['FileUpload'];
                $token = $this->jwt->encode($temp,"UAP)(*");
                $query[$i]['FileUpload'] = url_files."fileGetAnyToken/".$token;
            }
            
        }
        return $query;
    }

    public function UpdateCategoryRequested($data_received){
        if (array_key_exists('ID', $data_received)) {
           $ID = $data_received['ID'];
           $G_dt = $this->m_master->caribasedprimary('db_ticketing.received','ID',$ID);
           $TicketID = $G_dt[0]['TicketID'];
           // find this receved isFirst ?
           $G_received = $this->m_master->caribasedprimary('db_ticketing.received','TicketID',$TicketID);
           $bool = false;
           $cek = false;
           for ($i=0; $i < count($G_received); $i++) {
               $ReceivedID = $G_received[$i]['ID'];
               $G_received_details = $this->m_master->caribasedprimary('db_ticketing.received_details','ReceivedID',$ReceivedID);
               if (count($G_received_details) > 0) {
                   if (!$cek && $ReceivedID == $ID) {
                       $bool = true;
                       $cek = true;
                       break;
                   }
                   $cek = true;
               }
           }

           if ($bool) {
              $CategoryID = $data_received['data']['CategoryReceivedID'];
              $dataSave = [
                  'CategoryID' => $CategoryID,
              ];
              $this->db->where('ID',$TicketID);
              $this->db->update('db_ticketing.ticket',$dataSave);
           }
        }

    }

    public function TableReceivedAction($data_arr){
        if (array_key_exists('action', $data_arr)) {
            $action = $data_arr['action'];
            switch ($action) {
                case 'insert':
                    $dataSave = $data_arr['data'];
                    $dataSave['CreatedAt'] = date('Y-m-d H:i:s');
                    $this->db->insert('db_ticketing.received',$dataSave);
                    break;
                case 'update':
                    $dataSave = $data_arr['data'];
                    $ID = $data_arr['ID'];
                    $G_dt = $this->m_master->caribasedprimary('db_ticketing.received','ID',$ID);
                    if ($G_dt[0]['ReceivedAt'] == null  || $G_dt[0]['ReceivedAt'] == '' || $G_dt[0]['ReceivedAt'] == '0000-00-00 00:00:00') { // for first time
                        $dataSave['ReceivedAt'] = date('Y-m-d H:i:s');
                    }

                    if ($G_dt[0]['ReceivedBy'] != null  && $G_dt[0]['ReceivedBy'] != '') { // for first time
                        if (array_key_exists('ReceivedBy', $dataSave)) {
                            unset($dataSave['ReceivedBy']);
                        }
                    }

                    if (!empty($dataSave)) {
                        $this->db->where('ID',$ID);
                        $this->db->update('db_ticketing.received',$dataSave);
                    }

                    break;
                default:
                    # code...
                    break;
            }
        }

    }

    private function send_notification_ticketing_worker($arr_pass) {
        /*
            $arr_pass = [
                'To' =>
                'NoTicket' =>
                'DepartmentHandler' =>
                'CreatedBy' =>
            ];

        */

        $G_emp = $this->m_master->caribasedprimary('db_employees.employees','NIP',$arr_pass['CreatedBy']);
        $NameRequested = $G_emp[0]['Name'];
        $NoTicket = $arr_pass['NoTicket'];
        $DepartmentID = $arr_pass['DepartmentHandler'];
        $url_action = $this->jwt->encode($DepartmentID,"UAP)(*");
        $CreatedBy = $arr_pass['CreatedBy'];
        $To = [$arr_pass['To']];

        $array_send_notification=[
          'NameRequested' => $NameRequested,
          'Description' => 'Number Ticket '.$NoTicket,
          'URLDirect' => 'ticket/set_action_progress/'.$NoTicket.'/'.$url_action,
          'CreatedBy' => $CreatedBy,
          'To' => $To,
          'NeedEmail' => 'No',
        ];

        $data = array(
            'auth' => 's3Cr3T-G4N',
            'Logging' => array(
                            'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>E-ticketing Assign to by '.$array_send_notification['NameRequested'],
                            'Description' => $array_send_notification['Description'],
                            'URLDirect' => $array_send_notification['URLDirect'],
                            'CreatedBy' => $array_send_notification['CreatedBy'],
                          ),
            'To' => array(
                      'NIP' => $array_send_notification['To'],
                    ),
            'Email' => $array_send_notification['NeedEmail'],
        );

        $url = url_pas.'rest2/__send_notif_browser';
        $token = $this->jwt->encode($data,"UAP)(*");
        $this->m_master->apiservertoserver($url,$token);
    }

    public function TableReceived_DetailsAction($data_arr,$notifParams=[]){
        if (array_key_exists('action', $data_arr)) {
            $action = $data_arr['action'];
            switch ($action) {
                case 'insert':
                    $data = $data_arr['data'];
                    if (array_key_exists(0, $data)) {
                        for ($i=0; $i <count($data) ; $i++) {
                            $dataSave = $data[$i];
                            $dataSave['At'] = date('Y-m-d H:i:s');
                            $this->db->insert('db_ticketing.received_details',$dataSave);
                            if (!empty($notifParams)) {
                                $arr_pass =  ['To' => $dataSave['NIP'] ] + $notifParams;
                                $this->send_notification_ticketing_worker($arr_pass);
                            }
                        }
                    }
                    else
                    {
                        $dataSave = $data;
                        $dataSave['At'] = date('Y-m-d H:i:s');
                        $this->db->insert('db_ticketing.received_details',$dataSave);
                        if (!empty($notifParams)) {
                            $arr_pass =  ['To' => $dataSave['NIP'] ] + $notifParams;
                            $this->send_notification_ticketing_worker($arr_pass);
                        }
                    }
                    break;
                case 'update':
                    if (!array_key_exists(0, $data_arr)) { // not array
                        $data = $data_arr['data'];
                        $ID = $data_arr['ID'];
                        $dataSave = $data;
                        $dataSave['At'] = date('Y-m-d H:i:s');
                        $this->db->where('ID',$ID);
                        $this->db->update('db_ticketing.received_details',$dataSave);
                    }
                default:
                    # code...
                    break;
            }
        }
    }

    private function send_notification_ticketing_transfer_to($data_arr_index){
       $CreatedBy = $data_arr_index['CreatedBy'];
       $G_emp = $this->m_master->caribasedprimary('db_employees.employees','NIP',$CreatedBy);
       $NoTicket = $data_arr_index['NoTicket'];
       $DepartmentID = $data_arr_index['data']['DepartmentTransferToID'];
       $url_action = $this->jwt->encode($DepartmentID,"UAP)(*");
       $getAllUserByDepartment = function($DepartmentID){
        $dataToken = ['DepartmentID' => $DepartmentID];
        $get_data  = $this->m_general->getAllUserByDepartment($dataToken);
        $rs = [];
        for ($i=0; $i < count($get_data); $i++) {
            $rs[] = $get_data[$i]['NIP'];
        }
        return $rs;
       };
       $array_send_notification=[
         'NameRequested' => $G_emp[0]['Name'],
         'Description' => 'Number Ticket '.$NoTicket,
         'URLDirect' => 'ticket/set_action_progress/'.$NoTicket.'/'.$url_action,
         'CreatedBy' => $CreatedBy,
         'To' => $getAllUserByDepartment($DepartmentID),
         'NeedEmail' => 'No',
       ];

       $data = array(
           'auth' => 's3Cr3T-G4N',
           'Logging' => array(
                           'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>E-ticketing Transfer to by '.$array_send_notification['NameRequested'],
                           'Description' => $array_send_notification['Description'],
                           'URLDirect' => $array_send_notification['URLDirect'],
                           'CreatedBy' => $array_send_notification['CreatedBy'],
                         ),
           'To' => array(
                     'NIP' => $array_send_notification['To'],
                   ),
           'Email' => $array_send_notification['NeedEmail'],
       );

       $url = url_pas.'rest2/__send_notif_browser';
       $token = $this->jwt->encode($data,"UAP)(*");
       $this->m_master->apiservertoserver($url,$token);
    }

    public function ProcessTransferTo($data_arr){
        if (count($data_arr) > 0) {
            for ($i=0; $i < count($data_arr); $i++) {
               $data_arr_index =$data_arr[$i];
               $action = $data_arr_index['action'];
               $this->TableReceivedAction($data_arr_index);
               // send notification
               if ($action == 'update' && array_key_exists('DepartmentTransferToID', $data_arr_index['data']) ) {
                   $this->send_notification_ticketing_transfer_to($data_arr_index);
               }
            }
        }
    }

    public function process_ticket($data_arr){
        $action = $data_arr['action'];
        switch ($action) {
            case 'update':
                $ID = $data_arr['ID'];
                $dataSave = $data_arr['data'];
                $this->db->where('ID',$ID);
                $this->db->update('db_ticketing.ticket',$dataSave);
                break;

            default:
                # code...
                break;
        }
    }

    public function getDataReceived_worker($arr){
         $data_received = $this->getDataReceived($arr);
         for ($i=0; $i < count($data_received); $i++) {
              $DataReceived_Details = $this->getDataReceived_DetailsBy(['ReceivedID' => $data_received[$i]['ID'] ]);
              $data_received[$i]['DataReceived_Details'] = $DataReceived_Details;
              $data_received[$i]['ReceivedAt'] = $this->__set_tgl_ticket($data_received[$i]['ReceivedAt']);
              $data_received[$i]['ReceivedAtTracking'] = $this->__set_datetime_modal_tracking($data_received[$i]['CreatedAt']);
              $data_received[$i]['DataRating'] = $this->m_master->caribasedprimary('db_ticketing.rating','ReceivedID',$data_received[$i]['ID']);
         }

         return $data_received;
    }

    public function getDataReceived_DetailsBy($arr){
        $strWhere = '';
        foreach ($arr as $key => $value) {
            $AndOrWhere = ($strWhere == '') ? 'where ' : ' and ';
            $strWhere .= $AndOrWhere.'a.'.$key.' = "'.$value.'"';
        }

        $sql = 'select a.*,b.Name as NameWorker from db_ticketing.received_details as a
                join db_employees.employees as b on a.NIP = b.NIP
                '.$strWhere.'
        ';

        $query = $this->db->query($sql,array())->result_array();
        for ($i=0; $i < count($query); $i++) {
            $DueDateShow = date('M d, Y', strtotime($query[$i]['DueDate']));
            $query[$i]['DueDateShow'] = $DueDateShow;
        }
        return $query;
    }

    public function DataReceivedSelected($TicketID,$DepartmentID){
        $rs = [];
        $arr_where = [
            'TicketID' => $TicketID,
            'DepartmentReceivedID' => $DepartmentID,
            'SetAction' => "1",
        ];

        $DataReceived = $this->getDataReceived_worker($arr_where);
        if (count($DataReceived) == 0) {
            show_404($log_error = TRUE);
            die();
        }
        else
        {
            $rs = $DataReceived;
        }

        return $rs;
    }

    public function trigger_close_ticket($TicketID){
        $sql = 'select count(*) as total from db_ticketing.received where ReceivedStatus = "0" and TicketID = "'.$TicketID.'" ';
        $query = $this->db->query($sql,array())->result_array();
        if ($query[0]['total'] == 0) {
          $dataSave = [
            'TicketStatus' => 3,
            'TicketClosedAt' => date('Y-m-d H:i:s'),
          ];

          $this->db->where('ID',$TicketID);
          $this->db->update('db_ticketing.ticket',$dataSave);
        }
    }

    public function LoadDataTicketStatus($dataToken){
        $rs = [];
        $AddWhere = '';
        $sql = 'select * from db_ticketing.ticket_status
        ';
        $query = $this->db->query($sql,array())->result_array();

        $data = array();
        for ($i=0; $i < count($query); $i++) {
            $nestedData = array();
            $row = $query[$i];
            $nestedData[] = $i+1;
            foreach ($row as $key => $value) {
              $nestedData[] = $value;
            }
            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }

        $rs = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data
        );

        return $rs;
    }

    public function LoadTicketListServerSide($dataToken){
        $requestData = $_REQUEST;
        $TicketStatus = $dataToken['TicketStatus'];
        $DepartmentID = $dataToken['DepartmentID'];
        $NIP = $dataToken['NIP'];
        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";

        /*
            Outgoing : Ticket keluar dari Department by department request 99
            Incoming : Ticket terima atau handler -99

        */

        $totalData = 100;
        switch ($TicketStatus) {
            case '1':
            case 1:
                $Addwhere3 ='';
                $Addwhere = ' and (ca.DepartmentID = "'.$dataToken['DepartmentID'].'" or a.DepartmentTicketID = "'.$dataToken['DepartmentID'].'" )';
                if (array_key_exists('FilterFor', $dataToken)) {
                    if ($dataToken['FilterFor'] == '99') {
                       $Addwhere3 .= ' and a.DepartmentTicketID = "'.$DepartmentID.'"';
                    }
                    elseif ($dataToken['FilterFor'] == '-99') {
                        $Addwhere3 .= ' and a.DepartmentTicketID != "'.$DepartmentID.'"';
                    }
                }

                $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                        b.Photo,qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.ID,ca.Descriptions as CategoryDescriptions,a.TicketStatus,ts.Status as NameStatusTicket,qdx.NameDepartment as NameDepartmentTicket
                        from db_ticketing.ticket as a
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                        '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                        join db_employees.employees as b on a.RequestedBy = b.NIP
                        join db_ticketing.ticket_status as ts on ts.ID = a.TicketStatus
                        where a.TicketStatus = 1
                        '.$Addwhere.'
                        and (
                             b.Name LIKE "'.$requestData['search']['value'].'%" or
                             a.NoTicket LIKE "'.$requestData['search']['value'].'%"
                        )
                        and (select count(*) as total from db_ticketing.received where TicketID = a.ID) = 1
                        ';
                $sql.= ' ORDER BY a.ID desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';

                $sqlTotalData = 'select count(*) as total from (
                        select a.NoTicket
                        from db_ticketing.ticket as a
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                        join db_employees.employees as b on a.RequestedBy = b.NIP
                        join db_ticketing.ticket_status as ts on ts.ID = a.TicketStatus
                        where a.TicketStatus = 1
                        '.$Addwhere.'
                        and (
                             b.Name LIKE "'.$requestData['search']['value'].'%" or
                             a.NoTicket LIKE "'.$requestData['search']['value'].'%"
                        )
                        and (select count(*) as total from db_ticketing.received where TicketID = a.ID) = 1
                    )xx';
                break;
            case '2':
            case 2:
                $AddwhereFor = '';
                $AddwhereFor2 = 'a.DepartmentTicketID = "'.$dataToken['DepartmentID'].'"
                                     or';
                $Addwhere3 = '';
                if (array_key_exists('FilterFor', $dataToken) ) {
                    if ($dataToken['FilterFor'] == '1') {
                        $AddwhereFor = 'and a.ID in (
                                                select ReceivedID from db_ticketing.received_details where Status != "-1" AND NIP = "'.$NIP.'"
                                             )';
                        $AddwhereFor2 = '';
                    }
                    if ($dataToken['FilterFor'] == '99') {
                       $Addwhere3 .= ' or a.DepartmentTicketID = "'.$DepartmentID.'"';
                       $AddwhereFor = ' and a.DepartmentTransferToID IS NOT NULL and a.DepartmentTransferToID != ""';
                    }
                    elseif ($dataToken['FilterFor'] == '-99') {
                        // $AddwhereFor = ' and (select count(*) as total from db_ticketing.received_details where ReceivedID = a.ID) > 0';
                        $AddwhereFor = ' and a.ReceivedStatus = "0" and a.SetAction = "1" ';
                        $AddwhereFor2 = '';
                    }

                    // $AddwhereFor2 = '';
                }
                $Addwhere = ' and ( '.$AddwhereFor2.' a.ID in (
                                         select a.TicketID from db_ticketing.received as a
                                         where a.DepartmentReceivedID = "'.$dataToken['DepartmentID'].'"
                                         '.$AddwhereFor.'
                                     )
                        '.$Addwhere3.'
                 ) ';

                $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                        b.Photo,a.ID,ca.Descriptions as CategoryDescriptions,a.DepartmentTicketID,qdx.NameDepartment as NameDepartmentTicket,
                        qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.TicketStatus,ts.Status as NameStatusTicket
                        from db_ticketing.ticket as a
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        join db_employees.employees as b on a.RequestedBy = b.NIP
                        join db_ticketing.ticket_status as ts on ts.ID = a.TicketStatus
                        '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID','qdj').'
                        where a.TicketStatus = 2
                        '.$Addwhere.'
                        and (
                             b.Name LIKE "'.$requestData['search']['value'].'%" or
                             a.NoTicket LIKE "'.$requestData['search']['value'].'%"
                        )
                        ';
                $sql.= ' ORDER BY a.ID desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';

                $sqlTotalData = 'select count(*) as total from (
                        select a.NoTicket
                        from db_ticketing.ticket as a
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        join db_employees.employees as b on a.RequestedBy = b.NIP
                        join db_ticketing.ticket_status as ts on ts.ID = a.TicketStatus
                        '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID','qdj').'
                        where a.TicketStatus = 2
                        '.$Addwhere.'
                        and (
                             b.Name LIKE "'.$requestData['search']['value'].'%" or 
                             a.NoTicket LIKE "'.$requestData['search']['value'].'%"
                        )
                    )xx';
                break;
            case '3':
            case 3:
            case '4':
            case 4:
                $AddwhereFor = '';
                $AddwhereFor2 = 'a.DepartmentTicketID = "'.$dataToken['DepartmentID'].'"
                                     or';
                $Addwhere3 = '';
                if (array_key_exists('FilterFor', $dataToken) ) {
                    if ($dataToken['FilterFor'] == '1') {
                        $AddwhereFor = 'and a.ID in (
                                                select ReceivedID from db_ticketing.received_details where Status != "-1" AND NIP = "'.$NIP.'"
                                             )';
                        $AddwhereFor2 = '';
                    }

                    if ($dataToken['FilterFor'] == '99') {
                       $Addwhere3 .= ' or a.DepartmentTicketID = "'.$DepartmentID.'"';
                       $AddwhereFor = '
                           and a.DepartmentTransferToID IS NOT NULL and a.DepartmentTransferToID != ""
                       ';
                    }
                    elseif ($dataToken['FilterFor'] == '-99') {
                        // $Addwhere3 .= ' or a.DepartmentTicketID != "'.$DepartmentID.'"';
                        $AddwhereFor = '
                            and (select count(*) as total from db_ticketing.received_details where ReceivedID = a.ID) > 0
                        ';
                        $AddwhereFor2 = '';
                    }

                    // $AddwhereFor2 = '';
                }

                $Addwhere = ' and ( '.$AddwhereFor2.' a.ID in (
                                         select a.TicketID from db_ticketing.received as a
                                         where a.DepartmentReceivedID = "'.$dataToken['DepartmentID'].'"
                                         '.$AddwhereFor.'
                                     )
                 '.$Addwhere3.'
                 ) ';
              
                $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                        b.Photo,a.ID,ca.Descriptions as CategoryDescriptions,a.DepartmentTicketID,qdx.NameDepartment as NameDepartmentTicket,
                        qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.TicketStatus,ts.Status as NameStatusTicket
                        from db_ticketing.ticket as a
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        join db_employees.employees as b on a.RequestedBy = b.NIP
                        join db_ticketing.ticket_status as ts on ts.ID = a.TicketStatus
                        '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID','qdj').'
                        where a.TicketStatus = '.$TicketStatus.'
                        '.$Addwhere.'
                        and (
                             b.Name LIKE "'.$requestData['search']['value'].'%" or 
                             a.NoTicket LIKE "'.$requestData['search']['value'].'%"
                        )
                        ';
                // print_r($sql);die();
                $sql.= ' ORDER BY a.ID desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
                $sqlTotalData = 'select count(*) as total from (
                        select a.NoTicket
                        from db_ticketing.ticket as a
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        join db_employees.employees as b on a.RequestedBy = b.NIP
                        '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID','qdj').'
                        where a.TicketStatus = '.$TicketStatus.'
                        '.$Addwhere.'
                        and (
                             b.Name LIKE "'.$requestData['search']['value'].'%" or 
                             a.NoTicket LIKE "'.$requestData['search']['value'].'%"
                        )
                    )xx';
                break;
            case '%':
                $AddwhereFor = '';
                $AddwhereFor2 = 'a.DepartmentTicketID = "'.$dataToken['DepartmentID'].'"
                                     or';
                $Addwhere3 = '';
                if (array_key_exists('FilterFor', $dataToken) ) {
                    if ($dataToken['FilterFor'] == '1') {
                        $AddwhereFor = 'and a.ID in (
                                                select ReceivedID from db_ticketing.received_details where Status != "-1" AND NIP = "'.$NIP.'"
                                             )';
                        $AddwhereFor2 = '';
                    }

                    if ($dataToken['FilterFor'] == '99') {
                       $Addwhere3 .= ' or a.DepartmentTicketID = "'.$DepartmentID.'"';
                       $AddwhereFor = '
                           and a.DepartmentTransferToID IS NOT NULL and a.DepartmentTransferToID != ""
                       ';
                    }
                    elseif ($dataToken['FilterFor'] == '-99') {
                        // $Addwhere3 .= ' or a.DepartmentTicketID != "'.$DepartmentID.'"';
                        $AddwhereFor = '
                            and (select count(*) as total from db_ticketing.received_details where ReceivedID = a.ID) > 0
                        ';
                        $AddwhereFor2 = '';
                    }

                    // $AddwhereFor2 = '';
                }

                $Addwhere = ' Where ( '.$AddwhereFor2.' a.ID in (
                                         select a.TicketID from db_ticketing.received as a
                                         where a.DepartmentReceivedID = "'.$dataToken['DepartmentID'].'"
                                         '.$AddwhereFor.'
                                     )
                 '.$Addwhere3.'
                 ) ';
                
                $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                        b.Photo,a.ID,ca.Descriptions as CategoryDescriptions,a.DepartmentTicketID,qdx.NameDepartment as NameDepartmentTicket,
                        qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.TicketStatus,ts.Status as NameStatusTicket
                        from db_ticketing.ticket as a
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        join db_employees.employees as b on a.RequestedBy = b.NIP
                        join db_ticketing.ticket_status as ts on ts.ID = a.TicketStatus
                        '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID','qdj').'
                        '.$Addwhere.'
                        and (
                             b.Name LIKE "'.$requestData['search']['value'].'%" or 
                             a.NoTicket LIKE "'.$requestData['search']['value'].'%"
                        )
                        ';
                // print_r($sql);die();
                $sql.= ' ORDER BY a.ID desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
                $sqlTotalData = 'select count(*) as total from (
                        select a.NoTicket
                        from db_ticketing.ticket as a
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        join db_employees.employees as b on a.RequestedBy = b.NIP
                        '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID','qdj').'
                        '.$Addwhere.'
                        and (
                             b.Name LIKE "'.$requestData['search']['value'].'%" or 
                             a.NoTicket LIKE "'.$requestData['search']['value'].'%"
                        )
                    )xx';
                break;    
            default:
                # code...
                break;
        }

        $query = $this->db->query($sql)->result_array();
        $queryTotal = $this->db->query($sqlTotalData)->result_array();
        $totalData = $queryTotal[0]['total'];
        // set data
        $query = $this->__ticket_list_set_data($query,$dataToken);
        $No = $requestData['start'] + 1;
        $data = array();
        for ($i=0; $i < count($query); $i++) {
            $nestedData=array();
            $row = $query[$i];
            $nestedData[] = $No;
            $nestedData[] = $row['NoTicket'];
            $nestedData[] = $row['NameRequested'];
            $nestedData[] = $row['Title'];
            $nestedData[] = $row['Message'];
            $nestedData[] = $row['NameStatusTicket'];
            $nestedData[] = $row['RequestedAt'];
            $nestedData[] = $row['NameStatusTicket'];
            $nestedData[] = $row['TicketStatus'];
            $nestedData[] = $row['token'];
            $nestedData[] = $row['Files'];
            $nestedData[] = $row['setTicket'];
            $nestedData[] = $row['NameDepartmentTicket'];
            $data[] = $nestedData;
            $No++;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data,
        );

        return $json_data;
    }

    private function __ticket_list_set_data($query,$dataToken)
    {
        $NIP = $dataToken['NIP'];
        for ($i=0; $i < count($query); $i++) {
            $data_received = $this->getDataReceived_worker([ 'TicketID' => $query[$i]['ID'] ]);

            $query[$i]['data_received'] = $data_received;

            if ($query[$i]['Files'] != '' && $query[$i]['Files'] != null) {
                $token = $this->jwt->encode($query[$i]['Files'],"UAP)(*");
                $url = url_files."fileGetAnyToken/".$token;
                $query[$i]['Files'] = $url;
            }

            // add foto
            if ($query[$i]['Photo'] != '' && $query[$i]['Photo'] != null) {
                $url = url_pas."uploads/employees/".$query[$i]['Photo'];
                $query[$i]['Photo'] = $url;
            }

            $query[$i]['RequestedAt'] = $this->__set_tgl_ticket($query[$i]['RequestedAt']);
            $query[$i]['setTicket'] = $this->__setTicket_action_progress($query[$i]['NoTicket'],$NIP,$dataToken['DepartmentID'],$data_received,$query[$i]['TicketStatus']);
            $token = $this->jwt->encode($query[$i],"UAP)(*");
            $query[$i]['token'] =  $token;
        }

        return $query;
    }

    public function ticketing_GiveRatingCheck($dataToken){
        $rs = [];
        $NIP = $dataToken['NIP'];
        $arr_where = [
            'RequestedBy' => $NIP,
            'TicketStatus' => 3,
        ];
        $GetTicketData = $this->getDataTicketBy($arr_where);
        if (count($GetTicketData) > 0) {
            for ($i=0; $i < count($GetTicketData); $i++) {
                $data_received = $this->getDataReceived_worker([ 'TicketID' => $GetTicketData[$i]['ID'] ]);
                $GetTicketData[$i]['data_received'] = $data_received;
                $rs[] = $GetTicketData[$i];
            }
        }

        return $rs;
    }

    public function __create_rating($dataToken){
        $dataSave = $dataToken['data'];
        $dataSave['EntredAt'] = date('Y-m-d H:i:s');
        $this->db->insert('db_ticketing.rating',$dataSave);
        $this->trigger_after_create_ticketing($dataToken['TicketID']);
    }

    private function trigger_after_create_ticketing($TicketID){
        $data_received = $this->getDataReceived_worker([ 'TicketID' => $TicketID ]);
        $bool = true;
        for ($i=0; $i < count($data_received); $i++) {
            $DataRating = $data_received[$i]['DataRating'];
            $DataReceived_Details = $data_received[$i]['DataReceived_Details'];
            if (count($DataRating) == 0 && count($DataReceived_Details) > 0 ) {
                $bool = false;
                break;
            }
        }

        if ($bool) {
            $this->db->where('ID',$TicketID);
            $dataSave = [
                'TicketStatus' => 4,
            ];
            $this->db->update('db_ticketing.ticket',$dataSave);
        }
    }

    public function dashboard_ticket_date($dataToken){
        $dateGet = $dataToken['dateGet'];
        $rs = [];
        // ALL Department
        $url = url_pas.'api/__getAllDepartementPU';
        $GetDepartment = $this->m_master->apiservertoserver($url);
        $data = array();
        for ($i=0; $i < count($GetDepartment); $i++) { 
            $nestedData = array();
            $nestedData[] = $i+1;
            $nestedData[] = $GetDepartment[$i]['Name1'];

            $queryGet = function($DepartmentID,$dateGet,$TicketStatus=''){
                if ($TicketStatus == 3) {
                    $TicketStatus =  ' (a.TicketStatus = 3 or  a.TicketStatus = 4  ) and';
                }
                else
                {
                   $TicketStatus = ($TicketStatus != '') ? ' a.TicketStatus = '.$TicketStatus.' and' : '';
                }
                
                return  $this->db->query(    
                    'select count(*) as total from (
                        select 1 from db_ticketing.ticket as a 
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                        where '.$TicketStatus.'
                        a.ID in (
                            select a.TicketID from db_ticketing.received as a
                            where a.DepartmentReceivedID = "'.$DepartmentID.'"
                            and DATE_FORMAT(a.CreatedAt,"%Y-%m-%d") = "'.$dateGet.'"
                        )
                    )xx '
                )->result_array()[0]['total'];
            };

            $nestedData[] = $queryGet($GetDepartment[$i]['Code'],$dateGet);
            $nestedData[] = $queryGet($GetDepartment[$i]['Code'],$dateGet,1);
            $nestedData[] = $queryGet($GetDepartment[$i]['Code'],$dateGet,2);
            $nestedData[] = $queryGet($GetDepartment[$i]['Code'],$dateGet,3);
            $queryGetData = function($DepartmentID,$dateGet,$TicketStatus=''){
                        if ($TicketStatus == 3) {
                            $TicketStatus =  'where (a.TicketStatus = 3  or a.TicketStatus = 4 )';
                        }
                        else
                        {
                             $TicketStatus = ($TicketStatus != '') ? 'where a.TicketStatus = '.$TicketStatus : '';
                        }
                       
                        return '
                                select a.*,ca.Descriptions as CategoryDescriptions,emp.Name as NameRequested,
                                 rec.CategoryReceivedID
                                 from db_ticketing.ticket as a 
                                join db_employees.employees as emp on emp.NIP = a.RequestedBy
                                join (
                                        select rec.* from db_ticketing.received as rec
                                        where
                                        rec.DepartmentReceivedID = "'.$DepartmentID.'"
                                        and DATE_FORMAT(rec.CreatedAt,"%Y-%m-%d") = "'.$dateGet.'"
                                        group by rec.TicketID,rec.CategoryReceivedID
                                        order by rec.ID desc
                                    ) as rec on a.ID = rec.TicketID
                                join db_ticketing.category as ca on rec.CategoryReceivedID = ca.ID
                                 '.$TicketStatus.'
                             ';
                    };
            $dataRow = [
                'tot' => $queryGetData($GetDepartment[$i]['Code'],$dateGet),
                'Open' => $queryGetData($GetDepartment[$i]['Code'],$dateGet,1),
                'Progress' => $queryGetData($GetDepartment[$i]['Code'],$dateGet,2),
                'Closed' => $queryGetData($GetDepartment[$i]['Code'],$dateGet,3),
            ];

            $nestedData[] = $this->jwt->encode($dataRow,"UAP)(*");
            $data[] = $nestedData;

        }

        $json_data = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($GetDepartment)),
            "recordsFiltered" => intval( count($GetDepartment) ),
            "data"            => $data,
        );

        return $json_data;

    }

    public function dashboard_ticket_all($dataToken){
        $dateGet = $dataToken['dateGet'];
        $rs = [];
        // ALL Department
        $url = url_pas.'api/__getAllDepartementPU';
        $GetDepartment = $this->m_master->apiservertoserver($url);
        $data = array();
        for ($i=0; $i < count($GetDepartment); $i++) { 
            $nestedData = array();
            $nestedData[] = $i+1;
            $nestedData[] = $GetDepartment[$i]['Name1'];

            $queryGet = function($DepartmentID,$dateGet,$TicketStatus=''){
                if ($TicketStatus == 3) {
                    $TicketStatus =  ' (a.TicketStatus = 3 or  a.TicketStatus = 4  ) and';
                }
                else
                {
                    $TicketStatus = ($TicketStatus != '') ? ' a.TicketStatus = '.$TicketStatus.' and' : '';
                }
                
                return  $this->db->query(    
                    'select count(*) as total from (
                        select 1 from db_ticketing.ticket as a 
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                        where '.$TicketStatus.'
                        a.ID in (
                            select a.TicketID from db_ticketing.received as a
                            where a.DepartmentReceivedID = "'.$DepartmentID.'"
                            and DATE_FORMAT(a.CreatedAt,"%Y-%m") = "'.$dateGet.'"
                        )
                    )xx '
                )->result_array()[0]['total'];
            };

            $nestedData[] = $queryGet($GetDepartment[$i]['Code'],$dateGet);
            $nestedData[] = $queryGet($GetDepartment[$i]['Code'],$dateGet,1);
            $nestedData[] = $queryGet($GetDepartment[$i]['Code'],$dateGet,2);
            $nestedData[] = $queryGet($GetDepartment[$i]['Code'],$dateGet,3);
            $queryGetData = function($DepartmentID,$dateGet,$TicketStatus=''){
                        if ($TicketStatus == 3) {
                            $TicketStatus =  'where (a.TicketStatus = 3  or a.TicketStatus = 4 )';
                        }
                        else
                        {
                            $TicketStatus = ($TicketStatus != '') ? 'where a.TicketStatus = '.$TicketStatus : '';
                        }
                        
                        return '
                                select a.*,ca.Descriptions as CategoryDescriptions,emp.Name as NameRequested,
                                 rec.CategoryReceivedID
                                 from db_ticketing.ticket as a 
                                join db_employees.employees as emp on emp.NIP = a.RequestedBy
                                join (
                                        select rec.* from db_ticketing.received as rec
                                        where
                                        rec.DepartmentReceivedID = "'.$DepartmentID.'"
                                        and DATE_FORMAT(rec.CreatedAt,"%Y-%m") = "'.$dateGet.'"
                                        group by rec.TicketID,rec.CategoryReceivedID
                                        order by rec.ID desc
                                    ) as rec on a.ID = rec.TicketID
                                join db_ticketing.category as ca on rec.CategoryReceivedID = ca.ID
                                 '.$TicketStatus.'
                             ';
                    };
            $dataRow = [
                'tot' => $queryGetData($GetDepartment[$i]['Code'],$dateGet),
                'Open' => $queryGetData($GetDepartment[$i]['Code'],$dateGet,1),
                'Progress' => $queryGetData($GetDepartment[$i]['Code'],$dateGet,2),
                'Closed' => $queryGetData($GetDepartment[$i]['Code'],$dateGet,3),
            ];

            $nestedData[] = $this->jwt->encode($dataRow,"UAP)(*");
            $data[] = $nestedData;

        }

        $json_data = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($GetDepartment)),
            "recordsFiltered" => intval( count($GetDepartment) ),
            "data"            => $data,
        );

        return $json_data;
    }

    public function dashboard_detail($dataToken){
        $rs=[];
        $subdata = $dataToken['subdata'];
        $action = $subdata['action'];
        $dataDecode = $subdata['dataDecode'];
        $dataTable = [];
        $graph = [];
        foreach ($dataDecode as $key => $value) {
           if ($key == $action) {
               $dataTable = $this->detail_data_table($value);
               $graph = $this->detail_graph_category($value);
               break;
           }
        }

        $rs['dataTable'] = $dataTable;
        $rs['graph'] = $graph;
        return $rs;
    }

    private function detail_graph_category($query){
        $sql = $this->db->query(
            'select CategoryDescriptions as label,count(*) as data from (
                '.$query.'
            ) xx
            group by CategoryReceivedID
        '
        )->result_array();
        return $sql;
    }

    private function detail_data_table($query){
        $query = $this->db->query($query)->result_array();
        $data = array();
        if (count($query) > 0) {
           for ($i=0; $i < count($query); $i++) { 
               $nestedData = array();
               $nestedData[] = $i+1;
               $nestedData[] = $query[$i]['NoTicket'];
               $nestedData[] = $query[$i]['NameRequested'];
               $nestedData[] = $query[$i]['CategoryDescriptions'];
               $data[] = $nestedData;
           }
        }
        

        $json_data = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data,
            // "result"          => $query,
        );

        return $json_data;
    }

    public function dashboard_graph_ticket_date($dataToken){
        $rs = [];
        $url = url_pas.'api/__getAllDepartementPU';
        $GetDepartment = $this->m_master->apiservertoserver($url);
        $tot = [];
        $Open = [];
        $Progress = [];
        $Closed = [];
        $dateGet = $dataToken['dateGet'];
        $c = 10;
        for ($i=0; $i < count($GetDepartment); $i++) { 
            $Abbr = $GetDepartment[$i]['Abbr'];
            $queryGet = function($DepartmentID,$dateGet,$TicketStatus=''){
                // $TicketStatus = ($TicketStatus != '') ? ' a.TicketStatus = '.$TicketStatus.' and' : '';
                if ($TicketStatus == 3) {
                    $TicketStatus =  ' (a.TicketStatus = 3 or  a.TicketStatus = 4  ) and';
                }
                else
                {
                   $TicketStatus = ($TicketStatus != '') ? ' a.TicketStatus = '.$TicketStatus.' and' : '';
                }
                return  $this->db->query(    
                    'select count(*) as total from (
                        select 1 from db_ticketing.ticket as a 
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                        where '.$TicketStatus.'
                        a.ID in (
                            select a.TicketID from db_ticketing.received as a
                            where a.DepartmentReceivedID = "'.$DepartmentID.'"
                            and DATE_FORMAT(a.CreatedAt,"%Y-%m-%d") = "'.$dateGet.'"
                        )
                    )xx '
                )->result_array()[0]['total'];
            };

            $result = $queryGet($GetDepartment[$i]['Code'],$dateGet);
            if ($result > 0) {
                $tot[] = [$c,$queryGet($GetDepartment[$i]['Code'],$dateGet)];
                $Open[] = [$c,$queryGet($GetDepartment[$i]['Code'],$dateGet,1)];
                $Progress[] = [$c,$queryGet($GetDepartment[$i]['Code'],$dateGet,2)];
                $Closed[] = [$c,$queryGet($GetDepartment[$i]['Code'],$dateGet,3)];
                $Abbreviation[] = $Abbr;
                $c++;
            }
        }

        $rs =[
            'Total' => $tot,
            'Open' => $Open,
            'Progress' => $Progress,
            'Closed' => $Closed,
            'Abbreviation' => $Abbreviation,
        ];

        return $rs;

    }

    public function dashboard_graph_ticket_all($dataToken){
        $rs = [];
        $url = url_pas.'api/__getAllDepartementPU';
        $GetDepartment = $this->m_master->apiservertoserver($url);
        $tot = [];
        $Open = [];
        $Progress = [];
        $Closed = [];
        $dateGet = $dataToken['dateGet'];
        $c = 10;
        for ($i=0; $i < count($GetDepartment); $i++) { 
            $Abbr = $GetDepartment[$i]['Abbr'];
            $queryGet = function($DepartmentID,$dateGet,$TicketStatus=''){
                if ($TicketStatus == 3) {
                    $TicketStatus =  ' (a.TicketStatus = 3 or  a.TicketStatus = 4  ) and';
                }
                else
                {
                    $TicketStatus = ($TicketStatus != '') ? ' a.TicketStatus = '.$TicketStatus.' and' : '';
                }
                return  $this->db->query(    
                    'select count(*) as total from (
                        select 1 from db_ticketing.ticket as a 
                        join db_ticketing.category as ca on a.CategoryID = ca.ID
                        '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                        where '.$TicketStatus.'
                        a.ID in (
                            select a.TicketID from db_ticketing.received as a
                            where a.DepartmentReceivedID = "'.$DepartmentID.'"
                            and DATE_FORMAT(a.CreatedAt,"%Y-%m") = "'.$dateGet.'"
                        )
                    )xx '
                )->result_array()[0]['total'];
            };

            $result = $queryGet($GetDepartment[$i]['Code'],$dateGet);
            if ($result > 0) {
                $tot[] = [$c,$queryGet($GetDepartment[$i]['Code'],$dateGet)];
                $Open[] = [$c,$queryGet($GetDepartment[$i]['Code'],$dateGet,1)];
                $Progress[] = [$c,$queryGet($GetDepartment[$i]['Code'],$dateGet,2)];
                $Closed[] = [$c,$queryGet($GetDepartment[$i]['Code'],$dateGet,3)];
                $Abbreviation[] = $Abbr;
                $c++;
            }
        }

        $rs =[
            'Total' => $tot,
            'Open' => $Open,
            'Progress' => $Progress,
            'Closed' => $Closed,
            'Abbreviation' => $Abbreviation,
        ];

        return $rs;

    }

}
