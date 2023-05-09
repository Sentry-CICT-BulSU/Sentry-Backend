<?php

namespace App\Actions\Charts;

use App\Models\Attendances;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class FacultyChart
{
    public static function getChart(int $user_id): JsonResponse
    {
        $datePeriod = [
            Carbon::now()->subDays(7)->toDateString(),
            Carbon::now()->toDateString(),
        ];
        $dates = collect(CarbonPeriod::createFromArray($datePeriod)->map(fn($q) => $q->format('Y-m-d')));
        $query = Attendances::query()
            ->select(DB::raw('DATE(created_at) as date_time'), DB::raw('count(*) as count'))
            ->groupBy('date_time')
            ->whereBetween('created_at', $datePeriod)
            ->where('user_id', $user_id);

        $presentees = $query
            ->where('status', Attendances::STATUSES[Attendances::PRESENT])
            ->get()->toArray();
        $absentees = $query
            ->where('status', Attendances::STATUSES[Attendances::ABSENT])
            ->get()->toArray();
        $presentees_filtered = [];
        $absentees_filtered = [];

        foreach ($dates as $k => $date) {
            if (empty($absentees)) {
                $absentees_filtered[$k]['x'] = $date;
                $absentees_filtered[$k]['y'] = 0;
            } else {
                foreach ($absentees as $kk => $v) {
                    $absentees_filtered[$k]['x'] = $date;
                    $absentees_filtered[$k]['y'] = $absentees[$kk]['date_time'] === $date ? $absentees[$kk]['count'] : 0;
                }
            }
            if (empty($presentees)) {
                $presentees_filtered[$k]['x'] = $date;
                $presentees_filtered[$k]['y'] = 0;
            } else {
                foreach ($presentees as $kk => $v) {
                    $presentees_filtered[$k]['x'] = $date;
                    $presentees_filtered[$k]['y'] = $presentees[$kk]['date_time'] == $date ? $presentees[$kk]['count'] : 0;
                }
            }
        }

        return response()->json([
            'presentees' => $presentees_filtered,
            'absentees' => $absentees_filtered,
            'period' => $dates
        ]);
    }
}
