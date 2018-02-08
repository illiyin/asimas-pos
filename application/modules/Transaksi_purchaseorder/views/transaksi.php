<style type="text/css">
  .product-details input[type="text"]{
    width: 5em !important;
  }
  #productList {
    font-size: 90%;
  }
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <h3><strong>Transaksi</strong> - Purchase Order</h3>
    </div>
  </div>
   <div class="row">
    <div class="col-md-5 left-side">
      <form action="<?php echo base_url('Transaksi_purchaseorder/Transaksi/doSubmit'); ?>" method="post" id="purchaseOrder">          
         <div class="col-xs-8">&nbsp;</div>         
         <div class="col-sm-12">
          <div class="form-group">
            <label class="label-control">Supplier</label>
            <select class="js-select-options form-control" id="supplierSelect" onchange="filterProduk()" name="supplier" required="required">
              <option value="0">Pilih Supplier</option>
            </select>
          </div>
         </div>
         <div class="col-sm-12">
          <div class="form-group">
            <label class="label-control">Catatan</label>
            <textarea name="catatan" class="form-control" placeholder="CATATAN"></textarea>
           </div>
         </div>
         <div class="col-xs-3 table-header text-center">
            <label>PRODUK</label>
         </div>
         <div class="col-xs-3 table-header text-center">
            <label>OPSI</label>
         </div>
         <div class="col-xs-2 table-header text-center">
            <label>QTY</label>
         </div>
         <div class="col-xs-2 table-header text-center">
            <label>HARGA@ (IDR)</label>
         </div>
         <div class="col-xs-2 table-header text-center">
            <label>SUBTOTAL (IDR)</label>
         </div>
         <div id="productList">
            <!-- product List goes here  -->
         </div>
         <div class="footer-section">
            <div class="table-responsive col-sm-12 totalTab">
               <table class="table">
                  <tr>
                     <td class="active" width="40%">Total Qty</td>
                     <td class="whiteBg" width="60%"><span id="Subtot"></span>
                        <span class="float-right"><b><span id="eTotalItem"></span> Item</b></span>
                     </td>
                  </tr>
                  <tr>
                     <td class="active">Total Harga (IDR)</td>
                     <td class="whiteBg light-blue text-bold text-right"><span id="eTotal" class="money"></span></td>
                  </tr>
               </table>
            </div>
            <button type="button" onclick="cancelOrder()" class="btn btn-red col-md-6 flat-box-btn"><h5 class="text-bold">Cancel</h5></button>
            <button type="submit" class="btn btn-green col-md-6 flat-box-btn" data-toggle="modal" data-target="#AddSale" id="btnDoOrder"><h5 class="text-bold">Proses Purchase Order</h5></button>
         </div>
        </form>

      </div>
      <div class="col-md-7 right-side nopadding">
        <div class="row row-horizon" id="kategoriGat">
            <span class="categories selectedGat" id=""><i class="fa fa-home"></i></span>
        </div>
        <div class="col-sm-12">
           <div id="searchContaner">
               <div class="input-group stylish-input-group">
                   <input type="text" id="searchProd" class="form-control"  placeholder="Search" oninput="search()">
                   <span class="input-group-addon">
                       <button type="submit">
                           <span class="glyphicon glyphicon-search"></span>
                       </button>
                   </span>
               </div>
          </div>
        </div>
       <div id="productList2">
       </div>
      </div>
   </div>
