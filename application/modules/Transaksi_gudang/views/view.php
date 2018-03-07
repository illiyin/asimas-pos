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
.mb-20{
  margin-bottom: 20px;
}
#customdate{
  display: none;
  background-color: #efefef;
  padding: 10px;
}
#customdate.open{
  display: block;
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
    <div class="col-sm-6">
      <h3><strong>Transaksi</strong> - Laporan Transaksi Gudang</h3>
    </div>
    <div class="col-sm-6 text-right">
      <button class="btn btn-primary" onclick="showCetakChoice()"><i class="fa fa-print"></i> Cetak Laporan</button>
    </div>
  </div>
  <div class="row panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-9">
          <div class="multi-filter">
            <div class="filter-item">
              <input type="text" class="form-control" placeholder="Search..." id="search_global">
            </div>
            <div class="filter-item">
              <select class="form-control" name="" id="filter_bahan" data-column="1">
                <option value="" data-filter="0">Filter Bahan</option>
                <option value="bahan_a" data-filter="Brotowali">Brotowali</option>
                <option value="bahan_b" data-filter="Biskuit AMB">Biskuit AMB</option>
              </select>

            </div>
            <div class="filter-item">
              <select class="form-control" name="" id="filter_supplier" data-column="1">
                <option value="" data-filter="0">Filter Supplier</option>
                <option value="sup_a" data-filter="Supplier A">Supplier A</option>
                <option value="sup_b" data-filter="Supplier B">Supplier B</option>
              </select>

            </div>
            <div class="filter-item">
              <select class="form-control" name="" id="filter_kategori" data-column="1">
                <option value="" data-filter="0">Filter Kategori</option>
                <option value="kategori_a" data-filter="Kategori A">Kategori A</option>
                <option value="kategori_b" data-filter="Kategori B">Kategori B</option>
              </select>
            </div>

          </div>
        </div>

        <div class="col-sm-3">
          <div class="filter-date">
            <div class="input-group">
              <input type="text" class="form-control datepicker" name="" placeholder="dd/mm/yyyy">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
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
          <th class="text-center">Nama Bahan</th>
          <th class="text-center">Kode Bahan</th>
          <th class="text-center">Satuan</th>
          <th class="text-center">Stok Awal</th>
          <th class="text-center">Keluar</th>
          <th class="text-center">Masuk</th>
          <th class="text-center">Stok Akhir</th>
          <th class="text-center">Batch</th>
          <th class="text-center">Expire Date</th>
          <th class="text-center">Keterangan</th>
          <th class="text-center">Harga</th>
        </tr>
      </thead>

      <tbody id='bodytable'>
        <tr>
          <td>1</td>
          <td data-search="Brotowali Supplier A Kategori B">Brotowali</td>
          <td>XX-3s</td>
          <td>g</td>
          <td>300</td>
          <td>420</td>
          <td>50</td>
          <td>50</td>
          <td>50</td>
          <td>12/12/2019</td>
          <td>Sangat Terang</td>
          <td>300.000</td>
        </tr>
        <tr>
          <td>2</td>
          <td data-search="Biskuit AMB Supplier B Kategori A">Biskuit AMB</td>
          <td>XX-3s</td>
          <td>kg</td>
          <td>300</td>
          <td>420</td>
          <td>50</td>
          <td>50</td>
          <td>50</td>
          <td>12/12/2019</td>
          <td>Terang</td>
          <td>200.000</td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- Button trigger modal -->
  <!-- <button type="button" class="btn btn-add btn-lg"  onclick="showPilihTipe()">
  Tambah Dokumen
=======
          <!-- <td>
          <div class="btn-group" >
          <a id="group" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>
          <a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate()"><i class="fa fa-pencil"></i></a>
        </div>
      </td> -->
    </tr>
    <tr>
      <td>2</td>
      <td data-search="Biskuit AMB Supplier B Kategori A">Biskuit AMB</td>
      <td>XX-3s</td>
      <td>kg</td>
      <td>300</td>
      <td>23</td>
      <td>420</td>
      <td>50</td>
      <td>50</td>
      <td>50</td>
      <td>12/12/2019</td>
      <td>Terang</td>
      <td>200.000</td>
      <!-- <td>
      <div class="btn-group" >
      <a id="group" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>
      <a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate()"><i class="fa fa-pencil"></i></a>
    </div>
  </td> -->
