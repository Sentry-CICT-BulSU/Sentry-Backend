<?php

namespace App\Http\Controllers;

use App\Models\RoomKeys;
use App\Http\Requests\Api\RoomKeys\{
    StoreRoomKeysRequest,
    UpdateRoomKeysRequest
};
use Illuminate\Http\JsonResponse;

class RoomKeysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomKeysRequest $request): JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomKeys $key): JsonResponse
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomKeys $key): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomKeysRequest $request, RoomKeys $key): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomKeys $key): JsonResponse
    {
        //
    }
}
