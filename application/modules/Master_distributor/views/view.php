<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Master</strong> - Distributor</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center">Nama Distributor</th>
                  <th class="text-center">Alamat</th>
                  <th class="text-center">No. Telp</th>
                  <th class="text-center">Email</th>
                  <!-- <th class="text-center">NPWP</th>
                  <th class="text-center">Bank</th>
                  <th class="text-center">Tanggal Buat</th> -->
                  <th class="text-center no-sort">Aksi</th>
              </tr>
          </thead>

          <tbody id='bodytable'>

          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <button type="button" class="btn btn-add btn-lg"  onclick="showAdd()">
     Tambah Distributor
   </button>
</div>
<!-- /.container -->

<!-- Modal Add -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Distributor</h4>
      </div>
      <form action="" method="POST" id="myform">
        <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
                 <label for="nama">Nama Distributor</label>
                 <input type="text" name="nama" maxlength="50" Required class="form-control" id="nama" placeholder="Nama Distributor">
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Distributor">
               </div>
             </div>
             <div class="col-sm-12">
               <div class="form-group">
                 <label for="alamat">Alamat Distributor</label>
                 <input type="text" name="alamat" maxlength="30" class="form-control" id="alamat" placeholder="Alamat Distributor" required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="no_telp">No Telp Distributor</label>
                 <input type="number" min="0" maxlength="50" name="no_telp" class="form-control" id="no_telp" placeholder="No Telp Distributor" required="">
               </div>
             </div>
             <div class="col-sm-6">
               <div class="form-group">
                 <label for="email">Email Distributor</label>
                 <input type="email" maxlength="50" name="email" class="form-control" id="email" placeholder="Email Distributor" required="">
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
        // "order": [[ 1, 'asc' ]]
  });
  table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<span style='display:block' class='text-center'>"+(i+1)+"</span>";
        } );
  } ).draw();

  var jsonList = <?php echo $list; ?>;

  var awalLoad = true;

  loadData(jsonList);

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
            // json[i].npwp,
            // json[i].nama_bank,
            // DateFormat.format.date(json[i].date_add, "dd-MM-yyyy HH:mm"),
            '<td class="text-center"><div class="btn-group" >'+
                '<a id="group'+json[i].id+'" class="divpopover btn btn-sm btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="top" onclick="confirmDelete(this)" data-html="true" title="Hapus Data?" ><i class="fa fa-times"></i></a>'+
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
    $("#myModalLabel").text("Tambah Distributor");
    $("#id").val("");
    $("#nama").val("");
    $("#alamat").val("");
    $("#no_telp").val("");
    $("#email").val("");
    $("#npwp").val("");
    $("#nama_bank").val("");
    $("#no_rekening").val("");
    $("#rekening_an").val("");
    $("#keterangan").val("");
    $("#modalform").modal("show");
  }

  function showUpdate(i){
    $("#myModalLabel").text("Ubah Distributor");
    $("#id").val(jsonList[i].id);
    $("#nama").val(jsonList[i].nama);
    $("#alamat").val(jsonList[i].alamat);
    $("#no_telp").val(jsonList[i].no_telp);
    $("#email").val(jsonList[i].email);
    $("#modalform").modal("show");
  }

  $("#myform").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Master_distributor/Master/add')?>/";
    if ($("#id").val() != ""){
      action = "<?php echo base_url('Master_distributor/Master/edit')?>/";
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
        if (data.status == '3'){
          jsonList = data.list;
          loadData(jsonList);
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
        console.log(data);
        $('#aSimpan').html('Simpan');
        $("#aSimpan").prop("disabled", false);
      }
    });
  });

	function deleteData(element){
		var el = $(element).attr("id");
		var id  = el.replace("aConfirm","");
		var i = parseInt(id);
		$.ajax({
          type: 'post',
          url: '<?php echo base_url('Master_distributor/Master/delete'); ?>/',
          data: {"id":i},
		      dataType: 'json',
          beforeSend: function() {
            // kasi loading
            $("#aConfirm"+i).html("Sedang Menghapus...");
            $("#aConfirm"+i).prop("disabled", true);
          },
          success: function (data) {
            console.log(data);
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
