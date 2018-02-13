<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset='UTF-8'>
	<title>Invoices</title>
	<link rel='stylesheet' href='<?php echo base_url('assets/css/invoice.css') ?>'>
	<link rel='stylesheet' href='<?php echo base_url('assets/css/bootstrap.min.css') ?>'>
</head>
<style type="text/css">
	hr.stripe {
		border-top: 1px dashed;
		margin-top: 5px;
		margin-bottom: 5px;
	}
	table.table-borderless tr th, 
	table.table-borderless tr td {
		border: none;
		padding: 3px;
	}
	table td, table th {

	}
	table#items {
		font-size: 90%;
	}
	table#items th {
		text-align: center;
		border: 1px solid #222;
	}
	table#items td.item-name {
		width: auto;
	}
	table#items tr.item-summary td{
		border-top: 1px solid #222;
	}
	.form-group{
		margin-bottom: 5px;
	}
</style>

<body>
	<div id="page-wrap">
		<div class="row">
			<div class="col-sm-12">
				<div class="header">
					<p style="font-weight: bold;">IQBAL STORE
					<br>Jl. alamat
					<br>Layanan Pelanggan Telepon 091248184 / Whatsapp 0918241241</p>
				</div>
			</div>
		</div>

		<hr class="stripe">
		<h5 class="text-center"><b>FAKTUR RETUR</b></h5>

		<div class="inline-tables" style="float: left">
			<table class="table-borderless">
				<tr>
					<th>Pelanggan</th>
					<td><?php echo strtoupper($data->row()->namacus);?></td>
				</tr>
				<tr>
					<th>Alamat</th>
					<td><?php echo strtoupper($data->row()->alamatcus);?></td>
				</tr>
				<tr>
					<th>Telepon</th>
					<td><?php echo strtoupper($data->row()->notelpcus);?></td>
				</tr>
			</table>
		</div>

		<div class="inline-tables" style="float: right;">
			<table class="table-borderless">
				<tr>
					<th style="text-align: right">No.</th>
					<td><?php echo $data->row()->orderinvoice;?></td>
				</tr>
				<tr>
					<th style="text-align: right">Tanggal</th>
					<td><?php echo date("d-m-Y H:i:s", strtotime($data->row()->orderdate));?></td>
				</tr>
			</table>
		</div>

		<div style="clear:both"></div>
		
		<table id="items">
		  <tr>
		      <th>No. Kode</th>
		      <th>Produk</th>
		      <th>Ukuran</th>
		      <th>Warna</th>
		      <th>Harga</th>
		      <th>Jumlah</th>
		      <th>Pot. &#37;</th>
		      <th>Pot. Rp</th>
		      <th>Subtotal</th>
		  </tr>
		  <?php 
		  $pot_persen = 0;
		  $total_harga_normal = 0;
		  foreach ($data->result_array() as $row) { 
		  	$pot_persen = ($row['potongan']/$row['detailjualnormal']) * 100;
		  	// $pot_persen = (16863/134900) * 100;
		  	$total_harga_normal += $row['detailjualnormal'];
		  	?>
			  <tr class="item-row">
			      <td class><?php echo !empty($row['kodeprod']) ? $row['kodeprod'] : '-';?></td>
			      <td class><?php echo $row['namaprod'];?></td>
			      <td class>
			      	<?php echo !empty($row['nama_ukuran']) ? $row['nama_ukuran'] : 'Tidak ada';?>
		      	</td>
			      <td class>
			      	<?php echo !empty($row['nama_warna']) ? $row['nama_warna'] : 'Tidak ada';?>
		      	</td>
			      <td class="text-right"><?php echo number_format($row['detailjualnormal']);?></td>
			      <td class="text-center"><?php echo $row['jumlahjual'];?></td>
			      <td class="text-center"><?php echo number_format($pot_persen, 1);?>&#37;</td>
			      <td class="text-right"><?php echo number_format($row['potongan']);?></td>
			      <td class="text-right"><span class="price"><?php echo number_format($row['totaljual']);?></span></td>
			  </tr>
		  <?php } ?>
		  		  
		  <tr class="item-summary">
	      <td colspan="4" class="blank text-left">Terima kasih sudah berbelanja</td>
	      <td colspan="3" class="total-line">Total</td>
	      <td colspan="2" class="total-value text-right"><?php echo number_format($total_harga_normal);?></td>
		  </tr>
		  <tr>
	      <td colspan="4" class="blank"> </td>
	      <td colspan="3" class="total-line">Total Pot.</td>
	      <td colspan="2" class="total-value text-right"><?php echo number_format($data->row()->totalpotongan);?></td>
		  </tr>		
		  <tr>
	      <td colspan="4" class="blank"> </td>
	      <td colspan="3" class="total-line">Penjualan Bersih</td>
	      <td colspan="2" class="total-value text-right"><?php echo number_format($data->row()->ordertotal);?></td>
		  </tr>		
		</table>
		
	</div>
</body>
</html>