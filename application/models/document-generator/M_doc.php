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
		    	for ($z=0; $z < count($matches[1]); $z++) { 
    		        $str = trim($matches[1][$z]);
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

    	return $this->__preview_template($rs);
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
    			for ($z=0; $z < count($matches[1]); $z++) { 
	    		    $str = trim($matches[1][$z]);
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
		    		    				$arrKomponen = $matches[1];
		    		    				$arrValue = $rsGET[$keyRS][$setStr];
		    		    				$this->__SETWriteSignature($setStr,$TemplateProcessor,$arrKomponen,$arrValue);
		    		    				
		    		    			}
		    		    		}
	    		    			break;
	    		    		case $this->KeyUSER:
		    		    		if ($this->KeyUSER == $keyRS) {
		    		    			$setStr = trim(ucwords($ex[0]));
		    		    			$obj = $rsGET[$keyRS];
		    		    			for ($i=0; $i < count($obj); $i++) { 
		    		    				if ($setStr.'.'.$obj[$i]['field'] == $setValue) {
		    		    					$TemplateProcessor->setValue($setValue,trim($obj[$i]['value']));
		    		    					break;
		    		    				}
		    		    			}
		    		    		}
	    		    			
	    		    			break;
	    		    		case $this->KeyINPUT:
	    		    			if ($this->KeyINPUT == $keyRS) {
	    		    				$setStr = trim(ucwords($ex[0]));
	    		    				$obj = $rsGET[$keyRS];
	    		    				for ($i=0; $i < count($obj); $i++) { 
	    		    					if ($setStr.'.'.$obj[$i]['field'] == $setValue) {
	    		    						$TemplateProcessor->setValue($setValue,trim($obj[$i]['value']));
	    		    						break;
	    		    					}
	    		    				}
	    		    			}
	    		    			break;
	    		    		case $this->KeyGRAB:
	    		    			if ($this->KeyGRAB == $keyRS) {
	    		    				$setStr = trim(ucwords($ex[0]));
	    		    				$obj = $rsGET[$keyRS];
	    		    				foreach ($obj as $Objkey => $objvalue) {
	    		    					if ($Objkey == 'Date') {
	    		    						$TemplateProcessor->setValue($setValue,trim($obj[$Objkey]['value']));
	    		    						break;
	    		    					}
	    		    				}
	    		    				
	    		    			}
	    		    			break;
	    		    		case $this->KeyTABLE:
	    		    			
	    		    			break;
	    		    	}
	    		    }
    			}
    		    
    		    
    		}
    	}
    	
    	$NIPExt = $this->session->userdata('NIP').'.docx';
    	$FileName = $NIPExt;
    	$pathFolder = FCPATH."uploads\\document-generator\\template\\temp\\"; 
    	$pathFile = $pathFolder.$NIPExt; 
    	$TemplateProcessor->saveAs($pathFile,$pathFolder);
    	$convert = $this->ApiConvertDocxToPDF($pathFile,$pathFolder,$FileName);
    	return $convert;

    }

    private function convertToPDF($pathFile,$pathFolder,$FileName)
    {
    	// $strScript = '"C:\Program Files\LibreOffice\program\soffice.exe" --convert-to pdf '.$pathFile.' --outdir '.$pathFolder.' ';
     //    $result = 0;
     //    $output = system($strScript, $result);
    	// echo json_encode($output);
    	// PowerShell
    		// strScript = 'powershell .\converDocxToPdf.ps1 "C:\Users\alhadi.rahman\Documents\2018018.docx" "C:\Users\alhadi.rahman\Documents\2018018.pdf"';
    	// soffice --convert-to pdf C:\test\NPP\MBI_CONVERSION_PRESETS.docx --outdir C:\test\NPP\LOTestOutputs\

    	$this->ApiConvertDocxToPDF($pathFile,$FileName,$pathFolder);
    }

    private function ApiConvertDocxToPDF($pathFile,$pathFolder,$FileName){
    	$rs = array();
    	$headerOrigin = 'https://pcam.podomorouniversity.ac.id';
    	// $header[] = 'Content-Type: application/json';
    	$header[] = "Content-type: multipart/form-data";
    	$header[] = "Origin: ".$headerOrigin."";
    	$header[] = "Cache-Control: max-age=0";
    	$header[] = "Connection: keep-alive";
    	$header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";
    	$data = array(
    	    'auth' => 's3Cr3T-G4N',
    	);
    	$pas = md5('Uap)(*&^%');
    	$pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');
    	$url = url_DocxToPDf.'__uploadFile?apikey='.$pass;
    	$token = $this->jwt->encode($data,"UAP)(*");

    	$Input = $token;
    	$ch = curl_init();
    	$cfile = new CURLFile($pathFile, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', $FileName);
    	$post = array('token' => $Input,'file_contents[]'=>$cfile);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    	curl_setopt($ch, CURLOPT_HEADER, false);
    	curl_setopt($ch, CURLOPT_VERBOSE, false);
    	curl_setopt($ch, CURLOPT_URL,$url);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$pr = curl_exec($ch);
    	$rs = (array) json_decode($pr,true);
    	if ($rs['status'] == 1 || $rs['status'] == '1') {
    		// download file
    		$rs = $this->downloadConverter($rs,$pathFolder);
    	}
    	
    	curl_close ($ch);
    	return $rs;
    }

    private function downloadConverter($rs,$pathFolder){
    	$result = ['status' => 0,'callback' => ''];
    	//The resource that we want to download.
    	$fileUrl = url_DocxToPDf.'uploads/'.$rs['callback'];
    	 
    	//The path & filename to save to.
    	$saveTo = $pathFolder.$rs['callback'];
    	 
    	//Open file handler.
    	$fp = fopen($saveTo, 'w+');
    	 
    	//If $fp is FALSE, something went wrong.
    	if($fp === false){
    	    throw new Exception('Could not open: ' . $saveTo);
    	}
    	 
    	//Create a cURL handle.
    	$ch = curl_init($fileUrl);
    	 
    	//Pass our file handle to cURL.
    	curl_setopt($ch, CURLOPT_FILE, $fp);
    	 
    	//Timeout if the file doesn't download after 20 seconds.
    	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    	 
    	//Execute the request.
    	curl_exec($ch);
    	 
    	//If there was an error, throw an Exception
    	if(curl_errno($ch)){
    	    throw new Exception(curl_error($ch));
    	}
    	 
    	//Get the HTTP status code.
    	$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	 
    	//Close the cURL handler.
    	curl_close($ch);
    	 
    	//Close the file handler.
    	fclose($fp);
    	 
    	if($statusCode == 200){
    		$this->deleteFileConverter();
    		$result['status'] = 1;
    		$result['callback'] = base_url().'uploads/document-generator/template/temp/'.$rs['callback'];
    	}
    	return $result; 
    }

    private function deleteFileConverter(){
    	$array =[$this->session->userdata('NIP').'.docx',$this->session->userdata('NIP').'.pdf'];
    	$result = [];
    	$pas = md5('Uap)(*&^%');
    	$pass = sha1('jksdhf832746aiH{}{()&(*&(*'.$pas.'HdfevgyDDw{}{}{;;*766&*&*');
    	$headerOrigin = 'https://pcam.podomorouniversity.ac.id';
    	for ($i=0; $i < count($array); $i++) { 
    		$rs = array();
    		$header = [];
    		$header[] = "Content-type: multipart/form-data";
    		$header[] = "Origin: ".$headerOrigin."";
    		$header[] = "Cache-Control: max-age=0";
    		$header[] = "Connection: keep-alive";
    		$header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";
    		$data = array(
    		    'auth' => 's3Cr3T-G4N',
    		    'path' => $array[$i],
    		);
    		$url = url_DocxToPDf.'__deleteFile?apikey='.$pass;
    		$token = $this->jwt->encode($data,"UAP)(*");
    		$Input = $token;
    		$ch = curl_init();
    		$new_post_array  = ['token' => $Input];
    		$post = $new_post_array;
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    		curl_setopt($ch, CURLOPT_HEADER, false);
    		curl_setopt($ch, CURLOPT_VERBOSE, false);
    		curl_setopt($ch, CURLOPT_URL,$url);
    		curl_setopt($ch, CURLOPT_POST, 1);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		$pr = curl_exec($ch);
    		$rs = (array) json_decode($pr,true);
    		curl_close ($ch);
    		$result[] = $rs;
    	}

    	return $result;
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

    private function __SETWriteSignature($setStr,$TemplateProcessor,$arrKomponen,$arrValue){
    	// print_r($arrValue);die();
    	for ($i=0; $i < count($arrValue); $i++) { 
    		$keyApproval = $i + 1;
    		// show signature image or not
    		if ($arrValue[$i]['verify']['valueVerify'] == 1) {
    			$img = $arrValue[$i]['verify']['img'];
    			for ($j=0; $j < count($arrKomponen); $j++) {
    				$str = $arrKomponen[$j]; 
    				$ex = explode('.', $str);
    				if ($ex[0] == 'Signature') {
    					$key2 = $ex[1];
    					$exKey2 = explode('#', $key2);
    					if (count($exKey2)) {
    						switch ($exKey2[0]) {
    							case 'Image':
    								$setValue = $ex[0].'.'.$exKey2[0].'#'.$keyApproval;
    								$TemplateProcessor->setImageValue($setValue, 
    									array('path' => $img, 
    										// 'width' => 200, 
    										'height' => 300, 
    										'ratio' => true,

    									),'behind'
    								);
    								break;
    							
    							default:
    								
    								break;
    						}
    					}
    					else
    					{
    						echo 'Approval number not set';
    						die();
    					}
    					
    				}
    			}
    		}
    		elseif ($arrValue[$i]['verify']['valueVerify'] == 0) {
    			for ($j=0; $j < count($arrKomponen); $j++) {
    				$str = $arrKomponen[$j]; 
    				$ex = explode('.', $str);
    				if ($ex[0] == 'Signature') {
    					$key2 = $ex[1];
    					$exKey2 = explode('#', $key2);
    					if (count($exKey2)) {
    						switch ($exKey2[0]) {
    							case 'Image':
    								$setValue = $ex[0].'.'.$exKey2[0].'#'.$keyApproval;
    								$TemplateProcessor->setValue($setValue,'');
    								break;
    							
    							default:
    								
    								break;
    						}
    					}
    					else
    					{
    						echo 'Approval number not set';
    						die();
    					}
    					
    				}
    			}
    		}

    		// show cap image or not
    		if ($arrValue[$i]['cap']['valueCap'] == 1) {
    			$img = $arrValue[$i]['cap']['img'];

    			for ($j=0; $j < count($arrKomponen); $j++) {
    				$str = $arrKomponen[$j]; 
    				$ex = explode('.', $str);
    				if ($ex[0] == 'Signature') {
    					$key2 = $ex[1];
    					$exKey2 = explode('#', $key2);
    					if (count($exKey2)) {
    						switch ($exKey2[0]) {
    							case 'Cap':
    								$setValue = $ex[0].'.'.$exKey2[0].'#'.$keyApproval;
    								$TemplateProcessor->setImageValue($setValue, array(
    										'path' => $img, 
    										// 'width' => 200, 
    										'height' => 300,
    										'ratio' => true,
    									),'behind'
    							);
    								break;
    							
    							default:
    								
    								break;
    						}
    					}
    					else
    					{
    						echo 'Approval number not set';
    						die();
    					}
    					
    				}
    			}
    		}
    		elseif ($arrValue[$i]['cap']['valueCap'] == 0) {
    			for ($j=0; $j < count($arrKomponen); $j++) {
    				$str = $arrKomponen[$j]; 
    				$ex = explode('.', $str);
    				if ($ex[0] == 'Signature') {
    					$key2 = $ex[1];
    					$exKey2 = explode('#', $key2);
    					if (count($exKey2)) {
    						switch ($exKey2[0]) {
    							case 'Cap':
    								$setValue = $ex[0].'.'.$exKey2[0].'#'.$keyApproval;
    								$TemplateProcessor->setValue($setValue,'');
    								break;
    							
    							default:
    								
    								break;
    						}
    					}
    					else
    					{
    						echo 'Approval number not set';
    						die();
    					}
    					
    				}
    			}
    		}

    		// write name di arrKomponen key ke 2
    		if (array_key_exists(2, $arrKomponen)) {
    			$setValue = $arrKomponen[2];
    			$TemplateProcessor->setValue($setValue,$arrValue[$i]['NameEMP']);
    		}

    		// write name di arrKomponen key ke 3
    		if (array_key_exists(3, $arrKomponen)) {
    			$setValue = $arrKomponen[3];
    			$TemplateProcessor->setValue($setValue,$arrValue[$i]['NIPEMP']);
    		}
    		
    	}
    	
    }

    public function save_template($Input){
    	$rs = ['status' => 0,'callback' => ''];
    	$DocumentName = $Input['DocumentName'];
    	$DocumentAlias = $Input['DocumentAlias'];
    	$Config = json_encode($Input['settingTemplate']);
    	// upload file template
    	$PathTemplate = $this->upload_file_template($DocumentName);
    	$dataSave = [
    		'DocumentName' => $DocumentName,
    		'DocumentAlias' => $DocumentAlias,
    		'Config' => $Config,
    		'PathTemplate' => $PathTemplate,
    		'UpdatedBy' => $this->session->userdata('NIP'),
    		'UpdatedAt' => date('Y-m-d H:i:s'),
    	];
    	$this->db->insert('db_generatordoc.document',$dataSave);
    	$rs['status'] = 1;
    	return $rs;
    }

    private function upload_file_template($DocumentName){
    	$filename = str_replace(' ', '_', $DocumentName);
    	$varFiles = 'PathTemplate';
    	$file = $this->m_master->uploadDokumenMultiple($filename,$varFiles,'./uploads/document-generator/template');
    	return $file[0];
    }
    
  
}
