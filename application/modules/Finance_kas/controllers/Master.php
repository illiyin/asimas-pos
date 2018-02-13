<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
    private $modul = "Finance_kas/";
    private $fungsi = "";    
	function __construct() {
        parent::__construct();
        $this->load->model('Financekasmodel');
        $this->modul .= $this->router->fetch_class();
        $this->fungsi = $this->router->fetch_method();
        $this->_insertLog();
    }
    function _insertLog($fungsi = null){
        $id_user = $this->session->userdata('id_user');
        $dataInsert['id_user'] = $id_user;
        $dataInsert['modul'] = $this->modul;
        $dataInsert['fungsi'] = $this->fungsi;
        $insertLog = $this->Financekasmodel->insert($dataInsert, 't_log');        
    }  
    function index(){
    	$dataSelect['deleted'] = 1;
        $sql = "SELECT A.*, B.nama AS nama_pegawai FROM fin_kas A LEFT JOIN m_pegawai B ON A.add_by = B.id WHERE A.deleted = 1 ORDER BY A.date_add ASC";
        $data['list'] = json_encode($this->Financekasmodel->rawQuery($sql)->result());
    	$this->load->view('Finance_kas/view', $data);
    }

    function data(){
        $requestData= $_REQUEST;
        $columns = array( 
            0   =>  '#', 
            1   =>  'nama_pegawai', 
            2   =>  'date_add',
            3   =>  'rincian',
            4   =>  'debet',
            5   =>  'kredit',
            6   =>  'saldo_akhir',
            7   =>  'aksi'
        );
        $sql = "SELECT A.*, B.nama AS nama_pegawai FROM fin_kas A LEFT JOIN m_pegawai B ON A.add_by = B.id WHERE A.deleted = 1";
        $query=$this->Financekasmodel->rawQuery($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        
        if( !empty($requestData['search']['value']) ) {
            $sql.=" AND ( B.nama LIKE '%".$requestData['search']['value']."%' "; 
            $sql.=" OR A.date_add LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.rincian LIKE '%".$requestData['search']['value']."%' ";
            $sql.=" OR A.debet LIKE '%".$requestData['search']['value']."%'";
            $sql.=" OR A.kredit LIKE '%".$requestData['search']['value']."%'";
            $sql.=" OR A.saldo_akhir LIKE '%".$requestData['search']['value']."%' )";
        }

        //Date range filtering
        if(!empty($requestData['start_date']) AND !empty($requestData['end_date'])) {
            $sql.=" AND ( DATE(A.date_add) >= '".date("Y-m-d", strtotime($requestData['start_date']))."' "
                ."AND DATE(A.date_add) <= '".date("Y-m-d", strtotime($requestData['end_date']))."')";
        }
        // echo $sql;
        // die();

        $query=$this->Financekasmodel->rawQuery($sql);
        $totalFiltered = $query->num_rows();

        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $query=$this->Financekasmodel->rawQuery($sql);
        
        $data = array(); $i=0;
        foreach ($query->result_array() as $row) {
            $nestedData     =   array(); 
            $nestedData[]   =   "<span class='text-center' style='display:block;'>".($i+1)."</span>";
            $nestedData[]   =   $row["nama_pegawai"];
            $nestedData[]   =   date("d F Y H:i:s", strtotime($row["date_add"]));
            $nestedData[]   =   $row["rincian"];
            $nestedData[]   =   "<span class='pull-right'>".number_format($row["debet"], 2, ",", ".")."</span>";
            $nestedData[]   =   "<span class='pull-right'>".number_format($row["kredit"], 2, ",", ".")."</span>";
            $nestedData[]   =   "<span class='pull-right'>".number_format($row["saldo_akhir"], 2, ",", ".")."</span>";
            $attachment = (!empty($row['nama_foto'])) ? '<a href="'.base_url('upload/finance_kas/')."/".$row['nama_foto'].'" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Download Foto Bukti" download><i class="fa fa-paperclip"></i></a>' : '';
            $nestedData[]   .=   '<div class="text-center" style="display:block;"><div class="btn-group" >'
                // .'<a id="group'.$row["id"].'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'
                // .'<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('.$row["id"].')"><i class="fa fa-pencil"></i></a>'
                .$attachment
               .'</div>'
            .'</div>';
            
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
		$list = $this->Financekasmodel->select($dataSelect, 'fin_kas')->result();
		echo json_encode(array('status' => '3','list' => $list));
	}
	
    function add(){
        $params = $this->input->post();
        $id = (!empty($params['id'])) ? $params['id'] : $this->Financekasmodel->get_last_id("fin_kas") + 1;
        $saldo_akhir = $this->hitung_saldo($params['debet'], $params['kredit']);

        $dataInsert['rincian']          = $params['rincian'];
        $dataInsert['debet']            = $params['debet'];
        $dataInsert['kredit']           = $params['kredit'];
        $dataInsert['saldo_akhir']      = $saldo_akhir;
        $dataInsert['nama_foto']        = $this->proses_foto($id);
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']          = 1;

        $checkData = $this->Financekasmodel->select($dataInsert, 'fin_kas');
        if($checkData->num_rows() < 1){
            $insert = $this->Financekasmodel->insert_id($dataInsert, 'fin_kas');
            if($insert){
                //now update saldo akhir in setting
                $this->update_setting($saldo_akhir);

                $dataSelect['deleted'] = 1;
                $sql = "SELECT A.*, B.nama AS nama_pegawai FROM fin_kas A LEFT JOIN m_pegawai B ON A.add_by = B.id WHERE A.deleted = 1 ORDER BY A.date_add ASC";
                $list = $this->Financekasmodel->rawQuery($sql)->result();
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
    		$selectData = $this->Financekasmodel->select($dataSelect, 'fin_kas');
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
	
    function edit(){ /*NOT USED FOR NOW*/
		$params = $this->input->post();
        $id = (!empty($params['id'])) ? $params['id'] : $this->Financekasmodel->get_last_id("fin_kas") + 1;

        $dataCondition['id']            = $params['id'];
		$dataUpdate['id_bank'] 			= $params['id_bank'];
        $dataUpdate['nominal']          = $params['nominal'];
        $dataUpdate['tanggal_transfer'] = $params['tanggal_transfer'];
        $dataUpdate['keterangan']       = $params['keterangan'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        if(!$_FILES['foto']['error']) {
            $dataUpdate['nama_foto']         = $this->proses_foto($id);
        }

		$checkData = $this->Financekasmodel->select($dataCondition, 'fin_kas');
		if($checkData->num_rows() > 0){
			$update = $this->Financekasmodel->update($dataCondition, $dataUpdate, 'fin_kas');

			if($update){
				$dataSelect['deleted'] = 1;
                $sql = "SELECT A.*, B.nama AS nama_pegawai, C.nama AS nama_bank FROM fin_kas A LEFT JOIN m_pegawai B ON A.add_by = B.id LEFT JOIN m_bank C ON A.id_bank = C.id WHERE A.deleted = 1 ORDER BY A.date_add ASC";
				$list = $this->Financekasmodel->select($sql)->result();
				echo json_encode(array('status'=>'3','list'=>$list));
			}else{
				echo json_encode(array( 'status'=>'2' ));
			}
		}else{			
    		echo json_encode(array( 'status'=>'1' ));
		}
    }
    function delete(){ //NOT USED FOR NOW
		$id = $this->input->post("id");
    	if($id != null){
    		$dataCondition['id'] = $id;
    		$dataUpdate['deleted'] = 0;
    		$update = $this->Financekasmodel->update($dataCondition, $dataUpdate, 'fin_kas');
    		if($update){
    			$dataSelect['deleted'] = 1;
				$list = $this->Financekasmodel->select($dataSelect, 'fin_kas')->result();
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
        $filename = "contoh_kas.csv"; //download filename
        $sql = "SELECT B.nama AS pencatat, A.date_add, A.rincian, A.debet, A.kredit, A.saldo_akhir FROM fin_kas A LEFT JOIN m_pegawai B ON A.add_by = B.id WHERE A.deleted = 1 ORDER BY A.date_add ASC";
        $result = $this->db->query($sql);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }
    public function upload_csv() {   
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
                'date_add',
                'rincian',
                'debet',
                'kredit'
            );

            $final = array();
            foreach ($filearray as $key => $value) {
                $products[] = array_combine($fields, $value);
            }

            // date_default_timezone_set('');
            $date = date("Y-m-d H:i:s");
            $data = array();
            foreach ($products as $prdct) {
                $saldo_akhir = $this->hitung_saldo($prdct['debet'], $prdct['kredit']);
                $data[] = array(
                    "date_add"          => date('Y-m-d H:i:s', strtotime($prdct['date_add'])),
                    "rincian"           => $prdct['rincian'],
                    "debet"             => $prdct['debet'],
                    "kredit"            => $prdct['kredit'],
                    "saldo_akhir"       => $saldo_akhir,
                    "last_edited"       => date("Y-m-d H:i:s"),
                    "add_by"            => isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0,
                    "edited_by"         => isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0,
                    "deleted"         => 1
                );
                $this->update_setting($saldo_akhir);    
            }
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // die();

            $insert = $this->Financekasmodel->insert_batch($data, 'fin_kas');
            unlink('./files/finance/' . $file);
            if($insert) {
                $dataSelect['deleted'] = 1;
                $sql = "SELECT A.*, B.nama AS nama_pegawai FROM fin_kas A LEFT JOIN m_pegawai B ON A.add_by = B.id WHERE A.deleted = 1 ORDER BY A.date_add DESC";
                    $list = $this->Financekasmodel->rawQuery($sql)->result();
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

    private function update_setting($saldo_akhir) {
        //check if data "saldo kas kecil" exist
        $dataCondition = array( 'id' => 1, 'nama' => 'saldo kas kecil' );
        $data_db = $this->Financekasmodel->select($dataCondition, "setting");
        if($data_db->num_rows() < 1) {
            //insert into setting if "saldo kas akhir" not found
            $dataInsert = array('nama' => 'saldo kas kecil', 'nilai' => 0);
            $this->Financekasmodel->insert($dataInsert, "setting");
        }

        //update setting 'saldo kas akhir'
        $dataUpdate = array('nilai' => $saldo_akhir);
        $setting = $this->Financekasmodel->update($dataCondition, $dataUpdate, 'setting');

        return $setting;
    }
    private function hitung_saldo($debet, $kredit) {
        $dataCondition = array( 'id' => 1, 'nama' => 'saldo kas kecil' );
        $dataSetting = $this->Financekasmodel->select($dataCondition, "setting")->row();
        $saldo_awal = !empty($dataSetting->nilai) ? $dataSetting->nilai : 0;

        $saldo_akhir = ($saldo_awal + $debet) - $kredit;
        return $saldo_akhir;
    }
    private function proses_foto($id) { //PENGECUALIAN. (FOTO NOT REQUIRED & TIDAK TERBATAS IMAGE)
        $img_name = '';
        $date = date("dmY"); $time = date("His");
        $input_name = 'foto';
        if(!empty($_FILES[$input_name]['name'])) {
            
            $raw_filename = $_FILES[$input_name]['name'];
            $extension = pathinfo($raw_filename, PATHINFO_EXTENSION);
            $img_path = URL_UPLOAD."finance_kas/";
            $img_name = "kasBuktiFile".$id.".".$extension;

            $config['overwrite'] = true;
            $config['upload_path'] = $img_path;
            $config['file_name'] = $img_name;
            $config['allowed_types'] = "*";

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($input_name)) 
            {
                $error = array('error' => $this->upload->display_errors());
                $this->upload->display_errors();
                // echo "<pre>";
                // echo $this->upload->display_errors();;
                // echo "</pre>";
            }
            else {
                $file_data = $this->upload->data();
                $upload_data['file_name'] = $file_data['file_name'];
                $upload_data['created'] = date("Y-m-d H:i:s");
                $upload_data['modified'] = date("Y-m-d H:i:s");
                //echo $upload data if you want to see the file information
                // echo "<pre>";
                // print_r($upload_data);
                // echo "</pre>";
            }
        }
        return $img_name;
    }
    private function cek_tipe($tipe)
    {
        if ($tipe == 'image/jpeg') 
            { return ".jpg"; }
        else if($tipe == 'image/png') 
            { return ".png"; }
        else 
            { return false; }
    }
    
}