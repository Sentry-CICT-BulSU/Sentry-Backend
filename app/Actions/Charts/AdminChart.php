<?php

namespace App\Actions\Charts;

use App\Models\Attendances;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminChart
{
    private static function query(array $datePeriod)
    {
        return Attendances::query()
            ->select(DB::raw('DATE(created_at) as x'), DB::raw('count(*) as y'))
            ->groupBy('x')
            ->whereBetween('created_at', $datePeriod);
    }
    public static function getChart(): JsonResponse
    {
        $datePeriod = [
            Carbon::now()->subDays(7),
            Carbon::now(),
        ];
        $dates = collect(CarbonPeriod::createFromArray($datePeriod)->map(fn($q) => $q->toDateString()));

        $presentees = self::query($datePeriod)
            ->where('status', Attendances::STATUSES[Attendances::PRESENT])
            ->get();

        $absentees = self::query($datePeriod)
            ->where('status', Attendances::STATUSES[Attendances::ABSENT])
            ->get();

        $present_array = $dates->map(function ($e) use ($presentees) {
            $date = Carbon::parse($e)->format('D, M d');
            $present = $presentees->firstWhere('x', $e);
            return $present ? ['x' => $date, 'y' => $present->y] : ['x' => $date, 'y' => 0];
        })->toArray();
        $absent_array = $dates->map(function ($e) use ($absentees) {
            $date = Carbon::parse($e)->format('D, M d');
            $absent = $absentees->firstWhere('x', $e);
            return $absent ? ['x' => $date, 'y' => $absent->y] : ['x' => $date, 'y' => 0];
        })->toArray();

        return response()->json([
            'presentees' => $present_array,
            'absentees' => $absent_array,
            'period' => $dates->map(fn($e) => Carbon::parse($e)->format('D, M d'))
        ]);
    }
}
