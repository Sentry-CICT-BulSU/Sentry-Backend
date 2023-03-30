<?php

namespace App\Http\Controllers\Api;

use App\Actions\Schedules\StoreNewSchedule;
use App\Http\Controllers\Controller;
use App\Models\Schedules;
use App\Http\Requests\Api\Schedules\{
    StoreSchedulesRequest,
    UpdateSchedulesRequest
};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SchedulesController extends Controller
{
    public function index(): JsonResponse
    {
        // $schedule = Schedules::paginate(15);
        $schedule = Schedules::all();
        return response()->json([
            'message' => 'Schedules retrieved successfully',
            'schedules' => $schedule
        ], 200);
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
    public function show(Schedules $schedule): JsonResponse
    {
        $schedule->load([
            'adviser' => fn($q) => $q->withTrashed(),
            'subject',
            'semester',
            'room',
            'section'
        ]);
        return response()->json([
            'message' => 'Schedule retrieved successfully',
            'schedule' => $schedule
        ], 200);
    }
    public function update(
        UpdateSchedulesRequest $request,
        Schedules $schedule
    ): JsonResponse {
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
