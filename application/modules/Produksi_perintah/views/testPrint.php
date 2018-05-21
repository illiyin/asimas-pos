<div class="container">
  <div class="col-sm-12 text-right">
    <button class="btn btn-default" title="Cetak Halaman" onclick="window.print();"><i class="fa fa-print"></i> Cetak</button>
  </div>
</div>

<div id="printSection" class="print-container">
  <table class="header">
    <tr>
      <td class="logo"><img src="/assets/img/logo-asimas.png" alt="Logo"></td>
      <td class="kop">
        <h1>PT. AGARICUS SIDO MAKMUR SENTOSA</h1>
        <h3>Dokumen Perintah Produksi</h3>
      </td>
      <td>
        <table class="nested dok-detail">
          <tr>
            <td>No. Dokumen</td>
            <td>: ND</td>
          </tr>
          <tr>
            <td>Revisi</td>
            <td>: 01</td>
          </tr>
          <tr>
            <td>Tanggal Efektif</td>
            <td>: JAN 2018</td>
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
            <td>: NPP</td>
          </tr>
          <tr>
            <td>No. Sales Order</td>
            <td>: NSO</td>
          </tr>
          <tr>
            <td>Estimasi Proses</td>
            <td>: 3306 HARI</td>
          </tr>
        </table>
      </td>
      <td class="bagi-dua">
        <table class="nested">
          <tr>
            <td>Nama Produk</td>
            <td>: NAMA PRODUK</td>
          </tr>
          <tr>
            <td>Besar Batch</td>
            <td>: 5000 GRAM</td>
          </tr>
          <tr>
            <td>Kode Produksi</td>
            <td>: KP</td>
          </tr>
          <tr>
            <td>Expire Date</td>
            <td>: EXPIRED DATE</td>
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
        <th>Bentuk Persediaan</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Per Batch</th>
        <th>Satuan</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

  <h3 class="tb-title">Penimbangan Aktual:</h3>
  <table class="regular penimbanganAktual">
    <thead>
      <tr>
        <th class="nomer nope">No.</th>
        <th class="nope">Nama Bahan</th>
        <th class="nope">Jumlah</th>
        <th class="nope">Satuan</th>
        <th class="nope">Per Lot</th>
        <!-- <th class="nope">Total Lot</th> -->
        <th class="lot" colspan="<?php echo @$max_lot?>">Lot</th>
      </tr>
    </thead>
    <tbody>
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
    </tbody>
  </table>

  <table class="tanda-tangan">
    <tr>
      <td class="w-30">
        <table class="nested panel">
          <tr>
            <th>Diterbitkan</th>
          </tr>
          <tr>
            <td class="ttd-field">

            </td>
          </tr>
          <tr>
            <td class="name-field">
              &nbsp;
            </td>
          </tr>
          <tr>
            <td class="jabatan-field">
              PPIC
            </td>
          </tr>
        </table>
      </td>
      <td class="w-30">&nbsp;</td>
      <td class="w-40">
        <table class="nested panel">
          <tr>
            <th colspan="2">Diterima</th>
          </tr>
          <tr>
            <td class="ttd-field bagi-dua">

            </td>
            <td class="ttd-field bagi-dua">

            </td>
          </tr>
          <tr>
            <td class="name-field">
              &nbsp;
            </td>
            <td class="name-field">
              &nbsp;
            </td>
          </tr>
          <tr>
            <td class="jabatan-field">
              PPIC
            </td>
            <td class="jabatan-field">
              Kabag Produksi
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <h3 class="tb-title">
    Rekonsiliasi (syarat 95% - 105%):
  </h3>
  <table class="kotak">
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>

  <h3 class="tb-title">
    Justifikasi (bila rekonsiliasi di luar rentang):
  </h3>
  <table class="kotak">
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>

  <table class="nested panel">
    <tr>
      <th colspan="3">Mengetahui</th>
    </tr>
    <tr>
      <td class="ttd-field bagi-tiga">

      </td>
      <td class="ttd-field bagi-tiga">

      </td>
      <td class="ttd-field bagi-tiga">

      </td>
    </tr>
    <tr>
      <td class="name-field">
        &nbsp;
      </td>
      <td class="name-field">
        &nbsp;
      </td>
      <td class="name-field">
        &nbsp;
      </td>
    </tr>
    <tr>
      <td class="jabatan-field">
        Kabag Produksi
      </td>
      <td class="jabatan-field">
        PPIC
      </td>
      <td class="jabatan-field">
        Accounting
      </td>
    </tr>
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

<script>
  $('.navbar.navbar-default').hide();
  $(document).ready(function() {
    // window.print();
  });
</script>