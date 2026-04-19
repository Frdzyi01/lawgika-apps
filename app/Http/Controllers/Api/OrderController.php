<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Notifications\NewOrderNotification;

class OrderController extends Controller
{
    public function getServiceFields($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        // Contoh mapping field dinamis berdasarkan ID atau Kategori
        // Anda bisa menyimpannya di DB jika mau dinamis penuh
        $fields = [
            ['name' => 'company_name', 'label' => 'Nama Perusahaan Lengkap', 'type' => 'text', 'required' => true],
            ['name' => 'business_sector', 'label' => 'Sektor Usaha', 'type' => 'text', 'required' => true],
        ];

        return response()->json([
            'fields' => $fields
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'form_data' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        $service = Service::findOrFail($request->service_id);

        $order = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4)),
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'total_price' => $service->price ?? 0,
            'status' => 'pending',
            'form_data' => $request->form_data,
            'notes' => $request->notes,
        ]);

        // Send notifikasi ke Admin (pastikan ada User dengan role admin)
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new NewOrderNotification($order));
        }

        return response()->json([
            'message' => 'Pesanan berhasil dibuat.',
            'order_id' => $order->id
        ], 201);
    }
}
