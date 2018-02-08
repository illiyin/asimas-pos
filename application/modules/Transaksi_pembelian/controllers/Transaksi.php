<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends MX_Controller {
    private $modul = "Transaksi_pembelian/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksipembelianmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Transaksipembelianmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$this->load->view('Transaksi_pembelian/view');
    }
    function detail($id = 0){
    	$data['id'] = $id;
    	$this->load->view('Transaksi_pembelian/detail', $data);
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
		$sql = " SELECT t_beli.* , m_supplier_produk.nama as namasup ";
		$sql.= " FROM t_beli ";
		$sql.= " INNER JOIN m_supplier_produk ON t_beli.id_supplier = m_supplier_produk.id ";
		$query=$this->Transaksipembelianmodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;
		// $sql = "SELECT * ";
		$sql.=" WHERE t_beli.deleted=1 ";
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_supplier_produk.nama LIKE '%".$requestData['search']['value']."%' ";    
			$sql.=" OR t_beli.catatan LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_beli.total_berat LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_beli.total_qty LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_beli.total_harga_beli LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_beli.date_add LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksipembelianmodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksipembelianmodel->rawQuery($sql);
		$data = array(); $i = 1;
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 

            $nestedData[]   =   "<span class='center-block text-center'>". $row['id']."</span>";
			$nestedData[] 	= 	$row["namasup"];
			$nestedData[] 	= 	$row["catatan"];
			$nestedData[] 	= 	"<span class='money' style='display:block;'>". $row["total_berat"] ."</span>";
			$nestedData[] 	= 	"<span class='center-block text-center'>".$row["total_qty"] ."</span>";
			$nestedData[] 	= 	"<span class='pull-right money'>". $row["total_harga_beli"] ."</span>";
			$nestedData[] 	= 	$row["date_add"];
			$nestedData[] 	= 	"<div class='btn-group'>"
                            ."<button onclick=detail('".$row['id']."') class='btn btn-default btn-sm' title='Detail Pembelian'> <i class='fa fa-file-text-o'></i> </button>"
                            ."<a href='".base_url('Transaksi_pembelian/Transaksi/invoices/'.$row['id'])."') target='_blank' class='btn btn-default btn-sm' title='Cetak'> <i class='fa fa-print'></i> </a>"
                            ."</span>";
			
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
					t_beli.id as poid,
					t_beli_detail.id as podid, 
					t_beli_detail.jumlah as podjm,
					t_beli_detail.total_berat as podtb, 
					t_beli_detail.harga_beli as podhb, 
					t_beli_detail.total_harga as podth, 
					m_produk_ukuran.nama as ukuran,
					m_produk_warna.nama as warna,
					m_produk.nama as nama,
					m_produk.sku as sku";
		$sql.=" FROM t_beli";
		$sql.=" LEFT JOIN t_beli_detail ON t_beli.id = t_beli_detail.id_beli";
		$sql.=" LEFT JOIN m_produk on t_beli_detail.id_produk = m_produk.id";
		$sql.=" LEFT JOIN m_produk_ukuran on t_beli_detail.id_ukuran = m_produk_ukuran.id";
		$sql.=" LEFT JOIN m_produk_warna on t_beli_detail.id_warna = m_produk_warna.id";
		$sql.=" WHERE t_beli.deleted=1 ";
		$sql.=" AND t_beli_detail.id_beli=".$id_po;
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_produk.nama LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.sku LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.kode_barang LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.deskripsi LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksipembelianmodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;		
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksipembelianmodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$data = array(); $i = 1;
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 

			$nestedData[] 	= 	"<span class='center-block text-center'>".$i."</span>";
			$nestedData[] 	= 	$row['nama'];
			$nestedData[] 	= 	$row['ukuran']!=null||$row['ukuran']!=0?$row['ukuran']:"Tidak Ada Ukuran";
			$nestedData[] 	= 	$row['warna']!=null||$row['warna']!=0?$row['warna']:"Tidak Ada Warna";
			$nestedData[] 	= 	"<span class='center-block text-center'>". $row['podjm'] ."</span>";
			$nestedData[] 	= 	"<span class='money'>". $row['podtb'] ."</span>";
			$nestedData[] 	= 	"<span class='pull-right money'>". $row['podhb'] ."</span>";
			$nestedData[] 	= 	"<span class='pull-right money'>". $row['podth'] ."</span>";
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
                    m_supplier_produk.nama as namacus,
                    m_supplier_produk.alamat as alamatcus,
                    m_supplier_produk.no_telp as notelpcus,
                    t_beli.id as orderinvoice,
                    t_beli.date_add as orderdate,
                    t_beli.total_harga_beli as ordertotal,
                    m_produk.nama as namaprod,
                    m_produk.deskripsi as deskprod,
                    t_beli_detail.harga_beli as detailjual,
                    t_beli_detail.jumlah as jumlahjual,
                    t_beli_detail.total_harga as totaljual";
        $sql.= " FROM t_beli";
        $sql.= " LEFT JOIN t_beli_detail ON t_beli.id = t_beli_detail.id_beli";
        $sql.= " LEFT JOIN m_produk on t_beli_detail.id_produk = m_produk.id";
        $sql.= " LEFT JOIN m_supplier_produk ON t_beli.id_supplier = m_supplier_produk.id";
        $sql.= " LEFT JOIN m_produk_ukuran on t_beli_detail.id_ukuran = m_produk_ukuran.id";
        $sql.= " LEFT JOIN m_produk_warna on t_beli_detail.id_warna = m_produk_warna.id";
        $sql.= " WHERE t_beli.id=".$idORder;
        $exeQuery = $this->Transaksipembelianmodel->rawQuery($sql);
        $data['data'] = $exeQuery;
        $this->load->view('Transaksi_pembelian/invoice', $data);
    }        
    function getOrder(){
    	$data = array();
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "PEMBELIAN"){    				
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
    	$list = $this->Transaksipembelianmodel->select($dataSelect, 'm_produk');
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
        $dataCondition['id_supplier'] = $supplier;
        $list = $this->Transaksipembelianmodel->like($dataCondition, $dataLike, 'm_produk');
        return json_encode($list->result_array());
    }*/
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
        $list = $this->Transaksipembelianmodel->rawQuery($sql);
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
    	$list = $this->Transaksipembelianmodel->like($dataCondition, $dataLike, 'm_produk');
    	return json_encode($list->result_array());
    }    
    function getSupplier(){
    	$dataSelect['deleted'] = 1;
    	return json_encode($this->Transaksipembelianmodel->select($dataSelect, 'm_supplier_produk')->result_array());
    }
    function filterProduk($supplier){
    	echo $this->getProduk($supplier);
    }
    function getKategori($supplier){
    	$selectData = $this->Transaksipembelianmodel->rawQuery("SELECT m_produk_kategori.id, m_produk_kategori.nama FROM m_produk
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
    function filterProdukByKategori($supplier = null, $kategori = null, $keyword = null){
    	echo $this->getProdukByKategori($supplier, $kategori, $keyword);
    }
    function getWarna($id){
        $rid = explode("_", $id);
    	// $dataSelect['deleted'] = 1;
    	// $selectData = $this->Transaksipembelianmodel->select($dataSelect, 'm_produk_warna');
        $selectData = $this->Transaksipembelianmodel->rawQuery("SELECT m_produk_warna.id, m_produk_warna.nama
            FROM m_produk_det_warna
            INNER JOIN m_produk ON m_produk_det_warna.id_produk = m_produk.id
            INNER JOIN m_produk_warna ON m_produk_det_warna.id_warna = m_produk_warna.id
            WHERE m_produk_det_warna.id_produk = ".$rid[0]);
    	echo json_encode($selectData->result_array());
    }
    function getUkuran($id){
        $rid = explode("_", $id);
    	// $dataSelect['deleted'] = 1;
    	// $selectData = $this->Transaksipembelianmodel->select($dataSelect, 'm_produk_ukuran');
        $selectData = $this->Transaksipembelianmodel->rawQuery("SELECT m_produk_ukuran.id, m_produk_ukuran.nama
                FROM m_produk_det_ukuran
                INNER JOIN m_produk ON m_produk_det_ukuran.id_produk = m_produk.id
                INNER JOIN m_produk_ukuran ON m_produk_det_ukuran.id_ukuran =m_produk_ukuran.id
                WHERE m_produk_det_ukuran.id_produk = ".$rid[0]);
    	echo json_encode($selectData->result_array());
    }
    function getMetodePembayaran(){
        $list = null;
        $dataSelect['deleted'] = 1;
        $list = $this->Transaksipembelianmodel->select($dataSelect, 'm_metode_pembayaran');
        return json_encode($list->result_array());
    }
    function getBank(){
      $dataSelect['deleted'] = 1;
      $selectData = $this->Transaksipembelianmodel->select($dataSelect, 'm_bank');
      return json_encode($selectData->result_array());
    }
    function getUkuranById($id){
        $list = null;
        $dataSelect['deleted'] = 1;
        $dataSelect['id'] = $id;
        $list = $this->Transaksipembelianmodel->select($dataSelect, 'm_produk_ukuran');
        return $list->row();
    }
    function getWarnaById($id){
        $list = null;
        $dataSelect['deleted'] = 1;
        $dataSelect['id'] = $id;
        $list = $this->Transaksipembelianmodel->select($dataSelect, 'm_produk_warna');
        return $list->row();
    }
    function transaksi(){
      $getTotal = json_decode($this->_getTotal());
    	$dataSelect['deleted'] = 1;
    	$data['list_produk'] = $this->getProduk();
      $data['list_order'] = $this->getOrder();
      $data['list_supplier'] = $this->getSupplier();
      $data['list_bank'] = $this->getBank();
      $data['list_metode_pembayaran'] = $this->getMetodePembayaran();
      
      // $data['list_warna'] = $this->getWarna();
      // $data['list_ukuran'] = $this->getUkuran();
      // $data['total'] = $this->cart->total();
      // $data['total_items'] = $this->cart->total_items();
      $data['total'] = $getTotal->total;
      $data['total_items'] = $getTotal->total_items;
      
      $data['tax'] = 0;
      $data['discount'] = 0;
    	$this->load->view('Transaksi_pembelian/transaksi', $data);
    }
    function getTotal(){
    	$total = 0;
    	$total_item = 0;
    	foreach ($this->cart->contents() as $items) {    		
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "PEMBELIAN"){
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
    	$selectData = $this->Transaksipembelianmodel->select($dataSelect, 'm_produk');
    	$itemQty = $this->in_cart($id, 'qty', 'rowid');

    	if($state == 'tambah'){		
			$data = array(
			        'rowid'  => $id,
			        'qty'    => $itemQty + 1
			);
			$this->cart->update($data);
            echo json_encode(array("status" => 2, "list" => $this->getOrder()));
			// echo $this->getOrder();   	
    	}else{
			$data = array(
			        'rowid'  => $id,
			        'qty'    => $qty
			);
			$this->cart->update($data);
            echo json_encode(array("status" => 2, "list" => $this->getOrder()));
			// echo $this->getOrder();   	    		
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
    			if($idProduks[1] == "PEMBELIAN"){
    				$this->cart->remove($items['rowid']);
    			}
    		}
    	}
    	// echo $this->getOrder();	
    }

	function tambahCart($id){
        $params = $this->input->post();
        $idSupplier = $params['idSupplier'];
        $idUkuran = !empty($params['idUkuran']) ? $params['idUkuran'] : 0;
        $idWarna = !empty($params['idWarna']) ? $params['idWarna'] : 0;
        $textUkuran = $this->getUkuranById($idUkuran);
        $textWarna = $this->getWarnaById($idWarna);
		// $inCart = $this->in_cart($id."_PEMBELIAN");

        $cart_id = $id."_PEMBELIAN"."_".$idUkuran."_".$idWarna; //idProduk_PEMBELIAN_idUkuran_idWarna
        $inCart = $this->in_cart($cart_id);

		if($inCart != 'false') {
			$qty = $this->in_cart($id."_PEMBELIAN", 'qty') + 1;
			$this->updateCart($inCart, $qty);
		}
        else if($inCart == 'false'){
			$dataSelect['deleted']=1;
			$dataSelect['id']=$id;
			$selectData = $this->Transaksipembelianmodel->select($dataSelect, 'm_produk');
            $select_id = !empty($selectData->row()) ? $selectData->row()->id : 'null';
            $hargaBeli = $selectData->row()->harga_beli;

            if($hargaBeli != 0){
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
                        // 'total_berat' => $selectData->row()->berat
                    )
    			);
    			$this->cart->insert($datas);
                echo json_encode(array("status"=>2, "list"=>$this->getOrder()));
                // echo $this->getOrder();
            }
            else{
                // harga beli belum diset
                echo json_encode(array("status"=>0, "list"=>$this->getOrder()));
            }
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
    			if($idProduks[1] == "PEMBELIAN"){
    				$total += ($items['price']*$items['qty']);
    				$total_item += $items['qty'];
    			}
    		}
    	}
    	return json_encode(array("tax"=>0, "discount"=> 0, "total"=> $total, "total_items"=>$total_item));
    }    
    function getCartQtyById($id) {
        $sid = explode('_', $id);
        $totalQty = 0;

        if(!empty($id)) {
            $filteredCart = array();
            $dataCart = $this->cart->contents();
            foreach ($dataCart as $itemCart) {
                $splitCartId = explode('_' ,$itemCart['id']);
                if($splitCartId[1] == "PEMBELIAN") {
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
    function doSubmit(){
    	$params = $this->input->post();
      $lastInsertId = 0;
    	if($params != null){
    		$getTotal = json_decode($this->_getTotal(), true);
        //Newly added ---
        $dataInsert['id_metode_pembayaran'] = $params['paymentMethod'];
        $dataInsert['id_bank'] = $params['id_bank'];
        $dataInsert['nomor_kartu'] = $params['nomor_kartu'];
        $dataInsert['cash'] = $params['paid'];
        $dataInsert['uang_kembali'] = $params['kembalian'];
        //  ----

        $dataInsert['id_purchase_order'] = $params['idpo'];
    		$dataInsert['id_supplier'] 	= $params['supplier'];
    		$dataInsert['catatan']		= $params['catatan'];
    		$dataInsert['total_berat'] = $this->getTotalBerat();
    		$dataInsert['total_qty'] = $getTotal['total_items'];
    		$dataInsert['total_harga_beli'] = $getTotal['total'];
    		$dataInsert['add_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['edited_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsert['deleted'] = 1;
    		$insertDataMaster = $this->Transaksipembelianmodel->insert_id($dataInsert, 't_beli');

    		if($insertDataMaster){    		
          $lastInsertId = $insertDataMaster;
	    		$getDataID = $this->Transaksipembelianmodel->select($dataInsert, 't_beli');
	    		foreach ($this->cart->contents() as $items){
	    			$idProduks = explode("_", $items['id']);
	    			if(count($idProduks) > 1){
	    				if($idProduks[1]=="PEMBELIAN"){					
                $id_warna = $items['options']['warna'];
                $id_ukuran = $items['options']['ukuran'];
                $selectDataWarna = $this->Transaksipembelianmodel->select($id_warna, 'm_produk_warna')->row();
                $selectDataUkuran = $this->Transaksipembelianmodel->select($id_ukuran, 'm_produk_ukuran')->row();

				    		$dataInsertDetail['id_beli']		      =	$getDataID->row()->id;
				    		$dataInsertDetail['id_produk']				=	$idProduks[0];	
                $dataInsertDetail['id_ukuran']        = $id_ukuran;
                $dataInsertDetail['id_warna']         = $id_warna;
                $dataInsertDetail['nama_ukuran']      = !empty($selectDataUkuran->nama) ? $selectDataUkuran->nama : 'Tidak ada';
				    		$dataInsertDetail['nama_warna']			=	!empty($selectDataWarna->nama) ? $selectDataWarna->nama : 'Tidak ada';
				    		$dataInsertDetail['jumlah']					  =	$items['qty'];
				    		$dataInsertDetail['total_berat']			=	$items['total_berat'] * $items['qty'];
				    		$dataInsertDetail['harga_beli']				=	$items['price'];
				    		$dataInsertDetail['total_harga']			=	$items['price'] * $items['qty'];
				    		$insertDetail = $this->Transaksipembelianmodel->insert($dataInsertDetail, 't_beli_detail');
	    				}
	    			}
	    		}
        }
      }
      $this->destroyCart();
      $response = array('idOrder' => $lastInsertId);
      echo json_encode($response);
    }
    function getOption($option){
    	$total = 0;
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if (count($idProduks) > 1) {
    			if ($idProduks[1] == "PEMBELIAN") {
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
                if ($idProduks[1] == "PEMBELIAN") {
                    $total += $items['total_berat'];
                    $total = $total * $items['qty'];
                }
            }
        }
        return $total;
    }

    function getDataPO($id_supplier = null){
    	if($id_supplier != null){
    		$sql  = "SELECT t_purchase_order.id FROM t_purchase_order";
    		$sql .= " INNER JOIN m_supplier_produk ON t_purchase_order.id_supplier = m_supplier_produk.id";
    		$sql .= " WHERE m_supplier_produk.id = ".$id_supplier;
    		$sql .= " AND t_purchase_order.deleted = 1";
    		$exeQuery = $this->Transaksipembelianmodel->rawQuery($sql);
    		if($exeQuery->num_rows() > 0){			
	    		echo json_encode(array("status"=>1, "list"=>$exeQuery->result_array()));
    		}else{
    			echo json_encode(array("status"=>0));
    		}
    	}else{
    		echo json_encode(array("status"=>0));
    	}
    }
    function listPO(){
        $this->load->view('Transaksi_pembelian/listpo');
    }
    function dataPO(){
        $requestData= $_REQUEST;
        $columns = array( 
            0   =>  'id_supplier', 
            1   =>  'catatan',
            2   =>  'total_berat',
            3   =>  'total_qty',
            4   =>  'total_harga_beli',
            5   =>  'date_add',
            6   =>  'aksi'
        );
        $sql = " SELECT t_purchase_order.* , m_supplier_produk.nama as namasup ";
        $sql.= " FROM t_purchase_order ";
        $sql.= " INNER JOIN m_supplier_produk ON t_purchase_order.id_supplier = m_supplier_produk.id ";
        $query=$this->Transaksipembelianmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        $sql.=" WHERE t_purchase_order.deleted=1 ";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( id_supplier LIKE '%".$requestData['search']['value']."%' ";    
            $sql.=" OR catatan LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR total_berat LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR total_qty LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR total_harga_beli LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR date_add LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Transaksipembelianmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $query=$this->Transaksipembelianmodel->rawQuery($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData     =   array(); 

            $nestedData[]   =   $row["namasup"];
            $nestedData[]   =   $row["catatan"];
            $nestedData[]   =   $row["total_berat"];
            $nestedData[]   =   $row["total_qty"];
            $nestedData[]   =   $row["total_harga_beli"];
            $nestedData[]   =   $row["date_add"];
            $nestedData[]   =   "<button onclick=choosePO('".$row['id']."') class='btn btn-success'>PILIH</button>";
            
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
    function getInfoPO($idPO){
        $dataSelect['deleted']  =   1;
        $dataSelect['id']       =   $idPO;
        $selectPOMaster = $this->Transaksipembelianmodel->select($dataSelect, 't_purchase_order');
        echo json_encode($selectPOMaster->result_array());
    }
    function removePembelian(){
        foreach ($this->cart->contents() as $items) {
            $idProduks = explode("_", $items['id']);
            if(count($idProduks) > 1){
                if($idProduks[1] == "PEMBELIAN"){
                    $this->cart->remove($items['rowid']);
                }
            }
        }        
    }
    function addCartFromExistingPO($idPO){
        $this->removePembelian();
        $dataSelect['deleted']  =   1;
        $dataSelect['id']       =   $idPO;
        $selectPOMaster = $this->Transaksipembelianmodel->select($dataSelect, 't_purchase_order');
        if($selectPOMaster->num_rows() > 0){
            $selectDataDetail = $this->Transaksipembelianmodel->rawQuery("SELECT * FROM t_purchase_order_detail
                INNER JOIN m_produk ON t_purchase_order_detail.id_produk = m_produk.id
                WHERE t_purchase_order_detail.id_purchase_order =".$selectPOMaster->row()->id);
            if($selectDataDetail->num_rows() > 0){
                foreach ($selectDataDetail->result_array() as $row) {
                    $datax = array(
                                'id'      => $row['id']."_PEMBELIAN",
                                'qty'     => $row['jumlah'],
                                'price'   => $row['harga_beli'],
                                'name'    => $row['nama'],
                                'total_berat'=>$row['total_berat'],
                                'options' => array(
                                                'ukuran'=>$row['id_ukuran'],
                                                'warna'=>$row['id_warna'],
                                                'text_ukuran'=>$row['nama_ukuran'],
                                                'text_warna'=>$row['nama_warna']
                                                )
                    );
                    $this->cart->insert($datax);
                }
            }
        }
        echo $this->getOrder();
    }
    function rand_color() {
        echo sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    function getInvoiceData($id) {//Handling modal Invoice
        if($id == 'last') {
            $condition = array('deleted' => 1);
            $data = $this->Transaksipembelianmodel->select($condition, 't_beli', 'id', 'DESC')->row();
            $id = !empty($data) ? $data->id : 0;
        }
        if(!empty($id)) {
            $sql = "SELECT A.*, B.nama AS nama_customer FROM t_beli A "
                    ." LEFT JOIN m_supplier_produk B ON A.id_supplier = B.id"
                    ." WHERE A.id = ".$id;
            $dataProduk = $this->Transaksipembelianmodel->rawQuery($sql)->row();
         
            $sql = "SELECT A.*, B.nama AS nama_produk, C.nama AS nama_warna, D.nama AS nama_ukuran FROM t_beli_detail A "
                    ." LEFT JOIN m_produk B ON A.id_produk = B.id"
                    ." LEFT JOIN m_produk_warna C ON A.id_warna = C.id"
                    ." LEFT JOIN m_produk_ukuran D ON A.id_ukuran = D.id"
                    ." WHERE A.id_beli = ".$id;
            $dataDetailProduk = $this->Transaksipembelianmodel->rawQuery($sql)->result();

            $html = '<div class="row">
              <div class="col-md-12">
                <h5 class="text-center">Iqbal POS</h5>
                <h4 class="text-center">Invoice #'.$dataProduk->id.'</h4> <hr>
                <p>Tanggal: '.date('d-m-Y H:i:s', strtotime($dataProduk->date_add)).' <br>Supplier: '.$dataProduk->nama_customer.' </p> <br>
                <table class="table">
                  <thead> <tr> <th>#</th> <th>Produk</th> <th>Warna</th> <th>Ukuran</th> <th>Qty</th> <th>Subtotal (IDR)</th> </tr> </thead>
                  <tbody>';
                $i = 1; 
                foreach ($dataDetailProduk as $detail) {
                  $warna = !empty($detail->nama_warna) ? $detail->nama_warna : 'Tidak ada';
                  $ukuran = !empty($detail->nama_ukuran) ? $detail->nama_ukuran : 'Tidak ada';
                  $html .= '<tr> <td>'.$i++.'</td> <td>'.$detail->nama_produk.'</td> <td>'.$warna.'</td> <td>'.$ukuran.'</td> <td class="text-center">'.$detail->jumlah.'</td> <td class="text-right">'.number_format($detail->total_harga, 0, ',', '.').'</td> </tr>'; 
                }
                $html .= '</tbody> </table> <br>';

            $html2 = '<table class="table table-condensed"> <tbody>'
                    .'<tr style="font-weight: bold;">
                      <td style="width: 70%;">Total Harga</td>
                      <td style="width: 30%;">Rp <span class="pull-right">'.number_format($dataProduk->total_harga_beli, 0, ',', '.').'</span></td> </tr>'
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
}