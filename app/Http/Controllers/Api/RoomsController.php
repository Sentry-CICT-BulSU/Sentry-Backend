<?php

namespace App\Http\Controllers\Api;

use App\Actions\Rooms\StoreNewRoom;
use App\Http\Controllers\Controller;
use App\Models\Rooms;
use App\Http\Requests\Api\Rooms\{
    StoreRoomsRequest,
    UpdateRoomsRequest
};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RoomsController extends Controller
{
    public function index(): JsonResponse
    {
        // $rooms = Rooms::all(15);
        $rooms = Rooms::all();
        return response()->json([
            'message' => 'Rooms retrieved successfully',
            'rooms' => $rooms,
        ], 200);
    }
    public function store(
        StoreRoomsRequest $request,
        StoreNewRoom $storeNewRoom
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $room = $storeNewRoom->handle($request);
            DB::commit();
            return response()->json([
                'message' => 'Room created successfully',
                'room' => $room,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function show(Rooms $room): JsonResponse
    {
        return response()->json([
            'message' => 'Room retrieved successfully',
            'room' => $room,
        ], 200);
    }
    public function update(UpdateRoomsRequest $request, Rooms $room): JsonResponse
    {
        //
    }
    public function destroy(Rooms $room): JsonResponse
    {
        //
    }
    public function restore($room): JsonResponse
    {
        //
    }
}
