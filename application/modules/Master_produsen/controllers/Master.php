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
    function data() {
        $requestData= $_REQUEST;
        $columns = array(
            // 0   =>  '#',
            1   =>  'nama'
            // 2   =>  'no_batch',
            // 3   =>  'stok_akhir',
            // 4   =>  'date_add'
            // 5   =>  'aksi'
        );
        $sql = "SELECT * FROM m_produsen WHERE deleted = 1";
        $query=$this->Produsenmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT * ";
        $sql.=" FROM m_produsen WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR alamat LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_telp LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR email LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Produsenmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        // $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $sql.=" ORDER BY date_add DESC";
        $sql.= " LIMIT ".$requestData['start']." ,".$requestData['length']."";
        $query=$this->Produsenmodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $index => $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   $row["alamat"];
            $nestedData[]   =   $row["no_telp"];
            $nestedData[]   =   $row["email"];
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
