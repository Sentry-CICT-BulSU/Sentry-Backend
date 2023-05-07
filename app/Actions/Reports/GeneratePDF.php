<?php

namespace App\Actions\Reports;

use App\Exports\PDFExport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GeneratePDF extends QueryReports
{
    // private $filename = 'report_' . Str::slug(Carbon::now()->toString(), '_') . '.pdf';
    public function handle(Request $request)
    {
        $filename = 'report_' . Str::slug(Carbon::now()->toString(), '_') . '.pdf';

        return response($this->loadPDF($request), 200)
            ->withHeaders([
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        // return $this->loadPDF($request);
    }
    public function loadPDF(Request $request)
    {
        $reports = $this->query($request)->get();

        $headings = $this->pdfHeadings($request->get('type'));
        $view = $this->pdfView($request->get('type'));
        $pdf = Pdf::loadHTML(
            $view
                ->with([
                    'reports' => $reports,
                    'headings' => $headings
                ])
                ->render()
        );

        return $pdf
            ->setPaper('legal', 'landscape')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ])
            ->stream();
        // return $view->with([
        //     'reports' => $reports,
        //     'headings' => $headings
        // ]);
    }
    private function pdfView(string $type)
    {
        return match ($type) {
            'attendance' => view('pdf.Attendances.index'),
            'room-keys' => view('pdf.RoomKeyLogs.index'),
        };
    }
    private function pdfHeadings(string $type)
    {
        $headingsAttendance = [
            'Semester',
            'Academic Year',
            'First name',
            'Last name',
            'Email',
            'Attendance',
            'Created on',
            'Room',
            'Subject Code',
            'Subject Title',
        ];
        $headingsRoomKeyLogs = [
            'Room',
            'Status',
            'First name',
            'Last name',
            'Created on',
        ];
        return match ($type) {
            'attendance' => $headingsAttendance,
            'room-keys' => $headingsRoomKeyLogs,
        };
    }
}
