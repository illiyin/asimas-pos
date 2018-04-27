    <?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_stok_bahan_baku/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanstokbahanbaku');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->session_detail = pegawaiLevel($this->session->userdata('id_user_level'));
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Laporanstokbahanbaku->insert($dataInsert, 't_log');
    }
    function index(){
        $data['session_detail'] = $this->session_detail;
    	$this->load->view('Laporan_stok_bahan_baku/view', $data);
    }
    function cetak(){
        $sql = "SELECT
                bahan.nama AS nama_bahan , kategori.nama AS nama_kategori,
                tbahan.saldo_bulan_kemarin AS stok_awal , tbahan.saldo_bulan_sekarang AS stok_akhir, tbahan.tanggal
                FROM m_bahan bahan, m_bahan_kategori kategori , tt_bahan tbahan
                WHERE bahan.id_kategori_bahan = kategori.id AND tbahan.id_bahan = bahan.id AND kategori.nama LIKE '%bahan baku%'";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR kategori.nama LIKE '%".$requestData['search']['value']."%' )";
        }
        $query=$this->Laporanstokbahanbaku->rawQuery($sql);
        $data['data_list'] = $query->result();
    	$this->load->view('Laporan_stok_bahan_baku/cetak', $data);
    }
    function data(){
        $requestData= $_REQUEST;
        $sql = "SELECT * FROM m_bahan, tt_bahan WHERE m_bahan.deleted = 1 AND m_bahan.id = tt_bahan.id_bahan";
        $query=$this->Laporanstokbahanbaku->rawQuery($sql);
        $totalData = $query->num_rows();
        $sql = "SELECT
                bahan.nama AS nama_bahan , kategori.nama AS nama_kategori,
                tbahan.saldo_bulan_kemarin AS stok_awal , tbahan.saldo_bulan_sekarang AS stok_akhir, tbahan.tanggal
                FROM m_bahan bahan, m_bahan_kategori kategori , tt_bahan tbahan
                WHERE bahan.id_kategori_bahan = kategori.id AND tbahan.id_bahan = bahan.id AND kategori.nama LIKE '%bahan baku%'";
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR kategori.nama LIKE '%".$requestData['search']['value']."%' )";
        }
        $query = $this->Laporanstokbahanbaku->rawQuery($sql);
        $totalFiltered = $query->num_rows();
        $sql.=" ORDER BY bahan.nama";
        $sql.= " LIMIT ".$requestData['start']." ,".$requestData['length']."";
        $query=$this->Laporanstokbahanbaku->rawQuery($sql);

        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array();
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_bahan"];
            $nestedData[]   =   $row["nama_kategori"];
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["stok_awal"]."</span>";
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".$row["stok_akhir"]."</span>";
            $nestedData[]   =   date('M, Y', strtotime($row["tanggal"]));

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
