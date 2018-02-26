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
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Laporanfifomodel->insert($dataInsert, 't_log');
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
    function cetakbarang(){
    	$this->load->view('Laporan_fifo/cetak-barang');
    }
    function cetakbahan(){
    	$this->load->view('Laporan_fifo/cetak-bahan');
    }
    function cetaksupplier(){
    	$this->load->view('Laporan_fifo/cetak-supplier');
    }
    function cetakprodusen(){
    	$this->load->view('Laporan_fifo/cetak-produsen');
    }
    function cetakdistributor(){
    	$this->load->view('Laporan_fifo/cetak-distributor');
    }
  }
