<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_produk_jadi/";
    private $fungsi = "";
    function __construct() {
        parent::__construct();
        $this->load->model('Masterprodukjadimodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Masterprodukjadimodel->insert($dataInsert, 't_log');
    }
    function index(){
        $dataSelect['deleted'] = 1;
        $data['session_detail'] = pegawaiLevel($this->session->userdata('id_user_level'));
        $data['list_data'] = $this->Masterprodukjadimodel->select($dataSelect, 'm_produk_jadi', 'date_added', 'DESC')->result();
        $this->load->view('Master_produk_jadi/view', $data);
    }
    function data(){
        if(!$this->input->post()) redirect();
        $requestData= $_REQUEST;
        $columns = array(
            0   =>  'id',
            1   =>  'nama_barang',
            2   =>  'no_po',
            3   =>  'no_so',
            4   =>  'harga',
            5   =>  'expired_date',
            6   =>  'stok',
            // 5   =>  'aksi'
        );
        $sql = "SELECT * FROM m_produk_jadi WHERE deleted = 1";
        $query=$this->Masterprodukjadimodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT * ";
        $sql.=" FROM m_produk_jadi WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama_barang LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_po LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_so LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Masterprodukjadimodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."";
        
        $query=$this->Masterprodukjadimodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_barang"];
            $nestedData[]   =   $row["no_po"];
            $nestedData[]   =   $row["no_so"];
            $nestedData[]   =   $row["harga"];
            $nestedData[]   =   date("d/m/Y", strtotime($row["expired_date"]));
            $nestedData[]   =   $row["stok"];
            $nestedData[]   =   '<td class="text-center"><div class="btn-group">'
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
    function add(){
      $params = $this->input->post();

      $expiredDate = explode("/", $params['expired_date']);
      $dataInsert['nama_barang']        = $params['nama'];
      $dataInsert['no_po']              = $params['no_purchase'];
      $dataInsert['no_so']              = $params['no_sales'];
      $dataInsert['harga']              = $params['harga'];
      $dataInsert['stok']               = $params['stok'];
      $dataInsert['expired_date']       = $expiredDate[2].'-'.$expiredDate[1].'-'.$expiredDate[0];
      $dataInsert['added_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
      $dataInsert['date_added']         = date('Y-m-d H:i:s');
      $dataInsert['modified_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
      $dataInsert['last_modified']      = date('Y-m-d H:i:s');
      $dataInsert['deleted']          = 1;

      $insert = $this->Masterprodukjadimodel->insert($dataInsert, 'm_produk_jadi');
      if($insert){
          $dataSelect['deleted'] = 1;
          $list = $this->Masterprodukjadimodel->select($dataSelect, 'm_produk_jadi', 'date_added', 'DESC');
          echo json_encode(array('status' => 3,'list' => $list));
      }else{
          echo json_encode(array('status' => 2, 'message' => "Error after insert data!"));
      }
    }
    function edit(){
      $params = $this->input->post();

      $expiredDate = explode("/", $params['expired_date']);
      $condition['id'] = $params['id'];
      $dataUpdate['nama_barang']        = $params['nama'];
      $dataUpdate['no_po']              = $params['no_purchase'];
      $dataUpdate['no_so']              = $params['no_sales'];
      $dataUpdate['harga']              = $params['harga'];
      $dataUpdate['stok']               = $params['stok'];
      $dataUpdate['expired_date']       = $expiredDate[2].'-'.$expiredDate[1].'-'.$expiredDate[0];
      $dataUpdate['modified_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
      $dataUpdate['last_modified']      = date('Y-m-d H:i:s');
      $dataUpdate['deleted']          = 1;

      $checkData = $this->Masterprodukjadimodel->select($condition, 'm_produk_jadi');
      if($checkData->num_rows() > 0){
          $update = $this->Masterprodukjadimodel->update($condition, $dataUpdate, 'm_produk_jadi');
          if($update){
              $dataSelect['deleted'] = 1;
          $list = $this->Masterprodukjadimodel->select($dataSelect, 'm_produk_jadi', 'date_added', 'DESC');
              echo json_encode(array('status' => '3','list' => $list));
          }else{
              echo json_encode(array( 'status'=>'2' ));
          }
      }else{
          echo json_encode(array( 'status'=>'1', 'message' => 'Data Produk Jadi Tidak ditemukan!'));
      }
    }
    function delete() {
      $id = $this->input->post("id");
      if($id != null){
          $dataCondition['id'] = $id;
          $dataUpdate['deleted'] = 0;
          $update = $this->Masterprodukjadimodel->update($dataCondition, $dataUpdate, 'm_produk_jadi');
          if($update){
              $dataSelect['deleted'] = 1;
              $list = $this->Masterprodukjadimodel->select($dataSelect, 'm_produk_jadi', 'date_added', 'DESC');
              echo json_encode(array('status' => '3','list' => $list));
          }else{
              echo "1";
          }
      }else{
          echo "0";
      }
    }
}
