<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_customer/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Customermodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Customermodel->insert($dataInsert, 't_log');        
    }  
    function index(){
        $dataSelect['deleted !='] = 0;

        $sql = "SELECT nama FROM m_pegawai WHERE deleted = 1";
        $data['list_pegawai'] = json_encode($this->Customermodel->rawQuery($sql)->result());
        
        $data['list_prov'] = json_encode($this->Customermodel->select($dataSelect, 'm_provinsi', 'nama')->result());
        $data['list_kota'] = json_encode($this->Customermodel->select($dataSelect, 'm_kota', 'nama')->result());
        $data['list_level'] = json_encode($this->Customermodel->select($dataSelect, 'm_customer_level', 'nama')->result());
        $data['list'] = json_encode($this->Customermodel->select($dataSelect, 'm_customer', 'date_add', 'DESC')->result());
		//echo $data;
		//print_r($data);
    	$this->load->view('Master_customer/view', $data);
    }
	
	function test(){
		header('Content-Type: application/json; charset=utf-8');
		$dataSelect['deleted'] = 1;
		$list = $this->Customermodel->select($dataSelect, 'm_customer', 'date_add', 'DESC')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
    function add(){
		$params = $this->input->post();
		$dataInsert['nama'] 			= $params['nama'];
		$dataInsert['alamat'] 			= $params['alamat'];
		$dataInsert['no_telp'] 			= $params['no_telp'];
        $dataInsert['email']            = $params['email'];
		$dataInsert['password'] 	    = hash("sha512", $params['password']);
        $dataInsert['ktp']              = $params['ktp'];
        $dataInsert['npwp']             = $params['npwp'];
        $dataInsert['nama_bank']        = $params['nama_bank'];
        $dataInsert['no_rekening']      = $params['no_rekening'];
        $dataInsert['rekening_an']      = $params['rekening_an'];
        $dataInsert['keterangan']       = $params['keterangan'];
        $dataInsert['sales']            = $params['sales'];
		$dataInsert['kode_pos'] 		= $params['kodepos'];
		$dataInsert['id_provinsi'] 		= $params['id_provinsi'];
		$dataInsert['id_kota'] 			= $params['id_kota'];
		$dataInsert['id_customer_level'] = $params['id_customer_level'];
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
		$dataInsert['deleted'] 			= 1;

		$email_exist = $this->email_exist($params['email'], '');
		if($email_exist == FALSE){
			$insert = $this->Customermodel->insert($dataInsert, 'm_customer');
			if($insert){
				$dataSelect['deleted'] = 1;
				$list = $this->Customermodel->select($dataSelect, 'm_customer', 'date_add', 'DESC')->result();
				echo json_encode(array('status' => 3,'list' => $list));
			}else{
				echo json_encode(array('status' => 2));
			}
			
		} 
        else {			
    		echo json_encode(array( 'status'=>1 ));
		}
    }
   
	
	function get($id = null){   	
    	if($id != null){
    		$dataSelect['id'] = $id;
    		$selectData = $this->Customermodel->select($dataSelect, 'm_customer');
    		if($selectData->num_rows() > 0){
    			echo json_encode(
    				array(
    					'status'			=> 2,
    					'id'				=> $selectData->row()->id,
    					'nama'				=> $selectData->row()->nama,
    					'alamat'			=> $selectData->row()->alamat,
    					'no_telp'			=> $selectData->row()->no_telp,
    					'email'				=> $selectData->row()->email,
                        'ktp'               => $selectData->row()->ktp,
                        'npwp'              => $selectData->row()->npwp,
                        'nama_bank'         => $selectData->row()->nama_bank,
                        'no_rekening'       => $selectData->row()->no_rekening,
                        'rekening_an'       => $selectData->row()->rekening_an,
                        'keterangan'        => $selectData->row()->keterangan,
                        'sales'             => $selectData->row()->sales,
    					'kode_pos'			=> $selectData->row()->kode_pos,
    					'id_provinsi'		=> $selectData->row()->id_provinsi,
    					'id_kota'			=> $selectData->row()->id_kota,
    					'id_customer_level'	=> $selectData->row()->id_customer_level
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
        $dataUpdate['email']            = $params['email'];
        if(!empty($params['password'])) {
		  $dataUpdate['password'] 		= md5($params['password']);
        }
        $dataUpdate['ktp']              = $params['ktp'];
        $dataUpdate['npwp']             = $params['npwp'];
        $dataUpdate['nama_bank']        = $params['nama_bank'];
        $dataUpdate['no_rekening']      = $params['no_rekening'];
        $dataUpdate['rekening_an']      = $params['rekening_an'];
        $dataUpdate['keterangan']       = $params['keterangan'];
        $dataUpdate['sales']            = $params['sales'];
		$dataUpdate['kode_pos'] 		= $params['kodepos'];
		$dataUpdate['id_provinsi'] 		= $params['id_provinsi'];
		$dataUpdate['id_kota'] 			= $params['id_kota'];
		$dataUpdate['id_customer_level'] = $params['id_customer_level'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ?$_SESSION['id_user'] : 0;
        
        $email_exist = $this->email_exist($params['email'], $params['id']);
		if($email_exist == FALSE) {
			$update = $this->Customermodel->update($dataCondition, $dataUpdate, 'm_customer');
			if($update){
				$dataSelect['deleted'] = 1;
				$list = $this->Customermodel->select($dataSelect, 'm_customer', 'date_add', 'DESC')->result();
				echo json_encode(array('status' => '3','list' => $list));
			}else{
				echo json_encode(array( 'status'=>'2' ));
			}
		}
        else {	
    		echo json_encode(array( 'status'=>'1' ));
		}
    }
    function delete(){
		$id = $this->input->post("id");
    	if($id != null){
    		$dataCondition['id'] = $id;
    		$dataUpdate['deleted'] = 0;
    		$update = $this->Customermodel->update($dataCondition, $dataUpdate, 'm_customer');
    		if($update){
    			$dataSelect['deleted'] = 1;
				$list = $this->Customermodel->select($dataSelect, 'm_customer', 'date_add', 'DESC')->result();
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
    	echo json_encode($this->Customermodel->select($dataSelect, 'm_kota', 'nama')->result());
    }

    function update_status() {
        $response = array('status' => 0);
        $params = $this->input->post();
        if(!empty($params['id'])) {
            $condition = array('id' => $params['id']);
            $data = array('deleted' => $params['status'], 'edited_by' => isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0);
            $result = $this->Customermodel->update($condition, $data, 'm_customer'); 
            $response = array('status' => $result);
        }
        echo json_encode($response);
    }

    private function email_exist($email='', $current_id='') {
        $result = FALSE;
        if(!empty($email)) {
            $condition = array(
                    'email' => $email,
                    'deleted' => 1
                );
            if(!empty($current_id)) {
                $condition['id !='] = $current_id;
            }
            $result = $this->Customermodel->select($condition, 'm_customer')->num_rows();
        }   
        return $result;
    }

}