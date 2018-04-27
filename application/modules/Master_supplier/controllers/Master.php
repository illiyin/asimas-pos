<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_supplier/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Suppliermodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Suppliermodel->insert($dataInsert, 't_log');
    }
    function index(){
        $dataSelect['deleted'] = 1;
        $data['list'] = json_encode($this->Suppliermodel->select($dataSelect, 'm_supplier', 'date_add', 'DESC')->result());
        //echo $data;
        //print_r($data);
        $this->load->view('Master_supplier/view', $data);
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
        $sql = "SELECT * FROM m_supplier WHERE deleted = 1";
        $query=$this->Suppliermodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT * ";
        $sql.=" FROM m_supplier WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR alamat LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_telp LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR email LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR lead_time LIKE '%".$requestData['search']['value']."%' )";
        }

        // if(!empty($requestData['search']['value'])) {
        //     $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        // } else{
        // }
        $query=$this->Suppliermodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();
        
        $sql.=" ORDER BY date_add DESC";
        $sql.= " LIMIT ".$requestData['start']." ,".$requestData['length']."";
        $query=$this->Suppliermodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $index => $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   $row["alamat"];
            $nestedData[]   =   $row["no_telp"];
            $nestedData[]   =   $row["email"];
            $nestedData[]   =   $row["lead_time"];
            $nestedData[]   =   $row["status"] ? "Approved" : "Pre Approved";
            $nestedData[]   .=   '<td class="text-center"><div class="btn-group">'
                .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$index.')"><i class="fa fa-pencil"></i></a>'
            .'</td>';

            $data[] = $nestedData; $i++;
        }
        $totalData = count($data);
        $json_data = array(
                "sql" => $sql,
                    "draw"            => intval( $requestData['draw'] ),
                    "recordsTotal"    => intval( $totalData ),
                    "recordsFiltered" => intval( $totalFiltered ),
                    "data"            => $data
                    );
        echo json_encode($json_data);
    }
    function add(){
        $params = $this->input->post();

        // echo json_encode($params);
        $dataInsert['nama']             = $params['nama'];
        $dataInsert['alamat']           = $params['alamat'];
        $dataInsert['no_telp']          = $params['no_telp'];
        $dataInsert['email']            = $params['email'];
        $dataInsert['lead_time']        = $params['leadtime'];
        // $dataInsert['moq']              = $params['moq'];
        $dataInsert['status']           = $params['approvement'] ? $params['approvement'] : 0;
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['date_add']         = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']          = 1;

        $checkData = $this->Suppliermodel->select($dataInsert, 'm_supplier');
        if($checkData->num_rows() < 1){
            $insert = $this->Suppliermodel->insert($dataInsert, 'm_supplier');
            if($insert){
                $dataSelect['deleted'] = 1;
                $list = $this->Suppliermodel->select($dataSelect, 'm_supplier', 'date_add')->result();
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
        $dataUpdate['lead_time']        = $params['leadtime'];
        // $dataUpdate['moq']              = $params['moq'];
        $dataUpdate['status']           = $params['approvement'] ? $params['approvement'] : 0;
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;

        $checkData = $this->Suppliermodel->select($dataCondition, 'm_supplier');
        if($checkData->num_rows() > 0){
            $update = $this->Suppliermodel->update($dataCondition, $dataUpdate, 'm_supplier');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->Suppliermodel->select($dataSelect, 'm_supplier', 'date_add')->result();
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
            $update = $this->Suppliermodel->update($dataCondition, $dataUpdate, 'm_supplier');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->Suppliermodel->select($dataSelect, 'm_supplier', 'date_add')->result();
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
