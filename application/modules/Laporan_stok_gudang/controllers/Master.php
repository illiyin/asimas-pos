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
      $sql .= " bahan.nama AS nama_bahan , tbahan.jumlah_masuk, tbahan.jumlah_keluar , bahan.expired_date,
              tbahan.saldo_bulan_kemarin AS stok_awal , tbahan.saldo_bulan_sekarang AS stok_akhir, tbahan.tanggal,
              satuan.nama AS nama_satuan
              FROM m_bahan bahan, tt_bahan tbahan, m_satuan satuan
              WHERE bahan.deleted = 1 AND tbahan.id_bahan = bahan.id AND satuan.id = bahan.id_satuan";
      if( !empty($requestData['search']['value']) ) {
          $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' ";
          $sql.=" OR kategori.nama LIKE '%".$requestData['search']['value']."%' )";
      }
      $query=$this->Laporanstokgudangmodel->rawQuery($sql);
      $data['data_list'] = $query->result();
    $this->load->view('Laporan_stok_gudang/cetak', $data);
    }
    function data(){
      $requestData= $_REQUEST;
      $sql = "SELECT * FROM m_bahan, tt_bahan WHERE m_bahan.deleted = 1 AND m_bahan.id = tt_bahan.id_bahan";
      $query=$this->Laporanstokgudangmodel->rawQuery($sql);
      $totalData = $query->num_rows();
      $sql = "SELECT
              bahan.nama AS nama_bahan , tbahan.jumlah_masuk, tbahan.jumlah_keluar , bahan.expired_date,
              tbahan.saldo_bulan_kemarin AS stok_awal , tbahan.saldo_bulan_sekarang AS stok_akhir, tbahan.tanggal,
              satuan.nama AS nama_satuan
              FROM m_bahan bahan, tt_bahan tbahan, m_satuan satuan
              WHERE bahan.deleted = 1 AND tbahan.id_bahan = bahan.id AND satuan.id = bahan.id_satuan";
      if( !empty($requestData['search']['value']) ) {
          $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' ";
          $sql.=" OR kategori.nama LIKE '%".$requestData['search']['value']."%' )";
      }
      $totalFiltered = $query->num_rows();
      $sql.=" ORDER BY bahan.nama";
      $sql.= " LIMIT ".$requestData['start']." ,".$requestData['length']."";
      $query=$this->Laporanstokgudangmodel->rawQuery($sql);

      $data = array(); $i=0;
      foreach ($query->result_array() as $row) {
          $nestedData     =   array();
          $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
          $nestedData[]   =   $row["nama_bahan"];
          $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["nama_satuan"]."</span>";
          $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["stok_awal"]."</span>";
          $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["jumlah_masuk"]."</span>";
          $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["jumlah_keluar"]."</span>";
          $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["stok_akhir"]."</span>";
          $expiredDate = $row['expired_date'] == '0000-00-00' ? '-' : date('d/m/Y', strtotime($row["expired_date"]));
          $nestedData[]   =   "<span class='text-center' style='display:block;'>".$expiredDate."</span>";

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
