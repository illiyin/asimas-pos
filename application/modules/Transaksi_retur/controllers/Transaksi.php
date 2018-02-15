<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends MX_Controller {
    private $modul = "Transaksi_retur/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksireturmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
	// function _remap()
	// {
	//       echo 'No direct access allowed';
	// }    
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Transaksireturmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$this->load->view('Transaksi_retur/view');
    }
    function detail($id = 0){
    	$data['id'] = $id;
    	$this->load->view('Transaksi_retur/detail', $data);
    }
    function data(){
		$requestData= $_REQUEST;
		$columns = array( 
			0 	=>	'id', 
			1 	=>	'id_order', 
			2 	=> 	'namacus',
			3	=> 	'catatan',
			4	=> 	'total_qty',
			5	=> 	'total_harga',
			6	=> 	'date_add',
			7	=> 	'status',
			// 8	=> 	'proses',
			8	=> 	'aksi'
		);
		$sql = " SELECT t_retur.* , m_customer.nama as namacus";
		$sql.= " FROM t_retur ";
		$sql.= " LEFT JOIN m_customer ON t_retur.id_customer = m_customer.id ";
		$query=$this->Transaksireturmodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;
		$sql.=" WHERE t_retur.deleted=1 ";
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( id_order LIKE '%".$requestData['search']['value']."%' ";    
			$sql.=" OR m_customer.nama LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_retur.date_add LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR catatan LIKE '".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksireturmodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksireturmodel->rawQuery($sql);
		$data = array();
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 

			$nestedData[] 	= 	"<span class='center-block text-center'>".$row["id"]."</span>";
			$nestedData[] 	= 	"<span class='center-block text-center'>".$row["id_order"]."</span>";
			$nestedData[] 	= 	$row["namacus"];
			$nestedData[] 	= 	$row["catatan"];
			$nestedData[] 	= 	"<span class='center-block text-center'>".$row["total_qty"]."</span>";
			$nestedData[] 	= 	"<span class='pull-right money'>".$row["total_harga"]."</span>";
			$nestedData[] 	= 	$row["date_add"];
			// $nestedData[] 	= 	$row["status"]==1?"Belum Diproses":"Telah Diproses";
            $nestedData[]   =  $row["status"]==1?'<input type="checkbox" id="toggle_'.$row["id"].'" class="bootstrap-toggle" data-width="130" title="Belum Diproses">':'Telah Diproses';
			$nestedData[] 	= "<div class='btn-group'>"	
						."<button class='btn btn-default btn-sm' onclick=detail('".$row["id"]."') title='Detail Retur'><i class='fa fa-file-text-o'></i></button>"
						."<a href='".base_url('Transaksi_retur/Transaksi/invoices/'.$row['id'])."') target='_blank' class='btn btn-default btn-sm' title='Cetak Invoice'> <i class='fa fa-print'></i> </a>"
						."</div>";			
			$data[] = $nestedData;
		}
		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),
					"recordsTotal"    => intval( $totalData ),
					"recordsFiltered" => intval( $totalFiltered ),
					"data"            => $data
					);
		echo json_encode($json_data);
    }
    function updateProses($idRetur){
    	$dataCondition['deleted'] = 1;
    	$dataCondition['id'] = $idRetur;
    	$dataUpdate['status'] = 2;
    	$updateData = $this->Transaksireturmodel->update($dataCondition, $dataUpdate, 't_retur');
    	if($update){
    		echo json_encode(array("status"=>1));
    	}else{
    		echo json_encode(array("status"=>0));
    	}
    }
    function data_detail($id_po){
		$requestData= $_REQUEST;
		$columns = array( 
			0 	=>	'rid', 
			1 	=> 	'nama',
			2 	=> 	'nama_ukuran',
			3 	=> 	'nama_warna',
			4	=> 	'trjm',
			5	=> 	'trhb',
			6	=> 	'trhj',
			7	=> 	'trth'
		);
		$sql = "SELECT 
					t_retur.id as rid,
					t_retur_detail.id as trid, 
					t_retur_detail.jumlah as trjm,
					t_retur_detail.harga_beli as trhb, 
					t_retur_detail.harga_jual as trhj, 
					t_retur_detail.total_harga as trth, 
					m_produk.nama as nama,
					m_produk.sku as sku,
					m_produk_ukuran.nama as nama_ukuran,
					m_produk_warna.nama as nama_warna
					";
		$sql.=" FROM t_retur";
		$sql.=" LEFT JOIN t_retur_detail ON t_retur.id = t_retur_detail.id_retur";
		$sql.=" LEFT JOIN m_produk on t_retur_detail.id_produk = m_produk.id";
		$sql.=" LEFT JOIN m_produk_ukuran on t_retur_detail.id_ukuran = m_produk_ukuran.id";
		$sql.=" LEFT JOIN m_produk_warna on t_retur_detail.id_warna = m_produk_warna.id";
		$sql.=" WHERE t_retur.deleted=1 ";
		$sql.=" AND t_retur_detail.id_retur=".$id_po;
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_produk.nama LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.sku LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.kode_barang LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.deskripsi LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksireturmodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;		
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksireturmodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$data = array();
		$i=1;
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 
			$nestedData[] 	= 	$i;
			$nestedData[] 	= 	$row['nama'];
			$nestedData[] 	= 	!empty($row['nama_ukuran']) ? $row['nama_ukuran'] : 'Tidak ada';
			$nestedData[] 	= 	!empty($row['nama_warna']) ? $row['nama_warna'] : 'Tidak ada';
			$nestedData[] 	= 	$row['nama'];
			$nestedData[] 	= 	"<span class='center-block text-center'>".$row['trjm']."</span>";
			$nestedData[] 	= 	"<span class='pull-right money'>".$row['trhb']."</span>";
			$nestedData[] 	= 	"<span class='pull-right money'>".$row['trhj']."</span>";
			$nestedData[] 	= 	"<span class='pull-right money'>".$row['trth']."</span>";
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
    function getCustomer(){
    	$dataSelect['deleted'] = 1;
    	$selectData = $this->Transaksireturmodel->select($dataSelect, 'm_customer');
    	return json_encode($selectData->result_array());
    }
    /*function getKategori(){
    	$dataSelect['deleted'] = 1;
    	$selectData = $this->Transaksireturmodel->select($dataSelect, 'm_produk_kategori');
    	return json_encode($selectData->result_array());
    }*/
    function getKategori($idOrder){
    	if(!empty($idOrder)) {
	    	$selectData = $this->Transaksireturmodel->rawQuery("SELECT m_produk_kategori.id, m_produk_kategori.nama FROM m_produk
					INNER JOIN m_produk_kategori ON m_produk.id_kategori = m_produk_kategori.id
					INNER JOIN t_order_detail ON m_produk.id = t_order_detail.id_produk
					WHERE t_order_detail.id_order = ".$idOrder."
					GROUP BY m_produk.id_kategori");
	    	echo json_encode($selectData->result_array());
    	}
    	else {
	    	echo json_encode(array());
    	}
    }
    function getAvailableOrder($idCustomer){
    	$data['id_customer'] 	= $idCustomer;
    	$data['deleted']		= 1;
    	$selectData = $this->Transaksireturmodel->select($data, 't_order');
    	echo json_encode($selectData->result_array());
    }
    function getOrder(){
    	$data = array();
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "RETUR"){    				
		    		$nestedData = array();
		    		$nestedData['rowid'] = $items['rowid'];
		    		$nestedData['id'] = $items['id'];
		    		$nestedData['qty'] = $items['qty'];
		    		$nestedData['harga_beli'] = number_format($items['price']);
		    		$nestedData['produk'] = $items['name'];
		    		$nestedData['rowid'] = $items['rowid'];
		    		$nestedData['subtotal'] = number_format($items['price']*$items['qty']);

		    		$nestedData['ukuran'] = $items['options']['ukuran']!=null?$items['options']['ukuran']:0;
		    		$nestedData['text_ukuran'] = $items['options']['text_ukuran'];
		    		$nestedData['warna'] = $items['options']['warna']!=null?$items['options']['warna']:0;
		    		$nestedData['text_warna'] = $items['options']['text_warna'];
		    		$nestedData['total_berat'] = $items['options']['total_berat']!=null?$items['options']['total_berat']:0;
		    		array_push($data, $nestedData);
    			}
    		}
    	}
    	return json_encode($data);
    }
    function getAvailableProduk($idOrder){
    	$sql = "SELECT m_produk.*, t_order_detail.id AS id_detail_order, t_order_detail.harga_jual, t_order_detail.id_ukuran, t_order_detail.id_warna, t_order_detail.nama_warna, t_order_detail.nama_ukuran FROM t_order
				INNER JOIN t_order_detail ON t_order_detail.id_order = t_order.id
				INNER JOIN m_produk ON m_produk.id = t_order_detail.id_produk
				WHERE t_order.id = ".$idOrder;
		$exeQuery = $this->Transaksireturmodel->rawQuery($sql);
		echo json_encode($exeQuery->result_array());
    }
    function getProduk($supplier = null){
    	$list = null;
    	$dataSelect['deleted'] = 1;
    	if($supplier != null){
    		$dataSelect['id_supplier'] = $supplier;
    	}
    	$list = $this->Transaksireturmodel->select($dataSelect, 'm_produk');
    	return json_encode($list->result_array());
    }
    /*function getProdukByName($keyword = null, $supplier = null){
    	$list = null;
    	$dataCondition['deleted'] = 1;
		$dataLike = array();
		$dataCondition = array();
    	if($keyword != null || $keyword != ""){
    		$dataLike['nama'] = $keyword;
    		
    	}
    	$list = $this->Transaksireturmodel->like($dataCondition, $dataLike, 'm_produk');
    	return json_encode($list->result_array());
    }   */
    function getProdukByName($keyword = '', $customer = '', $id_order = '', $kategori = ''){
        $list = null; 
        $where_customer = $where_id_order = $where_kategori = '';
        $keyword = strtolower($keyword);
        if(!empty($customer)) {
            $where_customer = " AND C.id_customer = ".$customer;
        }
        if(!empty($id_order)) {
            $where_id_order = " AND B.id_order = ".$id_order;
        }
        if(!empty($kategori)) {
            $where_kategori = " AND A.id_kategori = ".$kategori;
        }
        $sql = "SELECT A.*, B.id AS id_detail_order FROM m_produk A"
        	." INNER JOIN t_order_detail B ON A.id = B.id_produk"
        	." INNER JOIN t_order C ON B.id_order = C.id"
        	." WHERE A.deleted = '1'"
        	.$where_id_order
        	.$where_customer
        	.$where_kategori
            ." AND ( LOWER(A.nama) LIKE '%".$keyword."%'"
            ." OR LOWER(A.deskripsi) LIKE '%".$keyword."%'"
            ." OR LOWER(A.harga_beli) LIKE '%".$keyword."%'"
            ." OR LOWER(B.nama_ukuran) LIKE '%".$keyword."%'"
            ." OR LOWER(B.nama_warna) LIKE '%".$keyword."%')";
        $dataLike = array();
        $list = $this->Transaksireturmodel->rawQuery($sql);
        return json_encode($list->result_array());
    }
    function getProdukByKategori($order=null, $kategori=0, $keyword=''){
        $keyword = strtolower($keyword);
        $where_order = $where_kategori = '';
        if(!empty($order)) {
	        if(!empty($order)) {
	            $where_order = " AND B.id_order = ".$order;
	        }
	        if(!empty($kategori)) {
	            $where_kategori = " AND A.id_kategori = ".$kategori;
	        }
	        $sql = "SELECT A.*, B.id AS id_detail_order, B.nama_ukuran, B.nama_warna FROM m_produk A"
				." INNER JOIN t_order_detail B ON A.id = B.id_produk"
	    		." WHERE A.deleted = '1'"
	    		.$where_order
	    		.$where_kategori
	    		." AND ( LOWER(A.nama) LIKE '%".$keyword."%')";

	        $dataProduk = $this->Transaksireturmodel->rawQuery($sql);
        }
        return json_encode($dataProduk->result_array());
    }
    function getSupplier(){
    	$dataSelect['deleted'] = 1;
    	return json_encode($this->Transaksireturmodel->select($dataSelect, 'm_supplier_produk')->result_array());
    }
    function filterProduk($supplier){
    	echo $this->getProduk($supplier);
    }
    function filterProdukByName(){
    	$params  = $this->input->post();
		$keyword = null;
		if($params['keyword'] != null || $params['keyword'] != "" ){
			$keyword = $params['keyword'];
		}
    	$customer = $params['customer'];
    	$id_order = $params['id_order'];
    	echo $this->getProdukByName($keyword, $customer, $id_order);
    }
    function filterProdukByKategori($kategori, $keyword = null){
    	echo $this->getProdukByKategori($kategori, $keyword);
    }
    function getWarna(){
    	$dataSelect['deleted'] = 1;
    	$selectData = $this->Transaksireturmodel->select($dataSelect, 'm_produk_warna');
    	return json_encode($selectData->result_array());
    }
    function getUkuran(){
    	$dataSelect['deleted'] = 1;
    	$selectData = $this->Transaksireturmodel->select($dataSelect, 'm_produk_ukuran');
    	return json_encode($selectData->result_array());
    }
    function getUkuranById($id){
        $list = null;
        $dataSelect['deleted'] = 1;
        $dataSelect['id'] = $id;
        $list = $this->Transaksireturmodel->select($dataSelect, 'm_produk_ukuran');
        return $list->row();
    }
    function getWarnaById($id){
        $list = null;
        $dataSelect['deleted'] = 1;
        $dataSelect['id'] = $id;
        $list = $this->Transaksireturmodel->select($dataSelect, 'm_produk_warna');
        return $list->row();
    }

    //-----------------------------------------------------------
    private function get_detail_stok($id_produk) {
        //fetch detail_stok from current product
        $result = 0;
        if(!empty($id_produk)) {
            $condition = array('id' => $id_produk, 'deleted' => 1);
            $data_produk = $this->Transaksireturmodel->select($condition, 'm_produk')->row();

            $result = isset($data_produk->detail_stok) ? $data_produk->detail_stok : 0;
        }
        return $result;
    }
    private function build_detail_stok($detail_stok, $id_warna=0, $id_ukuran=0, $nama_warna, $nama_ukuran, $qty, $operasi, $nama_produk) {
        //build new detail_stok json data
        $result = 0;
        if(!empty($detail_stok)) {
            $obj_data = json_decode($detail_stok);
            $arr_data = json_decode($detail_stok, true);
            $new_stok = $qty;
            end($arr_data); $index = (key($arr_data) + 1);
            $new_detail_stok = array();

            foreach ($obj_data as $key => $value) {
                if(($value->id_warna == $id_warna) && ($value->id_ukuran == $id_ukuran)) {
                    
                    if($operasi == 'tambah') {
                        $new_stok = ($new_stok + $arr_data[$key]['stok']);
                    }
                    else if ($operasi == 'kurang') {
                        //cek apakah stok tidak bisa dikurangi
                        if($arr_data[$key]['stok'] < $qty) {
                            echo json_encode(array("status"=>2, "message"=>"Stok untuk produk ".$nama_produk." warna ".$nama_warna." ukuran ".$nama_ukuran." terlalu sedikit untuk dikurangi"));
                            exit();
                        }
                        else {
                            $new_stok = ($arr_data[$key]['stok'] - $qty);
                        }
                    }
                    $index = $key;
                }
            }
            $arr_data[$index] = array(
                    'id_warna' => $id_warna,
                    'id_ukuran' => $id_ukuran,
                    'nama_warna' => $nama_warna,
                    'nama_ukuran' => $nama_ukuran,
                    'stok' => $new_stok
                );
            $result = json_encode($arr_data);
        }
        return $result;
    }
    private function find_detail_stok($detail_stok, $id_warna=0, $id_ukuran=0) {
        //find stok of current product with certain warna & ukuran
        $result = 'null';
        if(!empty($detail_stok)) {
            $obj_data = json_decode($detail_stok);
            foreach ($obj_data as $item) {
                if(($item->id_warna == $id_warna) && ($item->id_ukuran == $id_ukuran)) {
                    $result = $item->stok;
                }
            }
        }
        return $result;
    }
    private function total_detail_stok($id_produk) {
        //find total stok of current product
        $result = 0;
        if(!empty($id_produk)) {
            $detail_stok = $this->get_detail_stok($id_produk) ;
            $obj_data = json_decode($detail_stok);
            $total_stok = 0;
            foreach ($obj_data as $item) {
                $total_stok = (int)$total_stok + (int)$item->stok;
            }
            $result = $total_stok;
        }
        return $result;
    }
    //-----------------------------------------------------------

    function transaksi(){
    	$dataSelect['deleted'] = 1;
    	$data['list_produk'] = $this->getProduk();
        $data['list_order'] = $this->getOrder();
        $data['list_customer'] = $this->getCustomer();
        // $data['list_kategori'] = $this->getKategori();
        
        $data['list_warna'] = $this->getWarna();
        $data['list_ukuran'] = $this->getUkuran();
        
        $data['total'] = $this->cart->total();
        $data['total_items'] = $this->cart->total_items();
        $data['tax'] = 0;
        $data['discount'] = 0;
    	$this->load->view('Transaksi_retur/transaksi', $data);
    }
    function getTotal(){
    	$total = 0;
    	$total_item = 0;
    	foreach ($this->cart->contents() as $items) {    		
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "RETUR"){
    				$total = $total + ($items['price'] * $items['qty']);
    				$total_item += $items['qty'];
    			}
    		}
    	}    	
    	echo json_encode(array("tax"=>0, "discount"=> 0, "total"=> $total, "total_items"=>$total_item));
    }
    function updateCart($id, $qty, $state = 'tambah'){
    	$getid = $this->in_cart($id, 'id', 'rowid');
    	$dataSelect['deleted'] = 1;
    	$dataSelect['id'] = $getid;
    	$selectData = $this->Transaksireturmodel->select($dataSelect, 'm_produk');
    	$lastQty = $this->in_cart($id, 'qty', 'rowid');
    	if($state == 'tambah'){		
			$data = array(
			        'rowid'  => $id,
			        'qty'    => $lastQty+1
			);
			$this->cart->update($data);
			echo $this->getOrder();   	
    	}else{
			$data = array(
			        'rowid'  => $id,
			        'qty'    => $qty
			);
			$this->cart->update($data);
			echo $this->getOrder();   	    		
    	}
    }
    function updateOption($id, $warna, $ukuran, $total_berat){
		$data = array(
		        'rowid'  => $id,
		        'options'=> array('warna'=>$warna,'ukuran'=>$ukuran,'total_berat'=>$total_berat)
		);
		$this->cart->update($data);
		echo $this->getOrder();  
    }
    function updateUkuran($id,  $warna, $ukuran, $total_berat){
		$data = array(
		        'rowid'  => $id,
		        'options'=> array('warna'=>$warna,'ukuran'=>$ukuran,'total_berat'=>$total_berat)
		);
		$this->cart->update($data);
		echo $this->getOrder();      	
    }
    function updateQty($id, $qty){
    	$params = $this->input->post();
    	$id_produk = 0;
    	//checking jumlah produk yang dibeli
    	if(!empty($params['id_customer'] && $params['id_order'])) {
    		$id_customer = $params['id_customer'];
    		$id_order = $params['id_order'];
    		
    		//fetching id produk by rowid
    		foreach ($this->cart->contents() as $items) {
	    		$idProduks = explode("_", $items['id']);
	    		if(count($idProduks) > 1){
	    			if($idProduks[1] == "RETUR"){
    					$id_produk = $idProduks[0];
	    			}
	    		}
	    	}

	    	$itemOptions = $this->getOptionById($id);
    		$condition = array(
    					'id_order' => $id_order,
    					'id_produk' => $id_produk,
    					'id_ukuran' => !empty($itemOptions) ? $itemOptions['ukuran'] : 'null',
    					'id_warna' => !empty($itemOptions) ? $itemOptions['warna'] : 'null'
    				);
    		$data_db = $this->Transaksireturmodel->select($condition, 't_order_detail')->row();
    		
    		if($qty > $data_db->jumlah) { //jika melebihi qty terjual
				echo json_encode(array('status' => 0, 'list' => $data_db));
    		} 
    		else { //jika tidak melebihi qty terjual
				$data = array(
				        'rowid'  => $id,
				        'qty'=> $qty
				);
				$this->cart->update($data);
				echo json_encode(array('status' => 1, 'getOrder' => $this->getOrder()));
    		}
    	}
    }
    function updateTotalBerat($id,  $warna, $ukuran, $total_berat){
		$data = array(
		        'rowid'  => $id,
		        'options'=> array('warna'=>$warna,'ukuran'=>$ukuran,'total_berat'=>$total_berat)
		);
		$this->cart->update($data);
		echo $this->getOrder();      	
    }
    function updateHargaBeli($id, $hargaBeli){
		$data = array(
		        'rowid'  => $id,
		        'price'	 => $hargaBeli
		);
		$this->cart->update($data);
		echo $this->getOrder();      	
    }
    function checkCart(){
    	echo json_encode($this->cart->contents());
    }
    function testLastQty($id){
    	$lastQty = $this->in_cart($id, 'qty', 'rowid');
    	echo $lastQty;    	
    }
    function deleteCart($id){
    	$this->cart->remove($id);
    	echo $this->getOrder();
    }
    function destroyCart(){
    	foreach ($this->cart->contents() as $items) {
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "RETUR"){
    				$this->cart->remove($items['rowid']);
    			}
    		}
    	}
    	echo $this->getOrder();	
    }
	function tambahCart($id){
		$params	= $this->input->post();
		$idUkuran = !empty($params['id_ukuran']) ? $params['id_ukuran'] : 0;
		$idWarna = !empty($params['id_warna']) ? $params['id_warna'] : 0;
        $textUkuran = $this->getUkuranById($idUkuran);
        $textWarna = $this->getWarnaById($idWarna);

        $cart_id = $id."_RETUR"."_".$idUkuran."_".$idWarna; //idProduk_RETUR_idUkuran_idWarna

		// $inCart = $this->in_cart($id."_RETUR");
		$inCart = $this->in_cart($cart_id);
		$params	= $this->input->post();
		$idCustomer = $params['id_customer'];
        $idOrder = $params['id_order'];
		$idDetailOrder = $params['id_detail_order'];
		$currentQty = $params['current_qty'] + 1;

		//fetching data produk yang dibeli
		$condition = array(
					'id_order' => $idOrder,
					'id_produk' => $id,
					'id_ukuran' => $idUkuran,
					'id_warna' => $idWarna
				);
		$data_db = $this->Transaksireturmodel->select($condition, 't_order_detail')->row();

		if($inCart == 'false') {
			
    		if($currentQty > $data_db->jumlah) { //jika melebihi qty terjual
    			echo json_encode(array('status' => 0, 'list' => $data_db));
    		}
    		else { //jika tidak melebihi qty terjual
    			$dataSelect['deleted'] = 1;
				$dataSelect['id'] = $id;
				$selectData = $this->Transaksireturmodel->select($dataSelect, 'm_produk');
				$hargaProduk = $this->gethargaProduk($idDetailOrder);
				
                if($hargaProduk != 0) {
					$datas = array(
		                // 'id'      => $selectData->row()->id."_RETUR",
		                'id' => $cart_id,
		                'qty' => 1,
		                'price' => $hargaProduk,
		                'name' => $selectData->row()->nama,
		                'harga_jual_normal' => $data_db->harga_jual_normal,
		                'potongan' => $data_db->potongan,
		                'total_potongan' => $data_db->total_potongan,
				        'options' => array(
			        				'ukuran' => $idUkuran,
			        				'warna' => $idWarna,
			        				'total_berat' => $selectData->row()->berat*1,
			        				'text_warna' => !empty($textWarna) ? $textWarna->nama : 'Tidak ada',
			        				'text_ukuran' => !empty($textUkuran) ? $textUkuran->nama : 'Tidak ada'
			        				)
					);
					$this->cart->insert($datas);
				}
				echo json_encode(array('status' => 1, 'getOrder' => $this->getOrder()));
    		}
			
		}
		else {
			// $qty = $this->in_cart($id."_RETUR", 'qty') + 1;
			// $rowid = $this->in_cart($id."_RETUR", 'rowid');
			$qty = $this->in_cart($cart_id, 'qty') + 1;
			$rowid = $this->in_cart($cart_id, 'rowid');
			if($qty > $data_db->jumlah) {
    			echo json_encode(array('status' => 0,'rowid' => $rowid, 'list' => $data_db));
			}
			else {
				$this->updateCart($inCart, $qty);
			}
		}
	}
	function getHargaProduk($idDetailOrder = null){
        $harga = 0;
		$getData = $this->Transaksireturmodel->rawQuery("SELECT harga_jual FROM t_order_detail WHERE id = ".$idDetailOrder)->row();
        
        if(!empty($getData)) {
            $harga = $getData->harga_jual;
        }
        return $harga;
	}
	function in_cart($product_id = null, $type = 'rowid', $filter = 'id') {
	    if($this->cart->total_items() > 0){
	        $in_cart = array();
	        foreach ($this->cart->contents() AS $item){
	            $in_cart[$item[$filter]] = $item[$type];
	        }
	        if($product_id){
	            if (array_key_exists($product_id, $in_cart)){
	                return $in_cart[$product_id];
	            }else{            	
		            return "false";
	            }
	        }else{
	            return $in_cart;
	        }
	    }else{    	
		    return "false";
	    }
	}	
    function _getTotal(){
    	$total = 0;
    	$total_item = 0;
    	$total_potongan = 0;
    	foreach ($this->cart->contents() as $items) {    		
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "RETUR"){
    				$total += ($items['price']*$items['qty']);
    				$total_item += $items['qty'];
    				$total_potongan += $items['total_potongan'];
    			}
    		}
    	}
    	return json_encode(array("tax"=>0, "discount"=> 0, "total"=> $total, "total_items"=>$total_item, "total_potongan"=>$total_potongan));
    }
    function save(){
    	$params = $this->input->post();
    	if($params != null){
    		$dateNow = date('Y-m-d H:i:s');
    		$getTotal = json_decode($this->_getTotal(), true);
    		$returns = null;
    		$dataInsert['id_order'] = $params['idOrder'];
    		$dataInsert['id_customer'] = $params['idCustomer'];
    		$dataInsert['catatan'] = $params['catatan'];
    		$dataInsert['total_qty'] = $getTotal['total_items'];
    		$dataInsert['total_harga'] = $getTotal['total'];
    		$dataInsert['total_potongan'] = $getTotal['total_potongan'];
    		$dataInsert['status'] = 1;
    		$dataInsert['date_add'] = $dateNow;
    		$dataInsert['add_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['deleted'] = 1;
    		$insertRetur = $this->Transaksireturmodel->insert($dataInsert, 't_retur');
    		if($insertRetur){
    			$dataInsertT = array();
    			$getID = $this->Transaksireturmodel->select($dataInsert, 't_retur');
    			$dataInsertT['id_retur'] = $getID->row()->id;
    			foreach ($this->cart->contents() as $items) {
	    			$idProduks = explode("_", $items['id']);
	    			if(count($idProduks) > 1){
	    				if($idProduks[1]=="RETUR"){
	    					$dataSelectProduk['deleted'] = 1;
	    					$dataSelectProduk['id'] = $idProduks[0];
	    					$getDataProduk = $this->Transaksireturmodel->select($dataSelectProduk, 'm_produk');

				    		$dataInsertT['id_produk'] =	$getDataProduk->row()->id;
				    		$dataInsertT['harga_beli'] = $getDataProduk->row()->harga_beli;
				    		$dataInsertT['harga_jual'] = $items['price'];
				    		$dataInsertT['jumlah'] = $items['qty'];
				    		$dataInsertT['total_harga']	= $items['price'] * $items['qty'];
				    		$dataInsertT['id_ukuran'] =	$items['options']['ukuran'];
				    		$dataInsertT['id_warna'] = $items['options']['warna'];
				    		$dataInsertT['harga_jual_normal'] = $items['harga_jual_normal'];
				    		$dataInsertT['potongan'] = $items['potongan'];
				    		$dataInsertT['total_potongan'] = $items['total_potongan'];
				    		$returns = $this->Transaksireturmodel->insert($dataInsertT, 't_retur_detail');
	    				}
	    			}
    			}
    			if($returns){
			    	foreach ($this->cart->contents() as $items) {
			    		$idProduks = explode("_", $items['id']);
			    		if(count($idProduks) > 1){
			    			if($idProduks[1] == "RETUR"){
			    				$this->cart->remove($items['rowid']);
			    			}
			    		}
			    	}
    				echo json_encode(array("status"=>1));
    			}else{
    				echo json_encode(array("status"=>0));
    			}
    		}else{
    			echo json_encode(array("status"=>0));
    		}
    	}else{
    		echo json_encode(array("status"=>0));
    	}
    }
    function doSubmit(){
    	$params = $this->input->post();
    	if($params != null){
    		$getTotal = json_decode($this->_getTotal(), true);
            $dataInsert['id_purchase_order'] = $params['idpo'];
    		$dataInsert['id_supplier'] 	= $params['supplier'];
    		$dataInsert['catatan']		= $params['catatan'];
    		$dataInsert['total_berat'] = $this->getOption('total_berat');
    		$dataInsert['total_qty'] = $getTotal['total_items'];
    		$dataInsert['total_harga_beli'] = $getTotal['total'];
    		$dataInsert['add_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['edited_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['deleted'] = 1;
    		$insertDataMaster = $this->Transaksireturmodel->insert($dataInsert, 't_beli');
    		if($insertDataMaster){    		
	    		$getDataID = $this->Transaksireturmodel->select($dataInsert, 't_beli');
	    		foreach ($this->cart->contents() as $items){
	    			$idProduks = explode("_", $items['id']);
	    			if(count($idProduks) > 1){
	    				if($idProduks[1]=="RETUR"){					
				    		$dataInsertDetail['id_beli']		        =	$getDataID->row()->id;
				    		$dataInsertDetail['id_produk']				=	$idProduks[0];	
				    		$dataInsertDetail['id_ukuran']				=	$items['options']['ukuran'];
				    		$dataInsertDetail['id_warna']				=	$items['options']['warna'];
				    		$dataInsertDetail['jumlah']					=	$items['qty'];
				    		$dataInsertDetail['total_berat']			=	$items['options']['total_berat'];
				    		$dataInsertDetail['harga_beli']				=	$items['price'];
				    		$dataInsertDetail['total_harga']			=	$items['price'] * $items['qty'];
				    		$insertDetail = $this->Transaksireturmodel->insert($dataInsertDetail, 't_beli_detail');
	    				}
	    			}
	    		}
    		}
    	}
    	$this->destroyCart();
    }
    function testtCart(){
    	echo json_encode($this->cart->contents());
    }
    function payment(){
    	$params = $this->input->post();
    	if($params != null){
    		$idOrder = 0;
    		$realIDORDER = 0;
    		$dateNow = date('Y-m-d H:i:s');
    		$getTotal = json_decode($this->_getTotal(), true);
    		$dataInsertTorder['id_customer'] 					= 	$params['id_customer'];
    		$dataInsertTorder['catatan']						=	$params['catatan'];
    		$dataInsertTorder['total_berat']					=	$this->getOption('total_berat');
    		$dataInsertTorder['total_qty']						=	$getTotal['total_items'];
    		$dataInsertTorder['total_harga_barang']				=	$getTotal['total'];
    		$dataInsertTorder['grand_total']					=	$getTotal['total'] + 0;
    		$dataInsertTorder['profit']							=	0;
    		$dataInsertTorder['jenis_order']					=	$params['jenisOrder'];
    		$dataInsertTorder['status']							=	1;
    		$dataInsertTorder['date_add']						=	$dateNow;
    		$dataInsertTorder['add_by']							=	isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsertTorder['deleted']						=	1;
    		$dataInsertTorder['id_metode_pembayaran']			=	$params['paymentMethod'];
    		$insertTorder = $this->Transaksireturmodel->insert($dataInsertTorder, 't_order');

    		if($insertTorder){
    			// insert ke h_transaksi
    			$dataHtransaksi['jenis_transaksi'] 	= 4;
    			$dataHtransaksi['id_referensi']		= $params['chequenum'];
    			$dataHtransaksi['keterangan']		= $params['catatan'];
    			$dataHtransaksi['date_add']			= $dateNow;
    			$dataHtransaksi['add_by']			= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    			$dataHtransaksi['deleted']			= 1;
    			$insertHtransaksi = $this->Transaksireturmodel->insert($dataHtransaksi, 'h_transaksi');

    			if($insertHtransaksi){
    				// insert ke t_order_detail
		    		$getDataID = $this->Transaksireturmodel->select($dataInsertTorder, 't_order');
		    		$realIDORDER = $getDataID->row()->id;
		    		$insertDetail = false;
		    		foreach ($this->cart->contents() as $items){
		    			$idProduks = explode("_", $items['id']);
		    			if(count($idProduks) > 1){
		    				if($idProduks[1]=="RETUR"){
		    					$dataDetail['id'] = $idProduks[0];
		    					$getHargaBeli = $this->Transaksireturmodel->select($dataDetail, 'm_produk');
		    					$idOrder = $getHargaBeli->row()->id;
					    		$dataInsertDetail['id_order']		        =	$getDataID->row()->id;
					    		$dataInsertDetail['id_produk']				=	$idProduks[0];	
					    		$dataInsertDetail['id_ukuran']				=	$items['options']['ukuran'];
					    		$dataInsertDetail['id_warna']				=	$items['options']['warna'];
					    		$dataInsertDetail['jumlah']					=	$items['qty'];
					    		$dataInsertDetail['total_berat']			=	$items['options']['total_berat'];
					    		$dataInsertDetail['harga_beli']				=	$getHargaBeli->row()->harga_beli;
					    		$dataInsertDetail['harga_jual']				=	$items['price'];
					    		$dataInsertDetail['total_harga']			=	$items['price'] * $items['qty'];
					    		$dataInsertDetail['profit']					=	$items['price'] - $getHargaBeli->row()->harga_beli;
					    		$dataInsertDetail['profit']					=	$items['price'] - $getHargaBeli->row()->harga_beli;
					    		$insertDetail = $this->Transaksireturmodel->insert($dataInsertDetail, 't_order_detail');

								if($insertDetail){
									//update stok
									$getIdDetail = $this->Transaksireturmodel->select($dataInsertDetail, 't_order_detail');
									$dataConditionStok['id'] 					= $idProduks[0];
									$dataUpdateStok['stok']	 					= $getHargaBeli->row()->stok - $items['qty'];
									$dataUpdateStok['last_edited']	 			= $dateNow;
									$dataUpdateStok['edited_by']	 			= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
									$dataUpdateStok['tanggal_kurang_stok']	 	= $dateNow;
									$updateStokProduk = $this->Transaksireturmodel->update($dataConditionStok, $dataUpdateStok, 'm_produk');

									if($updateStokProduk){
										// insert ke h_stok_produk
										$dataHstok['id_produk'] 		= $idProduks[0];
										$dataHstok['id_order_detail']	= $getIdDetail->row()->id;
										$dataHstok['id_service']		= 0;
										$dataHstok['jumlah']	 		= $items['qty'];
										$dataHstok['stok_akhir'] 		= $getHargaBeli->row()->stok - $items['qty'];
										$dataHstok['keterangan'] 		= "TRANSAKSI RETUR OLEH ".$this->session->userdata('nama_user');
										$dataHstok['status']			= 1;
										$dataHstok['date_add']			= $dateNow;
                                        $dataHstok['add_by']            = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
										$dataHstok['edited_by']			= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
										$dataHstok['deleted']			= 1;
										$insertHstok = $this->Transaksireturmodel->insert($dataHstok, 'h_stok_produk');
									}
								}
		    				}
		    			}
		    		}

		    		if($insertHstok){
				    	foreach ($this->cart->contents() as $items) {
				    		$idProduks = explode("_", $items['id']);
				    		if(count($idProduks) > 1){
				    			if($idProduks[1] == "RETUR"){
				    				$this->cart->remove($items['rowid']);
				    			}
				    		}
				    	}

		    			echo json_encode(array('idOrder'=>$realIDORDER));
		    		}else{
		    			echo json_encode(array('status'=>0));
		    		}
    			}else{
    				echo json_encode(array('status'=>0));
    			}
    		}else{
    			echo json_encode(array('status'=>0));
    		}
    	}else{
    		echo json_encode(array('status'=>0));
    	}
    }
    function getOption($option){
    	$total = 0;
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if (count($idProduks) > 1) {
    			if ($idProduks[1] == "RETUR") {
		    		$total += $items['options'][$option];
    			}
    		}
    	}
    	return $total;
    }
    function getOptionById($rowid){
    	$options = array();
    	foreach ($this->cart->contents() as $items){
    		if($items['rowid'] == $rowid) {
    			$options = $items['options'];
    		}
    	}
    	return $options;
    }
    function invoices($idRetur){
    	$sql = " SELECT 
					m_customer.nama as namacus,
					m_customer.alamat as alamatcus,
					m_customer.no_telp as notelpcus,
					t_retur.id as orderinvoice,
					t_retur.date_add as orderdate,
					t_retur.total_harga as ordertotal,
					t_retur.total_potongan as totalpotongan,
                    m_produk.kode_barang as kodeprod,
					m_produk.sku as skuprod,
					m_produk.nama as namaprod,
					m_produk.deskripsi as deskprod,
					m_produk_ukuran.nama as nama_ukuran,
					m_produk_warna.nama as nama_warna,
					t_retur_detail.harga_jual_normal as detailjualnormal,
					t_retur_detail.harga_jual as detailjual,
					t_retur_detail.jumlah as jumlahjual,
					t_retur_detail.potongan as potongan,
					t_retur_detail.total_harga as totaljual";
		$sql.= " FROM t_retur";
		$sql.= " LEFT JOIN t_retur_detail ON t_retur.id = t_retur_detail.id_retur";
		$sql.= " LEFT JOIN m_produk on t_retur_detail.id_produk = m_produk.id";
		$sql.= " LEFT JOIN m_customer ON t_retur.id_customer = m_customer.id";
		$sql.= " LEFT JOIN m_produk_ukuran on t_retur_detail.id_ukuran = m_produk_ukuran.id";
		$sql.= " LEFT JOIN m_produk_warna on t_retur_detail.id_warna = m_produk_warna.id";
		$sql.= " WHERE t_retur.deleted = '1' AND t_retur.id=".$idRetur;
		$exeQuery = $this->Transaksireturmodel->rawQuery($sql);
		$data['data'] = $exeQuery;
		$this->load->view('Transaksi_retur/invoice', $data);
    }
    function testInvoices(){
    	$this->load->view('Transaksi_retur/invoice');
    }
}