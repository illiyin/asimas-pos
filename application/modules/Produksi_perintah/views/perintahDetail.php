<div class="print-container">
  <table class="header">
    <tr>
      <td class="logo"><img src="<?php echo base_url(); ?>/assets/img/logo-asimas.png" alt="Logo"></td>
      <td class="kop">
        <h1>PT. AGARICUS SIDO MAKMUR SENTOSA</h1>
        <h3>Dokumen Perintah Produksi</h3>
      </td>
      <td>
        <table class="nested dok-detail">
          <tr>
            <td>No. Dokumen</td>
            <td>: <?= $perintah_produksi->no_dokumen ?></td>
          </tr>
          <tr>
            <td>Revisi</td>
            <td>: <?= $perintah_produksi->revisi ?></td>
          </tr>
          <tr>
            <td>Tanggal Efektif</td>
            <td>: <?= date('d/m/Y', strtotime($perintah_produksi->tanggal_efektif)) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table class="detail">
    <tr>
      <td class="bagi-dua">
        <table class="nested">
          <tr>
            <td>No. Perintah Produksi</td>
            <td>: <?= $perintah_produksi->no_perintah ?></td>
          </tr>
          <tr>
            <td>No. Sales Order</td>
            <td>: <?= $perintah_produksi->no_sales_order ?></td>
          </tr>
          <tr>
            <td>Estimasi Proses</td>
            <td>: <?= $perintah_produksi->estimasi_proses ?> hari</td>
          </tr>
        </table>
      </td>
      <td class="bagi-dua">
        <table class="nested">
          <tr>
            <td>Nama Produk</td>
            <td>: <?= $perintah_produksi->nama_produk ?></td>
          </tr>
          <tr>
            <td>Besar Batch</td>
            <td>: <?= $perintah_produksi->besar_batch ?></td>
          </tr>
          <tr>
            <td>Kode Produksi</td>
            <td>: <?= $perintah_produksi->kode_produksi ?></td>
          </tr>
          <tr>
            <td>Expire Date</td>
            <td>: <?= date('d/m/Y', strtotime($perintah_produksi->expired_date)); ?> </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <h3 class="tb-title">Bahan Baku:</h3>
  <table class="regular">
    <thead>
      <tr>
        <th class="nomer">No.</th>
        <th>Nama Bahan</th>
        <th>Per Kaplet</th>
        <th>Satuan</th>
        <th>Per Batch</th>
        <th>Satuan</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; foreach($bahan_baku as $row): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_bahan'] ?></td>
        <td><?= $row['per_kaplet'] ?></td>
        <td><?= $row['satuan_kaplet'] ?></td>
        <td><?= $row['per_batch'] ?></td>
        <td><?= $row['satuan_batch'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h3 class="tb-title">Penimbangan Aktual:</h3>
  <table class="regular">
    <thead>
      <tr>
        <th class="nomer">No.</th>
        <th>Nama Bahan</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Per Lot</th>
        <th>Total Lot</th>
        <th class="lot">Lot 1</th>
        <th class="lot">Lot 2</th>
        <th class="lot">Lot 3</th>
        <th class="lot">Lot 4</th>
        <th class="lot">Lot 5</th>
        <th class="lot">Lot 6</th>
        <th class="lot">Lot 7</th>
        <th class="lot">Lot 8</th>
        <th class="lot">Lot 9</th>
        <th class="lot">Lot 10</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; foreach($bahan_baku as $row): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_bahan'] ?></td>
        <td><?= $row['per_batch'] ?></td>
        <td><?= $row['satuan_batch'] ?></td>
        <td><?= $row['jumlah_perlot'] ?></td>
        <td><?= $row['jumlah_lot'] ?></td>
        <?php if($row['jumlah_lot'] > 0 ):
          for($i = 0; $i < $row['jumlah_lot']; $i++): ?>
        <td></td>
        <?php endfor; else:?>
        <td colspan="10"></td>
      <?php endif; ?>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h3 class="tb-title">Bahan Kemas:</h3>
  <table class="regular">
    <thead>
      <tr>
        <th class="nomer">No.</th>
        <th>Nama Bahan</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Aktual</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; foreach($bahan_kemas as $row): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_bahan'] ?></td>
        <td><?= $row['jumlah'] ?></td>
        <td><?= $row['satuan'] ?></td>
        <td><?= $row['aktual'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="panel panel-default">
    <div class="panel-body text-right">
      <a href="<?= base_url() ?>index/modul/Produksi_perintah-master-index" class="btn btn-default">Kembali</a>
      <?php if($session_detail->id == 5 || strpos(strtolower($session_detail->name), 'ppic') === true): ?>
      <button id="setujui<?= base64_url_decode($this->uri->segment(4)) ?>" class="btn btn-success" data-toggle="popover" data-placement="top" onclick="confirmApprove(this)" data-html="true" title="Setujui dokumen ini?" <?= $perintah_produksi->status == 1 ? 'disabled' : null;?>>Setujui</button>
      <?php endif; ?>
    </div>
  </div>

</div> <!-- tutup container -->

<style>
.print-container{
  margin: 10px auto;
  color: #000;
  width: 210mm;
}
.header{
  margin-bottom: 60px;
}
.logo{
  /* text-align: right; */
  width: 70px;
}
.logo img{
  width: 60px;
  /* margin-right: 10px; */
}
.kop h1{
  margin-bottom: 0;
  font-size: 24px;
}
.kop h3{
  margin-top: 0;
  font-size: 18px;
}
table{
  width: 100%;
}
.dok-detail{
  border: 1px solid #000;
  margin: 10px 0 0;
}
.dok-detail tr td{
  padding: 7px 13px;
}
.bagi-dua{
  width: 50%;
}
.detail tr td{
  vertical-align: top;
}
.detail tr td:first-child{
  padding-right: 20px;
}
.detail tr td:last-child{
  padding-left: 20px;
}
.detail .nested tr td{
  padding: 16px 0 13px;
  border-bottom: 1px solid #000;
}
.tb-title{
  font-weight: bold;
  font-size: 16px;
}
.regular{
  margin-bottom: 50px;
}
.regular tr td,
.regular tr th{
  border: 1px solid #000;
  padding: 15px 10px;
  text-align: center;
}
.nomer{
  width: 60px;
}
.regular th{
  font-weight: bold;
  background-color: #9E9E9E;
  color: #fff;
}
.lot{
  width: 30px;
}
.w-20{
  width: 20%;
}
.w-30{
  width: 30%;
}
.w-40{
  width: 40%;
}
.w-50{
  width: 50%;
}
table.panel{
  border: 1px solid #000;
}
table.panel tr td{
  padding: 10px;
  border: 1px solid #000;
  text-align: center;
  /* border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  border-bottom-left-radius: 5px;
  border-bottom-right-radius: 5px; */
}
table.panel th{
  font-weight: bold;
  padding: 10px;
  text-align: center;
  background-color: #EEEEEE;
}
.ttd-field{
  height: 120px;
}
table.panel .ttd-field{
  border-top: none;
}
.kotak{
  margin-bottom: 50px;
  border: 1px solid #000;
}
.kotak tr td{
  height: 150px;
}
.bagi-tiga{
  width: 33.33%;
}
</style>
<script type="text/javascript">
function confirmApprove(el){
  var element = $(el).attr("id");
  var id  = element.replace("setujui","");
  var i = parseInt(id);
  $(el).attr("data-content","<button class=\'btn btn-success btn-block myconfirm\'  href=\'#\' onclick=\'approveData(this)\' id=\'aConfirm"+i+"\' style=\'min-width:85px\'><i class=\'fa fa-check-circle\'></i> Ya</button>");
  $(el).popover("show");
}

function approveData(element) {
  var el = $(element).attr("id");
  var id  = el.replace("aConfirm","");
  var i = parseInt(id);
  $.ajax({
    type: 'post',
    url: '<?php echo base_url('Produksi_perintah/Master/approve'); ?>/',
    data: {"id":i},
    dataType: 'json',
    beforeSend: function() {
      // kasi loading
      $("#aConfirm"+i).html("Sedang Menghapus...");
      $("#aConfirm"+i).prop("disabled", true);
    },
    success: function (data) {
      console.log(data);
      if(data.status == true){
        new PNotify({
          title: 'Sukses',
          text: data.message,
          type: 'success',
          hide: true,
          delay: 5000,
          styling: 'bootstrap3'
        });
        $("#setujui<?= base64_url_decode($this->uri->segment(4)) ?>").prop('disabled', true);
      } else {
        var $data = data.status.list_bahan;
        var $message = '';
        for (var i = 0; i < $data.length; i++) {
          var row = $data[i];
          var type = row.type == 'bahan_baku' ? "Bahan Baku" : "Bahan Kemas";
          $message += "<p>Bahan <strong>" + row.nama_bahan + "</strong> Stok kurang dari <strong>"+row.stok_kurang+"</strong> ("+type+")</p>";
        }
        swal({
          title: "Perhatian!",
          text: $message,
          icon: "warning",
          dangerMode: true,
          html: true
        });
      }
      $("#aConfirm"+i).html("OK");
      $("#aConfirm"+i).prop('disabled', false);
    }
  });
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
