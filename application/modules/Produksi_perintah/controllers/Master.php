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
      $data['session_detail'] = pegawaiLevel($this->session->userdata('id_user_level'));
      $this->load->view('Produksi_perintah/view', $data);
    }
    function perintahbaru(){
      $dataCondition['deleted'] = 1;
      $data['session_detail'] = pegawaiLevel($this->session->userdata('id_user_level'));
      if($data['session_detail']->id != 5 && $data['session_detail']->id != 9) redirect();
      $data['list_satuan'] = $this->Perintahproduksimodel->select($dataCondition, 'm_satuan')->result();
      $data['list_bahan'] = $this->Perintahproduksimodel->select($dataCondition, 'm_bahan')->result();
      $data['is_revisi'] = false;
      $listBahanBaku = "SELECT 
                        bahan.id, bahan.nama AS nama_bahan,  kategori.nama AS nama_kategori
                        FROM m_bahan bahan, m_bahan_kategori kategori
                        WHERE bahan.id_kategori_bahan = kategori.id AND kategori.nama LIKE '%bahan baku%' AND bahan.deleted = 1";
      $listBahanKemas = "SELECT 
                        bahan.id, bahan.nama AS nama_bahan,  kategori.nama AS nama_kategori
                        FROM m_bahan bahan, m_bahan_kategori kategori
                        WHERE bahan.id_kategori_bahan = kategori.id AND kategori.nama LIKE '%bahan kemas%' AND bahan.deleted = 1";
      $data['bahan_baku'] = $this->Perintahproduksimodel->rawQuery($listBahanBaku)->result();
      $data['bahan_kemas'] = $this->Perintahproduksimodel->rawQuery($listBahanKemas)->result();
      $this->load->view('Produksi_perintah/perintahBaru', $data);
    }
    function perintahrevisi(){
      $dataCondition['deleted'] = 1;
      $data['session_detail'] = pegawaiLevel($this->session->userdata('id_user_level'));
      if($data['session_detail']->id != 5 && $data['session_detail']->id != 9) redirect();
      $data['list_dokumen']     = $this->Perintahproduksimodel->select($dataCondition, 'm_perintah_produksi', 'revisi', 'DESC', 'no_dokumen')->result();
      $data['list_satuan'] = $this->Perintahproduksimodel->select($dataCondition, 'm_satuan')->result();
      $data['list_bahan'] = $this->Perintahproduksimodel->select($dataCondition, 'm_bahan')->result();
      $data['is_revisi'] = true;
      $this->load->view('Produksi_perintah/perintahRevisi', $data);
    }
    function cetak(){
      $uid = $this->uri->segment(4);
      if(!$uid) redirect('index/modul/Produksi_perintah-master-index');
      $id = base64_url_decode($uid);
      // Perintah Produksi
      $perintahProduksi = $this->Perintahproduksimodel->select(array('id' => $id), 'm_perintah_produksi')->row();
      // Bahan Baku & Penimbangan Aktual
      $bahanBaku = $this->Perintahproduksimodel->select(array('id_perintah_produksi' => $id), 'pp_bahan_baku')->result();
      $dataBahanBaku = null;
      foreach($bahanBaku as $row) {
        $bahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
        $satuanBatch = $this->Perintahproduksimodel->select(array('id' => $row->satuan_batch), 'm_satuan')->row();
        $satuanKaplet = $this->Perintahproduksimodel->select(array('id' => $row->satuan_kaplet), 'm_satuan')->row();
        $dataBahanBaku[] = array(
            'nama_bahan' => $bahan->nama,
            'per_kaplet' => $row->per_kaplet,
            'satuan_kaplet' => $satuanKaplet->nama,
            'per_batch' => $row->per_batch,
            'satuan_batch' => $satuanBatch->nama,
            'jumlah_lot' => $row->jumlah_lot,
            'jumlah_perlot' => $row->jumlah_perlot
          );
      }
      // Bahan Kemas
      $bahanKemas = $this->Perintahproduksimodel->select(array('id_perintah_produksi' => $id), 'pp_bahan_kemas')->result();
      $dataBahanKemas = null;
      foreach($bahanKemas as $row) {
        $bahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
        $satuan = $this->Perintahproduksimodel->select(array('id' => $row->satuan), 'm_satuan')->row();
        $dataBahanKemas[] = array(
            'nama_bahan' => $bahan->nama,
            'jumlah' => $row->jumlah,
            'satuan' => $satuan->nama,
            'aktual' => $row->aktual
          );
      }
      // Data
      $data['perintah_produksi'] = $perintahProduksi;
      $data['bahan_baku'] = $dataBahanBaku;
      $data['bahan_kemas'] = $dataBahanKemas;
      $this->load->view('Produksi_perintah/perintahCetak', $data);
      // $this->load->view('Produksi_perintah/view', $data);
    }
    function detail(){
      $uid = $this->uri->segment(4);
      if(!$uid) redirect('index/modul/Produksi_perintah-master-index');
      $id = base64_url_decode($uid);
      // Perintah Produksi
      $perintahProduksi = $this->Perintahproduksimodel->select(array('id' => $id), 'm_perintah_produksi')->row();
      // Bahan Baku & Penimbangan Aktual
      $bahanBaku = $this->Perintahproduksimodel->select(array('id_perintah_produksi' => $id), 'pp_bahan_baku')->result();
      $dataBahanBaku = null;
      foreach($bahanBaku as $row) {
        $bahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
        $satuanBatch = $this->Perintahproduksimodel->select(array('id' => $row->satuan_batch), 'm_satuan')->row();
        $satuanKaplet = $this->Perintahproduksimodel->select(array('id' => $row->satuan_kaplet), 'm_satuan')->row();
        $dataBahanBaku[] = array(
            'nama_bahan' => $bahan->nama,
            'per_kaplet' => $row->per_kaplet,
            'satuan_kaplet' => $satuanKaplet->nama,
            'per_batch' => $row->per_batch,
            'satuan_batch' => $satuanBatch->nama,
            'jumlah_lot' => $row->jumlah_lot,
            'jumlah_perlot' => $row->jumlah_perlot
          );
      }
      // Bahan Kemas
      $bahanKemas = $this->Perintahproduksimodel->select(array('id_perintah_produksi' => $id), 'pp_bahan_kemas')->result();
      $dataBahanKemas = null;
      foreach($bahanKemas as $row) {
        $bahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
        $satuan = $this->Perintahproduksimodel->select(array('id' => $row->satuan), 'm_satuan')->row();
        $dataBahanKemas[] = array(
            'nama_bahan' => $bahan->nama,
            'jumlah' => $row->jumlah,
            'satuan' => $satuan->nama,
            'aktual' => $row->aktual
          );
      }
      // Data
      $data['perintah_produksi'] = $perintahProduksi;
      $data['bahan_baku'] = $dataBahanBaku;
      $data['bahan_kemas'] = $dataBahanKemas;
      $data['session_detail'] = pegawaiLevel($this->session->userdata('id_user_level'));
      $this->load->view('Produksi_perintah/perintahDetail', $data);
      // $this->load->view('Produksi_perintah/view', $data);
    }
    function edit() {
      $uid = $this->uri->segment(4);
      if(!$uid) redirect('index/modul/Produksi_perintah-master-index');
      $id = base64_url_decode($uid);
      // Perintah Produksi
      $perintahProduksi = $this->Perintahproduksimodel->select(array('id' => $id), 'm_perintah_produksi')->row();
      // Bahan Baku & Penimbangan Aktual
      $bahanBaku = $this->Perintahproduksimodel->select(array('id_perintah_produksi' => $id), 'pp_bahan_baku')->result();
      $dataBahanBaku = null;
      foreach($bahanBaku as $row) {
        $bahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
        $satuanBatch = $this->Perintahproduksimodel->select(array('id' => $row->satuan_batch), 'm_satuan')->row();
        $satuanKaplet = $this->Perintahproduksimodel->select(array('id' => $row->satuan_kaplet), 'm_satuan')->row();
        $dataBahanBaku[] = array(
            'id_bahan' => $bahan->id,
            'nama_bahan' => $bahan->nama,
            'per_kaplet' => $row->per_kaplet,
            'id_satuan_kaplet' => $satuanKaplet->id,
            'satuan_kaplet' => $satuanKaplet->nama,
            'per_batch' => $row->per_batch,
            'id_satuan_batch' => $satuanBatch->id,
            'satuan_batch' => $satuanBatch->nama,
            'jumlah_lot' => $row->jumlah_lot,
            'jumlah_perlot' => $row->jumlah_perlot
          );
      }
      // Bahan Kemas
      $bahanKemas = $this->Perintahproduksimodel->select(array('id_perintah_produksi' => $id), 'pp_bahan_kemas')->result();
      $dataBahanKemas = null;
      foreach($bahanKemas as $row) {
        $bahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
        $satuan = $this->Perintahproduksimodel->select(array('id' => $row->satuan), 'm_satuan')->row();
        $dataBahanKemas[] = array(
            'id_bahan' => $bahan->id,
            'nama_bahan' => $bahan->nama,
            'jumlah' => $row->jumlah,
            'satuan' => $satuan->nama,
            'aktual' => $row->aktual
          );
      }
      // Data
      $data['perintah_produksi'] = $perintahProduksi;
      $data['list_bahan_baku'] = $dataBahanBaku;
      $data['list_bahan_kemas'] = $dataBahanKemas;
      $data['session_detail'] = pegawaiLevel($this->session->userdata('id_user_level'));
      $dataCondition['deleted'] = 1;
      $data['list_satuan'] = $this->Perintahproduksimodel->select($dataCondition, 'm_satuan')->result();
      $data['list_bahan'] = $this->Perintahproduksimodel->select($dataCondition, 'm_bahan')->result();
      $listBahanBaku = "SELECT 
                        bahan.id, bahan.nama AS nama_bahan,  kategori.nama AS nama_kategori
                        FROM m_bahan bahan, m_bahan_kategori kategori
                        WHERE bahan.id_kategori_bahan = kategori.id AND kategori.nama LIKE '%bahan baku%' AND bahan.deleted = 1";
      $listBahanKemas = "SELECT 
                        bahan.id, bahan.nama AS nama_bahan,  kategori.nama AS nama_kategori
                        FROM m_bahan bahan, m_bahan_kategori kategori
                        WHERE bahan.id_kategori_bahan = kategori.id AND kategori.nama LIKE '%bahan kemas%' AND bahan.deleted = 1";
      $data['bahan_baku'] = $this->Perintahproduksimodel->rawQuery($listBahanBaku)->result();
      $data['bahan_kemas'] = $this->Perintahproduksimodel->rawQuery($listBahanKemas)->result();
      $this->load->view('Produksi_perintah/perintahEdit', $data);
    }
    function addData(){
      $params = $this->input->post();
      // R&D
      $tanggalEfektif = $this->tanggalExplode(@$params['tanggal_efektif']);
      $dataInsert['no_dokumen'] = $params['no_dokumen'];
      $dataInsert['tanggal_efektif'] = $params['tanggal_efektif'] ? $tanggalEfektif : date('Y-m-d');
      $dataInsert['nama_produk']  = $params['nama_produk'];
      $dataInsert['besar_batch'] = $params['besar_batch'];
      $dataInsert['revisi'] = 0;
      $dataInsert['date_added'] = date("Y-m-d H:i:s");
      $dataInsert['added_by'] = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
      $dataInsert['last_modified'] = date('Y-m-d H:i:s');
      $dataInsert['modified_by']  = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
      $id_perintah_produksi = $this->Perintahproduksimodel->insert_id($dataInsert, 'm_perintah_produksi');
      
      // Data Bahan Baku & Penimbangan Aktual
      $bahanBaku = $params['bahan_baku'];
      $dataBahanBaku = json_decode($bahanBaku, true);
      $bahan_baku = null;

      if( count($dataBahanBaku) > 0) {
        foreach($dataBahanBaku as $num => $row) {
          $bahan_baku[] = array(
              'id_perintah_produksi' => $id_perintah_produksi,
              'id_bahan' => $row['id_bahan'],
              'per_kaplet' => $row['per_kaplet'],
              'satuan_kaplet' => is_numeric($row['satuan_kaplet']) ? $row['satuan_kaplet'] : $row['id_satuan_kaplet'],
              'per_batch' => $row['per_batch'],
              'satuan_batch' => is_numeric($row['satuan_batch']) ? $row['satuan_batch'] : $row['id_satuan_batch'],
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
              'id_perintah_produksi' => $id_perintah_produksi,
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

      $result = array(
          'status' => 1,
          'message' => "Berhasil menambah dokumen baru perintah produksi",
          'list' => $params
        );
      
      echo json_encode($result);
    }
    function editData(){
      $params = $this->input->post();

      $session_detail = pegawaiLevel($this->session->userdata('id_user_level'));
      if($session_detail->id == 9) {
        // R&D
        $dataCondition['id'] = $params['id'];
        // Delete all data 
        $this->Perintahproduksimodel->delete(array('id_perintah_produksi' => $params['id']), 'pp_bahan_baku');
        $this->Perintahproduksimodel->delete(array('id_perintah_produksi' => $params['id']), 'pp_bahan_kemas');
        $perintahProduksi = $this->Perintahproduksimodel->select($dataCondition, 'm_perintah_produksi')->row();
        $tanggalEfektif = $this->tanggalExplode(@$params['tanggal_efektif']);
        $dataUpdate['no_dokumen'] = $params['no_dokumen'] ? $params['no_dokumen'] : $perintahProduksi->no_dokumen;
        $dataUpdate['tanggal_efektif'] = $params['tanggal_efektif'] ? $tanggalEfektif : $perintahProduksi->tanggal_efektif;
        $dataUpdate['nama_produk']  = $params['nama_produk'] ? $params['nama_produk'] : $perintahProduksi->nama_produk;
        $dataUpdate['besar_batch'] = $params['besar_batch'] ? $params['besar_batch'] : $perintahProduksi->besar_batch;
        $dataUpdate['last_modified'] = date('Y-m-d H:i:s');
        $dataUpdate['modified_by']  = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $this->Perintahproduksimodel->update($dataCondition, $dataUpdate, 'm_perintah_produksi');
        
        // Data Bahan Baku & Penimbangan Aktual
        $bahanBaku = $params['bahan_baku'];
        $dataBahanBaku = json_decode($bahanBaku, true);
        $bahan_baku = null;

        if( count($dataBahanBaku) > 0) {
          foreach($dataBahanBaku as $num => $row) {
            $bahan_baku[] = array(
                'id_perintah_produksi' => $params['id'],
                'id_bahan' => $row['id_bahan'],
                'per_kaplet' => $row['per_kaplet'],
                'satuan_kaplet' => is_numeric($row['satuan_kaplet']) ? $row['satuan_kaplet'] : $row['id_satuan_kaplet'],
                'per_batch' => $row['per_batch'],
                'satuan_batch' => is_numeric($row['satuan_batch']) ? $row['satuan_batch'] : $row['id_satuan_batch'],
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
                'id_perintah_produksi' => $params['id'],
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

        $result = array(
            'status' => 1,
            'message' => "Berhasil mengubah dokumen baru perintah produksi",
            'list' => $params
          );
      } 
      else{
        // PPIC
        $dataCondition['id'] = $params['id'];
        $perintahProduksi = $this->Perintahproduksimodel->select($dataCondition, 'm_perintah_produksi')->row();
        $expiredDate = $this->tanggalExplode(@$params['expired_date']);
        $dataUpdate['no_perintah'] = $params['no_pp'] ? $params['no_pp'] : $perintahProduksi->no_perintah;
        $dataUpdate['no_sales_order'] = $params['no_so'] ? $params['no_so'] : $perintahProduksi->no_sales_order;
        $dataUpdate['estimasi_proses'] = $params['estimasi'] ? $params['estimasi'] : $perintahProduksi->estimasi_proses;
        $dataUpdate['kode_produksi'] = $params['kode_produksi'] ? $params['kode_produksi'] : $perintahProduksi->kode_produksi;
        $dataUpdate['expired_date'] = $params['expired_date'] ? $expiredDate : $perintahProduksi->expired_date;
        $dataUpdate['last_modified'] = date('Y-m-d H:i:s');
        $dataUpdate['modified_by']  = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $this->Perintahproduksimodel->update($dataCondition, $dataUpdate, 'm_perintah_produksi');

        $result = array(
            'status' => 1,
            'message' => "Berhasil mengubah dokumen baru perintah produksi",
            'list' => $params
          );
      }

      // $result = array('params' => $params, 'session' => $session_detail);
      echo json_encode($result);
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
      if(!empty($requestData['columns'][4]['search']['value']) && $requestData['columns'][4]['search']['value'] != '') {
        $filter = $requestData['columns'][4]['search']['value'] == 'notapproved' ? 0 : 1;
        $sql.= " AND status = ".$filter;
      }

      $query=$this->Perintahproduksimodel->rawQuery($sql);
      $totalFiltered = $query->num_rows();

      $sql .= " ORDER BY date_added DESC";
      $query=$this->Perintahproduksimodel->rawQuery($sql);

      $data = array(); $i=0;
      foreach ($query->result_array() as $row) {
          $nestedData     =   array();
          $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
          $dokumen = $row['no_dokumen'] ? '<a href="Produksi_perintah-master-detail/'.base64_url_encode($row['id']).'" title="Detail dan Setujui">'.$row["no_dokumen"].'</a>' : 'Belum disetting';
          $nestedData[]   =   $dokumen;
          $nestedData[]   =   $row["revisi"];
          $nestedData[]   =   ($row['tanggal_efektif'] == '0000-00-00'? 'Belum disetting' : date('d/m/Y', strtotime($row["tanggal_efektif"])));
          $statusValue = $row["status"] == 0 ? 'Belum Disetujui' : 'Disetujui';
          $statusFilter = $row["status"] == 0 ? 'notapproved' : 'approved';
          $nestedData[]   =   "<span data-filter='".$statusFilter."'>".$statusValue."</span>";
          $nestedData[]  .=   '<td class="text-center"><div class="btn-group">'
                .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
                .'<a class="btn btn-sm btn-default" href="Produksi_perintah-master-edit/'.base64_url_encode($row['id']).'" data-toggle="tooltip" data-placement="top" title="Ubah Data"><i class="fa fa-pencil"></i></a>'
                .'<a href="Produksi_perintah-master-cetak/'.base64_url_encode($row['id']).'" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Cetak Dokumen"><i class="fa fa-print"></i></a>'
               .'</div>'
            .'</td>';
          // Edit: Produksi_perintah-master-edit/'.base64_url_encode($row['id']).'
          $data[] = $nestedData; $i++;
      }
      $totalData = count($data);
      $json_data = array(
        //columns[4][search][value]  
          "req" => @$requestData['columns'][4]['search']['value'],
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
    function approve() {
      $params = $this->input->post();
      if(!$params) redirect();

      $dataCondition['id'] = $params['id'];
      $perintahProduksi = $this->Perintahproduksimodel->select($dataCondition, 'm_perintah_produksi')->row();
      $ppBahanBaku = $this->Perintahproduksimodel->select(array('id_perintah_produksi' => $perintahProduksi->id), 'pp_bahan_baku');
      $ppBahanKemas = $this->Perintahproduksimodel->select(array('id_perintah_produksi' => $perintahProduksi->id), 'pp_bahan_kemas');

      $dataBahanKurang = null;
      $dataBahanBaku = null;
      if($ppBahanBaku->num_rows() > 0) {
        foreach($ppBahanBaku->result() as $row) {
          $tbahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'tt_bahan')->row();
          $bahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
          $penguranganBahanBaku = ($tbahan->saldo_bulan_sekarang) - ($row->per_batch);
          $dataBahanBaku[] = array(
              'id_bahan' => $row->id_bahan,
              'saldo_bulan_sekarang' => $penguranganBahanBaku
            );

          if($penguranganBahanBaku < 0) {
            $dataBahanKurang[] = array(
               'nama_bahan' => $bahan->nama,
               'stok_kurang' => $penguranganBahanBaku,
               'type' => 'bahan_baku'
            );
          }
        }
      }

      $dataBahanKemas = null;
      if($ppBahanKemas->num_rows() > 0) {
        foreach($ppBahanKemas->result() as $row) {
          $tbahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'tt_bahan')->row();
          $bahan = $this->Perintahproduksimodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();

          $penguranganBahanKemas = ($tbahan->saldo_bulan_sekarang) - ($row->jumlah);
          $dataBahanKemas[] = array(
              'id_bahan' => $row->id_bahan,
              'saldo_bulan_sekarang' => $penguranganBahanKemas
            );

          if($penguranganBahanKemas < 0) {
            $dataBahanKurang[] = array(
               'nama_bahan' => $bahan->nama,
               'stok_kurang' => $penguranganBahanKemas,
               'type' => 'bahan_kemas'
            );
          }
        }
      }

      $statusStok = count($dataBahanKurang) > 0 ? array('list_bahan' => $dataBahanKurang) : "lanjut";

      if($statusStok == 'lanjut') {
        if(count($dataBahanBaku) > 0) $this->Perintahproduksimodel->update_batch($dataBahanBaku, 'tt_bahan', 'id_bahan');
        if(count($dataBahanKemas) > 0) $this->Perintahproduksimodel->update_batch($dataBahanKemas, 'tt_bahan', 'id_bahan');
        $dataUpdate['status'] = 1;
        $update = $this->Perintahproduksimodel->update($dataCondition, $dataUpdate, 'm_perintah_produksi');
      }

      $result = array(
          'is_ok' => $statusStok == 'lanjut' ?: 0,
          'status' => $statusStok == 'lanjut' ?: $statusStok,
          'message' => $statusStok == 'lanjut' ?'Berhasil menyetujui dokumen ini!' : null
        );
      echo json_encode($result);
    }
}
