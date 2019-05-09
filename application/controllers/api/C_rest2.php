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
                            $G_emp = $this->m_master->caribasedprimary('db_employees.employees','NIP',$arr_to[$i]);
                            $arr_to_email[] = $G_emp[0]['EmailPU'];
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
}
