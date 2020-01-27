<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_document_generator extends It_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_sendemail');
        $this->data['department'] = parent::__getDepartement(); 
    }

    public function menu_developer($page){
        $data['page'] = $page;
        $content = $this->load->view('page/it/console-developer/menu_developer',$data,true);
        $this->temp($content);
    }

    private function temp_index($content){
      $template = $this->load->view('page/'.$this->data['department'].'/console-developer/document-generator/index',$content,true);
      $this->menu_developer($template);
    }
    
    public function privileges()
    {
      $url = url_pas.'api/__getAllDepartementPU';
      $this->data['Arr_DepartmetnPU'] = $this->m_master->apiservertoserver($url);
      $content['page'] =  $this->load->view('page/'.$this->data['department'].'/console-developer/document-generator/privileges',$this->data,true);
      $this->temp_index($content);
    }

    public function api_table(){
      $content['page'] =  $this->load->view('page/'.$this->data['department'].'/console-developer/document-generator/api_table',$this->data,true);
      $this->temp_index($content);
    }

}
