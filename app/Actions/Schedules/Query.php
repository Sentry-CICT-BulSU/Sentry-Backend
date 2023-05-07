<?php

namespace App\Actions\Schedules;

use App\Models\Schedules;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Query
{
    public function handle(Request $request)
    {
        $schoolYear = Carbon::now()->year . '-' . Carbon::now()->addYear()->year;
        $dayNameNow = Carbon::now()->dayName;
        return $request->has('admin-dash')
            ? $this->adminQuery($dayNameNow, $schoolYear)
            : $this->basicQuery($request, $dayNameNow, $schoolYear);
    }
    private function basicQuery(Request $request, string $dayNameNow, string $schoolYear)
    {
        return Schedules::query()
            ->with([
                'section' => fn($q) => $q->withTrashed(),
                'room' => fn($q) => $q->when(
                    ($request->has('rid') && $request->get('rid') === 'am'),
                    fn($rq) => $rq->where('id', $request->get('rid'))
                )->withTrashed(),
                'adviser' => fn($q) => $q->when(
                    ($request->has('fid')),
                    fn($qf) => $qf->where('id', $request->get('fid'))
                )->withTrashed(),
                'subject' => fn($q) => $q->withTrashed(),
                'semester' => fn($q) => match ($request->user()->type) {
                    User::TYPES[User::ADMIN] => $q->where('academic_year', $schoolYear)->withTrashed(),
                    default => $q->where('academic_year', $schoolYear)
                },
                'attendance' => fn($q) => $q->withTrashed(),
            ])
            ->when(
                ($request->has('q') && $request->get('q') === 'am'),
                fn($q) => $q
                    ->whereTime('time_start', '>=', Carbon::parse('00:00:00')->toTimeString())
                    ->whereTime('time_end', '<=', Carbon::parse('11:59:59')->toTimeString())
                    ->whereJsonContains('active_days', strtolower($dayNameNow))
            )
            ->when(
                ($request->has('q') && $request->get('q') === 'pm'),
                fn($q) => $q
                    ->whereTime('time_start', '>=', Carbon::parse('12:00:00')->toTimeString())
                    ->whereTime('time_end', '<=', Carbon::parse('23:59:59')->toTimeString())
                    ->whereJsonContains('active_days', strtolower($dayNameNow))
            )
            ->when(
                (!$request->has('q') && !($request->user()->type === User::TYPES[User::ADMIN])),
                fn($q) => $q
                    ->whereJsonContains('active_days', strtolower($dayNameNow))
                    ->orWhereTime('time_start', '>=', Carbon::now()->toTimeString())
                    ->orWhereTime('time_end', '<=', Carbon::now()->toTimeString())
            )
            ->orderBy('time_start')
            ->orderBy('time_end');
    }

    private function adminQuery(string $dayNameNow, string $schoolYear)
    {
        return Schedules::query()
            ->join('attendances', 'schedules.id', '=', 'attendances.schedule_id')
            ->join('semesters', 'semesters.id', '=', 'schedules.semester_id')
            ->select([
                DB::raw('COUNT(attendances.id) as attendances_count'),
                'attendances.status'
            ])
            ->whereJsonContains('schedules.active_days', strtolower($dayNameNow))
            ->whereTime('schedules.time_start', '>=', Carbon::now()->toTimeString())
            ->whereTime('schedules.time_end', '<=', Carbon::now()->toTimeString())
            ->where('semesters.academic_year', $schoolYear)
            ->groupBy(['attendances.status'])
            ->get();
    }
}
