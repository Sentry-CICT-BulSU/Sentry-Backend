<?php

namespace App\Actions\Attendances;

use App\Http\Requests\Api\Attendances\StoreAttendanceRequest;
use App\Models\Attendances;
use App\Models\Schedules;

class StoreNewAttendance
{
    function handle(Schedules $schedule, StoreAttendanceRequest $request)
    {
        $data = $request->validated();
        $data['schedule_id'] = $schedule->id;
        return Attendances::create($data);
    }
}
