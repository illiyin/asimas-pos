<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Finance</strong> - Transfer Harian</h3>
  </div>
   <div class="row">
      <div class="input-group input-daterange col-sm-6 pull-right">
        <input type="text" id="start_date" class="form-control datepicker" placeholder="YYYY/MM/DD">
        <div class="input-group-addon">Sampai</div>
        <input type="text" id="end_date" class="form-control datepicker" placeholder="YYYY/MM/DD">
        <span class="input-group-btn">
          <button id="fReset" class="btn btn-default" type="button" disabled=""><i class="fa fa-undo"></i> Reset</button>
          <button id="fSubmit" class="btn btn-default" type="button"><i class="fa fa-filter"></i> Tampilkan</button>
        </span>
      </div>
   </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                <th class="text-center no-sort">#</th>
                <th class="text-center">Pencatat</th>
                <th class="text-center">Bank</th>
                <th class="text-center">Nominal</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center" class="hidden-xs">Tanggal Transfer</th>
                <!-- <th class="text-center no-sort">Aksi</th> -->
              </tr>
          </thead>

          <tbody id='bodytable'>
            
          </tbody>
      </table>
   </div>
   <div class="row">
     <!-- Button trigger modal -->
     <button type="button" class="btn btn-add btn-lg"  onclick="showAdd()">
       Tambah Transaksi
     </button>
     <div class=" float-right">
        <a class="btn btn-add btn-xs" href="<?php echo base_url()?>Finance_transfer/Master/download_csv">Download CSV</a>
        <a class="btn btn-add btn-xs" data-toggle="modal" data-target="#uploadModal">Upload file CSV</a>
     </div>
   </div>
</div>
<!-- /.container -->

<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="uploadForm" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Import Data</h4>
      </div>
      <div class="modal-body">
         <div class="form-group">
           <label for="exampleInputFile">Upload File CSV</label>
           <input type="file" name="upload_data" id="upload_data" class="form-control" required="">
           <p class="help-block text-right"><a href="<?=site_url('files/finance/contoh/transfer_harian.csv');?>">Download Contoh CSV</a></p>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" id="uSimpan" class="btn btn-add">Upload</button>
      </div>
      </form>
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
        <h4 class="modal-title" id="myModalLabel">Tambah Transaksi</h4>
      </div>
      <form action="" method="POST" id="myform" enctype="multipart/form-data"> <div class="modal-body">
           <div class="row">
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_bank">Bank</label>
                 <select name="id_bank" class="form-control" id="id_bank" required="">
                 </select>
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Transaksi">
               </div>
             </div>
             <div class="col-sm-6">
                <div class="form-group">
                 <label for="nominal">Nominal (IDR)</label>
                 <div class="input-group">
                  <span class="input-group-addon">Rp</span> 
                  <input type="text" name="nominal" Required class="form-control money" id="nominal" placeholder="Nominal">
                 </div>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="tanggal_transfer">Tanggal Transfer</label>
                 <div class="input-group">
                    <input type="text" name="tanggal_transfer" id="tanggal_transfer" class="form-control datepicker" placeholder="YYYY/MM/DD" required="">
                    <div class="input-group-addon">
                      <span class="fa fa-calendar"></span>
                    </div>
                  </div>
               </div>
             </div>
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="keterangan">Keterangan</label>
                 <textarea name="keterangan" rows="4" Required class="form-control" id="keterangan" placeholder="Keterangan"></textarea>
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
<!-- /.Modal Add-->

