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
          <th class="text-center">Nama Barang</th>
          <th class="text-center">No. Purchase Order</th>
          <th class="text-center">No. Sales Order</th>
          <th class="text-center">Harga</th>
          <th class="text-center">Tanggal Expired</th>
          <th class="text-center">Stok</th>
          <th class="text-center no-sort" width="130">Aksi</th>
        </tr>
      </thead>

      <tbody id='bodytable'>
        <tr>
          <td>1</td>
          <td>Paramex</td>
          <td>220-2</td>
          <td>SR443-22</td>
          <td>30.000</td>
          <td>12/03/2019</td>
          <td>232300</td>
          <td>
            <div class="btn-group">
              <a id="group" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>
              <a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate()"><i class="fa fa-pencil"></i></a>
              <a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Lihat Detail" onclick="showDetail()"><i class="fa fa-file-text-o"></i></a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-add btn-lg"  onclick="showAdd()">
    Tambah Produk
  </button>
</div>
<!-- /.container -->
<!-- Modal Detail -->
<div class="modal fade" id="Viewproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" id="viewModal">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="view">Detail Produk</h4>
      </div>
      <div class="modal-body" id="modal-body">
        <div id="viewSectionProduct">
          <!-- view goes here -->
          <div class="media">
            <div class="media-body">
              <h1 class="media-heading" id="det_nama">Nama Produk</h1>
              <div class="row">
                <div class="col-sm-12">
                  <p><b>No. Purchase Order :</b> <span id="det_purchase"></span></p>
                  <p><b>No. Sales Order :</b> <span id="det_sales"></span></p>
                  <p><b>Harga :</b> <span id="det_harga"></span></p>
                  <p><b>Tanggal Expired:</b> <span id="det_expired"></span></p>
                  <p><b>Stok :</b> <span id="det_stok"></span></p>
                </div>
              </div>
            </div>
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
        <h4 class="modal-title" id="myModalLabel">Tambah Produk</h4>
      </div>
      <form action="" method="POST" id="myform" enctype="multipart/form-data"> <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="nama">Nama Barang</label>
              <input type="text" name="nama" maxlength="50" Required class="form-control" id="nama" placeholder="Nama Barang">
              <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Barang">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="no_purchase">No. Purchase Order</label>
              <input type="text" name="no_purchase" maxlength="50" Required class="form-control" id="no_purchase" placeholder="No. Purchase Order">

            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="no_sales">No. Sales Order</label>
              <input type="text" name="no_sales" maxlength="50" Required class="form-control" id="no_sales" placeholder="No. Sales Order">

            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="harga">Harga</label>
              <input type="text" name="harga" maxlength="50" Required class="form-control" id="harga" placeholder="Harga">

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
              <label for="stok">Stok</label>
              <input type="text" name="stok" maxlength="50" Required class="form-control" id="stok" placeholder="Stok">
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
function showAdd(){
  $('#modalform').modal('show');
}
function showDetail(){
  $('#Viewproduct').modal('show');
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
