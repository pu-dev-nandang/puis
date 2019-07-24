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

    public function getKecukupanDosen(){

        // Get Program Studi
        $data = $this->db->select('ID,Code,Name')->get_where('db_academic.program_study',array('Status' => 1))->result_array();

        if(count($data)>0){
            $dataLAP = $this->db->get_where('db_employees.level_education',array(
                'ID >' => 7
            ))->result_array();
            for($i=0;$i<count($data);$i++){

                for($j=0;$j<count($dataLAP);$j++){

                    $dataDetails = $this->db->query('SELECT em.NIP, em.Name FROM db_employees.employees em WHERE em.ProdiID = "'.$data[$i]['ID'].'" 
                    AND em.LevelEducationID = "'.$dataLAP[$j]['ID'].'" ')->result_array();

                    $r = array('Level' => $dataLAP[$j]['Level'], 'Details' => $dataDetails);
                    $data[$i]['dataLecturers'][$j] = $r;

                }


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
                                                                    AND em.LecturerAcademicPositionID = "'.$dataPosition[$p]['ID'].'" ')->result_array();

                    $r = array(
                        'Position' => $dataPosition[$p]['Position'],
                        'dataEmployees' => $dataEmp
                    );

                    $data[$i]['details'][$p] = $r;
                }


            }

        }

        return print_r(json_encode($data));

    }

}
