<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_general_affair extends CI_Model {

	public function fetchPackage($count=false,$param='',$start='',$limit='',$order=''){
        $where='';$startDate = date("Y-m-d");
        if(!empty($param)){
            $where = 'WHERE ';
            $counter = 0;
            foreach ($param as $key => $value) {
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

        if($count){
            $select = "count(*) as Total";
        }else{
            $select = "*";
        }
        $sorted = " order by ".(!empty($order) ? $order : 'Created desc');
        
        $string = "SELECT {$select}
                   FROM db_general_affair.package_order
                   {$where} {$sorted} {$lims} ";
        
        $value  = $this->db->query($string);
        //var_dump($this->db->last_query());
        return $value;
    }

    public function fetchLostNFound($count=false,$param='',$start='',$limit='',$order=''){
        $where='';$startDate = date("Y-m-d");
        if(!empty($param)){
            $where = 'WHERE ';
            $counter = 0;
            foreach ($param as $key => $value) {
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

        if($count){
            $select = "count(*) as Total";
        }else{
            $select = "*";
        }
        $sorted = " order by ".(!empty($order) ? $order : 'Created desc');
        
        $string = "SELECT {$select}
                   FROM db_general_affair.lost_n_found
                   {$where} {$sorted} {$lims} ";
        
        $value  = $this->db->query($string);
        //var_dump($this->db->last_query());
        return $value;
    }

    

}