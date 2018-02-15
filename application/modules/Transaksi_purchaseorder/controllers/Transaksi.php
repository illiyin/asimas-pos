<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends MX_Controller {
    private $modul = "Transaksi_purchaseorder/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksipomodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Transaksipomodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$this->load->view('Transaksi_purchaseorder/view');
    }
    function detail($id = 0){
    	$data['id'] = $id;
    	$this->load->view('Transaksi_purchaseorder/detail', $data);
    }
    function data(){
		$requestData= $_REQUEST;
		$columns = array( 
            0   =>  'id', 
			1 	=>	'id_supplier', 
			2 	=> 	'catatan',
			3	=> 	'total_berat',
			4	=> 	'total_qty',
			5	=> 	'total_harga_beli',
			6	=> 	'date_add',
			7	=> 	'aksi'
		);
		$sql = " SELECT t_purchase_order.* , m_supplier_produk.nama as namasup ";
		$sql.= " FROM t_purchase_order ";
		$sql.= " INNER JOIN m_supplier_produk ON t_purchase_order.id_supplier = m_supplier_produk.id ";
		$query=$this->Transaksipomodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;
		// $sql = "SELECT * ";
		$sql.=" WHERE t_purchase_order.deleted=1 ";
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_supplier_produk.nama LIKE '%".$requestData['search']['value']."%' ";    
			$sql.=" OR t_purchase_order.catatan LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_purchase_order.total_berat LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_purchase_order.total_qty LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_purchase_order.total_harga_beli LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_purchase_order.date_add LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksipomodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksipomodel->rawQuery($sql);
		$data = array();
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 

            $nestedData[]   =   "<span class='center-block text-center'>". $row["id"]. "</span>";
			$nestedData[] 	= 	$row["namasup"];
			$nestedData[] 	= 	$row["catatan"];
			$nestedData[] 	= 	'<span class="money">'.$row["total_berat"].'</span>';
			$nestedData[] 	= 	"<span class='center-block text-center'>". $row["total_qty"] ."</span>";
			$nestedData[] 	= 	'<span class="money pull-right">'.$row["total_harga_beli"].'</span>';
			$nestedData[] 	= 	$row["date_add"];
			$nestedData[] 	= 	"<div class='btn-group'>"
                        ."<button onclick=detail('".$row['id']."') class='btn btn-default btn-sm' title='Detail Purchase Order'> <i class='fa fa-file-text-o'></i> </button>"
                        ."<a href='".base_url('Transaksi_purchaseorder/Transaksi/invoices/'.$row['id'])."') target='_blank' class='btn btn-default btn-sm' title='Cetak'> <i class='fa fa-print'></i> </a>"
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
    function data_detail($id_po){
		$requestData= $_REQUEST;
		$columns = array( 
			0 	=>	'#', 
			1 	=> 	'nama',
			2	=> 	'ukuran',
			3	=> 	'warna',
			4	=> 	'jumlah',
			5	=> 	'total_berat',
			6	=> 	'harga_beli',
			7	=> 	'total_harga'
		);
		$sql = "SELECT 
					t_purchase_order.id as poid,
					t_purchase_order_detail.id as podid, 
					t_purchase_order_detail.jumlah as podjm,
					t_purchase_order_detail.total_berat as podtb, 
					t_purchase_order_detail.harga_beli as podhb, 
					t_purchase_order_detail.total_harga as podth, 
					m_produk_ukuran.nama as ukuran,
					m_produk_warna.nama as warna,
					m_produk.nama as nama,
					m_produk.sku as sku";
		$sql.=" FROM t_purchase_order";
		$sql.=" LEFT JOIN t_purchase_order_detail ON t_purchase_order.id = t_purchase_order_detail.id_purchase_order";
		$sql.=" LEFT JOIN m_produk on t_purchase_order_detail.id_produk = m_produk.id";
		$sql.=" LEFT JOIN m_produk_ukuran on t_purchase_order_detail.id_ukuran = m_produk_ukuran.id";
		$sql.=" LEFT JOIN m_produk_warna on t_purchase_order_detail.id_warna = m_produk_warna.id";
		$sql.=" WHERE t_purchase_order.deleted=1 ";
		$sql.=" AND t_purchase_order_detail.id_purchase_order=".$id_po;
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_produk.nama LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.sku LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.kode_barang LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.deskripsi LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksipomodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;		
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksipomodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$data = array();
		$i=1;
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 

			$nestedData[] 	= 	"<span class='center-block text-center'>". $i ."</span>";
			$nestedData[] 	= 	$row['nama'];
			$nestedData[] 	= 	$row['ukuran']!=null||$row['ukuran']!=0?$row['ukuran']:"Tidak Ada Ukuran";
			$nestedData[] 	= 	$row['warna']!=null||$row['warna']!=0?$row['warna']:"Tidak Ada Warna";
			$nestedData[] 	= 	"<span class='center-block text-center'>". $row['podjm'] ."</span>";
			$nestedData[] 	= 	'<span class="money">'.$row['podtb'].'</span>';
			$nestedData[] 	= 	'<span class="money pull-right">'.$row['podhb'].'</span>';
			$nestedData[] 	= 	'<span class="money pull-right">'.$row['podth'].'</span>';
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
    function invoices($idORder){
        $sql = " SELECT 
                    m_supplier_produk.nama as nama_supplier,
                    m_supplier_produk.alamat as alamat_supplier,
                    m_supplier_produk.no_telp as notel_supplier,
                    t_purchase_order.id as orderinvoice,
                    t_purchase_order.date_add as orderdate,
                    t_purchase_order.total_harga_beli as ordertotal,
                    m_produk.kode_barang as kodeprod,
                    m_produk.nama as namaprod,
                    m_produk.deskripsi as deskprod,
                    t_purchase_order_detail.nama_warna as nama_warna,
                    t_purchase_order_detail.nama_ukuran as nama_ukuran,
                    t_purchase_order_detail.harga_beli as detailjual,
                    t_purchase_order_detail.jumlah as jumlahjual,
                    t_purchase_order_detail.total_harga as totaljual";
        $sql.= " FROM t_purchase_order";
        $sql.= " LEFT JOIN t_purchase_order_detail ON t_purchase_order.id = t_purchase_order_detail.id_purchase_order";
        $sql.= " LEFT JOIN m_produk on t_purchase_order_detail.id_produk = m_produk.id";
        $sql.= " LEFT JOIN m_supplier_produk ON t_purchase_order.id_supplier = m_supplier_produk.id";
        $sql.= " LEFT JOIN m_produk_ukuran on t_purchase_order_detail.id_ukuran = m_produk_ukuran.id";
        $sql.= " LEFT JOIN m_produk_warna on t_purchase_order_detail.id_warna = m_produk_warna.id";
        $sql.= " WHERE t_purchase_order.id=".$idORder;
        $exeQuery = $this->Transaksipomodel->rawQuery($sql);
        $data['data'] = $exeQuery;
        $this->load->view('Transaksi_purchaseorder/invoice', $data);
    }    
    function confirm(){
    	$params = $this->input->post();
    	if($params != null){
    		// update t_service_detail
    		// get status stok
    		// get status confirm
    		$dataSelects = explode("_", $params['id']);
    		$dataSelect['id'] = $dataSelects[0];
    		$selectData = $this->Transaksipomodel->select($dataSelect, 't_service_detail');

    		$statusStok = $selectData->row()->kurangi_stok;
    		if ($statusStok == 1) {
    			$idProduk = $selectData->row()->id_produk;
    			$dataSelectMaster['id'] = $idProduk;
    			$selectDataMaster = $this->Transaksipomodel->select($dataSelectMaster, 'm_produk');
    			
    			$stokKembali = $params['jbk'];
    			$stokGudang = $selectDataMaster->row()->stok;

    			$stokSekarang = $stokGudang + $stokKembali;

    			// update stok master
    			$dataConditionMaster['id'] = $idProduk;
    			$dataUpdateMaster['stok'] = $stokSekarang;
    			$updateDataMaster = $this->Transaksipomodel->update($dataConditionMaster, $dataUpdateMaster, 'm_produk');
    			if($updateDataMaster){
    				// insert ke h stok produk
    				$dataInsertHistori['id_produk'] 		= $idProduk;
    				$dataInsertHistori['id_order_detail'] 	= 0;
    				$dataInsertHistori['id_service'] 		= $params['id'];
    				$dataInsertHistori['jumlah']			= $stokKembali;
    				$dataInsertHistori['stok_akhir']		= $stokSekarang;
    				$dataInsertHistori['keterangan']		= "Barang Kembali";
    				$dataInsertHistori['status']			= 5;
    				$dataInsertHistori['add_by']			= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    				$dataInsertHistori['edited_by']			= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    				$dataInsertHistori['deleted']			= 1;
    				$insertHistori = $this->Transaksipomodel->insert($dataInsertHistori, 'h_stok_produk');
    				if($insertHistori){
    					// update service detail
    					$dataConditionService['id'] = $params['id'];
    					$dataUpdateService['uang_kembali']  		= $params['juk'];
    					$dataUpdateService['status']				= $params['sts'];
    					$dataUpdateService['jumlah_barang_kembali']	= $params['jbk'];
    					$updateService = $this->Transaksipomodel->update($dataConditionService, $dataUpdateService, 't_service_detail');
    					if($updateService){
    						echo json_encode(array("status"=>1));
    					}else{
    						echo json_encode(array("status"=>0));
    					}
    				}
    			}
    		}else{
				$dataConditionService['id'] = $params['id'];
				$dataUpdateService['uang_kembali']  		= $params['juk'];
				$dataUpdateService['status']				= $params['sts'];
				$dataUpdateService['jumlah_barang_kembali']	= $params['jbk'];
				$updateService = $this->Transaksipomodel->update($dataConditionService, $dataUpdateService, 't_service_detail');
				if($updateService){
					echo json_encode(array("status"=>1));
				}else{
					echo json_encode(array("status"=>0));
				}
    		}
    	}
    }
    function getOrder(){
    	$data = array();
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "PURCHASEORDER"){    				
		    		$nestedData = array();
		    		$nestedData['rowid'] = $items['rowid'];
		    		$nestedData['id'] = $items['id'];
		    		$nestedData['qty'] = $items['qty'];
		    		$nestedData['harga_beli'] = $items['price'];
		    		$nestedData['produk'] = $items['name'];
		    		$nestedData['rowid'] = $items['rowid'];
		    		$nestedData['subtotal'] = $items['price']*$items['qty'];

                    $nestedData['total_berat'] = $items['total_berat']!=null?$items['total_berat']:0;
                    $nestedData['ukuran'] = $items['options']['ukuran']!=null?$items['options']['ukuran']:0;
                    $nestedData['text_ukuran'] = $items['options']['text_ukuran']!=null?$items['options']['text_ukuran']:0;
                    $nestedData['warna'] = $items['options']['warna']!=null?$items['options']['warna']:0;
                    $nestedData['text_warna'] = $items['options']['text_warna']!=null?$items['options']['text_warna']:0;
		    		array_push($data, $nestedData);
    			}
    		}
    	}
    	return json_encode($data);
    }
    function getProduk($supplier = null){
    	$list = null;
    	$dataSelect['deleted'] = 1;
    	if($supplier != null){
    		$dataSelect['id_supplier'] = $supplier;
    	}
    	$list = $this->Transaksipomodel->select($dataSelect, 'm_produk');
    	return json_encode($list->result_array());
    }
    // function getProdukByName($keyword = null, $supplier = null, $kategori = null){
    //     $list = null;
    //     $dataSelect['deleted'] = 1;
    //     $dataCondition = array();
    //     $dataLike = array();
    //     if($keyword != null){
    //         $dataLike['nama'] = $keyword;
    //     }
    //     if($kategori != null || $kategori !=""){
    //         $dataCondition['id_kategori'] = $kategori;
    //     }        
    //     $dataCondition['id_supplier'] = $supplier;
    //     $list = $this->Transaksiservicemodel->like($dataCondition, $dataLike, 'm_produk');
    //     return json_encode($list->result_array());
    // }
    function getProdukByName($keyword = '', $supplier = '', $kategori = ''){
        $list = null; $where_supplier = ''; $where_kategori = '';
        $keyword = strtolower($keyword);
        if(!empty($supplier)) {
            $where_supplier = " AND id_supplier = ".$supplier;
        }
        if(!empty($kategori)) {
            $where_kategori = " AND id_kategori = ".$kategori;
        }
        $sql = "SELECT * FROM m_produk WHERE deleted = '1' ".$where_supplier.$where_kategori
            ." AND ( LOWER(nama) LIKE '%".$keyword."%'"
            ." OR LOWER(deskripsi) LIKE '%".$keyword."%'"
            ." OR LOWER(harga_beli) LIKE '%".$keyword."%')";
        $dataLike = array();
        $list = $this->Transaksipomodel->rawQuery($sql);
        return json_encode($list->result_array());
    }  
    function getProdukByKategori($supplier = null, $kategori = 0, $keyword = null){
        $list = null;
        $dataLike = array();
        $dataCondition = array();
        $dataCondition['deleted'] = 1;
        if($supplier != null && $kategori != 0){
            $dataCondition['id_supplier'] = $supplier;
            $dataCondition['id_kategori'] = $kategori;
        }
        if($kategori == 0){
            $dataCondition['id_supplier'] = $supplier;
        }
        if($keyword != null){
            $dataLike['nama'] = $keyword;
        }
        $list = $this->Transaksipomodel->like($dataCondition, $dataLike, 'm_produk');
        return json_encode($list->result_array());
    }
    function getSupplier(){
    	$dataSelect['deleted'] = 1;
    	return json_encode($this->Transaksipomodel->select($dataSelect, 'm_supplier_produk')->result_array());
    }
    function filterProduk($supplier){
    	echo $this->getProduk($supplier);
    }
    function getKategori($supplier){
    	$selectData = $this->Transaksipomodel->rawQuery("SELECT m_produk_kategori.id, m_produk_kategori.nama FROM m_produk
				INNER JOIN m_produk_kategori ON m_produk.id_kategori = m_produk_kategori.id
				WHERE m_produk.id_supplier=".$supplier."
				GROUP BY m_produk.id_kategori");
    	echo json_encode($selectData->result_array());
    }
    function filterProdukByName(){
        $params  = $this->input->post();
        $keyword = null;
        $kategori = null;
        if ($params['keyword'] != null || $params['keyword'] != "") {
            $keyword = $params['keyword'];
        }
        if($params['kategori'] != null || $params['kategori'] != ""){
            $realkategori = explode("-", $params['kategori']);
            $kategori = $realkategori[1];
        }
        $supplier = $params['supplier'];
        echo $this->getProdukByName($keyword, $supplier, $kategori);
    }
    function filterProdukByKategori($supplier, $kategori, $keyword = null){
    	echo $this->getProdukByKategori($supplier, $kategori, $keyword);
    }
    function getWarna($id){
        $rid = explode("_", $id);
    	// $dataSelect['deleted'] = 1;
    	// $selectData = $this->Transaksipomodel->select($dataSelect, 'm_produk_warna');
        $selectData = $this->Transaksipomodel->rawQuery("SELECT m_produk_warna.id, m_produk_warna.nama
                FROM m_produk_det_warna
                INNER JOIN m_produk ON m_produk_det_warna.id_produk = m_produk.id
                INNER JOIN m_produk_warna ON m_produk_det_warna.id_warna = m_produk_warna.id
                WHERE m_produk_det_warna.id_produk = ".$rid[0]);
    	echo json_encode($selectData->result_array());
    }
    function getUkuran($id){
        $rid = explode("_", $id);
    	// $dataSelect['deleted'] = 1;
    	// $selectData = $this->Transaksipomodel->select($dataSelect, 'm_produk_ukuran');
        $selectData = $this->Transaksipomodel->rawQuery("SELECT m_produk_ukuran.id, m_produk_ukuran.nama
                                                        FROM m_produk_det_ukuran
                                                        INNER JOIN m_produk ON m_produk_det_ukuran.id_produk = m_produk.id
                                                        INNER JOIN m_produk_ukuran ON m_produk_det_ukuran.id_ukuran =m_produk_ukuran.id
                                                        WHERE m_produk_det_ukuran.id_produk = ".$rid[0]);
    	echo json_encode($selectData->result_array());
    }
    function getUkuranById($id){
        $list = null;
        $dataSelect['deleted'] = 1;
        $dataSelect['id'] = $id;
        $list = $this->Transaksipomodel->select($dataSelect, 'm_produk_ukuran');
        return $list->row();
    }
    function getWarnaById($id){
        $list = null;
        $dataSelect['deleted'] = 1;
        $dataSelect['id'] = $id;
        $list = $this->Transaksipomodel->select($dataSelect, 'm_produk_warna');
        return $list->row();
    }
    function transaksi(){
    	$dataSelect['deleted'] = 1;
    	$data['list_produk'] = $this->getProduk();
        $data['list_order'] = $this->getOrder();
        $data['list_supplier'] = $this->getSupplier();
        
        // $data['list_warna'] = $this->getWarna();
        // $data['list_ukuran'] = $this->getUkuran();
        
        $data['total'] = $this->cart->total();
        $data['total_items'] = $this->cart->total_items();
        $data['tax'] = 0;
        $data['discount'] = 0;
    	$this->load->view('Transaksi_purchaseorder/transaksi', $data);
    }
    function getTotal(){
    	$total = 0;
    	$total_item = 0;
    	foreach ($this->cart->contents() as $items) {    		
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "PURCHASEORDER"){
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
    	$selectData = $this->Transaksipomodel->select($dataSelect, 'm_produk');
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
		$data = array(
		        'rowid'  => $id,
		        'qty'=> $qty
		);
		$this->cart->update($data);
		echo $this->getOrder();      	
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
    			if($idProduks[1] == "PURCHASEORDER"){
    				$this->cart->remove($items['rowid']);
    			}
    		}
    	}
    	echo $this->getOrder();	
    }
	function tambahCart($id){
        $params = $this->input->post();
        $idSupplier = $params['idSupplier'];
        $idUkuran = !empty($params['idUkuran']) ? $params['idUkuran'] : 0;
        $idWarna = !empty($params['idWarna']) ? $params['idWarna'] : 0;
        $textUkuran = $this->getUkuranById($idUkuran);
        $textWarna = $this->getWarnaById($idWarna);
		// $inCart = $this->in_cart($id."_PURCHASEORDER");

        $cart_id = $id."_PURCHASEORDER"."_".$idUkuran."_".$idWarna; //idProduk_PEMBELIAN_idUkuran_idWarna
        $inCart = $this->in_cart($cart_id);

		if($inCart != 'false') {
			$qty = $this->in_cart($id."_PURCHASEORDER", 'qty') + 1;
			$this->updateCart($inCart, $qty);
		}
        else if($inCart == 'false') {
			$dataSelect['deleted'] = 1;
			$dataSelect['id']=$id;
			$selectData = $this->Transaksipomodel->select($dataSelect, 'm_produk');
            $select_id = !empty($selectData->row()) ? $selectData->row()->id : 'null';
            $hargaBeli = $selectData->row()->harga_beli;

			$datas = array(
                    'id'      => $cart_id,
                    'qty'     => 1,
                    'price'   => $selectData->row()->harga_beli,
                    'name'    => $selectData->row()->nama,
                    'total_berat' => $selectData->row()->berat,
    		        'options' => array(
        				'ukuran' => $idUkuran,
                        'text_ukuran' => !empty($textUkuran) ? $textUkuran->nama : 'Tidak ada',
                        'warna' => $idWarna,
                        'text_warna' => !empty($textWarna) ? $textWarna->nama : 'Tidak ada',
				)
			);
			$this->cart->insert($datas);
			echo $this->getOrder();
		}
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
    	foreach ($this->cart->contents() as $items) {    		
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "PURCHASEORDER"){
    				$total += ($items['price']*$items['qty']);
    				$total_item += $items['qty'];
    			}
    		}
    	}
    	return json_encode(array("tax"=>0, "discount"=> 0, "total"=> $total, "total_items"=>$total_item));
    }    
    function doSubmit(){
    	$params = $this->input->post();
    	if($params != null){
    		$getTotal = json_decode($this->_getTotal(), true);
    		$dataInsert['id_supplier'] 	      = $params['supplier'];
    		$dataInsert['catatan']		      = $params['catatan'];
    		$dataInsert['total_berat']        = $this->getTotalBerat();
    		$dataInsert['total_qty']          = $getTotal['total_items'];
    		$dataInsert['total_harga_beli']   = $getTotal['total'];
    		$dataInsert['add_by']             = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['edited_by']          = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['deleted']            = 1;
    		$insertDataMaster = $this->Transaksipomodel->insert($dataInsert, 't_purchase_order');
    		if($insertDataMaster){    		
	    		$getDataID = $this->Transaksipomodel->select($dataInsert, 't_purchase_order');
	    		foreach ($this->cart->contents() as $items){
	    			$idProduks = explode("_", $items['id']);
	    			if(count($idProduks) > 1){
	    				if($idProduks[1]=="PURCHASEORDER"){					
				    		$dataInsertDetail['id_purchase_order']		=	$getDataID->row()->id;
				    		$dataInsertDetail['id_produk']				=	$idProduks[0];	
                            $dataInsertDetail['id_ukuran']              =   $items['options']['ukuran'];
                            $dataInsertDetail['nama_ukuran']                =   $items['options']['text_ukuran'];
                            $dataInsertDetail['id_warna']               =   $items['options']['warna'];
				    		$dataInsertDetail['nama_warna']				=	$items['options']['text_warna'];
				    		$dataInsertDetail['jumlah']					=	$items['qty'];
				    		$dataInsertDetail['total_berat']			=	$items['total_berat'] * $items['qty'];
				    		$dataInsertDetail['harga_beli']				=	$items['price'];
				    		$dataInsertDetail['total_harga']			=	$items['price'] * $items['qty'];
				    		$insertDetail = $this->Transaksipomodel->insert($dataInsertDetail, 't_purchase_order_detail');
	    				}
	    			}
	    		}
    		}
    	}
    	$this->destroyCart();
    }
    function getOption($option){
    	$total = 0;
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if (count($idProduks) > 1) {
    			if ($idProduks[1] == "PURCHASEORDER") {
		    		$total += $items['options'][$option];
    			}
    		}
    	}
    	return $total;
    }
    function getTotalBerat(){
        $total = 0;
        foreach ($this->cart->contents() as $items){
            $idProduks = explode("_", $items['id']);
            if (count($idProduks) > 1) {
                if ($idProduks[1] == "PURCHASEORDER") {
                    $total += $items['total_berat'];
                    $total = $total * $items['qty'];
                }
            }
        }
        return $total;
    }    
}