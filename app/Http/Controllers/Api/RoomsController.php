<?php

namespace App\Http\Controllers\Api;

use App\Actions\Rooms\StoreNewRoom;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Rooms;
use App\Http\Requests\Api\Rooms\{
    StoreRoomsRequest,
    UpdateRoomsRequest
};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Summary of RoomsController
 */
class RoomsController extends Controller
{
    public function index(): JsonResponse
    {
        $rooms = Rooms::paginate(15);
        // $rooms = Rooms::all();
        return RoomResource::collection($rooms)->response();
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
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function show(Rooms $room): RoomResource
    {
        return new RoomResource($room);
    }
    public function update(
        UpdateRoomsRequest $request,
        Rooms $room
    ): RoomResource|JsonResponse {
        try {
            DB::beginTransaction();
            $room->update($request->validated());
            DB::commit();
            return new RoomResource($room);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy(Rooms $room): JsonResponse
    {
        return !$room->trashed()
            ? response()->json([
                'message' => 'Room deleted successfully',
                'deleted' => $room->delete() && $room->key()->delete()
            ], 200)
            : response()->json([
                'message' => 'The room has already soft deleted',
            ], 403);
    }
    public function restore($room): JsonResponse
    {
        $restore = Rooms::withTrashed()->find($room);
        return $restore->trashed()
            ? response()->json([
                'message' => 'Room restored successfully',
                'restore' => $restore->restore()
            ], 200)
            : response()->json([
                'message' => 'The room has already been restored',
            ], 403);
    }
}
