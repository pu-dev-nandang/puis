<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_abdimas extends Abdimas_Controler {
	public $data = array();
    function __construct()
    {
        parent::__construct();
    }


   public function monitoring_abdimas(){
    $page['department'] = parent::__getDepartement();
    $content = $this->load->view('page/'.$page['department'].'/monitoring_abdimas',$page,true);
    $this->temp($content);
   }

   public function all_abdimas_data()
    {
      $rs = ['status' => 0,'msg' => '','callback' => [] ]; 
      $datatoken =  $this->getInputToken();
      $datatoken = json_decode(json_encode($datatoken),true);

        if($datatoken['action']=='viewData'){  
      
        	$requestData= $_REQUEST;

        	$totalData = $this->db->query('
        	            select count(*) as total from (
        	                select 1 FROM db_research.pengabdian_masyarakat AS a 
        	                LEFT JOIN db_agregator.sumber_dana AS b ON (a.ID_sumberdana = b.ID)
        	                LEFT JOIN db_employees.employees AS xx ON (a.NIP = xx.NIP)
        	                LEFT JOIN (SELECT DISTINCT a.ID_PKM, usr.NIP
        	                    FROM db_research.list_anggota_pkm AS a
        	                    LEFT JOIN db_research.master_anggota_pkm AS b ON (a.ID_anggota = b.ID)
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
        	                    ) AS c ON (a.ID_PKM = c.ID_PKM) group by a.Judul_PKM
        	                
        	            )xx
        	           ')->result_array();

        	    $sql = 'SELECT a.ID_PKM, a.Judul_PKM, a.Lama_kegiatan, a.Lokasi_kegiatan, b.SumberDana, a.Last_update, a.Last_sync, xx.Name, a.Status_data, a.Lama_waktu
        	            FROM db_research.pengabdian_masyarakat AS a 
        	            LEFT JOIN db_agregator.sumber_dana AS b ON (a.ID_sumberdana = b.ID)
        	            LEFT JOIN db_employees.employees AS xx ON (a.NIP = xx.NIP)
        	            LEFT JOIN (SELECT DISTINCT a.ID_PKM, usr.NIP
        	                FROM db_research.list_anggota_pkm AS a
        	                LEFT JOIN db_research.master_anggota_pkm AS b ON (a.ID_anggota = b.ID)
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
        	                ) AS c ON (a.ID_PKM = c.ID_PKM)';
        	             

        	if( !empty($requestData['search']['value']) ) {

        	    $sql.= ' WHERE a.Judul_PKM LIKE "%'.$requestData['search']['value'].'%" ';
        	    //$sql.= 'AND a.Lokasi_kegiatan LIKE "%'.$requestData['search']['value'].'%") ';
        
        	}

        	$sql.= 'group by a.Judul_PKM ORDER BY a.ID_PKM DESC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';
        	$query = $this->db->query($sql)->result_array();
        	$no = $requestData['start']+1;

        	$data = array();
        	for($i=0;$i<count($query);$i++){
        	    $nestedData=array();
        	    $row = $query[$i];
        	    $token = $this->jwt->encode($row["ID_PKM"],'UAP)(*');
        	    $date_update = date('d M Y H:i', strtotime($row["Last_sync"])); 

        	    $sql2 = 'SELECT ID_PKM FROM db_research.pengabdian_masyarakat WHERE ID_PKM = "'.$row["ID_PKM"].'"';
        	    $data2 =$this->db->query($sql2, array())->result_array(); 

        	   
    	        $butlist = '<div style="text-align: center;">
    	         <a type="button" class="btn btn-sm btn-primary btn-circle" publikasid="'.$row["ID_PKM"].'" data-toggle="tooltip" href="'.base_url('abdimas/detail-abdimas/'.$token).'" data-placement="top" title="Details"><i class="glyphicon glyphicon-th-list"></i></a> 
    	          </div>';
        	   

        	    $nameuser = strtoupper($row["Name"]);

        	    $nestedData[] = '<div  style="text-align:center;">'.$no.'</div>';
        	    $nestedData[] = '<div style="text-align: left;">'.$row["Judul_PKM"].'</div>';
        	    $nestedData[] = '<div style="text-align: center;">'.$row["Lokasi_kegiatan"].'</div>';
        	    $nestedData[] = '<div style="text-align: center;">'.$row["Lama_kegiatan"].' '.$row["Lama_waktu"].'</div>';
        	    $nestedData[] = '<div style="text-align: center;">'.$row["SumberDana"].'</div>';
        	    $nestedData[] = '<div style="text-align: center;">'.$nameuser.'</div>';
        	    $nestedData[] = '<div style="text-align: center;">'.$date_update.'</div>';
        	    $nestedData[] = ''.$butlist.'';
        	    $no++;
        	    $data[] = $nestedData;
        	}

        	$json_data = array(
        	    "draw"            => intval( $requestData['draw'] ),
        	    "recordsTotal"    => intval($totalData[0]['total']),
        	    "recordsFiltered" => intval($totalData[0]['total']),
        	    "data"            => $data
        	);
        	echo json_encode($json_data);
        
        } 
    }

    public function detail_abdimas($token){
        
        $key = "UAP)(*";
        $data_arr = (array) $this->jwt->decode($token,$key);
        $ID = $data_arr['0'];
    
        $NIP = $this->session->userdata('lecturer_NIP');

        $query = 'SELECT a.ID_PKM, usr.NIP, a.User_create
                  FROM db_research.list_anggota_pkm AS a
                  LEFT JOIN db_research.master_anggota_pkm AS b ON (a.ID_anggota = b.ID)
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
                  WHERE a.ID_PKM = "'.$ID.'" LIMIT 1 ';
        $datas=$this->db->query($query, array())->result_array(); 
        
        if(count($datas)>0) {

            $sql = 'SELECT a.*, c.Nm_skim, d.SumberDana, e.Name, f.NameEng, g.Name_University, b.Nm_kel_bidang, h.Judul_PKM as NamaPKM
            FROM  db_research.pengabdian_masyarakat AS a 
            LEFT JOIN db_research.kelompok_bidang AS b ON (a.ID_kel_bidang = b.Kode_kel_bidang)
            LEFT JOIN db_research.skim_kegiatan_pkm AS c ON (a.ID_skim = c.Kd_skim)
            LEFT JOIN db_agregator.sumber_dana AS d ON (a.ID_sumberdana = d.ID)
            LEFT JOIN db_academic.semester AS e ON (a.SemesterID = e.ID)
            LEFT JOIN db_academic.mata_kuliah AS f ON (a.MKCode = f.MKCode)
            LEFT JOIN db_research.university AS g ON (a.ID_lemb_iptek = g.ID) 
            LEFT JOIN db_research.pengabdian_masyarakat AS h ON (a.ID_pengabdian_existing = h.ID_PKM) 
            WHERE a.ID_PKM ="'.$datas[0]['ID_PKM'].'" AND a.Stat_aktif= "1" ';
       
            $data['arr_pkm'] = $this->db->query($sql, array())->result_array();   
            $data['department'] = parent::__getDepartement();
            $content = $this->load->view('page/'.$data['department'].'/abdimas_detail',$data,true);
            $this->temp($content);

        }
    }

    public function loadlistpkm(){
        $data_arr = $this->getInputToken();
        $NIP = $this->session->userdata('lecturer_NIP');
        if(count($data_arr)>0){

            if($data_arr['action']=='detail_loadupload_pkm'){
                $id_pkm = $data_arr['id_pkm'];
                $nilai = "1";
                
                $sql = 'SELECT a.ID_dok, a.Nm_dok, a.File_name, a.Ket_dok, a.Url, b.Nm_jns_dok FROM db_research.dokumen a
                        INNER JOIN db_research.jenis_dokumen b ON (b.ID_jns_dok = a.ID_jns_dok) WHERE a.ID_PKM= "'.$id_pkm.'" AND a.Csf= 1 AND a.Untuk_dokumen = "PKM" ';
                        
                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }

            else if ($data_arr['action']=='detail_loaddosen_pkm') {
                $id_pkm = $data_arr['id_pkm'];
                
                $sql = 'SELECT a.ID, a.ID_anggota, a.Peran, a.Status_aktif, usr.Nama, usr.NIP
                        FROM db_research.list_anggota_pkm AS a
                        INNER JOIN db_research.master_anggota_pkm AS b ON (a.ID_anggota = b.ID)
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
                        WHERE a.ID_PKM = "'.$id_pkm.'" AND b.Type_anggota = "DSN" ';
                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }

            else if ($data_arr['action']=='detail_loadkolaborator_pkm') {
                $id_pkm = $data_arr['id_pkm'];
                $nilai = "1";
                
                $sql = 'SELECT a.ID, a.ID_anggota, a.Peran, a.Status_aktif, usr.Nama as Name_kolaborator
                        FROM db_research.list_anggota_pkm AS a
                        LEFT JOIN db_research.detail_kolaborator_pkm AS b ON (a.ID_anggota = b.ID_anggota)
                        LEFT JOIN db_research.master_anggota_pkm AS c ON (a.ID_anggota = c.ID)
                        LEFT JOIN (
                          select concat("ekd.",a.ID)  as ID_user,a.Nama,a.NIDN,a.NIP,a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                          where F_mhs != 1
                          UNION
                          select concat("dsn.",a.NIP)  as ID_user,a.Name,a.NIDN,a.NIP,"" as NIM,"1" as Type_anggota,a.EmailPU as Email from db_employees.employees as a
                          UNION
                          select concat("mhs.",a.NPM) as ID_user,a.Name,"","",a.NPM as NIM,"2" as Type_anggota,a.EmailPU as Email from db_academic.auth_students as a
                          UNION
                          select concat("ekm.",a.ID)  as ID_user,a.Nama,"","",a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                          where F_mhs = 1) usr on usr.ID_user = c.ID_user
                        WHERE a.ID_PKM = "'.$id_pkm.'" AND c.Type_anggota = "KBR" ';
                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }

            else if ($data_arr['action']=='e_loadreviewer_pkm') {
                $nilai = "1";
                $id_pkm = $data_arr['id_pkm'];
                
                $sql = 'SELECT  a.ID, a.ID_anggota, usr.Nama, usr.NIP,usr.Email, b.Luar_internal
                        FROM db_research.list_anggota_pkm AS a
                        INNER JOIN db_research.master_anggota_pkm AS b ON (a.ID_anggota = b.ID)
                        LEFT JOIN (
                          select concat("ekd.",a.ID)  as ID_user,a.Nama,a.NIDN,a.NIP,a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                          where F_mhs != 1
                          UNION
                          select concat("dsn.",a.NIP)  as ID_user,a.Name,a.NIDN,a.NIP,"" as NIM,"1" as Type_anggota,a.EmailPU as Email from db_employees.employees as a
                          UNION
                          select concat("mhs.",a.NPM) as ID_user,a.Name,"","",a.NPM as NIM,"2" as Type_anggota,a.EmailPU as Email from db_academic.auth_students as a
                          UNION
                          select concat("ekm.",a.ID)  as ID_user,a.Nama,"","",a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                          where F_mhs = 1) usr on usr.ID_user = b.ID_user 
                        WHERE a.ID_PKM = "'.$id_pkm.'" AND b.Type_anggota = "REV" ';
                
                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }

            else if($data_arr['action']=='detail_loadmahasiswa_pkm'){

                $id_pkm = $data_arr['id_pkm'];
                $sql = 'SELECT a.ID, a.ID_anggota, a.Peran, usr.NIM, usr.Nama, a.Status_aktif
                        FROM db_research.list_anggota_pkm AS a
                        INNER JOIN db_research.master_anggota_pkm AS b ON (b.ID = a.ID_anggota) 
                        LEFT JOIN (
                          select concat("ekd.",a.ID)  as ID_user,a.Nama,a.NIDN,a.NIP,a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                          where F_mhs != 1
                          UNION
                          select concat("dsn.",a.NIP)  as ID_user,a.Name,a.NIDN,a.NIP,"" as NIM,"1" as Type_anggota,a.EmailPU as Email from db_employees.employees as a
                          UNION
                          select concat("mhs.",a.NPM) as ID_user,a.Name,"","",a.NPM as NIM,"2" as Type_anggota,a.EmailPU as Email from db_academic.auth_students as a
                          UNION
                          select concat("ekm.",a.ID)  as ID_user,a.Nama,"","",a.NIM, "0" as Type_anggota,a.Email as Email from db_research.master_user_research as a 
                          where F_mhs = 1) usr  on usr.ID_user = b.ID_user 
                        WHERE a.ID_PKM = "'.$id_pkm.'" AND b.Type_anggota = "MHS" ';
                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }

            else if ($data_arr['action']=='e_loadformat_isilaporan_PKM') {
                $id_pkm = $data_arr['id_pkm'];

                $sql = 'SELECT a.ID, a.Isi_laporan, a.Date_create, b.Nama_format
                        FROM db_research.pengabdian_masyarakat_isi_laporan AS a 
                        LEFT JOIN db_research.master_format_laporan AS b ON (a.Jenis_format = b.ID)
                        WHERE a.ID_PKM = "'.$id_pkm.'" ';
            
                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }

            else if ($data_arr['action']=='e_loadbiayaresearch_pkm') {
                $id_pkm = $data_arr['id_pkm'];

                $sql = 'SELECT a.ID, b.Nama_barang, a.Harga_satuan, a.Tipe_satuan, a.Qty, a.Total_harga, a.Keterangan, a.Realisasi
                        FROM db_research.pengabdian_masyarakat_anggaran AS a 
                        LEFT JOIN db_research.master_katalog_barang AS b ON (a.ID_Katalog = b.ID)
                        WHERE a.ID_PKM = "'.$id_pkm.'"';

                $data=$this->db->query($sql, array())->result_array();        
                return print_r(json_encode($data));
            }


        }
    }

}    