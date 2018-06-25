<?php
class pdf {

    function __construct() {
//        include_once APPPATH . '/third_party/fpdf/fpdf.php';
        require_once APPPATH.'third_party/fpdf/fpdf-1.8.php';
    }
}
?>