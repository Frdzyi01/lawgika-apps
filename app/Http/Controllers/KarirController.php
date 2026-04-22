<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKarirRequest;
use App\Http\Requests\UpdateKarirRequest;
use App\Models\Karir;

class KarirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function Karir()
    {
        return view('frontend.karir');
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
    public function store(StoreKarirRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Karir $karir)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karir $karir)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKarirRequest $request, Karir $karir)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karir $karir)
    {
        //
    }
}
