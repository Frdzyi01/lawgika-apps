<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JasaPembukuanController extends Controller
{
    /**
     * Pricing matrix — all prices from backend, never frontend.
     * slug => [ paket => harga_rupiah ]
     */
    public const PACKAGES = [
        'pembukuan-pajak' => [
            'label'    => 'Pembukuan dan Pajak',
            'subtitle' => 'Per Bulan',
            'items' => [
                'premium'   => ['label' => 'Premium',    'harga' => 4500000,  'badge' => null],
                'eksklusif' => ['label' => 'Eksklusif',  'harga' => 6030000,  'badge' => 'Paling Populer'],
                'enterprise' => ['label' => 'Enterprise', 'harga' => 8972500, 'badge' => 'Rekomendasi'],
            ],
        ],
        'berlangganan-pajak' => [
            'label'    => 'Berlangganan Pajak',
            'subtitle' => 'Per Bulan',
            'items' => [
                'premium'   => ['label' => 'Premium',    'harga' => 2700000,  'badge' => null],
                'eksklusif' => ['label' => 'Eksklusif',  'harga' => 4140000,  'badge' => 'Paling Populer'],
                'enterprise' => ['label' => 'Enterprise', 'harga' => 6475000, 'badge' => 'Rekomendasi'],
            ],
        ],
    ];

    /** Kategori short code for order number */
    private const KATEGORI_CODE = [
        'pembukuan-pajak'    => 'BP',
        'berlangganan-pajak' => 'BL',
    ];

    /** Paket short code for order number */
    private const PAKET_CODE = [
        'premium'    => 'PRE',
        'eksklusif'  => 'EKS',
        'enterprise' => 'ENT',
    ];

    /**
     * Show order form.
     */
    public function create(string $kategori, string $paket)
    {
        $kategori = strtolower($kategori);
        $paket    = strtolower($paket);

        // Validate slug
        if (! isset(self::PACKAGES[$kategori]['items'][$paket])) {
            abort(404, 'Paket tidak ditemukan.');
        }

        $kategoriInfo = self::PACKAGES[$kategori];
        $paketInfo    = $kategoriInfo['items'][$paket];

        return view('order.jasa-pembukuan.create', compact('kategori', 'paket', 'kategoriInfo', 'paketInfo'));
    }

    /**
     * Store order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori'        => 'required|in:' . implode(',', array_keys(self::PACKAGES)),
            'paket'           => 'required|string|max:50',
            'nama_lengkap'    => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'no_whatsapp'     => 'required|string|max:20',
            'nama_perusahaan' => 'required|string|max:255',
            'jenis_usaha'     => 'required|string|max:255',
            'catatan'         => 'nullable|string|max:2000',
        ]);

        $kategori = $request->kategori;
        $paket    = strtolower($request->paket);

        // Backend validation: paket must exist in category
        if (! isset(self::PACKAGES[$kategori]['items'][$paket])) {
            abort(422, 'Paket tidak valid.');
        }

        $kategoriInfo = self::PACKAGES[$kategori];
        $paketInfo    = $kategoriInfo['items'][$paket];

        // Build order code: ORD-JPP-{KAT}-{PKT}-YYYYMMDD-XXXXX
        $katCode = self::KATEGORI_CODE[$kategori] ?? strtoupper(substr($kategori, 0, 2));
        $pktCode = self::PAKET_CODE[$paket]       ?? strtoupper(substr($paket, 0, 3));
        $orderNumber = 'ORD-JPP-' . $katCode . '-' . $pktCode . '-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id'      => auth()->id(),
            'service_id'   => null,
            'service_name' => 'Jasa Pembukuan & Perpajakan – ' . $kategoriInfo['label'] . ' ' . $paketInfo['label'],
            'status'       => 'pending',
            'total_price'  => $paketInfo['harga'],
            'notes'        => $request->catatan,
            'form_data'    => [
                'layanan'         => 'jasa_pembukuan_perpajakan',
                'kategori'        => $kategori,
                'paket'           => $paket,
                'harga'           => $paketInfo['harga'],
                'nama_lengkap'    => $request->nama_lengkap,
                'email'           => $request->email,
                'no_whatsapp'     => $request->no_whatsapp,
                'nama_perusahaan' => $request->nama_perusahaan,
                'jenis_usaha'     => $request->jenis_usaha,
            ],
        ]);

        // Store summary in session for success page
        session([
            'jpp_success' => [
                'order_number'    => $orderNumber,
                'kategori'        => $kategori,
                'paket'           => $paket,
                'kategori_label'  => $kategoriInfo['label'],
                'paket_label'     => $paketInfo['label'],
                'harga'           => $paketInfo['harga'],
                'nama_lengkap'    => $request->nama_lengkap,
                'email'           => $request->email,
                'no_whatsapp'     => $request->no_whatsapp,
                'nama_perusahaan' => $request->nama_perusahaan,
                'jenis_usaha'     => $request->jenis_usaha,
            ],
        ]);

        return redirect()->route('jpp.success');
    }

    /**
     * Success page.
     */
    public function success()
    {
        if (! session()->has('jpp_success')) {
            return redirect('/jasa-pembukuan-perpajakan');
        }

        $data = session('jpp_success');
        session()->forget('jpp_success');

        return view('order.jasa-pembukuan.success', compact('data'));
    }
}
