<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_lppm extends Lppm_Controler {

    function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->model('lppm/m_lppm');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_content(){
        // $data['pages'] = $pages;
        $data['department'] = parent::__getDepartement();
        $data['category'] = $this->m_lppm->get_category();
        $content = $this->load->view('page/lppm/content/menu_content',$data,true);
        $this->temp($content);
    }

    ## table load /list

    public function ajax_list()
    {
        $type=$this->uri->segment(2);
        $list = $this->m_lppm->get_datatables($type);
        
        $data = array();        
        $no = $_POST['start'];
        foreach ($list as $m) {
            $no++;
            $row = array();
            $row[] = $m->Title;
            
            // $row[] = $m->Description;
            $row[] = $m->Status;
            $row[] = $m->UpdatedAt;
            $row[] = $m->Lang;
            
 
            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_lppm('."'".$m->ID."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_lppm('."'".$m->ID."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->m_lppm->count_all(),
                        "recordsFiltered" => $this->m_lppm->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->m_lppm->get_by_id($id);
        // print_r($data);die();
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $this->_validate();
        $data = array(
                // 'IDType' => $this->input->post('type'),
                'Title' => $this->input->post('title'),
                'IDSubCat' => $this->input->post('idsubcategory'),
                'Description' => $this->input->post('description'),
                'Meta_description' => $this->input->post('meta_des'),
                'Meta_keywords' => $this->input->post('meta_key'),
                'Lang' => $this->input->post('lang'),
                'AddDate' => $this->input->post('date'),
                'Status' => $this->input->post('status'),
                'CreateAt' => date('Y-m-d H:i:s'),
                'CreateBy' => $this->session->userdata('NIP'),
            );
        // print_r($data);
        if(!empty($_FILES['photo']['name']))
        {
            $upload = $this->_do_upload();
            $data['File'] = $upload;
        }
        $type = $this->input->post('type');
        $insert = $this->m_lppm->save($data,$type);
        echo json_encode(array("status" => TRUE));
    }


    // Category

    public  function list_category(){
        $data=$this->m_lppm->get_category();
        echo json_encode($data);
    }

    public function ajax_addCat()
    {
        // $this->_validate();
        $data = array(                
                'Name' => $this->input->post('category'),
                'Lang' => $this->input->post('lang'),
                'CreateAt' => date('Y-m-d H:i:s'),
                'CreateBy' => $this->session->userdata('NIP'),
            );
        
        // print_r($data);die();
        $insert = $this->m_lppm->saveCat($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_editCat($id)
    {
        $data = $this->m_lppm->get_by_idCat($id);
        echo json_encode($data);
    }

    public function ajax_updateCat()
    {
        // $this->_validate();
        $data = array(                
                'Name' => $this->input->post('category'),
                'Lang' => $this->input->post('lang'),
                'CreateAt' => date('Y-m-d H:i:s'),
                'CreateBy' => $this->session->userdata('NIP'),
            );
        
        // print_r($data);die();
        $insert = $this->m_lppm->updateCat(array('ID' => $this->input->post('idcat')),$data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_deleteCat($id)
    {
        $this->m_lppm->delete_by_idCat($id);
        echo json_encode(array("status" => TRUE));
    }


    // Sub Category
    public function getCatByLang(){

        $getidlang =  $this->input->post('idlang');
        // print_r($getidlang);
        $q = $this->m_lppm->getCategory($getidlang);        
        echo json_encode($q);  
    }


    public function getSubCat(){

        $getidcat =  $this->input->post('idcat');
        $getidsubcat =  $this->input->post('idsubcat');
        // print_r($getidcat);
        $q = $this->m_lppm->getSubCategory($getidcat,$getidsubcat);        
        echo json_encode($q);  
    }


    public  function list_Subcategory(){
        $data=$this->m_lppm->get_Subcategory();
        echo json_encode($data);
    }

    public function ajax_addSubCat()
    {
        // $this->_validate();
        $data = array(  
                'IDCat' => $this->input->post('idcategory'),
                'SubName' => $this->input->post('subcategoryname'),
                'CreateAt' => date('Y-m-d H:i:s'),
                'CreateBy' => $this->session->userdata('NIP'),
            );
        
        // print_r($data);die();
        $insert = $this->m_lppm->saveSubCat($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_editSubCat($id)
    {
        $data = $this->m_lppm->get_by_idSubCat($id);
        echo json_encode($data);
    }

    public function ajax_updateSubCat()
    {
        // $this->_validate();
        $data = array(                
                'IDCat' => $this->input->post('idcategory'),
                'SubName' => $this->input->post('subcategoryname'),
                'CreateAt' => date('Y-m-d H:i:s'),
                'CreateBy' => $this->session->userdata('NIP'),
            );
        
        // print_r($data);die();
        $insert = $this->m_lppm->updateSubCat(array('IDSub' => $this->input->post('idSubcat')),$data);
        // print_r($insert);die();
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_deleteSubCat($id)
    {
        $this->m_lppm->delete_by_idSubCat($id);
        echo json_encode(array("status" => TRUE));
    }

    // Content 
    public function ajax_update()
    {
        $this->_validate();
        $data = array(
                'Title' => $this->input->post('title'),
                'IDSubCat' => $this->input->post('category'),
                'Description' => $this->input->post('description'),
                'Meta_description' => $this->input->post('meta_des'),
                'Meta_keywords' => $this->input->post('meta_key'),
                'Lang' => $this->input->post('lang'),
                'AddDate' => $this->input->post('date'),
                'Status' => $this->input->post('status'),
                'UpdatedAt' => date('Y-m-d H:i:s'),
                'UpdatedBy' => $this->session->userdata('NIP'),
            );

        if(!empty($_FILES['photo']['name']))
        {
            $upload = $this->_do_upload();
             
            //delete file
            $lppm = $this->m_lppm->get_by_id($this->input->post('id'));
            if(file_exists('./uploads/lppm/'.$lppm->File) && $lppm->File)
                unlink('./uploads/lppm/'.$lppm->File);
 
            $data['File'] = $upload;
        }

        $this->m_lppm->update(array('ID' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_delete($id)
    {
        //delete file
        $lppm = $this->m_lppm->get_by_id($id);
        if(file_exists('./uploads/lppm/'.$lppm->File) && $lppm->File)
            unlink('./uploads/lppm/'.$lppm->File);

        $this->m_lppm->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _do_upload()
    {
        $config['upload_path']          = './uploads/lppm';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|pdf';
        $config['max_size']             = 2048000; //set max size allowed in Kilobyte 2mb
        // $config['max_width']            = 1000; // set max width image allowed
        // $config['max_height']           = 1000; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name
 
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
            $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        return $this->upload->data('file_name');
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('title') == '')
        {
            $data['inputerror'][] = 'title';
            $data['error_string'][] = 'Title is required';
            $data['status'] = FALSE;
        }
 
        // if($this->input->post('description') == '')
        // {
        //     $data['inputerror'][] = 'description';
        //     $data['error_string'][] = 'Description is required';
        //     $data['status'] = FALSE;
        // }
 
        if($this->input->post('status') == '')
        {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Status is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('lang') == '')
        {
            $data['inputerror'][] = 'lang';
            $data['error_string'][] = 'Please select language';
            $data['status'] = FALSE;
        }
        
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }


 
}
    

