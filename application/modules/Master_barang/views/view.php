<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Master barang</strong> - Semua Barang</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <!-- <th class="text-center no-sort">Foto</th> -->
                  <th class="text-center">Nama Barang</th>
                  <th class="text-center">No. Batch</th>
                  <th class="text-center">Stok Akhir</th>
                  <th class="text-center" class="hidden-xs">Tanggal Expired</th>
                  <th class="text-center no-sort">Aksi</th>
              </tr>
          </thead>

          <tbody id='bodytable'>

          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <button type="button" class="btn btn-add btn-lg"  onclick="showAdd()">
     Tambah Barang
   </button>
</div>
<!-- /.container -->
<!-- Modal Detail Bahan baku -->
<div class="modal fade" id="Viewproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document" id="viewModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="view">Detail Barang</h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div id="viewSectionProduct">
              <!-- view goes here -->
              <div class="col-md-12"><div class="media">
                 <!-- <div class="media-left">
                    <img id="det_foto" class="media-object img-rounded" src="<?php //echo base_url()?>upload/master_barang/placeholder.png" alt="image" width="200px">
                 </div> -->
                 <div class="media-body">
                  <h1 class="media-heading" id="det_nama">{{ nama_barang }}</h1>
                  <div class="row">
                    <div class="col-sm-6">
                      <p><b>No. Batch :</b> <span id="det_nobatch"></span></p>
                      <p><b>Expire Date :</b> <span id="det_expired"></span></p>
                      <p><b>Jumlah Masuk :</b> <span id="det_jml_masuk"></span></p>
                      <p><b>Stok Akhir:</b> <span id="det_stok"></span></p>
                      <p><b>Satuan :</b> <span id="det_satuan"></span></p>
                      <p><b>Kategori :</b> <span id="det_kategori"></span></p>
                      <p><b>Deskripsi :</b> <span id="det_deskripsi"></span></p></div>
                    <div class="col-sm-6">
                      <h4>Detail Supplier</h4>
                      <p><b>Nama Supplier :</b> <span id="sup_nama"></span></p>
                      <p><b>Alamat :</b>  <span id="sup_alamat"></span></p>
                      <p><b>No. Telepon :</b> <span id="sup_notelp"></span></p>
                      <p><b>Email :</b> <span id="sup_email"></span></p>
                    </div>
                  </div>
                 </div>
              </div></div>
              <div class="col-md-6">

              </div>
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal">Close</button>
        </div>
      </div>
   </div>
</div>
<!-- /.Modal -->

