<?php

namespace App\Actions\Schedules;

use App\Http\Requests\Api\Schedules\StoreSchedulesRequest;
use App\Models\Schedules;

class StoreNewSchedule
{
    public function handle(StoreSchedulesRequest $request): Schedules
    {
        return Schedules::create($request->validated());
    }
}
