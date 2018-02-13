<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Daftar_model extends CI_Model {
	
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
	
	public function get_provinsi() {
		
		$query = $this->db->query("SELECT * FROM `m_provinsi` WHERE deleted = 1");
        return $query->result();
		
    }
	
	public function get_kota($id_provinsi) {
		
		$id_provinsi	= $this->db->escape_str($id_provinsi);
		
		$query = $this->db->query("SELECT * FROM `m_kota` WHERE id_provinsi = '$id_provinsi' and deleted = 1");
        return $query->result();
		
    }
	
	public function get_customer_level() {
		
		$query = $this->db->query("SELECT * FROM `m_customer_level` WHERE deleted = 1");
        return $query->result();
		
    }
	
	public function cek_email($email) {
		
		$email		= $this->db->escape_str($email);
	
		$query = $this->db->query("select count(*) as total from m_customer where email = '$email'");
        return $query->first_row()->total;
		
    }
	
	public function insert_customer($nama,$alamat,$no_telp,$email,$kode_pos,$ktp,$npwp,$nama_bank,$no_rekening,$rekening_an,$keterangan,$sales,$id_provinsi,$id_kota,$id_customer_level,$password) {
		
		$nama			= $this->db->escape_str($nama);
		$alamat			= $this->db->escape_str($alamat);
		$no_telp		= $this->db->escape_str($no_telp);
		$email			= $this->db->escape_str($email);
		$kode_pos		= $this->db->escape_str($kode_pos);
		$ktp			= $this->db->escape_str($ktp);
		$npwp			= $this->db->escape_str($npwp);
		$nama_bank		= $this->db->escape_str($nama_bank);
		$no_rekening	= $this->db->escape_str($no_rekening);
		$rekening_an	= $this->db->escape_str($rekening_an);
		$keterangan		= $this->db->escape_str($keterangan);
		$sales			= $this->db->escape_str($sales);
		$id_provinsi	= $this->db->escape_str($id_provinsi);
		$id_kota		= $this->db->escape_str($id_kota);
		$id_customer_level	= $this->db->escape_str($id_customer_level);
		$password		= $this->db->escape_str($password);
		
        $this->db->query("INSERT INTO `m_customer`(`nama`, `alamat`, `no_telp`, `email`, `kode_pos`, `ktp`, `npwp`, `nama_bank`, `no_rekening`, `rekening_an`, `keterangan`, `sales`, `id_provinsi`, `id_kota`, `id_customer_level`, `date_add`, `last_edited`, `add_by`, `edited_by`, `deleted`,`password`) 
		VALUES('$nama','$alamat','$no_telp','$email','$kode_pos','$ktp','$npwp','$nama_bank','$no_rekening','$rekening_an','$keterangan','$sales','$id_provinsi','$id_kota','$id_customer_level',now(),now(),0,0,2,'$password')");
		
		$max_id = $this->db->query("select max(id) as id from m_customer")->first_row()->id;
		return $max_id;
        
    }
	
	public function insert_token($id_customer,$token) {
		
		$id_customer	= $this->db->escape_str($id_customer);
		$token			= $this->db->escape_str($token);
		
        $this->db->query("INSERT INTO `mobile_token`(`id_user`, `tipe`, `token`) VALUES ('$id_customer','1','$token')");
        
    }
	
}