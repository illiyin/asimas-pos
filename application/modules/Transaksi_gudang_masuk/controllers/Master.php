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
        $data['list_supplier'] = $this->Transaksigudangmasukmodel->select($dataSelect, 'm_supplier')->result();
        $data['list_kategori_bahan'] = $this->Transaksigudangmasukmodel->select($dataSelect, 'm_bahan_kategori')->result();
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
            0 => 'no_transaksi',
            1 => 'no_batch',
            2 => 'nama_bahan',
            3 => 'nama_supplier',
            4 => 'nama_produsen',
        );
        $sql = "SELECT * FROM tt_gudang_masuk WHERE deleted = 1";
        $query=$this->Transaksigudangmasukmodel->rawQuery($sql);
        $totalData = $query->num_rows();

        // Filter
        $sql = "SELECT
                gm.id, gm.no_transaksi, gm.no_batch,
                bahan.nama AS nama_bahan, produsen.nama AS nama_produsen, 
                supplier.nama AS nama_supplier, gm.jumlah_masuk, gm.no_so,
                gm.expired_date, gm.tanggal_masuk
                FROM tt_gudang_masuk gm, m_bahan bahan, m_produsen produsen, m_supplier supplier
                WHERE gm.deleted = 1 AND gm.id_bahan = bahan.id 
                AND gm.id_produsen = produsen.id AND gm.id_supplier = supplier.id";

        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( gm.no_transaksi LIKE '%".$requestData['search']['value']."%' )";
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
            $nestedData[]   =   $row['no_batch'];
            $nestedData[]   =   $row['nama_bahan'];
            $nestedData[]   =   $row['nama_supplier'];
            $nestedData[]   =   $row['nama_produsen'];
            $nestedData[]   =   $row['jumlah_masuk'];
            $nestedData[]   =   date('d/m/Y', strtotime($row['expired_date']));
            $nestedData[]   =   date('d/m/Y', strtotime($row['tanggal_masuk']));
            $nestedData[]   .=   '<td class="text-center"><div class="btn-group">'
                .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
                // .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$row["id"].')"><i class="fa fa-pencil"></i></a>'
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
        $expiredExplode                     = explode("/", $params['expire_date']);
        $dataInsert['no_transaksi']         = $params['no_transaksi'];
        $dataInsert['no_batch']             = $params['no_batch'];
        $dataInsert['id_bahan']             = $params['id_bahan'];
        $dataInsert['id_produsen']          = $params['id_produsen'];
        $dataInsert['id_supplier']          = $params['id_supplier'];
        $dataInsert['no_so']                = $params['no_so'];
        $dataInsert['jumlah_masuk']         = $params['jumlah_masuk'];
        $dataInsert['tanggal_masuk']        = $dateExplode[2].'-'.$dateExplode[1].'-'.$dateExplode[0];
        $dataInsert['expired_date']         = $expiredExplode[2].'-'.$expiredExplode[1].'-'.$expiredExplode[0];
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
    // function edit() {
    //     $params = $this->input->post();

    //     $condition['id'] = $params['id'];
    //     $dateExplode                        = explode("/", $params['tanggal_masuk']);
    //     $dataUpdate['id_barang']            = $params['id_barang'];
    //     $dataUpdate['id_produsen']          = $params['id_produsen'];
    //     $dataUpdate['id_bahan']             = $params['id_bahan'];
    //     $dataUpdate['harga_pembelian']      = $params['harga_beli'];
    //     $dataUpdate['tanggal_masuk']        = $dateExplode[2].'-'.$dateExplode[1].'-'.$dateExplode[0].' 00:00:00';
    //     $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;;
    //     $dataUpdate['last_edited']      = date('Y-m-d H:i:s');

    //     $checkData = $this->Transaksigudangmasukmodel->select($condition, 'tt_gudang_masuk');
    //     if($checkData->num_rows() > 0){
    //         $update = $this->Transaksigudangmasukmodel->update($condition, $dataUpdate, 'tt_gudang_masuk');
    //         if($update){
    //             $list = $this->Transaksigudangmasukmodel->select(array('deleted' => 1), 'tt_gudang_masuk', 'date_add', 'DESC');
    //             echo json_encode(array('status' => '3','list' => $list));
    //         }else{
    //             echo json_encode(array( 'status'=>'2' , 'message' => "Error code 2"));
    //         }
    //     }else{
    //         echo json_encode(array( 'status'=>'1' , 'message' => "Error code 1"));
    //     }
    // }
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
