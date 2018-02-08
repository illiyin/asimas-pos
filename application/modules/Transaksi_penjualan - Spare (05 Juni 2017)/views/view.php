<!-- Page Content -->
<style type="text/css">
#modaldetail 
{
    /*max-width: 1140px; */
}
</style>

<div class="container">
<div class="row" style='min-height:80px;'></div>
<div class="row">
  <h3><strong>Transaksi</strong> - Penjualan</h3>
</div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>         
              <tr>
                  <th class="text-center hidden-xs">ID</th>
                  <th class="text-center hidden-xs">Customer</th>
                  <th class="text-center hidden-xs">Catatan</th>
                  <th class="text-center hidden-xs">Total Berat (gr)</th>
                  <th class="text-center hidden-xs">Total Qty</th>
                  <!-- <th class="text-center hidden-xs">Biaya Kirim (IDR)</th> -->
                  <th class="text-center hidden-xs">Grand Total (IDR)</th>
                  <th class="text-center hidden-xs">Tanggal Transaksi</th>
                  <th class="text-center hidden-xs no-sort">Aksi</th>
              </tr>
          </thead>
          <tbody id='bodytable'>            
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <a type="button" class="btn btn-add btn-lg" href="<?php echo base_url('index/modul/Transaksi_penjualan-Transaksi-transaksi'); ?>" target="_blank">
     Tambah Penjualan
   </a>
</div>
<!-- /.container -->
<!-- Modal Detail -->
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Penjualan</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-lg-12"  id="body-detail">
           </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
 </div>
</div>
<!-- /.Modal Detail-->
<script type="text/javascript" language="javascript" >
    function maskInputMoney(){
      $('.money').mask('#.##0', {reverse: true});
    }
    function unmaskInputMoney(){
      $('.money').unmask();
    }    

    function detail(id){
      $.ajax({
        url :"<?php echo base_url('Transaksi_penjualan/Transaksi/detail')?>/"+id,
        type : "GET",
        data :"",
        success : function(data){
          $("#body-detail").html(data);
        }
      });       
      $("#modaldetail").modal("show");
    }
    $(document).ready(function() {
        var dataTable = $('#TableMain').DataTable( {
            "processing": true,
            "serverSide": true,
            "order": [[6, 'DESC']],
            "ajax":{
                url : "<?php echo base_url('Transaksi_penjualan/Transaksi/data'); ?>",
                type: "post",
                error: function(){
                    $("#TableMain").append('<tbody class="employee-grid-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
                    // $("#employee-grid_processing").css("display","none");
                    // dataTable.ajax.reload( null, false );
                }
            },
            "columnDefs": [ {
              "targets"  : 'no-sort',
              "orderable": false,
            }],
        });
        maskInputMoney();
    });
</script>
