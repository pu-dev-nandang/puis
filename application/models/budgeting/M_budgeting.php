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

    public function getSubmenu1BaseMenu_grouping($ID_Menu,$db='db_budgeting')
    {
        $sql = 'SELECT a.ID,a.ID_Menu,a.SubMenu1,a.SubMenu2,a.Slug,a.Controller,b.read,b.write,b.update,b.delete 
        from '.$db.'.cfg_sub_menu as a join '.$db.'.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
        where a.ID_Menu = ? group by a.SubMenu1';
        $query=$this->db->query($sql, array($ID_Menu))->result_array();
        return $query;
    }

    public function getSubmenu2BaseSubmenu1_grouping($submenu1,$db='db_admission',$IDmenu = null)
    {
        if ($IDmenu != null) {
            $sql = 'SELECT a.ID,a.ID_Menu,a.SubMenu1,a.SubMenu2,a.Slug,a.Controller,b.read,b.write,b.update,b.delete 
            from '.$db.'.cfg_sub_menu as a  join '.$db.'.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
             where a.SubMenu1 = ? and a.ID_Menu = ?';
            $query=$this->db->query($sql, array($submenu1,$IDmenu))->result_array();
        }
        else
        {
            $sql = 'SELECT a.ID,a.ID_Menu,a.SubMenu1,a.SubMenu2,a.Slug,a.Controller,b.read,b.write,b.update,b.delete 
            from '.$db.'.cfg_sub_menu as a  join '.$db.'.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
             where a.SubMenu1 = ?';
            $query=$this->db->query($sql, array($submenu1))->result_array();
        }
        
        return $query;
    }

    public function getData_cfg_postrealisasi($Active = null)
    {
        $Active = ($Active == null) ? '' : ' where a.Active = "'.$Active.'"';
        $sql = 'select a.CodePostRealisasi,a.CodeHeadAccount,b.Name as NameHeadAccount,a.RealisasiPostName,b.Departement,c.CodePost,c.PostName from db_budgeting.cfg_postrealisasi as a join db_budgeting.cfg_head_account as b on a.CodeHeadAccount = b.CodeHeadAccount
            join db_budgeting.cfg_post as c on c.CodePost = b.CodePost
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

            $query[$i]['DepartementName'] = $Departement;
        }
        return $query;
    }

    public function get_cfg_head_account($Active = null)
    {
        $Active = ($Active == null) ? '' : ' where a.Active = "'.$Active.'"';
        $sql = 'select a.CodeHeadAccount,a.Name as NameHeadAccount,a.Departement,b.CodePost,b.PostName
                from db_budgeting.cfg_head_account as a join db_budgeting.cfg_post as b on a.CodePost = b.CodePost
                '.$Active.' order by a.CodeHeadAccount desc';
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

            $query[$i]['DepartementName'] = $Departement;
        }
        return $query;
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
        $get_Data = $this->m_master->caribasedprimary('db_budgeting.cfg_head_account','Departement',$Departement);
        $sql = 'select a.CodePostBudget,b.CodeHeadAccount,b.Name as NameHeadAccount,a.Year,a.Budget,c.PostName,c.CodePost
                from db_budgeting.cfg_head_account as b left join (select * from db_budgeting.cfg_set_post where Year = ? and Active = 1) as a on a.CodeHeadAccount = b.CodeHeadAccount
                join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                where b.Departement = ? and b.Active = 1 order by a.CodeHeadAccount asc
                ';
                // print_r($sql);die();
        $query=$this->db->query($sql, array($Year,$Departement))->result_array();
        $arr_result = array('data' => $query,'OpPostRealisasi' => $get_Data);
        return $arr_result;
    }

    public function getPostDepartementEx($Year,$Departement)
    {
        $sql = 'select a.CodePostBudget,b.CodeHeadAccount,a.Year,a.Budget,b.Name as NameHeadAccount,c.PostName,c.CodePost
                from db_budgeting.cfg_head_account as b left join (select * from db_budgeting.cfg_set_post where Year = ? and Active = 1) as a on a.CodeHeadAccount = b.CodeHeadAccount
                join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                where b.Departement = ? and b.Active = 1 order by c.CodePost asc
                ';
        $query=$this->db->query($sql, array($Year,$Departement))->result_array();
        return $query;

    }

    public function getPostDepartementForDomApproval($Year,$Departement)
    {
        $arr_result = array();
        $get_Data = $this->getPostDepartementEx($Year,$Departement);
        // copy remaining = Budget
        for ($i=0; $i < count($get_Data); $i++) { 
            $get_Data[$i]['Remaining'] = $get_Data[$i]['Budget'];
        }
        $sql = 'select a.CodePostRealisasi,a.CodeHeadAccount,b.Name as NameHeadAccount,a.RealisasiPostName,b.Departement,c.CodePost,c.PostName from db_budgeting.cfg_postrealisasi as a join db_budgeting.cfg_head_account as b on a.CodeHeadAccount = b.CodeHeadAccount
            join db_budgeting.cfg_post as c on c.CodePost = b.CodePost
                where a.Active = 1 and b.Departement = ? order by a.CodePostRealisasi desc
                ';
        $query=$this->db->query($sql, array($Departement))->result_array();
        $arr_result = array('data' => $query,'getPostDepartement' => $get_Data);
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

    public function get_cfg_set_roleuser_budgeting($Departement)
    {
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser,c.Visible,c.TypeDesc
                from db_budgeting.cfg_m_userrole as a left join (select * from db_budgeting.cfg_approval_budget where Departement = ? ) as c
                on a.ID = c.ID_m_userrole
                left join db_employees.employees as b on b.NIP = c.NIP 
                order by a.ID asc
                ';
        $query=$this->db->query($sql, array($Departement))->result_array();
        return $query;
    }

    public function get_approval_budgeting($Departement)
    {
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser,c.Visible,c.TypeDesc,d.Name as NameTypeDesc
                from db_budgeting.cfg_m_userrole as a join (select * from db_budgeting.cfg_approval_budget where Departement = ? ) as c
                on a.ID = c.ID_m_userrole
                left join db_employees.employees as b on b.NIP = c.NIP 
                join db_budgeting.cfg_m_type_approval as d on d.ID = c.TypeDesc
                where a.ID > 1
                order by c.ID asc
                ';
        $query=$this->db->query($sql, array($Departement))->result_array();
        return $query;
    }

    public function log_budget($ID_creator_budget_approval,$Desc,$By = '')
    {
        if ($By ==  '') {
            $By = $this->session->userdata('NIP');
        }
        $dataSave = array(
            'ID_creator_budget_approval' => $ID_creator_budget_approval,
            'Desc' => $Desc,
            'Date' => date('Y-m-d'),
            'By' => $By,
        );

        $this->db->insert('db_budgeting.log_budget',$dataSave);
    }

    public function get_creator_budget_approval($Year,$Departement,$Approval = 'and a.Status = 2')
    {
        $sql = 'select a.*,b.NameDepartement from db_budgeting.creator_budget_approval as a 
        join (
        select * from (
        select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
        UNION
        select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
        ) aa
        ) as b on a.Departement = b.ID
        where a.Year = ? and a.Departement = ? '.$Approval.' 
        ';
        $query=$this->db->query($sql, array($Year,$Departement))->result_array();
        return $query;
    }

    public function get_creator_budget($ID_creator_budget_approval)
    {
        $sql = 'select a.ID_creator_budget_approval,a.CodePostRealisasi,a.UnitCost,a.Freq,a.DetailMonth,
               a.SubTotal,a.CreatedBy,a.CreatedAt,a.LastUpdateBy,a.LastUpdateAt,b.CodeHeadAccount,
                     b.RealisasiPostName,c.Name as NameHeadAccount,c.CodePost,d.PostName
               from db_budgeting.creator_budget as a left join db_budgeting.cfg_postrealisasi as b on a.CodePostRealisasi = b.CodePostRealisasi
               LEFT JOIN db_budgeting.cfg_head_account as c on b.CodeHeadAccount = c.CodeHeadAccount
               LEFT JOIN db_budgeting.cfg_post as d on c.CodePost = d.CodePost
               where a.ID_creator_budget_approval = ?
       ';
        $query=$this->db->query($sql, array($ID_creator_budget_approval))->result_array();
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
        $sql = 'select dd.ID,cc.CodePostBudget,cc.Year,cc.RealisasiPostName,cc.PostName,dd.ID_creator_budget,dd.Value
         ,cc.Departement
         from
            (
                   select * from db_budgeting.creator_budget as a join (
                   select a.CodePostBudget as CodePostBudget2,b.CodePostRealisasi,a.Year,a.Budget,b.RealisasiPostName,c.PostName,c.CodePost,
                   b.Departement
                   from db_budgeting.cfg_postrealisasi as b left join (select * from db_budgeting.cfg_set_post where Year = ? and Active = 1) as a on a.CodeSubPost = b.CodePostRealisasi
                   join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                   where b.Departement = ?     
                ) as  b on a.CodePostBudget = b.CodePostBudget2 order by a.CodePostBudget asc
            ) cc join db_budgeting.budget_left as dd on cc.ID = dd.ID_creator_budget
            ';
        $query=$this->db->query($sql, array($Year,$Departement))->result_array();
        // pass Name Department
        for ($i=0; $i < count($query); $i++) { 
            $NameDepartement = $this->SearchDepartementBudgeting($query[$i]['Departement']);
            $NameDepartement = $NameDepartement[0]['NameDepartement'];
            $query[$i]['NameDepartement'] = $NameDepartement;
        }

        return $query;
    }

    public function get_budget_remaining_all($Year)
    {
        $sql = 'select dd.ID,cc.CodePostBudget,cc.Year,cc.RealisasiPostName,cc.PostName,dd.ID_creator_budget,dd.Value
         ,cc.Departement
         from
            (
                   select * from db_budgeting.creator_budget as a join (
                   select a.CodePostBudget as CodePostBudget2,b.CodePostRealisasi,a.Year,a.Budget,b.RealisasiPostName,c.PostName,c.CodePost,
                   b.Departement
                   from db_budgeting.cfg_postrealisasi as b left join (select * from db_budgeting.cfg_set_post where Year = ? and Active = 1) as a on a.CodeSubPost = b.CodePostRealisasi
                   join db_budgeting.cfg_post as c on b.CodePost = c.CodePost
                ) as  b on a.CodePostBudget = b.CodePostBudget2 order by a.CodePostBudget asc
            ) cc join db_budgeting.budget_left as dd on cc.ID = dd.ID_creator_budget
            ';
        $query=$this->db->query($sql, array($Year))->result_array();
        // pass Name Department
        for ($i=0; $i < count($query); $i++) { 
            $NameDepartement = $this->SearchDepartementBudgeting($query[$i]['Departement']);
            $NameDepartement = $NameDepartement[0]['NameDepartement'];
            $query[$i]['NameDepartement'] = $NameDepartement;
        }

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
                else
                {
                    $G_Div = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ExpDepart[1]);
                    $abbreviation_Div = $G_Div[0]['Code'];
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
        $sql = 'select * from db_budgeting.cfg_set_roleuser where NIP = "'.$NIP.'" and Departement = "'.$Departement.'" limit 1';
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

    public function Budget_department_auth($Departement)
    {
        $rs = array();
        $NIP =$this->session->userdata('NIP');
        // get all department
        $arr = $this->m_master->apiservertoserver(url_pas.'api/__getAllDepartementPU','');
        // get auth
        $F = $this->m_master->caribasedprimary('db_budgeting.cfg_approval_budget','NIP',$NIP);
        // filtering
        if ($Departement == 'NA.9') {
            // get setiap departement
            // for ($i=0; $i < count($arr); $i++) { 
            //     // find di cfg_approval_budget
            //     $bool = false;
            //     for ($j=0; $j < count($F); $j++) { 
            //         $NIPDB = $F[$j]['NIP'];
            //         if ($NIP == $NIPDB) {
            //             $bool = true;
            //             $arr[$i] = $arr[$i] + array(
            //                  'ID_m_userrole' => $F[$j]['ID_m_userrole'],
            //                  'Visible' => $F[$j]['Visible'],
            //                  'TypeDesc' => $F[$j]['TypeDesc'],
            //                  'NIP' => $F[$j]['NIP'],
            //              ); 
            //             break;
            //         }
            //     }

            //     if (!$bool) {
            //        $arr[$i] = $arr[$i] + array(
            //             'ID_m_userrole' => 0,
            //             'Visible' => 'No',
            //             'TypeDesc' => '',
            //             'NIP' => $NIP
            //         ); 
            //     }
            // }
            $rs = $arr;
        }
        else
        {
            for ($i=0; $i < count($F); $i++) { 
               $NIPDB = $F[$i]['NIP'];
               $D = $F[$i]['Departement'];
               if ($NIP == $NIPDB) {
                   for ($j=0; $j < count($arr); $j++) { 
                       $D2 = $arr[$j]['Code'];
                       if ($D == $D2) {
                           $rs[] = $arr[$j];
                       }
                   }
               }
            }

        }

        return $rs;
    }

    public function Add_department_IFCustom_approval($Year)
    {
        $arr = array();
        $NIP = $this->session->userdata('NIP');
        $sql = 'select a.*,b.NameDepartement from db_budgeting.creator_budget_approval as a
                join (
                select * from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                ) aa
                ) as b on a.Departement = b.ID
                where JsonStatus like "%'.$NIP.'%"  and Year = ? ';
        $query=$this->db->query($sql, array($Year))->result_array();
        if (count($query) > 0) {
            for ($i=0; $i < count($query); $i++) { 
               $JsonStatus = (array) json_decode($query[$i]['JsonStatus'],true);
               // find NIP
               $bool = false;
               for ($j=0; $j < count($JsonStatus); $j++) { 
                   $NIPDB = $JsonStatus[$j]['NIP'];
                   if ($NIP == $NIPDB) {
                       $bool = true;
                       break;
                   }
               }

               if ($bool) {
                  $arr[] = array(
                    'Code' => $query[$i]['Departement'],
                    'Name2' => $query[$i]['NameDepartement'],
                  ); 
               }

            }
        }

        return $arr;
    }  
}