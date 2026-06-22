<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // SPV melihat semua user (termasuk admin1, admin2, spv)
        // Tampilkan semua kecuali diri sendiri
        $users = User::with(['orders.service', 'meetingRoomBookings', 'podcastRoomBookings'])
            ->where('id', '!=', auth()->id())
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:8|confirmed',
            'role'         => 'required|in:admin,admin1,admin2,customer',
            'phone'        => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'address'      => 'nullable|string',
        ]);

        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'phone'        => $request->phone,
            'company_name' => $request->company_name,
            'address'      => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun baru berhasil ditambahkan.');
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
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $id,
            'role'         => 'required|in:admin,admin1,admin2,customer',
            'phone'        => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'address'      => 'nullable|string',
        ]);

        $user->update($request->only(['name', 'email', 'role', 'phone', 'company_name', 'address']));

        return redirect()->route('admin.users.index')->with('success', 'Data akun berhasil diperbarui.');
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
