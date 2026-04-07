<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreperaturanKBLIRequest;
use App\Http\Requests\UpdateperaturanKBLIRequest;
use App\Models\peraturanKBLI;

class PeraturanKBLIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.peraturan-kbli.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreperaturanKBLIRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(peraturanKBLI $peraturanKBLI)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(peraturanKBLI $peraturanKBLI)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateperaturanKBLIRequest $request, peraturanKBLI $peraturanKBLI)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(peraturanKBLI $peraturanKBLI)
    {
        //
    }
}
