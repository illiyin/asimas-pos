<script>
Highcharts.setOptions({
        global: {
            useUTC: false,
        },
        lang: {
          decimalPoint: ',',
          thousandsSep: '.'
        }
    });

var jsonGrafik = <?php echo $data_grafik;?>;
Highcharts.chart('chart1_container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Jumlah Penjualan Per Hari'
    },
    subtitle: {
        text: "<?php echo date('F Y');?>"
    },
    xAxis: {
        categories: jsonGrafik.data_per,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Jumlah Transaksi Penjualan'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:,.0f} kali</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Jumlah Penjualan',
        data: jsonGrafik.jumlah_penjualan
    }]
  });

var jsonGrafik = <?php echo $data_grafik;?>;
Highcharts.chart('chart2_container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Total Penjualan Per Hari'
    },
    subtitle: {
        text: "<?php echo date('F Y');?>"
    },
    xAxis: {
        categories: jsonGrafik.data_per,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Transaksi Penjualan (IDR)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>Rp {point.y:,.0f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Total Penjualan',
        data: jsonGrafik.total_penjualan
    }]
  });    
</script>