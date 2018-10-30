<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
  Author  : Alhadi Rahman
  Date    : 29 Okt 2018
  Term & Condition : you must have table name :
    * cfg_group_user
    * cfg_menu
    * cfg_rule_g_user
    * cfg_sub_menu
    * previleges_guser
  see folder database in path /database/Adi/menu.sql  
*/


class M_menu extends CI_Model {

  public $data = array();
  private $NameSess = '';
  private $AuthNameSession = '';
  private $MenuSess = '';
  private $MenuSessGrouping = '';
  private $dbAuth = '';


  public function __construct()
  {
      parent::__construct();
  }

  public function set_model($NameSess = 'NameSess',$AuthNameSession = 'AuthNameSession',$MenuSess = 'MenuSess',$MenuSessGrouping = 'MenuSessGrouping',$dbAuth= 'db_admission')
  {
    $this->NameSess = $NameSess;
    $this->AuthNameSession = $AuthNameSession;
    $this->MenuSess = $MenuSess;
    $this->MenuSessGrouping = $MenuSessGrouping;
    $this->dbAuth = $dbAuth;
    $this->getParentConstruct();
  }

  private function getParentConstruct()
  {
      // check user auth
      if (!$this->session->userdata($this->NameSess)) {
          $check = $this->auth();
          if (!$check) {
              // not authorize
              redirect(base_url().'/');
          }
          else
          {
              if (!$this->session->userdata($this->AuthNameSession)) {
                  $this->getAuth();
              }
          }
      }
  }

  private function auth()
  {
      $NIP = $this->session->userdata('NIP');
      $getData = $this->getUserAuth($NIP);
      if (count($getData) > 0) {
          $this->session->set_userdata($this->NameSess,1);
          return true;
      }

      return false;
  }

  private function getAuth()
  {
      $data = array();
      $getDataMenu = $this->getMenuGroupUser();
      $data_sess = array();
      if (count($getDataMenu) > 0) {
          $this->session->set_userdata($this->AuthNameSession,1);
          $this->session->set_userdata($this->MenuSess,$getDataMenu);
          $this->session->set_userdata($this->MenuSessGrouping,$this->groupBYMenu_sess());
      }
  }

  private function getMenuGroupUser()
  {
    $NIP = $this->session->userdata('NIP');
    $db = $this->dbAuth;
      $sql = 'SELECT b.ID as ID_menu,b.Icon,c.ID,b.Menu,c.SubMenu1,c.SubMenu2,x.`read`,x.`update`,x.`write`,x.`delete`,c.Slug,c.Controller 
              from db_employees.employees as a
              join '.$db.'.previleges_guser as d
              on a.NIP = d.NIP
              join '.$db.'.cfg_rule_g_user as x
              on d.G_user = x.cfg_group_user
              join '.$db.'.cfg_sub_menu as c
              on x.ID_cfg_sub_menu = c.ID
              join '.$db.'.cfg_menu as b
              on b.ID = c.ID_Menu where a.NIP = ? GROUP by b.id';
      $query=$this->db->query($sql, array($NIP))->result_array();
      return $query;
  }

  public function getUserAuth($NIP)
  {
      $dpt = $this->session->userdata('IDdepartementNavigation');
      $sql = 'select CONCAT(a.Name," | ",a.NIP) as Name, a.NIP from db_employees.employees as a 
        join db_employees.rule_users as b
        on a.NIP = b.NIP
        where b.IDDivision = ? and a.NIP = ?
        GROUP BY a.NIP';
      $query=$this->db->query($sql, array($dpt,$NIP))->result_array();
      return $query;
  }

