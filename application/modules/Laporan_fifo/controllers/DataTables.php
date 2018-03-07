<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataTables extends MX_Controller {

	function __construct() {
      parent::__construct();
      $this->load->model('Laporanfifomodel');
  }
  function barang() {
  	$requestData= $_REQUEST;
    $sql = "SELECT * FROM m_barang WHERE deleted = 1";
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalData = $query->num_rows();
    $sql = "SELECT * ";
    $sql.=" FROM m_barang WHERE deleted = 1";
    if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( nama_barang LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR no_batch LIKE '%".$requestData['search']['value']."%' )";
    }
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalFiltered = $query->num_rows();

    $sql .= " ORDER BY date_add DESC";
    $query=$this->Laporanfifomodel->rawQuery($sql);

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
  function bahan() {
  	$requestData= $_REQUEST;
    $sql = "SELECT 
						m_bahan.nama AS nama_bahan,
						m_bahan_kategori.nama AS kategori_bahan,
						m_bahan.tgl_datang AS tanggal_datang
						FROM m_bahan, m_bahan_kategori
						WHERE m_bahan.id_kategori_bahan = m_bahan_kategori.id AND m_bahan.deleted = 1";
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalData = $query->num_rows();
    $sql = "SELECT "; 
		$sql .= "m_bahan.nama AS nama_bahan,
						m_bahan_kategori.nama AS kategori_bahan,
						m_bahan.tgl_datang AS tanggal_datang
						FROM m_bahan, m_bahan_kategori
						WHERE m_bahan.id_kategori_bahan = m_bahan_kategori.id AND m_bahan.deleted = 1";
    if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( m_bahan.nama LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR m_bahan_kategori.nama LIKE '%".$requestData['search']['value']."%' )";
    }
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalFiltered = $query->num_rows();

    $sql .= " ORDER BY m_bahan.tgl_datang DESC";
    $query=$this->Laporanfifomodel->rawQuery($sql);

    $data = array(); $i=0;
    foreach ($query->result_array() as $row) {
        $nestedData     =   array();
        $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
        $nestedData[]   =   $row["nama_bahan"];
        $nestedData[]   =   $row["kategori_bahan"];
        $nestedData[]   =   date("d/m/Y", strtotime($row["tanggal_datang"]));

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
  function realsupplier() {
    $requestData= $_REQUEST;
    $sql = "SELECT id_bahan, id_supplier FROM tt_gudang_masuk GROUP BY id_bahan, id_supplier";
    $query = $this->Laporanfifomodel->rawQuery($sql);
    $totalData = $query->num_rows();

    $tmpdata = null;
    foreach($query->result() as $row) {
      $bahan = $this->Laporanfifomodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
      $supplier = $this->Laporanfifomodel->select(array('id' => $row->id_supplier), 'm_supplier')->row();

        $temporary = array(
            'nama_bahan' => $bahan->nama,
            'nama_supplier' => $supplier->nama,
            'alamat' => $supplier->alamat,
            'no_telp' => $supplier->no_telp,
            'email' => $supplier->email
        );
        
    //   if( !empty($requestData['search']['value']) ) {
    //     $sql.=" AND ( nama LIKE '%".$requestData['search']['value']."%' ";
    //     $sql.=" OR alamat LIKE '%".$requestData['search']['value']."%' ";
    //     $sql.=" OR no_telp LIKE '%".$requestData['search']['value']."%' ";
    //     $sql.=" OR email LIKE '%".$requestData['search']['value']."%' )";
    // }
      $tmpdata[] = $temporary;
    }
    

    $data = array(); $i=0;
    foreach ($tmpdata as $row) {
        $nestedData     =   array();
        $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
        $nestedData[]   =   $row["nama_supplier"];
        $nestedData[]   =   $row["nama_bahan"];
        $nestedData[]   =   $row["alamat"];
        $nestedData[]   =   $row["no_telp"];
        $nestedData[]   =   $row["email"];

        $data[] = $nestedData; $i++;
    }
    $totalData = count($data);
    $json_data = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval( $totalData ),
                "recordsFiltered" => intval( 0 ),
                "data"            => $data,
                );
    echo json_encode($json_data);
  }
  function supplier() {
  	$requestData= $_REQUEST;
    $sql = "SELECT * FROM m_supplier WHERE deleted = 1";
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalData = $query->num_rows();
    $sql = "SELECT * ";
    $sql.=" FROM m_supplier WHERE deleted = 1";
    if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( nama LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR alamat LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR no_telp LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR email LIKE '%".$requestData['search']['value']."%' )";
    }
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalFiltered = $query->num_rows();

    $sql .= " ORDER BY date_add DESC";
    $query=$this->Laporanfifomodel->rawQuery($sql);

    $data = array(); $i=0;
    foreach ($query->result_array() as $row) {
        $nestedData     =   array();
        $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
        $nestedData[]   =   $row["nama"];
        $nestedData[]   =   $row["alamat"];
        $nestedData[]   =   $row["no_telp"];
        $nestedData[]   =   $row["email"];

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
  function produsen() {
  	$requestData= $_REQUEST;
    $sql = "SELECT * FROM m_produsen WHERE deleted = 1";
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalData = $query->num_rows();
    $sql = "SELECT * ";
    $sql.=" FROM m_produsen WHERE deleted = 1";
    if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( nama LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR alamat LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR no_telp LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR email LIKE '%".$requestData['search']['value']."%' )";
    }
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalFiltered = $query->num_rows();

    $sql .= " ORDER BY date_add DESC";
    $query=$this->Laporanfifomodel->rawQuery($sql);

    $data = array(); $i=0;
    foreach ($query->result_array() as $row) {
        $nestedData     =   array();
        $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
        $nestedData[]   =   $row["nama"];
        $nestedData[]   =   $row["alamat"];
        $nestedData[]   =   $row["no_telp"];
        $nestedData[]   =   $row["email"];

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
  function distributor() {
  	$requestData= $_REQUEST;
    $sql = "SELECT * FROM m_distributor WHERE deleted = 1";
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalData = $query->num_rows();
    $sql = "SELECT * ";
    $sql.=" FROM m_distributor WHERE deleted = 1";
    if( !empty($requestData['search']['value']) ) {
        $sql.=" AND ( nama LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR alamat LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR no_telp LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR email LIKE '%".$requestData['search']['value']."%' )";
    }
    $query=$this->Laporanfifomodel->rawQuery($sql);
    $totalFiltered = $query->num_rows();

    $sql .= " ORDER BY date_add DESC";
    $query=$this->Laporanfifomodel->rawQuery($sql);

    $data = array(); $i=0;
    foreach ($query->result_array() as $row) {
        $nestedData     =   array();
        $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
        $nestedData[]   =   $row["nama"];
        $nestedData[]   =   $row["alamat"];
        $nestedData[]   =   $row["no_telp"];
        $nestedData[]   =   $row["email"];

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
  function test(){
    $sql = "SELECT id_bahan, id_supplier FROM tt_gudang_masuk GROUP BY id_bahan, id_supplier";
    $query = $this->Laporanfifomodel->rawQuery($sql)->result();

    $data = null;
    foreach($query as $row) {
      $bahan = $this->Laporanfifomodel->select(array('id' => $row->id_bahan), 'm_bahan')->row();
      $supplier = $this->Laporanfifomodel->select(array('id' => $row->id_supplier), 'm_supplier')->row();

      $data[] = array(
            'nama_bahan' => $bahan->nama,
            'nama_supplier' => $supplier->nama,
            'alamat' => $supplier->alamat,
            'no_telp' => $supplier->no_telp,
            'email' => $supplier->email
        );
    }

    echo json_encode($data);
  }

}

/* End of file DataTables.php */
/* Location: ./application/modules/Laporan_fifo/controllers/DataTables.php */