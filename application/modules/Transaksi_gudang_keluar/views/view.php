<!-- Page Content -->
<div class="container">
  <div class="row" style='min-height:80px;'></div>
  <div class="row">
    <h3><strong>Transaksi</strong> - Keluar Gudang</h3>
  </div>
  <div class="row" style="margin-top:10px;">
    <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="text-center">No. Transaksi</th>
          <th class="text-center">Tanggal</th>
          <th class="text-center">Nama Bahan</th>
          <th class="text-center">Satuan</th>
          <th class="text-center">Jumlah Keluar</th>
          <th class="text-center">No. Batch</th>
          <th class="text-center">Expire Date</th>
          <th class="text-center">Kode Bahan</th>
          <th class="text-center">Nama Distributor</th>
          <th class="text-center">Keterangan</th>
          <th class="text-center hidden-xs no-sort">Aksi</th>
        </tr>
      </thead>

      <tbody id='bodytable'>
        <tr>
          <td class="text-center">1</td>
          <td>11/11/2019</td>
          <td>Brotowali</td>
          <td>kg</td>
          <td>234</td>
          <td>ACD-121</td>
          <td>21/11/2019</td>
          <td>xx-2212</td>
          <td>PT. Distribusi Indonesia</td>
          <td>Kurang terang</td>
          <td class="text-center hidden-xs no-sort">
            <div class="btn-group" >
              <a id="group" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>
              <a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate()"><i class="fa fa-pencil"></i></a>
            </div>
          </td>
        </tr>
        <tr>
          <td class="text-center">2</td>
          <td>11/11/2019</td>
          <td>Brotowali</td>
          <td>kg</td>
          <td>234</td>
          <td>ACD-121</td>
          <td>21/11/2019</td>
          <td>xx-2212</td>
          <td>PT. Distribusi Indonesia</td>
          <td>Kurang terang</td>
          <td class="text-center hidden-xs no-sort">
            <div class="btn-group" >
              <a id="group" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>
              <a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate()"><i class="fa fa-pencil"></i></a>
            </div>
          </td>
        </tr>
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
            <div class="col-sm-6">
              <div class="form-group">
                <label for="no_transaksi">No. Transaksi</label>
                <input type="text" class="form-control" name="no_transaksi" id="no_transaksi" placeholder="No. Transaksi">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <div class="input-group">
                  <input type="text" class="form-control datepicker" name="tanggal" id="tanggal" placeholder="dd/mm/yyyy">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="nama_bahan">Nama Bahan</label>
                <select name="nama_bahan" class="form-control" id="nama_bahan" required="required">
                  <option value="">-- Pilih Bahan --</option>
                  <option value="1">Bahan 1</option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="id_satuan">Satuan</label>
                <select name="id_satuan" class="form-control" id="id_satuan" required="required">
                  <option value="">-- Pilih Satuan --</option>
                  <option value="1">Satuan 1</option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="jumlah_keluar">Jumlah Keluar</label>
                <input type="text" class="form-control" name="jumlah_keluar" id="jumlah_keluar" placeholder="Jumlah Keluar">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="no_batch">No. Batch</label>
                <input type="text" class="form-control" name="no_batch" id="no_batch" placeholder="No. Batch">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="expire_date">Expire Date</label>
                <div class="input-group">
                  <input type="text" class="form-control datepicker" name="expire_date" id="expire_date" placeholder="dd/mm/yyyy">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="nama_distributor">Nama Distributor</label>
                <select name="nama_distributor" class="form-control" id="nama_distributor" required="required">
                  <option value="">-- Pilih Distributor --</option>
                  <option value="1">Distributor 1</option>
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
  function showAdd(){
    $('#modalAdd').modal('show');
  }
  function showUpdate(){
    $('#modalAdd').modal('show');
  }
  </script>
