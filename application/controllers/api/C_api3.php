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

    public function getListMenuAgregator($Type){

//        $data = $this->db->order_by('ID','ASC')->get('db_agregator.agregator_menu')->result_array();

        $data = $this->db->query('SELECT am.* FROM db_agregator.agregator_menu am
                                              LEFT JOIN db_agregator.agregator_menu_header amh
                                              ON (amh.ID = am.MHID)
                                              WHERE amh.Type = "'.$Type.'" ')->result_array();

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
        else if($data_arr['action']=='updateLembagaSurview') {

            $dataForm = array(
                'Lembaga' => $data_arr['Lembaga'],
                'Description' => $data_arr['Description']
            );

            if($data_arr['ID']!=''){
                // Update
                $this->db->where('ID',$data_arr['ID']);
                $this->db->update('db_agregator.lembaga_surview',$dataForm);
            } else {

                $squery = 'SELECT * FROM db_agregator.lembaga_surview WHERE Lembaga = "'.$data_arr['Lembaga'].'" ';
                $dataTable =$this->db->query($squery, array())->result_array();

                if(count($dataTable)>0){
                    return print_r(0);
                }
                else {
                    // Insert
                    $this->db->insert('db_agregator.lembaga_surview',$dataForm);
                    return print_r(1);
                }
            }

        }
        else if($data_arr['action']=='updateLembagaAudit') {

            $dataForm = array(
                'Lembaga' => $data_arr['Lembaga'],
                'Description' => $data_arr['Description']
            );

            if($data_arr['ID']!=''){
                    // Update
                $this->db->where('ID',$data_arr['ID']);
                $this->db->update('db_agregator.lembaga_audit',$dataForm);
            } else {

                $squery = 'SELECT * FROM db_agregator.lembaga_audit WHERE Lembaga = "'.$data_arr['Lembaga'].'" ';
                $dataTable =$this->db->query($squery, array())->result_array();

                if(count($dataTable)>0){
                        return print_r(0);
                }
                else {
                // Insert
                    $this->db->insert('db_agregator.lembaga_audit',$dataForm);
                    return print_r(1);
                }
            }
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
                                                                        <li><a href="javascript:void(0);" class="btnEditAE" data-no="'.$no.'"> <i class="fa fa fa-edit"></i> Edit</a></li>
                                                                        <li role="separator" class="divider"></li>
                                                                        <li><a href="javascript:void(0);" class="btnRemove" data-id="'.$row['ID'].'"> <i class="fa fa fa-trash"></i> Delete</a></li>
                                                                      </ul>
                                                                    </div>
                                                                        <textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                $nestedData[] = '<div style="text-align:center;">'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Lembaga'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Type'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Scope'].'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$row['Level'].'</div>';
                $nestedData[] = '<div style="text-align:center;">'.date('d M Y',strtotime($row['DueDate'])).'</div>';
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
                                                                        <li><a href="javascript:void(0);" class="btnEdit" data-no="'.$no.'"><i class="fa fa fa-edit"></i>Edit</a></li>
                                                                        <li role="separator" class="divider"></li>
                                                                        <li><a href="javascript:void(0);" class="btnRemove" data-id="'.$row['ID'].'" data-tb="db_agregator.international_accreditation_prodi"><i class="fa fa fa-trash"></i> Remove</a></li>
                                                                      </ul>
                                                                    </div>
                                                                        <textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                $nestedData[] = '<div style="text-align:center;">'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Lembaga'].'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$row['ProdiName'].'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$row['Status'].'</div>';
                $nestedData[] = '<div style="text-align:center;">'.date('d M Y',strtotime($row['DueDate'])).'</div>';
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

        else if($data_arr['action']=='removeDataMasterSurvey'){

            $ID = $data_arr['ID'];

            $this->db->where('ID', $ID);
            $this->db->delete('db_agregator.lembaga_surview');
            return print_r(1);

        }

        else if($data_arr['action']=='removeAkreditasi_eks'){

            $ID = $data_arr['ID'];

            $this->db->where('ID', $ID);
            $this->db->delete('db_agregator.external_accreditation');
            return print_r(1);

        }

        else if($data_arr['action']=='removeMasterAudit'){

            $ID = $data_arr['ID'];

            $this->db->where('ID', $ID);
            $this->db->delete('db_agregator.lembaga_audit');
            return print_r(1);

        }
        else if($data_arr['action']=='removeKerjasama'){

            $ID = $data_arr['ID'];

            $this->db->where('ID', $ID);
            $this->db->delete('db_agregator.lembaga_mitra_kerjasama');
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
                                                                        <li><a href="javascript:void(0);" class="btnEditAE" data-no="'.$no.'"><i class="fa fa fa-edit"></i> Edit</a></li>
                                                                        <li role="separator" class="divider"></li>
                                                                        <li><a href="javascript:void(0);" class="btnRemove" data-id="'.$row['ID'].'" data-tb="db_agregator.financial_external_audit"><i class="fa fa fa-trash"></i> Remove</a></li>
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

            if($data_arr['ID']!='') {
                // Update
                $this->db->where('ID',$data_arr['ID']);
                $this->db->update('db_agregator.lembaga_mitra_kerjasama',$dataForm);
            }
            else {
                $squery = 'SELECT * FROM db_agregator.lembaga_mitra_kerjasama WHERE Lembaga = "'.$data_arr['Lembaga'].'" ';
                $dataTable =$this->db->query($squery, array())->result_array();

                if(count($dataTable)>0){
                    return print_r(0);
                }
                else {
                    // Insert
                    $this->db->insert('db_agregator.lembaga_mitra_kerjasama',$dataForm);
                    return print_r(1);
                }
            }

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
                                                                        <li><a href="javascript:void(0);" class="btnEditAE" data-no="'.$no.'"><i class="fa fa fa-edit"></i> Edit</a></li>
                                                                        <li role="separator" class="divider"></li>
                                                                        <li><a href="javascript:void(0);" class="btnRemove" data-id="'.$row['ID'].'" data-file="'.$row['File'].'" data-tb="db_agregator.university_collaboration"><i class="fa fa fa-trash"></i> Remove</a></li>
                                                                      </ul>
                                                                    </div>
                                                                        <textarea class="hide" id="viewDetail_'.$no.'">'.json_encode($row).'</textarea>' : '-';

                if($row['File'] == null) {
                    $links = '<p target="_blank" disabled>No File</p>';
                } else {
                    $links = '<a target="_blank" href="'.base_url('uploads/agregator/'.$row['File']).'">Download Bukti</a>';
                }

                $nestedData[] = '<div style="text-align:center;">'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Lembaga'].'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$row['Tingkat'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.$row['Benefit'].'</div>';
                $nestedData[] = '<div style="text-align:left;">'.date('d M Y',strtotime($row['DueDate'])).'</div>';
                $nestedData[] = '<div style="text-align:center;">'.$links.'</div>';
                //$nestedData[] = '<div style="text-align:left;"><a target="_blank" href="'.base_url('uploads/agregator/'.$row['File']).'">Download Bukti</a></div>';
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
            // $data = $this->db->query('SELECT Year FROM db_agregator.student_selection GROUP BY Year ORDER BY Year ASC')->result_array();
            $data = [];
            $sql = "show databases like '".'ta_'."%'";
            $query=$this->db->query($sql, array())->result_array();
            for ($i=0; $i < count($query); $i++) {
                $variable = $query[$i]; 
                foreach ($variable as $key => $value) {
                    $ex = explode('_', $value);
                    $ta = $ex[1];
                    $data[] = array('Year' => $ta);
                }
            }
            
            return print_r(json_encode($data));
        }
        else if($data_arr['action'] == 'LoadDataToInputMHSBaru'){
            $this->load->model('admission/m_admission');
            $Year = $data_arr['Year'];
            $ProdiID = $data_arr['ProdiID'];
            $G_proses = $this->m_admission->proses_agregator_seleksi_mhs_baru_by_prodi($Year,$ProdiID);
            $sql = 'SELECT ss.*, ps.Name AS ProdiName, ps.Code AS ProdiCode FROM db_agregator.student_selection ss
                                                LEFT JOIN db_academic.program_study ps ON (ps.ID = ss.ProdiID)
                                                WHERE ss.Year = "'.$Year.'" and  ss.ProdiID = ? ';
            $query=$this->db->query($sql, array($ProdiID))->result_array();

            return print_r(json_encode($query));
        }
        else if($data_arr['action']=='readDataMHSBaru'){
            $this->load->model('admission/m_admission');
            $Year = $data_arr['Year'];
            // insert data all ta ke db_agregator.student_selection
            $G_proses = $this->m_admission->proses_agregator_seleksi_mhs_baru($Year);

            $data = array();
            // get all prodi
            $G_prodi = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
            for ($i=0; $i <count($G_prodi) ; $i++) {
                $sql = 'SELECT ss.*, ps.Name AS ProdiName, ps.Code AS ProdiCode FROM db_agregator.student_selection ss
                                                    LEFT JOIN db_academic.program_study ps ON (ps.ID = ss.ProdiID)
                                                    WHERE ss.Year = "'.$Year.'" and  ss.ProdiID = ? ';
                $query=$this->db->query($sql, array($G_prodi[$i]['ID']))->result_array();

                if (count($query) == 0) {
                    $temp = [
                        'Capacity' => null,
                        'EntredAt' => null,
                        'EntredBy' => null,
                        'ID' => null,
                        'PassSelection' => null,
                        'ProdiCode' => $G_prodi[$i]['Code'],
                        'ProdiID' => $G_prodi[$i]['ID'],
                        'ProdiName' => $G_prodi[$i]['Name'],
                        'Registrant' => null,
                        'Regular' => null,
                        'Regular2' => null,
                        'TotalStudemt' => null,
                        'Transfer' => null,
                        'Transfer2' => null,
                        'Type' => null,
                        'UpdatedBy' => null,
                        'Year' => $Year,
                        'updatedAt' => null,
                    ];
                }
                else
                {
                    $temp = $query[0];
                }

                $data[] = $temp;

            }

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='readDataMHSBaruByProdi'){

            // get tahun akademik smpe sekarang
            $rs = array();
            $arr_tahun_akademik = array();
            $stYear = 2014;
            $endYear = date('Y');
            for ($i=$stYear; $i <= $endYear; $i++) {
                $arr_tahun_akademik[] = $i;
            }

            // get prodi
            $filterProdi = $data_arr['filterProdi'];
            $filterProdiName = $data_arr['filterProdiName'];
            $exFPName = explode('-', $filterProdiName);
            $filterProdiName = trim($exFPName[1]);
            $arrExp = explode('.', $filterProdi);

            for ($i=0; $i < count($arr_tahun_akademik); $i++) {
                $Year = $arr_tahun_akademik[$i];
                $sql = 'SELECT ss.*, ps.Name AS ProdiName, ps.Code AS ProdiCode FROM db_agregator.student_selection ss
                                                    LEFT JOIN db_academic.program_study ps ON (ps.ID = ss.ProdiID)
                                                    WHERE ss.Year = "'.$Year.'" and  ss.ProdiID = ? ';
                $query=$this->db->query($sql, array($arrExp[0]))->result_array();
                if (count($query) == 0) {
                    $temp = [
                        'Capacity' => null,
                        'EntredAt' => null,
                        'EntredBy' => null,
                        'ID' => null,
                        'PassSelection' => null,
                        'ProdiCode' => $arrExp[1],
                        'ProdiID' => $arrExp[0],
                        'ProdiName' => $filterProdiName,
                        'Registrant' => null,
                        'Regular' => null,
                        'Regular2' => null,
                        'TotalStudemt' => null,
                        'Transfer' => null,
                        'Transfer2' => null,
                        'Type' => null,
                        'UpdatedBy' => null,
                        'Year' => $Year,
                        'updatedAt' => null,
                    ];
                }
                else
                {
                    $temp = $query[0];
                }

                $rs[] = $temp;
            }

            return print_r(json_encode($rs));

        }
        else if($data_arr['action']=='readDataMHSBaruAsing'){

            // $Year = $data_arr['Year'];
            // $data = $this->db->query('SELECT ssf.*, ps.Name AS ProdiName, ps.Code AS ProdiCode FROM db_agregator.student_selection_foreign ssf
            //                                         LEFT JOIN db_academic.program_study ps ON (ps.ID = ssf.ProdiID)
            //                                         WHERE ssf.Year = "'.$Year.'" ')->result_array();


            $rs = array('header' => array(),'body' => array(),  );
            // show all ta
            $sql = "show databases like '".'ta_'."%'";
            $query=$this->db->query($sql, array())->result_array();
            $temp = ['No','Program Studi'];
            for ($i=0; $i < count($query); $i++) {
                $arr = $query[$i];
                $db_ = '';

                foreach ($arr as $key => $value) {
                    $db_ = $value;
                }

                if ($db_ != '') {
                    $ta_year = explode('_', $db_);
                    $ta_year = $ta_year[1];
                    $temp[] = $ta_year;
                }
            }

            $rs['header'] = $temp;

            // body
            // find prodi
            $G_prodi = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
            $body = array();
            for ($j=0; $j < count($G_prodi); $j++) {
                $temp = [];
                // find count
                $ProdiID = $G_prodi[$j]['ID'];
                $ProdiName = $G_prodi[$j]['Name'];
                $temp[] = $ProdiName;
                for ($i=0; $i < count($query); $i++) {
                    $arr = $query[$i];
                    $db_ = '';

                    foreach ($arr as $key => $value) {
                        $db_ = $value;
                    }

                    $sql1 = 'select count(*) as total from '.$db_.'.students where NationalityID !=  "001" and ProdiID = ? ';
                    $query1=$this->db->query($sql1, array($ProdiID))->result_array();
                    $total = $query1[0]['total'];
                    $temp[] = $total;
                }

                $body[] = $temp;
            }

            $rs['body'] = $body;

            return print_r(json_encode($rs));

        }

        // else if($data_arr['action']=='filterYearMhsAsing'){
        //     // $data = $this->db->query('SELECT Year FROM db_agregator.student_selection_foreign GROUP BY Year ORDER BY Year ASC')->result_array();
        //     $data = [];
        //     $sql = "show databases like '".'ta_'."%'";
        //     $query=$this->db->query($sql, array())->result_array();
        //     for ($i=0; $i < count($query); $i++) {
        //         $variable = $query[$i]; 
        //         foreach ($variable as $key => $value) {
        //             $ex = explode('_', $value);
        //             $ta = $ex[1];
        //             $data[] = array('Year' => $ta);
        //         }
        //     }
        //     return print_r(json_encode($data));
        // }

        else if($data_arr['action']=='getAllCourse'){

            $dataProdi = $this->db->select('ID, Name')->get_where('db_academic.program_study',array(
                'Status' => '1'
            ))->result_array();

            $CurriculumID = $data_arr['CurriculumID'];

            if(count($dataProdi)>0){
                // get data kurikulum
                for($i=0;$i<count($dataProdi);$i++){
                    $d = $dataProdi[$i];
                    $dataCur = $this->db->query('SELECT cd.TotalSKS, mk.Name, mk.MKCode, mk.CourseType,cd.Semester FROM db_academic.curriculum_details cd 
                                                            LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = cd.MKID)
                                                            WHERE cd.ProdiID = "'.$d['ID'].'" 
                                                             AND cd.CurriculumID = "'.$CurriculumID.'"
                                                             ORDER BY cd.Semester ASC')->result_array();

                    $dataProdi[$i]['Details'] = $dataCur;
                }
            }

            return print_r(json_encode($dataProdi));

        }
    }


    public function crudAgregatorTB3(){

        $data_arr = $this->getInputToken2();

        // Rekognisi Dosen
        if($data_arr['action']=='save_rekognisi_dosen') {

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            if($ID!=''){
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();

                // add bukti upload,buktiname dan tingkat
                $BuktiUpload = json_encode('');
                if (array_key_exists('BuktiUpload', $_FILES)) {
                    $Upload = $this->m_master->uploadDokumenMultiple(uniqid(),'BuktiUpload',$path = './uploads/Agregator/Aps/');
                    $Upload = json_encode($Upload);
                    $BuktiUpload = $Upload;
                }

                $dataForm['BuktiPendukungUpload'] = $BuktiUpload;
                $this->db->where('ID',$ID);
                $this->db->update('db_agregator.rekognisi_dosen',$dataForm);
            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                // add bukti upload,buktiname dan tingkat
                $BuktiUpload = json_encode(array());
                if (array_key_exists('BuktiUpload', $_FILES)) {
                    $Upload = $this->m_master->uploadDokumenMultiple(uniqid(),'BuktiUpload',$path = './uploads/Agregator/Aps/');
                    $Upload = json_encode($Upload);
                    $BuktiUpload = $Upload;
                }
                $dataForm['BuktiPendukungUpload'] = $BuktiUpload;
                $this->db->insert('db_agregator.rekognisi_dosen',$dataForm);
            }

            return print_r(1);
        }
        else if($data_arr['action']=='readDataRekognisiDosen'){
            $data = $this->db->query('SELECT rd.*, em.Name FROM db_agregator.rekognisi_dosen rd
                                                LEFT JOIN db_employees.employees em ON (em.NIP = rd.NIP)
                                                ORDER BY em.Name ASC ')->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removeDataRekognisiDosen') {
            $ID = $data_arr['ID'];

            // remove file is exist
            $G_data = $this->m_master->caribasedprimary('db_agregator.rekognisi_dosen','ID',$ID);
            if ($G_data[0]['BuktiPendukungUpload'] != '' && $G_data[0]['BuktiPendukungUpload'] != null) {
                $arr_file = (array) json_decode($G_data[0]['BuktiPendukungUpload'],true);
                if (count($arr_file) > 0) {
                    $filePath = 'Agregator\\Aps\\'.$arr_file[0]; // pasti ada file karena required
                    $path = FCPATH.'uploads\\'.$filePath;
                    unlink($path);
                }
            }

            $this->db->where('ID', $ID);
            $this->db->delete('db_agregator.rekognisi_dosen');
            return print_r(1);
        }
        else if($data_arr['action']=='readProduktivitasPenelitian'){

            $rs = array('header' => array(),'body' => array() );
            $Year = date('Y');
            $Year3 = $Year - 2;
            $arr_year = array();
            for ($i=$Year; $i >= $Year3; $i--) {
                $arr_year[] = $i;
            }
            $header = $arr_year;
            // print_r($arr_year);
            $body = array();
            //$G_research = $this->m_master->showData_array('db_research.sumber_dana');
            $G_research = $this->db->query('SELECT * FROM db_agregator.sumber_dana WHERE Status = "1" ')->result_array();
            for ($i=0; $i < count($G_research); $i++) {
                $temp = array();
                $temp[] = $G_research[$i]['SumberDana'];
                $ID_sumberdana = $G_research[$i]['ID'];
                for ($j=0; $j < count($arr_year); $j++) {
                    $Year_ = $arr_year[$j];
                     //$sql = 'select Judul_litabmas from db_research.litabmas where ID_sumberdana = ? and ID_thn_laks = ? ';
                     $sql = 'SELECT a.Judul_litabmas, b.Name
                            FROM db_research.litabmas AS a
                            LEFT JOIN db_employees.employees AS b ON (b.NIP = a.NIP) where a.ID_sumberdana = ? and a.ID_thn_laks = ? ';
                     $query=$this->db->query($sql, array($ID_sumberdana,$Year_))->result_array();

                     // $count = $query[0]['total'];
                     $temp[] = $query;
                     // $temp['SumberDana'] = $G_research[$i]['SumberDana'];
                }

                $body[] = $temp;

            }
            $rs['header'] = $header;
            $rs['body'] = $body;
            return print_r(json_encode($rs));
        }

        else if($data_arr['action']=='readProduktivitasPkmDosen'){

            $rs = array('header' => array(),'body' => array() );
            $Year = date('Y');
            $Year3 = $Year - 2;
            $arr_year = array();
            for ($i=$Year; $i >= $Year3; $i--) {
                $arr_year[] = $i;
            }
            $header = $arr_year;
            // print_r($arr_year);
            $body = array();
            //$G_research = $this->m_master->showData_array('db_agregator.sumber_dana');
            $G_research = $this->db->query('SELECT * FROM db_agregator.sumber_dana WHERE Status = "1" ')->result_array();
            for ($i=0; $i < count($G_research); $i++) {
                $temp = array();
                $temp[] = $G_research[$i]['SumberDana'];
                $ID_sumberdana = $G_research[$i]['ID'];
                for ($j=0; $j < count($arr_year); $j++) {
                    $Year_ = $arr_year[$j];
                     //$sql = 'select Judul_PKM from db_research.pengabdian_masyarakat where ID_sumberdana = ? and ID_thn_laks = ? ';
                    $sql = 'SELECT a.Judul_PKM, b.Name
                            FROM db_research.pengabdian_masyarakat AS a
                            LEFT JOIN db_employees.employees AS b ON (b.NIP = a.NIP) where a.ID_sumberdana = ? and a.ID_thn_laks = ? ';
                     $query=$this->db->query($sql, array($ID_sumberdana,$Year_))->result_array();

                     //$count = $query[0]['total'];
                     //$temp[] = $count;
                     $temp[] = $query;

                }

                $body[] = $temp;
            }
            $rs['header'] = $header;
            $rs['body'] = $body;
            return print_r(json_encode($rs));
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


            $dataForm = (array) $data_arr['dataForm'];

            $JPID = $dataForm['JPID'];
            $Year = $dataForm['Year'];

            $dataCk = $this->db->get_where('db_agregator.penggunaan_dana',array(
                'JPID' => $JPID,
                'Year' => $Year
            ))->result_array();


//            $ID = $data_arr['ID'];
            $ID = (count($dataCk)>0) ? $dataCk[0]['ID'] : '';


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
         else if($data_arr['action']=='updatePenggunaanDana_aps'){


                    $dataForm = (array) $data_arr['dataForm'];
// print_r($dataForm);die();
                    $JPID = $dataForm['JPID'];
                    $Year = $dataForm['Year'];
                    $PriceUPPS = $dataForm['PriceUPPS'];
                    $PricePS = $dataForm['PricePS'];
                    $ProdiID = $dataForm['ProdiID'];
                    $dataCk = $this->db->get_where('db_agregator.penggunaan_dana_aps',array(
                        'JPID' => $JPID,
                        'Year' => $Year,
                        'PriceUPPS' => $PriceUPPS,
                        'PricePS' => $PricePS,
                        'ProdiID' => $ProdiID,
                    ))->result_array();


        //            $ID = $data_arr['ID'];
                    $ID = (count($dataCk)>0) ? $dataCk[0]['ID'] : '';


                    if($ID!=''){
                        // Update
                        $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                        $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                        $this->db->where('ID', $ID);
                        $this->db->update('db_agregator.penggunaan_dana_aps',$dataForm);

                    } else {
                        $dataForm['EntredBy'] = $this->session->userdata('NIP');
                        $this->db->insert('db_agregator.penggunaan_dana_aps',$dataForm);
                        $ID = $this->db->insert_id();
                    }

                    return print_r(json_encode(array(
                        'ID' => $ID
                    )));

                }
         else if($data_arr['action']=='viewPenggunaanDana'){

            $Year = $data_arr['Year'];
            $Year1 = $data_arr['Year1'];
            $Year2 = $data_arr['Year2'];

            // Load Jenis P
            $dataJenis = $this->db->get('db_agregator.jenis_penggunaan')->result_array();

            $result = [];

            if(count($dataJenis)>0){


                for($i=0;$i<count($dataJenis);$i++){
                    $d = $dataJenis[$i];

                    for($y=1;$y<=3;$y++){
                        if($y==1){
                            $YearEx = $Year;
                        } else if($y==2){
                            $YearEx = $Year1;
                        } else {
                            $YearEx = $Year2;
                        }

                        $dataPD = $this->db->query('SELECT pd.* FROM db_agregator.penggunaan_dana pd
                                                  WHERE pd.Year = "'.$YearEx.'" AND pd.JPID = "'.$d['ID'].'" ')->result_array();

                        $dataJenis[$i]['th'.$y] = (count($dataPD)>0) ? $dataPD[0]['Price'] : 0;
                    }

                }

            }

            return print_r(json_encode($dataJenis));
        }
         else if($data_arr['action']=='viewPenggunaanDana_aps'){

            $Year = $data_arr['Year'];
            $Year1 = $data_arr['Year1'];
            $Year2 = $data_arr['Year2'];
            $Year3 = $data_arr['Year3'];
            $Year4 = $data_arr['Year4'];
            $Year5 = $data_arr['Year5'];
            $ProdiID = $data_arr['ProdiID'];

            // Load Jenis P
            $dataJenis = $this->db->get('db_agregator.jenis_penggunaan_aps')->result_array();

            $result = [];

            if(count($dataJenis)>0){


                for($i=0;$i<count($dataJenis);$i++){
                    $d = $dataJenis[$i];

                    for($y=1;$y<=3;$y++){
                        if($y==1){
                            $YearEx = $Year;
                        } else if($y==2){
                            $YearEx = $Year1;
                        } else {
                            $YearEx = $Year2;
                        }

                        $dataPD = $this->db->query('SELECT pd.* FROM db_agregator.penggunaan_dana_aps pd
                                                  WHERE pd.Year = "'.$YearEx.'" AND pd.JPID = "'.$d['ID'].'" and pd.ProdiID = "'.$ProdiID.'" ')->result_array();

                        $dataJenis[$i]['th'.$y] = (count($dataPD)>0) ? $dataPD[0]['PriceUPPS'] : 0;
                        //$dataJenis[$i]['th'.$y] = (count($dataPD)>0) ? $dataPD[0]['PricePS'] : 0;
                    }

                }
                for($i=0;$i<count($dataJenis);$i++){
                    $d = $dataJenis[$i];

                    for($y=4;$y<=6;$y++){
                        if($y==4){
                            $YearEx = $Year3;
                        } else if($y==5){
                            $YearEx = $Year4;
                        } else {
                            $YearEx = $Year5;
                        }

                        $dataPD = $this->db->query('SELECT pd.* FROM db_agregator.penggunaan_dana_aps pd
                                                  WHERE pd.Year = "'.$YearEx.'" AND pd.JPID = "'.$d['ID'].'" and pd.ProdiID = "'.$ProdiID.'" ')->result_array();

                        $dataJenis[$i]['th'.$y] = (count($dataPD)>0) ? $dataPD[0]['PricePS'] : 0;
                        //$dataJenis[$i]['th'.$y] = (count($dataPD)>0) ? $dataPD[0]['PricePS'] : 0;
                    }

                }

            }

            return print_r(json_encode($dataJenis));
        }
        else if($data_arr['action']=='viewPenggunaanDanaYear'){
            $data = $this->db->query('SELECT pd.Year FROM db_agregator.penggunaan_dana pd
                                                  GROUP BY pd.Year ORDER BY pd.Year DESC ')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='viewPenggunaanDanaYear_aps'){
            $data = $this->db->query('SELECT pd.Year FROM db_agregator.penggunaan_dana_aps pd
                                                  GROUP BY pd.Year ORDER BY pd.Year DESC ')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removePenggunaanDana'){

            $this->db->where('ID', $data_arr['ID']);
            $this->db->delete('db_agregator.penggunaan_dana');

            return print_r(1);

        }
        else if($data_arr['action']=='removePenggunaanDana_aps'){

            $this->db->where('ID', $data_arr['ID']);
            $this->db->delete('db_agregator.penggunaan_dana_aps');

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
        else if($data_arr['action']=='updateJenisDana_aps'){

            $ID = $data_arr['ID'];

            $dataForm = array('Jenis' => $data_arr['Jenis']);

            if($ID!=''){
                // Update

                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.jenis_penggunaan_aps',$dataForm);

            } else {
                $this->db->insert('db_agregator.jenis_penggunaan_aps',$dataForm);
                $ID = $this->db->insert_id();
            }

            return print_r(1);


        }
        else if($data_arr['action']=='viewJenisDana'){

            $data = $this->db->get('db_agregator.jenis_penggunaan')->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='viewJenisDana_aps'){

            $data = $this->db->get('db_agregator.jenis_penggunaan_aps')->result_array();
            return print_r(json_encode($data));
        }

        else if($data_arr['action']=='readYearSDNewSumberDana'){
            $data = $this->db->query('SELECT Year FROM db_agregator.perolehan_dana_2 pd
                                                      GROUP BY Year ORDER BY Year DESC')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='readNewSumberDana'){

            $dataTS = $this->db->get_where('db_agregator.perolehan_dana_2',
                array('Year' => $data_arr['Year']))->result_array();


            $y1 = (int) $data_arr['Year'] - 1;
            $dataTS1 = $this->db->get_where('db_agregator.perolehan_dana_2',
                array('Year' => $y1))->result_array();


            $y2 = (int) $data_arr['Year'] - 2;
            $dataTS2 = $this->db->get_where('db_agregator.perolehan_dana_2',
                array('Year' => $y2))->result_array();

            $result = array(
                'TS' => $dataTS,
                'TS1' => $dataTS1,
                'TS2' => $dataTS2,
            );

            return print_r(json_encode($result));
        }
        else if($data_arr['action']=='updateNewSumberDana'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];
            $Year = $dataForm['Year'];

            $result = 0;
            if($ID!=''){
                // Update
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['updatedAt'] = $this->m_rest->getDateTimeNow();

                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.perolehan_dana_2',$dataForm);
                $result = 1;
            } else {
                // Cek apakah tahun sudah pernah di input atau blm;
                $dataY = $this->db->get_where('db_agregator.perolehan_dana_2',array(
                    'Year' => $Year
                ))->result_array();

                if(count($dataY)<=0){
                    $dataForm['EntredBy'] = $this->session->userdata('NIP');
                    $dataForm['EntredAt'] = $this->m_rest->getDateTimeNow();
                    $this->db->where('ID', $ID);
                    $this->db->insert('db_agregator.perolehan_dana_2',$dataForm);
                    $result = 1;
                }

            }

            return print_r($result);
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
                $this->db->update('db_studentlife.student_achievement',$dataForm);

                $this->db->reset_query();

                $this->db->where('SAID', $ID);
                $this->db->delete('db_studentlife.student_achievement_student');

            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $dataForm['EntredAt'] = $this->m_rest->getDateTimeNow();
//                $this->db->insert('db_agregator.prestasi_mahasiswa',$dataForm);
                $this->db->insert('db_studentlife.student_achievement',$dataForm);
                $ID = $this->db->insert_id();
            }

            // Add Student
            $dataListStudent = (array) $data_arr['dataListStudent'];

            if(count($dataListStudent)>0){
                for($i=0;$i<count($dataListStudent);$i++){
                    $d = (array) $dataListStudent[$i];
                    $arr = array(
                        'SAID' => $ID,
                        'NPM' => $d['NPM']
                    );
                    $this->db->insert('db_studentlife.student_achievement_student',$arr);
                }
            }


            return print_r(1);
        }
        else if($data_arr['action']=='viewDataPAM'){

            $data = $this->db->query('SELECT * FROM db_studentlife.student_achievement ORDER BY Year, StartDate DESC')->result_array();

            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    $ID = $data[$i]['ID'];
                    $data[$i]['DataStudent'] = $this->db->query('SELECT sas.*, ats.Name FROM db_studentlife.student_achievement_student sas
                                                            LEFT JOIN db_academic.auth_students ats ON (ats.NPM = sas.NPM)
                                                            WHERE sas.SAID = "'.$ID.'" ')->result_array();
                }
            }

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='viewDataPAM_APS'){
                    $ProdiID = $data_arr['ProdiID'];
                    $data = $this->db->query('SELECT sa.* FROM db_studentlife.student_achievement as sa
                        
                        JOIN db_studentlife.student_achievement_student as sas on sas.SAID = sa.ID 
                        JOIN db_academic.auth_students as aus on sas.NPM = aus.NPM

                        WHERE aus.ProdiID = '.$ProdiID.'   
                        ORDER BY sa.Year, sa.StartDate DESC')->result_array();

                    if(count($data)>0){
                        for($i=0;$i<count($data);$i++){
                            $ID = $data[$i]['ID'];
                            $data[$i]['DataStudent'] = $this->db->query('SELECT sas.*, ats.Name FROM db_studentlife.student_achievement_student sas
                                                                    LEFT JOIN db_academic.auth_students ats ON (ats.NPM = sas.NPM)
                                                                    WHERE sas.SAID = "'.$ID.'" ')->result_array();
                        }
                    }

                    return print_r(json_encode($data));

                }

        else if($data_arr['action']=='updateLamaStudy'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            $year = $dataForm['Year'];
            $ID_programpendik = $dataForm['ID_programpendik'];

            $squery = 'SELECT * FROM db_agregator.lama_studi_mahasiswa WHERE ID_programpendik = "'.$ID_programpendik.'" AND Year = "'.$year.'" ';
            $dataTable =$this->db->query($squery, array())->result_array();

            if(count($dataTable)>0){
                return print_r(0);
            }
            else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_agregator.lama_studi_mahasiswa',$dataForm);
                return print_r(1);
            }
        }

        else if($data_arr['action']=='update_study') {

            $dataForm = (array) $data_arr['dataForm'];
            $year = $dataForm['Year'];
            $ID_programpendik = $dataForm['ID_programpendik'];

            $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
            $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
            $this->db->where('Year', $year);
            $this->db->where('ID_programpendik', $ID_programpendik);
            $this->db->update('db_agregator.lama_studi_mahasiswa',$dataForm);
            return print_r(1);
        }

        else if($data_arr['action']=='viewPAM'){

            $data = $this->db->get_where('db_studentlife.student_achievement', array(
                'Type' => $data_arr['Type']
            ))->result_array();
            return print_r(json_encode($data));

            exit;

            $data = $this->db->get_where('db_agregator.prestasi_mahasiswa', array(
                'Type' => $data_arr['Type']
            ))->result_array();
            return print_r(json_encode($data));

        }

        else if($data_arr['action']=='viewLamaStudyold'){

            $year = date('Y');
            $arr_year = array();
            for ($i=0; $i < 3; $i++) {
                $arr_year[] = $year - $i;
            }
            $data = $this->db->query('SELECT a.ID,a.ID_programpendik, b.ID AS IDPrograms, b.NamaProgramPendidikan
                    FROM db_agregator.lama_studi_mahasiswa AS a
                    INNER JOIN db_agregator.program_pendidikan AS b ON (a.ID_programpendik = b.ID) Group by  a.ID_programpendik  order by a.ID_programpendik asc,a.Year desc ')->result_array();
            for ($i=0; $i < count($data); $i++) {
                for ($j=0; $j < count($arr_year); $j++) {
                   $sql = 'select * from db_agregator.lama_studi_mahasiswa where ID_programpendik = '.$data[$i]['ID_programpendik'].' and Year = '.$arr_year[$j];
                   $query=$this->db->query($sql, array())->result_array();
                   if (count($query) > 0) {
                       $data[$i]['Jumlah_lulusan_'.$arr_year[$j]] = $query[0]['Jumlah_lulusan'];
                       $data[$i]['Jumlah_masa_studi_'.$arr_year[$j]] = $query[0]['Jumlah_masa_studi'];
                   }
                   else
                   {
                    $data[$i]['Jumlah_lulusan_'.$arr_year[$j]] = 0;
                    $data[$i]['Jumlah_masa_studi_'.$arr_year[$j]] = 0;
                   }

                   $data[$i]['Year'] = $arr_year[$j];
                }
            }

            return print_r(json_encode($data));
        }

        else if($data_arr['action']=='viewLamaStudy'){

            $rs = array('header' => array(),'body' => array() );
            $header = array('No','Program Pendidikan');
            // dapatkan 3 tahun belakang
            $Year = date('Y');
            $Year3 = $Year - 2;
            for ($i=$Year; $i >= $Year3; $i--) {
                $header[] = (int)$i;
            }

            for ($i=$Year; $i >= $Year3; $i--) {
                $header[] = (int)$i;
            }
            $rs['header'] = $header;

            $ProgramPendidikan = array(
                "Doktor/ Doktor Terapan/ Subspesialis",
                "Magister/ Magister Terapan/ Spesialis",
                "Profesi 1 Tahun",
                "Profesi 2 Tahun",
                "Sarjana/ Diploma Empat/ Sarjana Terapan", // indeks 4 search ke database
                "Diploma Tiga",
                "Diploma Dua",
                "Diploma Satu",
            );

            $body = array();

            for ($i=0; $i < count($ProgramPendidikan); $i++) {
                // define temp default
                $temp = array();
                $temp[] = array('show' => $ProgramPendidikan[$i] ,'data' => '');
                if ($i == 4) {
                    for ($j=2; $j < count($header); $j++) {
                        $get_tayear = $header[$j];
                        if ($j <= 4) { // Jumlah Lulusan pada by Year
                            $sql = 'select count(*) as total from db_academic.auth_students where GraduationYear = "'.$get_tayear.'" and StatusStudentID = ?';
                            $query=$this->db->query($sql, array(1))->result_array();
                            // get data detail
                            $sql1 = 'select NPM,Name from db_academic.auth_students where GraduationYear = "'.$get_tayear.'" and StatusStudentID = ?';
                            $query1=$this->db->query($sql1, array(1))->result_array();
                            // encode token
                            $token = $this->jwt->encode($query1,"UAP)(*");
                            $temp[] = array('show' => $query[0]['total'] ,'data' => $token); // Jumlah PS
                        }
                        else // Rata-rata Masa Studi Lulusan pada
                        {
                            $arr_temp = [];
                            $sql = 'select NPM,Year,GraduationYear from db_academic.auth_students where GraduationYear = "'.$get_tayear.'" and StatusStudentID = ?';
                            $query=$this->db->query($sql, array(1))->result_array();
                            if (count($query) > 0 ) {
                                for ($k=0; $k < count($query); $k++) {
                                   $Co = $query[$k]['GraduationYear'] - $query[$k]['Year'];
                                   $arr_temp[] = $Co;
                                }

                                $rata_rata = array_sum($arr_temp)/count($arr_temp);
                                $temp[] = $rata_rata;
                            }
                            else
                            {
                                $temp[] = 0;
                            }

                        }
                    }
                }
                else
                {
                    for ($j=2; $j < count($header); $j++) {
                        // $temp[] = 0;
                        if ($j <= 4) { 
                            $temp[] = array('show' => 0 ,'data' => '');
                        }
                        else
                        {
                            $temp[] = 0;
                        }
                        
                    }
                }
                $body[] = $temp;
            }
            $rs['body'] = $body;
            return print_r(json_encode($rs));
        }

        else if($data_arr['action']=='getloopdatastudy'){

            $data = $this->db->query('SELECT a.* FROM db_agregator.program_pendidikan AS a')->result_array();
            return print_r(json_encode($data));

        }

        else if($data_arr['action']=='getPAMByID'){
            $ID = $data_arr['ID'];
            $dataAch = $this->db->get_where('db_studentlife.student_achievement',array('ID' => $ID))->result_array();

            $dataAchStd = $this->db->query('SELECT sas.NPM,ats.Name FROM db_studentlife.student_achievement_student sas
                                                        LEFT JOIN db_academic.auth_students ats ON (sas.NPM = ats.NPM)
                                                        WHERE sas.SAID = "'.$ID.'" ORDER BY ats.Name')->result_array();

            $arr = array(
                'dataAch' => $dataAch,
                'dataAchStd' => $dataAchStd
            );

            return print_r(json_encode($arr));
        }

        else if($data_arr['action']=='removePAM'){


            $this->db->where('SAID', $data_arr['ID']);
            $this->db->delete('db_studentlife.student_achievement_student');
            $this->db->reset_query();

            $this->db->where('ID', $data_arr['ID']);
            $this->db->delete('db_studentlife.student_achievement');
            return print_r(1);

        }
        else if($data_arr['action']=='viewIPK'){
            // error_reporting(0);
            $rs = array('header' => array(),'body' => array() );
            $header = array('No','Program Pendidikan','');
            /*
                array 3 awal yang di insert adalah Jumlah Lulusan pada
                array 3 setelah itu yang di insert adalah Rata-rata IPK Lulusan pada

            */
            // dapatkan 3 tahun belakang
            $Year = date('Y');
            $Year3 = $Year - 2;
            for ($i=$Year; $i >= $Year3; $i--) {
                $header[] = (int)$i;
            }

            for ($i=$Year; $i >= $Year3; $i--) {
                $header[] = (int)$i;
            }
            $rs['header'] = $header;

            $ProgramPendidikan = array(
                "Doktor/ Doktor Terapan/ Subspesialis",
                "Magister/ Magister Terapan/ Spesialis",
                "Profesi 1 Tahun",
                "Profesi 2 Tahun",
                "Sarjana/ Diploma Empat/ Sarjana Terapan", // indeks 4 search ke database
                "Diploma Tiga",
                "Diploma Dua",
                "Diploma Satu",
            );

            $body = array();
            for ($i=0; $i < count($ProgramPendidikan); $i++) {
                // define temp default
                $temp = array();
                // $temp[] = $ProgramPendidikan[$i];
                $temp[] = array('show' => $ProgramPendidikan[$i] ,'data' => '');
                if ($i == 4) {
                   for ($j=2; $j < count($header); $j++) {
                       if ($j == 2) {
                           $sql = 'select count(*) as total from db_academic.program_study where Status = 1 and EducationLevelID in(3,9)';
                           $query=$this->db->query($sql, array())->result_array();
                           // get data detail
                           $sql1 = 'select * from db_academic.program_study where Status = 1 and EducationLevelID in(3,9)';
                           $query1=$this->db->query($sql1, array())->result_array();
                           // encode token
                           $token = $this->jwt->encode($query1,"UAP)(*");
                           $temp[] = array('show' => $query[0]['total'] ,'data' => $token);
                           // $temp[] = $query[0]['total']; // Jumlah PS
                           continue;
                       }
                       else
                       {
                            if ($j <= 5) { // pembeda Jumlah Lulusan pada dan Rata-rata IPK Lulusan pada
                               $get_tayear = $header[$j]; // ex : 2014
                               $sql = 'select count(*) as total from db_academic.auth_students where GraduationYear = "'.$get_tayear.'" and StatusStudentID = ?';
                               $query=$this->db->query($sql, array(1))->result_array();
                               // get data detail
                               $sql1 = 'select NPM,Name from db_academic.auth_students where GraduationYear = "'.$get_tayear.'" and StatusStudentID = ?';
                               $query1=$this->db->query($sql1, array(1))->result_array();
                               // encode token
                               $token = $this->jwt->encode($query1,"UAP)(*");
                               // $temp[] = $query[0]['total']; // Jumlah PS
                               $temp[] = array('show' => $query[0]['total'] ,'data' => $token);
                            }
                            else // pembeda Jumlah Lulusan pada dan Rata-rata IPK Lulusan pada
                            {
                                // cari NPM dulu yg lulusan
                                $get_tayear = $header[$j];
                                $sql = 'select NPM,Year from db_academic.auth_students where GraduationYear = "'.$get_tayear.'" and StatusStudentID = 1';
                                $query=$this->db->query($sql, array())->result_array();
                                $GradeValueCredit = 0;
                                $Credit = 0;
                                $IPK = 0;
                                for ($k=0; $k < count($query); $k++) {
                                    $ta = 'ta_'.$query[$k]['Year'];
                                    $NPM = $query[$k]['NPM'];
                                    $sql1 = 'select * from '.$ta.'.study_planning where NPM = ?';
                                    // print_r($sql1);
                                    $query1=$this->db->query($sql1, array($NPM))->result_array();
                                    for ($l=0; $l < count($query1); $l++) {
                                        $GradeValue = $query1[$l]['GradeValue'];
                                        $CreditSub = $query1[$l]['Credit'];
                                        $GradeValueCredit = $GradeValueCredit + ($GradeValue * $CreditSub);
                                        $Credit = $Credit + $CreditSub;
                                    }
                                }

                                $IPK = ($Credit == 0) ? 0 : $GradeValueCredit / $Credit;
                                // $temp[] = $IPK;
                                $temp[] = array('show' => $IPK ,'data' => '');
                            }

                       }
                   }
                }
                else
                {
                    for ($j=2; $j < count($header); $j++) {
                        // $temp[] = 0;
                        $temp[] = array('show' => 0 ,'data' => '');
                    }
                }
                $body[] = $temp;
            }

            $rs['body'] = $body;

            return print_r(json_encode($rs));

        }
        else if($data_arr['action']=='getprogrampendik'){
            $data = $this->db->query('SELECT ID, NamaProgramPendidikan FROM db_agregator.program_pendidikan')->result_array();
            return print_r(json_encode($data));
        }

        else if($data_arr['action']=='getpenilaian'){
            $data = $this->db->query('SELECT *  FROM db_agregator.aspek_penilaian')->result_array();
            return print_r(json_encode($data));
        }

        else if($data_arr['action']=='yearstudy'){
            $data = $this->db->query('SELECT ID, Year FROM db_academic.curriculum')->result_array();
            return print_r(json_encode($data));
        }

        else if($data_arr['action']=='get_years') {

            if (count($data_arr) > 0) {

                $filterAwaltahun = $data_arr['filterAwaltahun'];
                $data = $this->db->query('SELECT ID, YEAR FROM db_academic.curriculum WHERE YEAR > "'.$data_arr['filterAwaltahun'].'" LIMIT 4')->result_array();
                return print_r(json_encode($data));
            }

        }

        else if($data_arr['action']=='readPublikasiIlmiah'){

            $rs = array('header' => array(),'body' => array() );
            $Year = date('Y');
            $Year3 = $Year - 2;
            $arr_year = array();
            for ($i=$Year; $i >= $Year3; $i--) {
                $arr_year[] = $i;
            }
            $header = $arr_year;
            // print_r($arr_year);
            $body = array();
            $G_research = $this->db->query('SELECT * FROM db_research.jenis_forlap_publikasi')->result_array();
            for ($i=0; $i < count($G_research); $i++) {
                $temp = array();
                $temp[] = $G_research[$i]['NamaForlap_publikasi'];
                $ID_sumberdana = $G_research[$i]['ID'];
                for ($j=0; $j < count($arr_year); $j++) {
                    $Year_ = $arr_year[$j];
                     //$sql = 'select Judul_litabmas from db_research.litabmas where ID_sumberdana = ? and ID_thn_laks = ? ';
                     $sql = 'SELECT a.Judul, a.Tgl_terbit, b.Name
                            FROM db_research.publikasi AS a
                            LEFT JOIN db_employees.employees AS b ON (b.NIP = a.NIP) 
                            WHERE a.ID_forlap_publikasi = ? and YEAR(a.Tgl_terbit) = ? ';
                     $query=$this->db->query($sql, array($ID_sumberdana,$Year_))->result_array();
                     $temp[] = $query;
                }

                $body[] = $temp;

            }
            $rs['header'] = $header;
            $rs['body'] = $body;
            return print_r(json_encode($rs));
        }

        //Waktu Tunggu lulusan
        else if($data_arr['action']=='saveWTL') {

            $dataForm = (array) $data_arr['dataForm'];

            $dataForm['EntredBy'] = $this->session->userdata('NIP');
            $this->db->insert('db_agregator.waktu_tunggu_lulusan',$dataForm);
            //$ID = $this->db->insert_id();
            return print_r(1);
        }

        else if($data_arr['action']=='update_waktu_tunggu') {

            $dataForm = (array) $data_arr['dataForm'];
            $year = $dataForm['Year'];
            $ID_programpendik = $dataForm['ID_programpendik'];

            $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
            $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
            $this->db->where('Year', $year);
            $this->db->where('ID_programpendik', $ID_programpendik);
            $this->db->update('db_agregator.waktu_tunggu_lulusan',$dataForm);
            return print_r(1);
        }

        else if($data_arr['action']=='viewWaktuTunggu'){
            $year = date('Y');
            $arr_year = array();
            for ($i=0; $i < 3; $i++) {
                $arr_year[] = $year - $i;
            }
            $data = $this->db->query('SELECT a.ID,a.ID_programpendik, b.ID AS IDPrograms, b.NamaProgramPendidikan
                    FROM db_agregator.waktu_tunggu_lulusan AS a
                    INNER JOIN db_agregator.program_pendidikan AS b ON (a.ID_programpendik = b.ID) Group by  a.ID_programpendik  order by a.ID_programpendik asc,a.Year desc ')->result_array();
            for ($i=0; $i < count($data); $i++) {
                for ($j=0; $j < count($arr_year); $j++) {
                   $sql = 'select * from db_agregator.waktu_tunggu_lulusan where ID_programpendik = '.$data[$i]['ID_programpendik'].' and Year = '.$arr_year[$j];
                   $query=$this->db->query($sql, array())->result_array();

                   if (count($query) > 0) {
                       $data[$i]['Masa_tunggu_'.$arr_year[$j]] = $query[0]['Masa_tunggu'];
                       //$data[$i]['Jumlah_masa_studi_'.$arr_year[$j]] = $query[0]['Jumlah_masa_studi'];
                   }
                   else
                   {
                    $data[$i]['Masa_tunggu_'.$arr_year[$j]] = 0;
                    //$data[$i]['Jumlah_masa_studi_'.$arr_year[$j]] = 0;
                   }

                   $data[$i]['Year'] = $arr_year[$j];
                }
            }
            return print_r(json_encode($data));
        }

        // Kesesuaian bidang kerja lulusan
        else if($data_arr['action']=='saveKBKL') {

            $dataForm = (array) $data_arr['dataForm'];

            $year = $dataForm['Year'];
            $ID_programpendik = $dataForm['ID_programpendik'];

            $squery = 'SELECT * FROM db_agregator.kesesuaian_bidang_kerja WHERE ID_programpendik = "'.$ID_programpendik.'" AND Year = "'.$year.'" ';
            $dataTable =$this->db->query($squery, array())->result_array();

            if(count($dataTable)>0){
                return print_r(0);
            }
            else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_agregator.kesesuaian_bidang_kerja',$dataForm);
                return print_r(1);
            }
        }

        else if($data_arr['action']=='viewKesesuaian'){
            $year = date('Y');
            $arr_year = array();
            for ($i=0; $i < 3; $i++) {
                $arr_year[] = $year - $i;
            }
            $data = $this->db->query('SELECT a.ID, a.ID_programpendik, b.ID AS IDPrograms, b.NamaProgramPendidikan
                    FROM db_agregator.kesesuaian_bidang_kerja AS a
                    INNER JOIN db_agregator.program_pendidikan AS b ON (a.ID_programpendik = b.ID) Group by  a.ID_programpendik  order by a.ID_programpendik asc,a.Year desc ')->result_array();
            for ($i=0; $i < count($data); $i++) {
                for ($j=0; $j < count($arr_year); $j++) {
                   $sql = 'select * from db_agregator.kesesuaian_bidang_kerja where ID_programpendik = '.$data[$i]['ID_programpendik'].' and Year = '.$arr_year[$j];
                   $query=$this->db->query($sql, array())->result_array();

                   if (count($query) > 0) {
                       $data[$i]['Persentase_'.$arr_year[$j]] = $query[0]['Persentase'];
                       //$data[$i]['Jumlah_masa_studi_'.$arr_year[$j]] = $query[0]['Jumlah_masa_studi'];
                   }
                   else
                   {
                    $data[$i]['Persentase_'.$arr_year[$j]] = 0;
                    //$data[$i]['Jumlah_masa_studi_'.$arr_year[$j]] = 0;
                   }

                   $data[$i]['Year'] = $arr_year[$j];
                }
            }
            return print_r(json_encode($data));
        }

    //Teknologi Produk Karya
        else if($data_arr['action']=='save_tekno_produk') {

            $dataForm = (array) $data_arr['dataForm'];
            $dataForm['EntredBy'] = $this->session->userdata('NIP');
            $this->db->insert('db_agregator.teknologi_produk_karya',$dataForm);
            return print_r(1);
        }

    //HKI Desain Produk
        else if($data_arr['action']=='save_hki_produk') {

            $dataForm = (array) $data_arr['dataForm'];
            $dataForm['EntredBy'] = $this->session->userdata('NIP');
            $this->db->insert('db_agregator.hki_desain_produk',$dataForm);
            return print_r(1);
        }

     //HKI Desain Produk
        else if($data_arr['action']=='save_hki_paten') {

            $dataForm = (array) $data_arr['dataForm'];
            $dataForm['EntredBy'] = $this->session->userdata('NIP');
            $this->db->insert('db_agregator.hki_paten_sederhana',$dataForm);
            return print_r(1);
        }

     //HKI Desain Produk
        else if($data_arr['action']=='save_sitasi_karya') {

            $dataForm = (array) $data_arr['dataForm'];
            $dataForm['EntredBy'] = $this->session->userdata('NIP');
            $this->db->insert('db_agregator.sitasi_karya',$dataForm);
            return print_r(1);
        }
        else if ($data_arr['action'] == 'viewRasioKelulusanTepatWaktuDanRasioKeberhasilanStudi') {
            $rs = array();
            $TA = $data_arr['TA'];
            // header table 7 tahun dari TA
            $UntilYear = $TA + 7;
            $te = [];
            for ($i=$TA; $i <= $UntilYear; $i++) { 
                $te[]= $i;
            }

            $Lte = $i - 1; // last year 
           
            $header = [
                   ['Name' => 'Data','Rowspan' => 2,'Colspan' => 1,'Sub' => [] ], 
                   ['Name' => 'Jumlah Mahasiswa per Angkatan pada Tahun','Rowspan' => 1,'Colspan' => 8,'Sub' => $te ], 
                   ['Name' => 'Total','Colspan' => 1,'Sub' => [] , 'Rowspan' => 2 ], 
            ];

            // show data per prodi in the table
            // $_data = ['Existing','Lulus','Ratio Tepat Waktu','Ratio Keberhasilan Studi'];
            $_data = ['Existing','Lulus','Persentase Ratio'];
            $G_prodi = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
            $sql_prodi = 'select a.*,b.MasaStudi from db_academic.program_study as a join db_academic.education_level as b on a.EducationLevelID =  b.ID where a.Status = 1';
            $G_prodi = $this->db->query($sql_prodi, array())->result_array();

            /*
                    Ratio Tepat waktu yaitu 4 tahun
            */
            $dt = [];
            for ($i=0; $i < count($G_prodi); $i++) {
                $ProdiID = $G_prodi[$i]['ID'];
                $MasaStudi = $G_prodi[$i]['MasaStudi'];
                $IndexMS = $MasaStudi; // array 1 adalah isian data lulus,existing etc
                // get all std ta by ProdiID tanpa status std
                $sql = 'select count(*) as total from (
                        select ID from db_academic.auth_students 
                        where Year = '.$TA.' and ProdiID = '.$ProdiID.'
                    )xx';
                $query =$this->db->query($sql, array())->result_array();
                $ExistingAwal = $query[0]['total'];
                $_dt = array(
                    'header' => $header,
                    'ProdiID' => $ProdiID,
                    'MasaStudi' => $MasaStudi,
                    'ProdiName' => $G_prodi[$i]['Name'],
                );
                $_getdt = [];
                $_ex = [];
                $_lu = [];
                for ($j=0; $j < count($_data); $j++) { 
                    $temp = [];
                    switch ($j) {
                        case 0: // existing
                            $temp[] = $_data[$j];
                            $TotalHor = 0;
                            // get 7 tahun ration
                            for ($k=0; $k < count($te); $k++) { 
                                if ($k == 0) {
                                  $temp[] = $ExistingAwal; 

                                }
                                else
                                {
                                    $ss = [];
                                    $Yte = $te[$k]+1;

                                    for ($z=$Yte; $z <= $Lte; $z++) { 
                                        $ss[] =(string)$z;
                                    }

                                    $q_add = '';
                                    $sqlAdd = '';
                                    if (count($ss) > 0 ) {
                                        $q_add = implode(',', $ss);
                                        $q_add = ' and GraduationYear in('.$q_add.')';
                                        $sqlAdd = ' UNION 
                                                    select ID from db_academic.auth_students
                                                    where Year = '.$TA.$q_add.' and GraduationYear is not NULL and  GraduationYear != "" 
                                                    and ProdiID = '.$ProdiID.'
                                                  ';
                                    }

                                    $sqlYear = 'select count(*) as total from (
                                                select ID from db_academic.auth_students
                                                where Year = '.$TA.' and ( GraduationYear IS NULL  or GraduationYear = "" )
                                                and ProdiID = '.$ProdiID.'
                                                '.$sqlAdd.'
                                            )xx ';
                                          
                                    $queryYear =$this->db->query($sqlYear, array())->result_array();

                                    $temp[] = $queryYear[0]['total'];
                                    $_ex[] = $queryYear[0]['total']; // get existing
                                    $TotalHor =  0; 
                                }
                            }
                            $_ex[] = 0; // get existing
                            $temp[] = $TotalHor;
                            break;
                        case 1: // Lulus
                            $temp[] = $_data[$j];
                            $TotalHor = 0;
                            // get 7 tahun ration
                           for ($k=0; $k < count($te); $k++) { 
                            $Yte = $te[$k];
                            $sqlYear = 'select count(*) as total from 
                                             (
                                                 select ID from db_academic.auth_students
                                                 where Year = '.$TA.'
                                                 and ProdiID = '.$ProdiID.'
                                                 and GraduationYear = '.$Yte.'
                                             )xx
                                        ';
                            $queryYear =$this->db->query($sqlYear, array())->result_array();
                            $temp[] = $queryYear[0]['total'];
                            $_lu[] =  $queryYear[0]['total'];
                            $TotalHor +=  $queryYear[0]['total']; 
                           }
                           $_lu[] = 0;
                           $temp[] = $TotalHor;
                            break;
                        case 2: //  Persentase Ratio
                            $temp[] = $_data[$j];
                            for ($k=0; $k < count($te); $k++) {
                                if ($k == $IndexMS) {
                                    if ($ExistingAwal == 0) {
                                        $temp[] = 0;
                                    }
                                    else
                                    {
                                         $lulus = ($_lu[($k)] / $ExistingAwal) * 100;
                                         // > 50 = 4 && > 50 = 0
                                         // if ($lulus > 50) {
                                         //     $temp[] = 4;
                                         // }
                                         // else
                                         // {
                                         //    $temp[] = 0;
                                         // }
                                         $temp[] = $lulus;
                                    }
                                    
                                }
                                else
                                {
                                    $temp[] = 0;
                                } 
                            }

                            $temp[] = 0;
                            break;
                        default:
                            # code...
                            break;
                    } // end switch

                    $_getdt[] = $temp;
                }

                $_dt['data'] = $_getdt; // add variable year
                $dt[] = $_dt; // insert ke table untuk body
            }

            $rs = $dt;
            return print_r(json_encode($rs));
        }

        // Table refrensi
        else if($data_arr['action']=='readTableRef'){

            $Year = (int) $data_arr['Year'];

            $dataEd = $this->db->query('SELECT el.ID, el.Name, el.Description FROM db_academic.education_level el')->result_array();

            if(count($dataEd)>0){
                for($j=0;$j<count($dataEd);$j++){

                    for($i=0;$i<=2;$i++){
                        $Year_where = $Year - $i;
                        $dataEd[$j]['BL_'.$Year_where] = $this->db->query('SELECT ats.NPM, ats.Name, ats.GraduationYear, ps.Name AS Prodi 
                                          FROM db_academic.auth_students ats
                                          LEFT JOIN db_academic.program_study ps ON (ps.ID = ats.ProdiID) 
                                          WHERE ats.GraduationYear = "'.$Year_where.'" 
                                          AND ps.EducationLevelID = "'.$dataEd[$j]['ID'].'"
                                           ORDER BY ats.NPM')->result_array();

//                        $data = $this->db->query('SELECT * FROM db_studentlife.alumni_experience')->result_array();

                    }



                }
            }


            return print_r(json_encode($dataEd));



        }

    }


    public function getsum_mahasiswa_asing() {

        $year = date('Y');
        $arr_year = array();
            for ($i=0; $i < 4; $i++) {
                $arr_year[] = $year - $i;
        }
        //print_r($arr_year); exit();

        $Status = $this->input->get('s');

        $data = $this->db->select('ID, Code, Name')->get_where('db_academic.program_study',array(
            'Status' => 1
        ))->result_array();
         $dataMhs = $this->db->query('SELECT a.*, b.Name
                    FROM db_agregator.student_selection_foreign AS a
                    LEFT JOIN db_academic.program_study AS b ON (a.ProdiID = b.ID)
                    WHERE b.Status = 1 ')->result_array();

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){

                for ($j=0; $j < count($arr_year); $j++) {

                    $dataMhs = $this->db->query('SELECT COUNT(*) AS Total FROM db_agregator.student_selection_foreign
                                          WHERE Year = '.$arr_year[$j].' AND ProdiID = "'.$data[$i]['ID'].'" ')->result_array();
                    //print_r($dataMhs); exit();

                    if (count($dataMhs) > 0) {
                        $data[$i]['Tahunmasuk_'.$arr_year[$j]] = $arr_year[$j];
                        $data[$i]['NameProdi'] = $data[$i]['Name'];
                        $data[$i]['TotalStudent_'.$arr_year[$j]] = $dataMhs[0]['Total'];
                    }


                //========================
                //$and2 = ($Status!='all') ? ' AND StatusForlap = "'.$Status.'" ' : '';
                // Total Mahasiswa
                //$dataMhs = $this->db->query('SELECT COUNT(*) AS Total FROM db_academic.auth_students
               //                           WHERE Status = "1" AND ProdiID = "'.$data[$i]['ID'].'"  '.$and2)->result_array();
               // $data[$i]['TotalMahasiwa'] = $dataMhs[0]['Total'];

                 // Total Lectrure
                //$dataEmp = $this->db->query('SELECT COUNT(*) AS Total FROM db_employees.employees
                //                          WHERE ProdiID = "'.$data[$i]['ID'].'"  '.$and2)->result_array();
                //$data[$i]['TotalLecturer'] = $dataEmp[0]['Total'];

                }
            }
        }

        return print_r(json_encode($data));

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

                    $dataDetails = $this->db->query('SELECT em.NIP,  em.NUP, em.NIDN, em.NIDK, em.Name FROM db_employees.employees em
                                                                            WHERE em.ProdiID = "'.$data[$i]['ID'].'"
                                                                            AND em.LevelEducationID = "'.$dataLAP[$j]['ID'].'"
                                                                            AND ( em.StatusForlap = "1" OR em.StatusForlap = "2" ) ')->result_array();

                    $r = array('Level' => $dataLAP[$j]['Description'], 'Details' => $dataDetails);
                    $data[$i]['dataLecturers'][$j] = $r;
                }


                $dataL = $this->db->query('SELECT em.NIP,  em.NUP, em.NIDN, em.NIDK, em.Name FROM db_employees.employees em
                                                                    WHERE em.ProdiID = "'.$data[$i]['ID'].'"
                                                                    AND em.Profession <> ""
                                                                    AND ( em.StatusForlap = "1" OR em.StatusForlap = "2" ) ')->result_array();
                $r = array('Level' => 'Profesi', 'Details' => $dataL);
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
                    $dataEmp = $this->db->query('SELECT em.NIP, em.NUP, em.NIDN, em.NIDK, em.Name FROM db_employees.employees em
                                                                    WHERE em.LevelEducationID = "'.$data[$i]['ID'].'"
                                                                    AND em.LecturerAcademicPositionID = "'.$dataPosition[$p]['ID'].'"
                                                                     AND (em.StatusForlap = "1" || em.StatusForlap = "2") ')->result_array();

                    $r = array(
                        'Position' => $dataPosition[$p]['Position'],
                        'dataEmployees' => $dataEmp
                    );

                    $data[$i]['details'][$p] = $r;
                }


                $dataEmp = $this->db->query('SELECT em.NIP, em.NUP, em.NIDN, em.NIDK, em.Name FROM db_employees.employees em
                                                                    WHERE em.LevelEducationID = "'.$data[$i]['ID'].'"
                                                                    AND em.LecturerAcademicPositionID NOT IN (SELECT ID FROM db_employees.lecturer_academic_position)
                                                                     AND (em.StatusForlap = "1" || em.StatusForlap = "2") ')->result_array();

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
                    $dataEmp = $this->db->query('SELECT em.NIP, em.NUP, em.NIDN, em.NIDK, em.Name FROM db_employees.employees em
                                                                    WHERE em.LevelEducationID = "'.$data[$i]['ID'].'"
                                                                    AND em.LecturerAcademicPositionID = "'.$dataPosition[$p]['ID'].'"
                                                                     AND em.StatusForlap = "0" ')->result_array();

                    $r = array(
                        'Position' => $dataPosition[$p]['Position'],
                        'dataEmployees' => $dataEmp
                    );

                    $data[$i]['details'][$p] = $r;
                }

                $dataEmp = $this->db->query('SELECT em.NIP, em.NUP, em.NIDN, em.NIDK, em.Name FROM db_employees.employees em
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

    public function getLecturerCertificate() {

        $data = $this->db->select('ID, Code, Name')->get_where('db_academic.program_study',array(
            'Status' => 1
        ))->result_array();

        if(count($data)>0){
            for($i=0;$i<count($data);$i++){

                // Total Employees
                $dataEmp = $this->db->query('SELECT em.NIP, em.NUP, em.NIDN, em.NIDK, em.Name FROM db_employees.employees em
                                          WHERE em.ProdiID = "'.$data[$i]['ID'].'"
                                          AND (em.StatusForlap = "1" || em.StatusForlap = "2")  ')->result_array();

                $data[$i]['TotalLecturer'] = $dataEmp;

                $dataEmpCerti = $this->db->query('SELECT em.NIP, em.NUP, em.NIDN, em.NIDK, em.Name FROM db_employees.employees em
                                          WHERE em.ProdiID = "'.$data[$i]['ID'].'" AND em.Serdos="1"
                                          AND (em.StatusForlap = "1" || em.StatusForlap = "2")  ')->result_array();


                $data[$i]['TotalLecturerCertifies'] = $dataEmpCerti;

            }
        }

        return print_r(json_encode($data));
    }


    public function getRasioDosenMahasiswa() {

        $SemesterID = $this->input->get('smt');
        $Year = $this->input->get('y');

        $data = $this->db->select('ID, Code, Name')->get_where('db_academic.program_study',array(
            'Status' => 1
        ))->result_array();

        if(count($data)>0){


            for($i=0;$i<count($data);$i++){



                // Total Mahasiswa
                $dataMhs = $this->db->query('SELECT ats.NPM, ats.Name, ss.Description FROM db_academic.auth_students ats
                                          LEFT JOIN db_academic.status_student ss ON (ss.ID = ats.StatusStudentID)
                                          WHERE ats.StatusStudentID = "3" AND ats.ProdiID = "'.$data[$i]['ID'].'"
                                          AND ats.Year <= "'.$Year.'"
                                          ORDER BY ats.Year, ats.NPM ASC ')->result_array();

                $data[$i]['dataMahasiwa'] = $dataMhs;


                $dataTA = $this->db->query('SELECT ats.NPM, ats.Name, ss.Description FROM db_academic.std_study_planning ssp
                                                    LEFT JOIN db_academic.auth_students ats ON (ats.NPM = ssp.NPM)
                                                    LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = ssp.MKID)
                                                    LEFT JOIN db_academic.status_student ss ON (ss.ID = ats.StatusStudentID)
                                                    WHERE ssp.SemesterID = "'.$SemesterID.'"
                                                    AND ats.ProdiID = "'.$data[$i]['ID'].'"
                                                     AND ats.Year <= "'.$Year.'"
                                                     AND mk.Yudisium = "1"')->result_array();

                $data[$i]['dataMahasiwaTA'] = $dataTA;


                //
                if($SemesterID>=13){

                    $dataSchedule = $this->db->query('SELECT sc.Coordinator AS NIP, em.NUP, em.NIDN, em.NIDK, em.Name FROM db_academic.schedule_details_course sdc
                                                              LEFT JOIN db_academic.schedule sc ON (sc.ID = sdc.ScheduleID)
                                                              LEFT JOIN db_employees.employees em ON (em.NIP = sc.Coordinator)
                                                               WHERE sc.SemesterID = "'.$SemesterID.'" AND sdc.ProdiID = "'.$data[$i]['ID'].'" AND em.ProdiID = "'.$data[$i]['ID'].'"
                                                                GROUP BY sc.Coordinator ')->result_array();

                    $data[$i]['Lecturer_Sch_Co'] = $dataSchedule;

                    $listCoord = [];
                    if(count($dataSchedule)>0){
                        foreach ($dataSchedule AS $item){
                            array_push($listCoord,$item['NIP']);
                        }
                    }

                    $data[$i]['Lecturer_Sch_Co_arr'] = $listCoord;

                    $dataScheduleTeam = $this->db->query('SELECT stt.NIP, em.NUP, em.NIDN, em.NIDK, em.Name  FROM db_academic.schedule_team_teaching stt
                                                                LEFT JOIN db_academic.schedule sc ON (sc.ID = stt.ScheduleID)
                                                                LEFT JOIN db_academic.schedule_details_course sdc ON (sc.ID = sdc.ScheduleID)
                                                                LEFT JOIN db_employees.employees em ON (em.NIP = stt.NIP)
                                                                WHERE sc.SemesterID = "'.$SemesterID.'" AND sdc.ProdiID = "'.$data[$i]['ID'].'" AND em.ProdiID = "'.$data[$i]['ID'].'"
                                                                GROUP BY stt.NIP
                                                                 ')->result_array();

                    $data[$i]['Lecturer_Sch_Team'] = $dataScheduleTeam;

                    if(count($dataScheduleTeam)>0){
                        foreach ($dataScheduleTeam AS $item){
                            if(!in_array($item['NIP'],$listCoord)){
                                array_push($dataSchedule,$item);
                            }
                        }
                    }

                    $data[$i]['Lecturer_Sch_Fix'] = $dataSchedule;

                } else {
                    // Schedule Lama
                    $data[$i]['Lecturer_Sch_Fix'] = [];
                }

            }
        }

        return print_r(json_encode($data));
    }


    public function getLuaran_lainnya(){

        $Status = $this->input->get('s');
        $stat = "('11','12','13','14','15','25','26','27','28') ";

        $data = $this->db->query('SELECT Judul, Tgl_terbit, Ket
                    FROM db_research.publikasi
                    WHERE ID_jns_pub IN '.$stat.' ')->result_array();
        return print_r(json_encode($data));
    }

    public function getLuaranTekno_produk(){

        $Status = $this->input->get('s');
        $data = $this->db->query('SELECT Nama_judul, Tahun_perolehan, Keterangan FROM db_agregator.teknologi_produk_karya ORDER BY ID DESC')->result_array();
        return print_r(json_encode($data));

    }

    public function getLuaranHkiproduk(){

        $Status = $this->input->get('s');
        $data = $this->db->query('SELECT Nama_judul, Tahun_perolehan, Keterangan FROM db_agregator.hki_desain_produk ORDER BY ID DESC')->result_array();
        return print_r(json_encode($data));
    }

    public function getLuaranHkipaten(){

        $Status = $this->input->get('s');
        $data = $this->db->query('
            SELECT Judul AS NamaJudul, Tgl_terbit AS Tahun, Ket AS Keterangan
            FROM db_research.publikasi 
            WHERE ID_kat_capaian = 2
            UNION
            SELECT Judul_PKM AS NamaJudul, ID_thn_kegiatan AS Tahun, Ket AS Keterangan
            FROM db_research.pengabdian_masyarakat
            WHERE ID_kat_capaian = 2')->result_array();
        return print_r(json_encode($data));
    }

    public function getsitasikarya(){

        //$Status = $this->input->get('s');
        $data = $this->db->query('SELECT a.NIP_penulis, a.Judul_artikel, a.Banyak_artikel, a.Tahun, b.Name
                    FROM db_agregator.sitasi_karya AS a
                    LEFT JOIN db_employees.employees AS b ON (a.NIP_penulis = b.NIP)
                    ORDER BY a.ID DESC')->result_array();
        return print_r(json_encode($data));

    }



    public function getAkreditasiProdi(){
        // get TypeHeader
        $rs = array();
        $header = array();
        $fill = array();
        $sql = 'select Type from db_academic.education_level Group by Type';
        $query=$this->db->query($sql, array())->result_array();
        for ($i=0; $i < count($query); $i++) {
            $sql2 = 'select * from db_academic.education_level where Type = ? order by Name asc ';
            $query2=$this->db->query($sql2, array($query[$i]['Type'] ))->result_array();
            $query[$i]['Detail'] = $query2;
        }

        $header = $query;

        // fill count
        $G_accreditation = $this->m_master->showData_array('db_academic.accreditation');
        for ($i=0; $i < count($G_accreditation); $i++) {
            $AccreditationID = $G_accreditation[$i]['ID'];
            $AccreditationName = $G_accreditation[$i]['Label'];
            $temp2 = array(
                'AccreditationID' => $AccreditationID,
                'AccreditationName' => $AccreditationName,
                'TypeProgramStudy' => array(),
            );
            $temp3 = array();
            for ($j=0; $j < count($query); $j++) {
                $TypeProgramStudy = $query[$j]['Type'];
                $temp3 = array(
                    'Name' => $TypeProgramStudy,
                    'Data' => array(),
                );

                $Detail = $query[$j]['Detail'];
                for ($k=0; $k < count($Detail); $k++) {
                    $EducationLevelID = $Detail[$k]['ID'];
                    $EducationLevelName = $Detail[$k]['Name'];
                    $EducationLevelDesc = $Detail[$k]['Description'];
                    $EducationLevelDescEng = $Detail[$k]['DescriptionEng'];
                    // find sql
                    $sql3 = 'select count(*) as Total from db_academic.program_study where EducationLevelID = ? and AccreditationID = ? ';
                    $query3=$this->db->query($sql3, array($EducationLevelID,$AccreditationID))->result_array();

                    $temp3['Data'][] = array(
                        'EducationLevelID' => $EducationLevelID,
                        'EducationLevelName' => $EducationLevelName,
                        'EducationLevelDesc' => $EducationLevelDesc,
                        'EducationLevelDescEng' => $EducationLevelDescEng,
                        'Count' => $query3[0]['Total'],
                    );
                }

                $temp2['TypeProgramStudy'][] = $temp3;

            }

            $fill[] = $temp2;
        }

        $rs['fill'] = $fill;
        $rs['header'] = $header;

        return print_r(json_encode($rs));

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
        else if($data_arr['action']=='readAgregatorAdmin'){
            $data = $this->db->query('SELECT aa.*, em.Name FROM db_agregator.agregator_admin aa
                                              LEFT JOIN db_employees.employees em ON (aa.NIP = em.NIP)')->result_array();

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removeAgregatorAdmin'){
            $ID = $data_arr['ID'];
            $this->db->where('ID', $ID);
            $this->db->delete('db_agregator.agregator_admin');

            return print_r(1);
        }
        else if($data_arr['action']=='addAgregatorAdmin'){
            $NIP = $data_arr['NIP'];

            // Cek nip
            $data = $this->db->get_where('db_agregator.agregator_admin',array(
                'NIP' => $NIP
            ))->result_array();

            if(count($data)>0){
                $result = 0;
            } else {

                $dataIns = array(
                    'NIP' => $NIP
                );
                $this->db->insert('db_agregator.agregator_admin',$dataIns);

                $result = 1;
            }
            return print_r($result);
        }
        else if($data_arr['action']=='readAgregatorHeaderMenu'){
            $data = $this->db->query('SELECT * FROM db_agregator.agregator_menu_header ORDER BY Type, Name ASC')->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removeAgregatorHeaderMenu'){
            $ID = $data_arr['ID'];
            $this->db->where('ID', ID);
            $this->db->delete('db_agregator.agregator_menu_header');
            $this->db->reset_query();

            $this->db->where('MHID', $ID);
            $this->db->delete('db_agregator.agregator_menu');

            return print_r(1);

        }
        else if($data_arr['action']=='updateAgregatorHeaderMenu'){
            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            if($ID!=''){
                // Update
                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.agregator_menu_header',$dataForm);
            } else {
                $this->db->insert('db_agregator.agregator_menu_header',$dataForm);
            }

            return print_r(1);
        }
        else if($data_arr['action']=='readAgregatorMenu'){
            $data = $this->db->query('SELECT am.*, amh.Name AS H_Name, amh.Type AS H_Type FROM db_agregator.agregator_menu am
                                                  LEFT JOIN db_agregator.agregator_menu_header amh ON (amh.ID = am.MHID)
                                                  ORDER BY am.MHID, am.ID ASC ')->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='removedAgregatorMenu'){
            $ID = $data_arr['ID'];
            $this->db->where('ID', $ID);
            $this->db->delete('db_agregator.agregator_menu');

            return print_r(1);
        }
        else if($data_arr['action']=='updateAgregatorMenu'){
            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];

            if($ID!=''){
                // Update
                $this->db->where('ID', $ID);
                $this->db->update('db_agregator.agregator_menu',$dataForm);
            } else {
                $this->db->insert('db_agregator.agregator_menu',$dataForm);
            }

            return print_r(1);

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
            $ProdiID = (isset($data_arr['ProdiID'])) ? $data_arr['ProdiID'] : '';
            $WhereProdi = ($ProdiID!='') ? ' AND ats.ProdiID = "'.$ProdiID.'" ' : '';

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataSearch = ' AND (  ats.Name LIKE "%'.$search.'%"
                                OR ats.NPM LIKE "%'.$search.'%" )';
            }

            $queryDefault = 'SELECT ssp.*, ats.Name AS StudentName, ats.IjazahSMA, mk.MKCode,
                                        mk.NameEng AS CourseEng, sc.ClassGroup,
                                        ats.ClearentLibrary, ats.ClearentLibrary_By, ats.ClearentLibrary_At, em1.Name AS ClearentLibrary_Name,
                                        ats.ClearentFinance, ats.ClearentFinance_By, ats.ClearentFinance_At, em2.Name AS ClearentFinance_Name,
                                        ats.ClearentKaprodi, ats.ClearentKaprodi_By, ats.ClearentKaprodi_At, em3.Name AS ClearentKaprodi_Name,
                                        ats.MentorFP1, em4.Name AS MentorFP1Name, ats.MentorFP2, em5.Name AS MentorFP2Name,
                                        ats.ID AS AUTHID
                                        FROM db_academic.std_study_planning ssp
                                        LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = ssp.MKID)
                                        LEFT JOIN db_academic.auth_students ats ON (ats.NPM = ssp.NPM)
                                        LEFT JOIN db_academic.schedule sc ON (sc.ID = ssp.ScheduleID)
                                        LEFT JOIN db_employees.employees em1 ON (ats.ClearentLibrary_By = em1.NIP)
                                        LEFT JOIN db_employees.employees em2 ON (ats.ClearentFinance_By = em2.NIP)
                                        LEFT JOIN db_employees.employees em3 ON (ats.ClearentKaprodi_By = em3.NIP)

                                        LEFT JOIN db_employees.employees em4 ON (ats.MentorFP1 = em4.NIP)
                                        LEFT JOIN db_employees.employees em5 ON (ats.MentorFP2 = em5.NIP)
                                        WHERE mk.Yudisium = "1" AND ssp.SemesterID = "'.$SemesterID.'" '.$WhereProdi.' '.$dataSearch;


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
                $Score = (count($dataScore)>0 && $dataScore[0]['Score']!=null && $dataScore[0]['Score']!='') ? $dataScore[0]['Score'] : '';

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

                // Edit Mentor Final Project
                $m1 = ($row['MentorFP1']!=null && $row['MentorFP1']!='') ? $row['MentorFP1'] : '';
                $m2 = ($row['MentorFP2']!=null && $row['MentorFP2']!='') ? $row['MentorFP2'] : '';

                $btnCrudPembimbing = ($DeptID=='6' || $DeptID==6) ? '<button class="btn btn-sm btn-default btnAddMentor" id="btnAddMentor_'.$row['AUTHID'].'" data-id="'.$row['AUTHID'].'"
                data-std="'.$row['NPM'].' - '.$row['StudentName'].'"
                data-m1="'.$m1.'" data-m2="'.$m2.'">Edit Mentor Final Project</button>' : '';


                // Library
                $dateTm = ($row['ClearentLibrary_At']!='' && $row['ClearentLibrary_At']!=null) ? ' <div style="color: #9e9e9e;">'.date('d M Y H:i',strtotime($row['ClearentLibrary_At'])).'</div>' : '';
                if($DeptID=='11' || $DeptID==11){
                    $c_Library = ($row['ClearentLibrary']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                        <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentLibrary_Name'].''.$dateTm
                        : '<button class="btn btn-sm btn-default btnClearnt" data-id="'.$row['AUTHID'].'" data-c="ClearentLibrary">Clearance</button>';
                } else {
                    $c_Library = ($row['ClearentLibrary']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                        <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentLibrary_Name'].''.$dateTm
                        : 'Waiting Library Clearance';
                }



                // Finance
                $dateTm = ($row['ClearentFinance_At']!='' && $row['ClearentFinance_At']!=null) ? ' <div style="color: #9e9e9e;">'.date('d M Y H:i',strtotime($row['ClearentFinance_At'])).'</div>' : '';
                if($DeptID=='9' || $DeptID==9){
                    $c_Finance = ($row['ClearentFinance']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                        <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentFinance_Name'].''.$dateTm
                        : '<button class="btn btn-sm btn-default btnClearnt" data-id="'.$row['AUTHID'].'" data-c="ClearentFinance">Clearance</button>';
                } else {
                    $c_Finance = ($row['ClearentFinance']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                        <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentFinance_Name'].''.$dateTm
                        : 'Waiting Finance Clearance';
                }


                // kaprodi
                $dateTm = ($row['ClearentKaprodi_At']!='' && $row['ClearentKaprodi_At']!=null)
                    ? ' <div style="color: #9e9e9e;">'.date('d M Y H:i',strtotime($row['ClearentKaprodi_At'])).'</div>'
                    : '';

                if($ProdiID!=''){
                    $c_Kaprodi = ($row['ClearentKaprodi']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                    <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentKaprodi_Name'].''.$dateTm
                        : '<button class="btn btn-sm btn-default btnClearnt" data-id="'.$row['AUTHID'].'" data-c="ClearentKaprodi">Clearance</button>';

                } else {
                    $c_Kaprodi = ($row['ClearentKaprodi']!='0') ? '<i class="fa fa-check-circle" style="color: darkgreen;"></i>
                    <hr style="margin-top: 7px;margin-bottom: 3px;"/>'.$row['ClearentKaprodi_Name'].''.$dateTm
                        : 'Waiting Approval Kaprodi';
                }



                $c_Kaprodi = ($row['ClearentFinance']!='0' && $row['ClearentLibrary']!='0' &&
                    $row['IjazahSMA']!=null && $row['IjazahSMA']!='') ? $c_Kaprodi : '<span style="font-size: 12px;">Waiting Ijazah Uploaded ,Library & Finance Clearance</span>';




                $m1Name = ($row['MentorFP1']!=null && $row['MentorFP1']!='') ? '<div>'.$row['MentorFP1'].' - '.$row['MentorFP1Name'].'</div>' : '';
                $m2Name = ($row['MentorFP2']!=null && $row['MentorFP2']!='') ? '<div>'.$row['MentorFP2'].' - '.$row['MentorFP2Name'].'</div>' : '';

                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;"><b>'.$row['StudentName'].'</b><br/>'.$row['NPM'].'<br/>'.$btnCrudPembimbing.'</div> ';
                $nestedData[] = '<div style="text-align:left;">'.$row['CourseEng'].'<br/>'.$row['MKCode'].' | Group : '.$row['ClassGroup'].'<div id="viewMentor_'.$row['AUTHID'].'">'.$m1Name.''.$m2Name.'</div></div>';
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
            $NIP = (isset($data_arr['NIP'])) ? $data_arr['NIP'] : '';

            $arr = array(
                $C => '1',
                $C.'_By' => ($NIP!='') ? $NIP : $this->session->userdata('NIP'),
                $C.'_At' => $this->m_rest->getDateTimeNow()
            );

            $this->db->where('ID', $ID);
            $this->db->update('db_academic.auth_students',$arr);

            return print_r(1);

        }

        else if($data_arr['action']=='updateMentorFP'){

            $dataForm = (array) $data_arr['dataForm'];

            $this->db->where('ID', $data_arr['ID']);
            $this->db->update('db_academic.auth_students',$dataForm);

            return print_r(1);
        }

    }


    public function crudqna(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='updateNewQNA'){

            $dataForm = (array) $data_arr['dataForm'];

            $ID = $data_arr['ID'];

            if($ID!=''){
                $dataForm['UpdatedBy'] = $this->session->userdata('NIP');
                $dataForm['UpdatedAt'] = $this->m_rest->getDateTimeNow();
                $this->db->where('ID',$ID);
                $this->db->update('db_employees.user_qna',$dataForm);
            } else {
                $dataForm['EntredBy'] = $this->session->userdata('NIP');
                $this->db->insert('db_employees.user_qna',$dataForm);
                $ID = $this->db->insert_id();
            }

            return print_r(json_encode(array('ID' => $ID )));
        }
        else if($data_arr['action']=='viewListQNA'){

            $requestData= $_REQUEST;

            $Previlege = $data_arr['Previlege'];
            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataSearch = ' WHERE  ls.Questions LIKE "%'.$search.'%"
            OR qna.Answers "%'.$search.'%" ';
            }

            $queryDefault = 'SELECT qna.*, ls.Questions FROM db_employees.qna qna '.$dataSearch;

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

    public function crudAllProgramStudy(){
        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='viewAllProdi'){
            $data = $this->db->get_where('db_academic.program_study',array(
                'Status' => 1
            ))->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='updateCreditAllProdi'){
            $dataForm = (array) $data_arr['dataForm'];

            if(count($dataForm)>0){
                for($i=0;$i<count($dataForm);$i++){
                    $d = (array) $dataForm[$i];
                    $this->db->set('DefaultCredit', $d['Credit']);
                    $this->db->where('ID', $d['ID']);
                    $this->db->update('db_academic.program_study');
                }
            }

            return print_r(1);

        }
    }

    public function crudLogging(){
        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='insertLog'){

            $dataForm = (array) $data_arr['dataForm'];
            $dataForm['AccessedOn'] = $this->m_rest->getDateTimeNow();
            $this->db->insert('db_employees.log_employees',$dataForm);
            return print_r(1);

        }
    }

    public function crudFileFinalProject(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='viewFileFinalProject'){
            $requestData= $_REQUEST;

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {

                $search = $requestData['search']['value'];
                $dataSearch = ' WHERE fpf.NPM LIKE "%'.$search.'%" OR ats.Name LIKE "%'.$search.'%"
                                OR ps.Name LIKE "%'.$search.'%" OR fpf.JudulInd LIKE "%'.$search.'%"
                                 OR fpf.JudulEng LIKE "%'.$search.'%" ';
            }

            $queryDefault = 'SELECT fpf.*, ats.Name, ps.Name AS ProdiName FROM db_academic.final_project_files fpf
                                          LEFT JOIN db_academic.auth_students ats ON (ats.NPM = fpf.NPM)
                                          LEFT JOIN db_academic.program_study ps ON (ps.ID = ats.ProdiID)
                                          '.$dataSearch.' ';

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $dataTable = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefault)->result_array();

            $query = $dataTable;

            $no = $requestData['start'] + 1;
            $data = array();

            for($i=0;$i<count($query);$i++) {
                $nestedData = array();
                $row = $query[$i];

                // 0 = Plan, 1 = Send, 2 = Approve, -2 Rejected
                $Status = 'Plan';
                if($row['Status']==1 || $row['Status']=='1'){
                    $Status = 'Waiting approval';
                }
                else if($row['Status']==2 || $row['Status']=='2'){
                    $Status = 'Approved';
                }
                else if($row['Status']==-2 || $row['Status']=='-2'){
                    $Status = 'Rejected';
                }

                $Noted = ($row['Noted']!='' && $row['Noted']!=null) ? $row['Noted'] : '';

                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;"><a href="'.base_url('library/yudisium/final-project/details/'.$row['NPM']).'" target="_blank"><b>'.$row['Name'].'</b></a><br/>'.$row['NPM'].'<br/>'.$row['ProdiName'].'</div>';
                $nestedData[] = '<div style="text-align:left;"><b>'.$row['JudulInd'].'</b><br/><i>'.$row['JudulEng'].'</i></div>';
                $nestedData[] = '<div>'.$Noted.'</div>';
                $nestedData[] = '<div>'.$Status.'</div>';

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
        else if($data_arr['action']=='viewDetailsFileFinalProject'){

            $NPM = $data_arr['NPM'];

            $data = $this->db->query('SELECT fpf.*, ats.Name FROM db_academic.final_project_files fpf
                                                LEFT JOIN db_academic.auth_students ats ON (ats.NPM = fpf.NPM)
                                                WHERE fpf.NPM = "'.$NPM.'" ')->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='updateFileFinalProject'){

            $NPM = $data_arr['NPM'];
            $dataForm = (array) $data_arr['dataform'];

            $this->db->where('NPM', $NPM);
            $this->db->update('db_academic.final_project_files',$dataForm);

            return print_r(1);

        }

    }

    public function crudProgrameStudy(){

        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='viewAllDataProdi'){
            $data = $this->db->query('SELECT ps.*,el.Description, a.Label AS Akreditation FROM db_academic.program_study ps
                                           LEFT JOIN db_academic.education_level el ON (el.ID = ps.EducationLevelID)
                                           LEFT JOIN db_academic.accreditation a ON (a.ID = ps.AccreditationID)
                                           WHERE ps.Status = "1"')->result_array();

            // Get jml mhs
            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    $DataMhs  = $this->db->query('SELECT COUNT(*) AS Total FROM db_academic.auth_students ats
                                                            WHERE ats.ProdiID = "'.$data[$i]['ID'].'" AND ats.StatusStudentID = "3" ')->result_array();
                    $data[$i]['TotalMhs'] = $DataMhs[0]['Total'];
                }
            }

            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='updateProgrammeStudy'){
            $ID = $data_arr['ID'];
            $dataForm = $data_arr['dataForm'];

            $this->db->where('ID', $ID);
            $this->db->update('db_academic.program_study',$dataForm);

            return print_r(1);

        }



    }

    public function getAccreditation(){
        $data = $this->db->get('db_academic.accreditation')->result_array();

        return print_r(json_encode($data));
    }

    public function getDataLogEmployees(){

        $requestData= $_REQUEST;

        $u = $this->input->get('u');

        $dataWhere = ($u!='' && $u!=null && isset($u)) ? 'WHERE lem.NIP = "'.$u.'" ' : '';

        $dataSearch = '';
        if( !empty($requestData['search']['value']) ) {
            $search = $requestData['search']['value'];

            $fillSrc = 'lem.URL LIKE "%'.$search.'%" OR
                                 em.Name LIKE "%'.$search.'%" OR
                                 em.NIP LIKE "%'.$search.'%" OR
                                 em2.Name LIKE "%'.$search.'%" OR
                                 em2.NIP LIKE "%'.$search.'%" OR
                                 ats.Name LIKE "%'.$search.'%" OR
                                 ats.NPM LIKE "%'.$search.'%"';

            $dataSearch = ($u!='' && $u!=null && isset($u))
                ?  ' AND ( '.$fillSrc.' )'
                : ' WHERE '.$fillSrc;
        }

        $queryDefault = 'SELECT lem.ID, em.Name, lem.AccessedOn,
                            (CASE WHEN lem.NIP = lem.UserID THEN 0 ELSE lem.UserID END ) AS LoginAs,
                            (CASE WHEN em2.Name = em.Name THEN NULL ELSE em2.Name END) AS LoginAsLec,
                            ats.Name AS LoginAsStd,lem.URL
                            FROM db_employees.log_employees lem
                            LEFT JOIN db_employees.employees em ON (em.NIP = lem.NIP)
                            LEFT JOIN db_employees.employees em2 ON (em2.NIP = lem.UserID)
                            LEFT JOIN db_academic.auth_students ats ON (ats.NPM =  lem.UserID)
                            '.$dataWhere.' '.$dataSearch.' ORDER BY lem.ID DESC';


        $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

        $query = $this->db->query($sql)->result_array();
        $queryDefaultRow = $this->db->query($queryDefault)->result_array();

        $no = $requestData['start'] + 1;
        $data = array();

        for($i=0;$i<count($query);$i++) {

            $nestedData = array();
            $row = $query[$i];



            $LoginAsLecturer = ($row['LoginAsLec']!='' && $row['LoginAsLec']!=null)
                ? $row['LoginAsLec'] : '';
            $LoginAsStudent = ($row['LoginAsStd']!='' && $row['LoginAsStd']!=null)
                ? ucwords(strtolower($row['LoginAsStd'])) : '';

            $urlExp = explode('/',$row['URL']);

            $viewLink = '';
            $im = 2;
            if(count($urlExp)>$im){
                for($i2=0;$i2<count($urlExp);$i2++){
                    if($i2>$im){
                        $lg = strlen($urlExp[$i2]);
                        $vl = ($lg<=55) ? $urlExp[$i2] : '';

                        $de = ($i2!=$im && $i2!=count($urlExp)) ? '<i class="fa fa-angle-right"></i>' : '';
                        $viewLink = $viewLink.' '.$de.' <b>'.$vl.'</b>';
                    }

                }
            } else {
                $viewLink = $urlExp[$im];
            }

            $nestedData[] = '<div>'.$no.'</div>';
            $nestedData[] = '<div>'.$row['Name'].'</div>';
            $nestedData[] = '<div>'.date('d M Y H:i:s',strtotime($row['AccessedOn'])).'</div>';
            $nestedData[] = '<div>'.$LoginAsLecturer.'</div>';
            $nestedData[] = '<div>'.$LoginAsStudent.'</div>';
            $nestedData[] = '<div>'.$viewLink.'</div>';

            $data[] = $nestedData;
            $no++;

        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval(count($queryDefaultRow)),
            "recordsFiltered" => intval( count($queryDefaultRow) ),
            "data"            => $data,
            "dataQuery"            => $query
        );
        echo json_encode($json_data);

    }

    public function crudTracerAlumni(){
        $data_arr = $this->getInputToken2();

        if($data_arr['action']=='viewAlumni'){

            $requestData= $_REQUEST;

            $dataSearch = '';
            if( !empty($requestData['search']['value']) ) {
                $search = $requestData['search']['value'];
                $dataSearch = ' WHERE  ls.Questions LIKE "%'.$search.'%"
            OR qna.Answers "%'.$search.'%" ';
            }

            $queryDefault = 'SELECT ats.* FROM db_academic.auth_students ats WHERE ats.StatusStudentID = "1"  '.$dataSearch;

            $sql = $queryDefault.' LIMIT '.$requestData['start'].','.$requestData['length'].' ';

            $query = $this->db->query($sql)->result_array();
            $queryDefaultRow = $this->db->query($queryDefault)->result_array();

            $no = $requestData['start'] + 1;
            $data = array();

            for($i=0;$i<count($query);$i++) {

                $nestedData = array();
                $row = $query[$i];

                $ShowName = $row['NPM'].' - '.ucwords(strtolower($row['Name']));


                $nestedData[] = '<div>'.$no.'</div>';
                $nestedData[] = '<div style="text-align:left;"><b><a href="javascript:void(0);" class="showDetailAlumni" data-npm="'.$row['NPM'].'" data-name="'.$ShowName.'">'.$ShowName.'</a></b></div>';

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
        else if($data_arr['action']=='showExperience'){
            $NPM = $data_arr['NPM'];

            $data = $this->db->query('SELECT ae.*, pl.Description AS PositionLevel FROM db_studentlife.alumni_experience ae 
                                              LEFT JOIN db_studentlife.position_level pl ON (pl.ID = ae.PositionLevelID)
                                              WHERE ae.NPM = "'.$NPM.'" ')->result_array();

            return print_r(json_encode($data));

        }
        else if($data_arr['action']=='loadPositionLevel'){
            $data = $this->db->order_by('ID','DESC')->get('db_studentlife.position_level')->result_array();
            return print_r(json_encode($data));
        }
        else if($data_arr['action']=='updateDataExperience'){

            $ID = $data_arr['ID'];
            $dataForm = (array) $data_arr['dataForm'];
            if($ID!=''){
                // Update
                $this->db->where('ID', $ID);
                $this->db->update('db_studentlife.alumni_experience',$dataForm);
            } else {
                $this->db->insert('db_studentlife.alumni_experience',$dataForm);
            }

            return print_r(1);

        }
    }


    function getLanguagelabels(){
        $dataTr = $this->db->query('SELECT * FROM db_prodi.language_label ORDER BY ID ASC ')->result_array();

        $keys = array_keys($dataTr[0]);

        $result = array();

        for ($i=1;$i<count($keys);$i++){

            $temp = array();
            foreach ($dataTr AS $item){
                $temp[$item['Eng']] = $item[$keys[$i]];

            }

            $result[$keys[$i]] = $temp;



        }

        return print_r(json_encode($result));
    }

    public function getAllTA_MHS()
    {
        $rs = [];
        $sql = "show databases like '".'ta_'."%'";
        $query=$this->db->query($sql, array())->result_array();
        for ($i=0; $i < count($query); $i++) {
            $variable = $query[$i]; 
            foreach ($variable as $key => $value) {
                $ex = explode('_', $value);
                $ta = $ex[1];
                $rs[] = $ta;
            }
        }
        echo json_encode($rs);

    }

}
