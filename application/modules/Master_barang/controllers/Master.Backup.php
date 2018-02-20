function add(){
        $params = $this->input->post();
        $id = (!empty($params['id'])) ? $params['id'] : $this->Barangmodel->get_last_id("m_bahan") + 1;

        $dataInsert['nama']             = $params['nama'];
        $dataInsert['id_supplier_bahan'] = $params['id_supplier'];
        $dataInsert['id_satuan']        = $params['id_satuan'];
        $dataInsert['id_gudang']        = $params['id_gudang'];
        $dataInsert['id_kategori_bahan'] = $params['id_kategori'];
        $dataInsert['sku']              = $params['sku'];
        $dataInsert['kode_barang']      = $params['kode_barang'];
        $dataInsert['berat']            = $params['berat'];
        $dataInsert['harga_beli']       = $params['harga_beli'];
        $dataInsert['deskripsi']        = $params['deskripsi'];
        $dataInsert['foto']             = $this->proses_foto($id);
        $dataInsert['last_edited']      = date("Y-m-d H:i:s");
        $dataInsert['add_by']           = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        $dataInsert['deleted']          = 1;

        $checkData = $this->Barangmodel->select($dataInsert, 'm_bahan');
        if($checkData->num_rows() < 1){
            $insert = $this->Barangmodel->insert_id($dataInsert, 'm_bahan');
            if($insert){
                if(isset($params['id_warna'])){
                    $this->insert_detail($insert, $params['id_warna'], "warna");
                }
                $dataSelect['deleted'] = 1;
                $list = $this->Barangmodel->select($dataSelect, 'm_bahan')->result();
                $list_det_warna = $this->Barangmodel->get('m_bahan_det_warna')->result();
                echo json_encode(array('status'=>3,'list'=>$list ,'list_det_warna' => $list_det_warna));
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
            $selectData = $this->Barangmodel->select($dataSelect, 'm_bahan');
            if($selectData->num_rows() > 0){
                echo json_encode(
                    array(
                        'status'            => 2,
                        'id'                => $selectData->row()->id,
                        'nama'              => $selectData->row()->nama,
                    ));
            }else{
                echo json_encode(array('status' => 1));
            }
        }else{
            echo json_encode(array('status' => 0));
        }
    }

    function last_id() {
        echo "<script>console.log(".$this->Barangmodel->get_last_id("m_bahan").");</script>";
    }
    function edit(){
        $params = $this->input->post();
        $id = (!empty($params['id'])) ? $params['id'] : $this->Barangmodel->get_last_id("m_bahan") + 1;

        $dataCondition['id']            = $params['id'];
        $dataUpdate['nama']             = $params['nama'];
        $dataUpdate['id_supplier_bahan'] = $params['id_supplier'];
        $dataUpdate['id_satuan']        = $params['id_satuan'];
        $dataUpdate['id_gudang']        = $params['id_gudang'];
        $dataUpdate['id_kategori_bahan'] = $params['id_kategori'];
        $dataUpdate['sku']              = $params['sku'];
        $dataUpdate['kode_barang']      = $params['kode_barang'];
        $dataUpdate['berat']            = $params['berat'];
        $dataUpdate['harga_beli']       = $params['harga_beli'];
        $dataUpdate['deskripsi']        = $params['deskripsi'];
        $dataUpdate['last_edited']      = date("Y-m-d H:i:s");
        $dataUpdate['edited_by']        = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
        if(!$_FILES['foto']['error']) {
            $dataUpdate['foto']         = $this->proses_foto($id);
        }

        $checkData = $this->Barangmodel->select($dataCondition, 'm_bahan');
        if($checkData->num_rows() > 0){
            $update = $this->Barangmodel->update($dataCondition, $dataUpdate, 'm_bahan');
            if(isset($params['id_warna'])){
                $this->insert_detail($params['id'], $params['id_warna'], "warna");
            }

            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->Barangmodel->select($dataSelect, 'm_bahan')->result();
                $list_det_warna = $this->Barangmodel->get('m_bahan_det_warna')->result();
                echo json_encode(array('status'=>'3','list'=>$list ,'list_det_warna' => $list_det_warna));
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
            $update = $this->Barangmodel->update($dataCondition, $dataUpdate, 'm_bahan');
            if($update){
                $dataSelect['deleted'] = 1;
                $list = $this->Barangmodel->select($dataSelect, 'm_bahan')->result();
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

    private function insert_detail($id_bahan, $data, $table) {
        $this->_insertLog('Insert Detail');
        if(!empty($table) AND !empty($data)) {
            //check if id_bahan exist in m_bahan_det_ tables
            $dataInsert = array();
            $dataCondition['id_bahan'] = $id_bahan;
            $checkData = $this->Barangmodel->select($dataCondition, 'm_bahan_det_'.$table);
            if($checkData->num_rows() > 0) {
                //Delete old data first
                $this->Barangmodel->delete($dataCondition, 'm_bahan_det_'.$table);
            }

            //Then insert new data
            foreach ($data as $key=>$value) {
                $dataInsert[] = array(
                        'id_bahan' => $id_bahan,
                        'id_'.$table => $value
                    );
            } // print_r($dataInsert);
            $this->Barangmodel->insert_batch($dataInsert, 'm_bahan_det_'.$table);
        }
    }
    private function proses_foto($id) {
        $date = date("dmY"); $time = date("His");
        $input_name = 'foto';

        $tipe = $this->cek_tipe($_FILES[$input_name]['type']);
        $img_path = URL_UPLOAD."master_barang/";
        $img_name = "bahanImage".$id.$tipe;

        $config['overwrite'] = true;
        $config['upload_path'] = $img_path;
        $config['allowed_types'] = "png|jpg|jpeg";
        $config['file_name'] = $img_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($input_name))
        {
            $error = array('error' => $this->upload->display_errors());
            $this->upload->display_errors();
        }
        else {
            $file_data = $this->upload->data();
            $upload_data['file_name'] = $file_data['file_name'];
            $upload_data['created'] = date("Y-m-d H:i:s");
            $upload_data['modified'] = date("Y-m-d H:i:s");
            //echo $upload data if you want to see the file information
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