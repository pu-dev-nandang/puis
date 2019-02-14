<?php
require APPPATH.'third_party/fpdf/watermark/rotation.php';
class pdf extends PDF_Rotate{

    function __construct($orientation='P', $unit='mm', $size='A4') {
    	FPDF::__construct($orientation,$unit,$size);
        // require_once APPPATH.'third_party/fpdf/fpdf-1.8.php';
    }

    function RotatedText($x, $y, $txt, $angle)
    {
    	//Text rotated around its origin
    	$this->Rotate($angle,$x,$y);
    	$this->Text($x,$y,$txt);
    	$this->Rotate(0);
    }
}
?>