<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Master</strong> - Hak Akses</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMain" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center">Nama Hak Akses</th>
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
     Tambah Hak Akses
   </button>
</div>
<!-- /.container -->

<!-- Modal Add -->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Hak Akses</h4>
      </div>
      <form action="" method="POST" id="myform">      
        <div class="modal-body">
           <div class="row">
             <div class="col-md-6 col-sm-12">
                <div class="form-group">
                 <label for="nama">Nama Hak Akses</label>
                 <input type="text" name="nama" maxlength="50" Required class="form-control" id="nama" placeholder="Nama Hak Akses">
                 <input type="hidden" name="id" maxlength="50" Required class="form-control" id="id" placeholder="ID Hak Akses">
               </div>
             </div>
             <div class="col-sm-12">
                <label for="permission">Permission</label>
               <div class="row">
                  <?php $permissions = json_decode($list_permission); ?>
                  <?php $menus = json_decode($list_menu); ?>
                  <?php $i = 0;
                    foreach ($menus as $menu) { ?>
                        <div class="col-sm-3">
                         <div id="bs-permissions">
                            <div class="panel-heading bg-primary">
                              <label>
                                <input type="checkbox" class="check-all" data-menu="<?php echo $menu->id?>" onchange="checkAllMenu(this);">
                                <span class="label-text">
                                  <?php echo ucfirst(str_replace("_", " ",$menu->nama))." ";?>
                                  <span class="label label-info"><?php echo $menu->jumlah_sub?></span>
                                </span>
                              </label>
                            </div>
                           <!-- <h5 class="panel-heading bg-primary"><?php echo ucfirst(str_replace("_", " ",$menu->nama));?> </h5> -->
                           <div class="panel-body">
                          <?php foreach ($permissions as $permission) { ?>
                            <?php if($menu->id == $permission->id_menu) { ?>
                            <div class="form-group">
                              <!-- <input type="text" name="<?php echo 'menu_'.$permission->id?>" value="0"  /> -->
                              <label>
                                <input type="checkbox" name="menu[]" id="<?php echo 'menu_'.$permission->id?>" class="menu_<?php echo $permission->id_menu?>" value="<?php echo $permission->id?>">
                                <span class="label-text"><?php echo $permission->nama?></span>
                              </label>
                            </div>
                            <?php } ?>
                          <?php } ?>
                           </div>
                         </div>
                        </div>
                  <?php 
                    $i++; 
                    if($i == 4) { ?>
                      <div class="clearfix"></div>
                    <?php $i = 0;}
                  } ?>

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

  var jsonlist = <?php echo $list; ?>;
  var awalLoad = true;
  
  loadData(jsonlist);

  function loadData(json){
	  //clear table
    table.clear().draw();
	  for(var i=0;i<json.length;i++){
      table.row.add( [
            "",
            json[i].nama,
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
    $("#myModalLabel").text("Tambah Hak Akses");
    $("#id").val("");
    $("#nama").val("");
    $("#myform :checkbox:enabled").prop("checked", false);
    $("#modalform").modal("show");    
  }
  
  function showUpdate(i){
    $("#myModalLabel").text("Ubah Hak Akses");
    $("#id").val(jsonlist[i].id);
    $("#nama").val(jsonlist[i].nama);
    
    // $(".check-all").prop("checked", false);
    // $("input[class^='menu_']").prop("checked", false);
    $("#myform :checkbox:enabled").prop("checked", false);
    var permissions = JSON.parse(jsonlist[i].permission);
    $.each(permissions, function(index, value) {
      // console.log("#menu_"+value);
      $("#menu_"+value).prop("checked", true);
    });
	  
    $("#modalform").modal("show");
  }
  
  $("#myform").on('submit', function(e){
    e.preventDefault();
    var notifText = 'Data berhasil ditambahkan!';
    var action = "<?php echo base_url('Master_hak_akses/Master/add')?>/";
    if ($("#id").val() != ""){
      action = "<?php echo base_url('Master_hak_akses/Master/edit')?>/";
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
  				console.log("ojueojueokl"+data.status);
  				jsonlist = data.list;
  				loadData(jsonlist);
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
      }
    });
  });
	
	function deleteData(element){
		var el = $(element).attr("id");
		console.log(el);
		var id  = el.replace("aConfirm","");
		var i = parseInt(id);
		//console.log(jsonlist[i]);
		$.ajax({
          type: 'post',
          url: '<?php echo base_url('Master_hak_akses/Master/delete'); ?>/',
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
      				jsonlist = data.list;
      				loadData(jsonlist);
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
    $(el).popover();
  }

  //check all menu checkboxes on kategori checkbox click
  function checkAllMenu(el) {
    var kategori = $(el).data("menu");
    $("#bs-permissions .menu_"+kategori).prop("checked", $(el).prop('checked'));
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
