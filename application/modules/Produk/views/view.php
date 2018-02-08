<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
   <div class="row">
    <h3><strong>Produk</strong> - Semua Produk</h3>
   </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center no-sort">Foto</th>
                  <th class="text-center">Nama Produk</th>
                  <th class="text-center">Merk</th>
                  <th class="text-center">SKU</th>
                  <th class="text-center">Total Stok</th>
                  <th class="text-center">Detail Stok</th>
                  <th class="text-center">Harga Jual Normal (IDR)</th>
                  <th class="text-center">Tanggal Buat</th>
                  <th class="text-center no-sort">Aksi</th>
              </tr>
          </thead>

          <tbody id='bodytable'>
            
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <button type="button" class="btn btn-add btn-lg"  onclick="showAdd()">
     Tambah Produk
   </button>
</div>
<!-- /.container -->
<!-- Modal Detail Product -->
<div class="modal fade" id="Viewproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document" id="viewModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="view">Detail Produk</h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div id="viewSectionProduct">
              <!-- view goes here -->
              <div class="col-md-12"><div class="media">
                 <div class="media-left">
                    <img id="det_foto" class="media-object img-rounded" src="<?php echo base_url()?>upload/produk/placeholder.png" alt="image" width="200px">
                 </div>
                 <div class="media-body">
                  <h1 class="media-heading" id="det_nama">sfsdg</h1>
                  <div class="row">
                    <div class="col-sm-6">
                      <p><b>Merk :</b> <span id="det_merk"></span></p>
                      <p><b>SKU :</b> <span id="det_sku"></span></p>
                      <p><b>Kode Barang :</b> <span id="det_kode_barang"></span></p>
                      <p><b>Harga Beli :</b> Rp <span id="det_harga_beli" class="money"></span></p>
                      <p><b>Harga Jual Normal :</b> Rp <span id="det_harga_jual_normal" class="money"></span></p>
                      <p><b>Stok :</b> <span id="det_stok"></span></p>
                      <p><b>Berat :</b> <span id="det_berat" class="money"></span> gram</p>
                      <p><b>Deskripsi :</b> <span id="det_deskripsi"></span></p>
                    </div>
                    <div class="col-sm-6">
                      <p><b>Supplier :</b> <span id="det_supplier"></span></p>
                      <p><b>Satuan :</b> <span id="det_satuan"></span></p>
                      <p><b>Gudang :</b> <span id="det_gudang"></span></p>
                      <p><b>Kategori :</b> <span id="det_kategori"></span></p>
                      <p><b>Bahan :</b> <span id="det_bahan"></span></p>
                      <p><b>Katalog :</b> <span id="det_katalog"></span></p>
                      <p><b>Ukuran :</b> <span id="det_ukuran"></span></p>
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

