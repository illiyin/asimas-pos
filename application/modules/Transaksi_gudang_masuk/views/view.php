<!-- Page Content -->
<div class="container">
  <div class="row" style='min-height:80px;'></div>
  <div class="row">
    <h3><strong>Transaksi</strong> - Gudang Masuk</h3>
  </div>
  <div class="row" style="margin-top:10px;">
    <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="text-center">No. Transaksi</th>
          <th class="text-center">Nama Barang</th>
          <th class="text-center">Nama Supplier</th>
          <th class="text-center">Satuan</th>
          <th class="text-center">Jumlah Masuk</th>
          <th class="text-center">No. Batch</th>
          <th class="text-center">Expire Date</th>
          <th class="text-center">Kode Bahan</th>
          <th class="text-center">Nama Produsen</th>
          <th class="text-center">Keterangan</th>
          <th class="text-center hidden-xs no-sort">Aksi</th>
        </tr>
      </thead>

      <tbody id='bodytable'>
        <tr>
          <td class="text-center">1</td>
          <td>Brotowali</td>
          <td>PT. Supplier</td>
          <td>kg</td>
          <td>234</td>
          <td>ACD-121</td>
          <td>21/11/2019</td>
          <td>xx-2212</td>
          <td>PT. Pembikin Bahan</td>
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
          <td>Brotowali</td>
          <td>PT. Supplier</td>
          <td>kg</td>
          <td>234</td>
          <td>ACD-121</td>
          <td>21/11/2019</td>
          <td>xx-2212</td>
          <td>PT. Pembikin Bahan</td>
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
</div>
<!-- /.container -->
<!-- Modal Ubah -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Ubah Stok Produk</h4>
      </div>
      <form action="<?php echo base_url('Transaksi_barangmasuk/Transaksi/ubahStok') ?>" method="POST" id="myform">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="id_warna">Warna</label>
                <select name="id_warna" class="form-control" id="id_warna" required="required">
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="id_ukuran">Ukuran</label>
                <select name="id_ukuran" class="form-control" id="id_ukuran" required="required">
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label for="nama">Jumlah QTY</label>
                <input type="number" name="qty" maxlength="50" Required class="form-control" id="qty" placeholder="Stok Produk">
                <input type="hidden" name="state" maxlength="50" Required class="form-control" id="state">
                <input type="hidden" name="idProduk" maxlength="50" Required class="form-control" id="idProduk">
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
