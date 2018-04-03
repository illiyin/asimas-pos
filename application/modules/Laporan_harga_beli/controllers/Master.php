<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_harga_beli/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanhargabelimodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Laporanhargabelimodel->insert($dataInsert, 't_log');
    }
    function index(){
    	$this->load->view('Laporan_harga_beli/view');
    }
    function cetak(){
        $sql = "SELECT ";
        $sql.="m_barang.nama_barang,
                m_bahan.nama AS nama_bahan,
                AVG(tt_gudang_masuk.harga_pembelian) AS harga_pembelian
                FROM tt_gudang_masuk,m_barang, m_bahan
                WHERE m_barang.id = tt_gudang_masuk.id_barang 
                AND m_bahan.id = tt_gudang_masuk.id_bahan
                AND tt_gudang_masuk.deleted = 1";
        $sql .= " GROUP BY tt_gudang_masuk.id_barang";
        $query=$this->Laporanhargabelimodel->rawQuery($sql);
        $data['data_list'] = $query->result();
    	$this->load->view('Laporan_harga_beli/cetak', $data);
    }
    function data(){
      $requestData = $_REQUEST;
      $sql = "SELECT bahan.nama AS nama_bahan, satuan.nama AS nama_satuan,
              SUM(gm.harga_pembelian) AS harga_jual,
              SUM(gm.jumlah_masuk) AS total_qty,
              SUM(gm.harga_pembelian * gm.jumlah_masuk) / SUM(gm.jumlah_masuk) AS total
              FROM m_bahan bahan, tt_gudang_masuk gm, m_bahan_kategori kategori, m_satuan satuan
              WHERE gm.id_bahan = bahan.id AND bahan.id_kategori_bahan = kategori.id
              AND gm.deleted = 1 AND bahan.id_satuan = satuan.id
              AND kategori.nama LIKE '%produk jadi%'";
      if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' )";
      }

      $sql.= " GROUP BY gm.id_bahan";
      $query=$this->Laporanhargabelimodel->rawQuery($sql);
      $totalFiltered = $query->num_rows();
      
      $data = array(); $i = 0;
      foreach($query->result_array() as $row) {
        $nestedData     =   array();
        $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
        $nestedData[]   =   $row["nama_bahan"];
        $nestedData[]   =   toRupiah($row["harga_jual"]);
        $nestedData[]   =   $row["total_qty"];
        $nestedData[]   =   $row['total'] === 0 ? 0 : toRupiah($row['total']).'/'.$row['nama_satuan'];

        $data[] = $nestedData; $i++;
      }
      $totalData = count($data);
      $json_data = array(
        "draw"            => intval( $requestData['draw'] ),
        "recordsTotal"    => intval( $totalData ),
        "recordsFiltered" => intval( $totalFiltered ),
        "data"            => $data
        );
      echo json_encode($json_data);
    }
  }
