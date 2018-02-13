<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends MX_Controller {
    private $modul = "Transaksi_barangmasuk/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksibarangmasukmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Transaksibarangmasukmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
        $sql = "SELECT A.*, B.nama FROM m_produk_det_ukuran A LEFT JOIN m_produk_ukuran B ON A.id_ukuran = B.id ORDER BY B.nama ASC";
        $data['list_det_ukuran'] = json_encode($this->Transaksibarangmasukmodel->rawQuery($sql)->result());

        $sql = "SELECT A.*, B.nama FROM m_produk_det_warna A LEFT JOIN m_produk_warna B ON A.id_warna = B.id ORDER BY B.nama ASC";
        $data['list_det_warna'] = json_encode($this->Transaksibarangmasukmodel->rawQuery($sql)->result());

    	$this->load->view('Transaksi_barangmasuk/view', $data);
    }
    function data(){
		$requestData= $_REQUEST;
		$columns = array( 
			0 	=>	'id',
			1 	=>	'foto',
			2 	=> 	'nama_bahan',
			3 	=> 	'sku',
            4   =>  'stok',
			5 	=> 	'detail_stok',
			5	=> 	'tanggal_tambah_stok',
			6	=> 	'tanggal_kurang_stok',
			7	=> 	'aksi'
		);
		$sql = " SELECT m_produk.*";
		$sql.= " FROM m_produk ";
		$sql.= " LEFT JOIN m_supplier_produk ON m_produk.id_supplier = m_supplier_produk.id ";
		$sql.= " LEFT JOIN m_satuan ON m_produk.id_satuan = m_satuan.id ";
		$sql.= " LEFT JOIN m_gudang ON m_produk.id_gudang = m_gudang.id ";
		$sql.= " LEFT JOIN m_produk_bahan ON m_produk.id_bahan = m_produk_bahan.id ";
		$query=$this->Transaksibarangmasukmodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;
		// $sql = "SELECT * ";
		$sql.=" WHERE m_produk.deleted=1 ";
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_supplier_produk.nama LIKE '%".$requestData['search']['value']."%' ";    
			$sql.=" OR m_produk.deskripsi LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.nama LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.harga_beli LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.detail_stok LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR m_produk.date_add LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Transaksibarangmasukmodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Transaksibarangmasukmodel->rawQuery($sql);
		$data = array();
		$i = 1;
		foreach ($query->result_array() as $row) {
            $foto_url = base_url()."/upload/produk/placeholder.png";
            if(!empty($row["foto"])) {
                if(file_exists(URL_UPLOAD."/produk/".$row["foto"])) {
                    $foto_url = base_url()."/upload/produk/".$row["foto"];
                }
            }			
            //Preparing detail stok
            $html_detail = '';
            $detail_stok = json_decode($row['detail_stok']);
            if(!empty($detail_stok)) {
                
                //sorting array of objects by nama_warna
                usort($detail_stok, function($a, $b) {
                    return strcmp($a->nama_ukuran, $b->nama_ukuran);
                });
                $html_detail .= "<table class='table table-condensed table-striped small'>"
                                    ."<thead><tr>"
                                        ."<th>Ukuran</th>"
                                        ."<th>Warna</th>"
                                        ."<th>Stok</th>"
                                    ."</tr></thead><tbody>";
                foreach ($detail_stok as $detail) {
                    $html_detail .= "<tr>"
                                ."<td>".$detail->nama_ukuran."</td>"
                                ."<td>".$detail->nama_warna."</td>"
                                ."<td>".$detail->stok."</td> </tr>";
                }
                $html_detail .= "</tbody></table>";
            }


			$nestedData		=	array(); 
			$nestedData[] 	= 	"<span class='center-block text-center'>". $row['id'] ."</span>";
            $nestedData[]   .=  "<a href='javascript:void(0)' data-toggle='popover' data-html='true' data-placement='right' onclick='showThumbnail(this)'>"
                            . "<img src='".$foto_url."' class='img-responsive img-rounded' width='70' alt='No Image' style='margin:0 auto;'> </a>";
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   $row["sku"];
            $nestedData[]   =   "<span class='center-block text-center'>". $row["stok"] ."</span>";
            $nestedData[]   =   (!empty($row["detail_stok"]) ? $html_detail : "<span class='center-block text-center'> - </span>");
            $nestedData[]   =   $row["tanggal_tambah_stok"];
            $nestedData[]   =   $row["tanggal_kurang_stok"];
            $nestedData[]   =   "
                                <a class='divpopover btn btn-sm btn-default' href='javascript:void(0)' data-toggle='popover' data-placement='top' data-html='true' title='Tambah Stok' onclick=tambahStok('".$row['id']."')><i class='fa fa-plus'></i>
                                </a>
                                <a class='divpopover btn btn-sm btn-default' href='javascript:void(0)' data-toggle='popover' data-placement='top' data-html='true' title='Kurangi Stok' onclick=kurangStok('".$row['id']."')><i class='fa fa-minus'></i>
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

    private function get_detail_stok($id_produk) {
        //fetch detail_stok from current product
        $result = 0;
        if(!empty($id_produk)) {
            $condition = array('id' => $id_produk, 'deleted' => 1);
            $data_produk = $this->Transaksibarangmasukmodel->select($condition, 'm_produk')->row();

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

    function ubahStok(){
    	$params = $this->input->post();
    	if($params != null){
            $dataUpdate = array();
            $dataInsert = array();
            $state = $params['state'];
            $qty = $params['qty'];
            $id_ukuran = $params['id_ukuran'];
            $id_warna = $params['id_warna'];
            $nama_ukuran = $params['nama_ukuran'];
            $nama_warna = $params['nama_warna'];
            $new_detail_stok = array();
    		$dateNow = date('Y-m-d H:i:s');
            
            $dataCondition['id'] = $params['idProduk'];
            $dataStok = $this->Transaksibarangmasukmodel->select($dataCondition, 'm_produk')->row();
            // $lastStok = $dataStok->row()->stok;

    		$dataUpdate['edited_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;

    		$dataInsert['id_produk'] = $params['idProduk'];
    		$dataInsert['id_order_detail'] = 0;
    		$dataInsert['id_service'] = 0;
            $dataInsert['last_edited'] = $dateNow;
            $dataInsert['add_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
            $dataInsert['edited_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
            $dataInsert['id_warna'] = $id_warna;
            $dataInsert['id_ukuran'] = $id_ukuran;
            $dataInsert['jumlah'] = $qty;
    		$dataInsert['deleted'] = 1;

            //checking if detail_stok field in m_produk is empty
            $detail_stok = $this->get_detail_stok($params['idProduk']);
            if(!empty($detail_stok)) {
                //update detail stok with new value based on warna & ukuran;
                $item_stok = $this->find_detail_stok($detail_stok, $id_warna, $id_ukuran);
                $lastStok = $item_stok;
                if(($item_stok==='null') && ($state == 'kurang')) {
                    echo json_encode(array("status"=>0, "message"=>"Stok untuk produk ".$dataStok->nama." warna ".$nama_warna." ukuran ".$nama_ukuran." tidak ditemukannnn"));
                        exit();
                }
                
                $new_detail_stok = $this->build_detail_stok($detail_stok, $id_warna, $id_ukuran, $nama_warna, $nama_ukuran, $qty, $state, $dataStok->nama);
                $current_total_stok = $this->total_detail_stok($params['idProduk']);
            }
            else {
                if($state == 'kurang') {
                    //insert stok & detail_stok into m_produk (tidak jadi diinsert -> diganti alert barang tidak ada)
                    echo json_encode(array("status"=>0, "message"=>"Stok untuk produk ".$dataStok->nama." warna ".$nama_warna." ukuran ".$nama_ukuran." tidak ditemukan"));
                        exit();
                }
                else if($state == 'tambah') {
                    $lastStok = 0;
                    $new_total_stok = $qty;
                    $data[] = array(
                                    'id_ukuran' => $id_ukuran,
                                    'id_warna' => $id_warna,
                                    'nama_ukuran' => $nama_ukuran,
                                    'nama_warna' => $nama_warna,
                                    'stok' => $qty
                                );
                    $new_detail_stok = json_encode($data);
                    $current_total_stok = 0;
                }
            }

            //proceed to update m_produk stok & detail stok
            if ($state == "kurang") {
                if ($lastStok < $qty) {
                    echo json_encode(array("status"=>2, "message"=>"Stok untuk produk ".$dataStok->nama." warna ".$nama_warna." ukuran ".$nama_ukuran." terlalu sedikit untuk dikurangi"));
                    exit();
                }
                else {
                    $new_total_stok = $current_total_stok - $qty;
                    $dataUpdate['tanggal_kurang_stok'] = $dateNow;
                    $dataUpdate['last_edited'] = $dateNow;
                    // $dataUpdate['stok'] = $lastStok - $qty;
                    $dataUpdate['stok'] = $new_total_stok;
                    $dataUpdate['detail_stok'] = $new_detail_stok;

                    $dataInsert['status'] = 3;
                    // $dataInsert['stok_akhir'] = $lastStok - $qty;
                    $dataInsert['stok_akhir'] = $new_total_stok;
                    $dataInsert['keterangan'] = $this->session->userdata('nama_user')." mengurangi sebanyak ".$qty." stok ";
                }
            }
            else if($state == "tambah") {
                $new_total_stok = $current_total_stok + $qty;
                $dataUpdate['tanggal_tambah_stok'] = $dateNow;
                $dataUpdate['last_edited'] = $dateNow;
                // $dataUpdate['stok'] = $lastStok + $qty;
                $dataUpdate['stok'] = $new_total_stok;
                $dataUpdate['detail_stok'] = $new_detail_stok;

                $dataInsert['status'] = 4;
                // $dataInsert['stok_akhir'] = $lastStok + $qty;
				$dataInsert['stok_akhir'] = $new_total_stok;
				$dataInsert['keterangan'] = $this->session->userdata('nama_user')." menambahkan sebanyak ".$qty." stok ";
    		}
    		$updateProduk = $this->Transaksibarangmasukmodel->update($dataCondition, $dataUpdate, 'm_produk');

    		if($updateProduk) {
    			$insertHistori = $this->Transaksibarangmasukmodel->insert($dataInsert, 'h_stok_produk');
    			if($insertHistori){
    				echo json_encode(array("status"=>1, "message"=>"Stok untuk produk ".$dataStok->nama." warna ".$nama_warna." ukuran ".$nama_ukuran." telah berhasil diupdate"));
    			} else {
    				echo json_encode(array("status"=>0, "message"=>"Stok untuk produk ".$dataStok->nama." warna ".$nama_warna." ukuran ".$nama_ukuran." gagal diupdate"));
    			}
    		} else {
    			echo json_encode(array("status"=>0, "message"=>"Stok untuk produk ".$dataStok->nama." warna ".$nama_warna." ukuran ".$nama_ukuran." gagal diupdate"));
    		}
    	}
    }
}