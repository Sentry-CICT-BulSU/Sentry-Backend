<?php

namespace App\Http\Controllers\Api;

use App\Actions\RoomKeyLogs\AvailableKeys;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomKeyLogsResource;
use App\Models\RoomKeyLogs;
use App\Models\RoomKeys;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomKeyLogsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $keyLogs = RoomKeyLogs::query()
            ->with([
                'roomKey.room' => fn($q) => $q->withTrashed(),
                'faculty' => fn($q) => $q->withTrashed(),
                'subject' => fn($q) => $q->withTrashed(),
            ])
            ->when(
                ($request->has('q') && $request->get('q') === RoomKeyLogs::STATUSES[RoomKeyLogs::RETURNED]),
                fn($q) => $q->where('status', RoomKeyLogs::RETURNED)
            )
            ->when(
                ($request->has('q') && $request->get('q') === RoomKeyLogs::STATUSES[RoomKeyLogs::BORROWED]),
                fn($q) => $q->where('status', RoomKeyLogs::BORROWED)
            )
            ->when(
                ($request->has('q') && $request->get('q') === RoomKeyLogs::STATUSES[RoomKeyLogs::LOST]),
                fn($q) => $q->where('status', RoomKeyLogs::LOST)
            )
            ->withTrashed()
            ->latest()->paginate(15);
        return RoomKeyLogsResource::collection($keyLogs)->response();
    }
    public function show(RoomKeys $key): JsonResponse
    {
        return RoomKeyLogsResource::collection(
            $key->logs()->withTrashed()->paginate(15)
        )->response();
    }
    public function availableKeys(AvailableKeys $availableKey): JsonResponse
    {
        return response()->json($availableKey->handle());
    }
}
