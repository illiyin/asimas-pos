<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Laporan FIFO</strong> - Barang</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center">Nama Barang</th>
                  <th class="text-center">No. Batch</th>
                  <th class="text-center">Stok Akhir</th>
                  <th class="text-center">Tanggal Expired</th>
              </tr>
          </thead>

          <tbody id='bodytable'>
            
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
  location.href="Laporan_fifo-master-cetakbarang";
}
var initDataTable = $('#TableMainServer').DataTable({
    "bProcessing": true,
    "bServerSide": true,
    // "order": [[4, 'DESC']],
    "ajax":{
          url :"<?php echo base_url()?>Laporan_fifo/DataTables/barang",
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
</script>
