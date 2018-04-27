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
    <h3><strong>Laporan FIFO</strong> - Supplier</h3>
  </div>
  <div class="row panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-2">
          <label for="filter_bahan" class="control-label" style="margin-top:5px;">Filter Bahan:</label>
        </div>
        <div class="col-sm-10">
          <div class="multi-filter">
            <div class="filter-item">
              <select class="form-control" name="" id="filter_bahan" data-column="1">
                <option value="" data-filter="0">Filter Bahan</option>
                <?php foreach($list_bahan as $bahan): ?>
                <option value="<?= $bahan->nama ?>" data-filter="<?= $bahan->id ?>"><?= $bahan->nama ?></option>
                <?php endforeach; ?>
                <!-- <option value="bahan_b" data-filter="Biskuit AMB">Biskuit AMB</option> -->
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
                  <th class="text-center no-sort">Nama Supplier</th>
                  <th class="text-center no-sort">Nama Bahan</th>
                  <th class="text-center no-sort">Alamat</th>
                  <th class="text-center no-sort">No. Telepon</th>
                  <th class="text-center no-sort">Email</th>
              </tr>
          </thead>

          <tbody id='bodytable'>
            <!-- <tr>
              <td class="text-center">1</td>
              <td data-search="Supplier 1 Brotowali">Supplier 1</td>
              <td>Malang</td>
              <td>008</td>
              <td>alamat@email.com</td>
            </tr> -->
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <button type="button" class="btn btn-add btn-lg" onclick="gotoCetak()">
     Cetak Laporan
   </button>
</div>
<!-- /.container -->


<script type="text/javascript">
function gotoCetak(){
  window.open("<?php echo base_url()?>index/modul/Laporan_fifo-master-cetaksupplier", "_blank");
  // location.href="Laporan_fifo-master-cetaksupplier";
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
var initDataTable = $('#TableMainServer').DataTable({
    "bProcessing": true,
    "bServerSide": true,
    // "order": [[3, 'DESC']],
    "ajax":{
          url :"<?php echo base_url()?>Laporan_fifo/DataTables/realsupplier",
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

  $('.filter-item select').on('change',function(){
    // var keyword = $('option:selected',this).attr('data-filter');
    // var keyword = $('#filter_bahan option:selected').attr('data-filter') +' '+ $('#filter_supplier option:selected').attr('data-filter') +' '+ $('#filter_kategori option:selected').attr('data-filter');
    var keyword='';
    var k1 = $('#filter_bahan option:selected').attr('data-filter');
    if(k1!=0){
      keyword+=k1;
    }
    filterColumn($(this).attr('data-column'),keyword);
    // console.log(keyword);
  })

  $('#search_global').on('keyup click',function(){
    var keyword = $(this).val();
    filterGlobal(keyword)
  })
</script>
