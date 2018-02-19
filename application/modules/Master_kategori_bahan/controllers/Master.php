<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_kategori_bahan/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Kategoribahanmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Kategoribahanmodel->insert($dataInsert, 't_log');
    }
    function index(){
    	$dataSelect['deleted'] = 1;
        $data['list'] = json_encode($this->Kategoribahanmodel->select($dataSelect, 'm_bahan_kategori', 'date_add', 'DESC')->result());
		//echo $data;
		//print_r($data);
    	$this->load->view('Master_kategori_bahan/view', $data);
    }
    function add(){
        $params = $this->input->post();

        $condition['kode_kategori']     = $params['kode_kategori'];
        $dataInsert['nama']             = $params['nama'];
        $dataInsert['kode_kategori']    = $params['kode_kategori'];
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['date_add']         = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']          = 1;

        $checkData = $this->Kategoribahanmodel->select($condition, 'm_bahan_kategori');
        if($checkData->num_rows() < 1){
            $insert = $this->Kategoribahanmodel->insert($dataInsert, 'm_bahan_kategori');
            if($insert){
                $dataSelect['deleted'] = 1;
                $list = $this->Kategoribahanmodel->select($dataSelect, 'm_bahan_kategori', 'date_add', 'DESC')->result();
                echo json_encode(array('status' => 3,'list' => $list));
            }else{
                echo json_encode(array('status' => 2));
            }

        }else{
            echo json_encode(array( 'status'=> 1, 'message' => 'Kode Bahan sudah ada!'));
        }
    }
    function edit(){
        $params = $this->input->post();
        $dataCondition['id']            = $params['id'];

        $dataUpdate['nama']             = $params['nama'];
        $dataUpdate['kode_kategori']    = $params['kode_kategori'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;

        $checkData = $this->Kategoribahanmodel->select($dataCondition, 'm_bahan_kategori');
        if($checkData->num_rows() > 0){
            $update = $this->Kategoribahanmodel->update($dataCondition, $dataUpdate, 'm_bahan_kategori');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->Kategoribahanmodel->select($dataSelect, 'm_bahan_kategori', 'date_add', 'DESC')->result();
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
            $update = $this->Kategoribahanmodel->update($dataCondition, $dataUpdate, 'm_bahan_kategori');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->Kategoribahanmodel->select($dataSelect, 'm_bahan_kategori', 'date_add', 'DESC')->result();
                echo json_encode(array('status' => '3','list' => $list));
            }else{
                echo "1";
            }
        }else{
            echo "0";
        }
    }
}
