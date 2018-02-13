<!-- Page Content -->
<div class="container">
<div class="row" style='min-height:80px;'>
  <div id='notif-top' style="margin-top:50px;display:none;" class="col-md-4 alert alert-success pull-right">
    <strong>Sukses!</strong> Data berhasil disimpan
  </div>
</div>
  <div class="row">
    <h3><strong>Laporan</strong> - Top Member</h3>
  </div>
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab_tabel" data-toggle="tab"><i class="fa fa-table" aria-hidden="true"></i> Tabel</a></li>
    <li class=""><a href="#tab_grafik" data-toggle="tab"><i class="fa fa-bar-chart" aria-hidden="true"></i> Grafik</a></li>
  </ul>
   <div class="tab-content">
    
    <!-- TAB TABEL -->
    <div class="tab-pane fade in active" id="tab_tabel">
       <div class="row">
          <form id="formTabel" method="post">
            <div class="col-sm-8">
              <label class="label-control">Range Tanggal</label>
              <div class="input-group input-daterange">
                <input type="text" id="start_date" class="form-control datepicker" placeholder="YYYY/MM/DD">
                <div class="input-group-addon">Sampai</div>
                <input type="text" id="end_date" class="form-control datepicker" placeholder="YYYY/MM/DD">
                <span class="input-group-btn">
                  <button id="fReset" class="btn btn-default" type="button" disabled=""><i class="fa fa-undo"></i> Reset</button>
                  <button id="fSubmit" class="btn btn-default" type="button"><i class="fa fa-filter"></i> Tampilkan</button>
                </span>
              </div>
            </div>
          </form>
       </div>
       <hr>

       <div class="row" style="margin-top:10px;">
          <table id="TableMainServer" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                  <tr>
                    <th class="text-center no-sort">#</th>
                    <th class="text-center">Nama Customer</th>
                    <th class="text-center">Jumlah Order</th>
                    <th class="text-center">Total Nilai Order (IDR)</th>
                    <!-- <th class="text-center no-sort">Aksi</th> -->
                  </tr>
              </thead>

              <tbody id='bodytable'>
                
              </tbody>
          </table>
       </div>
    </div>

    <!-- TAB GRAFIK -->
    <div class="tab-pane fade in" id="tab_grafik">
      <div class="row">
          <form id="formGrafik" method="post">
            <div class="col-sm-8">
              <label class="label-control">Range Tanggal</label>
              <div class="input-group input-daterange">
                <input type="text" id="start_date2" class="form-control datepicker" placeholder="YYYY/MM/DD">
                <div class="input-group-addon">Sampai</div>
                <input type="text" id="end_date2" class="form-control datepicker" placeholder="YYYY/MM/DD">
                <span class="input-group-btn">
                  <!-- <button id="fReset2" class="btn btn-default" type="button" disabled=""><i class="fa fa-undo"></i> Reset</button> -->
                  <button id="fSubmit2" class="btn btn-default" type="button"><i class="fa fa-filter"></i> Tampilkan</button>
                </span>
              </div>
            </div>
          </form>
          <!-- <form id="formGrafik" method="post">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="filter_grafik" class="label-control">Filter</label>
              <div class="input-group">
                <select name="filter_grafik" id="filter_grafik" class="form-control" required="">
                  <option value="hari">Per Hari</option>
                  <option value="bulan">Per Bulan</option>
                  <option value="tahun">Per Tahun</option>
                </select>
                <div class="input-group-btn">
                  <button id="gSubmit" class="btn btn-default"><i class="fa fa-filter"></i> Tampilkan</button>
                </div>
              </div>
            </div>
          </div>
          </form> -->
       </div>
       <hr>

       <div class="row">
          <div class="col-sm-6">
            <div id="chart1_container" class="form-group"></div>
          </div>
          <div class="col-sm-6">
            <div id="chart2_container" class="form-group"></div>
          </div>
       </div>
    </div>

   </div>
</div>
<!-- /.container -->


