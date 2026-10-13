
#PRAKTIKUM

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reistore - Program Transaksi Sederhana</title>
    <style>
        :root {
            --primary: #ef4444;       /* Warna Merah Utama */
            --primary-dark: #dc2626;  /* Warna Merah Gelap */
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-color: #1e293b;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        h2 {
            color: var(--primary-dark);
            text-align: center;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 15px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px 12px;
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        .btn-submit {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            transition: background-color 0.2s;
        }
        .btn-submit:hover {
            background-color: var(--primary-dark);
        }
        .result-box {
            margin-top: 35px;
            border-top: 2px dashed #cbd5e1;
            padding-top: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
            text-align: left;
        }
        th {
            background-color: #f8fafc;
            font-weight: 600;
        }
        .table-rincian th {
            background-color: var(--primary-dark);
            color: white;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background-color: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>REISTORE</h2>
    <div class="subtitle">Program Transaksi Sederhana & Pembayaran Kasir</div>

    <form action="" method="POST">
        <div class="form-group">
            <label>Nama Pembeli:</label>
            <input type="text" name="nama_pembeli" required placeholder="Contoh: Budi Santoso">
        </div>

        <div class="form-group">
            <label>Nama Barang:</label>
            <input type="text" name="nama_barang" required placeholder="Contoh: Kemeja Flanel">
        </div>

        <div class="form-group">
            <label>Harga Satuan (Rp):</label>
            <input type="number" name="harga_satuan" min="1" required placeholder="Contoh: 150000">
        </div>

        <div class="form-group">
            <label>Jumlah Beli (Qty):</label>
            <input type="number" name="jumlah_beli" min="1" value="1" required>
        </div>

        <div class="form-group">
            <label>Metode Pembayaran:</label>
            <select name="metode_pembayaran">
                <option value="Tunai">Tunai (Cash)</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="QRIS / E-Wallet">QRIS / E-Wallet (GoPay, OVO, Dana)</option>
                <option value="Kartu Kredit">Kartu Kredit / Debit</option>
            </select>
        </div>

        <button type="submit" name="proses_transaksi" class="btn-submit">Proses Transaksi</button>
    </form>

    <?php
    if (isset($_POST['proses_transaksi'])) {
        // 1. Variabel dengan minimal 4 tipe data berbeda
        $nama_pembeli      = (string) $_POST['nama_pembeli'];      // Tipe Data: String
        $nama_barang       = (string) $_POST['nama_barang'];       // Tipe Data: String
        $harga_satuan      = (float) $_POST['harga_satuan'];       // Tipe Data: Float
        $jumlah_beli       = (int) $_POST['jumlah_beli'];          // Tipe Data: Integer
        $metode_pembayaran = (string) $_POST['metode_pembayaran']; // Tipe Data: String
        $status_transaksi  = (bool) true;                          // Tipe Data: Boolean

        // Menghitung subtotal transaksi kotor
        $subtotal = $harga_satuan * $jumlah_beli;

        // 2. Fungsi buatan sendiri untuk memproses status / admin fee berdasarkan metode pembayaran
        function prosesPembayaran($total, $metode) {
            $biaya_admin = 0;

            // Percabangan (Branching) untuk menentukan biaya admin atau ketentuan khusus
            if ($metode == "Kartu Kredit") {
                $biaya_admin = 5000; // Tambahan biaya admin kartu kredit
                $keterangan = "Pembayaran via Kartu Kredit (Dikenakan Biaya Layanan)";
            } elseif ($metode == "Transfer Bank") {
                $keterangan = "Pembayaran via Transfer Bank (Menunggu Konfirmasi Otomatis)";
            } elseif ($metode == "QRIS / E-Wallet") {
                $keterangan = "Pembayaran via QRIS / E-Wallet (Bebas Biaya Admin)";
            } else {
                $keterangan = "Pembayaran Tunai Langsung di Kasir Toko";
            }

            return [
                'ket' => $keterangan,
                'admin' => $biaya_admin
            ];
        }

        $hasil_bayar = prosesPembayaran($subtotal, $metode_pembayaran);
        $total_akhir = $subtotal + $hasil_bayar['admin'];
        ?>

        <div class="result-box">
            <h3>Struk Bukti Transaksi - Reistore</h3>
            <p><strong>Nama Pembeli:</strong> <?php echo htmlspecialchars($nama_pembeli); ?></p>
            <p><strong>Metode:</strong> <span class="badge"><?php echo htmlspecialchars($metode_pembayaran); ?></span></p>
            <p><strong>Catatan:</strong> <?php echo $hasil_bayar['ket']; ?></p>

            <table class="table-rincian">
                <tr>
                    <th>Rincian Transaksi</th>
                    <th>Jumlah (IDR)</th>
                </tr>
                <tr>
                    <td>Barang: <?php echo htmlspecialchars($nama_barang); ?> (<?php echo $jumlah_beli; ?>x @ Rp <?php echo number_format($harga_satuan, 0, ',', '.'); ?>)</td>
                    <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                </tr>
                <?php if ($hasil_bayar['admin'] > 0): ?>
                <tr>
                    <td>Biaya Layanan / Admin</td>
                    <td>Rp <?php echo number_format($hasil_bayar['admin'], 0, ',', '.'); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td><strong>Total Pembayaran Akhir</strong></td>
                    <td><strong>Rp <?php echo number_format($total_akhir, 0, ',', '.'); ?></strong></td>
                </tr>
            </table>

            <h4 style="margin-top: 20px;">Rincian Satuan Item (Simulasi Perulangan)</h4>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                </tr>
                <?php
                // 3. Perulangan (Looping) untuk mencetak rincian item satuan belanja
                for ($i = 1; $i <= $jumlah_beli; $i++) {
                    echo "<tr>";
                    echo "<td>$i</td>";
                    echo "<td>" . htmlspecialchars($nama_barang) . "</td>";
                    echo "<td>Rp " . number_format($harga_satuan, 0, ',', '.') . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    <?php } ?>
</div>

</body>
</html>