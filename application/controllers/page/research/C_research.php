<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_research extends Research_Controler {
	public $data = array();
    function __construct()
    {
        parent::__construct();
    }

   public function portal_eksternal(){
   	$page['department'] = parent::__getDepartement();
   	$content = $this->load->view('page/'.$page['department'].'/portal_eksternal/index',$page,true);
   	$this->menu_portal_eksternal($content);
   }

   public function monitoring_research(){
    $page['department'] = parent::__getDepartement();
    $content = $this->load->view('page/'.$page['department'].'/monitoring_research',$page,true);
    $this->temp($content);
   }

   public function all_research_data()
    {
      $rs = ['status' => 0,'msg' => '','callback' => [] ]; 
      $datatoken =  $this->getInputToken();
      $datatoken = json_decode(json_encode($datatoken),true);

        if($datatoken['action']=='viewData'){  
         
         $requestData= $_REQUEST;
         
        //print_r($requestData['search']['value']);die();
        $totalData = $this->db->query('SELECT 
            count(*) as total from (
                            select 1 from
                            db_research.litabmas AS a 
                                LEFT JOIN db_research.skim_kegiatan AS b ON (b.Kd_skim = a.ID_skim)
                                LEFT JOIN db_employees.employees AS xx ON (xx.NIP = a.NIP)
                                LEFT JOIN (SELECT DISTINCT a.ID_Litabmas, usr.NIP
                                    FROM db_research.list_anggota_penelitian AS a
                                    LEFT JOIN db_research.master_anggota_penelitian AS b ON (a.ID_anggota = b.ID)
                                    LEFT JOIN (
                                       select concat("ekd.",a.ID)  as ID_user,a.Nama,a.NIDN,a.NIP,a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                                       where F_mhs != 1
                                       UNION
                                       select concat("dsn.",a.NIP)  as ID_user,a.Name,a.NIDN,a.NIP,"" as NIM,"1" as Type_anggota,a.EmailPU as Email from db_employees.employees as a
                                       UNION
                                       select concat("mhs.",a.NPM) as ID_user,a.Name,"","",a.NPM as NIM,"2" as Type_anggota,a.EmailPU as Email from db_academic.auth_students as a
                                       UNION
                                       select concat("ekm.",a.ID)  as ID_user,a.Nama,"","",a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                                       where F_mhs = 1) usr
                                    on usr.ID_user = b.ID_user) AS c ON (a.ID_litabmas = c.ID_Litabmas) GROUP BY a.Judul_litabmas
                    )xx
            ')->result_array();

          $sql = 'SELECT a.ID_litabmas, a.Judul_litabmas, a.ID_thn_usulan, a.ID_thn_laks,  a.ID_skim, b.Nm_skim, a.Lama_kegiatan, a.Last_update, a.Status_data, xx.Name, a.Jenis_usulan, a.Lama_waktu
                    FROM db_research.litabmas AS a 
                    LEFT JOIN db_research.skim_kegiatan AS b ON (b.Kd_skim = a.ID_skim)
                    LEFT JOIN db_employees.employees AS xx ON (xx.NIP = a.NIP)
                    LEFT JOIN (SELECT DISTINCT a.ID_Litabmas, usr.NIP
                        FROM db_research.list_anggota_penelitian AS a
                        LEFT JOIN db_research.master_anggota_penelitian AS b ON (a.ID_anggota = b.ID) 
                        LEFT JOIN (
                             select concat("ekd.",a.ID)  as ID_user,a.Nama,a.NIDN,a.NIP,a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                             where F_mhs != 1
                             UNION
                             select concat("dsn.",a.NIP)  as ID_user,a.Name,a.NIDN,a.NIP,"" as NIM,"1" as Type_anggota,a.EmailPU as Email from db_employees.employees as a
                             UNION
                             select concat("mhs.",a.NPM) as ID_user,a.Name,"","",a.NPM as NIM,"2" as Type_anggota,a.EmailPU as Email from db_academic.auth_students as a
                             UNION
                             select concat("ekm.",a.ID)  as ID_user,a.Nama,"","",a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                             where F_mhs = 1) usr
                          on usr.ID_user = b.ID_user ) AS c ON (a.ID_litabmas = c.ID_Litabmas)';


        if( !empty($requestData['search']['value']) ) {
            $sql.= ' WHERE a.Judul_litabmas LIKE "%'.$requestData['search']['value'].'%" ';

        }
        
        $sql.= ' GROUP BY a.Judul_litabmas ORDER BY a.ID_litabmas DESC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';
       
        $query = $this->db->query($sql)->result_array();
        $no = $requestData['start']+1;
        $data = array();

        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];
            $token = $this->jwt->encode($row["ID_litabmas"],'UAP)(*');

            $sql2 = 'SELECT ID_litabmas FROM db_research.litabmas WHERE ID_litabmas = "'.$row["ID_litabmas"].'" ';
            $data2 =$this->db->query($sql2, array())->result_array(); 

            
                $butlist = '<div style="text-align: center;">
                    <a class="btn btn-primary btn-circle" data-toggle="tooltip" href="'.base_url('research/detail-research/'.$token).'" data-placement="top" title="Details"><i class="glyphicon glyphicon-th-list"></i></a>  
                    </div>';
            

            if($row["Last_update"] == null) {
                $date_update = "-";
            } else {
                $date_update = date("d M Y H:i", strtotime($row["Last_update"]));
            }

            $nameuser = strtoupper($row["Name"]);
            $nestedData[] = '<div  style="text-align:center;">'.$no.'</div>';
            $nestedData[] = '<div style="text-align: left;">'.$row["Judul_litabmas"].'</div>';
            $nestedData[] = '<div style="text-align: center;">'.$row["ID_thn_usulan"].' - '.$row["ID_thn_laks"].'</div>';
            $nestedData[] = '<div style="text-align: center;">'.$row["Lama_kegiatan"].' '.$row["Lama_waktu"].'</div>';
            $nestedData[] = '<div style="text-align: center;">'.$nameuser.'</div>';
            $nestedData[] = '<div style="text-align: center;">'.$date_update.'</div>';
            $nestedData[] = '<div style="text-align: center;">'.$row["Status_data"]. ' '.$row["Jenis_usulan"]. '</div>';
            $nestedData[] = ''.$butlist.'';
            $no++;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval($totalData[0]['total']),
            "recordsFiltered" => intval( $totalData[0]['total'] ),
            "data"            => $data
        );
        echo json_encode($json_data);
        
        } 
    }

    public function detail_research($token){
        
        // $data_arr = $this->getInputToken($token);
        // $datatoken = json_decode(json_encode($data_arr),true);
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
    
        $ID = $data_arr['0'];
    
   

        $query = 'SELECT a.ID_Litabmas, a.User_create
                  FROM db_research.list_anggota_penelitian AS a
                  LEFT JOIN db_research.master_anggota_penelitian AS b ON (a.ID_anggota = b.ID)
                  WHERE a.ID_Litabmas = "'.$ID.'" LIMIT 1 ';
        $datas=$this->db->query($query, array())->result_array(); 
        //print_r($query); exit();
        
        if(count($datas)>0) {

            $sql = 'SELECT a.*,  b.Nm_skim, c.Nm_kel_bidang, d.Name_University, a.Judul_litabmas, e.Judul_litabmas AS judul_lanjutan, f.SumberDana, g.NameEng, h.Name AS NamaSemester, j.Name AS NmThn_akademik
            FROM db_research.litabmas AS a 
            LEFT JOIN db_research.skim_kegiatan AS b ON (b.Kd_skim = a.ID_skim)
            LEFT JOIN db_research.kelompok_bidang AS c ON (c.Kode_kel_bidang = a.ID_kel_bidang)
            LEFT JOIN db_research.university AS d ON (d.ID = a.ID_lemb_iptek)
            LEFT JOIN db_research.litabmas e ON (e.ID_litabmas = a.ID_lanjutan_litabmas)
            LEFT JOIN db_agregator.sumber_dana f ON (f.ID = a.ID_sumberdana)
            LEFT JOIN db_academic.mata_kuliah g ON (g.MKCode = a.MKCode)
            LEFT JOIN db_academic.semester h ON (h.ID = a.SemesterID)
            LEFT JOIN db_academic.semester j ON (j.ID = a.Tahun_akademik)
            WHERE a.ID_litabmas = "'.$datas[0]['ID_Litabmas'].'" AND a.NIP = "'.$datas[0]['User_create'].'" AND a.Stat_aktif= 1 ';
            //print_r($sql); exit();
            
            $data['arr_research'] = $this->db->query($sql, array())->result_array();   
            $data['department'] = parent::__getDepartement();
            $content = $this->load->view('page/'.$data['department'].'/research_detail',$data,true);
            $this->temp($content);

        }   
    }


    public function LoadDataResearch(){
        
        $data_arr = $this->getInputToken();
        $NIP = $this->session->userdata('lecturer_NIP');
        // print_r($data_arr); die();
        if(count($data_arr)>0){
            if ($data_arr['action']=='e_loadformat_isilaporan') {
                $NIP = $this->session->userdata('lecturer_NIP');
                $ID_litabmas = $data_arr['id_litabmas'];

                $sql = 'SELECT a.ID, a.Isi_laporan, a.Date_create, b.Nama_format
                        FROM db_research.litabmas_isi_laporan AS a 
                        LEFT JOIN db_research.master_format_laporan AS b ON (a.Jenis_format = b.ID)
                        WHERE a.ID_litabmas = "'.$ID_litabmas.'" AND a.User_create = "'.$NIP.'" ';
            
                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }

            else if ($data_arr['action']=='e_loadbiayaresearch') {
                $ID_litabmas = $data_arr['id_litabmas'];

                $sql = 'SELECT a.ID, b.Nama_barang, a.Harga_satuan, a.Tipe_satuan, a.Qty, a.Total_harga, a.Keterangan, a.Realisasi, c.Status_data, c.Jenis_usulan
                        FROM db_research.litabmas_anggaran AS a 
                        LEFT JOIN db_research.master_katalog_barang AS b ON (a.ID_Katalog = b.ID)
                         LEFT JOIN db_research.litabmas as c ON (a.ID_litabmas  = c.ID_litabmas)
                        WHERE a.ID_litabmas = "'.$ID_litabmas.'" AND a.User_create = "'.$NIP.'" ';

                $data=$this->db->query($sql, array())->result_array();   

                return print_r(json_encode($data));
            }

            else if ($data_arr['action']=='edit_loadupload') {
                $ID_litabmas = $data_arr['id'];
                $nilai = "1";
                
                $sql = 'SELECT a.ID_dok, a.Nm_dok, a.File_name, a.Ket_dok, a.Url, b.Nm_jns_dok FROM db_research.dokumen a
                        INNER JOIN db_research.jenis_dokumen b ON (b.ID_jns_dok = a.ID_jns_dok) WHERE a.Stat_aktif = "'.$nilai.'" AND a.ID_litabmas= "'.$ID_litabmas.'" AND a.Csf= 1 ';
                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }
            else if ($data_arr['action']=='edit_loaddosen') {

                $ID_litabmas = $data_arr['id'];
                
                $sql = 'SELECT a.ID, a.ID_anggota, a.Peran, a.Status_aktif, a.Disabled, usr.Nama, usr.NIP
                        FROM db_research.list_anggota_penelitian AS a
                        INNER JOIN db_research.master_anggota_penelitian AS b ON (a.ID_anggota = b.ID)
                        LEFT JOIN (
                           select concat("ekd.",a.ID)  as ID_user,a.Nama,a.NIDN,a.NIP,a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                           where F_mhs != 1
                           UNION
                           select concat("dsn.",a.NIP)  as ID_user,a.Name,a.NIDN,a.NIP,"" as NIM,"1" as Type_anggota,a.EmailPU as Email from db_employees.employees as a 
                           UNION
                           select concat("mhs.",a.NPM) as ID_user,a.Name,"","",a.NPM as NIM,"2" as Type_anggota,a.EmailPU as Email from db_academic.auth_students as a 
                           UNION
                           select concat("ekm.",a.ID)  as ID_user,a.Nama,"","",a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a  
                           where F_mhs = 1) usr
                         on usr.ID_user = b.ID_user
                        WHERE a.ID_litabmas = "'.$ID_litabmas.'" AND b.Type_anggota = "DSN" ';


                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }
            else if ($data_arr['action']=='edit_loadmahasiswa') {
                $ID_litabmas = $data_arr['id'];
                $nilai = "1";
                
                $sql = 'SELECT a.ID, a.ID_anggota, a.Peran, a.Status_aktif, usr.Nama, usr.NIM 
                        FROM db_research.list_anggota_penelitian AS a
                        INNER JOIN db_research.master_anggota_penelitian AS b ON (a.ID_anggota = b.ID)
                         LEFT JOIN (
                             select concat("ekd.",a.ID)  as ID_user,a.Nama,a.NIDN,a.NIP,a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                             where F_mhs != 1
                             UNION
                             select concat("dsn.",a.NIP)  as ID_user,a.Name,a.NIDN,a.NIP,"" as NIM,"1" as Type_anggota,a.EmailPU as Email from db_employees.employees as a 
                             UNION
                             select concat("mhs.",a.NPM) as ID_user,a.Name,"","",a.NPM as NIM,"2" as Type_anggota,a.EmailPU as Email from db_academic.auth_students as a 
                             UNION
                             select concat("ekm.",a.ID)  as ID_user,a.Nama,"","",a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a  
                             where F_mhs = 1) usr
                          on usr.ID_user = b.ID_user
                        WHERE a.ID_litabmas = "'.$ID_litabmas.'" AND b.Type_anggota = "MHS" ';

                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }
            else if ($data_arr['action']=='edit_loadkolaborator') {

                $NIP = $this->session->userdata('lecturer_NIP');
                $id_litabmas = $data_arr['id_litabmas'];

                $sql = 'SELECT a.ID, a.ID_anggota, a.Peran, a.Status_aktif, usr.Nama as Name_kolaborator
                        FROM db_research.list_anggota_penelitian AS a
                        LEFT JOIN db_research.master_anggota_penelitian AS b ON (a.ID_anggota = b.ID)
                        LEFT JOIN (
                           select concat("ekd.",a.ID)  as ID_user,a.Nama,a.NIDN,a.NIP,a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                           where F_mhs != 1
                           UNION
                           select concat("dsn.",a.NIP)  as ID_user,a.Name,a.NIDN,a.NIP,"" as NIM,"1" as Type_anggota,a.EmailPU as Email from db_employees.employees as a 
                           UNION
                           select concat("mhs.",a.NPM) as ID_user,a.Name,"","",a.NPM as NIM,"2" as Type_anggota,a.EmailPU as Email from db_academic.auth_students as a 
                           UNION
                           select concat("ekm.",a.ID)  as ID_user,a.Nama,"","",a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a  
                           where F_mhs = 1) usr
                         on usr.ID_user = b.ID_user
                        WHERE a.ID_Litabmas = "'.$id_litabmas.'" AND b.Type_anggota = "KBR" ';

                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }
            else if ($data_arr['action']=='e_loadreviewer_research') {
                $NIP = $this->session->userdata('lecturer_NIP');
                $id_litabmas = $data_arr['id_litabmas'];
                
                $sql = 'SELECT a.ID, a.ID_anggota, usr.Nama, usr.NIP,usr.Email, b.Luar_internal
                        FROM db_research.list_anggota_penelitian AS a
                        LEFT JOIN db_research.master_anggota_penelitian AS b ON (a.ID_anggota = b.ID)
                        LEFT JOIN (
                           select concat("ekd.",a.ID)  as ID_user,a.Nama,a.NIDN,a.NIP,a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                           where F_mhs != 1
                           UNION
                           select concat("dsn.",a.NIP)  as ID_user,a.Name,a.NIDN,a.NIP,"" as NIM,"1" as Type_anggota,a.EmailPU as Email from db_employees.employees as a 
                           UNION
                           select concat("mhs.",a.NPM) as ID_user,a.Name,"","",a.NPM as NIM,"2" as Type_anggota,a.EmailPU as Email from db_academic.auth_students as a 
                           UNION
                           select concat("ekm.",a.ID)  as ID_user,a.Nama,"","",a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a  
                           where F_mhs = 1) usr 
                          on usr.ID_user = b.ID_user
                        WHERE a.ID_Litabmas = "'.$id_litabmas.'" AND b.Type_anggota = "REV" ';

                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }

        } 
    }

}    