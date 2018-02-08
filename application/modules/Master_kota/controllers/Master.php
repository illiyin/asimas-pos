<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_kota/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Kotamodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Kotamodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $data['list_prov'] = json_encode($this->Kotamodel->select($dataSelect, 'm_provinsi', 'nama')->result());

        $sql = "SELECT m_kota.*, m_provinsi.id AS id_provinsi, m_provinsi.nama AS nama_provinsi FROM m_kota LEFT JOIN m_provinsi ON m_kota.id_provinsi = m_provinsi.id WHERE m_kota.deleted = '1' ORDER BY m_kota.date_add DESC";
        $data['list'] = json_encode($this->Kotamodel->rawQuery($sql)->result());
    	// $data['list'] = json_encode($this->Kotamodel->select($dataSelect, 'm_kota')->result());
		//echo $data;
		//print_r($data);
    	$this->load->view('Master_kota/view', $data);
    }
	
	function test(){
		header('Content-Type: application/json; charset=utf-8');
		$dataSelect['deleted'] = 1;
		$list = $this->Kotamodel->select($dataSelect, 'm_kota')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
    function add(){
		$params = $this->input->post();
		$dataInsert['nama'] 			= $params['nama'];
		$dataInsert['id_provinsi'] 		= $params['id_provinsi'];
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
		$dataInsert['deleted'] 			= 1;
        
		$checkData = $this->Kotamodel->select($dataInsert, 'm_kota');
		if($checkData->num_rows() < 1){
			$insert = $this->Kotamodel->insert($dataInsert, 'm_kota');
			if($insert){
				$dataSelect['deleted'] = 1;
                $sql = "SELECT m_kota.*, m_provinsi.id AS id_provinsi, m_provinsi.nama AS nama_provinsi FROM m_kota LEFT JOIN m_provinsi ON m_kota.id_provinsi = m_provinsi.id WHERE m_kota.deleted = '1' ORDER BY m_kota.date_add DESC";
				$list = $this->Kotamodel->rawQuery($sql)->result();
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
    		$selectData = $this->Kotamodel->select($dataSelect, 'm_kota');
    		if($selectData->num_rows() > 0){
    			echo json_encode(
    				array(
    					'status'			=> 2,
    					'id'				=> $selectData->row()->id,
    					'nama'				=> $selectData->row()->nama,
    					'id_provinsi'		=> $selectData->row()->id_provinsi
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
		$dataUpdate['id_provinsi'] 		= $params['id_provinsi'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;

		$checkData = $this->Kotamodel->select($dataCondition, 'm_kota');
		if($checkData->num_rows() > 0){
			$update = $this->Kotamodel->update($dataCondition, $dataUpdate, 'm_kota');
			if($update){
				$dataSelect['deleted'] = 1;
				$sql = "SELECT m_kota.*, m_provinsi.id AS id_provinsi, m_provinsi.nama AS nama_provinsi FROM m_kota LEFT JOIN m_provinsi ON m_kota.id_provinsi = m_provinsi.id WHERE m_kota.deleted = '1' ORDER BY m_kota.date_add DESC";
                $list = $this->Kotamodel->rawQuery($sql)->result();
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
    		$update = $this->Kotamodel->update($dataCondition, $dataUpdate, 'm_kota');
    		if($update){
    			$dataSelect['deleted'] = 1;
				$sql = "SELECT m_kota.*, m_provinsi.id AS id_provinsi, m_provinsi.nama AS nama_provinsi FROM m_kota LEFT JOIN m_provinsi ON m_kota.id_provinsi = m_provinsi.id WHERE m_kota.deleted = '1' ORDER BY m_kota.date_add DESC";
                $list = $this->Kotamodel->rawQuery($sql)->result();
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

}