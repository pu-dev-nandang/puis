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

    public function Get_POCode()
    {
        /* method PO
           Code : 016/PO/YPAP/IX/2018
           05 : Increment (Max length = 3)
           PO : Fix
           YPAP : Fix
           IX : Bulan dalam romawi
           2018 : Get Years Now
        */
        $Code = '';   
        $Year = date('Y');
        $Month = date('m');
        $Month = $this->m_master->romawiNumber($Month);
        $MaxLengthINC = 3;
        
        $sql = 'select * from db_purchasing.po_create 
                where SPLIT_STR(Code, "/", 5) = ?
                and SPLIT_STR(Code, "/", 4) = ?
                and TypeCreate = 1
                order by SPLIT_STR(Code, "/", 1) desc
                limit 1';
        $query=$this->db->query($sql, array($Year,$Month))->result_array();
        if (count($query) == 1) {
            // Inc last code
            $Code = $query[0]['Code'];
            $explode = explode('/', $Code);
            $C = $explode[0];
            $C = (int) $C;
            $C = $C + 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }

            $explode[0] = $strINC;
            $Code = implode('/', $explode);
        }
        else
        {
            $C = 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }
            /* method PO
               Code : 016/PO/YPAP/IX/2018
               05 : Increment (Max length = 3)
               PO : Fix
               YPAP : Fix
               IX : Bulan dalam romawi
               2018 : Get Years Now
            */

            $Code = $strINC.'/'.'PO/'.'YPAP'.'/'.$Month.'/'.$Year;
        }    
        return $Code;        
    }


    public function Get_SPKCode()
    {
        /* method PO
           Code : 016/PO/YPAP/IX/2018
           016 : Increment (Max length = 3)
           PO : Fix
           YPAP : Fix
           IX : Bulan dalam romawi
           2018 : Get Years Now
        */

       /* method SPK
          Code : S001/SPK/YPAP/I/2019
          S001 : Increment (Max length = 4) dengan huruf S didepan
          SPK : Fix
          YPAP : Fix
          IX : Bulan dalam romawi
          2018 : Get Years Now
       */
        $Code = '';   
        $Year = date('Y');
        $Month = date('m');
        $Month = $this->m_master->romawiNumber($Month);
        $MaxLengthINC = 3; // digit number aja
        $HurufFixed = 'S';
        
        $sql = 'select * from db_purchasing.po_create 
                where SPLIT_STR(Code, "/", 5) = ?
                and SPLIT_STR(Code, "/", 4) = ?
                and TypeCreate = 2
                order by SPLIT_STR(Code, "/", 1) desc
                limit 1';
        $query=$this->db->query($sql, array($Year,$Month))->result_array();
        if (count($query) == 1) {
            // Inc last code
            $Code = $query[0]['Code'];
            $explode = explode('/', $Code);
            $C = $explode[0];
            $C = str_replace($HurufFixed, '', $C);
            $C = (int) $C;
            $C = $C + 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }

            $explode[0] = $HurufFixed.$strINC;
            $Code = implode('/', $explode);
        }
        else
        {
            $C = 1;
            $B = strlen($C);
            $strINC = $C;
            for ($i=0; $i < $MaxLengthINC - $B; $i++) { 
                $strINC = '0'.$strINC;
            }
            $strINC = $HurufFixed.$strINC;
            /* method PO
               Code : 016/PO/YPAP/IX/2018
               05 : Increment (Max length = 3)
               PO : Fix
               YPAP : Fix
               IX : Bulan dalam romawi
               2018 : Get Years Now
            */
            /* method SPK
               Code : S001/SPK/YPAP/I/2019
               S001 : Increment (Max length = 4) dengan huruf S didepan
               SPK : Fix
               YPAP : Fix
               IX : Bulan dalam romawi
               2018 : Get Years Now
            */   
            $Code = $strINC.'/'.'SPK/'.'YPAP'.'/'.$Month.'/'.$Year;
        }    
        return $Code;        
    }

    public function Get_DataBudgeting_by_ID_budget_left($ID_budget_left)
    {
        $sql = 'select dd.ID,dd.`Using`,cc.CodePostRealisasi,cc.Year,cc.RealisasiPostName,cc.PostName,dd.ID_creator_budget,dd.Value
         ,cc.Departement,cc.CodeHeadAccount,cc.NameHeadAccount,cc.CodePost,cc.CodeDiv as CodeDepartment
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
                // $G1 = $this->m_master->caribasedprimary('db_budgeting.cfg_set_userrole','CodePost',$CodePost);
                $G1 = $this->__Get_Limit_CodePost_OrderBy($CodePost);
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
        // print_r($arr);die();
        $G = $this->get_approval_pr($Departement);
        // print_r($G);die();
        $ID_m_userrole_limit = $arr['Count'] + 1; // dengan admin
        // print_r($ID_m_userrole_limit.' => ID_m_userrole_limit');
        $indeksArr = 0;
        for ($i=0; $i < count($G); $i++) { 
             // add $G[0]['NIP'] = Session NIP
            if ($i == 0) {
                $G[$i]['NIP'] = $this->session->userdata('NIP');
            }

            $ID_m_userrole = $G[$i]['ID'];
             // print_r($ID_m_userrole.'<br>');
             $Status = ($ID_m_userrole == 1) ? 1 : 0;
             $ApproveAt = ($ID_m_userrole == 1) ? date('Y-m-d H:i:s') : '';
             // check nip ada sebelumnya jika ada maka continue
             $boolNIP = true;
             if ($i > 0) {
                $j = $indeksArr - 1;
                if ($j > 0) {
                    // print_r($G[$i]['NIP'].'=='.$rs[$j]['NIP']);
                    if ($G[$i]['NIP'] == $rs[$j]['NIP']) {
                        $boolNIP = false;
                    }
                }
             }

             if ($boolNIP) {
                  // print_r($ID_m_userrole.'<br>--BoolNIP<br>');
                 // filter untuk uncheck pada RAD
                     $BoolRAD = true;
                     if ($ID_m_userrole > 1) {
                         $sql = 'select * from db_budgeting.cfg_set_userrole where MaxLimit = ? and CodePost = ? and Approved = 1 and ID_m_userrole = ?';
                         $query = $this->db->query($sql, array($arr['MaxLimit'],$arr['CodePost'],$ID_m_userrole))->result_array();
                         // print_r($query);
                         if (count($query) == 0) {
                             $BoolRAD = false;
                         }
                     }
                     
                     if ($BoolRAD) {
                         $rs[] = array(
                             'NIP' => $G[$i]['NIP'],
                             'Status' => $Status,
                             'ApproveAt' => $ApproveAt,
                             'Representedby' => '',
                             'Visible' => $G[$i]['Visible'],
                             'NameTypeDesc' => $G[$i]['NameTypeDesc'],
                         );
                         $indeksArr++;
                     }  
             }
        }
        // print_r($rs);die();
        return $rs;       
    }

    public function GetPR_CreateByPRCode($PRCode)
    {
        $sql = 'select a.ID_template,a.ID,a.PRCode,a.Year,a.Departement,b.NameDepartement,a.CreatedBy,c.Name as NameCreatedBy,a.CreatedAt,
                                    if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done", if(a.Status = 3,"Reject","Cancel") ) ))
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
                    $NameAprrovedBy = $this->m_master->SearchNameNIP_Employees_PU_Holding($ApprovedBy);
                    $NameAprrovedBy = $NameAprrovedBy[0]['Name'];
                    $JsonStatusDecode[$j]['NameAprrovedBy'] = $NameAprrovedBy;
                }

                $JsonStatus = json_encode($JsonStatusDecode);
                $query[$i]['JsonStatus'] = $JsonStatus;
            }
        return $query;
    }

    public function GetPR_CreateByPRCode_multiple_pr_code($arr_pr_code)
    {
        $imp = implode(',', $arr_pr_code);
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
                where a.PRCode in ('.$imp.')
                ';
        $query = $this->db->query($sql, array())->result_array();
        // show Name Json Status
            for ($i=0; $i < count($query); $i++) { 
                $JsonStatus = $query[$i]['JsonStatus'];
                $JsonStatusDecode = (array)json_decode($JsonStatus,true);
                for ($j=0; $j < count($JsonStatusDecode); $j++) { 
                    $ApprovedBy = $JsonStatusDecode[$j]['NIP'];
                    $NameAprrovedBy = $this->m_master->SearchNameNIP_Employees_PU_Holding($ApprovedBy);
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
                a.Qty,a.UnitCost,a.SubTotal,a.DateNeeded,a.UploadFile,a.PPH,g.Photo,h.NameDepartement,d.Name as NameHeadAccount,g.EstimaValue,
                i.Days
                from db_budgeting.pr_detail as a
                join db_budgeting.budget_left as b on a.ID_budget_left = b.ID
                join db_budgeting.creator_budget as c on b.ID_creator_budget = c.ID
                join db_budgeting.cfg_postrealisasi as e on c.CodePostRealisasi = e.CodePostRealisasi
                                join db_budgeting.cfg_head_account as d on d.CodeHeadAccount = e.CodeHeadAccount
                join db_budgeting.cfg_post as f on d.CodePost = f.CodePost
                join db_purchasing.m_catalog as g on a.ID_m_catalog = g.ID
                join db_purchasing.m_category_catalog as i on g.ID_category_catalog = i.ID
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

    public function GetPR_DetailByPRCode_UN_PO($PRCode,$POCode = '')
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
                where a.PRCode = ? and a.ID not IN(select ID_pr_detail from db_purchasing.pre_po_detail) and a.Status = 1
               ';

            // for edit in Open PO   
            if ($POCode != '') {
               $G_pr_po = $this->Get_data_po_by_Code($POCode);
               $po_detail = $G_pr_po['po_detail'];
               $bool = false;
               for ($i=0; $i < count($po_detail); $i++) {
                   if ($po_detail[$i]['PRCode'] == $PRCode) {
                       $bool = true;
                   } 
               }

               if ($bool) {
                   $sql = '
                           select a.ID,a.PRCode,a.ID_budget_left,b.ID_creator_budget,c.CodePostRealisasi,e.CodeHeadAccount,f.CodePost,
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
                           where a.PRCode = ? and (
                                a.ID IN(select b.ID_pr_detail from db_purchasing.pre_po_detail as b join db_purchasing.po_detail as a on a.ID_pre_po_detail = b.ID where a.Code = "'.$POCode.'") 
                                or a.ID NOT IN (select b.ID_pr_detail from db_purchasing.pre_po_detail as b join db_purchasing.po_detail as a on a.ID_pre_po_detail = b.ID)
                            ) and a.Status = 1
                          ';

                }
               
            }

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


    public function GetPR_DetailByPRCode_UN_PO_multiple_pr_code($arr_pr_code,$POCode = '')
    {
        $temp = implode(',', $arr_pr_code);
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
                where a.PRCode in ('.$temp.') and a.ID not IN(select ID_pr_detail from db_purchasing.pre_po_detail) and a.Status = 1
               ';

            // for edit in Open PO   
            if ($POCode != '') {
               $G_pr_po = $this->Get_data_po_by_Code($POCode);
               $po_detail = $G_pr_po['po_detail'];
               $bool = true;
               // for ($i=0; $i < count($po_detail); $i++) {
               //     if ($po_detail[$i]['PRCode'] == $PRCode) {
               //         $bool = true;
               //     } 
               // }

               if ($bool) {
                   $sql = '
                           select a.ID,a.PRCode,a.ID_budget_left,b.ID_creator_budget,c.CodePostRealisasi,e.CodeHeadAccount,f.CodePost,
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
                           where a.PRCode in ('.$temp.') and a.ID IN(select b.ID_pr_detail from db_purchasing.pre_po_detail as b join db_purchasing.po_detail as a on a.ID_pre_po_detail = b.ID where a.Code = "'.$POCode.'")
                            and a.Status = 1
                          ';

                }
               
            }

        $query = $this->db->query($sql, array())->result_array();
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

    public function Get_supplier_po_by_Code($Code)
    {
        $G_data = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$Code);
        $ID_pre_po = $G_data[0]['ID_pre_po'];
        $sql = 'select b.CreatedBy as CreatedBy_pre_po,b.CreatedAt as CreatedAt_pre_po,
                c.CodeSupplier as CodeSupplier1,c.FileOffer,c.Approve as ApproveSupplier,c.Desc,d.*,e.CategoryName
                from db_purchasing.pre_po as b 
                join db_purchasing.pre_po_supplier as c on c.ID_pre_po = b.ID
                join db_purchasing.m_supplier as d on c.CodeSupplier = d.CodeSupplier
                join db_purchasing.m_categorysupplier as e on d.CategorySupplier = e.ID
                where b.ID = ?
                ';
        $query = $this->db->query($sql, array($ID_pre_po))->result_array();
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
            // $arr_result['rule'] = $this->m_master->caribasedprimary('db_budgeting.cfg_set_userrole','ID_m_userrole',$query[0]['ID_m_userrole']);
            $arr_result['rule'] = $this->__Get_Limit_userRole_OrderBy($query[0]['ID_m_userrole']);
            $arr_result['access'] = $query;
        }
        
        return $arr_result; 
    }

    private function __Get_Limit_userRole_OrderBy($ID_m_userrole)
    {
        $sql = 'select * from db_budgeting.cfg_set_userrole where ID_m_userrole = ? order by MaxLimit asc';
        $query = $this->db->query($sql, array($ID_m_userrole))->result_array();
        return $query; 
    }

    private function __Get_Limit_CodePost_OrderBy($CodePost)
    {
        $sql = 'select * from db_budgeting.cfg_set_userrole where CodePost = ? order by MaxLimit asc';
        $query = $this->db->query($sql, array($CodePost))->result_array();
        return $query; 
    }

    public function Get_m_Approver()
    {
        $sql = 'select * from db_budgeting.cfg_m_userrole where ID != 1';
        $query = $this->db->query($sql, array())->result_array();
        return $query;   
    }

    public function Get_m_Approver_po()
    {
        $sql = 'select * from db_purchasing.cfg_m_userrole where ID != 1';
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

    public function po_circulation_sheet($Code,$Desc,$By = '')
    {
        if ($By ==  '') {
            $By = $this->session->userdata('NIP');
        }
        $dataSave = array(
            'Code' => $Code,
            'Desc' => $Desc,
            'Date' => date('Y-m-d'),
            'By' => $By,
        );

        $this->db->insert('db_purchasing.po_circulation_sheet',$dataSave);
    }

    public function get_approval_pr($Departement)
    {
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser,c.Visible,c.TypeDesc,d.Name as NameTypeDesc
                from db_budgeting.cfg_m_userrole as a join (select * from db_budgeting.cfg_approval_pr where Departement = ? ) as c
                on a.ID = c.ID_m_userrole
                left join (
                    select NIP,Name,EmailPU from db_employees.employees
                    UNION
                    select NIK as NIP,Name,Email as EmailPU from db_employees.holding
                ) b on b.NIP = c.NIP 
                join db_budgeting.cfg_m_type_approval as d on d.ID = c.TypeDesc
                order by c.ID_m_userrole asc
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
                       if ($temp[$m] == $getData[$i]['ID']) {
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

    private function __Get_Limit_PO_CodePost_OrderBy()
    {
        $sql = 'select * from db_purchasing.cfg_set_userrole order by MaxLimit asc';
        $query = $this->db->query($sql, array())->result_array();
        return $query; 
    }

    public function GetRuleApproval_PO_JsonStatus($Amount)
    {
        $Departement = 'NA.4'; // Purchasing

        $arr = array(
            'MaxLimit' => 0,
            'Count' => 0,
        );
        $C_ = 0;
        $rs = array();

        // search in by CodePost and by Amount
        // $G1 = $this->m_master->showData_array('db_purchasing.cfg_set_userrole');
        $G1 = $this->__Get_Limit_PO_CodePost_OrderBy();
        for ($j=0; $j < count($G1); $j++) { 
            $MaxLimit = $G1[$j]['MaxLimit'];
            if ($MaxLimit >= $Amount) {
               $sql = 'select count(*) as total from db_purchasing.cfg_set_userrole where MaxLimit = ? and Approved = 1'; 
               $query = $this->db->query($sql, array($MaxLimit))->result_array();
               if ($query[0]['total'] >= $C_) {
                   $C_ = $query[0]['total'];
                   $temp = array(
                       'MaxLimit' => $MaxLimit,
                       'Count' => $C_, 
                   );
                   $arr = $temp;
               }
               break;
            }
        }

        $G = $this->get_approval_po($Departement);
        $ID_m_userrole_limit = $arr['Count'] + 1;
        $indeksArr = 0;
        for ($i=0; $i < count($G); $i++) { 
            $ID_m_userrole = $G[$i]['ID'];
            if ($ID_m_userrole <= $ID_m_userrole_limit) {
                $Status = ($ID_m_userrole == 1) ? 1 : 0;
                $ApproveAt = ($ID_m_userrole == 1) ? date('Y-m-d H:i:s') : '';

                // check nip ada sebelumnya jika ada maka continue
                $boolNIP = true;
                if ($i > 0) {
                   $j = $indeksArr - 1;
                   if ($j > 0) {
                       // print_r($G[$i]['NIP'].'=='.$rs[$j]['NIP']);
                       if ($G[$i]['NIP'] == $rs[$j]['NIP']) {
                           $boolNIP = false;
                       }
                   }
                   
                }

                if ($boolNIP) {
                    // filter untuk uncheck pada RAD
                        $BoolRAD = true;
                        if ($ID_m_userrole > 1) {
                            $sql = 'select * from db_purchasing.cfg_set_userrole where MaxLimit = ? and Approved = 1 and ID_m_userrole = ?';
                            $query = $this->db->query($sql, array($arr['MaxLimit'],$ID_m_userrole))->result_array();
                            // print_r($query);
                            if (count($query) == 0) {
                                $BoolRAD = false;
                            }
                        }

                        if ($BoolRAD) {
                            if ($i == 0) {
                                $G[$i]['NIP'] = $this->session->userdata('NIP');
                            }
                            $rs[] = array(
                                'NIP' => $G[$i]['NIP'],
                                'Status' => $Status,
                                'ApproveAt' => $ApproveAt,
                                'Representedby' => '',
                                'Visible' => $G[$i]['Visible'],
                                'NameTypeDesc' => $G[$i]['NameTypeDesc'],
                            );
                            $indeksArr++;
                        }
                }
                
            }
            
        }
        return $rs;       
    }

    public function GetRuleApproval_SPK_JsonStatus()
    {
        $G = $this->m_master->showData_array('db_purchasing.cfg_approval_spk');
        for ($i=0; $i < count($G); $i++) { 
            $ID_m_userrole = $G[$i]['ID_m_userrole'];
            $DG = $this->m_master->caribasedprimary('db_purchasing.cfg_m_type_approval','ID',$G[$i]['TypeDesc']);
            $Status = ($ID_m_userrole == 1) ? 1 : 0;
            $ApproveAt = ($ID_m_userrole == 1) ? date('Y-m-d H:i:s') : '';
            if ($i == 0) {
                $G[$i]['NIP'] = $this->session->userdata('NIP');
            }
            $rs[] = array(
                'NIP' => $G[$i]['NIP'],
                'Status' => $Status,
                'ApproveAt' => $ApproveAt,
                'Representedby' => '',
                'Visible' => $G[$i]['Visible'],
                'NameTypeDesc' => $DG[0]['Name'],
            );
            
        }
        return $rs;       
    }

    public function get_approval_po($Departement)
    {
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser,c.Visible,c.TypeDesc,d.Name as NameTypeDesc
                from db_purchasing.cfg_m_userrole as a join (select * from db_purchasing.cfg_approval where Departement = ? ) as c
                on a.ID = c.ID_m_userrole
                left join db_employees.employees as b on b.NIP = c.NIP 
                join db_purchasing.cfg_m_type_approval as d on d.ID = c.TypeDesc
                order by c.ID_m_userrole asc
                ';
        $query=$this->db->query($sql, array($Departement))->result_array();
        return $query;
    }

    public function Get_data_po_by_Code($Code)
    {
        $arr = array();
        $sql = 'select a.ID_pre_po,if(a.TypeCreate = 1,"PO","SPK") as TypeCode,a.Code,a.ID_pre_po_supplier,b.CodeSupplier,b.FileOffer,
                c.NamaSupplier,c.PICName,c.NoTelp,c.NoHp,c.JabatanPIC,c.Alamat,a.JsonStatus,a.Status,a.Notes,a.Notes2,a.Supporting_documents,a.POPrint_Approve,a.JobSpk,
                a.PostingDate,a.CreatedBy,a.CreatedAt,a.ID_pay_type
                from db_purchasing.po_create as a 
                join db_purchasing.pre_po_supplier as b on a.ID_pre_po_supplier = b.ID
                join db_purchasing.m_supplier as c on b.CodeSupplier = c.CodeSupplier
                where a.Code = ?
                ';
       $query=$this->db->query($sql, array($Code))->result_array();
       for ($i=0; $i < count($query); $i++) { 
           $JsonStatus = json_decode($query[$i]['JsonStatus'],true);
           for ($j=0; $j < count($JsonStatus); $j++) { 
               $NIP = $JsonStatus[$j]['NIP'];
               $G_emp = $this->m_master->SearchNameNIP_Employees_PU_Holding($NIP);
               $Name = $G_emp[0]['Name'];
               $JsonStatus[$j]['Name'] = $Name;
           }
           $query[$i]['JsonStatus']= json_encode($JsonStatus);
           $CreatedAt = $TimeStart = date("Y-m-d", strtotime($query[$i]['CreatedAt']));
           $query[$i]['CreatedAt_Indo'] = $this->m_master->getIndoBulan($CreatedAt);
           // show textarea jobspk,notes & notes2 
           // $query[$i]['JobSpk'] = nl2br($query[$i]['JobSpk']);
           // $query[$i]['Notes'] = nl2br($query[$i]['Notes']);
           // $query[$i]['Notes2'] = nl2br($query[$i]['Notes2']);
       }
       $arr['po_create'] = $query;

       $sql = 'select a.ID as ID_po_detail,a.ID_pre_po_detail,a.UnitCost as UnitCost_PO,a.Discount as Discount_PO,a.PPN as PPN_PO,a.SubTotal as Subtotal,
                b.ID_pr_detail,c.Qty as QtyPR,c.Item,c.Desc,c.DateNeeded,c.Spec_add,c.UnitCost as UnitCost_PR,c.Subtotal as Subtotal_PR,
                c.ID_budget_left,c.ID_creator_budget,c.CodePostRealisasi,c.CodeHeadAccount,c.CodePost,c.RealisasiPostName,c.Departement as Departement_HA,c.PostName,c.NameHeadAccount,c.PPH as PPH_PR,c.PRCode,c.DetailCatalog,a.AnotherCost
                from db_purchasing.po_detail as a 
                join db_purchasing.pre_po_detail as b on a.ID_pre_po_detail = b.ID
                join (
                    select a.ID,a.PRCode,a.ID_budget_left,b.ID_creator_budget,c.CodePostRealisasi,e.CodeHeadAccount,f.CodePost,
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
                    ) c on c.ID = b.ID_pr_detail
                    where a.Code = ?
                ';
        $query=$this->db->query($sql, array($Code))->result_array();        
        $arr['po_detail'] = $query;

        // Data perbandingan supplier
        $data = array(
              'Code' => $Code,
              'auth' => 's3Cr3T-G4N', 
        ); 
        $url = url_pas.'rest2/__Get_supplier_po_by_Code';
        $token = $this->jwt->encode($data,"UAP)(*");
        $arr['pre_po_supplier'] = $this->m_master->apiservertoserver($url,$token); 
        
        return $arr;       

    }

    public function Get_data_po_by_ID_po_detail($ID_po_detail)
    {
        $sql = 'select a.ID as ID_po_detail,a.ID_pre_po_detail,a.UnitCost as UnitCost_PO,a.Discount as Discount_PO,a.PPN as PPN_PO,a.SubTotal as Subtotal,
                b.ID_pr_detail,c.Qty as QtyPR,c.Item,c.Desc,c.DateNeeded,c.Spec_add,c.UnitCost as UnitCost_PR,c.Subtotal as Subtotal_PR,
                c.ID_budget_left,c.ID_creator_budget,c.CodePostRealisasi,c.CodeHeadAccount,c.CodePost,c.RealisasiPostName,c.Departement as Departement_HA,c.PostName,c.NameHeadAccount,c.PPH as PPH_PR,c.PRCode,c.DetailCatalog,a.AnotherCost
                from db_purchasing.po_detail as a 
                join db_purchasing.pre_po_detail as b on a.ID_pre_po_detail = b.ID
                join (
                    select a.ID,a.PRCode,a.ID_budget_left,b.ID_creator_budget,c.CodePostRealisasi,e.CodeHeadAccount,f.CodePost,
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
                    ) c on c.ID = b.ID_pr_detail
                    where a.ID = ?';
            $query=$this->db->query($sql, array($ID_po_detail))->result_array();
            return $query;        
    }

    public function CheckPerubahanData_PO_Created($po_data)
    {
        $bool = true;
        $po_data = json_decode(json_encode($po_data),true);
        $po_create = $po_data['po_create'];
        $po_detail = $po_data['po_detail'];
        $ID_pre_po_supplier = $po_create[0]['ID_pre_po_supplier'];
        $Status = $po_create[0]['Status'];
        $Notes = $po_create[0]['Notes'];
        /*
            1.Compare dengan data po sekarang
        */

         $Code = $po_create[0]['Code'];
         $G_po_create = $this->m_master->caribasedprimary('db_purchasing.po_create','Code',$Code);
         if ($G_po_create[0]['Status'] != $Status || $G_po_create[0]['ID_pre_po_supplier'] != $ID_pre_po_supplier || $G_po_create[0]['Notes'] != $Notes ) {
               $bool = false;
         }

         /*
            2.Compare dengan data detail sekarang
         */
            if ($bool) {
                $G_po_detail = $this->m_master->caribasedprimary('db_purchasing.po_detail','Code',$Code);
                if (count($G_po_detail) != count($po_detail)) {
                   $bool = false;
                }
                else
                {
                    for ($i=0; $i < count($po_detail); $i++) { 
                        $ID_po_detail = $po_detail[$i]['ID_po_detail'];
                        $bool2 = false;
                        for ($j=0; $j < count($G_po_detail); $j++) { 
                            $ID_po_detail_ = $G_po_detail[$j]['ID'];
                            if ($ID_po_detail == $ID_po_detail_) {
                                $UnitCost_PO = $po_detail[$i]['UnitCost_PO'];
                                $Discount_PO = $po_detail[$i]['Discount_PO'];
                                $PPN_PO = $po_detail[$i]['PPN_PO'];
                                $AnotherCost = $po_detail[$i]['AnotherCost'];
                                $Subtotal = $po_detail[$i]['Subtotal'];
                                $ID_pre_po_detail = $po_detail[$i]['ID_pre_po_detail'];
                                if ($UnitCost_PO != $G_po_detail[$j]['UnitCost'] || $Discount_PO !=  $G_po_detail[$j]['Discount'] || $PPN_PO != $G_po_detail[$j]['PPN']  ||  $Subtotal != $G_po_detail[$j]['SubTotal'] || $ID_pre_po_detail != $G_po_detail[$j]['ID_pre_po_detail'] || $AnotherCost !=  $G_po_detail[$j]['AnotherCost']) {
                                   $bool2 = false;
                                }
                                else
                                {
                                    $bool2 = true;
                                }
                                break;
                            }
                        }

                        if (!$bool2) {
                            $bool = false;
                            break;
                        }
                    }
                }
                
            }

        return $bool;    
                  
    }

    public function CekPRTo_Item_IN_PO($arr_post_data_ID_po_detail,$PRCode)
    {
        $Bool = true; // True = sama , false = tidak sama
        $G_item = $this->m_master->caribasedprimary('db_budgeting.pr_detail','PRCode',$PRCode);
        $C = 0;
        for ($i=0; $i < count($arr_post_data_ID_po_detail); $i++) { 
           $sql = 'select a.ID as ID_po_detail,a.ID_pre_po_detail,b.ID_pr_detail,c.ID_budget_left,c.SubTotal,c.CombineStatus
                   from db_purchasing.po_detail as a 
                   join db_purchasing.pre_po_detail as b on a.ID_pre_po_detail = b.ID
                   join db_budgeting.pr_detail as c on b.ID_pr_detail = c.ID
                   where a.ID = ? and c.PRCode = ?
                  ';

             $query=$this->db->query($sql, array($arr_post_data_ID_po_detail[$i],$PRCode))->result_array();

            // Note : satu  ID_po_detail sama dengan satu item pr detail
            if (count($query) > 0) {
                $C++;
            }
        }

        if ($C == count($G_item)) {
            $Bool = true;
        }
        else
        {
            $Bool = false;
        }
        
        return $Bool;
    }

    public function ReturnAllBudgetFromPO($arr_post_data_ID_po_detail)
    {
       for ($i=0; $i < count($arr_post_data_ID_po_detail); $i++) { 
           $sql = 'select a.ID as ID_po_detail,a.ID_pre_po_detail,b.ID_pr_detail,c.ID_budget_left,c.SubTotal,c.CombineStatus
                   from db_purchasing.po_detail as a 
                   join db_purchasing.pre_po_detail as b on a.ID_pre_po_detail = b.ID
                   join db_budgeting.pr_detail as c on b.ID_pr_detail = c.ID
                   where a.ID = ? and c.Status = 1
                  ';

             $query=$this->db->query($sql, array($arr_post_data_ID_po_detail[$i]))->result_array();
             if (count($query) > 0) {
                 // get using id_budget_left
                 $G_ID_budget_left = $this->m_master->caribasedprimary('db_budgeting.budget_left','ID',$query[0]['ID_budget_left']);
                 $ID_budget_left = $query[0]['ID_budget_left'];
                 $UsingNow = $G_ID_budget_left[0]['Using'];

                 // Subtotal
                    $SubtotalNow = $query[0]['SubTotal'];
                    $__BudgetFirstUsing = $SubtotalNow;
                    // cek Combine or not
                        if ($query[0]['CombineStatus'] == 1) { // combine
                            $G_ID_budget_left_combine = $this->m_master->caribasedprimary('db_budgeting.pr_detail_combined','ID_pr_detail',$query[0]['ID_pr_detail']);
                            for ($j=0; $j < count($G_ID_budget_left_combine); $j++) { 
                                $__BudgetFirstUsing = $__BudgetFirstUsing - $G_ID_budget_left_combine[$j]['Cost'];
                                // return Budget Combine First
                                    $ID_budget_left_Combine = $G_ID_budget_left_combine[$j]['ID_budget_left'];
                                    $G_ID_budget_left_Combine = $this->m_master->caribasedprimary('db_budgeting.budget_left','ID',$ID_budget_left_Combine);
                                    $UsingCombine = $G_ID_budget_left_Combine[0]['Using'];
                                    // kurangkan dengan cost
                                    $UsingCombine = $UsingCombine - $G_ID_budget_left_combine[$j]['Cost'];
                                    $dataSave = array(
                                        'Using' => $UsingCombine
                                    );

                                    $this->db->where('ID',$ID_budget_left_Combine);
                                    $this->db->update('db_budgeting.budget_left',$dataSave);
                            }
                        }

                    $Using = $UsingNow - $__BudgetFirstUsing;    
                    $dataSave = array(
                        'Using' => $Using
                    );
                    $this->db->where('ID',$ID_budget_left);
                    $this->db->update('db_budgeting.budget_left',$dataSave); 


                    $dataSave = array(
                        'Status' => -1,
                    );
                    $this->db->where('ID',$query[0]['ID_pr_detail']);
                    $this->db->update('db_budgeting.pr_detail',$dataSave);
             }        
       }
    }

    public function ReturnAllBudgetFromID_pr_detail($arr_post_data_ID_pr_detail)
    {
       for ($i=0; $i < count($arr_post_data_ID_pr_detail); $i++) { 
           $query= $this->m_master->caribasedprimary('db_budgeting.pr_detail','ID',$arr_post_data_ID_pr_detail[$i]);
             if (count($query) > 0) {
                 // get using id_budget_left
                 $G_ID_budget_left = $this->m_master->caribasedprimary('db_budgeting.budget_left','ID',$query[0]['ID_budget_left']);
                 $ID_budget_left = $query[0]['ID_budget_left'];
                 $UsingNow = $G_ID_budget_left[0]['Using'];

                 // Subtotal
                    $SubtotalNow = $query[0]['SubTotal'];
                    $__BudgetFirstUsing = $SubtotalNow;
                    // cek Combine or not
                        if ($query[0]['CombineStatus'] == 1) { // combine
                            $G_ID_budget_left_combine = $this->m_master->caribasedprimary('db_budgeting.pr_detail_combined','ID_pr_detail',$query[0]['ID']);
                            for ($j=0; $j < count($G_ID_budget_left_combine); $j++) { 
                                $__BudgetFirstUsing = $__BudgetFirstUsing - $G_ID_budget_left_combine[$j]['Cost'];
                                // return Budget Combine First
                                    $ID_budget_left_Combine = $G_ID_budget_left_combine[$j]['ID_budget_left'];
                                    $G_ID_budget_left_Combine = $this->m_master->caribasedprimary('db_budgeting.budget_left','ID',$ID_budget_left_Combine);
                                    $UsingCombine = $G_ID_budget_left_Combine[0]['Using'];
                                    // kurangkan dengan cost
                                    $UsingCombine = $UsingCombine - $G_ID_budget_left_combine[$j]['Cost'];
                                    $dataSave = array(
                                        'Using' => $UsingCombine
                                    );

                                    $this->db->where('ID',$ID_budget_left_Combine);
                                    $this->db->update('db_budgeting.budget_left',$dataSave);
                            }
                        }

                    $Using = $UsingNow - $__BudgetFirstUsing;    
                    $dataSave = array(
                        'Using' => $Using
                    );
                    $this->db->where('ID',$ID_budget_left);
                    $this->db->update('db_budgeting.budget_left',$dataSave); 


                    $dataSave = array(
                        'Status' => -1,
                    );
                    $this->db->where('ID',$query[0]['ID']);
                    $this->db->update('db_budgeting.pr_detail',$dataSave);
             }        
       }
    }

    public function __Cancel_update_pr_status_pr_status_detail($PRCode,$arr_post_data_ID_po_detail)
    {
        $G_item = $this->m_master->caribasedprimary('db_budgeting.pr_detail','PRCode',$PRCode);
        // pr_status kurangi process Item_proc sebanyak yang ditemukan dan tambahkan ke Item Cancel
        // pr_status_detail update Status menjadi -1
        $C = 0;
        for ($i=0; $i < count($arr_post_data_ID_po_detail); $i++) { 
           $sql = 'select a.ID as ID_po_detail,a.ID_pre_po_detail,b.ID_pr_detail,c.ID_budget_left,c.SubTotal,c.CombineStatus
                   from db_purchasing.po_detail as a 
                   join db_purchasing.pre_po_detail as b on a.ID_pre_po_detail = b.ID
                   join db_budgeting.pr_detail as c on b.ID_pr_detail = c.ID
                   where a.ID = ? and c.PRCode = ?
                  ';

             $query=$this->db->query($sql, array($arr_post_data_ID_po_detail[$i],$PRCode))->result_array();

            // satu  ID_po_detail sama dengan satu item pr detail
            if (count($query) > 0) {
                $C++;

                // update pr_status_detail
                $ID_pr_detail = $query[0]['ID_pr_detail'];
                $dataSave = array(
                    'Status' => -1,
                );

                $this->db->where('ID_pr_detail',$ID_pr_detail);
                $this->db->update('db_purchasing.pr_status_detail',$dataSave);
            }
        }

        // update pr_status
        $G_pr_status = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
        $Item_proc = $G_pr_status[0]['Item_proc'] - $C;
        $Item_cancel = $G_pr_status[0]['Item_cancel'] + $C;
        $dataSave = array(
            'Item_proc' => $Item_proc,
            'Item_cancel' => $Item_cancel,
        );

        $this->db->where('PRCode',$PRCode);
        $this->db->update('db_purchasing.pr_status',$dataSave);

    }

    public function __cancel_item_by_id_pr_detail($ID_pr_detail,$PRCode)
    {
        $C = 1;
         // update pr_status_detail
                $dataSave = array(
                    'Status' => -1,
                );

                $this->db->where('ID_pr_detail',$ID_pr_detail);
                $this->db->update('db_purchasing.pr_status_detail',$dataSave);
        // update pr_status
                $G_pr_status = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
                $Item_proc = $G_pr_status[0]['Item_proc'] - $C;
                $Item_cancel = $G_pr_status[0]['Item_cancel'] + $C;
                $dataSave = array(
                    'Item_proc' => $Item_proc,
                    'Item_cancel' => $Item_cancel,
                );

                $this->db->where('PRCode',$PRCode);
                $this->db->update('db_purchasing.pr_status',$dataSave);
    }

    public function check_pr_item_In_po($PRCode)
    {   
        $Bool = true;
        // pr_status = Item Proses, Item Done & Item Cancel harus 0
        $G_data = $this->m_master->caribasedprimary('db_purchasing.pr_status','PRCode',$PRCode);
        // print_r($G_data);
        // die();
        for ($i=0; $i < count($G_data); $i++) { 
            if ($G_data[$i]['Item_proc'] != 0 || $G_data[$i]['Item_cancel'] != 0 || $G_data[$i]['Item_done'] != 0) {
                $Bool = false;
                break;
            }
        }

        return $Bool;
    }

    public function check_po_status_by_item_pr_detail($ID_pr_detail)
    {
        $sql = 'select a.ID as ID_po_detail, a.Code,a.ID_pre_po_detail,b.ID_pr_detail,c.ID_budget_left,c.SubTotal,c.CombineStatus,
                d.Status
                from db_purchasing.po_detail as a 
                join db_purchasing.pre_po_detail as b on a.ID_pre_po_detail = b.ID
                join db_budgeting.pr_detail as c on b.ID_pr_detail = c.ID
                join db_purchasing.po_create as d on a.Code = d.Code
                where b.ID_pr_detail = ?
               ';
         $query=$this->db->query($sql, array($ID_pr_detail))->result_array();
         if (count($query) > 0) {
             return $query[0]['Status'];
         }
         else
         {
            return '';
         }
         
    }


}