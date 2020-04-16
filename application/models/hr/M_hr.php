<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hr extends CI_Model {


    public function checkPermanentDelete($NIP){

        $res = 0;

        // Cek apakah berelasi dengan jadwal
        $dataJ = $this->db->query('SELECT s.ID FROM db_academic.schedule s 
                                               LEFT JOIN db_academic.schedule_team_teaching stt 
                                               ON (stt.ScheduleID = s.ID)
                                               WHERE s.Coordinator = "'.$NIP.'" 
                                               OR stt.NIP = "'.$NIP.'" ')->result_array();

        $dataJlama = $this->db->query('SELECT s.ID FROM db_academic.z_schedule s
                                            LEFT JOIN db_academic.z_team_teaching ztt 
                                            ON (ztt.ScheduleID = s.ID)
                                            WHERE s.NIP = "'.$NIP.'" 
                                            OR ztt.NIP = "'.$NIP.'" ')->result_array();



        // Cek apakah dia mempunyai jabatan
        $dataKa = $this->db->query('SELECT ID FROM db_academic.program_study 
                                      WHERE KaprodiID = "'.$NIP.'" ')->result_array();

        // Cek apakah dia mempunyai mahasiswa bimbingan
        $dataMentor = $this->db->query('SELECT ID FROM db_academic.mentor_academic 
                                          WHERE NIP = "'.$NIP.'"')->result_array();
        if(count($dataJ)<=0 && count($dataJlama)<=0 && count($dataKa)<=0 && count($dataMentor)<=0){
            $res = array(
                'Status' => '0',
                'Msg' => 'Abel to delete permanent'
            );
        } else {
            if(count($dataJ)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist on schedule'
                );
            }
            else if(count($dataJlama)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist on schedule in Old System (SIAK LAMA)'
                );
            }
            else if(count($dataKa)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist AS -Kaprodi- '
                );
            }
            else if(count($dataMentor)>0){
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee is exist as mentor (Dosen PA)'
                );
            } else {
                $res = array(
                    'Status' => '1',
                    'Msg' => 'Employee EMPTY'
                );
            }
        }

        return $res;

    }

    public function getLecPartime(){
        $data = $this->db->query('SELECT * FROM db_employees.employees em
                                          WHERE em.StatusEmployeeID != -2 AND em.StatusEmployeeID = 4 ')->result_array();

        return $data;
    }


    /*ADDED BY FEBRI @ FEB 2020*/
    public function getMemberSTO($data){
        $this->db->select("a.ID as CareerID,a.StartJoin,a.EndJoin,a.LevelID,a.DepartmentID,a.PositionID,a.JobTitle,a.Superior,a.StatusID,a.Remarks, e.*");
        $this->db->from("db_employees.employees_career a");
        $this->db->join("db_employees.employees_career b","b.NIP = a.NIP and b.PositionID = a.PositionID and a.StartJoin > b.StartJoin","left");
        $this->db->join("db_employees.employees_career c","a.NIP = c.NIP and a.PositionID = c.PositionID and a.StartJoin > c.StartJoin and b.StartJoin > c.StartJoin","LEFT OUTER");
        $this->db->join("db_employees.sto_temp d","a.PositionID = d.ID","left");
        $this->db->join("db_employees.employees e","e.NIP = a.NIP","left");
        $this->db->where($data);
        $this->db->group_by("a.NIP");
        $query = $this->db->get();

        return $query;
    }

    public function getTitleSTO($keyword=null){
        $query = "select a.Position as Name, a.Description from db_employees.position a where a.Description like '%".$keyword."%' or a.Position like '%".$keyword."%'
                  union 
                  select b.Division as Name, b.Description from db_employees.division b where b.Description like '%".$keyword."%' or b.Division like '%".$keyword."%' ";
        $result = $this->db->query($query);
        return $result;
    }

    public function getEmpCareer($data){
        $this->db->select("a.*, b.name as LevelName, c.title as DepartmentName, d.title as PositionName");
        $this->db->from("db_employees.employees_career a");
        $this->db->join("db_employees.master_level b","b.ID=a.LevelID","left");
        $this->db->join("db_employees.sto_temp c","c.ID=a.DepartmentID","left");
        $this->db->join("db_employees.sto_temp d","d.ID=a.PositionID","left");
        $this->db->where($data);
        $this->db->order_by("a.StartJoin, a.EndJoin","desc");
        $query = $this->db->get();
        return $query;
    }



    public function fetchEmployee($count=false,$param='',$start='',$limit='',$order=''){
        $where='';$startDate = date("Y-m-d");
        if(!empty($param)){
            $where = 'WHERE '; $conditionDate = '';
            $counter = 0;
            foreach ($param as $key => $value) {
                if($value['field'] == "lem.AccessedOn"){
                    //$startDate = preg_replace("/'/", '', $value['data']);
                    $value['field'] = "DATE(".$value['field'].")";
                    $value['data'] = $value['data'];
                    //$conditionDate = " and "."DATE(a.AccessedOn)" ." ".$value['data'];
                }

                if($counter==0){
                    $where = $where.$value['field']." ".$value['data'];
                }else{ 
                    $where = $where.$value['filter']." ".$value['field']." ".$value['data'];
                }

                
                $counter++;
            }
        }

        $lims="";
        if($start!="" || $limit!=""){
            $lims = " LIMIT {$start},{$limit}"; 
        }

        $groupby = '';
        $sorted = '';
        if($count){
            $select = "count(DISTINCT em.NIP, DATE(lem.AccessedOn) ) as Total";
        }else{
            $select = "em.*
                        ,d.Division as DivisionMain_, p.Position as PositionMain_
                        ,concat(d1.Division,'-',p1.Position ) as PositionOther1
                        ,concat(d2.Division,'-',p2.Position ) as PositionOther2
                        ,concat(d3.Division,'-',p3.Position ) as PositionOther3
                        , DATE_FORMAT(lem.AccessedOn, '%d-%M-%Y %H:%i:%s') as FirstLoginPortal
                        ,DAYNAME(lem.AccessedOn) as FirstLoginPortalDay
                        ,WEEKDAY(lem.AccessedOn) as FirstLoginPortalDayNum ";
            $groupby = 'GROUP BY em.NIP, DATE(lem.AccessedOn)';
            $sorted = " order by ".(!empty($order) ? $order : 'FirstLoginPortal asc');
        }
        
        
        $string = "SELECT {$select}
                   FROM db_employees.log_employees lem
                   LEFT JOIN db_employees.employees em on (em.NIP = lem.NIP)
                   
                    LEFT JOIN db_employees.division d on (d.ID = SUBSTRING_INDEX(em.PositionMain,'.',1) ) 
                    LEFT JOIN db_employees.position p on (p.ID = SUBSTRING_INDEX(em.PositionMain,'.',-1) ) 

                    LEFT JOIN db_employees.division d1 on (d1.ID = SUBSTRING_INDEX(em.PositionOther1,'.',1) ) 
                    LEFT JOIN db_employees.position p1 on (p1.ID = SUBSTRING_INDEX(em.PositionOther1,'.',-1) ) 

                    LEFT JOIN db_employees.division d2 on (d2.ID = SUBSTRING_INDEX(em.PositionOther2,'.',1) ) 
                    LEFT JOIN db_employees.position p2 on (p2.ID = SUBSTRING_INDEX(em.PositionOther2,'.',-1) ) 

                    LEFT JOIN db_employees.division d3 on (d3.ID = SUBSTRING_INDEX(em.PositionOther3,'.',1) ) 
                    LEFT JOIN db_employees.position p3 on (p3.ID = SUBSTRING_INDEX(em.PositionOther3,'.',-1) ) 
                    
                    {$where} {$groupby} {$sorted} {$lims} ";
        
        $value  = $this->db->query($string);
        //var_dump($this->db->last_query());
        return $value;
    }


    public function fetchMemberOFDepartpent($data){
        $this->db->select('em.*,d.Division as DivisionMainName, p.Position as PositionMainName
                            ,( case when (em.PositionOther1 is null or em.PositionOther1 = "") then "" else ( concat(d1.Division,"-",p1.Position ) ) end ) as PositionOtherName1
                            ,( case when (em.PositionOther2 is null or em.PositionOther2 = "") then "" else ( concat(d2.Division,"-",p2.Position ) ) end ) as PositionOtherName2
                            ,( case when (em.PositionOther3 is null or em.PositionOther3 = "") then "" else ( concat(d3.Division,"-",p3.Position ) ) end ) as PositionOtherName3 ');
        $this->db->from('db_employees.employees em');
        $this->db->join('db_employees.division d','(d.ID = SUBSTRING_INDEX(em.PositionMain,".",1) )','left');
        $this->db->join('db_employees.position p','(p.ID = SUBSTRING_INDEX(em.PositionMain,".",-1) )','left');
        
        $this->db->join('db_employees.division d1','(d1.ID = SUBSTRING_INDEX(em.PositionOther1,".",1) )','left');
        $this->db->join('db_employees.position p1','(p1.ID = SUBSTRING_INDEX(em.PositionOther1,".",-1) )','left');
        
        $this->db->join('db_employees.division d2','(d2.ID = SUBSTRING_INDEX(em.PositionOther2,".",1) )','left');
        $this->db->join('db_employees.position p2','(p2.ID = SUBSTRING_INDEX(em.PositionOther2,".",-1) )','left');
        
        $this->db->join('db_employees.division d3','(d3.ID = SUBSTRING_INDEX(em.PositionOther3,".",1) )','left');
        $this->db->join('db_employees.position p3','(p3.ID = SUBSTRING_INDEX(em.PositionOther3,".",-1) )','left');
        $this->db->where($data);
        $this->db->order_by('em.PositionMain','asc');
        $query = $this->db->get();
        return $query;
    }
    /*END ADDED BY FEBRI @ FEB 2020*/

}
