<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MeetingRoomBooking;
use App\Models\PodcastRoomBooking;
use App\Models\Order;
use App\Models\Correspondence;
use App\Models\SptBadan;
use App\Models\RoomBenefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['orders.service', 'meetingRoomBookings', 'podcastRoomBookings'])
            ->where('id', '!=', auth()->id());

        // ── Filter by role ───────────────────────────────────────────────────
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        } else {
            // Default: tampilkan customer saja (Master Client)
            // Jika SPV ingin lihat semua, bisa pilih filter "Semua"
        }

        // ── Search ───────────────────────────────────────────────────────────
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * AJAX endpoint: Search client untuk autocomplete di form reservasi/order.
     * GET /admin/users/search?q=keyword
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $clients = User::with(['roomBenefits.order', 'orders.service'])
            ->where('role', 'customer')
            ->where(function ($query) use ($q) {
                $query->where('company_name', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$q}%")
                      ->orWhere('pic_name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->select('id', 'name', 'email', 'phone', 'company_name', 'pic_name', 'address', 'npwp', 'business_type')
            ->limit(10)
            ->get()
            ->map(function ($client) {
                // Ambil ringkasan quota
                $meetingQuota = $client->meeting_quota_summary;
                $podcastQuota = $client->podcast_quota_summary;

                // Ambil order/paket aktif
                $activeOrders = $client->active_orders;

                return [
                    'id'            => $client->id,
                    'name'          => $client->name,
                    'email'         => $client->email,
                    'phone'         => $client->phone,
                    'company_name'  => $client->company_name ?? '-',
                    'pic_name'      => $client->pic_name ?? $client->name,
                    'address'       => $client->address,
                    'npwp'          => $client->npwp,
                    'business_type' => $client->business_type,
                    'meeting_quota' => $meetingQuota,
                    'podcast_quota' => $podcastQuota,
                    'active_orders' => $activeOrders->map(fn($o) => [
                        'service_name' => $o->service_name ?: ($o->service->name ?? '-'),
                        'status'       => $o->status,
                        'created_at'   => $o->created_at->format('d M Y'),
                    ]),
                    'room_benefits' => $client->roomBenefits
                        ->where('is_active', true)
                        ->filter(fn($b) => is_null($b->expired_at) || $b->expired_at > now())
                        ->values()
                        ->map(fn($b) => [
                            'id'                => $b->id,
                            'type'              => $b->type,
                            'paket'             => $b->order ? $b->order->service_name : 'Manual Benefit',
                            'total_minutes'     => $b->total_minutes,
                            'used_minutes'      => $b->used_minutes,
                            'remaining_minutes' => $b->remaining_minutes,
                        ]),
                ];
            });

        return response()->json($clients);
    }

    /**
     * Halaman profil client lengkap dengan semua history.
     * GET /admin/users/{id}
     */
    public function show($id)
    {
        $user = User::with([
            'orders.service',
            'meetingRoomBookings',
            'podcastRoomBookings',
            'correspondences' => fn($q) => $q->root()->latest(),
            'sptBadans',
            'roomBenefits.order',
        ])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'nullable|string|min:8|confirmed',
            'role'          => 'required|in:admin,admin1,admin2,customer',
            'phone'         => 'nullable|string|max:20',
            'company_name'  => 'nullable|string|max:255',
            'address'       => 'nullable|string',
            // ── Master Client fields ─────────────────────────────────────────
            'pic_name'      => 'nullable|string|max:255',
            'npwp'          => 'nullable|string|max:50',
            'business_type' => 'nullable|string|max:255',
            'notes'         => 'nullable|string',
        ]);

        // Auto-generate password jika tidak diisi (untuk client dari WA)
        $password = $request->password
            ? $request->password
            : 'Lawgika' . date('Y') . '!';

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($password),
            'role'          => $request->role,
            'phone'         => $request->phone,
            'company_name'  => $request->company_name,
            'address'       => $request->address,
            'pic_name'      => $request->pic_name,
            'npwp'          => $request->npwp,
            'business_type' => $request->business_type,
            'notes'         => $request->notes,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Client baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id,
            'role'          => 'required|in:admin,admin1,admin2,customer',
            'phone'         => 'nullable|string|max:20',
            'company_name'  => 'nullable|string|max:255',
            'address'       => 'nullable|string',
            // ── Master Client fields ─────────────────────────────────────────
            'pic_name'      => 'nullable|string|max:255',
            'npwp'          => 'nullable|string|max:50',
            'business_type' => 'nullable|string|max:255',
            'notes'         => 'nullable|string',
        ]);

        $user->update($request->only([
            'name', 'email', 'role', 'phone', 'company_name', 'address',
            'pic_name', 'npwp', 'business_type', 'notes',
        ]));

        return redirect()->route('admin.users.index')->with('success', 'Data client berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Tidak bisa menghapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus.');
    }
}

