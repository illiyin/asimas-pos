<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksipenjualanmodel extends CI_Model {
	private $table_prefix = "";

	public function get($table){
		$this->load->database();
		$result = $this->db->get($this->table_prefix."".$table);
		$this->db->close();
		return $result;
	}
	public function like($condition = 'null', $like = 'null', $table){
		$this->load->database();
		if($condition != 'null'){
			$this->db->where($condition);
		}
		if($like != 'null'){
			$this->db->like($like);
		}
		$result = $this->db->get($this->table_prefix."".$table);
		$this->db->close();
		return $result;
	}	
	public function select($condition, $table, $order_by="", $sort="ASC"){
		$this->load->database();
		$this->db->where($condition);
		$this->db->order_by($order_by, $sort);
		$result = $this->db->get($this->table_prefix."".$table);
		$this->db->close();
		return $result;
	}
	public function insert($data, $table){
		$this->load->database();
		$result = $this->db->insert($this->table_prefix."".$table, $data);
		$this->db->close();
		return $result;
	}
	public function insert_id($data, $table){
		$this->load->database();
		$result = $this->db->insert($this->table_prefix."".$table, $data);
		$insert_id = $this->db->insert_id();
		$this->db->close();
		return $insert_id;
	}
	public function update($condition, $data, $table){
		$this->load->database();
		$this->db->where($condition);
		$result = $this->db->update($this->table_prefix."".$table, $data);
		$this->db->close();
		return $result;
	}
	public function delete($condition, $table){
		$this->load->database();
		$this->db->where($condition);
		$result = $this->db->delete($this->table_prefix."".$table);
		$this->db->close();
		return $result;
	}
	public function rawQuery($query){
		$this->load->database();
		$result = $this->db->query($query);
		$this->db->close();
		return $result;
	}
	public function haveIn($field, $data, $condition, $table){
		$this->load->database();
		if($condition != null){
			$this->db->where($condition);
		}
		$this->db->where_in($field, $data);
		$result = $this->db->get($this->table_prefix."".$table);
		$this->db->close();
		return $result;
	}
	public function haveNotIn($field, $data, $condition, $table){
		$this->load->database();
		if($condition != null){
			$this->db->where($condition);
		}
		$this->db->where_not_in($field, $data);
		$result = $this->db->get($this->table_prefix."".$table);
		$this->db->close();
		return $result;
	}
}