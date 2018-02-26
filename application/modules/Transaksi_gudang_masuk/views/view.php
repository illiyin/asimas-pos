<!-- Page Content -->
<div class="container">
  <div class="row" style='min-height:80px;'></div>
  <div class="row">
    <h3><strong>Transaksi</strong> - Masuk Gudang</h3>
  </div>
  <div class="row" style="margin-top:10px;">
    <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="text-center no-sort">#</th>
          <th class="text-center">No. Transaksi</th>
          <th class="text-center">Nama Barang</th>
          <th class="text-center">Nama Supplier</th>
          <th class="text-center">Satuan</th>
          <th class="text-center">Jumlah Masuk</th>
          <th class="text-center">No. Batch</th>
          <th class="text-center">Expire Date</th>
          <th class="text-center">Kode Bahan</th>
          <th class="text-center">Nama Produsen</th>
          <th class="text-center">Keterangan</th>
          <th class="text-center hidden-xs no-sort">Aksi</th>
        </tr>
      </thead>

      <tbody id='bodytable'>
      </tbody>
    </table>
  </div>
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-add btn-lg"  onclick="showAdd()">
    Tambah Transaksi
  </button>
</div>
<!-- /.container -->
<!-- Modal add -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Form Transaksi Masuk - Gudang</h4>
      </div>
      <form action="#" method="POST" id="myform">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <input type="hidden" name="id" id="id">
              <div class="form-group">
                <label for="no_transaksi">No. Transaksi</label>
                <input type="text" class="form-control" name="no_transaksi" id="no_transaksi" placeholder="No. Transaksi">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="harga_beli">Harga Pembelian</label>
                <input type="text" class="form-control" name="harga_beli" id="harga_beli" placeholder="Harga Pembelian">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <select name="id_barang" class="form-control" id="nama_barang" required="required" onchange="return detailBarang(this.value)">
                  <option value="" disabled>-- Pilih Barang --</option>
                  <?php foreach($list_barang as $data): ?>
                  <option value="<?= $data['id'] ?>"><?= $data['nama_barang'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="kode_bahan">Kode Bahan</label>
                <select name="id_bahan" class="form-control" id="kode_bahan" required="required">
                  <option value="" disabled>-- Pilih Kode Bahan --</option>
                  <?php foreach($list_bahan as $data): ?>
                  <option value="<?= $data->id ?>"><?= $data->kode_bahan ?></option>
                  <?php endforeach; ?>
                </select>
                <!-- <input type="text" class="form-control" name="kode_bahan" id="kode_bahan" placeholder="Kode Bahan"> -->
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="nama_produsen">Nama Produsen</label>
                <select name="id_produsen" class="form-control" id="nama_produsen" required="required">
                  <option value="" disabled="">-- Pilih Produsen --</option>
                  <?php foreach($list_produsen as $row): ?>
                  <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                  <?php endforeach; ?>
                  <!-- <option value="1">Produsen 1</option> -->
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="tanggal_masuk">Tanggal Masuk</label>
                <div class="input-group">
                  <input type="text" class="form-control datepicker" name="tanggal_masuk" id="tanggal_masuk" placeholder="dd/mm/yyyy">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="nama_supplier">Nama Supplier</label>
                <input type="text" class="form-control" name="nama_supplier" id="nama_supplier" placeholder="Nama Supplier" disabled="">
                <!-- <select name="nama_supplier" class="form-control" id="nama_supplier" required="required" disabled="">
                  <option value="">-- Pilih Supplier --</option>
                  <option value="1">Supplier 1</option>
                </select> -->
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="jumlah_masuk">Jumlah Masuk</label>
                <input type="text" class="form-control" name="jumlah_masuk" id="jumlah_masuk" placeholder="Jumlah Masuk" disabled="">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="id_satuan">Satuan</label>
                <input type="text" class="form-control" name="id_satuan" id="id_satuan" placeholder="Satuan" disabled="">
                <!-- <select name="id_satuan" class="form-control" id="id_satuan" required="required" disabled="">
                  <option value="">-- Pilih Satuan --</option>
                  <option value="1">Satuan 1</option>
                </select> -->
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="stok_akhir">Stok Akhir</label>
                <input type="text" class="form-control" name="stok_akhir" id="stok_akhir" placeholder="Stok Akhir" disabled="">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="no_batch">No. Batch</label>
                <input type="text" class="form-control" name="no_batch" id="no_batch" placeholder="No. Batch" disabled="">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="expire_date">Expire Date</label>
                <div class="input-group">
                  <input type="text" class="form-control datepicker" name="expire_date" id="expire_date" placeholder="dd/mm/yyyy" disabled="">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="kategori_bahan">Kategori Bahan</label>
                <input type="text" class="form-control" name="kategori_bahan" id="kategori_bahan" placeholder="Kategori Bahan" disabled="">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" rows="5" class="form-control" id="keterangan" disabled=""></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-add" id="aSimpan">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.Modal Ubah-->
<script type="text/javascript">
  var list_barang = <?php echo json_encode($list_barang); ?>;
  var list_data = <?php echo json_encode($list); ?>;
  var list_produsen = <?php echo json_encode($list_produsen); ?>;
  var list_bahan = <?php echo json_encode($list_bahan); ?>;
  var awalLoad = true;
  var initDataTable = $('#TableMain').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "ajax":{
            url :"<?php echo base_url()?>Transaksi_gudang_masuk/Master/data",
            type: "post",  // type of method  , by default would be get
            error: function(){  // error handling code
              // $("#employee_grid_processing").css("display","none");
            }
          },
      "columnDefs": [ {
        "targets"  : 'no-sort',
        "orderable": false,
      }]
    });

  function showAdd(){
    $("#id").val("");
    $("#no_transaksi").val("");
    $("#harga_beli").val("");
    $("#nama_barang").val("");
    $("#kode_bahan").val("");
    $("#nama_produsen").val("");
    $("#tanggal_masuk").val("");
    $("#nama_supplier").val("");
    $("#jumlah_masuk").val("");
    $("#id_satuan").val("");
    $("#stok_akhir").val("");
    $("#no_batch").val("");
    $("#expire_date").val("");
    $("#kategori_bahan").val("");
    $("#keterangan").val("");
    $('#modalAdd').modal('show');
  }
  function showUpdate(id){
    var dataMaster = list_data.filter(function (index){ return index.id == id});
    // Data Master
    $("#id").val(dataMaster[0].id);
    $("#no_transaksi").prop('disabled', true);
    $("#no_transaksi").val(dataMaster[0].no_transaksi);
    $("#harga_beli").val(dataMaster[0].harga_pembelian);
    var tglMasuk = dataMaster[0].tanggal_masuk;
    var datetime = tglMasuk.split(" ");
    var dateExplode = datetime[0].split("-");
    var real_datetime = dateExplode[2]+'/'+dateExplode[1]+'/'+dateExplode[0];
    $("#tanggal_masuk").val(real_datetime);
    // Data Bahan
    var dataBahan = getMasterById(list_bahan, dataMaster[0].id_bahan);
    $("#kode_bahan").val(dataBahan.id);
    // Data Produsen
    var dataProdusen = getMasterById(list_produsen, dataMaster[0].id_produsen);
    $("#nama_produsen").val(dataProdusen.id);
    // Data Barang
    var detailBarang = list_barang.filter(function (index) { return index.id == id });
    var data = detailBarang[0];
    $("#nama_supplier").val(data.supplier.nama);
    $("#jumlah_masuk").val(data.jumlah_masuk);
    $("#id_satuan").val(data.satuan.nama);  
    $("#stok_akhir").val(data.stok_akhir);
    $("#no_batch").val(data.no_batch);
    var expiredDate = data.expired_date;
    var datetime = expiredDate.split(" ");
    var dateExplode = datetime[0].split("-");
    var real_datetime = dateExplode[2]+'/'+dateExplode[1]+'/'+dateExplode[0];
    $("#expire_date").val(real_datetime);
    $("#kategori_bahan").val(data.kategori_bahan.nama);
    $("#keterangan").val(data.keterangan);
    $('#modalAdd').modal('show');
  }
  function getMasterById(jsonData, id){
    data = jsonData.filter(function(index) {return index.id == id});
    return data.length > 0 ? data[0] : false;
  }
  function detailBarang(id) {
    var dataDetail = list_barang.filter(function (index) { return index.id == id });
    var data = dataDetail[0];
    $("#nama_supplier").val(data.supplier.nama);
    $("#jumlah_masuk").val(data.jumlah_masuk);
    $("#id_satuan").val(data.satuan.nama);  
    $("#stok_akhir").val(data.stok_akhir);
    $("#no_batch").val(data.no_batch);
    var expiredDate = data.expired_date;
    var datetime = expiredDate.split(" ");
    var dateExplode = datetime[0].split("-");
    var real_datetime = dateExplode[2]+'/'+dateExplode[1]+'/'+dateExplode[0];
    $("#expire_date").val(real_datetime);
    $("#kategori_bahan").val(data.kategori_bahan.nama);
    $("#keterangan").val(data.keterangan);
  }
  $("#myform").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Transaksi_gudang_masuk/Master/add')?>/";
    if ($("#id").val() != ""){
      action = "<?php echo base_url('Transaksi_gudang_masuk/Master/edit')?>/";
      notifText = 'Data berhasil diubah!';
    }
    var param = $('#myform').serialize();
    if ($("#id").val() != ""){
     param = $('#myform').serialize()+"&id="+$('#id').val();
    }
    
    $.ajax({
      type: 'post',
      url: action,
      data: param,
      dataType: 'json',
      beforeSend: function() { 
        // tambahkan loading
        $("#aSimpan").prop("disabled", true);
        $('#aSimpan').html('Sedang Menyimpan...');
      },
      success: function (data) {
        if (data.status == '3'){
          initDataTable.ajax.reload();
          $("#modalAdd").modal('hide');
          // $("#notif-top").fadeIn(500);
          // $("#notif-top").fadeOut(2500);
          new PNotify({
              title: 'Sukses',
              text: notifText,
              type: 'success',
              hide: true,
              delay: 5000,
              styling: 'bootstrap3'
            });
        } else {
          $("#myform")[0].reset();
          new PNotify({
              title: 'Gagal',
              text: data.message,
              type: 'error',
              hide: true,
              delay: 3000,
              styling: 'bootstrap3'
            });
        }
        $('#aSimpan').html('Simpan');
        $("#aSimpan").prop("disabled", false);
      }
    });
  });

  function confirmDelete(el){
    var element = $(el).attr("id");
    var id  = element.replace("group","");
    var i = parseInt(id);
    $(el).attr("data-content","<button class=\'btn btn-danger myconfirm\'  href=\'#\' onclick=\'deleteData(this)\' id=\'aConfirm"+i+"\' style=\'min-width:85px\'><i class=\'fa fa-trash\'></i> Ya</button>");
    $(el).popover("show");
  }

  function deleteData(element){
    var el = $(element).attr("id");
    var id  = el.replace("aConfirm","");
    var i = parseInt(id);
    console.log(element);
    $.ajax({
      type: 'post',
      url: '<?php echo base_url('Transaksi_gudang_masuk/Master/delete'); ?>/',
      data: {"id":i},
      dataType: 'json',
      beforeSend: function() {
        // kasi loading
        $("#aConfirm"+i).html("Sedang Menghapus...");
        $("#aConfirm"+i).prop("disabled", true);
      },
      success: function (data) {
        if (data.status == '3'){
          initDataTable.ajax.reload();
         $("#aConfirm"+i).prop("disabled", false);
      // $("#notif-top").fadeIn(500);
      // $("#notif-top").fadeOut(2500);
          new PNotify({
            title: 'Sukses',
            text: 'Data berhasil dihapus!',
            type: 'success',
            hide: true,
            delay: 5000,
            styling: 'bootstrap3'
          });
        }
      }
    });
  }
  
</script>
