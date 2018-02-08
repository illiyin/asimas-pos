<style type="text/css">
  .product-details input[type="text"]{
    width: 5em !important;
  }
  #ticket .modal-dialog {
    width: 520px;
  }
  #ticketModal {
    font-size: 90%;
  }
  #productList {
    font-size: 90%;
  }
  .group-select {
    width: 100%;
  }
  .group-select select.form-control{
    width: 35%;
  }
  .group-select input.form-control{
    width: 65%;
  }
</style>
<?php 
  /*echo "<pre>";
  print_r (isset($_SESSION['cart_contents']) ? $_SESSION['cart_contents'] : '');
  echo "</pre>";*/
?>
<div class="container-fluid">
   <div class="row">
    <div class="col-sm-12">
      <h3><strong>Transaksi</strong> - Pembelian</h3>
    </div> 
   </div>
   <div class="row">
    <div class="col-md-5 left-side">
      <form action="<?php echo base_url('Transaksi_pembelian/Transaksi/doSubmit'); ?>" method="post" id="pembelian">
        <div class="col-sm-12 text-right" style="margin-top: 10px;"> 
            <div class="btn btn-default" onclick="showInvoce('last');" title="Tampilkan Invoce Terakhir"><i class="fa fa-ticket"></i></div>
         </div>
          <!--          
          <div class="col-xs-4 client-add">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#ticket" onclick="showPO()">
               <span class="fa-stack fa-lg" data-toggle="tooltip" data-placement="top" title="Choose From Purchase Order">
                  <i class="fa fa-square fa-stack-2x grey"></i>
                  <i class="fa fa-ticket fa-stack-1x fa-inverse dark-blue"></i>
               </span>
            </a>
         </div>    -->      
        
         <div class="col-sm-6">
          <div class="form-group">
            <label class="label-control">Supplier</label>
            <select class="js-select-options form-control" id="supplierSelect" onchange="filterProduk()" name="supplier" required="required">
              <option value="0">Pilih Supplier</option>
            </select>
            <input type="hidden" name="idpo" value="0" id="idpo">
          </div>
         </div>

         <div class="col-sm-6">
          <div class="form-group">
            <label class="label-control">Purchase Order</label>
            <select class="js-select-options form-control" id="poSelect" onchange="choosePO()" name="po" required="required">
              <option value="0">Tanpa Purchase Order</option>
            </select>
          </div>
         </div>
         <!-- catatan was here -->
         <div class="col-sm-12">&nbsp;</div>

         <div class="col-xs-3 table-header text-center">
            <label>PRODUK</label>
         </div>
         <div class="col-xs-3 table-header nopadding text-center">
            <label>OPSI</label>
         </div>
         <div class="col-xs-2 table-header nopadding text-center">
            <label>QTY</label>
         </div>
         <div class="col-xs-2 table-header nopadding text-center">
            <label>HARGA BELI@ (IDR)</label>
         </div>
         <div class="col-xs-2 table-header nopadding text-left">
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
                  <tr>
                     <td class="active">Total Harga (IDR)</td>
                     <td class="whiteBg light-blue text-bold text-right"><span id="eTotal" class="money"></span></td>
                  </tr>
               </table>
            </div>
            <!-- metode pembayaran was here -->
            <button type="button" onclick="cancelOrder()" class="btn btn-red col-md-6 flat-box-btn"><h5 class="text-bold">Cancel</h5></button>
            <button type="button" class="btn btn-green col-md-6 flat-box-btn" onclick="payment()" id="btnDoOrder"><h5 class="text-bold">Proses Transaksi</h5></button>
            <!-- <button type="submit" class="btn btn-green col-md-6 flat-box-btn" data-toggle="modal" data-target="#AddSale" id="btnDoOrder"><h5 class="text-bold">Proses Transaksi</h5></button> -->
         </div>
        </form>

      </div>
      <div class="col-md-7 right-side nopadding">
        <div class="row row-horizon" id="kategoriGat">
            <span class="categories selectedGat" id="gat-0">
              <i class="fa fa-home" onclick="filterProdukByKategori(0)"></i>
            </span>
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
<!-- Modal PO -->
<div class="modal fade" id="modalpo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">List Purchase Order</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-lg-12"  id="body-detail-po">
           </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" data-dismiss="modal">Ok</button>
      </div>
    </div>
 </div>
