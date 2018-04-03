<!-- Page Content -->
<div class="container">
  <div class="row" style='min-height:80px;'>
    <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
      <strong>Sukses!</strong> Data berhasil disimpan
    </div>
  </div>
  <div class="row">
    <h3><strong>Master</strong> - Produk Jadi</h3>
  </div>
  <div class="row" style="margin-top:10px;">
    <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="text-center no-sort">#</th>
          <th class="text-center">Nama Bahan</th>
          <th class="text-center">No. Sales Order</th>
          <th class="text-center">Harga Per Item</th>
          <th class="text-center">Tanggal Expired</th>
          <th class="text-center">Jumlah Masuk</th>
          <th class="text-center">Jumlah HPP</th>
          <?php if($session_detail->id != 8 || strtolower($session_detail->nama) != 'marketing'): ?>
          <th class="text-center no-sort">Aksi</th>
          <?php endif; ?>
        </tr>
      </thead>

      <tbody id='bodytable'>
      </tbody>
    </table>
  </div>
</div>
<!-- Modal Add -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <form action="" method="POST" id="myform" enctype="multipart/form-data"> <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="harga">Harga Pembelian</label>
              <input type="text" name="harga" maxlength="50" Required class="form-control" id="harga" placeholder="Harga Pembelian" onkeydown="return numericOnly(event)">
              <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Barang">
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
<!-- /.container -->

<script type="text/javascript">
var list_data = <?php echo json_encode($list_data); ?>;
var initDataTable = $('#TableMainServer').DataTable({
  "bProcessing": true,
  "bServerSide": true,
  // "order": [[4, 'DESC']],
  "ajax":{
        url :"<?php echo base_url()?>Master_produk_jadi/Master/data",
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

function showAdd(id){
  var data = list_data.filter(function (index) { return index.id == id })[0];
  $("#id").val(data.id);
  $("#harga").val(data.harga_pembelian);
  $("#myModalLabel").text("Ubah Harga Pembelian");
  $('#modalform').modal('show');
}
$("#myform").on('submit', function(e){
    e.preventDefault();
    action = "<?php echo base_url('Master_produk_jadi/Master/edit')?>/";
    var params = new FormData(jQuery('#myform')[0]);

    $.ajax({
      url: action,
      type: 'post',
      data: params,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function (data) {
        if(data.status == 3) {
          list_data = data.list
        }
        initDataTable.ajax.reload();
        $("#modalform").modal('hide');
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
