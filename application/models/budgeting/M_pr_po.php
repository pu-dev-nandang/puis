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

    public function Get_DataBudgeting_by_ID_budget_left($ID_budget_left)
    {
        $sql = 'select dd.ID,dd.`Using`,cc.CodePostRealisasi,cc.Year,cc.RealisasiPostName,cc.PostName,dd.ID_creator_budget,dd.Value
         ,cc.Departement,cc.CodeHeadAccount,cc.NameHeadAccount,cc.CodePost
         from
            (
                   select a.ID,a.ID_creator_budget_approval,a.CodePostRealisasi,a.UnitCost,a.Freq,a.DetailMonth,
               a.SubTotal,a.CreatedBy,a.CreatedAt,a.LastUpdateBy,a.LastUpdateAt,b.UnitDiv,b.CodeHeadAccount,
                     b.RealisasiPostName,b.Desc,c.Name as NameHeadAccount,c.CodePost,d.PostName,dp.NameDepartement as NameUnitDiv,dp.Code as CodeDiv,
                             cba.Departement,cba.`Year`
               from db_budgeting.creator_budget as a left join db_budgeting.cfg_postrealisasi as b on a.CodePostRealisasi = b.CodePostRealisasi
               LEFT JOIN db_budgeting.cfg_head_account as c on b.CodeHeadAccount = c.CodeHeadAccount
               LEFT JOIN db_budgeting.cfg_post as d on c.CodePost = d.CodePost
               LEFT JOIN (
                select CONCAT("AC.",ID) as ID,  CONCAT("Study ",NameEng) as NameDepartement,Code as Code from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, CONCAT("Faculty ",NameEng) as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
               ) as dp on b.UnitDiv = dp.ID
                 left join db_budgeting.creator_budget_approval as cba on cba.ID = a.ID_creator_budget_approval
            ) cc join db_budgeting.budget_left as dd on cc.ID = dd.ID_creator_budget
                            where dd.ID = ?';

        $query=$this->db->query($sql, array($ID_budget_left))->result_array();
        return $query;                    
    }

    public function GetRuleApproval_PR_JsonStatus2($Departement,$Amount,$arr_dt)
    {
        $arr = array();
        $C_ = 0;
        $rs = array();
        for ($i=0; $i < count($arr_dt); $i++) {
            $data = $arr_dt[$i]; 
            $key = "UAP)(*";
            $data_arr = (array) $this->jwt->decode($data,$key);
            $ID_budget_left = $data_arr['ID_budget_left'];
            $G = $this->Get_DataBudgeting_by_ID_budget_left($ID_budget_left);
            $CodePost = $G[0]['CodePost'];
            // search in by CodePost and by Amount
                $G1 = $this->m_master->caribasedprimary('db_budgeting.cfg_set_userrole','CodePost',$CodePost);
                for ($j=0; $j < count($G1); $j++) { 
                    $CodePost_ = $G1[$j]['CodePost'];
                    $MaxLimit = $G1[$j]['MaxLimit'];
                    if ($CodePost == $CodePost_ && $MaxLimit >= $Amount) {
                       $sql = 'select count(*) as total from db_budgeting.cfg_set_userrole where MaxLimit = ? and CodePost = ? and Approved = 1'; 
                       $query = $this->db->query($sql, array($MaxLimit,$CodePost))->result_array();
                       if ($query[0]['total'] >= $C_) {
                           $C_ = $query[0]['total'];
                           $temp = array(
                               'CodePost' => $CodePost,
                               'MaxLimit' => $MaxLimit,
                               'Count' => $C_, 
                           );
                           $arr = $temp;
                       }
                       break;
                    }
                }
        }       
        
        $G = $this->get_approval_pr($Departement);
        $ID_m_userrole_limit = $arr['Count'] + 1;
        for ($i=0; $i < count($G); $i++) { 
            $ID_m_userrole = $G[$i]['ID'];
            // if ($ID_m_userrole > 1) { // Admin tidak di inputkan dalam approval
            //     if ($ID_m_userrole <= $ID_m_userrole_limit) {
            //         $rs[] = array(
            //             'NIP' => $G[$i]['NIP'],
            //             'Status' => 0,
            //             'ApproveAt' => '',
            //             'Representedby' => '',
            //             'Visible' => $G[$i]['Visible'],
            //             'NameTypeDesc' => $G[$i]['NameTypeDesc'],
            //         );
            //     }
            // }
            if ($ID_m_userrole <= $ID_m_userrole_limit) {
                $Status = ($ID_m_userrole == 1) ? 1 : 0;
                $ApproveAt = ($ID_m_userrole == 1) ? date('Y-m-d H:i:s') : '';
                $rs[] = array(
                    'NIP' => $G[$i]['NIP'],
                    'Status' => $Status,
                    'ApproveAt' => $ApproveAt,
                    'Representedby' => '',
                    'Visible' => $G[$i]['Visible'],
                    'NameTypeDesc' => $G[$i]['NameTypeDesc'],
                );
            }
            
        }
        return $rs;       
    }

    public function GetPR_CreateByPRCode($PRCode)
    {
        $sql = 'select a.ID,a.PRCode,a.Year,a.Departement,b.NameDepartement,a.CreatedBy,c.Name as NameCreatedBy,a.CreatedAt,
                                    if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done","Reject") ))
                                    as StatusName,a.Status, a.JsonStatus ,a.PRPrint_Approve,a.Notes,a.Supporting_documents,a.PostingDate
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
                    $ApprovedBy = $JsonStatusDecode[$j]['NIP'];
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
        $sql = 'select a.ID,a.PRCode,a.ID_budget_left,b.ID_creator_budget,c.CodePostRealisasi,e.CodeHeadAccount,f.CodePost,
                e.RealisasiPostName,d.Departement,f.PostName,a.ID_m_catalog,g.Item,g.Desc,g.DetailCatalog,a.Spec_add,a.Need,
                a.Qty,a.UnitCost,a.SubTotal,a.DateNeeded,a.UploadFile,a.PPH,g.Photo,h.NameDepartement,d.Name as NameHeadAccount,g.EstimaValue
                from db_budgeting.pr_detail as a
                join db_budgeting.budget_left as b on a.ID_budget_left = b.ID
                join db_budgeting.creator_budget as c on b.ID_creator_budget = c.ID
                join db_budgeting.cfg_postrealisasi as e on c.CodePostRealisasi = e.CodePostRealisasi
                                join db_budgeting.cfg_head_account as d on d.CodeHeadAccount = e.CodeHeadAccount
                join db_budgeting.cfg_post as f on d.CodePost = f.CodePost
                join db_purchasing.m_catalog as g on a.ID_m_catalog = g.ID
                join (
                    select * from (
                                    select CONCAT("AC.",ID) as ID, NameEng as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                                    UNION
                                    select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                                    UNION
                                    select CONCAT("FT.",ID) as ID, NameEng as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                                    ) aa
                    ) as h on d.Departement = h.ID 
                where a.PRCode = ?
               ';
        $query = $this->db->query($sql, array($PRCode))->result_array();
        // get combine 
        for ($i=0; $i < count($query); $i++) { 
            $arr = array();
            $sql = 'select b.ID_budget_left as ID_budget_left_Combine,c.ID_creator_budget as ID_creator_budget_Combine,d.CodePostRealisasi as CodePostBudget_Combine,e.CodeHeadAccount as CodeHeadAccount_Combine,e.CodePost as CodePost_Combine,
                f.RealisasiPostName as RealisasiPostName_Combine,e.Departement as Departement_Combine,g.PostName as PostName_Combine,
                h.NameDepartement as NameDepartement_Combine,b.Cost as Cost_Combine from 
                db_budgeting.pr_detail_combined as b
                join db_budgeting.budget_left as c on b.ID_budget_left = c.ID
               join db_budgeting.creator_budget as d on c.ID_creator_budget = d.ID
               join db_budgeting.cfg_postrealisasi as f on d.CodePostRealisasi = f.CodePostRealisasi
                             join db_budgeting.cfg_head_account as e on e.CodeHeadAccount = f.CodeHeadAccount
               join db_budgeting.cfg_post as g on e.CodePost = g.CodePost
               join (
                   select * from (
                                   select CONCAT("AC.",ID) as ID, NameEng as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                                   UNION
                                   select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                                   UNION
                                   select CONCAT("FT.",ID) as ID, NameEng as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                                   ) aa
                   ) as h on e.Departement = h.ID
                where b.ID_pr_detail = ?   
                ';
            $arr = $this->db->query($sql, array($query[$i]['ID']))->result_array();
            $query[$i]['Combine'] = $arr;   
        }
        return $query;       
    }

    public function GetPR_DetailByPRCode_UN_PO($PRCode)
    {
        $sql = 'select a.ID,a.PRCode,a.ID_budget_left,b.ID_creator_budget,c.CodePostRealisasi,e.CodeHeadAccount,f.CodePost,
                e.RealisasiPostName,d.Departement,f.PostName,a.ID_m_catalog,g.Item,g.Desc,g.DetailCatalog,a.Spec_add,a.Need,
                a.Qty,a.UnitCost,a.SubTotal,a.DateNeeded,a.UploadFile,a.PPH,g.Photo,h.NameDepartement,d.Name as NameHeadAccount,g.EstimaValue
                from db_budgeting.pr_detail as a
                join db_budgeting.budget_left as b on a.ID_budget_left = b.ID
                join db_budgeting.creator_budget as c on b.ID_creator_budget = c.ID
                join db_budgeting.cfg_postrealisasi as e on c.CodePostRealisasi = e.CodePostRealisasi
                                join db_budgeting.cfg_head_account as d on d.CodeHeadAccount = e.CodeHeadAccount
                join db_budgeting.cfg_post as f on d.CodePost = f.CodePost
                join db_purchasing.m_catalog as g on a.ID_m_catalog = g.ID
                join (
                    select * from (
                                    select CONCAT("AC.",ID) as ID, NameEng as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                                    UNION
                                    select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                                    UNION
                                    select CONCAT("FT.",ID) as ID, NameEng as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                                    ) aa
                    ) as h on d.Departement = h.ID 
                where a.PRCode = ? and a.ID not IN(select ID_pr_detail from db_purchasing.pre_po_detail)
               ';
        $query = $this->db->query($sql, array($PRCode))->result_array();
        // get combine 
        for ($i=0; $i < count($query); $i++) { 
            $arr = array();
            $sql = 'select b.ID_budget_left as ID_budget_left_Combine,c.ID_creator_budget as ID_creator_budget_Combine,d.CodePostRealisasi as CodePostBudget_Combine,e.CodeHeadAccount as CodeHeadAccount_Combine,e.CodePost as CodePost_Combine,
                f.RealisasiPostName as RealisasiPostName_Combine,e.Departement as Departement_Combine,g.PostName as PostName_Combine,
                h.NameDepartement as NameDepartement_Combine,b.Cost as Cost_Combine from 
                db_budgeting.pr_detail_combined as b
                join db_budgeting.budget_left as c on b.ID_budget_left = c.ID
               join db_budgeting.creator_budget as d on c.ID_creator_budget = d.ID
               join db_budgeting.cfg_postrealisasi as f on d.CodePostRealisasi = f.CodePostRealisasi
                             join db_budgeting.cfg_head_account as e on e.CodeHeadAccount = f.CodeHeadAccount
               join db_budgeting.cfg_post as g on e.CodePost = g.CodePost
               join (
                   select * from (
                                   select CONCAT("AC.",ID) as ID, NameEng as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                                   UNION
                                   select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                                   UNION
                                   select CONCAT("FT.",ID) as ID, NameEng as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                                   ) aa
                   ) as h on e.Departement = h.ID
                where b.ID_pr_detail = ?   
                ';
            $arr = $this->db->query($sql, array($query[$i]['ID']))->result_array();
            $query[$i]['Combine'] = $arr;   
        }
        return $query;       
    }

    public function GetRuleAccess($NIP,$Departement)
    {
        // error_reporting(0);
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
        if (count($query) > 0) {
            $arr_result['rule'] = $this->m_master->caribasedprimary('db_budgeting.cfg_set_userrole','ID_m_userrole',$query[0]['ID_m_userrole']);
            $arr_result['access'] = $query;
        }
        
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

    public function get_approval_pr($Departement)
    {
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser,c.Visible,c.TypeDesc,d.Name as NameTypeDesc
                from db_budgeting.cfg_m_userrole as a join (select * from db_budgeting.cfg_approval_pr where Departement = ? ) as c
                on a.ID = c.ID_m_userrole
                left join db_employees.employees as b on b.NIP = c.NIP 
                join db_budgeting.cfg_m_type_approval as d on d.ID = c.TypeDesc
                order by c.ID asc
                ';
        $query=$this->db->query($sql, array($Departement))->result_array();
        return $query;
    }

    public function checkBudgetClientToServer_edit($BudgetLeft_awal,$BudgetRemaining)
    {
        $bool = true;
        for ($i=0; $i < count($BudgetRemaining); $i++) { 
            $ID_budget_left = $BudgetRemaining[$i]['ID'];
            // search in db
                $G = $this->m_master->caribasedprimary('db_budgeting.budget_left','ID',$ID_budget_left);
                $Value = $G[0]['Value'];
                $Using = $G[0]['Using'];
            // search in BudgetLeft_awal
                for ($j=0; $j < count($BudgetLeft_awal); $j++) { 
                    $ID_budget_left_ = $BudgetLeft_awal[$j]['ID'];
                    if ($ID_budget_left == $ID_budget_left_) {
                        $Value_ = $BudgetLeft_awal[$j]['Value'];
                        $Using_ = $BudgetLeft_awal[$j]['Using'];
                       if ($Value != $Value_ || $Using != $Using_) {
                            $bool = false;
                        } 
                       break;
                    }
                }

            if (!$bool) {
               break;
            }
        }

        return $bool;
    }

    public function BackBudgetToBeforeCreate($PRCode,$Year,$Departement)
    {
        $G_data = $this->GetPR_DetailByPRCode($PRCode);
        $getData = $this->m_budgeting->get_budget_remaining($Year,$Departement);
        $temp = array();
        for ($i=0; $i < count($getData); $i++) { 
            $CodePostRealisasi = $getData[$i]['CodePostRealisasi'];
            $Using = $getData[$i]['Using'];
            $Value = $getData[$i]['Value'];
            for ($j=0; $j < count($G_data); $j++) { 
               $CodePostRealisasi_ = $G_data[$j]['CodePostRealisasi'];
               if ($CodePostRealisasi == $CodePostRealisasi_) {
                   $SubTotal = $G_data[$j]['SubTotal'];
                   $Cost1 = $SubTotal;
                   $arr_Combine =  $G_data[$j]['Combine'];
                   if (count($arr_Combine) > 0) {
                       for ($k=0; $k < count($arr_Combine); $k++) { 
                          $Cost_Combine = $arr_Combine[$k]['Cost_Combine'];
                          $Cost1 = $Cost1 = $Cost_Combine;
                          $CodePostBudget_Combine = $arr_Combine[$k]['CodePostBudget_Combine'];

                          for ($l=0; $l < count($getData); $l++) { 
                              $CodePostBudget_Combine_ = $getData[$l]['CodePostRealisasi'];
                              if ($CodePostBudget_Combine == $CodePostBudget_Combine_) {
                                  $Using2 = $getData[$l]['Using'];
                                  $Using2 = $Using2 - $Cost_Combine;
                                  $getData[$l]['Using'] = $Using2;
                                  $bool2 = false;
                                  for ($m=0; $m < count($temp); $m++) { 
                                      if ($temp[$m] == $getData[$l]['ID']) {
                                          $bool2 = true;
                                          break;
                                      }
                                  }

                                  if (!$bool2) {
                                      $temp[] = $getData[$l]['ID'];
                                  }

                                  break;
                              }
                          }
                       }
                   }

                   $Using = $Using - $Cost1;
                   $getData[$i]['Using'] = $Using;
                   $bool2 = false;
                   for ($m=0; $m < count($temp); $m++) { 
                       if ($temp[$m] == $getData[$l]['ID']) {
                           $bool2 = true;
                           break;
                       }
                   }

                   if (!$bool2) {
                       $temp[] = $getData[$i]['ID'];
                   }

                   break;
               }
            }
        }

        // update to database
            for ($i=0; $i < count($temp); $i++) { 
                $ID = $temp[$i];    
                for ($j=0; $j <count($getData); $j++) { 
                    $ID_ = $getData[$j]['ID'];
                    if ($ID == $ID_) {
                       $dataSave = array(
                        'Using' => $getData[$j]['Using'],
                       );
                       $this->db->where('ID',$ID);
                       $this->db->update('db_budgeting.budget_left', $dataSave);
                       break;
                    }
                }
            }

    }

    public function Update_budget_left_pr($BudgetLeft_awal,$BudgetRemaining,$dt_arr)
    {
        for ($i=0; $i < count($BudgetRemaining); $i++) { 
            $ID_budget_left = $BudgetRemaining[$i]['ID'];
            $data_arr = array(
                'Using' => $BudgetRemaining[$i]['Using'],
            );

           $this->db->where('ID',$ID_budget_left);
           $this->db->update('db_budgeting.budget_left', $data_arr);        
        }
    }

}