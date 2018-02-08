<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Master</strong> - Pegawai</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center">Nama Pegawai</th>
                  <th class="text-center">Alamat</th>
                  <th class="text-center">No. HP</th>
                  <th class="text-center" class="hidden-xs">Email</th>
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
     Tambah Pegawai
   </button>
</div>
<!-- /.container -->

<!-- Modal Add -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Pegawai</h4>
      </div>
      <form action="" method="POST" id="myform">      
        <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama">Nama Pegawai</label>
                 <input type="text" name="nama" maxlength="50" Required class="form-control" id="nama" placeholder="Nama Pegawai">
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Pegawai">
               </div>
             </div>
             <div class="col-sm-12">
               <div class="form-group">
                 <label for="alamat">Alamat Pegawai</label>
                 <input type="text" name="alamat" maxlength="30" class="form-control" id="alamat" placeholder="Alamat Pegawai" required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="no_telp">No Telp Pegawai</label>
                 <input type="number" min="0" maxlength="50" name="no_telp" class="form-control" id="no_telp" placeholder="No Telp Pegawai"  required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="email">Email Pegawai</label>
                 <input type="email" maxlength="50" name="email" class="form-control" id="email" placeholder="Email Pegawai"  required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_provinsi">Provinsi</label>
                 <select  onchange='get_kota()' name="id_provinsi" class="form-control" id="id_provinsi"  required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_kota">Kota</label>
                 <select name="id_kota" class="form-control" id="id_kota"  required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="kodepos">Kode Pos</label>
                 <input type="number" min="0" maxlength="10" name="kodepos" class="form-control" id="kodepos" placeholder="Kode Pos"  required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="id_pegawai_level">Level</label>
                 <select name="id_pegawai_level" class="form-control" id="id_pegawai_level" required="">
                 </select>
               </div>
             </div>
             <div class="col-sm-12">
              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group" id="showFormPassword">
                    <label>
                      <input type="checkbox" name="" id="show_form_password" value="" data-toggle="collapse" data-target="#formPassword">
                      <span class="label-text">Ubah Password</span>
                    </label>
                  </div>
                </div>                
                <div id="formPassword" class="col-xs-12 collapse in">
                 <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                       <label for="password">Password</label>
                       <input type="password" maxlength="50" name="password" class="form-control" id="password" oninput="checkPasswordConfirm();" placeholder="Password" required="">
                      </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group">
                      <label for="password_confirm">Ulangi Password</label>
                      <input type="password" maxlength="50" name="password_confirm" class="form-control" id="password_confirm" oninput="checkPasswordConfirm();" placeholder="Ulangi Password" required="">
                    </div>
                    <span id="passwordConfirmText" class="text-danger" style="display: none;">Konfirmasi Password tidak sama!</span>
                   </div>
                 </div>
               </div>
              </div>
            </div>
           </div>
        </div>
        <div class="modal-footer">
          <div class="pull-left">
            <a href="javascript:void(0)" id="reset_password" class="btn btn-link" data-id="id" data-toggle="popover" data-placement="top" onclick="confirmReset(this);" data-html="true" title="Reset Password?">Reset Password</a>
          </div>
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
        // "order": [[ 1, 'asc' ]]    
  });
  table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<span style='display:block' class='text-center'>"+(i+1)+"</span>";
        } );
  } ).draw();

  var jsonlist = <?php echo $list; ?>;
  var jsonProv = <?php echo $list_prov; ?>;
  var jsonKota = <?php echo $list_kota; ?>;
  var jsonLevel = <?php echo $list_level; ?>;
 
  var awalLoad = true;
  
  loadData(jsonlist);
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
    var html = "<option value='' selected disabled>Pilih Level Pegawai</option>";
  	// html += "<option value='1'>LEVEL 1</option>";
  	for (var i=0;i<json.length;i++){
  	     html = html+ "<option value='"+json[i].id+"'>"+json[i].nama+"</option>";
  	}
  	$("#id_pegawai_level").html(html);
  }
  
  function get_kota(){
  	if ($("#id_provinsi").val() == "" || $("#id_provinsi").val()==null){
  	   return false;
  	}
  	$("#id_kota").prop("disabled",true);
  	
  	$.ajax({
  	   url :"<?php echo base_url('Master_pegawai/Master/get_kota')?>/",
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
       url :"<?php echo base_url('Master_pegawai/Master/get_kota')?>/",
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
	  for(var i=0;i<json.length;i++){
		  table.row.add( [
            "",
            json[i].nama,
            json[i].alamat,
            json[i].no_telp,
            json[i].email,
            DateFormat.format.date(json[i].date_add, "dd-MM-yyyy HH:mm"),
            '<td class="text-center"><div class="btn-group" >'+
                '<a id="group'+i+'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this);" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'+
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
    $("#reset_password").hide();
    $("#showFormPassword").hide();
    $("#formPassword").collapse("show");

    $("#myModalLabel").text("Tambah Pegawai");
    $("#id").val("");
    $("#nama").val("");
    $("#alamat").val("");
    $("#no_telp").val("");
    $("#email").val("");
    $("#kodepos").val("");
    $("#id_pegawai_level").val("");
    $("#password").val("");
    $("#password_confirm").val("");
    checkPasswordConfirm();
    load_prov(jsonProv);
    load_level(jsonLevel);
    $("#modalform").modal("show");    
  }
  function showUpdate(i){
    load_prov(jsonProv);
    load_kota(jsonKota, jsonlist[i].id_provinsi);
    load_level(jsonLevel);
    $("#showFormPassword").show();
    $("#reset_password").show();
    $("#formPassword").collapse("hide");
    $("#show_form_password").attr("checked", false);

    $("#myModalLabel").text("Ubah Pegawai");
    $("#id").val(jsonlist[i].id);
    $("#nama").val(jsonlist[i].nama);
    $("#alamat").val(jsonlist[i].alamat);
    $("#no_telp").val(jsonlist[i].no_telp);
    $("#email").val(jsonlist[i].email);
    $("#kodepos").val(jsonlist[i].kode_pos);
  	$("#id_provinsi").val(jsonlist[i].id_provinsi);
    $("#id_kota").val(jsonlist[i].id_kota);
  	$("#id_pegawai_level").val(jsonlist[i].id_pegawai_level);
    $("#password").val("");
    $("#password_confirm").val("");
    $("#reset_password").attr("data-id", jsonlist[i].id);
    checkPasswordConfirm();
	  $("#modalform").modal("show");
  }
  
  $("#myform").on('submit', function(e){
    e.preventDefault();
    if(checkPasswordConfirm() == true) {
      var notifText = 'Data berhasil ditambahkan!';
      var action = "<?php echo base_url('Master_pegawai/Master/add')?>/";
      if ($("#id").val() != ""){
        action = "<?php echo base_url('Master_pegawai/Master/edit')?>/";
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
          $("#aSimpan").prop("disabled", true);
          $('#aSimpan').html('Sedang Menyimpan...');
        },
        success: function (data) {
          if (data.status == '1'){ //jika email telah terdaftar dalam db
            $('#aSimpan').html('Simpan');
            $("#aSimpan").prop("disabled", false);
            new PNotify({
                        title: 'Gagal',
                        text: "Email telah terdaftar dalam database!",
                        type: 'danger',
                        hide: true,
                        delay: 5000,
                        styling: 'bootstrap3'
                      });          
          }
          else if (data.status == '3'){ //sukses
            // console.log("ojueojueokl"+data.status);
            jsonlist = data.list;
            loadData(jsonlist);
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
    }
  });
	function deleteData(element){
    var el = $(element).attr("id");
    console.log(el);
    var id  = el.replace("aConfirm","");
    var i = parseInt(id);
    //console.log(jsonlist[i]);
    $.ajax({
          type: 'post',
          url: '<?php echo base_url('Master_pegawai/Master/delete'); ?>/',
          data: {"id":jsonlist[i].id},
          dataType: 'json',
          beforeSend: function() { 
            // kasi loading
            $("#aConfirm"+i).prop("disabled", true);
            $("#aConfirm"+i).html("Sedang Menghapus...");
          },
          success: function (data) {
            if (data.status == '3'){
              $("#aConfirm"+i).prop("disabled", false);
              new PNotify({
                              title: 'Sukses',
                              text: 'Data berhasil dihapus!',
                              type: 'success',
                              hide: true,
                              delay: 5000,
                              styling: 'bootstrap3'
                            });          
              jsonlist = data.list;
              loadData(jsonlist);
            }
          }    
        });
  }
  function resetPassword(element){
		var el = $(element).attr("id");
		var id  = el.replace("aReset","");
		var i = parseInt(id);
		$.ajax({
          type: 'post',
          url: '<?php echo base_url('Master_pegawai/Master/reset_password'); ?>/',
          data: {"id":id},
		      dataType: 'json',
          beforeSend: function() { 
            // kasi loading
            $("#aReset"+i).prop("disabled", true);
            $("#aReset"+i).html("Sedang Mereset...");
          },
          success: function (data) {
            if (data.status == '3'){
              $("#aReset"+i).prop("disabled", false);
              $("#aReset"+i).html("<i class='fa fa-rotate-left'></i> Ya");
              new PNotify({
                              title: 'Sukses',
                              text: 'Password telah diubah menjadi \'admin\'!',
                              type: 'success',
                              hide: true,
                              delay: 5000,
                              styling: 'bootstrap3'
                            });          
              jsonlist = data.list;
              loadData(jsonlist);
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
    // $('[data-toggle="popover"]').popover('hide'); //hide all popover first
    $(el).popover();
  }
  function confirmReset(el){
    var element = $(el).attr("id");
    var i = parseInt($(el).attr("data-id"));
    $(el).attr("data-content","<button class=\'btn btn-danger myconfirm\'  href=\'#\' onclick=\'resetPassword(this)\' id=\'aReset"+i+"\' style=\'min-width:85px\'><i class=\'fa fa-rotate-left\'></i> Ya</button>");
    // $('[data-toggle="popover"]').popover('hide'); //hide all popover first
    $(el).popover();
  }
  
  //Checking (Validating) Password & Password Confirmation
  function checkPasswordConfirm() { 
    var check = true;
    var pass = $("#password").val();
    var passConfirm = $("#password_confirm").val();
    //Check if input is disabled
    if($("#password").prop('disabled') == false) {
      //check if password value is the same with password confirmation
      if(pass != passConfirm) {
        $("#passwordConfirmText").fadeIn();
        check = false;
      } else {
        $("#passwordConfirmText").fadeOut();
        check = true;
      }
    }
    return check;
  };

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

  //Show/Hide checkbox show password
  $("#show_form_password").on("click", function() {
    $("#formPassword").collapse("toggle");
  });
  // Disable/Enable form password inputs on hide/show collapse 
  $("#formPassword").on("shown.bs.collapse", function() {
    $("#formPassword :input").prop("disabled", false);
  });
  $("#formPassword").on("hidden.bs.collapse", function() {
    $("#formPassword :input").prop("disabled", true);
  });
</script>
