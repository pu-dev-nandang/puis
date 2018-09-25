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
                join db_budgeting.cfg_menu as b
                on b.ID = c.ID_Menu where a.NIP = ? and b.IDDepartement = ? GROUP by b.id';
        $query=$this->db->query($sql, array($NIP,$MenuDepartement))->result_array();
        return $query;
    }

    public function getData_cfg_postrealisasi($Active = null)
    {
        $arr_result = array();
        $Active = ($Active == null) ? '' : ' where a.Active = "'.$Active.'"';
        $sql = 'select a.CodePostRealisasi,a.CodePost,b.PostName,a.RealisasiPostName,a.Departement from db_budgeting.cfg_postrealisasi as a join db_budgeting.cfg_post as b on a.CodePost = b.CodePost
                '.$Active;
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
                where b.Departement = ?
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
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division
                ) aa
                ) as d on b.Departement = d.ID
                where (b.RealisasiPostName like "%'.$PostDepartement.'%" or d.NameDepartement like "%'.$PostDepartement.'%" 
                or c.PostName like "%'.$PostDepartement.'%" or b.CodePostRealisasi like "%'.$PostDepartement.'%")
                order by b.CodePostRealisasi asc 
                ';
        $query=$this->db->query($sql, array())->result_array();
        return $query;
    }
}