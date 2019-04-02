<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pr_po extends Budgeting_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function authFin()
    {
        $DepartementSess = $this->session->userdata('IDDepartementPUBudget');
        if ($DepartementSess != 'NA.9') {
            exit('No direct script access allowed');
        }
        
    }

    public function configfinance_pr($Request = null)
    {
        $this->authFin();
        $arr_menuConfig = array('Set_Rad',
                                'Set_Approval',
                                null
                            );
        if (in_array($Request, $arr_menuConfig))
          {
            $this->data['request'] = $Request;
            $content = $this->load->view('page/budgeting/'.$this->data['department'].'/configfinance_pr',$this->data,true);
            $this->temp($content);
          }
        else
          {
            show_404($log_error = TRUE);
          }
    }

    public function set_rad()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        // pass check data existing
        $this->data['dt'] = $this->m_master->showData_array('db_budgeting.cfg_set_userrole');
        $this->data['cfg_m_userrole'] = $this->m_master->showData_array('db_budgeting.cfg_m_userrole');
        $content = $this->load->view('page/budgeting/'.$this->data['department'].'/config_pr/set_rad',$this->data,true);
        $arr_result['html'] = $content;
        echo json_encode($arr_result);
    }

    public function Set_Approval()
    {
        $this->auth_ajax();
       $arr_result = array('html' => '','jsonPass' => '');
       $this->data['cfg_m_type_approval'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');
       // $this->data['employees'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');
       $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/config_pr/Set_Approval',$this->data,true);
       echo json_encode($arr_result);
    }

    public function userroledepart_submit()
    {
       $this->auth_ajax();
       $Msg = '';
       try {
        $Input = $this->getInputToken();
        $dataSave = array();
        if (count($Input) > 0) {
            $table = 'db_budgeting.cfg_set_userrole';
            $sql = "TRUNCATE TABLE ".$table;
            $query=$this->db->query($sql, array());
            foreach ($Input as $key) {
                $temp = array();
                foreach ($key as $keya => $value) {
                   $temp[$keya] = $value; 
                }
                $dataSave[] = $temp;
            }
            $this->db->insert_batch('db_budgeting.cfg_set_userrole', $dataSave);  
        }
        else
        {
            $Msg = 'No data action';
        }

       } catch (Exception $e) {
            $Msg = $this->Msg['Error'];
       }

       echo json_encode($Msg);
       
    }

    public function get_cfg_set_roleuser_pr($Departement)
    {
        $this->auth_ajax();
        $getData = $this->m_budgeting->get_cfg_set_roleuser_pr($Departement);
        echo json_encode($getData);
    }

    public function save_cfg_set_roleuser_pr()
    {
        $this->auth_ajax();
        $msg = array('status' => 0,'msg' => '');
        $Input = $this->getInputToken();
        $Action = $Input['Action'];
        switch ($Action) {
            case "":
                $dt = $Input['dt'];
                $dt = (array) json_decode(json_encode($dt),true);
                for ($i=0; $i < count($dt); $i++) { 
                    $FormInsert = $dt[$i]['FormInsert'];
                    // check NIM already exist in employees
                    $NIP = $FormInsert['NIP'];
                    $G = $this->m_master->caribasedprimary('db_employees.employees','NIP',$NIP);
                    if (count($G) == 0) {
                        $msg['msg'] = 'NIP : '.$NIP.' is not already exist';   
                        break;
                    }
                    $Method = $dt[$i]['Method'];
                    $subAction = $Method['Action'];
                    if ($subAction == 'add') {
                        $this->db->insert('db_budgeting.cfg_approval_pr',$FormInsert);
                    }
                    else
                    {
                        $ID = $Method['ID'];
                        $this->db->where('ID', $ID);
                        $this->db->update('db_budgeting.cfg_approval_pr', $FormInsert);
                    }
                }
                if ($msg['msg'] == '') {
                   $msg['status'] = 1;
                }
                break;
            case "delete":
                $ID = $Input['ID_set_roleuser'];
                $this->m_master->delete_id_table_all_db($ID,'db_budgeting.cfg_approval_pr');
                $msg['status'] = 1;
                break;
            default:
                # code...
                break;
        }
        

        echo json_encode($msg);
    }

    public function pr()
    {
        $content = $this->load->view('global/budgeting/pr/page',$this->data,true);
        $this->temp($content);
    }

    public function page_pr_catalog()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $uri = $this->uri->segment(3);
        switch ($uri) {
            case 'entry_catalog':
                $this->data['action'] = 'add';
                if ( (!empty($_POST)) && count($_POST) > 0 ){
                    $Input = $this->getInputToken();
                    $this->data['action'] = $Input['action'];
                    if ($Input['action'] == 'edit') {
                        $this->data['get'] = $this->m_master->caribasedprimary('db_purchasing.m_catalog','ID',$Input['ID']);
                    }
                }
                $content = $this->load->view('global/budgeting/pr/'.$uri,$this->data,true);
                $arr_result['html'] = $content;
                break;
            case 'datacatalog':
                $content = $this->load->view('global/budgeting/pr/'.$uri,$this->data,true);
                $arr_result['html'] = $content;
                break;
            default:
                # code...
                break;
        }
        
        echo json_encode($arr_result);
    }

    public function page_pr()
    {
      $this->auth_ajax();
      $arr_result = array('html' => '','jsonPass' => '');
      $uri = $this->uri->segment(3);
      switch ($uri) {
          case 'Catalog':
              $content = $this->load->view('global/budgeting/pr/'.$uri,$this->data,true);
              $arr_result['html'] = $content;
              break;
          
          default:
              $this->data['G_Approver'] = $this->m_pr_po->Get_m_Approver();
              $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
              $Year = $get[0]['Year'];
              $this->data['Year'] = $Year;
              $this->data['PRCodeVal'] = '';
              $content = $this->load->view('global/budgeting/pr/'.$uri,$this->data,true);
              $arr_result['html'] = $content;
              break;
      }
      
      echo json_encode($arr_result);
    }

    public function DataPR()
    {
        $requestData= $_REQUEST;
        $sqltotalData = 'select count(*) as total from db_budgeting.pr_create';
        $querytotalData = $this->db->query($sqltotalData)->result_array();
        $totalData = $querytotalData[0]['total'];

        $sql = 'select * from 
                (
                    select a.PRCode,a.Year,a.Departement,b.NameDepartement,a.CreatedBy,a.CreatedAt,
                                    if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done",if(a.Status = 3,"Reject","Cancel") ) ))
                                    as StatusName, a.JsonStatus,a.PostingDate 
                                    from db_budgeting.pr_create as a 
                    join (
                    select * from (
                    select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
                    UNION
                    select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                    UNION
                    select CONCAT("FT.",ID) as ID, NameEng as NameDepartement from db_academic.faculty where StBudgeting = 1
                    ) aa
                    ) as b on a.Departement = b.ID
                )aa
               ';

        $sql.= ' where PRCode LIKE "%'.$requestData['search']['value'].'%" or NameDepartement LIKE "'.$requestData['search']['value'].'%" or StatusName LIKE "'.$requestData['search']['value'].'%" 
                ';
        $sql.= ' ORDER BY PRCode Desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
        $query = $this->db->query($sql)->result_array();

        $No = $requestData['start'] + 1;
        $data = array();

        $G_Approver = $this->m_pr_po->Get_m_Approver();
        if (array_key_exists('length', $_POST)) {
            $Count_G_Approver = $_POST['length'];
        }
        else
        {
            $Count_G_Approver = count($G_Approver);
        }
        
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];
            $nestedData[] = $No;
            $nestedData[] = $row['PRCode'];
            $nestedData[] = $row['NameDepartement'];
            $nestedData[] = $row['StatusName'];
            // circulation sheet
            $nestedData[] = '<a href="javascript:void(0)" class = "btn btn-default btn_circulation_sheet" prcode = "'.$row['PRCode'].'">See</a>';
            $JsonStatus = (array)json_decode($row['JsonStatus'],true);
            $arr = array();
            if (count($JsonStatus) > 0) {
                for ($j=0; $j < count($JsonStatus); $j++) {
                    $getName = $this->m_master->caribasedprimary('db_employees.employees','NIP',$JsonStatus[$j]['ApprovedBy']);
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
            $nestedData[] = $row['Departement'];
            // get name created by
                $getName = $this->m_master->caribasedprimary('db_employees.employees','NIP',$row['CreatedBy']);
                $nestedData[] = $getName[0]['Name'];


            $data[] = $nestedData;
            $No++;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function checkruleinput()
    {
        $this->auth_ajax();
        $bool = false;
        $input = $this->getInputToken();
        if (!array_key_exists('NIP', $input)) {
             $NIP = $this->session->userdata('NIP');
        }
        else
        {
            $NIP = $input['NIP'];
        }

        if (!array_key_exists('Departement', $input)) {
             $Departement = $this->session->userdata('IDDepartementPUBudget');
        }
        else
        {
            $Departement = $input['Departement'];
        }

        if (array_key_exists('PRCodeVal', $input)) { // change department
            $PRCodeVal = $input['PRCodeVal'];
            $G_data = $this->m_master->caribasedprimary('db_budgeting.pr_create','PRCode',$PRCodeVal);
            if (count($G_data) > 0) {
                $JsonStatus = $G_data[0]['JsonStatus'];
                $JsonStatus = (array) json_decode($JsonStatus,true);
                $bool = true;
                for ($i=0; $i < count($JsonStatus); $i++) { 
                    $ApprovedBy = $JsonStatus[$i]['ApprovedBy'];
                    if ($NIP == $ApprovedBy) {
                        $bool = false;
                        break;
                    }
                }
                
                if (!$bool) {
                    // $Departement = $this->session->userdata('IDDepartementPUBudget');
                    $Departement = $G_data[0]['Departement'];
                    $GetRuleAccess = $this->m_pr_po->GetRuleAccess($NIP,$Departement);
                    if (count($GetRuleAccess['access']) == 0) {
                       $GetRuleAccess['rule'] = array();
                       $access = array();
                       $t = array(
                        'Active' => 1,
                        'DSG' => null,
                        'Departement' => $this->session->userdata('IDDepartementPUBudget'),
                        'ID' => 0,
                        'ID_m_userrole' => ($i+2), //  berdasarkan ID karena ID 1 adalah admin dan hasil loop dimulai dari 0
                        'NIP' => $NIP,
                        'Status' => 1,
                       );
                       $access[] = $t;
                       $GetRuleAccess['access'] = $access;
                    }

                    
                }
                else
                {
                     $GetRuleAccess = $this->m_pr_po->GetRuleAccess($NIP,$Departement);
                }

            }
        }
        else
        {
            // print_r($Departement);
            $GetRuleAccess = $this->m_pr_po->GetRuleAccess($NIP,$Departement);
        }

        echo json_encode($GetRuleAccess);
    }



}
