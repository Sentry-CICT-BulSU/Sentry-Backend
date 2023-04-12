<?php

namespace App\Actions\RoomKeyLogs;

use App\Models\RoomKeys;

class AvailableKeys
{
    public function handle()
    {
        // DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $keys = RoomKeys::all();
        // DB::statement("SET sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");

        $unavailableKeys = sizeof($keys->filter(fn($log) => $log->status !== RoomKeys::STATUSES[RoomKeys::AVAILABLE])->toArray());
        $availableKeys = sizeof($keys->filter(fn($log) => $log->status === RoomKeys::STATUSES[RoomKeys::AVAILABLE])->toArray());
        $total = $keys->count();
        $decrease = (($total - $unavailableKeys) / $total) * 100;
        $increase = ($unavailableKeys / $total) * 100;

        return [
            'availabe' => $availableKeys,
            'availabe_percentage' => $decrease,
            'unavailable' => $unavailableKeys,
            'unavailable_percentage' => $increase,
            'total_keys' => $total,
        ];
    }
}