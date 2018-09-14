<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_budgeting extends CI_Model {


    function __construct()
    {
        parent::__construct();
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
}