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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Summary of SchedulesController
 */
class SchedulesController extends Controller
{
    function __construct()
    {
        $this->middleware('admin');
    }
    public function index(): JsonResponse
    {
        $schedule = Schedules::paginate(15);
        return SchedulesResource::collection($schedule)->response();
    }
    public function store(
        StoreSchedulesRequest $request,
        StoreNewSchedule $storeNewSchedule
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $schedule = $storeNewSchedule->handle($request);
            DB::commit();
            return response()->json([
                'message' => 'Schedule created successfully',
                'schedule' => $schedule
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function show(Schedules $schedule): SchedulesResource
    {
        $schedule->load([
            'adviser' => fn($q) => $q->withTrashed(),
            'subject',
            'semester',
            'room',
            'section'
        ]);
        return new SchedulesResource($schedule);
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
