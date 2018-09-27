<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_master extends Budgeting_Controler {
    public $Msg = array(
            'Duplicate' => 'The data duplicate, Please check',
            'NotAction' => 'The data has been used for transaction, Cannot be action',
            'Error' => 'Error connection',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->data['department'] = parent::__getDepartement(); 
    }

    public function catalog()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/budgeting/master/catalog',$this->data,true);
        $this->temp($content);
    }

    public function supplier()
    {
        $content = $this->load->view('page/'.$this->data['department'].'/budgeting/master/supplier',$this->data,true);
        $this->temp($content);
    }

    public function InputCatalog()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/master/catalog/InputCatalog',$this->data,true);
        echo json_encode($arr_result);
    }

    public function InputCatalog_FormInput()
    {
        $this->auth_ajax();
        $Input = $this->getInputToken();
        $this->data['action'] = $Input['action'];
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/master/catalog/FormInputCatalog',$this->data,true);
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

        $filename = $Input['Item'].'_Uploaded';
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
                           'Photo' => $uploadFile,
                           'Departement' => $Departement,
                           'DetailCatalog' => $Detail,
                           'CreatedBy' => $this->session->userdata('NIP'),
                           'CreatedAt' => date('Y-m-d'),
                           'Approval' => 1,
                           'ApprovalBy' => $this->session->userdata('NIP'),
                           'ApprovalAt' => date('Y-m-d H:i:s'),
                       );
                       $this->db->insert('db_budgeting.m_catalog', $dataSave);
                       echo json_encode(array('msg' => 'The file has been successfully uploaded','status' => 1));
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
                        'Photo' => '',
                        'Departement' => $Departement,
                        'DetailCatalog' => $Detail,
                        'CreatedBy' => $this->session->userdata('NIP'),
                        'CreatedAt' => date('Y-m-d'),
                        'Approval' => 1,
                        'ApprovalBy' => $this->session->userdata('NIP'),
                        'ApprovalAt' => date('Y-m-d H:i:s'),
                    );
                    $this->db->insert('db_budgeting.m_catalog', $dataSave);
                    echo json_encode(array('msg' => 'The file has been successfully uploaded','status' => 1));
                }

                break;
            case 'edit':
                # code...
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

    public function Catalog_DataIntable()
    {
        $this->auth_ajax();
        $arr_result = array('html' => '','jsonPass' => '');
        $arr_result['html'] = $this->load->view('page/'.$this->data['department'].'/budgeting/master/catalog/Catalog_DataIntable',$this->data,true);
        echo json_encode($arr_result);
    }

    public function Catalog_DataIntable_server_side()
    {
        $this->auth_ajax();
        $requestData= $_REQUEST;
        $sql = 'select count(*) as total from db_budgeting.m_catalog where Active = 1 and Approval = 1';
        $query = $this->db->query($sql)->result_array();
        $totalData = $query[0]['total'];
        $No = $requestData['start'] + 1;

        $sql = 'select a.*,b.Name as NameCreated,c.NameDepartement
                from db_budgeting.m_catalog as a 
                join db_employees.employees as b on a.CreatedBy = b.NIP
                join (
                select * from (
                select CONCAT("AC.",ID) as ID, NameEng as NameDepartement from db_academic.program_study
                UNION
                select CONCAT("NA.",ID) as ID, Division as NameDepartement from db_employees.division
                ) aa
                ) as c on a.Departement = c.ID
               ';

        $sql.= ' where a.Item LIKE "'.$requestData['search']['value'].'%" or a.Desc LIKE "'.$requestData['search']['value'].'%" or c.NameDepartement LIKE "'.$requestData['search']['value'].'%" or a.DetailCatalog LIKE "'.$requestData['search']['value'].'%"
                ';
        $sql.= ' ORDER BY a.ID Desc LIMIT '.$requestData['start'].' , '.$requestData['length'].' ';
        $query = $this->db->query($sql)->result_array();

        $data = array();
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];
            $nestedData[] = $No;
            $nestedData[] = $row['Item'];
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
            $nestedData[] = $row['NameDepartement'];
            $nestedData[] = $row['DetailCatalog'];
            $nestedData[] = $row['NameCreated'];
            $nestedData[] = '';
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

}
