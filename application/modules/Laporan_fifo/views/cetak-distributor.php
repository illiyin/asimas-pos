<div id="printSection" class="print-container">
  <table class="header">
    <tr>
      <td class="logo"><img src="<?php echo base_url(); ?>/assets/img/logo-asimas.png" alt="Logo"></td>
      <td class="kop">
        <h1>PT. AGARICUS SIDO MAKMUR SENTOSA</h1>
        <h3>Laporan fifo distributor bulan Pebruari</h3>
      </td>
    </tr>
  </table>


  <table class="regular">
    <thead>
      <tr>
        <th class="nomer">No.</th>
        <th>Nama Distributor</th>
        <th>Alamat</th>
        <th>No. Telepon</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if(empty($data_list)) { ?>
        <tr><td colspan="5">Tidak ada data</td></tr>
      <?php 
      }
      else {
        foreach ($data_list as $key => $row) { ?>
        <tr>
          <td><?php echo ($key+1)?></td>
          <td><?php echo $row->nama?></td>
          <td><?php echo $row->alamat?></td>
          <td><?php echo $row->no_telp?></td>
          <td><?php echo $row->email?></td>
        </tr>
      <?php }
      } ?>
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
