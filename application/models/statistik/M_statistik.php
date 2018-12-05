<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_statistik extends CI_Model {

    public $data = array();
    public $Year = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_master');
        $this->db_statistik = $this->load->database('statistik', true);
    }

    public function droptablerekapintake($Year)
    {
        $Table = 'rekapintake_'.$Year;
        $this->db_statistik->query('DROP TABLE IF EXISTS '.$Table);
    }

    public function droptable($tblname)
    {
        $this->db_statistik->query('DROP TABLE IF EXISTS '.$tblname);
    }

    public function ShowRekapIntake($Year)
    {
        $arr_result = array();
        // passing variable to global
        $this->Year = $Year;

        //check table rekap exist
        $chk = $this->tableRekapExist($Year);
        if ($chk) {
            //show data in table
            $arr_result = $this->m_master->showData_array('db_statistik.rekapintake_'.$this->Year);
        }
        else
        {
            // create table
            $this->create_table();
            // insert data 
            $this->insert_data();
            $arr_result = $this->m_master->showData_array('db_statistik.rekapintake_'.$this->Year);
        }

        return $arr_result;
    }

    private function insert_data()
    {
        $Year = $this->Year;
        $Table = 'rekapintake_'.$Year;
        $getColoumn = $this->m_master->getColumnTable('db_statistik.'.$Table);
        $getProdi = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
        $arr_bulan = array(
            'Jan','Feb','March','April','May','June','July','August','Sep','Oct','Nov','Des'
        );
        for ($i=0; $i < count($getProdi); $i++) { 
            $ProdiID = $getProdi[$i]['ID'];
           // get per month
            $datasave = array('ProdiID' => $ProdiID);
            $field = $getColoumn['field'];
            for ($j=0; $j < count($field); $j++) { 
                if ($field[$j] != 'ID' && $field[$j] != 'ProdiID') {
                    $aa =  $field[$j];
                    if (strpos($aa, '_') !== true ) {
                        $monthQ = substr($aa, 0,strpos($aa, '_'));
                        $YearQ = $Year - 1;
                    }
                    else
                    {
                        $monthQ = $aa;
                        $YearQ = $Year;
                    }

                    // find  month number
                    for ($z=0; $z < count($arr_bulan); $z++) { 
                        if ($arr_bulan[$z] == $monthQ) {
                            $monthQ = $z  + 1;
                            break;
                        }
                    }
                    
                    $sql = 'select count(*) as total from (
                             select a.ID as RegisterID,a.Name,a.SchoolID,b.SchoolName,a.Email,a.VA_number,c.FormulirCode,e.ID_program_study,d.NameEng,d.Name as NamePrody, 
                            e.ID as ID_register_formulir,
                            if(a.StatusReg = 1, 
                            (select No_Ref from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,"" ) as No_Ref,
                            if(a.StatusReg = 1, 
                            (select DateFin from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,a.RegisterAT ) as intakedate
                            from db_admission.register as a 
                            join db_admission.school as b on a.SchoolID = b.ID 
                            LEFT JOIN db_admission.register_verification as z on a.ID = z.RegisterID 
                            LEFT JOIN db_admission.register_verified as c on z.ID = c.RegVerificationID 
                            LEFT JOIN db_admission.register_formulir as e on c.ID = e.ID_register_verified 
                            LEFT join db_academic.program_study as d on e.ID_program_study = d.ID 
                            left join db_admission.sale_formulir_offline as xz on c.FormulirCode = xz.FormulirCodeOffline  
                             where a.SetTa = "'.$Year.'" 
                            ) ccc where MONTH(intakedate) = "'.$monthQ.'" and Year(intakedate) = "'.$YearQ.'" and ID_program_study = ?';
                            $query=$this->db->query($sql, array($ProdiID))->result_array();
                            $total = $query[0]['total'];
                            $datasave[$field[$j]] = $total;
                            // print_r($monthQ);die();
                    
                }
            }
            // print_r($datasave);die();
            $this->db_statistik->insert($Table, $datasave);
        }
        $this->saveLastUpdated($Table);
    }

    private function saveLastUpdated($Table)
    {
        $datasave = array(
            'TableName' => $Table,
            'LastUpdated' => date('Y-m-d H:i:s'),
        );
        $c = $this->m_master->caribasedprimary('db_statistik.lastupdated','TableName',$Table);
        if (count($c) > 0) {
            $dataSave = array(
                'lastupdated' => date('Y-m-d H:i:s'),
            );
            $this->db->where('TableName', $Table);
            $this->db->update('db_statistik.lastupdated', $dataSave);
        }
        else
        {
            $this->db_statistik->insert('lastupdated', $datasave);
        }
    }

    private function create_table()
    {
        $Year = $this->Year;
        $Table = 'rekapintake_'.$Year;
        $this->db_statistik->query('CREATE TABLE '.$Table.' (
                                  `ID` int(11) NOT NULL AUTO_INCREMENT,
                                  `ProdiID` int(11) DEFAULT NULL,
                                  `August_` int(11) DEFAULT NULL,
                                  `Sep_` int(11) DEFAULT NULL,
                                  `Oct_` int(11) DEFAULT NULL,
                                  `Nov_` int(11) DEFAULT NULL,
                                  `Des_` int(11) DEFAULT NULL,
                                  `Jan` int(11) DEFAULT NULL,
                                  `Feb` int(11) DEFAULT NULL,
                                  `March` int(11) DEFAULT NULL,
                                  `April` int(11) DEFAULT NULL,
                                  `May` int(11) DEFAULT NULL,
                                  `June` int(11) DEFAULT NULL,
                                  `July` int(11) DEFAULT NULL,
                                  `August` int(11) DEFAULT NULL,
                                  `Sep` int(11) DEFAULT NULL,
                                  `Oct` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`ID`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=latin1');
    }

    public function tableRekapExist($Year)
    {
        $sql = 'show tables like "rekapintake_'.$Year.'";';
        $query=$this->db_statistik->query($sql, array())->result_array();
        if (count($query) > 0) {
            return true;
        }
        else
        {
            return false;
        }

    }

    public function trigger_formulir($ta,$month,$year,$ProdiID,$action)
    {
        $chk = $this->tableRekapExist($ta);
        if (!$chk) {
            $this->Year = $ta;
            $this->create_table();
            // insert data 
            $this->insert_data();
        }
        else
        {
            $arr_bulan = array(
                'Jan','Feb','March','April','May','June','July','August','Sep','Oct','Nov','Des'
            );
            $month = (int) $month;
            $month = $month - 1;//for indeks array
            $field = ($ta == $year) ? $arr_bulan[$month ] : $arr_bulan[$month ].'_';
            $Table = 'rekapintake_'.$ta;
            switch ($action) {
                case 'add':
                    $sql = 'update db_statistik.'.$Table.' set '.$field.' = '.$field.' + 1 where ProdiID = ?';
                    $query=$this->db_statistik->query($sql, array($ProdiID));
                    break;
                case 'delete':
                    $sql = 'update db_statistik.'.$Table.' set '.$field.' = '.$field.' - 1 where ProdiID = ?';
                    $query=$this->db_statistik->query($sql, array($ProdiID));
                    break;
                default:
                    # code...
                    break;
            }

        }
    }

    public function ShowRekapIntake_Beasiswa($Year)
    {
        $arr_result = array();
        // passing variable to global
        $this->Year = $Year;
        $tblname = 'rekapintake_bea_'.$this->Year;
        //check table rekap exist
        $chk = $this->table_Exist($tblname);
        if ($chk) {
            //show data in table
            $arr_result = $this->m_master->showData_array('db_statistik.rekapintake_bea_'.$this->Year);
        }
        else
        {
            // create table
            $this->create_table_rekapintake_bea_();
            // insert data 
            $this->insert_data_rekapintake_bea_();
            $arr_result = $this->m_master->showData_array('db_statistik.rekapintake_bea_'.$this->Year);
        }

        return $arr_result;
    }

    public function table_Exist($tblname)
    {
        $sql = 'show tables like "'.$tblname.'"';
        $query=$this->db_statistik->query($sql, array())->result_array();
        if (count($query) > 0) {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function create_table_rekapintake_bea_()
    {
        $Year = $this->Year;
        $Table = 'rekapintake_bea_'.$Year;
        $this->db_statistik->query('CREATE TABLE '.$Table.' (
                                      `ID` int(11) NOT NULL AUTO_INCREMENT,
                                      `ProdiID` int(11) DEFAULT NULL,
                                      `Detail` longtext,
                                      PRIMARY KEY (`ID`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1'
                                  );
    }

    private function getPersentType()
    {
        $Year = $this->Year;
        $arr_result = array();
        $sql = 'select  DISTINCT(Discount) as total from (
                select f.Discount
                from db_admission.register as a 
                join db_admission.school as b on a.SchoolID = b.ID 
                LEFT JOIN db_admission.register_verification as z on a.ID = z.RegisterID 
                LEFT JOIN db_admission.register_verified as c on z.ID = c.RegVerificationID 
                LEFT JOIN db_admission.register_formulir as e on c.ID = e.ID_register_verified 
                LEFT join db_academic.program_study as d on e.ID_program_study = d.ID 
                left join db_admission.sale_formulir_offline as xz on c.FormulirCode = xz.FormulirCodeOffline  
                join db_finance.payment_admisi as f on e.ID = f.ID_register_formulir    
                join db_finance.register_admisi as g on e.ID = g.ID_register_formulir
                 where a.SetTa = "'.$Year.'"and g.`Status` = "Approved"
                ) cc ORDER BY Discount asc';
        $query=$this->db_statistik->query($sql, array())->result_array();
        foreach ($query as $key) {
            foreach ($key as $keya => $value) {
                $arr_result[] = (int)$value;
            }
        }
        return $arr_result;        
    }

    private function insert_data_rekapintake_bea_()
    {
        $Year = $this->Year;
        $Table = 'rekapintake_bea_'.$Year;
        $getProdi = $this->m_master->caribasedprimary('db_academic.program_study','Status',1);
        $bea = $this->getPersentType();
        for ($i=0; $i < count($getProdi); $i++) {
            $datasave = array(); 
            $ProdiID = $getProdi[$i]['ID'];
            $fieldPersen = array();
            for ($j=0; $j < count($bea); $j++) { 
                $p = $bea[$j];
                $NmP = 'Beasiswa_'.$bea[$j].'%';
                $sql = 'select  count(*) as total from (
                        select a.ID as RegisterID,a.Name,a.SchoolID,b.SchoolName,a.Email,a.VA_number,c.FormulirCode,e.ID_program_study,d.NameEng,d.Name as NamePrody, 
                        e.ID as ID_register_formulir,f.Discount,
                        if(a.StatusReg = 1, 
                        (select No_Ref from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,"" ) as No_Ref,
                        if(a.StatusReg = 1, 
                        (select DateFin from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,a.RegisterAT ) as intakedate
                        from db_admission.register as a 
                        join db_admission.school as b on a.SchoolID = b.ID 
                        LEFT JOIN db_admission.register_verification as z on a.ID = z.RegisterID 
                        LEFT JOIN db_admission.register_verified as c on z.ID = c.RegVerificationID 
                        LEFT JOIN db_admission.register_formulir as e on c.ID = e.ID_register_verified 
                        LEFT join db_academic.program_study as d on e.ID_program_study = d.ID 
                        left join db_admission.sale_formulir_offline as xz on c.FormulirCode = xz.FormulirCodeOffline  
                        join db_finance.payment_admisi as f on e.ID = f.ID_register_formulir    
                        join db_finance.register_admisi as g on e.ID = g.ID_register_formulir
                         where a.SetTa = "'.$Year.'"  and g.`Status` = "Approved" and f.Discount = '.$p.' and e.ID_program_study = ?
                        ) cc';
                        $query=$this->db_statistik->query($sql, array($ProdiID))->result_array();
                        $fieldPersen[$NmP] = $query[0]['total'];
            }

            $datasave = array(
                   'ProdiID' =>  $ProdiID,
                   'Detail' => json_encode($fieldPersen),
            );

            $this->db_statistik->insert($Table, $datasave);
        }

        $this->saveLastUpdated($Table);
        
    }

    public function ShowRekapIntake_School($Year)
    {
        $arr_result = array();
        // passing variable to global
        $this->Year = $Year;
        $tblname = 'rekapintake_sch_'.$this->Year;
        //check table rekap exist
        $chk = $this->table_Exist($tblname);
        if ($chk) {
            //show data in table
            $arr_result = $this->m_master->showData_array('db_statistik.rekapintake_sch_'.$this->Year);
        }
        else
        {
            // create table
            $this->create_table_rekapintake_sch_();
            // insert data 
            $this->insert_data_rekapintake_sch_();
            $arr_result = $this->m_master->showData_array('db_statistik.rekapintake_sch_'.$this->Year);
        }

        return $arr_result;
    }

    private function create_table_rekapintake_sch_()
    {
        $Year = $this->Year;
        $Table = 'rekapintake_sch_'.$Year;
        $this->db_statistik->query('CREATE TABLE '.$Table.' (
                                      `ID` int(11) NOT NULL AUTO_INCREMENT,
                                      `ProvinceID` varchar(25) DEFAULT NULL,
                                      `Qty` int(11) DEFAULT NULL,
                                      `Detail` longtext,
                                      PRIMARY KEY (`ID`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1'
                                  );
    }

    private function insert_data_rekapintake_sch_()
    {
        $Year = $this->Year;
        $Table = 'rekapintake_sch_'.$Year;
        $getProv = $this->m_master->showData_array('db_admission.province');
        for ($i=0; $i < count($getProv); $i++) {
            $datasave = array(); 
            $Prov = $getProv[$i]['ProvinceID'];
            $Detail = array();
            $sql = '
                select  count(*) as total from (
                                        select a.ID as RegisterID,a.Name,a.SchoolID,b.SchoolName,a.Email,a.VA_number,c.FormulirCode,e.ID_program_study,d.NameEng,d.Name as NamePrody, 
                                        e.ID as ID_register_formulir,
                                        if(a.StatusReg = 1, 
                                        (select No_Ref from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,"" ) as No_Ref,
                                        if(a.StatusReg = 1, 
                                        (select DateFin from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,a.RegisterAT ) as intakedate
                                        from db_admission.register as a 
                                        join db_admission.school as b on a.SchoolID = b.ID 
                                        LEFT JOIN db_admission.register_verification as z on a.ID = z.RegisterID 
                                        LEFT JOIN db_admission.register_verified as c on z.ID = c.RegVerificationID 
                                        LEFT JOIN db_admission.register_formulir as e on c.ID = e.ID_register_verified 
                                        LEFT join db_academic.program_study as d on e.ID_program_study = d.ID 
                                        left join db_admission.sale_formulir_offline as xz on c.FormulirCode = xz.FormulirCodeOffline  
                                         where a.SetTa = "'.$Year.'" and b.ProvinceID = "'.$Prov.'"
                                        ) cc
            ';
              $query=$this->db_statistik->query($sql, array())->result_array();
              $Qty = $query[0]['total'];
              if ($Qty > 0) {
                  $c = $this->m_master->caribasedprimary('db_admission.province_region','ProvinceID',$Prov);
                  if (count($c) > 0) {
                      for ($l=0; $l < count($c); $l++) { 
                          $RegionID = $c[$l]['RegionID'];
                          $sql = '
                              select  count(*) as total from (
                                                      select a.ID as RegisterID,a.Name,a.SchoolID,b.SchoolName,a.Email,a.VA_number,c.FormulirCode,e.ID_program_study,d.NameEng,d.Name as NamePrody, 
                                                      e.ID as ID_register_formulir,
                                                      if(a.StatusReg = 1, 
                                                      (select No_Ref from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,"" ) as No_Ref,
                                                      if(a.StatusReg = 1, 
                                                      (select DateFin from db_admission.formulir_number_offline_m where FormulirCode = c.FormulirCode limit 1) ,a.RegisterAT ) as intakedate
                                                      from db_admission.register as a 
                                                      join db_admission.school as b on a.SchoolID = b.ID 
                                                      LEFT JOIN db_admission.register_verification as z on a.ID = z.RegisterID 
                                                      LEFT JOIN db_admission.register_verified as c on z.ID = c.RegVerificationID 
                                                      LEFT JOIN db_admission.register_formulir as e on c.ID = e.ID_register_verified 
                                                      LEFT join db_academic.program_study as d on e.ID_program_study = d.ID 
                                                      left join db_admission.sale_formulir_offline as xz on c.FormulirCode = xz.FormulirCodeOffline  
                                                       where a.SetTa = "'.$Year.'" and b.CityID = "'.$RegionID.'"
                                                      ) cc
                          ';
                            $query=$this->db_statistik->query($sql, array())->result_array();
                            $Detail[$RegionID] = $query[0]['total'];
                      }
                  }
              }

              $datasave = array(
                'ProvinceID' => $Prov,
                'Qty' => $Qty,
                'Detail' => json_encode($Detail),
              );
            $this->db_statistik->insert($Table, $datasave);
        }

        $this->saveLastUpdated($Table);
    }


}
