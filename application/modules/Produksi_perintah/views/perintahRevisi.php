<div class="container" style="margin-top:10px;margin-bottom:20px;">
  <h2>Dokumen Revisi</h2>
  <form class="form-horizontal" action="<?= base_url() ?>index/modul/Produksi_perintah-master-index" method="post">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">No. Dokumen</label>
          </div>
          <div class="col-sm-9">
            <select name="" id="" class="form-control">
              <option value="1">ZXS-234</option>
              <option value="2">ZXS-235</option>
              <option value="3">ZXS-236</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">Revisi Ke</label>
          </div>
          <div class="col-sm-9">
            <label for="" class="control-label">01</label>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">Tanggal Efektif</label>
          </div>
          <div class="col-sm-9">
            <div class="input-group">
              <input type="text" class="form-control datepicker" name="" id="">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">No. Perintah Produksi</label>
          </div>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="" id="">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">No. Sales Order</label>
          </div>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="" id="">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">Estimasi Proses</label>
          </div>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="" id="">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">Nama Produk</label>
          </div>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="" id="">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">Besar Batch</label>
          </div>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="" id="">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">Kode Produksi</label>
          </div>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="" id="">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3">
            <label for="" class="control-label">Expire Date</label>
          </div>
          <div class="col-sm-9">
            <div class="input-group">
              <input type="text" class="form-control datepicker" name="" id="">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="">
      <button class="btn btn-warning" onclick="showBahanBaku()" type="button">Tambah Bahan Baku</button>
      <button class="btn btn-warning" onclick="showBahanKemas()" type="button">Tambah Bahan Kemas</button>
    </div>


    <div class="tambahan-bahan-baku">
      <h3>Bahan Baku:</h3>
      <table class="table">
        <tr>
          <td>1</td>
          <td>Simplisia ABM</td>
          <td>Per Kaplet: 400mg</td>
          <td>Per Batch: 48kg</td>
          <td>Per Lot: 0</td>
          <td>Jumlah Lot: 1</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Simplisia ABM</td>
          <td>Per Kaplet: 400mg</td>
          <td>Per Batch: 48kg</td>
          <td>Per Lot: 0</td>
          <td>Jumlah Lot: 1</td>
        </tr>
      </table>

    </div>
    <div class="tambahan-bahan-kemas">
      <h3>Bahan Kemas:</h3>
      <table class="table">
        <tr>
          <td>1</td>
          <td>Botol Ester C</td>
          <td>Jumlah: 2000pcs</td>
          <td>Aktual: 2100pcs</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Botol Ester D</td>
          <td>Jumlah: 2000pcs</td>
          <td>Aktual: 2100pcs</td>
        </tr>
      </table>

    </div>

    <div class="panel panel-default">
      <div class="panel-body text-right">
        <a href="Produksi_perintah-master-index" class="btn btn-default">Kembali</a>
        <button class="btn btn-success">Submit Data</button>
      </div>
    </div>
  </form>
</div>

<!-- Modal tambah bahan baku -->
<div class="modal fade" id="modalBahanBaku" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Bahan Baku</h4>
      </div>
      <form action="" method="POST" id="myform" enctype="multipart/form-data" class="form-horizontal">
        <div class="modal-body">
          <div class="form-group">
            <div class="col-sm-3">
              <label for="">Nama Bahan</label>
            </div>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="" id="">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-3">
              <label for="">Per Kaplet</label>
            </div>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="" id="">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-3">
              <label for="">Satuan</label>
            </div>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="" id="">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-3">
              <label for="">Per Batch</label>
            </div>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="" id="">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-3">
              <label for="">Satuan</label>
            </div>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="" id="">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-3">
              <label for="">Jumlah Lot</label>
            </div>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="" id="">
            </div>
          </div>
        </div>
        <div class="modal-footer text-right">
          <button class="btn btn-default" data-dismiss="modal">Close</button>
          <button class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
  <!-- /.tutup Modal-->
  <!-- Modal tambah bahan kemas -->
  <div class="modal fade" id="modalBahanKemas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Tambah Bahan Kemas</h4>
        </div>
        <form action="" method="POST" id="myform" enctype="multipart/form-data" class="form-horizontal">
          <div class="modal-body">
            <div class="form-group">
              <div class="col-sm-3">
                <label for="">Nama Bahan</label>
              </div>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="" id="">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-3">
                <label for="">Jumlah</label>
              </div>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="" id="">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-3">
                <label for="">Satuan</label>
              </div>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="" id="">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-3">
                <label for="">Aktual</label>
              </div>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="" id="">
              </div>
            </div>
          </div>
          <div class="modal-footer text-right">
            <button class="btn btn-default" data-dismiss="modal">Close</button>
            <button class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /.tutup Modal-->
  <script type="text/javascript">
  function showBahanBaku() {
    $('#modalBahanBaku').modal('show');
  }
  function showBahanKemas() {
    $('#modalBahanKemas').modal('show');
  }
</script>
