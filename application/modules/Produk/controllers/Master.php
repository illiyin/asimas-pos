<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Produk/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Produkmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Produkmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $data['list_supplier'] = json_encode($this->Produkmodel->select($dataSelect, 'm_supplier_produk', 'nama')->result());
        $data['list_satuan'] = json_encode($this->Produkmodel->select($dataSelect, 'm_satuan', 'nama')->result());
        $data['list_gudang'] = json_encode($this->Produkmodel->select($dataSelect, 'm_gudang', 'nama')->result());
        $data['list_kategori'] = json_encode($this->Produkmodel->select($dataSelect, 'm_produk_kategori', 'nama')->result());
        $data['list_bahan'] = json_encode($this->Produkmodel->select($dataSelect, 'm_produk_bahan', 'nama')->result());
        $data['list_katalog'] = json_encode($this->Produkmodel->select($dataSelect, 'm_produk_katalog', 'nama')->result());
        $data['list_merk'] = json_encode($this->Produkmodel->select($dataSelect, 'm_produk_merk', 'nama')->result());
        $data['list_customer_level'] = json_encode($this->Produkmodel->select($dataSelect, 'm_customer_level', 'nama')->result());
        
        $data['list_ukuran'] = json_encode($this->Produkmodel->select($dataSelect, 'm_produk_ukuran', 'nama')->result());
        $data['list_warna'] = json_encode($this->Produkmodel->select($dataSelect, 'm_produk_warna', 'nama')->result());

        $data['list_det_ukuran'] = json_encode($this->Produkmodel->get('m_produk_det_ukuran')->result());
        $data['list_det_warna'] = json_encode($this->Produkmodel->get('m_produk_det_warna')->result());
        $data['list_det_harga'] = json_encode($this->Produkmodel->get('m_produk_det_harga')->result());

        $data['list'] = json_encode($this->Produkmodel->select($dataSelect, 'm_produk')->result());
		//echo $data;
		//print_r($data);
    	$this->load->view('Produk/view', $data);
    }

    function data(){
        $requestData= $_REQUEST;
        $columns = array( 
            0   =>  '#', 
            1   =>  'foto', 
            2   =>  'nama', 
            3   =>  'merk', 
            4   =>  'sku',
            5   =>  'stok',
            6   =>  'detail_stok',
            7   =>  'harga_jual_normal',
            8   =>  'date_add',
            9   =>  'aksi'
        );
        $sql = "SELECT A.*, B.nama AS merk FROM m_produk A LEFT JOIN m_produk_merk B ON B.id = A.id_merk WHERE A.deleted = 1";
        $query=$this->Produkmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        // $totalFiltered = $totalData;
        
        $sql = "SELECT A.*, B.nama AS merk";
        $sql.=" FROM m_produk A LEFT JOIN m_produk_merk B ON B.id = A.id_merk WHERE A.deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( A.nama LIKE '%".$requestData['search']['value']."%' "; 
            $sql.=" OR B.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.sku LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.detail_stok LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.harga_jual_normal LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.date_add LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Produkmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $query=$this->Produkmodel->rawQuery($sql);
        
        $data = array(); $i=0;
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

            $nestedData     =   array(); 
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   .=  "<a href='javascript:void(0)' data-toggle='popover' data-html='true' data-placement='right' onclick='showThumbnail(this)'>"
                            . "<img src='".$foto_url."' class='img-responsive img-rounded' width='70' alt='No Image' style='margin:0 auto;'> </a>";
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   $row["merk"];
            $nestedData[]   =   $row["sku"];
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["stok"]."</span>";
            $nestedData[]   =   (!empty($row["detail_stok"]) ? $html_detail : "<span class='text-center' style='display:block;'>-</span>") ;
            $nestedData[]   =   "<span class='pull-right money' style='display:block;'>".$row["harga_jual_normal"]."</span>";
            $nestedData[]   =   date("d-m-Y H:i", strtotime($row["date_add"]));
            $nestedData[]   .=   '<div class="center-block text-center"><div class="btn-group">'
                .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$row["id"].')"><i class="fa fa-pencil"></i></a>'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Lihat Detail" onclick="showDetail('.$row["id"].')"><i class="fa fa-file-text-o"></i></a>'
                .'</div><div class="btn-group" style="margin-top:5px;">'
                .'<a class="btn btn-sm btn-default" title="Lihat Barcode" onclick="showBarcode('.$row["id"].')"><i class="fa fa-barcode"></i></a>'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Harga Jual" onclick="showHarga('.$row["id"].')"><i class="fa fa-dollar"></i></a>'
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

    function show_barcode($id = '') {
        $result = array();
        if(!empty($id)) {
            $detail_stok = json_decode($this->get_detail_stok($id), true);

            if(!empty($detail_stok)) {
                $result = $detail_stok;
            }
        }
        echo json_encode($result);
    }
    private function get_detail_stok($id_produk) {
        //fetch detail_stok from current product
        $result = 0;
        if(!empty($id_produk)) {
            $condition = array('id' => $id_produk, 'deleted' => 1);
            $data_produk = $this->Produkmodel->select($condition, 'm_produk')->row();

            $result = isset($data_produk->detail_stok) ? $data_produk->detail_stok : 0;
        }
        return $result;
    }

	function test(){
		header('Content-Type: application/json; charset=utf-8');
		$dataSelect['deleted'] = 1;
		$list = $this->Produkmodel->select($dataSelect, 'm_produk')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
    function add(){
        $params = $this->input->post();
        $id = (!empty($params['id'])) ? $params['id'] : $this->Produkmodel->get_last_id("m_produk") + 1;

        $dataInsert['nama']             = $params['nama'];
        $dataInsert['id_supplier']      = $params['id_supplier'];
        $dataInsert['id_satuan']        = $params['id_satuan'];
        $dataInsert['id_gudang']        = $params['id_gudang'];
        $dataInsert['id_kategori']      = $params['id_kategori'];
        $dataInsert['id_bahan']         = $params['id_bahan'];
        $dataInsert['id_katalog']       = $params['id_katalog'];
        $dataInsert['id_merk']          = $params['id_merk'];
        $dataInsert['sku']              = $params['sku'];
        $dataInsert['kode_barang']      = $params['kode_barang'];
        $dataInsert['berat']            = $params['berat'];
        $dataInsert['harga_beli']       = $params['harga_beli'];
        $dataInsert['harga_jual_normal'] = $params['harga_jual_normal'];
        // $dataInsert['versi_foto']       = $params['versi_foto'];
        $dataInsert['deskripsi']        = $params['deskripsi'];
        $dataInsert['foto']             = $this->proses_foto($id);
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']          = 1;

        $checkData = $this->Produkmodel->select($dataInsert, 'm_produk');
        if($checkData->num_rows() < 1){
            $insert = $this->Produkmodel->insert_id($dataInsert, 'm_produk');
            if($insert){
                if(isset($params['id_ukuran'])){
                    $this->insert_detail($insert, $params['id_ukuran'], "ukuran");
                }
                if(isset($params['id_warna'])){
                    $this->insert_detail($insert, $params['id_warna'], "warna");
                }
                $dataSelect['deleted'] = 1;
                $list = $this->Produkmodel->select($dataSelect, 'm_produk')->result();
                $list_det_ukuran= $this->Produkmodel->get('m_produk_det_ukuran')->result();
                $list_det_warna = $this->Produkmodel->get('m_produk_det_warna')->result();
                echo json_encode(array('status'=>3,'list'=>$list ,'list_det_ukuran'=>$list_det_ukuran ,'list_det_warna' => $list_det_warna));
            }else{
                echo json_encode(array('status' => 1));
            }
            
        }else{          
            echo json_encode(array( 'status'=>1 ));
        }
    }

    function add_det_harga(){
		$params = $this->input->post();
        $id = (!empty($params['id'])) ? $params['id'] : '';
        unset($params['id']);

        if(isset($id)){
            foreach ($params as $key => $value) {
                $split = explode("_", $key);
                $dataInsert[] = array(
                            'id_produk' => $id, 
                            'id_customer_level' => $split[1], 
                            'harga' => $value, 
                        );
            }
        
            $dataCondition = array('id_produk' => $id);
            $checkData = $this->Produkmodel->select($dataCondition, 'm_produk_det_harga');
            if($checkData->num_rows() > 0) {
                $this->Produkmodel->delete($dataCondition, 'm_produk_det_harga');       
            }

            $insert = $this->Produkmodel->insert_batch($dataInsert, 'm_produk_det_harga');
            if($insert) {
                $list = $this->Produkmodel->get('m_produk_det_harga')->result();
                echo json_encode(array('status' => 3,'list' => $list));
            }else{
                echo json_encode(array('status' => 2));
            }
        }
        else{
            echo json_encode(array( 'status'=>1 ));
        }
    }
	
	function get($id = null){   	
    	if($id != null){
    		$dataSelect['id'] = $id;
    		$selectData = $this->Produkmodel->select($dataSelect, 'm_produk');
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
	
    function last_id() {
        echo "<script>console.log(".$this->Produkmodel->get_last_id("m_produk").");</script>";
    }
    function edit(){
		$params = $this->input->post();
        $id = (!empty($params['id'])) ? $params['id'] : $this->Produkmodel->get_last_id("m_produk") + 1;

		$dataCondition['id']			= $params['id'];
		$dataUpdate['nama'] 			= $params['nama'];
        $dataUpdate['id_supplier']      = $params['id_supplier'];
        $dataUpdate['id_satuan']        = $params['id_satuan'];
        $dataUpdate['id_gudang']        = $params['id_gudang'];
        $dataUpdate['id_kategori']      = $params['id_kategori'];
        $dataUpdate['id_bahan']         = $params['id_bahan'];
        $dataUpdate['id_katalog']       = $params['id_katalog'];
        $dataUpdate['id_merk']          = $params['id_merk'];
        $dataUpdate['sku']              = $params['sku'];
        $dataUpdate['kode_barang']      = $params['kode_barang'];
        $dataUpdate['berat']            = $params['berat'];
        $dataUpdate['harga_beli']       = $params['harga_beli'];
        $dataUpdate['harga_jual_normal'] = $params['harga_jual_normal'];
        // $dataUpdate['versi_foto']       = $params['versi_foto'];
        $dataUpdate['deskripsi']        = $params['deskripsi'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        if(!$_FILES['foto']['error']) {
            $dataUpdate['foto']         = $this->proses_foto($id);
        }
        
		$checkData = $this->Produkmodel->select($dataCondition, 'm_produk');
		if($checkData->num_rows() > 0){
			$update = $this->Produkmodel->update($dataCondition, $dataUpdate, 'm_produk');
            if(isset($params['id_ukuran'])){
                $this->insert_detail($params['id'], $params['id_ukuran'], "ukuran");
            }
            if(isset($params['id_warna'])){
                $this->insert_detail($params['id'], $params['id_warna'], "warna");
            }

			if($update){
				$dataSelect['deleted'] = 1;
				$list = $this->Produkmodel->select($dataSelect, 'm_produk')->result();
                $list_det_ukuran= $this->Produkmodel->get('m_produk_det_ukuran')->result();
                $list_det_warna = $this->Produkmodel->get('m_produk_det_warna')->result();
				echo json_encode(array('status'=>'3','list'=>$list ,'list_det_ukuran'=>$list_det_ukuran ,'list_det_warna' => $list_det_warna));
			}else{
				echo json_encode(array( 'status'=>'2' ));
			}
		}else{			
    		echo json_encode(array( 'status'=>'1' ));
		}
    }
    function delete(){
		$id = $this->input->post("id");
    	if($id != null){
    		$dataCondition['id'] = $id;
    		$dataUpdate['deleted'] = 0;
    		$update = $this->Produkmodel->update($dataCondition, $dataUpdate, 'm_produk');
    		if($update){
    			$dataSelect['deleted'] = 1;
				$list = $this->Produkmodel->select($dataSelect, 'm_produk')->result();
				echo json_encode(array('status' => '3','list' => $list));
    		}else{
    			echo "1";
    		}
    	}else{
    		echo "0";
    	}
    }
    function buttonDelete($id=null){
    	if($id!=null){
    		echo "<button class='btn btn-danger' onclick='delRow(".$id.")'>YA</button>";
    	}else{
    		echo "NOT FOUND";
    	}
    }
    
    private function insert_detail($id_produk, $data, $table) {
        if(!empty($table) AND !empty($data)) {
            //check if id_produk exist in m_produk_det_ tables
            $dataInsert = array();
            $dataCondition['id_produk'] = $id_produk;
            $checkData = $this->Produkmodel->select($dataCondition, 'm_produk_det_'.$table);
            if($checkData->num_rows() > 0) {
                //Delete old data first
                $this->Produkmodel->delete($dataCondition, 'm_produk_det_'.$table);       
            }
            
            //Then insert new data       
            foreach ($data as $key=>$value) {
                $dataInsert[] = array(
                        'id_produk' => $id_produk,
                        'id_'.$table => $value
                    );
            } // print_r($dataInsert);
            $this->Produkmodel->insert_batch($dataInsert, 'm_produk_det_'.$table);
        }
    }
    private function proses_foto($id) {
        $date = date("dmY"); $time = date("His");
        $input_name = 'foto';

        $tipe = $this->cek_tipe($_FILES[$input_name]['type']);
        $img_path = URL_UPLOAD."produk/";
        $img_name = "productImage".$id.$tipe;

        $config['overwrite'] = true;
        $config['upload_path'] = $img_path;
        $config['allowed_types'] = "png|jpg|jpeg";
        $config['file_name'] = $img_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($input_name)) 
        {
            $error = array('error' => $this->upload->display_errors());
            $this->upload->display_errors();
        }
        else {
            $file_data = $this->upload->data();
            $upload_data['file_name'] = $file_data['file_name'];
            $upload_data['created'] = date("Y-m-d H:i:s");
            $upload_data['modified'] = date("Y-m-d H:i:s");
            //echo $upload data if you want to see the file information
        }

        return $img_name;
    }
    private function cek_tipe($tipe)
    {
        if ($tipe == 'image/jpeg') 
            { return ".jpg"; }
        else if($tipe == 'image/png') 
            { return ".png"; }
        else 
            { return false; }
    }   
    
}