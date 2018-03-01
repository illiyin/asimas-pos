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
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Perintahproduksimodel->insert($dataInsert, 't_log');
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
      $this->load->view('Produksi_perintah/perintahDetail');
      // $this->load->view('Produksi_perintah/view', $data);
    }
    function add(){
      $params = $this->input->post();
      $bahanBaku = $params['bahan_baku'];

      $dataBahanBaku = json_decode($bahanBaku, true);

      if( count($dataBahanBaku) > 0) {
        $bahan_baku = null;
        foreach($dataBahanBaku as $num => $row) {
          $bahan_baku[] = array(
              'num' => $row['num']
            );
        }
      }
      // echo json_encode($data);
      echo json_encode($params);
    }
}
