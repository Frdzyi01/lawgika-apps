<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrOrderController extends Controller
{
    /**
     * Tampilkan halaman publik detail layanan dari QR code order.
     */
    public function show(string $token)
    {
        $order = Order::with(['user', 'service', 'roomBenefits', 'documents'])
            ->where('qr_token', $token)
            ->firstOrFail();

        // Dynamically parse form_data for generic display
        $formData = $order->form_data ?? [];
        $displayFields = [];

        if (is_array($formData)) {
            $hiddenKeys = ['_token', 'password', 'service', 'terms', 'payment_method'];
            foreach ($formData as $key => $value) {
                if (in_array($key, $hiddenKeys)) {
                    continue;
                }
                if (is_array($value)) {
                    $valStr = json_encode($value, JSON_UNESCAPED_UNICODE);
                } elseif (is_bool($value)) {
                    $valStr = $value ? 'Ya' : 'Tidak';
                } else {
                    $valStr = (string)$value;
                }

                if (trim($valStr) !== '') {
                    $label = ucwords(str_replace(['_', '-'], ' ', $key));
                    $displayFields[$label] = $valStr;
                }
            }
        }

        return view('qr.show', compact('order', 'displayFields'));
    }

    /**
     * Halaman portal verifikasi QR Lawgika (/qr)
     */
    public function index()
    {
        return view('qr.index');
    }

    /**
     * Proses verifikasi token QR atau nomor order (/qr/verify)
     */
    public function verify(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:150',
        ], [
            'query.required' => 'Silakan masukkan nomor order atau kode token verifikasi.',
        ]);

        $query = trim($request->input('query'));

        // Jika user mem-paste URL lengkap (contoh: https://lawgika.co.id/qr/abc123)
        if (str_contains($query, '/qr/')) {
            $parts = explode('/qr/', $query);
            $query = trim(end($parts));
        }

        // 1. Cari berdasarkan qr_token
        $order = Order::where('qr_token', $query)->first();

        // 2. Jika tidak ditemukan, cari berdasarkan nomor order (contoh: ORD-PT-...)
        if (!$order) {
            $order = Order::where('order_number', $query)->first();
            if ($order && empty($order->qr_token)) {
                $order->generateQrToken();
            }
        }

        if ($order && $order->qr_token) {
            return redirect()->route('qr.show', $order->qr_token);
        }

        return redirect()->route('qr.index')->with('error', 'Pesanan atau token verifikasi tidak ditemukan dalam database resmi Lawgika. Mohon periksa kembali.');
    }

    /**
     * Download atau render gambar SVG QR Code secara langsung.
     */
    public function image(string $token)
    {
        $order = Order::where('qr_token', $token)->firstOrFail();
        $targetUrl = $order->qr_url;

        try {
            $svg = QrCode::format('svg')->size(300)->margin(2)->generate($targetUrl);
            return response($svg, 200, [
                'Content-Type'        => 'image/svg+xml',
                'Content-Disposition' => 'inline; filename="qr-' . $order->order_number . '.svg"',
            ]);
        } catch (\Throwable $e) {
            abort(500, 'Gagal memproses QR Code.');
        }
    }
}
