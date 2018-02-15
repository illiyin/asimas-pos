<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Bahan Baku</strong> - Semua Bahan</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center no-sort">Foto</th>
                  <th class="text-center">Nama Bahan Baku</th>
                  <th class="text-center">SKU</th>
                  <th class="text-center">Stok</th>
                  <th class="text-center" class="hidden-xs">Tanggal Buat</th>
                  <th class="text-center no-sort">Aksi</th>
              </tr>
          </thead>

          <tbody id='bodytable'>
            
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <button type="button" class="btn btn-add btn-lg"  onclick="showAdd()">
     Tambah Bahan Baku
   </button>
</div>
<!-- /.container -->
<!-- Modal Detail Bahan baku -->
<div class="modal fade" id="Viewproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document" id="viewModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="view">Detail Bahan Baku</h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div id="viewSectionProduct">
              <!-- view goes here -->
              <div class="col-md-12"><div class="media">
                 <div class="media-left">
                    <img id="det_foto" class="media-object img-rounded" src="<?php echo base_url()?>upload/bahan_baku/placeholder.png" alt="image" width="200px">
                 </div>
                 <div class="media-body">
                  <h1 class="media-heading" id="det_nama">sfsdg</h1>
                  <div class="row">
                    <div class="col-sm-6">
                      <p><b>SKU :</b> <span id="det_sku"></span></p>
                      <p><b>Kode Barang :</b> <span id="det_kode_barang"></span></p>
                      <p><b>Harga Beli :</b> Rp <span id="det_harga_beli" class="money"></span></p>
                      <p><b>Stok :</b> <span id="det_stok"></span></p>
                      <p><b>Berat :</b> <span id="det_berat" class="money"></span> gram</p>
                      <p><b>Deskripsi :</b> <span id="det_deskripsi"></span></p>
                    </div>
                    <div class="col-sm-6">
                      <p><b>Supplier :</b> <span id="det_supplier"></span></p>
                      <p><b>Satuan :</b> <span id="det_satuan"></span></p>
                      <p><b>Gudang :</b> <span id="det_gudang"></span></p>
                      <p><b>Kategori :</b> <span id="det_kategori"></span></p>
                      <p><b>Warna :</b> <span id="det_warna"></span></p>
                    </div>
                  </div>
                 </div>
              </div></div>
              <div class="col-md-6">
                  
              </div>
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal">Close</button>
        </div>
      </div>
   </div>
</div>
<!-- /.Modal -->

<!-- Modal Add -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Bahan Baku</h4>
      </div>
      <form action="" method="POST" id="myform" enctype="multipart/form-data"> <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama">Nama Bahan Baku</label>
                 <input type="text" name="nama" maxlength="50" Required class="form-control" id="nama" placeholder="Nama Bahan Baku">
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Bahan Baku">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_supplier">Supplier Bahan Baku</label>
                 <select name="id_supplier" class="form-control" id="id_supplier" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_satuan">Satuan</label>
                 <select name="id_satuan" class="form-control" id="id_satuan" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_warna">Warna</label>
                 <select name="id_warna[]" class="form-control" id="id_warna" multiple="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6"></div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_gudang">Gudang</label>
                 <select name="id_gudang" class="form-control" id="id_gudang" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Kategori Bahan Baku</label>
                 <select name="id_kategori" class="form-control" id="id_kategori" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="sku">SKU</label>
                 <input type="text" name="sku" maxlength="50" Required class="form-control" id="sku" placeholder="SKU">
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="kode_barang">Kode Barang</label>
                 <input type="text" name="kode_barang" maxlength="50" Required class="form-control" id="kode_barang" placeholder="Kode Barang">
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="berat">Berat (gram)</label>
                 <input type="text" name="berat" min="0" Required class="form-control money" id="berat" placeholder="Berat (gram)">
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="harga_beli">Harga Beli (IDR)</label>
                 <div class="input-group">
                  <span class="input-group-addon">Rp</span> 
                  <input type="text" name="harga_beli" Required class="form-control money" id="harga_beli" placeholder="Harga Beli">
                 </div>
               </div>
             </div>
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="deskripsi">Deskripsi</label>
                 <textarea name="deskripsi" rows="2" Required class="form-control" id="deskripsi" placeholder="Deskripsi"></textarea>
               </div>
             </div>
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="foto">Foto</label>
                 <input type="file" name="foto" accept="image/png, image/jpeg" Required class="form-control" id="foto" placeholder="Foto">
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

