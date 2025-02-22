<?php 
class M_home extends CI_Model{

    var $table = 'db_prodi.prodi_texting';
    var $column_order = array('Title','Description',null); //set column field database for datatable orderable
    var $column_search = array('Title','Description'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('ID' => 'desc'); // default order 
    // var $prodi_active_id = $this->session->userdata('prodi_active_id');


    public function __construct()
    {
        parent::__construct();
        // Load database prodi
        $this->load->library('JWT');
        
    }

    function getTableProdi(){// db_academic

    	$prodi_active_id = $this->session->userdata('prodi_active_id');

        $hasil=$this->db->query("SELECT * FROM db_academic.program_study_detail where ProdiID = '".$prodi_active_id."' ")->result_array();
        return $hasil;
    }

    function updateTableProdi($data_arr){
        $prodi_active_id = $this->session->userdata('prodi_active_id');

        $dataForm = (array) $data_arr['dataForm'];

        // Cek
        $dataCk = $this->db->get_where('db_academic.program_study_detail'
            ,array('ProdiID'=>$prodi_active_id))->result_array();

        if(count($dataCk)>0){
        // print_r($dataForm);die();
       // print_r($dataForm);die();
            $this->db->where('ProdiID', $prodi_active_id);
            $this->db->update('db_academic.program_study_detail',$dataForm);
        } else {
            $dataForm['ProdiID'] = $prodi_active_id;
            $this->db->insert('db_academic.program_study_detail',$dataForm);
        }

    }

    function getDataTestimoni(){ // db_prodi
        $prodi_active_id = $this->session->userdata('prodi_active_id');

        $hasil=$this->db->query("SELECT * FROM db_prodi.testimoni where ProdiID = '".$prodi_active_id."' ")->result_array();
        return $hasil;
    }

    function getDataSlider(){ // db_prodi
        $prodi_active_id = $this->session->userdata('prodi_active_id');

        $hasil = $this->db->query("SELECT * FROM db_prodi.slider where ProdiID = '".$prodi_active_id."' order by Sorting asc ")->result_array();
        for ($i=0; $i < count($hasil); $i++) { 
            $data = $hasil[$i];
            $token = $this->jwt->encode($data,"UAP)(*");
            $hasil[$i]['token'] = $token;
        }
        // print_r($hasil);die();
        return $hasil;
    }

    function updateDataProdi($data_arr){
        $prodi_active_id = $this->session->userdata('prodi_active_id');

        $dataForm = (array) $data_arr['dataForm'];

        // Cek
        $dataCk = $this->db->get_where('db_prodi.slider'
            ,array('ProdiID'=>$prodi_active_id))->result_array();

        if(count($dataCk)>0){
        // print_r($dataForm);die();
       // print_r($dataForm);die();
            $this->db->where('ProdiID', $prodi_active_id);
            $this->db->update('db_prodi.slider',$dataForm);
        } else {
            $dataForm['ProdiID'] = $prodi_active_id;
            $this->db->insert('db_prodi.slider',$dataForm);
        }
        
    }


    private function _get_datatables_query()
    {
        $prodi_active_id = $this->session->userdata('prodi_active_id');
        if($this->input->post('type')){            
            $getvaID= $this->input->post('type');  
            $data=$this->db->where('Type', $getvaID);
            // print_r($getvaID);die();
        }
        $this->db->select('pt.ID,pt.Title,l.Language,pt.UpdatedAt,ck.Name');
        $this->db->from('db_prodi.prodi_texting as pt'); 
        $this->db->join('db_prodi.language as l', 'pt.LangID = l.ID');
        $this->db->join('db_prodi.category_knowledge as ck', 'pt.ID_CatBase = ck.ID');
        $this->db->where('pt.ProdiID', $prodi_active_id);
        $this->db->order_by('ID','desc');
        // $this->db->order_by("ID", "desc");
        $i = 0;
        
        // if(!isset($_POST['category']))
        // {
        //     $this->db->join('db_prodi.category_knowledge', 'db_prodi.content.IDCat = db_prodi.category_knowledge.ID');

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
        // print_r($query->result());
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

    public function getCategory($getidlang)
    {
        // $prodi_active_id = $this->session->userdata('prodi_active_id');
        // $this->db->from("db_prodi.category_knowledge");
        // $this->db->where(array('Lang' => $getidlang,'ProdiID' => $prodi_active_id));
        // $this->db->order_by('ID','desc');
        // $q = $this->db->get();
        // return $q->result();
        $prodi_active_id = $this->session->userdata('prodi_active_id');
        $hasil=$this->db->query("SELECT * FROM db_prodi.category_knowledge where ProdiID = '".$prodi_active_id."' and Lang = '".$getidlang."' ")->result_array();
        return $hasil;
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('ID',$id);
        $query = $this->db->get();
 
        return $query->row();
    }
    
    public function get_by_idCat($id)
    {
        $this->db->from('db_prodi.category_knowledge');
        $this->db->where('ID',$id);
        $query = $this->db->get(); 
        return $query->row();
    }

    public function save($data)
    {   
        // $sql = 'select pt.* from db_prodi.prodi_texting AS pt
        //                     WHERE pt.Type="'.$type.'" ';
        // // print_r($sql);die;
        // $query=$this->db->query($sql, array())->result_array();
        // $data['Type'] = $query[0]['Type'];
         // print_r($data);die();
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function get_category(){
        $prodi_active_id = $this->session->userdata('prodi_active_id');
        $this->db->select('ck.*,l.Language ');
        $this->db->from("db_prodi.category_knowledge as ck");
        $this->db->join("db_prodi.language as l", "ck.Lang = l.ID");
        $this->db->where('ProdiID',$prodi_active_id);
        $this->db->order_by('ck.ID','desc');
        $q = $this->db->get();
        return $q->result();  
    }

    public function saveCat($data)
    {           
        $this->db->insert('db_prodi.category_knowledge', $data);
        return $this->db->insert_id();
    }
 
    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }
    
    public function updateCat($where, $data)
    {
        $this->db->update('db_prodi.category_knowledge', $data, $where);
        return $this->db->affected_rows();
    }
 

    public function delete_by_idCat($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete('db_prodi.category_knowledge');
    }

    public function delete_by_id($id)
    {
        $this->db->where('ID', $id);
        $this->db->delete($this->table);
    }

}
?>