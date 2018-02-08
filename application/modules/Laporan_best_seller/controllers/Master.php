<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Laporan_best_seller/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Laporanbestmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Laporanbestmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $sql = "SELECT C.*, SUM(B.jumlah) AS jumlah, SUM(B.total_harga) AS total_harga FROM t_order A, t_order_detail B, m_produk C WHERE A.deleted = 1 AND A.status = 3 AND A.id = B.id_order AND B.id_produk = C.id GROUP BY B.id_produk ORDER BY jumlah DESC LIMIT 10";
        $data['list'] = json_encode($this->Laporanbestmodel->rawQuery($sql)->result());
        $data['data_grafik'] = json_encode($this->data_range_tanggal());
    	$this->load->view('Laporan_best_seller/view', $data);
    }

    function data(){
        $requestData= $_REQUEST;
        $columns = array( 
            0   =>  '#', 
            1   =>  'nama', 
            2   =>  'jumlah',
            // 3   =>  'total_order',
            // 6   =>  'aksi'
        );
        $sql = "SELECT C.*, SUM(B.jumlah) AS jumlah, SUM(B.total_harga) AS total_harga FROM t_order A, t_order_detail B, m_produk C WHERE A.deleted = 1 AND A.status = 3 AND A.id = B.id_order AND B.id_produk = C.id";
        $query=$this->Laporanbestmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( C.nama LIKE '%".$requestData['search']['value']."%' ) "; 
        }

        //Date range filtering
        if(!empty($requestData['start_date']) AND !empty($requestData['end_date'])) {
            $sql.=" AND ( DATE(A.date_add) >= '".date("Y-m-d", strtotime($requestData['start_date']))."' "
                ."AND DATE(A.date_add) <= '".date("Y-m-d", strtotime($requestData['end_date']))."')";
        }
        // echo $sql;
        // die();

        $query=$this->Laporanbestmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" GROUP BY B.id_produk ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $query=$this->Laporanbestmodel->rawQuery($sql);
        
        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array(); 
            $nestedData[]   =   "<span style='display:block' class='text-center'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama"];
            $nestedData[]   =   "<span style='display:block' class='text-center'>".$row["jumlah"]."</span>";
            $nestedData[]   =   "<span style='display:block' class='text-center money'>".$row["total_harga"]."</span>";
            // $nestedData[]   .=   '<td class="text-center"><div class="btn-group" >'
            //     .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
            //     .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$row["id"].')"><i class="fa fa-pencil"></i></a>'
            //    .'</div>'
            // .'</td>';
            
            $data[] = $nestedData; $i++;
        }
        $json_data = array(
                    "draw"            => intval( $requestData['draw'] ),
                    "recordsTotal"    => intval( $totalData ),
                    "recordsFiltered" => intval( $totalFiltered ),
                    "data"            => $data
                    );
        echo json_encode($json_data);
    }

    function chart_data() {
        $response = array();
        $params = $this->input->post();
        $response = $this->data_range_tanggal($params['start_date'], $params['end_date']);
        /*switch ($params['filter']) {
            case 'hari': //per hari
                $response = $this->data_per_hari();
                break;
            case 'bulan': //per bulan
                $response = $this->data_per_bulan();
                break;
            case 'tahun': //per tahun
                $response = $this->data_per_tahun();
                break;
            default:
                $response = $this->data_per_hari();
                break;
        }*/
        echo json_encode($response);
    }
	
    private function data_range_tanggal($start_date=0, $end_date=0) {
        $nama_produk = array(); $jumlah_produk = array(); $total_produk = array();
        if(empty($start_date)) {
            $start_date = date("Y-m").'-01';
        }
        if(empty($end_date)) {
            $end_date = date("Y-m-d");
        }
        
        $sql = "SELECT C.*, SUM(B.jumlah) AS jumlah, SUM(B.total_harga) AS total_harga FROM t_order A, t_order_detail B, m_produk C WHERE A.deleted = 1 AND A.status = 3 AND A.id = B.id_order AND B.id_produk = C.id AND (DATE(A.date_add) >= '".$start_date."' AND DATE(A.date_add) <= '".$end_date."') GROUP BY B.id_produk ORDER BY jumlah DESC LIMIT 10";
        $result = $this->Laporanbestmodel->rawQuery($sql)->result();

        foreach ($result as $produk) {
            $nama_produk[] = $produk->nama;
            $jumlah_produk[] = (int) $produk->jumlah;
            $total_produk[] = (int) $produk->total_harga;
        }
        // echo "<pre>";
        // echo "---RESULT---"; print_r($result);
        // echo "---nama_produk---"; print_r($nama_produk);
        // echo "---jumlah_produk---"; print_r($jumlah_produk);
        // echo "---total_produk---"; print_r($total_produk);
        // echo "</pre>"; die();
        $response = array( 'data_per' => $nama_produk, 'jumlah_produk' => $jumlah_produk, 'total_produk' => $total_produk );
        return $response;
    }

    /*private function data_per_bulan() {
        $cur_month = date("m"); $cur_year = date("Y");
        $num_of_months = 12;
        $months = array(); $dates = array(); $jumlah_pembelian = array(); $total_pembelian = array();
        
        $sql = "SELECT MONTH(date_add) AS month, COUNT(id) AS jumlah_pembelian, SUM(total_harga_beli) AS total_pembelian FROM t_beli WHERE deleted = 1 AND (YEAR(date_add) = '".$cur_year."') GROUP BY MONTH(date_add)";
        $result = $this->Laporanbestmodel->rawQuery($sql)->result();

        for ($m = 1; $m <= $num_of_months; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            $months[] = $month;
        }

        for ($i = 0; $i < $num_of_months; $i++) {
            $jumlah_pembelian[$i] = (int) 0;
            $total_pembelian[$i] = (int) 0;
            foreach ($result as $res) {
                if(($i+1) == $res->month) {
                    $jumlah_pembelian[$i] = (int)$res->jumlah_pembelian;
                    $total_pembelian[$i] = (int)$res->total_pembelian;
                }
            }
        }
        // echo "<pre>";
        // echo "---RESULT---"; print_r($result);
        // echo "---months---"; print_r($months);
        // echo "---jumlah_pembelian---"; print_r($jumlah_pembelian);
        // echo "---total_pembelian---"; print_r($total_pembelian);
        // echo "</pre>"; die();
        $response = array( 'data_per' => $months, 'dates' => $dates, 'jumlah_pembelian' => $jumlah_pembelian, 'total_pembelian' => $total_pembelian );
        return $response;
    }

    private function data_per_tahun() {
        $cur_year = date("Y");
        $num_of_years = 6;
        $years = array(); $dates = array(); $jumlah_pembelian = array(); $total_pembelian = array();
        
        $sql = "SELECT YEAR(date_add) AS year, COUNT(id) AS jumlah_pembelian, SUM(total_harga_beli) AS total_pembelian FROM t_beli WHERE deleted = 1 AND (YEAR(date_add) = '".$cur_year."' OR YEAR(date_add) = '".($cur_year-1)."' OR YEAR(date_add) = '".($cur_year-2)."' OR YEAR(date_add) = '".($cur_year-3)."' OR YEAR(date_add) = '".($cur_year-4)."' OR YEAR(date_add) = '".($cur_year-5)."') GROUP BY YEAR(date_add)";
        $result = $this->Laporanbestmodel->rawQuery($sql)->result();

        for ($y = $num_of_years; $y > 0; $y--) {
            $years[] = ($cur_year+1) - $y;
        }

        $i = 0;
        foreach ($years as $year) {
            $jumlah_pembelian[$i] = (int) 0;
            $total_pembelian[$i] = (int) 0;
            foreach ($result as $res) {
                if($year == $res->year) {
                    $jumlah_pembelian[$i] = (int)$res->jumlah_pembelian;
                    $total_pembelian[$i] = (int)$res->total_pembelian;
                }
            } $i++;
        }
        // echo "<pre>";
        // echo "---RESULT---"; print_r($result);
        // echo "---years---"; print_r($years);
        // echo "---jumlah_pembelian---"; print_r($jumlah_pembelian);
        // echo "---total_pembelian---"; print_r($total_pembelian);
        // echo "</pre>"; die();
        $response = array( 'data_per' => $years, 'dates' => $dates, 'jumlah_pembelian' => $jumlah_pembelian, 'total_pembelian' => $total_pembelian );
        return $response;
    }*/
   
    
}