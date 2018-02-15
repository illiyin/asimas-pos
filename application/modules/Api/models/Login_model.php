<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login_model extends CI_Model {
	
	function __construct() {
        parent::__construct();
        $this->load->database();
    }
	
	public function cek_api_key($os,$key) {
		
		$os			= $this->db->escape_str($os);
		$key		= $this->db->escape_str($key);
        $query = $this->db->query("select count(*) as total from mobile_api_key where operating_system = '$os' and api_key = '$key'");
        return $query->first_row()->total;
		
    }
	
	public function cek_login($email,$password) {
		
		$email		= $this->db->escape_str($email);
		$password	= $this->db->escape_str($password);
		
		$query = $this->db->query("SELECT count(*) as total FROM `m_customer` WHERE email = '$email' and password = '$password' and deleted = 1");
        return $query->first_row()->total;
		
    }
	
	public function get_customer($email) {
		
		$email		= $this->db->escape_str($email);
		
		$query = $this->db->query("SELECT * FROM `m_customer` WHERE email = '$email' and deleted = 1");
        return $query->result();
		
    }
	
	public function insert_token($id_customer,$token) {
		
		$id_customer	= $this->db->escape_str($id_customer);
		$token			= $this->db->escape_str($token);
		
        $this->db->query("INSERT INTO `mobile_token`(`id_user`, `tipe`, `token`) VALUES ('$id_customer','1','$token')");
        
    }
	
}