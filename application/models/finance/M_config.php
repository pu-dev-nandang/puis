<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_config extends CI_Model {

   private $data = array();
   function __construct()
   {
       parent::__construct();
       $this->load->model('finance/m_finance');
       $this->load->model('master/m_master');
   }

   

}
