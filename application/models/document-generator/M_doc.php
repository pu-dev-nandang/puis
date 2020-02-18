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
	private $GET = [];

	private $KeySET = 'SET';
	private $KeyUSER = 'USER';
	private $KeyINPUT = 'INPUT';
	private $KeyGRAB = 'GRAB';
    private $KeyTABLE = 'TBL';
	private $KeyGET = 'GET';

	// private $Path

    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->library('JWT');
    }

    private function __readDoc($FileTemplate){
    	// $generatedFile    = $_FILES['PathTemplate']['tmp_name'][0];
    	$generatedFile    = $FileTemplate;
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
    		        			$this->TABLE['KEY'][] = $setStr;
    		        			break;
                            case $this->KeyGET:
                                $setStr = trim(ucwords($ex[1]));
                                for ($i=2; $i < count($ex); $i++) {
                                    $setStr .= '.'.trim(ucwords($ex[$i]));
                                }
                                $this->GET[] = $setStr;
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
			'GET' => $this->GET,
		];	

    }

    private function __generator_obj(){
    	$this->__SETGenerate();
        $this->__GRABGenerate();
        $this->__TABLEGenerate();
    	$this->__GETGenerate();
    }

    private function __GETGenerate(){
        $GET = $this->GET;
        if (count($GET) > 0) {
            $this->load->model('document-generator/m_get');
            $this->GET = $this->m_get->__generate($GET);
        }
    }

    private function __TABLEGenerate(){
        $this->load->model('document-generator/m_table');
        $TABLE = $this->TABLE;
        if (array_key_exists('KEY', $TABLE)) {
             $this->TABLE = $this->m_table->__generate($TABLE);
        }
       
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

    private function __TagStrSignature($line){

        $str = 'SET.Signature.Position';
        //$bool = false; 
        $ArrRS = [];
        foreach ($line as $v) {
            if(preg_match_all('/{+(.*?)}/', $v, $matches)){
                // print_r($matches);
                for ($i=0; $i < count($matches); $i++) { 
                    $arr = $matches[$i];
                    // print_r($arr);
                    for ($j=0; $j < count($arr); $j++) { 
                        if (strpos($arr[$j], $str) !== false) {
                            $strRS = $arr[$j];
                            $strRS = str_replace("{","",$strRS);
                            $strRS = str_replace("}","",$strRS);
                            if (!in_array($strRS, $ArrRS)){
                                $ArrRS[] = $strRS;
                            }    
                        }
                    }
                    
                }
                
            }
        }
        
        $rs = $ArrRS;
        return $rs;
    }

    public function preview_template($Input){
    	$this->load->model('document-generator/m_set');
    	$this->load->model('document-generator/m_user');
        $this->load->model('document-generator/m_grab');
    	$this->load->model('document-generator/m_get');
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
                        case $this->KeyGET:
                            if (!in_array($ex[0], $Filtering)){
                                $NameObj = $ex[0];
                                $getObjInput = $this->__getObjInput($Input,$NameObj);
                                $rs[$this->KeyGET] = $this->m_get->preview_template($getObjInput);
                            }
                            else
                            {
                                continue;
                            }
                            
                            break;
    	        		case $this->KeySET:
    	        			if (!in_array($ex[0], $Filtering)){
    	        				$NameObj = $ex[0]; // SET
    	        				$getObjInput = $this->__getObjInput($Input,$NameObj); // array key pola surat dan signature

                                $TagStrSignature = $this->__TagStrSignature($line); // cek approval by USER or GET
                                if (count($TagStrSignature > 0)) {
                                    $TagStrSignature[] = $Input['GET']; // last key for get
                                }

    	        				$callback_data = $this->m_set->preview_template($getObjInput,'','',$TagStrSignature);
    	        				$rs[$this->KeySET] = $callback_data;
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
    	        				// print_r($Input[$this->KeyTABLE]);die();
                                // print_r($Input);die();
                                $rs[$this->KeyTABLE] = $Input['TABLE'];
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
        // die();
    	return $this->__preview_template($rs,$FileTemplate);
    }

    private function getBodyBlock($string){
        if (preg_match('%(?i)(?<=<w:body>)[\s|\S]*?(?=</w:body>)%', $string, $regs)) {
            return $regs[0];
        } else {
            return '';
        }
    }

    private function __preview_template($rsGET,$FileTemplate){
    	// $FileTemplate    = $_FILES['PathTemplate']['tmp_name'][0];
		$line = $this->__readDoc($FileTemplate);
    	$TemplateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($FileTemplate);
        $BoolTbl = false;
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
                            case $this->KeyGET:
                                if ($this->KeyGET == $keyRS) {
                                    $setStr = trim(ucwords($ex[0]));
                                    $obj = $rsGET[$keyRS];
                                    $arrKomponen = $matches[1];

                                    $this->__SETWriteGET($TemplateProcessor,$arrKomponen,$obj);
                                }
                                break;
	    		    		case $this->KeyTABLE:
                                if ($this->KeyTABLE == $keyRS) {
                                        if (!$BoolTbl) {
                                            $this->load->model('document-generator/m_table');
                                            $dataPass = [
                                                'ID_api' => $rsGET[$keyRS]['API']['Choose'],
                                                'action' => 'sample',
                                            ];
                                            $dataPass = $dataPass + $rsGET[$keyRS]['paramsUser'];
                                            $RSQuery = $this->run_set_table($dataPass);
                                            $this->m_table->writeDocument($TemplateProcessor,$rsGET[$keyRS],$RSQuery);
                                            // $values = [
                                            //     [$this->KeyTABLE.'.Number' => 'No', $this->KeyTABLE.'.H1' => 'Mata Kuliah', $this->KeyTABLE.'.H2' => 'SKS',$this->KeyTABLE.'.H3' => 'Sesi'],
                                            //     [$this->KeyTABLE.'.Number' => '1', $this->KeyTABLE.'.H1' => 'Batman', $this->KeyTABLE.'.H2' => 'Gotham City',$this->KeyTABLE.'.H3' => '14'],
                                            // ];
                                            // $TemplateProcessor->cloneRowAndSetValues('TABLE.Number',$values );
                                            $BoolTbl = true;
                                        }
                                        
                                }
	    		    			break;
	    		    	}
	    		    }
    			}
    		    
    		    
    		}
    	}

    	// $TemplateProcessor->cloneRow('userId', 2);
        // $this->KeyTABLE = 'TBL';
        // $values = [
        //     ['TABLE.Number' => 'No', $this->KeyTABLE.'.H1' => 'Mata Kuliah', $this->KeyTABLE.'.H2' => 'SKS',$this->KeyTABLE.'.H3' => 'Sesi'],
        //     ['TABLE.Number' => '1', $this->KeyTABLE.'.H1' => 'Batman', $this->KeyTABLE.'.H2' => 'Gotham City',$this->KeyTABLE.'.H3' => '14'],
        // ];
        // $TemplateProcessor->cloneRowAndSetValues('TABLE.Number',$values );
        // $TemplateProcessor->setValue('TABLE.Number','dsadsadsa');

        // die();

    	$NIPExt = $this->session->userdata('NIP').'.docx';
    	$FileName = $NIPExt;
    	$pathFolder = FCPATH."uploads/document-generator/template/temp/"; 
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
        // print_r($pr);die();
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
    		$this->deleteFileConverter($rs['callback']);
    		$result['status'] = 1;
    		$result['callback'] = base_url().'uploads/document-generator/template/temp/'.$rs['callback'];
    	}
    	return $result; 
    }

    private function deleteFileConverter($filename = ''){
        if ($filename == '') {
            $array =[$this->session->userdata('NIP').'.docx',$this->session->userdata('NIP').'.pdf'];
        }
        else
        {
            $ex = explode('.', $filename);
            $array =[$ex[0].'.docx',$ex[0].'.pdf'];
        }
    	
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
    	for ($i=0; $i < count($arrValue); $i++) { 
            // $keyApproval = $i + 1;
    		$keyApproval = $arrValue[$i]['number'];

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

    		// write name
                for ($j=0; $j < count($arrKomponen); $j++) {
                    $str = $arrKomponen[$j];
                    $setValue = 'SET.Signature.Position'.'#'.$keyApproval;
                    if (strpos($str, $setValue) !== false) {
                        // $TemplateProcessor->setValue($setValue,$arrValue[$i]['NameEMP']);
                        $TemplateProcessor->setValue($str,$arrValue[$i]['NameEMP']);
                        break;
                    }
                    
                    $setValue = 'SET.Signature.NIP'.'#'.$keyApproval;
                    if (strpos($str, $setValue) !== false) {
                        // $TemplateProcessor->setValue($setValue,$arrValue[$i]['NameEMP']);
                        $TemplateProcessor->setValue($str,$arrValue[$i]['NameEMP']);
                        break;
                    }
                   
                }

                // write NIP
                $setValue = 'Signature.NIP'.'#'.$keyApproval;
                $TemplateProcessor->setValue($setValue,$arrValue[$i]['NIPEMP']);
        		// if (array_key_exists(3, $arrKomponen)) {
        		// 	$setValue = $arrKomponen[3];
        		// 	$TemplateProcessor->setValue($setValue,$arrValue[$i]['NIPEMP']);
        		// }
    		
    	}
    	
    }

    public function save_template($Input){
    	$rs = ['status' => 0,'callback' => ''];
    	$DocumentName = $Input['DocumentName'];
    	$DocumentAlias = $Input['DocumentAlias'];
    	$Config = $Input['settingTemplate'];
        
        /* Set Default array GET */
        // $GET = $Config['GET'];
        // if (array_key_exists('EMP', $GET)) {
        //     $EMP = $GET['EMP'];
        //     for ($i=0; $i < count($EMP); $i++) { 
        //        $Config['GET']['EMP'][$i]['user'] = []; 
        //     }
        // }

        // if (array_key_exists('MHS', $GET)) {
        //     $MHS = $GET['MHS'];
        //     for ($i=0; $i < count($MHS); $i++) { 
        //        $Config['GET']['MHS'][$i]['user'] = []; 
        //     }
        // }
        /* Set Default array GET */
        $Config = json_encode($Config);
    	// upload file template
    	$PathTemplate = $this->upload_file_template($DocumentName);
    	$dataSave = [
    		'DocumentName' => $DocumentName,
    		'DocumentAlias' => $DocumentAlias,
    		'Config' => $Config,
    		'PathTemplate' => $PathTemplate,
    		'UpdatedBy' => $this->session->userdata('NIP'),
    		'UpdatedAt' => date('Y-m-d H:i:s'),
            'DepartmentCreated' => $this->session->userdata('DepartmentIDDocument'),
            'ID_category_document' => $Input['ID_category_document'],
    	];
    	$this->db->insert('db_generatordoc.document',$dataSave);
        $ID_document = $this->db->insert_id();

        // save department access
        $DepartmentArr= $Input['DepartmentArr'];
        for ($i=0; $i < count($DepartmentArr); $i++) { 
            $Department = $DepartmentArr[$i]['Code'];
            $dataSave = [
                'ID_document' => $ID_document,
                'Department' => $Department,
            ];

            $this->db->insert('db_generatordoc.document_access_department',$dataSave);
        }

    	$rs['status'] = 1;
    	return $rs;
    }

    private function upload_file_template($DocumentName){
    	$filename = str_replace(' ', '_', $DocumentName);
    	$varFiles = 'PathTemplate';
    	$file = $this->m_master->uploadDokumenMultiple($filename,$varFiles,'./uploads/document-generator/template');
    	return $file[0];
    }

    public function loadtableMaster($dataToken=[]){
       $rs = [];
       $DepartmentID = $this->session->userdata('DepartmentIDDocument');
       $AddWhere = '';
       if (array_key_exists('Active', $dataToken)) {
           $Active = $dataToken['Active'];
           $AddWhere = ' where a.Active = "'.$Active.'"';
       }

       $WhereOrAnd = ($AddWhere == '') ? ' Where' : ' And';
       $AddWhere .= $WhereOrAnd.' a.DepartmentCreated = "'.$DepartmentID.'"';

       $sql = 'select a.*,b.Name,c.NameCategorySrt from db_generatordoc.document as a 
              join db_employees.employees as b on a.UpdatedBy = b.NIP
              join db_generatordoc.category_document as c on a.ID_category_document = c.ID 
       		'.$AddWhere.'
       ';
       $query = $this->db->query($sql,array())->result_array();
       $data = array();
       for ($i=0; $i < count($query); $i++) {
           $nestedData = array();
           $row = $query[$i]; 
           $nestedData[] = $i+1;
           $nestedData[] = $row['DocumentName'];
           $nestedData[] = $row['DocumentAlias'];
           $nestedData[] = base_url().'uploads/document-generator/template/'.$row['PathTemplate'];
           $nestedData[] = '';
           $nestedData[] = $row['ID'];
           $row['document_access_department'] = $this->m_master->caribasedprimary('db_generatordoc.document_access_department','ID_document',$row['ID']);
           $token = $this->jwt->encode($row,"UAP)(*");
           $nestedData[] = $token;
           $data[] = $nestedData;
       }
       $rs = array(
           "draw"            => intval( 0 ),
           "recordsTotal"    => intval(count($query)),
           "recordsFiltered" => intval( count($query) ),
           "data"            => $data
       );
       return $rs;
    }

    public function save_edit_department_access($Input){
        $rs = ['status' => 0,'callback' => ''];
        $ID_document = $Input['ID'];
        $this->db->where('ID_document',$ID_document);
        $this->db->delete('db_generatordoc.document_access_department');

        $DepartmentArr= $Input['DepartmentArr'];
        for ($i=0; $i < count($DepartmentArr); $i++) { 
            $Department = $DepartmentArr[$i]['Code'];
            $dataSave = [
                'ID_document' => $ID_document,
                'Department' => $Department,
            ];

            $this->db->insert('db_generatordoc.document_access_department',$dataSave);
        }

        $rs['status'] = 1;
        return $rs;
       
    }

    public function preview_template_table($dataToken){
    	$this->load->model('document-generator/m_set');
    	$this->load->model('document-generator/m_user');
        $this->load->model('document-generator/m_grab');
    	$this->load->model('document-generator/m_get');
    	$rs = [];
    	 // print_r($dataToken);die();
    	$FileTemplate    = './uploads/document-generator/template/'.$dataToken['PathTemplate'];
		$line = $this->__readDoc($FileTemplate);
    	$Filtering = [];
    	$Input = $dataToken['config'];

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

                                $TagStrSignature = $this->__TagStrSignature($line); // cek approval by USER or GET
                                if (count($TagStrSignature > 0)) {
                                    $TagStrSignature[] = $Input['GET']; // last key for get
                                }

    	        				$callback_data = $this->m_set->preview_template($getObjInput,'','',$TagStrSignature);
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
    	        				$rs[$this->KeyTABLE] = $Input['TABLE'];
    	        			}
    	        			else
    	        			{
    	        				continue;
    	        			}
    	        			break;
                        case $this->KeyGET:
                            if (!in_array($ex[0], $Filtering)){
                                $NameObj = $ex[0];
                                $getObjInput = $this->__getObjInput($Input,$NameObj);
                                $rs[$this->KeyGET] = $this->m_get->preview_template($getObjInput);
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

    	return $this->__preview_template($rs,$FileTemplate);

    }

    public function RemoveDocumentMaster($ID){
    	$rs=['status' => 0,'msg' => ''];
    	$this->db->where('ID',$ID);
    	$this->db->update('db_generatordoc.document',array('Active' => 0,
    													   'UpdatedBy' => $this->session->userdata('NIP'),
    													   'UpdatedAt' => date('Y-m-d H:i:s'),
    														)
    					);
    	$rs=['status' => 1,'msg' => ''];
    	return $rs;
    }

    /* --- */

    public function previewbyUserRequest($dataToken){
        $this->load->model('document-generator/m_set');
        $this->load->model('document-generator/m_user');
        $this->load->model('document-generator/m_grab');
        $this->load->model('document-generator/m_get');
        $rs = [];
        $ID = $dataToken['ID'];
        $G_dt = $this->m_master->caribasedprimary('db_generatordoc.document','ID',$ID);

        $FileTemplate    = './uploads/document-generator/template/'.$G_dt[0]['PathTemplate'];
        $line = $this->__readDoc($FileTemplate);
        $Filtering = [];
        $Input = $dataToken['settingTemplate'];
        $DepartmentID = $dataToken['DepartmentID'];
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

                                $TagStrSignature = $this->__TagStrSignature($line); // cek approval by USER or GET
                                if (count($TagStrSignature > 0)) {
                                    $TagStrSignature[] = $Input['GET']; // last key for get
                                }

                                $callback_data = $this->m_set->preview_template($getObjInput,$ID,$DepartmentID,$TagStrSignature);
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
                                $rs[$this->KeyTABLE] = $Input['TABLE'];
                            }
                            else
                            {
                                continue;
                            }
                            break;
                        case $this->KeyGET:
                            if (!in_array($ex[0], $Filtering)){
                                $NameObj = $ex[0];
                                $getObjInput = $this->__getObjInput($Input,$NameObj);
                                $rs[$this->KeyGET] = $this->m_get->preview_template($getObjInput);
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

        return $this->__preview_templatebyUserRequest($rs,$FileTemplate);
    }

    private function __SETWriteSignatureNoSignature($setStr,$TemplateProcessor,$arrKomponen,$arrValue){
        // print_r($arrValue);die();
        for ($i=0; $i < count($arrValue); $i++) { 
            // $keyApproval = $i + 1;
            $keyApproval = $arrValue[$i]['number'];
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

            
            // write name
                for ($j=0; $j < count($arrKomponen); $j++) {
                    $str = $arrKomponen[$j];
                    $setValue = 'SET.Signature.Position'.'#'.$keyApproval;
                    if (strpos($str, $setValue) !== false) {
                        // $TemplateProcessor->setValue($setValue,$arrValue[$i]['NameEMP']);
                        $TemplateProcessor->setValue($str,$arrValue[$i]['NameEMP']);
                        break;
                    }
                    
                    $setValue = 'SET.Signature.NIP'.'#'.$keyApproval;
                    if (strpos($str, $setValue) !== false) {
                        // $TemplateProcessor->setValue($setValue,$arrValue[$i]['NameEMP']);
                        $TemplateProcessor->setValue($str,$arrValue[$i]['NameEMP']);
                        break;
                    }
                   
                }

                // write NIP
                $setValue = 'Signature.NIP'.'#'.$keyApproval;
                $TemplateProcessor->setValue($setValue,$arrValue[$i]['NIPEMP']);
                // if (array_key_exists(3, $arrKomponen)) {
                //  $setValue = $arrKomponen[3];
                //  $TemplateProcessor->setValue($setValue,$arrValue[$i]['NIPEMP']);
                // }
            
        }
        
    }

    private function __preview_templatebyUserRequest($rsGET,$FileTemplate){
        // print_r($rsGET);die();
        // $FileTemplate    = $_FILES['PathTemplate']['tmp_name'][0];
        $line = $this->__readDoc($FileTemplate);
        $TemplateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($FileTemplate);
        $BoolTbl = false;
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
                                        $this->__SETWriteSignatureNoSignature($setStr,$TemplateProcessor,$arrKomponen,$arrValue);
                                        
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
                            case $this->KeyGET:
                                if ($this->KeyGET == $keyRS) {
                                    $setStr = trim(ucwords($ex[0]));
                                    $obj = $rsGET[$keyRS];
                                    $arrKomponen = $matches[1];

                                    $this->__SETWriteGET($TemplateProcessor,$arrKomponen,$obj);
                                }
                                break;
                            case $this->KeyTABLE:
                                if ($this->KeyTABLE == $keyRS) {
                                        if (!$BoolTbl) {
                                            $this->load->model('document-generator/m_table');
                                            $dataPass = [
                                                'ID_api' => $rsGET[$keyRS]['API']['Choose'],
                                                'action' => 'live',
                                            ];
                                            $dataPass = $dataPass + $rsGET[$keyRS]['paramsUser'];
                                            // print_r($dataPass);die();
                                            $RSQuery = $this->run_set_table($dataPass);
                                            $this->m_table->writeDocument($TemplateProcessor,$rsGET[$keyRS],$RSQuery);
                                            $BoolTbl = true;
                                        }
                                        
                                }
                                break;
                        }
                    }
                }
                
                
            }
        }
        
        $NIPExt = $this->session->userdata('NIP').'.docx';
        $FileName = $NIPExt;
        $pathFolder = FCPATH."uploads/document-generator/template/temp/"; 
        $pathFile = $pathFolder.$NIPExt; 
        $TemplateProcessor->saveAs($pathFile,$pathFolder);
        $convert = $this->ApiConvertDocxToPDF($pathFile,$pathFolder,$FileName);
        return $convert;

    }

    public function savebyUserRequest($dataToken){
        $dataSave = [];
        $this->load->model('document-generator/m_set');
        $this->load->model('document-generator/m_user');
        $this->load->model('document-generator/m_grab');
        $this->load->model('document-generator/m_get');
        $rs = [];
        $ID = $dataToken['ID'];
        $G_dt = $this->m_master->caribasedprimary('db_generatordoc.document','ID',$ID);
        $DocumentName = $G_dt[0]['DocumentName'];

        $FileTemplate    = './uploads/document-generator/template/'.$G_dt[0]['PathTemplate'];
        $line = $this->__readDoc($FileTemplate);
        $Filtering = [];
        $Input = $dataToken['settingTemplate'];
        $DepartmentID = $dataToken['DepartmentID'];
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

                                $TagStrSignature = $this->__TagStrSignature($line); // cek approval by USER or GET
                                if (count($TagStrSignature > 0)) {
                                    $TagStrSignature[] = $Input['GET']; // last key for get
                                }

                                $callback_data = $this->m_set->preview_template($getObjInput,$ID,$DepartmentID,$TagStrSignature);
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
                                $rs[$this->KeyTABLE] = $Input['TABLE'];
                            }
                            else
                            {
                                continue;
                            }
                            break;
                        case $this->KeyGET:
                            if (!in_array($ex[0], $Filtering)){
                                $NameObj = $ex[0];
                                $getObjInput = $this->__getObjInput($Input,$NameObj);
                                $rs[$this->KeyGET] = $this->m_get->preview_template($getObjInput);
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
        
        if ( array_key_exists('TBL', $rs) && array_key_exists('KEY', $rs['TBL'])  ) {
            $dataSave = $this->dataSaveForTable($dataSave,$rs['TBL']);
        }

        if (array_key_exists('GET', $rs) &&  ( array_key_exists('EMP', $rs['GET']) || array_key_exists('MHS', $rs['GET']) )  ) {
            $dataSave = $this->dataSaveForGET($dataSave,$rs['GET']);
        }

        // print_r($dataSave);die();
        
        $dataSave['ID_document'] = $ID;
        $dataSave['DepartmentID'] = $DepartmentID;
        $dataSave['NoSuratOnly'] = $rs['SET']['PolaNoSurat']['NoSuratOnly'];
        $dataSave['NoSuratFull'] = $rs['SET']['PolaNoSurat']['NoSuratStr'];
        $dataSave['UserNIP'] = $this->session->userdata('NIP');
        $dataSave['DateRequest'] = date('Y-m-d');
        $dataSave['UpdatedBy'] = $this->session->userdata('NIP');
        $dataSave['UpdatedAt'] = date('Y-m-d H:i:s');
        
        $dataSave = $this->__saveApproval($dataSave,$rs['SET']['Signature']);

        $dataSave = $this->__saveInput($dataSave,$dataToken['settingTemplate']['INPUT']);
        $getPath = $this->__savebyUserRequest($rs,$FileTemplate,$DocumentName);
        $dataSave['Path'] = $getPath;
        $this->__clearTempFile();
        $this->db->insert('db_generatordoc.document_data',$dataSave);

        // send notification
        $this->__send_notification($dataSave,'Request',$DocumentName);

        return 1;

    }

    private function dataSaveForGET($dataSave,$dt){
        if (array_key_exists('InputJson', $dataSave)) {
            $dataSave['InputJson'] = json_decode($dataSave['InputJson'],true);
        }
        else
        {
            $dataSave['InputJson'] = [];
        }
        $arr_rs = [];
        $arr_rs['GET'] = [];
        foreach ($dt as $key => $value) {
            $arr = $dt[$key];
            if ($key == 'EMP') {
                $arr_rs['GET'][$key] = $arr;
            }

            if ($key == 'MHS') {
               $arr_rs['GET'][$key] = $arr;
            }

            // for ($i=0; $i < count($arr); $i++) { 
            //     if ($key == 'EMP') {
            //         $arr_rs['GET'][$key][] = $arr[$i]['user'];
            //     }

            //     if ($key == 'MHS') {
            //        $arr_rs['GET'][$key][] = $arr[$i]['user'];
            //     }
            // }
        }

        $dataSave['InputJson'] = $dataSave['InputJson'] + $arr_rs;
        // print_r($dataSave);die();
        $dataSave['InputJson'] = json_encode($dataSave['InputJson']);
        return $dataSave;
    } 

    private function dataSaveForTable($dataSave,$dt){
        $ID_api = $dt['API']['Choose'];
        // print_r($dt);die();
        $G_dt = $this->m_master->caribasedprimary('db_generatordoc.api_doc','ID',$ID_api);
        $Params = json_decode($G_dt[0]['Params'],true) ;
        $arr_json = [];
        for ($i=0; $i < count($Params); $i++) { 
            if (substr($Params[$i], 0,1) == '#') {
                // get data by passing
                // $str = str_replace('#', '', $Params[$i]);
                $str = $Params[$i];
                $arr_json['TABLE'][$i][$str] = $dt['paramsUser'][$i][$str];
            }
            elseif (substr($Params[$i], 0,1) == '$') {
                $str = $Params[$i];
                $keySess = str_replace('$', '', $Params[$i]);
                $arr_json['TABLE'][$i][$str] = $this->session->userdata($keySess);
            }
        }

        // print_r($arr_json);die();

        $dataSave['InputJson'] = json_encode($arr_json);
        return $dataSave;
    }

    private function __clearTempFile(){
        $ext = ['.docx','.pdf'];
        $pathFolder = FCPATH."uploads/document-generator/template/temp/"; 
        for ($i=0; $i < count($ext); $i++) { 
            $pathFile = $pathFolder.$this->session->userdata('NIP').$ext[$i];
            if (file_exists($pathFile)) {
                unlink($pathFile);
            }
        }
    }

    private function __saveInput($dataSave,$dt){
        for ($i=0; $i < count($dt); $i++) { 
            $dataSave[$dt[$i]['mapping']]=$dt[$i]['value'];
        }

        return $dataSave;
    }

    private function __savebyUserRequest($rsGET,$FileTemplate,$DocumentName,$DefFileName=''){
        // $FileTemplate    = $_FILES['PathTemplate']['tmp_name'][0];
        // print_r($rsGET);die();
        $line = $this->__readDoc($FileTemplate);
        $TemplateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($FileTemplate);
        $BoolTbl = false;
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
                                        $this->__SETWriteSignatureNoSignature($setStr,$TemplateProcessor,$arrKomponen,$arrValue);
                                        
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
                            case $this->KeyGET:
                                if ($this->KeyGET == $keyRS) {
                                    $setStr = trim(ucwords($ex[0]));
                                    $obj = $rsGET[$keyRS];
                                    $arrKomponen = $matches[1];

                                    $this->__SETWriteGET($TemplateProcessor,$arrKomponen,$obj);
                                }
                                break;
                            case $this->KeyTABLE:
                                if ($this->KeyTABLE == $keyRS) {
                                        if (!$BoolTbl) {
                                            // print_r($rsGET[$keyRS]);die();
                                            $this->load->model('document-generator/m_table');
                                            $dataPass = [
                                                'ID_api' => $rsGET[$keyRS]['API']['Choose'],
                                                'action' => 'live',
                                            ];
                                            $dataPass = $dataPass + $rsGET[$keyRS]['paramsUser'];
                                            $RSQuery = $this->run_set_table($dataPass);
                                            $this->m_table->writeDocument($TemplateProcessor,$rsGET[$keyRS],$RSQuery);
                                            $BoolTbl = true;
                                        }
                                        
                                }
                                break;
                        }
                    }
                }
                
                
            }
        }

        $DocumentName = preg_replace('/\s+/', '_', $DocumentName);

        if ($DefFileName == '') {
            $FileName = $DocumentName.'_'.$this->session->userdata('NIP').'_'.uniqid();
            $FileNameExt = $FileName.'.docx';
        }
        else
        {
            $FileName = $DefFileName;
            $expDe = explode('.', $DefFileName);
            $FileNameExt = $expDe[0].'.docx';
        }
        
        $pathFolder = FCPATH."uploads/document-generator/"; 
        $pathFile = $pathFolder.$FileNameExt; 
        $TemplateProcessor->saveAs($pathFile,$pathFolder);
        $convert = $this->ApiConvertDocxToPDF($pathFile,$pathFolder,$FileNameExt);
        if (file_exists($pathFile)) {
            unlink($pathFile);
        }
        if ($DefFileName == '') {
           return $FileName.'.pdf';
        }
        else
        {
            return $FileName;
        }

    }



    private function __saveApproval($dataSave,$dt){
        /*
            key Field 
            * Approve1
            * Approve1Status
            * Approve1At
            * TotApproval

        */

        $dataSave['TotApproval'] = count($dt);
        $key;
        $chk = 1; // check status
        $manually = 1;
        for ($i=0; $i < count($dt); $i++) { 
            $key = $i+1;
            if ($dt[$i]['verify']['valueVerify'] == 1 ) {
               $dataSave['Approve'.$key] = $dt[$i]['NIPEMP'];
               $dataSave['Approve'.$key.'Status'] = 0;
               if ($manually == 1) {
                    $manually = 0;
               }
               // $dataSave['IsManually'] = 0; // approval system
               $chk = $chk * 0;
            }
            else
            {
                $dataSave['Approve'.$key] = $dt[$i]['NIPEMP'];
                $dataSave['Approve'.$key.'Status'] = 1;
                // $dataSave['IsManually'] = 1; // approval manually
                $chk = $chk * 1;
            }
        }

        $dataSave['IsManually'] = $manually;

        $LeftApproval = 3 - $dataSave['TotApproval'];
        for ($i=0; $i < $LeftApproval ; $i++) { 
            $key++;
            $dataSave['Approve'.$key.'Status'] = 1;
            $chk = $chk * 1;
        }

        if ($chk == 1) {
            $dataSave['Status'] = 'Approve';
        }
        else{
            $dataSave['Status'] = 'Request';
        }

        return $dataSave;   
    }

    public function NeedApproval(){
        $NIP = $this->session->userdata('NIP');
        $AddWhere = '';
        $WhereOrAnd = ($AddWhere == '') ? ' Where ' : ' And ';
        $AddWhere .= $WhereOrAnd.' ( (a.Approve1 = "'.$NIP.'" and a.Approve1Status = 0  ) or (a.Approve2 = "'.$NIP.'" and a.Approve2Status = 0  ) or ( a.Approve3 = "'.$NIP.'"  and a.Approve3Status = 0  )  )';

        $WhereOrAnd = ($AddWhere == '') ? ' Where ' : ' And ';
        $AddWhere .= $WhereOrAnd.' a.Status = 1';

        $sql = 'select a.NoSuratFull,b.DocumentName,a.UserNIP,c.Name as NameEMPRequest,a.DateRequest,a.Approve1,a.Approve2,a.Approve3,a.Status,
                a.Input1,a.Input2,a.Input3,a.Input4,a.Input5,a.Input6,a.Input7,a.Input8,a.Input9,a.Input10,
                a.Approve1Status,a.Approve1At,a.Approve2Status,a.Approve2At,a.Approve3Status,a.Approve3At,a.TotApproval,
                d.Name as NameEMPAppr1,e.Name as NameEMPAppr2,f.Name as NameEMPAppr3,a.Path,a.InputJson,a.IsManually,a.ID_document,a.DepartmentID,a.ID
                from db_generatordoc.document_data as a 
                left join db_generatordoc.document as b on a.ID_document =  b.ID
                left join db_employees.employees as c on c.NIP = a.UserNIP
                left join db_employees.employees as d on d.NIP = a.Approve1
                left join db_employees.employees as e on e.NIP = a.Approve2
                left join db_employees.employees as f on f.NIP = a.Approve3
                join db_generatordoc.document_access_department as g on g.ID_document = b.ID
                '.$AddWhere.'
                group by a.ID
                order by a.ID asc
                ';
                // print_r($sql);die();
        $query = $this->db->query($sql,array())->result_array();
        $data = array();
        for ($i=0; $i < count($query); $i++) { 
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = $row['NoSuratFull'];
            $nestedData[] = $row['DocumentName'];
            $nestedData[] = $row['UserNIP'].' - '.$row['NameEMPRequest'];
            $nestedData[] = $this->m_master->getDateIndonesian($row['DateRequest']);
            $Appr = '';

            if ($row['IsManually'] == 0) {
                if ($row['Approve1'] != '' && $row['Approve1'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $style = ($row['Approve1Status'] == 1 ) ? '<span style="color:green;"><i class="fa fa-check-circle"></i> approved</span>' : '<span style="color:red;"><i class="fa fa-minus-circle"></i> not approved</span>';
                    $Appr .= '<li><label>Approval 1 : '.$row['NameEMPAppr1'].'</label> | Status : '.$style.'</li>';
                }

                if ($row['Approve2'] != '' && $row['Approve2'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $style = ($row['Approve2Status'] == 1 ) ? '<span style="color:green;"><i class="fa fa-check-circle"></i> approved</span>' : '<span style="color:red;"><i class="fa fa-minus-circle"></i> not approved</span>';

                    $Appr .= '<li><label>Approval 2 : '.$row['NameEMPAppr2'].'</label> | Status : '.$style.'</li>';
                }

                if ($row['Approve3'] != '' && $row['Approve3'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $style = ($row['Approve3Status'] == 1 ) ? '<span style="color:green;"><i class="fa fa-check-circle"></i> approved</span>' : '<span style="color:red;"><i class="fa fa-minus-circle"></i> not approved</span>';

                    $Appr .= '<li><label>Approval 3 : '.$row['NameEMPAppr3'].'</label> | Status : '.$style.'</li>';
                }
            }
            else
            {
                $style = '<span style="color:green;"><i class="fa fa-check-circle"></i> Manually Approve</span>';
                if ($row['Approve1'] != '' && $row['Approve1'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    
                    $Appr .= '<li><label>Approval 1 : '.$row['NameEMPAppr1'].'</label> | Status : '.$style.'</li>';
                }

                if ($row['Approve2'] != '' && $row['Approve2'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $Appr .= '<li><label>Approval 2 : '.$row['NameEMPAppr2'].'</label> | Status : '.$style.'</li>';
                }

                if ($row['Approve3'] != '' && $row['Approve3'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $Appr .= '<li><label>Approval 3 : '.$row['NameEMPAppr3'].'</label> | Status : '.$style.'</li>';
                }

            }
            


            if ($Appr != '') {
                $Appr .= '</ul>';
            }
            $nestedData[] = $Appr;
            $nestedData[] = ($row['IsManually'] == 0) ? $row['Status'] : 'Manually Approve';
            // $nestedData[] = $row['Status'] ;
            $nestedData[] = $row['ID'];
            
            $sqlmasterDocument = 'select a.*,b.Name from db_generatordoc.document as a join db_employees.employees as b on a.UpdatedBy = b.NIP
                    join db_generatordoc.document_access_department as c on c.ID_document = a.ID
                    where a.ID = '.$row['ID_document'].'
                    group by a.ID
            ';
            $querymasterDocument = $this->db->query($sqlmasterDocument,array())->result_array();
            $row['masterDocument'] = $querymasterDocument;

            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }

        $rs = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data
        );
        return $rs;
    }

    public function LoadTablebyUserRequest($dataToken){
        $NIP = $this->session->userdata('NIP');
        $DepartmentID = $this->session->userdata('DepartmentIDDocument');
        $AddWhere = '';
        $opFilteringStatus = $dataToken['opFilteringStatus'];
        $opFilteringData = $dataToken['opFilteringData'];
        $IDMasterSurat = $dataToken['IDMasterSurat'];
        if ($opFilteringStatus != '' && $opFilteringStatus != 'All') {
           $WhereOrAnd = ($AddWhere == '') ? ' Where ' : ' And ';
           $AddWhere .= $WhereOrAnd.'a.Status = "'.$opFilteringStatus.'"';
        }

        if ($opFilteringData == 1) { // for me
            $WhereOrAnd = ($AddWhere == '') ? ' Where ' : ' And ';
            $AddWhere .= $WhereOrAnd.' (a.UserNIP = "'.$NIP.'" or a.Approve1 = "'.$NIP.'" or a.Approve2 = "'.$NIP.'" or a.Approve3 = "'.$NIP.'"  )';
        }

        if ($opFilteringData == 2) { // My Document
            $WhereOrAnd = ($AddWhere == '') ? ' Where ' : ' And ';
            $AddWhere .= $WhereOrAnd.' (a.UserNIP = "'.$NIP.'" )';
        }

        if ($opFilteringData == 3 || $opFilteringData == 4) { // My Approved or Rejected
            $WhereOrAnd = ($AddWhere == '') ? ' Where ' : ' And ';
            $AddWhere .= $WhereOrAnd.' (a.Approve1 = "'.$NIP.'" or a.Approve2 = "'.$NIP.'" or a.Approve3 = "'.$NIP.'")';
        }

        if ($IDMasterSurat != '') {
            $WhereOrAnd = ($AddWhere == '') ? ' Where ' : ' And ';
            $AddWhere .= $WhereOrAnd.' a.ID_document ='.$IDMasterSurat;
        }

        $WhereOrAnd = ($AddWhere == '') ? ' Where ' : ' And ';
        $AddWhere .= $WhereOrAnd.' g.Department ="'.$DepartmentID.'"';

        $sql = 'select a.NoSuratFull,b.DocumentName,a.UserNIP,c.Name as NameEMPRequest,a.DateRequest,a.Approve1,a.Approve2,a.Approve3,a.Status,
                a.Input1,a.Input2,a.Input3,a.Input4,a.Input5,a.Input6,a.Input7,a.Input8,a.Input9,a.Input10,
                a.Approve1Status,a.Approve1At,a.Approve2Status,a.Approve2At,a.Approve3Status,a.Approve3At,a.TotApproval,
                d.Name as NameEMPAppr1,e.Name as NameEMPAppr2,f.Name as NameEMPAppr3,a.Path,a.InputJson,a.IsManually,a.ID_document,a.DepartmentID,a.ID
                from db_generatordoc.document_data as a 
                left join db_generatordoc.document as b on a.ID_document =  b.ID
                left join db_employees.employees as c on c.NIP = a.UserNIP
                left join db_employees.employees as d on d.NIP = a.Approve1
                left join db_employees.employees as e on e.NIP = a.Approve2
                left join db_employees.employees as f on f.NIP = a.Approve3
                join db_generatordoc.document_access_department as g on g.ID_document = b.ID
                '.$AddWhere.'
                group by a.ID
                order by a.ID asc
                Limit 500
                ';
                // print_r($sql);die();
        $query = $this->db->query($sql,array())->result_array();
        $data = array();
        for ($i=0; $i < count($query); $i++) { 
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = $row['NoSuratFull'];
            $nestedData[] = $row['DocumentName'];
            $nestedData[] = $row['UserNIP'].' - '.$row['NameEMPRequest'];
            $nestedData[] = $this->m_master->getDateIndonesian($row['DateRequest']);
            $Appr = '';

            if ($row['IsManually'] == 0) {
                if ($row['Approve1'] != '' && $row['Approve1'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $style = ($row['Approve1Status'] == 1 ) ? '<span style="color:green;"><i class="fa fa-check-circle"></i> approved</span>' : '<span style="color:red;"><i class="fa fa-minus-circle"></i> not approved</span>';
                    $Appr .= '<li><label>Approval 1 : '.$row['NameEMPAppr1'].'</label> | Status : '.$style.'</li>';
                }

                if ($row['Approve2'] != '' && $row['Approve2'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $style = ($row['Approve2Status'] == 1 ) ? '<span style="color:green;"><i class="fa fa-check-circle"></i> approved</span>' : '<span style="color:red;"><i class="fa fa-minus-circle"></i> not approved</span>';

                    $Appr .= '<li><label>Approval 2 : '.$row['NameEMPAppr2'].'</label> | Status : '.$style.'</li>';
                }

                if ($row['Approve3'] != '' && $row['Approve3'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $style = ($row['Approve3Status'] == 1 ) ? '<span style="color:green;"><i class="fa fa-check-circle"></i> approved</span>' : '<span style="color:red;"><i class="fa fa-minus-circle"></i> not approved</span>';

                    $Appr .= '<li><label>Approval 3 : '.$row['NameEMPAppr3'].'</label> | Status : '.$style.'</li>';
                }
            }
            else
            {
                $style = '<span style="color:green;"><i class="fa fa-check-circle"></i> Auto Approve</span>';
                if ($row['Approve1'] != '' && $row['Approve1'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    
                    $Appr .= '<li><label>Approval 1 : '.$row['NameEMPAppr1'].'</label> | Status : '.$style.'</li>';
                }

                if ($row['Approve2'] != '' && $row['Approve2'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $Appr .= '<li><label>Approval 2 : '.$row['NameEMPAppr2'].'</label> | Status : '.$style.'</li>';
                }

                if ($row['Approve3'] != '' && $row['Approve3'] != NULL ) {
                    if ($Appr == '') {
                        $Appr .= '<ul style = "margin-left:-25px;">';
                    }
                    $Appr .= '<li><label>Approval 3 : '.$row['NameEMPAppr3'].'</label> | Status : '.$style.'</li>';
                }

            }
            


            if ($Appr != '') {
                $Appr .= '</ul>';
            }
            $nestedData[] = $Appr;
            $nestedData[] = ($row['IsManually'] == 0) ? $row['Status'] : 'Auto Approve';
            // $nestedData[] = $row['Status'] ;
            $nestedData[] = $row['ID'];
            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }

        $rs = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data
        );
        return $rs;

    }

    public function editbyUserRequest($dataToken){
        $dataSave = [];
        $this->load->model('document-generator/m_set');
        $this->load->model('document-generator/m_user');
        $this->load->model('document-generator/m_grab');
        $this->load->model('document-generator/m_get');
        $rs = [];
        $ID = $dataToken['ID'];
        $G_dt = $this->m_master->caribasedprimary('db_generatordoc.document','ID',$ID);
        $DocumentName = $G_dt[0]['DocumentName'];

        $FileTemplate    = './uploads/document-generator/template/'.$G_dt[0]['PathTemplate'];
        $line = $this->__readDoc($FileTemplate);
        $Filtering = [];
        $Input = $dataToken['settingTemplate'];
        $DepartmentID = $dataToken['DepartmentID'];
        $dataID = $dataToken['dataID'];
        $G_dt = $this->m_master->caribasedprimary('db_generatordoc.document_data','ID',$dataID);
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

                                $TagStrSignature = $this->__TagStrSignature($line); // cek approval by USER or GET
                                if (count($TagStrSignature > 0)) {
                                    $TagStrSignature[] = $Input['GET']; // last key for get
                                }

                                $callback_data = $this->m_set->preview_template($getObjInput,$ID,$DepartmentID,$TagStrSignature);
                                $rs[$this->KeySET] = $callback_data;

                                $rs['SET']['PolaNoSurat']['NoSuratOnly'] = $G_dt[0]['NoSuratOnly'];
                                $rs['SET']['PolaNoSurat']['NoSuratStr'] = $G_dt[0]['NoSuratFull'];

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
                                $DateRequest = $G_dt[0]['DateRequest'];
                                $rs[$this->KeyGRAB] = $this->m_grab->preview_template($getObjInput,$DateRequest);

                            }
                            else
                            {
                                continue;
                            }
                            
                            break;
                        case $this->KeyTABLE:
                            if (!in_array($ex[0], $Filtering)){
                                $rs[$this->KeyTABLE] = $Input['TABLE'];
                            }
                            else
                            {
                                continue;
                            }
                            break;
                        case $this->KeyGET:
                            if (!in_array($ex[0], $Filtering)){
                                $NameObj = $ex[0];
                                $getObjInput = $this->__getObjInput($Input,$NameObj);
                                $rs[$this->KeyGET] = $this->m_get->preview_template($getObjInput);
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

        if ( array_key_exists('TBL', $rs) && array_key_exists('KEY', $rs['TBL'])  ) {
            $dataSave = $this->dataSaveForTable($dataSave,$rs['TBL']);
        }

        if (array_key_exists('GET', $rs) &&  ( array_key_exists('EMP', $rs['GET']) || array_key_exists('MHS', $rs['GET']) )  ) {
            $dataSave = $this->dataSaveForGET($dataSave,$rs['GET']);
        }

        $dataSave['DepartmentID'] = $DepartmentID;
        $dataSave['UpdatedBy'] = $this->session->userdata('NIP');
        $dataSave['UpdatedAt'] = date('Y-m-d H:i:s');
        $dataSave = $this->__saveApproval($dataSave,$rs['SET']['Signature']);

        $dataSave = $this->__saveInput($dataSave,$dataToken['settingTemplate']['INPUT']);
        $DefFileName = $G_dt[0]['Path'];
        $getPath = $this->__savebyUserRequest($rs,$FileTemplate,$DocumentName,$DefFileName);
        $dataSave['Path'] = $getPath;
        $this->__clearTempFile();
        $this->db->where('ID',$dataID);
        $this->db->update('db_generatordoc.document_data',$dataSave);

        // send notification
        $this->__send_notification($dataSave,'Edit',$DocumentName);

        return 1;
    }

    public function __send_notification($dataSave,$action,$DocumentName,$numberApprove=1){
        switch ($numberApprove) {
            case 1:
                if ($dataSave['Approve1Status'] == 0) {
                    $data = array(
                        'auth' => 's3Cr3T-G4N',
                        'Logging' => array(
                                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>'.$action.' '.$DocumentName.' by '.$this->session->userdata('Name'),
                                        'Description' => 'Approval',
                                        'URLDirect' => 'request-document-generator/NeedApproval',
                                        'CreatedBy' => $this->session->userdata('NIP'),
                                      ),
                        'To' => array(
                                  'NIP' => array($dataSave['Approve1']),
                                ),
                        'Email' => 'No',
                    );

                    $url = url_pas.'rest2/__send_notif_browser';
                    $token = $this->jwt->encode($data,"UAP)(*");
                    $this->m_master->apiservertoserver($url,$token);
                }
                break;
            case 2:
                if ($dataSave['Approve2Status'] == 0) {
                    $data = array(
                        'auth' => 's3Cr3T-G4N',
                        'Logging' => array(
                                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>'.$DocumentName.' has been Approved by '.$this->session->userdata('Name').' as '.$action,
                                        'Description' => 'Approval',
                                        'URLDirect' => 'request-document-generator/NeedApproval',
                                        'CreatedBy' => $this->session->userdata('NIP'),
                                      ),
                        'To' => array(
                                  'NIP' => array($dataSave['Approve2']),
                                ),
                        'Email' => 'No',
                    );

                    $url = url_pas.'rest2/__send_notif_browser';
                    $token = $this->jwt->encode($data,"UAP)(*");
                    $this->m_master->apiservertoserver($url,$token);
                }
                else
                {
                    $data = array(
                        'auth' => 's3Cr3T-G4N',
                        'Logging' => array(
                                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>'.$DocumentName.' has been Approved by '.$this->session->userdata('Name').' as '.$action,
                                        'Description' => 'Approval Done',
                                        'URLDirect' => 'request-document-generator',
                                        'CreatedBy' => $this->session->userdata('NIP'),
                                      ),
                        'To' => array(
                                  'NIP' => array($dataSave['UserNIP']),
                                ),
                        'Email' => 'No',
                    );

                    $url = url_pas.'rest2/__send_notif_browser';
                    $token = $this->jwt->encode($data,"UAP)(*");
                    $this->m_master->apiservertoserver($url,$token);
                }
                break;
            case 3:
                if ($dataSave['Approve3Status'] == 0) {
                    $data = array(
                        'auth' => 's3Cr3T-G4N',
                        'Logging' => array(
                                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>'.$DocumentName.' has been Approved by '.$this->session->userdata('Name').' as '.$action,
                                        'Description' => 'Approval',
                                        'URLDirect' => 'request-document-generator/NeedApproval',
                                        'CreatedBy' => $this->session->userdata('NIP'),
                                      ),
                        'To' => array(
                                  'NIP' => array($dataSave['Approve3']),
                                ),
                        'Email' => 'No',
                    );

                    $url = url_pas.'rest2/__send_notif_browser';
                    $token = $this->jwt->encode($data,"UAP)(*");
                    $this->m_master->apiservertoserver($url,$token);
                }
                else
                {
                    $data = array(
                        'auth' => 's3Cr3T-G4N',
                        'Logging' => array(
                                        'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>'.$DocumentName.' has been Approved by '.$this->session->userdata('Name').' as '.$action,
                                        'Description' => 'Approval Done',
                                        'URLDirect' => 'request-document-generator',
                                        'CreatedBy' => $this->session->userdata('NIP'),
                                      ),
                        'To' => array(
                                  'NIP' => array($dataSave['UserNIP']),
                                ),
                        'Email' => 'No',
                    );

                    $url = url_pas.'rest2/__send_notif_browser';
                    $token = $this->jwt->encode($data,"UAP)(*");
                    $this->m_master->apiservertoserver($url,$token);
                }
                break;
            case 4: // Done
                $data = array(
                    'auth' => 's3Cr3T-G4N',
                    'Logging' => array(
                                    'Title' => '<i class="fa fa-check-circle margin-right" style="color:green;"></i>'.$DocumentName.' has been Approved by '.$this->session->userdata('Name').' as '.$action,
                                    'Description' => 'Approval Done',
                                    'URLDirect' => 'request-document-generator',
                                    'CreatedBy' => $this->session->userdata('NIP'),
                                  ),
                    'To' => array(
                              'NIP' => array($dataSave['UserNIP']),
                            ),
                    'Email' => 'No',
                );

                $url = url_pas.'rest2/__send_notif_browser';
                $token = $this->jwt->encode($data,"UAP)(*");
                $this->m_master->apiservertoserver($url,$token);
                break;
            default:
                # code...
                break;
        }
        
    }

    public function ApproveDocument($dataToken){
        $dataSave = [];
        $this->load->model('document-generator/m_set');
        $this->load->model('document-generator/m_user');
        $this->load->model('document-generator/m_grab');
        $this->load->model('document-generator/m_get');
        $rs = [];
        $ID = $dataToken['ID'];
        $G_dt = $this->m_master->caribasedprimary('db_generatordoc.document','ID',$ID);
        $DocumentName = $G_dt[0]['DocumentName'];

        $FileTemplate    = './uploads/document-generator/template/'.$G_dt[0]['PathTemplate'];
        $line = $this->__readDoc($FileTemplate);
        $Filtering = [];
        $Input = $dataToken['settingTemplate'];
        // print_r($dataToken);die();
        $DepartmentID = $dataToken['DepartmentID'];
        $dataID = $dataToken['dataID'];
        $approval_number =  $dataToken['approval_number'];
        $G_dt = $this->m_master->caribasedprimary('db_generatordoc.document_data','ID',$dataID);
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

                                $TagStrSignature = $this->__TagStrSignature($line); // cek approval by USER or GET
                                if (count($TagStrSignature > 0)) {
                                    $TagStrSignature[] = $Input['GET']; // last key for get
                                }

                                $callback_data = $this->m_set->preview_template($getObjInput,$ID,$DepartmentID,$TagStrSignature);
                                $rs[$this->KeySET] = $callback_data;
                                $rs['SET']['PolaNoSurat']['NoSuratOnly'] = $G_dt[0]['NoSuratOnly'];
                                $rs['SET']['PolaNoSurat']['NoSuratStr'] = $G_dt[0]['NoSuratFull'];

                                // added variable approval
                                $keyApprovalNumber = $approval_number - 1;
                                $rs['SET']['Signature'][$keyApprovalNumber]['approve'] = 1;

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

                                foreach ($G_dt[0] as $row => $rw) {
                                    for ($x=0; $x < count($getObjInput); $x++) { 
                                        if ($row == $getObjInput[$x]['mapping']) {
                                           $getObjInput[$x]['value'] = $rw;
                                        }
                                    }
                                }
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
                                $DateRequest = $G_dt[0]['DateRequest'];
                                $rs[$this->KeyGRAB] = $this->m_grab->preview_template($getObjInput,$DateRequest);

                            }
                            else
                            {
                                continue;
                            }
                            
                            break;
                        case $this->KeyTABLE:
                            if (!in_array($ex[0], $Filtering)){
                                // print_r($Input['TABLE']);die();
                                $rs[$this->KeyTABLE] = $Input['TABLE'];
                            }
                            else
                            {
                                continue;
                            }
                            break;
                        case $this->KeyGET:
                            if (!in_array($ex[0], $Filtering)){
                                $NameObj = $ex[0];
                                $getObjInput = $this->__getObjInput($Input,$NameObj);
                                $rs[$this->KeyGET] = $this->m_get->preview_template($getObjInput);
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

        $dataSave['UpdatedBy'] = $this->session->userdata('NIP');
        $dataSave['UpdatedAt'] = date('Y-m-d H:i:s');
        
        $dataSave['Approve'.$approval_number.'Status'] = 1;
        $dataSave['Approve'.$approval_number.'At'] = date('Y-m-d H:i:s');
        $G_dt[0]['Approve'.$approval_number.'Status'] = 1;
        $chk = 1; // check status
        for ($i=1; $i <= 3; $i++) { 
            if ($G_dt[0]['Approve'.$i.'Status'] == 1 ) {
                  $chk = $chk * 1; // check status
            }
            else
            {
                $chk = $chk * 0;
            }
        }
      
        $dataSave['Status'] = ($chk == 1) ? 'Approve' : 'Request';
        $DefFileName = $G_dt[0]['Path'];
        $getPath = $this->__AprrovebyUserRequest($rs,$FileTemplate,$DocumentName,$DefFileName);
        $dataSave['Path'] = $getPath;
        $this->__clearTempFile();
        $this->db->where('ID',$dataID);
        $this->db->update('db_generatordoc.document_data',$dataSave);

        // send notification
        $numberApprove = $approval_number + 1;
        $this->__send_notification($G_dt[0],'Approve '.$approval_number,$DocumentName,$numberApprove);

        return 1;
    }

    private function __AprrovebyUserRequest($rsGET,$FileTemplate,$DocumentName,$DefFileName=''){
        // $FileTemplate    = $_FILES['PathTemplate']['tmp_name'][0];
        $line = $this->__readDoc($FileTemplate);
        $TemplateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($FileTemplate);
        $BoolTbl = false;
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
                                        $this->__ApproveSETWriteSignature($setStr,$TemplateProcessor,$arrKomponen,$arrValue);
                                        
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
                            case $this->KeyGET:
                                if ($this->KeyGET == $keyRS) {
                                    $setStr = trim(ucwords($ex[0]));
                                    $obj = $rsGET[$keyRS];
                                    $arrKomponen = $matches[1];

                                    $this->__SETWriteGET($TemplateProcessor,$arrKomponen,$obj);
                                }
                                break;
                            case $this->KeyTABLE:
                                if ($this->KeyTABLE == $keyRS) {
                                        if (!$BoolTbl) {
                                            $this->load->model('document-generator/m_table');
                                            $dataPass = [
                                                'ID_api' => $rsGET[$keyRS]['API']['Choose'],
                                                'action' => 'live',
                                            ];
                                            $dataPass = $dataPass + $rsGET[$keyRS]['paramsUser'];
                                          
                                            // get session from user request
                                            $dtArrSess = [];
                                            $dtUser = $rsGET['USER'];
                                            // print_r($dtUser);die();
                                            for ($z=0; $z < count($dtUser); $z++) {
                                                $field = $dtUser[$z]['field'];
                                                $ex_field = explode('.', $field);
                                                $dtArrSess[$ex_field[1]] = $dtUser[$z]['value'];
                                            }
                                            $RSQuery = $this->run_set_table($dataPass,$dtArrSess);
                                            $this->m_table->writeDocument($TemplateProcessor,$rsGET[$keyRS],$RSQuery);
                                            $BoolTbl = true;
                                        }
                                        
                                }
                                break;
                        }
                    }
                }
                
                
            }
        }

        $DocumentName = preg_replace('/\s+/', '_', $DocumentName);
        
        if ($DefFileName == '') {
            $FileName = $DocumentName.'_'.$this->session->userdata('NIP').'_'.uniqid();
            $FileNameExt = $FileName.'.docx';
        }
        else
        {
            $FileName = $DefFileName;
            $expDe = explode('.', $DefFileName);
            $FileNameExt = $expDe[0].'.docx';
        }
        $pathFolder = FCPATH."uploads/document-generator/"; 
        $pathFile = $pathFolder.$FileNameExt; 
        $TemplateProcessor->saveAs($pathFile,$pathFolder);
        $convert = $this->ApiConvertDocxToPDF($pathFile,$pathFolder,$FileNameExt);
        if (file_exists($pathFile)) {
            unlink($pathFile);
        }

        if ($DefFileName == '') {
           return $FileName.'.pdf';
        }
        else
        {
            return $FileName;
        }
        

    }


    private function __ApproveSETWriteSignature($setStr,$TemplateProcessor,$arrKomponen,$arrValue){
        for ($i=0; $i < count($arrValue); $i++) { 
            // $keyApproval = $i + 1;
            $keyApproval = $arrValue[$i]['number'];
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
                                    if ($arrValue[$i]['approve'] == 1) {
                                       $setValue = $ex[0].'.'.$exKey2[0].'#'.$keyApproval;
                                       $TemplateProcessor->setImageValue($setValue, 
                                           array('path' => $img, 
                                               // 'width' => 200, 
                                               'height' => 300, 
                                               'ratio' => true,

                                           ),'behind'
                                       );
                                    }
                                    else
                                    {
                                        $setValue = $ex[0].'.'.$exKey2[0].'#'.$keyApproval;
                                        $TemplateProcessor->setValue($setValue,'');
                                    }
                                    
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
                                    if ($arrValue[$i]['approve'] == 1) {
                                        $setValue = $ex[0].'.'.$exKey2[0].'#'.$keyApproval;
                                        $TemplateProcessor->setImageValue($setValue, array(
                                                'path' => $img, 
                                                // 'width' => 200, 
                                                'height' => 300,
                                                'ratio' => true,
                                            ),'behind'
                                        );
                                    }
                                    else
                                    {
                                        $setValue = $ex[0].'.'.$exKey2[0].'#'.$keyApproval;
                                        $TemplateProcessor->setValue($setValue,'');
                                    }
                                    
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

    public function run_set_table($dataToken,$dtArrSess=[]){
        $ID_api = $dataToken['ID_api'];
        $G_dt = $this->m_master->caribasedprimary('db_generatordoc.api_doc','ID',$ID_api);
        $Params = json_decode($G_dt[0]['Params'],true) ;
        $querySql = $G_dt[0]['Query'];
        $VarPassing = [];
        // print_r($dataToken);die();
        for ($i=0; $i < count($Params); $i++) { 
            if (substr($Params[$i], 0,1) == '#') {
                // get data by passing
                // $str = str_replace('#', '', $Params[$i]);
                $str = $Params[$i];
                $VarPassing[] = $dataToken[$i][$str];
            }
            else if (substr($Params[$i], 0,1) == '$') {
                $keySess = str_replace('$', '', $Params[$i]);
                $str = $Params[$i];
                if ($dataToken['action'] == 'sample') {
                    $VarPassing[] = $dataToken[$i][$str];
                }
                else
                {
                    if(!empty($dtArrSess)) 
                    {
                        foreach ($dtArrSess as $field_dt => $value_dt) {
                            if ($field_dt == $keySess) {
                                $VarPassing[] = $value_dt;
                                break;
                            }
                        }
                    }
                    else
                    {
                        $VarPassing[] = $this->session->userdata($keySess);
                    }
                    
                }
            }
        }
      
        $query = $this->db->query($querySql,$VarPassing)->result_array();
        if (count($query) > 0) {
            return [
                'status' => 1,
                'callback' => $query,
            ];
        }
        else
        {
            echo "No data result";die();
        }

    }

    private function __SETWriteGET($TemplateProcessor,$arrKomponen,$obj){
         // print_r($arrKomponen);die();
        // print_r($obj);die();
        for ($z=0; $z < count($arrKomponen); $z++) { 
            $komponen = $arrKomponen[$z];
            // print_r($komponen);
            $ex = explode('.', $komponen);
            // print_r($ex);
            if ($ex[0] == 'GET') {
                // get Type
                $keyType = $ex[1];

                // get key field
                $get_keyField = $ex[2];
                // get numbering by #
                $keyFieldArr = explode('#', $get_keyField);
                $keyField =  $keyFieldArr[0];
                $keyNumber = $keyFieldArr[1];

                foreach ($obj as $key => $v) {
                   switch ($key) {
                       case 'EMP':
                       case 'MHS':
                           if ($keyType == $key) {
                               $arr_dt = $obj[$key];
                               for ($i=0; $i < count($arr_dt); $i++) {
                                    $number = $arr_dt[$i]['number'];
                                    if ($arr_dt[$i]['Choose'] == $keyField && $number == $keyNumber) {
                                        $arr_get_value = $arr_dt[$i]['user'];
                                        if(!empty($arr_get_value)) {
                                            $TemplateProcessor->setValue($komponen,$arr_get_value[$keyField]);
                                            foreach ($arr_get_value as $col => $value) {
                                                $setValue = $keyType.'.'.$col.'#'.$keyNumber;
                                                $TemplateProcessor->setValue($setValue,$value);
                                            }
                                        }
                                        else
                                        {

                                            $setValue ='GET.'.$keyType.'.'.'NIP'.'#'.$keyNumber;
                                            $TemplateProcessor->setValue($setValue,'');

                                            $setValue = $keyType.'.'.'Name'.'#'.$keyNumber;
                                            $TemplateProcessor->setValue($setValue,'');
                                            // print_r($arrKomponen);die();
                                        }
                                        
                                    } 
                                   
                               }
                           }
                           
                           break;
                       default:
                           # code...
                           break;
                   }
                }
            }
            

        }
        

         // die();

    }

    public function LoadMasterSuratAccess($dataToken=[]){
        $rs = [];
        $DepartmentID = $this->session->userdata('DepartmentIDDocument');
        $AddWhere = '';
        if (array_key_exists('Active', $dataToken)) {
            $Active = $dataToken['Active'];
            $AddWhere = ' where a.Active = "'.$Active.'"';
        }

        $WhereOrAnd = ($AddWhere == '') ? ' Where' : ' And';
        $AddWhere .= $WhereOrAnd.' c.Department = "'.$DepartmentID.'"';

        $sql = 'select a.*,b.Name from db_generatordoc.document as a join db_employees.employees as b on a.UpdatedBy = b.NIP
                join db_generatordoc.document_access_department as c on c.ID_document = a.ID
                '.$AddWhere.'
                group by a.ID
        ';
        $query = $this->db->query($sql,array())->result_array();
        $data = array();
        for ($i=0; $i < count($query); $i++) {
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = $i+1;
            $nestedData[] = $row['DocumentName'];
            $nestedData[] = $row['DocumentAlias'];
            $nestedData[] = base_url().'uploads/document-generator/template/'.$row['PathTemplate'];
            $nestedData[] = '';
            $nestedData[] = $row['ID'];
            $row['document_access_department'] = $this->m_master->caribasedprimary('db_generatordoc.document_access_department','ID_document',$row['ID']);
            
            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }
        $rs = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data
        );
        return $rs;
    }

    public function LoadTableCategorySrt($dataToken=[]){
        $this->load->model('ticketing/m_general');
        $rs = [];
        $AddWhere = '';
        $DepartmentID = $this->session->userdata('DepartmentIDDocument');
        if (!empty($dataToken)) {
            if (array_key_exists('Active', $dataToken)) {
                $Active = $dataToken['Active'];
                $AddWhere .= ' where a.Active = "'.$Active.'"';
            }
        }

        $sql = 'select a.*,qdx.NameDepartment,b.Name as NameUpdated
                from db_generatordoc.category_document as a 
                '.$this->m_general->QueryDepartmentJoin('a.Department','qdx').'
                join db_employees.employees as b on a.UpdatedBy = b.NIP
                '.$AddWhere.'
                order by ID desc
                ';
        $query = $this->db->query($sql,array())->result_array();
        $data = array();
        for ($i=0; $i < count($query); $i++) {
            $nestedData = array();
            $row = $query[$i]; 
            $nestedData[] = $row['NameCategorySrt'];
            $nestedData[] = $row['DescSrt'];
            $nestedData[] = $row['NameDepartment'];
            $nestedData[] = $row['UpdatedBy'];
            $nestedData[] = $row['NameUpdated'];
            $nestedData[] = $row['UpdatedAt'];
            $nestedData[] = $row['ID'];
            $token = $this->jwt->encode($row,"UAP)(*");
            $nestedData[] = $token;
            $data[] = $nestedData;
        }
        $rs = array(
            "draw"            => intval( 0 ),
            "recordsTotal"    => intval(count($query)),
            "recordsFiltered" => intval( count($query) ),
            "data"            => $data
        );
        return $rs;
    }
  
}
