<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pembelian_ctr extends MX_Controller {
    
    private $modul = "Api/";
    private $fungsi = "";    
    
    function __construct() {
        parent::__construct();
        $this->load->model('Pembelian_model','m');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        
    }
    
    private function cek_api_key($os,$key){

        $status = $this->m->cek_api_key($os,$key);

        if($status == "0"){
                return false;
        }else{
                return true;
        }
    }
    
    private function cek_token($id_customer,$token){
        
        $status = $this->m->cek_token($id_customer,$token);

        if($status == "0"){
                return false;
        }else{
                return true;
        }
    }
    
    public function get_supplier_produk() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        $id_customer    = $this->input->get("id_customer");
        $token          = $this->input->get("token");
        
        $status_api_key = $this->cek_api_key($os,$key);
        $status_token = $this->cek_token($id_customer,$token);
        
        if($status_api_key == false || $status_token == false){
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY atau token!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_supplier_produk();
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_purchase_order() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        $id_customer    = $this->input->get("id_customer");
        $token          = $this->input->get("token");
        
        $status_api_key = $this->cek_api_key($os,$key);
        $status_token = $this->cek_token($id_customer,$token);
        
        if($status_api_key == false || $status_token == false){
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY atau token!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_purchase_order();
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_produk_by_supplier() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        $id_customer    = $this->input->get("id_customer");
        $token          = $this->input->get("token");
        $id_supplier    = $this->input->get("id_supplier");
        
        $status_api_key = $this->cek_api_key($os,$key);
        $status_token = $this->cek_token($id_customer,$token);
        
        if($status_api_key == false || $status_token == false){
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY atau token!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_produk_by_supplier($id_supplier);
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_ukuran_produk() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        $id_customer    = $this->input->get("id_customer");
        $token          = $this->input->get("token");
        $id_produk      = $this->input->get("id_produk");
        
        $status_api_key = $this->cek_api_key($os,$key);
        $status_token = $this->cek_token($id_customer,$token);
        
        if($status_api_key == false || $status_token == false){
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY atau token!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_ukuran_produk($id_produk);
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_warna_produk() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        $id_customer    = $this->input->get("id_customer");
        $token          = $this->input->get("token");
        $id_produk      = $this->input->get("id_produk");
        
        $status_api_key = $this->cek_api_key($os,$key);
        $status_token = $this->cek_token($id_customer,$token);
        
        if($status_api_key == false || $status_token == false){
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY atau token!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_warna_produk($id_produk);
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_produk_by_sku_nama() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        $id_customer    = $this->input->get("id_customer");
        $token          = $this->input->get("token");
        $param          = $this->input->get("param");
        
        $status_api_key = $this->cek_api_key($os,$key);
        $status_token = $this->cek_token($id_customer,$token);
        
        if($status_api_key == false || $status_token == false){
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY atau token!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_produk_by_sku_nama($param);
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_metode_pembayaran() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        $id_customer    = $this->input->get("id_customer");
        $token          = $this->input->get("token");
        
        $status_api_key = $this->cek_api_key($os,$key);
        $status_token = $this->cek_token($id_customer,$token);
        
        if($status_api_key == false || $status_token == false){
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY atau token!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_metode_pembayaran();
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }

    public function insert_pembelian() {

        header("Content-type: application/json");

        $os             = $this->input->post("os");
        $key            = $this->input->post("key");
        $id_customer    = $this->input->post("id_customer");
        $token          = $this->input->post("token");
        
        $id_supplier            = $this->input->post("id_supplier");
        $id_purchase_order      = $this->input->post("id_purchase_order");
        $id_metode_pembayaran   = $this->input->post("id_metode_pembayaran");
        $catatan                = $this->input->post("catatan");
        $cash                   = $this->input->post("cash");
        $uang_kembali           = $this->input->post("uang_kembali");
        $sum_qty                = 0;
        $sum_berat              = 0;
        $sum_harga_beli         = 0;

        $id_produk      = $this->input->post("id_produk");
        $id_ukuran      = $this->input->post("id_ukuran");
        $nama_ukuran    = $this->input->post("nama_ukuran");
        $id_warna       = $this->input->post("id_warna");
        $nama_warna     = $this->input->post("nama_warna");
        $qty            = $this->input->post("qty");
        $berat          = $this->input->post("berat");
        $harga_beli     = $this->input->post("harga_beli");

        $status_api_key = $this->cek_api_key($os,$key);
        $status_token = $this->cek_token($id_customer,$token);
        
        if($status_api_key == false || $status_token == false) {
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY atau token!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        } else {

            $pembelian = array(); $detail = array();
            if(!empty($id_produk)) {
                $list_produk             = explode("#", $id_produk);
                $list_ukuran             = explode("#", $id_ukuran);
                $list_nama_ukuran        = explode("#", $nama_ukuran);
                $list_warna              = explode("#", $id_warna);
                $list_nama_warna         = explode("#", $nama_warna);
                $list_qty                = explode("#", $qty);
                $list_berat              = explode("#", $berat);
                $list_harga_beli         = explode("#", $harga_beli);

                //preparing array to insert into t_beli_detail
                foreach ($list_produk as $key => $produk) {
                   $detail[$key]['id_produk']   = $produk;
                   $detail[$key]['id_ukuran']   = $list_ukuran[$key];
                   $detail[$key]['nama_ukuran'] = $list_nama_ukuran[$key];
                   $detail[$key]['id_warna']    = $list_warna[$key];
                   $detail[$key]['nama_warna']  = $list_nama_warna[$key];
                   $detail[$key]['jumlah']      = $list_qty[$key];
                   $detail[$key]['harga_beli']  = $list_harga_beli[$key];
                   $detail[$key]['total_berat'] = $list_berat[$key] * $list_qty[$key];
                   $detail[$key]['total_harga'] = $list_harga_beli[$key] * $list_qty[$key];

                   $sum_qty         = $sum_qty + $list_qty[$key];
                   $sum_berat       = $sum_berat + $list_berat[$key];
                   $sum_harga_beli  = $sum_harga_beli + $list_harga_beli[$key];
                }

                //preparing array to insert into t_beli
                $pembelian = array(
                        'deleted'               => "1",
                        'id_supplier'           => $id_supplier,
                        'id_purchase_order'     => $id_purchase_order,
                        'id_metode_pembayaran'  => $id_metode_pembayaran,
                        'total_qty'             => $sum_qty,
                        'total_berat'           => $sum_berat,
                        'total_harga_beli'      => $sum_harga_beli,
                        'cash'                  => $cash,
                        'uang_kembali'          => $uang_kembali,
                        'catatan'               => $catatan
                    );
            }
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = "0";

            $id_pembelian = $this->m->insert_pembelian($pembelian);

            if(!empty($id_pembelian)) {
                foreach ($detail as $key => $value) {
                    $detail[$key]['id_beli'] = $id_pembelian;
                }
                
                $data['data'] = $this->m->insert_detail_pembelian($detail);
            }

            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }   
    }
    
}