<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends MX_Controller {
    private $modul = "Transaksi_penjualan/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksipenjualanmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Transaksipenjualanmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
        $data['list_metode_pembayaran'] = $this->getMetodePembayaran();
    	$this->load->view('Transaksi_penjualan/view', $data);
    }
    function detail($id = 0){
    	$data['id'] = $id;
    	$this->load->view('Transaksi_penjualan/detail', $data);
    }
    function data(){
		$requestData= $_REQUEST;
		$columns = array( 
			0 	=>	'id', 
			1 	=>	'id_customer', 
			2 	=> 	'catatan',
			3	=> 	'total_berat',
			4	=> 	'total_qty',
			// 5	=> 	'biaya_kirim',
			5	=> 	'grand_total',
			6	=> 	'date_add',
			7	=> 	'aksi'
		);
		$sql = " SELECT t_order.* , m_customer.nama as namacus, m_metode_pembayaran.nama as namamet";
		$sql.= " FROM t_order ";
		$sql.= " LEFT JOIN m_customer ON t_order.id_customer = m_customer.id ";
		$sql.= " LEFT JOIN m_metode_pembayaran ON t_order.id_metode_pembayaran = m_metode_pembayaran.id ";
		$query=$this->Transaksipenjualanmodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;
		$sql.=" WHERE t_order.deleted = 1 ";
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_customer.nama LIKE '%".$requestData['search']['value']."%' ";    
			$sql.=" OR t_order.catatan LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_order.total_berat LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_order.total_qty LIKE '%".$requestData['search']['value']."%' ";
			// $sql.=" OR t_order.biaya_kirim LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_order.total_harga_barang LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksipenjualanmodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $query=$this->Transaksipenjualanmodel->rawQuery($sql);
        
        $data = array();
        foreach ($query->result_array() as $row) {
            $btnpayment_html = "<button class='btn btn-default btn-sm disabled' title='Pembayaran' disabled style='background-color:#f1f1f1'><i class='fa fa-money'></i></button>";

            if($row['status'] == 1) {
                $btnpayment_html = "<button class='btn btn-default btn-sm' onclick=payment('".$row["id"]."') title='Pembayaran'><i class='fa fa-money'></i></button>";
            }

			$nestedData		=	array(); 
			$nestedData[] 	= 	"<span class='center-block text-center'>". $row["id"] ."</span>";
			$nestedData[] 	= 	$row["namacus"];
			$nestedData[] 	= 	$row["catatan"];
			$nestedData[] 	= 	'<span class="money">'.$row["total_berat"]."</span>";
			$nestedData[] 	= 	"<span class='center-block text-center'>". $row["total_qty"] ."</span>";
			// $nestedData[] 	= 	"<span class='pull-right'>".number_format($row["biaya_kirim"])."</span>";
			$nestedData[] 	= 	"<span class='pull-right'>".number_format($row["total_harga_barang"])."</span>";
			$nestedData[] 	= 	$row["date_add"];
			$nestedData[] 	= 	"<div class='btn-group'>"
                        ."<button class='btn btn-default btn-sm' onclick=detail('".$row["id"]."') title='Detail Penjualan'><i class='fa fa-file-text-o'></i></button>"      
                        .$btnpayment_html
                        ."<a href='".base_url('Transaksi_penjualan/Transaksi/invoices/'.$row['id'])."') target='_blank' class='btn btn-default btn-sm' title='Cetak Invoice'> <i class='fa fa-print'></i> </a>"
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
			0 	=>	'podid', 
			1 	=> 	'nama',
			2	=> 	'ukuran',
			3	=> 	'warna',
			4	=> 	'jumlah',
			5	=> 	'total_berat',
			6	=> 	'harga_beli',
            7   =>  'harga_jual',
            8   =>  'harga_jual_normal',
            9   =>  'potongan',
			10	=> 	'total_potongan',
			11	=> 	'total_harga',
			12	=> 	'profit'
		);
		$sql = "SELECT
                    t_order_detail.nama_warna as nama_warna,
                    t_order_detail.nama_ukuran as nama_ukuran,
					t_order.id as poid,
					t_order_detail.id as podid, 
					t_order_detail.jumlah as podjm,
					t_order_detail.total_berat as podtb, 
					t_order_detail.harga_beli as podhb, 
                    t_order_detail.harga_jual as podhj, 
                    t_order_detail.harga_jual_normal as podhjn, 
                    t_order_detail.potongan as podpot, 
					t_order_detail.total_potongan as podtpot, 
					t_order_detail.total_harga as podth, 
					t_order_detail.profit as podp, 
					m_produk_ukuran.nama as ukuran,
					m_produk_warna.nama as warna,
					m_produk.nama as nama,
					m_produk.sku as sku";
		$sql.=" FROM t_order";
		$sql.=" LEFT JOIN t_order_detail ON t_order.id = t_order_detail.id_order";
		$sql.=" LEFT JOIN m_produk on t_order_detail.id_produk = m_produk.id";
		$sql.=" LEFT JOIN m_produk_ukuran on t_order_detail.id_ukuran = m_produk_ukuran.id";
		$sql.=" LEFT JOIN m_produk_warna on t_order_detail.id_warna = m_produk_warna.id";
		$sql.=" WHERE t_order.deleted=1 ";
		$sql.=" AND t_order_detail.id_order=".$id_po;
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_produk.nama LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.sku LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.kode_barang LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.deskripsi LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksipenjualanmodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;		
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksipenjualanmodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$data = array();
		$i=1;
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 
			$nestedData[] 	= 	"<span class='center-block text-center'>". $i ."</span>";
			$nestedData[] 	= 	$row['nama'];
			$nestedData[] 	= 	$row['nama_ukuran'];
			$nestedData[] 	= 	$row['nama_warna'];
			$nestedData[] 	= 	"<span class='center-block text-center'>".$row['podjm']."</span>";
			$nestedData[] 	= 	'<span class="money">'.$row['podtb'].'</span>';
			$nestedData[] 	= 	"<span class='pull-right'>".number_format($row['podhb'])."</span>";
            $nestedData[]   =   "<span class='pull-right'>".number_format($row['podhj'])."</span>";
            $nestedData[]   =   "<span class='pull-right'>".number_format($row['podhjn'])."</span>";
            $nestedData[]   =   "<span class='pull-right'>".number_format($row['podpot'])."</span>";
			$nestedData[] 	= 	"<span class='pull-right'>".number_format($row['podtpot'])."</span>";
			$nestedData[] 	= 	"<span class='pull-right'>".number_format($row['podth'])."</span>";
			$nestedData[] 	= 	"<span class='pull-right'>".number_format($row['podp'])."</span>";
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
    	$selectData = $this->Transaksipenjualanmodel->select($dataSelect, 'm_customer');
    	return json_encode($selectData->result_array());
    }
    function getKategori(){
        $dataSelect['deleted'] = 1;
        $selectData = $this->Transaksipenjualanmodel->select($dataSelect, 'm_produk_kategori');
        return json_encode($selectData->result_array());
    }
    function getBank(){
    	$dataSelect['deleted'] = 1;
    	$selectData = $this->Transaksipenjualanmodel->select($dataSelect, 'm_bank');
    	return json_encode($selectData->result_array());
    }
    function getOrder(){
    	$data = array();
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "PENJUALAN"){    				
		    		$nestedData = array();
		    		$nestedData['rowid'] = $items['rowid'];
		    		$nestedData['id'] = $items['id'];
		    		$nestedData['qty'] = $items['qty'];
                    $nestedData['harga_beli'] = $items['price'];
		    		$nestedData['harga_jual_normal'] = $items['price_normal'];
		    		$nestedData['produk'] = $items['name'];
		    		$nestedData['rowid'] = $items['rowid'];
		    		$nestedData['subtotal'] = number_format($items['price']*$items['qty']);

                    $nestedData['ukuran'] = $items['options']['ukuran']!=null?$items['options']['ukuran']:0;
		    		$nestedData['text_ukuran'] = $items['options']['text_ukuran'];
                    $nestedData['warna'] = $items['options']['warna']!=null?$items['options']['warna']:0;
                    $nestedData['text_warna'] = $items['options']['text_warna'];
                    // $nestedData['total_berat'] = $items['options']['total_berat']!=null?$items['options']['total_berat']:0;
		    		$nestedData['total_berat'] = $items['total_berat']!=null?$items['total_berat']:0;
		    		array_push($data, $nestedData);
    			}
    		}
    	}
    	return json_encode($data);
    }
    function getOrderArray(){
    	$data = array();
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "PENJUALAN"){    				
		    		$nestedData = array();
		    		$nestedData['rowid'] = $items['rowid'];
		    		$nestedData['id'] = $items['id'];
		    		$nestedData['qty'] = $items['qty'];
                    $nestedData['harga_beli'] = $items['price'];
		    		$nestedData['harga_jual_normal'] = $items['price_normal'];
		    		$nestedData['produk'] = $items['name'];
		    		$nestedData['rowid'] = $items['rowid'];
		    		$nestedData['subtotal'] = number_format($items['price']*$items['qty']);

		    		$nestedData['ukuran'] = $items['options']['ukuran']!=null?$items['options']['ukuran']:0;
                    $nestedData['text_ukuran'] = $items['options']['text_ukuran'];
                    $nestedData['warna'] = $items['options']['warna']!=null?$items['options']['warna']:0;
                    $nestedData['text_warna'] = $items['options']['text_warna'];
                    // $nestedData['total_berat'] = $items['options']['total_berat']!=null?$items['options']['total_berat']:0;
		    		$nestedData['total_berat'] = $items['total_berat']!=null?$items['total_berat']:0;
		    		array_push($data, $nestedData);
    			}
    		}
    	}
    	return $data;
    }    
    function getProduk($supplier = null){
    	$list = null;
    	$dataSelect['deleted'] = 1;
    	if($supplier != null){
    		$dataSelect['id_supplier'] = $supplier;
    	}
    	$list = $this->Transaksipenjualanmodel->select($dataSelect, 'm_produk');
    	return json_encode($list->result_array());
    }
    /*function getProdukByName($keyword = null, $supplier = null, $kategori = null){
        $list = null;
        $dataCondition['deleted'] = 1;
        $dataCondition = array();
        $dataLike = array();
        if($keyword != null){
            $dataLike['nama'] = $keyword;
        }
        if($kategori != null || $kategori !=""){
            $dataCondition['id_kategori'] = $kategori;
        }        
        $list = $this->Transaksipenjualanmodel->like($dataCondition, $dataLike, 'm_produk');
        return json_encode($list->result_array());
    }   */
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
        $list = $this->Transaksipenjualanmodel->rawQuery($sql);
        return json_encode($list->result_array());
    }
    function getProdukByKategori($kategori = null, $keyword = null){
    	$list = null;
    	$dataCondition['deleted'] = 1;
    	$dataLike = array();
    	if(!empty($kategori)){
    		$dataCondition['id_kategori'] = $kategori;
    	}
    	if($keyword != null){
    		$dataLike['nama'] = $keyword;
    	}
    	$list = $this->Transaksipenjualanmodel->like($dataCondition, $dataLike, 'm_produk');
    	return json_encode($list->result_array());
    }    
    function getSupplier(){
    	$dataSelect['deleted'] = 1;
    	return json_encode($this->Transaksipenjualanmodel->select($dataSelect, 'm_supplier_produk')->result_array());
    }
    function filterProduk($supplier){
    	echo $this->getProduk($supplier);
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
        echo $this->getProdukByName($keyword, null, $kategori);
    }
    function filterProdukByKategori($kategori, $keyword = null){
    	echo $this->getProdukByKategori($kategori, $keyword);
    }
    function getBarcode($barcode) {
        $result = array('status' => 2); //tidak ditemukan
        if(!empty($barcode)) {
            /* pattern P[id_produk]U[id_ukuran]W[id_warna] */
            $split_id = preg_split("/[\sPUW]+/", $barcode);
            $id_produk = $split_id['1'];
            $id_ukuran = $split_id['2'];
            $id_warna = $split_id['3'];

            $detail_stok = $this->get_detail_stok($id_produk);
            
            if(!empty($detail_stok)) {
                $item_stok = $this->find_detail_stok($detail_stok, $id_warna, $id_ukuran);

                $arr_data = json_decode($detail_stok, true);
                $data = array(
                        "id_produk" => $id_produk,
                        "id_warna" => $id_warna,
                        "id_ukuran" => $id_ukuran,
                        "stok" => $item_stok
                    );
                
                if($item_stok > 0) {
                    $result = array('status' => 1, 'data' => $data);
                }
                else {
                    $result = array('status' => 0);
                }
            }
        }
        echo json_encode($result);
    }
    function getWarna($id){
        $rid = explode("_", $id);
        $selectData = $this->Transaksipenjualanmodel->rawQuery("SELECT m_produk_warna.id, m_produk_warna.nama
            FROM m_produk_det_warna
            INNER JOIN m_produk ON m_produk_det_warna.id_produk = m_produk.id
            INNER JOIN m_produk_warna ON m_produk_det_warna.id_warna = m_produk_warna.id
            WHERE m_produk_det_warna.id_produk = ".$rid[0]);
        echo json_encode($selectData->result_array());
    }
    function getUkuran($id){
        $rid = explode("_", $id);
        $selectData = $this->Transaksipenjualanmodel->rawQuery("SELECT m_produk_ukuran.id, m_produk_ukuran.nama
                FROM m_produk_det_ukuran
                INNER JOIN m_produk ON m_produk_det_ukuran.id_produk = m_produk.id
                INNER JOIN m_produk_ukuran ON m_produk_det_ukuran.id_ukuran =m_produk_ukuran.id
                WHERE m_produk_det_ukuran.id_produk = ".$rid[0]);
        echo json_encode($selectData->result_array());
    }
    function getMetodePembayaran(){
        $list = null;
        $dataSelect['deleted'] = 1;
        $list = $this->Transaksipenjualanmodel->select($dataSelect, 'm_metode_pembayaran');
        return json_encode($list->result_array());
    }
    function getUkuranById($id){
        $list = null;
        $dataSelect['deleted'] = 1;
        $dataSelect['id'] = $id;
        $list = $this->Transaksipenjualanmodel->select($dataSelect, 'm_produk_ukuran');
        return $list->row();
    }
    function getWarnaById($id){
        $list = null;
        $dataSelect['deleted'] = 1;
        $dataSelect['id'] = $id;
        $list = $this->Transaksipenjualanmodel->select($dataSelect, 'm_produk_warna');
        return $list->row();
    }
    function getOrderById($id){
        $response = array('status' => 0);
        if(!empty($id)) {
            $condition = array(
                    'deleted' => 1,
                    'status' => 1, //booking
                    'id' => $id
                );
            $result = $this->Transaksipenjualanmodel->select($condition, 't_order')->row_array();
            $response = array('status' => (boolean)$result, 'data' => $result);
        }
        echo json_encode($response);
    }
    function transaksi(){

        $dataSelect['deleted'] = 1;
        $data['list_hold'] = $this->initHold();
    	$data['list_produk'] = $this->getProduk();
        $data['list_order'] = $this->getOrder();
        $data['list_customer'] = $this->getCustomer();
        $data['list_kategori'] = $this->getKategori();
        $data['list_bank'] = $this->getBank();
        $data['list_metode_pembayaran'] = $this->getMetodePembayaran();
        
        // $data['list_warna'] = $this->getWarna();
        // $data['list_ukuran'] = $this->getUkuran();
        $getTotal = json_decode($this->_getTotal(), true);
        $data['total'] = $getTotal['total'];
        $data['total_items'] = $getTotal['total_items'];
        $data['tax'] = 0;
        $data['discount'] = 0;
    	$this->load->view('Transaksi_penjualan/transaksi', $data);
    }
    function getTotal(){
    	$total = 0;
        $total_item = 0;
    	$total_potongan = 0;
    	foreach ($this->cart->contents() as $items) {    		
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "PENJUALAN"){
    				$total = $total + ($items['price'] * $items['qty']);
                    $total_item += $items['qty'];
    				$total_potongan += ($items['potongan'] * $items['qty']);
    			}
    		}
    	}    	
    	echo json_encode(array("tax"=>0, "discount"=> 0, "total"=>number_format($total), "total_items"=>$total_item, "total_potongan"=>$total_potongan));
    }
    function getCartQtyById($id) {
        $sid = explode('_', $id);
        $totalQty = 0;

        if(!empty($id)) {
            $filteredCart = array();
            $dataCart = $this->cart->contents();
            foreach ($dataCart as $itemCart) {
                $splitCartId = explode('_' ,$itemCart['id']);
                if($splitCartId[1] == "PENJUALAN") {
                    //Menghitung total qty produk yang memiliki id yang sama dengan id produk ini
                    if(($splitCartId[0] == $sid[0]) && ($splitCartId[2] == $sid[2]) && ($splitCartId[3] == $sid[3]) ) {
                        $totalQty = $itemCart['qty'];
                        // array_push($filteredCart, $itemCart);
                    }
                }
            }
        }
        return $totalQty;
    }
    function getCartQtyByIdNotThis($id) {
        $sid = explode('_', $id);
        $totalQty = 0;

        if(!empty($id)) {
            $filteredCart = array();
            $dataCart = $this->cart->contents();
            foreach ($dataCart as $itemCart) {
                $splitCartId = explode('_' ,$itemCart['id']);
                if($splitCartId[1] == "PENJUALAN") {
                    //Menghitung total qty produk dengan mengecualikan jumlah produk dengan ID ini (berdasarkan idukuran & idwarna)
                    $splitCartId23 = $splitCartId[2]."_".$splitCartId[3];
                    $sid23 = $sid[2]."_".$sid[3];
                    if(($splitCartId[0] == $sid[0]) && ($splitCartId23 != $sid23)) {
                        $totalQty = $totalQty + $itemCart['qty'];
                        array_push($filteredCart, $itemCart);
                    }
                }
            }
        }
        return $totalQty; //reserved qty
    }
    function updateOrder(){
        $response = array('status' => 0);
        $params = $this->input->post();
        if(!empty($params['id_order'])) {
            $condition = array(
                    'deleted' => 1,
                    'id' => $params['id_order']
                );
            $data = array(
                        'status' => 3, //selesai
                        'edited_by' => isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0,
                        'last_edited' => date('Y-m-d H:i:s'),
                        'id_metode_pembayaran' => $params['paymentMethod'],
                        'jenis_order' => $params['jenisOrder'],
                        'cash' => $params['paid'],
                        'uang_kembali' => $params['kembalian'],
                        'catatan' => $params['catatan']
                    );
            $result = $this->Transaksipenjualanmodel->update($condition, $data, 't_order');
            $response = array('status' => (boolean)$result, 'data' => $result);
        }
        echo json_encode($response);
    }
    function updateCart($id, $qty, $state = 'tambah'){
    	$getid = $this->in_cart($id, 'id', 'rowid');

        $dataSelect['deleted'] = 1;
    	$dataSelect['id'] = $getid;
    	$selectData = $this->Transaksipenjualanmodel->select($dataSelect, 'm_produk');

        $itemQty = $this->in_cart($id, 'qty', 'rowid');
        // $lastQty = $this->getCartQtyById($getid);
        // $reservedQty = $this->getCartQtyByIdNotThis($getid);
        if($state == 'tambah') {
            $split_id = explode('_', $getid);
            $id_produk = $split_id[0];
            $id_ukuran = $split_id[2];
            $id_warna = $split_id[3];
            $lastQty = $this->getCartQtyById($getid);

            $detail_stok = $this->get_detail_stok($id_produk);
            $item_stok = $this->find_detail_stok($detail_stok, $id_warna, $id_ukuran);
            $stokProduk = $item_stok;

            // $stokProduk = $selectData->row()->stok;
    		if($stokProduk >= ($lastQty + 1)){			
				$data = array(
				        'rowid'  => $id,
				        'qty'    => $itemQty + 1
				);
				$this->cart->update($data);
				echo json_encode(array("status" => 2, "list" => $this->getOrderArray()));
    		} else {
                //stok tidak mencukupi
                // $stokAvailable = array("stok" => ($stokProduk - $reservedQty));
                $stokAvailable = array("stok" => $stokProduk);
                // echo json_encode(array("status"=>1, "list"=>$this->getOrderArray()));
    			echo json_encode(array("lastQty" => $lastQty,"status" =>1, "list" => $stokAvailable, "rowid"=>$id));
    		}		
    	} else {
			$data = array(
			        'rowid'  => $id,
			        'qty'    => $qty
			);
			$this->cart->update($data);
			echo json_encode(array("status"=>2, "list"=>$this->getOrderArray()));
    	}
    }
    function updateOption($id, $warna, $ukuran, $total_berat){
		$data = array(
		        'rowid'  => $id,
                // 'options'=> array('warna'=>$warna,'ukuran'=>$ukuran,'total_berat'=>$total_berat)
		        'options'=> array('warna'=>$warna,'ukuran'=>$ukuran)
		);
		$this->cart->update($data);
		echo $this->getOrder();  
    }
    function updateUkuran($id,  $warna, $ukuran, $total_berat){
		$data = array(
		        'rowid'  => $id,
                // 'options'=> array('warna'=>$warna,'ukuran'=>$ukuran,'total_berat'=>$total_berat)
		        'options'=> array('warna'=>$warna,'ukuran'=>$ukuran)
		);
		$this->cart->update($data);
		echo $this->getOrder();      	
    }
    function updateQty($id, $qty){
    	$getid = $this->in_cart($id, 'id', 'rowid');
    	$dataSelect['deleted'] = 1;
    	$dataSelect['id'] = $getid;
    	$selectData = $this->Transaksipenjualanmodel->select($dataSelect, 'm_produk');

        $split_id = explode('_', $getid);
        $id_produk = $split_id[0];
        $id_ukuran = $split_id[2];
        $id_warna = $split_id[3];
        
        $detail_stok = $this->get_detail_stok($id_produk);
        $item_stok = $this->find_detail_stok($detail_stok, $id_warna, $id_ukuran);
        $stokProduk = $item_stok;

        // $stokProduk = $selectData->row()->stok;
        $lastQty = $this->getCartQtyById($getid);
        // $reservedQty = $this->getCartQtyByIdNotThis($getid);

    	if($stokProduk >= $qty){
			$data = array(
			        'rowid'  => $id,
			        'qty'=> isset($qty) ? $qty : 0
			);
			$this->cart->update($data);			
			echo json_encode(array("status"=>2, "list"=>$this->getOrderArray()));
    	}else{
    		// stok tidak mencukupi
            $stokAvailable = array(
                                // "stok" => ((int)$stokProduk - (int)$reservedQty),
                                "stok" => (int)$stokProduk,
                                // "stokProduk" => $stokProduk,
                                // "lastQty" => $lastQty,
                                // "reservedQty" => $reservedQty,
                                "qty" => $qty,
                                );
    		// echo json_encode(array("status"=>1, "id"=>$id, "list"=>$this->getOrderArray()));
            echo json_encode(array("status" => 1, "list" => $stokAvailable, "rowid"=>$id));
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
    			if($idProduks[1] == "PENJUALAN"){
    				$this->cart->remove($items['rowid']);
    			}
    		}
    	}
    	echo $this->getOrder();	
    }

    //--------------------------------------------
    private function get_detail_stok($id_produk) {
        //fetch detail_stok from current product
        $result = 0;
        if(!empty($id_produk)) {
            $condition = array('id' => $id_produk, 'deleted' => 1);
            $data_produk = $this->Transaksipenjualanmodel->select($condition, 'm_produk')->row();

            $result = isset($data_produk->detail_stok) ? $data_produk->detail_stok : 0;
        }
        return $result;
    }
    private function find_detail_stok($detail_stok, $id_warna=0, $id_ukuran=0) {
        //find stok of current product with certain warna & ukuran
        $result = 0;
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
    private function total_detail_stok($detail_stok) {
        //find total stok of current product
        $result = 0;
        if(!empty($detail_stok)) {
            $obj_data = json_decode($detail_stok);
            $total_stok = 0;
            foreach ($obj_data as $item) {
                $total_stok = (int)$total_stok + (int)$item->stok;
            }
            $result = $total_stok;
        }
        return $result;
    }
    private function build_detail_stok($detail_stok, $id_warna=0, $id_ukuran=0, $nama_warna, $nama_ukuran, $qty) {
        //build new detail_stok json data
        $result = 0;
        if(!empty($detail_stok)) {
            $obj_data = json_decode($detail_stok);
            $arr_data = json_decode($detail_stok, true);
            $new_stok = 0;
            end($arr_data); $index = (key($arr_data) + 1);
            $new_detail_stok = array();

            foreach ($obj_data as $key => $value) {
                if(($value->id_warna == $id_warna) && ($value->id_ukuran == $id_ukuran)) {
                    $new_stok = ($arr_data[$key]['stok'] - $qty);
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
    //--------------------------------------------
	function tambahCart($id){
		$params	= $this->input->post();
        $idCustomer = $params['idCustomer'];
        $idUkuran = !empty($params['idUkuran']) ? $params['idUkuran'] : 0;
		$idWarna = !empty($params['idWarna']) ? $params['idWarna'] : 0;
        $textUkuran = $this->getUkuranById($idUkuran);
        $textWarna = $this->getWarnaById($idWarna);
        // $inCart = $this->in_cart($id."_PENJUALAN");
        
        $cart_id = $id."_PENJUALAN"."_".$idUkuran."_".$idWarna; //idProduk_PENJUALAN_idUkuran_idWarna
        $inCart = $this->in_cart($cart_id);
        
		if($inCart == 'false'){
			$dataSelect['deleted']=1;
			$dataSelect['id'] = $id;
			$selectData = $this->Transaksipenjualanmodel->select($dataSelect, 'm_produk');

            $select_id = !empty($selectData->row()) ? $selectData->row()->id : 'null';
			$hargaCustomer = $this->getHargaCustomer($select_id, $idCustomer);

            $condition = array('id' => $select_id, 'deleted' => 1);
            $dataProduk = $this->Transaksipenjualanmodel->select($condition, 'm_produk')->row();
            $lastQty = $this->getCartQtyById($cart_id);

			if($hargaCustomer != 0){
                // if($selectData->row()->stok > 0){   
                // if($selectData->row()->stok > $lastQty){    
                $detail_stok = $this->get_detail_stok($id);
                $item_stok = $this->find_detail_stok($detail_stok, $idWarna, $idUkuran);

				if($item_stok > $lastQty){	
                    $price_customer = $this->getHargaCustomer($selectData->row()->id, $idCustomer);			
					$datas = array(
                        // 'id'      => $selectData->row()->id."_PENJUALAN_".date('YmdHis'),
                        // 'id'      => $selectData->row()->id."_PENJUALAN",
                        'id'      => $cart_id,
                        'name'    => $selectData->row()->nama,
                        'qty'     => 1,
                        'price'   => $price_customer,
                        'price_normal' => $dataProduk->harga_jual_normal,
                        'potongan' => $dataProduk->harga_jual_normal - $price_customer,
                        'total_berat' => $selectData->row()->berat,
				        'options' => array(
                                    'ukuran' => $idUkuran,
                                    'text_ukuran' => !empty($textUkuran) ? $textUkuran->nama : 'Tidak ada',
                                    'warna' => $idWarna,
                                    'text_warna' => !empty($textWarna) ? $textWarna->nama : 'Tidak ada',
			        				// 'total_berat' => $selectData->row()->berat
			        				)
					               );
					$this->cart->insert($datas);
					echo json_encode(array("status"=>2, "list"=>$this->getOrderArray()));
				}else{
					// stok kosong
					// echo json_encode(array("status"=>1, "list"=>$this->getOrderArray()));
                    echo json_encode(array("status" =>1, "list" => $selectData->row_array()));
				}
			}else{
				// harga customer belum diset
				echo json_encode(array("status"=>0, "list"=>$this->getOrderArray()));
			}			
		}
        else {
            // $qty = $this->in_cart($id."_PENJUALAN", 'qty');
			$qty = $this->in_cart($cart_id, 'qty');
			$this->updateCart($inCart, $qty);
		}
	}

	function getHargaCustomer($idProduk = null, $idCustomer = null){
		$getData = $this->Transaksipenjualanmodel->rawQuery("SELECT m_produk_det_harga.harga AS harga, m_produk.harga_jual_normal AS harga_jual_normal FROM m_produk
				INNER JOIN m_produk_det_harga ON m_produk_det_harga.id_produk = m_produk.id
				INNER JOIN m_customer ON m_produk_det_harga.id_customer_level = m_customer.id_customer_level
				WHERE m_produk.id = ".$idProduk." AND m_customer.id = ".$idCustomer);
        $harga = 0;
        $dataHarga = $getData->row();
        if(!empty($dataHarga)) {
            $harga = $dataHarga->harga;
        }
        else {
            $condition = array('id' => $idProduk, 'deleted' => 1);
            $dataProduk = $this->Transaksipenjualanmodel->select($condition, 'm_produk')->row();
            $harga = !empty($dataProduk) ? $dataProduk->harga_jual_normal : 0;
        }
        // return $getData->num_rows()>0 ? $getData->row()->harga : 0;
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
    /*function in_cart($product_id = null, $type = 'rowid', $filter = 'id') {
        $options = array('warna' => 0, 'ukuran' => 0);
        if($this->cart->total_items() > 0){
            $in_cart = array();
            foreach ($this->cart->contents() AS $item){
                $in_cart[$item[$filter]] = $item[$type];
            }
            if($product_id){
                $returnItem = array();
                $filtered_cart = $this->getCartItemById($product_id, $filter);
                    foreach ($filtered_cart as $fitem) {
                        $itemOptions = $fitem['options'];
                        $optDiff = array_diff($options, $itemOptions);
                        
                        if(empty($optDiff)) { //data dengan option yang sama sudah ada
                            $returnItem = $fitem;
                        }
                    }

                if (!empty($returnItem)){
                    return $returnItem[$type];
                }else{              
                    return "false";
                }
            }else{
                return $in_cart;
            }
        }else{      
            return "false";
        }
    }   */
    function getCartItemById($id, $filter) {
        $filtered_cart = array();
        foreach ($this->cart->contents() as $item) {
            if($id == $item[$filter]) {
                $filtered_cart[] = $item;
            }
        }
        return $filtered_cart;
    }
    function _getTotal(){
    	$total = 0;
        $total_item = 0;
    	$total_potongan = 0;
    	foreach ($this->cart->contents() as $items) {    		
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "PENJUALAN"){
    				$total += ($items['price'] * $items['qty']);
                    $total_item += $items['qty'];
    				$total_potongan += ($items['potongan'] * $items['qty']);
    			}
    		}
    	}
    	return json_encode(array("tax"=>0, "discount"=> 0, "total"=>$total, "total_items"=>$total_item, "total_potongan"=>$total_potongan));
    }    
    function doSubmit(){
    	$params = $this->input->post();
    	if($params != null){
    		$getTotal = json_decode($this->_getTotal(), true);
            $dataInsert['id_purchase_order'] = $params['idpo'];
    		$dataInsert['id_supplier'] 	= $params['supplier'];
            // $dataInsert['total_berat'] = $this->getOption('total_berat');
    		$dataInsert['catatan']		= $params['catatan'];
    		$dataInsert['total_berat'] = $getTotal['total_berat'];
    		$dataInsert['total_qty'] = $getTotal['total_items'];
    		$dataInsert['total_harga_beli'] = $getTotal['total'];
    		$dataInsert['add_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['edited_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['deleted'] = 1;
    		$insertDataMaster = $this->Transaksipenjualanmodel->insert($dataInsert, 't_beli');
    		if($insertDataMaster){    		
	    		$getDataID = $this->Transaksipenjualanmodel->select($dataInsert, 't_beli');
	    		foreach ($this->cart->contents() as $items){
	    			$idProduks = explode("_", $items['id']);
	    			if(count($idProduks) > 1){
	    				if($idProduks[1]=="PENJUALAN"){					
				    		$dataInsertDetail['id_beli']		        =	$getDataID->row()->id;
				    		$dataInsertDetail['id_produk']				=	$idProduks[0];	
				    		$dataInsertDetail['id_ukuran']				=	$items['options']['ukuran'];
				    		$dataInsertDetail['id_warna']				=	$items['options']['warna'];
				    		$dataInsertDetail['jumlah']					=	$items['qty'];
				    		$dataInsertDetail['total_berat']			=	$items['total_berat'];
				    		$dataInsertDetail['harga_beli']				=	$items['price'];
				    		$dataInsertDetail['total_harga']			=	$items['price'] * $items['qty'];
				    		$insertDetail = $this->Transaksipenjualanmodel->insert($dataInsertDetail, 't_beli_detail');
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
            $total_profit = 0;
    		$dateNow = date('Y-m-d H:i:s');
    		$getTotal = json_decode($this->_getTotal(), true);
    		$dataInsertTorder['id_customer'] 					= 	$params['id_customer'];
    		$dataInsertTorder['catatan']						=	$params['catatan'];
            $dataInsertTorder['total_berat']                    =   $this->getTotalBerat();
    		// $dataInsertTorder['total_berat']					=	$getTotal['total_berat'];
    		$dataInsertTorder['total_qty']						=	$getTotal['total_items'];
    		$dataInsertTorder['total_harga_barang']				=	$getTotal['total'];
    		$dataInsertTorder['grand_total']					=	$getTotal['total'] + 0;
            $dataInsertTorder['profit']                         =   0;
            $dataInsertTorder['jenis_order']                    =   $params['jenisOrder'];
            $dataInsertTorder['status']                         =   3;
            $dataInsertTorder['date_add']                       =   $dateNow;
            $dataInsertTorder['add_by']                         =   isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
            $dataInsertTorder['deleted']                        =   1;
            $dataInsertTorder['id_metode_pembayaran']           =   $params['paymentMethod'];
            // $dataInsertTorder['id_bank']                        =   $params['id_bank'];
            // $dataInsertTorder['nomor_kartu']                        =   $params['nomor_kartu'];
            $dataInsertTorder['cash']                           =   $params['paid'];
            $dataInsertTorder['uang_kembali']                   =   $params['kembalian'];
            $dataInsertTorder['total_potongan']                 =   $getTotal['total_potongan'];
    		$insertTorder = $this->Transaksipenjualanmodel->insert($dataInsertTorder, 't_order');

    		if($insertTorder){
    			// insert ke h_transaksi
    			$dataHtransaksi['jenis_transaksi'] 	= 4;
    			// $dataHtransaksi['id_referensi']		= $params['chequenum'];
    			$dataHtransaksi['keterangan']		= $params['catatan'];
    			$dataHtransaksi['date_add']			= $dateNow;
    			$dataHtransaksi['add_by']			= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    			$dataHtransaksi['deleted']			= 1;
    			$insertHtransaksi = $this->Transaksipenjualanmodel->insert($dataHtransaksi, 'h_transaksi');

    			if($insertHtransaksi){
    				// insert ke t_order_detail
		    		$getDataID = $this->Transaksipenjualanmodel->select($dataInsertTorder, 't_order');
		    		$realIDORDER = $getDataID->row()->id;
		    		$insertDetail = false;

		    		foreach ($this->cart->contents() as $items) {
		    			$idProduks = explode("_", $items['id']);
		    			if(count($idProduks) > 1){
		    				if($idProduks[1]=="PENJUALAN"){
		    					$dataDetail['id'] = $idProduks[0];
		    					$getHargaBeli = $this->Transaksipenjualanmodel->select($dataDetail, 'm_produk');
		    					$idOrder = $getHargaBeli->row()->id;
					    		$dataInsertDetail['id_order']		        =	$getDataID->row()->id;
					    		$dataInsertDetail['id_produk']				=	$idProduks[0];	
					    		$dataInsertDetail['id_ukuran']				=	$items['options']['ukuran'];
					    		$dataInsertDetail['id_warna']				=	$items['options']['warna'];
                                $dataInsertDetail['jumlah']                 =   $items['qty'];
                                $dataInsertDetail['total_berat']            =   $items['total_berat'] * $items['qty'];
                                $dataInsertDetail['harga_beli']             =   $getHargaBeli->row()->harga_beli;
                                $dataInsertDetail['harga_jual']             =   $items['price'];
                                $dataInsertDetail['total_harga']            =   $items['price'] * $items['qty'];
                                $dataSelectWarna['id']  =   $items['options']['warna'];
                                $selectDataWarna = $this->Transaksipenjualanmodel->select($dataSelectWarna, 'm_produk_warna');
                                $dataSelectUkuran['id'] =   $items['options']['ukuran'];
                                $selectDataUkuran = $this->Transaksipenjualanmodel->select($dataSelectUkuran, 'm_produk_ukuran');
                                $dataInsertDetail['nama_warna']             =   $selectDataWarna->num_rows()>0?$selectDataWarna->row()->nama:"Tidak Ada Warna";
                                $dataInsertDetail['nama_ukuran']            =   $selectDataUkuran->num_rows()>0?$selectDataUkuran->row()->nama:"Tidak Ada Ukuran";
                                $dataInsertDetail['profit']                 =   ($items['price'] - $getHargaBeli->row()->harga_beli) * $items['qty'];
                                $total_profit = $total_profit + $dataInsertDetail['profit'];
                                $dataInsertDetail['harga_jual_normal'] = $items['price_normal'];
					    		$dataInsertDetail['potongan'] = $items['potongan'];
                                $dataInsertDetail['total_potongan'] = ($items['potongan'] * $items['qty']);
                                
                                $insertDetail = $this->Transaksipenjualanmodel->insert($dataInsertDetail, 't_order_detail');

								if($insertDetail){
									//update stok
									$getIdDetail = $this->Transaksipenjualanmodel->select($dataInsertDetail, 't_order_detail');
									$dataConditionStok['id'] = $idProduks[0];
                                    // $dataUpdateStok['stok'] = $getHargaBeli->row()->stok - $items['qty'];
                                    $detail_stok = $this->get_detail_stok($idProduks[0]);
                                    $dataUpdateStok['detail_stok'] = $this->build_detail_stok($detail_stok, $items['options']['warna'], $items['options']['ukuran'], $items['options']['text_warna'], $items['options']['text_ukuran'], $items['qty']);
                                    $dataUpdateStok['stok'] = $this->total_detail_stok($dataUpdateStok['detail_stok']);

									$dataUpdateStok['last_edited'] = $dateNow;
									$dataUpdateStok['edited_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
									$dataUpdateStok['tanggal_kurang_stok'] = $dateNow;
                                    
                                    $produk_stok = $dataUpdateStok['stok'];
									$updateStokProduk = $this->Transaksipenjualanmodel->update($dataConditionStok, $dataUpdateStok, 'm_produk');

									if($updateStokProduk){
										// insert ke h_stok_produk
										$dataHstok['id_produk'] = $idProduks[0];
										$dataHstok['id_order_detail'] = $getIdDetail->row()->id;
										$dataHstok['id_service'] = 0;
                                        $dataHstok['jumlah'] = $items['qty'];
                                        $dataHstok['id_warna'] = $items['options']['warna'];
										$dataHstok['id_ukuran'] = $items['options']['ukuran'];
                                        // $dataHstok['stok_akhir']        = $getHargaBeli->row()->stok - $items['qty'];
										$dataHstok['stok_akhir'] = $produk_stok;
										$dataHstok['keterangan'] = "Stok berkurang ".$items['qty']." dari transaksi penjualan dengan ID ".$realIDORDER;
										$dataHstok['status'] = 1;
										$dataHstok['date_add'] = $dateNow;
                                        $dataHstok['add_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
										$dataHstok['edited_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
										$dataHstok['deleted'] = 1;
										$insertHstok = $this->Transaksipenjualanmodel->insert($dataHstok, 'h_stok_produk');
									}
								}
		    				}
		    			}
		    		}// end foreach

                    //updating t_order total profit
                    $condition = array("id" => $realIDORDER);
                    $dataUpdate = array('profit' => $total_profit);
                    $updateOrderProfit = $this->Transaksipenjualanmodel->update($condition, $dataUpdate, 't_order');

		    		if($insertHstok){
				    	foreach ($this->cart->contents() as $items) {
				    		$idProduks = explode("_", $items['id']);
				    		if(count($idProduks) > 1){
				    			if($idProduks[1] == "PENJUALAN"){
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
    function getTotalBerat(){
        $total = 0;
        foreach ($this->cart->contents() as $items){
            $idProduks = explode("_", $items['id']);
            if (count($idProduks) > 1) {
                if ($idProduks[1] == "PENJUALAN") {
                    // $total += $items['options']['total_berat'];
                    $total += $items['total_berat'];
                    $total = $total * $items['qty'];
                }
            }
        }
        return $total;
    }    
    function getOption($option){
    	$total = 0;
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if (count($idProduks) > 1) {
    			if ($idProduks[1] == "PENJUALAN") {
		    		$total += $items['options'][$option];
    			}
    		}
    	}
    	return $total;
    }
    function invoices($idORder){
    	$sql = " SELECT 
					m_customer.nama as namacus,
					m_customer.alamat as alamatcus,
					m_customer.no_telp as notelpcus,
					t_order.id as orderinvoice,
					t_order.date_add as orderdate,
                    t_order.grand_total as ordertotal,
                    t_order.cash as ordercash,
                    t_order.uang_kembali as order_uang_kembali,
                    t_order.total_potongan as totalpotongan,
                    t_order.cash as bayar,
					t_order.uang_kembali as kembalian,
                    m_produk.kode_barang as kodeprod,
                    m_produk.sku as skuprod,
                    m_produk.nama as namaprod,
                    m_produk.deskripsi as deskprod,
                    t_order_detail.harga_jual as detailjual,
                    t_order_detail.jumlah as jumlahjual,
                    t_order_detail.nama_warna as nama_warna,
					t_order_detail.nama_ukuran as nama_ukuran,
                    t_order_detail.total_harga as totaljual,
                    t_order_detail.harga_jual_normal as detailjualnormal,
                    t_order_detail.potongan as potongan,
                    t_order_detail.total_harga as totaljual";

		$sql.= " FROM t_order";
		$sql.= " LEFT JOIN t_order_detail ON t_order.id = t_order_detail.id_order";
		$sql.= " LEFT JOIN m_produk on t_order_detail.id_produk = m_produk.id";
		$sql.= " LEFT JOIN m_customer ON t_order.id_customer = m_customer.id";
		$sql.= " LEFT JOIN m_produk_ukuran on t_order_detail.id_ukuran = m_produk_ukuran.id";
		$sql.= " LEFT JOIN m_produk_warna on t_order_detail.id_warna = m_produk_warna.id";
		$sql.= " WHERE t_order.id=".$idORder;
		$exeQuery = $this->Transaksipenjualanmodel->rawQuery($sql);
		$data['data'] = $exeQuery;
		$this->load->view('Transaksi_penjualan/invoice', $data);
    }
    function testInvoices(){
    	$this->load->view('Transaksi_penjualan/invoice');
    }

    function getInvoiceData($id) {//Handling modal Invoice
        if($id == 'last') {
            $condition = array('deleted' => 1);
            $data = $this->Transaksipenjualanmodel->select($condition, 't_order', 'id', 'DESC')->row();
            $id = !empty($data) ? $data->id : 0;
        }
        if(!empty($id)) {
            $sql = "SELECT A.*, B.nama AS nama_customer FROM t_order A "
                    ." LEFT JOIN m_customer B ON A.id_customer = B.id"
                    ." WHERE A.id = ".$id;
            $dataProduk = $this->Transaksipenjualanmodel->rawQuery($sql)->row();
         
            $sql = "SELECT A.*, B.nama AS nama_produk FROM t_order_detail A "
                    ." LEFT JOIN m_produk B ON A.id_produk = B.id"
                    ." LEFT JOIN m_produk_warna C ON A.id_warna = C.id"
                    ." LEFT JOIN m_produk_ukuran D ON A.id_ukuran = D.id"
                    ." WHERE A.id_order = ".$id;
            $dataDetailProduk = $this->Transaksipenjualanmodel->rawQuery($sql)->result();

            $html = '<div class="row">
              <div class="col-md-12">
                <h5 class="text-center">Iqbal POS</h5>
                <h4 class="text-center">Invoice #'.$dataProduk->id.'</h4> <hr>
                <p>Tanggal: '.date('d-m-Y H:i:s', strtotime($dataProduk->date_add)).' <br>Customer: '.$dataProduk->nama_customer.' </p> <br>
                <table class="table">
                  <thead> <tr> <th>#</th> <th>Produk</th> <th>Warna</th> <th>Ukuran</th> <th>Qty</th> <th>Subtotal (IDR)</th> </tr> </thead>
                  <tbody>';
                $i = 1; 
                foreach ($dataDetailProduk as $detail) {
                  $html .= '<tr> <td>'.$i++.'</td> <td>'.$detail->nama_produk.'</td> <td>'.$detail->nama_warna.'</td> <td>'.$detail->nama_ukuran.'</td> <td class="text-center">'.$detail->jumlah.'</td> <td class="text-right">'.number_format($detail->total_harga, 0, ',', '.').'</td> </tr>'; 
                }
                $html .= '</tbody> </table> <br>';

            $html2 = '<table class="table table-condensed"> <tbody>'
                    .'<tr style="font-weight: bold;">
                      <td style="width: 70%;">Total Harga</td>
                      <td style="width: 30%;">Rp <span class="pull-right">'.number_format($dataProduk->grand_total, 0, ',', '.').'</span></td> </tr>'
                    .'<tr style="font-weight: bold;">
                      <td style="width: 70%;">Cash</td>
                      <td style="width: 30%;">Rp <span class="pull-right">'.number_format($dataProduk->cash, 0, ',', '.').'</span></td> </tr>'
                    .'<tr style="font-weight: bold;">
                      <td style="width: 70%;">Kembali</td>
                      <td style="width: 30%;">Rp <span class="pull-right">'.number_format($dataProduk->uang_kembali, 0, ',', '.').'</span></td> </tr>'
                    .'</tbody> </table> </div> </div>';
        
            echo $html.$html2;
        }
    }

    //HOLD
    public function initHold() {
        if(empty($_SESSION['cart_holds']['PENJUALAN'])) {
            $_SESSION['cart_holds']['PENJUALAN'] = array(
                    array(
                            'id' => 1,
                            'nama' => date("H:i")
                        )
                );
        }

        $cart_holds = $_SESSION['cart_holds']['PENJUALAN'];
        return json_encode($_SESSION['cart_holds']['PENJUALAN']);
    }

    public function checkHold($tipe="PENJUALAN") {
        $result = FALSE;
        if(isset($_SESSION['cart_holds'][$tipe])) {
            //check if 'cart holds Penjualan' is empty
            if(!empty($_SESSION['cart_holds'][$tipe])) {    
                $result = end($_SESSION['cart_holds'][$tipe]);
            }
        }
        else {
            //Create empty session cart_holds Penjualan if not exist
            $this->initHold();
        }
        return $result;
    }

    public function selectHold($number) {
        /*Posale::update_all(array(
            'set' => array(
                'status' => 0
            ),
            'conditions' => array(
                'status = ? AND register_id = ?',
                1,
                $this->register
            )
        ));
        Posale::update_all(array(
            'set' => array(
                'status' => 1
            ),
            'conditions' => array(
                'number = ? AND register_id = ?',
                $number,
                $this->register
            )
        ));*/
        $cart_holds = $_SESSION['cart_holds']['PENJUALAN'];
        echo json_encode(array(
            "status" => TRUE,
            "hold" => $cart_holds
        ));
    }
    
    public function addHold($registerid=0) {
        // $hold = Hold::find('last', array(
        //     'conditions' => array(
        //         'register_id = ?',
        //         $registerid
        //     )
        // ));
        // $number = ! empty($hold) ? intval($hold->number) + 1 : 1;
        // Posale::update_all(array(
        //     'set' => array(
        //         'status' => 0
        //     ),
        //     'conditions' => array(
        //         'status = ? AND register_id = ?',
        //         1,
        //         $this->register
        //     )
        // ));
        // $attributes = array(
        //     'number' => $number,
        //     'time' => date("H:i"),
        //     'register_id' => $registerid
        // );
        // Hold::create($attributes);
        $status = FALSE;
        $lastHold = $this->checkHold();

        $number = !empty($lastHold) ? (intval($lastHold['id'])+1) : 1;
        $hold = array(
                'id' => $number,
                'nama' => date("H:i")
            );
        array_push($_SESSION['cart_holds']['PENJUALAN'], $hold);
        $status = TRUE;

        $cart_holds = $_SESSION['cart_holds']['PENJUALAN'];
        echo json_encode(array(
            "status" => $status,
            "hold" => $cart_holds
        ));
    }

    public function removeHold($number) {
       /* $hold = Hold::find('first', array(
            'conditions' => array(
                'number = ? AND register_id = ?',
                $number,
                $registerid
            )
        ));
        $hold->delete();
        Posale::delete_all(array(
            'conditions' => array(
                'number = ? AND register_id = ?',
                $number,
                $registerid
            )
        ));
        $hold = Hold::find('last', array(
            'conditions' => array(
                'register_id = ?',
                $registerid
            )
        ));
        Posale::update_all(array(
            'set' => array(
                'status' => 1
            ),
            'conditions' => array(
                'number = ? AND register_id = ?',
                $hold->number,
                $registerid
            )
        ));*/
        foreach ($_SESSION['cart_holds']['PENJUALAN'] as $key => $value) {
            if($value['id'] == $number) {
                unset($_SESSION['cart_holds']['PENJUALAN'][$key]);
            }
        }

        $cart_holds = $_SESSION['cart_holds']['PENJUALAN'];
        echo json_encode(array(
            "status" => TRUE,
            "hold" => $cart_holds
        ));
    }

}