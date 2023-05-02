<?php

namespace App\Actions\Attendances;

use App\Http\Requests\Api\Attendances\StoreAttendanceRequest;
use App\Models\Attendances;

class StoreNewAttendance
{
    function handle(StoreAttendanceRequest $request)
    {
        return Attendances::create($request->validated());
    }
}
