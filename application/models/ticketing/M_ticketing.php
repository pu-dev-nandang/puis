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
                b.Photo,qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.ID,ca.Descriptions as CategoryDescriptions
                from db_ticketing.ticket as a 
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                join db_employees.employees as b on a.RequestedBy = b.NIP
                where TicketStatus = 1 and DATE_FORMAT(RequestedAt,"%Y-%m-%d") = CURDATE()
                '.$Addwhere.' 
                and (select count(*) as total from db_ticketing.received where TicketID = a.ID) = 1
                order by a.ID desc
                ';
        $query = $this->db->query($sql,array())->result_array();
        $rs['count'] = count($query);
        for ($i=0; $i < $rs['count']; $i++) { 
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

            $DateRequest = date('d M Y', strtotime($query[$i]['RequestedAt']));
            $TimeRequest = date('H:i', strtotime($query[$i]['RequestedAt']));
            $query[$i]['RequestedAt'] = $DateRequest.' '.$TimeRequest;
            $query[$i]['setTicket'] = ($this->m_general->auth($query[$i]['DepartmentIDDestination'],$NIP)) ? 'write' : '';

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
                b.Photo,qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.ID,ca.Descriptions as CategoryDescriptions
                from db_ticketing.ticket as a 
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                join db_employees.employees as b on a.RequestedBy = b.NIP
                where TicketStatus = 1 and DATE_FORMAT(RequestedAt,"%Y-%m-%d") < CURDATE()
                '.$Addwhere.'
                and (select count(*) as total from db_ticketing.received where TicketID = a.ID) = 1
                order by a.ID desc
                ';
        $query = $this->db->query($sql,array())->result_array();
        $rs['count'] = count($query);
        for ($i=0; $i < $rs['count']; $i++) { 
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

            $DateRequest = date('d M Y', strtotime($query[$i]['RequestedAt']));
            $TimeRequest = date('H:i', strtotime($query[$i]['RequestedAt']));
            $query[$i]['RequestedAt'] = $DateRequest.' '.$TimeRequest;

            $query[$i]['setTicket'] = ($this->m_general->auth($query[$i]['DepartmentIDDestination'],$NIP)) ? 'write' : '';
            $token = $this->jwt->encode($query[$i],"UAP)(*");
            $query[$i]['token'] =  $token;
        }
        $rs['data'] = $query;
        return $rs;
    }

    public function rest_progress_ticket($dataToken)
    {

    }

    public function getDataTicketBy($arr){
        // array by ID or NoTicket

        $strWhere = '';
        foreach ($arr as $key => $value) {
            $AndOrWhere = ($strWhere == '') ? 'where ' : ' and ';
            $strWhere .= $AndOrWhere.'a.'.$key.' = "'.$value.'"';
        }
        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";
        $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo as PhotoRequested,qdj.NameDepartment as NameDepartmentDestination,qdj.ID as DepartmentIDDestination,a.CategoryID,ca.Descriptions as CategoryDescriptions,a.ID
                from db_ticketing.ticket as a 
                join db_ticketing.category as ca on a.CategoryID = ca.ID
                '.$this->m_general->QueryDepartmentJoin('ca.DepartmentID').'
                join db_employees.employees as b on a.RequestedBy = b.NIP
                '.$strWhere.'
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
        $G_dt = $this->getDataTicketBy(['NoTicket' => $NoTicket]);
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

        $sql = 'select a.*,b.Descriptions as CategoryDescriptions,b.DepartmentID as DepartmentIDDestination,qdj.NameDepartment as NameDepartmentDestination
                from db_ticketing.received as a join db_ticketing.category as b on a.CategoryReceivedID = b.ID
                '.$this->m_general->QueryDepartmentJoin('b.DepartmentID').'
                '.$strWhere.'
        ';

        $query = $this->db->query($sql,array())->result_array();
        return $query;
    }

}
