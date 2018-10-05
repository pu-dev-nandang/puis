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
        $this->load->model('master/m_master');
        $this->data['department'] = parent::__getDepartement(); 
    }

    public function index()
    {
        $this->session->unset_userdata('auth_budgeting_sess');
        $this->session->unset_userdata('menu_budgeting_sess');
        $this->session->unset_userdata('menu_budgeting_grouping');
        $this->session->unset_userdata('role_user_budgeting');
        $IDdepartementNavigation = $this->session->userdata('IDdepartementNavigation');
        switch ($IDdepartementNavigation) {
            case 12: // IT
                // print_r($IDdepartementNavigation);
                $this->BudgetingIT();
                break;
            case 9: // Finance
                // print_r($IDdepartementNavigation);
                $this->BudgetingFinance();
                break;   
            case 8: // Adum
                // print_r($IDdepartementNavigation);
                $this->BudgetingAdum();
                break;     
            default:
                # code...
                break;
        }
        
    }

    public function BudgetingIT()
    {
         // echo __FUNCTION__;
        // get previleges for menu and content
        $MenuDepartement= 'NA.'.$this->session->userdata('IDdepartementNavigation');
        $this->getAuthSession($MenuDepartement);
        // $content = '<pre>'.print_r($this->session->userdata('menu_budgeting_grouping')).'</pre>';
        $content = $this->load->view('page/'.$this->data['department'].'/budgeting/dashboard',$this->data,true);
        $this->temp($content);
    }

    public function BudgetingFinance()
    {
        // get previleges for menu and content
        $MenuDepartement= 'NA.'.$this->session->userdata('IDdepartementNavigation');
        $this->getAuthSession($MenuDepartement);
        // $content = '<pre>'.print_r($this->session->userdata('menu_budgeting_grouping')).'</pre>';
        $content = $this->load->view('page/'.$this->data['department'].'/budgeting/dashboard',$this->data,true);
        $this->temp($content);
    }

    public function BudgetingAdum()
    {
        $MenuDepartement= 'NA.'.$this->session->userdata('IDdepartementNavigation');
        $this->getAuthSession($MenuDepartement);
        $content = $this->load->view('page/'.$this->data['department'].'/budgeting/dashboard',$this->data,true);
        $this->temp($content);
    }

    public function configfinance($Request = null)
    {
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
            $content = $this->load->view('page/'.$this->data['department'].'/budgeting/configfinance',$this->data,true);
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
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/pageLoadTimePeriod',$this->data,true);
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
        echo $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/modalform_timeperiod',$this->data,true);
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
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/pageloadCodePrefix',$this->data,true);
        echo json_encode($arr_result);
    }

    public function pageloadMasterPost()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/pageloadMasterPost',$this->data,true);
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
        echo $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/modalform_masterpost',$this->data,true);

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
                        $Msg = $this->Msg['NotAction'];
                    }
                }
                break;
            case 'delete':
                $CodePost = $input['CDID'];
                $sql = 'select * from db_budgeting.cfg_post where CodePost = ? and Active = 1';
                $query=$this->db->query($sql, array($CodePost))->result_array();
                $Status = $query[0]['Status']; // check can be delete
                   if ($Status == 1) {
                       $dataSave = array(
                           'Active' => 0
                       );
                       $this->db->where('CodePost', $CodePost);
                       $this->db->where('Active', 1);
                       $this->db->update('db_budgeting.cfg_post', $dataSave);
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
        echo $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/modal_postrealisasi',$this->data,true);
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
                       'CodePost' => $input['PostItem'],
                       'RealisasiPostName' => trim(ucwords($input['RealisasiPostName'])),
                       'Departement' => $input['Departement'],
                       'CreatedBy' => $this->session->userdata('NIP'),
                       'CreatedAt' => date('Y-m-d'),
                   );
                   $this->db->insert('db_budgeting.cfg_postrealisasi', $dataSave);

                   $tbl = 'db_budgeting.cfg_post';
                   $fieldCode = 'CodePost';
                   $ValueCode = $input['PostItem'];
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
                               'CodePost' => $input['PostItem'],
                               'Departement' => $input['Departement'],
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
                        $Msg = $this->Msg['NotAction'];
                    }
                }
                break;
            case 'delete':
                $CodePostRealisasi = $input['CDID'];
                $sql = 'select * from db_budgeting.cfg_postrealisasi where CodePostRealisasi = ? and Active = 1';
                $query=$this->db->query($sql, array($CodePostRealisasi))->result_array();
                $Status = $query[0]['Status']; // check can be delete
                   if ($Status == 1) {
                       $dataSave = array(
                           'Active' => 0
                       );
                       $this->db->where('CodePostRealisasi', $CodePostRealisasi);
                       $this->db->where('Active', 1);
                       $this->db->update('db_budgeting.cfg_postrealisasi', $dataSave);
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

    public function LoadSetPostDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/pageSetPostDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function LoadInputsetPostDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/setpostdepartement/pageInputsetPostDepartement',$this->data,true);
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
                $Q_get = $this->m_master->caribasedprimary('db_budgeting.cfg_postrealisasi','CodePostRealisasi',$input['CodeSubPost']);
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

                $sql = 'select * from db_budgeting.cfg_set_post where CodeSubPost = ? and Active = 1 and Year = ?';
                $query=$this->db->query($sql, array($input['CodeSubPost'],$input['Year']))->result_array();
                if (count($query) > 0) {
                   $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                   $dataSave = array(
                       'CodePostBudget' => $CodePostBudget,
                       'CodeSubPost' => $input['CodeSubPost'],
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

                   $tbl = 'db_budgeting.cfg_postrealisasi';
                   $fieldCode = 'CodePostRealisasi';
                   $ValueCode = $input['CodeSubPost'];
                   $this->m_budgeting->makeCanBeDelete($tbl,$fieldCode,$ValueCode);
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
                       $dataSave = array(
                           'Active' => 0
                       );
                       $this->db->where('CodePostBudget', $CodePostBudget);
                       $this->db->where('Active', 1);
                       $this->db->update('db_budgeting.cfg_set_post', $dataSave);

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
        $sql = 'select Budget from db_budgeting.cfg_set_post where CodeSubPost = ? and Year = ? and Active = 1 limit 1';
        $query=$this->db->query($sql, array($input['CodePostRealisasi'],$LastYear))->result_array();
        echo json_encode($query);
    }

    public function LogPostDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/setpostdepartement/pageLogPostDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function DataLogPostDepartement()
    {
        $requestData= $_REQUEST;
        $sqltotalData = 'select count(*) as total from db_budgeting.log_cfg_set_post';
        $querytotalData = $this->db->query($sqltotalData)->result_array();
        $totalData = $querytotalData[0]['total'];

        $sql = 'select a.*,b.PostName,b.CodePost,c.CodePostRealisasi,c.RealisasiPostName,c.Departement,d.Year,e.Name as NameAction,e.NIP from db_budgeting.log_cfg_set_post as a
                join db_budgeting.cfg_set_post as d on a.CodePostBudget = d.CodePostBudget
                join db_budgeting.cfg_postrealisasi as c on d.CodeSubPost =  c.CodePostRealisasi
                join db_budgeting.cfg_post as b on c.CodePost = b.CodePost
                join db_employees.employees as e on a.ActionBy = e.NIP   
               ';

        $sql.= ' where e.NIP LIKE "'.$requestData['search']['value'].'%" or e.Name LIKE "'.$requestData['search']['value'].'%" or a.CodePostBudget LIKE "'.$requestData['search']['value'].'%" or b.PostName LIKE "'.$requestData['search']['value'].'%" or c.RealisasiPostName LIKE "'.$requestData['search']['value'].'%"
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
            $nestedData[] = $row['CodePostRealisasi'].'<br>'.$row['PostName'].'-'.$row['RealisasiPostName'].'<br>'.$Departement;
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
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/pageLoadSetUserRole',$this->data,true);
        echo json_encode($arr_result);
    }

    public function LoadMasterUserRoleDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/setuserrole/LoadMasterUserRoleDepartement',$this->data,true);
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

    public function LoadSetUserActionDepartement()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/configuration/setuserrole/LoadSetUserActionDepartement',$this->data,true);
        echo json_encode($arr_result);
    }

    public function get_cfg_set_roleuser($Departement)
    {
        $this->auth_ajax();
        $getData = $this->m_budgeting->get_cfg_set_roleuser($Departement);
        echo json_encode($getData);
    }

    public function save_cfg_set_roleuser()
    {
        $this->auth_ajax();
        $msg = '';
        $Input = $this->getInputToken();
        $Action = $Input['Action'];
        switch ($Action) {
            case "":
                if ($Input['ID_set_roleuser'] == "") {
                    // insert data
                    $dataSave = array(
                        'ID_m_userrole' => $Input['ID_m_userrole'],
                        'NIP' => $Input['NIP'],
                        'Departement' => $Input['Departement']
                    );
                    $this->db->insert('db_budgeting.cfg_set_roleuser', $dataSave);
                }
                else
                {
                    // find $Input['ID_set_roleuser'] == ""
                    $get = $this->m_master->caribasedprimary('db_budgeting.cfg_set_roleuser','ID',$Input['ID_set_roleuser']);
                    if (count($get) > 0) {
                        // update
                        $dataSave = array(
                            'NIP' => $Input['NIP'],
                        );
                        $this->db->where('ID', $Input['ID_set_roleuser']);
                        $this->db->update('db_budgeting.cfg_set_roleuser', $dataSave);
                    }
                    else
                    {
                        $msg = "The Data is not exist, Please check";
                    }
                }
                break;
            case "delete":
                $ID = $Input['ID_set_roleuser'];
                $this->m_master->delete_id_table_all_db($ID,'db_budgeting.cfg_set_roleuser');
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
        // print_r($this->session->userdata('IDDepartementPUBudget'));
        $arr = array('EntryBudget',
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

    public function EntryBudget()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
        $arr_bulan = $this->m_master->getShowIntervalBulan($get[0]['StartPeriod'],$get[0]['EndPeriod']);
        $Year = $get[0]['Year'];
        $Departement = $this->session->userdata('IDDepartementPUBudget');
        $get = $this->m_budgeting->getPostDepartementForDomApproval($Year,$Departement);
        $this->data['fin'] = 0;
        if ($Departement == 'NA.9') {
            $this->data['fin'] = 1;
        }
        $this->data['Year'] = $Year;
        $this->data['Departement'] = $Departement;
        $this->data['arr_PostBudget'] = $get['data'];
        $this->data['arr_bulan'] = $arr_bulan;
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

    public function getLoadApprovalBudget()
    {
        $Input = $this->getInputToken();
        $Departement = $Input['Departement'];
        $arr_result = array('html' => '','jsonPass' => '');
        $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
        $arr_bulan = $this->m_master->getShowIntervalBulan($get[0]['StartPeriod'],$get[0]['EndPeriod']);
        $Year = $get[0]['Year'];
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

    public function getCreatorBudget()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $Year = $Input['Year'];
        $Departement = $Input['Departement'];
        $arr_result = array('creator_budget_approval' => array(),'creator_budget' => array());
        $get = $this->m_budgeting->get_creator_budget_approval($Year,$Departement,'');
        if (count($get) > 0) {
            // get Creator Budget
            $get2 = $this->m_budgeting->get_creator_budget($Year,$Departement);
            $arr_result['creator_budget_approval'] = $get;
            $arr_result['creator_budget'] = $get2;
        }

        echo json_encode($arr_result);
    }

    public function saveCreatorbudget()
    {
        $this->auth_ajax();
        $msg = '';
        $Input = $this->getInputToken();
        $creator_budget = $Input['creator_budget'];
        // save to creator_budget
        switch ($Input['action']) {
            case 'add':
                for ($i=0; $i < count($creator_budget); $i++) { 
                    $CodePostBudget = $creator_budget[$i]->CodePostBudget;
                    $UnitCost = $creator_budget[$i]->UnitCost;
                    $Freq = $creator_budget[$i]->Freq;
                    $DetailMonth = $creator_budget[$i]->DetailMonth;
                    $DetailMonth = json_encode($DetailMonth);
                    $SubTotal = $creator_budget[$i]->SubTotal;

                    $dataSave = array(
                        'CodePostBudget' => $CodePostBudget,
                        'UnitCost' => $UnitCost,
                        'Freq' => $Freq,
                        'DetailMonth' => $DetailMonth,
                        'SubTotal' => $SubTotal,
                        'CreatedBy' => $this->session->userdata('NIP'),
                        'CreatedAt' => date('Y-m-d H:i:s'),
                    );
                    $this->db->insert('db_budgeting.creator_budget', $dataSave);

                }

                $creator_budget_approval = $Input['creator_budget_approval'];
                $dataSave = array(
                    'Departement' => $creator_budget_approval->Departement,
                    'Year' => $creator_budget_approval->Year,
                    'Note' => $creator_budget_approval->Note,
                );
                $this->db->insert('db_budgeting.creator_budget_approval', $dataSave);
                break;
            case 'edit':
                $ID = $Input['ID'];
                for ($i=0; $i < count($creator_budget); $i++) { 
                    $CodePostBudget = $creator_budget[$i]->CodePostBudget;
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
                    $this->db->where('CodePostBudget', $CodePostBudget);
                    $this->db->update('db_budgeting.creator_budget', $dataSave);

                }

                $creator_budget_approval = $Input['creator_budget_approval'];
                $dataSave = array(
                    'Note' => $creator_budget_approval->Note,
                );
                $this->db->where('ID', $ID);
                $this->db->update('db_budgeting.creator_budget_approval', $dataSave);

                break;
            case 'approval':
                $ID = $Input['ID'];
                $dataSave = array(
                    'Approval' =>1,
                    'ApprovalBy' => $this->session->userdata('NIP'),
                    'ApprovalAt' => date('Y-m-d'),
                );
                $this->db->where('ID', $ID);
                $this->db->update('db_budgeting.creator_budget_approval', $dataSave);

                // save to table budget_left
                $creator_budget_approval = $Input['creator_budget_approval'];
                $Year  = $creator_budget_approval->Year;
                $Departement = $creator_budget_approval->Departement;
                $get2 = $this->m_budgeting->get_creator_budget($Year,$Departement);
                for ($i=0; $i < count($get2); $i++) { 
                    $ID_creator_budget = $get2[$i]['ID'];
                    $DetailMonth = $get2[$i]['DetailMonth'];
                    $DetailMonth = json_decode($DetailMonth);
                    // print_r($DetailMonth);
                    for ($j=0; $j < count($DetailMonth); $j++) { 
                        $dataSave = array(
                            'ID_creator_budget' => $ID_creator_budget,
                            'YearsMonth' => $DetailMonth[$j]->month.'-01',
                            'Value' => $DetailMonth[$j]->value * $get2[$i]['UnitCost'],
                        );

                        $this->db->insert('db_budgeting.budget_left', $dataSave);
                    }

                    $tbl = 'db_budgeting.cfg_set_post';
                    $fieldCode = 'CodePostBudget';
                    $ValueCode = $get2[$i]['CodePostBudget'];
                    $this->m_budgeting->makeCanBeDelete($tbl,$fieldCode,$ValueCode);
                }

                break;    
            default:
                # code...
                break;
        }
        echo json_encode($msg);
    }   

    public function ListBudgetDepartement()
    {
        $this->auth_ajax();
        $this->authFin();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/budget/ListBudgetDepartement',$this->data,true);
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
        $Input = $this->getInputToken();
        $Year = $Input['Year'];
        $get = $this->m_budgeting->getListBudgetingDepartement($Year);
        echo json_encode($get);
    }

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
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/budget/BudgetRemaining',$this->data,true);
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
        $arr_result = array('data' =>'','arr_bulan' => '');
        $Input = $this->getInputToken();
        $Year = $Input['Year'];
        $Departement = $Input['Departement'];
        $getData = $this->m_budgeting->get_budget_remaining($Year,$Departement);
        $get = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Year',$Year);
        $arr_bulan = $this->m_master->getShowIntervalBulan($get[0]['StartPeriod'],$get[0]['EndPeriod']);
        $arr_result = array('data' =>$getData,'arr_bulan' => $arr_bulan);
        echo json_encode($arr_result);
    }

}
