<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Log extends MX_Controller {
    private $modul = "Log/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Logmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        // $this->_insertLog();
    }
	// function _remap()
	// {
	//       echo 'No direct access allowed';
	// }    
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Logmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$this->load->view('Log_aktivitas/view');
    }
    function data(){
		$requestData= $_REQUEST;
		$columns = array( 
			0 	=>	'namauser', 
			1 	=>	'modul', 
			2 	=> 	'fungsi',
			3 	=> 	'date_add'
		);
		$sql = " SELECT t_log.* , m_pegawai.nama as namauser";
		$sql.= " FROM t_log ";
		$sql.= " INNER JOIN m_pegawai ON t_log.id_user = m_pegawai.id ";
		$query=$this->Logmodel->rawQuery($sql);
		$totalData = $query->num_rows();
		$totalFiltered = $totalData;
		$sql.=" WHERE m_pegawai.deleted=1 ";
		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( m_pegawai.nama LIKE '%".$requestData['search']['value']."%' ";    
			$sql.=" OR t_log.modul LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_log.date_add LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" OR t_log.fungsi LIKE '%".$requestData['search']['value']."%' )";
		}
		$query=$this->Logmodel->rawQuery($sql);
		$totalFiltered = $query->num_rows();
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
		$query=$this->Logmodel->rawQuery($sql);
		$data = array();
		foreach ($query->result_array() as $row) {
			$nestedData		=	array(); 

			$nestedData[] 	= 	$row["namauser"];
			$nestedData[] 	= 	$row["modul"];
			$nestedData[] 	= 	$row["fungsi"];
			$nestedData[] 	= 	$row["date_add"];
			$data[] = $nestedData;
		}
		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),
					"recordsTotal"    => intval( $totalData ),
					"recordsFiltered" => intval( $totalFiltered ),
					"data"            => $data
					);
		echo json_encode($json_data);
    }
}