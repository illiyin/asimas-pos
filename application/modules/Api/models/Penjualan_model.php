<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Penjualan_model extends CI_Model {
	
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
	
	public function get_penjualan_by_customer($id_customer) {
		
		$id_customer	= $this->db->escape_str($id_customer);
		
		$query = $this->db->query("SELECT * FROM `t_order` WHERE id_customer = '$id_customer' and deleted = 1 order by date_add desc");
        return $query->result();
		
    }
	
	public function get_all_customer() {
		
		$query = $this->db->query("SELECT * FROM `m_customer` WHERE deleted = 1");
        return $query->result();
		
    }
	
	public function get_all_produk() {
		
		$query = $this->db->query("SELECT * FROM `m_produk` WHERE deleted = 1");
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

    public function get_stok_produk($id_produk, $id_ukuran, $id_warna) {

    	$result = array();
    	$id_produk = $this->db->escape_str($id_produk);
    	$id_ukuran = $this->db->escape_str($id_ukuran);
    	$id_warna = $this->db->escape_str($id_warna);

    	$query = $this->db->query("SELECT * FROM m_produk WHERE id = '$id_produk'");
        
        $data = $query->row();
        $obj_data = !empty($data) ? json_decode($data->detail_stok) : '';
        
        if(!empty($obj_data)) {
	        foreach ($obj_data as $item) {
	            if(($item->id_ukuran == $id_ukuran) && ($item->id_warna == $id_warna)) {
	                $result = $item;
	                $result->id_produk = $data->id;
	                $result->nama_produk = $data->nama;
	            }
	        }
        }
        return $result;

    }
	
	public function get_harga_produk($id_produk) {
		
		$id_produk		= $this->db->escape_str($id_produk);
		
		$query = $this->db->query("SELECT a.*,b.nama as nama_customer_level from m_produk_det_harga a, m_customer_level b where a.id_customer_level = b.id and a.id_produk = '$id_produk'");
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

    public function insert_penjualan($data_penjualan) {

    	$insert_id = 0;
    	if(!empty($data_penjualan)) {
	        $this->db->insert('t_order', $data_penjualan);
		
			$insert_id = $this->db->insert_id();
    	}
		return $insert_id;
        
    }

    public function insert_detail_penjualan($data_detail) {
		
		$insert_ids = array();
		if(!empty($data_detail)) {
	        $this->db->insert_batch('t_order_detail', $data_detail);

	        $count_data = count($data_detail);
	        $first_id 	= $this->db->insert_id();
	        $last_id 	= $first_id + ($count_data - 1);
	        
	        for ($i=$first_id; $i<=$last_id; $i++) { 
	        	array_push($insert_ids, $i);
	        }
		}
		return $insert_ids;
        
    }

    public function insert_histori_produk($data_histori) {
		
		$insert_ids = array();
		if(!empty($data_histori)) {
	        $this->db->insert_batch('h_stok_produk', $data_histori);

	        $count_data = count($data_histori);
	        $first_id 	= $this->db->insert_id();
	        $last_id 	= $first_id + ($count_data - 1);
	        
	        for ($i=$first_id; $i<=$last_id; $i++) { 
	        	array_push($insert_ids, $i);
	        }
		}
		return $insert_ids;
        
    }

    public function update_stok_produk($data_produk) {
		
		$result = 0;
		if(!empty($data_produk)) {
	        $result = $this->db->update_batch('m_produk', $data_produk, 'id');
		}
		return $result;
        
    }

    public function get_some_produk($arr_id_produk) {
		
		$result = array();
		if(!empty($arr_id_produk)) {
			$this->db->where('deleted', '1');
			$this->db->where_in('id', $arr_id_produk);
			$result = $this->db->get('m_produk')->result_array();

		}
        return $result;

    }
	
}