<?php

namespace App\Http\Controllers\Api;

use App\Actions\Subjects\StoreNewSubject;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectsResource;
use App\Models\Subjects;
use App\Http\Requests\Api\Subjects\{
    StoreSubjectsRequest,
    UpdateSubjectsRequest
};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SubjectsController extends Controller
{
    public function index(): JsonResponse
    {
        $subjects = Subjects::paginate(15);
        return SubjectsResource::collection($subjects)->response();
    }
    public function store(StoreSubjectsRequest $request, StoreNewSubject $storeNewSubject): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subject = $storeNewSubject->handle($request);
            DB::commit();
            return response()->json([
                'message' => 'Subject created successfully',
                'subject' => $subject
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show(Subjects $subject): SubjectsResource
    {
        return new SubjectsResource($subject);

    }
    public function update(UpdateSubjectsRequest $request, Subjects $subject): SubjectsResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $subject->update($request->validated());
            DB::commit();
            return new SubjectsResource($subject);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Subject update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Subjects $subject): JsonResponse
    {
        return !$subject->trashed()
            ? response()->json([
                'message' => 'The subject has already been soft deleted',
                'deleted' => $subject->delete()
            ], 200) : response()->json([
                'message' => 'The room has already been restored',
            ], 403);
    }
    public function restore($subject): JsonResponse
    {
        $restore = Subjects::withTrashed()->find($subject);
        return $restore->trashed()
            ? response()->json([
                'message' => 'Subject restored successfully',
                'restore' => $restore->restore()
            ], 200)
            : response()->json([
                'message' => 'The subject has already been restored',
            ], 403);
    }
}
