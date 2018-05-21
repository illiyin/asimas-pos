<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Master_bahan/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Bahanmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Bahanmodel->insert($dataInsert, 't_log');
    }
    function index(){
    	$dataSelect['deleted'] = 1;
        $data['list_satuan'] = json_encode($this->Bahanmodel->select($dataSelect, 'm_satuan', 'nama')->result());
        $data['list_kategori'] = json_encode($this->Bahanmodel->select($dataSelect, 'm_bahan_kategori', 'nama')->result());
        $data['list'] = json_encode($this->dataBahan());
		//echo $data;
		//print_r($data);
    	$this->load->view('Master_bahan/view', $data);
    }
    public function data() {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'bahan.date_add'
        );

        $sql = "SELECT bahan.id, bahan.id_satuan, bahan.id_kategori_bahan, kategori.nama AS nama_kategori,
        bahan.nama AS nama_bahan, bahan.kode_bahan, tbahan.jumlah_masuk,
        tbahan.jumlah_keluar, tbahan.saldo_bulan_sekarang, tbahan.saldo_bulan_kemarin,
        bahan.tgl_datang, bahan.date_add , bahan.last_edited, bahan.expired_date, tbahan.tanggal
        FROM m_bahan bahan, tt_bahan tbahan, m_bahan_kategori kategori
        WHERE bahan.deleted = 1 
        AND bahan.id_kategori_bahan = kategori.id
        AND bahan.id = tbahan.id_bahan";
        if( !empty($requestData['search']['value']) ) {
          $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' ";
          $sql.=" OR kategori.nama LIKE '%".$requestData['search']['value']."%' )";
        }

        $query=$this->Bahanmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."";
        $query=$this->Bahanmodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_bahan"];
            $nestedData[]   =   $row["nama_kategori"];
            $nestedData[]   =   date("d/m/Y H:i", strtotime($row['date_add']));
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
                    "data"            => $data,
                    );
        echo json_encode($json_data);
    }
    private function dataBahan($id = '') {
        $sql = "SELECT bahan.id, bahan.id_satuan, bahan.id_kategori_bahan,
        bahan.nama AS nama_bahan, bahan.kode_bahan, tbahan.jumlah_masuk,
        tbahan.jumlah_keluar, tbahan.saldo_bulan_sekarang, tbahan.saldo_bulan_kemarin,
        bahan.tgl_datang, bahan.date_add , bahan.last_edited, bahan.expired_date, tbahan.tanggal
        FROM m_bahan bahan, tt_bahan tbahan
        WHERE bahan.deleted = 1 
        AND bahan.id = tbahan.id_bahan";
        if($id) $sql.= " AND bahan.id = '".$id."'";

        $query = $this->Bahanmodel->rawQuery($sql)->result();
        $data = null;
        foreach($query as $row) {
            $kategori = $this->Bahanmodel->select(array('id' => $row->id_kategori_bahan), 'm_bahan_kategori')->row();

            $data[] = array(
                    'id' => $row->id,
                    'id_satuan' => $row->id_satuan,
                    'kategori' => array(
                            'id' => $row->id_kategori_bahan,
                            'kode' => $kategori->kode_kategori,
                            'nama' => $kategori->nama,
                        ),
                    'nama_bahan' => $row->nama_bahan,
                    'kode_bahan' => $row->kode_bahan,
                    'jumlah_masuk' => $row->jumlah_masuk,
                    'jumlah_keluar' => $row->jumlah_keluar,
                    'saldo_bulan_sekarang' => $row->saldo_bulan_sekarang,
                    'saldo_bulan_kemarin' => $row->saldo_bulan_kemarin,
                    'tanggal_datang' => $row->tgl_datang,
                    'tanggal' => $row->tanggal,
                    'expired_date' => $row->expired_date,
                    'date_add' => $row->date_add,
                    'last_edited' => $row->last_edited
                );
        }

        return $data;
    }
    function add() {
        $params = $this->input->post();
        $dateExplode                        = explode("/", $params['tgl_datang']);
        $expiredExplode                     = explode("/", $params['expired_date']);
        $dataInsert['id_kategori_bahan']    = $params['id_kategori'];
        $dataInsert['id_satuan']            = $params['id_satuan'];
        $dataInsert['nama']                 = $params['nama'];
        $dataInsert['kode_bahan']           = $params['kode_bahan'] ? $params['kode_bahan'] : '-';
        $dataInsert['tgl_datang']           = $dateExplode[2].'-'.$dateExplode[1].'-'.$dateExplode[0];
        $dataInsert['expired_date']         = $expiredExplode[2].'-'.$expiredExplode[1].'-'.$expiredExplode[0];
        $dataInsert['date_add']             = date("Y-m-d H:i:s");
        $dataInsert['add_by']               = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['last_edited']          = date("Y-m-d H:i:s");
        $dataInsert['edited_by']            = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']              = 1;

        $insert = $this->Bahanmodel->insert_id($dataInsert, 'm_bahan');
        if($insert){
            $insertStok['id_bahan']             = $insert;
            $insertStok['jumlah_masuk']         = $params['jumlah_masuk'];
            $insertStok['jumlah_keluar']        = $params['jumlah_keluar'];
            $insertStok['saldo_bulan_sekarang'] = $params['saldo_sekarang'];
            $insertStok['saldo_bulan_kemarin']  = $params['saldo_kemarin'];
            $insertStok['tanggal']              = date('Y-m-1');
            $insertStok = $this->Bahanmodel->insert($insertStok, 'tt_bahan');
            $list = $this->dataBahan();
            echo json_encode(array('status' => 3,'list' => $list));
        }else{
            echo json_encode(array('status' => 2));
        }
    }
    function edit() {
        $params = $this->input->post();

        $dataCondition['id']                = $params['id'];
        $dateExplode                        = explode("/", $params['tgl_datang']);
        $expiredExplode                     = explode("/", $params['expired_date']);
        $dataUpdate['id_kategori_bahan']    = $params['id_kategori'];
        $dataUpdate['id_satuan']            = $params['id_satuan'];
        $dataUpdate['nama']                 = $params['nama'];
        $dataUpdate['kode_bahan']           = $params['kode_bahan'];
        $dataUpdate['tgl_datang']           = $dateExplode[2].'-'.$dateExplode[1].'-'.$dateExplode[0];
        $dataUpdate['expired_date']         = $expiredExplode[2].'-'.$expiredExplode[1].'-'.$expiredExplode[0];
        $dataUpdate['last_edited']          = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']            = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataUpdate['deleted']              = 1;

        $checkData = $this->Bahanmodel->select($dataCondition, 'm_bahan');
        if($checkData->num_rows() > 0){
            $update = $this->Bahanmodel->update($dataCondition, $dataUpdate, 'm_bahan');
            if($update){
                $stokUpdate['jumlah_masuk']         = $params['jumlah_masuk'];
                $stokUpdate['jumlah_keluar']        = $params['jumlah_keluar'];
                $stokUpdate['saldo_bulan_sekarang'] = $params['saldo_sekarang'];
                $stokUpdate['saldo_bulan_kemarin']  = $params['saldo_kemarin'];
                $updateStok = $this->Bahanmodel->update(array('id_bahan' => $params['id']), $stokUpdate, 'tt_bahan');
                $list = $this->dataBahan();
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
          $update = $this->Bahanmodel->update($dataCondition, $dataUpdate, 'm_bahan');
          if($update){
              $dataSelect['deleted'] = 1;
              $list = $this->dataBahan();
              echo json_encode(array('status' => '3','list' => $list));
          }else{
              echo "1";
          }
      }else{
          echo "0";
      }
    }
    function similar() {
      $params = $this->input->post();
      if(!$params) redirect();

      $namaBahan = trim(strtolower($params['nama_bahan']));
      $sql = "SELECT id, kode_bahan AS nama FROM m_bahan WHERE nama LIKE '%".$namaBahan."%'";
      $query = $this->Bahanmodel->rawQuery($sql);

      echo json_encode(array('total' => $query->num_rows(), 'list' => $query->result()));
    }
}
