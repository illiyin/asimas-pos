<div class="print-container">
  <table class="header">
    <tr>
      <td class="logo"><img src="<?php echo base_url(); ?>/assets/img/logo-asimas.png" alt="Logo"></td>
      <td class="kop">
        <h1>PT. AGARICUS SIDO MAKMUR SENTOSA</h1>
        <h3>Laporan fifo barang bulan Pebruari</h3>
      </td>
    </tr>
  </table>


  <table class="regular">
    <thead>
      <tr>
        <th class="nomer">No.</th>
        <th>Nama Barang</th>
        <th>No. Batch</th>
        <th>Stok Akhir</th>
        <th>Tanggal Expired</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Jamur ABM</td>
        <td>002</td>
        <td>22</td>
        <td>30/12/1988</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Jamur ABM</td>
        <td>002</td>
        <td>22</td>
        <td>30/12/1988</td>
      </tr>
      <tr>
        <td>3</td>
        <td>Jamur ABM</td>
        <td>002</td>
        <td>22</td>
        <td>30/12/1988</td>
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

</style>
