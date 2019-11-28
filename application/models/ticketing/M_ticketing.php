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
        ];

        $this->db->insert('db_ticketing.received',$dataSave);
    }

    private function Ticketing_uploadNas($filename)
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
        $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo,qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.ID,ca.Descriptions as CategoryDescriptions,a.TicketStatus
                from db_ticketing.ticket as a 
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                join db_employees.employees as b on a.RequestedBy = b.NIP
                where TicketStatus = 1 and DATE_FORMAT(RequestedAt,"%Y-%m-%d") = CURDATE()
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
        $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo,qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.ID,ca.Descriptions as CategoryDescriptions,a.TicketStatus
                from db_ticketing.ticket as a 
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                join db_employees.employees as b on a.RequestedBy = b.NIP
                where TicketStatus = 1 and DATE_FORMAT(RequestedAt,"%Y-%m-%d") < CURDATE()
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
                                    where SetAction = "1" and qdp.ID = "'.$dataToken['DepartmentID'].'"  
                                )   
            )';
        }
        $NIP = $dataToken['NIP'];
        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";
        $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo,a.ID,ca.Descriptions as CategoryDescriptions,a.DepartmentTicketID,qdx.NameDepartment as NameDepartmentTicket,
                qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.TicketStatus
                from db_ticketing.ticket as a 
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                join db_employees.employees as b on a.RequestedBy = b.NIP
                '.$this->m_general->QueryDepartmentJoin('a.DepartmentTicketID','qdx').'
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID','qdj').'
                where TicketStatus = 2
                '.$Addwhere.$customwhere.'
                order by a.ID asc
                ';
        $query = $this->db->query($sql,array())->result_array();
        $rs['count'] = count($query);
        for ($i=0; $i < $rs['count']; $i++) {
            $data_received = $this->getDataReceived_worker([ 'TicketID' => $query[$i]['ID'],'SetAction' => 1 ]);

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
                if ($this->m_general->auth($DepartmentID,$NIP)) {
                    $rs['bool'] = true;
                    $rs['callback'] = $DepartmentIDDestination;
                }
            }
           
        }
        return $rs;
    }

    private function __get_department_auth_action($TicketID,$DepartmentID,$first){
        $rs = [];
        $sql = 'select rcv.*,ca.DepartmentID from db_ticketing.received as rcv
                join db_ticketing.category as ca on rcv.CategoryReceivedID = ca.ID
                where rcv.TicketID = '.$TicketID.'
                order by rcv.ID asc
                ';
        $query = $this->db->query($sql,array())->result_array();
        if ($first =='yes') {
           if (count($query) == 1) {
              for ($i=0; $i < count($query); $i++) {
                  if ($DepartmentID == $query[$i]['DepartmentID']) {
                      $temp = [
                          'Status' => ($query[$i]['ReceivedStatus'] == 1) ? 'closed' : 'open',
                          'DepartmentID' => $query[$i]['DepartmentID'],
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
                if ($DepartmentID == $query[$i]['DepartmentID']) {
                    $temp = [
                        'Status' => ($query[$i]['ReceivedStatus'] == 1) ? 'closed' : 'open',
                        'DepartmentID' => $query[$i]['DepartmentID'],
                        'SetAction' => ($query[$i]['SetAction'] == 0) ? 'View' : 'Action',
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
        return $query;
    }

    public function TableReceivedAction($data_arr){
        if (array_key_exists('action', $data_arr)) {
            $action = $data_arr['action'];
            switch ($action) {
                case 'insert':
                    $dataSave = $data_arr['data'];
                    $dataSave['ReceivedAt'] = date('Y-m-d H:i:s');
                    $this->db->insert('db_ticketing.received',$dataSave);
                    break;
                case 'update':
                    $dataSave = $data_arr['data'];
                     $dataSave['ReceivedAt'] = date('Y-m-d H:i:s');
                    $ID = $data_arr['ID'];
                    $this->db->where('ID',$ID);
                    $this->db->update('db_ticketing.received',$dataSave);
                    break;
                default:
                    # code...
                    break;
            }
        }
        
    }

    public function TableReceived_DetailsAction($data_arr){
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
                        }
                    }
                    else
                    {
                        $dataSave = $data;
                        $dataSave['At'] = date('Y-m-d H:i:s');
                        $this->db->insert('db_ticketing.received_details',$dataSave);
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }

    public function ProcessTransferTo($data_arr){
        if (count($data_arr) > 0) {
            for ($i=0; $i < count($data_arr); $i++) { 
               $data_arr_index =$data_arr[$i];
               $action = $data_arr_index['action'];
               $this->TableReceivedAction($data_arr_index);
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
              $data_received[$i]['ReceivedAtTracking'] = $this->__set_datetime_modal_tracking($data_received[$i]['ReceivedAt']);
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

}