</tr>
</tbody>
</table>
</div>
<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-add btn-lg"  onclick="showPilihTipe()">
Tambah Dokumen
>>>>>>> 89437afed5fb6e0e990e7b7c914558448bc79e9d
</button> -->
</div>
<!-- /.container -->
<!-- Modal Pilihan Cetak -->
<div class="modal fade" id="cetakChoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" id="viewModal">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="view">Cetak Laporan</h4>
      </div>
      <div class="modal-body" id="modal-body">
        <div class="row">
          <div class="col-xs-6">
            <button onclick="cetakDaily()" class="mb-20 btn btn-warning btn-block btn-lg" target="_blank">Hari Ini</button>
          </div>
          <div class="col-xs-6">
            <button onclick="cetakWeekly()" class="mb-20 btn btn-info btn-block btn-lg" target="_blank">Minggu Ini</button>
          </div>
          <div class="col-xs-6">
            <button onclick="cetakMonthly()" class="mb-20 btn btn-danger btn-block btn-lg" target="_blank">Bulan Ini</button>
          </div>
          <div class="col-xs-6">
            <button class="mb-20 btn btn-default btn-block btn-lg" id="btnSesuaikan">Sesuaikan</button>
          </div>
          <div class="col-xs-12">
            <div id="customdate">
              <div class="input-daterange input-group input-group-lg" id="datepicker">
                <input type="text" class="input-sm form-control" name="start" placeholder="Mulai Tanggal"/>
                <span class="input-group-addon">s/d</span>
                <input type="text" class="input-sm form-control" name="end" placeholder="Sampai Tanggal"/>
                <span class="input-group-btn">
                  <button onclick="cetakCustom()" class="btn btn-primary">Cetak</button>
                </span>
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


<script type="text/javascript">
function showCetakChoice() {
  $('#cetakChoice').modal('show');
}
function filterColumn (i,keyword) {
  var tabel = $('#TableMainServer').DataTable();
  if(keyword!="0"){
    tabel.column(i).search(keyword, true, false ).draw();
  }else{
    tabel.draw();
  }
}
function filterGlobal (keyword) {
  var tabel = $('#TableMainServer').DataTable();
  if(keyword!="0"){
    tabel.search(keyword, true, false ).draw();
  }else{
    tabel.draw();
  }
}
$(document).ready(function() {
  $('#TableMainServer').DataTable({
    dom: "lrtip",
  });
  $('.filter-item select').on('change',function(){
    // var keyword = $('option:selected',this).attr('data-filter');
    // var keyword = $('#filter_bahan option:selected').attr('data-filter') +' '+ $('#filter_supplier option:selected').attr('data-filter') +' '+ $('#filter_kategori option:selected').attr('data-filter');
    var keyword='';
    var k1 = $('#filter_bahan option:selected').attr('data-filter');
    var k2 = $('#filter_supplier option:selected').attr('data-filter');
    var k3 = $('#filter_kategori option:selected').attr('data-filter');
    if(k1!=0){
      keyword+=k1;
    }
    if(k2!=0){
      keyword+=' '+k2;
    }
    if(k3!=0){
      keyword+=' '+k3;
    }
    filterColumn($(this).attr('data-column'),keyword);
    // console.log(keyword);
  })

  $('#search_global').on('keyup click',function(){
    var keyword = $(this).val();
    filterGlobal(keyword)
  })

});

$('#customdate .input-daterange').datepicker({
  format: "dd/mm/yyyy"
});

function cetakDaily(){
  window.open("<?php echo base_url()?>index/modul/Transaksi_gudang-master-cetak", "_blank");
}
function cetakWeekly(){
  window.open("<?php echo base_url()?>index/modul/Transaksi_gudang-master-cetak", "_blank");
}
function cetakMonthly(){
  window.open("<?php echo base_url()?>index/modul/Transaksi_gudang-master-cetak", "_blank");
}
function cetakCustom(){
  window.open("<?php echo base_url()?>index/modul/Transaksi_gudang-master-cetak", "_blank");
}

$('#btnSesuaikan').on('click',function(){
  var elem = $('#customdate');
  if(elem.hasClass('open')){
    elem.removeClass('open');
  }else{
    elem.addClass('open');
  }
})

</script>
