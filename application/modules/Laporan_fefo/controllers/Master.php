<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_fefo/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanfefomodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        // $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Laporanfefomodel->insert($dataInsert, 't_log');
    }
    function index(){
        $this->load->view('Laporan_fefo/view');
    }
    function cetak(){
        $sql = "SELECT * ";
        $sql.=" FROM m_barang WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama_barang LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_batch LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Laporanfefomodel->rawQuery($sql);
        $data['data_list'] = $query->result();
    	$this->load->view('Laporan_fefo/cetak', $data);
    }
    function data() {
        $requestData= $_REQUEST;
        $columns = array(
            0   =>  '#',
            1   =>  'nama_barang',
            2   =>  'no_batch',
            3   =>  'stok_akhir',
            4   =>  'expired_date',
            // 5   =>  'aksi'
        );
        $sql = "SELECT * FROM m_barang WHERE deleted = 1";
        $query=$this->Laporanfefomodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT * ";
        $sql.=" FROM m_barang WHERE deleted = 1";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( nama_barang LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR no_batch LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Laporanfefomodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query=$this->Laporanfefomodel->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_barang"];
            $nestedData[]   =   $row["no_batch"];
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["stok_akhir"]."</span>";
            $nestedData[]   =   date("d/m/Y", strtotime($row["expired_date"]));

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
  }
