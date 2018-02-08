<style type="text/css">
  .product-details input[type="text"]{
    width: 4em !important;
  }
  #productList {
    font-size: 90%;
  }
</style>
<?php
  // echo "<pre>";
  // print_r(isset($_SESSION['cart_contents']) ? $_SESSION['cart_contents'] : '');
  // echo "</pre>";
?>
<div class="container-fluid">
   <div class="row">
    <div class="col-sm-12">
      <h3><strong>Transaksi</strong> - Retur</h3>
    </div> 
   </div>
   <div class="row">
    <div class="col-md-5 left-side">
      <form action="<?php echo base_url('Transaksi_retur/Transaksi/save'); ?>" method="post" id="formretur">         
        <div class="col-xs-8"> &nbsp; </div>           
         <div class="col-sm-8">
          <div class="form-group">
            <label class="label-control">Customer</label class="label-control">
            <select class="js-select-options form-control" id="customerSelect" name="idCustomer" required="required">
              <option value="0">Pilih Customer</option>
            </select>
          </div>
         </div>
         <div class="col-sm-4">
          <div class="form-group">
            <label class="label-control">ID Order</label class="label-control">
            <select class="js-select-options form-control" id="orderSelect" name="idOrder" required="required" onchange="filterProduk()">
              <option value="0">Pilih ID Order</option>
            </select>
          </div>
         </div>
         <div class="col-sm-12">
          <div class="form-group">
            <label class="label-control">Catatan</label class="label-control">
            <textarea class="form-control" name="catatan" placeholder="Catatan"></textarea>
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
            <label>HARGA JUAL@ (IDR)</label>
         </div>
         <div class="col-xs-2 table-header text-center nopadding">
            <label>SUBTOTAL</label>
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
                  <!-- <tr>
                     <td class="active">TAX</td>
                     <td class="whiteBg"><input type="text" value="" id="eTax" class="total-input TAX" placeholder="N/A"  maxlength="5">
                        <span class="float-right"><b id="taxValue"></b></span>
                     </td>
                  </tr>
                  <tr>
                     <td class="active">Discount</td>
                     <td class="whiteBg">
                        <input type="text" value="" id="eDiscount" class="total-input Remise" placeholder="N/A"  maxlength="5">
                        <span class="float-right"><b id="RemiseValue"></b></span>
                     </td>
                  </tr> -->
                  <tr>
                     <td class="active">Total Harga (IDR)</td>
                     <td class="whiteBg light-blue text-bold text-right"><span id="eTotal" class="money"></span></td>
                  </tr>
               </table>
            </div>
            <button type="button" onclick="cancelOrder()" class="btn btn-red col-md-6 flat-box-btn"><h5 class="text-bold">Cancel</h5></button>
            <button type="submit" class="btn btn-green col-md-6 flat-box-btn" id="btnRetur"><h5 class="text-bold">Proses Retur</h5></button>
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

  var currentCustomer = 0;
  var currentIdOrder = 0;
  var currentQty = 0;
  var currentCustomerId = 0;
  var listOrder = <?php echo $list_order; ?>;
  var listCustomer = <?php echo $list_customer; ?>;
  //var listKategori = <?php /*echo $list_kategori;*/ ?>;
  var listWarna = <?php echo $list_warna; ?>;
  var listUkuran = <?php echo $list_ukuran; ?>;
  var tax = '<?php echo $tax; ?>';
  var discount = '<?php echo $discount; ?>';
  var total = '<?php echo $total; ?>';
  var totalItems = '<?php echo $total_items; ?>';
  maskInputMoney();
  inits(tax, discount, total, totalItems);
  load_customer(listCustomer);
  load_order(listOrder);

  function load_customer(json){
    var html = "";
    $("#customerSelect").html('');
    html = "<option value='0' selected disabled>Pilih Customer</option>";
    $("#customerSelect").append(html);
    for (var i=0;i<json.length;i++){
      html = "<option value=\'"+json[i].id+"\'>"+json[i].nama+"</option>";
      $("#customerSelect").append(html);
    }
  }
  $("#customerSelect").on("select2:open", function (e) { 
    saveCurrentCustomer();
  });
  $("#customerSelect").on("select2:select", function (e) { 
    changeCustomer();
  });
  function saveCurrentCustomer() {
    currentCustomerId = $("#customerSelect :selected").val();
  }
  function changeCustomer() {
    var idCustomer = $("#customerSelect").val();
    var productList = $("#productList");
    if(productList.html().length > 0) {
      $.confirm({
          title: 'Konfirmasi',
          content: 'Anda yakin ingin mengganti customer?',
          buttons: {
              ok: function () {
                //clear server cart first
                doClear(false); 
                //change customer  
                filterAvaiableOrder();
              },
              cancel: function () {
                //returning to previous selected value
                $("#customerSelect").val(idCustomer);
                $("#customerSelect").trigger('change.select2'); // Notify only Select2 of changes
                saveCurrentCustomer();
              }
            }
      }); 
    }
    else {
      filterAvaiableOrder();
      //what?
    }
  }
  function filterAvaiableOrder(){
    var idCustomer = $("#customerSelect").val();
    $.ajax({
      url :"<?php echo base_url('Transaksi_retur/Transaksi/getAvailableOrder')?>/"+idCustomer,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        clear_orderselect();
        load_available_order(data);
      }
    });
    load_kategori('');
  }
  function load_available_order(json){
    var html = "";
    $("#orderSelect").html('');
    html = "<option value='0' selected disabled>Pilih Order</option>";
    $("#orderSelect").append(html);
    for (var i=0;i<json.length;i++){
      html = "<option value=\'"+json[i].id+"\'>"+json[i].id+"</option>";
      $("#orderSelect").append(html);
    }    
  }
  function load_product(json){
    var html = "";
    var color = 2; 
    $("#productList2").html('');
    for (var i=0;i<json.length;i++){
      if(color == 7) { color = 1; }
      var colorClass = 'color0' + color; color++;
      html = "<div class='col-sm-2 col-xs-4' style='display: block;'>"+
              "<a href='javascript:void(0)' class='addPct' id=\'product-"+json[i].id+"\' onclick=\'addToCart("+json[i].id+", "+json[i].id_detail_order+","+json[i].id_ukuran+","+json[i].id_warna+")\'>"+
                "<div class='product "+colorClass+" flat-box waves-effect waves-block'>"+
                  "<h3 id='proname'>"+json[i].nama+
                  "<br><small style='color:white;'>"+json[i].nama_ukuran+"</small>"+
                  "<br><small style='color:white;'>"+json[i].nama_warna+"</small></h3>"+
                  "<div class='mask'>"+
                    "<h3>Rp <span class='money'>"+json[i].harga_jual+"</span></h3>"+
                    // "<p>"+json[i].deskripsi+"</p>"+
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
    // console.log(json);
    // console.log(json.length);
    $("#productList").html("");
      for (var i=0;i<json.length;i++){
        // option = json[i].options;
        // select = "stok-"+json[i].rowid;
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
                            "<span class='textPD'>"
                              +"<span><b>Ukuran:</b> "+json[i].text_ukuran+"</span>"+
                            "</span>"+
                            "<span class='textPD'>"
                              +"<span><b>Warna:</b> "+json[i].text_warna+"</span>"+
                            "</span>"+
                          "</div>"+
                          "<div class='col-xs-2 text-center'>"+
                            "<input id=\'qt-"+json[i].rowid+"\' class='form-control' value='"+json[i].qty+"' placeholder='0' maxlength='2' type='text' onfocus=saveCurrentQty(\'"+json[i].rowid+"\') onchange=updateQty(\'"+json[i].rowid+"\')>"+
                          "</div>"+
                          "<div class='col-xs-2 nopadding text-right'>"+json[i].harga_beli+"</div>"+
                          "<div class='col-xs-2 nopadding text-right'>"+json[i].subtotal+"</div>"+
                      "</div>"+
                  "</div>"+
              "</div>";
        $("#productList").append(html);
        loadUkuran(json[i].rowid, listUkuran, json[i].ukuran);
        loadWarna(json[i].rowid, listWarna, json[i].warna);
      }
  }
  function loadUkuran(id, json, pilih){
    var html = "";
    $("#uk-"+id).html('');
    html = "<option value='0' disabled>Pilih Ukuran</option>";
    $("#uk-"+id).append(html);
    for (var i=0;i<json.length;i++){
      var pilihs = "";
      if(json[i].id == pilih){
        pilihs = "selected";
      }
      html = "<option value=\'"+json[i].id+"\' "+pilihs+">"+json[i].nama+"</option>";
      $("#uk-"+id).append(html);
    }
  }
  function updateOption(id){
    var ukuran = $("#uk-"+id).val();
    var warna = $("#wr-"+id).val();
    var totalBerat = $("#tb-"+id).val();
    $.ajax({
      url :"<?php echo base_url('Transaksi_retur/Transaksi/updateOption')?>/"+id+"/"+warna+"/"+ukuran+"/"+totalBerat,
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
      url :"<?php echo base_url('Transaksi_retur/Transaksi/updateHargaBeli')?>/"+id+"/"+hb,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        load_order(data);
        fillInformation();
      }
    });    
  }

  $("#customerSelect").on("select2:open", function() {
    currentCustomer = $(this).val();
    // console.log("currentCustomer: "+currentCustomer);
  });
  $("#orderSelect").on("select2:open", function() {
    currentIdOrder = $(this).val();
    // console.log("currentIdOrder: "+currentIdOrder);
  });
  function saveCurrentQty(id) {
    currentQty = $("#qt-"+id).val() || 0;
    // console.log("currentQty: "+currentQty);
  };
  function updateQty(id){
    var idCustomer = $("#customerSelect").val() || '';
    var idOrder = $("#orderSelect").val() || '';
    var qty = $("#qt-"+id).val();
    if((idCustomer != '') && (idOrder != '')) {
      $.ajax({
        url :"<?php echo base_url('Transaksi_retur/Transaksi/updateQty')?>/"+id+"/"+qty,
        type : "POST",
        data : {'id_customer': idCustomer, 'id_order': idOrder},
        dataType : "json",
        success : function(data){
          if(data.status == 1) {
            load_order(JSON.parse(data.getOrder));
            fillInformation();
          }
          else if(data.status == 0){
            var list = data.list;
            $.confirm({
                title: 'Produk',
                content: 'Inputan melebihi Qty produk terjual!'
                          +'<br>Qty produk terjual: <b>' + list.jumlah + '</b>',
                buttons: {
                    ok: function () {
                      $("#qt-"+id).val(list.jumlah);
                      saveCurrentQty();
                    }
                }
            });
          }
        }
      });
    }
    else {
      $.alert({
          title: 'Perhatian!',
          content: 'Anda belum memilih Customer/ID Order!'
      });
      $("#qt-"+id).val(currentQty);
      saveCurrentQty(id);
    }
  }
  function loadWarna(id, json, pilih){
    var html = "";
    $("#wr-"+id).html('');
    html = "<option value='0' selected disabled>Pilih Warna</option>";
    $("#wr-"+id).append(html);
    for (var i=0;i<json.length;i++){
      var pilihs = "";
      if(json[i].id == pilih){
        pilihs = "selected";
      }      
      html = "<option value=\'"+json[i].id+"\' "+pilihs+">"+json[i].nama+"</option>";
      $("#wr-"+id).append(html);
    }
  }
  function payment(){
    $("#modalpayment").modal("show");
  }
  function filterProduk(){
    var idOrder = $("#orderSelect").val();
    $.ajax({
      url :"<?php echo base_url('Transaksi_retur/Transaksi/getAvailableProduk')?>/"+idOrder,
      type : "GET",
      data : "",
      dataType : "json",
      success : function(data){
        load_product(data);
        filterKategori();
      }
    });
  }
  function filterKategori(){
    var idOrder = $("#orderSelect").val() || '';
    $.ajax({
      url :"<?php echo base_url('Transaksi_retur/Transaksi/getKategori')?>/"+idOrder,
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
    html = "<span class='categories selectedGat' onclick=filterProdukByKategori(0) id=\'gat-0\'><i class='fa fa-home'></i></span>";
    $("#kategoriGat").append(html);
    for (var i=0;i<json.length;i++){
      html = "<span class='categories' onclick=filterProdukByKategori(\'"+json[i].id+"\') id=\'gat-"+json[i].id+"\'>"+json[i].nama+"</span>";
      $("#kategoriGat").append(html);
    }
  }
  function filterProdukByKategori(id){
    var keyword = $("#searchProd").val() || '';
    var order = $("#orderSelect").val() || '';
    $( ".categories" ).removeClass('selectedGat');
    $( "#gat-"+id ).addClass( "selectedGat" );

    if(order != 0){    
      $.ajax({
        url :"<?php echo base_url('Transaksi_retur/Transaksi/filterProdukByKategori')?>/"+order+"/"+id+"/"+keyword,
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
    var customer = $("#customerSelect").val() || '';
    var id_order = $("#orderSelect").val() || '';
    if(customer != '' && id_order != '') {
      $.ajax({
        url :"<?php echo base_url('Transaksi_retur/Transaksi/filterProdukByName')?>",
        type : "POST",
        data : "keyword="+keyword
                +"&customer="+customer
                +"&id_order="+id_order,
        dataType : "json",
        success : function(data){
          load_product(data);
        }
      });
    }
    else {
      $.alert({
          title: 'Perhatian!',
          content: 'Anda belum memilih Customer/ID Order!'
      });
    }
  }  
  function addToCart(id, idDetailOrder, idUkuran=0, idWarna=0){
    var idCustomer = $("#customerSelect").val() || '';
    var idOrder = $("#orderSelect").val() || '';
    var qty = $("#qt-"+id).val() || 0;

    if((idCustomer != '') && (idOrder != '')) {
      $.ajax({
        url :"<?php echo base_url('Transaksi_retur/Transaksi/tambahCart')?>/"+id,
        type : "POST",
        data : {'id_customer': idCustomer, 'id_order': idOrder
                ,'id_detail_order': idDetailOrder, 'current_qty': qty
                , 'id_warna': idWarna, 'id_ukuran': idUkuran
              },
        dataType : "json",
        success : function(data){
          if(data.status == 1) {
            console.log("Status 1 gan");
            console.log(JSON.parse(data.getOrder));
            load_order(JSON.parse(data.getOrder));
            fillInformation();
          }
          else if(data.status == 0){
            var list = data.list;
            $.confirm({
                title: 'Produk',
                content: 'Inputan melebihi Qty produk terjual!'
                          +'<br>Qty produk terjual: <b>' + list.jumlah + '</b>',
                buttons: {
                    ok: function () {
                      $("#qt-"+data.rowid).val(list.jumlah);
                      saveCurrentQty();
                    }
                }
            });
          }
          else {
            load_order(data);
            fillInformation();
          }
        }
      });
    }
    else {
      $.alert({
          title: 'Perhatian!',
          content: 'Anda belum memilih Customer/ID Order!'
      });
      $("#qt-"+id).val(currentQty);
      saveCurrentQty(id);
    }
  }


  function delete_order(id){
    $.ajax({
      url :"<?php echo base_url('Transaksi_retur/Transaksi/deleteCart')?>/"+id,
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
      url :"<?php echo base_url('Transaksi_retur/Transaksi/updateOption')?>/"+id+"/"+option,
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
      url :"<?php echo base_url('Transaksi_retur/Transaksi/updateCart')?>/"+id+"/"+qty+"/"+state,
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
      url :"<?php echo base_url('Transaksi_retur/Transaksi/getTotal')?>",
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
          content: 'Batalkan Transaksi?',
          buttons: {
              confirm: function () {
                  doClear();
              },
              cancel: function () {
                  // $.alert('Canceled!');
              }
          }
      });    
  }
  function doClear(){
    var defaultHtml = $('#btnRetur').html();
    $('#btnRetur h5').text("Clearing...");
    $("#btnRetur").prop("disabled", true);    
    $.ajax({
      url :'<?php echo base_url("Transaksi_retur/Transaksi/destroyCart"); ?>',
      type : $('#pembelian').attr('method'),
      data : $('#pembelian').serialize(),
      dataType : "json",
      success : function(data){
        // console.log(data);        
        load_order(data);
        fillInformation();        
        $('#btnRetur').html(defaultHtml);
        $("#btnRetur").prop("disabled", false);
      }
    });    
  }
  function confirmStatus(e){
    e.preventDefault();
    var i = $(e.currentTarget).prop("id");
    $.confirm({
    title: 'Konfirmasi!',
    content: 'Ubah status menjadi <i class="label label-success">Selesai</i>?',
    type: 'green',
    buttons: {
        confirm: {
          text: 'Ya',
          btnClass: 'btn-success',
          action: function() {
            $('#'+i).bootstrapToggle('disable'); 
            var id  = parseInt(i.replace('toggle_',''));
            updateProses(id);
          }
        },
        cancel: {
          text: 'Batal',
          action: function() {
            $('#'+i).bootstrapToggle('off'); 
          }
        }
      }
    });
  }
  function loadOrder(){
      var id = $("#supplierSelect").val();
      $.ajax({
        url :"<?php echo base_url('Transaksi_pembelian/Transaksi/getDataOrder')?>/"+id,
        type : "GET",
        data :"",
        dataType :"json",
        success : function(data){
          if(data.status==1){
            load_poselect(data.list);
          }else if(data.status==0){
            $.confirm({
                title: 'Purchase Order',
                content: 'Belum ada PO untuk supplier ini!',
                buttons: {
                    confirm: function () {
                      clear_poselect();           
                    }
                }
            }); 
          }
        }
      });           
  }
  function clear_orderselect(){
    $("#orderSelect").html('');
    $('#orderSelect').select2({data: [{id: '0', text: 'Pilih Order'}]}).trigger('change');
  }
  function load_orderselect(json){
    var html = "";
    $("#orderSelect").html('');
    html = "<option value='0' selected disabled >Pilih Order</option>";
    $("#orderSelect").append(html);
    for (var i=0;i<json.length;i++){
      html = "<option value=\'"+json[i].id+"\'>"+json[i].id+"</option>";
      $("#orderSelect").append(html);
    }
  }  
  function showORDER(){
      $.ajax({
        url :"<?php echo base_url('Transaksi_pembelian/Transaksi/listPO')?>",
        type : "GET",
        data :"",
        success : function(data){
          $("#body-detail-po").html(data);
        }
      });       
      $("#modalpo").modal("show");    
  }
  function choosePO(){
      var id = $("#poSelect").val();
      $.ajax({
        url :"<?php echo base_url('Transaksi_pembelian/Transaksi/addCartFromExistingPO')?>/"+id,
        type : "GET",
        data :"",
        dataType : "json",
        success : function(data){
          fillInfoPO(id);
          load_order(data);
        }
      });
  }  
  function updateProses(id){
    alert(id);
  }
  function doSubmit() {
    $.ajax({
        url :$('#formretur').attr('action'),
        type : $('#formretur').attr('method'),
        data : $('#formretur').serialize(),
        dataType : "json",
        success : function(data){
          $('#btnRetur').html("<h5 class=\'text-bold\'>Bayar</h5>");
          $("#btnRetur").prop("disabled", false);
          var datas = <?php echo json_encode(array()); ?>;
          load_order(datas);
          fillInformation();
          // window.location.reload(false);
        }
    });
  }
  $(document).ready(function(){
    $("#formretur").on('submit', function(e){
      var idCustomer = $("#customerSelect").val() || '';
      var idOrder = $("#orderSelect").val() || '';
      var defaultHtml = $('#btnRetur').html();
      $('#btnRetur h5').text("Saving...");
      $("#btnRetur").prop("disabled", true);      
      e.preventDefault();
      $.confirm({
          title: 'Konfirmasi Retur',
          content: 'Yakin ingin retur produk?',
          buttons: {
              confirm: function () {
                if(idCustomer != '' && idOrder != '') {
                  doSubmit();
                }
                else {
                  $.alert({
                      title: 'Perhatian!',
                      content: 'Anda belum memilih Customer/ID Order!'
                  });
                  $('#btnRetur').html(defaultHtml);
                  $("#btnRetur").prop("disabled", false);
                }
              },
              cancel: function () {
                  $('#btnRetur').html(defaultHtml);
                  $("#btnRetur").prop("disabled", false);
              }
          }
      });      
    });
  });
</script>