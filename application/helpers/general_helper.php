<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function compareDate($first,$second){
	$firstDateMonth = date("m",strtotime($first));
	$firstDateYear = date("y",strtotime($first));
	$secondDateMonth = date("m",strtotime($second));
	$secondDateYear = date("y",strtotime($second));
	$dateName = "";
	
	if($firstDateYear != $secondDateYear){
		$dateName = date("D d M Y",strtotime($first))." - ".date("D d M Y",strtotime($second));
	}else{
		if($firstDateMonth == $secondDateMonth){
			$dateName = date("D d",strtotime($first))." - ".date("D d M Y",strtotime($second));
		}else{
			$dateName = date("D d M",strtotime($first))." - ".date("D d M Y",strtotime($second));
		}
	}
	return $dateName;
}


function labelWApproval($number){
	switch ($number) {
		case 1:
			return "<span class='btn btn-xs btn-info'><i class='fa fa-hourglass'></i> Process</span>";
			break;
		case 2:
			return "<span class='btn btn-xs btn-primary'><i class='fa fa-check-square-o'></i> Approved</span>";
			break;
		case 3:
			return "<span class='btn btn-xs btn-danger'><i class='fa fa-times'></i> Rejected</span>";
			break;
		
		default:
			return "<span class='btn btn-xs btn-default'><i class='fa fa-question'></i> Unknow</span>";
			break;
	}
}

function labelApproval($number){
	switch ($number) {
		case 1:
			return "<span class='btn btn-xs btn-info'><i class='fa fa-exclamation-triangle'></i> Need Approval</span>";
			break;
		case 2:
			return "<span class='btn btn-xs btn-primary'><i class='fa fa-check-square-o'></i> Approved</span>";
			break;
		case 3:
			return "<span class='btn btn-xs btn-danger'><i class='fa fa-times'></i> Rejected</span>";
			break;
		
		default:
			return "<span class='btn btn-xs btn-default'><i class='fa fa-question'></i> Unknow</span>";
			break;
	}
}


function labelProfileDB($table,$data){
	$CI = & get_instance();
	$CI->load->model('General_model');
	$results = $CI->General_model->fetchData($table,$data)->row();
	return $results;
}


function lastLogin($data){
	$CI = & get_instance();
	$CI->load->model('General_model');
	$results = $CI->General_model->fetchData("db_employees.log_employees a",$data,"a.ID","desc","0#1")->row();
	//var_dump($CI->db->last_query());die();
	return $results;
}

function lastLoginLect($data){
	$CI = & get_instance();
	$CI->load->model('General_model');
	$results = $CI->General_model->fetchData("db_employees.log_lecturers a",$data,"a.ID","desc","0#1")->row();
	//var_dump($CI->db->last_query());die();
	return $results;
}


function minMaxCalculate(){
	$result = array('min'=>3,'max'=>15,'maxTime'=>'09.30');
	return $result;
}

function calculateAttendanceTime($assigned_time,$completed_time){
	$d1 = new DateTime($assigned_time);
	$d2 = new DateTime($completed_time);
	$interval = $d2->diff($d1);

	$calculateDays = $interval->format('%d days');
	$calculateHours = $interval->format('%H');

	return $calculateHours;
}
