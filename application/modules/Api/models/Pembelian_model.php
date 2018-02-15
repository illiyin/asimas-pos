<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pembelian_model extends CI_Model {
	
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
	
	public function cek_token($id_customer,$token) {
		
		$id_customer	= $this->db->escape_str($id_customer);
		$token			= $this->db->escape_str($token);
		
        $query = $this->db->query("select count(*) as total from mobile_token where id_user = '$id_customer' and token = '$token' and tipe = 1");
        return $query->first_row()->total;
    }
	
	public function get_supplier_produk() {
		
		$query = $this->db->query("SELECT * FROM `m_supplier_produk` WHERE deleted = 1");
        return $query->result();
		
    }
	
	public function get_purchase_order() {
		
		$query = $this->db->query("SELECT * FROM `t_purchase_order` WHERE deleted = 1");
        return $query->result();
		
    }
	
	public function get_produk_by_supplier($id_supplier) {
		
		$id_supplier	= $this->db->escape_str($id_supplier);
		
		$query = $this->db->query("SELECT * FROM `m_produk` WHERE id_supplier = '$id_supplier' and deleted = 1");
        return $query->result();
		
    }
	
	public function get_ukuran_produk($id_produk) {
		
		$id_produk		= $this->db->escape_str($id_produk);
		
		$query = $this->db->query("SELECT b.* FROM m_produk_det_ukuran a,m_produk_ukuran b WHERE a.id_ukuran = b.id and a.id_produk = '$id_produk'");
        return $query->result();
		
    }
	
	public function get_warna_produk($id_produk) {
		
		$id_produk		= $this->db->escape_str($id_produk);
		
		$query = $this->db->query("SELECT b.* FROM m_produk_det_warna a,m_produk_warna b WHERE a.id_warna = b.id and a.id_produk = '$id_produk'");
        return $query->result();
		
    }
	
	public function get_produk_by_sku_nama($param) {
		
		$param	= strtolower($this->db->escape_str($param));
		
		$query = $this->db->query("SELECT * FROM `m_produk` WHERE (lower(sku) like '%$param%' or lower(nama) like '%$param%') and deleted = 1");
        return $query->result();
		
    }
	
	public function get_metode_pembayaran() {
		
		$query = $this->db->query("SELECT * FROM `m_metode_pembayaran` WHERE deleted = 1");
        return $query->result();
		
    }

    public function insert_pembelian($data_pembelian) {

    	$insert_id = 0;
    	if(!empty($data_pembelian)) {
	        $this->db->insert('t_beli', $data_pembelian);
		
			$insert_id = $this->db->insert_id();
    	}
		return $insert_id;
        
    }

    public function insert_detail_pembelian($data_detail) {
		
		$max_id = 0;
		if(!empty($data_detail)) {
	        $this->db->insert_batch('t_beli_detail', $data_detail);
			
			$max_id = $this->db->query("select max(id) as id from t_beli_detail")->first_row()->id;
		}
		return $max_id;
        
    }
	
}