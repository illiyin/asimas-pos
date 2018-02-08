<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'></div>
   <div class="row">
    <h3><strong>Transaksi</strong> - Dropship</h3>
   </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>         
              <tr>
                <th class="text-center hidden-xs">ID</th>
                <th class="text-center hidden-xs">Customer</th>
                <th class="text-center hidden-xs">Detail Dropship</th>
                <th class="text-center hidden-xs">Biaya Kirim (IDR)</th>
                <th class="text-center hidden-xs">Harga Barang (IDR)</th>
                <th class="text-center hidden-xs">Tanggal Transaksi</th>
                <th class="text-center hidden-xs no-sort">Aksi</th>
              </tr>
          </thead>
          <tbody id='bodytable'>            
          </tbody>
      </table>
   </div>
</div>
<!-- /.container -->
<!-- Modal Add -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Dropship</h4>
      </div>
      <form action="<?php echo base_url('Transaksi_dropship/Transaksi/updateDropship') ?>" method="POST" id="dropshipform">      
        <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama_pengirim">Nama Pengirim</label>
                 <input type="text" name="nama_pengirim" maxlength="50" Required class="form-control" id="nama_pengirim" placeholder="Nama Pengirim">
                 <input type="hidden" name="id_order" maxlength="50" Required class="form-control" id="id_order">
               </div>
             </div>
             <div class="col-sm-12">
               <div class="form-group">
                 <label for="alamat">Alamat Pengirim</label>
                 <input type="text" name="alamat_pengirim" maxlength="30" class="form-control" id="alamat_pengirim" placeholder="Alamat Pengirim" required="">
               </div>
             </div>
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama_penerima">Nama Penerima</label>
                 <input type="text" name="nama_penerima" maxlength="50" Required class="form-control" id="nama_penerima" placeholder="Nama Penerima">
               </div>
             </div>
             <div class="col-sm-12">
               <div class="form-group">
                 <label for="alamat_penerima">Alamat Penerima</label>
                 <input type="text" name="alamat_penerima" maxlength="30" class="form-control" id="alamat_penerima" placeholder="Alamat Penerima" required="">
               </div>
             </div>
             <div class="col-sm-12">
               <div class="form-group">
                 <label for="no_telp_penerima">No Telp Penerima</label>
                 <input type="number" min="0" maxlength="50" name="no_telp_penerima" class="form-control" id="no_telp_penerima" placeholder="No Telp Penerima"  required="">
               </div>
             </div>
             <div class="col-sm-12">
               <div class="form-group">
                 <label for="biaya_kirim">Biaya Kirim (IDR)</label>
                 <input type="text" name="biaya_kirim" maxlength="30" min="0" class="form-control money" id="biaya_kirim" placeholder="Biaya Kirim" required="">
               </div>
             </div>
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-add" id="aSimpan">Simpan</button>
        </div>
      </form>
    </div>
 </div>
</div>
<!-- /.Modal Add-->

<script type="text/javascript" language="javascript" >
    function maskInputMoney(){
      $('.money').mask('#.##0', {reverse: true});
    }
    function unmaskInputMoney(){
      $('.money').unmask();
    }    

    function detail(id){
      // getDataDropship
      $.ajax({
        url :"<?php echo base_url('Transaksi_dropship/Transaksi/detail')?>/"+id,
        type : "GET",
        data :"",
        success : function(data){
          $("#body-detail").html(data);
          maskInputMoney();
        }
      });       
      $("#modaldetail").modal("show");
    }
    function showDropship(id){
      $.ajax({
        url :"<?php echo base_url('Transaksi_dropship/Transaksi/getDataDropship')?>/"+id,
        type :"GET",
        data :"",
        dataType:"json",
        success : function(data){
          $("#id_order").val(id);
          $("#nama_pengirim").val(data.nama_pengirim);
          $("#alamat_pengirim").val(data.alamat_pengirim);
          $("#nama_penerima").val(data.nama_penerima);
          $("#no_telp_penerima").val(data.no_telp_penerima);
          $("#alamat_penerima").val(data.alamat_penerima);
          $("#biaya_kirim").val(data.biaya_kirim);
          unmaskInputMoney(); maskInputMoney();
          $("#modalform").modal('show');
        }
      });       
    }
    $(document).ready(function() {
        maskInputMoney();
        var dataTable = $('#TableMain').DataTable( {
            "processing": true,
            "serverSide": true,
            "order": [[5, 'DESC']],
            "ajax":{
                url : "<?php echo base_url('Transaksi_dropship/Transaksi/data'); ?>",
                type: "post",
                error: function(){
                    $("#TableMain").append('<tbody class="employee-grid-error"><tr><th colspan="10">No data found in the server</th></tr></tbody>');
                }
            },
            "columnDefs": [ {
                "targets"  : 'no-sort',
                "orderable": false,
              }]
        });
        $("#dropshipform").on('submit', function(e){
          e.preventDefault();
          unmaskInputMoney();
          $.ajax({
            url :$("#dropshipform").attr('action'),
            type :$("#dropshipform").attr('method'),
            data :$("#dropshipform").serialize(),
            dataType:'json',
            success : function(data){
              dataTable.ajax.reload(null, false);
              $("#modalform").modal('hide');
            }
          });
          maskInputMoney();
        });
    });
</script>
