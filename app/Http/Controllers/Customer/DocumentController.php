<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Order;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService) {}

    /**
     * Daftar semua dokumen milik user (halaman umum).
     */
    public function index()
    {
        $documents = Document::where('user_id', auth()->id())->latest()->get();
        return view('customer.documents.index', compact('documents'));
    }

    /**
     * Upload dokumen baru untuk sebuah order.
     * Route: POST dashboard/documents
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id'      => 'required|exists:orders,id',
            'document_type' => 'required|string',
            'document'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'document.max'  => 'Ukuran file maksimal adalah 5MB.',
            'document.mimes'=> 'Format file harus JPG, PNG, atau PDF.',
        ]);

        // Pastikan order milik user yang sedang login
        $order = Order::findOrFail($request->order_id);
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses order ini.');
        }

        // Validasi apakah tipe dokumen valid & belum melebihi batas
        $check = $this->documentService->canUpload($order->id, $request->document_type);
        if (!$check['allowed']) {
            return back()->withErrors(['document_type' => $check['reason']])->withInput();
        }

        // Simpan file
        $file      = $request->file('document');
        $cleanName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $ext       = $file->getClientOriginalExtension();
        $filename  = time() . "_{$cleanName}.{$ext}";
        $path      = $file->storeAs('documents/user_' . auth()->id(), $filename, 'public');

        // Cari service_id dari order (bisa null untuk order lama)
        $serviceId = $order->service_id;

        Document::create([
            'user_id'       => auth()->id(),
            'order_id'      => $order->id,
            'service_id'    => $serviceId,
            'document_type' => $request->document_type,
            'filename'      => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'type'          => strtolower($request->document_type), // backward compat
            'status'        => 'pending',
        ]);

        // Sinkronisasi status order
        $this->documentService->syncOrderStatus($order);

        return back()->with('success', 'Dokumen berhasil diunggah dan sedang menunggu verifikasi admin.');
    }
}
