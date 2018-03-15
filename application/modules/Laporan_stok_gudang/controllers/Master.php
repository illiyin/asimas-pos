<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_stok_gudang/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanstokgudangmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Laporanstokgudangmodel->insert($dataInsert, 't_log');
    }
    function index(){
    	$this->load->view('Laporan_stok_gudang/view');
    }
    function cetak(){
        $sql = "SELECT ";
        $sql .= "m_barang.nama_barang,
                m_bahan_kategori.nama AS nama_kategori,
                m_barang.stok_akhir
                FROM m_barang, m_bahan_kategori WHERE m_barang.deleted = 1 AND m_barang.id_kategori_bahan = m_bahan_kategori.id";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( m_barang.nama_barang LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR m_bahan_kategori.nama LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Laporanstokgudangmodel->rawQuery($sql);
        $data['data_list'] = $query->result();
    	$this->load->view('Laporan_stok_gudang/cetak', $data);
    }
    function data(){
        $requestData= $_REQUEST;
        $sql = "SELECT * FROM tt_bahan";
        $query=$this->Laporanstokgudangmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT
                bahan.nama AS nama_bahan , kategori.nama AS nama_kategori,
                tbahan.saldo_bulan_kemarin AS stok_awal , tbahan.saldo_bulan_sekarang AS stok_akhir, tbahan.tanggal
                FROM m_bahan bahan, m_bahan_kategori kategori , tt_bahan tbahan
                WHERE bahan.id_kategori_bahan = kategori.id AND tbahan.id_bahan = bahan.id";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR kategori.nama LIKE '%".$requestData['search']['value']."%' )";
        }
        $totalFiltered = $query->num_rows();
        $query=$this->Laporanstokgudangmodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_bahan"];
            $nestedData[]   =   $row["nama_kategori"];
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["stok_awal"]."</span>";
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["stok_akhir"]."</span>";
            $nestedData[]   =   date('M, Y', strtotime($row["tanggal"]));

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
