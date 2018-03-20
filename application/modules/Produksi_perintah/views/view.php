<style media="screen">
.multi-filter{
  width: 100%;
}
.filter-item{
  width: 24%;
  display: inline-block;
}
.filter-item select,
.filter-item input{
  margin: 0!important;
}
</style>
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
  <div class="row panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-2">
          <label for="filter_status" class="control-label" style="margin-top:5px;">Filter Status:</label>
        </div>
        <div class="col-sm-10">
          <div class="multi-filter">
            <div class="filter-item">
              <select class="form-control" name="" id="filter_status" data-column="4">
                <option value="" data-filter="0">Semua</option>
                <option value="approved" data-filter="approved">Disetujui</option>
                <option value="notapproved" data-filter="notapproved">Belum Disetujui</option>
              </select>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row" style="margin-top:10px;">
    <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="text-center no-sort">#</th>
          <th class="text-center no-sort">No. Dokumen</th>
          <th class="text-center no-sort">Revisi Ke</th>
          <th class="text-center no-sort">Tanggal Efektif</th>
          <th class="text-center no-sort">Status</th>
          <?php if($session_detail->id != 8 || strtolower($session_detail->nama) != 'marketing'): ?>
          <th class="text-center no-sort">Aksi</th>
          <?php endif; ?>
        </tr>
      </thead>

      <tbody id='bodytable'>
      </tbody>
    </table>
  </div>
  <?php if($session_detail->id == 5): ?>
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-add btn-lg"  onclick="showPilihTipe()">
    Tambah Dokumen
  </button>
  <?php endif; ?>
</div>
<!-- /.container -->
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
function filterColumn (i,keyword) {
  var tabel = $('#TableMainServer').DataTable();
  if(keyword!="0"){
    tabel.column(i).search(keyword, true, false ).draw();
  }else{
    tabel.draw();
  }
}
$('.filter-item select').on('change',function(){
  // var keyword = $('option:selected',this).attr('data-filter');
  // var keyword = $('#filter_status option:selected').attr('data-filter') +' '+ $('#filter_supplier option:selected').attr('data-filter') +' '+ $('#filter_kategori option:selected').attr('data-filter');
  var keyword='';
  var k1 = $('#filter_status option:selected').attr('data-filter');
  if(k1!=0){
    keyword+=k1;
  }
  filterColumn($(this).attr('data-column'),keyword);
  // console.log(keyword);
})
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
