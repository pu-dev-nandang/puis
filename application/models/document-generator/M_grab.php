<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_grab extends CI_Model {

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

			$rs[$ex[0]] = $this->$ex[0]($value);
			
		}

		return $rs;
	}

	private function Date($params){
		$rs = [];
		$ex = explode('.', $params);
		$entity = $ex[1];
		switch ($entity) {
			case 'Date':
				$rs['Choose'] = $entity;
				$rs['select'] = [
					[
						'ID' => 'Indonesia',
						'Value' => 'Indonesia',
					],
					[
						'ID' => 'English',
						'Value' => 'English',
					],
				];
				$rs['user'] = '';
				break;
			default:
				# code...
				break;
		}

		return $rs;
	}

	public function preview_template($getObjInput){
		$rs = [];
		foreach ($getObjInput as $key => $value) {
			switch ($key) {
				case 'Date':
					$obj = $getObjInput[$key];
					$method = $obj['user'];
					$Date = $this->__getDateMethod($method);
					$rs['Date'] = ['value' => $Date]; 
					break;
				
				default:
					echo 'Obj DATE not exist';
					die();
					break;
			}
		}

		return $rs;
	}

	private function __getDateMethod($method){
		$date = date('Y-m-d');
		if ($method == 'Indonesia') {
			return $this->m_master->getDateIndonesian($date);
		}
		elseif ($method == 'English') {
			return date('d M Y', strtotime($date));
		}
	}

}