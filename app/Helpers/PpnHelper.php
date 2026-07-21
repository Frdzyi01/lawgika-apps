<?php

namespace App\Helpers;

class PpnHelper
{
    /** PPN rate: 11% */
    const RATE = 0.11;

    /**
     * Hitung PPN dari subtotal (harga dasar).
     *
     * @param  float|int  $subtotal  Harga dasar paket (tanpa PPN)
     * @return array{subtotal: int, ppn_rate: int, ppn_amount: int, grand_total: int}
     */
    public static function calculate(float|int $subtotal): array
    {
        $subtotal = (int) $subtotal;
        $ppn      = (int) round($subtotal * self::RATE);

        return [
            'subtotal'    => $subtotal,
            'ppn_rate'    => 11,
            'ppn_amount'  => $ppn,
            'grand_total' => $subtotal + $ppn,
        ];
    }

    /**
     * Format angka ke Rupiah.
     */
    public static function formatRupiah(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
