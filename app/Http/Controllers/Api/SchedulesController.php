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
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
    public function index(): AnonymousResourceCollection
    {
        $schedule = Schedules::paginate(15);
        // $schedule = Schedules::all();
        return SchedulesResource::collection($schedule);
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
    ): SchedulesResource {
        //
    }
    public function destroy(Schedules $schedule): JsonResponse
    {
        //
    }
    public function restore(Schedules $schedule): JsonResponse
    {
        //
    }
}