</div>
<!-- /.container -->
<script type="text/javascript">
  function maskInputMoney(){
    $('.money').mask('#.##0', {reverse: true});
  }
  function unmaskInputMoney(){
    $('.money').unmask();
  }

  var listProduct = <?php echo $list_produk; ?>;
  var listOrder = <?php echo $list_order; ?>;
  var listSupplier = <?php echo $list_supplier; ?>;
  var listWarna = "";
  var listUkuran = "";
  var tax = '<?php echo $tax; ?>';
  var discount = '<?php echo $discount; ?>';
  var total = '<?php echo $total; ?>';
  var totalItems = '<?php echo $total_items; ?>';
  inits(tax, discount, total, totalItems);
  load_supplier(listSupplier);
  // load_product(listProduct);
  load_order(listOrder);
  function load_supplier(json){
    var html = "";
    $("#supplierSelect").html('');
    html = "<option value='0' selected disabled>Pilih Supplier</option>";
    $("#supplierSelect").append(html);
    for (var i=0;i<json.length;i++){
      html = "<option value=\'"+json[i].id+"\'>"+json[i].nama+"</option>";
      $("#supplierSelect").append(html);
    }
  }
  function load_product(json){
    var html = "";
    var color = 2;
    $("#productList2").html('');
    for (var i=0;i<json.length;i++){
      if(color == 7) { color = 1; }
      var colorClass = 'color0' + color; color++;
      html = "<div class='col-sm-2 col-xs-3' style='display: block;'>"+
              "<a href='javascript:void(0)' class='addPct' id=\'product-"
              // +json[i].id+"\' onclick=\'addToCart("+json[i].id+")\'>"+
              +json[i].id+"\' onclick=\'selectProdukOptions("+json[i].id+")\'>"+
                "<div class='product "+colorClass+" flat-box waves-effect waves-block'>"+
                  "<h3 id='proname'>"+json[i].nama+"</h3>"+
                  "<div class='mask'>"+
                    "<h3>Rp <span class='money'>"+json[i].harga_beli+"</span></h3>"+
                    "<p>"+json[i].deskripsi+"</p>"+
                  "</div>"+
                  // "<img src=\'<?php echo base_url('upload/produk') ?>/"+json[i].foto+"\' alt=\'"+json[i].id_kategori+"\'>"+
                  "<img src='<?php echo base_url('upload/produk')?>/"+json[i].foto+"'>"+
                "</div>"+
              "</a>"+
             "</div>";
      $("#productList2").append(html);
    }
  }
  function load_order(json){
    var html = "";
    var option = "";
    var select = "";
    $("#productList").html("");
      for (var i=0;i<json.length;i++){
        html = "<div class='col-xs-12'>"+
                  "<div class='panel panel-default product-details'>"+
                      "<div class='panel-body' style=''>"+
                          "<div class='col-xs-3 nopadding'>"+
                              "<div class='col-xs-4 nopadding'>"+
                                  "<a href='javascript:void(0)' onclick=delete_order(\'"+json[i].rowid+"\')>"+
                                  "<span class='fa-stack fa-sm productD'>"+
                                    "<i class='fa fa-circle fa-stack-2x delete-product'></i>"+
                                    "<i class='fa fa-times fa-stack-1x fa-fw fa-inverse'></i>"+
                                  "</span>"+
                                  "</a>"+
                              "</div>"+
                              "<div class='col-xs-8 nopadding'>"+
                                "<span class='textPD'>"+json[i].produk+"</span>"+
                              "</div>"+
                          "</div>"+
                          "<div class='col-xs-3'>"+
                            "<span class='textPD'>"+
                              "<span><b>Ukuran:</b> "+json[i].text_ukuran+"</span>"+
                              /*"<select name=ukuran id=\'uk-"+json[i].rowid+"\' class=\'form-control\' onchange=updateOption(\'"+json[i].rowid+"\') title='Pilih Ukuran'>"+
                                "<option value=0 select disabled>Pilih Ukuran</option>"+
                              "</select>"+*/
                            "</span>"+
                            "<span class='textPD'>"+
                              "<span><b>Warna:</b> "+json[i].text_warna+"</span>"+
                              /*"<select name=warna id=\'wr-"+json[i].rowid+"\' class=\'form-control\' onchange=updateOption(\'"+json[i].rowid+"\') title='Pilih Warna'>"+
                                "<option value=0 select disabled>Pilih Warna</option>"+
                              "</select>"+*/
                            "</span>"+
                          "</div>"+
                          "<div class='col-xs-2'>"+
                            "<span class='textPD'>"+
                              "<input id=\'qt-"+json[i].rowid+"\' class='form-control' value='"+json[i].qty+"' placeholder='0' maxlength='4' type='text' onchange=updateQty(\'"+json[i].rowid+"\')>"+
                              "</span>"+
                          "</div>"+
                          "<div class='col-xs-2 nopadding'>"+
                            "<span class='textPD money' style='float:right;'>"+
                              ""+json[i].harga_beli+
                            "</span>"+
                              "<input type=hidden id=\'hb-"+json[i].rowid+"\' class=\'form-control\' value='"+json[i].harga_beli+"'  onchange=updateHargaBeli(\'"+json[i].rowid+"\')>"+
                          "</div>"+
                          "<div class='col-xs-2 nopadding'>"+
                            "<span class='textPD money' style='float:right;'>"+
                              ""+json[i].subtotal+
                            "</span>"+
                            "<input type=hidden id=\'tb-"+json[i].rowid+"\' class=\'form-control\' value='"+json[i].total_berat+"' onchange=updateOption(\'"+json[i].rowid+"\')>"+
                          "</div>"+
                      "</div>"+
                  "</div>"+
              "</div>";
        $("#productList").append(html);
        // loadUkuran(json[i].id, json[i].rowid, listUkuran, json[i].ukuran);
        // loadWarna(json[i].id, json[i].rowid, listWarna, json[i].warna);
      }
  }
  function loadUkuran(rid, id, json, pilih){
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/getUkuran')?>/"+rid,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        var html = "";
        $("#selectUkuran").html('');
        /*html = "<option value='0' selected>Tidak Ada Ukuran</option>";*/
        $("#selectUkuran").append(html);
        for (var i=0;i<data.length;i++){
          var pilihs = "";
          html = "<option value=\'"+data[i].id+"\' "+pilihs+">"+data[i].nama+"</option>";
          $("#selectUkuran").append(html);

        }
      }
    });     
  }
  function updateOption(id){
    var ukuran = $("#uk-"+id).val();
    var warna = $("#wr-"+id).val();
    var totalBerat = $("#tb-"+id).val();
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/updateOption')?>/"+id+"/"+warna+"/"+ukuran+"/"+totalBerat,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        load_order(data);
        fillInformation();
      }
    });    
  }
  function updateHargaBeli(id){
    var hb = $('#hb-'+id).val();
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/updateHargaBeli')?>/"+id+"/"+hb,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        load_order(data);
        fillInformation();
      }
    });    
  }
  function updateQty(id){
    var qty = $("#qt-"+id).val();
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/updateQty')?>/"+id+"/"+qty,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        load_order(data);
        fillInformation();
      }
    });
  }
  function loadWarna(rid, id, json, pilih){
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/getWarna')?>/"+rid,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        var html = "";
        $("#selectWarna").html('');
        /*html = "<option value='0' selected>Tidak Ada Warna</option>";*/
        $("#selectWarna").append(html);
        for (var i=0;i<data.length;i++){
          var pilihs = "";
          html = "<option value=\'"+data[i].id+"\' "+pilihs+">"+data[i].nama+"</option>";
          $("#selectWarna").append(html);
        }
      }
    });    
  }
  function filterProduk(){
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/filterProduk')?>/"+$("#supplierSelect").val(),
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        filterKategori();
        load_product(data);
      }
    });
  }
  function filterKategori(){
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/getKategori')?>/"+$("#supplierSelect").val(),
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        load_kategori(data);
      }
    });    
  }
  function load_kategori(json){
    var html = "";
    $("#kategoriGat").html('');
    // html = "<span class='categories'><i class='fa fa-home'></i></span>";
    html = "<span class='categories selectedGat' onclick=filterProdukByKategori(0) id=\'gat-0\'><i class='fa fa-home'></i></span>";
    $("#kategoriGat").append(html);
    for (var i=0;i<json.length;i++){
      html = "<span class='categories' onclick=filterProdukByKategori(\'"+json[i].id+"\') id=\'gat-"+json[i].id+"\'>"+json[i].nama+"</span>";
      $("#kategoriGat").append(html);
    }
  }
  function filterProdukByKategori(id){
    var keyword = $("#searchProd").val();
    var supplier = $("#supplierSelect").val();
    $( ".categories" ).removeClass('selectedGat');
    $( "#gat-"+id ).addClass( "selectedGat" );
    if(supplier != 0){    
      $.ajax({
        url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/filterProdukByKategori')?>/"+supplier+"/"+id+"/"+keyword,
        type : "GET",
        data :"",
        dataType : "json",
        success : function(data){
          load_product(data);
        }
      });
    }
  }
  function search(){
    var keyword = $("#searchProd").val();
    var supplier = $("#supplierSelect").val();
    var kategori = $(".selectedGat").attr('id');
    var realkategori = "";
    if(kategori != null || kategori != undefined){    
      realkategori = kategori;
    }
    if(supplier != 0){    
      $.ajax({
        url :"<?php echo base_url('Stok_service/Transaksi/filterProdukByName')?>",
        type : "POST",
        data : "keyword="+keyword+"&supplier="+supplier+"&kategori="+realkategori,
        dataType : "json",
        success : function(data){
          load_product(data);
        }
      });
    }
  }
  function selectProdukOptions(id){
    if(id != '') {
      $.confirm({
        title: 'Opsi Produk',
        content: '' +
        '<form action="" class="" method="post">' +
        '<div class="form-group">' +
        '<label>Pilih Ukuran Produk</label>' +
        '<select id=\'selectUkuran\' class=\'form-control\'>'+'</select>'
        + '</div>' +
        '<div class="form-group">' +
        '<label>Pilih Warna Produk</label>' +
        '<select id=\'selectWarna\' class=\'form-control\'>'+'</select>'
        + '</div>' +
        '</form>',
        buttons: {
            formSubmit: {
                text: 'Pilih',
                btnClass: 'btn-blue',
                action: function () {
                    var selectUkuran = this.$content.find('#selectUkuran').val() || 0;
                    var selectWarna = this.$content.find('#selectWarna').val() || 0;

                    if(selectUkuran == 0 || selectWarna == 0) {
                      $.alert('Anda belum memilih ukuran/warna!');
                    }
                    else {
                      addToCart(id);
                    }
                }
            },
            cancel: function () { },
        },
        onContentReady: function () {
            loadUkuran(id);
            loadWarna(id);
            // bind to events
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
    }
  }
  function addToCart(id){
    var idSupplier = $("#supplierSelect").val();
    var idUkuran = $("#selectUkuran").val();
    var idWarna = $("#selectWarna").val();
    if(idSupplier == '' || idSupplier == null) {
      $.alert({
          title: 'Perhatian',
          content: 'Anda belum memilih Supplier!',
      }); 
    }
    else {  
      $.ajax({
        url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/tambahCart')?>/"+id,
        type : "POST",
        data : {'idSupplier': idSupplier, 'idUkuran': idUkuran, 'idWarna': idWarna},
        dataType : "json",
        success : function(data){
          load_order(data);
          fillInformation();
        }
      });
    }
  }
  function delete_order(id){
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/deleteCart')?>/"+id,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        load_order(data);
        fillInformation();
      }
    });    
  }
  function changeOption(id){
    var qty = $("#qt-"+id).val();
    var option = $("#stok-"+id).val();
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/updateOption')?>/"+id+"/"+option,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        load_order(data);
        fillInformation();
      }
    });
  }
  function add_qty(id){
    var lastValue = $("#qt-"+id).val();
    lastValue = parseInt(lastValue) + 1;
    $("#qt-"+id).val(lastValue);
    change_total(id, 'tambah');
  }
  function reduce_qty(id){
    var lastValue = $("#qt-"+id).val();
    if(parseInt(lastValue) > 1){    
      lastValue = parseInt(lastValue) - 1;
      $("#qt-"+id).val(lastValue);
    }else{
      delete_order(id);
    }
    change_total(id, 'kurang');
  }
  function change_total(id, state){
    var qty = $("#qt-"+id).val();
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/updateCart')?>/"+id+"/"+qty+"/"+state,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        console.log(data);
        load_order(data);
        fillInformation();
      }
    });
  }
  function inits(etax, ediscount, etotal, etotal_items){
    unmaskInputMoney();
    $("#eTax").val(etax);
    $("#eDiscount").val(ediscount);
    $("#eTotal").html(etotal);    
    $("#eTotalItem").html(etotal_items);    
    maskInputMoney();
  }
  function fillInformation(){
    $.ajax({
      url :"<?php echo base_url('Transaksi_purchaseorder/Transaksi/getTotal')?>",
      type : "GET",
      data :"",
      success : function(data){        
        var jsonObjectParse = JSON.parse(data);
        var jsonObjectStringify = JSON.stringify(jsonObjectParse);
        var jsonObjectFinal = JSON.parse(jsonObjectStringify);
        var etx = jsonObjectFinal.tax;
        var edc = jsonObjectFinal.discount;
        var etl = jsonObjectFinal.total;
        var eti = jsonObjectFinal.total_items; 
        inits(etx, edc, etl, eti);
        totalItems = eti;
      }
    });    
  }
  function cancelOrder(){
      $.confirm({
          title: 'Batal',
          content: 'Batalkan Transaksi ?',
          buttons: {
              confirm: function () {
                  doClear();
              },
              cancel: function () {
                  // $('#btnDoOrder').html("<h5 class=\'text-bold\'>Cancel</h5>");
                  // $("#btnDoOrder").prop("disabled", false);  
              }
          }
      });    
  }
  function doClear(){
    $('#btnDoOrder').html("<h5 class=\'text-bold\'>Clearing...</h5>");
    $("#btnDoOrder").prop("disabled", true);    
    $.ajax({
      url :'<?php echo base_url("Transaksi_purchaseorder/Transaksi/destroyCart"); ?>',
      type : $('#purchaseOrder').attr('method'),
      data : $('#purchaseOrder').serialize(),
      dataType : "json",
      success : function(data){
        // console.log(data);        
        load_order(data);
        fillInformation();        
        $('#btnDoOrder').html("<h5 class=\'text-bold\'>Servis Stok</h5>");
        $("#btnDoOrder").prop("disabled", false);
        // window.close();
        window.location.reload(false);
      }
    });    
  }
  function doSubmit(){
    $.ajax({
      url :$('#purchaseOrder').attr('action'),
      type : $('#purchaseOrder').attr('method'),
      data : $('#purchaseOrder').serialize(),
      dataType : "json",
      success : function(data){        
        load_order(data);
        fillInformation();        
        $('#btnDoOrder').html("<h5 class=\'text-bold\'>Proses Purchase Order</h5>");
        $("#btnDoOrder").prop("disabled", false);
        // window.close();
        window.location.reload(false);
      }
    });    
  }
  $(document).ready(function(){
    $("#purchaseOrder").on('submit', function(e){
      $('#btnDoOrder').html("<h5 class=\'text-bold\'>Saving...</h5>");
      $("#btnDoOrder").prop("disabled", true);
      e.preventDefault();
      $.confirm({
          title: 'Konfirmasi Purchase Order',
          content: 'Yakin ingin purchase order ?',
          buttons: {
              confirm: function () {
                  doSubmit();
              },
              cancel: function () {
                $('#btnDoOrder').html("<h5 class=\'text-bold\'>Proses Purchase Order</h5>");
                $("#btnDoOrder").prop("disabled", false);
              }
          }
      });      
    });
  });
</script>