<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_gudang/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Gudangmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Gudangmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $data['list_prov'] = json_encode($this->Gudangmodel->select($dataSelect, 'm_provinsi', 'nama')->result());
        $data['list_kota'] = json_encode($this->Gudangmodel->select($dataSelect, 'm_kota', 'nama')->result());
        $sql = "SELECT m_gudang.*, m_kota.id AS id_kota, m_kota.nama AS nama_kota, m_provinsi.id AS id_provinsi, m_provinsi.nama AS nama_provinsi FROM m_gudang LEFT JOIN m_kota ON m_gudang.id_kota = m_kota.id LEFT JOIN m_provinsi ON m_kota.id_provinsi = m_provinsi.id WHERE m_gudang.deleted = '1' ORDER BY m_gudang.date_add DESC";
        $data['list'] = json_encode($this->Gudangmodel->rawQuery($sql)->result());
    	// $data['list'] = json_encode($this->Gudangmodel->select($dataSelect, 'm_gudang')->result());
		//echo $data;
		//print_r($data);
    	$this->load->view('Master_gudang/view', $data);
    }
	
	function test(){
		header('Content-Type: application/json; charset=utf-8');
		$dataSelect['deleted'] = 1;
		$list = $this->Gudangmodel->select($dataSelect, 'm_gudang')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
    function add(){
		$params = $this->input->post();
		$dataInsert['nama'] 			= $params['nama'];
		$dataInsert['alamat'] 			= $params['alamat'];
		$dataInsert['id_provinsi'] 		= $params['id_provinsi'];
		$dataInsert['id_kota'] 			= $params['id_kota'];
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
		$dataInsert['deleted'] 			= 1;

		$checkData = $this->Gudangmodel->select($dataInsert, 'm_gudang');
		if($checkData->num_rows() < 1){
			$insert = $this->Gudangmodel->insert($dataInsert, 'm_gudang');
			if($insert){
				$dataSelect['deleted'] = 1;
				$sql = "SELECT m_gudang.*, m_kota.id AS id_kota, m_kota.nama AS nama_kota, m_provinsi.id AS id_provinsi, m_provinsi.nama AS nama_provinsi FROM m_gudang LEFT JOIN m_kota ON m_gudang.id_kota = m_kota.id LEFT JOIN m_provinsi ON m_kota.id_provinsi = m_provinsi.id WHERE m_gudang.deleted = '1' ORDER BY m_gudang.date_add DESC";
                $list = $this->Gudangmodel->rawQuery($sql)->result();
				echo json_encode(array('status' => 3,'list' => $list));
			}else{
				echo json_encode(array('status' => 1));
			}
			
		}else{			
    		echo json_encode(array( 'status'=>1 ));
		}
    }
   
	
	function get($id = null){   	
    	if($id != null){
    		$dataSelect['id'] = $id;
    		$selectData = $this->Gudangmodel->select($dataSelect, 'm_gudang');
    		if($selectData->num_rows() > 0){
    			echo json_encode(
    				array(
    					'status'			=> 2,
    					'id'				=> $selectData->row()->id,
    					'nama'				=> $selectData->row()->nama,
    					'alamat'			=> $selectData->row()->alamat,
    					'id_provinsi'		=> $selectData->row()->id_provinsi,
    					'id_kota'			=> $selectData->row()->id_kota,
    				));
    		}else{
    			echo json_encode(array('status' => 1));
    		}
    	}else{
    		echo json_encode(array('status' => 0));
    	}
    }
	
    function edit(){
		$params = $this->input->post();
		$dataCondition['id']			= $params['id'];
		$dataUpdate['nama'] 			= $params['nama'];
		$dataUpdate['alamat'] 			= $params['alamat'];
		$dataUpdate['id_provinsi'] 		= $params['id_provinsi'];
		$dataUpdate['id_kota'] 			= $params['id_kota'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        
		$checkData = $this->Gudangmodel->select($dataCondition, 'm_gudang');
		if($checkData->num_rows() > 0){
			$update = $this->Gudangmodel->update($dataCondition, $dataUpdate, 'm_gudang');
			if($update){
				$dataSelect['deleted'] = 1;
				$sql = "SELECT m_gudang.*, m_kota.id AS id_kota, m_kota.nama AS nama_kota, m_provinsi.id AS id_provinsi, m_provinsi.nama AS nama_provinsi FROM m_gudang LEFT JOIN m_kota ON m_gudang.id_kota = m_kota.id LEFT JOIN m_provinsi ON m_kota.id_provinsi = m_provinsi.id WHERE m_gudang.deleted = '1' ORDER BY m_gudang.date_add DESC";
                $list = $this->Gudangmodel->rawQuery($sql)->result();
				echo json_encode(array('status' => '3','list' => $list));
			}else{
				echo json_encode(array( 'status'=>'2' ));
			}
		}else{			
    		echo json_encode(array( 'status'=>'1' ));
		}
    }
    function delete(){
		$id = $this->input->post("id");
    	if($id != null){
    		$dataCondition['id'] = $id;
    		$dataUpdate['deleted'] = 0;
    		$update = $this->Gudangmodel->update($dataCondition, $dataUpdate, 'm_gudang');
    		if($update){
    			$dataSelect['deleted'] = 1;
				$sql = "SELECT m_gudang.*, m_kota.id AS id_kota, m_kota.nama AS nama_kota, m_provinsi.id AS id_provinsi, m_provinsi.nama AS nama_provinsi FROM m_gudang LEFT JOIN m_kota ON m_gudang.id_kota = m_kota.id LEFT JOIN m_provinsi ON m_kota.id_provinsi = m_provinsi.id WHERE m_gudang.deleted = '1' ORDER BY m_gudang.date_add DESC";
                $list = $this->Gudangmodel->rawQuery($sql)->result();
				echo json_encode(array('status' => '3','list' => $list));
    		}else{
    			echo "1";
    		}
    	}else{
    		echo "0";
    	}
    }
    function buttonDelete($id=null){
    	if($id!=null){
    		echo "<button class='btn btn-danger' onclick='delRow(".$id.")'>YA</button>";
    	}else{
    		echo "NOT FOUND";
    	}
    }
    
   
    function get_kota(){
        $dataSelect['id_provinsi'] = $this->input->get("id_prov");
    	$dataSelect['deleted'] = 1;
    	echo json_encode($this->Gudangmodel->select($dataSelect, 'm_kota', 'nama')->result());
    }
    
}