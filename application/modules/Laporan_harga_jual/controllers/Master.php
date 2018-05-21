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
    function testQuery(){
        $sql = "SELECT gudang.id, gudang.id_bahan, 
                gudang.jumlah_masuk, gudang.stok_akhir,
                gm.harga_pembelian 
                FROM tt_gudang gudang, tt_gudang_masuk gm
                WHERE type = 1 AND gudang.id_bahan = 1
                AND gm.id = gudang.id_gudang
                LIMIT 3";
        $query = $this->Laporanhargajualmodel->rawQuery($sql);
        
        $formula1 = null;
        $formula2 = null;    

        foreach($query->result() as $row) {
            // = ( (JmlMasuk 1 * HP 1) + (JmlMasuk 2 * HP 2) + (JmlMasuk 3 * HP 3) ) / (JmlMasuk 1 + JmlMasuk 2 + JmlMasuk 3)
            // = ((1.000 x Rp. 500) + (800 x Rp. 550) + (700 x Rp. 600)) / (1000 + 800 + 700)
            // = (Rp. 500.000 + Rp. 440.000 + Rp. 420.000) / 2.500  = Rp. 544
            $formula1 += (($row->jumlah_masuk) * ($row->harga_pembelian));
            $formula2 += $row->jumlah_masuk;
        }

        $rata = ($formula1) / ($formula2);

        echo toRupiah(($rata) * ($formula2));
    }
  }
