<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_fefo/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanfefomodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        // $this->_insertLog();
    }
    function testUri(){
        print_r($this->uri->uri_to_assoc());
    }
    function checkAccess(){
        print_r($this->session->userdata());
    }
    function index(){
    	$this->load->view('Laporan_fefo/view');
    }
  }
