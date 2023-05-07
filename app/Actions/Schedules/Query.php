<?php

namespace App\Actions\Schedules;

use App\Models\Attendances;
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
                'attendances' => fn($q) => $q->whereBetween('created_at', [
                    Carbon::now()->startOfDay()->toDateTimeString(),
                    Carbon::now()->endOfDay()->toDateTimeString()
                ]),
            ])
            ->when(
                ($request->has('q') && $request->get('q') === 'am'),
                fn($q) => $q
                    ->whereBetween('time_start', [Carbon::parse('00:00:00')->toTimeString(), Carbon::parse('11:59:59')->toTimeString()])
                    // ->orWhereBetween('time_end', [Carbon::parse('00:00:00')->toTimeString(), Carbon::parse('11:59:59')->toTimeString()])
                    // ->whereTime('time_start', '>=', Carbon::parse('00:00:00')->toTimeString())
                    // ->orWhereTime('time_end', '<=', Carbon::parse('11:59:59')->toTimeString())
                    ->whereJsonContains('active_days', strtolower($dayNameNow))
            )
            ->when(
                ($request->has('q') && $request->get('q') === 'pm'),
                fn($q) => $q
                    ->whereBetween('time_start', [Carbon::parse('12:00:00')->toTimeString(), Carbon::parse('23:59:59')->toTimeString()])
                    // ->orWhereBetween('time_end', [Carbon::parse('12:00:00')->toTimeString(), Carbon::parse('23:59:59')->toTimeString()])
                    // ->whereTime('time_start', '>=', Carbon::parse('12:00:00')->toTimeString())
                    // ->orWhereTime('time_end', '<=', Carbon::parse('23:59:59')->toTimeString())
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
        $total_schedules = Schedules::query()->withTrashed()
            ->with(['semester' => fn($q) => $q->where('academic_year', $schoolYear)])
            ->whereJsonContains('active_days', strtolower($dayNameNow))
            ->count();
        $present = Attendances::query()
            ->with([
                'schedule' =>
                fn($q) => $q->whereJsonContains('active_days', strtolower($dayNameNow))
            ])
            ->where('status', Attendances::STATUSES[Attendances::PRESENT])
            ->count();
        $absent = Attendances::query()
            ->with([
                'schedule' =>
                fn($q) => $q->whereJsonContains('active_days', strtolower($dayNameNow))
            ])
            ->where('status', Attendances::STATUSES[Attendances::ABSENT])
            ->count();
        $unmarked = $total_schedules - ($present + $absent);
        return response()->json([
            'presents' => $present,
            'absents' => $absent,
            'not_visited' => $unmarked,
        ]);
    }
}
