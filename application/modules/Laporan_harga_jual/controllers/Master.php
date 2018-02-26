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
    	$this->load->view('Laporan_harga_jual/cetak');
    }
    function data(){
        $requestData= $_REQUEST;
        $sql = "SELECT * FROM tt_gudang_keluar WHERE deleted = 1";
        $query=$this->Laporanhargajualmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT ";
        $sql.="  m_bahan.nama AS nama_bahan,
                 m_bahan_kategori.nama AS kategori_bahan,
                 AVG(tt_gudang_keluar.harga_penjualan) AS harga_penjualan
                 FROM tt_gudang_keluar,m_bahan, m_bahan_kategori
                 WHERE m_bahan.id = tt_gudang_keluar.id_bahan 
                 AND m_bahan.id_kategori_bahan = m_bahan_kategori.id
                 AND tt_gudang_keluar.deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( m_bahan.nama LIKE '%".$requestData['search']['value']."%' )";
            // $sql.=" OR m_bahan.nama LIKE '%".$requestData['search']['value']."%' )";
        }
        $sql .= " GROUP BY tt_gudang_keluar.id_bahan";
        $query=$this->Laporanhargajualmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql .= " ORDER BY tt_gudang_keluar.date_added DESC";
        $query=$this->Laporanhargajualmodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_bahan"];
            $nestedData[]   =   $row["kategori_bahan"];
            $nestedData[]   =   "Rp".number_format($row['harga_penjualan'], 2, ',','.');

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
