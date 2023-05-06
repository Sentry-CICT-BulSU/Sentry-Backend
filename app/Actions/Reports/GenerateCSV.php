<?php

namespace App\Actions\Reports;

use App\Exports\CSVExport;
use App\Models\Attendances;
use App\Models\RoomKeyLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GenerateCSV extends QueryReports
{
    public function handle(
        Request $request
    ): mixed {
        $this->loadHeadings($request->get('type'));
        $report = (new CSVExport($this->query($request), $this->headings));
        return $request->has('preview')
            ? [
                'data' => $this->query($request)->get()->take(10),
                'type' => $request->get('type')
            ]
            : match (config('excel.defaults.export_type')) {
                'xlsx' => $report->download(
                    'report.xlsx',
                    Excel::XLSX,
                    ['Content-Type' => 'text/xlsx']
                ),
                default => $report->download(
                    'report.csv',
                    Excel::CSV,
                    ['Content-Type' => 'text/csv']
                ),
            };
    }
}
