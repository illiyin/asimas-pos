<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Dev_kategori/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Devkategorimodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Devkategorimodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $sql = "SELECT A.*, (SELECT COUNT(B.id) FROM m_pegawai_permission B WHERE B.id_menu = A.id) AS jumlah_menu FROM m_pegawai_menu A WHERE A.deleted = 1 ";
        $data['list'] = json_encode($this->Devkategorimodel->rawQuery($sql)->result());
    	// $data['list'] = json_encode($this->Devkategorimodel->select($dataSelect, 'm_pegawai_menu', 'date_add', 'DESC')->result());
		//echo $data;
		//print_r($data);
    	$this->load->view('Dev_kategori/view', $data);
    }
	
	function test(){
		header('Content-Type: application/json; charset=utf-8');
		$dataSelect['deleted'] = 1;
		$list = $this->Devkategorimodel->select($dataSelect, 'm_pegawai_menu', 'date_add', 'DESC')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
    function add(){
		$params = $this->input->post();
        $dataInsert['nama']            = $params['nama'];
		$dataInsert['icon_class'] 	   = $params['icon_class'];
		$dataInsert['deleted'] 		   = 1;

		$checkData = $this->Devkategorimodel->select($dataInsert, 'm_pegawai_menu');
		if($checkData->num_rows() < 1){
			$insert = $this->Devkategorimodel->insert($dataInsert, 'm_pegawai_menu');
			if($insert){
				$dataSelect['deleted'] = 1;
                $sql = "SELECT A.*, (SELECT COUNT(B.id) FROM m_pegawai_permission B WHERE B.id_menu = A.id) AS jumlah_menu FROM m_pegawai_menu A WHERE A.deleted = 1 ";
                $list = $this->Devkategorimodel->rawQuery($sql)->result();
				// $list = $this->Devkategorimodel->select($dataSelect, 'm_pegawai_menu', 'date_add', 'DESC')->result();
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
    		$selectData = $this->Devkategorimodel->select($dataSelect, 'm_pegawai_menu');
    		if($selectData->num_rows() > 0){
    			echo json_encode(
    				array(
    					'status'			=> 2,
    					'id'				=> $selectData->row()->id,
                        'nama'              => $selectData->row()->nama,
    					'icon_class'		=> $selectData->row()->icon_class,
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
        $dataUpdate['nama']             = $params['nama'];
		$dataUpdate['icon_class'] 		= $params['icon_class'];
        
		$checkData = $this->Devkategorimodel->select($dataCondition, 'm_pegawai_menu');
		if($checkData->num_rows() > 0){
			$update = $this->Devkategorimodel->update($dataCondition, $dataUpdate, 'm_pegawai_menu');
			if($update){
				$dataSelect['deleted'] = 1;
				$sql = "SELECT A.*, (SELECT COUNT(B.id) FROM m_pegawai_permission B WHERE B.id_menu = A.id) AS jumlah_menu FROM m_pegawai_menu A WHERE A.deleted = 1 ";
                $list = $this->Devkategorimodel->rawQuery($sql)->result();
                // $list = $this->Devkategorimodel->select($dataSelect, 'm_pegawai_menu', 'date_add', 'DESC')->result();
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
    		$update = $this->Devkategorimodel->update($dataCondition, $dataUpdate, 'm_pegawai_menu');
    		if($update){
    			$dataSelect['deleted'] = 1;
                $sql = "SELECT A.*, (SELECT COUNT(B.id) FROM m_pegawai_permission B WHERE B.id_menu = A.id) AS jumlah_menu FROM m_pegawai_menu A WHERE A.deleted = 1 ";
                $list = $this->Devkategorimodel->rawQuery($sql)->result();
				// $list = $this->Devkategorimodel->select($dataSelect, 'm_pegawai_menu', 'date_add', 'DESC')->result();
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