<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_transfer_student extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->load->model('akademik/m_tahun_akademik');
    }


    public function temp($content)
    {
        parent::template($content);
    }

    public function menu_transfer_studnet($page){
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$data['department'].'/transfer_student/menu_transfer_student',$data,true);
        parent::template($content);
    }

    public function transfer_prodi()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/transfer_student/transfer_prodi',$data,true);
        $this->menu_transfer_studnet($page);
    }

    public function course_conversion($TSID){
        $data['department'] = parent::__getDepartement();
        $data['TSID'] = $TSID;
        $page = $this->load->view('page/'.$data['department'].'/transfer_student/course_conversion',$data,true);
        $this->menu_transfer_studnet($page);
    }

    public function loadListTransferStudent(){
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        $requestData= $_REQUEST;

        $dataSearch = '';
        if( !empty($requestData['search']['value']) ) {
            $search = $requestData['search']['value'];
            $dataSearch = 'WHERE aut_s_b.Name LIKE "%'.$search.'%" 
                                OR ts.Before LIKE "%'.$search.'%" 
                                OR ts.After LIKE "%'.$search.'%"
                                OR ts.ClassOfBefore LIKE "%'.$search.'%"
                                OR ts.ClassOfAfter LIKE "%'.$search.'%"
                                OR ps_b.Name LIKE "%'.$search.'%" 
                                OR ps_b.NameEng LIKE "%'.$search.'%" 
                                OR ps_a.Name LIKE "%'.$search.'%" 
                                OR ps_b.NameEng LIKE "%'.$search.'%"';
        }

        $queryDefault = 'SELECT ts.ID AS TSID, aut_s_b.Name AS StudentName, ts.Before, ts.After, ts.Note, ts.NotedAt, em.Name AS NotedBY, aut_s_b.Year AS B_Year, aut_s_a.Year AS A_Year,  
                                      ps_b.Name AS B_ProdiName, ps_b.NameEng AS B_ProdiNameEng,
                                      ps_a.Name AS A_ProdiName, ps_a.NameEng AS A_ProdiNameEng
                                      FROM db_academic.transfer_student ts
                                      LEFT JOIN db_academic.auth_students aut_s_b ON (aut_s_b.NPM = ts.Before)
                                      LEFT JOIN db_academic.program_study ps_b ON (ps_b.ID = aut_s_b.ProdiID)
                                      LEFT JOIN db_academic.auth_students aut_s_a ON (aut_s_a.NPM = ts.After)
                                      LEFT JOIN db_academic.program_study ps_a ON (ps_a.ID = aut_s_a.ProdiID)
                                      LEFT JOIN db_employees.employees em on (em.NIP = ts.NotedBy)
                                      '.$dataSearch.' ORDER BY ts.ID DESC ';

        $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

        $query = $this->db->query($sql)->result_array();
        $queryDefaultRow = $this->db->query($queryDefault)->result_array();

        $no = $requestData['start'] + 1;
        $data = array();

        for($i=0;$i<count($query);$i++) {
            $nestedData = array();
            $row = $query[$i];


            $btnAction = '<div class="btn-group">
                          <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-pencil"></i> <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="'.base_url('academic/transfer-student/course-conversion/'.$row['TSID']).'">Course Conversion</a></li>
                            <li><a href="javascript:void(0);" class="showModalNote" data-note="'.$row['Note'].'" data-name="'.$row['StudentName'].'" data-id="'.$row['TSID'].'">Create / Update Note</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="javascript:void(0)" class="btnRemoveData" data-id="'.$row['TSID'].'">Remove</a></li>
                          </ul>
                        </div>';

            $noted = ($row['Note']!='' && $row['Note']!=null)
                ?
                '<div><hr style="margin-top: 5px; margin-bottom: 5px;"/><button disabled class="btn btn-sm btn-warning btnNote" data-toggle="popover" data-trigger="focus" data-placement="bottom" title="By : '.$row['NotedBY'].' | '.date('D, d M Y',strtotime($row['NotedAt'])).'" data-content="'.$row['Note'].'"><i class="fa fa-refresh fa-spin fa-fw"></i></button></div>'
                : '';

            $nestedData[] = '<div style="text-align:center;">'.$no.'</div>';
            $nestedData[] = '<div>'.$row['StudentName'].$noted.'</div>';
            $nestedData[] = '<div style="text-align:center;">'.$row['Before'].'</div>';
            $nestedData[] = '<div style="text-align:center;">'.$row['B_Year'].'</div>';
            $nestedData[] = '<div><b>'.$row['B_ProdiNameEng'].'</b><br/><i>'.$row['B_ProdiName'].'</i></div>';
            $nestedData[] = '<div style="text-align:center;">'.$btnAction.'</div>';
            $nestedData[] = '<div style="text-align:center;">'.$row['After'].'</div>';
            $nestedData[] = '<div style="text-align:center;">'.$row['A_Year'].'</div>';
            $nestedData[] = '<div><b>'.$row['A_ProdiNameEng'].'</b><br/><i>'.$row['A_ProdiName'].'</i></div>';

            $no++;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval(count($queryDefaultRow)),
            "recordsFiltered" => intval( count($queryDefaultRow) ),
            "data"            => $data
        );

        echo json_encode($json_data);


    }


}
