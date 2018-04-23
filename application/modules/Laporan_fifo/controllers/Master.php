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
        $sql = "SELECT * ";
        $sql.=" FROM m_barang WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( nama_barang LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR no_batch LIKE '%".$requestData['search']['value']."%' )";
    }
        $query=$this->Laporanfifomodel->rawQuery($sql);
        $data['data_list'] = $query->result();
        $this->load->view('Laporan_fifo/cetak-barang', $data);
    }
    function cetakbahan(){
        $sql = "SELECT "; 
        $sql .= "m_bahan.nama AS nama_bahan,
                        m_bahan_kategori.nama AS kategori_bahan,
                        m_bahan.tgl_datang AS tanggal_datang
                        FROM m_bahan, m_bahan_kategori
                        WHERE m_bahan.id_kategori_bahan = m_bahan_kategori.id AND m_bahan.deleted = 1";
        if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( m_bahan.nama LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR m_bahan_kategori.nama LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Laporanfifomodel->rawQuery($sql);
        $data['data_list'] = $query->result();
        $this->load->view('Laporan_fifo/cetak-bahan', $data);
    }
    function cetaksupplier(){
        $sql = "SELECT * ";
        $sql.=" FROM m_supplier WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR alamat LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_telp LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR email LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Laporanfifomodel->rawQuery($sql);
        $data['data_list'] = $query->result();
        $this->load->view('Laporan_fifo/cetak-supplier', $data);
    }
    function cetakprodusen(){
        $sql = "SELECT * ";
        $sql.=" FROM m_produsen WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR alamat LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_telp LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR email LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Laporanfifomodel->rawQuery($sql);
        $data['data_list'] = $query->result();
        $this->load->view('Laporan_fifo/cetak-produsen', $data);
    }
    function cetakdistributor(){
        $sql = "SELECT * ";
        $sql.=" FROM m_distributor WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR alamat LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_telp LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR email LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Laporanfifomodel->rawQuery($sql);
        $data['data_list'] = $query->result();
    	$this->load->view('Laporan_fifo/cetak-distributor', $data);
    }
  }
