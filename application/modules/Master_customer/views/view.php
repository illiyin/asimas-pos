<!-- Page Content -->
<style type="text/css">
  .awesomplete {
    display: block;
  }  
  input[type='password'].form-control {
    height: 34px;
    font-size: 14px;
  }
</style>

<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Master</strong> - Customer</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center">Nama Customer</th>
                  <th class="text-center">Alamat</th>
                  <th class="text-center">No. Telp</th>
                  <th class="text-center">Email</th>
                  <th class="text-center">Status Aktif</th>
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
     Tambah Customer
   </button>
</div>
<!-- /.container -->

<!-- Modal Add -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Customer</h4>
      </div>
      <form action="" method="POST" id="myform">      
        <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama">Nama Customer</label>
                 <input type="text" name="nama" maxlength="50" Required class="form-control" id="nama" placeholder="Nama Customer">
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Customer">
               </div>
             </div>
             <div class="col-sm-12">
               <div class="form-group">
                 <label for="alamat">Alamat</label>
                 <input type="text" name="alamat" maxlength="30" class="form-control" id="alamat" placeholder="Alamat Customer" required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="email">Email</label>
                 <input type="email" maxlength="50" name="email" class="form-control" id="email" placeholder="Email Customer" required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="npwp">Password</label>
                 <input type="password" name="password" class="form-control" id="password" placeholder="Password" title="Kosongi kolom ini jika tidak ingin mengganti password">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="no_telp">No. Telp</label>
                 <input type="number" min="0" maxlength="50" name="no_telp" class="form-control" id="no_telp" placeholder="No Telp Customer" required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_provinsi">Provinsi</label>
                 <select  onchange='get_kota()' name="id_provinsi" class="form-control" id="id_provinsi" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kota">Kota</label>
                 <select name="id_kota" class="form-control" id="id_kota" required="">
                 </select>
                
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="kodepos">Kode Pos</label>
                 <input type="number" min="0" maxlength="10" name="kodepos" class="form-control" id="kodepos" placeholder="Kode Pos" required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_customer_level">Customer Level</label>
                 <select name="id_customer_level" class="form-control" id="id_customer_level" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="ktp">No. KTP</label>
                 <input type="number" name="ktp" class="form-control" id="ktp" placeholder="Nomor KTP">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="npwp">NPWP</label>
                 <input type="text" name="npwp" class="form-control" id="npwp" placeholder="NPWP">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="nama_bank">Nama Bank</label>
                 <input type="text" name="nama_bank" class="form-control" id="nama_bank" placeholder="Nama Bank">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="no_rekening">No. Rekening</label>
                 <input type="text" name="no_rekening" class="form-control" id="no_rekening" placeholder="Nomor Rekening">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="rekening_an">Rekening Atas Nama</label>
                 <input type="text" name="rekening_an" class="form-control" id="rekening_an" placeholder="Rekening Atas Nama">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="sales">Nama Sales</label>
                 <input type="text" name="sales" class="form-control awesomplete" list="salesList" id="sales" placeholder="Nama Sales">
                 <datalist id="salesList">
                   <?php 
                    foreach (json_decode($list_pegawai) as $pegawai) { ?>
                      <option><?php echo $pegawai->nama;?></option>  
                    <?php }
                   ?>
                 </datalist>
               </div>
             </div>
             <div class="col-sm-12">
               <div class="form-group">
                 <label for="keterangan">Keterangan</label>
                 <textarea name="keterangan" class="form-control" id="keterangan" placeholder="Keterangan"></textarea>
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
  // initialize datatable
  var table = $("#TableMain").DataTable({
    "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": "no-sort"
        } ],
    "drawCallback": function() {
      $('.bootstrap-toggle').bootstrapToggle({
        size: 'small',
        on: '<i class="fa fa-check-square-o" title="Aktif"></i> Aktif',
        off: '<i class="fa fa-square-o" title="Tidak Aktif"></i> Tidak Aktif',
        onstyle: 'primary',
        offstyle: 'default',
      });
    }
        // "order": [[ 1, 'asc' ]]    
  });
  table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<span style='display:block' class='text-center'>"+(i+1)+"</span>";
        } );
  } ).draw();
    

  var jsonList = <?php echo $list; ?>;
  var jsonProv = <?php echo $list_prov; ?>;
  var jsonKota = <?php echo $list_kota; ?>;
  var jsonLevel = <?php echo $list_level; ?>;
 
  var awalLoad = true;
  
  loadData(jsonList);
  load_prov(jsonProv);
  load_level(jsonLevel);
  
  function load_prov(json){
  	var html = "<option value='' selected disabled>Pilih Provinsi</option>";
  	for (var i=0;i<json.length;i++){
  	     html = html+ "<option value='"+json[i].id+"'>"+json[i].nama+"</option>";
  	}
  	$("#id_provinsi").html(html);
  }
  function load_kota(json, idProv=0){
    // console.log(json);
    var html = "<option value='' selected disabled>Pilih Kota</option>";
    for (var i=0;i<json.length;i++){
         if(json[i].id_provinsi == idProv){
          html = html+ "<option value='"+json[i].id+"'>"+json[i].nama+"</option>";
         }
    }
    $("#id_kota").html(html);
  }
  function load_level(json){
    // console.log(json);
  	var html = "<option value='' selected disabled>Pilih Customer Level</option>";
  	for (var i=0;i<json.length;i++){
  	     html = html+ "<option value='"+json[i].id+"'>"+json[i].nama+"</option>";
  	}
  	$("#id_customer_level").html(html);
  }
  
  function get_kota(){
  	if ($("#id_provinsi").val() == "" || $("#id_provinsi").val()==null){
  	   return false;
  	}
  	$("#id_kota").prop("disabled",true);
  	
  	$.ajax({
  	   url :"<?php echo base_url('Master_customer/Master/get_kota')?>/",
  	   type : "GET",
  	   data :"id_prov="+$("#id_provinsi").val(),
  	   dataType : "json",
  	   success : function(data){
  	      $("#id_kota").prop("disabled",false);
  	      load_kota(data, $("#id_provinsi").val());
  	      
  	   }
  	});
  }
  function sync_kota(provinsi){
    $.ajax({
       url :"<?php echo base_url('Master_customer/Master/get_kota')?>/",
       type : "GET",
       data :"id_prov="+provinsi,
       dataType : "json",
       success : function(data){
          $("#id_kota").prop("disabled",false);
          load_kota(data, provinsi);
       }
    });
  }
  
  function loadData(json){
	  //clear table
    table.clear().draw();
    var statusCheck = '';
	  for(var i=0; i<json.length; i++){
      if(json[i].deleted == 1) { statusCheck = "checked"; }
      else if(json[i].deleted == 2) { statusCheck = ""; }
		  table.row.add( [
            "",
            json[i].nama,
            json[i].alamat,
            json[i].no_telp,
            json[i].email,
            '<span class="center-block text-center"> <input type = "checkbox" id="toggle_'+ json[i].id + '" class="bootstrap-toggle" onchange="confirmStatus(this);" '+ statusCheck +'> </span>',
            DateFormat.format.date(json[i].date_add, "dd-MM-yyyy HH:mm"),
            '<td class="text-center"><div class="btn-group" >'+
                '<a id="group'+i+'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'+
                '<a class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showUpdate('+i+')"><i class="fa fa-pencil"></i></a>'+
               '</div>'+
            '</td>'
        ] ).draw( false );
    }
	  if (!awalLoad){
		  $('.divpopover').attr("data-content","ok");
		  $('.divpopover').popover();
	  }
	  awalLoad = false;	 
  }
  
  
  function showAdd(){
    load_kota(jsonKota, 0);

    $("#myModalLabel").text("Tambah Customer");
    $("#id").val("");
    $("#nama").val("");
    $("#alamat").val("");
    $("#no_telp").val("");
    $("#email").val("");
    $("#password").val("");
    $("#password").prop('required', true);
    $("#ktp").val("");
    $("#npwp").val("");
    $("#nama_bank").val("");
    $("#no_rekening").val("");
    $("#rekening_an").val("");
    $("#keterangan").val("");
    $("#sales").val("");
    $("#kodepos").val("");
    $("#id_customer_level").val("");
    load_prov(jsonProv);
    load_level(jsonLevel);
    $("#modalform").modal("show");    
  }
  
  function showUpdate(i){
    load_prov(jsonProv);
    load_kota(jsonKota, jsonList[i].id_provinsi);
    load_level(jsonLevel);

    $("#myModalLabel").text("Ubah Customer");
    $("#id").val(jsonList[i].id);
    $("#nama").val(jsonList[i].nama);
    $("#alamat").val(jsonList[i].alamat);
    $("#no_telp").val(jsonList[i].no_telp);
    $("#email").val(jsonList[i].email);
    $("#password").val('');
    $("#password").prop('required', false);
    $("#ktp").val(jsonList[i].ktp);
    $("#npwp").val(jsonList[i].npwp);
    $("#nama_bank").val(jsonList[i].nama_bank);
    $("#no_rekening").val(jsonList[i].no_rekening);
    $("#rekening_an").val(jsonList[i].rekening_an);
    $("#keterangan").val(jsonList[i].keterangan);
    $("#sales").val(jsonList[i].sales);
    $("#kodepos").val(jsonList[i].kode_pos);
  	$("#id_provinsi").val(jsonList[i].id_provinsi);
  	$("#id_kota").val(jsonList[i].id_kota);
  	$("#id_customer_level").val(jsonList[i].id_customer_level);
	  $("#modalform").modal("show");
  }
  
  $("#myform").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Master_customer/Master/add')?>/";
    if ($("#id").val() != ""){
      action = "<?php echo base_url('Master_customer/Master/edit')?>/";
      notifText = 'Data berhasil diubah!';
	  }
	  var param = $('#myform').serialize();
	  if ($("#id").val() != ""){
		 param = $('#myform').serialize()+"&id="+$('#id').val();
	  }
	  
    $.ajax({
      type: 'post',
      url: action,
      data: param,
	    dataType: 'json',
      beforeSend: function() { 
        // tambahkan loading
        $('#aSimpan').html('Sedang Menyimpan...');
        $("#aSimpan").prop("disabled", true);
      },
      success: function (data) {
  			if (data.status == '3'){
  				jsonList = data.list;
  				loadData(jsonList);
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
        else if(data.status == 1) {
          $('#aSimpan').html('Simpan');
          $("#aSimpan").prop("disabled", false);
          new PNotify({
                  title: 'Perhatian',
                  text: 'Email telah terdaftar!',
                  hide: true,
                  delay: 5000,
                  styling: 'bootstrap3'
                }); 
        }
      }
    });
  });

  var allowChange = true;
  function confirmStatus(elem){
    var isChecked = $(elem).is(':checked');
    var targetId = $(elem).attr('id');
    if(isChecked) {
      textConfirm = 'Ubah status customer menjadi <i class="label label-primary">Aktif</i>?';
      setStatus = 1;
      resetToggle = 'off';
    }
    else {
      textConfirm = 'Ubah status customer menjadi <i class="label label-default">Tidak aktif</i>?';
      setStatus = 2;
      resetToggle = 'on';
    }

    if(allowChange == true) {
      $.confirm({
        title: 'Konfirmasi',
        content: textConfirm,
        buttons: {
            confirm: {
              text: 'Ya',
              btnClass: 'btn-primary',
              action: function() {
                allowChange = true;
                var id  = parseInt(targetId.replace('toggle_',''));
                updateStatus(id, setStatus);
              }
            },
            cancel: {
              text: 'Batal',
              action: function() {
                allowChange = false;
                $('#'+targetId).bootstrapToggle(resetToggle); 
              }
            }
          }
      });
    }
    allowChange = true;
  }
  function updateStatus(id, status){
      $.ajax({
        url :"<?php echo base_url('Master_customer/Master/update_status')?>",
        type : "POST",
        dataType: 'json',
        data : { 'id':id,  'status': status},
        success : function(data){ }
      });   
  }
	
	function deleteData(element){
		var el = $(element).attr("id");
		// console.log(el);
		var id  = el.replace("aConfirm","");
		var i = parseInt(id);
		//console.log(jsonList[i]);
		$.ajax({
          type: 'post',
          url: '<?php echo base_url('Master_customer/Master/delete'); ?>/',
          data: {"id":jsonList[i].id},
		      dataType: 'json',
          beforeSend: function() { 
            // kasi loading
            $("#aConfirm"+i).html("Sedang Menghapus...");
            $("#aConfirm"+i).prop("disabled", true);
          },
          success: function (data) {
      			if (data.status == '3'){
              $("#aConfirm"+i).prop("disabled", false);
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
      				jsonList = data.list;
      				loadData(jsonList);
      			}
          }    
        });
	}
	
	function confirmDelete(el){
		var element = $(el).attr("id");
		// console.log(element);
		var id  = element.replace("group","");
		var i = parseInt(id);
    $(el).attr("data-content","<button class=\'btn btn-danger myconfirm\'  href=\'#\' onclick=\'deleteData(this)\' id=\'aConfirm"+i+"\' style=\'min-width:85px\'><i class=\'fa fa-trash\'></i> Ya</button>");
		$(el).popover();
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
