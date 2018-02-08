<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_stok/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanstokmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Laporanstokmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $sql = "SELECT A.* FROM m_produk A WHERE A.deleted = 1 ORDER BY A.stok DESC";
        $data['list'] = json_encode($this->Laporanstokmodel->rawQuery($sql)->result());
    	$this->load->view('Laporan_stok/view', $data);
    }

    function data(){
        $requestData= $_REQUEST;
        $columns = array( 
            0   =>  '#', 
            1   =>  'nama', 
            2   =>  'sku',
            3   =>  'kode_barang',
            4   =>  'stok',
            // 6   =>  'aksi'
        );
        $sql = "SELECT A.* FROM m_produk A WHERE A.deleted = 1";
        $query=$this->Laporanstokmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( A.nama LIKE '%".$requestData['search']['value']."%' "; 
            $sql.=" OR A.sku LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.kode_barang LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.stok LIKE '%".$requestData['search']['value']."%' )";
        }

        //Stok filtering
        if(!empty($requestData['operator']) AND !empty($requestData['stok'])) {
            $operator = ($requestData['operator']=="kurang_dari") ? "<=" : ">=";
            $sql.=" AND (A.stok ".$operator." '".$requestData['stok']."')";
        }
        // echo $sql;
        // die();

        $query=$this->Laporanstokmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $query=$this->Laporanstokmodel->rawQuery($sql);
        
        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array(); 
            $nestedData[]   =   "<span style='display:block' class='text-center'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   $row["sku"];
            $nestedData[]   =   $row['kode_barang'];
            $nestedData[]   =   "<span style='display:block' class='text-center'>".$row["stok"]."</span>";
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
		$list = $this->Laporanstokmodel->select($dataSelect, 'fin_transfer_harian')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
	function get($id = null){   	
    	if($id != null){
    		$dataSelect['id'] = $id;
    		$selectData = $this->Laporanstokmodel->select($dataSelect, 'fin_transfer_harian');
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