<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Produksi_perintah/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Perintahproduksimodel');
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

    	$this->load->view('Produksi_perintah/view');
    	// $this->load->view('Produksi_perintah/view', $data);
    }

    function perintahbaru(){

      $this->load->view('Produksi_perintah/perintahBaru');
      // $this->load->view('Produksi_perintah/view', $data);
    }
    function perintahrevisi(){

      $this->load->view('Produksi_perintah/perintahRevisi');
      // $this->load->view('Produksi_perintah/view', $data);
    }
    function cetak(){

      $this->load->view('Produksi_perintah/perintahCetak');
      // $this->load->view('Produksi_perintah/view', $data);
    }


}
