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
                'room' => fn($q) => (match ($request->user()->type) {
                    User::TYPES[User::ADMIN] => $q->withTrashed(),
                default => $q
            })->when(
                        ($request->has('rid') && $request->get('rid') === 'am'),
                        fn($rq) => $rq->where('id', $request->get('rid'))
                    ),
                'adviser' => fn($q) => match ($request->user()->type) {
                    User::TYPES[User::ADMIN] => $q->withTrashed(),
                    default => $q
                },
                'subject' => fn($q) => match ($request->user()->type) {
                    User::TYPES[User::ADMIN] => $q->withTrashed(),
                    default => $q
                },
                'semester' => fn($q) => match ($request->user()->type) {
                    User::TYPES[User::ADMIN] => $q->where('academic_year', $schoolYear)->withTrashed(),
                    default => $q->where('academic_year', $schoolYear)
                },
                'attendances',
            ])
            // when auth user is attendance checker
            ->when(
                (!($request->user()->type === User::TYPES[User::ADMIN]) &&
                    !($request->user()->type === User::TYPES[User::FACULTY])),
                fn($q) => $q->whereJsonContains('active_days', strtolower($dayNameNow))
                    ->whereNot('adviser_id', $request->user()->id)
                    ->has('adviser')
                    ->has('subject')
                    ->has('semester')
                    ->has('section')
                    ->has('room')
            )
            // when auth user is faculllty
            ->when(
                (!($request->user()->type === User::TYPES[User::ADMIN]) &&
                    !($request->user()->type === User::TYPES[User::ATTENDANCE_CHECKER])),
                fn($q) => $q->where('adviser_id', $request->user()->id)
            )
            ->orderBy('time_start')
            ->orderBy('time_end');
    }

    private function adminQuery(string $dayNameNow, string $schoolYear)
    {
        $todayFilter = [
            Carbon::now()->startOfDay()->toDateTimeString(),
            Carbon::now()->endOfDay()->toDateTimeString()
        ];
        $total_schedules = Schedules::query()
            ->with([
                'semester' => fn($q) => $q->where('academic_year', $schoolYear)
            ])
            ->whereJsonContains('schedules.active_days', strtolower($dayNameNow))
            ->has('adviser')
            ->has('subject')
            ->has('semester')
            ->has('section')
            ->has('room')
            ->count();
        $present = Attendances::query()
            ->join('schedules', 'schedules.id', '=', 'attendances.schedule_id')
            ->select('attendances.*')
            ->whereJsonContains('schedules.active_days', strtolower($dayNameNow))
            ->where('attendances.status', Attendances::STATUSES[Attendances::PRESENT])
            ->whereBetween('attendances.created_at', $todayFilter)
            ->has('user')
            ->has('schedule')
            ->count();
        $absent = Attendances::query()
            ->join('schedules', 'schedules.id', '=', 'attendances.schedule_id')
            ->select('attendances.*')
            ->whereJsonContains('schedules.active_days', strtolower($dayNameNow))
            ->where('attendances.status', Attendances::STATUSES[Attendances::ABSENT])
            ->whereBetween('attendances.created_at', $todayFilter)
            ->has('user')
            ->has('schedule')
            ->count();
        $unmarked = $total_schedules - ($present + $absent);
        return response()->json([
            'total' => $total_schedules,
            'presents' => $present,
            'absents' => $absent,
            'not_visited' => $unmarked < 0 ? 0 : $unmarked,
        ]);
    }
}
