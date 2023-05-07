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
            ->whereBetween('created_at', $todayFilter)
            ->count();
        $absent = Attendances::query()
            ->with([
                'schedule' =>
                fn($q) => $q->whereJsonContains('active_days', strtolower($dayNameNow))
            ])
            ->where('status', Attendances::STATUSES[Attendances::ABSENT])
            ->whereBetween('created_at', $todayFilter)
            ->count();
        // dd($present, $absent, $total_schedules);
        $monitored = $absent + $present;
        $unmarked = $total_schedules - $monitored;
        // return ['schedules_count' => $total, 'attendances' => $monitored];
        if ($total_schedules === 0) {
            return [
                'availabe' => 0,
                'availabe_percentage' => 0,
                'unavailable' => 0,
                'unavailable_percentage' => 0,
                'total_keys' => 0,
            ];
        }
        $decrease = (($total_schedules - $unmarked) / $total_schedules) * 100;
        $increase = ($unmarked / $total_schedules) * 100;

        return [
            'monitored' => $monitored,
            'monitored_percentage' => number_format($decrease),
            'unmonitored' => $unmarked,
            'unmonitored_percentage' => number_format($increase),
            'total_attendance' => $total_schedules,
        ];
    }
}