</div>
<!-- /.Modal PO-->

<!-- Modal Payment -->
  <div class="modal fade" id="modalpayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="Addpayament">Proses Pembelian</h4>
        </div>
        <form method="POST" action="<?php echo base_url('Transaksi_pembelian/Transaksi/doSubmit'); ?>" id="formpayment">
        <div class="modal-body">
             <div class="form-group">
               <h2 id="textMetodePembayaran">Tunai</h2>
             </div>
             
             <div class="form-horizontal">
               <div class="form-group">
                 <label for="paymentMethod" class="col-sm-3 control-label">Metode Pembayaran</label>
                 <div class="col-sm-9">
                   <select class="form-control" id="paymentMethod" name="paymentMethod" required="required"> </select>
                 </div>
               </div>
             </div>
             <div class="row">
                <div class="col-xs-3">
                  <label class="center-block text-right">Total Harga</label>
                </div>
                <div class="col-xs-9">
                  <h2 class="text-right bg-warning" style="margin-top: 5px; padding:10px;"><b>Rp <span id='textTotalHarga' class="money">0</span></b>-</h2>
                </div>
             </div>

             <div class="form-horizontal">
               <div class="form-group">
                 <label class="col-sm-3 control-label" for="Paid">Nominal (IDR)</label>
                 <div class="col-sm-9">
                   <div class="input-group">
                    <span class="input-group-addon">Rp</span> 
                    <input type="text" value="0" name="paid" class="form-control money" id="Paid" placeholder="Nominal">
                   </div>
                 </div>
               </div>
               <!-- <div class="form-group pembayaran_bank">
                 <label class="col-sm-3 control-label" for="id_bank">Bank</label>
                 <div class="col-sm-9">
                   <div class="input-group group-select">
                     <select class="form-control" id="id_bank" name="id_bank"></select>
                     <input type="number" name="nomor_kartu" id="nomor_kartu" class="form-control" placeholder="Nomor Kartu">
                   </div>
                 </div> 
               </div> -->
             </div>

             <div class="row">
                <div class="col-xs-3">
                  <label class="center-block text-right">Total Bayar</label>
                </div>
                <div class="col-xs-6">
                  <h3 class="text-right bg-success" style="margin-top: 5px; padding:10px;">Rp <span id='textTotalBayar' class="money">0</span>-</h3>
                </div>
             </div>
             <div class="row">
               <div class="col-xs-3">
                 <label class="center-block text-right">Kembalian</label>
               </div>
               <div class="col-xs-6">
                 <h3 class="text-right bg-danger" style="margin-top: 5px; padding:10px;">Rp <span id='textKembalian' class="">0</span>-</h3>
                 <input type="hidden" name="kembalian" id="kembalian">
               </div>
             </div>
             <div class="form-horizontal">
               <div class="form-group">
                 <label for="catatan" class="col-sm-3 control-label">Catatan</label>
                 <div class="col-sm-9">
                   <textarea name="catatan" class="form-control" placeholder="Catatan" id="catatan"></textarea>
                 </div>
               </div>
             </div>
             <div class="clearfix"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>          
          <button type="submit" class="btn btn-add" id="btnBayar" disabled="disabled"><i class="fa fa-check-square-o"></i> Bayar</button>
        </div>
        </form>
      </div>
   </div>
  </div>
  <!-- /.Modal -->

