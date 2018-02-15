<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Stok</strong> - Pesanan</h3>
  </div>
   <div class="row" style="margin-top:10px;">
      <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="text-center no-sort">#</th>
                  <th class="text-center">ID Order</th>
                  <th class="text-center">Nama Customer</th>
                  <th class="text-center">Grand Total (IDR)</th>
                  <th class="text-center">Metode Bayar</th>
                  <th class="text-center">Catatan</th>
                  <th class="text-center" class="hidden-xs">Tanggal Order</th>
                  <th class="text-center no-sort">Status</th>
                  <th class="text-center no-sort">Aksi</th>
              </tr>
          </thead>

          <tbody id='bodytable'>
            
          </tbody>
      </table>
   </div>
</div>
<!-- /.container -->
<!-- Modal Detail Order -->
<div class="modal fade" id="Viewproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document" id="viewModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="view">Detail Order</h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div class="row" style="margin-top:10px;">
            <div class="col-sm-12">
              <table id="TableModal" class="table table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                      <tr>
                          <th class="text-center no-sort">#</th>
                          <th class="text-center">Nama Produk</th>
                          <th class="text-center">Jumlah</th>
                          <th class="text-center">Total Berat (gr)</th>
                          <th class="text-center">Total Harga (IDR)</th>
                          <th class="text-center">Warna</th>
                          <th class="text-center">Ukuran</th>
                      </tr>
                  </thead>
                  <tbody id='bodytable'> </tbody>
              </table>
            </div>
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal">Close</button>
        </div>
      </div>
   </div>
</div>
<!-- /.Modal -->


<script type="text/javascript">
  $(document).ready(function() {
    //initialize input money masking
    maskInputMoney();
    $("#foto").fileinput({ 'showUpload': false });
  });
  function maskInputMoney(){
    $('.money').mask('#.##0', {reverse: true});
  }
  function unmaskInputMoney(){
    $('.money').unmask();
  }

  var jsonlist = <?php echo $list; ?>;
  var jsonOrder = <?php echo $list_order; ?>;
  console.log(jsonOrder);
  
  var awalLoad = true;
  var initDataTable = $('#TableMainServer').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "order": [[6, 'DESC']],
      "ajax":{
            url :"<?php echo base_url()?>Stok_pesanan/Master/data",
            type: "post",  // type of method  , by default would be get
            error: function(){  // error handling code
              // $("#employee_grid_processing").css("display","none");
            }
          },
      "drawCallback": function( settings ) {
        //Init bootstrap toggle on datatable redraw
        $('.bootstrap-toggle').bootstrapToggle({
          size: 'small',
          off: '<i class="fa fa-calendar-check-o"></i> Booked',
          on: '<i class="fa fa-check-square-o"></i> Selesai',
          offstyle: 'default',
          onstyle: 'success'
        });
        $('.bootstrap-toggle').change(function(e) { 
          if($(e.currentTarget).is(':checked')) {
            confirmStatus(e);
          }
        });
      },
      "columnDefs": [ {
        "targets"  : 'no-sort',
        "orderable": false,
      }]
    });

  // initialize datatable in modal ------------
  var tableModal = $("#TableModal").DataTable({
    "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": "no-sort"
        } ],
        // "order": [[ 1, 'asc' ]]    
  });
  tableModal.on( 'order.dt search.dt', function () {
        tableModal.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<span style='display:block' class='text-center'>"+(i+1)+"</span>";
        } );
  } ).draw();

  function loadData(json){
    //clear tableModal
    tableModal.clear().draw();
    for(var i=0;i<json.length;i++){
      tableModal.row.add( [
            "",
            json[i].nama,
            "<span class='text-center' style='display:block;'>"+json[i].jumlah+"</span>",
            "<span class='money text-center' style='display:block;'>"+json[i].total_berat+"</span>",
            "<span class='money pull-right'>"+json[i].total_harga+"</span>",
            json[i].nama_warna,
            json[i].nama_ukuran,
        ] ).draw( false );
    }
    awalLoad = false;  
  }
  // end datatable in modal ------------
 
  function showDetail(i){
    //Filtering data in objects by id_order
    var dataDetail = jsonOrder.filter(function (index) { return index.id_order == i }); 
    console.log("filteredData: ");
    console.log(dataDetail);
    //push filtered data into datatable
    loadData(dataDetail); 
    //reinitialize money format changer
    unmaskInputMoney(); maskInputMoney();
    //show the modal
    $("#Viewproduct").modal("show");
  }

  function confirmStatus(e){
    e.preventDefault();
    var i = $(e.currentTarget).prop("id");
    //data ukuran & warna diambil dari tabel yang berbeda
    var dataDetail = jsonlist.filter(function (index) { return index.id == i }); 
    //confirmation setting:
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
            updateStatus(id); //Run AJAX here
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
  
  function updateStatus(id){
    var action = "<?php echo base_url('Stok_pesanan/Master/edit')?>/";
    var notifText = 'Data berhasil diubah!';
    var id_order = parseInt(id);
    $.ajax({
      url: action,
      type: 'post',
      data: { 'id': id_order },
      dataType: 'json',
      beforeSend: function() { 
      },
      success: function (data) {
        if (data.status == '3'){
          jsonOrder = JSON.parse(data.list_order);
          console.log(jsonOrder);
          initDataTable.ajax.reload(); //reload datatable
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
  };
	
  function showThumbnail(el){
    var img_src = $(el).find("img").attr("src");
    $(el).attr("data-content","<img src='"+img_src+"' class=\'img-responsive\'  href=\'#\' style=\'max-width:350px\'>");
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
