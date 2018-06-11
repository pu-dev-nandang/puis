<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_finance extends CI_Model {

   private $data = array();
   function __construct()
   {
       parent::__construct();
   }

   public function getEmailnURLCheckbox($arr,$delimiter)
   {
    $this->load->library('JWT');
    $key = "UAP)(*";
    $arr_temp = array();
    for ($i=0; $i < count($arr); $i++) { 
      $temp = explode($delimiter, $arr[$i]);
      $url = $this->jwt->encode($temp[0].";".$temp[2],$key);
      $arr_temp[] = array('email'=>$temp[2],'url' => $url);
    }
    return $arr_temp;
   }

   public function ProcessSaveDataVerification($arrData)
   {
      $arrData = explode(",", $arrData);
      for ($i=0; $i < count($arrData); $i++) { 
        $temp = explode(";", $arrData[$i]);
        if ($temp[0] != 'nothing') {
          if ($temp[1] == 'null') {
            // insert data ke db register_verification
            $this->saveData_register_verification($temp[0]);
            $this->load->model('master/m_master');
            $query = $this->m_master->caribasedprimary('db_admission.register_verification','RegisterID',$temp[0]);
            $id_register_verification = $query[0]['ID'];
            $this->saveDataRegisterVerified($id_register_verification);
          }
          else
          {
            // dapatkan id register verification dahulu
            $this->load->model('master/m_master');
            $query = $this->m_master->caribasedprimary('db_admission.register_verification','RegisterID',$temp[0]);
            $id_register_verification = $query[0]['ID'];
            $this->saveDataRegisterVerified($id_register_verification);
          }
        }
      }
   }

   public function saveData_register_verification($registerID)
   {
    $dataSave = array(
            'RegisterID' => $registerID,
                    );

    $this->db->insert('db_admission.register_verification', $dataSave);
   }

   public function saveDataRegisterVerified($register_verified)
   {
    $getFormulirCode = $this->getFormulirCode('online');
    $dataSave = array(
            'RegVerificationID' => $register_verified,
            'FormulirCode' => $getFormulirCode,
            'VerificationBY' => $this->session->userdata('NIP'),
            'VerificationAT' => date('Y-m-d H:i:s'),
                    );
    $this->db->insert('db_admission.register_verified', $dataSave);
   }

   public function getFormulirCode($tipeFormulirCode = null)
   {
    if ($tipeFormulirCode == 'online') { // online
        $sql = "select FormulirCode from db_admission.formulir_number_online_m where Status = 0 and Years ='".date('Y')."' order by ID asc limit 1";
    }
    else{
      $sql = "select FormulirCode from db_admission.formulir_number_offline_m where Status = 0 and Years ='".date('Y')."' order by ID asc limit 1";
    }
    $query=$this->db->query($sql, array())->result_array();
    $FormulirCode = $query[0]['FormulirCode'];
    
    if ($tipeFormulirCode == 'online') { // online
      $this->updateStatusFormulirCodeOnline($FormulirCode);
    }
    else
    {
      $this->updateStatusFormulirCodeOffline($FormulirCode);
    }

    return $FormulirCode;
   }

   public function updateStatusFormulirCodeOnline($FormulirCode)
   {
    $sql = "update db_admission.formulir_number_online_m set Status = 1 where FormulirCode = '".$FormulirCode."'";
    $query=$this->db->query($sql, array());
   }

   public function updateStatusFormulirCodeOffline($FormulirCode)
   {
    $sql = "update db_admission.formulir_number_offline_m set Status = 1 where FormulirCode = '".$FormulirCode."'";
    $query=$this->db->query($sql, array());
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
           where d.Name like '.$Nama.' and d.SchoolID like '.$Sekolah.' and a.ID_program_study like '.$selectProgramStudy.' and a.ID in (select ID_register_formulir from db_admission.register_nilai where Status = "Verified") LIMIT '.$start. ', '.$limit;
          $query=$this->db->query($sql, array())->result_array();
          return $query;
   }

   public function getVerified_Nilai($ID_register_formulir)
   {
    $sql = 'select a.*,b.Name from db_admission.register_nilai as a
            join db_employees.employees as b
            on b.NIP = a.VerifiedBY
            where a.ID_register_formulir = ? limit 1';
    $query=$this->db->query($sql, array($ID_register_formulir))->result_array();
    return $query;
   }

   public function submit_approved_nilai_rapor($input)
   {
    for ($i=0; $i < count($input); $i++) {
      $dataSave = array(
              'ApprovedBY' => $this->session->userdata('NIP'),
              'Status' => 'Approved',
              'ApprovedAT' => date('Y-m-d'),
                      );
      $this->db->where('ID_register_formulir',$input[$i]);
      $this->db->update('db_admission.register_nilai', $dataSave);
    }
   }

   public function set_tuition_fee_approve($input)
   {
    for ($i=0; $i < count($input); $i++) { 
      $dataSave = array(
              'ApprovedBY' => $this->session->userdata('NIP'),
              'Status' => 'Approved',
              'ApprovedAT' => date('Y-m-d'),
                      );
      $this->db->where('ID_register_formulir',$input[$i]);
      $this->db->update('db_finance.payment_register', $dataSave);
      // generate pdf
      $this->PDF_tuition_fee_approved($input[$i]);
    }
   }

   public function update_payment_pre($BilingID)
   {
      $dataSave = array(
              'Status' => 1,
              'UpdateAt' => date('Y-m-d H:i:s'),
                      );
      $this->db->where('BilingID',$BilingID);
      $this->db->update('db_finance.payment_pre', $dataSave);
   }

   public function proses_cicilan($ID_register_formulir,$data_register)
   {
    $msg = 'Your Payment is complete.';
    $checkCicilan = $this->checkCicilan($ID_register_formulir,0);
    if (count($checkCicilan) > 0) {
      $Invoice = $checkCicilan[0]['Invoice'];
      $Deadline = $checkCicilan[0]['Deadline'];
      $ID = $checkCicilan[0]['ID'];
      $p = $this->create_va_Payment($Invoice,$Deadline, $data_register[0]['Name'], $data_register[0]['Email'],$data_register[0]['VA_number']);
      $this->updateCicilanBiling($p,$ID);
      $msg = 'Please continue to pay the next installment with VA Number : '.$data_register[0]['VA_number']. ' <br> as much as Rp '.number_format($checkCicilan[0]['Invoice'],2,',','.');
    }

    return $msg;

   }

   public function updateCicilanBiling($data,$ID)
   {
      $dataSave = array(
              'BilingID' => $data['msg']['trx_id'],
                      );
      $this->db->where('ID',$ID);
      $this->db->update('db_finance.payment_pre', $dataSave);
   }

   public function checkCicilan($ID_register_formulir,$status)
   {
    $sql = 'select * from db_finance.payment_pre where ID_register_formulir = ? and Status = ? order by ID asc limit 1';
    $query=$this->db->query($sql, array($ID_register_formulir,$status))->result_array();
    return $query;
   }

   public function create_va_Payment($payment = null,$DeadLinePayment = null, $Name = null, $Email = null,$VA_number = null)
   {
       $arr = array();
       $arr['status'] = false;
       $arr['msg'] = '';
       if ($payment != null) {
           include_once APPPATH.'third_party/bni/BniEnc.php';
           // FROM BNI
           $client_id = '00202';
           $secret_key = '8ef738df0433c674e6663f3f7f5e6b68';
           $url = 'https://apibeta.bni-ecollection.com/';
           $getVANumber = $VA_number;
           $datetime_expired = $DeadLinePayment;

           if ($getVANumber != null) {
               $data_asli = array(
                   'client_id' => $client_id,
                   'trx_id' => mt_rand(), // fill with Billing ID
                   'trx_amount' => $payment,
                   'billing_type' => 'c',
                   'datetime_expired' => $datetime_expired, // billing will be expired in 2 hours
                   'virtual_account' => $getVANumber,
                   'customer_name' => $Name,
                   'customer_email' => $Email,
                   'customer_phone' => '+622129200456',
                   'description' => 'Pembayaran Uang Kuliah',
                   'type' => 'createbilling',
               );

               $hashed_string = BniEnc::encrypt(
                   $data_asli,
                   $client_id,
                   $secret_key
               );

               $data = array(
                   'client_id' => $client_id,
                   'data' => $hashed_string,
               );

               $response = $this->get_content($url, json_encode($data));
               $response_json = json_decode($response, true);

               if ($response_json['status'] !== '000') {
                   $arr['status'] = false;
                   $arr['msg'] = '';
               }
               else {
                   $data_response = BniEnc::decrypt($response_json['data'], $client_id, $secret_key);
                   $this->insert_va_log($data_asli,'db_finance.payment_pre');
                   $arr['status'] = true;
                   $arr['msg'] = $data_asli;
               }
           }
           else
           {
               $arr['status'] = false;
               $arr['msg'] = '';
           }
       }
       
       return $arr;
   }

   private function get_content($url, $post = '') {
       $usecookie = __DIR__ . "/cookie.txt";
       $header[] = 'Content-Type: application/json';
       $header[] = "Accept-Encoding: gzip, deflate";
       $header[] = "Cache-Control: max-age=0";
       $header[] = "Connection: keep-alive";
       $header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       curl_setopt($ch, CURLOPT_HEADER, false);
       curl_setopt($ch, CURLOPT_VERBOSE, false);
       // curl_setopt($ch, CURLOPT_NOBODY, true);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
       curl_setopt($ch, CURLOPT_ENCODING, true);
       curl_setopt($ch, CURLOPT_AUTOREFERER, true);
       curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

       curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");

       if ($post)
       {
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
       }

       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

       $rs = curl_exec($ch);

       if(empty($rs)){
           var_dump($rs, curl_error($ch));
           curl_close($ch);
           return false;
       }
       curl_close($ch);
       return $rs;
   }

   public function insert_va_log($data,$routes_table = 'db_admission.register')
   {
       $dataSave = array(
               'trx_id' => $data['trx_id'],
               'virtual_account' => $data['virtual_account'],
               'customer_name' => $data['customer_name'],
               'customer_email' => $data['customer_email'],
               'customer_phone' => $data['customer_phone'],
               'billing_type' => $data['billing_type'],
               'trx_amount' => $data['trx_amount'],
               'datetime_expired' => $data['datetime_expired'],
               'description' => $data['description'],
               'Status' => 0,
               'Created' => date('Y-m-d H:i:s'),
               'routes_table' => $routes_table,
                       );

       $this->db->insert('db_va.va_log', $dataSave);
   }

   public function updateCicilanDeadline($data,$ID)
   {
      $dataSave = array(
              'BilingID' => $data['trx_id'],
              'Deadline' => $data['datetime_expired'],
                      );
      $this->db->where('ID',$ID);
      $this->db->update('db_finance.payment_pre', $dataSave);
   }

   public function update_va_log($data,$routes_table = 'db_admission.register')
   {
       $dataSave = array(
               'customer_name' => $data['customer_name'],
               'customer_email' => $data['customer_email'],
               'customer_phone' => $data['customer_phone'],
               'trx_amount' => $data['trx_amount'],
               'datetime_expired' => $data['datetime_expired'],
               'description' => $data['description'],
               'Status' => 0,
               'Created' => date('Y-m-d H:i:s'),
               'routes_table' => $routes_table,
                       );
       $this->db->where('trx_id',$data['trx_id']);
       $this->db->update('db_va.va_log', $dataSave);
   }

   public function update_va_Payment($payment = null,$DeadLinePayment = null, $Name = null, $Email = null,$BilingID = null)
   {
       $arr = array();
       $arr['status'] = false;
       $arr['msg'] = '';
       if ($payment != null) {
           include_once APPPATH.'third_party/bni/BniEnc.php';
           // FROM BNI
           $client_id = '00202';
           $secret_key = '8ef738df0433c674e6663f3f7f5e6b68';
           $url = 'https://apibeta.bni-ecollection.com/';
           $datetime_expired = $DeadLinePayment;

           if ($BilingID != null) {
               $data_asli = array(
                   'client_id' => $client_id,
                   'trx_id' => $BilingID, // fill with Billing ID
                   'trx_amount' => $payment,
                   'customer_name' => $Name,
                   'customer_email' => $Email,
                   'customer_phone' => '+622129200456',
                   'datetime_expired' => $datetime_expired, // billing will be expired in 2 hours
                   'description' => 'Pembayaran Uang Kuliah',
                   'type' => 'updateBilling',

               );

               $hashed_string = BniEnc::encrypt(
                   $data_asli,
                   $client_id,
                   $secret_key
               );

               $data = array(
                   'client_id' => $client_id,
                   'data' => $hashed_string,
               );

               $response = $this->get_content2($url, json_encode($data));
               $response_json = json_decode($response, true);

               if ($response_json['status'] !== '000') {
                   
                   $arr['status'] = false;
                   $arr['msg'] = '';
               }
               else {
                   $data_response = BniEnc::decrypt($response_json['data'], $client_id, $secret_key);
                   $this->update_va_log($data_asli,'db_finance.payment_pre');
                   $arr['status'] = true;
                   $arr['msg'] = $data_asli;
               }
           }
           else
           {
               $arr['status'] = false;
               $arr['msg'] = '';
           }
       }
       
       return $arr;
   }

   private function get_content2($url, $post = '') {
       $usecookie = __DIR__ . "/cookie2.txt";
       $header[] = 'Content-Type: application/json';
       $header[] = "Accept-Encoding: gzip, deflate";
       $header[] = "Cache-Control: max-age=0";
       $header[] = "Connection: keep-alive";
       $header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       curl_setopt($ch, CURLOPT_HEADER, false);
       curl_setopt($ch, CURLOPT_VERBOSE, false);
       // curl_setopt($ch, CURLOPT_NOBODY, true);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
       curl_setopt($ch, CURLOPT_ENCODING, true);
       curl_setopt($ch, CURLOPT_AUTOREFERER, true);
       curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

       curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");

       if ($post)
       {
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
       }

       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

       $rs = curl_exec($ch);

       if(empty($rs)){
           var_dump($rs, curl_error($ch));
           curl_close($ch);
           return false;
       }
       curl_close($ch);
       return $rs;
   }

   public function getTuitionFee($ID_register_formulir)
   {
    $sql = 'select d.id as ID_register_formulir,b.ProdiID,a.Description,b.Cost,c.Discount,c.Pay_tuition_fee from db_finance.payment_type as a
            join db_finance.tuition_fee as b on a.ID = b.PTID
            join db_admission.register_formulir as d
            on d.ID_program_study = b.ProdiID
            join db_finance.payment_register as c
            on c.ID_register_formulir = d.ID
            where c.PTID = a.ID and d.ID = ? and b.ClassOf = YEAR(CURDATE())';
      $query=$this->db->query($sql, array($ID_register_formulir))->result_array();
      return $query;      
   }

   private function PDF_tuition_fee_approved($ID_register_formulir)
   {
       //error_reporting(0);
       $this->load->model('master/m_master');
       $this->load->model('admission/m_admission');

       $query = $this->m_admission->getDataPersonal($ID_register_formulir);
       $query2 = $this->m_master->caribasedprimary('db_admission.school','ID',$query[0]['SchoolID']);

       $data = $this->getTuitionFee($ID_register_formulir);
       $query3 = $this->m_master->caribasedprimary('db_academic.program_study','ID',$data[0]['ProdiID']);

       $arr_temp = array('filename' => '');
       $filename = 'Tuition_fee.pdf';
       $getData = $this->m_master->showData_array('db_admission.set_label_token_off');
       $setXAwal = 10;
       $setYAwal = 18;
       $setJarakY = 5;
       $setJarakX = 40;
       $setFontIsian = 12;
           $config=array('orientation'=>'P','size'=>'A4');
           $this->load->library('mypdf',$config);
           $this->mypdf->SetMargins(10,10,10,10);
           $this->mypdf->SetAutoPageBreak(true, 0);
           $this->mypdf->AddPage();
           // Logo
           $this->mypdf->Image('./images/logo_tr.png',10,10,50);
           $this->mypdf->SetFont('Arial','B',10);
           $this->mypdf->Text(150, 17, 'Formulir Number : '.$query[0]['FormulirCode']);

           // Line break
           $this->mypdf->Ln(20);

           // isian
           $setY = $setYAwal + 20;
           $setX = $setXAwal; 

           // label
           $this->mypdf->SetXY($setX,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
           $this->mypdf->Cell(0, 0, 'Nama', 0, 1, 'L', 0);

           // titik dua
           $setXtitik2 = $setX+$setJarakX;
           $this->mypdf->SetXY($setXtitik2,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$setFontIsian);
           $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

           // value
           $setXvalue = $setXtitik2 + 2;
           $this->mypdf->SetXY($setXvalue,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
           $this->mypdf->Cell(0, 0, $query[0]['Name'], 0, 1, 'L', 0);

           $setY = $setY + 8;

           // label
           $this->mypdf->SetXY($setX,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
           $this->mypdf->Cell(0, 0, 'Sekolah', 0, 1, 'L', 0);

           // titik dua
           $setXtitik2 = $setX+$setJarakX;
           $this->mypdf->SetXY($setXtitik2,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$setFontIsian);
           $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

           // value
           $setXvalue = $setXtitik2 + 2;
           $this->mypdf->SetXY($setXvalue,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
           $this->mypdf->Cell(0, 0, $query2[0]['SchoolName'], 0, 1, 'L', 0);

           $setY = $setY + 8;
           // label
           $this->mypdf->SetXY($setX,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
           $this->mypdf->Cell(0, 0, 'Program Studi', 0, 1, 'L', 0);

           // titik dua
           $setXtitik2 = $setX+$setJarakX;
           $this->mypdf->SetXY($setXtitik2,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$setFontIsian);
           $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

           // value
           $setXvalue = $setXtitik2 + 2;
           $this->mypdf->SetXY($setXvalue,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
           $this->mypdf->Cell(0, 0, $query3[0]['Name'], 0, 1, 'L', 0);

           $setY = $setY + 8;
           // label
           $this->mypdf->SetXY($setX,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
           $this->mypdf->Cell(0, 0, 'Virtual Account', 0, 1, 'L', 0);

           // titik dua
           $setXtitik2 = $setX+$setJarakX;
           $this->mypdf->SetXY($setXtitik2,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$setFontIsian);
           $this->mypdf->Cell(0, 0, ":", 0, 1, 'L', 0);

           // value
           $setXvalue = $setXtitik2 + 2;
           $this->mypdf->SetXY($setXvalue,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           $this->mypdf->SetFont('Arial','',$getData[0]['setFont1']);
           $this->mypdf->Cell(0, 0, $query[0]['VA_number'], 0, 1, 'L', 0);
          
            $setY = $setY + 10;

           $this->mypdf->SetXY($setX,$setY); 
           $this->mypdf->SetFillColor(226, 226, 226);
           $this->mypdf->Cell(8,9,'No',1,0,'C',true);
           $this->mypdf->Cell(45,9,'Type Biaya Kuliah',1,0,'C',true);
           $this->mypdf->Cell(60,9,'Harga',1,0,'C',true);
           $this->mypdf->Cell(25,9,'Diskon',1,0,'C',true);
           $this->mypdf->Cell(50,9,'Pembayaran',1,1,'C',true);

          $t = 0; 
          for ($i=0; $i < count($data); $i++) { 
            $no = $i + 1;
            $this->mypdf->SetFillColor(255, 255, 255);
            $this->mypdf->Cell(8,9,$no,1,0,'C',true);
            $this->mypdf->Cell(45,9,$data[$i]['Description'],1,0,'L',true);
            $this->mypdf->Cell(60,9,'Rp '.number_format($data[$i]['Cost'],2,',','.'),1,0,'L',true);
            $this->mypdf->Cell(25,9,$data[$i]['Discount'].'%',1,0,'L',true);
            $this->mypdf->Cell(50,9,'Rp '.number_format($data[$i]['Pay_tuition_fee'],2,',','.'),1,1,'L',true);
            $t = $t + $data[$i]['Pay_tuition_fee'];
          }

          $this->mypdf->Cell(8,9,'',0,0,'C',true);
          $this->mypdf->Cell(45,9,'',0,0,'L',true);
          $this->mypdf->Cell(60,9,'',0,0,'L',true);
          $this->mypdf->Cell(25,9,'',0,0,'L',true);
          $this->mypdf->Cell(50,9,'Rp '.number_format($t,2,',','.'),0,1,'L',true);

          $this->mypdf->Cell(25,5,'Note : ',0,1,'L',true);
          $this->mypdf->SetFont('Arial','',9);
          $this->mypdf->Cell(100,5,'* Biaya kuliah per semester : Biaya BPP + (Biaya per SKS (Credit) * Jumlah SKS) +  Biaya lain-lain persemester,',0,1,'L',true);
          $this->mypdf->Cell(100,5,'* Jika calon mahasiswa tidak lulus Ujian Nasional (UN) maka biaya yang telah dibayarkan akan dikembalikan dan ',0,1,'L',true);
          $this->mypdf->Cell(100,5,'  dipotong biaya administrasi sebesar Rp 500.000,00 setelah menunjukan surat keterangan dari sekolah, ',0,1,'L',true);
          $this->mypdf->Cell(100,5,'* Apabila diterima di Perguruan Tinggi Negri (PTN) yaitu UI,ITB,UNPAD,UNDIP,IPB,UGM,UNAIR,ITS melalui ',0,1,'L',true);
          $this->mypdf->Cell(100,5,'  jalur SNMPTN & SBMPTN (tidak termasuk jalur Ujian Mandiri, program diploma & politeknik negri) maka biaya yang telah',0,1,'L',true);
          $this->mypdf->Cell(100,5,'  dibayarkan akan dikembalikan & dipotong biaya administrasi Rp 1.500.000,00 ',0,1,'L',true);
          $this->mypdf->Cell(100,5,'  (dengan menunjukan surat penerimaan dari universitas terkait) ',0,1,'L',true);
          
           
           $this->mypdf->Line(20, 280, 190, 280);
           $setY = 282;
           $this->mypdf->SetFont('Arial','',6);
           $this->mypdf->SetXY(40,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           // $this->mypdf->SetFillColor(0,0,0);
           $this->mypdf->Cell(190, 5, 'Admission Office :  Central Park Mall, Lantai 3, Unit 112, Podomoro City, JL Letjen S. Parman Kav.28, Jakarta Barat 11470', 0, 1, 'L', 0);
           $setY = 285;
           $this->mypdf->SetFont('Arial','',6);
           $this->mypdf->SetXY(43,$setY);
           $this->mypdf->SetTextColor(0,0,0);
           // $this->mypdf->SetFillColor(0,0,0);
           $this->mypdf->Cell(190, 5, 'Telp : (021) 292 00 456    Email : admission@podomorouniversity.ac.id   Website : www.podomorouniversity.ac.id', 0, 1, 'L', 0);

           $path = './document';
           $path = $path.'/'.$filename;
           $this->mypdf->Output($path,'F');

           $this->load->model('m_sendemail');
           $text = 'Dear '.$query[0]['Name'].',<br><br>
                       Plase find attached your payment.<br>
                       Please login to your portal ('.$this->GlobalVariableAdi['url_registration']."login/".') to set up tuition installments.
                   ';
           $to = $query[0]['Email'];
           $subject = "Podomoro University Tuition Fee";
           $sendEmail = $this->m_sendemail->sendEmail($to,$subject,null,null,null,null,$text,$path);
   }

   public function get_tagihan_mhs($ta,$prodi,$PTID,$limit, $start)
   {
    $arr = array();
    $this->load->model('master/m_master');
    $ta1 = explode('.', $ta);
    $ta = $ta1[1];
    $db = 'ta_'.$ta.'.students';
    $field = 'StatusStudentID';
    $value = 3;
    if ($prodi == '') {
     $sql = 'select a.*,b.EmailPU,c.Cost from '.$db.' as a left join db_academic.auth_students as b on a.NPM = b.NPM left join db_finance.tuition_fee as c
             on a.ProdiID = c.ProdiID
             where a.StatusStudentID = ?  and a.NPM not in (select NPM from db_finance.payment) and c.ClassOf = ? and c.PTID = ? 
             LIMIT '.$start. ', '.$limit;

     $Data_mhs=$this->db->query($sql, array($value,$ta,$PTID))->result_array();
    }
    else
    {
      $sql = 'select a.*,b.EmailPU,c.Cost from '.$db.' as a left join db_academic.auth_students as b on a.NPM = b.NPM left join db_finance.tuition_fee as c
              on a.ProdiID = c.ProdiID
              where a.StatusStudentID = ? and a.ProdiID = ? and a.NPM not in (select NPM from db_finance.payment) and c.ClassOf = ? and c.PTID = ? 
              LIMIT '.$start. ', '.$limit;
      $Data_mhs=$this->db->query($sql, array($value,$prodi,$ta,$PTID))->result_array();
    }

    $SemesterID = $this->m_master->caribasedprimary('db_academic.semester','Status',1);
    // $SemesterID = $SemesterID[0]['ID'];
    $Discount = $this->m_master->showData_array('db_finance.discount');
    for ($i=0; $i < count($Data_mhs); $i++) { 
      $array = array('SemesterID' => $SemesterID[0]['ID'], 'SemesterName' => $SemesterID[0]['Name']);
      $Data_mhs[$i] = $Data_mhs[$i] + $array;
      $ProdiEng = $this->m_master->caribasedprimary('db_academic.program_study','ID',$Data_mhs[$i]['ProdiID']);
      $array = array('ProdiEng' => $ProdiEng[0]['NameEng']);
      $Data_mhs[$i] = $Data_mhs[$i] + $array;
    }
    $arr['Data_mhs'] = $Data_mhs;
    $arr['Discount'] = $Discount;
    return $arr;
   }

}
