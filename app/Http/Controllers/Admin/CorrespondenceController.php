<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Correspondence;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class CorrespondenceController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsAppService
    ) {}
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

    // ── Admin Create & Store (CRM Flow) ───────────────────────────────────────

    public function create()
    {
        return view('admin.surat-menyurat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title'   => 'required|string|max:255',
            'note'    => 'required|string',
            'file'    => 'required|file|mimes:pdf|max:5120',
        ], [
            'user_id.required' => 'Pilih client penerima surat.',
            'title.required'   => 'Judul surat wajib diisi.',
            'note.required'    => 'Catatan/deskripsi surat wajib diisi.',
            'file.required'    => 'File surat (PDF) wajib diunggah.',
            'file.mimes'       => 'File harus berformat PDF.',
            'file.max'         => 'Ukuran file maksimal 5MB.',
        ]);

        $path = $request->file('file')->store('documents/surat', 'public');

        $correspondence = Correspondence::create([
            'user_id'     => $request->user_id,
            'title'       => $request->title,
            'note'        => $request->note,
            'file_path'   => $path,
            'sender_role' => 'admin',
            'status'      => 'pending',
            'parent_id'   => null,
        ]);

        // ── WhatsApp Notification ─────────────────────────────────────────────
        $waMessage = '';
        try {
            $waLog = $this->whatsAppService->notifyCorrespondenceCreated($correspondence);
            if ($waLog && $waLog->status === \App\Models\WhatsappLog::STATUS_SUCCESS) {
                $waMessage = ' WhatsApp notifikasi berhasil dikirim.';
            } elseif ($waLog) {
                $waMessage = ' Tetapi WhatsApp gagal dikirim.';
            }
        } catch (\Exception $e) {
            $waMessage = ' Tetapi WhatsApp gagal dikirim.';
        }

        return redirect()
            ->route('admin.surat-menyurat.index')
            ->with('success', 'Surat baru berhasil dikirim ke client.' . $waMessage);
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
