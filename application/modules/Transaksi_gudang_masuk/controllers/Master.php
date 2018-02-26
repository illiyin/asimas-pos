<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Transaksi_gudang_masuk/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Transaksigudangmasukmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Transaksigudangmasukmodel->insert($dataInsert, 't_log');
    }
    function index(){
        $dataSelect['deleted'] = 1;
        $data['list'] = $this->Transaksigudangmasukmodel->select($dataSelect, 'tt_gudang_masuk')->result();
        $data['list_barang'] = $this->dataBarang();
        $data['list_produsen'] = $this->Transaksigudangmasukmodel->select($dataSelect, 'm_produsen')->result();
        $data['list_bahan'] = $this->Transaksigudangmasukmodel->select($dataSelect, 'm_bahan')->result();
    	$this->load->view('Transaksi_gudang_masuk/view', $data);
    }
    private function dataBarang($id = '') {
        $dataSelect['deleted'] = 1;
        if($id) $dataSelect['id'] = $id;
        $query = $this->Transaksigudangmasukmodel->select($dataSelect,'m_barang')->result();
        $data = null;
        foreach($query as $row) {
            $dataSatuan = $this->Transaksigudangmasukmodel->select(array('id' => $row->id_satuan), 'm_satuan')->row();
            $dataKategoriBahan = $this->Transaksigudangmasukmodel->select(array('id' => $row->id_kategori_bahan), 'm_bahan_kategori')->row();
            $dataSupplier = $this->Transaksigudangmasukmodel->select(array('id' => $row->id_supplier), 'm_supplier')->row();

            $data[] = array(
                    'id' => $row->id,
                    'satuan' => $dataSatuan,
                    'kategori_bahan' => $dataKategoriBahan,
                    'supplier' => $dataSupplier,
                    'nama_barang' => $row->nama_barang,
                    'jumlah_masuk' => $row->jumlah_masuk,
                    'no_batch' => $row->no_batch,
                    'expired_date' => $row->expired_date,
                    'stok_akhir' => $row->stok_akhir,
                    'keterangan' => $row->keterangan
                );
        }

        return $data;
    }
    private function selectMaster($table, $id) {
        $dataSelect['id'] = $id;
        $dataSelect['deleted'] = 1;
        $query = $this->Transaksigudangmasukmodel->select($dataSelect, $table)->result();
        return $query[0];
    }
    function data() {
        $requestData= $_REQUEST;
        $columns = array(
            0  =>  'no_transaksi',
            1   =>  'nama_barang',
            2   =>  'nama_supplier',
            3   =>  'satuan',
            4   =>  'no_batch',
            5   =>  'kode_bahan',
            6   =>  'nama_produsen',
            7   =>  'keterangan',
        );
        $sql = "SELECT 
                tt_gudang_masuk.id,
                tt_gudang_masuk.no_transaksi, 
                m_barang.nama_barang,
                m_barang.jumlah_masuk,
                m_supplier.nama AS nama_supplier,
                m_satuan.nama AS satuan,
                m_barang.no_batch,
                m_barang.expired_date,
                m_bahan.kode_bahan,
                m_barang.keterangan,
                m_produsen.nama AS nama_produsen,
                tt_gudang_masuk.date_add
                FROM tt_gudang_masuk, m_barang, m_produsen, m_bahan, m_supplier, m_satuan
                WHERE tt_gudang_masuk.id_barang = m_barang.id AND
                tt_gudang_masuk.id_produsen = m_produsen.id AND
                tt_gudang_masuk.id_bahan = m_bahan.id AND
                m_barang.id_satuan = m_satuan.id AND
                m_barang.id_supplier = m_supplier.id AND
                tt_gudang_masuk.deleted = 1";
        $query=$this->Transaksigudangmasukmodel->rawQuery($sql);
        $totalData = $query->num_rows();

        // Filter
        $sql = "SELECT";
        $sql.=" tt_gudang_masuk.id,
                tt_gudang_masuk.no_transaksi, 
                m_barang.nama_barang,
                m_barang.jumlah_masuk,
                m_supplier.nama AS nama_supplier,
                m_satuan.nama AS satuan,
                m_barang.no_batch,
                m_barang.expired_date,
                m_bahan.kode_bahan,
                m_barang.keterangan,
                m_produsen.nama AS nama_produsen,
                tt_gudang_masuk.date_add
                FROM tt_gudang_masuk, m_barang, m_produsen, m_bahan, m_supplier, m_satuan
                WHERE tt_gudang_masuk.id_barang = m_barang.id AND
                tt_gudang_masuk.id_produsen = m_produsen.id AND
                tt_gudang_masuk.id_bahan = m_bahan.id AND
                m_barang.id_satuan = m_satuan.id AND
                m_barang.id_supplier = m_supplier.id AND
                tt_gudang_masuk.deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( tt_gudang_masuk.no_transaksi LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR m_barang.nama_barang LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR m_supplier.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR m_satuan.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR m_barang.no_batch LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR m_bahan.kode_bahan LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR m_produsen.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR m_barang.keterangan LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Transaksigudangmasukmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."";
        $query=$this->Transaksigudangmasukmodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row['no_transaksi'];
            $nestedData[]   =   $row['nama_barang'];
            $nestedData[]   =   $row['nama_supplier'];
            $nestedData[]   =   $row['satuan'];
            $nestedData[]   =   $row['jumlah_masuk'];
            $nestedData[]   =   $row['no_batch'];
            $nestedData[]   =   $row['expired_date'];
            $nestedData[]   =   $row['kode_bahan'];
            $nestedData[]   =   $row['nama_produsen'];
            $nestedData[]   =   $row['keterangan'];
            $nestedData[]   .=   '<td class="text-center"><div class="btn-group">'
                .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$row["id"].')"><i class="fa fa-pencil"></i></a>'
               .'</div>'
            .'</td>';

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
    function add() {
        $params = $this->input->post();

        $condition['no_transaksi']          = $params['no_transaksi'];
        $dateExplode                        = explode("/", $params['tanggal_masuk']);
        $dataInsert['no_transaksi']         = $params['no_transaksi'];
        $dataInsert['id_barang']            = $params['id_barang'];
        $dataInsert['id_produsen']          = $params['id_produsen'];
        $dataInsert['id_bahan']             = $params['id_bahan'];
        $dataInsert['harga_pembelian']      = $params['harga_beli'];
        $dataInsert['tanggal_masuk']        = $dateExplode[2].'-'.$dateExplode[1].'-'.$dateExplode[0].' 00:00:00';
        $dataInsert['date_add']             = date("Y-m-d H:i:s");
        $dataInsert['add_by']               = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['last_edited']          = date("Y-m-d H:i:s");
        $dataInsert['edited_by']            = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']              = 1;

        $checkData = $this->Transaksigudangmasukmodel->select($condition, 'tt_gudang_masuk');
        if($checkData->num_rows() < 1){
            $insert = $this->Transaksigudangmasukmodel->insert($dataInsert, 'tt_gudang_masuk');
            if($insert){
                $list = $this->Transaksigudangmasukmodel->select(array('deleted' => 1), 'tt_gudang_masuk', 'date_add', 'DESC');
                echo json_encode(array('status' => 3,'list' => $list));
            }else{
                echo json_encode(array('status' => 2));
            }

        }else{
            echo json_encode(array( 'status'=> 1, 'message' => 'No transaksi sudah ada!'));
        }
    }
    function edit() {
        $params = $this->input->post();

        $condition['id'] = $params['id'];
        $dateExplode                        = explode("/", $params['tanggal_masuk']);
        $dataUpdate['id_barang']            = $params['id_barang'];
        $dataUpdate['id_produsen']          = $params['id_produsen'];
        $dataUpdate['id_bahan']             = $params['id_bahan'];
        $dataUpdate['harga_pembelian']      = $params['harga_beli'];
        $dataUpdate['tanggal_masuk']        = $dateExplode[2].'-'.$dateExplode[1].'-'.$dateExplode[0].' 00:00:00';
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;;
        $dataUpdate['last_edited']      = date('Y-m-d H:i:s');

        $checkData = $this->Transaksigudangmasukmodel->select($condition, 'tt_gudang_masuk');
        if($checkData->num_rows() > 0){
            $update = $this->Transaksigudangmasukmodel->update($condition, $dataUpdate, 'tt_gudang_masuk');
            if($update){
                $list = $this->Transaksigudangmasukmodel->select(array('deleted' => 1), 'tt_gudang_masuk', 'date_add', 'DESC');
                echo json_encode(array('status' => '3','list' => $list));
            }else{
                echo json_encode(array( 'status'=>'2' , 'message' => "Error code 2"));
            }
        }else{
            echo json_encode(array( 'status'=>'1' , 'message' => "Error code 1"));
        }
    }
    function delete() {
        $id = $this->input->post("id");
        if($id != null){
            $dataCondition['id'] = $id;
            $dataUpdate['deleted'] = 0;
            $update = $this->Transaksigudangmasukmodel->update($dataCondition, $dataUpdate, 'tt_gudang_masuk');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->Transaksigudangmasukmodel->select($dataSelect, 'tt_gudang_masuk', 'date_add', 'DESC');
                echo json_encode(array('status' => '3','list' => $list));
            }else{
                echo "1";
            }
        }else{
            echo "0";
        }
    }
}
