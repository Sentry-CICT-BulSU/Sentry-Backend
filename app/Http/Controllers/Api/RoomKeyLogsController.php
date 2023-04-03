<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomKeyLogs;
use App\Http\Requests\StoreRoomKeyLogsRequest;
use App\Http\Requests\UpdateRoomKeyLogsRequest;
use Illuminate\Http\JsonResponse;

class RoomKeyLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomKeyLogsRequest $request):JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomKeyLogs $roomKeyLogs):JsonResponse
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomKeyLogs $roomKeyLogs):JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomKeyLogsRequest $request, RoomKeyLogs $roomKeyLogs):JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomKeyLogs $roomKeyLogs):JsonResponse
    {
        //
    }
}
