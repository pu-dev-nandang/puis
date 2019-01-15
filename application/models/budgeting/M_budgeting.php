<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_budgeting extends CI_Model {


    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
    }


    public function getMenuGroupUser($NIP,$MenuDepartement)
    {
        $sql = 'SELECT b.ID as ID_menu,b.Icon,c.ID,b.Menu,c.SubMenu1,c.SubMenu2,x.`read`,x.`update`,x.`write`,x.`delete`,c.Slug,c.Controller 
                from db_employees.employees as a
                join db_budgeting.previleges_guser as d
                on a.NIP = d.NIP
                join db_budgeting.cfg_rule_g_user as x
                on d.G_user = x.cfg_group_user
                join db_budgeting.cfg_sub_menu as c
                on x.ID_cfg_sub_menu = c.ID
                join (select * from db_budgeting.cfg_menu where IDDepartement = ?
                UNION
                select * from db_budgeting.cfg_menu where IDDepartement = "0"
                ) as b
                where a.NIP = ? GROUP by b.id';
        $query=$this->db->query($sql, array($MenuDepartement,$NIP))->result_array();
        return $query;
    }

    public function getData_cfg_postrealisasi($Active = null)
    {
        $arr_result = array();
        $Active = ($Active == null) ? '' : ' where a.Active = "'.$Active.'"';
        $sql = 'select a.CodePostRealisasi,a.CodePost,b.PostName,a.RealisasiPostName,a.Departement from db_budgeting.cfg_postrealisasi as a join db_budgeting.cfg_post as b on a.CodePost = b.CodePost
                '.$Active.' order by a.CodePostRealisasi desc';
        $query=$this->db->query($sql, array())->result_array();
        for ($i=0; $i < count($query); $i++) { 
            $Departement = $query[$i]['Departement'];
            $exp = explode('.', $Departement);
            if ($exp[0] == 'NA') { // Non Academic
                $tget = $this->m_master->caribasedprimary('db_employees.division','ID',$exp[1]);
                $Departement = $tget[0]['Description'].' ('.$tget[0]['Division'].')';
            }
            elseif ($exp[0] == 'AC') {
                $tget = $this->m_master->caribasedprimary('db_academic.program_study','ID',$exp[1]);
                $Departement = $tget[0]['NameEng'];
            }

            $temp = array(
                        'CodePostRealisasi' => $query[$i]['CodePostRealisasi'],
                        'CodePost' => $query[$i]['CodePost'],
                        'PostName' => $query[$i]['PostName'],
                        'RealisasiPostName' => $query[$i]['RealisasiPostName'],
                        'Departement' => $Departement,
                    );
            $arr_result[] = $temp;
        }

        return $arr_result;
                
    }

    public function getTheCode($tbl,$fieldCode,$PrefixCode,$length,$Year = null)
    {   
        $Code = '';
        $strLenPrefix = strlen($PrefixCode) + 1; // + 1 untuk -
        if ($Year == null) {
            $sql = 'select * from '.$tbl.' where '.$fieldCode.' like "'.$PrefixCode.'%" order by '.$fieldCode.' desc limit 1';
            $query=$this->db->query($sql, array())->result_array();
            if (count($query) == 1) {
                $V = $query[0][$fieldCode];
                $V = explode('-', $V);
                $inc = $V[1];
                $inc = (int)$inc;
                $inc = $inc + 1;
                $lenINC = strlen($inc);
                $strINC = $inc;
                for ($i=0; $i < $length-$lenINC-$strLenPrefix; $i++) { 
                    $strINC = '0'.$strINC;
                }

                $Code = $PrefixCode.'-'.$strINC;
            }
            elseif(count($query) == 0)
            {
               $inc = 1;
               $lenINC = strlen($inc);
               $strINC = $inc;
               for ($i=0; $i < $length-$lenINC-$strLenPrefix; $i++) { 
                   $strINC = '0'.$strINC;
               }
               $Code = $PrefixCode.'-'.$strINC; 
            }
        }
        else
        {
            $Year = substr($Year, 2,2);
            $sql = 'select * from '.$tbl.' where '.$fieldCode.' like "'.$PrefixCode.$Year.'%" order by '.$fieldCode.' desc limit 1';
            $query=$this->db->query($sql, array())->result_array();
            if (count($query) == 1) {
                $V = $query[0][$fieldCode];
                $V = explode('-', $V);
                $inc = $V[1];
                $inc = (int)$inc;
                $inc = $inc + 1;
                $lenINC = strlen($inc);
                $strINC = $inc;
                for ($i=0; $i < $length-$lenINC-$strLenPrefix-(strlen($Year)); $i++) { 
                    $strINC = '0'.$strINC;
                }
                $Code = $PrefixCode.$Year.'-'.$strINC;
            }
            elseif(count($query) == 0)
            {
               $inc = 1;
               $lenINC = strlen($inc);
               $strINC = $inc;
               for ($i=0; $i < $length-$lenINC-$strLenPrefix-(strlen($Year)); $i++) { 
                   $strINC = '0'.$strINC;
               }
               $Code = $PrefixCode.$Year.'-'.$strINC; 
            }
        }

        return $Code;
    }

    public function getPostDepartement($Year,$Departement)
    {
        $arr_result = array();
        $DepartementCode = $Departement;
        $exp = explode('.', $Departement);
        if ($exp[0] == 'NA') { // Non Academic
            $tget = $this->m_master->caribasedprimary('db_employees.division','ID',$exp[1]);
            $Departement = $tget[0]['Description'].' ('.$tget[0]['Division'].')';
        }
        elseif ($exp[0] == 'AC') {
            $tget = $this->m_master->caribasedprimary('db_academic.program_study','ID',$exp[1]);
            $Departement = $tget[0]['NameEng'];
        }
        $sql = 'select a.CodePostBudget,a.CodeSubPost,a.Year,a.Budget,b.RealisasiPostName,c.PostName,c.CodePost
                from db_budgeting.cfg_set_post as a join db_budgeting.cfg_postrealisasi as b on a.CodeSubPost = b.CodePostRealisasi
                join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                where a.Year = ? and b.Departement = ?
                ';
        $query=$this->db->query($sql, array($Year,$DepartementCode))->result_array();
        for ($i=0; $i < count($query); $i++) { 
            $temp = array(
                    'CodePostBudget' => $query[$i]['CodePostBudget'],
                    'CodeSubPost' => $query[$i]['CodeSubPost'],
                    'Year' => $query[$i]['Year'],
                    'Budget' => $query[$i]['Budget'],
                    'RealisasiPostName' => $query[$i]['RealisasiPostName'],
                    'PostName' => $query[$i]['PostName'],
                    'CodePost' => $query[$i]['CodePost'],
                    'Departement' => $Departement
            );

            $arr_result[] = $temp;
        }

        return $arr_result;       
    }

    public function getPostDepartementForDom($Year,$Departement)
    {
        $arr_result = array();
        $get_Data = $this->m_master->caribasedprimary('db_budgeting.cfg_postrealisasi','Departement',$Departement);
        $sql = 'select a.CodePostBudget,b.CodePostRealisasi,a.Year,a.Budget,b.RealisasiPostName,c.PostName,c.CodePost
                from db_budgeting.cfg_postrealisasi as b left join (select * from db_budgeting.cfg_set_post where Year = ? and Active = 1) as a on a.CodeSubPost = b.CodePostRealisasi
                join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                where b.Departement = ? and b.Active = 1 order by a.CodePostBudget asc
                ';
        $query=$this->db->query($sql, array($Year,$Departement))->result_array();
        $arr_result = array('data' => $query,'OpPostRealisasi' => $get_Data);
        return $arr_result;

    }

    public function getPostDepartementForDomApproval($Year,$Departement)
    {
        $arr_result = array();
        $get_Data = $this->m_master->caribasedprimary('db_budgeting.cfg_postrealisasi','Departement',$Departement);
        $sql = 'select a.CodePostBudget,b.CodePostRealisasi,a.Year,a.Budget,b.RealisasiPostName,c.PostName,c.CodePost
                from db_budgeting.cfg_postrealisasi as b join (select * from db_budgeting.cfg_set_post where Year = ? and Active = 1) as a on a.CodeSubPost = b.CodePostRealisasi
                join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                where b.Departement = ? order by a.CodePostBudget asc
                ';
        $query=$this->db->query($sql, array($Year,$Departement))->result_array();
        $arr_result = array('data' => $query,'OpPostRealisasi' => $get_Data);
        return $arr_result;

    }

    public function makeCanBeDelete($tbl,$fieldCode,$ValueCode)
    {
        $dataSave = array(
            'Status' => 0,
        );
        $this->db->where($fieldCode, $ValueCode);
        $this->db->update($tbl, $dataSave);
    }

    public function getPostDepartementAutoComplete($PostDepartement)
    {
        $sql = 'select b.CodePostRealisasi,b.RealisasiPostName,c.PostName,c.CodePost,d.NameDepartement
                from db_budgeting.cfg_postrealisasi as b 
                join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                join (
                select * from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                ) aa
                ) as d on b.Departement = d.ID
                where (b.RealisasiPostName like "%'.$PostDepartement.'%" or d.NameDepartement like "%'.$PostDepartement.'%" 
                or c.PostName like "%'.$PostDepartement.'%" or b.CodePostRealisasi like "%'.$PostDepartement.'%")
                order by b.CodePostRealisasi asc 
                ';
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function get_cfg_set_roleuser($Departement)
    {
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser
                from db_budgeting.cfg_m_userrole as a left join (select * from db_budgeting.cfg_set_roleuser where Departement = ? and Active = 1) as c
                on a.ID = c.ID_m_userrole
                left join db_employees.employees as b on b.NIP = c.NIP 
                order by a.NameUserRole asc
                ';
        $query=$this->db->query($sql, array($Departement))->result_array();
        return $query;
    }

    public function get_creator_budget_approval($Year,$Departement,$Approval = 'and Approval = 1')
    {
        $sql = 'select * from db_budgeting.creator_budget_approval where Year = ? and Departement = ? '.$Approval;
        $query=$this->db->query($sql, array($Year,$Departement))->result_array();
        return $query;
    }

    public function get_creator_budget($Year,$Departement)
    {
        $sql = 'select * from db_budgeting.creator_budget as a join (
           select a.CodePostBudget,b.CodePostRealisasi,a.Year,a.Budget,b.RealisasiPostName,c.PostName,c.CodePost
           from db_budgeting.cfg_postrealisasi as b left join (select * from db_budgeting.cfg_set_post where Year = ? and Active = 1) as a on a.CodeSubPost = b.CodePostRealisasi
           join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
           where b.Departement = ?     
        ) as  b on a.CodePostBudget = b.CodePostBudget order by a.CodePostBudget asc';
        $query=$this->db->query($sql, array($Year,$Departement))->result_array();
        return $query;
    }

    public function role_user_budgeting() // for role user budgeting per post
    {
        $arr = array('ID_m_userrole' => '','NameUserRole' => '');
        $sql = 'select a.*,b.NameUserRole from db_budgeting.cfg_set_roleuser as a join db_budgeting.cfg_m_userrole as b on a.ID_m_userrole = b.ID
                where a.NIP = ? and a.Departement = ?
        ';
        $query=$this->db->query($sql, array($this->session->userdata('NIP'),$this->session->userdata('IDDepartementPUBudget')))->result_array();
        for ($i=0; $i < count($query); $i++) {
            $arr[] = array('ID_m_userrole' => $query[$i]['ID_m_userrole'] ,'NameUserRole' => $query[$i]['NameUserRole']);
        }

        return $arr;
        
    }

    public function getListBudgetingDepartement($Year)
    {
        $sql = 'select aa.*,b.Approval from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                ) aa left join (select * from db_budgeting.creator_budget_approval where Year = ?) as b on aa.ID = b.Departement
                ';
        $query=$this->db->query($sql, array($Year))->result_array(); 
        for ($i=0; $i < count($query); $i++) { 
            // cari grand total
            $GrandTotal = 0;
            if ($query[$i]['Approval'] == '1' || $query[$i]['Approval'] == '0') {
                $get = $this->get_creator_budget($Year,$query[$i]['ID']);
                for ($j=0; $j < count($get); $j++) { 
                   $GrandTotal = $GrandTotal + $get[$j]['SubTotal'];
                }
            }
            
            $query[$i] = $query[$i] + array('GrandTotal' => $GrandTotal);
        }
        return $query;       
    }

    public function getListBudgetingRemaining($Year)
    {
        $sql = 'select aa.*,b.Approval from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                ) aa left join (select * from db_budgeting.creator_budget_approval where Year = ?) as b on aa.ID = b.Departement
                ';
        $query=$this->db->query($sql, array($Year))->result_array(); 
        for ($i=0; $i < count($query); $i++) { 
            // cari grand total
            $GrandTotal = 0;
            if ($query[$i]['Approval'] == '1' || $query[$i]['Approval'] == '0') {
                $get = $this->get_budget_remaining($Year,$query[$i]['ID']);
                for ($j=0; $j < count($get); $j++) { 
                   $GrandTotal = $GrandTotal + $get[$j]['Value'];
                }
            }
            
            $query[$i] = $query[$i] + array('GrandTotal' => $GrandTotal);
        }
        return $query;  
    }

    public function get_budget_remaining($Year,$Departement)
    {
        $sql = 'select dd.ID,cc.CodePostBudget,cc.Year,cc.RealisasiPostName,cc.PostName,dd.ID_creator_budget,dd.Value from
            (
                   select * from db_budgeting.creator_budget as a join (
                   select a.CodePostBudget as CodePostBudget2,b.CodePostRealisasi,a.Year,a.Budget,b.RealisasiPostName,c.PostName,c.CodePost
                   from db_budgeting.cfg_postrealisasi as b left join (select * from db_budgeting.cfg_set_post where Year = ? and Active = 1) as a on a.CodeSubPost = b.CodePostRealisasi
                   join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                   where b.Departement = ?     
                ) as  b on a.CodePostBudget = b.CodePostBudget2 order by a.CodePostBudget asc
            ) cc join db_budgeting.budget_left as dd on cc.ID = dd.ID_creator_budget
            ';
        $query=$this->db->query($sql, array($Year,$Departement))->result_array();
        return $query;
    }

    public function Grouping_PostBudget($getData)
    {
        $arr_result = array();
        for ($i=0; $i < count($getData); $i++) { 
            $CodePostBudget1 = $getData[$i]['CodePostBudget'];
            $YearsMonth = $getData[$i]['YearsMonth'];
            $YearsMonth = explode("-", $YearsMonth);
        }
    }

    public function PostBudgetThisMonth_Department($Departement,$PostBudget,$Month)
    {
        $sql = 'select dd.ID,cc.CodePostBudget,cc.Year,cc.RealisasiPostName,cc.PostName,dd.ID_creator_budget,dd.YearsMonth,dd.Value,cc.CodePost from
                        (
                               select * from db_budgeting.creator_budget as a join (
                               select a.CodePostBudget as CodePostBudget2,b.CodePostRealisasi,a.Year,a.Budget,b.RealisasiPostName,c.PostName,c.CodePost
                               from db_budgeting.cfg_postrealisasi as b left join db_budgeting.cfg_set_post as a on a.CodeSubPost = b.CodePostRealisasi
                               join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                               where b.Departement = "'.$Departement.'"     
                            ) as  b on a.CodePostBudget = b.CodePostBudget2 order by a.CodePostBudget asc
                        ) cc join db_budgeting.budget_left as dd on cc.ID = dd.ID_creator_budget
            where dd.YearsMonth like "'.$Month.'%" and cc.CodePost ="'.$PostBudget.'"';
            $query=$this->db->query($sql, array())->result_array();
            return $query;
    }

    public function getPostBudgetDepartement($Departement,$Year)
    {
        $sql = 'select a.* from db_budgeting.cfg_post as a left join db_budgeting.cfg_postrealisasi as b on a.CodePost = b.CodePost 
            left join db_budgeting.cfg_set_post as c on b.CodePostRealisasi = c.CodeSubPost
            where b.Departement = ? and c.Year = ? group by a.CodePost';
        $query=$this->db->query($sql, array($Departement,$Year))->result_array();
        return $query;
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

    public function GetRuleApproval_PR_JsonStatus($Departement,$Amount)
    {
        $JsonStatus = array();
        $sql = 'select * from db_budgeting.cfg_set_userrole where MaxLimit >= '.$Amount.' and Approved = 1 and Status = 1 and Active = 1
                group by MaxLimit,ID_m_userrole order by MaxLimit,ID_m_userrole;
                ';
        $query=$this->db->query($sql, array())->result_array();
        // print_r($query);die();
        // get data to filtering MaxLimit
            $arr = array();
            for ($i=0; $i < count($query); $i++) {
                $MaxLimit = $query[$i]['MaxLimit'];
                $arr[]= $query[$i]['ID_m_userrole'];
                $bool = false;
                for ($j=$i+1; $j < count($query); $j++) {
                    $MaxLimit2 = $query[$j]['MaxLimit'];
                    if ($MaxLimit == $MaxLimit2) {
                        $arr[]= $query[$j]['ID_m_userrole']; 
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


    public function GetPR_CreateByPRCode($PRCode)
    {
        $sql = 'select a.ID,a.PRCode,a.Year,a.Departement,b.NameDepartement,a.CreatedBy,a.CreatedAt,
                                    if(a.Status = 0,"Draft",if(a.Status = 1,"Issued & Approval Process",if(a.Status =  2,"Approval Done","Reject") ))
                                    as StatusName,a.Status, a.JsonStatus ,a.PPN,a.PRPrint_Approve
                                    from db_budgeting.pr_create as a 
                join (
                select * from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                ) aa
                ) as b on a.Departement = b.ID
                join db_employees.employees as c on a.CreatedBy = c.NIP
                where a.PRCode = ?
                ';
        $query = $this->db->query($sql, array($PRCode))->result_array();
        return $query;
    }

    public function GetPR_DetailByPRCode($PRCode)
    {
        $sql = 'select a.ID,a.PRCode,a.ID_budget_left,b.ID_creator_budget,c.CodePostBudget,d.CodeSubPost,e.CodePost,
                e.RealisasiPostName,f.PostName,a.ID_m_catalog,g.Item,
                a.Qty,a.UnitCost,a.SubTotal,a.DateNeeded,a.BudgetStatus,a.UploadFile
                from db_budgeting.pr_detail as a
                join db_budgeting.budget_left as b on a.ID_budget_left = b.ID
                join db_budgeting.creator_budget as c on b.ID_creator_budget = c.ID
                join db_budgeting.cfg_set_post as d on c.CodePostBudget = d.CodePostBudget
                join db_budgeting.cfg_postrealisasi as e on d.CodeSubPost = e.CodePostRealisasi
                join db_budgeting.cfg_post as f on e.CodePost = f.CodePost
                join db_purchasing.m_catalog as g on a.ID_m_catalog = g.ID
                where a.PRCode = ?
               ';
        $query = $this->db->query($sql, array($PRCode))->result_array();
        return $query;       
    }  
}