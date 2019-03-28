<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_budgeting extends Budgeting_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->session->unset_userdata('auth_budgeting_sess');
        $this->session->unset_userdata('menu_budgeting_sess');
        $this->session->unset_userdata('menu_budgeting_grouping');
        $this->session->unset_userdata('role_user_budgeting');
        $MenuDepartement= ($this->data['IDdepartment'] == 12) ? 'NA.'.$this->session->userdata('IDdepartementNavigation'):'NA.'.$this->data['IDdepartment']; 

        if ($this->data['IDdepartment'] == 15 || $this->data['IDdepartment'] == 14) {
            $MenuDepartement= 'AC.'.$this->session->userdata('prodi_active_id');
        }
        $this->getAuthSession($MenuDepartement);
        $this->data['GetPeriod'] = $this->m_budgeting->GetPeriod();
        if (file_exists(APPPATH.'view/page/budgeting'.$this->data['department'].'dashboard')) {
            $content = $this->load->view('page/budgeting/'.$this->data['department'].'/dashboard',$this->data,true);
        }
        else
        {
            $content = $this->load->view('page/budgeting/dashboard',$this->data,true);
        }
        
        $this->temp($content);
        
    }

    public function configfinance_budgeting($Request = null)
    {
        $this->authFin();
        $arr_menuConfig = array('CodePrefix',
                                'TimePeriod',
                                'MasterPost',
                                'SetPostDepartement',
                                'MasterUserRole',
                                'UserRole',
                                null
                            );
        if (in_array($Request, $arr_menuConfig))
          {
            $this->data['request'] = $Request;
            $content = $this->load->view('page/budgeting/'.$this->data['department'].'/configfinance',$this->data,true);
            $this->temp($content);
          }
        else
          {
            show_404($log_error = TRUE);
          }
    }

    public function pageLoadTimePeriod()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/pageLoadTimePeriod',$this->data,true);
        echo json_encode($arr_result);
    }

    public function modal_pageLoadTimePeriod()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $sql = 'select * from db_budgeting.cfg_dateperiod where Year = ? and Active = 1';
            $query=$this->db->query($sql, array($this->data['id']))->result_array();
            $this->data['getData'] = $query;
        }
        echo $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/modalform_timeperiod',$this->data,true);
    }

    public function modal_pageLoadTimePeriod_save()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $Msg = '';
        switch ($input['Action']) {
            case 'add':
                // $dateStart = cal_days_in_month(CAL_GREGORIAN, $input['MonthStart'], $input['Year']); 
                $dateStart = '01'; 
                $dateEnd= cal_days_in_month(CAL_GREGORIAN, $input['MonthEnd'], $input['Year']);
                $Year = $input['Year'];
                $StartPeriod = $Year.'-'.$input['MonthStart'].'-'.$dateStart;
                $EndPeriod = ($Year + 1).'-'.$input['MonthEnd'].'-'.$dateEnd;
                $sql = 'select * from db_budgeting.cfg_dateperiod where Year = ? and Active = 1';
                $query=$this->db->query($sql, array($Year))->result_array();
                if (count($query) > 0) {
                    $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                    $dataSave = array(
                        'Year' => $Year,
                        'StartPeriod' => $StartPeriod,
                        'EndPeriod' => $EndPeriod,
                        'Activated' => 1
                    );
                    $this->db->insert('db_budgeting.cfg_dateperiod', $dataSave);

                    $sql = 'update db_budgeting.cfg_dateperiod set Activated = 0 where Year != ? ';
                    $query=$this->db->query($sql, array($Year));
                }

                break;
            case 'edit':
                // $dateStart = cal_days_in_month(CAL_GREGORIAN, $input['MonthStart'], $input['Year']); 
                $dateStart = '01';
                $dateEnd= cal_days_in_month(CAL_GREGORIAN, $input['MonthEnd'], $input['Year']);
                $Year = $input['Year'];
                $StartPeriod = $Year.'-'.$input['MonthStart'].'-'.$dateStart;
                $EndPeriod = ($Year + 1).'-'.$input['MonthEnd'].'-'.$dateEnd;
                $sql = 'select * from db_budgeting.cfg_dateperiod where Year = ? and Active = 1';
                $query=$this->db->query($sql, array($Year))->result_array();

                $Status = $query[0]['Status']; // check can be delete
                if ($Status == 1) {
                    try {
                        $dataSave = array(
                            'Year' => $Year,
                            'StartPeriod' => $StartPeriod,
                            'EndPeriod' => $EndPeriod
                        );
                        $this->db->where('Year', $Year);
                        $this->db->where('Active', 1);
                        $this->db->update('db_budgeting.cfg_dateperiod', $dataSave);
                    } catch (Exception $e) {
                         $Msg = $this->Msg['Duplicate'];
                    }
                }
                else
                {
                    $Msg = $this->Msg['NotAction'];
                }
                break;
            case 'delete':
                $Year = $input['CDID'];
                $sql = 'select * from db_budgeting.cfg_dateperiod where Year = ? and Active = 1';
                $query=$this->db->query($sql, array($Year))->result_array();
                $Status = $query[0]['Status']; // check can be delete
                   if ($Status == 1) {
                       $dataSave = array(
                           'Year' => $Year,
                           'StartPeriod' => $query[0]['StartPeriod'],
                           'EndPeriod' => $query[0]['EndPeriod'],
                           'Active' => 0
                       );
                       $this->db->where('Year', $Year);
                       $this->db->where('Active', 1);
                       $this->db->update('db_budgeting.cfg_dateperiod', $dataSave);
                   }
                   else
                   {
                       $Msg = $this->Msg['NotAction'];
                   }
                break;
            case 'activated':
                $Year = $input['CDID'];
                $sql = 'select * from db_budgeting.cfg_dateperiod where Year = ? and Active = 1';
                $query=$this->db->query($sql, array($Year))->result_array();
                $Status = $query[0]['Status']; // check can be delete
                   if ($Status == 1) {
                        // check activated
                        $Activated = ($query[0]['Activated'] == 0) ? 1 : 0;

                       $dataSave = array(
                           'Activated' => $Activated,
                       );
                       $this->db->where('Year', $Year);
                       $this->db->where('Active', 1);
                       $this->db->update('db_budgeting.cfg_dateperiod', $dataSave);

                        $sql = 'update db_budgeting.cfg_dateperiod set Activated = 0 where Year != ? ';
                        $query=$this->db->query($sql, array($Year));
                   }
                   else
                   {
                       $Msg = $this->Msg['NotAction'];
                   }
                break;    
            default:
                # code...
                break;
        }

        echo json_encode($Msg);
    }

    public function LoadTable_db_budgeting_cari($table,$field,$fieldValue,$Active = null)
    {
        $this->auth_ajax();
        $query = array();
        if ($Active == null) {
            $sql = 'select * from db_budgeting.'.$table.' where '.$field.' = ?';
            $query=$this->db->query($sql, array($fieldValue))->result_array();
        }
        else
        {
            $sql = 'select * from db_budgeting.'.$table.' where '.$field.' = ? and Active = ?';
            $query=$this->db->query($sql, array($fieldValue,$Active))->result_array();
        }

        echo json_encode($query);
    }

    public function LoadTable_db_budgeting_all($table,$Active = null)
    {
        $this->auth_ajax();
        $query = array();
        if ($Active == null) {
            $sql = 'select * from db_budgeting.'.$table;
            $query=$this->db->query($sql, array())->result_array();
        }
        else
        {
            $sql = 'select * from db_budgeting.'.$table.' where Active = ?';
            $query=$this->db->query($sql, array($Active))->result_array();
        }

        echo json_encode($query);
    }

    public function loadCodePrefix()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $this->data['loadData'] = $this->m_master->showData_array('db_budgeting.cfg_codeprefix');
        $this->data['loadData'] = json_encode($this->data['loadData']);
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/pageloadCodePrefix',$this->data,true);
        echo json_encode($arr_result);
    }

    public function pageloadMasterPost()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/pageloadMasterPost',$this->data,true);
        echo json_encode($arr_result);
    }

    public function save_codeprefix()
    {
        $this->auth_ajax();
        $input =  $this->getInputToken();
        if(array_key_exists("CodePost",$input))
        {
            $dataSave = array(
                'CodePost' => $input['CodePost'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("LengthCodePost",$input))
        {
            $dataSave = array(
                'LengthCodePost' => $input['LengthCodePost'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("CodePostRealisasi",$input))
        {
            $dataSave = array(
                'CodePostRealisasi' => $input['CodePostRealisasi'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("LengthCodePostRealisasi",$input))
        {
            $dataSave = array(
                'LengthCodePostRealisasi' => $input['LengthCodePostRealisasi'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("CodePostBudget",$input))
        {
            $dataSave = array(
                'CodePostBudget' => $input['CodePostBudget'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("YearCodePostBudget",$input))
        {
            $dataSave = array(
                'YearCodePostBudget' => $input['YearCodePostBudget'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("LengthCodePostBudget",$input))
        {
            $dataSave = array(
                'LengthCodePostBudget' => $input['LengthCodePostBudget'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("CodeCatalog",$input))
        {
            $dataSave = array(
                'CodeCatalog' => $input['CodeCatalog'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("LengthCodeCatalog",$input))
        {
            $dataSave = array(
                'LengthCodeCatalog' => $input['LengthCodeCatalog'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("CodeSupplier",$input))
        {
            $dataSave = array(
                'CodeSupplier' => $input['CodeSupplier'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }

        if(array_key_exists("LengthCodeSupplier",$input))
        {
            $dataSave = array(
                'LengthCodeSupplier' => $input['LengthCodeSupplier'],
            );
            $this->db->update('db_budgeting.cfg_codeprefix', $dataSave);
        }
    }

    public function get_cfg_postrealisasi()
    {
        $this->auth_ajax();
        $getData = $this->m_budgeting->getData_cfg_postrealisasi(1);
        echo json_encode($getData);
    }

    public function get_cfg_head_account()
    {
        $this->auth_ajax();
        $getData = $this->m_budgeting->get_cfg_head_account(1);
        echo json_encode($getData);
    }

    public function modal_pageloadMasterPost()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        // print_r($this->data);
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $sql = 'select * from db_budgeting.cfg_post where CodePost = ? and Active = 1';
            $query=$this->db->query($sql, array($this->data['id']))->result_array();
            $this->data['getData'] = $query;
        }
        echo $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/modalform_masterpost',$this->data,true);

    }

    public function modal_pageloadMasterPost_save()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $Msg = '';
        switch ($input['Action']) {
            case 'add':
                $NeedPrefix = $input['NeedPrefix'];
                $CodePost = $input['CodePost'];
                if ($NeedPrefix == 1) { // get the code
                    $CfgCode = $this->m_master->showData_array('db_budgeting.cfg_codeprefix');
                    $CodePostPrefix = $CfgCode[0]['CodePost'];
                    $LengthCode = $CfgCode[0]['LengthCodePost'];
                    $tbl = 'db_budgeting.cfg_post';
                    $fieldCode = 'CodePost';
                    $CodePost = $this->m_budgeting->getTheCode($tbl,$fieldCode,$CodePostPrefix,$LengthCode);
                }


                $sql = 'select * from db_budgeting.cfg_post where CodePost = ? and Active = 1';
                $query=$this->db->query($sql, array($CodePost))->result_array();
                if (count($query) > 0) {
                   $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                   $dataSave = array(
                       'CodePost' => $CodePost,
                       'PostName' => trim(ucwords($input['PostName'])),
                       'CreatedBy' => $this->session->userdata('NIP'),
                       'CreatedAt' => date('Y-m-d'),
                   );
                   $this->db->insert('db_budgeting.cfg_post', $dataSave);
                }
                break;
            case 'edit':
                $CodePost = $input['CodePost'];
                $query = array();
                if ($CodePost != $input['CDID']) {
                    $sql = 'select * from db_budgeting.cfg_post where CodePost = ? and Active = 1';
                    $query=$this->db->query($sql, array($CodePost))->result_array();
                }

                if (count($query) > 0) {
                   $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                    $sql = 'select * from db_budgeting.cfg_post where CodePost = ? and Active = 1';
                    $query=$this->db->query($sql, array($input['CDID']))->result_array();
                    $Status = $query[0]['Status'];
                    if ($Status == 1) {
                        try {
                           $dataSave = array(
                               'CodePost' => $CodePost,
                               'PostName' => trim(ucwords($input['PostName'])),
                           );
                           $this->db->where('CodePost', $input['CDID']);
                           $this->db->where('Active', 1);
                           $this->db->update('db_budgeting.cfg_post', $dataSave);
                        } catch (Exception $e) {
                             $Msg = $this->Msg['Duplicate'];
                        }   
                    }
                    else
                    {
                        // check data already exist in cfg_head_account,
                        $G = $this->m_master->caribasedprimary('db_budgeting.cfg_head_account','CodePost',$CodePost);
                        if (count($G) > 0) {
                            $Msg = $this->Msg['NotAction'];
                        }
                        else
                        {
                            $dataSave = array(
                                'CodePost' => $CodePost,
                                'PostName' => trim(ucwords($input['PostName'])),
                            );
                            $this->db->where('CodePost', $input['CDID']);
                            $this->db->where('Active', 1);
                            $this->db->update('db_budgeting.cfg_post', $dataSave);
                        }
                    }
                }
                break;
            case 'delete':
                $CodePost = $input['CDID'];
                $sql = 'select * from db_budgeting.cfg_post where CodePost = ? and Active = 1';
                $query=$this->db->query($sql, array($CodePost))->result_array();
                $Status = $query[0]['Status']; // check can be delete
                   if ($Status == 1) {
                       // $dataSave = array(
                       //     'Active' => 0
                       // );
                       // $this->db->where('CodePost', $CodePost);
                       // $this->db->where('Active', 1);
                       // $this->db->update('db_budgeting.cfg_post', $dataSave);
                        $this ->db-> where('CodePost', $CodePost);
                        $this ->db-> delete('db_budgeting.cfg_post');
                   }
                   else
                   {
                       // check data already exist in cfg_head_account,
                       $G = $this->m_master->caribasedprimary('db_budgeting.cfg_head_account','CodePost',$CodePost);
                       if (count($G) > 0) {
                           $Msg = $this->Msg['NotAction'];
                       }
                       else
                       {
                        // $dataSave = array(
                        //     'Active' => 0
                        // );
                        // $this->db->where('CodePost', $CodePost);
                        // $this->db->where('Active', 1);
                        // $this->db->update('db_budgeting.cfg_post', $dataSave);
                        $this ->db-> where('CodePost', $CodePost);
                        $this ->db-> delete('db_budgeting.cfg_post');
                       }
                       
                   }
                break;
            default:
                # code...
                break;
        }

        echo json_encode($Msg);
    }

    public function modal_postrealisasi()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $sql = 'select * from db_budgeting.cfg_postrealisasi where CodePostRealisasi = ? and Active = 1';
            $query=$this->db->query($sql, array($this->data['id']))->result_array();
            $this->data['getData'] = $query;
        }
        echo $this->load->view('page/budgeting/'.'finance'.'/configuration/modal_postrealisasi',$this->data,true);
    }

    public function save_postrealisasi()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $Msg = '';

        switch ($input['Action']) {
            case 'add':
                $NeedPrefix = $input['NeedPrefix'];
                $CodePostRealisasi = $input['CodePostRealisasi'];
                if ($NeedPrefix == 1) { // get the code
                    $CfgCode = $this->m_master->showData_array('db_budgeting.cfg_codeprefix');
                    $CodePostPrefix = $CfgCode[0]['CodePostRealisasi'];
                    $LengthCode = $CfgCode[0]['LengthCodePostRealisasi'];
                    $tbl = 'db_budgeting.cfg_postrealisasi';
                    $fieldCode = 'CodePostRealisasi';
                    $CodePostRealisasi = $this->m_budgeting->getTheCode($tbl,$fieldCode,$CodePostPrefix,$LengthCode);
                }


                $sql = 'select * from db_budgeting.cfg_postrealisasi where CodePostRealisasi = ? and Active = 1';
                $query=$this->db->query($sql, array($CodePostRealisasi))->result_array();
                if (count($query) > 0) {
                   $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                   $dataSave = array(
                       'CodePostRealisasi' => $CodePostRealisasi,
                       'CodeHeadAccount' => $input['HeadAccount'],
                       'RealisasiPostName' => trim(ucwords($input['RealisasiPostName'])),
                       'UnitDiv' => $input['UnitDiv'],
                       'CreatedBy' => $this->session->userdata('NIP'),
                       'CreatedAt' => date('Y-m-d'),
                   );
                   $this->db->insert('db_budgeting.cfg_postrealisasi', $dataSave);

                   $tbl = 'db_budgeting.cfg_head_account';
                   $fieldCode = 'CodeHeadAccount';
                   $ValueCode = $input['HeadAccount'];
                   $this->m_budgeting->makeCanBeDelete($tbl,$fieldCode,$ValueCode);
                }
                break;
            case 'edit':
                $CodePostRealisasi = $input['CodePostRealisasi'];
                $query = array();
                if ($CodePostRealisasi != $input['CDID']) {
                    $sql = 'select * from db_budgeting.cfg_postrealisasi where CodePostRealisasi = ? and Active = 1';
                    $query=$this->db->query($sql, array($CodePostRealisasi))->result_array();
                }

                if (count($query) > 0) {
                   $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                    $sql = 'select * from db_budgeting.cfg_postrealisasi where CodePostRealisasi = ? and Active = 1';
                    $query=$this->db->query($sql, array($input['CDID']))->result_array();
                    $Status = $query[0]['Status'];
                    if ($Status == 1) {
                        try {
                           $dataSave = array(
                               'CodePostRealisasi' => $CodePostRealisasi,
                               'RealisasiPostName' => trim(ucwords($input['RealisasiPostName'])),
                               'CodeHeadAccount' => $input['HeadAccount'],
                               'UnitDiv' => $input['UnitDiv'],
                           );
                           $this->db->where('CodePostRealisasi', $input['CDID']);
                           $this->db->where('Active', 1);
                           $this->db->update('db_budgeting.cfg_postrealisasi', $dataSave);
                        } catch (Exception $e) {
                             $Msg = $this->Msg['Duplicate'];
                        }   
                    }
                    else
                    {
                        // cek data exist di  creator_budget
                           $G = $this->m_master->caribasedprimary('db_budgeting.creator_budget','CodePostRealisasi',$CodePostRealisasi);
                           if (count($G) > 0) {
                               $Msg = $this->Msg['NotAction'];
                           }
                           else
                           {
                             $dataSave = array(
                                 'CodePostRealisasi' => $CodePostRealisasi,
                                 'RealisasiPostName' => trim(ucwords($input['RealisasiPostName'])),
                                 'CodeHeadAccount' => $input['HeadAccount'],
                                 'UnitDiv' => $input['UnitDiv'],
                             );
                             $this->db->where('CodePostRealisasi', $input['CDID']);
                             $this->db->where('Active', 1);
                             $this->db->update('db_budgeting.cfg_postrealisasi', $dataSave);
                           }
                    }
                }
                break;
            case 'delete':
                $CodePostRealisasi = $input['CDID'];
                $sql = 'select * from db_budgeting.cfg_postrealisasi where CodePostRealisasi = ? and Active = 1';
                $query=$this->db->query($sql, array($CodePostRealisasi))->result_array();
                $Status = $query[0]['Status']; // check can be delete
                   if ($Status == 1) {
                       // $dataSave = array(
                       //     'Active' => 0
                       // );
                       // $this->db->where('CodePostRealisasi', $CodePostRealisasi);
                       // $this->db->where('Active', 1);
                       // $this->db->update('db_budgeting.cfg_postrealisasi', $dataSave);
                        $this ->db-> where('CodePostRealisasi', $CodePostRealisasi);
                        $this ->db-> delete('db_budgeting.cfg_postrealisasi');
                   }
                   else
                   {
                       // cek data exist di  creator_budget
                          $G = $this->m_master->caribasedprimary('db_budgeting.creator_budget','CodePostRealisasi',$CodePostRealisasi);
                          if (count($G) > 0) {
                              $Msg = $this->Msg['NotAction'];
                          }
                          else
                          {
                            // $dataSave = array(
                            //     'Active' => 0
                            // );
                            // $this->db->where('CodePostRealisasi', $CodePostRealisasi);
                            // $this->db->where('Active', 1);
                            // $this->db->update('db_budgeting.cfg_postrealisasi', $dataSave);
                            $this ->db-> where('CodePostRealisasi', $CodePostRealisasi);
                            $this ->db-> delete('db_budgeting.cfg_postrealisasi');
                          }

                       
                   }
                break;
            default:
                # code...
                break;
        }

        echo json_encode($Msg);

    }

    public function modal_headaccount()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        $this->data['id'] = $input['CDID'];
        if ($input['Action'] == 'edit') {
            $sql = 'select * from db_budgeting.cfg_head_account where CodeHeadAccount = ? and Active = 1';
            $query=$this->db->query($sql, array($this->data['id']))->result_array();
            $this->data['getData'] = $query;
        }
        echo $this->load->view('page/budgeting/'.'finance'.'/configuration/modal_headaccount',$this->data,true);
    }

    public function save_headaccount()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $Msg = '';

        switch ($input['Action']) {
            case 'add':
                $NeedPrefix = $input['NeedPrefix'];
                $CodeHeadAccount = $input['CodeHeadAccount'];
                if ($NeedPrefix == 1) { // get the code
                    $CfgCode = $this->m_master->showData_array('db_budgeting.cfg_codeprefix');
                    $CodePostPrefix = $CfgCode[0]['HeadAccount'];
                    $LengthCode = $CfgCode[0]['LengthHeadAccount'];
                    $tbl = 'db_budgeting.cfg_head_account';
                    $fieldCode = 'CodeHeadAccount';
                    $CodeHeadAccount = $this->m_budgeting->getTheCode($tbl,$fieldCode,$CodePostPrefix,$LengthCode);
                }

                $sql = 'select * from db_budgeting.cfg_head_account where CodeHeadAccount = ? and Active = 1';
                $query=$this->db->query($sql, array($CodeHeadAccount))->result_array();
                if (count($query) > 0) {
                   $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                   $dataSave = array(
                       'CodeHeadAccount' => $CodeHeadAccount,
                       'CodePost' => $input['PostItem'],
                       'Name' => trim(ucwords($input['HeadAccountName'])),
                       'Departement' => $input['Departement'],
                       'CreatedBy' => $this->session->userdata('NIP'),
                       'CreatedAt' => date('Y-m-d'),
                   );
                   $this->db->insert('db_budgeting.cfg_head_account', $dataSave);

                   $tbl = 'db_budgeting.cfg_post';
                   $fieldCode = 'CodePost';
                   $ValueCode = $input['PostItem'];
                   $this->m_budgeting->makeCanBeDelete($tbl,$fieldCode,$ValueCode);

                   // insert data to cfg_set_post with year activated now and fill budget is zero
                   $YearActivated = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
                   $Year = $YearActivated[0]['Year'];
                   $sql = 'select count(*) as total from db_budgeting.cfg_set_post where Year = ? and CodeHeadAccount = ? ';
                   $query=$this->db->query($sql, array($Year,$CodeHeadAccount))->result_array();
                   if ($query[0]['total'] == 0) {
                        // get the code 
                        $tbl = 'db_budgeting.cfg_set_post';
                        $fieldCode = 'CodePostBudget';
                        $CfgCode = $this->m_master->showData_array('db_budgeting.cfg_codeprefix');
                        $CodePostPrefix = $CfgCode[0]['CodePostBudget'];
                        $LengthCode = $CfgCode[0]['LengthCodePostBudget'];
                        $CodePostBudget = $this->m_budgeting->getTheCode($tbl,$fieldCode,$CodePostPrefix,$LengthCode,$Year);

                       $dataSave = array(
                           'CodePostBudget' => $CodePostBudget,
                           'CodeHeadAccount' => $CodeHeadAccount,
                           'Year' => $Year,
                           'Budget' => 0,
                           'CreatedBy' => $this->session->userdata('NIP'),
                           'CreatedAt' => date('Y-m-d'),
                       );
                       $this->db->insert('db_budgeting.cfg_set_post', $dataSave);

                       $dataSave = array(
                           'CodePostBudget' => $CodePostBudget,
                           'Time' => date('Y-m-d H:i:s'),
                           'ActionBy' => $this->session->userdata('NIP'),
                           'Detail' => json_encode(array('action' => 'Created')),
                       );
                       $this->db->insert('db_budgeting.log_cfg_set_post', $dataSave);
                   }
                   
                }
                break;
            case 'edit':
                $CodeHeadAccount = $input['CodeHeadAccount'];
                $query = array();
                if ($CodeHeadAccount != $input['CDID']) {
                    $sql = 'select * from db_budgeting.cfg_head_account where CodeHeadAccount = ? and Active = 1';
                    $query=$this->db->query($sql, array($CodeHeadAccount))->result_array();
                }

                if (count($query) > 0) {
                   $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                    $sql = 'select * from db_budgeting.cfg_head_account where CodeHeadAccount = ? and Active = 1';
                    $query=$this->db->query($sql, array($input['CDID']))->result_array();
                    $Status = $query[0]['Status'];
                    if ($Status == 1) {
                        try {
                           $dataSave = array(
                               'CodeHeadAccount' => $CodeHeadAccount,
                               'Name' => trim(ucwords($input['HeadAccountName'])),
                               'CodePost' => $input['PostItem'],
                               'Departement' => $input['Departement'],
                           );
                           $this->db->where('CodeHeadAccount', $input['CDID']);
                           $this->db->where('Active', 1);
                           $this->db->update('db_budgeting.cfg_head_account', $dataSave);
                        } catch (Exception $e) {
                             $Msg = $this->Msg['Duplicate'];
                        }   
                    }
                    else
                    {
                        // check data in cfg_set_post,cfg_postrealisasi,
                        $b = true;
                        $arr_tbl = array('db_budgeting.cfg_set_post','db_budgeting.cfg_postrealisasi');
                        for ($i=0; $i < count($arr_tbl); $i++) { 
                            $sql = 'select * from '.$arr_tbl[$i].' where CodeHeadAccount = ? and Active = 1';
                            $query=$this->db->query($sql, array($CodeHeadAccount))->result_array(); 
                            $G = $query;
                            if (count($G) > 0) {
                                $Msg = $this->Msg['NotAction'];
                                $b = false;
                                break;
                            }
                        }
                        
                        if ($b) {
                           $dataSave = array(
                               'CodeHeadAccount' => $CodeHeadAccount,
                               'Name' => trim(ucwords($input['HeadAccountName'])),
                               'CodePost' => $input['PostItem'],
                               'Departement' => $input['Departement'],
                           );
                           $this->db->where('CodeHeadAccount', $input['CDID']);
                           $this->db->where('Active', 1);
                           $this->db->update('db_budgeting.cfg_head_account', $dataSave); 
                        }

                    }
                }
                break;
            case 'delete':
                $CodeHeadAccount = $input['CDID'];
                $sql = 'select * from db_budgeting.cfg_head_account where CodeHeadAccount = ? and Active = 1';
                $query=$this->db->query($sql, array($CodeHeadAccount))->result_array();
                $Status = $query[0]['Status']; // check can be delete
                   if ($Status == 1) {
                       // $dataSave = array(
                       //     'Active' => 0
                       // );
                       // $this->db->where('CodeHeadAccount', $CodeHeadAccount);
                       // $this->db->where('Active', 1);
                       // $this->db->update('db_budgeting.cfg_head_account', $dataSave);
                        $this ->db-> where('CodeHeadAccount', $CodeHeadAccount);
                        $this ->db-> delete('db_budgeting.cfg_head_account');
                   }
                   else
                   {
                       // check data in cfg_set_post,cfg_postrealisasi,
                       $b = true;
                       $arr_tbl = array('db_budgeting.cfg_set_post','db_budgeting.cfg_postrealisasi');
                       for ($i=0; $i < count($arr_tbl); $i++) {
                            $sql = 'select * from '.$arr_tbl[$i].' where CodeHeadAccount = ? and Active = 1';
                            $query=$this->db->query($sql, array($CodeHeadAccount))->result_array(); 
                           $G = $query;
                           if (count($G) > 0) {
                               $Msg = $this->Msg['NotAction'];
                               $b = false;
                               break;
                           }
                       }

                       if ($b) {
                          // $dataSave = array(
                          //     'Active' => 0
                          // );
                          // $this->db->where('CodeHeadAccount', $CodeHeadAccount);
                          // $this->db->where('Active', 1);
                          // $this->db->update('db_budgeting.cfg_head_account', $dataSave);

                          $this ->db-> where('CodeHeadAccount', $CodeHeadAccount);
                          $this ->db-> delete('db_budgeting.cfg_head_account'); 
                       }
                       
                   }
                break;
            default:
                # code...
                break;
        }

        echo json_encode($Msg);

    }

    public function LoadSetPostDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/pageSetPostDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function LoadInputsetPostDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/setpostdepartement/pageInputsetPostDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function ExportPostDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/setpostdepartement/pageExportPostDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function getPostDepartement()
    {
         $this->auth_ajax();
         $input = $this->getInputToken();
         $getData = $this->m_budgeting->getPostDepartement($input['Year'],$input['Departement']);
         echo json_encode($getData);
    }

    public function getDomPostDepartement()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $getDataForDom = $this->m_budgeting->getPostDepartementForDom($input['Year'],$input['Departement']);
        echo json_encode($getDataForDom);
    }

    public function save_setpostdepartement()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $Msg = '';

        switch ($input['Action']) {
            case 'add':
            // check data telah diapprove atau belum // data pass : Year & Departement
                $Q_get = $this->m_master->caribasedprimary('db_budgeting.cfg_head_account','CodeHeadAccount',$input['CodeHeadAccount']);
                $Departement = $Q_get[0]['Departement'];
                $Q_get = $this->m_budgeting->get_creator_budget_approval($input['Year'],$Departement);
                if (count($Q_get) > 0) {
                    $Msg = $this->Msg['NotAction'];
                    break;
                }

                $tbl = 'db_budgeting.cfg_set_post';
                $fieldCode = 'CodePostBudget';
                $CfgCode = $this->m_master->showData_array('db_budgeting.cfg_codeprefix');
                $CodePostPrefix = $CfgCode[0]['CodePostBudget'];
                $LengthCode = $CfgCode[0]['LengthCodePostBudget'];
                $CodePostBudget = $this->m_budgeting->getTheCode($tbl,$fieldCode,$CodePostPrefix,$LengthCode,$input['Year']);

                $sql = 'select * from db_budgeting.cfg_set_post where CodeHeadAccount = ? and Active = 1 and Year = ?';
                $query=$this->db->query($sql, array($input['CodeHeadAccount'],$input['Year']))->result_array();
                if (count($query) > 0) {
                   $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                   $dataSave = array(
                       'CodePostBudget' => $CodePostBudget,
                       'CodeHeadAccount' => $input['CodeHeadAccount'],
                       'Year' => $input['Year'],
                       'Budget' => $input['Budget'],
                       'CreatedBy' => $this->session->userdata('NIP'),
                       'CreatedAt' => date('Y-m-d'),
                   );
                   $this->db->insert('db_budgeting.cfg_set_post', $dataSave);

                   $dataSave = array(
                       'CodePostBudget' => $CodePostBudget,
                       'Time' => date('Y-m-d H:i:s'),
                       'ActionBy' => $this->session->userdata('NIP'),
                       'Detail' => json_encode(array('action' => 'Created')),
                   );
                   $this->db->insert('db_budgeting.log_cfg_set_post', $dataSave);

                   // $tbl = 'db_budgeting.cfg_postrealisasi';
                   // $fieldCode = 'CodePostRealisasi';
                   // $ValueCode = $input['CodeSubPost'];
                   // $this->m_budgeting->makeCanBeDelete($tbl,$fieldCode,$ValueCode);
                }
                break;
            case 'edit':
                $CodePostBudget = $input['CodePostBudget'];
                $sql = 'select * from db_budgeting.cfg_set_post where CodePostBudget = ? and Active = 1';
                $query=$this->db->query($sql, array($CodePostBudget))->result_array();
                $Status = $query[0]['Status'];
                if ($Status == 1) {
                    try {
                       $get = $this->m_master->caribasedprimary('db_budgeting.cfg_set_post','CodePostBudget',$CodePostBudget); 
                       $time = date('Y-m-d H:i:s');
                       $dataSave = array(
                           'Budget' => $input['Budget'],
                           'LastUpdateBy' => $this->session->userdata('NIP'),
                           'LastUpdateAt' => $time,
                       );
                       $this->db->where('CodePostBudget', $CodePostBudget);
                       $this->db->update('db_budgeting.cfg_set_post', $dataSave);

                       $arr_detail = array(
                            'Before' => array(
                                   'Budget' => $get[0]['Budget'],
                            ),
                            'After' => array(
                                'Budget' => $input['Budget'],
                            ),    
                       );
                       $dataSave = array(
                           'CodePostBudget' => $CodePostBudget,
                           'Time' => $time,
                           'ActionBy' => $this->session->userdata('NIP'),
                           'Detail' => json_encode(array('action' => 'Edited','Detail' => $arr_detail)),
                       );
                       $this->db->insert('db_budgeting.log_cfg_set_post', $dataSave);
                    } catch (Exception $e) {
                         $Msg = $this->Msg['Error'];
                    }   
                }
                else
                {
                    $Msg = $this->Msg['NotAction'];
                }
                break;
            case 'delete':
                $CodePostBudget = $input['CodePostBudget'];
                $sql = 'select * from db_budgeting.cfg_set_post where CodePostBudget = ? and Active = 1';
                $query=$this->db->query($sql, array($CodePostBudget))->result_array();
                $Status = $query[0]['Status']; // check can be delete
                   if ($Status == 1) {
                       // $dataSave = array(
                       //     'Active' => 0
                       // );
                       // $this->db->where('CodePostBudget', $CodePostBudget);
                       // $this->db->where('Active', 1);
                       // $this->db->update('db_budgeting.cfg_set_post', $dataSave);
                        $this ->db-> where('CodePostBudget', $CodePostBudget);
                        $this ->db-> delete('db_budgeting.cfg_set_post');

                       $dataSave = array(
                           'CodePostBudget' => $CodePostBudget,
                           'Time' => date('Y-m-d H:i:s'),
                           'ActionBy' => $this->session->userdata('NIP'),
                           'Detail' => json_encode(array('action' => 'Delete')),
                       );
                       $this->db->insert('db_budgeting.log_cfg_set_post', $dataSave);
                   }
                   else
                   {
                       $Msg = $this->Msg['NotAction'];
                   }
                break;
            default:
                # code...
                break;
        }

        echo json_encode($Msg);
    }

    public function getBudgetLastYearByCode()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $LastYear = $input['Year'] - 1;
        $sql = 'select Budget from db_budgeting.cfg_set_post where CodeHeadAccount = ? and Year = ? and Active = 1 limit 1';
        $query=$this->db->query($sql, array($input['CodeHeadAccount'],$LastYear))->result_array();
        echo json_encode($query);
    }

    public function LogPostDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/setpostdepartement/pageLogPostDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function DataLogPostDepartement()
    {
        $requestData= $_REQUEST;
        $sqltotalData = 'select count(*) as total from db_budgeting.log_cfg_set_post';
        $querytotalData = $this->db->query($sqltotalData)->result_array();
        $totalData = $querytotalData[0]['total'];

        $sql = 'select a.*,b.PostName,b.CodePost,c.CodeHeadAccount,c.Name as NameHeadAccount,c.Departement,d.Year,e.Name as NameAction,e.NIP from db_budgeting.log_cfg_set_post as a
                join db_budgeting.cfg_set_post as d on a.CodePostBudget = d.CodePostBudget
                join db_budgeting.cfg_head_account as c on d.CodeHeadAccount =  c.CodeHeadAccount
                join db_budgeting.cfg_post as b on c.CodePost = b.CodePost
                join db_employees.employees as e on a.ActionBy = e.NIP   
               ';

        $sql.= ' where e.NIP LIKE "'.$requestData['search']['value'].'%" or e.Name LIKE "'.$requestData['search']['value'].'%" or a.CodePostBudget LIKE "'.$requestData['search']['value'].'%" or b.PostName LIKE "'.$requestData['search']['value'].'%" or c.Name LIKE "'.$requestData['search']['value'].'%"
                ';
        $sql.= ' ORDER BY a.Time Desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
        $query = $this->db->query($sql)->result_array();

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];

            $Departement = $row['Departement'];
            $exp = explode('.', $Departement);
            if ($exp[0] == 'NA') { // Non Academic
                $tget = $this->m_master->caribasedprimary('db_employees.division','ID',$exp[1]);
                $Departement = $tget[0]['Description'].' ('.$tget[0]['Division'].')';
            }
            elseif ($exp[0] == 'AC') {
                $tget = $this->m_master->caribasedprimary('db_academic.program_study','ID',$exp[1]);
                $Departement = $tget[0]['NameEng'];
            }

            $DayDateTime = '';
            $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $row['Time']);
            $DayDateTime = $datetime->format('D').','.$row['Time'];

            // get Detail 
            $JsonArr = json_decode($row['Detail']);
            $JsonAction = $JsonArr->action;
            $str = $JsonAction.'<br>';
            if (array_key_exists("Detail",$JsonArr)) {
                $JsonDetail = $JsonArr->Detail;
                $Count1 = count($JsonDetail);
                $No1 = 1;
                foreach ($JsonDetail as $key => $value) {
                    $str .= $key.' : ';
                    $Count2 = count($value);
                    $No2 = 1;
                    foreach ($value as $ac => $valuee) {
                        if ($No2 != $Count1) {
                            $str .= $ac.' = '.'Rp. '.number_format($valuee,0,",",".").',-'.' ; '; 
                        }
                        else
                        {
                            $str .= $ac.' = '.'Rp. '.number_format($valuee,0,",",".").',-'.'<br>';
                        }
                        $No2++;
                    }

                    $No1++;
                }
            }
            

            $nestedData[] = $row['CodePostBudget'];
            $nestedData[] = $row['CodeHeadAccount'].'<br>'.$row['PostName'].'-'.$row['NameHeadAccount'].'<br>'.$Departement;
            $nestedData[] = $row['NIP'].'<br>'.$row['NameAction'];
            $nestedData[] = $DayDateTime;
            $nestedData[] = $str;
            $data[] = $nestedData;
        }

        // print_r($data);

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function LoadSetUserRole()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/pageLoadSetUserRole',$this->data,true);
        echo json_encode($arr_result);
    }

    public function LoadMasterUserRoleDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        // pass check data existing
        $this->data['dt'] = $this->m_master->showData_array('db_budgeting.cfg_set_userrole');
        $this->data['cfg_m_userrole'] = $this->m_master->showData_array('db_budgeting.cfg_m_userrole');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/setuserrole/LoadMasterUserRoleDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function AutoCompletePostDepartement()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $data['response'] = 'true'; //mengatur response
        $data['message'] = array(); //membuat array
        $getData = $this->m_budgeting->getPostDepartementAutoComplete($input['PostDepartement']);
        for ($i=0; $i < count($getData); $i++) {
        // for ($i=0; $i < 2; $i++) {
            $data['message'][] = array(
                'label' => $getData[$i]['CodePostRealisasi'].' | '.$getData[$i]['PostName'].'-'.$getData[$i]['RealisasiPostName'].' | '.$getData[$i]['NameDepartement'],
                'value' => $getData[$i]['CodePostRealisasi']
            );
        }
        echo json_encode($data);
    }

    public function save_cfg_set_userrole()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        // check data insert or update
        $sql = 'select * from db_budgeting.cfg_set_userrole where CodePostRealisasi = ? and ID_m_userrole = ?';
        $query=$this->db->query($sql, array($Input['CodePostRealisasi'],$Input['id_m_userrole']))->result_array();
        if (count($query) > 0) {
            // update
            $dataSave = array(
                $Input['field'] => $Input['Input'],
            );
            $this->db->where('CodePostRealisasi', $Input['CodePostRealisasi']);
            $this->db->where('ID_m_userrole', $Input['id_m_userrole']);
            $this->db->update('db_budgeting.cfg_set_userrole', $dataSave);
        }
        else
        {
            // insert
            $dataSave = array(
                $Input['field'] => $Input['Input'],
                'CodePostRealisasi' => $Input['CodePostRealisasi'],
                'ID_m_userrole' => $Input['id_m_userrole']
            );
            $this->db->insert('db_budgeting.cfg_set_userrole', $dataSave);
        }

    }

    public function LoadSetUserApprovalDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $this->data['cfg_m_type_approval'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');
        // $this->data['employees'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/configuration/setuserrole/LoadSetUserApprovalDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function get_cfg_set_roleuser_budgeting($Departement)
    {
        $this->auth_ajax();
        $getData = $this->m_budgeting->get_cfg_set_roleuser_budgeting($Departement);
        echo json_encode($getData);
    }

    public function save_cfg_set_roleuser_budgeting()
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
                        $this->db->insert('db_budgeting.cfg_approval_budget',$FormInsert);
                    }
                    else
                    {
                        $ID = $Method['ID'];
                        $this->db->where('ID', $ID);
                        $this->db->update('db_budgeting.cfg_approval_budget', $FormInsert);
                    }
                }
                if ($msg['msg'] == '') {
                   $msg['status'] = 1;
                }
                break;
            case "delete":
                $ID = $Input['ID_set_roleuser'];
                $this->m_master->delete_id_table_all_db($ID,'db_budgeting.cfg_approval_budget');
                $msg['status'] = 1;
                break;
            default:
                # code...
                break;
        }
        

        echo json_encode($msg);
    }

    /*Note ***
    *    Budgeting Entry for All
    *Alhadi Rahman 02 Oktober 2018
    */

    public function entry_budgeting($Request = null)
    {
        $arr = array('EntryPostItemBudgeting',
                    'EntryBudget',
                    'Approval',
                    'ListBudgetDepartement',
                    null
                );
                if (in_array($Request, $arr))
                  {
                    $this->data['request'] = $Request;
                    $content = $this->load->view('global/budgeting/entry_budgeting',$this->data,true);
                    $this->temp($content);
                  }
                else
                  {
                    show_404($log_error = TRUE);
                  }
       
    }

    public function EntryBudget($Year = null)
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
        $this->data['arr_Year'] = $this->m_master->showData_array('db_budgeting.cfg_dateperiod');
        $Departement = $this->session->userdata('IDDepartementPUBudget');
        // filtering auth department cfg_approval_budget
        $arr_department_pu = $this->m_budgeting->Budget_department_auth($Departement);
        $this->data['arr_department_pu'] = $arr_department_pu;
        $arr_result['html'] = $this->load->view('global/budgeting/form_entry_budgeting',$this->data,true);
        echo json_encode($arr_result);
    }

    public function EntryBudget_Approval()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('global/budgeting/form_approval_budgeting',$this->data,true);
        echo json_encode($arr_result);
    }

    public function getLoadApprovalBudget($Year = null)
    {
        $Input = $this->getInputToken();
        $Departement = $Input['Departement'];
        $arr_result = array('html' => '','jsonPass' => '');
        $this->data['arr_Year'] = $this->m_master->showData_array('db_budgeting.cfg_dateperiod');
        $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
        $arr_bulan = $this->m_master->getShowIntervalBulan($get[0]['StartPeriod'],$get[0]['EndPeriod']);
        $Year = ($Year == null ) ? $get[0]['Year'] : $Year;
        $get = $this->m_budgeting->getPostDepartementForDomApproval($Year,$Departement);
        $this->data['fin'] = 0;
        $DepartementSess = $this->session->userdata('IDDepartementPUBudget');
        if ($DepartementSess == 'NA.9') {
            $this->data['fin'] = 1;
        }
        $this->data['Year'] = $Year;
        $this->data['Departement'] = $Departement;
        $this->data['arr_PostBudget'] = $get['data'];
        $this->data['arr_bulan'] = $arr_bulan;
        $arr_result['html'] = $this->load->view('global/budgeting/form_entry_budgeting',$this->data,true);
        echo json_encode($arr_result);
    }

    public function EntryPostItemBudgeting()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('global/budgeting/entry_post_item_budgeting',$this->data,true);
        echo json_encode($arr_result);
    } 

    public function getCreatorBudget()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $Year = $Input['Year'];
        $Departement = $Input['Departement'];
        $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Year',$Year);
        $arr_result = array('creator_budget_approval' => array(),'creator_budget' => array());
        $arr_bulan = $this->m_master->getShowIntervalBulan($get[0]['StartPeriod'],$get[0]['EndPeriod']);
        $arr_result['arr_bulan'] = $arr_bulan;
        $get = $this->m_budgeting->get_creator_budget_approval($Year,$Departement,'');
        if (count($get) > 0) {
            // get Creator Budget
            $ID_creator_budget_approval= $get[0]['ID'];
            $get2 = $this->m_budgeting->get_creator_budget($ID_creator_budget_approval);
            $arr_result['creator_budget_approval'] = $get;
            $arr_result['creator_budget'] = $get2;
        }
        
        $get = $this->m_budgeting->getPostDepartementForDomApproval($Year,$Departement);
        $arr_result['PostBudget'] = $get;
        $arr_result['Approval'] = $this->m_budgeting->get_cfg_set_roleuser_budgeting($Departement); 

        // adding department while the user have auth of custom approval was added by admin
            $arr_result['Add_department_IFCustom_approval'] = $this->m_budgeting->Add_department_IFCustom_approval($Year);
            $arr_result['m_type_user'] = $this->m_master->showData_array('db_budgeting.cfg_m_type_approval');

        echo json_encode($arr_result);
    }

    public function update_approval_budgeting()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $rs = array('msg' => '');
        switch ($Input['action']) {
            case 'add':
                // validation urutan Approver
                    $id_creator_budget_approval = $Input['id_creator_budget_approval'];
                    $G_data = $this->m_master->caribasedprimary('db_budgeting.creator_budget_approval','ID',$id_creator_budget_approval);
                    $JsonStatus = $G_data[0]['JsonStatus'];
                    $JsonStatus = (array)json_decode($JsonStatus,true);
                    $Approver = $Input['NIP'];
                    $indexjson = $Input['indexjson'];
                    $Visible = $Input['Visible'];
                    $NameTypeDesc = $Input['NameTypeDesc'];
                    $indexjsonAdd = count($JsonStatus); // hitung index array
                    if ($indexjson == $indexjsonAdd ) { // validation urutan Approver
                        $JsonStatus[] = array(
                            'NIP' => $Approver,
                            'Status' => 0,
                            'ApproveAt' => '',
                            'Representedby' => '',
                            'Visible' => $Visible,
                            'NameTypeDesc' => $NameTypeDesc,
                         );

                        $JsonStatusSave = json_encode($JsonStatus);
                        $dataSave = array(
                            'JsonStatus' => $JsonStatusSave,
                        );    
                        $this->db->where('ID',$id_creator_budget_approval);
                        $this->db->update('db_budgeting.creator_budget_approval',$dataSave);
                        
                        $rs['data']=  $JsonStatusSave;
                            // save to log
                                $this->m_budgeting->log_budget($id_creator_budget_approval,'Custom Approval',$By = $this->session->userdata('NIP')); 
                    }
                    else
                    {
                        $rs['msg'] = 'Please fill Approver '.(count($JsonStatus)+1);
                    }
                break;
            case 'edit':
                    $id_creator_budget_approval = $Input['id_creator_budget_approval'];
                    $G_data = $this->m_master->caribasedprimary('db_budgeting.creator_budget_approval','ID',$id_creator_budget_approval);
                    $JsonStatus = $G_data[0]['JsonStatus'];
                    $JsonStatus = (array)json_decode($JsonStatus,true);
                    $Approver = $Input['NIP'];
                    $indexjson = $Input['indexjson'];
                    $Visible = $Input['Visible'];
                    $NameTypeDesc = $Input['NameTypeDesc'];
                    $JsonStatus[$indexjson] = array(
                        'NIP' => $Approver,
                        'Status' => 0,
                        'ApproveAt' => '',
                        'Representedby' => '',
                        'Visible' => $Visible,
                        'NameTypeDesc' => $NameTypeDesc,
                    );
                    $JsonStatusSave = json_encode($JsonStatus);
                    $dataSave = array(
                        'JsonStatus' => $JsonStatusSave,
                    );    
                    $this->db->where('ID',$id_creator_budget_approval);
                    $this->db->update('db_budgeting.creator_budget_approval',$dataSave);
   
                    $rs['data']= $JsonStatusSave;
                    // save to log
                        $this->m_budgeting->log_budget($id_creator_budget_approval,'Custom Approval',$By = $this->session->userdata('NIP')); 
                break;    
            case 'delete':
                    $id_creator_budget_approval = $Input['id_creator_budget_approval'];
                    $indexjson = $Input['indexjson'];
                    $G_data = $this->m_master->caribasedprimary('db_budgeting.creator_budget_approval','ID',$id_creator_budget_approval);
                    $JsonStatus = $G_data[0]['JsonStatus'];
                    $JsonStatus = (array)json_decode($JsonStatus,true);
                    // Data json yang boleh dihapus adalah yang terakhir
                    $KeyJsonStatus = count($JsonStatus) - 1;
                    if ($indexjson == $KeyJsonStatus) {
                        $t = array();
                        for ($i=0; $i < count($JsonStatus) - 1; $i++) { // add 0 until last key - 1
                            $t[] = $JsonStatus[$i];
                        }

                        $JsonStatus = $t;
                        $JsonStatusSave = json_encode($JsonStatus);
                        $dataSave = array(
                            'JsonStatus' => $JsonStatusSave,
                        );
                        $this->db->where('ID',$id_creator_budget_approval);
                        $this->db->update('db_budgeting.creator_budget_approval',$dataSave);
                        $rs['msg'] = '';
                        $rs['data']= $JsonStatusSave;
                    }
                    else
                    {
                        $rs['msg'] = 'Please delete last Approval first';
                    }

                break;
            default:
                # code...
                break;
        }

        echo json_encode($rs);
    }

    public function saveCreatorbudget()
    {
        $this->auth_ajax();
        $msg = array('Status' => 0,'msg'=>'error');
        $Input = $this->getInputToken();
        $creator_budget = $Input['creator_budget'];
         $creator_budget_approval = $Input['creator_budget_approval'];
        // save to creator_budget
        switch ($Input['action']) {
            case 'add':
                // get rule approval
                    $Approval = $this->m_budgeting->get_approval_budgeting($creator_budget_approval->Departement);
                    $JsonStatus = array();
                    for ($i=0; $i < count($Approval); $i++) { 
                        $Status = 0;
                        $NIP = $Approval[$i]['NIP'];
                        $Visible = $Approval[$i]['Visible'];
                        $NameTypeDesc = $Approval[$i]['NameTypeDesc'];
                        $ApproveAt = '';
                        $Representedby = '';
                        $JsonStatus[] = array(
                            'NIP' => $NIP,
                            'Status' => $Status,
                            'ApproveAt' => $ApproveAt,
                            'Representedby' => $Representedby,
                            'Visible' => $Visible,
                            'NameTypeDesc' => $NameTypeDesc,
                        );

                    }

                $dataSave = array(
                    'Departement' => $creator_budget_approval->Departement,
                    'Year' => $creator_budget_approval->Year,
                    'Note' => $creator_budget_approval->Note,
                    'Status' => $creator_budget_approval->Status,
                    'JsonStatus' => json_encode($JsonStatus),
                );
                $this->db->insert('db_budgeting.creator_budget_approval', $dataSave);
                $ID_creator_budget_approval = $this->db->insert_id();

                for ($i=0; $i < count($creator_budget); $i++) { 
                    $CodePostRealisasi = $creator_budget[$i]->CodePostRealisasi;
                    $UnitCost = $creator_budget[$i]->UnitCost;
                    $Freq = $creator_budget[$i]->Freq;
                    $DetailMonth = $creator_budget[$i]->DetailMonth;
                    $DetailMonth = json_encode($DetailMonth);
                    $SubTotal = $creator_budget[$i]->SubTotal;

                    $dataSave = array(
                        'CodePostRealisasi' => $CodePostRealisasi,
                        'UnitCost' => $UnitCost,
                        'Freq' => $Freq,
                        'DetailMonth' => $DetailMonth,
                        'SubTotal' => $SubTotal,
                        'CreatedBy' => $this->session->userdata('NIP'),
                        'CreatedAt' => date('Y-m-d H:i:s'),
                        'ID_creator_budget_approval' => $ID_creator_budget_approval
                    );
                    $this->db->insert('db_budgeting.creator_budget', $dataSave);

                }

                // save date period
                    $update = array('Status' => 0);
                    $this->db->where('Year', $creator_budget_approval->Year);
                    $this->db->update('db_budgeting.cfg_dateperiod', $update);
                
                // save to log
                    $this->m_budgeting->log_budget($ID_creator_budget_approval,'Create',$By = $this->session->userdata('NIP'));    

                $msg = array('Status' => 1,'msg'=>$ID_creator_budget_approval );
                break;
            case 'edit':
                $ID = $Input['ID'];
                for ($i=0; $i < count($creator_budget); $i++) { 
                    $CodePostRealisasi = $creator_budget[$i]->CodePostRealisasi;
                    $UnitCost = $creator_budget[$i]->UnitCost;
                    $Freq = $creator_budget[$i]->Freq;
                    $DetailMonth = $creator_budget[$i]->DetailMonth;
                    $DetailMonth = json_encode($DetailMonth);
                    $SubTotal = $creator_budget[$i]->SubTotal;

                    $dataSave = array(
                        'UnitCost' => $UnitCost,
                        'Freq' => $Freq,
                        'DetailMonth' => $DetailMonth,
                        'SubTotal' => $SubTotal,
                        'LastUpdateBy' => $this->session->userdata('NIP'),
                        'LastUpdateAt' => date('Y-m-d H:i:s'),
                    );
                    $this->db->where('CodePostRealisasi', $CodePostRealisasi);
                    $this->db->update('db_budgeting.creator_budget', $dataSave);

                }

                $creator_budget_approval = $Input['creator_budget_approval'];
                // get Json Status to set All Status to 0
                    $G_data = $this->m_master->caribasedprimary('db_budgeting.creator_budget_approval','ID',$ID);
                    $JsonStatus =(array) json_decode($G_data[0]['JsonStatus'],true);
                    for ($i=0; $i < count($JsonStatus); $i++) { 
                        $JsonStatus[$i]['Status'] = 0;
                    }

                $dataSave = array(
                    'Note' => $creator_budget_approval->Note,
                    'Status' => $creator_budget_approval->Status,
                    'JsonStatus' => json_encode($JsonStatus),
                );
                $this->db->where('ID', $ID);
                $this->db->update('db_budgeting.creator_budget_approval', $dataSave);

                $st = ($creator_budget_approval->Status == 0 || $creator_budget_approval->Status == '0') ? 'Edited' : 'Issued / Submit';
                // save to log
                    $this->m_budgeting->log_budget($ID,$st,$By = $this->session->userdata('NIP')); 

                $msg = array('Status' => 1,'msg'=>$ID );
                break;
            default:
                # code...
                break;
        }
        echo json_encode($msg);
    }

    public function Upload_File_Creatorbudget()
    {
        $input = $this->getInputToken();
        // upload file
        $filename = $input['attachName'].'.'.$input['extension'];
        $config['upload_path']   = './uploads/budgeting';
        $config['overwrite'] = TRUE; 
        $config['allowed_types'] = '*'; 
        $config['file_name'] = $filename;
        //$config['max_size']      = 100; 
        //$config['max_width']     = 300; 
        //$config['max_height']    = 300;  
        $this->load->library('upload', $config);
           
        if ( ! $this->upload->do_upload('fileData')) {
           // return $error = $this->upload->display_errors(); 
           echo json_encode(array('msg' => 'The file did not upload successfully','status' => 0));
           //$this->load->view('upload_form', $error); 
        }
           
        else { 
          // return $data =  $this->upload->data(); 
            // update data to save file
                $dataSave['FileUpload'] = $filename;
                $ID_creator_budget_approval = $input['id_creator_budget_approval'];
                $this->db->where('ID', $ID_creator_budget_approval);
                $this->db->update('db_budgeting.creator_budget_approval', $dataSave);

          echo json_encode(array('msg' => 'The file has been successfully uploaded','status' => 1,'filename' => $filename));
        }
    }

    public function Upload_File_Creatorbudget_all()
    {
        $input = $this->getInputToken();
        // upload file
        $filename = $input['attachName'].'.'.$input['extension'];
        $config['upload_path']   = './uploads/budgeting';
        $config['overwrite'] = TRUE; 
        $config['allowed_types'] = '*'; 
        $config['file_name'] = $filename;
        //$config['max_size']      = 100; 
        //$config['max_width']     = 300; 
        //$config['max_height']    = 300;  
        $this->load->library('upload', $config);
           
        if ( ! $this->upload->do_upload('fileData')) {
           // return $error = $this->upload->display_errors(); 
           echo json_encode(array('msg' => 'The file did not upload successfully','status' => 0));
           //$this->load->view('upload_form', $error); 
        }
           
        else { 
          // return $data =  $this->upload->data(); 
            // update data to save file
                $dataSave['BudgetApproveUpload'] = $filename;
                $year = $input['year'];
                $this->db->where('Year', $year);
                $this->db->update('db_budgeting.cfg_dateperiod', $dataSave);

          echo json_encode(array('msg' => 'The file has been successfully uploaded','status' => 1,'filename' => $filename));
        }
    }    

    public function ListBudgetDepartement()
    {
        $this->auth_ajax();
        $this->authFin();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/budget/ListBudgetDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function authFin()
    {
        $DepartementSess = $this->session->userdata('IDDepartementPUBudget');
        if ($DepartementSess != 'NA.9') {
            exit('No direct script access allowed');
        }
        
    }

    public function getListBudgetingDepartement()
    {
        $this->auth_ajax();
        $rs = array('dt' => array(),'dt_Year' => array());
        $Input = $this->getInputToken();
        $Year = $Input['Year'];
        $dt = $this->m_budgeting->getListBudgetingDepartement($Year);
        $dt_Year = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Year',$Year);
        $rs['dt'] = $dt;
        $rs['dt_Year'] = $dt_Year;
        echo json_encode($rs);
    }

    /*
        End Budgeting
        27 March 2019
        Alhadi Rahman
    */

    public function BudgetLeft()
    {
        $this->auth_ajax();
        $Departement = $this->session->userdata('IDDepartementPUBudget');
        switch ($Departement) {
            case 'NA.9':
                $this->BudgetRemainingFinance();
                break;
            
            default:
                $this->BudgetRemainingPerDiv();
                break;
        }
    }

    public function BudgetRemainingFinance()
    {
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/budgeting/'.$this->data['department'].'/budget/BudgetRemaining',$this->data,true);
        echo json_encode($arr_result);
    }

    public function BudgetRemainingPerDiv()
    {

    }

    public function getListBudgetingRemaining()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $Year = $Input['Year'];
        $get = $this->m_budgeting->getListBudgetingRemaining($Year);
        echo json_encode($get);
    }

    public function detail_budgeting_remaining()
    {
        $this->auth_ajax();
        $arr_result = array('data' =>'');
        $Input = $this->getInputToken();
        $Year = $Input['Year'];
        $Departement = $Input['Departement'];
        $getData = $this->m_budgeting->get_budget_remaining($Year,$Departement);
        $arr_result = array('data' =>$getData);
        echo json_encode($arr_result);
    }

    public function detail_budgeting_remaining_All()
    {
        $this->auth_ajax();
        $arr_result = array('data' =>'');
        $Input = $this->getInputToken();
        $Year = $Input['Year'];
        $getData = $this->m_budgeting->get_budget_remaining_all($Year);
        $arr_result = array('data' =>$getData);
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

    public function pr()
    {
        $content = $this->load->view('global/budgeting/pr/page',$this->data,true);
        $this->temp($content);
    }

    public function page_pr()
    {
      $this->auth_ajax();
      $arr_result = array('html' => '','jsonPass' => '');
      $uri = $this->uri->segment(3);
      switch ($uri) {
          case 'Catalog':
              // $this->data['action'] = 'add';
              // if (empty($_POST) && count($_POST) > 0 ){
              //     $Input = $this->getInputToken();
              //     $this->data['action'] = $Input['action'];
              //     if ($Input['action'] == 'edit') {
              //         $this->data['get'] = $this->m_master->caribasedprimary('db_purchasing.m_catalog','ID',$Input['ID']);
              //     }
              // }
              // $content = $this->load->view('global/budgeting/pr/'.$uri,$this->data,true);
              // $arr_result['html'] = $content;
              $content = $this->load->view('global/budgeting/pr/'.$uri,$this->data,true);
              $arr_result['html'] = $content;
              break;
          
          default:
              $this->data['G_Approver'] = $this->m_budgeting->Get_m_Approver();
              $this->data['arr_Year'] = $this->m_master->showData_array('db_budgeting.cfg_dateperiod');
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

    public function PostBudgetThisMonth_Department()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $Month = date('Y-m');
        $Departement = $Input['Departement'];
        $PostBudget = $Input['PostBudget'];
        $get = $this->m_budgeting->PostBudgetThisMonth_Department($Departement,$PostBudget,$Month);
        echo json_encode($get);
    }

    public function getPostBudgetDepartement()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $Departement = $Input['Departement'];
        $Year = $Input['Year'];
        $get = $this->m_budgeting->getPostBudgetDepartement($Departement,$Year);
        echo json_encode($get);
    }

    public function submitpr()
    {
        $action = $this->input->post('Action');
        switch ($action) {
            case 0:
                $this->AddPrToDraft();
                break;
            case 1:
                $this->PRToIssued();
                break;
            default:
                # code...
                break;
        }
    }

    private function AddPrToDraft()
    {
        $input = $this->getInputToken();

        $Year = $this->input->post('Year');
        $key = "UAP)(*";
        $Year = $this->jwt->decode($Year,$key);

        $Departement = $this->input->post('Departement');
        $key = "UAP)(*";
        $Departement = $this->jwt->decode($Departement,$key);

        $PPN = $this->input->post('PPN');
        $key = "UAP)(*";
        $PPN = $this->jwt->decode($PPN,$key);

        $PRCode = $this->input->post('PRCode');
        $key = "UAP)(*";
        $PRCode = $this->jwt->decode($PRCode,$key);
        $act = $PRCode;

        $Notes = $this->input->post('Notes');
        $key = "UAP)(*";
        $Notes = $this->jwt->decode($Notes,$key);

        // adding Supporting_documents
            $Supporting_documents = array();
            $Supporting_documents = json_encode($Supporting_documents); 
            if (array_key_exists('Supporting_documents', $_FILES)) {
                // do upload file
                $uploadFile = $this->uploadDokumenMultiple(uniqid(),'Supporting_documents');
                $Supporting_documents = json_encode($uploadFile); 
            }


        $PRCode = ($act == '') ? $this->m_budgeting->Get_PRCode2($Departement) : $PRCode;
        // print_r($PRCode);die();
        if ($act == '') {
            $dataSave = array(
                'PRCode' => $PRCode,
                'Year' => $Year,
                'Departement' => $Departement,
                'CreatedBy' => $this->session->userdata('NIP'),
                'CreatedAt' => date('Y-m-d H:i:s'),
                'JsonStatus' => json_encode(array()),
                'Status' => 0,
                'PPN' => $PPN,
                'PRPrint_Approve' => '',
                'Notes' => $Notes,
                'Supporting_documents' => $Supporting_documents,
            );

            $this->db->insert('db_budgeting.pr_create',$dataSave);
            if ($this->db->affected_rows() > 0 )
            {
                for ($i=0; $i < count($input); $i++) {
                    $data = $input[$i]; 
                    $key = "UAP)(*";
                    $data_arr = (array) $this->jwt->decode($data,$key);

                    // proses upload file
                        if (array_key_exists('UploadFile'.$i, $_FILES)) {
                            // do upload file
                            $uploadFile = $this->uploadDokumenMultiple(mt_rand(),'UploadFile'.$i);
                            $data_arr['UploadFile'] = json_encode($uploadFile); 
                        }

                        // exclude 
                        $Combine =  (array)  json_decode(json_encode($data_arr['FormInsertCombine']),true);
                        unset($data_arr['FormInsertCombine']);

                    $data_arr['PRCode'] = $PRCode;    
                    $this->db->insert('db_budgeting.pr_detail',$data_arr);
                    // insert combine budgeting
                    $getID = $this->db->insert_id();
                    if (count($Combine) > 0) {
                        for ($j=0; $j <count($Combine) ; $j++) { 
                            $dataSave_combine = array(
                                'ID_pr_detail' => $getID,
                                'ID_budget_left' => $Combine[$j]['id_budget_left'],
                                'Cost' => $Combine[$j]['cost'],
                                'Estvalue' => $Combine[$j]['estvalue'],
                            );
                            $this->db->insert('db_budgeting.pr_detail_combined',$dataSave_combine);
                        }
                        
                    }

                    // make can be delete
                       $tbl = 'db_purchasing.m_catalog';
                       $fieldCode = 'ID';
                       $ValueCode = $data_arr['ID_m_catalog'];
                       $this->m_budgeting->makeCanBeDelete($tbl,$fieldCode,$ValueCode);
                }

                // insert to pr_circulation_sheet
                    $this->m_budgeting->pr_circulation_sheet($PRCode,'Created');
            }
            else
            {
                //return FALSE;
                $PRCode = '';
            }
        }
        else
        {
            $dataSave = array(
                'CreatedBy' => $this->session->userdata('NIP'),
                'CreatedAt' => date('Y-m-d H:i:s'),
                'PPN' => $PPN,
                'Notes' => $Notes,
                'Supporting_documents' => $Supporting_documents,
            );

            // jika dari reject go back status ke 0
                $G_data = $this->m_master->caribasedprimary('db_budgeting.pr_create','PRCode',$PRCode);
                if ($G_data[0]['Status'] ==  3 || $G_data[0]['Status'] ==  4) {
                    $JsonStatus = $G_data[0]['JsonStatus'];
                    $JsonStatus = (array) json_decode($JsonStatus,true);
                    // update all 0 agar bisa di approve ulang
                    for ($i=0; $i < count($JsonStatus); $i++) { 
                        $JsonStatus[$i]['Status'] = 0;
                        $JsonStatus[$i]['ApproveAt'] = '';
                    }
                    $dataSave['JsonStatus'] = json_encode($JsonStatus);
                    $dataSave['Status'] = 0;
                }

            $this->db->where('PRCode',$PRCode);
            $this->db->update('db_budgeting.pr_create',$dataSave);
            if ($this->db->affected_rows() > 0 )
            {
                // remove PRCode in pr_detail
                    $this->db->where(array('PRCode' => $PRCode));
                    $this->db->delete('db_budgeting.pr_detail');
                for ($i=0; $i < count($input); $i++) {
                    $data = $input[$i]; 
                    $key = "UAP)(*";
                    $data_arr = (array) $this->jwt->decode($data,$key);

                    // proses upload file
                        if (array_key_exists('UploadFile'.$i, $_FILES)) {
                            // do upload file
                            $uploadFile = $this->uploadDokumenMultiple(mt_rand(),'UploadFile'.$i);
                            $data_arr['UploadFile'] = json_encode($uploadFile); 
                        }
                        // exclude 
                        $Combine =  (array)  json_decode(json_encode($data_arr['FormInsertCombine']),true);
                        unset($data_arr['FormInsertCombine']);

                    $data_arr['PRCode'] = $PRCode;    
                    $this->db->insert('db_budgeting.pr_detail',$data_arr);
                    // insert combine budgeting
                    $getID = $this->db->insert_id();
                    if (count($Combine) > 0) {
                        for ($j=0; $j <count($Combine) ; $j++) { 
                            $dataSave_combine = array(
                                'ID_pr_detail' => $getID,
                                'ID_budget_left' => $Combine[$j]['id_budget_left'],
                                'Cost' => $Combine[$j]['cost'],
                            );
                            $this->db->insert('db_budgeting.pr_detail_combined',$dataSave_combine);
                        }
                        
                    }

                }

                // insert to pr_circulation_sheet
                    $this->m_budgeting->pr_circulation_sheet($PRCode,'Edited');
            }
            else
            {
                //return FALSE;
                $PRCode = '';
            }
        }
        echo json_encode($PRCode);
        
    }

    private function uploadDokumenMultiple($filename,$ggFiles = 'UploadFile')
    {
        $path = './uploads/budgeting/pr';
        // Count total files
        $countfiles = count($_FILES[$ggFiles ]['name']);
      
      $output = array();
      // Looping all files
      for($i=0;$i<$countfiles;$i++){
            $config = array();
            if(!empty($_FILES[$ggFiles ]['name'][$i])){
     
              // Define new $_FILES array - $_FILES['file']
              $_FILES['file']['name'] = $_FILES[$ggFiles]['name'][$i];
              $_FILES['file']['type'] = $_FILES[$ggFiles]['type'][$i];
              $_FILES['file']['tmp_name'] = $_FILES[$ggFiles]['tmp_name'][$i];
              $_FILES['file']['error'] = $_FILES[$ggFiles]['error'][$i];
              $_FILES['file']['size'] = $_FILES[$ggFiles]['size'][$i];

              // Set preference
              $config['upload_path'] = $path.'/';
              $config['allowed_types'] = '*';
              $config['overwrite'] = TRUE; 
              $no = $i + 1;
              $config['file_name'] = $filename.'_'.$no;

              $filenameUpload = $_FILES['file']['name'];
              $ext = pathinfo($filenameUpload, PATHINFO_EXTENSION);
              $filenameNew = $filename.'_'.$no.'_'.mt_rand().'.'.$ext;
     
              //Load upload library
              $this->load->library('upload',$config); 
              $this->upload->initialize($config);
     
              // File upload
              if($this->upload->do_upload('file')){
                // Get data about the file
                $uploadData = $this->upload->data();
                $filePath = $uploadData['file_path'];
                $filename_uploaded = $uploadData['file_name'];
                // rename file
                $old = $filePath.'/'.$filename_uploaded;
                $new = $filePath.'/'.$filenameNew;

                rename($old, $new);

                $output[] = $filenameNew;
              }
            }
        }
        return $output;
    }

    private function PRToIssued()
    {
        $input = $this->getInputToken();

        $Year = $this->input->post('Year');
        $key = "UAP)(*";
        $Year = $this->jwt->decode($Year,$key);

        $Departement = $this->input->post('Departement');
        $key = "UAP)(*";
        $Departement = $this->jwt->decode($Departement,$key);

        $PPN = $this->input->post('PPN');
        $key = "UAP)(*";
        $PPN = $this->jwt->decode($PPN,$key);

        $PRCode = $this->input->post('PRCode');
        $key = "UAP)(*";
        $PRCode = $this->jwt->decode($PRCode,$key);

        $Notes = $this->input->post('Notes');
        $key = "UAP)(*";
        $Notes = $this->jwt->decode($Notes,$key);

        // adding Supporting_documents
            $Supporting_documents = array();
            $Supporting_documents = json_encode($Supporting_documents); 
            if (array_key_exists('Supporting_documents', $_FILES)) {
                // do upload file
                $uploadFile = $this->uploadDokumenMultiple(uniqid(),'Supporting_documents');
                $Supporting_documents = json_encode($uploadFile); 
            }

        // RuleApproval
            // check Subtotal
                $Amount = 0;
                for ($i=0; $i < count($input); $i++) {
                    $data = $input[$i]; 
                    $key = "UAP)(*";
                    $data_arr = (array) $this->jwt->decode($data,$key);
                    $SubTotal = $data_arr['SubTotal'];
                    $Amount = $Amount + $SubTotal;
                }

            // $JsonStatus = $this->m_budgeting->GetRuleApproval_PR_JsonStatus($Departement,$Amount);
            $JsonStatus = $this->m_budgeting->GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$PRCode);

        $dataSave = array(
            'CreatedBy' => $this->session->userdata('NIP'),
            'CreatedAt' => date('Y-m-d H:i:s'),
            'Status' => 1,
            'JsonStatus' => json_encode($JsonStatus),
            'PPN' => $PPN,
            'Notes' => $Notes,
            'Supporting_documents' => $Supporting_documents,
        );

        $this->db->where('PRCode',$PRCode);
        $this->db->update('db_budgeting.pr_create',$dataSave);

        // passing show name JsonStatus
        for ($i=0; $i < count($JsonStatus); $i++) { 
            $Name = $this->m_master->caribasedprimary('db_employees.employees','NIP',$JsonStatus[$i]['ApprovedBy']);
            $Name = $Name[0]['Name'];
            $JsonStatus[$i]['NameApprovedBy'] = $Name;
         } 

        if ($this->db->affected_rows() > 0 )
        {
            // remove PRCode in pr_detail
                $this->db->where(array('PRCode' => $PRCode));
                $this->db->delete('db_budgeting.pr_detail');
            for ($i=0; $i < count($input); $i++) {
                $data = $input[$i]; 
                $key = "UAP)(*";
                $data_arr = (array) $this->jwt->decode($data,$key);

                // proses upload file
                    if (array_key_exists('UploadFile'.$i, $_FILES)) {
                        // do upload file
                        $uploadFile = $this->uploadDokumenMultiple(mt_rand(),'UploadFile'.$i);
                        $data_arr['UploadFile'] = json_encode($uploadFile); 
                    }

                    // exclude 
                        $Combine =  (array)  json_decode(json_encode($data_arr['FormInsertCombine']),true);
                        unset($data_arr['FormInsertCombine']);

                    $data_arr['PRCode'] = $PRCode;    
                    $this->db->insert('db_budgeting.pr_detail',$data_arr);
                    // insert combine budgeting
                    $getID = $this->db->insert_id();
                    if (count($Combine) > 0) {
                        for ($j=0; $j <count($Combine) ; $j++) { 
                            $dataSave_combine = array(
                                'ID_pr_detail' => $getID,
                                'ID_budget_left' => $Combine[$j]['id_budget_left'],
                                'Cost' => $Combine[$j]['cost'],
                                'Estvalue' => $Combine[$j]['estvalue'],
                            );
                            $this->db->insert('db_budgeting.pr_detail_combined',$dataSave_combine);
                        }
                        
                    }

            }

            // insert to pr_circulation_sheet
                $this->m_budgeting->pr_circulation_sheet($PRCode,'Issued');
        }
        else
        {
            //return FALSE;
            $PRCode = '';
        }
        echo json_encode(array('PRCode' => $PRCode,'JsonStatus' => json_encode($JsonStatus)) );
        
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

        $G_Approver = $this->m_budgeting->Get_m_Approver();
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

    public function FormEditPR()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $PRCode = $input['PRCode'];
        $Departement = $input['department'];
        $this->data['arr_Year'] = $this->m_master->showData_array('db_budgeting.cfg_dateperiod');
        $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
        $Year = $get[0]['Year'];
        $this->data['Year'] = $Year;
        $this->data['PRCodeVal'] = $PRCode;
        $this->data['Departement'] = $Departement;
        $arr_result = array('html' => '','jsonPass' => '');
        $content = $this->load->view('global/budgeting/pr/form',$this->data,true);
        $arr_result['html'] = $content;
        echo json_encode($arr_result);
    }

    public function GetDataPR()
    {
        $this->auth_ajax();
        $input = $this->getInputToken();
        $arr_result = array('pr_create' => array(),'pr_detail' => array());
        $arr_result['pr_create'] = $this->m_budgeting->GetPR_CreateByPRCode($input['PRCode']);
        $arr_result['pr_detail'] = $this->m_budgeting->GetPR_DetailByPRCode($input['PRCode']);
        echo json_encode($arr_result);
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
                    $GetRuleAccess = $this->m_budgeting->GetRuleAccess($NIP,$Departement);
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
                     $GetRuleAccess = $this->m_budgeting->GetRuleAccess($NIP,$Departement);
                }

            }
        }
        else
        {
            // print_r($Departement);
            $GetRuleAccess = $this->m_budgeting->GetRuleAccess($NIP,$Departement);
        }

        echo json_encode($GetRuleAccess);
    }

    public function update_approver()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $rs = array('msg' => '');
        switch ($Input['action']) {
            case 'add':
                // validation urutan Approver
                    $PRCode = $Input['PRCode'];
                    $G_data = $this->m_master->caribasedprimary('db_budgeting.pr_create','PRCode',$PRCode);
                    $JsonStatus = $G_data[0]['JsonStatus'];
                    $JsonStatus = (array)json_decode($JsonStatus,true);
                    $Approver = $Input['Approver'];
                    $indexjson = $Input['indexjson'];
                    $indexjsonAdd = count($JsonStatus); // hitung index array
                    if ($indexjson == $indexjsonAdd ) { // validation urutan Approver
                        $JsonStatus[] = array(
                             'ApprovedBy' => $Approver,
                             'Status' => 0,
                             'ApproveAt' => '',
                             'Representedby' => '',
                         );

                        $JsonStatusSave = json_encode($JsonStatus);
                        $dataSave = array(
                            'JsonStatus' => $JsonStatusSave,
                        );    
                        $this->db->where('PRCode',$PRCode);
                        $this->db->update('db_budgeting.pr_create',$dataSave);
                        // get Name Approver for callback
                            for ($i=0; $i < count($JsonStatus); $i++) { 
                                $Name = $this->m_master->caribasedprimary('db_employees.employees','NIP',$JsonStatus[$i]['ApprovedBy']);
                                $Name = $Name[0]['Name'];
                                $JsonStatus[$i]['NameApprovedBy'] = $Name; 
                            }
                            
                            $rs['data']= $JsonStatus;
                            // insert to pr_circulation_sheet
                                $this->m_budgeting->pr_circulation_sheet($PRCode,'Custom Approval');
                    }
                    else
                    {
                        $rs['msg'] = 'Please fill Approver '.(count($JsonStatus)+1);
                    }
                break;
            case 'edit':
                    $PRCode = $Input['PRCode'];
                    $G_data = $this->m_master->caribasedprimary('db_budgeting.pr_create','PRCode',$PRCode);
                    $JsonStatus = $G_data[0]['JsonStatus'];
                    $JsonStatus = (array)json_decode($JsonStatus,true);
                    $Approver = $Input['Approver'];
                    $indexjson = $Input['indexjson'];
                    $JsonStatus[$indexjson] = array(
                        'ApprovedBy' => $Approver,
                        'Status' => 0,
                        'ApproveAt' => '',
                        'Representedby' => '',
                    );
                    $JsonStatusSave = json_encode($JsonStatus);
                    $dataSave = array(
                        'JsonStatus' => $JsonStatusSave,
                    );    
                    $this->db->where('PRCode',$PRCode);
                    $this->db->update('db_budgeting.pr_create',$dataSave);
                    for ($i=0; $i < count($JsonStatus); $i++) { 
                        $Name = $this->m_master->caribasedprimary('db_employees.employees','NIP',$JsonStatus[$i]['ApprovedBy']);
                        $Name = $Name[0]['Name'];
                        $JsonStatus[$i]['NameApprovedBy'] = $Name; 
                    }
                    
                    $rs['data']= $JsonStatus;
                    // insert to pr_circulation_sheet
                        $this->m_budgeting->pr_circulation_sheet($PRCode,'Custom Approval');
                break;    
            case 'delete':
                    $PRCode = $Input['PRCode'];
                    $indexjson = $Input['indexjson'];
                    $G_data = $this->m_master->caribasedprimary('db_budgeting.pr_create','PRCode',$PRCode);
                    $JsonStatus = $G_data[0]['JsonStatus'];
                    $JsonStatus = (array)json_decode($JsonStatus,true);
                    // Data json yang boleh dihapus adalah yang terakhir
                    $KeyJsonStatus = count($JsonStatus) - 1;
                    if ($indexjson == $KeyJsonStatus) {
                        $t = array();
                        for ($i=0; $i < count($JsonStatus) - 1; $i++) { // add 0 until last key - 1
                            $Name = $this->m_master->caribasedprimary('db_employees.employees','NIP',$JsonStatus[$i]['ApprovedBy']);
                            $Name = $Name[0]['Name'];
                            $JsonStatus[$i]['NameApprovedBy'] = $Name;
                            $t[] = $JsonStatus[$i];
                        }

                        $JsonStatus = $t;
                        $JsonStatusSave = json_encode($JsonStatus);
                        $dataSave = array(
                            'JsonStatus' => $JsonStatusSave,
                        );
                        $this->db->where('PRCode',$PRCode);
                        $this->db->update('db_budgeting.pr_create',$dataSave);
                        $rs['msg'] = '';
                        $rs['data']= $JsonStatus;
                    }
                    else
                    {
                        $rs['msg'] = 'Please delete last Approver first';
                    }

                break;
            default:
                # code...
                break;
        }

        echo json_encode($rs);
    }

}
