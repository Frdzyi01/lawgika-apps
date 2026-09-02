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
    public function frontendIndex(Request $request)
    {
        $query = Berita::whereNotNull('published_at')
            ->where('published_at', '<=', now());
        
        // Filter by kategori
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        
        $beritas = $query->orderBy('published_at', 'desc')->paginate(9);
        
        $kategoriList = Berita::select('kategori', \DB::raw('count(*) as total'))
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->groupBy('kategori')
            ->get();
        
        return view('frontend.berita.index', compact('beritas', 'kategoriList'));
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
                    ->limit(4)
                    ->get();
                    
        $kategoriList = Berita::select('kategori', \DB::raw('count(*) as total'))
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->groupBy('kategori')
            ->orderBy('kategori')
            ->get();

        return view('frontend.berita.show', compact('berita', 'beritaLainnya', 'kategoriList'));
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
     * Handle image upload dengan auto center-crop ke rasio 1:1 (600x600 px) menggunakan PHP GD.
     */
    private function handleImageUpload(\Illuminate\Http\UploadedFile $file): string
    {
        $filename  = 'berita/' . uniqid('img_') . '.jpg';
        $targetW   = 600;
        $targetH   = 600;

        // Pastikan direktori ada
        Storage::disk('public')->makeDirectory('berita');

        $mime = $file->getMimeType();
        $realPath = $file->getRealPath();

        // Buat resource gambar dari upload
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
            return $file->store('berita', 'public');
        }

        [$origW, $origH] = getimagesize($realPath);
        if (!$origW || !$origH) {
            $origW = imagesx($src);
            $origH = imagesy($src);
        }

        // Auto center-crop to 1:1 square
        $minSide = min($origW, $origH);
        $cropX = (int) round(($origW - $minSide) / 2);
        $cropY = (int) round(($origH - $minSide) / 2);

        $dst = imagecreatetruecolor($targetW, $targetH);

        // Preserve white background for transparency
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));

        imagecopyresampled($dst, $src, 0, 0, $cropX, $cropY, $targetW, $targetH, $minSide, $minSide);

        // Simpan ke storage/app/public/berita/
        $path = storage_path('app/public/' . $filename);
        imagejpeg($dst, $path, 90);

        imagedestroy($src);
        imagedestroy($dst);

        return $filename;
    }
}
