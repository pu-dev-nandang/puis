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

	// public function PolaNoSurat($params){
	// 	// example :  041/UAP/R/SKU/X/2019
	// 	$ex = explode('.', $params);
	// 	if (count($ex) > 0 && count($ex) == 1 ) {
	// 		return array(
	// 			'value' => 'method_default',
	// 			'setting' => array(
	// 				'prefix' => '',
	// 			),
	// 			'sample' => '041/UAP/R/SKU/X/2019',
	// 		);
	// 	}

	// }

	public function PolaNoSurat($params){
		// example :  041/UAP/R/SKU/X/2019
		$ex = explode('.', $params);
		$sql = 'select * from db_generatordoc.category_document where Active = 1';
		$query = $this->db->query($sql,array())->result_array();
		if (count($ex) > 0 && count($ex) == 1 ) {
			return array(
				'value' => 'method_default',
				'setting' => array(
					'prefix' => '',
				),
				'sample' => '041/UAP/R/SKU/X/2019',
				'choose' => $query,
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
					$sql = 'select NIP as ID,Name as Value from db_employees.employees where StatusEmployeeID != -1';
					$query = $this->db->query($sql,array())->result_array();
					$rs['Choose'] = $exEntity[0];
					$rs['select'] = $query;
					$rs['user'] = '';
					$rs['verify'] = '';
					$rs['cap'] = '';
					$rs['number'] = $exEntity[1];
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

	public function preview_template($getObjInput,$ID_document='',$DepartmentID='',$TagStr=[]){
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
									$DataUser = [];
									for ($z=0; $z < count($TagStr)-1; $z++) { // last data adalah data GET
										$ArrTagExplode = explode('.', $TagStr[$z]) ;
										if (count($ArrTagExplode) == 6) {
											// ambil array key 3 untuk menentukan GET atau USER
											if ($ArrTagExplode[3] == 'GET') {
												if ($ArrTagExplode[4] == 'EMP') { // EMP or MHS
													$Arr5 = $ArrTagExplode[5];
													$Arr5Ex =  explode('#', $Arr5); // NIP#1
													$getNumber = $Arr5Ex[1];
													$ArrgetNumberAppTag = explode('#', $ArrTagExplode[2]); // Position#1
													// print_r($ArrgetNumberAppTag);
													if ($Arr5Ex[0] == 'NIP' && $ArrgetNumberAppTag[1] == $obj[$i]['number'] ) { // number
														// ambil data GET
														$GET = $TagStr[count($TagStr)-1];
														if (!array_key_exists('EMP', $GET)) {
															echo "Variable GET not defined";die();
														}
														
														// get by getNumber
														$EMP  = $GET['EMP'];
														for ($x=0; $x < count($EMP); $x++) { 
															if ($getNumber == $EMP[$x]['number']) {
																$DataUser = $EMP[$x]['user'];
																break;
															}
														}

														break;

													}
												}
												elseif ($ArrTagExplode[4] == 'MHS') {
													$Arr5 = $ArrTagExplode[5];
													$Arr5Ex =  explode('#', $Arr5); //  NPM#1
													$getNumber = $Arr5Ex[1];
													$ArrgetNumberAppTag = explode('#', $ArrTagExplode[2]); // Position#1
													// print_r($ArrgetNumberAppTag);
													if ($Arr5Ex[0] == 'NPM' && $ArrgetNumberAppTag[1] == $obj[$i]['number'] ) { // number
														// ambil data GET
														$GET = $TagStr[count($TagStr)-1];
														if (!array_key_exists('MHS', $GET)) {
															echo "Variable GET not defined";die();
														}
														
														// get by getNumber
														$MHS  = $GET['MHS'];
														for ($x=0; $x < count($MHS); $x++) { 
															if ($getNumber == $MHS[$x]['number']) {
																$DataUser = $MHS[$x]['user'];
																break;
															}
														}

														break;

													}
												}
											}
											elseif ($ArrTagExplode[3] == 'USER') {
												$NIPSess = $this->session->userdata('NIP');
												$G_dt = $this->m_master->caribasedprimary('db_employees.employees','NIP',$NIPSess);
												$DataUser = $G_dt[0];
												$PosMain = explode('.', $DataUser['PositionMain']);
												$PosMain[0] = $IDDivision;
												$DataUser['PositionMain'] = implode('.', $PosMain);
											}
										}
									}
									if ($exDepartment[0] == 'NA') {
										if ($user == 10 || $user == 11 || $user == 12) {
											$PosMain = explode('.', $DataUser['PositionMain']);
											$DivisionID = $PosMain[0];

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
											        			SPLIT_STR(PositionMain, '.', 1) = ".$DivisionID." or
											        			SPLIT_STR(PositionOther1, '.', 1) = ".$DivisionID." or
											        			SPLIT_STR(PositionOther2, '.', 1) = ".$DivisionID." or
											        			SPLIT_STR(PositionOther3, '.', 1) = ".$DivisionID." 
											        		)
											        	)

											        	and StatusEmployeeID != -1
											        limit 1
											        ";
											$query=$this->db->query($sql, array())->result_array();
										}
										elseif ($user == 5) { // dekan
											$ProdiID = ($ArrTagExplode[3] == 'USER') ? $exDepartment[1] : $DataUser['ProdiID'];
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
											$ProdiID = ($ArrTagExplode[3] == 'USER') ? $exDepartment[1] : $DataUser['ProdiID'];
											$sql = 'select a.KaprodiID from db_academic.program_study as a where ID = '.$ProdiID.'
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
									elseif ($exDepartment[0] == 'AC') {
										if ($user == 5) { // dekan
											$ProdiID = ($ArrTagExplode[3] == 'USER') ? $exDepartment[1] : $DataUser['ProdiID'];
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
											$ProdiID = ($ArrTagExplode[3] == 'USER') ? $exDepartment[1] : $DataUser['ProdiID'];
											$sql = 'select a.KaprodiID from db_academic.program_study as a where ID = '.$ProdiID.'
													';
											$_query = $this->db->query($sql, array())->result_array();
											$NIP = $_query[0]['KaprodiID'];

											$sql = 'select * from db_employees.employees where NIP = "'.$NIP.'" and StatusEmployeeID != -1
											        limit 1 ';
											$query=$this->db->query($sql, array())->result_array();
										}
										elseif ($user == 10 || $user == 11 || $user == 12) {
											$PosMain = explode('.', $DataUser['PositionMain']);
											$DivisionID = $PosMain[0];

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
											        			SPLIT_STR(PositionMain, '.', 1) = ".$DivisionID." or
											        			SPLIT_STR(PositionOther1, '.', 1) = ".$DivisionID." or
											        			SPLIT_STR(PositionOther2, '.', 1) = ".$DivisionID." or
											        			SPLIT_STR(PositionOther3, '.', 1) = ".$DivisionID." 
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
									else
									{
										// faculty
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
								else
								{

									if ($user > 4) {  // dibawah warek
										$DataUser = [];
										for ($z=0; $z < count($TagStr)-1; $z++) { // last data adalah data GET
											$ArrTagExplode = explode('.', $TagStr[$z]) ;
											if (count($ArrTagExplode) == 6) {
												// ambil array key 3 untuk menentukan GET atau USER
												if ($ArrTagExplode[3] == 'GET') {
													if ($ArrTagExplode[4] == 'EMP') { // EMP or MHS
														$Arr5 = $ArrTagExplode[5];
														$Arr5Ex =  explode('#', $Arr5); // NIP#1
														$getNumber = $Arr5Ex[1];
														$ArrgetNumberAppTag = explode('#', $ArrTagExplode[2]); // Position#1
														// print_r($ArrgetNumberAppTag);
														if ($Arr5Ex[0] == 'NIP' && $ArrgetNumberAppTag[1] == $obj[$i]['number'] ) { // number
															// ambil data GET
															$GET = $TagStr[count($TagStr)-1];
															if (!array_key_exists('EMP', $GET)) {
																echo "Variable GET not defined";die();
															}
															
															// get by getNumber
															$EMP  = $GET['EMP'];
															for ($x=0; $x < count($EMP); $x++) { 
																if ($getNumber == $EMP[$x]['number']) {
																	$DataUser = $EMP[$x]['user'];
																	break;
																}
															}

															break;

														}
													}
													elseif ($ArrTagExplode[4] == 'MHS') {
														$Arr5 = $ArrTagExplode[5];
														$Arr5Ex =  explode('#', $Arr5); //  NPM#1
														$getNumber = $Arr5Ex[1];
														$ArrgetNumberAppTag = explode('#', $ArrTagExplode[2]); // Position#1
														// print_r($ArrgetNumberAppTag);
														if ($Arr5Ex[0] == 'NPM' && $ArrgetNumberAppTag[1] == $obj[$i]['number'] ) { // number
															// ambil data GET
															$GET = $TagStr[count($TagStr)-1];
															if (!array_key_exists('MHS', $GET)) {
																echo "Variable GET not defined";die();
															}
															
															// get by getNumber
															$MHS  = $GET['MHS'];
															for ($x=0; $x < count($MHS); $x++) { 
																if ($getNumber == $MHS[$x]['number']) {
																	$DataUser = $MHS[$x]['user'];
																	break;
																}
															}

															break;

														}
													}
												}
												elseif ($ArrTagExplode[3] == 'USER') {
													$NIPSess = $this->session->userdata('NIP');
													$G_dt = $this->m_master->caribasedprimary('db_employees.employees','NIP',$NIPSess);
													$DataUser = $G_dt[0];
												}
											}
										}

										if ($user == 5) { // dekan
											$ProdiID = $DataUser['ProdiID'];
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
											$ProdiID = $DataUser['ProdiID'];
											$sql = 'select a.KaprodiID from db_academic.program_study as a where ID = '.$ProdiID.'
													';
											$_query = $this->db->query($sql, array())->result_array();
											$NIP = $_query[0]['KaprodiID'];
											$sql = 'select * from db_employees.employees where NIP = "'.$NIP.'" and StatusEmployeeID != -1
											        limit 1 ';
											$query=$this->db->query($sql, array())->result_array();
										}
										elseif ($user == 10 || $user == 11 || $user == 12) {
											$PosMain = explode('.', $DataUser['PositionMain']);
											$DivisionID = $PosMain[0];
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
											        			SPLIT_STR(PositionMain, '.', 1) = ".$DivisionID." or
											        			SPLIT_STR(PositionOther1, '.', 1) = ".$DivisionID." or
											        			SPLIT_STR(PositionOther2, '.', 1) = ".$DivisionID." or
											        			SPLIT_STR(PositionOther3, '.', 1) = ".$DivisionID." 
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
							
							case 'NIP':
								$sql = "select * from db_employees.employees
								        where NIP = '".$user."'
								        limit 1
								        ";
								$query=$this->db->query($sql, array())->result_array();
								
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
				// get Category document first
				$NoSuratOnly = 1;
				$G_dt = $this->m_master->caribasedprimary('db_generatordoc.document','ID',$ID_document);
				// prefix
				$prefix = $setting['prefix'];
				$Year = date('Y');
				if (count($G_dt) > 0) {
					$ID_category_document = $G_dt[0]['ID_category_document'];
					$sql = 'select a.NoSuratOnly from db_generatordoc.document_data as a 
							join db_generatordoc.document as b on a.ID_document = b.ID
							join db_generatordoc.category_document as c on b.ID_category_document = c.ID
							where c.ID = ?
							and Year(a.DateRequest) = "'.$Year.'"
							order by a.ID desc limit 1';
					$query = $this->db->query($sql,array($ID_category_document))->result_array();
					if (count($query) > 0 ) {
						$NoSuratOnly =(int)$query[0]['NoSuratOnly']+1;
					}

					// prefix ambil dari category
					$G_dt_category = $this->m_master->caribasedprimary('db_generatordoc.category_document','ID',$ID_category_document);
					$Config = json_decode($G_dt_category[0]['Config'],true) ;
					$prefix = $Config['SET']['PolaNoSurat']['setting']['prefix'];
				}


				// No Surat
				$maxCharacter = 3;

				$len = strlen($NoSuratOnly);
				$NoSuratStr = (string) $NoSuratOnly;
				for ($i=0; $i < $maxCharacter - $len; $i++) { 
					$NoSuratStr = '0'.$NoSuratStr;
				}



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