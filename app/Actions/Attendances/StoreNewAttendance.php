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
        if ($request->file('attachment')) {
            $fileName = $request->file('attachment')->storePublicly('attachments');
            if (!$fileName) {
                throw new \Exception('Failed to upload attachment', 500);
            }
            $data['attachment'] = $fileName;
        }
        return Attendances::create($data);
    }
}
