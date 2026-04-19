<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PublicOrderController extends Controller
{
    /**
     * Simpan order dari halaman publik (guest maupun user yang sudah login).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
            'service_name' => 'required|string|max:255',
            'notes'        => 'nullable|string|max:1000',
        ]);

        // ── 1. Cari / buat akun user ──────────────────────────────────────────
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name'     => $request->name,
                'password' => Hash::make(Str::random(10)), // password sementara
                'role'     => 'customer',
                'phone'    => $request->phone,
            ]
        );

        // Jika user sudah ada tapi nama/phone-nya belum terisi, update
        if ($user->wasRecentlyCreated === false) {
            $user->fill([
                'name'  => $user->name  ?: $request->name,
                'phone' => $user->phone ?: $request->phone,
            ])->save();
        }

        // ── 2. Buat Order ─────────────────────────────────────────────────────
        Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'user_id'      => $user->id,
            'service_id'   => null,
            'service_name' => $request->service_name,
            'status'       => 'pending',
            'total_price'  => 0,
            'notes'        => $request->notes,
        ]);

        // ── 3. Login otomatis ─────────────────────────────────────────────────
        if (!Auth::check()) {
            Auth::login($user);
        }

        return redirect()->route('customer.dashboard')
            ->with('success', 'Pesanan Anda berhasil dikirim! Tim kami akan segera menghubungi Anda.');
    }
}
