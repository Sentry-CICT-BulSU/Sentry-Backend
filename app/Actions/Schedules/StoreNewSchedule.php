<?php

namespace App\Actions\Schedules;

use App\Http\Requests\Api\Schedules\StoreSchedulesRequest;
use App\Models\Schedules;
use Carbon\Carbon;

class StoreNewSchedule
{
    public function handle(StoreSchedulesRequest $request): Schedules
    {
        $req = $request->validated();
        $req['time_start'] = Carbon::parse($req['time_start'])->format('H:i');
        $req['time_end'] = Carbon::parse($req['time_end'])->format('H:i');

        $conflict = Schedules::query()
            ->where('adviser_id', $req['adviser_id'])
            ->where('subject_id', $req['subject_id'])
            ->where('semester_id', $req['semester_id'])
            ->where('room_id', $req['room_id'])
            ->where('section_id', $req['section_id'])
            ->whereBetween('time_start', [$req['time_start'], $req['time_end']])
            ->whereBetween('time_end', [$req['time_start'], $req['time_end']]);
        // dd($conflict->exists());

        if ($conflict->exists()) {
            throw new \Exception('Class schedule may conflict with other classes');
        }
        return Schedules::create($req);
    }
}
