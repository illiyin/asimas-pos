<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'></div>
<div class="row">
  <h3><strong>Log</strong> - Aktivitas</h3>
</div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>         
              <tr>
                  <th class="text-center" class="hidden-xs">User</th>
                  <th class="text-center" class="hidden-xs">Modul</th>
                  <th class="text-center" class="hidden-xs">Fungsi</th>
                  <th class="text-center" class="hidden-xs">Tanggal Aktivitas</th>
              </tr>
          </thead>
          <tbody id='bodytable'>            
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <!-- <a type="button" class="btn btn-add btn-lg" href="<?php echo base_url('index/modul/Transaksi_retur-Transaksi-transaksi'); ?>" target="_blank">
     Tambah Transaksi Retur
   </a> -->
</div>
<!-- /.container -->
<!-- Modal Detail -->
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Log Akivitas</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-lg-12"  id="body-detail">
           </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" data-dismiss="modal">Ok</button>
      </div>
    </div>
 </div>
</div>
<!-- /.Modal Detail-->
<script type="text/javascript" language="javascript" >
    var dataTable = $('#TableMain').DataTable( {
        "processing": true,
        "serverSide": true,
        "order": [[3, 'DESC']],
        "ajax":{
            url : "<?php echo base_url('Log_aktivitas/log/data'); ?>",
            type: "post",
            error: function(){
                $("#TableMain").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
            }
        }
    });
</script>
