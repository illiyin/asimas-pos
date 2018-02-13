<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Transaksi_gudang_keluar/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksigudangkeluarmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        // $this->_insertLog();
    }

    function index(){
    	$this->load->view('Transaksi_gudang_keluar/view');
    	// $this->load->view('Produksi_perintah/view', $data);
    }

}
