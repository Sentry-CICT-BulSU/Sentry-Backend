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
        if ($total === 0) {
            return [
                'availabe' => 0,
                'availabe_percentage' => 0,
                'unavailable' => 0,
                'unavailable_percentage' => 0,
                'total_keys' => 0,
            ];
        }
        $decrease = (($total - $unavailableKeys) / $total) * 100;
        $increase = ($unavailableKeys / $total) * 100;

        return [
            'available' => $availableKeys,
            'available_percentage' => $decrease,
            'unavailable' => $unavailableKeys,
            'unavailable_percentage' => $increase,
            'total_keys' => $total,
        ];
    }
}
