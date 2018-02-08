<style type="text/css">
  .product-details input[type="text"] {
    width: 4em !important;
  }
  .product, .product img {
    width: 100px;
    height: 100px;
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
  .group-select select.form-control {
    width: 35%;
  }
  .group-select input.form-control {
    width: 65%;
  }
</style>
<?php
  // unset($_SESSION['cart_holds']);
  /*echo "<pre>";
  print_r(isset($_SESSION['cart_holds']) ? $_SESSION['cart_holds'] : 'Cart Hold is Empty');
  print_r(isset($_SESSION['cart_contents']) ? $_SESSION['cart_contents'] : 'Cart is Empty');
  echo "</pre>";
  echo "<br>";*/
?>
<div class="container-fluid">
   <div class="row">
    <div class="col-sm-12">
      <h3><strong>Transaksi</strong> - Penjualan</h3>
    </div>
   </div>
   <div class="row">
    <div class="col-md-6 left-side">
      
      <div class="row row-horizon">
        <span id="holdList"> </span>
        <span class="Hold pl" onclick="addHold()"><i class="fa fa-plus"></i></span>
        <span class="Hold pl" onclick="removeHold()"><i class="fa fa-minus"></i></span>
      </div>

      <form action="<?php echo base_url('Transaksi_penjualan/Transaksi/doSubmit'); ?>" method="post" id="pembelian">          
         <div class="col-sm-12 text-right" style="margin-top: 10px;"> 
            <div class="btn btn-default" onclick="showInvoce('last');" title="Tampilkan Invoce Terakhir"><i class="fa fa-ticket"></i></div>
         </div>
         <div class="col-sm-12 col-lg-6">
          <div class="form-group">
            <label class="label-control">Customer</label>
            <select class="js-select-options form-control" id="customerSelect" name="customer" required="required">
              <option value="0">Pilih Customer</option>
            </select>
          </div>
         </div>
         <div class="col-sm-12 col-lg-6">
           <div class="form-group">
             <label for="barcode">Barcode</label>
             <div class="input-group">
              <input type="text" name="barcode" class="form-control" placeholder="Barcode" id="barcode" title="Input Barcode: Tekan tombol Shift untuk fokus ke input ini">
              <span class="input-group-btn">
                <button type="button" id="btnBarcode" class="btn btn-default"><i class="fa fa-barcode" title="Cek Barcode"></i> Check</button> 
              </span>
             </div>
           </div>
         </div>
         <!-- catatan was here -->
         <div class="col-sm-12">&nbsp;</div>

         <div class="col-xs-2 table-header text-center">
            <label>PRODUK</label>
         </div>
         <div class="col-xs-2 table-header nopadding text-center">
            <label>OPSI</label>
         </div>
         <div class="col-xs-2 table-header text-center">
            <label class="text-left">QTY</label>
         </div>
         <div class="col-xs-2 table-header text-center">
            <label>HARGA NORMAL @ (IDR)</label>
         </div>
         <div class="col-xs-2 table-header text-center">
            <label>POTONGAN @ (IDR)</label>
         </div>
         <div class="col-xs-2 table-header nopadding text-center">
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
            <!-- metode pembayaran was here -->
            <button type="button" onclick="cancelOrder()" class="btn btn-red col-md-6 flat-box-btn"><h5 class="text-bold">Cancel</h5></button>
            <button type="button" class="btn btn-green col-md-6 flat-box-btn" onclick="payment()" id="btnDoOrder"><h5 class="text-bold">Proses Transaksi</h5></button>
         </div>
        </form>

      </div>
      <div class="col-md-6 right-side nopadding">
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
  <!-- Modal Payment -->
  <div class="modal fade" id="modalpayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="Addpayament">Proses Pembayaran</h4>
        </div>
        <form method="POST" action="<?php echo base_url('Transaksi_penjualan/Transaksi/payment'); ?>" id="formpayment">
        <div class="modal-body">
             <div class="form-group">
               <h2 id="textMetodePembayaran">Tunai</h2>
             </div>

             <div class="form-group hidden" style="visibility: hidden;">
               <label for="jenisOrder">Jenis Order</label>
               <input type="hidden" name="id_customer" id="idCustomer">
               <select class="form-control" id="jenisOrder" name="jenisOrder">
                 <option value="1">Take Away</option>
                 <option value="2">Dropship</option>
              </select>
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
                     <select class="form-control" id="id_bank" name="id_bank"> </select>
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

             
             <div class="form-group CreditCardNum">
               <i class="fa fa-cc-visa fa-2x" id="visa" aria-hidden="true"></i>
               <i class="fa fa-cc-mastercard fa-2x" id="mastercard" aria-hidden="true"></i>
               <i class="fa fa-cc-amex fa-2x" id="amex" aria-hidden="true"></i>
               <i class="fa fa-cc-discover fa-2x" id="discover" aria-hidden="true"></i>
               <label for="CreditCardNum">Nomor Kartu Kredit</label>
               <input type="text" class="form-control cc-num" id="CreditCardNum" placeholder="Nomor Kartu Kredit">
             </div>
             <div class="clearfix"></div>
             <div class="form-group CreditCardHold col-md-4 padding-s">
               <input type="text" class="form-control" id="CreditCardHold" placeholder="CVV">
             </div>
             <div class="form-group CreditCardHold col-md-2 padding-s">
               <input type="text" class="form-control" id="CreditCardMonth" placeholder="Bulan">
             </div>
             <div class="form-group CreditCardHold col-md-2 padding-s">
               <input type="text" class="form-control" id="CreditCardYear" placeholder="TAHUN">
             </div>
             <div class="form-group CreditCardHold col-md-4 padding-s">
               <input type="text" class="form-control" id="CreditCardCODECV" placeholder="VCC">
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
            <!-- <div class="row">
              <div class="col-md-12">
                <h5 class="text-center">Iqbal POS</h5>
                <h4 class="text-center">Invoice #id</h4>
                <hr>
                <p>Tanggal: date_add
                  <br>Customer: nama_customer
                </p>

                <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Produk</th>
                      <th>Warna</th>
                      <th>Ukuran</th>
                      <th>Qty</th>
                      <th>Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>nama</td>
                      <td>nama_warna</td>
                      <td>nama_ukuran</td>
                      <td>qty</td>
                      <td>subtotal</td>
                    </tr>
                  </tbody>
                </table>

                <table class="table table-condensed">
                  <tbody>
                    <tr>
                      <td style="width: 60%;"><b>Total Harga</b></td>
                      <td style="width: 40%;">Rp <span class="pull-right">grandtotal</span></td>
                    </tr>
                    <tr>
                      <td style="width: 60%;"><b>Cash</b></td>
                      <td style="width: 40%;">Rp <span class="pull-right">cash</span></td>
                    </tr>
                    <tr>
                      <td style="width: 60%;"><b>Kembali</b></td>
                      <td style="width: 40%;">Rp <span class="pull-right">uang_kembali</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div> -->
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

  $('.ReturnChange').show();
  $('.CreditCardNum').hide();
  $('.CreditCardHold').hide();
  $('.stripe-btn').hide();
  // $(".pembayaran_bank").find("input, select").prop("disabled", true);
  // $(".pembayaran_bank").hide();

  $("#paymentMethod").change(function(){
     var p_met = $(this).find('option:selected').val();
     var lower_p_met = $(this).find('option:selected').html().toLowerCase();
     var textMetodePembayaran = $(this).find('option:selected').html();

     $("#textMetodePembayaran").html(textMetodePembayaran);

     /*if (lower_p_met === 'tunai' || lower_p_met === 'kredit') {
        $(".pembayaran_bank").find("input, select").prop("disabled", true);
        $(".pembayaran_bank").hide();
        $('.Paid').show();
        $('.ReturnChange').show();
        $('.CreditCardNum').hide();
        $('.CreditCardHold').hide();
        $('.CreditCardMonth').hide();
        $('.CreditCardYear').hide();
        $('.CreditCardCODECV').hide();
        $('#CreditCardNum').val('');
        $('#CreditCardHold').val('');
        $('#CreditCardYear').val('');
        $('#CreditCardMonth').val('');
        $('#CreditCardCODECV').val('');
        $('.stripe-btn').hide();
     } else {
        $(".pembayaran_bank").find("input, select").prop("disabled", false);
        $(".pembayaran_bank").show();
        $('.Paid').show();
        $('.ReturnChange').hide();
        $('.CreditCardNum').hide();
        $('.CreditCardHold').hide();
        $('.CreditCardMonth').hide();
        $('.CreditCardYear').hide();
        $('.CreditCardCODECV').hide();
        $('#CreditCardNum').val('');
        $('#CreditCardHold').val('');
        $('#CreditCardYear').val('');
        $('#CreditCardMonth').val('');
        $('#CreditCardCODECV').val('');
        $('.stripe-btn').hide();
     }*/
  });

  var currentCustomerId = 0;
  var listHold = <?php echo $list_hold; ?>;
  var listProduct = <?php echo $list_produk; ?>;
  var listOrder = <?php echo $list_order; ?>;
  var listCustomer = <?php echo $list_customer; ?>;
  var listKategori = <?php echo $list_kategori; ?>;
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
  load_customer(listCustomer);
  load_order(listOrder);
  load_metode_pembayaran(listMetodePembayaran);
  load_bank(listBank);
  load_hold(listHold);

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
                    "<h3>Rp <span class='money'>"+json[i].harga_jual_normal+"</span></h3>"+
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
      for (var i=0; i<json.length; i++){
        html = "<div class='col-xs-12'>"+
                  "<div class='panel panel-default product-details'>"+
                      "<div class='panel-body' style=''>"+
                          "<div class='col-xs-2 nopadding'>"+
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
                          "<div class='col-xs-2'>"+
                            "<span class='textPD'>"
                              +"<span><b>Ukuran:</b> "+json[i].text_ukuran+"</span>"+
                            // +"<select name=ukuran id=\'uk-"+json[i].rowid+"\' class=\'form-control input-sm\' onchange=updateOption(\'"+json[i].rowid+"\') title='Pilih Ukuran'>"+
                            //     "<option value=0 select disabled>Pilih Ukuran</option>"+
                            //   "</select>"+
                            "</span>"+
                            "<span class='textPD'>"
                              +"<span><b>Warna:</b> "+json[i].text_warna+"</span>"+
                              // +"<select name=warna id=\'wr-"+json[i].rowid+"\' class=\'form-control input-sm\' onchange=updateOption(\'"+json[i].rowid+"\') title='Pilih Warna'>"+
                              //   "<option value=0 select disabled>Pilih Warna</option>"+
                              // "</select>"+
                            "</span>"+
                          "</div>"+
                          "<div class='col-xs-2 productNum'>"+
                            "<span class='center-block text-center' style='margin-top:5px'>"+
                            "<input id=\'qt-"+json[i].rowid+"\' class='form-control' value='"+json[i].qty+"' placeholder='0' maxlength='4' type='text' onchange=updateQty(\'"+json[i].rowid+"\')>"+
                            "</span>"+
                          "</div>"+
                          "<div class='col-xs-2 nopadding productNum'>"+
                            "<span class='textPD pull-right money'>"+json[i].harga_jual_normal+"</span>"+
                          "</div>"+
                          "<div class='col-xs-2 nopadding productNum'>"+
                            "<span class='textPD pull-right money'>"+(parseInt(json[i].harga_jual_normal) - parseInt(json[i].harga_beli))+"</span>"+
                          "</div>"+
                          "<div class='col-xs-2 nopadding productNum'>"+
                            "<span class='textPD pull-right money'>"+json[i].subtotal+"</span>"+
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
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/getUkuran')?>/"+rid,
      type : "GET",
      data : "",
      dataType : "json",
      success : function(data){
        var html = "";
        // $("#uk-"+id).html('');
        $("#selectUkuran").html('');
        // html = "<option value='0' selected>Tidak Ada Ukuran</option>";
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
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/updateOption')?>/"+id+"/"+warna+"/"+ukuran+"/"+totalBerat,
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
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/updateHargaBeli')?>/"+id+"/"+hb,
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
    var qty = $("#qt-"+id).val() || 0;
    $.ajax({
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/updateQty')?>/"+id+"/"+qty,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        if(data.status == 2) {        
          load_order(data.list);
          fillInformation();
        }
        else if(data.status == 1) {
          var list = data.list;
          var elem = $("#qt-"+data.rowid);
          // var getRow = list.filter(function (index) { return index.rowid == data.id }) || 0;

          $.confirm({
              title: 'Stok',
              content: 'Stok Tidak Mencukupi <br>Max Qty: <b>' + list.stok + "</b>",
              buttons: {
                  ok: function () {
                    $(elem).val(parseInt(list.stok)); 
                    $(elem).trigger('change'); 
                  }
                }
          }); 
        }
      }
    });
  }
  function loadWarna(rid, id, json, pilih){
    $.ajax({
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/getWarna')?>/"+rid,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        var html = "";
        // $("#wr-"+id).html('');
        $("#selectWarna").html('');
        // html = "<option value='0' selected>Tidak Ada Warna</option>";
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
  function payment(){
    var idCustomer = $("#customerSelect").val() || '';
    // var idMetodePembayaran = $("#paymentMethod").val() || '';
    var textMetodePembayaran = $("#paymentMethod :selected").html() || '';

    $("#Paid").val("");
    $("#catatan").val("");
    // $(".pembayaran_bank").find("input, select").val("");
    $("#textTotalBayar").html('0');
    $("#textKembalian").html('0');

    // if((idCustomer!='') && (idMetodePembayaran!='')) {
    if(idCustomer!='') {
      $("#textMetodePembayaran").html(textMetodePembayaran);
      $("#modalpayment").modal("show");
      $("#modalpayment").on("shown.bs.modal", function() {
        $("#Paid").focus();
      }); 
    }
    else {
      $.alert({
          title: 'Perhatian',
          content: 'Anda belum memilih Customer!',
      });
    }
  }
  function filterProduk(){
    // alert($("#customerSelect").find(":selected").text());
    $("#idCustomer").val($("#customerSelect").val());
    load_product(listProduct);
    load_kategori(listKategori);
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
        url :"<?php echo base_url('Transaksi_penjualan/Transaksi/filterProdukByKategori')?>/"+id+"/"+keyword,
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
    var kategori = $(".selectedGat").attr('id');
    var realkategori = "";
    if(kategori != null || kategori != undefined){    
      realkategori = kategori;
    }
    $.ajax({
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/filterProdukByName')?>",
      type : "POST",
      data : "keyword="+keyword+"&kategori="+realkategori,
      dataType : "json",
      success : function(data){
        load_product(data);
      }
    });
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
                    var idUkuran = this.$content.find('#selectUkuran').val() || 0;
                    var idWarna = this.$content.find('#selectWarna').val() || 0;
                    if(idUkuran==0 || idWarna==0){
                        $.alert('Anda belum memilih Ukuran/Warna!');
                        return false;
                    }
                    addToCart(id);
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
    var idCustomer = $("#customerSelect").val();
    var idUkuran = $("#selectUkuran").val();
    var idWarna = $("#selectWarna").val();
    var holdId = $(".selectedHold").attr('id').split('-');
    var idCart = holdId[1];

    if(idCustomer == '' || idCustomer == null) {
      $.alert({
          title: 'Perhatian',
          content: 'Anda belum memilih Customer!',
      }); 
    }
    else {
      $.ajax({
        url :"<?php echo base_url('Transaksi_penjualan/Transaksi/tambahCart')?>/"+id,
        type : "POST",
        // data :"idCustomer="+$("#customerSelect").val(),
        data : {'idCustomer': idCustomer, 'idUkuran': idUkuran, 'idWarna': idWarna,'idCart': idCart},
        dataType : "json",
        success : function(data) {
          if(data.status == 2){
            load_order(data.list);
            fillInformation();
          }
          else if(data.status == 1) {
            $.confirm({
                title: 'Stok',
                content: 'Stok Tidak Mencukupi',
                buttons: {
                    ok: function () { }
                  }
            }); 
          }
          else if(data.status == 0) {
            $.confirm({
                title: 'Harga',
                content: 'Harga Belum Diset, Hubungi Admin!!',
                buttons: {
                    ok: function () { }
                  }
            }); 
          }
        }
      });
    }
  }
  function addToCartBarcode(id='', idCustomer='', idWarna='', idUkuran=''){
    if(idCustomer == '' || idCustomer == null) {
      $.alert({
          title: 'Perhatian',
          content: 'Anda belum memilih Customer!',
      }); 
    }
    else {
      $.ajax({
        url :"<?php echo base_url('Transaksi_penjualan/Transaksi/tambahCart')?>/"+id,
        type : "POST",
        data : {'idCustomer': idCustomer, 'idUkuran': idUkuran, 'idWarna': idWarna},
        dataType : "json",
        success : function(data) {
          if(data.status == 2){
            load_order(data.list);
            fillInformation();
          }
          else if(data.status == 1) {
            $.confirm({
                title: 'Stok',
                content: 'Stok Tidak Mencukupi',
                buttons: {
                    ok: function () { }
                  }
            }); 
          }
          else if(data.status == 0) {
            $.confirm({
                title: 'Harga',
                content: 'Harga Belum Diset, Hubungi Admin!!',
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
    var holdId = $(".selectedHold").attr('id').split('-');
    var idCart = holdId[1];

    $.ajax({
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/deleteCart')?>/"+id,
      type : "GET",
      data : { 'idCart': idCart },
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
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/updateOption')?>/"+id+"/"+option,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        load_order(data);
        fillInformation();
      }
    });
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
  function changeCustomer(){
    var productList = $("#productList");
    var holdId = $(".selectedHold").attr('id').split('-');
    var idCart = holdId[1];
    var customerId = currentCustomerId;
    
    setHoldCustomer( idCart, $("#customerSelect :selected").val() );

    if(productList.html().length > 0) {
      $.confirm({
            title: 'Konfirmasi',
            content: 'Anda yakin ingin mengganti customer?',
            buttons: {
                ok: function () {
                  //clear server cart first
                  doClear(false); 
                  //change customer  
                  filterProduk();
                },
                cancel: function () {
                  //returning to previous selected value
                  $("#customerSelect").val(customerId);
                  $("#customerSelect").trigger('change.select2'); // Notify only Select2 of changes
                  saveCurrentCustomer();
                }
              }
        }); 
    }
    else {
      filterProduk();
    }
  }

  function setHoldCustomer(idCart, idCustomer){
    $.ajax({
      url: "<?php echo base_url('Transaksi_penjualan/Transaksi/setHoldCustomer')?>",
      type: "POST",
      data: { 'idCart': idCart, 'idCustomer': idCustomer },
      dataType: "json",
      success: function(response) {
        console.log(response);
      },
      error: function() {
        alert("setHoldCustomer error");
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
    var idHold = $('.selectedHold').attr('id').split('-');
    var idCart = $idHold[1];
    var qty = $("#qt-"+id).val();
    $.ajax({
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/updateCart')?>/"+id+"/"+qty+"/"+state+"/"+idCart,
      type : "GET",
      data :"",
      dataType : "json",
      success : function(data){
        if(data.status == 2){        
          load_order(data.list);
          fillInformation();
        }else if(data.status == 1){
          $.confirm({
              title: 'Stok',
              content: 'Stok Tidak Mencukupi',
              buttons: {
                  ok: function () {                      
                  }
                }
          });           
        }
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
    etotal = etotal + '';    
    transTotalHarga = parseInt(etotal.split(',').join(''));
    maskInputMoney(); 
  }
  function fillInformation(){
    var idHold = $(".selectedHold").attr('id').split('-');
    var idCart = idHold[1];
    $.ajax({
      url :"<?php echo base_url('Transaksi_penjualan/Transaksi/getTotal')?>" + "/" + idCart,
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
                  doClearAll(true);
              },
              cancel: function () {
                  // $.alert('Canceled!');
                  $('#btnDoOrder').html(defaultHtml);
                  $("#btnDoOrder").prop("disabled", false);  
              }
          }
      });    
  }
  function doClear(reload){
    var idHold = $('.selectedHold').attr('id').split('-');
    var idCart = idHold[1];

    if(reload != false) {
      reload = true;
    }
    // var productList = $("#productList");
    $('#btnDoOrder').html("<h5 class=\'text-bold\'>Clearing...</h5>");
    $("#btnDoOrder").prop("disabled", true);    
    $.ajax({
      url :'<?php echo base_url("Transaksi_penjualan/Transaksi/removeCart"); ?>',
      type : "POST",
      data : { 'idCart': idCart },
      dataType : "json",
      success : function(data){
        load_order(data);
        fillInformation();        
        $('#btnDoOrder').html("<h5 class=\'text-bold\'>Proses Transaksi</h5>");
        $("#btnDoOrder").prop("disabled", false);
        // window.close();
        if(reload == true) {
          window.location.reload(false);
        }
      }
    });    
  }
  function doClearAll(reload){
    if(reload != false) {
      reload = true;
    }
    $('#btnDoOrder').html("<h5 class=\'text-bold\'>Clearing...</h5>");
    $("#btnDoOrder").prop("disabled", true);    
    $.post('<?php echo base_url("Transaksi_penjualan/Transaksi/destroyCart"); ?>', function(data) {
        $('#btnDoOrder').html("<h5 class=\'text-bold\'>Proses Transaksi</h5>");
        $("#btnDoOrder").prop("disabled", false);
        // window.close();
        if(reload == true) {
          window.location.reload(false);
        }
      }
    );    
  }
  function doSubmit(){
    var idHold = $(".selectedHold").attr('id').split('-');
    var idCart = idHold[1];
    $.ajax({
      url :$('#pembelian').attr('action'),
      type : $('#pembelian').attr('method'),
      data : $('#pembelian').serialize()
              + "&idCart=" +idCart,
      dataType : "json",
      success : function(data){        
        load_order(data);
        fillInformation();        
        $('#btnDoOrder').html("<h5 class=\'text-bold\'>Proses Transaksi</h5>");
        $("#btnDoOrder").prop("disabled", false);
        // window.close();
        window.location.reload(false);
      }
    });    
  }
  $(document).ready(function(){
    $("#formpayment").on('submit', function(e){
      e.preventDefault();      
      unmaskInputMoney();
      
      var defaultHtml = $('#btnBayar').html();
      var idHold = $('.selectedHold').attr('id').split('-');
      var idCart = idHold[1];
      var paymentMethod = $("#paymentMethod").val() || '';
      var id_bank = $("#id_bank").val() || '';
      var nomor_kartu = $("#nomor_kartu").val() || '';
      var catatan = $("#catatan").val();
      var kembalian = $("#kembalian").val();

      if(paymentMethod == '' || paymentMethod == null) {
        $.alert({
            title: 'Perhatian',
            content: 'Anda belum memilih Metode Pembayaran!',
        });
      }
      else {
        $('#btnBayar').text("Saving...");
        $("#btnBayar").prop("disabled", true);      
        $.ajax({
          url :$('#formpayment').attr('action'),
          type : $('#formpayment').attr('method'),
          data : $('#formpayment').serialize() 
                  + "&paymentMethod=" +paymentMethod
                  + "&catatan=" +catatan
                  + "&kembalian=" +kembalian
                  + "&id_bank=" +id_bank
                  + "&nomor_kartu=" +nomor_kartu
                  + "&id_cart=" +idCart,
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

            // window.open("<?php echo base_url('Transaksi_penjualan/Transaksi/invoices'); ?>/"+data.idOrder, "_blank");
          },
          error: function(){
            $('#btnBayar').html(defaultHtml);
            $("#btnBayar").prop("disabled", false);
          }
        });
      }
      maskInputMoney();
    });
    
    $("#pembelian").on('submit', function(e){
      $('#btnDoOrder').html("<h5 class=\'text-bold\'>Saving...</h5>");
      $("#btnDoOrder").prop("disabled", true);
      e.preventDefault();
      $.confirm({
          title: 'Confirm!',
          content: 'Simple confirm!',
          buttons: {
              confirm: function () {
                  doSubmit();
              },
              cancel: function () {                  
              }
          }
      });      
    });
  });

  //BARCODE Handler
  $(document).on("keyup", function(ev) {
    if(!$(ev.target).is("input:text, textarea")) {
      var keycode = (ev.keyCode ? ev.keyCode : ev.which);
      if(keycode == '16') {
        $("input#barcode").focus();
      }
    }
  });
  $("input#barcode").on("keydown keypress", function(ev) {
      var keycode = (ev.keyCode ? ev.keyCode : ev.which);
      if(keycode == '13') {
        ev.preventDefault();
        ev.stopPropagation();
        // return false;
        // alert("submit");
        checkBarcode();
      }
  }); 
  $("#btnBarcode").on("click", function(ev){
    ev.preventDefault();
    checkBarcode();
  });
  function checkBarcode() {
    var barcode = $("#barcode").val() || ''; //Barcode == IDproduk
    var defaultHtml = $('#btnBarcode').html();

    if(barcode != '') {
      $.ajax({
        url: '<?php echo base_url("Transaksi_penjualan/Transaksi/getBarcode")?>'+'/'+barcode,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
          $('#btnBarcode').html("Checking...");
          $('#btnBarcode').prop('disabled', true);
        },
        success: function(response, status) {
          var data = response.data;
          $('#btnBarcode').html(defaultHtml);
          $('#btnBarcode').prop('disabled', false);

          if(response.status == 1) {
            var idCustomer = $("#customerSelect").val() || '';
            addToCartBarcode(data.id_produk, idCustomer, data.id_warna, data.id_ukuran);
          }
          else if(response.status == 2){
            $.alert("Produk tidak ditemukan!");
          }
          else {
            $.alert("Stok tidak mencukupi!");
          }
        },
        error(jqXhr, status, errorThrown) {
          console.log(status);
          $('#btnBarcode').html(defaultHtml);
          $('#btnBarcode').prop('disabled', false);
          $.alert("Terjadi kesalahan dalam proses pengecekan!");
        }
      });
    }
      // addToCart(barcode);
      // selectProdukOptions(barcode);
  };

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
        url : '<?php echo base_url("Transaksi_penjualan/Transaksi/getInvoiceData"); ?>/'+idOrder,
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

  //HOLD
  function load_hold(json) {
    /*var html = '<span class="Hold selectedHold">1<span id="Time"> <?php echo date("H:i") ?> </span></span>';
    $("#holdList").html(html);*/
    $("#holdList").html('');

    $.each(json, function(i, value) {
      html = "<span class='Hold' id=\'hold-" + json[i].id + "\'  onclick=selectHold(\'" + json[i].id + "\')>" + json[i].id + "<span id='Time'>" + json[i].nama + "</span></span>";
        $("#holdList").append(html);
    });

    $("#holdList > span:last-child").trigger('click');
  }
  function selectHold(id) {
    $.ajax({
        url : "<?php echo site_url('Transaksi_penjualan/Transaksi/selectHold')?>/"+id,
        type: "POST",
        data: { 'idCart': id },
        dataType: "JSON",
        success: function(data)
        {
           var customerId = 0;
           var holds = data.hold;
           var orders = data.order;

           $('#hold-'+id).parent().children().removeClass('selectedHold');
           $('#hold-'+id).addClass('selectedHold');

           console.log(holds);
           var hold = 0;
           $.each(holds, function(i, value) {
            if(holds[i].id == id) {
              hold = holds[i];
            }
           });

           if(hold.id_customer != '') {
            customerId = hold.id_customer;
           } 
           $("#idCustomer").val(customerId); //hidden input
           $("#customerSelect").val(customerId);
           $("#customerSelect").trigger('change.select2'); // Notify 
           // filterProduk();

           load_order(orders);
           fillInformation(); 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           alert("selectHold error");
        }
    });
  }
  function addHold() {
    $.ajax({
        url : "<?php echo site_url('Transaksi_penjualan/Transaksi/addHold')?>/",
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
           load_hold(data.hold);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           alert("addHold error");
        }
    });
  }
  function removeHold() {
     var number = $('.selectedHold').clone().children().remove().end().text();
     if(number != 1) {
        $.confirm({
            title: 'Konfirmasi',
            content: 'Yakin hapus sesi <b>' + number + "</b>?",
            type: 'red',
            buttons: {
                Yes: {
                  btnClass: 'btn-blue',
                  action: function () { doRemoveHold(number); } 
                },
                Cancel: function(){}
              }
        }); 
     }
  }
  function doRemoveHold(number) {
    $.ajax({
      url : "<?php echo site_url('Transaksi_penjualan/Transaksi/removeHold')?>/"+number,
      type: "POST", 
      dataType: "JSON",
      success: function(data)
      {
        load_hold(data.hold);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
         alert("removeHold error");
      }
    });
  }
</script>