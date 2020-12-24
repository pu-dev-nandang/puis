<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_final_project extends Academic_Controler {

    function __construct()
    {
        parent::__construct();
//        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('m_rest');
    }


    public function temp($content)
    {
        parent::template($content);
    }

     public function monitoring_final_project(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/monitoring_final_project',$data,true);
        $this->menu_transcript($page);
    }


    public function loadFinalProject()
    {
        $datatoken =  $this->getInputToken();
        $datatoken = json_decode(json_encode($datatoken),true);

        if($datatoken['action']=='viewList'){

            $requestData= $_REQUEST;

            
            
            $dataWhere = ($datatoken['ProdiID']!='' && $datatoken['ProdiID']!=null)
                ? '( aut_s.StatusStudentID = "3" OR aut_s.StatusStudentID = "1" ) AND aut_s.ProdiID = "'.$datatoken['ProdiID'].'" '
                : '( aut_s.StatusStudentID = "3" OR aut_s.StatusStudentID = "1" ) ' ;

            $dataSemester = '';
            if( !empty($datatoken['Year'])) {
                $classofyear = $datatoken['Year'];
                $datatahun = 'AND ( aut_s.Year = '.$classofyear.')';
            }

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataSearch = 'AND ( aut_s.Name LIKE "%'.$search.'%" OR aut_s.NPM LIKE "%'.$search.'%"
                               OR ps.Name LIKE "%'.$search.'%"  OR ps.NameEng LIKE "%'.$search.'%" OR fp.TitleInd LIKE "%'.$search.'%" OR fp.TitleEng LIKE "%'.$search.'%" )';
            }

            $queryDefault = 'SELECT fp.*, aut_s.Name, ps.Name AS ProdiName, ps.NameEng AS ProdiNameEng FROM db_academic.final_project fp 
                                      LEFT JOIN db_academic.auth_students aut_s ON (fp.NPM = aut_s.NPM)
                                      LEFT JOIN db_academic.program_study ps ON (ps.ID = aut_s.ProdiID)
                                      WHERE ( '.$dataWhere.' ) '.$dataSearch.' '.$datatahun.' ORDER BY aut_s.NPM ASC ';

            $queryDefaultTotal = 'SELECT COUNT(*) AS Total FROM ('.$queryDefault.') xx';


            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefaultTotal)->result_array()[0]['Total'];

            $no = $requestData['start'] + 1;
            $data = array();

            for($i=0;$i<count($query);$i++) {

                $nestedData = array();
                $row = $query[$i];
                $tokenID = $this->jwt->encode(array('ID'=>$row['ID']),'UAP)(*');


                  $btnAct = ' <div class="btn-group">
                   <button class="btn btn-warning btn-sm" data-id="'.$row['ID'].'" data-tittleindo="'.$row['TitleInd'].'" data-tittleing="'.$row['TitleEng'].'" data-toggle="modal" data-target="#editdata" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                   </div>';

            
                    $getName = $this->db->query('SELECT Name FROM db_employees.employees WHERE NIP = '.$row['UpdatedBy'])->row_array();
                

                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div  style="text-align:left;">
                                    <b><i class="fa fa-user margin-right"></i> '.ucwords(strtolower($row['Name'])).'</b><br/>
                                        '.$row['NPM'].' | '.$row['ProdiNameEng'].'<br/>
                                        </div>';
                $nestedData[] = '<div style="text-align: left;">'.$row['TitleInd'].'</div>';
                $nestedData[] = '<div style="text-align: left;">'.$row['TitleEng'].'</div>';
                $nestedData[] = '<div style="text-align: left;"><b>'.ucwords(strtolower($getName['Name'])).'</b> <br>'.$row['UpdatedBy'].'</div>';
                $nestedData[] = '<div style="text-align: left;">'.$row['UpdatedAt'].'</div>';
                

                $nestedData[] = $btnAct;
                

                $data[] = $nestedData;
                $no++;
            }

            $json_data = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval($queryDefaultRow),
                "recordsFiltered" => intval($queryDefaultRow),
                "data"            => $data
            );
            echo json_encode($json_data);

        }

        else if($datatoken['action']=='editTittle'){
            $formData = $datatoken['datarequest'];

            $requestid = $formData['ID'];
            $requestInd = $formData['tittleIndo'];
            $requestIng = $formData['tittleIng'];
            $ActionBy = $this->session->userdata('NIP');
      
            $updates = array(
              'TitleInd' => $requestInd,
              'TitleEng' => $requestIng,
              'UpdatedAt' => date('Y-m-d H:i:s'),
              'UpdatedBy' => $ActionBy,
            );

            
            $this->db->where('ID', $requestid);
            $this->db->update('db_academic.final_project', $updates);

            $rs['status'] = 1;
            return print_r(json_encode($rs));

        }

            
    }

    public function menu_transcript($page){
        $data['department'] = parent::__getDepartement();
        $data['page'] = $page;
        $content = $this->load->view('page/'.$data['department'].'/finalproject/menu_finalproject',$data,true);
        parent::template($content);
    }

    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/finalproject_list_student',$data,true);
        $this->menu_transcript($page);
    }

    public function list_student(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/list_student',$data,true);
        $this->menu_transcript($page);
    }

    public function mentor_final_project(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/mentor_final_project',$data,true);
        $this->menu_transcript($page);
    }

    public function seminar_schedule(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/seminar_schedule',$data,true);
        $this->menu_transcript($page);
    }

    public function monitoring_yudisium(){
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/finalproject/monitoring_yudisium',$data,true);
        $this->menu_transcript($page);
    }

    public function setting_transcript(){
        $data['Transcript'] = $this->db->get('db_academic.setting_transcript')->result_array()[0];
        $data['Graduation'] = $this->db->get('db_academic.graduation')->result_array();
        $data['Education'] = $this->db->get('db_academic.education_level')->result_array();
        $data['department'] = parent::__getDepartement();
        $page = $this->load->view('page/'.$data['department'].'/transcript/setting_transcript',$data,true);
        $this->menu_transcript($page);
    }

    public function uploadIjazahStudent(){

        $fileName = $this->input->get('fileName');
        $old = $this->input->get('old');
        $NPM = $this->input->get('NPM');

        $pathStudent = './uploads/document/'.$NPM;
        if (!file_exists($pathStudent)) {
            mkdir($pathStudent, 0777, true);
        }

        $config['upload_path']          = $pathStudent.'/';
        $config['allowed_types']        = '*';
        $config['max_size']             = 8000; // 8 mb
        $config['file_name']            = $fileName;

        if($old!='' && is_file($pathStudent.'/'.$old)){
            unlink($pathStudent.'/'.$old);
        }

        if ($_SERVER['SERVER_NAME'] == 'pcam.podomorouniversity.ac.id') {
            // upload to nas
            $headerOrigin = ($_SERVER['SERVER_NAME'] == 'localhost') ? "http://localhost" : serverRoot;
            $path = 'document/'.$NPM;
            $TheFile = 'userfile';
            $uploadNas = $this->m_master->UploadOneFilesToNas($headerOrigin,$fileName,$TheFile,$path,'string');

            // Cek apakah sudah ada atau blm
            $arrWhere = array(
                'NPM' => $NPM,
                'ID_reg_doc_checklist' => 3
            );
            $dataCK = $this->db->get_where('db_admission.doc_mhs',$arrWhere)->result_array();
            $dataInsrt = array(
                'NPM' => $NPM,
                'ID_reg_doc_checklist' => 3,
                'Status' => 'Done',
                'Description' => 'Ijazah / SKHUN SMA from Academic',
                'VerificationBy' => $this->session->userdata('NIP'),
                'VerificationAT' => $this->m_rest->getDateTimeNow(),
                'Attachment' => $fileName
            );

            print_r($dataInsrt);
            print_r($dataCK);

            if(count($dataCK)>0){
                $this->db->where('ID' , $dataCK[0]['ID']);
                $this->db->update('db_admission.doc_mhs',$dataInsrt);
            } else {
                $this->db->insert('db_admission.doc_mhs',$dataInsrt);
            }

            return print_r(1);
        }
        else
        {
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('userfile')){
                // Error
                $error = array('error' => $this->upload->display_errors());
                return print_r(json_encode($error));
            }
            else {
                // Sukses

                // Cek apakah sudah ada atau blm
                $arrWhere = array(
                    'NPM' => $NPM,
                    'ID_reg_doc_checklist' => 3
                );
                $dataCK = $this->db->get_where('db_admission.doc_mhs',$arrWhere)->result_array();
                $dataInsrt = array(
                    'NPM' => $NPM,
                    'ID_reg_doc_checklist' => 3,
                    'Status' => 'Done',
                    'Description' => 'Ijazah / SKHUN SMA from Academic',
                    'VerificationBy' => $this->session->userdata('NIP'),
                    'VerificationAT' => $this->m_rest->getDateTimeNow(),
                    'Attachment' => $fileName
                );

                print_r($dataInsrt);
                print_r($dataCK);

                if(count($dataCK)>0){
                    $this->db->where('ID' , $dataCK[0]['ID']);
                    $this->db->update('db_admission.doc_mhs',$dataInsrt);
                } else {
                    $this->db->insert('db_admission.doc_mhs',$dataInsrt);
                }

                return print_r(1);
            }
        }
       
    }

    public function scheduling_final_project(){

        $ID = $this->input->get('id');

        $data['department'] = parent::__getDepartement();
        $data['ID'] = ($ID!='') ? $ID : '';

        $DataEdit = [];
        if($ID!=''){
            $DataEdit = $this->db->query('SELECT fpc.*, cl.Room, cl.Seat, cl.SeatForExam FROM db_academic.final_project_schedule fpc 
                                                    LEFT JOIN db_academic.classroom cl ON (cl.ID = fpc.ClassroomID)
                                                    WHERE fpc.ID = "'.$ID.'" ')->result_array();

            if(count($DataEdit)>0){
                for($i=0;$i<count($DataEdit);$i++){
                    // Get Std
                    $DataEdit[$i]['Student'] = $this->db->query('SELECT sp.*, fp.Status, ats.Name, em1.Name AS  MentorFP1_Name,  
                                                         em2.Name AS  MentorFP2_Name FROM db_academic.final_project_schedule_student sp
                                                        LEFT JOIN db_academic.final_project fp ON (fp.NPM = sp.NPM)
                                                        LEFT JOIN db_academic.auth_students ats ON (ats.NPM = sp.NPM)
                                                        LEFT JOIN db_employees.employees em1 ON (em1.NIP = ats.MentorFP1)
                                                        LEFT JOIN db_employees.employees em2 ON (em2.NIP = ats.MentorFP2)
                                                        WHERE sp.FPSID = "'.$DataEdit[$i]['ID'].'" ')->result_array();

                    $DataEdit[$i]['Examiner'] = $this->db->query('SELECT sp.*, em.Name  FROM db_academic.final_project_schedule_lecturer sp 
                                                        LEFT JOIN db_employees.employees em ON (em.NIP = sp.NIP)
                                                        WHERE sp.FPSID = "'.$DataEdit[$i]['ID'].'" ')->result_array();
                }
            }


        }

        $data['DataEdit'] = json_encode($DataEdit);
        $data['ID'] = (count($DataEdit)>0) ? $ID : '';

        $page = $this->load->view('page/'.$data['department'].'/finalproject/scheduling_final_project',$data,true);
        $this->menu_transcript($page);
    }

}
