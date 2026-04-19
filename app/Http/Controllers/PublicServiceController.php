<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class PublicServiceController extends Controller
{
    public function show($slug)
    {
        // Temukan service berdasarkan slug
        $service = Service::where('slug', $slug)->firstOrFail();
        
        return view('services.show', compact('service'));
    }
}
