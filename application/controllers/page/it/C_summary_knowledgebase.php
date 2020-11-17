<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_summary_knowledgebase extends It_Controler {
    public $data = array();
    public $subdata = array();

    function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement(); 

        $this->subdata['tbl_total_kb_per_divisi'] = [
            'columns' => [
                '0' => ['name' => 'No', 'title' => "No", 'class' => 'no-sort image', 'filter' => false],
                '1' => ['name' => 'NameDepartment', 'title' => 'Divisi',],
                '2' => ['name' => 'Countable', 'title' => 'Total', 'class' => 'default-sort', 'sort' => 'desc',],
            ],
        ];

        $this->subdata['total_top100_view_log_employees'] = [
            'columns' => [
                '0' => ['name' => 'No', 'title' => "No", 'class' => 'no-sort image', 'filter' => false],
                '1' => ['name' => 'Name', 'title' => 'Name',],
                '2' => ['name' => 'Countable', 'title' => 'Total', 'class' => 'default-sort', 'sort' => 'desc',],
            ],
        ];
    }

    public function index(){
    	$this->data['page_total_kb_per_divisi'] = $this->load->view('page/it/summary_knowledgebase/total_kb_per_divisi',$this->subdata,true);
    	$this->data['page_total_max_view_log_employees'] = $this->load->view('page/it/summary_knowledgebase/page_total_max_view_log_employees',$this->subdata,true);
    	$this->data['page_total_top10By_EMP'] = $this->load->view('page/it/summary_knowledgebase/page_total_top10By_EMP','',true);
    	$this->data['page_max_view_content_per_divisi'] = $this->load->view('page/it/summary_knowledgebase/page_max_view_content_per_divisi','',true);
    	$this->data['page_search_filter_by_employees'] = $this->load->view('page/it/summary_knowledgebase/page_search_filter_by_employees','',true);
    	$this->data['page_search_filter_by_content'] = $this->load->view('page/it/summary_knowledgebase/page_search_filter_by_content','',true);
    	
    	$content = $this->load->view('page/it/summary_knowledgebase/index',$this->data,true);
    	$this->temp($content);
    }

    public function get_total_kb_per_divisi(){
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $search = $this->input->post('search')['value'];
        $this->session->set_userdata('tbl_total_per_division', $search);
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order = $this->input->post('order')[0];
        $draw = intval($this->input->post('draw'));

        $this->load->model('it/summary_knowledgebase/m_total_kb_per_divisi_model');

        $datas= $this->m_total_kb_per_divisi_model->get_all($start, $length, $search, $order);
        $data_total =  $this->m_total_kb_per_divisi_model->get_total();
        $data_total_filtered =  $this->m_total_kb_per_divisi_model->get_total($search);
        $output['data'] = array();

        if ($datas) {
            $no = $start + 1;
            foreach ($datas->result() as $data) {
                $output['data'][] = array(
                    $no,
                    $data->NameDepartment,
                    $data->Countable,
                );

                $no++;
            }
        }

        $output['draw'] = $draw++;
        $output['recordsTotal'] = $data_total;
        $output['recordsFiltered'] = $data_total_filtered;
        echo json_encode($output);
    }

    public function chart_total_kb_per_divisi(){
        $this->load->model('it/summary_knowledgebase/m_total_kb_per_divisi_model');

        $data = $this->m_total_kb_per_divisi_model->chart();

        echo json_encode($data);
    }

    public function get_total_top100_view_log_employees(){
        $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $search = $this->input->post('search')['value'];
        $this->session->set_userdata('total_top100_view_log_employees', $search);
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order = $this->input->post('order')[0];
        $draw = intval($this->input->post('draw'));

        $this->load->model('it/summary_knowledgebase/m_total_top100_view_log_employees_model');

        $datas= $this->m_total_top100_view_log_employees_model->get_all($start, $length, $search, $order);
        $data_total =  $this->m_total_top100_view_log_employees_model->get_total();
        $data_total_filtered =  $this->m_total_top100_view_log_employees_model->get_total($search);
        $output['data'] = array();

        if ($datas) {
            $no = $start + 1;
            foreach ($datas->result() as $data) {
                $output['data'][] = array(
                    $no,
                    $data->Name,
                    $data->Countable,
                );

                $no++;
            }
        }

        $output['draw'] = $draw++;
        $output['recordsTotal'] = $data_total;
        $output['recordsFiltered'] = $data_total_filtered;
        echo json_encode($output);
    }

    public function pie_chart_total_top10By_EMP(){
          $this->input->is_ajax_request() or exit('No direct post submit allowed!');
          $this->load->model('it/summary_knowledgebase/m_total_top100_view_log_employees_model');
          $rs = [];
          $order = array ( 'column' => 2, 'dir' => 'desc' );
          $datas= $this->m_total_top100_view_log_employees_model->get_all(0, 5, '', $order);
          if ($datas) {
              foreach ($datas->result() as $data) {
                $rs[] = [
                    'label' => (strlen($data->Name) > 22) ? substr($data->Name, 0,22).'...' : $data->Name,
                    'data' => $data->Countable,
                ];
              }
          }

          echo json_encode($rs);
          
          
    }

}