<script type="text/javascript">
  $(document).ready(function() {
    //set data awal grafik
    var jsonGrafik = <?php echo $data_grafik;?>;
    
    //initialize input money masking
    maskInputMoney();
    $('.datepicker').datepicker({
      todayBtn: 'linked',
      todayHighlight: true,
      autoclose: true,
      format: 'yyyy/mm/dd',
      endDate: '+0d'
    });
    $('#fSubmit').click(function(e){
      e.preventDefault();
      $("#fReset").attr("disabled", false);
      initDataTable.destroy();
      initDataTable = $('#TableMainServer').DataTable({
        "bProcessing": true,
        "bServerSide": true,
        "order": [[3, 'DESC']],
        "ajax":{
              url :"<?php echo base_url()?>Laporan_top_member/Master/data",
              type: "post",  // type of method  , by default would be get
              "data": {
                  start_date: $("#start_date").val(),
                  end_date: $("#end_date").val()
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
        "order": [[3, 'DESC']],
        "ajax":{
              url :"<?php echo base_url()?>Laporan_top_member/Master/data",
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
      "order": [[3, 'DESC']],
      "ajax":{
            url :"<?php echo base_url()?>Laporan_top_member/Master/data",
            type: "post",  // type of method  , by default would be get
            "data": {
                start_date: $("#start_date").val(),
                end_date: $("#end_date").val()
            },
            error: function(){ /*error handling code*/ }
          },
      "columnDefs": [ {
        "targets"  : 'no-sort',
        "orderable": false,
      }]
    });
</script>

<?php include "chart.php"; ?>

<script type="text/javascript">
  var chart1 = $('#chart1_container').highcharts();
  var chart2 = $('#chart2_container').highcharts();
  //Fixing bootstrap tabs highchart width problem
  $(".nav-tabs a").on("shown.bs.tab", function() {
    chart1.reflow(); chart2.reflow(); // console.log("REFLOW DONG");
  });
  function set_grafik_range() {
    chart1.title.update({text: 'Top Customer Per Hari (Banyaknya Order)'});
    chart1.subtitle.update({text: $("#start_date2").val() +' - '+ $("#end_date2").val()});
    chart2.title.update({text: 'Top Customer Per Hari (Total Nilai Order)'});
    chart2.subtitle.update({text: $("#start_date2").val() +' - '+ $("#end_date2").val()});
  }
  /*function set_grafik_hari() {
    chart1.title.update({text: 'Top Customer Per Hari (Banyaknya Order)'});
    chart1.subtitle.update({text: "<?php echo date('F Y');?>"});
    chart2.title.update({text: 'Top Customer Per Hari (Total Nilai Order)'});
    chart2.subtitle.update({text: "<?php echo date('F Y');?>"});
  }
  function set_grafik_bulan() { 
    chart1.title.update({text: 'Top Customer Per Bulan (Banyaknya Order)'});
    chart1.subtitle.update({text: "<?php echo 'Tahun '. date('Y');?>"});
    chart2.title.update({text: 'Top Customer Per Bulan (Total Nilai Order)'});
    chart2.subtitle.update({text: "<?php echo 'Tahun '. date('Y');?>"});
  }
  function set_grafik_tahun() { 
    chart1.title.update({text: 'Top Customer Per Tahun (Banyaknya Order)'});
    chart1.subtitle.update({text: "<?php echo (date('Y')-5) .' s/d '.date('Y') ;?>"});
    chart2.title.update({text: 'Top Customer Per Tahun (Total Nilai Order)'});
    chart2.subtitle.update({text: "<?php echo (date('Y')-5) .' s/d '.date('Y') ;?>"});
  }*/

  $("#fSubmit2").click(function(e) {
    e.preventDefault();
    var action = "<?php echo base_url('Laporan_top_member/Master/chart_data')?>/";
    $.ajax({
      url: action,
      type: 'post',
      data: { start_date: $("#start_date2").val(), end_date: $("#end_date2").val() },
      dataType: 'json',
      beforeSend: function() { 
        // tambahkan loading
        $("#fSubmit2").prop("disabled", true);
        $('#fSubmit2').html('Sedang Mencari...');
      },
      success: function (response) {
          $('#fSubmit2').html('<i class="fa fa-filter"></i> Tampilkan');
          $("#fSubmit2").prop("disabled", false);

          //Injecting new array data into charts
          var chart1 = $('#chart1_container').highcharts();
          var chart2 = $('#chart2_container').highcharts();
          chart1.xAxis[0].setCategories(response.data_per);
          chart1.series[0].setData(response.jumlah_order);
          chart2.xAxis[0].setCategories(response.data_per);
          chart2.series[0].setData(response.total_order);
          set_grafik_range();
          //reconfigure charts title & subtitle
          /*switch($("#filter_grafik").val()) {
            case 'hari':
              set_grafik_hari();
              break;
            case 'bulan':
              set_grafik_bulan();
              break;
            case 'tahun':
              set_grafik_tahun();
              break;
            default:
              set_grafik_hari();
              break;
          }*/
      }
    });
  })
</script>
