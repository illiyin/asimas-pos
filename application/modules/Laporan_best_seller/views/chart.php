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
        text: 'Best Seller Per Hari (Banyaknya Produk)'
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
            text: 'Jumlah Produk'
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
        name: 'Jumlah Produk',
        data: jsonGrafik.jumlah_produk
    }]
  });

var jsonGrafik = <?php echo $data_grafik;?>;
Highcharts.chart('chart2_container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Best Seller Per Hari (Total Nilai Produk)'
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
            text: 'Total Nilai Produk (IDR)'
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
        name: 'Total Nilai Produk',
        data: jsonGrafik.total_produk
    }]
  });    
</script>