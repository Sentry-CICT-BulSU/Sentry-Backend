<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomKeyLogsResource;
use App\Models\RoomKeyLogs;
use App\Http\Requests\StoreRoomKeyLogsRequest;
use App\Http\Requests\UpdateRoomKeyLogsRequest;
use App\Models\RoomKeys;
use Illuminate\Http\JsonResponse;

class RoomKeyLogsController extends Controller
{
    public function index(): JsonResponse
    {
        $keyLogs = RoomKeyLogs::with([
            'roomKey.room',
            'faculty',
            'subject',
        ])->withTrashed()->latest()->paginate(15);
        return RoomKeyLogsResource::collection($keyLogs)->response();
    }
    public function show(RoomKeys $key): JsonResponse
    {
        return RoomKeyLogsResource::collection(
            $key->logs()->withTrashed()->paginate(15)
        )->response();
    }
}
