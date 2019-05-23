<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_rest2 extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('master/m_master');
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

    public function send_notif_browser()
    {
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                /*
                    Parameter
                    var data = {
                        'auth' => 's3Cr3T-G4N',
                        'Logging' : {fieldTable}, //  field CreatedBy,salah satu field URL,Title,Description required
                        'To' : {
                            'NIP' : [], berbentuk array indeks // boleh salah satu
                            'Div' : [], berbentuk array indeks // boleh salah satu
                        },
                        'Email' : 'Yes', Field ini bersifat tentative
                    };
        
                */
                if (array_key_exists('Logging', $dataToken) &&  array_key_exists('To', $dataToken)) {
                    $arr_to = array();
                    $arr_to_email = array();
                    $Logging = (array) json_decode(json_encode($dataToken['Logging']),true);
                        if (!array_key_exists('CreatedBy', $Logging)  && !array_key_exists('Title', $Logging) && !array_key_exists('Description', $Logging) ) {
                            echo '{"status":"999","message":"Parameter not match"}';
                            die();
                        }

                            // Data Employees
                            $CreatedBy = $Logging['CreatedBy'];
                            $G_emp = $this->m_master->caribasedprimary('db_employees.employees','NIP',$CreatedBy);

                        if (!array_key_exists('Icon', $Logging)) {
                               $url = base_url('uploads/employees/'.$G_emp[0]['Photo']);
                               $img_profile = ($this->is_url_exist($url) && $G_emp[0]['Photo']!='')
                                   ? $url
                                   : url_server_ws.'/images/icon/lecturer.png';
                               $Logging['Icon'] = $img_profile;   
                        }


                        if (!array_key_exists('CreatedName', $Logging)) {
                            $Logging['CreatedName'] = $G_emp[0]['Name']; 
                        }

                        if (!array_key_exists('CreatedAt', $Logging)) {
                            $Logging['CreatedAt'] = date('Y-m-d H:i:s'); 
                        }

                        // check url Logging terisi
                            $bool = false;
                            $arr_url = array('URLDirect','URLDirectStudent','URLDirectLecturer','URLDirectLecturerKaprodi');
                            for ($i=0; $i < count($arr_url); $i++) { 
                                if (!$bool) {
                                    if (array_key_exists($arr_url[$i], $Logging)) {
                                        // check terisi atau tidak
                                        if ($Logging[$arr_url[$i]] != '' && $Logging[$arr_url[$i]] != null && (!empty($Logging[$arr_url[$i]]))) {
                                            $bool = true;
                                        }
                                    }
                                }
                            }

                            if (!$bool) {
                                echo '{"status":"999","message":"Error in parameter URL"}';
                                die();
                            }

                        // check parameter To
                            $To = (array) json_decode(json_encode($dataToken['To']),true);
                            if (array_key_exists('Div', $To) || array_key_exists('NIP', $To) ) {
                                $Div = $To['Div'];
                                if (array_key_exists('Div',$To)) {
                                    if (!is_array($Div)) {
                                        echo '{"status":"999","message":"Error in parameter To"}';
                                        die();
                                    }
                                    else
                                    {
                                        $Div = (array) json_decode(json_encode($To['Div']),true);
                                        for ($i=0; $i < count($Div); $i++) { 
                                           $sql = 'select a.NIP,a.Name,SPLIT_STR(a.PositionMain, ".", 1) as PositionMain1,
                                                   SPLIT_STR(a.PositionMain, ".", 2) as PositionMain2,
                                                         a.StatusEmployeeID
                                                    FROM   db_employees.employees as a
                                                    where SPLIT_STR(a.PositionMain, ".", 1) = ? and a.StatusEmployeeID != -1        
                                                ';
                                            $query=$this->db->query($sql, array($Div[$i]))->result_array();
                                            for ($j=0; $j < count($query); $j++) { 
                                                $NIP = $query[$j]['NIP'];
                                                // search in arr_to
                                                    $bool =true;
                                                    for ($k=0; $k < count($arr_to); $k++) { 
                                                        if ($NIP == $arr_to[$k]) {
                                                           $bool =false;
                                                           break;
                                                        }
                                                    }

                                                    if ($bool) {
                                                        $arr_to[] = $NIP;
                                                    }
                                            }
                                        }
                                    }
                                }

                                if (array_key_exists('NIP',$To)) {
                                    $NIP = $To['NIP'];
                                    if (!is_array($NIP)) {
                                        echo '{"status":"999","message":"Error in parameter To"}';
                                        die();
                                    }
                                    else
                                    {
                                        $NIP_arr = (array) json_decode(json_encode($To['NIP']),true);
                                        for ($i=0; $i < count($NIP_arr); $i++) {
                                            $NIP = $NIP_arr[$i]; 
                                            $bool =true;
                                            for ($k=0; $k < count($arr_to); $k++) { 
                                                if ($NIP == $arr_to[$k]) {
                                                   $bool =false;
                                                   break;
                                                }
                                            }

                                            if ($bool) {
                                                $arr_to[] = $NIP;
                                            }
                                        }
                                    }

                                }
                                
                            }
                            else
                            {
                                echo '{"status":"999","message":"Error in parameter To"}';
                                die();
                            }    

                    //============= Logging ==========
                    // Insert Logging
                        $this->db->insert('db_notifikasi.logging',$Logging);
                        $insert_id_logging = $this->db->insert_id();  

                    // insert ke user
                       for ($i=0; $i < count($arr_to); $i++) { 
                            $Log_arr_ins = array(
                                'IDLogging' => $insert_id_logging,
                                'UserID' => $arr_to[$i],
                            );
                            $this->db->insert('db_notifikasi.logging_user',$Log_arr_ins);
                            // fill arr_to_email
                            $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($arr_to[$i]);
                            if (count($G_emp) > 0) {
                                if ($G_emp[0]['EmailPU'] != '') {
                                    $arr_to_email[] = $G_emp[0]['EmailPU'];
                                }
                                
                            }
                            
                        }     

                    if (array_key_exists('Email', $dataToken)) {
                        if ($dataToken['Email'] == 'Yes') {
                            // send email
                            $data = array(
                                'auth' => 's3Cr3T-G4N',
                                'to' => implode(',', $arr_to_email),
                                'subject' => strip_tags($Logging['Title']),
                                'text' => $Logging['Description'],
                            );

                            $url = url_pas.'rest/__sendEmail';
                            $token = $this->jwt->encode($data,"UAP)(*");
                            $this->m_master->apiservertoserver($url,$token);
                        }
                        else
                        {
                            // No send Email
                        }
                    }

                    echo json_encode(array(1));
                }
                else
                {
                    echo '{"status":"999","message":"Parameter not match"}';
                }
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        catch(Exception $e) {
             // handling orang iseng
             echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function remove_file()
    {
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $DeleteDb = $dataToken['DeleteDb'];
                $filePath = $dataToken['filePath'];
                $filePath = str_replace('-', '\\', $filePath);
                $bool = false;
                $DeleteDb = (array) json_decode(json_encode($DeleteDb),true);
                if ($DeleteDb['auth'] == 'Yes') {
                    /* Type Field 
                        0 : String
                        1 : Array
                    */
                        $detail = $DeleteDb['detail'];
                        $table = $detail['table'];
                        $idtable = $detail['idtable'];
                        $field = $detail['field'];
                        $typefield = $detail['typefield'];
                        $delimiter = $detail['delimiter'];
                        $fieldwhere = $detail['fieldwhere'];
                      $G_data = $this->m_master->caribasedprimary($table,$fieldwhere,$idtable);
                      if ($typefield == 0) {
                          if ($delimiter != '' && $delimiter != null) {
                             $arr_file = explode($delimiter, $G_data[0][$field]);
                             // get filename
                             $arr_temp = explode('\\', $filePath);
                             $keyArr = count($arr_temp) - 1;
                             $filename = $arr_temp[$keyArr];
                             $arr_rs = array();
                             for ($i=0; $i < count($arr_file); $i++) { 
                                if ($filename != $arr_file[$i]) {
                                    $arr_rs[] = $arr_file[$i];
                                }
                             }

                             $rs = (count($arr_rs) == 0) ? '' : implode($delimiter, $arr_rs);
                             $dataSave = array(
                                $field => $rs
                             );

                             $this->db->where($fieldwhere,$idtable);
                             $this->db->update($table,$dataSave);
                             $bool = true;
                          }
                      }
                      else if ($typefield == 1) {
                          $arr_file = (array) json_decode($G_data[0][$field],true);
                          // get filename
                          $arr_temp = explode('\\', $filePath);
                          $keyArr = count($arr_temp) - 1;
                          $filename = $arr_temp[$keyArr];

                          $arr_rs = array();
                          for ($i=0; $i < count($arr_file); $i++) { 
                             if ($filename != $arr_file[$i]) {
                                 $arr_rs[] = $arr_file[$i];
                             }
                          }

                          $rs = (count($arr_rs) == 0) ? NULL : json_encode($arr_rs);
                          $dataSave = array(
                             $field => $rs
                          );
                          $this->db->where($fieldwhere,$idtable);
                          $this->db->update($table,$dataSave);
                          $bool = true;
                      }

                }
                $path = FCPATH.'uploads\\'.$filePath;
                unlink($path);
                echo json_encode(1);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        catch(Exception $e) {
             // handling orang iseng
             echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function get_data_po($Status)
    {
        try {
                $dataToken = $this->getInputToken2();
                $auth = $this->m_master->AuthAPI($dataToken);
                if ($auth) {
                     $this->load->model('budgeting/m_pr_po');
                    $requestData= $_REQUEST;
                    $sqltotalData = 'select count(*) as total from db_purchasing.po_create';
                    $StatusQuery = ($Status == 'All') ? '' : ' where Status = '.$Status;
                    $sqltotalData .= $StatusQuery;
                    $querytotalData = $this->db->query($sqltotalData)->result_array();
                    $totalData = $querytotalData[0]['total'];

                    $StatusQuery = ($Status == 'All') ? '' : ' and Status = '.$Status;
                    $sql = 'select * from (
                                select if(a.TypeCreate = 1,"PO","SPK") as TypeCode,a.Code,a.ID_pre_po_supplier,b.CodeSupplier,
                                    c.NamaSupplier,c.PICName as PICSupplier,c.Alamat as AlamatSupplier,
                                    a.JsonStatus,
                                    if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done",if(a.Status = -1,"Reject","Cancel") ) )) as StatusName,a.CreatedBy,d.Name as NameCreateBy,a.CreatedAt,a.PostingDate
                                from db_purchasing.po_create as a
                                left join db_purchasing.pre_po_supplier as b on a.ID_pre_po_supplier = b.ID
                                left join db_purchasing.m_supplier as c on b.CodeSupplier = c.CodeSupplier
                                left join db_employees.employees as d on a.CreatedBy = d.NIP     
                            )aa
                           ';

                    $sql.= ' where (Code LIKE "%'.$requestData['search']['value'].'%" or TypeCode LIKE "'.$requestData['search']['value'].'%" or NamaSupplier LIKE "%'.$requestData['search']['value'].'%" or CodeSupplier LIKE "'.$requestData['search']['value'].'%"
                          or NameCreateBy LIKE "'.$requestData['search']['value'].'%" or CreatedBy LIKE "'.$requestData['search']['value'].'%"  
                        ) '.$StatusQuery ;
                    $sql.= ' ORDER BY Code Desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
                    $query = $this->db->query($sql)->result_array();

                    $No = $requestData['start'] + 1;
                    $G_Approver = $this->m_pr_po->Get_m_Approver_po();
                    if (array_key_exists('length', $dataToken)) {
                        $Count_G_Approver = $dataToken['length'];
                    }
                    else
                    {
                        $Count_G_Approver = count($G_Approver);
                    }
                    $data = array();
                    for($i=0;$i<count($query);$i++){
                        $nestedData=array();
                        $row = $query[$i];
                        $nestedData[] = $No;
                        $nestedData[] = $row['Code'];
                        $nestedData[] = $row['TypeCode'];
                        $nestedData[] = $row['CodeSupplier'].' || '.$row['NamaSupplier'];
                        $nestedData[] = $row['StatusName'];
                        $nestedData[] = '';
                        $JsonStatus = (array)json_decode($row['JsonStatus'],true);
                        $arr = array();
                        if (count($JsonStatus) > 0) {
                            for ($j=1; $j < count($JsonStatus); $j++) {
                                $getName = $this->m_master->caribasedprimary('db_employees.employees','NIP',$JsonStatus[$j]['NIP']);
                                $Name = $getName[0]['Name'];
                                $StatusInJson = $JsonStatus[$j]['Status'];
                                switch ($StatusInJson) {
                                    case '1':
                                        $stjson = '<i class="fa fa-check" style="color: green;"></i>';
                                        break;
                                    case '2':
                                        $stjson = '<i class="fa fa-times" aria-hidden="true" style="color: red;"></i>';
                                        break;
                                    default:
                                        $stjson = "-";
                                        break;
                                }
                                $arr[] = $stjson.'<br>'.'Approver : '.$Name.'<br>'.'Approve At : '.$JsonStatus[$j]['ApproveAt'];
                            }
                        }

                        $c = $Count_G_Approver - count($arr);
                        for ($l=0; $l < $c; $l++) { 
                             $arr[] = '-';
                        }

                        $nestedData = array_merge($nestedData,$arr);
                        $nestedData[] = $row['NameCreateBy'];
                        $data[] = $nestedData;
                        $No++;
                    }

                    $json_data = array(
                        "draw"            => intval( $requestData['draw'] ),
                        "recordsTotal"    => intval($totalData),
                        "recordsFiltered" => intval($totalData ),
                        "data"            => $data,
                    );
                    echo json_encode($json_data);
                }
                else
                {
                    // handling orang iseng
                    echo '{"status":"999","message":"Not Authorize"}';
                }
            }
            catch(Exception $e) {
                 // handling orang iseng
                 echo '{"status":"999","message":"Not Authorize"}';
            }
    }

    public function Get_data_po_by_Code()
    {
        try {
                $dataToken = $this->getInputToken2();
                $auth = $this->m_master->AuthAPI($dataToken);
                if ($auth) {
                    $this->load->model('budgeting/m_pr_po');
                    $Code = $dataToken['Code'];
                    $data = $this->m_pr_po->Get_data_po_by_Code($Code);
                    echo json_encode($data);
                }
                else
                {
                    // handling orang iseng
                    echo '{"status":"999","message":"Not Authorize"}';
                }
            }
            catch(Exception $e) {
                 // handling orang iseng
                 echo '{"status":"999","message":"Not Authorize"}';
            }
    }

    public function Get_supplier_po_by_Code()
    {
        try {
                $dataToken = $this->getInputToken2();
                $auth = $this->m_master->AuthAPI($dataToken);
                if ($auth) {
                    $this->load->model('budgeting/m_pr_po');
                    $Code = $dataToken['Code'];
                    $data = $this->m_pr_po->Get_supplier_po_by_Code($Code);
                    echo json_encode($data);
                }
                else
                {
                    // handling orang iseng
                    echo '{"status":"999","message":"Not Authorize"}';
                }
            }
            catch(Exception $e) {
                 // handling orang iseng
                 echo '{"status":"999","message":"Not Authorize"}';
            }
    }

    public function ajax_terbilang()
    {
        try {
                $dataToken = $this->getInputToken2();
                $auth = $this->m_master->AuthAPI($dataToken);
                if ($auth) {
                    $terbilang = $this->m_master->moneySay($dataToken['bilangan']);
                    $terbilang = trim(ucwords($terbilang));
                    echo json_encode($terbilang);
                }
                else
                {
                    // handling orang iseng
                    echo '{"status":"999","message":"Not Authorize"}';
                }
            }
            catch(Exception $e) {
                 // handling orang iseng
                 echo '{"status":"999","message":"Not Authorize"}';
            }
    }

    public function ajax_dayOfDate()
    {
        try {
                $dataToken = $this->getInputToken2();
                $auth = $this->m_master->AuthAPI($dataToken);
                if ($auth) {
                    $rs = '';
                    $hari = array ( 1 =>    'Senin',
                                'Selasa',
                                'Rabu',
                                'Kamis',
                                'Jumat',
                                'Sabtu',
                                'Minggu'
                            );
                    $Date = $dataToken['Date'];
                    $Date = date("Y-m-d", strtotime($Date));
                    $num = date('N', strtotime($Date)); 
                    $NameDay = $hari[$num];
                    echo json_encode($NameDay);
                }
                else
                {
                    // handling orang iseng
                    echo '{"status":"999","message":"Not Authorize"}';
                }
            }
            catch(Exception $e) {
                 // handling orang iseng
                 echo '{"status":"999","message":"Not Authorize"}';
            }
    }

    public function Get_spk_pembukaan()
    {
        try {
                $dataToken = $this->getInputToken2();
                $auth = $this->m_master->AuthAPI($dataToken);
                if ($auth) {
                    $rs = '';
                    $Date = $dataToken['Date'];
                    // get hari
                        $data = array(
                              'Date' => $Date,
                              'auth' => 's3Cr3T-G4N', 
                        ); 
                        $url = url_pas.'rest2/__ajax_dayOfDate';
                        $token = $this->jwt->encode($data,"UAP)(*");
                        $this->m_master->apiservertoserver($url,$token);
                        $NameDay = $this->m_master->apiservertoserver($url,$token);
                    // terbilang tanggal
                         $bilangan = date("d", strtotime($Date));
                         $data = array(
                               'bilangan' => $bilangan,
                               'auth' => 's3Cr3T-G4N', 
                         ); 
                         $url = url_pas.'rest2/__ajax_terbilang';
                         $token = $this->jwt->encode($data,"UAP)(*");
                         $this->m_master->apiservertoserver($url,$token);
                         $Tanggal = $this->m_master->apiservertoserver($url,$token);

                    // Nama Bulan
                        $Date_ = date("Y-m-d", strtotime($Date));
                        $DateIndo = $this->m_master->getIndoBulan($Date_);
                        $DateIndo = explode(' ', $DateIndo);
                        $NmBulan = $DateIndo[1];

                    // terbilang tahun
                         $bilangan = date("Y", strtotime($Date));
                         $data = array(
                               'bilangan' => $bilangan,
                               'auth' => 's3Cr3T-G4N', 
                         ); 
                         $url = url_pas.'rest2/__ajax_terbilang';
                         $token = $this->jwt->encode($data,"UAP)(*");
                         $this->m_master->apiservertoserver($url,$token);
                         $Tahun = $this->m_master->apiservertoserver($url,$token);          
                    $rs = 'Pada hari ini '.$NameDay[0].', tanggal '.$Tanggal[0].' Bulan '.$NmBulan.', tahun '.$Tahun[0].', kami yang bertanda tangan dibawah ini :';     
                    echo json_encode($rs);
                }
                else
                {
                    // handling orang iseng
                    echo '{"status":"999","message":"Not Authorize"}';
                }
            }
            catch(Exception $e) {
                 // handling orang iseng
                 echo '{"status":"999","message":"Not Authorize"}';
            }
    }

    public function approve_po()
    {
        try {
                $dataToken = $this->getInputToken2();
                $auth = $this->m_master->AuthAPI($dataToken);
                if ($auth) {
                    $this->load->model('budgeting/m_pr_po');
                    $rs = array('Status' => 1,'Change' => 0,'msg' => '');
                    $po_data = $dataToken['po_data'];
                    $CheckPerubahanData = $this->m_pr_po->CheckPerubahanData_PO_Created($po_data);
                    if ($CheckPerubahanData) {
                        $Code = $dataToken['Code'];
                        $CodeUrl = str_replace('/', '-', $Code);
                        $approval_number = $dataToken['approval_number'];
                        $NIP = $dataToken['NIP'];
                        $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
                        $NameFor_NIP = $G_emp[0]['Name'];
                        $action = $dataToken['action'];

                        // get data
                        $G_data = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$Code);

                        $keyJson = $approval_number - 1; // get array index json
                        $JsonStatus = (array)json_decode($G_data[0]['JsonStatus'],true);

                        // get data update to approval
                        $arr_upd = $JsonStatus[$keyJson];

                        if ($arr_upd['NIP'] == $NIP || $arr_upd['Representedby'] == $NIP) {
                            $arr_upd['Status'] = ($action == 'approve') ? 1 : 2;
                            $arr_upd['ApproveAt'] = ($action == 'approve') ? date('Y-m-d H:i:s') : '-';
                            $JsonStatus[$keyJson] = $arr_upd;
                            $datasave = array(
                                'JsonStatus' => json_encode($JsonStatus),
                            );

                            // check all status for update data
                            $boolApprove = true;
                            for ($i=0; $i < count($JsonStatus); $i++) { 
                                $arr = $JsonStatus[$i];
                                $Status = $arr['Status'];
                                if ($Status == 2 || $Status == 0) {
                                    $boolApprove = false;
                                    break;
                                }
                            }

                            if ($boolApprove) {
                                $datasave['Status'] = 2;
                                $datasave['PostingDate'] = date('Y-m-d H:i:s');
                            }
                            else
                            {
                                $boolReject = false;
                                for ($i=0; $i < count($JsonStatus); $i++) { 
                                    $arr = $JsonStatus[$i];
                                    $Status = $arr['Status'];
                                    if ($Status == 2) {
                                        $boolReject = true;
                                        break;
                                    }
                                }

                                if ($boolReject) {
                                    $NoteDel = $dataToken['NoteDel'];
                                    $Notes = $G_data[0]['Notes']."\n".$NoteDel;
                                    $datasave['Status'] = -1;
                                    // $datasave['Notes'] = $Notes;
                                }
                                else
                                {
                                    // Notif to next step approval & User
                                        $NIPApprovalNext = $JsonStatus[($keyJson+1)]['NIP'];
                                        // Send Notif for next approval
                                            $data = array(
                                                'auth' => 's3Cr3T-G4N',
                                                'Logging' => array(
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Approval PO : '.$Code,
                                                                'Description' => 'Please approve PO '.$Code,
                                                                'URLDirect' => 'global/purchasing/transaction/po/list/'.$CodeUrl,
                                                                'CreatedBy' => $NIP,
                                                              ),
                                                'To' => array(
                                                          'NIP' => array($NIPApprovalNext),
                                                        ),
                                                'Email' => 'No', 
                                            );

                                            $url = url_pas.'rest2/__send_notif_browser';
                                            $token = $this->jwt->encode($data,"UAP)(*");
                                            $this->m_master->apiservertoserver($url,$token);

                                        // Send Notif for user 
                                            $data = array(
                                                'auth' => 's3Cr3T-G4N',
                                                'Logging' => array(
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  PO '.$Code.' has been Approved',
                                                                'Description' => 'PR '.$Code.' has been approved by '.$NameFor_NIP,
                                                                'URLDirect' => 'global/purchasing/transaction/po/list/'.$CodeUrl,
                                                                'CreatedBy' => $NIP,
                                                              ),
                                                'To' => array(
                                                          'NIP' => array($JsonStatus[0]['NIP']),
                                                        ),
                                                'Email' => 'No', 
                                            );

                                            $url = url_pas.'rest2/__send_notif_browser';
                                            $token = $this->jwt->encode($data,"UAP)(*");
                                            $this->m_master->apiservertoserver($url,$token); 
                                }
                            }

                            $this->db->where('Code',$Code);
                            $this->db->update('db_purchasing.po_create',$datasave); 

                            // insert to po_circulation_sheet
                                $Desc = ($arr_upd['Status'] == 1) ? 'Approve' : 'Reject';
                                if (array_key_exists('Status', $datasave)) {
                                    if ($datasave['Status'] == 2) {
                                        $Desc = "All Approve and posting date at : ".$datasave['PostingDate'];

                                        // Notif All Approve to JsonStatus allkey
                                            $arr_to = array();
                                            for ($i=0; $i < count($JsonStatus); $i++) { 
                                                $arr_to[] = $JsonStatus[$i]['NIP'];
                                            }

                                            $data = array(
                                                'auth' => 's3Cr3T-G4N',
                                                'Logging' => array(
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> PO '.$Code.' has been done',
                                                                'Description' => 'PO '.$Code.' has been done',
                                                                'URLDirect' => 'global/purchasing/transaction/po/list/'.$CodeUrl,
                                                                'CreatedBy' => $NIP,
                                                              ),
                                                'To' => array(
                                                          'NIP' => $arr_to,
                                                        ),
                                                'Email' => 'No', 
                                            );

                                            $url = url_pas.'rest2/__send_notif_browser';
                                            $token = $this->jwt->encode($data,"UAP)(*");
                                            $this->m_master->apiservertoserver($url,$token);

                                        // Notif to Purchasing 
                                            $data = array(
                                                'auth' => 's3Cr3T-G4N',
                                                'Logging' => array(
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> PO '.$Code.' has been done',
                                                                'Description' => 'PO '.$Code.' has been done',
                                                                'URLDirect' => 'global/purchasing/transaction/po/list/'.$CodeUrl,
                                                                'CreatedBy' => $NIP,
                                                              ),
                                                'To' => array(
                                                          'Div' => array(4),
                                                        ),
                                                'Email' => 'No', 
                                            );

                                            $url = url_pas.'rest2/__send_notif_browser';
                                            $token = $this->jwt->encode($data,"UAP)(*");
                                            $this->m_master->apiservertoserver($url,$token);   
                                    }
                                }

                                if ($arr_upd['Status'] == 2) {
                                    if ($dataToken['NoteDel'] != '' || $dataToken['NoteDel'] != null) {
                                        $Desc .= '<br>{'.$dataToken['NoteDel'].'}';
                                    }

                                    // Notif Reject to JsonStatus key 0
                                        // Send Notif for user 
                                            $data = array(
                                                'auth' => 's3Cr3T-G4N',
                                                'Logging' => array(
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> PO '.$Code.' has been Rejected',
                                                                'Description' => 'PO '.$Code.' has been Rejected by '.$NameFor_NIP,
                                                                'URLDirect' => 'global/purchasing/transaction/po/list/'.$CodeUrl,
                                                                'CreatedBy' => $NIP,
                                                              ),
                                                'To' => array(
                                                          'NIP' => array($JsonStatus[0]['NIP']),
                                                        ),
                                                'Email' => 'No', 
                                            );

                                            $url = url_pas.'rest2/__send_notif_browser';
                                            $token = $this->jwt->encode($data,"UAP)(*");
                                            $this->m_master->apiservertoserver($url,$token);
                                }
                                
                                $this->m_pr_po->po_circulation_sheet($Code,$Desc,$NIP);

                        }
                        else
                        {
                            $msg = 'Not Authorize';
                        }


                    }
                    else
                    {
                        $rs['Change'] = 1;
                    }

                    echo json_encode($rs);

                }
                else
                {
                    // handling orang iseng
                    echo '{"status":"999","message":"Not Authorize"}';
                }
            }
            catch(Exception $e) {
                 // handling orang iseng
                 echo '{"status":"999","message":"Not Authorize"}';
            }
    }
}
