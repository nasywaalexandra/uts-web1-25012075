<?php
session_start();
require_once "data/menu.php";

if (isset($_POST['tambah'])) {
    $kode = isset($_POST['kode']) ? trim($_POST['kode']) : '';
    $jumlah = isset($_POST['jumlah']) ? trim($_POST['jumlah']) : '';

    // 1. Jumlah tidak boleh kosong
    if ($jumlah === '') {
        $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => '❌ Jumlah tidak boleh kosong!'];
        header("Location: index.php");
        exit;
    }

    // 2. Harus angka
    if (!is_numeric($jumlah)) {
        $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => '❌ Jumlah harus berupa angka!'];
        header("Location: index.php");
        exit;
    }

    $jumlah = (int)$jumlah;

    // 3. Harus lebih dari 0
    if ($jumlah <= 0) {
        $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => '❌ Jumlah harus lebih dari 0!'];
        header("Location: index.php");
        exit;
    }

    // 4. Maksimal 10
    if ($jumlah > 10) {
        $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => '⚠️ Maksimal pesanan 10 item per menu!'];
        header("Location: index.php");
        exit;
    }

    // 5. Kode terdaftar?
    if (!isset($daftar_menu[$kode])) {
        $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => '❌ Kode menu tidak terdaftar!'];
        header("Location: index.php");
        exit;
    }

    // === PROSES TAMBAH ===
    $menu = $daftar_menu[$kode];
    
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    if (isset($_SESSION['keranjang'][$kode])) {
        $jumlah_baru = $_SESSION['keranjang'][$kode]['jumlah'] + $jumlah;
        if ($jumlah_baru > 10) {
            $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => '⚠️ Total pesanan tidak boleh lebih dari 10!'];
            header("Location: index.php");
            exit;
        }
        $_SESSION['keranjang'][$kode]['jumlah'] = $jumlah_baru;
    } else {
        $_SESSION['keranjang'][$kode] = [
            'kode' => $menu['kode'],
            'nama' => $menu['nama'],
            'harga' => $menu['harga'],
            'jumlah' => $jumlah
        ];
    }

    $_SESSION['pesan'] = ['tipe' => 'sukses', 'teks' => '✅ Ditambahkan ke keranjang!'];
    header("Location: keranjang.php");
    exit;
}

// Hapus item
if (isset($_GET['hapus'])) {
    $kode = $_GET['hapus'];
    if (isset($_SESSION['keranjang'][$kode])) {
        $nama = $_SESSION['keranjang'][$kode]['nama'];
        unset($_SESSION['keranjang'][$kode]);
        $_SESSION['pesan'] = ['tipe' => 'sukses', 'teks' => "🗑️ '$nama' telah dihapus dari keranjang."];
    }
    header("Location: keranjang.php");
    exit;
}

// Kosongkan keranjang
if (isset($_GET['kosongkan'])) {
    unset($_SESSION['keranjang']);
    $_SESSION['pesan'] = ['tipe' => 'sukses', 'teks' => '🧹 Keranjang telah dikosongkan.'];
    header("Location: keranjang.php");
    exit;
}

header("Location: index.php");
exit;
?>