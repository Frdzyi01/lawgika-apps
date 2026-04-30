<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Correspondence;
use Illuminate\Http\Request;

class CorrespondenceController extends Controller
{
    /**
     * List semua surat masuk dari customer (root only).
     * GET /admin/surat-menyurat
     */
    public function index()
    {
        $correspondences = Correspondence::root()
            ->with(['user', 'replies'])
            ->latest()
            ->get();

        return view('admin.surat-menyurat.index', compact('correspondences'));
    }

    /**
     * Detail surat + riwayat balasan + form balas.
     * GET /admin/surat-menyurat/{id}
     */
    public function show($id)
    {
        $correspondence = Correspondence::root()
            ->with(['user', 'replies.user'])
            ->findOrFail($id);

        return view('admin.surat-menyurat.show', compact('correspondence'));
    }

    /**
     * Admin membalas surat customer dengan PDF.
     * POST /admin/surat-menyurat/reply/{id}
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:5120',
        ], [
            'note.required' => 'Catatan balasan wajib diisi.',
            'file.required' => 'File PDF balasan wajib diunggah.',
            'file.mimes'    => 'File harus berformat PDF.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $parent = Correspondence::root()->findOrFail($id);

        $path = $request->file('file')->store('documents', 'public');

        Correspondence::create([
            'user_id'     => $parent->user_id,
            'title'       => 'Re: ' . $parent->title,
            'note'        => $request->note,
            'file_path'   => $path,
            'sender_role' => 'admin',
            'status'      => 'pending',
            'parent_id'   => $parent->id,
        ]);

        // Auto-update status induk menjadi 'replied' jika masih pending
        if ($parent->status === 'pending') {
            $parent->update(['status' => 'replied']);
        }

        return redirect()
            ->route('admin.surat-menyurat.show', $parent->id)
            ->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Update status surat.
     * POST /admin/surat-menyurat/status/{id}
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,replied,done',
        ]);

        $correspondence = Correspondence::root()->findOrFail($id);
        $correspondence->update(['status' => $request->status]);

        return redirect()
            ->route('admin.surat-menyurat.show', $correspondence->id)
            ->with('success', 'Status berhasil diperbarui menjadi "' . ucfirst($request->status) . '".');
    }
}
