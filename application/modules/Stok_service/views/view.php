<style type="text/css">
  @media (min-width: 992px) {
    .modal-lg.modal-xl {
        width: 1080px;
    }
  }
</style>
<!-- Page Content -->
<div class="container">
  <div class="row" style='min-height:80px;'></div>
  <div class="row">
    <h3><strong>Stok</strong> - Service</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center">ID</th>
                  <th class="text-center">Nama Supplier</th>
                  <th class="text-center">Catatan</th>
                  <th class="text-center">Jumlah Diservis</th>
                  <th class="text-center hidden-xs">Total Harga (IDR)</th>
                  <th class="text-center hidden-xs">Barang Kembali</th>
                  <th class="text-center hidden-xs">Uang Kembali (IDR)</th>
                  <th class="text-center hidden-xs">Status</th>
                  <th class="text-center">Tanggal Service</th>
                  <th class="text-center no-sort">Aksi</th>
              </tr>
          </thead>

          <tbody id='bodytable'>
            
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <a type="button" class="btn btn-add btn-lg" href="<?php echo base_url('index/modul/Stok_service-Transaksi-transaksi'); ?>" target="_blank">
     Tambah Stok Service
   </a>
</div>
<!-- /.container -->
<!-- Modal Detail -->
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg modal-xl" role="document">
    <div class="modal-content">
      <form action="<?php echo base_url('Stok_service/Transaksi/confirm')?>" method="POST" id="frm-detail">          
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Barang Service</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-lg-12"  id="body-detail">
           </div>
         </div>
      </div>
      <div class="modal-footer">
          <div class="row">
           <div class="col-lg-9">
           <small>*Barang yang statusnya sudah diubah, tidak akan bisa diubah lagi. <br>Mohon periksa kembali sebelum klik tombol Simpan</small>
           </div>
           <div class="col-lg-3">
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            <button id="btnConfirm" type="submit" class="btn btn-add">Simpan</button>
           </div>
          </div>
      </div>
      </form>
    </div>
 </div>
</div>
<!-- /.Modal Detail-->
<script type="text/javascript" language="javascript" >
    function maskInputHundreds(){
      console.log("Run Hundreds");
      $('.hundreds').mask('000', {reverse: true});
    }
    function unmaskInputHundreds(){
      $('.hundreds').unmask();
    }
    function maskInputMoney(){
      $('.money').mask('#.##0', {reverse: true});
    }
    function unmaskInputMoney(){
      $('.money').unmask();
    }
    maskInputMoney();
    var dataTable = $('#TableMain').DataTable( {
        "processing": true,
        "serverSide": true,
        "order": [[8, 'DESC']],
        "ajax":{
            url : "<?php echo base_url('Stok_service/Transaksi/data'); ?>",
            type: "post",
            error: function(){
                $("#TableMain").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                // $("#employee-grid_processing").css("display","none");
                // dataTable.ajax.reload( null, false );
            }
        },
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }]
    });
    var dataTables = $('#TableMains').DataTable();

    function detail(id){
      $.ajax({
        url :"<?php echo base_url('Stok_service/Transaksi/detail')?>/"+id,
        type : "GET",
        data :"",
        success : function(data){
          $("#body-detail").html(data);
        }
      });       
      maskInputHundreds();
      $("#modaldetail").modal("show");
    }
    function testClick(){
      $("#frm-detail").submit();
    }

    $(document).ready(function(){
      $("#frm-detail").on('submit', function(e){
        e.preventDefault();
        unmaskInputMoney();
        var defaultHtml = $("#btnConfirm").html();
        $.ajax({
          url : $('#frm-detail').attr('action'),
          type : $('#frm-detail').attr('method'),
          data : $("#frm-detail").serialize(),
          dataType : 'json',
          beforeSend : function() {
            $("#btnConfirm").text("Saving...");
            $("#btnConfirm").prop("disabled", true);
          },
          success : function(data){
            if(data.status == 1){
              $("#modaldetail").modal("hide");
              console.log("Masuk status 1");
              $("#btnConfirm").text("Simpan");
              $("#btnConfirm").prop("disabled", false);
              // $("#btnConfirm").html(defaultHtml);
              // $("#modaldetail").modal("hide");
              reloadTable();
              dataTables.ajax.reload(null, false);
              maskInputMoney();
            }
          }
        });   
      });      
    });

</script>
