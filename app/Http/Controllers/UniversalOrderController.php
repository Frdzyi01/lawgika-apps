<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Order;
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
        'pt-dibawah-1m'  => ['label' => 'Pendirian PT (< 1M)',          'url' => '/pendirian-pt->-1m'],
        'pt-diatas-1m'   => ['label' => 'Pendirian PT (> 1M)',          'url' => '/pendirian-pt-<-1m'],
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
        'eksklusif'    => 'Paket Eksklusif',
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

        return view('order.create', compact('service', 'package', 'serviceInfo', 'packageLabel'));
    }

    /**
     * Store the order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service'  => 'required|string|max:100',
            'package'  => 'required|string|max:100',
            'phone'    => 'required|string|max:20',
            'notes'    => 'nullable|string|max:2000',
            'ktp'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'npwp'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $serviceKey   = $request->service;
        $packageKey   = $request->package;
        $serviceInfo  = self::$services[$serviceKey]
            ?? ['label' => Str::title(str_replace('-', ' ', $serviceKey)), 'url' => '/'];
        $packageLabel = self::$packages[$packageKey]
            ?? Str::title(str_replace('-', ' ', $packageKey));

        $serviceName = $serviceInfo['label'] . ' – ' . $packageLabel;

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(substr($serviceKey, 0, 3)) . '-'
                            . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'user_id'      => auth()->id(),
            'service_id'   => null,
            'service_name' => $serviceName,
            'status'       => 'pending',
            'total_price'  => 0,
            'notes'        => $request->notes,
            'form_data'    => [
                'service' => $serviceKey,
                'package' => $packageKey,
                'phone'   => $request->phone,
            ],
        ]);

        // Upload KTP (required)
        $this->storeDoc($request, 'ktp', $order->id, 'ktp');

        // Upload NPWP (optional)
        if ($request->hasFile('npwp')) {
            $this->storeDoc($request, 'npwp', $order->id, 'npwp');
        }

        // Upload Dokumen Pendukung (optional)
        if ($request->hasFile('document')) {
            $this->storeDoc($request, 'document', $order->id, 'other');
        }

        // Persist phone to user profile if not already set
        $user = auth()->user();
        if (empty($user->phone)) {
            $user->phone = $request->phone;
            $user->save();
        }

        return redirect()->route('order.success', [
            'order'   => $order->order_number,
            'service' => $serviceKey,
            'package' => $packageKey,
        ]);
    }

    /**
     * Show the success page.
     */
    public function success(Request $request)
    {
        $serviceKey   = $request->query('service', '');
        $packageKey   = $request->query('package', '');
        $orderNumber  = $request->query('order', '');

        $serviceInfo  = self::$services[$serviceKey]
            ?? ['label' => Str::title(str_replace('-', ' ', $serviceKey)), 'url' => '/'];
        $packageLabel = self::$packages[$packageKey]
            ?? Str::title(str_replace('-', ' ', $packageKey));

        return view('order.success', compact('serviceKey', 'packageKey', 'serviceInfo', 'packageLabel', 'orderNumber'));
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
