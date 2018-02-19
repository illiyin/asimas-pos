<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_produsen/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Produsenmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Produsenmodel->insert($dataInsert, 't_log');
    }
    function index(){
    	$dataSelect['deleted'] = 1;
    	$data['list'] = json_encode($this->Produsenmodel->select($dataSelect, 'm_produsen', 'date_add', 'DESC')->result());
		//echo $data;
		//print_r($data);
    	$this->load->view('Master_produsen/view', $data);
    }

	function add(){
        $params = $this->input->post();

        $dataInsert['nama']             = $params['nama'];
        $dataInsert['alamat']           = $params['alamat'];
        $dataInsert['no_telp']          = $params['no_telp'];
        $dataInsert['email']            = $params['email'];
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['date_add']         = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']          = 1;

        $checkData = $this->Produsenmodel->select($dataInsert, 'm_produsen');
        if($checkData->num_rows() < 1){
            $insert = $this->Produsenmodel->insert($dataInsert, 'm_produsen');
            if($insert){
                $dataSelect['deleted'] = 1;
                $list = $this->Produsenmodel->select($dataSelect, 'm_produsen', 'date_add', 'DESC')->result();
                echo json_encode(array('status' => 3,'list' => $list));
            }else{
                echo json_encode(array('status' => 1));
            }

        }else{
            echo json_encode(array( 'status'=>1 ));
        }
    }
    
    function edit(){
        $params = $this->input->post();
        $dataCondition['id']            = $params['id'];

        $dataUpdate['nama']             = $params['nama'];
        $dataUpdate['alamat']           = $params['alamat'];
        $dataUpdate['no_telp']          = $params['no_telp'];
        $dataUpdate['email']            = $params['email'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;

        $checkData = $this->Produsenmodel->select($dataCondition, 'm_produsen');
        if($checkData->num_rows() > 0){
            $update = $this->Produsenmodel->update($dataCondition, $dataUpdate, 'm_produsen');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->Produsenmodel->select($dataSelect, 'm_produsen', 'date_add', 'DESC')->result();
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
            $update = $this->Produsenmodel->update($dataCondition, $dataUpdate, 'm_produsen');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->Produsenmodel->select($dataSelect, 'm_produsen', 'date_add', 'DESC')->result();
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
