<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_config extends Vreservation_Controler {

    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->load->model('m_api');
        $this->load->library('JWT');
        $this->load->model('master/m_master');
        $this->load->model('vreservation/m_reservation');
    }

    public function menu()
    {
        $content = $this->load->view($this->pathView.'config/menu','',true);
        $this->temp($content);
    }

    public function modal_form_previleges()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        echo $this->load->view($this->pathView.'config/modal_menu_previleges',$this->data,true);
    }

    public function get_menu()
    {
        $generate = $this->m_reservation->getdataMenu();
        echo json_encode($generate);
    }

    public function get_menu_save_menu()
    {
        $input = $this->getInputToken();
        $menu = $input['InputJenisMenu'];
        $this->m_reservation->saveMenu($menu);
    }

    public function get_submenu_save_menu()
    {
        $input = $this->getInputToken();
        $menu = $input['selectMenu'];
        $sub_menu1 = $input['sub_menu1'];
        $sub_menu2 = $input['sub_menu2'];
        $Slug = $input['Slug'];
        $Controller = $input['Controller'];
        $chkPrevileges = $input['chkPrevileges'];
        $this->m_reservation->saveSubMenu($menu,$sub_menu1,$sub_menu2,$chkPrevileges,$Slug,$Controller);
    }

    public function get_submenu_show()
    {
        $generate = $this->m_reservation->showSubmenu();
        echo json_encode($generate);
    }

    public function get_submenu_update()
    {
        $input = $this->getInputToken();
        $this->m_reservation->updateSubMenu($input);

    }

    public function get_submenu_delete()
    {
        $input = $this->getInputToken();
        $this->m_reservation->deleteSubMenu($input);
    }

    public function getGroupPrevileges()
    {
        $generate = $this->m_master->showData_array('db_reservation.cfg_group_user');
        echo json_encode($generate);
    }

    public function getMenu()
    {
        $generate = $this->m_master->showData_array('db_reservation.cfg_menu');
        echo json_encode($generate);
    }

    public function get_submenu_by_menu()
    {
        $input = $this->getInputToken();
        $generate = $this->m_reservation->get_submenu_by_menu($input);
        echo json_encode($generate);
    }

    public function get_previleges_group_show()
    {
        $input = $this->getInputToken();
        $GroupID = $input['Nama_search'];
        $generate = $this->m_reservation->get_previleges_group_show($GroupID);
        echo json_encode($generate);
    }

    public function save_groupuser_previleges()
    {
        $input = $this->getInputToken();
        $this->m_reservation->save_groupuser_previleges($input);
    }

    public function previleges_groupuser_update()
    {
        $input = $this->getInputToken();
        $this->m_reservation->previleges_groupuser_update($input);
    }

    public function previleges_groupuser_delete()
    {
        $input = $this->getInputToken();
        $this->m_reservation->previleges_groupuser_delete($input);
    }

    public function modalform_group_user()
    {
        $input = $this->getInputToken();
        $this->data['action'] = $input['Action'];
        echo $this->load->view($this->pathView.'config/modal_group_user',$this->data,true);
    }

    public function save_group_user()
    {
        $input = $this->getInputToken();
        $dataSave = array(
            'GroupAuth' => $input['groupName'],
        );
        $this->db->insert('db_reservation.cfg_group_user', $dataSave);
    }

    public function update_group_user()
    {
        $input = $this->getInputToken();
        $ID = $input['ID'];
        $GroupAuth = $input['GroupAuth'];
        $sql = "update db_reservation.cfg_group_user set GroupAuth = ? where ID = ? ";
        $query=$this->db->query($sql, array($GroupAuth,$ID));
    }

    public function delete_group_user()
    {
        $input = $this->getInputToken();
        $sql = "delete from db_reservation.cfg_group_user where ID = ".$input['ID'];
        $query=$this->db->query($sql, array());
    }

    public function g_previleges()
    {
        $content = $this->load->view($this->pathView.'config/previleges','',true);
        $this->temp($content);
    }


}
