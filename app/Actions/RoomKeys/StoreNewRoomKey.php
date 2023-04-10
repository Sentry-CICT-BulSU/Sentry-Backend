<?php

namespace App\Actions\RoomKeys;

use App\Http\Requests\Api\RoomKeys\StoreRoomKeysRequest;
use App\Models\RoomKeyLogs;
use App\Models\RoomKeys;

class StoreNewRoomKey
{
    public function handle(StoreRoomKeysRequest $request)
    {
        $data = $request->validated();
        $currentStatus = RoomKeyLogs::where('room_key_id', $data['room_key_id'])->latest()->first()->status;
        if ($currentStatus == RoomKeyLogs::STATUSES[RoomKeyLogs::BORROWED]) {
            throw new \Exception('Room key is already borrowed');
        } else if ($currentStatus == RoomKeyLogs::STATUSES[RoomKeyLogs::LOST]) {
            throw new \Exception('Room key is missing and cannot be borrowed');
        }
        $data['status'] = RoomKeyLogs::BORROWED;
        return RoomKeyLogs::create($data);
    }
}
