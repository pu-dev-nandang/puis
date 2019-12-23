<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH.'vendor/autoload.php';
class M_doc extends CI_Model {
	private $SET = [];
	private $USER = [];
	private $INPUT = [];
	private $GRAB = [];
	private $TABLE = [];
	private $DOCUMENT = [];

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

    private function __readDoc($FileTemplate){
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

    	return $line;
    }

    public function readTemplate(){
    	$FileTemplate    = $_FILES['PathTemplate']['tmp_name'][0];
		$line = $this->__readDoc($FileTemplate);

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
		        			// if ($setStr == 'Signature') {
		        			// 	$this->TOT_APPROVAL = $this->TOT_APPROVAL + 1;
		        			// }
		        			for ($i=2; $i < count($ex); $i++) {
		        				
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
			'DOCUMENT' => $this->DOCUMENT,
		];	

    }

    private function __generator_obj(){
    	$this->__SETGenerate();
    	$this->__GRABGenerate();
    }

    private function __SETGenerate(){
    	$this->load->model('document-generator/m_set');
		$SET = $this->SET;
		$this->SET = $this->m_set->__generate($SET);
    }

    private function __GRABGenerate(){
    	$this->load->model('document-generator/m_grab');
		$GRAB = $this->GRAB;
		$this->GRAB = $this->m_grab->__generate($GRAB);
    }

    public function preview_template($Input){
    	$this->load->model('document-generator/m_set');
    	$this->load->model('document-generator/m_user');
    	$this->load->model('document-generator/m_grab');
    	$rs = [];
    	$FileTemplate    = $_FILES['PathTemplate']['tmp_name'][0];
		$line = $this->__readDoc($FileTemplate);
    	$Filtering = [];

    	foreach ($line as $v) {
    	    if(preg_match_all('/{+(.*?)}/', $v, $matches)){
    	        $str = trim($matches[1][0]);
    	        $ex = explode('.', $str);
    	        if (count($ex) > 0) {
    	        	switch ($ex[0]) {
    	        		case $this->KeySET:
    	        			
    	        			if (!in_array($ex[0], $Filtering)){
    	        				$NameObj = $ex[0];
    	        				$getObjInput = $this->__getObjInput($Input,$NameObj);
    	        				$callback_data = $this->m_set->preview_template($getObjInput);
    	        				// print_r($callback_data);
    	        				$rs[$this->KeySET] = $callback_data;
    	        			}
    	        			else
    	        			{
    	        				continue;
    	        			}

    	        			break;
    	        		case $this->KeyUSER:
    	        			if (!in_array($ex[0], $Filtering)){
    	        				$NameObj = $ex[0];
    	        				$getObjInput = $this->__getObjInput($Input,$NameObj);
    	        				$rs[$this->KeyUSER] = $this->m_user->preview_template($getObjInput);
    	        			}
    	        			else
    	        			{
    	        				continue;
    	        			}
    	        			break;
    	        		case $this->KeyINPUT:
    	        			if (!in_array($ex[0], $Filtering)){
    	        				$NameObj = $ex[0];
    	        				$getObjInput = $this->__getObjInput($Input,$NameObj);
    	        				$rs[$this->KeyINPUT] = $getObjInput;
    	        			}
    	        			else
    	        			{
    	        				continue;
    	        			}
    	        			break;
    	        		case $this->KeyGRAB:
    	        			if (!in_array($ex[0], $Filtering)){
    	        				$NameObj = $ex[0];
    	        				$getObjInput = $this->__getObjInput($Input,$NameObj);
    	        				$rs[$this->KeyGRAB] = $this->m_grab->preview_template($getObjInput);
    	        			}
    	        			else
    	        			{
    	        				continue;
    	        			}
    	        			
    	        			break;
    	        		case $this->KeyTABLE:
    	        			if (!in_array($ex[0], $Filtering)){
    	        				
    	        			}
    	        			else
    	        			{
    	        				continue;
    	        			}
    	        			break;
    	        	}

    	        	$Filtering[] = $ex[0];
    	        }
    	    }
    	}

    	$this->__preview_template($rs);
    }

    private function getBodyBlock($string){
        if (preg_match('%(?i)(?<=<w:body>)[\s|\S]*?(?=</w:body>)%', $string, $regs)) {
            return $regs[0];
        } else {
            return '';
        }
    }

    private function __preview_template($rsGET){
    	$FileTemplate    = $_FILES['PathTemplate']['tmp_name'][0];
		$line = $this->__readDoc($FileTemplate);
    	$TemplateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($FileTemplate);

    	foreach ($line as $v) {
    		if(preg_match_all('/{+(.*?)}/', $v, $matches)){
    		    $str = trim($matches[1][0]);
    		    $ex = explode('.', $str);
    		    $setValue = $str;
    		    // print_r($str.'<br/>');
    		    foreach ($rsGET as $keyRS => $valueRS) {
    		    	switch ($ex[0]) {
    		    		case $this->KeySET:
	    		    		if ($this->KeySET == $keyRS) {
	    		    			$setStr = trim(ucwords($ex[1]));
	    		    			if ($setStr == 'PolaNoSurat') {
	    		    				$TemplateProcessor->setValue($setValue,trim($rsGET[$keyRS][$setStr]['NoSuratStr']) );
	    		    			}
	    		    			elseif ($setStr == 'Signature') {
	    		    				// cek verify dan cap
	    		    				if ($rsGET[$keyRS][$setStr]['verify']['valueVerify'] == 1) {
	    		    					$img = $rsGET[$keyRS][$setStr]['verify']['img'];
	    		    					$phpWord = new \PhpOffice\PhpWord\PhpWord();
	    		    					$section = $phpWord->addSection();
	    		    					$section->addImage($img,array(
	    		    					                    'width'         => 100,
	    		    					                    'height'        => 100,
	    		    					                    // 'marginTop'     => -1,
	    		    					                    // 'marginLeft'    => -1,
	    		    					                    'wrappingStyle' => 'inline'
	    		    					                                    )       
	    		    					                    );
	    		    					$section->addText($rsGET[$keyRS][$setStr]['NameEMP']);
	    		    					$objWriter =  \PhpOffice\PhpWord\IOFactory::createWriter($phpWord);
	    		    					$fullXml = $objWriter->getWriterPart('Document')->write();
	    		    					$TemplateProcessor->setValue($setValue,$this->getBodyBlock($fullXml)  );
	    		    					// $TemplateProcessor->setImageValue($setValue, array('path' => $img, 'width' => 100, 'height' => 100, 'ratio' => false));
	    		    				}
	    		    				
	    		    			}
	    		    		}
    		    			break;
    		    		case $this->KeyUSER:
    		    			
    		    			break;
    		    		case $this->KeyINPUT:
    		    			
    		    			break;
    		    		case $this->KeyGRAB:
    		    			
    		    			break;
    		    		case $this->KeyTABLE:
    		    			
    		    			break;
    		    	}
    		    }
    		    
    		}
    	}

    	$NIPExt = $this->session->userdata('NIP').'.docx';
    	$TemplateProcessor->saveAs('./uploads/document-generator/template/temp/'.$NIPExt);

    }

    public function __getObjInput($Input,$NameObj){
    	$rs = [];
    	$bool = false;
    	foreach ($Input as $key => $value) {
    		if ($key == $NameObj) {
    			$rs = $value;
    			$bool = true;
    			break;
    		}
    	}

    	if (!$bool) {
    		echo "Obj not exist";
    		die();
    	}

    	return $rs;
    }
    
  
}
