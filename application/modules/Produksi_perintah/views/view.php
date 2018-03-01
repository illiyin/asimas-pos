<!-- Page Content -->
<div class="container">
  <div class="row" style='min-height:80px;'>
    <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
      <strong>Sukses!</strong> Data berhasil disimpan
    </div>
  </div>
  <div class="row">
    <h3><strong>Produksi</strong> - Perintah Produksi</h3>
  </div>
  <div class="row" style="margin-top:10px;">
    <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="text-center no-sort">#</th>
          <th class="text-center">No. Dokumen</th>
          <th class="text-center">Revisi Ke</th>
          <th class="text-center">Tanggal Efektif</th>
          <th class="text-center no-sort">Aksi</th>
        </tr>
      </thead>

      <tbody id='bodytable'>
        <!-- <tr>
          <td>1</td>
          <td><a href="Produksi_perintah-master-detail" title="Detail dan setujui">ZXS-234</a></td>
          <td>1</td>
          <td>12/03/2018</td>
          <td>
            <div class="btn-group" >
              <a id="group" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>
              <a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate()"><i class="fa fa-pencil"></i></a>
              <a href="Produksi_perintah-master-cetak" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fa fa-print"></i></a>
            </div>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td><a href="Produksi_perintah-master-detail" title="Detail dan setujui">ZXS-234</a></td>
          <td>1</td>
          <td>12/03/2018</td>
          <td>
            <div class="btn-group" >
              <a id="group" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>
              <a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate()"><i class="fa fa-pencil"></i></a>
              <a href="Produksi_perintah-master-cetak" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fa fa-print"></i></a>
            </div>
          </td>
        </tr> -->
      </tbody>
    </table>
  </div>
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-add btn-lg"  onclick="showPilihTipe()">
    Tambah Dokumen
  </button>
</div>
<!-- /.container -->
<!-- Modal Detail Kategori Bahan baku -->
<div class="modal fade" id="Viewproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" id="viewModal">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="view">Detail Kategori Bahan</h4>
      </div>
      <div class="modal-body" id="modal-body">
        <div id="viewSectionProduct">
          <!-- view goes here -->
          <div class="col-md-12"><div class="media">
            <!-- <div class="media-left">
            <img id="det_foto" class="media-object img-rounded" src="<?php echo base_url()?>upload/bahan_baku/placeholder.png" alt="image" width="200px">
          </div> -->
          <div class="media-body">
            <h1 class="media-heading" id="det_nama">sfsdg</h1>
            <div class="row">
              <div class="col-sm-6">
                <p><b>Kode :</b> <span id="det_kategori"></span></p>
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
<div class="modal fade" id="modalPilihTipe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Pilih Tipe Dokumen</h4>
      </div>
      <form action="" method="POST" id="myform" enctype="multipart/form-data"> <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <a href="Produksi_perintah-master-perintahbaru" class="btn btn-primary btn-lg btn-block">Buat Baru</a>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <a href="Produksi_perintah-master-perintahrevisi" class="btn btn-default btn-lg btn-block">Revisi</a>
            </div>
          </div>


        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.Modal Add-->
<script type="text/javascript">
function showPilihTipe() {
  $('#modalPilihTipe').modal('show');
}
var initDataTable = $('#TableMainServer').DataTable({
    "bProcessing": true,
    "bServerSide": true,
    // "order": [[3, 'DESC']],
    "ajax":{
          url :"<?php echo base_url()?>Produksi_perintah/Master/data",
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
    url: '<?php echo base_url('Produksi_perintah/Master/delete'); ?>/',
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
