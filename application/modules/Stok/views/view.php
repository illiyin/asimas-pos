<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Stok</strong> - Semua Stok</h3>
  </div>
   <!-- <div class="row">
      <form id="myform" method="post">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="operator" class="label-control">Operator</label>
          <select name="operator" id="operator" class="form-control" required="">
            <option value="kurang_dari">Kurang Dari</option>
            <option value="lebih_dari">Lebih Dari</option>
          </select>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="stok" class="label-control">Stok</label>
          <input type="number" min="0" name="stok" id="stok" class="form-control" placeholder="Jumlah Stok" required="">
        </div>
        <div class="form-group text-right">
          <button id="fReset" class="btn btn-default" disabled=""><i class="fa fa-undo"></i> Reset</button>
          <button id="fSubmit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
        </div>
      </div>
      </form>
   </div> -->
   <hr>

   <div class="row" style="margin-top:10px;">
      <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                <th class="text-center no-sort">#</th>
                <th class="text-center">Update</th>
                <th class="text-center">Nama Produk</th>
                <th class="text-center">SKU</th>
                <th class="text-center">Warna</th>
                <th class="text-center">Ukuran</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Total Stok Akhir</th>
                <th class="text-center">ID Order</th>
                <th class="text-center">Status</th>
                <!-- <th class="text-center no-sort">Aksi</th> -->
              </tr>
          </thead>

          <tbody id='bodytable'>
            
          </tbody>
      </table>
   </div>
</div>
<!-- /.container -->


<script type="text/javascript">
  $(document).ready(function() {
    //initialize input money masking
    maskInputMoney();

    $('#fSubmit').click(function(e){
      e.preventDefault();
      $("#fReset").attr("disabled", false);
      initDataTable.destroy();
      initDataTable = $('#TableMainServer').DataTable({
        "bProcessing": true,
        "bServerSide": true,
        "order": [[1, 'DESC']],
        "ajax":{
              url :"<?php echo base_url()?>Stok/Master/data",
              type: "post",  // type of method  , by default would be get
              "data": {
                  // operator: $("#operator").val(),
                  // stok: $("#stok").val()
              },
              error: function(){ /*error handling code*/ }
            },
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }]
      });
      initDataTable.ajax.reload();
    });
  });

  $('#fReset').click(function(){
      $("#fReset").attr("disabled", true);
      $('.input-daterange input').each(function() {
        $(this).datepicker('clearDates');
      });
      initDataTable.destroy();
      initDataTable = $('#TableMainServer').DataTable({
        "bProcessing": true,
        "bServerSide": true,
        "order": [[1, 'DESC']],
        "ajax":{
              url :"<?php echo base_url()?>Stok/Master/data",
              type: "post",  // type of method  , by default would be get
              "data": {
                  // start_date: "", end_date: ""
              },
              error: function(){  // error handling code
                // $("#employee_grid_processing").css("display","none");
              }
            },
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
        }]
      });
      initDataTable.ajax.reload();
    });

  function maskInputMoney(){
    $('.money').mask('#.##0', {reverse: true});
  }
  function unmaskInputMoney(){
    $('.money').unmask();
  }

  var jsonlist = <?php echo $list; ?>;
  
  var awalLoad = true;
  var initDataTable = $('#TableMainServer').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "order": [[1, 'DESC']],
      "ajax":{
            url :"<?php echo base_url()?>Stok/Master/data",
            type: "post",  // type of method  , by default would be get
            "data": {
                // operator: $("#operator").val(),
                // stok: $("#stok").val()
            },
            error: function(){ /*error handling code*/ }
          },
      "columnDefs": [ {
        "targets"  : 'no-sort',
        "orderable": false,
      }]
    });
 
  // $("#myform").on('submit', function(e){
  //   e.preventDefault();
  //   var action = "<?php echo base_url('Stok/Master/add')?>/";

  //   unmaskInputMoney(); //clean input masking first
  //   var param = $('#myform').serialize();
  //   if ($("#id").val() != ""){
  //     param = $('#myform').serialize()+"&id="+$('#id').val();
  //   }
  //   maskInputMoney(); //re run masking
    
  //   $.ajax({
  //     url: action,
  //     type: 'post',
  //     data: param,
  //     dataType: 'json',
  //     beforeSend: function() { 
  //       // tambahkan loading
  //       $("#fSubmit").prop("disabled", true);
  //       $('#fSubmit').html('Sedang Mencari...');
  //     },
  //     success: function (data) {
  //       if (data.status == '3'){
  //         console.log("ojueojueokl"+data.status);
  //         jsonlist = data.list;
  //         // loadData(jsonlist);
  //         initDataTable.ajax.reload();

  //         $('#fSubmit').html('Simpan');
  //         $("#fSubmit").prop("disabled", false);
  //         $("#modalform").modal('hide');
  //         new PNotify({
  //                     title: 'Sukses',
  //                     text: 'Data ditemukan!',
  //                     type: 'success',
  //                     hide: true,
  //                     delay: 5000,
  //                     styling: 'bootstrap3'
  //                   });
  //       }
  //       else if (data.status == '2'){
  //         console.log("ojueojueokl"+data.status);
  //         jsonlist = data.list;
  //         // loadData(jsonlist);
  //         initDataTable.ajax.reload();

  //         $('#fSubmit').html('Simpan');
  //         $("#fSubmit").prop("disabled", false);
  //         $("#modalform").modal('hide');
  //         new PNotify({
  //                     title: 'Gagal',
  //                     text: 'Data tidak ditemukan!',
  //                     type: 'error',
  //                     hide: true,
  //                     delay: 5000,
  //                     styling: 'bootstrap3'
  //                   });
  //       }
  //     }
  //   });
  // });
 
</script>