  private function groupBYMenu_sess()
  {
      $DataDB = $this->session->userdata($this->MenuSess);
      $arr = array();
      for ($i=0; $i < count($DataDB); $i++) {
          $submenu1 = $this->getSubmenu1BaseMenu_grouping($DataDB[$i]['ID_menu'],$this->dbAuth);
          $arr2 = array();
          for ($k=0; $k < count($submenu1); $k++) { 
              $submenu2 = $this->getSubmenu2BaseSubmenu1_grouping($submenu1[$k]['SubMenu1'],$this->dbAuth,$DataDB[$i]['ID_menu']);
              $arr2[] = array(
                  'SubMenu1' => $submenu1[$k]['SubMenu1'],
                  'Submenu' => $submenu2,
              );
          }

          if ($i == 0) {
              // SORTING ASC
                  usort($arr2, function($a, $b) {
                      return $a['SubMenu1'] - $b['SubMenu1'];
                  });
          }
          

          $arr[] =array(
              'Menu' => $DataDB[$i]['Menu'],
              'Icon' => $DataDB[$i]['Icon'],
              'Submenu' => $arr2

          );
          
      }

      return $arr;
  }

  private function getSubmenu1BaseMenu_grouping($ID_Menu,$db='db_admission')
  {
      $sql = 'SELECT a.ID,a.ID_Menu,a.SubMenu1,a.SubMenu2,a.Slug,a.Controller,b.read,b.write,b.update,b.delete 
      from '.$db.'.cfg_sub_menu as a join '.$db.'.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
      join '.$db.'.previleges_guser as c on b.cfg_group_user = c.G_user  where a.ID_Menu = ? and c.NIP = ? group by a.SubMenu1';
      $query=$this->db->query($sql, array($ID_Menu,$this->session->userdata('NIP')))->result_array();
      return $query;
  }

  private function getSubmenu2BaseSubmenu1_grouping($submenu1,$db='db_admission',$IDmenu = null)
  {
      if ($IDmenu != null) {
          $sql = 'SELECT a.ID,a.ID_Menu,a.SubMenu1,a.SubMenu2,a.Slug,a.Controller,b.read,b.write,b.update,b.delete 
          from '.$db.'.cfg_sub_menu as a  join '.$db.'.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
          join '.$db.'.previleges_guser as c on b.cfg_group_user = c.G_user
           where a.SubMenu1 = ? and c.NIP = ? and a.ID_Menu = ?';
          $query=$this->db->query($sql, array($submenu1,$this->session->userdata('NIP'),$IDmenu))->result_array();
      }
      else
      {
          $sql = 'SELECT a.ID,a.ID_Menu,a.SubMenu1,a.SubMenu2,a.Slug,a.Controller,b.read,b.write,b.update,b.delete 
          from '.$db.'.cfg_sub_menu as a  join '.$db.'.cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
          join '.$db.'.previleges_guser as c on b.cfg_group_user = c.G_user
           where a.SubMenu1 = ? and c.NIP = ?';
          $query=$this->db->query($sql, array($submenu1,$this->session->userdata('NIP')))->result_array();
      }
      
      return $query;
  }

  private function chkAuthDB_Base_URL($URL,$db = 'db_admission')
  {
      $a = explode('/', $URL);
      $b = count($a) - 1;
      $URISlug = 'and a.Slug = "'.$URL.'"';
      if ($a[$b] == 1) {
          $URISlug = '';
          for ($i=0; $i < count($b); $i++) { 
              $URISlug .= $a[$i].'/';
          }
          $URISlug = 'and a.Slug like "%'.$URISlug.'%"';
      }
      $sql = "select b.read,b.write,b.update,b.delete from ".$db.".cfg_sub_menu as a join ".$db.".cfg_rule_g_user as b on a.ID = b.ID_cfg_sub_menu
      join ".$db.".previleges_guser as c on c.G_user = b.cfg_group_user
      where c.NIP = ? ".$URISlug;
      $query=$this->db->query($sql, array($this->session->userdata('NIP')))->result_array();
      return $query;
  }

