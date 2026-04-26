<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Order;
use App\Models\UserRoomQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UniversalOrderController extends Controller
{
    /**
     * Map service slug → human-readable label + page URL.
     */
    public static array $services = [
        'pt-perorangan'  => ['label' => 'Pendirian PT Perorangan',     'url' => '/pendirian-pt-perorangan'],
        'cv'             => ['label' => 'Pendirian CV',                 'url' => '/pendirian-cv'],
        'firma'          => ['label' => 'Pendirian Firma',              'url' => '/pendirian-firma'],
        'pt-pma'         => ['label' => 'Pendirian PT PMA',             'url' => '/pendirian-pt-pma'],
        'yayasan'        => ['label' => 'Pendirian Yayasan',            'url' => '/pendirian-yayasan'],
        'pendirian-pt'   => ['label' => 'Pendirian PT',                 'url' => '/pendirian-pt'],
        'pt-diatas-1m'   => ['label' => 'Pendirian PT (Legacy)',        'url' => '/pendirian-pt'],
    ];

    /**
     * Map package slug → label.
     * Packages that don't match will be shown title-cased.
     */
    public static array $packages = [
        'basic'        => 'Paket Basic',
        'professional' => 'Paket Professional',
        'enterprise'   => 'Paket Enterprise',
        'premium'      => 'Paket Premium',
        'eksklusif'    => 'Paket Eksekutif',
        'eksekutif'    => 'Paket Eksekutif',
    ];

    /**
     * Pricing matrix for Pendirian PT.
     * Key: modal_dasar value  →  [ package_slug => price_in_rupiah ]
     */
    public static array $ptPricing = [
        'Di bawah 1 Miliar' => [
            'premium'   => 6030000,
            'eksklusif' => 7740000,
            'eksekutif' => 7740000,
            'enterprise' => 8640000,
        ],
        'Di atas 1 Miliar' => [
            'premium'   => 6930000,
            'eksklusif' => 8640000,
            'eksekutif' => 8640000,
            'enterprise' => 9540000,
        ],
    ];

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Show the order form.
     */
    public function create(string $service, string $package)
    {
        $service  = strtolower($service);
        $package  = strtolower($package);

        $serviceInfo = self::$services[$service]
            ?? ['label' => Str::title(str_replace('-', ' ', $service)), 'url' => '/'];

        $packageLabel = self::$packages[$package]
            ?? Str::title(str_replace('-', ' ', $package));

        // The view handles conditional rendering for all service+package combos
        return view('order.create', compact('service', 'package', 'serviceInfo', 'packageLabel'));
    }

    /**
     * Store the order.
     */
    public function store(Request $request)
    {
        // ── Unified Validation Rules for All Orders ───────────────────────────
        $rules = [
            'service'               => 'required|string|in:' . implode(',', array_keys(self::$services)),
            'package'               => 'required|string|max:100', // Allow any string like premium, eksklusif, enterprise
            'director_name'         => 'required|string|max:255',
            'director_phone'        => 'required|string|max:20',
            'company_name'          => 'required|string|max:255',
            'pic_name'              => 'required|string|max:255',
            'pic_phone'             => 'required|string|max:20',
            'company_email'         => 'required|email|max:255',
            'operational_address'   => 'required|string',
            'business_field'        => 'required|string|max:255',
            'ktp_direktur'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'npwp_direktur'         => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes'                 => 'nullable|string|max:2000',
        ];

        if (strtolower($request->service) === 'pendirian-pt') {
            $rules['modal_dasar'] = 'required|string|in:Di bawah 1 Miliar,Di atas 1 Miliar';
        }

        $request->validate($rules);

        // ── Resolve labels ────────────────────────────────────────────────────
        $serviceKey   = $request->service;
        $packageKey   = $request->package;
        $serviceInfo  = self::$services[$serviceKey]
            ?? ['label' => Str::title(str_replace('-', ' ', $serviceKey)), 'url' => '/'];
        $packageLabel = self::$packages[$packageKey]
            ?? Str::title(str_replace('-', ' ', $packageKey));

        $serviceName = $serviceInfo['label'] . ' – ' . $packageLabel;

        // ── Build form_data payload ───────────────────────────────────────────
        $formData = [
            'service'             => $serviceKey,
            'package'             => $packageKey,
            'director_name'       => $request->input('director_name'),
            'director_phone'      => $request->input('director_phone'),
            'company_name'        => $request->input('company_name'),
            'pic_name'            => $request->input('pic_name'),
            'pic_phone'           => $request->input('pic_phone'),
            'company_email'       => $request->input('company_email'),
            'operational_address' => $request->input('operational_address'),
            'business_field'      => $request->input('business_field'),
        ];

        if ($request->has('modal_dasar')) {
            $formData['modal_dasar'] = $request->input('modal_dasar');
        }

        // ── Create order ──────────────────────────────────────────────────────
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(substr($serviceKey, 0, 3)) . '-'
                            . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'user_id'      => auth()->id(),
            'service_id'   => null,
            'service_name' => $serviceName,
            'status'       => 'pending',
            'total_price'  => 0,
            'notes'        => $request->notes,
            'form_data'    => $formData,
        ]);

        // ── Document uploads ──────────────────────────────────────────────────
        // 2 required documents for all orders now
        $docTypes = [
            'ktp_direktur'    => 'ktp_direktur',
            'npwp_direktur'   => 'npwp_direktur',
        ];

        foreach ($docTypes as $inputName => $type) {
            if ($request->hasFile($inputName)) {
                $this->storeDoc($request, $inputName, $order->id, $type);
            }
        }

        // (Optional) Update user profile phone if not exists using director_phone
        $user = auth()->user();
        if (empty($user->phone) && !empty($request->director_phone)) {
            $user->phone = $request->director_phone;
            $user->save();
        }

        // ── Auto Create Shared Room Quota for PT Eksklusif/Enterprise ─────────
        if (in_array(strtolower($packageKey), ['eksklusif', 'eksekutif', 'enterprise'])) {
            UserRoomQuota::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'total_seconds'     => 216000, // 60 jam
                    'used_seconds'      => \DB::raw('used_seconds'), // keep if exists, or defaults to 0 if new
                    'remaining_seconds' => \DB::raw('216000 - used_seconds'), 
                    'expired_at'        => now()->addYear()
                ]
            );
        }

        return redirect()->route('order.success', [
            'order'       => $order->order_number,
            'service'     => $serviceKey,
            'package'     => $packageKey,
            'modal_dasar' => $request->input('modal_dasar', ''),
        ]);
    }

    /**
     * Show the success page.
     */
    public function success(Request $request)
    {
        $serviceKey   = $request->query('service', '');
        $packageKey   = strtolower($request->query('package', ''));
        $orderNumber  = $request->query('order', '');
        $modalDasar   = $request->query('modal_dasar', '');

        $serviceInfo  = self::$services[$serviceKey]
            ?? ['label' => Str::title(str_replace('-', ' ', $serviceKey)), 'url' => '/'];
        $packageLabel = self::$packages[$packageKey]
            ?? Str::title(str_replace('-', ' ', $packageKey));

        // Hitung total biaya khusus Pendirian PT
        $totalBiaya = null;
        if ($serviceKey === 'pendirian-pt' && $modalDasar && isset(self::$ptPricing[$modalDasar][$packageKey])) {
            $totalBiaya = self::$ptPricing[$modalDasar][$packageKey];
        }

        return view('order.success', compact(
            'serviceKey', 'packageKey', 'serviceInfo', 'packageLabel',
            'orderNumber', 'modalDasar', 'totalBiaya'
        ));
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function storeDoc(Request $request, string $inputName, int $orderId, string $type): void
    {
        $file     = $request->file($inputName);
        $clean    = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $ext      = $file->getClientOriginalExtension();
        $filename = time() . "_{$clean}.{$ext}";
        $path     = $file->storeAs('documents/user_' . auth()->id(), $filename, 'public');

        Document::create([
            'user_id'       => auth()->id(),
            'order_id'      => $orderId,
            'filename'      => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'type'          => $type,
            'status'        => 'pending',
        ]);
    }
}
