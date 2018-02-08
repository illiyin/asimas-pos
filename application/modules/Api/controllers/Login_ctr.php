<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login_ctr extends MX_Controller {
    
	private $modul = "Api/";
    private $fungsi = "";    
	
	function __construct() {
        parent::__construct();
        $this->load->model('Login_model','m');
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
	
	public function generate_token($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
	
	public function login() {
        
        header("Content-type: application/json");
        
        $os             = $this->input->post("os");
        $key            = $this->input->post("key");
		$email			= $this->input->post("email");
		$password		= $this->input->post("password");
		
		$os             = "android";
        $key            = "pos2017";
		$email			= "bambang1@gmail.com";
		$password		= "admin";
        
        $status_api_key = $this->cek_api_key($os,$key);
        
        if($status_api_key == false){
            $data['code'] = "0";// code 0 artinya gagal, code 1 artinya sukses
            $data['pesan'] = "Invalid API KEY!!";
            $data['data'] = "0";

            echo json_encode($data,JSON_PRETTY_PRINT);
        }else{
            
			$cek_email = $this->m->cek_login($email,hash("sha512",$password));
			
			if( $cek_email == "0" ){
				$data['code'] = "0";
				$data['token'] = "0";
				$data['pesan'] = "Email atau password yang anda masukkan salah, atau data customer anda dinonaktifkan oleh admin";
				$data['data'] = "0";
				
				echo json_encode($data,JSON_PRETTY_PRINT);
			}else{
				
				$token = $this->generate_token(40);
				$data_customer = $this->m->get_customer($email);
				$this->m->insert_token($data_customer[0]->id,$token);
				
				$data['code'] = "1";
				$data['token'] = $token;
				$data['pesan'] = "Sukses login!";
				$data['data'] = $data_customer;
				
				echo json_encode($data,JSON_PRETTY_PRINT);
			}
			    
        }
    }
	
}