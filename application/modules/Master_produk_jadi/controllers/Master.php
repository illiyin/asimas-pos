<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_produk_jadi/";
    private $fungsi = "";
    function __construct() {
        parent::__construct();
        $this->load->model('Masterprodukjadimodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        // $this->_insertLog();
    }

    function index(){

        // $this->load->view('Master_produk_jadi/view', $data);
        $this->load->view('Master_produk_jadi/view');
    }

}
