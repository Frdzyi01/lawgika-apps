<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PtPeroranganOrderController extends Controller
{
    /**
     * Daftar paket yang valid beserta harganya.
     */
    private array $packages = [
        'basic'        => ['label' => 'Paket Basic',        'price' => 'Rp 2.000.000'],
        'professional' => ['label' => 'Paket Professional', 'price' => 'Rp 6.000.000'],
        'enterprise'   => ['label' => 'Paket Enterprise',   'price' => 'Rp 8.000.000'],
    ];

    /**
     * Halaman form order.
     */
    public function create(string $package)
    {
        $package = strtolower($package);

        if (!array_key_exists($package, $this->packages)) {
            abort(404, 'Paket tidak ditemukan.');
        }

        $packageInfo = $this->packages[$package];

        return view('order.pt-perorangan.create', compact('package', 'packageInfo'));
    }

    /**
     * Simpan order + upload dokumen.
     */
    public function store(Request $request)
    {
        $baseRules = [
            'package'  => 'required|string|in:basic,professional,enterprise',
            'notes'    => 'nullable|string|max:2000',
        ];

        if ($request->package === 'professional') {
            $rules = array_merge($baseRules, [
                'director_name'       => 'required|string|max:255',
                'director_phone'      => 'required|string|max:20',
                'company_name'        => 'required|string|max:255',
                'pic_name'            => 'required|string|max:255',
                'pic_phone'           => 'required|string|max:20',
                'company_email'       => 'required|email|max:255',
                'operational_address' => 'required|string',
                'business_field'      => 'required|string|max:255',
                'akta_pendirian'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'npwp_perusahaan'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'sk_kemenkumham'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'ktp_direktur'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'npwp_direktur'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);
        } else {
            $rules = array_merge($baseRules, [
                'phone'    => 'required|string|max:20',
                'ktp'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'npwp'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);
        }

        $request->validate($rules);

        $packageKey  = $request->package;
        $packageInfo = $this->packages[$packageKey];

        $formData = [
            'package' => $packageKey,
        ];

        if ($request->package === 'professional') {
            $formData = array_merge($formData, $request->only([
                'director_name', 'director_phone', 'company_name', 'pic_name',
                'pic_phone', 'company_email', 'operational_address', 'business_field'
            ]));
        } else {
            $formData['phone'] = $request->phone;
        }

        // Buat order
        $order = Order::create([
            'order_number' => 'ORD-PT-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'user_id'      => auth()->id(),
            'service_id'   => null,
            'service_name' => 'Pendirian PT Perorangan – ' . $packageInfo['label'],
            'status'       => 'pending',
            'total_price'  => 0,
            'notes'        => $request->notes,
            'form_data'    => $formData,
        ]);

        if ($request->package === 'professional') {
            $docTypes = [
                'akta_pendirian'  => 'akta_pendirian',
                'npwp_perusahaan' => 'npwp_perusahaan',
                'sk_kemenkumham'  => 'sk_kemenkumham',
                'ktp_direktur'    => 'ktp_direktur',
                'npwp_direktur'   => 'npwp_direktur',
            ];
            foreach ($docTypes as $inputName => $type) {
                if ($request->hasFile($inputName)) {
                    $this->storeDoc($request, $inputName, $order->id, $type);
                }
            }
        } else {
            // Upload KTP (wajib)
            $this->storeDoc($request, 'ktp', $order->id, 'ktp');

            // Upload NPWP (opsional)
            if ($request->hasFile('npwp')) {
                $this->storeDoc($request, 'npwp', $order->id, 'npwp');
            }

            // Upload Dokumen Pendukung (opsional)
            if ($request->hasFile('document')) {
                $this->storeDoc($request, 'document', $order->id, 'other');
            }
        }

        // Update nomor HP user jika belum ada
        if ($request->package !== 'professional') {
            $user = auth()->user();
            if (empty($user->phone)) {
                $user->phone = $request->phone;
                $user->save();
            }
        }

        return redirect()->route('order.pt-perorangan.success', [
            'package' => $packageKey,
            'order'   => $order->order_number,
        ]);
    }

    /**
     * Halaman sukses.
     */
    public function success(Request $request)
    {
        $packageKey  = $request->query('package', 'basic');
        $orderNumber = $request->query('order');
        $packageInfo = $this->packages[$packageKey] ?? $this->packages['basic'];

        return view('order.pt-perorangan.success', compact('packageKey', 'packageInfo', 'orderNumber'));
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function storeDoc(Request $request, string $inputName, int $orderId, string $type): void
    {
        $file = $request->file($inputName);

        $cleanName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $ext       = $file->getClientOriginalExtension();
        $filename  = time() . "_{$cleanName}.{$ext}";
        $path      = $file->storeAs('documents/user_' . auth()->id(), $filename, 'public');

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
