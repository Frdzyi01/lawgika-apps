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
            $data['banner'] = $request->file('banner')->store('event', 'public');
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
            if ($event_upcoming->banner) {
                Storage::disk('public')->delete($event_upcoming->banner);
            }
            $data['banner'] = $request->file('banner')->store('event', 'public');
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
