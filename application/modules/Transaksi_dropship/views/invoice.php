<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset='UTF-8'>
	
	<title>Invoices</title>
	
	<link rel='stylesheet' href='<?php echo base_url('assets/css/invoice.css') ?>'>
</head>
<script type="text/javascript">
	function onload(){
		window.print();
	}
</script>
<body onload="onload()">
	<div id="page-wrap">

		<textarea id="header">INVOICE</textarea>
		
		<div id="identity">
		
            <textarea id="address"><?php echo $data->row()->namacus; ?>&nbsp;
<?php echo $data->row()->alamatcus; ?>

Phone: <?php echo $data->row()->notelpcus; ?></textarea>

            <div id="logo">

              <div id="logohelp">
                <input id="imageloc" type="text" size="50" value="" /><br />
                (max width: 540px, max height: 100px)
              </div>
              <img id="image" src="images/logo.png" alt="logo" />
            </div>
		
		</div>
		
		<div style="clear:both"></div>
		
		<div id="customer">

            <textarea id="customer-title">Nama Toko Anda</textarea>

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
                    <td class="meta-head">Total</td>
                    <td><div class="due">Rp. <?php echo number_format($data->row()->ordertotal); ?></div></td>
                </tr>

            </table>
		
		</div>
		
		<table id="items">
		
		  <tr>
		      <th>Item</th>
		      <th>Description</th>
		      <th>Unit Cost</th>
		      <th>Quantity</th>
		      <th>Price</th>
		  </tr>
		  <?php foreach ($data->result_array() as $row) { ?>
			  <tr class="item-row">
			      <td class="item-name"><textarea><?php echo $row['namaprod']; ?></textarea></td>
			      <td class="description"><textarea><?php echo $row['deskprod']; ?></textarea></td>
			      <td><textarea class="cost">Rp. <?php echo number_format($row['detailjual']); ?></textarea></td>
			      <td><textarea class="qty"><?php echo $row['jumlahjual']; ?></textarea></td>
			      <td><span class="price"><?php echo number_format($row['totaljual']); ?></span></td>
			  </tr>
		  <?php } ?>
		  		  
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Subtotal</td>
		      <td class="total-value"><div id="subtotal">Rp. <?php echo number_format($data->row()->ordertotal); ?></div></td>
		  </tr>
		  <tr>

		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Total</td>
		      <td class="total-value"><div id="total">Rp. <?php echo number_format($data->row()->ordertotal); ?></div></td>
		  </tr>		
		</table>
		
		<div id="terms">
		  <h5>Terms</h5>
		  <textarea>Terima Kasih Telah Berbelanja</textarea>
		</div>
	
	</div>
	
</body>

</html>