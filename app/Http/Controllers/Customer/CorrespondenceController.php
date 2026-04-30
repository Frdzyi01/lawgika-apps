<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Correspondence;
use Illuminate\Http\Request;

class CorrespondenceController extends Controller
{
    /**
     * Daftar surat yang dikirim customer (root only).
     * GET /dashboard/surat-menyurat
     */
    public function index()
    {
        $correspondences = Correspondence::root()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.surat-menyurat.index', compact('correspondences'));
    }

    /**
     * Form kirim surat baru.
     * GET /dashboard/surat-menyurat/create
     */
    public function create()
    {
        return view('customer.surat-menyurat.create');
    }

    /**
     * Simpan surat baru.
     * POST /dashboard/surat-menyurat
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'note'  => 'required|string',
            'file'  => 'required|file|mimes:pdf|max:5120',
        ], [
            'title.required' => 'Judul dokumen wajib diisi.',
            'note.required'  => 'Catatan / keterangan wajib diisi.',
            'file.required'  => 'File PDF wajib diunggah.',
            'file.mimes'     => 'File harus berformat PDF.',
            'file.max'       => 'Ukuran file maksimal 5MB.',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        Correspondence::create([
            'user_id'     => auth()->id(),
            'title'       => $request->title,
            'note'        => $request->note,
            'file_path'   => $path,
            'sender_role' => 'customer',
            'status'      => 'pending',
            'parent_id'   => null,
        ]);

        return redirect()
            ->route('customer.surat-menyurat.index')
            ->with('success', 'Dokumen berhasil dikirim. Admin akan segera merespons.');
    }

    /**
     * Detail surat + riwayat balasan.
     * GET /dashboard/surat-menyurat/{id}
     */
    public function show($id)
    {
        $correspondence = Correspondence::root()
            ->where('user_id', auth()->id())
            ->with('replies.user')
            ->findOrFail($id);

        return view('customer.surat-menyurat.show', compact('correspondence'));
    }
}
