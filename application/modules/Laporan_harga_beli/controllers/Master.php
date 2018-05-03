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
        $sql = "SELECT bahan.nama AS nama_bahan, satuan.nama AS nama_satuan,
              SUM(gm.harga_pembelian) AS harga_jual,
              SUM(gudang.jumlah_masuk) AS total_qty,
              SUM(gm.harga_pembelian * gudang.jumlah_masuk) / SUM(gudang.jumlah_masuk) AS total
              FROM m_bahan bahan, tt_gudang_masuk gm, m_bahan_kategori kategori, m_satuan satuan, tt_gudang gudang
              WHERE gudang.id_bahan = bahan.id AND bahan.id_kategori_bahan = kategori.id
              AND bahan.id_satuan = satuan.id
              AND gudang.id_gudang = gm.id
              AND kategori.nama LIKE '%produk jadi%'";
        $sql .= " GROUP BY gudang.id_bahan";
        $query=$this->Laporanhargabelimodel->rawQuery($sql);
        $data['data_list'] = $query->result();
    	$this->load->view('Laporan_harga_beli/cetak', $data);
    }
    function data(){
      $requestData = $_REQUEST;
      $sql = "SELECT bahan.nama AS nama_bahan, satuan.nama AS nama_satuan,
              SUM(gm.harga_pembelian) AS harga_jual,
              SUM(gudang.jumlah_masuk) AS total_qty,
              SUM(gm.harga_pembelian * gudang.jumlah_masuk) / SUM(gudang.jumlah_masuk) AS total
              FROM m_bahan bahan, tt_gudang_masuk gm, m_bahan_kategori kategori, m_satuan satuan, tt_gudang gudang
              WHERE gudang.id_bahan = bahan.id AND bahan.id_kategori_bahan = kategori.id
              AND bahan.id_satuan = satuan.id
              AND gudang.id_gudang = gm.id
              AND kategori.nama LIKE '%produk jadi%'";
      if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' )";
      }

      $sql.= " GROUP BY gudang.id_bahan";
      $query=$this->Laporanhargabelimodel->rawQuery($sql);
      $totalFiltered = $query->num_rows();
      
      $data = array(); $i = 0;
      foreach($query->result_array() as $row) {
        $nestedData     =   array();
        $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
        $nestedData[]   =   $row["nama_bahan"];
        $nestedData[]   =   $row["total_qty"];
        $nestedData[]   =   $row['total'] === 0 ? 0 : toRupiah($row['total']).'/'.$row['nama_satuan'];
        $jumlahHpp = ($row["total"]) * ($row["total_qty"]);
        $nestedData[]   =   toRupiah($jumlahHpp);

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
