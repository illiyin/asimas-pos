<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_produk_jadi/";
    private $fungsi = "";
    function __construct() {
        parent::__construct();
        $this->load->model('Masterprodukjadimodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Masterprodukjadimodel->insert($dataInsert, 't_log');
    }
    function index(){
        $sql = "SELECT 
            gm.id, bahan.nama AS nama_bahan, gm.no_so , gm.harga_pembelian,
            gm.expired_date, gudang.jumlah_masuk, kategori.nama
            FROM m_bahan bahan, tt_gudang_masuk gm, m_bahan_kategori kategori, tt_gudang gudang
            WHERE gudang.id_bahan = bahan.id 
            AND gm.id = gudang.id_gudang
            AND bahan.id_kategori_bahan = kategori.id
            AND kategori.nama LIKE '%produk jadi%'";
        $data['session_detail'] = pegawaiLevel($this->session->userdata('id_user_level'));
        $data['list_data'] = $this->Masterprodukjadimodel->rawQuery($sql)->result();
        $this->load->view('Master_produk_jadi/view', $data);
    }
    function data(){
      $requestData = $_REQUEST;
      $sql = "SELECT 
              gm.id, bahan.nama AS nama_bahan, gm.no_so , gm.harga_pembelian,
              gm.expired_date, gudang.jumlah_masuk, kategori.nama
              FROM m_bahan bahan, tt_gudang_masuk gm, m_bahan_kategori kategori, tt_gudang gudang
              WHERE gudang.id_bahan = bahan.id 
              AND gm.id = gudang.id_gudang
              AND bahan.id_kategori_bahan = kategori.id
              AND kategori.nama LIKE '%produk jadi%'";
      if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' )";
      }
      $query=$this->Masterprodukjadimodel->rawQuery($sql);
      $totalFiltered = $query->num_rows();
      
      $data = array(); $i = 0;
      foreach($query->result_array() as $row) {
        $nestedData     =   array();
        $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
        $nestedData[]   =   $row["nama_bahan"];
        $nestedData[]   =   $row["no_so"];
        $nestedData[]   =   toRupiah($row["harga_pembelian"]);
        $nestedData[]   =   date('d/m/Y', strtotime($row["expired_date"]));
        $nestedData[]   =   $row["jumlah_masuk"];
        $sumHarga       =   (float) $row['harga_pembelian'] * $row['jumlah_masuk'];
        $nestedData[]   =   $row['harga_pembelian'] === 0 ? 0 : toRupiah($sumHarga);
        $nestedData[]   =   '<a class="btn btn-sm btn-default text-center" style="display:block;" data-toggle="tooltip" data-placement="top" title="Ubah Harga" onclick="showAdd('.$row["id"].')"><i class="fa fa-pencil"></i></a>';

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
    function edit(){
      $params = $this->input->post();

      $dataCondition['id'] = $params['id'];

      $checkData = $this->Masterprodukjadimodel->select($dataCondition, 'tt_gudang_masuk');
      if($checkData->num_rows() > 0) {
        $dataUpdate['harga_pembelian'] = $params['harga'];
        $update = $this->Masterprodukjadimodel->update($dataCondition, $dataUpdate, 'tt_gudang_masuk');
        if($update) {
          $sql = "SELECT 
                  gm.id, bahan.nama AS nama_bahan, gm.no_so , gm.harga_pembelian,
                  gm.expired_date, gudang.jumlah_masuk, kategori.nama
                  FROM m_bahan bahan, tt_gudang_masuk gm, m_bahan_kategori kategori, tt_gudang gudang
                  WHERE gudang.id_bahan = bahan.id 
                  AND gm.id = gudang.id_gudang
                  AND bahan.id_kategori_bahan = kategori.id
                  AND kategori.nama LIKE '%produk jadi%'";
          $list = $this->Masterprodukjadimodel->rawQuery($sql)->result();
          echo json_encode(array('status' => '3','message' => 'Berhasil mengubah data!' , 'list' => $list));
        } else {
          echo json_encode(array( 'status'=>'2', 'message' => 'Something Error!' ));          
        }
      } else {
        echo json_encode(array( 'status'=>'1', 'message' => 'Data Produk Jadi Tidak ditemukan!'));
      }
    } 
}
