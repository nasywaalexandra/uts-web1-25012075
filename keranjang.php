<?php
session_start();
require_once "includes/fungsi.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Pesanan</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <h1>🛒 Keranjang Pesanan</h1>
    <nav>
        <a href="index.php">Daftar Menu</a>
        <a href="keranjang.php">Keranjang 
            <?php echo isset($_SESSION['keranjang']) ? "(".count($_SESSION['keranjang']).")" : "(0)"; ?>
        </a>
    </nav>
</header>

<?php if (isset($_SESSION['pesan'])): ?>
<div class="alert <?php echo $_SESSION['pesan']['tipe']; ?>">
    <?php echo $_SESSION['pesan']['teks']; ?>
</div>
<?php unset($_SESSION['pesan']); endif; ?>

<h2>Isi Keranjang</h2>

<?php if (!isset($_SESSION['keranjang']) || !is_array($_SESSION['keranjang']) || empty($_SESSION['keranjang'])): ?>
    <div class="alert" style="background:#e9ecef; color:#495057;">
        🛒 Keranjang Anda masih kosong. Silakan pilih menu di <a href="index.php" style="font-weight:bold;">Daftar Menu</a>.
    </div>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Menu</th>
            <th>Harga Satuan</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $semua_subtotal = 0;
        foreach ($_SESSION['keranjang'] as $kode => $item):
            // Pastikan $item adalah array sebelum dipakai
            if (!is_array($item) || !isset($item['nama'], $item['harga'], $item['jumlah'])) {
                continue;
            }
            $subtotal = $item['harga'] * $item['jumlah'];
            $semua_subtotal += $subtotal;
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($item['nama']); ?></td>
            <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
            <td><?php echo $item['jumlah']; ?></td>
            <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
            <td>
                <a href="proses.php?hapus=<?php echo urlencode($kode); ?>" 
                   style="color:white; background:#dc3545; padding:5px 10px; border-radius:4px; text-decoration:none;"
                   onclick="return confirm('Hapus item ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="total">
    <p>Subtotal: Rp <?php echo number_format($semua_subtotal, 0, ',', '.'); ?></p>
    
    <?php
    $diskon = 0;
    if ($semua_subtotal >= 50000) {
        $diskon = $semua_subtotal * 0.10;
        echo '<p class="diskon">Diskon 10%: -Rp ' . number_format($diskon, 0, ',', '.') . '</p>';
    }
    $total_akhir = $semua_subtotal - $diskon;
    ?>
    
    <p class="akhir">Total Bayar: Rp <?php echo number_format($total_akhir, 0, ',', '.'); ?></p>
</div>

<div style="margin-top:20px;">
    <a href="proses.php?kosongkan=1" 
       style="background:#dc3545; color:white; padding:10px 15px; border-radius:4px; text-decoration:none; display:inline-block;"
       onclick="return confirm('Kosongkan semua item?')">🧹 Kosongkan Keranjang</a>
</div>
<?php endif; ?>

<footer>
    <p>&copy; <?php echo date("Y"); ?> — Kantin Kampus | UTS Web I</p>
</footer>

</body>
</html>