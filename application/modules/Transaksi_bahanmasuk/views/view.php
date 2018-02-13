<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'></div>
<div class="row">
  <h3><strong>Transaksi</strong> - Bahan Masuk</h3>
</div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center">ID</th>
                  <th class="text-center">Foto</th>
                  <th class="text-center">Nama Produk</th>
                  <th class="text-center">SKU</th>
                  <th class="text-center">Stok</th>
                  <th class="text-center">Terakhir Tambah Stok</th>
                  <th class="text-center">Terakhir Kurang Stok</th>
                  <th class="text-center hidden-xs no-sort">Aksi</th>
              </tr>
          </thead>

          <tbody id='bodytable'>            
          </tbody>
      </table>
   </div>
</div>
<!-- /.container -->
<!-- Modal Ubah -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Ubah Stok Bahan</h4>
      </div>
      <form action="<?php echo base_url('Transaksi_bahanmasuk/Transaksi/ubahStok') ?>" method="POST" id="myform">      
        <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama">Jumlah QTY</label>
                 <input type="text" name="qty" maxlength="50" Required class="form-control" id="qty" placeholder="Stok Produk">
                 <input type="hidden" name="state" maxlength="50" Required class="form-control" id="state">
                 <input type="hidden" name="idProduk" maxlength="50" Required class="form-control" id="idProduk">
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
<!-- /.Modal Ubah-->
<script type="text/javascript" language="javascript" >
    function tambahStok(id){
      $("#myModalLabel").html("Tambah Stok Bahan");
      $("#state").val("tambah");
      $("#qty").val("");
      $("#idProduk").val(id);
      $("#modalform").modal("show");
    }
    function kurangStok(id){
      $("#myModalLabel").html("Kurang Stok Bahan");
      $("#state").val("kurang");
      $("#qty").val("");
      $("#idProduk").val(id);
      $("#modalform").modal("show");
    }
    $(document).ready(function() {
        var dataTable = $('#TableMain').DataTable( {
            "processing": true,
            "serverSide": true,
            "order": [[5, 'DESC']],
            "ajax":{
                url : "<?php echo base_url('Transaksi_bahanmasuk/Transaksi/data'); ?>",
                type: "post",
                error: function(){
                    $("#TableMain").append('<tbody class="employee-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
                    // $("#employee-grid_processing").css("display","none");
                    // dataTable.ajax.reload( null, false );
                }
            },
            "columnDefs": [ {
                "targets"  : 'no-sort',
                "orderable": false,
              }]
        });
        $("#myform").on('submit', function(e){
          e.preventDefault();
          $.ajax({
            url : $("#myform").attr('action'),
            type : $("#myform").attr('method'),
            data : $("#myform").serialize(),
            dataType: 'json',
            beforeSend: function() { 
              $("#aSimpan").prop("disabled", true);
              $('#aSimpan').html('Sedang Menyimpan...');
            },            
            success : function(data){
              console.log(data);
              if(data.status == 1){
                new PNotify({
                            title: 'Berhasil',
                            text: "Berhasil Update Stok",
                            type: 'success',
                            hide: true,
                            delay: 5000,
                            styling: 'bootstrap3'
                          });          
              }else{
                new PNotify({
                            title: 'Gagal',
                            text: "Gagal Update Stok",
                            type: 'danger',
                            hide: true,
                            delay: 5000,
                            styling: 'bootstrap3'
                          });          

              }
              $('#aSimpan').html('Simpan');
              $("#aSimpan").prop("disabled", false);              
              dataTable.ajax.reload( null, false );
              $("#modalform").modal("hide");
            }
          });    
        });
    });

    function showThumbnail(el){
      var img_src = $(el).find("img").attr("src");
      $(el).attr("data-content","<img src='"+img_src+"' class=\'img-responsive\'  href=\'#\' style=\'max-width:350px\'>");
      $(el).popover("show");
    }

    //Hack untuk bootstrap popover (popover hilang jika diklik di luar)
    $(document).on('click', function (e) {
      $('[data-toggle="popover"],[data-original-title]').each(function () {
          //the 'is' for buttons that trigger popups
          //the 'has' for icons within a button that triggers a popup
          if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {                
              (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
          }
      });
    });  
</script>
