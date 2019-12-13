<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH.'vendor/autoload.php';
class M_doc extends CI_Model {
	private $SET = [];
	private $USER = [];
	private $INPUT = [];
	private $GRAB = [];
	private $TABLE = [];

	private $KeySET = 'SET';
	private $KeyUSER = 'USER';
	private $KeyINPUT = 'INPUT';
	private $KeyGRAB = 'GRAB';
	private $KeyTABLE = 'TABLE';

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
		        $str = $matches[1][0];
		        $ex = explode('.', $str);
		        if (count($ex) > 0) {
		        	/*
						array key 1 : value
						array key > 1 : get field
						Max key 2
	
		        	*/
		        	switch ($ex[0]) {
		        		case $this->KeySET:
		        			$setStr = $ex[1];
		        			for ($i=2; $i < count($ex); $i++) {
		        				$setStr .= '.'.$ex[$i];
		        			}
		        			
		        			$this->SET[] = $setStr;
		        			break;
		        		case $this->KeyUSER:
		        			$setStr = $ex[1];
		        			for ($i=2; $i < count($ex); $i++) {
		        				$setStr .= '.'.$ex[$i];
		        			}
		        			$this->USER[] = $setStr;
		        			break;
		        		case $this->KeyINPUT:
		        			$setStr = $ex[1];
		        			for ($i=2; $i < count($ex); $i++) {
		        				$setStr .= '.'.$ex[$i];
		        			}
		        			$this->INPUT[] = $setStr;
		        			break;
		        		case $this->KeyGRAB:
		        			$setStr = $ex[1];
		        			for ($i=2; $i < count($ex); $i++) {
		        				$setStr .= '.'.$ex[$i];
		        			}
		        			$this->GRAB[] = $setStr;
		        			break;
		        		case $this->KeyTABLE:
		        			$setStr = $ex[1];
		        			for ($i=2; $i < count($ex); $i++) {
		        				$setStr .= '.'.$ex[$i];
		        			}
		        			$this->TABLE[] = $setStr;
		        			break;
		        	}
		        }
		    }
		}

		return $data = [
			'SET' => $this->SET,
			'USER' => $this->USER,
			'INPUT' => $this->INPUT,
			'GRAB' => $this->GRAB,
			'TABLE' => $this->TABLE,
		];	

    }

    
  
}
