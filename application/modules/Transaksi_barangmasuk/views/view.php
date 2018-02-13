<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'></div>
<div class="row">
    <h3><strong>Transaksi</strong> - Barang Masuk</h3>
 </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center">ID</th>
                  <th class="text-center">Foto</th>
                  <th class="text-center">Nama Produk</th>
                  <th class="text-center">SKU</th>
                  <th class="text-center">Total Stok</th>
                  <th class="text-center">Detail Stok</th>
                  <th class="text-center">Terakhir Ditambah</th>
                  <th class="text-center">Terakhir Dikurangi</th>
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
        <h4 class="modal-title" id="myModalLabel">Ubah Stok Produk</h4>
      </div>
      <form action="<?php echo base_url('Transaksi_barangmasuk/Transaksi/ubahStok') ?>" method="POST" id="myform">      
        <div class="modal-body">
           <div class="row">
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_warna">Warna</label>
                 <select name="id_warna" class="form-control" id="id_warna" required="required">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_ukuran">Ukuran</label>
                 <select name="id_ukuran" class="form-control" id="id_ukuran" required="required">
                 </select>
               </div>
             </div>
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama">Jumlah QTY</label>
                 <input type="number" name="qty" maxlength="50" Required class="form-control" id="qty" placeholder="Stok Produk">
                 <input type="hidden" name="state" maxlength="50" Required class="form-control" id="state">
                 <input type="hidden" name="idProduk" maxlength="50" Required class="form-control" id="idProduk">
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
<!-- /.Modal Ubah-->
<script type="text/javascript" language="javascript" >
    var jsonDetUkuran = <?php echo $list_det_ukuran; ?>;
    var jsonDetWarna = <?php echo $list_det_warna; ?>;

    function loadWarna(id){
      var html = "<option selected disabled>Pilih Warna</option>";
          // html += "<option value='0'>Tidak Ada</option>";
      if(id != '' || id != null) {
        options = jsonDetWarna.filter(function (index) { return index.id_produk == id }); 

        $.each(options, function(index, value) {
          html += "<option value=\'"+options[index].id_warna+"\'>"
                + options[index].nama
                + "</option>";
        });
        $("#id_warna").html(html);
      }
    }
    function loadUkuran(id){
      var html = "<option selected disabled>Pilih Ukuran</option>";
          // html += "<option value='0'>Tidak Ada</option>";
      if(id != '' || id != null) {
        options = jsonDetUkuran.filter(function (index) { return index.id_produk == id }); 

        $.each(options, function(index, value) {
          html += "<option value=\'"+options[index].id_ukuran+"\'>"
                + options[index].nama
                + "</option>";
        });
        $("#id_ukuran").html(html);
      }
    }

    function tambahStok(id){
      $("#myModalLabel").html("Tambah Stok Produk");
      $("#state").val("tambah");
      $("#qty").val("");
      $("#idProduk").val(id);
      loadWarna(id);
      loadUkuran(id);
      $("#modalform").modal("show");
    }
    function kurangStok(id){
      $("#myModalLabel").html("Kurangi Stok Produk");
      $("#state").val("kurang");
      $("#qty").val("");
      $("#idProduk").val(id);
      loadWarna(id);
      loadUkuran(id);
      $("#modalform").modal("show");
    }
    $(document).ready(function() {
        var dataTable = $('#TableMain').DataTable( {
            "processing": true,
            "serverSide": true,
            "order": [[5, 'DESC']],
            "ajax":{
                url : "<?php echo base_url('Transaksi_barangmasuk/Transaksi/data'); ?>",
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
            data : $("#myform").serialize()
                    + "&nama_ukuran=" + $("#id_ukuran :selected").html()
                    + "&nama_warna=" + $("#id_warna :selected").html(),
            dataType: 'json',
            beforeSend: function() { 
              $("#aSimpan").prop("disabled", true);
              $('#aSimpan').html('Sedang Menyimpan...');
            },            
            success : function(data){
              console.log(data);
              if(data.status == 1) {
                new PNotify({
                            title: 'Berhasil',
                            text: data.message,
                            type: 'success',
                            hide: true,
                            delay: 5000,
                            styling: 'bootstrap3'
                          });          
              } else if(data.status == 2) {
                new PNotify({
                            title: 'Perhatian',
                            text: data.message,
                            type: 'warning',
                            hide: true,
                            delay: 5000,
                            styling: 'bootstrap3'
                          });
              } else {
                new PNotify({
                            title: 'Gagal',
                            text: data.message,
                            type: 'error',
                            hide: true,
                            delay: 5000,
                            styling: 'bootstrap3'
                          });          
              }
              $('#aSimpan').html('Simpan');
              $("#aSimpan").prop("disabled", false);              
              dataTable.ajax.reload( null, false );
              $("#modalform").modal("hide");
            },
            error: function(jqXHR, exception) {
              console.log(exception);
              new PNotify({
                            title: 'Gagal',
                            text: "Gagal Update Stok",
                            type: 'error',
                            hide: true,
                            delay: 5000,
                            styling: 'bootstrap3'
                          });          
              $('#aSimpan').html('Simpan');
              $("#aSimpan").prop("disabled", false);
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
