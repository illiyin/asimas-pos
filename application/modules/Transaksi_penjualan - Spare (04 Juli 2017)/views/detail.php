<!-- Page Content -->
      <table id="TableMains" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center">Produk</th>
                  <th class="text-center">Ukuran</th>
                  <th class="text-center">Warna</th>
                  <th class="text-center">Jumlah</th>
                  <th class="text-center">Total Berat (gr)</th>
                  <th class="text-center">Harga Beli (IDR)</th>
                  <th class="text-center">Harga Jual (IDR)</th>
                  <th class="text-center">Harga Jual Normal (IDR)</th>
                  <th class="text-center">Potongan (IDR)</th>
                  <th class="text-center">Total Potongan (IDR)</th>
                  <th class="text-center">Total Harga (IDR)</th>
                  <th class="text-center">Profit (IDR)</th>
              </tr>
          </thead>
          <tbody id='bodytable'>
            
          </tbody>
      </table>
<!-- /.container -->
<script type="text/javascript" language="javascript" >  
    // $(document).ready(function() {
    var dataTables = $('#TableMains').DataTable( {
        "searching": false,
        "processing": true,
        "serverSide": true,
        "order": [[1, 'DESC']],
        "ajax":{
            url : "<?php echo base_url('Transaksi_penjualan/Transaksi/data_detail'); ?>/"+<?php echo $id; ?>,
            type: "get",
            dataType : "json",
            error: function(){
                $("#TableMains").append('<tbody class="employee-grid-error"><tr><th colspan="10">No data found in the server</th></tr></tbody>');
                // dataTable.ajax.reload( null, false );
            }
        },
        "columnDefs": [ {
            "targets"  : 'no-sort',
            "orderable": false,
        }],
        "buttons": [
            {
                extend: 'excel',
                text: '<span class="fa fa-file-excel-o"></span> Excel Export',
                exportOptions: {
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                }
            }
        ]
    });
    // });
    function confirm(id){
      var jbk = $('#jbk-'+id).val();
      var juk = $('#juk-'+id).val();
      var sts = $('#sts-'+id).val();
      /* alert('JBK'+jbk+' JUK'+juk); */
      $.ajax({
        url :"<?php echo base_url('Transaksi_penjualan/Transaksi/confirm')?>/"+id,
        type : "POST",
        data : "jbk="+jbk+"&juk="+juk+"&sts="+sts+"&id="+id,
        success : function(data){
          dataTables.ajax.reload( null, false );
        }
      });      
    }
    var export_filename = 'Filename-Export';
    new $.fn.dataTable.Buttons( dataTables, {
        buttons: [
            {
                text: '<i class="fa fa-lg fa-clipboard"></i>',
                extend: 'copy',
                className: 'btn btn-xs btn-primary p-5 m-0 width-35 assets-export-btn export-copy ttip'
            }, {
                text: '<i class="fa fa-lg fa-file-text-o"></i>',
                extend: 'csv',
                className: 'btn btn-xs btn-primary p-5 m-0 width-35 assets-export-btn export-csv ttip',
                title: export_filename,
                extension: '.csv'
            }, {
                text: '<i class="fa fa-lg fa-file-excel-o"></i>',
                extend: 'excel',
                className: 'btn btn-xs btn-primary p-5 m-0 width-35 assets-export-btn export-xls ttip',
                title: export_filename,
                extension: '.xls'
            }, {
                text: '<i class="fa fa-lg fa-file-pdf-o"></i>',
                extend: 'pdf',
                className: 'btn btn-xs btn-primary p-5 m-0 width-35 assets-export-btn export-pdf ttip',
                title: export_filename,
                extension: '.pdf'
            }
        ]
    } );
    dataTables.buttons( 0, null ).container().appendTo( '#export-assets' );
     
     
    // Configure Print Button
    new $.fn.dataTable.Buttons( dataTables, {
        buttons: [
            {
                text: '<i class="fa fa-lg fa-print"></i> Print Assets',
                extend: 'print',
                className: 'btn btn-primary btn-sm m-5 width-140 assets-select-btn export-print'
            }
        ]
    } );
     
    // Add the Print button to the toolbox
    dataTables.buttons( 1, null ).container().appendTo( '#print-assets' );
     
     
    // Select Buttons
    new $.fn.dataTable.Buttons( dataTables, {
        buttons: [
            {
                extend: 'selectAll',
                className: 'btn btn-xs btn-primary p-5 m-0 width-70 assets-select-btn'
            }, {
                extend: 'selectNone',
                className: 'btn btn-xs btn-primary p-5 m-0 width-70 assets-select-btn'
            }
        ]
    } );
    dataTables.buttons( 2, null ).container().appendTo( '#select-assets' );
    new $.fn.dataTable.Buttons( dataTables, {
        buttons: [
            {
                text: 'Delete Selected',
                action: function () {
                    assets.delete_from_list(dt, assets.selected_ids( dt ) );
                },
                className: 'btn btn-primary btn-sm m-5 width-140 assets-select-btn toolbox-delete-selected'
            }, {
                text: 'View Timeline',
                action: function () {
                    console.log(assets.selected_ids( dataTables ));
                },
                className: 'btn btn-primary btn-sm m-5 width-140 assets-select-btn'
            }
        ]
    } );
    dataTables.buttons( 3, null ).container().appendTo( '#selected-assets-btn-group' );
     
     
    // Configure Select Columns
    new $.fn.dataTable.Buttons( dataTables, {
        buttons: [
            {
                extend: 'collection',
                text: 'Select Columns',
                buttons: [ {
                    extend: 'columnsToggle',
                    columns: ':not([data-visible="false"])'
                } ],
                className: 'btn btn-primary btn-sm m-5 width-140 assets-select-btn'
            }
        ],
        fade: true
    } );
    dataTables.buttons( 4, null ).container().appendTo( '#toolbox-column-visibility' );
</script>
