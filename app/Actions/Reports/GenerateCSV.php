<?php

namespace App\Actions\Reports;

use App\Exports\CSVExport;
use App\Models\Attendances;
use App\Models\RoomKeyLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateCSV
{
    public function handle(Request $request): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return (new CSVExport(match ($request->get('type')) {
            'attendance' => Attendances::query()
                ->when(($request->has('time')), fn($q) => $q->whereBetween('created_at', $this->filterByTime($request->get('time')))),
            'room-keys' => RoomKeyLogs::query()
                ->when(($request->has('time')), fn($q) => $q->whereBetween('created_at', $this->filterByTime($request->get('time')))),
            default => throw new \Exception('Type filter exception', 403),
        }))->download('report.csv');
    }

    private function filterByTime(string $filter)
    {
        $todayFilter = [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()];
        $thisWeekFilter = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $thisMonthFilter = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        $thisYearFilter = [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
        $schoolYearFillter = [Carbon::now()->startOfYear(), Carbon::now()->addYear()->endOfYear()];

        return match ($filter) {
            'today' => $todayFilter,
            'this-week' => $thisWeekFilter,
            'this-month' => $thisMonthFilter,
            'this-year' => $thisYearFilter,
            'school-year' => $schoolYearFillter,
            default => throw new \Exception('Time filter exception', 403),
        };
    }
}
