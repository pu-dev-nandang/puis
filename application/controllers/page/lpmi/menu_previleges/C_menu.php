<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_menu extends It_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('db_select')) {
           $this->session->set_userdata('db_select','db_lpmi');
        }

        $this->data['db_select'] = $this->session->userdata('db_select');
    }

    public function set_session_db()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $Input = $this->getInputToken();
        $db_select = $Input['db_select'];
        $this->session->set_userdata('db_select',$db_select);
        echo json_encode(1);
    }

    public function menu_request($page){
        $data['page'] = $page;
        $content = $this->load->view('page/lpmi/menu_previleges/menu_choose',$data,true);
        $this->temp($content);
    }

    public function menu()
    {
        $sql = 'select a.* from '.$this->data['db_select'].'.cfg_menu as a 
                order by a.Sort asc';
        $query=$this->db->query($sql, array())->result_array();
        $this->data['Arr_Menu'] =$query;
    	$content = $this->load->view('page/lpmi/menu_previleges/menu',$this->data,true);
    	$this->menu_request($content);
    }

    public function save_menu()
    {
    	$Input = $this->getInputToken();
    	$action = $Input['action'];
    	$rs = array();
    	switch ($action) {
    		case 'read':
				$sql = 'select a.* from '.$this->data['db_select'].'.cfg_menu as a 
                        order by a.Sort asc';
				$query=$this->db->query($sql, array())->result_array();
				echo json_encode($query);
    			break;
    		case 'add':
    			unset($Input['ID']);
    			unset($Input['action']);
    			$this->db->insert($this->data['db_select'].'.cfg_menu',$Input);
                $sql = 'select a.* from '.$this->data['db_select'].'.cfg_menu as a 
                        order by a.Sort asc';
				$query=$this->db->query($sql, array())->result_array();
				$rs['data'] = $query;
				echo json_encode($rs);
    			break;
    		case 'edit':
    			$ID = $Input['ID'];
    			unset($Input['action']);
    			unset($Input['ID']);
    			$this->db->where('ID',$ID);
    			$this->db->update($this->data['db_select'].'.cfg_menu',$Input);
				echo json_encode(1);
    			break;
    		case 'delete':
    			$ID = $Input['ID'];
    			$this->db->where('ID',$ID);
    			$this->db->delete($this->data['db_select'].'.cfg_menu');
				echo json_encode(1);
    			break;	
    		default:
    			# code...
    			break;
    	}
    }

    public function sub_menu()
    {
		$sql = 'select a.Menu,a.Sort,b.* from '.$this->data['db_select'].'.cfg_menu as a
				join '.$this->data['db_select'].'.cfg_sub_menu as b 
				on a.ID = b.ID_Menu order by a.Sort asc,b.Sort1 asc,b.Sort2 asc
				';
		$query=$this->db->query($sql, array())->result_array();
		$this->data['Arr_dt'] =$query;
		$this->data['Arr_Menu'] =$this->m_master->showData_array($this->data['db_select'].'.cfg_menu');				
		$content = $this->load->view('page/lpmi/menu_previleges/sub_menu',$this->data,true);
        $this->menu_request($content);
    }

    public function save_sub_menu()
    {
    	$Input = $this->getInputToken();
    	$action = $Input['action'];
    	$rs = array();
    	switch ($action) {
    		case 'add':
    			unset($Input['ID']);
    			unset($Input['action']);
    			$actionCh = $Input['actionCh']; // for akses
    			unset($Input['actionCh']);
    			$t = array(
    				'read' => 1,
    				'write' => 0,
    				'update' => 0,
    				'delete' => 0,
    			);
    			if ($actionCh == 1) {
    				$t = array(
    					'read' => 1,
    					'write' => 1,
    					'update' => 1,
    					'delete' => 1,
    				);
    			}

    			$Input = $Input + $t;
    			$this->db->insert($this->data['db_select'].'.cfg_sub_menu',$Input);
				$sql = 'select a.Menu,a.Sort,b.* from '.$this->data['db_select'].'.cfg_menu as a
						join '.$this->data['db_select'].'.cfg_sub_menu as b 
						on a.ID = b.ID_Menu order by a.Sort asc,b.Sort1 asc,b.Sort2 asc
						';
				$query=$this->db->query($sql, array())->result_array();
				$rs['data'] = $query;
				echo json_encode($rs);
    			break;
    		case 'edit':
    			$ID = $Input['ID'];
    			unset($Input['ID']);
    			unset($Input['action']);
    			$actionCh = $Input['actionCh']; // for akses
    			unset($Input['actionCh']);
    			$t = array(
    				'read' => 1,
    				'write' => 0,
    				'update' => 0,
    				'delete' => 0,
    			);
    			if ($actionCh == 1) {
    				$t = array(
    					'read' => 1,
    					'write' => 1,
    					'update' => 1,
    					'delete' => 1,
    				);
    			}

    			$Input = $Input + $t;
    			$this->db->where('ID',$ID);
    			$this->db->update($this->data['db_select'].'.cfg_sub_menu',$Input);
				echo json_encode(1);
    			break;
    		case 'delete':
    			$ID = $Input['ID'];
    			$this->db->where('ID',$ID);
    			$this->db->delete($this->data['db_select'].'.cfg_sub_menu');
				echo json_encode(1);
    			break;	
    		default:
    			# code...
    			break;
    	}
    }

    public function groupuser()
    {
    	$content = $this->load->view('page/lpmi/menu_previleges/group_user',$this->data,true);
    	$this->menu_request($content);
    }

    public function group_previleges_crud()
    {
    	$Input = $this->getInputToken();
    	$action = $Input['action'];
    	switch ($action) {
    		case 'read':
    			$generate = $this->m_master->showData_array($this->data['db_select'].'.cfg_group_user');
    			echo json_encode($generate);
    			break;
    		case 'add':
    			$dataSave = array(
    			    'GroupAuth' => $Input['groupName'],
    			);
    			$this->db->insert($this->data['db_select'].'.cfg_group_user', $dataSave);
    			break;
    		case 'edit':
    			$dataSave = array(
    			    'GroupAuth' => $Input['GroupAuth'],
    			);
    			$this->db->where('ID',$Input['ID']);
    			$this->db->update($this->data['db_select'].'.cfg_group_user', $dataSave);
    			break;	
    		case 'delete':
    			$this->db->where('ID',$Input['ID']);
    			$this->db->delete($this->data['db_select'].'.cfg_group_user');
    			break;	
    		default:
    			# code...
    			break;
    	}
    }

    public function get_submenu_by_menu()
    {
    	$Input = $this->getInputToken();
    	$generate = $this->m_menu2->get_submenu_by_menu($Input,$this->data['db_select'].'');
    	echo json_encode($generate);
    }

    public function save_submenu_by_menu()
    {
    	$Input = $this->getInputToken();
    	$cfg_group_user = $Input['ID_GroupUSer'];
    	$getData = $Input['getData'];
    	$getData = json_decode(json_encode($getData),true);
    	$t = array(
    		'read' => 1,
    		'write' => 0,
    		'update' => 0,
    		'delete' => 0,
    	);
    	for ($i=0; $i < count($getData); $i++) { 
    		if ($getData[$i]['value'] == 1) {
    			$t = array(
    				'read' => 1,
    				'write' => 1,
    				'update' => 1,
    				'delete' => 1,
    			);
    		}

    		$dataSave = array(
    			'cfg_group_user' => $cfg_group_user,
    			'ID_cfg_sub_menu' => $getData[$i]['ID'],
    		);

    		$dataSave = $dataSave + $t;
    		$this->db->insert($this->data['db_select'].'.cfg_rule_g_user', $dataSave);
    	}

    }

    public function group_previleges_rud()
    {
    	$Input = $this->getInputToken();
    	$action = $Input['action'];
    	switch ($action) {
    		case 'read':
    			$GroupID = $Input['Nama_search'];
    			$generate = $this->m_menu2->get_previleges_group_show($GroupID,$this->data['db_select'].'');
    			echo json_encode($generate);
    			break;
    		case 'edit':
    			$t = array(
    				'read' => 1,
    				'write' => 0,
    				'update' => 0,
    				'delete' => 0,
    			);
    			if ($Input['actionCh'] == 1) {
    				$t = array(
    					'read' => 1,
    					'write' => 1,
    					'update' => 1,
    					'delete' => 1,
    				);
    			}
    			$ID = $Input['ID'];
    			$this->db->where('ID',$ID);
    			$this->db->update($this->data['db_select'].'.cfg_rule_g_user',$t);
    			break;
            case 'delete':
                $ID = $Input['ID'];
                $this->db->where('ID',$ID);
                $this->db->delete($this->data['db_select'].'.cfg_rule_g_user');
                break;    
    		default:
    			# code...
    			break;
    	}
    }

    public function getAuthDataTables()
    {
        $requestData= $_REQUEST;
        // print_r($requestData);
        $totalData = $this->m_master->getCountAllDataAuth($this->data['db_select'].'.previleges_guser');

        // get NIP
        $NIP = $this->session->userdata('NIP');
        $get = $this->m_master->caribasedprimary($this->data['db_select'].'.previleges_guser','NIP',$NIP);
        if ($get[0]['G_user'] == 1) {
            if( !empty($requestData['search']['value']) ) {
                $sql = 'SELECT a.NIP,b.Name,a.G_user FROM '.$this->data['db_select'].'.previleges_guser as a join db_employees.employees as b
                        on a.NIP = b.NIP  left join '.$this->data['db_select'].'.cfg_group_user as cgu on a.G_user = cgu.ID';

                $sql.= ' where a.NIP LIKE "'.$requestData['search']['value'].'%" or b.Name LIKE "%'.$requestData['search']['value'].'%" or cgu.GroupAuth LIKE "%'.$requestData['search']['value'].'%"';
                $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

            }
            else {
                 $sql = 'SELECT a.NIP,b.Name,a.G_user FROM '.$this->data['db_select'].'.previleges_guser as a join db_employees.employees as b
                         on a.NIP = b.NIP  left join '.$this->data['db_select'].'.cfg_group_user as cgu on a.G_user = cgu.ID';
                 $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

            }
        }
        else
        {
            if( !empty($requestData['search']['value']) ) {
                $sql = 'SELECT a.NIP,b.Name,a.G_user FROM '.$this->data['db_select'].'.previleges_guser as a join db_employees.employees as b
                        on a.NIP = b.NIP  left join '.$this->data['db_select'].'.cfg_group_user as cgu on a.G_user = cgu.ID';

                $sql.= ' where a.NIP LIKE "'.$requestData['search']['value'].'%" or b.Name LIKE "%'.$requestData['search']['value'].'%" and a.G_user != 1 or cgu.GroupAuth LIKE "%'.$requestData['search']['value'].'%"';
                $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

            }
            else {
                 $sql = 'SELECT a.NIP,b.Name,a.G_user FROM '.$this->data['db_select'].'.previleges_guser as a join db_employees.employees as b
                         on a.NIP = b.NIP and a.G_user != 1 left join '.$this->data['db_select'].'.cfg_group_user as cgu on a.G_user = cgu.ID';
                 $sql.= ' ORDER BY a.NIP ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

            }
        }

        $query = $this->db->query($sql)->result_array();

        if ($get[0]['G_user'] == 1) {
            $getGroupUser = $this->m_master->showData_array($this->data['db_select'].'.cfg_group_user');
        }
        else
        {
            $getGroupUser = $this->m_master->getDataWithoutSuperAdminGlobal($this->data['db_select'].'.cfg_group_user');
        }

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];

            $nestedData[] = $row['NIP'];
            $nestedData[] = $row['Name'];

            $combo = '<select class="full-width-fix select grouPAuth btn-edit" NIP = "'.$row['NIP'].'">';
            for ($j=0; $j < count($getGroupUser); $j++) { 
                if ($getGroupUser[$j]['ID'] == $row['G_user']) {
                     $combo .= '<option value = "'.$getGroupUser[$j]['ID'].'" selected>'.$getGroupUser[$j]['GroupAuth'].'</option>';
                }
                else
                {
                    $combo .= '<option value = "'.$getGroupUser[$j]['ID'].'">'.$getGroupUser[$j]['GroupAuth'].'</option>';
                }
            }

            $combo .= '</select>';

            $nestedData[] = $combo;

            $btn = '<button class="btn btn-primary btn-sm btn-write btn-save-groupauth" NIP = "'.$row['NIP'].'"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>&nbsp<button class="btn btn-danger btn-sm btn-write btn-delete-groupauth" NIP = "'.$row['NIP'].'"><i class="fa fa-trash" aria-hidden="true"></i></button>';  

            $nestedData[] = $btn;
            $data[] = $nestedData;
        }

        // print_r($data);

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function authUser_cud()
    {
    	$Input = $this->getInputToken();
    	$action = $Input['action'];
    	switch ($action) {
    		case 'add':
    			// check NIP existing
    			$G_ = $this->m_master->caribasedprimary($this->data['db_select'].'.previleges_guser','NIP',$Input['NIP']);
    			if (count($G_) == 0) {
    				$dataSave = array(
    				    'NIP' => $Input['NIP'],
    				    'G_user' => $Input['GroupUser'],
    				);
    				$this->db->insert($this->data['db_select'].'.previleges_guser', $dataSave);
    				echo json_encode(1);
    			}
    			else
    			{
    				echo json_encode('User Already exist');
    			}
    			
    			break;
    		case 'edit':
    			$input = $this->getInputToken();
    			$dataSave = array(
    			    'G_user' => $input['valuee'],
    			);
    			$this->db->where('NIP', $input['NIP']);
    			$this->db->update($this->data['db_select'].'.previleges_guser', $dataSave);
    			break;
    		case 'delete':
    			$input = $this->getInputToken();
    			$sql = "delete from ".$this->data['db_select'].".previleges_guser where NIP = '".$input['NIP']."'";
    			$query=$this->db->query($sql, array());
    			break;	
    		default:
    			# code...
    			break;
    	}
    }

}
