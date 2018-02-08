<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends MX_Controller {
    private $modul = "Stok_service/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksiservicemodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Transaksiservicemodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$this->load->view('Stok_service/view');
    }
    function detail($id = 0){
    	$data['id'] = $id;
    	$this->load->view('Stok_service/detail', $data);
    }
    function data(){
		$requestData= $_REQUEST;
		$columns = array( 
			0 	=>	'id', 
			1 	=>	'id_supplier', 
			2 	=> 	'catatan',
			3	=> 	'jumlah_barang_service',
			4	=> 	'total_harga',
			5	=> 	'jumlah_barang_kembali',
			6	=> 	'jumlah_uang_kembali',
			7	=> 	'status',
			8	=> 	'date_add',
			9	=> 	'aksi'
		);
		$sql = "SELECT t_service.*, m_supplier_produk.nama as namasup ";
		$sql.=" FROM t_service";
		$sql.=" INNER JOIN m_supplier_produk ON t_service.id_supplier = m_supplier_produk.id";
		$query=$this->Transaksiservicemodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;
		// $sql = "SELECT * ";
		$sql.=" WHERE t_service.deleted=1 ";
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_supplier_produk.nama LIKE '%".$requestData['search']['value']."%' ";    
			$sql.=" OR t_service.catatan LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_service.jumlah_barang_service LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_service.total_harga LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_service.jumlah_barang_kembali LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_service.jumlah_uang_kembali LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_service.status LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_service.date_add LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksiservicemodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksiservicemodel->rawQuery($sql);
		$data = array(); $i = 1;
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 

			$nestedData[] 	= 	"<span class='center-block text-center'>".$row['id']."</span>";
			$nestedData[] 	= 	$row["namasup"];
			$nestedData[] 	= 	$row["catatan"];
			$nestedData[] 	= 	"<span class='center-block text-center'>".$row["jumlah_barang_service"]."</span>";
			$nestedData[] 	= 	"<span class='pull-right'>".number_format($row["total_harga"])."</span>";
			$nestedData[] 	= 	"<span class='center-block text-center'>".$row["jumlah_barang_kembali"]."</span>";
			$nestedData[] 	= 	"<span class='pull-right'>".number_format($row["jumlah_uang_kembali"])."</span>";
            $ketStatus = "";
            switch ($row["status"]) {
                case '1':
                    $ketStatus = "Baru";
                    break;
                case '2':
                    $ketStatus = "Proses Pengembalian";
                    break;
                case '3':
                    $ketStatus = "Selesai";
                    break;
                
                default:
                    $ketStatus = "Baru";
                    break;
            }
			$nestedData[] 	= 	$ketStatus;
			$nestedData[] 	= 	date('d-m-Y H:i', strtotime($row["date_add"]));
			$nestedData[] 	= 	"<button onclick=detail('".$row['id']."') class='btn btn-default btn-sm' title='Konfirmasi Barang Service'><i class='fa fa-edit'></i></button>";
			
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
    function data_detail($id_service){
		$requestData= $_REQUEST;
		$columns = array( 
			0 	=>	'#', 
			1 	=> 	'produk',
			2 	=> 	'nama_warna',
			3 	=> 	'nama_ukuran',
			4	=> 	'sku',
			5	=> 	'barang_diservis',
			6	=> 	'barang_kembali',
			7	=> 	'uang_kembali',
			8	=> 	'status'
		);
		$sql = "SELECT 
					t_service.id as sid,
					t_service_detail.id as sdid, 
					t_service_detail.nama_warna as nama_warna, 
					t_service_detail.nama_ukuran as nama_ukuran, 
					t_service_detail.jumlah as sdjm,
					t_service_detail.jumlah_barang_kembali as sdjbk, 
					t_service_detail.uang_kembali as sdjuk, 
					t_service_detail.status as sdst, 
					m_produk.nama as nama,
					m_produk.sku as sku";
		$sql.=" FROM t_service";
		$sql.=" LEFT JOIN t_service_detail ON t_service.id = t_service_detail.id_service";
		$sql.=" LEFT JOIN m_produk on t_service_detail.id_produk = m_produk.id";
		$sql.=" WHERE t_service.deleted=1 ";
		$sql.=" AND t_service.id=".$id_service;
		$query=$this->Transaksiservicemodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;		
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksiservicemodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$data = array();
		$i=1;
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 

			$nestedData[] 	= 	"<span class='center-block text-center'>".$i."</span>";
			$nestedData[] 	= 	$row["nama"];
			$nestedData[] 	= 	$row["nama_warna"];
			$nestedData[] 	= 	$row["nama_ukuran"];
			$nestedData[] 	= 	$row["sku"];
			$nestedData[] 	= 	"<span class='center-block text-center'>".$row["sdjm"]."</span>";
			$nestedData[] 	= 	$row['sdst']==1?"<input type='text' id='jbk-".$row['sdid']."' name='jbk-".$row['sdid']."' value='".$row["sdjbk"]."' class='form-control nopadding productNum input-sm pull-right hundreds' title='Jumlah Barang Kembali'/>": "<span class='center-block text-center'>".$row["sdjbk"]."</span>";
			$nestedData[] 	= 	$row['sdst']==1?"<input class='form-control nopadding productNum money input-sm' type='text' id='juk-".$row['sdid']."' name='juk-".$row['sdid']."' value='".$row["sdjuk"]."' title='Nominal Uang Kembali'/>": "<span class='money pull-right'>".$row["sdjuk"]."</span>";
			$enableButton	=	"";
			switch ($row['sdst']) {
				case 2:
					$status = "Dikembalikan Barang";
					$enableButton = "disabled";
					break;
				case 3:
					$status = "Dikembalikan Uang";
					$enableButton = "disabled";
					break;
				case 4:
					$status = "Dikembalikan Barang & Uang";
					$enableButton = "disabled";
					break;
				default:
		            $status  = "<select class='form-control' name='sts-".$row['sdid']."' id='sts-".$row['sdid']."' style='width: 100%'>";
					$status .= "<option value='1' selected>Dalam Proses</option>";
					$status .= "<option value='2'>Barang</option>";
					$status .= "<option value='3'>Uang</option>";
					$status .= "<option value='4'>Barang dan Uang</option>";
					$enableButton = "";
		            $status .= "</select>";
					break;
			}
			$nestedData[] 	= 	$status;		
			$aksi = $enableButton=="disabled"?"CONFIRMED":"<button onclick=confirm('".$row['sdid']."') class='btn btn-success'>CONFIRM</button>";
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
    function confirm(){
    	$params = $this->input->post();
    	$data = array();
    	$status = 0;
    	if($params['id_hidden'] != null){
    		// get data t_service_detail
    		$dataSelect['id_service'] = $params['id_hidden'];
    		$selectDataDetail = $this->Transaksiservicemodel->select($dataSelect, 't_service_detail');
    		if($selectDataDetail->num_rows() > 0){
    			foreach ($selectDataDetail->result_array() as $rowDetail) {
    				if(isset($params['jbk-'.$rowDetail['id']])){

			    		$statusStok = $rowDetail['kurangi_stok'];
			    		if ($statusStok == 1) {
			    			$idProduk = $rowDetail['id_produk'];
			    			$dataSelectMaster['id'] = $idProduk;
			    			$selectDataMaster = $this->Transaksiservicemodel->select($dataSelectMaster, 'm_produk');
			    			
			    			$inputStokTerakhir = $selectDataDetail->row()->jumlah_barang_kembali;
			    			$stokKembali = $params['jbk-'.$rowDetail['id']];
			    			$stokGudang = $selectDataMaster->row()->stok - $inputStokTerakhir;
			    			$stokSekarang = $stokGudang + $stokKembali;

			    			// update stok master
			    			$dataConditionMaster['id'] = $idProduk;
			    			// $dataUpdateMaster['stok'] = $stokSekarang;
			    			//NANTI TAMBAHKAN DISINI
			    			$detail_stok = $this->get_detail_stok($idProduk);
                            $dataUpdateMaster['detail_stok'] = $this->build_detail_stok($detail_stok, $rowDetail['id_warna'], $rowDetail['id_ukuran'], $rowDetail['nama_warna'], $rowDetail['nama_ukuran'], $stokKembali, 'tambahkan');
                            $dataUpdateMaster['stok'] = $this->total_detail_stok($dataUpdateMaster['detail_stok']);
                            $total_stok = $dataUpdateMaster['stok'];
			    			$updateDataMaster = $this->Transaksiservicemodel->update($dataConditionMaster, $dataUpdateMaster, 'm_produk');

			    			if($updateDataMaster){
			    				// insert ke h stok produk
			    				$dataInsertHistori['id_produk'] 		= $idProduk;
			    				$dataInsertHistori['id_order_detail'] 	= 0;
			    				$dataInsertHistori['id_service'] 		= $params['id_hidden'];
			    				$dataInsertHistori['jumlah']			= $stokKembali;
			    				// $dataInsertHistori['stok_akhir']		= $stokSekarang;
			    				$dataInsertHistori['stok_akhir']		= $total_stok;
			    				$dataInsertHistori['keterangan']		= "Barang Kembali";
			    				$dataInsertHistori['status']			= 5;
			    				$dataInsertHistori['add_by']			= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
			    				$dataInsertHistori['edited_by']			= 0;
			    				$dataInsertHistori['deleted']			= 1;
			    				$insertHistori = $this->Transaksiservicemodel->insert($dataInsertHistori, 'h_stok_produk');
			    				if($insertHistori){
			    					// update service detail
			    					// $dataConditionService['id'] = $dataSelect['id_service'];
			    					$dataConditionService['id'] = $rowDetail['id'];
			    					$dataUpdateService['uang_kembali']  		= $params['juk-'.$rowDetail['id']];
			    					$dataUpdateService['status']				= $params['sts-'.$rowDetail['id']];
			    					$dataUpdateService['jumlah_barang_kembali']	= $params['jbk-'.$rowDetail['id']];
			    					$updateService = $this->Transaksiservicemodel->update($dataConditionService, $dataUpdateService, 't_service_detail');
			    					if($updateService){
			    						// get last jumlah_uang_kembali
			    						// get last jumlah_barang_kembali
			    						$dataSelectTservice['id'] = $rowDetail['id'];
			    						$selectlastJbk = $this->Transaksiservicemodel->rawQuery("SELECT SUM(jumlah_barang_kembali) as JBK FROM t_service_detail WHERE id_service = ".$params['id_hidden']);
			    						$lastBarang = $selectlastJbk->row()->JBK;
			    						$selectlastJuk = $this->Transaksiservicemodel->rawQuery("SELECT SUM(uang_kembali) as JUK FROM t_service_detail WHERE id_service = ".$params['id_hidden']);
			    						$lastUang = $selectlastJuk->row()->JUK;

			                            //get data all row
			                            $dataSelectAllRow['id_service'] = $params['id_hidden'];
			                            $selectDataAllRow = $this->Transaksiservicemodel->select($dataSelectAllRow, 't_service_detail');
			                            //get data status 1
			                            $dataSelectstatsOne['id_service'] = $params['id_hidden'];
			                            $dataSelectstatsOne['status'] = 1;
			                            $selectDataOne = $this->Transaksiservicemodel->select($dataSelectstatsOne, 't_service_detail');
			                            $statusTService = 1;
			                            if($selectDataOne->num_rows() == 0){
			                                $statusTService = 3;
			                            }else if($selectDataAllRow->num_rows() == $selectDataOne->num_rows()){
			                                $statusTService = 1;
			                            }else if ($selectDataOne->num_rows() < $selectDataAllRow->num_rows()) {
			                                $statusTService = 2;
			                            }
			    						$dataConditionTservice['id'] = $params['id_hidden'];
			    						$dataInsertTservice['jumlah_barang_kembali'] = $lastBarang;
			    						$dataInsertTservice['jumlah_uang_kembali'] = $lastUang;
			    						$dataInsertTservice['status'] = $statusTService;
			    						$updateTservice = $this->Transaksiservicemodel->update($dataConditionTservice, $dataInsertTservice, 't_service');
			    						if($updateTservice){    						
				    						// echo json_encode(array("status"=>"A"));
				    						$status = 1;
				    						// exit();
			    						}else{
			    							// echo json_encode(array("status"=>"B"));
			    							$status = 0;
			    							// exit();
			    						}
			    					}else{
			    						// echo json_encode(array("status"=>"C"));
			    						$status = 0;
			    						// exit();
			    					}
			    				}
			    			}
			    		}else{
							$dataConditionService['id'] = $rowDetail['id'];
							$dataUpdateService['uang_kembali']  		= $params['juk-'.$rowDetail['id']];
							$dataUpdateService['status']				= $params['sts-'.$rowDetail['id']];
							$dataUpdateService['jumlah_barang_kembali']	= $params['jbk-'.$rowDetail['id']];
							$updateService = $this->Transaksiservicemodel->update($dataConditionService, $dataUpdateService, 't_service_detail');
							if($updateService){
								// get last jumlah_uang_kembali
								// get last jumlah_barang_kembali
								$dataSelectTservice['id'] = $selectDataDetail->row()->id_service;
								$selectlastJbk = $this->Transaksiservicemodel->rawQuery("SELECT SUM(jumlah_barang_kembali) as JBK FROM t_service_detail WHERE id_service = ".$selectDataDetail->row()->id_service);
								$lastBarang = $selectlastJbk->row()->JBK;
								$selectlastJuk = $this->Transaksiservicemodel->rawQuery("SELECT SUM(uang_kembali) as JUK FROM t_service_detail WHERE id_service = ".$selectDataDetail->row()->id_service);
								$lastUang = $selectlastJuk->row()->JUK;

								$dataConditionTservice['id'] = $selectDataDetail->row()->id_service;
								$dataInsertTservice['jumlah_barang_kembali'] = $lastBarang;
								$dataInsertTservice['jumlah_uang_kembali'] = $lastUang;
								$dataInsertTservice['status'] = 3;
								$updateTservice = $this->Transaksiservicemodel->update($dataConditionTservice, $dataInsertTservice, 't_service');
								if($updateTservice){    						
									// echo json_encode(array("status"=>"D"));
									$status = 1;
									// exit();
								}else{
									// echo json_encode(array("status"=>"E"));
									$status = 0;
									// exit();
								}
							}else{
								// echo json_encode(array("status"=>"F"));
								$status = 0;
								// exit();
							}
			    		}
    				}else{
						// echo json_encode(array("status"=>"G"));
						$status = 0;
						// exit();
    				}
    			}
    		}
    	}
    	echo json_encode(array("status"=>$status));
    }
    function confirms(){
    	$params = $this->input->post();
    	if($params != null){
    		// update t_service_detail
    		// get status stok
    		// get status confirm
    		$dataSelects = explode("_", $params['id']);
    		$dataSelect['id'] = $dataSelects[0];
    		$selectData = $this->Transaksiservicemodel->select($dataSelect, 't_service_detail');

    		$statusStok = $selectData->row()->kurangi_stok;
    		if ($statusStok == 1) {
    			$idProduk = $selectData->row()->id_produk;
    			$dataSelectMaster['id'] = $idProduk;
    			$selectDataMaster = $this->Transaksiservicemodel->select($dataSelectMaster, 'm_produk');
    			
    			$stokKembali = $params['jbk'];
    			$stokGudang = $selectDataMaster->row()->stok;

    			$stokSekarang = $stokGudang + $stokKembali;

    			// update stok master
    			$dataConditionMaster['id'] = $idProduk;
    			$dataUpdateMaster['stok'] = $stokSekarang;
    			$updateDataMaster = $this->Transaksiservicemodel->update($dataConditionMaster, $dataUpdateMaster, 'm_produk');
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
    				$dataInsertHistori['edited_by']			= 0;
    				$dataInsertHistori['deleted']			= 1;
    				$insertHistori = $this->Transaksiservicemodel->insert($dataInsertHistori, 'h_stok_produk');
    				if($insertHistori){
    					// update service detail
    					$dataConditionService['id'] = $dataSelect['id'];
    					$dataUpdateService['uang_kembali']  		= $params['juk'];
    					$dataUpdateService['status']				= $params['sts'];
    					$dataUpdateService['jumlah_barang_kembali']	= $params['jbk'];
    					$updateService = $this->Transaksiservicemodel->update($dataConditionService, $dataUpdateService, 't_service_detail');
    					if($updateService){
    						// get last jumlah_uang_kembali
    						// get last jumlah_barang_kembali
    						$dataSelectTservice['id'] = $selectData->row()->id_service;
    						$selectlastJbk = $this->Transaksiservicemodel->rawQuery("SELECT SUM(jumlah_barang_kembali) as JBK FROM t_service_detail WHERE id_service = ".$selectData->row()->id_service);
    						$lastBarang = $selectlastJbk->row()->JBK;
    						$selectlastJuk = $this->Transaksiservicemodel->rawQuery("SELECT SUM(uang_kembali) as JUK FROM t_service_detail WHERE id_service = ".$selectData->row()->id_service);
    						$lastUang = $selectlastJuk->row()->JUK;

                            //get data all row
                            $dataSelectAllRow['id_service'] = $selectData->row()->id_service;
                            $selectDataAllRow = $this->Transaksiservicemodel->select($dataSelectAllRow, 't_service_detail');
                            //get data status 1
                            $dataSelectstatsOne['id_service'] = $selectData->row()->id_service;
                            $dataSelectstatsOne['status'] = 1;
                            $selectDataOne = $this->Transaksiservicemodel->select($dataSelectstatsOne, 't_service_detail');
                            $statusTService = 1;
                            if($selectDataOne->num_rows() == 0){
                                $statusTService = 3;
                            }else if($selectDataAllRow->num_rows() == $selectDataOne->num_rows()){
                                $statusTService = 1;
                            }else if ($selectDataOne->num_rows() < $selectDataAllRow->num_rows()) {
                                $statusTService = 2;
                            }
    						$dataConditionTservice['id'] = $selectData->row()->id_service;
    						$dataInsertTservice['jumlah_barang_kembali'] = $lastBarang;
    						$dataInsertTservice['jumlah_uang_kembali'] = $lastUang;
    						$dataInsertTservice['status'] = $statusTService;
    						$updateTservice = $this->Transaksiservicemodel->update($dataConditionTservice, $dataInsertTservice, 't_service');
    						if($updateTservice){    						
	    						echo json_encode(array("status"=>1));
    						}else{
    							echo json_encode(array("status"=>0));
    						}
    					}else{
    						echo json_encode(array("status"=>0));
    					}
    				}
    			}
    		}else{
				$dataConditionService['id'] = $dataSelect['id'];
				$dataUpdateService['uang_kembali']  		= $params['juk'];
				$dataUpdateService['status']				= $params['sts'];
				$dataUpdateService['jumlah_barang_kembali']	= $params['jbk'];
				$updateService = $this->Transaksiservicemodel->update($dataConditionService, $dataUpdateService, 't_service_detail');
				if($updateService){
					// get last jumlah_uang_kembali
					// get last jumlah_barang_kembali
					$dataSelectTservice['id'] = $selectData->row()->id_service;
					$selectlastJbk = $this->Transaksiservicemodel->rawQuery("SELECT SUM(jumlah_barang_kembali) as JBK FROM t_service_detail WHERE id_service = ".$selectData->row()->id_service);
					$lastBarang = $selectlastJbk->row()->JBK;
					$selectlastJuk = $this->Transaksiservicemodel->rawQuery("SELECT SUM(uang_kembali) as JUK FROM t_service_detail WHERE id_service = ".$selectData->row()->id_service);
					$lastUang = $selectlastJuk->row()->JUK;

					$dataConditionTservice['id'] = $selectData->row()->id_service;
					$dataInsertTservice['jumlah_barang_kembali'] = $lastBarang;
					$dataInsertTservice['jumlah_uang_kembali'] = $lastUang;
					$dataInsertTservice['status'] = 2;
					$updateTservice = $this->Transaksiservicemodel->update($dataConditionTservice, $dataInsertTservice, 't_service');
					if($updateTservice){    						
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
    function getOrder(){
    	$data = array();
    	foreach ($this->cart->contents() as $items){
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){			
	    		if($idProduks[1] == "STOKSERVICE"){
		    		$nestedData = array();
		    		$nestedData['id'] = $idProduks;
		    		$nestedData['qty'] = $items['qty'];
		    		$nestedData['price'] = $items['price'];
		    		$nestedData['produk'] = $items['name'];
		    		$nestedData['rowid'] = $items['rowid'];
		    		$nestedData['subtotal'] = $items['subtotal'];
		    		$nestedData['options'] = $items['options']['stok'];
		    		$nestedData['ukuran'] = $items['ukuran']!=null?$items['ukuran']:0;
		    		$nestedData['text_ukuran'] = $items['text_ukuran'];
                    $nestedData['warna'] = $items['warna']!=null?$items['warna']:0;
                    $nestedData['text_warna'] = $items['text_warna'];
		    		array_push($data, $nestedData);
	    		}
    		}
    	}
    	return json_encode($data);
    }
    function getProduk($supplier = null){
    	$list = null;
    	$dataSelect['deleted'] = 1;
    	// $query = "SELECT * FROM m_produk ";
    	// $query .= "INNER JOIN m_det"
    	if($supplier != null){
    		// $query .= " AND id_supplier = '".$supplier."'"
    		$dataSelect['id_supplier'] = $supplier;
    	}
    	$list = $this->Transaksiservicemodel->select($dataSelect, 'm_produk');
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
    	$list = $this->Transaksiservicemodel->like($dataCondition, $dataLike, 'm_produk');
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
        $list = $this->Transaksiservicemodel->rawQuery($sql);
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
    	$list = $this->Transaksiservicemodel->like($dataCondition, $dataLike, 'm_produk');
    	return json_encode($list->result_array());
    }   
    function getSupplier(){
    	$dataSelect['deleted'] = 1;
    	return json_encode($this->Transaksiservicemodel->select($dataSelect, 'm_supplier_produk')->result_array());
    }
    function filterProduk($supplier){
    	echo $this->getProduk($supplier);
    }
    function getKategori($supplier){
    	$selectData = $this->Transaksiservicemodel->rawQuery("SELECT m_produk_kategori.id, m_produk_kategori.nama FROM m_produk
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
        $selectData = $this->Transaksiservicemodel->rawQuery("SELECT m_produk_warna.id, m_produk_warna.nama
            FROM m_produk_det_warna
            INNER JOIN m_produk ON m_produk_det_warna.id_produk = m_produk.id
            INNER JOIN m_produk_warna ON m_produk_det_warna.id_warna = m_produk_warna.id
            WHERE m_produk_det_warna.id_produk = ".$rid[0]);
    	echo json_encode($selectData->result_array());
    }
    function getUkuran($id){
        $rid = explode("_", $id);
        $selectData = $this->Transaksiservicemodel->rawQuery("SELECT m_produk_ukuran.id, m_produk_ukuran.nama
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
        $list = $this->Transaksiservicemodel->select($dataSelect, 'm_produk_ukuran');
        return $list->row();
    }
    function getWarnaById($id){
        $list = null;
        $dataSelect['deleted'] = 1;
        $dataSelect['id'] = $id;
        $list = $this->Transaksiservicemodel->select($dataSelect, 'm_produk_warna');
        return $list->row();
    }
    function transaksi(){
    	$getTotal = json_decode($this->_getTotal());
    	$dataSelect['deleted'] = 1;
    	$data['list_produk'] = $this->getProduk();
        $data['list_order'] = $this->getOrder();
        $data['list_supplier'] = $this->getSupplier();
        // $data['total'] = $this->cart->total();
        // $data['total_items'] = $this->cart->total_items();
        $data['total'] = $getTotal->total;
        $data['total_items'] = $getTotal->total_items;
        $data['tax'] = 0;
        $data['discount'] = 0;
    	$this->load->view('Stok_service/transaksi', $data);
    }
    function getTotal(){
    	$total = 0;
    	$total_item = 0;
    	foreach ($this->cart->contents() as $items) {    		
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "STOKSERVICE"){
    				$total += $items['price']*$items['qty'];
    				$total_item += $items['qty'];
    			}
    		}
    	}
    	echo json_encode(array("tax"=>0, "discount"=> 0, "total"=> $total, "total_items"=>$total_item));
    }
    function _getTotal(){
    	$total = 0;
    	$total_item = 0;
    	foreach ($this->cart->contents() as $items) {    		
    		$idProduks = explode("_", $items['id']);
    		if(count($idProduks) > 1){
    			if($idProduks[1] == "STOKSERVICE"){
    				$total += $items['price']*$items['qty'];
    				$total_item += $items['qty'];
    			}
    		}
    	}
    	return json_encode(array("tax"=>0, "discount"=> 0, "total"=> $total, "total_items"=>$total_item));
    }
    function updateCart($id, $qty, $jenis_stok, $state = 'tambah'){
    	$getid = $this->in_cart($id, 'id', 'rowid');
    	$dataSelect['deleted'] = 1;
    	$dataSelect['id'] = $getid;
    	$selectData = $this->Transaksiservicemodel->select($dataSelect, 'm_produk');
    	$lastQty = $this->in_cart($id, 'qty', 'rowid');

    	$item_id = explode('_', $getid);
    	$id_produk = $item_id[0]; $idUkuran = $item_id[2]; $idWarna = $item_id[3];

    	$detail_stok = $this->get_detail_stok($id_produk);
        $item_stok = $this->find_detail_stok($detail_stok, $idWarna, $idUkuran);

        /*echo "Qty: ".$qty ."<br>";
        echo $lastQty ." <= ";
        echo $item_stok;
        die();*/
    	
    	if($state == 'tambah') {		
			$data = array(
			        'rowid'  => $id,
			        'qty'    => $qty
			);
			if($jenis_stok == 1) { //jika kurangi stok
		    	// if($lastQty <= $selectData->row()->stok){
		    	if($lastQty <= $item_stok){
					$this->cart->update($data);
					// echo $this->getOrder();   	
	                echo json_encode(array("status" => 2, "list" => $this->getOrder(), "rowid"=>$id)); 

		    	}
		    	else {
	                echo json_encode(array("status" => 0, "list" => $this->getOrder(), "rowid"=>$id)); 
		    	}
			}
			else if($jenis_stok == 2) { //jika tidak kurangi stok
				$this->cart->update($data);
				// echo $this->getOrder();   	
	            echo json_encode(array("status" => 2, "list" => $this->getOrder(), "rowid"=>$id)); 

			}
    	}
    	else {
			$data = array(
			        'rowid'  => $id,
			        'qty'    => $qty
			);
			$this->cart->update($data);
			// echo $this->getOrder();
	        echo json_encode(array("status" => 1, "list" => $this->getOrder(), "rowid"=>$id)); 

    	}
    }
    function updateOption($id, $options){
		$data = array(
		        'rowid'  => $id,
		        'options'=> array('stok'=>$options)
		);
		$this->cart->update($data);
		echo $this->getOrder();  
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
    			if($idProduks[1] == "STOKSERVICE"){
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
            $data_produk = $this->Transaksiservicemodel->select($condition, 'm_produk')->row();

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
    private function build_detail_stok($detail_stok, $id_warna=0, $id_ukuran=0, $nama_warna, $nama_ukuran, $qty, $operasi) {
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

                	if($operasi == 'tambahkan') {
                    	$new_stok = ($arr_data[$key]['stok'] + $qty);
                	} 
                	else if($operasi == 'kurangi') {
                    	$new_stok = ($arr_data[$key]['stok'] - $qty);
                	} 
                	else {
                    	$new_stok = $qty;
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
    //--------------------------------------------
	function tambahCart($id){
		$params	= $this->input->post();
		$jenis_stok = $params['jenis_stok'];
		$getqty = $params['qty'];
		$event = $params['event'];
        $rowid = isset($params['rowid']) ? $params['rowid'] : 0;
        $idSupplier = $params['idSupplier'];
        $idUkuran = !empty($params['idUkuran']) ? $params['idUkuran'] : 0;
		$idWarna = !empty($params['idWarna']) ? $params['idWarna'] : 0;
        $textUkuran = $this->getUkuranById($idUkuran);
        $textWarna = $this->getWarnaById($idWarna);
		// $inCart = $this->in_cart($id."_STOKSERVICE");

        if(!empty($rowid)) {
        	$cart_id = $this->in_cart($rowid, 'id', 'rowid');
        	$split = explode('_', $cart_id);
        	$idUkuran = $split['2'];
        	$idWarna = $split['3'];
        }else {
        	$cart_id = $id."_STOKSERVICE"."_".$idUkuran."_".$idWarna; //idProduk_STOKSERVICE_idUkuran_idWarna
        }
        $inCart = $this->in_cart($cart_id);
		
		$dataSelect['deleted'] = 1;
		$dataSelect['id'] = $id;
		$selectData = $this->Transaksiservicemodel->select($dataSelect, 'm_produk');

		//fetching stok berdasarkan warna&ukuran barang
		$detail_stok = $this->get_detail_stok($id);
        $item_stok = $this->find_detail_stok($detail_stok, $idWarna, $idUkuran);

		if($inCart != 'false'){
			if($jenis_stok == 1) { // jika kurangi stok
				//check apakah permintaan melebihi stok
				// $current_qty = $this->in_cart($id."_STOKSERVICE", 'qty');
				if($event == 'input') { 
					$current_qty = $getqty; 
				}
				else if ($event == 'click') { 
					$current_qty = $this->in_cart($cart_id, 'qty') + 1; 
				}

				// if($current_qty < $selectData->row()->stok){	
				if($current_qty <= $item_stok) {	
					//membedakan qty tsb adalah inputan dari user ataukah dari klik thumbnail produk
					$qty = $current_qty;		
					$this->updateCart($inCart, $qty, $jenis_stok);
				}
				else{
					//stok not available
					/*echo "getqty: ".$getqty;
					echo "<br>current_qty: ".$current_qty;
					echo " < item_stok: ".$item_stok;
					die();*/

					//fetching rowid
					$cartContent = json_decode($this->getOrder());
					$rowid = "";
					foreach ($cartContent as $item) {
						// if(($item->id[0] == $id) && ($item->id[1] == 'STOKSERVICE')) {
						$item_id = join('_', $item->id);
						if($item_id == $cart_id) {
							$rowid = $item->rowid;
						}
					}
	                echo json_encode(array("status" => 0, "list" => $this->getOrder(), "rowid"=>$rowid, "available" => $item_stok)); 
	            }
			} 
			else if($jenis_stok == 2) {  //jika tidak kurangi stok
				//langsung saja update cart
				if($event == 'input') { //inputan dari user
					$qty = $getqty;
				}
				else if($event == 'click') { //dari klik produk
					// $qty = $this->in_cart($id."_STOKSERVICE", 'qty') + 1;
					$qty = $this->in_cart($cart_id, 'qty') + 1;
				}
				$this->updateCart($inCart, $qty, $jenis_stok);
			} 
		}
		else if($inCart == 'false'){		
			//siapkan data untuk insert dulu
			$datas = array(
                // 'id'      => $selectData->row()->id."_STOKSERVICE",
                'id'      => $cart_id,
                'qty'     => 1,
                'price'   => $selectData->row()->harga_beli,
                'name'    => $selectData->row()->nama,
                'ukuran' => $idUkuran,
                'text_ukuran' => !empty($textUkuran) ? $textUkuran->nama : 'Tidak ada',
                'warna' => $idWarna,
                'text_warna' => !empty($textWarna) ? $textWarna->nama : 'Tidak ada',
		        'options' => array(
		        		'stok' => $jenis_stok
		        		)
			);

			if($jenis_stok == 1) { // jika kurangi stok
				//check apakah stok available
				// if($selectData->row()->stok >= 1) {
				if($item_stok >= 1) {
					$this->cart->insert($datas);
					// echo $this->getOrder();
					$rowid = "";
	                echo json_encode(array("status" => 2, "list" => $this->getOrder(), "rowid"=>$rowid)); 
				}
				else{
	                // echo json_encode(array("status"=>0));
	                $cartContent = json_decode($this->getOrder());
					$rowid = "";
					foreach ($cartContent as $item) {
						// if(($item->id[0] == $id) && ($item->id[1] == 'STOKSERVICE')) {
						$item_id = join('_', $item->id);
						if($item_id == $cart_id) {
							$rowid = $item->rowid;
						}
					}
	                $obj_list = json_decode($this->getOrder());
					$arr_list = (array)$obj_list;
					
					if(isset($arr_list[0]->stok)) {
						$arr_list[0]->stok = $item_stok;
					}
					
					$list = json_encode($arr_list);
	                echo json_encode(array("status" => 0, "list" => $list, "rowid"=>$rowid, "available" => $item_stok)); 
	            }
			}
			else if($jenis_stok == 2) {  //jika tidak kurangi stok
				//langsung saja insert
				$this->cart->insert($datas);
				// echo $this->getOrder();
				echo json_encode(array("status" => 2, "list" => $this->getOrder(), "rowid"=>$rowid)); 
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
    function doServices(){
    	$params = $this->input->post();
    	if($params != null){
    		$getTotal  = json_decode($this->_getTotal(), true);
    		$dataInsertPrimer['id_supplier'] 			= $params['supplier'];
    		$dataInsertPrimer['catatan']				= $params['catatan'];
    		$dataInsertPrimer['jumlah_barang_service']	= $getTotal['total_items'];
    		$dataInsertPrimer['total_harga']			= $getTotal['total'];
    		$dataInsertPrimer['jumlah_barang_kembali']	= 0;
    		$dataInsertPrimer['jumlah_uang_kembali']	= 0;
    		$dataInsertPrimer['status']					= 1;
    		$dataInsertPrimer['add_by']					= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsertPrimer['edited_by']				= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    		$dataInsertPrimer['deleted']				= 1;
    		$insertPrimer = $this->Transaksiservicemodel->insert_id($dataInsertPrimer, 't_service');

    		if($insertPrimer){
    			$id = $insertPrimer;
    			// $getId = $this->Transaksiservicemodel->select($dataInsertPrimer, 't_service');
    			// $id = $getId->row()->id;
	    		$dataInsertSekunder['id_service'] = $id;
	    		$dataInsertHistori['id_service'] = $id;
		    	foreach ($this->cart->contents() as $items) {
		    		$idProduks = explode("_", $items['id']);
		    		if (count($idProduks) > 1) {
			    		if ($idProduks[1] == "STOKSERVICE") {
			    			// echo "Updating service detail <br>";
				    		$dataInsertSekunder['id_produk'] = $idProduks[0];
				    		$dataInsertSekunder['harga_beli'] = $items['price'];
				    		$dataInsertSekunder['jumlah'] = $items['qty'];
				    		$dataInsertSekunder['id_warna'] = $items['warna'];
				    		$dataInsertSekunder['id_ukuran'] = $items['ukuran'];
				    		$dataInsertSekunder['nama_warna'] = $items['text_warna'];
				    		$dataInsertSekunder['nama_ukuran'] = $items['text_ukuran'];
				    		$dataInsertSekunder['total_harga'] = $items['subtotal'];
				    		$dataInsertSekunder['uang_kembali'] = 0;
				    		$dataInsertSekunder['kurangi_stok'] = $items['options']['stok'];
				    		$dataInsertSekunder['status'] = 1;
				    		$dataInsertSekunder['jumlah_barang_kembali'] = 0;
				    		$dataInsertSekunder['stok_kembali'] = 0;
				    		$insertDataSekunder = $this->Transaksiservicemodel->insert($dataInsertSekunder, 't_service_detail');

				    		if($insertDataSekunder){
				    			// echo "Updating data produk <br>";
				    			if($items['options']['stok'] == 1) {
				    				$detail_stok = $this->get_detail_stok($idProduks[0]);
				    				$dataUpdate['detail_stok'] = $this->build_detail_stok($detail_stok, $items['warna'], $items['ukuran'], $items['text_warna'], $items['text_ukuran'], $items['qty'], 'kurangi');
				    				$dataUpdate['stok'] = $this->total_detail_stok($dataUpdate['detail_stok']);
				    				$dataUpdate['deskripsi'] = "ganti iki lho";
				    				$produk_stok = $dataUpdate['stok'];
				    				// $dataUpdate['stok']					=	$getDataLastStok->row()->stok - $items['qty'];
				    				$condition = array('id' => $idProduks[0]);
				    				$updateStok	= $this->Transaksiservicemodel->update($condition, $dataUpdate, 'm_produk');
					    			
					    			if($updateStok){
					    				// echo "Updating stok produk<br>";
					    				$dataSelectLastStok['id'] = $items['id'];
					    				$dataSelectLastStok['deleted'] = 1;
						    			$dataInsertHistori['id_produk'] = $idProduks[0];
						    			$dataInsertHistori['id_order_detail'] =	0;
						    			$dataInsertHistori['jumlah'] = $items['qty'];
					    				// $getDataLastStok = $this->Transaksiservicemodel->select($dataSelectLastStok, 'm_produk');
						    			// $dataInsertHistori['stok_akhir']		=	$getDataLastStok->row()->stok - $items['qty'];
						    			$dataInsertHistori['stok_akhir'] =	$produk_stok;
						    			$dataInsertHistori['keterangan'] =	$params['catatan'];
						    			$dataInsertHistori['status'] =	2;
						    			$dataInsertHistori['add_by'] =	isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
						    			$dataInsertHistori['edited_by'] =	isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
						    			$dataInsertHistori['id_warna'] = $items['warna'];
						    			$dataInsertHistori['id_ukuran'] = $items['ukuran'];
						    			$dataInsertHistori['deleted'] =	1;
						    			$insertHistori = $this->Transaksiservicemodel->insert($dataInsertHistori, 'h_stok_produk');

					    				if($insertHistori){
					    					$this->cart->remove($items['rowid']);
					    				}
					    			}
				    			}
				    		}
			    		}
		    		}
		    	}    			
    		}
    	}
    	// $this->cart->destroy();
    	$this->destroyCart();
		// echo $this->getOrder();    	
    }
    function testCart(){
    	echo json_encode($this->cart->contents());
    }
}