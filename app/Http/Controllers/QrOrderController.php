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
        $order = Order::with(['user', 'service', 'roomBenefits'])
            ->where('qr_token', $token)
            ->firstOrFail();

        // 1. Hitung Status Active / Expired
        $isExpired = false;
        $expiredDate = null;

        if ($order->roomBenefits->isNotEmpty()) {
            $hasActiveBenefit = $order->roomBenefits->contains(function ($b) {
                return $b->is_active && (!$b->expired_at || $b->expired_at->isFuture());
            });
            $isExpired = !$hasActiveBenefit;
            $expiredDate = $order->roomBenefits->first()->expired_at;
        } else {
            $expiredDate = $order->created_at ? $order->created_at->copy()->addYear() : now()->addYear();
            if (in_array($order->status, ['cancelled', 'rejected'])) {
                $isExpired = true;
            } else {
                $isExpired = $expiredDate->isPast();
            }
        }

        // 2. Filter hanya 5 spesifikasi layanan sesuai kebutuhan:
        // - Director Name
        // - Company Name
        // - PIC Name
        // - Operations Address
        // - Business Field
        $formData = $order->form_data ?? [];

        $specifications = [
            'Director Name'      => $formData['director_name'] ?? $formData['director'] ?? $formData['nama_direktur'] ?? '–',
            'Company Name'       => $formData['company_name'] ?? $order->user?->company_name ?? $formData['nama_perusahaan'] ?? '–',
            'PIC Name'           => $formData['pic_name'] ?? $order->user?->name ?? $formData['nama_pic'] ?? '–',
            'Operations Address' => $formData['operational_address'] ?? $formData['operations_address'] ?? $formData['address'] ?? $formData['alamat'] ?? $order->user?->address ?? '–',
            'Business Field'     => $formData['business_field'] ?? $formData['bidang_usaha'] ?? $order->user?->business_type ?? '–',
        ];

        return view('qr.show', compact('order', 'specifications', 'isExpired', 'expiredDate'));
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
