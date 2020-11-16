<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_summary_knowledgebase extends It_Controler {
    public $data = array();
    function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement(); 
    }

    public function index(){
    	$this->data['page_total_kb_per_divisi'] = $this->load->view('page/it/summary_knowledgebase/total_kb_per_divisi','',true);
    	$this->data['page_total_max_view_log_employees'] = $this->load->view('page/it/summary_knowledgebase/page_total_max_view_log_employees','',true);
    	$this->data['page_total_max_view_log_per_divisi'] = $this->load->view('page/it/summary_knowledgebase/page_total_max_view_log_per_divisi','',true);
    	$this->data['page_max_view_content_per_divisi'] = $this->load->view('page/it/summary_knowledgebase/page_max_view_content_per_divisi','',true);
    	$this->data['page_search_filter_by_employees'] = $this->load->view('page/it/summary_knowledgebase/page_search_filter_by_employees','',true);
    	$this->data['page_search_filter_by_content'] = $this->load->view('page/it/summary_knowledgebase/page_search_filter_by_content','',true);
    	
    	$content = $this->load->view('page/it/summary_knowledgebase/index',$this->data,true);
    	$this->temp($content);
    }

}
