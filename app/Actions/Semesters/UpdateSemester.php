<?php

namespace App\Actions\Semesters;

use App\Http\Requests\Api\Semesters\UpdateSemestersRequest;
use App\Models\Semesters;
use Carbon\Carbon;

/**
 * Summary of UpdateSemester
 */
class UpdateSemester
{
    public function handle(Semesters $semester, UpdateSemestersRequest $request): Semesters
    {
        $req = $request->validated();
        if ($req['duration_start'] && $req['duration_end']) {
            $start = Carbon::parse($req['duration_start']);
            $req['duration_start'] = $start;
            $end = Carbon::parse($req['duration_end']);
            $req['duration_end'] = $end;
        }
        $conflict = Semesters::query()
            ->where('name', $req['name'])
            ->when(
                $request->has('duration_start') && $request->has('duration_end'),
                fn($q) => $q->whereBetween('duration_start', [$start, $end])
                    ->whereBetween('duration_end', [$start, $end])
            );
        // dd($conflict->exists());

        if ($conflict->exists()) {
            throw new \Exception('Semester schedule may conflict with other semesters');
        }
        $semester->update($req);
        return $semester;
    }
}
