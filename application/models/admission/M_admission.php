<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admission extends CI_Model {

  public $data = array(
                      'ID_register_document' => null,
                      );

  public function __construct()
    {
        parent::__construct();
    }

    public function count_calon_mahasiswa()
    {
        $sql = "select count(*) as total from (
                select count(*) as total from db_admission.register_formulir as a
                join db_admission.register_document as b ON
                a.ID = b.ID_register_formulir
                where b.Status != 'Done'
                GROUP BY a.ID
                ) as a
                ";          
        $query=$this->db->query($sql, array())->result_array();
        $conVertINT = (int) $query[0]['total'];
        return $conVertINT;
    }

    public function selectDataCalonMahasiswa($limit,$start,$tahun,$nama,$status)
    {
      $arr_temp = array('data' => array());
      if($nama != '%') {
          $nama = '"%'.$nama.'%"'; 
      }
      else
      {
        $nama = '"%"'; 
      }
      if($status == 'Belum Done') {
        $status = 'Status != "Done"';
      }
      else
      {
        $status = 'Status = "Done"';
      }

      $tahun = 'year(RegisterAT) = '.$tahun;
      $sql = "select * from (
              select a.ID,z.name as name_programstudy, 
              (select count(*) as total from db_admission.register_document 
              where ".$status." and ID_register_formulir = a.ID
              GROUP BY ID_register_formulir limit 1) as document_undone,
              (select count(*) as total from db_admission.register_document 
              where Status = 'Progress Checking' and ID_register_formulir = a.ID
              GROUP BY ID_register_formulir limit 1) as document_progress,
              a.ID_program_study,d.Name,a.IdentityCard,e.ctr_name as Nationality,concat(a.PlaceBirth,',',a.DateBirth) as PlaceDateBirth,g.JenisTempatTinggal,
              h.ctr_name as CountryAddress,i.ProvinceName as ProvinceAddress,j.RegionName as RegionAddress,k.DistrictName as DistrictsAddress,
                          a.District as DistrictAddress,a.Address,a.ZipCode,a.PhoneNumber,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,
              n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,IF(a.KPSReceiverStatus = 'YA',CONCAT('No KPS : ',a.NoKPS),'Tidak') as KPSReceiver,
              a.UploadFoto,d.RegisterAT
              from db_admission.register_formulir as a
              JOIN db_admission.register_verified as b 
              ON a.ID_register_verified = b.ID
              JOIN db_admission.register_verification as c
              ON b.RegVerificationID = c.ID
              JOIN db_admission.register as d
              ON c.RegisterID = d.ID
              JOIN db_admission.country as e
              ON a.NationalityID = e.ctr_code
              JOIN db_admission.register_jtinggal_m as g
              ON a.ID_register_jtinggal_m = g.ID
              JOIN db_admission.country as h
              ON a.ID_country_address = h.ctr_code
              JOIN db_admission.province as i
              ON a.ID_province = i.ProvinceID
              JOIN db_admission.region as j
              ON a.ID_region = j.RegionID
              JOIN db_admission.district as k
              ON a.ID_districts = k.DistrictID
              JOIN db_admission.school_type as l
              ON l.sct_code = a.ID_school_type
              JOIN db_admission.register_major_school as m
              ON m.ID = a.ID_register_major_school
              JOIN db_admission.school as n
              ON n.ID = d.SchoolID
              JOIN db_academic.program_study as z
              on a.ID_program_study = z.id
              ) as a
              where document_undone > 0 and Name like ".$nama." and ".$tahun."
              order by document_progress desc
              LIMIT ".$start. ", ".$limit; // query undone

        $query=$this->db->query($sql, array())->result();
          $a = 0;
          foreach ($query as $key) { // foreach 1
            $ID_register_formulir = $key->ID;
            $sql2 = "select a.*, b.DocumentChecklist,b.Required from db_admission.register_document as a
              join db_admission.reg_doc_checklist as b
              on a.ID_reg_doc_checklist = b.ID where a.ID_register_formulir = ? ";
              $query2=$this->db->query($sql2, array($ID_register_formulir))->result();
              $arr_document = array();
              $b = 0;
              foreach ($query2 as $row) { // foreach 2
                  $arr_document[$b] = array(
                                          'ID_register_document' => $row->ID,
                                          'DocumentChecklist' => $row->DocumentChecklist,
                                          'Required' => $row->Required,
                                          'Attachment' => $row->Attachment,
                                          'Status' => $row->Status,
                  );
                  $arr_temp['data'][$a] = array(
                              'Name' => $key->Name,
                              'Email' => $key->Email,
                              'PhoneNumber' => $key->PhoneNumber,
                              'Name_programstudy' => $key->name_programstudy,
                              'Alamat' => $key->Address." Kelurahan ".$key->DistrictAddress." ".$key->DistrictsAddress." ".$key->RegionAddress." ".$key->ProvinceAddress,
                              'SMA' => $key->SchoolName." ".$key->SchoolRegion." ".$key->SchoolProvince,
                              'document' => $arr_document,
                  );
                          
                  $b++;
              }  // exit foreach 2
              $a++;
          } // exit foreach 1
          return $arr_temp;
    }

    public function updateStatusVeriDokumen($data_arr,$Status)
    {
        for ($i=0; $i < count($data_arr); $i++) { 
          $arr = explode(";", $data_arr[$i]);
          $ID = $arr[0];
          //$NamaFile = ($arr[1] == 'nothing' ? $NamaFile="" : $NamaFile=$arr[1]);
          $VerificationBY = $this->session->userdata('NIP');
          $VerificationAT = date("Y-m-d H:i:s");
          // $sql = "update db_admission.register_document set Status = ?,Attachment = ?, VerificationBY = ?, VerificationAT = ? where ID = ?";
          $sql = "update db_admission.register_document set Status = ?, VerificationBY = ?, VerificationAT = ? where ID = ?";
          // $query=$this->db->query($sql, array($Status,$NamaFile,$VerificationBY,$VerificationAT,$ID));
          $query=$this->db->query($sql, array($Status,$VerificationBY,$VerificationAT,$ID));
        } 
        
    }

    public function getKeylinkURLFormulirRegistration($ID_Register = null,$email = null)
    {
      $this->load->model('m_master');
      $ID_register_document = $this->data['ID_register_document'];
      $callback = array();
      switch ($ID_Register) {
        case null:
          $sql = "select a.ID,a.Email from db_admission.register as a
                  join db_admission.register_verification as b 
                  on a.ID = b.RegisterID
                  join db_admission.register_verified as c
                  on b.ID = c.RegVerificationID 
                  join db_admission.register_formulir as d
                  on c.ID = d.ID_register_verified
                  join db_admission.register_document as e
                  on d.ID = e.ID_register_formulir
                  where e.ID = ? LIMIT 1";
          $query=$this->db->query($sql, array($ID_register_document))->result_array();
          $RegisterID = $query[0]['ID'];
          if ($email == null) {
            $query = $this->m_master->caribasedprimary('db_admission.register','ID',$RegisterID);
            $email = $query[0]['Email'];
          }
          $this->getlinkURLFormulirRegistration($RegisterID,$email);
          break;
        
        default:
          $this->load->library('JWT');
          $key = "UAP)(*";
          if ($email == null) {
            $query = $this->m_master->caribasedprimary('db_admission.register','ID',$ID_Register);
            $email = $query[0]['Email'];
          }
          $url = $this->jwt->encode($ID_Register.";".$email,$key);
          $callback = array('url' => $url,'email' => $email);
          $this->data['callback'] = $callback;
          break;
      }

      return $callback;
    }

    private function getlinkURLFormulirRegistration($ID_Register,$email)
    {
      $this->load->library('JWT');
      $key = "UAP)(*";
      if ($email == null) {
        $query = $this->m_master->caribasedprimary('db_admission.register','ID',$ID_Register);
        $email = $query[0]['Email'];
      }
      $url = $this->jwt->encode($ID_Register.";".$email,$key);
      $callback = array('url' => $url,'email' => $email);
      $this->data['callback'] = $callback;
    }

    public function checkAllstatusDoneVeriDoc($ID_register_document)
    {
      $check = TRUE;
      $query = $this->m_master->caribasedprimary('db_admission.register_document','ID',$ID_register_document);
      $ID_register_formulir = $query[0]['ID_register_formulir'];
      $query = $this->m_master->caribasedprimary('db_admission.register_document','ID_register_formulir',$ID_register_formulir);
      for ($i=0; $i < count($query); $i++) { 
        $Status = $query[$i]['Status'];
        if ($Status != 'Done') {
          $check = FALSE;
          break;
        }
      }
      return $check;
    }

    public function totalDataFormulir_offline()
    {
      $sql = "select count(*) as total from (
              select a.Name as NameCandidate,a.Email,z.SchoolName,c.FormulirCode,a.StatusReg
              from db_admission.register as a 
              join db_admission.register_verification as b
              on a.ID = b.RegisterID
              join db_admission.register_verified as c
              on c.RegVerificationID = b.ID
              join db_admission.school as z
              on z.ID = a.SchoolID
              where a.StatusReg = 1
              ) as a right JOIN db_admission.formulir_number_offline_m as b
              on a.FormulirCode = b.FormulirCode
              ";          
      $query=$this->db->query($sql, array())->result_array();
      $conVertINT = (int) $query[0]['total'];
      return $conVertINT;
    }

    public function totalDataFormulir_online()
    {
      $sql = "select count(*) as total from (
              select a.Name as NameCandidate,a.Email,z.SchoolName,c.FormulirCode,a.StatusReg
              from db_admission.register as a 
              join db_admission.register_verification as b
              on a.ID = b.RegisterID
              join db_admission.register_verified as c
              on c.RegVerificationID = b.ID
              join db_admission.school as z
              on z.ID = a.SchoolID
              where a.StatusReg = 0
              ) as a right JOIN db_admission.formulir_number_online_m as b
              on a.FormulirCode = b.FormulirCode
              ";          
      $query=$this->db->query($sql, array())->result_array();
      $conVertINT = (int) $query[0]['total'];
      return $conVertINT;
    }

    public function selectDataDitribusiFormulirOnline($limit, $start,$tahun,$NomorFormulir,$status)
    {
      $arr_temp = array('data' => array());
      if($NomorFormulir != '%') {
          $NomorFormulir = '"%'.$NomorFormulir.'%"'; 
      }
      else
      {
        $NomorFormulir = '"%"'; 
      }
      
      if($status != '%') {
        // $status = '"%'.$status.'%"'; 
        // $status = 'StatusUsed != '.$status;
        $status = ' and b.Status = '.$status;
      }
      else
      {
        $status = ''; 
      }

        $sql = 'select a.NameCandidate,a.Email,a.SchoolName,b.FormulirCode,a.StatusReg,b.Years,b.Status as StatusUsed from (
          select a.Name as NameCandidate,a.Email,z.SchoolName,c.FormulirCode,a.StatusReg
          from db_admission.register as a 
          join db_admission.register_verification as b
          on a.ID = b.RegisterID
          join db_admission.register_verified as c
          on c.RegVerificationID = b.ID
          join db_admission.school as z
          on z.ID = a.SchoolID
          where a.StatusReg = 0
          ) as a right JOIN db_admission.formulir_number_online_m as b
          on a.FormulirCode = b.FormulirCode
          where Years = "'.$tahun.'" and b.FormulirCode like '.$NomorFormulir.$status.' LIMIT '.$start. ', '.$limit;
           $query=$this->db->query($sql, array())->result_array();
           return $query;
    }

    public function selectDataDitribusiFormulirOffline($limit, $start,$tahun,$NomorFormulir,$NamaStaffAdmisi,$status,$statusJual)
    {
      $arr_temp = array('data' => array());
      if($NomorFormulir != '%') {
          $NomorFormulir = '"%'.$NomorFormulir.'%"'; 
      }
      else
      {
        $NomorFormulir = '"%"'; 
      }
      if($NamaStaffAdmisi != '%') {
          $NamaStaffAdmisi = ' and b.Sales like "%'.$NamaStaffAdmisi.'%"'; 
      }
      else
      {
        $NamaStaffAdmisi = ''; 
      }
      if($status != '%') {
        // $status = '"%'.$status.'%"'; 
        // $status = 'StatusUsed != '.$status;
        $status = ' and b.Status = '.$status;
      }
      else
      {
        $status = ''; 
      }

      if($statusJual != '%') {
        // $status = '"%'.$status.'%"'; 
        // $status = 'StatusUsed != '.$status;
        $statusJual = ' and b.StatusJual = '.$statusJual;
      }
      else
      {
        $statusJual = ''; 
      }

        $sql = 'select a.NameCandidate,a.Email,a.SchoolName,b.FormulirCode,a.StatusReg,b.Years,b.Status as StatusUsed, b.StatusJual,
                b.FullName as NamaPembeli,b.PhoneNumber as PhoneNumberPembeli,b.HomeNumber as HomeNumberPembeli,b.Email as EmailPembeli,b.Sales,b.PIC as SalesNIP,
                b.ID as ID_sale_formulir_offline,b.Price_Form
                from (
                select a.Name as NameCandidate,a.Email,z.SchoolName,c.FormulirCode,a.StatusReg
                from db_admission.register as a 
                join db_admission.register_verification as b
                on a.ID = b.RegisterID
                join db_admission.register_verified as c
                on c.RegVerificationID = b.ID
                join db_admission.school as z
                on z.ID = a.SchoolID
                where a.StatusReg = 1
                ) as a right JOIN
                (
                select a.FormulirCode,a.Years,a.Status,a.StatusJual,b.FullName,b.HomeNumber,b.PhoneNumber,
                b.Email,c.Name as Sales,b.PIC,b.ID,b.Price_Form from db_admission.formulir_number_offline_m as a
                left join db_admission.sale_formulir_offline as b
                on a.FormulirCode = b.FormulirCodeOffline
                left join db_employees.employees as c
                on c.NIP = b.PIC
                )
                as b
                on a.FormulirCode = b.FormulirCode
                where Years = "'.$tahun.'" and b.FormulirCode like '.$NomorFormulir.$NamaStaffAdmisi.$status.$statusJual.' LIMIT '.$start. ', '.$limit;
           $query=$this->db->query($sql, array())->result_array();
           return $query;
    }

    public function updateSelloutFormulir($data_arr)
    {
      $SellLinkBy = $this->session->userdata('NIP');

      for ($i=0; $i < count($data_arr); $i++) { 
        if ($data_arr == 'nothing') {
          continue;
        }
        $sql = "update db_admission.formulir_number_offline_m set StatusJual = 1,SellLinkBy = ? where FormulirCode = ?";
        $query=$this->db->query($sql, array($SellLinkBy,$data_arr[$i]));
      }
    }

    public function getJadwalUjian()
    {
      $sql = "select C.Name,a.ID_ujian_perprody,DATE(a.DateTimeTest) as tanggal
              ,CONCAT((EXTRACT(HOUR FROM a.DateTimeTest)),':',(EXTRACT(MINUTE FROM a.DateTimeTest))) as jam,
              a.Lokasi from db_admission.register_jadwal_ujian as a 
              join db_admission.ujian_perprody_m as b
              on a.ID_ujian_perprody = b.ID
              join db_academic.program_study as c
              on c.ID = b.ID_ProgramStudy
              GROUP BY C.Name,DATE(a.DateTimeTest)
              ";          
      $query=$this->db->query($sql, array())->result_array();
      return $query;
    }

    public function save_jadwal_ujian($ID_ujian_perprody,$DateTimeTest,$Lokasi)
    {
      $dataSave = array(
              'ID_ujian_perprody' => $ID_ujian_perprody,
              'DateTimeTest' => $DateTimeTest,
              'Lokasi' => $Lokasi,
      );
      $this->db->insert('db_admission.register_jadwal_ujian', $dataSave);
    }

    public function getID_register_formulir_programStudy_arr($arr)
    {
      $arr_temp = array();
      for ($i=0; $i < count($arr); $i++) { 
        $sql = "select ID from db_admission.register_formulir where ID_program_study = ? and ID in (select ID_register_formulir from db_admission.register_butuh_ujian)";          
        $query=$this->db->query($sql, array($arr[$i]['ID_ProgramStudy']))->result_array();
        for ($j=0; $j < count($query); $j++) { 
          $arr_temp[] = array('ID_register_formulir' => $query[$j]['ID'],'ID_register_jadwal_ujian' => $arr[$i]['ID_register_jadwal_ujian'],'ID_ProgramStudy'=>$arr[$i]['ID_ProgramStudy']);
        }
      }
      
      return $arr_temp;
    }

    public function get_arr_ID_ujian_per_prody($arr_ID_ProgramStudy)
    {
      $arr_temp = array('result' => '','data' => array());
      $arr = array();
      $x = 0;
      for ($i=0; $i <  count($arr_ID_ProgramStudy) ; $i++) { 
        $sql = "select ID,ID_ProgramStudy from db_admission.ujian_perprody_m where ID_ProgramStudy = ? ";          
        $query=$this->db->query($sql, array($arr_ID_ProgramStudy[$i]))->result_array();
        // print_r($query);
          if (count($query) == 0) {
            $arr_temp['result'] = 'Ujian Masuk Per Prody belum disetting, silahkan inputkan dulu pada Master Registration Ujian Per Prody';  
            break;
          }
          else
          {
              for ($j=0; $j < count($query); $j++) { 
                $arr[$x] = array('ID_ujian_perprody' =>$query[$j]['ID'], 'ID_ProgramStudy' => $query[$j]['ID_ProgramStudy'] );
                $x++;
              }
          }
      }
      $arr_temp['data'] = $arr;
      return $arr_temp;
    }

    public function saveDataJadwalUjian_returnArr($arr_ID_ujian_per_prody,$DateTimeTest,$Lokasi)
    {
      $arr_temp = array();
      $x = 0;
       // print_r($arr_ID_ujian_per_prody['data']);
      for ($i=0; $i < count($arr_ID_ujian_per_prody['data']); $i++) {
        try{
          $dataSave = array(
                  'ID_ujian_perprody' => $arr_ID_ujian_per_prody['data'][$i]['ID_ujian_perprody'],
                  'DateTimeTest' => $DateTimeTest,
                  'Lokasi' => $Lokasi,
          );
          $this->db->insert('db_admission.register_jadwal_ujian', $dataSave);
        }
        catch(Exception $e)
        {
          continue;
        } 
        
        $sql = "select ID from db_admission.register_jadwal_ujian where ID_ujian_perprody = ? and DateTimeTest = ? and Lokasi = ?";          
        $query=$this->db->query($sql, array($arr_ID_ujian_per_prody['data'][$i]['ID_ujian_perprody'],$DateTimeTest,$Lokasi))->result_array();
        $arr = array();
        for ($j=0; $j < count($query) ; $j++) { 
          $arr_temp[$x] = array('ID_register_jadwal_ujian' => $query[0]['ID'],'ID_ProgramStudy' => $arr_ID_ujian_per_prody['data'][$i]['ID_ProgramStudy'] );
          $x++;
        }
      }

      return $arr_temp;
    }

    public function saveDataregister_formulir_jadwal_ujian($arr_id)
    {
      //error_reporting(0);
      for ($i=0; $i < count($arr_id); $i++) { 
        try
        {
          // check ID_register_formulir sudah ada pada jadwal ujian atau belum 
          $sql = 'select count(*) as total from db_admission.register_formulir_jadwal_ujian where ID_register_formulir = ?';
          $query=$this->db->query($sql, array($arr_id[$i]['ID_register_formulir']))->result_array();

          $sql2 = 'select count(*) as total from db_admission.ujian_perprody_m where ID_ProgramStudy = ?';
          $query2=$this->db->query($sql2, array($arr_id[$i]['ID_ProgramStudy']))->result_array();
          // print_r($query[0]['total']);
          if ($query[0]['total'] != $query2[0]['total']) {
            $dataSave = array(
                    'ID_register_jadwal_ujian' => $arr_id[$i]['ID_register_jadwal_ujian'],
                    'ID_register_formulir' => $arr_id[$i]['ID_register_formulir'],
            );
            $this->db->insert('db_admission.register_formulir_jadwal_ujian', $dataSave);
          }
          
        }
        catch(Exception $e)
        {
          continue;
        }
      }
      
    }

    public function daftar_jadwal_ujian_load_data_now()
    {
      $sql = 'select C.Name as prody,a.ID_ujian_perprody,DATE(a.DateTimeTest) as tanggal
        ,CONCAT((EXTRACT(HOUR FROM a.DateTimeTest)),":",(EXTRACT(MINUTE FROM a.DateTimeTest))) as jam,
        a.Lokasi,
        h.Name as NameCandidate,h.Email,i.SchoolName,f.FormulirCode,e.ID as ID_register_formulir
        from db_admission.register_jadwal_ujian as a 
        join db_admission.ujian_perprody_m as b
        on a.ID_ujian_perprody = b.ID
        join db_academic.program_study as c
        on c.ID = b.ID_ProgramStudy
        join db_admission.register_formulir_jadwal_ujian as d
        ON a.ID = d.ID_register_jadwal_ujian
        JOIN db_admission.register_formulir as e
        on e.ID = d.ID_register_formulir
        join db_admission.register_verified as f
        on e.ID_register_verified = f.ID
        join db_admission.register_verification as g
        on g.ID = f.RegVerificationID
        join db_admission.register as h
        on h.ID = g.RegisterID
        join db_admission.school as i
        on i.ID = h.SchoolID
        where DATE(a.DateTimeTest) = CURDATE()
        GROUP BY C.Name,DATE(a.DateTimeTest),e.ID';
      $query=$this->db->query($sql, array())->result_array();
      return $query;
    }

    public function daftar_jadwal_ujian_load_data_paging($limit, $start,$Nama,$FormulirCode)
    {
      $where = 'where DATE(a.DateTimeTest) > CURDATE() ';
      if ($Nama != '') {
        $where .= ' and h.Name like "%'.$Nama.'%" or i.SchoolName like "%'.$Nama.'%"';
        if ($FormulirCode != '') {
           $where .= ' and f.FormulirCode like "%'.$FormulirCode.'%"';
         } 
      }
      else
      {
        if ($FormulirCode != '') {
          $where .= ' and f.FormulirCode like "%'.$FormulirCode.'%"';
        }
      }

      $sql = 'select C.Name as prody,a.ID_ujian_perprody,DATE(a.DateTimeTest) as tanggal
        ,CONCAT((EXTRACT(HOUR FROM a.DateTimeTest)),":",(EXTRACT(MINUTE FROM a.DateTimeTest))) as jam,
        a.Lokasi,
        h.Name as NameCandidate,h.Email,i.SchoolName,f.FormulirCode,e.ID as ID_register_formulir
        from db_admission.register_jadwal_ujian as a 
        join db_admission.ujian_perprody_m as b
        on a.ID_ujian_perprody = b.ID
        join db_academic.program_study as c
        on c.ID = b.ID_ProgramStudy
        join db_admission.register_formulir_jadwal_ujian as d
        ON a.ID = d.ID_register_jadwal_ujian
        JOIN db_admission.register_formulir as e
        on e.ID = d.ID_register_formulir
        join db_admission.register_verified as f
        on e.ID_register_verified = f.ID
        join db_admission.register_verification as g
        on g.ID = f.RegVerificationID
        join db_admission.register as h
        on h.ID = g.RegisterID
        join db_admission.school as i
        on i.ID = h.SchoolID
        '.$where.' 
        GROUP BY C.Name,DATE(a.DateTimeTest),e.ID '.' LIMIT '.$start. ', '.$limit;
      $query=$this->db->query($sql, array())->result_array();
      return $query;

    }

    public function daftar_set_nilai_ujian_load_data_paging($limit, $start,$ID_ProgramStudy)
    {
      $sql = 'select C.Name as prody,a.ID_ujian_perprody,DATE(a.DateTimeTest) as tanggal
              ,CONCAT((EXTRACT(HOUR FROM a.DateTimeTest)),":",(EXTRACT(MINUTE FROM a.DateTimeTest))) as jam,
              a.Lokasi,b.NamaUjian,b.Bobot,
              h.Name as NameCandidate,e.ID as ID_register_formulir,b.ID_ProgramStudy
              from db_admission.register_jadwal_ujian as a 
              RIGHT JOIN db_admission.ujian_perprody_m as b
              on a.ID_ujian_perprody = b.ID
              join db_academic.program_study as c
              on c.ID = b.ID_ProgramStudy
              join db_admission.register_formulir_jadwal_ujian as d
              ON a.ID = d.ID_register_jadwal_ujian
              JOIN db_admission.register_formulir as e
              on e.ID = d.ID_register_formulir
              join db_admission.register_verified as f
              on e.ID_register_verified = f.ID
              join db_admission.register_verification as g
              on g.ID = f.RegVerificationID
              join db_admission.register as h
              on h.ID = g.RegisterID
              where b.ID_ProgramStudy = ? and b.Active = 1 and d.ID not in(select ID_register_formulir_jadwal_ujian from db_admission.register_hasil_ujian)
              GROUP by e.ID
              LIMIT '.$start. ', '.$limit;
      $query=$this->db->query($sql, array($ID_ProgramStudy))->result_array();
      return $query;
    }

    public function select_mataUjian($ID_ProgramStudy)
    {
      $sql = 'select * from db_admission.ujian_perprody_m where ID_ProgramStudy = ?';
      $query=$this->db->query($sql, array($ID_ProgramStudy))->result_array();
      return $query;
    }

    public function saveDataNilaiUjian($arr)
    {
      for ($i=0; $i < count($arr['processs1']); $i++) { 
        $sql = 'select c.ID from db_admission.ujian_perprody_m as a
                join db_admission.register_jadwal_ujian as b
                on a.ID = b.ID_ujian_perprody
                join db_admission.register_formulir_jadwal_ujian as c
                on c.ID_register_jadwal_ujian = b.ID
                where a.ID = ? and c.ID_register_formulir = ?';

        // print_r($arr[$i]->id_mataujian);        
        $ID_ujian_perprody = $arr['processs1'][$i]->id_mataujian;
        $ID_register_formulir = $arr['processs1'][$i]->id_formulir;          
        $query=$this->db->query($sql, array($ID_ujian_perprody,$ID_register_formulir))->result_array();
          for ($j=0; $j < count($query); $j++) { 
              $ID_register_formulir_jadwal_ujian = $query[$j]['ID'];
              $dataSave = array(
                      'ID_register_formulir_jadwal_ujian' => $ID_register_formulir_jadwal_ujian,
                      'Value' => $arr['processs1'][$i]->value,
                      'CreateAT' => date('Y-m-d'),
                      'CreateBY' => $this->session->userdata('NIP'),
              );
              $this->db->insert('db_admission.register_hasil_ujian', $dataSave);
          }        
      }

      // print_r($arr['kelulusan']);

      for ($i=0; $i < count($arr['kelulusan']); $i++) { 
        $a = $arr['kelulusan'][$i];
        if ($a != '') {
          $arr_temp = explode(";", $a);
           // print_r($arr_temp);
          $Kelulusan = $arr_temp[0];
          $ID_register_formulir = $arr_temp[1];
          $dataSave = array(
                  'ID_register_formulir' => $ID_register_formulir,
                  'Kelulusan' => $Kelulusan,
          );
          $this->db->insert('db_admission.register_kelulusan_ujian', $dataSave);
        }
      }

    }

    public function showData($tabel)
    {
      $sql = "select * from ".$tabel; 
      $query=$this->db->query($sql, array());
      return $query->result_array();
    }

    public function inserData_formulir_offline_sale_save($input_arr)
    {
      $dataSave = array(
              'FormulirCodeOffline' => $input_arr['selectFormulirCode'],
              'PIC' => $input_arr['PIC'],
              'ID_ProgramStudy' => $input_arr['selectProgramStudy'],
              'FullName' => $input_arr['Name'],
              'Gender' => $input_arr['selectGender'],
              'HomeNumber' => $input_arr['telp_rmh'],
              'PhoneNumber' => $input_arr['hp'],
              'Email' => $input_arr['email'],
              'SchoolID' => $input_arr['autoCompleteSchool'],
              'price_event_ID' => $input_arr['selectEvent'],
              'source_from_event_ID' => $input_arr['selectSourceFrom'],
              'Channel' => $input_arr['tipeChannel'],
              'SchoolIDChanel' => $input_arr['autoCompleteSchoolChanel'],
              'Price_Form' => $input_arr['priceFormulir'],
              'CreateAT' => date('Y-m-d'),
              'CreatedBY' => $this->session->userdata('NIP'),
              // 'Price_Form' => $Kelulusan,
      );
      $this->db->insert('db_admission.sale_formulir_offline', $dataSave);
      $this->updateSellOUTFormulirOffline($input_arr['selectFormulirCode']);
      // print_r($input_arr);
    }

    public function formulir_offline_salect_PIC($input_arr)
    {
      $this->load->model('m_api');
      
      $SchoolID = $input_arr['School'];
      if ($SchoolID != '') {
        // cari sales berdasarkan wilayah, jika tidak ada maka tampilkan sales secara keseluruhan
            $sql = 'select b.NIP,b.Name from db_admission.sales_school_m as a
                    join db_employees.employees as b
                    on a.SalesNIP = b.NIP where a.SchoolID = ? ';
            $query=$this->db->query($sql, array($SchoolID))->result_array();
            if (count($query) == 0) {
              $query = $this->m_api->getEmployeesBy('10','13');
            }
            // print_r($query);
      }
      else
      {
        // tampilkan sales secara keseluruhan
        $query = $this->m_api->getEmployeesBy('10','13');
      }

      return $query;
    }

    public function updateSellOUTFormulirOffline($FormulirCodeOffline)
    {
      $dataSave = array(
              'StatusJual' => 1,
                      );
      $this->db->where('FormulirCode',$FormulirCodeOffline);
      $this->db->update('db_admission.formulir_number_offline_m', $dataSave);
    }

    public function loadData_calon_mahasiswa($limit, $start,$Nama,$selectProgramStudy,$Sekolah)
    {
      $arr_temp = array('data' => array());
      if($Nama != '%') {
          $Nama = '"%'.$Nama.'%"'; 
      }
      else
      {
        $Nama = '"%"'; 
      }
      
      if($selectProgramStudy != '%') {
        $selectProgramStudy = '"%'.$selectProgramStudy.'%"'; 
      }
      else
      {
        $selectProgramStudy = '"%"'; 
      }

      if($Sekolah != '%') {
        $Sekolah = '"%'.$Sekolah.'%"'; 
      }
      else
      {
        $Sekolah = '"%"'; 
      }

        $sql = 'select a.ID as ID_register_formulir,a.ID_program_study,o.Name as NamePrody,d.Name,a.Gender,a.IdentityCard,e.ctr_name as Nationality,f.Religion,concat(a.PlaceBirth,",",a.DateBirth) as PlaceDateBirth,g.JenisTempatTinggal,
            h.ctr_name as CountryAddress,i.ProvinceName as ProvinceAddress,j.RegionName as RegionAddress,k.DistrictName as DistrictsAddress,
            a.District as DistrictAddress,a.Address,a.ZipCode,a.PhoneNumber,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,e.ctr_name as SchoolCountry,
            n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,a.UploadFoto
            from db_admission.register_formulir as a
            JOIN db_admission.register_verified as b 
            ON a.ID_register_verified = b.ID
            JOIN db_admission.register_verification as c
            ON b.RegVerificationID = c.ID
            JOIN db_admission.register as d
            ON c.RegisterID = d.ID
            JOIN db_admission.country as e
            ON a.NationalityID = e.ctr_code
            JOIN db_employees.religion as f
            ON a.ReligionID = f.IDReligion
            JOIN db_admission.register_jtinggal_m as g
            ON a.ID_register_jtinggal_m = g.ID
            JOIN db_admission.country as h
            ON a.ID_country_address = h.ctr_code
            JOIN db_admission.province as i
            ON a.ID_province = i.ProvinceID
            JOIN db_admission.region as j
            ON a.ID_region = j.RegionID
            JOIN db_admission.district as k
            ON a.ID_districts = k.DistrictID
            JOIN db_admission.school_type as l
            ON l.sct_code = a.ID_school_type
            JOIN db_admission.register_major_school as m
            ON m.ID = a.ID_register_major_school
            JOIN db_admission.school as n
            ON n.ID = d.SchoolID
            join db_academic.program_study as o
            on o.ID = a.ID_program_study
            where d.Name like '.$Nama.' and d.SchoolID like '.$Sekolah.' and a.ID_program_study like '.$selectProgramStudy.' and a.ID not in (select ID_register_formulir from db_admission.register_butuh_ujian) and  a.ID not in (select ID_register_formulir from db_admission.register_nilai) LIMIT '.$start. ', '.$limit;
           $query=$this->db->query($sql, array())->result_array();
           return $query;
    }

    public function submit_ikut_ujian($input)
    {
      for ($i=0; $i < count($input); $i++) { 
        $dataSave = array(
                'ID_register_formulir' => $input[$i],
        );
        $this->db->insert('db_admission.register_butuh_ujian', $dataSave);
      }
    }

    public function daftar_set_nilai_rapor_load_data_paging($limit, $start,$ID_ProgramStudy)
    {
      $sql = 'select a.Name as NameCandidate,a.Email, d.ID as ID_register_formulir, e.bobot as Bobot, e.NamaUjian,f.SchoolName,f.CityName
              from db_admission.register as a
              join db_admission.register_verification as b
              on a.ID = b.RegisterID
              join db_admission.register_verified as c
              on b.ID = c.RegVerificationID
              join db_admission.register_formulir as d
              on c.ID = d.ID_register_verified
              join db_admission.ujian_perprody_m as e
              on e.ID_ProgramStudy = d.ID_program_study
              join db_admission.school as f
              on a.SchoolID = f.ID
              where d.ID_program_study = ? and e.Active = 1 and d.ID not in(select ID_register_formulir from db_admission.register_nilai)
              and  d.ID not in (select ID_register_formulir from db_admission.register_butuh_ujian)
              GROUP by d.ID
              LIMIT '.$start. ', '.$limit;
      $query=$this->db->query($sql, array($ID_ProgramStudy))->result_array();
      return $query;
    }

    public function saveDataNilaRapor($arr)
    {
      for ($i=0; $i < count($arr['processs1']); $i++) { 
        $ID_ujian_perprody = $arr['processs1'][$i]->id_mataujian;
        $ID_register_formulir = $arr['processs1'][$i]->id_formulir;
        
        $bool = true;
        for ($j=0; $j < count($arr['rangking']); $j++) { 
            $id_doc = $arr['rangking'][$j]->id_doc;
            if ($ID_register_formulir == $arr['rangking'][$j]->id_formulir) {
              if ($id_doc == '' || $id_doc == null ) {
                $bool = false;
                break;
              }
            }
            
        } 
        
        if ($bool) {
            $dataSave = array(
                    'ID_ujian_perprody' => $ID_ujian_perprody,
                    'ID_register_formulir' => $ID_register_formulir,
                    'Value' => $arr['processs1'][$i]->value,
                    'CreateAT' => date('Y-m-d'),
                    'CreateBY' => $this->session->userdata('NIP'),
            );
            $this->db->insert('db_admission.register_nilai', $dataSave);
        }       
         
      }

    }

    public function saveDataRangkingRapor($arr)
    {
      for ($j=0; $j < count($arr['rangking']); $j++) { 
          $ID_register_formulir = $arr['rangking'][$j]->id_formulir;
          $Rangking = $arr['rangking'][$j]->rangking;
          $id_doc = $arr['rangking'][$j]->id_doc;
          if ($id_doc != '' || $id_doc != null) {
            $dataSave = array(
                    'ID_register_formulir' => $ID_register_formulir,
                    'Rangking' => $Rangking,
                    'FileRapor' => $id_doc,
            );
            $this->db->insert('db_admission.register_rangking', $dataSave);
          }
      } 
    }

    public function loadData_calon_mahasiswa_created($limit, $start,$Nama,$selectProgramStudy,$Sekolah)
    {
      $arr_temp = array('data' => array());
      if($Nama != '%') {
          $Nama = '"%'.$Nama.'%"'; 
      }
      else
      {
        $Nama = '"%"'; 
      }
      
      if($selectProgramStudy != '%') {
        $selectProgramStudy = '"%'.$selectProgramStudy.'%"'; 
      }
      else
      {
        $selectProgramStudy = '"%"'; 
      }

      if($Sekolah != '%') {
        $Sekolah = '"%'.$Sekolah.'%"'; 
      }
      else
      {
        $Sekolah = '"%"'; 
      }

        $sql = 'select a.ID as ID_register_formulir,a.ID_program_study,o.Name as NamePrody,d.Name,a.Gender,a.IdentityCard,e.ctr_name as Nationality,f.Religion,concat(a.PlaceBirth,",",a.DateBirth) as PlaceDateBirth,g.JenisTempatTinggal,
            h.ctr_name as CountryAddress,i.ProvinceName as ProvinceAddress,j.RegionName as RegionAddress,k.DistrictName as DistrictsAddress,
            a.District as DistrictAddress,a.Address,a.ZipCode,a.PhoneNumber,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,e.ctr_name as SchoolCountry,
            n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,a.UploadFoto
            from db_admission.register_formulir as a
            JOIN db_admission.register_verified as b 
            ON a.ID_register_verified = b.ID
            JOIN db_admission.register_verification as c
            ON b.RegVerificationID = c.ID
            JOIN db_admission.register as d
            ON c.RegisterID = d.ID
            JOIN db_admission.country as e
            ON a.NationalityID = e.ctr_code
            JOIN db_employees.religion as f
            ON a.ReligionID = f.IDReligion
            JOIN db_admission.register_jtinggal_m as g
            ON a.ID_register_jtinggal_m = g.ID
            JOIN db_admission.country as h
            ON a.ID_country_address = h.ctr_code
            JOIN db_admission.province as i
            ON a.ID_province = i.ProvinceID
            JOIN db_admission.region as j
            ON a.ID_region = j.RegionID
            JOIN db_admission.district as k
            ON a.ID_districts = k.DistrictID
            JOIN db_admission.school_type as l
            ON l.sct_code = a.ID_school_type
            JOIN db_admission.register_major_school as m
            ON m.ID = a.ID_register_major_school
            JOIN db_admission.school as n
            ON n.ID = d.SchoolID
            join db_academic.program_study as o
            on o.ID = a.ID_program_study
            where d.Name like '.$Nama.' and d.SchoolID like '.$Sekolah.' and a.ID_program_study like '.$selectProgramStudy.' and a.ID in (select ID_register_formulir from db_admission.register_nilai where Status != "Approved") LIMIT '.$start. ', '.$limit;
           $query=$this->db->query($sql, array())->result_array();
           return $query;
    }

    public function getValuePerid_ujian_register($ID_ujian_perprody,$ID_register_formulir)
    {
      $sql = "select * from db_admission.register_nilai where ID_ujian_perprody = ? and ID_register_formulir = ?";
      $query=$this->db->query($sql, array($ID_ujian_perprody,$ID_register_formulir))->result_array();
      return $query;
    }

     public function submit_cancel_nilai_rapor($input)
     {
      for ($i=0; $i < count($input); $i++) {
        $sql = "delete from db_admission.register_nilai where ID_register_formulir = ".$input[$i];
        $query=$this->db->query($sql, array()); 
      }
     }

     public function getDataDokumentRegister($ID_register_formulir)
     {
         $sql = "select a.ID,a.ID_register_formulir,a.ID_reg_doc_checklist,a.Status,a.Attachment,b.DocumentChecklist,
                 b.Required from db_admission.register_document as a
                 join db_admission.reg_doc_checklist as b
                 on b.ID = a.ID_reg_doc_checklist
                 where a.Status = 'Done' and a.ID_register_formulir = ?
             ";
         $query=$this->db->query($sql, array($ID_register_formulir))->result_array();
         return $query;
     }

     public function getRangking($ID_register_formulir)
     {
      $sql= "select a.*,b.Attachment from db_admission.register_rangking as a join db_admission.register_document as b
             on a.FileRapor = b.ID where a.ID_register_formulir = ?
            ";
      $query=$this->db->query($sql, array($ID_register_formulir))->result_array();
      return $query;      
     }

     public function submit_cancel_nilai_rapor_rangking($input)
     {
      for ($i=0; $i < count($input); $i++) {
        $sql = "delete from db_admission.register_rangking where ID_register_formulir = ".$input[$i];
        $query=$this->db->query($sql, array()); 
      }
     }

     /*public function getDataCalonMhsTuitionFee($limit, $start)
     {
      $arr_temp = array();
      $sql= 'select a.ID as ID_register_formulir,a.ID_program_study,o.Name as NamePrody,d.Name,a.Gender,a.IdentityCard,e.ctr_name as Nationality,
              f.Religion,concat(a.PlaceBirth,",",a.DateBirth) as PlaceDateBirth,g.JenisTempatTinggal,
              h.ctr_name as CountryAddress,i.ProvinceName as ProvinceAddress,j.RegionName as RegionAddress,k.DistrictName as DistrictsAddress,
              a.District as DistrictAddress,a.Address,a.ZipCode,a.PhoneNumber,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,e.ctr_name as SchoolCountry,
              n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,a.UploadFoto,
              if((select count(*) as total from db_admission.register_nilai where Status = "Verified" and ID_register_formulir = a.ID limit 1) > 0,"Rapor","Ujian")
              as status1
              from db_admission.register_formulir as a
              JOIN db_admission.register_verified as b 
              ON a.ID_register_verified = b.ID
              JOIN db_admission.register_verification as c
              ON b.RegVerificationID = c.ID
              JOIN db_admission.register as d
              ON c.RegisterID = d.ID
              JOIN db_admission.country as e
              ON a.NationalityID = e.ctr_code
              JOIN db_employees.religion as f
              ON a.ReligionID = f.IDReligion
              JOIN db_admission.register_jtinggal_m as g
              ON a.ID_register_jtinggal_m = g.ID
              JOIN db_admission.country as h
              ON a.ID_country_address = h.ctr_code
              JOIN db_admission.province as i
              ON a.ID_province = i.ProvinceID
              JOIN db_admission.region as j
              ON a.ID_region = j.RegionID
              JOIN db_admission.district as k
              ON a.ID_districts = k.DistrictID
              JOIN db_admission.school_type as l
              ON l.sct_code = a.ID_school_type
              JOIN db_admission.register_major_school as m
              ON m.ID = a.ID_register_major_school
              JOIN db_admission.school as n
              ON n.ID = d.SchoolID
              join db_academic.program_study as o
              on o.ID = a.ID_program_study
              where ( a.ID in (select ID_register_formulir from db_admission.register_nilai where Status = "Verified") 
              or a.ID in (select ID_register_formulir from db_admission.register_kelulusan_ujian where Kelulusan = "Lulus") ) and a.ID not in (select ID_register_formulir from db_finance.payment_register) LIMIT '.$start. ', '.$limit;
      $query=$this->db->query($sql, array())->result_array();
      //print_r($query);
      //die();

      $this->load->model('master/m_master');
      $jpa = $this->m_master->showData_array('db_admission.register_dsn_jpa');
      for ($i=0; $i < count($query); $i++) { 

        // get SKS
        $ID_program_study = $query[$i]['ID_program_study'];
        $ccc = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ID_program_study);
        $Credit = $ccc[0]['DefaultCredit'];

        $DiskonSPP = 0;
        // get Price
            $getPaymentType_Cost = $this->getPaymentType_Cost($query[$i]['ID_program_study']);
            $arr_temp2 = array();
            for ($k=0; $k < count($getPaymentType_Cost); $k++) {
              if ($getPaymentType_Cost[$k]['Abbreviation'] == 'Credit') {
                 $arr_temp2 = $arr_temp2 + array($getPaymentType_Cost[$k]['Abbreviation'] => (int)$getPaymentType_Cost[$k]['Cost'] * (int) $Credit.'.00');
               }
               else
               {
                $arr_temp2 = $arr_temp2 + array($getPaymentType_Cost[$k]['Abbreviation'] => $getPaymentType_Cost[$k]['Cost']);
               } 
              
            }
        if ($query[$i]['status1'] == 'Rapor') {
          // check rangking
            $getRangking = $this->getRangking($query[$i]['ID_register_formulir']);
            $getRangking = $getRangking[0]['Rangking'];
          // get Discount
            for ($j=0; $j < count($jpa); $j++) { 
              if ($getRangking >= $jpa[$j]['StartRange'] && $getRangking <= $jpa[$j]['EndRange'] ) {
                $DiskonSPP = $jpa[$j]['DiskonSPP'];
                break;
              }
            }
            
            $arr_temp[$i] = array(
              'ID_register_formulir' => $query[$i]['ID_register_formulir'],
              'Name' => $query[$i]['Name'],
              'NamePrody' => $query[$i]['NamePrody'],
              'SchoolName' => $query[$i]['SchoolName'],
              'Status1' => $query[$i]['status1'],
              'DiskonSPP' => $DiskonSPP,
              'RangkingRapor' => $getRangking,
            );
        }
        else
        {
            $arr_temp[$i] = array(
              'ID_register_formulir' => $query[$i]['ID_register_formulir'],
              'Name' => $query[$i]['Name'],
              'NamePrody' => $query[$i]['NamePrody'],
              'SchoolName' => $query[$i]['SchoolName'],
              'Status1' => $query[$i]['status1'],
              'DiskonSPP' => $DiskonSPP,
              'RangkingRapor' => 0,
            );
        }

        $arr_temp[$i] = $arr_temp[$i] + $arr_temp2;
      }
      return $arr_temp;

     }*/

     public function getDataCalonMhsTuitionFee($limit, $start)
     {
      $arr_temp = array();
      $sql= 'select a.ID as ID_register_formulir,a.ID_program_study,o.Name as NamePrody,d.Name,a.Gender,a.IdentityCard,e.ctr_name as Nationality,
              f.Religion,concat(a.PlaceBirth,",",a.DateBirth) as PlaceDateBirth,g.JenisTempatTinggal,
              h.ctr_name as CountryAddress,i.ProvinceName as ProvinceAddress,j.RegionName as RegionAddress,k.DistrictName as DistrictsAddress,
              a.District as DistrictAddress,a.Address,a.ZipCode,a.PhoneNumber,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,e.ctr_name as SchoolCountry,
              n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,a.UploadFoto,
              if((select count(*) as total from db_admission.register_nilai where Status = "Verified" and ID_register_formulir = a.ID limit 1) > 0,"Rapor","Ujian")
              as status1,b.FormulirCode
              from db_admission.register_formulir as a
              JOIN db_admission.register_verified as b 
              ON a.ID_register_verified = b.ID
              JOIN db_admission.register_verification as c
              ON b.RegVerificationID = c.ID
              JOIN db_admission.register as d
              ON c.RegisterID = d.ID
              JOIN db_admission.country as e
              ON a.NationalityID = e.ctr_code
              JOIN db_employees.religion as f
              ON a.ReligionID = f.IDReligion
              JOIN db_admission.register_jtinggal_m as g
              ON a.ID_register_jtinggal_m = g.ID
              JOIN db_admission.country as h
              ON a.ID_country_address = h.ctr_code
              JOIN db_admission.province as i
              ON a.ID_province = i.ProvinceID
              JOIN db_admission.region as j
              ON a.ID_region = j.RegionID
              JOIN db_admission.district as k
              ON a.ID_districts = k.DistrictID
              JOIN db_admission.school_type as l
              ON l.sct_code = a.ID_school_type
              JOIN db_admission.register_major_school as m
              ON m.ID = a.ID_register_major_school
              JOIN db_admission.school as n
              ON n.ID = d.SchoolID
              join db_academic.program_study as o
              on o.ID = a.ID_program_study
              where ( a.ID in (select ID_register_formulir from db_admission.register_nilai where Status = "Verified") 
              or a.ID in (select ID_register_formulir from db_admission.register_kelulusan_ujian where Kelulusan = "Lulus") ) and a.ID not in (select ID_register_formulir from db_finance.register_admisi) LIMIT '.$start. ', '.$limit;
      $query=$this->db->query($sql, array())->result_array();

      $this->load->model('master/m_master');
      $jpa = $this->m_master->showData_array('db_admission.register_dsn_jpa');
      $getDiscount = $this->m_master->showData_array('db_finance.discount');
      $getBeasiswa = $this->m_master->showData_array('db_admission.register_dsn_type_m');
      $getMaxCicilan = $this->m_master->showData_array('db_admission.cfg_cicilan');
      for ($i=0; $i < count($query); $i++) { 

        // get SKS
        $ID_program_study = $query[$i]['ID_program_study'];
        $ccc = $this->m_master->caribasedprimary('db_academic.program_study','ID',$ID_program_study);
        $Credit = $ccc[0]['DefaultCredit'];

        $DiskonSPP = 0;
        // get Price
            $getPaymentType_Cost = $this->getPaymentType_Cost($query[$i]['ID_program_study']);
            $arr_temp2 = array();
            for ($k=0; $k < count($getPaymentType_Cost); $k++) {
              if ($getPaymentType_Cost[$k]['Abbreviation'] == 'Credit') {
                 $arr_temp2 = $arr_temp2 + array($getPaymentType_Cost[$k]['Abbreviation'] => (int)$getPaymentType_Cost[$k]['Cost'] * (int) $Credit.'.00');
               }
               else
               {
                $arr_temp2 = $arr_temp2 + array($getPaymentType_Cost[$k]['Abbreviation'] => $getPaymentType_Cost[$k]['Cost']);
               } 
              
            }
          $Attachment = '';
          // get All Files Uploaded
             $Document = $this->getDataDokumentRegister($query[$i]['ID_register_formulir']);  
        if ($query[$i]['status1'] == 'Rapor') {
          // check rangking
            $getRangking = $this->getRangking($query[$i]['ID_register_formulir']);
            $Attachment = $getRangking[0]['Attachment'];
            $getRangking = $getRangking[0]['Rangking'];

          // get Discount
            for ($j=0; $j < count($jpa); $j++) { 
              if ($getRangking >= $jpa[$j]['StartRange'] && $getRangking <= $jpa[$j]['EndRange'] ) {
                $DiskonSPP = $jpa[$j]['DiskonSPP'];
                break;
              }
            }
            
            $arr_temp[$i] = array(
              'ID_register_formulir' => $query[$i]['ID_register_formulir'],
              'Name' => $query[$i]['Name'],
              'NamePrody' => $query[$i]['NamePrody'],
              'SchoolName' => $query[$i]['SchoolName'],
              'Status1' => $query[$i]['status1'],
              'DiskonSPP' => $DiskonSPP.'.0',
              'RangkingRapor' => $getRangking,
              'getDiscount' =>$getDiscount,
              'FormulirCode' => $query[$i]['FormulirCode'],
              'Document' => $Document,
              'Attachment' => $Attachment,
              'getBeasiswa' => $getBeasiswa,
              'Email' => $query[$i]['Email'],
              'getMaxCicilan' => $getMaxCicilan
            );
        }
        else
        {
            $arr_temp[$i] = array(
              'ID_register_formulir' => $query[$i]['ID_register_formulir'],
              'Name' => $query[$i]['Name'],
              'NamePrody' => $query[$i]['NamePrody'],
              'SchoolName' => $query[$i]['SchoolName'],
              'Status1' => $query[$i]['status1'],
              'DiskonSPP' => $DiskonSPP.'.0',
              'RangkingRapor' => 0,
              'getDiscount' =>$getDiscount,
              'FormulirCode' => $query[$i]['FormulirCode'],
              'Document' => $Document,
              'Attachment' => $Attachment,
              'getBeasiswa' => $getBeasiswa,
              'Email' => $query[$i]['Email'],
              'getMaxCicilan' => $getMaxCicilan,
            );
        }

        $arr_temp[$i] = $arr_temp[$i] + $arr_temp2;
      }
      return $arr_temp;

     }

     public function getPaymentType_Cost($ID_program_study)
     {
      $year = date('Y');
      $sql = 'select a.PTID,a.ProdiID,a.ClassOf,a.Cost,b.Description,b.Abbreviation from db_finance.tuition_fee as a join db_finance.payment_type as b
              on a.PTID = b.ID where a.ProdiID = "'.$ID_program_study.'" and a.ClassOf = '.$year.'
              order by b.ID asc';
      $query=$this->db->query($sql, array())->result_array();
      return $query;       
     }

     public function getPaymentType_Cost_created($ID_register_formulir)
     {
      $sql = 'select a.*,b.Description,b.Abbreviation,c.Pay_tuition_fee,C.Discount from db_finance.payment_admisi as c join db_finance.payment_type as b on c.PTID = b.ID join db_finance.register_admisi as a on c.ID_register_formulir = a.ID_register_formulir where a.ID_register_formulir = ?';
      $query=$this->db->query($sql, array($ID_register_formulir))->result_array();
      return $query;
     }

     public function set_tuition_fee_save($input)
     {
      for ($i=0; $i < count($input); $i++) { 
        // echo $input[$i]->PTID;
        $PTID = $input[$i]->PTID;
        $ID_register_formulir = $input[$i]->ID_register_formulir;
        $Discount = $input[$i]->Discount;
        $Pay_tuition_fee = $input[$i]->Pay_tuition_fee;
        $this->load->model('master/m_master');
        $Pay_tuition_fee = $this->m_master->ClearPricetoDB($Pay_tuition_fee);
        $dataSave = array(
                'PTID' => $PTID,
                'ID_register_formulir' => $ID_register_formulir,
                'Discount' => $Discount,
                'Pay_tuition_fee' => $Pay_tuition_fee,
                'CreateAT' => date('Y-m-d'),
                'CreateBY' => $this->session->userdata('NIP'),
                'Status' => 'Created',
        );
        $this->db->insert('db_finance.payment_register', $dataSave);
      }
     }

     public function count_getDataCalonMhsTuitionFee()
     {
      $sql= 'select count(*) as total
              from db_admission.register_formulir as a
              JOIN db_admission.register_verified as b 
              ON a.ID_register_verified = b.ID
              JOIN db_admission.register_verification as c
              ON b.RegVerificationID = c.ID
              JOIN db_admission.register as d
              ON c.RegisterID = d.ID
              JOIN db_admission.country as e
              ON a.NationalityID = e.ctr_code
              JOIN db_employees.religion as f
              ON a.ReligionID = f.IDReligion
              JOIN db_admission.register_jtinggal_m as g
              ON a.ID_register_jtinggal_m = g.ID
              JOIN db_admission.country as h
              ON a.ID_country_address = h.ctr_code
              JOIN db_admission.province as i
              ON a.ID_province = i.ProvinceID
              JOIN db_admission.region as j
              ON a.ID_region = j.RegionID
              JOIN db_admission.district as k
              ON a.ID_districts = k.DistrictID
              JOIN db_admission.school_type as l
              ON l.sct_code = a.ID_school_type
              JOIN db_admission.register_major_school as m
              ON m.ID = a.ID_register_major_school
              JOIN db_admission.school as n
              ON n.ID = d.SchoolID
              join db_academic.program_study as o
              on o.ID = a.ID_program_study
              where ( a.ID in (select ID_register_formulir from db_admission.register_nilai where Status = "Verified") 
              or a.ID in (select ID_register_formulir from db_admission.register_kelulusan_ujian where Kelulusan = "Lulus") ) and a.ID not in (select ID_register_formulir from db_finance.register_admisi)';
      $query=$this->db->query($sql, array())->result_array();
      return $query[0]['total'];
     }

     public function count_getDataCalonMhsTuitionFee_delete()
     {
      $sql= 'select count(*) as total
              from db_admission.register_formulir as a
              JOIN db_admission.register_verified as b 
              ON a.ID_register_verified = b.ID
              JOIN db_admission.register_verification as c
              ON b.RegVerificationID = c.ID
              JOIN db_admission.register as d
              ON c.RegisterID = d.ID
              JOIN db_admission.country as e
              ON a.NationalityID = e.ctr_code
              JOIN db_employees.religion as f
              ON a.ReligionID = f.IDReligion
              JOIN db_admission.school_type as l
              ON l.sct_code = a.ID_school_type
              JOIN db_admission.register_major_school as m
              ON m.ID = a.ID_register_major_school
              JOIN db_admission.school as n
              ON n.ID = d.SchoolID
              join db_academic.program_study as o
              on o.ID = a.ID_program_study
              join db_finance.register_admisi as p
              on a.ID = p.ID_register_formulir
              where p.Status = "Created" group by a.ID';
      $query=$this->db->query($sql, array())->result_array();
      return $query[0]['total'];
     }

     public function getDataCalonMhsTuitionFee_delete($limit, $start)
     {
      $arr_temp = array();
      $sql= 'select a.ID as ID_register_formulir,a.ID_program_study,o.Name as NamePrody,d.Name,a.Gender,a.IdentityCard,e.ctr_name as Nationality,
              f.Religion,concat(a.PlaceBirth,",",a.DateBirth) as PlaceDateBirth,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,e.ctr_name as SchoolCountry,
              n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,a.UploadFoto,
              if((select count(*) as total from db_admission.register_nilai where Status = "Verified" and ID_register_formulir = a.ID limit 1) > 0,"Rapor","Ujian")
              as status1,p.CreateAT,p.CreateBY,b.FormulirCode,p.TypeBeasiswa,p.FileBeasiswa
              from db_admission.register_formulir as a
              JOIN db_admission.register_verified as b 
              ON a.ID_register_verified = b.ID
              JOIN db_admission.register_verification as c
              ON b.RegVerificationID = c.ID
              JOIN db_admission.register as d
              ON c.RegisterID = d.ID
              JOIN db_admission.country as e
              ON a.NationalityID = e.ctr_code
              JOIN db_employees.religion as f
              ON a.ReligionID = f.IDReligion
              JOIN db_admission.school_type as l
              ON l.sct_code = a.ID_school_type
              JOIN db_admission.register_major_school as m
              ON m.ID = a.ID_register_major_school
              JOIN db_admission.school as n
              ON n.ID = d.SchoolID
              join db_academic.program_study as o
              on o.ID = a.ID_program_study
              join db_finance.register_admisi as p
              on a.ID = p.ID_register_formulir
              where p.Status = "Created" group by a.ID LIMIT '.$start. ', '.$limit;
      $query=$this->db->query($sql, array())->result_array();
      $this->load->model('master/m_master');
      $jpa = $this->m_master->showData_array('db_admission.register_dsn_jpa');
      for ($i=0; $i < count($query); $i++) { 
        $DiskonSPP = 0;
        // get Price
            $getPaymentType_Cost = $this->getPaymentType_Cost_created($query[$i]['ID_register_formulir']);
            $arr_temp2 = array();
            for ($k=0; $k < count($getPaymentType_Cost); $k++) { 
              // $arr_temp2 = $arr_temp2 + array($getPaymentType_Cost[$k]['Abbreviation'] => $getPaymentType_Cost[$k]['Cost']);
              $arr_temp2 = $arr_temp2 + array(
                $getPaymentType_Cost[$k]['Abbreviation'] => 'Rp. '.number_format($getPaymentType_Cost[$k]['Pay_tuition_fee'],2,',','.'),
                'Discount-'.$getPaymentType_Cost[$k]['Abbreviation'] => $getPaymentType_Cost[$k]['Discount']
              );
            }

        // get file dan type beasiswa
           $getBeasiswa = $this->m_master->caribasedprimary('db_admission.register_dsn_type_m','ID',$query[$i]['TypeBeasiswa']);
           if (count($getBeasiswa) > 0) {
             $getBeasiswa = $getBeasiswa[0]['DiscountType']; 
           }
           else
           {
            $getBeasiswa = '-'; 
           }

        // get File
          $getFile = $this->m_master->caribasedprimary('db_admission.register_document','ID',$query[$i]['FileBeasiswa']);
          if (count($getFile) > 0) {
            $getFile = $getFile[0]['Attachment']; 
          }
          else
          {
           $getFile = '-'; 
          }
            
        if ($query[$i]['status1'] == 'Rapor') {
          // check rangking
            $getRangking = $this->getRangking($query[$i]['ID_register_formulir']);
            $getRangking = $getRangking[0]['Rangking'];
            
            $arr_temp[$i] = array(
              'ID_register_formulir' => $query[$i]['ID_register_formulir'],
              'Name' => $query[$i]['Name'],
              'NamePrody' => $query[$i]['NamePrody'],
              'SchoolName' => $query[$i]['SchoolName'],
              'Status1' => $query[$i]['status1'],
              // 'DiskonSPP' => $DiskonSPP,
              'RangkingRapor' => $getRangking,
              'FormulirCode' => $query[$i]['FormulirCode'],
              'getBeasiswa' => $getBeasiswa,
              'getFile' => $getFile,
              'Email' => $query[$i]['Email']
            );
        }
        else
        {
            $arr_temp[$i] = array(
              'ID_register_formulir' => $query[$i]['ID_register_formulir'],
              'Name' => $query[$i]['Name'],
              'NamePrody' => $query[$i]['NamePrody'],
              'SchoolName' => $query[$i]['SchoolName'],
              'Status1' => $query[$i]['status1'],
              // 'DiskonSPP' => $DiskonSPP,
              'RangkingRapor' => 0,
              'FormulirCode' => $query[$i]['FormulirCode'],
              'getBeasiswa' => $getBeasiswa,
              'getFile' => $getFile,
              'Email' => $query[$i]['Email'],
            );
        }

        $arr_temp[$i] = $arr_temp[$i] + $arr_temp2;
      }
      return $arr_temp;

     }

     public function set_tuition_fee_delete_data($input)
     {
      for ($i=0; $i < count($input); $i++) { 
        $sql = "delete from db_finance.register_admisi where ID_register_formulir = ".$input[$i];
        $query=$this->db->query($sql, array()); 

        $sql = "delete from db_finance.payment_admisi where ID_register_formulir = ".$input[$i];
        $query=$this->db->query($sql, array()); 

        $sql = "delete from db_finance.payment_pre where ID_register_formulir = ".$input[$i];
        $query=$this->db->query($sql, array()); 
      }
     }

    public function getDataCalonMhsTuitionFee_approved($limit, $start)
    {
     $arr_temp = array();
     $sql= 'select a.ID as ID_register_formulir,a.ID_program_study,o.Name as NamePrody,d.Name,a.Gender,a.IdentityCard,e.ctr_name as Nationality,
             f.Religion,concat(a.PlaceBirth,",",a.DateBirth) as PlaceDateBirth,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,e.ctr_name as SchoolCountry,
             n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,a.UploadFoto,
             if((select count(*) as total from db_admission.register_nilai where Status = "Approved" and ID_register_formulir = a.ID limit 1) > 0,"Rapor","Ujian")
             as status1,p.CreateAT,p.CreateBY
             from db_admission.register_formulir as a
             JOIN db_admission.register_verified as b 
             ON a.ID_register_verified = b.ID
             JOIN db_admission.register_verification as c
             ON b.RegVerificationID = c.ID
             JOIN db_admission.register as d
             ON c.RegisterID = d.ID
             JOIN db_admission.country as e
             ON a.NationalityID = e.ctr_code
             JOIN db_employees.religion as f
             ON a.ReligionID = f.IDReligion
             JOIN db_admission.school_type as l
             ON l.sct_code = a.ID_school_type
             JOIN db_admission.register_major_school as m
             ON m.ID = a.ID_register_major_school
             JOIN db_admission.school as n
             ON n.ID = d.SchoolID
             join db_academic.program_study as o
             on o.ID = a.ID_program_study
             join db_finance.register_admisi as p
             on a.ID = p.ID_register_formulir
             where p.Status = "Approved" group by a.ID LIMIT '.$start. ', '.$limit;
     $query=$this->db->query($sql, array())->result_array();
     $this->load->model('master/m_master');
     $jpa = $this->m_master->showData_array('db_admission.register_dsn_jpa');
     for ($i=0; $i < count($query); $i++) { 
       $DiskonSPP = 0;
       // get Price
           $getPaymentType_Cost = $this->getPaymentType_Cost_created($query[$i]['ID_register_formulir']);
           $arr_temp2 = array();
           for ($k=0; $k < count($getPaymentType_Cost); $k++) { 
             // $arr_temp2 = $arr_temp2 + array($getPaymentType_Cost[$k]['Abbreviation'] => $getPaymentType_Cost[$k]['Cost']);
             $arr_temp2 = $arr_temp2 + array(
               $getPaymentType_Cost[$k]['Abbreviation'] => number_format($getPaymentType_Cost[$k]['Pay_tuition_fee'],2,',','.'),
               'Discount-'.$getPaymentType_Cost[$k]['Abbreviation'] => $getPaymentType_Cost[$k]['Discount']
             );
           }
       if ($query[$i]['status1'] == 'Rapor') {
         // check rangking
           $getRangking = $this->getRangking($query[$i]['ID_register_formulir']);
           $getRangking = $getRangking[0]['Rangking'];
           
           $arr_temp[$i] = array(
             'ID_register_formulir' => $query[$i]['ID_register_formulir'],
             'Name' => $query[$i]['Name'],
             'NamePrody' => $query[$i]['NamePrody'],
             'SchoolName' => $query[$i]['SchoolName'],
             'Status1' => $query[$i]['status1'],
             // 'DiskonSPP' => $DiskonSPP,
             'RangkingRapor' => $getRangking,
           );
       }
       else
       {
           $arr_temp[$i] = array(
             'ID_register_formulir' => $query[$i]['ID_register_formulir'],
             'Name' => $query[$i]['Name'],
             'NamePrody' => $query[$i]['NamePrody'],
             'SchoolName' => $query[$i]['SchoolName'],
             'Status1' => $query[$i]['status1'],
             // 'DiskonSPP' => $DiskonSPP,
             'RangkingRapor' => 0,
           );
       }

       $arr_temp[$i] = $arr_temp[$i] + $arr_temp2;
     }
     return $arr_temp;

    }

    public function getDataCalonMhsCicilan($limit, $start)
    {
     $arr_temp = array();
     $sql= 'select a.ID as ID_register_formulir,a.ID_program_study,o.Name as NamePrody,d.Name,a.Gender,a.IdentityCard,e.ctr_name as Nationality,
             f.Religion,concat(a.PlaceBirth,",",a.DateBirth) as PlaceDateBirth,g.JenisTempatTinggal,
             h.ctr_name as CountryAddress,i.ProvinceName as ProvinceAddress,j.RegionName as RegionAddress,k.DistrictName as DistrictsAddress,
             a.District as DistrictAddress,a.Address,a.ZipCode,a.PhoneNumber,d.Email,n.SchoolName,l.sct_name_id as SchoolType,m.SchoolMajor,e.ctr_name as SchoolCountry,
             n.ProvinceName as SchoolProvince,n.CityName as SchoolRegion,n.SchoolAddress,a.YearGraduate,a.UploadFoto,
             if((select count(*) as total from db_admission.register_nilai where Status = "Approved" and ID_register_formulir = a.ID limit 1) > 0,"Rapor","Ujian")
             as status1,d.VA_number
             from db_admission.register_formulir as a
             JOIN db_admission.register_verified as b 
             ON a.ID_register_verified = b.ID
             JOIN db_admission.register_verification as c
             ON b.RegVerificationID = c.ID
             JOIN db_admission.register as d
             ON c.RegisterID = d.ID
             JOIN db_admission.country as e
             ON a.NationalityID = e.ctr_code
             JOIN db_employees.religion as f
             ON a.ReligionID = f.IDReligion
             JOIN db_admission.register_jtinggal_m as g
             ON a.ID_register_jtinggal_m = g.ID
             JOIN db_admission.country as h
             ON a.ID_country_address = h.ctr_code
             JOIN db_admission.province as i
             ON a.ID_province = i.ProvinceID
             JOIN db_admission.region as j
             ON a.ID_region = j.RegionID
             JOIN db_admission.district as k
             ON a.ID_districts = k.DistrictID
             JOIN db_admission.school_type as l
             ON l.sct_code = a.ID_school_type
             JOIN db_admission.register_major_school as m
             ON m.ID = a.ID_register_major_school
             JOIN db_admission.school as n
             ON n.ID = d.SchoolID
             join db_academic.program_study as o
             on o.ID = a.ID_program_study
             where a.ID in (select ID_register_formulir from db_finance.payment_pre)
             order by a.ID desc
            LIMIT '.$start. ', '.$limit;
     $query=$this->db->query($sql, array())->result_array();
     $this->load->model('master/m_master');
     for ($i=0; $i < count($query); $i++) { 
       $getCicilan = $this->m_master->caribasedprimary('db_finance.payment_pre','ID_register_formulir',$query[$i]['ID_register_formulir']);
       $arr_temp[$i] = array(
          'ID_register_formulir' => $query[$i]['ID_register_formulir'],
          'VA_number' => $query[$i]['VA_number'],
          'Name' => $query[$i]['Name'],
          'NamePrody' => $query[$i]['NamePrody'],
          'SchoolName' => $query[$i]['SchoolName'],
          'Status1' => $query[$i]['status1'],
          'Email' => $query[$i]['Email'],
       );
       for ($j=0; $j < count($getCicilan); $j++) {
         $arr_temp2 = array(); 
         $arr_temp2 = array(
          'Cicilan_'.($j + 1) => array(
                                      'Invoice' => number_format($getCicilan[$j]['Invoice'],2,',','.'),
                                      'BilingID' => $getCicilan[$j]['BilingID'],
                                      'Status' => $getCicilan[$j]['Status'],
                                      'Deadline' => $getCicilan[$j]['Deadline'],
                                      'ID' => $getCicilan[$j]['ID'],
                                      )
        );
         if (count($getCicilan) == ($j+1)) {
            $a = $this->m_master->showData_array('db_admission.cfg_cicilan');
           if (count($getCicilan) != $a[0]['max_cicilan']) {
             $b = $a[0]['max_cicilan'] - count($getCicilan);
             for ($k=0; $k < $b; $k++) { 
               $arr_temp3 = array();
                 $arr_temp3 = array(
                  'Cicilan_'.($j + 1 + $k + 1) => array(
                                              'Invoice' => '',
                                              'BilingID' => '',
                                              'Status' => '',
                                              'Deadline' => '',
                                              'ID' => '',
                                              )
                );
                $arr_temp2 = $arr_temp2 + $arr_temp3; 
             }
           }
         }
         $arr_temp[$i] = $arr_temp[$i] + $arr_temp2;
       }
     }
     return $arr_temp;

    }

    public function getDataPersonal($ID_register_formulir)
    {
      $sql = "select a.*,c.FormulirCode from db_admission.register as a join db_admission.register_verification as b
              on a.ID = b.RegisterID join db_admission.register_verified as c on b.ID = c.RegVerificationID
              join db_admission.register_formulir as d on d.ID_register_verified = c.ID where d.ID = ?
            ";
      $query=$this->db->query($sql, array($ID_register_formulir))->result_array();
      return $query;
    }

    public function cekTanggalTime($datetime)
    {
      $sql = 'select * from(select now() as a,"'.$datetime.'" as d) tbl
              where a > d';
      $query=$this->db->query($sql, array())->result_array();

      if (count($query) > 0) {
        return true;
      }
      else
      {
        return false;
      }
    }

    public function getDataCalonMhsAll($limit, $start,$input = null)
    {
      $arr_result = array();
      $Nama = '';
      $Sekolah = '';
      $Email = '';
      $BFormulir = $input['BFormulir'];
      $Ranking = '';
      $BKuliah = $input['BKuliah'];
      $Prody = '';
        if ($input != null) {
          $Nama = ' Name like "%'.$input['Nama'].'%"' ;
          $Sekolah = ($input['Sekolah'] != '') ? ' and SchoolID = '.$input['Sekolah'] : '';
          $Email = ($input['Email'] != '') ? ' and Email like "'.$input['Email'].'%"' : '';
          /*$BFormulir = ($input['BFormulir'] == 0) ? ' and FormulirCode is NULL' : '';
          if ($input['BFormulir'] == 1) {
            $BFormulir = ' and FormulirCode is NOT NULL';
          }*/
          if ($input['BFormulir'] == '%') {
            $BFormulir = '';
          }
          elseif($input['BFormulir'] == 1)
          {
            $BFormulir = ' and FormulirCode is NOT NULL';
          }
          elseif($input['BFormulir'] == 0)
          {
            $BFormulir = ' and FormulirCode is NULL';
          }

          $Ranking = ($input['Prody'] != '' ) ? ' and Rangking ='.$input['Ranking'] : '';
          if ($input['Ranking'] == '%') {
            $Ranking = '';
          }

          $v= ($input['BFormulir'] == '0') ? '= 0' : '!= 0';
          // $BKuliah = ($input['BKuliah'] != '%') ? ' and NotLunas '.$v : '';
          $Prody = ($input['Prody'] != '%' ) ? ' and ID_program_study ='.$input['Prody'] : '';

          if ($input['BKuliah'] != '%') {
            if ($input['BKuliah'] == 1) {
              $BKuliah = ' and NotLunas = 0 and FormulirCode is not null and chklunas > 0';
            }
            else
            {
              $BKuliah = '  and (chklunas != Cicilan) or chklunas = 0';
            }
          }
          else
          {
            $BKuliah = '';
          }

          $sql = 'select * from (
                  select a.ID as RegisterID,a.Name,a.SchoolID,b.SchoolName,a.Email,a.VA_number,c.FormulirCode,e.ID_program_study,d.NameEng,e.ID as ID_register_formulir,e.UploadFoto,
                  f.Rangking,(select count(*) as total from db_finance.payment_pre as aaa where aaa.ID_register_formulir =  e.ID and aaa.Status = 0) as NotLunas,
                  (select count(*) as total from db_finance.payment_pre as aaa where aaa.ID_register_formulir =  e.ID and aaa.Status = 1) as chklunas,
                  (select count(*) as total from db_finance.payment_pre as aaa where aaa.ID_register_formulir =  e.ID ) as Cicilan
                  from db_admission.register as a
                  join db_admission.school as b
                  on a.SchoolID = b.ID
                  LEFT JOIN db_admission.register_verification as z
                  on a.ID = z.RegisterID
                  LEFT JOIN db_admission.register_verified as c
                  on z.ID = c.RegVerificationID
                  LEFT JOIN db_admission.register_formulir as e
                  on c.ID = e.ID_register_verified
                  LEFT join db_academic.program_study as d
                  on e.ID_program_study = d.ID
                  LEFT join db_admission.register_rangking as f
                  on e.ID = f.ID_register_formulir
                  ) ccc where '.$Nama.$Sekolah.$Email.$Ranking.$Prody.$BFormulir.$BKuliah.' order by ID_register_formulir desc
            LIMIT '.$start. ', '.$limit;
          $query=$this->db->query($sql, array())->result_array();
          $arr_result = $query;
        }
        return $arr_result;
    }

    public function set_input_tuition_fee_submit($input)
    {
      //save data to payment_register
      $this->load->model('master/m_master');
      $data2 = $input['data2'][0];
      $arr = [];
      $temp = array();
      $temp2 = array();
      $temp['ID_register_formulir'] = $data2->id_formulir;
      $temp['TypeBeasiswa'] = $data2->getBeasiswa;
      $temp['FileBeasiswa'] = $data2->getDokumen;
      $temp['Desc'] = $data2->ket;
      $temp['CreateAT'] = date('Y-m-d');
      $temp['CreateBY'] = $this->session->userdata('NIP');
      $this->db->insert('db_finance.register_admisi', $temp);

      foreach ($data2 as $key => $value) {
            $a = explode('-', $key);
            if (count($a) > 1) {
              $get = $this->m_master->caribasedprimary('db_finance.payment_type','Abbreviation',$a[1]);
              $temp2['Discount'] = $value;
              $temp2['Pay_tuition_fee'] = $this->m_master->ClearPricetoDB($data2->$a[1]);
              $temp2['PTID'] = $get[0]['ID'];
              $temp2['ID_register_formulir'] = $data2->id_formulir;
              
              $arr[] = $temp2;
            }
            
      }
      $this->db->insert_batch('db_finance.payment_admisi', $arr);
      

      // save data cicilan pada table payment_pre
        $ID_register_formulir = $data2->id_formulir;
        $data1 = $input['data1'];
        $arr2 = array();
        for ($i=0; $i < count($data1); $i++) { 
          $temp3 = array();
          $temp3['ID_register_formulir'] = $ID_register_formulir;
          $temp3['Invoice'] = $data1[$i]->Payment;
          $temp3['Deadline'] = $data1[$i]->Deadline;
          $arr2[] = $temp3;
        }

        $this->db->insert_batch('db_finance.payment_pre', $arr2);

    }

}
