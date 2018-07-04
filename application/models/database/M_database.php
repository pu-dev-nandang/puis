<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_database extends CI_Model {

    public function __getSemester()
    {
        $data = $this->db->query('SELECT s.*, e.Name AS NameEmployee FROM db_academic.semester s
                                            JOIN db_employees.employees e ON (s.UpdateBy = e.NIP)
                                             ORDER BY s.ID DESC');

        return $data->result_array();
    }

    public function insert_data_employees($ReligionID = null,$JobGradeID = null,$PositionMain = null,$ProdiID = null,$CityID = null,$ProvinceID =  null,$NIP,$NIDN = null,$KTP = null,$Name,$TitleAhead = null,$TitleBehind = null,$Gender = null,$PlaceOfBirth = null,$DateOfBirth = null,$Phone = null,$HP = null,$Email = null ,$EmailPU,$Password = 123456,$Address = null,$Photo,$PositionOther1 = null,$PositionOther2 = null,$PositionOther3 = null,$StatusEmployeeID = null,$Status = "1")
    {
    	// proses password
    	$Password = $this->genratePassword($NIP,$Password);
    	$dataSave = array(
    	        'ReligionID' => $ReligionID,
    	        'JobGradeID' => $JobGradeID,
    	        'PositionMain' => $PositionMain,
    	        'ProdiID' => $ProdiID,
    	        'CityID' => $CityID,
    	        'ProvinceID' => $ProvinceID,
    	        'NIP' => $NIP,
    	        'NIDN' => $NIDN,
    	        'KTP' => $KTP,
    	        'Name' => $Name,
    	        'TitleAhead' => $TitleAhead,
    	        'TitleBehind' => $TitleBehind,
    	        'Gender' => $Gender,
    	        'PlaceOfBirth' => $PlaceOfBirth,
    	        'DateOfBirth' => $DateOfBirth,
    	        'Phone' => $Phone,
    	        'HP' => $HP,
    	        'Email' => $Email,
    	        'EmailPU' => $EmailPU,
    	        'Password' => $Password,
    	        'Address' => $Address,
    	        'Photo' => $Photo,
    	        'PositionOther1' => $PositionOther1,
    	        'PositionOther2' => $PositionOther2,
    	        'PositionOther3' => $PositionOther3,
    	        'StatusEmployeeID' => $StatusEmployeeID,
    	        'Status' => $Status,
    	                );
    	$this->db->insert('db_employees.employees', $dataSave);
    }

    public function genratePassword($NIP,$Password){

        $plan_password = $NIP.''.$Password;
        $pas = md5($plan_password);
        $pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');
        return $pass;
    }

    public function update_data_employees1($ReligionID,$PositionMain,$NIP,$NIDN,$KTP,$Name,$TitleAhead,$TitleBehind,$Gender,$PlaceOfBirth,$DateOfBirth,$Phone,$HP,$Email,$EmailPU,$Address,$PositionOther1,$PositionOther2,$PositionOther3,$StatusEmployeeID,$NIPedit)
    {
    	$dataSave = array(
    	        'ReligionID' => $ReligionID,
    	        'PositionMain' => $PositionMain,
    	        'NIP' => $NIP,
    	        'NIDN' => $NIDN,
    	        'KTP' => $KTP,
    	        'Name' => $Name,
    	        'TitleAhead' => $TitleAhead,
    	        'TitleBehind' => $TitleBehind,
    	        'Gender' => $Gender,
    	        'PlaceOfBirth' => $PlaceOfBirth,
    	        'DateOfBirth' => $DateOfBirth,
    	        'Phone' => $Phone,
    	        'HP' => $HP,
    	        'Email' => $Email,
    	        'EmailPU' => $EmailPU,
    	        'Address' => $Address,
    	        'PositionOther1' => $PositionOther1,
    	        'PositionOther2' => $PositionOther2,
    	        'PositionOther3' => $PositionOther3,
    	        'StatusEmployeeID' => $StatusEmployeeID,
    	                );
    	$this->db->where('NIP',$NIPedit);
    	$this->db->update('db_employees.employees', $dataSave);
    }

    public function update_data_employees2($ReligionID,$PositionMain,$NIP,$NIDN,$KTP,$Name,$TitleAhead,$TitleBehind,$Gender,$PlaceOfBirth,$DateOfBirth,$Phone,$HP,$Email,$EmailPU,$Address,$PositionOther1,$PositionOther2,$PositionOther3,$StatusEmployeeID,$NIPedit)
    {
    	$dataSave = array(
    	        'ReligionID' => $ReligionID,
    	        'PositionMain' => $PositionMain,
    	        'NIP' => $NIP,
    	        'NIDN' => $NIDN,
    	        'KTP' => $KTP,
    	        'Name' => $Name,
    	        'TitleAhead' => $TitleAhead,
    	        'TitleBehind' => $TitleBehind,
    	        'Gender' => $Gender,
    	        'PlaceOfBirth' => $PlaceOfBirth,
    	        'DateOfBirth' => $DateOfBirth,
    	        'Phone' => $Phone,
    	        'HP' => $HP,
    	        'Email' => $Email,
    	        'EmailPU' => $EmailPU,
    	        'Address' => $Address,
    	        'PositionOther1' => $PositionOther1,
    	        'PositionOther2' => $PositionOther2,
    	        'PositionOther3' => $PositionOther3,
    	        'StatusEmployeeID' => $StatusEmployeeID,
    	        // 'Photo' => $Photo,
    	                );
    	$this->db->where('NIP',$NIPedit);
    	$this->db->update('db_employees.employees', $dataSave);
    }

    public function changestatus($NIP,$Active)
    {
    	if ($Active == 0) {
    		$dataSave = array(
    		        'Status' => "1",
    		                );
    	}
    	else
    	{
    		$dataSave = array(
    		        'Status' => "0",
    		                );
    	}
    	
    	$this->db->where('NIP',$NIP);
    	$this->db->update('db_employees.employees', $dataSave);
    }

}
