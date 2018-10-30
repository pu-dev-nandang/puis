<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_dashboard extends Globalclass {

    public function temp($content)
    {
        parent::template($content);
        $this->load->model('master/m_master');
        $this->load->model('m_menu');
    }

    public function index()
    {
        $data['department'] = parent::__getDepartement();
        $dpt = $this->session->userdata('IDdepartementNavigation');
        // print_r(APPPATH.'views/page/'.$data['department'].'/dashboard.php');die();
        if (file_exists(APPPATH.'views/page/'.$data['department'].'/dashboard.php')) {
            switch ($dpt) {
                case 10: // admission
                case 18: // BA
                    $set_ta = $this->m_master->showData_array('db_admission.set_ta');
                    $data['set_ta'] = $set_ta[0]['Ta'];
                    $this->m_menu->set_model('admission_sess','auth_admission_sess','menu_admission_sess','menu_admission_grouping','db_admission');
                    $content = $this->load->view('page/'.$data['department'].'/dashboard',$data,true);
                    $this->temp($content);
                    break;
                
                default:
                    $getSemester = $this->m_master->caribasedprimary('db_academic.semester','Status',1);
                    $data['getSemester'] = $getSemester;
                    $content = $this->load->view('page/'.$data['department'].'/dashboard',$data,true);
                    $this->temp($content);
                    break;
            }
           
        }
        else
        {
            $content = $this->load->view('dashboard/dashboard',$data,true);
            $this->temp($content);
        }
        
    }

    public function change_departement(){
        $dpt = $this->input->post('departement');
        $IDDivision = $this->input->post('IDDivision');
        $this->session->set_userdata('IDdepartementNavigation', ''.$IDDivision);
        parent::__setDepartement($dpt);
    }

    public function profile($username=''){
        $data['']=123;
        $content = $this->load->view('dashboard/profile','',true);
        $this->temp($content);
    }

    public function load_data_registration_upload()
    {
        $content = $this->load->view('page/load_data_registration_upload',$this->data,true);
        echo $content;
    }

    public function readNotificationDivision()
    {
        $input = $this->getInputToken();
        $this->load->model('master/m_master');
        $this->m_master->readNotificationDivision($input['IDDivision']);
        echo json_encode(1);
    }

    public function testadi()
    {
        /*for ($i=0; $i <= 100 ; $i= $i + 5) {
            $dataSave = array(
                'discount' => $i,
            );
            $this->db->insert('db_finance.discount', $dataSave);
        }

        echo 'test';*/
        

        $client = new Client(new Version1X('//localhost:3000'));

        $client->initialize();
        // send message to connected clients
        $client->emit('update_notifikasi', ['update_notifikasi' => '1']);
        $client->close();
    }

    public function page404(){
        parent::page404();
//        $data['']=123;
//        $content = $this->load->view('template/404page','',true);
//        $this->temp($content);
    }

    public function finance_dashboard()
    {
        // echo __FUNCTION__;
        $data['department'] = parent::__getDepartement();
        // print_r(APPPATH.'views/page/'.$data['department'].'/dashboard.php');die();
        if (file_exists(APPPATH.'views/page/'.$data['department'].'/dashboard.php')) {
            $content = $this->load->view('dashboard/finance_dashboard',$data,true);
            $this->temp($content);
        }
        else
        {
            $this->index();
        }

    }

    public function summary_payment()
    {
        $arr_json = array();
        $arrDB = array();
        $sqlDB = 'show databases like "%ta_2%"';
        $SemesterID = $this->m_master->caribasedprimary('db_academic.semester','Status',1);
        $SemesterYear = $SemesterID[0]['Year'];
        $queryDB=$this->db->query($sqlDB, array())->result_array();
        foreach ($queryDB as $key) {
          foreach ($key as $keyB ) {
            $YearDB = explode('_', $keyB);
            $YearDB = $YearDB[1];
            if ($SemesterYear >= $YearDB) {
                $arrDB[] = $keyB;
            }
          }
          
        }

        rsort($arrDB);
        $Year = 'ta_'.date('Y');
        $Semester = $SemesterID[0]['ID'];
        $Semester = ' and SemesterID = '.$Semester;
        $unk = 1;

        // get paid off
        $Paid_Off = array();
        $Unpaid_Off = array();
        $unsetPaid = array();
        for ($i=0; $i < count($arrDB); $i++) { 
            // if ($arrDB[$i] != $Year) {

                $a_Paid_Off = 0;
                $a_Unpaid_Off = 0;
                $a_unsetPaid = 0;
                    // get Data Mahasiswa
                    $sql = 'select a.NPM,a.Name,b.NameEng from '.$arrDB[$i].'.students as a join db_academic.program_study as b on a.ProdiID = b.ID where a.StatusStudentID in (3,2,8) ';
                    $query=$this->db->query($sql, array())->result_array();
                    for ($u=0; $u < count($query); $u++) { 

                      // cek BPP 
                      $sqlBPP = 'select * from db_finance.payment where PTID = 2 and NPM = ? '.$Semester; //  limit 1
                      $queryBPP=$this->db->query($sqlBPP, array($query[$u]['NPM']))->result_array();
                      $arrBPP = array(
                        'BPP' => '0',
                        'PayBPP' => '0',
                        'SisaBPP' => '0',
                        'DetailPaymentBPP' => '',
                      );
                        if (count($queryBPP) > 0) {
                            for ($t=0; $t < count($queryBPP); $t++) { 
                              // cek payment students
                              $Q_invStudent = $this->m_master->caribasedprimary('db_finance.payment_students','ID_payment',$queryBPP[$t]['ID']);
                              $PayBPP = 0;
                              $SisaBPP = 0;
                              for ($r=0; $r < count($Q_invStudent); $r++) { 
                                if ($Q_invStudent[$r]['Status'] == 1) { // lunas
                                  $PayBPP = $PayBPP + $Q_invStudent[$r]['Invoice'];
                                }
                                else
                                {
                                  $SisaBPP = $SisaBPP + $Q_invStudent[$r]['Invoice'];
                                }
                              }
                              
                              $arrBPP = array(
                                'BPP' => (int)$queryBPP[$t]['Invoice'],
                                'PayBPP' => (int)$PayBPP,
                                'SisaBPP' => (int)$SisaBPP,
                                'DetailPaymentBPP' => $Q_invStudent,
                              );

                            }
                        }

                      // cek Credit 
                      $sqlCr = 'select * from db_finance.payment where PTID = 3 and NPM = ? '.$Semester; // limit 1
                      $queryCr=$this->db->query($sqlCr, array($query[$u]['NPM']))->result_array();
                      $arrCr = array(
                        'Cr' => '0',
                        'PayCr' => '0',
                        'SisaCr' => '0',
                        'DetailPaymentCr' => '',
                      );
                        if (count($queryCr) > 0) {
                            for ($t=0; $t < count($queryCr); $t++) { 
                              // cek payment students
                              $Q_invStudent = $this->m_master->caribasedprimary('db_finance.payment_students','ID_payment',$queryCr[$t]['ID']);
                              $PayCr = 0;
                              $SisaCr = 0;
                              for ($r=0; $r < count($Q_invStudent); $r++) { 
                                if ($Q_invStudent[$r]['Status'] == 1) { // lunas
                                  $PayCr = $PayCr + $Q_invStudent[$r]['Invoice'];
                                }
                                else
                                {
                                  $SisaCr = $SisaCr + $Q_invStudent[$r]['Invoice'];
                                }
                              }

                              $arrCr = array(
                                'Cr' => (int)$queryCr[$t]['Invoice'],
                                'PayCr' => (int)$PayCr,
                                'SisaCr' => (int)$SisaCr,
                                'DetailPaymentCr' => $Q_invStudent,
                              );

                            }
                        }

                        if ($arrBPP['DetailPaymentBPP'] == '' || $arrCr['DetailPaymentCr'] == '') { // unset paid
                          $a_unsetPaid = $a_unsetPaid + 1;

                        }
                        else
                        {
                            if ($arrBPP['DetailPaymentBPP'] != '' && $arrCr['DetailPaymentCr'] != '' &&  $arrBPP['SisaBPP'] == 0 && $arrCr['SisaCr'] == 0) { // lunas
                              $a_Paid_Off = $a_Paid_Off + 1;

                            }
                            elseif ( $arrBPP['DetailPaymentBPP'] != '' || $arrCr['DetailPaymentCr'] != '' ||  $arrBPP['SisaBPP'] > 0 || $arrCr['SisaCr'] > 0 ) { // belum lunas
                              $a_Unpaid_Off = $a_Unpaid_Off + 1;

                            }     
                            
                        }  

                    } // loop per mhs    

                $strUnk = $unk.'.6818181818181817';
                $YearDB = explode('_', $arrDB[$i]);
                $YearDB = $YearDB[1];

                $Paid_Off[] = array($YearDB,$a_Paid_Off);
                $Unpaid_Off[] = array($YearDB,$a_Unpaid_Off);
                $unsetPaid[] = array($YearDB,$a_unsetPaid);
                $unk++;

            // }
        }

        $arr_json = array('Paid_Off'=> $Paid_Off,'Unpaid_Off' => $Unpaid_Off,'unsetPaid' => $unsetPaid);
        echo json_encode($arr_json);
    }

    public function dashboard_getoutstanding_today()
    {
        $requestData= $_REQUEST;
        // print_r($requestData);
        $sql = 'select count(*) as total 
                from db_finance.payment as a join db_academic.auth_students as b on a.NPM = b.NPM 
                join db_academic.semester as c on a.SemesterID = c.ID
                join db_finance.payment_type as d on a.PTID = d.ID join db_finance.payment_students as e
                on a.ID = e.ID_payment and e.Status = 0 and DATE_FORMAT(e.Deadline,"%Y-%m-%d") <= curdate() group by a.ID';
        $query=$this->db->query($sql, array())->result_array();
        $totalData = count($query);

        $sql = 'select a.*, b.Year,b.EmailPU,b.Pay_Cond,c.Name as NameSemester, d.Description 
                from db_finance.payment as a join db_academic.auth_students as b on a.NPM = b.NPM 
                join db_academic.semester as c on a.SemesterID = c.ID
                join db_finance.payment_type as d on a.PTID = d.ID join db_finance.payment_students as e
                on a.ID = e.ID_payment
            ';
        $Year = date('Y');    
        $sql.= ' where ( e.Status = 0 and DATE_FORMAT(e.Deadline,"%Y-%m-%d") <= curdate() ) and  (a.NPM like "'.$requestData['search']['value'].'%" or d.Description like "'.$requestData['search']['value'].'%" or c.Name like "'.$requestData['search']['value'].'%" ) and b.Year !='.$Year.' group by a.ID';
        $sql.= ' ORDER BY a.NPM ASC LIMIT '.$requestData['start'].' ,'.$requestData['length'].' ';

        $query = $this->db->query($sql)->result_array();

        $data = array();
        $this->load->model('master/m_master');
        for($i=0;$i<count($query);$i++){
            $nestedData=array();
            $row = $query[$i];
            $Year = $row['Year'];
            $getData = $this->m_master->caribasedprimary('ta_'.$Year.'.students','NPM',$row['NPM']);
            $nestedData[] = $row['NPM'].'<br>'.$getData[0]['Name'];
            $nestedData[] = $row['Description'];
            $nestedData[] = $row['NameSemester'];
            $nestedData[] = '<button class="btn btn-default edit" NPM = "'.$row['NPM'].'" semester = "'.$row['SemesterID'].'" PTID = "'.$row['PTID'].'" PaymentID = "'.$row['ID'].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
 Edit</button>';
            $data[] = $nestedData;
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

    public function summary_payment_admission()
    {
        $arr_json = array();
        $arrDB = array();
        $sqlDB = 'show databases like "%ta_2%"';
        $queryDB=$this->db->query($sqlDB, array())->result_array();
        foreach ($queryDB as $key) {
          foreach ($key as $keyB ) {
            $Year = explode('_', $keyB);
            $Year = $Year[1];
            $arrDB[] = $Year;
          }
          
        }

        rsort($arrDB);
        $taDb = $this->m_master->showData_array('db_admission.set_ta');
        $taDb = $taDb[0]['Ta'];
         if(!in_array($taDb, $arrDB))
           {
              $arrDB[] = $taDb;
           }
        // get paid off
        $Paid_Off = array();
        $Unpaid_Off = array();
        $Unset_Paid = array();
        for ($i=0; $i < count($arrDB); $i++) {
            // lunas 
            $sqlPaid_Off = 'select count(*) as total from (
                    select a.ID as ID_register_formulir,
                    if((select count(*) as total from db_finance.payment_pre where `Status` = 0 and ID_register_formulir = a.ID limit 1) = 0 ,"Lunas","Belum Lunas") as StatusPayment
                    from db_admission.register_formulir as a
                    left JOIN db_admission.register_verified as b 
                    ON a.ID_register_verified = b.ID
                    left JOIN db_admission.register_verification as c
                    ON b.RegVerificationID = c.ID
                    left JOIN db_admission.register as d
                    ON c.RegisterID = d.ID
                    left JOIN db_admission.country as e
                    ON a.NationalityID = e.ctr_code
                    left JOIN db_employees.religion as f
                    ON a.ReligionID = f.IDReligion
                    left JOIN db_admission.school_type as l
                    ON l.sct_code = a.ID_school_type
                    left JOIN db_admission.register_major_school as m
                    ON m.ID = a.ID_register_major_school
                    left JOIN db_admission.school as n
                    ON n.ID = d.SchoolID
                    left join db_academic.program_study as o
                    on o.ID = a.ID_program_study
                    left join db_finance.register_admisi as p
                    on a.ID = p.ID_register_formulir
                    where p.Status = "Approved"  and d.SetTa = "'.$arrDB[$i].'" group by a.ID

                    ) SubQuery where StatusPayment = "Lunas";
                ';
            $queryPaid_Off = $this->db->query($sqlPaid_Off)->result_array();  

            $sqlUnpaid_Off = 'select count(*) as total from (
                    select a.ID as ID_register_formulir,
                    if((select count(*) as total from db_finance.payment_pre where `Status` = 0 and ID_register_formulir = a.ID limit 1) = 0 ,"Lunas","Belum Lunas") as StatusPayment
                    from db_admission.register_formulir as a
                    left JOIN db_admission.register_verified as b 
                    ON a.ID_register_verified = b.ID
                    left JOIN db_admission.register_verification as c
                    ON b.RegVerificationID = c.ID
                    left JOIN db_admission.register as d
                    ON c.RegisterID = d.ID
                    left JOIN db_admission.country as e
                    ON a.NationalityID = e.ctr_code
                    left JOIN db_employees.religion as f
                    ON a.ReligionID = f.IDReligion
                    left JOIN db_admission.school_type as l
                    ON l.sct_code = a.ID_school_type
                    left JOIN db_admission.register_major_school as m
                    ON m.ID = a.ID_register_major_school
                    left JOIN db_admission.school as n
                    ON n.ID = d.SchoolID
                    left join db_academic.program_study as o
                    on o.ID = a.ID_program_study
                    left join db_finance.register_admisi as p
                    on a.ID = p.ID_register_formulir
                    where p.Status = "Approved"  and d.SetTa = "'.$arrDB[$i].'" group by a.ID

                    ) SubQuery where StatusPayment = "Belum Lunas";
                ';
            $queryUnpaid_Off = $this->db->query($sqlUnpaid_Off)->result_array(); 

            $sqlUnset_Paid = 'select count(*) as total from (
                    select a.ID as ID_register_formulir
                    from db_admission.register_formulir as a
                    left JOIN db_admission.register_verified as b 
                    ON a.ID_register_verified = b.ID
                    left JOIN db_admission.register_verification as c
                    ON b.RegVerificationID = c.ID
                    left JOIN db_admission.register as d
                    ON c.RegisterID = d.ID
                    left JOIN db_admission.country as e
                    ON a.NationalityID = e.ctr_code
                    left JOIN db_employees.religion as f
                    ON a.ReligionID = f.IDReligion
                    left JOIN db_admission.school_type as l
                    ON l.sct_code = a.ID_school_type
                    left JOIN db_admission.register_major_school as m
                    ON m.ID = a.ID_register_major_school
                    left JOIN db_admission.school as n
                    ON n.ID = d.SchoolID
                    left join db_academic.program_study as o
                    on o.ID = a.ID_program_study
                    left join db_finance.register_admisi as p
                    on a.ID = p.ID_register_formulir
                    where (p.Status = "Created"  or  a.ID not in (select ID_register_formulir from db_finance.register_admisi)  ) and d.SetTa = "'.$arrDB[$i].'" group by a.ID

                    ) SubQuery;
                ';
            $queryUnset_Paid = $this->db->query($sqlUnset_Paid)->result_array();   

             $Paid_Off[] = array($arrDB[$i],$queryPaid_Off[0]['total']);
             $Unpaid_Off[] = array($arrDB[$i],$queryUnpaid_Off[0]['total']);
             $Unset_Paid[] = array($arrDB[$i],$queryUnset_Paid[0]['total']);
        }

        $arr_json = array('Paid_Off'=> $Paid_Off,'Unpaid_Off' => $Unpaid_Off,'Unset_Paid' => $Unset_Paid);
        echo json_encode($arr_json);
    }

    public function summary_payment_formulir()
    {
        $arr_json = array();
        $arrDB = array();
        $sqlDB = 'show databases like "%ta_2%"';
        $queryDB=$this->db->query($sqlDB, array())->result_array();
        foreach ($queryDB as $key) {
          foreach ($key as $keyB ) {
            $Year = explode('_', $keyB);
            $Year = $Year[1];
            $arrDB[] = $Year;
          }
          
        }

        rsort($arrDB);
        $taDb = $this->m_master->showData_array('db_admission.set_ta');
        $taDb = $taDb[0]['Ta'];
         if(!in_array($taDb,$arrDB))
           {
              $arrDB[] = $taDb;
           }

        $Paid_Off = array();
        $Return_Formulir = array();
        for ($i=0; $i < count($arrDB); $i++) { 
            // lunas
            $sql = 'select count(*) as total from(
                select FormulirCode from db_admission.formulir_number_online_m where Status = 1 and Years = "'.$arrDB[$i].'"
                union
                select FormulirCode from db_admission.formulir_number_offline_m where StatusJual = 1 and Years = "'.$arrDB[$i].'"
            ) subquery';

            $query=$this->db->query($sql, array())->result_array();

            $sqlReturn_Formulir = 'select count(*) as total from(
                        select a.ID as ID_register_formulir
                        from db_admission.register_formulir as a
                        left JOIN db_admission.register_verified as b 
                        ON a.ID_register_verified = b.ID
                        left JOIN db_admission.register_verification as c
                        ON b.RegVerificationID = c.ID
                        left JOIN db_admission.register as d
                        ON c.RegisterID = d.ID
                        where d.SetTa = "'.$arrDB[$i].'"
                    ) subquery';

            $queryReturn_Formulir=$this->db->query($sqlReturn_Formulir, array())->result_array();

            $Paid_Off[] = array($arrDB[$i],$query[0]['total']);
            $Return_Formulir[] = array($arrDB[$i],$queryReturn_Formulir[0]['total']);
            
        }
        $arr_json = array('Paid_Off'=> $Paid_Off,'Return_Formulir' => $Return_Formulir);
        echo json_encode($arr_json);
        

    }

    public function SummaryFormulirPerSales()
    {
        $arr_result = array();   
        // get all grouping from sales
            $set_ta = $this->m_master->showData_array('db_admission.set_ta');
            $Ta = $set_ta[0]['Ta'];
            $sql = 'select a.PIC,if(b.Name IS NULL or b.Name = "","Unknown",b.Name) as Name,count(*) as total from db_admission.sale_formulir_offline as a 
                    left join db_employees.employees as b on a.PIC = b.NIP
                    left join db_admission.formulir_number_offline_m as c
                    on a.FormulirCodeOffline = c.FormulirCode
                    where c.Years = ?
                    group by a.PIC
                    ';
            $query=$this->db->query($sql, array($Ta))->result_array();
            for ($i=0; $i < count($query); $i++) { 
                $Name = explode(" ", trim($query[$i]['Name']));
                $Name = $Name[0];
                $arr_result[] = array($i,$query[$i]['total'],$Name);
            }
        $arr_json = array('arr_result'=> $arr_result);
        echo json_encode($arr_json);             

    }

    public function SummaryBox()
    {
        $set_ta = $this->m_master->showData_array('db_admission.set_ta');
        $Ta = $set_ta[0]['Ta'];
        // valueFormulir
            $sql = 'select sum(Price_Form) as total from
                    (
                        select a.Price_Form from db_admission.sale_formulir_offline as a 
                            left join db_admission.formulir_number_offline_m as c
                            on a.FormulirCodeOffline = c.FormulirCode
                            where c.Years = ?
                    )aa
                    ';
            $query=$this->db->query($sql, array($Ta))->result_array();

        // value tuition fee
            $sqlTuitionFee = 'select sum(Invoice) as total from(
                        select e.Invoice
                        from db_admission.register_formulir as a
                        left JOIN db_admission.register_verified as b 
                        ON a.ID_register_verified = b.ID
                        left JOIN db_admission.register_verification as c
                        ON b.RegVerificationID = c.ID
                        left JOIN db_admission.register as d
                        ON c.RegisterID = d.ID
                        left join db_finance.payment_pre as e
                        on a.ID = e.ID_register_formulir
                        where d.SetTa = ? and e.Status = 1
                    ) subquery';

            $queryTuitionFee=$this->db->query($sqlTuitionFee, array($Ta))->result_array();

        $arr_json = array('Formulir'=> ($query[0]['total'] == null || $query[0]['total'] == "") ? 0 : $query[0]['total'],'tuition_fee' => ($queryTuitionFee[0]['total'] == null || $queryTuitionFee[0]['total'] == "") ? 0 : $queryTuitionFee[0]['total']);
        echo json_encode($arr_json);         
    }


}