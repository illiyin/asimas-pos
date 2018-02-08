<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Dev_menu/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Devmenumodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Devmenumodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $data['list_kat'] = json_encode($this->Devmenumodel->select($dataSelect, 'm_pegawai_menu', 'nama')->result());

        $sql = "SELECT m_pegawai_permission.*, m_pegawai_menu.id AS id_kategori, m_pegawai_menu.nama AS nama_kategori FROM m_pegawai_permission LEFT JOIN m_pegawai_menu ON m_pegawai_permission.id_menu = m_pegawai_menu.id WHERE m_pegawai_permission.deleted = '1' ORDER BY m_pegawai_permission.date_add DESC";
        $data['list'] = json_encode($this->Devmenumodel->rawQuery($sql)->result());
    	// $data['list'] = json_encode($this->Devmenumodel->select($dataSelect, 'm_pegawai_permission')->result());
		//echo $data;
		//print_r($data);
    	$this->load->view('Dev_menu/view', $data);
    }
	
	function test(){
		header('Content-Type: application/json; charset=utf-8');
		$dataSelect['deleted'] = 1;
		$list = $this->Devmenumodel->select($dataSelect, 'm_pegawai_permission')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
    function add(){
		$params = $this->input->post();
		$dataInsert['nama'] 			= $params['nama'];
		$dataInsert['id_menu'] 		    = $params['id_kategori'];
        $dataInsert['url']              = $params['url'];
        $dataInsert['urutan']              = $params['urutan'];
        $dataInsert['icon_class']       = $params['icon_class'];
		$dataInsert['deleted'] 			= 1;
        
		$checkData = $this->Devmenumodel->select($dataInsert, 'm_pegawai_permission');
		if($checkData->num_rows() < 1){
			$insert = $this->Devmenumodel->insert($dataInsert, 'm_pegawai_permission');
			if($insert){
				$dataSelect['deleted'] = 1;
                $sql = "SELECT m_pegawai_permission.*, m_pegawai_menu.id AS id_kategori, m_pegawai_menu.nama AS nama_kategori FROM m_pegawai_permission LEFT JOIN m_pegawai_menu ON m_pegawai_permission.id_menu = m_pegawai_menu.id WHERE m_pegawai_permission.deleted = '1' ORDER BY m_pegawai_permission.date_add DESC";
				$list = $this->Devmenumodel->rawQuery($sql)->result();
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
    		$selectData = $this->Devmenumodel->select($dataSelect, 'm_pegawai_permission');
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
        $dataUpdate['id_menu']          = $params['id_kategori'];
        $dataUpdate['url']              = $params['url'];
        $dataUpdate['urutan']              = $params['urutan'];
		$dataUpdate['icon_class'] 		= $params['icon_class'];

		$checkData = $this->Devmenumodel->select($dataCondition, 'm_pegawai_permission');
		if($checkData->num_rows() > 0){
			$update = $this->Devmenumodel->update($dataCondition, $dataUpdate, 'm_pegawai_permission');
			if($update){
				$dataSelect['deleted'] = 1;
				$sql = "SELECT m_pegawai_permission.*, m_pegawai_menu.id AS id_kategori, m_pegawai_menu.nama AS nama_kategori FROM m_pegawai_permission LEFT JOIN m_pegawai_menu ON m_pegawai_permission.id_menu = m_pegawai_menu.id WHERE m_pegawai_permission.deleted = '1' ORDER BY m_pegawai_permission.date_add DESC";
                $list = $this->Devmenumodel->rawQuery($sql)->result();
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
    		$update = $this->Devmenumodel->update($dataCondition, $dataUpdate, 'm_pegawai_permission');
    		if($update){
    			$dataSelect['deleted'] = 1;
				$sql = "SELECT m_pegawai_permission.*, m_pegawai_menu.id AS id_kategori, m_pegawai_menu.nama AS nama_kategori FROM m_pegawai_permission LEFT JOIN m_pegawai_menu ON m_pegawai_permission.id_menu = m_pegawai_menu.id WHERE m_pegawai_permission.deleted = '1' ORDER BY m_pegawai_permission.date_add DESC";
                $list = $this->Devmenumodel->rawQuery($sql)->result();
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