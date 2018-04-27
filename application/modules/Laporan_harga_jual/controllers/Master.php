<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_harga_jual/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanhargajualmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Laporanhargajualmodel->insert($dataInsert, 't_log');
    }
    function index(){
    	$this->load->view('Laporan_harga_jual/view');
    }
    function cetak(){
        $sql = "SELECT
                bahan.nama AS nama_bahan, kategori.nama AS nama_kategori,
                SUM(gk.harga_penjualan / gudang.jumlah_keluar) / COUNT(*) AS total
                FROM m_bahan bahan, tt_gudang_keluar gk, m_bahan_kategori kategori, tt_gudang gudang
                WHERE gudang.id_bahan = bahan.id AND bahan.id_kategori_bahan = kategori.id
                AND gudang.id_gudang = gk.id";
        $sql .= " GROUP BY gk.id_bahan";
        $sql .= " ORDER BY gk.date_added DESC";
        $query=$this->Laporanhargajualmodel->rawQuery($sql);
        $data['data_list'] = $query->result();
    	$this->load->view('Laporan_harga_jual/cetak', $data);
    }
    function data(){
        $requestData= $_REQUEST;
        $sql = "SELECT * FROM tt_gudang_keluar";
        $query=$this->Laporanhargajualmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT
                bahan.nama AS nama_bahan, kategori.nama AS nama_kategori,
                SUM(gk.harga_penjualan / gudang.jumlah_keluar) / COUNT(*) AS total
                FROM m_bahan bahan, tt_gudang_keluar gk, m_bahan_kategori kategori, tt_gudang gudang
                WHERE gudang.id_bahan = bahan.id AND bahan.id_kategori_bahan = kategori.id
                AND gudang.id_gudang = gk.id";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' )";
            // $sql.=" OR m_bahan.nama LIKE '%".$requestData['search']['value']."%' )";
        }
        $sql .= " GROUP BY gk.id_bahan";
        $query=$this->Laporanhargajualmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql .= " ORDER BY gk.date_added DESC";
        $query=$this->Laporanhargajualmodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_bahan"];
            $nestedData[]   =   $row["nama_kategori"];
            $nestedData[]   =   toRupiah($row['total']);

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
