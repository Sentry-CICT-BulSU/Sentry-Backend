<?php

namespace App\Http\Controllers\Api;

use App\Actions\Schedules\StoreNewSchedule;
use App\Http\Controllers\Controller;
use App\Http\Resources\SchedulesResource;
use App\Models\Schedules;
use App\Http\Requests\Api\Schedules\{
    StoreSchedulesRequest,
    UpdateSchedulesRequest
};
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Summary of SchedulesController
 */
class SchedulesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $schoolYear = Carbon::now()->year . '-' . Carbon::now()->addYear()->year;
        $dayNameNow = Carbon::now()->dayName;
        $schedule = Schedules::query()
            ->with([
                'section' => fn($q) => $q->withTrashed(),
                'room' => fn($q) => $q
                    ->when(
                        ($request->has('rid') && $request->get('rid') === 'am'),
                        fn($rq) => $rq->where('id', $request->get('rid'))
                    )->withTrashed(),
                'adviser' => fn($q) => $q->when(($request->has('fid')), fn($qf) => $qf->where('id', $request->get('fid')))->withTrashed(),
                'subject' => fn($q) => $q->withTrashed(),
                'semester' => fn($q) => match (Auth::user()->type) {
                    User::ADMIN => $q->where('academic_year', $schoolYear)->withTrashed(),
                    default => $q->where('academic_year', $schoolYear)
                },
                'attendance' => fn($q) => $q->withTrashed(),
            ])
            ->when(
                ($request->has('q') && $request->get('q') === 'am'),
                fn($q) => $q->whereBetween('time_start', [Carbon::parse('00:00:00'), Carbon::parse('11:59:59')])
            )
            ->when(
                ($request->has('q') && $request->get('q') === 'pm'),
                fn($q) => $q->whereBetween('time_start', [Carbon::parse('12:00:00'), Carbon::parse('23:59:59')])
            )
            ->whereJsonContains('active_days', strtolower($dayNameNow))
            ->orderBy('time_start')
            ->orderBy('time_end')
            // ->when($request->has('q') && $request->q === 'faculty', fn($q) => $q->where('', 'faculty'))
            ->paginate(15);
        return SchedulesResource::collection($schedule)->response();
    }
    public function store(
        StoreSchedulesRequest $request,
        StoreNewSchedule $storeNewSchedule
    ): SchedulesResource|JsonResponse {
        try {
            DB::beginTransaction();
            $schedule = $storeNewSchedule->handle($request);
            DB::commit();
            return new SchedulesResource($schedule);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function show(Schedules $schedule, Request $request): SchedulesResource
    {
        $id = $schedule->semester_id;
        $data = (match ($request->get('type')) {
            'section' => Schedules::where([['section_id', $request->get('id')], ['semester_id', $id]])->get(),
            'room' => Schedules::where([['room_id', $request->get('id')], ['semester_id', $id]])->get(),
            'faculty' => Schedules::where([['adviser_id', $request->get('id')], ['semester_id', $id]])->get(),
            default => $schedule
        });

        return new SchedulesResource($data->load([
            'adviser' => fn($q) => $q->withTrashed(),
            'subject',
            'semester',
            'room',
            'section',
        ]));
    }
    public function update(
        UpdateSchedulesRequest $request,
        Schedules $schedule
    ): SchedulesResource|JsonResponse {
        try {
            DB::beginTransaction();
            $schedule->update($request->validated());
            DB::commit();
            return new SchedulesResource($schedule);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Schedules $schedule): JsonResponse
    {
        return !$schedule->trashed()
            ? response()->json([
                'message' => 'Schedules deleted successfully',
                'deleted' => $schedule->delete()
            ], 200)
            : response()->json([
                'message' => 'The schedule has already been soft deleted',
            ], 403);
    }

    public function restore($schedule): JsonResponse
    {
        $restore = Schedules::withTrashed()->find($schedule);
        return $restore->trashed()
            ? response()->json([
                'message' => 'Schedules restored successfully',
                'restore' => $restore->restore()
            ], 200)
            : response()->json([
                'message' => 'The schedule has already been restored',
            ], 403);
    }
}
