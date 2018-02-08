<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Stok_pesanan/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Stokpesananmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Stokpesananmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $sql = "SELECT A.*, B.nama FROM t_order_detail A LEFT JOIN m_produk B ON A.id_produk = B.id ORDER BY A.id_order DESC";
        $data['list_order'] = json_encode($this->Stokpesananmodel->rawQuery($sql)->result());

        $dataSelect['status'] = 1;
        $sql = "SELECT A.*, B.nama, C.nama AS nama_metode FROM t_order A LEFT JOIN m_customer B ON A.id_customer = B.id LEFT JOIN m_metode_pembayaran C ON A.id_metode_pembayaran = C.id WHERE A.deleted = 1 AND A.status = 1 ORDER BY A.date_add DESC";
        $data['list'] = json_encode($this->Stokpesananmodel->rawQuery($sql)->result());

    	$this->load->view('Stok_pesanan/view', $data);
    }

    function data(){
        $requestData= $_REQUEST;
        $columns = array( 
            0   =>  '#', 
            1   =>  'id', 
            2   =>  'nama', 
            3   =>  'grand_total', 
            4   =>  'nama_metode',
            5   =>  'catatan',
            6   =>  'date_add',
            7   =>  'status',
            8   =>  'aksi'
        );
        $sql = "SELECT A.*, B.nama, C.nama AS nama_metode FROM t_order A LEFT JOIN m_customer B ON A.id_customer = B.id LEFT JOIN m_metode_pembayaran C ON A.id_metode_pembayaran = C.id  WHERE A.deleted = 1 AND A.status = 1";
        $query=$this->Stokpesananmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        // $totalFiltered = $totalData;
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( B.nama LIKE '%".$requestData['search']['value']."%' "; 
            $sql.=" OR A.grand_total LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR C.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.catatan LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.date_add LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Stokpesananmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $query=$this->Stokpesananmodel->rawQuery($sql);
        
        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array(); 
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row['id']."</span>";
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   "<span class='pull-right money'>".$row["grand_total"]."</span>";
            $nestedData[]   =   $row["nama_metode"];
            $nestedData[]   =   $row["catatan"];
            $nestedData[]   =   date("d-m-Y H:i", strtotime($row["date_add"]));
            $nestedData[]   .=   '<div class="text-center" style="display:block;><div class="btn-group" >'
                .'<input type="checkbox" id="toggle_'.$row["id"].'" class="bootstrap-toggle">'
               .'</div>'
            .'</div>';
            $nestedData[]   .=   '<div class="text-center" style="display:block;"><div class="btn-group" >'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Lihat Detail" onclick="showDetail('.$row["id"].')"><i class="fa fa-file-text-o"></i></a>'
               .'</div>'
            .'</div>';
            
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
	
	function test(){
		header('Content-Type: application/json; charset=utf-8');
		$dataSelect['deleted'] = 1;
		$list = $this->Stokpesananmodel->select($dataSelect, 'm_bahan')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
	function get($id = null){   	
    	if($id != null){
    		$dataSelect['id'] = $id;
    		$selectData = $this->Stokpesananmodel->select($dataSelect, 'm_bahan');
    		if($selectData->num_rows() > 0){
    			echo json_encode(
    				array(
    					'status'			=> 2,
    					'id'				=> $selectData->row()->id,
    					'nama'				=> $selectData->row()->nama,
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
		$dataUpdate['status'] 			= 3; //status 'selesai'
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        
		$checkData = $this->Stokpesananmodel->select($dataCondition, 't_order');
		if($checkData->num_rows() > 0){
			$update = $this->Stokpesananmodel->update($dataCondition, $dataUpdate, 't_order');

			if($update){
				$dataSelect['deleted'] = 1;
				$sql = "SELECT A.*, B.nama FROM t_order_detail A LEFT JOIN m_produk B ON A.id_produk = B.id ORDER BY A.id_order DESC";
                $list_order = json_encode($this->Stokpesananmodel->rawQuery($sql)->result());
				echo json_encode(array('status'=>'3','list_order'=>$list_order));
			}else{
				echo json_encode(array( 'status'=>'2' ));
			}
		}else{			
    		echo json_encode(array( 'status'=>'1' ));
		}
    }
  
}