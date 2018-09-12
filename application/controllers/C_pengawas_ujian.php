
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Nandang
 * Date: 12/20/2017
 * Time: 1:41 PM
 */
include_once APPPATH.'vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class C_pengawas_ujian extends CI_Controller {

    function __construct()
    {
        parent::__construct();


        // define config Virtual Account
        if (!$this->session->userdata('loggedIn')) {
            redirect(base_url());
        }

        $this->load->library('JWT');
        $this->load->library('google');
        $this->load->model('m_auth');
        date_default_timezone_set("Asia/Jakarta");

    }

    public function index(){
        $data['include'] = $this->load->view('template/include','',true);
        $this->load->view('template/pengawas_ujian',$data);
    }




}
