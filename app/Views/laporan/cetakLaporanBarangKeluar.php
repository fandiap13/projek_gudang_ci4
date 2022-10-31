<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Barang Masuk</title>
</head>

<body onload="window.print();">

  <!-- <body> -->
  <table style="width: 100%; border-collapse: collapse; text-align:center" border="1">
    <tr>
      <td>
        <table style="width: 100%;" border="0">
          <tr style="text-align: center;">
            <td>
              <h1>Toko Fandi</h1>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table style="width: 100%; text-align: center;" border="0">
          <tr style="text-align: center;">
            <td>
              <h3><u>Laporan Barang Keluar</u></h3>
              <br>
              Periode : <?= date('d-m-Y', strtotime($tglawal)) . " s/d " . date('d-m-Y', strtotime($tglakhir)); ?>
            </td>
          </tr>
          <tr>
            <td>
              <br>
              <center>
                <table border="1" style="border-collapse: collapse; border: 1px solid #000; text-align: center; width: 80%;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>No.Faktur</th>
                      <th>Tanggal</th>
                      <th>Pelanggan</th>
                      <th>Detail Barang</th>
                      <th>Jumlah Uang</th>
                      <th>Sisa Uang</th>
                      <th>Total Harga</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $nomor = 1;
                    $totalSeluruhHarga = 0;
                    foreach ($datalaporan->getResultArray() as $row) :
                      $totalSeluruhHarga += $row['totalbayar'];
                    ?>
                      <tr>
                        <td><?= $nomor++; ?></td>
                        <td><?= $row['faktur']; ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tglfaktur'])); ?></td>
                        <td>
                          <?php if (intval($row['idpel']) != 0) {
                            $db = \Config\Database::connect();
                            $dataPelanggan = $db->table('pelanggan')->getWhere([
                              'pelid' => $row['idpel']
                            ])->getRowArray();
                            echo $dataPelanggan['pelnama'];
                          }
                          ?>
                        </td>
                        <td style="text-align: left;">
                          <?php
                          $db = \Config\Database::connect();
                          $detailBarang = $db->table('detail_barangkeluar')->join('barang', 'brgkode=detbrgkode')->join('satuan', 'satid=brgsatid')->getWhere([
                            'detfaktur' => $row['faktur']
                          ]);
                          foreach ($detailBarang->getResultArray() as $d) :
                          ?>
                            <?= $d['brgnama']; ?>
                            <ul>
                              <li>Jml : <?= $d['detjml']; ?> <?= $d['satnama']; ?></li>
                              <li>Harga jual : <?= number_format($d['dethargajual'], 0, ",", "."); ?></li>
                              <li>Subtotal : <?= number_format($d['detsubtotal'], 0, ",", "."); ?></li>
                            </ul>
                          <?php endforeach; ?>
                        </td>
                        <td style="text-align: right;">Rp. <?= number_format($row['jumlahuang'], 0, ",", "."); ?></td>
                        <td style="text-align: right;">Rp. <?= number_format($row['sisauang'], 0, ",", "."); ?></td>
                        <td style="text-align: right;">Rp. <?= number_format($row['totalbayar'], 0, ",", "."); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="7">Total Seluruh Harga</th>
                      <td style="text-align: right;">Rp. <?= number_format($totalSeluruhHarga, 0, ",", "."); ?></td>
                    </tr>
                  </tfoot>
                </table>
              </center>
              <br>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>