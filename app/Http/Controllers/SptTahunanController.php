<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SptBadan;

class SptTahunanController extends Controller
{
    /**
     * Handle submission from frontend form.
     */
    public function store(Request $request)
    {
        $rules = [
            'subject_type'    => 'required|in:pribadi,badan',
            'tahun_pajak'     => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'laporan_keuangan'=> 'required|in:sudah,belum',
            'status_lapor'    => 'required|in:sudah,belum',
        ];

        $messages = [
            'subject_type.required' => 'Pilih subject pajak terlebih dahulu.',
            'tahun_pajak.required'  => 'Tahun pajak wajib diisi.',
            'tahun_pajak.min'       => 'Tahun pajak tidak valid.',
        ];

        if ($request->subject_type === 'pribadi') {
            $rules['nama_lengkap'] = 'required|string|max:255';
            $rules['nik']          = 'nullable|digits:16';
        } else {
            $rules['perusahaan']      = 'required|string|max:255';
            $rules['npwp_perusahaan'] = 'nullable|string|max:30';
        }

        $validated = $request->validate($rules, $messages);

        SptBadan::create([
            'user_id'          => auth()->id(),
            'subject_type'     => $validated['subject_type'],
            'nama_lengkap'     => $request->nama_lengkap,
            'nik'              => $request->nik,
            'perusahaan'       => $request->perusahaan,
            'npwp_perusahaan'  => $request->npwp_perusahaan,
            'nama'             => $validated['subject_type'] === 'pribadi'
                                    ? $request->nama_lengkap
                                    : $request->perusahaan,
            'jenis_usaha'      => null,
            'tahun_pajak'      => $validated['tahun_pajak'],
            'laporan_keuangan' => $validated['laporan_keuangan'],
            'status_lapor'     => $validated['status_lapor'],
            'status_pesanan'   => 'Menunggu Proses',
        ]);

        // Store summary in session for success page
        session([
            'spt_success' => [
                'subject_type'    => $validated['subject_type'],
                'nama_lengkap'    => $request->nama_lengkap,
                'nik'             => $request->nik,
                'perusahaan'      => $request->perusahaan,
                'npwp_perusahaan' => $request->npwp_perusahaan,
                'tahun_pajak'     => $validated['tahun_pajak'],
                'laporan_keuangan'=> $validated['laporan_keuangan'],
                'status_lapor'    => $validated['status_lapor'],
            ],
        ]);

        return redirect()->route('spt-tahunan.success');
    }

    /**
     * Success page after submission.
     */
    public function success()
    {
        if (! session()->has('spt_success')) {
            return redirect('/pelaporan-spt-tahunan');
        }

        $data = session('spt_success');
        session()->forget('spt_success');

        return view('frontend.services.pembukuan-pajak.pelaporan-spt-tahunan-success', compact('data'));
    }

    /**
     * Admin Dashboard: list all submissions.
     */
    public function adminDashboard()
    {
        $requests = SptBadan::with('user')->latest()->get();
        return view('admin.spt-badan.index', compact('requests'));
    }

    /**
     * Admin: update status of a submission.
     */
    public function updateStatus(Request $request, $id)
    {
        $sptRequest = SptBadan::findOrFail($id);

        $request->validate([
            'status_pesanan' => 'required|in:Menunggu Proses,Diproses,Selesai',
        ]);

        $sptRequest->update([
            'status_pesanan' => $request->status_pesanan,
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Customer Dashboard: list user's own submissions.
     */
    public function customerDashboard()
    {
        $requests = SptBadan::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.spt-badan.index', compact('requests'));
    }
}
