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
        $data['list_bahan'] = $this->Laporanfifomodel->select(['deleted' => 1], 'm_bahan', 'nama')->result();
    	$this->load->view('Laporan_fifo/supplier', $data);
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
        $sql = "SELECT gudang.id_bahan, gm.id_supplier FROM tt_gudang_masuk gm, tt_gudang gudang
            WHERE gm.id = gudang.id_gudang GROUP BY gudang.id_bahan, gm.id_supplier";
        $query = $this->Laporanfifomodel->rawQuery($sql);
        
        $data = null;
        if($query->num_rows() > 0){
            foreach($query->result() as $row) {
              $bahan = $this->Laporanfifomodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
              $supplier = $this->Laporanfifomodel->select(array('id' => $row->id_supplier), 'm_supplier')->row();

                $temporary = array(
                    'nama_bahan' => $bahan->nama,
                    'nama_supplier' => $supplier->nama,
                    'alamat' => $supplier->alamat,
                    'no_telp' => $supplier->no_telp,
                    'email' => $supplier->email
                );

              $data[] = $temporary;
            }
        }

        $data['data_list'] = $data;
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
