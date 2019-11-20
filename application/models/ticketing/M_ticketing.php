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
        $NoTicket = $this->generate_code_ticketing($dataSave['RequestedBy']);
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

    private function generate_code_ticketing($Request)
    {
        /* method
           Code : IT-19000001
           IT : Abbreviation
           19 : Year
           0000001 : Increment 
        */
        $NIP = $Request;
        $GetAbbrEmployees = $this->m_master->SearchEmployeesByNIP($NIP);
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
        
        $Code = '';
        $Year = date('Y');
        $YearSubStr = substr($Year, 2,2);
        $MaxLengthINC = 7;

        $sql = 'select * from db_ticketing.ticket
                where Year(RequestedAt) = '.$Year.'
                order by ID desc
                limit 1';
        $query = $this->db->query($sql,array())->result_array();
        if (count($query) == 1) {
           $LastCode = $query[0]['NoTicket']; 
           $explode = explode('-', $LastCode);
           $CodeNumber = (int) $explode[1];
           $CodeNumber = $CodeNumber + 1;
           $strlen = strlen($CodeNumber);
           $strINC = $CodeNumber;
           for ($i=0; $i < $MaxLengthINC - $strlen; $i++) { 
               $strINC = '0'.$strINC;
           }

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

    public function rest_open_ticket()
    {
        $rs = [];
        $pathfolder = ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') ? "pcam/ticketing/" : "localhost/ticketing/";
        $sql = 'select a.NoTicket,a.Title,Message,CONCAT("'.$pathfolder.'",a.Files) as Files,b.Name as NameRequested,a.RequestedAt,
                b.Photo,a.ID
                from db_ticketing.ticket as a 
                join db_employees.employees as b on a.RequestedBy = b.NIP
                where TicketStatus = 1
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
        }
        $rs['data'] = $query;
        return $rs;
    }
}
