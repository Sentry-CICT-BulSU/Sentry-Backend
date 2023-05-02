<?php

namespace App\Http\Controllers\Api;

use App\Actions\Attendances\StoreNewAttendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Attendances\StoreAttendanceRequest;
use App\Http\Requests\Api\Attendances\UpdateAttendanceRequest;
use App\Http\Resources\AttendancesResource;
use App\Models\Attendances;
use App\Models\Schedules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    function __construct()
    {
        $this->middleware('admin')->except(['index', 'show', 'store']);
    }
    public function index(Request $request): JsonResponse
    {
        return AttendancesResource::collection(Attendances::paginate(15))->response();
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
}
