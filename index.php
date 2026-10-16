<?php
session_start();
require_once "data/menu.php";

// Simpan preferensi kategori
if (isset($_POST['simpan_preferensi'])) {
    $_SESSION['kategori_favorit'] = $_POST['kategori_favorit'];
}
$terpilih = isset($_SESSION['kategori_favorit']) ? $_SESSION['kategori_favorit'] : '';

// Pencarian & Filter
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$filter_kategori = isset($_GET['filter_kategori']) ? $_GET['filter_kategori'] : '';

// Filter daftar menu
$hasil_menu = array();
foreach ($daftar_menu as $menu) {
    $cocok = true;
    if ($cari !== '' && stripos($menu['nama'], $cari) === false) {
        $cocok = false;
    }
    if ($filter_kategori !== '' && $menu['kategori'] !== $filter_kategori) {
        $cocok = false;
    }
    if ($cocok) {
        $hasil_menu[] = $menu;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu — Kantin Kampus</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <h1>🍽️ Kantin Kampus</h1>
    <nav>
        <a href="index.php">Daftar Menu</a>
        <a href="keranjang.php">Keranjang 
            <?php echo isset($_SESSION['keranjang']) ? "(".count($_SESSION['keranjang']).")" : "(0)"; ?>
        </a>
    </nav>
</header>

<!-- Pesan Notifikasi -->
<?php if (isset($_SESSION['pesan'])): ?>
<div class="alert <?php echo $_SESSION['pesan']['tipe']; ?>">
    <?php echo $_SESSION['pesan']['teks']; ?>
</div>
<?php unset($_SESSION['pesan']); endif; ?>

<!-- Kotak Pencarian & Filter -->
<div class="box-cari">
    <form method="get" action="index.php">
        <strong>Cari Menu:</strong>
        <input type="text" name="cari" placeholder="Contoh: nasi goreng" 
               value="<?php echo htmlspecialchars($cari); ?>">
        
        <strong>Filter Kategori:</strong>
        <select name="filter_kategori">
            <option value="">Semua Kategori</option>
            <option value="Makanan" <?php echo $filter_kategori==='Makanan'?'selected':''; ?>>Makanan</option>
            <option value="Minuman" <?php echo $filter_kategori==='Minuman'?'selected':''; ?>>Minuman</option>
            <option value="Camilan" <?php echo $filter_kategori==='Camilan'?'selected':''; ?>>Camilan</option>
        </select>
        <button type="submit">Cari / Filter</button>
    </form>
</div>

<!-- Simpan Preferensi Kategori -->
<div class="box-cari">
    <form method="post" action="index.php">
        <strong>Kategori Favorit:</strong>
        <select name="kategori_favorit">
            <option value="">Pilih Kategori</option>
            <option value="Makanan" <?php echo $terpilih==='Makanan'?'selected':''; ?>>Makanan</option>
            <option value="Minuman" <?php echo $terpilih==='Minuman'?'selected':''; ?>>Minuman</option>
            <option value="Camilan" <?php echo $terpilih==='Camilan'?'selected':''; ?>>Camilan</option>
        </select>
        <button type="submit" name="simpan_preferensi">Simpan Preferensi</button>
    </form>
</div>

<!-- Tabel Menu -->
<h2>Daftar Menu</h2>
<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Menu</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Pesan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($hasil_menu as $menu): ?>
        <tr>
            <td><?php echo htmlspecialchars($menu['kode']); ?></td>
            <td><?php echo htmlspecialchars($menu['nama']); ?></td>
            <td><?php echo htmlspecialchars($menu['kategori']); ?></td>
            <td>Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></td>
            <td>
                <form action="proses.php" method="post">
                    <input type="hidden" name="kode" value="<?php echo htmlspecialchars($menu['kode']); ?>">
                    <input type="text" name="jumlah" value="1" style="width: 50px; text-align: center;">
                    <button type="submit" name="tambah">Tambah</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if (empty($hasil_menu)): ?>
        <tr>
            <td colspan="5" style="text-align:center; padding:20px;">Menu tidak ditemukan.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<footer>
    <p>&copy; <?php echo date("Y"); ?> — Kantin Kampus | UTS Web I</p>
</footer>

</body>
</html>