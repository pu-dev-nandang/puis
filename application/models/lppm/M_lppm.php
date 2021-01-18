<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class M_lppm extends CI_Model {
 
    var $table = 'db_lppm.content';
    var $column_order = array('Title','Description','Status',null); //set column field database for datatable orderable
    var $column_search = array('Title','Description','Status'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('ID' => 'desc'); // default order 
    
    public function __construct()
    {
        parent::__construct();
        // $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        if($this->input->post('type')){
            $sql = 'select ci.* from db_lppm.content_index AS ci
                            WHERE ci.SegmentMenu="'.$this->input->post('type').'" ';
            $query=$this->db->query($sql, array())->result_array();
            $getvaID= $query[0]['ID'];  
            $this->db->where('IDindex', $getvaID);
        }
        
        $this->db->from('db_lppm.content'); 
        $i = 0;
        
        // if(!isset($_POST['category']))
        // {
        //     $this->db->join('db_lppm.category', 'db_lppm.content.IDCat = db_lppm.category.ID');

        // }

        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.

                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }    
    
    public function get_by_id($id)
    {
        $this->db->select('co.ID,co.IDindex,co.IDSubCat,co.Title,co.Meta_description,co.Meta_keywords,co.Description,co.File,co.Lang,co.AddDate,co.Status,co.CreateAt,co.CreateBy,co.UpdatedAt,co.UpdatedBy');
        $this->db->select('sb.IDSub,sb.IDCat,sb.SubName');
        $this->db->select('cat.ID as ID1,cat.Name,cat.Lang as Lang1');
        $this->db->from('db_lppm.content as co');
        $this->db->join('db_lppm.sub_category as sb','sb.IDSub=co.IDSubCat','left');          
        $this->db->join('db_lppm.category as cat','sb.IDCat=cat.ID','left');
        $this->db->where('co.ID',$id);       
        $query = $this->db->get(); 
        return $query->row();

    }
    
    public function get_by_idCat($id)
    {
        $this->db->from('db_lppm.category');
        $this->db->where('ID',$id);
        $query = $this->db->get(); 
        return $query->row();
    }

    public function save($data,$type)
    {   
        $sql = 'select ci.* from db_lppm.content_index AS ci
                            WHERE ci.SegmentMenu="'.$type.'" ';
        $query=$this->db->query($sql, array())->result_array();
        $data['IDindex'] = $query[0]['ID'];
         // print_r($data);die();
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

 
    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }    
    

    public function delete_by_id($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete($this->table);
    }





    // category

    public function get_category(){
        $this->db->from("db_lppm.category");
        $this->db->order_by('ID','desc');
        $q = $this->db->get();
        return $q->result();  
    }

    public function saveCat($data)
    {           
        $this->db->insert('db_lppm.category', $data);
        return $this->db->insert_id();
    }

    public function updateCat($where, $data)
    {
        $this->db->update('db_lppm.category', $data, $where);
        return $this->db->affected_rows();
    }
 

    public function delete_by_idCat($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('db_lppm.category');
    }




    // sub category

    public function getCategory($getidlang)
    {
        $hasil=$this->db->query("SELECT * FROM db_lppm.category where Lang = '".$getidlang."' ")->result_array();
        return $hasil;
    }

    public function getSubCategory($getidcat,$getidsubcat)
    {
        $hasil=$this->db->query("SELECT * FROM db_lppm.category as ck 
                                 left join db_lppm.sub_category as sc on sc.IDCat=ck.ID 
                                 where sc.IDCat = '".$getidcat."' or IDSub='".$getidsubcat."' ")
        ->result_array();
        return $hasil;
    }

    public function get_by_idSubCat($id)
    {
        $this->db->from('db_lppm.sub_category as sc');
        $this->db->join('db_lppm.category as ck','sc.IDCat=ck.ID');
        $this->db->where('sc.IDSub',$id);
        $query = $this->db->get(); 
        return $query->row();
    }

    public function get_Subcategory(){
        $this->db->from("db_lppm.sub_category as sc");
        $this->db->join("db_lppm.category as ck", "ck.ID = sc.IDCat");
        $this->db->order_by('sc.IDSub','desc');
        $q = $this->db->get();
        return $q->result();  
    }

    public function saveSubCat($data)
    {           
        $this->db->insert('db_lppm.sub_category', $data);
        return $this->db->insert_id();
    }

    public function updateSubCat($where, $data)
    {
        $this->db->update('db_lppm.sub_category', $data, $where);
        return $this->db->affected_rows();
    }
 

    public function delete_by_idSubCat($id)
    {
        $this->db->where('IDSub', $id);
        $this->db->delete('db_lppm.sub_category');
    }
 
 
}
