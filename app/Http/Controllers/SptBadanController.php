<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SptBadan;

class SptBadanController extends Controller
{
    // Handle submission from frontend
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'tahun_pajak' => 'required|integer|min:2000',
            'laporan_keuangan' => 'required|string',
            'status_lapor' => 'required|string',
        ]);

        SptBadan::create([
            'user_id' => auth()->id(),
            'nama' => $request->nama,
            'perusahaan' => $request->perusahaan,
            'jenis_usaha' => $request->jenis_usaha,
            'tahun_pajak' => $request->tahun_pajak,
            'laporan_keuangan' => $request->laporan_keuangan,
            'status_lapor' => $request->status_lapor,
        ]);

        return redirect()->back()->with('success', 'Pengajuan SPT Badan Anda berhasil dikirim. Kami akan segera memprosesnya.');
    }

    // Customer Dashboard: list user's requests
    public function customerDashboard()
    {
        $requests = SptBadan::where('user_id', auth()->id())
            ->latest()
            ->get();
            
        return view('customer.spt-badan.index', compact('requests'));
    }

    // Admin Dashboard: list all requests
    public function adminDashboard()
    {
        $requests = SptBadan::with('user')->latest()->get();
        return view('admin.spt-badan.index', compact('requests'));
    }

    // Admin: update status
    public function updateStatus(Request $request, $id)
    {
        $sptRequest = SptBadan::findOrFail($id);
        
        $request->validate([
            'status_pesanan' => 'required|in:Pending,Diproses,Selesai'
        ]);
        
        $sptRequest->update([
            'status_pesanan' => $request->status_pesanan
        ]);
        
        return redirect()->back()->with('success', 'Status pesanan SPT berhasil diperbarui.');
    }
}
