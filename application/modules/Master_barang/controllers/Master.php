<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_barang/";
    private $fungsi = "";
    function __construct() {
        parent::__construct();
        $this->load->model('Barangmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Barangmodel->insert($dataInsert, 't_log');
    }
    function index(){
        $dataSelect['deleted'] = 1;
        $data['list_supplier'] = json_encode($this->Barangmodel->select($dataSelect, 'm_supplier', 'nama')->result());
        $data['list_satuan'] = json_encode($this->Barangmodel->select($dataSelect, 'm_satuan', 'nama')->result());
        $data['list_kategori'] = json_encode($this->Barangmodel->select($dataSelect, 'm_bahan_kategori', 'nama')->result());
        $data['list'] = json_encode($this->Barangmodel->select($dataSelect, 'm_barang')->result());
        //echo $data;
        //print_r($data);
        $this->load->view('Master_barang/view', $data);
    }
    private function dataBarang($id = '') {
        $dataSelect['deleted'] = 1;
        if($id) $dataSelect['id'] = $id;
        $dataBarang = $this->Barangmodel->select($dataSelect, 'm_barang')->result();
        $data = null;
        foreach ($dataBarang as $row) {
            $dataSatuan = $this->Barangmodel->select(array('id' => $row->id_satuan), 'm_satuan')->row();
            $dataBahan = $this->Barangmodel->select(array('id' => $row->id_kategori_bahan), 'm_bahan_kategori')->row();
            $dataSupplier = $this->Barangmodel->select(array('id' => $row->id_supplier), 'm_supplier')->row();

            $data[] = array(
                    'id' => $row->id,
                    'satuan' => $dataSatuan,
                    'bahan' => $dataBahan,
                    'supplier' => $dataSupplier,
                    'nama_barang' => $row->nama_barang,
                    'no_batch' => $row->no_batch,
                    'jumlah_masuk' => $row->jumlah_masuk,
                    'stok_akhir' => $row->stok_akhir,
                    'expired_date' => $row->expired_date,
                    'keterangan' => $row->keterangan
                );
        }
        return $data;
    }
    function data(){
        $requestData= $_REQUEST;
        $columns = array(
            // 0   =>  '#',
            1   =>  'nama_barang',
            2   =>  'no_batch',
            3   =>  'stok_akhir',
            4   =>  'date_add',
            // 5   =>  'aksi'
        );
        $sql = "SELECT * FROM m_barang WHERE deleted = 1";
        $query=$this->Barangmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT * ";
        $sql.=" FROM m_barang WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama_barang LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_batch LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Barangmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query=$this->Barangmodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_barang"];
            $nestedData[]   =   strlen($row["no_batch"]) == 0 ? '<strong>Belum di Setting</strong>' : $row["no_batch"];
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["stok_akhir"]."</span>";
            $nestedData[]   =   $row['expired_date'] == '0000-00-00' ? '<strong>Belum di Setting</strong>' : date("d-m-Y", strtotime($row["expired_date"]));
            $nestedData[]   .=   '<td class="text-center"><div class="btn-group">'
                .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$row["id"].')"><i class="fa fa-pencil"></i></a>'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Lihat Detail" onclick="showDetail('.$row["id"].')"><i class="fa fa-file-text-o"></i></a>'
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

        $expiredDate = explode("/", $params['expired_date']);
        $condition['no_batch'] = $params['no_batch'];
        $condition['deleted'] = 1;
        $dataInsert['id_satuan']        = $params['id_satuan'];
        $dataInsert['id_kategori_bahan']= $params['id_kategori'];
        $dataInsert['id_supplier']      = isset($params['id_supplier']) ? $params['id_supplier'] : 0;
        $dataInsert['nama_barang']      = $params['nama'];
        $dataInsert['jumlah_masuk']     = isset($params['jml_masuk']) ? $params['jml_masuk'] : 0;
        $dataInsert['no_batch']         = isset($params['no_batch']) ? $params['no_batch'] : null;
        $dataInsert['expired_date']     = isset($params['expired_date']) ?
                 $expiredDate[2].'-'.$expiredDate[1].'-'.$expiredDate[0]: '0000-00-00';
        $dataInsert['stok_akhir']       = $params['stok_akhir'];
        $dataInsert['keterangan']       = $params['deskripsi'];
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;;
        $dataInsert['date_add']         = date('Y-m-d H:i:s');
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;;
        $dataInsert['last_edited']      = date('Y-m-d H:i:s');
        $dataInsert['deleted']          = 1;

        $checkData = $this->Barangmodel->select($condition, 'm_barang');
        if($checkData->num_rows() < 1){
            $insert = $this->Barangmodel->insert($dataInsert, 'm_barang');
            if($insert){
                $list = $this->dataBarang();
                echo json_encode(array('status' => 3,'list' => $list));
            }else{
                echo json_encode(array('status' => 2));
            }

        }else{
            echo json_encode(array( 'status'=> 1, 'message' => 'No Batch sudah ada!'));
        }
    }
    function edit() {
        $params = $this->input->post();

        $expiredDate = explode("/", $params['expired_date']);
        $condition['id'] = $params['id'];
        $dataUpdate['id_satuan']        = $params['id_satuan'];
        $dataUpdate['id_kategori_bahan']= $params['id_kategori'];
        $dataUpdate['id_supplier']      = $params['id_supplier'];
        $dataUpdate['nama_barang']      = $params['nama'];
        $dataUpdate['jumlah_masuk']     = $params['jml_masuk'];
        $dataUpdate['no_batch']         = $params['no_batch'];
        $dataUpdate['expired_date']     = $expiredDate[2].'-'.$expiredDate[1].'-'.$expiredDate[0].' 00:00:00';
        $dataUpdate['stok_akhir']       = $params['stok_akhir'];
        $dataUpdate['keterangan']       = $params['deskripsi'];
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;;
        $dataUpdate['last_edited']      = date('Y-m-d H:i:s');

        $checkData = $this->Barangmodel->select($condition, 'm_barang');
        if($checkData->num_rows() > 0){
            $update = $this->Barangmodel->update($condition, $dataUpdate, 'm_barang');
            if($update){
                $list = $this->dataBarang();
                echo json_encode(array('status' => '3','list' => $list));
            }else{
                echo json_encode(array( 'status'=>'2' ));
            }
        }else{
            echo json_encode(array( 'status'=>'1' ));
        }
    }
    function delete() {
        $id = $this->input->post("id");
        if($id != null){
            $dataCondition['id'] = $id;
            $dataUpdate['deleted'] = 0;
            $update = $this->Barangmodel->update($dataCondition, $dataUpdate, 'm_barang');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->dataBarang();
                echo json_encode(array('status' => '3','list' => $list));
            }else{
                echo "1";
            }
        }else{
            echo "0";
        }
    }
}
