<!-- <link rel="stylesheet" href="<?= base_url() . '/plugins/chart.js/Chart.min.css' ?>">
<script src=" <?= base_url() . '/plugins/chart.js/Chart.bundle.min.js' ?>">
</script> -->

<!-- <script src="<?= base_url(); ?>/plugins/chart.js/Chart.min.js"></script> -->

<canvas id="myChart" style="height: 50vh; width: 80vh;"></canvas>
<?php

$tanggal = "";
$total = "";

foreach ($grafik as $row) :
  $tgl = $row->tgl;
  $tanggal .= "'$tgl'" . ",";

  $totalHarga = $row->totalbayar;
  $total .= "'$totalHarga'" . ",";;
endforeach;

?>

<script>
  var ctx = document.getElementById('myChart').getContext('2d');
  var chart = new Chart(ctx, {
    type: 'bar', // tipe grafik
    responsive: true,
    data: {
      labels: [<?= $tanggal; ?>], // label mengambil nilai dari tanggal
      datasets: [{ // mengasih label tulisan atau mengubah warna batang
        label: 'Total Harga',
        backgroundColor: ['rgb(255,99,132)', 'rgb(14, 99, 132)', 'rgb(14, 99, 13)'], // terdapat dua warna pada batang
        borderColor: ['rgb(255,991,130)'],
        data: [<?= $total; ?>]
      }]
    },
    duration: 1000
  });
</script>