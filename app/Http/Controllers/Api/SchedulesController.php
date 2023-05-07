<?php

namespace App\Http\Controllers\Api;

use App\Actions\Schedules\Query;
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
    public function index(Request $request, Query $query): JsonResponse
    {
        $schedule = $query->handle($request);
        return match ($request->has('admin-dash')) {
            true => $schedule,
            default => SchedulesResource::collection($schedule->paginate(15))->response(),
        };
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
