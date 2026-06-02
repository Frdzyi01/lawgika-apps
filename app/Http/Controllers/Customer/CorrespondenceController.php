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
            'title.required' => __('validation.corres.title_required'),
            'note.required'  => __('validation.corres.note_required'),
            'file.required'  => __('validation.corres.file_required'),
            'file.mimes'     => __('validation.corres.file_mimes'),
            'file.max'       => __('validation.corres.file_max'),
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
            ->with('success', __('flash.corres_submitted_success'));
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
