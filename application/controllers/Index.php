<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Index extends MX_Controller {
	function __construct() {
        parent::__construct();
        $this->load->model("Gmodel");
        $this->load->helper("url");
        $this->checkLogin();
    }
    function checkLogin(){
        if($this->session->userdata('is_logged_in')!=1){
            $current_url = urlencode(base_url(uri_string()));
            header("location:".base_url('Login/master/index')."?&redir=".$current_url);
            exit;
        }
    }
    function index(){
    	//$data['view'] = 'base_html/sale';
    	//$this->load->view('base_html/base', $data);
        redirect("index/modul/Transaksi_penjualan-Transaksi-index");
    }
    function base_sale(){
    	$this->load->view('base_html/sale');
    }
    function sale(){
        $data['view'] = 'base_html/sale';
        $this->load->view('base_html/base', $data);     
    }
    function testSession(){
        print_r($this->session->userdata());
    }
    function modul($modul = null){
        $sql = "SELECT B.id, B.nama, B.icon_class AS kategori_icon, (SELECT COUNT(A.id) FROM m_pegawai_permission A WHERE A.id_menu = B.id) AS jumlah_sub FROM m_pegawai_menu B";
        $data['nav_kategori'] = $this->Gmodel->rawQuery($sql)->result();
        $data['nav_menu'] = $this->Gmodel->get("m_pegawai_permission", "id_menu, urutan, id", "ASC")->result();

        if($modul != null){
            $realmodul = explode("-", $modul);
            $modul=str_replace("-", "/", $modul);
            $realmoduls = $realmodul[0]."-".$realmodul[1];
            $selectData['url'] = "index/modul/".$realmoduls;
            $selectDataPermission = $this->Gmodel->like('null', $selectData, 'm_pegawai_permission');
            if($selectDataPermission->num_rows() > 0){            
                $idPermission = $selectDataPermission->row()->id;
                if($idPermission!=null && $this->session->userdata('user_permission')!=null){                
                    if(in_array($idPermission, $this->session->userdata('user_permission'))){            
                        $data['view'] = $modul;
                    }else{
                        $data['view'] = "index/access_restricted";
                    }
                }else{
                    $data['view'] = "index/access_restricted";
                }
            }else{
                $data['view'] = "index/access_restricted";
            }
        }else{
            $data['view'] = 'index/base_sale';
        }

        //exception for Dev_menu & Dev_kategori
        $segment = strtolower($realmodul[0]);
        if(($segment == 'dev_kategori') || ($segment == 'dev_menu') || ($segment == 'api')){
            $data['view'] = $modul;
        } 

        //Hide navbar menu untuk segment Transaksi Penjualan & Transaksi Retur
        $data['segmen_hide'] = array(
                'transaksi_penjualan', 'transaksi_retur'
                );
        $data['segmen_0'] = strtolower($realmodul[0]);
        $data['segmen_1'] = strtolower($realmodul[1]);
        $data['segmen_2'] = strtolower($realmodul[2]);

        $this->load->view('base_html/base', $data);
    }
    function access_restricted(){
        $this->load->view('base_html/access_restricted');
    }
    function login(){
        $params = $this->input->get();
        $data['redir'] = isset($params['redir']) ? $params['redir'] : '';

    	$this->load->view('Login/view', $data);
    }
}