<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_api3 extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->load->model('m_api');
        $this->load->model('m_rest');
        $this->load->model('akademik/m_tahun_akademik');
        $this->load->model('master/m_master');
        $this->load->library('JWT');
        $this->load->library('google');
    }

    private function getInputToken()
    {
        $token = $this->input->post('token');
        $key = "s3Cr3T-G4N";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    private function getInputToken2()
    {
        $token = $this->input->post('token');
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        return $data_arr;
    }

    public function is_url_exist($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code == 200){
            $status = true;
        }else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    public function getListMenuAgregator(){

        $data = $this->db->order_by('ID','ASC')->get('db_agregator.agregator_menu')->result_array();

        return print_r(json_encode($data));

    }

    public function crudTeamAgregagor(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='insertTeamAggr'){

            $dataForm = (array) $data_arr['dataForm'];
            $this->db->insert('db_agregator.agregator_user',$dataForm);
            $insert_id = $this->db->insert_id();

            $Member = (array) $data_arr['Member'];
            if(count($Member)>0){
                for($i=0;$i<count($Member);$i++){

                    // Cek apakah NIP sudah ada atau blm
                    $dataCk = $this->db->get_where('db_agregator.agregator_user_member',array(
                        'NIP' => $Member[$i]
                    ))->result_array();

                    if(count($dataCk)<=0){
                        $arr = array(
                            'AUPID' => $insert_id,
                            'NIP' => $Member[$i]
                        );
                        $this->db->insert('db_agregator.agregator_user_member',$arr);
                    }


                }
            }

            return print_r(1);


        }
        else if($data_arr['action']=='updateTeamAggr'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            $this->db->where('ID', $ID);
            $this->db->update('db_agregator.agregator_user',$dataForm);
            $this->db->reset_query();


            $this->db->where('AUPID', $ID);
            $this->db->delete('db_agregator.agregator_user_member');
            $this->db->reset_query();

            $Member = (array) $data_arr['Member'];
            if(count($Member)>0){
                for($i=0;$i<count($Member);$i++){
                    // Cek apakah NIP sudah ada atau blm
                    $dataCk = $this->db->get_where('db_agregator.agregator_user_member',array(
                        'NIP' => $Member[$i]
                    ))->result_array();

                    if(count($dataCk)<=0){
                        $arr = array(
                            'AUPID' => $ID,
                            'NIP' => $Member[$i]
                        );
                        $this->db->insert('db_agregator.agregator_user_member',$arr);
                    }

                }
            }

            return print_r(1);

        }
        else if($data_arr['action']=='readTeamAggr'){

            $data = $this->db->get('db_agregator.agregator_user')->result_array();

            for($i=0;$i<count($data);$i++){

                // Get Menu Name
                $ArrMenu = json_decode($data[$i]['Menu']);

                $listMenu = [];
                for($m=0;$m<count($ArrMenu);$m++){

                    $dtm = $this->db->get_where('db_agregator.agregator_menu',array(
                        'ID' => $ArrMenu[$m]
                    ))->result_array();

                    if(count($dtm)>0){
                        array_push($listMenu,$dtm[0]);
                    }
                }

                $data[$i]['Member'] = $this->db->query('SELECT aum.*, em.Name FROM db_agregator.agregator_user_member aum 
                                                            LEFT JOIN db_employees.employees em ON (em.NIP = aum.NIP)
                                                            WHERE aum.AUPID = "'.$data[$i]['ID'].'" ')->result_array();

                $data[$i]['DetailMenu'] = $listMenu;

            }



            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='removeTeamAggr'){
            $ID = $data_arr['ID'];

            $this->db->where('AUPID', $ID);
            $this->db->delete('db_agregator.agregator_user_member');
            $this->db->reset_query();

            $this->db->where('ID', $ID);
            $this->db->delete('db_agregator.agregator_user');

            return print_r(1);

        }

    }

    public function crudLembagaSurview(){
        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='readLembagaSurview'){
            $data = $this->db->get('db_agregator.lembaga_surview')->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='readLembagaAudit'){
            $data = $this->db->get('db_agregator.lembaga_audit')->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='updateLembagaSurview'){

            $dataForm = array(
                'Lembaga' => $data_arr['Lembaga'],
                'Description' => $data_arr['Description']
            );

            if($data_arr['ID']!=''){
                // Update
                $this->db->where('ID',$data_arr['ID']);
                $this->db->update('db_agregator.lembaga_surview',$dataForm);
            } else {
                // Insert
                $this->db->insert('db_agregator.lembaga_surview',$dataForm);
            }

            return print_r(1);

        }
        else if($data_arr['action']=='updateLembagaAudit'){
            $dataForm = array(
                'Lembaga' => $data_arr['Lembaga'],
                'Description' => $data_arr['Description']
            );

            if($data_arr['ID']!=''){
                // Update
                $this->db->where('ID',$data_arr['ID']);
                $this->db->update('db_agregator.lembaga_audit',$dataForm);
            } else {
                // Insert
                $this->db->insert('db_agregator.lembaga_audit',$dataForm);
            }

            return print_r(1);
        }

    }

    public function crudExternalAccreditation(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='updateNewAE'){

            $dataForm = (array) $data_arr['dataForm'];

            if($data_arr['ID']!=''){
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID',$data_arr['ID']);
                $this->db->update('db_agregator.external_accreditation',$dataForm);
            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_agregator.external_accreditation',$dataForm);
            }

            return print_r(1);
        }
        else if($data_arr['action']=='viewListAE'){

            $requestData= $_REQUEST;

            $Previlege = $data_arr['Previlege'];

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataSearch = ' WHERE  ls.Lembaga LIKE "%'.$search.'%" 
            OR ea.Type LIKE "%'.$search.'%"
             OR ea.Scope LIKE "%'.$search.'%"
              OR ea.Description LIKE "%'.$search.'%"  ';
            }

            $queryDefault = 'SELECT ea.*, ls.Lembaga FROM db_agregator.external_accreditation ea 
                                        LEFT JOIN db_agregator.lembaga_surview ls ON (ls.ID = ea.LembagaID) '.$dataSearch;

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefault)->result_array();

            $no = $requestData['start'] + 1;
            $data = array();

            for($i=0;$i<count($query);$i++){

                $nestedData=array();
                $row = $query[$i];

                $btnAction = ($Previlege=='1' || $Previlege==1) ? '
                                                                       <div class="btn-group btnAction">
                                                                      <button type="button" class="btn btn-sm btn-default dropdown-toggle dropdown-menu-left" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-pencil"></i> <span class="caret"></span>
                                                                      </button>
                                                                      <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0);" class="btnEditAE" data-no="'.$no.'">Edit</a></li>
                                                                        <li role="separator" class="divider"></li>
                                                                        <li><a href="javascript:void(0);" class="btnRemove" data-id="'.$row['ID'].'" data-tb="db_agregator.external_accreditation">Remove</a></li>
                                                                      </ul>
                                                                    </div>
                                                                        <textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                $nestedData[] = '<div style="text-align:center;">'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Lembaga'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Type'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Scope'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Level'].'</div>';
                $nestedData[] = '<div style="text-align:right;">'.date('d M Y',strtotime($row['DueDate'])).'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$btnAction.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Description'].'</div>';

                $data[] = $nestedData;
                $no++;

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

    public function crudInternationalAccreditation(){

        $data_arr = $this->getInputToken2();
        if($data_arr['action']=='updateIAP'){

            $dataForm = (array) $data_arr['dataForm'];
            if($data_arr['ID']!=''){
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID',$data_arr['ID']);
                $this->db->update('db_agregator.international_accreditation_prodi',$dataForm);
            } else {
                $this->db->insert('db_agregator.international_accreditation_prodi',$dataForm);
            }

            return print_r(1);

        } else if($data_arr['action']=='viewListIA'){

            $requestData= $_REQUEST;
            $Previlege = $data_arr['Previlege'];

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataSearch = ' WHERE  ls.Lembaga LIKE "%'.$search.'%" 
            OR ea.Type LIKE "%'.$search.'%"
             OR ea.Scope LIKE "%'.$search.'%"
              OR ea.Description LIKE "%'.$search.'%"  ';
            }

            $queryDefault = 'SELECT iap.*, ls.Lembaga, ps.Name AS ProdiName, ps.Code AS ProdiCode FROM db_agregator.international_accreditation_prodi iap 
                                        LEFT JOIN db_agregator.lembaga_surview ls ON (ls.ID = iap.LembagaID)
                                        LEFT JOIN db_academic.program_study ps ON (ps.ID = iap.ProdiID)
                                         '.$dataSearch;

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefault)->result_array();

            $no = $requestData['start'] + 1;
            $data = array();

            for($i=0;$i<count($query);$i++){

                $nestedData=array();
                $row = $query[$i];

//                $btnAction = ($Previlege=='1' || $Previlege==1) ? '<button class="btn btn-default btn-sm btnEdit" data-no="'.$no.'"><i class="fa fa-edit"></i></button><textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                $btnAction = ($Previlege=='1' || $Previlege==1) ? '
                                                                       <div class="btn-group btnAction">
                                                                      <button type="button" class="btn btn-sm btn-default dropdown-toggle dropdown-menu-left" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-pencil"></i> <span class="caret"></span>
                                                                      </button>
                                                                      <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0);" class="btnEdit" data-no="'.$no.'">Edit</a></li>
                                                                        <li role="separator" class="divider"></li>
                                                                        <li><a href="javascript:void(0);" class="btnRemove" data-id="'.$row['ID'].'" data-tb="db_agregator.international_accreditation_prodi">Remove</a></li>
                                                                      </ul>
                                                                    </div>
                                                                        <textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                $nestedData[] = '<div style="text-align:center;">'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Lembaga'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['ProdiName'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Status'].'</div>';
                $nestedData[] = '<div style="text-align:right;">'.date('d M Y',strtotime($row['DueDate'])).'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$btnAction.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Description'].'</div>';

                $data[] = $nestedData;
                $no++;

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

    public function crudAgregatorTB1(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='crudFEA'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            // Update
            if($ID!=''){
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['updatedAt'] = $this->m_rest->getDateTimeNow();

                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.financial_external_audit',$dataForm);

            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_agregator.financial_external_audit',$dataForm);
            }

            return print_r(1);

        }
        else if($data_arr['action']=='removeDataAgg'){


            $this->db->where('ID', $data_arr['ID']);
            $this->db->delete($data_arr['Table']);

            // Remove File
            if(isset($data_arr['File']) && $data_arr['File']!=''
                && is_file('./uploads/agregator/'.$data_arr['File'])){
                unlink('./uploads/agregator/'.$data_arr['File']);
            }

            return print_r(1);

        }
        else if($data_arr['action']=='viewListAKE'){

            $requestData= $_REQUEST;

            $Previlege = $data_arr['Previlege'];

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataSearch = ' WHERE  fae.Lembaga LIKE "%'.$search.'%" 
            OR fae.Year LIKE "%'.$search.'%"
             OR fae.Opinion LIKE "%'.$search.'%"
              OR fae.Description LIKE "%'.$search.'%"  ';
            }

            $queryDefault = 'SELECT fae.*, lu.Lembaga FROM db_agregator.financial_external_audit fae 
                                        LEFT JOIN db_agregator.lembaga_audit lu ON (lu.ID = fae.LembagaAuditID) '.$dataSearch;

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefault)->result_array();

            $no = $requestData['start'] + 1;
            $data = array();
            for($i=0;$i<count($query);$i++){

                $nestedData=array();
                $row = $query[$i];

//                $btnAction = ($Previlege=='1' || $Previlege==1) ? '<button class="btn btn-default btn-sm btnEditAE" data-no="'.$no.'"><i class="fa fa-edit"></i></button><textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                $btnAction = ($Previlege=='1' || $Previlege==1) ? '
                                                                       <div class="btn-group btnAction">
                                                                      <button type="button" class="btn btn-sm btn-default dropdown-toggle dropdown-menu-left" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-pencil"></i> <span class="caret"></span>
                                                                      </button>
                                                                      <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0);" class="btnEditAE" data-no="'.$no.'">Edit</a></li>
                                                                        <li role="separator" class="divider"></li>
                                                                        <li><a href="javascript:void(0);" class="btnRemove" data-id="'.$row['ID'].'" data-tb="db_agregator.financial_external_audit">Remove</a></li>
                                                                      </ul>
                                                                    </div>
                                                                        <textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                $nestedData[] = '<div style="text-align:center;">'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Lembaga'].'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$row['Year'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Opinion'].'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$btnAction.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Description'].'</div>';

                $data[] = $nestedData;
                $no++;

            }

            $json_data = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval(count($queryDefaultRow)),
                "recordsFiltered" => intval( count($queryDefaultRow) ),
                "data"            => $data
            );
            echo json_encode($json_data);

        }
        else if($data_arr['action']=='updateLembagaMitraKerjasama'){

            $dataForm = array(
                'Lembaga' => $data_arr['Lembaga'],
                'Description' => $data_arr['Description']
            );

            if($data_arr['ID']!=''){
                // Update
                $this->db->where('ID',$data_arr['ID']);
                $this->db->update('db_agregator.lembaga_mitra_kerjasama',$dataForm);
            } else {
                // Insert
                $this->db->insert('db_agregator.lembaga_mitra_kerjasama',$dataForm);
            }

            return print_r(1);

        }
        else if($data_arr['action']=='readLembagaMitraKerjasama'){
            $data = $this->db->get('db_agregator.lembaga_mitra_kerjasama')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='crudKPT'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            $FileName ='';

            if($ID!=''){
                // Update
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.university_collaboration',$dataForm);

                $dataFileName = $this->db->select('File')->get_where('db_agregator.university_collaboration',
                    array(
                       'ID' => $ID
                    ))->result_array();

                $FileName = (count($dataFileName)>0) ? $dataFileName[0]['File'] : '';
            } else {
                // Insert
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_agregator.university_collaboration',$dataForm);
                $ID = $this->db->insert_id();
            }

            return print_r(json_encode(array(
                'ID' => $ID,
                'FileName' => $FileName
            )));

        }
        else if($data_arr['action']=='viewListKPT'){

            $requestData= $_REQUEST;

            $Previlege = $data_arr['Previlege'];

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataSearch = ' WHERE  lmk.Lembaga LIKE "%'.$search.'%" 
            OR uc.Tingkat LIKE "%'.$search.'%"
             OR uc.Benefit LIKE "%'.$search.'%"';
            }

            $queryDefault = 'SELECT uc.*, lmk.Lembaga FROM db_agregator.university_collaboration uc 
                                        LEFT JOIN db_agregator.lembaga_mitra_kerjasama lmk ON (lmk.ID = uc.LembagaMitraID) '.$dataSearch;

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefault)->result_array();

            $no = $requestData['start'] + 1;
            $data = array();
            for($i=0;$i<count($query);$i++){

                $nestedData=array();
                $row = $query[$i];

//                $btnAction = ($Previlege=='1' || $Previlege==1) ? '<button class="btn btn-default btn-sm btnEditAE" data-no="'.$no.'"><i class="fa fa-edit"></i></button><textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                $btnAction = ($Previlege=='1' || $Previlege==1) ? '
                                                                       <div class="btn-group btnAction">
                                                                      <button type="button" class="btn btn-sm btn-default dropdown-toggle dropdown-menu-left" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-pencil"></i> <span class="caret"></span>
                                                                      </button>
                                                                      <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0);" class="btnEditAE" data-no="'.$no.'">Edit</a></li>
                                                                        <li role="separator" class="divider"></li>
                                                                        <li><a href="javascript:void(0);" class="btnRemove" data-id="'.$row['ID'].'" data-file="'.$row['File'].'" data-tb="db_agregator.university_collaboration">Remove</a></li>
                                                                      </ul>
                                                                    </div>
                                                                        <textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                $nestedData[] = '<div style="text-align:center;">'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Lembaga'].'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$row['Tingkat'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Benefit'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.date('d M Y',strtotime($row['DueDate'])).'</div>';
                $nestedData[] = '<div style="text-align:left;"><a target="_blank" href="'.base_url('uploads/agregator/'.$row['File']).'">Download Bukti</a></div>';
                $nestedData[] = '<div style="text-align:center;">'.$btnAction.'</div>';

                $data[] = $nestedData;
                $no++;

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

    public function crudAgregatorTB2(){
        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='crudMHSBaru'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            // Update
            if($ID!=''){
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['updatedAt'] = $this->m_rest->getDateTimeNow();

                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.student_selection',$dataForm);

            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_agregator.student_selection',$dataForm);
            }

            return print_r(1);

        }
        else if($data_arr['action']=='crudMHSBaruAsing'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            // Update
            if($ID!=''){
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['updatedAt'] = $this->m_rest->getDateTimeNow();

                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.student_selection_foreign',$dataForm);

            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_agregator.student_selection_foreign',$dataForm);
            }

            return print_r(1);

        }
        else if($data_arr['action']=='filterYear'){
            $data = $this->db->query('SELECT Year FROM db_agregator.student_selection GROUP BY Year ORDER BY Year ASC')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='readDataMHSBaru'){

            $Year = $data_arr['Year'];
            $data = $this->db->query('SELECT ss.*, ps.Name AS ProdiName, ps.Code AS ProdiCode FROM db_agregator.student_selection ss 
                                                    LEFT JOIN db_academic.program_study ps ON (ps.ID = ss.ProdiID)
                                                    WHERE ss.Year = "'.$Year.'" ')->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='readDataMHSBaruAsing'){

            $Year = $data_arr['Year'];
            $data = $this->db->query('SELECT ssf.*, ps.Name AS ProdiName, ps.Code AS ProdiCode FROM db_agregator.student_selection_foreign ssf 
                                                    LEFT JOIN db_academic.program_study ps ON (ps.ID = ssf.ProdiID)
                                                    WHERE ssf.Year = "'.$Year.'" ')->result_array();

            return print_r(json_encode($data));

        }

        else if($data_arr['action']=='filterYearMhsAsing'){
            $data = $this->db->query('SELECT Year FROM db_agregator.student_selection_foreign GROUP BY Year ORDER BY Year ASC')->result_array();

            return print_r(json_encode($data));
        }
    }

    public function crudAgregatorTB4(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='readSumberDana'){

            $data = $this->db->get('db_agregator.sumber_dana')->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='readSumberDanaType'){

            $data = $this->db->get_where('db_agregator.sumber_dana_type',array(
                'SumberDanaID' => $data_arr['SumberDanaID']
            ))->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='readSumberDanaType_All'){
            $data = $this->db->query('SELECT sdt.*, sd.SumberDana FROM db_agregator.sumber_dana_type sdt 
                                          LEFT JOIN db_agregator.sumber_dana sd ON (sdt.SumberDanaID = sd.ID) ')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='updateSumberDana'){

            $ID = $data_arr['ID'];

            if($ID!=''){
                // Update
                $this->db->set('SumberDana', $data_arr['SumberDana']);
                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.sumber_dana');

            } else {
                $this->db->insert('db_agregator.sumber_dana', array(
                    'SumberDana' => $data_arr['SumberDana']
                ));
                $ID = $this->db->insert_id();
            }

            return print_r(json_encode(array('ID' => $ID)));

        }
        else if($data_arr['action']=='UpdateSumberDataType'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];
            if($ID!=''){
                // Update
                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.sumber_dana_type',$dataForm);
            } else {
                // Insert
                $this->db->insert('db_agregator.sumber_dana_type', $dataForm);
                $ID = $this->db->insert_id();
            }
            return print_r(json_encode(array('ID'=>$ID)));
        }
        else if($data_arr['action']=='updatePerolehanDana'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];
            if($ID!=''){
                // Update
                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.perolehan_dana',$dataForm);
            } else {
                // Insert
                $this->db->insert('db_agregator.perolehan_dana',$dataForm);
                $ID = $this->db->insert_id();
            }
            return print_r(json_encode(array('ID'=>$ID)));

        }
        else if($data_arr['action']=='readPerolehanDana'){

            $data = $this->db->query('SELECT pd.*, sd.SumberDana, sdt.Label AS SumberDanaType FROM db_agregator.perolehan_dana pd 
                                              LEFT JOIN db_agregator.sumber_dana sd ON (sd.ID = pd.SumberDanaID)
                                              LEFT JOIN db_agregator.sumber_dana_type sdt ON (sdt.ID = pd.SumberDanaTypeID) ')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removePerolehanDana'){

            $ID = $data_arr['ID'];

            $this->db->where('ID', $ID);
            $this->db->delete('db_agregator.perolehan_dana');

            return print_r(1);

        }

        else if($data_arr['action']=='updatePenggunaanDana'){

            $ID = $data_arr['ID'];

            $dataForm = (array) $data_arr['dataForm'];

            if($ID!=''){
                // Update
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.penggunaan_dana',$dataForm);

            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_agregator.penggunaan_dana',$dataForm);
                $ID = $this->db->insert_id();
            }

            return print_r(json_encode(array(
                'ID' => $ID
            )));

        }
        else if($data_arr['action']=='viewPenggunaanDana'){
            $data = $this->db->query('SELECT pd.*, jp.Jenis AS JP FROM db_agregator.penggunaan_dana pd 
                                                  LEFT JOIN db_agregator.jenis_penggunaan jp ON (pd.JPID = jp.ID) ')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removePenggunaanDana'){

            $this->db->where('ID', $data_arr['ID']);
            $this->db->delete('db_agregator.penggunaan_dana');

            return print_r(1);

        }
        else if($data_arr['action']=='updateJenisDana'){

            $ID = $data_arr['ID'];

            $dataForm = array('Jenis' => $data_arr['Jenis']);

            if($ID!=''){
                // Update

                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.jenis_penggunaan',$dataForm);

            } else {
                $this->db->insert('db_agregator.jenis_penggunaan',$dataForm);
                $ID = $this->db->insert_id();
            }

            return print_r(1);


        }
        else if($data_arr['action']=='viewJenisDana'){

            $data = $this->db->get('db_agregator.jenis_penggunaan')->result_array();
            return print_r(json_encode($data));
        }

    }

    public function crudAgregatorTB5(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='updatePAM'){

            $ID = $data_arr['ID'];

            $dataForm = (array) $data_arr['dataForm'];

            if($ID!=''){
                // Update
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.prestasi_mahasiswa',$dataForm);

            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_agregator.prestasi_mahasiswa',$dataForm);
                $ID = $this->db->insert_id();
            }

            return print_r(1);

        }
        else if($data_arr['action']=='viewPAM'){

            $data = $this->db->get_where('db_agregator.prestasi_mahasiswa', array(
                'Type' => $data_arr['Type']
            ))->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='removePAM'){

            $this->db->where('ID', $data_arr['ID']);
            $this->db->delete('db_agregator.prestasi_mahasiswa');
            return print_r(1);

        }
        else if($data_arr['action']=='viewIPK'){

            $Year = $data_arr['Year'];

            $data = $this->db->query('SELECT * FROM db_academic.auth_students ast 
                                                          WHERE ast.StatusStudentID = "1" 
                                                          AND ast.Year = "'.$Year.'" ')->result_array();



        }

    }

    public function getKecukupanDosen(){

        // Get Program Studi
        $data = $this->db->select('ID,Code,Name')->get_where('db_academic.program_study',array('Status' => 1))->result_array();

        if(count($data)>0){
            $dataLAP = $this->db->order_by('ID','DESC')->get_where('db_employees.level_education',array(
                'ID >' => 8
            ))->result_array();
            for($i=0;$i<count($data);$i++){

                for($j=0;$j<count($dataLAP);$j++){

                    $dataDetails = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.employees em WHERE em.ProdiID = "'.$data[$i]['ID'].'" 
                    AND em.LevelEducationID = "'.$dataLAP[$j]['ID'].'" ')->result_array();

                    $r = array('Level' => $dataLAP[$j]['Level'], 'Details' => $dataDetails);
                    $data[$i]['dataLecturers'][$j] = $r;
                }


                $dataL = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.employees em WHERE em.ProdiID = "'.$data[$i]['ID'].'" 
                    AND em.Profession <> "" ')->result_array();
                $r = array('Level' => '', 'Details' => $dataL);
                $data[$i]['dataLecturers'][2] = $r;

            }

        }


        return print_r(json_encode($data));

    }

    public function getJabatanAkademikDosenTetap(){

        $data = $this->db->get_where('db_employees.level_education',array(
            'ID >' => 7
        ))->result_array();

        $dataPosition = $this->db->get('db_employees.lecturer_academic_position')->result_array();

        if(count($data)>0){

            for($i=0;$i<count($data);$i++){

                for($p=0;$p<count($dataPosition);$p++){
                    $dataEmp = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.employees em 
                                                                    WHERE em.LevelEducationID = "'.$data[$i]['ID'].'" 
                                                                    AND em.LecturerAcademicPositionID = "'.$dataPosition[$p]['ID'].'"
                                                                     AND em.StatusForlap = "1" ')->result_array();

                    $r = array(
                        'Position' => $dataPosition[$p]['Position'],
                        'dataEmployees' => $dataEmp
                    );

                    $data[$i]['details'][$p] = $r;
                }


                $dataEmp = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.employees em 
                                                                    WHERE em.LevelEducationID = "'.$data[$i]['ID'].'" 
                                                                    AND em.LecturerAcademicPositionID NOT IN (SELECT ID FROM db_employees.lecturer_academic_position) 
                                                                     AND em.StatusForlap = "1" ')->result_array();

                $r = array(
                    'Position' => 'Tenaga Pengajar',
                    'dataEmployees' => $dataEmp
                );

                $data[$i]['details'][4] = $r;


            }

        }

        return print_r(json_encode($data));

    }

    public function getJabatanAkademikDosenTidakTetap(){

        $data = $this->db->get_where('db_employees.level_education',array(
            'ID >' => 7
        ))->result_array();

        $dataPosition = $this->db->get('db_employees.lecturer_academic_position')->result_array();

        if(count($data)>0){

            for($i=0;$i<count($data);$i++){

                for($p=0;$p<count($dataPosition);$p++){
                    $dataEmp = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.employees em 
                                                                    WHERE em.LevelEducationID = "'.$data[$i]['ID'].'" 
                                                                    AND em.LecturerAcademicPositionID = "'.$dataPosition[$p]['ID'].'"
                                                                     AND em.StatusForlap = "0" ')->result_array();

                    $r = array(
                        'Position' => $dataPosition[$p]['Position'],
                        'dataEmployees' => $dataEmp
                    );

                    $data[$i]['details'][$p] = $r;
                }

                $dataEmp = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.employees em 
                                                                    WHERE em.LevelEducationID = "'.$data[$i]['ID'].'" 
                                                                    AND em.LecturerAcademicPositionID NOT IN (SELECT ID FROM db_employees.lecturer_academic_position) 
                                                                     AND em.StatusForlap = "0" ')->result_array();

                $r = array(
                    'Position' => 'Tenaga Pengajar',
                    'dataEmployees' => $dataEmp
                );

                $data[$i]['details'][4] = $r;

            }

        }

        return print_r(json_encode($data));

    }

    public function getLecturerCertificate(){

        $Status = $this->input->get('s');

        $data = $this->db->select('ID, Code, Name')->get_where('db_academic.program_study',array(
            'Status' => 1
        ))->result_array();

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){

                $and2 = ($Status!='all') ? ' AND StatusForlap = "'.$Status.'" ' : '';

                // Total Employees
                $dataEmp = $this->db->query('SELECT COUNT(*) AS Total FROM db_employees.employees 
                                          WHERE ProdiID = "'.$data[$i]['ID'].'"  '.$and2)->result_array();

                $data[$i]['TotalLecturer'] = $dataEmp[0]['Total'];

                $dataEmpCerti = $this->db->query('SELECT COUNT(*) AS Total FROM db_employees.employees 
                                          WHERE ProdiID = "'.$data[$i]['ID'].'" AND Certified="1"  '.$and2)->result_array();
                $data[$i]['TotalLecturerCertifies'] = $dataEmpCerti[0]['Total'];

            }
        }



        return print_r(json_encode($data));

    }

    public function getAkreditasiProdi(){
        $data = $this->db->get('db_academic.accreditation')->result_array();

        if(count($data)>0){

            // Data Education level
            $edl = $this->db->get('db_academic.education_level')->result_array();
            for($i=0;$i<count($data);$i++){

                for($a=0;$a<count($edl);$a++){

                    $dataP = $this->db->get_where('db_academic.program_study',array(
                        'EducationLevelID' => $edl[$a]['ID'],
                        'AccreditationID' => $data[$i]['ID']
                    ))->result_array();

                    $r = array(
                        'Level' => $edl[$a]['Name'],
                        'Prodi' => count($dataP)
                    );

                    $data[$i]['Details'][$a] = $r;

                }

            }

        }

        return print_r(json_encode($data));

    }

    public function crudAgregator(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='updateTable'){

            $ID = ($data_arr['ID']!='') ? $data_arr['ID'] : '';
            $table = $data_arr['table'];
            $dataForm = (array) $data_arr['dataForm'];
            $OldFile = '';

            if($ID!=''){
                // Update
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID', $ID);
                $this->db->update(''.$table,$dataForm);



            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert(''.$table,$dataForm);
                $ID = $this->db->insert_id();
            }


            return print_r(json_encode(array(
                'ID' => $ID,
                'File' => $OldFile
            )));

        }

    }

    public function crudGroupStd(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='view_GS'){

            $ProdiID = $data_arr['ProdiID'];

            $data = $this->db->order_by('ID','ASC')->get_where('db_academic.prodi_group',array(
                'ProdiID' => $ProdiID
            ))->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='update_GS'){
            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            if($ID!=''){
                // Update
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID',$ID);
                $this->db->update('db_academic.prodi_group',$dataForm);
            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_academic.prodi_group',$dataForm);
            }

            return print_r(1);
        }
        else if($data_arr['action']=='viewStudent_GS'){
            $data = $this->db->select('ID,NPM, Name, ProdiGroupID')->get_where('db_academic.auth_students',array(
                'ProdiGroupID' => $data_arr['ProdiGroupID']
            ))->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='viewStudentNew_GS'){

            $data = $this->db->query('SELECT ID, NPM, Name, ProdiGroupID FROM db_academic.auth_students 
                                          WHERE Year = "'.$data_arr['Year'].'"
                                           AND ProdiID = "'.$data_arr['ProdiID'].'"
                                            AND (ProdiGroupID IS NULL OR ProdiGroupID ="")')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='updateStudent_GS'){

            $arrID = (array) $data_arr['arrID'];

            for ($i=0;$i<count($arrID);$i++){

                // Update
                $this->db->where('ID',$arrID[$i]);
                $this->db->update('db_academic.auth_students',array(
                    'ProdiGroupID' => $data_arr['ProdiGroupID']
                ));
                $this->db->reset_query();

                // get nip
                $dataN = $this->db->select('NPM')->get_where('db_academic.auth_students',array(
                    'ID' => $arrID[$i]
                ))->result_array();

                $this->db->insert('db_academic.prodi_group_log',array(
                    'NPM' => $dataN[0]['NPM'],
                    'ProdiGroupID' => $data_arr['ProdiGroupID'],
                    'Status' => 'in',
                    'UpdatedBy' => $this->session->userdata('NIP')
                ));


            }

            return print_r(1);

        }
        else if($data_arr['action']=='removeFMGrStudent_GS'){

            $this->db->where('ID',$data_arr['ID']);
            $this->db->update('db_academic.auth_students',array(
                'ProdiGroupID' => ''
            ));

            $this->db->reset_query();

            $this->db->insert('db_academic.prodi_group_log',array(
                'NPM' => $data_arr['NPM'],
                'ProdiGroupID' => $data_arr['ProdiGroupID'],
                'Status' => 'out',
                'UpdatedBy' => $this->session->userdata('NIP')
            ));


            return print_r(1);



        }

    }


    public function crudCheckDataKRS(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='checkDataKRS'){

            $Year = $data_arr['Year'];
            $SemesterID = $data_arr['SemesterID'];

            $db = 'ta_'.$Year;

            $dataStd = $this->db->query('SELECT s.NPM, s.Name FROM  '.$db.'.students s 
                                                ORDER BY s.NPM ASC ')->result_array();

            $result = [];
            if(count($dataStd)>0){

                for($i=0;$i<count($dataStd);$i++){

                    // KRS Approve
                    $dataSP = $this->db->query('SELECT sp.ID, sp.ScheduleID, sch.ClassGroup FROM '.$db.'.study_planning sp
                                                LEFT JOIN db_academic.schedule sch ON (sch.ID = sp.ScheduleID)
                                                WHERE sp.SemesterID = '.$SemesterID.' 
                                                AND sp.NPM = "'.$dataStd[$i]['NPM'].'"
                                                ORDER BY sp.ScheduleID ASC ')->result_array();

                    // KRS Online
                    $dataKO = $this->db->query('SELECT sk.ID, sk.ScheduleID, sch.ClassGroup FROM db_academic.std_krs sk 
                                                LEFT JOIN db_academic.schedule sch ON (sch.ID = sk.ScheduleID)
                                                WHERE sk.SemesterID = '.$SemesterID.' 
                                                AND sk.NPM = "'.$dataStd[$i]['NPM'].'"
                                                AND sk.Status = "3" 
                                                ORDER BY sk.ScheduleID ASC ')->result_array();



                    if(count($dataSP)!= count($dataKO)){

                        $dataStd[$i]['A'] = $dataSP;
                        $dataStd[$i]['B'] = $dataKO;
                        array_push($result,$dataStd[$i]);
                    }

                }

            }

            return print_r(json_encode($result));

        }
        else if($data_arr['action']=='removeRedundancy'){

            $Year = $data_arr['Year'];
            $NPM = $data_arr['NPM'];
            $SemesterID = $data_arr['SemesterID'];
            $ScheduleID = $data_arr['ScheduleID'];

            $db = 'ta_'.$Year.'.study_planning';

            // Cek apakah double
            $data = $this->db->query('SELECT sp.ID FROM '.$db.' sp WHERE sp.SemesterID = "'.$SemesterID.'" 
                                                AND sp.NPM = "'.$NPM.'"
                                                 AND sp.ScheduleID = "'.$ScheduleID.'" ')->result_array();

            $result = array(
                'Status' => '0'
            );

            if(count($data)>1){


                // Get ID Attendance
                $dataAttd = $this->db->select('ID')->get_where('db_academic.attendance',array(
                    'SemesterID' => $SemesterID,
                    'ScheduleID' => $ScheduleID
                ))->result_array();

                if(count($dataAttd)>0){
                    for ($i=0;$i<count($dataAttd);$i++){
                        $this->db->where(array(
                            'ID_Attd' => $dataAttd[$i]['ID'],
                            'NPM' => $NPM
                        ));
                        $this->db->delete('db_academic.attendance_students');
                    }
                }

                // Remove di SP
                $this->db->where('ID', $data_arr['SPID']);
                $this->db->delete($db);

                $result = array(
                    'Status' => '1'
                );


            }

            return print_r(json_encode($result));

        }

    }

    public function crudYudisium(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='viewYudisiumList'){

            $requestData= $_REQUEST;

            $SemesterID = $data_arr['SemesterID'];

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataSearch = ' WHERE  lmk.Lembaga LIKE "%'.$search.'%" 
            OR uc.Tingkat LIKE "%'.$search.'%"
             OR uc.Benefit LIKE "%'.$search.'%"';
            }

            $queryDefault = 'SELECT ssp.*, ats.Name AS StudentName, ats.IjazahSMA, mk.MKCode,   
                                        mk.NameEng AS CourseEng, sc.ClassGroup, 
                                        ats.ClearentLibrary, ats.ClearentLibrary_By, ats.ClearentLibrary_At, em1.Name AS ClearentLibrary_Name,    
                                        ats.ClearentFinance, ats.ClearentFinance_By, ats.ClearentFinance_At, em2.Name AS ClearentFinance_Name,    
                                        ats.ClearentKaprodi, ats.ClearentKaprodi_By, ats.ClearentKaprodi_At, em3.Name AS ClearentKaprodi_Name, 
                                        ats.ID AS AUTHID  
                                        FROM db_academic.std_study_planning ssp
                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = ssp.MKID)
                                        LEFT JOIN db_academic.auth_students ats ON (ats.NPM = ssp.NPM)
                                        LEFT JOIN db_academic.schedule sc ON (sc.ID = ssp.ScheduleID)
                                        LEFT JOIN db_employees.employees em1 ON (ats.ClearentLibrary_By = em1.NIP)
                                        LEFT JOIN db_employees.employees em2 ON (ats.ClearentFinance_By = em2.NIP)
                                        LEFT JOIN db_employees.employees em3 ON (ats.ClearentKaprodi_By = em3.NIP)
                                        WHERE mk.Yudisium = "1" AND ssp.SemesterID = "'.$SemesterID.'" ';

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefault)->result_array();

            $no = $requestData['start'] + 1;
            $data = array();
            for($i=0;$i<count($query);$i++) {

                $nestedData = array();
                $row = $query[$i];

                // Get score
                $dbStd = 'ta_'.$row['ClassOf'];
                $dataScore = $this->db->select('Score')->get_where($dbStd.'.study_planning',array(
                    'ID' => $row['SPID']
                ))->result_array();


                // Score
                $Score = ($dataScore[0]['Score']!=null && $dataScore[0]['Score']!='') ? $dataScore[0]['Score'] : '';

                $DeptID = $this->session->userdata('IDdepartementNavigation');

                // Ijazah
                $ijazahBtnD = ($row['IjazahSMA']!=null && $row['IjazahSMA']!='')
                    ? '<hr style="margin-top: 7px;margin-bottom: 3px;"/><a href="'.base_url('uploads/ijazah_student/'.$row['IjazahSMA']).'" target="_blank"><i class="fa fa-download"></i> Download</a>'
                    : '<hr style="margin-top: 7px;margin-bottom: 3px;"/> Waiting Upload';
                if($DeptID=='6' || $DeptID==6){

                    $fileIjazahOld = ($row['IjazahSMA']!=null && $row['IjazahSMA']!='') ? $row['IjazahSMA'] : '';

                    $ijazah = '<form id="formupload_files_'.$row['AUTHID'].'" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <div class="form-group"><label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                        <i class="fa fa-upload"></i>
                                        <input type="file" name="userfile" class="uploadIjazahStudentFile" data-old="'.$fileIjazahOld.'" data-npm="'.$row['NPM'].'" data-id="'.$row['AUTHID'].'" id="upload_files_'.$row['AUTHID'].'" accept="application/pdf" style="display: none;">
                                    </label>
                                </div>
                        </form>'.$ijazahBtnD;

                } else {
                    $ijazah = $ijazahBtnD;
                }


                // Library
                $dateTm = ($row['ClearentLibrary_At']!='' && $row['ClearentLibrary_At']!=null) ? ' <div style="color: #9e9e9e;">'.date('d M Y H:i',strtotime($row['ClearentLibrary_At'])).'</div>' : '';
                if($DeptID=='11' || $DeptID==11){
                    $c_Library = ($row['ClearentLibrary']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                        <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentLibrary_Name'].''.$dateTm
                        : '<button class="btn btn-sm btn-default btnClearnt" data-id="'.$row['AUTHID'].'" data-c="ClearentLibrary">Clearent</button>';
                } else {
                    $c_Library = ($row['ClearentLibrary']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                        <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentLibrary_Name'].''.$dateTm
                        : 'Waiting Library Clearent';
                }



                // Finance
                $dateTm = ($row['ClearentFinance_At']!='' && $row['ClearentFinance_At']!=null) ? ' <div style="color: #9e9e9e;">'.date('d M Y H:i',strtotime($row['ClearentFinance_At'])).'</div>' : '';
                if($DeptID=='9' || $DeptID==9){
                    $c_Finance = ($row['ClearentFinance']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                        <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentFinance_Name'].''.$dateTm
                        : '<button class="btn btn-sm btn-default btnClearnt" data-id="'.$row['AUTHID'].'" data-c="ClearentFinance">Clearent</button>';
                } else {
                    $c_Finance = ($row['ClearentFinance']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                        <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentFinance_Name'].''.$dateTm
                        : 'Waiting Finance Clearent';
                }


                // kaprodi
                $dateTm = ($row['ClearentKaprodi_At']!='' && $row['ClearentKaprodi_At']!=null) ? ' <div style="color: #9e9e9e;">'.date('d M Y H:i',strtotime($row['ClearentKaprodi_At'])).'</div>' : '';
                $c_Kaprodi = ($row['ClearentKaprodi']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                    <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentKaprodi_Name'].''.$dateTm
                    : 'Waiting Approval';
//                    : '<button class="btn btn-sm btn-default btnClearnt" data-id="'.$row['AUTHID'].'" data-c="ClearentKaprodi">Clearent</button>';

                $c_Kaprodi = ($row['ClearentFinance']!='0' && $row['ClearentLibrary']!='0' &&
                    $row['IjazahSMA']!=null && $row['IjazahSMA']!='') ? $c_Kaprodi : '<span style="font-size: 12px;">Waiting Library & Finance Clearent</span>';


                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;"><b>'.$row['StudentName'].'</b><br/>'.$row['NPM'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['CourseEng'].'<br/>'.$row['MKCode'].' | Group : '.$row['ClassGroup'].'</div>';
                $nestedData[] = '<div>'.$Score.'</div>';
                $nestedData[] = '<div>'.$ijazah.'</div>';
                $nestedData[] = '<div>'.$c_Library.'</div>';
                $nestedData[] = '<div>'.$c_Finance.'</div>';
                $nestedData[] = '<div>'.$c_Kaprodi.'</div>';

                $data[] = $nestedData;
                $no++;
            }

            $json_data = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval(count($queryDefaultRow)),
                "recordsFiltered" => intval( count($queryDefaultRow) ),
                "data"            => $data
            );
            echo json_encode($json_data);

        }

        else if($data_arr['action']=='updateClearent'){

            $ID = $data_arr['ID'];
            $C = $data_arr['C'];

            $arr = array(
                $C => '1',
                $C.'_By' => $this->session->userdata('NIP'),
                $C.'_At' => $this->m_rest->getDateTimeNow()
            );

            $this->db->where('ID', $ID);
            $this->db->update('db_academic.auth_students',$arr);

            return print_r(1);

        }

    }

}
