<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomKeysResource;
use App\Models\RoomKeys;
use App\Http\Requests\Api\RoomKeys\{
    StoreRoomKeysRequest,
    UpdateRoomKeysRequest
};
use App\Models\Schedules;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RoomKeysController extends Controller
{
    public function index(): RoomKeysResource
    {
        $keys = RoomKeys::all();
        return new RoomKeysResource($keys);
    }
    public function store(StoreRoomKeysRequest $request): JsonResponse
    {
        //
    }
    public function show(RoomKeys $key): RoomKeysResource
    {
        $key->load([
            'room',
            'schedules.section',
            'schedules.adviser',
            'schedules.subject',
            'schedules.semester',
            'schedules' => fn($q) => $q->orderBy('time_start') //->select('time_start', 'time_end')
                ->where('time_start', '>=', now()->toTimeString())->first()
        ]);
        return new RoomKeysResource($key);
    }
    public function update(UpdateRoomKeysRequest $request, RoomKeys $key): RoomKeysResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $key->update($request->validated());
            DB::commit();
            return new RoomKeysResource($key);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(RoomKeys $key): JsonResponse
    {
        //
    }
    public function restore(RoomKeys $key): JsonResponse
    {
        //
    }
}
