<?php

namespace App\Http\Controllers;

use App\Models\Promo;

class PromoControllerFrontend extends Controller
{
    /**
     * Tampilkan katalog semua promo.
     */
    public function index()
    {
        $promos = Promo::where('status', true)->latest()->get();
        return view('frontend.promo.index', compact('promos'));
    }

    /**
     * Tampilkan detail promo berdasarkan ID.
     */
    public function show($id)
    {
        $promo = Promo::findOrFail($id);
        return view('frontend.promo.show', compact('promo'));
    }
}
