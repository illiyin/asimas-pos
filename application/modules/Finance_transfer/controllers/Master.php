<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Finance_transfer/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Financetransfermodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Financetransfermodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $data['list_bank'] = json_encode($this->Financetransfermodel->select($dataSelect, 'm_bank')->result());
        $sql = "SELECT A.*, B.nama AS nama_pegawai, C.nama AS nama_bank FROM fin_transfer_harian A LEFT JOIN m_pegawai B ON A.add_by = B.id LEFT JOIN m_bank C ON A.id_bank = C.id WHERE A.deleted = 1 ORDER BY A.date_add ASC";
        $data['list'] = json_encode($this->Financetransfermodel->rawQuery($sql)->result());
    	$this->load->view('Finance_transfer/view', $data);
    }

    function data(){
        $requestData= $_REQUEST;
        $columns = array( 
            0   =>  '#', 
            1   =>  'nama_pegawai', 
            2   =>  'nama_bank',
            3   =>  'nominal',
            4   =>  'keterangan',
            5   =>  'tanggal_transfer'
            // 6   =>  'aksi'
        );
        $sql = "SELECT A.*, B.nama AS nama_pegawai, C.nama AS nama_bank FROM fin_transfer_harian A LEFT JOIN m_pegawai B ON A.add_by = B.id LEFT JOIN m_bank C ON A.id_bank = C.id WHERE A.deleted = 1";
        $query=$this->Financetransfermodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( B.nama LIKE '%".$requestData['search']['value']."%' "; 
            $sql.=" OR C.nama LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.nominal LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.keterangan LIKE '%".$requestData['search']['value']."%'";
            $sql.=" OR A.tanggal_transfer LIKE '%".$requestData['search']['value']."%' )";
        }

        //Date range filtering
        if(!empty($requestData['start_date']) AND !empty($requestData['end_date'])) {
            $sql.=" AND (A.tanggal_transfer >= '".date("Y-m-d", strtotime($requestData['start_date']))."' "
                ."AND A.tanggal_transfer <= '".date("Y-m-d", strtotime($requestData['end_date']))."')";
        }
        // echo $sql;
        // die();

        $query=$this->Financetransfermodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $query=$this->Financetransfermodel->rawQuery($sql);
        
        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array(); 
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_pegawai"];
            $nestedData[]   =   $row["nama_bank"];
            $nestedData[]   =   "<span class='pull-right'>".number_format($row["nominal"], 2, ",", ".")."</span>";
            $nestedData[]   =   $row["keterangan"];
            $nestedData[]   =   date("d F Y", strtotime($row["tanggal_transfer"]));
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
	
	function test(){
		header('Content-Type: application/json; charset=utf-8');
		$dataSelect['deleted'] = 1;
		$list = $this->Financetransfermodel->select($dataSelect, 'fin_transfer_harian')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
    function add(){
        $params = $this->input->post();

        $dataInsert['id_bank']          = $params['id_bank'];
        $dataInsert['nominal']          = $params['nominal'];
        $dataInsert['tanggal_transfer'] = date("Y-m-d", strtotime($params['tanggal_transfer']));
        $dataInsert['keterangan']       = $params['keterangan'];
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']          = 1;

        $checkData = $this->Financetransfermodel->select($dataInsert, 'fin_transfer_harian');
        if($checkData->num_rows() < 1){
            $insert = $this->Financetransfermodel->insert_id($dataInsert, 'fin_transfer_harian');
            if($insert){
                $dataSelect['deleted'] = 1;
                $sql = "SELECT A.*, B.nama AS nama_pegawai, C.nama AS nama_bank FROM fin_transfer_harian A LEFT JOIN m_pegawai B ON A.add_by = B.id LEFT JOIN m_bank C ON A.id_bank = C.id WHERE A.deleted = 1 ORDER BY A.date_add ASC";
                $list = $this->Financetransfermodel->rawQuery($sql)->result();
                echo json_encode(array('status'=>3,'list'=>$list));
            }else{
                echo json_encode(array('status' => 1));
            }
            
        }else{          
            echo json_encode(array( 'status'=>1 ));
        }
    }

	function get($id = null){   	
    	if($id != null){
    		$dataSelect['id'] = $id;
    		$selectData = $this->Financetransfermodel->select($dataSelect, 'fin_transfer_harian');
    		if($selectData->num_rows() > 0){
    			echo json_encode(
    				array(
    					'status'			=> 2,
    					'id'				=> $selectData->row()->id,
    					'nama'				=> $selectData->row()->nama,
    				));
    		}else{
    			echo json_encode(array('status' => 1));
    		}
    	}else{
    		echo json_encode(array('status' => 0));
    	}
    }
	
    function edit(){
		$params = $this->input->post();
        
        $dataCondition['id']            = $params['id'];
		$dataUpdate['id_bank'] 			= $params['id_bank'];
        $dataUpdate['nominal']          = $params['nominal'];
        $dataUpdate['tanggal_transfer'] = $params['tanggal_transfer'];
        $dataUpdate['keterangan']       = $params['keterangan'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        
		$checkData = $this->Financetransfermodel->select($dataCondition, 'fin_transfer_harian');
		if($checkData->num_rows() > 0){
			$update = $this->Financetransfermodel->update($dataCondition, $dataUpdate, 'fin_transfer_harian');

			if($update){
				$dataSelect['deleted'] = 1;
                $sql = "SELECT A.*, B.nama AS nama_pegawai, C.nama AS nama_bank FROM fin_transfer_harian A LEFT JOIN m_pegawai B ON A.add_by = B.id LEFT JOIN m_bank C ON A.id_bank = C.id WHERE A.deleted = 1 ORDER BY A.date_add ASC";
				$list = $this->Financetransfermodel->select($sql)->result();
				echo json_encode(array('status'=>'3','list'=>$list));
			}else{
				echo json_encode(array( 'status'=>'2' ));
			}
		}else{			
    		echo json_encode(array( 'status'=>'1' ));
		}
    }
    function delete(){
		$id = $this->input->post("id");
    	if($id != null){
    		$dataCondition['id'] = $id;
    		$dataUpdate['deleted'] = 0;
    		$update = $this->Financetransfermodel->update($dataCondition, $dataUpdate, 'fin_transfer_harian');
    		if($update){
    			$dataSelect['deleted'] = 1;
				$list = $this->Financetransfermodel->select($dataSelect, 'fin_transfer_harian')->result();
				echo json_encode(array('status' => '3','list' => $list));
    		}else{
    			echo "1";
    		}
    	}else{
    		echo "0";
    	}
    }
    function buttonDelete($id=null){
    	if($id!=null){
    		echo "<button class='btn btn-danger' onclick='delRow(".$id.")'>YA</button>";
    	}else{
    		echo "NOT FOUND";
    	}
    }

    function download_csv() {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "contoh_transfer_harian.csv";
        $sql = "SELECT B.nama AS pencatat, C.nama AS bank, A.nominal, A.tanggal_transfer FROM fin_transfer_harian A LEFT JOIN m_pegawai B ON A.add_by = B.id LEFT JOIN m_bank C ON A.id_bank = C.id WHERE A.deleted = 1 ORDER BY A.date_add ASC";
        $result = $this->db->query($sql);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }
    public function upload_csv()
    {   
        $input_name = 'upload_data';
        $config['upload_path'] = './files/finance';
        $config['allowed_types'] = 'csv';
        $config['overwrite'] = TRUE;
        $config['max_size'] = '500';

        $this->load->library('upload', $config);
        if ($this->upload->do_upload($input_name)) {
            $data = array(
                'upload_data' => $this->upload->data()
            );
            $file = $data['upload_data']['file_name'];

            $fileopen = fopen('files/finance/' . $file, "r");
            if ($fileopen) {
                while (($row = fgetcsv($fileopen, 2075, ",")) !== FALSE) {
                    $filearray[] = $row;
                }
                fclose($fileopen);
            }
            array_shift($filearray);

            $fields = array(
                'id_bank',
                'nominal',
                'keterangan',
                'tanggal_transfer'
            );

            $final = array();
            foreach ($filearray as $key => $value) {
                $products[] = array_combine($fields, $value);
            }

            // date_default_timezone_set('');
            $date = date("Y-m-d H:i:s");
            $sql = "SELECT id, nama FROM m_bank";
            $list_bank = $this->Financetransfermodel->rawQuery($sql)->result();
            $data = array();
            foreach ($products as $prdct) {
                foreach ($list_bank as $bank) {
                    $id_bank = (strtolower($prdct['id_bank']) == strtolower($bank->nama)) ? $bank->id : 0;
                }
                $data[] = array(
                    "id_bank"           => $id_bank,
                    "nominal"           => $prdct['nominal'],
                    "keterangan"        => $prdct['keterangan'],
                    "tanggal_transfer"  => date("Y-m-d", strtotime($prdct['tanggal_transfer'])),
                    "last_edited"       => date("Y-m-d H:i:s"),
                    "add_by"            => isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0,
                    "edited_by"         => isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0,
                    "deleted"         => 1
                );
                
            }

            $insert = $this->Financetransfermodel->insert_batch($data, 'fin_transfer_harian');
            unlink('./files/finance/' . $file);
            if($insert) {
                $dataSelect['deleted'] = 1;
                $sql = "SELECT A.*, B.nama AS nama_pegawai, C.nama AS nama_bank FROM fin_transfer_harian A LEFT JOIN m_pegawai B ON A.add_by = B.id LEFT JOIN m_bank C ON A.id_bank = C.id WHERE A.deleted = 1 ORDER BY A.date_add ASC";
                    $list = $this->Financetransfermodel->rawQuery($sql)->result();
                echo json_encode(array('status'=>'3','list'=>$list));
            }
            else {
                echo json_encode(array( 'status'=>'2' ));
            }
        }
        else { 
            echo json_encode(array( 'status'=>'1' ));
        }
    }
    
}