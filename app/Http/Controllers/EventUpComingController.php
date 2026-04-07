<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventUpComingRequest;
use App\Http\Requests\UpdateEventUpComingRequest;
use App\Models\EventUpComing;

class EventUpComingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.event-upcoming.index');
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
    public function store(StoreEventUpComingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EventUpComing $eventUpComing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventUpComing $eventUpComing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventUpComingRequest $request, EventUpComing $eventUpComing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventUpComing $eventUpComing)
    {
        //
    }
}
