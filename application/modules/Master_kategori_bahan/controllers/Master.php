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
    function data() {
        $requestData= $_REQUEST;
        $columns = array(
            // 0   =>  '#',
            // 1   =>  'nama',
            // 2   =>  'no_batch',
            // 3   =>  'stok_akhir',
            4   =>  'date_add'
            // 5   =>  'aksi'
        );
        $sql = "SELECT * FROM m_bahan_kategori WHERE deleted = 1";
        $query=$this->Kategoribahanmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT * ";
        $sql.=" FROM m_bahan_kategori WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( kode_kategori LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR nama LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Kategoribahanmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query=$this->Kategoribahanmodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $index => $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["kode_kategori"];
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   date("d-m-Y H:m", strtotime($row["date_add"]));
            $nestedData[]   .=   '<td class="text-center"><div class="btn-group">'
                .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$index.')"><i class="fa fa-pencil"></i></a>'
            .'</td>';

            $data[] = $nestedData; $i++;
        }
        $totalData = count($data);
        $json_data = array(
                    "draw"            => intval( $requestData['draw'] ),
                    "recordsTotal"    => intval( $totalData ),
                    "recordsFiltered" => intval( $totalFiltered ),
                    "data"            => $data
                    );
        echo json_encode($json_data);
    }
    function add(){
        $params = $this->input->post();

        $condition['kode_kategori']     = $params['kode_kategori'];
        $condition['deleted']           = 1;
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
            echo json_encode(array( 'status'=> 1, 'message' => 'Kode Kategori Bahan sudah ada!'));
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
