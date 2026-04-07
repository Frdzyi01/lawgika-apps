<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorepromoRequest;
use App\Http\Requests\UpdatepromoRequest;
use App\Models\Promo;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::latest()->get();

        $totalPromo   = $promos->count();
        $promoAktif   = $promos->where('status', true)->count();
        $promoBerakhir= $promos->where('status', false)->count();

        return view('admin.promo.index', compact('promos', 'totalPromo', 'promoAktif', 'promoBerakhir'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorepromoRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('promo', 'public');
        }

        Promo::create($data);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepromoRequest $request, Promo $promo)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($promo->gambar) {
                Storage::disk('public')->delete($promo->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('promo', 'public');
        } else {
            unset($data['gambar']); // Jaga gambar lama
        }

        $promo->update($data);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
    {
        if ($promo->gambar) {
            Storage::disk('public')->delete($promo->gambar);
        }

        $promo->delete();

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil dihapus.');
    }
}
