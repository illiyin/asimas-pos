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
          <th class="text-center">No. Batch</th>
          <th class="text-center">Nama Bahan</th>
          <th class="text-center">Nama Supplier</th>
          <th class="text-center no-sort">Nama Produsen</th>
          <th class="text-center no-sort">Jumlah Masuk</th>
          <th class="text-center no-sort">Expired Date</th>
          <th class="text-center no-sort">Tanggal Masuk</th>
          <?php if($session_detail->id == 7): ?>
          <th class="text-center no-sort">Harga Pembelian</th>
          <?php endif; ?>
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
                <input type="text" class="form-control" name="no_transaksi" id="no_transaksi" placeholder="No. Transaksi" required />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="no_batch">No. Batch</label>
                <input type="text" class="form-control" name="no_batch" id="no_batch" placeholder="No. Batch" required />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="no_so">No. SO</label>
                <input type="text" class="form-control" name="no_so" id="no_so" placeholder="No. Sales Order" required />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="id_bahan">Nama Bahan</label>
                <select name="id_bahan" class="form-control" id="nama_bahan" required="required" onchange="selectBahan(this.value)">
                  <option value=""  disabled selected>-- Pilih Nama Bahan --</option>
                  <?php foreach($list_bahan as $data): ?>
                  <option value="<?= $data->id ?>"><?= $data->nama ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="kode_bahan">Kode Bahan</label>
                <input type="text" class="form-control" name="kode_bahan" id="kode_bahan" disabled>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="kategori_bahan">Kategori Bahan</label>
                <input type="text" class="form-control" name="kategori_bahan" id="kategori_bahan" disabled>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="nama_produsen">Nama Produsen</label>
                <select name="id_produsen" class="form-control" id="nama_produsen">
                  <option value="" disabled selected>-- Pilih Produsen --</option>
                  <?php foreach($list_produsen as $row): ?>
                  <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                  <?php endforeach; ?>
                  <!-- <option value="1">Produsen 1</option> -->
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="nama_supplier">Nama Supplier</label>
                <select name="id_supplier" class="form-control" id="nama_supplier" required="required">
                  <option value="" disabled="">-- Pilih Supplier --</option>
                  <?php foreach($list_supplier as $row): ?>
                  <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                  <?php endforeach; ?>
                  <!-- <option value="1">Produsen 1</option> -->
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="jumlah_masuk">Jumlah Masuk</label>
                <input type="text" class="form-control" name="jumlah_masuk" id="jumlah_masuk" placeholder="Jumlah Masuk" required />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="tanggal_masuk">Tanggal Masuk</label>
                <div class="input-group">
                  <input type="text" class="form-control datepicker" name="tanggal_masuk" id="tanggal_masuk" placeholder="dd/mm/yyyy" required />
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="expire_date">Expire Date</label>
                <div class="input-group">
                  <input type="text" class="form-control datepicker" name="expire_date" id="expire_date" placeholder="dd/mm/yyyy" required />
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
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

<!-- Add Harga -->
<div class="modal fade" id="hargapembelian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <form action="" method="POST" id="formHargaPembelian" enctype="multipart/form-data"> <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="harga">Harga Pembelian</label>
              <input type="text" name="harga" maxlength="50" Required class="form-control" id="harga" placeholder="Harga Pembelian" onkeydown="return numericOnly(event)">
              <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id_harga" placeholder="ID Barang">
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
<!-- /.Modal Add-->

<script type="text/javascript">
  var list_barang = <?php echo json_encode($list_barang); ?>;
  var list_data = <?php echo json_encode($list); ?>;
  var list_produsen = <?php echo json_encode($list_produsen); ?>;
  var list_supplier = <?php echo json_encode($list_supplier); ?>;
  var list_bahan = <?php echo json_encode($list_bahan); ?>;
  var list_kategori_bahan = <?php echo json_encode($list_kategori_bahan); ?>;

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
    $("#no_batch").val("");
    $("#nama_supplier").val("");
    $("#nama_bahan").val("");
    $("#nama_produsen").val("");
    $("#jumlah_masuk").val("");
    $("#no_so").val("");
    $("#kode_bahan").val("");
    $("#kategori_bahan").val("");
    $("#tanggal_masuk").val("");
    $("#expire_date").val("");
    $('#modalAdd').modal('show');
  }
  function addHarga(id){
    var data = list_data.filter(function (index) { return index.id == id })[0];
    $("#id_harga").val(data.id);
    $("#harga").val(data.harga_pembelian);
    $("#myModalLabel").text("Ubah Harga Pembelian");
    $('#hargapembelian').modal('show');
  }
  function getMasterById(jsonData, id){
    data = jsonData.filter(function(index) {return index.id == id});
    return data.length > 0 ? data[0] : false;
  }
  function selectBahan(id) {
    var dataBahan = getMasterById(list_bahan, id);
    var dataKategori = getMasterById(list_kategori_bahan, dataBahan.id_kategori_bahan);
    $("#kode_bahan").val(dataBahan.kode_bahan);
    $("#kategori_bahan").val(dataKategori.nama);
  }
  
  $("#formHargaPembelian").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Transaksi_gudang_masuk/Master/edit')?>/";
    var param = $('#formHargaPembelian').serialize();
    
    $.ajax({
      type: 'post',
      url: action,
      data: param,
      dataType: 'json',
      success: function (data) {
        if(data.status == 3) {
          list_data = data.list
        }
        initDataTable.ajax.reload();
        $("#hargapembelian").modal('hide');
        new PNotify({
          title: data.status == 3 ? 'Success' : 'Gagal',
          text: data.message,
          type: data.status == 3 ? 'success' : 'error',
          hide: true,
          delay: 5000,
          styling: 'bootstrap3'
        });
      }
    });
  });

  $("#myform").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Transaksi_gudang_masuk/Master/add')?>/";
    var param = $('#myform').serialize();
    
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
