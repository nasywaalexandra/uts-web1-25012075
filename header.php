<?php if (!isset($judulHalaman)) $judulHalaman = 'Kantin Kampus'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($judulHalaman) ?> - Kantin Kampus</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
    <h1>🍽️ Kantin Kampus</h1>
    <nav><a href="index.php">Daftar Menu</a><a href="keranjang.php">Keranjang (<?= array_sum($_SESSION['keranjang'] ?? []) ?>)</a></nav>
</header>
<main class="container">
<?php $flashTampil = ambilFlash(); if ($flashTampil): ?>
    <div class="alert <?= e($flashTampil['jenis']) ?>"><?= e($flashTampil['pesan']) ?></div>
<?php endif; ?>
