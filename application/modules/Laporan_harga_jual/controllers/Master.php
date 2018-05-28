<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_harga_jual/";
    private $fungsi = "";
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanhargajualmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Laporanhargajualmodel->insert($dataInsert, 't_log');
    }
    function index(){
    	$this->load->view('Laporan_harga_jual/view');
    }
    function cetak(){
        $sql = "SELECT
                bahan.nama AS nama_bahan, kategori.nama AS nama_kategori,
                SUM(gk.harga_penjualan / gudang.jumlah_keluar) / COUNT(*) AS total
                FROM m_bahan bahan, tt_gudang_keluar gk, m_bahan_kategori kategori, tt_gudang gudang
                WHERE gudang.id_bahan = bahan.id AND bahan.id_kategori_bahan = kategori.id
                AND gudang.id_gudang = gk.id";
        $sql .= " GROUP BY gk.id_bahan";
        $sql .= " ORDER BY gk.date_added DESC";
        $query=$this->Laporanhargajualmodel->rawQuery($sql);
        $data['data_list'] = $query->result();
    	$this->load->view('Laporan_harga_jual/cetak', $data);
    }
    function data() {
        $requestData = $_REQUEST;
        $sql = "SELECT * FROM m_bahan WHERE deleted = 1";
        $query=$this->Laporanhargajualmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        
        // Gudang Masuk in tt_gudang FILTERED
        $sql = "SELECT id_bahan FROM tt_gudang WHERE type = 1 GROUP BY id_bahan";
        $gudang = $this->Laporanhargajualmodel->rawQuery($sql);
        $rowGudang = $gudang->num_rows();
        $availBahan = null;
        foreach($gudang->result() as $index => $gudang) { $availBahan[] = $gudang->id_bahan; }

        $data = array(); $i = 0;
        foreach($query->result() as $bahan) {
            $sql = "SELECT bahan.*, kategori.nama AS nama_kategori 
                    FROM m_bahan bahan, m_bahan_kategori kategori
                    WHERE bahan.id = {$bahan->id} AND bahan.id_kategori_bahan = kategori.id";
            $result = $this->Laporanhargajualmodel->rawQuery($sql)->row();
            if(in_array($bahan->id, $availBahan)) {
                $sql = "SELECT gudang.id, gudang.id_bahan, bahan.nama,
                        tbahan.jumlah_masuk, tbahan.jumlah_keluar,
                        tbahan.saldo_bulan_sekarang AS stok_akhir, tbahan.saldo_bulan_kemarin AS stok_awal,
                        gudang.jumlah_masuk AS jml_masuk, gudang.stok_akhir AS jml_akhir,
                        -- gudang.stok_awal, gudang.jumlah_keluar,
                        gm.harga_pembelian
                        FROM tt_gudang gudang, tt_gudang_masuk gm, m_bahan bahan, tt_bahan tbahan
                        WHERE type = 1
                        AND gm.id = gudang.id_gudang
                        AND tbahan.id_bahan = bahan.id
                        AND gudang.id_bahan = bahan.id
                        AND gudang.id_bahan = {$bahan->id}
                        ORDER BY gudang.date_add DESC";
                $query = $this->Laporanhargajualmodel->rawQuery($sql);
                $bahanGudang = $query->row();
                $formula1 = null;
                $formula2 = null;    
                $stokAkhir = 0;
                foreach($query->result() as $index => $row) {
                    if($index == 0) {
                        $stokAkhir = $row->jml_akhir;
                    }
                    $formula1 += (($row->jml_masuk) * ($row->harga_pembelian));
                    $formula2 += $row->jml_masuk;
                }

                $rata = ($formula1) / ($formula2);
                $rataHarga = toRupiah(($rata) * ($stokAkhir));
            }

            $isAvail = in_array($bahan->id, $availBahan) ? true : false;
            
            $nestedData = array();
            $nestedData[] = "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[] = $result->nama;
            // $nestedData[] = $result->nama_kategori;
            $nestedData[] = "<span class='text-center' style='display:block;'>".($isAvail ? $bahanGudang->stok_awal : 0)."</span>";
            $nestedData[] = "<span class='text-center' style='display:block;'>".($isAvail ? $bahanGudang->jumlah_masuk : 0)."</span>";
            $nestedData[] = "<span class='text-center' style='display:block;'>".($isAvail ? $bahanGudang->jumlah_keluar : 0)."</span>";
            $nestedData[] = "<span class='text-center' style='display:block;'>".($isAvail ? $bahanGudang->stok_akhir : 0)."</span>";
            $nestedData[] = "<span class='text-center' style='display:block;'>".($isAvail ? toRupiah($rata) : toRupiah(0))."</span>";
            $nestedData[] = "<span class='text-center' style='display:block;'>".($isAvail ? $rataHarga : toRupiah(0))."</span>";

            $data[] = $nestedData; $i++;
        }        

        $json_data = array(
                    "draw"            => intval( $requestData['draw'] ),
                    "recordsTotal"    => intval( $totalData ),
                    "recordsFiltered" => 1,
                    "data"            => $data
                    );
        echo json_encode($json_data);
    }
    function testQuery(){
        $sql = "SELECT gudang.id, gudang.id_bahan, bahan.nama,
                gudang.jumlah_masuk, gudang.stok_akhir,
                gm.harga_pembelian
                FROM tt_gudang gudang, tt_gudang_masuk gm, m_bahan bahan
                WHERE type = 1
                AND gm.id = gudang.id_gudang
                AND gudang.id_bahan = bahan.id
                AND gudang.id_bahan = 1
                ORDER BY gudang.date_add DESC";
        $query = $this->Laporanhargajualmodel->rawQuery($sql);
        
        $formula1 = null;
        $formula2 = null;    
        $stokAkhir = 0;
        foreach($query->result() as $index => $row) {
            if($index == 0) {
                $stokAkhir = $row->stok_akhir;
            }
            $formula1 += (($row->jumlah_masuk) * ($row->harga_pembelian));
            $formula2 += $row->jumlah_masuk;
        }

        $rata = ($formula1) / ($formula2);
        echo toRupiah($rata).'<BR>';
        echo toRupiah(($rata) * ($stokAkhir));
    }
    // function data(){
    //     $requestData= $_REQUEST;
    //     $sql = "SELECT * FROM tt_gudang_keluar";
    //     $query=$this->Laporanhargajualmodel->rawQuery($sql);
    //     $totalData = $query->num_rows();
    //     $sql = "SELECT
    //             bahan.nama AS nama_bahan, kategori.nama AS nama_kategori,
    //             SUM(gk.harga_penjualan / gudang.jumlah_keluar) / COUNT(*) AS total
    //             FROM m_bahan bahan, tt_gudang_keluar gk, m_bahan_kategori kategori, tt_gudang gudang
    //             WHERE gudang.id_bahan = bahan.id AND bahan.id_kategori_bahan = kategori.id
    //             AND gudang.id_gudang = gk.id";
    //     if( !empty($requestData['search']['value']) ) {
    //         $sql.=" AND ( bahan.nama LIKE '%".$requestData['search']['value']."%' )";
    //         // $sql.=" OR m_bahan.nama LIKE '%".$requestData['search']['value']."%' )";
    //     }
    //     $sql .= " GROUP BY gk.id_bahan";
    //     $query=$this->Laporanhargajualmodel->rawQuery($sql);
    //     $totalFiltered = $query->num_rows();

    //     $sql .= " ORDER BY gk.date_added DESC";
    //     $query=$this->Laporanhargajualmodel->rawQuery($sql);

    //     $data = array(); $i=0;
    //     foreach ($query->result_array() as $row) {
    //         $nestedData     =   array();
    //         $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
    //         $nestedData[]   =   $row["nama_bahan"];
    //         $nestedData[]   =   $row["nama_kategori"];
    //         $nestedData[]   =   toRupiah($row['total']);

    //         $data[] = $nestedData; $i++;
    //     }
    //     $totalData = count($data);
    //     $json_data = array(
    //                 "draw"            => intval( $requestData['draw'] ),
    //                 "recordsTotal"    => intval( $totalData ),
    //                 "recordsFiltered" => intval( $totalFiltered ),
    //                 "data"            => $data
    //                 );
    //     echo json_encode($json_data);
    // }
  }
