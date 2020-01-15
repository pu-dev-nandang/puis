<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_get extends CI_Model {

	function __construct()
	{
	    parent::__construct();
	}

	public function __generate($Props){
		$rs = [];
		foreach ($Props as $key => $value) {
			$ex = explode('.', $value);
			$func = trim($ex[0]);
			if (!method_exists($this,$ex[0])) {
				echo json_encode('Method not exist '.$ex[0]);
				die();
			}

			$rs[$ex[0]][] = $this->$ex[0]($value);
			
		}

		return $rs;
	}

	public function EMP($params){
		$rs = [];
		$ex = explode('.', $params);
		$entity = $ex[1];
		$exEntity = explode('#', $entity);
		if (count($exEntity) > 0 ) {
			switch ($exEntity[0]) {
				case 'NIP':
					$rs['Choose'] = $exEntity[0];
					$rs['user'] = [];
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

}