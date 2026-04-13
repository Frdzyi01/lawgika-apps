<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBeritaRequest;
use App\Http\Requests\UpdateBeritaRequest;
use App\Models\Berita;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Frontend: Tampilkan daftar berita
     */
    public function frontendIndex()
    {
        $beritas = Berita::whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->orderBy('published_at', 'desc')
                    ->paginate(9);

        return view('frontend.berita.index', compact('beritas'));
    }

    /**
     * Frontend: Tampilkan detail berita
     */
    public function frontendShow($slug)
    {
        $berita = Berita::where('slug', $slug)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->firstOrFail();

        $beritaLainnya = Berita::where('id', '!=', $berita->id)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->orderBy('published_at', 'desc')
                    ->limit(5)
                    ->get();

        return view('frontend.berita.show', compact('berita', 'beritaLainnya'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beritas     = Berita::latest('published_at')->paginate(10);
        $totalBerita = Berita::count();
        $published   = Berita::whereNotNull('published_at')->where('published_at', '<=', now())->count();
        $draft       = $totalBerita - $published;

        return view('admin.berita.index', compact('beritas', 'totalBerita', 'published', 'draft'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.berita.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBeritaRequest $request)
    {
        $data = $request->validated();

        // Default penulis dari user yang login
        if (empty($data['penulis'])) {
            $data['penulis'] = Auth::user()->name;
        }

        // Upload & resize gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->handleImageUpload($request->file('gambar'));
        }

        Berita::create($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBeritaRequest $request, Berita $berita)
    {
        $data = $request->validated();

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $this->handleImageUpload($request->file('gambar'));
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $berita)
    {
        // Hapus file gambar dari storage
        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus!');
    }

    /**
     * Handle image upload dengan resize ke 450x294 menggunakan PHP GD.
     */
    private function handleImageUpload(\Illuminate\Http\UploadedFile $file): string
    {
        $filename  = 'berita/' . uniqid('img_') . '.jpg';
        $targetW   = 450;
        $targetH   = 294;

        // Pastikan direktori ada
        Storage::disk('public')->makeDirectory('berita');

        $mime = $file->getMimeType();

        // Buat resource gambar dari upload
        if ($mime === 'image/png') {
            $src = imagecreatefrompng($file->getRealPath());
        } elseif ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $src = imagecreatefromjpeg($file->getRealPath());
        } else {
            // Fallback: coba jpeg
            $src = imagecreatefromjpeg($file->getRealPath());
        }

        [$origW, $origH] = getimagesize($file->getRealPath());

        // Resize ke 450x294 (cover: crop center)
        $origRatio  = $origW / $origH;
        $targetRatio = $targetW / $targetH;

        if ($origRatio > $targetRatio) {
            // Gambar lebih lebar → crop kiri-kanan
            $cropH = $origH;
            $cropW = (int) round($origH * $targetRatio);
            $cropX = (int) round(($origW - $cropW) / 2);
            $cropY = 0;
        } else {
            // Gambar lebih tinggi → crop atas-bawah
            $cropW = $origW;
            $cropH = (int) round($origW / $targetRatio);
            $cropX = 0;
            $cropY = (int) round(($origH - $cropH) / 2);
        }

        $dst = imagecreatetruecolor($targetW, $targetH);

        // Preserve transparency for PNG
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));

        imagecopyresampled($dst, $src, 0, 0, $cropX, $cropY, $targetW, $targetH, $cropW, $cropH);

        // Simpan ke storage/app/public/berita/
        $path = storage_path('app/public/' . $filename);
        imagejpeg($dst, $path, 90);

        imagedestroy($src);
        imagedestroy($dst);

        return $filename;
    }
}
