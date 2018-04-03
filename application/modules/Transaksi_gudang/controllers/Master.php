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
        $sql = "SELECT gm.id,
                gm.no_transaksi, gm.no_batch, gm.expired_date, gm.harga_pembelian AS harga,
                bahan.nama AS nama_bahan, bahan.kode_bahan, satuan.nama AS nama_satuan,
                gudang.stok_awal, gudang.jumlah_masuk, gudang.jumlah_keluar, gudang.stok_akhir,
                gudang.type, gudang.date_add
                FROM tt_gudang_masuk gm
                LEFT JOIN tt_gudang gudang ON gm.id = gudang.id_gudang
                LEFT JOIN m_bahan bahan ON bahan.id = gudang.id_bahan
                LEFT JOIN m_satuan satuan ON bahan.id_satuan = satuan.id";
                
        if( !empty($requestData['search']['value']) ) {
            $sql.=" WHERE ( bahan.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR bahan.kode_bahan LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_transaksi LIKE '%".$requestData['search']['value']."%' )";
        }

        $sql.= " UNION SELECT gm.id,
                gm.no_transaksi, gm.no_batch, gm.expired_date, gm.harga_penjualan AS harga,
                bahan.nama AS nama_bahan, bahan.kode_bahan, satuan.nama AS nama_satuan,
                gudang.stok_awal, gudang.jumlah_masuk, gudang.jumlah_keluar, gudang.stok_akhir,
                gudang.type, gudang.date_add
                FROM tt_gudang_keluar gm
                LEFT JOIN tt_gudang gudang ON gm.id = gudang.id_gudang
                LEFT JOIN m_bahan bahan ON bahan.id = gudang.id_bahan
                LEFT JOIN m_satuan satuan ON bahan.id_satuan = satuan.id";
                
        if( !empty($requestData['search']['value']) ) {
            $sql.=" WHERE ( bahan.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR bahan.kode_bahan LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_transaksi LIKE '%".$requestData['search']['value']."%' )";
        }

        $sql.= " GROUP BY no_transaksi ORDER BY date_add DESC";
        
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
            $nestedData[]   =   ($row['type'] == 1 ? 'Gudang Masuk' : 'Gudang Keluar');
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
