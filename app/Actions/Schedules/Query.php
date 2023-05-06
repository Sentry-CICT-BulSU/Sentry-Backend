<?php

namespace App\Actions\Schedules;

use App\Models\Schedules;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Query
{
    public function handle(Request $request)
    {
        $schoolYear = Carbon::now()->year . '-' . Carbon::now()->addYear()->year;
        $dayNameNow = Carbon::now()->dayName;
        return $request->has('admin-dash')
            ? $schedule = Schedules::query()
            ->withCount(['attendance as present_count' => fn ($q) => $q->where('status', 'present')])
            ->withCount(['attendance as absent_count' => fn ($q) => $q->where('status', 'absent')])
            ->withCount(['attendance as total_count'])
            ->whereJsonContains('active_days', strtolower($dayNameNow))
            ->whereTime('time_start', '>=', Carbon::now()->toTimeString())
            ->whereTime('time_end', '<=', Carbon::now()->toTimeString())
            ->orderBy('time_start')
            ->orderBy('time_end')
            :  $schedule = Schedules::query()
            ->with([
                'section' => fn ($q) => $q->withTrashed(),
                'room' => fn ($q) => $q
                    ->when(
                        ($request->has('rid') && $request->get('rid') === 'am'),
                        fn ($rq) => $rq->where('id', $request->get('rid'))
                    )->withTrashed(),
                'adviser' => fn ($q) => $q->when(($request->has('fid')), fn ($qf) => $qf->where('id', $request->get('fid')))->withTrashed(),
                'subject' => fn ($q) => $q->withTrashed(),
                'semester' => fn ($q) => match (Auth::user()->type) {
                    User::ADMIN => $q->where('academic_year', $schoolYear)->withTrashed(),
                    default => $q->where('academic_year', $schoolYear)
                },
                'attendance' => fn ($q) => $q->withTrashed(),
            ])
            ->when(
                ($request->has('q') && $request->get('q') === 'am'),
                fn ($q) => $q //->whereBetween('time_start', [Carbon::parse('00:00:00')->toTimeString(), Carbon::parse('11:59:59')->toTimeString()])
                    ->whereTime('time_start', '>=', Carbon::parse('00:00:00')->toTimeString())
                    ->whereTime('time_end', '<=', Carbon::parse('11:59:59')->toTimeString())
                    ->whereJsonContains('active_days', strtolower($dayNameNow))
            )
            ->when(
                ($request->has('q') && $request->get('q') === 'pm'),
                fn ($q) => $q //->whereBetween('time_start', [Carbon::parse('12:00:00')->toTimeString(), Carbon::parse('23:59:59')->toTimeString()])
                    ->whereTime('time_start', '>=', Carbon::parse('12:00:00')->toTimeString())
                    ->whereTime('time_end', '<=', Carbon::parse('23:59:59')->toTimeString())
                    ->whereJsonContains('active_days', strtolower($dayNameNow))
            )
            ->when(
                (!$request->has('q') && Auth::user()->type !== User::TYPES[User::ADMIN]),
                fn ($q) => $q
                    ->whereJsonContains('active_days', strtolower($dayNameNow))
                    ->whereTime('time_start', '>=', Carbon::now()->toTimeString())
                    ->whereTime('time_end', '<=', Carbon::now()->toTimeString())
            )
            ->orderBy('time_start')
            ->orderBy('time_end');
    }
}
