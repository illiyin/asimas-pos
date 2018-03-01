<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Produksi_perintah/";
    private $fungsi = "";
  function __construct() {
        parent::__construct();
        $this->load->model('Perintahproduksimodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $inseRtmaster['id_user'] = $id_user;
        $inseRtmaster['modul'] = $this->modul;
        $inseRtmaster['fungsi'] = $this->fungsi;
        $insertLog = $this->Perintahproduksimodel->insert($inseRtmaster, 't_log');
    }
    function index(){
      $this->load->view('Produksi_perintah/view');
      // $this->load->view('Produksi_perintah/view', $data);
    }

    function perintahbaru(){
      $dataCondition['deleted'] = 1;
      $data['list_satuan'] = $this->Perintahproduksimodel->select($dataCondition, 'm_satuan')->result();
      $data['list_bahan'] = $this->Perintahproduksimodel->select($dataCondition, 'm_bahan')->result();
      $this->load->view('Produksi_perintah/perintahBaru', $data);
      // $this->load->view('Produksi_perintah/view', $data);
    }
    function perintahrevisi(){
      $this->load->view('Produksi_perintah/perintahRevisi');
      // $this->load->view('Produksi_perintah/view', $data);
    }
    function cetak(){
      $this->load->view('Produksi_perintah/perintahCetak');
      // $this->load->view('Produksi_perintah/view', $data);
    }
    function detail(){
      $uid = $this->uri->segment(4);
      if(!$uid) redirect('index/modul/Produksi_perintah-master-index');
      $this->load->view('Produksi_perintah/perintahDetail');
      // $this->load->view('Produksi_perintah/view', $data);
    }
    function add(){
      $params = $this->input->post();
      // Custom Data
      $expiredDate = $this->tanggalExplode($params['expired_date']);
      $tanggalEfektif = $this->tanggalExplode($params['tanggal_efektif']);
      // Data Master
      $insertMaster['no_dokumen'] = $params['no_dokumen'];
      $insertMaster['revisi'] = 0;
      $insertMaster['tanggal_efektif'] = $tanggalEfektif;
      $insertMaster['no_perintah'] = $params['no_pp'];
      $insertMaster['no_sales_order'] = $params['no_so'];
      $insertMaster['estimasi_proses'] = $params['estimasi'];
      $insertMaster['nama_produk'] = $params['nama_produk'];
      $insertMaster['besar_batch'] = $params['besar_batch'];
      $insertMaster['kode_produksi'] = $params['kode_produksi'];
      $insertMaster['expired_date'] = $expiredDate;
      $insertMaster['date_added'] = date("Y-m-d H:i:s");
      $insertMaster['added_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
      $insertMaster['last_modified'] = date("Y-m-d H:i:s");
      $insertMaster['modified_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
      $insertMaster['deleted'] = 1;
      $insertId = $this->Perintahproduksimodel->insert_id($insertMaster, 'm_perintah_produksi');

      if($insertId) {
        // Data Bahan Baku & Penimbangan Aktual
        $bahanBaku = $params['bahan_baku'];
        $dataBahanBaku = json_decode($bahanBaku, true);
        $bahan_baku = null;
        $penimbangan_aktual = null;

        if( count($dataBahanBaku) > 0) {
          foreach($dataBahanBaku as $num => $row) {
            $bahan_baku[] = array(
                'id_perintah_produksi' => $insertId,
                'id_bahan' => $row['id_bahan'],
                'per_kaplet' => $row['per_kaplet'],
                'satuan_kaplet' => $row['satuan_kaplet'],
                'per_batch' => $row['per_batch'],
                'satuan_batch' => $row['satuan_batch'],
                'jumlah_lot' => $row['jumlah_lot'],
                'jumlah_perlot' => $row['jumlah_perlot'],
                'date_add' => date('Y-m-d H:i:s'),
                'added_by' => isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0,
                'modified_by' => isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0,
                'last_modified' => date('Y-m-d H:i:s'),
                'deleted' => 1
              );
          }
          $this->Perintahproduksimodel->insert_batch($bahan_baku, 'pp_bahan_baku');
        }

        // Data Bahan Kemas
        $bahanKemas = $params['bahan_kemas'];
        $dataBahanKemas = json_decode($bahanKemas, true);
        $bahan_kemas = null;

        if( count($dataBahanKemas) > 0) {
          foreach($dataBahanKemas as $num => $row) {
            $bahan_kemas[] = array(
                'id_perintah_produksi' => $insertId,
                'id_bahan' => $row['id_bahan'],
                'jumlah' => $row['jumlah'],
                'satuan' => $row['satuan'],
                'aktual' => $row['aktual'],
                'date_added' => date('Y-m-d H:i:s'),
                'added_by' => isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0,
                'deleted' => 1
              );
          }
          $this->Perintahproduksimodel->insert_batch($bahan_kemas, 'pp_bahan_kemas');
        }

        $response = array(
            'status' => 3
          );
      } else {
        $response = array(
            'status' => 1
          );
      }

      echo json_encode($response);
    }
    function data() {
      $requestData= $_REQUEST;
      $sql = "SELECT * FROM m_perintah_produksi WHERE deleted = 1";
      $query=$this->Perintahproduksimodel->rawQuery($sql);
      $totalData = $query->num_rows();
      $sql = "SELECT * ";
      $sql.=" FROM m_perintah_produksi WHERE deleted = 1";
      if( !empty($requestData['search']['value']) ) {
          $sql.=" AND ( no_dokumen LIKE '%".$requestData['search']['value']."%' )";
      }
      $query=$this->Perintahproduksimodel->rawQuery($sql);
      $totalFiltered = $query->num_rows();

      $sql .= " ORDER BY date_added DESC";
      $query=$this->Perintahproduksimodel->rawQuery($sql);

      $data = array(); $i=0;
      foreach ($query->result_array() as $row) {
          $nestedData     =   array();
          $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
          $nestedData[]   =   '<a href="Produksi_perintah-master-detail/'.base64_url_encode($row['id']).'" title="Detail dan Setujui">'.$row["no_dokumen"].'</a>';
          $nestedData[]   =   $row["revisi"];
          $nestedData[]   =   date('d/m/Y', strtotime($row["tanggal_efektif"]));
          $nestedData[]  .=   '<td class="text-center"><div class="btn-group">'
                .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
                .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$row["id"].')"><i class="fa fa-pencil"></i></a>'
                .'<a href="Produksi_perintah-master-cetak" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Cetak Dokumen"><i class="fa fa-print"></i></a>'
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
    function delete() {
      $id = $this->input->post("id");
      if($id != null){
          $dataCondition['id'] = $id;
          $dataUpdate['deleted'] = 0;
          $update = $this->Perintahproduksimodel->update($dataCondition, $dataUpdate, 'm_perintah_produksi');
          if($update){
              $dataSelect['deleted'] = 1;
              $list = $this->Perintahproduksimodel->select(array('deleted' => 0), 'm_perintah_produksi');
              echo json_encode(array('status' => '3','list' => $list));
          }else{
              echo "1";
          }
      }else{
          echo "0";
      }
    }
    private function tanggalExplode($date) {
      $x = explode("/" , $date);
      return $x[2].'-'.$x[1].'-'.$x[0];
    }
}
