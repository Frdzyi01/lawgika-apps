<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Update the status of a single document.
     * Called via POST from the admin order detail page.
     */
    public function updateStatus(Request $request, Document $document)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected,pending',
        ]);

        $document->update(['status' => $request->status]);

        $label = match ($request->status) {
            'verified' => 'disetujui',
            'rejected' => 'ditolak',
            default    => 'direset ke pending',
        };

        return back()->with('success', 'Dokumen "' . $document->original_name . '" berhasil ' . $label . '.');
    }
}
