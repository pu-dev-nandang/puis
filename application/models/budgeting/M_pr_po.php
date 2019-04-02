<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_pr_po extends CI_Model {


    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('budgeting/m_budgeting');
    }

    public function Get_PRCode($Year,$Departement)
    {
        /* method PR
           Code : PRNA.12-1901000001
           PR -> Fix
           NA.12 -> Code Deparment in Budgeting
           - -> Fix
           19 -> Last two Year
           01 -> Month
           000001 -> Increment, Max 999.999 in one month
        */
        $PRCode = '';   
        $Year = substr($Year, 2,2);
        $Month = date('m');
        $MaxLengthINC = 6;
        $PRSearch = 'PR'.$Departement.'-'.$Year.$Month;
        $sql = 'select * from db_budgeting.pr_create where PRCode like "'.$PRSearch.'%" order by PRCode desc limit 1';
        $query=$this->db->query($sql, array())->result_array();

        if (count($query) == 1) {
            // Inc last code
            $PRCode = $query[0]['PRCode'];
            $C = substr($PRCode, 12,strlen($PRCode));
            $C = (int) $C;
            $C = $C + 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }

            $PRCode = $strINC;
            $PRCode = $PRSearch.$PRCode;
        }
        else
        {
            $C = 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }
            $PRCode = $strINC;
            $PRCode = $PRSearch.$PRCode;
        }    

        return $PRCode;
    }

    public function Get_PRCode2($Departement)
    {
        /* method PR
           Code : 05/UAP-IT/PR/IX/2018
           05 : Increment (Max length = 2)
           UAP- : Fix
           IT : Division Abbreviation
           PR : Fix
           IX : Bulan dalam romawi
           2018 : Get Years Now
        */
        $PRCode = '';   
        $Year = date('Y');
        $Month = date('m');
        $Month = $this->m_master->romawiNumber($Month);
        $MaxLengthINC = 2;
        
        $sql = 'select * from db_budgeting.pr_create 
                where Departement = ? and SPLIT_STR(PRCode, "/", 5) = ?
                and SPLIT_STR(PRCode, "/", 4) = ?
                order by SPLIT_STR(PRCode, "/", 1) desc
                limit 1';
        $query=$this->db->query($sql, array($Departement,$Year,$Month))->result_array();
        if (count($query) == 1) {
            // Inc last code
            $PRCode = $query[0]['PRCode'];
            $explode = explode('/', $PRCode);
            $C = $explode[0];
            $C = (int) $C;
            $C = $C + 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }

            $explode[0] = $strINC;
            $PRCode = implode('/', $explode);
        }
        else
        {
            $C = 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }

            // get abbreviation department
                $ExpDepart = explode('.', $Departement);
                $abbreviation_Div = '';
                if ($ExpDepart[0] == 'NA') {
                    $G_Div = $this->m_master->caribasedprimary('db_employees.division','ID',$ExpDepart[1]);
                    $abbreviation_Div = $G_Div[0]['Abbreviation'];
                }
                elseif ($ExpDepart[0] == 'AC') {
                    $G_Div = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ExpDepart[1]);
                    $abbreviation_Div = $G_Div[0]['Code'];
                }
                else
                {
                    $G_Div = $this->m_master->caribasedprimary('db_academic.faculty','ID',$ExpDepart[1]);
                    $abbreviation_Div = $G_Div[0]['Abbr'];
                }

            $PRCode = $strINC.'/'.'UAP-'.$abbreviation_Div.'/'.'PR'.'/'.$Month.'/'.$Year;
        }    

        return $PRCode;        

    }

    public function GetRuleApproval_PR_JsonStatus($Departement,$Amount)
    {
        $JsonStatus = array();
        $sql = 'select * from db_budgeting.cfg_set_userrole where MaxLimit >= '.$Amount.' and Approved = 1 and Status = 1 and Active = 1
                group by MaxLimit,ID_m_userrole order by MaxLimit,ID_m_userrole;
                ';
        $query=$this->db->query($sql, array())->result_array();
        
        // get data to filtering MaxLimit
        // print_r($query);die();
            $arr = array();
            for ($i=0; $i < count($query); $i++) {
                $MaxLimit = $query[$i]['MaxLimit'];
                $arr[]= $query[$i]['ID_m_userrole'];
                $bool = false;
                for ($j=$i+1; $j < count($query); $j++) { 
                    $MaxLimit2 = $query[$j]['MaxLimit'];
                    if ($MaxLimit == $MaxLimit2) {
                        $boolz = false;
                        for ($z=0; $z < count($arr); $z++) { 
                            if ($query[$j]['ID_m_userrole'] == $arr[$z]) {
                                $boolz = true;
                                break;
                            }
                        }

                        if (!$boolz) {
                            $arr[]= $query[$j]['ID_m_userrole'];
                        }

                        $i = $j;

                    }
                    else
                    {
                        $bool = true;
                        break;
                    }
                }

                if ($bool) {
                    break;
                }     

            }

        // find approver
            for ($i=0; $i < count($arr); $i++) { 
               $sql = 'select * from db_budgeting.cfg_set_roleuser where Departement = "'.$Departement.'" and ID_m_userrole = '.$arr[$i];
               $query=$this->db->query($sql, array())->result_array();
               $NIP = $query[0]['NIP'];

               $JsonStatus[] = array(
                    'ApprovedBy' => $NIP,
                    'Status' => 0,
                    'ApproveAt' => ''
                );
            } 

        return $JsonStatus;              
    }

    public function GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$PRCode)
    {
        $JsonStatus = array();
        // check apakah cross atau IN
            $checkCrossOrIN = $this->checkCrossOrIN($PRCode);
            if ($checkCrossOrIN['check'] == 'Cross') {
                $JsonStatus = $checkCrossOrIN['JsonStatus']; 
            }

        $sql = 'select * from db_budgeting.cfg_set_userrole where MaxLimit >= '.$Amount.' and Approved = 1 and Status = 1 and Active = 1
                group by MaxLimit,ID_m_userrole order by MaxLimit,ID_m_userrole;
                ';
        $query=$this->db->query($sql, array())->result_array();
        
        // get data to filtering MaxLimit
        // print_r($query);die();
            $arr = array();
            for ($i=0; $i < count($query); $i++) {
                $MaxLimit = $query[$i]['MaxLimit'];
                $arr[]= $query[$i]['ID_m_userrole'];
                $bool = false;
                for ($j=$i+1; $j < count($query); $j++) { 
                    $MaxLimit2 = $query[$j]['MaxLimit'];
                    if ($MaxLimit == $MaxLimit2) {
                        $boolz = false;
                        for ($z=0; $z < count($arr); $z++) { 
                            if ($query[$j]['ID_m_userrole'] == $arr[$z]) {
                                $boolz = true;
                                break;
                            }
                        }

                        if (!$boolz) {
                            $arr[]= $query[$j]['ID_m_userrole'];
                        }

                        $i = $j;

                    }
                    else
                    {
                        $bool = true;
                        break;
                    }
                }

                if ($bool) {
                    break;
                }     

            }

        // find approver
            for ($i=0; $i < count($arr); $i++) { 
               $sql = 'select * from db_budgeting.cfg_set_roleuser where Departement = "'.$Departement.'" and ID_m_userrole = '.$arr[$i];
               $query=$this->db->query($sql, array())->result_array();
               $NIP = $query[0]['NIP'];

               $JsonStatus[] = array(
                    'ApprovedBy' => $NIP,
                    'Status' => 0,
                    'ApproveAt' => '',
                    'Representedby' => '',
                );
            } 

        // print_r($JsonStatus);die();
        return $JsonStatus;              
    }

    public function checkCrossOrIN($PRCode)
    {
        $rs = array('JsonStatus'=>array(),'check' => 'IN');
        $G_data = $this->GetPR_DetailByPRCode($PRCode);
        $check = 'IN';
        $JsonStatus = array();
        for ($i=0; $i < count($G_data); $i++) { 
            $BudgetStatus = $G_data[$i]['BudgetStatus'];
            if ($BudgetStatus == 'Cross' && $check == 'IN') {
                $check = 'Cross';
            }

            if ($BudgetStatus == 'Cross') {
                // get approver 1 dari department tersebut
                    $Departement = $G_data[$i]['Departement'];
                    $sql = 'select * from db_budgeting.cfg_set_roleuser where Departement = "'.$Departement.'" and ID_m_userrole = 2';
                    $query=$this->db->query($sql, array())->result_array();
                    $NIP = $query[0]['NIP'];
                    // check existing in array JsonStatus
                        $bool = true;
                        for ($j=0; $j < count($JsonStatus); $j++) { 
                            if ($NIP == $JsonStatus[$j]['ApprovedBy']) {
                               $bool = false;
                               break;
                            }
                        }

                        if ($bool) {
                            $JsonStatus[] = array(
                                 'ApprovedBy' => $NIP,
                                 'Status' => 0,
                                 'ApproveAt' => '',
                                 'Representedby' => '',
                             );
                        }
            }
        }

        $rs = array('JsonStatus'=>$JsonStatus,'check' => $check);
        return $rs;
    }


    public function GetPR_CreateByPRCode($PRCode)
    {
        $sql = 'select a.ID,a.PRCode,a.Year,a.Departement,b.NameDepartement,a.CreatedBy,a.CreatedAt,
                                    if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done","Reject") ))
                                    as StatusName,a.Status, a.JsonStatus ,a.PPN,a.PRPrint_Approve,a.Notes,a.Supporting_documents,a.PostingDate
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
                join db_employees.employees as c on a.CreatedBy = c.NIP
                where a.PRCode = ?
                ';
        $query = $this->db->query($sql, array($PRCode))->result_array();
        // show Name Json Status
            for ($i=0; $i < count($query); $i++) { 
                $JsonStatus = $query[$i]['JsonStatus'];
                $JsonStatusDecode = (array)json_decode($JsonStatus,true);
                for ($j=0; $j < count($JsonStatusDecode); $j++) { 
                    $ApprovedBy = $JsonStatusDecode[$j]['ApprovedBy'];
                    $NameAprrovedBy = $this->m_master->caribasedprimary('db_employees.employees','NIP',$ApprovedBy);
                    $NameAprrovedBy = $NameAprrovedBy[0]['Name'];
                    $JsonStatusDecode[$j]['NameAprrovedBy'] = $NameAprrovedBy;
                }

                $JsonStatus = json_encode($JsonStatusDecode);
                $query[$i]['JsonStatus'] = $JsonStatus;
            }
        return $query;
    }

    public function GetPR_DetailByPRCode($PRCode)
    {
        $sql = 'select a.ID,a.PRCode,a.ID_budget_left,b.ID_creator_budget,c.CodePostBudget,d.CodeSubPost,e.CodePost,
                e.RealisasiPostName,e.Departement,f.PostName,a.ID_m_catalog,g.Item,g.Desc,g.DetailCatalog,a.Spec_add,a.Need,
                a.Qty,a.UnitCost,a.SubTotal,a.DateNeeded,a.BudgetStatus,a.UploadFile,g.Photo,h.NameDepartement
                from db_budgeting.pr_detail as a
                join db_budgeting.budget_left as b on a.ID_budget_left = b.ID
                join db_budgeting.creator_budget as c on b.ID_creator_budget = c.ID
                join db_budgeting.cfg_set_post as d on c.CodePostBudget = d.CodePostBudget
                join db_budgeting.cfg_postrealisasi as e on d.CodeSubPost = e.CodePostRealisasi
                join db_budgeting.cfg_post as f on e.CodePost = f.CodePost
                join db_purchasing.m_catalog as g on a.ID_m_catalog = g.ID
                join (
                    select * from (
                                    select CONCAT("AC.",ID) as ID, NameEng as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                                    UNION
                                    select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                                    UNION
                                    select CONCAT("FT.",ID) as ID, NameEng as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                                    ) aa
                    ) as h on e.Departement = h.ID 
                where a.PRCode = ?
               ';
        $query = $this->db->query($sql, array($PRCode))->result_array();
        // get combine 
        for ($i=0; $i < count($query); $i++) { 
            $arr = array();
            $sql = 'select b.ID_budget_left as ID_budget_left_Combine,c.ID_creator_budget as ID_creator_budget_Combine,d.CodePostBudget as CodePostBudget_Combine,e.CodeSubPost as CodeSubPost_Combine,f.CodePost as CodePost_Combine,
                f.RealisasiPostName as RealisasiPostName_Combine,f.Departement as Departement_Combine,g.PostName as PostName_Combine,
                h.NameDepartement as NameDepartement_Combine,b.Cost as Cost_Combine,b.Estvalue as Estvalue_Combine from 
                db_budgeting.pr_detail_combined as b
                join db_budgeting.budget_left as c on b.ID_budget_left = c.ID
               join db_budgeting.creator_budget as d on c.ID_creator_budget = d.ID
               join db_budgeting.cfg_set_post as e on d.CodePostBudget = e.CodePostBudget
               join db_budgeting.cfg_postrealisasi as f on e.CodeSubPost = f.CodePostRealisasi
               join db_budgeting.cfg_post as g on f.CodePost = g.CodePost
               join (
                   select * from (
                                   select CONCAT("AC.",ID) as ID, NameEng as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                                   UNION
                                   select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                                   UNION
                                   select CONCAT("FT.",ID) as ID, NameEng as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                                   ) aa
                   ) as h on f.Departement = h.ID
                where b.ID_pr_detail = ?   
                ';
            $arr = $this->db->query($sql, array($query[$i]['ID']))->result_array();
            $query[$i]['Combine'] = $arr;   
        }
        return $query;       
    }

    public function GetRuleAccess($NIP,$Departement)
    {
        error_reporting(0);
        $arr_result = array('access' => array(),'rule' => array());
        $sql = 'select a.*,b.NameUserRole,c.Name as NameTypeDesc,d.NameDepartement from db_budgeting.cfg_approval_pr as a 
                join db_budgeting.cfg_m_userrole as b on a.ID_m_userrole = b.ID
                join db_budgeting.cfg_m_type_approval as c on a.TypeDesc = c.ID
                join (
                select * from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, NameEng as NameDepartement from db_academic.faculty where StBudgeting = 1
                ) aa
                ) as d on a.Departement = d.ID
                where a.NIP = "'.$NIP.'" and a.Departement = "'.$Departement.'" limit 1';
        $query = $this->db->query($sql, array())->result_array();
        $arr_result['rule'] = $this->m_master->caribasedprimary('db_budgeting.cfg_set_userrole','ID_m_userrole',$query[0]['ID_m_userrole']);
        $arr_result['access'] = $query;
        return $arr_result; 
    }

    public function Get_m_Approver()
    {
        $sql = 'select * from db_budgeting.cfg_m_userrole where ID != 1';
        $query = $this->db->query($sql, array())->result_array();
        return $query;   
    }

    public function SearchDepartementBudgeting($DepartementBudgeting)
    {
        $sql = 'select * from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, NameEng as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                ) aa
                where ID = ?
                ';
        $query=$this->db->query($sql, array($DepartementBudgeting))->result_array();
        return $query;
    }

    public function SearchDepartementBudgetingByName($DepartementBudgeting)
    {
        $sql = 'select * from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, NameEng as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                ) aa
                where NameDepartement = ?
                ';
        $query=$this->db->query($sql, array($DepartementBudgeting))->result_array();
        return $query;
    }

    public function GetPeriod()
    {
        $YearActivated = $this->m_master->caribasedprimary('db_budgeting.cfg_dateperiod','Activated',1);
        $st = $YearActivated[0]['StartPeriod'];
        $st = explode('-', $st);
        $StartMonth = (int) $st[1];
        $StartMonth = (strlen($StartMonth) == 1 ) ? '0'.$StartMonth : $StartMonth;
        
        $StartMonth   = DateTime::createFromFormat('!m', $StartMonth);
        $StartMonth = $StartMonth->format('F').' '.$st[0]; // March

        $end = $YearActivated[0]['EndPeriod'];
        $end = explode('-', $end);
        $EndMonth = (int) $end[1];
        $EndMonth   = DateTime::createFromFormat('!m', $EndMonth);
        $EndMonth = $EndMonth->format('F').' '.$end[0]; // March

        return array('StartMonth' => $StartMonth,'EndMonth' => $EndMonth);
    }

    public function pr_circulation_sheet($PRCode,$Desc,$By = '')
    {
        if ($By ==  '') {
            $By = $this->session->userdata('NIP');
        }
        $dataSave = array(
            'PRCode' => $PRCode,
            'Desc' => $Desc,
            'Date' => date('Y-m-d'),
            'By' => $By,
        );

        $this->db->insert('db_budgeting.pr_circulation_sheet',$dataSave);
    }

}