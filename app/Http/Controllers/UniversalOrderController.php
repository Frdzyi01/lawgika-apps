<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRequirement;
use App\Models\Order;
use App\Models\Service;
use App\Models\UserRoomQuota;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniversalOrderController extends Controller
{
    public function __construct(protected DocumentService $documentService) {}

    /**
     * Map service slug → human-readable label + page URL + DB slug.
     */
    public static array $services = [
        'pt-perorangan'  => ['label' => 'Pendirian PT Perorangan', 'url' => '/pendirian-pt-perorangan', 'db_slug' => 'pendirian-pt-perorangan'],
        'cv'             => ['label' => 'Pendirian CV',             'url' => '/pendirian-cv',             'db_slug' => 'pendirian-cv'],
        'firma'          => ['label' => 'Pendirian Firma',          'url' => '/pendirian-firma',          'db_slug' => 'pendirian-firma'],
        'pt-pma'         => ['label' => 'Pendirian PT PMA',         'url' => '/pendirian-pt-pma',         'db_slug' => 'pendirian-pt-pma'],
        'yayasan'        => ['label' => 'Pendirian Yayasan',        'url' => '/pendirian-yayasan',        'db_slug' => 'pendirian-yayasan'],
        'pendirian-pt'   => ['label' => 'Pendirian PT',             'url' => '/pendirian-pt',             'db_slug' => 'pendirian-pt'],
        'pt-diatas-1m'   => ['label' => 'Pendirian PT (Legacy)',    'url' => '/pendirian-pt',             'db_slug' => 'pendirian-pt'],
        'virtual-office' => ['label' => 'Virtual Office',           'url' => '/virtual-office',           'db_slug' => 'virtual-office'],
    ];

    public static array $packages = [
        'basic'        => 'Paket Izin',
        'professional' => 'Paket Bundling',
        'enterprise'   => 'Paket Bundling',
        'premium'      => 'Paket Izin',
        'eksklusif'    => 'Paket Izin (Lama)',
        'eksekutif'    => 'Paket Eksekutif',
    ];

    public static array $ptPricing = [
        'Di bawah 1 Miliar' => ['premium' => 5000000, 'eksklusif' => 5000000, 'eksekutif' => 5000000, 'enterprise' => 9300000],
        'Di atas 1 Miliar'  => ['premium' => 6000000, 'eksklusif' => 6000000, 'eksekutif' => 6000000, 'enterprise' => 10000000],
    ];

    public static array $otherPricing = [
        'pt-perorangan' => ['basic' => 1500000, 'professional' => 5800000, 'enterprise' => 5800000],
        'cv'            => ['premium' => 4500000, 'eksklusif' => 4500000, 'enterprise' => 8500000],
        'firma'         => ['premium' => 4500000, 'eksklusif' => 4500000, 'enterprise' => 8500000],
        'yayasan'       => ['premium' => 4500000, 'eksklusif' => 4500000, 'enterprise' => 8500000],
        'pt-pma'        => ['premium' => 7830000, 'eksklusif' => 7830000, 'enterprise' => 10440000],
        'virtual-office' => ['premium' => 2800000, 'eksklusif' => 5500000, 'enterprise' => 6800000],
    ];

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Show the order form — requirements diambil dari DB, BUKAN hardcoded.
     */
    public function create(string $service, string $package)
    {
        $service = strtolower($service);
        $package = strtolower($package);

        $serviceInfo  = self::$services[$service] ?? ['label' => Str::title(str_replace('-', ' ', $service)), 'url' => '/', 'db_slug' => $service];
        $packageLabel = self::$packages[$package] ?? Str::title(str_replace('-', ' ', $package));

        // Ambil requirements dari DB berdasarkan slug layanan
        $dbService    = Service::where('slug', $serviceInfo['db_slug'])->first();
        $requirements = $dbService
            ? DocumentRequirement::where('service_id', $dbService->id)->orderBy('id')->get()
            : collect();

        return view('order.create', compact('service', 'package', 'serviceInfo', 'packageLabel', 'requirements', 'dbService'));
    }

    /**
     * Store the order — dokumen diproses secara dinamis dari $requirements.
     */
    public function store(Request $request)
    {
        // ── Base validation rules ─────────────────────────────────────────────
        $rules = [
            'service'             => 'required|string|in:' . implode(',', array_keys(self::$services)),
            'package'             => 'required|string|max:100',
            'director_name'       => 'required|string|max:255',
            'director_phone'      => 'required|string|max:20',
            'company_name'        => 'required|string|max:255',
            'pic_name'            => 'required|string|max:255',
            'pic_phone'           => 'required|string|max:20',
            'company_email'       => 'required|email|max:255',
            'operational_address' => 'required|string',
            'business_field'      => 'required|string|max:255',
            'notes'               => 'nullable|string|max:2000',
        ];

        if (strtolower($request->service) === 'pendirian-pt') {
            $rules['modal_dasar'] = 'required|string|in:Di bawah 1 Miliar,Di atas 1 Miliar';
        }

        // ── Dynamic file validation dari document_requirements ─────────────────
        $serviceKey  = strtolower($request->service);
        $serviceInfo = self::$services[$serviceKey] ?? ['db_slug' => $serviceKey];
        $dbService   = Service::where('slug', $serviceInfo['db_slug'] ?? $serviceKey)->first();
        $requirements = $dbService
            ? DocumentRequirement::where('service_id', $dbService->id)->get()
            : collect();

        foreach ($requirements as $req) {
            $fieldKey = 'documents.' . $req->document_type;
            if ($req->min_required > 0) {
                $rules[$fieldKey]      = 'required|array|min:1';
                $rules[$fieldKey . '.*'] = 'file|mimes:jpg,jpeg,png,pdf|max:5120';
            } else {
                $rules[$fieldKey]        = 'nullable|array';
                $rules[$fieldKey . '.*'] = 'file|mimes:jpg,jpeg,png,pdf|max:5120';
            }
        }

        $messages = [];
        foreach ($requirements as $req) {
            $messages['documents.' . $req->document_type . '.required'] = $req->label . ' wajib diunggah.';
            $messages['documents.' . $req->document_type . '.min']      = $req->label . ' minimal 1 file.';
            $messages['documents.' . $req->document_type . '.*.mimes']  = $req->label . ': format harus JPG, PNG, atau PDF.';
            $messages['documents.' . $req->document_type . '.*.max']    = $req->label . ': ukuran maksimal 5MB per file.';
        }

        $request->validate($rules, $messages);

        // ── Resolve labels ────────────────────────────────────────────────────
        $packageKey   = strtolower($request->package);
        $packageLabel = self::$packages[$packageKey] ?? Str::title(str_replace('-', ' ', $packageKey));
        $serviceName  = ($serviceInfo['label'] ?? Str::title(str_replace('-', ' ', $serviceKey))) . ' – ' . $packageLabel;

        // ── Build form_data ───────────────────────────────────────────────────
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

        $totalBiaya = 0;
        if ($serviceKey === 'pendirian-pt' && $request->has('modal_dasar')) {
            $md = $request->input('modal_dasar');
            $totalBiaya = self::$ptPricing[$md][$packageKey] ?? 0;
        } elseif (isset(self::$otherPricing[$serviceKey][$packageKey])) {
            $totalBiaya = self::$otherPricing[$serviceKey][$packageKey];
        }

        // ── Create order ──────────────────────────────────────────────────────
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(substr($serviceKey, 0, 3)) . '-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'user_id'      => auth()->id(),
            'service_id'   => $dbService?->id,
            'service_name' => $serviceName,
            'status'       => 'draft',
            'total_price'  => $totalBiaya,
            'notes'        => $request->notes,
            'form_data'    => $formData,
        ]);

        // ── Store dokumen secara dinamis ──────────────────────────────────────
        if ($request->has('documents') && is_array($request->documents)) {
            foreach ($request->documents as $documentType => $files) {
                if (!is_array($files)) continue;
                foreach ($files as $file) {
                    if (!$file || !$file->isValid()) continue;
                    $this->storeDocumentFile($file, $order->id, $documentType, $dbService?->id);
                }
            }
        }

        // ── Sinkronisasi status order ─────────────────────────────────────────
        $this->documentService->syncOrderStatus($order);

        // ── Update user phone ─────────────────────────────────────────────────
        $user = auth()->user();
        if (empty($user->phone) && !empty($request->director_phone)) {
            $user->phone = $request->director_phone;
            $user->save();
        }

        // Benefit activation is now handled ONLY upon admin approval in OrderController.

        return redirect()->route('order.success', [
            'order'       => $order->order_number,
            'service'     => $serviceKey,
            'package'     => $packageKey,
            'modal_dasar' => $request->input('modal_dasar', ''),
        ]);
    }

    /**
     * Show success page.
     */
    public function success(Request $request)
    {
        $serviceKey   = $request->query('service', '');
        $packageKey   = strtolower($request->query('package', ''));
        $orderNumber  = $request->query('order', '');
        $modalDasar   = $request->query('modal_dasar', '');

        $serviceInfo  = self::$services[$serviceKey] ?? ['label' => Str::title(str_replace('-', ' ', $serviceKey)), 'url' => '/'];
        $packageLabel = self::$packages[$packageKey] ?? Str::title(str_replace('-', ' ', $packageKey));

        $totalBiaya = null;
        if ($serviceKey === 'pendirian-pt' && $modalDasar && isset(self::$ptPricing[$modalDasar][$packageKey])) {
            $totalBiaya = self::$ptPricing[$modalDasar][$packageKey];
        } elseif (isset(self::$otherPricing[$serviceKey][$packageKey])) {
            $totalBiaya = self::$otherPricing[$serviceKey][$packageKey];
        }

        return view('order.success', compact('serviceKey', 'packageKey', 'serviceInfo', 'packageLabel', 'orderNumber', 'modalDasar', 'totalBiaya'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: simpan satu file dokumen
    // ─────────────────────────────────────────────────────────────────────────

    private function storeDocumentFile($file, int $orderId, string $documentType, ?int $serviceId): void
    {
        $clean    = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $ext      = $file->getClientOriginalExtension();
        $filename = time() . '_' . $clean . '.' . $ext;
        $path     = $file->storeAs('documents/user_' . auth()->id(), $filename, 'public');

        Document::create([
            'user_id'       => auth()->id(),
            'order_id'      => $orderId,
            'service_id'    => $serviceId,
            'document_type' => $documentType,
            'filename'      => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'type'          => strtolower($documentType), // backward-compat
            'status'        => 'pending',
        ]);
    }
}
