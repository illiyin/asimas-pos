<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Transaksi_gudang/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksigudangmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Transaksigudangmodel->insert($dataInsert, 't_log');
    }
    function index(){
    	$this->load->view('Transaksi_gudang/view');
    }
    function cetak(){
    	$this->load->view('Transaksi_gudang/cetak');
    }
    function data() {
        $requestData= $_REQUEST;
        $sql = "SELECT 
                gm.no_transaksi, bahan.nama AS nama_bahan, bahan.kode_bahan, satuan.nama AS nama_satuan, 
                tbahan.saldo_bulan_kemarin AS stok_awal, tbahan.saldo_bulan_sekarang AS stok_akhir,
                tbahan.jumlah_masuk, tbahan.jumlah_keluar, gm.no_batch, 
                gm.expired_date, 1 AS table_status, gm.date_add AS tanggal, gm.harga_pembelian AS harga
                FROM tt_gudang_masuk gm, m_bahan bahan, m_satuan satuan, tt_bahan tbahan
                WHERE gm.id_bahan = bahan.id AND bahan.id_satuan = satuan.id
                AND gm.id_bahan = tbahan.id_bahan AND gm.deleted = 1
                UNION
                SELECT
                gm.no_transaksi, bahan.nama AS nama_bahan, bahan.kode_bahan, satuan.nama AS nama_satuan, 
                tbahan.saldo_bulan_kemarin AS stok_awal, tbahan.saldo_bulan_sekarang AS stok_akhir,
                tbahan.jumlah_masuk, tbahan.jumlah_keluar, gm.no_batch, 
                gm.expired_date, 2 AS table_status, gm.date_added AS tanggal, gm.harga_penjualan AS harga
                FROM tt_gudang_keluar gm, m_bahan bahan, m_satuan satuan, tt_bahan tbahan
                WHERE gm.id_bahan = bahan.id AND bahan.id_satuan = satuan.id
                AND gm.deleted = 1
                GROUP BY no_transaksi
                ORDER BY tanggal DESC";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama_barang LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_batch LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Transaksigudangmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            // $nestedData[]   =   "<td data-search='AsdSDasd'><span class='text-center' style='display:block;'>".($i+1)."</span></td>";
            $nestedData[]   =   $row['no_transaksi'];
            $nestedData[]   =   $row['nama_bahan'];
            $nestedData[]   =   $row['kode_bahan'];
            $nestedData[]   =   $row['nama_satuan'];
            $nestedData[]   =   $row['stok_awal'];
            $nestedData[]   =   $row['jumlah_masuk'];
            $nestedData[]   =   $row['jumlah_keluar'];
            $nestedData[]   =   $row['stok_akhir'];
            $nestedData[]   =   $row['no_batch'];
            $nestedData[]   =   date('d/m/Y' , strtotime($row['expired_date']));
            $nestedData[]   =   ($row['table_status'] == 1 ? 'Gudang Masuk' : 'Gudang Keluar');
            $nestedData[]   =   toRupiah($row['harga']);

            $data[] = $nestedData; $i++;
        }
        $totalData = count($data);
        $json_data = array(
                    "draw"            => intval( $requestData['draw'] ),
                    "recordsTotal"    => intval( $totalData ),
                    "recordsFiltered" => intval( 0 ),
                    "data"            => $data
                    );
        echo json_encode($json_data);
      }
}