<script type="text/javascript">
  $(document).ready(function() {
    //initialize input money masking
    maskInputMoney();
    $("#foto").fileinput({ 'showUpload': false });
  });
  function maskInputMoney(){
    $('.money').mask('#.##0', {reverse: true});
  }
  function unmaskInputMoney(){
    $('.money').unmask();
  }
  function fotoInitialPreview(file_source, file_name){
    $("#foto").fileinput('destroy');
    $("#foto").fileinput({ 
      showUpload: false,
      initialPreview: [file_source],
      initialPreviewAsData: true,
      initialPreviewFileType: 'image',
      initialPreviewConfig: [
        {caption: file_name}
        ],
      // initialPreviewShowDelete: false,
      purifyHtml: true, // this by default purifies HTML data for preview
     });
  }

  var jsonlist = <?php echo $list; ?>;
  var jsonSupplier = <?php echo $list_supplier; ?>;
  var jsonSatuan = <?php echo $list_satuan; ?>;
  var jsonGudang = <?php echo $list_gudang; ?>;
  var jsonKategori = <?php echo $list_kategori; ?>;
  
  var jsonWarna = <?php echo $list_warna; ?>;
  var jsonDetWarna = <?php echo $list_det_warna; ?>;
  
  var awalLoad = true;
  var initDataTable = $('#TableMainServer').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "order": [[4, 'DESC']],
      "ajax":{
            url :"<?php echo base_url()?>Bahan_baku/Master/data",
            type: "post",  // type of method  , by default would be get
            error: function(){  // error handling code
              // $("#employee_grid_processing").css("display","none");
            }
          },
      "columnDefs": [ {
        "targets"  : 'no-sort',
        "orderable": false,
      }]
    });
 
  function load_select_option(json, target_id, nama=""){
    var html = "";
    if(!nama == "") {
      html = "<option value='' selected disabled>Pilih "+nama+"</option>";
    }
    for (var i=0;i<json.length;i++){
         html = html+ "<option value='"+json[i].id+"'>"+json[i].nama+"</option>";
    } $(target_id).html(html);
  }
  function load_select() {
    load_select_option(jsonSupplier, "#id_supplier", "Supplier");
    load_select_option(jsonSatuan, "#id_satuan", "Satuan");
    load_select_option(jsonGudang, "#id_gudang", "Gudang");
    load_select_option(jsonKategori, "#id_kategori", "Kategori");
    load_select_option(jsonWarna, "#id_warna","");
    $("#id_warna").multiselect({
      buttonWidth: '100%',
      inheritClass: true,
      enableFiltering: true,
      includeSelectAllOption: true,
      nonSelectedText: "Pilih Warna"
    });
  }
  function showAdd(){
    load_select();
    $("#myModalLabel").text("Tambah Bahan Baku");
    $("#id").val("");
    $("#nama").val("");
    $("#id_supplier").val("");
    $("#id_satuan").val("");
    $("#id_gudang").val("");
    $("#id_kategori").val("");
    $("#id_warna").multiselect('refresh');
    $("#sku").val("");
    $("#kode_barang").val("");
    $("#berat").val("");
    $("#harga_beli").val("");
    $("#foto").attr("required", true);
    $("#foto").fileinput("clear");
    $("#deskripsi").val("");
    unmaskInputMoney(); maskInputMoney();
    $("#modalform").modal("show");    
  }
  
  function showUpdate(i){
    load_select();
    //data ukuran & warna diambil dari tabel yang berbeda
    var dataUpdate = jsonlist.filter(function (index) { return index.id == i }); 
    console.log(dataUpdate);
    var getWarna = jsonDetWarna.filter(function (index) { return index.id_bahan == i });
    var id_warna = [];
    id_warna = $.map(getWarna, function(el, idx){
       return [el["id_warna"]];
    }); 

    $("#myModalLabel").text("Ubah Bahan Baku");
    $("#id").val(dataUpdate[0].id);
    $("#nama").val(dataUpdate[0].nama);
    $("#id_supplier").val((dataUpdate[0].id_supplier_bahan==0) ? "" : dataUpdate[0].id_supplier_bahan);
    $("#id_satuan").val((dataUpdate[0].id_satuan==0) ? "" : dataUpdate[0].id_satuan);
    $("#id_gudang").val((dataUpdate[0].id_gudang==0) ? "" : dataUpdate[0].id_gudang);
    $("#id_kategori").val((dataUpdate[0].id_kategori_bahan==0) ? "" : dataUpdate[0].id_kategori_bahan);
    $("#sku").val(dataUpdate[0].sku);
    $("#kode_barang").val(dataUpdate[0].kode_barang);
    $("#berat").val(dataUpdate[0].berat);
    $("#harga_beli").val(dataUpdate[0].harga_beli);
    $("#deskripsi").val(dataUpdate[0].deskripsi);
    $("#foto").attr("required", false);
    $("#foto").fileinput("clear");

    var file_source = dataUpdate[0].foto || "placeholder.png";
    fotoInitialPreview("<?php echo base_url();?>"+ "upload/bahan_baku/" + file_source, file_source);
    
    $("#id_warna").val(id_warna);
    $("#id_warna").multiselect("refresh");
    unmaskInputMoney(); maskInputMoney();
    $("#modalform").modal("show");
  }
  function showDetail(i){
    //data ukuran & warna diambil dari tabel yang berbeda
    var dataDetail = jsonlist.filter(function (index) { return index.id == i }); 
    console.log(dataDetail);
    var getWarna = jsonDetWarna.filter(function (index) { return index.id_bahan == i });
    
    var id_warna = [];
    id_warna = $.map(getWarna, function(el, idx){
       return [el["id_warna"]];
    }); 

    var list_warna = [];
    $.each(id_warna, function(idx, val) {
       list_warna.push($.map(jsonWarna, function(index, value) {
        if(id_warna[idx] == index["id"]){
          return [index["nama"]];
        }
      }));
    });

    $("#det_nama").text(dataDetail[0].nama ? dataDetail[0].nama : '-');
    $("#det_sku").text(dataDetail[0].sku ? dataDetail[0].sku : '-');
    $("#det_kode_barang").text(dataDetail[0].kode_barang ? dataDetail[0].kode_barang : '-');
    $("#det_harga_beli").text(dataDetail[0].harga_beli);
    $("#det_stok").text(dataDetail[0].stok);
    $("#det_berat").text(dataDetail[0].berat);
    $("#det_deskripsi").text(dataDetail[0].deskripsi ? dataDetail[0].deskripsi : '-');

    $("#det_supplier").text(getMasterById(jsonSupplier, dataDetail[0].id_supplier_bahan));
    $("#det_satuan").text(getMasterById(jsonSatuan, dataDetail[0].id_satuan));
    $("#det_gudang").text(getMasterById(jsonGudang, dataDetail[0].id_gudang));
    $("#det_kategori").text(getMasterById(jsonKategori, dataDetail[0].id_kategori_bahan));
    $("#det_warna").text((list_warna.length>0) ? list_warna.join() : '-');
    $("#det_foto").attr("src", "<?php echo base_url('upload/bahan_baku')?>/"+dataDetail[0].foto);
    unmaskInputMoney(); maskInputMoney();
    $("#Viewproduct").modal("show");
  }
  
  function getMasterById(jsonData, id){
    dataNama = '-';
    data = jsonData.filter(function(index) {return index.id == id});
    // console.log(data);
    if(data.length > 0) {
      dataNama = data[0].nama;
      // console.log(data[0].nama);
    }
    return dataNama;
  }
  
  $("#myform").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Bahan_baku/Master/add')?>/";
    if ($("#id").val() != ""){
      action = "<?php echo base_url('Bahan_baku/Master/edit')?>/";
      notifText = 'Data berhasil diubah!';
    }
    unmaskInputMoney();
    var paramImg = new FormData(jQuery('#myform')[0]);
    maskInputMoney(); 
    
    $.ajax({
      url: action,
      type: 'post',
      data: paramImg,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      beforeSend: function() { 
        // tambahkan loading
        $("#aSimpan").prop("disabled", true);
        $('#aSimpan').html('Sedang Menyimpan...');
      },
      success: function (data) {
        if (data.status == '3'){
          console.log("ojueojueokl"+data.status);
          jsonlist = data.list;
          jsonDetWarna = data.list_det_warna;
          // loadData(jsonlist);
          initDataTable.ajax.reload();

          $('#aSimpan').html('Simpan');
          $("#aSimpan").prop("disabled", false);
          $("#modalform").modal('hide');
          new PNotify({
                      title: 'Sukses',
                      text: notifText,
                      type: 'success',
                      hide: true,
                      delay: 5000,
                      styling: 'bootstrap3'
                    });
        }
      }
    });
  });
	
	function deleteData(element){
		var el = $(element).attr("id");
		console.log(el);
		var id  = el.replace("aConfirm","");
		var i = parseInt(id);
		$.ajax({
          type: 'post',
          url: '<?php echo base_url('Bahan_baku/Master/delete'); ?>/',
          data: {"id":i},
		      dataType: 'json',
          beforeSend: function() { 
            // kasi loading
            $("#aConfirm"+i).html("Sedang Menghapus...");
            $("#aConfirm"+i).prop("disabled", true);
          },
          success: function (data) {
            if (data.status == '3'){
              $("#aConfirm"+i).prop("disabled", false);
              initDataTable.ajax.reload();
              new PNotify({
                            title: 'Sukses',
                            text: 'Data berhasil dihapus!',
                            type: 'success',
                            hide: true,
                            delay: 5000,
                            styling: 'bootstrap3'
                          });
            }
          }    
        });
	}
	
	function confirmDelete(el){
		var element = $(el).attr("id");
		console.log(element);
		var id  = element.replace("group","");
		var i = parseInt(id);
    $(el).attr("data-content","<button class=\'btn btn-danger myconfirm\'  href=\'#\' onclick=\'deleteData(this)\' id=\'aConfirm"+i+"\' style=\'min-width:85px\'><i class=\'fa fa-trash\'></i> Ya</button>");
    $(el).popover("show");
	}
  
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
