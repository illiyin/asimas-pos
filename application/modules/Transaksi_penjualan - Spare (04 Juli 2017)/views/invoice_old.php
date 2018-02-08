<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset='UTF-8'>
	<title>Invoices</title>
	<link rel='stylesheet' href='<?php echo base_url('assets/css/invoice.css') ?>'>
</head>

<script type="text/javascript">
	function onload(){
		// window.print();
	}
</script>
<body onload="onload()">
	<div id="page-wrap">
		<textarea id="header">INVOICE</textarea>
		<h3 style="text-align: center;">Iqbal POS</h3>
		<div id="identity">
            <div id="logo">
              <div id="logohelp">
                <input id="imageloc" type="text" size="50" value="" /><br />
                (max width: 540px, max height: 100px)
              </div>
              <!-- <img id="image" src="images/logo.png" alt="logo" /> -->
            </div>
		</div>
		
		<div style="clear:both"></div>
		
		<div id="customer">
			<div style="float:left;">
	            <table id="meta">
	                <tr>
	                    <td class="meta-head">Nama Customer</td>
	                    <td><textarea><?php echo $data->row()->namacus;?></textarea></td>
	                </tr>
	                <tr>
	                    <td class="meta-head">Alamat</td>
	                    <td><textarea id="date"><?php echo $data->row()->alamatcus; ?></textarea></td>
	                </tr>
	                <tr>
	                    <td class="meta-head">Telepon</td>
	                    <td><div class="due"><?php echo $data->row()->notelpcus;?></div></td>
	                </tr>
	            </table>
			</div>
            <table id="meta">
                <tr>
                    <td class="meta-head">Invoice #</td>
                    <td><textarea><?php echo $data->row()->orderinvoice; ?></textarea></td>
                </tr>
                <tr>
                    <td class="meta-head">Tanggal</td>
                    <td><textarea id="date"><?php echo $data->row()->orderdate; ?></textarea></td>
                </tr>
                <tr>
                    <td class="meta-head">Total Harga</td>
                    <td><div class="due">Rp <?php echo number_format($data->row()->ordertotal); ?></div></td>
                </tr>
            </table>
		</div>
		
		<div style="clear:both"></div>
		<table id="items">
		  <tr>
		      <th>#</th>
		      <th>Nama Produk</th>
		      <th>Warna</th>
		      <th>Ukuran</th>
		      <th>Quantity</th>
		      <th>Subtotal</th>
		  </tr>
		  <?php $i = 1;
		  	foreach ($data->result_array() as $row) { ?>
			  <tr class="item-row">
			      <td style='text-align: center;'><?php echo $i++; ?></td>
			      <td class="item-name"><textarea><?php echo $row['namaprod']; ?></textarea></td>
			      <td class="description"><textarea><?php echo $row['nama_warna']; ?></textarea></td>
			      <td class="description"><textarea><?php echo $row['nama_ukuran']; ?></textarea></td>
			      <td class="qty" style='text-align: center;'><?php echo $row['jumlahjual']; ?></td>
			      <td class="item-name">Rp <span style="float:right;"><?php echo number_format($row['totaljual']); ?></span></td>
			      <!-- <td><span class="price"><?php echo number_format($row['totaljual']); ?></span></td> -->
			  </tr>
		  <?php } ?>
		  		  
		  <tr style='font-weight: bold'>
		      <td colspan="3" class="blank"> </td>
		      <td colspan="2" class="total-line">Total Harga</td>
		      <td class="total-value"><div id="subtotal">Rp <span style="float:right;"><?php echo number_format($data->row()->ordertotal); ?></span></div></td>
		  </tr>
		  <tr style='font-weight: bold'>
		      <td colspan="3" class="blank"> </td>
		      <td colspan="2" class="total-line">Cash</td>
		      <td class="total-value"><div id="total">Rp <span style="float:right;"><?php echo number_format($data->row()->ordercash); ?></span></div></td>
		  </tr>		
		  <tr style='font-weight: bold'>
		      <td colspan="3" class="blank"> </td>
		      <td colspan="2" class="total-line">Kembali</td>
		      <td class="total-value"><div id="total">Rp <span style="float:right;"><?php echo number_format($data->row()->order_uang_kembali); ?></span></div></td>
		  </tr>		
		</table>
		
		<div id="terms">
		  <h5>Terms</h5>
		  <textarea>Terima Kasih Telah Berbelanja</textarea>
		</div>
	
	</div>
</body>

</html>