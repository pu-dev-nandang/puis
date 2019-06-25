<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_master extends Purchasing_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
        $this->data['department'] = parent::__getDepartement(); 
        $this->load->model('budgeting/m_budgeting');
    }

    public function catalog()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/catalog',$this->data,true);
        $this->temp($content);
    }

    public function supplier()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/master/supplier',$this->data,true);
        $this->temp($content);
    }

    public function InputCatalog()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/master/catalog/InputCatalog',$this->data,true);
        echo json_encode($arr_result);
    }

    public function InputCatalog_FormInput()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $this->data['action'] = $Input['action'];
        if ($Input['action'] == 'edit') {
            $this->data['get'] = $this->m_master->caribasedprimary('db_purchasing.m_catalog','ID',$Input['ID']);
            $__Depart= $this->m_master->caribasedprimary('db_purchasing.m_catalog_division','ID_m_catalog',$Input['ID']);
            for ($i=0; $i < count($__Depart); $i++) { 
              $__G_name = $this->m_budgeting->SearchDepartementBudgeting2($__Depart[$i]['Departement']);
              $__Depart[$i]['Name'] = $__G_name[0]['NameDepartement'];
              $__Depart[$i]['Code'] = $__G_name[0]['ID'];
            }
            $this->data['AssignDepart'] = $__Depart;

            // lock beberapa field untuk tidak bisa diedit
                $sql = 'select * from db_budgeting.pr_detail where ID_m_catalog = ? limit 1';
                $query=$this->db->query($sql, array($Input['ID']))->result_array();
                $this->data['arr_lock'] = count($query);
        }

        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/master/catalog/FormInputCatalog',$this->data,true);
        echo json_encode($arr_result);
    }

    public function InputCatalog_saveFormInput()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $Item = $Input['Item'];
        $Desc = $Input['Desc'];
        $EstimaValue = $Input['EstimaValue'];
        $Departement = $Input['Departement'];
        $Detail = $Input['Detail'];
        $Detail = json_encode($Detail);
        if (array_key_exists('ID_category_catalog', $Input)) {
          $ID_category_catalog = $Input['ID_category_catalog'];
        }

        $filename = $Input['Item'].'_Uploaded';
        $filename = str_replace(" ", '_', $filename);
        $path = FCPATH.'uploads\\budgeting\\catalog\\';
        switch ($Input['Action']) {
            case 'add':
                if (array_key_exists('fileData',$_FILES)) {
                   $path = './uploads/budgeting/catalog';
                   $uploadFile = $this->uploadDokumenMultiple($path,$filename);
                   if (is_array($uploadFile)) {
                       $uploadFile = implode(',', $uploadFile);
                       $dataSave = array(
                           'Item' => $Item,
                           'Desc' => $Desc,
                           'EstimaValue' => $EstimaValue,
                           'ID_category_catalog' => $ID_category_catalog,
                           'Photo' => $uploadFile,
                           'Departement' => $Departement,
                           'DetailCatalog' => $Detail,
                           'CreatedBy' => $this->session->userdata('NIP'),
                           'CreatedAt' => date('Y-m-d'),
                           'Approval' => 1,
                           'ApprovalBy' => $this->session->userdata('NIP'),
                           'ApprovalAt' => date('Y-m-d H:i:s'),
                           'LastUpdateAt' => date('Y-m-d H:i:s'),
                       );
                       $this->db->insert('db_purchasing.m_catalog', $dataSave);
                       // get last insert  id
                         $insert_id = $this->db->insert_id();
                         // assign department
                         $listDepartmentSelected = $Input['listDepartmentSelected'];
                         for ($i=0; $i < count($listDepartmentSelected); $i++) { 
                           $Departement__ = $listDepartmentSelected[$i];
                           $dataSave__ =array(
                            'ID_m_catalog' => $insert_id,
                            'Departement' => $Departement__,
                           );
                           $this->db->insert('db_purchasing.m_catalog_division', $dataSave__);
                         }

                       echo json_encode(array('msg' => 'Saved','status' => 1));
                   }
                   else
                   {
                       echo json_encode(array('msg' => $uploadFile,'status' => 0));
                   }
                }
                else{
                    $dataSave = array(
                        'Item' => $Item,
                        'Desc' => $Desc,
                        'EstimaValue' => $EstimaValue,
                        'ID_category_catalog' => $ID_category_catalog,
                        'Photo' => '',
                        'Departement' => $Departement,
                        'DetailCatalog' => $Detail,
                        'CreatedBy' => $this->session->userdata('NIP'),
                        'CreatedAt' => date('Y-m-d'),
                        'Approval' => 1,
                        'ApprovalBy' => $this->session->userdata('NIP'),
                        'ApprovalAt' => date('Y-m-d H:i:s'),
                        'LastUpdateAt' => date('Y-m-d H:i:s'),
                    );
                    $this->db->insert('db_purchasing.m_catalog', $dataSave);
                    // get last insert  id
                      $insert_id = $this->db->insert_id();
                      // assign department
                      $listDepartmentSelected = $Input['listDepartmentSelected'];
                      for ($i=0; $i < count($listDepartmentSelected); $i++) { 
                        $Departement__ = $listDepartmentSelected[$i];
                        $dataSave__ =array(
                         'ID_m_catalog' => $insert_id,
                         'Departement' => $Departement__,
                        );
                        $this->db->insert('db_purchasing.m_catalog_division', $dataSave__);
                      }

                    echo json_encode(array('msg' => 'Saved','status' => 1));
                }

                break;
            case 'edit':
                $Get_Data = $this->m_master->caribasedprimary('db_purchasing.m_catalog','ID',$Input['ID']);
                if (array_key_exists('fileData',$_FILES)) {
                   $path = './uploads/budgeting/catalog';
                   $uploadFile = $this->uploadDokumenMultiple($path,$filename);
                   if (is_array($uploadFile)) {
                       $uploadFile = implode(',', $uploadFile);
                       // get all file first
                            $F = $Get_Data[0]['Photo'];
                            if ($F != '' && $F != null && !empty($F)) {
                                $uploadFile = $uploadFile.','.$F;
                            }
                       $dataSave = array(
                           'Item' => $Item,
                           'Desc' => $Desc,
                           'EstimaValue' => $EstimaValue,
                           'ID_category_catalog' => $ID_category_catalog,
                           'Photo' => $uploadFile,
                           'Departement' => $Departement,
                           'DetailCatalog' => $Detail,
                           'LastUpdateBy' => $this->session->userdata('NIP'),
                           'LastUpdateAt' => date('Y-m-d H:i:s'),
                       );
                       $this->db->where('ID', $Input['ID']);
                       $this->db->update('db_purchasing.m_catalog', $dataSave);

                       // assign department
                       $listDepartmentSelected = $Input['listDepartmentSelected'];
                       // delete first and insert again
                       $this->db->where('ID_m_catalog',$Input['ID']);
                       $this->db->delete('db_purchasing.m_catalog_division');
                       // insert
                       for ($i=0; $i < count($listDepartmentSelected); $i++) { 
                         $Departement__ = $listDepartmentSelected[$i];
                         $dataSave__ =array(
                          'ID_m_catalog' => $Input['ID'],
                          'Departement' => $Departement__,
                         );
                         $this->db->insert('db_purchasing.m_catalog_division', $dataSave__);
                       }

                       echo json_encode(array('msg' => 'Saved','status' => 1));
                   }
                   else
                   {
                       echo json_encode(array('msg' => $uploadFile,'status' => 0));
                   }
                }
                else{
                    $dataSave = array(
                        'Item' => $Item,
                        'Desc' => $Desc,
                        'EstimaValue' => $EstimaValue,
                        'ID_category_catalog' => $ID_category_catalog,
                        'Departement' => $Departement,
                        'DetailCatalog' => $Detail,
                        'LastUpdateBy' => $this->session->userdata('NIP'),
                        'LastUpdateAt' => date('Y-m-d H:i:s'),
                    );
                    $this->db->where('ID', $Input['ID']);
                    $this->db->update('db_purchasing.m_catalog', $dataSave);

                    // assign department
                    $listDepartmentSelected = $Input['listDepartmentSelected'];
                    // delete first and insert again
                    $this->db->where('ID_m_catalog',$Input['ID']);
                    $this->db->delete('db_purchasing.m_catalog_division');
                    // insert
                    for ($i=0; $i < count($listDepartmentSelected); $i++) { 
                      $Departement__ = $listDepartmentSelected[$i];
                      $dataSave__ =array(
                       'ID_m_catalog' => $Input['ID'],
                       'Departement' => $Departement__,
                      );
                      $this->db->insert('db_purchasing.m_catalog_division', $dataSave__);
                    }

                    echo json_encode(array('msg' => 'Saved','status' => 1));
                }
                break;
            case 'status':
                $dataSave = array(
                    'Active' => 0,
                    'LastUpdateBy' => $this->session->userdata('NIP'),
                    'LastUpdateAt' => date('Y-m-d H:i:s'),
                );
                $this->db->where('ID', $Input['ID']);
                $this->db->update('db_purchasing.m_catalog', $dataSave);
                echo json_encode(array(''));
                break;
            case 'delete':
                $sql = 'select * from db_budgeting.pr_detail where ID_m_catalog = ? limit 1';
                $query=$this->db->query($sql, array($Input['ID']))->result_array();
                if (count($query) == 0) {
                  $Get_Data = $this->m_master->caribasedprimary('db_purchasing.m_catalog','ID',$Input['ID']);
                  $F = $Get_Data[0]['Photo'];
                  $F = explode(',', $F);

                  $this->db->where('ID', $Input['ID']);
                  $this->db->delete('db_purchasing.m_catalog');

                  // delete all file
                        for ($i=0; $i < count($F); $i++) { 
                            unlink($path.$F[$i]);
                        }

                  // delete m_catalog_division
                  $this->db->where('ID_m_catalog',$Input['ID']);
                  $this->db->delete('db_purchasing.m_catalog_division');

                  echo json_encode(array(''));
                }
                else
                {
                  echo json_encode(array($this->Msg['NotAction']));
                }
                break;    
            case 'approve':
                $dataSave = array(
                    'Approval' => 1,
                    'ApprovalBy' => $this->session->userdata('NIP'),
                    'ApprovalAt' => date('Y-m-d H:i:s'),
                    'Reason' => '',
                    'LastUpdateAt' => date('Y-m-d H:i:s'),
                );
                $this->db->where('ID', $Input['ID']);
                $this->db->update('db_purchasing.m_catalog', $dataSave);
                echo json_encode(array(''));
            break;
            case 'reject':
                $dataSave = array(
                    'Approval' => -1,
                    'ApprovalBy' => $this->session->userdata('NIP'),
                    'ApprovalAt' => date('Y-m-d H:i:s'),
                    'Reason' => $Input['Reason'],
                    'LastUpdateAt' => date('Y-m-d H:i:s'),
                );
                $this->db->where('ID', $Input['ID']);
                $this->db->update('db_purchasing.m_catalog', $dataSave);
                echo json_encode(array(''));
            break;         
            default:
                # code...
                break;
        }
    }

    public function uploadDokumenMultiple($path,$filename)
    {

        // Count total files
        $countfiles = count($_FILES['fileData']['name']);
      
      $output = array();
      // Looping all files
      for($i=0;$i<$countfiles;$i++){
            $config = array();
            if(!empty($_FILES['fileData']['name'][$i])){
     
              // Define new $_FILES array - $_FILES['file']
              $_FILES['file']['name'] = $_FILES['fileData']['name'][$i];
              $_FILES['file']['type'] = $_FILES['fileData']['type'][$i];
              $_FILES['file']['tmp_name'] = $_FILES['fileData']['tmp_name'][$i];
              $_FILES['file']['error'] = $_FILES['fileData']['error'][$i];
              $_FILES['file']['size'] = $_FILES['fileData']['size'][$i];

              // Set preference
              $config['upload_path'] = $path.'/';
              $config['allowed_types'] = '*';
              $config['overwrite'] = TRUE; 
              $no = $i + 1;
              $config['file_name'] = $filename.'_'.$no;

              $filenameUpload = $_FILES['file']['name'];
              $ext = pathinfo($filenameUpload, PATHINFO_EXTENSION);

              // $filenameNew = $filename.'_'.$no.'.pdf';
              $filenameNew = $filename.'_'.$no.'.'.$ext;
              // print_r($_FILES['file']['type']);

     
              //Load upload library
              $this->load->library('upload',$config); 
              $this->upload->initialize($config);
     
              // File upload
              if($this->upload->do_upload('file')){
                // Get data about the file
                $uploadData = $this->upload->data();
                $filePath = $uploadData['file_path'];
                $filename_uploaded = $uploadData['file_name'];
                // rename file
                $old = $filePath.'/'.$filename_uploaded;
                $new = $filePath.'/'.$filenameNew;

                rename($old, $new);

                $output[] = $filenameNew;
              }
            }
        }
        return $output;
    }

    public function Catalog_DataIntable($action = "All_approval")
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $this->data['action'] = $action;
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/master/catalog/Catalog_DataIntable',$this->data,true);
        echo json_encode($arr_result);
    }

    public function allow_division_catalog()
    {
      $this->auth_ajax();
      $arr_result = array('html' => '','jsonPass' => '');
      $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/master/catalog/allow_division_catalog',$this->data,true);
      echo json_encode($arr_result);
    }

    public function table_allow_div()
    {
      $this->auth_ajax();
      $arr_result = array('html' => '','jsonPass' => '');
      $GetDeparment = $this->m_master->apiservertoserver(url_pas.'api/__getAllDepartementPU','');
      for ($i=0; $i < count($GetDeparment); $i++) { 
        $CodeDepartment = $GetDeparment[$i]['Code'];
        // check in table catalog_permission
        $c = $this->m_master->caribasedprimary('db_purchasing.catalog_permission','Departement',$CodeDepartment);
        $GetDeparment[$i]['No'] = $i + 1;
        if (count($c) > 0) {
          $st = 'Allowed';
          $stcode = '<button class = "btn btn-inverse btnpermission" department = "'.$GetDeparment[$i]['Code'].'" stnow = "1">Not Allow</button>';
          $GetDeparment[$i]['st'] = $st;
          $GetDeparment[$i]['stcode'] = $stcode;
        }
        else
        {
          $st = 'Not Allowed';
          $stcode = '<button class = "btn btn-primary btnpermission" department = "'.$GetDeparment[$i]['Code'].'" stnow = "0">Allow</button>';
          $GetDeparment[$i]['st'] = $st;
          $GetDeparment[$i]['stcode'] = $stcode;
        }
      }

      $this->data['GetDeparment'] = $GetDeparment;
      $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/master/catalog/table_allow_div',$this->data,true);
      echo json_encode($arr_result);
    }

    public function submit_permission_division()
    {
      $this->auth_ajax();
      $Input = $this->getInputToken();
      $Departement = $Input['Department'];
      switch ($Input['passaction']) {
        case 'delete':
           $this->db->where('Departement', $Departement);
           $this->db->delete('db_purchasing.catalog_permission');
          break;
        case 'add':
           $dataSave = array('Departement' => $Departement);
           $this->db->insert('db_purchasing.catalog_permission',$dataSave);
          break;
        default:
          # code...
          break;
      }

      echo json_encode('');

    }

    public function Catalog_DataIntable_server_side()
    {
        $this->auth_ajax();
        $action = $this->input->post('action');
        $condition = '';
        if ($action == 'All_approval') {
           $condition = ' and a.Approval = 1';
        }
        elseif ($action == 'non_approval') {
            $condition = ' and a.Approval != 1';
        }

        $requestData= $_REQUEST;
        $sql = 'select count(*) as total from db_purchasing.m_catalog as a where a.Active = 1 '.$condition;
        $query = $this->db->query($sql)->result_array();
        $totalData = $query[0]['total'];
        $No = $requestData['start'] + 1;

        $sql = 'select a.*,b.Name as NameCreated,c.NameDepartement,mcc.Name as NameCategory
                from db_purchasing.m_catalog as a
                join db_purchasing.m_category_catalog as mcc on mcc.ID = a.ID_category_catalog 
                join db_employees.employees as b on a.CreatedBy = b.NIP
                join (
                select * from (
                select CONCAT("AC.",ID) as ID, CONCAT("Prodi ",NameEng)  as NameDepartement from db_academic.program_study
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division where StatusDiv = 1
                UNION
                select CONCAT("FT.",ID) as ID, CONCAT("Faculty ",NameEng) as NameDepartement from db_academic.faculty
                ) aa
                ) as c on a.Departement = c.ID
               ';

        $sql.= ' where ( a.Item LIKE "'.$requestData['search']['value'].'%" or a.Desc LIKE "'.$requestData['search']['value'].'%" or c.NameDepartement LIKE "'.$requestData['search']['value'].'%" or a.DetailCatalog LIKE "%'.$requestData['search']['value'].'%"
          or mcc.Name LIKE "'.$requestData['search']['value'].'%"
                ) and a.Active = 1 '.$condition;
        $sql.= ' ORDER BY a.ID Desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
        $query = $this->db->query($sql)->result_array();

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];
            $nestedData[] = $No;
            $nestedData[] = $row['Item'].'<br><span style = "color : red" >'.$row['NameCategory'].'</span>';
            $nestedData[] = $row['Desc'];
            $EstimaValue = $row['EstimaValue'];
            $EstimaValue = 'Rp '.number_format($EstimaValue,2,',','.');
            $nestedData[] = $EstimaValue;
            $Photo = $row['Photo'];
            // print_r($Photo);
            if ($Photo != '') {
                // print_r('test');
                $Photo = explode(",", $Photo);
                $htmlPhoto = '<ul>';
                for ($z=0; $z < count($Photo); $z++) { 
                    $htmlPhoto .= '<li>'.'<a href="'.base_url("fileGetAny/budgeting-catalog-".$Photo[$z]).'" target="_blank"></i>'.$Photo[$z].'</a></li>';
                }
                $htmlPhoto .= '</ul>';
            }
            else
            {
                $htmlPhoto = '';
            }
            $nestedData[] = $htmlPhoto;

            $btnViewDepart = '';
            // $__G_data = $this->m_master->caribasedprimary('db_purchasing.m_catalog_division','ID_m_catalog',$row['ID']);
            // if (count($__G_data) > 0) {
            //   $btnViewDepart = '<button class = "btn btn-default btnViewDepart" rowid = "'.$row['ID'].'">Another</button>';
            // }

            // $nestedData[] = '<div align = "center" >'.$row['NameDepartement'].'<br>'.$btnViewDepart.'</div>';
            $nestedData[] = '<div align = "center" >'.$row['NameDepartement'].'</div>';
            $DetailCatalog = $row['DetailCatalog'];
            $DetailCatalog = json_decode($DetailCatalog);
            $temp = '';
            if ($DetailCatalog != "" || $DetailCatalog != null) {
                foreach ($DetailCatalog as $key => $value) {
                    $temp .= $key.' :  '.$value.'<br>';
                }

            }

            if ($action == 'All_approval') {
                $btn = '<button type="button" class="btn btn-warning btn-edit btn-edit-catalog" code="'.$row['ID'].'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>&nbsp <button type="button" class="btn btn-danger btn-delete btn-delete-catalog" code="'.$row['ID'].'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
            }
            elseif ($action == 'non_approval')
            {
                $btn = '<button type="button" class="btn btn-default btn-edit btn-approve-catalog" code="'.$row['ID'].'"> <i class="fa fa-handshake-o" aria-hidden="true"></i> Approve</button>&nbsp <button type="button" class="btn btn-inverse btn-edit btn-reject-catalog" code="'.$row['ID'].'"> Reject</button>';
            }
            else
            {
                $btn = '';
            }
            
            $nestedData[] = $temp;
            $nestedData[] = $row['NameCreated'];
            $st = '';
            switch ($row['Approval']) {
              case 0:
                $st = 'Awaiting Approval';
                break;
              case 1:
                $st = 'Approve';
                break;
              case -1:
                $st = 'Reject<br><br><button type="button" class="btn btn-default btn-edit btn-reason" reason= "'.$row['Reason'].'"> Reason</button>';
                break;  
              default:
                # code...
                break;
            }

            $nestedData[] = $st;
            $nestedData[] = $btn;
            $nestedData[] = $row['Reason'];
            $data[] = $nestedData;

            $No++;
        }

        // print_r($data);

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function ApprovalCatalog()
    {
        $this->auth_ajax();
        $this->Catalog_DataIntable('non_approval');
    }

    public function InputSupplier()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/master/supplier/InputSupplier',$this->data,true);
        echo json_encode($arr_result);
    }

    public function InputSupplier_FormInput()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $this->data['action'] = $Input['action'];
        if ($Input['action'] == 'edit') {
            $this->data['get'] = $this->m_master->caribasedprimary('db_purchasing.m_supplier','ID',$Input['ID']);
            $d = $this->data['get'];
            $CodeSupplier = $d[0]['CodeSupplier'];
            // lock beberapa field untuk tidak bisa diedit
                $sql = 'select * from db_purchasing.pre_po_supplier where CodeSupplier = ? limit 1';
                $query=$this->db->query($sql, array($CodeSupplier))->result_array();
                $this->data['arr_lock'] = count($query); 
        }
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/master/supplier/FormInputSupplier',$this->data,true);
        echo json_encode($arr_result);
    }

    public function InputSupplier_saveFormInput()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $Msg = '';
        $NamaSupplier = $Input['NamaSupplier'];
        $PICName = $Input['PICName'];
        $JabatanPIC = $Input['JabatanPIC'];
        $Alamat = $Input['Alamat'];
        $Website = $Input['Website'];
        $NoTelp = $Input['NoTelp'];
        $NoHp = $Input['NoHp'];
        $CategorySupplier = $Input['CategorySupplier'];
        
        $DetailInfo = $Input['DetailInfo'];
        $DetailInfo = json_encode($DetailInfo);

        $DetailItem = $Input['DetailItem'];
        $DetailItem = json_encode($DetailItem);

        switch ($Input['Action']) {
            case 'add':
                $NeedPrefix = $Input['NeedPrefix'];
                $CodeSupplier = $Input['CodeSupplier'];
                if ($NeedPrefix == 1) { // get the code
                    $CfgCode = $this->m_master->showData_array('db_budgeting.cfg_codeprefix');
                    $CodePostPrefix = $CfgCode[0]['CodeSupplier'];
                    $LengthCode = $CfgCode[0]['LengthCodeSupplier'];
                    $tbl = 'db_purchasing.m_supplier';
                    $fieldCode = 'CodeSupplier';
                    $CodeSupplier = $this->m_budgeting->getTheCode($tbl,$fieldCode,$CodePostPrefix,$LengthCode);
                }

                $sql = 'select * from db_purchasing.m_supplier where CodeSupplier = ? and Active = 1';
                $query=$this->db->query($sql, array($CodeSupplier))->result_array();
                if (count($query) > 0) {
                   $Msg = $this->Msg['Duplicate'];
                }
                else
                {
                   $dataSave = array(
                       'CodeSupplier' => $CodeSupplier,
                       'NamaSupplier' => trim(ucwords($NamaSupplier)),
                       'PICName' => trim(ucwords($PICName)),
                       'JabatanPIC' => trim(ucwords($JabatanPIC)),
                       'Alamat' => trim($Alamat),
                       'Website' => trim($Website),
                       'NoTelp' => trim($NoTelp),
                       'NoHp' => trim($NoHp),
                       'CategorySupplier' => $CategorySupplier,
                       'DetailInfo' => $DetailInfo,
                       'DetailItem' => $DetailItem,
                       'Approval' => 1,
                       'ApprovalBy' => $this->session->userdata('NIP'),
                       'ApprovalAt' => date('Y-m-d H:i:s'),
                       'CreatedBy' => $this->session->userdata('NIP'),
                       'CreatedAt' => date('Y-m-d'),
                   );
                   $this->db->insert('db_purchasing.m_supplier', $dataSave);
                }
                break;
            case 'edit':
                $ID = $Input['ID'];
                $CodeSupplier = $Input['CodeSupplier'];
                $dataSave = array(
                   'CodeSupplier' => $CodeSupplier,
                   'NamaSupplier' => trim(ucwords($NamaSupplier)),
                   'PICName' => trim(ucwords($PICName)),
                   'JabatanPIC' => trim(ucwords($JabatanPIC)),
                   'Alamat' => trim($Alamat),
                   'Website' => trim($Website),
                   'NoTelp' => trim($NoTelp),
                   'NoHp' => trim($NoHp),
                   'CategorySupplier' => $CategorySupplier,
                   'DetailInfo' => $DetailInfo,
                   'DetailItem' => $DetailItem,
                   'LastUpdateBy' => $this->session->userdata('NIP'),
                   'LastUpdateAt' => date('Y-m-d H:i:s'),
                );
                $this->db->where('ID', $ID);
                $this->db->update('db_purchasing.m_supplier', $dataSave);
                break;
            case 'delete':
                $dataSave = array(
                    'Active' => 0,
                    'LastUpdateBy' => $this->session->userdata('NIP'),
                    'LastUpdateAt' => date('Y-m-d H:i:s'),
                );
                $this->db->where('ID', $Input['ID']);
                $this->db->update('db_purchasing.m_supplier', $dataSave);
                break;
            // case 'delete':
            //     $G_data = $this->m_master->caribasedprimary('db_purchasing.m_supplier','ID',$Input['ID']);
            //     $CodeSupplier = $G_data[0]['CodeSupplier'];
            //     $sql = 'select * from db_purchasing.pre_po where CodeSupplier = ? limit 1';
            //     $query=$this->db->query($sql, array($CodeSupplier))->result_array();
            //     if (count($query) == 0) {
            //       $this->db->where('ID', $Input['ID']);
            //       $this->db->delete('db_purchasing.m_supplier');
            //     }
            //     else
            //     {
            //       $msg = 'The data has been used for transaction, Cannot be action';
            //     }
            //     break;    
            case 'approve':
                $dataSave = array(
                    'Approval' => 1,
                    'ApprovalBy' => $this->session->userdata('NIP'),
                    'ApprovalAt' => date('Y-m-d H:i:s'),
                );
                $this->db->where('ID', $Input['ID']);
                $this->db->update('db_purchasing.m_supplier', $dataSave);
                break;           
            default:
                # code...
                break;
        }

        echo json_encode($Msg);
    }

    public function saveCategoryFormInput()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $CategoryName = $Input['CategoryName'];
        $action = $Input['action'];
        switch ($action) {
          case 'add':
            $dataSave = array(
                'CategoryName' => trim(ucwords($CategoryName)),
            );
            $this->db->insert('db_purchasing.m_categorysupplier', $dataSave);
            break;
          case 'edit':
            $ID = $Input['id_data'];
            $dataSave = array(
                'CategoryName' => trim(ucwords($CategoryName)),
            );
            $this->db->where('ID',$ID);
            $this->db->update('db_purchasing.m_categorysupplier', $dataSave);
            break;
          case 'delete':
            $ID = $Input['id_data'];
            // $G = $this->m_master->caribasedprimary('db_purchasing.m_supplier','CategorySupplier',$ID);
            // if (count($G) > 0) {
            //   echo json_encode(0);
            // }
            // else
            // {
            //   $this->db->where('ID',$ID);
            //   $this->db->delete('db_purchasing.m_categorysupplier');
            //   echo json_encode(1);
            // }
            $dataSave = array(
                'Active' => 0,
            );
            $this->db->where('ID', $ID);
            $this->db->update('db_purchasing.m_categorysupplier', $dataSave);
            echo json_encode(1);
            break;  
          default:
            # code...
            break;
        }
        
    }

    public function Supplier_DataIntable($action = "All_approval")
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $this->data['action'] = $action;
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/master/supplier/Supplier_DataIntable',$this->data,true);
        echo json_encode($arr_result);
    }

    public function Supplier_DataIntable_server_side()
    {
        $this->auth_ajax();
        $action = $this->input->post('action');
        $condition = '';
        if ($action == 'All_approval') {
           $condition = ' and a.Approval = 1';
        }
        elseif ($action == 'non_approval') {
            $condition = ' and a.Approval = 0';
        }

        $requestData= $_REQUEST;
        $sql = 'select count(*) as total from db_purchasing.m_supplier as a where a.Active = 1 '.$condition;
        $query = $this->db->query($sql)->result_array();
        $totalData = $query[0]['total'];
        $No = $requestData['start'] + 1;

        $sql = 'select a.*,b.Name as NameCreated,c.CategoryName
                from db_purchasing.m_supplier as a 
                join db_employees.employees as b on a.CreatedBy = b.NIP
                join db_purchasing.m_categorysupplier as c on a.CategorySupplier = c.ID
               ';

        $sql.= ' where ( a.CodeSupplier LIKE "'.$requestData['search']['value'].'%" or a.NamaSupplier LIKE "%'.$requestData['search']['value'].'%" or a.PICName LIKE "'.$requestData['search']['value'].'%" or a.DetailInfo LIKE "%'.$requestData['search']['value'].'%" or c.CategoryName LIKE "'.$requestData['search']['value'].'%" or a.CategorySupplier LIKE "%'.$requestData['search']['value'].'%" or b.Name LIKE "%'.$requestData['search']['value'].'%" or a.DetailItem LIKE "%'.$requestData['search']['value'].'%"
                ) and a.Active = 1 and c.Active = 1'.$condition;
        $sql.= ' ORDER BY a.ID Desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
        $query = $this->db->query($sql)->result_array();

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];
            $nestedData[] = $No;
            $nestedData[] = $row['CodeSupplier'];
            $nestedData[] = '<label>'.$row['NamaSupplier'].'</label><br>'.$row['Website'].'<br>'.'PIC : '.$row['PICName'].'<br>'.'Alamat : '.$row['Alamat'];
            // $nestedData[] = $row['Website'];
            // $nestedData[] = $row['PICName'];
            // $nestedData[] = $row['Alamat'];
            $nestedData[] = $row['NoTelp'].' & '.$row['NoHp'];
            $nestedData[] = $row['CategoryName'];
            
            $DetailInfo = $row['DetailInfo'];
            $DetailInfo = json_decode($DetailInfo);
            $temp = '';
            if ($DetailInfo != "" || $DetailInfo != null) {
                $temp = '<ul>';
                foreach ($DetailInfo as $key => $value) {
                    $temp .= '<li>'.$key.' :  '.$value.'</li>';
                }

                $temp .= '</ul>';

            }

            $nestedData[] = $temp;
            $DetailItem = $row['DetailItem'];
            $DetailItem = json_decode($DetailItem);
            $temp = '';
            if ($DetailItem != "" || $DetailItem != null) {
                $temp = '<ul>';
                foreach ($DetailItem as $key => $value) {
                    $temp .= '<li>'.$key.' :  '.$value.'</li>';
                }

                $temp .= '</ul>';

            }
            $nestedData[] = $temp;

            if ($action == 'All_approval') {
                $btn = '<button type="button" class="btn btn-warning btn-edit btn-edit-supplier" code="'.$row['ID'].'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>&nbsp <button type="button" class="btn btn-danger btn-delete btn-delete-supplier" code="'.$row['ID'].'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
            }
            elseif ($action == 'non_approval')
            {
                $btn = '<button type="button" class="btn btn-default btn-edit btn-approve-supplier" code="'.$row['ID'].'"> <i class="fa fa-handshake-o" aria-hidden="true"></i> Approve</button>';
            }
            else
            {
                $btn = '';
            }

            $nestedData[] = $row['NameCreated'];
            $nestedData[] = $btn;
            $data[] = $nestedData;

            $No++;
        }

        // print_r($data);

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalData ),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function ApprovalSupplier()
    {
      $this->auth_ajax();
      $this->Supplier_DataIntable('non_approval');
    }

    public function import_data_catalog()
    {
      $rs = array(
          'status' => 0,
          'msg' => '',
      );
      if(isset($_FILES["fileData"]["name"]))
      { 
        $path = $_FILES["fileData"]["tmp_name"];
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load($path); // Empty Sheet
        $objWorksheet = $excel2->setActiveSheetIndex(0);
        $CountRow = $objWorksheet->getHighestRow();
        for ($i=2; $i < ($CountRow + 1); $i++) {
          $Item = $objWorksheet->getCellByColumnAndRow(0, $i)->getCalculatedValue();
          $Desc = $objWorksheet->getCellByColumnAndRow(1, $i)->getCalculatedValue();
          $EstimaValue = $objWorksheet->getCellByColumnAndRow(2, $i)->getCalculatedValue();
          $Departement = $objWorksheet->getCellByColumnAndRow(3, $i)->getCalculatedValue();
          // check department
          $chk = $this->m_budgeting->SearchDepartementBudgeting($Departement);
          if (count($chk) == 0) {
            $chk = $this->m_budgeting->SearchDepartementBudgetingByName($Departement);
            if (count($chk) == 0) {
              $rs['msg'] =  'Departement is unknown';
              echo json_encode($rs);
              die();
            }
          }
          $DetailCatalog = $objWorksheet->getCellByColumnAndRow(4, $i)->getCalculatedValue();

          $dataSave = array(
            'Item' => $Item,
            'Desc' => $Desc,
            'EstimaValue' => $EstimaValue,
            'Departement' => $Departement,
            'DetailCatalog' => $DetailCatalog,
            'Approval' => 1,
            'ApprovalAt' => date("Y-m-d H:i:s"),
            'ApprovalBy' => $this->session->userdata("NIP"),
            'CreatedAt' => date("Y-m-d H:i:s"),
            'CreatedBy' => $this->session->userdata("NIP"),
          );

          $this->db->insert('db_purchasing.m_catalog',$dataSave);
           $rs['status'] = 1;
        }
        echo json_encode($rs);

      }
    }

}
