<?php

namespace App\Actions\Rooms;

use App\Http\Requests\Api\Rooms\StoreRoomsRequest;
use App\Models\Rooms;

class StoreNewRoom
{
    public function handle(StoreRoomsRequest $request): Rooms
    {
        return Rooms::create($request->validated());
    }
}