<!-- Modal Invoice -->
  <div class="modal fade" id="ticket" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document" id="ticketModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Invoice</h4>
        </div>
        <div class="modal-body">
          <div id="printSection">
            <!-- load content here -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>          
          <a href="javascript:void(0);" onclick="printTicket();" class="btn btn-add" id="btnCetak" title="Cetak Invoice"><i class="fa fa-print"></i> Cetak</a>
        </div>
      </div>
   </div>
  </div>
  <!-- /.Modal -->

<script type="text/javascript">
  function maskInputMoney(){
    $('.money').mask('#.##0', {reverse: true});
  }
  function unmaskInputMoney(){
    $('.money').unmask();
  }

  $("#paymentMethod").change(function(){
     var p_met = $(this).find('option:selected').val();
     var lower_p_met = $(this).find('option:selected').html().toLowerCase();
     var textMetodePembayaran = $(this).find('option:selected').html();

     $("#textMetodePembayaran").html(textMetodePembayaran);

     if (lower_p_met === 'tunai' || lower_p_met === 'kredit') {
        // $(".pembayaran_bank").find("input, select").prop("disabled", true);
        // $(".pembayaran_bank").hide();
        $('.Paid').show();
     } else {
        // $(".pembayaran_bank").find("input, select").prop("disabled", false);
        // $(".pembayaran_bank").show();
        $('.Paid').show();
     }
  });

  var listProduct = <?php echo $list_produk; ?>;
  var listOrder = <?php echo $list_order; ?>;
  var listSupplier = <?php echo $list_supplier; ?>;
  var listBank = <?php echo $list_bank; ?>;
  var listMetodePembayaran = <?php echo $list_metode_pembayaran; ?>;
  var listWarna = "";
  var listUkuran = "";
  var tax = '<?php echo $tax; ?>';
  var discount = '<?php echo $discount; ?>';
  var total = '<?php echo $total; ?>';
  var totalItems = '<?php echo $total_items; ?>';
  var transTotalHarga = 0;
  maskInputMoney();
  inits(tax, discount, total, totalItems);
  load_supplier(listSupplier);
  // load_product(listProduct);
  load_order(listOrder);
  load_metode_pembayaran(listMetodePembayaran);
  load_bank(listBank);

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
    $("#productList2").html('');
    var color = 2;
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
                  // "<img src='#' alt=\'"+json[i].id_kategori+"\'>"+
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
                              /*"<select name=ukuran id=\'uk-"+json[i].rowid+"\' class=\'form-control\' onchange=updateOption(\'"+json[i].rowid+"\') title=\'Pilih Ukuran\'>"+
                                "<option value=0 select disabled>Pilih Ukuran</option>"+
                              "</select>"+*/
                            "</span>"+
                            "<span class='textPD'>"+
                              "<span><b>Warna:</b> "+json[i].text_warna+"</span>"+
                              /*"<select name=warna id=\'wr-"+json[i].rowid+"\' class=\'form-control\' onchange=updateOption(\'"+json[i].rowid+"\') title=\'Pilih Warna\'>"+
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
                            "<span class=\'textPD money\' style='float:right;'>"+json[i].harga_beli+"</span>"+
                            "<input type=hidden id=\'hb-"+json[i].rowid+"\' class=\'form-control\' value='"+json[i].harga_beli+"'  onchange=updateHargaBeli(\'"+json[i].rowid+"\')>"+
                          "</div>"+
                          "<div class='col-xs-2 nopadding'>"+
                            "<span class=\'textPD pull-right money\'>"+json[i].subtotal+"</span>"+
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
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/getUkuran')?>/"+rid,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        var html = "";
        // $("#uk-"+id).html('');
        $("#selectUkuran").html('');
        /*html = "<option value='0' selected>Tidak Ada Ukuran</option>";*/
        $("#selectUkuran").append(html);
        // $("#uk-"+id).append(html);
        for(var i=0; i<data.length; i++) {
          var pilihs = "";
          /*if(data[i].id == pilih){
            pilihs = "selected";
          }*/
          html = "<option value=\'"+data[i].id+"\' "+pilihs+">"+data[i].nama+"</option>";
          // $("#uk-"+id).append(html);
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
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/updateOption')?>/"+id+"/"+warna+"/"+ukuran+"/"+totalBerat,
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
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/updateHargaBeli')?>/"+id+"/"+hb,
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
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/updateQty')?>/"+id+"/"+qty,
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
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/getWarna')?>/"+rid,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        var html = "";
        // $("#wr-"+id).html('');
        $("#selectWarna").html('');
        /*html = "<option value='0' selected>Tidak Ada Warna</option>";*/
        // $("#wr-"+id).append(html);
        $("#selectWarna").append(html);
        for (var i=0;i<data.length;i++){
          var pilihs = "";
          /*if(data[i].id == pilih){
            pilihs = "selected";
          }      */
          html = "<option value=\'"+data[i].id+"\' "+pilihs+">"+data[i].nama+"</option>";
          $("#selectWarna").append(html);
          // $("#wr-"+id).append(html);
        }
      }
    });    
  }
  function filterProduk(){
    $.ajax({
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/filterProduk')?>/"+$("#supplierSelect").val(),
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        filterKategori();
        load_product(data);
        loadPO();
      }
    });
  }
  function filterKategori(){
    $.ajax({
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/getKategori')?>/"+$("#supplierSelect").val(),
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
  function load_metode_pembayaran(json){
    var html = "";
    $("#paymentMethod").html('');
    html = "<option value='' disabled selected>Pilih Metode Pembayaran</option>";
    $("#paymentMethod").append(html);
    for (var i=0;i<json.length;i++){
      html = "<option value=\'"+json[i].id+"\'>"+json[i].nama+"</option>";
      $("#paymentMethod").append(html);
    }
  }
  function load_bank(json){
    var html = "";
    $("#id_bank").html('');
    html = "<option value='' disabled selected>Pilih Bank</option>";
    $("#id_bank").append(html);
    for (var i=0;i<json.length;i++){
      html = "<option value=\'"+json[i].id+"\'>"+json[i].nama+"</option>";
      $("#id_bank").append(html);
    }
  }
  function filterProdukByKategori(id){
    var keyword = $("#searchProd").val();
    var supplier = $("#supplierSelect").val();
    $( ".categories" ).removeClass('selectedGat');
    $( "#gat-"+id ).addClass( "selectedGat" );

    if(supplier != 0){    
      $.ajax({
        url :"<?php echo base_url('Transaksi_pembelian/Transaksi/filterProdukByKategori')?>/"+supplier+"/"+id+"/"+keyword,
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
        url :"<?php echo base_url('Transaksi_pembelian/Transaksi/filterProdukByName')?>",
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
        url :"<?php echo base_url('Transaksi_pembelian/Transaksi/tambahCart')?>/"+id,
        type : "POST",
        data : {'idSupplier': idSupplier, 'idUkuran': idUkuran, 'idWarna': idWarna},
        dataType : "json",
        success : function(data) {
          if(data.status == 2){
            load_order(JSON.parse(data.list));
            fillInformation();
          }
          else if(data.status == 0) {
            $.confirm({
                title: 'Harga',
                content: 'Harga Beli belum diset, Hubungi Admin!!',
                buttons: {
                    ok: function () { }
                  }
            }); 
          }
        }
      });
    }
  }
  function delete_order(id){
    $.ajax({
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/deleteCart')?>/"+id,
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
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/updateOption')?>/"+id+"/"+option,
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
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/updateCart')?>/"+id+"/"+qty+"/"+state,
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
    $("#textTotalHarga").html(etotal);
    console.log("etotal " + etotal);
    etotal = etotal + '';
    transTotalHarga = parseInt(etotal.split(',').join(''));
    maskInputMoney();
  }
  function fillInformation(){
    $.ajax({
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/getTotal')?>",
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
      var defaultHtml = $('#btnDoOrder').html();
      $.confirm({
          title: 'Batal',
          content: 'Batalkan Transaksi ?',
          buttons: {
              confirm: function () {
                  doClear();
              },
              cancel: function () {
                  // $.alert('Canceled!');
                  $('#btnDoOrder').html(defaultHtml);
                  $("#btnDoOrder").prop("disabled", false);  
              }
          }
      });    
  }
  function doClear(){
    $('#btnDoOrder').html("<h5 class=\'text-bold\'>Clearing...</h5>");
    $("#btnDoOrder").prop("disabled", true);    
    $.ajax({
      url :'<?php echo base_url("Transaksi_pembelian/Transaksi/destroyCart"); ?>',
      type : $('#pembelian').attr('method'),
      data : $('#pembelian').serialize(),
      dataType : "json",
      success : function(data){
        // console.log(data);        
        load_order(data);
        fillInformation();        
        $('#btnDoOrder').html("<h5 class=\'text-bold\'>Proses Transaksi</h5>");
        $("#btnDoOrder").prop("disabled", false);
        // window.close();
        window.location.reload(false);
      }
    });    
  }
  function doSubmit(){
    var defaultHtml = $('#btnDoOrder').html();
    var paymentMethod = $("#paymentMethod").val() || '';
    alert(paymentMethod);
    $.ajax({
      url :$('#pembelian').attr('action'),
      type : $('#pembelian').attr('method'),
      data : $('#pembelian').serialize() 
              + "&paymentMethod="+paymentMethod,
      dataType : "json",
      success : function(data){        
        load_order(data);
        fillInformation();        
        $('#btnDoOrder').html(defaultHtml);
        $("#btnDoOrder").prop("disabled", false);
        // window.close();
        window.location.reload(false);
      }
    });    
  }
  function loadPO(){
      var id = $("#supplierSelect").val();
      $.ajax({
        url :"<?php echo base_url('Transaksi_pembelian/Transaksi/getDataPO')?>/"+id,
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
  function clear_poselect(){
    $("#poSelect").html('');
    $('#poSelect').select2({data: [{id: '0', text: 'Tanpa Purchase Order'}]}).trigger('change');
  }
  function load_poselect(json){
    var html = "";
    $("#poSelect").html('');
    html = "<option value='0' selected >Tanpa Purchase Order</option>";
    $("#poSelect").append(html);
    for (var i=0;i<json.length;i++){
      html = "<option value=\'"+json[i].id+"\'>"+json[i].id+"</option>";
      $("#poSelect").append(html);
    }
  }  
  function showPO(){
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
          // fillInfoPO(id);
          load_order(data);
          fillInformation();
        }
      });
  }
  function fillInfoPO(idPO){
    // getInfoPO
    $.ajax({
      url :"<?php echo base_url('Transaksi_pembelian/Transaksi/getInfoPO')?>/"+idPO,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        $("#idpo").val(data[0].id);
        $("#supplierSelect").val(data[0].id_supplier);
        $("#catatan").val(data[0].catatan);
      }
    });
  }

  function payment(){
    var idSupplier = $("#supplierSelect").val() || '';
    var idMetodePembayaran = $("#paymentMethod").val() || '';
    var textMetodePembayaran = $("#paymentMethod :selected").html() || '';

    $("#Paid").val("");
    // $(".pembayaran_bank").find("input, select").val("");
    $("#textTotalBayar").html('0');
    $("#textKembalian").html('0');

    console.log(idSupplier);
    if(idSupplier != '') {
      $("#textMetodePembayaran").html(textMetodePembayaran);
      $("#modalpayment").modal("show");
      $("#modalpayment").on("shown.bs.modal", function() {
        $("#Paid").focus();
      }); 
    }
    else {
      $.alert({
          title: 'Perhatian',
          content: 'Anda belum memilih Supplier!',
      });
    }
  }
  $(document).ready(function(){
    $("#formpayment").on('submit', function(e){
      e.preventDefault();      
      unmaskInputMoney();
      
      var defaultHtml = $('#btnBayar').html();
      var paymentMethod = $("#paymentMethod").val();
      var catatan = $("#catatan").val();
      var paid = $("#Paid").val() || '';
      var id_bank = $("#id_bank").val() || '';
      var nomor_kartu = $("#nomor_kartu").val() || '';
      var kembalian = $("#kembalian").val();
      $('#btnBayar').text("Saving...");
      $("#btnBayar").prop("disabled", true);      

      $.ajax({
        url :$('#formpayment').attr('action'),
        type : $('#formpayment').attr('method'),
        // data : $('#formpayment').serialize() 
        data : $('#pembelian').serialize()
                + "&paid=" +paid
                + "&id_bank=" +id_bank
                + "&nomor_kartu=" +nomor_kartu
                + "&kembalian=" +kembalian
                + "&catatan=" +catatan
                + "&paymentMethod=" +paymentMethod,
        dataType : "json",
        success : function(data){
          $("#modalpayment").modal('hide');
          $('#btnBayar').html(defaultHtml);
          $("#btnBayar").prop("disabled", false);
          // window.location.reload(false);
          var datas = <?php echo json_encode(array()); ?>;
          load_order(datas);
          fillInformation();
          showInvoce(data.idOrder);

          //empty the fields
          $("#pembelian").find("input, textarea").val('');

          // window.open("<?php echo base_url('Transaksi_pembelian/Transaksi/invoices'); ?>/"+data.idOrder, "_blank");
        },
        error: function(jqXHR, textStatus, errorThrown){
          console.log("Error!?????: "+textStatus);
          $('#btnBayar').html(defaultHtml);
          $("#btnBayar").prop("disabled", false);
        }
      });
      maskInputMoney();
    });
    $("#pembelian").on('submit', function(e){
      var defaultHtml = $('#btnDoOrder').html();
      $('#btnDoOrder h5').text("Saving...");
      $("#btnDoOrder").prop("disabled", true);
      e.preventDefault();
      $.confirm({
          title: 'Konfirmasi Pembelian',
          content: 'Yakin ingin membeli barang ?',
          buttons: {
              confirm: function () {
                  doSubmit();
              },
              cancel: function () {
                $('#btnDoOrder').html(defaultHtml);
                $("#btnDoOrder").prop("disabled", false);
                  
              }
          }
      });      
    });
  });

  //KEMBALIAN Handler
  $("#Paid").on("keyup", function() {
    var totalHarga = transTotalHarga || 0;
    var paid = $(this).val().split('.').join("") || 0;
    // var paid = $(this).val().split('.').join("") || totalHarga;
    var kembalian = parseInt(paid) - parseInt(totalHarga);
    $("#textTotalBayar").text(paid);
    $("#textKembalian").text(kembalian);
    $("#kembalian").val(kembalian);

    $("#textTotalBayar").unmask();
    $("#textTotalBayar").mask('#.##0', {reverse: true});
    $("#textKembalian").unmask();
    $("#textKembalian").mask('#.##0', {reverse: true});
    
    if(kembalian < 0) {
      $("#textKembalian").parent("h3").addClass("text-danger");
      $("#btnBayar").prop("disabled", true);
    } else {
      $("#textKembalian").parent("h3").removeClass("text-danger");
      $("#btnBayar").prop("disabled", false);
    }
  });

  function showInvoce(id){
    var idOrder = id || '';
    var html = "<h3 class='text-center text-muted'>Tidak ada invoice<h3>";
    if(idOrder != '') {
      $.ajax({
        url : '<?php echo base_url("Transaksi_pembelian/Transaksi/getInvoiceData"); ?>/'+idOrder,
        type : "POST",
        data : "",
        // dataType : "json",
        success : function(data){
          if(data == '') {
            $("#printSection").html(html);
          } else {
            $("#printSection").html(data);
          }
        }
      });
    }
    else {
      $("#printSection").html(html);
    }
    $("#ticket").modal("show");
  }
  function printTicket() {
     $('.modal-body').removeAttr('id');
     window.print();
     $('.modal-body').attr('id', 'modal-body');
  }
</script>