<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_fifo/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanfifomodel');
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
    function barang(){
    	$this->load->view('Laporan_fifo/barang');
    }
    function bahan(){
    	$this->load->view('Laporan_fifo/bahan');
    }
    function supplier(){
    	$this->load->view('Laporan_fifo/supplier');
    }
    function produsen(){
    	$this->load->view('Laporan_fifo/produsen');
    }
    function distributor(){
    	$this->load->view('Laporan_fifo/distributor');
    }
  }
