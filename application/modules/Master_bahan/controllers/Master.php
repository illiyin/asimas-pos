<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_bahan/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Bahanmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Bahanmodel->insert($dataInsert, 't_log');
    }
    function index(){
    	$dataSelect['deleted'] = 1;
        $data['list_satuan'] = json_encode($this->Bahanmodel->select($dataSelect, 'm_satuan', 'nama')->result());
        $data['list_kategori'] = json_encode($this->Bahanmodel->select($dataSelect, 'm_bahan_kategori', 'nama')->result());
        $data['list'] = json_encode($this->data());
		//echo $data;
		//print_r($data);
    	$this->load->view('Master_bahan/view', $data);
    }
    private function data($id = '') {
        $dataSelect['deleted'] = 1;
        if($id) $dataSelect['id'] = $id;
        $query = $this->Bahanmodel->select($dataSelect, 'm_bahan')->result();
        $data = null;
        foreach($query as $row) {
            $kategori = $this->Bahanmodel->select(array('id' => $row->id_kategori_bahan), 'm_bahan_kategori')->row();

            $data[] = array(
                    'id' => $row->id,
                    'id_satuan' => $row->id_satuan,
                    'kategori' => array(
                            'id' => $row->id_kategori_bahan,
                            'kode' => $kategori->kode_kategori,
                            'nama' => $kategori->nama,
                        ),
                    'nama_bahan' => $row->nama,
                    'kode_bahan' => $row->kode_bahan,
                    'jumlah_masuk' => $row->jumlah_masuk,
                    'jumlah_keluar' => $row->jumlah_keluar,
                    'saldo_bulan_sekarang' => $row->saldo_bulan_sekarang,
                    'saldo_bulan_kemarin' => $row->saldo_bulan_kemarin,
                    'tanggal_datang' => $row->tgl_datang,
                    'date_add' => $row->date_add,
                    'last_edited' => $row->last_edited
                );
        }

        return $data;
    }
    function add() {
        $params = $this->input->post();
        $condition['kode_bahan']            = $params['kode_bahan'];
        $dateExplode                        = explode("/", $params['tgl_datang']);
        $dataInsert['id_kategori_bahan']    = $params['id_kategori'];
        $dataInsert['id_satuan']            = $params['id_satuan'];
        $dataInsert['nama']                 = $params['nama'];
        $dataInsert['kode_bahan']           = $params['kode_bahan'];
        $dataInsert['jumlah_masuk']         = $params['jumlah_masuk'];
        $dataInsert['jumlah_keluar']        = $params['jumlah_keluar'];
        $dataInsert['saldo_bulan_sekarang'] = $params['saldo_sekarang'];
        $dataInsert['saldo_bulan_kemarin']  = $params['saldo_kemarin'];
        $dataInsert['tgl_datang']           = $dateExplode[2].'-'.$dateExplode[1].'-'.$dateExplode[0].' 00:00:00';
        $dataInsert['date_add']             = date("Y-m-d H:i:s");
        $dataInsert['add_by']               = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['last_edited']          = date("Y-m-d H:i:s");
        $dataInsert['edited_by']            = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']              = 1;

        $checkData = $this->Bahanmodel->select($condition, 'm_bahan');
        if($checkData->num_rows() < 1){
            $insert = $this->Bahanmodel->insert($dataInsert, 'm_bahan');
            if($insert){
                $list = $this->data();
                echo json_encode(array('status' => 3,'list' => $list));
            }else{
                echo json_encode(array('status' => 2));
            }

        }else{
            echo json_encode(array( 'status'=> 1, 'message' => 'Kode Bahan sudah ada!'));
        }
    }
    function edit() {
        $params = $this->input->post();

        $dataCondition['id']                = $params['id'];
        $dateExplode                        = explode("/", $params['tgl_datang']);
        $dataUpdate['id_kategori_bahan']    = $params['id_kategori'];
        $dataUpdate['id_satuan']            = $params['id_satuan'];
        $dataUpdate['nama']                 = $params['nama'];
        $dataUpdate['kode_bahan']           = $params['kode_bahan'];
        $dataUpdate['jumlah_masuk']         = $params['jumlah_masuk'];
        $dataUpdate['jumlah_keluar']        = $params['jumlah_keluar'];
        $dataUpdate['saldo_bulan_sekarang'] = $params['saldo_sekarang'];
        $dataUpdate['saldo_bulan_kemarin']  = $params['saldo_kemarin'];
        $dataUpdate['tgl_datang']           = $dateExplode[2].'-'.$dateExplode[1].'-'.$dateExplode[0].' 00:00:00';
        $dataUpdate['last_edited']          = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']            = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataUpdate['deleted']              = 1;

        $checkData = $this->Bahanmodel->select($dataCondition, 'm_bahan');
        if($checkData->num_rows() > 0){
            $update = $this->Bahanmodel->update($dataCondition, $dataUpdate, 'm_bahan');
            if($update){
                $list = $this->data();
                echo json_encode(array('status' => '3','list' => $list));
            }else{
                echo json_encode(array( 'status'=>'2' ));
            }
        }else{
            echo json_encode(array( 'status'=>'1' ));
        }
    }
    function delete() {
        $id = $this->input->post("id");
        if($id != null){
            $dataCondition['id'] = $id;
            $dataUpdate['deleted'] = 0;
            $update = $this->Bahanmodel->update($dataCondition, $dataUpdate, 'm_bahan');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->data();
                echo json_encode(array('status' => '3','list' => $list));
            }else{
                echo "1";
            }
        }else{
            echo "0";
        }
    }
}
