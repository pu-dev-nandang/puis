<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_top5_content_model extends CI_Model {

    function __construct() {
       parent::__construct();
       $this->columns = isset($this->subdata['tbl_total_top5_content']) ? $this->subdata['tbl_total_top5_content']['columns'] : [];
       $this->sqlFrom = '
                        select "auto" as No, kb.ID,kb.Desc,kbt.Type,kb.EntredBy,emp.Name as EnteredByName,kbt.IDDivision,qdj.NameDepartment,qdj.Abbr,
                        (select count(*) as total from db_employees.log_countable_content as lcc
                         join db_employees.employees as emp on emp.NIP = lcc.NIP
                         join db_employees.knowledge_base as sub_kb on sub_kb.ID = lcc.ContentID
                         join db_employees.kb_type as kt on kt.ID = sub_kb.IDType
                         where ContentID = kb.ID and TypeContent = "knowledge_base" limit 1 ) AS Countable
                        from db_employees.knowledge_base as kb join db_employees.kb_type as kbt on kb.IDType = kbt.ID
                        join db_employees.employees as emp on  kb.EntredBy =  emp.NIP
                        '.$this->m_master->QueryDepartmentJoin('kbt.IDDivision').'
                        ';
    }

    function get_all($start = 0, $length, $filter = array(), $order = array()) {
        $this->filtered($filter);
        if ($order) {
            $order['column'] = $this->columns[$order['column']]['name'];
            $this->db->order_by($order['column'], $order['dir']);
        }
        $data = $this->db->select('ID,No, Desc, Type, EntredBy, EnteredByName, NameDepartment, Countable,Abbr')
                ->limit($length, $start);

        return $this->db->get('( '.$this->sqlFrom .' ) as summary');
    }

    private function filtered($filter = array()){
        // print_r($filter);die();
        if ($filter) {
            $this->db->group_start();
            foreach ($filter as $column => $value) {
                $this->db->like('IFNULL(' . $this->columns[$column]['name'] . ',"")', $value);
            }
            $this->db->group_end();
        }
    }

    public function get_total($filter = array()){
        $this->filtered($filter);
        $data = $this->db->count_all_results('( '.$this->sqlFrom .' ) as summary');
        return $data;
    }


}