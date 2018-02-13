<!-- Page Content -->
        <!-- <form action="<?php echo base_url('Stok_service/Transaksi/confirm')?>" method="POST" id="frm-detail"> -->
        <input type="hidden" name="id_hidden" value="<?php echo $id; ?>">
        <table id="TableMains" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center no-sort">#</th>
                    <th class="text-center">Produk</th>
                    <th class="text-center">Warna</th>
                    <th class="text-center">Ukuran</th>
                    <th class="text-center">SKU</th>
                    <th class="text-center hidden-xs">Jumlah Diservis</th>
                    <th class="text-center hidden-xs no-sort">Barang Kembali</th>
                    <th class="text-center hidden-xs no-sort">Uang Kembali (IDR)</th>
                    <th class="text-center hidden-xs no-sort">Status Kembali</th>
                </tr>
            </thead>

            <tbody id='bodytable'>
              
            </tbody>
        </table>
        <!-- </form> -->
<!-- /.container -->
<script type="text/javascript" language="javascript" >
    var dataTables = $('#TableMains').DataTable( {
        "searching": false,
        "processing": true,
        "serverSide": true,
        "order": [[2, 'DESC']],
        "ajax":{
            url : "<?php echo base_url('Stok_service/Transaksi/data_detail'); ?>/"+<?php echo $id; ?>,
            type: "get",
            dataType : "json",
            error: function(){
                $("#TableMains").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
                // dataTable.ajax.reload( null, false );
            }
        },
        "columnDefs": [ {
            "targets"  : 'no-sort',
            "orderable": false,
          }]
    });
    maskInputMoney();
    function confirm(id){
      var jbk = $('#jbk-'+id).val();
      var juk = $('#juk-'+id).val();
      var sts = $('#sts-'+id).val();
      $.ajax({
        url :"<?php echo base_url('Stok_service/Transaksi/confirm')?>/"+id,
        type : "POST",
        data : "jbk="+jbk+"&juk="+juk+"&sts="+sts+"&id="+id,
        success : function(data){
          // dataTables.ajax.reload( null, false );
        }
      });
    }
    function reloadTable(){
      dataTable.ajax.reload( null, false );
    }
</script>
