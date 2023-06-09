<?php

namespace App\Actions\Reports;

use App\Models\Attendances;
use App\Models\RoomKeyLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QueryReports
{
    public $headings;
    public function loadHeadings(string $type)
    {
        $this->headings = match ($type) {
            'attendance' => $this->headings = [
                'semesters.name',
                'semesters.academic_year',
                'users.first_name',
                'users.last_name',
                'users.email',
                'attendances.status',
                'attendances.created_at',
                'rooms.name',
                'subjects.code',
                'subjects.title',
            ],
            'room-keys' => $this->headings = [
                'rooms.name',
                'room_key_logs.status',
                'users.first_name',
                'users.last_name',
                'room_key_logs.created_at',
            ],
            default => throw new \Exception('Type filter exception', 403),
        };
    }
    public function query(Request $request)
    {
        try {
            return match ($request->get('type')) {
                'attendance' => Attendances::query()
                    ->join('schedules', 'schedules.id', '=', 'attendances.schedule_id')
                    ->join('semesters', 'semesters.id', '=', 'schedules.semester_id')
                    ->join('subjects', 'subjects.id', '=', 'schedules.subject_id')
                    ->join('rooms', 'rooms.id', '=', 'schedules.room_id')
                    ->join('users', 'users.id', '=', 'attendances.user_id')
                    ->select([
                        'semesters.name as sem_name',
                        'semesters.academic_year',
                        'users.first_name',
                        'users.last_name',
                        'users.email',
                        'attendances.status',
                        'attendances.created_at',
                        'rooms.name as room_name',
                        'subjects.code',
                        'subjects.title',
                    ])
                    ->when(($request->has('time')), fn($q) => $q->whereBetween('attendances.created_at', $this->filterByTime($request->get('time'))))
                    ->withTrashed(),
                'room-keys' => RoomKeyLogs::query()
                    ->join('rooms', 'rooms.id', '=', 'room_key_logs.room_key_id')
                    ->join('users', 'users.id', '=', 'room_key_logs.faculty_id')
                    ->select([
                        'rooms.name',
                        'room_key_logs.status',
                        'users.first_name',
                        'users.last_name',
                        'room_key_logs.created_at',
                    ])
                    ->when(($request->has('time')), fn($q) => $q->whereBetween('room_key_logs.created_at', $this->filterByTime($request->get('time'))))
                    ->withTrashed(),
                default => throw new \Exception('Type filter exception', 403),
            };
        } catch (\Exception $err) {
            throw $err;
        }
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
