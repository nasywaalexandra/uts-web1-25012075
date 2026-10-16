<?php
// Hitung total belanja, diskon, dan total akhir
function hitungTotal($keranjang) {
    $subtotal = 0;
    foreach ($keranjang as $item) {
        $subtotal += $item['harga'] * $item['jumlah'];
    }
    
    $diskon = 0;
    if ($subtotal >= 50000) {
        $diskon = $subtotal * 0.10; // Diskon 10%
    }
    
    $total_akhir = $subtotal - $diskon;
    
    return [
        'subtotal' => $subtotal,
        'diskon' => $diskon,
        'total' => $total_akhir
    ];
}
?>