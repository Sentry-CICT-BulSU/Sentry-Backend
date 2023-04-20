<?php
namespace App\Actions\Semesters;

use App\Http\Requests\Api\Semesters\StoreSemestersRequest;
use App\Models\Semesters;
use Carbon\Carbon;

class StoreNewSemester
{
    public function handle(StoreSemestersRequest $request): Semesters
    {
        $req = $request->validated();
        $start = Carbon::parse($req['duration_start']);
        $end = Carbon::parse($req['duration_end']);
        $req['duration_start'] = $start;
        $req['duration_end'] = $end;

        $conflict = Semesters::query()
            ->where('name', $req['name'])
            ->whereBetween('duration_start', [$start, $end])
            ->whereBetween('duration_end', [$start, $end]);
        // dd($conflict->exists());

        if ($conflict->exists()) {
            throw new \Exception('Semester schedule may conflict with other semesters');
        }

        return Semesters::create($req);
    }
}
