<!-- Page Content -->
<div class="container">
  <div class="row" style='min-height:80px;'></div>
  <div class="row">
    <h3><strong>Transaksi</strong> - Keluar Gudang</h3>
  </div>
  <div class="row" style="margin-top:10px;">
    <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="text-center">No. Transaksi</th>
          <th class="text-center">Tanggal Keluar</th>
          <th class="text-center">Nama Bahan</th>
          <th class="text-center">Satuan</th>
          <th class="text-center">Jumlah Keluar</th>
          <th class="text-center">No. Batch</th>
          <th class="text-center">Expired Date</th>
          <th class="text-center">Kode Bahan</th>
          <th class="text-center">Nama Distributor</th>
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
        <h4 class="modal-title" id="myModalLabel">Form Transaksi Keluar - Gudang</h4>
      </div>
      <form action="#" method="POST" id="myform">
        <div class="modal-body">
          <div class="row">
            <input type="hidden" name="id" id="id">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="no_transaksi">No. Transaksi</label>
                <input type="text" class="form-control" name="no_transaksi" id="no_transaksi" placeholder="No. Transaksi" required>
              </div>
            </div>
            <!-- <div class="col-sm-6">
              <div class="form-group">
                <label for="harga_jual">Harga Penjualan</label>
                <input type="text" class="form-control" name="harga_jual" id="harga_jual" placeholder="Harga Penjualan" required>
              </div>
            </div> -->
            <div class="col-sm-6">
              <div class="form-group">
                <label for="tanggal_keluar">Tanggal Keluar</label>
                <div class="input-group">
                  <input type="text" class="form-control datepicker" name="tanggal_keluar" id="tanggal_keluar" placeholder="dd/mm/yyyy" required>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="id_bahan">Nama Bahan</label>
                <select name="id_bahan" class="form-control" id="id_bahan" required="required" onchange="selectBahan(this.options[this.selectedIndex].getAttribute('data-satuan'))">
                  <option value="" disabled selected>-- Pilih Bahan --</option>
                  <?php foreach($list_bahan as $row): ?>
                  <option value="<?= $row->id ?>" data-satuan="<?= $row->id_satuan ?>"><?php echo $row->nama ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="id_satuan">Satuan</label>
                  <input type="text" name="id_satuan" id="id_satuan" disabled class="form-control">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="jumlah_keluar">Jumlah Keluar</label>
                <input type="text" class="form-control" name="jumlah_keluar" id="jumlah_keluar" placeholder="Jumlah Keluar" onkeydown="return numericOnly(event)" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="no_batch">No. Batch</label>
                <!-- <input type="text" class="form-control" name="no_batch" id="no_batch" placeholder="No. Batch" required> -->
                <select name="no_batch" id="no_batch" class="form-control">
                  <option selected disabled>--Pilih No. Batch--</option>
                  <?php foreach($list_batch as $batch): ?>
                  <option value="<?= $batch->no_batch ?>"><?= $batch->no_batch ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="expired_date">Expired Date</label>
                <div class="input-group">
                  <input type="text" class="form-control datepicker" name="expired_date" id="expired_date" placeholder="dd/mm/yyyy" required>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="id_distributor">Nama Distributor</label>
                <select name="id_distributor" class="form-control" id="id_distributor" required="required">
                  <option value="" disabled selected>-- Pilih Distributor --</option>
                  <?php foreach($list_distributor as $row): ?>
                  <option value="<?= $row->id ?>"><?php echo $row->nama ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" rows="5" class="form-control" id="keterangan"></textarea>
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
var jsonList = <?php echo json_encode($list_data); ?>;
var list_distributor = <?php echo json_encode($list_distributor); ?>;
var list_satuan = <?php echo json_encode($list_satuan); ?>;
var list_bahan = <?php echo json_encode($list_bahan); ?>;
var initDataTable = $('#TableMainServer').DataTable({
    "bProcessing": true,
    "bServerSide": true,
    // "order": [[3, 'DESC']],
    "ajax":{
          url :"<?php echo base_url()?>Transaksi_gudang_keluar/Master/data",
          type: "post",  // type of method  , by default would be get
          error: function(e){  // error handling code
            console.log(e);
            // $("#employee_grid_processing").css("display","none");
          }
        },
    "columnDefs": [ {
      "targets"  : 'no-sort',
      "orderable": false,
    }]
  });
function showAdd(){
  $("#myform")[0].reset();
  $('#modalAdd').modal('show');
}
function showUpdate(id){
  var dataDetail = jsonList.filter(function (index) { return index.id == id })[0];
  $("#id").val(dataDetail.id);
  $("#no_transaksi").val(dataDetail.no_transaksi);
  $("#no_batch").val(dataDetail.no_batch);
  $("#no_transaksi").prop('disabled', true);
  // $("#harga_jual").val(dataDetail.harga_penjualan);
  $("#jumlah_keluar").val(dataDetail.jumlah_keluar);
  $("#keterangan").val(dataDetail.keterangan);
  var expiredDate = dataDetail.expired_date;
  var datetime = expiredDate.split(" ");
  var dateExplode = datetime[0].split("-");
  var real_datetime = dateExplode[2]+'/'+dateExplode[1]+'/'+dateExplode[0];
  $("#expired_date").val(real_datetime);
  var tanggalKeluar = dataDetail.tanggal_keluar;
  var dateKeluar = tanggalKeluar.split(" ");
  var tanggalExplode = dateKeluar[0].split("-");
  var real_datetime = tanggalExplode[2]+'/'+tanggalExplode[1]+'/'+tanggalExplode[0];
  $("#tanggal_keluar").val(real_datetime);
  var dataBahan = getMasterById(list_bahan, dataDetail.id_bahan);
  $("#id_bahan").val(dataBahan.id);
  var dataSatuan = getMasterById(list_satuan, dataDetail.id_satuan);
  $("#id_satuan").val(dataSatuan.id);
  var dataDistributor = getMasterById(list_distributor, dataDetail.id_distributor);
  $("#id_distributor").val(dataDistributor.id);
  $('#modalAdd').modal('show');
}
function getMasterById(jsonData, id){
  data = jsonData.filter(function(index) {return index.id == id});
  return data.length > 0 ? data[0] : false;
}
function selectBahan(id){
  var satuan = getMasterById(list_satuan, id);
  $("#id_satuan").val(satuan.nama);
}

$("#myform").on('submit', function(e){
  e.preventDefault();
  var notifText = 'Data berhasil ditambahkan!';
  var action = "<?php echo base_url('Transaksi_gudang_keluar/Master/add')?>/";
  if ($("#id").val() != ""){
    action = "<?php echo base_url('Transaksi_gudang_keluar/Master/edit')?>/";
    notifText = 'Data berhasil diubah!';
  }
  var params = new FormData(jQuery('#myform')[0]);

  $.ajax({
    url: action,
    type: 'post',
    data: params,
    cache: false,
    contentType: false,
    processData: false,
    dataType: 'json',
    beforeSend: function() {
      // tambahkan loading
      $("#aSimpan").prop("disabled", true);
      $('#aSimpan').html('Sedang Menyimpan...');
    },
    error: function(e) {
      console.log(e);
    },
    success: function (data) {
      if (data.status == '3'){
        initDataTable.ajax.reload();
        $("#modalAdd").modal('hide');
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
          delay: 5000,
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
  $.ajax({
    type: 'post',
    url: '<?php echo base_url('Transaksi_gudang_keluar/Master/delete'); ?>/',
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