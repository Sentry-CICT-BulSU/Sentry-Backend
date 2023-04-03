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
        $data['status'] = RoomKeyLogs::BORROWED;
        return RoomKeyLogs::create($data);
    }
}
