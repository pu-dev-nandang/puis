<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH.'vendor/autoload.php';
class M_doc extends CI_Model {
	private $SET = [];
	private $USER = [];
	private $INPUT = [];
	private $GRAB = [];
	private $TABLE = [];
	private $TOT_APPROVAL = 0;

	private $KeySET = 'SET';
	private $KeyUSER = 'USER';
	private $KeyINPUT = 'INPUT';
	private $KeyGRAB = 'GRAB';
	private $KeyTABLE = 'TABLE';

	// private $Path

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->library('JWT');
    }

    public function readTemplate(){
		$generatedFile    = $_FILES['PathTemplate']['tmp_name'][0];
		$sourceZip = new \ZipArchive();
		$sourceZip->open($generatedFile);
		$sourceDocument = $sourceZip->getFromName('word/document.xml');
		$sourceDom      = new DOMDocument();
		$sourceDom->loadXML($sourceDocument);
		$sourceXPath = new \DOMXPath($sourceDom);
		$sourceXPath->registerNamespace("w", "http://schemas.openxmlformats.org/wordprocessingml/2006/main");
		$sourceNodes = $sourceXPath->query('//w:document/w:body/*[not(self::w:sectPr)]');
		$line = [];
		foreach ($sourceNodes as $entry) {
		    $line[]=$entry->nodeValue;
		}

		foreach ($line as $v) {
		    if(preg_match_all('/{+(.*?)}/', $v, $matches)){
		        $str = trim($matches[1][0]);
		        $ex = explode('.', $str);
		        if (count($ex) > 0) {
		        	/*
						array key 1 : value
						array key > 1 : get field
						Max key 2
	
		        	*/
		        	switch ($ex[0]) {
		        		case $this->KeySET:
		        			$setStr = trim(ucwords($ex[1]));
		        			for ($i=2; $i < count($ex); $i++) {
		        				if (trim(ucwords($ex[$i])) == 'Signature') {
		        					$this->TOT_APPROVAL = $this->TOT_APPROVAL + 1;
		        				}
		        				$setStr .= '.'.trim(ucwords($ex[$i]));
		        			}
		        			
		        			$this->SET[] = $setStr;
		        			break;
		        		case $this->KeyUSER:
		        			$setStr = trim(ucwords($ex[1]));
		        			for ($i=2; $i < count($ex); $i++) {
		        				$setStr .= '.'.trim(ucwords($ex[$i]));
		        			}
		        			$this->USER[] = $setStr;
		        			break;
		        		case $this->KeyINPUT:
		        			$setStr = trim(ucwords($ex[1]));
		        			for ($i=2; $i < count($ex); $i++) {
		        				$setStr .= '.'.trim(ucwords($ex[$i]));
		        			}
		        			$this->INPUT[] = $setStr;
		        			break;
		        		case $this->KeyGRAB:
		        			$setStr = trim(ucwords($ex[1]));
		        			for ($i=2; $i < count($ex); $i++) {
		        				$setStr .= '.'.trim(ucwords($ex[$i]));
		        			}
		        			$this->GRAB[] = $setStr;
		        			break;
		        		case $this->KeyTABLE:
		        			$setStr = trim(ucwords($ex[1]));
		        			for ($i=2; $i < count($ex); $i++) {
		        				$setStr .= '.'.trim(ucwords($ex[$i]));
		        			}
		        			$this->TABLE[] = $setStr;
		        			break;
		        	}
		        }
		    }
		}

		// extract set to function
		$this->__generator_obj();

		return $data = [
			'SET' => $this->SET,
			'USER' => $this->USER,
			'INPUT' => $this->INPUT,
			'GRAB' => $this->GRAB,
			'TABLE' => $this->TABLE,
			'TOT_APPROVAL' => $this->TOT_APPROVAL,
		];	

    }

    private function __generator_obj(){
    	$this->__SETGenerate();
    }

    private function __SETGenerate(){
    	$this->load->model('document-generator/m_set');
		$SET = $this->SET;
		$this->SET = $this->m_set->__generate($SET);
    }
    
  
}
