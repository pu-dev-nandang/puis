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
                    $StatusQuery = ($Status == 'All') ? '' : ' and Status = '.$Status;
                    $sqltotalData = 'select count(*) as total  from (
                                select if(a.TypeCreate = 1,"PO","SPK") as TypeCode,a.Code,a.ID_pre_po_supplier,b.CodeSupplier,
                                    c.NamaSupplier,c.PICName as PICSupplier,c.Alamat as AlamatSupplier,
                                    a.JsonStatus,
                                    if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done",if(a.Status = -1,"Reject","Cancel") ) )) as StatusName,a.CreatedBy,d.Name as NameCreateBy,a.CreatedAt,a.PostingDate,g.PRCode
                                from db_purchasing.po_create as a
                                left join db_purchasing.pre_po_supplier as b on a.ID_pre_po_supplier = b.ID
                                left join db_purchasing.m_supplier as c on b.CodeSupplier = c.CodeSupplier
                                left join db_employees.employees as d on a.CreatedBy = d.NIP
                                left join db_purchasing.po_detail as e on a.Code = e.Code
                                left join db_purchasing.pre_po_detail as f on e.ID_pre_po_detail = f.ID
                                left join db_budgeting.pr_detail as g on f.ID_pr_detail = g.ID
                                group by a.Code     
                            )aa
                           ';

                    $sqltotalData.= ' where (Code LIKE "%'.$requestData['search']['value'].'%" or TypeCode LIKE "'.$requestData['search']['value'].'%" or NamaSupplier LIKE "%'.$requestData['search']['value'].'%" or CodeSupplier LIKE "'.$requestData['search']['value'].'%"
                          or NameCreateBy LIKE "'.$requestData['search']['value'].'%" or CreatedBy LIKE "'.$requestData['search']['value'].'%" 
                          or PRCode LIKE "'.$requestData['search']['value'].'%"  
                        ) '.$StatusQuery ;

                    $querytotalData = $this->db->query($sqltotalData)->result_array();
                    $totalData = $querytotalData[0]['total'];

                    $StatusQuery = ($Status == 'All') ? '' : ' and Status = '.$Status;
                    $sql = 'select * from (
                                select a.ID as ID_po_create,if(a.TypeCreate = 1,"PO","SPK") as TypeCode,a.Code,a.ID_pre_po_supplier,b.CodeSupplier,
                                    c.NamaSupplier,c.PICName as PICSupplier,c.Alamat as AlamatSupplier,
                                    a.JsonStatus,
                                    if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done",if(a.Status = -1,"Reject","Cancel") ) )) as StatusName,a.CreatedBy,d.Name as NameCreateBy,a.CreatedAt,a.PostingDate,g.PRCode
                                from db_purchasing.po_create as a
                                left join db_purchasing.pre_po_supplier as b on a.ID_pre_po_supplier = b.ID
                                left join db_purchasing.m_supplier as c on b.CodeSupplier = c.CodeSupplier
                                left join db_employees.employees as d on a.CreatedBy = d.NIP
                                left join db_purchasing.po_detail as e on a.Code = e.Code
                                left join db_purchasing.pre_po_detail as f on e.ID_pre_po_detail = f.ID
                                left join db_budgeting.pr_detail as g on f.ID_pr_detail = g.ID
                                group by a.Code      
                            )aa
                           ';

                    $sql.= ' where (Code LIKE "%'.$requestData['search']['value'].'%" or TypeCode LIKE "'.$requestData['search']['value'].'%" or NamaSupplier LIKE "%'.$requestData['search']['value'].'%" or CodeSupplier LIKE "'.$requestData['search']['value'].'%"
                          or NameCreateBy LIKE "'.$requestData['search']['value'].'%" or CreatedBy LIKE "'.$requestData['search']['value'].'%" 
                          or PRCode LIKE "'.$requestData['search']['value'].'%"  
                        ) '.$StatusQuery ;
                    $sql.= ' ORDER BY ID_po_create Desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
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
                        // find PR in po_detail
                            $arr_temp = array();
                            $sql_get_pr = 'select a.ID,a.ID_m_catalog,b.Item,c.ID as ID_pre_po_detail,d.Code,a.PRCode
                            from db_budgeting.pr_detail as a join db_purchasing.m_catalog as b on a.ID_m_catalog = b.ID
                            left join db_purchasing.pre_po_detail as c on a.ID = c.ID_pr_detail
                            left join db_purchasing.po_detail as d on c.ID = d.ID_pre_po_detail
                            where d.Code = ?
                            ';
                            $query_get_pr=$this->db->query($sql_get_pr, array($row['Code']))->result_array();
                            for ($j=0; $j < count($query_get_pr); $j++) { 
                                if (count($arr_temp) == 0) {
                                    $arr_temp[] = $query_get_pr[$j]['PRCode'];
                                }
                                else
                                {
                                    // check exist
                                    $bool = true;
                                    for ($k=0; $k < count($arr_temp); $k++) { 
                                        if ($arr_temp[$k]==$query_get_pr[$j]['PRCode']) {
                                            $bool = false;    
                                            break;
                                        }
                                    }

                                    if ($bool) {
                                        $arr_temp[] = $query_get_pr[$j]['PRCode'];
                                    }

                                }
                            }

                        $nestedData[] = $arr_temp;
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
                        // $this->m_master->apiservertoserver($url,$token);
                        $NameDay = $this->m_master->apiservertoserver($url,$token);
                    // terbilang tanggal
                         $bilangan = date("d", strtotime($Date));
                         $data = array(
                               'bilangan' => $bilangan,
                               'auth' => 's3Cr3T-G4N', 
                         ); 
                         $url = url_pas.'rest2/__ajax_terbilang';
                         $token = $this->jwt->encode($data,"UAP)(*");
                         // $this->m_master->apiservertoserver($url,$token);
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
                         // $this->m_master->apiservertoserver($url,$token);
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
                        $urlS = ($G_data[0]['TypeCreate'] == 1) ? 'po' : 'spk';

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
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  Approval PO/SPK : '.$Code,
                                                                'Description' => 'Please approve PO/SPK '.$Code,
                                                                'URLDirect' => 'global/purchasing/transaction/'.$urlS.'/list/'.$CodeUrl,
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
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>  PO/SPK '.$Code.' has been Approved',
                                                                'Description' => 'PO/SPK '.$Code.' has been approved by '.$NameFor_NIP,
                                                                'URLDirect' => 'global/purchasing/transaction/'.$urlS.'/list/'.$CodeUrl,
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
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> PO/SPK '.$Code.' has been done',
                                                                'Description' => 'PO/SPK '.$Code.' has been done',
                                                                'URLDirect' => 'global/purchasing/transaction/'.$urlS.'/list/'.$CodeUrl,
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
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> PO/SPK '.$Code.' has been done',
                                                                'Description' => 'PO/SPK '.$Code.' has been done',
                                                                'URLDirect' => 'global/purchasing/transaction/'.$urlS.'/list/'.$CodeUrl,
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

                                        // insert to po_invoice_status
                                            $Code_po_create = $Code;
                                            $G_po_detail = $this->m_master->caribasedprimary('db_purchasing.po_detail','Code',$Code_po_create);
                                            $InvoicePO = 0; // tambah dengan biaya lain-lain
                                            $AnotherCost = $G_data[0]['AnotherCost'];
                                            $InvoicePO = $InvoicePO + $AnotherCost;
                                            for ($i=0; $i < count($G_po_detail); $i++) { 
                                               $InvoicePO = $InvoicePO + $G_po_detail[$i]['SubTotal'];
                                            }
                                        // check po_invoice_status already exist or not
                                             $G_po_invoice_status = $this->m_master->caribasedprimary('db_purchasing.po_invoice_status','Code_po_create',$Code_po_create);
                                             if (count($G_po_invoice_status) > 0 ) {
                                                  $InvoicePayPO = $G_po_invoice_status[0]['InvoicePayPO']; 
                                                  $InvoiceLeftPO = $InvoicePO - $InvoicePayPO;

                                                  $dtSave = array(
                                                    'InvoicePO' => $InvoicePO,
                                                    'InvoicePayPO' => $InvoicePayPO,
                                                    'InvoiceLeftPO' => $InvoiceLeftPO,
                                                  );
                                                  $this->db->where('Code_po_create',$Code_po_create);
                                                  $this->db->update('db_purchasing.po_invoice_status',$dtSave); 
                                              }
                                              else{
                                                $InvoicePayPO = 0; 
                                                $InvoiceLeftPO = $InvoicePO;
                                                $dtSave = array(
                                                  'Code_po_create' => $Code_po_create,  
                                                  'InvoicePO' => $InvoicePO,
                                                  'InvoicePayPO' => $InvoicePayPO,
                                                  'InvoiceLeftPO' => $InvoiceLeftPO,
                                                );
                                                $this->db->insert('db_purchasing.po_invoice_status',$dtSave);
                                              }   

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
                                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i> PO/SPK '.$Code.' has been Rejected',
                                                                'Description' => 'PO/SPK '.$Code.' has been Rejected by '.$NameFor_NIP,
                                                                'URLDirect' => 'global/purchasing/transaction/'.$urlS.'/list/'.$CodeUrl,
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



    // ==== CRM ====
    public function crudFormCRM(){

        $data_arr = $this->getInputToken();

        if($data_arr['action']=='insertCRM'){

            $dataInsert = (array) $data_arr['dataInsert'];

            $this->db->insert('db_admission.crm',$dataInsert);

            return print_r(1);

        }

    }

    public function crudCRMPeriode(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='readCRMPeriode'){

            $data = $this->db->order_by('Year','DESC')->get('db_admission.crm_period')->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='insertCRMPeriode'){
            $Year = $data_arr['Year'];

            // Cek apakah data ada jika ada maka tidak insert
            $dataCk = $this->db->get_where('db_admission.crm_period',array(
                'Year' => $Year
            ))->result_array();

            $result = array(
              'Status' => '0'
            );
            if(count($dataCk)<=0){
                $this->db->insert('db_admission.crm_period',array(
                    'Year' => $Year
                ));
                $result = array(
                    'Status' => '1'
                );
            }



            return print_r(json_encode($result));
        }
        else if($data_arr['action']=='publishCRMPeriode'){
            $ID = $data_arr['ID'];

            $this->db->set('Status', '0');
            $this->db->update('db_admission.crm_period');
            $this->db->reset_query();

            $this->db->set('Status', '1');
            $this->db->where('ID', $ID);
            $this->db->update('db_admission.crm_period');

            return print_r(1);
        }
        else if($data_arr['action']=='removeCRMPeriode'){
            $ID = $data_arr['ID'];

            // Cek penggunaan ID
            $dataCk_team = $this->db->limit(1)->get_where('db_admission.crm_team',array(
                'PeriodID' => $ID
            ))->result_array();

            $dataCk_crm = $this->db->limit(1)->get_where('db_admission.crm',array(
                'PeriodID' => $ID
            ))->result_array();

            if(count($dataCk_team)>0 || count($dataCk_crm)>0){
                $result = array(
                    'Status' => '0'
                );
            } else {
                $this->db->where('ID', $ID);
                $this->db->delete('db_admission.crm_period');
                $result = array(
                    'Status' => '1'
                );
            }

            return print_r(json_encode($result));

        }
        else if($data_arr['action']=='activeCRMPeriode'){
            $data = $this->db->limit(1)->get_where('db_admission.crm_period',
                array(
                    'Status' => '1'
                ))->result_array();
            return print_r(json_encode($data));
        }
    }

    public function crudCRMTeam(){
        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='insertCRMTeam'){

            $team = (array) $data_arr['team'];
            $member = (array) $data_arr['member'];

            $this->db->insert('db_admission.crm_team',$team);
            $CRMTeamID = $this->db->insert_id();

            if(count($member)>0){
                for($i=0;$i<count($member);$i++){

                    if($member[$i]!=$team['Coordinator']){
                        $dataIns = array(
                            'CRMTeamID' => $CRMTeamID,
                            'NIP' => $member[$i]
                        );

                        $this->db->insert('db_admission.crm_team_member',$dataIns);
                    }



                }
            }

            return print_r(1);

        }
        else if($data_arr['action']=='updateCRMTeam'){

            $ID = $data_arr['ID'];

            $team = (array) $data_arr['team'];
            $member = (array) $data_arr['member'];

            $this->db->where('ID', $ID);
            $this->db->update('db_admission.crm_team',$team);
            $this->db->reset_query();

            $this->db->where('CRMTeamID', $ID);
            $this->db->delete('db_admission.crm_team_member');
            $this->db->reset_query();

            if(count($member)>0){
                for($i=0;$i<count($member);$i++){

                    if($member[$i]!=$team['Coordinator']){
                        $dataIns = array(
                            'CRMTeamID' => $ID,
                            'NIP' => $member[$i]
                        );

                        $this->db->insert('db_admission.crm_team_member',$dataIns);
                    }
                }
            }

            return print_r(1);

        }
        else if($data_arr['action']=='readCRMTeam'){
            $PeriodID = $data_arr['PeriodID'];

            $data = $this->db->query('SELECT ct.*, em.Name AS CoordinatorName, cp.Year, cp.Status FROM db_admission.crm_team ct 
                                                LEFT JOIN db_employees.employees em  ON (em.NIP = ct.Coordinator)
                                                LEFT JOIN db_admission.crm_period cp ON (cp.ID = ct.PeriodID)
                                                WHERE ct.PeriodID = "'.$PeriodID.'" ')->result_array();

            if(count($data)>0){
                for($i=0;$i<count($data);$i++){

                    $data[$i]['Member'] = $this->db->query('SELECT ctm.*, em.Name AS MemberName FROM db_admission.crm_team_member ctm 
                                                                      LEFT JOIN db_employees.employees em 
                                                                      ON (em.NIP = ctm.NIP) 
                                                                      WHERE ctm.CRMTeamID = "'.$data[$i]['ID'].'" ')->result_array();

                }
            }

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='removeCRMTeam'){

            $CRMTeamID = $data_arr['ID'];

            $dataCk = $this->db->get_where('db_admission.crm',array(
                'CRMTeamID' => $CRMTeamID
            ))->result_array();

            $result = array('Status' => '0');

            if(count($dataCk)<=0){
                // Remove Team
                $this->db->where('CRMTeamID', $CRMTeamID);
                $this->db->delete('db_admission.crm_team_member');
                $this->db->reset_query();

                $this->db->where('ID', $CRMTeamID);
                $this->db->delete('db_admission.crm_team');

                $result = array('Status' => '1');
            }

            return print_r(json_encode($result));


        }
    }

    public function crudMarketingActivity(){
        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='ins_MA'){

            $ID = $data_arr['ID'];

            $dataForm = (array) $data_arr['dataForm'];

//            print_r($dataForm);exit;

            $dateEvent = $dataForm['Start'];
            $Month = explode('-',$dateEvent)[1];
            $Year = explode('-',$dateEvent)[0];
            $dataForm['Month'] = $Month;
            $dataForm['Year'] = $Year;

            if($ID!='' && $ID!=null){
                // Update
                $this->db->where('ID', $ID);
                $this->db->update('db_admission.marketing_activity', $dataForm);
            } else {
                // Insert
                $this->db->insert('db_admission.marketing_activity', $dataForm);
                $ID = $this->db->insert_id();
            }


            // Participant
            $this->db->where('MAID', $ID);
            $this->db->delete('db_admission.marketing_activity_participants');
            $this->db->reset_query();

            $Participants = (array) $data_arr['Participants'];
            if(count($Participants)>0){
                for($i=0;$i<count($Participants);$i++){
                    $arrIns = array(
                        'MAID' => $ID,
                        'NIP' => $Participants[$i]
                    );
                    $this->db->insert('db_admission.marketing_activity_participants', $arrIns);
                }
            }

            return print_r(1);

        }
        else if($data_arr['action']=='readMonthYear_MA'){
            $dataMonth = $this->db->query('SELECT ma.Month FROM db_admission.marketing_activity ma 
                                                            GROUP BY ma.Month ORDER BY ma.Month ASC')->result_array();

            $dataYear = $this->db->query('SELECT ma.Year FROM db_admission.marketing_activity ma 
                                                            GROUP BY ma.Year ORDER BY ma.Year DESC')->result_array();

            $result = array('Month' => $dataMonth,'Year' => $dataYear);

            return print_r(json_encode($result));
        }
        else if($data_arr['action']=='filter_MA'){

            $Month = $data_arr['Month'];
            $Year = $data_arr['Year'];

            $data = $this->db->query('SELECT * FROM db_admission.marketing_activity ma 
                                                  WHERE ma.Month = "'.$Month.'" 
                                                  AND ma.Year = "'.$Year.'"  ')->result_array();

            if(count($data)>0){
                for ($i=0;$i<count($data);$i++){
                    $data[$i]['Participants'] = $this->db->query('SELECT map.*, em.Name FROM db_admission.marketing_activity_participants map
                                                                    LEFT JOIN db_employees.employees em ON (em.NIP = map.NIP)
                                                                    WHERE map.MAID = "'.$data[$i]['ID'].'" ')->result_array();
                }
            }

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='remove_MA'){
            $ID = $data_arr['ID'];

            $dataCheck = $this->db->get_where('db_admission.crm',array(
                'MAID' => $ID
            ))->result_array();

            $result = array('Status'=>'0');
            if(count($dataCheck)<=0){
                // Remove
                $this->db->where('MAID', $ID);
                $this->db->delete('db_admission.marketing_activity_participants');
                $this->db->reset_query();

                $this->db->where('ID', $ID);
                $this->db->delete('db_admission.marketing_activity');

                $result = array('Status'=>'1');
            }

            return print_r(json_encode($result));

        }

        else if($data_arr['action']=='readActiveNow_MA'){

            $DateNow = $this->m_rest->getDateNow();

            $data = $this->db->query('SELECT * FROM db_admission.marketing_activity ma WHERE ma.Start <= "'.$DateNow.'" AND ma.End >= "'.$DateNow.'" ')->result_array();

            return print_r(json_encode($data));

        }

    }

    public function crudContact(){
        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='insertContat'){

            $dataForm = (array) $data_arr['dataForm'];
            $this->db->insert('db_admission.contact',$dataForm);
            return print_r(json_encode(array('Status'=> '1')));

        }
        else if($data_arr['action']=='updateContat'){
            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];
            $this->db->where('ID', $ID);
            $this->db->update('db_admission.contact',$dataForm);
            return print_r(json_encode(array('Status'=> '1')));

        }
        else if($data_arr['action']=='searchContact'){
            $key = $data_arr['key'];

            $query = 'SELECT c.*, s.SchoolName, s.CityID, em.Name AS CreatedBy_Name, s.CityName FROM db_admission.contact c  
                                              LEFT JOIN db_admission.school s ON (s.ID = c.SchoolID)
                                              LEFT JOIN db_employees.employees em ON (em.NIP = c.CreatedBy)
                                              ';

            if($key!=''){

                $des = explode('schid:',$key);

                if(count($des)>1){
                    $data = $this->db->query($query.' WHERE s.ID = "'.$des[1].'" ORDER BY c.Name ASC LIMIT 20 ')->result_array();
                } else {
                    $data = $this->db->query($query.' WHERE c.Name LIKE "%'.$key.'%" 
                                              OR c.Phone LIKE "%'.$key.'%"
                                              OR c.Email LIKE "%'.$key.'%"
                                              OR s.SchoolName LIKE "%'.$key.'%" ORDER BY c.Name ASC LIMIT 20 ')->result_array();
                }

            } else {
                $data = $this->db->query($query.' ORDER BY c.Name ASC LIMIT 7 ')->result_array();
            }

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='removeContact'){
            $ID = $data_arr['ID'];
            $this->db->where('ID', $ID);
            $this->db->delete('db_admission.contact');
            return print_r(1);
        }
    }

    public function crudProspectiveStudents(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='insert_PS'){

            $dataForm = (array) $data_arr['dataForm'];

            $this->db->insert('db_admission.crm',$dataForm);

            return print_r(1);

        }
        else if($data_arr['action']=='update_PS'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            $this->db->where('ID', $ID);
            $this->db->update('db_admission.crm',$dataForm);

            return print_r(1);

        }
        else if($data_arr['action']=='read_PS'){

            $PeriodID = $data_arr['PeriodID'];
            $data = $this->db->query('SELECT c.*, em.Name AS NameProspect_by FROM db_admission.crm c 
                                                LEFT JOIN db_employees.employees em ON (em.NIP = c.NIP)
                                                WHERE c.PeriodID = "'.$PeriodID.'" ')->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='read2Full_PS'){
            $ID = $data_arr['ID'];
            $data = $this->db->query('SELECT c.*, em.Name AS SalesName, s.CityID, rms.SchoolMajor FROM db_admission.crm c 
                                                LEFT JOIN db_employees.employees em ON (em.NIP = c.NIP)
                                                LEFT JOIN db_admission.school s ON (s.ID = c.SchoolID)
                                                LEFT JOIN db_admission.register_major_school rms ON (rms.ID = c.PathwayID)
                                                WHERE c.ID = "'.$ID.'" ')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='insertStatus_PS'){

            $dataForm = (array) $data_arr['dataForm'];
            $this->db->insert('db_admission.crm_status', $dataForm);
            return print_r(1);

        }
        else if($data_arr['action']=='updateStatus_PS'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            $this->db->where('ID', $ID);
            $this->db->update('db_admission.crm_status',$dataForm);
            return print_r(1);
        }
        else if($data_arr['action']=='removeCRM_PS'){



        }
        else if($data_arr['action']=='status_PS'){

            $data = $this->db->query('SELECT cs.*,csl.Name AS LabelName, csl.Class AS LabelClass FROM db_admission.crm_status cs 
                                                LEFT JOIN db_admission.crm_status_label csl ON (csl.ID = cs.LabelID)')->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='removeStatus_PS'){

            $ID = $data_arr['ID'];
            // Cek apakah IDnya di pakai
            $dataCheck = $this->db->get_where('db_admission.crm',array('Status'=>$ID))->result_array();

            if(count($dataCheck)>0){
                $Status = '0';
            } else {
                $this->db->where('ID', $ID);
                $this->db->delete('db_admission.crm_status');
                $Status = '1';
            }

            return print_r(json_encode(array('Status' => $Status)));



        }
        else if($data_arr['action']=='insertCommentF_SP'){

            $dataForm = (array) $data_arr['dataForm'];
            $this->db->insert('db_admission.crm_followup', $dataForm);
            return print_r(1);

        }
        else if($data_arr['action']=='updateCommentF_SP'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            $this->db->where('ID', $ID);
            $this->db->update('db_admission.crm_followup',$dataForm);
            return print_r(1);

        }
        else if($data_arr['action']=='viewFollowUp_SP'){
            $CRMID = $data_arr['CRMID'];
            $data = $this->db->get_where('db_admission.crm_followup',array('CRMID' => $CRMID))->result_array();

            return print_r(json_encode($data));
        }

    }

    public function getPathway(){
        $data = $this->db->get_where('db_admission.register_major_school',array(
            'Active' => 1
        ))->result_array();

        return print_r(json_encode($data));

    }

    // ==== PENUTUP CRM ====


    public function show_info_pr()
    {
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $rs = array();
                $PRCode = $dataToken['PRCode'];
                $sql = 'select a.Desc,a.Date,b.NIP,b.Name from db_budgeting.pr_circulation_sheet as a 
                        join db_employees.employees as b on a.By = b.NIP
                        where a.PRCode = ?
                        ';
                $query=$this->db->query($sql, array($PRCode))->result_array();
                $rs['PR_Process'] = $query;

                $rs['PR_Status_Summary'] = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
                $sql = 'select a.ID,a.ID_m_catalog,b.Item,c.ID as ID_pre_po_detail,d.Code
                        from db_budgeting.pr_detail as a join db_purchasing.m_catalog as b on a.ID_m_catalog = b.ID
                        left join db_purchasing.pre_po_detail as c on a.ID = c.ID_pr_detail
                        left join db_purchasing.po_detail as d on c.ID = d.ID_pre_po_detail
                        where a.PRCode = ?
                        '; 
                $query=$this->db->query($sql, array($PRCode))->result_array();
                $rs['PR_link_PO'] = $query;
                echo json_encode($rs);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function show_info_po()
    {
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $rs = array();
                $Code = $dataToken['Code'];
                $sql = 'select a.Desc,a.Date,b.NIP,b.Name from db_purchasing.po_circulation_sheet as a 
                        join db_employees.employees as b on a.By = b.NIP
                        where a.Code = ?
                        ';
                $query=$this->db->query($sql, array($Code))->result_array();
                $rs['po_circulation_sheet'] = $query;
                $po_invoice_status = $this->m_master->caribasedprimary('db_purchasing.po_invoice_status','Code_po_create',$Code);
                $rs['po_invoice_status'] = $po_invoice_status;        
                echo json_encode($rs);
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function reject_pr_from_another()
    {
        $msg = '';
        $Reload = 0;
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $BoolReload = false;
                $this->load->model('budgeting/m_budgeting');
                $this->load->model('budgeting/m_pr_po');
                $PRCode = $dataToken['PRCode'];
                $NIP = $dataToken['NIP'];

                // check PR Item belum di proses pada PO
                    $Bool = $this->m_pr_po->check_pr_item_In_po($PRCode);
                    if ($Bool) {
                        $datasave['Status'] = 3;
                        $this->db->where('PRCode',$PRCode);
                        $this->db->update('db_budgeting.pr_create',$datasave);
                        $G_data = $this->m_master->caribasedprimary('db_budgeting.pr_create','PRCode',$PRCode);
                        $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
                        $NameFor_NIP = $G_emp[0]['Name'];
                        $JsonStatus = (array)json_decode($G_data[0]['JsonStatus'],true);

                        // Send Notif for user 
                            $data = array(
                                'auth' => 's3Cr3T-G4N',
                                'Logging' => array(
                                                'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>'.$PRCode.' has been Rejected by '.$NameFor_NIP,
                                                'Description' => 'PR '.$PRCode.' has been Rejected by '.$NameFor_NIP,
                                                'URLDirect' => 'budgeting_pr',
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

                        $Desc = 'Reject by '.$NameFor_NIP.'<br>{'.$dataToken['NoteDel'].'}';    
                        $this->m_pr_po->pr_circulation_sheet($PRCode,$Desc,$NIP);
                    }
                    else
                    {
                        $msg = 'PR is being processed, cant action reject';
                    }

                    echo json_encode($msg);
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

    public function cancel_pr_item_from_another()
    {
        $rs = array ('msg' => '','reload' => 0);
        $Reload = 0;
        try {
            $dataToken = $this->getInputToken2();
            $auth = $this->m_master->AuthAPI($dataToken);
            if ($auth) {
                $BoolReload = false;
                $this->load->model('budgeting/m_budgeting');
                $this->load->model('budgeting/m_pr_po');
                $ID_pr_detail = $dataToken['ID_pr_detail'];
                $NIP = $dataToken['NIP'];
                // get PRCode first
                $G_pr_detail = $this->m_master->caribasedprimary('db_budgeting.pr_detail','ID',$ID_pr_detail);
                $PRCode = $G_pr_detail[0]['PRCode'];

                $G_data = $this->m_master->caribasedprimary('db_budgeting.pr_create','PRCode',$PRCode);
                $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
                $NameFor_NIP = $G_emp[0]['Name'];
                $JsonStatus = (array)json_decode($G_data[0]['JsonStatus'],true);

                // yang boleh di cancel adalah item pr yang dalam status po_nya adalah cancel dan item pr yang belum di proses po
                // jika item pr di cancel yang belum masuk proses po maka status pr akan otomatis reject
                    $__G_po_detail = $this->m_pr_po->check_po_status_by_item_pr_detail($ID_pr_detail);
                    if ($__G_po_detail == 4) {
                        $Bool = $this->m_pr_po->check_pr_item_In_po($PRCode); // auto reject if true
                        if ($Bool) {
                            $datasave['Status'] = 3;
                            $this->db->where('PRCode',$PRCode);
                            $this->db->update('db_budgeting.pr_create',$datasave);

                            // Send Notif for user 
                                $data = array(
                                    'auth' => 's3Cr3T-G4N',
                                    'Logging' => array(
                                                    'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>'.$PRCode.' has been Rejected by '.$NameFor_NIP,
                                                    'Description' => 'PR '.$PRCode.' has been Rejected by '.$NameFor_NIP,
                                                    'URLDirect' => 'budgeting_pr',
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

                                $rs['reload'] = 1;

                        }

                        // cek status first
                            $Status = $G_pr_detail[0]['Status'];
                            if ($Status == 1) {
                                $arr = array($ID_pr_detail);
                                $this->m_pr_po->ReturnAllBudgetFromID_pr_detail($arr);

                                $ID_m_catalog = $G_pr_detail[0]['ID_m_catalog'];
                                $G_m_catalog = $this->m_master->caribasedprimary('db_purchasing.m_catalog','ID',$ID_m_catalog);
                                
                                $Desc = 'Item '.$G_m_catalog[0]['Item'].' cancel by '.$NameFor_NIP.'<br>{'.$dataToken['NoteDel'].'}';    
                                $this->m_pr_po->pr_circulation_sheet($PRCode,$Desc,$NIP);

                                // update ke pr_status dan pr_status_detail
                                    $this->m_pr_po->__cancel_item_by_id_pr_detail($ID_pr_detail,$PRCode);
                            }
                            else
                            {
                                $rs['msg'] = 'Item has canceled,cant action';
                            }

                    }
                    else
                    {
                        $G_pr_status_detail= $this->m_master->caribasedprimary('db_purchasing.pr_status_detail','ID_pr_detail',$ID_pr_detail); // auto reject if true
                        $Bool = ($G_pr_status_detail[0]['Status'] == 0) ? true : false;
                        if ($Bool) {
                            $Bool2 = $this->m_pr_po->check_pr_item_In_po($PRCode); // auto reject if true
                            if ($Bool2) {
                                $datasave['Status'] = 3;
                                $this->db->where('PRCode',$PRCode);
                                $this->db->update('db_budgeting.pr_create',$datasave);

                                // Send Notif for user 
                                    $data = array(
                                        'auth' => 's3Cr3T-G4N',
                                        'Logging' => array(
                                                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>'.$PRCode.' has been Rejected by '.$NameFor_NIP,
                                                        'Description' => 'PR '.$PRCode.' has been Rejected by '.$NameFor_NIP,
                                                        'URLDirect' => 'budgeting_pr',
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

                                    $rs['reload'] = 1;
                            }
                            
                           // cek status first
                               $Status = $G_pr_detail[0]['Status'];
                               if ($Status == 1) {
                                   $arr = array($ID_pr_detail);
                                   $this->m_pr_po->ReturnAllBudgetFromID_pr_detail($arr);

                                   $ID_m_catalog = $G_pr_detail[0]['ID_m_catalog'];
                                   $G_m_catalog = $this->m_master->caribasedprimary('db_purchasing.m_catalog','ID',$ID_m_catalog);
                                   
                                   $Desc = 'Item '.$G_m_catalog[0]['Item'].' cancel by '.$NameFor_NIP.'<br>{'.$dataToken['NoteDel'].'}';    
                                   $this->m_pr_po->pr_circulation_sheet($PRCode,$Desc,$NIP);

                                   // update ke pr_status dan pr_status_detail
                                       $this->m_pr_po->__cancel_item_by_id_pr_detail($ID_pr_detail,$PRCode);
                               }
                               else
                               {
                                   $rs['msg'] = 'Item has canceled,cant action';
                               }     

                        }
                        else
                        {
                            $rs['msg'] = 'Item is being processed';
                        }
                    }

                    echo json_encode($rs);
                
            }
            else
            {
                // handling orang iseng
                echo '{"status":"999","message":"Not Authorize"}';
            }
        }
        //catch exception
        catch(Exception $e) {
          // handling orang iseng
          echo '{"status":"999","message":"Not Authorize"}';
        }
    }

}
