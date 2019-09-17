<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_cooperation extends Cooperation_Controler {
    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->data['department'] = parent::__getDepartement();
    }

    public function kerja_sama_perguruan_tinggi()
    {
      $content = $this->load->view('page/'.$this->data['department'].'/kerjasama-perguruan-tinggi/index',$this->data,true);
      $this->temp($content);
    }

    public function kerja_sama_perguruan_tinggi_submit()
    {
        $rs = ['Status' => 0,'msg' => ''];
        $Input = $this->getInputToken();
        $mode = $Input['mode'];
        switch ($mode) {
            case 'add':
                // Save data kerjasama
                $kerjasama = json_decode(json_encode($Input['kerjasama']),true);
                $Upload = $this->m_master->uploadDokumenMultiple(uniqid(),'BuktiUpload',$path = './uploads/cooperation');
                $Upload = json_encode($Upload);
                $kerjasama['BuktiUpload'] = $Upload;
                // add upload bukti kerjasama
                $this->db->insert('db_cooperation.kerjasama',$kerjasama);
                $insert_id = $this->db->insert_id();
                $ID = $insert_id;

                $Perjanjian = json_decode(json_encode($Input['k_perjanjian']),true);
                $arr_post_file_perjanjian = ['Upload_MOU','Upload_MOA','Upload_IA'];
                $k_perjanjian = [];
                for ($i=0; $i < count($arr_post_file_perjanjian); $i++) { 
                    $PostName = $arr_post_file_perjanjian[$i];
                    $ex = explode('_', $PostName);
                    for ($j=0; $j < count($Perjanjian); $j++) { 
                        if ($ex[1] == $Perjanjian[$j]) {

                            // upload file
                            $Upload = $this->m_master->uploadDokumenMultiple(uniqid(),$PostName,$path = './uploads/cooperation');
                            $Upload = json_encode($Upload); 

                            $k_perjanjian[] = array(
                                'KerjasamaID' => $ID,
                                'Type' => $Perjanjian[$j],
                                'Upload' => $Upload,
                            );    
                            break;
                        }
                    }
                }
                
                $this->db->insert_batch('db_cooperation.k_perjanjian', $k_perjanjian);
                // insert department
                $DepartmentSelected = json_decode(json_encode($Input['k_department']),true);
                $k_department = [];
                for ($i=0; $i < count($DepartmentSelected); $i++) { 
                    $k_department[] = array(
                        'KerjasamaID' => $ID,
                        'Departement' => $DepartmentSelected[$i],
                    );
                }
                $this->db->insert_batch('db_cooperation.k_department', $k_department);
                $rs['Status'] = 1;
                break;
            
            default:
                # code...
                break;
        }

        echo json_encode($rs);


    }

}
