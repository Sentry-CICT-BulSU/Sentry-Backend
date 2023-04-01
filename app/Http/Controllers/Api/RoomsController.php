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
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

/**
 * Summary of RoomsController
 */
class RoomsController extends Controller
{
    function __construct()
    {
        $this->middleware('admin');
    }
    public function index(): AnonymousResourceCollection
    {
        $rooms = Rooms::paginate(15);
        // $rooms = Rooms::all();
        return RoomResource::collection($rooms);
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
    public function show(Rooms $room): RoomResource
    {
        return new RoomResource($room);
    }
    public function update(UpdateRoomsRequest $request, Rooms $room): RoomResource
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