<script type="text/javascript">
  $(document).ready(function() {
    //initialize input money masking
    maskInputMoney();
    //initialize input datepicker
    $('.datepicker').datepicker({
      todayBtn: 'linked',
      todayHighlight: true,
      autoclose: true,
      format: 'yyyy/mm/dd',
      endDate: '+0d'
    });
    $('.input-daterange input').each(function() {
        $(this).datepicker('clearDates');
    });
    $('#fSubmit').click(function(){
      $("#fReset").attr("disabled", false);
      initDataTable.destroy();
      initDataTable = $('#TableMainServer').DataTable({
        "bProcessing": true,
        "bServerSide": true,
        "order": [[5, 'DESC']],
        "ajax":{
              url :"<?php echo base_url()?>Finance_transfer/Master/data",
              type: "post",  // type of method  , by default would be get
              "data": {
                  start_date: $("#start_date").val(),
                  end_date: $("#end_date").val()
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
    $('#fReset').click(function(){
      $("#fReset").attr("disabled", true);
      $('.input-daterange input').each(function() {
        $(this).datepicker('clearDates');
      });
      initDataTable.destroy();
      initDataTable = $('#TableMainServer').DataTable({
        "bProcessing": true,
        "bServerSide": true,
        "order": [[5, 'DESC']],
        "ajax":{
              url :"<?php echo base_url()?>Finance_transfer/Master/data",
              type: "post",  // type of method  , by default would be get
              "data": {
                  start_date: "", end_date: ""
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
  });

  function maskInputMoney(){
    $('.money').mask('#.##0', {reverse: true});
  }
  function unmaskInputMoney(){
    $('.money').unmask();
  }

  var jsonlist = <?php echo $list; ?>;
  var jsonBank = <?php echo $list_bank; ?>;
  
  var awalLoad = true;
  var initDataTable = $('#TableMainServer').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "order": [[5, 'DESC']],
      "ajax":{
            url :"<?php echo base_url()?>Finance_transfer/Master/data",
            type: "post",  // type of method  , by default would be get
            "data": {
                start_date: $("#start_date").val(),
                end_date: $("#end_date").val()
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
    load_select_option(jsonBank, "#id_bank", "Bank");
  }

  function showAdd(){
    load_select();
    $("#myModalLabel").text("Tambah Transaksi");
    $("#id").val("");
    $("#id_bank").val("");
    $("#nominal").val("");
    $("#tanggal_transfer").val("");
    $("#keterangan").val("");
    unmaskInputMoney(); maskInputMoney();
    $("#modalform").modal("show");    
  }
  
  function showUpdate(i){
    load_select();
    var dataUpdate = jsonlist.filter(function (index) { return index.id == i }); 

    $("#myModalLabel").text("Ubah Transaksi");
    $("#id").val(dataUpdate[0].id);
    $("#id_bank").val((dataUpdate[0].id_bank==0) ? "" : dataUpdate[0].id_bank);
    $("#nominal").val(dataUpdate[0].nominal);
    $("#keterangan").val(dataUpdate[0].keterangan);
    
    unmaskInputMoney(); maskInputMoney();
    $("#modalform").modal("show");
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
    var action = "<?php echo base_url('Finance_transfer/Master/add')?>/";
    if ($("#id").val() != ""){
      action = "<?php echo base_url('Finance_transfer/Master/edit')?>/";
      notifText = 'Data berhasil diubah!';
    }
    unmaskInputMoney(); //clean input masking first
    var param = $('#myform').serialize();
    if ($("#id").val() != ""){
      param = $('#myform').serialize()+"&id="+$('#id').val();
    }
    maskInputMoney(); //re run masking
    
    $.ajax({
      url: action,
      type: 'post',
      data: param,
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
  $("#uploadForm").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Finance_transfer/Master/upload_csv')?>/";
    var paramImg = new FormData(jQuery('#uploadForm')[0]);
    
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
        $("#uSimpan").prop("disabled", true);
        $('#uSimpan').html('Sedang Menyimpan...');
      },
      success: function (data) {
        if (data.status == '3'){
          console.log("ojueojueokl"+data.status);
          jsonlist = data.list;
          initDataTable.ajax.reload();

          $('#uSimpan').html('Simpan');
          $("#uSimpan").prop("disabled", false);
          $("#uploadModal").modal('hide');
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
          url: '<?php echo base_url('Finance_transfer/Master/delete'); ?>/',
          data: {"id":jsonlist[i].id},
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
