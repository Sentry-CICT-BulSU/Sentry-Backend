<?php

namespace App\Http\Controllers\Api;

use App\Actions\RoomKeys\{
    StoreNewRoomKey,
    UpdateRoomKey
};
use App\Http\Controllers\Controller;
use App\Http\Resources\{
    RoomKeyLogsResource,
    RoomKeysResource
};
use App\Models\{RoomKeys};
use App\Http\Requests\Api\RoomKeys\{
    StoreRoomKeysRequest,
    UpdateRoomKeysRequest,
};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RoomKeysController extends Controller
{
    public function index(): JsonResponse
    {
        $keys = RoomKeys::with(['room'])->paginate(15);
        return RoomKeysResource::collection($keys)->response();
    }
    public function store(StoreRoomKeysRequest $request, StoreNewRoomKey $storeNewRoomKey): RoomKeyLogsResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $log = $storeNewRoomKey->handle($request);
            DB::commit();
            return new RoomKeyLogsResource($log);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
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
                ->where('time_start', '>=', now()->toTimeString())->first(),
            'logs.faculty' => fn($q) => $q->withTrashed(),
            'logs.roomKey.room',
            'logs.subject',
            'logs' => fn($q) => $q->limit(20)
        ]);
        return new RoomKeysResource($key);
    }
    public function update(UpdateRoomKeysRequest $request, RoomKeys $key, UpdateRoomKey $updateRoomKey): RoomKeysResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $key = $updateRoomKey->handle($key, $request);
            DB::commit();
            return new RoomKeysResource($key);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