<!-- Modal harga -->
  <div class="modal fade" id="modalharga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document" id="stockModal">
      <div class="modal-content">
        <form action="" id="formHarga" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalHargaTitle">Harga Jual</h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div>
             <!-- content -->
              <table class="table">
                <thead>
                  <tr>
                    <th>Level Customer</th>
                    <th>Harga Jual</th>
                  </tr>
                </thead>
                <tbody class="itemslist">
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id_produk" placeholder="ID Produk">
                  <?php foreach (json_decode($list_customer_level) as $cust_level) { ?>
                    <tr>
                      <td><p><?php echo $cust_level->nama?></p></td>
                      <td>
                        <div class="form-group">
                          <div class="input-group">
                            <span class="input-group-addon">Rp</span>
                            <input type="text" name="harga_<?php echo $cust_level->id?>" id="harga_<?php echo $cust_level->id?>" class="form-control money" placeholder="Harga untuk <?php echo $cust_level->nama?>">
                          </div>
                          </div>
                        </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal">Batal</button>
          <button type="submit" id="hSimpan" class="btn btn-add hiddenpr">Simpan</button>
        </div>
       </form> 
      </div>
   </div>
  </div>
  <!-- /.Modal -->


<!-- Modal Barcode -->
<div class="modal fade" id="modalbarcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Daftar Barcode <span id="barcodeTitle"></span></h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-lg-12"  id="body-barcode">
           <table id="barcodeTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center">Ukuran</th>
                  <th class="text-center">Warna</th>
                  <th class="text-center">Barcode</th>
                </tr>
              </thead>
              <tbody id='bodytable'>
                
              </tbody>
            </table>
           </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
 </div>
</div>
<!-- /.Modal Barcode-->

<!-- Modal Add -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Produk</h4>
      </div>
      <form action="" method="POST" id="myform" enctype="multipart/form-data"> <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama">Nama Produk</label>
                 <input type="text" name="nama" maxlength="50" Required class="form-control" id="nama" placeholder="Nama Produk">
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Produk">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_merk">Merk Produk</label>
                 <select name="id_merk" class="form-control" id="id_merk" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_supplier">Supplier Produk</label>
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
                 <label for="id_ukuran">Ukuran</label>
                 <select name="id_ukuran[]" class="form-control" id="id_ukuran" multiple="" required="required">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_warna">Warna</label>
                 <select name="id_warna[]" class="form-control" id="id_warna" multiple="" required="required">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_gudang">Gudang</label>
                 <select name="id_gudang" class="form-control" id="id_gudang" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kategori">Kategori Produk</label>
                 <select name="id_kategori" class="form-control" id="id_kategori" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_bahan">Bahan Produk</label>
                 <select name="id_bahan" class="form-control" id="id_bahan" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_katalog">Katalog</label>
                 <select name="id_katalog" class="form-control" id="id_katalog" required="">
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
                  <input type="text" name="harga_beli" min="0" Required class="form-control money" id="harga_beli" placeholder="Harga Beli">
                 </div>
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="harga_jual_normal">Harga Jual Normal (IDR)</label>
                 <div class="input-group">
                  <span class="input-group-addon">Rp</span> 
                  <input type="text" name="harga_jual_normal" min="0" Required class="form-control money" id="harga_jual_normal" placeholder="Harga Jual Normal">
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
                 <input type="file" name="foto" accept="image/png, image/jpeg" Required id="foto" placeholder="Foto">
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

  //initialize barcode datatable
  var barcodeTable = $("#barcodeTable").DataTable({
    "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": "no-sort"
        } ],
        "order": [[ 1, 'ASC' ]]
  });
  barcodeTable.on( 'order.dt search.dt', function () {
        barcodeTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<span style='display:block' class='text-center'>"+(i+1)+"</span>";
        } );
  } ).draw();

  
  var jsonlist = <?php echo $list; ?>;
  var jsonCustomerLevel = <?php echo $list_customer_level; ?>;
  var jsonSupplier = <?php echo $list_supplier; ?>;
  var jsonSatuan = <?php echo $list_satuan; ?>;
  var jsonGudang = <?php echo $list_gudang; ?>;
  var jsonKategori = <?php echo $list_kategori; ?>;
  var jsonBahan = <?php echo $list_bahan; ?>;
  var jsonKatalog = <?php echo $list_katalog; ?>;
  var jsonMerk = <?php echo $list_merk; ?>;
  
  var jsonUkuran = <?php echo $list_ukuran; ?>;
  var jsonWarna = <?php echo $list_warna; ?>;
  var jsonDetUkuran = <?php echo $list_det_ukuran; ?>;
  var jsonDetWarna = <?php echo $list_det_warna; ?>;
  var jsonDetHarga = <?php echo $list_det_harga; ?>;
  
  var awalLoad = true;
  var initDataTable = $('#TableMainServer').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "order": [[7, 'DESC']],
      "ajax":{
            url :"<?php echo base_url()?>Produk/Master/data",
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
    load_select_option(jsonBahan, "#id_bahan", "Bahan");
    load_select_option(jsonKatalog, "#id_katalog", "Katalog");
    load_select_option(jsonMerk, "#id_merk", "Merk");
    load_select_option(jsonUkuran, "#id_ukuran", "");
    load_select_option(jsonWarna, "#id_warna","");
    $("#id_ukuran").multiselect({
      buttonWidth: '100%',
      inheritClass: true,
      enableFiltering: true,
      includeSelectAllOption: true,
      nonSelectedText: "Pilih Ukuran"
    });
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
    maskInputMoney();
    $("#myModalLabel").text("Tambah Produk");
    $("#id").val("");
    $("#nama").val("");
    $("#id_supplier").val("");
    $("#id_satuan").val("");
    $("#id_gudang").val("");
    $("#id_kategori").val("");
    $("#id_bahan").val("");
    $("#id_katalog").val("");
    $("#id_merk").val("");
    $("#id_ukuran").multiselect('refresh');
    $("#id_warna").multiselect('refresh');
    $("#sku").val("");
    $("#kode_barang").val("");
    $("#berat").val("");
    $("#harga_beli").val("");
    $("#harga_jual_normal").val("");
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
    var getUkuran = jsonDetUkuran.filter(function (index) { return index.id_produk == i }); 
    var getWarna = jsonDetWarna.filter(function (index) { return index.id_produk == i });
    var id_ukuran = [];
    id_ukuran = $.map(getUkuran, function(el, idx){
       return [el["id_ukuran"]];
    }); 
    var id_warna = [];
    id_warna = $.map(getWarna, function(el, idx){
       return [el["id_warna"]];
    }); 

    $("#myModalLabel").text("Ubah Produk");
    $("#id").val(dataUpdate[0].id);
    $("#nama").val(dataUpdate[0].nama);
    $("#id_supplier").val((dataUpdate[0].id_supplier==0) ? "" : dataUpdate[0].id_supplier);
    $("#id_satuan").val((dataUpdate[0].id_satuan==0) ? "" : dataUpdate[0].id_satuan);
    $("#id_gudang").val((dataUpdate[0].id_gudang==0) ? "" : dataUpdate[0].id_gudang);
    $("#id_kategori").val((dataUpdate[0].id_kategori==0) ? "" : dataUpdate[0].id_kategori);
    $("#id_bahan").val((dataUpdate[0].id_bahan==0) ? "" : dataUpdate[0].id_bahan);
    $("#id_katalog").val((dataUpdate[0].id_katalog==0) ? "" : dataUpdate[0].id_katalog);
    $("#id_merk").val((dataUpdate[0].id_merk==0) ? "" : dataUpdate[0].id_merk);
    $("#sku").val(dataUpdate[0].sku);
    $("#kode_barang").val(dataUpdate[0].kode_barang);
    $("#berat").val(dataUpdate[0].berat);
    $("#harga_beli").val(dataUpdate[0].harga_beli);
    $("#harga_jual_normal").val(dataUpdate[0].harga_jual_normal);
    $("#deskripsi").val(dataUpdate[0].deskripsi);
    $("#foto").attr("required", false);
    $("#foto").fileinput("clear");

    var file_source = dataUpdate[0].foto || "placeholder.png";
    fotoInitialPreview("<?php echo base_url();?>"+ "upload/produk/" + file_source, file_source);
    
    $("#id_ukuran").val(id_ukuran);
    $("#id_ukuran").multiselect("refresh");
    $("#id_warna").val(id_warna);
    $("#id_warna").multiselect("refresh");
    unmaskInputMoney(); maskInputMoney();
    $("#modalform").modal("show");
  }
  function showDetail(i){
    //data ukuran & warna diambil dari tabel yang berbeda
    var dataDetail = jsonlist.filter(function (index) { return index.id == i }); 
    console.log(dataDetail);
    var getUkuran = jsonDetUkuran.filter(function (index) { return index.id_produk == i }); 
    var getWarna = jsonDetWarna.filter(function (index) { return index.id_produk == i });
    
    var id_ukuran = [];
    id_ukuran = $.map(getUkuran, function(el, idx){
       return [el["id_ukuran"]];
    }); 
    var id_warna = [];
    id_warna = $.map(getWarna, function(el, idx){
       return [el["id_warna"]];
    }); 

    var list_ukuran = [];
    $.each(id_ukuran, function(idx, val) {
       list_ukuran.push($.map(jsonUkuran, function(index, value) {
        if(id_ukuran[idx] == index["id"]){
          return [index["nama"]];
        }
      }));
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
    $("#det_harga_jual_normal").text(dataDetail[0].harga_jual_normal);
    $("#det_stok").text(dataDetail[0].stok);
    $("#det_berat").text(dataDetail[0].berat);
    $("#det_deskripsi").text(dataDetail[0].deskripsi ? dataDetail[0].deskripsi : '-');

    $("#det_supplier").text(getMasterById(jsonSupplier, dataDetail[0].id_supplier));
    $("#det_satuan").text(getMasterById(jsonSatuan, dataDetail[0].id_satuan));
    $("#det_gudang").text(getMasterById(jsonGudang, dataDetail[0].id_gudang));
    $("#det_kategori").text(getMasterById(jsonKategori, dataDetail[0].id_kategori));
    $("#det_bahan").text(getMasterById(jsonBahan, dataDetail[0].id_bahan));
    $("#det_katalog").text(getMasterById(jsonKatalog, dataDetail[0].id_katalog));
    $("#det_merk").text(getMasterById(jsonMerk, dataDetail[0].id_merk));
    $("#det_ukuran").text((list_ukuran.length>0) ? list_ukuran.join() : '-');
    $("#det_warna").text((list_warna.length>0) ? list_warna.join() : '-');
    $("#det_foto").attr("src", "<?php echo base_url('upload/produk')?>/"+dataDetail[0].foto);
    unmaskInputMoney(); maskInputMoney();
    $("#Viewproduct").modal("show");
  }
  function showHarga(i){
    $("#modalharga :input").val(""); //empty inputs first!
    $("#id_produk").val(i);
    var getHarga = jsonDetHarga.filter(function (index) { return index.id_produk == i });

    $.each(getHarga, function(i) {
      $("#modalharga #harga_"+getHarga[i].id_customer_level).val(getHarga[i].harga);
      // console.log(getHarga[i].harga);
    });
    unmaskInputMoney(); maskInputMoney();
    $("#modalharga").modal("show");
  }

  function showBarcode(id){
    $.ajax({
      url :"<?php echo base_url('Produk/Master/show_barcode')?>/"+id,
      type : "GET",
      dataType: 'json',
      success : function(data){
        loadBarcodeData(id, data);
      }
    });       
    $("#modalbarcode").modal("show");
  }
  function loadBarcodeData(id, json){
    barcodeTable.clear().draw();

    for (var i=0; i<json.length; i++){
      barcodeTable.row.add([
          '',
          json[i].nama_ukuran,
          json[i].nama_warna,
          'P'+id + 'U'+json[i].id_ukuran + 'W'+json[i].id_warna,
        ]).draw();
    }
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
    var action = "<?php echo base_url('Produk/Master/add')?>/";
    if ($("#id").val() != ""){
      action = "<?php echo base_url('Produk/Master/edit')?>/";
      notifText = 'Data berhasil diubah!';
    }
    // var param = $('#myform').serialize();
    unmaskInputMoney(); //clean input masking first
    var paramImg = new FormData(jQuery('#myform')[0]);
    maskInputMoney(); //re run masking
    // if ($("#id").val() != ""){
    //   paramImg = new FormData(jQuery('#myform')[0])+"&id="+$('#id').val();
    //   // param = $('#myform').serialize()+"&id="+$('#id').val();
    // }
    
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
          jsonDetUkuran = data.list_det_ukuran;
          jsonDetWarna = data.list_det_warna;
          // loadData(jsonlist);
          initDataTable.ajax.reload();

          $('#aSimpan').html('Simpan');
          $("#aSimpan").prop("disabled", false);
          $("#modalform").modal('hide');
          // $("#notif-top").fadeIn(500);
          // $("#notif-top").fadeOut(2500);
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
  $("#formHarga").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil diubah!';
    var action = "<?php echo base_url('Produk/Master/add_det_harga')?>/";
    unmaskInputMoney(); //clean input masking first
    var param = $('#formHarga').serialize()+"&id="+$('#id_produk').val();
    maskInputMoney(); //re run masking
	  
    $.ajax({
      url: action,
      type: 'post',
      data: param,
	    dataType: 'json',
      beforeSend: function() { 
        // tambahkan loading
        $("#hSimpan").prop("disabled", true);
        $('#hSimpan').html('Sedang Menyimpan...');
      },
      success: function (data) {
        if (data.status == '3'){
          console.log("ojueojueokl"+data.status);
          jsonDetHarga = data.list;
          $('#hSimpan').html('Simpan');
          $("#hSimpan").prop("disabled", false);
  				$("#modalharga").modal('hide');
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
		//console.log(jsonlist[i]);
		$.ajax({
          type: 'post',
          url: '<?php echo base_url('Produk/Master/delete'); ?>/',
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
              // $("#notif-top").fadeIn(500);
              // $("#notif-top").fadeOut(2500);
              new PNotify({
                            title: 'Sukses',
                            text: 'Data berhasil dihapus!',
                            type: 'success',
                            hide: true,
                            delay: 5000,
                            styling: 'bootstrap3'
                          });
              // jsonlist = data.list;
              // loadData(jsonlist);
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
