<?php

namespace App\Actions\RoomKeys;

use App\Http\Requests\Api\RoomKeys\UpdateRoomKeysRequest;
use App\Models\RoomKeyLogs;
use App\Models\RoomKeys;

class UpdateRoomKey
{
    public function handle(RoomKeys $key, UpdateRoomKeysRequest $request)
    {
        $key->update($request->validated());
        $key->log()->create([
            'faculty_id' => $key->log->faculty_id,
            'status' => $request->status,
        ]);
        // RoomKeyLogs::create([
        //     'room_key_id' => $key->id,
        //     'faculty_id' => $key->schedules()->orderBy('time_start') //->select('time_start', 'time_end')
        //         ->where('time_start', '>=', now()->toTimeString())->first(),
        //     'status' => $request->status,
        // ]);
        return $key;
    }
}
