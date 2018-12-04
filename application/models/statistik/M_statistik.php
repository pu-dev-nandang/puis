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
        $this->db_statistik->query('DROP TABLE '.$Table);
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


}
