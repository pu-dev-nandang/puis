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
                join (select * from db_budgeting.cfg_menu where IDDepartement = "'.$MenuDepartement.'"
                UNION
                select * from db_budgeting.cfg_menu where IDDepartement = "0"
                ) as b on c.ID_menu = b.ID
                where a.NIP = "'.$NIP.'" GROUP by b.id order by b.Sort asc';
                // print_r($sql);die();
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function getSubmenu1BaseMenu_grouping($ID_Menu,$db='db_budgeting')
    {
        $sql = 'SELECT a.ID,a.ID_Menu,a.SubMenu1,a.SubMenu2,a.Slug,a.Controller,b.read,b.write,b.update,b.delete 
        from '.$db.'.cfg_sub_menu as a join '.$db.'.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
        where a.ID_Menu = ? group by a.SubMenu1 order by a.Sort1 asc';
        $query=$this->db->query($sql, array($ID_Menu))->result_array();
        return $query;
    }

    public function getSubmenu2BaseSubmenu1_grouping($submenu1,$db='db_admission',$IDmenu = null)
    {
        if ($IDmenu != null) {
            $sql = 'SELECT a.ID,a.ID_Menu,a.SubMenu1,a.SubMenu2,a.Slug,a.Controller,b.read,b.write,b.update,b.delete 
            from '.$db.'.cfg_sub_menu as a  join '.$db.'.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
             where a.SubMenu1 = ? and a.ID_Menu = ? order by a.Sort2 asc';
            $query=$this->db->query($sql, array($submenu1,$IDmenu))->result_array();
        }
        else
        {
            $sql = 'SELECT a.ID,a.ID_Menu,a.SubMenu1,a.SubMenu2,a.Slug,a.Controller,b.read,b.write,b.update,b.delete 
            from '.$db.'.cfg_sub_menu as a  join '.$db.'.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
             where a.SubMenu1 = ? order by a.Sort2 asc';
            $query=$this->db->query($sql, array($submenu1))->result_array();
        }
        
        return $query;
    }

    public function getData_cfg_postrealisasi($Active = null)
    {
        $Active = ($Active == null) ? '' : ' where a.Active = "'.$Active.'"';
        $sql = 'select a.CodePostRealisasi,a.CodeHeadAccount,b.Name as NameHeadAccount,a.RealisasiPostName,a.UnitDiv,a.Desc,b.Departement,c.CodePost,c.PostName from db_budgeting.cfg_postrealisasi as a join db_budgeting.cfg_head_account as b on a.CodeHeadAccount = b.CodeHeadAccount
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
                $Departement = 'Study '.$tget[0]['NameEng'];
            }
            elseif ($exp[0] == 'FT') {
                $tget = $this->m_master->caribasedprimary('db_academic.faculty','ID',$exp[1]);
                $Departement = 'Faculty '.$tget[0]['NameEng'];
            }

            $query[$i]['DepartementName'] = $Departement;

            // for Unit Div
            $UnitDiv = $query[$i]['UnitDiv'];
            $exp = explode('.', $UnitDiv);
            $Departement = '';
            if ($exp[0] == 'NA') { // Non Academic
                $tget = $this->m_master->caribasedprimary('db_employees.division','ID',$exp[1]);
                $Departement = $tget[0]['Description'].' ('.$tget[0]['Division'].')';
            }
            elseif ($exp[0] == 'AC') {
                $tget = $this->m_master->caribasedprimary('db_academic.program_study','ID',$exp[1]);
                $Departement = 'Study '.$tget[0]['NameEng'];
            }
            elseif ($exp[0] == 'FT') {
                $tget = $this->m_master->caribasedprimary('db_academic.faculty','ID',$exp[1]);
                $Departement = 'Faculty '.$tget[0]['NameEng'];
            }

            $query[$i]['UnitDivName'] = $Departement;

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
                $Departement = 'Study '.$tget[0]['NameEng'];
            }
            elseif ($exp[0] == 'FT') {
                $tget = $this->m_master->caribasedprimary('db_academic.faculty','ID',$exp[1]);
                $Departement = 'Faculty '.$tget[0]['NameEng'];
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

    public function getTheCodeByDiv($tbl,$fieldCode,$PrefixCode,$length,$Departement,$Year = null)
    {   
        $Code = '';
        // get abbr dari Departement
        $G = $this->SearchDepartementBudgeting($Departement);
        $Abbr =$G[0]['Code'];
        $strLenPrefix = strlen($PrefixCode) + 1; // + 1 untuk -
        if ($Year == null) {
            $sql = 'select * from '.$tbl.' where '.$fieldCode.' like "'.$PrefixCode.'-'.$Abbr.'%" order by '.$fieldCode.' desc limit 1';
            $query=$this->db->query($sql, array())->result_array();
            if (count($query) == 1) {
                $V = $query[0][$fieldCode];
                $V = explode('-', $V);
                $inc = $V[2];
                $inc = (int)$inc;
                $inc = $inc + 1;
                $lenINC = strlen($inc);
                $strINC = $inc;
                for ($i=0; $i < $length-$lenINC-$strLenPrefix; $i++) { 
                    $strINC = '0'.$strINC;
                }

                $Code = $PrefixCode.'-'.$Abbr.'-'.$strINC;
            }
            elseif(count($query) == 0)
            {
               $inc = 1;
               $lenINC = strlen($inc);
               $strINC = $inc;
               for ($i=0; $i < $length-$lenINC-$strLenPrefix; $i++) { 
                   $strINC = '0'.$strINC;
               }
               $Code = $PrefixCode.'-'.$Abbr.'-'.$strINC;
            }
        }
        else
        {
            $Year = substr($Year, 2,2);
            $sql = 'select * from '.$tbl.' where '.$fieldCode.' like "'.$PrefixCode.'-'.$Abbr.'-'.$Year.'%" order by '.$fieldCode.' desc limit 1';
            $query=$this->db->query($sql, array())->result_array();
            if (count($query) == 1) {
                $V = $query[0][$fieldCode];
                $V = explode('-', $V);
                $inc = $V[2];
                $inc = (int)$inc;
                $inc = $inc + 1;
                $lenINC = strlen($inc);
                $strINC = $inc;
                for ($i=0; $i < $length-$lenINC-$strLenPrefix-(strlen($Year)); $i++) { 
                    $strINC = '0'.$strINC;
                }
                $Code = $PrefixCode.'-'.$Abbr.'-'.$strINC;
            }
            elseif(count($query) == 0)
            {
               $inc = 1;
               $lenINC = strlen($inc);
               $strINC = $inc;
               for ($i=0; $i < $length-$lenINC-$strLenPrefix-(strlen($Year)); $i++) { 
                   $strINC = '0'.$strINC;
               }
               $Code = $PrefixCode.'-'.$Abbr.'-'.$strINC;
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
        elseif ($exp[0] == 'FT') {
            $tget = $this->m_master->caribasedprimary('db_academic.faculty','ID',$exp[1]);
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
                UNION
                select CONCAT("FT.",ID) as ID, NameEng as NameDepartement from db_academic.faculty where StBudgeting = 1
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

    public function get_cfg_set_roleuser_pr($Departement)
    {
        $sql = 'select a.*,b.Name as NamaUser,b.NIP,c.Departement,c.ID as ID_set_roleuser,c.Visible,c.TypeDesc
                from db_budgeting.cfg_m_userrole as a left join (select * from db_budgeting.cfg_approval_pr where Departement = ? ) as c
                on a.ID = c.ID_m_userrole
                left join 
                (
                     select NIP,Name,EmailPU from db_employees.employees
                     UNION
                     select NIK as NIP,Name,Email as EmailPU from db_employees.holding
                )  b on b.NIP = c.NIP 
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
                order by c.ID_m_userrole asc
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
        UNION
        select CONCAT("FT.",ID) as ID, NameEng as NameDepartement from db_academic.faculty where StBudgeting = 1
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
               a.SubTotal,a.CreatedBy,a.CreatedAt,a.LastUpdateBy,a.LastUpdateAt,b.UnitDiv,b.CodeHeadAccount,
                     b.RealisasiPostName,b.Desc,c.Name as NameHeadAccount,c.CodePost,d.PostName,dp.NameDepartement as NameUnitDiv,dp.Code as CodeDiv
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
               where a.ID_creator_budget_approval = ?
               order by c.CodeHeadAccount asc,a.ID asc
       ';
        $query=$this->db->query($sql, array($ID_creator_budget_approval))->result_array();
        return $query;
    }

    public function role_user_budgeting() // for role user budgeting per post
    {
        $arr = array('ID_m_userrole' => '','NameUserRole' => '');
        $sql = 'select a.*,b.NameUserRole from db_budgeting.cfg_approval_budget as a join db_budgeting.cfg_m_userrole as b on a.ID_m_userrole = b.ID
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
        $sql = 'select aa.*,b.Status,b.ID as ID_creator_budget from (
                select CONCAT("AC.",ID) as ID, CONCAT("Study ",NameEng) as NameDepartement from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, CONCAT("Faculty ",NameEng) as NameDepartement from db_academic.faculty where StBudgeting = 1
                ) aa left join (select * from db_budgeting.creator_budget_approval where Year = ?) as b on aa.ID = b.Departement
                ';
        $query=$this->db->query($sql, array($Year))->result_array(); 
        for ($i=0; $i < count($query); $i++) { 
            // cari grand total
            $ID_creator_budget = $query[$i]['ID_creator_budget'];
            $GrandTotal = 0;
            if ($query[$i]['Status'] == '2') {
                $get = $this->get_creator_budget($ID_creator_budget);
                for ($j=0; $j < count($get); $j++) { 
                   $GrandTotal = $GrandTotal + $get[$j]['SubTotal'];
                }
            }
            
            $query[$i] = $query[$i] + array('GrandTotal' => $GrandTotal);
        }
        return $query;       
    }

    public function get_data_ListBudgetingDepartement($Year)
    {
        $sql = 'select aa.*,b.ID as ID_creator_budget,b.* from (
                select CONCAT("AC.",ID) as ID,  CONCAT("Study ",NameEng) as NameDepartement,Code as Code from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, CONCAT("Faculty ",NameEng) as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                ) aa left join (select * from db_budgeting.creator_budget_approval where Year = ?) as b on aa.ID = b.Departement
                ';
        $query=$this->db->query($sql, array($Year))->result_array(); 
        return $query;       
    }

    // public function getListBudgetingRemaining($Year)
    // {
    //     $sql = 'select aa.*,b.Approval from (
    //             select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study where Status = 1
    //             UNION
    //             select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
    //             UNION
    //             select CONCAT("FT.",ID) as ID, NameEng as NameDepartement from db_academic.faculty where StBudgeting = 1
    //             ) aa left join (select * from db_budgeting.creator_budget_approval where Year = ?) as b on aa.ID = b.Departement
    //             ';
    //     $query=$this->db->query($sql, array($Year))->result_array(); 
    //     for ($i=0; $i < count($query); $i++) { 
    //         // cari grand total
    //         $GrandTotal = 0;
    //         if ($query[$i]['Approval'] == '1' || $query[$i]['Approval'] == '0') {
    //             $get = $this->get_budget_remaining($Year,$query[$i]['ID']);
    //             for ($j=0; $j < count($get); $j++) { 
    //                $GrandTotal = $GrandTotal + $get[$j]['Value'];
    //             }
    //         }
            
    //         $query[$i] = $query[$i] + array('GrandTotal' => $GrandTotal);
    //     }
    //     return $query;  
    // }

    public function get_budget_remaining($Year,$Departement)
    {
        $sql = 'select dd.ID,dd.`Using`,cc.CodePostRealisasi,cc.Year,cc.RealisasiPostName,cc.PostName,dd.ID_creator_budget,dd.Value
         ,cc.Departement,cc.CodeHeadAccount,cc.NameHeadAccount,cc.CodePost,cc.SubTotal as PriceBudgetAwal
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
                             where cba.`Year` = ? and cba.Departement = ?
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

    public function SearchDepartementBudgeting($DepartementBudgeting)
    {
        $sql = 'select * from (
                select CONCAT("AC.",ID) as ID, CONCAT("Study ",NameEng) as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, CONCAT("Faculty ",NameEng) as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                ) aa
                where ID = ?
                ';
        $query=$this->db->query($sql, array($DepartementBudgeting))->result_array();
        return $query;
    }

    public function SearchDepartementBudgeting2($DepartementBudgeting)
    {
        $sql = 'select * from (
                select CONCAT("AC.",ID) as ID, CONCAT("Prodi ",Name) as NameDepartement,`Code` as Code from db_academic.program_study where Status = 1
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, CONCAT("Facultas ",Name) as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
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
                UNION
                select CONCAT("FT.",ID) as ID, NameEng as NameDepartement from db_academic.faculty where StBudgeting = 1
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
                           // check data exist 
                            $bool = false;
                            for ($k=0; $k < count($rs); $k++) { 
                                if ($rs[$k]['Code'] == $D2) {
                                    $bool = true;
                                    break;
                                }
                            }

                            if (!$bool) {
                                $rs[] = $arr[$j];
                            }
                           
                       }
                   }
               }
            }

        }

        return $rs;
    }

    public function GetAllBudgetGrouping($Year)
    {
        $arr = array();
        $sql = 'select CodePost,PostName,SUM(SubTotal) as total from (
                select cba.Year,c.CodePost,d.PostName,a.ID_creator_budget_approval,a.CodePostRealisasi,a.UnitCost,a.Freq,a.DetailMonth,
                   a.SubTotal,a.CreatedBy,a.CreatedAt,a.LastUpdateBy,a.LastUpdateAt,b.UnitDiv,b.CodeHeadAccount,
                         b.RealisasiPostName,b.Desc,c.Name as NameHeadAccount,dp.NameDepartement as NameUnitDiv,dp.Code as CodeDiv
                   from db_budgeting.creator_budget as a left join db_budgeting.cfg_postrealisasi as b on a.CodePostRealisasi = b.CodePostRealisasi
                   LEFT JOIN db_budgeting.cfg_head_account as c on b.CodeHeadAccount = c.CodeHeadAccount
                   LEFT JOIN db_budgeting.cfg_post as d on c.CodePost = d.CodePost
                   LEFT JOIN (
                    select CONCAT("AC.",ID) as ID,  CONCAT("Study ",NameEng) as NameDepartement,Code as Code from db_academic.program_study where Status = 1
                    UNION
                    select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                    UNION
                    select CONCAT("FT.",ID) as ID, CONCAT("Faculty ",NameEng) as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                   ) as dp on c.Departement = dp.ID
                   join db_budgeting.creator_budget_approval as cba on cba.ID = a.ID_creator_budget_approval
                                 where cba.`Year` = ? and cba.Status = 2

            ) as subquery
               group by CodePost
               order by CodePost asc';
        $query=$this->db->query($sql, array($Year))->result_array();
        $arr_ha = array();
        $arr_bc = $query;
        for ($i=0; $i < count($query); $i++) { 
            $sql2 = '
                    select CodeHeadAccount,NameHeadAccount,UnitDiv,NameUnitDiv,CodeDiv,SUM(SubTotal) as total from (
                                    select cba.Year,c.CodePost,d.PostName,a.ID_creator_budget_approval,a.CodePostRealisasi,a.UnitCost,a.Freq,a.DetailMonth,
                                       a.SubTotal,a.CreatedBy,a.CreatedAt,a.LastUpdateBy,a.LastUpdateAt,b.UnitDiv,b.CodeHeadAccount,
                                             b.RealisasiPostName,b.Desc,c.Name as NameHeadAccount,dp.NameDepartement as NameUnitDiv,dp.Code as CodeDiv
                                       from db_budgeting.creator_budget as a left join db_budgeting.cfg_postrealisasi as b on a.CodePostRealisasi = b.CodePostRealisasi
                                       LEFT JOIN db_budgeting.cfg_head_account as c on b.CodeHeadAccount = c.CodeHeadAccount
                                       LEFT JOIN db_budgeting.cfg_post as d on c.CodePost = d.CodePost
                                       LEFT JOIN (
                                        select CONCAT("AC.",ID) as ID,  CONCAT("Study ",NameEng) as NameDepartement,Code as Code from db_academic.program_study where Status = 1
                                        UNION
                                        select CONCAT("NA.",ID) as ID, Division as NameDepartement,Abbreviation as Code from db_employees.division where StatusDiv = 1
                                        UNION
                                        select CONCAT("FT.",ID) as ID, CONCAT("Faculty ",NameEng) as NameDepartement,Abbr as Code from db_academic.faculty where StBudgeting = 1
                                       ) as dp on c.Departement = dp.ID
                                       join db_budgeting.creator_budget_approval as cba on cba.ID = a.ID_creator_budget_approval
                                                     where cba.`Year` = ? and cba.Status = 2 and c.CodePost = ?
                                ) as subquery
                                group by CodeHeadAccount
                                order by CodeHeadAccount asc
                    ';
            $query2=$this->db->query($sql2, array($Year,$query[$i]['CodePost']))->result_array();
            for ($j=0; $j < count($query2); $j++) { 
                $arr_ha[] = $query2[$j];
            }

            $query[$i]['HeadAccount'] = $query2;    
        }
        $arr['post'] = $query;
        $arr['BudgetCategory'] = $arr_bc;
        $arr['HeadAccount'] = $arr_ha;
        return $arr;   
    }

    public function SearchDt_perHeadAccount($arr_code_ha,$arr_bulan,$arr_Department_split,$Year)
    {
        $rs = array();
        $arr_custom = $arr_code_ha;
        for ($i=0; $i < count($arr_custom); $i++) { 
            $arr_custom[$i] = '"'.$arr_custom[$i].'"';
        }
        $CodeHeadAccountIn = implode(',', $arr_custom);
        $sql = 'select a.CodeHeadAccount,a.Name,b.CodePostRealisasi,b.RealisasiPostName,b.UnitDiv,c.UnitCost,c.Freq,c.DetailMonth,c.SubTotal, d.Departement as DepartementID
                from db_budgeting.cfg_head_account as a join db_budgeting.cfg_postrealisasi as b on a.CodeHeadAccount = b.CodeHeadAccount
                    join db_budgeting.creator_budget as c on b.CodePostRealisasi = c.CodePostRealisasi
                    join db_budgeting.creator_budget_approval as d on c.ID_creator_budget_approval = d.ID
                    where a.CodeHeadAccount in ('.$CodeHeadAccountIn.') and d.`Status` = 2 and d.`Year` = ?;
                ';
        // print_r($sql);        
        $query=$this->db->query($sql, array($Year))->result_array();
        $arr_month_val = array();
        for ($i=0; $i < count($arr_bulan); $i++) { // loop bulan dahulu
            $keyValueFirst = $arr_bulan[$i]['keyValueFirst'];
            for ($j=0; $j < count($query); $j++) { // cari data berdasarkan headaccount multiple where dan data bisa lebih dari satu karena merge
                $DetailMonth = $query[$j]['DetailMonth'];
                $DetailMonth = (array) json_decode($DetailMonth,true);
                $UnitCost = $query[$j]['UnitCost']; // Unit cost untuk pengali value per bulan
                for ($k=0; $k < count($DetailMonth); $k++) { 
                    $month = $DetailMonth[$k]['month'];
                    if ($month == $keyValueFirst) {
                        $value = $DetailMonth[$k]['value'];
                        $v = $value * $UnitCost / 1000;
                        $v = (int)$v;
                        if (count($arr_month_val) == 0) {
                            $arr_month_val[] = array(
                                'keyValueFirst' => $keyValueFirst,
                                'value' => $v,
                            );
                        }
                        else
                        {
                            // check exist
                            $bool = true;
                            for ($l=0; $l < count($arr_month_val); $l++) { 
                                if ($arr_month_val[$l]['keyValueFirst'] == $keyValueFirst) {
                                   // exist and update
                                      $arr_month_val[$l]['value']  = $arr_month_val[$l]['value'] + $v;
                                    $bool = false;
                                    break;
                                }
                            }

                            if ($bool) { // add
                                $arr_month_val[] = array(
                                    'keyValueFirst' => $keyValueFirst,
                                    'value' => $v,
                                );
                            }
                        }

                        break;
                    }
                }
            }
        }


        $arr_Department_ac = $arr_Department_split['Academic'];
        // print_r($query);
        // print_r($arr_Department_ac);

        $arr_unit_ac_val = array();
        for ($i=0; $i < count($arr_Department_ac); $i++) { 
            $Code = $arr_Department_ac[$i]['Code'];
            $bool = true;
            for ($j=0; $j < count($query); $j++) { 
                $UnitDiv = $query[$j]['DepartementID'];
                if ($Code == $UnitDiv) {
                    $SubTotal = $query[$j]['SubTotal'] / 1000;
                    // check array key exist
                    $bool2 = true;
                    for ($k=0; $k < count($arr_unit_ac_val); $k++) { 
                        if ($arr_unit_ac_val[$k]['Code'] == $Code) { // exist
                            $arr_unit_ac_val[$k]['SubTotal'] = $arr_unit_ac_val[$k]['SubTotal']  + $SubTotal;
                            $bool2 = false;
                        }
                    }
                    if ($bool2) {
                        $arr_unit_ac_val[] = array(
                            'Code' => $Code,
                            'SubTotal' => $SubTotal,
                        );
                    }
                    $bool = false;
                }
                //break;
            }

            if ($bool) {
                $arr_unit_ac_val[] = array(
                    'Code' => $Code,
                    'SubTotal' => 0,
                );
            }

        }

        $arr_Department_nac = $arr_Department_split['NonAcademic'];
        $arr_unit_nac_val = array();
        for ($i=0; $i < count($arr_Department_nac); $i++) { 
            $Code = $arr_Department_nac[$i]['Code'];
            $bool = true;
            for ($j=0; $j < count($query); $j++) { 
                $UnitDiv = $query[$j]['DepartementID'];
                if ($Code == $UnitDiv) {
                    $SubTotal = $query[$j]['SubTotal'] / 1000;
                    // check array key exist
                    $bool2 = true;
                    for ($k=0; $k < count($arr_unit_nac_val); $k++) { 
                        if ($arr_unit_nac_val[$k]['Code'] == $Code) { // exist
                            $arr_unit_nac_val[$k]['SubTotal'] = $arr_unit_nac_val[$k]['SubTotal']  + $SubTotal;
                            $bool2 = false;
                        }
                    }
                    if ($bool2) {
                        $arr_unit_nac_val[] = array(
                            'Code' => $Code,
                            'SubTotal' => $SubTotal,
                        );
                    }
                    $bool = false;
                }
                //break;
            }

            if ($bool) {
                $arr_unit_nac_val[] = array(
                    'Code' => $Code,
                    'SubTotal' => 0,
                );
            }
        }

        $rs = array(
            'arr_month_val' => $arr_month_val,
            'arr_unit_ac_val' => $arr_unit_ac_val,
            'arr_unit_nac_val' => $arr_unit_nac_val

        );

        return $rs;
        
    }

    public function SumAnggaranPerYear($Departement,$Year)
    {
        $sql = 'select sum(b.SubTotal) as Total from db_budgeting.creator_budget_approval as a 
                join db_budgeting.creator_budget as b on a.ID = b.ID_creator_budget_approval
                where a.Departement = ? and a.Year = ?
                ';
        $query=$this->db->query($sql, array($Departement,$Year))->result_array();
        return (int)$query[0]['Total'];        
    }

    public function __tbl_cfg_set_userrole()
    {
        $sql = 'select * from db_budgeting.cfg_set_userrole order by MaxLimit asc
                ';
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }

    public function FindBudgetLeft_Department($ID_budget_left,$Departement)
    {
        $DepartementSess = $this->session->userdata('IDDepartementPUBudget');
        $AddSql ='where cba.Departement = "'.$Departement.'"';
        if ($DepartementSess == 'NA.9') {
            $AddSql = '';
        }
        $sql = 'select dd.ID,dd.`Using`,cc.CodePostRealisasi,cc.Year,cc.RealisasiPostName,cc.PostName,dd.ID_creator_budget,dd.Value
         ,cc.Departement,cc.CodeHeadAccount,cc.NameHeadAccount,cc.CodePost,cc.SubTotal as PriceBudgetAwal
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
                             '.$AddSql.' 
            ) cc join db_budgeting.budget_left as dd on cc.ID = dd.ID_creator_budget where dd.ID = ?
            ';
        $query=$this->db->query($sql, array($ID_budget_left))->result_array();
        return $query;
    }

    public function get_budget_left_group_by_month($ID_budget_left)
    {
        $sql = 'SELECT DISTINCT YEAR(a.PostingDate) AS "Year", MONTH(a.PostingDate) AS "Month" FROM db_budgeting.ap as a
                join db_budgeting.budget_payment as b on a.ID = b.ID_ap                
                where b.ID_budget_left = ?
                ';
        $query=$this->db->query($sql, array($ID_budget_left))->result_array();
        return $query;
        
    }

    public function get_budget_left_onprocess_group_by_month($ID_budget_left)
    {
        // print_r($ID_budget_left);die();
        $sql = 'SELECT DISTINCT YEAR(CreatedAt) AS "Year", MONTH(CreatedAt) AS "Month" FROM 
                (
                    select * from (
                        select a.CreatedAt,b.ID_payment from db_payment.payment as a
                        join 
                            (
                                select a.ID_payment,a.Perihal  from db_payment.spb as a
                                join db_payment.spb_detail as b on a.ID = b.ID_spb 
                                where b.ID_budget_left = '.$ID_budget_left.'
                               UNION 
                               select a.ID_payment,a.Perihal from db_payment.bank_advance as a
                               join db_payment.bank_advance_detail as b on a.ID = b.ID_bank_advance 
                               where b.ID_budget_left = '.$ID_budget_left.' group by b.ID_bank_advance
                               UNION 
                               select a.ID_payment,a.Perihal from db_payment.cash_advance  as a
                               join db_payment.cash_advance_detail as b on a.ID = b.ID_cash_advance 
                               where b.ID_budget_left = '.$ID_budget_left.' group by b.ID_cash_advance
                               UNION 
                               select a.ID_payment,a.Perihal from db_payment.petty_cash 
                               as a
                               join db_payment.petty_cash_detail as b on a.ID = b.ID_petty_cash 
                               where b.ID_budget_left = '.$ID_budget_left.' group by b.ID_petty_cash
                            ) as b
                            on a.ID = b.ID_payment
                    ) as py
                    where py.ID_payment not in (
                            select ap.ID_payment from db_budgeting.ap as ap
                            join db_budgeting.budget_payment as bp on ap.ID = bp.ID_ap
                            where bp.ID_budget_left != '.$ID_budget_left.' group by bp.ID_ap 
                        )
                    UNION
                    select a.CreatedAt,b.ID_payment from db_budgeting.pr_create as a
                    join (
                        select d.ID as ID_payment,a.PRCode from db_budgeting.pr_detail as a
                        left join db_purchasing.pre_po_detail as b on a.ID = b.ID_pr_detail
                        left join db_purchasing.po_detail as c on b.ID = c.ID_pre_po_detail
                        left join db_payment.payment as d on d.Code_po_create = c.Code
                        where a.ID_budget_left = '.$ID_budget_left.'
                        group by d.ID 

                    ) as b
                    on a.PRCode = b.PRCode
                    left join (
                                select ap.ID_payment from db_budgeting.ap as ap
                                join db_budgeting.budget_payment as bp on ap.ID = bp.ID_ap
                                where bp.ID_budget_left != '.$ID_budget_left.' group by bp.ID_ap 
                            )   as ap on ap.ID_payment = b.ID_payment
                    
                ) aa
                ';
             
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }  
}