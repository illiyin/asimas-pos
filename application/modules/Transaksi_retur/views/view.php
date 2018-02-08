<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'></div>
<div class="row">
  <h3><strong>Transaksi</strong> - Retur</h3>
</div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>         
              <tr>
                  <th class="text-center hidden-xs">ID</th>
                  <th class="text-center hidden-xs">ID Order</th>
                  <th class="text-center hidden-xs">Nama Customer</th>
                  <th class="text-center hidden-xs">Catatan</th>
                  <th class="text-center hidden-xs">Jumlah</th>
                  <th class="text-center hidden-xs">Harga (IDR)</th>
                  <th class="text-center hidden-xs">Tanggal Retur</th>
                  <!-- <th class="text-center hidden-xs">Status Retur</th> -->
                  <th class="text-center hidden-xs no-sort">Status Retur</th>
                  <th class="text-center hidden-xs no-sort">Aksi</th>
              </tr>
          </thead>
          <tbody id='bodytable'>            
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <a type="button" class="btn btn-add btn-lg" href="<?php echo base_url('index/modul/Transaksi_retur-Transaksi-transaksi'); ?>" target="_blank">
     Tambah Retur
   </a>
</div>
<!-- /.container -->
<!-- Modal Detail -->
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Barang Retur</h4>
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
        url :"<?php echo base_url('Transaksi_retur/Transaksi/detail')?>/"+id,
        type : "GET",
        data :"",
        success : function(data){
          $("#body-detail").html(data);
        }
      });       
      $("#modaldetail").modal("show");
    }
    var dataTable = $('#TableMain').DataTable( {
        "processing": true,
        "serverSide": true,
        "order": [[6, 'DESC']],
        "ajax":{
            url : "<?php echo base_url('Transaksi_retur/Transaksi/data'); ?>",
            type: "post",
            error: function(){
                $("#TableMain").append('<tbody class="employee-grid-error"><tr><th colspan="10">No data found in the server</th></tr></tbody>');
            }
        },
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }],
        "drawCallback": function( settings ) {
          maskInputMoney();
          $('.bootstrap-toggle').bootstrapToggle({
            size: 'small',
            off: '<i class="fa fa-square-o" title="Belum Diproses"></i> Belum Diproses',
            on: '<i class="fa fa-check-square-o" title="Telah Diproses"></i> Telah Diproses',
            offstyle: 'default',
            onstyle: 'success'
          });
          $('.bootstrap-toggle').change(function(e) { 
            if($(e.currentTarget).is(':checked')) {
              confirmStatus(e);
            }
          });
        }
    });
  function confirmStatus(e){
    e.preventDefault();
    var i = $(e.currentTarget).prop("id");
    $.confirm({
    title: 'Konfirmasi!',
    content: 'Ubah status retur menjadi <i class="label label-success">Telah Diproses</i>?',
    type: 'green',
    buttons: {
        confirm: {
          text: 'Ya',
          btnClass: 'btn-success',
          action: function() {
            $('#'+i).bootstrapToggle('disable'); 
            var id  = parseInt(i.replace('toggle_',''));
            updateProses(id);
          }
        },
        cancel: {
          text: 'Batal',
          action: function() {
            $('#'+i).bootstrapToggle('off'); 
          }
        }
      }
    });
  }
  function updateProses(id){
      $.ajax({
        url :"<?php echo base_url('Transaksi_retur/Transaksi/updateProses')?>/"+id,
        type : "GET",
        data :"",
        success : function(data){
          dataTable.ajax.reload(null, false);
        }
      });   
  }

</script>
