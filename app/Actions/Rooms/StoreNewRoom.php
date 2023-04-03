<?php

namespace App\Actions\Rooms;

use App\Http\Requests\Api\Rooms\StoreRoomsRequest;
use App\Models\RoomKeys;
use App\Models\Rooms;

class StoreNewRoom
{
    public function handle(StoreRoomsRequest $request): Rooms
    {
        $room = Rooms::create($request->validated());
        return $room->key()->create([
            'room_id' => $room->id,
            'status' => RoomKeys::AVAILABLE
        ]);
    }
}
