<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Daftar_ctr extends MX_Controller {
    
    private $modul = "Api/";
    private $fungsi = "";    
    
    function __construct() {
        parent::__construct();
        $this->load->model('Daftar_model','m');
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
    
    public function get_provinsi() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        
        $status_api_key = $this->cek_api_key($os,$key);
        
        if($status_api_key == false){
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_provinsi();
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_kota() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        $id_provinsi    = $this->input->get("id_provinsi");
        
        $status_api_key = $this->cek_api_key($os,$key);
        
        if($status_api_key == false){
            $data['code'] = "0";
            $data['pesan'] = "Invalid API KEY!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_kota($id_provinsi);
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function get_customer_level() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->get("os");
        $key            = $this->input->get("key");
        
        $status_api_key = $this->cek_api_key($os,$key);
        
        if($status_api_key == false){
            $data['code'] = "0";
            $data['pesan'] = "Invalid API KEY!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $data['code'] = "1";
            $data['pesan'] = "Sukses!";
            $data['data'] = $this->m->get_customer_level();
            
            echo json_encode($data,JSON_PRETTY_PRINT);
                
        }
    }
    
    public function insert_customer() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->post("os");
        $key            = $this->input->post("key");
        $nama           = $this->input->post('nama');
        $alamat         = $this->input->post('alamat');
        $no_telp        = $this->input->post('no_telp');
        $email          = $this->input->post('email');
        $kode_pos       = $this->input->post('kode_pos');
        $ktp            = $this->input->post('ktp');
        $npwp           = $this->input->post('npwp');
        $nama_bank      = $this->input->post('nama_bank');
        $no_rekening    = $this->input->post('no_rekening');
        $rekening_an    = $this->input->post('rekening_an');
        $keterangan     = $this->input->post('keterangan');
        $sales          = $this->input->post('sales');
        $id_provinsi    = $this->input->post('id_provinsi');
        $id_kota        = $this->input->post('id_kota');
        $id_customer_level  = $this->input->post('id_customer_level');
        $password       = $this->input->post('password');
        
        // $os             = "android";
        // $key            = "pos2017";
        // $nama            = "bambang suloyo";
        // $alamat          = "jalan raya soekarno hatta";
        // $no_telp     = "08123456";
        // $email           = "bambang1@gmail.com";
        // $kode_pos        = "65163";
        // $ktp         = "111222333";
        // $npwp            = "918.121.3131";
        // $nama_bank       = "Mandiri";
        // $no_rekening = "818247182";
        // $rekening_an = "bambang suloyo";
        // $keterangan      = "-";
        // $sales           = "sales";
        // $id_provinsi = "1";
        // $id_kota     = "1";
        // $id_customer_level   = "1";
        // $password        = "admin";
        
        $status_api_key = $this->cek_api_key($os,$key);
        
        if($status_api_key == false){
            $data['code'] = "0";
            $data['pesan'] = "Invalid API KEY!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
            $cek_email = intval($this->m->cek_email($email));
            
            if( $cek_email > 0 ){
                
                $data['code'] = "0";
                $data['pesan'] = "Email sudah ada!";
                
                echo json_encode($data,JSON_PRETTY_PRINT);
            }else{
                $data['code'] = "1";
                $data['pesan'] = "Sukses!";
                
                $this->m->insert_customer($nama,$alamat,$no_telp,$email,$kode_pos,$ktp,$npwp,$nama_bank,$no_rekening,$rekening_an,$keterangan,$sales,$id_provinsi,$id_kota,$id_customer_level,hash("sha512",$password));
                
                echo json_encode($data,JSON_PRETTY_PRINT);
            }
            
        }
    }
    
}