<!-- Modal Add -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Barang</h4>
      </div>
      <form action="" method="POST" id="myform" enctype="multipart/form-data"> <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama">Nama Barang</label>
                 <input type="text" name="nama" maxlength="50" Required class="form-control" id="nama" placeholder="Nama Barang">
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Barang">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_satuan">Satuan</label>
                 <select name="id_satuan" class="form-control" id="id_satuan" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_supplier">Supplier</label>
                 <select name="id_supplier" class="form-control" id="id_supplier" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Kategori Bahan</label>
                 <select name="id_kategori" class="form-control" id="id_kategori" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="jml_masuk">Jumlah Masuk</label>
                 <input type="number" name="jml_masuk" maxlength="5" Required class="form-control" id="jml_masuk" placeholder="Jumlah masuk">
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="no_batch">No. Batch</label>
                 <input type="text" name="no_batch" maxlength="50" Required class="form-control" id="no_batch" placeholder="No. Batch">
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="expire_date">Expire Date</label>
                 <input type="text" name="expired_date" maxlength="50" Required class="form-control datepicker" id="expired_date" placeholder="Expire date">
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="stok_akhir">Stok Akhir</label>
                 <input type="text" name="stok_akhir" maxlength="50" Required class="form-control" id="stok_akhir" placeholder="Stok Akhir">
               </div>
             </div>
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="deskripsi">Keterangan</label>
                 <textarea name="deskripsi" rows="2" Required class="form-control" id="deskripsi" placeholder="Keterangan"></textarea>
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
  var jsonlist = <?php echo $list; ?>;
  var jsonSupplier = <?php echo $list_supplier; ?>;
  var jsonSatuan = <?php echo $list_satuan; ?>;
  var jsonKategori = <?php echo $list_kategori; ?>;
  var awalLoad = true;
  var initDataTable = $('#TableMainServer').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "order": [[4, 'DESC']],
      "ajax":{
            url :"<?php echo base_url()?>Master_barang/Master/data",
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

  function load_select_option(json, target_id, nama=""){
    var html = "";
    if(!nama == "") {
      html = "<option value='' selected disabled>Pilih "+nama+"</option>";
    }
    for (var i=0;i<json.length;i++){
         html = html+ "<option value='"+json[i].id+"'>"+json[i].nama+"</option>";
    } $(target_id).html(html);
  }
  function load_select() {
    load_select_option(jsonSupplier, "#id_supplier", "Supplier");
    load_select_option(jsonSatuan, "#id_satuan", "Satuan");
    load_select_option(jsonKategori, "#id_kategori", "Kategori");
  }
  function showAdd(){
    load_select();
    $("#myModalLabel").text("Tambah Barang");
    $("#id").val("");
    $("#nama").val("");
    $("#id_satuan").val("");
    $("#id_supplier").val("");
    $("#id_kategori").val("");
    $("#jml_masuk").val("");
    $("#no_batch").val("");
    $("#expired_date").val("");
    $("#stok_akhir").val("");
    $("#deskripsi").val("");
    $("#modalform").modal("show");
  }
  function showUpdate(i) {
    load_select();
    $("#myModalLabel").text("Ubah Barang");
    var dataUpdate = jsonlist.filter(function (index) { return index.id == i });
    var data = dataUpdate[0];
    $("#id").val(data.id);
    $("#nama").val(data.nama_barang);
    $("#id_satuan").val(data.id_satuan);
    $("#id_supplier").val(data.id_supplier);
    $("#id_kategori").val(data.id_kategori_bahan);
    $("#jml_masuk").val(data.jumlah_masuk);
    $("#no_batch").val(data.no_batch);
    var expiredDate = data.expired_date;
    var datetime = expiredDate.split(" ");
    var dateExplode = datetime[0].split("-");
    var real_datetime = dateExplode[2]+'/'+dateExplode[1]+'/'+dateExplode[0];
    $("#expired_date").val(real_datetime);
    $("#stok_akhir").val(data.stok_akhir);
    $("#deskripsi").val(data.keterangan);
    $("#modalform").modal("show");
  }
  function showDetail(i){
    var dataDetail = jsonlist.filter(function (index) { return index.id == i });
    $("#det_nama").text(dataDetail[0].nama_barang ? dataDetail[0].nama_barang : '-');
    $("#det_nobatch").text(dataDetail[0].no_batch ? dataDetail[0].no_batch : '-');
    $("#det_expired").text(dataDetail[0].expired_date ? dataDetail[0].expired_date : '-');
    $("#det_jml_masuk").text(dataDetail[0].jumlah_masuk ? dataDetail[0].jumlah_masuk : '-');
    $("#det_stok").text(dataDetail[0].stok_akhir ? dataDetail[0].stok_akhir : '-');
    var dataSatuan = getMasterById(jsonSatuan, dataDetail[0].id_satuan);
    $("#det_satuan").text(dataSatuan.nama);
    var dataKategori = getMasterById(jsonKategori, dataDetail[0].id_kategori_bahan);
    $("#det_kategori").text(dataKategori.nama);
    $("#det_deskripsi").text(dataDetail[0].keterangan ? dataDetail[0].keterangan : '-');
    var dataSupplier = getMasterById(jsonSupplier, dataDetail[0].id_supplier);
    $("#sup_nama").text(dataSupplier.nama);
    $("#sup_email").text(dataSupplier.email);
    $("#sup_notelp").text(dataSupplier.no_telp);
    $("#sup_alamat").text(dataSupplier.alamat);
    $("#Viewproduct").modal("show");
  }

  function getMasterById(jsonData, id){
    data = jsonData.filter(function(index) {return index.id == id});
    return data.length > 0 ? data[0] : false;
  }

  $("#myform").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Master_barang/Master/add')?>/";
    if ($("#id").val() != ""){
      action = "<?php echo base_url('Master_barang/Master/edit')?>/";
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
      success: function (data) {
        if (data.status == '3'){
          initDataTable.ajax.reload();
          $('#aSimpan').html('Simpan');
          $("#aSimpan").prop("disabled", false);
          $("#modalform").modal('hide');
          new PNotify({
            title: 'Sukses',
            text: notifText,
            type: 'success',
            hide: true,
            delay: 5000,
            styling: 'bootstrap3'
          });
        } else {
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
      url: '<?php echo base_url('Master_barang/Master/delete'); ?>/',
      data: {"id":i},
      dataType: 'json',
      beforeSend: function() {
        // kasi loading
        $("#aConfirm"+i).html("Sedang Menghapus...");
        $("#aConfirm"+i).prop("disabled", true);
      },
      success: function (data) {
        console.log(data);
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

  //Hack untuk bootstrap popover (popover hilang jika diklik di luar)
  $(document).on('click', function (e) {
    $('[data-toggle="popover"],[data-original-title]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
        }
    });
  });
</script>
