<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventUpComingRequest;
use App\Http\Requests\UpdateEventUpComingRequest;
use App\Models\EventUpComing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EventUpComingController extends Controller
{
    /**
     * ==============================================
     * ADMIN SECTION (sudah ada)
     * ==============================================
     */

    /**
     * Display a listing of the resource for ADMIN.
     */
    public function index()
    {
        $events = EventUpComing::latest()->get(); // ini sudah aman karena pakai latest()

        $totalEvent   = $events->count();
        $eventAktif   = $events->filter(fn($e) => $e->status_aktif)->count();
        $eventSelesai = $events->filter(fn($e) => !$e->status_aktif)->count();

        return view('admin.event-upcoming.index', compact('events', 'totalEvent', 'eventAktif', 'eventSelesai'));
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
    public function store(StoreEventUpComingRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('banner')) {
            $data['banner'] = $this->handleBannerUpload($request->file('banner'));
        }

        // Konversi narasumber dari string koma ke array JSON
        if (!empty($data['narasumber'])) {
            $data['narasumber'] = array_map('trim', explode(',', $data['narasumber']));
        }

        EventUpComing::create($data);

        return redirect()->route('admin.event-upcoming.index')->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EventUpComing $event_upcoming)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventUpComing $event_upcoming)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventUpComingRequest $request, EventUpComing $event_upcoming)
    {
        $data = $request->validated();

        if ($request->hasFile('banner')) {
            if ($event_upcoming->banner && Storage::disk('public')->exists($event_upcoming->banner)) {
                Storage::disk('public')->delete($event_upcoming->banner);
            }
            $data['banner'] = $this->handleBannerUpload($request->file('banner'));
        } else {
            unset($data['banner']);
        }

        // Konversi narasumber dari string koma ke array JSON
        if (!empty($data['narasumber'])) {
            $data['narasumber'] = array_map('trim', explode(',', $data['narasumber']));
        }

        $event_upcoming->update($data);

        return redirect()->route('admin.event-upcoming.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Handle upload banner event dengan auto center-crop ke rasio 1:1 (600x600 px).
     */
    private function handleBannerUpload(\Illuminate\Http\UploadedFile $file): string
    {
        $filename = 'event/' . uniqid('event_') . '.jpg';
        $targetW  = 600;
        $targetH  = 600;

        Storage::disk('public')->makeDirectory('event');

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
            return $file->store('event', 'public');
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
    public function destroy(EventUpComing $event_upcoming)
    {
        if ($event_upcoming->banner) {
            Storage::disk('public')->delete($event_upcoming->banner);
        }

        $event_upcoming->delete();

        return redirect()->route('admin.event-upcoming.index')->with('success', 'Event berhasil dihapus.');
    }

    /**
     * ==============================================
     * FRONTEND SECTION (TAMBAHAN BARU)
     * ==============================================
     */

    /**
     * Halaman publik: menampilkan semua event untuk user
     */
    public function frontendIndex()
    {
        $events = EventUpComing::orderBy('tanggal_mulai', 'asc')->get();
        return view('frontend.upcomingevent.index', compact('events'));
    }

    /**
     * API untuk detail event (dipanggil popup via fetch)
     */
    public function detail($id)
    {
        $event = EventUpComing::findOrFail($id);

        // Explicit cast agar frontend selalu menerima tipe yang konsisten
        $data = $event->toArray();
        $data['status_aktif'] = (bool) $event->status_aktif;
        $data['label_status'] = $event->label_status; // "Aktif" atau "Selesai"

        return response()->json($data);
    }
}
