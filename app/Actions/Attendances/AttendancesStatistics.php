<?php

namespace App\Actions\Attendances;

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
        $scheduleQuery = Schedules::query()
            ->with([
                'adviser' => fn($q) => $q->withTrashed(),
                'semester' => fn($q) => match (Auth::user()->type) {
                    User::ADMIN => $q->where('academic_year', $schoolYear)->withTrashed(),
                    default => $q->where('academic_year', $schoolYear)
                },
                'attendance' => fn($q) => $q->withTrashed(),
            ])
            ->whereJsonContains('active_days', strtolower($dayNameNow))
            ->orderBy('time_start')
            ->orderBy('time_end')
            ->get();

        $total = $scheduleQuery->count();
        $monitored = $scheduleQuery->pluck('attendance')->count();

        // return ['schedules_count' => $total, 'attendances' => $monitored];
        if ($total === 0) {
            return [
                'availabe' => 0,
                'availabe_percentage' => 0,
                'unavailable' => 0,
                'unavailable_percentage' => 0,
                'total_keys' => 0,
            ];
        }
        $unmonitored = $total - $monitored;
        $decrease = (($total - $unmonitored) / $total) * 100;
        $increase = ($unmonitored / $total) * 100;

        return [
            'monitored' => $monitored,
            'monitored_percentage' => $decrease,
            'unmonitored' => $unmonitored,
            'unmonitored_percentage' => $increase,
            'total_attendance' => $total,
        ];
    }
}
