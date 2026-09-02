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
            $data['gambar'] = $this->handleImageUpload($request->file('gambar'));
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
            if ($promo->gambar && Storage::disk('public')->exists($promo->gambar)) {
                Storage::disk('public')->delete($promo->gambar);
            }
            $data['gambar'] = $this->handleImageUpload($request->file('gambar'));
        } else {
            unset($data['gambar']); // Jaga gambar lama
        }

        $promo->update($data);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Handle upload gambar promo dengan auto center-crop ke rasio 1:1 (600x600 px).
     */
    private function handleImageUpload(\Illuminate\Http\UploadedFile $file): string
    {
        $filename = 'promo/' . uniqid('promo_') . '.jpg';
        $targetW  = 600;
        $targetH  = 600;

        Storage::disk('public')->makeDirectory('promo');

        $mime = $file->getMimeType();
        $realPath = $file->getRealPath();

        if ($mime === 'image/png') {
            $src = @imagecreatefrompng($realPath);
        } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
            $src = @imagecreatefromwebp($realPath);
        } else {
            $src = @imagecreatefromjpeg($realPath);
            if (!$src) {
                $src = @imagecreatefromstring(file_get_contents($realPath));
            }
        }

        if (!$src) {
            return $file->store('promo', 'public');
        }

        [$origW, $origH] = getimagesize($realPath);
        if (!$origW || !$origH) {
            $origW = imagesx($src);
            $origH = imagesy($src);
        }

        $minSide = min($origW, $origH);
        $cropX = (int) round(($origW - $minSide) / 2);
        $cropY = (int) round(($origH - $minSide) / 2);

        $dst = imagecreatetruecolor($targetW, $targetH);
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
        imagecopyresampled($dst, $src, 0, 0, $cropX, $cropY, $targetW, $targetH, $minSide, $minSide);

        $path = storage_path('app/public/' . $filename);
        imagejpeg($dst, $path, 90);

        imagedestroy($src);
        imagedestroy($dst);

        return $filename;
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
