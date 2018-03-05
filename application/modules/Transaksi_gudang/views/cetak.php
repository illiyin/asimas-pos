<div class="container">
  <div class="col-sm-12 text-right">
    <button class="btn btn-default" title="Cetak Halaman" onclick="window.print();"><i class="fa fa-print"></i> Cetak</button>
  </div>
</div>
<div id="printSection" class="print-container">
  <table class="header">
    <tr>
      <td class="logo"><img src="<?php echo base_url(); ?>/assets/img/logo-asimas.png" alt="Logo"></td>
      <td class="kop">
        <h1>PT. AGARICUS SIDO MAKMUR SENTOSA</h1>
        <h3>Laporan transaksi gudang: 12/03/2019</h3>
      </td>
    </tr>
  </table>


  <table class="regular">
    <thead>
      <tr>
        <th class="nomer">No.</th>
        <th class="text-center">Nama Bahan</th>
        <th class="text-center">Kode Bahan</th>
        <th class="text-center">Satuan</th>
        <th class="text-center">Jumlah</th>
        <th class="text-center">Stok Awal</th>
        <th class="text-center">Keluar</th>
        <th class="text-center">Masuk</th>
        <th class="text-center">Stok Akhir</th>
        <th class="text-center">Batch</th>
        <th class="text-center">Expire Date</th>
        <th class="text-center">Keterangan</th>
        <th class="text-center">Harga</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Brotowali</td>
        <td>XX-3s</td>
        <td>g</td>
        <td>300</td>
        <td>23</td>
        <td>420</td>
        <td>50</td>
        <td>50</td>
        <td>50</td>
        <td>12/12/2019</td>
        <td>Sangat Terang</td>
        <td>300.000</td>
      </tr>
    </tbody>
  </table>


</div> <!-- tutup container -->

<style>
.print-container{
  margin: 10px auto;
  color: #000;
  width: 210mm;
}
.header{
  margin-bottom: 60px;
  border-bottom:2px solid #000;
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

.regular{
  margin-bottom: 50px;
}
.regular tr td,
.regular tr th{
  border: 1px solid #000;
  padding: 15px 10px;
}
.nomer{
  width: 60px;
}
.regular th{
  font-weight: bold;
  background-color: #9E9E9E;
  color: #fff;
  text-align: center;
}

.text-left {
  text-align: left;
}
.text-right {
  text-align: right;
}
.text-center {
  text-align: center;
}
</style>

<style>
@page {
  /* width = 21cm (potrait)*/
  /* width = 29.7cm (landscape) */
  size: A4 landscape;
  margin: 0 1.5cm 1cm;

}



@media print {

  body {
    font: 12pt Georgia, "Times New Roman", Times, serif;
    line-height: 1.5;
    padding-top: 0px;
  }
  table.header {
    margin-bottom: 30px;
  }
  table.regular td{
    padding: 10px!important;
    /* line-height: 1.5; */
  }

  table{
    width: 26.7cm;
  }
}
</style>

<script>
  $('.navbar.navbar-default').hide();
  $(document).ready(function() {
    window.print();
  });
</script>
