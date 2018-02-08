<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Penjualan_ctr extends MX_Controller {
    
    private $modul = "Api/";
    private $fungsi = "";    
    
    function __construct() {
        parent::__construct();
        $this->load->model('Penjualan_model','m');
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
    
    public function get_penjualan_by_customer() {
        
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
            $data['data'] = $this->m->get_penjualan_by_customer($id_customer);
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_all_customer() {
        
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
            $data['data'] = $this->m->get_all_customer();
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_all_produk() {
        
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
            $data['data'] = $this->m->get_all_produk();
            
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

    public function get_stok_produk() {
        header("Content-type: application/json");

        $os             = $this->input->get('os');
        $key            = $this->input->get('key');
        $id_customer    = $this->input->get('id_customer');
        $token          = $this->input->get('token');
        $id_produk      = $this->input->get('id_produk');
        $id_ukuran      = $this->input->get('id_ukuran');
        $id_warna       = $this->input->get('id_warna');

        $status_api_key = $this->cek_api_key($os, $key);
        $status_token = $this->cek_token($id_customer, $token);

        if($status_api_key == false || $status_token == false) {
            $data['code'] = "0"; // code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY atau token!!";
            $data['data'] = "0";

            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_stok_produk($id_produk, $id_ukuran, $id_warna);

            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    }
    
    public function get_harga_produk() {
        
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
            $data['data'] = $this->m->get_harga_produk($id_produk);
            
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

    public function insert_penjualan() {
         
        header("Content-type: application/json");
        
        $os             = $this->input->post("os");
        $key            = $this->input->post("key");
        $id_customer    = $this->input->post("id_customer");
        $token          = $this->input->post("token");

        $status                 = 1; //antrian
        $id_metode_pembayaran   = $this->input->post('id_metode_pembayaran');
        $jenis_order            = $this->input->post('jenis_order');
        $catatan                = $this->input->post('catatan');
        $cash                   = $this->input->post('cash');
        $sum_harga_barang       = 0;
        $sum_potongan           = 0;
        $sum_qty                = 0;
        $sum_berat              = 0;
        $sum_profit             = 0;
        
        $id_produk              = $this->input->post('id_produk');
        $id_ukuran              = $this->input->post('id_ukuran');
        $id_warna               = $this->input->post('id_warna');
        $nama_ukuran            = $this->input->post('nama_ukuran');
        $nama_warna             = $this->input->post('nama_warna');
        $qty                    = $this->input->post('qty');
        $berat                  = $this->input->post('berat');
        $harga                  = $this->input->post('harga');
        $harga_normal           = $this->input->post('harga_normal');
        $harga_beli             = $this->input->post('harga_beli');
        $potongan               = $this->input->post('potongan');
        // $profit              = $this->input->post('profit');
        
        $status_api_key = $this->cek_api_key($os,$key);
        
        if($status_api_key == false) {
            $data['code'] = "0";
            $data['pesan'] = "Invalid API KEY!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        } else {    

            $order = array(); $detail = array(); $list_produk = array();
            if(!empty($id_produk)) {
                $list_produk             = explode("#", $id_produk);
                $list_ukuran             = explode("#", $id_ukuran);
                $list_warna              = explode("#", $id_warna);
                $list_nama_ukuran        = explode("#", $nama_ukuran);
                $list_nama_warna         = explode("#", $nama_warna);
                $list_qty                = explode("#", $qty);
                $list_berat              = explode("#", $berat);
                $list_harga              = explode("#", $harga);
                $list_harga_normal       = explode("#", $harga_normal);
                $list_harga_beli         = explode("#", $harga_beli);
                $list_potongan           = explode("#", $potongan);
                // $list_profit             = explode("#", $profit);

                //preparing array to insert into t_order_detail
                foreach ($list_produk as $key => $produk) {
                    $detail[$key]['id_produk']        = $produk;
                    $detail[$key]['id_ukuran']        = $list_ukuran[$key];
                    $detail[$key]['id_warna']         = $list_warna[$key];
                    $detail[$key]['nama_ukuran']      = $list_nama_ukuran[$key];
                    $detail[$key]['nama_warna']       = $list_nama_warna[$key];
                    $detail[$key]['jumlah']           = $list_qty[$key];
                    $detail[$key]['total_berat']      = $list_berat[$key] * $list_qty[$key];
                    $detail[$key]['total_harga']      = $list_harga[$key] * $list_qty[$key];
                    $detail[$key]['harga_jual']       = $list_harga[$key];
                    $detail[$key]['harga_jual_normal']= $list_harga_normal[$key];
                    $detail[$key]['harga_beli']       = $list_harga_beli[$key];
                    $detail[$key]['potongan']         = $list_potongan[$key];
                    $detail[$key]['total_potongan']   = $list_potongan[$key] * $list_qty[$key];
                    $detail[$key]['profit']           = ($list_harga[$key] - $list_harga_beli[$key]) * $list_qty[$key];

                    $sum_harga_barang   = $sum_harga_barang + $detail[$key]['total_harga'];
                    $sum_potongan       = $sum_potongan + $detail[$key]['total_potongan'];
                    $sum_qty            = $sum_qty + $detail[$key]['jumlah'];
                    $sum_berat          = $sum_berat + $detail[$key]['total_berat'];
                    $sum_profit          = $sum_profit + $detail[$key]['profit'];
                }

                //preparing array to insert into t_order
                $order['deleted']                = "1";
                $order['status']                 = $status;
                $order['id_customer']            = $id_customer;
                $order['id_metode_pembayaran']   = $id_metode_pembayaran;
                $order['jenis_order']            = $jenis_order;
                $order['catatan']                = $catatan;
                $order['cash']                   = $cash;
                $order['uang_kembali']           = $sum_harga_barang - $cash;
                $order['total_harga_barang']     = $sum_harga_barang;
                $order['grand_total']            = $sum_harga_barang;
                $order['total_potongan']         = $sum_potongan;
                $order['total_qty']              = $sum_qty;
                $order['total_berat']            = $sum_berat;
                $order['profit']                 = $sum_profit;
            }   
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = "0";
            
            $id_order = $this->m->insert_penjualan($order);
            $id_detail = 0;

            if(!empty($id_order)) {
                foreach ($detail as $key => $value) {
                    $detail[$key]['id_order'] = $id_order;
                }

                $data['data'] = $id_order;
                $id_detail = $this->m->insert_detail_penjualan($detail);
            }
            
            //get data produk where in list_produk
            $arr_produk = $this->m->get_some_produk($list_produk);
            
            //UPDATE STOK DI M_PRODUK
            $this->update_stok_produk($arr_produk, $detail);

            //INSERT HISTORI DI H_STOK_PRODUK JUGA
            $this->insert_histori_produk($id_detail, $detail, $arr_produk);
            
            echo json_encode($data,JSON_PRETTY_PRINT);

        }
    }

    private function update_stok_produk($arr_produk, $data_detail) {

        $produk = array();
        if(!empty($arr_produk)) {
            
            $detail_stok = array();
            foreach ($data_detail as $key => $detail) {

                //filtering data produk which has current id_produk
                $current_id = $detail['id_produk'];
                $current_produk = array_filter($arr_produk, function($i) use($current_id) {
                    return $i['id'] == $current_id;
                });

                if(empty($detail_stok[$current_id])) {
                    foreach ($current_produk as $key => $value) {
                        $detail_stok[$current_id] = $current_produk[$key]['detail_stok'];
                    }
                }

                //filtering data detail_produk which has current id_ukuran & id_warna
                $arr_detail = json_decode($detail_stok[$current_id], true);
                $current_detail_stok = array_filter($arr_detail, function($i) use($detail) {
                    return ( $i['id_ukuran'] == $detail['id_ukuran'] && $i['id_warna'] == $detail['id_warna'] );
                });
                
                $stok = 0;
                if(empty($current_detail_stok)) {
                    foreach ($current_produk as $key => $value) {
                        $stok = $current_detail_stok[$key]['stok'];
                    }
                }

                //building json detail_stok to update m_produk's detail_stok
                $detail_stok[$current_id] = $this->build_detail_stok($detail_stok[$current_id], $detail['id_warna'], $detail['id_ukuran'], $detail['nama_warna'], $detail['nama_ukuran'], $detail['jumlah']);

                //calculating product total stok from detail_stok
                $current_total_stok = $this->total_detail_stok($detail_stok[$current_id]);

                //preparing array for m_produk update_batch
                $produk[$current_id] = array(
                        'id'            => $current_id,
                        'stok'          => $current_total_stok, 
                        'detail_stok'   => $detail_stok[$current_id], 
                    );
            }

            $update_stok = $this->m->update_stok_produk($produk);
        }
    }

    private function insert_histori_produk($id_detail, $data_detail, $arr_produk) {

        $h_stok = array(); $produk = array(); $detail_stok = array(); 
        $total_stok = array();
        if(!empty($id_detail)) {

            //preparing array to insert into h_stok_produk
            foreach ($data_detail as $key => $detail) {

                $current_id = $detail['id_produk'];

                //filtering data produk which has current id_produk
                $current_produk = array_filter($arr_produk, function($i) use($current_id) {
                    return $i['id'] == $current_id;
                });
                if(empty($detail_stok[$current_id])) {
                    foreach ($current_produk as $key => $value) {
                        $detail_stok[$current_id] = $current_produk[$key]['detail_stok'];
                    }
                }

                //calculating product total stok from detail_stok
                if(empty($total_stok[$current_id])) {
                    $total_stok[$current_id] = $this->total_detail_stok($detail_stok[$current_id]);
                }
                $total_stok[$current_id] = $total_stok[$current_id] - $detail['jumlah'];

                $keterangan = "Stok berkurang ". $detail['jumlah'] ." dari transaksi penjualan dengan ID ".$id_detail[$key]; 
                
                $h_stok[$key]['deleted']            = "1";
                $h_stok[$key]['status']             = "1"; //berkurang dari penjualan
                $h_stok[$key]['keterangan']         = $keterangan;
                $h_stok[$key]['id_produk']          = $detail['id_produk'];
                $h_stok[$key]['id_order_detail']    = $id_detail[$key];
                $h_stok[$key]['jumlah']             = $detail['jumlah'];
                $h_stok[$key]['stok_akhir']         = $total_stok[$current_id];
                $h_stok[$key]['id_ukuran']          = $detail['id_ukuran'];
                $h_stok[$key]['id_warna']           = $detail['id_warna'];
            }

            $id_h_stok = $this->m->insert_histori_produk($h_stok);
        }
    }

    private function build_detail_stok($detail_stok, $id_warna=0, $id_ukuran=0, $nama_warna, $nama_ukuran, $qty) {
        //build new detail_stok json data
        $result = 0;
        if(!empty($detail_stok)) {
            $obj_data = json_decode($detail_stok);
            $arr_data = json_decode($detail_stok, true);
            $new_stok = 0;
            end($arr_data); $index = (key($arr_data) + 1);
            $new_detail_stok = array();

            foreach ($obj_data as $key => $value) {
                if(($value->id_warna == $id_warna) && ($value->id_ukuran == $id_ukuran)) {
                    $new_stok = ($arr_data[$key]['stok'] - $qty);
                    $index = $key;
                }
            }
            $arr_data[$index] = array(
                    'id_warna' => $id_warna,
                    'id_ukuran' => $id_ukuran,
                    'nama_warna' => $nama_warna,
                    'nama_ukuran' => $nama_ukuran,
                    'stok' => $new_stok
                );
            $result = json_encode($arr_data);
        }
        return $result;
    }

    private function total_detail_stok($detail_stok) {
        //find total stok of current product
        $result = 0;
        if(!empty($detail_stok)) {
            $obj_data = json_decode($detail_stok);
            $total_stok = 0;
            foreach ($obj_data as $item) {
                $total_stok = (int)$total_stok + (int)$item->stok;
            }
            $result = $total_stok;
        }
        return $result;
    }

    public function test_update_stok_produk() {
        $list_produk = array(18,2);
        $arr_produk = $this->m->get_some_produk($list_produk);
        $data_detail = array(
                            array(
                                'id_produk'        => 18,
                                'id_ukuran'        => 4,
                                'id_warna'         => 2,
                                'nama_ukuran'      => "kilo",
                                'nama_warna'       => "Biru",
                                'jumlah'           => 1
                                ),
                            array(
                                'id_produk'        => 2,
                                'id_ukuran'        => 1,
                                'id_warna'         => 3,
                                'nama_ukuran'      => "senti",
                                'nama_warna'       => "ijo",
                                'jumlah'           => 3
                                ),
                        );

        $this->update_stok_produk($arr_produk, $data_detail);
    }

    public function test_insert_histori_produk() {
        $list_produk = array(18,2);
        $id_detail = array(0, 1);
        $arr_produk = $this->m->get_some_produk($list_produk);
        $data_detail = array(
                            array(
                                'id_produk'        => 18,
                                'id_ukuran'        => 4,
                                'id_warna'         => 2,
                                'nama_ukuran'      => "kilo",
                                'nama_warna'       => "Biru",
                                'jumlah'           => 1
                                ),
                            array(
                                'id_produk'        => 2,
                                'id_ukuran'        => 1,
                                'id_warna'         => 3,
                                'nama_ukuran'      => "senti",
                                'nama_warna'       => "ijo",
                                'jumlah'           => 1
                                ),
                        );

        $this->insert_histori_produk($id_detail, $data_detail, $arr_produk);
    }
    
}