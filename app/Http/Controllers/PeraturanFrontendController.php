<?php

namespace App\Http\Controllers;

use App\Models\peraturanKBLI;
use Illuminate\Http\Request;

class PeraturanFrontendController extends Controller
{
    public function index(Request $request)
    {
        $query = peraturanKBLI::query();

        // Search by judul or kode_kbli
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_peraturan', 'like', "%{$search}%")
                  ->orWhere('kode_kbli', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by year (from tanggal_berlaku)
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_berlaku', $request->tahun);
        }

        $peraturan = $query->latest('tanggal_berlaku')->paginate(15)->withQueryString();

        // Get available years for filter dropdown
        $years = peraturanKBLI::selectRaw('YEAR(tanggal_berlaku) as tahun')
            ->whereNotNull('tanggal_berlaku')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('frontend.peraturan.index', compact('peraturan', 'years'));
    }
}
