<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH.'vendor/autoload.php';
class M_api_doc extends CI_Model {
    private $CODE = [];
    private $KeyCODE = 'CODE';
    private $Parameter;
    function __construct()
    {
        parent::__construct();
        $this->load->model('master/m_master');
        $this->load->model('document-generator/m_doc');
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

    public function readTemplate($FileTemplate){
        $rs = [];
        $line = $this->__readDoc($FileTemplate);
        foreach ($line as $v) {
            if(preg_match_all('/{+(.*?)}/', $v, $matches)){
                if (array_key_exists(1, $matches)) {
                    for ($z=0; $z < count($matches[1]); $z++) { 
                        $str = trim($matches[1][$z]);    
                        $ex = explode('.', $str);
                        if (count($ex) > 0) {
                            switch ($ex[0]) {
                                case $this->KeyCODE:
                                    if (!array_key_exists($this->KeyCODE, $rs)) {
                                        $rs[$this->KeyCODE] = [];
                                    }
                                    if (!in_array($ex[0], $rs[$this->KeyCODE])){
                                        $NameObj = $ex[0];
                                        $setStr = trim(ucwords($ex[1]));
                                        
                                        for ($i=2; $i < count($ex); $i++) {
                                            $setStr .= '.'.trim(ucwords($ex[$i]));
                                            $rs[$this->KeyCODE][] = $setStr;
                                        }
                                        
                                    }
                                    else
                                    {
                                        continue;
                                    }    
                                    break;
                                
                            }

                        }
                    }
                }
                else
                {
                    print_r('error');die();
                }
               
            }
        }

        return $rs;
    }

    public function exeDocx_permohonanBiayaKuliah($dataToken,$FileTemplate){
        $arrSelector = $this->readTemplate($FileTemplate);
        $TemplateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($FileTemplate);
        $data = $dataToken['data'];
        $NPM = $data['NPM'];
        $AC_MHS = $this->db->query(
            'select * from db_academic.auth_students where NPM = "'.$NPM.'" '
        )->result_array()[0];

        $db_ = 'ta_'.$AC_MHS['Year'];
        $TA_MHS = $this->db->query(
            'select a.*,b.Name as ProdiName,b.NameEng as ProdiNameEng from  '.$db_.'.students as a 
             join db_academic.program_study as b on a.ProdiID = b.ID
             where a.NPM = "'.$NPM.'" 
             '
        )->result_array()[0];

        foreach ($arrSelector as $key => $arr) {
            for ($i=0; $i < count($arr); $i++) { 
                $setValue = $key.'.'.$arr[$i];
                $ex = explode('.', $arr[$i]);
                $field = $ex[1];
                $param = $ex[0];
                switch ($param) {
                    case 'NPM':
                        $TemplateProcessor->setValue($setValue,$TA_MHS[$field]);
                        break;
                    
                    default:
                        # code...
                        break;
                }
                
            }
            
        }

        $FileName = $NPM.'_surat_permohonan_cicilan.docx';
        $FilePDF = $NPM.'_surat_permohonan_cicilan.pdf';
        if (!file_exists('./uploads/document/'.$NPM)) {
            mkdir('./uploads/document/'.$NPM, 0777, true);
            copy("./uploads/index.html",'./uploads/document/'.$NPM.'/index.html');
            copy("./uploads/index.php",'./uploads/document/'.$NPM.'/index.php');
        }
        $pathFolder = FCPATH."uploads/document/".$NPM.'/';
        $pathFile = $pathFolder.$FileName;  
        $TemplateProcessor->saveAs($pathFile,$pathFolder);
        $convert = $this->m_doc->ApiConvertDocxToPDF($pathFile,$pathFolder,$FileName);
        $convert['callback'] = base_url().'fileGetAny/document-'.$NPM.'-'.$FilePDF;
        return $convert;

    }
  
}
