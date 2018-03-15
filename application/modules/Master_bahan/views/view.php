<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Master</strong> - Semua Bahan</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <!-- <th class="text-center no-sort">Foto</th> -->
                  <th class="text-center">Nama Bahan</th>
                  <th class="text-center">Kategori Bahan</th>
                  <!-- <th class="text-center">Stok</th> -->
                  <th class="text-center no-sort hidden-xs">Tanggal Buat</th>
                  <th class="text-center no-sort">Aksi</th>
              </tr>
          </thead>

          <tbody id='bodytable'>

          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <button type="button" class="btn btn-add btn-lg"  onclick="showAdd()">
     Tambah Bahan
   </button>
</div>
<!-- /.container -->
<!-- Modal Detail Bahan baku -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document" id="viewModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="view">Detail Bahan</h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div id="viewSectionProduct">
              <!-- view goes here -->
              <div class="col-md-12"><div class="media">
                 <!-- <div class="media-left">
                    <img id="det_foto" class="media-object img-rounded" src="<?php echo base_url()?>upload/bahan_baku/placeholder.png" alt="image" width="200px">
                 </div> -->
                 <div class="media-body">
                  <h1 class="media-heading" id="det_nama"></h1>
                  <div class="row">
                    <div class="col-sm-6">
                      <p><b>Kode Bahan :</b> <span id="det_kode"></span></p>
                      <p><b>Satuan :</b> <span id="det_satuan"></span></p>
                      <p><b>Kategori :</b> <span id="det_kategori"></span></p>
                      <p><b>Jumlah Masuk :</b> <span id="det_jml_masuk"></span></p>
                      <p><b>Jumlah Keluar :</b> <span id="det_jml_keluar"></span></p>
                      <p><b>Saldo Bulan <span id="det_tahunbulan_sekarang"></span> :</b> <span id="det_saldo_sekarang"></span></p>
                      <p><b>Saldo Bulan <span id="det_tahunbulan_kemarin"></span> :</b> <span id="det_saldo_kemarin"></span></p>
                      <p><b>Tanggal Datang :</b> <span id="det_tanggal_datang"></span></p>
                      <p><b>Expired Date :</b> <span id="det_expired"></span></p>
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
        <h4 class="modal-title" id="myModalLabel">Tambah Bahan</h4>
      </div>
      <form action="" method="POST" id="myform" enctype="multipart/form-data"> <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama">Nama Bahan</label>
                 <input type="text" name="nama" maxlength="50" Required class="form-control" id="nama">
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Bahan">
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
                 <label for="id_satuan">Satuan</label>
                 <select name="id_satuan" class="form-control" id="id_satuan" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Kode</label>
                 <input type="text" name="kode_bahan" maxlength="50" class="form-control" id="kode_bahan">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Jumlah Keluar</label>
                 <input type="text" name="jumlah_keluar" maxlength="50" Required class="form-control" id="jumlah_keluar">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Jumlah Masuk</label>
                 <input type="text" name="jumlah_masuk" maxlength="50" Required class="form-control" id="jumlah_masuk">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Saldo Bulan Sebelumnya</label>
                 <input type="text" name="saldo_kemarin" maxlength="50" Required class="form-control" id="saldo_kemarin">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Saldo Bulan Ini</label>
                 <input type="text" name="saldo_sekarang" maxlength="50" Required class="form-control" id="saldo_sekarang">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Tgl. Datang</label>
                 <input type="text" name="tgl_datang" maxlength="50" class="form-control datepicker" id="tgl_datang">
                 </select>
               </div>
             </div>
            <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Expired Date</label>
                 <input type="text" name="expired_date" maxlength="50" class="form-control datepicker" id="expired_date">
                 </select>
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
  // initialize datatable
  var table = $("#TableMainServer").DataTable({
    "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": "no-sort"
        } ],
        // "order": [[ 1, 'asc' ]]
  });
  table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<span style='display:block' class='text-center'>"+(i+1)+"</span>";
        } );
  } ).draw();

  var jsonlist = <?php echo $list; ?>;
  var jsonSatuan = <?php echo $list_satuan; ?>;
  var jsonKategori = <?php echo $list_kategori; ?>;

  var awalLoad = true;
  loadData(jsonlist);

  function loadData(json){
    //clear table
    if(json != null) {
    table.clear().draw();
    for(var i=0;i<json.length;i++){
        table.row.add( [
            "",
            json[i].nama_bahan,
            json[i].kategori.nama,
            // "Tanggal Buat " + i,
            // json[i].nama,
            // json[i].alamat,
            // json[i].no_telp,
            // json[i].email,
            // json[i].npwp,
            // json[i].nama_bank,
            DateFormat.format.date(json[i].date_add, "dd-MM-yyyy HH:mm"),
            '<td class="text-center"><div class="btn-group" >'+
                '<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Detail Data" onclick="showDetail('+i+')"><i class="fa fa-file-text-o"></i></a>'+
                '<a id="group'+json[i].id+'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'+
                '<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('+i+')"><i class="fa fa-pencil"></i></a>'+
               '</div>'+
            '</td>'
        ] ).draw( false );
    }
    if (!awalLoad){
      $('.divpopover').attr("data-content","ok");
      $('.divpopover').popover();
    }
    awalLoad = false;
    }
  }

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
    load_select_option(jsonSatuan, "#id_satuan", "Satuan");
    load_select_option(jsonKategori, "#id_kategori", "Kategori");
  }
  function showAdd(){
    load_select();
    $("#myModalLabel").text("Tambah Bahan");
    $("#id").val("");
    $("#nama").val("");
    $("#id_satuan").val("");
    $("#id_kategori").val("");
    $("#kode_bahan").val("");
    $("#jumlah_masuk").val("");
    $("#jumlah_keluar").val("");
    $("#saldo_kemarin").val("");
    $("#saldo_sekarang").val("");
    $("#tgl_datang").val("");
    $("#expired_date").val("");
    $("#modalform").modal("show");
  }
  function showDetail(i) {
    var data = jsonlist[i];
    $("#det_nama").text(data.nama_bahan);
    $("#det_kode").text(data.kode_bahan);
    var dataSatuan = getMasterById(jsonSatuan, data.id_satuan);
    $("#det_satuan").text(dataSatuan.nama);
    var dataKategori = getMasterById(jsonKategori, data.kategori.id);
    $("#det_kategori").text(dataKategori.nama);
    $("#det_jml_masuk").text(data.jumlah_masuk);
    $("#det_jml_keluar").text(data.jumlah_keluar);
    var getTanggal = data.tanggal.split('-');
    var thisMonth = parseInt(getTanggal[1]);
    var prevMonth = getTanggal[1] - 1;
    $("#det_tahunbulan_sekarang").text('('+getMonth(thisMonth)+' '+getTanggal[0]+')');
    $("#det_tahunbulan_kemarin").text('('+getMonth(prevMonth)+' '+getTanggal[0]+')');
    $("#det_saldo_sekarang").text(data.saldo_bulan_sekarang);
    $("#det_saldo_kemarin").text(data.saldo_bulan_kemarin);
    var tanggal_datang = data.tanggal_datang != '0000-00-00' ? explodeDate(data.tanggal_datang) : 'Tidak di setting';
    var expired_date = data.expired_date != '0000-00-00' ? explodeDate(data.expired_date) : 'Tidak di setting';
    $("#det_tanggal_datang").text(tanggal_datang);
    $("#det_expired").text(expired_date);
    $("#detailModal").modal("show");
  }
  function getMonth(n) {
    var month = new Array();
    month[1] = "Januari";
    month[2] = "Febuari";
    month[3] = "Maret";
    month[4] = "April";
    month[5] = "Mei";
    month[6] = "Juni";
    month[7] = "Juli";
    month[8] = "Agustus";
    month[9] = "September";
    month[10] = "Oktober";
    month[11] = "November";
    month[12] = "Desember";

    return month[n];
  }
  function showUpdate(i){
    load_select();
    $("#myModalLabel").text("Ubah Bahan");
    $("#id").val(jsonlist[i].id);
    $("#nama").val(jsonlist[i].nama_bahan);
    $("#id_satuan").val(jsonlist[i].id_satuan);
    $("#id_kategori").val(jsonlist[i].kategori.id);
    $("#kode_bahan").val(jsonlist[i].kode_bahan);
    $("#jumlah_masuk").val(jsonlist[i].jumlah_masuk);
    $("#jumlah_keluar").val(jsonlist[i].jumlah_keluar);
    $("#saldo_kemarin").val(jsonlist[i].saldo_bulan_kemarin);
    $("#saldo_sekarang").val(jsonlist[i].saldo_bulan_sekarang);
    var tanggal_datang = explodeDate(jsonlist[i].tanggal_datang);
    var expired_date = explodeDate(jsonlist[i].expired_date);
    $("#tgl_datang").val(tanggal_datang);
    $("#expired_date").val(expired_date);
    $("#modalform").modal("show");
  }
  function explodeDate(param) {
    var x = param.split('-');
    var result = x[2] + '/' + x[1] + '/' + x[0];
    return result;
  }
  function getMasterById(jsonData, id){
    data = jsonData.filter(function(index) {return index.id == id});
    return data.length > 0 ? data[0] : false;
  }
  function confirmDelete(el){
    var element = $(el).attr("id");
    var id  = element.replace("group","");
    var i = parseInt(id);
    $(el).attr("data-content","<button class=\'btn btn-danger myconfirm\'  href=\'#\' onclick=\'deleteData(this)\' id=\'aConfirm"+i+"\' style=\'min-width:85px\'><i class=\'fa fa-trash\'></i> Ya</button>");
    $(el).popover();
  }

  function deleteData(element){
    var el = $(element).attr("id");
    var id  = el.replace("aConfirm","");
    var i = parseInt(id);
    $.ajax({
          type: 'post',
          url: '<?php echo base_url('Master_bahan/Master/delete'); ?>/',
          data: {"id":i},
          dataType: 'json',
          beforeSend: function() {
            // kasi loading
            $("#aConfirm"+i).html("Sedang Menghapus...");
            $("#aConfirm"+i).prop("disabled", true);
          },
          success: function (data) {
            if (data.status == '3'){
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
              table.clear().draw();
              jsonList = data.list;
              loadData(jsonList);
            }
          }
        });
  }


  $("#myform").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Master_bahan/Master/add')?>/";
    if ($("#id").val() != ""){
      action = "<?php echo base_url('Master_bahan/Master/edit')?>/";
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
          jsonlist = data.list;
          loadData(jsonlist);
          $("#modalform").modal('hide');
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
        } else if(data.status == 1){
          $("#myform")[0].reset();
          new PNotify({
            title: 'Gagal',
            text: data.message,
            type: 'warning',
            hide: true,
            delay: 5000,
            styling: 'bootstrap3'
          });
        };
        $('#aSimpan').html('Simpan');
        $("#aSimpan").prop("disabled", false);
      }
    });
  });

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
