<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_supplier_bahan/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Supplierbahanmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Supplierbahanmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $data['list_prov'] = json_encode($this->Supplierbahanmodel->select($dataSelect, 'm_provinsi', 'nama')->result());
        $data['list_kota'] = json_encode($this->Supplierbahanmodel->select($dataSelect, 'm_kota', 'nama')->result());
    	$data['list'] = json_encode($this->Supplierbahanmodel->select($dataSelect, 'm_supplier_bahan', 'date_add', 'DESC')->result());
		//echo $data;
		//print_r($data);
    	$this->load->view('Master_supplier_bahan/view', $data);
    }
	
	function test(){
		header('Content-Type: application/json; charset=utf-8');
		$dataSelect['deleted'] = 1;
		$list = $this->Supplierbahanmodel->select($dataSelect, 'm_supplier_bahan', 'date_add', 'DESC')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
    function add(){
		$params = $this->input->post();
		$dataInsert['nama'] 			= $params['nama'];
		$dataInsert['alamat'] 			= $params['alamat'];
		$dataInsert['no_telp'] 			= $params['no_telp'];
        $dataInsert['email']            = $params['email'];
        $dataInsert['id_provinsi']      = $params['id_provinsi'];
        $dataInsert['id_kota']          = $params['id_kota'];
        $dataInsert['npwp']             = $params['npwp'];
        $dataInsert['nama_bank']        = $params['nama_bank'];
        $dataInsert['no_rekening']      = $params['no_rekening'];
        $dataInsert['rekening_an']      = $params['rekening_an'];
		$dataInsert['keterangan'] 		= $params['keterangan'];
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
		$dataInsert['deleted'] 			= 1;

		$checkData = $this->Supplierbahanmodel->select($dataInsert, 'm_supplier_bahan');
		if($checkData->num_rows() < 1){
			$insert = $this->Supplierbahanmodel->insert($dataInsert, 'm_supplier_bahan');
			if($insert){
				$dataSelect['deleted'] = 1;
				$list = $this->Supplierbahanmodel->select($dataSelect, 'm_supplier_bahan', 'date_add', 'DESC')->result();
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
    		$selectData = $this->Supplierbahanmodel->select($dataSelect, 'm_supplier_bahan');
    		if($selectData->num_rows() > 0){
    			echo json_encode(
    				array(
    					'status'			=> 2,
    					'id'				=> $selectData->row()->id,
    					'nama'				=> $selectData->row()->nama,
    					'alamat'			=> $selectData->row()->alamat,
    					'no_telp'			=> $selectData->row()->no_telp,
                        'email'             => $selectData->row()->email,
                        'id_provinsi'       => $selectData->row()->id_provinsi,
                        'id_kota'           => $selectData->row()->id_kota,
                        'npwp'              => $selectData->row()->npwp,
                        'nama_bank'         => $selectData->row()->nama_bank,
                        'no_rekening'       => $selectData->row()->no_rekening,
                        'rekening_an'       => $selectData->row()->rekening_an,
    					'keterangan'		=> $selectData->row()->keterangan,
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
		$dataUpdate['no_telp'] 			= $params['no_telp'];
		$dataUpdate['email'] 			= $params['email'];
		$dataUpdate['id_provinsi'] 		= $params['id_provinsi'];
		$dataUpdate['id_kota'] 			= $params['id_kota'];
        $dataUpdate['npwp']             = $params['npwp'];
        $dataUpdate['nama_bank']        = $params['nama_bank'];
        $dataUpdate['no_rekening']      = $params['no_rekening'];
        $dataUpdate['rekening_an']      = $params['rekening_an'];
        $dataUpdate['keterangan']       = $params['keterangan'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        
		$checkData = $this->Supplierbahanmodel->select($dataCondition, 'm_supplier_bahan');
		if($checkData->num_rows() > 0){
			$update = $this->Supplierbahanmodel->update($dataCondition, $dataUpdate, 'm_supplier_bahan');
			if($update){
				$dataSelect['deleted'] = 1;
				$list = $this->Supplierbahanmodel->select($dataSelect, 'm_supplier_bahan', 'date_add', 'DESC')->result();
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
    		$update = $this->Supplierbahanmodel->update($dataCondition, $dataUpdate, 'm_supplier_bahan');
    		if($update){
    			$dataSelect['deleted'] = 1;
				$list = $this->Supplierbahanmodel->select($dataSelect, 'm_supplier_bahan', 'date_add', 'DESC')->result();
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
    	echo json_encode($this->Supplierbahanmodel->select($dataSelect, 'm_kota', 'nama')->result());
    }
    
}