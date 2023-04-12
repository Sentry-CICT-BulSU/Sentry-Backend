<?php

namespace App\Actions\RoomKeys;

use App\Http\Requests\Api\RoomKeys\UpdateRoomKeysRequest;
use App\Models\RoomKeyLogs;
use App\Models\RoomKeys;

class UpdateRoomKey
{
    public function handle(RoomKeys $key, UpdateRoomKeysRequest $request)
    {
        $status = [
            'key' => null,
            'log' => null,
        ];
        $log = $key->log()->latest()->first();
        if ($request->status === RoomKeyLogs::STATUSES[RoomKeyLogs::RETURNED]) {
            $status['key'] = RoomKeys::AVAILABLE;
            $status['log'] = RoomKeyLogs::RETURNED;
        } else if ($request->status === RoomKeyLogs::STATUSES[RoomKeyLogs::LOST]) {
            $status['key'] = RoomKeys::LOST;
            $status['log'] = RoomKeyLogs::LOST;
        }
        $key->update(['status' => $status['key']]);
        return $key->log()->create([
            'faculty_id' => $log->faculty_id,
            'status' => $status['log'],
            'subject_id' => $log->subject_id,
            'time_block' => $log->time_block,
        ]);
    }
}
