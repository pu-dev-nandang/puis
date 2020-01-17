<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_set extends CI_Model {

	function __construct()
	{
	    parent::__construct();
	    $this->load->model('master/m_master');
	}

	public function __generate($Props)
	{
		$rs = [];
		foreach ($Props as $key => $value) {
			$ex = explode('.', $value);
			$func = trim($ex[0]);
			if (!method_exists($this,$ex[0])) {
				echo json_encode('Method not exist '.$ex[0]);
				die();
			}

			if ($ex[0] == 'Signature') {
				$rs[$ex[0]][] = $this->$ex[0]($value);
			}
			else
			{
				$rs[$ex[0]] = $this->$ex[0]($value);
			}
			
			// $rs[][$ex[0]] = $this->$ex[0]($value); // call function
			
		}

		return $rs;
	}

	public function PolaNoSurat($params){
		// example :  041/UAP/R/SKU/X/2019
		$ex = explode('.', $params);
		if (count($ex) > 0 && count($ex) == 1 ) {
			return array(
				'value' => 'method_default',
				'setting' => array(
					'prefix' => '',
				),
				'sample' => '041/UAP/R/SKU/X/2019',
			);
		}

	}

	public function Signature($params){
		$rs = [];
		$ex = explode('.', $params);
		$entity = $ex[1];
		$exEntity = explode('#', $entity);
		if (count($exEntity) > 0 ) {
			switch ($exEntity[0]) {
				case 'NIP':
					
					break;
				case 'Position':
					$sql = 'select ID,Position as Value from db_employees.position where ID in(1,2,3,4,5,6,10,11,12)';
					$query = $this->db->query($sql,array())->result_array();
					$rs['Choose'] = $exEntity[0];
					$rs['select'] = $query;
					$rs['user'] = '';
					$rs['verify'] = '';
					$rs['cap'] = '';
					$rs['number'] = $exEntity[1];
					break;
				default:
					# code...
					break;
			}

		}
		else
		{
			echo 'Approval number not set';
			die();
		}
		
		return $rs;
		
	}

	public function preview_template($getObjInput,$ID_document='',$DepartmentID=''){
		$rs = [];
		foreach ($getObjInput as $key => $value) {
			switch ($key) {
				case 'PolaNoSurat':
					$obj = $getObjInput[$key];
					$method = $obj['value'];
					$setting = $obj['setting'];
					$PolaNoSurat = $this->__getValuePolaNoSurat($method,$setting,$ID_document);
					$rs['PolaNoSurat'] = $PolaNoSurat;
					break;
				case 'Signature':
					$obj = $getObjInput[$key];
					for ($i=0; $i < count($obj); $i++) { 
						$Choose = $obj[$i]['Choose'];
						$user = $obj[$i]['user'];
						$verify = $obj[$i]['verify'];
						$cap = $obj[$i]['cap'];
						switch ($Choose) {
							case 'Position':
								if ($DepartmentID != '') {
									$exDepartment = explode('.', $DepartmentID);
									$IDDivision = $exDepartment[1];
									if ($exDepartment[0] == 'NA') {

										if ($user == 11 || $user == 12) {
											
											$sql = "select * from db_employees.employees
											        where ( 
											        		(
											        		SPLIT_STR(PositionMain, '.', 2) = ".$user." or
											        		SPLIT_STR(PositionOther1, '.', 2) = ".$user." or
											        		SPLIT_STR(PositionOther2, '.', 2) = ".$user." or
											        		SPLIT_STR(PositionOther3, '.', 2) = ".$user." 
											        		)
											        		and 
											        		(
											        			SPLIT_STR(PositionMain, '.', 1) = ".$IDDivision." or
											        			SPLIT_STR(PositionOther1, '.', 1) = ".$IDDivision." or
											        			SPLIT_STR(PositionOther2, '.', 1) = ".$IDDivision." or
											        			SPLIT_STR(PositionOther3, '.', 1) = ".$IDDivision." 
											        		)
											        	)

											        	and StatusEmployeeID != -1
											        limit 1
											        ";
											$query=$this->db->query($sql, array())->result_array();
										}
										else
										{
											$sql = "select * from db_employees.employees
											        where ( 
											        	SPLIT_STR(PositionMain, '.', 2) = ".$user." or
											        	SPLIT_STR(PositionOther1, '.', 2) = ".$user." or
											        	SPLIT_STR(PositionOther2, '.', 2) = ".$user." or
											        	SPLIT_STR(PositionOther3, '.', 2) = ".$user." 

											        	)and StatusEmployeeID != -1
											        limit 1
											        ";
											$query=$this->db->query($sql, array())->result_array();
										}
									}
									elseif ($exDepartment[0] == 'AC') {
										if ($user == 5) { // dekan
											$ProdiID = $exDepartment[1];
											$sql = 'select b.NIP from db_academic.program_study as a 
													join db_academic.faculty as b
													on a.FacultyID = b.FacultyID
													where a.ID = '.$ProdiID.'
													';
											$_query = $this->db->query($sql, array())->result_array();
											$NIP = $_query[0]['NIP'];

											$sql = 'select * from db_employees.employees where NIP = "'.$NIP.'" and StatusEmployeeID != -1
											        limit 1 ';
											$query=$this->db->query($sql, array())->result_array();
										}
										elseif ($user == 6) {
											$ProdiID = $exDepartment[1];
											$sql = 'select a.KaprodiID from db_academic.program_study
													';
											$_query = $this->db->query($sql, array())->result_array();
											$NIP = $_query[0]['KaprodiID'];

											$sql = 'select * from db_employees.employees where NIP = "'.$NIP.'" and StatusEmployeeID != -1
											        limit 1 ';
											$query=$this->db->query($sql, array())->result_array();
										}
										else
										{
											$sql = "select * from db_employees.employees
											        where ( 
											        	SPLIT_STR(PositionMain, '.', 2) = ".$user." or
											        	SPLIT_STR(PositionOther1, '.', 2) = ".$user." or
											        	SPLIT_STR(PositionOther2, '.', 2) = ".$user." or
											        	SPLIT_STR(PositionOther3, '.', 2) = ".$user." 

											        	)and StatusEmployeeID != -1
											        limit 1
											        ";
											$query=$this->db->query($sql, array())->result_array();
										}
									}
									
								}
								else
								{
									$sql = "select * from db_employees.employees
									        where ( 
									        	SPLIT_STR(PositionMain, '.', 2) = ".$user." or
									        	SPLIT_STR(PositionOther1, '.', 2) = ".$user." or
									        	SPLIT_STR(PositionOther2, '.', 2) = ".$user." or
									        	SPLIT_STR(PositionOther3, '.', 2) = ".$user." 

									        	)and StatusEmployeeID != -1
									        limit 1
									        ";
									$query=$this->db->query($sql, array())->result_array();
								}
								
								$value = '';
								if (count($query) == 0 && !isset($query) ) {
									echo "Position User not exist";
									die();
								}
								else
								{
									$TitleBehind = ($query[0]['TitleBehind'] == '' || $query[0]['TitleBehind'] == null ) ? '' : ', '.$query[0]['TitleBehind'];
									$value = $query[0]['TitleAhead'].' '.$query[0]['Name'].$TitleBehind;
									$rs['Signature'][$i]['NameEMP'] = $value;
									$NIP = $query[0]['NIP'];
									$rs['Signature'][$i]['NIPEMP'] = $NIP;
								}

								// verify
								$img = '';
								
								$valueVerify = $verify;
								if ($verify == 1) {
									$img = './uploads/signature/'.$query[0]['Signatures'];
									
								}

								$rs['Signature'][$i]['verify'] = [
									'valueVerify' => $valueVerify,
									'img' => $img,
								];
								
								// cap
								$img = '';
								$valueCap = $cap;
								if ($cap == 1) {
									$img = './images/cap.png';
									// $img = './uploads/signature/'.$query[0]['NIP'].'_cap.png';
								}

								$rs['Signature'][$i]['cap'] = [
									'valueCap' => $valueCap,
									'img' => $img,
								];
								$rs['Signature'][$i]['number'] = $obj[$i]['number'];
								break;
							
							default:
								# code...
								break;
						}
					}
					break;
				default:
					echo 'Obj SET not exist';
					die();
					break;
			}
		}

		return $rs;
	}

	private function __getValuePolaNoSurat($method,$setting,$ID_document){
		switch ($method) {
			case 'method_default':
				// No Surat
				$Year = date('Y');
				$sql = 'select NoSuratOnly from db_generatordoc.document_data where ID_document = ?
						and Year(DateRequest) = "'.$Year.'"
						order by ID desc limit 1';
				$query = $this->db->query($sql,array($ID_document))->result_array();

				$NoSuratOnly = 1;
				$maxCharacter = 3;
				if (count($query) > 0 ) {
					$NoSuratOnly =(int)$query[0]['NoSuratOnly']+1;
				}

				$len = strlen($NoSuratOnly);
				$NoSuratStr = (string) $NoSuratOnly;
				for ($i=0; $i < $maxCharacter - $len; $i++) { 
					$NoSuratStr = '0'.$NoSuratStr;
				}

				// prefix
				$prefix = $setting['prefix'];

				// Bulan & Tahun
				$Month = date('m');
				$MonthRomawi = $this->m_master->romawiNumber($Month);
				

				$NoSuratStr = $NoSuratStr.'/'.$prefix.'/'.$MonthRomawi.'/'.$Year;
				return array(
					'NoSuratStr' => $NoSuratStr,
					'NoSuratOnly' => $NoSuratOnly,
				);
				break;
			
			default:
				echo "Method PolaNoSurat ".$method." not exist";
				die();
				break;
		}
	}
}