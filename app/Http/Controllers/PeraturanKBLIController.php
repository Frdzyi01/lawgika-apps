<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreperaturanKBLIRequest;
use App\Http\Requests\UpdateperaturanKBLIRequest;
use App\Models\peraturanKBLI;
use Illuminate\Support\Facades\Storage;

class PeraturanKBLIController extends Controller
{
    public function index()
    {
        $data         = peraturanKBLI::latest()->get();
        $totalPeraturan = $data->count();
        $totalAktif   = $data->where('status', 'aktif')->count();
        $totalDirevisi= $data->where('status', 'direvisi')->count();
        $totalDicabut = $data->where('status', 'dicabut')->count();

        return view('admin.peraturan-kbli.index', compact(
            'data', 'totalPeraturan', 'totalAktif', 'totalDirevisi', 'totalDicabut'
        ));
    }

    public function create()
    {
        //
    }

    public function store(StoreperaturanKBLIRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('file_dokumen')) {
            $validated['file_dokumen'] = $request->file('file_dokumen')->store('kbli', 'public');
        }

        peraturanKBLI::create($validated);

        return redirect()->route('admin.peraturan-kbli.index')->with('success', 'Peraturan berhasil ditambahkan.');
    }

    public function show(peraturanKBLI $peraturan_kbli)
    {
        //
    }

    public function edit(peraturanKBLI $peraturan_kbli)
    {
        //
    }

    public function update(UpdateperaturanKBLIRequest $request, peraturanKBLI $peraturan_kbli)
    {
        $validated = $request->validated();

        if ($request->hasFile('file_dokumen')) {
            // Hapus file lama jika ada
            if ($peraturan_kbli->file_dokumen) {
                Storage::disk('public')->delete($peraturan_kbli->file_dokumen);
            }
            $validated['file_dokumen'] = $request->file('file_dokumen')->store('kbli', 'public');
        } else {
            unset($validated['file_dokumen']); // Jaga file lama
        }

        $peraturan_kbli->update($validated);

        return redirect()->route('admin.peraturan-kbli.index')->with('success', 'Peraturan berhasil diperbarui.');
    }

    public function destroy(peraturanKBLI $peraturan_kbli)
    {
        if ($peraturan_kbli->file_dokumen && Storage::disk('public')->exists($peraturan_kbli->file_dokumen)) {
            Storage::disk('public')->delete($peraturan_kbli->file_dokumen);
        }

        $peraturan_kbli->delete();

        return redirect()->route('admin.peraturan-kbli.index')->with('success', 'Peraturan berhasil dihapus.');
    }
}
