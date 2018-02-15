<!-- Page Content -->
<style type="text/css">
#modaldetail 
{
    /*max-width: 1140px; */
}
</style>

<div class="container">
<div class="row" style='min-height:80px;'></div>
<div class="row">
  <h3><strong>Transaksi</strong> - Penjualan</h3>
</div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>         
              <tr>
                  <th class="text-center hidden-xs">ID</th>
                  <th class="text-center hidden-xs">Customer</th>
                  <th class="text-center hidden-xs">Catatan</th>
                  <th class="text-center hidden-xs">Total Berat (gr)</th>
                  <th class="text-center hidden-xs">Total Qty</th>
                  <!-- <th class="text-center hidden-xs">Biaya Kirim (IDR)</th> -->
                  <th class="text-center hidden-xs">Grand Total (IDR)</th>
                  <th class="text-center hidden-xs">Tanggal Transaksi</th>
                  <th class="text-center hidden-xs no-sort">Aksi</th>
              </tr>
          </thead>
          <tbody id='bodytable'>            
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <a type="button" class="btn btn-add btn-lg" href="<?php echo base_url('index/modul/Transaksi_penjualan-Transaksi-transaksi'); ?>" target="_blank">
     Tambah Penjualan
   </a>
</div>
<!-- /.container -->
<!-- Modal Detail -->
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Penjualan</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-lg-12"  id="body-detail">
           </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
 </div>
</div>
<!-- /.Modal Detail-->

<!-- Modal Payment -->
<div class="modal fade" id="modalpayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="Addpayament">Proses Pembayaran</h4>
        </div>
        <form method="POST" action="<?php echo base_url('Transaksi_penjualan/Transaksi/updateOrder'); ?>" id="formpayment">
        <div class="modal-body">
             <div class="form-group">
               <h2 id="textMetodePembayaran">Tunai</h2>
             </div>

             <div class="form-group hidden" style="visibility: hidden;">
               <label for="jenisOrder">Jenis Order</label>
               <input type="hidden" name="id_order" id="id_order">
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

<script type="text/javascript" language="javascript" >
    var listMetodePembayaran = <?php echo $list_metode_pembayaran; ?>;
    var transTotalHarga = 0;
    
    function maskInputMoney(){
      $('.money').mask('#.##0', {reverse: true});
    }
    function unmaskInputMoney(){
      $('.money').unmask();
    }    

    load_metode_pembayaran(listMetodePembayaran);
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

    function detail(id){
      $.ajax({
        url :"<?php echo base_url('Transaksi_penjualan/Transaksi/detail')?>/"+id,
        type : "GET",
        data :"",
        success : function(data){
          $("#body-detail").html(data);
        }
      });       
      $("#modaldetail").modal("show");
    }

    $(document).ready(function() {
      var dataTable = $('#TableMain').DataTable( {
          "processing": true,
          "serverSide": true,
          "order": [[6, 'DESC']],
          "ajax":{
              url : "<?php echo base_url('Transaksi_penjualan/Transaksi/data'); ?>",
              type: "post",
              error: function(){
                  $("#TableMain").append('<tbody class="employee-grid-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
                  // $("#employee-grid_processing").css("display","none");
                  // dataTable.ajax.reload( null, false );
              }
          },
          "columnDefs": [ {
            "targets"  : 'no-sort',
            "orderable": false,
          }],
      });
      maskInputMoney();

      $("#formpayment").on('submit', function(e){
        e.preventDefault();      
        unmaskInputMoney();
        var defaultHtml = $('#btnBayar').html();
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
                    + "&nomor_kartu=" +nomor_kartu,
            dataType : "json",
            success : function(response){
              $("#modalpayment").modal('hide');
              $('#btnBayar').html(defaultHtml);
              $("#btnBayar").prop("disabled", false);
              if(response.status == 1) {
                new PNotify({
                  title: 'Sukses',
                  text: 'Pembayaran berhasil disimpan!',
                  type: 'success',
                  hide: true, delay: 5000,
                  styling: 'bootstrap3'
                });
              } else {
                new PNotify({
                  title: 'Error',
                  text: 'Terjadi kesalahan dalam proses pembayaran!',
                  type: 'error',
                  hide: true, delay: 5000,
                  styling: 'bootstrap3'
                }); 
              }
              // showInvoce(data.idOrder);
            },
            error: function(){
              $('#btnBayar').html(defaultHtml);
              $("#btnBayar").prop("disabled", false);
              new PNotify({
                  title: 'Error',
                  text: 'Terjadi kesalahan dalam proses pembayaran!',
                  type: 'error',
                  hide: true, delay: 5000,
                  styling: 'bootstrap3'
                });
            }
          });
        }
        dataTable.draw(false);
        maskInputMoney();
      });
    });

    //Select Payment method handler
    $('#paymentMethod').on('change', function() {
      var textMetodePembayaran = $(this).find('option:selected').html();
      $("#textMetodePembayaran").html(textMetodePembayaran);
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

    function payment(id) {
      var idOrder = id;
      $("#Paid").val('');
      $("#catatan").val('');
      $("#textTotalBayar").html('0');
      $("#textKembalian").html('0');
      if(idOrder!='') {
        getOrder(id);
      } else {
        $.alert('ID Order tidak ditemukan!');
      }
    }

    function getOrder(id) {
      var textMetodePembayaran = $("#paymentMethod :selected").html() || '';
      if(id != '') {
        $.getJSON("<?php echo base_url('Transaksi_penjualan/transaksi/getOrderById')?>/" + id, function(response) {
          if(response.status == 1) {
            // console.log(response.data);
            unmaskInputMoney();
            grandtotal = response.data.grand_total;
            transTotalHarga = parseInt(grandtotal.split(',').join(''));
            $("#id_order").val(id);
            $("#textMetodePembayaran").html(textMetodePembayaran);
            $("#textTotalHarga").html(grandtotal);
            $("#modalpayment").modal("show");
            $("#modalpayment").on("shown.bs.modal", function() {
              $("#Paid").focus();
            }); 
            maskInputMoney();
          } else {
            $.alert('Data Order tidak ditemukan!');
          }
        });
      }
    }
</script>
