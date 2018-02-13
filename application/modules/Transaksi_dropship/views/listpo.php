<table id="Listpo" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
      <tr>
          <th class="text-center">SUPPLIER</th>
          <th class="text-center">CATATAN</th>
          <th class="text-center">TOTAL BERAT</th>
          <th class="text-center" class="hidden-xs">TOTAL QTY</th>
          <th class="text-center" class="hidden-xs">TOTAL HARGA BELI</th>
          <th class="text-center" class="hidden-xs">DATE ADD</th>
          <th class="text-center" class="hidden-xs">AKSI</th>
      </tr>
  </thead>

  <tbody id='bodytable'>
    
  </tbody>
</table>
<script type="text/javascript" language="javascript" >
    $(document).ready(function() {
        var dataTable = $('#Listpo').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax":{
                url : "<?php echo base_url('Transaksi_pembelian/Transaksi/dataPO'); ?>",
                type: "post",
                error: function(){
                    $("#Listpo").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
                    // $("#employee-grid_processing").css("display","none");
                    // dataTable.ajax.reload( null, false );
                }
            }
        });
    });
</script>
