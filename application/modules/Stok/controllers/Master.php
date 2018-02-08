<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Stok/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Stokmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Stokmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $sql = "SELECT A.*, B.nama, B.sku, C.id_order, C.nama_warna, C.nama_ukuran FROM h_stok_produk A LEFT JOIN m_produk B ON A.id_produk = B.id LEFT JOIN t_order_detail C ON A.id_order_detail = C.id ORDER BY A.date_add DESC";
        $data['list'] = json_encode($this->Stokmodel->rawQuery($sql)->result());
    	$this->load->view('Stok/view', $data);
    }

    function data(){
        $requestData= $_REQUEST;
        $columns = array( 
            0   =>  '#', 
            1   =>  'date_add', 
            2   =>  'nama',
            3   =>  'sku',
            4   =>  'nama_warna',
            5   =>  'nama_ukuran',
            6   =>  'jumlah',
            7   =>  'stok_akhir',
            8   =>  'id_order',
            9   =>  'status',
            // 6   =>  'aksi'
        );
        $sql = "SELECT A.*, B.nama, B.sku, C.id_order, D.nama AS nama_warna, E.nama AS nama_ukuran FROM h_stok_produk A LEFT JOIN m_produk B ON A.id_produk = B.id LEFT JOIN t_order_detail C ON A.id_order_detail = C.id LEFT JOIN m_produk_warna D ON A.id_warna = D.id LEFT JOIN m_produk_ukuran E ON A.id_ukuran = E.id";
        $query=$this->Stokmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        
        if( !empty($requestData['search']['value']) ) {
            $sql.=" WHERE (B.nama LIKE '%".$requestData['search']['value']."%' "; 
            $sql.=" OR B.sku LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.jumlah LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.stok_akhir LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR D.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR E.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR C.id_order LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.status LIKE '%".$requestData['search']['value']."%' )";
        }

        //Stok filtering
        // if(!empty($requestData['operator']) AND !empty($requestData['stok'])) {
        //     $operator = ($requestData['operator']=="kurang_dari") ? "<=" : ">=";
        //     $sql.=" AND (A.stok ".$operator." '".$requestData['stok']."')";
        // }
        // echo $sql;
        // die();

        $query=$this->Stokmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $query=$this->Stokmodel->rawQuery($sql);
        
        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            if($row['status'] == 1) {
                 $status = "Berkurang dari proses penjualan"; }
            else if($row['status'] == 2) {
                 $status = "Berkurang dari proses service"; }
            else if($row['status'] == 3) {
                 $status = "Dikurangi manual oleh admin"; }
            else if($row['status'] == 4) {
                 $status = "Ditambah manual oleh admin"; }
            else if($row['status'] == 5) {
                 $status = "Ditambah dari barang yang telah diservice"; }

            $nestedData     =   array(); 
            $nestedData[]   =   "<span style='display:block' class='text-center'>".($i+1)."</span>";
            $nestedData[]   =   date("d-m-Y H:i", strtotime($row["date_add"]));
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   $row["sku"];
            $nestedData[]   =   !empty($row['nama_warna']) ? $row['nama_warna'] : "<span class='center-block text-center'>-</span>";
            $nestedData[]   =   !empty($row['nama_ukuran']) ? $row['nama_ukuran'] : "<span class='center-block text-center'>-</span>";
            $nestedData[]   =   "<span style='display:block' class='text-center'>".$row["jumlah"]."</span>";
            $nestedData[]   =   "<span style='display:block' class='text-center'>".$row["stok_akhir"]."</span>";
            $nestedData[]   =   "<span style='display:block' class='text-center'>".$row["id_order"]."</span>";
            $nestedData[]   =   $status;
            // $nestedData[]   .=   '<td class="text-center"><div class="btn-group" >'
            //     .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
            //     .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$row["id"].')"><i class="fa fa-pencil"></i></a>'
            //    .'</div>'
            // .'</td>';
            
            $data[] = $nestedData; $i++;
        }
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
		$list = $this->Stokmodel->select($dataSelect, 'fin_transfer_harian')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
	function get($id = null){   	
    	if($id != null){
    		$dataSelect['id'] = $id;
    		$selectData = $this->Stokmodel->select($dataSelect, 'fin_transfer_harian');
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
	
   
    
}