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
        $start = Carbon::parse($req['date_start']);
        $end = Carbon::parse($req['date_end']);
        $req['date_start'] = $start;
        $req['date_end'] = $end;

        $conflict = Schedules::query()
            ->where('adviser_id', $req['adviser_id'])
            ->where('subject_id', $req['subject_id'])
            ->where('semester_id', $req['semester_id'])
            ->where('room_id', $req['room_id'])
            ->where('section_id', $req['section_id'])
            ->whereBetween('date_start', [$start, $end])
            ->whereBetween('date_end', [$start, $end]);
        // dd($conflict->exists());

        if ($conflict->exists()) {
            throw new \Exception('Class schedule may conflict with other classes');
        }
        return Schedules::create($request->validated());
    }
}
