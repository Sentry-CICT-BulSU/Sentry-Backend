<?php

namespace App\Http\Controllers\Api;

use App\Actions\Attendances\AttendancesStatistics;
use App\Actions\Attendances\StoreNewAttendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Attendances\StoreAttendanceRequest;
use App\Http\Requests\Api\Attendances\UpdateAttendanceRequest;
use App\Http\Resources\AttendancesResource;
use App\Http\Resources\SchedulesResource;
use App\Models\Attendances;
use App\Models\Schedules;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    function __construct()
    {
        $this->middleware('admin')->except(['index', 'attendances', 'show', 'store', 'statistics']);
    }
    public function index(Request $request): JsonResponse
    {
        $schoolYear = Carbon::now()->year . '-' . Carbon::now()->addYear()->year;
        $dayNameNow = Carbon::now()->dayName;
        $attendances = Schedules::query()
            ->with([
                'adviser' => fn($q) => $q->when(
                    ($request->has('fid')),
                    fn($qf) => $qf->where('id', $request->get('id'))
                )->withTrashed(),
                'semester' => fn($q) => match (Auth::user()->type) {
                    User::ADMIN => $q->where('academic_year', $schoolYear)->withTrashed(),
                    default => $q->where('academic_year', $schoolYear)
                },
                'attendance' => fn($q) => $q->withTrashed(),
            ])
            ->whereJsonContains('active_days', strtolower($dayNameNow))
            ->orderBy('time_start')
            ->orderBy('time_end')
            // ->when($request->has('q') && $request->q === 'faculty', fn($q) => $q->where('', 'faculty'))
            ->paginate(15);
        return SchedulesResource::collection($attendances)->response();
    }
    public function attendances(User $user): JsonResponse
    {
        return AttendancesResource::collection(
            Attendances::where('user_id', $user->id)->latest()->withTrashed()->paginate(20)
        )->response();
    }
    public function store(Schedules $schedule, StoreAttendanceRequest $request, StoreNewAttendance $storeNewAttendance): AttendancesResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $attendance = $storeNewAttendance->handle($schedule, $request);
            DB::commit();
            return new AttendancesResource($attendance);
        } catch (\Exception $err) {
            DB::rollBack();
            return response()->json([
                'message' => 'Attendance not created',
                'error' => $err->getMessage()
            ], 500);
        }
    }
    public function show(Attendances $attendance): AttendancesResource
    {
        return new AttendancesResource($attendance);
    }
    public function destroy(Attendances $attendance): JsonResponse
    {
        return !$attendance->trashed()
            ? response()->json([
                'message' => 'Attendance deleted successfully',
                'deleted' => $attendance->delete()
            ], 200)
            : response()->json([
                'message' => 'The attendance has already been soft deleted',
            ], 403);
    }
    public function restore($attendance): JsonResponse
    {
        $restore = Attendances::withTrashed()->find($attendance);
        return $restore->trashed()
            ? response()->json([
                'message' => 'Attendance restored successfully',
                'restore' => $restore->restore()
            ], 200)
            : response()->json([
                'message' => 'The attendance has already been restored',
            ], 403);
    }

    public function statistics(AttendancesStatistics $attendancesStatistics): JsonResponse
    {
        return response()->json($attendancesStatistics->handle());
    }
}