  public function checkAuth_user($db)
  {
      $base_url = base_url();
      $currentURL = current_url();
      $URL = str_replace($base_url,"",$currentURL);
      
      // get Access URL
      $getDataSess  = $this->session->userdata($this->MenuSessGrouping);
      $access = array(
          'read' => 0,
          'write' => 0,
          'update' => 0,
          'delete' => 0,
      );

      $p = $this->chkAuthDB_Base_URL($URL,$db);
      if (count($p) > 0 ) {
          $access = array(
              'read' => $p[0]['read'],
              'write' => $p[0]['write'],
              'update' => $p[0]['update'],
              'delete' => $p[0]['delete'],
          );
      }

      $html = '';
      if ($access['read'] == 0) {
          $html .= '<script type="text/javascript">
               var waitForEl = function(selector, callback) {
                 if (jQuery(selector).length) {
                   callback();
                 } else {
                   setTimeout(function() {
                     waitForEl(selector, callback);
                   }, 100);
                 }
               };

               waitForEl(".btn-read", function() {
                 $(".btn-read").remove();
               });

               $(document).ready(function () {
                   $(".btn-read").remove();
                   $(document).ajaxComplete(function () {
                       $(".btn-read").remove();
                   });
               });
               </script>
          ';
          echo $html;
      }

      if ($access['write'] == 0) {
          $html .= '<script type="text/javascript">
               var waitForEl = function(selector, callback) {
                 if (jQuery(selector).length) {
                   callback();
                 } else {
                   setTimeout(function() {
                     waitForEl(selector, callback);
                   }, 100);
                 }
               };

               waitForEl(".btn-add", function() {
                 $(".btn-add").remove();
               });

               $(document).ready(function () {
                   $(".btn-add").remove();
                   $(document).ajaxComplete(function () {
                      $(".btn-add").remove();
                   });
               });
               </script>
          ';
          echo $html;
      }
      if ($access['update'] == 0) {
          $html .= '<script type="text/javascript">
               var waitForEl = function(selector, callback) {
                 if (jQuery(selector).length) {
                   callback();
                 } else {
                   setTimeout(function() {
                     waitForEl(selector, callback);
                   }, 100);
                 }
               };

               waitForEl(".btn-edit", function() {
                 $(".btn-edit").remove();
               });

               $(document).ready(function () {
                   $(".btn-edit").remove();
                   $(document).ajaxComplete(function () {
                            $(".btn-edit").remove();
                   });
               });
               </script>
          ';
          echo $html;
      }
      if ($access['delete'] == 0) {
          $html .= '<script type="text/javascript">
               var waitForEl = function(selector, callback) {
                 if (jQuery(selector).length) {
                   callback();
                 } else {
                   setTimeout(function() {
                     waitForEl(selector, callback);
                   }, 100);
                 }
               };

               waitForEl(".btn-delete", function() {
                 $(".btn-delete").remove();
               });

               waitForEl(".btn-Active", function() {
                 $(".btn-Active").remove();
               });

               $(document).ready(function () {
                  $(".btn-delete").remove();
                  $(".btn-Active").remove();
                  $(document).ajaxComplete(function () {
                      $(".btn-delete").remove();
                      $(".btn-Active").remove();
                  });
                   
               });
               
               </script>
          ';
          echo $html;
      }


      // special menu & group
      $bool = true;
      foreach ($access as $key => $value) {
          if ($value == 0) {
              $bool = false;
              break;
          }
      }

      if (!$bool) {
          $html .= '<script type="text/javascript">
               var waitForEl = function(selector, callback) {
                 if (jQuery(selector).length) {
                   callback();
                 } else {
                   setTimeout(function() {
                     waitForEl(selector, callback);
                   }, 100);
                 }
               };

               waitForEl(".btn-delete-menu-auth", function() {
                  $(".btn-delete-menu-auth").remove();
               });

               waitForEl(".btn-edit-menu-auth", function() {
                 $(".btn-edit-menu-auth").remove();
               });

               waitForEl(".btn-edit-menu-auth", function() {
                 $(".btn-edit-menu-auth").remove();
               })

               waitForEl(".btn-add-menu-auth", function() {
                 $(".btn-add-menu-auth").remove();
               });

               waitForEl(".btn-delete-menu-auth", function() {
                 $(".btn-delete-menu-auth").remove();
               });
               
               </script>
          ';
          echo $html;
      }
      return $html;
  }

}
