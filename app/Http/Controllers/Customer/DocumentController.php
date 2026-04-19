<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('user_id', auth()->id())->latest()->get();
        return view('customer.documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'type' => 'required|in:ktp,npwp,company_nib,other',
            'order_id' => 'nullable|exists:orders,id'
        ]);

        $file = $request->file('document');
        
        $cleanFilename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        $filename = time() . "_{$cleanFilename}.{$extension}";
        
        $path = $file->storeAs('documents/user_' . auth()->id(), $filename, 'public');

        Document::create([
            'user_id' => auth()->id(),
            'order_id' => $request->order_id,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $request->type,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }
}
