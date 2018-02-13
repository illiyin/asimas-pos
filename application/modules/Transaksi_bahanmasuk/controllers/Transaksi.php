<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends MX_Controller {
    private $modul = "Transaksi_bahanmasuk/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksibahanmasukmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Transaksibahanmasukmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$this->load->view('Transaksi_bahanmasuk/view');
    }
    function data(){
		$requestData= $_REQUEST;
		$columns = array( 
			0 	=>	'id',
			1 	=>	'foto',
			2 	=> 	'nama_bahan',
			3 	=> 	'sku',
			4 	=> 	'stok',
			5	=> 	'tanggal_tambah_stok',
			6	=> 	'tanggal_kurang_stok',
			7	=> 	'aksi'
		);
		$sql = " SELECT m_bahan.*";
		$sql.= " FROM m_bahan ";
		$sql.= " LEFT JOIN m_supplier_bahan ON m_bahan.id_supplier_bahan = m_supplier_bahan.id ";
		$sql.= " LEFT JOIN m_satuan ON m_bahan.id_satuan = m_satuan.id ";
		$sql.= " LEFT JOIN m_gudang ON m_bahan.id_gudang = m_gudang.id ";
		$query=$this->Transaksibahanmasukmodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;
		$sql.=" WHERE m_bahan.deleted=1 ";
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_supplier_bahan.nama LIKE '%".$requestData['search']['value']."%' ";    
			$sql.=" OR m_bahan.deskripsi LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_bahan.nama LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_bahan.harga_beli LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_bahan.stok LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_bahan.date_add LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksibahanmasukmodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksibahanmasukmodel->rawQuery($sql);
		$data = array();
		$i = 1;
		foreach ($query->result_array() as $row) {
            $foto_url = base_url()."/upload/bahan_baku/placeholder.png";
            if(!empty($row["foto"])) {
                if(file_exists(URL_UPLOAD."/bahan_baku/".$row["foto"])) {
                    $foto_url = base_url()."/upload/bahan_baku/".$row["foto"];
                }
            }			
			$nestedData		=	array(); 
			$nestedData[] 	= 	"<span class='center-block text-center'>". $row['id'] ."</span>";
            $nestedData[]   .=  "<a href='javascript:void(0)' data-toggle='popover' data-html='true' data-placement='right' onclick='showThumbnail(this)'>"
                            . "<img src='".$foto_url."' class='img-responsive img-rounded' width='70' alt='No Image' style='margin:0 auto;'> </a>";
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   $row["sku"];
            $nestedData[]   =   "<span class='center-block text-center'>". $row["stok"] ."</span>";
            $nestedData[]   =   $row["tanggal_tambah_stok"];
            $nestedData[]   =   $row["tanggal_kurang_stok"];
            $nestedData[]   =   "
                                <a class='divpopover btn btn-sm btn-default' href='javascript:void(0)' data-toggle='popover' data-placement='top' data-html='true' title='Tambah Stok' onclick=tambahStok('".$row['id']."')><i class='fa fa-plus'></i>
                                </a>
                                <a class='divpopover btn btn-sm btn-default' href='javascript:void(0)' data-toggle='popover' data-placement='top' data-html='true' title='Tambah Stok' onclick=kurangStok('".$row['id']."')><i class='fa fa-minus'></i>
                                </a>
                                ";
            
            $data[] = $nestedData;
			$i++;
		}
		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),
					"recordsTotal"    => intval( $totalData ),
					"recordsFiltered" => intval( $totalFiltered ),
					"data"            => $data
					);
		echo json_encode($json_data);
    }
    function ubahStok(){
    	$params = $this->input->post();
    	if($params != null){
    		$dataUpdate = array();
    		$dataInsert = array();
    		$dataCondition['id'] = $params['idProduk'];
    		$state 	= 	$params['state'];
    		$qty	=	$params['qty'];
    		$dataStok = $this->Transaksibahanmasukmodel->select($dataCondition, 'm_bahan');
    		$lastStok = $dataStok->row()->stok;
    		$dateNow = date('Y-m-d H:i:s');

    		$dataUpdate['edited_by']	=	isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;

    		$dataInsert['id_bahan']		=	$params['idProduk'];
    		$dataInsert['last_edited']	=	$dateNow;
    		$dataInsert['edited_by']	=	isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['jumlah']		=	$qty;
    		$dataInsert['deleted']		=	1;

    		if ($state == "kurang") {
    			if ($lastStok < $qty) {
    				echo json_encode(array("status"=>0));
    				exit();
    			}else{
    				$dataUpdate['tanggal_kurang_stok'] = $dateNow;
    				$dataUpdate['last_edited'] = $dateNow;
    				$dataUpdate['stok'] = $lastStok - $qty;
    				$dataInsert['status'] = 1; //sesuai keterangan di tabel h_stok_produk
    				$dataInsert['stok_akhir'] = $lastStok - $qty;
					$dataInsert['keterangan'] = $this->session->userdata('nama_user')." mengurangi sebanyak ".$qty." stok ";
    			}
    		}else if($state == "tambah"){
				$dataUpdate['tanggal_tambah_stok'] = $dateNow;
				$dataUpdate['last_edited'] = $dateNow;
				$dataUpdate['stok'] = $lastStok + $qty;
				$dataInsert['status'] = 2; //sesuai keterangan di tabel h_stok_produk
				$dataInsert['stok_akhir'] = $lastStok + $qty;
				$dataInsert['keterangan'] = $this->session->userdata('nama_user')." menambahkan sebanyak ".$qty." stok ";
    		}
    		$updateProduk = $this->Transaksibahanmasukmodel->update($dataCondition, $dataUpdate, 'm_bahan');
    		if($updateProduk){
    			$insertHistori = $this->Transaksibahanmasukmodel->insert($dataInsert, 'h_stok_bahan');
    			if($insertHistori){
    				echo json_encode(array("status"=>1));
    			}else{
    				echo json_encode(array("status"=>0));
    			}
    		}else{
    			echo json_encode(array("status"=>0));
    		}
    	}
    }
}