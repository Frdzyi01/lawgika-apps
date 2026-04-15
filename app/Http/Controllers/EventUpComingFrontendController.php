<?php

namespace App\Http\Controllers;

use App\Models\EventUpComing;
use Illuminate\Http\Request;

class EventUpComingFrontendController extends Controller
{
    /**
     * Display a listing of all upcoming events (frontend).
     */
    public function index()
    {
        $events = EventUpComing::orderBy('tanggal_mulai', 'asc')->get();
        return view('frontend.upcomingevent.index', compact('events'));
    }

    /**
     * Return a single event as JSON for the popup modal.
     */
    public function detail($id)
    {
        $event = EventUpComing::findOrFail($id);

        return response()->json([
            'id'              => $event->id,
            'nama_event'      => $event->nama_event,
            'deskripsi'       => $event->deskripsi,
            'lokasi'          => $event->lokasi,
            'tanggal_mulai'   => $event->tanggal_mulai ? $event->tanggal_mulai->format('d F Y') : '-',
            'tanggal_selesai' => $event->tanggal_selesai ? $event->tanggal_selesai->format('d F Y') : '-',
            'banner'          => $event->banner ? asset('storage/' . $event->banner) : null,
            'label_status'    => $event->label_status,
        ]);
    }
}
