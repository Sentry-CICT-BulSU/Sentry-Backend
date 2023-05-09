<?php

namespace App\Actions\Attendances;

use App\Models\Attendances;
use App\Models\Schedules;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendancesStatistics
{
    public function handle(): array
    {
        $schoolYear = Carbon::now()->year . '-' . Carbon::now()->addYear()->year;
        $dayNameNow = Carbon::now()->dayName;
        $todayFilter = [
            Carbon::now()->startOfDay()->toDateTimeString(),
            Carbon::now()->endOfDay()->toDateTimeString()
        ];
        $total_schedules = Schedules::query()
            ->join('semesters', 'semesters.id', '=', 'schedules.semester_id')
            ->where('semesters.academic_year', $schoolYear)
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
        // dd($present, $absent, $total_schedules);
        $monitored = $absent + $present;
        $unmarked = $total_schedules - $monitored;
        // return ['schedules_count' => $total, 'attendances' => $monitored];
        if ($total_schedules === 0) {
            return [
                'monitored' => 0,
                'monitored_percentage' => 0,
                'unmonitored' => 0,
                'unmonitored_percentage' => 0,
                'total_attendance' => 0,
            ];
        }
        $decrease = (($total_schedules - $unmarked) / $total_schedules) * 100;
        $increase = ($unmarked / $total_schedules) * 100;

        return [
            'monitored' => $monitored,
            'monitored_percentage' => number_format($decrease),
            'unmonitored' => $unmarked < 0 ? 0 : $unmarked,
            'unmonitored_percentage' => number_format($increase),
            'total_attendance' => $total_schedules,
        ];
    }
}
